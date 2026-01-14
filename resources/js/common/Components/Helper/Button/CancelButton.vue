<template>
    <button @click="$emit('click')"
            type="button"
            :class="`btn `+btnClass">
        {{ $t(label) }}
    </button>
</template>

<script>
export default {
    name: "CancelButton",
    props: {
        label: {
            default: function () {
                return 'cancel';
            }
        },
        btnClass: {
            default: 'btn-dark'
        }
    }
}
</script>
