<template>
    <section id="app-inventory-stock" class="content-wrapper">
        <!-- top page section -->
        <app-page-top-section :title="$t('invoices')" />

        <app-table :id="table_id" v-if="options.url" :options="options" @action="triggerAction"/>

        <app-invoice-modal
            v-if="isInvoiceModalActive"
            :order-id="orderId"
            :value="true"
            @modal-close="closeModal"
        />

        <app-due-payment-modal
            v-if="isModalActive"
            :order-id="orderId"
            :value="true"
            @modal-close="closeModal"
        />

    </section>
</template>

<script>

import DatatableHelperMixin from "../../../../../common/Mixin/Global/DatatableHelperMixin";
import SelectableStatusMixin from "../../../../Helper/SelectableOptions/SelectableStatusMixin";
import invoiceList from "./invoiceList";

export default {
    name: 'InvoiceView',
    mixins: [DatatableHelperMixin, SelectableStatusMixin, invoiceList],
    data() {
        return {
            table_id: 'invoice-list-table',
            orderId: '',
            isModalActive: false,
            isInvoiceModalActive: false
        };
    },
    methods: {
        triggerAction(row, action, active) {
            this.orderId = row.id;
            if (action.type === 'view') {
                this.isInvoiceModalActive = true;
            } else if (action.type === 'due_receive') {
                this.isModalActive = true
            }
        },
        closeModal() {
            $('#invoice-view-modal').modal('hide')
            this.isInvoiceModalActive = false;
            this.isModalActive = false
            this.$emit('modal-close');
        },
        openModal() {
            this.isModalActive = true;
            this.selectedUrl = ''
        }
    }
};
</script>
