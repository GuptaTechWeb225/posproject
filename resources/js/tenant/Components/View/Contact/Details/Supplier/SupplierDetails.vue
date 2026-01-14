<template>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-8">
                <app-breadcrumb :page-title="ucWords($t('supplier_details'))"
                                :button="{label: $t('back_to_suppliers'), url: getAppUrl( SUPPLIER_LIST )}"/>
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
                                        <label id="upload-label">{{ $t('change') }}</label>
                                        <app-input
                                            v-model="profile_picture"
                                            :generate-file-url="false"
                                            :label="$t('change')"
                                            name="profile_picture"
                                            type="custom-file-upload"
                                            @change="changeProfile()"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="media-body user-info-header">
                                <h4>
                                    {{ supplierData.name }}
                                </h4>
                                <p class="mt-2 mb-0"><span class="text-muted"> {{ $t('supplier_no') }}: </span>
                                    {{ supplierData.supplier_no }}
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
                                                <p class="text-muted mb-0">{{ $t('total_lot') }}</p>
                                                <p class="mb-0">{{ supplierData.lots_count }}</p>
                                            </div>
                                        </div>
                                        <div class="media mb-4 mb-xl-0">
                                            <div class="align-self-center mr-3">
                                                <app-icon name="mail"/>
                                            </div>
                                            <div class="media-body">
                                                <p class="text-muted mb-0">{{ $t('email_s') }}</p>
                                                <div class="d-flex" v-if="supplierData.emails.length">
                                                    <div>
                                                        <p class="mb-0"
                                                           v-if="index < emailShow"
                                                           v-for="(email, index) in supplierData.emails">
                                                            {{ email.value }}
                                                        </p>
                                                    </div>
                                                    <div
                                                        v-if="emailShow < supplierData.emails.length || supplierData.emails.length > emailShow">
                                                        <a href="#"
                                                           @click="emailShow = supplierData.emails.length"
                                                           class="ml-2"> +
                                                            {{ supplierData.emails.length - 1 }}
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
                                            <p class="mb-0">{{ $t('not_added_yet') }}</p>
                                        </div>
                                    </div>
                                    <div class="media mb-0 mb-xl-0">
                                        <div class="align-self-center mr-3">
                                            <app-icon name="phone-call"/>
                                        </div>
                                        <div class="media-body">
                                            <p class="text-muted mb-0">{{ $t('phone_number(s)') }}</p>
                                            <div class="d-flex"
                                                 v-if="supplierData.phone_numbers.length">
                                                <div>
                                                    <p class="mb-0"
                                                       v-if=" index < phoneNumberShow"
                                                       v-for="(phone, index) in supplierData.phone_numbers">
                                                        {{ phone.value }}
                                                    </p>
                                                </div>
                                                <div
                                                    v-if="phoneNumberShow < supplierData.phone_numbers.length || supplierData.phone_numbers.length > phoneNumberShow">
                                                    <a href="#"
                                                       @click="phoneNumberShow = supplierData.phone_numbers.length"
                                                       class="ml-2">
                                                        + {{ supplierData.phone_numbers.length - 1 }}
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
import CoreLibrary from "../../../../../../core/helpers/CoreLibrary";
import {
    SUPPLIER_LIST,
    SUPPLIERS,
    SUPPLIER_PROFILE_PICTURE_UPLOAD
} from '../../../../../Config/ApiUrl-CPB';
import {axiosGet, axiosPost, urlGenerator} from '../../../../../../common/Helper/AxiosHelper';
import {ucWords} from "../../../../../../common/Helper/Support/TextHelper";
import {FormMixin} from "../../../../../../core/mixins/form/FormMixin";

export default {
    name: "SupplierDetails",
    mixins: [FormMixin],
    extends: CoreLibrary,
    props: {
        supplierId: {},
    },
    data() {
        return {
            supplierData: {},
            ucWords,
            SUPPLIER_LIST,
            SUPPLIERS,
            SUPPLIER_PROFILE_PICTURE_UPLOAD,
            emailShow: 1,
            phoneNumberShow: 1,
            loader: false,
            profile_picture: "",
            formData: {},

            tabs: [
                {
                    'name': this.$t('personal_info'),
                    'component': 'app-supplier-personal-info',
                    'permission': '',
                    'props': this.supplierId,
                },
                {
                    'name': this.$t('address'),
                    'component': 'app-supplier-address',
                    'permission': '',
                    'props': this.supplierId,
                }
            ]
        }
    },
    methods: {
        changeProfile() {
            let formData = new FormData();
            formData.append("profile_picture", this.profile_picture);
            axiosPost(SUPPLIER_PROFILE_PICTURE_UPLOAD + this.supplierId, formData).then((res) => {
                window.location.reload();
            })
        },
        getSupplierInfo() {
            this.loader = true;
            axiosGet(`${SUPPLIERS}/${this.supplierId}`).then((res) => {
                this.supplierData = res.data;
                this.loader = false;
            }).finally(() => {
                this.loader = false;
            })
        },
    },
    created() {
        this.getSupplierInfo();
    },
    computed: {
        profile_picture_link() {
            if (this.supplierData.profile_picture) {
                return this.$optional(this.supplierData, 'profile_picture', 'full_url');
            }
            return urlGenerator('/images/avatar.png')
        }
    },
}
</script>