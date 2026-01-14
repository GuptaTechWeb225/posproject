<template>
    <div class="">

        <app-table
            id="coupon-table"
            :options="options"
            @action="triggerAction"
        />

        <app-coupon-modal
            v-if="isModalActive"
            v-model="isModalActive"
            :selected-url="selectedUrl"
            @close="isModalActive = false"
        />

        <app-confirmation-modal
            v-if="confirmationModalActive"
            icon="trash-2"
            modal-id="app-confirmation-modal"
            @confirmed="confirmed('coupon-table')"
            @cancelled="cancelled"
        />

    </div>
</template>

<script>

import HelperMixin from "../../../../common/Mixin/Global/HelperMixin";
import CouponMixin from "../../Mixins/CouponMixin";
import {COUPONS} from "../../../Config/ApiUrl-CPB";

export default {
    name: "Coupon",
    mixins: [HelperMixin, CouponMixin],
    props: {
        props: {
            default: ''
        },
        id: {
            type: String
        }
    },
    data() {
        return {
            isModalActive: false,
            selectedUrl: '',
        }
    },
    mounted() {
        this.$hub.$on('headerButtonClicked-' + this.id, (component) => {
            this.openModal();
        })
    },
    methods: {
        triggerAction(row, action, active) {
            if (action.name === 'edit') {
                this.selectedUrl = `${COUPONS}/${row.id}`;
                this.isModalActive = true;
            } else {
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