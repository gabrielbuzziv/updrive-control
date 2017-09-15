@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @include('accounts.header', ['title' => 'Settings'])

                @include('partials.alert')

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="mdi mdi-settings margin-right-10"></i>
                            Settings
                        </h3>
                    </div>

                    <form action="/accounts/{{ $account->id }}/settings" method="POST" class="form-horizontal">
                        <div class="panel-body">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="patch">

                            <div class="form-group">
                                <label class="control-label col-md-3">Companies Limit</label>
                                <div class="col-md-4">
                                    <select name="settings[companies_limit]" class="form-control">
                                        <option value="20" {{ isset($account->setting->companies_limit) && $account->setting->companies_limit == '20' ? 'selected' : '' }}>20 companies</option>
                                        <option value="150" {{ isset($account->setting->companies_limit) && $account->setting->companies_limit == '150' ? 'selected' : '' }}>150 companies</option>
                                        <option value="300" {{ isset($account->setting->companies_limit) && $account->setting->companies_limit == '300' ? 'selected' : '' }}>300 companies</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="panel-footer">
                            <div class="col-md-6 text-center col-md-offset-3">
                                <button class="btn btn-success" type="submit">Save</button>
                                <a href="/accounts/{{ $account->id }}/settings/default" class="btn btn-default">Restore Default</a>
                            </div>

                            <div class="clearfix"></div>
                        </div>
                    </form>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="mdi mdi-database margin-right-10"></i>
                            Backup
                        </h3>
                    </div>

                    <form action="/accounts/{{ $account->id }}/settings" method="POST" class="form-horizontal">
                        <div class="panel-body">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="patch">

                            <div class="form-group">
                                <label class="control-label col-md-3">Database Limit</label>
                                <div class="col-md-4">
                                    <select name="settings[backup_database_limit]" class="form-control">
                                        <option value="7" {{ isset($account->setting->backup_database_limit) && $account->setting->backup_database_limit == '7' ? 'selected' : '' }}>Last 7 days</option>
                                        <option value="30" {{ isset($account->setting->backup_database_limit) && $account->setting->backup_database_limit == '30' ? 'selected' : '' }}>Last 30 days</option>
                                        <option value="60" {{ isset($account->setting->backup_database_limit) && $account->setting->backup_database_limit == '60' ? 'selected' : '' }}>Last 60 days</option>
                                        <option value="90" {{ isset($account->setting->backup_database_limit) && $account->setting->backup_database_limit == '90' ? 'selected' : '' }}>Last 90 days</option>
                                        <option value="unlimited" {{ isset($account->setting->backup_database_limit) && $account->setting->backup_database_limit == 'unlimited' ? 'selected' : '' }}>Unlimited</option>
                                    </select>

                                    <small class="helper-block">One database backup a day.</small>
                                </div>
                            </div>
                        </div>

                        <div class="panel-footer">
                            <div class="col-md-6 text-center col-md-offset-3">
                                <button class="btn btn-success" type="submit">Save</button>
                                <a href="/accounts/{{ $account->id }}/settings/backup" class="btn btn-default">Restore Default</a>
                            </div>

                            <div class="clearfix"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

