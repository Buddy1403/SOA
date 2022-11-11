<template>
    <v-row class="signatory-container" >
        <v-col>
            <v-row>
                <div class="sign-wrapper d-flex align-center mt-2">
                    <span class="signedBy">Computed&nbsp;by:</span>
                    <div
                        class="sign-container d-flex justify-center align-center flex-column"
                    >
                        <span
                            class="select-field_container d-flex justify-center align-center"
                        >
                            <span class="computation signed-text signed-text_name">{{
                                arenaDetails.user_team &&
                                arenaDetails.user_team.user_details
                                    ? arenaDetails.user_team.user_details.name
                                    : ""
                            }}</span>
                        </span>

                        <span class="signed-title computation signed-text">
                            {{
                                arenaDetails.user_team &&
                                arenaDetails.user_team.user_details &&
                                arenaDetails.user_team.user_details
                                    .position_details
                                    ? arenaDetails.user_team.user_details
                                          .position_details.position
                                    : ""
                            }}
                        </span>
                    </div>
                </div>
            </v-row>
            <v-row>
                <div
                    class="sign-wrapper scbottom d-flex align-center mt-5 sign-wrapper-bottom"
                >
                    <span class="signedBy">Prepared&nbsp;by:</span>
                    <div
                        class="sign-container d-flex justify-center align-center flex-column"
                    >
                        <span
                            class="select-field_container d-flex justify-center align-center"
                        >
                            <span class="computation signed-text signed-text_name">{{
                                formatArrayToString(prepared).name ||
                                "Clarise A. Valles"
                            }}</span>
                        </span>

                        <span class="signed-title computation signed-text">
                            {{ formatArrayToString(prepared).position || "Finance Assistant - Kiosk" }}
                        </span>
                    </div>
                </div>
            </v-row>
        </v-col>
        <v-col>
            <v-row>
                <div
                    class="sign-wrapper d-flex mt-2 align-center"
                    style="display: flex; align-items: center; margin-top: 8px"
                >
                    <span class="signedBy">Checked&nbsp;by:</span>
                    <div
                        class="sign-container d-flex justify-center align-center flex-column"
                    >
                        <span
                            class="select-field_container d-flex justify-center align-center"
                        >
                            <span class="computation signed-text signed-text_name">{{
                                 formatArrayToString(checkSetOne).name ||
                                "Ma. Lourdes Anoba/Leo Tampilic Jr."
                            }}</span>
                        </span>

                        <span class="signed-title computation signed-text">
                            {{
                                formatArrayToString(checkSetOne).position ||
                                "QA - Team Leader"
                            }}
                        </span>
                    </div>
                </div>
            </v-row>
            <v-row>
                <div
                    class="sign-wrapper scbottom d-flex mt-5 align-center"
                    style="display: flex; align-items: center; margin-top: 20px"
                >
                    <span class="signedBy">Checked&nbsp;by:</span>
                    <div
                        class="sign-container d-flex justify-center align-center flex-column"
                    >
                        <span
                            class="select-field_container d-flex justify-center align-center"

                        >
                            <span
                                class="computation signed-text signed-text_name"

                                >{{
                                     formatArrayToString(checkSetTwo).name ||
                                    "Mariel Pilotos / Jonalyn Bonares"
                                }}</span
                            >
                        </span>

                        <span class="signed-title computation signed-text">
                            {{
                                 formatArrayToString(checkSetTwo).position ||
                                "Supervisor - Arena/OCBS"
                            }}
                        </span>
                    </div>
                </div>
            </v-row>
        </v-col>
    </v-row>
</template>
<script>
import {uniq} from 'lodash'
export default {
    name: "PreparedChecked",
    props: {
        arenaDetails: Object,
    },
    data() {
        return {
            prepared: [],
            checkSetOne: [],
            checkSetTwo: [],

        };
    },
    methods: {
        async usersSignatory(signatory) {
            const signedUsers = await axios.get(
                `api/getUsersSignatory/${signatory}`
            );


            this.$emit('signed', signedUsers)
            console.log(signedUsers)

           return signedUsers.data
        },

        async signatory(){
            const preparedUser = await this.usersSignatory('prepared');
            const checkSetOneUser = await this.usersSignatory('checkSetOne');
            const checkSetTwoUser = await this.usersSignatory('checkSetTwo');

            this.prepared = preparedUser
            this.checkSetOne = checkSetOneUser
            this.checkSetTwo = checkSetTwoUser
        },

        formatArrayToString(array) {
            const formatSignName = array.map((sign) => (sign["name"]));
            const name = formatSignName.length > 1
                ? formatSignName.join(" / ")
                : formatSignName.toString();

            const formatSignPosition = array.map(
                (sign) => sign.position_details.position
            );
            const uniqArrPosition = uniq(formatSignPosition);
            const position = uniqArrPosition.length > 1
                ? uniqArrPosition.join(" / ")
                : uniqArrPosition.toString();

            return {
                name,
                position
            }
        },

    },
    created() {
        this.signatory()
    },
};
</script>
