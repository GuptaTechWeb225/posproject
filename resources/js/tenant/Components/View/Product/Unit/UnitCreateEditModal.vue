<template>
    <modal id="unit-create-edit-modal"
           size="default"
           v-model="showModal"
           :title="generateModalTitle('unit')"
           @submit="submitData"
           :loading="loading"
           :preloader="preloader">

        <form ref="form" class="mb-12"
              :data-url='selectedUrl ? selectedUrl : url'
              @submit.prevent="submitData">

            <div :class="`add-unit mb-primary ${ $isRtl ? 'text-left' : '' }`">
                <label for="unit-add-input">{{ $t('name') }}</label>
                <app-input
                    id="unit-add-input"
                    type="text"
                    :placeholder="$placeholder('name')"
                    :errorMessage="$errorMessage(errors, 'name')"
                    v-model="formData.name"
                />
            </div>

            <div :class="`unit-status ${ $isRtl ? 'text-left' : '' }`"  v-if="this.$can('change_unit_status')">
                <label for="unit-status-input">{{ $t('status') }}</label>
                <app-input
                    id="unit-status-input"
                    type="select"
                    :list="unitStatuses"
                    v-model="formData.status_id"
                />
            </div>
        </form>
    </modal>
</template>

<script>
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import HelperMixin from "../../../../../common/Mixin/Global/HelperMixin";
import ModalMixin from "../../../../../common/Mixin/Global/ModalMixin";
import {UNIT} from "../../../../Config/ApiUrl-CP";
import SelectableStatusMixin from "../../../../Helper/SelectableOptions/SelectableStatusMixin";

export default {
    name: "UnitCreateEditModal",
    mixins: [FormHelperMixins, HelperMixin, ModalMixin, SelectableStatusMixin],
    props: ['status'],
    data() {
        return {
            url: UNIT,
            formData: {
                name: '',
                status_id: null,
            }
        }
    },
    methods: {
        submitData(){
            this.loading = true;
            this.message = '';
            this.errors = {};
            this.formData = {...this.formData }

            this.save(this.formData);
        },
        afterSuccess({data}) {
            $('#unit-create-edit-modal').modal('hide');
            this.$emit('update-values');
            this.$emit('branch-created');
            this.toastAndReload(data.message, 'all-units-table');
            this.formData = {};
        },
    },
    mounted(){
        this.getUnitStatuses();
    }
}
</script>

<style lang="scss" scoped>

.mb-12 {
    margin-bottom: 12rem !important;
}

</style>
