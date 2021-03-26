<?php
/**
 * Click trigger settings.
 *
 * @package Hustle
 * @since 4.3.0
 */

?>
<?php /* translators: module type in small caps and in singular */ ?>
<label class="sui-settings-label"><?php printf( esc_html__( 'Show %s on page load', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></label>
<?php /* translators: module type capitalized and in singular */ ?>
<span class="sui-description"><?php printf( esc_html__( '%s will be shown as soon as page is loaded. If you want to add some delay, use the option below:', 'hustle' ), esc_html( $capitalize_singular ) ); ?></span>

<div class="sui-border-frame">

	<label class="sui-label"><?php esc_html_e( 'Add delay', 'hustle' ); ?></label>

	<div class="sui-row">

		<div class="sui-col-md-6">

			<input type="number"
				value="<?php echo esc_attr( $triggers['on_time_delay'] ); ?>"
				min="0"
				class="sui-form-control"
				name="trigger_on_time_delay"
				data-attribute="triggers.on_time_delay" />

		</div>

		<div class="sui-col-md-6">

			<select name="on_time_unit" data-attribute="triggers.on_time_unit">

				<option value="seconds"
					<?php selected( $triggers['on_time_unit'], 'seconds' ); ?>
				>
					<?php esc_html_e( 'seconds', 'hustle' ); ?>
				</option>

				<option value="minutes"
					<?php selected( $triggers['on_time_unit'], 'minutes' ); ?>
				>
					<?php esc_html_e( 'minutes', 'hustle' ); ?>
				</option>

				<option value="hours"
					<?php selected( $triggers['on_time_unit'], 'hours' ); ?>
				>
					<?php esc_html_e( 'hours', 'hustle' ); ?>
				</option>

			</select>

		</div>

	</div>

</div>
