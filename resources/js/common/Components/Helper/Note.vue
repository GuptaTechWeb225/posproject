<template>
    <div>
        <div class="note-title d-flex" v-if="title">
            <app-icon :name="icon"/>
            <h6 class="card-title pl-2"> {{ title }}</h6>
        </div>
        <div class="note note-warning p-4">
            <p class="m-1" v-html="purify(description)"></p>
        </div>
    </div>
</template>

<script>
import {purify} from "../../../tenant/Helper/Purifier/HTMLPurifyHelper";

export default {
    name: "Note",
    data() {
        return {
            purify,
        }
    },
    props: {
        title: {},
        icon: {
            default: 'book-open'
        },
        description: {}
    }
}
</script>

<style scoped>

</style>
