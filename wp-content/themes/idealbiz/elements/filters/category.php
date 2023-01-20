<div class="listing_cat--select border-blue b-t-l-r b-b-l-r">
    <select class="category-filter" data-placeholder="<?php _e('Category', 'idealbiz'); ?>" name="listing_cat">
        <option></option>
        <?php
        $terms = get_terms(
            array('taxonomy' => 'listing_cat', 'hide_empty' => false, 'parent' => 0) //change to true after
        );
        foreach ($terms as $term) {
            echo sprintf(
                '<option value="%1$d" %3$s>%2$s</option>',
                $term->term_id,
                $term->name,
                $_REQUEST['listing_cat'] == $term->term_id ? 'selected' : ''
            );
        }
        ?>
    </select>
</div>