<template>
    <div :class="`${ viewMode === 'primary' ? 'card card-with-shadow pr-2 col-12 mb-4 row align-items-center no-gutters' : ''}`">
        <div class="labels col-12 row border-bottom no-gutters py-2 mb-0" v-if="viewMode === 'primary'">
            <small class="col-4">{{ $t('selected_product') }}</small>
            <small class="col-4">{{ $t('quantity') }}</small>
            <small class="col-3">{{ $t('sub_total') }}</small>
            <small class="col-1 text-right"></small>
        </div>

        <div :class="`col-12 ${viewMode === 'primary' ? ($isRtl ? 'pl-2' : 'pr-2') + ' cart-item-display' : 'px-0'}`">
            <template v-if="viewMode === 'primary'">
                <div class="empty-cart-message d-flex flex-column align-items-center justify-content-around"
                     v-if="!cartItems.length">
                    <div class="mb-5">
                        <app-icon name="shopping-cart" class="text-primary" style="transform: scale(3)"/>
                    </div>
                    <h4 class="text-muted">{{ $t('no_items_to_show') }}</h4>
                </div>
                <cart-item v-for="(item, i) in cartItems" :key="i" :item="item"/>
            </template>
            <cart-details />
        </div>
        <cart-control :viewMode="viewMode"/>
    </div>
</template>

<script>
import CartItem from "./CartItem";
import {mapGetters} from "vuex";
import CartDetails from "./CartDetails";
import CartControl from "./CartControl";

export default {
    name: "app-cart",
    components: {'cart-item': CartItem, 'cart-details': CartDetails, 'cart-control': CartControl},
    computed: {
        ...mapGetters(['cartItems', 'viewMode', 'subtotal']),
    }
}
</script>

<style lang="sass">
$height: 50vh

.cart-item-display
    min-height: $height
    max-height: $height
    overflow-y: scroll
    overflow-x: hidden
    padding-bottom: 0.5rem
    -ms-overflow-style: none
    scrollbar-width: 0.25vw
    scrollbar-color: #4466F255 #99999900

    &::-webkit-scrollbar
        width: 0.25vw

    &::-webkit-scrollbar-track
        background-color: #99999900

    &::-webkit-scrollbar-thumb
        background-color: #4466F255
        border-radius: 50px


.empty-cart-message
    margin-top: 20%
</style>
