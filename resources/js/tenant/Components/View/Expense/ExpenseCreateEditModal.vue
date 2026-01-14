<template>
    <modal id="expense-create-edit-modal"
           v-model="showModal"
           :loading="loading"
           :preloader="preloader" :title="generateModalTitle('expense')"
           @submit="submitDataTest">

        <form
            ref="form"
            :class="`${ $isRtl ? 'text-left' : '' }`"
            :data-url='selectedUrl ? selectedUrl : EXPENSES'
            enctype="multipart/form-data">

            <app-form-group
                v-model="formData.name"
                :label="$t('name')"
                :placeholder="$placeholder('name')"
                :required="true"
                :error-message="$errorMessage(errors, 'name')"
            />

            <div class="form-element mb-3" :key="branchOrWarehouseInputKey">
                <label>{{ $t('branch_or_warehouse') }}</label>
                <app-input
                    type="search-and-select"
                    v-if="formData.branch_or_warehouse_id"
                    :placeholder="$placeholder('branch_or_warehouse')"
                    :options="branchesOrWarehousesOptions"
                    v-model="formData.branch_or_warehouse_id"
                    :error-message="$errorMessage(errors, 'branch_or_warehouse_id')"
                />

                <app-input
                    v-else
                    type="search-and-select"
                    :placeholder="$placeholder('branch_or_warehouse')"
                    :options="branchesOrWarehousesOptions"
                    v-model="formData.branch_or_warehouse_id"
                    :error-message="$errorMessage(errors, 'branch_or_warehouse_id')"
                />
            </div>

            <app-form-group-selectable
                v-model="formData.expense_area_id"
                :label="$t('area_of_expense')"
                :selectLabel="$t('area_of_expense')"
                :fetch-url="SELECTABLE_EXPENSE_AREA"
                list-value-field="name"
                type="select"
                :error-message="$errorMessage(errors, 'expense_area_id')"
            />

            <app-form-group
                v-model="formData.amount"
                :label="$t('amount')+' '+`${'('+getCurrencySymbol()+')'}`"
                :placeholder="$placeholder('amount')"
                :required="true"
                type="currency"
                :error-message="$errorMessage(errors, 'amount')"
            />

            <app-form-group
                v-model="formData.expense_date"
                :label="$t('expense_date')"
                :placeholder="$placeholder('expense_date')"
                type="date"
            />

            <app-form-group
                v-model="formData.description"
                :label="$t('description')"
                :placeholder="$placeholder('description')"
            />

            <div class="form-row">
                <div class="margin-top-10 text-warning d-flex align-item-center">
                    <app-icon name="alert-circle" class="mr-2"/>
                    <p style="margin-top: 2px;"> {{ $t('clicking_remove_file_will_remove_the_attachment_permanently') }} </p>
                </div>
                <div class="form-group col-12">
                    <label>{{ $t('attachments') }}</label>
                    <app-input
                        v-model="formData.attachments"
                        @file-removed="handleDropzoneFileRemove"
                        type="dropzone"
                        v-if="showDropzone"
                    />
                </div>

                <small :class="{'text-danger':attachmentErrorLength}">
                    {{ $t("allowed_file_types_xls_csv_excluded") }}
                </small>
            </div>

        </form>
    </modal>
</template>
<script>
import FormHelperMixins from "../../../../common/Mixin/Global/FormHelperMixins";
import ModalMixin from "../../../../common/Mixin/Global/ModalMixin";
import {
    EXPENSES,
    SELECTABLE_BRANCHES_OR_WAREHOUSES,
    SELECTABLE_EXPENSE_AREA,
    EXPENSE_ATTACHMENT_DELETE,
} from "../../../Config/ApiUrl-CP";
import {formDataAssigner, getCurrencySymbol} from "../../../Helper/Helper";
import {formatDateForServer} from "../../../../common/Helper/Support/DateTimeHelper";
import {axiosDelete, urlGenerator} from "../../../../common/Helper/AxiosHelper";
import {mapGetters} from "vuex";

export default {
    name: "ExpenseCreateEditModal",
    mixins: [FormHelperMixins, ModalMixin],
    mounted() {
        this.formData.branch_or_warehouse_id = this.getBranchOrWarehouseId;
        this.formData.expense_date = new Date();
        this.branchOrWarehouseInputKey++;
    },
    data() {
        return {
            showNote: true,
            expenseAttachments: [],
            branchOrWarehouseInputKey: 0,
            showDropzone: true,
            formData: {
                name: '',
                branch_or_warehouse_id: '',
                expense_area_id: '',
                amount: '',
                expense_date: '',
                attachments: [],
                description: '',
            },
            branchesOrWarehousesOptions: {
                url: urlGenerator(SELECTABLE_BRANCHES_OR_WAREHOUSES),
                query_name: "search_by_name",
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                prefetch: false,
                modifire: ({id, name: value}) => ({id, value}),
            },
            EXPENSES,
            SELECTABLE_EXPENSE_AREA,
            getCurrencySymbol,
        }
    },
    methods: {
        handleDropzoneFileRemove(file) {
            const fileToRemove = this.expenseAttachments.find(attachment => attachment.path === file.dataURL);
            if (!fileToRemove) return;
            const { id: fileToRemoveId } = fileToRemove;
            axiosDelete(EXPENSE_ATTACHMENT_DELETE + fileToRemoveId)
                .then(res => this.$toastr.s('', res.data.message))
                .catch(err => this.$toastr.e('', err.message));
        },
        submitDataTest() {
            this.loading = true;
            this.message = '';
            this.errors = {};
            this.showDropzone = false; // removing the dropzone from the template immediately on save button click (the contents get removed if this isn't done)

            try { this.formData.attachments = this.formData.attachments.filter(attachment => 'upload' in attachment); }
            catch (e) { this.$toastr.e(e); }

            if (this.formData.branch_or_warehouse_id === null) this.formData.branch_or_warehouse_id = '';
            let formData = formDataAssigner(new FormData, this.formData);

            if (this.selectedUrl) formData.append('_method', 'patch')
            formData.append('expense_date', formatDateForServer(this.formData.expense_date))
            if (this.formData.attachments.length) this.formData.attachments.forEach(attachment => {
                attachment instanceof File ?
                    formData.append(`attachments[]`, attachment) :
                    formData.append(`dont_delete[]`, attachment.dataURL)
            })

            return this.submitFromFixin(
                'post',
                this.selectedUrl ? this.selectedUrl : EXPENSES,
                formData
            );
        },
        afterSuccess({data}) {
            $('#expense-create-edit-modal').modal('hide');
            this.$emit('input', false);
            this.toastAndReload(data.message, 'expense-table');
        },
        afterSuccessFromGetEditData({data}) {

            this.preloader = false;
            this.formData.expense_date = data.expense_date ? new Date(data.expense_date) : '';
            this.formData = data;
            this.expenseAttachments = [ ...this.formData.attachments ];

            //show attachment in dropzone
            this.formData.attachments = _.map(this.formData.attachments, 'path');
        }
    },
    computed: {
        ...mapGetters(['getBranchOrWarehouseId']),
        attachmentErrorLength() {
            return Object.keys(this.errors).filter(item => {
                return item.includes('attachments');
            }).length > 0
        }
    }
}
</script>
