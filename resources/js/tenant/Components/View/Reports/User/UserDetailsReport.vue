<template>
    <div>
        <div class="content-wrapper" style="min-height: 200px">
            <button class="btn btn-primary mb-3" type="button" @click.prevent="goBack">
              {{ $t('back') }}
            </button>
            <app-tab type="horizontal" :tabs="horizontalTabs"/>
        </div>
    </div>
</template>

<script>
import DatatableHelperMixin from "../../../../../common/Mixin/Global/DatatableHelperMixin";
import SalesReportMixins from "./UserReportMixins";
import {urlGenerator} from "../../../../../common/Helper/AxiosHelper";
import {mapGetters} from "vuex";

export default {
    name: 'UserDetailsReport',
    mixins: [DatatableHelperMixin, SalesReportMixins],
    props: {
        user: {}
    },
    data() {
        return {
            exportLoading: false,
            branch_or_warehouse_id: this.getBranchOrWarehouseId,
            horizontalTabs: [
                {
                    "name": this.$t('sales'),
                    "icon": "shopping-cart",
                    "component": "app-user-sales-report-tab",
                    "props": this.user,
                    "permission": ""
                },
                {
                    "name": this.$t('sale_return'),
                    "icon": "corner-down-left",
                    "component": "app-user-sales-return-report-tab",
                    "props": this.user,
                    "permission": ""
                },
                {
                    "name": this.$t('purchase'),
                    "icon": "package",
                    "component": "app-user-purchase-report-tab",
                    "props": this.user,
                    "permission": ""
                },
            ]
        };
    },
    methods: {
      goBack(){
        window.location.replace(urlGenerator(`user/report`))
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
