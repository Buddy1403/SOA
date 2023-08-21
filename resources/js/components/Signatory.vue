<template lang="">
    <v-app>
                <v-card class="pa-8 mx-auto w-50 mt-16">
                    <v-banner class="h4">SIGNATORY</v-banner>
                    <v-row>
                        <v-col>
                            <v-row>
                                <sign-box
                                    titleKey="Prepared by"
                                    :check.sync="checkSetOne"
                                    assignedSign="checkSetOne"
                                    :noOfSign="4"
                                     @checkSetOne="handleChangedOpenSignatory"
                                ></sign-box>
                            </v-row>
                            <v-divider></v-divider>
                            <v-row>
                                <sign-box
                                    titleKey="Checked by"
                                    :check.sync="prepared"
                                    assignedSign="prepared"
                                    :noOfSign="3"
                                     @prepared="handleChangedOpenSignatory"
                                ></sign-box>
                            </v-row>
                            <v-divider></v-divider>
                            <v-row>
                                <sign-box
                                    titleKey="Checked by"
                                    :check.sync="checkSetTwo"
                                    assignedSign="checkSetTwo"
                                    :noOfSign="4"
                                     @checkSetTwo="handleChangedOpenSignatory"

                                ></sign-box>
                            </v-row>
                        </v-col>
                    </v-row>
        </v-card>
        </v-app>
</template>
<script>
import SignBox from "./Sign/SignBox.vue";
export default {
    components: {
        SignBox,
    },
    data() {
        return {
            checkSetOne: false,
            checkSetTwo: false,
            prepared: false,
        };

    },
    methods: {
        handleChangedOpenSignatory(item){

            this.prepared = (item.checkSetOne || item.checkSetTwo) ? false : (!item.checkSetOne && !item.checkSetTwo && !item.prepared) ? false: true;
            this.checkSetOne = (item.prepared || item.checkSetTwo) ? false : (!item.checkSetOne && !item.checkSetTwo && !item.prepared) ? false: true;
            this.checkSetTwo = (item.prepared || item.checkSetOne) ? false : (!item.checkSetOne && !item.checkSetTwo && !item.prepared) ? false: true;
        }
    },
    computed: {

    }
};
</script>
