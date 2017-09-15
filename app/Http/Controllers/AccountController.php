<?php

namespace App\Http\Controllers;

use App\Module;
use App\Account;
use Illuminate\Http\Request;
use App\Events\AccountCreated;
use App\Events\BackupAccountDatabaseEvent;
use App\Events\BackupAccountDatabaseRequested;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{

    /**
     * AccountController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage-accounts');
        $this->middleware('permission:manage-backups', ['only' => 'backups']);
        $this->middleware('permission:manage-settings', ['only' => 'settings']);
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

        event(new AccountCreated($account));
        flash('The account has been created.', 'success');

        return redirect("/accounts");
    }

    /**
     * Edit account page.
     *
     * @param Account $account
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Account $account)
    {
        return view('accounts.edit', compact('account'));
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

        flash('The account has been updated.', 'success');

        return redirect('/accounts');
    }

    /**
     * Change account active status.
     *
     * @param Account $account
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function toggleActive(Account $account)
    {
        $account->active = ! $account->active;
        $account->save();

        if ($account->active) {
            flash('The account status has been actived.', 'success');

            return redirect("/accounts/{$account->id}");
        }

        flash('The account status has been paused.', 'success');

        return redirect("/accounts/{$account->id}");
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
        DB::statement("DROP DATABASE {$account->slug}");

        Storage::disk('s3')->delete($account->slug);

        $account->delete();

        flash('The account has been deleted.', 'success');

        return redirect('/accounts');
    }

    /**
     * Show account page.
     *
     * @param Account $account
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Account $account)
    {
        return view('accounts.show', compact('account'));
    }

    /**
     * Backup account page.
     *
     * @param Account $account
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function backup(Account $account)
    {
        return view('accounts.backups', compact('account'));
    }

    /**
     * Return the an array of account object.
     *
     * @param Account $account
     * @return array
     */
    public function details($account)
    {
        return Account::with('backups')->findOrFail($account);
    }

    /**
     * Settings account page.
     *
     * @param Account $account
     * @return float
     */
    public function settings(Account $account)
    {
        return view('accounts.settings', compact('account'));
    }

    /**
     * Modules account page.
     *
     * @param Account $account
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function modules(Account $account)
    {
        $modules = Module::all();

        return view('accounts.modules', compact('account', 'modules'));
    }

    /**
     * Get selected modules.
     *
     * @param Account $account
     * @return mixed
     */
    public function selectedModules(Account $account)
    {
        return $account->modules()->pluck('id')->all();
    }

    /**
     * Update account modules in database.
     *
     * @param Account $account
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateModules(Account $account, Request $request)
    {
        if ($request->input('modules')) {
            $account->modules()->sync($request->input('modules'));
        } else {
            $account->modules()->sync([]);
        }

        flash('The account modules has been updated', 'success');

        return redirect("/accounts/{$account->id}/modules");
    }
}
