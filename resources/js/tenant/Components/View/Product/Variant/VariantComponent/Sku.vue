<template>
    <div>
        {{ rowData.sku ? rowData.sku : $t('not_added_yet') }}
    </div>
</template>

<script>
export default {
    name: "VariantSku",
    props: ['rowData', 'tableId', 'value'],
}
</script>