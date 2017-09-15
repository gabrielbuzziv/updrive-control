<?php

namespace App\Providers;

use App\AccountBackup;
use App\AccountSetting;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Storage;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\AccountCreated' => [
            'App\Listeners\CreateDatabase',
        ],
        
        'App\Events\DatabaseCreated' => [
            'App\Listeners\InstallMigration'
        ],

        'App\Events\MigrationInstalled' => [
            'App\Listeners\MigrateMigration'
        ],

        'App\Events\MigrationMigrated' => [
            'App\Listeners\InstallStorage'
        ],

        'App\Events\StorageInstalled' => [
            'App\Listeners\InstallAccount'
        ],

        'App\Events\AccountBackupRequested' => [
            'App\Listeners\AccountBackup'
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * When a setting is saved verify:
         *
         * - If is "backup_database_limit", delete exceeded backuped databases.
         */
        AccountSetting::saved(function ($setting) {
            if ($setting->label == 'backup_database_limit') {
                $query = AccountBackup::where('account_id', $setting->account->id);

                if ($query->count() >= $setting->value) {
                    $exceeded = $query->orderBy('created_at', 'desc')->take(999999999999)->skip($setting->value)->get();
                    foreach ($exceeded as $exceed) {
                        $exceed->delete();
                    }
                }
            }
        });

        /**
         * When a backup database is deleted, remove the file from storage.
         */
        AccountBackup::deleting(function ($backup) {
            $path = "{$backup->account->slug}/database/{$backup->filename}";
            Storage::disk('s3')->delete($path);
        });

        parent::boot();
    }
}
