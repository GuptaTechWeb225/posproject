<template>
    <div class="d-flex">
        <span class="ml-1">{{ item === 1 ? $t('yes') : $t('no') }}</span>
    </div>
</template>

<script>
import FormHelperMixins from "../../../../common/Mixin/Global/FormHelperMixins";
import {TAX} from "../../../Config/ApiUrl-CPB";
import {collection} from "../../../../common/Helper/helpers";

export default {
    name: "IsDefaultForDataTable",
    mixins: [FormHelperMixins],
    props: ['rowData', 'tableId', 'value'],
    data() {
        return {
            item: this.value,
            rowValues: this.rowData
        }
    },
    methods: {
        triggerIsDefault() {

            const tax_id = collection(this.rowValues.tax_taxes).pluck('parent_id');
            delete this.rowValues['tax_taxes'];
            this.rowValues = {...this.rowValues, tax_id};
            this.rowValues.is_default = this.item ? 1 : 0

            this.axiosPatch({
                url: TAX + '/' + this.rowValues.id,
                data: this.rowValues
            }).then(res => {
                this.$hub.$emit('reload-all-taxes-table');
            });
        }
    },
}
</script>