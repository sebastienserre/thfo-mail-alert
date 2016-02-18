/**
 * Created by sebastien on 05/02/16.
 */

var uploader;
function upload_image(id) {

    //Extend the wp.media object
    uploader = wp.media.frames.file_frame = wp.media({
        title: 'Choose Image',
        button: {
            text: 'Choose Image'
        },
        multiple: false
    });

    //When a file is selected, grab the URL and set it as the text field's value
    uploader.on('select', function() {
        attachment = uploader.state().get('selection').first().toJSON();
        var url = attachment['url'];
        jQuery('#'+id).val(url);
    });

    //Open the uploader dialog
    uploader.open();
}

