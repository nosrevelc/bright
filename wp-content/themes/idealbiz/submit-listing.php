<?php // Template Name: SubmitListing

if(!$_SESSION['cl_cacheLink']){
    if(!$_GET['add-to-cart']){
        if(!$_GET['id']){
        WC()->cart->empty_cart();
        }
    }
}

$params = array(
    'post_type' => 'product',
    'posts_per_page' => -1,
    'meta_query' => array(
        array(
            'key' => '_product_type_meta_key',
            'value' => 'listing_plan',
            'compare' => '='
        )
    ),
    'orderby'        => 'meta_value_num',
    'meta_key'       => '_price',
    'order'          => 'asc'
);
$wc_query = new WP_Query($params);
$broker = isBroker(NULL, pll_current_language());
$num_cols=0;


$cl_filtro_expert = get_field('filter_expert_on_submit_listing_page');


if (!is_user_logged_in()) {
    $redirect = ( strpos( $_SERVER['REQUEST_URI'], '/options.php' ) && wp_get_referer() ) ? wp_get_referer() : set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
    wp_redirect(get_site_url().'/'.pll_languages_list()[0].'/login-register/?redirect_to='.$redirect );
}

//OCULTA LINGUA OPÇÃO DE PUBLICAÇÃO EM TERMINADAS LINGUAS EM DETERMINADOS SITES.

switch ($blog_id) {
    case 86:
        $cl_ingles = 'display:none';
        $cl_campo = 516;
        break;
    case 395:
        $cl_ingles = 'display:none';
        $cl_campo = 547;
        break;
        default:
        $cl_ingles = '';
        $cl_campo = '';

}

acf_form_head();

get_header();


$cl_memberRecomemded = $_SESSION['cl_cacheLink'];
//NPMM - Aqui oculta ou mostra quadro com valores alterando classe ".page" COLOCAR "!" no isset para inverter e exibir.
if(isset($cl_memberRecomemded)){
    $cl_precario = 'none';
}else{
    $cl_precario = 'block';
}

/* var_dump($cl_memberRecomemded);
cl_alerta($cl_memberRecomemded); */

?>

<style>
li[data-id="<?php echo $cl_campo;?>"]{
    <?php echo $cl_ingles;?>
}
</style>

<?php


if (have_posts()) : while (have_posts()) : the_post();
        $meta = get_post_meta($post->ID, 'post_fields', true);

        ?>



<?php endwhile;
endif;

/*
$args = array(
    'post_type' => 'listing',
    'post_status' => 'draft',
    'posts_per_page' => 1,
    'author' => get_current_user_id()
);
$draftPost = get_posts($args);
$did='';
if (count($draftPost)) {
    //$did= $draftPost[0]->ID;
}

*/
$did = '';
$new_or_edit = 'new_post';
$broad = 'broadcast_post';

if (isset($_GET['listing_id'])) {
    $did = $_GET['listing_id'];
    $broad = '';
}
if ($did != '') {
    if (current_user_can('editor') || current_user_can('administrator') || get_current_user_id() == get_post_field('post_author', $did) || get_current_user_id() == get_field('owner', $did)['ID']) {
        if (get_post_type($did) == 'listing' && get_post_status($did) != 'trash') {
            $p = get_post($did);
            $new_or_edit = $did;
        }
    }
}
?>
<section class="page">



    <div class="container text-center">
        <h1><?php the_title(); ?></h1>
        <br /><br />
       
        <?php if (!$cl_memberRecomemded){?>
        <?php the_content(); ?>
        <?php } ?>
        <?php
        


        if(!$_GET['add-to-cart'] && !$broker){
            echo '<h2 class="cl_h2">'.__('_str First Select Value','idealbiz').'</h2>';
            include(MY_THEME_DIR.'includes/page-sell-business-inc.php');
        }

        ?>

    </div>



    <?php //whiteBackground(); 
    ?>

<?php
    global $woocommerce;
    $items = $woocommerce->cart->get_cart();
    foreach($items as $item => $values) { 
        $_product =  wc_get_product( $values['data']->get_id()); 
        $price = get_post_meta($values['product_id'] , '_price', true);                        
    } 
?>

    <div class="container p-t-40">

        <div class="row">
                            
                            </div>
                            <div class="col-md-10 col-xs-12 stretch-100">
                            <!--  NPMM - Aqui existe uma questão para enconder a Caixa de seleção de valore. -->
                            <!-- Está no Trelllo - https://trello.com/c/gqC83LqB -->
                            <?php if($price){?>
                            <?php /* if($_GET['add-to-cart'] || $broker){ */?>
                            <div class="col-md-7 cl_selecionado">
                            <h2 class="cl_h2"><i class="dashicons-before dashicons-yes-alt"></i><?php _e('srt_Selected plan','idealbiz');?></h2>                           
                            <?php                            
                            echo '<h2><i class="dashicons-before dashicons-arrow-right"></i>'.$_product->get_title().'<h2>'; 
                            echo '<h2><i class="dashicons-before dashicons-arrow-right"></i>'.__('_str Price','idealbiz').' '.get_woocommerce_currency_symbol().$price."</h2>";                                                      
                            ?>
                            <!-- BOTÃO TROCA DE PLANO -->
                            <?php if($_GET['add-to-cart']){?>
                            <button class="btn btn-blue m-t-10 font-btn-plus blue--hover" type="submit" value="Submit">
                            <a href="javascript: history.go(-1)"><i class="dashicons-before dashicons-undo"></i><span class="font-weight-bold m-l-5"><?php _e('_str Change Plan', 'idealbiz'); ?></span></a>
                            </button>
                            <?php } ?>
                            </div>
                            <?php } ?>
                     
                            <?php if($broker){?>
                            <div class="col-md-7 cl_selecionado">
                            <h2 class="cl_h2"><i class="dashicons-before dashicons-yes-alt"></i><?php _e('srt_Selected plan','idealbiz');?></h2>
                            <?php                 
                            echo '<h2><i class="dashicons-before dashicons-businessman"></i>'.__('_str Broker Plan','idealbiz').'</h2>';                                                      
                            ?>
                            </div>
                            <?php } ?>

                            <?php if($cl_memberRecomemded){?>
                            <div class="col-md-7 cl_selecionado">
                            <h2 class="cl_h2"><i class="dashicons-before dashicons-yes-alt"></i><?php _e('srt_Selected plan','idealbiz');?></h2>
                            <?php                 
                            echo '<h2><i class="dashicons-before dashicons-businessman"></i>'.__('_str Recomemded Business Plan','idealbiz').'</h2>';                                                      
                            ?>
                            </div>
                            <?php } ?>

                                <?php 
                            if($_GET['add-to-cart']){?>             
                <h1 class="text-left m-b-15 m-t-20">
                    <?php _e('Businness Details', 'idealbiz'); ?>
                </h1>
                <?php } ?>
                <?php
                global $post;
                $post = $p;
                ?>
<?php 

//NPMM - Adiciona produto valor zero no carrinho para Recomemded Business.
//NPMM - A variável "$cl_memberRecomemded" é definida em wp-content/themes/idealbiz/includes/page-sell-business-inc.php
if(isset($cl_memberRecomemded)){
    $rb_id_porduct_zero = get_field('rb_id_porduct_zero', 'option');
    do_action('auto_add_prod_cart',$rb_id_porduct_zero);
}




    if($_GET['add-to-cart'] || $broker || $cl_memberRecomemded){?>
            <div class="row generic-form">
                    <?php
                    function my_acf_render_experts( $fields ) { ?>
                        <div class="sidebar-listing-submission m-t-10">                   
                            <div id="contact-this-seller" class="form-content">
                                <div class="expert-field-message d-flex flex-wrap justify-content-between">
                                    <div class="acf-field acf-field-message acf-field-5e60b63f4306c2 h2 m-b-5 w-100"
                                        data-name="location_expert_business" data-type="message" data-key="field_5e60b63f4306c">
                                        <div class="acf-label m-b-36">
                                            <h2 style="font-size: 30px!important;color: #14307b;margin-top: 30px;"><?php esc_html_e('Choose your expert', 'idealbiz'); ?>
                                             <?php echo do_shortcode('[i]'.pll__('Select your Expert').'[/i]'); ?></h2>
                                        </div>
                                        <div class="acf-notice acf-error-message m-t-0 notice-expert-select w-">
                                            <p>
                                                <?php esc_html_e('If you choose the IDB / Idealbiz Expert, we will assign you the best expert to help you with your Listing', 'idealbiz'); ?>
                                            </p>
                                        </div>
                             
                                        <div class="acf-notice -error acf-error-message expert-selection"
                                            style="display: none;">
                                            <p>
                                                <?php esc_html_e('Please, select your Expert'); ?>
                                            </p>
                                        </div>
                                        <div class="acf-input">
                                        </div>
                                    </div>
                                </div>
                                <?php //experts

                                //INICIO CHECA MODO LEAD DO USER PARA SELECIONAR ELE COMO CONTACTO
                                $current_user = wp_get_current_user();
                                
                                $id_expert = isExpert($current_user->ID); 
                                $check_member = get_field('rb_member_of_recommended_business',$id_expert[0]->ID);// boleano true ou false
                                $cl_expertDsplayName = $expert[0]->post_title;
                                $cl_rb_pay_lead_mode = get_field('rb_pay_lead_mode',$id_expert[0]->ID);

                                if($cl_rb_pay_lead_mode === NULL){
                                    $cl_rb_pay_lead_mode = ['value'=>'rb_pay_before','label'=>'Pay Before'];

                                }
                                $cl_rb_pay_lead_mode_value = $cl_rb_pay_lead_mode['value'];

                                //FIM CHECA MODO LEAD DO USER PARA SELECIONAR ELE COMO CONTACTO





                                $cl_filtro_expert = get_field('filter_expert_on_submit_listing_page');
                                if($cl_filtro_expert){    
                                    if ($check_member != true){
                                        $post_args = array(
                                            'posts_per_page'=> -1,
                                            'post_type'     => 'expert',
                                            'post_status'   => 'publish', 
                                            'orderby' => 'post_title',
                                            'order'   => 'ASC',
                                            'tax_query' => array(
                                                array(
                                                    'taxonomy' => 'service_cat',        // taxonomy name
                                                    'field' => 'cat',           // term_id, slug or name
                                                    'terms' => $cl_filtro_expert // term id, term slug or term name
                                                )
                                                )     
                                        );
                                    }else{
                                        
                                        $post_args = array(
                                            'posts_per_page'=> -1,
                                            'post_type'     => 'expert',
                                            'post_status'   => 'publish',
                                            'p' =>$id_expert[0]->ID,
                                        );
                                    }

                                }else{
                                    $post_args = array(
                                        'posts_per_page'=> -1,
                                        'post_type'     => 'expert',
                                        'post_status'   => 'publish',
                                        'orderby' => 'post_title',
                                        'order'   => 'ASC',
                                    );   
                                }

                                
                                    $p = '';
                                    $myposts = get_posts($post_args);
                                    /* var_dump($myposts); */
                                    if ($myposts) {
                                        $i=0;
                                        foreach ($myposts as $post) {

                                            $act_class='';
                                            if($i==0){
                                                $act_class=' default_expert_selection';
                                            }

                                            $term_obj_list = get_the_terms($post->ID, 'service_cat');
                                            $classes = '';
                                            foreach ($term_obj_list as $t) {
                                                $classes .= ' service_cat_' . $t->term_id;
                                            }
                                            $location_objs = get_the_terms($post->ID, 'location');
                                            $location_as_classes = '';
                                            foreach ($location_objs as $l) {
                                                $location_as_classes .= ' location_' . $l->term_id;
                                            } 

                                            $pcontent = get_field('pitch', $post->ID); ?>
                                            <div id="modal_<?php echo $post->ID; ?>" class="iziModal expert-details-modal">
                                                <div class="content p-x-20">
                                                    <button data-izimodal-close="" class="icon-close"></button>
                                                    <h1 class="text-center m-b-20"><?php echo get_the_title($post->ID); ?></h1>
                                                    <div class="accordion" id="accordion_<?php echo $post->ID; ?>">
                                                        <div class="card">
                                                            <div class="card-header" id="heading_pitch_<?php echo $post->ID; ?>">
                                                                <h3 class="mb-0">
                                                                    <a class="btn btn-link" data-toggle="collapse" data-target="#pitch_<?php echo $post->ID; ?>"
                                                                        aria-expanded="true" aria-controls="pitch_<?php echo $post->ID; ?>">
                                                                        <?php echo _e('Pitch', 'idealbiz'); ?>
                                                                    </a>
                                                                </h3>
                                                            </div>
                                            
                                                            <div id="pitch_<?php echo $post->ID; ?>" class="collapse show"
                                                                aria-labelledby="heading_pitch_<?php echo $post->ID; ?>"
                                                                data-parent="#accordion_<?php echo $post->ID; ?>">
                                                                <div class="card-body">
                                                                    <?php echo $pcontent; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                            
                                                        <?php if ($expert_schedule_available = get_field('expert_schedule_available', $post->ID)) : ?>
                                            
                                                        <div class="card">
                                                            <div class="card-header" id="heading_availability_<?php echo $post->ID; ?>">
                                                                <h3 class="mb-0">
                                                                    <a class="btn btn-link" data-toggle="collapse"
                                                                        data-target="#availability_<?php echo $post->ID; ?>" aria-expanded="false"
                                                                        aria-controls="availability_<?php echo $post->ID; ?>">
                                                                        <?php _e('Availability', 'idealbiz'); ?>
                                                                    </a>
                                                                </h3>
                                                            </div>
                                            
                                                            <div id="availability_<?php echo $post->ID; ?>" class="collapse"
                                                                aria-labelledby="heading_availability_<?php echo $post->ID; ?>"
                                                                data-parent="#accordion_<?php echo $post->ID; ?>">
                                                                <div class="card-body">
                                                                    <?php echo $expert_schedule_available; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                            
                                                        <?php endif; ?>
                                            
                                                        <?php
                                            
                                                                                        $expert_looking_for_projects_on = get_post_meta( $post->ID, 'expert_looking_for_projects_on' )[0];
                                                                                        $expert_has_expertise = get_post_meta( $post->ID, 'expert_has_expertise' )[0];
                                                                                        if ($expert_looking_for_projects_on || $expert_has_expertise) : 
                                                                                        ?>
                                            
                                                        <div class="card">
                                                            <div class="card-header" id="heading_business_sectors_<?php echo $post->ID; ?>">
                                                                <h3 class="mb-0">
                                                                    <a class="btn btn-link" data-toggle="collapse"
                                                                        data-target="#business_sectors_<?php echo $post->ID; ?>" aria-expanded="false"
                                                                        aria-controls="business_sectors_<?php echo $post->ID; ?>">
                                                                        <?php _e('Business Sectors and Expertise Areas', 'idealbiz'); ?>
                                                                    </a>
                                                                </h3>
                                                            </div>
                                            
                                                            <div id="business_sectors_<?php echo $post->ID; ?>" class="collapse"
                                                                aria-labelledby="heading_business_sectors_<?php echo $post->ID; ?>"
                                                                data-parent="#accordion_<?php echo $post->ID; ?>">
                                                                <div class="card-body">
                                                                    <?php if ($expert_looking_for_projects_on) : ?>
                                                                    <h4><?php _e('Looking for Projects on:', 'idealbiz'); ?></h4>
                                                                    <div class="d-flex flex-column m-b-10">
                                                                        <?php 
                                                                                                foreach ($expert_looking_for_projects_on as $term) : 
                                                                                                ?>
                                                                        <span><?php echo get_term_by( 'id', $term, 'service_cat' )->name; ?></span>
                                                                        <?php endforeach; ?>
                                                                    </div>
                                                                    <?php endif; ?>
                                                                    <?php if ($expert_has_expertise) : ?>
                                                                    <h4><?php _e('Have expertise on the following areas:', 'idealbiz'); ?></h4>
                                                                    <div class="d-flex flex-column">
                                                                        <?php foreach ($expert_has_expertise as $term) : ?>
                                                                        <span><?php echo get_term_by( 'id', $term, 'service_cat' )->name; ?></span>
                                                                        <?php endforeach; ?>
                                                                    </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                            
                                                        <?php endif; ?>
                                            
                                                        <?php if ($experts_professional_experience = get_field('experts_professional_experience', $post->ID)) : ?>
                                            
                                                        <div class="card">
                                                            <div class="card-header" id="heading_professional_experience_<?php echo $post->ID; ?>">
                                                                <h3 class="mb-0">
                                                                    <a class="btn btn-link" data-toggle="collapse"
                                                                        data-target="#professional_experience_<?php echo $post->ID; ?>" aria-expanded="false"
                                                                        aria-controls="professional_experience_<?php echo $post->ID; ?>">
                                                                        <?php _e('Professional Experience', 'idealbiz'); ?>
                                                                    </a>
                                                                </h3>
                                                            </div>
                                            
                                                            <div id="professional_experience_<?php echo $post->ID; ?>" class="collapse"
                                                                aria-labelledby="heading_professional_experience_<?php echo $post->ID; ?>"
                                                                data-parent="#accordion_<?php echo $post->ID; ?>">
                                                                <div class="card-body">
                                                                    <?php $i = 1; ?>
                                                                    <?php foreach ($experts_professional_experience as $experience) : ?>
                                                                    <h4 class="<?php echo $i > 1 ? 'm-t-10' : ''; ?>"><strong><?php echo $experience['company']; ?>
                                                                            - </strong></h4>
                                                                    <?php if ($experience['start_date'] || $experience['end_date']) : ?>
                                                                    <div class="d-flex flex-row m-b-5">
                                                                        <strong><?php _e('Duration:', 'idealbiz'); ?>&nbsp;</strong>
                                                                        <span><?php echo $experience['start_date']; ?> -
                                                                            <?php echo $experience['end_date']; ?></span>
                                                                    </div>
                                                                    <?php endif; ?>
                                            
                                                                    <?php if ($experience['field']) : ?>
                                                                    <div class="d-flex flex-row m-b-5">
                                                                        <strong><?php _e('Field:', 'idealbiz'); ?>&nbsp;</strong>
                                                                        <span><?php echo $experience['field']->name; ?></span>
                                                                    </div>
                                                                    <?php endif; ?>
                                            
                                                                    <?php if ($experience['role']) : ?>
                                                                    <div class="d-flex flex-row m-b-5">
                                                                        <strong><?php _e('Role:', 'idealbiz'); ?>&nbsp;</strong>
                                                                        <span><?php echo $experience['role']; ?></span>
                                                                    </div>
                                                                    <?php endif; ?>
                                            
                                                                    <?php if ($experience['description']) : ?>
                                                                    <div class="d-flex flex-row m-b-5">
                                                                        <strong><?php _e('Description:', 'idealbiz'); ?>&nbsp;</strong>
                                                                        <span><?php echo $experience['description']; ?></span>
                                                                    </div>
                                                                    <?php endif; ?>
                                            
                                                                    <?php $i++; ?>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                            
                                                        <?php endif; ?>
                                            
                                                        <?php if ($experts_studies = get_field('experts_studies', $post->ID)) : ?>
                                            
                                                        <div class="card">
                                                            <div class="card-header" id="heading_studies_<?php echo $post->ID; ?>">
                                                                <h3 class="mb-0">
                                                                    <a class="btn btn-link" data-toggle="collapse" data-target="#studies_<?php echo $post->ID; ?>"
                                                                        aria-expanded="false" aria-controls="studies_<?php echo $post->ID; ?>">
                                                                        <?php _e('Studies', 'idealbiz'); ?>
                                                                    </a>
                                                                </h3>
                                                            </div>
                                            
                                                            <div id="studies_<?php echo $post->ID; ?>" class="collapse"
                                                                aria-labelledby="heading_studies_<?php echo $post->ID; ?>"
                                                                data-parent="#accordion_<?php echo $post->ID; ?>">
                                                                <div class="card-body">
                                                                    <?php $i = 1; ?>
                                                                    <?php foreach ($experts_studies as $study) : ?>
                                                                    <h4 class="<?php echo $i > 1 ? 'm-t-10' : ''; ?>"><strong><?php echo $study['course']; ?> -
                                                                        </strong></h4>
                                                                    <?php if ($study['start_date'] || $study['end_date']) : ?>
                                                                    <div class="d-flex flex-row m-b-5">
                                                                        <strong><?php _e('Duration:', 'idealbiz'); ?>&nbsp;</strong>
                                                                        <span><?php echo $study['start_date']; ?> - <?php echo $study['end_date']; ?></span>
                                                                    </div>
                                                                    <?php endif; ?>
                                            
                                                                    <?php if ($study['school']) : ?>
                                                                    <div class="d-flex flex-row m-b-5">
                                                                        <strong><?php _e('School:', 'idealbiz'); ?>&nbsp;</strong>
                                                                        <span><?php echo $study['school']; ?></span>
                                                                    </div>
                                                                    <?php endif; ?>
                                            
                                                                    <?php $i++; ?>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                            
                                                        <?php endif; ?>
                                                        <?php
                                                                                        $mother_tongue = get_field('expert_mother_tongue', $post->ID);
                                                                                        $other_languages = get_field('other_languages', $post->ID);
                                                                                        ?>
                                            
                                                        <?php if ($mother_tongue || $other_languages) : ?>
                                            
                                                        <div class="card">
                                                            <div class="card-header" id="heading_languages_<?php echo $post->ID; ?>">
                                                                <h3 class="mb-0">
                                                                    <a class="btn btn-link" data-toggle="collapse" data-target="#languages_<?php echo $post->ID; ?>"
                                                                        aria-expanded="false" aria-controls="languages_<?php echo $post->ID; ?>">
                                                                        <?php _e('Languages', 'idealbiz'); ?>
                                                                    </a>
                                                                </h3>
                                                            </div>
                                            
                                                            <div id="languages_<?php echo $post->ID; ?>" class="collapse"
                                                                aria-labelledby="heading_languages_<?php echo $post->ID; ?>"
                                                                data-parent="#accordion_<?php echo $post->ID; ?>">
                                                                <div class="card-body">
                                                                    <?php if ($mother_tongue) : ?>
                                                                    <h4><?php _e('Mother tongue:', 'idealbiz'); ?></h4>
                                                                    <div class="d-flex flex-column m-b-10">
                                                                        <strong><span><?php echo $mother_tongue; ?></span></strong>
                                                                    </div>

                                                                    <?php endif; ?>
                                                                    <?php if ($other_languages) : ?>
                                                                    <h4><?php _e('Other languages:', 'idealbiz'); ?></h4>
                                                                    <div class="d-flex flex-column">
                                                                        <?php foreach ($other_languages as $other_language) : ?>
                                                                        <div><strong><?php echo $other_language['language']; ?> -
                                                                            </strong><span><?php _e($other_language['level'], 'idealbiz'); ?></span></div>
                                                                        <?php endforeach; ?>
                                                                    </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                            
                                                        <?php endif; ?>
                                            
                                                        <?php if ($expert_projects = get_field('expert_projects', $post->ID)) : 
                                                                            $showprojects=0;
                                                                            foreach ($expert_projects as $project) :
                                                                                if($project['project']!=''){
                                                                                    $showprojects=1;
                                                                                }
                                                                            endforeach;
                                                                            if($showprojects){
                                                                            ?>
                                                        <div class="card">
                                                            <div class="card-header" id="heading_other_projects_<?php echo $post->ID; ?>">
                                                                <h3 class="mb-0">
                                                                    <a class="btn btn-link" data-toggle="collapse"
                                                                        data-target="#other_projects_<?php echo $post->ID; ?>" aria-expanded="false"
                                                                        aria-controls="other_projects_<?php echo $post->ID; ?>">
                                                                        <?php _e('Other Projects', 'idealbiz'); ?>
                                                                    </a>
                                                                </h3>
                                                            </div>
                                            
                                                            <div id="other_projects_<?php echo $post->ID; ?>" class="collapse"
                                                                aria-labelledby="heading_other_projects_<?php echo $post->ID; ?>"
                                                                data-parent="#accordion_<?php echo $post->ID; ?>">
                                                                <div class="card-body">
                                                                    <?php $i = 1; ?>
                                                                    <?php foreach ($expert_projects as $project) : ?>
                                                                    <h4 class="<?php echo $i > 1 ? 'm-t-10' : ''; ?>"><strong><?php echo $project['project']; ?> -
                                                                        </strong></h4>
                                                                    <?php if ($project['start_date'] || $project['end_date']) : ?>
                                                                    <div class="d-flex flex-row m-b-5">
                                                                        <strong><?php _e('Duration:', 'idealbiz'); ?>&nbsp;</strong>
                                                                        <span><?php echo $project['start_date']; ?> - <?php echo $project['end_date']; ?></span>
                                                                    </div>
                                                                    <?php endif; ?>
                                            
                                                                    <?php if ($project['small_description']) : ?>
                                                                    <div class="d-flex flex-row m-b-5">
                                                                        <strong><?php _e('Small Description:', 'idealbiz'); ?>&nbsp;</strong>
                                                                        <span><?php echo $project['small_description']; ?></span>
                                                                    </div>
                                                                    <?php endif; ?>
                                            
                                                                    <?php $i++; ?>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php 
                                                                            }
                                                                        endif; ?>
                                            
                                                        <?php 
                                                                            if ($expert_it_knowledge = get_field('expert_it_knowledge', $post->ID)) : 
                                                                                $showit=0;
                                                                                foreach ($expert_it_knowledge as $it_knowledge) :
                                                                                    if($it_knowledge['name']!=''){
                                                                                        $showit=1;
                                                                                    }
                                                                                endforeach;
                                                                                if($showit){ ?>
                                                        <div class="card">
                                                            <div class="card-header" id="heading_it_knowledge_<?php echo $post->ID; ?>">
                                                                <h3 class="mb-0">
                                                                    <a class="btn btn-link" data-toggle="collapse"
                                                                        data-target="#it_knowledge_<?php echo $post->ID; ?>" aria-expanded="false"
                                                                        aria-controls="it_knowledge_<?php echo $post->ID; ?>">
                                                                        <?php _e('IT Knowledge', 'idealbiz'); ?>
                                                                    </a>
                                                                </h3>
                                                            </div>
                                                            <div id="it_knowledge_<?php echo $post->ID; ?>" class="collapse"
                                                                aria-labelledby="heading_it_knowledge_<?php echo $post->ID; ?>"
                                                                data-parent="#accordion_<?php echo $post->ID; ?>">
                                                                <div class="card-body">
                                                                    <div class="d-flex flex-column">
                                                                        <?php foreach ($expert_it_knowledge as $it_knowledge) : ?>
                                                                        <div><strong><?php echo $it_knowledge['name']; ?> -
                                                                            </strong><span><?php _e($it_knowledge['level'], 'idealbiz'); ?></span></div>
                                                                        <?php endforeach; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                                                } 
                                                                            endif; 
                                                                        ?>
                                                    </div>
                                            
                                                </div>
                                            </div> 
                                            
                                <?php
                                            

                                            $p .= '<div class="swiper-slide p-5 '.$act_class.' '.$location_as_classes.'" style="height:auto;"><div style="top: 10px;align-items: center;display: flex;justify-content: center;" data-expert="' . $post->ID . '" class="p-20 m-b-20 ' . $classes . ' expert-card position-relative flex-column black--color white--background dropshadow font-weight-medium  w-100"  data-locations="' . join(',', wp_list_pluck($location_objs, 'slug')) . '">';
                                            $p .= '<div class="d-flex flex-row center-content w-100" style="height:auto;">';
                                            $p .= '<div class="w-100px h-100px  d-block o-hidden no-decoration">';
                                            $p .= '<img class="object-cover" src="' . get_field('foto', $post->ID)['sizes']['medium'] . '">';
                                            $p .= '</div>';
                                            $p .= '<div class="calc-100-120 h-100 d-flex justify-content-between flex-column p-y-10 p-x-17">';
                                            $p .= '<div>'; 
                                            $p .= '<h3 class="font-weight-semi-bold base_color--color">' . get_the_title($post->ID) . '</h3> ';
                                            $p .= '</div>';
                                            $p .= '<span class="">' . join(', ', wp_list_pluck($term_obj_list, 'name')) . '</span>';
                                            $p .= '' . ($location_objs ? '<span class=" location p-t-10 font-weight-bold"><i class="icon-local"></i><span class="text-uppercase">' . join(', ', wp_list_pluck($location_objs, 'name')) . '</span></span>' : '') . '';
                                            $p .= '</div>';
                                            $p .= '<a href="#" data-izimodal-open="#modal_' . $post->ID . '" class="info-balloon info-modal">i</a>' . '</div>';
                                            $p .= '</div></div>';
                                            $i++;
                                        }
                                    }
                                ?>
                              
                              <div>
                                        <?php 
                                            if ($check_member != true){
                                        ?>    
                                                    <div class="p-x-30 w-100">
                                                        <div class="experts-slider row col-xs-12 justify-content-center p-relative">
                                                            <div class="expert-preview m-t-0 swiper-container">
                                                                <div class="swiper-wrapper">
                                                                    <?php echo $p; ?>
                                                                </div>
                                                            </div>
                                                            <div class="swiper-button-next"></div>
                                                            <div class="swiper-button-prev"></div>                                
                                                    </div>
                                              
                                                <?php
                                                }else{
                                                ?>    
                                                    <div class="p-x-30 w-100">
                                                        <div class="experts-slider row justify-content-center p-relative">
                                                            <div class=" expert-preview m-t-0 swiper-container">
                                                                <div class="cl_cartaoMembro swiper-wrapper">
                                                                    <?php echo $p; ?>
                                                                </div>
                                                            </div>                          
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                            
                             </div>

  
                                
                                <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

                                <script>
                                jQuery(document).ready(($) => {

                                    var swiper = undefined;
                                    function initSwiper(){
                                        swiper = new Swiper('.experts-slider .swiper-container', {
                                            loop: false,
                                            autoplay: false,
                                            navigation: {
                                            nextEl: '.swiper-button-next',
                                            prevEl: '.swiper-button-prev',
                                            },
                                            slidesPerView: 1,
                                            spaceBetween: 10,
                                            grabCursor: true,
                                            // init: false,
                                            breakpoints: {
                                            300: {
                                                slidesPerView: 1
                                            },
                                            600: {
                                                slidesPerView: 3
                                            }
                                            }
                                        });
                                    }

                                    $('.expert-card').on('click', function() {
                                        $('.expert-card').each(function() {
                                            $(this).removeClass('active');
                                        });
                                        $(this).addClass('active');

                                        var expert_id = $(this).data('expert');
                                        $('[data-name="expert"] select').find('option')
                                            .remove()
                                            .end()
                                            .append('<option value="' + expert_id +'">My Expert</option>')
                                            .val(expert_id);
                                        
                                        $('.notice-expert-select').fadeOut();
                                    });
                                    $('[data-name="location"] select').on('change', function() {
                                        swiper.destroy();
                                        $(".location_" + $(this).val()).insertAfter(".default_expert_selection");
                                        initSwiper();
                                    });

                                    $('form#listing').submit(function(e){
                                        var is_selected = 0;
                                        $('.expert-preview .expert-card').each(function() {
                                            if ($(this).hasClass("active")) {
                                                is_selected = 1;
                                            }
                                        });
                                        if (is_selected == 0) {
                                            $('.expert-selection').css('display', 'block');
                                            e.preventDefault();
                                            e.stopPropagation();
                                            $("body,html").animate(
                                                {
                                                    scrollTop: ($(".expert-selection").offset().top-250)
                                                },
                                                800
                                            );
                                            return false;
                                        }
                                    });
                                    $('.default_expert_selection .expert-card').click();
                                    initSwiper();
                                });
                                </script>
                                <style>
                                [data-name="expert"] {
                                    display: none;
                                }
                                </style>
                            </div>
                        </div>
                    <?php
                    }
                    add_action('acf/render_field/name=broadcast_post', 'my_acf_render_experts');

                    add_filter('acf/get_valid_field', 'change_form_fields_properties');
                    function change_form_fields_properties($field)
                    {
                        if ($field['type'] == 'wysiwyg') {
                            $field['type']         = 'textarea';
                            $field['label']        = __('Summary', 'idealbiz');
                            $field['required']        = 1;
                            $field['class']         = 'textarea';
                        }
                        
                        return $field;
                    }

                    function my_acf_prepare_title( $field ) {
                        $field['instructions'] = pll__('Insert the title of the listing');
                        return $field; 
                    }
                    add_filter('acf/prepare_field/name=_post_title', 'my_acf_prepare_title');

                    function my_acf_prepare_content( $field ) {
                        $field['instructions'] = pll__('Insert the description of the listing');
                        return $field; 
                    }
                    add_filter('acf/prepare_field/name=_post_content', 'my_acf_prepare_content');

                    function my_acf_prepare_featured_image( $field ) {
                        $field['instructions'] = pll__('Insert the image of the listing');
                        return $field; 
                    }
                    add_filter('acf/prepare_field/name=featured_image', 'my_acf_prepare_featured_image');


                    function my_acf_prepare_business_type( $field ) {
                        $field['message'] = pll__('Please choose the most relevant category for your business. Our team will review your business later and provide support if you think there is a more suitable one!');
                        return $field; 
                    }
                    add_filter('acf/prepare_field/name=business_type', 'my_acf_prepare_business_type');

                    
                    function my_acf_prepare_publish_in( $field ) {
                        //var_dump($field);
                        $field['instructions'] = pll__('Attention: iDealBiz.io is a bilingual website. When submitting your ad, if you are operating in the Portuguese language select to publish in the same language.');
                        return $field; 
                    }
                    add_filter('acf/prepare_field/name=publish_in', 'my_acf_prepare_publish_in');

                    


                    function acf_load_hidden_name($value, $post_id, $field)
                    {

                        return get_current_user_id();
                    }
                    add_filter('acf/load_value/name=owner', 'acf_load_hidden_name', 10, 3);

                    //need to find the field key in order to target it whenever it loads
                    add_filter('acf/prepare_field/name=broadcast_post', 'populate_with_ib_sites');
                    function populate_with_ib_sites($field)
                    {
                        // reset choices 
                        $field['choices'] = array();
                        $field['checke'] = '';
                        $cid = get_current_blog_id();
                        //CLEVERSON
                        $campo['choise'] = array();
                        $campo['checke'] = '';

                        if (function_exists('get_sites') && class_exists('WP_Site_Query')) {
                            $sites = get_sites();
                            foreach ($sites as $site) {
                                
                                $blog_details = get_blog_details(array('blog_id' => $site->blog_id));

                                //Utilizado para selecionar os site que não começas com iDealBiz
                                $nomeblog = $blog_details->blogname; 
                                //Utilizado para selecionar os sites que estão não deletados. 
                                $ativado = $blog_details -> archived;  
                                // Condição que seleciona os sites que vão aparecer 
                                //OBRIGATORIO Site começar com iDealBiz + Sufixo do Pais.
                                if (strpos($nomeblog,"iDealBiz") === 0 && $ativado === "0" && $blog_details->blog_id != 382){
                                    $current_blog_details = get_blog_details(array('blog_id' => $site->blog_id));                            
                                    $campo['choise'][$site->id_do_blog] = $current_blog_details->nomeblog; 
                                    //Consição que preenche o return que irá exibis os camopos selcionados.
                                    if ($cid != $site->blog_id && $site->blog_id != 1){
                                        $field['choices'][$site->blog_id] = $current_blog_details->blogname;
                                    }
                                }    
                            }

                            
                        }
                        return $field;
                    }

                    //NPMM - Campos de comissão só aparecem para Membros de Recomendação de Negócios.
                    $current_user = wp_get_current_user();
                    $cl_userAtual = isExpert($current_user->ID)[0]->ID; 
                    $cl_rb_member_of_recommended_business = get_field('rb_member_of_recommended_business', $cl_userAtual);
                    if ($cl_rb_member_of_recommended_business == true) {

                    $fields = array(
                        'type_of_listing',
                        'featured_image',
                        'gallery',
                        'galeria',
                        'cover_video',
                        /*'let_our_staff_create_your_ad',*/
                        /* 'business_type', */
                        'category',
                        /* 'location_business', */
                        'location',
                        'price_type',
                        'price_manual',
                        'rb_hidden_price_in_front_office',
                        'commission_by_recommendation_field',
                        'monetary_value_field',
                        'percentage_value_field',
                        'rb_post_in_recomemded_business',
                        'publish_in',
                        'owner',
                        'expert',
                        $broad
                    );
                }else{
                    $fields = array(
                        'type_of_listing',
                        'featured_image',
                        'gallery',
                        'galeria',
                        'cover_video',
                        /*'let_our_staff_create_your_ad',*/
                        /* 'business_type', */
                        'category',
                        /* 'location_business', */
                        'location',
                        'price_type',
                        /* 'price_range', */
                        'price_manual',
                        'publish_in',
                        'owner',
                        'expert',
                        $broad
                    ); 
                }


                    $returnurl = get_permalink(wc_get_page_id('shop')) . '/?ptype=listing&id=%post_id%';
                    if (isset($_GET['edit'])) {
                        $returnurl = wc_get_endpoint_url('mylistings', '', get_permalink(get_option('woocommerce_myaccount_page_id'))) . '?edited=%post_id%';
                    }

                    acf_register_form(array(
                        'id'               => 'listing',
                        'post_id'          => $new_or_edit,
                        'new_post'         => array(
                            'post_type'    => 'listing',
                            'post_status'  => 'draft' 
                        ),
                        'post_title'       => true,
                        'post_content'     => true,
                        'uploader'         => 'basic',
                        'updated_message'  => null,
                        'return'           => $returnurl,
                        'fields'           => $fields,
                        'submit_value'     => __('str_Submit Listing', 'idealbiz')
                    ));
                    // Load the form 
                    acf_form('listing');
                    ?>
            </div>
                <div style="display:none;" id="returnurl"
                    data-href="<?php echo wc_get_endpoint_url('mylistings', '', get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>">
                </div>
                <?php
                $url = get_bloginfo('url');
                if (current_user_can('editor') || current_user_can('administrator') || get_current_user_id() == get_post_field('post_author', $did) || get_current_user_id() == get_field('owner', $did)['ID']) {
                    if (get_post_type($did) == 'listing') {
                        echo '<a class="delete-post red--color m-r-15" href="#" onclick="redirect(\'' . wc_get_endpoint_url('mylistings', '', get_permalink(get_option('woocommerce_myaccount_page_id'))) . '?dpost=' . $did . '\');">' . __('Delete Listing', 'idealbiz') . '</a>';
                    }
                }
                ?>
                <script>
                function redirect(url) {
                    if (confirm('<?php _e('Are you sure you want to delete your post?', 'idealbiz'); ?>')) {
                        window.location.href = url;
                    }
                    return false;
                }
                jQuery(document).ready(($) => {
                    $('.acf-form-submit').prepend($('.delete-post'));

                    var selected_sites = [
                        <?php echo '"' . implode('","', get_field("broadcast_post")) . '"' ?>
                    ];
                    $('div[data-name="broadcast_post"]').find('input').each(function() {
                        if (!selected_sites.includes($(this).val())) {
                            $(this).click();
                        }
                    });

                    $('.acf-dropzone-info > p > span:nth-child(3)').html(
                        '<?php _e('Drop files here', 'idealbiz'); ?>');
                    $('.acf-dropzone-info > p > span:nth-child(2)').html('<?php _e('or', 'idealbiz'); ?>');
                    $('.acf-dropzone-info > p > span.show-if-focus.drag-drop-info').html(
                        '<?php _e('Paste from Clipboard', 'idealbiz'); ?>');

                    <?php if ($broad == '') { ?>
                    $('div[data-name="location_business"]').css('display', 'none');
                    <?php } ?>

                });
                </script>

            </div>
            <?php }?>
        </div>
    </div>
</section>

<?php
/* global $wp;

$cl_user_id = 3555;


$subscriptions = wcs_get_subscriptions(['customer_id' => $cl_user_id,'subscriptions_per_page' => -1]);

// Loop through subscriptions protected objects

foreach ( $subscriptions as $subscription) {
    $items = $subscription->get_items();
        foreach ($items as $item) {
            $product_id = $item->get_product_id();
            $cl_product_id = wc_get_product($product_id);
        }    

}

$pType = get_post_meta($product_id, '_product_type_meta_key', true); */

?>

<?php get_footer(); ?>

<style>
    .cl_cartaoMembro .swiper-slide{
       min-width: 350px !important;  
    }
    .page{
        display: <?php echo $cl_precario ?>;
    }
    .swiper-wrapper{
        height:190px;
    }
    .h3, h3 {
    font-size: 1.4em;
    font-weight: var(--font-weight);
}
.object-cover{
    width: 80%;
    height: 80%;
    border-radius: 50%;
}
.font-weight-medium{
    font-size: 0.80em;
}

.cl_selecionado{
    border-radius: 7px;
    background-color: #eeeeee;
    border: 1px solid #ccc !important;
    padding:30px;
}
.cl_h2{
    font-weight: 600 !important;
    color:#005882 !important;
}
.btn a{
    color:#ffffff !important;
}

.mce-toolbar .mce-btn-group {
    display: none;
}

@media only screen and (max-width: 768px) {
    .shop-page .content-area .plist {
        list-style: none;
        padding: 0 50px !important;
        margin-bottom: 0;
    }
    .sell-business .shop-page .content-area .plist li{
        border-radius: 7px;
        background-color: #eeeeee;
        border: 1px solid #ccc !important;
        padding:30px;
    }
}



</style>