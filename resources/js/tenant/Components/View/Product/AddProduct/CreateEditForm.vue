<template>
    <form :data-url="url" ref="form">
        <div class="card border-0 card-with-shadow">
            <div class="card-body">
                <slot></slot>
                <div class="row">
                    <div class="col-12 mt-5">
                        <app-cancel-button @click="$emit('cancel')"/>
                        <app-submit-button btn-class="ml-2" :loading="loading" @click="submitData"/>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>

<script>

export default {
    name: "CreateEditForm",
    props: {
        url: {
            required: false
        },
        loading: {
            required: true
        },
        submitData: {
            required: true,
        }
    }
}
</script>
