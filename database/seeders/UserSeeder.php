<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contents = File::get(base_path('.\database\seeders\userDataToSeed.json'));
        $data = json_decode(json: $contents, associative: true);
        foreach ($data as $key => $value ) {
            User::create([
                'name' => $value['name'],
                'email' => $value['email'],
                'password' => bcrypt($value['password']), // Ejemplo de procesamiento de datos
                // ... otros campos
            ]);
        }
    }
}
