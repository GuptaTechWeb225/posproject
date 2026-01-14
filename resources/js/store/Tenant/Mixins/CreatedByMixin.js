export const createdBy = {
    computed: {
        createdBy() {
            return this.$store.getters.getCreatedBy.map(createdUser => {
                return {
                    id: createdUser.id,
                    value: createdUser.full_name
                }
            })
        }
    },
    watch: {
        'createdBy': {
            handler: function () {
                this.options.filters.find((filter) => {
                    if (!filter) return;
                    if (filter.key === 'created_by') filter.option.push(...this.createdBy)
                })
            },
            immediate: true
        }
    },
    mounted() {
        this.$store.dispatch('getCreatedBy')
    }
}
