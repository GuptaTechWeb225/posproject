<template>
    <div class="row justify-content-center py-5">
        <div class="col-lg-11">

            <div class="d-flex mb-primary">
                <div>
                    <app-icon name="layers" class="text-muted size-40"/>
                </div>
                <div class="ml-3">
                    <h6>{{ data.name }} {{ $t('has') }} {{ counters.length }} {{ $t('counters') }}</h6>
                    <h6 class="text-muted">
                        {{ $t('open') }} {{ counterCount.open }},
                        {{ $t('close') }} {{ counterCount.close }}</h6>
                </div>
            </div>
            <div v-for="(counter, index) in counters" class="row mb-3">
                <div class="col-md-12 col-lg-3">
                    <div class="d-flex justify-content-between">
                        <div class="text-warning mr-5 d-flex">
                            <app-icon name="corner-down-right"/>
                            <h6>{{ counter.name }}</h6>
                        </div>
                        <div>
                            <p class="text-muted mb-0"> {{ $t('sales_person') }}: </p>
                            <div v-if="counter.sales_people > 0">
                                <p v-for="sales_men in counter.sales_people" class="mb-0">
                                    {{ sales_men.sales_person.first_name }}</p>
                            </div>
                            <div v-else>
                                <p> {{$t('not_added_yet')}} </p>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-3">
                    <div>
                        <p class=" mb-0"><span
                            class="text-muted"> {{ $t('invoice_template') }}: </span>
                            <span>{{ counter.invoice_template ? counter.invoice_template.name : '' }}</span>
                            <app-icon name="file" class="size-20 text-primary"/>
                        </p>
                        <p class=" mb-0">
                            <span class="text-muted"> {{ $t('created_by') }}: </span>
                            <span> {{ counter.invoice_template ? counter.invoice_template.created_by.first_name : '' }} </span>
                            <span> {{ counter.invoice_template ? counter.invoice_template.created_by.last_name : '' }} </span>
                        </p>
                    </div>
                </div>
                <div class="col-md-12 col-lg-3">
                    <div class="d-flex justify-content-center">
                        <app-input type="switch" :label="$t('switch')" v-model="switchValue[index]"
                                   @click="toggle(index, counter.id)"/>
                        <span class="ml-1">{{ switchValue[index] === true ? $t('open') : $t('close') }}</span>
                    </div>
                </div>
                <div class="col-md-12 col-lg-2">
                    <div class="d-flex justify-content-center">
                        <default-action
                            :key="`default${counter.id}`"
                            :actions="actions"
                            :unique-key="counter.id"
                            :row-data="counter"
                            @action="triggerAction"
                        />
                    </div>
                </div>
            </div>
            <div class="row justify-content-end">
                <div class="col-md-8">
                    <div class="d-flex justify-content-end">

                        <button class="btn btn-default text-primary" @click="openModal">
                            <i data-feather="plus"></i> {{ this.$t('add_new_counter') }}
                        </button>

                        <app-counter-modal
                            :branchId="data.id"
                            table-type="branches"
                            v-if="isModalActive"
                            v-model="isModalActive"
                            :counter="counters"
                            :selectedUrl="url"
                            @close="isModalActive = false"

                        />

                        <app-confirmation-modal
                            v-if="confirmationModalActive"
                            :firstButtonName="$t('yes')"
                            modal-class="warning"
                            icon="trash-2"
                            modal-id="app-confirmation-modal"
                            @confirmed="deleteCounter"
                            @cancelled="cancelled"
                        />

                        <app-confirmation-modal
                            v-if="statusOpen"
                            :firstButtonName="$t('yes')"
                            modal-class="warning"
                            icon="slash"
                            :message="$t('you_are_going_to_deactivate_a_counter')"
                            modal-id="app-confirmation-modal"
                            @confirmed="changeStatus"
                            @cancelled="cancelDeactivateCounter"
                        />

                        <app-confirmation-modal
                            v-if="statusClose"
                            :firstButtonName="$t('yes')"
                            modal-class="primary"
                            icon="check-square"
                            :message="$t('you_are_going_to_activate_a_counter')"
                            modal-id="app-confirmation-modal"
                            @confirmed="changeStatus"
                            @cancelled="cancelActivateCounter"
                        />

                    </div>
                </div>
            </div>

        </div>
    </div>
</template>
<script>
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import DefaultAction from "../../../../../core/components/datatable/DefaultAction";
import CashRegisterAddEditModal from "../../Counter/CounterAddEditModal";
import { COUNTERS } from "../../../../Config/ApiUrl-CPB";
import Status from "./Status";
import {axiosGet, axiosPost} from "../../../../../common/Helper/AxiosHelper";

export default {
    name: "CounterDetails",
    mixins: [FormHelperMixins],
    props: ['tableID', 'value', 'data'],
    components: {
        Status,
        DefaultAction,
        CashRegisterAddEditModal
    },
    data() {
        return {

            switchValue: [],
            clickedIndex: null,
            clickedCounterId: null,
            isModalActive: false,
            statusOpen: false,
            statusClose: false,
            cancelStatus: false,
            counters: this.data.counters,
            counterId: '',
            url: '',
            deleteUrl: COUNTERS,
            COUNTERS,
            confirmationModalActive: false,
            openCounter: null,
            closeCounter: null,
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
        this.counters.forEach(counter => {
            if (counter.status.name === "status_open") {
                this.switchValue.push(true);
            }else{
                this.switchValue.push(false);
            }
        });
    },
    computed: {
        counterCount() {
            return this.data.counters.reduce((carry, counter) => {
                counter.status.name === 'status_open' ? carry.open += 1 : carry.close += 1;
                return carry;
            }, {open: 0, close: 0});
        },
    },
    methods: {
        getCounters() {
            axiosGet(COUNTERS).then((response) => {
                this.counters = response.data;
            }).catch((error) => {
                this.$toastr.e(error);
            });
        },
        changeStatus() {
            axiosPost(`${this.apiUrl.COUNTERS}/${this.clickedCounterId}/update-status`, {
                status_id: this.switchValue
            }).then(({data}) => {
                this.toastAndReload(data.message, 'all-branches-table');
            }).catch(({response}) => {
                this.toastAndReload(response.data.message, 'all-branches-table');
            }).finally(() => {
                this.confirmationModalActive = false;
            });
        },
        openModal() {
            this.isModalActive = true;
            this.url = '';
        },
        cancelled() {
            this.confirmationModalActive = false;
            this.deleteUrl = '';
        },
        deleteCounter() {
            this.axiosDelete(this.deleteUrl).then(({data}) => {
                this.cancelled();
                this.toastAndReload(data.message, 'all-branches-table');
            });
        },
        triggerAction(row, action) {
            this.branchId = row.id;
            if (action.type === 'edit') {
                this.url = `${COUNTERS}/${row.id}`;
                this.isModalActive = true;
                this.status = row.status;
            } else if (action.type === 'delete') {
                this.confirmationModalActive = true;
                this.deleteUrl = `${COUNTERS}/${row.id}`;
            } else {
                this.getAction(row, action, active)
            }
        },
        cancelDeactivateCounter() {
            this.statusOpen = false;
            this.switchValue[this.clickedIndex] = !this.switchValue[this.clickedIndex];
        },
        cancelActivateCounter() {
            this.statusClose = false;
            this.switchValue[this.clickedIndex] = !this.switchValue[this.clickedIndex];
        },
        toggle(index, id) {
            this.clickedIndex = index;
            this.clickedCounterId = id;
            if (this.switchValue[index]) {
                this.statusClose = false;
                this.statusOpen = true;
            } else {
                this.statusOpen = false;
                this.statusClose = true;
            }
        }
    }
}
</script>