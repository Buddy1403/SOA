<template lang="">
<div>

    <v-data-table
        item-key="codeEvent"
        :headers="headers"
        :items="arenaData"
        v-model="selected"
        :loading="loading"
        :search="search"
        :show-select="downloadingReport ? false : true"
        :disable-filtering="downloadingReport ? true : false"
        :disable-sort="downloadingReport ? true : false"
        :single-select="singleSelect"
        class="elevation-1 text-center"
        :footer-props="{
            'items-per-page-options': itemsPerPageOptions,

        }"
        :options="perPagee"
        :page="page"

        @page-count="handlePageCount"
        :server-items-length="total"
        @pagination="handlePaginate"
         @update:options="handlePageOptions"
        @toggle-select-all="selectAllToggle"
        @input="singleSelected"

    >
        <template v-slot:top="{ pagination, options, updateOptions }">
            <v-data-footer
                :items-per-page-options ="itemsPerPageOptions"
                :pagination="pagination"
                :options="perPagee"
                items-per-page-text="$vuetify.dataTable.itemsPerPageText"

                @update:options="handlePageOptions"
            />
        </template>
        <template v-slot:item="{item, isSelected, select}">

            <tr>
                <td>
                    <v-simple-checkbox
                        :value="isSelected"
                        :readonly="item.disabled"
                        :disabled="item.arena_details ? false : true"
                        @input="select($event)"
                    ></v-simple-checkbox>
                </td>
                <td>
                    <p class="font-weight-bold text-left">{{item.date_of_soa}}</p>
                </td>
                 <td>
                    <p class="font-weight-bold text-left">{{item.areaCode}}</p>
                </td>
                <td>
                    <p class="font-weight-bold text-left">{{item.refNo}}</p>
                </td>
                 <td>
                    <p class="font-weight-bold text-left">{{item.arena_name}}</p>
                </td>
                <td>
                    <v-tooltip top color="primary">
                        <template v-slot:activator="{ on, attrs, hover }">
                            <v-btn
                                icon
                                color="primary"
                                dark
                                small
                                v-bind="attrs"
                                v-on="on"
                                @click="openModal(item)"
                                :class="{
                                'on-hover': hover,
                                }"
                                :disabled="downloadingReport"
                            >
                                <v-icon>mdi-eye</v-icon>
                            </v-btn>
                        </template>
                        <span>View Account</span>
                    </v-tooltip>
                </td>
            </tr>

        </template>

    </v-data-table>
    <loading-progress :loading="loading" />
</div>
</template>
<script>
export default {
    name: "table-soax",
    props: {
        soaLists: Function, // FIXME: TO REMOVE
        withStatus: Function, // FIXME: TO REMOVE
        fetchLists: Function,
        loadDateRange: Function,
        handleSearching: Function,
        arenaData: Array,
        downloadingReport: Boolean,
        openModal: Function,
        search: String,
        total: Number,
        page: Number,
        perPage: Number,
        numberOfPages: Number,
        dates: Array,
        tab: String,
        handleNoArenaDetails: Function,
        filteredText: String
    },
    data: () => ({
        headers: [
            { text: "Date", value: "date_of_soa" ,sortable: false },
            { text: "Area Code", value: "areaCode", sortable: false  },
            { text: "Reference #", value: "refNo", sortable: false  },
            { text: "Arena Name", value: "arena_name" ,sortable: false },
            { text: "", value: "actions", sortable: false },
        ],
        itemsPerPageOptions: [5, 10, 20, 30, 40, 50, 100, 200],
        singleSelect: false,
        selectedItems: [],
        selected: [],
        loading: false,
        pagePosition: 1,

        paginationOption: {}
    }),
    methods: {
        // Select all imports with arena details
        selectAllToggle(props) {
            let dis = 0;
            this.selectedItems = props.items;
            props.items.map((x) => {
                if (!x.arena_details) dis += 1;
            });
            if (this.selected.length != props.items.length - dis) {
                this.selected = [];
                const self = this;
                props.items.forEach((item) => {
                    if (item.arena_details) {
                        self.selected.push(item);
                    }
                });
                console.log('ok',this.selected)
                this.$emit('selectedSoa', this.selected)
            } else {
                this.selected = []
                this.$emit('selectedSoa', [])
            };
        },
        resetTable(){
            console.log('xxxRESETxxxx')
            this.selected = []
        },
        singleSelected(item){
            this.$emit('selectedSoa', item)
        },

        async handlePaginate(e){
            this.pagePosition  = e.page
            console.log('Tabbbbb>>>>', this.tab)
                  this.$emit('loading', true)
                    await this.fetchLists(localStorage.getItem('site'));
                    this.$emit('loading', false)



        },
        async handlePageCount(e){

            const perPage = Math.ceil((this.total/e))

            this.$emit('perPage', perPage)

        },

        handlePageOptions(e){
            console.log('handlePageOptions',e.itemsPerPage)

            e.itemsPerPage && localStorage.setItem('itemsPerPage', e.itemsPerPage)
            this.$emit('pageOption', e)

            this.paginationOption = {
              ...e,
              itemsPerPage: this.perPage,
            }


        },
        pageOptions(){
            console.log('pageOptions')
            // this.paginationOption = {
            //   ...this.paginationOption,
            //   // page: parseInt(localStorage.getItem('page')),
            //   itemsPerPage: parseInt(localStorage.getItem('itemsPerPage')),
            // }
        },
        pageReset(){
           return this.paginationOption = {
              ...this.paginationOption,
              page: 1,
              itemsPerPage: parseInt(localStorage.getItem('itemsPerPage')),

            }
        }

    },
    computed: {
        perPagee() {

            const paginationOpt = {
                ...this.paginationOption,
                itemsPerPage: this.perPage,
            }


            return paginationOpt
        }
    },
    created(){
      this.pageReset()
    }
};
</script>
<style lang=""></style>
