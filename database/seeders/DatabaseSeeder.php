<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local', 'testing')) {
            $this->command?->warn('Seeder akun workshop hanya tersedia di lingkungan local atau testing.');

            return;
        }

        foreach ([
            ['Dewi Anggraini', 'admin@catering.test', User::ROLE_ADMIN],
            ['Sarah Paramitha', 'pelanggan@catering.test', User::ROLE_CUSTOMER],
        ] as [$name, $email, $role]) {
            $user = User::firstOrNew(['email' => $email]);

            if (! $user->exists) {
                $user->name = $name;
                $user->password = 'Workshop123!';
                $user->role = $role;
                $user->save();
            }
        }

        foreach ([
            ['Nasi Ayam Sambal Matah', 'Ayam berbumbu dengan nasi hangat dan sambal matah segar.', 25000],
            ['Nasi Ayam Bakar', 'Ayam bakar berbumbu dengan nasi putih, sambal, dan lalapan.', 28000],
            ['Nasi Rendang', 'Rendang daging sapi dengan nasi putih dan pilihan sayur.', 32000],
        ] as [$name, $description, $price]) {
            Menu::firstOrCreate(['name' => $name], [
                'description' => $description,
                'price' => $price,
            ]);
        }
    }
}
