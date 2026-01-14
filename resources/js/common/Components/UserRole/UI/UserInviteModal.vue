<template>
  <modal id="user-invite"
         v-model="showModal"
         :title="$fieldTitle('invite', 'user', true)"
         @submit="submit"
         :btn-label="$t('invite')"
         :scrollable="false"
         :loading="loading"
         :preloader="preloader"
  >
    <form ref="form" :data-url='submitURL' v-if="isMailSettingExist">
      <app-form-group
          type="email"
          :label="$t('email')"
          :placeholder="$placeholder('email')"
          v-model="formData.email"
          :required="true"
          :error-message="$errorMessage(errors, 'email')"
      />

      <app-form-group
            :label="$t('branch_or_warehouse')"
            class="margin-right-8"
            type="search-and-select"
            :placeholder="$placeholder('branch_or_warehouse')"
            :options="branchesOrWarehousesOptions"
            v-model="formData.branch_or_warehouse_id"
            :error-message="$errorMessage(errors, 'branch_or_warehouse_id')"
        />

      <app-form-group
          :label="$t('choose_a_role')"
          class="margin-right-8"
          type="search-and-select"
          :placeholder="$placeholder('choose_a_role')"
          :options="roleLists"
          v-model="role"
          :key="roleInputKey"
          :error-message="$errorMessage(errors, 'roles')"
      />
    </form>

    <app-note v-else
              :title="$t('no_delivery_settings_found')"
              :notes="[$t('no_delivery_settings_warning', {
                      location: `<a href='${settingsLocation}'>${$t('here')}</a>`
                  })]"
              content-type="html"
    />
    <template v-if="!isMailSettingExist" slot="footer">
      <button type="button"
              class="btn btn-secondary"
              data-dismiss="modal">
        {{ $t('close') }}
      </button>
    </template>
  </modal>
</template>

<script>
import FormHelperMixins from "../../../Mixin/Global/FormHelperMixins";
import ModalMixin from "../../../Mixin/Global/ModalMixin";
import {
  MAIL_CHECK_URL,
  TENANT_USER_INVITE,
  DELIVERY_SETTINGS_FRONT_END,
  TENANT_MAIL_CHECK_URL,
  TENANT_DELIVERY_SETTINGS_FRONT_END,
  TENANT_ROLES,
} from
      "../../../Config/apiUrl";

import {axiosGet, urlGenerator} from "../../../Helper/AxiosHelper";
import {SELECTABLE_BRANCHES_OR_WAREHOUSES} from "../../../../tenant/Config/ApiUrl-CP";

export default {
  name: "UserInviteModal",
  mixins: [FormHelperMixins, ModalMixin],
  props: ['roles', 'alias'],
  data() {
    return {
      formData: {
        roles: [],
        branch_or_warehouse_id: ''
      },
        roleInputKey: 0,
      role: '',
      isMailSettingExist: false,
      branchesOrWarehousesOptions: {
        url: urlGenerator(SELECTABLE_BRANCHES_OR_WAREHOUSES),
        query_name: "search",
        per_page: 10,
        loader: "app-pre-loader",
        modifire: ({id, name: value}) => ({id, value}),
        prefetch: !Boolean(this.selectedUrl),
      },
      settingsLocation: urlGenerator(this.alias === 'tenant' ? TENANT_DELIVERY_SETTINGS_FRONT_END : DELIVERY_SETTINGS_FRONT_END)
    }
  },
  methods: {
    submit() {
      this.loading = true;
      this.formData.roles = [this.role];
      this.save(this.formData);
    },

    afterSuccess({data}) {
      this.loading = false;
      this.toastAndReload(data.message, 'user-table');
      $("#user-invite").modal('hide')
    },

    afterSuccessFromGetEditData({data}) {
      this.formData = data;
    },

    checkMailSettings() {
      this.preloader = true;
      const url = this.alias === 'tenant' ? TENANT_MAIL_CHECK_URL : MAIL_CHECK_URL
      axiosGet(url).then(response => {
        this.isMailSettingExist = !!response.data;
      }).finally(() => {
        this.preloader = false;
      });
    },
  },
  computed: {
    submitURL() {
      return this.alias === 'tenant' ? TENANT_USER_INVITE : `admin/auth/users/invite-user`;
    },

  roleLists() {
    return {
            url: urlGenerator(TENANT_ROLES),
              query_name: "search",
            params: {
              branch_or_warehouse_id: this.formData.branch_or_warehouse_id
            },
            per_page: 10,
            loader: "app-pre-loader",
            modifire: ({id, name: value}) => ({id, value}),
            prefetch: false,
        }
    },
  },

  created() {
    this.checkMailSettings();
  },
    watch: {
      'formData.branch_or_warehouse_id'() {
          this.roleInputKey++;
          this.role = '';
      }
    }


}
</script>

