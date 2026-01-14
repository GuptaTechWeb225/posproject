export const expense_area = {
    computed: {
        expenseAreas() {
            return this.$store.getters.expenseAreaOptions.map(expenseArea => {
                return {
                    id: expenseArea.id,
                    value: expenseArea.name
                }
            })
        }
    },
    watch: {
        'expenseAreas': {
            handler: function () {
                this.options.filters.find(({key, option}) => {
                    if (key === 'expense_area') option.push(...this.expenseAreas)
                })
            },
            immediate: true
        }
    },
    mounted() {
        this.$store.dispatch('getExpenseArea')
    }
}
