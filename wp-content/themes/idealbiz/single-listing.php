<?php
// Template Name: SingleListing
get_header();

if (have_posts()) : while (have_posts()) : the_post();
        $post_id = get_the_ID();
        $image = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'medium');
        $permalink = get_permalink();
        $title = get_the_title();
        $ref = get_the_ID();
        $is_certified = get_field('listing_certification_status') == 'certification_finished';
        $cl_expert = get_field('expert');
        $cl_expert_id = get_field('expert')->ID;
        $cl_hidden_price = get_field('rb_hidden_price_in_front_office');
        $cl_rb_post_in_recomemded_business = get_field('rb_post_in_recomemded_business',$post_id);

        $current_user = wp_get_current_user();
        $cl_userAtual = isExpert($current_user->ID)[0]->ID; 

        if (is_user_logged_in()) {
            update_user_meta(get_current_user_id(), 'read_post_' . $post_id, true);
        }

?>




        <section class="single-listing md-m-t-35 md-m-b-130">
            <div class="container m-b-25 md-m-b-10">
                <a href="javascript: history.go(-1)"><i class="dashicons-before dashicons-undo"></i><span class="font-weight-bold m-l-5"><?php _e('Go back to your seach', 'idealbiz'); ?></span></a>
                <!-- VOLTAR DEIXOU DE FUNCIONAR TROQUEI POR UM JAVA -->
                <!--<a class="go-search font-weight-medium d-flex align-items-center" href="<?php echo get_permalink(pll_get_post(get_page_by_path('listings')->ID)); ?>">
                    <i class="icon-dropdown"></i>
                    <span class="font-weight-bold m-l-5"><?php _e('Go back to your seach', 'idealbiz'); ?></span>
                </a> -->
            </div>


            <div class="black--color white--background dropshadow p-t-20 p-b-20 container d-flex flex-row flex-wrap justify-content-around">

                <div class="col-12 col-lg-6">

                    <div>
                        <h2 class="listing-title"><?php echo $title . ' - ' . 'REF:' . $ref; ?></h2>

                    </div>

                    <div class="d-flex flex-row flex-wrap">
                        <span class="m-r-20"><?php echo esc_html(get_field('category')->name);  ?></span>
                        <div class="location p-x-0 font-weight-bold">
                            <i class="icon-local"></i>
                            <span class="text-uppercase"><?php echo esc_html(get_field('location')->name);  ?></span>
                        </div>
                    </div>

                    <div class="listing-description m-t-5 m-b-10 text-justify">
                        <?php echo the_content(); ?>
                    </div>
                    <?php if ($cl_hidden_price != true){?>
                        <?php $cl_valor=IDB_Listing_Data::get_listing_value($post_id, 'price_type')?>

                    <?php if ($cl_valor != '') { ?>
                        <div class="price m-b-20">
                            <h2 class="font-weight-bold light-blue--color"><?php _e('Price', 'idealbiz'); ?>: <?php echo IDB_Listing_Data::get_listing_value($post_id, 'price_type'); 
                           
                            ?></h2>
                        </div>
                        <?php } ?>

                    <?php } ?>
                    <div class="listing-financial d-none font-weight-medium col-md-10 p-0">
                        <?php
                        set_query_var('post_id', $post_id);
                        get_template_part('/elements/listing/listing_financial');
                        ?>
                    </div>

                    <div class="m-t-15 hidden-mobile">
                        <?php
                        $args = array(
                            'post_status' => 'publish',
                            'posts_per_page' => 1,
                            'post_type' => 'expert',
                            'p' => $cl_expert_id,
                        );
                        $experts = new WP_Query($args);
                        // Busca valor dentro de $esperts.


                        //NPMM - Solução criada pelo Cleverson para buscar o contacto para este listing após descoatibilidade com cache.
                        //ini 
                        foreach ($experts as $post) {
                        };
                        $expert_id_contact = get_field('expert', $post->ID);
                        $cl_email_contactar = get_field('expert_email', $expert_id_contact);
                        $cl_contactar =  $post_id . '|' . $cl_email_contactar . '|' . wp_get_current_user()->ID;
                        session_start();
                        $_SESSION["contato_para_listing"] = $cl_contactar;

                        /* echo $_SESSION["contato_para_listing"]; */

                        //fim


                        if ($experts->have_posts()) :
                            while ($experts->have_posts()) : $experts->the_post();
                        ?>
                                <div class="expert-card-2">
                                    <?php get_template_part('/elements/listing/experts_cad_listing'); ?>
                                </div>
                        <?php
                            endwhile;
                        else :
                            get_template_part('/elements/no_results');
                        endif;
                        wp_reset_postdata();

                        ?>
                    </div>


                </div>

                <div class="col-12 col-lg-6">
                    <div class="listing position-relative p-b-15 dropshadow d-flex flex-column black--color white--background">

                        <?php if ($is_certified) : $badge = get_template_directory_uri() . '/assets/img/badge.png'; ?>
                            <div class="certified-badge" style="background-image: url(<?php echo $badge; ?>);"></div>
                        <?php endif; ?>

                        <?php if (get_field('cover_video')) { ?>
                            <?php
                            $url = get_field('cover_video');
                            parse_str(parse_url($url, PHP_URL_QUERY), $my_array_of_vars);
                            ?>
                            <div class="cl_video">
                                <iframe class="cl_video_iframe" src="https://www.youtube.com/embed/<?php echo $my_array_of_vars['v']; ?>?rel=0&enablejsapi=1" title="<?php echo $title ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        <?php } else { ?>
                            <div class="image" style="background-image:url(<?php echo get_field('featured_image')['sizes']['large']; ?>);"></div>
                        <?php } ?>
                        <div class="d-flex flex-column flex-md-row p-x-20 justify-content-between align-items-center m-t-15">
                            <div class="social d-flex flex-row m-t-5 m-b-5">
                                <a href="http://www.facebook.com/sharer.php?u=<?php echo $permalink; ?>" target="_blank" rel="nofollow"><i class="icon-facebook"></i></a>
                                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $permalink; ?>&amp;title=<?php echo $title; ?>" target="_blank" rel="nofollow" class="m-x-15"><i class="icon-linkedin"></i></a>
                                <a href="https://twitter.com/intent/tweet?text=<?php echo $title; ?>&amp;url=<?php echo $permalink; ?>" target="_blank" rel="nofollow"><i class="icon-twitter"></i></a>
                            </div>
                            <div class="pull-right">


                            <?php
                            
                                    //	NPMM - REDIRECT TO FORM QUALIFICATION LEAD
                                    //$rb_id_gravity_form_recommended_business = get_field('id_gform_recommended_business', 'option'); 
                                    
                                    

                                    $cl_id_member_chosen = $expert_id_contact->ID;
                                    //var_dump($expert_id_contact);  
                                    $cl_emailMember = $cl_email_contactar;

                                    $cl_page = getLinkByTemplate('page-qualificatio-lead_listing.php');
                                    $cl_id_Opportunity = $post_id;
                                    //$cl_Opportunitydasboard =getLinkByTemplate('RecommendedBusiness.php').'?received=1';
                                    global $wp;
                                    $cl_Opportunitydasboard =home_url( add_query_arg( array(), $wp->request ) );  
                                    $cl_urlQualificatioLead = $cl_page.'?membro_e_mail='.$cl_emailMember .'&id_Opportunity='.$cl_id_Opportunity.'&id_member_chosen='.$cl_id_member_chosen.'&port_listing=true&myOpportunityDashboard='.$cl_Opportunitydasboard;
                                    //$confirmation = array( 'redirect' => $cl_urlQualificatioLead );
                            
                            ?>

                                <?php echo do_shortcode('[favorite_button post_id="' . $post_id . '" site_id="' . get_current_blog_id() . '"]'); ?>
                                
                                <?php if(!isset($_GET['step'])){?>
                                <a href="<?php echo $cl_urlQualificatioLead ?>" class="btn_step1 btn btn-light-blue contact-seller"><?php _e('_str Step 1', 'idealbiz'); ?></a>
                                <?php }else{ ?>

                                <a href="#contact_form_id" class="btn_step2 btn btn-light-blue contact-seller popUpForm  "><?php _e('_str Step 2', 'idealbiz'); ?></a>
                                <?php } ?>

                            </div>

                        </div>

                    </div>
                    <div>
                    <?php

                                $cl_rb_member_of_recommended_business = get_field('rb_member_of_recommended_business', $cl_userAtual);

                                /* var_dump($cl_rb_member_of_recommended_business); */
                                
                                if ($cl_rb_member_of_recommended_business == true) {
                                    if ($cl_rb_post_in_recomemded_business === true){
                                ?>
                                    <h1><p class="p-t-20"><?php _e('_str You can receive to recommend this deal','idealbiz');?></p></h1>
                                    <div class="cl_comissao"></div>
                                    <div id="test-popup" class="white-popup mfp-hide">

                                    <?php    
                                    $rb_id_gravity_form_recommended_business = get_field('id_gform_recommended_business', 'option');
                                    echo do_shortcode( '[gravityform id="'.$rb_id_gravity_form_recommended_business.'" title="false" description="false" ajax="true" tabindex="49" field_values=""]' ); 
                                    ?>
                                    </div>
                                <?php 
                                    }
                                } 
                                ?>
                    </div>
                    <div class="m-t-15 hidden-desktop">
                        <?php
                        $args = array(
                            'post_status' => 'publish',
                            'posts_per_page' => 1,
                            'post_type' => 'expert',
                            'p' => $cl_expert_id,
                        );
                        $experts = new WP_Query($args);

                        if ($experts->have_posts()) :
                            while ($experts->have_posts()) : $experts->the_post();
                        ?>
                                <div class="expert-card-2">
                                    <?php get_template_part('/elements/listing/experts_cad_listing'); ?>
                                </div>
                        <?php
                            endwhile;
                        else :
                            get_template_part('/elements/no_results');
                        endif;
                        wp_reset_postdata();

                        ?>
                    </div>
                </div>

                <?php echo do_shortcode(get_post_field('post_content', getIdByTemplate('single-listing.php'))); ?>
            </div>

            <?php
            $images = get_field('gallery');
            if ($images) : ?>

                <div class="swiper-container gallery container m-t-30">
                    <div class="swiper-wrapper">
                        <?php foreach ($images as $image) : ?>
                            <div class="swiper-slide"> <img class="w-100 h-100" src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" /></div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="swiper-container gallery-thumbs container m-t-20">
                    <div class="swiper-wrapper">
                        <?php foreach ($images as $image) : ?>
                            <div class="swiper-slide w-150px h-150px"> <img class="w-100 h-100" src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" /></div>
                        <?php endforeach; ?>
                    </div>
                </div>

            <?php endif; ?>


            <div class="container d-none flex-row justify-content-around m-t-100">
                <div class="d-flex flex-column col-md-9">
                    <?php get_template_part('/elements/listing/listing_data'); ?>
                </div>
                <div class="col-md-2"></div>
            </div>

            <?php //get_template_part('/elements/listing/contact_seller'); 
            ?>




            <?php
            /* contact expert or responsible */
            /*
            $expert = get_field( "expert" );
            if($expert){
                ?>
                <br/>
                <br/>
                <br/>
                <div class="container d-flex flex-row flex-wrap justify-content-around">
                <div class="col-md-12">
                <div class="expert position-relative p-b-15 dropshadow d-flex flex-column black--color white--background">
                <div class="container text-center">
                    <h2 class="base_color--color m-t-30"><?php _e('Contact this Expert', 'idealbiz'); ?></h2>
                    <br>
                    <?php 

                    if(is_user_logged_in()){
                        /*
                        $post_author_id = get_post_field( 'post_author', $expert->ID );
                        $author_obj = get_user_by('id', $post_author_id);
                        echo '<div class="generic-form">
                                <div class="acf-form">';
                            echo do_shortcode('[fep_shortcode_new_message_form to="'.$author_obj->user_nicename.'" subject="'.__('Listing# ','idealbiz').$post_id.' - '.get_the_title().'" heading=""]');
                            echo '</div>
                            </div><style>.fep-form-field-fep_upload, .fep-form-field-message_title, .fep-form-field-message_content label {display: none;}</style>';
                        }else{
                        ?>
                        <p class="has-text-align-center"><?php _e('Get in touch with this expert, login now!', 'idealbiz'); ?></p>
                        <p class="has-text-align-center"><a class="login-register p-y-6 pointer register lrm-login btn-blue" href=""><?php _e('Login','idealbiz'); ?></a></p>
                        </div>
                        </div>
                        </div>
                        <?php
                    }
              
                    //comments_template();
                    ?> 
                </div>
                </div>
                </div>
                <?php
                //var_dump( get_field('owner'));
            }
                  */


            ?>

            <script>
                jQuery(document).ready(($) => {
                    var url_actual = window.location.href;

                    <?php if (LISTING_SYSTEM == '1') { ?>
                        //$('select[name="owner"]').parent().css('display','none').prev().css('display','none');
                        $('select[name="owner"]').attr('disabled', 'disabled');
                        $('input[name="listingsystem"]').val('1');
                        var aux_system = '<?php echo get_the_ID(); ?>|' + $('select[name="owner"] option:selected').val() + '|' + <?php echo wp_get_current_user()->ID; ?> + '';
                        $('input[name="listingsystem"]').val(aux_system);
                        console.log(aux_system);

                    <?php } ?>

                    //$('input[name="owner"]').val('<?php echo get_field('owner')["user_email"]; ?>');
                    $('input[name="current_user_email"]').val('<?php echo wp_get_current_user()->user_email; ?>');
                    $('input[name="post_title"]').val('<?php the_title(); ?>');
                    $('input[name="ref"]').val('<?php echo $ref; ?>');
                    $('input[name="post_link"]').val('<?php the_permalink(); ?>');
                    $('input[name="costumercare"]').val('<?php echo get_field('costumer_care_email', 'options'); ?>');
                    $('input[name="owner_name"]').val($('select[name="owner"] option:selected').text());
                    $('input[name="lang"]').val('<?php echo get_user_locale(); ?>');



                    $('select[name="owner"]').on('change', function() {
                        $('input[name="owner_name"]').val($('select[name="owner"] option:selected').text());

                        <?php if (LISTING_SYSTEM == '1') { ?>
                            aux_system = '<?php echo get_the_ID(); ?>|' + $('select[name="owner"] option:selected').val() + '|' + <?php echo wp_get_current_user()->ID; ?> + '';
                            $('input[name="listingsystem"]').val(aux_system);
                            console.log(aux_system);
                        <?php } ?>
                    });

                });
            </script>

                <?php

                    $current_user = wp_get_current_user();
                    $email_membro=$current_user->user_email;
                    $id_expert = isExpert($current_user->ID);
                    $user_meta = get_user_meta($id_user_atual);
                    $last_name = $user_meta['last_name'][0];
                    $first_name = $user_meta['first_name'][0];
                    $comission = get_field('commission_by_recommendation_field');
                    $cl_moneySymbol = get_woocommerce_currency_symbol();
                    $cl_forceContact = get_field('rb_force_direct_contact_with_advertiser', $expert_id_contact);
                    $cl_strAguarde = __('_str Wait...','idealbiz');
                    //NPMM - Quando o contato directo com anunciante troca email do suporte para email do owner.
                    if($cl_forceContact===true){
                        $email_titular =get_field('owner')['user_email'];
                        $Name_titular = get_field('owner')['nickname'];
                        $id_titular = isExpert(get_field('owner')['ID'])[0]->ID;
                    }else{
                        $id_titular = $expert_id_contact->ID;
                        $email_titular = $cl_email_contactar;
                        $Name_titular = $expert_id_contact->post_title;
                    }

                    $linsting_price_trash = IDB_Listing_Data::get_listing_value($post_id, 'price_type');
                    $linsting_price = preg_replace("/[^0-9]/", "", $linsting_price_trash );
                    

                    /* cl_alerta($linsting_price); */
                    
                    
                    
                    if ($comission === 'monetary_value'){
                        $comission_value_field = get_field('monetary_value_field');
                        $comission_value = calculaComissaoRecomendacao($comission,$id_titular,$linsting_price,$comission_value_field);
                        $cl_percentage_comissao = (float)(100-$comission_value[0]);
                        $comission_value_type = __('_str Receive You will receive','idealbizio').' '.$cl_percentage_comissao .'%'.' '.__('_str of Gross Amount','idealbiz');
                        $valor_bruto_comissao = __('_str Gross Amount','idealbiz').' : '.$comission_value[3].$cl_moneySymbol.' '.' '.__('_str Monetary Value Fixed','idealbiz'); 
                         
                    }else{
                        $comission_value_field = get_field('percentage_value_field');
                        $comission_value = calculaComissaoRecomendacao($comission,$id_titular,$linsting_price,$comission_value_field);
                        $valor_bruto_comissao = __('_str Gross Amount','idealbiz').' '.$comission_value_field.'%'.' '.__('_str of Price','idealbiz').' : '.$comission_value[3].$cl_moneySymbol;

                        $cl_percentage_comissao = (float)(100-$comission_value[0]);
                        $comission_value_type = __('_str You will receive','idealbizio').' '.$cl_percentage_comissao .'%'.' '.__('_str of Gross Amount','idealbiz');
                    };

                    /* var_dump($comission_value_type); */

                    $args = array(
                        'type'                     => 'expert',
                        'child_of'                 => 0,
                        'parent'                   => '',
                        'orderby'                  => 'name',
                        'order'                    => 'ASC',
                        'hide_empty'               => 1,
                        'hierarchical'             => 1,
                        'taxonomy'                 => 'service_cat',
                        'pad_counts'               => false );

                        $categories = get_categories($args);                
                        foreach ($categories as $category) {
                        $url = get_term_link($category);
                        $opts .= '<option value="' . $category->name . '" data-select2-id= "'.$category->cat_ID.'"  id="dropOption">' . $category->name . '</option>';
                        }

                        /* var_dump($comission); */
                ?>
                <script>

                     let btn_step1 = document.querySelector('a.btn_step1');
                     btn_step1.addEventListener('click',clicar)
                     function clicar(){ 
                        btn_step1.style.background = '#7A7A7A'
                        btn_step1.innerText = '<?php echo $cl_strAguarde; ?>'
                    }

                     let form = document.forms[1];
                     let idUserActual = form.querySelector('input[name="input_7"]');
                     let inputNameIndicate = form.querySelector('input[name="input_23"]');
                     let comission_value_type = form.querySelector('input[name="input_22"]');
                     let comission_value = form.querySelector('input[name="input_12"]');
                     let inputEmailTitular = form.querySelector('input[name="input_19"]');
                     let inputIdTtular = form.querySelector('input[name="input_20"]');
                     let inputNameTtular = form.querySelector('input[name="input_21"]');
                     let inputRef = form.querySelector('input[name="input_25"]');
                     let inputPrice = form.querySelector('input[name="input_24"]');
                     let inputComissaoCalculada = form.querySelector('input[name="input_26"]');
                     let inputComissaoBruto = form.querySelector('input[name="input_27"]'); 
                     const textComissao = document.querySelector('.cl_comissao');

                     
                     

                     idUserActual.value = '<?php echo $id_expert[0]->ID; ?>';
                     inputNameIndicate.value = '<?php echo $id_expert[0]->post_title ; ?>';
                     comission_value.value = '<?php echo $comission_value_field ; ?>';
                     comission_value_type.value = '<?php echo $comission ; ?>';
                     inputIdTtular.value = '<?php echo $id_titular ; ?>';
                     inputEmailTitular.value = '<?php echo $email_titular; ?>';
                     inputNameTtular.value = '<?php echo $Name_titular; ?>';
                     inputPrice.value = '<?php echo $linsting_price; ?>';
                     inputComissaoCalculada.value = '<?php echo $comission_value[2]; ?>';
                     inputComissaoBruto.value = '<?php echo $comission_value[3]; ?>';
                     inputRef.value = '<?php echo $ref ; ?>';
                     textComissao.innerHTML = `<p class="font-weight-bold light-blue--color p-t-0"> ${'<?php echo $valor_bruto_comissao ?>'}</p><p class="font-weight-bold light-blue--color p-t-0"> ${'<?php echo  __('_str iDB Tax','idealbiz').' '.$comission_value[0].'% '. __('_str iDB Tax Value','idealbiz').' '.$comission_value[1].$cl_moneySymbol; ?>'}</p><p class="font-weight-bold light-blue--color p-t-0"> ${'<?php echo $comission_value_type.' : '.$comission_value[2].$cl_moneySymbol .'<br><span class="cl_information">'.__('_str Information','idealbiz').'</span>'.'<br><h1><span class="">'.__('_str contact_information','idealbiz').'</span></h1>'; ?>'}</p>`;

                     
                     console.log(textComissao.innerHTML);

                     /* alert(comission_value_type.value); */

                     options.addEventListener('change', function() {
                        alert('Teste');   
                     });

                     console.log(label_comission.value);

                        

                </script>
        </section>

<?php endwhile;
endif; ?>

<?php get_footer();?>

<style>
    .btn_step2{
        background-color: #FF7904;
    }
    .white-popup {
        padding: 0px !important;
    }
    .div_top {
        vertical-align: top;

    }

    #bottom {

        bottom: 0;
        left: 0;
    }

    .cl_video {
        position: relative;
        padding-bottom: 56.25%;
        /* 16:9 */
        height: 0;
    }

    .cl_video_iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .white-popup {
        position: relative;
        background: #FFF;
        padding: 20px;
        width: auto;
        max-width: 500px;
        margin: 20px auto;
    }

    .cl_information{
        color:red;
    }

    h1{
        font-size:1.5em !important;
    }


</style>

