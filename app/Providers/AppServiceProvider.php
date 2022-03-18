<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('user\CompadUserRepository', 'user\EloquentCompadUserRepository'); 
        $this->app->bind('member\MemberRepository', 'member\EloquentMemberRepository');
        $this->app->bind('academy\AcademyRepository', 'academy\EloquentAcademyRepository');  
        $this->app->bind('event\EventRegistrationRepository', 'event\EloquentEventRegistrationRepository'); 
        $this->app->bind('event\EventConfigRepository', 'event\EloquentEventConfigRepository'); 

        $this->app->bind('organization\OrganizationRepository', 'organization\EloquentOrganizationRepository'); 

        //reference
        $this->app->bind('reference\EventEntriesRepository', 'reference\EloquentEventEntriesRepository'); 
        $this->app->bind('reference\EntryConfigAgeRepository', 'reference\EloquentEntryConfigAgeRepository'); 
        $this->app->bind('reference\EntryConfigBeltRepository', 'reference\EloquentEntryConfigBeltRepository'); 
        $this->app->bind('reference\EntryConfigRepository', 'reference\EloquentEntryConfigRepository'); 
        $this->app->bind('reference\EntryConfigWeightRepository', 'reference\EloquentEntryConfigWeightRepository'); 
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
