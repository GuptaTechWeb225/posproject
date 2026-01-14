<template>
    <div>
        <div class="note-title d-flex" v-if="showTitle">
            <app-icon :name="noteIcon"/>
            <h6 class="card-title pl-2">{{ title }}</h6>
        </div>
        <div class="note" :class="[noteType,paddingClass]">
            <p class="m-1" v-if="contentType==='string'" v-for="(note, index) in notesData" :key="index">{{ note }}</p>
            <p class="m-1" v-if="contentType==='html'" v-for="(note, index) in notesData" :key="index" v-html="purify(note)"></p>
        </div>
    </div>
</template>

<script>
import {purify} from "../../../tenant/Helper/Purifier/HTMLPurifyHelper";

export default {
    name: "AppNotes",

    props: {
        title: {
            type: String,
            require: true
        },
        showTitle: {
            type: Boolean,
            default: true,
        },
        noteType: {
            type: String,
            default: 'note-warning',
        },
        contentType: {
            type: String,
            default: 'string'
        },
        notes: {
            type: [Array, String],
            require: true,
        },
        noteIcon: {
            type: String,
            default: 'book-open'
        },
        paddingClass: {
            type: String,
            default: 'p-4'
        }
    },
    data(){
        return{
            purify,
        }
    },
    computed: {
        notesData() {
            if (typeof this.notes === 'string') {
                return [this.notes];
            } else return this.notes;
        }
    }
}
</script>
