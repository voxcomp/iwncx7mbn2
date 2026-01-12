<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        $user = User::create([
            'username' => 'admin',
            'user_type' => 'admin',
            'fname' => 'Beth',
            'lname' => 'Viney',
            'email' => 'info@czarspromise.com',
            'password' => Hash::make('findThecause18'),
            'validated' => 1,
        ]);
        /*
                $user = User::create([
                    'username' => 'user',
                    'user_type' => 'auth',
                    'fname' => 'Test',
                    'lname' => 'User',
                    'email' => 'test@voxcomp.com',
                    'password' => Hash::make('password'),
                    'validated' => 1,
                    'phone' => '123-123-1234',
                    'address' => '123 Street St',
                    'city' => 'City',
                    'state' => 'WI',
                    'zip' => '12345'
                ]);
                $user = User::create([
                    'username' => 'eventuser',
                    'user_type' => 'auth',
                    'fname' => 'Test',
                    'lname' => 'Participant',
                    'email' => 'test3@voxcomp.com',
                    'password' => Hash::make('password'),
                    'validated' => 1,
                ]);
        */
    }
}
