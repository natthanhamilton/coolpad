jQuery(document).ready(function (t) {
    "use strict";
    t(".titan-framework-panel-wrap table.form-table").filter(function () {
        return 0 === t(this).find("tbody tr").length
    }).remove()
});