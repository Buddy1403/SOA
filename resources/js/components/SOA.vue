<template>
    <v-app>
        <v-container :class="{ 'blur-content': dialog }">
            <p class="custom-text">
                Statement of Accounts</p>
            <!-- Arena Modal -->
            <email-loading v-if="sendingEmail"></email-loading>
            <arena-modal :arenaNames="arenaName" :soaLists="soaLists">
            </arena-modal>
            <v-row class="mt-3">
                <v-col class="col-md-12">
                    <v-row>
                        <!-- DATE RANGE -->
                        <date-range
                            @depositReplenish="handleFilterDate"
                            :soaLists="soaLists"
                            @dates="getDates"
                            @tabs="revertTab"
                            ref="dateRange"
                            :tab.sync="tab"
                            @showClearBtn="handleClearBtn"
                            :pageNumber="pageNumber"
                            :page="page"
                        ></date-range>
                        <!-- Search Input -->
                         <v-col class="col-md-2">
                            <v-text-field
                                v-model="searchArenaParams"
                                outlined
                                dense
                                label="Search"
                                color="#8DA90B"
                                clearable
                                @click:clear="handleClear"
                                v-on:keyup.enter="searchCentralize()"
                            >
                            <template v-slot:prepend-inner>
                                <v-icon outlined dark color="#8DA90B">mdi-magnify</v-icon>
                            </template>
                         </v-text-field>
                        </v-col>
                        <!-- <search-soa
                            @searchData="handleSearch"
                            @searchParams = "searchParams"
                            :tab="tab"
                            :page="pageNumber"
                            :soaLists="soaLists"
                            :importWithStatus="importWithStatus"
                            ref="search"
                        ></search-soa> -->
                        <!-- Filter WIth/Without ARENA Details -->
                        <filter-arena
                            :arenaData="arenaData"
                            :loadDateRange="loadDateRange"
                            :page="pageNumber"
                            :tab="tab"
                            :dates="dates"
                            :fetchLists="handleFetchLists"
                            :soaLists="soaLists"
                            :importWithStatus="importWithStatus"
                            @noArenaDetails="noArenaDetails"
                            @filterText="filterText"
                            ref="filterArena"
                        ></filter-arena>

                        <filter-site
                            :loadDateRange="loadDateRange"
                            :page="pageNumber"
                            :tab="tab"
                            :dates="dates"
                            :soaLists="soaLists"
                            :importWithStatus="importWithStatus"
                            :fetchLists="handleFetchLists"
                            @arenaPerSite="arenaPerSite"

                            ref="site"
                        ></filter-site>
                        <!-- FILE INPUT -->
                        <soa-input :soaLists="soaLists"></soa-input>
                    </v-row>
                    <template>
                        <v-btn
                            rounded
                            color="#8DA90B"
                            dark
                            class="mb-5 w-100"
                            @click="searchCentralize()"
                        >
                        <i class="fa fa-search circle-icon"></i>
                            S E A R C H
                        </v-btn>
                    </template>
                    <v-card class="custom-tbl">
                        <v-card-title>
                            <v-row>
                                <v-col class="col-md-6">
                                    <v-switch
                                        v-model="switchPrepared"
                                        color="#8DA90B"
                                        inset
                                        @change="handleSwitchSignatory"
                                        flat
                                        dense
                                    >
                                    <template v-slot:label>
                                        <span :style="[
                                                    switchPrepared ? {'color':'#8fa900'} : ''
                                                     ]">
                                            Signatory {{switchPrepared ? 'On' : 'Off' }}
                                        </span>
                                    </template>
                                    </v-switch>
                                </v-col>
                                <v-col class="col-md-6" align="right">
                                    <div>
                                         <!-- Downloads and Clear Buttons -->
                                            <actions-buttons
                                                :selected="selected"
                                                :showClear="showClear"
                                                :dates="dates"
                                                :tab="tab"
                                                :arenaData="arenaData"
                                                :handleEmptySelect="handleEmptySelect"
                                                :soaLists="soaLists"
                                                :importWithStatus="importWithStatus"
                                                :fetchLists="handleFetchLists"
                                                :loadDateRange="loadDateRange"
                                                :printReadyProgress="printReadyProgress"
                                                :handleSendingEmail="handleSendingEmail"
                                            />
                                    </div>
                                </v-col>
                            </v-row>
                        </v-card-title>
                        <!-- TAB -->
                        <v-tabs
                            color="#8DA90B"
                            v-model="tab"
                            @change="handleChangeTab"
                            class="p-3"
                        >
                            <v-spacer></v-spacer>

                            <v-tab
                                v-for="item in items"
                                class="custom-tabs"
                                :key="item.tabItem"
                                :href="`#${item.tabItem}`"
                                @click="handleEmptySelect"
                                :disabled="
                                    filteredText === 'noArenaDetails' || dialog2
                                        ? true
                                        : false
                                "
                            >
                               <span class="custom-tab-span"> {{ item.text }}</span>
                            </v-tab>
                        </v-tabs>

                        <v-card-text>
                            <v-tabs-items v-model="tab">
                                <v-tab-item
                                    v-for="item in items"
                                    :key="item.tabItem"
                                    :id="item.tabItem"
                                >
                                    <!-- Table for ongoing soa -->
                                    <!-- ref="tableArenaOnGoing" -->

                                    <table-soa
                                        :arenaData="arenaData"
                                        :downloadingReport="downloadingReport"
                                        @selectedSoa="handleSelected"
                                        :search="search"
                                        :openModal="openModal"
                                        :tab="tab"
                                        :filteredText="filteredText"
                                        :dates="dates"
                                        :total="total"
                                        :page="page"
                                        :perPage="perPage"
                                        :numberOfPages="numberOfPages"
                                        :soaLists="soaLists"
                                        :withStatus="importWithStatus"
                                        :fetchLists="handleFetchLists"
                                        :loadDateRange="loadDateRange"
                                        :handleSearching="handleSearching"
                                        :handleNoArenaDetails="
                                            handleNoArenaDetails
                                        "
                                        @loading="handlePageLoad"
                                        @pageOption="pageOption"
                                    ></table-soa>
                                </v-tab-item>

                            </v-tabs-items>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col>
                    <soa-cards :selected="selected"></soa-cards>
                </v-col>
                <v-dialog
                    v-model="dialog"
                    transition="dialog-bottom-transition"
                    content-class="my-custom-dialog"
                    scrollable
                    persistent
                    width="800px"
                    style="z-index: 9999999999 !important"
                >
                    <!-- SOA Container for single download ng print -->
                    <v-card
                        class="overflow-y-auto overflow-x-hidden report-preview"
                    >
                        <v-row>
                            <v-col class="cols-12 pa-0">
                                <v-card
                                    class="pa-10 overflow-auto"
                                    rounded="false"
                                    style="height: 100%"
                                >
                                    <div>
                                        <v-spacer></v-spacer>
                                        <div
                                            style="
                                                position: absolute;
                                                right: 10px;
                                                top: 15px;
                                            "
                                        >
                                            <v-tooltip bottom>
                                                <template
                                                    v-slot:activator="{
                                                        on,
                                                        attrs,
                                                    }"
                                                >
                                                    <v-chip
                                                        small
                                                        class="ma-2"
                                                        close
                                                        color="red"
                                                        text-color="white"
                                                        v-bind="attrs"
                                                        v-on="on"
                                                        @click="closeDialog"
                                                    >
                                                        Close
                                                    </v-chip>
                                                </template>
                                                <span>Close</span>
                                            </v-tooltip>
                                        </div>
                                    </div>
                                    <div style="padding: 1px" ref="soaReport">
                                        <vue-html2pdf
                                            :show-layout="false"
                                            :float-layout="false"
                                            :enable-download="false"
                                            :preview-modal="false"
                                            :filename="arenaDetails.arena"
                                            :pdf-quality="2"
                                            :image="{
                                                type: 'jpeg',
                                                quality: 1,
                                            }"
                                            :manual-pagination="true"
                                            pdf-format="letter"
                                            pdf-orientation="portrait"
                                            pdf-content-width="90%"
                                            ref="html2Pdf"
                                            class="vuehtmlpdf"
                                            @beforeDownload="
                                                beforeDownload($event)
                                            "
                                        >
                                            <section
                                                slot="pdf-content"
                                                class="pdf-content"
                                                id="printingSOA"
                                            >
                                                <v-card-title
                                                    class="text-h5 text-center font-weight-medium d-flex justify-center align-center pdf-title"
                                                >
                                                    <span>{{
                                                        depRep
                                                            .depositReplenishText
                                                            .title
                                                    }}</span>
                                                </v-card-title>
                                                <v-card-text>
                                                    <v-row>
                                                        <v-spacer></v-spacer>
                                                        <v-spacer></v-spacer>
                                                        <DateSOA
                                                            :depositReplenishText="
                                                                depRep.depositReplenishText
                                                            "
                                                            :refNo="refNo"
                                                            :dateEvent="
                                                                dateEvent
                                                            "
                                                            :dateSoa="
                                                                dateCreated
                                                            "
                                                        />
                                                    </v-row>
                                                    <v-row>
                                                        <ArenaDetails
                                                            :arenaDetails="
                                                                arenaDetails
                                                            "
                                                            :arenaName="
                                                                arenaName
                                                            "
                                                            :emailFormat="
                                                                emailFormat
                                                            "
                                                            :contactFormat="
                                                                contactFormat
                                                            "
                                                        />
                                                    </v-row>
                                                    <v-row>
                                                        <div
                                                            class="computation-banner"
                                                        >
                                                            Computation
                                                        </div>
                                                    </v-row>
                                                    <v-row>
                                                        <ComputeBox
                                                            :depositReplenishTxt="
                                                                depRep.depositReplenishText
                                                            "
                                                            :commissionPercent="
                                                                parseFloat(
                                                                    commission_percent
                                                                )
                                                            "
                                                            :computation="
                                                                computation
                                                            "
                                                        />
                                                    </v-row>

                                                    <v-row>
                                                        <span
                                                            v-if="
                                                                depRep
                                                                    .depositReplenishText
                                                                    .dateText ===
                                                                'FR'
                                                            "
                                                            class="fr-notif"
                                                            >Please be advised
                                                            that replenishment
                                                            are only available
                                                            during banking days.
                                                            We allow offsetting
                                                            of pending
                                                            remittances and
                                                            replenishments
                                                            during non-banking
                                                            days.</span
                                                        >
                                                        <BankBox
                                                            :bankAccounts="
                                                                bankAccounts ||
                                                                []
                                                            "
                                                            :arenaDetails="
                                                                arenaDetails
                                                            "
                                                            :operatorName="
                                                                operator_name
                                                            "
                                                            :depositReplenishText="
                                                                depRep.depositReplenishText
                                                            "
                                                        />

                                                    </v-row>

                                                    <SignatoryBox
                                                        v-if="switchPrepared"
                                                        :arenaDetails="
                                                            arenaDetails
                                                        "
                                                    />

                                                </v-card-text>
                                            </section>
                                        </vue-html2pdf>
                                    </div>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>

                                        <v-spacer></v-spacer>

                                        <v-tooltip bottom>
                                            <template
                                                v-slot:activator="{ on, attrs }"
                                            >
                                                <v-btn
                                                    icon
                                                    large
                                                    color="green"
                                                    v-bind="attrs"
                                                    v-on="on"
                                                    @click="
                                                        downloadImg(
                                                            arenaDetails,
                                                            codeEvent
                                                        )
                                                    "
                                                >
                                                    <v-icon>mdi-image</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Download as PNG</span>
                                        </v-tooltip>
                                        <v-tooltip bottom>
                                            <template
                                                v-slot:activator="{ on, attrs }"
                                            >
                                                <v-btn
                                                    icon
                                                    large
                                                    color="red darken-2"
                                                    v-bind="attrs"
                                                    v-on="on"
                                                    :loading="loading"
                                                    :disabled="loading"
                                                    @click="
                                                        generateReport(
                                                            codeEvent
                                                        )
                                                    "
                                                >
                                                    <v-icon
                                                        >mdi-file-pdf-box</v-icon
                                                    >
                                                </v-btn>
                                            </template>
                                            <span>Download as PDF</span>
                                        </v-tooltip>
                                        <v-tooltip bottom>
                                            <template
                                                v-slot:activator="{ on, attrs }"
                                            >
                                                <v-btn
                                                    icon
                                                    large
                                                    color="primary"
                                                    v-bind="attrs"
                                                    v-on="on"
                                                    :loading="loading"
                                                    :disabled="loading"
                                                    @click="
                                                        printSoa('printingSOA')
                                                    "
                                                >
                                                    <v-icon>mdi-printer</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>PRINT</span>
                                        </v-tooltip>
                                        <v-tooltip bottom>
                                            <template
                                                v-slot:activator="{ on, attrs }"
                                            >
                                                <v-btn
                                                    icon
                                                    large
                                                    color="yellow"
                                                    v-bind="attrs"
                                                    v-on="on"
                                                    :loading="loading"
                                                    :disabled="loading"
                                                    @click="
                                                        sendEmail(arenaDetails,depRep.depositReplenishText)
                                                    "
                                                >
                                                    <v-icon>mdi-email</v-icon>
                                                </v-btn>
                                            </template>
                                            <span>Send via Email</span>
                                        </v-tooltip>
                                    </v-card-actions>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-card>
                </v-dialog>
            </v-row>

            <!-- Loading Progress -->
            <loading-progress :loading="dialog2"></loading-progress>
        </v-container>
        <div class="floating-button" v-show="scY > 150">
            <v-tooltip top color="#000">
                <template v-slot:activator="{ on, attrs }">
                    <v-btn
                        v-bind="attrs"
                        v-on="on"
                        fab
                        dark
                        bottom
                        right
                        @click="toTop"
                    >
                        <v-icon>mdi-chevron-up</v-icon>
                    </v-btn>
                </template>
                <span>Go to top</span>
            </v-tooltip>
        </div>
    </v-app>
</template>
<script>
import {
    numberUnformat,
    moneyFormat,
    defineEmail,
    defineContact,
}
from "../utility";
import VueHtml2pdf from "vue-html2pdf";
import moment from "moment";
import DateSOA from "./SoaComponents/DateSOA.vue";
import ArenaDetails from "./SoaComponents/ArenaDetails.vue";
import ComputeBox from "./SoaComponents/ComputeBox.vue";
import BankBox from "./SoaComponents/BankBox.vue";
import SignatoryBox from "./SoaComponents/SignatoryBox.vue";
import DateRange from "./ComponentBits/DateRange.vue";
import TableSoa from "./ComponentBits/TableSoa.vue";
import SoaInput from "./ComponentBits/SoaInput.vue";
import ActionsButtons from "./ComponentBits/ActionsButtons.vue";
import SearchSoa from "./ComponentBits/SearchSoa.vue";
import FilterArena from "./ComponentBits/FilterArena.vue";
import FilterSite from "./ComponentBits/FilterSite.vue";
import EmailLoading from './ComponentBits/EmailLoading.vue'
import SoaCards from './SoaComponents/SoaCards.vue'


import {
    imageDownload,
    truncate,
    beforeDownload,
    withStatus,
    soa,
    reportGenerate,
    computationSoa,
    printSoa,
    sendEmailwithImage,
} from "../methods";

import ArenaModal from "./modal/ArenaModal.vue";


export default {
    components: {
        VueHtml2pdf,
        DateSOA,
        ArenaDetails,
        ComputeBox,
        BankBox,
        SignatoryBox,
        ArenaModal,
        DateRange,
        TableSoa,
        FilterArena,
        SoaInput,
        ActionsButtons,
        SearchSoa,
        FilterSite,
        EmailLoading,
        SoaCards
    },
    data() {
        return {
            tab: null,
            items: [
                {
                    text: "On going",
                    tabItem: "ongoing",
                },
                {
                    text: "converted",
                    tabItem: "converted",
                },
            ],
            sortBy: "refNo",
            keys: ["CATEGORY"],
            arenaName: "",
            selected: [],
            dialog: false,
            dialog2: false,
            search: "",
            commission_percent: 0.02,
            bankAccounts: [],
            operator_name: "",
            sofrNumSeq: 0,
            arenaData: [],
            arenaOnGoing: [],
            arenaDatastatus: [],
            arenaDetails: {},
            areaCode: "",
            codeEvent: "",
            loading: false,
            loader: null,
            downloadingReport: false,
            fileUpload: null,
            moment,
            moneyFormat,
            numberUnformat,
            defineEmail,
            defineContact,
            computation: {},
            refNo: "",
            soa: true,
            dateCreated: "",
            dateEvent: "",
            emailFormat: "",
            contactFormat: "",
            depositReplenishTxt: {},
            printSoa,
            switchPrepared: false,
            menu: false,
            dates: [],
            showClear: false,
            computationSoa,
            signsArray: [],
            progressText: null,
            printReadyProgress: 100,
            total: 0,
            page: 0,
            numberOfPages: 0,
            perPage: 0,
            pageNumber: 1,
            filteredText: "",
            site: "",
            scTimer: 0,
            scY: 0,
            selectsited: '',
            selectedDated:'',
            sendingEmail: false,
            EmailCC: [],
            searchArenaParams:'',
            tabParams:[],
        };
    },
    methods: {

        async HandlerCc(){
            const {data} = await axios.get('api/emails')
            this.selectedCc = data.filter(ec => ec.isUse !== 0)
            this.EmailCC =  this.selectedCc.map(selectedCc => {
                return selectedCc.email_cc
            });

        },
        async sendEmail(details, type){
            const el = this.$refs.soaReport;
            const imgdl = await sendEmailwithImage(details, this.dateEvent, this.codeEvent,el,type.title,this.EmailCC);
            if (imgdl.status === 200) {
                this.dialog = false;
                this.arenaDetails = {};
                this.handleFetchLists();
                swal.fire("Email Send!", "successfully", "success");
            }


        },
        handleSwitchSignatory() {
            // switch between to have signatory or not
            localStorage.setItem("prepared", this.switchPrepared);
        },
        truncate, // truncate data based on date
        async soaLists(site, dates) {
            this.selectsited = site;
            this.selectedDated = dates;
            // console.log('SOALISTxxxxx', site)
            // fetch all soa with status = null
            const perPage = parseInt(localStorage.getItem("itemsPerPage"));
            const sited = site
            const { soaLists, total, page } = await soa(
                sited,
                this.pageNumber,
                perPage,
                null,
                dates
            );
            this.perPage = perPage;
            this.arenaData = soaLists;
            this.total = total;
            this.page = page;
        },
        async importWithStatus(site, dates) {
            this.selectsited = site;
            this.selectedDated = dates;
            // console.log('SOALISTxxxxx', site)
            // fetch data with status = done

            const perPage = parseInt(localStorage.getItem("itemsPerPage"));
            const sited = site
            this.perPage = perPage;
            const { withStatusData, total, page } = await withStatus(
                sited,
                this.pageNumber,
                perPage,
                'done',
                dates
            );

            this.arenaData = withStatusData;
            this.total = total;
            this.page = page;
        },

        openModal(data) {
            // View SOA dialog
            // console.log(data.arena_details);
            if (data.arena_details === null) {
                $("#addNew").modal("show");

                this.arenaName = data.arena_name;
            } else {
                this.dialog = true;
                this.operator_name = data.arena_details.operator;
                this.dateCreated = moment(data.date_closed).format("LL");
                this.dateEvent = moment(data.date_of_soa).format("LL");
                this.refNo = data.refNo;
                this.arenaDetails = data.arena_details;
                this.arenaName = data.arena_name;
                this.areaCode = data.areaCode;
                this.codeEvent = data.codeEvent;
                const computation = computationSoa(data);
                this.computation = computation;
            }

            // Format email and contact to string
            this.emailFormat =
                data.arena_details &&
                data.arena_details.email_details &&
                this.defineEmail(data && data.arena_details.email_details);

            this.contactFormat =
                data.arena_details &&
                data.arena_details.contact_details &&
                this.defineContact(data && data.arena_details.contact_details);
        },
        closeDialog() {
            this.dialog = false;
            this.arenaDetails = {};
            this.operator_name = "";

            $(".computation").attr("disabled", true);
        },
        async handleFetchLists(site) {
             if (this.dates.length < 1 && this.search) {
                await this.searchCentralize();
            } else if (
                this.filteredText === "noArenaDetails" &&
                this.dates.length < 1 &&
                !this.search
            ) {
                await this.handleNoArenaDetails(this.filteredText);
            }
            else if ( this.filteredText === "noArenaDetails" &&
                this.dates.length > 1 ) {
                    await this.handleNoArenaDetails(this.filteredText, this.dates);

            }
            else if ( site &&
                this.tab === "ongoing" &&
                this.dates.length > 1 &&
                !this.search){

                    await this.searchCentralize(site, this.dates);
            }
            else if ( site &&
                this.tab === "converted" &&
                this.dates.length > 1 &&
                !this.search){
                    await this.soaLists(site, this.dates);
            }
            else if (
                this.tab === "ongoing" &&
                this.dates.length < 1 &&
                !this.search
            ) {

                await this.searchCentralize(site);
            }
            else if (
                site &&
                this.tab === "converted" &&
                this.dates.length < 1 &&
                !this.search
            ) {
                await this.importWithStatus(site);
            }
            else if (this.dates.length > 1 && !this.search) {
                await this.searchCentralize();
            } else {
                // console.log("SEARCH", this.search);
            }
        },
        // Generate a pdf report
        generateReport(codeEvent) {
            const { dialog } = reportGenerate(codeEvent, this.$refs.html2Pdf);
            this.handleFetchLists();
            this.dialog = dialog;
        },
        beforeDownload, // Customize PDF before download

        async downloadImg(details, codeEvent) {
            // Download PNG
            const el = this.$refs.soaReport;
            const imgdl = await imageDownload(details, codeEvent, el);
            if (imgdl.status === 200) {
                this.dialog = false;
                this.arenaDetails = {};
                this.handleFetchLists();
                swal.fire("convert to png!", "successfully", "success");
            }
        },

        loadBankDetails() {
            // Load Company Bank Details
            axios.get("api/Companybanks").then(({ data }) => {
                this.bankAccounts = data;
            });
        },

        handleFilterDate(item) {
            // $emit filter soa import by date from date-range component
            this.arenaData = item.depositReplenish;
            this.total = item.total;
            this.page = item.page;
            this.numberOfPages = item.numberOfPages;
            if (item.depositReplenish.length === 0) this.showClear = false;
        },
        handleClearBtn(value) {
            // $emit clear dates from date-range component
            this.showClear = value;
        },

        async handleClear(){
            this.tab === 'ongoing' ? await this.soaLists() : await this.importWithStatus()
        },

        handleSelected(value) {
            // $emit Selected imports from table-soa component
            this.selected = value;

            console.log("SELECTED", value);
            if (value.length < 1)
                (this.signsArray = []), (this.printReadyProgress = 100);
        },
        getDates(value) {
            // $emit get dates from date-range component
            this.dates = value;
            // console.log(this.dates);
        },

        revertTab(item) {
            // $emit return to default menu tab (ongoing) from date-range component
            this.tab = item;
        },
        handleEmptySelect() {
            // unselect all selected imports
            this.printReadyProgress = 0;
            this.signsArray = [];
            this.$refs.tableArenaOnGoing &&
                this.$refs.tableArenaOnGoing.resetTable();
            this.$refs.tableArenaConverted &&
                this.$refs.tableArenaConverted.resetTable();
        },

        async loadDateRange(item) {
            // Load imports based on date range
            this.$refs.dateRange &&
                (await this.$refs.dateRange.loadDateRange(
                    item,
                    this.pageNumber,
                    this.perPage
                ));
        },
        async handleChangeTab(item) {
            this.tabParams = item;
            // Swicth between menu tab: ongoing and converted
            // console.log('switch tab', this.selectsited);

            this.pageNumber = 1;
            this.signsArray = [];
            const perPage = parseInt(localStorage.getItem("itemsPerPage"));
            this.search
                ? this.handleSearching(item)
                    : this.dates.length !== 0
                ? this.loadDateRange(item, this.pageNumber, perPage)
                    : item === "ongoing"
                ? await this.soaLists(this.selectsited, this.selectedDated )
                    : await this.importWithStatus(this.selectsited, this.selectedDated );


            this.loadBankDetails();
        },
        handleSigned(value) {
            // $emit signatories data from signatory component
            this.printReadyProgress = Math.ceil(
                ((this.signsArray.length + 1) / (this.selected.length * 3)) *
                    100
            );
            this.signsArray.push(value);
        },
        handlePageLoad(value) {
            this.dialog2 = value;
        },

        pageOption(item) {
            this.pageNumber = item.page;
            this.perPage = item.itemsPerPage;
        },
        handleSearching(item) {
            this.$refs.search.handleSearch(item);

        },
        handleSearch(items) {
            this.search = items.search;
            this.arenaData = items.searchData;
            this.total = items.total;
            this.page = items.page;
        },

       async searchCentralize(site){
            const tabItem = this.tab;
            const to = moment(this.dates[1], "YYYY-MM-DD")
            .add(1, "days")
            .format("YYYY-MM-DD");

            const status =  tabItem === 'ongoing' || (!tabItem && this.tab ==='ongoing') ? null : 'done'
            const {data} = await axios.get(`api/searchSoa?&search=${this.searchArenaParams}&site=${site}&status=${status}&dateFrom=${this.dates[0]}&dateTo=${to}&page=${this.pageNumber}&per_page=${parseInt(localStorage.getItem('itemsPerPage'))}`);

            this.arenaData = data.data;
            console.log('data',data);
            return data;
        },
        noArenaDetails(item) {
            this.arenaData = item.noArenaData;
            this.total = item.total;
            this.page = item.current_page;
        },
        handleNoArenaDetails(dates) {
            this.$refs.filterArena.handleSelectionFilterArena(
                this.filteredText,
                dates
            );
        },
        filterText(item) {
            this.filteredText = item;
        },

        arenaPerSite(item){
            this.arenaData = item.arenaData
            this.total = item.total;
            this.page = item.current_page;
        },


        //scrolltotop
        handleScroll() {
            if (this.scTimer) return;
            this.scTimer = setTimeout(() => {
                this.scY = window.scrollY;
                clearTimeout(this.scTimer);
                this.scTimer = 0;
            }, 100);
        },
        toTop() {
            window.scrollTo({
                top: 0,
                behavior: "smooth",
            });
        },
        handleSendingEmail(){
            this.sendingEmail = true;
            setTimeout(() => {
                this.sendingEmail = false
            }, 5000)
        }
    },

    computed: {
        dateRangeText() {
            // modify date string
            const dateRange = this.dates.length > 1 ? this.dates.sort() : null;
            return dateRange ? dateRange.join(" ~ ") : null;
        },
        depRep: function () {
            // switch between for replenishment and soa text and other details
            const depositReplenishText =
                numberUnformat(this.computation.depositReplenish) < 0
                    ? {
                          title: "For Replenishment",
                          dateText: "FR",
                          totalText: "Replenish",
                          number:
                              "FR" + moment().format("MMDYY") + this.sofrNumSeq,
                          bankTitle: "We will replenish to",
                      }
                    : {
                          title: "Statement of Account",
                          dateText: "SOA",
                          totalText: "Deposit",
                          number:
                              "SOA" +
                              moment().format("MMDYY") +
                              this.sofrNumSeq,
                          bankTitle: "Kindly Deposit to",
                      };

            return {
                depositReplenishText,
            };
        },
    },
    created() {
        this.HandlerCc();
        // console.log("MOUNTED", this.perPage);
        if (localStorage.getItem("itemsPerPage") === "NaN")
            localStorage.setItem("itemsPerPage", this.perPage);
    },
    mounted() {
        //     localStorage.removeItem('itemsPerPage')
        if (
            localStorage.getItem("itemsPerPage") === null ||
            JSON.parse(localStorage.getItem("itemsPerPage")) < 1
        )
            localStorage.setItem("itemsPerPage", 10);

        if (localStorage.getItem("site") === null) localStorage.setItem("site", 'all');

        if (localStorage.getItem("prepared")) {
            try {
                this.switchPrepared = JSON.parse(
                    localStorage.getItem("prepared")
                );
            } catch (e) {
                localStorage.removeItem("prepared");
            }
        }

        window.addEventListener("scroll", this.handleScroll);
    },

};

</script>
<style scoped>

.floating-button {
    position: fixed;
    bottom: 7%;
    right: 20px;
}
.custom-text{
    font-style: normal;
    font-weight: 600;
    font-size: 28px;
    line-height: 34px;
    color:#8DA90B;
}

.custom-tbl{
    border: 2px solid #8DA90B;
    border-radius: 20px;
}

.vtabs{
    width: 315px;
    margin-left:80%;
    overflow: hidden;
}

</style>
