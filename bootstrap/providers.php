<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Modules\Users\Providers\UsersServiceProvider::class,
    App\Modules\Articles\Providers\ArticlesServiceProvider::class,
    App\Modules\Comments\Providers\CommentsServiceProvider::class,
    App\Modules\Security\Providers\SecurityServiceProvider::class,
];
