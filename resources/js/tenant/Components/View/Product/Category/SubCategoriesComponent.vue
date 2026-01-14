<template>
    <div>
        <div v-if="value.length">
            <div v-for="(item, index) in value" class="mb-2">
            <span class="badge badge-light text-capitalize">
                {{ item.name }}
            </span>
            </div>
        </div>
        <div v-else> -</div>
    </div>
</template>

<script>
export default {
    name: "SubCategoriesComponent",
    props: ['rowData', 'tableId', 'value'],
}
</script>