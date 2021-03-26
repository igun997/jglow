<?php
/**
 * Triggers section.
 *
 * @package Hustle
 * @since 4.0.0
 */

$trigger_args = array(
	'smallcaps_singular'  => $smallcaps_singular,
	'capitalize_singular' => $capitalize_singular,
	'shortcode_id'        => $shortcode_id,
	'triggers'            => $triggers,
);

$options = array(
	'time'        => array(
		'value'   => 'time',
		'label'   => esc_html__( 'Time', 'hustle' ),
		'content' => $this->render( 'admin/commons/sui-wizard/tab-behaviour/trigger/time', $trigger_args, true ),
	),
	'scroll'      => array(
		'value'   => 'scroll',
		'label'   => esc_html__( 'Scroll', 'hustle' ),
		'content' => $this->render( 'admin/commons/sui-wizard/tab-behaviour/trigger/scroll', $trigger_args, true ),
	),
	'click'       => array(
		'value'   => 'click',
		'label'   => esc_html__( 'Click', 'hustle' ),
		'content' => $this->render( 'admin/commons/sui-wizard/tab-behaviour/trigger/click', $trigger_args, true ),
	),
	'exit_intent' => array(
		'value'   => 'exit_intent',
		'label'   => esc_html__( 'Exit intent', 'hustle' ),
		'content' => $this->render( 'admin/commons/sui-wizard/tab-behaviour/trigger/exit-intent', $trigger_args, true ),
	),
	'adblock'     => array(
		'value'   => 'adblock',
		'label'   => esc_html__( 'AdBlock', 'hustle' ),
		'content' => $this->render( 'admin/commons/sui-wizard/tab-behaviour/trigger/adblock', $trigger_args, true ),
	),
);
?>

<div class="sui-box-settings-row">

	<div class="sui-box-settings-col-1">
		<?php /* translators: module type capitalized and in singular */ ?>
		<span class="sui-settings-label"><?php printf( esc_html__( '%s Trigger', 'hustle' ), esc_html( $capitalize_singular ) ); ?></span>
		<?php /* translators: module type in small caps and in singular */ ?>
		<span class="sui-description"><?php printf( esc_html__( '%s can be triggered after a certain amount of Time, when the user Scrolls past an element, on Click, if the user tries to Leave or if we detect AdBlock.', 'hustle' ), esc_html( $capitalize_plural ) ); ?></span>
	</div>

	<div class="sui-box-settings-col-2">

		<?php
		$this->render(
			'admin/global/sui-components/sui-tabs',
			array(
				'name'          => 'triggers.trigger',
				'radio'         => true,
				'saved_value'   => $triggers['trigger'],
				'sidetabs'      => true,
				'content'       => true,
				'content_class' => 'sui-tabs-content-lg',
				'options'       => $options,
			)
		);
		?>

	</div>

</div>
