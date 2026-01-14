<template>
    <div v-if="value" class="d-flex">
        <app-input v-model="statusActive" :label="$t('switch')" type="switch" @change="triggerStatus"/>
        <span class="ml-1">{{ value.translated_name }}</span>
    </div>
</template>

<script>
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import {axiosPatch} from "../../../../../common/Helper/AxiosHelper";
import {PRODUCTS} from "../../../../Config/ApiUrl-CP";

export default {
    name: "ProductStatusComponent",
    mixins: [FormHelperMixins],
    props: ['rowData', 'tableId', 'value'],
    data() {
        return {
            statusActive: this.value?.name === 'status_active',
            statusInfo: {},
            productStatusChangeUrl: PRODUCTS + '/' + this.rowData.id + '/change-status'
        }
    },
    methods: {
        triggerStatus() {
            this.statusInfo.status = this.statusActive
            axiosPatch(this.productStatusChangeUrl, this.statusInfo)
                .then(res => {
                    this.$hub.$emit('reload-product-table');
                })
                .catch((error) => {
                    this.$toastr.e(error);
                })
        }
    }
}
</script>
