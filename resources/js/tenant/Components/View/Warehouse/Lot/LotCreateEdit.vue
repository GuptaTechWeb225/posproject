<template>
    <div :class="`content-wrapper ${ readOnly ? 'pe-none' : '' }`">
        <div class="d-flex align-items-center justify-content-between">
            <app-breadcrumb v-if="!readOnly" :page-title="!lotId ? $t('add_lot') : $t('edit_lot')"/>
            <app-breadcrumb v-else :page-title="$t('view_lot_')"/>
        </div>
        <app-pre-loader v-if="loading"/>
        <form v-else :data-url="lotId ? lotId : LOT" ref="form" :class="`row align-items-start no-gutters ${ $isRtl ? 'text-left' : '' }`">
            <!-- main lot add card -->
            <div class="card border-0 card-with-shadow col-md-12 mb-4">
                <div class="card-body row">
                    <!-- warehouse input -->
                    <div class="form-element col-12 col-md-4 mb-4">
                        <label>{{ $t('branch_or_warehouse') }}</label>
                        <app-input
                            :class="`${ !$isRtl ? 'margin-right-8' : 'margin-left-8' }`"
                            type="warehouse-or-branch"
                            :placeholder="$t('select_branch_or_warehouse')"
                            :inputclearable="false"
                            :key="ssKey"
                            :options="branchOrWareHoueeOptions"
                            v-model="formData.branch_or_warehouse_id"
                            :error-message="$errorMessage(errors, 'branch_or_warehouse_id')"
                        />
                    </div>

                    <!-- supplier input -->
                    <div class="form-element col-12 col-md-4 mb-4">
                        <label>{{ $t('supplier') }}</label>
                        <app-input
                            :class="`${ !$isRtl ? 'margin-right-8' : 'margin-left-8' }`"
                            type="search-and-select"
                            :inputclearable="false"
                            :placeholder="$t('select_supplier')"
                            :options="supplierOptions"
                            v-model="formData.supplier_id"
                            :error-message="$errorMessage(errors, 'supplier_id')"
                        />
                    </div>

                    <!-- purchase date input -->
                    <div class="form-element col-12 col-md-4 mb-4">
                        <label>{{ $t('purchase_date') }}</label>
                        <app-input
                            :class="`${ !$isRtl ? 'margin-right-8' : 'margin-left-8' }`"
                            type="date"
                            :placeholder="$t('select_date')"
                            v-model="formData.purchase_date"
                            :error-message="$errorMessage(errors, 'purchase_date')"
                        />
                    </div>

                    <!-- product input -->
                    <div class="form-element col-12 mb-4 row no-gutters" v-if="!readOnly">
                        <label class="col-12 col-md-6">{{ $t('select_product') }}</label>
                        <app-input
                            class="col-12"
                            type="search-and-select"
                            :disabled="!formData.branch_or_warehouse_id"
                            :placeholder="$t('select_product')"
                            :options="productOptions"
                            :key="formData.branch_or_warehouse_id"
                            v-model="latestAddedVariantToLot"
                        />
                    </div>

                    <div class="col-12 mb-4 row no-gutters align-items-center" v-if="formData.lotVariants.length">
                        <div class="labels col-12 row border-bottom no-gutters">
                            <p class="text-muted col-md-3">{{ $t('items') }}</p>
                            <p :class="`text-muted col-md-${readOnly ? '3' : '2'}`">{{ $t('unit') }}</p>
                            <p class="text-muted col-md-2">{{ $t('unit_price') }}</p>
                            <p class="text-muted col-md-2 text-right pr-5">{{ $t('total_price') }}</p>
                            <p class="text-muted col-md-2">{{ $t('unit_tax_percentage') }}</p>
                            <p class="text-muted col-md-1 text-right" v-if="!readOnly">{{ $t('action') }}</p>
                        </div>

                        <div
                            class="values row no-gutters align-items-center justify-content-between col-md-12 border-bottom py-3"
                            v-for="(item, index) in formData.lotVariants"
                            :key="item.variant_id">
                            <div class="col-md-3"> <!--products-->
                                <lot-profile
                                    :rowData="generateProductsProfileProp(item)" :disable-redirect="true"/>
                            </div>
                            <div :class="`col-md-${readOnly ? '3' : '2'} d-flex align-items-center`"
                                 style="gap: 1.5rem"> <!--unit-->
                                <a @click.prevent="decrementUnit(item.variant_id)" v-if="!readOnly"
                                   class="text-primary text-decoration-none cursor-pointer">
                                    <app-icon name="minus-circle"/>
                                </a>
                                <app-input
                                    type="number" class="count-input"
                                    style="width: 5rem;"
                                    v-model="item.unit_quantity"
                                    @input="handleItemQuantityChange(index)"
                                    @keydown.enter.prevent
                                />
                                <a @click.prevent="incrementUnit(item.variant_id)" v-if="!readOnly"
                                   class="text-primary text-decoration-none cursor-pointer">
                                    <app-icon name="plus-circle"/>
                                </a>
                            </div>
                            <div class="col-md-2"> <!--unit_price-->
                                <amount-control
                                    :placeholder="$t('select_unit_price')"
                                    :currentAmount="item.unit_price"
                                    @amountChange="handleUnitPriceAmountChange"
                                    :variantId="item.variant_id"
                                />
                            </div>
                            <div class="col-md-2 text-right pr-5">  <!--total_price-->
                                {{ numberWithCurrencySymbol(item.total_unit_price) }}
                            </div>
                            <div class="col-md-2"> <!--tax_percentage-->
                                <amount-control
                                    :placeholder="$t('select_tax')"
                                    :currentAmount="item.unit_tax_percentage"
                                    @amountChange="handleTaxAmountChange"
                                    :variantId="item.variant_id"
                                />
                            </div>
                            <div class="col-md-1 text-primary text-right" v-if="!readOnly">  <!--action-->
                                <a @click="removeProductFromLot(item.variant_id)"
                                   class="d-inline-block remove-item-btn cursor-pointer">
                                    <app-icon name="x-circle"/>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="total-details row col-12">
                        <div class="col-md-3 d-flex">
                            <h5 class="label text-muted pr-2">{{ $t('total_items') }}:</h5>
                            <h5 class="value">{{ totalItems }}</h5>
                        </div>
                        <div class="col-md-3 d-flex">
                            <h5 class="label text-muted pr-2">{{ $t('total_unit') }}:</h5>
                            <h5 class="value">{{ totalUnit }}</h5>
                        </div>
                        <div class="col-md-5 d-flex">
                            <h5 class="label text-muted pr-2">{{ $t('total_price') }}:</h5>
                            <h5 class="value"> {{ numberWithCurrencySymbol(subtotal) }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- other information card -->
            <div class="col-md-12 d-flex" style="gap: 1.5rem;">
                <div class="card card-with-shadow">
                    <div class="card-body row no-gutters">
                        <h5 class="text-muted col-md-12 mb-3 border-bottom pb-2">{{ $t('other_information') }}</h5>
                        <div class="form-element col-12 col-md-6 mb-4">
                            <label>{{ $t('purchase_status') }}</label>
                            <app-input
                                :class="`${ !$isRtl ? 'margin-right-8' : 'margin-left-8' }`"
                                type="search-and-select"
                                :inputclearable="false"
                                :placeholder="$t('select_purchase_status')"
                                :options="purchaseStatusOptions"
                                v-model="formData.status_id"
                                :error-message="$errorMessage(errors, 'status_id')"
                            />
                        </div>

                        <div class="form-element col-12 col-md-6 mb-4">
                            <label>{{ $t('lot_reference_no') }}</label>
                            <app-input
                                :class="`${ !$isRtl ? 'margin-right-8' : 'margin-left-8' }`"
                                type="number"
                                @keydown.enter.prevent="returnFalse"
                                :placeholder="$placeholder('reference_no')"
                                v-model="formData.reference_no"
                                :error-message="$errorMessage(errors, 'reference_no')"
                            />
                        </div>

                        <div class="form-element col-12 col-md-6 mb-4">
                            <label>{{ $t('other_charge') }}</label>
                            <app-input
                                :class="`${ !$isRtl ? 'margin-right-8' : 'margin-left-8' }`"
                                type="number"
                                @keydown.enter.prevent="returnFalse"
                                :placeholder="$placeholder('other_charge')"
                                v-model="formData.other_charge"
                                :error-message="$errorMessage(errors, 'other_charge')"
                            />
                        </div>

                        <div class="form-element col-12 col-md-6 mb-4">
                            <label>{{ $t('discount') }}</label>
                            <app-input
                                :class="`${ !$isRtl ? 'margin-right-8' : 'margin-left-8' }`"
                                type="number"
                                @keydown.enter.prevent="returnFalse"
                                :placeholder="$placeholder('discount')"
                                v-model="formData.discount_amount"
                                :error-message="$errorMessage(errors, 'discount_amount')"
                            />
                        </div>

                        <div class="form-element col-12">
                            <label>{{ $t('note') }}</label>
                            <app-input
                                :class="`${ !$isRtl ? 'margin-right-8' : 'margin-left-8' }`"
                                type="textarea"
                                :placeholder="$placeholder('note')"
                                v-model="formData.note"
                                :error-message="$errorMessage(errors, 'note')"
                            />
                        </div>
                    </div>
                </div>

                <!-- Payment summary card  -->
                <div class="card border-0 card-with-shadow">
                    <div class="card-body row text-right align-items-start no-gutters">
                        <h5 class="text-muted col-md-12 mb-3 pb-2 border-bottom"> {{ $t('payment_summary') }} </h5>

                        <div class="row col-md-12 no-gutters mb-3">
                            <div class="info-field row col-md-12 no-gutters mb-2">
                                <span class="label text-muted col-md-6">{{ $t('subtotal') }}:</span>
                                <span class="value col-6"> {{ numberWithCurrencySymbol(subtotal) }}</span>
                            </div>
                            <div class="info-field row col-md-12 no-gutters mb-2">
                                <span class="label text-muted col-md-6">{{ $t('other_charge') }}:</span>
                                <span class="value col-6"> {{
                                        numberWithCurrencySymbol(formData.other_charge || 0)
                                    }}</span>
                            </div>
                            <div class="info-field row col-md-12 no-gutters mb-2">
                                <span class="label text-muted col-md-6">{{ $t('tax_/_vat') }}:</span>
                                <span class="value col-6"> {{ numberWithCurrencySymbol(totalTaxAmount) }}</span>
                            </div>
                            <div class="info-field row col-md-12 no-gutters mb-2">
                                <span class="label text-muted col-md-6">(-) {{ $t('discount') }}:</span>
                                <span class="value col-6"> {{
                                        numberWithCurrencySymbol(formData.discount_amount || 0)
                                    }}</span>
                            </div>
                        </div>

                        <div class="row col-md-12 no-gutters border-top pt-2">
                            <div class="row col-md-12 no-gutters">
                                <h4 class="label col-md-6" style="text-transform: uppercase;">{{ $t('total') }}:</h4>
                                <h4 class="value col-md-6"> {{ numberWithCurrencySymbol(totalPrice) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" v-if="!readOnly">
                <div class="col-12 mt-5">
                    <app-cancel-button btn-class="btn-secondary" @click="resetData"/>
                    <button class="btn btn-primary ml-2" @click.prevent="submitData">{{
                            !lotId ? $t('add') : $t('save')
                        }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import {mapGetters} from "vuex";
import {axiosGet, urlGenerator} from "../../../../../common/Helper/AxiosHelper";
import HelperMixin from "../../../../../common/Mixin/Global/HelperMixin";
import {LOT, GENERATE_UPC, SELECTABLE_VARIANTS} from "../../../../Config/ApiUrl-CP";
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import AmountControl from "./AmountControl";
import {formatDateToLocal, numberWithCurrencySymbol} from "../../../../Helper/Helper";

export default {
    name: 'app-lot-create-edit',
    mixins: [HelperMixin, FormHelperMixins],
    components: {
        'amount-control': AmountControl
    },
    props: ['lotId'],
    async mounted() {
        this.setViewMode(); // toggling read only mode if user clicks on the 'view' action
        this.formData.purchase_date = new Date(); // setting the current date

        if (this.lotId) {
            this.loading = true
            const editData = await axiosGet(`app/lot/${this.lotId}`);
            return this.afterSuccessFromGetEditData(editData);
        }
        // setting the pending status id by default on lot add
        const {data: {data: purchaseStatuses}} = await axiosGet('app/selectable-statuses?type=purchase');
        this.setUniqueReferenceNo();
    },
    data() {
        return {
            temporarySelectBoxValue: 'SELECT_VALUE',
            ssKey: 0,
            formatDateToLocal,
            readOnly: false,
            LOT,
            latestAddedVariantToLot: '',
            postUrl: '',
            formData: {
                branch_or_warehouse_id: '',
                supplier_id: '',
                purchase_date: '',
                discount_amount: '',
                status_id: '',
                reference_no: '',
                other_charge: '',
                note: '',
                lotVariants: [
                ]
            },
            warehouseOptions: {
                url: urlGenerator("app/selectable-warehouses"), // temporary api
                query_name: "search_by_name",
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                modifire: ({id, name: value}) => ({id, value}),
            },
            branchOrWareHoueeOptions: {
                url: urlGenerator("app/selectable-branches-or-warehouses"),
                queryName: 'search_by_name',
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                modifire: (value) => ({id: value.id, value: value.name, type: value.type}),
            },
            supplierOptions: {
                url: urlGenerator("app/selectable-suppliers"),
                queryName: 'search_by_name',
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                modifire: (value) => ({id: value.id, value: value.name}),
                prefetch: !Boolean(this.lotId)
            },
            purchaseStatusOptions: {
                url: urlGenerator("app/selectable-statuses"),
                query_name: "search_by_name",
                params: {
                    type: 'purchase',
                },
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                modifire: (value) => ({id: value.id, value: value.translated_name}),
                prefetch: !Boolean(this.lotId)
            },
        }
    },
    computed: {
        ...mapGetters(['getBranchOrWarehouseId']),
        productOptions() {
            return {
                url: urlGenerator(SELECTABLE_VARIANTS),
                query_name: "search_by_name",
                params: {
                    'branch_or_warehouse_id': this.formData.branch_or_warehouse_id,
                },
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                modifire: ({id, name: value}) => ({id, value}),
            }
        },
        totalUnit() {
            return this.formData.lotVariants.map(lotItem => lotItem.unit_quantity).reduce((a, v) => +a + +v, 0).toFixed(2);
        },
        totalItems() {
            return this.formData.lotVariants.length;
        },
        subtotal() {
            return this.formData.lotVariants.map(lotItem => lotItem.total_unit_price).reduce((a, v) => +a + +v, 0).toFixed(2);
        },
        totalTaxAmount() {
            return this.formData.lotVariants.map(lotItem => +lotItem.unit_quantity * (+lotItem.unit_price * +lotItem.unit_tax_percentage / 100)).reduce((a, v) => a + v, 0);
        },
        totalPrice() {
            return ((+this.subtotal + +this.formData.other_charge + +this.totalTaxAmount) - +this.formData.discount_amount);
        }
    },
    watch: {
        async latestAddedVariantToLot(newVariantId) {
            if (!newVariantId) return;
            if (this.formData.lotVariants.some(lotVariant => lotVariant.variant_id === newVariantId)) return this.incrementUnit(newVariantId);
            const newLotVariant = await this.generateLotItem(newVariantId);
            this.formData.lotVariants.push(newLotVariant);

            this.latestAddedVariantToLot = '';
        },
        'getBranchOrWarehouseId': {
            immediate: true,
            handler(newId) {
                if (!newId) return;
                this.formData.branch_or_warehouse_id = newId;
                this.ssKey++;
            }
        }
    },
    methods: {
        setUniqueReferenceNo() {
            axiosGet(GENERATE_UPC).then(({data: UPC}) => this.formData.reference_no = UPC);
        },
        setViewMode() {
            let queryParams = new URLSearchParams(window.location.search);
            this.readOnly = Boolean(eval(queryParams.get('read_only')));
        },
        returnFalse() {
            return false
        }, /* this is to disable enter press submit on input fields */
        numberWithCurrencySymbol(value) {
            return numberWithCurrencySymbol(value);
        },
        generateProductsProfileProp(item) {
            return {
                id: item.variant_id,
                status: 'status_active',
                thumbnail: item.thumbnail,
                product: item.product,
                variant: {
                    upc: item.upc,
                    selling_price: item.selling_price,
                    name: item.product_name,
                }
            }
        },
        formatDate(dateObj) {
            dateObj = new Date(dateObj);
            const date = {year: dateObj.getFullYear(), month: dateObj.getMonth() + 1, day: dateObj.getDate()}
            return `${date.year}-${date.month <= 9 ? '0' : ''}${date.month}-${date.day <= 9 ? '0' : ''}${date.day}`;
        },
        removeProductFromLot(variantId) {
            this.formData.lotVariants = this.formData.lotVariants.filter(item => item.variant_id !== variantId);
        },
        handleItemQuantityChange(index) {
            const variant = this.formData.lotVariants[index];
            if (variant.unit_quantity === "") return;
            if (+variant.unit_quantity <= 0) variant.unit_quantity = 1;
            variant.total_unit_price = +variant.unit_quantity * +variant.unit_price;
        },
        incrementUnit(variantId) {
            this.formData.lotVariants = this.formData.lotVariants.map(
                item =>
                    item.variant_id !== variantId ? item : {
                        ...item,
                        unit_quantity: +item.unit_quantity + 1,
                        total_unit_price: ((+item.unit_quantity + 1) * +item.unit_price)
                    }
            );
        },
        decrementUnit(variantId) {
            // exiting the function if unit_quantity count is 1
            if (this.formData.lotVariants.find(lotItem => lotItem.variant_id === variantId).unit_quantity <= 1) return;

            this.formData.lotVariants = this.formData.lotVariants.map(item => item.variant_id !== variantId ? item : {
                ...item,
                unit_quantity: +item.unit_quantity - 1,
                total_unit_price: ((+item.unit_quantity - 1) * +item.unit_price)
            });
        },
        async generateLotItem(variantId) {
            const variantDataResponseData = await axiosGet(`/app/variant/${variantId}`);
            const {data: {upc, name, thumbnail, product, selling_price, status: {name: status_name}}} = variantDataResponseData;
            const newLotItem = {
                product_name: name,
                variant_id: variantId,
                product, 
                selling_price,
                upc,
                thumbnail,
                status_name,
                unit_quantity: 1,
                unit_price: 0,
                unit_tax_percentage: 0,
            };

            return {...newLotItem, total_unit_price: newLotItem.unit_price * newLotItem.unit_quantity};
        },
        handleUnitPriceAmountChange({variantId, newAmount}) {
            this.formData.lotVariants =
                this.formData.lotVariants
                    .map(lotVariant =>
                        lotVariant.variant_id !== variantId ? lotVariant : {
                            ...lotVariant,
                            unit_price: newAmount,
                            total_unit_price: lotVariant.unit_quantity * newAmount
                        });
        },
        handleTaxAmountChange({variantId, newAmount}) {
            this.formData.lotVariants = this.formData.lotVariants
                .map(lotVariant => lotVariant.variant_id !== variantId ? lotVariant : {
                    ...lotVariant,
                    unit_tax_percentage: newAmount
                });
        },
        submitData() {
            if (!this.formData.lotVariants.length) return this.$toastr.e(this.$t("please_enter_products_to_store"));
            if (this.formData.lotVariants.find(lotVariant => +lotVariant.unit_price <= 0)) return this.$toastr.e(this.$t("please_enter_valid_unit_prices"));
            this.formData = {
                ...this.formData,
                purchase_date: this.formatDate(this.formData.purchase_date),
                total_amount: this.totalPrice,
                reference_no: this.formData.reference_no + ''
            };

            if (this.lotId) return this.submitFromFixin(
                'patch',
                `app/lot/${this.lotId}`,
                this.formData
            )

            this.save(this.formData);
        },
        resetData() {
            this.latestAddedVariantToLot = '';
            this.formData = {
                warehouse_id: '',
                branch_id: '',
                supplier_id: '',
                purchase_date: '',
                discount_amount: '',
                status_id: '',
                reference_no: '',
                other_charge: '',
                note: '',
                lotVariants: []
            }
        },
        afterSuccess({data}) {
            this.$toastr.s('', data.message);
            if (!this.lotId) this.resetData(); // resetting everything if user is adding a lot
            this.setUniqueReferenceNo();
        },
        afterSuccessFromGetEditData(response) {
            this.formData = {
                ...response.data,
                lotVariants: response.data.lot_variants.map(lv => ({
                    ...lv,
                    product_name: lv.variant.name,
                    upc: lv.variant.upc,
                    selling_price : lv.variant.selling_price
                }))
            };
            delete this.formData.lot_variants;
            this.loading = false
        },
        afterError(response) {
            this.errors = response.data.errors || {};
            this.$toastr.e(response.data.message);
        },
    }
}
</script>

<style lang="sass" scoped>
.remove-item-btn
    transition: 100ms

    &:hover
        transform: scale(1.2)

.pe-none
    pointer-events: none
</style>
