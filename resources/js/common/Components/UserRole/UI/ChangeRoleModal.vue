<template>
    <modal
        id="app-change-role-modal"
        v-model="showModal"
        :title="$fieldTitle('manage', 'role', true)"
        @submit="submitData"
        :loading="loading"
        :preloader="preloader"
        :scrollable="false"
    >
        <form ref="form"
              :data-url='`${getSubmitURL}/${user}`'
              @submit.prevent="submitData"
        >
            <div class="form-group">
                <app-input
                    type="multi-select"
                    v-model="formData.roles"
                    :list="roleList"
                    listValueField="name"
                    :isAnimatedDropdown="true"
                />
                <app-message type="error" :message="$errorMessage(errors, 'roles')"/>
            </div>
        </form>
    </modal>
</template>

<script>
import ModalMixin from "../../../Mixin/Global/ModalMixin";
import FormHelperMixins from "../../../Mixin/Global/FormHelperMixins";
import {TENANT_ATTACH_ROLES, TENANT_USERS} from "../../../Config/apiUrl";

export default {
    name: "ChangeRoleModal",
    mixins: [ModalMixin, FormHelperMixins],
    props: ['roleList', 'user', 'alias'],
    data() {
        return {
            formData: {
                roles: []
            }
        }
    },
    methods: {
        afterSuccess({data}) {
            this.formData = {}
            $('#app-change-role-modal').modal('hide');
            this.$emit('input', false)
            this.toastAndReload(data.message, 'user-table')
            this.$hub.$emit('reload-role-table')
        },

        afterSuccessFromGetEditData({data}) {
            this.formData.roles = this.collection(data.roles).pluck();
            this.preloader = false;
        },
    },
    computed: {
        getSubmitURL() {
            return {
                tenant: TENANT_ATTACH_ROLES,
                app: `admin/auth/users/attach-roles`,
            }[this.alias];
        },
        getBaseURL() {
            return {
                tenant: TENANT_USERS,
                app: `admin/auth/users`
            }[this.alias];
        }
    },
    mounted() {
        if (this.user) {
            this.getEditData(`${this.getBaseURL}/${this.user}`);
        }
    }
}
</script>
