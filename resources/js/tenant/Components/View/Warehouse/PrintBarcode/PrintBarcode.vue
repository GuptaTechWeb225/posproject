<template>
    <div class="content-wrapper">
        <div class="d-flex align-items-center justify-content-between">
            <app-breadcrumb :page-title="$t('print_barcode')"/>
        </div>

        <form ref="form" :data-url='selectedUrl ? selectedUrl : ""' :class="`${ $isRtl ? 'text-left' : '' }`">
            <div class="row mb-4">
                <label class="col-12">{{ $t('select_lot') }}</label>
                <app-input
                    class="col-12"
                    type="search-and-select"
                    :placeholder="$placeholder('lot_reference_number')"
                    :options="lotOptions"
                    v-model="formData.lot_id"
                />
            </div>
            <div :class="`${!Boolean(formData.lot_id) && 'custom-disable'}`">
                <div class="row">
                    <label class="col-12">{{ $t('select_product') }}</label>
                    <app-input
                        class="col-12"
                        type="search-and-select"
                        :placeholder="$placeholder('product')"
                        :key="formData.lot_id"
                        :options="lotVariants"
                        v-model="formData.lot_variant_id"
                    />
                </div>

                <div class="my-4 card card-with-shadow"
                     v-if="itemToPrint !== null && Boolean(Object.keys(itemToPrint).length)">
                    <div class="col-12 mb-4 row no-gutters align-items-center mt-2">
                        <div class="labels col-12 row border-bottom no-gutters align-items-baseline">
                            <p class="text-muted col-md-4">{{ $t('product') }}</p>
                            <p class="text-muted col-md-2 align-items-center">{{ $t('code') }}</p>
                            <p class="text-muted col-md-2">{{ $t('reference_no') }}</p>
                            <p class="text-muted col-md-4">{{ $t('quantity') }}</p>
                            <!-- <p class="text-muted col-md-1 text-right">{{ $t('action') }}</p> -->
                        </div>
                    </div>

                    <div class="values row col-12 no-gutters align-items-baseline">
                        <p class="text-primary col-md-4">{{ itemToPrint.name }}</p>
                        <p class="col-md-2">{{ itemToPrint.upc }}</p>
                        <p class="col-md-2">{{ itemToPrint.reference_no }}</p>
                        <div class="col-md-4 d-flex align-items-baseline quantity-control">
                            <a @click.prevent="" class="text-primary text-decoration-none cursor-pointer"
                               @click="decrementQuantity">
                                <app-icon name="minus-circle"/>
                            </a>
                            <p class="text-center d-inline-block">{{ itemToPrint.quantity }}</p>
                            <a @click.prevent="" class="text-primary text-decoration-none cursor-pointer"
                               @click="incrementQuantity">
                                <app-icon name="plus-circle"/>
                            </a>
                        </div>
                        <!-- <div class="col-md-1 text-right"> -->
                        <!--     <button @click.prevent="" class="btn btn-option text-primary border"> -->
                        <!--         <app-icon name="more-vertical" class="size-16"/> -->
                        <!--     </button> -->
                        <!-- </div> -->
                    </div>

                </div>

                <div class="row my-4">
                    <label class="col-12">{{ $t('page_style') }}</label>
                    <app-input
                        class="col-12"
                        :disabled="true"
                        type="text"
                        v-model="formData.page_style"
                    />
                </div>

                <div
                    :class="`${!Boolean(itemToPrint !== null && Boolean(Object.keys(itemToPrint).length)) && 'custom-disable'}`">
                    <fieldset class="product-details mb-3">
                        <h4 class="text-decoration-underline">{{ $t('print') }}</h4>
                        <hr/>

                        <div class="d-flex justify-content-between align-items-baseline">
                            <div class="d-flex align-items-baseline">
                                <div class="d-flex align-items-baseline mr-3">
                                    <button :key="formData.labels.reference_no ? 'check-square' : 'square'"
                                            @click="formData.labels.reference_no = !formData.labels.reference_no"
                                            class="btn default-base-color p-2 padding-x-10 border mr-2">
                                        <app-icon
                                            class="size-19 primary-text-color"
                                            :name="formData.labels.reference_no ? 'check-square' : 'square'"
                                        />
                                    </button>
                                    <p>{{ $t('reference_no') }}</p>
                                </div>
                            </div>
                            <div class="preview-control mt-3" v-if="barcodePreview">
                                <button class="btn btn-secondary mr-4" @click.prevent="barcodePreview = false">
                                    {{ $t('cancel') }}
                                </button>
                                <button class="btn btn-primary" @click.prevent="handleBarcodePrint">{{
                                        $t('print')
                                    }}
                                </button>
                            </div>
                        </div>
                    </fieldset>

                    <button class="btn btn-primary" v-if="!barcodePreview"
                            @click.prevent="handleGenerateBarcodeBtnClick">
                        {{ $t("generate_barcode") }}
                    </button>

                    <template v-else>
                        <div class="barcode-preview border" id="section-to-print" ref="barcodePreview">
                            <div class="d-flex flex-wrap" style="gap: 1rem">
                                <div class="barcode-wrapper border d-flex flex-column p-4"
                                     v-for="(barcode, idx) in itemToPrint.quantity">
                                    <p id="reference-no"> {{ $t('ref') }}: {{ itemToPrint.reference_no }} </p>
                                    <div v-if="Boolean(barcodeResponse)" v-html="barcodeResponse"></div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </form>
        <PrintAria v-if="printAria" v-show="false" id="tharmal-80mm" css="css/invoice/80mm.css"
                   @close="printAria = false">
            <div v-html="contentHtml"></div>
        </PrintAria>
    </div>
</template>

<script>
import HelperMixin from "../../../../../common/Mixin/Global/HelperMixin";
import {GENERATE_BARCODE, LOT, SELECTABLE_LOT, SELECTABLE_LOT_VARIANTS} from "../../../../Config/ApiUrl-CP";
import DatatableHelperMixin from "../../../../../common/Mixin/Global/DatatableHelperMixin";
import {axiosPost, urlGenerator} from "../../../../../common/Helper/AxiosHelper";
import {FormMixin} from "../../../../../core/mixins/form/FormMixin";
import PrintAria from "../../../../../common/Components/Helper/PrintAria";
import {purifyForPrint} from "../../../../Helper/Purifier/HTMLPurifyHelper";

export default {
    name: 'PrintBarcode',
    mixins: [DatatableHelperMixin, HelperMixin, FormMixin],
    components: {PrintAria},
    data() {
        return {
            purifyForPrint,
            printAria: false,
            contentHtml: '',
            barcodePreview: false,
            barcodeResponse: '',
            productDetailsList: [
                {id: 1, value: this.$t('product_name')},
                {id: 2, value: this.$t('upc')},
                {id: 3, value: this.$t('reference_no')},
            ],
            lotVariantData: [],
            referenceNumbers: [],
            itemToPrint: {},
            formData: {
                lot_id: '',
                lot_variant_id: '',
                product_details: [],
                labels: {product_name: true, reference_no: true, upc: true},
                page_style: 'A4',
            },
            lotOptions: {
                url: urlGenerator(SELECTABLE_LOT),
                query_name: "search_by_lot_reference_no",
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                listValueField: 'reference_no',
                modifire: (value) => {
                    this.referenceNumbers.push({lot_id: value.id, reference_no: value.reference_no});
                    return {
                        id: value.id,
                        value: value.reference_no
                    };
                },
            },
        }
    },
    watch: {
        'formData.lot_id'(newId) {
            if (!this.lotVariantData.length) return;
            this.lotVariantData = [];
            this.itemToPrint = {};
            this.formData.lot_variant_id = '';
        },
        'formData.lot_variant_id'(newId) {
            if (!this.formData.lot_variant_id) {
                this.itemToPrint = {};
                return;
            }
            const selectedLotVariant = this.lotVariantData.filter(lv => lv.id === newId);
            this.addItemToList(selectedLotVariant);
        }
    },
    computed: {
        lotVariants() {
            return {
                url: urlGenerator(`${SELECTABLE_LOT_VARIANTS}?lot_id=${this.formData.lot_id}`),
                query_name: "search_by_name",
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                modifire: (value) => {
                    this.lotVariantData.push(value);
                    return {id: value.id, lot_id: value.lot_id, value: value.variant.name}
                },
            }
        },
    },
    methods: {
        generateBarcode(barcode, reference_no, labelText, barcodeCount) {
            const barcodeWrapper = (`
                <div class="barcode-wrapper" style="border: 1px solid #999; display: flex; flex-direction: column; padding: 1.5rem;">
                    <p id="reference-no"> ${labelText}: ${reference_no} </p>
                    ${barcode}
                </div>
            `)
            const barcodePrint = new Array(barcodeCount).fill(null).map(() => barcodeWrapper).join(''); // creating an array of barcodeWrappers
            return `
                <div class="barcode-preview border">
                    <div class="d-flex flex-wrap" style="display: flex; flex-wrap: wrap; gap: 1rem;">
                        ${barcodePrint}
                    </div>
                </div>
            `
        },
        handleBarcodePrint() {
            this.contentHtml = this.generateBarcode(
                this.barcodeResponse,
                this.purifyForPrint(this.itemToPrint.reference_no),
                this.$t('ref'),
                this.itemToPrint.quantity,
            );
            this.printAria = true;
        },
        async handleGenerateBarcodeBtnClick() {
            const barcodeApiResponse = await axiosPost(GENERATE_BARCODE, {
                upc: this.itemToPrint.upc,
            });
            this.barcodeResponse = barcodeApiResponse.data;
            this.barcodePreview = true;
        },
        incrementQuantity() {
            this.itemToPrint.quantity = this.itemToPrint.quantity + 1;
        },
        decrementQuantity() {
            if (this.itemToPrint.quantity <= 1) return; // exiting the function if unit_quantity count is 1
            this.itemToPrint.quantity = this.itemToPrint.quantity - 1;
        },
        addItemToList(itemData) {
            const [item] = itemData;
            this.itemToPrint = {
                name: item.variant.name,
                id: item.variant.id,
                upc: item.variant.upc,
                quantity: 1,
                reference_no: this.referenceNumbers.find(rn => item.lot_id === rn.lot_id).reference_no
            };
        },
        triggerActions(row, action, active) {
            if (action.title === this.$t("edit")) {
                this.selectedUrl = `${LOT}/${row.id}`;
                this.isModalActive = true;
            } else {
                this.getAction(row, action, active)
            }
        },
        openModal() {
            this.isModalActive = true;
            this.selectedUrl = ''
        },
    }
}
</script>

<style lang="sass" scoped>
.barcode
    font-size: 0
    position: relative
    width: 11.5rem
    background: #fff

.quantity-control
    gap: 2.5rem

.custom-disable
    opacity: 0.5
    pointer-events: none

.barcode-preview
    $divideBy: 0.5
    height: 3508px * $divideBy
    width: 2480px * $divideBy
    background: #fff
    padding: 1rem
</style>
