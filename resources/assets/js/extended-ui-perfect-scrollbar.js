/**
 * Perfect Scrollbar
 */
"use strict";

document.addEventListener("DOMContentLoaded", function () {
    (function () {
        const verticalExample =
                document.getElementsByClassName("perfect-scrollbar"),
            horizontalExample = document.getElementById("horizontal-example"),
            horizVertExample = document.getElementById(
                "both-scrollbars-example"
            );
        console.log(
            document.getElementsByClassName("perfect-scrollbar").length
        );
        // Vertical Example
        // --------------------------------------------------------------------
        if (verticalExample.length) {
            [].forEach.call(verticalExample, (element) => {
                new PerfectScrollbar(element, {
                    wheelPropagation: false,
                });
            });
        }

        // Horizontal Example
        // --------------------------------------------------------------------
        if (horizontalExample) {
            new PerfectScrollbar(horizontalExample, {
                wheelPropagation: false,
                suppressScrollY: true,
            });
        }

        // Both vertical and Horizontal Example
        // --------------------------------------------------------------------
        if (horizVertExample) {
            new PerfectScrollbar(horizVertExample, {
                wheelPropagation: false,
            });
        }
    })();
});
