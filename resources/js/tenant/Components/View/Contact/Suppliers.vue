<template>
  <div class="content-wrapper">
    <app-page-top-section :title="$t('suppliers')">

      <app-default-button
          v-if="this.$can('create_suppliers')"
          :title="$addLabel('supplier')"
          @click="openModal()"/>

    </app-page-top-section>

    <app-table
        id="supplier-table"
        :options="options"
        @action="triggerAction"
    />

    <app-supplier-modal
        v-if="isModalActive"
        v-model="isModalActive"
        :selected-url="selectedUrl"
        @close="isModalActive = false"
    />

    <app-confirmation-modal
        v-if="confirmationModalActive"
        icon="trash-2"
        modal-id="app-confirmation-modal"
        @cancelled="cancelled"
        @confirmed="confirmed('supplier-table')"
    />


    <!-- for activating/de-activating supplier -->
    <app-confirmation-modal
        v-if="Boolean(statusChangeUrl)"
        icon="alert-circle"
        modal-id="app-confirmation-modal"
        :message="$t('are_you_sure_you_want_to_change_the_status')"
        @cancelled="cancelProductStatusChange"
        @confirmed="changeProductStatus"
    />
  </div>
</template>

<script>
import HelperMixin from "../../../../common/Mixin/Global/HelperMixin";
import SupplierMixin from "../../Mixins/SupplierMixin";
import {SUPPLIER_EXPORT, SUPPLIERS} from "../../../Config/ApiUrl-CPB";
import {axiosPatch, urlGenerator} from "../../../../common/Helper/AxiosHelper";

export default {
  name: "Suppliers",
  mixins: [HelperMixin, SupplierMixin],
  data() {
    return {
      isModalActive: false,
      statusChangeUrl: '',
      selectedUrl: '',
      urlGenerator,
    }
  },
  methods: {
    triggerAction(row, action, active) {
      if (action.name === 'edit') {
        this.selectedUrl = `${SUPPLIERS}/${row.id}`;
        this.isModalActive = true;
      } else if (action.name === 'deactivate') {
        this.statusChangeUrl = `app/supplier/${row.id}/change-status?status=${false}`;
      } else if (action.name === 'activate') {
        this.statusChangeUrl = `app/supplier/${row.id}/change-status?status=${true}`;
      } else {
        this.getAction(row, action, active)
      }
    },
    changeProductStatus() {
      axiosPatch(this.statusChangeUrl)
          .then(response => {
            this.toastAndReload(response.data.message, 'supplier-table');
            this.statusChangeUrl = '';
          }).catch((error) => {
        if (error.response)
          this.toastException(error.response.data)
      });
    },

    cancelProductStatusChange() {
      this.statusChangeUrl = '';
    },
    openModal() {
      this.isModalActive = true;
      this.selectedUrl = ''
    },
    exportSuppliers() {
      window.location = SUPPLIER_EXPORT;
    }
  }
}
</script>