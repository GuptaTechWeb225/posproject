<template>
    <div>
        <app-tag-manager
            :list="tagOptions"
            :tag-preloader="tagPreloader"
            :tags="tags"
            list-value-field="name"
            @attachTag="attachTag"
            @detachTag="detachTag"
            @storeTag="testStoreData"
        />
    </div>
</template>

<script>
import CoreLibrary from "../../../../../core/helpers/CoreLibrary";
import {TagsMixin} from "../../../../../store/Tenant/Mixins/TagsMixin";
import {axiosPost} from "../../../../../common/Helper/AxiosHelper";
import {PRODUCTS} from "../../../../Config/ApiUrl-CP";

export default {
    name: "ProductTagsComponent",
    extends: CoreLibrary,
    mixins: [TagsMixin],
    props: ['rowData', 'tableId', 'value'],
    data() {
        return {
            tagPreloader: false,
            tags: [],
            tagOptions: []
        }
    },
    watch:{
        value: {
            handler: function (value) {
                this.tags = value.map(item => item.id);
            },
            deep: true,
            immediate: true
        }
    },
    computed: {
        attachTagUrl() {
            return `${PRODUCTS}/${this.rowData.id}/attach-tags`
        },
        detachTagUrl() {
            return `${PRODUCTS}/${this.rowData.id}/detach-tags`
        },
        attachStoreTagUrl() {
            return `${PRODUCTS}/${this.rowData.id}/attach-store-tags`
        }
    },
    methods: {
        testStoreData(arg) {
            this.tagPreloader = true;
            this.addNewTagCreateOption(arg);
        },
        attachTag(tag_id) {
            this.tags.push(tag_id)
            axiosPost(this.attachTagUrl, {tag_id}).catch((error) => {
                this.$toastr.e(error);
            });
        },
        detachTag(tag_id) {
            let index = this.tags.indexOf(tag_id);
            this.tags.splice(index, 1);

            axiosPost(this.detachTagUrl, {tag_id}).catch((error) => {
                this.$toastr.e(error);
            });
        },
        addNewTagCreateOption: function (data) {
            this.axiosPost({
                url: this.attachStoreTagUrl,
                data
            }).then(response => {
                this.tags.push(response.data.id);
                this.$store.dispatch('getTag');
            }).finally(() => {
                this.tagPreloader = false;
            });
        }
    }
}
</script>
