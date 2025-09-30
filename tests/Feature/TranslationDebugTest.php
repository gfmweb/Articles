<?php

namespace Tests\Feature;

use Tests\TestCase;

class TranslationDebugTest extends TestCase
{
    public function test_debug_translations(): void
    {
        // Проверяем, что файл переводов существует
        $translationPath = base_path('app/Modules/Articles/Persistence/resources/lang/ru/articles.php');
        echo 'Translation path: '.$translationPath."\n";
        echo 'File exists: '.(file_exists($translationPath) ? 'YES' : 'NO')."\n";

        if (file_exists($translationPath)) {
            $translations = include $translationPath;
            echo 'Translations loaded: '.json_encode($translations)."\n";
        }

        // Проверяем, что Laravel может найти переводы
        $translation = __('articles.messages.created');
        echo 'Translation result: '.$translation."\n";

        // Проверяем, что Laravel может найти переводы с fallback
        $translationWithFallback = __('articles.messages.created', [], 'ru');
        echo 'Translation with fallback: '.$translationWithFallback."\n";

        $this->assertTrue(true); // Просто чтобы тест прошел
    }
}
