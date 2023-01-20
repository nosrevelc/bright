<div class="location--select border-blue m-l--1 b-t-r-r b-b-r-r">
    <select class="location-filter" data-placeholder="<?php _e('Location'); ?>" name="location">
        <option></option>
        <?php
        $terms = get_terms(
            array('taxonomy' => 'location', 'hide_empty' => false, 'parent' => 0) //change to true after
        );
        foreach ($terms as $term) {
            echo sprintf(
                '<option value="%1$d" %3$s>%2$s</option>',
                $term->term_id,
                $term->name,
                $_REQUEST['location'] == $term->term_id ? 'selected' : ''
            );
        }
        ?>
    </select>
</div>