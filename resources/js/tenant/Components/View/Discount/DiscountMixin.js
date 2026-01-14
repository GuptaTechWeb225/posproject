import {formatDateToLocal, numberWithCurrencySymbol} from "../../../Helper/Helper";
import {textTruncate} from "../../../../common/Helper/Support/TextHelper";
import {DISCOUNT_LIST, INVOICE_LIST, ORDER_MAX_MIN_PRICE} from "../../../Config/ApiUrl-CP";
import DatatableHelperMixin from "../../../../common/Mixin/Global/DatatableHelperMixin";
import {mapGetters} from "vuex";

export default {
    mixins: [DatatableHelperMixin],
    data() {
        return {
            options: {
                name: this.$t('discount'),
                url: null,
                showCount: true,
                showClearFilter: true,
                showHeader: true,
                columns: [
                    {
                        title: this.$t('discount_name'),
                        type: 'text',
                        key: 'name',
                    },
                    {
                        title: this.$t('discount_type'),
                        type: 'custom-html',
                        key: 'discount_type',
                        modifier: value => `<p class="mb-0">${this.$t(value)}</p>`
                    },
                    {
                        title: this.$t('type'),
                        type: 'custom-html',
                        key: 'type',
                        modifier: value => `<p class="mb-0">${value  === 'flat' ? this.$t('fixed') : this.$t('percentage') }</p>`
                    },
                    {
                        title: this.$t('amount'),
                        type: 'custom-html',
                        key: 'value',
                        titleAlignment: 'right',
                        modifier: (value, { type: discountType }) => {
                            return `<p class="text-right mb-0">${discountType.toLowerCase() !== 'percentage' ? numberWithCurrencySymbol(value) : value + ' %'}</p>`
                        }
                    },
                    {
                        title: this.$t('total_products'),
                        type: 'custom-html',
                        key: 'discount_products_count',
                        titleAlignment: 'right',
                        modifier: total_products => `<p class="text-right mb-0">${total_products ?? '-'}</p>`
                    },
                    {
                        title: this.$t('start_at'),
                        type: 'custom-html',
                        key: 'start_at',
                        modifier: startAt => `<span>${formatDateToLocal(startAt)}</span>`
                    },
                    {
                        title: this.$t('end_at'),
                        type: 'custom-html',
                        key: 'end_at',
                        modifier: endAt => `<span>${formatDateToLocal(endAt)}</span>`
                    },
                    {
                        title: this.$t('note'),
                        type: 'custom-html',
                        key: 'note',
                        modifier: value => value ? `<p class="mb-0">${textTruncate(value, 80)}</p>` : '-'
                    },
                    {
                        title: this.$t('status'),
                        type: 'custom-html',
                        key: 'status',
                        isVisible: true,
                        modifier: (value) => {
                            return `<span class="badge badge-pill badge-${value.class}">${value.translated_name}</span>`;
                        }
                    },
                    {
                        title: this.$t('actions'),
                        type: 'action',
                        key: 'invoice',
                        isVisible: true
                    },
                ],
                actionType: "dropdown",
                actions: [
                    {
                        title: this.$t('edit'),
                        type: 'modal',
                        component: 'app-tenant-group-modal',
                        modalId: 'app-modal',
                    },
                    {
                        title: this.$t('activate'),
                        type: 'active_inactive',
                        alias: 'status_active',
                        modifier: row => (row.status.name === 'status_inactive')
                    },
                    {
                        title: this.$t('de_activate'),
                        type: 'active_inactive',
                        alias: 'status_inactive',
                        modifier: row => (row.status.name === 'status_active')
                    },
                    {
                        title: this.$t('delete'),
                        type: 'modal',
                        component: 'app-confirmation-modal',
                        modalId: 'app-confirmation-modal',
                        url: DISCOUNT_LIST,
                        name: 'delete'

                    }
                ],
                filters: [
                    {
                        title: this.$t('date'),
                        type: "range-picker",
                        key: "expense_date",
                        option: ["today", "thisMonth", "last7Days", "thisYear"]
                    },
                ],
                paginationType: "pagination",
                responsive: true,
                rowLimit: 10,
                showAction: true,
                orderBy: 'desc',
                search: true,
            },
        }
    },
    computed:{
        ...mapGetters(['getBranchOrWarehouseId']),
    },
    mounted(){
        this.updateUrl(DISCOUNT_LIST);
    },
    watch: {
        getBranchOrWarehouseId(){
            this.updateUrl(DISCOUNT_LIST);
        },
    }
}
