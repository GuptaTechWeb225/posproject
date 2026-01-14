<template>
  <modal id="tax-create-edit-modal"
         v-model="showModal"
         :loading="loading"
         :preloader="preloader"
         :title="generateModalTitle('tax')"
         size="large"
         @submit="submitData">

    <form ref="form"
          :data-url='selectedUrl ? selectedUrl : url'
          @submit.prevent="submitData">

      <app-form-group
          v-model="formData.name"
          :error-message="$errorMessage(errors, 'name')"
          :label="$t('name')"
          :page="'modal'"
          :placeholder="$placeholder('tax name')"
          :required="true"
      />

      <app-form-group
          v-model="formData.type"
          :error-message="$errorMessage(errors, 'type')"
          :label="$t('type')"
          :list="typeList"
          :page="'modal'"
          :radio-checkbox-name="'for-type'"
          :required="true"
          :disabled="Boolean(selectedUrl)"
          :type="'radio'"
      />

      <template v-if="showGroupByInput">
         <app-form-group
             v-if="formData.type=='multi_tax' && !this.loader"
             v-model="formData.tax_id"
             :error-message="$errorMessage(errors, 'tax_id')"
             :label="$t('group_by')"
             :list="selectableTax"
             list-value-field="name"
             type="multi-select"
         />
      </template>

      <p v-if="formData.type === 'multi_tax'" class="my-4">{{ $t('total_percentage_by_grouping_all_single_tax') }}:
        {{ totalTax }} </p>

      <app-form-group
          v-if="formData.type === 'single_tax'"
          v-model="formData.percentage"
          :error-message="$errorMessage(errors, 'percentage')"
          :label="$t('percentage')"
          :max-number="100"
          :page="'modal'"
          :placeholder="$placeholder('percentage')"
          :required="true"
          type="number"
      />


      <app-note v-if="formData.is_default === 1 && showNote "
                :notes="[$t('this_tax_will_be_applicable_automatically_when_tax_applied')]" :show-title="false"
                title="'title'"/>

    </form>

  </modal>
</template>

<script>
import FormHelperMixins from "../../../../common/Mixin/Global/FormHelperMixins";
import HelperMixin from "../../../../common/Mixin/Global/HelperMixin";
import ModalMixin from "../../../../common/Mixin/Global/ModalMixin";
import CoreLibrary from "../../../../core/helpers/CoreLibrary";
import {GET_TAX,TAX} from "../../../Config/ApiUrl-CPB";
import IsDefaultHelperMixin from "../../Mixins/IsDefaultHelperMixin";
import {axiosGet} from "../../../../common/Helper/AxiosHelper";
import {collection} from "../../../../common/Helper/helpers";

export default {
  name: "TaxCreateEditModal",
  extends: CoreLibrary,
  mixins: [FormHelperMixins, HelperMixin, ModalMixin, IsDefaultHelperMixin],
  data() {
    return {
      url: TAX,
      formData: {
        is_default: false,
        type: 'single_tax',
        tax_id: [],
      },
      typeList: [
        {id: 'single_tax', value: this.$t('single_tax')},
        {id: 'multi_tax', value: this.$t('multi_tax')}
      ],
      GET_TAX,
      isSingleTax: true,
      showGroupBy: false,
      selectableTax: [],
      loader: false,
    }

  },
  methods: {
      submitData() {
          this.fieldStatus.isSubmit = true;
          this.loading = true;
          this.message = '';
          this.errors = {};

          if (this.formData.type === 'single_tax') {
              this.formData.tax_id = [];
          }
          this.save(this.formData);
      },
      afterSuccess({data}) {
      this.formData = {};
      $('#tax-create-edit-modal').modal('hide');
      this.$emit('tax-created');
      this.toastAndReload(data.message, 'all-taxes-table');
    },
    afterSuccessFromGetEditData(response) {

      this.getSelectableTax()
      this.preloader = false;
      const tax_id = collection(response.data.tax_taxes).pluck('parent_id');
      delete response.data['tax_taxes'];
      this.formData = {...response.data, tax_id, is_default: +response.data.is_default === 1};
      this.noteVisibility(response.data)
    },
    getSelectableTax() {
      this.loader = true;
      axiosGet(this.GET_TAX).then(({data}) => {
        this.selectableTax = data
      }).finally(()=>{
          this.loader = false;
      });
    }
  },
  mounted() {
    this.getSelectableTax()
  },
  computed: {
    totalTax() {
        if (!this.selectableTax?.length) return 0;
        let selectedTaxFilter = this.selectableTax?.filter(e => this.formData.tax_id?.includes(e.id))

        return selectedTaxFilter.reduce((prev, acc) => {
            prev += parseFloat(acc['percentage']);
            return prev;
        }, 0)
    },
    showGroupByInput() {
      if (!Boolean(this.selectedUrl)) return true;
      if (Boolean(this.formData.tax_id.length)) return true;
      return false;
    }
  }
}
</script>
