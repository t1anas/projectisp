<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Dummyuser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userData = [
            [
            'name' => 'John Doe',
            'email' => 'hhh@gmail.com',
            'role' => 'admin',
            'password' => bcrypt('password-123')
            ],
            [
            'name' => 'Johnny nct',
            'email' => 'huhah@gmail.com',
            'role' => 'noc',
            'password' => bcrypt('password-155563')
            ],
            [
            'name' => 'Johnhaechan',
            'email' => 'nct@gmail.com',
            'role' => 'cs',
            'password' => bcrypt('password-16323')
            ],
            ];
            foreach ($userData as $key => $val){
                User::create($val);
            }
            }
    }
