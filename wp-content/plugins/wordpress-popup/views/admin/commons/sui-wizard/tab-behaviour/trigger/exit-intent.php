<?php
/**
 * Exit intent trigger settings.
 *
 * @package Hustle
 * @since 4.3.0
 */

?>

<div class="sui-notice">

	<div class="sui-notice-content">

		<div class="sui-notice-message">

			<span class="sui-notice-icon sui-icon-info sui-md" aria-hidden="true"></span>
			<p>
				<?php printf( esc_html__( "%1\$sNote:%2\$s This doesn't work on mobile and tablet because we use mouse movements to detect the exit intent.", 'hustle' ), '<b>', '</b>' ); ?>
			</p>

		</div>
	</div>
</div>

<?php // SETTINGS: Trigger once per session. ?>
<div class="sui-form-field">

	<label for="hustle-trigger-exit--session" class="sui-toggle">
		<input type="checkbox"
			id="hustle-trigger-exit--session"
			name="trigger_on_exit_intent_per_session"
			data-attribute="triggers.on_exit_intent_per_session"
			aria-labelledby="hustle-trigger-exit--session-label"
			aria-describedby="hustle-trigger-exit--session-description"
			<?php checked( $triggers['on_exit_intent_per_session'], '1' ); ?>
		/>
		<span class="sui-toggle-slider" aria-hidden="true"></span>

		<span id="hustle-trigger-exit--session-label" class="sui-toggle-label"><?php esc_html_e( 'Trigger once per session', 'hustle' ); ?></span>

		<?php /* translators: module type in small caps and in singular */ ?>
		<span id="hustle-trigger-exit--session-description" class="sui-description" style="margin-top: 0;"><?php printf( esc_html__( 'Enabling this will trigger the %s only for the first time user tries to leave your website in a session.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></span>
	</label>

</div>

<?php // SETTINGS: Add delay. ?>
<div class="sui-form-field">

	<label for="hustle-trigger-exit--delay" class="sui-toggle hustle-toggle-with-container" data-toggle-on="trigger-exit-delay">
		<input type="checkbox"
			id="hustle-trigger-exit--delay"
			name="trigger_on_exit_intent_delayed"
			data-attribute="triggers.on_exit_intent_delayed"
			aria-labelledby="hustle-trigger-exit--delay-label"
			aria-describedby="hustle-trigger-exit--delay-description"
			<?php checked( $triggers['on_exit_intent_delayed'], '1' ); ?>
		/>
		<span class="sui-toggle-slider" aria-hidden="true"></span>

		<span id="hustle-trigger-exit--delay-label" class="sui-toggle-label"><?php esc_html_e( 'Add delay', 'hustle' ); ?></span>

		<?php /* translators: module type in small caps and in singular */ ?>
		<span id="hustle-trigger-exit--delay-description" class="sui-description" style="margin-top: 0;"><?php printf( esc_html__( 'This will delay the appearance of the %s after the user attempts to exit.', 'hustle' ), esc_html( $smallcaps_singular ) ); ?></span>
	</label>

	<div class="sui-border-frame sui-toggle-content" data-toggle-content="trigger-exit-delay">

		<label class="sui-label"><?php esc_html_e( 'Delay time', 'hustle' ); ?></label>

		<div class="sui-row">

			<div class="sui-col-md-6">

				<input type="number"
					value="<?php echo esc_attr( $triggers['on_exit_intent_delayed_time'] ); ?>"
					min="0"
					class="sui-form-control"
					name="trigger_on_exit_intent_delayed_time"
					data-attribute="triggers.on_exit_intent_delayed_time" />

			</div>

			<div class="sui-col-md-6">

				<select name="trigger_on_exit_intent_delayed_unit" data-attribute="triggers.on_exit_intent_delayed_unit">

					<option value="seconds"
						<?php selected( $triggers['on_exit_intent_delayed_unit'], 'seconds' ); ?>
					>
						<?php esc_html_e( 'seconds', 'hustle' ); ?>
					</option>

					<option value="minutes"
						<?php selected( $triggers['on_exit_intent_delayed_unit'], 'minutes' ); ?>
					>
						<?php esc_html_e( 'minutes', 'hustle' ); ?>
					</option>

					<option value="hours"
						<?php selected( $triggers['on_exit_intent_delayed_unit'], 'hours' ); ?>
					>
						<?php esc_html_e( 'hours', 'hustle' ); ?>
					</option>

				</select>

			</div>

		</div>

	</div>

</div>
