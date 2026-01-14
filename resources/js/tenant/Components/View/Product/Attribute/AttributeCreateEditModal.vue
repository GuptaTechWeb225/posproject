<template>
    <modal id="attribute-modal"
           v-model="showModal"
           :title="generateModalTitle('variant_attribute')"
           @submit="submitData"
           :loading="loading"
           :scrollable="false"
           :preloader="preloader">

        <form
            ref="form"
            :data-url='selectedUrl ? selectedUrl : ATTRIBUTE'
            @submit.prevent="submitData">

            <app-form-group
                :label="$t('name')"
                :placeholder="$placeholder('name')"
                v-model="formData.name"
                :required="true"
                :error-message="$errorMessage(errors, 'name')"
            />

            <app-form-group-selectable
                type="multi-create"
                :class="`${ $isRtl ? 'text-left' : '' }`"
                :label="$t('variations')"
                list-value-field="name"
                v-model="formData.variations"
                :error-message="$errorMessage(errors, 'variations')"
                :fetch-url="SELECTABLE_VARIATIONS"
                :store-data="attributeVariationStore"
                @list="attributeVariations = $event"
                @input="chipChange"
            />
        </form>
    </modal>
</template>

<script>
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import ModalMixin from "../../../../../common/Mixin/Global/ModalMixin";
import {ATTRIBUTE, SELECTABLE_VARIATIONS} from "../../../../Config/ApiUrl-CP";

export default {
    name: "AttributeCreateEditModal",
    mixins: [FormHelperMixins, ModalMixin],

    data() {
        return {
            SELECTABLE_VARIATIONS,
            ATTRIBUTE,
            attributeVariations: [],
            formData: {
                variations: [],
                attribute_variation: [],
                chips: {}
            },

        }
    },
    methods: {
        afterSuccess({data}) {
            this.formData = {};
            $('#attribute-modal').modal('hide');
            this.$emit('update-values');
            this.$emit('input', false);
            this.toastAndReload(data.message, 'attributes-table');
        },
        afterSuccessFromGetEditData(response) {
            this.preloader = false;
            this.formData = response.data;
            const items = response.data.attribute_variations.map(item => item.id);
            const updateItem = response.data.attribute_variations.map(item => item.name);
            this.formData.attribute_variation = updateItem;
            this.formData.variations = items;
        },
        chipChange(variation) {
            const tem = this.attributeVariations.filter(({id}) => variation.includes(id)).map(e => e.name);
            this.formData.attribute_variation = tem;
        },
        attributeVariationStore(variationName) {
            let tempId = this.attributeVariations.length ? this.attributeVariations[this.attributeVariations.length - 1].id + 1 : Date.now();

            this.attributeVariations.push({
                id: tempId,
                name: variationName
            })

            this.formData.variations.push(tempId);

            this.formData.attribute_variation.push(variationName);

        }
    },
}
</script>
