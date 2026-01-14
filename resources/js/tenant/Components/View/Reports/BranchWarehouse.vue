<template>
  <div>
    <template v-for="(item, index) in branchWarehousesData" v-if="index < 1">
      <div class="d-flex align-items-center">
        <span class="mb-1 badge badge-round badge-light">{{ item.branch_or_warehouse.name ?? '-'  }}</span>
        <br>
      </div>

      <small class="cursor-pointer" @click.prevent="viewAll" v-if="branchWarehousesData.length > 1 && index == 0">
        <b>+{{ branchWarehousesData.length - 1 }} more</b>
      </small>
    </template>

    <all-branch-warehouses
        v-if="isViewModalOpen"
        :row-data="branchWarehousesData"
        @close-modal="closeModal"
    />
  </div>
</template>

<script>
import {collection} from "../../../Helper/Helper";

export default {
  name: "BranchWarehouseColumn",
  props: {
    rowData: {
      type: Object,
      required: true
    },
    value: {
      type: Array,
    }
  },
  data() {
    return {
      isViewModalOpen: false,
    }
  },
  methods: {
    viewAll() {
      this.isViewModalOpen = true;
    },
    closeModal() {
      this.isViewModalOpen = false;
      $("#person-org-modal").modal("hide");
    }
  },
  computed: {
    branchWarehousesData() {
      return this.value.length ? collection(this.value).get("branch_or_warehouse") : [];
    }
  }
}
</script>