<template>
    <modal id="role-modal"
           v-model="showModal"
           :title="this.manage ? $fieldTitle('manage', 'permissions', true) : generateModalTitle('role')"
           @submit="submitData"
           :loading="loading"
           :hide-footer="formData.id === 1 && formData.is_admin && formData.is_default"
           :preloader="preloader">
        <div v-if="formData.id !== 1 && !(formData.is_default && formData.is_admin)">
            <form ref="form"
                  :data-url='selectedUrl ? `${getSubmitURL}/${this.formData.id}` : getSubmitURL'
                  @submit.prevent="submitData">
                <template v-if="!manage">
                    <app-form-group
                        v-model="formData.name"
                        :label="$t('name')"
                        :placeholder="$placeholder('name')"
                        :error-message="$errorMessage(errors, 'name')"
                    />
                </template>
                <div class="form-group" v-if="Object.keys(data.permissions).length">
                    <app-message type="error" :message="$errorMessage(errors, 'permissions')"/>

                    <div id="accordionExample" class="accordion">
                        <app-overlay-loader v-if="!Object.keys(data.permissions).length" />
                        <div class="card" v-else v-for="(permission, index) in Object.keys(data.permissions)">
                            <div class="card-header border-0">
                                <div
                                    class="custom-checkbox-default d-block position-relative text-capitalize collapsible-link py-2 cursor-pointer"
                                    data-toggle="collapse"
                                    :data-target="`#${permission}`"
                                    aria-expanded="false"
                                    :aria-expanded="`${checkForVisibility(index, permission)? true : false}`"
                                    aria-controls="permission">
                                    <div class="customized-checkbox checkbox-default">
                                        <input type="checkbox"
                                               :name="`single-checkbox-${permission}`"
                                               :id="`single-checkbox-${permission}`"
                                               :disabled="readOnly"
                                               :value="permission"
                                               :checked="ifChecked(permission)"
                                               @input="checkGroup($event, permission)"
                                               ref="checkbox"
                                               v-if="loadChecked"
                                               @click="$event.stopPropagation()"/>
                                        <label class="mb-0"
                                               :for="`single-checkbox-${permission}`"
                                               @click="$event.stopPropagation()">
                                            {{ $t(permission) }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div :id="permission"
                                 data-parent="#accordionExample"
                                 :class="`collapse ${checkForVisibility(index, permission)? 'show' : ''}`">
                                <div class="card-body p-primary">
                                    <app-input type="checkbox"
                                               v-if="loaded"
                                               :list="data.permissions[permission]"
                                               v-model="checkedPermissions[permission]"
                                               @input="checkPermissions(permission)"
                                               list-value-field="translated_name"/>
                                               <!-- :disabled="readOnly" -->

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group" v-else>
                    <app-pre-loader/>
                </div>
            </form>
        </div>
        <div v-else>
            {{ $t('action_not_allowed') }}
        </div>
    </modal>
</template>

<script>
import FormHelperMixins from "../../../Mixin/Global/FormHelperMixins";
import ModalMixin from "../../../Mixin/Global/ModalMixin";
import {APP_ROLES, TENANT_ROLES} from "../../../Config/apiUrl";

export default {
    name: "AppRolesModal",
    components: {},
    mixins: [FormHelperMixins, ModalMixin],
    props: {
        data: {
            default: function () {
                return {
                    permissions: {},
                    types: []
                }
            }
        },
        manage: {},
        alias: {}
    },
    data() {
        return {
            permissions: [],
            checkedPermissions: {},
            brands: [],
            loaded: true,
            loadChecked: true
        }
    },
    mounted() {
        this.setFormData()
    },
    created() {
        Object.keys(this.data.permissions)
            .forEach(permission => this.checkedPermissions[permission] = []);
    },
    methods: {
        submitData() {
            this.formData.is_manager == true ? this.formData.is_manager = 1 : this.formData.is_manager = 0;
            const role = {
                ...this.formData,
                permissions: this.permissions.map(permission => {
                    return {
                        permission_id: permission
                    }
                })
            }
            this.loading = true;
            this.save(role);
        },
        afterSuccess({data}) {
            this.toastAndReload(data.message, 'role-table');
            this.$hub.$emit('rolesAffected')
            this.loading = false;
            this.closeModal()
        },
        afterSuccessFromGetEditData(response) {
            this.formData = response.data;
            this.formData.is_manager = response.data.is_default;
            this.preloader = false;
            this.permissions = this.collection(response.data.permissions).pluck('id');
            Object.keys(this.data.permissions).forEach(permission => {
                let checked = this.data.permissions[permission].filter(p => this.permissions.includes(p.id))
                this.checkedPermissions[permission] = checked.map(obj => obj.id);
            });
        },
        closeModal() {
            $('#role-modal').modal('hide');
        },
        checkGroup(event, permission) {
            this.loaded = false;
            const permissions = this.collection(this.data.permissions[permission]).pluck('id');
            if (event.target.checked) {
                this.$set(this.checkedPermissions, permission, permissions);
                this.checkPermissions(permission);
            } else {
                this.$set(this.checkedPermissions, permission, []);
                this.permissions = this.permissions.filter(p => !permissions.includes(parseInt(p)));
            }
            this.loaded = true;
        },
        checkForVisibility(index, permission) {
            return (this.checkedPermissions[permission] && this.checkedPermissions[permission].length)
        },
        checkPermissions(permission) {
            this.loadChecked = false;
            const all_permission_of_group = this.collection(this.data.permissions[permission]).pluck('id');
            const checked_permissions = this.checkedPermissions[permission].map(p => parseInt(p));
            const removable_permissions = all_permission_of_group.filter(permission => !checked_permissions.includes(permission));
            this.permissions = this.permissions.filter(permission => !removable_permissions.includes(parseInt(permission)));
            this.permissions = Array.from(new Set(this.permissions.concat(checked_permissions)));
            this.loadChecked = true;
        },
        ifChecked(permission) {
            const permissions = this.collection(this.data.permissions[permission]).pluck();
            const checked = this.checkedPermissions[permission];
            return permissions.length === checked.length;
        },
        setFormData() {
            const type = this.data.types.find(type => type.alias === this.alias);
            this.formData.type_id = type.id;
            this.formData.tenant_id = null;
            if (this.alias === 'tenant') {
                this.formData.tenant_id = window.tenant.id
            }
        }
    },
    computed: {
        getSubmitURL() {
            return {
                tenant: TENANT_ROLES,
                app: APP_ROLES,
            }[this.alias];
        },
        readOnly() {
            return this.formData.id <= 5 // disabling the checkbox inputs for default roles
        }
    }
}
</script>
