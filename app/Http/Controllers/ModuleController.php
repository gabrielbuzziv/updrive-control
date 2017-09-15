<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * ModuleController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage-modules');
    }

    /**
     * List module page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $modules = Module::all();

        return view('modules.index', compact('modules'));
    }

    /**
     * Create module page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $requirements = Module::all();

        return view('modules.create', compact('requirements'));
    }

    /**
     * Store module in database.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $module = Module::create($request->all());

        if ($request->input('requirements')) {
            $module->requirements()->sync($request->input('requirements'));
        } else {
            $module->requirements()->sync([]);
        }

        flash('The module has been created.', 'success');

        return redirect('/modules');
    }

    /**
     * Edit module page.
     *
     * @param Module $module
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Module $module)
    {
        $requirements = Module::all();

        return view('modules.edit', compact('module', 'requirements'));
    }

    /**
     * Update module in database.
     *
     * @param Module $module
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Module $module, Request $request)
    {
        $module->update($request->all());

        if ($request->input('requirements')) {
            $module->requirements()->sync($request->input('requirements'));
        } else {
            $module->requirements()->sync([]);
        }

        flash('The module has been updated.', 'success');

        return redirect('/modules');
    }

    /**
     * Destroy module from database.
     *
     * @param Module $module
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Module $module)
    {
        $module->delete();

        flash('The module has been deleted.', 'success');

        return redirect('/modules');
    }

    /**
     * Return a list of all modules.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function listOfModules()
    {
        return Module::with('requirements')->get();
    }
}
