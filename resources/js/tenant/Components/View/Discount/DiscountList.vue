<template>
    <div class="content-wrapper">

        <app-page-top-section :title="$t('discount')" icon="briefcase">
            <app-default-button
                @click="openModal()"
                :title="$fieldTitle('add', 'discount', true)"/>
        </app-page-top-section>


        <app-table
            id="discount-table"
            v-if="options.url"
            :options="options"
            @action="triggerActions"
        />


        <app-discount-create-edit-modal
            v-if="isModalActive"
            v-model="isModalActive"
            :selected-url="selectedUrl"
            @close-modal="closeDiscountModal"
        />

        <app-confirmation-modal
            v-if="confirmationModalActive"
            icon="trash-2"
            modal-id="app-confirmation-modal"
            @confirmed="confirmed('discount-table')"
            @cancelled="cancelled"
        />

        <app-confirmation-modal
            v-if="discountActiveModal"
            icon="check-circle"
            :modal-class="discountModalClass"
            modal-id="app-status-change-confirmation-modal"
            :message="confirmationMessage"
            @confirmed="discountStatusChangeConfirm('discount-table')"
            @cancelled="discountStatusChangeCancel"
        />

    </div>
</template>

<script>
import HelperMixin from "../../../../common/Mixin/Global/HelperMixin";
import {DISCOUNT_LIST} from "../../../Config/ApiUrl-CP";
import DiscountMixin from "./DiscountMixin";
import {axiosGet} from "../../../../common/Helper/AxiosHelper";

export default {
    name: "DiscountList",
    mixins: [HelperMixin, DiscountMixin],
    data() {
        return {
            table_id: 'discount-table',
            confirmationMessage: '',
            discountActiveModal: false,
            isModalActive: false,
            selectedUrl: '',
            discount_id: '',
            discountModalClass: 'success'
        }
    },
    methods: {
        closeDiscountModal() {
            $('#discount-create-edit-modal').modal('hide');
            this.isModalActive = false;
        },
        discountStatusChangeConfirm(id) {
            axiosGet(`app/discount-status-change/${this.discount_id}`).then((res) => {
                this.toastAndReload(res.data.message, id);
                this.discountStatusChangeCancel();
            })
        },
        discountStatusChangeCancel() {
            this.confirmationMessage = '';
            this.discountActiveModal = false;
            $('#app-status-change-confirmation-modal').modal('hide');
        },
        triggerActions(row, action, active) {
            this.discount_id = row.id
            if (action.title === this.$t('edit')) {
                this.selectedUrl = `${DISCOUNT_LIST}/${row.id}`;
                this.isModalActive = true;
            } else if (action.title === this.$t('activate')) {
                this.confirmationMessage = this.$t('are_you_sure_to_active_this_discount')
                this.discountActiveModal = true;
                this.discountModalClass = 'success';
            } else if (action.title === this.$t('de_activate')) {
                this.confirmationMessage = this.$t('are_you_sure_to_de_active_this_discount')
                this.discountModalClass = 'danger';
                this.discountActiveModal = true;
            } else if ('delete') {
                this.getAction(row, action, active)
            }
        },
        openModal() {
            this.isModalActive = true;
            this.selectedUrl = ''
        },
    }
}
</script>
