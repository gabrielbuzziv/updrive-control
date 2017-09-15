@extends('layouts.app')

@section('content')
    <div class="container" xmlns="http://www.w3.org/1999/html">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @include('accounts.header', ['title' => 'Backups'])

                @include('partials.alert')

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="mdi mdi-database margin-right-10"></i>
                            Database
                        </h3>
                    </div>

                    <div class="panel-body">
                        <div class="alert" :class="limitClass" v-if="databases.length">
                            <p>This account have @{{ databases.length }} of {{ $account->setting->backup_database_limit }}
                                database backups.</p>
                            <small>When the backup limit be reached <b>({{ $account->setting->backup_database_limit }})</b>,
                                the oldest backup files will be removed.
                            </small>
                        </div>

                        <table class="table" v-if="databases.length">
                            <thead>
                                <th>File Name</th>
                                <th>Size</th>
                                <th class="text-center">Done at</th>
                                <th></th>
                            </thead>

                            <tbody>
                                <tr v-for="database in databases">
                                    <td>@{{ database.filename }}</td>
                                    <td>@{{ database.filesize }}</td>
                                    <td align="middle">
                                        <span v-if="database.done_at">@{{ database.done_at }}</span>
                                        <span v-else><img src="/images/loading.gif"/></span>
                                    </td>
                                    <td align="right">
                                        <a :href="database.download_url" class="btn btn-default btn" :disabled="! database.done_at">
                                            <i class="mdi mdi-download"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="jumbotron text-center margin-bottom-0" v-else>
                            <h2>Not Found</h2>
                            <p>This account has no backups at the moment.</p>
                            <a href="/accounts/{{ $account->id }}/backup/create"
                               class="btn btn-primary margin-top-15">
                                <span>Create a new backup</span>
                            </a>

                            <div class="clearfix"></div>
                        </div>

                        <div v-if="databases.length">
                            <a href="/accounts/{{ $account->id }}/backup/create" class="btn btn-primary">
                                <span>Create a new backup</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script async>
    new Vue({
        el: '#app',

        data () {
            return {
                databases: [],
                loading: {
                    backup: {
                        database: false
                    }
                }
            }
        },

        computed: {
            limitClass () {
                if (this.databases.length >= {{ $account->setting->backup_database_limit }}) {
                    return 'alert-danger'
                }

                if (this.databases.length >= {{ $account->setting->backup_database_limit }} * 0.7) {
                    return 'alert-warning'
                }

                return 'alert-info'
            },
        },

        methods: {
            load () {
                const vm = this

                vm.$http.get(`/accounts/{{ $account->id }}/backup/databases`)
                        .then(function (response) {
                            vm.databases = response.data
                        })
            },

            download (database) {
                return `/accounts/{{ $account->id }}/backup/${database.id}/download`
            },

            listenForEvents () {
                const vm      = this
                const channel = window.Echo.private('account')

                channel.listen('AccountBackupRequested', function (data) {
                    if (data.account.id == '{{ $account->id }}') {
                        vm.load()
                    }
                })

                channel.listen('DatabaseBackupDone', function (data) {
                    if (data.account.id == '{{ $account->id }}') {
                        console.log('ok')
                        vm.load()
                    }
                })
            }
        },

        mounted () {
            this.load()
            this.listenForEvents()
        }
    })
</script>
@endpush

