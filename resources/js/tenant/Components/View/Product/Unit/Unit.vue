<template>
    <div class="content-wrapper">

        <app-page-top-section :title="$t('units')" icon="briefcase">

            <div class="dropdown d-inline-block btn-dropdown">
                <button class="btn btn-success dropdown-toggle ml-0 mr-2"
                        aria-expanded="false"
                        aria-haspopup="true"
                        data-toggle="dropdown"
                        type="button">
                    {{ $t('action') }}
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item d-flex align-items-center p-3" href="#" @click="exportUnit">
                        <app-icon class="size-15 mr-2" name="download"/>
                        {{ ucWords($t('export_unit')) }}
                    </a>
                </div>
            </div>

            <app-default-button v-if="this.$can('create_unit')"
                                @click="openModal"
                                :title="$addLabel('unit')"/>
        </app-page-top-section>

        <app-table id="all-units-table"
                   :options="options"
                   @action="triggerActions"
        />

        <app-unit-create-edit-modal
            v-if="isModalActive"
            v-model="isModalActive"
            :selected-url="selectedUrl"
            @close="isModalActive = false"

        />

        <app-confirmation-modal
            v-if="confirmationModalActive"
            :firstButtonName="$t('yes')"
            modal-class="danger"
            icon="trash-2"
            modal-id="app-confirmation-modal"
            @confirmed="confirmed('all-units-table')"
            @cancelled="cancelled"
        />
    </div>

</template>

<script>
import UnitMixin from "../../../Mixins/UnitMixin";
import DatatableHelperMixin from "../../../../../common/Mixin/Global/DatatableHelperMixin";
import {UNIT, UNIT_EXPORT} from "../../../../Config/ApiUrl-CP";
import {ucWords} from "../../../../../common/Helper/Support/TextHelper";
import StatusQueryMixin from "../../../../../common/Mixin/Global/StatusQueryMixin";
import {urlGenerator} from "../../../../Helper/Helper";

export default {
    name: "Unit",
    mixins: [UnitMixin, DatatableHelperMixin, StatusQueryMixin],
    data() {
        return {
            selectedUrl: '',
            isModalActive: false,
            ucWords
        }
    },
    methods: {
        openModal() {
            this.selectedUrl = '';
            this.isModalActive = true;
        },
        triggerActions(row, action, active) {
            if (action.type === 'edit') {
                this.selectedUrl = `${UNIT}/${row.id}`;
                this.isModalActive = true;
            } else if (action.type === 'status_change') {
                this.triggerStatusChange(row.id, row.status.translated_name);
            } else {
                this.getAction(row, action, active)
            }
        },
        exportUnit() {
            window.location = urlGenerator(UNIT_EXPORT);
        },
        triggerStatusChange(id, statusToChangeFrom) {
            this.switchStatus(
                'unit',
                id,
                statusToChangeFrom.toLowerCase(),
                'all-units-table'
            );
        }

    },
}
</script>
