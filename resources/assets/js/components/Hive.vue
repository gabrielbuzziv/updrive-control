<template>
    <div class="hive">
        <div class="honeycomb" v-for="module in modules">
            <div class="cavity" :class="{ 'disabled': ! isEnabled(module) }">
                <div class="out">
                    <div class="in" :class="{ 'selected': module.selected }" @click.prevent="onSelect(module)">
                        <span class="content">
                            <strong>{{ module.name }}</strong>
                            <span v-if="module.requirements.length">
                                <b>Requirements</b>
                                <p>{{ module.requirements.map(requirement => requirement.name).join(', ') }}</p>
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
    export default {
        props: {
            hive: {
                type: Array,
                required: true
            }
        },

        data () {
            return {
                modules: []
            }
        },

        watch: {
            hive () {
                this.modules = this.hive
            }
        },

        methods: {
            isEnabled (module) {
                const selected     = this.modules.filter(module => module.selected)
                const requirements = module.requirements.map(requirement => {
                    return ! ! selected.filter(select => select.id == requirement.id).length
                }).filter(enabled => enabled)

                return requirements.length == module.requirements.length
            },

            unselectChilds (module) {
                const childs = this.modules.filter(child => {
                    return ! ! child.requirements.filter(requirement => requirement.id == module.id).length
                })

                childs.map(child => {
                    child.selected = false
                })
            },

            onSelect (module) {
                // If this module is enabled, disable the select option.
                if (! this.isEnabled(module)) {
                    return false
                }

                // If this module is selected do something before unselect it.
                if (module.selected) {
                    this.unselectChilds(module)
                }

                const index                  = this.modules.indexOf(module)
                this.modules[index].selected = ! this.modules[index].selected
                this.$root.$emit('changed::modules', this.modules.filter(module => module.selected).map(module => module.id))
            }
        }
    }
</script>