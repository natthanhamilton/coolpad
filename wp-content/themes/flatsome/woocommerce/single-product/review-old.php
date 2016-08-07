<?php
global $post, $comment;

$rating = intval(get_comment_meta($comment->comment_ID, 'rating', TRUE));

if (function_exists('wc_review_is_from_verified_owner'))
{
    $verified = wc_review_is_from_verified_owner($comment->comment_ID);
}
else
{
    $verified = wc_customer_bought_product($comment->comment_author_email, $comment->user_id, $comment->comment_post_ID);
}

?>
<li itemprop="review" itemscope itemtype="http://schema.org/Review" <?php comment_class(); ?>
    id="li-comment-<?php comment_ID() ?>">

    <div id="comment-<?php comment_ID(); ?>" class="comment_container review-item">

        <?php echo get_avatar($comment, apply_filters('woocommerce_review_gravatar_size', '60'), ''); ?>

        <div class="comment-text">

            <?php if ($rating && get_option('woocommerce_enable_review_rating') == 'yes') : ?>

                <div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="star-rating"
                     title="<?php echo sprintf(__('Rated %d out of 5', 'woocommerce'), $rating) ?>">
                    <span style="width:<?php echo ($rating / 5) * 100; ?>%"><strong
                            itemprop="ratingValue"><?php echo $rating; ?></strong> <?php _e('out of 5', 'woocommerce'); ?></span>
                </div>

            <?php endif; ?>

            <?php do_action('woocommerce_review_before_comment_meta', $comment); ?>

            <?php if ($comment->comment_approved == '0') : ?>

                <p class="meta"><em><?php _e('Your comment is awaiting approval', 'woocommerce'); ?></em></p>

            <?php else : ?>

                <p class="meta">
                    <strong itemprop="author"><?php comment_author(); ?></strong> <?php

                    if (get_option('woocommerce_review_rating_verification_label') === 'yes')
                        if ($verified)
                            echo '<em class="verified">(' . __('verified owner', 'woocommerce') . ')</em> ';

                    ?>&ndash;
                    <time itemprop="datePublished"
                          datetime="<?php echo get_comment_date('c'); ?>"><?php echo get_comment_date(wc_date_format()); ?></time>
                    :
                </p>

            <?php endif; ?>

            <?php do_action('woocommerce_review_before_comment_text', $comment); ?>

            <div itemprop="description" class="description"><?php comment_text(); ?></div>

            <?php do_action('woocommerce_review_after_comment_text', $comment); ?>
        </div>
    </div>