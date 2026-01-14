<template>
    <app-modal
        modal-id="invoice-view-modal"
        modal-size="large"
        modal-alignment="top"
        @close-modal="closeModal"
    >
        <!-- Modal header -->
        <template slot="header">
            <h5 class="modal-heading">
                {{ $t('return_invoice_view') }}
            </h5>

            <button class="close outline-none" @click.prevent="closeModal">
                <app-icon name="x"/>
            </button>
        </template>

        <!-- Modal body -->
        <template slot="body">

            <div class="row">
                <div class="col-md-5 row">
                    <div class="col-md-6">{{ $t('date') }} :</div>
                    <div class="col-md-6">{{ invoice.returned_at }}</div>
                    <div class="col-md-6">{{ $t('warehouse') }} :</div>
                    <div class="col-md-6">
                        <template v-if="invoice.branch_or_warehouse">
                            {{ invoice.branch_or_warehouse?.name }}
                        </template>
                    </div>
                </div>
                <div class="col-md-2"></div>
                <div class="col-md-5 row">
                    <div class="col-md-6">{{ $t('invoice_number') }} :</div>
                    <div class="col-md-6">{{ invoice.invoice_number }}</div>
                    <div class="col-md-6">{{ $t('return_type') }} :</div>
                    <div class="col-md-6">{{ invoice.type }}</div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-md-6 row">
                    <div class="col-md-6"><h5>{{ $t('invoice_to') }} :</h5>
                        {{ invoice.customer?.full_name }}<br>
                        <template v-if="invoice.customer">
                            <template v-if="invoice.customer.phone_numbers.length > 0">
                                <div class="col-md-6">
                                <span v-for="phone in invoice.customer.phone_numbers">
                                    {{ phone.value }} <br>
                                </span>
                                </div>
                                <br>
                            </template>
                        </template>
                    </div>
                </div>
                <div class="col-md-6 row">
                    <div class="col-md-6"></div>
                    <div class="col-md-6"><h5>{{ $t('invoice_from') }} : </h5><br>
                        {{ invoice.created_by?.full_name }}<br>
                        {{ $t('sales_man') }} {{ invoice.branch_or_warehouse?.name }} {{ $t('branch') }}<br>
                        {{ invoice.branch_or_warehouse?.location }}
                    </div>
                </div>
            </div>

            <div class="mb-4 mt-4 row no-gutters">
                <div class="labels col-12 row border-bottom no-gutters">
                    <p class="text-muted col-md-1">#</p>
                    <p class="text-muted col-md-2">{{ $t('items') }}</p>
                    <p class="text-muted col-md-1">{{ $t('quantity') }}</p>
                    <p class="text-muted col-md-2">{{ $t('unit_price') }}</p>
                    <p class="text-muted col-md-2">{{ $t('unit_discount') }}</p>
                    <p class="text-muted col-md-2">{{ $t('tax') }}</p>
                    <p class="text-muted col-md-2 text-right">{{ $t('total') }}</p>
                </div>

                <div
                    class="values row no-gutters align-items-baseline justify-content-between col-md-12 border-bottom py-3"
                    v-for="(item, index) in invoice.return_order_products"
                    :key="item.invoice_number">
                    <p class="col-md-1"> {{ index + 1 }} </p>
                    <div class="d-flex align-items-center col-md-2">
                        <div>
                            <template v-if="item.variant">
                                <a> {{ item.variant.name }} </a>
                                <small class="d-block text-muted">{{ item.variant.upc }}</small>
                            </template>
                        </div>
                    </div>
                    <p class="col-md-1">
                        {{ item.quantity }}
                        <template v-if="item.variant">
                            <template v-if="item.variant.product">
                                <template v-if="item.variant.product.unit">
                                    ({{ item.variant.product.unit.name }})
                                </template>
                            </template>
                        </template>
                    </p>
                    <p class="col-md-2"> {{ item.price }} </p>
                    <p class="col-md-2"> {{ item.discount_type === 'percentage' ? (item.discount_value+'%') : numberWithCurrencySymbol(item.discount_amount)}} </p>
                    <p class="col-md-2"> {{ numberWithCurrencySymbol(item.tax_amount) }} </p>
                    <p class="col-md-2 text-right"> {{ numberWithCurrencySymbol(item.sub_total) }} </p>
                </div>
                <div class="col-md-6 row mt-4">
                    <div class="col-md-6"><h5>{{ $t('payment_info') }} :</h5>
                        <template v-if="invoice.payment_method">
                            <br> <span>{{ invoice.payment_method.name }}</span>
                        </template>
                    </div>

                </div>
                <div class="col-md-6 text-right row  mt-4">
                    <div class="col-md-6">
                        {{ $t('discount_on_total') }}
                    </div>
                    <div class="col-md-6 text-right">
                        {{ numberWithCurrencySymbol(invoice.discount ?? 0) }}
                    </div>
                    <div class="col-md-6">
                        {{ $t('tax') }}
                    </div>
                    <div class="col-md-6 text-right">
                        {{ numberWithCurrencySymbol(invoice.total_tax ?? 0) }}
                    </div>
                    <div class="col-md-6 text-right">
                        {{ $t('paid') }}
                    </div>
                    <div class="col-md-6 text-right">
                        {{ numberWithCurrencySymbol(invoice.paid_amount ?? 0) }}
                    </div>
                    <div class="col-md-6">
                        {{ $t('due') }}
                    </div>
                    <div class="col-md-6 text-right">
                        {{ numberWithCurrencySymbol(invoice.due_amount ?? 0) }}
                    </div>
                    <div class="col-md-6">
                        {{ $t('invoice_total') }}
                    </div>
                    <div class="col-md-6 text-right">
                        {{ numberWithCurrencySymbol(invoice.sub_total ?? 0) }}
                    </div>
                </div>
            </div>


        </template>

        <!-- Modal Footer -->
        <template slot="footer" class="justify-content-start">
            <app-cancel-button btn-class="btn-secondary" :label="$t('close')" @click="closeModal"/>
        </template>

    </app-modal>
</template>

<script>
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import {axiosGet} from "../../../../../common/Helper/AxiosHelper";
import {DUE_PAYMENT_INFO, DUE_PAYMENT_RECEIVE, RETURN_ORDER_VIEW} from "../../../../Config/ApiUrl-CP";
import {numberWithCurrencySymbol} from "../../../../Helper/Helper";

export default {
    name: "ReturnInvoiceViewModal",
    mixins: [FormHelperMixins],
    props: {
        orderId: {
            require: true
        },
    },
    data() {
        return {
            numberWithCurrencySymbol,
            showNote: true,
            loading: false,
            DUE_PAYMENT_RECEIVE,
            DUE_PAYMENT_INFO,
            invoice: {}
        }
    },
    methods: {
        getOrderInformation() {
            axiosGet(`${RETURN_ORDER_VIEW}/${this.orderId}`).then(response => {
                this.invoice = response.data
            })
        },
        closeModal() {
            $('#invoice-view-modal').modal('hide')
            this.$emit('modal-close');
        },
    },
    created() {
        this.getOrderInformation()
    }
}
</script>
