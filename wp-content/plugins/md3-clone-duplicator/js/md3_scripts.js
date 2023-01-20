jQuery(document).ready(($) => {
    //$('#show-advanced-options').parent().hide();
    $('#country_market').select2();
    $('#site-currency').select2();
    //$('#site-published-language').select2();
    $('#site-languages').select2({
        width: 'resolve',
        maximumSelectionLength: 2
    }).on('select2:unselecting', function (e) {
        // before removing tag we check option element of tag and 
        // if it has property 'locked' we will create error to prevent all select2 functionality
        if ($(e.params.args.data.element).attr('locked')) {
            e.preventDefault();
        }
    })
        .on('change', function (e) {
            var options = $('#site-languages').select2('data').map(function (option) {
                return '<option value="' + option.id + '">' + option.text + '</option>';
            });
            $('#site-published-language').html(options)
        });

    /*
    $('.sites_page_md3-clone-duplicator form').submit(function (e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.

        var form = $(this);
        var url = form.attr('action');

        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(), // serializes the form's elements.
            success: function (data) {
                alert(data); // show response from the php script.
            }
        });


    });
    */
});