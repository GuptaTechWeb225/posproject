<template>
    <div class="d-flex align-items-center">
        <app-avatar
            :key="value ? value.id : Date.now()"
            :border="true"
            :img="value ? value.full_url : defaultImg"
            avatar-class="avatars-w-60"
            :title="rowData.name"
        />
    </div>
</template>

<script>
import {urlGenerator} from "../../../../common/Helper/AxiosHelper";

export default {
    name: 'app-supplier-img',
    props: ['rowData', 'tableId', 'value'],
    data() {
        return {
            urlGenerator
        }
    },
    computed: {
        defaultImg() {
            return urlGenerator('images/product/default_product.png');
        }
    }
}
</script>
