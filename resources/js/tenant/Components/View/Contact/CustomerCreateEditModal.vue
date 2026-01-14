<template>
    <modal id="customer-modal"
           v-model="showModal"
           :loading="loading"
           :preloader="preloader"
           :title="generateModalTitle('customer')" size="extra-large"
           @submit="submitData">
        <form ref="form" :class="`${ $isRtl ? 'text-left' : '' }`"
              :data-url='selectedUrl ? selectedUrl : CUSTOMERS'
              @submit.prevent="submitData">

            <h5 class="border-bottom mb-4 pb-2">{{ $t('personal_information') }}</h5>
            <div class="row">
                <div class="col-12 col-sm-6 col-md-4">
                    <app-form-group
                        v-model="formData.first_name"
                        :error-message="$errorMessage(errors, 'first_name')"
                        :label="$t('first_name')"
                        :placeholder="$placeholder($t('first_name'))"
                        :required="true">
                    </app-form-group>
                </div>
                <div class="col-12 col-sm-6 col-md-4">
                    <app-form-group
                        v-model="formData.last_name"
                        :error-message="$errorMessage(errors, 'last_name')"
                        :label="$t('last_name')"
                        :placeholder="$placeholder($t('last_name'))"
                    />
                </div>
                <div class="customer-group-input mb-3 col-md-4">
                    <label>{{ $t('customer_group') }}</label>
                    <app-input
                        :class="`${ !$isRtl ? 'margin-right-8' : 'margin-left-8' }`"
                        type="search-and-select"
                        :key="customerGroupInputKey"
                        :placeholder="$t('search_and_select', {name: $t('customer_group').toLowerCase()})"
                        :options="customerGroupOptions"
                        :inputclearable="false"
                        v-model="formData.customer_group_id"
                        :error-message="$errorMessage(errors, 'customer_group_id')"
                    />
                </div>
            </div>

            <div class="mb-4">
                <label>{{ $t('email_s') }}</label>

                <div v-for="(rowData, index) in inputEmailRow" :key="index" class="row mb-2">
                    <div class="col-md-8">
                        <app-input
                            v-model="rowData.email"
                            :id="'email'+index"
                            :error-message="$errorMessage(errors, 'email_details.'+index+'.email')"
                            :placeholder="$placeholder($t('email'))"
                        />
                    </div>
                    <div class="col-md-3">
                        <app-input
                            v-model="rowData.email_type"
                            :id="'email_type'+index"
                            :error-message="$errorMessage(errors, 'email_type')"
                            :list="typeList"
                            list-value-field="name"
                            type="select"
                        />
                    </div>
                    <div class="col-md-1">
                        <button v-if="(index+1) === inputEmailRow.length" :key="'plus'+index"
                                class="btn default-base-color btn-block"
                                type="button"
                                @click="appendEmailRow"
                        >
                            <app-icon class="size-19 primary-text-color" name="plus"/>
                        </button>
                        <button v-else :key="'trash'+index"
                                class="btn default-base-color btn-block"
                                type="button"
                                @click="removeEmailRow(index)"
                        >
                            <app-icon class="size-19 primary-text-color" name="trash"/>
                        </button>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label>{{ $t('phone_number_s') }}</label>
                <div v-for="(rowData, index) in inputPhoneNumberRow" :key="index" class="row mb-2">
                    <div class="col-md-8">
                        <app-input
                            v-model="rowData.phone_number"
                            type="number"
                            :id="'phone_number'+index"
                            :error-message="$errorMessage(errors, 'phone_number_details.'+ index +'.phone_number')"
                            :placeholder="$placeholder($t('phone_number'))"
                        />
                    </div>
                    <div class="col-md-3">
                        <app-input
                            v-model="rowData.phone_number_type"
                            :id="'phone_number_type'+index"
                            :error-message="$errorMessage(errors, 'phone_number_type')"
                            :list="typeList"
                            list-value-field="name"
                            type="select"
                        />
                    </div>
                    <div class="col-md-1">
                        <button v-if="(index+1) === inputPhoneNumberRow.length" :key="'plus'+index"
                                class="btn default-base-color btn-block"
                                type="button"
                                @click="appendPhoneNumberRow"
                        >
                            <app-icon class="size-19 primary-text-color" name="plus"/>
                        </button>
                        <button v-else :key="'trash'+index"
                                class="btn default-base-color btn-block"
                                type="button"
                                @click="removePhoneNumberRow(index)"
                        >
                            <app-icon class="size-19 primary-text-color" name="trash"/>
                        </button>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label>{{ $t('tin') }}</label>
                    <app-input
                        v-model="formData.tin"
                        :error-message="$errorMessage(errors, 'tin')"
                        type="number"
                        :placeholder="$placeholder($t('tin'))"
                    />
                </div>
            </div>

            <h5 class="border-bottom mb-4 pb-2">{{ $t('address') }}</h5>
            <div v-for="(rowData, index) in inputAddressRow" class="mb-2">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <app-input
                            v-model="inputAddressRow[index].name"
                            :id="'title'+index"
                            :error-message="$errorMessage(errors, 'address_information_details.'+index+'.name')"
                            :placeholder="$placeholder('title')"
                        />
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <app-input
                            v-model="inputAddressRow[index].details"
                            :id="'details'+index"
                            :error-message="$errorMessage(errors, 'address_information_details.'+index+'.details')"
                            :placeholder="$placeholder($t('address_details'))"
                            type="textarea"
                        />
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>{{ $t('country') }}</label>
                        <app-input
                            :class="`${ !$isRtl ? 'margin-right-8' : 'margin-left-8' }`"
                            type="select"
                            :list="countryList"
                            :key="countryList.length"
                            list-value-field="name"
                            v-model="inputAddressRow[index].country_id"
                            :error-message="$errorMessage(errors, 'country')"
                        />
                    </div>
                    <div class="col-md-6">
                        <label>{{ $t('City') }}</label>
                        <app-input
                            v-model="inputAddressRow[index].city"
                            type="text"
                            :id="'city'+index"
                            :error-message="$errorMessage(errors, 'city')"
                            :placeholder="$placeholder($t('city'))"
                        />
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>{{ $t('area') }}</label>
                        <app-input
                            v-model="inputAddressRow[index].area"
                            type="text"
                            :id="'area'+index"
                            :error-message="$errorMessage(errors, 'area')"
                            :placeholder="$placeholder($t('area'))"
                        />
                    </div>

                    <div class="col-md-6">
                        <label>{{ $t('zip_code') }}</label>
                        <app-input
                            v-model="inputAddressRow[index].zip_code"
                            type="number"
                            :id="'zip_code'+index"
                            :error-message="$errorMessage(errors, 'zip_code')"
                            :placeholder="$placeholder($t('zip_code'))"
                        />
                    </div>
                </div>
            </div>
        </form>
    </modal>
</template>
<script>
import FormHelperMixins from "../../../../common/Mixin/Global/FormHelperMixins";
import ModalMixin from "../../../../common/Mixin/Global/ModalMixin";
import {CUSTOMERS, SELECTABLE_CUSTOMER_GROUP} from "../../../Config/ApiUrl-CPB";
import {COUNTRIES} from "../../../Config/ApiUrl-CP";
import CoreLibrary from "../../../../core/helpers/CoreLibrary";
import {numberToString} from "../../../../common/Helper/Support/TextHelper";
import {axiosGet, urlGenerator} from "../../../../common/Helper/AxiosHelper";
import {mapGetters} from 'vuex';

export default {
    name: 'CustomerCreateEditModal',
    extends: CoreLibrary,
    mixins: [FormHelperMixins, ModalMixin],
    data() {
        return {
            numberToString,
            customerGroupInputKey: 0,
            customerGroupOptions: {
                url: urlGenerator("app/selectable-customer-groups"),
                query_name: "search_by_name",
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                modifire: ({id, name: value}) => ({id, value}),
                prefetch: false,
            },
            formData: {
                customer_group_id: '',
                addresses: {},
                contacts: {},
                tin: '',
                tenant_id: ''
            },
            CUSTOMERS,
            SELECTABLE_CUSTOMER_GROUP,
            COUNTRIES,
            typeList: [
                {id: '', name: this.$t('select_type')},
                {id: 'home', name: this.$t('home')},
                {id: 'work', name: this.$t('work')},
                {id: 'office', name: this.$t('office')}
            ],
            countryList: [],
            inputEmailRow: [],
            removableEmailRow: true,
            inputPhoneNumberRow: [],
            removablePhoneNumberRow: true,
            inputAddressRow: [
                {
                    name: "",
                    city: "",
                    country_id: "",
                    zip_code: "",
                    area: "",
                    details: "",
                }
            ],
            removableAddressRow: true,
        }
    },
    created() {
        if (!this.selectedUrl) this.setDefaultGroupId();
    },
    watch: {
        inputEmailRow() {
            this.removableEmailRow = this.inputEmailRow.length <= 1;
        },
        inputPhoneNumberRow() {
            this.removablePhoneNumberRow = this.inputPhoneNumberRow.length <= 1;
        },
    },
    mounted() {
        this.appendEmailRow();
        this.appendPhoneNumberRow();
        this.getCountries();
        this.formData.tenant_id = this.getTenantId;
    },
    computed: {
        ...mapGetters(["getTenantId"])
    },
    methods: {
        getCountries() {
            axiosGet(this.COUNTRIES)
                .then(data => this.countryList = [{name: this.$t('select_country')}, ...data.data ]);
        },
        submitData() {
            this.fieldStatus.isSubmit = true;
            this.loading = true;
            this.message = '';
            this.errors = {};

            this.formData.email_details = this.inputEmailRow.map((item) => ({
                    'email': item.email,
                    'email_type': item.email_type,
                })
            );
            this.formData.email = this.formData.email_details[0].email;

            this.formData.phone_number_details = this.inputPhoneNumberRow.map((item) => ({
                    'phone_number': item.phone_number,
                    'phone_number_type': item.phone_number_type,
                })
            );

            const existingAddresses = this.formData.addresses.length ?  this.formData.addresses.filter(address => address.id !== this.inputAddressRow[0].id) : [];

            this.formData.address_information_details =
                [...existingAddresses, ...this.inputAddressRow].map((item) => ({
                    'name': item.name,
                    'country_id': item.country_id,
                    'city': item.city,
                    'zip_code': item.zip_code,
                    'area': item.area,
                    'details': item.details,
                })
            );

            this.save(this.formData);
        },
        afterSuccess({data}) {
            this.formData = {};
            this.$emit('afterSubmitSuccess');
            $('#customer-modal').modal('hide');
            this.$emit('input', false);
            this.toastAndReload(data.message, 'customer-table');
        },
        afterSuccessFromGetEditData(response) {
            this.preloader = false;
            this.formData = response.data;
            if (this.formData.addresses.length) this.inputAddressRow = [this.formData.addresses[0]];
            if (this.formData.contacts.email.length) this.inputEmailRow = this.formData.contacts.email.map((item) => ({
                    email: item.value,
                    email_type: item.type
                })
            );
            if (this.formData.contacts.phone_number.length) this.inputPhoneNumberRow = this.formData.contacts.phone_number.map((item) => ({
                    phone_number: item.value,
                    phone_number_type: item.type
                })
            );
        },
        setDefaultGroupId() {
            this.axiosGet(SELECTABLE_CUSTOMER_GROUP).then((response) => {
                let id;
                response.data.filter((item) => {
                    if (+item.is_default === 1) id = item.id;
                })
                this.formData.customer_group_id = id;
                this.customerGroupInputKey++;
            })
        },
        appendEmailRow() {
            let checkEmptyRows = this.inputEmailRow.filter(line => line.number === null);
            if (checkEmptyRows.length >= 1 && this.inputEmailRow.length > 0) return;
            this.inputEmailRow.push({});
        },
        removeEmailRow(rowId) {
            if (!this.removableEmailRow) this.inputEmailRow.splice(rowId, 1);
        },
        appendPhoneNumberRow() {
            let checkEmptyRows = this.inputPhoneNumberRow.filter(line => line.number === null);
            if (checkEmptyRows.length >= 1 && this.inputPhoneNumberRow.length > 0) return;
            this.inputPhoneNumberRow.push({});
        },
        removePhoneNumberRow(rowId) {
            if (!this.removablePhoneNumberRow) this.inputPhoneNumberRow.splice(rowId, 1);
        },
        removeAddressRow(rowId) {
            if (!this.removableAddressRow) this.inputAddressRow.splice(rowId, 1);
        },
    },
}
</script>
