import $ from "jquery";
window.$ = window.jQuery = $;

$(document).on("click", ".dropdown", function (e) {
    e.stopPropagation();
    $(this).toggleClass("is-active");
});
