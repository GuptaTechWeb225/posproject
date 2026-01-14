<template>
    <div class="row justify-content-center py-5 max-height-450 overflow-auto">
        <div class="col-11 col-lg-10">
            <div class="d-flex mb-primary">
                <div>
                    <app-icon class="text-muted size-40" name="layers"/>
                </div>
                <div class="ml-3">
                    <h6 :key="data.id">{{ data.name }} {{ $t('has') }} {{ data.variants.length }} {{
                            $t('variants')
                        }}</h6>
                    <h6 class="text-muted">
                        {{ $t('active') }} {{ variantCount.active }},
                        {{ $t('inactive') }} {{ variantCount.inactive }}
                    </h6>
                </div>
            </div>

            <div class="row mb-primary" v-for="(variant, index) in data.variants" :key="index">
                <div class="col-12 col-lg-3 mb-3 mb-lg-0">
                    <div class="d-flex">
                        <div class="mr-2">
                            <app-avatar
                                :border="true"
                                :img="getVariantThumbnail(variant.id)"
                                :status="variant.status ? variant.status.name === 'status_active' ? 'success' : 'secondary' : 'secondary'"
                                :title="variant.name"
                                avatar-class="avatars-w-50"
                            />
                        </div>
                        <div>
                            <p class="mb-1">{{ variant.name }}</p>
                            <p class="text-muted mb-0">{{ variant.description !== 'null' ? variant.description : '' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-4 col-lg-3 mb-3 mb-lg-0">
                    <div>
                        <p class="mb-0">
                            <span class="text-muted">{{ $t('tax') }}(%): </span>
                            <template v-if="variant.tax">{{ parseInt(variant.tax.percentage) }}</template>
                            <template v-else>{{ $t('not_added_yet') }}</template>
                        </p>
                    </div>
                </div>

                <div class="col-5 col-lg-3 mb-3 mb-lg-0">
                    <div>
                        <div class="d-flex">
                            <span class="text-muted mr-2">{{ $t('avg_purchase_price') }}:</span>
                            <template class="text-muted mr-2">{{ numberWithCurrencySymbol(variant.average_purchase_price ?? 0) }}</template>
                        </div>
                        <p class="mb-0">
                            <span class="text-muted">{{ $t('selling_price') }}: </span>
                            <template class="text-muted"> {{ numberWithCurrencySymbol(variant.selling_price) }}</template>
                        </p>
                    </div>
                </div>

                <div class="col-3 col-lg-2 mb-3 mb-lg-0" v-if="false">
                    <div class="d-flex justify-content-center">
                        <app-input
                            v-model="statusValue[index]"
                            :label="$t('switch')"
                            type="switch"
                            @input="toggle(index, variant.id)"
                        />
                        <span class="ml-1">{{ statusValue[index] === true ? $t('active') : $t('inactive') }}</span>
                    </div>
                </div>

                <div class="col-12 col-lg-3 mb-3 mb-lg-0">
                    <div class="d-flex justify-content-end justify-content-lg-center">
                        <dropdown-action
                            :key="`dropdown${variant.id}`"
                            :actions="actions"
                            :row-data="variant"
                            :unique-key="variant.id"
                            @action="triggerAction"
                        />
                    </div>
                </div>
            </div>

        </div>

        <variant-add-edit-modal
            v-if="isModalActive"
            v-model="isModalActive"
            :selected-url="url"
            @close="isModalActive = false"
            :product-id="productId"
        />

        <app-confirmation-modal
            v-if="confirmationModalActive"
            icon="trash-2"
            modal-id="app-confirmation-modal"
            @confirmed="confirmed('product-table')"
            @cancelled="cancelled"

        />

        <app-confirmation-modal
            v-if="variantStatusChangeModalOpen"
            icon="alert-circle"
            modal-id="app-confirmation-modal"
            :message="$t('are_you_sure_you_want_to_change_the_status')"
            @cancelled="cancelVariantStatusChange"
            @confirmed="changeVariantStatus"
        />

    </div>
</template>

<script>
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import DefaultAction from "../../../../../core/components/datatable/DefaultAction";
import CashRegisterAddEditModal from "../../Counter/CounterAddEditModal";
import Status from "./ProductStatusComponent";
import DropdownAction from "../../../../../core/components/datatable/DropdownAction";
import {axiosPatch, urlGenerator} from "../../../../../common/Helper/AxiosHelper";
import {CHANGE_VARIANT_STATUS, VARIANT} from "../../../../Config/ApiUrl-CP";
import DeleteMixin from "../../../../../common/Mixin/Global/DeleteMixin";
import {numberWithCurrencySymbol} from "../../../../Helper/Helper";

export default {
    name: "VariantDetails",
    mixins: [FormHelperMixins, DeleteMixin],
    props: ['tableID', 'value', 'data'],
    components: {
        DropdownAction,
        Status,
        DefaultAction,
        CashRegisterAddEditModal
    },
    data() {
        return {
            variantStatusChangeModalOpen: false,
            productId: this.data.id,
            img: '',
            status: this.data.status.name,
            statusValue: [],
            clickedIndex: null,
            clickedVariantId: null,
            isModalActive: false,
            statusActive: false,
            statusInActive: false,
            cancelStatus: false,
            variantId: '',
            url: '',
            deleteUrl: '',
            confirmationModalActive: false,
            openVariant: null,
            closeVariant: null,
            statusInfo: {},
            actions: [
                {
                    title: this.$t('edit'),
                    icon: 'edit',
                    type: 'edit',
                },
                {
                    title: this.$t('deactivate'),
                    type: 'status_change',
                    name: 'deactivate',
                    component: '',
                    modalId: 'product-modal',
                    modifier: (value) => {
                        if (!value.status) return false;
                        return value.status.name === 'status_active'
                    },
                },
                {
                    title: this.$t('activate'),
                    type: 'status_change',
                    name: 'activate',
                    component: '',
                    modalId: 'product-modal',
                    modifier: (value) => {
                        if (!value.status) return false;
                        return value.status.name !== 'status_active'
                    },
                },
                {
                    title: this.$t('show_stock_overview'),
                    type: 'show_stock_overview',
                    name: 'show_stock_overview',
                },
            ],
        }
    },
    mounted() {
        const deleteAction = {
            title: this.$t('delete'),
            type: 'delete',
            icon: 'trash-2',
            component: 'app-confirmation-modal',
            modalId: 'app-confirmation-modal',
            url: VARIANT,
            name: 'delete'
        };

        if (this.data.variants.length > 1) this.actions.push(deleteAction);
    },
    computed: {
        variantCount() {
            return this.data.variants.reduce((carry, variant) => {
                variant.status?.translated_name === 'Active' ? carry.active += 1 : carry.inactive += 1;
                return carry;
            }, {active: 0, inactive: 0});
        },
        checkStatus() {
            return this.status === 'status_active' ? 'success' : 'secondary';
        }
    },
    created() {
        this.data.variants.forEach(variant => {
            parseInt(variant.is_enabled) === 1 ? this.statusValue.push(true) : this.statusValue.push(false);
        });
    },
    methods: {
        numberWithCurrencySymbol(value) {
            return numberWithCurrencySymbol(value);
        },
        getVariantThumbnail(variantId) {
            const variant = this.data.variants.find(variant => variant.id === variantId);
            return variant.thumbnail ? variant.thumbnail.full_url : '';
        },
        cancelVariantStatusChange() {
            this.variantStatusChangeModalOpen = false;
            this.variantId = '';
            this.currentVariantStatus = '';
        },
        changeVariantStatus() {
            axiosPatch(`app/variant/${this.variantId}/status-update`, {status: this.currentVariantStatus !== 'status_active'})
                .then(({data}) => {
                    this.toastAndReload(data.message, 'product-table');
                    this.variantStatusChangeModalOpen = false;
                });
            // calling the cancel function to reset them modal's state
            this.cancelVariantStatusChange();
        },
        toggle(index, id) {
            this.clickedIndex = index;
            this.clickedVariantId = id;
            this.statusInfo.status = this.statusValue[index];

            axiosPatch(CHANGE_VARIANT_STATUS + '/' + id, this.statusInfo).then(res => {
                this.$hub.$emit('reload-product-table');
            }).catch((error) => {
                this.$toastr.e(error);
            })

        },
        openModal() {
            this.isModalActive = true;
            this.url = '';
        },
        triggerAction(row, action) {
            if (action.type === 'edit') {
                this.url = `${VARIANT}/${row.id}`;
                this.isModalActive = true;
            } else if (action.type === 'status_change') {
                this.variantId = row.id;
                this.currentVariantStatus = row.status.name;
                this.variantStatusChangeModalOpen = true;
            } else if (action.type === 'delete') {
                this.confirmationModalActive = true;
                this.deleteUrl = `${VARIANT}/${row.id}`;
                this.delete_url = `${VARIANT}/${row.id}`;
            } else if (action.type === 'show_stock_overview') {
                return window.location.replace(urlGenerator(`inventory/stock/overview/variant/${row.id}`));
            }
        },
    },
}
</script>
