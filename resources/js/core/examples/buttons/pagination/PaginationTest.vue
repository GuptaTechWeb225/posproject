<template>
    <diV class="content-wrapper" style="height: 350px !important;">
        <app-pagination :total-row="30" :row-limit="rowLimit"/>
        <app-pagination :total-row="totalRow" :row-limit="rowLimit"/>
        <app-pagination :total-row="72" :row-limit="rowLimit"/>
    </diV>
</template>

<script>
    export default {
        name: "PaginationTest",
        data() {
            return {
                totalRow: 1000,
                rowLimit: 10
            }
        }
    }
</script>
