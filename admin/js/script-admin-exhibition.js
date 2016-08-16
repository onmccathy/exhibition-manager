/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    jQuery('.hacc-start-date').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 2,
        onSelect: function (selected) {
          jQuery(".hacc-end-date").datepicker("option","minDate",selected)  
        }
    }
    );
    jQuery('.hacc-end-date').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 2,
        onSelect: function (selected) {
          jQuery(".hacc-start-date").datepicker("option","maxDate",selected)  
        }
    }
    );
});
jQuery(document).ready(function () {
    jQuery('.hacc-timepicker').timepicker({
        'minTime': '7:00am',
        'maxTime' : '10:30pm',
        'durationTime' : '7.00am'
        });
});