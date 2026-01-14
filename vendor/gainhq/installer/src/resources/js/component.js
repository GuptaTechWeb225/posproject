import Vue from "vue";

Vue.component('app-additional-requirement-wizard', require('./Components/AdditionalRequirementWizard').default);
Vue.component('app-purchase-code-wizard', require('./Components/PurchaseCodeWizard').default);
Vue.component('app-database-wizard', require('./Components/DatabaseWizard').default);
Vue.component('app-admin-wizard', require('./Components/AdminWizard').default);
Vue.component('app-email-setup-wizard', require('./Components/EmailSetupWizard').default);
Vue.component('app-pusher-setup-wizard', require('./Components/PusherSetupWizard').default);
Vue.component('app-update', require('./Components/template/Update').default);
Vue.component('app-update-confirmation-modal', require('./Components/template/UpdateConfirmationModal').default);
Vue.component('app-purchase-error-modal', require('./Components/template/PurchaseErrorModal').default);