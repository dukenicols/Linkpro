<?php global $linkpro; ?>

<?php 
	if ( linkpro_get_option('customfont') ) {
		$font = linkpro_get_option('customfont');
	} elseif ( linkpro_get_option('googlefont') ) {
		$font = linkpro_get_option('googlefont');
	} else {
		$font = 'inherit'; // fallback
	}
	
	if (isset( $linkpro->temp_id ) && $linkpro->temp_id != '' ) {
		$user_id = $linkpro->temp_id;
	}
?>

<style type="text/css">

div.linkpro-awsm-pic {
	margin-left: -<?php echo ($memberlist_v2_pic_size/ 2) +5; ?>px;
	top: -<?php echo ($memberlist_v2_pic_size/ 2) +5; ?>px;
}

div.linkpro-awsm-pic img {
	width: <?php echo $memberlist_v2_pic_size; ?>px;
	height: <?php echo $memberlist_v2_pic_size; ?>px;
}

div.linkpro,
div.emd-main,
div.emd-filters,
div.linkpro-search-results,
div.linkpro-label label,
div.linkpro input,
div.linkpro textarea,
div.linkpro select,
div.linkpro-field textarea.linkpro_editor,
div.linkpro-msg-overlay-content,
div.linkpro-msg-overlay-content input,
div.linkpro-msg-overlay-content textarea,
div.linkpro-notifier
{
	font-family: <?php echo $font; ?>;
}

<?php //Show custom background
if (isset($user_id) && $linkpro->has('custom_profile_bg', $user_id) ) { ?>

div.linkpro-<?php echo $i; ?>.linkpro-id-<?php echo $user_id; ?> div.linkpro-centered,
div.linkpro-<?php echo $i; ?>.linkpro-id-<?php echo $user_id; ?> div.linkpro-centered-header-only
{
	background-image: url(<?php echo $linkpro->correct_space_in_url(  linkpro_profile_data('custom_profile_bg', $user_id) ); ?>) !important;
	background-size: cover;
	-webkit-background-origin:border;
	background-repeat: no-repeat;
}

<?php if (linkpro_profile_data('custom_profile_color', $user_id) == linkpro_get_option('heading_light') ) { ?>
div.linkpro-<?php echo $i; ?>.linkpro-id-<?php echo $user_id; ?> div.linkpro-profile-name,
div.linkpro-<?php echo $i; ?>.linkpro-id-<?php echo $user_id; ?> div.linkpro-profile-name a {
	color: #fff !important;
}
<?php } ?>

<?php } // End custom background ?>

div.linkpro-<?php echo $i; ?> {
	max-width: <?php echo $max_width; ?>;
	<?php if ($align == 'left') { ?>
	float: left;
	width: <?php echo $max_width; ?>;
	<?php } ?>
	<?php if ($align == 'right') { ?>
	float: right;
	width: <?php echo $max_width; ?>;
	<?php } ?>
	<?php if ($align == 'center') { ?>
	margin-left: auto;margin-right: auto;
	<?php } ?>
	<?php if ($margin_top) { ?>
	margin-top: <?php echo $margin_top; ?>;
	<?php } ?>
	<?php if ($margin_bottom) { ?>
	margin-bottom: <?php echo $margin_bottom; ?>;
	<?php } ?>
}

<?php if (isset($args['no_header'])) { ?>

div.linkpro-<?php echo $i; ?> div.linkpro-head,
div.linkpro-<?php echo $i; ?> div.linkpro-centered {
	display: none !important;
}
div.linkpro-<?php echo $i; ?> div.linkpro-centered-header-only {
	display: block !important;
}

<?php } ?>

<?php if (isset($args['no_style'])) { ?>

div.linkpro-<?php echo $i; ?> div.linkpro-head,
div.linkpro-<?php echo $i; ?> div.linkpro-centered {
	display: none !important;
}
div.linkpro-<?php echo $i; ?> div.linkpro-centered-header-only {
	display: block !important;
}

div.linkpro-<?php echo $i; ?> {
	border: none !important;
	padding: 0 !important;
	background: transparent !important;
}
div.linkpro-<?php echo $i; ?> div.linkpro-body {
	padding: 0 !important;
}

div.linkpro-<?php echo $i; ?> div.linkpro-body div.linkpro-online-count {
	padding: 0 !important;
}
<?php } ?>

div.linkpro-<?php echo $i; ?>.linkpro-nostyle {
	max-width: <?php echo isset($card_width)?$card_width:'250px'; ?>;
}

div.linkpro-<?php echo $i; ?>.linkpro-users {
	max-width: <?php echo $memberlist_width; ?> !important;
}

div.linkpro-<?php echo $i; ?> div.linkpro-user {
	margin-top: <?php echo $memberlist_pic_topspace; ?>px;
	margin-left: <?php echo $memberlist_pic_sidespace; ?>px;
	margin-right: <?php echo $memberlist_pic_sidespace; ?>px;
}

div.linkpro-<?php echo $i; ?> div.linkpro-user a.linkpro-user-img {
	width: <?php echo $memberlist_pic_size; ?>px;
	height: <?php echo $memberlist_pic_size; ?>px;
}
div.linkpro-<?php echo $i; ?> div.linkpro-user a.linkpro-user-img span {
	top: -<?php echo $memberlist_pic_size; ?>px;
	line-height: <?php echo $memberlist_pic_size; ?>px;
}

div.linkpro-<?php echo $i; ?> div.linkpro-user div.linkpro-user-link {
	width: <?php echo $memberlist_pic_size; ?>px;
}

<?php if ($memberlist_pic_rounded) { ?>
div.linkpro-<?php echo $i; ?> div.linkpro-user a.linkpro-user-img,
div.linkpro-<?php echo $i; ?> div.linkpro-user a.linkpro-user-img span {
	border-radius: 999px !important;
}
<?php } ?>

div.linkpro-<?php echo $i; ?> div.linkpro-list-item-i {
	width: <?php echo $list_thumb; ?>px;
	height: <?php echo $list_thumb; ?>px;
}

<?php if (isset($list_mini)){ ?>
div.linkpro-<?php echo $i; ?> div.linkpro-list-item {
	border-bottom: 0px !important;
	padding: 10px 0 0 0;
}

div.linkpro-<?php echo $i; ?> div.linkpro-list-item img.linkpro-profile-badge,
div.linkpro-<?php echo $i; ?> div.linkpro-list-item img.linkpro-profile-badge-right {
	max-width: 14px !important;
	max-height: 14px !important;
}
<?php } ?>

div.linkpro-<?php echo $i; ?> div.linkpro-online-item-i {
	width: <?php echo $online_thumb; ?>px;
	height: <?php echo $online_thumb; ?>px;
}

<?php if (isset($online_mini)){ ?>
div.linkpro-<?php echo $i; ?> div.linkpro-online-item {
	border-bottom: 0px !important;
	padding: 10px 0 0 0;
}

div.linkpro-<?php echo $i; ?> div.linkpro-online-item img.linkpro-profile-badge,
div.linkpro-<?php echo $i; ?> div.linkpro-online-item img.linkpro-profile-badge-right {
	max-width: 14px !important;
	max-height: 14px !important;
}
<?php } ?>

div.linkpro-<?php echo $i; ?> div.linkpro-profile-img {
	width: <?php echo $profile_thumb_size; ?>px;
}

div.emd-user {
    width: <?php echo $args['emd_col_width']; ?>;
	margin-left: <?php echo $args['emd_col_margin']; ?> !important;
}

<?php if (linkpro_get_option('thumb_style') == 'abit_rounded') { ?>
div.linkpro-profile-img img,
div.linkpro-pic-profilepicture img,
div.linkpro-list-item-i,
div.linkpro-list-item-i img,
div.linkpro-online-item-i,
div.linkpro-online-item-i img,
div.linkpro-post.linkpro-post-compact div.linkpro-post-img img,
a.linkpro-online-i-thumb img,
div.linkpro-awsm-pic img,
div.linkpro-awsm-pic,
div.linkpro-sc-img img
{
	border-radius: 3px !important;
}
<?php } ?>

<?php if (linkpro_get_option('thumb_style') == 'rounded') { ?>
div.linkpro-profile-img img,
div.linkpro-pic-profilepicture img,
div.linkpro-list-item-i,
div.linkpro-list-item-i img,
div.linkpro-online-item-i,
div.linkpro-online-item-i img,
div.linkpro-post.linkpro-post-compact div.linkpro-post-img img,
a.linkpro-online-i-thumb img,
div.linkpro-awsm-pic img,
div.linkpro-awsm-pic,
div.linkpro-sc-img img
{
	border-radius: 999px !important;
}
<?php } ?>

<?php if (linkpro_get_option('thumb_style') == 'square') { ?>
div.linkpro-profile-img img,
div.linkpro-pic-profilepicture img,
div.linkpro-list-item-i,
div.linkpro-list-item-i img,
div.linkpro-online-item-i,
div.linkpro-online-item-i img,
div.linkpro-post.linkpro-post-compact div.linkpro-post-img img,
a.linkpro-online-i-thumb img,
div.linkpro-awsm-pic img,
div.linkpro-awsm-pic,
div.linkpro-sc-img img
{
	border-radius: 0px !important;
}
<?php } ?>

</style>

