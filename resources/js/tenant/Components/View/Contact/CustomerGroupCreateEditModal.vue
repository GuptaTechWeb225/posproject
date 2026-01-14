<template>
    <modal id="customer-group-modal"
           v-model="showModal"
           :title="generateModalTitle(ucWords($t('customer_group')))"
           @submit="submitData" :loading="loading"
           :preloader="preloader">

        <form
            ref="form"
            :data-url='selectedUrl ? selectedUrl : CUSTOMER_GROUP'
            @submit.prevent="submitData">

            <app-form-group
                :label="$t('name')"
                :placeholder="$placeholder('name')"
                v-model="formData.name"
                :required="true"
                :error-message="$errorMessage(errors, 'name')"
            />

            <app-form-group
                :label="$t('mark_as_default')"
                :placeholder="$placeholder('name')"
                v-model="formData.is_default"
                type="radio"
                :list="defaultList"
            />

            <app-note v-if="formData.is_default==1 && showNote " title="'title'" :show-title="false"
                      :notes="[$t('this_group_will_be_selected_automatically_when_you_create_new_customer')]"/>

        </form>
    </modal>
</template>
<script>
import FormHelperMixins from "../../../../common/Mixin/Global/FormHelperMixins";
import ModalMixin from "../../../../common/Mixin/Global/ModalMixin";
import {CUSTOMER_GROUP} from "../../../Config/ApiUrl-CPB";
import {ucWords} from "../../../../common/Helper/Support/TextHelper";

export default {
    name: "CustomerGroupCreateEditModal",
    mixins: [FormHelperMixins, ModalMixin],
    data() {
        return {
            showNote: true,
            formData: {
                is_default: 0,
            },
            isDefaultList: [
                {
                    id: 1,
                    value: this.$t('yes')
                },
                {
                    id: 0,
                    value: this.$t('no')
                }
            ],
            CUSTOMER_GROUP,
            ucWords,
        }
    },
    computed: {
        defaultList() {
            let list = _.cloneDeep(this.isDefaultList);
            if (!this.showNote) {
                list.map((item) => {
                    if (item.id == 0)
                        item.disabled = true;
                })
                return list;
            }
            return this.isDefaultList;
        }
    },
    methods: {
        afterSuccess({data}) {
            this.formData = {};
            $('#customer-group-modal').modal('hide');
            this.$emit('input', false);
            this.toastAndReload(data.message, 'customer-group-table');
        },
        afterSuccessFromGetEditData(response) {
            this.preloader = false;
            this.formData = response.data;
            if (response.data.is_default == 1)
                this.showNote = false
        },
    },
}
</script>