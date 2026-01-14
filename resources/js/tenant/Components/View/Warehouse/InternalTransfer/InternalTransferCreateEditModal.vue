<template>
    <modal id="internal-transfer-create-edit-modal"
           modal-id="internal-transfer-create-edit-modal"
           v-model="showModal"
           :loading="loading"
           :scrollable="false"
           :preloader="preloader"
           size="extra-large"
           :title="modalTitle ? modalTitle : generateModalTitle('internal_transfer')"
           @close-modal="closeModal"
           @submit="submit">

        <form
            ref="form"
            :class="`row ${ $isRtl ? 'text-left' : '' }`"
            :data-url='selectedUrl ? selectedUrl : INTERNAL_TRANSFER'
            enctype="multipart/form-data">
            
            <!-- warehouse from input -->
            <div class="form-element col-12 col-md-6 mb-4">
                <label>{{ $t('warehouse_/_store_from') }}</label>
                <app-input
                    :class="`${ !$isRtl ? 'margin-right-8' : 'margin-left-8' }`"
                    type="search-and-select"
                    :key="ssKey"
                    :placeholder="$placeholder('warehouse')"
                    :options="branchesOrWarehousesOptions"
                    :inputclearable="false"
                    v-model="formData.branch_or_warehouse_from_id"
                    :error-message="$errorMessage(errors, 'branch_or_warehouse_from_id')"
                />
            </div>

            <!-- warehouse to input -->
            <div class="form-element col-12 col-md-6 mb-4">
                <label>{{ $t('warehouse_/_store_to') }}</label>
                <app-input
                    :class="`${ !$isRtl ? 'margin-right-8' : 'margin-left-8' }`"
                    type="search-and-select"
                    :placeholder="$placeholder('warehouse')"
                    :options="branchesOrWarehousesOptions"
                    v-model="formData.branch_or_warehouse_to_id"
                    :error-message="$errorMessage(errors, 'branch_or_warehouse_to_id')"
                />
            </div>

            <!-- date input -->
            <div class="form-element col-12 col-md-6 mb-4">
                <label>{{ $t('date') }}</label>
                <app-input
                    :class="`${ !$isRtl ? 'margin-right-8' : 'margin-left-8' }`"
                    type="date"
                    :placeholder="$placeholder('date')"
                    v-model="formData.date"
                    :error-message="$errorMessage(errors, 'date')"
                />
            </div>

            <div class="form-element col-12 col-md-6 mb-4">
                <label>{{ $t('reference_no') }}</label>
                <app-input
                    :class="`${ !$isRtl ? 'margin-right-8' : 'margin-left-8' }`"
                    type="number"
                    :placeholder="$placeholder('reference_no')"
                    v-model="formData.reference_no"
                    :error-message="$errorMessage(errors, 'reference_no')"
                />
            </div>

            <!-- product input -->
            <div class="form-element col-12 mb-4 row no-gutters">
                <label class="col-12 col-md-6">{{ $t('select_product') }}</label>
                <app-input
                    class="col-12"
                    type="search-and-select"
                    :placeholder="$placeholder('product')"
                    :key="formData.branch_or_warehouse_from_id"
                    :options="productOptions"
                    v-model="product_id"
                />
            </div>

            <!-- products go here -->
            <div class="col-12 mb-2 row no-gutters align-items-center mt-2" v-if="formData.internalTransferVariants.length">
                <div class="labels col-12 row border-bottom no-gutters align-items-baseline">
                    <p class="text-muted col-md-4">{{ $t('product') }}</p>
                    <p :class="`text-muted col-md-2 align-items-center pr-5 ${ $isRtl ? 'text-left' : 'text-right' }`">{{ $t('stock') }}</p>
                    <p class="text-muted col-md-3">{{ $t('unit_quantity') }}</p>
                    <p class="text-muted col-md-2">{{ $t('lot_reference_no') }}*</p>
                    <p class="text-muted col-md-1 text-right">{{ $t('action') }}</p>
                </div>

                <div class="values col-12 row border-bottom no-gutters align-items-baseline py-2" v-for="(itemToTransfer, index) in formData.internalTransferVariants" :key="index">
                    <p class="text-primary col-md-4 my-1">{{ itemToTransfer.name }}</p>
                    <p :class="`col-md-2 pr-5 my-1 ${ $isRtl ? 'text-left' : 'text-right' }`">{{ itemToTransfer.stock }}</p>
                    <div class="col-md-3 d-flex align-items-baseline quantity-control" style="gap: 1.5rem">
                        <a @click.prevent="" class="text-primary text-decoration-none cursor-pointer"
                           @click="decrementUnit(index)">
                            <app-icon name="minus-circle"/>
                        </a>
                        <app-input
                            type="number"
                            class="count-input"
                            style="width: 5rem;"
                            v-model="itemToTransfer.unit_quantity"
                            @input="handleItemQuantityChange(index)"
                        />
                        <a @click.prevent="" class="text-primary text-decoration-none cursor-pointer"
                           @click="incrementUnit(index)">
                            <app-icon name="plus-circle"/>
                        </a>
                    </div>
                    <p class="col-md-2 my-1">
                        <app-input
                            type="text"
                            :placeholder="$placeholder('lot_reference_no')"
                            v-model="itemToTransfer.lot_reference_no"
                        />
                    </p>
                    <div class="col-md-1 text-right">
                        <button class="btn btn-option text-primary" @click.prevent="handleItemRemove(itemToTransfer.variant_id)">
                            <app-icon name="x-circle" class="text-primary size-18" />
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-12 mb-4 row no-gutters">
                <label class="col-12 col-md-6"> {{ $t('cost_for_transfer') }} </label>
                <app-input
                    type="number"
                    v-model="formData.total_transfer_cost"
                    :error-message="$errorMessage(errors, 'total_transfer_cost')"
                    class="col-md-12"
                    :placeholder="$placeholder('total_transfer_cost')"
                    :label="$t('total_transfer_cost')"
                />
            </div>

            <!-- note input -->
            <div class="form-element col-12 mb-4 no-gutters">
                <label class="col-md-6">{{ $t("note") }}</label>
                <app-input
                    :class="`${ !$isRtl ? 'margin-right-8' : 'margin-left-8' } col-12`"
                    type="textarea"
                    :placeholder="$placeholder('note')"
                    v-model="formData.note"
                />
            </div>
        </form>
    </modal>
</template>

<script>
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import ModalMixin from "../../../../../common/Mixin/Global/ModalMixin";
import {
    SELECTABLE_BRANCH,
    SELECTABLE_BRANCHES_OR_WAREHOUSES,
    SELECTABLE_RECEIVED_BY,
    INTERNAL_TRANSFER,
    SELECTABLE_STOCK_ADJUSTMENT_VARIANTS_WITH_BRANCH,
    GET_AVAILABLE_STOCK, GENERATE_UPC, SELECTABLE_INTERNAL_TRANSFER_STATUSES
} from "../../../../Config/ApiUrl-CP";
import {formDataAssigner} from "../../../../Helper/Helper";
import {axiosGet, urlGenerator} from "../../../../../common/Helper/AxiosHelper";
import StatusQueryMixin from "../../../../../common/Mixin/Global/StatusQueryMixin";
import { mapGetters } from "vuex";

export default {
    name: "InternalTransferCreateEditModal",
    mixins: [FormHelperMixins, ModalMixin, StatusQueryMixin],
    props: ['modal-title', 'variantId', 'selectedUrl'],
    async mounted() {
        const internalTransferStatus = await axiosGet(SELECTABLE_INTERNAL_TRANSFER_STATUSES);
        this.statusList = internalTransferStatus.data;
        this.formData.status_id = this.statusList[0].id;
        this.formData.branch_or_warehouse_from_id = this.getBranchOrWarehouseId;
        this.ssKey++;

        if (this.selectedUrl) return; // exiting a function
        this.generateReferenceNumber();
    },
    data() {
        return {
            showNote: true,
            ssKey: 0,
            product_id: this.variantId ? this.variantId : '',
            INTERNAL_TRANSFER,
            formData: {
                status_id: '1',
                total_transfer_cost: '0',
                reference_no: '',
                note: '',
                branch_or_warehouse_to_id: '',
                date: new Date(),
                branch_or_warehouse_from_id: '',
                internalTransferVariants: [
                
                ]
            },
            SELECTABLE_BRANCH,
            SELECTABLE_RECEIVED_BY,
            statusList: [], // gets populated from mounted
            branchesOrWarehousesOptions: {
                url: urlGenerator(SELECTABLE_BRANCHES_OR_WAREHOUSES),
                query_name: "search",
                per_page: 10,
                loader: "app-pre-loader",
                modifire: ({id, name: value}) => ({id, value}),
                prefetch: !Boolean(this.selectedUrl),
            },
        }
    },
    computed: {
        productOptions() {
            return {
                url: urlGenerator(`${SELECTABLE_STOCK_ADJUSTMENT_VARIANTS_WITH_BRANCH}`),
                query_name: "search",
                params: {
                    branch_or_warehouse_id:  this.formData.branch_or_warehouse_from_id
                },
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                prefetch: true,
                modifire: (value) => ({value: value.variant.name, id: value.variant.id}),
            }
        },
        ...mapGetters(['getBranchOrWarehouseId'])
    },
    watch: {
        async product_id(newVariantId) {
            if (!newVariantId) return;

            if (this.formData.internalTransferVariants.some(internalTransferVariant => internalTransferVariant.variant_id === newVariantId)) {
                const existingVariantIndex = this.formData.internalTransferVariants.findIndex(itv => itv.variant_id === newVariantId);
                return this.incrementUnit(existingVariantIndex);
            }

            const internalTransferVariant = await this.generateTransferItem(newVariantId);
            this.formData.internalTransferVariants.push({ ...internalTransferVariant, lot_reference_no: '' });
            this.product_id = '';
        },
    },
    methods: {
        async generateReferenceNumber() {
            const {data: reference_no} = await axiosGet(GENERATE_UPC);
            this.formData.reference_no = reference_no;
        },
        async generateTransferItem(variantId) {
            const variantDataResponseData = await axiosGet(`/app/variant/${variantId}`);
            const variantStockData = await axiosGet(GET_AVAILABLE_STOCK(variantId, this.formData.branch_or_warehouse_from_id));
            const variantData = variantDataResponseData.data;

            return {
                name: variantData.name,
                stock: variantStockData.data.available_qty,
                variant_id: variantId,
                unit_quantity: 1,
            };
        },
        handleItemRemove(variant_id) {
            this.formData.internalTransferVariants = this.formData.internalTransferVariants.filter(item => item.variant_id !== variant_id);
        },
        handleItemQuantityChange(index)  {
            const variant = this.formData.internalTransferVariants[index];
            if (variant.unit_quantity === '') return;
            if (+variant.unit_quantity <= 0) {
                variant.unit_quantity = 1;
                return;
            }
            if (+variant.unit_quantity > variant.stock) variant.unit_quantity = 1;
        },
        incrementUnit(index) {
            const variantToUpdate = this.formData.internalTransferVariants[index];
            if (+variantToUpdate.stock === +variantToUpdate.unit_quantity) return;
            variantToUpdate.unit_quantity = +variantToUpdate.unit_quantity + 1;
        },
        decrementUnit(index) {
            const variantToUpdate = this.formData.internalTransferVariants[index];
            if (variantToUpdate.unit_quantity <= 1) return; // exiting the function if unit_quantity count is 1
            variantToUpdate.unit_quantity = +variantToUpdate.unit_quantity - 1;
        },
        formatDate(dateObj) {
            if (typeof dateObj === 'string') return dateObj;
            const date = {year: dateObj.getFullYear(), month: dateObj.getMonth() + 1, day: dateObj.getDate()}
            return `${date.year}-${date.month <= 9 ? '0' : ''}${date.month}-${date.day <= 9 ? '0' : ''}${date.day}`;
        },
        async submit() {
            if (this.formData.branch_or_warehouse_from_id) {
                if (!Boolean(this.formData.internalTransferVariants.length)) {
                    return this.$toastr.e('', this.$t('please_select_products_to_transfer_first') + '!'); 
                }

                const itvWithNoReferenceNumber = this.formData.internalTransferVariants.find(itv => itv.lot_reference_no === '')
                if (itvWithNoReferenceNumber) {
                    return this.$toastr.e('', this.$t('please_input_reference_number_for_all_products') + '!'); 
                }
            }

            this.loading = true;
            this.message = '';
            this.errors = {};

            this.formData = {
                ...this.formData,
                date: this.formData.date ? this.formatDate(this.formData.date) : ''
            };

            let formData = {...this.formData};
            const keysToIgnore = [];
            for (const key in formData) !formData[key] ? keysToIgnore.push(key) : '';

         

            formData = formDataAssigner(new FormData, this.formData, keysToIgnore);
            if (this.selectedUrl) formData.append('_method', 'patch');

            this.submitFromFixin(
                'post',
                this.selectedUrl ? this.selectedUrl : INTERNAL_TRANSFER,
                formData
            );
        },
        afterSuccess({data}) {
            this.formData = {};
            $("#internal-transfer-create-edit-modal").modal('hide');
            this.$emit('input', false);
            this.toastAndReload(data.message, 'internal-transfer-table');
        },
        afterError(response) {
            this.message = '';
            this.loading = false;
            if (this.preloader) this.preloader = false;
            this.errors = response.data.errors || {};
            if (response.status != 422)
                this.$toastr.e(response.data.message || response.statusText)
        },
        afterSuccessFromGetEditData({data}) {
            this.preloader = false;
            this.formData.expense_date = data.expense_date ? new Date(data.expense_date) : '';
            data.internalTransferVariants = data.internal_transfer_variants.map(itv => ({...itv, ...itv.variant, stock: itv.variant.stock.available_qty, id: itv.id}));
            delete data.internal_transfer_variants;
            this.formData = data;
            //show attachment in dropzone
            this.formData.attachments = _.map(this.formData.attachments, 'path');
        },
        closeModal() {
            this.$emit('modal-close');
        }
    },
}
</script>
