import {axiosGet, axiosPatch} from "../../Helper/AxiosHelper";
import HelperMixin from "./HelperMixin";

export default {
    mixins: [HelperMixin],
    methods: {
        getStatus(type) {
            return `app/selectable-statuses?type=${type}`;
        },
        formatSwitchStatusEndpoint(elementStatusToChange, elementId, statusId) {
            return `app/${elementStatusToChange}/${elementId}/change-status?status_id=${statusId}`;
        },
        switchStatus(elementStatusToChange, elementId, currentStatus, tableIdToRefresh, makeApiCall = true) {
            axiosGet(this.getStatus(elementStatusToChange))
                .then(({data: {data}}) => {
                    const [{id: activeStatusId}, {id: inactiveStatusId}] = data;
                    const switchStatusEndpoint = this.formatSwitchStatusEndpoint(
                        elementStatusToChange,
                        elementId,
                        currentStatus === 'active' ? inactiveStatusId : activeStatusId
                    );
                    axiosPatch(
                        switchStatusEndpoint
                    )
                        .then(({data}) => this.toastAndReload(data.message, tableIdToRefresh))
                        .catch((error) => this.$toastr.e(error));
                })
                .catch((error) => this.$toastr.e(error));
        },
    }
}