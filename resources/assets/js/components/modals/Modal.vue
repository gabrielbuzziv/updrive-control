<template>
    <div class="modal fade" :id="id">
        <div class="modal-dialog" :class="`modal-${size}`">
            <div class="modal-content">
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
    export default {
        props: {
            id: {
                type: String,
                required: true
            },

            size: {
                type: String,
                default: 'md'
            }
        },

        methods: {
            show () {
                $(`#${this.id}`).modal('show')
            },

            hide () {
                $(`#${this.id}`).modal('hide')
            }
        },

        mounted () {
            this.$root.$on('show::modal', id => id == this.id && this.show())
            this.$root.$on('hide::modal', id => id == this.id && this.hide())
        },

        beforeDestroy () {
            this.$root.$off('show::modal')
            this.$root.$off('hide::modal')
        }
    }
</script>
