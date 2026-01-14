<template>
    <div class="is-variant-component">
        <app-icon :name="value === 'variant' ? 'layers' : 'hexagon'" />
        <span class="product-type text-capitalize">{{ value }}</span>
    </div>
</template>

<script>
export default {
    name: "IsVariantComponent",
    props: ['rowData', 'tableId', 'value'],
}
</script>
