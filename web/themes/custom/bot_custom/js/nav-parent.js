(function ($, Drupal) {
    Drupal.behaviors.BOTCustomNavParent = {
        attach: function (context, settings) {
          $('.dropdown-toggle').click(function(e) {
            e.preventDefault();
            
            if ($(window).width() > 768) {
              var location = $(this).attr('href');
              window.location.href = location;
              return false;
            }
          });
        }
    };
})(jQuery, Drupal);
