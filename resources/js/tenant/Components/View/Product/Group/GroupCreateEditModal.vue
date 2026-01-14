<template>
    <modal id="group-modal"
           v-model="showModal"
           :loading="loading"
           :preloader="preloader"
           :title="generateModalTitle('group')"
           @submit="submitData">

        <form
            ref="form"
            :data-url='selectedUrl ? selectedUrl : GROUPS'
            @submit.prevent="submitData">

            <app-form-group
                v-model="formData.name"
                :error-message="$errorMessage(errors, 'name')"
                :label="$t('name')"
                :placeholder="$placeholder('name')"
                :required="true"
            />


          <div class="group-description-input mb-3" :class="`${ $isRtl ? 'text-left' : ''}`">
        <label for="items-add-input">{{ $t('description') }}</label>
            <app-input
                id="group-description-input"
                type="textarea"
                v-model="formData.description"
                :error-message="$errorMessage(errors, 'description')"
                :placeholder="$placeholder('description')"
                :required="false"
            />
          </div>
        </form>
    </modal>
</template>

<script>

import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import ModalMixin from "../../../../../common/Mixin/Global/ModalMixin";
import {GROUPS} from "../../../../Config/ApiUrl-CP";
import SelectableProductMixin from "../../../../Helper/SelectableOptions/SelectableProductMixin";
import HelperMixin from "../../../../../common/Mixin/Global/HelperMixin";
import {collection} from "../../../../../common/Helper/helpers";

export default {
    name: "GroupCreateEditModal",
    mixins: [FormHelperMixins, HelperMixin,ModalMixin, SelectableProductMixin],
    data() {
        return {
            discountInput: true,
            formData: {
                name: '',
                products: [],
                description: '',
            },
            GROUPS
        }
    },

    methods: {
        afterSuccess({data}) {
            this.formData = {name: '', items: [], group_description: '',};
            $('#group-modal').modal('hide');
            this.$emit('update-values');
            this.$emit('input', false);
            this.toastAndReload(data.message, 'group-table');
        },
        afterSuccessFromGetEditData(response){
            this.preloader = false;
            this.formData = {...response.data};
            this.formData.products = collection(response.data.products).pluck('id');
        }
    },
    mounted(){
        this.getAllProductsName()
    }
}
</script>

