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

        $superAdmin->each(function ($admin) {
            factory(User::class)->state('super_admin')->create([
                'name'  => $admin['name'],
                'email' => $admin['email'],
            ]);
        });

        factory(User::class)->state('building_manager')->create([
            'name'  => 'Manager',
            'email' => 'manager@example.com',
        ]);
        factory(User::class)->state('viewer')->create([
            'name'  => 'Viewer',
            'email' => 'viewer@example.com',
        ]);
        factory(User::class)->state('help_desk')->create([
            'name'  => 'Help Desk',
            'email' => 'helpdesk@example.com',
        ]);
    }
}
