<?php

use Illuminate\Database\Seeder;
use App\Models\AdminUser;
use App\Models\AdminUserRole;

class AdminUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $adminUser = factory( AdminUser::class )->create(
            [
                'name'     => 'TestUser',
                'email'    => 'test@example.com',
                'password' => '123',
            ]
        );

        factory( AdminUserRole::class )->create(
            [
                'admin_user_id' => $adminUser->id,
                'role'          => AdminUserRole::ROLE_SUPER_USER,
            ]
        );
    }
}
