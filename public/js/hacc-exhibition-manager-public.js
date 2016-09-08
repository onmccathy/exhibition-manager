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

/*
 * remove all class clear elements
 */

function removeClearElements(widgetGroup) {
    
     widgetGroup.find(".clear").each( function(){
         console.log("cleared");
         (this).remove();
     });
    
}

/**
 * Set elements to the same height.
 * 
 * Calculates the tallest element in a group and sets the height of elements
 * in another group to the same height.
 *  
 * @param {type} widgetGroup - Calculates the height of the tallest element
 *  in this group of elements.
 *   
 * @param {type} widgetWrapper - Sets all wrapper group elements to the height of
 * the tallest element in the widgetGroup.
 *   
 * @returns {undefined}
 */

function equalHeight(widgetGroup) {
    var tallest = [];
    var widgetGroupWidth = widgetGroup.width();
    var widgetWrapperWidth = 0;
    var noWidgets = 0;
    var maxWidgetsperRow = 0;
    var tallestInRow = 0;
    console.log('widgetGroup.width '  + widgetGroupWidth);
    /**
     * Calculate the maximum number of widgets will fit in a row
     * by adding the widths of widgets in the first row and include padding of 30 pixels
     * at atart of row, between widgets and at end of row.
     * 
     * @returns {undefined}
     */
    var totalWidth = 0;  // this is the total width of all widgets if stacked in one row
    widgetGroup.find(".hacc-flashy-widget-wrapper").each( function(){
        
        totalWidth = totalWidth + jQuery(this).width();
        noWidgets++;
    });
    
    /*
     * add extra space for padding between, before and after widget row
     */
    totalWidth = totalWidth + noWidgets * (10+1);
    widgetWrapperWidth = Math.floor(totalWidth / noWidgets );
    maxWidgetsperRow = Math.floor(widgetGroupWidth / widgetWrapperWidth);
    
    /*
     * Calculate the tallest widget in each row and store in tallest array
     */
    var rowWidget = 0;
    widgetGroup.find(".hacc-flashy-widget-wrapper").each( function(){
        
        var thisHeight = jQuery(this).height();
        if (thisHeight >  tallestInRow) {
            tallestInRow = thisHeight;
        }
        rowWidget++;
        if (rowWidget >= maxWidgetsperRow) {
            tallest.push(tallestInRow);
            tallestInRow = 0;
            rowWidget = 0;
        }
    });
    if (rowWidget !== 0) {
        tallest.push(tallestInRow);
    }
    noWidgets = 0;
    console.log('widgetWrapperWidth ' + widgetWrapperWidth);
    console.log('maxWidgetsperRow ' + maxWidgetsperRow);
    console.log('tallest ' + tallest);
    /*
     * initialise tallest widget in first row
     * @type Number|thisHeight|Object
     */
    var tallestInRow = tallest.shift();
    var noOfWidget = 0;
    /*
     * remove all clear divs and then insert them below where the 
     * end of row is to occur
     */
     removeClearElements(widgetGroup);
    /*
     * Iterate through widgets and set the hieght of each widget wrapper
     * to the height of the tallest widget in the row.
     * Add <div class="clear"></div> at after last widget in row. 
     */
    
    widgetGroup.find(".hacc-flashy-widget-wrapper").each( function(){
        jQuery(this).height(tallestInRow);
        noOfWidget++;
        if (noOfWidget===maxWidgetsperRow) {
                       
            jQuery('<div class="clear"></div>').insertAfter(this);
            tallestInRow = tallest.shift();
            noOfWidget = 0;
        }
        console.log(jQuery(this).height());
    });
    console.log(tallest);
    
};



jQuery(document).ready(function () {
    console.log('Document Ready');
    equalHeight(jQuery(".hacc-flashycontainer-loop"));
   
});

/**
 * Adjust size of widgets in flashy container as the window is resized
 * @param {type} param
*/

jQuery(window).bind('resizeEnd', function() {
    //do something, window hasn't changed size in 500ms

    console.log('window Resize')
    equalHeight(jQuery(".hacc-flashycontainer-loop"),jQuery(".hacc-flashy-widget-wrapper"));
     
});

/*
 * Delays the call of a function for a period of time. Ie whilw user resizes the window
*/ 

jQuery(window).resize(function() {
        if(this.resizeTO) clearTimeout(this.resizeTO);
        this.resizeTO = setTimeout(function() {
            jQuery(this).trigger('resizeEnd');
        }, 500);
    });
