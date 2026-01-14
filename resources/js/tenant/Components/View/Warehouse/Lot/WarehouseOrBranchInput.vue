<template>
    <div>
        <div class="select-box" @click="dropdownActive = true" style="cursor: pointer;">{{ temporaryHeading }}</div>
        <div class="dropdown" v-if="dropdownActive">
            <h4 class="text-primary">WAREHOUSES</h4>
            <p class="temp-dropdown-item" v-for="warehouse in warehouses" :key="warehouse.name + warehouse.id"
               @click="emitData(warehouse)" style="cursor: pointer;">
                {{ warehouse.name }}</p>

            <h4 class="text-primary">BRANCHES</h4>
            <p class="temp-dropdown-item" v-for="branch in branches" :key="branch.name + branch.id"
               @click="emitData(branch)"
               style="cursor: pointer;">{{ branch.name }}</p>
        </div>
    </div>
</template>

<script>
import {axiosGet} from "../../../../../common/Helper/AxiosHelper";

export default {
    name: 'WarehouseOrBranchInput',
    props: ['temporaryHeading'],
    data() {
        return {
            dropdownActive: false,
            apiData: [],
        }
    },
    methods: {
        emitData(selectedItemData) {
            this.$emit('item-select', selectedItemData);
            this.dropdownActive = false;
        }
    },
    computed: {
        warehouses() {
            return this.apiData.filter(arrItem => arrItem.type === 'warehouse')
        },
        branches() {
            return this.apiData.filter(arrItem => arrItem.type === 'branch')
        }
    },
    async mounted() {
        const { data } = await axiosGet("app/selectable-branches-or-warehouses");
        this.apiData = data;
    }
}
</script>

<style lang="scss">
.temp-dropdown-item {
    color: #fff;
    transition: 200ms;
    padding-left: 1rem;

    &:hover {
        padding-left: 2rem;
    }
}
</style>