<template>
    <div>
        <span v-for="(item, index) in rowData.attribute_variations" class="text-capitalize badge badge-pill badge-secondary ml-2">
            {{ item.name }} <span v-if="item.name.length> 1 && index !== rowData.attribute_variations.length - 1"> </span>
        </span>
    </div>
</template>

<script>
export default {
    name: "VariantValue",
    props: ['rowData', 'tableId', 'value'],
    data() {
        return{
            attribute_variations:{}
        }
    }
}
</script>