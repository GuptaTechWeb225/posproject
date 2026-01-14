<template>
    <div>
        <app-modal
            modal-alignment="top"
            modal-id="importPreviewModal"
            modal-size="fullscreen"
        >
            <template slot="header">
                <h5 id="exampleModalLabel" class="modal-title">{{ $t('preview_import') }}</h5>
                <button aria-label="Close" class="close outline-none" data-dismiss="modal" type="button"
                        @click="$emit('close')">
                    <span> <app-icon name="x"></app-icon> </span>
                </button>
            </template>

            <template slot="body">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-6">
                                <h5> {{ $t('filtered_supplier') }} </h5>
                            </div>
                            <div class="col-6 text-right">
                                <app-button
                                    :is-disabled="false"
                                    :label="$t('download')"
                                    className="btn btn-primary text-center"
                                    @submit="download_csv"
                                />
                            </div>
                            <div
                                v-for="(column, parentIndex) in getExactColumns"
                                :class="`col-${columnWidth(parentIndex)}`"
                            >
                                <label>{{ $t(column) }}</label>
                                <template v-for="(supplier, index) in suppliersData.filtered">
                                    <app-input
                                        v-if="column in supplier['errorBag']"
                                        :id="`filtered-${index}-${column}`"
                                        :key="index"
                                        v-model="supplier[column]"
                                        :error-message="$errorMessage(supplier['errorBag'], column)"
                                    />
                                    <app-input
                                        v-else
                                        :id="`filtered-${index}-${column}`"
                                        :key="index"
                                        v-model="supplier[column]"
                                        class="bottomFix"
                                    />
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <template slot="footer">
                <button
                    class="btn btn-secondary mr-4"
                    data-dismiss="modal"
                    type="button"
                    @click="$emit('close')">{{ $t('close') }}
                </button>
                <button
                    class="btn btn-primary"
                    type="button"
                    @click="submitData">{{ $fieldTitle('save') }}
                </button>
            </template>
        </app-modal>
    </div>
</template>

<script>

import {SUPPLIER_STORE} from "../../../../Config/ApiUrl-CPB";

export default {
    name: "ImportSupplierPreviewModal",
    props: {
        suppliers: {
            default() {
                return {}
            }
        },
    },
    data() {
        return {
            suppliersData: [],
            type: 'error',
            message: '',
        }
    },

    methods: {
        submitData() {
            const formData = {
                suppliers: [...this.filteredSuppliers()],
            }
            axios.post(SUPPLIER_STORE, formData).then(({data}) => {
                if (typeof data === 'object') {
                    this.suppliersData = data
                    $('#importPreviewModal').modal('show');

                } else {
                    $('#importPreviewModal').modal('hide');
                }

                this.$emit('succeed', data)
            })
                .catch(({response}) => {
                    this.message = response.data.message
                    $(`.${this.type}`).toast('show');
                })
        },

        filteredSuppliers() {
            return [...this.suppliersData.filtered].map(supplier => {
                let data = {...supplier};
                delete data.errorBag;
                return data;
            });
        },

        download_csv() {

            let csv = this.getExactColumns.join(',') + "\n";

            [...this.suppliers.filtered].forEach((row) => {

                this.getExactColumns.forEach(column => {
                    if (column in row['errorBag']) {
                        csv += `${row[column]} (${row['errorBag'][column]}),`
                    } else {
                        csv += row[column] + ","
                    }
                })

                csv += "\n"
            });

            this.$parent.downloadCSV(csv);
        },

        columnWidth(index) {
            const length = this.getExactColumns.length
            if (length > 6) {
                if (index > 2) {
                    return 1;
                }
            }
            return Math.ceil(12 / length)
        }
    },
    computed: {
        supplierObjectWatcher() {
            return Object.keys(this.suppliers).length;
        },

        getExactColumns() {
            return this.suppliers.columns;
        }
    },
    watch: {
        'supplierObjectWatcher': {
            handler: function () {
                this.suppliersData = {...this.suppliers}
            },
            immediate: true
        }
    },
}
</script>

<style scoped>
.bottomFix {
    height: 62px;
}
</style>
