<template>
    <div class="card card-with-shadow mb-4">
        <div class="filter-card-header border-bottom d-flex justify-content-between px-4 pt-3 mb-2">
            <p>{{ $t('product_filters') }}</p>
            <p class="text-primary cursor-pointer" @click="handleClearFilterClick">{{ $t('clear_filters') }}</p>
        </div>
        <div class="filter-card-body row px-4 py-4" :key="filterKey">
            <app-input
                class="col-6"
                type="search-and-select"
                :placeholder="$t('search_and_select', {name: $t('category').toLowerCase()})"
                :options="categoryOptions"
                v-model="filters.category_id"
            />

            <app-input
                class="col-6"
                type="search-and-select"
                :placeholder="$t('search_and_select', {name: $t('brand').toLowerCase()})"
                :options="brandOptions"
                v-model="filters.brand_id"
            />
        </div>
    </div>
</template>

<script>
import FormHelperMixins from "../../../../common/Mixin/Global/FormHelperMixins";
import {urlGenerator} from "../../../../common/Helper/AxiosHelper";
import {SELECTABLE_BRANDS, SELECTABLE_CATEGORIES} from "../../../Config/ApiUrl-CP";
import { mapActions } from "vuex";

export default {
    name: 'filter-display',
    mixins: [FormHelperMixins],
    props: ['searchTerm'],
    data() {
        return {
            brandOptions: {
                url: urlGenerator(SELECTABLE_BRANDS),
                query_name: "search_by_name",
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                prefetch: !Boolean(this.productId),
                modifire: ({id, name: value}) => ({id, value}),
            },
            categoryOptions: {
                url: urlGenerator(SELECTABLE_CATEGORIES),
                query_name: "search_by_name",
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                modifire: ({id, name: value}) => ({id, value}),
                prefetch: !Boolean(this.productId)
            },
            filterKey: 0,
            filters: {
                brand_id: '',
                category_id: ''
            }
        }
    },
    watch: {
        filters: {
            immediate: true,
            deep: true,
            handler: function (newFilters) {
                this.$emit('filter-change', (newFilters.brand_id || newFilters.category_id) ? newFilters : null)
                this.fetchProducts(newFilters)
            }
        }
    },
    methods: {
        ...mapActions(['setProducts']),
        handleClearFilterClick() {
            this.filters =  {
                brand_id: '',
                category_id: ''
            }
            this.filterKey += 1;
            this.$emit('clear-filter-click');
            this.fetchProducts(this.filters);
        },
        fetchProducts(filterObjs) {
            const productFilterEntries = Object.entries(filterObjs);
            if (this.searchTerm) productFilterEntries.push(['search', this.searchTerm]);
            const filterQueryString = productFilterEntries.filter(filterEntry => filterEntry[1]).reduce((str, filter, i) => str += `${filter[0]}=${filter[1]}${i + 1 !== productFilterEntries.length ? '&' : ''}`, '&')
            this.setProducts(filterQueryString);
        }
    }
}
</script>
