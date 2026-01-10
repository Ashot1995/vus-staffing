
  (function ($) {
  
  "use strict";

    // NAVBAR - Only collapse when clicking non-dropdown links
    $('.navbar-nav .nav-link:not(.dropdown-toggle)').click(function(){
        $(".navbar-collapse").collapse('hide');
    });
    
    // Handle dropdown items - collapse navbar after navigation
    $('.navbar-nav .dropdown-item').click(function(){
        $(".navbar-collapse").collapse('hide');
    });

    // CUSTOM LINK 
    $('.custom-link').click(function(){
    var el = $(this).attr('href');
    var elWrapped = $(el);
    var header_height = $('.navbar').height() + 10;

    scrollToDiv(elWrapped,header_height);
    return false;

    function scrollToDiv(element,navheight){
      var offset = element.offset();
      var offsetTop = offset.top;
      var totalScroll = offsetTop-navheight;

      $('body,html').animate({
      scrollTop: totalScroll
      }, 300);
  }
});
    
  })(window.jQuery);


