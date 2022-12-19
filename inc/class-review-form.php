<?php
    class review_Form_New {

        public function __construct() {

            add_action('wp_enqueue_scripts', [$this, 'enqueue']);
            add_action('init', [$this, 'review_form_shortcode']);

            add_action('wp_ajax_review_form', [$this, 'review_form']);
            add_action('wp_ajax_nopriv_review_form', [$this, 'review_form']);
        }

        public function enqueue() {
            wp_enqueue_script('review_form_script', plugins_url('simple-reviews-form/assets/js/front/review-form.js'), array('jquery'), 1.0, true);

            wp_localize_script('review_form_script', 'review_form_script_var', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('_wpnonce'),
                )
            );
        }
    
    public function review_form_shortcode() {
        add_shortcode('the_review_form', [$this, 'review_form_html']);
    }



    public function review_form_html($atts, $content){
        $out = '
        <div class="wrapper">
            <div id="review_form_result"></div>
            <form method="post" id="add_review">
                <div>
                    <label for="review_title">Заголовок сообщения: </label>
                    <input placeholder="Введите заголовок" type="text" name="review_title" id="review_title" value="" tabindex="1" />
                </div>
                <div>
                    <label for="review_description">Текст сообщения:</label>
                    <textarea name="review_description" id="review_description" placeholder="Введите текст сообщения" tabindex="2"></textarea>
                </div>
                <div>
                    <label for="review_name">Имя:</label>
                    <input placeholder="Введите имя" type="text" name="review_name" id="review_name" value="" tabindex="3" />
                </div>
                <div>
                    <label for="review_link">Ссылка на соцсети:</label>
                    <input placeholder="Введите ссылку на соцсети" type="text" name="review_link" id="review_link" value="" tabindex="4" />
                </div>
                <div>
                    <input type="submit" tabindex="5" name="submit" id="review_plug_submit" value="Отправить отзыв" />
                </div>
            </form>
        </div>  
            ';
            return $out;
    }

    function review_form() {
         check_ajax_referer('_wpnonce', 'nonce');

        // print_r($_POST);
        if(!empty($_POST)){

            if(isset($_POST['title'])){
                $title = sanitize_text_field( $_POST['title'] );
            }
            if(isset($_POST['description'])){
                $description = sanitize_textarea_field( $_POST['description'] );
            }
            if(isset($_POST['name'])){
                $name = sanitize_text_field( $_POST['name'] );
            }
            if(isset($_POST['link'])){
                $link = sanitize_text_field( $_POST['link'] );
            }

            $review_item = array();

            $review_item['post_type'] = 'review';
            $review_item['post_title'] = $title;
            $review_item['post_content'] = $description;
            $review_item['post_status'] = 'pending';

            $review_item_id = wp_insert_post($review_item);

            if($review_item_id > 0) {
                do_action('wp_insert_post', 'wp_insert_post');
            }


            if($review_item_id > 0) {
                if($name && $name !='') {
                    update_post_meta($review_item_id, 'review_name', $name);
                }
                if($link && $link !='') {
                    update_post_meta($review_item_id, 'review_social', $link);
                }
            }

    //         // $message = 'The message';
    //         // $data_message = 'Data message';
    //         // //Email Admin
    //         // wp_mail(get_option( 'adminemail', 'New Reservation', $data_message ), 'subject', $data_message);
    //         // //Email Client
    //         // $message = 'The message';
    //         // wp_mail($email, 'subject', $message);

        } else {
            echo 'smth go wrong';
        }

        wp_die();
    }
}

$review_form = new review_Form_New();
