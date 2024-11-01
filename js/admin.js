jQuery(document).ready(function () {
    jQuery('.url_rotator_manager_delete_form').submit(function(e) {
        if ((!confirm('Are you sure you want to delete the field?'))) {
            return false;
        }
    });
    
    jQuery('.url_rotator_manager_reset_form').submit(function(e) {
        if ((!confirm('Are you sure you want to reset the counter?'))) {
            return false;
        }
    });
    
    jQuery('.url_rotator_manager_delete_url_form').submit(function(e) {
        if ((!confirm('Are you sure you want to delete the field?'))) {
            return false;
        }
    });
    
    jQuery('#url_rotator_manager_name').keyup(function() {
        var val = jQuery('#url_rotator_manager_name').val();
        jQuery('#url_rotator_manager_name').val( val.replace(" ","-") );

    });

    jQuery('#url_rotator_manager_name').change(function () {

        jQuery.each(aName, function (key, value) {
            if (value.name == jQuery('#url_rotator_manager_name').val()) {
                jQuery('#url_rotator_manager_link').val(value.link);

                jQuery('#url_rotator_manager_submit').val('Modify');
                jQuery('#url_rotator_manager_name').prop('readonly', true);
                jQuery('#url_rotator_manager_cancel').show();
            }
        })
    })

    jQuery('.edit').click(function () {
        jQuery(this).parent().parent().css('border', 'solid 1x red');
        name = jQuery(this).attr('data-name');
        key = jQuery(this).attr('data-key');
        url = jQuery(this).attr('data-url');

        jQuery('#url_rotator_manager_key_' + name).val(key);
        jQuery('#url_rotator_manager_url_' + name).val(url);

        jQuery('#url_rotator_manager_new_url_submit_' + name).val('Modify');
        jQuery('#url_rotator_manager_new_url_cancel_' + name).show();
    });

    jQuery('.url_rotator_manager_new_url_cancel').click(function () {
        name = jQuery(this).attr('data-name');
        
        jQuery('#url_rotator_manager_key_' + name).val(null);
        jQuery('#url_rotator_manager_url_' + name).val(null);
        jQuery('#url_rotator_manager_new_url_submit_' + name).val('Save');

        jQuery('#url_rotator_manager_new_url_cancel_' + name).hide();
    });
})