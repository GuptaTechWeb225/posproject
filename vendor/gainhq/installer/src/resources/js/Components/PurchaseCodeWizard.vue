<template>
    <div class="py-5">

        <h2 class="text-center text-capitalize mb-primary">
            {{ $t('install') }} {{ appName }}
        </h2>
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="progress" style="height: 10px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                         :style="'width:' +calculateBarWidth(1)+'%'" aria-valuenow="0" aria-valuemin="0"
                         aria-valuemax="100"></div>
                </div>
            </div>
        </div>

        <div class="card card-with-shadow border-0 mb-primary">
            <div class="card-header bg-transparent p-primary">
                <h5 class="card-title mb-0 d-flex align-items-center ">
                    <app-icon name="key" class="primary-text-color mr-2"/>
                    {{ $t('purchase_code') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-md-3 mt-2">
                        {{ $t('code') }}
                    </label>
                    <div class="col-md-9">
                        <app-input
                            type="text"
                            v-model="environment.code"
                            :placeholder="$t('code')"
                        />
                        <small class="text-danger validation-error">{{ errors.code }}</small>

                        <div v-if="purchase_code_error_occurs"
                             class="note note-warning p-3 mt-2">
                            <p class="mb-0">{{ errors.note }}</p>
                            <div class="">
                                {{ $t('please_check_the') }}
                                <a class="font-italic text-primary"
                                   href="#"
                                   v-if="isActiveCopy"
                                   @click="showPurchaseErrorModal">{{ $t('error_log') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group mt-5">
            <app-submit-button
                :loading="loading"
                @click="checkPurchaseCode"
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
        <app-purchase-error-modal
            v-if="isActivePurchaseErrorModal"
            :error-logs="error_logs"
            @closeModal="closePurchaseErrorModal"
        />
    </div>
</template>

<script>
import {FormMixin} from "../core/mixins/form/FormMixin";
import PurchaseCodeWizardMixin from "./Mixins/PurchaseCodeWizardMixin";
import Loader from "../core/components/preloders/Loader";
import {ProgressionBarMixin} from "./Mixins/ProgressionBarMixin";

export default {
    name: "PurchaseCodeWizard",
    mixins: [FormMixin, PurchaseCodeWizardMixin, ProgressionBarMixin],
    props: {
        appName: {},
        installerConfig: {
            include_option: {}
        }
    },
    components: {
        'loader': Loader
    },
}
</script>