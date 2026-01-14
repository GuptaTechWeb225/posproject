<template>
    <div class="col-4 d-flex align-items-baseline" style="gap: 0.75rem">
        <a @click.prevent="decrementCount"
           class="text-primary text-decoration-none cursor-pointer">
            <app-icon name="minus-circle"/>
        </a>

        <template>
            <p @click="handleCountClick" v-if="!showInput" class="text-center cursor-pointer pt-3">
                {{ count }}
            </p>
            <app-input
                @mouseout="showInput = false"
                v-else
                type="number"
                class="count-input arrows-hidden"
                v-model="count"
                @keyup="handleCountControlKeyUp"
            />
        </template>

        <a @click.prevent="incrementCount"
           class="text-primary text-decoration-none" style="cursor: pointer;">
            <app-icon name="plus-circle"/>
        </a>
    </div>
</template>

<script>
import {mapActions, mapMutations} from "vuex";

export default {
    name: 'count-control',
    props: ['itemUpc', 'quantity'],
    components: {},
    data() {
        return {
            count: this.quantity,
            showInput: false,
        }
    },
    watch: {
        quantity(newValue) {
            this.count = newValue;
        },
    },
    methods: {
        ...mapActions(['changeItemQuantityInCart']),
        ...mapMutations(['SET_ITEM_QUANTITY']),
        handleCountClick() {
            this.showInput = true;
        },
        decrementCount() {
            this.count--;
            this.$emit('quantity-change', this.count);
            this.changeItemQuantityInCart({upc: this.itemUpc, type: 'decrement'})
        },
        incrementCount() {
            this.count--;
            this.$emit('quantity-change', this.count);
            this.changeItemQuantityInCart({upc: this.itemUpc, type: 'increment'})
        },
        handleCountControlKeyUp(e) {
            this.$emit('quantity-change', this.count);
            this.SET_ITEM_QUANTITY({upc: this.itemUpc, new_quantity_amount: this.count});
        },
    },
}
</script>

<style lang="sass" scoped>
.count-input
    font-size: .75rem
    width: 5rem
</style>
