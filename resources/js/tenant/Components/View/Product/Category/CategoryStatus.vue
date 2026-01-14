<template>
  <div class="d-flex">
    <app-input type="switch" v-model="active" @click="triggerStatusChange"/>
    <span class="category-status d-inline-block">{{ value.translated_name }}</span>
  </div>
</template>

<script>
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import StatusQueryMixin from "../../../../../common/Mixin/Global/StatusQueryMixin";

export default {
  name: "app-category-status-change",
  mixins: [FormHelperMixins, StatusQueryMixin],
  props: ['rowData', 'tableId', 'value'],
  data() {
    return {
      active: this.value.translated_name === 'Active',
    }
  },
  methods: {
    triggerStatusChange() {
      this.switchStatus(
          'category',
          this.rowData.id,
          this.value.translated_name.toLowerCase(),
          this.tableId
      );
    }
  },
}
</script>
