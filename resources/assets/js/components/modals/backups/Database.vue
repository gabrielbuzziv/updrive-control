<template>
    <div class="panel panel-default">
        <div class="panel-body">
            <div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>File</th>
                            <th>Date</th>
                            <th>Size</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr v-for="backup in account.backups">
                            <td>{{ backup.filename }}</td>
                            <td>{{ backup.filesize | filesize }}</td>
                            <td>{{ backup.created_at }}</td>
                            <td align="right">
                                <button class="btn btn-default btn-xs">
                                    <i class="mdi mdi-download"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="panel-footer">
            <button class="btn btn-success btn-sm" @click="onBackup" :disabled="loading.backup">
                <span v-if="loading.backup">Requesting Backup</span>
                <span v-else>Create Backup</span>
            </button>
        </div>
    </div>
</template>

<script type="text/babel">
    export default {
        props: ['account'],

        data () {
            return {
                loading: {
                    backup: false,
                    databases: false
                }
            }
        },

        filters: {
            filesize (bytes) {
                const thresh = 1024;

                if (Math.abs(bytes) < thresh) {
                    return bytes + ' B';
                }
                const units = ['kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

                let u = - 1;

                while (Math.abs(bytes) >= thresh && u < units.length - 1) {
                    bytes /= thresh;
                    ++ u;
                }

                return bytes.toFixed(1) + ' ' + units[u];
            },
        },

        methods: {
            onBackup () {
                this.loading.backup = true
                this.$http.get(`/accounts/${this.account.id}/backup`)
                        .then(response => {
                            this.loading.backup = false
                        })
            }
        }
    }
</script>
