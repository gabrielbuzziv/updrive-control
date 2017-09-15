<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage-users');
    }

    /**
     * List user page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $users = User::paginate(35);

        return view('users.index', compact('users'));
    }

    /**
     * Create user page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $roles = Role::all();

        return view('users.create', compact('roles'));
    }

    /**
     * Store user in database.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $user = User::create($request->all());

        if ($request->input('roles')) {
            $user->roles()->sync($request->input('roles'));
        } else {
            $user->roles()->sync([]);
        }

        flash('The user has been created.', 'success');

        return redirect('/users');
    }

    /**
     * Edit user page.
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user)
    {
        $roles = Role::all();

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update user in database.
     *
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(User $user, Request $request)
    {
        $user->update($request->all());

        if ($request->input('roles')) {
            $user->roles()->sync($request->input('roles'));
        } else {
            $user->roles()->sync([]);
        }

        flash('The user has been updated.', 'success');

        return redirect('/users');
    }

    /**
     * Destroy user from database.
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(User $user)
    {
        $user->delete();

        flash('The user has been deleted.', 'success');

        return redirect('/users');
    }
}
