<template>
    <div class="col-4 d-flex m-0 p-0">
        <div
            :key="dropdownActive"
            class="d-flex justify-content-center mr-1 cursor-pointer"
            @click="handleSelectedProductDropdownBtnClick">
            <div :class="`round-btn ${darkThemeActive ? 'dark-bg' : 'light-bg'}`">
                <app-icon
                    :key="dropdownActive"
                    :name="`chevron-${!dropdownActive ? 'down' : 'up' }`"
                />
            </div>
        </div>
        <div class="product-details">
            <p class="product-name mb-0">{{ product.name | truncate }}</p>
            <small class="text-muted product-upc"># {{ product.upc }}</small> <br/>
            <small v-if="discountAmount" class="d-inline-block bg-primary px-2 text-white pill">
                {{
                    `${$t('discount')} - ${discountAmount}${product.discount_type === 'percentage' ? '%' : '(' + getCurrencySymbol + ')'}`
                }}
            </small>
            <small v-if="product.tax_amount" class="d-inline-block bg-info px-2 text-white pill">
                {{ `${$t('tax')} - ${product.tax_amount}%` }}
            </small>
        </div>

    </div>
</template>

<script>
import {getCurrencySymbol} from "../../../../Helper/Helper";

export default {
    name: 'selected-product',
    props: ['product', 'dropdownState', 'discountAmount'],
    components: {},
    filters: {
        truncate(value) {
            const limit = 30;
            if (value.length < limit) return value;
            return value.substring(0, limit) + '...'
        }
    },
    data() {
        const dropdownActive = this.dropdownState;
        return {dropdownActive}
    },
    methods: {
        handleSelectedProductDropdownBtnClick() {
            this.dropdownActive = !this.dropdownActive;
            this.$emit('dropdown-toggle');
        }
    },
    computed: {
        getCurrencySymbol() {
            return getCurrencySymbol();
        },
        darkThemeActive() {
            return this.$store.state.theme.darkMode;
        }
    }
}
</script>

<style scoped lang="sass">
.round-btn
    display: inline-flex
    justify-content: center
    align-items: center
    margin-right: 0.65rem
    cursor: pointer
    width: 1.65rem
    height: 1.65rem
    border-radius: 50%

.dark-bg
    background-color: #3b3d43

.light-bg
    background-color: #f9f9f9

.product-name
    font-size: 12px

.product-upc
    font-size: .625rem

.pill
    border-radius: 5rem
    font-size: .625rem
</style>
