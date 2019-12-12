<?php

namespace Mistery23\LaravelRoles;

use Illuminate\Support\ServiceProvider;
use Mistery23\LaravelRoles\App\Http\Middleware\VerifyLevel;
use Mistery23\LaravelRoles\App\Http\Middleware\VerifyPermission;
use Mistery23\LaravelRoles\App\Http\Middleware\VerifyRole;
use Mistery23\LaravelRoles\Console\Commands\Roles;
use Mistery23\LaravelRoles\Contracts;
use Mistery23\LaravelRoles\Model\Entity\Permission;
use Mistery23\LaravelRoles\Model\Entity\Role;
use Mistery23\LaravelRoles\Model\ReadModels;
use Mistery23\LaravelRoles\Model\Utils;


/**
 * Class RolesServiceProvider
 */
class RolesServiceProvider extends ServiceProvider
{

    /**
     * @var string
     */
    private $packageTag = 'laravelroles';


    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->app['router']->aliasMiddleware('role', VerifyRole::class);
        $this->app['router']->aliasMiddleware('permission', VerifyPermission::class);
        $this->app['router']->aliasMiddleware('level', VerifyLevel::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                Roles::class,
            ]);
        }

        if (config('roles.rolesApiEnabled')) {
            $this->loadRoutesFrom(dirname(__DIR__) . '/routes/api.php');
        }
        $this->loadTranslationsFrom(dirname(__DIR__) . '/resources/lang/', $this->packageTag);

        $this->registerBladeExtensions();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(dirname(__DIR__) . '/config/roles.php', 'roles');
        $this->loadMigrationsFrom(__DIR__. ' /Database/Migrations');

        $this->publishFiles();

        $this->app->bind(Utils\SplitterInterface::class, Utils\Splitter::class);

        $this->app->bind(Role\Repository\RoleRepositoryInterface::class, Role\Repository\RoleRepository::class);
        $this->app->bind(ReadModels\RoleQueriesInterface::class, ReadModels\RoleQueries::class);

        $this->app->bind(Permission\Repository\PermissionRepositoryInterface::class, Permission\Repository\PermissionRepository::class);
        $this->app->bind(ReadModels\PermissionQueriesInterface::class, ReadModels\PermissionQueries::class);

        $this->app->bind(Contracts\UserRepositoryInterface::class, config('roles.dependencies.userRepository'));
        $this->app->bind(Contracts\UserQueriesInterface::class, config('roles.dependencies.userQueries'));
    }

    /**
     * Publish files for package.
     *
     * @return void
     */
    private function publishFiles(): void
    {
        $publishTag = $this->packageTag;

        $this->publishes([
            dirname(__DIR__) . '/config/roles.php' => config_path('roles.php'),
        ], $publishTag.'-config');

        $this->publishes([
            __DIR__ . '/Database/Migrations' => database_path('migrations'),
        ], $publishTag.'-migrations');

        $this->publishes([
            dirname(__DIR__) . '/config/roles.php'       => config_path('roles.php'),
            dirname(__DIR__) . '/config/roles-seed.php'       => config_path('roles-seed.php'),
            __DIR__ . '/Database/Migrations'    => database_path('migrations'),
        ], $publishTag);
    }

    /**
     * Register Blade extensions.
     *
     * @return void
     */
    protected function registerBladeExtensions(): void
    {
        $blade = $this->app['view']->getEngineResolver()->resolve('blade')->getCompiler();

        $blade->directive('role', function ($expression) {
            return "<?php if (Auth::check() && Auth::user()->hasRole({$expression})): ?>";
        });

        $blade->directive('endrole', function () {
            return '<?php endif; ?>';
        });

        $blade->directive('permission', function ($expression) {
            return "<?php if (Auth::check() && Auth::user()->hasPermission({$expression})): ?>";
        });

        $blade->directive('endpermission', function () {
            return '<?php endif; ?>';
        });

        $blade->directive('level', function ($expression) {
            $level = trim($expression, '()');

            return "<?php if (Auth::check() && Auth::user()->level() >= {$level}): ?>";
        });

        $blade->directive('endlevel', function () {
            return '<?php endif; ?>';
        });

        $blade->directive('allowed', function ($expression) {
            return "<?php if (Auth::check() && Auth::user()->allowed({$expression})): ?>";
        });

        $blade->directive('endallowed', function () {
            return '<?php endif; ?>';
        });
    }
}
