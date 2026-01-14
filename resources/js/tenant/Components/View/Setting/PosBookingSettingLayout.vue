<template>
    <div class="content-wrapper">
        <app-page-top-section
            :title="this.$t('pos_settings')"
            icon="settings"
        />

        <app-tab
            :tabs="tabs"
            icon="settings"
        />
    </div>
</template>
<script>

export default {
    name: "PosBookingSettingLayout",
    data() {
        return {
            tabs: [
                {
                    headerButton: this.$can('view_payment_method') && {
                        "label": this.$fieldTitle('add', 'payment_method', true),
                    },
                    name: this.$t('payment_methods'),
                    title: this.$t('payment_methods'),
                    component: "app-payment-method-lists",
                    permission: this.$can('view_payment_method')
                },
                {
                    headerButton: this.$can('view_invoice_template') && {
                        "label": this.$fieldTitle('add', 'invoice_template', true),
                    },
                    name: this.$t('invoice_template'),
                    title: this.$t('invoice_template'),
                    component: "app-invoice-template",
                    permission: this.$can('view_invoice_template')
                },
                {
                    name: this.$t('sales_invoice_setup'),
                    title: this.$t('sales_invoice_setup'),
                    component: "app-sales-invoice-settings",
                    props: {alias: 'app'},
                    permission: Boolean(this.$can('view_delivery_settings') && Boolean(tenant.is_single))
                },
                {
                    name: this.$t('return_invoice_setup'),
                    title: this.$t('return_invoice_setup'),
                    component: "app-return-invoice-settings",
                    props: {alias: 'app'},
                    permission: Boolean(this.$can('view_delivery_settings') && Boolean(tenant.is_single))
                },
             
            ]
        }
    }
}
</script>
