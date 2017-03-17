@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="/accounts/create" class="btn btn-primary btn-xs pull-right">Create</a>

                        Accounts
                    </div>

                    <div class="panel-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Domain</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($accounts as $account)
                                    <tr>
                                        <td>{{ $account->id }}</td>
                                        <td>
                                            {{ $account->name }}<br />
                                            <small>{{ $account->email }}</small>
                                        </td>
                                        <td>
                                            <a href="http://{{ $account->slug }}.saas.app" target="_blank">
                                                {{ $account->slug }}.saas.app
                                            </a>
                                        </td>
                                        <td align="right">
                                            <a href="/accounts/{{ $account->id }}/backup" class="btn btn-default btn-xs">
                                                <i class="mdi mdi-database"></i>
                                            </a>

                                            <form action="/accounts/{{ $account->id }}" method="POST" style="display: inline-block;">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="_method" value="delete">

                                                <button type="submit" class="btn btn-default btn-xs" onClick="return confirm('Tem certeza?')">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
