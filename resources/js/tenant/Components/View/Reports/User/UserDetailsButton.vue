<template>
    <div>
        <button class="btn btn-info" @click.prevent="userDetailsReport">{{ $t('reports') }}</button>
    </div>
</template>

<script>
import {urlGenerator} from "../../../../../common/Helper/AxiosHelper";

export default {
    name: "UserDetailsButton",
    props: {
        rowData: {}
    },
    data() {
        return {
            urlGenerator
        }
    },
    methods: {
        userDetailsReport() {
            window.location.replace(urlGenerator(`user/report/details/${this.rowData.id}`))
        }
    }
}
</script>