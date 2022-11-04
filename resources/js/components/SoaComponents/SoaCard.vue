<template lang="">
      <v-card ref="soaReport">
                <v-card-title
                    class="text-h5 text-center font-weight-medium d-flex justify-center align-center pdf-title"
                >
                    <span>{{
                        item.group === "Replenish"
                            ? "For Replenishment"
                            : "Statement of Account"
                    }}</span>
                </v-card-title>
                <v-card-text class="text-sm-body-2">
                    <v-row>
                        <v-spacer></v-spacer>
                        <v-spacer></v-spacer>
                        <DateSOA
                            :depositReplenishText="
                                item.group === 'Replenish'
                                    ? {
                                          title: 'For Replenishment',
                                          dateText: 'FR',
                                          totalText: 'Replenish',
                                          bankTitle: 'We will replenish to',
                                      }
                                    : {
                                          title: 'Statement of Account',
                                          dateText: 'SOA',
                                          totalText: 'Deposit',
                                          bankTitle: 'Kindly Deposit to',
                                      }
                            "
                            :refNo="item.refNo"
                            :dateEvent="moment(item.date_of_soa).format('LL')"
                            :dateSoa="moment(item.date_closed).format('LL')"
                        />
                    </v-row>
                    <v-row>
                        <ArenaDetails
                            :arenaDetails="
                                item.arena_details
                                    ? item.arena_details
                                    : { arena: item.arena_name }
                            "
                            :arenaName="item.arena_name"
                            :emailDetails="item.email_details"
                            :emailFormat="
                                item.arena_details
                                    ? defineEmail(
                                          item.arena_details.email_details
                                      )
                                    : ''
                            "
                            :contactFormat="
                                item.arena_details
                                    ? defineContact(
                                          item.arena_details.contact_details
                                      )
                                    : ''
                            "
                        />
                    </v-row>
                    <v-row>
                        <div class="computation-banner">Computation</div>
                    </v-row>
                    <ComputeBox
                        :computation="computationSoa(item)"
                        :commissionPercent="commission_percent"
                        :depositReplenishTxt="
                            item.group === 'Replenish'
                                ? {
                                      totalText: 'Replenishment',
                                  }
                                : {
                                      totalText: 'Deposit',
                                  }
                        "
                    />
                    <span
                        v-if="item.group === 'Replenish'"
                        class="text-xs my-2"
                        style="color: #e64a19"
                        >Please be advised that replenishment are only available
                        during banking days. We allow off setting of pending
                        remittances and replenishments during non-banking
                        days.</span
                    >

                    <BankBox
                        :bankAccounts="bankAccounts || []"
                        :arenaDetails="item.arena_details"
                        :operatorName="
                            item.arena_details
                                ? item.arena_details.operator
                                : ''
                        "
                        :depositReplenishText="
                            item.group === 'Replenish'
                                ? {
                                      totalText: 'Replenish',
                                      bankTitle: 'We will replenish to',
                                  }
                                : {
                                      totalText: 'Deposit',
                                      bankTitle: 'Kindly Deposit to',
                                  }
                        "
                    />
                </v-card-text>
            </v-card>
</template>
<script>
import moment from 'moment'
import DateSOA from './DateSOA.vue'
import ArenaDetails from './ArenaDetails.vue'
import ComputeBox from './ComputeBox.vue'
import BankBox from './BankBox.vue'
import {
    computationSoa,
} from "../../methods";
import {
    numberUnformat,
    moneyFormat,
    defineEmail,
    defineContact,
} from "../../utility";
export default {
    name: 'soa-card',
    components: {
        DateSOA,
        ArenaDetails,
        ComputeBox,
        BankBox
    },
    props: {
        item: Object
    },
     data: () => ({
        computationSoa,
        defineEmail,
        defineContact,
        moment,
        commission_percent: 0.02,
        bankAccounts: [],
    }),
    methods: {
        loadBankDetails() {
            // Load Company Bank Details
            axios.get("api/Companybanks").then(({ data }) => {
                this.bankAccounts = data;
            });
        },
    },
    created(){
       this.loadBankDetails()
    }

}
</script>
<style lang="">

</style>