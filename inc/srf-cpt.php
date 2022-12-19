<?php
if (!class_exists('SRFCpt')) {
    class SRFCpt
    {
        public function register(){
            add_action('init', [$this, 'custom_post_type']);
            add_action('add_meta_boxes', [$this, 'add_meta_box_review']);
            add_action('save_post', [$this, 'save_metabox'], 10, 2);
        }
        public function add_meta_box_review(){
            add_meta_box(
                'review_additional',
                'Review Additional Information',
                [$this, 'metabox_review_html'],
                'review',
                'normal',
                'default'
            );
        }

        public function save_metabox($post_id, $post) {

            if(!isset($_POST['_review']) || !wp_verify_nonce( $_POST['_review'], 'reviewfields')) {
                return $post_id;
            }

            if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return $post_id;
            }

            if ($post->post_type != 'review') {
                return $post_id;
            }

            //Эта проверка работает криво, надо разобраться
            $post_type = get_post_type_object($post->post_type);
            if(!current_user_can($post_type->cap->edit_post, $post_id)) {
                return $post_id;
            }

            if (is_null($_POST['review_name'])) {
                delete_post_meta($post_id, 'review_name');
            } else {
                update_post_meta($post_id, 'review_name', sanitize_text_field($_POST['review_name']));
            }
            if (is_null($_POST['review_social'])) {
                delete_post_meta($post_id, 'review_social');
            } else {
                update_post_meta($post_id, 'review_social', sanitize_text_field($_POST['review_social']));
            }
            
            
        }
        public function metabox_review_html($post){
            $name = get_post_meta($post->ID, 'review_name', true);
            $social = get_post_meta($post->ID, 'review_social', true);


            wp_nonce_field('reviewfields', '_review');

            echo '
            <div>
                <label for="review_name">Name</label></label>
                <input type="text" id="review_name" name="review_name" value="'.esc_html($name).'">
            </div>
            <div>
                <label for="review_social">Social link</label>
                <input type="text" id="review_social" name="review_social" value="'.esc_html($social).'">
            </div>
            ';


    }
        public function custom_post_type()
        {
            register_post_type(
                'review',
                array(
                    'public' => true,
                    'has_archive' => true,
                    'rewrite' => ['slug' => 'reviews'],
                    'label' => 'RevieW',
                    'supports' => ['title', 'editor'],
                    'description' => 'the description',
                )
            );
        }
    }


    if (class_exists('SRFCpt')) {
        $SRFCpt = new SRFCpt();
        $SRFCpt->register();
    }
}