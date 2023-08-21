<template>
    <v-col>
        <div class="d-flex align-center arenaInfo">
            <span class="custom-label arenaLabel">Arena / OCBS Name:</span>
            <div
                class="custom-span fullspan d-flex align-center"
                :class="{ 'editmode-span': editmode }"
            >
                <span
                    style="text-transform: uppercase; font-weight: 900"
                    class="computation"
                    >{{ arenaName }}</span
                >
            </div>
        </div>
        <div class="d-flex align-center arenaInfo">
            <span class="custom-label arenaLabel">Address:</span>
            <div
                class="custom-span fullspan d-flex align-center custom-textarea"
                :class="{ 'editmode-span': editmode }"
            >
                <span
                    style="text-transform: uppercase"
                    class="input-field_report computation arenaLabel"
                    >{{ arenaDetails.address }}</span
                >
            </div>
        </div>
        <div class="d-flex align-center arenaInfo">
            <span class="custom-label arenaLabel">Operator:</span>
            <div
                class="custom-span fullspan d-flex align-center"
                :class="{ 'editmode-span': editmode }"
            >
                <span
                    style="text-transform: uppercase"
                    class="input-field_report computation arenaLabel"
                    >{{ arenaDetails.operator }}</span
                >
            </div>
        </div>
        <div class="d-flex align-center arenaInfo">
            <span class="custom-label arenaLabel">Contact Number:</span>
            <div
                class="custom-span fullspan d-flex align-center"
                :class="{ 'editmode-span': editmode }"
            >
                <span class="input-field_report computation arenaLabel">{{
                    contactFormat
                }}</span>
            </div>
        </div>
        <div class="d-flex align-center arenaInfo">
            <span class="custom-label arenaLabel">Email Address:</span>
            <div
                class="custom-span text-small fullspan d-flex align-center"
                :class="{ 'editmode-span': editmode }"
            >
                <span class="input-field_report computation emailFormat">
                    {{ arenaDetails.email_details[0].email }}
                </span>
            </div>
        </div>
    </v-col>
</template>
<script>
export default {
    name: "ArenaDetails",
    props: {
        arenaDetails: Object,
        arenaName: String,
        editmode: Boolean,
        emailFormat: String,
        contactFormat: String,
    },
};
</script>
<style scoped>
.emailFormat {
    color: rgb(0, 123, 238);
}
</style>
