<template>
    <div class="dropdown d-inline-block btn-dropdown keep-inside-clicks-open">
        <button type="button"
                class="btn btn-light dropdown-toggle border-right"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false">
            <app-icon :name="icon"/>
        </button>
        <div class="dropdown-menu py-0" :class="dropdownMenuClass">
            <slot></slot>
        </div>
    </div>
</template>

<script>
export default {
    name: "ContextButton",
    props: {
        icon: {
            require: true,
        },
        dropdownMenuClass: {
            type: String
        }
    }
}
</script>
