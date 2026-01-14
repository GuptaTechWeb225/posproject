<template>
    <div>
        <input type="text" v-bind:value="value" @input="input($event)">
    </div>
</template>
<script>
    export default {
        name: "TestInput",
        props: ['value'],
        methods: {
            input($event) {
                this.$emit('input', $event.target.value); //for v-model use
            },
            findMixin() {
                let instance = this,
                    parent = instance;

                //we'll find upto 10 level of parent to get the mixin
                for (let i = 0; i < 10; i++) {
                    if (parent.mixinName === "formMixin") {
                        instance.mixinInstance = parent;
                        break;
                    }
                    parent = parent.$parent;
                }
            }
        }
    }
</script>
