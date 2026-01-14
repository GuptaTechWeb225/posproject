export const SelectableSuppliers = {
    computed: {
        suppliers() {
            return this.$store.getters.supplierOptions.map(e => {
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
                    if (key === 'suppliers') option.push(...this.suppliers)
                })
            },
            immediate: true
        }
    },
    mounted() {
        this.$store.dispatch('getSelectableSuppliers')
    }
}
