export const TagsMixin = {
    computed: {
        tagLists() {
            return this.$store.getters.tagsOptions.map(tag => {
                return {
                    id: tag.id,
                    name: tag.name,
                    color: tag.color
                }
            })
        }
    },
    watch: {
        'tagLists': {
            handler: function (tags) {
                this.tagOptions = tags
            },
            immediate: true
        }
    }
}
