<template>
    <div class="content-wrapper">
        <app-page-top-section :title="$t('manage_lot')">
                <!-- Will be implemented later -->


          <app-default-button
              class="btn btn-primary btn-with-shadow mb-4 d-inline-flex align-items-center"
              v-if="this.$can('create_lot')"
              :title="$addLabel('lot')"
              :url="('/lot/add')"/>
        </app-page-top-section>

        <app-table
            :id="table_id"
            :options="options"
            v-if="options.url"
            @action="triggerActions"
        />

        <app-confirmation-modal
            v-if="confirmationModalActive"
            :firstButtonName="$t('yes')"
            modal-class="warning"
            icon="trash-2"
            modal-id="app-confirmation-modal"
            @confirmed="confirmed('lot-table')"
            @cancelled="cancelled"
        />
    </div>
</template>

<script>
import HelperMixin from "../../../../../common/Mixin/Global/HelperMixin";
import {LOT} from "../../../../Config/ApiUrl-CP";
import LotTableMixin from "../../../Mixins/LotTableMixin";
import DatatableHelperMixin from "../../../../../common/Mixin/Global/DatatableHelperMixin";
import {urlGenerator} from "../../../../Helper/Helper";
import {axiosGet} from "../../../../../common/Helper/AxiosHelper";
import {mapMutations} from "vuex";

export default {
    name: "Lot",
    mixins: [DatatableHelperMixin, HelperMixin, LotTableMixin],
    data() {
        return {
            table_id: 'lot-table',
            isModalActive: false,
            selectedUrl: '',
        }
    },
    methods: {
        triggerActions(row, action, active) {
            if (action.name === 'edit') {
                this.selectedUrl = `${LOT}/${row.id}`;
                window.location.replace(urlGenerator(`/lot/edit/${row.id}`));
            }  else if (action.name === 'view') {
                // do something here to detect that the user wants to 'view' the lot
                window.location.replace(urlGenerator(`/lot/edit/${row.id}?read_only=true`));
            } else {
                this.getAction(row, action, active)
            }
        },
        openModal() {
            this.isModalActive = true;
            this.selectedUrl = ''
        },
        ...mapMutations({
            setStatuses: 'SET_LOT_STATUSES',
        }),
    },
    async mounted() {
        const statusData = await axiosGet('app/selectable-statuses?search_by_name=&page=1&per_page=10&type=purchase')
        const lotStatuses = statusData.data.data;
        this.setStatuses(lotStatuses);
    }
}
</script>