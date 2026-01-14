<template>
    <div class="login-form d-flex align-items-center">
        <form class="sign-in-sign-up-form w-100" ref="form" data-url="admin/users/login">
            <div class="text-center mb-4">
                <img :src="logoUrl" alt="" class="img-fluid logo">
            </div>
            <div class="form-row">
                <div class="form-group col-12">
                    <h6 class="text-center mb-0">{{ $t('hi', {object: $t('there')}) }}!</h6>
                    <label class="text-center d-block">{{ $t('login_to_your_dashboard') }}</label>
                </div>
            </div>

            <div class="form-row" v-if="marketPlaceVersion">
                <div class="form-group col-12">
                    <label>{{ $t("log_in_as") }}</label>
                    <app-input
                        type="select"
                        id="input-select"
                        :placeholder="$t('select_a_role')"
                        v-model="userRole"
                        @change="setUserInfo"
                        :list="userTypeList"
                    />
                </div>
            </div>

            <app-form-group
                :label="$t('email')"
                type="email"
                v-model="formData.email"
                :placeholder="$placeholder('your', 'email')"
                :error-message="$errorMessage(errors, 'email')"/>

            <app-form-group
                :label="$t('password')"
                type="password"
                v-model="formData.password"
                :placeholder="$placeholder('your', 'password')"
                :error-message="$errorMessage(errors, 'password')"
                :show-password="true"
            />

            <div class="form-row">
                <div class="form-group col-12">
                    <app-submit-button
                        btn-class="d-inline-flex btn-block text-center"
                        :label="$t('login')"
                        :loading="loading"
                        @click="submitData"
                    />
                </div>
            </div>

            <div class="form-row">
                <div class="col-6">
                    <app-input
                        class="mb-primary"
                        type="single-checkbox"
                        :list-value-field="$t('remember_me')"
                        v-model="formData.remember_me"
                    />
                </div>

                <div class="col-6 text-right">
                    <a :href="urlGenerator('/users/password-reset')" class="bluish-text">
                        <i data-feather="lock" class="pr-2"/>{{ $t('forget', {subject: $t('password')}) }}?
                    </a>
                </div>
            </div>

            <div class="form-group">
                <div class="col-12">
                    <p class="text-center mt-5 footer-copy">
                        {{ $t('copyright') }} @ {{ new Date().getFullYear() }} {{ $t('by') }} {{ appName ?? '-' }}
                    </p>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import ThemeMixin from "../../Mixin/Global/ThemeMixin";
import FormHelperMixins from "../../Mixin/Global/FormHelperMixins";

export default {
    name: "Login",
    mixins: [ThemeMixin, FormHelperMixins],
    props: {
        logoUrl: {
          required: false
        },
          appName: {
            required: false
          },
        marketPlaceVersion: {
            default: false,
        },
    },
    mounted() {
        const itemsToRemoveFromLocalStorageBeforeLogin = ['cartState', 'cash_register_id', 'currentBranchWahrehouseId', 'currentBranchWahrehouseName'];
        for (const itemInLocalStorage of itemsToRemoveFromLocalStorageBeforeLogin) localStorage.removeItem(itemInLocalStorage);
    },
    data() {
        return {
            formData: {
                email: this.email,
                password: this.password,
            },
            userRole: 0,
            userTypeList: [
                {
                    id: 0,
                    value: "Select a role",
                    email: null,
                    password: null,
                },
                {
                    id: 1,
                    value: "Admin",
                    email: "admin@demo.com",
                    password: 123456,
                },
                {
                    id: 2,
                    value: "Manager",
                    email: "manager@demo.com",
                    password: 123456,
                },
                {
                    id: 3,
                    value: "Warehouse Manager",
                    email: "warehousemanager@demo.com",
                    password: 123456,
                },
                {
                    id: 4,
                    value: "Branch manager",
                    email: "branchmanager@demo.com",
                    password: 123456,
                },
                {
                    id: 5,
                    value: "Cashier",
                    email: "cashier@demo.com",
                    password: 123456,
                }
            ]

        }
    },

    methods: {
        setUserInfo() {
            this.userTypeList.map((user) => {
                if (user.id == this.userRole) {
                    this.formData.email = user.email;
                    this.formData.password = user.password;
                }
            });
        },
        submitData() {
            this.message = '';
            this.loading = true;
            this.save(this.formData);
        },

        afterSuccess({data}) {
            window.location.href = data;
        },
        afterFinalResponse() {
        },
    },
}
</script>
