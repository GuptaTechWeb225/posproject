<template>
    <modal id="branch-create-edit-modal"
           size="large"
           v-model="showModal"
           :title="generateModalTitle('branch')"
           @submit="submitData"
           :loading="loading"
           :preloader="preloader">

        <form ref="form" :class="`${ $isRtl ? 'text-left' : '' }`"
              :data-url='selectedUrl ? selectedUrl : url'
              @submit.prevent="submitData">

            <app-form-group
                :page="'modal'"
                :label="$t('name')"
                :required="true"
                :error-message="$errorMessage(errors, 'name')"
                :placeholder="$placeholder('name')"
                v-model="formData.name"
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
                :page="'modal'"
                :label="$t('location')"
                :required="false"
                :error-message="$errorMessage(errors, 'location')"
                :placeholder="$placeholder('location')"
                v-model="formData.location"
            />
        </form>

    </modal>
</template>

<script>
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import HelperMixin from "../../../../../common/Mixin/Global/HelperMixin";
import ModalMixin from "../../../../../common/Mixin/Global/ModalMixin";
import CoreLibrary from "../../../../../core/helpers/CoreLibrary";
import {BRANCH_OR_WAREHOUSE} from "../../../../Config/ApiUrl-CPB";
import {urlGenerator} from "../../../../../common/Helper/AxiosHelper";

export default {
    name: "BranchCreateEditModal",
    extends: CoreLibrary,
    mixins: [FormHelperMixins, HelperMixin, ModalMixin],
    props: ['status'],
    data() {
        return {
            url: BRANCH_OR_WAREHOUSE,
            statusList: [],
            managerOptions: {
                url: urlGenerator("app/selectable/managers"),
                query_name: "search_by_name",
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                modifire: ({id, full_name: value}) => ({id, value}),
            },
            formData: {
                status_id: this.status.id,
                type: "branch"
            },
        }
    },
    methods: {
        submitData() {
            this.submitFromFixin(this.selectedUrl ? 'patch' : 'post' , this.selectedUrl ?? this.url, this.formData);
        },
        afterSuccess({data}) {
            this.formData = {};
            $('#branch-create-edit-modal').modal('hide');
            this.$emit('branch-created');
            this.toastAndReload(data.message, 'branches-and-warehouses-table');
            setTimeout(() => {
                window.location.reload();
            }, 1000)
        },
    }
}
</script>
