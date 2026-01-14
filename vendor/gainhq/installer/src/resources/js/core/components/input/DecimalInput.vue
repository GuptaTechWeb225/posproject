<template>
    <!--input-->
    <input :type="'number'"
           :name="name"
           :id="inputFieldId"
           :required="data.required"
           :class="'form-control '+data.inputClass"
           :placeholder="data.placeholder"
           :readonly="data.readOnly"
           :disabled="data.disabled"
           v-bind:value="value"
           v-on="listeners"
           :autocomplete="data.autocomplete"
    />
</template>

<script>
import accounting from 'accounting';
import {InputMixin} from './mixin/InputMixin.js';

export default {
    name: "DecimalInput",

    mixins: [InputMixin],
    data() {
        return {
            formattedValue: ''
        }
    },
    methods: {

    },
}
</script>
