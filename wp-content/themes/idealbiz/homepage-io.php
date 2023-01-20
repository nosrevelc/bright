<?php
// Template Name: Homepage IO

$config_landing_page = get_field('landing_page');


?>

<!doctype html>
<html class="homepage-io" <?php language_attributes(); ?>>
<head>
	<!--[if lt IE 9]>
		<script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
	<![endif]-->

	<meta charset="<?php bloginfo('charset'); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<title><?php wp_title(); ?></title>

	<script>
		document.documentElement.className = document.documentElement.className.replace(/\bno-js\b/, 'js')
	</script>
	<style>
		@font-face {
		font-family: "idealbiz";
		src: url(https://idealbiz.io/wp-content/themes/idealbiz/assets/fonts/idealbiz.woff) format("woff");
		font-weight: normal;
		font-style: normal;
		}
	</style>


	<?php wp_head(); //Alterar?>
</head>
<body data-ajax-url="<?php echo site_url() . '/wp-admin/admin-ajax.php'; ?>" style="background-image: url(<?php echo $config_landing_page['image_landing_page']['url']; ?>);">
	<div class="transparent-background w-100 h-100"></div>

	<?php Component_Countries_Modal::render(); ?>

</body>

<?php
  $image = wp_get_attachment_image_src($img, $size);
?>



<?php





wp_footer();
?>

</html>