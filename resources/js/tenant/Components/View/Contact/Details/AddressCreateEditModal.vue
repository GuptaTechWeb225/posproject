<template>
    <modal id="address-modal"
           v-model="showModal"
           size="large"
           :title="generateModalTitle('address')"
           @submit="submitData" :loading="loading"
           :preloader="preloader">
        <form
            v-if="!loading"
            ref="form"
            :data-url='selectedUrl ? selectedUrl : ADDRESS'
            @submit.prevent="submitData">

            <div class="row">
                <div class="col-md-12">
                    <app-form-group
                        :label="$t('title')"
                        :placeholder="$placeholder('address_title')"
                        v-model="formData.name"
                        :required="true"
                        :error-message="$errorMessage(errors, 'name')"
                    />
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <app-form-group
                        :label="$t( 'address_details')"
                        type="textarea"
                        v-model="formData.details"
                        :placeholder="$placeholder('address_details_here')"
                        :required="true"
                        :error-message="$errorMessage(errors, 'details')"/>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <label>{{ $t('country') }}</label>
                    <app-input
                        type="select"
                        :list="countryList"
                        :key="countryList.length"
                        list-value-field="name"
                        v-model="formData.country_id"
                        :error-message="$errorMessage(errors, 'country')"
                    />
                </div>
                <div class="col-md-6">
                    <app-form-group
                        :label="$t( 'city')"
                        :placeholder="$placeholder('city')"
                        v-model="formData.city"
                        :required="true"
                        :error-message="$errorMessage(errors, 'city')"
                    />
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <app-form-group
                        :label="$t('area')"
                        :placeholder="$placeholder('area')"
                        v-model="formData.area"
                        :required="true"
                        :error-message="$errorMessage(errors, 'area')"
                    />
                </div>
                <div class="col-md-6">
                    <app-form-group
                        :label="$t('zip_code')"
                        :placeholder="$placeholder('zip_code')"
                        v-model="formData.zip_code"
                        type="number"
                        :required="true"
                        :error-message="$errorMessage(errors, 'zip_code')"
                    />
                </div>
            </div>
        </form>
    </modal>
</template>

<script>
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import ModalMixin from "../../../../../common/Mixin/Global/ModalMixin";
import DefaultAction from "../../../../../core/components/datatable/DefaultAction";
import {ADDRESS} from "../../../../Config/ApiUrl-CPB";
import {COUNTRIES} from "../../../../Config/ApiUrl-CP";
import CoreLibrary from "../../../../../core/helpers/CoreLibrary";
import {axiosGet, urlGenerator} from "../../../../../common/Helper/AxiosHelper";

export default {
    name: "AddressModal",
    extends: CoreLibrary,
    mixins: [FormHelperMixins, ModalMixin],
    components: {
        DefaultAction,
    },
    props: {
        userId: {},
        userType: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            customerData: '',
            ADDRESS,
            COUNTRIES,
            isModalActive: false,
            countryOptions: {
                url: urlGenerator("app/selectable-countries"),
                query_name: "search_by_name",
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                modifire: ({id, name: value}) => ({id, value}),
            },
            formData: {
                customer_id: this.userType === 'customer' ? this.userId : '',
                country_id: '',
                supplier_id: this.userType === 'supplier' ? this.userId : '',
            },
            countryList: [],
        }
    },
    methods: {
        getCountries() {
            axiosGet("app/selectable-countries")
                .then(data => this.countryList = [{name: this.$t('select_country')}, ...data.data]);
        },
        afterSuccess({data}) {
            this.formData = {
                sales_person: []
            };
            $('#address-modal').modal('hide');
            this.$emit('input', false);
            this.$emit('reload');
            this.toastAndReload(data.message);
        },
        afterSuccessFromGetEditData(response) {
            this.formData = response.data;
            this.axiosGet("app/selectable-countries").then(res => this.countryList = res.data).finally(() => {
                this.preloader = false;
            })
        },
    },
    mounted() {
        this.getCountries();
    },
}
</script>
