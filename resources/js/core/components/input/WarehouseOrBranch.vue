<template>
    <div class="single-filter search-filter-dropdown" @click="handleClick">
        <div class="dropdown dropdown-with-animation position-relative" :class="{'disabled':data.disabled}" >
            <div :id="inputFieldId"
                 data-toggle="dropdown"
                 aria-haspopup="true"
                 aria-expanded="false">
                <input type="text"
                       class="form-control cursor-pointer"
                       :placeholder="data.placeholder"
                       :disabled="data.disabled"
                       @click="startNavigation"
                       :value="showValue">
            </div>
            <button v-if="clearAble" type="button" @click="clearValue"
                    class="btn btn-link rounded-pill position-absolute p-0" style="top: 8px; right: 10px;">
                <app-icon name="x"/>
            </button>

            <div class="dropdown-menu py-0 my-1 w-100" :class="data.listClass" :aria-labelledby="inputFieldId">
                <div class="form-group form-group-with-search">
                    <span class="form-control-feedback">
                        <app-icon name="search"/>
                    </span>
                    <input type="text"
                           ref="searchInput"
                           :class="'form-control '+data.listItemInputClass"
                           :placeholder="$t('search')"
                           v-model="searchValue"
                           @input="search($event)"
                           @keydown.down=""
                           @keydown.up=""
                           @keydown.enter.prevent=""
                           :autofocus="startNavigation">
                </div>
                <div class="dropdown-divider my-0"/>
                <div class="dropdown-search-result-wrapper custom-scrollbar" ref="optionList" @scroll="loadMore">
                    <template v-for="(tag, index) in tags">
                        <div :key="`tags-item-${index}`">
                            <div class="d-flex align-items-center" style="gap: 15px; padding: 15px 30px;">
                                <div class="text-uppercase" style="letter-spacing: 5px;">{{ tag }}</div>
                                <div class="bg-secondary flex-fill" style="height: 3px;"></div>
                            </div>
                            <a class="dropdown-item"
                               href="#"
                               v-for="(item,index) in computedList.filter(i => i.type === tag)"
                               :class="{
                                   'active': index == activeIndex,
                                   [data.listItemClass]: !isUndefined(data.listItemClass),
                                   'selected': data.options.multiple ? (value||[]).includes(item.id) : item.id === value,
                                   'disabled':item.disabled,
                               }"
                               @click.prevent="changeSelectedValue(item)"
                               :key="`${inputFieldId}-${index}`">

                                <template v-if="!showExtendedDropdownDetails">
                                    {{ item[data.listValueField] }}
                                    <span class="check-sign float-right">
                                        <app-icon name="check" class="menu-icon"/>
                                    </span>
                                </template>

                                <template v-else>
                                    <div class="row">
                                        <div class="product-profile col-5 d-flex align-items-center">
                                            <div class="mr-2">
                                                <app-avatar
                                                    :key="item.id"
                                                    :border="true"
                                                    :img="item.thumbnail === null ? '' : item.thumbnail.full_url"
                                                    avatar-class="avatars-w-60"
                                                    :status="`success`"
                                                    :title="item.name"
                                                />
                                            </div>
                                            <div>
                                                <p style="max-width: 5rem;">{{ item.name }}</p>
                                                <small class="d-block text-muted">{{ item.upc }}</small>
                                            </div>
                                        </div>

                                        <div class="stock-amount col-2">
                                            <p>
                                                <span class="text-muted">Stock:</span> <br />
                                                <span>{{ item.stock === null ? 0 : item.stock.available_qty }}</span>
                                            </p>
                                        </div>

                                        <div class="discount-info col-5" v-if="item.active_discount_product !== null">
                                            <p class="text-muted">This product is already listed in: </p>
                                            <p class="d-inline-block text-capitalize bg-warning text-white px-3 py-1" style="border-radius: 8rem;">{{ item.active_discount_product.discount.name }}</p>
                                        </div>
                                    </div>
                                </template>
                            </a>
                        </div>
                    </template>
                    <div style="min-height: 30px;">
                        <div v-if="isLoading" class="dropdown-item position-relative">
                            <app-pre-loader v-if="data.options.loader === 'app-pre-loader'"/>
                            <app-overlay-loader v-else/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {InputMixin} from './mixin/InputMixin.js';
import {NavigationMixin} from "./mixin/NavigationMixin";
import CoreLibrary from "../../helpers/CoreLibrary";

export default {
    mixins: [InputMixin, NavigationMixin],
    extends: CoreLibrary,
    props: ['showExtendedDropdownDetails', 'inputclearable'],
    data() {
        return {
            selectedValue: '',
            searchValue: '',
            list: [],
            selectedList: this.data.options.multiple ? [] : {},
            loadedIndex: this.data.loadedPerTime,
            moreTagLoading: false,
            isLoading: false,
            noMoreData: false,
            pageNumber: 1,
            per_page: this.data.options.per_page || 10,
            query_name: this.data.options.query_name || 'search'
        }
    },
    mounted() {
        $('.dropdown').on('hide.bs.dropdown', () => {
            this.searchValue = ''
            this.loadedIndex = this.data.loadedPerTime;
        });
        this.addToList(this.searchValue, this.data.options.multiple ? this.value.length : this.value);
    },
    created() {
        const {per_page, query_name} = this.data.options;
        if (per_page) this.per_page = per_page;
        if (query_name) this.query_name = query_name;
        if (this.data.options.multiple) this.selectedValue = [];
    },
    computed: {
        showValue() {
            return this.data.options.multiple ? '' : this.computedList.find(item => item.id === this.value)?.value
        },
        clearAble() {
            if (!this.inputclearable) return false;
            return this.data.options.multiple ? this.value.length : this.value
        },
        computedList() {
            const listWithPossibleDupes = this.data.options.multiple
                ? [...this.selectedList, ...this.list.filter(item => !(this.value.includes(item.id)))]
                : [this.selectedList, ...this.list.filter(item => item.id !== Number(this.value))].filter(i => Object.keys(i).length)

            const removeDuplicates = (arr) =>arr.filter((item, index) => arr.indexOf(item) === index);
            const listItemIds = removeDuplicates(listWithPossibleDupes.map(listItem => listItem.id));
            return listItemIds.map(listItemId => listWithPossibleDupes.find(listItem => listItem.id === listItemId));
        },
        tags(){
            return new Set(this.computedList.map(item => item.type))
        }
    },
    methods: {
        handleClick() {
            let isExist = Object.keys(this.data.options).find(i => i === 'prefetch');
            if (isExist && !this.data.options.prefetch) {
                this.$emit('search-and-select-click');
                this.addToList(this.searchValue);
            }
        },
        clearValue() {
            if (!this.data.options.multiple) {
                this.changeSelectedValue({id: ''})
                this.selectedList = ''
                this.list = []
                this.addToList(this.searchValue);
            } else {
                this.selectedValue = [];
                this.selectedList = []
            }
            this.changed()
        },
        changed() {
            this.$emit('input', this.selectedValue);
            this.searchValue = "";
        },
        changeSelectedValue(value) {
            if (this.data.options.multiple) {
                this.selectedValue = this.selectedValue.includes(value.id) ? this.selectedValue.filter(i => i !== value.id) : [...this.selectedValue, value.id];
                this.selectedList = this.selectedList.some(item => item.id === value.id) ? [...this.selectedList.filter(i => i.id !== value.id)] : [...this.selectedList, value];
            } else {
                if (!value) return;
                this.selectedValue = value.id;
                this.selectedList = this.list.find(item => item.id === value.id)
            }
            this.navigationStart = false;
            this.changed();
        },
        enterSelectedValue() {
            this.options.filter((item, index) => {
                if (index == this.activeIndex) {
                    this.changeSelectedValue(item)
                }
            });
            this.endNavigation();
        },
        clear() {
            this.selectedValue = '';
            this.changed();
        },
        addToList(searchText, withSelected = false) {
            let urlObj = {...this.data.options.params}
            urlObj[this.query_name] = searchText;
            urlObj['page'] = this.pageNumber;
            urlObj['per_page'] = this.per_page;
            urlObj['page'] = this.pageNumber;
            if (withSelected) urlObj['selected'] = this.value;
            let url = `${this.data.options.url}?${new URLSearchParams(urlObj)}`;
            this.isLoading = true;
            setTimeout(() => {
                fetch(url).then(res => {
                    if (res.ok) {
                        return res.json()
                    }
                }).then(res => {
                    if (this.data.options.modifire) this.list = [...this.list, ...res.data.map(item => this.data.options.modifire(item))]
                    else this.list = [...this.list, ...res.data]
                    this.isLoading = false;
                    this.noMoreData = res.data.length < this.per_page
                    if (this.value && withSelected) {
                        if (this.data.options.multiple) {
                            for (let id of this.value.split(',').map(i => Number(i))) {
                                this.changeSelectedValue(this.list.find(item => item.id === id))
                            }
                        } else this.changeSelectedValue([this.selectedList, ...this.list].find(item => item.id === Number(this.value)))
                    }
                })
            }, 1000)
        },
        searchAndSet(searchText) {
            this.noMoreData = false;
            this.pageNumber = 1;
            this.list = [];
            this.addToList(searchText);
        },
        addMore(searchText) {
            if (!this.noMoreData) {
                this.pageNumber = this.pageNumber + 1;
                this.addToList(searchText);
            }
        },
        search: _.debounce(function (key) {
            this.searchAndSet(key.target.value);
        }, 500),
        loadMore: _.debounce(function (e) {
            if (!this.isLoading) {
                let scrollBar = e.target;
                let isReachBottom = (scrollBar.scrollTop + scrollBar.clientHeight >= scrollBar.scrollHeight - 20);
                isReachBottom && this.addMore(this.searchValue);
            }
        }, 500),
    },
    watch: {
        'searchValue': {
            handler(n, o){
             
            }
        }
    }
}
</script>

<style lang="sass" scoped>
.dropdown-menu
    transform: translateY(-15%) !important
</style>
