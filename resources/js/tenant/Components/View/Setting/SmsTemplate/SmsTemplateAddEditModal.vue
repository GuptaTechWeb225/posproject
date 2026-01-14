<template>
    <modal id="app-sms-template-modal"
           v-model="showModal"
           :title="generateModalTitle('sms_template')"
           @submit="submitData" :loading="loading"
           :preloader="preloader">
        <form
            ref="form"
            :data-url='selectedUrl ? selectedUrl : SMS_TEMPLATE'
            @submit.prevent="submitData">

            <app-form-group
                type="text"
                :label="$t('subject')"
                :placeholder="$placeholder('subject')"
                v-model="formData.subject"
                :required="true"
                :error-message="$errorMessage(errors, 'subject')">
            </app-form-group>

            <div class="form-group">
                <label>{{ $t('content') }}</label>
                <app-note
                    :show-title="false"
                    :padding-class="'p-3 mb-4'"
                    :notes="[$t('sms_content_message')]"/>
                <app-input
                    type="text-editor"
                    v-model="formData.content"
                    :required="true"
                    id="text-editor-for-template"
                    :options="textEditorOptions"
                    :error-message="$errorMessage(errors, 'content')"
                />
            </div>
            <div class="form-group text-center">
                <button
                    type="button"
                    class="btn btn-sm btn-primary px-3 py-1 margin-left-2 mt-2"
                    data-toggle="tooltip"
                    data-placement="top"
                    v-for="tag in all_tags"
                    :title="tag.description"
                    @click="addTag(tag.tag)"
                >{{ tag.tag }}
                </button>
            </div>

        </form>
    </modal>
</template>

<script>
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import ModalMixin from "../../../../../common/Mixin/Global/ModalMixin";
import {SMS_TEMPLATE} from "../../../../Config/ApiUrl-CPB";
import {textEditorHints} from "../../../../../common/Helper/Support/TextEditorHelper";
import Vue from "vue";

export default {
    name: "SmsTemplateAddEditModal",
    mixins: [FormHelperMixins, ModalMixin],
    data() {
        return {
            formData: {
                is_default: false
            },
            SMS_TEMPLATE,
            textEditorHints,
            footer_content: "{company_name}, {first_name}, {last_name}, {invoice_id}, {total}",
            tags: {
                '{company_name}': Vue.prototype.$t('company_name'),
                '{first_name}': Vue.prototype.$t('first_name'),
                '{last_name}': Vue.prototype.$t('last_name'),
                '{invoice_id}': Vue.prototype.$t('invoice_id'),
                '{total}': Vue.prototype.$t('total'),
            },
        }
    },
    computed: {
        all_tags() {
            const tags = Object.keys(this.tags)
            return tags.map(tag => {
                return {tag, description: this.tags[tag]}
            })
        },
        textEditorOptions() {
            return {
                config: {
                    placeholder: this.$placeholder('content'),
                    dialogsInBody: true,
                    toolbar: [],
                    hint: textEditorHints(Object.keys(this.tags)),
                }
            }
        }
    },
    methods: {
        handleCheckBoxClick() {
            this.formData.is_default = this.formData.is_default == true ? false : true;
        },
        afterSuccess({data}) {
            this.formData = {};
            $('#app-sms-template-modal').modal('hide');
            this.$emit('input', false);
            this.toastAndReload(data.message, 'sms-template-table');
        },
        afterSuccessFromGetEditData(response) {
            this.preloader = false;
            this.formData = response.data;
        },
        addTag(tag_name = '{name}') {
            $("#text-editor-for-template").summernote('editor.saveRange');
            $("#text-editor-for-template").summernote('editor.restoreRange');
            $("#text-editor-for-template").summernote('editor.focus');
            $("#text-editor-for-template").summernote('editor.insertText', tag_name);
        }
    },
}
</script>