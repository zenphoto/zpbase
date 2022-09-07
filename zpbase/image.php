<?php
/*	zpBase image.php 
*	This theme page shows the large default sized single image.
*	http://www.oswebcreations.com
================================================== */
if (isset($_GET['show'])) {
include ('inc/image-popup.php');
} else {

include ('inc/header.php'); ?>
			
	<div class="container" id="middle">
		<div class="row">
			<div id="content">
				<div id="object-info">
					<?php 
					// The following checks and modifications of breadcrumb link backs are necessary when using an album layout without pagination, since default behaviour is to provide a link back to the album page the image is on.
					$linkbackpaged = true;
					if (function_exists('getSelectedLayout')) {
						$albumlayout = getSelectedLayout($_zp_current_album,'multiple_layouts_albums');
					} else {
						$albumlayout = array('data'=>'');
					}
					if (is_array($albumlayout) && (($albumlayout['data'] == 'album-masonry.php') || ($albumlayout['data'] == 'album-galleria.php')) || (getOption('zpbase_defaultalbum') == 'album-galleria') || (getOption('zpbase_defaultalbum') == 'album-masonry')) { 
						$linkbackpaged = false; 
						if (in_context(ZP_SEARCH_LINKED)) { 
							$_zp_current_search->page = '1'; 
						}
					}
					?>
					<div id="object-title">
						<div id="breadcrumb">
							<?php 
							printParentBreadcrumb('',' / ',' / '); 
							if ($linkbackpaged) {
							printAlbumBreadcrumb(' ', ' / '); 
							} else {
							$link = rewrite_path("/" . pathurlencode($_zp_current_album->name) . "/","/index.php?album=" . pathurlencode($_zp_current_album->name)); ?>
							<a href="<?php echo $link; ?>" title="<?php echo $_zp_current_album->getTitle(); ?>"><?php echo $_zp_current_album->getTitle(); ?></a>&nbsp;/&nbsp;
							<?php } ?>
							<span>(<em><?php echo imageNumber().' of '.getNumImages(); ?></em>)</span>
						</div>
						<h2 class="notop"><?php printImageTitle(); ?></h2>
					</div>
				</div>

				<div id="image-full" class="block clearfix">
					<div id="single-img-nav"<?php if ($_zp_current_image->isVideo()) echo ' class="video-nav"'; ?>>
						<?php if (hasPrevImage()) { ?>
						<a class="prev-link" href="<?php echo html_encode(getPrevImageURL());?>" title="<?php echo gettext("Previous Image"); ?>"><span></span></a>
						<?php } if (hasNextImage()) { ?>
						<a class="next-link" href="<?php echo html_encode(getNextImageURL());?>" title="<?php echo gettext("Next Image"); ?>"><span></span></a>
						<?php } ?>
					</div>
					<?php printDefaultSizedImage(getBareImageTitle(),'remove-attributes'); ?>
					<?php if (getOption('zpbase_verticalscale')) { ?>
					<script>
						var imgh = <?php echo getFullHeight() ?>;
						var imgw = <?php echo getFullWidth() ?>;
						function resizeFullImageDiv() {
							var vpw = $(window).width();
							var vph = $(window).height()
							    - $('#top').outerHeight(true)
							    - $('#object-info').outerHeight(true)
							    - parseInt($('#image-full').css('margin-top'),10)
							    - parseInt($('#image-full').css('margin-bottom'),10);
							var scalefactor = Math.min(vpw/imgw, vph/imgh);
							var w = (imgw*scalefactor).toFixed();
							var h = (imgh*scalefactor).toFixed();
							$('#image-full').css({'height': h+'px'});
							if ($('video').length) {
							    $('video').css({'height': h+'px'});
							    $('video').css({'width': w+'px'});
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
					<?php } ?>
				</div>

				<div id="object-info-img-bottom">
					<div id="object-menu">
						<?php if (getOption('zpbase_date_images')) { ?><span><?php printImageDate(); ?></span><?php } ?>
						<?php if (getOption('zpbase_social')) include ('inc/socialshare.php'); ?>
						<?php if (getOption('zpbase_download')) { ?><span><a href="<?php echo html_encode(getFullImageURL()); ?>" title="<?php echo gettext('Download'); ?>"><?php echo gettext('Download').' ('.getFullWidth().' x '.getFullHeight().')'; ?></a></span><?php } ?>
						<?php if (getOption('zpbase_galss')) { 
						if ($_zp_current_image->isPhoto()) { ?><span><?php printBaseSlideShowLink(); ?></span><?php } ?>
						<?php } elseif (function_exists('printSlideShowLink')) { ?>
						<span><?php printSlideShowLink(); ?></span>
						<?php } ?>
					</div>
					<div id="object-desc"><?php printImageDesc(); ?></div>
					<?php if (function_exists('printGoogleMap')) { ?><div id="map-wrap"><?php printGoogleMap('Google Map',null,'hide'); ?></div><?php } ?>
					<?php if (function_exists('printOpenStreetMap')) { ?><div id="map-wrap"><?php printOpenStreetMap(); ?></div><?php } ?>
					<?php if (getImageData('copyright')) { ?><p class="image-copy"><?php echo getImageData('copyright'); ?></p><?php } ?>
					
					<?php if (getOption('zpbase_archive')) {
					$singletag = getTags(); $tagstring = implode(', ', $singletag); 
					if (strlen($tagstring) > 0) { ?>
					<div class="block"><?php echo gettext('Tags: '); ?><?php printTags('links','','taglist', ', '); ?></div>
					<?php } 
					} ?>
					
					<?php printCodeblock(); ?>
					
					<?php if (getImageMetaData()) { ?><p><?php printImageMetadata('',false,'imagemetadata'); ?></p><?php } ?>
				</div>
				
				<div class="jump center">
					<?php printBaseAlbumMenuJump('count',gettext('Gallery Index')); ?>
				</div>
					
				<?php if (function_exists('printRating')) { ?>
				<div id="rating" class="block"><?php printRating(); ?></div>
				<?php } ?>
				<?php if (function_exists('printAddToFavorites')) { ?>
				<div id="favorites" class="block"><?php printAddToFavorites($_zp_current_image); ?></div>
				<?php } ?>
				<?php if (getOption('zpbase_disqus')) { ?><div class="block"><?php printDisqusCommentForm(); ?></div>
				<?php } elseif (function_exists('printCommentForm')) { ?><div class="block"><?php printCommentForm(); ?></div><?php } ?>
				<?php if (function_exists('printRelatedItems')) { ?><div class="block"><?php printRelatedItems(5,'pages',null,null,'pages'); ?></div><?php } ?>
			</div>
		</div>
	</div>

<?php include ('inc/footer.php'); 
} ?>
