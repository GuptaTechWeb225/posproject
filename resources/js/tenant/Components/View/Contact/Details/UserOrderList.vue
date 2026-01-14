<template>
    <div>
        <app-table :id="table_id" :options="options" @action="triggerAction" ></app-table>

        <app-due-payment-modal
            v-if="isModalActive"
            :order-id="orderId"
            :value="true"
            @modal-close="closeModal"
        />

    </div>
</template>
<script>
import HelperMixin from "../../../../../common/Mixin/Global/HelperMixin";
import OrderListMixin from "../../../Mixins/OrderListMixin";
export default {
    name: "OrderList",
    props: {
        props: {

        }
    },
    mixins: [HelperMixin, OrderListMixin],
    data() {
        return {
            table_id: 'invoice-list-table',
            orderId : '',
            isModalActive : false
        };
    },
    methods:{
        triggerAction(row, action, active) {
            console.log(action)
            this.orderId = row.id;
            if (action.type === 'due_receive') {
                this.isModalActive = true
            }
        },
        closeModal() {
            $('#due-payment-modal').modal('hide')
            this.isModalActive = false
            this.$emit('modal-close');
        },
        openModal() {
            this.isModalActive = true;
            this.selectedUrl = ''
        }
    }
}
</script>