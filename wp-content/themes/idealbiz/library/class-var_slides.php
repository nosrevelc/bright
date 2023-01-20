<?php

/**
 * Handles Listings Page Data
 * 
 *
 *
 * @author CLEVERSON VIEIRA +351 939 302 819
 */


// make sure this file is called by wp
defined('ABSPATH') or die();


class Conj_Var_Member_out_group_ACF{

    public function Var_Member($conj){
    /* $this->config_sub_homepage = get_field('config_sub_homepage');  */   
   /*  $this->title_section_member = $this->config_sub_homepage['title_section_member'.$conj.'']; */
    $this->number_of_members = get_field('number_of_members'.$conj.'');
    $this->show_section_member = true;
    $this->member_category_section = get_field('member_category_section'.$conj.'');
    $this->view_all_member_partner = false;
    /* $this->select_page_all_member_partner = $this->config_sub_homepage['select_page_all_member_partner'.$conj.'']; */
   
    }

}

class Conj_var_listing_out_goup_ACF{


    public function Var_listing($conj){
    /* $this->config_sub_homepage = get_field('config_sub_homepage'); */
    /* $this->title_section_linsting = ''; */
    $this->slide_listing = true;   
    $this->listing_our_wanted =  'listing';
    $this->view_all_opportunities = true;
    /* $this->highlight =  false; */
    $this->anuncio_por_pagina =  get_field('anuncio_por_pagina'.$conj.'');
    $this->categoria_anuncios =  get_field('categoria_anuncios'.$conj.'');
    $this->filter_view_button_all_opportunities =  get_field('categoria_anuncios'.$conj.'');
    }

}



class Conj_var_listing{


    public function Var_listing($conj){
    $this->config_sub_homepage = get_field('config_sub_homepage');
    $this->title_section_linsting = $this->config_sub_homepage['title_section_linsting'.$conj.''];
    $this->slide_listing = $this->config_sub_homepage['slide_listing'.$conj.''];   
    $this->listing_our_wanted =  $this->config_sub_homepage['listing_our_wanted'.$conj.''];
    $this->view_all_opportunities =  $this->config_sub_homepage['view_all_opportunities'.$conj.''];
    $this->highlight =  $this->config_sub_homepage['highlight'.$conj.''];
    $this->anuncio_por_pagina =  $this->config_sub_homepage['anuncio_por_pagina'.$conj.''];
    $this->categoria_anuncios =  $this->config_sub_homepage['categoria_anuncios'.$conj.''];
    $this->filter_view_button_all_opportunities =  $this->config_sub_homepage['filter_view_button_all_opportunities'.$conj.''];
    }

}

class Conj_Var_Member{

    public function Var_Member($conj){
    $this->config_sub_homepage = get_field('config_sub_homepage');    
    $this->title_section_member = $this->config_sub_homepage['title_section_member'.$conj.''];
    $this->number_of_members = $this->config_sub_homepage['number_of_members'.$conj.''];
    $this->show_section_member = $this->config_sub_homepage['show_section_member'.$conj.''];
    $this->member_category_section = $this->config_sub_homepage['member_category_section'.$conj.''];
    $this->view_all_member_partner = $this->config_sub_homepage['view_all_member_partner'.$conj.''];
    $this->select_page_all_member_partner = $this->config_sub_homepage['select_page_all_member_partner'.$conj.''];
   
    }

}

class Conj_Var_Partner{

    public function Var_Partner($conj){
   
    $this->title_section_member = get_field('title_section_partner'.$conj.'');
    $this->number_of_members = get_field('number_of_partner'.$conj.'');
    $this->show_section_member = get_field('show_section_member'.$conj.'');
    $this->member_category_section = get_field('member_category_section'.$conj.'');
    $this->view_all_member_partner = get_field('view_all_member_partner'.$conj.'');
    $this->select_page_all_member_partner = get_field('select_page_all_member_partner'.$conj.'');
    $this->view_button_join_us = get_field('view_button_join_us'.$conj.'');
    $this->select_page_all_member_join_us = get_field('select_page_all_member_join_us'.$conj.'');
    $this->view_button_learn_more = get_field('view_button_learn_more'.$conj.'');
    $this->select_page_learn_more = get_field('select_page_learn_more'.$conj.'');
   
    }

}

class Conj_Var_Product{

    public function Var_Product($conj){
        $this->title_section_products = $this->config_sub_homepage['title_section_products'.$conj.''];
        $this->mostrar_produtos = $this->config_sub_homepage['mostrar_produtos'.$conj.''];
        $this->quantidade_de_produtos = $this->config_sub_homepage['quantidade_de_produtos'.$conj.''];
        $this->categorias_de_pordutos = $this->config_sub_homepage['categorias_de_pordutos'.$conj.''];
        $this->view_all_products = $this->config_sub_homepage['view_all_products'.$conj.''];
        $this->select_page_all_product = $this->config_sub_homepage['select_page_all_product'.$conj.''];
   
    }

}


