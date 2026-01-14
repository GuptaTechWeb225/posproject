<template>
    <div key>
        <!--summary report -->
        <div class="d-flex justify-content-start container-fluid row mt-3">
            <div
                class="w-100 mb-primary col-md-2 text-white mr-2 bg-primary shadow rounded d-flex align-items-center p-4">
                <div class="ml-3">
                    <div class="flex-shrink-0">
                        {{ numberWithCurrencySymbol(totalSalesAmount) }}
                    </div>
                    <h5 class="mb-0">{{ $t('total_sales_amount') }}</h5>
                </div>
            </div>

            <div
                class="w-100 mb-primary mx-2 col-md-2 shadow rounded d-flex align-items-center p-4">
                <div class="ml-3">
                    <div class="flex-shrink-0">
                        {{ numberWithCurrencySymbol(totalDiscount) }}
                    </div>
                    <h5 class="mb-0">{{ $t('total_discount') }}</h5>
                </div>
            </div>
        </div>
        <!--    end summary report-->
    </div>

</template>

<script>

import HelperMixin from "../../../../../common/Mixin/Global/HelperMixin";
import {axiosGet, urlGenerator} from "../../../../../common/Helper/AxiosHelper";
import {numberWithCurrencySymbol} from "../../../../Helper/Helper";
import {TOP_SELLING_PRODUCT_SUMMARY, TOP_SELLING_PRODUCT_REPORT} from "../../../../Config/ApiUrl-CP";
import {mapGetters} from "vuex";

export default {
    name: "TopSellingProductSummary",
    mixins: [HelperMixin],
    data() {
        return {
            location: window.location,
            options: {
                url: TOP_SELLING_PRODUCT_REPORT,
            },
            table_id: 'top-selling-product-report-table',
            isModalActive: false,
            selectedUrl: '',
            totalCustomer: '',
            urlGenerator,
            numberWithCurrencySymbol,
            totalSalesAmount: 0,
            totalDiscount: 0,
            totalTax: 0,
            totalPaid: 0,
            totalDue: 0,
            branch_or_warehouse_id: '',
        }
    },
    methods: {
        triggerActions(row, action, active) {
        },
        topSellingProductSummary() {
            axiosGet(this.options.url).then((response) => {
                this.totalSalesAmount = response.data.totalSalesAmount
                this.totalDiscount = response.data.totalDiscount
            })
        },

        towDigitAfterDecimal(value) {
            return value.toFixed(2);
        },

        updateUrl() {
            this.options.url = `${TOP_SELLING_PRODUCT_SUMMARY}?branch_or_warehouse_id=${this.getBranchOrWarehouseId}`
            this.topSellingProductSummary();

            //set current branch_or_warehouse_id
            this.branch_or_warehouse_id = this.getBranchOrWarehouseId;
            this.$hub.$emit(`reload-${this.table_id}`)
        },
    },
    computed: {
        ...mapGetters(['getBranchOrWarehouseId']),
    },

    mounted() {
        this.updateUrl();
        this.topSellingProductSummary();


        //change filter and update summary...
        this.$hub.$on('filter-change', (newFilterValues) => {
            let filterData = Object.fromEntries(Object.entries(newFilterValues).filter(([_, v]) => v !== ''));
            const urlParams = new URLSearchParams({
                ...filterData,
                date: JSON.stringify(filterData.date),
                range_filter: filterData.range_filter ? JSON.stringify(filterData.range_filter) : ''
            });
            urlParams.append('branch_or_warehouse_id', this.branch_or_warehouse_id)
            this.options.url = `${TOP_SELLING_PRODUCT_SUMMARY}?${urlParams}`
            this.topSellingProductSummary();
        });
    },

    watch: {
        getBranchOrWarehouseId(new_id) {
            this.updateUrl();
        }
    }
}
</script>