<?php
/**
 * Fetch global reviews, not used atm
 *
 * @package WordPress
 * @subpackage Customer Alliance Reviews
 * @since Customer Alliance Reviews 0.1.0
 */

require_once('helper-functions.php');

// feed with global statistics
$reviewsGlobal = getXML('https://api.customer-alliance.com/statistics?id=qkJ&access_key=&_locale=de');
$globalStatistics = $reviewsGlobal->globalStatistics;
$portalStatistics = $reviewsGlobal->portalStatistics;

?>

<div class="reviews review-overview" style="font-size: 12px">
    <div class="reviews-head" style="margin: 10px 0">
        Bewertungen: <?php echo $globalStatistics->reviewCount ?><br>
        Durchschnitt: <?php echo reasonableRound($globalStatistics->averageRating) . '/' . $globalStatistics->averageRating['scale'] ?>
        <br>
        Prozentualer Durchschnitt: <?php echo $globalStatistics->averageRatingPercentage ?><br>
        Auf <?php echo $globalStatistics->portalCount ?> Portalen vertreten!<br>
    </div>

    <div class="reviews-global" style="margin: 10px 0">
        <?php foreach ($globalStatistics->ratings->category as $rating) { ?>
            <?php //echo $rating->identifier ?>
            <div class="review-global" style="margin: 5px 0">
                <?php echo $rating->label ?>:
                <?php echo reasonableRound($rating->averageRating) . '/' . $rating->averageRating['scale'] ?>
                <br>
                <?php echo $rating->averageRatingPercentage ?>%
            </div>
        <?php } ?>
    </div>

    <div class="reviews-portal" style="margin: 10px 0">
        <?php foreach ($portalStatistics->portal as $portal) { ?>
            <?php //echo $portal->identifier ?>
            <div class="review-portal" style="margin: 5px 0">
                <?php echo $portal->name ?>:
                <?php echo reasonableRound($portal->averageRating) . '/' . $portal->averageRating['scale'] ?>
                <br>
                Anzahl Bewertungen: <?php echo $portal->reviewCount ?><br>
                Prozentualer Durchschnitt: <?php echo $portal->averageRatingPercentage ?>%
            </div>
        <?php } ?>
    </div>
</div>

<hr>