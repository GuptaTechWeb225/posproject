<template>
    <div>
        <div>
             <span class="text-muted mb-0">
                  {{ $t('sticker') }}
             </span>
            <span
                v-for="(attribute, index) in rowData.attributes_variations"
                class="badge badge-pill badge-warning rounded-pill mb-1 ml-1">
                {{ attribute.name ? attribute.name : $t('not_added_yet') }}
            </span>
        </div>
        <div>
             <span class="text-muted mb-0">
                   {{ $t('warranty') }}
             </span>
            <span>
                 {{ rowData.warranty ? rowData.warranty : $t('not_added_yet') }}
            </span>
        </div>
    </div>
</template>

<script>
export default {
    name: "VariantWarranty",
    props: ['rowData', 'tableId', 'value'],
}
</script>