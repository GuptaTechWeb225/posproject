<template>
    <div class="row no-gutters col-12 align-items-center px-0">
        <div :class="`row ${viewMode === 'primary' ? 'no-gutters' : 'card card-with-shadow'} col-12 mx-0`">
            <div id="sub-total"
                 :class="`row no-gutters col-12 align-items-center justify-content-between pb-3 ${viewMode === 'primary' ? 'pt-5' : 'pt-3 pb-2'}`">
                <div class="row col-12 no-gutters justify-content-between border-bottom">
                    <h5>{{ $t('sub_total') }}</h5>
                    <h5>{{ numberWithCurrencySymbol(subtotal) }}</h5>
                </div>
            </div>
            <div
                :class="`sub-total-discount row no-gutters col-12 align-items-center justify-content-between ${ showDiscountOnSubtotalInput && 'py-2' }`">
                <div :class="`row no-gutters col-12 pb-2 ${!discountDropdownActive ? 'border-bottom' : ''} justify-content-between align-items-center`">
                    <div class="d-flex align-items-center justify-content-start">
                        <div :class="`round-btn ${darkThemeActive ? 'dark-bg' : 'light-bg'}`"
                             @click="handleDiscountDropdownBtnClick" :key="discountDropdownActive">
                            <app-icon :key="discountDropdownActive"
                                      :name="discountDropdownActive ? 'chevron-up' : 'chevron-down'"/>
                        </div>
                        <span class="mb-0 text-muted cursor-pointer" @click="handleDiscountOnSubtotalClick">
                            {{ $t('discount_on_subtotal') + ` ${subtotalDiscountType === 'percentage' ? '(' + discount_on_subtotal + ' %)' : '(' + getCurrencySymbol + ')'}` }}
                            <small class="text-primary" v-if="getFlatDiscountActiveStatus"> <br> {{ getSubtotalDiscountName }} </small>
                        </span>
                    </div>

                    <p class="cursor-pointer custom-margin pb-0" v-show="!showDiscountOnSubtotalInput" style="padding: 10px 0"
                       @click="handleDiscountOnSubtotalClick"> - {{ numberWithCurrencySymbol(totalDiscountOnProducts || 0) }} </p>

                    <app-input
                        v-show="showDiscountOnSubtotalInput"
                        @mouseout="showDiscountOnSubtotalInput = false"
                        type="number"
                        class="arrows-hidden text-right"
                        :placeholder="$placeholder('discount')"
                        @keyup="handleSubtotalDiscountInput"
                        :disabled="getFlatDiscountActiveStatus"
                        :key="subtotalDiscountType"
                        v-model="discount_on_subtotal"
                    />

                </div>

                <div class="discount-type-input d-flex align-items-baseline justify-content-between py-2 col-12 border-bottom"
                     v-if="discountDropdownActive">
                    <label>{{ $t('discount_type') }}:</label>
                    <app-input
                        type="radio"
                        :disabled="getFlatDiscountActiveStatus"
                        :required="true"
                        @input="handleDiscountTypeRadioInput"
                        v-model="subtotalDiscountType"
                        :list="discountTypes"
                    />
                </div>
            </div>
            <div class="tax row no-gutters col-12 align-items-center justify-content-between py-2">
                <div class="border-bottom row no-gutters col-12 justify-content-between align-items-baseline pb-2">
                    <p v-show="!showTaxDropdown" class="cursor-pointer custom-margin pb-0" @click="handleTaxNameClick">{{ selectedTaxName }} <span v-show="Boolean(selectedTaxName)">({{getTaxData?.percentage + '%'}})</span></p>
                    <app-input
                        v-show="showTaxDropdown"
                        type="select"
                        :placeholder="$t('select_tax')"
                        v-if="taxList.length"
                        :list="taxList"
                        list-value-field="name"
                        style="width: 48%"
                        @mouseout="showTaxDropdown = false"
                        @input="handleTaxIdInput"
                        v-model="tax_id"
                    />
                    <p class="mb-0 text-right pb-0" style="width: 40%">+ {{ numberWithCurrencySymbol(getTaxAmount) }}</p>
                </div>
            </div>

            <div v-if="viewMode !== 'primary'"
                 class="row no-gutters col-12 align-items-center justify-content-between pt-3 pb-2">
                <div class="row col-12 no-gutters justify-content-between">
                    <h5 class="text-uppercase">{{ $t('total') }}</h5>
                    <h5> {{ numberWithCurrencySymbol(getGrandTotal) }}</h5>
                </div>
            </div>
        </div>

        <invoice-summary v-if="viewMode === 'secondary'"/>
    </div>
</template>

<script>
import CartControl from './CartControl';
import InvoiceSummary from './InvoiceSummary';
import {mapGetters, mapMutations} from 'vuex';
import {DISCOUNT_INFO} from "../../../../Config/ApiUrl-CP";
import {axiosGet, urlGenerator} from "../../../../../common/Helper/AxiosHelper";
import {ALL_TAX} from "../../../../Config/ApiUrl-CPB";
import {getCurrencySymbol} from "../../../../Helper/Helper";
import {numberWithCurrencySymbol} from "../../../../Helper/Helper";

export default {
    name: 'cart-details',
    components: {
        'cart-control': CartControl,
        'invoice-summary': InvoiceSummary,
    },
    mounted() {
        this.setTaxData();
        this.SET_DISCOUNT_ON_SUBTOTAL_TYPE(this.getSubtotalDiscountType);
    },
    data() {
        return {
            showDiscountOnSubtotalInput: false,
            showTaxDropdown: false,
            discountTypeRadioKey: 0,
            discountDropdownActive: false,
            discountTypes: [
                {id: "percentage", value: this.$t('percentage')},
                {id: "fixed", value: this.$t('fixed')},
            ],
            discount_on_subtotal: 0,
            subtotalDiscountType: 'percentage',
            tax_id: '',
            taxInputKey: 0,
            taxList: [],
        };
    },
    computed: {
        ...mapGetters([
            'viewMode',
            'subtotal',
            'getBranchOrWarehouseId',
            'getGrandTotal',
            'getTotalTax',
            'getTaxAmount',
            'getOrderResumeState',
            'getTaxId',
            'getTaxData',
            'getDiscountOnSubtotal',
            'getSubtotalDiscountType',
            'getSubtotalDiscountName',
            'getFlatDiscountActiveStatus',
            'getDiscountValue',
            'numberOfHeldOrders'
        ]),
        darkThemeActive() {
            return this.$store.state.theme.darkMode;
        },
        selectedTaxName() {
            if (!this.taxList.length) return '';
            const selectedTax = this.taxList.find(tax => +tax.id === +this.tax_id)
            if (selectedTax) return selectedTax.name;
        },
        totalDiscountOnProducts() { // required for watcher
            if (+this.subtotal === 0) return 0;
            if (this.subtotalDiscountType === 'fixed') return this.getDiscountValue;
            return this.subtotal * (this.getDiscountValue / 100);
        },
        grandTotal() {
            const discountTotalCalculated = this.subtotalDiscountType === 'percentage' ? this.subtotal - (this.subtotal * (this.discount_on_subtotal / 100)) : this.subtotal - this.discount_on_subtotal;
            const flatTax = this.getTaxAmount;
            if (!flatTax) return discountTotalCalculated;
            return (discountTotalCalculated + +flatTax).toFixed(2);
        },
        getCurrencySymbol() {
            return getCurrencySymbol();
        },
        amountAfterSubtotalDeduction() {
            return this.subtotal - this.totalDiscountOnProducts;
        }
    },
    watch: {
        amountAfterSubtotalDeduction: {
            immediate: true,
            handler() {
                if (this.getTaxId)  {
                    if (+this.getTaxId !== +this.tax_id) this.tax_id = this.getTaxId;
                }
                this.handleTaxIdInput();
            }
        },
        getBranchOrWarehouseId: {
            immediate: true,
            handler(newId) {
                if (!newId) return;
                this.getSubtotalDiscountData();
            }
        },
        numberOfHeldOrders(newCount, oldCount = 0) {
            if (newCount > oldCount) this.discount_on_subtotal = 0; // resetting the input value everytime a order is being held
        },
        getOrderResumeState() {
            this.tax_id = this.getTaxId;
            this.taxInputKey++;
            this.discount_on_subtotal = this.getDiscountValue;
            this.subtotalDiscountType = this.getSubtotalDiscountType === "flat" ? "fixed" : "percentage";

            this.SET_ORDER_RESUME_STATE(false);
        },
        grandTotal(newGrandTotal) {
            this.SET_GRAND_TOTAL(newGrandTotal);
        },
        subtotal() {
            this.setTaxData();
        },
        getTaxData(newTaxData) {
            if (newTaxData !== null) return;
            this.tax_id = '';
            this.taxInputKey++;
        },
        totalDiscountOnProducts: {
            immediate: true,
            handler(newValue) {
                this.SET_DISCOUNT_ON_SUBTOTAL(newValue)
            }
        },
        getDiscountValue: {
            immediate: true,
            handler(newValue) {
                this.discount_on_subtotal = newValue
            }
        }
    },
    methods: {
        handleTaxNameClick() {
            this.showTaxDropdown = true
        },
        handleDiscountOnSubtotalClick() {
            this.showDiscountOnSubtotalInput = true;
        },
        handleTaxIdInput() {
            const selectedTax = this.taxList.find(td => +td.id === +this.tax_id);

            if (!selectedTax) return;
            const amountAfterDiscountDeduction = this.subtotal - this.totalDiscountOnProducts;
            const calculatedAmountFromPercentage = selectedTax.percentage / 100;
            const totalTax = amountAfterDiscountDeduction * calculatedAmountFromPercentage;

            this.SET_TAX_DATA(selectedTax);
            this.SET_TAX_AMOUNT(totalTax);
        },
        ...mapMutations([
            'SET_DISCOUNT_ON_SUBTOTAL',
            'SET_DISCOUNT_ON_SUBTOTAL_TYPE',
            'SET_DISCOUNT_ON_SUBTOTAL_NAME',
            'SET_GRAND_TOTAL',
            'SET_TAX_DATA',
            'SET_TAX_AMOUNT',
            'SET_FLAT_DISCOUNT_ACTIVE_STATUS',
            'SET_DISCOUNT_VALUE',
            'SET_ORDER_RESUME_STATE'
        ]),
        handleDiscountTypeRadioInput() {
            this.discount_on_subtotal = 0;
            this.SET_DISCOUNT_ON_SUBTOTAL_TYPE(this.subtotalDiscountType);
            this.SET_DISCOUNT_VALUE(0);
            this.SET_DISCOUNT_ON_SUBTOTAL(0);

        },
        async getSubtotalDiscountData() {
            try {
                const {data: {discount_on_sub_total: discountData}} = await axiosGet(DISCOUNT_INFO + `?branch_or_warehouse_id=${this.getBranchOrWarehouseId}`);
                this.SET_FLAT_DISCOUNT_ACTIVE_STATUS(Boolean(discountData));
                if (discountData) {
                    const {value, type, name} = discountData;
                    this.SET_DISCOUNT_VALUE(value);
                    this.SET_DISCOUNT_ON_SUBTOTAL_TYPE(type);
                    this.SET_DISCOUNT_ON_SUBTOTAL_NAME(name);
                }
            } catch (e) {
                this.$toastr.e(e);
            }

            this.discount_on_subtotal = this.getDiscountValue;
            this.subtotalDiscountType = this.getSubtotalDiscountType;
        },
        numberWithCurrencySymbol(value) {
            return numberWithCurrencySymbol(value);
        },
        async setTaxData() {
            try {
                const { data: taxList } = await axiosGet(ALL_TAX);
                this.taxList = taxList;
                if (!this.getTaxId) return this.setDefaultTax();
                this.tax_id = this.getTaxId;
                this.handleTaxIdInput();
            } catch (e) {
                this.$toastr.e(e)
            }
        },
        handleSubtotalDiscountInput() {
            if (+`${this.discount_on_subtotal}`[0] === 0) this.discount_on_subtotal = +`${this.discount_on_subtotal}`.slice(1);

            if (this.subtotalDiscountType === 'percentage') {
                if (this.discount_on_subtotal >= 100) this.discount_on_subtotal = 100;
                if (this.discount_on_subtotal !== '' && this.discount_on_subtotal <= 1) this.discount_on_subtotal = 1;
            } else {
                if (this.discount_on_subtotal >= this.subtotal) this.discount_on_subtotal = this.subtotal;
                if (this.discount_on_subtotal <= 0) this.discount_on_subtotal = 0;
            }

            this.SET_DISCOUNT_VALUE(+this.discount_on_subtotal);
        },
        setDefaultTax() {
            if (!this.taxList.length) return;
            const defaultTax = this.taxList.find(tax => +tax.is_default === 1);
            if (!Object.keys(defaultTax).length)  {
                alert(this.$t('please_set_a_default_tax_from_settings'));
                window.location.replace(urlGenerator('/dashboard')); // redirecting to dashboard and not the tax management page due to permission issues
            }
            this.tax_id = defaultTax.id;
            this.handleTaxIdInput();
        },
        handleDiscountDropdownBtnClick() {
            this.discountDropdownActive = !this.discountDropdownActive;
        },
    },
};
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

.dark-bg
    background-color: #3b3d43

.light-bg
    background-color: #f9f9f9

#sub-total > *
    font-size: 16px

.custom-margin
    margin: 12px 0
</style>
