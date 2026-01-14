<template>
    <vertical-tab
        v-if="type === 'vertical'"
        :tabs="tabs" :icon="icon"
        :query-string="queryString"
    />
    <horizontal-tab
        v-else
        :tabs="tabs"
        :query-string="queryString"
        :bodyClass="bodyClass"
        :tabClass="tabClass"
    />
</template>
<script>
import VerticalTab from "./VerticalTab";
import HorizontalTab from "./HorizontalTab";

export default {
    name: "AppTab",
    components: {HorizontalTab, VerticalTab},
    props: {
        tabs: {
            type: Array,
            require: true
        },
        type: {
            type: String,
            default: 'vertical'
        },
        icon: {
            type: String
        },
        queryString: {
            type: Boolean,
            default: true
        },
        tabClass: {
            type: String,
            default: 'p-primary'
        },
        bodyClass: {
            type: String,
            default: 'p-primary'
        }
    }
}
</script>

