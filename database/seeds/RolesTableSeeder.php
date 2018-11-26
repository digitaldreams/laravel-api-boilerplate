<?php


use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'slug' => Role::SUPER_ADMIN,
            'name' => 'Super Admin'
        ]);
        Role::create([
            'slug' => Role::USER,
            'name' => 'User'
        ]);
    }
}
