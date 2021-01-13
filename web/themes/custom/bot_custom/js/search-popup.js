(function ($, Drupal) {
  Drupal.behaviors.BOTCustomSearchPopup = {
    attach: function (context, settings) {
      $('#block-searchform', context).once('BOTCustomSearchPopup').each(function () {
        $('#block-searchform').append('<a class="close-btn" href="javascript:void(0)">Close</a>');
      });

      $('#block-searchform').on('click.BOTCustomSearchPopupClose', '.close-btn', function(e){
        e.preventDefault();
        window.location.hash = '';
      });
    }
  };
})(jQuery, Drupal);
