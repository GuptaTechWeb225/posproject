<template>
    <div class="sub-category row w-100">
        <div class="col-lg-3 border-bottom py-3 mb-3">
            {{ subCategory.name }}
        </div>

        <div class="col-lg-4 border-bottom py-3 mb-3">
            <p>
                <span class="text-muted">{{ $t('description') }}:</span>
                {{ subCategory.description }}
            </p>
        </div>

        <div class="col-lg-3 border-bottom py-3 mb-3 d-flex">
            <span class="text-muted pr-2"> {{ $t('status') }} : </span>
            <app-input type="switch" v-model="active" @click="triggerStatusChange"/>
            <span class="subcategory-status">{{ subCategory.status.translated_name }}</span>
        </div>

        <div class="col-lg-2 border-bottom py-3 mb-3 gl-5 d-flex justify-content-center">
            <dropdown-action
                :key="`subcategory-dropdown-key`"
                :actions="subCategoryActions"
                @action="triggerAction"
                :rowData="subCategory"
            />
        </div>

    </div>
</template>

<script>
import DropdownAction from '../../../../../core/components/datatable/DropdownAction';
import StatusQueryMixin from "../../../../../common/Mixin/Global/StatusQueryMixin";
import {CATEGORIES, SUB_CATEGORIES, UNIT} from "../../../../Config/ApiUrl-CP";
import {axiosDelete, urlGenerator} from "../../../../../common/Helper/AxiosHelper";

export default {
    name: "app-sub-category",
    mixins: [StatusQueryMixin],
    components: {
        DropdownAction
    },
    props: ['subCategory', 'tableId'],
    data() {
        return {
            active: this.subCategory.status.translated_name === 'Active',
            subCategoryActions: [
                {
                    title: this.$t('edit'),
                    icon: 'edit',
                    type: 'modal',
                    name: 'edit',
                    component: 'app-sub-category-modal',
                    modalId: 'sub-category-modal',
                    modifier: (row) => {
                        return this.$can('update_category');
                    },
                },
                {
                    title: this.$t('delete'),
                    icon: 'trash-2',
                    type: 'modal',
                    component: 'app-confirmation-modal',
                    modalId: 'app-confirmation-modal',
                    url: SUB_CATEGORIES,
                    name: 'delete',
                    modifier: (row) => {
                        return this.$can('delete_category');
                    },
                }
            ],
        }
    },
    methods: {
        triggerAction(rowData, action, active) {
            if (action.name === 'edit') {
                this.$hub.$emit('subcategory-edit', {subcategoryId: rowData.id});
            } else if (action.name === 'delete') {
                this.$hub.$emit('subcategory-delete', this.subCategory.id);
            }
        },
        triggerStatusChange() {
            this.switchStatus(
                'sub_category',
                this.subCategory.id,
                this.subCategory.status.translated_name.toLowerCase(),
                this.tableId
            );
        }
    }
}
</script>