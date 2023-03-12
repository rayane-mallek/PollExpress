<?php

namespace Framework\Service;

use Symfony\Component\Yaml\Yaml;

class Translator
{
    private string $defaultLanguage;
    private array $translations;

    public function __construct(string $defaultLanguage, string $translations)
    {
        $this->defaultLanguage = $defaultLanguage;
        $this->translations = Yaml::parseFile($translations);
    }

    public function getTranslation(string $key, string $language = null): string
    {
        $language = $language ?? $this->defaultLanguage;

        if (!isset($this->translations[$language][$key])) {
            throw new \InvalidArgumentException(sprintf('Translation for "%s" not found in language "%s"', $key, $language));
        }

        return $this->translations[$language][$key];
    }
}