<?php

namespace App\Http\Controllers;

use App\Account;
use App\AccountBackup;
use App\Events\AccountBackupRequested;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class AccountBackupController extends Controller
{

    /**
     * AccountBackupController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage-backups');
    }

    /**
     * Get all account databases backups.
     *
     * @param Account $account
     * @return mixed
     */
    public function databases(Account $account)
    {
        return $account->backups()->orderBy('created_at', 'desc')->get();
    }

    /**
     * Backup account database.
     *
     * @param Account $account
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function backupDatabase(Account $account)
    {
        $limit = $account->setting->backup_database_limit;
        if ($limit != 'unlimited' && $account->backups->count() == $limit) {
            $account->backups()->orderBy('created_at', 'asc')->first()->delete();
        }

        $filename = sprintf('%s.sql', Carbon::now()->format('YmdHis'));
        $backup = $account->backups()->create(['filename' => $filename]);

        event(new AccountBackupRequested($account, $backup));

        flash('A new backup has been requested', 'success');

        return redirect("/accounts/{$account->id}/backup");
    }
}
