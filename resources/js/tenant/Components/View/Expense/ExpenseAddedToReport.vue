<template>
    <div class="d-flex">
        <app-input
            class="margin-top-5"
            v-model="item"
            type="switch"
            @change="triggerIsDefault"
        />
        <span class="ml-1 mt-1">{{ item === 1 ? $t('yes') : $t('no') }}</span>
    </div>
</template>
<script>
import FormHelperMixins from "../../../../common/Mixin/Global/FormHelperMixins";
import {EXPENSE_AREAS} from "../../../Config/ApiUrl-CP";

export default {
    name: "ExpenseAddedToReport",
    mixins: [FormHelperMixins],
    props: ['rowData', 'tableId', 'value'],
    data() {
        return {
            item: parseInt(this.value),
            rowValues: this.rowData,
        }
    },
    methods: {
        triggerIsDefault() {

            this.rowValues.is_add_to_report = this.item ? 1 : 0;

            this.axiosPatch({
                url: EXPENSE_AREAS + '/' + this.rowValues.id,
                data: this.rowValues
            }).then(({data}) => {
                this.toastAndReload(data.message, this.tableId);
            }).catch(({data}) => {
                this.toastAndReload(data.message, this.tableId);
            });
        }
    },
}
</script>

