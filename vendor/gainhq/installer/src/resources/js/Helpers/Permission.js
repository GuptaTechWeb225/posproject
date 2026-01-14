export default class Permission {
    getPermissions() {
        return window.localStorage.getItem('permissions');
    }

    can(ability) {
        if (this.permissions().is_app_admin) {
            return true
        }
        return Boolean(this.permissions()[ability]);
    }
    
    isAppAdmin() {
        return this.permissions().is_app_admin
    }

    permissions() {
        return JSON.parse(this.getPermissions());
    }
}
