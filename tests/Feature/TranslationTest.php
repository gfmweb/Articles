<?php

namespace Tests\Feature;

use Tests\TestCase;

class TranslationTest extends TestCase
{
    public function test_articles_translations_are_loaded(): void
    {
        // Проверяем, что переводы модуля Articles загружены
        $translation = __('articles::messages.created');
        echo 'Translation result: '.$translation."\n";

        // Проверяем, что файлы переводов существуют
        $messagesPath = __DIR__.'/../../app/Modules/Articles/Persistence/resources/lang/ru/messages.php';
        $validationPath = __DIR__.'/../../app/Modules/Articles/Persistence/resources/lang/ru/validation.php';
        $this->assertFileExists($messagesPath);
        $this->assertFileExists($validationPath);

        $this->assertEquals('Статья успешно создана.', $translation);
        $this->assertEquals('Статья не найдена.', __('articles::messages.not_found'));
        $this->assertEquals('Заголовок статьи обязателен.', __('articles::validation.title_required'));
    }

    public function test_comments_translations_are_loaded(): void
    {
        // Проверяем, что переводы модуля Comments загружены
        $messagesPath = __DIR__.'/../../app/Modules/Comments/Persistence/resources/lang/ru/messages.php';
        $validationPath = __DIR__.'/../../app/Modules/Comments/Persistence/resources/lang/ru/validation.php';
        $this->assertFileExists($messagesPath);
        $this->assertFileExists($validationPath);

        $this->assertEquals('Комментарий успешно создан.', __('comments::messages.created'));
        $this->assertEquals('Комментарий не найден.', __('comments::messages.not_found'));
        $this->assertEquals('Текст комментария обязателен.', __('comments::validation.text_required'));
    }

    public function test_users_translations_are_loaded(): void
    {
        // Проверяем, что переводы модуля Users загружены
        $messagesPath = __DIR__.'/../../app/Modules/Users/Persistence/resources/lang/ru/messages.php';
        $validationPath = __DIR__.'/../../app/Modules/Users/Persistence/resources/lang/ru/validation.php';
        $this->assertFileExists($messagesPath);
        $this->assertFileExists($validationPath);

        $this->assertEquals('Пользователь успешно создан.', __('users::messages.created'));
        $this->assertEquals('Пользователь не найден.', __('users::messages.not_found'));
        $this->assertEquals('Email обязателен.', __('users::validation.email_required'));
    }
}
