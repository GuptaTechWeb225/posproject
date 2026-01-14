<template>
    <div class="media align-items-center">
        <div class="avatars-w-50">
            <app-avatar :title="fullName"
                        :shadow="true"
                        :border="false"
                        :img="value ? value.full_url : null"
                        :alt-text="fullName"/>
        </div>

        <div class="media-body ml-3">
            {{ fullName }}
            <p class="text-muted font-size-90 mb-0">{{ rowData.email }}</p>
            <span class="d-inline-block bg-secondary px-2 text-white pill">{{ name }}</span>
        </div>
    </div>
</template>

<script>
import {urlGenerator} from "../../../Helper/AxiosHelper";

export default {
    name: "UserMedia",
    props: ['value', 'rowData', 'index'],
    data() {
        return {
            urlGenerator
        }
    },
    computed: {
        fullName() {
            return this.rowData.full_name ? this.rowData.full_name : '';
        },
        name() {
            const rolesArray = this.rowData.roles;
            if (rolesArray !== undefined && rolesArray.length) return rolesArray[0].name;
            return '';
        }
    },
}
</script>

<style scoped lang="sass">
.pill
    border-radius: 5rem
    font-size: .625rem
</style>
