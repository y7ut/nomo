<?php

namespace App\Providers;

use App\Permission;
use App\Polices\PostPolices;
use App\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Post::class => PostPolices::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
//        Passport::routes();
//        Passport::tokensExpireIn(Carbon::now()->addMinutes(15));
//        Passport::refreshTokensExpireIn(Carbon::now()->addDays(10));

        foreach($this->getPermissions() as $permission){
            Gate::define($permission->name,function($user) use ($permission){
                return $user->hasRole($permission->roles);
            });
        }
        //
    }
    protected function getPermissions(){
        return Permission::with('roles')->get();
    }
}
