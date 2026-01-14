<template>
    <app-modal modal-id="order-hold-modal"
               v-model="showModal"
               size="large"
               :preloader="preloader"
               @close-modal="handleOrderModalCloseEvent">
        <template #header>
            <h5 class="text-center">{{ $t('hold_order_list') }}</h5>
        </template>

        <template #body>
            <app-input
                type="text"
                :placeholder="$t('search_by_invoice_number')"
                v-model="invoiceNumber"
                class="mb-5"
            />

            <app-overlay-loader v-if="ordersOnHold === null"/>

            <template v-else>
                <div class="mb-3 row align-items-center no-gutters mt-5" v-if="ordersOnHold.length">
                    <div class="labels col-12 row border-bottom no-gutters py-2 mb-0 align-items-baseline">
                        <p class="col-4 pb-0">{{ $t('invoice_number') }}</p>
                        <p class="col-4 pb-0">{{ $t('ordered_at') }}</p>
                        <p class="col-4 pb-0 text-right">{{ $t('action') }}</p>
                    </div>

                    <div class="held-order col-12 row border-bottom no-gutters py-3 mb-0" v-for="order in matchingOrdersOnHold">
                        <p class="invoice-id col-4 text-primary cursor-pointer" @click="handleOrderClick(order.id)">{{ order.invoice_number }}</p>
                        <p class="col-4" @click="handleOrderClick(order.id)">{{ formatDateTimeToLocal(order.ordered_at) }}</p>
                        <div class="col-4 d-flex justify-content-end">
                            <span @click="handleOrderDiscardClick(order.id)">
                                <app-icon class="text-danger cursor-pointer del-btn" name="x-circle"/>
                            </span>
                        </div>
                    </div>
                </div>

                <h4 v-else class="text-center text-muted mx-5"> {{ $t('no_orders_to_show') }} </h4>
            </template>
        </template>

        <template #footer>
            <template v-if="!showBulkDeleteConfirmationButtons">
                <button class="btn btn-secondary" @click="handleOrderModalCloseEvent">
                    {{ $t('close') }}
                </button>
                <button class="btn btn-danger ml-2" @click="showBulkDeleteConfirmationButtons = true"
                        v-if="Array.isArray(ordersOnHold) && ordersOnHold.length">
                    {{ $t('discard_all_orders') }}
                </button>
            </template>

            <template v-else>
                <div class="d-flex align-items-baseline justify-content-between">
                    <p> {{ $t('are_you_sure_you_want_to_discard_all_orders') }} </p>
                    <div style="margin-left: 5.875rem;">
                        <button class="btn btn-success" @click="closeBulkDeleteConfirmation"> {{ $t("keep") }}</button>
                        <button class="btn btn-danger ml-2" @click="handleBulkDeleteConfirmation"> {{
                                $t("discard")
                            }}
                        </button>
                    </div>
                </div>
            </template>
        </template>
    </app-modal>
</template>
<script>

import ModalMixin from "../../../../common/Mixin/Global/ModalMixin";
import FormHelperMixins from "../../../../common/Mixin/Global/FormHelperMixins";
import {axiosPost} from "../../../../common/Helper/AxiosHelper";
import {HOLD_ORDER_DELETE} from "../../../Config/ApiUrl-CP";
import {mapGetters, mapActions, mapMutations} from "vuex";
import {formatDateTimeToLocal} from "../../../Helper/Helper";


export default {
    name: "OrderHoldModal",
    mixins: [FormHelperMixins, ModalMixin],
    watch: {
        getOrdersOnHold: {
            immediate: true,
            handler: function (newOrdersOnHold) {
                this.ordersOnHold = newOrdersOnHold
            }
        }
    },
    mounted() {
        this.loadOrderData();
    },
    data() {
        return {
            formatDateTimeToLocal,
            invoiceNumber: '',
            ordersOnHold: null,
            showBulkDeleteConfirmationButtons: false
        }
    },
    computed: {
        ...mapGetters(['getUserId', 'getOrdersOnHold']),
        matchingOrdersOnHold() {
            if (!this.ordersOnHold instanceof Array) return [];
            return this.ordersOnHold.filter(order => order.invoice_number.includes(this.invoiceNumber));
        }
    },
    methods: {
        ...mapActions(['setHoldOrders', 'setPendingOrder']),
        ...mapMutations(['CLEAR_ORDERS_ON_HOLD']),
        loadOrderData() {
            this.setHoldOrders();
        },
        reloadData() {
            this.CLEAR_ORDERS_ON_HOLD();
            this.closeBulkDeleteConfirmation();
            this.loadOrderData();
        },
        handleOrderDiscardClick(orderId) {
            const orderIdArray = [orderId];
            const soldBy = this.getUserId;
            axiosPost(HOLD_ORDER_DELETE, {orderIds: orderIdArray, soldBy})
                .then(() => this.reloadData())
                .catch(e => {
                    this.$toastr.e(e)
                });
        },
        handleOrderClick(orderID) {
            this.setPendingOrder(orderID);
            this.handleOrderDiscardClick(orderID);
            this.handleOrderModalCloseEvent();
        },
        handleOrderModalCloseEvent() {
            $('#order-hold-modal').modal('hide');
            this.$emit('close');
        },
        handleBulkDeleteConfirmation() {
            // call the bulk delete api here
            const orderIdArray = this.ordersOnHold.map(order => order.id);
            const soldBy = this.getUserId;
            axiosPost(HOLD_ORDER_DELETE, {orderIds: orderIdArray, soldBy})
                .then(() => this.reloadData())
                .catch(e => {
                    this.$toastr.e(e)
                });
        },
        closeBulkDeleteConfirmation() {
            this.showBulkDeleteConfirmationButtons = false;
        }
    }
}
</script>

<style lang="sass">
.held-order
    & > p
        margin-bottom: 0

    .invoice-id:hover
       color: #4485F2 !important

    &:hover
        border-color: red

    .del-btn:hover
        transform: scale(1.1)
        transition: 150ms
</style>