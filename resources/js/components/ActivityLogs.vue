<template>
<v-app>
    <v-card outlined color="transparent vcard">
        	<v-card-title class="custom-color">
                Activity Logs
                <v-spacer></v-spacer>
                <v-text-field
                    v-model="search"
                    color="#8DA90B"
                    label="Search"
                    class="mx-4 tfield"
                    clearable
                >
                    <template v-slot:prepend-inner>
                        <v-icon outlined dark color="#8DA90B">mdi-magnify</v-icon>
                    </template>
                </v-text-field>
            </v-card-title>
                  <v-data-table
                    :headers="activityHeaders"
                    :items="activity"
                    :search="search"
                    sort-by="calories"
                    class="elevation-1 ma-4 custom-tbl"
                >
                <template  style="font-size:16px;"  v-for="header in activityHeaders" v-slot:[`header.${header.value}`]="{ header }">
                    <v-icon medium color="#8DA90B"> {{ header.icon }}</v-icon>
                    <span style="color:#8DA90B"> &nbsp;{{ header.text }} </span>
                </template>
                <template v-slot:[`item.date`]="{ item }">
                            <span>{{item.created_at | myDatewithtime}}</span>
                </template>
                <template v-slot:[`item.causer`]="{ item }">
                            <span>{{item.user == null ? "admin" : item.user.name }}</span>
                             <!-- <span>{{item}}</span> -->
                </template>
                <template v-slot:[`item.Description`]="{ item }">
                            <span>{{item.log_name}}
                                {{item.description}}
                                in
                                {{item.subject_type}}
                                on
                                {{item.created_at | myDatewithtime}} </span>
                </template>
                </v-data-table>

    </v-card>
    </v-app>
</template>

<script>
    export default {
        data() {
            return {

                activityHeaders : [
                    { text: 'Date', value: 'date', icon: 'mdi-calendar' },
                    { text: 'By User', value: 'causer', icon: 'mdi-account'  },
                    { text: 'Action', value: 'description', icon: 'mdi-arrow-right-drop-circle'  },
                    { text: 'Ip Address', value: 'ip_address', icon: 'mdi-console-network'},
                    { text: 'Description', value: 'Description', icon: 'mdi-format-align-left'  },
                ],
                search: '',
                activity:[],


            }
        },
        methods: {
            loadActivity(){
                axios.get('api/indexActivity').then((data)=>{
                    // console.log(data);
                    this.activity = data.data;
                });
            },

        },
        created() {
            this.loadActivity();
        }
    }
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
        box-shadow: none;
    }

    .tfield{
        border-bottom-color: #8DA90B;
    }
</style>