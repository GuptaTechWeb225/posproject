<template>
    <div>
        <div v-if="photos.length === 0"
             class="height-300 w-100 rounded border default-base-color d-flex align-items-center justify-content-center">
            <div :key="'photos-icon'"
                 class="width-100 height-100 rounded-circle primary-card-color d-flex align-items-center justify-content-center">
                <app-icon name="archive" class="size-45 text-muted"/>
            </div>
        </div>
        <div v-else
             id="productCarousel"
             class="carousel slide product-gallery-slider mb-3"
             data-ride="carousel"
             data-interval="false">
            <div class="carousel-inner">
                <div class="carousel-item"
                     :class="{'active': activeIndex === index}" :key="'photo'+index"
                     v-for="(photo, index) in computedPhotos"
                     :style="`background-image: url(${urlGenerator(photo.path)})`"/>
            </div>
        </div>

        <template v-if="photos.length >= 1 && !showDropzone">
            <div class="product-thumbnail-slider">
                <div class="slider-indicator left-indicator" @click="slideItems('prev')"
                     :disabled="atHeadOfList">
                    <app-icon name="chevron-left"/>
                </div>
                <div class="thumbnail-overflow-container">
                    <div class="thumbnail-wrapper" :style="`transform: translateX(${currentOffset}%)`">
                        <div class="thumbnail-item"
                             :class="{'active': activeIndex === index}"
                             v-for="(photo, index) in computedPhotos"
                             @click="changeCarousal(index)">
                            <div class="thumbnail-image"
                                 :data-slide-to="index"
                                 data-target="#productCarousel"
                                 :style="`background-image: url(${urlGenerator(photo.path)})`"/>
                        </div>
                    </div>
                </div>
                <div class="slider-indicator right-indicator" @click="slideItems('next')"
                     :disabled="atEndOfList">
                    <app-icon name="chevron-right"/>
                </div>
            </div>
            <div class="text-center mt-primary">
                <button class="btn btn-primary"
                        @click="prepareEditGallery"
                        type="button">
                    {{ $t('view_gallery') }}
                </button>
            </div>
        </template>
    </div>
</template>

<script>
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import {formDataAssigner} from "../../../../Helper/Helper";
import {PRODUCTS} from "../../../../Config/ApiUrl-CP";
import {axiosPost} from "../../../../../common/Helper/AxiosHelper";
import {urlGenerator} from "../../../../Helper/Helper";

export default {
    name: "ProductGallery",
    mixins: [FormHelperMixins],
    props: {
        productId: {},
        photos: {}
    },

    data() {
        return {
            urlGenerator,
            buttonLoading: {
                makeDefault: false,
                save: false,
            },
            isActive: {
                editGallery: true,
                makeDefault: false,
                save: false,
            },

            showDropzone: true,
            dropzone_files: [],
            formData: {
                product_id: this.productId
            },

            defaultImage: {},

            // Thumbnail slider
            activeIndex: 0,
            windowSize: 3,
            currentOffset: 0,
            paginationFactor: 33.33,
        }
    },

    methods: {
        slideItems(direction) {
            if (direction === 'next' && !this.atEndOfList) {
                this.currentOffset -= this.paginationFactor;
            } else if (direction === 'prev' && !this.atHeadOfList) {
                this.currentOffset += this.paginationFactor;
            }
        },
        prepareEditGallery() { 
            this.$emit('view-gallery');
        },
        saveProductImage() {
            this.buttonLoading.save = true;
            let formData = {...this.formData};
            formData = formDataAssigner(new FormData, this.formData);

            if (this.dropzone_files.length) {
                this.dropzone_files.forEach(photo => {

                    photo instanceof File ?
                        formData.append(`photos[]`,photo) :
                        formData.append(`dont_delete[]`, photo.dataURL)
                })
            }

            if (this.selectedUrl) {
                formData.append('_method', 'patch')
            }

            formData.append('default_index', this.activeIndex)

            return this.submitFromFixin(
                'post',
                `${PRODUCTS}/${this.productId}/photo-upload`,
                formData
            );
        },
        afterSuccess({data}) {
            this.buttonLoading.save = false;
            this.$toastr.s(data.message);
            this.showDropzone = false;

            this.$emit('product-image-update', data.photos)
        },
        makeDefault() {

            this.buttonLoading.makeDefault = true;
            let temp_photos = {
                file_id: this.photos[this.activeIndex].id,
                product_id: this.productId,
                photos: this.photos
            }

            axiosPost(`${PRODUCTS}/${this.productId}/make-default`, temp_photos).then(response => {
                this.buttonLoading.makeDefault = false;
                this.isActive.editGallery = true;
                this.isActive.makeDefault = false;
            })
        },
        changeCarousal(index) {
            this.activeIndex = index;
            this.isActive.makeDefault = true;
            this.isActive.editGallery = false;
            this.isActive.save = false
        }
    },

    computed: {
        atEndOfList() {
            return this.currentOffset <= (this.paginationFactor * -1) * (this.photos.length - this.windowSize);
        },
        atHeadOfList() {
            return this.currentOffset === 0;
        },
        computedPhotos() {
            return this.photos.filter(photo => photo);
        }
    },

    created() {
        if (this.photos.length > 1) {
            this.showDropzone = false;
        }
    },

    watch: {
        dropzone_files: function (file) {

            if (file.length === 0 && this.photos.length > 0) {
                this.isActive.save = true;
            } else this.isActive.save = file.length !== 0;
        },
        photos: {
            handler: function (photos) {
                if (!photos.length) return;
                photos.find((item, index) => {
                    if (!item) return;
                    if (item.type === 'product_default_image') {
                        this.activeIndex = index;
                        this.defaultImage = item;
                    }
                })

                this.showDropzone = photos.length === 0;
            },
            immediate: true
        }
    }
}
</script>