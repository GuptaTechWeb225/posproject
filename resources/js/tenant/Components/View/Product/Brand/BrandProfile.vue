<template>
    <div v-if="rowData" class="d-flex align-items-center">
        <div class="media align-items-center">
            <div class="avatars-w-50">
                <app-avatar :title="rowData.name"
                            :shadow="true"
                            :border="false"
                            :img="$optional(rowData.brand_logo, 'full_url')"
                            :alt-text="rowData.name"/>
            </div>
            <div class="media-body ml-3">
                {{ rowData.name }}
            </div>
        </div>
    </div>
</template>

<script>
import {urlGenerator} from "../../../../Helper/Helper";

export default {
    name: "brand-profile",
    data() {
        return {
            urlGenerator
        }
    },
    props: ['rowData', 'tableId'],
}
</script>
