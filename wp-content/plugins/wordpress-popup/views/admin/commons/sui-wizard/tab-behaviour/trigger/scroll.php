<?php
/**
 * Scroll trigger settings.
 *
 * @package Hustle
 * @since 4.3.0
 */

?>
<?php // SETTINGS: After the amount of page scroll. ?>
<div class="sui-form-field">

	<label for="hustle-trigger-scroll--percentage"
		class="sui-radio sui-radio-stacked"
		style="margin-bottom: 0;">
		<input type="radio"
			value="scrolled"
			id="hustle-trigger-scroll--percentage"
			name="trigger_on_scroll"
			data-attribute="triggers.on_scroll"
			<?php checked( $triggers['on_scroll'], 'scrolled' ); ?>
		/>
		<span aria-hidden="true"></span>
		<span><?php esc_html_e( 'After the amount of page scroll', 'hustle' ); ?></span>
	</label>

	<?php /* translators: module type capitalized and in singular */ ?>
	<span class="sui-description sui-checkbox-description"><?php printf( esc_html__( '%s will be shown as the page has been scrolled by certain percentage.', 'hustle' ), esc_html( $capitalize_singular ) ); ?></span>

	<div id="hustle-on-scroll--scrolled-toggle-wrapper"
		class="sui-border-frame <?php echo ( 'scrolled' !== $triggers['on_scroll'] ) ? ' sui-hidden' : ''; ?>"
		style="margin-left: 26px;">
		<?php /* translators: module type in small caps and in singular */ ?>
		<label class="sui-label"><?php printf( esc_html__( 'Scroll &#37; to trigger the %s (anything between 0 - 100&#37;)', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></label>

		<input type="number"
			value="<?php echo esc_attr( $triggers['on_scroll_page_percent'] ); ?>"
			min="0"
			max="100"
			class="sui-form-control"
			name="trigger_on_scroll_page_percent"
			data-attribute="triggers.on_scroll_page_percent" />

	</div>

</div>

<?php // SETTINGS: After the passed selector. ?>
<div class="sui-form-field">

	<label for="hustle-trigger-scroll--selector"
		class="sui-radio sui-radio-stacked"
		style="margin-bottom: 0;">
		<input type="radio"
			value="selector"
			id="hustle-trigger-scroll--selector"
			name="trigger_on_scroll"
			data-attribute="triggers.on_scroll"
			<?php checked( $triggers['on_scroll'], 'selector' ); ?>
		/>
		<span aria-hidden="true"></span>
		<span><?php esc_html_e( 'After the passed selector', 'hustle' ); ?></span>
	</label>

	<?php /* translators: module type capitalized and in singular */ ?>
	<span class="sui-description sui-checkbox-description"><?php printf( esc_html__( '%s will be shown as the user has passed a CSS selector', 'hustle' ), esc_html( $capitalize_singular ) ); ?></span>

	<div id="hustle-on-scroll--selector-toggle-wrapper" class="sui-border-frame <?php echo 'selector' !== $triggers['on_scroll'] ? 'sui-hidden' : ''; ?>"
		style="margin-left: 26px;">

		<label for="hustle-trigger-scroll--selector-name" class="sui-label"><?php esc_html_e( 'CSS selector', 'hustle' ); ?></label>

		<input type="text"
			placeholder="<?php esc_html_e( 'Enter selector Class or Id', 'hustle' ); ?>"
			value="<?php echo esc_attr( $triggers['on_scroll_css_selector'] ); ?>"
			id="hustle-trigger-scroll--selector-name"
			class="sui-form-control"
			name="trigger_on_scroll_css_selector"
			data-attribute="triggers.on_scroll_css_selector" />

		<span class="sui-description"><?php esc_html_e( 'You can enter the class as .css-class and id as #css-id', 'hustle' ); ?></span>

	</div>

</div>
