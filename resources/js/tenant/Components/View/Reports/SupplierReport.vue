<template>
  <div class="content-wrapper">

    <div class="">
      <app-page-top-section :title="$t('supplier_report')" icon="briefcase">
        <app-default-button
            @click="exportSupplierReport"
            :title="$fieldTitle('Supplier', 'Export All', true)"/>
      </app-page-top-section>
    </div>

    <app-table
        :id="table_id"
        v-if="options.url"
        :options="options"
        @action="triggerActions">
    </app-table>
  </div>

</template>

<script>

import HelperMixin from "../../../../common/Mixin/Global/HelperMixin";
import CoreLibrary from "../../../../core/helpers/CoreLibrary";
import { SUPPLIER_REPORT_EXPORT} from "../../../Config/ApiUrl-CPB";
import {axiosGet, urlGenerator} from "../../../../common/Helper/AxiosHelper";
import {mapGetters} from "vuex";
import SupplierReportMixin from "../../Mixins/SupplierReportMixin";

export default {
  name: "SupplierReport",
  mixins: [HelperMixin, SupplierReportMixin],
  data() {
    return {
      table_id: 'supplier-report-table',
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

    exportSupplierReport() {
      this.exportLoading = true
      const urlParams = new URLSearchParams(location.search);

      urlParams.append('branch_or_warehouse_id', this.branch_or_warehouse_id);
      window.location.replace(urlGenerator(`${SUPPLIER_REPORT_EXPORT}?${urlParams}`));
    },
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