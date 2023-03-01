<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name'=>'Janneck Denda',
            'email'=>'janneck.denda@gmail.com',
            'password'=>Hash::make('123456'),
        ]);
    }
}
