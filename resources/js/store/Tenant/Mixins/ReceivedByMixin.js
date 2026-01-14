export const SelectableReceivedBy = {
    computed: {
        suppliers() {
            if (this.$store.getters.receivedByOptions.data) return this.$store.getters.receivedByOptions.data.map(e => {
                return {
                    id: e.id,
                    name: e.full_name
                }
            })
        }
    },
    watch: {
        'suppliers': {
            handler: function () {
                this.options.filters.find(({key, option}) => {
                    if (key === 'received_by') option.push(...this.suppliers)
                })
            },
            immediate: true
        }
    },
    mounted() {
        this.$store.dispatch('getSelectableReceivedBy')
    }
}
