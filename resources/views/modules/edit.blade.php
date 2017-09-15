@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="/modules" class="btn btn-default btn-xs pull-right">
                            <i class="mdi mdi-arrow-left"></i> Back
                        </a>

                        Edit Module: <em>{{ $module->name }}</em>
                    </div>

                    <div class="panel-body">
                        <form action="/modules/{{ $module->id }}" method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="patch">

                            @include('modules.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection