<template>
    <form ref="form" data-url='admin/auth/users/change-settings' v-if="!preloader">
        <app-form-group
            page="page"
            :label="$t('first_name')"
            type="text"
            id="input-text-first-name"
            :placeholder="$placeholder('first_name', '')"
            v-model="userProfileInfo.first_name"
            :error-message="$errorMessage(errors, 'first_name')"
        />

        <app-form-group
            page="page"
            :label="$t('last_name')"
            type="text"
            id="input-text-last-name"
            :placeholder="$placeholder('last_name', '')"
            v-model="userProfileInfo.last_name"
            :error-message="$errorMessage(errors, 'last_name')"
        />

        <app-form-group
            page="page"
            :label="$t('email')"
            type="email"
            id="input-text-email"
            :placeholder="$placeholder('email', '')"
            v-model="userProfileInfo.email"
            :error-message="$errorMessage(errors, 'email')"
        />

        <app-form-group
            v-if="userProfileInfo.profile"
            page="page"
            :label="$t('gender')"
            type="radio"
            :list="[
                {id:'male',value: $t('male')},
                {id:'female', value:  $t('female')}
            ]"
            v-model="userProfileInfo.profile.gender"
            :error-message="$errorMessage(errors, 'gender')"

        />

        <app-form-group
            v-if="userProfileInfo.profile"
            page="page"
            :label="$fieldTitle('contact', 'number')"
            type="number"
            id="input-text-contact"
            :placeholder="$placeholder('contact', 'number')"
            v-model="userProfileInfo.profile.contact"
            :error-message="$errorMessage(errors, 'contact')"
        />

        <app-form-group
            v-if="userProfileInfo.profile"
            page="page"
            :label="$t('address')"
            type="text"
            id="input-text-address"
            :placeholder="$placeholder('address', '')"
            v-model="userProfileInfo.profile.address"
            :error-message="$errorMessage(errors, 'address')"
        />

        <app-form-group
            v-if="userProfileInfo.profile"
            page="page"
            :label="$t('date_of_birth')"
            type="date"
            v-model="userProfileInfo.profile.date_of_birth"
            :placeholder="$placeholder('date_of_birth', '')"
            :error-message="$errorMessage(errors, 'date_of_birth')"
        />

        <div :class="`form-group mt-5 mb-0 ${ $isRtl ? 'text-left' : '' }`">
            <app-submit-button @click="submitData" :title="$t('save')" :loading="loading"/>
        </div>
    </form>
    <app-pre-loader class="p-primary" v-else />
</template>

<script>
    import moment from 'moment'
    import FormHelperMixins from "../../../Mixin/Global/FormHelperMixins";

    export default {
        name: "ProfilePersonalInfo",
        mixins: [FormHelperMixins],
        data() {
            return {
                userProfileInfo: {}
            }
        },
        methods: {
            submitData() {
                let profile = this.userProfileInfo;
                this.loading = true;
                profile.gender = this.userProfileInfo.profile.gender;
                profile.contact = this.userProfileInfo.profile.contact;
                profile.address = this.userProfileInfo.profile.address;
                profile.date_of_birth = this.userProfileInfo.profile.date_of_birth ? moment(this.userProfileInfo.profile.date_of_birth).format('YYYY-MM-DD') : '';
                this.save(profile);
            },
            afterSuccess(response) {
                this.loading = false;
                this.$toastr.s('', response.data.message);
                this.scrollToTop(false)
                setTimeout(() => location.reload())
            },

            cancelUser() {
                location.reload();
            },

        },

        computed: {
            userInfo() {
                return this.$store.getters.getUserInformation
            }
        },
        mounted() {
            this.$store.dispatch('getUserInformation');
            this.preloader = true
        },

        watch: {
            userInfo: {
                handler: function (user) {
                    if (user) this.preloader = false
                    this.userProfileInfo = {
                        ...user,
                        profile: {
                            ...user.profile,
                            date_of_birth: user.profile && user.profile.date_of_birth ? new Date(user.profile.date_of_birth) : ''
                        }
                    };
                },
                deep: true
            }
        }

    }
</script>


