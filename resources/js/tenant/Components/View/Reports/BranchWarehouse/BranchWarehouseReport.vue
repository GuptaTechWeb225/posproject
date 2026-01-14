<template>
    <div class="content-wrapper">

        <app-page-top-section :title="$t('warehouse_report')" icon="briefcase">
            <app-default-button
                @click="exportBranchWarehouse"
                :title="$fieldTitle('warehouse_report_export', '', true)"/>
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
import BranchWarehouseReportMixins from "./BranchWarehouseReportMixins";
import {BRANCH_WAREHOUSE_EXPORT, SALES_REPORT_EXPORT} from "../../../../Config/ApiUrl-CP";
import {urlGenerator} from "../../../../../common/Helper/AxiosHelper";

export default {
    name: "BranchWarehouseReport",
    mixins: [HelperMixin, BranchWarehouseReportMixins],
    data() {
        return {
            table_id: 'branch-warehouse-report-table',
        }
    },
    methods: {
        exportBranchWarehouse() {
            window.open(urlGenerator(`${BRANCH_WAREHOUSE_EXPORT}/?branch_or_warehouse_id=${this.getBranchOrWarehouseId}`, '_blank'));

        }
    }
}
</script>