<template>
    <div class="d-flex">
        <app-input type="switch" v-model="active" />
        <span class="unit-status d-inline-block ml-n2">{{ value.translated_name }}</span>
    </div>
</template>

<script>
import {FormMixin} from '../../../../../core/mixins/form/FormMixin';
import StatusQueryMixin from "../../../../../common/Mixin/Global/StatusQueryMixin";

export default {
    name: 'app-unit-status-change',
    mixins: [FormMixin, StatusQueryMixin],
    props: ['rowData', 'tableId', 'value'],
    data() {
        return {
            active: this.value.translated_name === 'Active',
        }
    },
    watch:  {
        active() {
            this.triggerStatusChange();
        }
    },
    methods: {
        triggerStatusChange() {
            this.switchStatus(
                'unit',
                this.rowData.id,
                this.value.translated_name.toLowerCase(),
                'all-units-table'
            );
        }
    },
}
</script>
