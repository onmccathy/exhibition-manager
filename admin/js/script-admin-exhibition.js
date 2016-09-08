/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
jQuery(document).ready(function () {
    jQuery('.hacc-start-date').flatpickr({
        dateFormat: 'Y-m-d',
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 2,
        onChange: function (d) {
            jQuery(".hacc-end-date").flatpickr().set("minDate", d.fp_incr(1));
        }
    }
    );
    jQuery('.hacc-end-date').flatpickr({
        dateFormat: 'Y-m-d',
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 2,
        onChange: function (d) {
               jQuery(".hacc-start-date").flatpickr().set("maxDate", d);
        }
        
    }
    );
});
jQuery(document).ready(function () {
    jQuery('.hacc-timepicker').flatpickr({
        enableTime: true,
        noCalendar: true,
        minuteIncrement: 15,
    });
});