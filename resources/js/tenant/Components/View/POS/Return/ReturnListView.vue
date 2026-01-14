<template>
    <section id="app-order-return" class="content-wrapper">
        <app-page-top-section :title="$t('returns')" icon="briefcase">
            <app-default-button
                v-if="this.$can('create_return_order')"
                @click="isModalActive = true"
                :title="$fieldTitle('add', 'return', true)"/>

        </app-page-top-section>

        <app-table :key="tableKey"  :id="table_id" :options="options" v-if="options.url" @action="triggerAction" />


        <app-return-add-edit-modal
            v-if="isModalActive"
            v-model="isModalActive"
            @successful-return-add="handleNewReturnAdd"
            @close="handleReturnModalClose"
        />

        <app-sale-invoice-modal
            v-if="isInvoiceModalActive"
            :order-id="rowData.id"
            :value="true"
            @modal-close="closeModal"
        />

    </section>
</template>

<script>
  import DatatableHelperMixin from "../../../../../common/Mixin/Global/DatatableHelperMixin";
  import ReturnList from "./returnList";

  export default {
      name: 'ReturnList',
      mixins: [DatatableHelperMixin, ReturnList],
      data() {
          return {
              table_id: 'return-list-table',
              isModalActive: false,
              isInvoiceModalActive: false,
              rowData:'',
              tableKey: 0,
          };
      },
      methods: {
          handleReturnModalClose() {
             this.isModalActive = false
          },
          handleNewReturnAdd() {
              this.tableKey++;
          },
          triggerAction(row, action, active) {
              if (action.type === 'view'){
                  this.rowData = row
                  this.openModal()
              }
          },
          closeModal() {
              this.isInvoiceModalActive = false;
              this.$emit('modal-close');
          },
          openModal() {
              this.isInvoiceModalActive = true;
          }
      }
  };
</script>
