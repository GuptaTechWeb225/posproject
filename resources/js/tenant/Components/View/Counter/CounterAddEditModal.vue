<template>
    <modal id="counter-modal"
           v-model="showModal"
           :title="generateModalTitle('counter')"
           @submit="submitData" :loading="loading"
           :preloader="preloader">
        <form
            ref="form"
            :data-url='selectedUrl ? selectedUrl : COUNTERS'
            @submit.prevent="submitData">
            <app-form-group
                :label="$t('name')"
                :placeholder="$placeholder('name')"
                v-model="formData.name"
                :required="true"
                :error-message="$errorMessage(errors, 'name')"
            />

            <div :class="`form-element mb-3 ${ $isRtl ? 'text-left' : '' }`">
                <label>{{ $t('branch') }}</label>
                    <app-input
                        type="select"
                        :placeholder="$placeholder('branch')"
                        v-model="formData.branch_or_warehouse_id"
                        :list="branchList"
                        :error-message="$errorMessage(errors, 'branch_or_warehouse_id')"
                    />
            </div>
        </form>
    </modal>
</template>

<script>
import FormHelperMixins from "../../../../common/Mixin/Global/FormHelperMixins";
import ModalMixin from "../../../../common/Mixin/Global/ModalMixin";
import {
    COUNTERS,
    SELECTABLE_BRANCH, SELECTABLE_BRANCHES,
    SELECTABLE_INVOICE_TEMPLATE
} from "../../../Config/ApiUrl-CPB";

import { axiosGet } from "../../../../common/Helper/AxiosHelper";
import {mapGetters} from "vuex";

export default {
    name: "CounterAddEditModal",
    mixins: [FormHelperMixins, ModalMixin],
    props: {
        tableType: {
            default: 'counter',
        },
        branchId: {},
    },
    data() {
        return {
            formData: {
                sales_person: [],
                branch_id: this.branchId,
                tenant_id: '',
                branch_or_warehouse_id: '',
            },
            branchList: [],
            COUNTERS,
            SELECTABLE_BRANCH,
            SELECTABLE_INVOICE_TEMPLATE,
        }
    },
    mounted() {
        this.formData.tenant_id = this.getTenantId;
        this.getBranchList();
    },
    computed: {
        ...mapGetters(['getTenantId']),
    },
    methods: {
        async getBranchList() {
            try {
                const { data: { data: branchData } } = await axiosGet(SELECTABLE_BRANCHES);
                this.branchList = branchData.map(branchObj => ({ id: branchObj.id, value: branchObj.name }));
            } catch (e) {
                this.$toastr.e(e)
            }
        },
        afterSuccess({data}) {
            this.formData = {
                sales_person: []
            };
            $('#counter-modal').modal('hide');
            this.$emit('input', false);
            if (this.tableType === 'counter') {
                this.toastAndReload(data.message, 'counter-table');
            } else {
                this.toastAndReload(data.message, 'all-branches-table');
            }
        },
        afterSuccessFromGetEditData(response) {
            this.preloader = false;
            this.formData = response.data;
        },
    },
}
</script>
