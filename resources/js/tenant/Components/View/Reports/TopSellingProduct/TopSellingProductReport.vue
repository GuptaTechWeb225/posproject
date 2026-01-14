<template>
    <section id="app-order-return" class="content-wrapper">

        <app-page-top-section :title="$t('top_selling_product_report')" icon="briefcase">
        </app-page-top-section>

        <app-table :id="table_id" :options="options" v-if="options.url" @action=""></app-table>

    </section>
</template>

<script>
import DatatableHelperMixin from "../../../../../common/Mixin/Global/DatatableHelperMixin";
import TopSellingProductMixins from "./TopSellingProductMixins";
import {TOP_SELLING_PRODUCT_REPORT_EXPORT} from "../../../../Config/ApiUrl-CP";
import {mapGetters} from "vuex";

export default {
    name: 'TopSellingProductReport',
    mixins: [DatatableHelperMixin, TopSellingProductMixins],
    data() {
        return {
            table_id: 'top-selling-product-report-table',
            exportLoading: false,
            branch_or_warehouse_id: this.getBranchOrWarehouseId,
        };
    },
    methods: {
        exportTopSellingProducts() {
            window.open(`${TOP_SELLING_PRODUCT_REPORT_EXPORT}/?branch_or_warehouse_id=${this.getBranchOrWarehouseId}`, '_blank');
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
