<?php // Template Name: SubmitExpert

//Categorai de Menbro que o Form esta setado.
$member_category = get_field('member_category');
/* var_dump($member_category); */

if(!is_user_logged_in()){
    wp_redirect(home_url());
}

acf_form_head();

get_header();

?>
<?php

 if ( have_posts() ) : while ( have_posts() ) : the_post(); 
    $meta = get_post_meta( $post->ID, 'post_fields', true ); 
    
 ?>



<?php endwhile; endif; 


$new_or_edit ='new_post';

// Assuming you've got $author_id set
// and your post type is called 'your_post_type'

$id_product_fee=0;
$return_url='';
$apply_text='';
if(get_current_user_id()!=0){


$current_user_email = wp_get_current_user()->user_email;


$args = array(
    'post_type' => array ( 'expert' ),
    'post_status' => array('publish', 'pending', 'draft', 'trash') ,
    'meta_query' => array(
        array(
            'key' => 'expert_email',
            'value' => $current_user_email,
            'compare' => '='
        )
    ) ,  
    'posts_per_page' => -1 
);

$eposts = new WP_Query( $args );
$status = '';
if( $eposts->have_posts() ) {
    while( $eposts->have_posts()) { 
        $p = $eposts->the_post();
        $new_or_edit = get_the_ID();
        $status = get_post_status();
    }
    wp_reset_postdata();
}

if(WEBSITE_SYSTEM == '1' && 1==0){  // reunião dia 17/02/2021 decidiu-se que no BO tratam do envio da fatura para pagamento
    $params = array(
        'post_type' => 'product',
        'meta_query' => array(
            array('key' => '_product_type_meta_key', //meta key name here
                'value' => 'expert_fee', 
                'compare' => '=',
            )
        ),  
        'posts_per_page' => -1 
    );
    $wc_query = new WP_Query($params);
    global $post, $product;
    if( $wc_query->have_posts() ) {
    while( $wc_query->have_posts() ) {
            $wc_query->the_post();
            $id_product_fee=get_the_ID();
            $return_url=wc_get_checkout_url().'?add-to-cart='.$id_product_fee;
            if(userHasActiveExpertFeeSubscription()){
                $apply_text=pll__('Apply');
            }else{
                $apply_text=pll__('Apply and Pay Fee');
            }
            //the_title();
    } // end while
    } // end if
    wp_reset_postdata();
}



/*
$args = array(
    'author' => get_current_user_id(),
    'post_type' => array ( 'expert' ),
    'post_status' => array('publish', 'pending', 'draft', 'trash')    
);

$author_posts = new WP_Query( $args );
$status = '';
if( $author_posts->have_posts() ) {
    while( $author_posts->have_posts()) { 
        $p = $author_posts->the_post();
        $new_or_edit = get_the_ID();
        $status = get_post_status();
    }
    wp_reset_postdata();
}
*/
}

?>
<section class="page">
    <div class="container text-center m-t-30">
        <h1><?php the_title(); ?></h1>
        <br/>
         <?php the_content(); ?>
    </div>
    <?php //whiteBackground();
    if($status == 'publish'){
        echo '<div class="container text-center m-t-30"><h3>'.__('Congratulations! You are already an expert.','idealbiz').'</h3></div>';
    }
    if($status == 'draft' || $status == 'pending'){
        infoModal('<h3>'.__('Thank you for your interest. We will contact you shortly.','idealbiz').'</h3>', 'draft_post', 'd-none');
    ?>
        <script> 
            jQuery(document).ready(($) => {
                
                    setTimeout(function(){
                        if ($('#draft_post').length){
                        $('#draft_post').iziModal('open');
                        }
                    }, 500);
     
            });
        </script>   

    <?php
    }
    if($status == 'trash'){
        echo '<div class="container text-center m-t-30"><h3>'.__('Sorry but your application was declined.','idealbiz').'</h3></div>';
    }
    ?>
    <div class="container p-t-0">
    <div class="row">
		<div class="col-md-2">
		</div>
		<div class="col-md-8">
                <?php
                global $post;
                $post = $p;
                ?>
                <div class="row generic-form">
                    <?php

                    function my_acf_prepare_member( $field) {
                        $member_category = get_field('member_category');
                        $field['label'] = '';
                        $field['value'] = $member_category;
                        return $field;
                    }
                    add_filter('acf/prepare_field/name=member_category_store', 'my_acf_prepare_member');

                    function my_acf_prepare_field( $field ) {
                        $field['label'] = __("Full name","idealbiz");
                        return $field;
                    }
                    add_filter('acf/prepare_field/name=_post_title', 'my_acf_prepare_field');

                    function acf_set_featured_image( $value, $post_id, $field  ){
                        if($value != ''){
                            add_post_meta($post_id, '_thumbnail_id', $value);
                        }
                        return $value;
                    }
                    add_filter('acf/update_value/name=foto', 'acf_set_featured_image', 10, 3);

                    if($status == 'publish' || $status == 'trash'){
                        function acf_read_only($field) {
                            $field['readonly'] = 1;
                            return $field;
                        }
                        add_filter('acf/load_field', 'acf_read_only');
                    }

                    function my_acf_prepare_pitch( $field ) {
                        $member_category = get_field('member_category');
                        $field['label'] = __("Pitch","idealbiz");
                        $field['type']  	   = 'textarea';
                        $field['required'] 	   = 1;
                        $field['class']  	   = 'textarea';
                        $field['instructions'] = __('This summary will appear associated with your profile and can be viewed by the other Associated Consultants.','idealbiz');
                        return $field; 
                    }
                    add_filter('acf/prepare_field/name=pitch', 'my_acf_prepare_pitch');


                    /* function my_acf_prepare_iban( $field ) {
                        $field['instructions'] = __('O IBAN é confidencial e será utilizado para recebimentos por transferência bancária.','idealbiz');
                        return $field; 
                    }
                    add_filter('acf/prepare_field/name=iban', 'my_acf_prepare_iban'); */


/*
                    add_filter( 'acf/get_valid_field', 'change_form_fields_properties');
                    function change_form_fields_properties( $field ) {                                                            
                        if($field['type'] == 'wysiwyg') {
                            $field['type']  	   = 'textarea';
                            $field['label'] 	   = __('Pitch', 'idealbiz'); 
                            $field['required'] 	   = 1;
                            $field['class']  	   = 'textarea';
                            $field['instructions'] = __('This summary will appear associated with your profile and can be viewed by the other Associated Consultants.','idealbiz');
                        }               
                        return $field;                  
                    }
                    */
/*
                    function my_acf_prepare_email( $field ) {
                        $field['label'] = __("Email","idealbiz").'';
                        return $field;
                    }
                    add_filter('acf/prepare_field/name=expert_email', 'my_acf_prepare_email');
*/

                    function allowpitch_acf_prepare_field( $field ) {
                        $field['choices']=__('I authorize my Pitch to appear on the HomePage of the iDealBiz Experts network website and in the "Meet our Consultants" section.','idealbiz');
                        return $field;
                    }
                    add_filter('acf/prepare_field/name=allow_pitch', 'allowpitch_acf_prepare_field');


                    $fields = array(
                        'member_category_store',
                        'pitch',
                        'expert_phone',
                        'expert_email',
                        'cv',
                        'foto', 
                        'echelon_competency_factor',
                        'allow_pitch'
                        
                        
                    );
                    if($return_url==''){
                        $return_url= get_permalink().'?expert_id=%post_id%';
                        $apply_text =  pll__('Apply');
                    }
                    
                    acf_register_form(array(
                        'id'		    	=> 'expert',
                        'post_id'	    	=> $new_or_edit,
                        'new_post'			=> array(
                            'post_type'		=> 'expert',
                            'post_status'	=> 'draft'
                        ),
                        'post_title'		=> true,
                        'post_content'  	=> false,
                        
                        'uploader'      	=> 'basic',
                        'updated_message'	=> null,
                        'return'			=> $return_url,
                        'fields'			=> $fields,
                        
                        'submit_value'		=> $apply_text,
                        'html_after_fields' => '<div><input type="hidden" name="form_post_type" value="expert"></div>'
                    ));
                    // Load the form 
                    acf_form('expert');
                    ?>
                </div>
                <?php
                $url = get_bloginfo('url');
                if( current_user_can('editor') || current_user_can('administrator') || get_current_user_id()==get_post_field( 'post_author', $_GET['listing_id'] ) ){
                    if ( get_post_type( $_GET['listing_id'] ) == 'listing' ) {
                        echo '<a class="delete-post red--color m-r-15" href="'.get_delete_post_link( $_GET['listing_id'], true).'">'.__('Delete Application', 'idealbiz').'</a>';
                    }
                }
                ?> 
                <script>
                    jQuery(document).ready(($) => {
                      $('.acf-form-submit').prepend($('.delete-post'));
                        <?php 
                        $current_user = wp_get_current_user();
                        ?>
                        if(!$('.acf-field[data-name="expert_email"] input[type="email"]').val())
                            $('.acf-field[data-name="expert_email"] input[type="email"]').val('<?php echo $current_user->user_email; ?>');
                            
                        if($('.acf-field[data-name="expert_email"] input[type="email"]').val())    
                            $('.acf-field[data-name="expert_email"] input[type="email"]').attr('readonly', 'readonly');
                        });
                </script>
                <?php
                if($status == 'trash'){ ?>
                    <script>
                        jQuery(document).ready(($) => {
                            $('.acf-form-submit').remove();
                            $('.acf-icon.-cancel').remove();
                            $('.acf-field[data-name="allow_pitch"] input[type="checkbox"]').attr('readonly', 'readonly');
                        });
                    </script>    
                <?php } 
                
                
                
                ?>
                
           <!--  </form> -->
		</div>
		<div class="col-md-2">
		</div>
	</div>

    </div>
</section>

<?php
//echo '<pre>';
//var_dump($p);
//echo '</pre>';
?>

<?php get_footer(); ?>

<style>

#fgenmodal4{
    max-width: 800px !important;
}
#member_cat{
    display: none;
}

</style>