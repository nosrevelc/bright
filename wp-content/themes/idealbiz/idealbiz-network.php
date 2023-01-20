<?php
// Template Name: iDealBiz Netowrk

get_header();

$title = get_the_title();
?>
<div class="container m-b-25 md-m-b-50">

    <h1 class="listing-title text-center"><?php echo $title; ?></h1>

    <div class="listing-description m-t-30 m-b-50">
        <?php echo the_content(); ?>
    </div>
</div>


<?php get_footer(); ?>