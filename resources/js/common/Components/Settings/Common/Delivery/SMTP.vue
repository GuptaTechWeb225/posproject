<template>
    <div>
        <app-form-group
            :label="$t('host')"
            page="page"
            v-model="delivery.smtp_host"
            :error-message="$errorMessage(errors, 'smtp_host')"
            :placeholder="$placeholder('','host')"
        />

        <app-form-group
            :label="$t('port')"
            page="page"
            v-model="delivery.smtp_port"
            :error-message="$errorMessage(errors, 'smtp_port')"
            :placeholder="$placeholder('','port')"
        />

        <app-form-group
            :label="$t('encryption')"
            page="page"
            v-model="delivery.smtp_encryption"
            :error-message="$errorMessage(errors, 'smtp_encryption')"
            :placeholder="$placeholder('','encryption')"
        />

        <app-form-group
            :label="$fieldLabel('user', 'name')"
            page="page"
            v-model="delivery.smtp_user_name"
            :error-message="$errorMessage(errors, 'smtp_user_name')"
            :placeholder="$placeholder('user', 'name')"
        />

        <app-form-group
            :label="$t('password')"
            page="page"
            v-model="delivery.smtp_password"
            type="password"
            :error-message="$errorMessage(errors, 'smtp_password')"
            :placeholder="$placeholder('password')"
            :show-password="true"
        />
    </div>
</template>

<script>
import DeliveryMixin from "./DeliveryMixin";

export default {
    mixins: [DeliveryMixin],
    name: "SMTP",
    data() {
        return {
            delivery: {
                smtp_encryption: 'tls'
            }
        }
    }
}
</script>

<style scoped>

</style>
