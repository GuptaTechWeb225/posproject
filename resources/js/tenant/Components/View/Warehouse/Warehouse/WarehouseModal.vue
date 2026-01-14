<template>
    <modal id="warehouse-modal"
           v-model="showModal"
           :title="generateModalTitle('warehouse')"
           @submit="submitData" :loading="loading"
           :preloader="preloader">
        <form
            ref="form"
            :class="`${ $isRtl ? 'text-left' : '' }`"
            :data-url='selectedUrl ? selectedUrl : BRANCH_OR_WAREHOUSE'
            @submit.prevent="submitData">

            <app-form-group
                :label="$t('name')"
                :placeholder="$placeholder('name')"
                v-model="formData.name"
                :required="true"
                :error-message="$errorMessage(errors, 'name')"
            />

            <div class="manager-input mb-3">
                <label>{{ $t('manager') }}</label>

                <app-input
                    :class="`${ $isRtl ? 'text-left margin-left-8 ' : 'margin-right-8 ' }`"
                    type="search-and-select"
                    :placeholder="$t('search_and_select', {name: $t('manager').toLowerCase()})"
                    :options="managerOptions"
                    v-model="formData.manager_id"
                    :error-message="$errorMessage(errors, 'manager_id')"
                />
            </div>

            <app-form-group
                :label="$t('location')"
                :placeholder="$placeholder('location')"
                v-model="formData.location"
                :required="true"
                :error-message="$errorMessage(errors, 'location')"
            />
        </form>
    </modal>
</template>

<script>
import {
    SELECTABLE_BRANCH,
    SELECTABLE_INVOICE_TEMPLATE,
    BRANCH_OR_WAREHOUSE,
    SALES_PEOPLE
} from "../../../../Config/ApiUrl-CPB";
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import ModalMixin from "../../../../../common/Mixin/Global/ModalMixin";
import {urlGenerator} from "../../../../../common/Helper/AxiosHelper";

export default {
    name: "WarehouseAddEditModal",
    mixins: [FormHelperMixins, ModalMixin],
    props: {
        tableType: {
            default: 'warehouse',
        },
        branchId: {},
    },
    data() {
        return {
            url: BRANCH_OR_WAREHOUSE,
            managerOptions: {
                url: urlGenerator("app/selectable/managers"),
                query_name: "search_by_first_name",
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                modifire: ({id, full_name: value}) => ({id, value}),
            },
            formData: {
                name: '',
                manager_id: '',
                type: 'warehouse',
                location: '',
            },
            SELECTABLE_BRANCH,
            SELECTABLE_INVOICE_TEMPLATE,
            BRANCH_OR_WAREHOUSE,
            SALES_PEOPLE
        }
    },
    methods: {
        submitData() {
            this.submitFromFixin(this.selectedUrl ? 'patch' : 'post' , this.selectedUrl ?? this.url, this.formData);
        },
        afterSuccess({data}) {
            this.formData = {};
            $('#warehouse-modal').modal('hide');
            this.$emit('warehouse-created');
            this.toastAndReload(data.message, 'branches-and-warehouses-table');
        },
    },
}
</script>
