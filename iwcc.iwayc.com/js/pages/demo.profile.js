jQuery(document).ready(function(a) {
    lightGallery();
    nanoScroller();
    progressBar();
    userCircliful();
    userEasyPieChart();
    userTaskManager();
    morrisCHartStart();
    a(window).resize(function() {
        var b = a(window).width();
        if (b < 1025) {
            a("#hero-donut").html("");
            clearTimeout(resizeIdMorris);
            resizeIdMorris = setTimeout(morrisCHartStart, 600)
        }
    });
    userGoMap()
});

function lightGallery() {
    $("#ls-project-lightGallery").lightGallery()
}

function nanoScroller() {
    $(".nano").nanoScroller({
        preventPageScrolling: true
    })
}

function progressBar() {
    $(".progress-bar").progressbar({
        display_text: "fill"
    })
}

function userCircliful() {
    $("#ls-user-friends").circliful({
        foregroundColor: "#269ABC",
        backgroundColor: "#eee",
        width: 3,
        dimension: "112",
        fillColor: false
    });
    $("#ls-user-followers").circliful({
        foregroundColor: "#269ABC",
        backgroundColor: "#eee",
        width: 3,
        dimension: "112",
        fillColor: false
    });
    $("#ls-user-following").circliful({
        foregroundColor: "#269ABC",
        backgroundColor: "#eee",
        width: 3,
        dimension: "112",
        fillColor: false
    });
    $("#ls-user-posts").circliful({
        foregroundColor: "#269ABC",
        backgroundColor: "#eee",
        width: 3,
        dimension: "112",
        fillColor: false
    });
    $("#ls-user-likes").circliful({
        foregroundColor: "#269ABC",
        backgroundColor: "#eee",
        width: 3,
        dimension: "112",
        fillColor: false
    })
}

function userEasyPieChart() {
    $(".easyPieChartBlack").easyPieChart({
        barColor: "#269ABC",
        scaleColor: "#FF7878",
        easing: "easeOutBounce",
        size: 100,
        lineWidth: 3,
        onStep: function(c, b, a) {
            $(this.el).find(".easyPieChartBlackPercent").text(Math.round(a))
        }
    })
}

function morrisCHartStart() {
    Morris.Donut({
        element: "hero-donut",
        data: [{
            label: "OSX",
            value: 50
        }, {
            label: "Linux",
            value: 60
        }, {
            label: "Ubuntu",
            value: 90
        }, {
            label: "Other",
            value: 10
        }],
        formatter: function(a) {
            return a + "%"
        },
        colors: [$fillColor2, $redActive, $greenActive, $lightBlueActive]
    })
}

function userGoMap() {
    $(".user-google-location").click(function() {
        $("#user-locator").goMap({
            markers: [{
                latitude: 56.948813,
                longitude: 24.704004,
                html: {
                    content: "Mr. John Doe",
                    popup: true
                }
            }],
            hideByClick: true
        })
    })
}

function userTaskManager() {
    $("ul#slippylist li i").click(function() {
        if ($(this).parent("li").hasClass("strikeout")) {
            $(this).parent("li").removeClass("strikeout");
            $(this).addClass("fa-circle-o");
            $(this).removeClass("fa-check")
        } else {
            $(this).addClass("fa-check");
            $(this).removeClass("fa-circle-o");
            $(this).parent("li").addClass("strikeout")
        }
    });
    $(document).on("click", "button.removeTask", function() {
        $(this).parent("li").remove();
        new Slip(a)
    });
    var a = document.getElementById("slippylist");
    a.addEventListener("slip:beforereorder", function(b) {
        if (/demo-no-reorder/.test(b.target.className)) {
            b.preventDefault()
        }
    }, false);
    a.addEventListener("slip:beforeswipe", function(b) {
        if (b.target.nodeName == "INPUT" || /demo-no-swipe/.test(b.target.className)) {
            b.preventDefault()
        }
    }, false);
    a.addEventListener("slip:beforewait", function(b) {
        if (b.target.className.indexOf("instant") > -1) {
            b.preventDefault()
        }
    }, false);
    a.addEventListener("slip:afterswipe", function(b) {
        b.target.parentNode.removeChild(b.target)
    }, false);
    a.addEventListener("slip:reorder", function(b) {
        b.target.parentNode.insertBefore(b.target, b.detail.insertBefore);
        return false
    }, false);
    new Slip(a)
};