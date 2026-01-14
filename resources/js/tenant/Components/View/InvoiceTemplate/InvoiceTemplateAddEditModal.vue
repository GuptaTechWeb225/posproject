<template>
    <modal id="invoice-template-modal"
           v-model="showModal"
           size="extra-large"
           :title="generateModalTitle('invoice_template')"
           @submit="submitData" :loading="loading"
           :preloader="preloader">
        <form
            ref="form"
            :data-url='selectedUrl ? selectedUrl : INVOICE_TEMPLATE'
            @submit.prevent="submitData">

            <app-form-group
                :label="$t('name')"
                :placeholder="$placeholder('name')"
                v-model="formData.name"
                :required="true"
                :error-message="$errorMessage(errors, 'name')">
            </app-form-group>

            <div class="row">
                <app-form-group
                    type="select"
                    :label="$t('type')"
                    list-value-field="value"
                    class="col-8"
                    v-model="formData.type"
                    @input="handleTypeInput"
                    :chooseLabel="$t('select_type')"
                    :error-message="$errorMessage(errors, 'type')"
                    :list="defaultType"

                />

                <app-form-group
                    class="pt-3 col-4"
                    :label="$t('mark_as_default')"
                    :placeholder="$placeholder('name')"
                    v-model="formData.is_default"
                    type="radio"
                    :list="defaultList"
                />
            </div>
            <app-input :key="summernoteKey" type="text-editor" id="text-editor-for-template"
                       v-model="formData.custom_content"/>

            <app-note v-if="formData.is_default==1 && showNote " title="'title'" :show-title="false"
                      :notes="[$t('this_template_will_be_applicable_automatically_for_invoice')]"/>

        </form>
        <div class="form-group text-left mt-3">
            <p>{{ $t('independent_chips') }} :</p>
            <button
                type="button"
                class="btn btn-sm btn-primary px-3 py-1 margin-left-2 mt-2"
                data-toggle="tooltip"
                data-placement="top"
                v-for="chip in independentChips"
                :title="chip"
                @click="addTag(chip)"
            >{{ chip }}
            </button>
        </div>
        <div class="form-group text-left">
            <p> {{ $t('table_chips') }} : </p>
            <button
                type="button"
                class="btn btn-sm btn-primary px-3 py-1 margin-left-2 mt-2"
                data-toggle="tooltip"
                data-placement="top"
                v-for="chip in tableChips"
                :title="chip"
                @click="addTag(chip)"
            >{{ chip }}
            </button>
        </div>
    </modal>
</template>

<script>
import FormHelperMixins from "../../../../common/Mixin/Global/FormHelperMixins";
import ModalMixin from "../../../../common/Mixin/Global/ModalMixin";
import {INVOICE_TEMPLATE} from "../../../Config/ApiUrl-CPB";
import IsDefaultHelperMixin from "../../Mixins/IsDefaultHelperMixin";
import {axiosGet} from "../../../../common/Helper/AxiosHelper";
import {purify} from "../../../Helper/Purifier/HTMLPurifyHelper";

export default {
    name: "InvoiceTemplateAddEditModal",
    mixins: [FormHelperMixins, ModalMixin, IsDefaultHelperMixin],
    watch: {},
    data() {
        return {
            INVOICE_TEMPLATE,
            textEditorTest: '',
            summernoteKey: 0,
            formData: {
                type: '',
                custom_content: '',
                is_default: false,
            },
            defaultType: [
                {
                    id: null,
                    value: this.$t('select_type')
                },
                {
                    id: 'sales_invoice',
                    value: this.$t('sales_invoice')
                },
                {
                    id: 'return_invoice',
                    value: this.$t('return_invoice')
                }
            ],
            independentChips: [
                '{company_logo}', '{company_name}', '{invoice_number}', '{sales_invoice_logo}', '{return_invoice_logo}',
                '{employee_name}', '{date}', '{time}', '{customer_name}',
                '{phone_number}', '{address}', '{tin}', '{note}', '{branch_phone}', '{branch_email}', '{company_address}', '{sale_note}', '{payment_note}'
            ],
            tableChips: [
                '{item_details}', '{sub_total}', '{tax}', '{tax_amount}', '{discount}', '{discount_amount}',
                '{total}', '{payment_details}', '{exchange}', '{due}', '{barcode}'
            ]
        }
    },
    methods: {
        async handleTypeInput() {
            const typeIds = {sales_invoice: 1, return_invoice: 2};
            const {data: {custom_content}} = await axiosGet(`${INVOICE_TEMPLATE}/${typeIds[this.formData.type]}`);
            this.formData.custom_content = purify(custom_content);
            this.summernoteKey++;
        },
        submitData() {
            this.fieldStatus.isSubmit = true;
            this.loading = true;
            this.message = '';
            this.errors = {};

            this.formData.custom_content = purify(this.formData.custom_content);
            this.save(this.formData);
        },
        addTag(tag_name = "{name}") {
            $("#text-editor-for-template").summernote('editor.saveRange');
            $("#text-editor-for-template").summernote('editor.restoreRange');
            $("#text-editor-for-template").summernote('editor.focus');
            $("#text-editor-for-template").summernote('editor.insertText', tag_name);
        },
        afterSuccess({data}) {
            this.formData = {};
            $('#invoice-template-modal').modal('hide');
            this.$emit('input', false);
            this.toastAndReload(data.message, 'invoice-template-table');
        },
        afterSuccessFromGetEditData(response) {
            this.preloader = false;
            this.formData = response.data;
            this.noteVisibility(response.data)
        },
    },
}
</script>
