<template lang="">
    <div
        class="report-soa_container mx-auto d-flex flex-column"
        id="report-soa_container"
      
    >
        <div
            v-for="item in selected"
            :key="item.codeEvent"
            class="reportsoaoutput"
            id="reportsoaoutput"
        >
            <soa-card :item="item">
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
                        days.
                    </span>

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
            </soa-card>
        </div>
    </div>
</template>
<script>
import SoaCard from './SoaCard.vue'

export default {
    name: 'soa-cards',
    components: {
        SoaCard,
    },
    props: {
        selected: Array,
    },
};
</script>
<style lang=""></style>
