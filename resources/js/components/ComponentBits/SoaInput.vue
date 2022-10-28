<template lang="">
    <v-col class="col-md-3">
        <v-file-input
            outlined
            dense
            v-model="fileUpload"
            color="#8DA90B"
            label="File input"
            placeholder="Select your file"
            :clearable="false"

            @change="onFileChange($event)"
        >
            <template  v-slot:append>
                <v-tooltip bottom color="success">
                    <template v-slot:activator="{ on }">
                        <v-icon
                            :disabled="!isExcel"
                            v-on="on"
                            color="#8DA90B"
                            style="cursor: pointer"
                            @click="proceedAction"
                        >
                            mdi-file-import
                        </v-icon>
                    </template>
                    <span>Import File</span>
                </v-tooltip>
            </template>
            <template v-slot:selection="{ index, text }">
                <v-chip
                    v-if="index < 2"
                    color="deep-purple accent-4"
                    dark
                    label
                    close
                    @click:close="clearFile"
                >
                    {{ text }}
                </v-chip>
            </template>
        </v-file-input>
       <loading-progress :loading="loading" />
    </v-col>
</template>
<script>
import { readSoa } from "../../methods";

export default {
  name: "soa-input",
  props: {
    soaLists: Function,
  },
  data: () => ({
    isExcel: false,
    ocbsArrayFiltered: [],
    fileUpload: null,
    loading: false,
    withSite: false

  }),
  methods: {
    async onFileChange(event) {

        if(event) {
            this.loading = true
            setTimeout(() => {this.loading = false}, 3000)

        }
        const { arenaReportFiltered, isExcel, withSite } = await readSoa(event, this.isExcel, this.withSite);
        this.ocbsArrayFiltered = arenaReportFiltered;
        this.isExcel = isExcel;
        this.withSite = withSite
    },

    async proceedAction() {
        console.log(this.ocbsArrayFiltered)
      try {
         this.$Progress.start();
        if (
          $("#importData").val() === "" ||
          !this.fileUpload.name.includes("xlsx")
        ) {
          this.fileUpload = null
          Toast.fire({
            icon: "warning",
            title: "Make sure you insert correct excel data!",
          });
        } else {
          this.loading = true
          await axios.post("api/import", this.ocbsArrayFiltered)
          await this.soaLists()

          this.isExcel = false;
          this.fileUpload = null;
          setTimeout(() => {this.loading = false}, 1000)

        }
      } catch (error) {
          console.error(error);
          this.loading = false
          Toast.fire("Error!", "Excel import denied", "error");
      }

    },
    clearFile(file) {
      console.log(file);
      this.isExcel = false;
      this.fileUpload = null;
    },
  },
};
</script>
<style lang="">
</style>