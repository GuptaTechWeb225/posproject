export const VariationMixin = {
    computed: {
        attributeVariations() {
            return this.$store.getters.attributeVariations.map(attributeVariation => {
                return {
                    id: attributeVariation.id,
                    name: attributeVariation.name,
                    color: attributeVariation.color
                }
            })
        }
    },
    watch: {
        'variations': {
            handler: function (attributeVariation) {
                this.attributeVariations = attributeVariation
            },
            immediate: true
        }
    }
}
