/**
 * Created by Vlad Karapetyan on 12/25/2015.
 */
jQuery(function() {
    var scntDiv = jQuery('#p_scents');
    var i = jQuery('#p_scents p').size() + 1;

    jQuery('#add_new_scope').live('click', function() {
        jQuery('<p><input type="text" name="new_scope[]" value="" placeholder="Input scope name" /><button id="remScnt">Remove</button></p>').appendTo(scntDiv);
        i++;
        return false;
    });

    jQuery('#remScnt').live('click', function() {
        if( i > 2 ) {
            jQuery(this).parents('p').remove();
            i--;
        }
        return false;
    });

    var scntDiv_script = jQuery('#p_scents_script');
    var j = jQuery('#p_scents_script p').size() + 1;

    jQuery('#add_new_suctom_script').live('click', function() {
        jQuery('<p>' +
            '<input type="text" style="margin-right: 5px " name="new_custom_script_name_'+j+'" size="30" placeholder="Display name (example Google+)" />' +
            '<input type="text" style="margin-right: 5px; margin-left: 5px; " name="new_custom_script_value_'+j+'" size="40" placeholder="ACR Value (script name in the Gluu Server)" />' +
            '<input type="hidden" name="image_url_'+j+'" id="image_url_'+j+'" class="regular-text">'+
            '<input type="button" style="margin-right: 5px; margin-left: 3px; " name="upload-btn" onclick="upload_this('+j+')" id="upload-btn_'+j+'" class="button-secondary" value="Upload app image (120x120)" />' +
            '<button id="remScnt_script">Remove</button>' +
            '</p>').appendTo(scntDiv_script);
        j++;
        jQuery('#count_scripts').val(jQuery('#p_scents_script p').size());
        return false;
    });

    jQuery('#remScnt_script').live('click', function() {
        if( j > 2 ) {
            jQuery(this).parents('p').remove();
            j--;
            jQuery('#count_scripts').val(jQuery('#p_scents_script p').size());
        }
        return false;
    });
});
function upload_this(k){

    var image = wp.media({
        title: 'Upload Image',
        // mutiple: true if you want to upload multiple files at once
        multiple: false
    }).open()
        .on('select', function(e){
            // This will return the selected image from the Media Uploader, the result is an object
            var uploaded_image = image.state().get('selection').first();
            // We convert uploaded_image to a JSON object to make accessing it easier
            // Output to the console uploaded_image
            var image_url = uploaded_image.toJSON().url;
            // Let's assign the url value to the input field
            jQuery('#image_url_'+k).val(image_url);
        });
}
jQuery(document).ready(function(){
    jQuery("#show_script_table").click(function(){
        jQuery("#custom_script_table").toggle();
    });
    jQuery("#show_scope_table").click(function(){
        jQuery("#custom_scope_table").toggle();
    });
});
function delete_custom_script(val){
    jQuery.ajax({
        url: window.location,
        type: 'POST',
        data:{option:'oxd_openid_config_info_hidden', delete_value:val},
        success: function(result){
            if(result){
                location.reload();
            }else{
                alert('Error, please try again.')
            }
        }});
}
function delete_scopes(val){
    jQuery.ajax({
        url: window.location,
        type: 'POST',
        data:{option:'oxd_openid_config_info_hidden', delete_scope:val},
        success: function(result){
            if(result){
                location.reload();
            }else{
                alert('Error, please try again.')
            }
        }});
}