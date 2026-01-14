import DeleteMixin from "./DeleteMixin";
import StatusMixin from "./StatusMixin";
import {STOCK} from "../../../tenant/Config/ApiUrl-CP";

export default {
    mixins: [DeleteMixin, StatusMixin],
    methods: {
        updateUrl(baseUrl, callback){
            const branchIdOnLocalStorage = localStorage.getItem('currentBranchWahrehouseId');
            this.options.url = `${baseUrl}?branch_or_warehouse_id=${branchIdOnLocalStorage ?? this.getBranchOrWarehouseId}`
            if (callback) callback();
            this.$hub.$emit(`reload-${this.table_id}`)
        }
    }
}
