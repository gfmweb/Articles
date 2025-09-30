<?php

namespace Tests\Feature;

use Tests\TestCase;

class PathTest extends TestCase
{
    public function test_translation_paths(): void
    {
        $articlesMessagesPath = __DIR__.'/../../app/Modules/Articles/Persistence/resources/lang/ru/messages.php';
        $articlesValidationPath = __DIR__.'/../../app/Modules/Articles/Persistence/resources/lang/ru/validation.php';
        $commentsMessagesPath = __DIR__.'/../../app/Modules/Comments/Persistence/resources/lang/ru/messages.php';
        $commentsValidationPath = __DIR__.'/../../app/Modules/Comments/Persistence/resources/lang/ru/validation.php';
        $usersMessagesPath = __DIR__.'/../../app/Modules/Users/Persistence/resources/lang/ru/messages.php';
        $usersValidationPath = __DIR__.'/../../app/Modules/Users/Persistence/resources/lang/ru/validation.php';

        echo 'Articles messages path: '.$articlesMessagesPath."\n";
        echo 'Articles messages exists: '.(file_exists($articlesMessagesPath) ? 'YES' : 'NO')."\n";

        echo 'Articles validation path: '.$articlesValidationPath."\n";
        echo 'Articles validation exists: '.(file_exists($articlesValidationPath) ? 'YES' : 'NO')."\n";

        echo 'Comments messages path: '.$commentsMessagesPath."\n";
        echo 'Comments messages exists: '.(file_exists($commentsMessagesPath) ? 'YES' : 'NO')."\n";

        echo 'Comments validation path: '.$commentsValidationPath."\n";
        echo 'Comments validation exists: '.(file_exists($commentsValidationPath) ? 'YES' : 'NO')."\n";

        echo 'Users messages path: '.$usersMessagesPath."\n";
        echo 'Users messages exists: '.(file_exists($usersMessagesPath) ? 'YES' : 'NO')."\n";

        echo 'Users validation path: '.$usersValidationPath."\n";
        echo 'Users validation exists: '.(file_exists($usersValidationPath) ? 'YES' : 'NO')."\n";

        $this->assertTrue(file_exists($articlesMessagesPath));
        $this->assertTrue(file_exists($articlesValidationPath));
        $this->assertTrue(file_exists($commentsMessagesPath));
        $this->assertTrue(file_exists($commentsValidationPath));
        $this->assertTrue(file_exists($usersMessagesPath));
        $this->assertTrue(file_exists($usersValidationPath));
    }
}
