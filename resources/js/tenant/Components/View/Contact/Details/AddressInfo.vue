<template>
    <div style="width: 15.625rem">

        <div v-if="value.length">
            <div>
                <p class="mb-2"
                   v-if=" index < addressShow"
                   v-for="(item, index) in value">
                    <span>{{ fullAddress(item) }}</span>
                </p>
            </div>

            <div
                v-if="addressShow < value.length || value.length > addressShow">
                <a :href="addressUrl"
                   class="ml-2">
                    + {{ value.length - 1 }} {{ $t('more') }}
                </a>
            </div>
        </div>
        <div v-else>-</div>

    </div>
</template>

<script>

import {CUSTOMER_DETAILS} from '../../../../Config/ApiUrl-CPB';

export default {
    name: "AddressInfo",
    props: ['rowData', 'tableId', 'value'],
    data() {
        return {
            addressShow: 1,
            CUSTOMER_DETAILS,
            addressUrl: CUSTOMER_DETAILS + this.rowData.id + '?tab=Address'
        }
    },
    methods: {

        fullAddress(item) {
            return `${item.details ? item.details + ', ' : ''}
            ${item.area ? item.area + ', ' : ''}
            ${item.city ? item.city + ', ' : ''}
            ${item.country ? item.country.name + ' -' : ''}
            ${item.zip_code ? item.zip_code : ''}`;
        }
    }
}
</script>
