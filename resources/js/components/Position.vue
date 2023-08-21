<template>
    <v-container>
        <v-data-table
            :headers="positionHeaders"
            :items="position"
            :search="search"
            sort-by="calories"
            class="elevation-1 ma-4 custom-tbl"

        >
            <template  style="font-size:16px;"  v-for="header in positionHeaders" v-slot:[`header.${header.value}`]="{ header }">
                <v-icon medium color="#8DA90B">{{ header.icon }}</v-icon>
                <span style="color:#8DA90B"> {{ header.text }} </span>
            </template>
            <template v-slot:top>
                <v-toolbar style="border-radius:20px"
                    flat
                >
                    <v-toolbar-title class="font-weight-bold custom-color">POSITIONS</v-toolbar-title>

                    <v-spacer></v-spacer>
                        <v-btn
                            color="#8DA90B"
                            dark
                            class="mb-2"
                            @click="addnewPostion()"
                        >
                        <i class="fas fa-plus fa-fw circle-icon"></i>
                        &nbsp; Add New Position
                        </v-btn>
                </v-toolbar>
                </template>
                    <template v-slot:[`item.PositionAction`]="{ item }">

                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                        <v-btn
                                        color="white"
                                        class="edit custom-btn"
                                        icon
                                        dark
                                        v-bind="attrs"
                                        v-on="on"
                                        @click="editPositionModal(item)"
                                        >
                                        UPDATE
                                        </v-btn>
                                </template>
                            <span>Edit User Info</span>
                            </v-tooltip>

                            <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                   <v-btn
                                    color="white"
                                    dark
                                    icon
                                    v-bind="attrs"
                                    v-on="on"
                                    class="mx-2 delete custom-btn"
                                    @click="deletePosition(item.id)"
                                    >
                                    DELETE
                                    </v-btn>
                                </template>
                            <span>Delete info User</span>
                            </v-tooltip>
                    </template>
        </v-data-table>

            <!-- Position Modal -->
        <div class="modal fade"  id="addPostion" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered"  role="document">
                <div class="modal-content custom-tbl">
                <div class="modal-header">
                    <h5 class="modal-title custom-color" v-show="!editmode" id="addNewLabel"><i class="fas fa-user-plus fa-fw"></i>&nbsp; Add  Position</h5>
                    <h5 class="modal-title custom-color" v-show="editmode" id="addNewLabel"><i class="fas fa-user-edit fa-fw"></i>&nbsp; Update Position</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="editmode ? updatePosition() : createPosition()">
                    <div class="modal-body">
                            <v-text-field
                                color="#8DA90B"
                                outlined
                                prepend-inner-icon="mdi-crosshairs custom-color"
                                label="Position"
                                placeholder="user position"
                                v-model="positionform.position"
                                :rules="[
                                    () => !!positionform.position || 'This field is required',

                                ]"
                                required
                        ></v-text-field>
                    </div>
                    <div class="modal-footer">
                        <v-btn type="button" color="error" elevation="2" data-dismiss="modal">Close</v-btn>
                        <v-btn v-show="editmode" type="submit" class="add" color="#8DA90B" elevation="2">Update</v-btn>
                        <v-btn v-show="!editmode" type="submit" class="add" color="#8DA90B"  elevation="2">Add</v-btn>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </v-container>
</template>

<script>
    export default {
        name: 'position',
        data() {
            return {

                positionHeaders : [

                    { text: 'Position', value: 'position', icon: 'mdi-account-switch' },
                    { text: '', value: 'PositionAction', sortable: false},
                ],
                editmode: false,

                length: '',
                search: '',

                position:[],
                positionNew:'',

                positionform: new Form({
                    id:'',
                    position: '',
                }),

            }
        },
        methods: {

            addnewPostion(){
                $('#addPostion').modal('show');
                 this.positionform.reset();
                   this.editmode = false;
            },
            createPosition(){
                this.positionform.post('api/saveposition').then((data)=> {
                    console.log(data)
                      Fire.$emit('AfterCreate');
                      Toast.fire({
                        icon: 'success',
                        title: 'Position Added in successfully'
                        })
                         $('#addPostion').modal('hide');
                });
            },
            updatePosition(){
                this.positionform.put('api/updateposition/'+this.positionform.id).then((data)=> {

                      Fire.$emit('AfterCreate');
                      Toast.fire({
                        icon: 'success',
                        title: 'Position Added in Updated'
                        })
                         $('#addPostion').modal('hide');
                });
            },
            editPositionModal(data){
                this.editmode = true;
                this.positionform.reset();
                $('#addPostion').modal('show');
                this.positionform.fill(data);
            },
            loadPosition(){
                axios.get('api/getposition').then((data)=>{
                    this.position = data.data;

                });
            },
            deletePosition(id){
                  swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#8DA90B',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                         if (result.value) {
                              axios.get('api/deletePosition/'+id).then((data)=> {
                                    swal.fire(
                                        'Deleted!',
                                        'Your file has been deleted.',
                                        'success'
                                        )
                                    Fire.$emit('AfterCreate');
                                });
                         }
                    })

            },
        },

        created() {
            this.loadPosition();
           Fire.$on('AfterCreate',() => {
                this.loadPosition();
           });
        }
    }
</script>

<style scoped>
    .custom-color{
    color:#8DA90B;
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

</style>

