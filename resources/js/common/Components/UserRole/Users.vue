<template>
    <div class="card card-with-shadow border-0 pb-primary">
        <div class="card-header d-flex align-items-center p-primary primary-card-color">
            <h5 class="card-title d-inline-block mb-0">{{ $t('users') }}</h5>
            <app-search @input="getSearchValue($event)"/>
        </div>
        <div class="p-primary d-flex align-items-center primary-card-color">
            <ul class="nav tab-filter-menu justify-content-flex-end">
                <li class="nav-item" v-for="(item, index) in userStatuses" :key="index">
                    <a href="#"
                       class="nav-link py-0 font-size-default"
                       :class="[value === item.id ? 'active' : index === 0 && value === '' ? 'active': '']"
                       @click="filterUser(item.id)">
                        {{ item.translated_name }}
                    </a>
                </li>
            </ul>
        </div>
        <hr class="mx-primary my-0">
        <div class="card-body p-0">
            <app-table
                id="user-table"
                :options="options"
                @action="triggerActions"
                :filtered-data="filteredData"
                :search="search"
            />

            <app-change-role-modal
                :alias="alias"
                v-if="isChangeRoleModalActive"
                v-model="isChangeRoleModalActive"
                :user="user"
                :roleList="roleList"
            />

            <app-user-modal
                :alias="alias"
                v-if="isUserModalActive"
                v-model="isUserModalActive"
                :selected-url="selectedUrl"
            />

            <app-confirmation-modal
                v-if="confirmationModalActive"
                icon="trash-2"
                modal-id="app-confirmation-modal"
                @confirmed="confirmed('user-table', 'role-table')"
                @cancelled="cancelled"
            />

            <app-confirmation-modal
                v-if="isUserCancelInvitationModalActive"
                :message="$t('you_are_going_to_cancel_an_invitation')"
                modal-id="app-confirmation-modal"
                @confirmed="cancelUserInvitation('user-table', 'role-table')"
                @cancelled="isUserCancelInvitationModalActive = false"
            />
        </div>
    </div>
</template>

<script>
import UsersMixin from "../../Mixin/UsersMixin";
import {axiosGet, axiosPatch, axiosPost} from "../../Helper/AxiosHelper";
import {APP_USERS, STATUSES, TENANT_USERS} from "../../Config/apiUrl";

export default {
    name: "Users",
    components: {
        'app-change-role-modal': require('./UI/ChangeRoleModal').default
    },
    mixins: [UsersMixin],
    props: ['roleList', 'alias'],
    data() {
        return {
            isChangeRoleModalActive: false,
            isUserModalActive: false,
            isUserCancelInvitationModalActive: false,
            selectedUrl: '',
            user: '',
            filteredData: {},
            search: '',
            value: '',
            hiddenStatus: [],
            cancel_invitation_url: ''
        }
    },
    methods: {
        triggerActions(row, action, active) {
            this.selectedUrl = `${this.getBaseURL}/${row.id}`;
            if (action.type === 'action') {
                this.makeAction(row, action.alias)
            }

            if (action.name === 'cancel') {
                this.isUserCancelInvitationModalActive = true;
                this.cancel_invitation_url = `${action.url}/${row.id}`;
            }else if (action.name === 'edit') {
                this.isUserModalActive = true;
            } else if (action.name === 'change_role') {
                this.isChangeRoleModalActive = true
                this.user = row.id
            } else {
                this.getAction(row, action, active)
            }
        },
        makeAction(row, alias) {
            const user = {
                first_name: row.first_name,
                last_name: row.last_name,
                branch_or_warehouse_id: row.branch_or_warehouse_id,
                status_id: this.userStatuses.find(status => status.name === alias).id
            }
            axiosPatch(this.selectedUrl, {...user})
                .then(({data}) => {
                    this.toastAndReload(data.message, 'user-table')
                    this.$hub.$emit('reload-role-table');
                })
                .catch(({response}) => this.toastException(response.data))
        },
        filterUser(id) {
            this.value = id;
            this.filteredData['status-id'] = id;
            setTimeout(() => {
                this.$hub.$emit('reload-user-table');
            });
        },
        getSearchValue(event) {
            this.search = event;
            setTimeout(() => {
                this.$hub.$emit('reload-user-table');
            });
        },
        userStatusSearch() {
            axiosGet(`${STATUSES}?type=user`).then(res => {
                this.userStatuses = [
                    {id: '', translated_name: this.$allLabel('user')}
                ];
                this.userStatuses = this.userStatuses.concat(res.data)
            })
        },
        cancelUserInvitation(user, role = null) {
            axiosPost(this.cancel_invitation_url)
                .then(response => this.toastAndReload(response.data.message, user))
                .catch(error => this.toastException(error.response.data))
                .finally(() => {
                    this.cancel_invitation_url = null;
                    this.$hub.$emit(`reload-${role}`)
                })
        }
    },
    computed: {
        getBaseURL() {
            return {
                tenant: TENANT_USERS,
                app: APP_USERS,
            }[this.alias];
        },
    },
    mounted() {
        $('#invite-open-modal').on('hidden.bs.modal', () => {
            this.isInviterOpenModalActive = false;
        });
        this.userStatusSearch();
    },
    watch: {
        isUserOpenModalActive: function (value) {
            if (!value)
                this.selectedUrl = '';
        }
    }

}
</script>
