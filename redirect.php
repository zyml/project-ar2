<?php
/*
 * Template Name: Redirect
 */
?>

<?php if ( have_posts () ) : the_post() ?>
<?php $URL = get_the_excerpt(); if ( !preg_match( '/^http:\/\//', $URL ) ) $URL = 'http://' . $URL; ?>

<!DOCTYPE html>
<html <?php language_attributes() ?>>

<head>
<meta http-equiv="refresh" content="0;url=<?php echo $URL; ?>">
</head>

<body>
</body>
</html>

<?php endif; ?>
