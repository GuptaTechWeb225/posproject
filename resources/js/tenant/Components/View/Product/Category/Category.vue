<template>
    <div class="content-wrapper">

        <app-page-top-section :title="$t('categories')">

            <div class="dropdown d-inline-block btn-dropdown">
                <div class="dropdown-menu">
                    <a class="dropdown-item d-flex align-items-center p-3" :href="urlGenerator('/category/import')">
                        <app-icon class="size-15 mr-2" name="download"/>
                        {{ $t('import_category') }}
                    </a>
                    <a class="dropdown-item d-flex align-items-center p-3" href="#" @click="exportCategories">
                        <app-icon class="size-15 mr-2" name="upload"/>
                        {{ $t('export_category') }}
                    </a>
                </div>
            </div>

            <app-default-button
                v-if="this.$can('create_categories')"
                :title="$addLabel('category')"
                @click="openModal()"/>

        </app-page-top-section>

        <app-table
            id="category-table"
            :options="options"
            @action="triggerAction"
        />

        <app-category-modal
            v-if="isModalActive"
            v-model="isModalActive"
            :selected-url="categorySelectedUrl"
            @close="isModalActive = false"
        />

        <app-sub-category-modal
            v-if="isSubCategoryModalActive"
            v-model="isSubCategoryModalActive"
            @close-modal="handleSubCategoryModalClose"
            :selected-url="selectedSubCategoryUrl"
            :category-id="categoryId"
        />

        <app-confirmation-modal
            v-if="confirmationModalActive"
            icon="trash-2"
            modal-id="app-confirmation-modal"
            @cancelled="cancelled"
            @confirmed="confirmed('category-table')"
        />
    </div>

</template>
<script>
import HelperMixin from "../../../../../common/Mixin/Global/HelperMixin";
import CategoryMixin from "../../../Mixins/CategoryMixin";
import {CATEGORIES, EXPORT_CATEGORIES, SUB_CATEGORIES} from "../../../../Config/ApiUrl-CP";
import {urlGenerator} from "../../../../../common/Helper/AxiosHelper";

export default {
    name: "Category",
    mixins: [HelperMixin, CategoryMixin],
    data() {
        return {
            isModalActive: false,
            isSubCategoryModalActive: false,
            categorySelectedUrl: this.selectedUrl,
            selectedSubCategoryUrl: '',
            // this value is for the subcategory modal, being set form triggerAction
            categoryId: null,
            urlGenerator
        }
    },
    mounted() {
        this.$hub.$on('subcategory-edit', (data) => {
            this.isSubCategoryModalActive = true;
            // make the "3", dynamic
            this.selectedSubCategoryUrl = `${SUB_CATEGORIES}/${data.subcategoryId}`;
        });
        this.$hub.$on('subcategory-delete', (subCategoryId) => {
            this.delete_url = `app/sub-categories/${subCategoryId}`
            this.confirmationModalActive = true;
        });
    },
    methods: {
        triggerAction(row, action, active) {
            this.categoryId = row.id;
            if (action.name === 'edit') {
              this.categorySelectedUrl = `${CATEGORIES}/${row.id}`;
              this.isModalActive = true;
            } else if (action.type === 'status_change') {
                this.triggerStatusChange(row.id, row.status.translated_name);
            } else if (action.name === 'add_sub_category')  {
                this.isSubCategoryModalActive = true;
            } else {
                this.getAction(row, action, active)
            }
        },
        triggerStatusChange(id, statusToChangeFrom) {
            this.switchStatus(
                'category',
                id,
                statusToChangeFrom.toLowerCase(),
                'category-table'
            );
        },
        openModal() {
            this.isModalActive = true;
            this.categorySelectedUrl = ''
            this.selectedSubCategoryUrl = ''
        },
        handleSubCategoryModalClose() {
            this.selectedSubCategoryUrl = ''
            this.isSubCategoryModalActive = false
        },
        exportCategories(){
            window.location = EXPORT_CATEGORIES;
        }
    },
}
</script>