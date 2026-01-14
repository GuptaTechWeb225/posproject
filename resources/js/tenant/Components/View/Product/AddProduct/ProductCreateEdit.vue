<template>
    <div class="content-wrapper" v-click-outside="wantToLeave">
        <app-page-top-section
            :title="productId ? $t('edit_product') : $addLabel('product')"
            :hide-button="true"
            icon="menu"/>

        <app-overlay-loader v-if="editDataLoading"/>
        <template>
            <div class="card border-0 card-with-shadow" v-show="!editDataLoading">
                <div class="card-body">
                    <div class="mb-primary">
                        <div class="note note-warning p-4">
                            <div class="note-title d-flex">
                                <app-icon :name="'book-open'"/>
                                <h6 class="card-title pl-2"> {{ $t('how_to_manage_product') }}</h6>
                            </div>
                            <div class="ml-4">
                                <p class="m-1">{{ $t('product_instruction_1') }}</p>
                                <p class="m-1">{{ $t('product_instruction_2') }}</p>
                                <p class="m-1">{{ $t('product_instruction_3') }} <a :href="goToStockPage">{{ $t('stock') }}</a>
                                    {{ $t('product_instruction_4') }}</p>
                                <p class="m-1"> {{ $t('product_instruction_5') }} </p>
                            </div>
                        </div>
                    </div>

                    <div class="min-height-400" :class="{'loading-opacity':!isActive.renderComponent}">
                        <fieldset class="form-group mb-5">
                            <legend :class="`text-muted text-size-18 border-bottom pb-2 mb-3 ${$isRtl ? 'text-left' : ''}`">
                                {{ $t('product_information') }}
                            </legend>

                            <div class="form-group row align-items-center">
                                <label class="mb-lg-0 col-lg-3">
                                    {{ $t('name') }}*
                                </label>
                                <div class="col-lg-7">
                                    <app-input
                                        v-model="formData.name"
                                        @input="prepareVariant()"
                                        :required="true"
                                        :error-message="$errorMessage(errors, 'name')"
                                        :placeholder="$placeholder('name')"
                                    />
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="mb-lg-0 col-lg-3">
                                    {{ $t('description') }}
                                </label>
                                <div class="col-lg-7">
                                    <app-input
                                        type="textarea"
                                        :text-area-rows="5"
                                        v-model="formData.description"
                                        :error-message="$errorMessage(errors, 'description')"
                                        :placeholder="$placeholder('description')"
                                    />
                                </div>
                            </div>

                            <div class="form-group row align-items-center">
                                <label class="mb-lg-0 col-lg-3">
                                    {{ $t('image') }}
                                </label>
                                <div class="col-lg-7">
                                    <app-input
                                        type="custom-file-upload"
                                        @change="handleProductThumbnailChange"
                                        v-model="formData.product_thumbnail"
                                        :generate-file-url="false"
                                        :label="$t('upload_file')"
                                        :required="false"
                                        :error-message="$errorMessage(errors, 'product_thumbnail')"
                                    />
                                    <div class="margin-top-10">
                                        <small class="text-muted font-italic text-size-12">
                                            {{ $t('recommended_type_jpg') }}
                                            {{ $t('max_filesize_5mb') }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row align-items-center">
                                <label class="mb-lg-0 col-lg-3">
                                    {{ $t('product_gallery') }}
                                </label>
                                <div class="col-lg-7">
                                    <app-input
                                        type="dropzone"
                                        class="dropzone-product-gallery"
                                        :key="dropzoneKey"
                                        v-model="formData.product_gallery"
                                        :error-message="$errorMessage(errors, 'product_gallery')"
                                        @file-removed="handleDropzoneFileRemove"
                                    />

                                    <div class="margin-top-10">
                                        <small class="text-muted font-italic text-size-12">
                                            {{ $t('recommended_type_jpg') }}
                                            {{ $t('max_filesize_5mb') }}
                                        </small>
                                    </div>

                                    <dropzone-img-remove-alert-message
                                        v-if="Boolean(productCreateEditSelectedUrl) && Boolean(formData.product_gallery.length)"/>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="form-group mb-5">

                            <legend :class="`text-muted text-size-18 border-bottom pb-2 mb-3 ${ $isRtl ? 'text-left' : '' }`">
                                {{ $t('specifications') }}
                            </legend>

                            <div class="form-group row align-items-center">
                                <label class="mb-lg-0 col-lg-3">
                                    {{ $t('group') }}
                                </label>
                                <div class="col-lg-7">
                                    <div class="row no-gutters">
                                        <div class="col-10 col-md-11">
                                            <app-input
                                                class="margin-right-8"
                                                type="search-and-select"
                                                :key="ssKey"
                                                :placeholder="$t('search_and_select', {name: $t('group').toLowerCase()})"
                                                :options="groupOptions"
                                                v-model="formData.group_id"
                                                :error-message="$errorMessage(errors, 'group')"
                                            />
                                        </div>
                                        <div class="col-2 col-md-1">
                                            <button type="button"
                                                    data-toggle="tooltip"
                                                    :title="$addNew('group')"
                                                    data-placement="top"
                                                    class="btn default-base-color btn-block h-100 p-1"
                                                    @click="openModal('group')">
                                                <app-icon class="size-19 primary-text-color" name="plus"/>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group row align-items-center">
                                <label class="mb-lg-0 col-lg-3">
                                    {{ $t('category') }}
                                </label>
                                <div class="col-lg-7">
                                    <div class="row no-gutters">
                                        <div class="col-10 col-md-11">
                                            <app-input
                                                class="margin-right-8"
                                                type="search-and-select"
                                                :placeholder="$t('search_and_select', {name: $t('category').toLowerCase()})"
                                                :options="categoryOptions"
                                                :key="ssKey"
                                                @input="handleCategoryChange"
                                                v-model="formData.category_id"
                                                :error-message="$errorMessage(errors, 'category_id')"
                                            />
                                        </div>
                                        <div class="col-2 col-md-1">
                                            <button type="button"
                                                    data-toggle="tooltip"
                                                    :title="$addNew('category')"
                                                    data-placement="top"
                                                    class="btn default-base-color btn-block h-100 p-1"
                                                    @click="openModal('category')">
                                                <app-icon class="size-19 primary-text-color" name="plus"/>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row align-items-center" v-if="formData.category_id">
                                <label class="mb-lg-0 col-lg-3">
                                    {{ $t('sub_category') }}
                                </label>
                                <div class="col-lg-7">
                                    <div class="row no-gutters">
                                        <div class="col-10 col-md-11">
                                            <app-input
                                                class="margin-right-8"
                                                type="search-and-select"
                                                :placeholder="$t('search_and_select', {name: $t('sub_category').toLowerCase()})"
                                                :options="subCategoryOptions"
                                                :key="componentKeys.subcategorySearchAndSelect"
                                                v-model="formData.sub_category_id"
                                                :error-message="$errorMessage(errors, 'sub_category')"
                                            />
                                        </div>
                                        <div class="col-2 col-md-1">
                                            <button type="button"
                                                    data-toggle="tooltip"
                                                    :title="$addNew('sub_category')"
                                                    data-placement="top"
                                                    class="btn default-base-color btn-block h-100 p-1"
                                                    @click="openModal('subCategory')">
                                                <app-icon class="size-19 primary-text-color" name="plus"/>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row align-items-center">
                                <label class="mb-lg-0 col-lg-3">
                                    {{ $t('brand') }}
                                </label>
                                <div class="col-lg-7">
                                    <div class="row no-gutters">
                                        <div class="col-10 col-md-11">
                                            <app-input
                                                class="margin-right-8"
                                                type="search-and-select"
                                                :key="ssKey"
                                                :placeholder="$t('search_and_select', {name: $t('brand').toLowerCase()})"
                                                :options="brandOptions"
                                                v-model="formData.brand_id"
                                                :error-message="$errorMessage(errors, 'brand')"
                                            />
                                        </div>
                                        <div class="col-2 col-md-1">
                                            <button type="button"
                                                    data-toggle="tooltip"
                                                    :title="$addNew('brand')"
                                                    data-placement="top"
                                                    class="btn default-base-color btn-block h-100 p-1"
                                                    @click="openModal('brand')">
                                                <app-icon class="size-19 primary-text-color" name="plus"/>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row align-items-center">
                                <label class="mb-lg-0 col-lg-3">
                                    {{ $t('unit') }}
                                </label>
                                <div class="col-lg-7">
                                    <div class="row no-gutters">
                                        <div class="col-10 col-md-11">
                                            <app-input
                                                class="margin-right-8"
                                                type="search-and-select"
                                                :key="ssKey"
                                                :placeholder="$t('search_and_select', {name: $t('unit').toLowerCase()})"
                                                :options="unitOptions"
                                                v-model="formData.unit_id"
                                                :error-message="$errorMessage(errors, 'unit')"
                                            />
                                        </div>
                                        <div class="col-2 col-md-1">
                                            <button type="button"
                                                    data-toggle="tooltip"
                                                    :title="$addNew('unit')"
                                                    data-placement="top"
                                                    class="btn default-base-color btn-block h-100 p-1"
                                                    @click="openModal('unit')">
                                                <app-icon class="size-19 primary-text-color" name="plus"/>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div :class="`form-group row align-items-center ${this.productId && 'pe-none opacity-50'}`">
                                <label class="mb-lg-0 col-lg-3">
                                    {{ $t('product_type') }}*
                                </label>
                                <div class="col-lg-7">
                                    <app-input
                                        type="radio"
                                        @input="parseInt($event) === 1 ? initializeTooltip(1000) : false"
                                        :disabled="Boolean(productId)"
                                        :required="true"
                                        v-model="formData.product_type"
                                        :list="selectableList.productTypes"
                                        :error-message="$errorMessage(errors, 'product_type')"
                                    />
                                </div>
                            </div>
                        </fieldset>

                        <app-overlay-loader v-if="!isActive.renderComponent"/>

                        <template>
                            <template v-if="formData.product_type === 'single'">
                                <fieldset class="form-group mb-5">
                                    <legend :class="`text-muted text-size-18 border-bottom pb-2 mb-3 ${ $isRtl ? 'text-left' : '' }`">
                                        {{ $t('product_pricing') }}
                                    </legend>

                                    <div class="form-group row align-items-center">
                                        <label class="mb-lg-0 col-lg-3">
                                            {{ $t('selling_price') }}*
                                        </label>
                                        <div class="col-lg-7">
                                            <app-input
                                                :required="true"
                                                :disabled="false"
                                                type="number"
                                                v-model="formData.selling_price"
                                                :placeholder="$placeholder('selling_price')"
                                                :label="$t('selling_price')"
                                                :error-message="$errorMessage(errors, 'selling_price')"
                                            />
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="form-group mb-5">
                                    <legend :class="`text-muted text-size-18 border-bottom pb-2 mb-3 ${ $isRtl ? 'text-left' : '' }`">
                                        {{ $t('item_details') }}
                                    </legend>

                                    <div class="form-group row align-items-center">
                                        <label class="mb-lg-0 col-lg-3">
                                            {{ $t('upc') }}
                                        </label>
                                        <div class="col-lg-7">
                                            <div class="row no-gutters">
                                                <div class="col-10 col-md-11">
                                                    <app-overlay-loader v-if="showUpcLoader"/>
                                                    <app-input
                                                        :class="`margin-right-8 ${showUpcLoader && 'temp-disable'}`"
                                                        :placeholder="$placeholder('upc')"
                                                        :required="true"
                                                        @keyup="handleSingleProductUpcChange"
                                                        v-model="formData.upc"
                                                        :error-message="$errorMessage(errors, 'upc')"
                                                    />
                                                    <small class="text-danger text-xs" v-if="!upcIsUnique">{{
                                                            $t('the_upc_you_entered_is_not_unique')
                                                        }}</small>
                                                </div>
                                                <div class="col-2 col-md-1">
                                                    <button type="button"
                                                            data-toggle="tooltip"
                                                            :title="$t('get_a_new_upc')"
                                                            data-placement="top"
                                                            :key="'refresh-cw'"
                                                            :class="`btn default-base-color btn-block h-100 p-1 ${showUpcLoader && 'temp-disable'}`"
                                                            @click="generateProductUpc">
                                                        <app-icon class="size-19 primary-text-color" name="refresh-cw"/>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row align-items-center">
                                        <label class="mb-lg-0 col-lg-3">
                                            {{ $t('warranty_duration') }}
                                        </label>
                                        <div class="col-lg-7">
                                            <div class="row no-gutters">
                                                <div class="col-9 col-md-10">
                                                    <app-input
                                                        class="margin-right-8"
                                                        type="number"
                                                        :placeholder="$placeholder('warranty_duration')"
                                                        v-model="formData.warranty_duration"
                                                        :error-message="$errorMessage(errors, 'warranty_duration')"
                                                    />
                                                </div>
                                                <div class="col-3 col-md-2 pl-0" v-if="selectableList.warranty_duration_type.length">
                                                    <app-input
                                                        type="select"
                                                        :list="selectableList.warranty_duration_type"
                                                        v-model="formData.duration_id"
                                                        list-value-field="type"
                                                        :error-message="$errorMessage(errors, 'duration_id')"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- single product tax -->
                                    <div class="form-group row align-items-center">
                                        <label class="mb-lg-0 col-lg-3">
                                            {{ $t('tax') }}
                                        </label>
                                        <div class="col-lg-7">
                                            <app-input
                                                type="search-and-select"
                                                :key="ssKey"
                                                :placeholder="$placeholder('tax')"
                                                :options="taxOptions"
                                                v-model="formData.tax_id"
                                                :error-message="$errorMessage(errors, 'tax_id')"
                                            />
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="mb-lg-0 col-lg-3">
                                            {{ $t('note') }}
                                        </label>
                                        <div class="col-lg-7">
                                            <app-input
                                                type="textarea"
                                                :text-area-rows="5"
                                                :placeholder="$placeholder('note')"
                                                v-model="formData.note"
                                                :error-message="$errorMessage(errors, 'note')"
                                            />
                                        </div>
                                    </div>

                                </fieldset>
                            </template>

                            <template v-else>
                                <fieldset class="form-group mb-5">
                                    <legend :class="`text-muted text-size-18 border-bottom pb-2 mb-3 ${ $isRtl ? 'text-left' : '' }`">
                                        {{ $t('item_details') }}
                                    </legend>

                                    <div class="form-group row align-items-center">
                                        <label class="mb-lg-0 col-lg-3">
                                            {{ $t('warranty_duration') }}
                                        </label>
                                        <div class="col-lg-7">
                                            <div class="row no-gutters">
                                                <div class="col-9 col-md-10">
                                                    <app-input
                                                        class="margin-right-8"
                                                        type="number"
                                                        :placeholder="$placeholder('warranty_duration')"
                                                        v-model="formData.warranty_duration"
                                                        :error-message="$errorMessage(errors, 'warranty_duration')"
                                                    />
                                                </div>
                                                <div class="col-3 col-md-2 pl-0">
                                                    <app-input
                                                        type="select"
                                                        :list="selectableList.warranty_duration_type"
                                                        v-model="formData.duration_id"
                                                        list-value-field="type"
                                                        :error-message="$errorMessage(errors, 'duration_id')"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- variant product tax -->
                                    <div v-if="!productId" class="form-group row align-items-center">
                                        <label class="mb-lg-0 col-lg-3">
                                            {{ $t('tax') }}
                                        </label>
                                        <div class="col-lg-7">
                                            <app-input
                                                type="search-and-select"
                                                :key="ssKey"
                                                :options="taxOptions"
                                                :placeholder="$placeholder('tax')"
                                                v-model="formData.tax_id"
                                                :error-message="$errorMessage(errors, 'tax')"
                                            />
                                        </div>
                                    </div>
                                </fieldset>

                                <fieldset class="form-group mb-5">
                                    <legend :class="`text-muted text-size-18 border-bottom pb-2 mb-3 ${ $isRtl ? 'text-left' : '' }`">
                                        {{ $t('attribute_definition') }}
                                    </legend>

                                    <div class="form-group row align-items-center" v-if="Boolean(!productId)" :key="attributeListInputKey">
                                        <label class="mb-lg-0 col-lg-3">
                                            {{ $t('variant_attribute') }}
                                        </label>
                                        <div class="col-lg-7">
                                            <div class="row no-gutters">
                                                <div class="col-10 col-md-11">
                                                    <app-input
                                                        class="margin-right-8"
                                                        type="select"
                                                        :list="!productId ? selectableList.attributes : filteredVariantAttributes"
                                                        list-value-field="name"
                                                        @change="changeAttribute"
                                                        :required="true"
                                                        v-model="formData.attribute_id"
                                                        :error-message="$errorMessage(errors, 'variant_attribute')"
                                                    />
                                                </div>
                                                <div class="col-2 col-md-1">
                                                    <button type="button"
                                                            data-toggle="tooltip"
                                                            :title="$addNew('variant_attribute')"
                                                            data-placement="top"
                                                            :key="'plus'"
                                                            class="btn default-base-color btn-block h-100 p-1"
                                                            @click="openModal('attribute')">
                                                        <app-icon class="size-19 primary-text-color" name="plus"/>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div v-for="item in attributeCombination" class="form-group row">
                                        <label class="mb-lg-0 col-lg-3">
                                            {{ capitalizeFirst(item.name) }}
                                        </label>
                                        <div class="col-lg-7">
                                            <div class="row no-gutters">
                                                <div class="col-10 col-md-11">
                                                    <app-input
                                                        class="margin-right-8"
                                                        type="multi-create"
                                                        @storeData="attributeVariationStore($event , item)"
                                                        :list="item.attribute_variations"
                                                        @input="changeChips($event, item)"
                                                        list-value-field="name"
                                                        :required="true"
                                                        :unremovables="productId ? Object.values(existingVariationChips).flat() : []"
                                                        v-model="formData.variationChips[item.name.toLowerCase()]"
                                                    />
                                                </div>
                                                <div class="col-2 col-md-1">
                                                    <button type="button"
                                                            v-if="!Boolean(productId)"
                                                            data-toggle="tooltip"
                                                            :title="$t('remove_variant_attribute')"
                                                            data-placement="top"
                                                            class="btn default-base-color btn-block p-1 py-2"
                                                            @click="changeAttribute('delete', item)">
                                                        <app-icon class="size-19 primary-text-color" name="trash-2"/>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </fieldset>
                                <fieldset class="form-group mb-5" v-if="isActive.attribute_definition">
                                    <legend :class="`text-muted text-size-18 border-bottom pb-2 mb-3 ${ $isRtl ? 'text-left' : '' }`">
                                        {{ $t('item_details_of_variant') }}
                                    </legend>

                                    <div class="form-group row d-none d-lg-flex">
                                        <div class="col-lg-10">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <label class="mb-lg-0">
                                                        {{ $t('variants') }}
                                                    </label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label class="mb-lg-0">
                                                        {{ $t('selling_price') }}*
                                                    </label>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label class="mb-lg-0">
                                                        {{ $t('upc') }}*
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 text-right">
                                            {{ $t('actions') }}
                                        </div>
                                    </div>
                                    <div
                                        :class="`form-group row mb-5 mb-lg-2
                                        ${item.upcData.variantUpcIsUnique && formData.variants.some(variant => !variant.upcData.variantUpcIsUnique) ? 'temp-disable' : ''}`"
                                        v-for="(item, index) in formData.variants">
                                        <div class="col-lg-10">
                                            <div class="row">
                                                <div class="col-lg-4 mb-2 mb-lg-0">
                                                    <app-input
                                                        :disabled="!item.is_checked"
                                                        v-model="item.name"
                                                    />
                                                </div>
                                                <div class="col-lg-4 mb-2 mb-lg-0">
                                                    <app-input
                                                        :disabled="!item.is_checked"
                                                        type="number"
                                                        :required="true"
                                                        v-model="item.selling_price"
                                                        :placeholder="$placeholder('selling_price')"
                                                        :label="$t('selling_price')"
                                                        :error-message="$errorMessage(errors, 'selling_price')"
                                                    />
                                                </div>
                                                <div class="col-lg-4 mb-2 mb-lg-0">
                                                    <div class="row no-gutters">
                                                        <div class="col-11 col-lg-10">
                                                            <app-overlay-loader
                                                                v-if="item.upcData.showVariantUpcLoader"/>
                                                            <app-input
                                                                :disabled="!item.is_checked"
                                                                :class="`margin-right-8 ${item.upcData.showVariantUpcLoader && 'temp-disable'}`"
                                                                v-model="item.upc"
                                                                @keyup="handleVariantProductUpcChange(item, index)"
                                                                :required="true"
                                                                :placeholder="$placeholder('upc')"
                                                            />
                                                            <small class="text-danger text-xs"
                                                                   v-if="!item.upcData.variantUpcIsUnique">{{
                                                                    $t('the_upc_you_entered_is_not_unique')
                                                                }}</small>
                                                        </div>
                                                        <div class="col-1 col-lg-2">
                                                            <button type="button"
                                                                    :disabled="!item.is_checked"
                                                                    data-toggle="tooltip"
                                                                    :title="$t('get_a_new_upc')"
                                                                    data-placement="top"
                                                                    :class="`btn default-base-color btn-block h-100 p-1 ${showUpcLoader && 'temp-disable'}`"
                                                                    @click="handleGenerateUpcBtnClick(index)">
                                                                <app-icon class="size-19 primary-text-color"
                                                                          name="refresh-cw"/>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="text-right h-100 mt-3 mt-lg-0">
                                                <button type="button"
                                                        class="btn default-base-color h-100 p-1 padding-x-10"
                                                        data-toggle="tooltip"
                                                        :title="$t('change_item_status')"
                                                        :key="item.is_checked ? 'check-square' : 'square'"
                                                        @click="checkedVariants(item,index)">
                                                    <app-icon class="size-19 primary-text-color"
                                                              :name="item.is_checked ? 'check-square' : 'square'"/>

                                                </button>
                                                <button type="button"
                                                        :disabled="!item.is_checked"
                                                        data-toggle="tooltip"
                                                        :title="$addNew('variant_details')"
                                                        data-placement="top"
                                                        class="btn default-base-color h-100 p-1 padding-x-10 margin-left-5"
                                                        @click="openModal('variant', value=item, index)">
                                                    <app-icon class="size-19 primary-text-color" name="plus"/>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                                <div class="form-group row">
                                    <label class="mb-lg-0 col-lg-3">
                                        {{ $t('note') }}
                                    </label>
                                    <div class="col-lg-7">
                                        <app-input
                                            type="textarea"
                                            :text-area-rows="5"
                                            :placeholder="$placeholder('note')"
                                            v-model="formData.note"
                                            :error-message="$errorMessage(errors, 'note')"
                                        />
                                    </div>
                                </div>

                            </template>

                        </template>

                    </div>

                    <div class="row">
                        <div :class="`col-12 mt-5 ${$isRtl ? 'text-left' : ''}`">
                            <app-cancel-button btn-class="btn-secondary" @click="handleCancelBtnClick"/>
                            <app-submit-button
                                :btn-class="`ml-2 ${showUpcLoader || !upcIsUnique || formData.variants.some(variant => !variant.upcData.variantUpcIsUnique) ? 'temp-disable' : ''}`"
                                :loading="loading" @click="submitData"/>
                        </div>
                    </div>
                </div>
            </div>

            <app-group-modal
                v-if="isActive.group"
                v-model="isActive.group"
                :selected-url="selectedUrl"
                @close="isActive.group = false"
                @update-values="getSelectables({groups: SELECTABLE_GROUPS})"
            />

            <app-category-modal
                v-if="isActive.category"
                v-model="isActive.category"
                :selected-url="selectedUrl"
                @close="isActive.category = false"
                @update-values="getSelectables({categories: SELECTABLE_CATEGORIES})"
            />

            <app-sub-category-modal
                v-if="isActive.subCategory"
                v-model="isActive.subCategory"
                :selected-url="selectedUrl"
                :category-id="formData.category_id"
                @close="isActive.subCategory = false"
                @input="handleSubcategoryInput"
                @update-values="getSelectables({subcategories: SELECTABLE_SUB_CATEGORIES})"
            />

            <app-brand-modal
                v-if="isActive.brand"
                v-model="isActive.brand"
                :selected-url="selectedUrl"
                @close="isActive.brand = false"
                @update-values="getSelectables({brands: SELECTABLE_BRANDS})"
            />

            <app-unit-create-edit-modal
                v-if="isActive.unit"
                v-model="isActive.unit"
                :selected-url="selectedUrl"
                @close="isActive.unit = false"
                @update-values="getSelectables({units: SELECTABLE_UNITS})"
            />


            <app-attribute-modal
                v-if="isActive.attribute"
                v-model="isActive.attribute"
                :selected-url="selectedUrl"
                @close="isActive.attribute = false"
                @update-values="handleAttributeValueUpdate"
            />

            <app-variant-create-edit-modal
                v-if="isActive.variant"
                v-model="isActive.variant"
                :selected-url="selectedUrl"
                :product-id="productId + ''"
                :variant="variantRow"
                :variantGalleryBackup="variantGalleryBackup"
                @close="isActive.variant = false"
                @variant-data-update="handleVariantDataUpdate"
                @variant-thumbnail-change="handleVariantThumbnailChange"
            />
        </template>
    </div>
</template>

<script>
import Note from "../../../../../common/Components/Helper/Note";
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import ProductCreateMixin from "./ProductCreateMixin";
import {axiosDelete, axiosGet, urlGenerator} from "../../../../../common/Helper/AxiosHelper";
import StatusQueryMixin from "../../../../../common/Mixin/Global/StatusQueryMixin";
import ProductEditMixin from "./ProductEditMixin";
import DeleteMixin from "../../../../../common/Mixin/Global/DeleteMixin";
import ProductUpcValidateMixin from "./ProductUpcValidateMixin";
import {
  PRODUCT_IMAGE_DELETE, PRODUCT_LIST, SELECTABLE_BRANDS, SELECTABLE_CATEGORIES,
  SELECTABLE_GROUPS, STOCK,
  SELECTABLE_TAX, SELECTABLE_UNITS,
  SELECTABLE_VARIANT_ATTIRBUTES, INVENTORY
} from "../../../../Config/ApiUrl-CP";
import DropzoneImgRemoveAlertMessage from "./DropzoneImgRemoveAlertMessage";


export default {
    name: "ProductCreateEdit",
    components: {
        Note,
        'dropzone-img-remove-alert-message': DropzoneImgRemoveAlertMessage
    },
    mixins: [FormHelperMixins, ProductCreateMixin, StatusQueryMixin, ProductEditMixin, ProductUpcValidateMixin, DeleteMixin],
    props: ['productId'],
    data() {
        return {
            attributeListInputKey: 0,
            dropZoneBoot: false,
            // these will be updated from the getSelectables method to rerender the components
            specificationModalKeys: {
                groups: 0,
                brands: 0,
                categories: 0,
                attributes: 0,
                units: 0,
            },
            isLoaded: false,
            existingVariationChips: {},
            confirmationModalActive: false,
            renderCategorySelectComponent: true,

            // search and select field options
            taxOptions: {
                url: urlGenerator(SELECTABLE_TAX),
                query_name: "search_by_name",
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                modifire: ({id, name: value}) => ({id, value}),
                prefetch: !Boolean(this.productId)
            },
            variantAttributeOptions: {
                url: urlGenerator(SELECTABLE_VARIANT_ATTIRBUTES),
                query_name: "search_by_name",
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                modifire: ({id, name: value}) => ({id, value}),
                prefetch: !Boolean(this.productId)
            },
            // specifications input inputs
            groupOptions: {
                url: urlGenerator(SELECTABLE_GROUPS),
                query_name: "search_by_name",
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                modifire: ({id, name: value}) => ({id, value}),
                prefetch: !Boolean(this.productId),
            },
            categoryOptions: {
                url: urlGenerator(SELECTABLE_CATEGORIES),
                query_name: "search_by_name",
                params: {
                    status_id: '',
                },
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                modifire: ({id, name: value}) => ({id, value}),
                prefetch: !Boolean(this.productId)
            },
            subCategoryOptions: {
                query_name: "search_by_name",
                params: {
                    status_id: '',
                },
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                modifire: ({id, name: value}) => ({id, value}),
                prefetch: !Boolean(this.productId)
            },
            brandOptions: {
                url: urlGenerator(SELECTABLE_BRANDS),
                query_name: "search_by_name",
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                prefetch: !Boolean(this.productId),
                modifire: ({id, name: value}) => ({id, value}),
            },
            unitOptions: {
                url: urlGenerator(SELECTABLE_UNITS),
                query_name: "search_by_name",
                params: {
                    status_id: '',
                },
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                modifire: ({id, name: value}) => ({id, value}),
                prefetch: !Boolean(this.productId)
            },
            componentKeys: {
                subcategorySearchAndSelect: 0,
            },

        }
    },
    watch: {
        ssKey() {
            this.componentKeys.subcategorySearchAndSelect++;
        }
    },
    methods: {
        handleAttributeValueUpdate() {
            this.getSelectables({attributes: this.SELECTABLE_ATTRIBUTES});
            this.attributeListInputKey++;
        },
        handleVariantThumbnailChange(variantIndex) {
            this.formData.variants = this.formData.variants.map((v, i) => i === variantIndex ? {
                ...v,
                variantThumbnailChanged: true
            } : v);
        },
        handleProductThumbnailChange() {
            this.productThumbnailChanged = true;
        },
        handleDropzoneFileRemove(file) {
            if (this.formDataBeingSubmitted) return;
            if (!this.productId) return;
            const fileToRemove = this.formData.photos.find(photo => photo.path === file.dataURL);
            if (!fileToRemove) return;
            const {id: fileToRemoveId} = fileToRemove;
            axiosDelete(PRODUCT_IMAGE_DELETE + fileToRemoveId)
                .then(res => this.$toastr.s('', res.data.message))
                .catch(err => this.$toastr.e('', err.message))
        },
        handleCancelBtnClick() {
            this.$emit('cancel');
            window.location.replace(urlGenerator(PRODUCT_LIST));
        },
        handleSubcategoryInput() {
            this.componentKeys.subcategorySearchAndSelect += 1;
        },
        handleGenerateUpcBtnClick(variantProductIndex) {
            axiosGet("app/product/get_upc")
                .then(({data: upc}) => {
                    this.variantUpcData.upcs[variantProductIndex] = upc;
                    this.formData.variants = this.formData.variants.map((v, i) => i !== variantProductIndex ? v : {
                        ...v,
                        upc
                    });
                    this.handleVariantUpcBlur();
                    if (this.formData.variants[variantProductIndex].upcData) this.formData.variants[variantProductIndex].upcData.variantUpcIsUnique = true;
                });
        },
        handleCategoryChange() {
            this.sub_category_id = '';
        },
    },
    mounted() {
        setTimeout(() => this.dropZoneBoot = true)
        // setting the category_id to be the on load,
        // this will be changed later on a new subcategory add to force reload the subcategory search and select input (to get the newly added sub category)
        this.componentKeys.subcategorySearchAndSelect = +this.formData.category_id;
    },
    computed: {
        goToStockPage(){
          return urlGenerator(INVENTORY);
        },
        filteredVariantAttributes() {
            return this.selectableList.attributes.filter(attr => {
                const activeVariantAttrs = Object.keys(this.formData.variationChips);
                return activeVariantAttrs.includes(attr.name.toLowerCase());
            });
        },
    },
}
</script>

<style scoped>
.pe-none {
    pointer-events: none;
}

.temp-disable {
    opacity: 0.5;
    pointer-events: none;
}
</style>

