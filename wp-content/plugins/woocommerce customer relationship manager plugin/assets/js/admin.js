jQuery(function ($) {
	jQuery('#recipients').textboxlist({unique: true, bitsOptions: {editable: {addKeys: [188]}}});
});

jQuery('document').ready(function($){
    if($('#related_to').length > 0){
        $('#related_to').change(function(){
            $('.related_by').hide();
            if($(this).val() == 'order') $('#related_by_order').show();
            if($(this).val() == 'product') $('#related_by_product').show();
        });
    }
    if($(".display_time").length > 0){
        var callTimer = new (function() {

        // Stopwatch element on the page
        var $stopwatch;

        // Timer speed in milliseconds
        var incrementTime = 60;

        // Current timer position in milliseconds
        var currentTime = 0;

        // Start the timer
        $(function() {
            $stopwatch = $('.display_time');
            callTimer.Timer = $.timer(updateTimer, incrementTime, false);
        });

        // Output time and increment
        function updateTimer() {
            formatTimeDuration(currentTime);
            var timeString = formatTime(currentTime);
            $stopwatch.html(timeString);
            currentTime += incrementTime;
        }

        // Reset timer
        this.resetStopwatch = function() {
            currentTime = 0;
            var timeString = formatTime(currentTime);
            $stopwatch.html(timeString);
            callTimer.Timer.stop();
            $('#stop_timer, #pause_timer, #reset_timer').hide().removeClass('play');
            $('.completed_call_wrap').hide();
            $('#start_timer').show();
        };

    });
        $('#start_timer').click(function(){
            callTimer.Timer.play();
            setCurrentTime();
            $('#stop_timer, #pause_timer, #reset_timer').show();
            $('#start_timer').hide();
            return false;
        });
        $('#stop_timer').click(function(){
            callTimer.Timer.stop();
            $('.completed_call_wrap').show();
            $('#pause_timer').removeClass('play').hide();
            return false;
        });
        $('#pause_timer').click(function(){
            $(this).toggleClass('play');
            callTimer.Timer.toggle();
            return false;
        });
        $('#reset_timer').click(function(){
            callTimer.resetStopwatch();
            return false;
        });

        $('#related_to').change(function(){
            var related_to = $('#related_to').val();
            $('#view_info').attr('href', '?page=wc-customer-relationship-manager&'+related_to+'_list='+related_to+'&order_id='+$('#order_id').val());
        });


        var myDate = new Date();
        var month = myDate.getMonth()+1;
        var day = myDate.getDate();
        if(month < 10){
            month = '0'+month;
        }
        if(day < 10){
            day = '0'+day;
        }
        var prettyDate =myDate.getFullYear() + '-' + month + '-' + day;
        $("#call_date").val(prettyDate);
        $( "#call_date" ).datepicker({
            dateFormat: "yy-mm-dd",
            numberOfMonths: 1,
            showButtonPanel: true,
            maxDate: '0',
            changeMonth: true,
            changeYear: true

        });
        $('#new_call').click(function(){
            if( $('#user_phone').val() == '' ){
                $( '.error_message', $('#user_phone').parent() ).text('Please enter user phone!').show();
                return false;
            }else if( !checkPhone($('#user_phone').val()) ){
                $( '.error_message', $('#user_phone').parent() ).text('Please enter valid phone number!').show();
                return false;
            }
            else{
                $( '.error_message', $('#user_phone').parent() ).hide();
            }
        });
        $('#wc_crm_customers_form').submit(function(){
            $('.error.below-h2').hide();
            $('.form-invalid').removeClass('form-invalid');
            var err = '';
            if( $('#subject_of_call').val() == '' ){
                var error_text = $( '.error_message', $('#subject_of_call').parent() ).html();
                err += '<p>'+error_text+'</p>';
                $('#subject_of_call').parents('tr').addClass('form-invalid');
            }
            if( $('#call_date').val() == '' && $('#call_date').is(':visible') ){
                var error_text = $( '.error_message', $('#call_date').parent() ).html();
                err += '<p>'+error_text+'</p>';
                $('#call_date').parents('tr').addClass('form-invalid');
            }
            var order_num = $('#number_order_product').val();
            order_num = order_num.replace('#', '') ;

            if( $('#related_to').val() == 'order' && order_num == '' ){
                var error_text = '<strong>ERROR</strong>: Please enter Order Number.';
                err += '<p>'+error_text+'</p>';
                $('#related_to').parents('tr').addClass('form-invalid');
            }
            if( $('#related_to').val() == 'product' && order_num == '' ){
                var error_text = '<strong>ERROR</strong>: Please enter Product Number.';
                err += '<p>'+error_text+'</p>';
                $('#related_to').parents('tr').addClass('form-invalid');
            }
            order_num = order_num.replace(/[0-9]/g, '') ;
            if( order_num != ''){
                var error_text = '<strong>ERROR</strong>: Please enter valid Number.';
                err += '<p>'+error_text+'</p>';
                $('#related_to').parents('tr').addClass('form-invalid');
            }
            if( $('#call_time_h').is(':visible') ){
                var h = $('#call_time_h').val();
                var m = $('#call_time_m').val();
                var s = $('#call_time_s').val();            
                if(h=='' || m == '' || s==''){
                    var error_text = $( '.error_message', $('#call_time_h').parent() ).html();
                    err += '<p>'+error_text+'</p>';
                    $('#call_time_h').parents('tr').addClass('form-invalid');
                }
                else if( h.replace(/[0-9]/g, '')!='' || m.replace(/[0-9]/g, '')!='' || s.replace(/[0-9]/g, '')!='' || h>23 || m>59 || s>59){                
                    var error_text = $( '.error_message', $('#call_time_h').parent() ).html();
                    err += '<p>'+error_text+'</p>';
                    $('#call_time_h').parents('tr').addClass('form-invalid');
                }
            }
            if( $('#call_duration_h').is(':visible') ){
                var d_h = $('#call_duration_h').val();
                var d_m = $('#call_duration_m').val();
                var d_s = $('#call_duration_s').val();
                if(d_h=='' || d_m == '' || d_s==''){
                    var error_text = $( '.error_message', $('#call_duration_h').parent() ).html();
                    err += '<p>'+error_text+'</p>';
                    $('#call_duration_h').parents('tr').addClass('form-invalid');
                }
                else if( d_h.replace(/[0-9]/g, '')!='' || d_m.replace(/[0-9]/g, '')!='' || d_s.replace(/[0-9]/g, '')!='' || d_h>23 || d_m>59 || d_s>59 ){                
                    var error_text = $( '.error_message', $('#call_duration_h').parent() ).html();
                    err += '<p>'+error_text+'</p>';
                    $('#call_duration_h').parents('tr').addClass('form-invalid');
                }else if( d_h == 0 && d_m == 0 && d_s == 0 ){
                    var error_text = $( '.error_message', $('#call_duration_h').parent() ).html();
                    err += '<p>'+error_text+'</p>';
                    $('#call_duration_h').parents('tr').addClass('form-invalid');
                }
            }

            if(err != ''){
                $('.error.below-h2').html(err).show();
                return false;
            }
        });

        $('.call_details input').change(function(){
            var val = $(this).val();
            currentTime = 0;
            if(callTimer.Timer != undefined ) {
                callTimer.Timer.stop();

            }
            var timeString = formatTime(currentTime);
            $('.display_time').html(timeString);
            $('#stop_timer, #pause_timer, #reset_timer').hide().removeClass('play');
            $('#start_timer').show();
            if(val == 'completed_call'){
                $('.completed_call_wrap').removeClass('disabled').show();
                $('#current_call_wrap').hide();
                $('#call_time_h, #call_time_m, #call_time_s, #call_duration_h, #call_duration_m, #call_duration_s').val('');
            }else{
                $('.completed_call_wrap').addClass('disabled').hide();
                $('#current_call_wrap').show();
            }
        });
        $('#current_call').click();
    }
    if( $('#customer_data #excerpt').length > 0 ){
        $('#customer_data #excerpt').closest('p').remove();
    }
    if( $('#wc_crm_edit_customer_form').length > 0 ){
        $('#wc_crm_edit_customer_form').submit(function(){
            var user_id = $('#customer_user').val();
            var action = $('#wc_crm_customer_action').val();
            if(user_id != ''){
                if(action == 'wc_crm_customer_action_new_order'){
                    var url = 'post-new.php?post_type=shop_order&user_id='+user_id;
                    window.open(url,'_self');
                    return false; 
                }else if(action == 'wc_crm_customer_action_send_email'){
                    var url = 'admin.php?page=wc-customer-relationship-manager&action=email&user_id='+user_id;
                    window.open(url,'_blank');
                    return false; 
                }else if(action == 'wc_crm_customer_action_phone_call'){
                    var url = 'admin.php?page=wc-customer-relationship-manager&action=phone_call&user_id='+user_id;
                    window.open(url,'_blank');
                    return false; 
                }
            }   
        });
    }
    if( $('#date_of_birth').length > 0 ){
        $('#date_of_birth').datepicker({
            dateFormat: "yy-mm-dd",
            numberOfMonths: 1,
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            yearRange: '-100y:c+nn',
            maxDate: '-1d'
        });

        $('.handlediv').click(function(){
            $(this).parent().toggleClass('closed');
        });
   }
   if( $('#woocommerce-customer-notes').length > 0 ){
        // Customer notes
        $('#woocommerce-customer-notes').on( 'click', 'a.add_note_customer', function() {
            if ( ! $('textarea#add_order_note').val() ) return;

            $('#woocommerce-customer-notes').block({ message: null, overlayCSS: { background: '#fff url(' + woocommerce_admin_meta_boxes.plugin_url + '/assets/images/ajax-loader.gif) no-repeat center', opacity: 0.6 } });
            var data = {
                action:         'woocommerce_crm_add_customer_note',
                user_id:        $('#customer_user').val(),
                note:           $('textarea#add_order_note').val()
            };

            $.post( woocommerce_admin_meta_boxes.ajax_url, data, function(response) {
                $('ul.order_notes').prepend( response );
                $('#woocommerce-customer-notes').unblock();
                $('#add_order_note').val('');
            });

            return false;

        });
        $('#woocommerce-customer-notes').on( 'click', 'a.delete_customer_note', function() {
            var note = $(this).closest('li');
            $(note).block({ message: null, overlayCSS: { background: '#fff url(' + woocommerce_admin_meta_boxes.plugin_url + '/assets/images/ajax-loader.gif) no-repeat center', opacity: 0.6 } });

            var data = {
                action:         'woocommerce_crm_delete_customer_note',
                note_id:        $(note).attr('rel'),
            };

            $.post( woocommerce_admin_meta_boxes.ajax_url, data, function(response) {
                $(note).remove();
            });

            return false;
        });
    }

    jQuery('.fancybox').fancybox({
        'width'         : '75%',
        'height'        : '75%',
        'autoScale'     : false,
        'transitionIn'  : 'none',
        'transitionOut' : 'none',
        'type'          : 'iframe'
    });

    jQuery(".tips").tipTip({
        'attribute' : 'data-tip',
        'fadeIn' : 50,
        'fadeOut' : 50,
        'delay' : 200
    });


});
// Common functions
function pad(number, length) {
    var str = '' + number;
    while (str.length < length) {str = '0' + str;}
    return str;
}
function formatTime(time) {
    time = time / 10;
    var h   = parseInt(time / 360000),
        min = parseInt(time / 6000) - (h * 60),
        sec = parseInt(time / 100) - (h*60*60+min*60);
        hundredths = pad(time - (sec * 100) - (min * 6000), 2);
    return (h > 0 ? pad(h, 2) : "00") + ":" + ((min > 0 && min < 60) ? pad(min, 2) : "00") + ":" + pad(sec, 2) + ':' + hundredths;
}
function formatTimeDuration(time) {
     time = time / 10;
   var h   = parseInt(time / 360000),
        min = parseInt(time / 6000) - (h * 60),
        sec = parseInt(time / 100) - (h*60*60+min*60);
    document.getElementById("call_duration_h").value = h;
    document.getElementById("call_duration_m").value = min;
    document.getElementById("call_duration_s").value = sec;
}
function setCurrentTime() {
    var currentdate = new Date();
    document.getElementById("call_time_h").value = currentdate.getHours();
    document.getElementById("call_time_m").value = currentdate.getMinutes();
    document.getElementById("call_time_s").value = currentdate.getSeconds();
}
function isInt(n) {
   return typeof n === 'number' && n % 1 == 0;
}
function checkPhone(e){
    var number_count = 0;
    for(i=0; i < e.length; i++)
        if((e.charAt(i)>='0') && (e.charAt(i) <=9))
            number_count++;

    if (number_count == 10)
        return true;

    return false;
}