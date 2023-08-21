<template>
    <v-app style="height: 100%">
        <v-container style="height: auto">
            <!-- <h1 class="h3">Arena Details</h1> -->
            <v-row class="is-blurred">
                <v-col class="col-md-12">
                    <v-row>
                        <v-col>
                            <h1 class="custom-color mb-5">Arena Details</h1>
                            <form>
                                <v-row>
                                    <v-col>
                                        <v-btn
                                            color="#8DA90B"
                                            dark
                                            elevation="2"
                                            @click="openModal"
                                            >
                                            <i class="fas fa-plus fa-fw circle-icon"></i>
                                            Add Arena
                                        </v-btn>
                                        <v-btn
                                            color="#8DA90B"
                                            dark
                                            elevation="2"
                                            @click="downloadArenaDetails"
                                        >
                                        <i class="fas fa-download circle-icon"></i>
                                        Download Arena Masterlist
                                        </v-btn>
                                        <v-btn
                                            color="#8DA90B"
                                            dark
                                            elevation="2"
                                            @click="clearArena"
                                        >
                                        <i class="fas fa-trash circle-icon"></i>
                                        ClEAR ARENA LIST
                                        </v-btn>
                                    </v-col>
                                    <v-col>
                                        <v-file-input
                                            outlined
                                            dense
                                            v-model="fileUpload"
                                            color="#8DA90B"
                                            label="File input"
                                            :clearable="false"
                                            placeholder="Select your file"
                                            counter
                                            append-icon="mdi-file-import"
                                            :show-size="1000"
                                            @change="onFileChange($event)"
                                        >
                                            <template v-slot:append>
                                                <v-tooltip
                                                    bottom
                                                    color="success"
                                                >
                                                    <template
                                                        v-slot:activator="{
                                                            on,
                                                        }"
                                                    >
                                                        <v-icon
                                                            :disabled="!isExcel"
                                                            v-on="on"
                                                            color="green darken-3"
                                                            style="
                                                                cursor: pointer;
                                                            "
                                                            @click="
                                                                proceedAction
                                                            "
                                                        >
                                                            mdi-file-import
                                                        </v-icon>
                                                    </template>
                                                    <span>Import File</span>
                                                </v-tooltip>
                                            </template>
                                            <template
                                                v-slot:selection="{
                                                    index,
                                                    text,
                                                }"
                                            >
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
                                    </v-col>
                                </v-row>
                            </form>

                            <v-text-field
                                v-model="search"
                                label="Search Arena"
                                color="#8DA90B"
                                clearable
                            >
                                <template v-slot:prepend-inner>
                                    <v-icon outlined dark color="#8DA90B">mdi-magnify</v-icon>
                                </template>
                            </v-text-field>
                            <v-data-table
                                :headers="headers"
                                :items="arena"
                                :items-per-page="10"
                                :search="search"
                                class="elevation-1 text-center custom-tbl"
                            >
                                <template  style="font-size:16px;"  v-for="header in headers" v-slot:[`header.${header.value}`]="{ header }">
                                    <v-icon medium color="#8DA90B">{{ header.icon }}</v-icon>
                                    <span style="color:#8DA90B"> {{ header.text }} </span>
                                </template>

                                <template v-slot:[`item.team`]="{ item }">
                                    <span>{{
                                        item.team && item.team.toUpperCase()
                                    }}</span>
                                </template>
                                <template v-slot:[`item.bank`]="{ item }">
                                    <v-tooltip
                                        top
                                        :color="
                                            item.bank_details.length == 0
                                                ? 'gray'
                                                : 'green'
                                        "
                                    >
                                        <template
                                            v-slot:activator="{ on, attrs }"
                                        >
                                            <v-btn
                                                :class="
                                                    item.bank_details.length ==
                                                    0
                                                        ?'no custom-btn'
                                                        :'yes custom-btn'
                                                "
                                                dark
                                                icon
                                                v-bind="attrs"
                                                v-on="on"
                                                color="white"
                                                @click="openBankModel(item)"
                                            >
                                                View
                                            </v-btn>
                                        </template>
                                        <span>{{
                                            item.bank_details.length == 0
                                                ? "No bank Details Available"
                                                : "Bank Information"
                                        }}</span>
                                    </v-tooltip>
                                </template>

                                <template v-slot:[`item.actions`]="{ item }">
                                    <v-tooltip top color="orange">
                                        <template
                                            v-slot:activator="{ on, attrs }"
                                        >
                                            <v-btn
                                                icon
                                                dark
                                                v-bind="attrs"
                                                v-on="on"
                                                class="edit custom-btn"
                                                @click="editModal(item)"
                                            >
                                                Update
                                            </v-btn>
                                        </template>
                                        <span>Update Arena</span>
                                    </v-tooltip>
                                    <v-tooltip top color="red">
                                        <template
                                            v-slot:activator="{ on, attrs }"
                                        >
                                            <v-btn
                                                icon
                                                dark
                                                v-bind="attrs"
                                                v-on="on"
                                                 class="delete custom-btn"
                                                @click="deleteArena(item.id)"
                                            >
                                                Delete
                                            </v-btn>
                                        </template>
                                        <span>Delete Arena</span>
                                    </v-tooltip>
                                </template>
                            </v-data-table>
                        </v-col>
                    </v-row>
                </v-col>
            </v-row>

            <!-- Bank Modal -->
            <div
                class="modal fade"
                id="bankModal"
                data-backdrop="static"
                tabindex="-1"
                role="dialog"
                aria-labelledby="addNewLabel"
                aria-hidden="true"
            >
                <div
                    class="modal-dialog modal-dialog-centered modal-lg"
                    role="document"
                >
                    <div class="modal-content custom-tbl">
                        <div class="modal-header">
                            <h5
                                class="modal-title custom-color"
                                v-show="!editmode"
                                id="addNewLabel"
                            >
                                Bank Details
                            </h5>
                            <h5
                                class="modal-title custom-color"
                                v-show="editmode"
                                id="addNewLabel"
                            >
                                Update Bank Details
                            </h5>

                            <button
                                type="button"
                                class="close"
                                data-dismiss="modal"
                                aria-label="Close"
                            >
                                <span class="custom-color" aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <v-data-table
                            :headers="Bankheaders"
                            :items="bankDetails"
                            :items-per-page="5"
                            :search="searchbank"
                            class="elevation-1 text-center"
                            style="border-radius:20px;"
                        >
                            <template  style="font-size:16px;"  v-for="header in Bankheaders" v-slot:[`header.${header.value}`]="{ header }">
                                <v-icon medium color="#8DA90B">{{ header.icon }}</v-icon>
                                <span style="color:#8DA90B"> {{ header.text }} </span>
                            </template>
                            <template v-slot:top>
                                <v-toolbar flat>
                                    <v-toolbar-title>
                                        <v-btn
                                            dark
                                            color="#8DA90B"
                                            elevation="2"
                                            @click="openBankDetails"
                                            ><i class="fas fa-plus fa-fw circle-icon"
                                            ></i> &nbsp;Add Bank
                                        </v-btn>
                                    </v-toolbar-title>
                                    <v-spacer></v-spacer>
                                    <div class="mt-4">
                                        <v-text-field
                                            v-model="searchbank"
                                            append-icon="mdi-magnify"
                                            label="Search"
                                            color="#8DA90B"
                                        ></v-text-field>
                                    </div>
                                </v-toolbar>
                            </template>
                            <template v-slot:[`item.modify`]="{ item }">
                                <!-- {{item.bank_activity.length}} -->
                                <div v-if="item.bank_activity.length >= 1">
                                    <strong>{{
                                        item.bank_activity[0].log_name
                                    }}</strong>
                                    {{ item.bank_activity[0].description }} it
                                    on
                                    {{
                                        item.bank_activity[0].created_at
                                            | myDatewithtime
                                    }}
                                </div>
                            </template>
                            <template v-slot:[`item.actions`]="{ item }">
                                <v-tooltip top>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn
                                            class="edit custom-btn"
                                            icon
                                            dark
                                            v-bind="attrs"
                                            v-on="on"
                                            @click="updateBankDetails(item)"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </v-btn>
                                    </template>
                                    <span>Edit Bank Info</span>
                                </v-tooltip>
                                <v-tooltip top>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn
                                            dark
                                            icon
                                            v-bind="attrs"
                                            v-on="on"
                                            class="delete custom-btn"
                                            @click="deleteAccount(item.id)"
                                        >
                                            <i class="fa fa-trash"></i>
                                        </v-btn>
                                    </template>
                                    <span>Delete Bank info </span>
                                </v-tooltip>
                            </template>
                        </v-data-table>

                        <v-expand-transition>
                            <div v-show="show">
                                <v-card-title>
                                    <h5
                                        class="modal-title custom-color"
                                        v-show="!editmode"
                                        id="addNewLabel"
                                    >
                                        Add Bank Details
                                    </h5>
                                    <h5
                                        class="modal-title custom-color"
                                        v-show="editmode"
                                        id="addNewLabel"
                                    >
                                        Update Bank Details
                                    </h5>
                                    <v-spacer></v-spacer>
                                    <v-icon color="#8DA90B" @click="show = false">
                                        mdi-close
                                    </v-icon>
                                </v-card-title>
                                <v-card-text>
                                    <form
                                        @submit.prevent="
                                            editmode
                                                ? updateBank()
                                                : createBank()
                                        "
                                    >
                                        <div class="modal-body">
                                            <v-text-field
                                                color="#8DA90B"
                                                label="Account Name"
                                                placeholder="Enter Account Name"
                                                outlined
                                                dense
                                                v-model="Bankform.account_name"
                                                :rules="[
                                                    () =>
                                                        !!Bankform.account_name ||
                                                        'This field is required',
                                                ]"
                                            ></v-text-field>

                                            <v-text-field
                                                color="#8DA90B"
                                                label="Bank Name"
                                                placeholder="Enter Bank Name"
                                                outlined
                                                dense
                                                v-model="Bankform.bank_name"
                                                :rules="[
                                                    () =>
                                                        !!Bankform.bank_name ||
                                                        'This field is required',
                                                ]"
                                            ></v-text-field>

                                            <v-text-field
                                                color="#8DA90B"
                                                label="Bank Number"
                                                placeholder="Enter Bank number"
                                                outlined
                                                dense
                                                v-model="Bankform.bank_number"
                                                :rules="[
                                                    () =>
                                                        !!Bankform.bank_number ||
                                                        'This field is required',
                                                ]"
                                            ></v-text-field>
                                        </div>
                                        <div class="modal-footer">
                                            <v-btn
                                                type="button"
                                                color="error"
                                                elevation="2"
                                                data-dismiss="modal"
                                                >Close</v-btn
                                            >
                                            <v-btn
                                                dark
                                                v-show="editmode"
                                                type="submit"
                                                color="#8DA90B"
                                                elevation="2"
                                                >Update</v-btn
                                            >
                                            <v-btn
                                                dark
                                                v-show="!editmode"
                                                type="submit"
                                                color="#8DA90B"
                                                elevation="2"
                                                >Create</v-btn
                                            >
                                        </div>
                                    </form>
                                </v-card-text>
                            </div>
                        </v-expand-transition>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div
                class="modal fade"
                id="addNew"
                data-backdrop="static"
                tabindex="-1"
                role="dialog"
                aria-labelledby="addNewLabel"
                aria-hidden="true"
            >
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content custom-tbl">
                        <div class="modal-header">
                            <h5
                                class="modal-title custom-color"
                                v-show="!editmode"
                                id="addNewLabel"
                            >
                                New Arena
                            </h5>
                            <h5
                                class="modal-title custom-color"
                                v-show="editmode"
                                id="addNewLabel"
                            >
                                Update Arena
                            </h5>
                            <button

                                type="button"
                                class="close"
                                data-dismiss="modal"
                                aria-label="Close"
                            >
                                <span class="custom-color" aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form
                            @submit.prevent="
                                editmode ? updateArena() : createArena()
                            "
                        >
                            <div class="modal-body">
                                <v-text-field
                                    color="#8DA90B"
                                    label="Arena Name"
                                    outlined
                                    required
                                    v-model="form.arena"
                                    :rules="[
                                        () => !! form.arena || 'This field is required',
                                        ]"
                                    :error-messages="errors.areaCode"
                                ></v-text-field>

                                <v-text-field
                                    label="Arena Address"
                                    color="#8DA90B"
                                    outlined
                                    v-model="form.address"
                                    :rules="[
                                        () =>
                                            !!form.address ||
                                            'This field is required',
                                    ]"
                                    :class="{
                                        'is-invalid':
                                            form.errors.has('address'),
                                    }"
                                    required
                                ></v-text-field>

                                <v-text-field
                                    label="Operator Name"
                                    color="#8DA90B"
                                    placeholder="john Doe"
                                    outlined
                                    v-model="form.operator"
                                    :rules="[
                                        () =>
                                            !!form.operator ||
                                            'This field is required',
                                    ]"
                                    required
                                ></v-text-field>

                                <v-combobox
                                    v-model="contactNos"
                                    :items="contacts"
                                    color="#8DA90B"
                                    label="Contact Number"
                                    hint="Must be 11 digits"
                                    multiple
                                    outlined
                                    deletable-chips
                                    chips
                                    item-text="contact_number"
                                    :error-messages="errors.contacts"
                                >
                                </v-combobox>

                                <v-combobox
                                    v-model="emailsArr"
                                    :items="emails"
                                    color="#8DA90B"
                                    label="Email Address"
                                    hint="Maximum of 5 Emails"
                                    multiple
                                    chips
                                    outlined
                                    deletable-chips
                                    item-text="email"
                                >
                                </v-combobox>
                            </div>
                            <div class="modal-footer">
                                <v-btn
                                    dark
                                    v-show="editmode"
                                    type="submit"
                                    color="#8DA90B"
                                    elevation="2"
                                    >Update</v-btn
                                >
                                <v-btn
                                    dark
                                    v-show="!editmode"
                                    type="submit"
                                    color="#8DA90B"
                                    elevation="2"
                                    >Add Arena</v-btn
                                >
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </v-container>
        <v-dialog v-model="arenaLoading" persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>
                    Please stand by
                    <v-progress-linear
                        indeterminate
                        color="white"
                        class="mb-0"
                    ></v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-app>
</template>

<script>
import { camelCase } from "lodash";
import XLSX from "xlsx";

import ExcelJS from "exceljs";
import { saveAs } from "file-saver";

export default {
    data() {
        return {
            headers: [
                { text: "#", value: "index", sortable: false },
                { text: "Code", value: "area_code", icon: 'mdi-map-marker-radius', sortable: false },
                { text: "Arena Name", value: "arena", icon: 'mdi-alpha-a-circle', sortable: false },
                { text: "Operator", value: "operator", icon: 'mdi-account-cog', sortable: false },
                { text: "Team", value: "team", icon: 'mdi-account-group', sortable: false },
                { text: "Bank Details", value: "bank", icon: 'mdi-bank', sortable: false },
                { text: "", value: "actions", sortable: false },
            ],

            Bankheaders: [
                {
                    text: "Account Name",
                    value: "account_name",
                    sortable: false,
                },
                { text: "Bank Name", value: "bank_name", icon: 'mdi-bank-minus' , sortable: false },
                { text: "Bank Number", value: "bank_number", icon: 'mdi-bank', sortable: false },
                { text: "Modify", value: "modify", icon: "mdi-account-edit" ,  sortable: false },
                { text: "", value: "actions", sortable: false },
            ],
            activityHeaders: [
                { text: "Name", value: "log_name" },
                { text: "Description", value: "description" },
            ],
            select: [],
            emailRules: [],
            errorMessages: "",
            formHasErrors: false,
            editmode: false,
            arena: [],
            emails: [],
            contacts: [],
            arenalogs: [],
            search: "",
            searchlogs: "",
            searchbank: "",
            input: "",
            loading: false,
            arenaLoading: false,
            show: false,
            bankDetails: [],
            form: new Form({
                area_code: "",
                id: "",
                arena: "",
                address: "",
                operator: "",
                contact_number: [],
                email: [],
            }),
            Bankform: new Form({
                id: "",
                account_name: "",
                arenas_id: "",
                bank_name: "",
                bank_number: "",
            }),

            contactNumbers: [],
            contactNos: [], // to removed
            emailsArr: [],
            emailList: [],
            arenaList: [],
            bankList: [],
            fileUpload: null,
            isExcel: false,
            errors: {
                areaCode: "",
                contacts: "",
                emails: "",
            },
        };
    },
    methods: {
        onFileChange(event) {
            const file = event ? event : null;

            const checkfile =
                event.name.includes("xlsx") || event.name.includes("csv");

            if (event && checkfile) {
                this.isExcel = true;
                const reader = new FileReader();
                let arrayData = [];

                reader.onload = (e) => {
                    const bstr = e.target.result;
                    const wb = XLSX.read(bstr, { type: "binary" });

                    const ws = wb.SheetNames;

                    const filteredWS = ws.filter(function (value) {
                        return (
                            value.toLowerCase() ===
                            "SUMMARY OF OCBS DETAILS".toLowerCase()
                        );
                    });

                    filteredWS.forEach((w) => {
                        const singleSheet = wb.Sheets[w];

                        arrayData.push(
                            XLSX.utils.sheet_to_json(singleSheet, {
                                header: "A",
                                defval: "",
                            })
                        );
                    });

                    const objectKeyed = (array) => {
                        const keysss = array.find(
                            (k) => k.C === "ARENA NAME" || k.B === "CODE"
                        );

                        const [...headKey] = Object.values(keysss);
                        const headK = [...headKey];

                        return array.map((data) => {
                            data = Object.assign(
                                {},
                                ...Object.entries(data).map(
                                    ([, prop], index) => ({
                                        [camelCase(headK[index])]: prop,
                                    })
                                )
                            );
                            return { ...data };
                        });
                    };

                    // const objk = objectKeyed(arrayData[0]);

                    let omitNull = (obj) => {
                        Object.keys(obj)
                            .filter((k) => obj[k] === null || obj[k] === "")
                            .forEach((k) => delete obj[k]);
                        return obj;
                    };
                    const mergedRows = {
                        ...omitNull(arrayData[0][0]),
                        ...omitNull(arrayData[0][1]),
                    };
                    arrayData[0].splice(0, 2);

                    const mergingData = [mergedRows].concat(arrayData[0]);

                    const objk = objectKeyed(mergingData);

                    const toArrayContactEmail = (contactString) => {

                        let number = [];
                        const checkBreak = contactString?.toString().includes("\r\n");

                        const checkForwardSlash = contactString?.toString().includes("/");

                        if (contactString != null) {
                            if (checkBreak) {
                                const contactSplit =
                                    contactString.split("\r\n");

                                const filteredContact = contactSplit.filter(
                                    function (el) {
                                        return el != "";
                                    }
                                );

                                filteredContact.map((cs) => {
                                    number.push(cs.trim());
                                });
                            } else if (checkForwardSlash) {
                                const contactSplit = contactString.split("/");
                                const filteredContact = contactSplit.filter(
                                    function (el) {
                                        return el != "";
                                    }
                                );

                                filteredContact.map((cs) => {
                                    number.push(cs.trim());
                                });
                            } else {
                                number.push(contactString);
                            }

                            return number.join(" / ");
                        }
                    };

                    let contactNo = [];
                    let emailList = [];

                    const removeFirstObjectTitle = objk.filter((oj) => {
                        if (oj.arenaName !== "ARENA NAME") {
                            return oj;
                        }
                    });

                    removeFirstObjectTitle.forEach((foh) => {
                        if (foh.bankName !== "" || foh.bankNumber !== "")
                            this.bankList.push({
                                account_name: foh.accountName,
                                bank_name: foh.bankName,
                                bank_number: foh.bankNumber,
                                area_code: foh.areaCode,
                            });

                        this.arenaList.push({
                            arena:
                                foh.arenaName.indexOf("~") > -1
                                    ? foh.arenaName.replace(/\~/g, "/")
                                    : foh.arenaName,
                            area_code: foh.areaCode,
                            address: foh.address,
                            operator: foh.operatorsName,
                            team: foh.team && foh.team.toLowerCase(),
                            contact_number: "xxxxxxx",
                        });

                        if (toArrayContactEmail(foh.contact) !== "")
                            contactNo.push({
                                area_code: foh.areaCode,
                                contact_number: toArrayContactEmail(
                                    foh.contact
                                ),
                            });
                        if (toArrayContactEmail(foh.email) !== "")
                            emailList.push({
                                area_code: foh.areaCode,
                                email: toArrayContactEmail(foh.email),
                            });
                    });

                    this.emailList = emailList;
                    this.contactNumbers = contactNo;
                    // console.log( this.bankList);
                };
                reader.readAsBinaryString(file);
            } else {
                this.isExcel = false;
                Fire.$emit("AfterCreate"),
                    Toast.fire({
                        icon: "warning",
                        title: "Make sure you insert correct excel data!",
                    });
            }
        },
        clearFile() {
            this.isExcel = false;
            this.fileUpload = null;
        },
        async proceedAction() {
            console.log(this.bankList);
            this.$Progress.start();
            this.arenaLoading = true;
            await axios.post("api/importArena", {
                arenaList: this.arenaList,
                Uploadname: this.fileUpload.name,
            });
            await axios.post("api/contactnumbers", this.contactNumbers);
            await axios.post("api/emails", this.emailList);
            await axios.post("api/bankStore", this.bankList);

            Fire.$emit("AfterCreate");
            Toast.fire("Successfully!", "Excel Imported", "success");
            this.$Progress.finish();
            this.arenaLoading = false;
            this.fileUpload = null;

            this.isExcel = false;
        },

        required(value) {
            if (value instanceof Array && value.length == 0) {
                return "Email is Required.";
            }
            return true;
        },
        removeEmail(id) {
            axios.get("api/emailDelete/" + id).then((data) => {
                Fire.$emit("AfterCreate");
                //  $('#addNew').modal('hide');
                // Toast.fire({
                //     icon: 'success',
                //     title: 'Email successfully Deleted'
                //     })
                this.getEmail(this.form.area_code);
            });
        },
        removeContact(id) {
            // console.log(id);
            // console.log(this.form.area_code);
            axios.delete("api/deleteContact/" + id).then((data) => {
                Fire.$emit("AfterCreate");
                // $('#addNew').modal('hide');
                // Toast.fire({
                //     icon: 'success',
                //     title: 'Email successfully Deleted'
                //     })
                this.getContacts(this.form.area_code);
            });
        },
        openBankDetails() {
            this.show = true;
            this.editmode = false;
            this.Bankform.bank_name = "";
            this.Bankform.bank_number = "";
        },
        openBankModel(data) {
            this.show = false;

            $("#bankModal").modal("show");
            this.bankDetails = data.bank_details;
            this.Bankform.arenas_id = data.id;
            this.Bankform.area_code = data.area_code;
            // this.Bankform.account_name = data.operator;
        },
        createBank() {
            this.$Progress.start();
            this.Bankform.post("api/bankaccount")
                .then(({ data }) => {
                    console.log(data);
                    Fire.$emit("AfterCreate");
                    this.bankDetails.push(data);
                    Toast.fire({
                        icon: "success",
                        title: "Bank Created in successfully",
                    });
                    this.show = false;
                    // $('#bankModal').modal('hide');
                    this.$Progress.finish();
                })
                .catch(() => {});
        },
        updateBank() {
            this.$Progress.start();
            this.Bankform.put("api/bankaccount/" + this.Bankform.id)
                .then(() => {
                    swal.fire(
                        "Updated!",
                        "Information has been updated.",
                        "success"
                    );

                    // const foundIndex = this.bankDetails.findIndex(x => x.id === this.Bankform.id);

                    // this.bankDetails[foundIndex] = this.Bankform
                    // console.log(foundIndex)
                    // console.log(this.bankDetails[foundIndex])

                    this.show = false;
                    $("#bankModal").modal("hide");
                    this.$Progress.finish();
                    Fire.$emit("AfterCreate");
                })
                .catch(() => {
                    this.$Progress.fail();
                });
        },
        deleteAccount(id) {
            swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.value) {
                    this.Bankform.delete("api/bankaccount/" + id)
                        .then(() => {
                            swal.fire(
                                "Deleted!",
                                "Your file has been deleted.",
                                "success"
                            );
                            Fire.$emit("AfterCreate");
                            $("#bankModal").modal("hide");
                        })
                        .catch(() => {
                            swal.fire(
                                "Failed!",
                                "There was something wrong.",
                                "warning"
                            );
                        });
                }
            });
        },
        updateBankDetails(accounts) {
            this.Bankform.reset();
            this.show = true;
            this.editmode = true;
            this.Bankform.fill(accounts);
            this.Bankform.arenas_id = accounts.arenas_id;
        },
        editBankModal(accounts) {
            this.editmode = true;
            this.form.reset();
            $("#addNew").modal("show");
            this.form.fill(accounts);
            this.form.arenas_id = accounts.arenas_id;
        },
        updateArena() {
            this.$Progress.start();
            const areaCode = this.form.arena.split(" ")[0];
            this.form.area_code = areaCode;
            this.form.contact_number =
                this.contactNos == ""
                    ? null
                    : this.contactNos.length > 1
                    ? this.contactNos.join(" / ")
                    : this.contactNos.toString();
            this.form.email =
                this.emailsArr == ""
                    ? null
                    : this.emailsArr.length > 1
                    ? this.emailsArr.join(" / ")
                    : this.emailsArr.toString();
            console.log(this.form);
            this.form
                .put("api/arena/" + this.form.id)
                .then((data) => {
                    console.log(data);
                    $("#addNew").modal("hide");
                    Toast.fire({
                        icon: "success",
                        title: "Successfully Updated",
                    });
                    this.$Progress.finish();
                    Fire.$emit("AfterCreate");
                })
                .catch((e) => {
                    this.errors.contacts =
                        e.response.data.errors.contact_number;
                    this.errors.emails = e.response.data.errors.email;
                    this.$Progress.fail();
                });
        },
        editModal(arenas) {
            this.editmode = true;
            this.form.reset();

            $("#addNew").modal("show");
            this.form.area_code = arenas.area_code;
            this.form.id = arenas.id;
            this.form.arena = arenas.arena;
            this.form.address = arenas.address;
            this.form.operator = arenas.operator;
            this.getEmail(arenas.area_code);
            this.getContacts(arenas.area_code);

            console.log(arenas);
        },
        getEmail(areaCode) {
            axios.get("api/getEmails/" + areaCode).then(({ data }) => {
                const em =
                    data.length > 0 && data[0].email?.includes(" / ")
                        ? data[0].email.split(" / ")
                        : data.length > 0
                        ? [data[0].email]
                        : [];

                this.emailsArr = em;
            });
        },
        getContacts(areaCode) {
            axios.get("api/getContacts/" + areaCode).then(({ data }) => {
                const contacts =
                    data.length > 0 && data[0].contact_number?.includes(" / ")
                        ? data[0].contact_number.split(" / ")
                        : data.length > 0
                        ? [data[0].contact_number]
                        : [];

                this.contactNos = contacts;
            });
        },
        openModal() {
            this.editmode = false;
            this.form.reset();
            this.emailsArr = "";
            this.contactNos = "";
            $("#addNew").modal("show");
        },
        deleteArena(id) {
            swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.value) {
                    this.form
                        .delete("api/arena/" + id)
                        .then(() => {
                            swal.fire(
                                "Deleted!",
                                "Your file has been deleted.",
                                "success"
                            );
                            Fire.$emit("AfterCreate");
                        })
                        .catch(() => {
                            swal.fire(
                                "Deleted!",
                                "Your file has been deleted.",
                                "success"
                            );
                        });
                }
            });
        },
        clearArena(){
            swal.fire({
                title: "Clear Arena Details?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete all!",
            }).then((result) => {
                if (result.value) {
                   axios.post("api/clearArena")
                        .then(() => {
                            swal.fire(
                                "Deleted!",
                                "Your file has been deleted.",
                                "success"
                            );
                            Fire.$emit("AfterCreate");
                        })
                        .catch(() => {
                            swal.fire(
                                "Deleted!",
                                "Your file has been deleted.",
                                "success"
                            );
                        });
                }
            });
        },
        loadArena() {
            axios.get("api/arena").then((data) => {
                this.arena = data.data;
            });
        },
        createArena() {
            this.$Progress.start();
            const areaCode = this.form.arena.split(" ")[0];
            this.form.area_code = areaCode;
            this.form.contact_number = !this.contactNos
                ? null
                : this.contactNos.length > 1
                ? this.contactNos.join(" / ")
                : this.contactNos.toString();
            this.form.email = !this.emailsArr
                ? null
                : this.emailsArr.length > 1
                ? this.emailsArr.join(" / ")
                : this.emailsArr.toString();

            this.form
                .post("api/arena")
                .then(() => {
                    Fire.$emit("AfterCreate");
                    $("#addNew").modal("hide");
                    Toast.fire({
                        icon: "success",
                        title: "Successfully Created",
                    });

                    this.$Progress.finish();
                })
                .catch((e) => {
                    this.$Progress.fail();
                    this.errors.areaCode =
                        e.response.data.message.includes(
                            "Integrity constraint"
                        ) || e.response.status === 500
                            ? "Area Code/Arena already exist."
                            : "";
                });
        },

        async downloadArenaDetails() {
            const fontColor = (
                color = "FFFFFFFF",
                name = "Arial",
                family = 2,
                size = 9
            ) => {
                return {
                    name,
                    color: { argb: color },
                    family,
                    size,
                    bold: true,
                };
            };

            const fillColor = (color = "FF000000") => {
                return {
                    type: "pattern",
                    pattern: "solid",
                    fgColor: { argb: color },
                };
            };

            const border = (color) => {
                return {
                    top: {
                        style: "thick",
                        color: { argb: color },
                    },
                    left: {
                        style: "thick",
                        color: { argb: color },
                    },
                    bottom: {
                        style: "thick",
                        color: { argb: color },
                    },
                    right: {
                        style: "thick",
                        color: { argb: color },
                    },
                };
            };

            const current = new Date();
            const { data } = await axios.get("api/arenaToExcel");

            const workbook = new ExcelJS.Workbook(data);
            const worksheet = await workbook.addWorksheet(
                "SUMMARY OF OCBS DETAILS",
                { properties: { tabColor: { argb: "FF8FD5A6" } } }
            );

            const convertedResult = data.map((val, index) => {
                return {
                    count: index + 1,
                    contact:
                        val.contact_details.length <= 0 ||
                        val.contact_details === 0
                            ? null
                            : val.contact_details[0].contact_number,
                    email:
                        val.email_details.length <= 0 || val.email_details === 0
                            ? null
                            : val.email_details[0].email,
                    account_name:
                        val.bank_details.length <= 0 || val.bank_details === 0
                            ? null
                            : val.bank_details[0].account_name,
                    bankname:
                        val.bank_details.length <= 0 || val.bank_details === 0
                            ? null
                            : val.bank_details[0].bank_name,
                    banknumber:
                        val.bank_details.length <= 0 || val.bank_details === 0
                            ? null
                            : val.bank_details[0].bank_number,
                    bankOperator: val.operator,
                    ...val,
                };
            });
            worksheet.getRow(2).values = [
                "#",
                "CODE",
                "ARENA NAME",
                "ADDRESS",
                "OPERATOR'S NAME",
                "CONTACT NUMBER",
                "EMAIL",
            ];

            worksheet.columns = [
                { key: "id", width: 10 },
                { key: "area_code", width: 10 },
                { key: "arena", width: 100 },
                { key: "address", width: 50 },
                { key: "operator", width: 50 }, //to fix
                { key: "contact", width: 50 }, //to fix
                { key: "email", width: 50 }, //to fix
                { key: "bankOperator", width: 50 }, //to fix
                { key: "account_name", width: 30 }, //to fix
                { key: "bankname", width: 30 }, //to fix
                { key: "banknumber", width: 30 }, //to fix
            ];

            worksheet.addRows(convertedResult);

            worksheet.mergeCells("H1:K1");
            worksheet.getCell("A1").value = "#";
            worksheet.getCell("B1").value = "AREA CODE";
            worksheet.getCell("C1").value = "ARENA NAME";
            worksheet.getCell("D1").value = "ADDRESS";
            worksheet.getCell("E1").value = "OPERATOR\'S NAME";
            worksheet.getCell("F1").value = "CONTACT";
            worksheet.getCell("G1").value = "EMAIL";
            worksheet.getCell("H1").value = "BANK DETAILS";
            worksheet.getCell("H2").value = "OPERATOR'S NAME";
            worksheet.getCell("I2").value = "ACCOUNT NAME";
            worksheet.getCell("J2").value = "BANK NAME";
            worksheet.getCell("K2").value = "BANK NUMBER";

            // Custom design excel
            const customFillColumn = (columns, fontColor, fillColor) => {
                columns.forEach((column) => {
                    worksheet.getColumn(column).eachCell((cell) => {
                        cell.font = fontColor;
                        cell.fill = fillColor;
                        cell.border = {
                            top: {
                                style: "thick",
                                color: { argb: "FF000000" },
                            },
                            left: {
                                style: "thick",
                                color: { argb: "FF000000" },
                            },
                            bottom: {
                                style: "thick",
                                color: { argb: "FF000000" },
                            },
                            right: {
                                style: "thick",
                                color: { argb: "FF000000" },
                            },
                        };
                    });
                });
            };

            worksheet.columns.forEach(function (column, i) {
                column["eachCell"]({ includeEmpty: true }, function (cell) {
                    cell.border = {
                        top: { style: "thin" },
                        left: { style: "thin" },
                        bottom: { style: "thin" },
                        right: { style: "thin" },
                    };
                    cell.alignment = {
                        vertical: "middle",
                        horizontal: "center",
                    };
                });
            });

            const headerCells = ["A", "B", "C", "D", "E", "F", "G"];

            // worksheet.getRow(1).height = 30;
            headerCells.forEach((cell) => {
                worksheet.getCell(`${cell}1`).border = border("FFFFFFFF");
                worksheet.getCell(`${cell}1`).font = fontColor("FFFFFFFF");
                worksheet.getCell(`${cell}1`).fill = fillColor("FF000000");
                worksheet.getCell(`${cell}1`).alignment = {
                    vertical: "middle",
                    horizontal: "center",
                };

                worksheet.mergeCells(`${cell}1:${cell}2`);
            }),
                (worksheet.getCell("H1").fill = fillColor("FFFFE1EA"));
            worksheet.getCell("H1").font = fontColor("FF000000");

            const bankDetailsCellHeader = ["H2", "I2", "J2", "K2"];
            bankDetailsCellHeader.forEach((cell) => {
                worksheet.getCell(cell).border = border("FFFFFFFF");
                worksheet.getCell(cell).fill = fillColor("FF000000");
                worksheet.getCell(cell).font = fontColor("FFFFFFFF");
                worksheet.getCell(cell).alignment = {
                    vertical: "middle",
                    horizontal: "center",
                };
            });

            customFillColumn(["B"], fontColor("000000"), fillColor("cbe01f"));

            const buf = await workbook.xlsx.writeBuffer();
            saveAs(
                new Blob([buf]),
                `Arena Master List-${current.getDate()}/${
                    current.getMonth() + 1
                }/${current.getFullYear()}.xlsx`
            );
        },
    },
    created() {
        this.loadArena();

        Fire.$on("AfterCreate", () => {
            this.loadArena();
        });
    },
};
</script>
<style scoped>
    .custom-color{
    color:#8DA90B;
    }

    .add{
        background-color: #8DA90B;
        color:white;
    }

    .view{
        background-color: #3a89e8;
    }

    .yes{
        background-color: #8DA90B;
    }

    .no{
        background-color: #b8b8b8;
    }

    .edit{
        background-color: #f0bb00;
    }

    .delete{
        background-color: red;
    }

    .custom-btn{
        width:100px;
        border-radius: 20px;
    }

    .custom-tbl{
        border: 2px solid #8DA90B;
        border-radius: 20px;
    }

    .custom-bg{
        background-color: #3a89e8;
    }

</style>