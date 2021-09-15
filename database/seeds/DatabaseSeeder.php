<?php
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_sys_admin = Role::where('name','System Admin')->first();
        if(!$role_sys_admin)
        {
            $role_sys_admin = new Role();
            $role_sys_admin->name = 'System Admin';
            $role_sys_admin->save();
        }

        $role_manager = Role::where('name','Manager')->first();
        if(!$role_manager)
        {
            $role_manager = new Role();
            $role_manager->name = 'Manager';
            $role_manager->save();
        }

        $role_branch_manager = Role::where('name','Branch Manager')->first();
        if(!$role_branch_manager)
        {
            $role_branch_manager = new Role();
            $role_branch_manager->name = 'Branch Manager';
            $role_branch_manager->save();
        }

        $role_admin = Role::where('name','Admin')->first();
        if(!$role_admin)
        {
            $role_admin = new Role();
            $role_admin->name = 'Admin';
            $role_admin->save();
        }

        $role_dealer = Role::where('name','Salesman')->first();
        if(!$role_dealer)
        {
            $role_dealer = new Role();
            $role_dealer->name = 'Salesman';
            $role_dealer->save();
        }

        if(!User::first())
        {
            $sysadmin = new User();
            $sysadmin->name = 'Marcus';
            $sysadmin->email = 'huanyang@live.com';
            $sysadmin->password = bcrypt('mP@ssw0rd');
            $sysadmin->contact = '0163284324';
            $sysadmin->code = 'US000001';
            $sysadmin->status = 'ACTIVE';
            $sysadmin->save();
            $sysadmin->assignRole('System Admin');
        }

        if(!Permission::first())
        {
            Permission::create(['name'=>'DASHBOARD']);
            Permission::create(['name'=>'PRESALES']);
            Permission::create(['name'=>'SALES']);
            Permission::create(['name'=>'PROCUREMENT']);
            Permission::create(['name'=>'INVENTORY']);
            Permission::create(['name'=>'PRINCIPAL']);
            Permission::create(['name'=>'SMART ANALYSIS']);
            Permission::create(['name'=>'COMPANY']);
        }

        $role_sys_admin->givePermissionTo(Permission::all());
        $role_manager->givePermissionTo(Permission::all());
        $role_branch_manager->givePermissionTo(Permission::all());
        $role_admin->givePermissionTo(Permission::all());
        $role_dealer->givePermissionTo(['PRESALES','SALES']);

        $path = storage_path()."/DB_Seed.sql";
        DB::unprepared(file_get_contents($path));
        $this->command->info('Backup Script Imported!');
    }
}
