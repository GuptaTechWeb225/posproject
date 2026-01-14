<template>
  <div v-if="this.$can('view_branch_or_warehouses')">
    <app-pre-loader v-if="loading"/>
    <div v-else class="dropdown dropdown-with-animation d-inline-block btn-dropdown btn-tenant-dropdown">
      <template>
        <button v-if="branch_or_warehouses.length" 
                @click="checkForOpenedRegisters"
                type="button"
                class="btn"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false">
                <span class="d-none d-md-inline-block mr-2"> {{ currentBranchOrWarehouse.id ? currentBranchOrWarehouse.name : $t('all') }} </span>
          <app-icon name="chevron-down" class="size-15 primary-text-color"/>
        </button>
      </template>

      <div class="dropdown-menu p-0">
        <app-pre-loader v-if="cashRegisterDetailsLoading"/>
        <template v-else>
            <template v-if="openedCashRegisterDetails">
                <div class="text-center py-4">
                    <app-icon 
                        name="alert-circle" 
                        class="size-50 text-warning" 
                    />
                    <p class="mt-2">{{ $t('cash_register_open') }}</p>
                    <small> {{ $t('close_the_opened_register_to_switch_branches') }} </small>
                </div>
            </template>
            <template v-else>
                <div v-if="branch_or_warehouses.length"
                    class="tenant-items custom-scrollbar max-height-300 overflow-auto">
                    <a v-if="role_ids.includes(roles[0].id)" href="#" class="dropdown-item py-4 d-flex align-items-center"
                        @click="changeBranchOrWarehouse(allBranchOrWarehouse)">
                        {{ $t('all') }}
                    </a>

                    <p href="#" class="dropdown-item py-3"
                        :class="{'primary-text-color': branch_or_warehouse.id === currentBranchOrWarehouse ? currentBranchOrWarehouse.id : '', 'separator': branch_or_warehouse.last_branch}"
                        v-for="branch_or_warehouse in branchAndWarehouseIdsSorted"
                        @click="changeBranchOrWarehouse(branch_or_warehouse)">
                        <span>{{ branch_or_warehouse.name }}</span> <br>
                        <small class="text-muted text-capitalize">{{ branch_or_warehouse.type }}</small>
                    </p>
                </div>
            </template>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
import {BRANCHES_OR_WAREHOUSES} from "../../../Config/ApiUrl-CPB";
import {axiosGet} from "../../../../common/Helper/AxiosHelper";
import {mapActions, mapGetters} from "vuex";
import {urlGenerator} from "../../../Helper/Helper";
import { CASH_COUNTER_OPEN_CLOSE_STATUS } from "../../../../tenant/Config/ApiUrl-CP";

export default {
    name: 'BranchManager',
    data() {
        return {
            loading: true,
            disableBranchSwitching: false,
            showOpenedCashRegisterModal: false,
            allBranchOrWarehouse: {
                id: null,
                name: 'All'
            },
            roles: [],
            role_ids: [1, 2, 3], //admin_role, manager, warehouse_manager
            branch_or_warehouses: '',
            currentBranchOrWarehouse: {},
            openedCashRegisterDetails: null,
            registerDataLoading: false,
            cashRegisterDetailsLoading: false
        }
    },
    methods: {
        getBranchAndWarehouses() {
            axiosGet(`${BRANCHES_OR_WAREHOUSES}?active=true`).then(response => {
                this.branch_or_warehouses = response.data.data;
                let currentBranchWahrehouseId = localStorage.getItem('currentBranchWahrehouseId');
                let currentBranchWahrehouseName = localStorage.getItem('currentBranchWahrehouseName');
                if (currentBranchWahrehouseId) {
                    this.setBranchOrWarehouseId(eval(currentBranchWahrehouseId));
                    this.setBranchOrWarehouseName(currentBranchWahrehouseName === 'null' ? null : currentBranchWahrehouseName);
                    return;
                }
                const userBranch = this.branch_or_warehouses.find(bw => bw.id === window.user.branch_or_warehouse_id);
                this.changeBranchOrWarehouse(userBranch);
            }).catch(error => {
                this.loading = false;
            }).finally(() => {
                this.loading = false;
            })
        },
        async checkForOpenedRegisters() {
            this.cashRegisterDetailsLoading = true
            const data = await axiosGet(CASH_COUNTER_OPEN_CLOSE_STATUS)
            if (data.data.cash_register) {
                this.openedCashRegisterDetails = data.data.cash_register
            }
            this.cashRegisterDetailsLoading = false
            return
        },
        changeBranchOrWarehouse(branch_or_warehouse) {
            if (this.openedCashRegisterDetails) {
                this.showOpenedCashRegisterModal = true
                return;
            }
            if (+branch_or_warehouse.id === +this.currentBranchOrWarehouse.id) return;
            this.currentBranchOrWarehouse = branch_or_warehouse;

            //update in vuex
            console.log(eval(branch_or_warehouse.id))
            this.setBranchOrWarehouseId(eval(branch_or_warehouse.id));
            this.setBranchOrWarehouseName(branch_or_warehouse.name);

            localStorage.setItem('currentBranchWahrehouseId', eval(branch_or_warehouse.id));
            localStorage.setItem('currentBranchWahrehouseName', branch_or_warehouse.name);

            localStorage.removeItem('cartState');
            localStorage.removeItem('cash_register_id');

            // removing sales view data from localStorage if user switches branches with an opened cash register
            const salesViewPathName = '/sales/view';
            if (window.location.pathname.includes(salesViewPathName)) return window.location.replace(urlGenerator('/dashboard'));
        },

        ...mapActions(['setBranchOrWarehouseId', 'setBranchOrWarehouseName'])
    },
    created() {
        this.roles = window.user.roles
        this.getBranchAndWarehouses();
    },

    computed: {
        ...mapGetters(['getBranchOrWarehouseId', 'getBranchOrWarehouseName', 'getStocksByBranchOrWarehouse']),
        branchAndWarehouseIdsSorted() {
            if (!this.branch_or_warehouses) return [];
            const branches = this.branch_or_warehouses.filter(branchOrWarehouse => branchOrWarehouse.type === 'branch');
            branches[branches.length - 1]['last_branch'] = true;
            const warehouses = this.branch_or_warehouses.filter(branchOrWarehouse => branchOrWarehouse.type === 'warehouse');
            return [...branches, ...warehouses];
        }
    },

    async mounted() {
        await this.checkForOpenedRegisters();
        if (this.openedCashRegisterDetails) {
            const {id, name} = this.openedCashRegisterDetails.branch_or_warehouse;

            localStorage.setItem('currentBranchWahrehouseId', eval(id));
            localStorage.setItem('currentBranchWahrehouseName', name);
        }

        let currentBranchWahrehouseId = localStorage.getItem('currentBranchWahrehouseId');
        let currentBranchWahrehouseName = localStorage.getItem('currentBranchWahrehouseName');

        this.currentBranchOrWarehouse = {
            id: eval(currentBranchWahrehouseId),
            name: currentBranchWahrehouseName
        }
    }
}
</script>

<style lang="sass">
.custom-disable
  opacity: 0.5

.separator
    position: relative
    //border-bottom: 2px solid #dddddd

.separator::after
    content: ''
    position: absolute
    display: block
    width: 100%
    height: 2px
    background-color: #afb1b6
    border-radius: 50px
    bottom: -13%
    left: 50%
    transform: translateX(-50%)
</style>
