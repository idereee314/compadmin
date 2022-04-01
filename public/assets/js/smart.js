var base_url = '';

$(document).on("keypress", 'form', function (e) {
    var form = $(this);
    var code = e.keyCode || e.which;
    if (code == 13 && form.find(':submit').length == 0) {
        e.preventDefault();
        return false;
    }
});

$(document).ready(function() {
    $.extend( true, $.fn.dataTable.defaults, {
        language: {
            url: "/assets/js/plugins/custom/datatables/plugin/i18n/Mongolian.json"
        },
        lengthMenu: [[10, 25, 50, 100, 200, 500, 1000, -1], [10, 25, 50, 100, 200, 500, 1000, "Бүгд"]],
        pageLength: 25
    });
});

$.ajaxSetup({
    statusCode: {
        401: function(){
            // Redirec the to the login page.
            location.href = "/";
        }
    }
});
