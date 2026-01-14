<template>
    <div class="content-wrapper">

        <app-page-top-section :title="$t('expenses')" icon="briefcase">
            <app-default-button
                @click="openModal()"
                v-if="this.$can('create_expenses')"
                :title="$fieldTitle('add', 'expense', true)"/>
        </app-page-top-section>

        <app-table
            :id="table_id"
            v-if="options.url"
            :options="options"
            @action="triggerActions"
        />

        <app-expanse-create-edit-modal
            v-if="isModalActive"
            v-model="isModalActive"
            :selected-url="selectedUrl"
            @close="isModalActive = false"
        />

        <app-confirmation-modal
            v-if="confirmationModalActive"
            icon="trash-2"
            modal-id="app-confirmation-modal"
            @confirmed="confirmed('expense-table')"
            @cancelled="cancelled"
        />

    </div>
</template>

<script>
import HelperMixin from "../../../../common/Mixin/Global/HelperMixin";
import {EXPENSES} from "../../../Config/ApiUrl-CP";
import ExpenseMixin from "../../Mixins/ExpenseMixin";

export default {
    name: "Expense",
    mixins: [HelperMixin, ExpenseMixin],
    data() {
        return {
            table_id: 'expense-table',
            isModalActive: false,
            selectedUrl: '',
        }
    },
    methods: {
        triggerActions(row, action, active) {
            if (action.title === this.$t('edit')) {
                this.selectedUrl = `${EXPENSES}/${row.id}`;
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
