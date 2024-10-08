<?php 
/*	zpBase Image popup page
* 	This file is used as the magnific popup of the image page, if set in options.
*	It is a very minimal version of the image.php page.
*	http://www.oswebcreations.com	
================================================== */
// force UTF-8 �

if (!defined('WEBPATH')) die();?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="<?php echo LOCAL_CHARSET; ?>" />
	<?php zp_apply_filter('theme_head');
	$objectclass = str_replace (" ", "", getBareImageTitle()).'-'.$_zp_current_image->getID();
	printHeadTitle(); ?>
	<meta name="description" content="<?php echo truncate_string(getBareImageDesc(),150,'...'); ?>" />	
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" href="<?php echo $_zp_themeroot; ?>/css/style.css">
	<script src="<?php echo $_zp_themeroot; ?>/js/imagesloaded.pkgd.min.js"></script>
	<!--<script src="<?php echo $_zp_themeroot; ?>/js/zpbase_js.js"></script>-->
	<?php if (getOption('zpbase_googlefont1') != null) { 
	$googlefontstack1 = str_replace('+',' ',getOption('zpbase_googlefont1')); ?>
	<link href="http://fonts.googleapis.com/css?family=<?php echo getOption('zpbase_googlefont1'); ?>" rel="stylesheet" type="text/css" />
	<?php } ?>
	<?php if (getOption('zpbase_googlefont2') != null) {
	$googlefontstack2 = str_replace('+',' ',getOption('zpbase_googlefont2')); ?>
	<link href="http://fonts.googleapis.com/css?family=<?php echo getOption('zpbase_googlefont2'); ?>" rel="stylesheet" type="text/css" />
	<?php } ?>
	<style>
		<?php if (getOption('zpbase_googlefont1') != null) { ?>body,#nav a,#sidebar ul a{font-family: '<?php echo $googlefontstack1; ?>', sans-serif;} <?php } ?>
		<?php if (getOption('zpbase_googlefont2') != null) { ?>h1,h2,h3,h4,h5,h6{font-family: '<?php echo $googlefontstack2; ?>', serif;} <?php } ?>
		<?php if (((getOption('zpbase_fontsize')) > 10) && (getOption('zpbase_fontsize') < 19)) echo 'body{font-size:'.getOption('zpbase_fontsize').'px;}'; ?>
		<?php if (getOption('zpbase_customcss') != null) { echo getOption('zpbase_customcss'); } ?>
		<?php if (($isMobile) || ($isTablet)) { echo 'body{font-size:16px;}'; } ?>
	</style>
	<base target="_parent">
</head>
<body id="<?php echo getOption('zpbase_style'); ?>" class="image-popup-page <?php echo $objectclass.' '.$layoutbodyclass; ?>">
	<?php if ( (getOption('zpbase_analytics')) && (!zp_loggedin(ADMIN_RIGHTS)) ) { ?>
	<script>
		<?php if (getOption('zpbase_analytics_type') == 'universal') { ?>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
		ga('create', '<?php echo getOption('zpbase_analytics'); ?>', 'auto');
		ga('send', 'pageview');
		<?php } else { ?>
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', '<?php echo getOption('zpbase_analytics'); ?>']);
		_gaq.push(['_trackPageview']);
		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
		<?php } ?>
	</script>
	<?php } ?>
	<?php zp_apply_filter('theme_body_open'); ?>
	
	<div id="image-popup">
		<div id="image-full" class="block clearfix" style="width:100%;">
			<div id="single-img-nav"<?php if ($_zp_current_image->isVideo()) echo ' class="video-nav"'; ?>>
				<?php if (hasPrevImage()) { ?>
				<a class="prev-link" target="_self" href="<?php echo html_encode(getPrevImageURL());?>?show=imagepage" title="<?php echo gettext("Previous Image"); ?>"><span></span></a>
				<?php } if (hasNextImage()) { ?>
				<a class="next-link" target="_self" href="<?php echo html_encode(getNextImageURL());?>?show=imagepage" title="<?php echo gettext("Next Image"); ?>"><span></span></a>
				<?php } ?>
			</div>
			<?php printDefaultSizedImage(getBareImageTitle(),'remove-attributes'); ?>
			<script>
				$('#image-full').imagesLoaded(function(){
					$('.remove-attributes').fadeIn();
				});
			</script>

			<script>
				var imgh = <?php echo getFullHeight() ?>;
				var imgw = <?php echo getFullWidth() ?>;

				function resizeFullImageDiv() {
					var vpw = $(window).width();
					var vph = $(window).height();

					var scalefactor = Math.min(vpw/imgw, vph/imgh);
					var w = (imgw*scalefactor).toFixed();
					var h = (imgh*scalefactor).toFixed();

					$('div#image-full').css({'height': h+'px'});
					$('div#image-full').css({'top': ((vph-h)/2)+'px'});

					$('div#image-full img.remove-attributes').attr('height', h);
					$('div#image-full img.remove-attributes').attr('width', w);

					if ($('video').length) {
						$('video').css({'height': h+'px'});
						$('video').css({'width': w+'px'});
						$('video').css({'top': ((vph-h)/2)+'px'});
					}
				}

				window.onresize = function(event) {
					resizeFullImageDiv();
				}

				$(document).ready(function(){
				resizeFullImageDiv();

				$('video').bind("loadedmetadata", function() {
					imgh = this.videoHeight;
					imgw = this.videoWidth;
					resizeFullImageDiv();
				});

				// for HTML5 videos enable metadata preloading
				$('video').not(['preload']).each(function(){
					$(this).attr('preload','metadata');
					this.load();
				});
			    });
			</script>

		</div>

</body>
</html>
