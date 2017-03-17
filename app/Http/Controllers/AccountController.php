<?php

namespace App\Http\Controllers;

use App\Account;
use App\Events\AccountCreated;
use App\Events\BackupAccountDatabaseEvent;
use App\Events\BackupAccountDatabaseRequested;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{

    /**
     * AccountController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Account list page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $accounts = Account::all();

        return view('accounts.index', compact('accounts'));
    }

    /**
     * Account create page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('accounts.create');
    }

    /**
     * Store account in database.
     * Create database in server.
     * Install database migrations.
     * Create subdomain in server.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $account = Account::create($request->all());

        // Create the account database.
        DB::statement("CREATE DATABASE {$account->slug}");
        // Install and migrate account migrations.
        Artisan::call('tenanti:install', ['driver' => 'account']);
        Artisan::call('tenanti:migrate', ['driver' => 'account']);

        event(new AccountCreated($account));

        // Create all directories.
        File::makeDirectory(storage_path("app/accounts/{$account->slug}"), 0755, true);
        File::makeDirectory(storage_path("app/accounts/{$account->slug}/database"), 0755, true);

        return redirect('/accounts');
    }

    /**
     * Edit account page.
     *
     * @param Account $account
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Account $account)
    {
        return view('accounts.edit');
    }

    /**
     * Update account in database.
     *
     * @param Account $account
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Account $account, Request $request)
    {
        $account->update($request->all());

        return redirect('/accounts');
    }

    /**
     * Destroy account from database.
     * Backup database and save in a folder.
     * Delete database from server.
     * Delete the subdomain from server.
     *
     * @param Account $account
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(Account $account)
    {
        // Drop database.
        DB::statement("DROP DATABASE {$account->slug}");

        // Delete folders
        File::deleteDirectory(storage_path("app/accounts/{$account->slug}"));

        // Delete account from database.
        $account->delete();

        return redirect('/accounts');
    }

    /**
     * Backup account database.
     *
     * @param Account $account
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function backup(Account $account)
    {
        event(new BackupAccountDatabaseRequested($account));

        return redirect('/accounts');
    }
}
