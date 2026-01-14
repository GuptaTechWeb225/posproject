<template>
    <modal id="counter-modal"
           v-model="showModal"
           :title="generateModalTitle('counter')"
           @submit="submitData" :loading="loading"
           :preloader="preloader">
        <form
            ref="form"
            :data-url='selectedUrl ? selectedUrl : COUNTERS'
            @submit.prevent="submitData">

            <app-form-group
                :label="$t('name')"
                :placeholder="$placeholder('name')"
                v-model="formData.name"
                :required="true"
                :error-message="$errorMessage(errors, 'name')">
            </app-form-group>

            <app-form-group-selectable
                type="select"
                :label="$t('branch')"
                list-value-field="name"
                v-model="formData.branch_id"
                :chooseLabel="$t('Branch')"
                :error-message="$errorMessage(errors, 'branch_id')"
                :fetch-url="SELECTABLE_BRANCH"
            />

            <app-form-group-selectable
                type="select"
                :label="$t('sales_person')"
                list-value-field="first_name"
                v-model="formData.first_name"
                :chooseLabel="$t('sales_person')"
                :error-message="$errorMessage(errors, 'sales_person')"
                :fetch-url="SALES_PEOPLE"
            />

        </form>
    </modal>
</template>

<script>
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import ModalMixin from "../../../../../common/Mixin/Global/ModalMixin";
import {
    COUNTERS,
    SELECTABLE_BRANCH,
    SELECTABLE_INVOICE_TEMPLATE,
    SALES_PEOPLE
} from "../../../../Config/ApiUrl-CPB";

export default {
    name: "CashRegisterAddEditModal",
    mixins: [FormHelperMixins, ModalMixin],
    data() {
        return {
            formData: {
                sales_person: []
            },
            SELECTABLE_BRANCH,
            SELECTABLE_INVOICE_TEMPLATE,
            COUNTERS,
            SALES_PEOPLE
        }
    },
    methods: {
        afterSuccess({data}) {
            this.formData = {};
            $('#counter-modal').modal('hide');
            this.$emit('input', false);
            this.toastAndReload(data.message, 'counter-table');
        },
        afterSuccessFromGetEditData(response) {
            this.preloader = false;
            this.formData = response.data;
        },
    },
}
</script>