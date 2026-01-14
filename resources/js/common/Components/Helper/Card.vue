<template>
    <div class="card card-with-shadow border-0 p-primary tenant-preview-card">
        <img :src="data.logo || ''"
             class="img-fluid d-inline-block mx-auto rounded-circle mb-2"
             width="50"
             height="50"
             alt="">
        <p class="mb-2">{{ data.name }}</p>
        <p class="mb-4 font-size-80 text-muted">{{ data.admin_name }}</p>
        <div class="d-flex align-items-center justify-content-center tenant-status mb-2">
            <span :class="`badge badge-${$optional(data.status, 'class')} badge-lg badge-pill mr-2`">{{ $optional(data.status, 'translated_name') }}</span>
        </div>
        <div class="mb-4">
            <p class="mb-2">
                <span class="text-muted">{{ $t('short_name') }}:</span> {{ data.short_name }}
            </p>
            <p class="mb-0">
                <span class="text-muted">{{ $t('group') }}:</span> {{ data.group_name }}
            </p>
        </div>
        <a :href="dashboardUrl" class="btn btn-transparent-primary">{{ $t('go_to_destination', {destination: $t('dashboard')}) }}</a>
    </div>
</template>

<script>
import {urlGenerator} from "../../Helper/AxiosHelper";

export default {
    name: "Card",
    props: {
        data: {
            type: Object,
            required: false,
            default: function () {
                return {
                    name: 'Pathao BD',
                    logo: {

                    },
                    admin_name: 'Burnet Ali',
                    status: {
                        id: 10,
                        name: 'status_active',
                        class: 'success',
                        translated_name: 'Active'
                    },
                    short_name: 'pathao-bd',
                    group_name: 'Multinational',
                    dashboard_url: false
                }
            }
        }
    },
    computed: {
        dashboardUrl() {
            return this.data.dashboard_url || urlGenerator(`t/${this.data.short_name}/dashboard`)
        },
    },
}
</script>