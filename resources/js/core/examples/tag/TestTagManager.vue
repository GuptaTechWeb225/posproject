<template>
    <div>
        <app-tag-manager
            :tags="tags"
            :list="tagOptions"
            list-value-field="value"
            color-value-field="colors"
            :loaded-per-time="1"
            :tag-preloader="tagPreloader"
            @storeTag="testStoreData"
            @attachTag="attachTag"
            @detachTag="detachTag"
        />
    </div>
</template>

<script>
import CoreLibrary from "../../helpers/CoreLibrary";

export default {
    name: "TestTagManager",
    extends: CoreLibrary,
    data() {
        return {
            value: {},
            tagPreloader: false,
            tags: [],
            tagOptions: [
                {id: 1, value: 'Cricket', colors: '#e0e0e0'},
                {id: 2, value: 'Football', colors: '#bd5555'},
                {id: 3, value: 'Badminton', colors: '#d52828'},
                {id: 4, value: 'Option 4', colors: '#5e1414'},
                {id: 5, value: 'Option 5', colors: '#e8baba'},
                {id: 6, value: 'Option 6', colors: '#546cbb'},
                {id: 7, value: 'Option 7', colors: '#283fd5'},
                {id: 8, value: 'Option 8', colors: '#b21717'},
                {id: 9, value: 'Option 9', colors: '#05112f'},
                {id: 10, value: 'Option 10', colors: '#8c072f'},
            ]
        }
    },
    methods: {
        testStoreData(arg) {
            this.tagPreloader = true;
            this.addNewTagCreateOption(arg);
        },
        attachTag(tagId) {
            this.tags.push(tagId)
        },
        detachTag(tagId) {
            let index = this.tags.indexOf(tagId);
            this.tags.splice(index, 1);
        },
        addNewTagCreateOption(value) {
            this.axiosPost({
                url: 'store-tag-options',
                data: value
            }).then(response => {
                this.tagOptions = response.data.list;
                this.tags.push(response.data.new_id);
            }).finally(() => {
                this.tagPreloader = false;
            });
        },
    }
}
</script>
