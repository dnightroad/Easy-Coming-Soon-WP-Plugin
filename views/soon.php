<?php
/**
 * Maintenance mode/Coming soon template that's shown to logged out users.
 *
 * @package   maintenance-mode
 * @copyright Copyright (c) 2022, Diana Edreva
 * @license   GPLv2 or later
 */

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href='<?php echo esc_url( $ecsm_stylesheet ); ?>'>

<title>
<?php echo esc_attr($ecsm_heading_0);?>
</title>

  </head>
<body style ="background-image: url('<?php echo esc_url( $ecsm_background_image ); ?>');">
	<header>


<h1>
	<?php echo esc_attr($ecsm_heading_0);?>
</h1>
		<h2>
			<?php echo esc_attr($ecsm_sub_heading_1);?>
		</h2>
	</header>
	<div class ="social">
		<a href="mailto:<?php echo esc_attr($ecsm_email_address_2);?>"><i class="fa fa-envelope" style="font-size:24px"></i> <?php echo esc_attr($ecsm_email_address_2);?></a>
	</div>




</body>
</html>
