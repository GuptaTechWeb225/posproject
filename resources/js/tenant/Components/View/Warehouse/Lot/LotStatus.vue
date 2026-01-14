<template>
    <div class="dropdown pb-3" id="app-lot-status">
        <a  :class="`${dropdownClass} ${dropdownClass === 'success' ? 'pe-none' : ''} btn border-0 btn-secondary dropdown-toggle d-inline-flex align-items-center text-capitalize`"
            type="button"
            id="dropdownMenuButton"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
        >
            <span class="pr-2"> {{ status }} </span>
            <app-icon name="chevron-down" class="size-16" v-if="status.toLowerCase() !== 'delivered'"/>
        </a>

        <div
            class="dropdown-menu"
            aria-labelledby="dropdownMenuButton"
            id="dropdownMenu"
        >
            <a
                class="dropdown-item py-3 d-flex align-items-center" style="cursor: pointer;"
                @click="changeLotStatus({status_id: lotStatus.id, name: lotStatus.translated_name})"
                v-for="lotStatus in statuses.lotStatuses" :key="status.id" v-if="lotStatus.id !== rowData.status_id">
                <span
                    style="width: 10px; height: 10px;"
                    :class="`rounded-circle bg-${lotStatus.class} d-inline-block`"></span>
                <span class="item-text-content d-block pl-3">
                    {{ lotStatus.translated_name }}
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
import {axiosPost} from "../../../../../common/Helper/AxiosHelper";
import HelperMixin from "../../../../../common/Mixin/Global/HelperMixin";

export default {
    mixins: [HelperMixin],
    name: 'app-lot-status',
    props: ['rowData', 'tableId', 'value'],
    data() {
        return {
            confirmationMessage: '',
            confirmationModalActive: false,
            lotStatusChangeEndpoint: '',
            lotStatusChangePostBody: {},
            status: this.rowData.status.translated_name,
        };
    },
    methods: {
        changeLotStatus(newStatus) {
            const endpoint = `/app/change-lot-status/${this.rowData.id}`;
            const postBody = { status_id: newStatus.status_id };

            if (this.status.toLowerCase() === 'delivered') {
                 axiosPost(endpoint, postBody)
                    .catch(error => this.$toastr.e('', error.response.data.message));
                return;
            }

            this.lotStatusChangeEndpoint = endpoint;
            this.lotStatusChangePostBody = postBody;
            this.confirmationMessage = newStatus.name.toLowerCase() === 'delivered'
                ? this.$t('delivered_lot_status_change_message') : this.$t('normal_lot_status_change_message');
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
            if (this.status.toLowerCase() === 'delivered') return 'success';
            if (this.status.toLowerCase() === 'pending') return 'warning';
            if (this.status.toLowerCase() === 'declined') return 'danger';
        },
        ...mapState({
            statuses: state => state.lotStatuses
        })
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
