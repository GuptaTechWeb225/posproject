<template>
    <div
        :class="`${cartItems.length && getSelectedCustomer && !holdRequestPending ? '' : 'custom-disable'} cart-control row ${viewMode === 'primary' ? 'no-gutters ' + ($isRtl ? 'pl-2' : 'pr-2') : ''} col-12 mx-0 py-3 px-0 align-items-center ${viewMode === 'primary' && 'mt-1'} justify-content-between`">
        <div id="grand-total" class="row no-gutters col-12 align-items-center justify-content-between px-0">
            <h4 class="text-uppercase">{{ viewMode === 'primary' ? $t('total') : $t('total_payable') }}</h4>
            <h4> {{ numberWithCurrencySymbol(getGrandTotal) }}</h4>
        </div>
        <button
            :class="`btn btn-primary col-12 py-2 mb-3 ${viewMode === 'secondary' ? 'py-3 mb-4' : ''}`"
            @click="handlePaymentBtnClick">
            <app-icon name="dollar-sign" class="size-16"/>
            <span>{{ $t('pay_now') }}</span>
        </button>
        <template>
            <button v-if="holdRequestPending" class="btn btn-warning col-4 py-2 animate-pulse" :disabled="true">
                <span>{{ $t('loading') }}...</span>
            </button>
            <button v-else class="btn btn-warning col-4 py-2" @click="handleHoldModalBtnClick"
                    :key="holdRequestPending">
                <app-icon name="pause" class="size-16" :key="holdRequestPending"/>
                <span>{{ $t('hold') }}</span>
            </button>
        </template>
        <button class="btn btn-danger col-3 py-2" @click="handleCancelBtnClick">
            <app-icon name="x-square" class="size-16"/>
            <span>{{ $t('cancel') }}</span>
        </button>
        <button class="btn btn-info col-4 py-2" @click="handlePrintOrderClick">
            <app-icon name="file-text" class="size-16"/>
            <span>{{ $t('print_order') }}</span>
        </button>

        <app-confirmation-modal
            v-if="showCancelOrderConfirmationModal"
            icon="x-circle"
            modal-id="app-confirmation-modal"
            :message="$t('are_you_sure_you_want_to_clear_the_cart')"
            @confirmed="orderCancelConfirm"
            @cancelled="orderCancelCancel"
        />

        <payment-modal
            v-if="paymentModalOpen"
            v-model="paymentModalOpen"
            @close="handlePaymentModalClose"
        />

        <print-area
            v-show="false"
            v-if="showPrintArea"
            id="tharmal-80mm"
            css="css/invoice/80mm.css"
            @close="handlePrintAreaCloseEvent"
        >
            <div v-html="purify(htmlContent)"></div>
        </print-area>
    </div>
</template>

<script>
import {mapGetters, mapActions} from "vuex";
import PaymentModal from "./PaymentModal";
import {CASH_REGISTER_INFO, GET_CUSTOMER_DETAILS, GET_GENERAL_SETTINGS, HOLD_ORDER} from "../../../../Config/ApiUrl-CP";
import {axiosGet, axiosPost} from "../../../../../common/Helper/AxiosHelper";
import PrintAria from "../../../../../common/Components/Helper/PrintAria";
import {purify} from "../../../../Helper/Purifier/HTMLPurifyHelper";
import {generateInvoiceTemplate} from './invoice_template';
import {formatDateToLocal} from "../../../../Helper/Helper";
import {numberWithCurrencySymbol} from "../../../../Helper/Helper";

export default {
    name: 'cart-control',
    components: {'payment-modal': PaymentModal, 'print-area': PrintAria},
    data() {
        return {
            purify,
            holdRequestPending: false,
            paymentModalOpen: false,
            showPrintArea: false,
            numberWithCurrencySymbol,
            showCancelOrderConfirmationModal: false,
            invoiceData: {
                logo_source: 'https://media.glassdoor.com/sqll/4308684/gain-solutions-squarelogo-1608634285110.png',
                company_address: 'Nirala',
                company_phone: '01717605715',
                company_email: 'shishir@gain.media',
                cash_counter: '1'
            },
            htmlContent: '',
            formData: {
                branch_or_warehouse_id: '',
                cash_register_id: '',
                customer_id: '',
                sub_total: '',
                grand_total: '',
                total_tax: '',
                discount: '',
                note: '',
                created_by: '',
                ordered_at: '',
                order_products: [],
            },
            orderProductObjFormat: { // used to filter out the irrelevant properties from the payload
                stock_id: 9,
                variant_id: 13,
                order_product_id: 12,
                price: 800,
                itemPrice: 50,
                quantity: 3,
                selling_price: 3,
                tax_amount: 1,
                discount_type: "flat",
                discount_value: 1,
                discount_amount: 10,
                sub_total: 1,
                tenant_id: 1,
                note: "Hi",
                id: '',
                avg_purchase_price: ''
            }
        }
    },
    computed: {
        ...mapGetters([
            'viewMode',
            'cartItems',
            'getSelectedCustomer',
            'getBranchOrWarehouseId',
            'getUserId',
            'subtotal',
            'getTotalTax',
            'getDiscountOnSubtotal',
            'getGrandTotal',
            'getTaxId',
            'getCashRegisterId',
            'getProductSellingPrice',
            'getSubtotalDiscountType',
            'getDiscountValue',
        ])
    },
    methods: {
        ...mapActions(['setHoldOrders', 'cancelOrder']),
        async handlePrintOrderClick() {
            try {
                const {data: branchData} = await axiosGet('app/branch_or_warehouses/' + this.getBranchOrWarehouseId);
                const {data: {full_name: customer_name}} = await axiosGet(GET_CUSTOMER_DETAILS + this.getSelectedCustomer);
                const {data: {cashRegister: {name: cash_register}}} = await axiosGet(CASH_REGISTER_INFO + this.getCashRegisterId);
                const date = formatDateToLocal(new Date());
                this.htmlContent = generateInvoiceTemplate({
                    company_address: branchData.location,
                    company_email: branchData.email,
                    company_phone: branchData.phone,
                    sub_total: this.subtotal,
                    discount: this.getDiscountOnSubtotal,
                    tax: this.getTotalTax,
                    total: this.getGrandTotal,
                    cartItems: this.cartItems,
                    logo_source: this.invoiceData.logo_source,
                    customer_name,
                    date,
                    cash_register
                });
                this.showPrintArea = true;
            } catch (e) {
                this.$toastr.e(e);
            }
        },
        handleCancelBtnClick() {
            this.showCancelOrderConfirmationModal = true;
        },
        handlePaymentModalClose(clearCart = false) {
            this.paymentModalOpen = false;
            if(clearCart) {
                this.cancelOrder();
            }
        },
        orderCancelConfirm() {
            this.cancelOrder();
        },
        orderCancelCancel() {
            this.showCancelOrderConfirmationModal = false;
        },
        handlePrintAreaCloseEvent() {
            this.showPrintArea = false;
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
        handleHoldModalBtnClick() {
            this.submitFormData();
        },
        handlePaymentBtnClick() {
            this.paymentModalOpen = true
        },
        setFormData() {
            this.formData.branch_or_warehouse_id = this.getBranchOrWarehouseId;
            this.formData.created_by = this.getUserId;
            this.formData.order_products = JSON.parse(JSON.stringify(this.cartItems));
            this.formData.ordered_at = this.getFormattedCurrentTimeAndDate();
            this.formData.total_tax = +this.getTotalTax;
            this.formData.tax_id = this.getTaxId;
            this.formData.discount = +this.getDiscountValue;
            this.formData.discount_value = +this.getDiscountOnSubtotal;
            this.formData.discount_type = this.getSubtotalDiscountType === 'percentage' ? 'percentage' : 'flat';
            this.formData.sub_total = +this.subtotal;
            this.formData.grand_total = +this.getGrandTotal;
            this.formData.customer_id = +this.getSelectedCustomer;
            this.formData.cash_register_id = +this.getCashRegisterId;
            this.formData.is_being_held = true;

            for (const op of this.formData.order_products)
                for (const opVKey in op)
                    !Object.keys(this.orderProductObjFormat).includes(opVKey) ? delete op[opVKey] : '';

            this.formData.order_products = this.formData.order_products.map(op =>
                ({
                    ...op,
                    // selling_price: this.getProductSellingPrice(op.upc),
                    selling_price: op.price,
                    discount_value: op.discount_value ? op.discount_value : 0
                })
            );
        },
        submitFormData() {
            this.setFormData();
            this.holdRequestPending = true;
            axiosPost(HOLD_ORDER, this.formData)
                .then(res => this.setHoldOrders(true))
                .catch(e => {
                    this.$toastr.e(e)
                })
                .finally(() => this.holdRequestPending = false);

            this.printAria = true; // temporary
        },
    }
}
</script>

<style scoped lang="sass">
.round-btn
    display: inline-flex
    justify-content: center
    align-items: center
    margin-right: 0.65rem
    cursor: pointer
    width: 1.45rem
    height: 1.45rem
    border-radius: 50%
    background: #3a3c43

.custom-disable
    opacity: 0.5
    pointer-events: none

@keyframes pulse
    0%, 100%
        opacity: 1
    50%
        opacity: 0.5

#grand-total > *
    font-size: 18.7px
</style>
