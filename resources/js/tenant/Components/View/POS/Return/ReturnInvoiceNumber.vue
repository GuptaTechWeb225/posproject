<template>
    <div>
        <div href="javascript:void(0)" @click.prevent="openModal()">
            <a class="invoice_number" href="javascript:void(0)">{{ rowData.invoice_number }}</a><br>
            <small>{{ $t('date') }} : {{ formatDateToLocal(rowData.returned_at.split('-').reverse().join('-')) }}</small>
        </div>

        <app-sale-invoice-modal
            v-if="isInvoiceModalActive"
            :order-id="rowData.id"
            :value="true"
            @modal-close="closeModal"
        />

    </div>
</template>

<script>
import HelperMixin from "../../../../../common/Mixin/Global/HelperMixin";
import {formatDateToLocal} from "../../../../Helper/Helper";

export default {
    mixins: [HelperMixin],
    name: 'ReturnInvoiceNumber',
    props: ['rowData', 'tableId', 'value'],
    data() {
        return {
            isInvoiceModalActive: false,
            formatDateToLocal
        };
    },
    methods: {
        closeModal() {
            this.isInvoiceModalActive = false;
            this.$emit('modal-close');
        },
        openModal() {
            this.isInvoiceModalActive = true;
        }

    },
};
</script>

<style lang="scss" scoped>
.invoice_number{
    text-decoration: none;
}
.invoice_number:hover{
    text-decoration: none;
    color: #4466F2;
}
</style>
