<template>
    <div class="content-wrapper">

        <app-page-top-section :title="$t('area_of_expense')" icon="briefcase">
            <app-default-button
                @click="openModal()"
                v-if="this.$can('create_expense_areas')"
                :title="$fieldTitle('add', 'area_of_expense', true)"/>
        </app-page-top-section>


        <app-table
            id="expense-area-table"
            :options="options"
            @action="triggerActions"
        />


        <app-expanse-area-create-edit-modal
            v-if="isModalActive"
            v-model="isModalActive"
            :selected-url="selectedUrl"
            @close="isModalActive = false"
        />

        <app-confirmation-modal
            v-if="confirmationModalActive"
            icon="trash-2"
            modal-id="app-confirmation-modal"
            @confirmed="confirmed('expense-area-table')"
            @cancelled="cancelled"
        />
    </div>

</template>

<script>
import {EXPENSE_AREAS} from "../../../Config/ApiUrl-CP";
import ExpenseAreaMixin from "../../Mixins/ExpenseAreaMixin";
import HelperMixin from "../../../../common/Mixin/Global/HelperMixin";

export default {
    name: "ExpenseArea",
    mixins: [HelperMixin, ExpenseAreaMixin],
    data() {
        return {
            isModalActive: false,
            selectedUrl: '',
        }
    },
    methods: {
        triggerActions(row, action, active) {
            if (action.title === this.$t('edit')) {
                this.selectedUrl = `${EXPENSE_AREAS}/${row.id}`;
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
