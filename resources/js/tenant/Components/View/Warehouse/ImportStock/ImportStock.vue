<template>
    <div class="content-wrapper">
        <div class="d-flex align-items-center justify-content-between">
            <app-breadcrumb :page-title="$t('import_stock')"/>
        </div>

        <form :class="`card border-0 card-with-shadow ${ $isRtl ? 'text-left' : '' }`" data-url="" ref="form">
            <div class="card-body row">
                <!-- warehouse input -->
                <div class="form-element col-12 col-md-6 mb-4">
                    <label>{{ $t('warehouse') }}</label>
                    <app-input
                        :class="`${ !$isRtl ? 'margin-right-8 ' : 'margin-left-8' }`"
                        type="search-and-select"
                        :placeholder="$placeholder('warehouse')"
                        :options="warehouseOptions"
                        v-model="formData.branch_or_warehouse_id"
                    />
                </div>

                <!-- supplier input -->
                <div class="form-element col-12 col-md-6 mb-4">
                    <label>{{ $t('supplier') }}</label>
                    <app-input
                        :class="`${ !$isRtl ? 'margin-right-8 ' : 'margin-left-8' }`"
                        type="search-and-select"
                        :placeholder="$placeholder('supplier')"
                        :options="supplierOptions"
                        v-model="formData.supplier_id"
                    />
                </div>

                <!-- purchase date input -->
                <div class="form-element col-12 col-md-6 mb-4">
                    <label>{{ $t('purchase_date') }}</label>
                    <app-input
                        :class="`${ !$isRtl ? 'margin-right-8 ' : 'margin-left-8' }`"
                        type="date"
                        :placeholder="$placeholder('date')"
                        v-model="formData.purchase_date"
                    />
                </div>

                <!-- purchase status input -->
                <div class="form-element col-12 col-md-6 mb-4">
                    <label>{{ $t('purchase_status') }}</label>
                    <app-input
                        :class="`${ !$isRtl ? 'margin-right-8 ' : 'margin-left-8' }`"
                        type="search-and-select"
                        :placeholder="$placeholder('purchase_status')"
                        :options="purchaseStatusOptions"
                        v-model="formData.purchase_status"
                    />
                </div>

                <!-- reference no input -->
                <div class="form-element col-12 col-md-6 mb-4">
                    <label>{{ $t('lot_reference_number') }}</label>
                    <app-input
                        :class="`${ !$isRtl ? 'margin-right-8 ' : 'margin-left-8' }`"
                        type="number"
                        :placeholder="$placeholder('lot_reference_number')"
                        v-model="formData.reference_no"
                    />
                </div>

                <!-- file input -->
                <div class="form-element col-12 mb-4">
                    <label>{{ $t('upload_csv_file') }}</label>
                    <app-input
                        :class="`${ !$isRtl ? 'margin-right-8 ' : 'margin-left-8' }`"
                        type="dropzone"
                        v-model="formData.csv_file"
                    />
                    <p class="mt-1">
                        <span class="text-muted pr-5">{{ $t('allowed_file_types_csv') }}</span>
                        <a href="#" class="text-underline" @click="sampleDownload">{{ $t("download_sample_file") }}</a>
                    </p>
                </div>

                <!-- note input -->
                <div class="form-element col-12">
                    <label>{{ $t('note') }}</label>
                    <app-input
                        :class="`${ !$isRtl ? 'margin-right-8 ' : 'margin-left-8' }`"
                        type="textarea"
                        :placeholder="$placeholder('note')"
                        v-model="formData.note"
                    />
                </div>
                <div class="col-12 col-md-6 mt-5">
                    <button class="btn btn-secondary mr-3">{{ $t('cancel') }}</button>
                    <button class="btn btn-primary" @click.prevent="handleUpload">{{ $t('upload') }}</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import HelperMixin from "../../../../../common/Mixin/Global/HelperMixin";
import DatatableHelperMixin from "../../../../../common/Mixin/Global/DatatableHelperMixin";
import {urlGenerator} from "../../../../../common/Helper/AxiosHelper";
import {FormMixin} from "../../../../../core/mixins/form/FormMixin";

export default {
    name: "ImportStock",
    mixins: [DatatableHelperMixin, HelperMixin, FormMixin],
    data() {
        return {
            formData: {
                branch_or_warehouse_id: '',
                supplier_id: '',
                purchase_date: new Date(),
                purchase_status: '',
                reference_no: '',
                csv_file: [],
                note: '',
            },
            warehouseOptions: {
                url: urlGenerator("app/selectable-warehouses"),
                query_name: "search_by_name",
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                modifire: ({id, name: value}) => ({id, value}),
            },
            supplierOptions: {
                url: urlGenerator("app/selectable-suppliers"),
                query_name: "search_by_name",
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                modifire: ({id, name: value}) => ({id, value}),
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
        }
    },
    methods: {
        formatDate(dateObj) {
            const date = {year: dateObj.getFullYear(), month: dateObj.getMonth() + 1, day: dateObj.getDate()}
            return `${date.year}-${date.month <= 9 ? '0' : ''}${date.month}-${date.day <= 9 ? '0' : ''}${date.day}`;
        },
        handleUpload() {
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

                formData.append('import_stock', this.formData.csv_file[0]);
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
    }
}
</script>
