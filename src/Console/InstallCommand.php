<?php

namespace Patressz\LaravelStarter\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use RuntimeException;
use Symfony\Component\Process\Process;

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
        $this->requireComposerPackages(['laravel/breeze'], true);

        // Require laravel blade components
        $this->requireComposerPackages(['patressz/laravel-blade-components']);

        // Install npm packages
        $this->updateNodePackages(function ($packages) {
            return [
                'tailwindcss' => '3.3.5',
                'sass' => '1.69.5',
            ] + $packages;
        });

        $this->updateNodePackages(function ($packages) {
            return [
                'sweetalert2' => '11.9.0',
            ] + $packages;
        }, false);

        // Install breeze
        $this->runCommands(['php artisan breeze:install blade']);

        // Install tailwindcss
        $this->runCommands(['npx tailwindcss init -p']);

        // Delete relevant files and directories
        (new Filesystem)->delete(app_path('Http/Requests/ProfileUpdateRequest.php'));
        (new Filesystem)->delete(app_path('Http/Controllers/ProfileController.php'));
        (new Filesystem)->deleteDirectory(app_path('View'));
        (new Filesystem)->deleteDirectory(resource_path('css'));

        // Ensure if direfcotires exists
        (new Filesystem)->ensureDirectoryExists(resource_path('views'));
        (new Filesystem)->ensureDirectoryExists(resource_path('sass'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js'));
        (new Filesystem)->ensureDirectoryExists(base_path('routes'));
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Controllers/Admin'));

        // Clean directories
        (new Filesystem)->cleanDirectory(resource_path('views'));

        // Copy files and directories
        copy(__DIR__.'/../../resources/stubs/web.php', base_path('routes/web.php'));
        copy(__DIR__.'/../../resources/stubs/auth.php', base_path('routes/auth.php'));
        copy(__DIR__.'/../../resources/stubs/vite.config.js', base_path('vite.config.js'));
        copy(__DIR__.'/../../resources/stubs/tailwind.config.js', base_path('tailwind.config.js'));
        copy(__DIR__.'/../../resources/stubs/ExampleController.php', app_path('Http/Controllers/Admin/ExampleController.php'));

        (new Filesystem)->copyDirectory(__DIR__.'/../../resources/stubs/views', resource_path('views'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../resources/stubs/sass', resource_path('sass'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../resources/stubs/js', resource_path('js'));

        // Build npm
        $this->runCommands(['yarn install', 'yarn run build']);

        $this->line('');
        $this->components->info('Laravel starter installed successfully.');
    }

    /**
     * Installs the given Composer Packages into the application.
     * Taken from https://github.com/laravel/breeze/blob/1.x/src/Console/InstallCommand.php
     *
     * @param  bool  $asDev
     * @return bool
     */
    protected function requireComposerPackages(array $packages, $asDev = false)
    {

        $command = array_merge(
            ['composer', 'require'],
            $packages,
            $asDev ? ['--dev'] : [],
        );

        return (new Process($command, base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']))
            ->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output->write($output);
            }) === 0;
    }

    /**
     * Update the "package.json" file.
     * Taken from https://github.com/laravel/breeze/blob/1.x/src/Console/InstallCommand.php
     *
     * @param  bool  $dev
     * @return void
     */
    protected static function updateNodePackages(callable $callback, $dev = true)
    {
        if (! file_exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        $packages[$configurationKey] = $callback(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
            $configurationKey
        );

        ksort($packages[$configurationKey]);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL
        );
    }

    /**
     * Run the given commands.
     * Taken from https://github.com/laravel/breeze/blob/1.x/src/Console/InstallCommand.php
     *
     * @param  array  $commands
     * @return void
     */
    protected function runCommands($commands)
    {
        $process = Process::fromShellCommandline(implode(' && ', $commands), null, null, null, null);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            try {
                $process->setTty(true);
            } catch (RuntimeException $e) {
                $this->output->writeln('  <bg=yellow;fg=black> WARN </> '.$e->getMessage().PHP_EOL);
            }
        }

        $process->run(function ($type, $line) {
            $this->output->write('    '.$line);
        });
    }
}
