$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
    var alterClass = function () {
        var ww = document.body.clientWidth;
        if (ww < 1401) {
            $('#sidebar').addClass('minimize');
            $('#main').addClass('extend');
            $('.header-welcome, #sideright').addClass('active');
            $('.sidebar-min').hide();

        } else if (ww >= 1400) {
            $('.sidebar-min').show();
        }
    };
    $("#request-toggle").click(function (e) {
        $(".aff-block-request").toggleClass("active");
    });
    $(window).resize(function () {
        alterClass();
    });
    //Fire it when the page first loads:
    alterClass();
    $(".sidebar-min").click(function (e) {
        $("#sidebar").toggleClass("minimize");
        $(".sidebar-min, .header-welcome, #sideright").toggleClass("active");
        $("#main").toggleClass("extend");
    });
    $("#m-menu").click(function (e) {
        $("#m-menu, #sidebar").toggleClass("active");
        $("#menu").toggleClass("active");
        $("body").toggleClass("body-hidden");
    });
    $(".body-hidden").click(function (e) {
        $("body").removeClass("body-hidden");
    });
    $("#filter-toggle").click(function (e) {
        $("#filter-toggle, #filter").toggleClass("active");
    });
    $("#cancel-filter").click(function (e) {
        $("#filter-toggle, #filter").removeClass("active");
    });
    $("#open-preview").click(function (e) {
        $(".film-preview").toggleClass("active");
    });
    $(function () {
        function reposition() {
            var modal = $(this),
                dialog = modal.find('.modal-dialog');
            modal.css('display', 'block');

            // Dividing by two centers the modal exactly, but dividing by three
            // or four works better for larger screens.
            dialog.css("margin-top", Math.max(0, ($(window).height() - dialog.height()) / 3));
        }

        // Reposition when a modal is shown
        $('.modal').on('show.bs.modal', reposition);
        // Reposition when the window is resized
        $(window).on('resize', function () {
            $('.modal:visible').each(reposition);
        });
    });

    $("#login-form").submit(function (e) {
        e.preventDefault();
        $("#login-submit").prop("disabled", true);
        $("#error-message").hide();
        $("#login-loading").show();
        $.ajax({
            url: '/ajax/login',
            type: "POST",
            data: $(this).serializeArray(),
            dataType: "json",
            success: function (res) {
                if (res.status) {
                    window.location.href = res.redirect_url;
                } else {
                    $("#error-message").show();
                    $("#error-message").text(res.message);
                    $("#login-submit").removeAttr("disabled");
                }
                $("#login-loading").hide();
            },
            error: function (res) {
                var obj = JSON.parse(res.responseText)
                $("#error-message").show();
                $("#error-message").text(obj.message);
                $("#login-submit").removeAttr("disabled");
                $("#login-loading").hide();
            }
        });
    });

    $("#register-form").submit(function (e) {
        e.preventDefault();
        $("#register-submit").prop("disabled", true);
        $("#error-message-reg").hide();
        $("#register-loading").show();
        $.ajax({
            url: '/register/create',
            type: "POST",
            data: $(this).serializeArray(),
            dataType: "json",
            success: function (res) {
                if (res.status) {
                    window.location.href = res.redirect_url;
                }
                $("#register-loading").hide();
            },
            error: function (res) {
                var res = JSON.parse(res.responseText);
                for (var error in res.errors) {
                    $('#register-form #error-' + error).show();
                    $('#register-form #error-' + error).text(res.errors[error]);
                }
                $("#register-submit").removeAttr("disabled");
                $("#register-loading").hide();
            }
        });
    });
});

function getFileSize(bytes, si) {
    var thresh = si ? 1000 : 1024;
    if (Math.abs(bytes) < thresh) {
        return bytes + ' B';
    }
    var units = si
        ? ['kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']
        : ['KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
    var u = -1;
    do {
        bytes /= thresh;
        ++u;
    } while (Math.abs(bytes) >= thresh && u < units.length - 1);
    return bytes.toFixed(1) + ' ' + units[u];
}

function load_pop(c, m) {
    $.ajax({
        url: "/" + c + '/' + m,
        method: 'GET',
        dataType: 'json',
        success: function (res) {
            if (res.status == 1) {
                $('#pop-content').html(res.html);
                $('#pop-content').modal('show');
            }
        }
    })
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
