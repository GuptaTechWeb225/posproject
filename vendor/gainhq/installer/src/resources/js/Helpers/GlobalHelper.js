import './Translator';
import Vue from "vue";
import Permission from "./Permission";
import * as PermissionString from "./../Config/PermissionString";

Vue.prototype.$have = ability => {
    if(!PermissionString[ability]) {
        console.warn(`Please add ${ability} in PermissionString.js file`);
        return (new Permission()).can(ability)
    }
    return (new Permission()).can(PermissionString[ability])
};
Vue.prototype.$isSuperAdmin = () => (new Permission()).isAppAdmin();
Vue.prototype.$isAppAdmin = () => (new Permission()).isAppAdmin();