import Vue from 'vue'
import Vuex from 'vuex'
import {formatted_date, formatted_time} from '../common/Helper/Support/DateTimeHelper'
//Import you module here
import Support from "./modules/Common/Support";
import Notifications from "./modules/Common/Notifications";
import Notification from "./modules/Common/Settings/Notification";
import User from "./modules/Common/User";
import NotificationEvent from "./modules/Common/Settings/NotificationEvent";
import Settings from "./modules/Common/Settings/Settings";
import Role from "./modules/Common/Role";
import CustomField from "./modules/Common/Settings/CustomField/CustomField";
import Profile from "./modules/Common/Profile";
import Roles from "./Tenant/Roles";
import TenantSettings from "./Tenant/TenantSettings";
import CreatedBy from "./modules/Tenant/CreatedBy";
import Customer from "./Tenant/Customer";
import Tags from "./modules/Tenant/Tags";
import Variations from "./modules/Tenant/VariantAttributes";
import ExpenseArea from "./modules/Tenant/ExpenseArea";
import Branch from "./modules/Tenant/Branch";
import Suppliers from "./modules/Tenant/SupplierSeletable";
import ReceivedBy from "./modules/Tenant/ReceivedBy";
import LotStatuses from "./modules/LotStatuses";
import SalesView from "./modules/SalesView";
import Auth from "./modules/Auth/Auth.js";
import Stock from "./modules/Inventory/Stock";

Vue.use(Vuex);

export default new Vuex.Store({

    state: {
        loading: false,
        settings: {
            dateFormat: formatted_date(),
            timeFormat: parseInt(formatted_time())
        },
        theme: {
            darkMode: false
        }

    },

    getters: {},

    actions: {},

    mutations: {
        SET_LOADER(state) {
            state.loading = !state.loading;
        }
    },

    modules: {
        support: Support,
        notifications: Notifications,
        user: User,
        notification: Notification,
        notification_event: NotificationEvent,
        settings: Settings,
        role: Role,
        custom_field: CustomField,
        profile: Profile,
        roles: Roles,
        tenant_settings: TenantSettings,
        customer:Customer,
        CreatedBy: CreatedBy,
        tags: Tags,
        variations: Variations,
        expense_area: ExpenseArea,
        branch: Branch,
        suppliers: Suppliers,
        receivedBy: ReceivedBy,
        lotStatuses: LotStatuses,
        salesView: SalesView,
        auth: Auth,
        stock: Stock
    }

});
