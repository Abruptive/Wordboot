/*! 
 * wordboot 
 * 
 * Version: 1.0.0 
 * Author: Abruptive 
 * Author URL: https://abruptive.com 
 * Build Date: 2018-08-03 
 */

"use strict";

! function(o) {
    "use strict";
    o(document).on("click", ".ajax", function() {
        o.ajax({
            url: wordboot.ajax_url,
            type: "POST",
            data: {
                action: "callback",
                nonce: wordboot.nonce,
                example: "Example"
            },
            dataType: "json",
            error: function error(o, n, c) {
                console.error(c);
            },
            success: function success(o, n, c) {
                console.log(o);
            },
            complete: function complete(o, n) {
                console.log(n);
            }
        });
    });
}(jQuery);
//# sourceMappingURL=public.min.js.map
//# sourceMappingURL=public.js.map
