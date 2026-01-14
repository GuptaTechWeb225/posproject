<template>
    <div key>
        <!--summary lot report -->
        <div class="no-gutters justify-content-between row my-3">
            <div class="w-100 mb-primary col-md-2 text-white bg-primary shadow rounded d-flex align-items-center p-4">
                <div class="ml-3">
                    <h5 class="mb-0"> {{ numberWithCurrencySymbol(totalPurchaseAmount) }}</h5>
                    <div class="flex-shrink-0">
                        {{ $t('total_purchase_amount') }}
                    </div>
                </div>
            </div>

            <div
                class="w-100 mb-primary mx-2 col-md-2 shadow rounded d-flex align-items-center p-4">
                <div class="ml-3">
                    <h5 class="mb-0">{{ numberWithCurrencySymbol(totalDiscount) }}</h5>
                    <div class="flex-shrink-0">
                        {{ $t('total_discount') }}
                    </div>
                </div>
            </div>
            <div class="w-100 mb-primary col-md-2  shadow rounded d-flex align-items-center p-4">
                <div class="ml-3">
                    <h5 class="mb-0">{{ numberWithCurrencySymbol(totalOtherCharges) }}</h5>
                    <div class="flex-shrink-0">
                        {{ $t('other_cost') }}
                    </div>
                </div>
            </div>
            <div class="w-100 mb-primary col-md-2 shadow rounded d-flex align-items-center p-4">
                <div class="ml-3">
                    <h5 class="mb-0">{{ numberWithCurrencySymbol(totalPaid) }}</h5>
                    <div class="flex-shrink-0">
                        {{ $t('total_paid') }}
                    </div>
                </div>
            </div>
            <div class="w-100 mb-primary col-md-2 shadow rounded d-flex align-items-center p-4">
                <div class="ml-3">
                    <h5 class="mb-0">{{ total_unit_quantity }}</h5>
                    <div class="flex-shrink-0">
                        {{ $t('total_items') }}
                    </div>
                </div>
            </div>
        </div>
        <!--    end summary lot report-->
    </div>

</template>

<script>

import HelperMixin from "../../../../common/Mixin/Global/HelperMixin";
import CoreLibrary from "../../../../core/helpers/CoreLibrary";
import {axiosGet, urlGenerator} from "../../../../common/Helper/AxiosHelper";
import {PURCHASE_SUMMARY} from "../../../Config/ApiUrl-CPB";
import {mapGetters} from "vuex";
import {numberWithCurrencySymbol} from "../../../Helper/Helper";

export default {
    name: "LotSummary",
    mixins: [HelperMixin],
    data() {
        return {
            location: window.location,
            options: {
                url: PURCHASE_SUMMARY,
            },
            table_id: 'purchase-report-table',
            isModalActive: false,
            selectedUrl: '',
            totalCustomer: '',
            urlGenerator,
            numberWithCurrencySymbol,

            //purchase summary...
            totalPurchaseAmount: 0,
            totalDiscount: 0,
            totalOtherCharges: 0,
            totalPaid: 0,
            total_unit_quantity: 0,
            branch_or_warehouse_id: '',
        }
    },
    extends: CoreLibrary,

    methods: {
        triggerActions(row, action, active) {},

        purchaseSummary() {
            axiosGet(this.options.url).then((response) => {
                this.totalPurchaseAmount = response.data.totalPurchaseAmount
                this.totalDiscount = response.data.totalDiscount
                this.totalOtherCharges = response.data.totalOtherCharges
                this.total_unit_quantity = response.data.total_unit_quantity
                this.totalPaid = response.data.totalPaid
            })
        },

        towDigitAfterDecimal(value) {
            return value.toFixed(2);
        },

        updateUrl() {
            this.options.url = `${PURCHASE_SUMMARY}?branch_or_warehouse_id=${this.getBranchOrWarehouseId}`
            this.purchaseSummary();

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
        // this.purchaseSummary();

        //change filter and update summary...
        this.$hub.$on('filter-change', (newFilterValues) => {
            let filterData = Object.fromEntries(Object.entries(newFilterValues).filter(([_, v]) => v !== ''));
            const urlParams = new URLSearchParams({...filterData, date: JSON.stringify(filterData.date)});
            urlParams.append('branch_or_warehouse_id', this.branch_or_warehouse_id)
            this.options.url = `${PURCHASE_SUMMARY}?${urlParams}`
            this.purchaseSummary();
        });
    },

    watch: {
        getBranchOrWarehouseId(new_id) {
            this.updateUrl();
        }
    },

    created() {}
}
</script>
