<template>
    <div class="amount-control d-flex align-items-center">
        <app-input
            class="text-right mr-2"
            v-model="newAmount"
            :placeholder="placeholder"
            @keyup="revealIcons"
            @keydown.enter.prevent="setAmount"
            type="number"
            :step="0.1"
        />
        <div :class="`icons ${showIcons ? '' : 'hidden'} d-flex`">
            <a @click="setAmount" class="text-primary pr-1" style="cursor: pointer;">
                <app-icon name="check-circle" class="size-18" />
            </a>

            <a @click="resetAmount" class="text-danger" style="cursor: pointer;">
                <app-icon name="x-circle" class="size-18"/>
            </a>
        </div>
    </div>
</template>

<script>
export default {
    name: 'amount-control',
    props: ['placeholder', 'currentAmount', 'variantId'],
    data() {
        return {
            newAmount: this.currentAmount,
            showIcons: false,
        }
    },
    methods: {
        revealIcons() {
            this.showIcons = true;
        },
        hideIcons() {
            this.showIcons = false;
        },
        setAmount() {
            this.$emit('amountChange', { variantId: this.variantId, newAmount: +this.newAmount });
            this.hideIcons();
            return false;
        },
        resetAmount() {
            this.newAmount = this.currentAmount;
            this.hideIcons();
        }
    }

}
</script>

<style scoped>
.hidden {
    opacity: 0;
    pointer-events: none;
}
</style>