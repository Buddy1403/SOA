<template lang="">
    <v-container>
        <v-card class="custom-tbl">
          <v-toolbar elevation="0" class="custom-color">
            <v-icon class="mr-4" color="#8DA90B">mdi-group</v-icon>
            <v-toolbar-title>{{selectedTeam && selectedTeam.name.toUpperCase()}}</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
              <v-btn dark text @click.stop="closeViewTeam">
                <v-icon color="#8DA90B">mdi-close</v-icon>
              </v-btn>
            </v-toolbar-items>
          </v-toolbar>
          <v-card-text>
              <v-tabs
                color="#8DA90B"
                right
                flat
              >
              <v-tab class="custom-tabs">Arenas</v-tab>
              <v-tab class="custom-tabs">Members</v-tab>
              <v-tab-item>
                <v-data-table :items="arenaTeams" :headers="headers">
                  <template  style="font-size:16px;"  v-for="header in headers" v-slot:[`header.${header.value}`]="{ header }">
                  <v-icon medium color="#8DA90B">{{header.icon}}</v-icon>
                      <span style="color:#8DA90B"> &nbsp;{{ header.text }} </span>
                  </template>
                  <template v-slot:top>
                    <v-toolbar flat>
                      <v-tooltip color="#8DA90B" bottom>
                        <template v-slot:activator="{ on, attrs }">
                          <v-btn
                            color="#8DA90B"
                            dark
                            v-bind="attrs"
                            v-on="on"
                            class="mx-2"
                            @click="openArenaSelection"
                          >
                            <v-icon>mdi-stadium-variant</v-icon>
                            Add Arena
                          </v-btn>
              </template>
                  <span>Add Arena to {{selectedTeam && selectedTeam.name.toUpperCase()}}</span>
                                                    </v-tooltip>
                                            </v-toolbar>

                                            <v-dialog v-model="dialogRemoveItem" max-width="500px"  persistent>
                                                    <v-card>
                                                        <v-card-title class="text-h6"> Remove {{selectedArena && selectedArena.area_code}} from {{selectedTeam && selectedTeam.name.toUpperCase()}}?</v-card-title>

                                                        <v-card-actions >
                                                            <v-spacer></v-spacer>
                                                            <v-btn color="red darken-1" text @click="dialogRemoveItem = false">Cancel</v-btn>
                                                            <v-btn color="#8DA90B" text @click="removeItemConfirm">OK</v-btn>

                                                        </v-card-actions>
                                                    </v-card>
                                            </v-dialog>
                                    </template>
                                
                                
                                <template v-slot:[`item.team`]="{ item }">
                                    <v-edit-dialog
                                    :return-value.sync="item.team"
                                    large
                                    persistent
                                    @save="save(item)"
                                    @cancel="cancel"
                                    @open="open"
                                    @close="close"
                                    :default="item.team"
                                    >
                                     <div>{{ item.team }}</div>
                                        <template v-slot:input>
                                            <div class="mt-4 text-h6">
                                                Change Team
                                            </div>

                                            <v-select

                                                v-model="item.team"
                                                :items="teams"
                                                menu-props="auto"
                                                label="Team"
                                                single-line
                                                counter
                                                autofocus
                                                item-text='name'
                                                item-value='name'
                                                @change="handleChanged"

                                            ></v-select>

                                        </template>
                                    </v-edit-dialog>
                                </template>

                                <template v-slot:[`item.actions`]="{ item }">

                                        <v-tooltip color="#8DA90B" bottom>
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-btn
                                                    color="white"
                                                    dark
                                                    icon
                                                    v-bind="attrs"
                                                    v-on="on"
                                                    class="mx-2 delete custom-btn"
                                                    @click="removeItem(item)"

                                                >
                                                    DELETE
                                                </v-btn>
                                            </template>
                                            <span>Remove {{ item.name }}</span>
                                        </v-tooltip>
                                </template>

                            </v-data-table>


                        </v-tab-item>
                        <!-- MEMBERS -->
                         <v-tab-item>

                                <v-data-table v-model="selected" value="10"  :items="userTeams" item-key="id"  :headers="headersUser" single-select show-select @input="handleChangedSelectedUser">
                                <template  style="font-size:16px;"  v-for="header in headersUser" v-slot:[`header.${header.value}`]="{ header }">
                                    <v-icon medium color="#8DA90B">{{header.icon}}</v-icon>
                                    <span style="color:#8DA90B"> &nbsp;{{ header.text }} </span>
                                </template>
                                <template v-slot:top>
                                    <v-toolbar flat>
                                        <v-spacer></v-spacer>
                                                <v-tooltip color="#8DA90B" bottom>
                                                        <template v-slot:activator="{ on, attrs }">
                                            <v-btn
                                              color="#8DA90B"
                                              dark
                                              v-bind="attrs"
                                              v-on="on"
                                              class="mx-2"
                                              @click="openUserSelection"
                                            >
                                              <v-icon>mdi-account-plus</v-icon>
                                              Add User
                                            </v-btn>
                                          </template>
                                                            <span>Add User to {{selectedTeam && selectedTeam.name}}</span>
                                                        </v-tooltip>
                                                </v-toolbar>

                                                   <v-dialog v-model="dialogRemoveUserItem" max-width="500px">
                                                    <v-card>
                                                    <v-card-title class="text-h6">Remove {{selectedUser && selectedUser.name}} from {{selectedTeam && selectedTeam.name}}?</v-card-title>

                                                    <v-card-actions>
                                                        <v-spacer></v-spacer>
                                                        <v-btn color="red darken-1" text @click="dialogRemoveUserItem = false">Cancel</v-btn>
                                                        <v-btn color="#8DA90B" text @click="removeUserItemConfirmation">OK</v-btn>

                                                    </v-card-actions>
                                                    </v-card>
                                                </v-dialog>


                                    </template>


                                     <template
                                                v-slot:[`item.data-table-select`]="{
                                                    item,
                                                    isSelected,
                                                    select,
                                                }"
                                            >
                                                <v-simple-checkbox

                                                    :value="item.id == assignedComputed"
                                                    @change="handleChangedSelectedUser(item)"
                                                    @input="select(item)"
                                                ></v-simple-checkbox>
                                            </template>

                                

                                <template v-slot:[`item.team`]="{ item }">
                                    <v-edit-dialog
                                    :return-value.sync="item.team"
                                    large
                                     persistent
                                    @save="saveTeamUser(item)"
                                    @cancel="cancel"
                                    @open="openUser"
                                    @close="closeUser"
                                    :default="item.team"
                                    >
                                     <div>{{ item.team }}</div>
                                        <template v-slot:input>
                                            <div class="mt-4 text-h6">
                                                Change Team
                                            </div>

                                            <v-select
                                                :items="teams"
                                                menu-props="auto"
                                                label="Team"
                                                single-line
                                                counter
                                                autofocus
                                                item-text='name'
                                                item-value='id'
                                                :value="item"
                                                return-object
                                                @change="handleChangedUserTeam"

                                            ></v-select>

                                        </template>
                                    </v-edit-dialog>
                                </template>

                                  <template v-slot:[`item.actions`]="{ item }">
                                          <v-tooltip color="error" bottom>
                                            <template v-slot:activator="{ on, attrs }">
                                                <v-btn
                                                    color="white"
                                                    dark
                                                    icon
                                                    v-bind="attrs"
                                                    v-on="on"
                                                    class="mx-2 delete custom-btn"
                                                    @click="removeUserItem(item)"

                                                >
                                                    DELETE
                                                </v-btn>
                                            </template>
                                            <span>Remove {{ item.name }}</span>
                                          </v-tooltip>
                                    </template>
                            </v-data-table>
                        </v-tab-item>
                    </v-tabs>
                    </v-card-text>
                </v-card>
            <!-- </v-dialog> -->

             <v-dialog internal-activator v-model="addNewArenaItem" max-width="500px">
                <v-card class="custom-tbl">
                   <v-card-title class="text-h6 custom-color">Add arena into {{selectedTeam && selectedTeam.name}} team</v-card-title>
                      <v-card-title>
                        <v-text-field
                          color="#8DA90B"
                          v-model="arenaSearch"
                          label="Search"
                          single-line
                          hide-details
                        >
                          <template v-slot:prepend-inner>        
                            <v-icon outlined dark color="#8DA90B">mdi-magnify</v-icon> 
                          </template>
                        </v-text-field>
                      </v-card-title>
                    <v-card-text>
                        <span class="subtitle-2">Select arena that not yet selected to other team.</span>
                          <v-data-table v-model="selectedArenasToTeam"  :items="arenaNoTeam" :headers="headersArena" :search="arenaSearch" :itemsPerPage="5" show-select  :footer-props="{
                                                'items-per-page-options': [
                                                    5,
                                                    10,
                                                ],
                                            }">
                                <template  style="font-size:16px;"  v-for="header in headersArena" v-slot:[`header.${header.value}`]="{ header }">
                                  <v-icon medium color="#8DA90B">{{header.icon}}</v-icon>
                                <span style="color:#8DA90B"> &nbsp; {{ header.text }} </span>
                            </template>

                          </v-data-table>
                           
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                            <v-btn color="red darken-1" text @click="() => {addNewArenaItem = false; this.selectedArenasToTeam = []}">Cancel</v-btn>
                            <v-btn :disabled="selectedArenasToTeam < 1 ? true : false" color="#8DA90B" text @click="addArenaSelectedToTeam">ADD</v-btn>

                    </v-card-actions>
                </v-card>
            </v-dialog>

                <!-- ADD MEMBER TO TEAM LIST -->
               <v-dialog internal-activator v-model="addNewUserItem" max-width="500px">
                <v-card class="custom-tbl">
                    <v-card-title class="text-h6 custom-color">Add user into {{selectedTeam && selectedTeam.name}} team</v-card-title>
                       <v-card-title>
                        <v-text-field
                          v-model="staffSearch"
                          label="Search"
                          single-line
                          hide-details
                        >
                          <template v-slot:prepend-inner>        
                                    <v-icon outlined dark color="#8DA90B">mdi-magnify</v-icon> 
                          </template>
                        </v-text-field>
                      </v-card-title>
                    <v-card-text>
                        <!-- <span class="subtitle-2">Select user that not yet selected to other team.</span>
                          <v-autocomplete
                            v-model="addSelectedUserTeamItem"
                            :items="userNoTeam"
                            color="white"
                            item-text="name"
                            label="Staffs"

                            return-object
                        ></v-autocomplete> -->
                         <v-data-table v-model="selectedUserToTeam"  :items="userNoTeam" :headers="headerStaffs" :search="staffSearch" :itemsPerPage="5" show-select  :footer-props="{
                                                'items-per-page-options': [
                                                    5,
                                                    10,
                                                ],
                                            }">
                            <template  style="font-size:16px;"  v-for="header in headerStaffs" v-slot:[`header.${header.value}`]="{ header }">
                                <v-icon medium color="#8DA90B">{{header.icon}}</v-icon>
                                <span style="color:#8DA90B"> &nbsp; {{ header.text }} </span>
                            </template>

                          </v-data-table>

                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                            <v-btn color="red darken-1" text @click="() => {addNewUserItem = false}">Cancel</v-btn>
                            <v-btn color="#8DA90B" text @click="addUserSelectedToTeam">ADD</v-btn>

                    </v-card-actions>
                </v-card>
            </v-dialog>

            <v-snackbar
                v-model="snack"
                :timeout="2000"
                :color="snackColor"
                bottom
                right
                style="z-index: 9999;"
                >
                {{ snackText }}

            </v-snackbar>

    </v-container>
</template>

<script>
export default {
  name: "team-management",
  props: {
    viewTeam: Boolean,
    selectedTeam: Object,
    arenaTeams: Array,
    userTeams: Array,
    teams: Array,
    getAllArenaPerTeam: Function,
    getAllUserPerTeam: Function,
    getAssignUserTeam: Function,
    loadTeam: Function,
    assignedComputed: Number,
  },
  data() {
    return {
      snack: false,
      snackColor: "",
      snackText: "",
      arenaSearch: "",
      staffSearch: "",
      tab: null,
      headers: [
        { text: "Code", value: "area_code", icon: 'mdi-map-marker-radius' },
        { text: "Arena", value: "arena", icon: 'mdi-alpha-a-circle'},
        { text: "Team", value: "team", icon: 'mdi-account-group'},
        { text: "", value: "actions", sortable: false },
      ],
      headersUser: [
        { text: "Name", value: "name", icon: "mdi-account-details"},
        { text: "Position", value: "position_details.position", icon: "mdi-account-switch" },
        { text: "Team", value: "team", icon: 'mdi-account-group'},
        { text: "", value: "actions", sortable: false },
      ],
      headerStaffs: [
         { text: "Name", value: "name", icon: "mdi-account-details"},
        { text: "Position", value: "position_details.position", icon: "mdi-account-switch" },
      ],
      headersArena: [
        { text: "Code", value: "area_code", icon: 'mdi-map-marker-radius' },
        { text: "Arena", value: "arena", icon: 'mdi-alpha-a-circle' },
      ],

      selected: [],
      selectedArenasToTeam: [],
      selectedUserToTeam: [],
      newSelectedTeam: "",
      newSelectedUserTeam: "",
      dialogRemoveItem: false,
      dialogRemoveUserItem: false,
      selectedArena: {},
      selectedUser: {},
      addNewArenaItem: false,
      addNewUserItem: false,
      arenaNoTeam: [],
      userNoTeam: [],
      addSelectedArenaTeamItem: null,
      addSelectedUserTeamItem: null,
      assignedCompt: this.assignedComputed,
    };
  },
  methods: {
    closeViewTeam() {
      this.$emit("update:viewTeam", false);
      this.$emit("update:arenaTeams", []);
      this.$emit("update:userTeams", []);
      this.$emit("update:selectedTeam", null);
    },

    async save(item) {
      this.snack = true;
      this.snackColor = "success";
      this.snackText = "Data saved";

      if (this.newSelectedTeam !== this.selectedTeam.name) {
        this.$emit(
          "update:arenaTeams",
          this.arenaTeams.filter(function (v, i) {
            return v.area_code !== item.area_code && v.team !== item.team;
          })
        );
        await axios.put("api/updateArenaTeam/" + item.area_code, {
          team: item.team,
        });
        await Fire.$emit("AfterCreateUserTeam");
      }
    },
    async saveTeamUser(item) {
      this.snack = true;
      this.snackColor = "success";
      this.snackText = "Data saved";
      const teamId = this.newSelectedUserTeam.id;

      if (teamId === this.selectedTeam.id) {
        console.log("Not happening");
      } else if (item.isAssign) {
        console.log("Not happening IS ASSIGN");
      } else {
        this.$emit(
          "update:userTeams",
          this.userTeams.filter(function (v, i) {
            return v.id !== item.id;
          })
        );
        await axios.put("api/updateUserTeam/" + item.id, {
          team_id: teamId,
           assign: 'computed'
        });
        await Fire.$emit("AfterCreateUserTeam");
      }
    },
    cancel() {
      this.snack = true;
      this.snackColor = "error";
      this.snackText = "Canceled";
    },
    open() {
      this.snack = true;
      this.snackColor = "info";
      this.snackText = "Dialog opened";
    },
    openUser(item) {
      console.log("OPEN TEAM ");
    },
    close() {
      console.log("Dialog closed");
    },
    closeUser(item) {},
    handleChanged(item) {
      this.newSelectedTeam = item;
    },
    handleChangedUserTeam(item) {
      this.newSelectedUserTeam = item;
    },
    openArenaSelection() {
      this.addNewArenaItem = true;
      this.getArenasWithoutTeam();
    },
    openUserSelection() {
      this.addNewUserItem = true;
      this.getUsersWithoutTeam();
    },

    removeItem(item) {  // Open dialog asking to confirm if arena will be remove
      this.selectedArena = item;
      this.dialogRemoveItem = true;
    },

    async removeItemConfirm() {  // Remove Arena to specific a team
      const code = this.selectedArena.area_code;

      await axios.put("api/updateArenaTeam/" + code);

      this.$emit(
        "update:arenaTeams",
        this.arenaTeams.filter(function (v, i) {
          return v.area_code !== code;
        })
      );
      await Fire.$emit("AfterCreateArenaTeam");

      this.selectedArena = {};
      this.dialogRemoveItem = false;
    },

    removeUserItem(item) { // Open dialog asking to confirm if user will be remove
      this.selectedUser = item;
      this.dialogRemoveUserItem = true;
    },

    async removeUserItemConfirmation() {   // Remove user to specific a team
      const user = this.selectedUser;
      await axios.put("api/updateUserTeam/" + user.id, {
        team_id: null,
        assign: null
      });

      this.$emit(
        "update:userTeams",
        this.userTeams.filter(function (v, i) {
          return v.id !== user.id;
        })
      );

      await Fire.$emit("AfterCreateUserTeam");
      this.selectedUser = {};
      this.dialogRemoveUserItem = false;
    },

    async getArenasWithoutTeam() { // Get all arenas without team
      const arena = await axios.get("api/arena");
      const arenaNoTeam = arena.data.filter((a) => a.team == null);

      this.arenaNoTeam = arenaNoTeam;
    },
    // get all users that are not admin and no team and not yet assigned into a signatory
    async getUsersWithoutTeam() {
      const users = await axios.get("api/getStaffs");

      this.userNoTeam = users.data;
    },



      // // ADD multiple users to team and assigned as computed
      async addUserSelectedToTeam() {
          // let usersTeam = [];

          // this.selectedUserToTeam.forEach((a) => {
          //   const { bank_details, contact_details, ...arenas } = a;
          //   usersTeam.push({ ...arenas, team: this.selectedTeam.name });
          // });

          const teamId = this.selectedTeam.id;
          await axios.put(
            `api/updateSelectedUserToTeam/${teamId}`,
            {
              assign: 'computed',
              users: this.selectedUserToTeam
            }
          );
          Fire.$emit("AfterCreateUserTeam");
          Fire.$emit("AfterAddSelected");

          this.addNewUserItem = false;
          this.selectedUserToTeam = [];
    },
    // Assign specific user as computed
    async handleChangedSelectedUser() {
      const assignedd = await axios.put(
        "api/updateAssignedTeam/" + this.selected[0].id,
        { team_id: this.selectedTeam.id }
      );

      this.assignedCompt = assignedd.data.id;
      Fire.$emit("AfterAssigned");
      Fire.$emit("AfterCreateUserTeam");
      this.$emit("update:assignedComputed", this.assignedCompt);
    },

    // Assign multiple arena to specific team
    async addArenaSelectedToTeam() {
      let arenasTeam = [];

      this.selectedArenasToTeam.forEach((a) => {
        const { bank_details, contact_details, ...arenas } = a;
        arenasTeam.push({ ...arenas, team: this.selectedTeam.name });
      });

      const team = this.selectedTeam.name;
      await axios.put(
        `api/updateSelectedArenaToTeam/${team}`,
        this.selectedArenasToTeam
      );
      Fire.$emit("AfterCreateArenaTeam");
      Fire.$emit("AfterAddSelected");

      this.addNewArenaItem = false;
      this.selectedArenasToTeam = [];
    },

    // getAssignUserTeam(){
    //       axios.get('api/assigneduserteam/'+this.selectedTeam.id).then(({data}) => {
    //         //  this.assignedComputed = data.id.toString();
    //         this.selected.push({id: data.id})
    //      });
    // }
  },
  computed: {
    viewingTeam: {
      get() {
        return this.viewTeam;
      },
      set(value) {
        this.$emit("input", value);
      },
    },
  },
  created() {
    Fire.$on("AfterCreateUserTeam", () => {
      this.getAllUserPerTeam();
      this.loadTeam();
    });
    Fire.$on("AfterCreateArenaTeam", () => {
      this.getAllArenaPerTeam();
      this.loadTeam();
    });

    Fire.$on("AfterAssigned", () => {
      this.getAssignUserTeam();
      this.loadTeam();
    });

    Fire.$on("AfterAddSelected", () => {
      this.getArenasWithoutTeam();
      this.loadTeam();
    });
  },
};
</script>
<style scoped>
   .custom-color{
    color:#8DA90B;
    }

    .vcard{
        border-radius: 20px;
    }

    .add{
        background-color: #8DA90B;
        color:white;
    }

    .view{
        background-color: #3a89e8;
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
</style>
