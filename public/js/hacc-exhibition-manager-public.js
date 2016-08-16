/*
 * colorbox - Display gallary images
 */
jQuery(document).ready(function ($) {
    //Settings for lightbox
    var cbSettings = {
      rel: 'gallery-icon',
      width: '95%',
      height: 'auto',
      maxWidth: '660',
      maxHeight: 'auto',
      title: function() {
        return $(this).find('img').attr('alt');
      }
    }
    //Initialize jQuery Colorbox   
    $('.gallery-icon a[href$=".jpg"],.gallery-icon a[href$=".jpeg"],.gallery-icon a[href$=".png"],.gallery-icon a[href$=".gif"]').colorbox(cbSettings);
    
    //Keep lightbox responsive on screen resize
    $(window).on('resize', function() {
        $.colorbox.resize({
        width: window.innerWidth > parseInt(cbSettings.maxWidth) ? cbSettings.maxWidth : cbSettings.width
      }); 
    });
});