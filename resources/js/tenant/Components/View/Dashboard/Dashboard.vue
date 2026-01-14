<template>
    <div class="content-wrapper">
        <div>
            <h3 class="mb-3">{{$t('dashboard')}}</h3>
            <div>
                <!-- highlight card -->

                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>{{ $t('highlight') }}</div>
                        <div class="dropdown d-inline-block btn-dropdown">
                            <a class="btn btn-link text-primary shadow-none dropdown-toggle ml-0 mr-2" href="#"
                               role="button"
                               data-toggle="dropdown" aria-expanded="false">
                                <span class="text-capitalize">{{ $t(highlight) }}</span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="#"
                                   @click="highlight = 'last_week'">{{ $t('last_week') }}</a>
                                <a class="dropdown-item" href="#"
                                   @click="highlight = 'last_month'">{{ $t('last_month') }}</a>
                                <a class="dropdown-item" href="#"
                                   @click="highlight = 'this_month'">{{ $t('this_month') }}</a>
                                <a class="dropdown-item" href="#"
                                   @click="highlight = 'this_year'">{{ $t('this_year') }}</a>
                                <a class="dropdown-item" href="#"
                                   @click="highlight = 'last_year'">{{ $t('last_year') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body mb-5">
                        <app-overlay-loader v-if="isHighlightLoading"/>
                        <div v-else>
                            <div class="row">
                                <div class="col-12 col-sm-6 col-md-3">
                                    <div class="grid-card d-flex align-items-center">
                                        <div class="icon text-white">
                                            <app-icon name="dollar-sign"/>
                                        </div>
                                        <div>
                                            <h5>
                                                {{ numberWithCurrencySymbol(highlightsData.totalSales) }}
                                            </h5>
                                            <div class="text-muted">{{ $t('total_sales') }}</div>
                                        </div>
                                    </div>
                                </div>

                              <!--  cashier can't view this portion -->
                              <div class="col-12 col-sm-6 col-md-3" v-if="userRole.id !== 5">
                                    <div class="grid-card d-flex align-items-center">
                                        <div class="icon text-white">
                                            <app-icon name="activity"/>
                                        </div>
                                        <div>
                                            <h5>{{ numberWithCurrencySymbol(highlightsData.totalExpense) }}</h5>
                                            <div class="text-muted">{{$t('total_expenses')}}</div>
                                        </div>
                                    </div>
                                </div>


                              <!--  cashier can't view this portion -->
                                <div class="col-12 col-sm-6 col-md-3" v-if="userRole.id !== 5">
                                    <div class="grid-card d-flex align-items-center">
                                        <div class="icon text-white">
                                            <app-icon name="zap"/>
                                        </div>
                                        <div>
                                            <h5>{{ numberWithCurrencySymbol(highlightsData.netProfit) }}</h5>
                                            <div class="text-muted">{{$t('net_profit')}}</div>
                                        </div>
                                    </div>
                                </div>

                              <!--  cashier can't view this portion -->
                                <div class="col-12 col-sm-6 col-md-3" v-if="userRole.id !== 5">
                                    <div class="grid-card d-flex align-items-center">
                                        <div class="icon text-white">
                                            <app-icon name="credit-card"/>
                                        </div>
                                        <div>
                                            <h5>{{ numberWithCurrencySymbol(highlightsData.totalDue) }}</h5>
                                            <div class="text-muted">{{ $t('due') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- charts -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="card card-with-shadow border-0 h-100" v-if="!userIsACashier">
                            <div class="card-header bg-transparent px-5 py-4">
                                <div class="d-flex align-items-center text-secondary">
                                    <h4 class="m-0 p-0">{{ $t("sales_and_purchase") }}</h4>

                                    <div class="dropdown d-inline-block btn-dropdown" v-if="!isSalesPurchaseLoading">
                                        <a class="btn btn-link text-primary shadow-none dropdown-toggle ml-0 mr-2" href="#"
                                           role="button"
                                           data-toggle="dropdown" aria-expanded="false">
                                            <span class="text-capitalize">{{ $t(salesDropdownMenu) }}</span>
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="#"
                                               @click="salesDropdownMenu = 'last_week'">{{ $t('last_week') }}</a>
                                            <a class="dropdown-item" href="#"
                                               @click="salesDropdownMenu = 'this_week'">{{ $t('this_week') }}</a>
                                            <a class="dropdown-item" href="#"
                                               @click="salesDropdownMenu = 'last_month'">{{ $t('last_month') }}</a>
                                            <a class="dropdown-item" href="#"
                                               @click="salesDropdownMenu = 'this_month'">{{ $t('this_month') }}</a>
                                            <a class="dropdown-item" href="#"
                                               @click="salesDropdownMenu = 'this_year'">{{ $t('this_year') }}</a>
                                            <a class="dropdown-item" href="#"
                                               @click="salesDropdownMenu = 'last_year'">{{ $t('last_year') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <app-overlay-loader v-if="isSalesPurchaseLoading" />
                                <app-chart
                                    v-else
                                    type="bar-chart"
                                    :height="360"
                                    :labels="salesPurchaseData.labels"
                                    :data-sets="salesPurchaseData.dataSet"
                                />
                                <div class="d-flex justify-content-center align-items-center mt-2" style="gap: 15px;" v-if="!isSalesPurchaseLoading">
                                    <div v-for="(item, index) in salesPurchaseData.dataSet"
                                         :key="`chart-item-${index}`">
                                        <div class="d-flex align-items-center">
                    <span style="width: 10px; height: 10px; border-radius: 50%; margin-right:10px;"
                          :style="{ backgroundColor: item.backgroundColor }"></span>{{ item.label }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card" v-else>
                            <div class="card-header bg-transparent px-5 py-4">
                                <div class="d-flex align-items-center text-secondary">
                                    <h4 class="m-0 p-0">{{ $t('recent_sales') }}</h4>
                                    <a class="btn btn-link text-primary" href="javascript:void(0)"
                                       @click="viewAllInvoice">{{ $t('view_all_invoices') }}</a>
                                </div>
                            </div>
                            <template v-if="isRecentSalesLoading">
                                <div class="card-body datatable">
                                    <app-overlay-loader/>
                                </div>
                            </template>
                            <div class="card-body datatable" v-else>
                                <custom-table :options="recentSalesTable.options" :data="recentSalesTable.data"
                                              @activate-invoice-view-modal="handleInvoiceModalActiveEvent"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-with-shadow border-0 h-100">
                            <div class="card-header bg-transparent px-5 py-4">
                                <div class="d-flex align-items-center text-secondary">
                                    <h4 class="m-0 p-0">{{ $t('top_selling_products') }}</h4>
                                </div>
                            </div>
                            <template v-if="isTopSellingLoading">
                                <div class="card-body">
                                    <app-overlay-loader/>
                                </div>
                            </template>
                            <div class="card-body" v-else>
                                <app-chart class="mb-primary"
                                           type="dough-chart"
                                           :height="360"
                                           :labels="topSellingData.labels"
                                           :data-sets="topSellingData.dataSet"/>
                                <div class="chart-data-list">
                                    <div class="row">
                                        <div class="col d-inline-flex flex-wrap" style="gap: 10px;">
                                            <div v-for="(item, index) in topSellingData.dataSet[0].backgroundColor"
                                                 :key="index"
                                                 class="d-flex align-items-center">
                                                <div
                                                    style="width: 10px; height: 10px; border-radius: 3px; margin-right:10px;"
                                                    :style="{ backgroundColor: item }"></div>
                                                <span>{{
                                                        topSellingData.labels[index]
                                                    }} {{ topSellingData.dataSet[0].data[index] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- sales tables -->
                <div class="row mb-4">
                    <div class="col">
                        <div class="card" v-if="!userIsACashier">
                            <div class="card-header bg-transparent px-5 py-4">
                                <div class="d-flex align-items-center text-secondary">
                                    <h4 class="m-0 p-0">{{ $t('recent_sales') }}</h4>
                                    <a class="btn btn-link text-primary" href="javascript:void(0)"
                                       @click="viewAllInvoice">{{ $t('view_all_invoices') }}</a>
                                </div>
                            </div>
                            <template v-if="isRecentSalesLoading">
                                <div class="card-body datatable">
                                    <app-overlay-loader/>
                                </div>
                            </template>
                            <div class="card-body datatable" v-else>
                                <custom-table :options="recentSalesTable.options" :data="recentSalesTable.data"
                                              @activate-invoice-view-modal="handleInvoiceModalActiveEvent"/>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header bg-transparent px-5 py-4">
                                <div class="d-flex align-items-center text-secondary">
                                    <h4 class="m-0 p-0">{{ $t('stock_summary') }}</h4> ({{ $t('all_branch') }})
                                    <a class="btn btn-link text-primary" href="javascript:void(0)"
                                       @click="viewAllStock">{{ $t('view_all_stocks') }}</a>
                                </div>
                            </div>
                            <template v-if="isStockSummaryLoading">
                                <div class="card-body datatable">
                                    <app-overlay-loader/>
                                </div>
                            </template>
                            <div class="card-body datatable" v-else>
                                <custom-table :options="stockSummaryData.options" :data="stockSummaryData.data"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header bg-transparent px-5 py-4">
                                <div class="d-flex align-items-center text-secondary">
                                    <h4 class="m-0 p-0">{{ $t("top_customer") }}</h4>
                                    <a class="btn btn-link text-primary" href="javascript:void(0)"
                                       @click="customerList">{{ $t('view_all_customers') }}</a>
                                </div>
                            </div>
                            <template v-if="isCustomerLoading">
                                <div class="card-body datatable">
                                    <app-overlay-loader/>
                                </div>
                            </template>
                            <div class="card-body datatable" v-else>
                                <custom-table :options="topCustomer.options" :data="topCustomer.data"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <app-invoice-modal
            v-if="invoiceModalActive"
            :order-id="invoiceIdToShow"
            :value="true"
            @modal-close="closeModal"
        />
    </div>
</template>

<script>
import CustomTable from "./CustomTable";
import {axiosGet, urlGenerator} from "../../../../common/Helper/AxiosHelper";
import {
    DASHBOARD_HIGHLIGHT,
    DASHBOARD_TOP_SELLING_PRODUCTS,
    DASHBOARD_TOP_CUSTOMERS,
    DASHBOARD_PURCHASE_SALE_STATISTICS
    , DASHBOARD_RECENT_SALES, DASHBOARD_STOCK_SUMMARY
} from "../../../Config/ApiUrl-CP";
import {mapGetters} from "vuex";
import {numberWithCurrencySymbol} from "../../../Helper/Helper";

export default {
    name: "Dashboard",
    components: {CustomTable},
    data() {
        return {
            userRole: '',
            invoiceModalActive: false,
            invoiceIdToShow: '',
            numberWithCurrencySymbol,
            isSalesPurchaseLoading: false,
            salesDropdownMenu: 'this_week',
            salePurchaseUrl: DASHBOARD_PURCHASE_SALE_STATISTICS,
            salesPurchaseData: {
                labels: [],
                dataSet: [
                    {
                        label: this.$t('sales'),
                        barPercentage: 0.5,
                        barThickness: 30,
                        borderWidth: 1,
                        backgroundColor: "#3874ff",
                        data: []
                    },
                    {
                        label: this.$t('purchase'),
                        barPercentage: 0.5,
                        barThickness: 30,
                        borderWidth: 1,
                        backgroundColor: "#5bc5d5",
                        data: []
                    }
                ]
            },
            isCustomerLoading: false,
            topCustomerUrl: DASHBOARD_TOP_CUSTOMERS,
            topCustomer: {
                options: {
                    cols: [
                        {name: this.$t('customer_name'), key: 'name', class: 'text-primary'},
                        {name: this.$t('total_amount'), key: 'amount', class: '', alignment: 'right'}
                    ]
                },
                data: []
            },
            isStockSummaryLoading: false,
            stockSummaryUrl: DASHBOARD_STOCK_SUMMARY,
            stockSummaryData: {
                options: {
                    cols: [
                        {name: this.$t('stock_alert'), key: 'name', class: 'text-primary'},
                        {name: this.$t('quantity'), key: 'amount', class: 'text-danger', alignment: 'right'}
                    ]
                },
                data: []
            },
            isHighlightLoading: false,
            highlight: 'this_week',
            highlightsData: {},
            highlightUrl: DASHBOARD_HIGHLIGHT,
            topSellingProductsUrl: DASHBOARD_TOP_SELLING_PRODUCTS,
            loader: false,
            isTopSellingLoading: false,
            topSellingData: {
                labels: [],
                dataSet: [
                    {
                        barThickness: 30,
                        borderWidth: 0,
                        backgroundColor: [
                            '#00cc4a',
                            '#54caf2',
                            '#bb6bdb',
                            '#f28319',
                            '#808080'
                        ],
                        data: []
                    }
                ]
            },
            isRecentSalesLoading: false,
            recentSalesUrl: DASHBOARD_RECENT_SALES,
            recentSalesTable: {
                options: {
                    cols: [
                        {name: this.$t('invoice_id'), key: 'id', class: 'text-primary', type: 'modal'},
                        {name: this.$t('customer'), key: 'customer', class: 'text-primary',},
                        {name: this.$t('grand_total'), key: 'total', alignment: 'right'},
                        {name: this.$t('paid'), key: 'paid', alignment: 'right'},
                        {name: this.$t('due'), key: 'due', alignment: 'right'},
                        {name: this.$t('order_status'), key: 'status'}
                    ]
                },
                data: []
            },

        }
    },
    created() {
    },
    methods: {
        handleInvoiceModalActiveEvent(invoiceId) {
            this.invoiceIdToShow = invoiceId;
            this.invoiceModalActive = true;
        },
        closeModal() {
            $('#invoice-view-modal').modal('hide')
            this.invoiceIdToShow = '';
            this.invoiceModalActive = false;
            this.$emit('modal-close');
        },
        getSalePurchaseStatistics() {
            this.isSalesPurchaseLoading = true
            axiosGet(`${this.salePurchaseUrl}&date=${this.salesDropdownMenu}`).then((response) => {
                this.salesPurchaseData.labels = response.data.label
                this.salesPurchaseData.dataSet[0].data = response.data.sales
                this.salesPurchaseData.dataSet[1].data = response.data.purchase
            }).finally(() => {
                this.isSalesPurchaseLoading = false
            })
        },
        getTopSellingProducts() {
            this.isTopSellingLoading = true
            axiosGet(this.topSellingProductsUrl).then((response) => {
                this.topSellingData.labels = response.data.label
                this.topSellingData.dataSet[0].data = response.data.series
            }).finally(() => {
                this.isTopSellingLoading = false
            })
        },
        getHighlights() {
            this.isHighlightLoading = true;
            axiosGet(`${this.highlightUrl}&date=${this.highlight}`).then((response) => {
                this.highlightsData = response.data
            }).finally(() => {
                this.isHighlightLoading = false
            })
        },
        getRecentSales() {
            this.isRecentSalesLoading = true;
            axiosGet(this.recentSalesUrl).then((response) => {
                this.recentSalesTable.data = response.data.map(obj => ({
                    id: obj.invoice_number,
                    invoice_id: obj.id,
                    customer: obj.customer.full_name,
                    customer_id: obj.customer.id,
                    total: numberWithCurrencySymbol(obj.grand_total),
                    paid: numberWithCurrencySymbol(obj.paid_amount),
                    due: numberWithCurrencySymbol(obj.due_amount),
                    status: `<span class="badge badge-${obj.status.class} badge-pill">${this.$t(obj.status.name)}</span>`
                }))
            }).finally(() => {
                this.isRecentSalesLoading = false;
            })
        },
        getStockSummary() {
            this.isStockSummaryLoading = true;
            axiosGet(this.stockSummaryUrl).then((response) => {
                this.stockSummaryData.data = response.data.map(obj => ({
                    name: `<div class="text-primary">${obj.name}</div><small class="text-muted">#${obj.upc}</small>`,
                    amount: `<span class="text-${obj.available_qty <= obj.stock_reminder_quantity ? 'danger' : 'success'}">${obj.available_qty ?? 0}</span>`,
                }))
            }).finally(() => {
                this.isStockSummaryLoading = false;
            })
        },
        getTopCustomers() {
            this.isCustomerLoading = true;
            axiosGet(this.topCustomerUrl).then((response) => {
                this.topCustomer.data = response.data.map(obj => ({
                    name: `<div class="text-primary">${obj.customer ? obj.customer.full_name : ''}</div><small class="text-muted">#${obj.customer ? obj.customer.email : ''}</small>`,
                    amount: `<span class="text-success">${numberWithCurrencySymbol(obj.purchase_total)}</span>`,
                }))
            }).finally(() => {
                this.isCustomerLoading = false;
            })
        },
        viewAllInvoice() {
            window.location.replace(urlGenerator(`invoice`))
        },
        viewAllStock() {
            window.location.replace(urlGenerator(`inventory/stock`))
        },
        customerList() {
            window.location.replace(urlGenerator(`customer/lists`))
        },
        updateUrl() {
            this.highlightUrl = `${DASHBOARD_HIGHLIGHT}?branch_or_warehouse_id=${this.getBranchOrWarehouseId}`
            this.topSellingProductsUrl = `${DASHBOARD_TOP_SELLING_PRODUCTS}?branch_or_warehouse_id=${this.getBranchOrWarehouseId}`
            this.recentSalesUrl = `${DASHBOARD_RECENT_SALES}?branch_or_warehouse_id=${this.getBranchOrWarehouseId}`
            this.stockSummaryUrl = `${DASHBOARD_STOCK_SUMMARY}?branch_or_warehouse_id=${this.getBranchOrWarehouseId}`
            this.topCustomerUrl = `${DASHBOARD_TOP_CUSTOMERS}?branch_or_warehouse_id=${this.getBranchOrWarehouseId}`
            this.salePurchaseUrl = `${DASHBOARD_PURCHASE_SALE_STATISTICS}?branch_or_warehouse_id=${this.getBranchOrWarehouseId}`
        }
    },
    computed: {
        ...mapGetters(['getBranchOrWarehouseId']),
        userIsACashier() {
            const [userRole] = window.user.roles
            return userRole.id === 5;
        },
    },
    mounted() {
        this.updateUrl();
        this.getHighlights();
        this.getTopSellingProducts();
        this.getRecentSales();
        this.getStockSummary();
        this.getTopCustomers();
        this.getSalePurchaseStatistics();
        this.userRole = window.user.roles[0] ?? null
    },
    watch: {
        highlight(new_value) {
            this.highlight = new_value
            this.getHighlights();
        },
        salesDropdownMenu(new_value) {
            this.salesDropdownMenu = new_value
            this.getSalePurchaseStatistics();
        },
        getBranchOrWarehouseId(new_id) {
            this.updateUrl();
            this.getHighlights();
            this.getTopSellingProducts();
            this.getRecentSales();
            this.getStockSummary();
            this.getTopCustomers();
            this.getSalePurchaseStatistics();
        },
    }
}
</script>

<style scoped lang="scss">
.grid-card {
    display: flex;
    gap: 15px;

    .icon {
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 3px;
        width: 72px;
        height: 72px;
        background-color: #4466F2;
    }
}
</style>
