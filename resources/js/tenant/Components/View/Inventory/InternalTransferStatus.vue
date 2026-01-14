<template>
    <div class="dropdown" id="app-lot-status">
        <a  :class="`${dropdownClass} btn border-0 btn-secondary dropdown-toggle d-inline-flex align-items-center text-capitalize`"
            type="button"
            id="dropdownMenuButton"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
        >
            <span class="pr-2"> {{ status }} </span>
            <app-icon name="chevron-down" class="size-16" />
        </a>

        <div
            class="dropdown-menu"
            aria-labelledby="dropdownMenuButton"
            id="dropdownMenu"
        >
            <a
                class="dropdown-item py-3 d-flex align-items-center" style="cursor: pointer;"
                @click="changeInternalTransfer({status_id: status.id, name: status.translated_name})"
                v-for="status in statuses" :key="status.id" v-if="status.id !== rowData.status_id">
                <span
                    style="width: 10px; height: 10px;"
                    :class="`rounded-circle bg-${status.class} d-inline-block`"></span>
                <span class="item-text-content d-block pl-3">
                    {{ status.translated_name }}
                </span>
            </a>
        </div>

        <app-confirmation-modal
            v-if="confirmationModalActive"
            icon="trash-2"
            modal-id="app-status-change-confirmation-modal"
            :message="confirmationMessage"
            @confirmed="lotStatusChangeConfirm"
            @cancelled="lotStatusChangeCancel"
        />
    </div>
</template>

<script>
import {mapState} from "vuex";
import HelperMixin from "../../../../common/Mixin/Global/HelperMixin";
import {axiosGet, axiosPost} from "../../../../common/Helper/AxiosHelper";
import {CHANGE_INTERNAL_TRANSFER_STATUS, SELECTABLE_INTERNAL_TRANSFER_STATUSES} from "../../../Config/ApiUrl-CP";

export default {
    mixins: [HelperMixin],
    name: 'app-internal-transfer-status',
    props: ['rowData', 'tableId', 'value'],
    data() {
        return {
            confirmationMessage: '',
            confirmationModalActive: false,
            lotStatusChangeEndpoint: '',
            lotStatusChangePostBody: {},
            status: this.rowData.status.translated_name,
            statuses: [],
        };
    },
    mounted() {
        axiosGet(SELECTABLE_INTERNAL_TRANSFER_STATUSES)
            .then(data => {
                this.statuses = data.data
            });
    },
    methods: {
        changeInternalTransfer(newStatus) {
            const endpoint = `${CHANGE_INTERNAL_TRANSFER_STATUS}${this.rowData.id}`;
            const postBody = { status_id: newStatus.status_id };

            if (this.status.toLowerCase() === 'complete') {
                axiosPost(endpoint, postBody)
                    .catch(error => this.$toastr.e('', error.response.data.message));
                return;
            }


            this.lotStatusChangeEndpoint = endpoint;
            this.lotStatusChangePostBody = postBody;
            this.confirmationMessage = newStatus.name.toLowerCase() === "complete"
                ? this.$t('completed_internal_transfer_status_change_message') : this.$t('normal_internal_transfer_status_change_message');
            this.confirmationModalActive = true;
        },
        async lotStatusChangeConfirm() {
            const response = await axiosPost(this.lotStatusChangeEndpoint, this.lotStatusChangePostBody);
            this.toastAndReload(response.data.message, this.tableId);

            // calling the cancel method here as it closes the modal
            this.lotStatusChangeCancel();
        },
        lotStatusChangeCancel() {
            this.confirmationMessage = '';
            this.confirmationModalActive = false;
            this.lotStatusChangeEndpoint = '';
            this.lotStatusChangePostBody = {};
        }
    },
    computed: {
        dropdownClass() {
            if (this.status.toLowerCase() === 'complete') return 'success';
            if (this.status.toLowerCase() === 'pending') return 'warning';
            if (this.status.toLowerCase() === 'failed') return 'danger';
        },
    },
};
</script>

<style lang="scss" scoped>
#dropdownMenuButton {
    border-radius: 35px;
    font-size: 0.35rem;
    padding: 0.4rem 0.85rem;
    transform: scale(0.85);

    &.success {
        background: rgba(39, 174, 96, 0.2);
        color: #27ae60;
    }

    &.warning {
        background: rgba(255, 148, 23, 0.2);
        color: #ff9417;
    }

    &.danger {
        background: rgba(252, 44, 16, 0.2);
        color: #fc2c10;
    }
}

.pe-none { pointer-events: none; }
</style>
