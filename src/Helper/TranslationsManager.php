<?php

namespace TwinElements\AdminBundle\Helper;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Translation\Dumper\YamlFileDumper;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Translation\Writer\TranslationWriter;

class TranslationsManager
{
    /**
     * @var string $translationsPath
     */
    private $translationsPath;

    /**
     * @var string $defaultLocale
     */
    private $defaultLocale;

    /**
     * @var array $locales
     */
    private $locales;

    public function __construct(ParameterBagInterface $parameterBag, RequestStack $requestStack)
    {
        $this->translationsPath = $parameterBag->get('translator.default_path');
        $this->defaultLocale = $requestStack->getCurrentRequest()->getDefaultLocale();
        $this->locales = explode('|', $parameterBag->get('app_locales'));
    }

    /**
     * @return string
     */
    public function getDefaultLocale(): string
    {
        return $this->defaultLocale;
    }

    /**
     * @param string $catalog
     * @param string $locale
     * @return string
     */
    public function getCatalogPath(string $catalog, string $locale): string
    {
        return $this->translationsPath . '/' . $catalog . '.' . $locale . '.yaml';
    }

    /**
     * @param string $catalog
     * @param string $locale
     * @return array
     */
    public function getTranslations(string $catalog, string $locale): array
    {
        $yamlLoader = new YamlFileLoader();
        $filesystem = new Filesystem();

        if ($locale === $this->defaultLocale) {
            return $yamlLoader->load($this->getCatalogPath($catalog, $locale), $locale, $catalog)->all($catalog);
        } else {
            if (!$filesystem->exists($this->getCatalogPath($catalog, $locale))) {
                $this->createCatalog($catalog, $locale);
            }
            $keys = array_keys($yamlLoader->load($this->getCatalogPath($catalog, $this->defaultLocale), $this->defaultLocale, $catalog)->all($catalog));
            $yml = $yamlLoader->load($this->getCatalogPath($catalog, $locale), $locale, $catalog)->all($catalog);
            $translations = [];
            foreach ($keys as $key) {
                $translations[$key] = (array_key_exists($key, $yml) ? $yml[$key] : null);
            }

            return $translations;
        }
    }

    /**
     * @param string $key
     * @param string $catalog
     * @return array
     */
    public function getKeyTranslations(string $key, string $catalog): array
    {

        $translations = [];
        foreach ($this->locales as $locale) {
            $translations[$locale] = $this->getKeyTranslation($key, $catalog, $locale);
        }

        return $translations;
    }

    /**
     * @param string $key
     * @param string $catalog
     * @param string $locale
     * @return string|null
     */
    public function getKeyTranslation(string $key, string $catalog, string $locale): ?string
    {
        $yamlLoader = new YamlFileLoader();
        return ($yamlLoader->load($this->getCatalogPath($catalog, $locale), $locale, $catalog)->has($key, $catalog) ? $yamlLoader->load($this->getCatalogPath($catalog, $locale), $locale, $catalog)->get($key, $catalog) : null);
    }

    /**
     * @param string $key
     * @param string $catalog
     * @param array $values
     */
    public function updateKeyTranslations(string $key, string $catalog, array $values): void
    {
        $translationWriter = new TranslationWriter();
        $translationWriter->addDumper('yaml', new YamlFileDumper('yaml'));

        foreach ($values as $locale => $value) {
            $messageCatalogue = $this->getCatalog($catalog, $locale);
            $messageCatalogue->set($key, (string)$value, $catalog);
            $translationWriter->write($messageCatalogue, 'yaml', [
                'path' => $this->translationsPath,
                'as_tree' => true
            ]);
        }
    }

    /**
     * @param string $catalog
     * @param string $locale
     * @return MessageCatalogue
     */
    public function getCatalog(string $catalog, string $locale): MessageCatalogue
    {
        $yamlLoader = new YamlFileLoader();
        return $yamlLoader->load($this->getCatalogPath($catalog, $locale), $locale, $catalog);
    }

    /**
     * @param string $catalogName
     * @param string $language
     * @return MessageCatalogue
     */
    public function createCatalog(string $catalogName, string $language): MessageCatalogue
    {
        $catalog = new MessageCatalogue($language, [$catalogName => null]);

        $translationWriter = new TranslationWriter();
        $translationWriter->addDumper('yaml', new YamlFileDumper('yaml'));
        $translationWriter->write($catalog, 'yaml', [
            'path' => $this->translationsPath,
            'as_tree' => true
        ]);

        return $catalog;
    }
}
