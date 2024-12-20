<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//        ]);

        $user = User::create([
            'name' => 'Luis Miguel',
            'email' => 'sanchezclm@gmail.com',
            'password' => password_hash('Lwyz1987$', PASSWORD_DEFAULT),
        ]);
        $user->email_verified_at = Carbon::now();
        $user->save();

        $user = User::create([
            'name' => 'Juan',
            'email' => 'juan@gmail.com',
            'password' => password_hash('Lwyz1987$', PASSWORD_DEFAULT),
        ]);
        $user->email_verified_at = Carbon::now();
        $user->save();


        $user = User::create([
            'name' => 'Mauricio',
            'email' => 'mauricio@gmail.com',
            'password' => password_hash('Lwyz1987$', PASSWORD_DEFAULT),
        ]);
        $user->email_verified_at = Carbon::now();
        $user->save();
    }
}
