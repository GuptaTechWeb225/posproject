<template>
    <modal
        id="category-modal"
        v-model="showModal"
        :loading="loading"
        :preloader="preloader"
        :scrollable="false"
        :title="generateModalTitle('category')"
        @submit="submitData">

        <form
            ref="form"
            :data-url='selectedUrl ? selectedUrl: CATEGORIES'
            @submit.prevent="submitData">

            <app-form-group
                v-model="formData.name"
                :error-message="$errorMessage(errors, 'name')"
                :label="$t('name')"
                :placeholder="$placeholder('name')"
                :required="true"
            />

          <app-form-group
              v-model="formData.description"
              type="textarea"
              :error-message="$errorMessage(errors, 'description')"
              :label="$t('description')"
              :placeholder="$placeholder('description')"
              :required="false"
          />

          <div class="category-status d-flex" v-if="this.$can('change_category_status')">
            <label for="status-input" class="text-muted pr-2">{{ $t('status') }}: </label>
            <app-input
              v-model="status"
              id="status-input"
              type="switch"
              :error-message="$errorMessage(errors, 'status')"
              :label="$addLabel('status')"
              :placeholder="$placeholder('status')"
            />
            <span class="category-status">{{ status ? $t('status_active') : $t('status_inactive') }}</span>
          </div>

        </form>
    </modal>
</template>

<script>
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import ModalMixin from "../../../../../common/Mixin/Global/ModalMixin";
import {CATEGORIES, SELECTABLE_SUB_CATEGORIES} from "../../../../Config/ApiUrl-CP";
import {axiosGet} from "../../../../../common/Helper/AxiosHelper";
import StatusQueryMixin from "../../../../../common/Mixin/Global/StatusQueryMixin";

export default {
    name: "CategoryCreateEditModal",
    mixins: [FormHelperMixins, ModalMixin, StatusQueryMixin],
    data() {
        return {
            categoryStatusData: [],
            currentCategoryStatus: null,
            categoryEditSelectedUrl: this.selectedUrl,
            status: true,
            formData: {
              sub_category_id: [],
              status_id: null,
            },
            CATEGORIES,
            SELECTABLE_SUB_CATEGORIES
        }
    },
    watch: {
      status(newValue) {
          const [{id: activeStatusId}, {id: inactiveStatusId}] = this.categoryStatusData;
          this.formData.status_id = newValue ? activeStatusId : inactiveStatusId;
      }
    },
    mounted() {
      axiosGet(this.getStatus('category'))
        .then(statusData => {
          this.categoryStatusData = [...statusData.data.data];
          this.formData.status_id = this.categoryStatusData[0].id;
        });
    },
    methods: {
        afterSuccess({data}) {
            this.formData = {};
            $('#category-modal').modal('hide');
            this.$emit('update-values');
            this.$emit('input', false);
            this.toastAndReload(data.message, 'category-table');
        },
        afterSuccessFromGetEditData(response) {
            this.preloader = false;
            this.formData = response.data;
            this.formData.sub_category_id = this.getSubCategoryIds(this.formData.sub_categories);

            const { status_id: categoryStatusId } = response.data;
            categoryStatusId === this.categoryStatusData[0].id ? this.status = true : this.status = false;
        },
        getSubCategoryIds(subCategoryObj) {
            let subCategory = [];
            subCategoryObj.filter((item) => {
                subCategory.push(item.id);
            })
            return subCategory;
        }
    },
}
</script>

