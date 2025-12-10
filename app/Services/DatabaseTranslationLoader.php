<?php

namespace App\Services;

use App\Models\Translation;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\LoaderInterface;

class DatabaseTranslationLoader implements LoaderInterface
{
    protected FileLoader $fileLoader;

    public function __construct(FileLoader $fileLoader)
    {
        $this->fileLoader = $fileLoader;
    }

    /**
     * Load the messages for the given locale and group.
     *
     * @param  string  $locale
     * @param  string  $group
     * @param  string|null  $namespace
     * @return array
     */
    public function load($locale, $group, $namespace = null)
    {
        // First, load translations from files
        $fileTranslations = $this->fileLoader->load($locale, $group, $namespace);

        // Then, load translations from database
        try {
            $databaseTranslations = Translation::getTranslations($locale, $group);

            // Merge database translations with file translations
            // Database translations take precedence over file translations
            return array_merge($fileTranslations, $databaseTranslations);
        } catch (\Exception $e) {
            // If database is not available or table doesn't exist, return file translations
            return $fileTranslations;
        }
    }

    /**
     * Add a new namespace to the loader.
     *
     * @param  string  $namespace
     * @param  string  $hint
     * @return void
     */
    public function addNamespace($namespace, $hint)
    {
        $this->fileLoader->addNamespace($namespace, $hint);
    }

    /**
     * Add a new JSON path to the loader.
     *
     * @param  string  $path
     * @return void
     */
    public function addJsonPath($path)
    {
        $this->fileLoader->addJsonPath($path);
    }

    /**
     * Get an array of all the registered namespaces.
     *
     * @return array
     */
    public function namespaces()
    {
        return $this->fileLoader->namespaces();
    }
}
