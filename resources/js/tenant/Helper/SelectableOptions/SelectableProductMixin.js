import {axiosGet} from "../../../common/Helper/AxiosHelper";
export default {
    name: 'SelectableProductMixin',
    data() {
        return {
            productNameList: [],
        }
    },
    methods: {
        getAllProductsName() {
            axiosGet('app/selectable-products').then(res =>{
                this.productNameList = res.data.data
            });
        }
    },
}
