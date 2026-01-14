<template>
    <modal modal-id="gallery-modal"
           v-model="showModal"
           :title="$t('gallery')"
           size="large"
    >
        <div>
            <div class="row">
                <div class="col-4 mb-5" v-for="img in galleryImages" :key="img.path">
                    <img class="gallery-img rounded shadow" :src="urlGenerator( img.full_url )" alt="" />
                </div>
            </div>
        </div>
    </modal>
</template>

<script>
import ModalMixin from "../../../../../../common/Mixin/Global/ModalMixin";
import HelperMixin from "../../../../../../common/Mixin/Global/HelperMixin";
import {urlGenerator} from "../../../../../Helper/Helper";

export default {
    name: "GalleryModal",
    mixins: [ModalMixin, HelperMixin],
    props: {
        galleryImages: {}
    },
    data() {
        return {
            preloader: false,
            loading: false,
            urlGenerator,
        }
    },
    computed: {},
}
</script>

<style scoped>
.gallery-img {
    width: 15rem;
    height: 15rem;
}
</style>
