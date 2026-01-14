<template>
    <app-modal modal-id="app-product-import-modal" modal-size="large" modal-alignment="top" @close-modal="closeModal">
       <!-- Modal header -->
       <template slot="header">
          <h5 class="modal-heading">{{ $t('import_product') }}</h5>
          <button class="close outline-none" @click.prevent="closeModal"><app-icon name="x" /></button>
       </template>
 
       <!-- Modal body -->
       <template slot="body">
          <form ref="form" :data-url="''">
            <app-input type="dropzone" v-model="formData.file" />
          </form>
       </template>
 
       <!-- Modal Footer -->
       <template slot="footer" class="justify-content-start">
            <app-submit-button :loading="loading" btn-class="d-inline-flex text-center mr-2" :label="$t('import')" @click="save" />
            <app-cancel-button btn-class="btn-secondary" @click="closeModal"/>
       </template>
    </app-modal>
 </template>
 
 <script>
 import { FormMixin } from '../../../../core/mixins/form/FormMixin';
 import HelperMixin from "../../../../common/Mixin/Global/HelperMixin";
 
 export default {
    name: 'app-product-import-modal',
    mixins: [FormMixin, HelperMixin],
    data() {
       return {
            formData: {
                file: null
            },
            loading: false
       };
    },
    methods: {
       closeModal() {
            $('#app-product-import-modal').modal('hide')
            this.$emit('modal-close');
       },
       save() {
        this.loading = true
        const formData = new FormData();
        formData.append('import_file', this.formData.file[0])
        
        let url = 'app/import/products'

        this.axiosPost({ url, data: formData }).then(res => {
            this.toastAndReload(res.data.message, 'product-table');
            this.closeModal();
        }).catch(({response}) => {
            this.$toastr.e(response.data.message);
        }).finally(() => {
            this.loading = false
        })
       }
    },
    mounted(){
        $('#app-product-import-modal').modal('show')
    }
 };
 </script>

 