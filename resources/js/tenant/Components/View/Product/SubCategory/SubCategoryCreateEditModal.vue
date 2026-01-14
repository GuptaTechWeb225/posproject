<template>
    <modal id="sub-category-modal"
           modal-id="sub-category-modal"
           v-model="showModal"
           :loading="loading"
           :preloader="preloader"
           :scrollable="false"
           :title="generateModalTitle('sub_category')"
           @input="closeSubCategoryModal"
           @submit="submitData">

        <form
            ref="form"
            :data-url='selectedUrl ? selectedUrl : SUB_CATEGORIES'
            @submit.prevent="submitData">

            <app-form-group
                v-model="formData.name"
                :error-message="$errorMessage(errors, 'name')"
                :label="$t('name')"
                :placeholder="$placeholder('name')"
                :required="true"
            />

            <div :class="`subcategory-description-input mb-3 ${ $isRtl ? 'text-left' : '' }`">
            <label for="subcategory-description">{{ $t('description') }}</label>
            <app-input
                type="textarea"
                :text-area-rows="5"
                id="subcategory-description"
                v-model="formData.description"
                :error-message="$errorMessage(errors, 'description')"
                :label="$t('description')"
                :placeholder="$placeholder('description')"
            />
          </div>

          <div class="sub-category-status d-flex"  v-if="this.$can('change_status_subcategory')">
            <label for="status-input" class="text-muted pr-2">{{ $t('status') }}:</label>
            <app-input
                v-model="status"
                id="status-input"
                type="switch"
                :error-message="$errorMessage(errors, 'status')"
                :label="$addLabel('status')"
                :placeholder="$placeholder('status')"
            />
            <span class="sub-category-status">{{ status ? $t('status_active') : $t('status_deactive') }}</span>
          </div>

        </form>
    </modal>
</template>

<script>

import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import ModalMixin from "../../../../../common/Mixin/Global/ModalMixin";
import {SUB_CATEGORIES} from "../../../../Config/ApiUrl-CP";
import {axiosGet} from "../../../../../common/Helper/AxiosHelper";
import StatusQueryMixin from "../../../../../common/Mixin/Global/StatusQueryMixin";

export default {
    name: 'app-sub-category-modal',
    mixins: [FormHelperMixins, ModalMixin, StatusQueryMixin],
    props: ['categoryId'],
    data() {
        return {
            discountInput: true,
            status: true,
            subCategoryStatusData: [],
            formData: {
                category_id: !this.selectedUrl ? this.categoryId : '',
                status_id: null,
            },
            SUB_CATEGORIES,
        }
    },
    mounted() {
      axiosGet(this.getStatus('sub_category'))
        .then(statusData => {
          this.subCategoryStatusData = statusData.data.data;
          this.formData.status_id = this.subCategoryStatusData[0].id;
        });
    },
    watch: {
        status(newValue) {
            const [{id: activeStatusId}, {id: inactiveStatusId}] = this.subCategoryStatusData;
            this.formData.status_id = newValue ? activeStatusId : inactiveStatusId;
        }
    },
    methods: {
        afterSuccess({data}) {
            this.formData = {};
            $('#sub-category-modal').modal('hide');
            this.$emit('update-values');
            this.$emit('input', false);
            this.toastAndReload(data.message, 'category-table');
        },
        afterSuccessFromGetEditData(response) {
            this.preloader = false;
            this.formData = response.data;
            const { status_id: subCategoryStatusId } = response.data;
            subCategoryStatusId === this.subCategoryStatusData[0].id ? this.status = true : this.status = false;
        },
        getCategoryIds(categoryObj) {
            let category = [];
            categoryObj.filter((item) => {
                category.push(item.id);
            })
            return category;
        },
        closeSubCategoryModal() {
            this.$emit('close-modal');
        }
    },
}
</script>

