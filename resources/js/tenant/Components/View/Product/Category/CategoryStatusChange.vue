<template>
    <div class="d-flex">
        <app-input type="switch" v-model="active" />
        <span class="category-status d-inline-block ml-2">{{ active ? $t('status_active') : $t('status_deactive') }}</span>
    </div>
</template>

<script>
import { FormMixin } from "../../../../../core/mixins/form/FormMixin";

export default {
   name: 'app-category-status-change',
	mixins: [ FormMixin ],
   data() {
       return {
           // using a randomized value for the moment as backend category "status" is non-existent
           active: Math.random() > 0.5
       }
   },
}
</script>
