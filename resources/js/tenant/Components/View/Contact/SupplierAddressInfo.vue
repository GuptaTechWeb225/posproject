<template>
    <div id="note-dropdown"
         class="dropdown keep-inside-clicks-open dropdown-note-editor d-inline-block">
        <button type="button"
                class="btn p-0 mb-1 primary-text-color"
                data-toggle="dropdown">
            <app-icon name="file-text" class="size-18"/>
        </button>
        <div class="dropdown-menu p-primary mt-1">
            <div>
                <h5 class="text-secondary">{{ $t('supplier_address') }}</h5>
                <div class="note note-warning custom-scrollbar p-4 mb-5" v-for="(address_value, i) in value" :key="i">
                    <p class="m-0" v-if="address_value.name"><label class="font-weight-bold">{{$t('name')}}:</label> {{ address_value.name }}</p>
                    <p class="m-0" v-if="address_value.country?.name"><label class="font-weight-bold">{{$t('country')}}:</label> {{ address_value.country?.name ?? '-' }}</p>
                    <p class="m-0" v-if="address_value.city"><label class="font-weight-bold">{{$t('city')}}:</label> {{ address_value.city }}</p>
                    <p class="m-0" v-if="address_value.area"><label class="font-weight-bold">{{$t('area')}}:</label> {{ address_value.area }}</p>
                    <p class="m-0" v-if="address_value.zip_code"><label class="font-weight-bold mb-0">{{$t('zip_code')}}:</label> {{ address_value.zip_code }}</p>
                    <p class="m-0 pt-2" v-if="address_value.details"><label class="font-weight-bold mb-0">{{$t('details')}}:</label> {{ address_value.details }}</p>
                </div>

                <div class="text-right">
                    <a href="#"
                       class="btn btn-secondary"
                       @click.prevent="closeDropDown">
                        {{ $t('close') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "SupplierAddressInfo",
    props: ['rowData', 'value'],
    methods: {
        closeDropDown() {
            $(".dropdown-menu").removeClass('show')
        },
    }
}
</script>

<style scoped lang="scss">
.dropdown {
    .dropdown-menu {
        width: 350px;

        .note {
            overflow-y: auto;
            max-height: 200px;
        }
    }
}
</style>
