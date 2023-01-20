jQuery(document).ready(function($){
    jQuery('#add-new-shortcut-row').click(function(){
        var template = jQuery('#superpwa-appshortcut-template').html();
        var sec = jQuery('.superpwa-repeater-sec:last').attr('data-current');
        template = template.replace(/%i%/g, parseInt(sec)+1)
        jQuery('.superpwa-repeater-wrap').append(template);
    })// #addition-shortcut click event closed

    jQuery(document).on('click','.superpwa-repeater-trash', function(e){
        e.preventDefault();
        var self = jQuery(this);
            self.parents('.superpwa-repeater-sec').remove();
    })// .pwaforwp-repeat-trash click event closed

    jQuery(document).on('click', ".superpwa-set-shortcut-icon", function(e) {  // Application Icon upload
        var self = jQuery(this);
        e.preventDefault();
        var superpwaMediaUploader = wp.media({
            title: 'Select APP Shortcut (192x192)',
            button: {
                text: 'Insert'
            },
            multiple: false,  // Set this to true to allow multiple files to be selected
            library:{type : 'image', }
        })
        .on("select", function() {
            var attachment = superpwaMediaUploader.state().get("selection").first().toJSON();
            self.parent().find(".superpwa-icon-field").val(attachment.url);
        })
        .open();
    });
});