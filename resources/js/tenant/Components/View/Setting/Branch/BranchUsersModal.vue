<template>
    <modal id="manage-role"
           v-model="showModal"
           :title="$fieldTitle('manage', 'users', true)"
           @submit="submitData"
           :scrollable="false"
           body-class="px-0"
           :loading="loading"
           :cancel-btn-label="$t('close')"
           :preloader="preloader"
    >
      <app-pre-loader v-if="false"></app-pre-loader>

      <form v-else ref="form"
            :data-url='selectedUrl ? selectedUrl : "" '
            @submit.prevent="submitData">

        <div class="custom-scrollbar px-primary" style="max-height: 300px; overflow-y: auto" v-if="computedUsers.length">

          <h5> {{ $t('branch_or_warehouse') }}: {{branchName ?? ''}}</h5><hr>

          <div v-for="(user, index) in computedUsers" :key="index"
               class="d-flex align-items-center justify-content-between"
               :class="{'pb-3 mb-3 border-bottom': index !== user_list.length - 1}">
            <div class="media d-flex align-items-center">
              <div class="avatars-w-50 ml-2">
                <app-avatar :title="user.full_name"
                            :shadow="true"
                            :img="$optional(user.profile_picture, 'full_url')"
                            :alt-text="user.full_name"/>
              </div>
              <div class="media-body ml-3">
                {{ user.full_name }}
                <p class="text-muted font-size-90 mb-0">{{ user.email }}</p>
                <small class="text-capitalize bg-success px-3 py-1 text-white" style="border-radius: 8rem;">{{ user?.roles[0]?.name }}</small>
              </div>
            </div>
            <div>
              <a href="#" @click="deactivateUser(user, index)"
                 class="d-flex align-items-center text-muted font-size-90">
                <app-icon name="trash" class="mr-1" width="19"/>
              </a>
            </div>
          </div>
        </div>
        <div :class="`p-primary ${ $isRtl ? 'text-left' : '' }`" ref="form" :data-url="attachURL">
          <app-form-group-selectable
              type="multi-select"
              :label="$t('add_users')"
              list-value-field="full_name"
              v-model="formData.users"
              :error-message="$errorMessage(errors, 'users')"
              :fetch-url="SELECTABLE_USERS"
          />
        </div>
      </form>
    </modal>
</template>

<script>
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import ModalMixin from "../../../../../common/Mixin/Global/ModalMixin";
import {TENANT_ROLES} from "../../../../../common/Config/apiUrl";
import {SELECTABLE_USERS, MANAGE_USERS} from "../../../../Config/ApiUrl-CP";
import {axiosGet, axiosPatch} from "../../../../../common/Helper/AxiosHelper";
import {USER_STATUS_CHANGE} from "../../../../Config/ApiUrl-CP";
import StatusQueryMixin from "../../../../../common/Mixin/Global/StatusQueryMixin";
import {urlGenerator} from "../../../../Helper/Helper";
import {collection} from "../../../../../common/Helper/helpers";
import {formDataAssigner} from "../../../../Helper/Helper";

export default {
    mixins: [FormHelperMixins, ModalMixin, StatusQueryMixin],
    name: "BranchUsersModal",
    props: {
        role: {
            required: true
        },
        branchOrWarehouseId: {
            required: true,
        },
        userList: [],
        branchName: ''
    },
    data() {
        return {
            SELECTABLE_USERS,
            user_list: [],
            userStatuses: [],
            formData: {
                users: []
            },
            urlGenerator,
            selectedUser: 'test',
        }
    },
    mounted() {
        this.getUserStatuses();
    },
    computed: {
        attachableUsers() {
            return this.$store.state.user.users;
        },
        attachURL() {
            return {
                tenant: `${TENANT_ROLES}/${this.role.id}/attach-users`,
                app: `admin/auth/roles/${this.role.id}/attach-users`,
            }[this.alias];
        },
        computedUsers() {
            if (this.activeStatusData.length === 0) return [];
            return this.user_list.filter(user => user.status_id === this.activeStatusData.id);
        },
        activeStatusData() {
            if (this.userStatuses.length === 0) return [];
            return this.userStatuses.find(data => data.translated_name.toLowerCase() === 'active');
        },
        roleUsersLength() {
            if (!this.role.users) return 0;
            return this.role.users.length;
        }
    },
    methods: { 
        getUserStatuses() {
            axiosGet(this.getStatus('user')).then(data => this.userStatuses = data.data.data);
        },
        submitData() {
          this.fieldStatus.isSubmit = true;
          this.loading = true;
          this.message = '';
          this.errors = {};
          this.formData.users = this.formData.users.filter(user => +user !== 1); // default user id: 1 (app admin)

          const url = `${MANAGE_USERS}/${this.branchOrWarehouseId}`;
          this.submitFromFixin('patch', url, this.formData);
        },
        afterSuccess(response) {
            this.$toastr.s('', response.data.message);
            this.user_list = this.attachableUsers.filter(user => {
                return this.formData.users.includes(user.id);
            }).concat(this.user_list);

            this.formData.users = [];

            this.$emit('input', false);
            $("#manage-role").modal('hide');
            this.$hub.$emit('reload-branches-and-warehouses-table');
        },
        deactivateUser(user) {
            const userStatus = this.userStatuses.find(status => status.id === user.status_id).translated_name.toLowerCase();
            const { id: activeStatusId } = this.activeStatusData
            const { id: inActiveStatusId } = this.userStatuses.find(status => status.translated_name.toLowerCase() === 'inactive');
            const payload = {
                first_name: user.first_name,
                last_name: user.last_name,
                branch_or_warehouse_id: user.branch_or_warehouse_id,
                status_id: userStatus === "active" ? inActiveStatusId : activeStatusId
            }

            axiosPatch(USER_STATUS_CHANGE + user.id, {...payload})
                .then(({data}) => {
                    this.toastAndReload(this.$t(userStatus === 'active' ? 'user_deactivated' : 'user_activated'), 'user-table')
                    $("#manage-role").modal('hide');
                    this.$hub.$emit(this.userList ? 'reload-branches-and-warehouses-table' : 'reload-role-table');
                })
                .catch(({response}) => this.toastException(response.data))
        },
    },
    watch: {
        roleUsersLength: {
            immediate: true,
            handler: function (users) {
                this.user_list = this.role.users ?? this.userList;
                if (!this.user_list.length) return;
            },
        }
    }
}
</script>

<style scoped>

</style>
