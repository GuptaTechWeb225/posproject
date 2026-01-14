<template>
    <div class="content-wrapper">
        <app-page-top-section :title="$t('product_list')">

            <div class="dropdown d-inline-block btn-dropdown">

                <button class="btn btn-success dropdown-toggle ml-0 mr-2"
                        aria-expanded="false"
                        aria-haspopup="true"
                        data-toggle="dropdown"
                        type="button">
                    {{ $t('action') }}
                </button>

                <div class="dropdown-menu"> 
                    <a class="dropdown-item d-flex align-items-center p-3"
                       href="#" @click="redirectToImportPage"
                       v-if="this.$can('import_products')"
                    >
                        <app-icon class="size-15 mr-2" name="download"/>
                        {{ $t('import') }}
                    </a>
                    <a class="dropdown-item d-flex align-items-center p-3" href="#" @click="exportTableData">
                        <app-icon class="size-15 mr-2" name="upload"/>
                        {{ $t('export_all_data') }}
                    </a>

                </div>
            </div>

            <app-default-button
                v-if="this.$can('create_products')"
                :title="$addLabel('product')"
                :url="('/products/create')"/>

        </app-page-top-section>

        <app-table
            id="product-table"
            :options="options"
            @action="triggerAction"
        />

        <!-- Specification modals -->
        <app-group-modal
            v-if="modalStates.group.isActive"
            v-model="modalStates.group.isActive"
            @close="modalStates.group.isActive = false"
        />

        <app-category-modal
            v-if="modalStates.category.isActive"
            v-model="modalStates.category.isActive"
            @close="modalStates.group.isActive = false"
        />

        <app-sub-category-modal
            v-if="modalStates.subCategory.isActive"
            v-model="modalStates.subCategory.isActive"
            @close="modalStates.subCategory.isActive = false"
        />

        <app-brand-modal
            v-if="modalStates.brand.isActive"
            v-model="modalStates.brand.isActive"
            @close="modalStates.brand.isActive = false"
        />

        <app-unit-create-edit-modal
            v-if="modalStates.unit.isActive"
            v-model="modalStates.unit.isActive"
            @close="modalStates.unit.isActive = false"
        />

        <!-- for deleting product -->
        <app-confirmation-modal
            v-if="confirmationModalActive"
            icon="trash-2"
            modal-id="app-confirmation-modal"
            @cancelled="cancelled"
            @confirmed="confirmed('product-table')"
        />

        <!-- for activating/de-activating product -->
        <app-confirmation-modal
            v-if="Boolean(statusChangeUrl)"
            icon="alert-circle"
            modal-id="app-confirmation-modal"
            :message="$t('are_you_sure_you_want_to_change_the_status')"
            @cancelled="cancelProductStatusChange"
            @confirmed="changeProductStatus"
        />

        <import-modal v-if="importDialog" @modal-close="closeImportDialog"/>

    </div>
</template>

<script>
import HelperMixin from "../../../../common/Mixin/Global/HelperMixin";
import ProductMixin from "../../Mixins/ProductMixin";
import {PRODUCT_EXPORT_ALL, PRODUCTS} from "../../../Config/ApiUrl-CP";

import ProductsModal from "./ProductsModal.vue";
import {axiosDelete, axiosGet, axiosPatch, urlGenerator} from "../../../../common/Helper/AxiosHelper";
import StatusQueryMixin from "../../../../common/Mixin/Global/StatusQueryMixin";
import ImportModal from './ImportModal.vue';

export default {
    name: "Products",
    mixins: [HelperMixin, ProductMixin, StatusQueryMixin],
    components: {
        ProductsModal,
        ImportModal
    },
    data() {
        return {
            importDialog: false,
            isModalActive: false,
            statusChangeUrl: '',
            modalStates: {
                group: {isActive: false,},
                category: {isActive: false,},
                subCategory: {isActive: false,},
                brand: {isActive: false,},
                unit: {isActive: false,},
            },
        }
    },
    methods: { 
        redirectToImportPage() {
            window.location.replace(urlGenerator('/import/product'));
        },
        closeImportDialog(){
            this.importDialog = false
        },
        exportTableData() {
            window.open(urlGenerator(PRODUCT_EXPORT_ALL));
        },
        changeProductStatus() {
            axiosPatch(this.statusChangeUrl)
                .then(response => {
                    this.toastAndReload(response.data.message, 'product-table');
                    this.statusChangeUrl = '';
                }).catch((error) => {
                if (error.response)
                    this.toastException(error.response.data)
            });
        },
        cancelProductStatusChange() {
            this.statusChangeUrl = '';
        },
        triggerAction(row, action, active) {
            switch (action.name) {
                case 'edit':
                    this.selectedUrl = `${PRODUCTS}/${row.id}`;
                    window.location.replace(urlGenerator(`products/edit/${row.id}`));
                    break;
                case 'deactivate':
                    this.statusChangeUrl = `app/products/${row.id}/change-status?status=${false}`;
                    break;
                case 'activate':
                    this.statusChangeUrl = `app/products/${row.id}/change-status?status=${true}`;
                    break;
                case 'add_to_inventory':
                    break;
                case 'show_stock_overview':
                    return window.location.replace(urlGenerator(`inventory/stock/overview/variant/${row.variants[0].id}`));
                    break;
                default:
                    this.getAction(row, action, active)
            }
        },
    },
    mounted() {
        this.getProductStatuses();
    }
}
</script>
