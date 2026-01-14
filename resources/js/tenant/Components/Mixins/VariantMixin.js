import DatatableHelperMixin from "../../../common/Mixin/Global/DatatableHelperMixin";
import {PRODUCTS, VARIANT} from "../../Config/ApiUrl-CP";

export default {
    mixins: [DatatableHelperMixin],

    data() {
        return {
            options: {
                afterRequestSuccess: ({data}) => {
                    this.tempVariants = data.data;
                    this.$emit('variant-update', this.tempVariants)
                },
                name: this.$t('tenant_groups'),
                url: `/app/variant/product/${this.productId}`,
                responsive: true,
                dataTableWrapper: true,
                tableShadow: false,
                showTableManager: false,
                showSearch: true,
                showFilter: true,
                showCount: true,
                filters: [],
                showHeader: true,
                columns: [
                    {
                        title: this.$t('profile'),
                        type: 'component',
                        key: 'id',
                        componentName: 'variant-profile',
                    },
                    {
                        title: this.$t('description'),
                        type: 'custom-html',
                        key: 'description',
                        modifier: (value) => value ? `<p class="mb-0 max-width-250">${value}</p>` : '-',
                    },
                    {
                        title: this.$t('quantity'),
                        type: 'custom-html',
                        key: 'quantity',
                        modifier: (value) => `<span>${value ?? '-'}</span>`,
                    },
                    {
                        title: this.$t('Price'),
                        type: 'custom-html',
                        key: 'selling_price', // temporary key
                        modifier: (value) => `
                            <div class="row no-gutters">
                                <div class="col-8 pr-2">
                                    <p class="text-muted">${this.$t('avg_unit_price')}:</p>
                                </div>
                                <div class="col-4">
                                    <p>-</p>
                                </div>
                            </div>
                            <div class="row no-gutters">
                                <div class="col-8 pr-2">
                                    <p class="text-muted">${this.$t('sale_price')}:</p>
                                </div>
                                <div class="col-4">
                                    <p> ${ value ?? '-' } </p>
                                </div>
                            </div>
                        `,
                    },
                    {
                        title: this.$t('tax') + '(%)',
                        type: 'custom-html',
                        key: 'tax',
                        modifier: (value) => `<span>${value ? (+value.percentage).toFixed(0) : '-'}</span>`,
                    },
                    {
                        title: this.$t('LSR'),
                        type: 'custom-html',
                        key: 'stock_reminder_quantity',
                        modifier: (value) => `<span>${value ?? '-'}</span>`,
                    },
                    {
                        title: this.$t('actions'),
                        type: 'action',
                        key: 'invoice'
                    },
                ],
                actionType: "dropdown",
                showAction: true,
                actions: [
                    {
                        title: this.$t('edit'),
                        icon: 'edit',
                        type: 'edit',
                    },
                    {
                        title: this.$t('deactivate'),
                        type: 'status_change',
                        name: 'deactivate',
                        component: '',
                        modalId: 'product-modal',
                        modifier: ({status: {name: status}}) => {
                            return status === 'status_active'
                        },
                    },
                    {
                        title: this.$t('activate'),
                        type: 'status_change',
                        name: 'activate',
                        component: '',
                        modalId: 'product-modal',
                        modifier: ({status: {name: status}}) => {
                            return status !== 'status_active'
                        },
                    },
                    {
                        title: this.$t('show_stock_overview'),
                        type: 'show_stock_overview',
                        name: 'show_stock_overview',
                    },
                    {
                        title: this.$t('delete'),
                        type: 'modal',
                        icon: 'trash-2',
                        component: 'app-confirmation-modal',
                        modalId: 'app-confirmation-modal',
                        url: VARIANT,
                        name: 'delete'
                    },
                ],
                rowLimit: 10,
                orderBy: 'desc',
                paginationType: "pagination"
            },
        }
    },
}
