<template>
    <div :class="`row col-12 ${!isBeingUsedFromPaymentModal && 'mt-5' } mx-0`">
        <div class="heading border-bottom mb-3 col-12 px-0">
            <h4 :class="`${isBeingUsedFromPaymentModal ? 'text-center' : ''}`">{{ $t('invoice_summary') }}</h4>
        </div>

        <div :class="`row no-gutters col-12 align-items-center justify-content-between ${!isBeingUsedFromPaymentModal && 'text-muted'} px-0`">
            <p>{{ $t('total_items') }}</p>
            <p>{{ totalItems }}</p>
        </div>

        <div :class="`row no-gutters col-12 align-items-center justify-content-between ${!isBeingUsedFromPaymentModal && 'text-muted'} px-0`">
            <p>{{ $t('sub_total') }}</p>
            <p>{{numberWithCurrencySymbol( subtotal )}}</p>
        </div>

        <div :class="`row no-gutters col-12 align-items-center justify-content-between ${!isBeingUsedFromPaymentModal && 'text-muted'} px-0`">
            <p>{{ $t('total_discount') }} {{ getSubtotalDiscountType === 'percentage' ? `- ${getDiscountValue}%` : ``}}</p>
            <p>- {{ numberWithCurrencySymbol(getDiscountOnSubtotal) }}</p>
        </div>

        <div :class="`row no-gutters col-12 align-items-center justify-content-between ${!isBeingUsedFromPaymentModal && 'text-muted'} px-0`">
            <p>{{ $t('tax') }} - {{ getTotalTax }}%</p>
            <p>+ {{ numberWithCurrencySymbol(getTaxAmount) }}</p>
        </div>

        <div v-show="viewMode === 'primary'" class="row no-gutters col-12 align-items-center justify-content-between text-muted px-0">
            <h5>{{ $t('total_payable') }}</h5>
            <h5>{{ numberWithCurrencySymbol(getGrandTotal) }}</h5>
        </div>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';
import {numberWithCurrencySymbol} from "../../../../Helper/Helper";

export default {
    name: 'invoice-summary',
    props: ['is-being-used-from-payment-modal'],
    data() {
        return {}
    },
    methods: {
        numberWithCurrencySymbol(value) {
            return numberWithCurrencySymbol(value);
        }
    },
    computed: {
        ...mapGetters(['subtotal', 'totalItems', 'getGrandTotal', 'getTotalTax', 'getDiscountOnSubtotal', 'getTaxAmount', 'viewMode', 'getSubtotalDiscountType', 'getDiscountValue']),
    }
}
</script>

<style scoped>

</style>
