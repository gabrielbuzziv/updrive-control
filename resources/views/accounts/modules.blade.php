@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @include('accounts.header', ['title' => 'Modules'])

                @include('partials.alert')

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <i class="mdi mdi-view-module margin-right-10"></i>
                            Modules
                        </h3>
                    </div>

                    <form action="/accounts/{{ $account->id }}/modules" method="POST">
                        <div class="panel-body">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="patch">
                            <input type="hidden" name="modules[]" :value="module" v-for="module in selected_modules">

                            <hive :hive="modules"></hive>
                        </div>

                        <div class="panel-footer text-center">
                            <button class="btn btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    new Vue({
        el: '#app',

        data () {
            return {
                modules: [],
                selected_modules: []
            }
        },

        methods: {
            load () {
                const vm = this

                vm.$http.post("/accounts/{{ $account->id }}/modules")
                        .then(function (response) {
                            vm.selected_modules = response.data

                            vm.$http.get('/modules/list')
                                    .then(function (response) {
                                        vm.modules = response.data.map(function (module) {
                                            let requirements = module.requirements.map(function (requirement) {
                                                return { id: requirement.id, name: requirement.display_name }
                                            })
                                            let selected = !! vm.selected_modules.filter(function (select) {
                                                return select == module.id
                                            }).length

                                            console.log(selected)
                                            return { id: module.id, name: module.display_name, requirements, selected: selected }
                                        })
                                    })
                        })
            }
        },

        mounted () {
            this.load()
            this.$root.$on('changed::modules', function (modules) {
                this.selected_modules = modules
            })
        }
    })
</script>
@endpush

