<?php 
// Template Name: Quaification Lead Listing
get_header(); ?>

<?php 
    $cl_id_Opportunity = $_GET['id_Opportunity'];
?>

<section class="page">
            <div class="chat container position-relative p-b-30 m-t-30">
            <?php echo do_shortcode(get_post_field('post_content', getIdByTemplate('page-qualificatio-lead_listing.php'))); ?>
    </div>             
</section>



<?php get_footer(); ?>

<script>
/* let $cl_origin = document.querySelector('input[name="input_'+cl_id_campo_Origin_SR+'"]'); */
/* let $cl_origin = document.querySelector('input[name="input_28"]'); */
let form = document.forms[1];
let id_Opportunity = form.querySelector('input[name="input_28"]');

id_Opportunity.value = '<?php echo $cl_id_Opportunity; ?>';


</script>

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