<template>
    <div>

        <app-table
            id="sms-template-table"
            :options="options"
            @action="triggerActions"
        />

        <app-sms-template-modal
            v-if="isModalActive"
            v-model="isModalActive"
            :selected-url="selectedUrl"
            @close="isModalActive = false"

        />

        <app-confirmation-modal
            v-if="confirmationModalActive"
            icon="trash-2"
            modal-id="app-confirmation-modal"
            @confirmed="confirmed('sms-template-table')"
            @cancelled="cancelled"
        />
    </div>
</template>

<script>
import HelperMixin from "../../../../../common/Mixin/Global/HelperMixin";
import SmsTemplateMixin from "../../../Mixins/SmsTemplateMixin";
import {SMS_TEMPLATE} from "../../../../Config/ApiUrl-CPB";
import SmsTemplateAddEditModal from "./SmsTemplateAddEditModal";
export default {
    name: "SmsTemplate",
    components: {SmsTemplateAddEditModal},
    mixins: [HelperMixin, SmsTemplateMixin],
    props: ['id'],
    data() {
        return {
            isModalActive: false,
            selectedUrl: '',
        }
    },

    methods: {
        triggerActions(row, action, active) {

            if (action.title === this.$t('edit')) {
                this.selectedUrl = `${SMS_TEMPLATE}/${row.id}`;
                this.isModalActive = true;
            } else {
                this.getAction(row, action, active)
            }
        },
        closeModal() {
            alert('on')
            $('#app-sms-template-modal').modal('hide')
            this.isModalActive = false;
            this.formData ={};
            this.selectedUrl = ''
        },
        openModal() {
            this.isModalActive = true;
            this.selectedUrl = ''
        },
    },
    mounted() {
        this.$hub.$on('headerButtonClicked-' + this.id, () => {
            this.isModalActive = true
        })
    },
    watch: {
        isModalActive: function (isModalActive) {
            if (!isModalActive)
                this.selectedUrl = null;
        }
    },

    beforeDestroy() {
        this.$hub.$off('headerButtonClicked-' + this.id);
    }
}
</script>
