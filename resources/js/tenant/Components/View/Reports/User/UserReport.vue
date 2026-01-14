<template>
    <section id="app-order-return" class="content-wrapper">

        <app-page-top-section :title="$t('user_report')" icon="briefcase">
            <app-default-button
                @click="exportUsers"
                :title="$fieldTitle('export_all_user', '', true)"/>
        </app-page-top-section>

        <app-table :id="table_id" :options="options" @action=""></app-table>

    </section>
</template>

<script>
import DatatableHelperMixin from "../../../../../common/Mixin/Global/DatatableHelperMixin";
import SalesReportMixins from "./UserReportMixins";
import {mapGetters} from "vuex";
import {USER_REPORT_EXPORT} from "../../../../Config/ApiUrl-CP";
import {urlGenerator} from "../../../../../common/Helper/AxiosHelper";

export default {
    name: 'SalesReport',
    mixins: [DatatableHelperMixin, SalesReportMixins],
    data() {
        return {
            table_id: 'user-report-table',
            exportLoading: false,
            branch_or_warehouse_id: this.getBranchOrWarehouseId,
        };
    },
    methods: {
        exportUsers() {
            window.open(urlGenerator(`${USER_REPORT_EXPORT}/?branch_or_warehouse_id=${this.getBranchOrWarehouseId}`, '_blank'));
        }
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
