<template>
  <div class="content-wrapper">
    <div class="">
      <app-page-top-section :title="$t('stock_report')" icon="briefcase">
        <app-default-button
            @click="exportProductStockReport"
            :title="$fieldTitle('Export', 'All Stock', true)"/>
      </app-page-top-section>
    </div>

    <app-table
        :id="table_id"
        v-if="options.url"
        :options="options"
        @action="triggerActions"
    />
  </div>
</template>

<script>
import HelperMixin from "../../../../common/Mixin/Global/HelperMixin";
import CoreLibrary from "../../../../core/helpers/CoreLibrary";
import {
  PRODUCT_STOCK_REPORT_EXPORT,
} from "../../../Config/ApiUrl-CPB";
import {urlGenerator} from "../../../../common/Helper/AxiosHelper";
import StockReportMixin from "../../Mixins/StockReportMixin";
import {mapGetters} from "vuex";

export default {
  name: "StockReport",
  mixins: [HelperMixin, StockReportMixin],
  data() {
    return {
      table_id: 'stock-report-table',
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
    triggerActions(row, action, active) {
    },

    openModal() {
      this.isModalActive = true;
      this.selectedUrl = ''
    },

    exportProductStockReport() {
      this.exportLoading = true
      const urlParams = new URLSearchParams(location.search);
      urlParams.append('branch_or_warehouse_id', this.branch_or_warehouse_id);

      window.location.replace(urlGenerator( `${PRODUCT_STOCK_REPORT_EXPORT}?${urlParams}` ));
    }
  },

  computed:{
    ...mapGetters(['getBranchOrWarehouseId']),
  },

  watch: {
    getBranchOrWarehouseId(new_id){
      this.branch_or_warehouse_id = new_id
    },
  },
}
</script>