<?php // Template Name: Login Register

?>

<!doctype html>
<html class="" <?php language_attributes(); ?>>

<head>
    <!--[if lt IE 9]>
		<script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
	<![endif]-->

    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
	

    <title><?php wp_title(); ?></title>

	<script async src="https://cse.google.com/cse.js?cx=fb7fe69bdc108dde7"></script>

    <script>
    document.documentElement.className = document.documentElement.className.replace(/\bno-js\b/, 'js')
    </script>


    <?php // load the core js polyfills 
	?>
    <script async defer src="<?php echo get_template_directory_uri(); ?>/assets/js/core.js"></script>


    <?php wp_head(); ?>
	

</head>
<style>
<?php if (is_singular('listing')) {
    $bg='#fbfbfc';
}

else {
    $bg=get_field('background');
    if ($bg=='') $bg='#fbfbfc';
}

?>body {
    background-color: <?php echo $bg;
    ?> !important;
}

.whiteTriangle:after {
    background-color: <?php echo $bg;
    ?> !important;
}

.lrm-form a.button, .lrm-form button, .lrm-form button[type=submit], .lrm-form #buddypress input[type=submit], .lrm-form input[type=submit] {
    background-color: #14307b;
}




</style>

<?php 

$titulo = get_the_title();
$redirect_after_login = $_REQUEST['redirect_to'];
?>
<div class="container text-center">
        <!-- <h1 class="m-h2 text-xs-left m-b-30 p-t-30" Style= "text-align:center;">
        <?php echo $titulo; ?>
        </h1>  -->       
</div>
<div class="container m-b-30">
<?php 
echo '<div class="lrm-user-modal-container">';
the_content();
echo '</div>';

if (is_user_logged_in()==false){
echo do_shortcode('[lrm_form default_tab="login" logged_in_message="You are currently logged in!"]');
}else{
    wp_redirect($redirect_after_login);
}

 ?> 
</div> 


<?php 
get_footer();
?>
