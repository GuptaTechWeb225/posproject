<template>
    <div class="content-wrapper">

        <app-page-top-section :title="$t('groups')">

            <app-default-button
                v-if="this.$can('create_groups')"
                :title="$addLabel('group')"
                @click="openModal()"/>

        </app-page-top-section>



        <app-table
            id="group-table"
            :options="options"
            @action="triggerAction"
        />

        <app-group-modal
            v-if="isModalActive"
            v-model="isModalActive"
            :selected-url="selectedUrl"
            @close="isModalActive = false"
        />

        <app-confirmation-modal
            v-if="confirmationModalActive"
            icon="trash-2"
            modal-id="app-confirmation-modal"
            @cancelled="cancelled"
            @confirmed="confirmed('group-table')"
        />
    </div>
</template>

<script>
import HelperMixin from "../../../../../common/Mixin/Global/HelperMixin";
import GroupMixin from "../../../Mixins/GroupMixin";
import {EXPORT_GROUPS, GROUPS, PRODUCT_GROUP_IMPORT_PAGE} from "../../../../Config/ApiUrl-CP";

export default {
    name: "Group",
    mixins: [HelperMixin, GroupMixin],
    data() {
        return {
            isModalActive: false,
            selectedUrl: '',
            PRODUCT_GROUP_IMPORT_PAGE
        }
    },
    methods: {
        triggerAction(row, action, active) {
            if (action.name === 'edit') {
                this.selectedUrl = `${GROUPS}/${row.id}`;
                this.isModalActive = true;
            } else {
                this.getAction(row, action, active)
            }
        },
        openModal() {
            this.isModalActive = true;
            this.selectedUrl = ''
        },
      exportGroups(){
          window.location = EXPORT_GROUPS;
      }
    }
}
</script>