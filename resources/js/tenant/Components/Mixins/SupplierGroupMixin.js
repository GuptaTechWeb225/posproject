import DatatableHelperMixin from "../../../common/Mixin/Global/DatatableHelperMixin";
export default {
    mixins: [DatatableHelperMixin],
    data() {

        return {
            options: {
                name: this.$t('tenant_groups'),

                showHeader: true,
                columns: [
                    {
                        title: this.$t('title'),
                        type: 'text',
                        key: 'name',

                    },
                    {
                        title: this.$t('discount'),
                        type: 'object',
                        key: 'company',
                    },
                    {
                        title: this.$t('actions'),
                        type: 'action',
                        key: 'actions',
                    },
                ],

            },
        }
    }
}
