import $ from "jquery";
import Toastify from "toastify-js";
import tippy from "tippy.js";
import cookieconsent from "vanilla-cookieconsent";

window.$ = window.jQuery = $;
window.Toastify = Toastify;
window.tippy = tippy;
window.cookieconsent = initCookieConsent();

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

window.getCsrfToken = () => {
    return $('meta[name="csrf-token"]').attr("content");
};

window.getFromTemplate = (templateClass, replaces = {}) => {
    const template = $("#templates " + templateClass).html();
    if (template) {
        let content = template;
        for (const property in replaces) {
            content = content
                .split("%%" + property + "%%")
                .join(replaces[property]);
        }
        return content;
    }
    throw "template not found";
};

(function ($) {
    $.fn.solarSystemSelector = function (url) {
        const self = this;

        const listObject = self.siblings(".suggestions");

        if (listObject.length) {
            self.on("keyup", () => {
                console.log(url);
                $.post({
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN": getCsrfToken(),
                    },
                    data: {
                        search: self.val(),
                    },
                }).done(async (response) => {
                    const getList = (response, listObject) => {
                        listObject.html("");
                        response.systems.forEach((element) => {
                            listObject.append("<div>" + element + "</div>");
                        });
                        listObject.show();
                        return listObject;
                    };

                    const list = await getList(response, listObject);
                    list.children("div").on("click", (e) => {
                        self.val(e.currentTarget.innerText);
                        listObject.hide();
                    });
                });
            });
        }

        return this;
    };
})(jQuery);

$(function () {
    $(".navbar-burger").on("click", function () {
        $(".navbar-burger").toggleClass("is-active");
        $(".navbar-menu").toggleClass("is-active");
    });
});
