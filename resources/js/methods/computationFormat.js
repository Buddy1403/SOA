const computationSoa = (data) => {

    const totalMWBets = data.total_meron_wala;
    const drawCancelled = data.draw_cancelled;
    const draw = data.draw;
    const totalPayoutPaid = data.total_payout_paid;
    const cdPaid = data.draw_cancelled_paid;
    const drawPaid = data.draw_paid;
    const cUnpaid = data.cancelled_unpaid;
    const salesDeduction = data.salesDeductionTablet;
    const otherCommissionIntel01 = data.otherCommissionIntel01;
    const totalMWMobile = data.total_win_mobile;
    const drawMobile = data.draw_mobile;
    const depositReplenish = parseFloat(data.for_total).toFixed(2);
    const totalCommission = data.totalCommission;
    const netOpCommission = data.netOperatorsCommission;
    const consolidatorsCommission = data.consolidatorsCommission;
    const paymentForOutstandingBalance = data.paymentForOutstandingBalance;
    const safetyFund = data.safetyFund;
    const safetyFundMob = data.safetyFundMob;
    const otherCommIntMob = data.otherCommIntMob;
    const consolCommMob = data.consolCommMob;
    const payOutsBalMob = data.payOutsBalMob;
    const totalSafetyFund = parseFloat(safetyFund) + parseFloat(safetyFundMob);
    const totalConsolComm =
        parseFloat(consolidatorsCommission) + parseFloat(consolCommMob);
    const totalPayOutBal =
        parseFloat(paymentForOutstandingBalance) + parseFloat(payOutsBalMob);
    const otherCommission = data.otherCommission;


    //2%
    const mwTwoPer = data.mwTwo;
    const drawTwoPer = data.drawTwo;


    //LESS WITH HOLDING TAX (SEPCIAL)

    const specialMRA = parseFloat(totalMWBets) + parseFloat(draw) ;
    const specialWHT =parseFloat(specialMRA *0.02 * 0.2 * 0.02);

    //LESS WITH HOLDING TAX

    const totalComm1 = parseFloat(mwTwoPer) + parseFloat(drawTwoPer);

    const lessWithHoldingTax = parseFloat(totalComm1 * 0.2 * 0.02).toFixed(2);
    const totalNetComm = parseFloat(netOpCommission).toFixed(2) + parseFloat(lessWithHoldingTax).toFixed(2);
    const totalNetCommWithTax = parseFloat(netOpCommission) - parseFloat(otherCommission) - parseFloat(lessWithHoldingTax);
    const specialTotalNetCommWithTax = parseFloat(netOpCommission) - parseFloat(otherCommission) - parseFloat(specialWHT);

    console.log(parseFloat(netOpCommission) , parseFloat(otherCommission) , parseFloat(specialWHT));

    const computation = {
        totalMWBets,
        drawCancelled,
        totalPayoutPaid,
        cdPaid,
        drawPaid,
        cUnpaid,
        salesDeduction,
        netOpCommission,
        totalCommission,
        depositReplenish,
        drawMobile,
        totalMWMobile,
        safetyFund: totalSafetyFund,
        totalSafetyFund : totalSafetyFund,
        consolidatorsCommission: totalConsolComm,
        totalConsolComm : totalConsolComm,
        paymentForOutstandingBalance: totalPayOutBal,
        lessWithHoldingTax : lessWithHoldingTax,
        totalNetComm: totalNetComm,
        totalNetCommWithTax: totalNetCommWithTax,
        specialWHT: specialWHT,
        specialTotalNetCommWithTax: specialTotalNetCommWithTax,
        otherCommission: otherCommission,
        ...data,
    };

    return computation;
};

export { computationSoa };
