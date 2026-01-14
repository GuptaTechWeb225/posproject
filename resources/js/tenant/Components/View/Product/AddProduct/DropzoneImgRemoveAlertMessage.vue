<template>
    <div class="margin-top-10 text-warning d-flex align-item-center">
        <app-icon name="alert-circle" class="mr-2"/>
        <p style="margin-top: 2px;"> {{ $t('clicking_remove_file_will_remove_the_image_indefinitely') }} </p>
    </div>
</template>

<script>
export default {
    name: 'DropzoneImgRemoveAlertMessage',
}
</script>
