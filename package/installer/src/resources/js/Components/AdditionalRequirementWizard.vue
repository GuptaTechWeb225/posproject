<template>
    <div class="py-5">

        <h2 class="text-center text-capitalize mb-primary">
            Additional Requirement
        </h2>

        <div class="card card-with-shadow border-0 mb-primary">
            <div class="card-header bg-transparent d-flex align-items-center justify-content-between p-primary">
                <h5 class="card-title mb-0 d-flex align-items-center">
                    <i class="fas fa-link text-primary mr-2"></i>Symlink Requirement
                </h5>
                <a class="text-muted hover-underline" target="_blank" :href="symlinkDocumentation">Need help?</a>
            </div>
            <div class="card-body">
                <div class="row mb-2" v-if="symlinkError">
                    <div class="col-12">
                        <app-note
                            :title="$t('symlink_permission')"
                            content-type="html"
                            :notes="additionalRequirementNote"
                        />
                    </div>
                </div>
                <hr v-if="symlinkError"/>
                <div class="row mt-2">
                    <div class="col-4 text-size-18">Requirement</div>
                    <div class="col-4 text-size-18">Response</div>
                    <div class="col-4 text-size-18">Status</div>
                </div>
                <hr/>

                <div class="row mb-3" v-for="item in additional_requirements">

                    <div class="col-4" :class="item.status ? 'text-success' : 'text-danger'">
                        <span class="text-size-16">{{ item.requirement }}</span>
                    </div>

                    <div class="col-4 text-size-16" :class="item.status ? 'text-success' : 'text-danger'">
                        {{ item.message }}
                    </div>

                    <div class="col-4 text-size-26">
                        <i :class="item.status ? 'text-success fas fa-check-circle' : 'text-danger far fa-times-circle'"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group mt-5">
            <app-submit-button
                :loading="loading"
                @click="next()"
                btn-class="btn-block"
                :label="$t('save_&_next')"
            />
        </div>
        <div class="form-group mt-3">
            <button class="btn btn-light text-center btn-block"
                    type="button"
                    @click.prevent="back()"
                    :disabled="backLoading">
                <span class="w-100">
                    <loader v-if="backLoading" :loader-color="'brand-color'"/>
                    <template v-if="!backLoading">
                         {{ $t('back') }}
                    </template>
                </span>
            </button>
        </div>
    </div>
</template>

<script>
import {ADDITIONAL_REQUIREMENTS, INSTALL, PURCHASE_CODE} from "../Config/ApiUrl";
import Loader from "./preloders/Loader";
import {axiosGet, urlGenerator} from "../Helpers/AxiosHelper";

export default {
    name: "AdditionalRequirementWizard",
    props: {
        appName: {},
        symlinkDocumentation: {},
    },
    components: {
        'loader': Loader
    },
    data() {
        return {
            additional_requirements: [],
            loading: false,
            backLoading: false,
            additionalRequirementNote: '',
            symlinkError: false
        }
    },
    created() {
        this.getAdditionalRequirements();
    },
    methods: {
        getAdditionalRequirements() {
            this.loading = true;
            axiosGet(ADDITIONAL_REQUIREMENTS).then((res) => {
                this.additional_requirements = res.data.data;
                if (!this.additional_requirements?.symlink?.status) {
                    this.symlinkError = true;
                    this.additionalRequirementNote = '<ul class=\'mb-0\'>' +
                        '<li>' + this.$t('symlink_error') + '</li>' +
                        '<li>' + this.$t('symlink_warning_1') + '</li>' +
                        '<li>' + this.$t('symlink_warning_2') + '</li>' +
                        '<li>' + this.$t('symlink_warning_3') + '</li>' +
                        '<li>' + '<kbd>' + 'ln -s ' +
                        this.additional_requirements?.symlink?.optional?.symlink?.target_link +
                        ' ' +
                        this.additional_requirements?.symlink?.optional?.symlink?.storage_link + '</kbd>' +
                        '</li>' +
                        '</ul>';
                }
                this.loading = false;
            });
        },
        next() {
            this.loading = true;
            this.$toastr.s('', this.$t('requirement_checked_successfully'));
            setTimeout(() => {
                window.location = urlGenerator(PURCHASE_CODE);
            }, 1000);
        },
        back() {
            this.backLoading = true;
            window.location = urlGenerator(INSTALL);
        },
    },
}
</script>
