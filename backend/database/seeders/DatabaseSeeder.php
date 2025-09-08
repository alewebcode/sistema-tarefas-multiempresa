<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use App\Models\Task;
use Illuminate\Support\Facades\Hash;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $company1 = Company::create([
            'name' => 'Empresa 1',
            'email' => 'contato@empresa1.com'
        ]);

        $company2 = Company::create([
            'name' => 'Empresa 2',
            'email' => 'contato@empresa2.com'
        ]);

        $company3 = Company::create([
            'name' => 'Empresa 3',
            'email' => 'contato@empresa3.com'
        ]);

   
        $user1 = User::create([
            'name' => 'Jonh Doe',
            'email' => 'jonh@empresa1.com',
            'password' => Hash::make('123456'),
            'company_id' => $company1->id
        ]);

        $user2 = User::create([
            'name' => 'Jane Doe',
            'email' => 'jane@empresa2.com',
            'password' => Hash::make('123456'),
            'company_id' => $company2->id
        ]);
        $user3 = User::create([
            'name' => 'Richard Doe',
            'email' => 'richard@empresa3.com',
            'password' => Hash::make('123456'),
            'company_id' => $company3->id
        ]);
    }
}