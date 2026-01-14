<template>
    <modal id="discount-create-edit-modal"
           v-model="showModal"
           size="extra-large"
           :loading="loading"
           :preloader="preloader" :title="generateModalTitle('discount')"
           @submit="submitDataTest">

        <form
            ref="form"
            :data-url='selectedUrl ? selectedUrl : DISCOUNT_LIST'
            enctype="multipart/form-data">

            <app-form-group
                v-model="formData.discount_type"
                type="radio"
                class="mb-2"
                @input="handleDiscountTypeRadioInput"
                :label="$t('discount_type')"
                :list="discountTypes"
                :required="true"
                :disabled="Boolean(selectedUrl)"
                :error-message="$errorMessage(errors, 'discount_type')"
            />

            <div class="note note-warning p-4 mb-3">
                <div class="note-title d-flex">
                    <app-icon :name="'book-open'" class="text-warning"/>
                    <h6 class="card-title pl-2"> {{ $t('what_is_an_individual_discount') }}</h6>
                </div>
                <div class="ml-2">
                    <ul>
                        <li>{{
                                $t("discounts_on_individual_products_only_have_an_impact_on_each_variant's_unit_pricing")
                            }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <app-form-group
                        v-model="formData.name"
                        type="text"
                        :label="$t('discount_name')"
                        :placeholder="$placeholder('discount_name')"
                        :required="true"
                        :error-message="$errorMessage(errors, 'name')"
                    />
                </div>


                <div :class="`col-md-6 ${ $isRtl ? 'text-left' : '' }`">
                    <label>{{ $t('branch') }}</label>
                    <template v-if="formData.branch_or_warehouse_id === null">
                        <app-input
                            class="margin-right-8"
                            type="search-and-select"
                            :placeholder="$t('choose_branch')"
                            :options="branchesOrWarehousesOptions"
                            :inputclearable="false"
                            v-model="formData.branch_or_warehouse_id"
                            :error-message="$errorMessage(errors, 'branch_or_warehouse_id')"
                        />
                    </template>
                    <template v-else>
                        <app-input
                            class="margin-right-8"
                            type="search-and-select"
                            :placeholder="$t('choose_branch')"
                            :options="branchesOrWarehousesOptions"
                            :inputclearable="false"
                            @input="handleBranchInput"
                            v-if="formData.branch_or_warehouse_id"
                            v-model="formData.branch_or_warehouse_id"
                            :error-message="$errorMessage(errors, 'branch_or_warehouse_id')"
                        />
                    </template>
                </div>


                <div class="col-md-6">
                    <app-form-group
                        v-model="formData.start_at"
                        type="date"
                        :label="$t('starts_at')"
                        :placeholder="$placeholder('date')"
                        :error-message="$errorMessage(errors, 'start_at')"
                    />
                </div>

                <div class="col-md-6">
                    <app-form-group
                        v-model="formData.end_at"
                        type="date"
                        :label="$t('ends_at')"
                        :placeholder="$placeholder('date')"
                        :min-date="formData.start_at"
                        :error-message="$errorMessage(errors, 'end_at')"
                    />
                </div>

                <div class="col-6">
                    <app-form-group
                        v-model="formData.value"
                        :label="$t('amount')+' '+`${formData.type === 'percentage' ? '(%)' : '('+ getCurrencySymbol() +')'}`"
                        @keyup="handleDiscountAmountInput"
                        class="col-12 px-0 mx-0"
                        :placeholder="$placeholder('amount')"
                        :required="true"
                        type="currency"
                        :error-message="$errorMessage(errors, 'value')"
                    />
                    <small class="text-danger" v-if="fixedAmountExceeded">{{
                            $t('fixed_amounts_cannot_exceed_product_selling_prices')
                        }}</small>
                </div>
                <app-input
                    type="radio"
                    :required="true"
                    @input="handleTypeRadioInput"
                    class="col-md-6 pt-5"
                    v-model="formData.type"
                    :list="discountAmountTypes"
                    :error-message="$errorMessage(errors, 'type')"
                />
            </div>

            <template v-if="formData.discount_type === 'individual'">
                <div class="text-warning d-flex align-item-center">
                    <app-icon name="alert-circle" class="mr-2"/>
                    <p style="margin-top: 2px;">
                        {{
                            $t('selecting_products_with_ongoing_discounts_will_override_the_existing_discounts')
                        }}! </p>
                </div>
                <!-- product input -->
                <div :class="`mb-4 row ${ $isRtl ? 'text-left' : '' }`" v-if="formData.branch_or_warehouse_id">
                    <label class="col-md-12">{{ $t('select_product') }}</label>
                    <app-input
                        class="col-md-12"
                        type="search-and-select"
                        :key="formData.branch_or_warehouse_id"
                        :placeholder="$placeholder('product')"
                        :show-extended-dropdown-details="true"
                        :options="productOptions"
                        v-model="latestAddedVariantId"
                    />
                </div>

                <template v-if="formData.discount_products.length">
                    <div class="mb-4 row no-gutters">
                        <div class="labels col-12 row border-bottom no-gutters">
                            <p class="text-muted col-md-4">{{ $t('products') }}</p>
                            <p class="text-muted col-md-4 text-right">{{ $t('stock_available') }}</p>
                            <p class="text-muted col-md-4 text-right">{{ $t('action') }}</p>
                        </div>

                        <div
                            class="values row no-gutters align-items-baseline justify-content-between col-md-12 border-bottom py-3"
                            v-for="(item, index) in formData.discount_products"
                            :key="item.variant_id">
                            <div class="d-flex align-items-center col-md-4">
                                <div class="mr-2">
                                    <app-avatar
                                        :key="item.upc || item.variant.upc"
                                        :border="true"
                                        status="success"
                                        avatar-class="avatars-w-60"
                                        :title="item.name || item.variant.name"
                                    />
                                </div>
                                <div>
                                    <a> {{ item.name || item.variant.name }} </a>
                                    <small class="d-block text-muted">{{ item.upc || item.variant.upc }}</small>
                                </div>
                            </div>

                            <p class="col-md-4 text-right"> {{
                                    item.stock || item.variant.stock.available_qty
                                }} </p>

                            <div class="col-md-4 text-right cursor-pointer"
                                 @click="handleDiscountProductRemoveBtnClick(index)">
                                <app-icon name="trash-2" class="text-primary"/>
                            </div>
                        </div>
                    </div>

                    <div class="total-details">
                        <h4 class="label pr-2">
                            <strong class="label text-muted"> {{ $t('total_items') }}: </strong>
                            <strong class="value"> {{ totalItems }} </strong>
                        </h4>
                    </div>
                </template>
            </template>

            <app-form-group
                v-model="formData.note"
                :error-message="$errorMessage(errors, 'note')"
                class="mt-5"
                :label="$t('note')"
                type="textarea"
                :placeholder="$placeholder('note')"
            />
        </form>
    </modal>
</template>
<script>
import FormHelperMixins from "../../../../common/Mixin/Global/FormHelperMixins";
import ModalMixin from "../../../../common/Mixin/Global/ModalMixin";
import {DISCOUNT_LIST, STOCK_VARIANT_WITH_BRANCH} from "../../../Config/ApiUrl-CP";
import {formDataAssigner, getCurrencySymbol} from "../../../Helper/Helper";
import {formatDateForServer} from "../../../../common/Helper/Support/DateTimeHelper";
import {axiosGet, urlGenerator} from "../../../../common/Helper/AxiosHelper";
import {mapGetters} from "vuex";
import {SELECTABLE_BRANCHES} from "../../../Config/ApiUrl-CPB";

export default {
    name: "DiscountCreateEditModal",
    mixins: [FormHelperMixins, ModalMixin],
    watch: {
        async latestAddedVariantId(newVariantId) {
            if (!newVariantId) return;
            const newDiscountProductExists = this.formData.discount_products.some(product => +product.variant_id === +newVariantId);
            if (newDiscountProductExists) return;
            const newDiscountProduct = await this.generateProductWithDiscount(newVariantId);
            this.formData.discount_products.push(newDiscountProduct);
            this.latestAddedVariantId = '';
        }, 
    },
    data() {
        return {
            showNote: true,
            DISCOUNT_LIST,
            getCurrencySymbol,
            latestAddedVariantId: '',
            currentBranchSet: false,
            formData: {
                name: '',
                note: '',
                branch_or_warehouse_id: '',
                value: '',
                start_at: '',
                end_at: '',
                discount_products: [],
                discount_type: 'individual',
                type: 'percentage'
            },
            discountTypes: [
                {
                    id: 'individual',
                    value: this.$t('individual')
                },
                {
                    id: 'flat_discount',
                    value: this.$t('flat_discount')
                }
            ],
            branchesOrWarehousesOptions: {
                url: urlGenerator(SELECTABLE_BRANCHES),
                query_name: "search",
                per_page: 10,
                loader: "app-pre-loader",
                modifire: ({id, name: value}) => ({id, value}),
                prefetch: false,
            },
        }
    },
    computed: {
        ...mapGetters(['getBranchOrWarehouseId']),
        discountAmountTypes() {
            if (this.formData.discount_type === 'individual') return [
                {id: 'percentage', value: 'Percentage'},
                {id: 'flat', value: 'Fixed'}
            ]

            return [{id: 'percentage', value: 'Percentage'}];
        },
        totalItems() {
            return this.formData.discount_products.length;
        },
        productOptions() {
            return {
                url: urlGenerator(`app/discount-products/${this.formData.branch_or_warehouse_id}`),
                params: {
                    search_stock_by_branch_or_warehouse: this.formData.branch_or_warehouse_id,
                },
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                modifire: (value) => value,
            }
        },
        fixedAmountExceeded() {
            if (this.formData.type === "percentage") return false;
            const selling_prices = this.formData.discount_products.map(dp => dp?.selling_price ?? dp.variant.selling_price);
            return selling_prices.some(sp => +sp <= +this.formData.value);
        }
    },
    mounted() {
        if (this.selectedUrl) {
            axiosGet(this.selectedUrl).then(res => {
                this.preloader = false;
                this.formData = res.data;
                if (this.formData.note) return;
                this.formData.note = '';
            });
            return;
        }
        this.formData.start_at = new Date();
        this.formData.branch_or_warehouse_id = this.getBranchOrWarehouseId;
    },
    methods: {
        handleBranchInput() {
            if (!this.currentBranchSet) {
                this.currentBranchSet = true;
                return;
            }
            this.resetDiscountProducts();
        },
        resetDiscountProducts() {
            this.formData.discount_products = [];
        },
        handleTypeRadioInput() {
            this.formData.value = 0;
        },
        handleDiscountAmountInput() {
            if (this.formData.type !== 'percentage') return
            if (this.formData.value >= 100) this.formData.value = 100;
        },
        handleDiscountTypeRadioInput() {
            if (this.formData.discount_type === 'flat_discount') this.formData.type = "percentage";
            this.resetDiscountProducts();
        },
        formatDate(dateObj) {
            if (typeof dateObj === 'string') return dateObj;
            const date = {year: dateObj.getFullYear(), month: dateObj.getMonth() + 1, day: dateObj.getDate()}
            return `${date.year}-${date.month <= 9 ? '0' : ''}${date.month}-${date.day <= 9 ? '0' : ''}${date.day}`;
        },
        async generateProductWithDiscount(variantId) {
            const variantDataResponseData = await axiosGet(`${STOCK_VARIANT_WITH_BRANCH}/${this.formData.branch_or_warehouse_id}/${variantId}`);
            const {name, upc, id: variant_id, product_id, selling_price} = variantDataResponseData.data;
            const {available_qty: stock} = variantDataResponseData.data.stock;
            return {
                name,
                upc,
                stock,
                variant_id,
                product_id,
                selling_price,
                branch_or_warehouse_id: this.formData.branch_or_warehouse_id
            };
        },
        handleDiscountProductRemoveBtnClick(index) {
            this.formData.discount_products = this.formData.discount_products.filter((p, i) => i !== index);
        },
        submitDataTest() {
            if (this.formData.discount_type === 'individual') {
                if (this.formData.discount_products.length === 0) return this.$toastr.e(this.$t('please_enter_products_to_set_the_individual_discounts_to'))
            }

            if (this.formData.name && this.formData.end_at) {
                if (this.fixedAmountExceeded) return this.$toastr.e(this.$t('please_reduce_the_discount_amount'));
                if (+this.formData.value <= 0) return this.$toastr.e(this.$t('invalid_discount_amount'));
            }

            this.message = '';
            this.errors = {};

            this.formData = {
                ...this.formData,
                start_at: this.formData.start_at ? this.formatDate(this.formData.start_at) : '',
                end_at: this.formData.end_at ? this.formatDate(this.formData.end_at) : ''
            };
            let formData = {...this.formData};
            formData = formDataAssigner(new FormData, this.formData);

            if (this.selectedUrl) formData.append('_method', 'patch');
            formData.append('expense_date', formatDateForServer(this.formData.expense_date))

            return this.submitFromFixin(
                'post',
                this.selectedUrl ? this.selectedUrl : DISCOUNT_LIST,
                formData
            );
        },
        afterSuccess({data}) {
            this.formData = {};
            $('#discount-create-edit-modal').modal('hide');
            this.$emit('input', false);
            this.toastAndReload(data.message, 'discount-table');
        },
        closeModal() {
            this.$emit('close-modal');
        }, 
        generateProductsProfileProp(item) {
            return {
                id: item.variant_id,
                status: 'status_active',
                variant: {
                    upc: item.upc,
                    name: item.product_name,
                }
            }
        },
        removeProductFromLot(variantId) {
            this.formData.discount_products = this.formData.discount_products.filter(item => item.variant_id !== variantId);
        },
    }
}
</script>

<style lang="sass" scoped>
.custom-disable
    opacity: 0.5
    pointer-events: none

.special-disable
    transition: 200ms

    &:hover
        margin-right: 2.5rem

</style>
