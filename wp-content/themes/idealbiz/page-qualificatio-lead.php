<?php 
// Template Name: Quaification Lead
get_header(); ?>


<section class="page">
            <div class="chat container position-relative p-b-30 m-t-30">
            <?php echo do_shortcode(get_post_field('post_content', getIdByTemplate('page-qualificatio-lead.php'))); ?>
    </div>             
</section>



<?php get_footer(); ?>

<style>
    /* keep it mobile friendly by only applying these styles for larger viewports */
@media only screen and (min-width: 641px) {
  
  body #gform_wrapper_67 { 
   max-width: 50%;
   margin: 0 auto;
  }
}

body{
 color:#707070;
}
.gform_wrapper .gfield_required{
    display:none;
    color:#F5B0B0;
}


.gform_wrapper.gravity-theme .gfield-choice-input {
    max-width:15px !important; 
}

.gform_wrapper.gravity-theme .gform_footer {
    padding-top: 41px !important;
}

.gform_title {
    
    font-size: 3.45em;
    font-family: var(--font-default),sans-serif;
    color: #005882;
    text-align: center;
    padding-bottom: 30px;
}

.gform_description{
    font-size: 1.7em;
    font-family: var(--font-default),sans-serif;
}

</style>

<script>
      $(document).ready(function() {

          $("input[type='text']").each(function() {
            $(this).attr("autocomplete", "off");
          });
       
      });
    </script>