import {axiosGet} from "../../../../../common/Helper/AxiosHelper";
import {collection} from "../../../../Helper/Helper";

export default {
    name: 'ProductEditMixin',
    data() {
        return {
            variants: {},
            variantAttributesArray: [], // used the fix the indexes on submit
            existingVariants: [],
            photos: {},
            dropzoneKey: 0,
            editDataLoaded: false,
            ssKey: 0, // search and select key
            productThumbnail: '',
            variantGalleryBackup: null,
            activeProductStatusId: '',
            // storing the selectedUrl with a different name due to conflicts in other modals in productCreateEdit
            productCreateEditSelectedUrl: '',
        }
    },
    methods: {
        setSelectedUrl() {
            this.productCreateEditSelectedUrl = `app/products/${this.productId}`;
        },
        setProductThumbnail() {
            if (!this.productThumbnail) return;
            this.formData.product_thumbnail = this.productThumbnail.full_url;
        },
        setSingleProductItemDetails() {
            const [ variant ] = this.variants;
            this.formData.tax_id = variant.tax_id;
            this.formData.upc = variant.upc;
            this.formData.selling_price = variant.selling_price;
            this.formData.stock_reminder_quantity = variant.stock_reminder_quantity;
        },
        setVariants() {
            if (this.variants.length) this.variants = this.variants.map(variant => ({
                ...variant,
                is_checked: +variant.status_id === +this.activeProductStatusId,
                variant_attributes: variant.attributes_variations.map(av => av.name),
                variant_thumbnail: variant.thumbnail,
                // this is necessary for upc validation
                upcData: {
                    showVariantUpcLoader: false,
                    variantUpcIsUnique: true,
                },
                variantThumbnailChanged: false, variant_gallery: variant.photos.length ? collection(variant.photos).pluck('path') : []
            }))
            this.existingVariants = JSON.parse(JSON.stringify(this.variants));
        },
        setVariationChips() {
            const attributeVariations = this.variants.map(variant => variant.attributes_variations);
            const variantAttributes = attributeVariations
                .map(av => av.map(ava => ava.attribute)).flat()
                .filter((v, i, arr) => i === arr.findIndex((t) => t.name === v.name))

            const variationChips = {};
            const attributeVariationsDupesRemoved = attributeVariations.flat().filter((v, i, arr) => i === arr.findIndex((t) => t.name === v.name));

            variantAttributes.forEach(({ name, id }) => {
                variationChips[name.toLowerCase()] = attributeVariationsDupesRemoved
                    .map(av => av.attribute.id === id ? av.id : null)
                    .filter(v => v)

                const currentAttributeValue = this.selectableList.attributes.find(element => element.id === id);
                this.attributeCombination.push(currentAttributeValue);
            });

            this.formData.variationChips = variationChips;
            this.existingVariationChips = JSON.parse(JSON.stringify(variationChips))
            this.variantAttributes = variantAttributes;
            this.attributeVariations = attributeVariationsDupesRemoved;
        },
        setTempObj() {
            const tempObj = {};
            this.variantAttributes.forEach(va => {
               tempObj[va.name] = [];
               for (const av of this.attributeVariations) {
                   if (av.attribute.id === va.id) tempObj[va.name] = [...tempObj[va.name], av.name];
               }
            });
            this.tempObj = tempObj;
            for (const v of Object.values(this.tempObj).flat())
                this.generateCartesian(Object.values(this.tempObj).filter(item => item.length > 0));
        },
    },
    computed: {
        editDataLoading() {
            if (!this.productCreateEditSelectedUrl) return false;
            if (!this.editDataLoaded) return true;
        }
    },
    async mounted() {
        if (!this.productId) return;
        this.setSelectedUrl();
        if (!this.isActive.attribute_definition) this.isActive.attribute_definition = true;

        const editData = await axiosGet(`app/products/${this.productId}`);
        this.variants = editData.data.variants;
        this.photos = editData.data.photos;

        this.productThumbnail = editData.data.thumbnail;
        this.productType = editData.data.product_type;

        if (this.productType === 'single') this.setSingleProductItemDetails();
        this.setVariants();
        this.setVariationChips();
        this.setProductThumbnail();

        this.variantAttributesArray = JSON.parse(JSON.stringify(this.variants.map(variant => variant.variant_attributes)));
        this.formData = {
            ...this.formData,
            ...editData.data,
            product_gallery: editData.data.photos.length ? collection(editData.data.photos).pluck('path') : [],
            variants: this.variants,
        };

        this.variantGalleryBackup = this.variants.map(v => v.photos.length ? collection(v.photos).pluck('path') : []);
        this.dropzoneKey += 1;
        this.editDataLoaded = true;
        this.setTempObj();
        this.ssKey += 1;
    }
}
