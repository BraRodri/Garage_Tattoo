<?php
  
namespace Database\Seeders;

use App\Models\Clients;
use Illuminate\Database\Seeder;
use App\Models\User;
use Laravel\Jetstream\Role as JetstreamRole;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
  
class CreateAdminUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'rut'=>'111111111',
            'active'=>1,
            'name' => 'Jhon Jairo', 
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456')
        ]);

        $client = Clients::create([
            'rut'=>'111111111',
            'active'=>1,
            'business_name' => 'Jhon Jairo', 
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456')
        ]);

        $role = Role::create(['name' => 'Admin']);
     
        $permissions = Permission::pluck('id','id')->all();
   
        $role->syncPermissions($permissions);
     
        $user->assignRole([$role->id]);

    }
}



