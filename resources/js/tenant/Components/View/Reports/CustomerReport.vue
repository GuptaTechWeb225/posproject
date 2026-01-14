<template>
  <div class="content-wrapper">
    <div class="">
      <app-page-top-section :title="$t('customer_report')" icon="briefcase">
        <app-default-button
            @click="exportCustomerReport"
            :title="$fieldTitle($t('customer'), $t('export_all'), true)"/>
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
import {CUSTOMER_REPORT_EXPORT, PURCHASE_REPORT_EXPORT} from "../../../Config/ApiUrl-CPB";
import {axiosGet, urlGenerator} from "../../../../common/Helper/AxiosHelper";
import {mapGetters} from "vuex";
import CustomerReportMixin from "../../Mixins/CustomerReportMixin";

export default {
  name: "CustomerReport",
  mixins: [HelperMixin, CustomerReportMixin],
  data() {
    return {
      table_id: 'customer-report-table',
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

    exportCustomerReport() {
      this.exportLoading = true
      const urlParams = new URLSearchParams(location.search);

      urlParams.append('branch_or_warehouse_id', this.branch_or_warehouse_id);
      window.location.replace(urlGenerator( `${CUSTOMER_REPORT_EXPORT}?${urlParams}` ));
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