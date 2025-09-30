<?php

namespace Database\Seeders;

use App\Modules\Users\Persistence\ORM\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создаем 10 пользователей с русскими именами
        $users = [
            [
                'name' => 'Александр Петров',
                'email' => 'alexander.petrov@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Мария Сидорова',
                'email' => 'maria.sidorova@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Дмитрий Козлов',
                'email' => 'dmitry.kozlov@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Елена Волкова',
                'email' => 'elena.volkova@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Сергей Морозов',
                'email' => 'sergey.morozov@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Анна Соколова',
                'email' => 'anna.sokolova@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Игорь Лебедев',
                'email' => 'igor.lebedev@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Ольга Новикова',
                'email' => 'olga.novikova@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Владимир Орлов',
                'email' => 'vladimir.orlov@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Татьяна Медведева',
                'email' => 'tatyana.medvedeva@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}
