<?php

/**
 * Template part for displaying the list sites
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx;



    $blogs = get_sites();

    $cl_excluir_site = array('1','382');
    $cl_lista_site = array();
    /* echo '<pre>'; */

    foreach($cl_excluir_site as $a){

    }
    foreach($blogs as $b){
        $cl_lista_site[] = $b->blog_id;
    }

    $cl_site = array_diff($cl_lista_site,$cl_excluir_site);
    foreach($cl_site as $c ){
        foreach($blogs as $x){
            if($x->deleted==0 && $x->blog_id == $c){
            
                $link = $x->domain.$x->path;
                echo '<a href="https://'.$link .'">https://'.$link.'</a><br/>';
                

        }
        }

    }
    ?>