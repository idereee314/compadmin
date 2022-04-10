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
        $this->app->bind('user\UserRepository', 'user\EloquentUserRepository'); 
        $this->app->bind('member\MemberRepository', 'member\EloquentMemberRepository');
        $this->app->bind('academy\AcademyRepository', 'academy\EloquentAcademyRepository');  
        $this->app->bind('event\EventRegistrationRepository', 'event\EloquentEventRegistrationRepository'); 
        $this->app->bind('event\EventConfigRepository', 'event\EloquentEventConfigRepository'); 
        $this->app->bind('event\EventAwardRepository', 'event\EloquentEventAwardRepository');
        $this->app->bind('event\EventRepository', 'event\EloquentEventRepository'); 

        $this->app->bind('organization\OrganizationRepository', 'organization\EloquentOrganizationRepository'); 

        //reference
        $this->app->bind('reference\EventEntriesRepository', 'reference\EloquentEventEntriesRepository'); 
        $this->app->bind('reference\EntryConfigAgeRepository', 'reference\EloquentEntryConfigAgeRepository'); 
        $this->app->bind('reference\EntryConfigBeltRepository', 'reference\EloquentEntryConfigBeltRepository'); 
        $this->app->bind('reference\EntryConfigRepository', 'reference\EloquentEntryConfigRepository'); 
        $this->app->bind('reference\EntryConfigWeightRepository', 'reference\EloquentEntryConfigWeightRepository'); 
        $this->app->bind('reference\EventEntriesFeeRepository', 'reference\EloquentEventEntriesFeeRepository'); 
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
