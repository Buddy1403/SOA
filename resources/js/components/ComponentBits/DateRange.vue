<template lang="">
   <v-col class="col-md-3">
        <v-menu
            ref="menu"
            v-model="menu"
            :close-on-content-click="false"
            :return-value.sync="dates"
            transition="scale-transition"
            offset-y
            min-width="auto"
            color="#8DA90B"
        >
            <template v-slot:activator="{ on, attrs }">
                <v-text-field
                        color="#8DA90B"
                        background-color="bg-white cleaar-shadow caption"
                        v-model="dateRangeText"
                        outlined
                        dense
                        label="Select Date Range"
                        prepend-inner-icon="mdi-calendar"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                        clearable
                        @click:clear="handleClear"
                    >
                    <template v-slot:prepend-inner>        
                        <v-icon outlined dark color="#8DA90B">mdi-calendar</v-icon> 
                    </template>
                    
                </v-text-field>
            </template>
            <v-date-picker
                v-model="dates"
                no-title
                scrollable
                range
                :show-current="false"
                
            >
                <v-spacer></v-spacer>
                <v-btn
                    text
                    color="primary"
                    @click="handleClear"
                >
                    Clear
                </v-btn>
                <v-btn
                    text
                    color="primary"
                    @click="handleFilterDate(dates)"
                >
                    OK
                </v-btn>
            </v-date-picker>
        </v-menu>
     
    </v-col>
</template>
<script>
import {toOrderBy} from '../../utility'
import moment from "moment";
export default {
    name: 'date-range',
    props: {
        soaLists: Function,
        tab: String,
        pageNumber: Number,
        page: Number
    },
    data: ()=>({
        menu: false,
        dates: [],
        dateRangeTxt: '',
        loading: false,
      
    }),
    methods: {
        handleFilterDate(dates) {
            console.log(dates)
            this.$refs.menu.save(dates);
            if(dates.length === 1) dates.push(dates[0])
            this.$emit('dates', dates)
            // if(dates[1]){
            //     this.$emit('showClearBtn', true)
            //     this.loadDateRange();
            //     this.$emit('dates', dates)
            // }else{
            //     this.$emit('showClearBtn', false)

            // }
        },
        handleClear() {
            this.menu = false;
            this.$refs.menu.save([]);
            this.$emit('dates', [])
            this.$emit('tabs', 'ongoing')
            this.$emit('showClearBtn', false)
            this.soaLists();
        },

        // async loadDateRange(tabItem, page, perPage) {
        //     const status =  tabItem === 'ongoing' || (!tabItem && this.tab ==='ongoing') ? null : 'done'
        //     // DATE RANGE
        //     const endDate = moment(this.dates[1], "YYYY-MM-DD")
        //         .add(1, "days")
        //         .format("YYYY-MM-DD");
        //     const {data} = await axios.get(
        //         `api/importDateRange/${this.dates[0]}/${endDate}?page=${page}&per_page=${parseInt(localStorage.getItem('itemsPerPage'))}&status=${status}`
        //     );

        //     const depositReplenish = data.data.map(dp => {
        //          const arenaName =
        //         dp.arena_name.indexOf("~") > -1
        //         ? dp.arena_name.replace(/\~/g, "/")
        //         : dp.arena_name;

        //         const obj = {
        //             ...dp,
        //             arena_name: arenaName,
        //         };

        //         // return obj
        //     })

        //     this.$emit('depositReplenish', {
        //         depositReplenish: toOrderBy(depositReplenish),
        //         page: data.current_page,
        //         total: data.total,
        //         numberOfPages: data.last_page
        //     })
        // },
    },
    computed: {
            dateRangeText: {
                get() {
                    const dateRange = this.dates.length > 1 ? this.dates.sort() : null;
                    this.dateRangeTxt = dateRange ? dateRange.join(" ~ ") : null;
                    return this.dateRangeTxt
                },
                set(value) {
                    console.log('VALUE>>>',value)
                    this.dateRangeTxt = value
                }
           
        },
    }
}
</script>
<style scoped>
    .v-date-picker-table .v-btn { color: green };
</style>