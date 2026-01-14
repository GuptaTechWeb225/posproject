<template>
    <section id="app-order-return" class="content-wrapper">

        <app-page-top-section :title="$t('sales_report')" icon="briefcase">
            <app-default-button
                @click="exportSalesSummary"
                :title="$fieldTitle('export_all_order', '', true)"/>
        </app-page-top-section>

        <app-table :id="table_id" :options="options" v-if="options.url" @action="">
            <template #data-cards>
                <sales-summary></sales-summary>
            </template>
        </app-table>

    </section>
</template>

<script>
import DatatableHelperMixin from "../../../../../common/Mixin/Global/DatatableHelperMixin";
import SalesReportMixins from "./SalesReportMixins";
import {SALES_REPORT_EXPORT, SALES_REPORT, SALES_SUMMARY} from "../../../../Config/ApiUrl-CP";
import {numberWithCurrencySymbol} from "../../../../Helper/Helper";
import {mapGetters} from "vuex";
import {urlGenerator} from "../../../../../common/Helper/AxiosHelper";

export default {
    name: 'SalesReport',
    mixins: [DatatableHelperMixin, SalesReportMixins],
    data() {
        return {
            table_id: 'sales-report-table',
            numberWithCurrencySymbol,
            exportLoading: false,
            branch_or_warehouse_id: this.getBranchOrWarehouseId,
        };
    },
    methods: {
        exportSalesSummary() {
            window.open(urlGenerator(`${SALES_REPORT_EXPORT}/?branch_or_warehouse_id=${this.getBranchOrWarehouseId}`,'_blank'));
        },
    },
    computed: {
        ...mapGetters(['getBranchOrWarehouseId']),
    },
    watch: {
        getBranchOrWarehouseId(new_id) {
            this.branch_or_warehouse_id = new_id
        },
    }
};
</script>
