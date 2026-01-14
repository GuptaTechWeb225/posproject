import {TENANT_BASE_URL} from '../../common/Config/UrlHelper';


export const IMPORT_PRODUCT = `${TENANT_BASE_URL}app/import/products`;
export const TENANT_GENERAL_SETTING_URL = `${TENANT_BASE_URL}general/settings`;
export const TENANTS = `app/user/tenants`;
export const SELECTABLE_ROLE = `${TENANT_BASE_URL}selectable/roles`;
export {TENANT_SELECTABLE_USER as TENANT_SELECTABLE_USER} from "../../common/Config/apiUrl";
export const TEST_MAIL = `${TENANT_BASE_URL}app/test-mail/send`;

//Customer & Customer Group
export const CUSTOMERS = `${TENANT_BASE_URL}app/customers`;
export const CUSTOMER_ORDERS = `${TENANT_BASE_URL}app/customer/orders/`;
export const CUSTOMER_LIST = `${TENANT_BASE_URL}customer/lists`;
export const CUSTOMER_PROFILE_PICTURE_UPLOAD = `${TENANT_BASE_URL}app/customer/profile/picture/`;
export const CUSTOMER_DETAILS = `${TENANT_BASE_URL}/customer/details/`;
export const CUSTOMER_DATA = `${TENANT_BASE_URL}app/customer/details`;
export const CASH_REGISTER = `${TENANT_BASE_URL}app/selectable-cash-register`;
export const PERSONAL_INFO_UPDATE = `${TENANT_BASE_URL}/customer/details/`;
export const ADDRESS = `${TENANT_BASE_URL}app/customer/address`;
export const CUSTOMER_ADDRESS = `${TENANT_BASE_URL}app/address`;
export const CUSTOMER_INFO = `${TENANT_BASE_URL}app/customer/info`;
export const CUSTOMERS_COUNT = `${TENANT_BASE_URL}customer/count`;

// Customer Export
export const CUSTOMERS_EXPORT = `/${TENANT_BASE_URL}export/customers`;
export const CUSTOMER_EXPORT_SHEET = `${TENANT_BASE_URL}/export/sheet/`;

//Import Customer
export const CUSTOMERS_IMPORT = `${TENANT_BASE_URL}app/customers-import`;
export const CUSTOMERS_STORE = `${TENANT_BASE_URL}/app/customers/bulk-import`;

// Supplier
export const SUPPLIERS = `${TENANT_BASE_URL}app/suppliers`;
export const SUPPLIER_LIST = `${TENANT_BASE_URL}supplier/lists`;
export const SUPPLIER_DETAILS = `${TENANT_BASE_URL}/supplier/details/`;
export const SUPPLIER_PROFILE_PICTURE_UPLOAD = `${TENANT_BASE_URL}app/supplier/profile/picture/`;
export const SUPPLIER_ADDRESS = `${TENANT_BASE_URL}/app/supplier/address`;
export const SUPPLIER_ADDRESSES = `${TENANT_BASE_URL}app/supplier-all-address/`;



// Customer Group
export const CUSTOMER_GROUP = `${TENANT_BASE_URL}app/customer-groups`;
export const SELECTABLE_CUSTOMER_GROUP = `${TENANT_BASE_URL}selectable/customer/group`;

//Branch
export const SELECTABLE_BRANCH = `${TENANT_BASE_URL}selectable/branch`;
export const SELECTABLE_BRANCHES = `${TENANT_BASE_URL}app/selectable/branches`;
export const GET_BRANCH_STATUS = `${TENANT_BASE_URL}app/branch-status`;
export const BRANCH_OR_WAREHOUSE = `${TENANT_BASE_URL}app/branch_or_warehouses`;
export const BRANCHES_OR_WAREHOUSES = `${TENANT_BASE_URL}app/branch_or_warehouses`;

// Counter
export const COUNTERS = `${TENANT_BASE_URL}app/counters`;
export const SALES_PEOPLE = `${TENANT_BASE_URL}selectable-sales-person`;

//Invoice Template
export const INVOICE_TEMPLATE = `${TENANT_BASE_URL}app/invoice-templates`;
export const SELECTABLE_INVOICE_TEMPLATE = `${TENANT_BASE_URL}selectable/invoice-templates`;

//Tax
export const TAX = `${TENANT_BASE_URL}app/tax`;
export const ALL_TAX = `${TENANT_BASE_URL}app/tax?all=true`;
export const GET_TAX = `${TENANT_BASE_URL}app/get-tax`;
export const SELECTABLE_TAX = `${TENANT_BASE_URL}app/selectable-tax`;

//Coupon
export const COUPONS = `${TENANT_BASE_URL}app/coupons`;
export const SELECTABLE_DISCOUNTS = `${TENANT_BASE_URL}app/selectable-discounts`;


//Payment Methods
export const PAYMENT_METHOD = `${TENANT_BASE_URL}app/payment-method`;
export const GET_PAYMENT_METHOD_STATUS = `${TENANT_BASE_URL}app/payment-method-status`;

//Sms setting
export const SMS_SETTING = `${TENANT_BASE_URL}app/sms-settings`;
export const GET_SMS_SETTING = `${TENANT_BASE_URL}app/sms-setting-info`;

//Sms Template
export const SMS_TEMPLATE = `${TENANT_BASE_URL}app/sms-templates`;

//Import Supplier
export const SUPPLIER_IMPORT = `${TENANT_BASE_URL}app/supplier-import`;
export const SUPPLIER_STORE = `${TENANT_BASE_URL}/app/supplier/balk-import`;
export const SUPPLIER_EXPORT = `/${TENANT_BASE_URL}export/supplier/`;

//Payment
export const PAYPAL_ACTION_URL = `${TENANT_BASE_URL}/payment-paypal-action`;
export const STRIPE_ACTION_URL = `${TENANT_BASE_URL}/payment-stripe-action`;

//Report
export const LOT_REPORT = `${TENANT_BASE_URL}report/purchase`;
export const PURCHASE_SUMMARY = `${TENANT_BASE_URL}/report/purchase-summary`;
export const PURCHASE_REPORT_EXPORT = `${TENANT_BASE_URL}/report/purchase-export`;

//Supplier report
export const SUPPLIER_REPORT = `${TENANT_BASE_URL}report/supplier`;
export const SUPPLIER_SUMMARY = `${TENANT_BASE_URL}/report/supplier-summary`;
export const SUPPLIER_REPORT_EXPORT = `${TENANT_BASE_URL}/report/supplier-export`;

//stock / inventory report
export const PRODUCT_STOCK_REPORT_EXPORT = `${TENANT_BASE_URL}/report/product-stock-export`;
export const PRODUCT_STOCK_REPORT = `${TENANT_BASE_URL}report/product-stock`;

//Customer report
export const CUSTOMER_REPORT = `${TENANT_BASE_URL}report/customer`;
export const CUSTOMER_REPORT_EXPORT = `${TENANT_BASE_URL}/report/customer-export`;
