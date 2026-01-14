import {
    SELECTABLE_ATTRIBUTES,
    SELECTABLE_BRANDS,
    SELECTABLE_CATEGORIES,
    SELECTABLE_GROUPS,
    SELECTABLE_SUB_CATEGORIES,
    SELECTABLE_UNITS,
    SELECTABLE_DURATIONS,
    STOCK,
    VARIATION_STORE,
} from "../../../../Config/ApiUrl-CP";
import {SELECTABLE_TAX} from "../../../../Config/ApiUrl-CPB";
import {capitalizeFirst} from "../../../../../common/Helper/Support/TextHelper";
import GenerateCartesianMixin from "./GenerateCartesianMixin";
import {axiosGet, urlGenerator} from "../../../../../common/Helper/AxiosHelper";

export default {
    mixins: [GenerateCartesianMixin],
    mounted() {
        this.fetchWarrantyDurations();
    },
    data() {
        return {
            STOCK,
            VARIATION_STORE,
            SELECTABLE_GROUPS,
            SELECTABLE_CATEGORIES,
            SELECTABLE_SUB_CATEGORIES,
            SELECTABLE_BRANDS,
            SELECTABLE_UNITS,
            SELECTABLE_TAX,
            SELECTABLE_ATTRIBUTES,
            capitalizeFirst,
            parseInt,
            variantDisabledIndex: [],
            availabilityIsChecked: true,
            productThumbnailChanged: false,
            isActive: {
                group: false,
                category: false,
                subCategory: false,
                brand: false,
                unit: false,
                attribute: false,
                attribute_definition: false,
                variant: false,
                leaving: false,
                isSubmitted: false,
                renderComponent: true,
            },
            tempItemDetails: {
                warranty_duration: '',
                duration_id: '',
                tax_id: '',
                stock_reminder_quantity: ''
            },
            formData: {
                name: '',
                product_thumbnail: '',
                product_gallery: [],
                product_type: 'single',
                unit_id: null,
                brand_id: null,
                group_id: null,
                category_id: null,
                sub_category_id: null,
                status_id: '',
                warranty_duration: '',
                duration_id: '2',
                description: '',
                tax_id: null,
                attribute_id: '',
                variationChips: {},
                variants: [],
                variantAttributes: [],
                stock_reminder_quantity: '',
                upc: '',
                note: '',
                selling_price: '',
                variations: [],
            },
            selectableList: {
                productTypes: [
                    {id: 'single', value: this.$t('single_product')},
                    {id: 'variant', value: this.$t('variant_product')}
                ],
                groups: [],
                categories: [],
                subcategories: [],
                brands: [],
                units: [],
                warranty_duration_type: [],
                taxes: [],
                attributes: [],
            },
            attributeCombination: [],
            tempCombination: [],
            tempObj: {},
            variantRow: {},
            preventRedirect: null,
            formChangeCount: 0,
        }
    },
    watch: {
        errors() {
            // attaching the existing product_gallery images if errors suddenly appear after submit
            this.formData.product_gallery = this.existingProductGalleryImgs;
            this.formData.product_thumbnail = this.existingProductThumbnail;
        }
    },
    methods: {
        fetchWarrantyDurations() {
            axiosGet(SELECTABLE_DURATIONS)
            .then(res => {
                if (res.data?.length) {
                    this.selectableList.warranty_duration_type = res.data 
                    this.formData.duration_id = this.selectableList.warranty_duration_type[0].id;
                }
            }).catch(e => {
                this.$toastr.e('something_went_wrong');
                console.log(e);
            });
        },
        afterSuccess({data}) {
            this.formDataBeingSubmitted = false;
            this.toastAndReload(data.message);
            window.onbeforeunload = function (e) {};
            window.location.replace(urlGenerator('products/list'));
        },
        afterError(response) {
            this.formDataBeingSubmitted = false;
            this.message = '';
            this.loading = false;
            this.isActive.renderComponent = true;
            this.errors = response.data.errors || {};
            this.scrollToTop(false);
            if (response.status != 422)
                this.$toastr.e(response.data.message || response.statusText)
        },
    },
}


