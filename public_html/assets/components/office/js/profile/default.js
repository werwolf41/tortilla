Office.Profile = {

    container: null,

    initialize: function (selector) {
        var elem = $(selector);
        this.container = elem;
        if (!elem.length) {
            return false;
        }

        // Disable elements during ajax request
        $(document).ajaxStart(function () {
            elem.find('button, a, input, select, textarea').attr('disabled', true).addClass('tmp-disabled');
        })
            .ajaxStop(function () {
                elem.find('.tmp-disabled').attr('disabled', false);
            });

        $(document).on('click', '#office-user-photo-remove', function (e) {
            e.preventDefault();
            Office.Profile.clearPhoto(elem);
            elem.submit();
            return false;
        });

        $(document).on('submit', selector, function (e) {
            $(this).ajaxSubmit({
                url: OfficeConfig.actionUrl,
                dataType: 'json',
                beforeSubmit: function (data) {
                    Office.Message.close();
                    var $form = $(selector);
                    $form.find('.desc').show();
                    $form.find('.message').text('');
                    $form.find('.has-error').removeClass('has-error');
                    data.push({name: 'action', value: 'Profile/Update'});
                    data.push({name: 'pageId', value: OfficeConfig.pageId});
                },
                success: function (response) {
                    var i;
                    if (response.success) {
                        Office.Message.success(response.message);
                        Office.Profile.clearPhoto(elem);
                        if (response.data) {
                            for (i in response.data) {
                                if (response.data.hasOwnProperty(i)) {
                                    Office.Profile.setValue(i, response.data[i], selector);
                                }
                            }
                        }
                    }
                    else {
                        Office.Message.error(response.message, false);
                        if (response.data) {
                            for (i in response.data) {
                                if (response.data.hasOwnProperty(i)) {
                                    Office.Profile.setError(i, response.data[i], selector);
                                }
                            }
                        }
                    }
                }
            });
            return false;
        });

        return true;
    },

    clearPhoto: function (elem) {
        var $newphoto = elem.find('input[name="newphoto"]');
        $newphoto.val('').replaceWith($newphoto.clone(true));
        elem.find('input[name="photo"]', this.container).attr('value', '');
    },

    setValue: function (key, value, selector) {
        var $field = $('[name="' + key + '"]', this.container);
        if (typeof(value) == 'object') {
            for (var i in value) {
                if (!value.hasOwnProperty(i)) {
                    continue;
                }
                this.setValue(key + '[' + i + ']', value[i]);
            }
        }
        else {
            if (key == 'photo') {
                var $photo = $('#profile-user-photo');
                if (value != '') {
                    $photo.prop('src', value);
                    $('#office-user-photo-remove').show();
                }
                else {
                    $photo.prop('src', $photo.data('gravatar'));
                    $('#office-user-photo-remove').hide();
                }
            }
            if (key == 'phone_code') {
                if (value) {
                    $(selector).find('input').attr('readonly', true);
                    $field.attr('readonly', false)
                        .parents('.hidden').removeClass('hidden').addClass('not-hidden');
                } else {
                    $(selector).find('input').attr('readonly', false);
                    $field.attr('readonly', true)
                        .parents('.not-hidden').removeClass('not-hidden').addClass('hidden');
                }
                value = '';
            }
            $field.val(value);
        }
    },

    setError: function (key, value) {
        if (typeof(value) == 'object') {
            for (var i in value) {
                if (!value.hasOwnProperty(i)) {
                    continue;
                }
                this.setError(key + '[' + i + ']', value[i]);
            }
        }
        else {
            var $parent = $('[name="' + key + '"]', this.container).parent();
            $parent.addClass('has-error');
            $parent.find('.desc').hide();
            $parent.find('.message').text(value);
        }
    },

};

Office.Profile.initialize('#office-profile-form');