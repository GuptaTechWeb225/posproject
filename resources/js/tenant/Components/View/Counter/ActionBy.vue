<template>
    <div>
        <UserMedia
            :index="index"
            :rowData="userMediaRowData"
            :value="userMediaValue"
            v-if="openOrClosingTimeStr"
        />
        <div class="d-flex align-items-center mt-3" :title="rowData.status.name === 'status_open' ? $t('opening_time') : $t('closing_time')">
            <span><app-icon name="clock" />:<b> {{ openOrClosingTimeStr ? formatDateToLocal(openOrClosingTimeStr, true) : $t('not_yet_opened')}}</b></span> 
        </div>

    </div>
</template>

<script>
import UserMedia from '../../../../common/Components/UserRole/UI/UserMedia.vue';
import { formatDateToLocal } from '../../../../common/Helper/Support/DateTimeHelper';

export default {
    name:"ActionBy",
    components: { UserMedia },
    props: ['value', 'rowData', 'index'],
    data() {
        return {
            formatDateToLocal,
        }
    },
    computed: {
        openOrClosingTimeStr() {
            return this.rowData[this.rowData?.status?.name === 'status_open' ? 'opening_time' : 'closing_time']
        },
        userMediaRowData() {
            return {
                full_name: this.value?.full_name,
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

