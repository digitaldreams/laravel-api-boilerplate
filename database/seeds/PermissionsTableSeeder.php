<?php


use Illuminate\Database\Seeder;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Models\Role;
use App\Services\PermitGenerator;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pg = new PermitGenerator();
        $collections = $pg->process();
        $this->command->info(count($collections) . '  permissions saved');
    }

}