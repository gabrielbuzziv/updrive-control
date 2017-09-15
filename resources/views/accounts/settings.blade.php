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
                            <i class="mdi mdi-archive margin-right-10"></i>
                            Storage
                        </h3>
                    </div>

                    <form action="/accounts/{{ $account->id }}/settings" method="POST" class="form-horizontal">
                        <div class="panel-body">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="patch">

                            <div class="form-group">
                                <label class="control-label col-md-3">Storage Limit</label>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input type="text" name="settings[storage_limit]" class="form-control" value="{{ isset($account->setting->storage_limit) ? $account->setting->storage_limit : '' }}">
                                        <span class="input-group-addon">MB</span>
                                    </div>

                                    <small class="helper-block">This is the total of MB is possible to store.</small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-3">Listable Storage</label>
                                <div class="col-md-4">
                                    <label class="radio radio-inline">
                                        <input type="radio" name="settings[storage_listable]" value="1" {{ isset($account->setting->storage_listable) && $account->setting->storage_listable == '1' ? 'checked' : '' }}>
                                        <span>Yes</span>
                                    </label>

                                    <label class="radio radio-inline">
                                        <input type="radio" name="settings[storage_listable]" value="0" {{ isset($account->setting->storage_listable) && $account->setting->storage_listable == '0' ? 'checked' : '' }}>
                                        <span>No</span>
                                    </label>

                                    <div class="clearfix"></div>
                                    <small class="helper-block">The account manager can see the storage.</small>
                                </div>
                            </div>
                        </div>

                        <div class="panel-footer">
                            <div class="col-md-6 text-center col-md-offset-3">
                                <button class="btn btn-success" type="submit">Save</button>
                                <a href="/accounts/{{ $account->id }}/settings/storage" class="btn btn-default">Restore Default</a>
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

