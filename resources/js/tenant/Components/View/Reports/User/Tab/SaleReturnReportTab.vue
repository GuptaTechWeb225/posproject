<template>
    <div>
        <app-table :id="table_id" :options="options" @action=""></app-table>
    </div>
</template>

<script>
import DatatableHelperMixin from "../../../../../../common/Mixin/Global/DatatableHelperMixin";
import SaleReturnReportMixins from "./Mixins/SaleReturnReportMixins";

export default {
    name: "PurchaseReportTab",
    mixins: [DatatableHelperMixin, SaleReturnReportMixins],
    props: {
        props: {}
    },
    data() {
        return {
            table_id: 'user-sale-report-table',
        };
    },
    methods: {},
}
</script>

<style scoped>

</style>