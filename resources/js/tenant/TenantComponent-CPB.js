import Vue from 'vue'
import UrlMixin from "./Config/UrlMixin";
import TenantMixin from './Config/TenantMixin'

Vue.mixin(UrlMixin);
Vue.mixin(TenantMixin);

// POS
Vue.component('app-sales-view', require('./Components/View/POS/SalesView').default);
Vue.component('app-invoice-view', require('./Components/View/POS/Invoice/InvoiceView').default);
Vue.component('app-invoice-modal', require('./Components/View/POS/Invoice/InvoiceViewModal').default);
Vue.component('app-due-payment-modal', require('./Components/View/POS/Invoice/DuePaymentModal').default);
Vue.component('app-invoice-number', require('./Components/View/POS/Invoice/InvoiceNumber').default);
Vue.component('app-sale-invoice-modal', require('./Components/View/POS/Return/ReturnInvocieModal').default);

//Return
Vue.component('app-sales-return-view', require('./Components/View/POS/Return/ReturnListView').default);
Vue.component('app-sales-return-modal', require('./Components/View/POS/Return/ReturnAddEditModal').default);
Vue.component('app-return-invoice-number', require('./Components/View/POS/Return/ReturnInvoiceNumber').default);


//Supplier
Vue.component('app-supplier-lists', require('./Components/View/Contact/Suppliers').default);
Vue.component('app-supplier-img', require('./Components/View/Contact/SupplierImage').default);
Vue.component('app-supplier-action', require('./Components/View/Contact/SupplierAction').default);
Vue.component('app-supplier-contact', require('./Components/View/Contact/SupplierContact').default);
Vue.component('app-supplier-address-info', require('./Components/View/Contact/SupplierAddressInfo').default);
Vue.component('app-supplier-modal', require('./Components/View/Contact/SupplierCreateEditModal').default);
Vue.component('app-supplier-import', require('./Components/View/Contact/SupplierImport').default);
Vue.component('app-supplier-import-preview-modal', require('./Components/View/Contact/Components/ImportSupplierPreviewModal').default);
Vue.component('app-supplier-export-lists', require('./Components/View/Contact/SuppliersExportLists').default);
Vue.component('app-supplier-details', require('./Components/View/Contact/Details/Supplier/SupplierDetails').default);

// Supplier Details
Vue.component('app-supplier-personal-info', require('./Components/View/Contact/Details/Supplier/SupplierPersonalInfo').default);
Vue.component('app-supplier-address', require('./Components/View/Contact/Details/Supplier/SupplierAddress').default);
Vue.component('app-supplier-address', require('./Components/View/Contact/Details/Supplier/SupplierAddress').default);


// Customer
Vue.component('app-customer-lists', require('./Components/View/Contact/Customers').default);
Vue.component('app-customer-groups', require('./Components/View/Contact/CustomerGroup').default);
Vue.component('app-customer-modal', require('./Components/View/Contact/CustomerCreateEditModal').default);
Vue.component('app-customer-image', require('./Components/View/Contact/CustomerImage').default);
Vue.component('app-customer-import', require('./Components/View/Contact/CustomerImport').default);
Vue.component('app-customer-import-preview-modal', require('./Components/View/Contact/Components/ImportPreviewModal').default);
Vue.component('app-customer-group-modal', require('./Components/View/Contact/CustomerGroupCreateEditModal').default);
Vue.component('app-form-group-selectable', require('./Components/Helper/AppFromGroupSelectable').default);
Vue.component('app-customer-export-lists', require('./Components/View/Contact/CustomersExportLists').default);

// Customer Details
Vue.component('app-customer-action', require('./Components/View/Contact/Details/CustomerAction').default);
Vue.component('app-customer-details', require('./Components/View/Contact/Details/CustomerDetails').default);
Vue.component('app-customer-address', require('./Components/View/Contact/Details/Address').default);
Vue.component('app-customer-personal-info', require('./Components/View/Contact/Details/PersonalInfo').default);
Vue.component('app-customer-order-list', require('./Components/View/Contact/Details/UserOrderList').default);
Vue.component('app-customer-contact', require('./Components/View/Contact/Details/CustomerContact').default);
Vue.component('app-customer-address-info', require('./Components/View/Contact/Details/AddressInfo').default);
Vue.component('app-address-modal', require('./Components/View/Contact/Details/AddressCreateEditModal').default);

//Settings
Vue.component('app-tenant-settings-layout', require('./Components/View/Setting/TenantSettingLayout').default)
Vue.component('app-tenant-general-settings', require('./Components/View/Setting/Component/TenantGeneralSetting').default)
Vue.component('app-tenant-notification-settings', require('./Components/View/Setting/Component/TenantNotificationSetting').default)
Vue.component('app-tenant-pos-booking-settings-layout', require('./Components/View/Setting/PosBookingSettingLayout').default)

//Branch
Vue.component('app-branch-lists', require('./Components/View/Setting/Branch/Branches').default)
Vue.component('app-branch-users-modal', require('./Components/View/Setting/Branch/BranchUsersModal').default)
Vue.component('counter-details', require('./Components/View/Setting/Branch/CounterDetails').default)
Vue.component('app-branch-status', require('./Components/View/Setting/Branch/Status').default)

Vue.component('app-branch-warehouse', require('./Components/View/Reports/BranchWarehouse').default);
Vue.component('all-branch-warehouses', require('./Components/View/Reports/ViewAllBranchOrWarehousesModal').default);

// warehouse
Vue.component('warehouse-create-edit-modal', require('./Components/View/Warehouse/Warehouse/WarehouseModal').default)

// Reports
Vue.component('report-page', require('./Components/View/Reports/Reports').default)
Vue.component('app-lot-report', require('./Components/View/Reports/LotReport').default);
Vue.component('app-customer-report', require('./Components/View/Reports/CustomerReport').default);
Vue.component('purchase-summary', require('./Components/View/Reports/LotSummary').default);
Vue.component('app-stock-report', require('./Components/View/Reports/StockReport').default);
Vue.component('app-supplier-report', require('./Components/View/Reports/SupplierReport').default);
Vue.component('supplier-summary', require('./Components/View/Reports/SupplierSummary').default);

// Cash Counter
Vue.component('app-cash-counter', require('./Components/View/Counter/Counter').default)
Vue.component('app-counter-modal', require('./Components/View/Counter/CounterAddEditModal').default)


//Invoice Template
Vue.component('app-invoice-template', require('./Components/View/InvoiceTemplate/InvoiceTemplate').default)
Vue.component('app-invoice-template-modal', require('./Components/View/InvoiceTemplate/InvoiceTemplateAddEditModal').default)
Vue.component('app-branch-lists', require('./Components/View/Setting/Branch/Branches').default)
Vue.component('branch-create-edit-modal', require('./Components/View/Setting/Branch/BranchCreateEditModal').default)

//Invoice Setting
Vue.component('app-sales-invoice-settings', require('./Components/View/Setting/Invoice/SalesInvoiceSetting').default)
Vue.component('app-return-invoice-settings', require('./Components/View/Setting/Invoice/ReturnInvoiceSetting').default)


Vue.component('app-payment-note-column',require('./Components/View/POS/Invoice/PaymentNoteColumn').default);


//Tax
Vue.component('app-tax-lists', require('./Components/View/Tax/Taxes').default)
Vue.component('tax-create-edit-modal', require('./Components/View/Tax/TaxCreateEditModal').default)

//Coupon
Vue.component('app-coupon-lists', require('./Components/View/Coupon/Coupon').default);
Vue.component('app-coupon-modal', require('./Components/View/Coupon/CouponCreateEditModal').default);

//Payment Methods
Vue.component('app-payment-method-lists', require('./Components/View/PaymentMethod/PaymentMethods').default)
Vue.component('payment-method-create-edit-modal', require('./Components/View/PaymentMethod/PaymentMethodCreateEditModal').default)
Vue.component('app-paypal-button', require('./Components/View/Payment/Paypal').default)
Vue.component('app-stripe-layout', require('./Components/View/Payment/Stripe').default)


//Sms Settings
Vue.component('sms-setting', require('./Components/View/Setting/SmsSetting/SmsSetting').default);

//Sms Template
Vue.component('sms-template', require('./Components/View/Setting/SmsTemplate/SmsTemplate').default);
Vue.component('app-sms-template-modal', require('./Components/View/Setting/SmsTemplate/SmsTemplateAddEditModal').default);
Vue.component('is-default-for-datatable', require('./Components/View/Tax/IsDefaultForDataTable').default);


//reset password..
Vue.component('app-password-reset', require('../common/Components/Auth/Password/RequestResetPassword').default);
Vue.component('app-reset-password', require('../common/Components/Auth/Password/ResetPassword').default)