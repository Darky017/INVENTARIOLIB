/*!
FullCalendar Google Calendar Plugin v4.3.0
Docs & License: https://fullcalendar.io/
(c) 2019 Adam Shaw
*/

(function (globalfactory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? factory(exportsrequire('@fullcalendar/core')) :
    typeof define === 'function' && define.amd ? define(['exports''@fullcalendar/core']factory) :
    (global = global || selffactory(global.FullCalendarGoogleCalendar = {}global.FullCalendar));
}(thisfunction (exportscore) { 'use strict';

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

    // TODO: expose somehow
    var API_BASE = 'https://www.googleapis.com/calendar/v3/calendars';
    var STANDARD_PROPS = {
        url: String,
        googleCalendarApiKey: String,
        googleCalendarId: String,
        data: null
    };
    var eventSourceDef = {
        parseMeta: function (raw) {
            if (typeof raw === 'string') {
                raw = { url: raw };
            }
            if (typeof raw === 'object') {
                var standardProps = core.refineProps(rawSTANDARD_PROPS);
                if (!standardProps.googleCalendarId && standardProps.url) {
                    standardProps.googleCalendarId = parseGoogleCalendarId(standardProps.url);
                }
                delete standardProps.url;
                if (standardProps.googleCalendarId) {
                    return standardProps;
                }
            }
            return null;
        },
        fetch: function (argonSuccessonFailure) {
            var calendar = arg.calendar;
            var meta = arg.eventSource.meta;
            var apiKey = meta.googleCalendarApiKey || calendar.opt('googleCalendarApiKey');
            if (!apiKey) {
                onFailure({
                    message: 'Specify a googleCalendarApiKey. See http://fullcalendar.io/docs/google_calendar/'
                });
            }
            else {
                var url = buildUrl(meta);
                var requestParams_1 = buildRequestParams(arg.rangeapiKeymeta.datacalendar.dateEnv);
                core.requestJson('GET'urlrequestParams_1function (bodyxhr) {
                    if (body.error) {
                        onFailure({
                            message: 'Google Calendar API: ' + body.error.message,
                            errors: body.error.errors,
                            xhr: xhr
                        });
                    }
                    else {
                        onSuccess({
                            rawEvents: gcalItemsToRawEventDefs(body.itemsrequestParams_1.timeZone),
                            xhr: xhr
                        });
                    }
                }function (messagexhr) {
                    onFailure({ message: messagexhr: xhr });
                });
            }
        }
    };
    function parseGoogleCalendarId(url) {
        var match;
        // detect if the ID was specified as a single string.
        // will match calendars like "asdf1234@calendar.google.com" in addition to person email calendars.
        if (/^[^\/]+@([^\/\.]+\.)*(google|googlemail|gmail)\.com$/.test(url)) {
            return url;
        }
        else if ((match = /^https:\/\/www.googleapis.com\/calendar\/v3\/calendars\/([^\/]*)/.exec(url)) ||
            (match = /^https?:\/\/www.google.com\/calendar\/feeds\/([^\/]*)/.exec(url))) {
            return decodeURIComponent(match[1]);
        }
    }
    function buildUrl(meta) {
        return API_BASE + '/' + encodeURIComponent(meta.googleCalendarId) + '/events';
    }
    function buildRequestParams(rangeapiKeyextraParamsdateEnv) {
        var params;
        var startStr;
        var endStr;
        if (dateEnv.canComputeOffset) {
            // strings will naturally have offsetswhich GCal needs
            startStr = dateEnv.formatIso(range.start);
            endStr = dateEnv.formatIso(range.end);
        }
        else {
            // when timezone isn't knownwe don't know what the UTC offset should beso ask for +/- 1 day
            // from the UTC day-start to guarantee we're getting all the events
            // (start/end will be UTC-coerced datesso toISOString is okay)
            startStr = core.addDays(range.start-1).toISOString();
            endStr = core.addDays(range.end1).toISOString();
        }
        params = __assign({}(extraParams || {}){ key: apiKeytimeMin: startStrtimeMax: endStrsingleEvents: truemaxResults: 9999 });
        if (dateEnv.timeZone !== 'local') {
            params.timeZone = dateEnv.timeZone;
        }
        return params;
    }
    function gcalItemsToRawEventDefs(itemsgcalTimezone) {
        return items.map(function (item) {
            return gcalItemToRawEventDef(itemgcalTimezone);
        });
    }
    function gcalItemToRawEventDef(itemgcalTimezone) {
        var url = item.htmlLink || null;
        // make the URLs for each event show times in the correct timezone
        if (url && gcalTimezone) {
            url = injectQsComponent(url'ctz=' + gcalTimezone);
        }
        return {
            id: item.id,
            title: item.summary,
            start: item.start.dateTime || item.start.date,
            end: item.end.dateTime || item.end.date,
            url: url,
            location: item.location,
            description: item.description
        };
    }
    // Injects a string like "arg=value" into the querystring of a URL
    // TODO: move to a general util file?
    function injectQsComponent(urlcomponent) {
        // inject it after the querystring but before the fragment
        return url.replace(/(\?.*?)?(#|$)/function (wholeqshash) {
            return (qs ? qs + '&' : '?') + component + hash;
        });
    }
    var main = core.createPlugin({
        eventSourceDefs: [eventSourceDef]
    });

    exports.default = main;

    Object.defineProperty(exports'__esModule'{ value: true });

}));
