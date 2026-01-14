<template>
    <div class="content-wrapper">

        <app-page-top-section :title="$t('cash_counter_report')" icon="briefcase">
            <app-default-button
                @click="exportCashCounter"
                :title="$fieldTitle('cash_counter_report_export', '', true)"/>
        </app-page-top-section>

        <app-table
            :id="table_id"
            v-if="options.url"
            :options="options"
            @action=""
        />

    </div>
</template>

<script>
import HelperMixin from "../../../../../common/Mixin/Global/HelperMixin";
import CashCounterMixins from "./CashCounterMixins";
import {CASH_COUNTER_EXPORT} from "../../../../Config/ApiUrl-CP";
import {urlGenerator} from "../../../../../common/Helper/AxiosHelper";

export default {
    name: "Index",
    mixins: [HelperMixin, CashCounterMixins],
    data() {
        return {
            table_id: 'cash-counter-report-table',
        }
    },
    methods: {
        exportCashCounter() {
            window.open(urlGenerator(`${CASH_COUNTER_EXPORT}/?branch_or_warehouse_id=${this.getBranchOrWarehouseId}`, '_blank'));
        }
    }
}
</script>