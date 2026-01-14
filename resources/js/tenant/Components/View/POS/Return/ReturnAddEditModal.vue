<template>
    <app-modal
        modal-id="discount-create-edit-modal"
        v-model="showModal"
        modal-size="large"
        :loading="loading"
        :preloader="preloader" :title="generateModalTitle('return')"
        @close-modal="handleReturnModalCloseClick"
        @submit="submitDataTest">

        <div class="modal-header">
            <h4> {{ $t('add_return') }} </h4>
        </div>

        <form
            ref="form"
            :data-url='selectedUrl ? selectedUrl : STORE_RETURN_ORDER'
            class="row no-gutters modal-body"
            enctype="multipart/form-data">

            <div :class="`col-md-12 row no-gutters ${!formData.invoice_id && 'custom-margin'}`">
                <label class="col-md-12">{{ $t('invoice_id') }}</label>
                <app-input
                    class="col-md-12 pr-4"
                    type="search-and-select"
                    :placeholder="$placeholder('invoice_id')"
                    :options="invoiceIdOptions"
                    v-model="formData.invoice_id"
                    :error-message="$errorMessage(errors, 'invoice_id')"
                />
            </div>

            <template v-if="Boolean(formData.invoice_id)">
                <template v-if="totalQuantity === 0">
                    <h4 class="mt-5 text-center col-12 text-danger">{{ $t('no_items_left_to_return') }}</h4>
                </template>
                <template v-if="totalQuantity">
                    <div class="col-md-12 row mt-4">
                        <div class="date-input col-md-6 row no-gutters">
                            <label class="col-md-12">{{ $t('return_date') }}*</label>
                            <app-input
                                class="col-md-12"
                                type="date"
                                :placeholder="$placeholder('date')"
                                v-model="formData.returned_at"
                                :error-message="$errorMessage(errors, 'returned_at')"
                            />
                        </div>

                        <div class="date-input col-md-6 row no-gutters" v-if="formData.customer_id">
                            <label class="col-md-12">{{ $t('customer_name') }}</label>
                            <app-input
                                class="col-md-12"
                                type="search-and-select"
                                :disabled="true"
                                :options="customerOptions"
                                :placeholder="$placeholder('customer_name')"
                                v-model="formData.customer_id"
                                :error-message="$errorMessage(errors, 'customer_id')"
                            />
                        </div>

                        <div class="biller-input col-md-6 row no-gutters mt-3">
                            <label class="col-md-12">{{ $t('biller') }}*</label>
                            <app-input
                                class="col-md-12"
                                type="search-and-select"
                                :options="billerOptions"
                                :placeholder="$placeholder('biller')"
                                v-model="formData.created_by"
                                :error-message="$errorMessage(errors, 'created_by')"
                            />
                        </div>

                        <div class="col-md-6 row no-gutters mt-3">
                            <label class="col-md-12">{{ $t('reference_no') }}</label>
                            <app-input
                                class="margin-right-8 col-md-12"
                                type="number"
                                :placeholder="$placeholder('reference_no')"
                                v-model="formData.reference_number"
                                :error-message="$errorMessage(errors, 'reference_number')"
                            />
                        </div>
                    </div>

                    <div class="col-12 my-4 row no-gutters align-items-center"
                         v-if="Boolean(formData.return_order_products.length)">
                        <div class="labels col-12 row border-bottom no-gutters">
                            <p class="text-muted col-md-1">#</p>
                            <p class="text-muted col-md-2">{{ $t('items') }}</p>
                            <p class="text-muted col-md-2 align-items-center">{{ $t('quantity') }}</p>
                            <p class="text-muted col-md-2">{{ $t('sub_total') }}</p>
                            <p class="text-muted col-md-2">{{ $t('return_quantity') }}</p>
                            <p class="text-muted col-md-2 text-right">{{ $t('return_subtotal') }}</p>
                            <p class="col-md-1 text-right"></p>
                        </div>

                        <div
                            :class="`values row no-gutters align-items-baseline justify-content-between col-md-12 border-bottom py-3`"
                            v-for="( item, index ) in returnOrderProducts"
                            :key="index">
                            <p class="text-muted col-md-1">{{ index }}</p>
                            <div class="col-md-2 align-self-center">
                                <p class="mb-0 text-primary">{{ item.name }}</p>
                                <small v-if="Boolean(item.discount_value)"
                                       class="d-inline-block bg-primary px-2 text-white" style="border-radius: 5rem;">
                                    {{
                                        `${$t('discount')} - ${item.discount_value}${item.discount_type === 'percentage' ? '%' : ''}`
                                    }}
                                </small>
                            </div>

                            <p class="col-md-2 align-items-center"> {{ item.quantity }} {{ $t('pcs') }} </p>
                            <p class="col-md-2 align-items-center"> {{ numberWithCurrencySymbol(item.sub_total) }} </p>

                            <div class="col-md-2 d-flex justify-content-between">
                                <a @click.prevent="decrementUnit(index)"
                                   class="text-primary text-decoration-none" style="cursor: pointer;">
                                    <app-icon name="minus-circle"/>
                                </a>

                                <span> {{ item.return_quantity }} </span>

                                <a @click.prevent="incrementUnit(index)"
                                   class="text-primary text-decoration-none: pointer;">
                                    <app-icon name="plus-circle"/>
                                </a>
                            </div>

                            <p class="col-md-2 text-right">
                                {{
                                    numberWithCurrencySymbol(item.return_quantity * (item.price - item.discount_amount))
                                }}
                            </p>

                            <div class="col-md-1 text-primary text-right">
                                <a @click="removeProductFromReturnList(item.variant_id)">
                                    <app-icon name="x-circle" style="cursor: pointer;"/>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex my-4" style="gap: 1.5rem;">
                        <div class="d-flex" style="gap: 1rem;">
                            <h5 class="text-muted"> {{ $t('total_items_returned') }}: </h5>
                            <h5>{{ returnOrderProducts.length }}</h5>
                        </div>
                        <div class="d-flex" style="gap: 1rem;">
                            <h5 class="text-muted"> {{ $t('total_unit') }}: </h5>
                            <h5>{{ totalUnit }}</h5>
                        </div>
                        <div class="d-flex" style="gap: 1rem;">
                            <h5 class="text-muted"> {{ $t('return_total') }}: </h5>
                            <h5>{{ returnTotal ? numberWithCurrencySymbol(returnTotal) : 0 }}</h5>
                        </div>
                    </div>

                    <div class="form-element col-12 mb-4 no-gutters">
                        <label class="col-md-6">{{ $t("return_note") }}</label>
                        <app-input
                            class="margin-right-8 col-12"
                            type="textarea"
                            :placeholder="$placeholder('note')"
                            v-model="formData.note"
                        />
                    </div>
                </template>
            </template>
        </form>

        <div class="modal-footer ">
            <button class="btn btn-secondary" @click="handleReturnModalCloseClick">{{ $t('close') }}</button>
            <button :class="`btn btn-primary ml-4 ${ !formData.created_by || !formData.returned_at ? 'custom-disable' : '' }`" @click="submitDataTest" v-if="Boolean(formData.invoice_id)">
                {{ $t('print') }}
            </button>
        </div>

        <PrintAria v-show="false" v-if="printAria" id="tharmal-80mm" css="css/invoice/80mm.css"
                   @close="handlePrintAreaCloseEvent">
            <div v-html="purify(contentHtml)"></div>
        </PrintAria>

    </app-modal>
</template>
<script>
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import ModalMixin from "../../../../../common/Mixin/Global/ModalMixin";
import {
    DISCOUNT_LIST, GENERATE_UPC, ORDER_INVOICE_VIEW,
    SELECTABLE_CUSTOMERS,
    SELECTABLE_INVOICES,
    SELECTABLE_USERS,
    STORE_RETURN_ORDER
} from "../../../../Config/ApiUrl-CP";
import {axiosGet, axiosPost, urlGenerator} from "../../../../../common/Helper/AxiosHelper";
import {numberWithCurrencySymbol} from "../../../../Helper/Helper";
import {mapGetters} from "vuex";
import {purify} from "../../../../Helper/Purifier/HTMLPurifyHelper";
import PrintAria from "../../../../../common/Components/Helper/PrintAria";

export default {
    name: "ReturnCreateEditModal",
    mixins: [FormHelperMixins, ModalMixin],
    mounted() {
        this.generateReferenceNumber();
    },
    components: {PrintAria},
    data() {
        return {
            printAria: false,
            purify,
            showNote: true,
            contentHtml: '',
            DISCOUNT_LIST,
            STORE_RETURN_ORDER,
            discountOnSubtotal: 0, // gets set from populateFormData
            formData: {
                branch_or_warehouse_id: '',
                customer_id: '',
                order_id: '',
                created_by: '',
                reference_number: '',
                order_invoice_number: '',
                sub_total: '',
                total_tax: '',
                note: '',
                returned_at: new Date(),
                return_order_products: [],
                invoice_id: '',
            },
            customerOptions: {
                url: urlGenerator(SELECTABLE_CUSTOMERS),
                query_name: "search_by_name",
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                modifire: ({id, full_name: value}) => ({id, value}),
                prefetch: false
            },
            billerOptions: {
                url: urlGenerator(SELECTABLE_USERS),
                query_name: "search_by_name",
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                modifire: ({id, full_name: value}) => ({id, value}),
                prefetch: false,
            },
        }
    },
    watch: {
        'formData.invoice_id'(newInvoiceId) {
            if (!newInvoiceId) return;
            this.populateFormData(newInvoiceId);
        }
    },
    computed: {
        ...mapGetters(['getTenantId', 'getBranchOrWarehouseId']),

        invoiceIdOptions() {
            return {
                url: urlGenerator(SELECTABLE_INVOICES),
                query_name: "search",
                per_page: 10,
                loader: "app-pre-loader",
                modifire: ({id, invoice_number: value}) => ({id, value}),
                prefetch: false,
                params: {
                    'branch_or_warehouse_id': this.getBranchOrWarehouseId,
                    'status_id': 14
                }
            }
        },
        returnOrderProducts() {
            if (!this.formData.return_order_products.length) return [];
            return this.formData.return_order_products.filter(product => product.quantity);
        },
        totalUnit() {
            return this.returnOrderProducts.map(product => product.return_quantity).reduce((a, v) => a + v, 0);
        },
        totalQuantity() {
            if (!this.returnOrderProducts.length) return null;
            return this.returnOrderProducts.map(product => product.quantity).reduce((a, v) => a + v, 0);
        },
        productPricesWithDiscountAmountsDeducted() {
            const returnOrderProducts = this.returnOrderProducts;
            if (!returnOrderProducts) return;
            return returnOrderProducts.map((item) => (item.return_quantity * (item.price - item.discount_amount)));
        },
        totalReturnPrice() {
            if (!this.productPricesWithDiscountAmountsDeducted) return
            if (!this.productPricesWithDiscountAmountsDeducted.length) return 0;
            return this.productPricesWithDiscountAmountsDeducted.reduce((a, v) => a + v);
        },
        totalDiscount() {
            return (this.discountOnSubtotal * this.totalReturnPrice) / this.formData.sub_total_ ? this.formData.sub_total_ : 0;
        },
        returnTotal() {
            return (this.totalReturnPrice - this.discountOnSubtotal);
        }
    },
    methods: {
        handlePrintAreaCloseEvent() {
            this.printAria = false;
        },
        numberWithCurrencySymbol(value) {
            return numberWithCurrencySymbol(value)
        },
        formatDate(dateObj) {
            if (typeof dateObj === 'string') return dateObj;
            const date = {year: dateObj.getFullYear(), month: dateObj.getMonth() + 1, day: dateObj.getDate()}
            return `${date.year}-${date.month <= 9 ? '0' : ''}${date.month}-${date.day <= 9 ? '0' : ''}${date.day}`;
        },
        async generateReferenceNumber() {
            const {data: reference_no} = await axiosGet(GENERATE_UPC);
            this.formData.reference_number = reference_no;
        },
        attachOtherPayloadData(invoiceData) {
            this.formData.order_id = invoiceData.data.order.id;
            this.formData.sub_total = invoiceData.data.order.sub_total;
            this.formData.total_tax = invoiceData.data.order.total_tax;
            this.formData.branch_or_warehouse_id = invoiceData.data.order.branch_or_warehouse_id;
            this.formData.order_invoice_number = invoiceData.data.order.invoice_number;
            this.formData.customer_id = invoiceData.data.order.customer_id;
        },
        getSubtotalData(totalDiscount) {
            this.discountOnSubtotal = totalDiscount || 10;
        },
        async populateFormData(invoiceID) {
            const invoiceData = await axiosGet(ORDER_INVOICE_VIEW + invoiceID);
            this.attachOtherPayloadData(invoiceData);
            this.getSubtotalData(invoiceData.data.order.discount_value);
            const orderProducts = invoiceData.data.orderProductList;
            const productsToReturn = [];
            for (const op of orderProducts) productsToReturn.push(
                {
                    order_id: op.order_id,
                    stock_id: op.stock_id,
                    order_product_id: op.order_product_id,
                    quantity: op.quantity,
                    discount_type: op.discount_type,
                    discount_value: op.discount_value,
                    discount_amount: op.discount_amount,
                    price: op.price,
                    return_quantity: op.quantity,
                    tenant_id: op.tenant_id,
                    sub_total: op.sub_total,
                    name: op.variant.name,
                    variant_id: op.variant.id
                }
            );
            this.formData.return_order_products = productsToReturn;
        },
        resetData() {
            this.invoice_id = 0;
            this.discountOnSubtotal = 0;
            this.formData = {
                branch_or_warehouse_id: '',
                customer_id: '',
                order_id: '',
                created_by: '',
                reference_number: '',
                sub_total: '',
                total_tax: '',
                note: '',
                returned_at: new Date(),
                return_order_products: [],
                invoice_id: '',
            }
        },
        handleReturnModalCloseClick() {
            $("#discount-create-edit-modal").modal("hide");
            this.$emit('close');
        },
        removeProductFromReturnList(variant_id) {
            if (this.formData.return_order_products.length === 1) return this.resetData();
            this.formData.return_order_products = this.formData.return_order_products.filter((product) => product.variant_id !== variant_id);
        },
        incrementUnit(index) {
            const productToUpdate = this.formData.return_order_products[index];
            if (productToUpdate.quantity === productToUpdate.return_quantity) return;
            productToUpdate.return_quantity = productToUpdate.return_quantity + 1;
        },
        decrementUnit(index) {
            const productToUpdate = this.formData.return_order_products[index];
            if (productToUpdate.return_quantity === 1) return this.removeProductFromReturnList(productToUpdate.variant_id);
            productToUpdate.return_quantity = productToUpdate.return_quantity - 1;
        },
        assainValue(key, value) {
            this.contentHtml = this.contentHtml.replace(`{${key}}`, value)
        },
        prepareInvoice() {
            for (const key in this.invoiceData) {
                this.assainValue(key, this.invoiceData[key])
            }
        },
        submitDataTest() {
            this.loading = true;
            this.message = '';
            this.errors = {};

            const payload = {
                ...this.formData,
                returned_at: this.formData.returned_at ? this.formatDate(this.formData.returned_at) : '',
                total_returned_quantity: this.totalUnit,
                tenant_id: this.getTenantId
            }
            axiosPost(this.selectedUrl ? this.selectedUrl : STORE_RETURN_ORDER, payload)
                .then((res) => {
                    this.contentHtml = res.data.invoice_template;
                    this.$toastr.s(this.$t("items_return_successfully"));
                    this.$emit('successful-return-add');

                    this.printAria = true;
                    this.prepareInvoice();
                })
                .catch(e => {
                    this.$toastr.e(e)
                })
                .finally(() => {
                    this.handleReturnModalCloseClick();
                })
        },
        afterSuccess({data}) {
            this.formData = {};
            $('#discount-create-edit-modal').modal('hide');
            this.$emit('input', false);
            this.toastAndReload(data.message, 'return-list-table');
        },
    }
}
</script>

<style lang="sass">
.custom-margin
    margin-bottom: 25rem

.custom-disable
    opacity: 0.5
    pointer-events: none
</style>
