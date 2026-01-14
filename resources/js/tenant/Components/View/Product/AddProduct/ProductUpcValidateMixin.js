import {axiosGet} from "../../../../../common/Helper/AxiosHelper";

export default {
    data() {
        return {
            showUpcLoader: false,
            upcIsUnique: true,
            variantUpcData: {
                upcs: [],
            }
        }
    },
    mounted() {
        if (this.formData.variants) this.variantUpcData.upcs = this.formData.variants.map((v, i) => ({upc: v.upc, variant_index: i}));
    },
    methods: {
        switchUpcUniqueStatus(status) {
            this.upcIsUnique = status;
        },
        async handleSingleProductUpcChange(e, variant_id) {
            if (!variant_id) variant_id = this.formData.variants.length ? this.formData.variants[0].id : 0;
            if (!this.formData.upc) return;
            // resetting these values everytime user attempts to type something
            this.showUpcLoader = true;
            this.upcIsUnique = true;
            const {data} = await axiosGet(`/app/product/check_unique_upc/${this.formData.upc}/${this.productId ? variant_id : ''}`);
            if (data === 'unique') this.showUpcLoader = false;
            else {
                this.switchUpcUniqueStatus(false)
                this.showUpcLoader = false;
            }
        },
        async handleVariantProductUpcChange(item, index) {
            if (!item.upc) return;
            this.formData.variants[index].upcData.showVariantUpcLoader = true;
            this.formData.variants[index].upcData.variantUpcIsUnique = true;

            // checking for existing upcs in the database
            this.variantUpcData.upcs[index].variant_upc = item.upc;
            const {data: upcStatus} = await axiosGet(`/app/product/check_unique_upc/${item.upc}/${this.productId ? this.formData.variants[index].id : ''}`);
            this.formData.variants[index].upcData.showVariantUpcLoader = false;

            if (upcStatus === 'not-unique') return this.formData.variants[index].upcData.variantUpcIsUnique = false;

            // checking for existing upcs in the client side
            this.handleVariantUpcBlur(item, index);
        },
        handleVariantUpcBlur(item, index) {
            const enteredUpcs = this.variantUpcData.upcs.map(upc => typeof upc.variant_upc === 'number' ? JSON.stringify(upc.variant_upc) : upc.variant_upc);
            const dupesRemoved = Array.from([...new Set(enteredUpcs)]);

            if (!this.formData.variants[index]) return;
            if (!this.formData.variants[index].upcData.variantUpcIsUnique) return;
            this.formData.variants[index].upcData.variantUpcIsUnique = dupesRemoved.length === enteredUpcs.length;

        }
    }
}










