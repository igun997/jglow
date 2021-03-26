<?php
/**
 * Ad-block trigger settings.
 *
 * @package Hustle
 * @since 4.3.0
 */

?>
<div class="sui-form-field">

	<label for="hustle-trigger-adblock" class="sui-toggle">
		<input type="checkbox"
			id="hustle-trigger-adblock"
			name="trigger_on_adblock"
			data-attribute="triggers.on_adblock"
			aria-labelledby="hustle-trigger-adblock-label"
			aria-describedby="hustle-trigger-adblock-description"
			<?php checked( $triggers['on_adblock'], '1' ); ?>
		/>
		<span class="sui-toggle-slider" aria-hidden="true"></span>

		<span id="hustle-trigger-adblock-label" class="sui-toggle-label"><?php esc_html_e( 'Trigger when adblock is detected', 'hustle' ); ?></span>

		<?php /* translators: module type in small caps and in singular */ ?>
		<span id="hustle-trigger-adblock-description" class="sui-description" style="margin-top: 0;"><?php printf( esc_html__( 'Enabling this will trigger the %s everytime an AdBlock is detected in your visitor’s browser.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></span>
	</label>

</div>

<div class="sui-form-field">

	<label for="hustle-trigger-adblock--delay" class="sui-toggle hustle-toggle-with-container" data-toggle-on="trigger-adblock-delay">
		<input type="checkbox"
			id="hustle-trigger-adblock--delay"
			name="trigger_enable_on_adblock_delay"
			data-attribute="triggers.enable_on_adblock_delay"
			aria-labelledby="hustle-trigger-adblock--delay-label"
			aria-describedby="hustle-trigger-adblock--delay-description"
			<?php checked( $triggers['enable_on_adblock_delay'], '1' ); ?>
		/>
		<span class="sui-toggle-slider" aria-hidden="true"></span>

		<span id="hustle-trigger-adblock--delay-label" class="sui-toggle-label"><?php esc_html_e( 'Add delay', 'hustle' ); ?></span>

		<?php /* translators: module type in small caps and in singular */ ?>
		<span id="hustle-trigger-adblock--delay-description" class="sui-description"><?php printf( esc_html__( 'This will delay the %s trigger when AdBlock is detected.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></span>
	</label>

	<div class="sui-border-frame sui-toggle-content" data-toggle-content="trigger-adblock-delay">

		<label class="sui-label"><?php esc_html_e( 'Delay time', 'hustle' ); ?></label>

		<div class="sui-row">

			<div class="sui-col-md-6">

				<input type="number"
					value="<?php echo esc_attr( $triggers['on_adblock_delay'] ); ?>"
					min="0"
					class="sui-form-control"
					name="trigger_on_adblock_delay"
					data-attribute="triggers.on_adblock_delay" />

			</div>

			<div class="sui-col-md-6">

				<select name="trigger_on_adblock_delay_unit" data-attribute="triggers.on_adblock_delay_unit">

					<option value="seconds"
						<?php selected( $triggers['on_adblock_delay_unit'], 'seconds' ); ?>
					>
						<?php esc_html_e( 'seconds', 'hustle' ); ?>
					</option>

					<option value="minutes"
						<?php selected( $triggers['on_adblock_delay_unit'], 'minutes' ); ?>
					>
						<?php esc_html_e( 'minutes', 'hustle' ); ?>
					</option>

					<option value="hours"
						<?php selected( $triggers['on_adblock_delay_unit'], 'hours' ); ?>
					>
						<?php esc_html_e( 'hours', 'hustle' ); ?>
					</option>

				</select>

			</div>

		</div>

	</div>

</div>
