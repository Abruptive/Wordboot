/*! 
 * wordboot 
 * 
 * Version: 1.0.0 
 * Author: Abruptive 
 * Author URL: https://abruptive.com 
 * Build Date: 2018-08-03 
 */

"use strict";

! function(p) {
    "use strict";
    p(".repeater").length && (p(document).on("click", "[data-repeater]", function(e) {
        var a = p(this).closest(".repeater").find("tbody"),
            t = p(this).closest(".repeater-item"),
            r = a.find(".repeater-template").html().replace("data-name", "name");
        switch (p(this).data("repeater")) {
            case "add":
                a.append('<tr class="repeater-item">' + r + "</tr>");
                break;
            case "remove":
                t.remove();
        }
        e.preventDefault();
    }), p(".repeater").each(function() {
        var e = p(this).find("tbody"),
            a = e.find(".repeater-template").html().replace("data-name", "name"),
            t = JSON.parse(p(this).find(".repeater-data").val().replace('"",', ""));
        for (var r in t) {
            e.append('<tr class="repeater-item">' + a.replace('value=""', 'value="' + t[r] + '"') + "</tr>");
        }
    }));
}(jQuery);
//# sourceMappingURL=admin.min.js.map
//# sourceMappingURL=admin.js.map
