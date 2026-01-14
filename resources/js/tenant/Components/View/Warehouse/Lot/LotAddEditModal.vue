<template>
    <modal id="lot-create-edit-modal"
           v-model="showModal"
           :loading="loading"
           :scrollable="false"
           :preloader="preloader"
           size="large"
           :title="generateModalTitle('lot')"
           @submit="submit">

        <form
            ref="form"
            :data-url='selectedUrl ? selectedUrl : LOT'
            enctype="multipart/form-data">

            <app-form-group
                v-model="formData.name"
                :label="$t('name')"
                :placeholder="$placeholder('name')"
                :required="true"
                :error-message="$errorMessage(errors, 'name')"
            />

            <app-form-group
                v-model="formData.arrival_date"
                :label="$t('arrival_date')"
                :placeholder="$placeholder('arrival_date')"
                type="date"
            />

            <div class="form-group">
                <label>{{ $fieldTitle('received_at', '') }}</label>
                <app-input
                    :id="'received_at'"
                    type="date"
                    date-mode="dateTime"
                    v-model="formData.received_at"
                    :placeholder="$placeholder('received_at', '')"
                    name="received_at"
                    :error-message="$errorMessage(errors, 'received_at')"
                    :max-date="new Date()"
                />
            </div>

            <app-form-group-selectable
                v-model="formData.branch_id"
                :label="$t('branch')"
                :selectLabel="$t('branch')"
                :fetch-url="SELECTABLE_BRANCH"
                list-value-field="name"
                type="search-select"
                :error-message="$errorMessage(errors, 'branch_id')"
            />

            <app-form-group-selectable
                v-model="formData.received_by"
                :label="$t('received_by')"
                :selectLabel="$t('received_by')"
                :fetch-url="SELECTABLE_RECEIVED_BY"
                list-value-field="full_name"
                type="search-select"
                :error-message="$errorMessage(errors, 'received_by')"
            />
        </form>
    </modal>

</template>
<script>
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import ModalMixin from "../../../../../common/Mixin/Global/ModalMixin";
import {
    LOT,
    SELECTABLE_BRANCH,
    SELECTABLE_RECEIVED_BY
} from "../../../../Config/ApiUrl-CP";
import {formatDateForServer, formatDateTimeForServer} from "../../../../Helper/DateTimeHelper";

export default {
    name: "LotAddEditModal",
    mixins: [FormHelperMixins, ModalMixin],
    data() {
        return {
            showNote: true,
            formData: {
                name: '',
                arrival_date: '',
                received_at: '',
                supplier_id:'',
                branch_id:'',
                received_by:'',
            },
            LOT,
            SELECTABLE_BRANCH,
            SELECTABLE_RECEIVED_BY,
        }
    },
    methods: {
        submit() {

            let submittedData = {...this.formData};

            submittedData.arrival_date = formatDateForServer(submittedData.arrival_date);
            submittedData.received_at = formatDateTimeForServer(this.formData.received_at);

            this.save(submittedData);
        },
        afterSuccess({data}) {
            this.formData = {};
            $('#Lot-create-edit-modal').modal('hide');
            this.$emit('input', false);
            this.toastAndReload(data.message, 'Lot-table');
        },
        afterSuccessFromGetEditData({data}) {

            this.preloader = false;
            this.formData.arrival_date = data.arrival_date ? new Date(data.arrival_date) : '';
            this.formData = data;
        }
    },
}
</script>