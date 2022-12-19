<?php

$properties = get_posts(array('post_type'=>'review', 'numberposts'=>-1));
foreach ($reviews as $review ) {
    wp_delete_post($review->ID, true);
}