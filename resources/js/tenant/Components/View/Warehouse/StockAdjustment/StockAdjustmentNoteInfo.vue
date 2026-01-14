<template>
    <div id="note-dropdown"
         class="dropdown keep-inside-clicks-open dropdown-note-editor d-inline-block">
        <button type="button"
                class="btn p-0 mb-1 primary-text-color"
                data-toggle="dropdown">
            <app-icon name="file-text" class="size-18"/>
        </button>
        <div class="dropdown-menu p-primary mt-1">
            <div>
                <p class="text-secondary">{{ value }}</p>

                <div :class="`${ $isRtl ? 'text-left' : 'text-right ' }`">
                    <a href="#"
                       class="btn btn-secondary"
                       @click.prevent="closeDropDown">
                        {{ $t('close') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "StockAdjustmentNote",
    props: ['rowData', 'value'],
    methods: {
        closeDropDown() {
            $(".dropdown-menu").removeClass('show')
        },
    }
}
</script>

<style scoped lang="scss">
.dropdown {
    .dropdown-menu {
        width: 350px;

        .note {
            overflow-y: auto;
            max-height: 200px;
        }
    }
}
</style>
