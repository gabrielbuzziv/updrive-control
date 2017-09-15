@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @include('partials.alert')

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
                                            <a href="http://{{ $account->slug }}.updrive.app" target="_blank">
                                                {{ $account->slug }}.updrive.app
                                            </a>
                                        </td>
                                        <td align="right">
                                            <a href="/accounts/{{ $account->id }}" class="btn btn-default">
                                                <i class="mdi mdi-arrow-right"></i>
                                            </a>
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

    <backup />
@endsection

@push('scripts')
<script>
    new Vue({
        el: '#app',

        methods: {
            showModal (accountId, modalId) {
                this.$root.$emit('load::backup', accountId)
                this.$root.$emit('show::modal', modalId)
            }
        }
    })
</script>
@endpush