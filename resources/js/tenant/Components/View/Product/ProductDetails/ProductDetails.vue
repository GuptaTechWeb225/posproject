<template>
    <div class="content-wrapper">
        <app-breadcrumb :page-title="ucWords($t('product_details'))"
                        :button="{label: $t('back_to_product_list'), url: backToProductList } "/>

        <div class="card card-with-shadow border-0 min-height-400">
            <div class="card-body position-relative">
                <app-overlay-loader v-if="preloader"/>
                <template v-else>

                    <div class="product-details-actions">
                        <dropdown-action
                            :actions="actions"
                            :unique-key="product.id"
                            :row-data="product"
                            @action="triggerAction"
                        />
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-xl-3 mb-4 mb-lg-0">
                            <product-gallery
                                v-if="productPhotos.length"
                                @product-image-update="product.photos = $event"
                                :product-id="productId"
                                :photos="productPhotos"
                                @view-gallery="handleViewGalleryBtnClick"
                            />
                        </div>
                        <div class="col-lg-8 col-xl-9">
                            <div class="mb-5">
                                <h5>
                                    {{ product?.name }}
                                </h5>
                                <div class="d-flex align-items-center">
                                    <span
                                        class="badge badge-pill  rounded-pill"
                                        :class="`badge-${product?.status?.class}`"
                                    >
                                        {{ product?.status?.translated_name }}
                                    </span>

                                    <span class="text-muted" :key="product.product_type">
                                        <app-icon :name="product.product_type === 'variant' ? 'layers' : 'hexagon'"
                                                  class="size-20 mx-2"/>
                                        {{ product.product_type }}
                                    </span>
                                </div>
                            </div>

                            <div class="text-muted mb-5">
                                <p class="mb-0">{{ $t('description') }}</p>
                                <hr class="mt-2 mb-3"/>
                                <p>{{ product?.description || $t('not_added_yet') }}</p>
                            </div>

                            <!--SPECIFICATIONS DETAILS SECTION-->
                            <div class="row justify-content-lg-between">
                                <div class="col-lg-5 mb-5 mb-lg-0">
                                    <h6 class="text-muted mb-0">{{ $t('specifications') }}</h6>
                                    <hr class="mt-2 mb-3"/>
                                    <div class="row">
                                        <div class="col-4">
                                            <p class="text-muted">{{ $t('brand') }}</p>
                                        </div>
                                        <div class="col-8">
                                            <p>
                                                {{ product.brand ? product.brand.name : $t('not_added_yet') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <p class="text-muted">{{ $t('group') }}</p>
                                        </div>
                                        <div class="col-8">
                                            <p>
                                                {{ product.group ? product.group.name : $t('not_added_yet') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <p class="text-muted">{{ $t('category') }}</p>
                                        </div>
                                        <div class="col-8">
                                            <p>
                                                {{ product.category ? product.category.name : $t('not_added_yet') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <p class="text-muted">{{ $t('sub-category') }}</p>
                                        </div>
                                        <div class="col-8">
                                            <p>
                                                {{
                                                    product.sub_category ? product.sub_category.name : $t('not_added_yet')
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <p class="text-muted">{{ $t('unit') }}</p>
                                        </div>
                                        <div class="col-8">
                                            <p>
                                                {{ product.unit ? product.unit.name : $t('not_added_yet') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <!--ITEM DETAILS SECTION-->
                                <div class="col-lg-6">
                                    <h6 class="text-muted mb-0">{{ $t('item_details') }}</h6>
                                    <hr class="mt-2 mb-3"/>

                                    <div class="row" v-if="product.product_type === 'single'">
                                        <div class="col-4">
                                            <p class="text-muted">{{ $t('upc') }}</p>
                                        </div>
                                        <div class="col-8">
                                            <p>
                                                {{ product?.variants?.length ? product.variants[0]?.upc : '-' }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-4">
                                            <p class="text-muted">{{ $t('warranty') }}</p>
                                        </div>
                                        <div class="col-8">
                                            <p>
                                                <span>{{ product.warranty_duration || 0 }}</span>
                                                <span>{{ product?.duration?.type }}</span>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="row" v-if="product.product_type === 'single'">
                                        <div class="col-4">
                                            <p class="text-muted">{{ $t('tax') }} (%) </p>
                                        </div>
                                        <div class="col-8">
                                            <template v-if="product?.variants[0]?.tax">
                                                <p>
                                                    {{
                                                        product?.variants?.length ? product.variants[0]?.tax?.percentage : '-'
                                                    }}
                                                </p>
                                            </template>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <p class="text-muted">{{ $t('low_stock_reminder_quantity') }}</p>
                                        </div>
                                        <div class="col-8">
                                            <p>
                                                {{
                                                    product?.product_type === 'variant' ? $t('mixed') : product?.variants?.length ? product.variants[0]?.stock_reminder_quantity : '-'
                                                }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <p class="text-muted">{{ $t('note') }}</p>
                                        </div>
                                        <div class="col-8">
                                            <p> {{ product.note || '-' }} </p>
                                        </div>
                                    </div>
                                </div>

                                <template v-if="product.product_type === 'single'">
                                    <!--PRODUCT PRICING SECTION-->
                                    <div class="col-lg-6">
                                        <h6 class="text-muted mb-0">{{ $t('product_pricing') }}</h6>

                                        <hr class="mt-2 mb-3"/>

                                        <div class="row">
                                            <div class="col-4">
                                                <p class="text-muted">{{ $t('avg_purchase_price') }}</p>
                                            </div>
                                            <div class="col-8">
                                                <p>-</p>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-4">
                                                <p class="text-muted">{{ $t('selling_price') }}</p>
                                            </div>
                                            <div class="col-8">
                                                <p>
                                                    ${{
                                                        product?.variants?.length ? product?.variants[0]?.selling_price : '-'
                                                    }}
                                                </p>
                                            </div>
                                        </div>

                                    </div>

                                    <!--STOCK DETAILS SECTION-->
                                    <div class="col-lg-6">
                                        <h6 class="text-muted mb-0">{{ $t('stock_details') }}</h6>
                                        <hr class="mt-2 mb-3"/>
                                        <div class="row">
                                            <div class="col-4">
                                                <p class="text-muted">{{ $t('quantity') }}</p>
                                            </div>
                                            <div class="col-8">
                                                <p>-</p>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <variants-table
            @variant-update="product.variants = $event"
            v-if="product.product_type === 'variant'"
            class="mt-primary"
            :product-id="productId"
        />

        <app-confirmation-modal
            v-if="modelOpen"
            :firstButtonName="$t('yes')"
            :modal-class="this.product.status.name === 'status_inactive' ? 'primary' : 'warning'"
            :icon="this.product.status.name === 'status_inactive' ? 'check-square' : 'slash'"
            :message="this.product.status.name === 'status_inactive' ? $t('you_are_going_to_activate_a_product') : $t('you_are_going_to_deactivate_a_product')"
            modal-id="app-confirmation-modal"
            @confirmed="changeStatus"
            @cancelled="cancelled"
        />

        <!-- for deleting product -->
        <app-confirmation-modal
            v-if="deleteModalActive"
            icon="trash-2"
            modal-id="product-delete-modal"
            @cancelled="cancelProductDelete"
            @confirmed="deleteProduct"
        />

        <GalleryModal
            v-if="galleryModalActive"
            v-model="galleryModalActive"
            :gallery-images="product.photos"
            @close="galleryModalActive = false"
        />
    </div>
</template>

<script>
import {axiosDelete, axiosGet, axiosPost} from "../../../../../common/Helper/AxiosHelper";
import {ucWords} from "../../../../../common/Helper/Support/TextHelper";
import DropdownAction from "../../../../../core/components/datatable/DropdownAction";
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import {
    PRODUCTS,
    PRODUCT_LIST,
} from "../../../../Config/ApiUrl-CP";
import {urlGenerator} from "../../../../Helper/Helper";
import GalleryModal from "../Variant/VariantComponent/GalleryModal";

export default {
    name: "ProductDetails",
    mixins: [FormHelperMixins],
    components: {DropdownAction, GalleryModal},
    props: {
        productId: {}
    },
    data() {
        return {
            deleteModalActive: false,
            productDeleteUrl: '',
            ucWords,
            parseInt,
            modelOpen: false,
            cancelStatus: '',
            PRODUCT_LIST,
            galleryModalActive: false,
            product: {},
            actions: [
                {
                    title: this.$t('edit'),
                    icon: 'edit',
                    type: 'edit',
                    name: 'edit',
                    modifier:() => this.$can('update_products')
                },
                // this is empty at first before being assigned properties from this.getProduct
                {},
                {
                  title: this.$t('show_stock_overview'),
                  name: 'show_stock_overview',
                  type: 'show_stock_overview',
                  modifier: row => row?.product_type?.toLowerCase() === 'single'
                },
                {
                    title: this.$t('delete'),
                    icon: 'trash-2',
                    type: 'delete',
                    name: 'delete',
                    url: PRODUCTS,
                    modifier:() => this.$can('delete_products')
                },
            ],
            statusActive: true,
        }
    },
    created() {
        this.getProduct();
    },
    computed: {
        backToProductList(){
          return urlGenerator(PRODUCT_LIST);
        },
        visibleTaxWarranty() {
            if (this.product.product_type === 'single') return true;
            const taxes = [...new Set(_.map(this.product.variants, 'tax_id'))];
            const warranty_duration = [...new Set(_.map(this.product.variants, 'warranty_duration'))];
            const low_stock_quantity = [...new Set(_.map(this.product.variants, 'low_stock_quantity'))];
            return warranty_duration.length === 1 && taxes.length === 1 && low_stock_quantity.length === 1
        },
        productPhotos() {
            if (!Object.keys(this.product).length) return [];
            return this.product.photos.map(photo => photo);
        }
    },
    methods: {
        deleteProduct() {
            axiosDelete(this.productDeleteUrl)
                .then(response => {
                    this.$toastr.s('', response.data.message);
                    // redirecting the user to the list page after displaying the toast message
                    setTimeout(() => window.location.replace(urlGenerator( '/products/list' )), 1500);
                })
                .catch((error) => (error.response) ? this.toastException(error.response.data) : null);
        },
        cancelProductDelete() {
            this.deleteModalActive = false;
            this.productDeleteUrl = '';
        },
        handleViewGalleryBtnClick() {
            this.galleryModalActive = true;
        },
        getProduct() {
            this.preloader = true;
            axiosGet(`${PRODUCTS}/${this.productId}/details`).then(({data}) => {
                this.product = data;
                if (this.product.product_type === 'single') this.actions[1] =
                    {
                        title: this.product.status.name === 'status_inactive' ? this.$t('activate') : this.$t('deactivate'),
                        type: 'change-status',
                        name: 'activate',
                        component: 'app-confirmation-modal',
                        modifier:() => this.$can('update_products')
                    }
            }).catch(() => {
                this.$toastr.e("product doesn't exist");
            })
                .finally(() => {
                this.preloader = false;
            });
        },
        triggerAction(row, action) {
            switch (action.type) {
                case 'show_stock_overview':
                  return window.location.replace(urlGenerator(`inventory/stock/overview/variant/${row.variants[0].id}`));
                  break;
                case 'change-status':
                    this.modelOpen = true;
                    break;
                case 'edit': // edit logic
                    window.location.replace(urlGenerator(`products/edit/${row.id}`));
                    break;
                case 'delete': // delete logic
                    this.deleteModalActive = true;
                    this.productDeleteUrl = `/app/products/${row.id}`;
                    break;
                default:
                    // default logic
                    break;
            }
        },
        cancelled() {
            this.modelOpen = false;
        },
        changeStatus() {
            axiosPost(`${PRODUCTS}/${this.product.id}/change-product-status`, {
                status: this.product.status.name,
            }).then(({data}) => {
                this.getProduct();
                this.$toastr.s(data.message);
            })
        }

    }
}

</script>