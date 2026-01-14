<template>
    <div class="row">
        <div class="col-lg-9">
            <form
                ref="form"
                class="mb-0 position-relative"
                :class="{'loading-opacity': loading}"
                :data-url='selectedUrl ? selectedUrl : SUPPLIERS'
                @submit.prevent="submitData">
                <app-overlay-loader v-if="loading"/>

                <div class="form-group row">
                    <div class="col-md-6">
                        <app-form-group
                            :label="$t('supplier_name')"
                            :placeholder="$placeholder('supplier_name')"
                            v-model="formData.name"
                            form-group-class="mb-md-0"
                            :required="true"
                            :error-message="$errorMessage(errors, 'name')"
                        />
                    </div>
                    <div class="col-md-6">
                        <app-form-group
                            :label="$t('supplier_no')"
                            :placeholder="$placeholder('supplier_no')"
                            v-model="formData.supplier_no"
                            form-group-class="mb-0"
                            :required="true"
                            :error-message="$errorMessage(errors, 'supplier_no')"
                        />
                    </div>
                </div>

                <div class="form-group">
                    <label>{{ $t('email') }}(s)</label>
                    <div v-for="(rowData, index) in inputEmailRow"
                         :key="index"
                         class="row"
                         :class="{'form-group': index !== inputEmailRow.length - 1}">
                        <div class="col-md-6 col-lg-6">
                            <app-input
                                class="mb-2 mb-sm-2 mb-md-0"
                                v-model="rowData.email"
                                :error-message="$errorMessage(errors, 'email')"
                                :placeholder="$placeholder('email')"
                            />
                        </div>
                        <div class="col-md-4 col-lg-5">
                            <app-input
                                class="mb-2 mb-sm-2 mb-md-0"
                                v-model="rowData.email_type"
                                :error-message="$errorMessage(errors, 'email_type')"
                                :list="typeList"
                                list-value-field="name"
                                type="select"
                            />
                        </div>
                        <div class="col-md-2 col-lg-1">
                            <div class="text-right h-100">
                                <button v-if="(index+1) === inputEmailRow.length" :key="'plus'+index"
                                        class="btn default-base-color"
                                        type="button"
                                        @click="addEmailRow">
                                    <app-icon class="size-19 primary-text-color" name="plus"/>
                                </button>
                                <button v-else :key="'trash'+index"
                                        class="btn default-base-color"
                                        type="button"
                                        @click="removeEmailRow(index)">
                                    <app-icon class="size-19 primary-text-color" name="trash"/>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>{{ $t('phone_number') }}(s)</label>
                    <div v-for="(rowData, index) in inputPhoneNumberRow"
                         :key="index"
                         class="row"
                         :class="{'form-group': index !== inputPhoneNumberRow.length - 1}">
                        <div class="col-md-6 col-lg-6">
                            <app-input
                                class="mb-2 mb-sm-2 mb-md-0"
                                v-model="rowData.phone_number"
                                :error-message="$errorMessage(errors, 'phone_number')"
                                :placeholder="$placeholder('phone_number')"
                            />
                        </div>
                        <div class="col-md-4 col-lg-5">
                            <app-input
                                class="mb-2 mb-sm-2 mb-md-0"
                                v-model="rowData.phone_number_type"
                                :error-message="$errorMessage(errors, 'type')"
                                :list="typeList"
                                list-value-field="name"
                                type="select"
                            />
                        </div>
                        <div class="col-md-2 col-lg-1">
                            <div class="text-right h-100">
                                <button v-if="(index+1) === inputPhoneNumberRow.length" :key="'plus'+index"
                                        class="btn default-base-color"
                                        type="button"
                                        @click="addPhoneNumberRow">
                                    <app-icon class="size-19 primary-text-color" name="plus"/>
                                </button>
                                <button v-else :key="'trash'+index"
                                        class="btn default-base-color"
                                        type="button"
                                        @click="removePhoneNumberRow(index)">
                                    <app-icon class="size-19 primary-text-color" name="trash"/>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-primary">
                    <button class="btn btn-primary" type="button" @click.prevent="submitData">
                        {{ $t('save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import FormHelperMixins from "../../../../../../common/Mixin/Global/FormHelperMixins";
import DefaultAction from "../../../../../../core/components/datatable/DefaultAction";

import {SUPPLIERS} from "../../../../../Config/ApiUrl-CPB";
import {axiosGet, axiosPatch} from "../../../../../../common/Helper/AxiosHelper";

export default {
    name: "SupplierPersonalInfo",
    mixins: [FormHelperMixins],
    props: {
        props: {
            supplierId: {},
        },
    },
    components: {
        DefaultAction
    },
    data() {
        return {
            loading: true,
            SUPPLIERS,
            formData: {
                name: ''
            },
            actions: [
                {
                    title: this.$t('delete'),
                    icon: 'trash-2',
                    type: 'delete',
                    className: 'btn-dark text-primacy'
                }
            ],
            actionsAdd: [
                {
                    title: this.$t('add'),
                    icon: 'plus',
                    type: 'add',
                    className: 'text-primary',
                }
            ],
            typeList: [
                {
                    id: '',
                    name: this.$t('select_type'),
                },
                {
                    id: 'home',
                    name: this.$t('home'),
                },
                {
                    id: 'work',
                    name: this.$t('work'),
                },
                {
                    id: 'office',
                    name: this.$t('office'),
                }
            ],
            inputEmailRow: [],
            removableEmailRow: true,
            inputPhoneNumberRow: [],
            removablePhoneNumberRow: true,
            inputAddressRow: [],
            removableAddressRow: true,
        }
    },
    created() {
        axiosGet(`${SUPPLIERS}/${this.props}`).then((response) => {
            this.formData = response.data;
            if (this.formData.emails.length > 0) {
                this.inputEmailRow = this.formData.emails.map((item) => {
                    return {
                        email: item.value,
                        email_type: item.type
                    }
                });
            }

            if (this.formData.phone_numbers.length > 0) {
                this.inputPhoneNumberRow = this.formData.phone_numbers.map((item) => {
                    return {
                        phone_number: item.value,
                        phone_number_type: item.type
                    }
                });
            }
        }).catch((error) => {
            this.$toastr.e(error);
        }).finally(() => {
            this.loading = false;
        });
    },
    watch: {
        inputEmailRow() {
            this.removableEmailRow = this.inputEmailRow.length <= 1;
        },
        inputPhoneNumberRow() {
            this.removablePhoneNumberRow = this.inputPhoneNumberRow.length <= 1;
        },
        inputAddressRow() {
            this.removableAddressRow = this.inputAddressRow.length <= 1;
        },
        supplier: {
            handler: function (supplier) {
                this.formData = supplier;
            }
        }
    },
    mounted() {
        this.addEmailRow();
        this.addPhoneNumberRow();
    },
    methods: {
        submitData() {
            if (!this.formData.name) return this.$toastr.e('', this.$t('please_enter_the_supplier_name'));
            if (!this.formData.supplier_no) return this.$toastr.e('', this.$t('please_enter_the_supplier_no'));
            this.fieldStatus.isSubmit = true;
            this.loading = true;
            this.message = '';
            this.errors = {};

            this.formData.email_details = this.inputEmailRow.map((item) => {
                return {
                    'email': item.email,
                    'email_type': item.email_type,
                }
            });

            this.formData.phone_number_details = this.inputPhoneNumberRow.map((item) => {
                return {
                    'phone_number': item.phone_number,
                    'phone_number_type': item.phone_number_type,
                }
            });

            axiosPatch(`${SUPPLIERS}/${this.props}`, this.formData)
                .then(() => {
                    setTimeout(() => {
                        window.location.reload()
                    }, 2500)
                })
                .catch(err => this.$toastr.e('', err.data.message))
        },

        afterSuccess(response) {
            this.$toastr.s('', response.data.message);
            setTimeout('', location.reload());
        },

        addEmailRow() {
            let checkEmptyRows = this.inputEmailRow.filter(
                line => line.number === null
            );
            if (checkEmptyRows.length >= 1 && this.inputEmailRow.length > 0) return;
            this.inputEmailRow.push({
                email: '',
                email_type: '',
            });
        },

        removeEmailRow(rowId) {
            if (!this.removableEmailRow) {
                this.inputEmailRow.splice(rowId, 1);
            }
        },

        addPhoneNumberRow() {
            let checkEmptyRows = this.inputPhoneNumberRow.filter(
                line => line.number === null
            );
            if (checkEmptyRows.length >= 1 && this.inputPhoneNumberRow.length > 0) return;
            this.inputPhoneNumberRow.push({
                phone_number: null,
            });
        },

        removePhoneNumberRow(rowId) {
            if (!this.removablePhoneNumberRow) {
                this.inputPhoneNumberRow.splice(rowId, 1);
            }
        },

        addAddress() {
            this.addAddressRow()
        },
        addAddressRow() {
            let checkEmptyRows = this.inputAddressRow.filter(
                line => line.number === null
            );
            if (checkEmptyRows.length >= 1 && this.inputAddressRow.length > 0) return;
            this.inputAddressRow.push({
                address_title: null,
            });
        },

        removeAddressRow(rowId) {
            if (!this.removableAddressRow) {
                this.inputAddressRow.splice(rowId, 1);
            }
        },
    },
}
</script>