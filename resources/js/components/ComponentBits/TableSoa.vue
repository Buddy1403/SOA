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
        <template v-slot:top="{ pagination, options, updateOptions, itemsPerPageOptions }">
            <v-data-footer
                :items-per-page-options ="[5, 10, 20, 30, 40, 50, 100]"
                :pagination="pagination"
                :options="perPagee"
                items-per-page-text="$vuetify.dataTable.itemsPerPageText"

                @update:options="handlePageOptions"
            />
        </template>


        <template  style="font-size:16px;"  v-for="header in headers" v-slot:[`header.${header.value}`]="{ header }">
             <v-icon medium color="#8DA90B">{{header.icon}}</v-icon>
             <span style="color:#8DA90B"> {{ header.text }} </span>
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
                <td >
                    <div class="d-flex">
                        <v-tooltip top color="primary">
                            <template v-slot:activator="{ on, attrs, hover }">
                                <v-btn

                                    color="#0B2EA9"
                                    dark
                                    small
                                    v-bind="attrs"
                                    v-on="on"
                                    @click="openModal(item)"
                                    :class="{
                                    'on-hover': hover,
                                    }"
                                    :disabled="downloadingReport"
                                    class='mr-2 custom-button'
                                >
                                    View
                                    <!-- <v-icon>mdi-eye</v-icon> -->
                                </v-btn>
                            </template>
                            <span>View Account</span>
                        </v-tooltip>
                        <v-tooltip top color="red">
                            <template v-slot:activator="{ on, attrs, hover }">
                                <v-btn
                                    color="#FF1313"
                                    class="custom-button"
                                    dark
                                    small
                                    v-bind="attrs"
                                    v-on="on"
                                    @click="deleteOneSoa(item.id)"
                                    :class="{
                                    'on-hover': hover,
                                    }"
                                    :disabled="downloadingReport"
                                >
                                    Delete
                                    <!-- <v-icon>mdi-trash-can</v-icon> -->
                                </v-btn>
                            </template>
                            <span>Delete Account</span>
                        </v-tooltip>
                    </div>
                </td>
            </tr>
        </template>

    </v-data-table>
    <loading-progress :loading="loading" />
</div>
</template>
<script>
export default {
    name: "table-soa",
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
            { text: "Date", value: "date_of_soa" , icon: 'mdi-calendar',sortable: false },
            { text: "Area Code", value: "areaCode",  icon: 'mdi-map-marker-radius',sortable: false  },
            { text: "Reference #", value: "refNo", icon: 'mdi-pound-box', sortable: false  },
            { text: "Arena Name", value: "arena_name" ,  icon: 'mdi-alpha-a-circle',sortable: false },
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
        //delete specific soa
       deleteOneSoa(id){
            swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete('api/deleteOneSoa/'+ id).then((data)=>{
                        console.log('data',data)
                        this.$emit('loading', true)
                        this.soaLists();
                        this.$emit('loading', false)

                    });

                }
                })
        },
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
                // console.log('Tabbbbb>>>>', this.tab)
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
<style scoped>
    .custom-button{
        border: 1px solid #000000;
        box-sizing: border-box;
        box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
        border-radius: 30px;
    }
</style>
