<template>
    <div class="content-wrapper">

        <app-page-top-section :title="$t('expense_report')" icon="briefcase">

        </app-page-top-section>

        <app-table
            :id="table_id"
            v-if="options.url"
            :options="options"
            @action=""
        />

    </div>
</template>

<script>
import HelperMixin from "../../../../../common/Mixin/Global/HelperMixin";
import ExpenseReportMixins from "./ExpenseReportMixins";

export default {
    name: "Expense",
    mixins: [HelperMixin, ExpenseReportMixins],
    data() {
        return {
            table_id: 'expense-report-table',
        }
    },
    methods: {}
}
</script>