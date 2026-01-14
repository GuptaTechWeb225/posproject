<template>
    <section id="app-order-return" class="content-wrapper">

        <app-page-top-section :title="$t('profit_loss_report')" icon="briefcase">
            <app-default-button
                @click="exportProfitLoss"
                :title="$fieldTitle('export_all_profit_loss', '', true)"/>
        </app-page-top-section>

        <app-table :id="table_id" :options="options" @action="" />

    </section>
</template>

<script>
import DatatableHelperMixin from "../../../../../common/Mixin/Global/DatatableHelperMixin";
import {PROFIT_LOSS_REPORT_EXPORT} from "../../../../Config/ApiUrl-CP";
import ProfitLossMixins from "./ProfitLossMixins";
import {mapGetters} from "vuex";
import {CUSTOMER_REPORT_EXPORT} from "../../../../Config/ApiUrl-CPB";
import {urlGenerator} from "../../../../Helper/Helper";

export default {
    name: 'ProfitLossReport',
    mixins: [DatatableHelperMixin, ProfitLossMixins],
    data() {
        return {
            table_id: 'profit-loss-report-table',
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
        exportProfitLoss() {
            const urlParams = new URLSearchParams(location.search);
            urlParams.append('branch_or_warehouse_id', this.branch_or_warehouse_id);
            window.location.replace(urlGenerator( `${PROFIT_LOSS_REPORT_EXPORT}?${urlParams}` ));
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
