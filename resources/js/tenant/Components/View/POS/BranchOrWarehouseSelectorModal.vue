<template>
    <app-modal modal-id="branch-or-warehouse-select-modal"
               :title="$t('select_branch_or_warehouse')"
               modal-alignment="top"
               modal-size="large"
               :modal-backdrop="false"
               v-model="showBranchOrWarehouseSelectorModal"
               @close-modal="closeModal">

        <template #header>
            <div class="header col-12">
                <h4 class="p-0 m-0">{{ $t('select_a_branch_or_a_warehouse') }}</h4>
            </div>
        </template>

        <template #body>
            <div>
                <label>{{ $t('branch_or_warehouse') }}</label>
                <app-input
                    class="margin-right-8"
                    type="search-and-select"
                    :placeholder="$placeholder('branch_or_warehouse')"
                    :options="branchesOrWarehousesOptions"
                    v-model="branchOrWarehouseId"
                />
            </div>
        </template>

        <template #footer>
            <button :class="`btn btn-primary mr-3 ${!branchOrWarehouseId  && 'custom-disable'}`" @click="handleBranchOrWarehouseSelect"> {{ $t('select') }} </button>
            <button class="btn btn-secondary" @click="handleBackBtnClick">{{ $t('back_to_dashboard') }}</button>
        </template>
    </app-modal>
</template>

<script>
import FormHelperMixins from "../../../../common/Mixin/Global/FormHelperMixins";
import {axiosGet, urlGenerator} from "../../../../common/Helper/AxiosHelper";
import { mapGetters, mapActions } from "vuex";
import { SELECTABLE_BRANCHES_OR_WAREHOUSES } from "../../../Config/ApiUrl-CP";
import {BRANCHES_OR_WAREHOUSES} from "../../../Config/ApiUrl-CPB";

export default {
    name: "CashRegisterSelectModal",
    mixins: [FormHelperMixins],
    mounted() {
        this.getBranchOrWarehouseNames();
    },
    data() {
        return {
            showBranchOrWarehouseSelectorModal: '',
            branchOrWarehouseId: '',
            branchAndWarehouses: [],
            branchesOrWarehousesOptions: {
                url: urlGenerator(SELECTABLE_BRANCHES_OR_WAREHOUSES),
                query_name: "search_by_name",
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
                modifire: ({id, name: value}) => ({id, value}),
            },
        }
    },
    computed: {
        ...mapGetters(['getBranchOrWarehouseId', 'getNewCashRegisterId']),
    },
    methods: {
        ...mapActions(['setBranchOrWarehouseId', 'setBranchOrWarehouseName']),
        handleBackBtnClick() {
            window.location.replace(urlGenerator('/dashboard'));
        },
        getBranchOrWarehouseNames() {
            axiosGet(BRANCHES_OR_WAREHOUSES).then(response => {
                this.branchAndWarehouses = response.data.data;
            }).catch(e => {
                this.$toastr.e(e);
            })
        },
        handleBranchOrWarehouseSelect() {
           this.setBranchOrWarehouseId(this.branchOrWarehouseId);
           const {name: selectedBranchName} = this.branchAndWarehouses.find(branchOrWarehouse => branchOrWarehouse.id === this.branchOrWarehouseId)
           this.setBranchOrWarehouseName(selectedBranchName);

           localStorage.setItem('currentBranchWahrehouseId', this.branchOrWarehouseId);
           localStorage.setItem('currentBranchWahrehouseName', selectedBranchName);

           this.$emit('branch-select', this.branchOrWarehouseId);
           this.closeModal();
        },
        closeModal() {
            $('branch-or-warehouse-select-modal').modal('hide');
            this.$emit('close');
        },
    }
}
</script>

<style lang="sass">
.custom-disable
    opacity: 0.5
    pointer-events: none
</style>