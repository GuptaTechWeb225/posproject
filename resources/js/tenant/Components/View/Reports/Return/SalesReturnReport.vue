<template>
    <section id="app-order-return" class="content-wrapper">

        <app-page-top-section :title="$t('sales_return_report')" icon="briefcase">
            <app-default-button
                @click="exportSalesSummary"
                :title="$fieldTitle('export_all_return_order', '', true)"/>
        </app-page-top-section>

        <app-table :id="table_id" :options="options" v-if="options.url" @action="">
            <!--    sale return summary..-->
            <template #data-cards>
                <sales-return-summary></sales-return-summary>
            </template>
        </app-table>

    </section>
</template>

<script>
import {SALES_RETURN_REPORT_EXPORT} from "../../../../Config/ApiUrl-CP";
import SalesReturnReportMixins from "./SalesReturnReportMixins";
import {mapGetters} from "vuex";
import {urlGenerator} from "../../../../../common/Helper/AxiosHelper";

export default {
    name: 'SalesReturnReport',
    mixins: [SalesReturnReportMixins],
    data() {
        return {
            table_id: 'sales-return-report-table',
            totalSalesAmount: 0,
            totalDiscount: 0,
            totalTax: 0,
            totalPaid: 0,
            totalDue: 0,
            exportLoading: false,
            branch_or_warehouse_id: this.getBranchOrWarehouseId,
        };
    },
    methods: {
        exportSalesSummary() {
            window.open(urlGenerator(`${SALES_RETURN_REPORT_EXPORT}`, '_blank'));
        },
    },
    computed: {
        ...mapGetters(['getBranchOrWarehouseId']),
    },
    watch: {
        getBranchOrWarehouseId(new_id) {
            this.branch_or_warehouse_id = new_id
        },
    },
};
</script>
