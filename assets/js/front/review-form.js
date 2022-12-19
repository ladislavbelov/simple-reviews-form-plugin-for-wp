jQuery(document).ready(function($){

    $('#review_plug_submit').on('click', function(e){
            e.preventDefault();

            $.ajax({
                url: review_form_script_var.ajaxurl,
                type: 'post',
                data: {
                    action: 'review_form',
                    nonce: review_form_script_var.nonce,
                    title: $('#review_title').val(),
                    description: $('#review_description').val(),
                    name: $('#review_name').val(),
                    link: $('#review_link').val(),
                },
                success: function(data) {
                    $('#review_form_result').html(data);
                },
                error: function(errorThrow) {
                    console.log(errorThrow);
                }
            });
    });
});