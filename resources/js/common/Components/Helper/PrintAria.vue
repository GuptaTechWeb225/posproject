<template>
  <div>
    <div :id="id">
      <slot></slot>
    </div>
  </div>
</template>

<script>
import AppFunction from "../../../core/helpers/app/AppFunction.js";
import * as printJS from "print-js";
export default {
  props: {
    id: {
      type: String,
      default: 'print_aria'
    },
    css: {
      type: String,
      default: "",
    },
  },
  data() {
    return {
      option: {
        printable: this.id,
        type: "html",
        maxWidth: "",
        scanStyles: false,
        css: [AppFunction.getAppUrl(this.css)],
        onPrintDialogClose: () => {
            this.$emit("close");
        },
      },
    };
  },
  mounted() {
    printJS(this.option);
  },
};
</script>