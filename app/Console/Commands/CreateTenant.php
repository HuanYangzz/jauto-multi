<?php

namespace App\Console\Commands;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Hyn\Tenancy\Contracts\Repositories\HostnameRepository;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Hyn\Tenancy\Environment;
use Hyn\Tenancy\Models\Hostname;
use Hyn\Tenancy\Models\Website;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateTenant extends Command
{
    protected $signature = 'tenant:create {name} {email}';

    protected $description = 'Creates a tenant with the provided name and email address e.g. php artisan tenant:create boise boise@example.com';

    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');

        $hostname = $this->registerTenant($name, $email);
        app(Environment::class)->hostname($hostname);

        // we'll create a random secure password for our to-be admin
        $password = "mP@ssw0rd";
        $this->addAdmin($name, $email, $password);

        $this->info("Tenant '{$name}' is created and is now accessible at {$hostname->fqdn}");
        $this->info("Admin {$email} can log in using password {$password}");
    }

    private function registerTenant($name, $email)
    {
        // associate the customer with a website
        $website = new Website;
        $website->uuid = env('SITE_TITLE_CODE')."_".$name;
        app(WebsiteRepository::class)->create($website);

        // associate the website with a hostname
        $hostname = new Hostname;
        $baseUrl = env('APP_URL_BASE');
        $hostname->fqdn = "{$name}.{$baseUrl}";
        $hostname = app(HostnameRepository::class)->create($hostname);
        app(HostnameRepository::class)->attach($hostname, $website);

        return $hostname;
    }

    private function addAdmin($name, $email, $password)
    {
        $role_sys_admin = Role::where('name','System Admin')->first();
        if(!$role_sys_admin)
        {
            $role_sys_admin = new Role();
            $role_sys_admin->name = 'System Admin';
            $role_sys_admin->save();
        }

        if(!User::first())
        {
            $sysadmin = new User();
            $sysadmin->name = $name;
            $sysadmin->email = $email;
            $sysadmin->password = bcrypt($password);
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

        return $sysadmin;
    }
}