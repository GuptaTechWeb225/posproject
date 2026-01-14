<template>
    <div
        :class="`row no-gutters col-12 border-bottom py-3 align-items-center ${ viewMode !== 'primary' ? 'default-base-color' : 'px-0' }`">
        <selected-product :product="item" :discountAmount="productDiscount" @dropdown-toggle="handleDropdownToggle"
                          :dropdown-state="item.dropdownActive"/>
        <count-control :item-upc="item.upc" :quantity="item.quantity" @quantity-change="handleQuantityChangeEvent"/>
        <h6 class="col-3 mb-0 item-subtotal">{{ numberWithCurrencySymbol(individualItemTotalPrice) }}</h6>
        <div class="col-1 item-remove-btn text-right" @click="handleCartItemClose">
            <app-icon name="x-circle" class="text-danger size-18"/>
        </div>
        <small class="text-danger py-2" v-if="stockAmountExceeded">{{$t('specified_quantity_unavailable_in_stock')}}</small>
        <div v-if="item.dropdownActive" class="product-details-dropdown col-12 row no-gutters pt-3">
            <div class="row col-12 no-gutters justify-content-between pb-2">
                <app-input
                    type="number"
                    class="col-6 pr-3"
                    :placeholder="$placeholder('price')"
                    @keyup="handleCartItemPriceChange"
                    v-model="productPrice"
                    :min-number="1"
                />
                <div class="col-6 row no-gutters">
                    <app-input
                        type="number"
                        class="col-9 pr-2"
                        :disabled="getFlatDiscountActiveStatus"
                        :placeholder="$placeholder('discount')"
                        @keyup="handleCartItemDiscountChange"
                        :key="productDiscountType"
                        v-model="productDiscount"
                    />

                    <app-input
                        type="select"
                        class="col-3"
                        :list="discountTypes"
                        :disabled="getFlatDiscountActiveStatus"
                        v-model="productDiscountType"
                        list-value-field="type"
                    />
                </div>
            </div>
            <div class="row no-gutters col-12 py-2">
                <app-input
                    type="textarea"
                    class="d-block col-12"
                    :text-area-rows="5"
                    :placeholder="$placeholder('note')"
                    v-model="productNote"
                />
            </div>
        </div>
    </div>
</template>

<script>
import SelectedProduct from "./SelectedProduct";
import CountControl from "./CountControl";
import {mapMutations, mapGetters} from "vuex";
import {numberWithCurrencySymbol} from "../../../../Helper/Helper";

export default {
    name: 'cart-item',
    props: {
        item: {
            type: Object,
            required: true
        }
    },
    components: {
        'selected-product': SelectedProduct,
        'count-control': CountControl
    },
    data() {
        return {
            stockAmountExceeded: false,
            discountTypes: [
                {id: 'fixed', type: '$'},
                {id: 'percentage', type: '%'}
            ],
            productPrice: +this.item.price || +this.item.variant.selling_price,
            productDiscount: +this.item.discount_value || '',
            productDiscountType: this.item.discount_type,
            productDetailsDropdownActive: false,
            productNote: this.item.note ? this.item.note : '',
        }
    },
    computed: {
        ...mapGetters(['cartItems', 'viewMode', 'getOrderResumeState', 'getFlatDiscountActiveStatus']),
        discountAmount() {
            return this.productDiscountType === 'percentage' ? +(this.productPrice * +(+this.productDiscount / 100)).toFixed(2) : +this.productDiscount;
        },
        individualItemTotalPrice() {
            const unitTaxAmount = (this.item.tax_amount / 100) * (+this.productPrice - +this.discountAmount.toFixed(2));
            const discountAmountDeducted = +(+this.item.quantity * (+this.productPrice - +this.discountAmount.toFixed(2)));
            return (discountAmountDeducted + (unitTaxAmount * +this.item.quantity)).toFixed(2);
        }
    },
    watch: {
        individualItemTotalPrice: {
            immediate: true,
            handler: function (newPrice) {
                this.calculatePrices(newPrice);
            }
        },
        'cartItems.length'() {
            this.setSubTotalOnState();
        },
        productDiscountType() {
            this.productDiscount = '';
        },
        productDiscount() {
            this.SET_ITEM_DISCOUNT_DETAILS({
                upc: this.item.upc,
                discount_type: this.productDiscountType,
                discount_value: this.productDiscount,
                discount_amount: this.discountAmount
            });
        },
        productNote(newNote) {
            this.SET_ITEM_NOTE({upc: this.item.upc.toString(), note: newNote});
        },
        productPrice: {
            immediate: true,
            handler: function (newPrice) {
                this.CHANGE_CART_ITEM_PRICE({upc: this.item.upc.toString(), newPrice});
                this.SET_ITEM_DISCOUNT_DETAILS({
                    upc: this.item.upc,
                    discount_type: this.productDiscountType,
                    discount_value: this.productDiscount,
                    discount_amount: this.discountAmount
                });
            }
        }
    },
    methods: {
        handleQuantityChangeEvent(newQuantity) {
            const stockAmountExceeded = newQuantity > this.item.stock.available_qty;
            if (stockAmountExceeded) this.$toastr.e(this.$t('stock_out_message', {product_name: this.item.name, available_qty: this.item.stock.available_qty}));
        },
        numberWithCurrencySymbol(value) {
            return numberWithCurrencySymbol(value);
        },
        calculatePrices(newPrice) {
            this.CHANGE_CART_ITEM_SELLING_PRICE({upc: this.item.upc , new_product_price: newPrice});
            this.setSubTotalOnState();
        },
        setSubTotalOnState() {
            const subTotal = this.cartItems.map(item => +item.sub_total).reduce((a, v) => a + v, 0);
            this.SET_SUBTOTAL(subTotal);
        },
        ...mapMutations(['REMOVE_ITEM_FROM_CART', 'CHANGE_CART_ITEM_SELLING_PRICE', 'CHANGE_CART_ITEM_PRICE', 'SET_ITEM_DISCOUNT_DETAILS', 'SET_ITEM_NOTE', 'TOGGLE_CART_ITEM_DROPDOWN', 'SET_SUBTOTAL']),
        handleCartItemClose() {
            this.REMOVE_ITEM_FROM_CART(this.item.upc);
        },
        handleDropdownToggle() {
            this.TOGGLE_CART_ITEM_DROPDOWN({cartItemUpc: this.item.upc.toString(), show: !this.item.dropdownActive})
        },
        handleCartItemDiscountChange() {
            if (+`${this.productDiscount}`[0] === 0) this.productDiscount = +`${this.productDiscount}`.slice(1);

            if (this.productDiscountType === 'percentage') {
                if (this.productDiscount >= 100) this.productDiscount = 100;
                if (this.productDiscount !== '' && this.productDiscount <= 1) this.productDiscount = 1;
            } else {
                if (this.productDiscount >= this.productPrice) this.productDiscount = this.productPrice;
                if (this.productDiscount <= 0) this.productDiscount = 0;
            }
        },
        handleCartItemPriceChange() {
            if (this.productPrice <= 0) this.productPrice = 1;
        }
    }
}
</script>

<style lang="sass">
.item-remove-btn
    transition: 150ms
    cursor: pointer

.item-subtotal
    font-size: 12px
</style>
