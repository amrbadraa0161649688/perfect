// Header Scrolled
 $(document).on('scroll', function() {
     $(window).scroll(function(){
         var scroll = $(window).scrollTop();
         if (scroll > 50) {
             $(".navbar").addClass("scrolled");
         }
         else{
             $(".navbar").removeClass("scrolled");
         }
     });
 });
// Side Menu
$(".toggle-menu").click(function (e) {
    e.preventDefault();
    $(".toggle-menu, .side-wrapper").toggleClass("active");
});
// Change input on checked
$('.custom-radio input[type=radio]').change(function() {
    if($('#customRadio1').is(':checked')) {
        $("#input-container").addClass("active");
        $("#input-booking").removeClass("active");
    }
    else if($('#customRadio2').is(':checked')) {
        $("#input-booking").addClass("active");
        $("#input-container").removeClass("active");
    }
});
// tracking
$("#trackingLink").click(function () {
    $('#trackingTab').trigger('click');
})
// Change background on hover
$('ul.solutions-list > li').hover(function () {
    $("ul.solutions-list > li").removeClass("active");
    $(this).addClass("active");
})
// Counter
$(document).ready(function(){
    function counterStart() {
        $('.counter-number').each(function() {
            var $this = $(this),
                countTo = $this.attr('data-count');

            $({ countNum: $this.text()}).animate({
                    countNum: countTo
                },
                {
                    duration: 1000,
                    easing:'linear',
                    step: function() {
                        $this.text(Math.floor(this.countNum));
                    },
                    complete: function() {
                        $this.text(this.countNum);
                        //alert('finished');
                    }

                });
        });
    }
    $(window).scroll(function () {
        var counterSection =  $('.counter-item');
        var sectionTop = $( window ).height()  + counterSection.height();

        if($(this).scrollTop()>=sectionTop){
            counterStart()
        }
    });
});
// Owl
$(document).ready(function() {
    $('.owl-carousel.advisories-slider').owlCarousel({
        loop: true,
        dots: false,
        nav:true,
        navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"] ,
        margin: 20,
        responsiveClass:true,
        responsive:{
            0:{
                items:1,
            },
            992:{
                items:2,
            },
            1200:{
                items:3,
            }
        }
    });
    $('.owl-carousel.partners-slider').owlCarousel({
        loop: true,
        dots: false,
        nav:true,
        navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"] ,
        margin: 20,
        responsiveClass:true,
        responsive:{
            0:{
                items:1,
            },
            768:{
                items:3,
            },
            992:{
                items:4,
            },
            1200:{
                items:5,
            }
        }
    });
})
// When the user clicks on the button, scroll to the top of the document
function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}
// Tooltip
$(document).ready(function() {
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
});

// WOW =====================
$(document).ready(function(){
    wow = new WOW
    (
        {
            boxClass: 'wow',            // default
            animateClass: 'animated',   // default
            offset: 1,                  // default
            mobile: false,               // default
            live: true                  // default
        }
    );
    wow.init()
});
// Footer bottom
$(document).ready(function(){
    let footer = $("footer");
    function footerAlign() {
        footer.css('display', 'block');
        footer.css('height', 'auto');
        let footerHeight = footer.outerHeight();
        $('body').css('padding-bottom', footerHeight);
        footer.css('height', footerHeight);
    }
    footerAlign();
    $(window).resize(function () {
        footerAlign();
    });
});
//
<!-- GetButton.io widget -->

<!-- /GetButton.io widget -->