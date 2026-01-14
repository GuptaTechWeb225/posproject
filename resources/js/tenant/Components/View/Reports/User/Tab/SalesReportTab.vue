<template>
    <div>
        <app-table :id="table_id" :options="options" @action=""></app-table>
    </div>
</template>

<script>
import DatatableHelperMixin from "../../../../../../common/Mixin/Global/DatatableHelperMixin";
import UserSaleReportMixins from "./Mixins/UserSaleReportMixins";
import {mapGetters} from "vuex";

export default {
    name: "SalesReportTab",
    mixins: [DatatableHelperMixin, UserSaleReportMixins],
    props: {
        props: {

        }
    },
    data() {
        return {
            table_id: 'user-sale-report-table',
        };
    },
    methods: {
    },
}
</script>

<style scoped>

</style>