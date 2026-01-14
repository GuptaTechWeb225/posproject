<template>

    <modal id="brand-modal"
           v-model="showModal"
           :title="generateModalTitle('brand')"
           @submit="submit" :loading="loading"
           :preloader="preloader">

        <form
            ref="form"
            :data-url='selectedUrl ? selectedUrl : BRAND'
            @submit.prevent="submitData">

            <div :class="`brand-name-input mb-3 ${ $isRtl ? 'text-left' : '' }`">
                <label for="brand-name-input">{{ $t('name') }}</label>
                <app-input
                    id="brand-name-input"
                    :label="$t('name')"
                    :placeholder="$placeholder('name')"
                    v-model="formData.name"
                    :error-message="$errorMessage(errors, 'name')"
                />
            </div>

            <div :class="`brand-description-input mb-3 ${ $isRtl ? 'text-left' : '' }`">
                <label for="brand-name-input">{{ $t('description') }}</label>
                <app-input
                    id="brand-textarea-input"
                    type="textarea"
                    :label="$t('description')"
                    :placeholder="$placeholder('description')"
                    v-model="formData.description"
                    :required="false"
                    :error-message="$errorMessage(errors, 'description')"
                />
            </div>

            <div :class="`brand-logo-input mb-3 ${ $isRtl ? 'text-left' : '' }`">
                <label for="brand-logo-input">{{ $t('logo') }}</label>
                <app-input
                    v-if="imageUploaderBoot"
                    id="brand-logo-input"
                    type="custom-file-upload"
                    v-model="brand_logo"
                    :generate-file-url="false"
                    :label="$t('change_brand_logo')"
                    :required="false"
                    :error-message="$errorMessage(errors, 'brand_logo')"
                />
            </div>
        </form>
    </modal>

</template>

<script>
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import ModalMixin from "../../../../../common/Mixin/Global/ModalMixin";
import {BRAND} from "../../../../Config/ApiUrl-CP";
import {formDataAssigner} from "../../../../../common/Helper/Support/FormHelper";
import {urlGenerator} from "../../../../../common/Helper/AxiosHelper";

export default {
    name: "BrandModal",
    mixins: [FormHelperMixins, ModalMixin],

    data() {
        return {
            BRAND,
            brand_logo: null,
            brandLogoChanged: false,
            brandLogoPath: '',
            formData: {
                name: '',
                description: ''
            },
            imageUploaderBoot: true
        }
    },
    watch: {
        brand_logo(newLogo, oldLogo) {
            if (!this.selectedUrl) return;
            this.brandLogoChanged = !this.brandLogoChanged;
            this.formData.brand_logo = newLogo;
        }
    },
    methods: {
        submit() {
            let url = this.selectedUrl ? this.selectedUrl : BRAND;
            let formData = {...this.formData, description: !this.formData.description ? '' : this.formData.description};

            formData = formDataAssigner(new FormData, formData);
            formData.append("brand_logo", this.brand_logo);

            if (this.selectedUrl) formData.append('_method', 'patch')
            this.submitFromFixin('post', url, formData);
            this.preloader = true;

        },
        afterSuccess({data}) {
            this.formData = {};
            this.preloader = false;
            $('#brand-modal').modal('hide');
            this.$emit('update-values');
            this.$emit('input', false);
            this.toastAndReload(data.message, 'brands-table');
        },
        afterSuccessFromGetEditData(response) {
            this.imageUploaderBoot = response.data.brand_logo ? false : this.imageUploaderBoot;
            this.preloader = false;
            this.formData = response.data;
            this.brandLogoPath = response.data.brand_logo.path;
            this.brand_logo = urlGenerator(response.data.brand_logo.path)
            this.imageUploaderBoot = true;
        },
    },
}
</script>
