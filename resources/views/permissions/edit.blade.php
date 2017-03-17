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
                        <a href="/permissions" class="btn btn-default btn-xs pull-right">
                            <i class="mdi mdi-arrow-left"></i> Back
                        </a>

                        Edit Permission: <em>{{ $permission->name }}</em>
                    </div>

                    <div class="panel-body">
                        <form action="/permissions/{{ $permission->id }}" method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="patch">

                            @include('permissions.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection