var easyComm = {
    initialize: function(){
        if(!jQuery().ajaxForm) {
            easyComm.notice.error('Can`t find jQuery ajaxForm plugin!');
        }
        easyComm.rating.initialize();
        jQuery(document).on('submit', 'form.ec-form', function(e){
            easyComm.message.send(this);
            e.preventDefault();
            return false;
        });
    },

    message: {
        send: function(form) {
            jQuery(form).ajaxSubmit({
                data: {action: 'message/create'}
                ,url: easyCommConfig.actionUrl
                ,form: form
                ,dataType: 'json'
                ,beforeSubmit: function() {
                    jQuery(form).find('input[type="submit"]').attr('disabled','disabled');
                    jQuery(form).find('.has-error').removeClass('has-error');
                    jQuery(form).find('.ec-error').text('').hide();
                    return true;
                }
                ,success: function(response) {
                    var fid = jQuery(form).data('fid');
                    jQuery(form).find('input[type="submit"]').removeAttr('disabled');
                    if (response.success) {
                        jQuery(form)[0].reset();
                        if(typeof (response.data) == "string") {
                            jQuery('#ec-form-success-' + fid).html(response.data);
                            jQuery(form).hide();
                        }
                        else {
                            easyComm.notice.show(response.message);
                        }
                    }
                    else {
                        if(response.data && response.data.length) {
                            jQuery.each(response.data, function(i, error) {
                                jQuery(form).find('[name="' + error.field + '"]').closest('.form-group').addClass('has-error');
                                jQuery(form).find('#ec-' + error.field + '-error-' + fid).text(error.message).show().closest('.form-group').addClass('has-error');
                            });
                        } else {
                            easyComm.notice.error(response.message);
                        }
                    }
                }
                ,error: function(){
                    jQuery(form).find('input[type="submit"]').removeAttr('disabled');
                    easyComm.notice.error('Submit error');
                }
            });
        }
    },


    rating: {
        initialize: function(){
            var stars = jQuery('.ec-rating').find('.ec-rating-stars>span');
            stars.on('touchend click', function(e){
                var starDesc = jQuery(this).data('description');
                jQuery(this).parent().parent().find('.ec-rating-description').html(starDesc).data('old-text', starDesc);
                jQuery(this).parent().children().removeClass('active active2 active-disabled');
                jQuery(this).prevAll().addClass('active');
                jQuery(this).addClass('active');
                // save vote
                var storageId = jQuery(this).closest('.ec-rating').data('storage-id');
                jQuery('#' + storageId).val(jQuery(this).data('rating'));
            });
            stars.hover(
                // hover in
                function() {
                    var descEl = jQuery(this).parent().parent().find('.ec-rating-description');
                    descEl.data('old-text', descEl.html());
                    descEl.html(jQuery(this).data('description'));
                    jQuery(this).addClass('active2').removeClass('active-disabled');
                    jQuery(this).prevAll().addClass('active2').removeClass('active-disabled');
                    jQuery(this).nextAll().removeClass('active2').addClass('active-disabled');
                },
                // hover out
                function(){
                    var descEl = jQuery(this).parent().parent().find('.ec-rating-description');
                    descEl.html(descEl.data('old-text'));
                    jQuery(this).parent().children().removeClass('active2 active-disabled');
                }
            );
        }
    },

    notice: {
        error: function(text) {
            alert(text);
        },
        show: function(text) {
            alert(text);
        }
    }
}

jQuery(document).ready(function(){
    easyComm.initialize();
});

var easyCommReCaptchaCallback = function() {
    if(typeof grecaptcha !== 'undefined'){
        $('.ec-captcha').each(function(index) {
            grecaptcha.render($(this).attr('id'), {
                'sitekey' : easyCommConfig.reCaptchaSiteKey
            });
        });
    }
    else {
        easyComm.notice.error('grecaptcha is not defined!');
    }
}