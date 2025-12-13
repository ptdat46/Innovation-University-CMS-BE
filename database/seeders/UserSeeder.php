<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin - 1 tài khoản duy nhất
        User::create([
            'name' => 'Admin',
            'email' => 'admin@innovationuni.edu.vn',
            'password' => Hash::make('admin'),
            'role' => 'admin',
        ]);

        // Writers - 5 tài khoản
        $writers = [
            ['name' => 'Nguyễn Văn An', 'email' => 'writer1@innovationuni.edu.vn'],
            ['name' => 'Trần Thị Bình', 'email' => 'writer2@innovationuni.edu.vn'],
            ['name' => 'Lê Hoàng Cường', 'email' => 'writer3@innovationuni.edu.vn'],
            ['name' => 'Phạm Minh Đức', 'email' => 'writer4@innovationuni.edu.vn'],
            ['name' => 'Võ Thị Hằng', 'email' => 'writer5@innovationuni.edu.vn'],
        ];

        foreach ($writers as $writer) {
            User::create([
                'name' => $writer['name'],
                'email' => $writer['email'],
                'password' => Hash::make('writer123'),
                'role' => 'writer',
            ]);
        }

        // Regular Users - 10 tài khoản
        $users = [
            'Nguyễn Thanh Hà',
            'Trần Quốc Huy',
            'Lê Thị Lan',
            'Phạm Văn Long',
            'Hoàng Thị Mai',
            'Đỗ Minh Nam',
            'Bùi Thị Nga',
            'Vũ Đức Phong',
            'Đinh Thị Quỳnh',
            'Ngô Văn Sơn',
        ];

        foreach ($users as $index => $name) {
            User::create([
                'name' => $name,
                'email' => 'user' . ($index + 1) . '@innovationuni.edu.vn',
                'password' => Hash::make('user123'),
                'role' => 'user',
            ]);
        }
    }
}
