<?php
/**
 * Title: Header
 * Slug: blockskit-health-care/header
 * Categories: theme
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|x-small","bottom":"var:preset|spacing|x-small"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--x-small);padding-bottom:var(--wp--preset--spacing--x-small)"><!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column {"verticalAlignment":"center","width":"15%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:15%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|xx-small"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
<div class="wp-block-group"><!-- wp:site-logo {"width":26,"shouldSyncIcon":true} /-->

<!-- wp:site-title {"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}},"fontSize":"large"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"15%","className":"bk-hide-tab bk-hide-mob"} -->
<div class="wp-block-column is-vertically-aligned-center bk-hide-tab bk-hide-mob" style="flex-basis:15%"></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"70%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:70%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|medium"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group"><!-- wp:navigation {"ref":344,"icon":"menu","style":{"spacing":{"blockGap":"var:preset|spacing|medium"},"typography":{"fontStyle":"normal","fontWeight":"600","textTransform":"uppercase"}},"fontSize":"x-small","layout":{"type":"flex","justifyContent":"center"}} /-->

<!-- wp:group {"style":{"layout":{"selfStretch":"fit","flexSize":null}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"left"}} -->
<div class="wp-block-group"><!-- wp:group {"style":{"layout":{"selfStretch":"fixed","flexSize":"50px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:image {"id":315,"scale":"contain","sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/header-img1.png' ) ); ?>" alt="" class="wp-image-315" style="object-fit:contain"/></figure>
<!-- /wp:image --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0"},"layout":{"selfStretch":"fixed","flexSize":"280px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"fontSize":"x-small"} -->
<p class="has-x-small-font-size"><?php esc_html_e( 'Emergency Number', 'blockskit-health-care' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":6} -->
<h6 class="wp-block-heading"><?php esc_html_e( '+21(352) 848 9979', 'blockskit-health-care' ); ?></h6>
<!-- /wp:heading --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->