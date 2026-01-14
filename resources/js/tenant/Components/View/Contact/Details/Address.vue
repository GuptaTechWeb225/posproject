<template>
    <div id="address">
        <div class="row">
            <div class="col-lg-9">
                <div v-for="address in addresses" class="row">
                    <div class="col-md-3">
                        <div class="media mb-4 mb-xl-0">
                            <div class="align-self-center">
                                <app-icon name="home"/>
                            </div>
                            <div class="media-body align-self-center ml-3">
                                <p class="mb-0"> {{ address.name }} </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <p>
                            {{ fullAddress(address) }}
                        </p>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex justify-content-center">
                            <default-action
                                :key="`default`"
                                :actions="actions"
                                :row-data="address"
                                :unique-key="address.id"
                                @action="triggerAction"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="row mt-3">
                    <div class="col-md-3">
                        <div class="media mb-4 mb-xl-0">
                            <div class="align-self-center">
                                <app-icon name="home"/>
                            </div>
                            <div class="media-body align-self-center ml-3">
                                <p class="mb-0">{{ $t('address_title') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <p> {{ $t('not_added_yet') }} </p>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex justify-content-center">
                            <button class="btn btn-primary" @click="addAddressModal">
                                {{ this.$t('add') }}
                            </button>
                            <app-address-modal
                                v-if="isModalActive"
                                v-model="isModalActive"
                                :user-id="props"
                                :selectedUrl="url"
                                :user-type="userType"
                                @close="isModalActive = false"
                                @reload="getAddress"
                            />
                            <app-confirmation-modal
                                v-if="confirmationModalActive"
                                :firstButtonName="$t('yes')"
                                icon="trash-2"
                                modal-class="warning"
                                modal-id="app-confirmation-modal"
                                @cancelled="cancelled"
                                @confirmed="deleteAddress"
                                @reload="getAddress"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import HelperMixin from "../../../../../common/Mixin/Global/HelperMixin";
import DefaultAction from "../../../../../core/components/datatable/DefaultAction";
import AddressModal from "./AddressCreateEditModal";
import {ADDRESS, CUSTOMER_ADDRESS} from "../../../../Config/ApiUrl-CPB";
import {axiosDelete, axiosGet} from "../../../../../common/Helper/AxiosHelper";

export default {
    name: "Address",
    components: {
        DefaultAction,
        AddressModal
    },
    props: {
        props: {},
    },
    mixins: [HelperMixin],
    data() {
        return {
            isModalActive: false,
            addresses: '',
            url: '',
            customerData: '',
            userType:'customer',
            deleteUrl: ADDRESS,
            confirmationModalActive: false,
            actions: [
                {
                    title: this.$t('edit'),
                    icon: 'edit',
                    type: 'edit',
                },
                {
                    title: this.$t('delete'),
                    icon: 'trash-2',
                    type: 'delete',
                }
            ],
        }
    },

    created() {

        axiosGet(CUSTOMER_ADDRESS + '/' + this.props).then((response) => {
            this.addresses = response.data;
        }).catch((error) => {
            this.$toastr.e(error);
        });
    },

    methods: {
        getAddress() {
            axiosGet(CUSTOMER_ADDRESS + '/' + this.props).then((response) => {
                this.addresses = response.data;
            }).catch((error) => {
                this.$toastr.e(error);
            });
        },
        fullAddress(address) {

            return `${address.details ? address.details + ', ' : ''}
            ${address.area ? address.area + ', ' : ''}
            ${address.city ? address.city + ', ' : ''}
            ${address.country ? address.country + ' -' : ''}
            ${address.zip_code ? address.zip_code : ''}`;
        },
        addAddressModal() {
            this.isModalActive = true;
            this.url = '';
        },

        cancelled() {
            this.confirmationModalActive = false;
            this.deleteUrl = '';
        },
        triggerAction(row, action) {

            if (action.type === 'edit') {
                this.url = `${ADDRESS}/${row.id}`;
                this.isModalActive = true;
                this.status = row.status;
            } else if (action.type === 'delete') {
                this.confirmationModalActive = true;
                this.deleteUrl = `${ADDRESS}/${row.id}`;
            } else {
                this.getAction(row, action, active)
            }
        },
        deleteAddress() {
            axiosDelete(this.deleteUrl).then(({data}) => {
                this.cancelled();
                this.toastAndReload(data.message, 'all-branches-table');
                this.getAddress();
            });
        },
    }
}
</script>