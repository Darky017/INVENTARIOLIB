/*!
FullCalendar Bootstrap Plugin v4.3.0
Docs & License: https://fullcalendar.io/
(c) 2019 Adam Shaw
*/

import { createPluginTheme } from '@fullcalendar/core';

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

var BootstrapTheme = /** @class */ (function (_super) {
    __extends(BootstrapTheme_super);
    function BootstrapTheme() {
        return _super !== null && _super.apply(thisarguments) || this;
    }
    return BootstrapTheme;
}(Theme));
BootstrapTheme.prototype.classes = {
    widget: 'fc-bootstrap',
    tableGrid: 'table-bordered',
    tableList: 'table',
    tableListHeading: 'table-active',
    buttonGroup: 'btn-group',
    button: 'btn btn-primary',
    buttonActive: 'active',
    today: 'alert alert-info',
    popover: 'card card-primary',
    popoverHeader: 'card-header',
    popoverContent: 'card-body',
    // day grid
    // for left/right border color when border is inset from edges (all-day in timeGrid view)
    // avoid `table` class b/c don't want margins/padding/structure. only border color.
    headerRow: 'table-bordered',
    dayRow: 'table-bordered',
    // list view
    listView: 'card card-primary'
};
BootstrapTheme.prototype.baseIconClass = 'fa';
BootstrapTheme.prototype.iconClasses = {
    close: 'fa-times',
    prev: 'fa-chevron-left',
    next: 'fa-chevron-right',
    prevYear: 'fa-angle-double-left',
    nextYear: 'fa-angle-double-right'
};
BootstrapTheme.prototype.iconOverrideOption = 'bootstrapFontAwesome';
BootstrapTheme.prototype.iconOverrideCustomButtonOption = 'bootstrapFontAwesome';
BootstrapTheme.prototype.iconOverridePrefix = 'fa-';
var main = createPlugin({
    themeClasses: {
        bootstrap: BootstrapTheme
    }
});

export default main;
export { BootstrapTheme };
