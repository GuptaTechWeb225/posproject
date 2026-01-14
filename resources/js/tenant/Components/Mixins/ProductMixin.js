import DatatableHelperMixin from "../../../common/Mixin/Global/DatatableHelperMixin";
import {
    PRODUCTS, SELECTABLE_GROUPS,
    SELECTABLE_BRANDS, SELECTABLE_ATTRIBUTES,
    SELECTABLE_UNITS, SELECTABLE_CATEGORIES, SELECTABLE_SUB_CATEGORIES
} from "../../Config/ApiUrl-CP";
import {createdBy} from "../../../store/Tenant/Mixins/CreatedByMixin";
import {axiosGet, urlGenerator} from "../../../common/Helper/AxiosHelper";
import SelectableStatusMixin from "../../Helper/SelectableOptions/SelectableStatusMixin";
import {numberWithCurrencySymbol} from "../../Helper/Helper";

export default {
    mixins: [DatatableHelperMixin, createdBy, SelectableStatusMixin],
    data() {
        return {
            options: {
                name: this.$t('tenant_groups'),
                url: PRODUCTS,
                showCount: true,
                showClearFilter: true,
                showHeader: true,
                enableRowSelect: false,
                sortByFilter: {
                    isVisible: false,
                    label: this.$t("sort_by") + ':',
                    key: 'sort_by',
                    options: [
                        this.$t('most_recent'),
                        this.$t('product_name_a_z'),
                        this.$t('product_name_z_a'),
                        this.$t('last_created')
                    ]
                },
                columns: [
                    {
                        title: this.$t('products'),
                        type: 'component',
                        key: 'name',
                        componentName: 'products-profile',
                    },
                    {
                        title: this.$t('group'),
                        type: 'custom-html',
                        key: 'group',
                        modifier: (value) => value ? `<span>${value.name}</span>` : '-',
                    },
                    {
                        title: this.$t('product_type'),
                        type: 'component',
                        key: 'product_type',
                        componentName: 'is-variant-products',
                    },
                    {
                        title: this.$t('no_of_variants'),
                        type: 'expandable-column',
                        key: 'variants_count',
                        className: 'secondary',
                        componentName: 'products-variant-details',
                        showTitle: this.$t('show'),
                        hideTitle: this.$t('hide'),
                        showIcon: 'eye',
                        hideIcon: 'eye-off',
                        modifier: (value, row) => {
                            return {
                                title: this.$t('show'),
                                expandable: row.product_type === 'variant',
                                button: true,
                                visible: row.product_type === 'variant',
                            }
                        }
                    },
                    {
                        title: this.$t('status'),
                        type: 'custom-html',
                        key: 'status',
                        modifier: (value) =>
                            `<small class="text-capitalize bg-${value.translated_name === 'Inactive' ? 'danger' : 'success'} px-3 py-1 text-white" style="border-radius: 8rem;">${value.translated_name}</small>`,
                    },
                    {
                        title: this.$t('selling_price'),
                        type: 'custom-html',
                        key: 'variants',
                        titleAlignment: 'right',
                        modifier: (value) => {
                            if (value.length > 1 || !value.length) return `<span>-</span>`;
                            const sellingPrice = value[0].selling_price;

                            if (!sellingPrice) return `<span>-</span>`
                            if (typeof sellingPrice === 'string') return `<p class="text-right">${numberWithCurrencySymbol(sellingPrice)}</p>`
                            return `<p class="text-right">${Number.isInteger(sellingPrice) ? numberWithCurrencySymbol( sellingPrice ) : numberWithCurrencySymbol(sellingPrice.toFixed(2))}</p>`
                        }
                    },
                    {
                        title: this.$t('actions'),
                        type: 'action',
                        key: 'actions',
                    },
                ],
                actionType: "dropdown",
                actions: [
                    {
                        title: this.$t('edit'),
                        icon: 'edit',
                        type: 'edit',
                        name: 'edit',
                        modifier: () => this.$can('update_products')
                    },
                    {
                        title: this.$t('deactivate'),
                        type: 'modal',
                        name: 'deactivate',
                        component: '',
                        modalId: 'product-modal',
                        modifier: ({status: {name: status}}) => {
                            return status === 'status_active' && this.$can('change_status_products')
                        },
                    },
                    {
                        title: this.$t('activate'),
                        type: 'modal',
                        name: 'activate',
                        component: '',
                        modalId: 'product-modal',
                        modifier: ({status: {name: status}}) => {
                            return status !== 'status_active' && this.$can('change_status_products')
                        },
                    },
                    {
                        title: this.$t('show_stock_overview'),
                        name: 'show_stock_overview',
                        component: '',
                        modifier: row => {
                            return row.product_type.toLowerCase() === 'single'
                        },
                    },
                    {
                        title: this.$t('delete'),
                        icon: 'trash-2',
                        type: 'modal',
                        name: 'delete',
                        url: PRODUCTS,
                        component: 'app-confirmation-modal',
                        modalId: 'app-confirmation-modal',
                        modifier: () => this.$can('delete_products')
                    },

                ],
                filters: [
                    // "created" filter
                    {
                        title: this.$t('created_date'),
                        type: "range-picker",
                        key: "date",
                        option: ["today", "thisMonth", "last7Days", "thisYear"]
                    }, 
                    {
                        title: this.$t('groups'),
                        type: 'search-and-select-filter',
                        key: 'groups',
                        settings: {
                            url: urlGenerator('app/selectable-groups'),
                            modifire: ({id, name}) => ({id, name}),
                            params: {},
                            per_page: 10,
                            queryName: 'search_by_name',
                            loader: 'app-pre-loader'
                        },
                        listValueField: 'name'
                    },
                    // "category" filter
                    {
                        title: this.$t('category'),
                        type: 'search-and-select-filter',
                        key: 'categories',
                        settings: {
                            url: urlGenerator('app/selectable-categories'),
                            modifire: ({id, name}) => ({id, name}),
                            params: {},
                            per_page: 10,
                            queryName: 'search_by_name',
                            loader: 'app-pre-loader'
                        },
                        listValueField: 'name'
                    },
                    // "brand" filter
                    {
                        title: this.$t('brand'),
                        type: 'search-and-select-filter',
                        key: 'brands',
                        settings: {
                            url: urlGenerator('app/selectable-brands'),
                            modifire: ({id, name}) => ({id, name}),
                            params: {},
                            per_page: 10,
                            queryName: 'search_by_name',
                            loader: 'app-pre-loader'
                        },
                        listValueField: 'name'
                    },
                    // "units" filter
                    {
                        title: this.$t('units'),
                        type: 'search-and-select-filter',
                        key: 'units',
                        settings: {
                            url: urlGenerator('app/selectable-units'),
                            modifire: ({id, name}) => ({id, name}),
                            params: {},
                            per_page: 10,
                            queryName: 'search_by_name',
                            loader: 'app-pre-loader'
                        },
                        listValueField: 'name'
                    },
                    // product statuses
                    this.getStatusFilterOptions('product'),
                    // "product_type" filter
                    {
                        title: this.$t('product_type'),
                        type: "radio",
                        key: "product_type",
                        initValue: '',
                        header: {title: '', description: ''},
                        option: [
                            {id: 'single', name: this.$t('single')},
                            {id: 'variant', name: this.$t('variant')}
                        ],
                        listValueField: 'name'
                    },
                ],
                paginationType: "pagination",
                responsive: true,
                rowLimit: 10,
                showAction: true,
                search: true,
            },
        }
    },
    mounted() {
        this.$store.dispatch('getTag');
    },
    methods: {
        filterInitiate(urls) {
            for (const [key, url] of Object.entries(urls)) {
                axiosGet(url).then(response => {
                    let name = this.options.filters.find(element => element.key === key);
                    name.option = response.data.data.map(name => {
                        return {
                            id: name.id,
                            name: name.name
                        }
                    });
                })
            }
        },
    }
}
