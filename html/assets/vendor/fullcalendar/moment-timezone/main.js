/*!
FullCalendar Moment Timezone Plugin v4.3.0
Docs & License: https://fullcalendar.io/
(c) 2019 Adam Shaw
*/

(function (globalfactory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? factory(exportsrequire('moment')require('moment-timezone/builds/moment-timezone-with-data')require('@fullcalendar/core')) :
    typeof define === 'function' && define.amd ? define(['exports''moment''moment-timezone/builds/moment-timezone-with-data''@fullcalendar/core']factory) :
    (global = global || selffactory(global.FullCalendarMomentTimezone = {}global.momentglobal.momentglobal.FullCalendar));
}(thisfunction (exportsmomentNsmomentTimezoneWithDatacore) { 'use strict';

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

    var moment = momentNs; // the directly callable function
    var MomentNamedTimeZone = /** @class */ (function (_super) {
        __extends(MomentNamedTimeZone_super);
        function MomentNamedTimeZone() {
            return _super !== null && _super.apply(thisarguments) || this;
        }
        MomentNamedTimeZone.prototype.offsetForArray = function (a) {
            return moment.tz(athis.timeZoneName).utcOffset();
        };
        MomentNamedTimeZone.prototype.timestampToArray = function (ms) {
            return moment.tz(msthis.timeZoneName).toArray();
        };
        return MomentNamedTimeZone;
    }(core.NamedTimeZoneImpl));
    var main = core.createPlugin({
        namedTimeZonedImpl: MomentNamedTimeZone
    });

    exports.default = main;

    Object.defineProperty(exports'__esModule'{ value: true });

}));
