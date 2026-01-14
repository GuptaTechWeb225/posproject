<template>
    <div>
        <UserMedia
            :index="index"
            :rowData="userMediaRowData"
            :value="userMediaValue"
        />
    </div>
</template>

<script>
import UserMedia from '../../../../common/Components/UserRole/UI/UserMedia.vue';

export default {
    name:"CreatedBy",
    components: { UserMedia },
    props: ['value', 'rowData', 'index'],
    computed: {
        userMediaRowData() {
            return {
                full_name: this.value.full_name,
                // setting undefined as a hard coded value as user media expects it in some cases
                roles: this.value?.roles?.length ? this.value?.roles : undefined,
                email: this.value?.email
            }
        },
        userMediaValue() {
            if (this.value) return { full_url: this.value?.full_url }
            return null;
        },
    },
}
</script>

<style lang="scss" scoped>

</style>
