<?php
/**
 * @var $frontend
 * @var $fields
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="wpcpo-wrapper">
    <div class="wpcpo-options">
		<?php
		foreach ( $fields as $key => $field ):
			$field = wp_parse_args( $field, [
				'required'      => '',
				'enable_price'  => '',
				'price_type'    => '',
				'price'         => '',
				'custom_price'  => '',
				'hide_title'    => '',
				'show_desc'     => '',
				'desc'          => '',
				'enable_limit'  => '',
				'min'           => '',
				'step'          => '',
				'max'           => '',
				'default_value' => '',
				'value'         => '',
				'id'            => $key
			] );

			$field_class = 'wpcpo-option wpcpo-option-' . $field['type'];
			$field_class .= $field['required'] ? ' wpcpo-required' : '';
			?>
            <div class="<?php echo esc_attr( $field_class ); ?>">
				<?php if ( strpos( $field['type'], 'appearance-' ) !== false ) {
					wc_get_template(
						'fields/' . $field['type'] . '.php',
						[
							'field' => $field,
							'key'   => $key,
						],
						'wpc-product-options',
						WPCPO_DIR . 'templates/'
					);
				} else { ?><?php if ( ! $field['hide_title'] ): ?>
                    <label class="wpcpo-option-name" for="<?php echo esc_attr( $key ); ?>">
                        <strong><?php echo esc_html( $field['title'] ); ?></strong>
                        <span><?php echo Wpcpo_Frontend::get_label_price( $field ); ?></span> </label>
				<?php endif; ?><?php if ( $field['show_desc'] && $field['desc'] !== '' ): ?>
                    <div class="wpcpo-option-description">
						<?php echo esc_html( $field['desc'] ); ?>
                    </div>
				<?php endif; ?>
                    <div class="wpcpo-option-form">
                        <p class="form-row">
							<?php
							wc_get_template(
								'fields/' . $field['type'] . '.php',
								[
									'field' => $field,
									'key'   => $key,
								],
								'wpc-product-options',
								WPCPO_DIR . 'templates/'
							);
							?>
                            <input type="hidden" name="<?php echo esc_attr( $key ); ?>[title]" value="<?php echo esc_attr( $field['title'] ); ?>"/>
                        </p>
                    </div>
				<?php } ?>
            </div>
		<?php endforeach; ?>
    </div>
	<?php $frontend->total_price_settings(); ?>
</div>
