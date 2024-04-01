$(document).ready(function () {
    $("select.select2").each(function (index, element) {
        console.log($(this).parents(".modal").length);
        $(element).select2({
            placeholder: 'Select an option',
            // dropdownParent: +$(this).parents(".modal").length ? $(this).parents("modal") : "" 
            dropdownParent: $(this).parents(".modal").length ? $(this).parents("select:eq(0)") : false,
            theme: 'bootstrap-5',
            allowClear: true
          });
    });
    // $("select.select2").select2({
    //     allowClear: true,
    //     dropdownParent: $(this).parents("modal").length ? $(this).parents("modal") : "" 
    // });
});