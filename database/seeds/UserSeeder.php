<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Collection Super Admin
        $superAdmin = collect([
            ['name' => 'hanan', 'email' => 'hasyrawi@gmail.com'],
            ['name' => 'Muh Ghazali Akbar', 'email' => 'muhghazaliakbar@icloud.com'],
            ['name' => 'Super Admin', 'email' => 'superadmin@example.com'],
        ]);

        $superAdmin->each(
            fn ($admin) => factory(User::class)->state('super_admin')->create([
                'name' => $admin['name'],
                'email' => $admin['email']
            ])
        );

        factory(User::class, 5)->state('admin')->create();
        factory(User::class, 5)->state('manager')->create();
        factory(User::class, 5)->state('viewer')->create();
    }
}
