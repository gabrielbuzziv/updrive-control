@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h1 class="page-title margin-top-0 margin-bottom-30">{{ $account->name }}</h1>

                @include('partials.alert')

                <div class="panel panel-default">
                    <div class="panel-heading">
                        Options
                    </div>

                    <div class="panel-body">
                        <ul class="options">
                            <li>
                                <a href="/accounts/{{ $account->id }}/edit">
                                    <i class="mdi mdi-pencil"></i>
                                    <span>Edit</span>
                                    <small>Registration data</small>
                                </a>
                            </li>

                            <li>
                                <a href="/accounts/{{ $account->id }}/modules">
                                    <i class="mdi mdi-view-module"></i>
                                    <span>Modules</span>
                                    <small>Enable/Disable</small>
                                </a>
                            </li>

                            <li>
                                <a href="/accounts/{{ $account->id }}/billings">
                                    <i class="mdi mdi-cash-usd"></i>
                                    <span>Billing</span>
                                    <small>Invoices and payments</small>
                                </a>
                            </li>

                            <li>
                                <a href="/accounts/{{ $account->id }}/backup">
                                    <i class="mdi mdi-backup-restore"></i>
                                    <span>Backup</span>
                                    <small>Database and Storage</small>
                                </a>
                            </li>

                            <li>
                                <a href="/accounts/{{ $account->id }}/settings">
                                    <i class="mdi mdi-settings"></i>
                                    <span>Settings</span>
                                    <small>Integrations, restrictions, etc...</small>
                                </a>
                            </li>

                            <li>
                                <a href="/accounts/{{ $account->id }}/toggle">
                                    <i class="mdi mdi-{{ $account->active ? 'pause' : 'play' }}"></i>
                                    <span>{{ $account->active ? 'Pause' : 'Active' }}</span>
                                    <small>{{ $account->active ? 'Pause' : 'Resume' }} the app</small>
                                </a>
                            </li>

                            @if (! $account->active)
                                <li>
                                    <a href="/accounts/{{ $account->id }}/destroy" onclick="return confirm('Are you sure?')">
                                        <i class="mdi mdi-close text-danger" ></i>
                                        <span class="text-danger">Terminate</span>
                                        <small class="text-danger">Remove the account</small>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        Resources
                    </div>

                    <div class="panel-body">
                        <p>Some charts about usage resources.</p>
                        <ul>
                            <li>Database Size</li>
                            <li>Storage Size</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <backup />
@endsection