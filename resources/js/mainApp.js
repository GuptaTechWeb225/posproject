import './bootstrap';
import './plugins';
import Vue from 'vue';
import './core/coreApp';
import store from './store/Index'
import './common/Translator'
import './common/Helper/helpers'
import './common/commonComponent'
import './tenant/TenantComponent-CP'
import './tenant/TenantComponent-CPB'


const app = new Vue({
    store,
    strict: true,
    el: '#app',
});
