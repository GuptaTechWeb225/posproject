import {axiosGet, urlGenerator} from "../../../common/Helper/AxiosHelper";
import StatusQueryMixin from "../../../common/Mixin/Global/StatusQueryMixin";

export default {
    name: 'FilterOptionsMixin',
    mixins: [StatusQueryMixin],
    data() {
        return {
            unitStatuses: [],
            productStatuses: [],
        }
    },
    methods: {
        getStatusFilterOptions(statusType, listValueField = 'name') {
            return {
                title: this.$t('status'),
                type: 'search-and-select-filter',
                key: 'status_id',
                settings: {
                    url: urlGenerator("app/selectable-statuses"),
                    modifire: (v) => {
                        return {id: v.id, name: v.translated_name}
                    },
                    params: {
                        type: statusType,
                    },
                    per_page: 10,
                    queryName: 'search_by_name',
                    loader: 'app-pre-loader'
                },
                listValueField
            }
        },
        getUnitStatuses() {
            axiosGet(this.getStatus('unit')).then(res => {
                this.unitStatuses = res.data.data.map(unitStatusData => ({
                    id: unitStatusData.id,
                    value: unitStatusData.translated_name
                }));
                this.formData.status_id = this.unitStatuses.find(unitStatus => unitStatus.value.toLowerCase() === 'active').id;
            });
        },
        getProductStatuses() {
            axiosGet(this.getStatus('product')).then(res => {
                this.productStatuses = res.data.data.map(productStatusData => ({
                    id: productStatusData.id,
                    value: productStatusData.translated_name
                }));
            });
        }
    },
}