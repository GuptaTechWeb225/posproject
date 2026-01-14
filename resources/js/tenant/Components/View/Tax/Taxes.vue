<template>

    <div class="content-wrapper">

        <app-page-top-section :title="$t('all_taxes')" icon="briefcase">
            <app-default-button
                @click="openModal()"
                :title="$fieldTitle('Add', 'Tax', true)"/>

        </app-page-top-section>

        <div>
            <app-table
                id="all-taxes-table"
                showFilter
                :options="options"
                @action="triggerActions"
            />

        </div>
        <tax-create-edit-modal
            v-if="isModalActive"
            v-model="isModalActive"
            :selected-url="selectedUrl"
            @close="isModalActive = false"
        />

        <app-confirmation-modal
            v-if="confirmationModalActive"
            :firstButtonName="$t('yes')"
            modal-class="warning"
            icon="trash-2"
            modal-id="app-confirmation-modal"
            @confirmed="confirmed('all-taxes-table')"
            @cancelled="cancelled"
        />
    </div>

</template>

<script>
import TaxMixin from "../../Mixins/TaxMixin";
import DatatableHelperMixin from "../../../../common/Mixin/Global/DatatableHelperMixin";
import TaxCreateEditModal from "./TaxCreateEditModal";
import {TAX} from "../../../Config/ApiUrl-CPB";
import {collection} from "../../../../common/Helper/helpers";
import {axiosPatch} from "../../../../common/Helper/AxiosHelper";

export default {
    name: "Taxes",
    components: {TaxCreateEditModal},
    mixins: [TaxMixin, DatatableHelperMixin],
    props: {
        props: {
            default: ''
        },
        id: {
            type: String
        }
    },
    data() {
        return {
            selectedUrl: '',
            isModalActive: false,
        }
    },
    mounted() {
        this.$hub.$on('headerButtonClicked-' + this.id, () => {
            this.openModal();
        })
    },
    methods: {
        triggerIsDefault() {
            const tax_id = collection(this.rowValues.tax_taxes).pluck('parent_id');
            delete this.rowValues['tax_taxes'];
            this.rowValues = {...this.rowValues, tax_id};
            this.rowValues.is_default = this.item ? 1 : 0

            this.axiosPatch({
                url: TAX + '/' + this.rowValues.id,
                data: this.rowValues
            }).then(res => {
                this.$hub.$emit('reload-all-taxes-table');
            });
        },
        openModal() {
            this.selectedUrl = '';
            this.isModalActive = true;
        },
        triggerActions(row, action, active) {
            if (action.type === 'edit') {
                this.selectedUrl = `${TAX}/${row.id}`;
                this.isModalActive = true;
            } else if (action.type === 'default_status_change') {
                let rowValues = {...row};
                const tax_id = collection(row.tax_taxes).pluck('parent_id');
                delete rowValues['tax_taxes'];
                rowValues = {...rowValues, tax_id};
                rowValues.is_default = row.tax_taxes ? 1 : 0

                axiosPatch(TAX + '/' + rowValues.id, rowValues).then(res => {
                    this.$hub.$emit('reload-all-taxes-table');
                });
            } else {
                this.getAction(row, action, active)
            }
        },
    },
    beforeDestroy() {
        this.$hub.$off('headerButtonClicked-' + this.id);
    }
}
</script>
