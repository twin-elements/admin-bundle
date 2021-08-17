<?php

namespace TwinElements\AdminBundle\Controller;

use TwinElements\AdminBundle\Helper\Breadcrumbs;
use TwinElements\AdminBundle\Helper\Flashes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\Dumper\YamlFileDumper;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Translation\Writer\TranslationWriter;
use TwinElements\FormExtensions\Type\SaveButtonsType;


class TranslateController extends AbstractController
{
    private $defaultTranslations = [];
    private $languagesWithoutBasic = [];
    private $languages = [];
    private $category;

    public function __construct(RequestStack $requestStack, $locales)
    {
        $request = $requestStack->getCurrentRequest();

        $this->languages = explode('|', $locales);
        $languagesWithoutBasic = $this->languages;
        foreach ($languagesWithoutBasic as $itemKey => $itemValue) {
            if ($itemValue == $request->getDefaultLocale()) {
                unset($languagesWithoutBasic[$itemKey]);
            }
        }
        $this->languagesWithoutBasic = $languagesWithoutBasic;
    }

    /**
     * @Route("/dictionary/{category}", name="dictionary_main")
     */
    public function dictionaryMain(Request $request, $category, Breadcrumbs $breadcrumbs)
    {
        $this->category = $category;

        $yamlLoader = new YamlFileLoader();
        $filesystem = new Filesystem();


        $translationsPaths = [];
        $translationsFiles = [];

        foreach ($this->languages as $language) {
            $translationsPaths[$language] = $this->getParameter('translator.default_path') . '/' . $this->category . '.' . $language . '.yaml';
            if (!$filesystem->exists($translationsPaths[$language])) {
                $translationsFiles[$language] = $this->createNewCatalogue($language);
            }

            $translationsFiles[$language] = $yamlLoader->load($translationsPaths[$language], $language, $this->category);
        }

//        $this->syncCatalogues($translationsFiles, $request);

        $yml = $yamlLoader->load($translationsPaths[$request->getLocale()], $request->getLocale(), $this->category)->all($this->category);
        $keys = array_keys($yamlLoader->load($translationsPaths[$request->getDefaultLocale()], $request->getDefaultLocale(), $this->category)->all($this->category));

        $breadcrumbs->setItems([
            'cms.'.$category => null
        ]);
        return $this->render('@TwinElementsAdmin/translations/index.html.twig', [
            'category' => $category,
            'translations' => $yml,
            'keys' => $keys
        ]);
    }

    /**
     * @Route("/dictionary/{category}/{key}", name="dictionary_key_edit")
     */
    public function settingsEditKey($category, $key, Request $request, Breadcrumbs $breadcrumbs, Flashes $flashes)
    {
        $this->category = $category;

        $yamlLoader = new YamlFileLoader();

        $formBuilder = $this->createFormBuilder();

        $translationsPaths = [];
        $translationsFiles = [];
        foreach ($this->languages as $language) {
            $translationsPaths[$language] = $this->getParameter('translator.default_path') . '/'.$this->category.'.' . $language . '.yaml';
            $translationsFiles[$language] = $yamlLoader->load($translationsPaths[$language], $language, $this->category);
            $value = $translationsFiles[$language]->get($key, $this->category);

            $formBuilder->add($language, TextareaType::class, [
                'required' => false,
                'data' => $translationsFiles[$language]->has($key, $this->category) ? $translationsFiles[$language]->get($key, $this->category) : null,
                'attr' => [
                    'placeholder' => $translationsFiles[$request->getDefaultLocale()]->get($key, $this->category),
                    'rows' => 4
                ]
            ]);
            unset($value);
        }

        $formBuilder->add('save', SaveButtonsType::class);
        $form = $formBuilder->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $translationWriter = new TranslationWriter();
            $translationWriter->addDumper('yaml', new YamlFileDumper('yaml'));

            foreach ($data as $language => $value) {
                $translationsFiles[$language]->set($key, (string)$value, $this->category);
                $translationWriter->write($translationsFiles[$language], 'yaml', [
                    'path' => $this->getParameter('translator.default_path'),
                    'as_tree' => true
                ]);
            }

            $flashes->successMessage();

            if ('save' === $form->getClickedButton()->getName()) {
                return $this->redirectToRoute('dictionary_key_edit', array('category' => $category, 'key' => $key));
            } else {
                return $this->redirectToRoute('dictionary_main', array('category' => $category));
            }

        }

        $breadcrumbs->setItems([
            'cms.'.$category => $this->generateUrl('dictionary_main', [
                'category' => $category
            ]),
            $key => null
        ]);

        return $this->render('@TwinElementsAdmin/translations/edit.html.twig', [
            'category' => $category,
            'key' => $key,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/update-translations", name="translations_update")
     */
    public function updateTranslations(Request $request, Flashes $flashes, KernelInterface $kernel)
    {
        $cacheDir = $kernel->getCacheDir();

        $translationsPath = $cacheDir . '/translations';

        $files = glob($cacheDir . '/translations/*');

        foreach ($files as $file) {
            unlink($file);
        }

        rmdir($translationsPath);

        $flashes->successMessage('Tłmaczenia zostały zaktualizowane');

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }

    private function createNewCatalogue(string $language)
    {
        $catalogue = new MessageCatalogue($language, [$this->category => null]);

        $translationWriter = new TranslationWriter();
        $translationWriter->addDumper('yaml', new YamlFileDumper('yaml'));
        $translationWriter->write($catalogue, 'yaml', [
            'path' => $this->getParameter('translator.default_path'),
            'as_tree' => true
        ]);

        return $catalogue;
    }

    private function syncCatalogues($translationsFiles, Request $request)
    {
        $needSync = false;
        $cataloguesToUpdate = [];

        foreach ($translationsFiles[$request->getDefaultLocale()]->all($this->category) as $basicKey => $basicValue) {
            foreach ($this->languagesWithoutBasic as $language) {

                if (!$translationsFiles[$language] instanceof MessageCatalogue) {
                    break;
                }
                if (!$translationsFiles[$language]->has($basicKey, $this->category)) {
                    $needSync = true;
                    $cataloguesToUpdate[$language] = $language;
                    $translationsFiles[$language]->set($basicKey, '', $this->category);
                }
            }
        }

        if ($needSync) {
            $translationWriter = new TranslationWriter();
            $translationWriter->addDumper('yaml', new YamlFileDumper('yaml'));
            foreach ($cataloguesToUpdate as $updatedCatalogueLanguage) {
                $translationWriter->write($translationsFiles[$updatedCatalogueLanguage], 'yaml', [
                    'path' => $this->getParameter('translator.default_path'),
                    'as_tree' => true
                ]);
            }
        }
    }
}
