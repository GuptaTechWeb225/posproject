<template>
    <div class="d-flex product-variant-profile">
        <div>
            <div v-if="!rowDataPhotosLength"
                 class="width-150 height-160 rounded border default-base-color d-flex align-items-center justify-content-center">
                <div
                    class="width-60 height-60 rounded-circle primary-card-color d-flex align-items-center justify-content-center">
                    <app-icon name="archive" class="size-25 text-muted"/>
                </div>
            </div>
            <div class="d-flex flex-column align-items-center border" v-else>
                <img :src="variantThumbnail || defaultImage" alt="" class="rounded shadow mb-2" style="width: 140px; height: 150px;" />
                <p class="text-primary" @click="handleViewGalleryClick" style="cursor: pointer;" v-if="rowDataPhotosLength">{{ $t('view_gallery') }}</p>
            </div>
        </div>

        <div class="d-flex flex-column justify-content-between ml-3">
            <div>
                <p class="mb-0">{{ rowData.name ? rowData.name : '-' }}</p>
                <small class="d-block text-muted my-1">#{{ rowData.upc }}</small>
                <div>
                    <span
                        v-for="(variation, index) in rowData.attributes_variations"
                        class="badge badge-warning badge-sm rounded-pill mr-1">{{ variation.name }}</span>
                </div>
                <span
                    class="badge badge-pill rounded-pill badge-sm mt-2"
                    :class="`badge-${rowData.status.translated_name === 'Active' ? 'success' : 'danger' }`"
                >
                    {{ rowData.status.translated_name }}
                </span>
            </div>
        </div>

        <GalleryModal
            v-if="galleryModalActive"
            v-model="galleryModalActive"
            :gallery-images="rowData.photos"
            @close="galleryModalActive = false"
        />
    </div>
</template>


<script>
import {textTruncate} from '../../../../../../common/Helper/Support/TextHelper';
import {axiosPost} from "../../../../../../common/Helper/AxiosHelper";
import {VARIANT} from "../../../../../Config/ApiUrl-CP";
import {urlGenerator} from '../../../../../../common/Helper/AxiosHelper';
import GalleryModal from "./GalleryModal";

export default {
    name: "VariantImage",
    components: {GalleryModal},
    props: {
        'rowData': {},
        'tableId': {},
        'value': {}
    },

    data() {
        return {
            textTruncate,
            urlGenerator,
            galleryModalActive: false,
            buttonLoading: {
                makeDefault: false,
            },
            // Thumbnail slider
            activeIndex: 0,
            variantThumbnail: this.rowData?.thumbnail ? this.rowData.thumbnail.full_url : null
        }
    },
    methods: {
        handleViewGalleryClick() {
            this.galleryModalActive = true;
        },
        makeVariantDefault() {
            let temp_photos = {
                file_id: this.rowData.photos[this.activeIndex].id,
                variant_id: this.rowData.id,
                photos: this.rowData.id,
            }
            axiosPost(`${VARIANT}/${this.rowData.id}/make-default`, temp_photos).then(response => {
                this.$toastr.s(response.data.message);
            })
        },
    },
    computed: {
        rowDataPhotosLength() {
            if (this.rowData.photos) return this.rowData.photos.length;
            else return 0;
        },
        defaultImage() {
          return urlGenerator('images/product/default_product.png');
        },
    },
    watch: {
        rowData: {
            handler: function (rowData) {
                if (!Object.keys(rowData).length) return;
                if (rowData.photos) rowData.photos.find((item, index) => {
                    if (item.type === 'variant_default_image') {
                        this.activeIndex = index;
                    }
                });
            },
            immediate: true
        }
    }
}
</script>
