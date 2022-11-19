import $ from "jquery";
window.$ = window.jQuery = $;
import Toastify from "toastify-js";
window.Toastify = Toastify;

$(document).on("click", ".dropdown", function (e) {
    e.stopPropagation();
    $(this).toggleClass("is-active");
});
