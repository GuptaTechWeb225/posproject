<template>
    <div class="content-wrapper">

        <div class="">
            <app-page-top-section :title="$t('lot_report')" icon="briefcase">
                <app-default-button
                    @click="exportLotReport"
                    :title="$fieldTitle('Lot', 'Export All', true)"/>
            </app-page-top-section>
        </div>

        <app-table
            :id="table_id"
            v-if="options.url"
            :options="options"
            @action="triggerActions">

            <!--    lot summary..-->
            <template #data-cards>
                <purchase-summary></purchase-summary>
            </template>

        </app-table>
    </div>

</template>

<script>

import HelperMixin from "../../../../common/Mixin/Global/HelperMixin";
import CoreLibrary from "../../../../core/helpers/CoreLibrary";
import {LOT_REPORT, PURCHASE_REPORT_EXPORT} from "../../../Config/ApiUrl-CPB";
import {axiosGet, urlGenerator} from "../../../../common/Helper/AxiosHelper";
import LotReportMixin from "../../Mixins/LotReportMixin";
import {mapGetters} from "vuex";

export default {
    name: "LotReport",
    mixins: [HelperMixin, LotReportMixin],
    data() {
        return {
            table_id: 'purchase-report-table',
            isModalActive: false,
            selectedUrl: '',
            totalCustomer: '',
            urlGenerator,
            exportLoading: false,
            branch_or_warehouse_id: this.getBranchOrWarehouseId,
        }
    },
    extends: CoreLibrary,
    methods: {
        triggerActions(row, action, active) {},

        openModal() {
            this.isModalActive = true;
            this.selectedUrl = ''
        },

        exportLotReport() {
            this.exportLoading = true
            const urlParams = new URLSearchParams(location.search);

            urlParams.append('branch_or_warehouse_id', this.branch_or_warehouse_id);
            window.location.replace(urlGenerator( `${PURCHASE_REPORT_EXPORT}?${urlParams}` ));
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

}
</script>