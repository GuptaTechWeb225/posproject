<template>
   <app-modal
      modal-id="activation-alert-modal"
      modal-size="medium"
      modal-alignment="center"
      @close-modal="closeActivationPopup"
   >
      <template slot="body">
        <div class="text-center mt-4">
            <app-icon name="flag" class="mb-4 size-64 primary-text-color" />

            <h3 class="mb-4">{{ $t('no_selling_price_added') + '!' }}</h3>

            <p class="text-muted mb-4">
               {{ $t('you_must_set_a_selling_price_to_activate_a_product') }}
            </p>
           <button @click="closeActivationPopup" class="btn btn-primary">{{ $t('close') }}</button>
         </div>
      </template>
   </app-modal>
</template>

<script>
export default {
   name: 'app-activation-alert-popup',
   data() {
      return {};
   },
   methods: {
      closeActivationPopup() {
         this.$emit('activation-popup-close');
      },
   },
};
</script>
