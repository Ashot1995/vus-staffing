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
        // Load translations from files (primary source)
        $fileTranslations = $this->fileLoader->load($locale, $group, $namespace);
        
        // Load translations from database and merge (database takes precedence)
        if ($group && !$namespace) {
            try {
                $dbTranslations = Translation::getTranslations($locale, $group);
                
                if (!empty($dbTranslations)) {
                    // Convert flat dot notation to nested array
                    $dbNested = $this->convertToNestedArray($dbTranslations);
                    
                    // Merge database translations over file translations
                    $fileTranslations = $this->arrayMergeRecursiveDistinct($fileTranslations, $dbNested);
                }
            } catch (\Exception $e) {
                // If database fails, just return file translations
                // This prevents errors during migrations or when table doesn't exist
            }
        }
        
        return $fileTranslations;
    }

    /**
     * Convert flat array with dot notation keys to nested array
     * Example: ['home.professional_service.description' => 'value'] 
     * becomes: ['home' => ['professional_service' => ['description' => 'value']]]
     */
    protected function convertToNestedArray(array $flatArray): array
    {
        $nested = [];
        
        foreach ($flatArray as $key => $value) {
            $keys = explode('.', $key);
            $current = &$nested;
            
            foreach ($keys as $k) {
                if (!isset($current[$k]) || !is_array($current[$k])) {
                    $current[$k] = [];
                }
                $current = &$current[$k];
            }
            
            $current = $value;
        }
        
        return $nested;
    }

    /**
     * Recursively merge arrays, with second array values taking precedence
     */
    protected function arrayMergeRecursiveDistinct(array $array1, array $array2): array
    {
        $merged = $array1;
        
        foreach ($array2 as $key => $value) {
            if (isset($merged[$key]) && is_array($merged[$key]) && is_array($value)) {
                $merged[$key] = $this->arrayMergeRecursiveDistinct($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }
        
        return $merged;
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

