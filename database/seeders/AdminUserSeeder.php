<?php

namespace Database\Seeders;

use App\Models\Alternative;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Alternative::create([
            'nama' => 'admin',
            'nim' => 'admin',
            'jurusan' => '',
            'password' => bcrypt('admin123'),
            'data_kriteria' => ''
        ]);
    }
}
