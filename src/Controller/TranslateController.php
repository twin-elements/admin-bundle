<?php

namespace TwinElements\AdminBundle\Controller;

use TwinElements\AdminBundle\Helper\Breadcrumbs;
use TwinElements\AdminBundle\Helper\Flashes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use TwinElements\AdminBundle\Helper\TranslationsManager;
use TwinElements\FormExtensions\Type\SaveButtonsType;

class TranslateController extends AbstractController
{
    /**
     * @var TranslationsManager $translationsManager
     */
    private $translationsManager;

    public function __construct(TranslationsManager $translationsManager)
    {
        $this->translationsManager = $translationsManager;
    }

    /**
     * @Route("/dictionary/{category}", name="dictionary_main")
     */
    public function dictionaryMain(Request $request, $category, Breadcrumbs $breadcrumbs)
    {
        $breadcrumbs->setItems([
            'admin.' . $category => null
        ]);

        return $this->render('@TwinElementsAdmin/translations/index.html.twig', [
            'category' => $category,
            'translations' => $this->translationsManager->getTranslations($category, $request->getLocale())
        ]);
    }

    /**
     * @Route("/dictionary/{category}/{key}", name="dictionary_key_edit")
     */
    public function settingsEditKey($category, $key, Request $request, Breadcrumbs $breadcrumbs, Flashes $flashes)
    {
        $formBuilder = $this->createFormBuilder();

        foreach ($this->translationsManager->getKeyTranslations($key, $category) as $locale => $keyTranslation) {
            $formBuilder->add($locale, TextareaType::class, [
                'required' => false,
                'data' => $keyTranslation,
                'attr' => [
                    'placeholder' => $this->translationsManager->getKeyTranslation($key, $category, $this->translationsManager->getDefaultLocale()),
                    'rows' => 4
                ]
            ]);
        }

        $formBuilder->add('save', SaveButtonsType::class);
        $form = $formBuilder->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $this->translationsManager->updateKeyTranslations($key, $category, $data);

            $flashes->successMessage();

            if ('save' === $form->getClickedButton()->getName()) {
                return $this->redirectToRoute('dictionary_key_edit', array('category' => $category, 'key' => $key));
            } else {
                return $this->redirectToRoute('dictionary_main', array('category' => $category));
            }
        }

        $breadcrumbs->setItems([
            'admin.' . $category => $this->generateUrl('dictionary_main', [
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
}
