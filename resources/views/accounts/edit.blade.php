@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="/accounts" class="btn btn-default btn-xs pull-right">
                            <i class="mdi mdi-arrow-left"></i> Back
                        </a>

                        Edit Account: {{ $account->name }}
                    </div>

                    <div class="panel-body">
                        <form action="/accounts/{{ $account->id }}" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="patch">

                            @include('accounts.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

