<?php
/**
 * Template for german and english.
 *
 * @package WordPress
 * @subpackage Customer Alliance Reviews
 * @since Customer Alliance Reviews 0.1.0
 */

require_once('helper-functions.php');

// add_query_vars for review-page required in functions.php
$reviewPage = urldecode(get_query_var('review-page', 1));

//offset and limit
$offset = ((int)$reviewPage - 1) * 20;
$limit = 20;

//get language
if (get_bloginfo("language")) {
    $lang = explode("-", get_bloginfo("language"));
    $locale = $lang[0];
} else {
    $locale = 'de';
}

//load language files
$loaded = load_plugin_textdomain('ca-reviews', FALSE, basename(plugin_dir_path(dirname(__FILE__))) . '/languages/');
if (!$loaded) {
    echo "<hr/>";
    echo "No translation found!";
}


//get option from plugin
$ca_options = get_option('ca_reviews');

if (!isset($ca_options['id_key'], $ca_options['id_key'])) {
    __('no-data', 'ca-reviews');
}

//feed with customer reviews
$reviewsCustomer = getXML('https://api.customer-alliance.com/reviews/list?version=4&id=' . $ca_options['id_key'] . '&access_key=' . $ca_options['access_key'] . '&_locale=' . $locale . '&language=' . $locale . '&limit=' . $limit . '&offset=' . $offset);
$howManyReviews = getXML('https://api.customer-alliance.com/reviews/list?version=4&id=' . $ca_options['id_key'] . '&access_key=' . $ca_options['access_key'] . '&_locale=' . $locale . '&language=' . $locale. '&limit=500');
$business = $reviewsCustomer->business;
$reviewList = $reviewsCustomer->reviews;
?>

<div class="reviews">
    <div class="business clearfix">
        <div class="size3of5 unit">
            <div class="overview">
                <?php echo $business->name ?><br>
                <?php echo $business->street ?><br>
                <?php echo $business->postCode ?> <?php echo $business->city ?><br>

                <!--        <a href="--><?php //echo $business->bookingUrls->bookingUrl[0] ?><!--">Link</a>-->
                <!--        [--><?php //echo $business->bookingUrls->bookingUrl[0]['lang'] ?><!--]<br>-->

                <!--        <a href="--><?php //echo $business->bookingUrls->bookingUrl[1] ?><!--">Link</a>-->
                <!--        [--><?php //echo $business->bookingUrls->bookingUrl[1]['lang'] ?><!--]<br>-->

                <?php echo __('positive-reviews', 'ca-reviews') ?>: <?php echo $business->ratingPositivePercent ?> %<br>
                <?php if ($howManyReviews->reviews->review !== null) { ?>
                <?php echo __('review-count', 'ca-reviews') ?>: <?php echo count($howManyReviews->reviews->children()) ?><br>
                <?php } ?>
                <span class="review-rating">
                      <span class="rating-percentage"
                            style="width:<?php echo reasonableRound($business->overallRating) * 2 * 10 ?>%">●●●●●</span>
                      ○○○○○
                </span>
            </div>
        </div>

        <div class="business-global review-box size2of5 unit">
            <table class="summary">
                <colgroup>
                    <col style="width:60%">
                    <col style="width:40%">
                </colgroup>
                <?php foreach ($business->subcategoryRatings->subcategoryRating as $rating) { ?>
                    <tr>
                        <?php //echo $rating->identifier ?>
                        <td class="category-column">
                            <?php echo $rating->category ?>:
                        </td>
                        <td class="review-column">
                        <span class="review-rating">
                             <span class="rating-percentage"
                                   style="width:<?php echo reasonableRound($rating->averageRating) * 2 * 10 ?>%">●●●●●</span>
                            ○○○○○
                        </span>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>

    </div>

    <div class="customer-reviews review-box">
        <?php $count = 0; ?>
        <?php foreach ($reviewList->review as $review) { ?>
            <?php $count++; ?>
            <div class="customer-review <?php echo ($count % 2 == 1) ? "odd" : "even"; ?>">
                <div class="review-head clearfix">
                    <?php //echo $review->id ?>
                    <?php //echo $review->language ?>
                    <span class="review-rating">
                            <span class="rating-percentage"
                                  style="width:<?php echo reasonableRound($review->overallRating) * 2 * 10 ?>%">●●●●●</span>○○○○○
                    </span> <a href="#show-details"
                               class="js-toggle details-toggle"><?php echo __('show-details', 'ca-reviews') ?></a><br>

                    <div class="review-summary hidden">
                        <div class="summary clearfix">
                            <?php foreach ($review->subcategoryRatings->subcategoryRating as $subcategory) { ?>
                                <div class="clearfix">
                                    <div class="category-column size1of5 unit"><?php echo $subcategory->category ?>:
                                    </div>
                                    <div class="review-column size1of5 unit">
                                        <span class="review-rating">
                                        <span class="rating-percentage"
                                              style="width:<?php echo reasonableRound($subcategory->rating) * 2 * 10 ?>%">
                                         ●●●●●
                                         </span>
                                         ○○○○○
                                         </span>
                                    </div>
                                    <?php if (strlen($subcategory->comment) > 0) { ?>
                                        <div class="size3of5 unit">
                                            <div class="comment-column"><?php echo $subcategory->comment ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <?php if (strlen($review->author) > 0) { ?>
                        <strong><?php echo __('review', 'ca-reviews') ?>,
                            <?php echo dateFormat($review->date) ?>,
                            <?php echo $review->author ?></strong>,
                        <?php echo __('age', 'ca-reviews') ?> <?php echo $review->reviewerAge ?>-<?php echo($review->reviewerAge + 10) ?> <?php echo __('years', 'ca-reviews') ?>
                    <?php } else { ?>
                        <strong><?php echo __('review', 'ca-reviews') ?>,
                            <?php echo dateFormat($review->date) ?></strong>,
                        <?php echo __('age', 'ca-reviews') ?> <?php echo $review->reviewerAge ?>-<?php echo($review->reviewerAge + 10) ?> <?php echo __('years', 'ca-reviews') ?>
                    <?php } ?>
                </div>
                <blockquote class="cite"><?php echo $review->overallComment ?></blockquote>
                <?php if (strlen($review->yourComment) > 0) { ?>
                    <p class="your-reply"><?php echo $review->yourComment ?></p>
                <?php } ?>
            </div>

        <?php } ?>
    </div>
    <?php if ($howManyReviews->reviews->review !== null && count($howManyReviews->reviews->children()) > 1) { ?>
        <div class="pagination">
            <?php echo __('page', 'ca-reviews') ?>: <?php echo renderPagination(count($howManyReviews->reviews->children()), $reviewPage) ?>
        </div>
    <?php } ?>
</div>

<script>
    jQuery(function ($) {
        $('a.js-toggle').on('click', function (e) {
            e.preventDefault();
            $(this)
                .parent()
                .find('div.review-summary')
                .toggle();
        });
    });
</script>