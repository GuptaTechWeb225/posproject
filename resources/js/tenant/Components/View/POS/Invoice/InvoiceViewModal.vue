<template>
    <app-modal
        modal-id="invoice-view-modal"
        modal-size="extra-large"
        modal-alignment="top"
        @close-modal="closeModal"
    >
        <!-- Modal header -->
        <template slot="header">
            <h5 class="modal-heading">
                {{ $t('invoice_view') }}
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
                    <div class="col-md-6">{{ formatDateTimeToLocal(invoice.ordered_at) }}</div>
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
                    <div class="col-md-6">{{ $t('status') }} :</div>
                    <div class="col-md-6">{{ $t(invoice.status?.name) }}</div>
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
                    v-for="(item, index) in invoice.order_products"
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
                    <p class="col-md-2"> {{ numberWithCurrencySymbol(item.price) }} </p>
                    <p class="col-md-2"> {{ item.discount_type === 'percentage' ? (item.discount_value+'%') : numberWithCurrencySymbol(item.discount_amount)}} </p>
                    <p class="col-md-2"> {{ item.tax_amount }}% </p>
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
                        {{ $t('sub_total') }}
                    </div>
                    <div class="col-md-6 text-right">
                        {{ numberWithCurrencySymbol(invoice.sub_total) }}
                    </div>
                    <div class="col-md-6">
                        {{ $t('discount_on_total') }} <span v-if="invoice.discount_type === 'percentage'">[ -{{invoice.discount}} %]</span>
                    </div>
                    <div class="col-md-6 text-right">
                        {{ numberWithCurrencySymbol(invoice.discount_value) }}
                    </div>
                    <div class="col-md-6">
                        {{ $t('tax') }} <span v-if="invoice?.tax?.percentage">[ +{{invoice.tax.percentage}} %]</span>
                    </div>
                    <div class="col-md-6 text-right">
                        {{ numberWithCurrencySymbol(invoice.total_tax) }}
                    </div>
                    <div class="col-md-6 border-top py-2">
                        {{ $t('invoice_total') }}
                    </div>
                    <div class="col-md-6 text-right border-top  py-2">
                        {{ numberWithCurrencySymbol(invoice.grand_total) }}
                    </div>
                    <div class="col-md-6 text-right border-top">
                        {{ $t('paid') }}
                    </div>
                    <div class="col-md-6 text-right border-top">
                        {{ numberWithCurrencySymbol(invoice.paid_amount) }}
                    </div>
                    <div class="col-md-6">
                        {{ $t('change') }}
                    </div>
                    <div class="col-md-6 text-right">
                        {{ numberWithCurrencySymbol(invoice.change_return) }}
                    </div>
                    <div class="col-md-6">
                        {{ $t('due') }}
                    </div>
                    <div class="col-md-6 text-right">
                        {{ numberWithCurrencySymbol(invoice.due_amount) }}
                    </div>
                </div>
            </div>


        </template>

        <!-- Modal Footer -->
        <template slot="footer" class="justify-content-start">
            <button 
                :disabled="!$can('sales_invoice_generate')" 
                class="btn btn-primary mr-3" 
                :label="$t('print')" 
                @click="handlePrintBtnClick"
            >
                {{ $t('print') }}
            </button>
            <app-cancel-button btn-class="btn-secondary" :label="$t('close')" @click="closeModal"/>
        </template>

        <PrintAria v-show="false" v-if="printAria" id="tharmal-80mm" css="css/invoice/80mm.css" @close="handlePrintAreaCloseEvent">
            <div v-html="purify(invoiceTemplateToPrint)"></div>
        </PrintAria>
    </app-modal>
</template>

<script>
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import {axiosGet, urlGenerator} from "../../../../../common/Helper/AxiosHelper";
import {DUE_PAYMENT_INFO, DUE_PAYMENT_RECEIVE, INVOICE_VIEW, GENERATE_INVOICE} from "../../../../Config/ApiUrl-CP";
import {purify} from "../../../../Helper/Purifier/HTMLPurifyHelper";
import PrintAria from "../../../../../common/Components/Helper/PrintAria";
import {formatDateTimeToLocal, numberWithCurrencySymbol} from "../../../../Helper/Helper";

export default {
    name: "InvoiceViewModal",
    mixins: [FormHelperMixins],
    components: {PrintAria},
    props: {
        orderId: {
            require: true
        },
    },
    data() {
        return {
            purify,
            invoiceTemplateToPrint: '',
            printAria: false,
            showNote: true,
            loading: false,
            DUE_PAYMENT_RECEIVE,
            DUE_PAYMENT_INFO,
            invoice: {},
            invoiceData: {
                logo_source: 'https://media.glassdoor.com/sqll/4308684/gain-solutions-squarelogo-1608634285110.png',
                company_address: 'Nirala',
                company_phone: '01717605715',
                company_email: 'shishir@gain.media',
                cash_counter: '1'
            },
        }
    },
    methods: {
        numberWithCurrencySymbol,
        formatDateTimeToLocal,
        handlePrintAreaCloseEvent() {
            this.printAria = false;
        },
        prepareInvoice() {
            for (const key in this.invoiceData) {
                this.assainValue(key, this.invoiceData[key])
            }
        },
        assainValue(key, value) {
            this.invoiceTemplateToPrint = this.invoiceTemplateToPrint.replace(`{${key}}`, value)
        },
        handlePrintBtnClick() {
            axiosGet(GENERATE_INVOICE + this.orderId)
                .then(res => {
                    this.invoiceTemplateToPrint = res.data.invoice_template 
                    this.printAria = true; // temporary
                    this.prepareInvoice()
                })
                .catch(e => 
                    this.$toastr.e(
                        e?.response?.data?.message ?? this.$t('something_went_wrong')
                    )
                )
        },
        getOrderInformation() {
            axiosGet(`${INVOICE_VIEW}/${this.orderId}`).then(response => {
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
