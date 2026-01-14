<template>
    <div :class="`customer-input ${ viewMode === 'primary' ? 'col-md-5 custom-resize' : 'col-md-6' } col-12 d-flex`">
        <app-input
            class="mr-2"
            style="flex: 1;"
            v-if="selectedCustomer"
            type="search-and-select"
            :placeholder="$placeholder('customer')"
            @input="handleCustomerInput"
            :options="customerOptions"
            :inputclearable="false"
            list-value-field="value"
            v-model="selectedCustomer"
        />
        <app-input
            v-else
            type="text"
            :placeholder="$placeholder('customer')"
            v-model="selectedCustomer"
        />
        <button class="btn btn-primary" @click="isModalActive = true">
            <app-icon name="user-plus"/>
        </button>

        <app-customer-modal
            v-if="isModalActive"
            v-model="isModalActive"
            @close="isModalActive = false"
        />
    </div>
</template>

<script>
import FormHelperMixins from "../../../../common/Mixin/Global/FormHelperMixins";
import {urlGenerator} from "../../../../common/Helper/AxiosHelper";
import {SELECTABLE_CUSTOMERS} from "../../../Config/ApiUrl-CP";
import {mapGetters, mapMutations} from "vuex";

export default {
    mixins: [FormHelperMixins],
    name: 'CustomerInput',
    props: ['viewMode'],
    data() {
        return {
            isModalActive: false,
            selectedCustomer: '',
            customerOptions: {
                url: urlGenerator(SELECTABLE_CUSTOMERS),
                query_name: "search",
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                modifire: ({id, full_name: value}) => ({id, value}), prefetch: false
            },
        }
    },
    methods: {
        ...mapMutations(['SET_SELECTED_CUSTOMER']),
        handleCustomerInput() {
            this.SET_SELECTED_CUSTOMER(this.selectedCustomer);
        }
    },
    computed: {
        ...mapGetters(['getSelectedCustomer']),
    },
    mounted() {
        this.selectedCustomer = this.getSelectedCustomer || 1;
    }
}
</script>

<style lang="scss">
.customer-input {
    order: 1;
}

@media only screen and (min-width: 768px) {
    .customer-input {
        order: 0;
    }
}
</style>
