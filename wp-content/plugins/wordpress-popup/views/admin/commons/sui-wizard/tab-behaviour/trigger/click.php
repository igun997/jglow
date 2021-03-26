<?php
/**
 * Click trigger settings.
 *
 * @package Hustle
 * @since 4.3.0
 */

?>

<?php // SETTINGS: Click on existing element. ?>
<div class="sui-form-field">

	<label for="hustle-trigger-click--selector" class="sui-toggle hustle-toggle-with-container" data-toggle-on="trigger-click-selector">
		<input type="checkbox"
			id="hustle-trigger-click--selector"
			name="trigger_on_click_selector"
			data-attribute="triggers.enable_on_click_element"
			aria-labelledby="hustle-trigger-click--selector-label"
			aria-describedby="hustle-trigger-click--selector-description"
			<?php checked( $triggers['enable_on_click_element'], '1' ); ?>
		/>
		<span class="sui-toggle-slider" aria-hidden="true"></span>

		<span id="hustle-trigger-click--selector-label" class="sui-toggle-label"><?php esc_html_e( 'Click on existing element', 'hustle' ); ?></span>

		<?php /* translators: module type capitalized and in singular */ ?>
		<span id="hustle-trigger-click--selector-description" class="sui-description"><?php printf( esc_html__( 'Trigger your %s when a user clicks on an existing HTML element(s).', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></span>
	</label>

	<div class="sui-border-frame sui-toggle-content" data-toggle-content="trigger-click-selector">

		<label class="sui-label"><?php esc_html_e( 'CSS selector(s)', 'hustle' ); ?></label>

		<input type="text"
			placeholder="<?php esc_attr_e( 'For example .element-class, #element-id', 'hustle' ); ?>"
			value="<?php echo esc_attr( $triggers['on_click_element'] ); ?>"
			class="sui-form-control"
			name="trigger_on_click_element"
			data-attribute="triggers.on_click_element" />

			<?php /* translators: module type in smallcaps and in singular */ ?>
		<p class="sui-description"><?php printf( esc_html__( 'You can add multiple selectors separated by a comma to trigger your %s from multiple elements.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></p>
	</div>

</div>

<?php // SETTINGS: Render a new button. ?>
<div class="sui-form-field">

	<label for="hustle-trigger-click--shortcode" class="sui-toggle hustle-toggle-with-container" data-toggle-on="trigger-click-shortcode">
		<input type="checkbox"
			id="hustle-trigger-click--shortcode"
			name="trigger_on_click_shortcode"
			data-attribute="triggers.enable_on_click_shortcode"
			aria-labelledby="hustle-trigger-click--shortcode-label"
			aria-describedby="hustle-trigger-click--shortcode-description"
			<?php checked( $triggers['enable_on_click_shortcode'], '1' ); ?>
		/>
		<span class="sui-toggle-slider" aria-hidden="true"></span>

		<span id="hustle-trigger-click--shortcode-label" class="sui-toggle-label"><?php esc_html_e( 'Render a new button', 'hustle' ); ?></span>

		<?php /* translators: module type in small caps and in singular */ ?>
		<span id="hustle-trigger-click--shortcode-description" class="sui-description" style="margin-top: 0;"><?php printf( esc_html__( 'You can render a new button which will tigger the %s using the shortcode.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></span>
	</label>

	<div class="sui-border-frame sui-toggle-content" data-toggle-content="trigger-click-shortcode">

		<label class="sui-label"><?php esc_html_e( 'Shortcode to render the trigger element', 'hustle' ); ?></label>

		<div class="sui-with-button sui-with-button-inside">
			<input type="text"
				class="sui-form-control"
				value='[wd_hustle id="<?php echo esc_attr( $shortcode_id ); ?>" type="<?php echo esc_attr( $this->admin->module_type ); ?>"]<?php esc_attr_e( 'Click', 'hustle' ); ?>[/wd_hustle]'
				readonly="readonly">
			<button class="sui-button-icon hustle-copy-shortcode-button">
				<span aria-hidden="true" class="sui-icon-copy"></span>
				<span class="sui-screen-reader-text"><?php esc_html_e( 'Copy shortcode', 'hustle' ); ?></span>
			</button>
		</div>

	</div>

</div>
