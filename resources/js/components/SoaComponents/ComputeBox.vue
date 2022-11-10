<template>
    <v-col>
        <v-row class="row-compute">
            <div class="compute-container-box">
                <div>
                    <div
                        class="d-flex justify-center align-center kiosk-mobile_text"
                    >
                        <span>( Kiosk )</span>
                    </div>
                    <div
                        class="d-flex align-center justify-end computation-wrapper_field"
                    >
                        <span class="custom-label">Total M/W Bets:</span>
                        <div
                            class="custom-span computation-span d-flex align-center"

                        >
                            <span>{{ moneyFormat(computation.totalMWBets) }}</span>
                        </div>
                    </div>

                    <div
                        class="d-flex align-center justify-end computation-wrapper_field"
                    >
                        <span class="custom-label">Total Cancelled Bets:</span>
                        <div
                            class="d-flex align-center computation-container_field"
                        >
                            <span class="operation">+</span>
                            <div
                                class="custom-span computation-span d-flex align-center"

                            >
                                <span>{{ moneyFormat(computation.drawCancelled) }}</span>
                            </div>
                        </div>
                    </div>
                    <div
                        class="d-flex align-center justify-end computation-wrapper_field"
                    >
                        <span class="custom-label">Total Draw Bets:</span>
                        <div
                            class="d-flex align-center computation-container_field"
                        >
                            <span class="operation">+</span>
                            <div
                                class="custom-span computation-span d-flex align-center"

                            >
                                <span>{{ moneyFormat(computation.draw) }}</span>
                            </div>
                        </div>
                    </div>
                    <div
                        class="d-flex align-center justify-end computation-wrapper_field"
                    >
                        <span class="custom-label">Total Payout Paid:</span>
                        <div
                            class="d-flex align-center computation-container_field"
                        >
                            <span class="operation">-</span>
                            <div
                                class="custom-span computation-span d-flex align-center"

                            >
                                <span>{{ moneyFormat(computation.totalPayoutPaid) }}</span>
                            </div>
                        </div>
                    </div>
                    <div
                        class="d-flex align-center justify-end computation-wrapper_field"
                    >
                        <span class="custom-label">Total C/D Paid:</span>

                        <div
                            class="d-flex align-center computation-container_field"
                        >
                            <span class="operation">-</span>
                            <div
                                class="custom-span computation-span d-flex align-center"

                            >
                                <span>{{ moneyFormat(computation.cdPaid) }}</span>
                            </div>
                        </div>
                    </div>
                    <div
                        class="d-flex align-center justify-end computation-wrapper_field"
                    >
                        <span class="custom-label">Total Draw Paid:</span>
                        <div
                            class="d-flex align-center computation-container_field"
                        >
                            <span class="operation">-</span>
                            <div
                                class="custom-span computation-span d-flex align-center"
                            >

                                <span>{{ moneyFormat(computation.drawPaid) }}</span>
                            </div>
                        </div>
                    </div>

                    <div
                        class="d-flex align-center justify-end computation-wrapper_field"
                    >
                        <span class="font-weight-bold custom-label ctotal-label"
                            >Net Win/Loss:</span
                        >
                        <div
                            class="d-flex align-center computation-container_field bordertop"
                        >
                            <span class="operation">=</span>
                            <div
                                class="custom-span computation-span d-flex align-center computation-total"
                            >
                                <span class="ctotal-text">{{
                                    moneyFormat(computation.netWinLoss)
                                }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div
                        class="d-flex align-center justify-end computation-wrapper_field"
                    >
                        <span class="font-weight-bold custom-label ctotal-label"
                            >Net Win/Loss:</span
                        >
                        <div
                            class="d-flex align-center computation-container_field borderbottom computation"
                        >
                            <span class="operation">=</span>
                            <div
                                class="custom-span computation-span d-flex align-center computation-total"
                            >
                                <span class="ctotal-text">{{
                                    moneyFormat(computation.netWinLoss)
                                }}</span>
                            </div>
                        </div>
                    </div>
                    <div
                        v-if="!computation.arena_details.email_details[0].email.includes('dea.greatpeak@gmail.com')"
                        class="d-flex align-center justify-end computation-wrapper_field"
                    >
                        <span class="custom-label"
                            >M/W*{{ commissionPercent * 100 }}% (kiosk):</span
                        >
                        <div
                            class="d-flex align-center computation-container_field"
                        >
                            <span class="operation">+</span>
                            <div
                                class="custom-span computation-span d-flex align-center computation"
                            >
                                <span>{{ moneyFormat(computation.mwTwo) }}</span>
                            </div>
                        </div>
                    </div>
                    <div
                        v-if="!computation.arena_details.email_details[0].email.includes('dea.greatpeak@gmail.com')"
                        class="d-flex align-center justify-end computation-wrapper_field"
                    >
                        <span class="custom-label">Draw*{{ commissionPercent * 100 }}% (kiosk):</span
>
                        <div
                            class="d-flex align-center computation-container_field"
                        >
                            <span class="operation">+</span>
                            <div
                                class="custom-span computation-span d-flex align-center"
                            >
                                <span>{{ moneyFormat(computation.drawTwo) }}</span>
                            </div>
                        </div>
                    </div>
                    <div
                        v-if="!computation.arena_details.email_details[0].email.includes('dea.greatpeak@gmail.com')"
                        class="d-flex align-center justify-end computation-wrapper_field"
                    >
                        <span class="custom-label"
                            >M/W*{{ commissionPercent * 100 }}% (mobile):</span
                        >
                        <div
                            class="d-flex align-center computation-container_field"
                        >
                            <span class="operation">+</span>
                            <div
                                class="custom-span computation-span d-flex align-center"
                            >
                                <span>{{
                                    moneyFormat(computation.mwTwoMobile)
                                }}</span>
                            </div>
                        </div>
                    </div>
                    <div
                        v-if="!computation.arena_details.email_details[0].email.includes('dea.greatpeak@gmail.com')"
                        class="d-flex align-center justify-end computation-wrapper_field"
                    >
                        <span class="custom-label"
                            >Draw*{{ commissionPercent * 100 }}% (mobile):</span
                        >
                        <div
                            class="d-flex align-center computation-container_field"
                        >
                            <span class="operation">+</span>
                            <div
                                class="custom-span computation-span d-flex align-center"
                            >
                                <span>{{
                                    moneyFormat(computation.drawTwoMobile)
                                }}</span>
                            </div>
                        </div>
                    </div>
                    <div
                        class="d-flex align-center justify-end computation-wrapper_field"
                    >
                        <span class="custom-label">Total Unclaimed:</span>
                        <div
                            class="d-flex align-center computation-container_field"
                        >
                            <span class="operation">+</span>
                            <div
                                class="custom-span computation-span d-flex align-center"

                            >
                                <span>{{ moneyFormat(computation.unclaimed) }}</span>
                            </div>
                        </div>
                    </div>
                    <div
                        class="d-flex align-center justify-end computation-wrapper_field"
                    >
                        <span class="custom-label">Total C Unpaid:</span>
                        <div
                            class="d-flex align-center computation-container_field"
                        >
                            <span class="operation">+</span>
                            <div
                                class="custom-span computation-span d-flex align-center"

                            >
                                <span>{{ moneyFormat(computation.cUnpaid) }}</span>
                            </div>
                        </div>
                    </div>
                    <div
                        v-if="!computation.arena_details.email_details[0].email.includes('dea.greatpeak@gmail.com')"
                        class="d-flex align-center justify-end computation-wrapper_field"
                    >
                        <span class="custom-label">Sales Deduction:</span>
                        <div
                            class="d-flex align-center computation-container_field"
                        >
                            <span class="operation">-</span>
                            <div
                                class="custom-span computation-span d-flex align-center"

                            >
                                <span>{{ moneyFormat(computation.salesDeduction) }}</span>
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="!computation.arena_details.email_details[0].email.includes('dea.greatpeak@gmail.com')"
                        class="d-flex align-center justify-end computation-wrapper_field"
                    >
                        <span class="custom-label"
                            >Payment for outstanding balance:</span
                        >
                        <div
                            class="d-flex align-center computation-container_field"
                        >
                            <span class="operation">-</span>
                            <div
                                class="custom-span computation-span d-flex align-center"

                            >
                                <span>{{
                                    moneyFormat(computation.paymentForOutstandingBalance)
                                }}</span>
                            </div>
                        </div>
                    </div>
                    <div
                        v-if="!computation.arena_details.email_details[0].email.includes('dea.greatpeak@gmail.com')"
                        class="d-flex align-center justify-end computation-wrapper_field"
                    >
                        <span class="custom-label"
                            >Other Deductibles from Commission:</span
                        >
                        <div
                            class="d-flex align-center computation-container_field"
                        >
                            <span class="operation">-</span>
                            <div
                                class="custom-span computation-span d-flex align-center"

                            >
                                <span>{{
                                    moneyFormat(computation.otherDeductiblesFromCommission)
                                }}</span>
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="!computation.arena_details.email_details[0].email.includes('dea.greatpeak@gmail.com')"
                        class="d-flex align-center justify-end computation-wrapper_field"
                    >
                        <span class="font-weight-bold custom-label ctotal-label"
                            >Net Operator's Commission:</span
                        >
                        <div
                            class="d-flex align-center computation-container_field"
                        >
                            <span class="operation">=</span>
                            <div
                                class="custom-span computation-span d-flex align-center computation-total"
                            >
                                <span>{{ moneyFormat(computation.netOpCommission) }}</span>
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="!computation.arena_details.email_details[0].email.includes('dea.greatpeak@gmail.com')"
                        class="d-flex align-center justify-end computation-wrapper_field"
                    >
                        <span class="font-weight-bold custom-label ctotal-label"
                            >Withholding Tax:</span
                        >
                        <div
                            class="d-flex align-center computation-container_field"
                        >
                            <span class="operation">=</span>
                            <div
                                class="custom-span computation-span d-flex align-center computation-total"
                                style="background-color: rgb(174,185,203);"
                            >
                                <span v-if="computation.arena_details.area_code != 'MRA01'">
                                    {{ moneyFormat(computation.lessWithHoldingTax) }}
                                </span>
                                <span v-else>
                                    {{ moneyFormat(computation.specialWHT) }}
                                </span>

                            </div>
                        </div>
                    </div>

                    <div
                        v-if="!computation.arena_details.email_details[0].email.includes('dea.greatpeak@gmail.com')"
                        class="d-flex align-center justify-end computation-wrapper_field"
                    >
                        <span class="font-weight-bold custom-label ctotal-label"
                            >Net Operator's Commision, after tax:</span
                        >
                        <div
                            class="d-flex align-center computation-container_field bordertop"
                        >
                            <span class="operation">=</span>
                            <div
                                class="custom-span computation-span d-flex align-center computation-total"
                            >
                                <span class="ctotal-text">{{
                                    moneyFormat(computation.totalNetCommWithTax.toFixed(2), true)
                                }}</span>
                            </div>
                        </div>
                    </div>
                    <div
                        class="d-flex align-center justify-end computation-wrapper_field"
                    >
                        <span class="custom-label">Cash Load from Mobile:</span>
                        <div
                            class="d-flex align-center computation-container_field"
                        >
                            <span class="operation">+</span>
                            <div
                                class="custom-span computation-span d-flex align-center"

                            >
                                <span>{{ moneyFormat(computation.cashLoad) }}</span>
                            </div>
                        </div>
                    </div>
                    <div
                        class="d-flex align-center justify-end computation-wrapper_field"
                    >
                        <span class="custom-label"
                            >Cash Withdrawal from Mobile:</span
                        >
                        <div
                            class="d-flex align-center computation-container_field"
                        >
                            <span class="operation">-</span>
                            <div
                                class="custom-span computation-span d-flex align-center"

                            >
                                <span>{{
                                    moneyFormat(computation.cashWithdrawal)
                                }}</span>
                            </div>
                        </div>
                    </div>
                    <div
                        class="d-flex align-center justify-end computation-wrapper_field"
                    >
                        <span class="font-weight-bold custom-label ctotal-label"
                            >For {{ depositReplenishTxt.totalText }}:</span
                        >
                        <div
                            class="d-flex align-center computation-container_field bordertop"
                        >
                            <span class="operation">=</span>
                            <div
                                class="custom-span computation-span d-flex align-center overtotal"
                            >
                                <span class="ctotal-text">{{
                                    moneyFormat(computation.depositReplenish)
                                }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </v-row>
        <v-row class="row-compute">
            <div class="d-flex mobile-computebox" :style="[!computation.arena_details.email_details[0].email.includes('dea.greatpeak@gmail.com') ? {'margin-top': '-85px'} : {'margin-top': '50px'}]">
                <div>
                    <div
                        class="d-flex justify-center align-center kiosk-mobile_text"
                    >
                        <span>(Mobile)</span>
                    </div>
                    <div
                        class="d-flex align-center justify-end computation-wrapper_field"
                    >
                        <span class="custom-label">Total M/W Bets:</span>
                        <div
                            class="d-flex align-center computation-container_field"
                        >
                            <div
                                class="custom-span computation-span d-flex align-center"

                            >
                                <span>{{
                                    moneyFormat(computation.totalMWMobile)
                                }}</span>
                            </div>
                        </div>
                    </div>
                    <div
                        class="d-flex align-center justify-end computation-wrapper_field"
                    >
                        <span class="custom-label">Total Draw Bets:</span>
                        <div
                            class="d-flex align-center computation-container_field"
                        >
                            <div
                                class="custom-span computation-span d-flex align-center"

                            >
                                <span>{{
                                    moneyFormat(computation.drawMobile)
                                }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </v-row>
    </v-col>
</template>

<script>
import {moneyFormat} from "../../utility/format";
export default {
    name: "ComputeBox",
    props: {
        computation: Object,
        editmode: Boolean,
        commissionPercent: Number,
        depositReplenishTxt: Object,
        emailFormat: String,
    },
    data: () => ({
        moneyFormat,
    }),
};
</script>
