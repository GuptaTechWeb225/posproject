
export default {
    data() {
        return {
            showNote: true,
        }
    },
    computed: {
        defaultList() {
            let list = [{id:1, value:this.$t('yes')},{id:0, value:this.$t('no')}];
            if (!this.showNote) {
                list.map((item) => {
                    if (item.id == 0)
                        item.disabled = true;
                })
                return list;
            }
            return list;
        }
    },
    methods: {
        noteVisibility(data){
            if (data.is_default == 1)
                this.showNote = false
        }
    }
}