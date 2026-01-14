<template>

    <div class="content-wrapper">

        <app-page-top-section :title="$t('brands')" icon="briefcase">

            <div class="dropdown d-inline-block btn-dropdown">
                <button class="btn btn-success dropdown-toggle ml-0 mr-2"
                        aria-expanded="false"
                        aria-haspopup="true"
                        data-toggle="dropdown"
                        type="button">
                    {{ $t('action') }}
                </button>
                <div class="dropdown-menu">

                    <a class="dropdown-item d-flex align-items-center p-3" href="#" @click="exportBrands">
                        <app-icon class="size-15 mr-2" name="upload"/>
                        {{ ucWords($t('export_brand')) }}
                    </a>
                </div>
            </div>

            <app-default-button
                v-if="this.$can('create_brands')"
                @click="openModal()"
                :title="$fieldTitle('Add', 'Brand', true)"/>

        </app-page-top-section>

        <app-table
            id="brands-table"
            :options="options"
            @action="triggerActions"
        />

        <app-brand-modal
            v-if="isModalActive"
            v-model="isModalActive"
            :selected-url="selectedUrl"
            @close="isModalActive = false"
        />

        <app-confirmation-modal
            v-if="confirmationModalActive"
            icon="trash-2"
            modal-id="app-confirmation-modal"
            @confirmed="confirmed('brands-table')"
            @cancelled="cancelled"

        />

    </div>

</template>

<script>
import HelperMixin from "../../../../../common/Mixin/Global/HelperMixin";
import BrandMixin from "../../../Mixins/BrandMixin";
import {BRAND, EXPORT_BRANDS} from "../../../../Config/ApiUrl-CP";
import {ucWords} from "../../../../../common/Helper/Support/TextHelper";
import {urlGenerator} from "../../../../../common/Helper/AxiosHelper";

export default {

    name: "Brands",
    mixins: [HelperMixin, BrandMixin],

    data() {
        return {
            isModalActive: false,
            selectedUrl: '',
            ucWords
        }
    },

    methods: {

        triggerActions(row, action, active) {

            if (action.title === this.$t('edit')) {
                this.selectedUrl = `${BRAND}/${row.id}`;
                this.isModalActive = true;
            } else {
                this.getAction(row, action, active)
            }
        },

        openModal() {
            this.isModalActive = true;
            this.selectedUrl = ''
        },
        exportBrands(){
            window.location = urlGenerator(EXPORT_BRANDS);
        }
    }
}
</script>