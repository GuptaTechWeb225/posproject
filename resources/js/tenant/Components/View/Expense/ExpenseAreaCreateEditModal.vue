<template>
    <modal id="expense-area-create-edit-modal"
           v-model="showModal"
           :title="generateModalTitle('area_of_expense')"
           @submit="submitData" :loading="loading"
           :preloader="preloader">

        <form
            ref="form"
            :data-url='selectedUrl ? selectedUrl : EXPENSE_AREAS'
            @submit.prevent="submitData">

            <app-form-group
                :label="$t('name')"
                :placeholder="$placeholder('name')"
                v-model="formData.name"
                :required="true"
                :error-message="$errorMessage(errors, 'name')"
            />

            <app-form-group
                :label="$t('description')"
                :placeholder="$placeholder('description')"
                v-model="formData.description"
            />

            <div class="d-flex align-items-center">
                <label class="mb-0 mr-5">{{ $t('add_to_report')}}</label>
                <app-input
                    class="margin-top-5"
                    v-model="formData.is_add_to_report"
                    type="switch"
                />
                <p class="mb-0">{{ formData.is_add_to_report ? $t('yes') : $t('no')}}</p>
            </div>
        </form>
    </modal>
</template>
<script>
import FormHelperMixins from "../../../../common/Mixin/Global/FormHelperMixins";
import ModalMixin from "../../../../common/Mixin/Global/ModalMixin";
import {EXPENSE_AREAS} from "../../../Config/ApiUrl-CP";

export default {
    name: "ExpenseAreaCreateEditModal",
    mixins: [FormHelperMixins, ModalMixin],
    data() {
        return {
            showNote: true,
            formData: {
                is_add_to_report: false
            },
            EXPENSE_AREAS,
        }
    },
    methods: {
        submitData() {
            this.fieldStatus.isSubmit = true;
            this.loading = true;
            this.message = '';
            this.errors = {};

            this.formData.is_add_to_report ? this.formData.is_add_to_report = 1 : this.formData.is_add_to_report = 0
            this.save(this.formData);
        },
        afterSuccess({data}) {
            this.formData = {};
            $('#expense-area-create-edit-modal').modal('hide');
            this.$emit('input', false);
            this.toastAndReload(data.message, 'expense-area-table');
        },
        afterSuccessFromGetEditData(response) {
            this.preloader = false;
            this.formData = response.data;
            parseInt(this.formData.is_add_to_report) === 1 ? this.formData.is_add_to_report = true : this.formData.is_add_to_report = false
        },
    },
}
</script>