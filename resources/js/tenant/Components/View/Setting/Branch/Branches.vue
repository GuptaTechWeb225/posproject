<template>
    <div class="content-wrapper">

        <app-page-top-section :title="$t('branch_&_warehouses')" icon="briefcase">
            <app-default-button
                v-if="this.$can('create_branch')"
                @click="openWarehouseModal"
                :title="$addLabel('warehouse')"
                btn-class="btn btn-success"
            />

            <app-default-button
                v-if="this.$can('create_branch')"
                class="ml-3"
                @click="openBranchModal"
                :title="$addLabel('branch')"
            />
        </app-page-top-section>


        <app-table id="branches-and-warehouses-table"
            :options="options"
            @action="triggerActions"/>


        <branch-create-edit-modal
            v-if="branchModalIsActive"
            v-model="branchModalIsActive"
            :selected-url="branchUrl"
            :status="status"
            @close="branchModalIsActive = false"
        />

        <warehouse-create-edit-modal
            v-if="warehouseModalIsActive"
            v-model="warehouseModalIsActive"
            :selected-url="warehouseUrl"
            :status="status"
            @close="warehouseModalIsActive = false"
        />

        <app-confirmation-modal
            v-if="confirmationModalActive"
            :firstButtonName="$t('yes')"
            modal-class="warning"
            icon="trash-2"
            modal-id="app-confirmation-modal"
            @confirmed="confirmed('branches-and-warehouses-table')"
            @cancelled="cancelled"
        />

        <app-confirmation-modal
            v-if="statusActive"
            :firstButtonName="$t('yes')"
            modal-class="warning"
            icon="slash"
            :message="$t('you_are_going_to_deactivate_a_branch')"
            modal-id="app-confirmation-modal"
            @confirmed="changeStatus"
            @cancelled="statusActiveCancel"
        />

        <app-confirmation-modal
            v-if="statusInActive"
            :firstButtonName="$t('yes')"
            modal-class="primary"
            icon="check-square"
            :message="$t('you_are_going_to_activate_a_branch')"
            modal-id="app-confirmation-modal"
            @confirmed="changeStatus"
            @cancelled="statusInActiveCancel"
        />

        <app-branch-users-modal
            :alias="alias"
            v-if="showUserList"
            :branch-or-warehouse-id="branchIdForUsersModal"
            :user-list="branchUsers"
            :branch-name="branchName"
            :branch-type="type"
            v-model="showUserList"
            :role="role"
        />

    </div>
</template>

<script>
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import BranchesMixin from "../../../Mixins/BranchesMixin";
import DatatableHelperMixin from "../../../../../common/Mixin/Global/DatatableHelperMixin";
import BranchCreateEditModal from "./BranchCreateEditModal";
import CoreLibrary from "../../../../../core/helpers/CoreLibrary";
import {BRANCH, GET_BRANCH_STATUS, BRANCH_OR_WAREHOUSE} from "../../../../Config/ApiUrl-CPB";
import {axiosDelete, axiosPatch} from "../../../../../common/Helper/AxiosHelper";
import ManageRolesModal from "../../../../../common/Components/UserRole/UI/ManageRolesModal";
import {mapMutations, mapGetters} from "vuex";

export default {
    name: "Branches",
    extends: CoreLibrary,
    components: {BranchCreateEditModal, "app-manage-roles": ManageRolesModal},
    mixins: [FormHelperMixins, BranchesMixin, DatatableHelperMixin],
    data() {
        return {
            showUserList: false,
            alias: 'tenant',
            role: {},
            branchModalIsActive: false,
            statusActive: false,
            statusInActive: false,
            status: '',
            branchUrl: '',
            warehouseUrl: '',
            switchValue: '',
            GET_BRANCH_STATUS,
            warehouseModalIsActive: false,
            selectedBranchId: '',
            branchUsers: [],
            branchName: '',
            branchIdForUsersModal: '',
        }
    },
    created() {
        this.getBranchStatus()
    },
    computed: {
        ...mapGetters(['getBranchOrWarehouseId']),
    },
    methods: {
        ...mapMutations([ 'INC_BRANCH_MANAGER_KEY' ]),
        changeStatus() {
            this.branchId = this.selectedBranchId;
            if (+this.branchId === +this.getBranchOrWarehouseId) return this.$toastr.e(this.$t('cannot_deactivate_the_current_branch'));
            axiosPatch(`app/branch_or_warehouse/${this.branchId}/update-status`, {status: this.switchValue}).then(({data}) => {
                this.toastAndReload(data.message, 'branches-and-warehouses-table');
                this.INC_BRANCH_MANAGER_KEY(); // re-rendering the BranchManager component
            }).catch(({response}) => {
                this.toastAndReload(response.data.message, 'branches-and-warehouses-table');
            }).finally(() => this.resetStatusChangeValues());
        },
        resetStatusChangeValues() {
            this.confirmationModalActive = false;
            this.selectedBranchId = '';
            this.switchValue = '';
        },
        statusActiveCancel() {
            this.statusActive = false;
            this.switchValue = this.cancelStatus;
            this.resetStatusChangeValues();
        },
        statusInActiveCancel() {
            this.statusInActive = false;
            this.switchValue = this.cancelStatus;
            this.resetStatusChangeValues();
        },
        openBranchModal(selectedUrl) {
            this.branchUrl = selectedUrl;
            this.branchModalIsActive = true;
        },
        openWarehouseModal(selectedUrl) {
            this.warehouseUrl = selectedUrl;
            this.warehouseModalIsActive = true;
        },
        triggerActions(row, action, active) {
            action.url = 'app/branch_or_warehouses';
            if (action.type === 'edit') {
                if (row.type === 'branch') this.openBranchModal(`${BRANCH_OR_WAREHOUSE}/${row.id}`);
                else this.openWarehouseModal(`${BRANCH_OR_WAREHOUSE}/${row.id}`);
                this.status = row.status;
            } else if (action.type === 'status_change') {
                this.selectedBranchId =  row.id;
                const currentBranchStatus = row.status.translated_name.toLowerCase();
                this.switchValue = currentBranchStatus !== 'active';
                currentBranchStatus === 'active' ? this.statusActive = true : this.statusInActive = true;
            } else if (action.type === 'delete') {
                this.delete_url = `${BRANCH_OR_WAREHOUSE}/${row.id}`;
                this.confirmationModalActive = true;
            } else if (action.type === 'modal') {
                this.branchUsers = row.users;
                this.branchName = row?.name;
                this.branchIdForUsersModal = row.id;
                this.showUserList = true;
            }
            else {
                this.getAction(row, action, active)
            }
        },
        getBranchStatus() {
            this.axiosGet(GET_BRANCH_STATUS).then(response => {
                let name = this.options.filters.find(element => element.key === 'statusId');
                if (name) {
                    name.option = response.data.map(status => {
                        return {
                            id: status.id,
                            name: status.translated_name
                        }
                    });
                }
            });
        },
    },
}
</script>
