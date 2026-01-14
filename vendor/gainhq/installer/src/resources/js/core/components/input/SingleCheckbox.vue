<template>
    <div class="customized-checkbox" :class="data.customCheckboxType">
        <input type="checkbox"
               :name="name"
               :id="inputFieldId"
               :disabled="data.disabled"
               :checked="value"
               :value="value"
               v-on="listeners"/>
        <label :for="inputFieldId" :class="data.labelClass">
            {{ data.placeholder }}
        </label>
    </div>
</template>

<script>
import {InputMixin} from "./mixin/InputMixin";

export default {
    name: "SingleCheckbox",
    mixins: [InputMixin]
}
</script>