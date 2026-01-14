export const SelectableBranches = {
    computed: {
        branches() {
            if (this.$store.getters.branchesOptions.data) return this.$store.getters.branchesOptions.data.map(e => {
                return {
                    id: e.id,
                    name: e.name
                }
            })
        }
    },
    watch: {
        'branches': {
            handler: function () {
                this.options.filters.find(({key, option}) => {
                    if (key === 'branches') option.push(...this.branches)
                })
            },
            immediate: true
        }
    },
    mounted() {
        this.$store.dispatch('getSelectableBranches')
    }
}
