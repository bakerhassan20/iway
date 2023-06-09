jQuery(document).ready(function(a) {
    date_and_time_picker_call();
    // mini_color_call()
});

function date_and_time_picker_call() {
    $(".dateTimePicker").datetimepicker();
    $(".dateTimePickerCustom").datetimepicker();
    $(".dateTimePickerCustom1").datetimepicker();
    $(".datePickerCall").click(function() {
        $(".dateTimePickerCustom").datetimepicker("show")
    });
    $(".datePickerCall1").click(function() {
        $(".dateTimePickerCustom1").datetimepicker("show")
    });
    $(".timePickerOnly").datetimepicker({
        datepicker: false,
        format: "H:i",
        mask: "00:00"
    });
    $(".datePickerOnly").datetimepicker({
        timepicker: false,
        datepicker: true,
        mask: "23-09-2016",
        format: "d-m-Y"
    });
    $("#date_timepicker_start").datetimepicker({
        onShow: function(a) {
            this.setOptions({
                maxDate: $("#date_timepicker_end").val() ? $("#date_timepicker_end").val() : false
            })
        }
    });
    $("#date_timepicker_end").datetimepicker({
        onShow: function(a) {
            this.setOptions({
                minDate: $("#date_timepicker_start").val() ? $("#date_timepicker_start").val() : false
            })
        }
    });
    $("#inlineDatePicker").datetimepicker({
        format: "d.m.Y H:i",
        inline: true,
        lang: "en"
    });
    $("#inlineDatePickerLanguage").datetimepicker({
        format: "d.m.Y H:i",
        inline: true,
        lang: "es"
    })
}
//
// function mini_color_call() {
//     $(".miniColors").minicolors({
//         animationSpeed: 50,
//         animationEasing: "swing",
//         change: null,
//         changeDelay: 0,
//         control: "hue",
//         defaultValue: $defultColor,
//         hide: null,
//         hideSpeed: 100,
//         inline: false,
//         letterCase: "lowercase",
//         opacity: true,
//         position: "bottom left",
//         show: null,
//         showSpeed: 100,
//         theme: "bootstrap"
//     });
//     $(".miniColors2").minicolors({
//         animationSpeed: 50,
//         animationEasing: "swing",
//         change: null,
//         changeDelay: 0,
//         control: "hue",
//         defaultValue: $redActive,
//         hide: null,
//         hideSpeed: 100,
//         inline: false,
//         letterCase: "lowercase",
//         opacity: false,
//         position: "bottom right",
//         show: null,
//         showSpeed: 100,
//         theme: "bootstrap"
//     });
//     $(".miniColor3").minicolors({
//         animationSpeed: 50,
//         animationEasing: "swing",
//         change: null,
//         changeDelay: 0,
//         control: "hue",
//         defaultValue: $brownActive,
//         hide: null,
//         hideSpeed: 100,
//         inline: false,
//         letterCase: "lowercase",
//         opacity: true,
//         position: "top left",
//         show: null,
//         showSpeed: 100,
//         theme: "bootstrap"
//     });
//     $(".miniColor4").minicolors({
//         animationSpeed: 50,
//         animationEasing: "swing",
//         change: null,
//         changeDelay: 0,
//         control: "hue",
//         defaultValue: $lightBlueActive,
//         hide: null,
//         hideSpeed: 100,
//         inline: false,
//         letterCase: "uppercase",
//         opacity: true,
//         position: "top right",
//         show: null,
//         showSpeed: 100,
//         theme: "bootstrap"
//     })
// };