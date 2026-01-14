<template>
  <div class="content-wrapper">
    <app-breadcrumb
        :page-title="$t('products_import')"
        :directory="[$t('products'), $t('products_import')]"
        :icon="'users'"
        :button="{ label: $t('back_to_products') }"
    />

    <div class="card border-0 card-with-shadow">
      <form
          class="mb-0"
          ref="form"
          enctype="multipart/form-data"
      >
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <div class="note-title d-flex">
                <app-icon name="book-open" />
                <h6 class="card-title pl-2">{{ $t("csv_format_guideline") }}</h6>
              </div>
              <div class="note note-warning p-4 mb-5">
                <p class="my-1">- {{ $t("csv_guideline_1") }}</p>
                <p class="my-1">- {{ $t("csv_guideline_2") }}</p>
                <p class="my-1">- {{ $t("csv_guideline_3") }}</p>
                <p class="my-1">- {{ $t("csv_guideline_5") }}</p>
                <p class="my-1">- {{ $t("csv_guideline_7") }}</p>
                <p class="my-1">- {{ $t("csv_guideline_4") }}</p>
              </div>

              <div>
                  <p> {{ $t("csv_download_label") }}
                  <a href="#" @click.prevent="sampleDownload">{{ $t("download_sample_file") }}</a></p>
              </div>
                <div class="form-group">
                <app-input type="dropzone" v-model="files" :maxFiles="1"  :key="dropzoneKey" />

                <div class="mt-2" v-if="stat?.errors > 0">
                  <span class="text-secondary">
                    <app-icon name="check-circle" class="mr-2" stroke-width="1" />
                    <span class="text-success"> {{ stat?.successfull }} </span>
                    {{ $t('successful_rows') }}
                  </span>
                  <div class="progress mt-1 mb-1">
                    <div
                        class="progress-bar progress-bar-striped bg-success"
                        role="progressbar"
                        :style="`width: ${stat?.successRate}%`"
                        :aria-valuenow="`${stat?.successRate}`"
                        aria-valuemin="0"
                        aria-valuemax="100"
                    >
                      {{ stat?.successRate }}
                      %
                    </div>
                  </div>
                  <span class="text-secondary">
                    <app-icon name="x-circle" class="mr-2" stroke-width="1" />
                    <span class="text-warning">{{ stat?.unsuccessfull }}</span>
                    {{ $t('unsuccessful_rows') }} <br />
                  </span>
                  <div class="progress mt-1 mb-1">
                    <div
                        class="progress-bar progress-bar-striped bg-danger"
                        role="progressbar"
                        :style="`width: ${stat?.unsuccessRate}%`"
                        :aria-valuenow="`${stat?.unsuccessRate}`"
                        aria-valuemin="0"
                        aria-valuemax="100"
                    >
                      {{ stat?.unsuccessRate }}
                      %
                    </div>
                  </div>
                  <span class="text-secondary">
                    <app-icon name="info" class="mr-2" stroke-width="1" />
                    <span class="text-danger">{{ stat?.errors }}</span>
                    {{ $t("errors_found") }}
                  </span>
                  <div class="progress mt-1 mb-1">
                    <div
                        class="progress-bar progress-bar-striped bg-secondary"
                        role="progressbar"
                        :style="`width: ${stat?.errorRate}%`"
                        :aria-valuenow="`${stat?.errorRate}`"
                        aria-valuemin="0"
                        aria-valuemax="100"
                    >
                      {{ stat?.errorRate }}
                      %
                    </div>
                  </div>
                </div>
                <small v-if="errors.import_file" class="text-danger">
                  {{ errors.import_file[0] }}
                </small>
                <template v-if="errors.length > 0">
                  <div
                      class="alert alert-danger alert-dismissible fade show mt-3"
                      role="alert"
                  >
                    <h5 class="alert-heading">{{$t('error')}}</h5>
                    {{ errors.length }} required fields are missing
                    <hr />
                      {{$t('missing_fields')}}:
                    <em
                    ><code>{{ errors.join(",") }}</code></em
                    >
                    <button
                        type="button"
                        class="close"
                        data-dismiss="alert"
                        aria-label="Close"
                    >
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                </template>
              </div>
            </div>
          </div>
        </div>
      </form>

      <div class="card-footer p-primary bg-transparent">
        <button type="button" class="btn btn-primary mr-2" @click.prevent="submitData">
          <app-pre-loader v-if="loading" />
          <template v-else>{{ $t("save") }}</template>
        </button>
        <button
            type="button"
            class="btn btn-secondary mr-2"
            data-dismiss="modal"
            @click="cancel"
        >
          {{ $t("cancel") }}
        </button>
        <button
            v-if="stat.errors > 0"
            class="btn btn-warning btn-sm"
            href=""
            @click.prevent="importErrorOpen"
        >
          <app-icon name="download" class="mr-2" stroke-width="1" />{{
            $t("export_report.xlsx")
          }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>

import {check} from "../../../Helper/Check";
import {urlGenerator} from "../../../Helper/Helper";
import {FormMixin} from "../../../../core/mixins/form/FormMixin";
import HelperMixin from "../../../../common/Mixin/Global/HelperMixin";
import {IMPORT_PRODUCT} from "../../../Config/ApiUrl-CPB";

export default {
  mixins: [FormMixin, HelperMixin],
  props: {
    validKeys: {
      type: Array,
    },
  },
  data() {
    return { 
      dropzoneKey: 0,
      files: [],
      errors: [],
      stat: {},
      check,
      loading: false,
      sampleFileType: [],
    };
  },
  methods: {
    resetDropzone() {
        this.files = [];
        this.dropzoneKey++;
    },
    beforeSubmit() {
      this.loading = true;
    },
    submitData() {
      this.loading = true
      this.stat = {};
      this.errors = [];
      const formData = new FormData();

      if (this.files.length) formData.append('import_file', this.files[0])
      else {
          this.$toastr.e(this.$t('please_add_some_files_to_import'))
          this.loading = false;
          return;
      }

      this.axiosPost({ url: IMPORT_PRODUCT, data: formData }).then(res => {
          if (res.data?.stat) {
              this.stat = res.data.stat;

              if (+this.stat.unsuccessRate === 100) {
                  this.$toastr.e(this.$t('products_not_imported'))
                  return this.resetDropzone();
              }
          }
          this.toastAndReload(res.data.message, 'product-table');
          this.resetDropzone()
      }).catch((e) => {
          if (e.response.data instanceof Array)  {
              this.errors = e.response.data;
              return;
          }
          this.$toastr.e(e.response.data.message);
      }).finally(() => {
          this.loading = false
      })
    },
    afterError(response) {
      this.loading = false;
      this.errors = response.data.errors ?? response.data;
    },
    afterSuccess(response) {
      this.loading = false;
      if (response.data.stat?.errors > 0) {
        if (response.data.stat.successfull > 0) {
          this.$toastr.i(response.data.message);
        }
        this.stat = response.data.stat;
      } else {
        this.$toastr.s(response.data.message);
        this.scrollToTop(false);
        setTimeout(() => {
          location.reload();
        }, 1000);
      }
    },
    cancel() {
      location.reload();
    },
    sampleDownload() {
      let commas = "";
      let keys = [
        "name",
        "description",
        "category",
        "subcategory",
        "brand",
        "unit",
        "group",
        "product_type",
        "upc",
        "selling_price",
        "stock_reminder_quantity",
        "variant_value",
        "variant_name",
      ];

      if (this.sampleFileType.length) {
        keys = [...this.validKeys];
        commas = ",".repeat(keys.slice(5).length);
      }

      this.downloadCSV(
          `${keys.join(",")}\n` +
          `Product 1,description...,Category-Wheat,Subcategory-Sister Fisher I,Brand-Cornsilk,Unit-Purple,Group-Teal,single,upc-02,211,11,"","",${commas}\n` +
          `Product 2 ,description...,Category-Wheat,Subcategory-Sister Fisher I,Brand-Cornsilk,Unit-Purple,Group-Teal,single,upc-5,300,10,"","",${commas}\n` +
          `Product 3 ,description...,Category-Wheat,Subcategory-Sister Fisher I,Brand-Cornsilk,Unit-Purple,Group-Teal,variant,upc-4,400,10,"red,m","product color red size m",${commas}\n`
    );
    },
    downloadCSV(csv) {
      let e = document.createElement("a");
      e.href = "data:text/csv;charset=utf-8," + encodeURI(csv);
      e.target = "_blank";
      e.download = `${this.$t("products")}.csv`;
      e.click();
    },

    importErrorOpen() {
      var downloadLink = window.document.createElement("a");
      downloadLink.href = urlGenerator("/storage/importReport.xlsx");
      downloadLink.download = "Import Report.xlsx";
      downloadLink.click();
    },
  },
};

</script>
<style scoped>
.progress-bar.bg-danger {
  color: #fbfbfb !important;
}
</style>
