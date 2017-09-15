<template>
    <modal :id="id" size="lg">
        <div class="modal-header">
            <button class="close" @click="$root.$emit('hide::modal', id)"><i class="mdi mdi-close"></i></button>

            <h4 class="modal-title">
                Account Backup: {{ account.name }}
            </h4>
        </div>

        <div class="modal-body margin-top-20">
            <ul class="nav nav-tabs nav-justified">
                <li class="active">
                    <a href="#database" data-toggle="tab">
                        <i class="mdi mdi-database margin-right-5"></i>
                        Database
                    </a>
                </li>
                <li>
                    <a href="#storage" data-toggle="tab">
                        <i class="mdi mdi-archive margin-right-5"></i>
                        Storage
                    </a>
                </li>
                <li>
                    <a href="#settings" data-toggle="tab">
                        <i class="mdi mdi-settings margin-right-5"></i>
                        Settings
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="database">
                    <database :account="account" />
                </div>
                <div class="tab-pane" id="storage">
                    <storage />
                </div>
                <div class="tab-pane" id="settings">
                    <settings />
                </div>
            </div>

            <div class="clearfix"></div>
        </div>

        <div class="modal-footer">
            <button class="btn btn-default pull-left" type="button" @click="$root.$emit('hide::modal', id)">
                Close
            </button>
        </div>
    </modal>
</template>

<script type="text/babel">
    import Modal from './Modal'
    import Database from './backups/Database'
    import Storage from './backups/Storage'
    import Settings from './backups/Settings'

    export default {
        components: { Modal, Database, Storage, Settings },

        data () {
            return {
                id: 'backupModal',
                account: {},
                loading: {
                    account: false,
                },
            }
        },

        methods: {
            load (accountId) {
                if (this.account.id != accountId) {
                    this.loading.account = true
                }

                this.$http.get(`/accounts/${accountId}/details`)
                        .then(response => {
                            this.account = response.data
                            this.loading.account = false
                        })
            }
        },

        mounted () {
            this.$root.$on('load::backup', accountId => this.load(accountId))
        },

        beforeDestroy () {
            this.$root.$off('load::backup')
        }
    }
</script>
