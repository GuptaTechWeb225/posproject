<template>
    <div class="dropdown dropdown-with-animation d-inline-block btn-dropdown btn-tenant-dropdown">
        <button type="button"
                class="btn"
                v-if="tenants.length"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false">
            <span class="d-none d-md-inline-block mr-2">{{ currentTenant.name }}</span>
            <app-icon name="chevron-down" class="size-15 primary-text-color"/>
        </button>
        <div class="dropdown-menu p-0">
            <a class="dropdown-item dropdown-title py-4" href="#">
                <app-icon name="arrow-left" class="primary-text-color size-18 mr-2"/>
                {{ $t('back_to_multi_tenant') }}
            </a>
            <div class="dropdown-divider" v-if="tenants.length"/>
            <div class="tenant-items" v-if="tenants.length">
                <app-pre-loader v-if="loading"/>
                <a v-else
                   href="#"
                   class="dropdown-item py-4 d-flex align-items-center"
                   v-for="tenant in tenants">
                    <img v-if="tenant.img"
                         src=""
                         class="rounded-circle mr-3"
                         width="20"
                         alt="Tenant name">
                    <span :class="{'primary-text-color': tenant.id === currentTenant.id}">
                        {{ tenant.name }}
                    </span>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "TenantManager",
    data() {
        return {
            tenants: [
                {id: 1, name: 'Default tenant'},
                {id: 2, name: 'Grameenphone Ltd.'},
                {id: 3, name: 'Robi Ltd.'},
                {id: 4, name: 'Bkash Ltd, Bangladesh'},
                {id: 5, name: 'Pathao, Bangladesh'},
            ],
            loading: false,
        }
    }
}
</script>