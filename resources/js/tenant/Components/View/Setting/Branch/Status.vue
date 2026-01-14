<template>
    <div class="d-flex">
        <app-input type="switch" :label="$t('Switch')" v-model="switchValue" @click="toggle"/>
        <span class="ml-1">{{ value.translated_name }}</span>

        <app-confirmation-modal
            v-if="statusActive"
            :firstButtonName="$t('yes')"
            modal-class="warning"
            icon="slash"
            :message="$t('you_are_going_to_deactivate_a_branch')"
            modal-id="app-confirmation-modal"
            @confirmed="changeStatus"
            @cancelled="statusActiveCancel"
        />

        <app-confirmation-modal
            v-if="statusInActive"
            :firstButtonName="$t('yes')"
            modal-class="primary"
            icon="check-square"
            :message="$t('you_are_going_to_activate_a_branch')"
            modal-id="app-confirmation-modal"
            @confirmed="changeStatus"
            @cancelled="statusInActiveCancel"
        />


    </div>
</template>

<script>
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import DatatableHelperMixin from "../../../../../common/Mixin/Global/DatatableHelperMixin";
import {BRANCHES_OR_WAREHOUSES} from "../../../../Config/ApiUrl-CPB";
import {axiosPatch, axiosPost} from "../../../../../common/Helper/AxiosHelper";

export default {
    name: "Status",
    mixins: [FormHelperMixins, DatatableHelperMixin],
    props: {
        value: {},
        rowData: {},
    },
    data() {
        return {
            switchValue: '',
            status: '',
            statusActive: false,
            statusInActive: false,
            cancelStatus: false,
            updateUrl: BRANCHES_OR_WAREHOUSES,
            branchId: '',
        }
    },
    created() {
        if (this.value.name === "status_active") {
            this.switchValue = true;
            this.cancelStatus = true;
        }
    },
    methods: {
        changeStatus() {
            this.branchId = this.rowData.id;
            axiosPatch(`app/branch_or_warehouse/${this.branchId}/update-status`, {status: this.switchValue}).then(({data}) => {
                this.toastAndReload(data.message, 'branches-and-warehouses-table');
            }).catch(({response}) => {
                this.toastAndReload(response.data.message, 'branches-and-warehouses-table');
            }).finally(() => {
                this.confirmationModalActive = false;
            });
        },
        statusActiveCancel() {
            this.statusActive = false;
            this.switchValue = this.cancelStatus;
        },
        statusInActiveCancel() {
            this.statusInActive = false;
            this.switchValue = this.cancelStatus;
        },
        toggle() {

            if (this.switchValue) {
                this.statusInActive = false;
                this.statusActive = true;
            } else {
                this.statusActive = false;
                this.statusInActive = true;
            }
        }
    },
    computed: {
        className() {
            return 'badge badge-pill badge-' + this.value.class
        },
    },
}
</script>
