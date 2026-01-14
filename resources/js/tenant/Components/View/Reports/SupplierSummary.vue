<template>
  <div key>
    <!--summary lot report -->
    <div class="no-gutters justify-content-between row my-3">
      <div class="w-100 mb-primary col-md-2 text-white bg-primary shadow rounded d-flex align-items-center p-4">
        <div class="ml-3">
          <div class="flex-shrink-0">
           ss - {{ numberWithCurrencySymbol(totalPurchaseAmount) }}
          </div>
          <h5 class="mb-0">{{ $t('total_purchase') }}</h5>
        </div>
      </div>

      <div
          class="w-100 mb-primary mx-2 col-md-2 shadow rounded d-flex align-items-center p-4">
        <div class="ml-3">
          <div class="flex-shrink-0">
            {{ numberWithCurrencySymbol(totalDiscount) }}
          </div>
          <h5 class="mb-0">{{ $t('total_discount') }}</h5>
        </div>
      </div>
      <div class="w-100 mb-primary col-md-2  shadow rounded d-flex align-items-center p-4">
        <div class="ml-3">
          <div class="flex-shrink-0">
            {{ numberWithCurrencySymbol(totalOtherCharges) }}
          </div>
          <h5 class="mb-0">{{ $t('other_cost') }}</h5>
        </div>
      </div>
      <div class="w-100 mb-primary col-md-2 shadow rounded d-flex align-items-center p-4">
        <div class="ml-3">
          <div class="flex-shrink-0">
            {{ numberWithCurrencySymbol(totalPaid)  }}
          </div>
          <h5 class="mb-0">{{ $t('total_paid') }}</h5>
        </div>
      </div>
      <div class="w-100 mb-primary col-md-2 shadow rounded d-flex align-items-center p-4">
        <div class="ml-3">
          <div class="flex-shrink-0">
            {{ total_unit_quantity  }}
          </div>
          <h5 class="mb-0">{{ $t('total_items') }}</h5>
        </div>
      </div>
    </div>
    <!--    end summary lot report-->
  </div>

</template>

<script>

import HelperMixin from "../../../../common/Mixin/Global/HelperMixin";
import CoreLibrary from "../../../../core/helpers/CoreLibrary";
import {axiosGet, urlGenerator} from "../../../../common/Helper/AxiosHelper";
import { SUPPLIER_SUMMARY} from "../../../Config/ApiUrl-CPB";
import {mapGetters} from "vuex";
import {numberWithCurrencySymbol} from "../../../Helper/Helper";

export default {
  name: "SupplierSummary",
  mixins: [HelperMixin],
  data() {
    return {
      location: window.location,
      options: {
        url: SUPPLIER_SUMMARY,
      },
      table_id: 'purchase-report-table',
      isModalActive: false,
      selectedUrl: '',
      totalCustomer: '',
      urlGenerator,
      numberWithCurrencySymbol,

      //purchase summary...
      totalPurchaseAmount: 0,
      totalDiscount: 0,
      totalOtherCharges: 0,
      totalPaid: 0,
      total_unit_quantity: 0,
      branch_or_warehouse_id : '',
    }
  },
  extends: CoreLibrary,

  methods: {
    triggerActions(row, action, active) {},

    supplierSummary() {
      axiosGet(this.options.url).then((response) => {
        this.totalPurchaseAmount = response.data.totalPurchaseAmount
        this.totalDiscount = response.data.totalDiscount
        this.totalOtherCharges = response.data.totalOtherCharges
        this.total_unit_quantity = response.data.total_unit_quantity
        this.totalPaid = response.data.totalPaid
      })
    },

    towDigitAfterDecimal(value) {
      return value.toFixed(2);
    },

    updateUrl(){
      this.options.url = `${SUPPLIER_SUMMARY}?branch_or_warehouse_id=${this.getBranchOrWarehouseId}`
      this.supplierSummary();

      //set current branch_or_warehouse_id
      this.branch_or_warehouse_id = this.getBranchOrWarehouseId;
      this.$hub.$emit(`reload-${this.table_id}`)
    },
  },
  computed:{
    ...mapGetters(['getBranchOrWarehouseId']),
  },

  mounted(){
    this.updateUrl();
    this.supplierSummary();

    //change filter and update summary...
    this.$hub.$on('filter-change', (newFilterValues) => {
      let filterData = Object.fromEntries(Object.entries(newFilterValues).filter(([_, v]) => v !== ''));
      const urlParams = new URLSearchParams({...filterData, date:JSON.stringify(filterData.date)});
      urlParams.append('branch_or_warehouse_id', this.branch_or_warehouse_id)
      this.options.url = `${SUPPLIER_SUMMARY}?${urlParams}`
      this.supplierSummary();
    });
  },

  watch: {
    getBranchOrWarehouseId(new_id){
      this.updateUrl();
    }
  },

  created() {}
}
</script>