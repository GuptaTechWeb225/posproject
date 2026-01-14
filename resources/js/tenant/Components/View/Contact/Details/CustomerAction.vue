<template>
    <div>
        <p class="text-primary cursor-pointer pb-0 mb-0" @click="handleCustomerNameClick"> {{ rowData.full_name }} </p>
    </div>
</template>

<script>
import {CUSTOMER_DETAILS} from "../../../../Config/ApiUrl-CPB";
import {urlGenerator} from "../../../../Helper/Helper";

export default {
    name: "CustomerAction",
    props: {
        value: {},
        rowData: {},
    },
    data() {
        return {
            CUSTOMER_DETAILS
        }
    },
    methods: {
        handleCustomerNameClick() {
            return window.location.replace(urlGenerator(CUSTOMER_DETAILS + this.rowData.id));
        }
    }
}
</script>