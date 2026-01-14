import Vue from 'vue'
import UrlMixin from "./Config/UrlMixin";
import TenantMixin from './Config/TenantMixin'
import Warehouse from "./Components/View/Warehouse/Warehouse/Warehouse";

Vue.mixin(UrlMixin);
Vue.mixin(TenantMixin);

// Dashboard
Vue.component('app-dashboard', require('./Components/View/Dashboard/Dashboard').default);

// Helper Component
Vue.component('app-data-export', require('./Components/Helper/AppDataExport').default);
Vue.component('app-tenant-manager', require('./Components/Helper/TopbarComponents/TenantManager').default);
Vue.component('app-branch-manager', require('./Components/Helper/TopbarComponents/BranchManager').default);
Vue.component('app-note-editor', require('./Components/Helper/AppNoteEditor').default)

// Category
Vue.component('app-category-lists', require('./Components/View/Product/Category/Category').default);
Vue.component('app-category-modal', require('./Components/View/Product/Category/CategoryCreateEditModal').default);
Vue.component('app-category-import', require('./Components/View/Product/Category/CategoryImport').default);
Vue.component('app-category-import-preview-modal', require('./Components/View/Product/Category/ImportPreviewModal').default);
Vue.component('app-sub-categories', require('./Components/View/Product/Category/SubCategoriesComponent').default);
Vue.component('app-category-status-change', require('./Components/View/Product/Category/CategoryStatus').default);
Vue.component('sub-category-details', require('./Components/View/Product/Category/SubCategoryDetails').default);

// Unit
Vue.component('app-unit-lists', require('./Components/View/Product/Unit/Unit').default);
Vue.component('app-unit-create-edit-modal', require('./Components/View/Product/Unit/UnitCreateEditModal').default);
Vue.component('app-unit-status-change', require('./Components/View/Product/Unit/UnitStatusChangeInput').default);

// Group
Vue.component('app-group-lists', require('./Components/View/Product/Group/Group').default);
Vue.component('app-group-description', require('./Components/View/Product/Group/GroupDescription').default);
Vue.component('app-group-modal', require('./Components/View/Product/Group/GroupCreateEditModal').default);
Vue.component('app-product-group-import', require('./Components/View/Product/Group/Import').default);
Vue.component('app-product-group-import-preview-modal', require('./Components/View/Product/Group/ImportPreviewModal').default);

// Attribute
Vue.component('app-attributes', require('./Components/View/Product/Attribute/Attributes').default);
Vue.component('app-attribute-modal', require('./Components/View/Product/Attribute/AttributeCreateEditModal').default);
Vue.component('app-attribute-import', require('./Components/View/Product/Attribute/Import').default);
Vue.component('app-attribute-import-preview-modal', require('./Components/View/Product/Attribute/ImportPreviewModal').default);

// Attribute
Vue.component('app-attributes', require('./Components/View/Product/Attribute/Attributes').default);
Vue.component('app-attribute-modal', require('./Components/View/Product/Attribute/AttributeCreateEditModal').default);
Vue.component('app-attribute-import', require('./Components/View/Product/Attribute/Import').default);
Vue.component('app-attribute-import-preview-modal', require('./Components/View/Product/Attribute/ImportPreviewModal').default);
Vue.component('variant-value', require('./Components/View/Product/Attribute/VariantValue').default)

// Brand
Vue.component('app-brands', require('./Components/View/Product/Brand/Brands').default);
Vue.component('brand-profile', require('./Components/View/Product/Brand/BrandProfile').default);
Vue.component('app-brand-modal', require('./Components/View/Product/Brand/BrandModal').default);
Vue.component('app-brand-import', require('./Components/View/Product/Brand/Import').default);
Vue.component('app-brand-import-preview-modal', require('./Components/View/Product/Brand/ImportPreviewModal').default);

// Expense
Vue.component('app-expense', require('./Components/View/Expense/Expense').default);
Vue.component('app-expanse-create-edit-modal', require('./Components/View/Expense/ExpenseCreateEditModal').default);
Vue.component('app-expense-area', require('./Components/View/Expense/ExpenseArea').default);
Vue.component('app-expanse-area-create-edit-modal', require('./Components/View/Expense/ExpenseAreaCreateEditModal').default);
Vue.component('app-expense-added-to-report', require('./Components/View/Expense/ExpenseAddedToReport').default);

// SubCategory
Vue.component('app-sub-category', require('./Components/View/Product/SubCategory/SubCategory').default);
Vue.component('app-sub-category-modal', require('./Components/View/Product/SubCategory/SubCategoryCreateEditModal').default);

// Attachment Component
Vue.component('app-attachment', require('./Components/View/Expense/AttachmentComponent').default);

// Product
Vue.component('app-product-crete-edit', require('./Components/View/Product/AddProduct/ProductCreateEdit').default);
Vue.component('app-crete-edit-form', require('./Components/View/Product/AddProduct/CreateEditForm').default);
Vue.component('app-products-list', require('./Components/View/Product/Products').default);
Vue.component('is-variant-products', require('./Components/View/Product/Component/IsVariantComponent').default);
Vue.component('products-status', require('./Components/View/Product/Component/ProductStatusComponent').default);
Vue.component('products-profile', require('./Components/View/Product/Component/ProductProfileComponent').default);
Vue.component('products-variant-details', require('./Components/View/Product/Component/VariantDetails').default);
Vue.component('products-tags', require('./Components/View/Product/Component/ProductTagsComponent').default);

Vue.component('app-import-product', require('./Components/View/Product/ImportProduct').default);


// Product Details
Vue.component('product-details', require('./Components/View/Product/ProductDetails/ProductDetails').default);
Vue.component('product-gallery', require('./Components/View/Product/ProductDetails/ProductGallery').default);
Vue.component('variants-table', require('./Components/View/Product/Variant/Variants').default);

// Variant
Vue.component('variant-add-edit-modal', require('./Components/View/Product/Variant/VariantCreateEditModal').default);
Vue.component('variant-profile', require('./Components/View/Product/Variant/VariantComponent/Gallery').default);
Vue.component('variant-sku', require('./Components/View/Product/Variant/VariantComponent/Sku').default);
Vue.component('variant-warranty', require('./Components/View/Product/Variant/VariantComponent/Warranty').default);
Vue.component('variant-status', require('./Components/View/Product/Variant/VariantComponent/Status').default);
Vue.component('app-variant-create-edit-modal', require('./Components/View/Product/AddProduct/VariantDetailsCreateEditModal').default);

//Inventory
Vue.component('app-stock-view', require('./Components/View/Inventory/Stock').default);
Vue.component('app-stock-create-edit-modal', require('./Components/View/Warehouse/WarehouseStock/StockAddEditModal').default);
Vue.component('app-stock-adjustment-create-edit-modal', require('./Components/View/Warehouse/WarehouseStock/StockAdjustmentModal').default);
Vue.component('app-internal-transfer-create-edit-modal', require('./Components/View/Warehouse/InternalTransfer/InternalTransferCreateEditModal').default);
Vue.component('app-branch-stock', require('./Components/View/Inventory/BranchStock').default);
Vue.component('app-internal-transfer', require('./Components/View/Inventory/InternalTransfer').default);
Vue.component('app-internal-transfer-status', require('./Components/View/Inventory/InternalTransferStatus').default);
Vue.component('app-receive-product', require('./Components/View/Inventory/ReceiveProduct').default);
Vue.component('app-inventory-stock', require('./Components/View/Inventory/Stock').default);
Vue.component('app-activation-alert-popup', require('./Components/View/Inventory/ActivationPopupAlert').default);
Vue.component('app-lot-view', require('./Components/View/Warehouse/Lot/Lot').default);
Vue.component('app-supplier-link', require('./Components/View/Warehouse/Lot/SupplierLink').default);
Vue.component('app-lot-create-edit', require('./Components/View/Warehouse/Lot/LotCreateEdit').default);
Vue.component('app-lot-create-edit-modal', require('./Components/View/Warehouse/Lot/LotAddEditModal').default);
Vue.component('app-print-barcode', require('./Components/View/Warehouse/PrintBarcode/PrintBarcode').default);
Vue.component('app-print-qrcode', require('./Components/View/Warehouse/PrintQrCode/PrintQrCode').default);
Vue.component('app-stock-adjustment', require('./Components/View/Warehouse/StockAdjustment/StockAdjustment').default);
Vue.component('app-stock-adjustment-note', require('./Components/View/Warehouse/StockAdjustment/StockAdjustmentNoteInfo').default);
Vue.component('app-stock-transfer', require('./Components/View/Warehouse/StockTransfer/StockTransfer').default);
Vue.component('app-import-stock', require('./Components/View/Warehouse/ImportStock/ImportStock').default);
Vue.component('app-lot-status', require('./Components/View/Warehouse/Lot/LotStatus').default);

Vue.component('app-stock-overview', require('./Components/View/Warehouse/Stock/StockOverview').default);
Vue.component('stock-profile', require('./Components/View/Inventory/StockProfileComponent').default);
Vue.component('lot-profile', require('./Components/View/Inventory/LotProfileComponent').default);

//Warehouse
Vue.component('app-warehouse-list', require('./Components/View/Warehouse/Warehouse/Warehouse').default);
Vue.component('app-warehouse-stock-view', require('./Components/View/Warehouse/WarehouseStock/WareHouseStock').default);

//Discount
Vue.component('app-discount-list', require('./Components/View/Discount/DiscountList').default);
Vue.component('app-discount-create-edit-modal', require('./Components/View/Discount/DiscountCreateEditModal').default);

//Update
Vue.component('app-update', require('./Components/View/Update/template/Update').default);
Vue.component('app-manual-updater', require('./Components/View/Update/template/ManualUpdater').default);
Vue.component('app-update-confirmation-modal', require('./Components/View/Update/template/UpdateConfirmationModal').default);

// Return
Vue.component('app-return-add-edit-modal', require('./Components/View/POS/Return/ReturnAddEditModal').default);

//Report
//Sales report
Vue.component('app-sales-report', require('./Components/View/Reports/Sales/SalesReport').default);
Vue.component('sales-summary', require('./Components/View/Reports/Sales/SalesSummary').default);

//Expense report
Vue.component('app-expense-report', require('./Components/View/Reports/Expense/ExpenseReport').default);

//Branch warehouse report
Vue.component('app-branch-warehouse-report', require('./Components/View/Reports/BranchWarehouse/BranchWarehouseReport').default);

//Cash counter report
Vue.component('app-cash-counter-report', require('./Components/View/Reports/CashCounter/Index').default);

//Return report
Vue.component('app-sales-return-report', require('./Components/View/Reports/Return/SalesReturnReport').default);
Vue.component('sales-return-summary', require('./Components/View/Reports/Return/SalesReturnSummary').default);

//Top-selling product report
Vue.component('app-top-selling-product-report', require('./Components/View/Reports/TopSellingProduct/TopSellingProductReport').default);
Vue.component('top-selling-product-summary', require('./Components/View/Reports/TopSellingProduct/TopSellingProductSummary').default);

//User report
Vue.component('app-user-report', require('./Components/View/Reports/User/UserReport').default);
Vue.component('app-user-details-button', require('./Components/View/Reports/User/UserDetailsButton').default);
Vue.component('app-user-report-details', require('./Components/View/Reports/User/UserDetailsReport').default);
Vue.component('app-user-sales-report-tab', require('./Components/View/Reports/User/Tab/SalesReportTab').default);
Vue.component('app-user-sales-return-report-tab', require('./Components/View/Reports/User/Tab/SaleReturnReportTab').default);
Vue.component('app-user-purchase-report-tab', require('./Components/View/Reports/User/Tab/PurchaseReportTab').default);

//Profit loss report
Vue.component('app-profit-loss-report', require('./Components/View/Reports/ProfitLoss/ProfitLossReport').default);
Vue.component('app-counter-created-by', require('./Components/View/Counter/CreatedBy.vue').default);
Vue.component('app-counter-action-by', require('./Components/View/Counter/ActionBy.vue').default);

