import {axiosGet, axiosPost, urlGenerator} from "../../../../../common/Helper/AxiosHelper";
import {
    PRODUCTS,
    SELECTABLE_ATTRIBUTES,
    SELECTABLE_BRANDS,
    SELECTABLE_CATEGORIES,
    SELECTABLE_GROUPS,
    SELECTABLE_SUB_CATEGORIES,
    SELECTABLE_UNITS,
    STOCK,
    VARIATION_STORE,
} from "../../../../Config/ApiUrl-CP";
import {SELECTABLE_TAX} from "../../../../Config/ApiUrl-CPB";
import {capitalizeFirst} from "../../../../../common/Helper/Support/TextHelper";
import {cartesian, formDataAssigner, randomAlphanumeric} from "../../../../Helper/Helper";

export default {
    data() {
        return {
            dropzoneKey: 0,
            formDataBeingSubmitted: false,
            existingProductGalleryImgs: [],
            existingProductThumbnail: [],
        }
    },
    methods: {
        sortArraysAlphabetically(arr) {
            return arr.sort((a, b) => a.localeCompare(b));
        },
        submitData() {
            this.formDataBeingSubmitted = true; // this is to detect from the dropzone file remove event handler
            this.loading = true;
            this.message = '';
            this.errors = {};

            const backendImgPaths = this.productId ? this.photos.map(img => img.path) : [];
            const oldImagesFilteredOut = this.formData.product_gallery.filter(img => !backendImgPaths.includes(img.dataURL));

            // retaining these existing images for potential errors
            this.existingProductGalleryImgs = this.formData.product_gallery;
            this.existingProductThumbnail = this.formData.product_thumbnail;

            this.formData.product_gallery = [...oldImagesFilteredOut];
            this.formData.product_thumbnail = this.productThumbnailChanged ? this.formData.product_thumbnail : '';

            const newVariants = this.formData.variants.filter(variant => !Boolean('id' in variant));
            this.formData.variants = [...this.variantAttributesArray.map(vAA => {
                const vAAStringiFied = JSON.stringify(this.sortArraysAlphabetically(vAA));
                const correspondingFormDataVariant = this.formData.variants.find(variant => JSON.stringify(this.sortArraysAlphabetically(variant.variant_attributes)) === vAAStringiFied);
                if (correspondingFormDataVariant) return correspondingFormDataVariant;
            }).filter(v => v), ...newVariants];

            const variationsArray = this.attributeCombination.map(aC => aC.attribute_variations).flat();
            const allVariantAttributes = this.formData.variants.map(variant => variant.variant_attributes);
           
            const variations = allVariantAttributes.map(aVA => aVA.map(variationChip => ({ attribute_variation_id: variationsArray.find(variation => variation.name === variationChip).id })))
            this.formData.variations = [ ...variations ];
          

            let formData = new FormData;

            if (this.productId) formData.append('_method', 'patch')
            formData.append('product_thumbnail', this.formData.product_thumbnail);

            if (this.formData.product_gallery && this.formData.product_gallery.length)
                this.formData.product_gallery.forEach(galleryImage => formData.append(`product_gallery[]`, galleryImage))

            if (this.formData.variants) this.formData.variants.forEach((variant, variantIndex) => {
                if (!this.productCreateEditSelectedUrl) variant.variantThumbnailChanged = true;
                if (variant.variantThumbnailChanged) formData.append(`variants[${variantIndex}][variant_thumbnail]`, variant.variant_thumbnail ?? '');
                if (variant.variant_gallery) {
                    variant.variant_gallery.length ?
                        variant.variant_gallery.forEach((vgi, vgiIndex) =>
                            Object.keys(vgi).includes('upload') && formData.append(`variants[${variantIndex}][variant_gallery][${vgiIndex}]`, vgi))
                        : formData.append(`variants[${variantIndex}][variant_gallery]`, []);
                } else formData.append(`variants[${variantIndex}][variant_gallery]`, []);
            });

            const keysToIgnore = ['product_thumbnail', 'product_gallery', 'photos', 'thumbnail', 'created_by', 'created_at', 'updated_at', 'slug'];

            (() => {
                let dataObject = this.formData;
                Object.keys(dataObject).forEach((key) => {
                    if (keysToIgnore.includes(key)) return;
                    if (!dataObject[key]) return;
                    // checking for 1 and 2D objects and appending them to the FormData
                    if (dataObject[key] && !dataObject[key].length > 0 && Object.keys(dataObject[key]).length > 0)
                        Object.keys(dataObject[key]).forEach(childKey => formData.append(key + `[${childKey}]`, dataObject[key][childKey]))
                    // checking for arrays
                    else if (Array.isArray(dataObject[key])) {
                        const variantKeysToIgnore = ['variant_thumbnail', 'variant_gallery', 'photos'];
                        dataObject[key].forEach((el, index) => el && Object.keys(el).forEach(objectKeys => {
                            if (el[objectKeys] === null) el[objectKeys] = '';
                            const arrayElement = el[objectKeys];
                            if (variantKeysToIgnore.includes(objectKeys)) return;
                            // checking for 2D arrays and nested objects in it (for variations)
                            if (typeof arrayElement === 'object' && arrayElement !== null && !Array.isArray(arrayElement)) {
                                Object.keys(arrayElement).forEach(arrayElementInnerKey =>
                                    formData.append(key + `[${index}][${objectKeys}][${arrayElementInnerKey}]`, arrayElement[arrayElementInnerKey])
                                );
                                return;
                            }
                            formData.append(key + `[${index}][${objectKeys}]`, arrayElement);
                        }));
                    } else return formData.append(key, dataObject[key]);
                });
            })();

            if (this.selectedUrl) formData.append('_method', 'patch');
            this.formData.variationChips && formData.append('attribute_variation', Object.values(this.formData.variationChips).flat());

            return this.submitFromFixin(
                'post',
                this.productCreateEditSelectedUrl ? this.productCreateEditSelectedUrl : PRODUCTS,
                formData
            );
        },
        getSelectables(urls) {
            for (let [key, url] of Object.entries(urls)) {
                if (key === 'subcategories') url += `/${this.formData.category_id}`;
                axiosGet(url).then(response => {
                    this.selectableList[key] = response.data.data;
                    if (key === 'taxes' || key === 'attributes') {
                        this.selectableList[key].unshift({
                            id: '',
                            name: this.$t('select_field_name', {name: key === 'taxes' ? this.$t('tax') : this.$t('variant_attribute')}),
                            disabled: true
                        })
                    }
                })
            }
        },
        setSpecificationStatusIds() {
            axiosGet(this.getStatus('unit')).then(data => {
                const {data: {data: unitStatusData}} = data;
                this.unitOptions.params.status_id = unitStatusData.find(statusData => statusData.name === 'status_active').id;
            });

            axiosGet(this.getStatus('category')).then(data => {
                const {data: {data: categoryStatusData}} = data;
                this.categoryOptions.params.status_id = categoryStatusData.find(statusData => statusData.name === 'status_active').id;
            });

            axiosGet(this.getStatus('sub_category')).then(data => {
                const {data: {data: subcategoryStatusData}} = data;
                this.subCategoryOptions.params.status_id = subcategoryStatusData.find(statusData => statusData.name === 'status_active').id;
            });
        },
        async setStatusId() {
            const productStatusData = await axiosGet(this.getStatus('product'));
            const [{id: activeProductStatusId}] = productStatusData.data.data;
            this.activeProductStatusId = activeProductStatusId;

            this.formData = {...this.formData, status_id: activeProductStatusId};
        },
        changeAttribute(operation = 'add', item) {
            if (operation === 'delete') {
                let deleteIndex = this.attributeCombination.indexOf(item);
                this.attributeCombination.splice(deleteIndex, 1);
                this.tempObj[item.name] = [];
                this.generateCartesian(Object.values(this.tempObj).filter(item => item.length > 0));
                !this.attributeCombination.length ? this.formData.attribute_id = '' : this.formData.attribute_id = this.attributeCombination[this.attributeCombination.length - 1].id;
                return;
            }

            let value = this.selectableList.attributes.find(element => parseInt(element.id) === parseInt(this.formData.attribute_id))
            if (!this.attributeCombination.includes(value)) {
                this.attributeCombination.push(value);
                this.formData.variationChips[value?.name.toLowerCase()] = [];
            }

            this.initializeTooltip(500);
        },
        changeChips(variation, item) {
            this.isActive.attribute_definition = true;
            this.tempObj[item.name] = item.attribute_variations.filter(({id}) => variation.includes(id)).map(e => e.name);

            this.generateCartesian(Object.values(this.tempObj).filter(item => item.length > 0));
        },
        generateCartesian(selectedChips) {
            if (selectedChips.length > 0)
                this.tempCombination = cartesian(selectedChips);
            else {
                this.tempCombination = [];
                this.isActive.attribute_definition = false;
            }
            this.prepareVariant();
        },
        prepareVariant() {
            const existingVariants = [...this.formData.variants];
            this.formData.variants = [];
            const sortArraysAlphabetically = (arr) => arr.sort((a, b) => a.localeCompare(b));
            this.tempCombination.forEach((e, index) => {
                const variantName = this.formData.name ? this.formData.name + ('-') + e.join('-') : e.join('-');

                const newProductVariant = {
                    name: variantName,
                    upc: '',
                    upcData: {
                        showVariantUpcLoader: false,
                        variantUpcIsUnique: true,
                    },
                    variant_thumbnail: '',
                    description: '',
                    warranty_duration: this.tempItemDetails.warranty_duration,
                    warranty_duration_type: this.tempItemDetails.warranty_duration_type,
                    tax_id: this.formData.tax_id,
                    variant_gallery: [],
                    selling_price: '',
                    stock_reminder_quantity: this.formData.stock_reminder_quantity,
                    is_checked: true,
                    status_id: this.formData.status_id,
                    variant_attributes: e,
                    ...{
                        ...existingVariants.find(ev => {
                            return JSON.stringify(sortArraysAlphabetically(ev.variant_attributes)) == JSON.stringify(sortArraysAlphabetically(e))
                        }),
                        name: variantName
                    }
                }

                this.formData.variants.push(newProductVariant);
            });
        },
        generateProductUpc() {
            this.upcIsUnique = true;
            axiosGet('app/product/get_upc').then(({data: UPC}) => this.formData.upc = UPC);
        },
        getSku() {
            return this.formData.name ? this.formData.name + ('-') + randomAlphanumeric(8) : randomAlphanumeric(8);
        },
        openModal(modal, value = null, variantIndex) {

            value = {...value, variantIndex};
            this.variantRow = value;
            this.isActive[modal] = true;
        },
        attributeVariationStore(variation, item) {
            let variationData = {
                attribute_id: item.id,
                name: variation
            }
            this.axiosPost({
                url: VARIATION_STORE,
                data: variationData
            }).then(({data}) => {
                this.formData.variationChips[item.name.toLowerCase()].push(data.id);
                item.attribute_variations = data.list;
            })
        },
        async checkedVariants(item, index) {
            const productStatusData = await axiosGet(this.getStatus('product'));
            const [{id: activeProductStatusId}, {id: inactiveProductStatusId}] = productStatusData.data.data;

            this.formData.variants[index].is_checked = !this.formData.variants[index].is_checked
            const {is_checked} = this.formData.variants[index];
            this.formData.variants[index].status_id = is_checked ? activeProductStatusId : inactiveProductStatusId;
        },
        wantToLeave() {
            if (this.formChangeCount > 1) {
                window.onbeforeunload = (event) => {
                    return true
                }
            }
        },
        handleVariantDataUpdate(variantData) {
            this.formData.variants = this.formData.variants.map((v, i) => {
                return i === variantData.variantIndex ? {...v, ...variantData} : v
            });
        },
        resetFromData() {
            this.dropzoneKey += 1;
            this.formData = {
                name: '',
                product_thumbnail: '',
                product_gallery: [],
                product_type: 'single',
                unit_id: '',
                brand_id: '',
                group_id: '',
                category_id: '',
                sub_category_id: '',
                status_id: '',
                warranty_duration: '',
                duration_id: '2',
                description: '',
                tax_id: '',
                attribute_id: '',
                variationChips: {},
                variants: [],
                variantAttributes: [],
                stock_reminder_quantity: '',
                upc: '',
                note: '',
                selling_price: '',
                variations: [],
            };
            if (this.isActive.attribute_definition) this.isActive.attribute_definition = false;
            if (this.attributeCombination) this.attributeCombination = [];
            if (this.tempObj) this.tempObj = {};
        },
    },
    watch: {
        'formData.variants': function () {
           

            // saving the upc data to a different property on ProductUpcValidateMixin to check for duplicates
            this.variantUpcData.upcs = this.formData.variants.map((v, i) => ({variant_upc: v.upc, variant_index: i}));
        },
        formData: {
            handler: function () {
                this.formChangeCount++
                if (this.formChangeCount > 1) {
                    window.onbeforeunload = (event) => {
                        return true
                    }
                }
            },
            deep: true
        },
        'formData.category_id': {
            immediate: true,
            handler: function (newId, oldId) {
                this.componentKeys.subcategorySearchAndSelect += 1;
                if (this.formData.category_id) {
                    this.subCategoryOptions = {
                        ...this.subCategoryOptions,
                        url: urlGenerator(`app/selectable-sub-categories/${this.formData.category_id}`),
                    }
                }
            }
        },
        tempItemDetails: {
            handler: function () {
                if (this.formData.variants.length > 0) {
                    this.formData.variants.forEach(item => {
                        item.warranty_duration = this.tempItemDetails.warranty_duration;
                        item.warranty_duration_type = this.tempItemDetails.warranty_duration_type;
                        item.tax_id = this.tempItemDetails.tax_id;
                        item.low_stock_quantity = this.tempItemDetails.low_stock_quantity;
                    })
                }
            },
            deep: true
        },
        'formData.product_type': function (newType, oldType) {
            if (this.productId) return;
            if (oldType === 'single') {
                this.formData.upc = '';
                this.showUpcLoader = false;
                this.upcIsUnique = true;
                this.formData.selling_price = '';
                return;
            }

            this.formData.variationChips = {};
            this.formData.variants = [];
            this.formData.variantAttributes = [];
            this.formData.variations = [];
            this.formData.attribute_id = '';

            this.tempCombination = [];
            this.tempObj = {};

            this.attributeCombination = [];
            this.isActive.attribute_definition = false;

        }
    },
    mounted() {
        this.getSelectables({
                groups: SELECTABLE_GROUPS,
                categories: SELECTABLE_CATEGORIES,
                brands: SELECTABLE_BRANDS,
                units: SELECTABLE_UNITS,
                taxes: SELECTABLE_TAX,
                attributes: SELECTABLE_ATTRIBUTES,
            }
        );
        axiosGet('/app/selectable-durations')
            .then(({data: {data}}) => this.selectableList.warranty_duration_type = data);
        // auto generating the productUpc
        this.generateProductUpc();
        this.setStatusId();

    },
    beforeMount() {
        this.setSpecificationStatusIds();
    }
}
