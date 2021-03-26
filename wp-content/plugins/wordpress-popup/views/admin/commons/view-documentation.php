<?php
/**
 * Title section.
 *
 * @package Hustle
 * @since 4.3.1
 */

$hide = apply_filters( 'wpmudev_branding_hide_doc_link', false );
if ( ! $hide ) :
	?>
	<div class="sui-actions-right">
		<a
			href="https://premium.wpmudev.org/docs/wpmu-dev-plugins/hustle/#<?php echo esc_attr( $docs_section ); ?>"
			target="_blank"
			class="sui-button sui-button-ghost"
		>
			<span class="sui-icon-academy"></span> <?php esc_html_e( 'View Documentation', 'hustle' ); ?>
		</a>
	</div>
<?php endif; ?>
