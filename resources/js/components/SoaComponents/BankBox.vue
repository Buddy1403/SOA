<template>
    <v-row
        class="bank-container"
        style="padding: 1px !important; height: auto; margin: 0 !important"
    >
        <div
            class="bank-wrapper"
            style="padding: 1px 30px; margin: 0 !important"
        >
            <div class="bank-headline mr-6 detailed" style="font-weight: 500">
                <span>{{ depositReplenishText.bankTitle }}: </span>
            </div>

            <div
                class="bank-content"
                v-if="depositReplenishText.totalText === 'Deposit'"
            >
                <div class="bank bank1">
                    <span class="bank_account-name detailed">{{
                        bankAccounts.length > 0
                            ? bankAccounts[0].account_name
                            : "LUCKY 8 STAR QUEST INC."
                    }}</span>
                    <span class="detailed">{{
                        bankAccounts.length > 0
                            ? bankAccounts[0].bank_name
                            : "PBB"
                    }}</span>
                    <span class="detailed">{{
                        bankAccounts.length > 0
                            ? bankAccounts[0].bank_number
                            : "0050-1001-5156"
                    }}</span>
                </div>

                <div v-show="bankAccounts.length >= 2" class="or">OR</div>
                <div v-show="bankAccounts.length === 0" class="or">OR</div>
                <div class="bank bank2">
                    <span class="bank_account-name detailed">{{
                        bankAccounts.length === 0
                            ? "LUCKY 8 STAR QUEST INC."
                            : bankAccounts.length > 1
                            ? bankAccounts[1].account_name
                            : ""
                    }}</span>
                    <span class="detailed">{{
                        bankAccounts.length === 0
                            ? "PBCOM"
                            : bankAccounts.length > 1
                            ? bankAccounts[1].bank_name
                            : ""
                    }}</span>
                    <span class="detailed">{{
                        bankAccounts.length === 0
                            ? "0248-20-100964-4"
                            : bankAccounts.length > 1
                            ? bankAccounts[1].bank_number
                            : ""
                    }}</span>
                </div>
            </div>

            <div class="bank-content d-flex justify-center align-center" v-else>
                <div class="bank bank1">
                    <span
                        class="text-sm font-weight-medium bank_account-name detailed"
                        >{{
                            arenaDetails &&
                            arenaDetails.bank_details &&
                            arenaDetails.bank_details.length > 0
                                ? arenaDetails.bank_details[0].account_name
                                : ""
                        }}</span
                    >
                    <span
                        v-if="!editmode"
                        class="text-sm font-weight-medium detailed"
                    >
                        {{
                            arenaDetails &&
                            arenaDetails.bank_details &&
                            arenaDetails.bank_details.length > 0
                                ? arenaDetails.bank_details[0].bank_name
                                : ""
                        }}</span
                    >
                    <span class="text-sm font-weight-medium detailed">{{
                        arenaDetails &&
                        arenaDetails.bank_details &&
                        arenaDetails.bank_details.length > 0
                            ? arenaDetails.bank_details[0].bank_number
                            : ""
                    }}</span>
                </div>
            </div>
        </div>
    </v-row>
</template>
<script>
export default {
    name: "BankBox",
    props: {
        bankAccounts: Array,
        arenaDetails: Object,
        operatorName: String,
        editmode: Boolean,
        depositReplenishText: Object,
    },

};
</script>
