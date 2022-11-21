import $ from "jquery";
window.$ = window.jQuery = $;

import Toastify from "toastify-js";
window.Toastify = Toastify;

import tippy from 'tippy.js';
window.tippy = tippy;

$(document).on("click", ".dropdown", function (e) {
    e.stopPropagation();
    $(this).toggleClass("is-active");
});

window.formatValues = () => {
    // security status color
    $(".security").each(function () {
        let security = parseFloat($(this).text());
        $(this).css("color", "#00BFFF");
        if (security <= 0.8) {
            $(this).css("color", "#57EDAA");
        }
        if (security < 0.6) {
            $(this).css("color", "#FFD700");
        }
        if (security < 0.5) {
            $(this).css("color", "#FF8C00");
        }
        if (security < 0) {
            $(this).css("color", "#FF0000");
        }
    });

    // statics format
    $(".class-type").each(function () {
        let inClass = parseInt($(this).data("in-class"));

        $(this).text(function () {
            switch (inClass) {
                case 7:
                    $(this).addClass("has-text-success");
                    return "High";
                case 8:
                    $(this).addClass("has-text-warning");
                    return "Low";
                case 9:
                    $(this).addClass("has-text-danger");
                    return "Null";
                case 12:
                    $(this).addClass("has-text-info");
                    return "Thera";
            }

            $(this).addClass("has-text-link");
            return "C" + inClass;
        });
    });
};

window.toast = (message) => {
    return Toastify({
        text: message,
        duration: 3000,
    }).showToast();
};
