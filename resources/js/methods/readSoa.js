import moment from "moment";
import XLSX from "xlsx";
import CryptoJS from "crypto-js";
import {
    camelCase,
    groupBy,
    map,
    spread,
    values,
    assign,
    concat,
    sortBy,
} from "lodash";
import { mergeObject, valueSplit } from "../utility";

// Convert all object values to a string
function toString(o) {
    Object.keys(o).forEach((k) => {
        if (typeof o[k] === "object") {
            return toString(o[k]);
        }
        o[k] = "" + o[k];
    });

    return o;
}

// if date is serial
const ExcelDateToJSDate = (serial) => {
    const utcDays = Math.floor(serial - 25569);
    const utcValues = utcDays * 86400;
    const dateInfo = new Date(utcValues * 1000);
    const fractionalDay = serial - Math.floor(serial) + 0.0000001;
    let totalSeconds = Math.floor(86400 * fractionalDay);
    const seconds = totalSeconds % 60;
    totalSeconds -= seconds;
    const hours = Math.floor(totalSeconds / (60 * 60));
    const minutes = Math.floor(totalSeconds / 60) % 60;
    return new Date(
        dateInfo.getFullYear(),
        dateInfo.getMonth(),
        dateInfo.getDate(),
        hours,
        minutes,
        seconds
    );
};

const readSoa = (event, isExcel, withSite) => {

    let arenaReportFiltered = [];
    const file = event ? event : null;
    const checkfile = event.name.includes("xlsx") || event.name.includes("csv");

    if (event && checkfile) {
        isExcel = true;

        const reader = new FileReader();
        let arrayData = [];
        let reportCombined = [];
        let eventsCombined = [];
        let summaryReport = [];
        let objMobileKiosk = [];
        let eventDetailsA = [];

        reader.onload = (e) => {
            // eslint-disable-next-line no-unused-vars
            const bstr = e.target.result;
            const wb = XLSX.read(bstr, { type: "binary" });
            const ws = wb.SheetNames;

            // Select only specific excel sheet
            const filteredWS = ws.filter(function (value, index, arr) {
                const accReportComb = "Accounts Report Combined";
                const summaryRep = "REPORT FOR FA";
                return (
                    camelCase(value) === camelCase(accReportComb) ||
                    camelCase(value) === camelCase(summaryRep)
                );
            });

            filteredWS.forEach((w) => {
                const singleSheet = wb.Sheets[w];
                arrayData.push(
                    XLSX.utils.sheet_to_json(singleSheet, {
                        header: "A",
                        defval: 0,
                    })
                );
            });

            const siteLists = ["A", "B"];

            const checkSiteExist = siteLists.some((s) =>
                Object.values(arrayData[1][5]).includes(s)
            );


            if (checkSiteExist) {
                withSite = true
                arrayData[1].map((r) => {
                    if (Object.keys(r).length >= 17) reportCombined.push(r);

                    if (typeof r.A == "string") eventDetailsA.push(r);
                    if (typeof r.A == "string" && r.A.indexOf("Date") > -1) {
                        eventsCombined.push(valueSplit(r.A));
                    }
                });

                arrayData[1].map((sr) => {
                    if (Object.keys(sr).length >= 20) summaryReport.push(sr);
                });

                // Merge Object
                const mergeObj = mergeObject(eventsCombined);

                // date format MM/DD/YYYY || DD/MM/YYYY
                const dateFormatting = (date) =>
                    moment(date, "MM/DD/YYYY").isValid()
                        ? moment(date, "MM/DD/YYYY").format("YYYY-MM-DD LTS")
                        : moment(date, "DD/MM/YYYY").isValid()
                        ? moment(date, "DD/MM/YYYY").format("YYYY-MM-DD LTS")
                        : moment(date).format("YYYY-MM-DD LTS");
                const eventCreatedUTC = ExcelDateToJSDate(arrayData[1][2].B);
                const eventClosedUTC = ExcelDateToJSDate(arrayData[1][4].B);

                // Validate if Event date is in a correct date format
                const isValidEventArenaDate = (stringDate) =>
                    moment(stringDate, "MM/DD/YYYY hh:mm:ss a").isValid() ||
                    moment(stringDate, "DD/MM/YYYY hh:mm:ss a").isValid()
                        ? stringDate
                        : null;

                const eventDateCreated = dateFormatting(
                    mergeObj.dateCreated ||
                        isValidEventArenaDate(eventDetailsA[2]?.A) ||
                        eventCreatedUTC
                );

                console.log("eventDateCreated", eventDateCreated);
                const eventDateClosed = dateFormatting(
                    mergeObj.dateClosed ||
                        isValidEventArenaDate(eventDetailsA[4]?.A) ||
                        eventClosedUTC
                );

                console.log(isValidEventArenaDate(arrayData[1][3].A));

                const objectKeyed = (array) => {
                    let objectKeyReplacedArray = [];
                    const keysss = array.find((k) => k.B === "ARENA NAME");

                    const [, ...headKey] = Object.values(keysss);
                    const headK = ["key", ...headKey];

                    array.map((data) => {
                        data = Object.assign(
                            {},
                            ...Object.entries(data).map(([, prop], index) => ({
                                [camelCase(headK[index])]: prop,
                            }))
                        );

                        objectKeyReplacedArray.push({
                            eventCreated: eventDateCreated,
                            eventClosed: eventDateClosed,
                            type: data.type
                                ? data.type
                                : data.classification
                                ? data.classification
                                : null,

                            drawMobile: 0,
                            totalMWMobile: 0,
                            safetyFundMob: 0,
                            otherCommIntMob: 0,
                            consolCommMob: 0,
                            payOutsBalMob: 0,
                            otherDeductiblesFromCommission: 0,
                            otherCommissionGofw0005: 0,
                            ...data,
                        });
                    });

                    return objectKeyReplacedArray;
                };

                const objKeySummary = objectKeyed(summaryReport, 7);

                objKeySummary.forEach(function (item) {
                    const existing = objMobileKiosk.filter((v, i) => {
                        if (v.type === "KIOSK" && v.areaCode == item.areaCode) {
                            return v.areaCode == item.areaCode;
                        }
                    });

                    if (existing.length) {
                        const m = item.type.toLowerCase();
                        const existingIndex = objMobileKiosk.indexOf(
                            existing[0]
                        );

                        objMobileKiosk[existingIndex].totalMWMobile =
                            item.total;
                        objMobileKiosk[existingIndex].drawMobile = item.draw;

                        objMobileKiosk[existingIndex].safetyFundMob =
                            item.safetyFund;
                        objMobileKiosk[existingIndex].otherCommIntMob =
                            item.otherCommissionIntel01;
                        objMobileKiosk[existingIndex].consolCommMob =
                            item.consolidatorsCommission;
                        objMobileKiosk[existingIndex].payOutsBalMob =
                            item.paymentForOutstandingBalance;
                    } else {
                        if (typeof item.value == "string") {
                            item.value = [item.value];
                        }

                        objMobileKiosk.push(item);
                    }
                });

                let helper = {};
                const result = objMobileKiosk.reduce(function (r, o) {
                    let key = o.areaCode;

                    if (!helper[key]) {
                        helper[key] = Object.assign({}, o); // create a copy of o

                        r.push(helper[key]);
                    } else {
                        helper[key].totalMWMobile = o.total;
                        helper[key].drawMobile = o.draw;
                        helper[key].safetyFundMob = o.safetyFund;
                        helper[key].otherCommIntMob = o.otherCommissionIntel01;
                        helper[key].consolCommMob = o.consolidatorsCommission;
                        helper[key].payOutsBalMob = o.paymentForOutstandingBalance;
                    }

                    return r;
                }, []);

                const accountsReportSummaryCombined = [...result];

                const arsc = values(
                    map(
                        groupBy(accountsReportSummaryCombined, "arenaName"),
                        spread(assign)
                    )
                );
                // Filter not needed object with specific arenaName
                const filterObjectHeader = arsc.filter((obk) => {
                    if (
                        obk.arenaName !== "OCBS NAME" &&
                        obk.arenaName !== "ARENA NAME" &&
                        obk.arenaName !== "Over All Total:" &&
                        obk.arenaName !== "Grand Total:" &&
                        obk.arenaName !== 0
                    )
                        return obk;
                });

                const removeKeyReportObject = filterObjectHeader.map(
                    ({ key, ...rest }) => {

                        const type = rest.type || rest.classification;
                        const exempted = rest.exempted;
                        const totalMWBets = rest.meron + rest.wala;
                        const totalCancelledBets = rest.drawCancelled;
                        const totalDrawBets = rest.draw;
                        const totalPayoutPaid = rest.payoutPaid;
                        const totalCDPaid = rest.cDPaid;
                        const totalDrawPaid = rest.drawPaid;
                        const totalMWMobile = rest.totalMWMobile;
                        const totalDrawMobile = rest.drawMobile;
                        const safetyFundMob = rest.safetyFundMob;
                        const otherCommIntMob = rest.otherCommIntMob;
                        const consolCommMob = rest.consolCommMob;
                        const payOutsBalMob = rest.payOutsBalMob;
                        const netWinLoss = rest.netWinLoss;
                        const mwTwo = totalMWBets * 0.02;
                        const drawTwo = totalDrawBets * 0.02;
                        const mwTwoMobile = totalMWMobile * 0.02;
                        const drawTwoMobile = totalDrawMobile * 0.02;
                        const totalUnclaimed = rest.unclaimed;
                        const totalCUnpaid = rest.cUnpaid;
                        const salesDeduction = rest.salesDeductionTablet;
                        const netOperatorsCommission =
                            rest.netOperatorsCommission;
                        const otherCommissionIntel =
                            rest.otherCommissionIntel01;
                        const otherCommissionGofw0005 =
                            rest.otherCommissionGofw0005;
                        const consolidatorsCommission =
                            rest.consolidatorsCommission;
                        const safetyFund = rest.safetyFund;
                        const paymentForOutstandingBalance =
                            rest.paymentForOutstandingBalance;
                        const otherDeductiblesFromCommission =
                            rest.otherDeductiblesFromCommission;
                        const totalCommission = rest.totalCommission;
                        const cashLoad = rest.cashLoad;
                        const cashWithdrawal =  rest.cashWithdrawal;
                        const totalOthers = rest.totalOthers;
                        const systemErrorCOArmsi = rest.systemErrorCOArmsi;
                        const depositReplenish = rest.forDepositReplenish;
                        const soaFr =
                            parseFloat(depositReplenish) < 0 ? "fr" : "soa";
                        const group = soaFr === "fr" ? "Replenish" : "Deposit";
                        const arenaName =
                            rest.arenaName.indexOf("/") > -1
                                ? rest.arenaName.replace(/\//g, "~")
                                : rest.arenaName;
                        const areaCode =
                            rest.areaCode.indexOf("/") > -1
                                ? rest.areaCode.replace(/\//g, "~")
                                : rest.areaCode;

                        const codeEvent = `${areaCode.toLowerCase()}${moment(
                            rest.eventCreated
                        ).format("X")}`;

                        const hashedCode = CryptoJS.AES.encrypt(
                            codeEvent,
                            "secretKey"
                        ).toString();

                        rest = {
                            areaCode,
                            codeEvent,
                            date_of_soa: rest.eventCreated,
                            date_closed: rest.eventClosed,
                            meron: rest.meron,
                            wala: rest.wala,
                            rake: rest.rake,
                            draw_unpaid: rest.dUnpaid,
                            draw_unclaimed: rest.drawUnclaimed,
                            arena_name: arenaName.toUpperCase(),
                            type,
                            exempted,
                            total_meron_wala: totalMWBets,
                            draw_cancelled: totalCancelledBets,
                            draw: totalDrawBets,
                            total_payout_paid: totalPayoutPaid,
                            draw_cancelled_paid: totalCDPaid,
                            draw_paid: totalDrawPaid,
                            netWinLoss,
                            mwTwo,
                            drawTwo,
                            mwTwoMobile,
                            drawTwoMobile,
                            unclaimed: totalUnclaimed,
                            cancelled_unpaid: totalCUnpaid,
                            salesDeductionTablet: salesDeduction,
                            netOperatorsCommission,
                            otherCommissionIntel01: otherCommissionIntel,
                            otherCommissionGofw0005: otherCommissionGofw0005,
                            consolidatorsCommission,
                            safetyFund,
                            paymentForOutstandingBalance,
                            otherDeductiblesFromCommission,
                            totalCommission,
                            total_win_mobile: totalMWMobile,
                            draw_mobile: totalDrawMobile,
                            cashLoad,
                            cashWithdrawal,
                            for_total: depositReplenish,
                            totalOthers,
                            systemErrorCOArmsi,
                            safetyFundMob,
                            otherCommIntMob,
                            consolCommMob,
                            payOutsBalMob,
                            soaFr,
                            group,
                        };

                        return toString({ ...rest });
                    }
                );

                const removeLucky = removeKeyReportObject.filter(
                    (removeLuck) =>
                        removeLuck.areaCode !== "LUCKY" ||
                        removeLuck.arena_name.split(" ")[0] !== "LUCKY"
                );
                // group fr and soa
                const groupSOAFR = removeLucky.reduce(function (r, a) {
                    r[a.soaFr] = r[a.soaFr] || [];
                    r[a.soaFr].push(a);
                    return r;
                }, Object.create(null));

                console.log(groupSOAFR);

                // Convert Month number to alphabet
                const moLetter = String.fromCharCode(
                    96 + (moment(eventDateCreated).month() + 1)
                ).toUpperCase();

                const sortSoa = sortBy(groupSOAFR.soa, [
                    function (o) {
                        return o.areaCode;
                    },
                ]);

                const sortFr = sortBy(groupSOAFR.fr, [
                    function (o) {
                        return o.areaCode;
                    },
                ]);

                const site =
                    arrayData[1][5].A.split(":")[1].trim() === ""
                        ? arrayData[1][5].B
                        : arrayData[1][5].A.split(":")[1];

                const count = (i) => `0000${i + 1}`.slice(-4);
                const newsoa = sortSoa.map(({ soaFr, ...s }, i) => ({
                    refNo: `S${site}${moment(eventDateCreated).format(
                        "YYDD"
                    )}${moLetter}${count(i)}`,
                    ...s,
                }));
                const newfr = sortFr.map(({ soaFr, ...f }, i) => ({
                    refNo: `R${site}${moment(eventDateCreated).format(
                        "YYDD"
                    )}${moLetter}${count(i)}`,
                    ...f,
                }));

                const newSetReport = concat(newsoa, newfr);

                const sortReport = sortBy(newSetReport, [
                    function (o) {
                        return o.areaCode;
                    },
                ]);
                arenaReportFiltered.push(...sortReport);
            } else {
                withSite = false
                Fire.$emit("AfterCreate"),
                    Toast.fire({
                        icon: "warning",
                        title: "Site is Missing, Please double check your excel!",
                    });
            }
        };
        reader.readAsBinaryString(file);
    } else {
        isExcel = false;
        Fire.$emit("AfterCreate"),
            Toast.fire({
                icon: "warning",
                title: "Make sure you insert correct excel data!",
            });
    }
    return {
        arenaReportFiltered,
        isExcel,
        withSite
    };
};

export { readSoa };
