import {TENANT_BASE_URL} from '../../common/Config/UrlHelper';
//Update
export const APP_UPDATE = `${TENANT_BASE_URL}app/updates`;
export const APP_UPDATE_INSTALL = `${TENANT_BASE_URL}app/updates/install`;

export const TENANT_GENERAL_SETTING_URL = `${TENANT_BASE_URL}general/settings`;
export const GET_GENERAL_SETTINGS = `${TENANT_BASE_URL}app/get-general-settings`;
export const GET_CUSTOMER_DETAILS = `${TENANT_BASE_URL}app/customer/details/`;
export const TENANTS = `app/user/tenants`;
export const SELECTABLE_ROLE = `${TENANT_BASE_URL}selectable/roles`;
export const USER_STATUS_CHANGE = `${TENANT_BASE_URL}users/`;
export {TENANT_SELECTABLE_USER as TENANT_SELECTABLE_USER} from "../../common/Config/apiUrl";

// Variant Attribute
export const ATTRIBUTE = `${TENANT_BASE_URL}app/attributes`;
export const ATTRIBUTE_IMPORT = `${TENANT_BASE_URL}app/attribute/import`;
export const ATTRIBUTE_STORE = `${TENANT_BASE_URL}/app/attribute/bulk-import`;
export const EXPORT_ATTRIBUTE = `/${TENANT_BASE_URL}app/export/attributes`;
export const ATTRIBUTE_IMPORT_PAGE = `${TENANT_BASE_URL}/attribute/import`;
export const SELECTABLE_ATTRIBUTES = `${TENANT_BASE_URL}/app/selectable-attributes`;
export const SELECTABLE_VARIATIONS = `${TENANT_BASE_URL}/app/selectable-variations`;
export const SELECTABLE_DURATIONS = `${TENANT_BASE_URL}/app/selectable-durations`;
export const STORE_VARIATIONS = `${TENANT_BASE_URL}/app/store/variations`;

// Category
export const CATEGORIES = `${TENANT_BASE_URL}app/categories`;
export const CATEGORIES_IMPORT = `${TENANT_BASE_URL}app/categories-import`;
export const CATEGORY_IMPORT_PAGE = `${TENANT_BASE_URL}/category/import`;
export const CATEGORIES_STORE = `${TENANT_BASE_URL}/app/categories/bulk-import`;
export const EXPORT_CATEGORIES = `/${TENANT_BASE_URL}app/export/categories`;
export const CATEGORY_LIST = `/${TENANT_BASE_URL}category/lists`;

// Unit
export const UNIT = `${TENANT_BASE_URL}app/unit`;
export const UNIT_EXPORT = `${TENANT_BASE_URL}/app/export/units`;

// Group
export const GROUPS = `${TENANT_BASE_URL}app/groups`;
export const PRODUCT_GROUP_IMPORT = `${TENANT_BASE_URL}app/group/import`;
export const PRODUCT_GROUP_STORE = `${TENANT_BASE_URL}/app/group/bulk-import`;
export const EXPORT_GROUPS = `/${TENANT_BASE_URL}app/export/groups`;
export const GROUP_LIST = `/${TENANT_BASE_URL}group/lists`;
export const PRODUCT_GROUP_IMPORT_PAGE = `/${TENANT_BASE_URL}product/group/import`;

// Brand
export const BRAND = `${TENANT_BASE_URL}app/brands`;
export const BRAND_IMPORT = `${TENANT_BASE_URL}app/brand/import`;
export const BRAND_STORE = `${TENANT_BASE_URL}/app/brand/bulk-import`;
export const EXPORT_BRANDS = `${TENANT_BASE_URL}/app/export/brands`;

// Expense
export const EXPENSES = `${TENANT_BASE_URL}app/expenses`;
export const EXPENSE_AREAS = `${TENANT_BASE_URL}app/expense-areas`;
export const SELECTABLE_EXPENSE_AREA = `${TENANT_BASE_URL}selectable/expense-areas`;
export const EXPENSE_ATTACHMENT_DELETE = `${TENANT_BASE_URL}app/expense-attachment/delete/`;

// Created User
export const CREATED_USERS = `${TENANT_BASE_URL}/created-users`;

// Sub Category
export const SUB_CATEGORIES = `${TENANT_BASE_URL}/app/sub-categories`;
export const SELECTABLE_SUB_CATEGORIES = `${TENANT_BASE_URL}/app/selectable-sub-categories`;

// Countries
export const COUNTRIES = `${TENANT_BASE_URL}app/selectable-countries`;
export const ADDRESS_AREA = `${TENANT_BASE_URL}/app/address-areas`;
export const ADDRESS_CITY = `${TENANT_BASE_URL}/app/address-city`;

// Product
export const PRODUCTS = `${TENANT_BASE_URL}/app/products`;
export const SELECTABLE_TAGS = `${TENANT_BASE_URL}/app/selectable-tags`;
export const VARIATION_STORE = `${TENANT_BASE_URL}/app/variation-store`;

// Product Details
export const PRODUCT_DETAILS = `/${TENANT_BASE_URL}products/`;
export const PRODUCT_LIST = `/${TENANT_BASE_URL}products/list`;
export const PRODUCT_IMPORT = `/${TENANT_BASE_URL}app/import/products`;
export const PRODUCT_EXPORT_ALL = `/${TENANT_BASE_URL}app/export/product/all`;

// VARIANT
export const VARIANT = `/${TENANT_BASE_URL}app/variant`;
export const VARIANT_STATUS = `/${TENANT_BASE_URL}app/variant-status`;
export const CHANGE_VARIANT_IMAGE = `/${TENANT_BASE_URL}app/variant/image/change/`;
export const PRODUCT_PHOTO_UPLOAD = `/${TENANT_BASE_URL}app/product/photo/upload`;
export const PRODUCT_MAKE_DEFAULT = `/${TENANT_BASE_URL}app/product/make/default`;
export const CHANGE_VARIANT_STATUS = `${TENANT_BASE_URL}` + '/' + PRODUCTS + '/change-variant-status';

// Stock
export const STOCK = `${TENANT_BASE_URL}app/stock`;
export const INVENTORY = `${TENANT_BASE_URL}inventory/stock`;
export const STOCK_OVERVIEW = `${TENANT_BASE_URL}app/stock-overview/variant/`;


// Stock Adjustment
export const STOCK_ADJUSTMENTS = `${TENANT_BASE_URL}app/stock-adjustments`;
export const DELETE_STOCK_ADJUSTMENT = `${TENANT_BASE_URL}app/stock-adjustments/`;

//Lot
export const LOT = `${TENANT_BASE_URL}app/lot`;
export const SELECTABLE_BRANCH = `${TENANT_BASE_URL}app/selectable/branches`;
export const SELECTABLE_SUPPLIERS = `${TENANT_BASE_URL}app/selectable-suppliers`;
export const SELECTABLE_RECEIVED_BY = `${TENANT_BASE_URL}app/selectable/received-bys`;

// Internal transfer
export const INTERNAL_TRANSFER = `${TENANT_BASE_URL}app/internal-transfers`;
export const CHANGE_INTERNAL_TRANSFER_STATUS = `${TENANT_BASE_URL}/app/change-internal-transfer-status/`;

//Sales
export const GENERATE_INVOICE = `${TENANT_BASE_URL}app/generate-sales-invoice/`;
export const INVOICE_LIST = `${TENANT_BASE_URL}app/invoice-list`;
export const ORDER_INVOICE_VIEW = `${TENANT_BASE_URL}app/order-return-view/`;
export const INVOICE_VIEW = `${TENANT_BASE_URL}app/invoice-view/`;
export const RETURN_ORDER_VIEW = `${TENANT_BASE_URL}app/sale-return-view/`;
export const ORDER_MAX_MIN_PRICE = `${TENANT_BASE_URL}app/order-max-min-price`;
export const RETURN_ORDER_MAX_MIN_PRICE = `${TENANT_BASE_URL}app/return-order-max-min-price`;
export const RETURN_ORDER_LIST = `${TENANT_BASE_URL}app/return-order-list`;
export const DUE_PAYMENT_RECEIVE = `${TENANT_BASE_URL}app/due-payment-receive/`;
export const DUE_PAYMENT_INFO = `${TENANT_BASE_URL}app/due-payment-info/`;

// Returns
export const SELECTABLE_INVOICES = `${TENANT_BASE_URL}app/selectable-invoice`;
export const SELECTABLE_CUSTOMERS = `${TENANT_BASE_URL}app/selectable-customers`;
export const SELECTABLE_USERS = `${TENANT_BASE_URL}app/selectable/users`;
export const MANAGE_USERS = `${TENANT_BASE_URL}app/manage-users/branch_or_warehouse/`;
export const STORE_RETURN_ORDER = `${TENANT_BASE_URL}app/store-return-order`;
export const STORE_ORDER = `${TENANT_BASE_URL}app/order-store`;
export const HOLD_ORDER = `${TENANT_BASE_URL}app/order-hold`;
export const ON_HOLD_ORDERS = `${TENANT_BASE_URL}app/on-hold-orders`;
export const HOLD_ORDER_DELETE = `${TENANT_BASE_URL}app/hold-order-delete/`;

//Discount
export const DISCOUNT_LIST = `${TENANT_BASE_URL}app/discount-list`;


//Install
export const APP_LOG_IN = `${TENANT_BASE_URL}admin/users/login`;
export const APP_INSTALL_ADMIN_INFO = `${TENANT_BASE_URL}setup/admin-info`;
export const GENERATE_PURCHASE_CODE_URL = `${TENANT_BASE_URL}setup/generate-url`;
export const GET_DATABASE_HOSTNAME = `${TENANT_BASE_URL}setup/get-database-hostname`;
export const GET_UPDATE_URL = `${TENANT_BASE_URL}app/generated-update-url-purchase-code`;
export const SET_UP_EMAIL = `${TENANT_BASE_URL}setup/email-setup`;
export const SET_UP_BROADCAST = `${TENANT_BASE_URL}setup/broadcast-setup`;
export const BROADCAST_SETTING_UPDATE = `${TENANT_BASE_URL}setup/broadcast-setting-update`;
export const BROADCAST_SKIP = `${TENANT_BASE_URL}setup/broadcast-skip`;
export const EMAIL_SETUP_SKIP = `${TENANT_BASE_URL}setup/email-setup-skip`;
export const ADDITIONAL_REQUIREMENTS = `${TENANT_BASE_URL}setup/additional-requirements`;
export const ADDITIONAL_REQUIREMENT = `${TENANT_BASE_URL}setup/additional-requirement`;
export const DATABASE_CONFIGURATION = `${TENANT_BASE_URL}setup/database`;
export const PURCHASE_CODE = `${TENANT_BASE_URL}setup/purchase-code`;
export const PURCHASE_CODE_STORE = `${TENANT_BASE_URL}setup/purchase-code-store`;
export const INSTALL = `${TENANT_BASE_URL}install`;
export const EMAIL_SETTING_UPDATE = `${TENANT_BASE_URL}setup/email-setting-update-delivery`;
export const CLEAR_CACHE = `${TENANT_BASE_URL}app/clear-cache`;
export const CRON_JOB_SETTING = `${TENANT_BASE_URL}admin/app/settings/cronjob`;
export const MAIL_SETUP_CHECK_URL = `${TENANT_BASE_URL}check-mail-settings`;
export const JOB_POINT_EMAIL_SETUP_SETTING = `${TENANT_BASE_URL}app-setting?tab=Email%20Setup`;
export const NOTIFICATION_SETTINGS_FRONT_END = `${TENANT_BASE_URL}app-setting?tab=Notification`;
export const CANCEL_INVITATION = `${TENANT_BASE_URL}cancel-invitation`;
export const PRODUCT_IMAGE_DELETE = `${TENANT_BASE_URL}app/product-image/delete/`;
export const SALES_VIEW_PRODUCTS = `${TENANT_BASE_URL}app/sale/products`;
export const SINGLE_VARIANT = `${TENANT_BASE_URL}app/variant`;
export const STOCK_VARIANT_WITH_BRANCH = `${TENANT_BASE_URL}app/stock-variant-with-branch/`;
export const VARIANT_PRODUCT_TAX = `${TENANT_BASE_URL}app/variant-product-tax-and-discount/`;
export const GENERATE_UPC = `${TENANT_BASE_URL}app/product/get_upc`;
export const GENERATE_BARCODE = `${TENANT_BASE_URL}app/inventory/generate-barcode`;
export const GENERATE_QRCODE = `${TENANT_BASE_URL}app/inventory/generate-qrcode`;
export const GET_VARIANT_BY_BARCODE = `${TENANT_BASE_URL}app/inventory/get-variant-by-barcode-or-qrcode/`;
export const PAYMENT_METHODS = `${TENANT_BASE_URL}app/payment-methods`;
export const DISCOUNT_INFO = `${TENANT_BASE_URL}app/discount-info`;

export const SELECTABLE_INTERNAL_TRANSFER_STATUSES = `${TENANT_BASE_URL}app/selectable-internal-transfer-status`;
export const SELECTABLE_BRANCHES_OR_WAREHOUSES = `${TENANT_BASE_URL}app/selectable-branches-or-warehouses`;
export const SELECTABLE_STOCK_ADJUSTMENT_VARIANTS_WITH_BRANCH = `${TENANT_BASE_URL}/app/selectable-stock-adjustment-variants`;
export const SELECTABLE_PRODUCTS = `${TENANT_BASE_URL}/app/selectable-products`;
export const SELECTABLE_LOT = `${TENANT_BASE_URL}/app/selectable-lot`;
export const SELECTABLE_LOT_VARIANTS = `${TENANT_BASE_URL}/app/selectable-lot-variants`;
export const GET_AVAILABLE_STOCK = (variantId, branchId) => `${TENANT_BASE_URL}/app/get-available-stock/variant/${variantId}/branch_or_warehouse/${branchId}`;
export const SELECTABLE_TAX = `${TENANT_BASE_URL}/app/selectable-tax`;
export const SELECTABLE_VARIANT_ATTIRBUTES = `${TENANT_BASE_URL}/app/selectable-attributes`;
export const SELECTABLE_GROUPS = `${TENANT_BASE_URL}/app/selectable-groups`;
export const SELECTABLE_CATEGORIES = `${TENANT_BASE_URL}/app/selectable-categories`;
export const SELECTABLE_BRANDS = `${TENANT_BASE_URL}/app/selectable-brands`;
export const SELECTABLE_UNITS = `${TENANT_BASE_URL}/app/selectable-units`;
export const SELECTABLE_CASH_REGISTERS = `${TENANT_BASE_URL}/app/selectable-cash-register`;
export const CASH_COUNTER_OPEN_CLOSE = `${TENANT_BASE_URL}/app/cash-counter-open-close`;
export const CASH_REGISTER_INFO = `${TENANT_BASE_URL}app/cash-counter-information/`;
export const CASH_COUNTER_OPEN_CLOSE_STATUS = `${TENANT_BASE_URL}app/cash-counter-open-close-status`;

export const SELECTABLE_VARIANTS = `${TENANT_BASE_URL}/app/selectable-variants`;


//report
//sale
export const SALES_REPORT = `${TENANT_BASE_URL}/report/sales`;
export const SALES_SUMMARY = `${TENANT_BASE_URL}/report/sales-summary`;
export const SALES_REPORT_EXPORT = `${TENANT_BASE_URL}/app/export/sales`;


export const CASH_COUNTER_REPORT = `${TENANT_BASE_URL}/report/cash-counter`;
export const CASH_COUNTER_EXPORT = `${TENANT_BASE_URL}/app/export/cash-counter`;


//return
export const SALES_RETURN_REPORT = `${TENANT_BASE_URL}/report/sales-return`;
export const SALES_RETURN_SUMMARY = `${TENANT_BASE_URL}/report/sales-return-summary`;
export const SALES_RETURN_REPORT_EXPORT = `${TENANT_BASE_URL}/app/export/sales-return`;

//top-selling product
export const TOP_SELLING_PRODUCT_REPORT = `${TENANT_BASE_URL}/report/top-selling-products`;
export const TOP_SELLING_PRODUCT_MAX_MIN_PRICE = `${TENANT_BASE_URL}/report/top-selling-products-max-min-price`;
export const TOP_SELLING_PRODUCT_SUMMARY = `${TENANT_BASE_URL}/report/top-selling-products-summary`;
export const TOP_SELLING_PRODUCT_REPORT_EXPORT = `${TENANT_BASE_URL}/app/export/top-selling-products`;

//expense report
export const EXPENSE_REPORT = `${TENANT_BASE_URL}/report/expense`;

//user report
export const USER_REPORT = `${TENANT_BASE_URL}/report/users`;
export const USER_REPORT_EXPORT = `${TENANT_BASE_URL}/app/export/users`;
export const USER_SALE_DETAILS_REPORT = `${TENANT_BASE_URL}/report/user/sales/`;
export const USER_SALE_RETURN_REPORT = `${TENANT_BASE_URL}/report/user/sales/return/`;
export const USER_PURCHASE_REPORT = `${TENANT_BASE_URL}/report/user/purchase/`;

//branch warehouse report
export const BRANCH_WAREHOUSE_REPORT = `${TENANT_BASE_URL}/report/branch-warehouse/`;
export const BRANCH_WAREHOUSE_EXPORT = `${TENANT_BASE_URL}/app/export/branch-warehouse`;

//profit los report
export const PROFIT_LOSS_REPORT = `${TENANT_BASE_URL}/report/profit-loss`;
export const PROFIT_LOSS_REPORT_EXPORT = `${TENANT_BASE_URL}/app/export/profit-loss`;


//Dashboard apis
export const DASHBOARD_HIGHLIGHT = `${TENANT_BASE_URL}/dashboard/highlights`;
export const DASHBOARD_RECENT_SALES = `${TENANT_BASE_URL}/dashboard/recent-sales`;
export const DASHBOARD_TOP_SELLING_PRODUCTS = `${TENANT_BASE_URL}/dashboard/top-selling-products`;
export const DASHBOARD_TOP_CUSTOMERS = `${TENANT_BASE_URL}/dashboard/top-customers`;
export const DASHBOARD_STOCK_SUMMARY = `${TENANT_BASE_URL}/dashboard/stock-summary`;
export const DASHBOARD_PURCHASE_SALE_STATISTICS = `${TENANT_BASE_URL}/dashboard/purchase-sale-statistics`;
