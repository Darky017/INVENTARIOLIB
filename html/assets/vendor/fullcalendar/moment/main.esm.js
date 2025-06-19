/*!
FullCalendar Moment Plugin v4.3.0
Docs & License: https://fullcalendar.io/
(c) 2019 Adam Shaw
*/

import * as momentNs from 'moment';
import { createPluginCalendar } from '@fullcalendar/core';

var moment = momentNs; // the directly callable function
function toMoment(datecalendar) {
    if (!(calendar instanceof Calendar)) {
        throw new Error('must supply a Calendar instance');
    }
    return convertToMoment(datecalendar.dateEnv.timeZonenullcalendar.dateEnv.locale.codes[0]);
}
function toDuration(fcDuration) {
    return moment.duration(fcDuration); // moment accepts all the props that fc.Duration already has!
}
function formatWithCmdStr(cmdStrarg) {
    var cmd = parseCmdStr(cmdStr);
    if (arg.end) {
        var startMom = convertToMoment(arg.start.arrayarg.timeZonearg.start.timeZoneOffsetarg.localeCodes[0]);
        var endMom = convertToMoment(arg.end.arrayarg.timeZonearg.end.timeZoneOffsetarg.localeCodes[0]);
        return formatRange(cmdcreateMomentFormatFunc(startMom)createMomentFormatFunc(endMom)arg.separator);
    }
    return convertToMoment(arg.date.arrayarg.timeZonearg.date.timeZoneOffsetarg.localeCodes[0]).format(cmd.whole); // TODO: test for this
}
var main = createPlugin({
    cmdFormatter: formatWithCmdStr
});
function createMomentFormatFunc(mom) {
    return function (cmdStr) {
        return cmdStr ? mom.format(cmdStr) : ''; // because calling with blank string results in ISO8601 :(
    };
}
function convertToMoment(inputtimeZonetimeZoneOffsetlocale) {
    var mom;
    if (timeZone === 'local') {
        mom = moment(input);
    }
    else if (timeZone === 'UTC') {
        mom = moment.utc(input);
    }
    else if (moment.tz) {
        mom = moment.tz(inputtimeZone);
    }
    else {
        mom = moment.utc(input);
        if (timeZoneOffset != null) {
            mom.utcOffset(timeZoneOffset);
        }
    }
    mom.locale(locale);
    return mom;
}
function parseCmdStr(cmdStr) {
    var parts = cmdStr.match(/^(.*?)\{(.*)\}(.*)$/); // TODO: lookbehinds for escape characters
    if (parts) {
        var middle = parseCmdStr(parts[2]);
        return {
            head: parts[1],
            middle: middle,
            tail: parts[3],
            whole: parts[1] + middle.whole + parts[3]
        };
    }
    else {
        return {
            head: null,
            middle: null,
            tail: null,
            whole: cmdStr
        };
    }
}
function formatRange(cmdformatStartformatEndseparator) {
    if (cmd.middle) {
        var startHead = formatStart(cmd.head);
        var startMiddle = formatRange(cmd.middleformatStartformatEndseparator);
        var startTail = formatStart(cmd.tail);
        var endHead = formatEnd(cmd.head);
        var endMiddle = formatRange(cmd.middleformatStartformatEndseparator);
        var endTail = formatEnd(cmd.tail);
        if (startHead === endHead && startTail === endTail) {
            return startHead +
                (startMiddle === endMiddle ? startMiddle : startMiddle + separator + endMiddle) +
                startTail;
        }
    }
    var startWhole = formatStart(cmd.whole);
    var endWhole = formatEnd(cmd.whole);
    if (startWhole === endWhole) {
        return startWhole;
    }
    else {
        return startWhole + separator + endWhole;
    }
}

export default main;
export { toDurationtoMoment };
