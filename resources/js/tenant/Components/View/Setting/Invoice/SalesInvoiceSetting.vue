<template>
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12">
                <form method="post" data-url="app/invoice-settings" ref="form"
                      enctype="multipart/form-data">
                    <div>
                        <fieldset class="form-group mb-5">
                            <div class="row">
                                <div class="col-8 row">
                                    <div class="col-12">
                                        <div v-if="formData.products_sold" class="note note-warning d-flex align-items-center mb-3 p-3">
                                            <app-icon name="alert-circle" class="size-14" />
                                            <p class="text-muted ml-2 mb-0"> {{ $t('certain_pieces_of_information_cannot_be_re-configured_after_products_have_been_sold') }} </p>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group row">
                                            <label class="col-lg-3 mb-lg-0" for="invoiceLogo">
                                                {{ $t('invoice_logo') }} <br>
                                            </label>
                                            <div class="col-lg-8">
                                                <app-input
                                                    id="invoiceLogo"
                                                    v-model="formData.sales_invoice_logo"
                                                    :error-message="$errorMessage(errors, 'sales_invoice_logo')"
                                                    :generate-file-url="false"
                                                    :label="$t('change_logo')"
                                                    type="custom-file-upload"
                                                    @change="handleInvoiceLogoChange"
                                                />
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-12">
                                        <app-form-group
                                            :label="$t('invoice_prefix')"
                                            v-model="formData.sales_invoice_prefix"
                                            :placeholder="$t('invoice_prefix')"
                                            :error-message="$errorMessage(errors, 'sales_invoice_prefix')"
                                            :disabled="formData.products_sold"
                                        />
                                    </div>

                                    <div class="col-12">
                                        <app-form-group
                                            type="number"
                                            :label="$t('invoice_start_from')"
                                            v-model="formData.sales_invoice_starts_from"
                                            :placeholder="$t('invoice_start_from')"
                                            :error-message="$errorMessage(errors, 'sales_invoice_starts_from')"
                                            :disabled="formData.products_sold"
                                        />
                                    </div>

                                    <div class="col-12">
                                        <app-form-group
                                            :label="$t('invoice_suffix')"
                                            v-model="formData.sales_invoice_suffix"
                                            :placeholder="$t('invoice_suffix')"
                                            :error-message="$errorMessage(errors, 'sales_invoice_suffix')"
                                            :disabled="formData.products_sold"
                                        />
                                    </div>


                                    <div class="col-6 d-flex align-items-center justify-content-between mb-3">
                                        <label> {{ $t('auto_generate_receipt') }}: </label>
                                        <button :key="formData.sales_invoice_auto_generate === '1' ? 'check-square' : 'square'" class="btn py-2 px-2 padding-x-10" @click="handleAutoGenerateCheckBoxClick">
                                            <app-icon
                                                class="size-19 primary-text-color"
                                                :name="formData.sales_invoice_auto_generate === '1' ? 'check-square' : 'square'"
                                            />
                                        </button>
                                    </div>

                                    <div class="col-md-6"></div>

                                    <div class="col-6 d-flex align-items-center justify-content-between">
                                        <label> {{ $t('auto_email_receive_to_customer') }}: </label>
                                        <button :key="formData.sales_invoice_auto_email === '1' ? 'check-square' : 'square'" class="btn py-2 px-2 padding-x-10" @click="handleEmailReceiveCheckBoxClick">
                                            <app-icon
                                                class="size-19 primary-text-color"
                                                :name="formData.sales_invoice_auto_email === '1' ? 'check-square' : 'square'"
                                            />
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </fieldset>

                        <div class="form-group mt-5 mb-0">
                            <app-submit-button :loading="loading"
                                               @click="submit"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import {formDataAssigner} from "../../../../Helper/Helper";
import {urlGenerator} from "../../../../Helper/Helper";


export default {
    name: "SalesInvoiceSetting",
    mixins: [FormHelperMixins],
    data() {
        return {
            isImageChange: false,
            errors: {},
            formData: {
                sales_invoice_logo: '',
                sales_invoice_prefix: '',
                sales_invoice_suffix: '',
                sales_invoice_starts_from: '',
            },
            invoiceLogo: false,
        }
    },
    methods: {
        handleInvoiceLogoChange() {
            this.invoiceLogo = true;
            this.isImageChange = true;
        },
        handleAutoGenerateCheckBoxClick() {
            this.formData.sales_invoice_auto_generate = this.formData.sales_invoice_auto_generate === '1' ? "0" : "1";
        },
        handleEmailReceiveCheckBoxClick() {
            this.formData.sales_invoice_auto_email = this.formData.sales_invoice_auto_email === '1' ? "0" : "1";
        },
        afterError(response) {
            this.errors = response.data.errors;
        },
        submit() {
            this.errors = {}
            this.loading = false
            if (this.invoiceLogo === false) {
                delete this.formData.invoice_logo;
            }
            this.formData.sales_invoice_suffix === null ? this.formData.sales_invoice_suffix = '' : '';
            if (!this.isImageChange) delete this.formData.sales_invoice_logo;
            let formData = formDataAssigner(new FormData, this.formData);
            formData.append('type','sales')

            this.save(formData)

        },
        afterSuccess(res) {
            this.$toastr.s(res.data.message);
            this.getInvoiceData();
        },
        getInvoiceData() {
            this.preloader = true;
            this.axiosGet(`app/get-general-settings?invoice_type=sales`).then(({data}) => {
                this.formData = data;
                if (!data.sales_invoice_logo) return;
                this.formData.sales_invoice_logo = (data.sales_invoice_logo === 'null') ? '' : urlGenerator(data.sales_invoice_logo);
            }).finally(() => {
                this.preloader = false;
            })
        }
    },
    created() {
        this.getInvoiceData();
    }
}
</script>

<style scoped>
label {
    margin-top: 12px;
}
</style>
