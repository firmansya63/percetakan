<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Owner Percetakan',
                'email' => 'owner@percetakan.com',
                'role' => 'owner',
            ],
            [
                'name' => 'Kasir Utama',
                'email' => 'kasir@percetakan.com',
                'role' => 'kasir',
            ],
            [
                'name' => 'Operator Produksi',
                'email' => 'operator@percetakan.com',
                'role' => 'operator',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ],
            );

            $user->syncRoles([$userData['role']]);
        }

        $this->command->info('Users seeded successfully!');
        $this->command->info('Owner: owner@percetakan.com / password');
        $this->command->info('Kasir: kasir@percetakan.com / password');
        $this->command->info('Operator: operator@percetakan.com / password');
    }
}
