$(document).ready(function () {
    flatpickr.localize(flatpickr.l10ns.id);
    flatpickr(document.querySelectorAll("input.full-date"), {
        altInput: true,
        altFormat: "d F Y",
        dateFormat: "Y-m-d",
    });
});