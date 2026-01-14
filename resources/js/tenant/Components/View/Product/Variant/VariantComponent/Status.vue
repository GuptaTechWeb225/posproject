<template>
    <div>
        <div class="d-flex">
            <app-input v-model="switchValue" :label="$t('switch')" type="switch"  @click="toggle"/>
            <span class="ml-1">

                {{ parseInt(rowData.is_enabled) === 0 ? $t('inactive') : $t('active') }}
            </span>
        </div>

        <app-confirmation-modal
            v-if="statusActive"
            :firstButtonName="$t('yes')"
            modal-class="warning"
            icon="slash"
            :message="$t('you_are_going_to_deactivate_a_variant')"
            modal-id="app-confirmation-modal"
            @confirmed="changeStatus"
            @cancelled="statusActiveCancel"
        />

        <app-confirmation-modal
            v-if="statusInActive"
            :firstButtonName="$t('yes')"
            modal-class="primary"
            icon="check-square"
            :message="$t('you_are_going_to_activate_a_variant')"
            modal-id="app-confirmation-modal"
            @confirmed="changeStatus"
            @cancelled="statusInActiveCancel"
        />

    </div>
</template>

<script>
import FormHelperMixins from "../../../../../../common/Mixin/Global/FormHelperMixins";
import {axiosPost} from "../../../../../../common/Helper/AxiosHelper";
import {VARIANT} from "../../../../../Config/ApiUrl-CP";

export default {
    name: "Status",
    props: ['rowData', 'tableId', 'value'],
    mixins: [FormHelperMixins],
    data() {
        return {
            parseInt,
            VARIANT,
            switchValue: this.value,
            status: '',
            statusActive: false,
            statusInActive: false,
            cancelStatus: false,
        }
    },
    created() {
        if ( parseInt(this.rowData.is_enabled) === 1) {
            this.switchValue = true;
            this.cancelStatus = true;
        }
    },
    methods: {
        changeStatus() {
            this.variantId = this.rowData.id;
            axiosPost(`${VARIANT}/${this.variantId}/status-update`, {is_enabled: this.switchValue}).then(({data}) => {
                this.toastAndReload(data.message, 'variant-table', this.rowData.product_id);
            }).catch(({response}) => {
                this.toastAndReload(response.data.message, 'variant-table');
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