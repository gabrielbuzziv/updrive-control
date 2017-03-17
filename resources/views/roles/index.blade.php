@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                @include('partials.configuration')
            </div>

            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="/roles/create" class="btn btn-primary btn-xs pull-right">
                            Create
                        </a>

                        Roles
                    </div>

                    <div class="panel-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Display Name</th>
                                    <th style="text-align: right;">
                                        <i class="mdi mdi-settings"></i>
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($roles as $role)
                                <tr>
                                    <td>{{ $role->id }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>{{ $role->display_name }}</td>
                                    <td align="right">
                                        <a href="/roles/{{ $role->id }}/edit" class="btn btn-default btn-xs">Editar</a>

                                        <form action="/roles/{{ $role->id }}" method="POST" style="display: inline-block;">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="_method" value="delete">

                                            <button type="submit" class="btn btn-danger btn-xs" onClick="return confirm('Tem certeza?')">Remover</button>
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