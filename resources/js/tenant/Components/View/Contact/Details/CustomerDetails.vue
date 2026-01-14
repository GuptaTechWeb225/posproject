<template>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-8">
                <app-breadcrumb :page-title="ucWords($t('customer_details'))"
                                :button="{label: $t('back_to_customers'), url: getAppUrl( CUSTOMER_LIST )}"/>
            </div>
            <div class="col-sm-4">
                <div class="text-sm-right">
                </div>
            </div>
        </div>

        <div class="user-profile mb-primary">
            <div class="card card-with-shadow py-5 border-0" style="min-height: 200px;">
                <app-overlay-loader v-if="loader"/>
                <div v-else class="row">
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-5">
                        <div class="media border-right px-5 pr-xl-5 pl-xl-0 user-header-media">
                            <div class="profile-pic-wrapper">
                                <div class="custom-image-upload-wrapper circle mx-xl-auto">
                                    <div class="image-area d-flex">
                                        <img id="imageResult"
                                             :src="profile_picture_link"
                                             alt=""
                                             class="img-fluid mx-auto my-auto">
                                    </div>
                                    <div class="input-area">
                                        <label id="upload-label" for="upload">{{ $t('change') }}</label>
                                        <input id="upload"
                                               @change="readURL"
                                               type="file"
                                               ref="changeProfileImage"
                                               class="form-control d-none">
                                    </div>
                                </div>
                            </div>
                            <div class="media-body user-info-header">
                                <h4>
                                    {{ customerData.full_name }}
                                </h4>
                                <p class="mt-2 mb-0"><span class="text-muted"> {{ $t('group_name') }}: </span>
                                    {{ customerData.customer_group ? customerData.customer_group.name : '' }}
                                </p>
                                <p class="mt-2 mb-0"><span class="text-muted">{{ $t('tin_number') }}: </span>
                                    {{ customerData.tin ? customerData.tin : $t('not_added_yet') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-7">
                        <div
                            class="user-details px-5 px-sm-5 px-md-5 px-lg-0 px-xl-0 mt-5 mt-sm-5 mt-md-0 mt-lg-0 mt-xl-0">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                                    <div class="border-right custom">
                                        <div class="media mb-4 mb-xl-5">
                                            <div class="align-self-center mr-3">
                                                <app-icon name="gift"/>
                                            </div>
                                            <div class="media-body">
                                                <p class="text-muted mb-0">{{ $t('total_orders') }}</p>
                                                <p class="mb-0">{{ numberWithCurrencySymbol(customerData.total_order) }}</p>
                                            </div>
                                        </div>
                                        <div class="media mb-4 mb-xl-0">
                                            <div class="align-self-center mr-3">
                                                <app-icon name="mail"/>
                                            </div>
                                            <div class="media-body">
                                                <p class="text-muted mb-0">{{ $t('email_s') }}</p>
                                                <div class="d-flex" v-if="$optional(customerData, 'contacts','email')">
                                                    <div>
                                                        <p class="mb-0"
                                                           v-if="index < emailShow"
                                                           v-for="(email, index) in customerData.contacts.email ">
                                                            {{ email.value }}
                                                        </p>
                                                    </div>
                                                    <div
                                                        v-if="emailShow < customerData.contacts.email.length || customerData.contacts.email.length > emailShow">
                                                        <a href="#"
                                                           @click="emailShow = customerData.contacts.email.length"
                                                           class="ml-2"> +
                                                            {{ customerData.contacts.email.length - 1 }}
                                                            {{ $t('more') }}
                                                        </a>
                                                    </div>
                                                </div>
                                                <div v-else>{{ $t('not_added_yet') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
                                    <div class="media mb-4 mb-xl-5">
                                        <div class="align-self-center mr-3">
                                            <app-icon name="dollar-sign"/>
                                        </div>
                                        <div class="media-body">
                                            <p class="text-muted mb-0">{{ $t('total_due') }}</p>
                                            <p class="mb-0">{{ numberWithCurrencySymbol(customerData.total_due) }}</p>
                                        </div>
                                    </div>
                                    <div class="media mb-0 mb-xl-0">
                                        <div class="align-self-center mr-3">
                                            <app-icon name="phone-call"/>
                                        </div>
                                        <div class="media-body">
                                            <p class="text-muted mb-0">{{ $t('phone_number(s)') }}</p>
                                            <div class="d-flex"
                                                 v-if="customerData.contacts && customerData.contacts.phone_number">
                                                <div>
                                                    <p class="mb-0"
                                                       v-if=" index < phoneNumberShow"
                                                       v-for="(phone, index) in customerData.contacts.phone_number">
                                                        {{ phone.value }}
                                                    </p>
                                                </div>
                                                <div
                                                    v-if="phoneNumberShow < customerData.contacts.phone_number.length || customerData.contacts.phone_number.length > phoneNumberShow">
                                                    <a href="#"
                                                       @click="phoneNumberShow = customerData.contacts.phone_number.length"
                                                       class="ml-2">
                                                        + {{ customerData.contacts.phone_number.length - 1 }}
                                                        {{ $t('more') }}
                                                    </a>
                                                </div>
                                            </div>
                                            <div v-else>{{ $t('not_added_yet') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <app-tab type="horizontal" :tabs="tabs"/>
    </div>
</template>

<script>
import {mapState} from 'vuex';
import CoreLibrary from "../../../../../core/helpers/CoreLibrary";
import {CUSTOMER_LIST, CUSTOMER_PROFILE_PICTURE_UPLOAD} from '../../../../Config/ApiUrl-CPB';
import {axiosPost, urlGenerator} from '../../../../../common/Helper/AxiosHelper';
import {ucWords} from "../../../../../common/Helper/Support/TextHelper";
import {numberWithCurrencySymbol} from "../../../../Helper/Helper";

export default {
    name: "CustomerDetails",
    extends: CoreLibrary,
    props: {
        customerId: {},
    },
    data() {
        return {
            customerData: {
                customer_group: '',
                tin: '',
            },
            ucWords,
            CUSTOMER_LIST,
            CUSTOMER_PROFILE_PICTURE_UPLOAD,
            emailShow: 1,
            phoneNumberShow: 1,
            profile_picture: '',
            tabs: [
                {
                    'name': this.$t('personal_info'),
                    'component': 'app-customer-personal-info',
                    'permission': '',
                    'props': this.customerId,
                },
                {
                    'name': this.$t('address'),
                    'component': 'app-customer-address',
                    'permission': '',
                    'props': this.customerId,
                },
                {
                    'name': this.$t('order_list'),
                    "component": "app-customer-order-list",
                    'permission': '',
                    "props": this.customerId,
                }
            ]
        }
    },
    mounted() {
        this.$store.dispatch('getCustomer', this.customerId)
    },
    computed: {
        ...mapState({
            loader: state => state.loading,
            customer: state => state.customer.customer,
        }),
        profile_picture_link() {
            if (this.customerData.profile_picture) {
                return this.$optional(this.customerData, 'profile_picture', 'full_url');
            }
            return urlGenerator('/images/avatar.png')
        }
    },
    watch: {
        watch: {
            customerDetails: {
                handler: function (customer) {
                    this.customerData = customer;
                    if (customer.full_name) {
                        let title = document.title.split('-');
                        title[0] = customer.full_name;
                        document.title = title.join(' - ');
                    }
                },
                deep: true
            }
        },
        customer: {
            handler: function (customer) {
                this.customerData = customer;
            }
        }
    },
    methods: {
        numberWithCurrencySymbol,
        readURL() {
            this.files = this.$refs.changeProfileImage.files;
            if (this.files && this.files[0]) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    $('#imageResult').attr('src', e.target.result);
                };
                let image = reader.readAsDataURL(this.files[0]);
                let formData = new FormData;
                formData.append('profile_picture', this.files[0]);
                formData.append('user_id', this.customerId);
                axiosPost(CUSTOMER_PROFILE_PICTURE_UPLOAD + this.customerId, formData).then(response => {
                    location.reload();
                }).catch(error => {
                    this.$toastr.e(error.response.data ? error.response.data.profile_picture[0] : '');
                });
            }
        },
    }
}
</script>
