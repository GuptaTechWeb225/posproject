<template>
    <app-modal modal-id="payment-modal"
               :title="$t('make_payment')"
               modal-alignment="top"
               v-model="showModal"
               :modal-size="innerWidth > 768 ? 'extra-large' :'fullscreen'"
               :modal-backdrop="false"
               style="margin-top: 5rem;"
               :preloader="preloader"
               @close-modal="handlePaymentModalCloseEvent">

        <app-overlay-loader v-if="orderRequestPending" />
        <div v-else :class="`container-fluid row no-gutters modal-body default-base-color pb-4 ${orderRequestPending ? 'custom-disable' : ''}`">
            <div class="invoice-summary col-md-6 col-12">
                <invoice-summary :is-being-used-from-payment-modal="true"/>
            </div>

            <div class="payment-actions col-md-6 col-12">
                <div class="row no-gutters">
                    <div class="heading border-bottom mb-3 col-12">
                        <h4 class="text-center">{{ $t('payments') }}</h4>
                    </div>

                    <div class="payment-info col-12 mt-3 row no-gutters">
                        <!-- Payment Inputs -->
                        <template v-if="activePaymentMethods.length">
                            <div v-for="paymentMethod in activePaymentMethods"
                                class="payment-input-wrapper row no-gutters col-md-12 align-items-center mb-3">
                                <label class="payment-method-type col-md-4">{{ $t(paymentMethod.name) }}</label>
                                <div class="payment-input col-md-8 d-flex align-items-center justify-content-end"
                                    :key="activePaymentMethods.length"
                                    style="gap: 1.2rem">
                                    <app-input type="number" v-model="paymentMethod.paidAmount"/>

                                    <div
                                        :class="`${activePaymentMethods.length > 1 ? 'cursor-pointer' : 'custom-hide'}`"
                                        :key="activePaymentMethods.length"
                                        @click="handlePaymentMethodRemoveClick(paymentMethod.id)">
                                        <app-icon class="payment-method-close text-danger" name="x-circle"/>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template v-else>
                            <div>
                                <app-pre-loader/>
                            </div>
                        </template>


                        <div
                            class="change-return row no-gutters col-12 align-items-center justify-content-between text-muted py-2">
                            <p class="m-0">{{ $t('due') }}</p>
                            <p class="text-danger m-0">{{ dueAmount ? numberWithCurrencySymbol(dueAmount) : 0 }}</p>
                        </div>
                        <div
                            class="change-return row no-gutters col-12 align-items-center justify-content-between text-muted py-2">
                            <p class="m-0">{{ $t('change_return') }}</p>
                            <p class="text-success m-0">{{
                                changeAmount ? numberWithCurrencySymbol(changeAmount) : 0
                                }}</p>
                        </div>

                        <div class="row no-gutters col-12 py-2">
                            <app-input
                                type="textarea"
                                class="d-block col-12"
                                :text-area-rows="3"
                                :placeholder="$placeholder('note')"
                                v-model="formData.note"
                            />
                        </div>

                        <div class="row no-gutters col-12 py-2">
                            <app-input
                                type="textarea"
                                class="d-block col-12"
                                :text-area-rows="3"
                                :placeholder="$placeholder('payment_note')"
                                v-model="formData.payment_note"
                            />
                        </div>

                        <div class="payment-method-options col-md-12 d-flex mb-3 flex-wrap" style="gap: 1.2rem;">
                            <button
                                :class="`btn btn-${pmOption.id === nextPaymentMethodIdToPayWith ? 'primary' : 'secondary'}`"
                                v-for="pmOption in availablePaymentMethodOptions" :key="pmOption.key"
                                @click="handleNewPaymentMethodClick(pmOption)"
                            >

                                {{ $t(pmOption.name) }}
                            </button>
                        </div>

                        <div class="col-12 py-2 d-flex justify-content-between">
                            <button class="btn btn-secondary" style="width: 48%" @click="handlePaymentModalCloseEvent">
                                {{ $t('cancel') }}
                            </button>
                            <button v-if="activePaymentMethods.length" style="width: 48%"
                                :class="`btn btn-primary ${!availablePaymentMethodOptions.length && dueAmount ? 'custom-disable' : ''}`"
                                :disabled="paymentDone"
                                @click.prevent="handlePaymentBtnClick"
                            >
                                {{ dueAmount ? $t('add_payment') : $t('done_payment') }}
                            </button>
                        </div>
                    </div>

                    <PrintAria v-if="printAria" v-show="false" id="tharmal-80mm" css="css/invoice/80mm.css"
                        @close="handlePrintAreaCloseEvent">
                        <div v-html="purify(contentHtml)"></div>
                    </PrintAria>
                </div>
            </div>
        </div>


    </app-modal>
</template>

<script>

import ModalMixin from "../../../../../common/Mixin/Global/ModalMixin";
import HelperMixin from "../../../../../common/Mixin/Global/HelperMixin";
import InvoiceSummary from "./InvoiceSummary";
import {mapGetters, mapActions} from "vuex";
import {PAYMENT_METHODS, STORE_ORDER} from "../../../../Config/ApiUrl-CP";
import {axiosGet, axiosPost} from "../../../../../common/Helper/AxiosHelper";
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import PrintAria from '../../../../../common/Components/Helper/PrintAria.vue'
import {purify} from "../../../../Helper/Purifier/HTMLPurifyHelper";
import {numberWithCurrencySymbol} from "../../../../Helper/Helper";

export default {
    name: "payment-modal",
    mixins: [ModalMixin, HelperMixin, FormHelperMixins],
    components: {'invoice-summary': InvoiceSummary, PrintAria},
    data() {
        return {
            purify,
            innerWidth: 0,
            nextPaymentMethodIdToPayWith: "",
            orderRequestPending: false,
            activePaymentMethods: [],
            paymentMethods: [],
            paymentDone: false,
            orderProductObjFormat: { // used to filter out the irrelevant properties from the payload
                stock_id: 9,
                variant_id: 13,
                order_product_id: 12,
                price: 800,
                itemPrice: 50,
                quantity: 3,
                tax_amount: 1,
                discount_type: "flat",
                discount_value: 1,
                discount_amount: 10,
                sub_total: 1,
                tenant_id: 1,
                note: "Hi",
                payment_note: '',
                id: '',
                avg_purchase_price: ''
            },
            formData: {
                branch_or_warehouse_id: '',
                customer_id: '',
                due_amount: '',
                paid_amount: '',
                sub_total: '',
                grand_total: '',
                total_tax: '',
                payment_type: '',
                discount: '',
                change_return: '',
                note: '',
                payment_note: '',
                created_by: '',
                ordered_at: '',
                order_products: [],
                transactions: []
            },
            printAria: false,
            paymentNote: '',
            invoiceData: {
                logo_source: 'https://media.glassdoor.com/sqll/4308684/gain-solutions-squarelogo-1608634285110.png',
                company_address: 'Nirala',
                company_phone: '01717605715',
                company_email: 'shishir@gain.media',
                cash_counter: '1'
            },
            contentHtml: ''
        }
    },
    watch: {
        getBranchOrWarehouseId: {
            immediate: true,
            handler: function (newBranchOrWarehouseId) {
                this.formData.branch_or_warehouse_id = newBranchOrWarehouseId;
            }
        },
        'formData.paid_amount'() { // setting dynamic values to formData from here
            this.formData.change_return = +this.changeAmount.toFixed(2);
            this.formData.payment_type = 'full_payment';
            const creditAmount = this.activePaymentMethods.find(pmo => pmo.alias.toLowerCase() === 'credit');
            if (creditAmount) {
                +creditAmount.paidAmount ? this.formData.payment_type = 'partial_payment' : '';
                this.formData.due_amount = +creditAmount.paidAmount ?? 0;
            }
            this.formData.transactions = this.activePaymentMethods.map(apm => ({
                payment_method_id: apm.id,
                amount: +apm.paidAmount
            }));
            this.formData.paid_amount = +this.formData.paid_amount;
        },
        totalAmountPaid(newAmount) {
            this.formData.paid_amount = newAmount;
        },
        availablePaymentMethodOptions(newPmOptions) {
            if (!newPmOptions.length) return;
            this.nextPaymentMethodIdToPayWith = newPmOptions[0].id;
        }
    },
    mounted() {
        this.getPaymentMethods();
        this.setFormData();
        this.innerWidth = window.innerWidth;
    },
    computed: {
        totalAmountPaid() { // the reactivity of this computed property is being used in its watcher to set totalPaidAmount
            const totalAmountPaid = this.activePaymentMethods.map(apm => apm.paidAmount);
            return totalAmountPaid.reduce((a, v) => +a + +v, 0);
        },
        availablePaymentMethodOptions() {
            const activePaymentMethodIds = this.activePaymentMethods.map(aPmo => aPmo.id);
            const activePaymentMethodsFilteredOut = this.paymentMethods.filter(pmo => !activePaymentMethodIds.includes(pmo.id));
            // filtering out the credit method if the customer is a walk-in customer
            if (+this.getSelectedCustomer === 1) return activePaymentMethodsFilteredOut.filter(pmo => pmo.alias !== 'credit');
            return activePaymentMethodsFilteredOut;
        },
        changeAmount() {
            if (this.formData.paid_amount < this.getGrandTotal) return 0;
            return this.formData.paid_amount - this.getGrandTotal;
        },
        ...mapGetters([
            'getSelectedCustomer',
            'getBranchOrWarehouseId',
            'getCashRegisterId',
            'getUserId',
            'cartItems',
            'subtotal',
            'getTotalTax',
            'getTaxAmount',
            'getDiscountOnSubtotal',
            'getGrandTotal',
            'getTaxId',
            'getProductSellingPrice',
            'getSubtotalDiscountType',
            'getDiscountValue'
        ]),
        dueAmount() {
            if (+this.formData.paid_amount < +this.getGrandTotal) return +this.getGrandTotal - +this.formData.paid_amount;
        },
    },
    methods: {
        ...mapActions(['setProducts']),
        numberWithCurrencySymbol(value) {
            return numberWithCurrencySymbol(value);
        },
        handlePrintAreaCloseEvent($e) {
            this.printAria = false;
            this.handlePaymentModalCloseEvent($e, true);
        },
        setDefaultCashAmount() {
            const cashMethod = this.activePaymentMethods.find(apm => apm.alias === 'cash');
            cashMethod.paidAmount = this.getGrandTotal;
        },
        handlePaymentMethodRemoveClick(paymentMethodId) {
            this.activePaymentMethods = this.activePaymentMethods.filter(pm => pm.id !== paymentMethodId);
        },
        handleNewPaymentMethodClick(paymentMethodObj) {
            this.nextPaymentMethodIdToPayWith = paymentMethodObj.id;
        },
        addNewPaymentMethod(paymentMethodObj) {
            this.activePaymentMethods.push({...paymentMethodObj, paidAmount: +this.dueAmount});
        },
        assainValue(key, value) {
            this.contentHtml = this.contentHtml.replace(`{${key}}`, value)
        },
        prepareInvoice() {
            for (const key in this.invoiceData) {
                this.assainValue(key, this.invoiceData[key])
            }
        },
        formatDate(dateObj) {
            if (typeof dateObj === 'string') return dateObj;
            const date = {
                year: dateObj.getFullYear(),
                month: dateObj.getMonth() + 1,
                day: dateObj.getDate(),
                hour: dateObj.getHours(),
                minute: dateObj.getMinutes(),
                second: dateObj.getSeconds()
            }

            return `${date.year}-${date.month <= 9 ? '0' : ''}${date.month}-${date.day <= 9 ? '0' : ''}${date.day} ${date.hour}:${date.minute}:${date.second}`;
        },
        getFormattedCurrentTimeAndDate() {
            const currentDate = new Date();
            return this.formatDate(currentDate);
        },
        setFormData() {
            this.formData.branch_or_warehouse_id = this.getBranchOrWarehouseId;
            this.formData.cash_register_id = this.getCashRegisterId;
            this.formData.created_by = this.getUserId;
            this.formData.order_products = JSON.parse(JSON.stringify(this.cartItems));
            this.formData.ordered_at = this.getFormattedCurrentTimeAndDate();
            this.formData.total_tax = +this.getTotalTax;
            this.formData.tax_id = this.getTaxId;
            this.formData.discount = this.getDiscountValue;
            this.formData.discount_value = +this.getDiscountOnSubtotal;
            this.formData.discount_type = this.getSubtotalDiscountType === 'percentage' ? 'percentage' : 'flat';
            this.formData.sub_total = +this.subtotal;
            this.formData.grand_total = +this.getGrandTotal;
            this.formData.customer_id = +this.getSelectedCustomer;
            this.formData.transactions = this.activePaymentMethods.map(apm => ({
                payment_method_id: apm.id,
                amount: +apm.paidAmount
            }));
            // dynamic values being set from formData.paid_amount watcher

            for (let op of this.formData.order_products)
                for (const opVKey in op)
                    !Object.keys(this.orderProductObjFormat).includes(opVKey) ? delete op[opVKey] : '';

            this.formData.order_products = this.formData.order_products.map(op =>
                ({
                    ...op,
                    selling_price: this.getProductSellingPrice(op.id),
                    discount_value: op.discount_value ? op.discount_value : 0
                })
            );
        },
        async getPaymentMethods() {
            try {
                const {data} = await axiosGet(PAYMENT_METHODS);
                this.paymentMethods = data;
                this.setDefaultPaymentMethod();
                this.setDefaultCashAmount();
            } catch (e) {
                this.$toastr.e(e);
            }
        },
        setDefaultPaymentMethod() {
            const cash = this.paymentMethods.find(pm => pm.alias === 'cash');
            this.activePaymentMethods.push({...cash, paidAmount: 0});
        },
        handlePaymentBtnClick() {
            if (!this.dueAmount) return this.submitPayment();
            const selectedPaymentMethod = this.availablePaymentMethodOptions.find(pmo => pmo.id === this.nextPaymentMethodIdToPayWith);
            this.addNewPaymentMethod(selectedPaymentMethod);
        },
        submitPayment() {
            this.orderRequestPending = true;
            this.formData = {...this.formData, total_tax: this.getTaxAmount, due_amount: this.formData.due_amount ? this.formData.due_amount : 0}
            axiosPost(STORE_ORDER, {
                ...this.formData,
                paid_amount: this.formData.due_amount ? (this.formData.paid_amount - this.formData.due_amount) : this.formData.paid_amount
            })
                .then((res) => {
                    this.contentHtml = res.data.invoice_template;
                    this.paymentDone = true;
                    this.setProducts(); // getting the product and its stock data after a sale
                    this.printAria = true; // temporary
                    this.prepareInvoice()
                })
                .catch(e => {
                    this.$toastr.e(e.response.data.message)
                })
                .finally(() => {
                    this.orderRequestPending = false;
                })
        },
        handlePaymentModalCloseEvent($e, clearCart = false) {
            $("#payment-modal").modal('hide');
            this.$emit('close', clearCart);
        },
    },
}
</script>

<style lang="sass" scoped>
.custom-disable
    opacity: 0.5
    pointer-events: none

.custom-hide
    opacity: 0
    pointer-events: none

.payment-actions
    margin-top: 1.5rem

@media only screen and (min-width: 768px)
    .payment-actions
        margin-top: 0

</style>
