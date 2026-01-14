<template>
    <div>
        <span v-if="items.length === 0">-</span>
        <span v-else v-for="(item, index) in items"
              class="badge badge-secondary rounded-pill text-capitalize"
              :class="{'mr-2': items.length > 0 && index !== items.length - 1}">
            {{ item.parent_tax.name }}
        </span>
    </div>
</template>

<script>
export default {
    name: "GroupBy",
    props: ['rowData', 'tableId', 'value'],
    data() {
        return{
            items: this.value,
        }
    },
}
</script>