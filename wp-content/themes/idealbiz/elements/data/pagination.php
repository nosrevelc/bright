<div class="d-none pagination-data">
    <?php
    $total_pages = $wp_query->max_num_pages;
    Component_Listings::pagination($total_pages, true);
    ?>
</div>