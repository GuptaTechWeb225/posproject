<template>
    <app-modal modal-id="app-stock-import-modal" modal-size="large" modal-alignment="top" @close-modal="closeModal">
        <!-- Modal header -->
        <template slot="header">
            <h5 class="modal-heading">{{ $t('import_stock') }}</h5>
            <button class="close outline-none" @click.prevent="closeModal">
                <app-icon name="x"/>
            </button>
        </template>


        <!-- Modal body -->
        <template slot="body">
            <form ref="form" :data-url="''">

                <div class="row align-items-start no-gutters">
                    <!-- warehouse input -->
                    <div class="form-element col-12 col-md-4 mb-4">
                        <label>{{ $t('branch_or_warehouse') }}</label>
                        <app-input
                            class="margin-right-8"
                            type="warehouse-or-branch"
                            :placeholder="$placeholder('branch_or_warehouse')"
                            :options="branchOrWareHoueeOptions"
                            v-model="formData.branch_or_warehouse_id"
                            :error-message="$errorMessage(errors, 'branch_or_warehouse_id')"
                        />
                    </div>

                    <!-- supplier input -->
                    <div class="form-element col-12 col-md-4 mb-4">
                        <label>{{ $t('supplier') }}</label>
                        <app-input
                            class="margin-right-8"
                            type="search-and-select"
                            :placeholder="$placeholder('supplier')"
                            :options="supplierOptions"
                            v-model="formData.supplier_id"
                            :error-message="$errorMessage(errors, 'supplier_id')"
                        />
                    </div>

                    <!-- purchase date input -->
                    <div class="form-element col-12 col-md-4 mb-4">
                        <label>{{ $t('purchase_date') }}</label>
                        <app-input
                            class="margin-right-8"
                            type="date"
                            :placeholder="$placeholder('date')"
                            v-model="formData.purchase_date"
                            :error-message="$errorMessage(errors, 'purchase_date')"
                        />
                    </div>

                    <div class="form-element col-12 col-md-6 mb-4">
                        <label>{{ $t('purchase_status') }}</label>
                        <app-input
                            class="margin-right-8"
                            type="search-and-select"
                            :placeholder="$placeholder('purchase_status')"
                            :options="purchaseStatusOptions"
                            v-model="formData.purchase_status"
                            :error-message="$errorMessage(errors, 'purchase_status_id')"
                        />
                    </div>

                    <div class="form-element col-12 col-md-6 mb-4">
                        <label>{{ $t('lot_reference_number') }}</label>
                        <app-input
                            class="margin-right-8"
                            type="number"
                            :placeholder="$placeholder('lot_reference_number')"
                            v-model="formData.reference_no"
                            :error-message="$errorMessage(errors, 'reference_no')"
                        />
                    </div>
                </div>

                <app-input type="dropzone" v-model="formData.file"/>

                <div class="d-inline-block mt-2">
                    <app-icon name="download" />
                    <a href="#" @click.prevent="sampleDownload">
                        {{ $t('download_sample_file') }}
                    </a>
                </div>
            </form>
        </template>


        <!-- Modal Footer -->
        <template slot="footer" class="justify-content-start">
            <app-submit-button :loading="loading" btn-class="d-inline-flex text-center mr-2" :label="$t('import')"
                               @click="save"/>
            <app-cancel-button btn-class="btn-secondary" @click="closeModal"/>
        </template>
    </app-modal>
</template>

<script>
import {FormMixin} from '../../../../core/mixins/form/FormMixin';
import HelperMixin from "../../../../common/Mixin/Global/HelperMixin";
import {urlGenerator} from "../../../../common/Helper/AxiosHelper";

export default {
    name: 'app-stock-import-modal',
    mixins: [FormMixin, HelperMixin],
    data() {
        return {
            formData: {
                file: null,

                branch_or_warehouse_id: '',
                supplier_id: '',
                purchase_date: new Date(),
                purchase_status: '',
                reference_no: '',
            },
            loading: false,

            errors: {},

            branchOrWareHoueeOptions: {
                url: urlGenerator("app/selectable-branches-or-warehouses"),
                queryName: 'search_by_name',
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                modifire: (value) => ({id: value.id, value: value.name, type: value.type}),
            },
            supplierOptions: {
                url: urlGenerator("app/selectable-suppliers"),
                queryName: 'search_by_name',
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                modifire: (value) => ({id: value.id, value: value.name}),
                prefetch: !Boolean(this.lotId)
            },
            purchaseStatusOptions: {
                url: urlGenerator("app/selectable-statuses"),
                query_name: "search_by_name",
                params: {
                    type: 'purchase',
                },
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                modifire: (value) => ({id: value.id, value: value.translated_name}),
                prefetch: !Boolean(this.lotId)
            },
        };
    },
    methods: {
        closeModal() {
            $('#app-stock-import-modal').modal('hide')
            this.$emit('modal-close');
        },

        formatDate(dateObj) {
            const date = {year: dateObj.getFullYear(), month: dateObj.getMonth() + 1, day: dateObj.getDate()}
            return `${date.year}-${date.month <= 9 ? '0' : ''}${date.month}-${date.day <= 9 ? '0' : ''}${date.day}`;
        },

        save() {
            this.loading = true
            let formData = new FormData();

            if (this.formData.file !== null) {
                for (const key in this.formData) {
                    if (key !== 'file') {
                        if (key === 'purchase_date') {
                            formData.append(key, this.formatDate(new Date(this.formData.purchase_date)));
                        } else {
                            formData.append(key, this.formData[key])
                        }
                    }
                }

                formData.append('import_stock', this.formData.file[0]);
            }

            let url = 'app/import/stocks'

            this.axiosPost({url, data: formData}).then(res => {
                this.toastAndReload(res.data.message, 'stock-import-table');
                this.closeModal();
            }).catch(({response}) => {
                this.$toastr.e(response.data.message);
            }).finally(() => {
                this.loading = false
            })
        },
        sampleDownload() {
            let commas = "";
            let keys = [
                "variant_name",
                "unit_quantity",
                "unit_price",
                "unit_tax_percentage",
            ];

            commas = ",".repeat(keys.slice(5).length)

            this.downloadCSV(
                `${keys.join(",")}\n` +
                `"T Shirt",2,50,20${commas}\n` +
                `"Full jeans pant",2,50,20${commas}\n`
            );
        },
        downloadCSV(csv) {
            let e = document.createElement("a");
            e.href = "data:text/csv;charset=utf-8," + encodeURI(csv);
            e.target = "_blank";
            e.download = `${this.$t("stock")}.csv`;
            e.click();
        },
    },
    mounted() {
        $('#app-stock-import-modal').modal('show')
    }
};
</script>