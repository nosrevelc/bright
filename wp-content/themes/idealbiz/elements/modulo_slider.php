
<?php
/**
 * Exibe modulos slider tipo Listing Membros ou Cookies
 * 
 *
 *
 * @author CLEVERSON VIEIRA +351 939 302 819
 */


// Classes em wp-content/themes/idealbiz/library/class-var_slides.php
        
        $show_type_module = get_field('show_type_module'.$id_modulo);

                /* var_dump($show_type_module.$id_modulo); */

                if($show_type_module == 'listing'){
                    $id_listing = $id_modulo;
                    $conj_val = $id_modulo;
                    $cl_varlisting = new Conj_var_listing_out_goup_ACF();
                    $cl_varlisting->Var_listing($conj_val);
                        if($cl_varlisting->slide_listing){
                        include(MY_THEME_DIR.'elements/silider_listing.php');
                        }
                    wp_reset_postdata();           
                            
                }

                if($show_type_module == 'member'){
                    $conj_val = $id_modulo;
                    $cl_varParceiro = new Conj_Var_Member_out_group_ACF();
                    $cl_varParceiro->Var_Member($conj_val);
                        if($cl_varParceiro->show_section_member){
                            include(MY_THEME_DIR.'elements/silider_membros.php');
                        }   
                    wp_reset_postdata();
                }

                if($show_type_module == 'cookies'){
                    $conj_cookies = $id_modulo;
                    $destaque = false;
                    include(MY_THEME_DIR . 'elements/cookies.php');
                }    
            

            
    
?>
