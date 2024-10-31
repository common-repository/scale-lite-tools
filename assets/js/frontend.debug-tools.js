
jQuery(document).ready(function() {
  // Handler for .ready() called.
  jQuery('#sl-debug-header').on('click', function(){
    jQuery('#sl-debug-content').slideToggle("fast");
  });
});
