<template>
    <modal id="stock-adjustment-create-edit-modal"
           modal-id="stock-adjustment-create-edit-modal"
           v-model="showModal"
           :loading="loading"
           :scrollable="false"
           :preloader="preloader"
           size="large"
           :title="generateModalTitle('adjustment')"
           @close-modal="closeModal"
           @submit="submit">

        <form
            ref="form"
            :class="`row ${ $isRtl ? 'text-left' : '' }`"
            :data-url='selectedUrl ? selectedUrl : ""'
            enctype="multipart/form-data">

            <!-- branch and/or warehouse input -->
            <div :class="`form-element col-12 col-md-6 mb-4 ${selectedUrl ? 'custom-disable' : ''}`">
                <label>{{ $t('branch_or_warehouse') }}</label>
                <app-input
                    :class="`${ !$isRtl ? 'margin-right-8 ' : 'margin-left-8' }`"
                    type="search-and-select"
                    :placeholder="$placeholder('branch_or_warehouse')"
                    :key="branchInputKey"
                    :disabled="branchInputDisabled"
                    :inputclearable="false"
                    :options="branchesOrWarehousesOptions"
                    v-model="formData.branch_or_warehouse_id"
                    :error-message="$errorMessage(errors, 'branch_or_warehouse_id')"
                />
            </div>

            <!-- date input -->
            <div class="form-element col-12 col-md-6 mb-4">
                <label>{{ $t('date') }}</label>
                <app-input
                    :class="`${ !$isRtl ? 'margin-right-8 ' : 'margin-left-8' }`"
                    type="date"
                    :placeholder="$placeholder('date')"
                    v-model="formData.adjustment_date"
                    :error-message="$errorMessage(errors, 'adjustment_date')"
                />
            </div>

            <!-- product input -->
            <hr>
            <div
                v-show="!itemToAdjust"
                :class="`form-element col-12 mb-4 row no-gutters ${!formData.branch_or_warehouse_id ? 'custom-disable' : ''}`">
                <label class="col-12 col-md-6">{{ $t('select_product') }}</label>
                <app-input
                    class="col-12"
                    type="search-and-select"
                    :placeholder="$placeholder('product')"
                    :key="formData.branch_or_warehouse_id"
                    :options="productOptions"
                    v-model="latestAddedVariantToStockAdjustment"
                />
            </div>

            <div class="col-12 mb-4 row no-gutters align-items-center"
                 v-if="Boolean(formData.adjustmentVariants.length)">
                <div class="labels col-12 row border-bottom no-gutters">
                    <p class="text-muted col-md-3">{{ $t('product') }}</p>
                    <p class="text-muted col-md-2 align-items-center">{{ $t('stock') }}</p>
                    <p class="text-muted col-md-3">{{ $t('quantity') }}</p>
                    <p class="text-muted col-md-2">{{ $t('type') }}</p>
                    <p class="text-muted col-md-2 text-right">{{ $t('action') }}</p>
                </div>

                <div
                    class="values row no-gutters align-items-baseline justify-content-between col-md-12 border-bottom py-3"
                    v-for="( item, index ) in formData.adjustmentVariants"
                    :key="item.variant_id">

                    <div class="col-md-3 align-self-center">
                        <p class="mb-0 text-primary">{{ item.product_name ? item.product_name : item.variant.name }}</p>
                        <small class="d-block text-muted">#{{ item.upc ? item.upc : item.variant.upc }}</small>
                    </div>

                    <p class="col-md-2 align-items-center"> {{ item.stock }} </p>

                    <div class="col-md-3 d-flex align-items-baseline quantity-control" style="gap: 1.5rem">
                        <a @click.prevent="decrementUnit(index)" class="text-primary text-decoration-none"
                           style="cursor: pointer;">
                            <app-icon name="minus-circle"/>
                        </a>
                        <app-input
                            type="number"
                            class="count-input"
                            style="width: 5rem;"
                            v-model="item.unit_quantity"
                            @input="handleItemQuantityChange(index)"
                        />
                        <a @click.prevent="incrementUnit(index)" class="text-primary"
                           style="text-decoration-none: pointer;">
                            <app-icon name="plus-circle"/>
                        </a>
                    </div>

                    <div class="col-md-2">
                        <app-input
                            type="select"
                            :list="selectableList.adjustment_types"
                            v-model="formData.adjustmentVariants[index].adjustment_type"
                            list-value-field="value"
                            :error-message="$errorMessage(errors, 'adjustment_type')"/>
                    </div>

                    <div class="col-md-2 text-primary text-right">
                        <a @click="removeProductFromLot(item.variant_id)">
                            <app-icon name="x-circle" style="cursor: pointer;"/>
                        </a>
                    </div>
                </div>
            </div>

            <!-- note input -->
            <div class="form-element col-12 mb-4 no-gutters">
                <label>{{ $t('note') }}</label>
                <app-input
                    class=""
                    type="textarea"
                    :placeholder="$placeholder('note')"
                    v-model="formData.note"
                    :error-message="$errorMessage(errors, 'note')"
                />
            </div>
        </form>

    </modal>

</template>

<script>
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import ModalMixin from "../../../../../common/Mixin/Global/ModalMixin";
import {
    GENERATE_UPC, GET_AVAILABLE_STOCK,
    SELECTABLE_BRANCH, SELECTABLE_BRANCHES_OR_WAREHOUSES,
    SELECTABLE_RECEIVED_BY, SELECTABLE_STOCK_ADJUSTMENT_VARIANTS_WITH_BRANCH,
    SINGLE_VARIANT,
    STOCK_ADJUSTMENTS
} from "../../../../Config/ApiUrl-CP";
import {formDataAssigner} from "../../../../Helper/Helper";
import {axiosGet, urlGenerator} from "../../../../../common/Helper/AxiosHelper";
import {mapGetters} from "vuex";

export default {
    name: "StockAdjustmentCreateEditModal",
    mixins: [FormHelperMixins, ModalMixin],
    props: {
        itemToAdjust: {
            default: null,
        }
    },
    mounted() {
        this.formData.branch_or_warehouse_id = this.getBranchOrWarehouseId;
        this.branchInputKey++;
        if (this.itemToAdjust) return this.setStockItemsFromProp();
    },
    data() {
        return {
            showNote: true,
            branchInputKey: 0,
            formData: {
                adjustment_date: new Date(),
                adjustmentVariants: [],
                branch_or_warehouse_id: '',
            },
            selectableList: {
                adjustment_types: [
                    {id: 'addition', value: this.$t('addition')},
                    {id: 'subtraction', value: this.$t('subtraction')}
                ],
            },
            latestAddedVariantToStockAdjustment: '',
            SELECTABLE_BRANCH,
            SELECTABLE_RECEIVED_BY,
            branchesOrWarehousesOptions: {
                url: urlGenerator(SELECTABLE_BRANCHES_OR_WAREHOUSES),
                query_name: "search_by_name",
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                modifire: ({id, name: value}) => ({id, value}),
            },
        }
    },
    watch: {
        async latestAddedVariantToStockAdjustment(newVariantId) {
            if (!newVariantId) return;
            if (this.formData.adjustmentVariants.some(lotVariant => lotVariant.variant_id === newVariantId)) return;
            const newAdjustmentVariant = await this.generateStockItem(newVariantId);
            this.formData.adjustmentVariants.push(newAdjustmentVariant);

            this.latestAddedVariantToStockAdjustment = '';
        },
        'formData.branch_or_warehouse_id': {
            immediate: true,
            handler: function (newID) {
                if (this.selectedUrl) return;
                if (!newID) return;
                this.formData.adjustmentVariants = [];
            }
        }
    },
    computed: {
        ...mapGetters(["getBranchOrWarehouseId"]),
        productOptions() {
            return {
                url: urlGenerator(`${SELECTABLE_STOCK_ADJUSTMENT_VARIANTS_WITH_BRANCH}`),
                query_name: "search",
                params: {
                    branch_or_warehouse_id: this.formData.branch_or_warehouse_id,
                },
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                modifire: (value) => ({value: value.variant.name, id: value.variant.id}),
            }
        },
        branchInputDisabled() {
            return Boolean(this.itemToAdjust);
        }
    },
    methods: {
        async setStockItemsFromProp() {
            const newAdjustmentVariant = await this.generateStockItem(this.itemToAdjust);
            this.formData.adjustmentVariants.push(newAdjustmentVariant);
        },
        formatDate(dateObj) {
            if (typeof dateObj === "string") return dateObj;
            const date = {year: dateObj.getFullYear(), month: dateObj.getMonth() + 1, day: dateObj.getDate()}
            return `${date.year}-${date.month <= 9 ? '0' : ''}${date.month}-${date.day <= 9 ? '0' : ''}${date.day}`;
        },
        async submit() {
            if (this.formData.branch_or_warehouse_id && !Boolean(this.formData.adjustmentVariants.length)) return this.$toastr.e('', this.$t('please_select_products_for_adjustments_first') + '!');
            this.loading = true;
            this.message = '';
            this.errors = {};
            const {data: reference_no} = await axiosGet(GENERATE_UPC);
            this.formData = {
                ...this.formData,
                reference_no: this.formData.reference_no ?? reference_no,
                adjustment_date: this.formData.adjustment_date ? this.formatDate(this.formData.adjustment_date) : ''
            };

            let formData = {...this.formData};
            const keysToIgnore = [];
            for (const key in formData) !formData[key] ? keysToIgnore.push(key) : '';

            for (const variant of formData.adjustmentVariants) {
                delete variant.created_by
                delete variant.created_at
                delete variant.updated_at
            }

            formData = formDataAssigner(new FormData, {...this.formData, created_by: window.user.id}, keysToIgnore);
            if (this.selectedUrl) formData.append('_method', 'patch');

            this.submitFromFixin(
                'post',
                this.selectedUrl ? this.selectedUrl : STOCK_ADJUSTMENTS,
                formData
            );
        },

        generateProductsProfileProp(item) {
            return {
                id: item.variant_id,
                variant: {
                    upc: item.upc,
                    name: item.product_name,
                }
            }
        },
        removeProductFromLot(variantId) {
            this.formData.adjustmentVariants = this.formData.adjustmentVariants.filter(item => item.variant_id !== variantId);
        },
        handleItemQuantityChange(index) {
            const variant = this.formData.adjustmentVariants[index];
            if (variant.unit_quantity === '') return;
            if (+variant.unit_quantity <= 0) variant.unit_quantity = 1;
        },
        incrementUnit(index) {
            const variantToUpdate = this.formData.adjustmentVariants[index];
            variantToUpdate.unit_quantity = +variantToUpdate.unit_quantity + 1;
        },
        decrementUnit(index) {
            const variantToUpdate = this.formData.adjustmentVariants[index];
            if (variantToUpdate.unit_quantity <= 1) return; // exiting the function if unit_quantity count is 1
            variantToUpdate.unit_quantity = +variantToUpdate.unit_quantity - 1;
        },
        async generateStockItem(variantId) {
            const variantData = await axiosGet(`${SINGLE_VARIANT}/${variantId}`);
            const variantStockData = await axiosGet(GET_AVAILABLE_STOCK(variantId, this.formData.branch_or_warehouse_id));
            const {data: {name, upc}} = variantData;

            const newAdjustmentItem = {
                product_name: name,
                adjustment_type: 'addition',
                upc,
                stock: variantStockData.data.available_qty,
                variant_id: variantId,
                unit_quantity: 1,
            };

            return {...newAdjustmentItem};
        },
        afterSuccess({data}) {
            this.formData = {};
            $('#stock-adjustment-create-edit-modal').modal('hide');
            this.$emit('input', false);
            this.toastAndReload(data.message, 'stock-table');
            this.$hub.$emit('reload-stock-adjustment-table');
        },

        async afterSuccessFromGetEditData({data}) {
            this.preloader = false;
            this.formData.expense_date = data.expense_date ? new Date(data.expense_date) : '';
            this.formData = data;
            const adjustmentVariants = data.adjustment_variants;
            delete this.formData.adjustment_variants;
            this.formData.adjustmentVariants = adjustmentVariants;
        },
        closeModal() {
            this.$emit('modal-close');
        }
    },
}
</script>

<style lang="sass" scoped>
.custom-disable
    opacity: 0.5
    pointer-events: none

</style>
