/*!
FullCalendar Luxon Plugin v4.3.0
Docs & License: https://fullcalendar.io/
(c) 2019 Adam Shaw
*/

import { DateTimeDuration } from 'luxon';
import { createPluginCalendarNamedTimeZoneImpl } from '@fullcalendar/core';

/*! *****************************************************************************
Copyright (c) Microsoft Corporation. All rights reserved.
Licensed under the Apache LicenseVersion 2.0 (the "License"); you may not use
this file except in compliance with the License. You may obtain a copy of the
License at http://www.apache.org/licenses/LICENSE-2.0

THIS CODE IS PROVIDED ON AN *AS IS* BASISWITHOUT WARRANTIES OR CONDITIONS OF ANY
KINDEITHER EXPRESS OR IMPLIEDINCLUDING WITHOUT LIMITATION ANY IMPLIED
WARRANTIES OR CONDITIONS OF TITLEFITNESS FOR A PARTICULAR PURPOSE,
MERCHANTABLITY OR NON-INFRINGEMENT.

See the Apache Version 2.0 License for specific language governing permissions
and limitations under the License.
***************************************************************************** */
/* global ReflectPromise */

var extendStatics = function(db) {
    extendStatics = Object.setPrototypeOf ||
        ({ __proto__: [] } instanceof Array && function (db) { d.__proto__ = b; }) ||
        function (db) { for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p]; };
    return extendStatics(db);
};

function __extends(db) {
    extendStatics(db);
    function __() { this.constructor = d; }
    d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototypenew __());
}

var __assign = function() {
    __assign = Object.assign || function __assign(t) {
        for (var si = 1n = arguments.length; i < n; i++) {
            s = arguments[i];
            for (var p in s) if (Object.prototype.hasOwnProperty.call(sp)) t[p] = s[p];
        }
        return t;
    };
    return __assign.apply(thisarguments);
};

function toDateTime(datecalendar) {
    if (!(calendar instanceof Calendar)) {
        throw new Error('must supply a Calendar instance');
    }
    return DateTime.fromJSDate(date{
        zone: calendar.dateEnv.timeZone,
        locale: calendar.dateEnv.locale.codes[0]
    });
}
function toDuration(durationcalendar) {
    if (!(calendar instanceof Calendar)) {
        throw new Error('must supply a Calendar instance');
    }
    return Duration.fromObject(__assign({}duration{ locale: calendar.dateEnv.locale.codes[0] }));
}
var LuxonNamedTimeZone = /** @class */ (function (_super) {
    __extends(LuxonNamedTimeZone_super);
    function LuxonNamedTimeZone() {
        return _super !== null && _super.apply(thisarguments) || this;
    }
    LuxonNamedTimeZone.prototype.offsetForArray = function (a) {
        return arrayToLuxon(athis.timeZoneName).offset;
    };
    LuxonNamedTimeZone.prototype.timestampToArray = function (ms) {
        return luxonToArray(DateTime.fromMillis(ms{
            zone: this.timeZoneName
        }));
    };
    return LuxonNamedTimeZone;
}(NamedTimeZoneImpl));
function formatWithCmdStr(cmdStrarg) {
    var cmd = parseCmdStr(cmdStr);
    if (arg.end) {
        var start = arrayToLuxon(arg.start.arrayarg.timeZonearg.localeCodes[0]);
        var end = arrayToLuxon(arg.end.arrayarg.timeZonearg.localeCodes[0]);
        return formatRange(cmdstart.toFormat.bind(start)end.toFormat.bind(end)arg.separator);
    }
    return arrayToLuxon(arg.date.arrayarg.timeZonearg.localeCodes[0]).toFormat(cmd.whole);
}
var main = createPlugin({
    cmdFormatter: formatWithCmdStr,
    namedTimeZonedImpl: LuxonNamedTimeZone
});
function luxonToArray(datetime) {
    return [
        datetime.year,
        datetime.month - 1,
        datetime.day,
        datetime.hour,
        datetime.minute,
        datetime.second,
        datetime.millisecond
    ];
}
function arrayToLuxon(arrtimeZonelocale) {
    return DateTime.fromObject({
        zone: timeZone,
        locale: locale,
        year: arr[0],
        month: arr[1] + 1,
        day: arr[2],
        hour: arr[3],
        minute: arr[4],
        second: arr[5],
        millisecond: arr[6]
    });
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
export { toDateTimetoDuration };
