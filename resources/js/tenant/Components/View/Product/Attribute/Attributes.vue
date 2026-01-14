<template>

    <div class="content-wrapper">

        <app-page-top-section :title="$t('variant_attributes')" icon="briefcase">

            <div class="dropdown d-inline-block btn-dropdown">
                <button class="btn btn-success dropdown-toggle ml-0 mr-2"
                        aria-expanded="false"
                        aria-haspopup="true"
                        data-toggle="dropdown"
                        type="button">
                    {{ $t('action') }}
                </button>
                <div class="dropdown-menu">

                    <a class="dropdown-item d-flex align-items-center p-3" href="#" @click="exportAttributes()">
                        <app-icon class="size-15 mr-2" name="upload"/>
                        {{ $t('export_all_data') }}
                    </a>
                </div>
            </div>


            <app-default-button
                @click="openModal()"
                v-if="this.$can('create_attributes')"
                :title="$fieldTitle('Add', 'variant_attributes', true)"/>

        </app-page-top-section>

        <app-table
            id="attributes-table"
            :options="options"
            @action="triggerActions"
        />

        <app-attribute-modal
            v-if="isModalActive"
            v-model="isModalActive"
            :selected-url="selectedUrl"
            @close="isModalActive = false"
        />

        <app-confirmation-modal
            v-if="confirmationModalActive"
            icon="trash-2"
            modal-id="app-confirmation-modal"
            @confirmed="confirmed('attributes-table')"
            @cancelled="cancelled"

        />
    </div>

</template>

<script>
import HelperMixin from "../../../../../common/Mixin/Global/HelperMixin";
import AttributeMixin from "../../../Mixins/AttributeMixin";
import {EXPORT_ATTRIBUTE, ATTRIBUTE} from "../../../../Config/ApiUrl-CP";
import {urlGenerator} from "../../../../Helper/Helper";

export default {

    name: "Attributes",
    mixins: [HelperMixin, AttributeMixin],

    data() {
        return {
            isModalActive: false,
            selectedUrl: '',
        }
    },
    methods: {

        triggerActions(row, action, active) {
            if (action.title === this.$t('edit')) {
                this.selectedUrl = `${ATTRIBUTE}/${row.id}`;
                this.isModalActive = true;
            } else {
                this.getAction(row, action, active)
            }
        },

        openModal() {
            this.isModalActive = true;
            this.selectedUrl = ''
        },
        exportAttributes() {
            window.location = urlGenerator(EXPORT_ATTRIBUTE);
        }
    }
}
</script>
