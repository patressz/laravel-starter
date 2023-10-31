<?php

namespace Patressz\LaravelStarter\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravelstarter:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the laravel starter kit';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Require laravel breeze
        shell_exec('composer require laravel/breeze --dev');

        // Require laravel blade components
        shell_exec('composer require patressz/laravel-blade-components');

        // Install npm packages
        shell_exec('yarn add tailwindcss postcss autoprefixer sass --dev');

        // Install breeze
        shell_exec('php artisan breeze:install blade');

        // Install tailwindcss
        shell_exec('npx tailwindcss init -p');

        // Delete relevant files and directories
        (new Filesystem)->delete(app_path('Http/Requests/ProfileUpdateRequest.php'));
        (new Filesystem)->delete(app_path('Http/Controllers/ProfileController.php'));
        (new Filesystem)->deleteDirectory(app_path('View'));

        // Copy routes
        (new Filesystem)->copy(__DIR__.'/../../resources/stubs/routes.php', base_path('routes/web.php'));
        (new Filesystem)->copy(__DIR__.'/../../resources/stubs/auth.php', base_path('routes/auth.php'));

        // Copy views files
        (new Filesystem)->copyDirectory(__DIR__.'/../../resources/stubs/views', resource_path());

        // Ensure if directories exists
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Controllers/Admin'));

        // Copy ExampleController
        (new Filesystem)->copy(__DIR__.'/../../stubs/ExampleController.php', app_path('Http/Controllers/Admin'));
    }
}
