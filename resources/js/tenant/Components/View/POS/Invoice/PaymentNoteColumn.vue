<template>
  <div>
    <app-note-editor
        v-if="Object.keys(comment)"
        :row-data="comment"
        :note-title="$t('payment_note')"
        :note-description="comment"
        :edit-permission="false"
    />
  </div>
</template>

<script>

export default {
  name: "paymentNote",
  props: {
    value: {},
    rowData: {},
    tableId: {}
  },
  data() {
    return {
      isResponseLogModalActive: false,
    }
  },
  computed: {
    user(){
      return window.user;
    },
    comment() {
      if (!this.value) return '';
      return this.value;
    },
  },
}

</script>

<style scoped>

</style>