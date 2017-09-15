@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @include('partials.alert')

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="/modules/create" class="btn btn-primary btn-xs pull-right">
                            Create
                        </a>

                        Modules
                    </div>

                    <div class="panel-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th style="text-align: right;" width="70">
                                        <i class="mdi mdi-settings"></i>
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($modules as $module)
                                <tr>
                                    <td>{{ $module->id }}</td>
                                    <td>
                                        {{ $module->display_name }}<br>
                                        <small>#{{ $module->name }}</small>
                                    </td>
                                    <td>
                                        <div class="label {{ $module->status ? 'label-success' : 'label-danger' }}">
                                            {{ $module->status ? 'Enabled' : 'Disabled' }}
                                        </div>
                                    </td>
                                    <td align="right">
                                        <a href="/modules/{{ $module->id }}/edit" class="btn btn-default btn-xs">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>

                                        <form action="/modules/{{ $module->id }}" method="POST" style="display: inline-block;">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="_method" value="delete">

                                            <button type="submit" class="btn btn-danger btn-xs" onClick="return confirm('Tem certeza?')">
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