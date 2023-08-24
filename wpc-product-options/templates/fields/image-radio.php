<?php
/**
 * @var $field
 * @var $key
 */

defined( 'ABSPATH' ) || exit;

if ( isset( $field['options'] ) && ! empty( $field['options'] ) ) {
	foreach ( $field['options'] as $option_key => $option ) {
		if ( isset( $option['value'] ) && $option['value'] !== '' && isset( $option['image'] ) && ! empty( $option['image'] ) ) {
			?>
            <input class="wpcpo-option-field field-radio" type="radio" name="<?php echo esc_attr( $key ); ?>[value]" id="<?php echo esc_attr( $option_key ); ?>" data-label="<?php echo esc_attr( $option['value'] ); ?>" data-title="<?php echo esc_attr( $field['title'] ); ?>" data-enable-price="1" data-price-type="<?php echo esc_attr( $option['price_type'] ); ?>" data-price="<?php echo esc_attr( $option['price'] ); ?>" data-price-custom="<?php echo esc_attr( $option['custom_price'] ); ?>" value="<?php echo esc_attr( $option['value'] ); ?>" data-image="<?php echo esc_attr( $option['image'] ); ?>" <?php echo esc_attr( $field['default_value'] && ( $field['value'] === $option['value'] ) ? 'checked' : '' ); ?>>
            <label for="<?php echo esc_attr( $option_key ); ?>">
				<?php echo wp_get_attachment_image( $option['image'] ); ?>
                <span><?php echo Wpcpo_Frontend::get_label_price( $option, 'option' ); ?></span> </label>
		<?php }
	}
} ?>
<input type="hidden" name="<?php echo esc_attr( $key ); ?>[label]" value=""/>
<input type="hidden" name="<?php echo esc_attr( $key ); ?>[price_type]" value=""/>
<input type="hidden" name="<?php echo esc_attr( $key ); ?>[price]" value=""/>
<input type="hidden" name="<?php echo esc_attr( $key ); ?>[custom_price]" value=""/>
<input type="hidden" name="<?php echo esc_attr( $key ); ?>[type]" value="image-radio"/>
<input type="hidden" name="<?php echo esc_attr( $key ); ?>[image]" value=""/>
