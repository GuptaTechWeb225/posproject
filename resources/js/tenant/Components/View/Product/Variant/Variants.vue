<template>
    <div>
        <div class="card card-with-shadow border-0">
            <div class="card-header bg-transparent d-flex align-items-center justify-content-between p-primary">
                <div>
                    <h5 class="text-muted mb-0">
                        {{ $t('variants') }} : {{ tempVariants ? tempVariants.length : '' }} {{
                            $t('items')
                        }}
                    </h5>
                </div>
                <div class="d-flex align-items-center">
                    <div class="mr-3">
                        <span class="text-muted"> {{ $t('active') }} </span>:
                        <span class="text-success">{{ variantCount.active }}</span>
                    </div>
                    <div>
                        <span class="text-muted"> {{ $t('inactive') }} </span>:
                        <span class="text-danger">{{ variantCount.inactive }}</span>
                    </div>
                </div>
            </div>

            <div class="card-body p-0 m-4">
                <app-table
                    class="variants-product-table"
                    id="variant-table"
                    :options="options"
                    @action="triggerActions"
                />
            </div>
        </div>

        <variant-add-edit-modal
            v-if="isModalActive"
            v-model="isModalActive"
            :product-id="productId"
            :selected-url="url"
            @close="isModalActive = false"
        />

        <app-confirmation-modal
            v-if="confirmationModalActive"
            icon="trash-2"
            modal-id="app-confirmation-modal"
            @confirmed="confirmed('variant-table')"
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
import {ucWords} from "../../../../../common/Helper/Support/TextHelper";
import {PRODUCTS, VARIANT} from "../../../../Config/ApiUrl-CP";
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import DropdownAction from "../../../../../core/components/datatable/DropdownAction";
import VariantMixin from "../../../Mixins/VariantMixin";
import {axiosPatch, axiosPost, urlGenerator} from "../../../../../common/Helper/AxiosHelper";

export default {
    name: "Variants",
    mixins: [FormHelperMixins, VariantMixin],
    components: {
        DropdownAction,
    },
    props: {
        productId: {}
    },

    data() {
        return {
            ucWords,
            isModalActive: false,
            confirmationModalActive: false,
            variantStatusChangeModalOpen: false,
            tempVariants: [],
            variantId: '', // this gets set from the trigger action function temporarily before being reset to an empty string if user decides to not change the variant status
            currentVariantStatus: '',
            url: '',
        }
    },
    computed: {
        variantCount() {
            return this.tempVariants.reduce((carry, variant) => {
                variant.status?.translated_name === 'Active' ? carry.active += 1 : carry.inactive += 1;
                return carry;
            }, {active: 0, inactive: 0});
        },
    },
    methods: {
        cancelVariantStatusChange() {
            this.variantStatusChangeModalOpen = false;
            this.variantId = '';
            this.currentVariantStatus = '';
        },
        changeVariantStatus() {
            axiosPatch(`app/variant/${this.variantId}/status-update`, {status: this.currentVariantStatus !== 'status_active'})
               .then(({data}) => {
                   this.toastAndReload(data.message, 'variant-table');
                   this.variantStatusChangeModalOpen = false;
               });
            // calling the cancel function to reset them modal's state
            this.cancelVariantStatusChange();
        },
        openModal() {
            this.isModalActive = true;
            this.url = '';
        },
        triggerActions(row, action, active) {
            if (action.type === 'edit') {
                this.url = `${VARIANT}/${row.id}`;
                this.isModalActive = true;
            } else if (action.type === 'status_change') {
                this.variantId = row.id;
                this.currentVariantStatus = row.status.name;
                this.variantStatusChangeModalOpen = true;
            } else if (action.type === 'show_stock_overview') {
                return window.location.replace(urlGenerator(`inventory/stock/overview/variant/${row.id}`));
            } else {
                this.getAction(row, action, active)
            }
        },
    }
}
</script>
