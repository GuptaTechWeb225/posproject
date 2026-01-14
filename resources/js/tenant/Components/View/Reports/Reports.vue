<template>
    <div class="content-wrapper">
        <app-table id="report-sales-datatable" :options="options"/>
    </div>
</template>

<script>
import {axiosGet} from "../../../../common/Helper/AxiosHelper";
import {REPORT_SALES} from "../../../../common/Config/apiUrl";

export default {
    data() {
        return {
            options: {
                url: REPORT_SALES,
                showHeader: false,
                showCount: true,
                showClearFilter: false,
                noGrandTotal: true,
                tableShadow: false,
                columns: [
                    {
                        title: this.$t('invoice'),
                        type: 'text',
                        key: 'invoice_number'
                    },
                    {
                        title: this.$t('customer'),
                        type: 'object',
                        key: 'customer',
                        modifier: ( _ , row) => row.customer.full_name
                    },
                    {
                        title: this.$t('grand_total'),
                        type: 'text',
                        key: 'grand_total',
                    },
                ],
                filters: [],
                paginationType: "pagination",
                responsive: true,
                rowLimit: 10,
                showAction: true,
                orderBy: 'desc',
                actionType: "dropdown",
                actions: [],
            },
        }
    },
    methods: {},
    mounted() {}
}
</script>

<style scoped>

</style>