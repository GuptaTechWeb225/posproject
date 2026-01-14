<template>
    <app-navbar :user="user"
                :profile-data="profileData"
                :selected-language="userLanguage"
                :language-data="languages"
                :notification-data="notifications.data"
                :logo-url="logoIconSrc"
                :allNotificationUrl="''"
                :show-identifier="!!notifications.total_unread"
                @clicked="readNotification">
        <template slot="left-option">
            <button class="navbar-toggler shadow-none pl-0">
                <app-branch-manager :key="getBranchManagerKey"/>
            </button>

            <button class="navbar-toggler pos-menu-btn" @click="handlePosMenuBtnClick" v-if="!currentlyOnSalesView">
                <div class="dropdown dropdown-with-animation d-inline-block btn-dropdown btn-tenant-dropdown">
                    <button type="button" class="btn" id="pos-btn">
                        <span class="d-none d-md-inline-block mr-2">{{ $t('pos_menu') }}</span>
                        <app-icon name="shopping-cart"/>
                    </button>
                </div>
            </button>
        </template>
    </app-navbar>
</template>

<script>
import CoreLibrary from "../../../core/helpers/CoreLibrary";
import {mapActions, mapState, mapGetters, mapMutations} from 'vuex'
import {axiosPost, urlGenerator} from "../../Helper/AxiosHelper";


export default {
    name: "TopBar",
    extends: CoreLibrary,
    props: {
        profileData: {},
        logoIconSrc: {
            type: String,
            default: '/images/logo.png'
        },
    },
    data() {
        return {
            user: {},
            salesViewPathname: '/sales/view'
        }
    },
    computed: {
        ...mapGetters(['getBranchOrWarehouseId', 'getBranchManagerKey']),
        ...mapState({
            languages: state => state.support.languages
        }),
        userLanguage() {
            if (window.appLanguage) {
                return window.appLanguage.toUpperCase();
            }
        },
        notifications() {
            return this.$store.getters.getFormattedNotifications
        },
        isTenantExist() {
            return !!window.tenant && !window.tenant.is_single;
        },
        currentlyOnSalesView() {
            return window.location.pathname === this.salesViewPathname;
        }
    },
    methods: {
        ...mapActions([
            'getLanguages'
        ]),
        ...mapMutations([
            'INC_BRANCH_MANAGER_KEY'
        ]),
        handlePosMenuBtnClick() {
            window.location.replace(urlGenerator(this.salesViewPathname));
        },
        setUser() {
            this.user = {
                full_name: this.$optional(window.user, 'full_name'),
                img: this.$optional(window.user, 'profile_picture', 'full_url') || urlGenerator('/images/avatar.png'),
                status: this.$t('online'),
                role: window.user.roles && window.user.roles.length ? window.user.roles[0].name : ''
            };
        },
        readNotification(notification) {
            axiosPost(`admin/user/notifications/mark-as-read/${notification.id}`).then(({data}) => {
                if (data.data.url) {
                    window.location = data.data.url;
                }
                this.$store.dispatch('getNotifications');
            });
        }
    },
    watch: {
        getBranchOrWarehouseId() {
            this.INC_BRANCH_MANAGER_KEY();
        }
    },
    created() {
        this.getLanguages();
    },
    mounted() {
        this.setUser();
        this.$store.dispatch('getNotifications');
    }
}
</script>

<style lang="sass">
.pos-menu-btn
    transition: 150ms

    &:hover
        transform: scale(1.05)

    #pos-btn
        background-color: #3498db
        color: #ffffff
</style>
