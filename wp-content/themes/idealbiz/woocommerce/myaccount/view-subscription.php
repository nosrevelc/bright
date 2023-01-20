<?php
/**
 * View Subscription
 *
 * Shows the details of a particular subscription on the account page
 *
 * @author    Prospress
 * @package   WooCommerce_Subscription/Templates
 * @version   2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( empty( $subscription ) ) {
	global $wp;

	if ( ! isset( $wp->query_vars['view-subscription'] ) || 'shop_subscription' != get_post_type( absint( $wp->query_vars['view-subscription'] ) ) || ! current_user_can( 'view_order', absint( $wp->query_vars['view-subscription'] ) ) ) {
		echo '<div class="woocommerce-error">' . esc_html__( 'Invalid Subscription.', 'woocommerce-subscriptions' ) . ' <a href="' . esc_url( wc_get_page_permalink( 'myaccount' ) ) . '" class="wc-forward">'. esc_html__( 'My Account', 'woocommerce-subscriptions' ) .'</a>' . '</div>';
		return;
	}

	$subscription = wcs_get_subscription( $wp->query_vars['view-subscription'] );
}

wc_print_notices();
?>
<script>
    jQuery('.woocommerce-MyAccount-navigation-link--subscriptions').addClass('is-active');
</script>
<div class="block stroke dropshadow p-30 m-b-25 b-r-5 white--background">
    <h2 class="text-left m-b-15"><?php esc_html_e( 'Subscription Status', 'idealbiz' ); ?></h2>
    <table class="shop_table subscription_details m-b-0">
        <tr>
            <td><h3 class="font-weight-bold"><?php esc_html_e( 'Status', 'woocommerce-subscriptions' ); ?></h3></td>
            <td><h3 class="font-weight-bold"><?php echo esc_html( wcs_get_subscription_status_name( $subscription->get_status() ) ); ?></h3></td>
        </tr>
        <tr>
            <td><?php echo esc_html_x( 'Start Date', 'table heading',  'woocommerce-subscriptions' ); ?></td>
            <td><?php echo esc_html( $subscription->get_date_to_display( 'date_created' ) ); ?></td>
        </tr>
        <?php foreach ( array(
            'last_order_date_created' => _x( 'Last Order Date', 'admin subscription table header', 'woocommerce-subscriptions' ),
            'next_payment'            => _x( 'Next Payment Date', 'admin subscription table header', 'woocommerce-subscriptions' ),
            'end'                     => _x( 'End Date', 'table heading', 'woocommerce-subscriptions' ),
            'trial_end'               => _x( 'Trial End Date', 'admin subscription table header', 'woocommerce-subscriptions' ),
            ) as $date_type => $date_title ) : ?>
            <?php $date = $subscription->get_date( $date_type ); ?>
            <?php if ( ! empty( $date ) ) : ?>
                <tr>
                    <td><?php echo esc_html( $date_title ); ?></td>
                    <td><?php echo esc_html( $subscription->get_date_to_display( $date_type ) ); ?></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
        <?php do_action( 'woocommerce_subscription_before_actions', $subscription ); ?>
        <?php $actions = wcs_get_all_user_actions_for_subscription( $subscription, get_current_user_id() ); ?>
        <?php if ( ! empty( $actions ) ) : ?>
            <tr>
                <td><?php esc_html_e( 'Actions', 'woocommerce-subscriptions' ); ?></td>
                <td>
                    <?php foreach ( $actions as $key => $action ) : ?>
                        <a href="<?php echo esc_url( $action['url'] ); ?>" class="btn btn-blue blue--hover <?php echo sanitize_html_class( $key ) ?>"><?php echo esc_html( $action['name'] ); ?></a>
                    <?php endforeach; ?>
                </td>
            </tr>
        <?php endif; ?>
        <?php do_action( 'woocommerce_subscription_after_actions', $subscription ); ?>
    </table> 
</div>


<?php if ( $notes = $subscription->get_customer_order_notes() ) :
    ?>
    <div class="block stroke dropshadow p-30 m-b-25 b-r-5 white--background">
        <h2 class="text-left m-b-15"><?php esc_html_e( 'Subscription Updates', 'woocommerce-subscriptions' ); ?></h2>
        <ol class="commentlist notes">
            <?php foreach ( $notes as $note ) : ?>
            <li class="comment note">
                <div class="comment_container">
                    <div class="comment-text">
                        <p class="meta"><?php echo esc_html( date_i18n( _x( 'l jS \o\f F Y, h:ia', 'date on subscription updates list. Will be localized', 'woocommerce-subscriptions' ), wcs_date_to_time( $note->comment_date ) ) ); ?></p>
                        <div class="description">
                            <?php echo wp_kses_post( wpautop( wptexturize( $note->comment_content ) ) ); ?>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
            </li>
            <?php endforeach; ?>
        </ol>
    </div>    
<?php endif; ?>
<?php $allow_remove_item = wcs_can_items_be_removed( $subscription ); ?>

<div class="block stroke dropshadow p-30 m-b-25 b-r-5 white--background">
    <h2 class="text-left m-b-15"><?php esc_html_e( 'Subscription Order Details', 'idealbiz' ); ?></h2>
    <table class="shop_table order_details m-b-0">
        <thead>
            <tr>
                <?php if ( $allow_remove_item ) : ?>
                <th class="product-remove" style="width: 3em;">&nbsp;</th>
                <?php endif; ?>
                <th class="product-name"><?php echo esc_html_x( 'Product', 'table headings in notification email', 'woocommerce-subscriptions' ); ?></th>
                <th class="product-total"><?php echo esc_html_x( 'Total', 'table heading', 'woocommerce-subscriptions' ); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ( sizeof( $subscription_items = $subscription->get_items() ) > 0 ) {

                foreach ( $subscription_items as $item_id => $item ) {
                    $_product  = apply_filters( 'woocommerce_subscriptions_order_item_product', $subscription->get_product_from_item( $item ), $item );
                    if ( apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
                        ?>
                        <tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $subscription ) ); ?>">
                            <?php if ( $allow_remove_item ) : ?>
                                <td class="remove_item"><a href="<?php echo esc_url( WCS_Remove_Item::get_remove_url( $subscription->get_id(), $item_id ) );?>" class="remove" onclick="return confirm('<?php printf( esc_html__( 'Are you sure you want remove this item from your subscription?', 'woocommerce-subscriptions' ) ); ?>');">&times;</a></td>
                            <?php endif; ?>
                            <td class="product-name">
                                <?php
                                if ( $_product && ! $_product->is_visible() ) {
                                    echo esc_html( apply_filters( 'woocommerce_order_item_name', $item['name'], $item, false ) );
                                } else {
                                    echo wp_kses_post( apply_filters( 'woocommerce_order_item_name', sprintf( '<a data-t="%s">%s</a>', get_permalink( $item['product_id'] ), $item['name'] ), $item, false ) );
                                }

                                echo wp_kses_post( apply_filters( 'woocommerce_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf( '&times; %s', $item['qty'] ) . '</strong>', $item ) );

                                // Allow other plugins to add additional product information here
                                do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $subscription );

                                wcs_display_item_meta( $item, $subscription );

                                wcs_display_item_downloads( $item, $subscription );

                                // Allow other plugins to add additional product information here
                                do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $subscription );
                                ?>
                            </td>
                            <td class="product-total">
                                <?php echo wp_kses_post( $subscription->get_formatted_line_subtotal( $item ) ); ?>
                            </td>
                        </tr>
                        <?php
                    }

                    if ( $subscription->has_status( array( 'completed', 'processing' ) ) && ( $purchase_note = get_post_meta( $_product->id, '_purchase_note', true ) ) ) {
                        ?>
                        <tr class="product-purchase-note">
                            <td colspan="3"><?php echo wp_kses_post( wpautop( do_shortcode( $purchase_note ) ) ); ?></td>
                        </tr>
                        <?php
                    }
                }
            }
            ?>
        </tbody>
            <tfoot>
            <?php

            if ( $totals = $subscription->get_order_item_totals() ) {
                foreach ( $totals as $key => $total ) {
                    ?>
                <tr>
                    <th scope="row" <?php echo ( $allow_remove_item ) ? 'colspan="2"' : ''; ?>><?php echo esc_html( $total['label'] ); ?></th>
                    <td><?php echo wp_kses_post( $total['value'] ); ?></td>
                </tr>
                    <?php
                }
            } ?>
        </tfoot>
    </table>
</div>    




    <?php
    global $wpdb;
    
    $blis = getListsOfBroker();
    if($blis):
        ?>

        <div class="block stroke dropshadow p-30 m-b-25 b-r-5 white--background listing-page">
            <h2 class="text-left m-b-15"><?php esc_html_e( 'Listings of Current Broker Subscription', 'idealbiz' ); ?></h2>
            <div class="listings">

        <?php
    echo '<div class="row listings listings-container m-t-20">';
    
    // Check that we have query results.
    if ( $blis->have_posts() ) {
    
        // Start looping over the query results.
        while ( $blis->have_posts() ) {
    
            $blis->the_post();

         //setup_postdata($blis);

        echo '<div class="col col-md-3 text-left m-b-20">';
        get_template_part('/elements/listings'); 
     
        $opts_id_lang = pll_get_post( wc_get_page_id( 'shop' ), get_field('publish_in',get_the_ID())->slug );
        if(!$opts_id_lang){
            $opts_id_lang='en';
        }
        // edita a publicação e retorna ao my-listings
        echo '<div class="text-center m-t-0 m-b-5 d-flex flex-direction-row edit-opts">
                <a class="edit btn-blue" href="'.getLinkByTemplate('submit-listing.php').'?listing_id='.get_the_ID().'&edit=1">
                    '.__('Edit','idealbiz').'
                </a>';

        if(get_post_status()=='draft'){ // tem a publicação po publicar
            if(get_field('active_order',get_the_ID())!=''){ // comprou a publicação mas aguarda validação no BO
                echo '<a class="awating-publish btn-blue" href="#" onclick="return false;" style="cursor:default;">
                '.__('Waiting Validation','idealbiz').'
                </a>';
            }else{ // Criou publicação mas ainda nao comprou o plano
                echo '<a class="publish btn-blue" href="'.get_site_url().'/'.get_field('publish_in',get_the_ID())->slug.'/?p='.$opts_id_lang.'&ptype='.get_post_type($post->ID).'&id='.$post->ID.'">
                '.__('Publish','idealbiz').'
                </a>';
            }
        }else{ // já tem publicação comprada e aprovada
            if(get_post_type(get_the_ID())=='listing'){ //publicação é uma listing consegue fazer upgrade às opções
                echo '<a class="upgrade btn-blue" href="'.get_site_url().'/'.get_field('publish_in',get_the_ID())->slug.'/?p='.$opts_id_lang.'&ptype=upgrade&id='.$post->ID.'">
                    '.__('Upgrade','idealbiz').'
                    </a>';
            }

            // renova a publicação comprando novo plano
            echo '<a class="renew btn-blue" href="'.get_site_url().'/'.get_field('publish_in',get_the_ID())->slug.'/?p='.$opts_id_lang.'&ptype='.get_post_type($post->ID).'&id='.$post->ID.'&renew=1">
                '.__('Renew','idealbiz').'
                </a>';
        }
        if(get_field('listing_certification_status',get_the_ID()) == 'certification_not') { // compra certificação se for uma listing
            echo '<a class="certif btn-blue" href="'.get_site_url().'/'.get_field('publish_in',get_the_ID())->slug.'/?p='.$opts_id_lang.'&ptype=upgrade&id='.$post->ID.'&certify=1">
                '.__('Certify','idealbiz').'
                </a>';
        }
        echo '</div>';

        $expire_date = get_field('expire_date',get_the_ID());
        if($expire_date){
            echo '<div class="expire-date small d-block">
                <span class="pull-left">'.__('Expires:','idealbiz').' '.date('d-m-Y', strtotime($expire_date)).'</span>';
                if(get_field('listing_certification_status',get_the_ID()) == 'certification_ongoing') {
                    echo '<span class="pull-right">'.__('Ongoing Certification','idealbiz').'</span>';
                }
            echo '</div>';
        }
        echo '</div>';

        }

    }
    

    echo '</div></div>
    </div>';
    endif;
   
?>



<?php do_action( 'woocommerce_subscription_details_after_subscription_table', $subscription ); ?>

<?php wc_get_template( 'order/order-details-customer.php', array( 'order' => $subscription ) ); ?>

<div class="clear"></div>
