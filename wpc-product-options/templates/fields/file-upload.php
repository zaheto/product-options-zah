<?php
/**
 * @var $field
 * @var $key
 */

defined( 'ABSPATH' ) || exit;
?>
<input type="file" class="wpcpo-option-field field-uploads input-text" name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>"
	<?php echo esc_attr( $field['required'] ? 'required' : '' ); ?>
        data-title="<?php echo esc_attr( $field['title'] ); ?>" data-enable-price="<?php echo esc_attr( $field['enable_price'] ); ?>" data-price-type="<?php echo esc_attr( $field['price_type'] ); ?>" data-price-custom="<?php echo esc_attr( $field['custom_price'] ); ?>" data-price="<?php echo esc_attr( $field['price'] ); ?>"/>
<input type="hidden" name="<?php echo esc_attr( $key ); ?>[value]" value="wpcpo-<?php echo esc_attr( $key ); ?>"/>
<input type="hidden" name="<?php echo esc_attr( $key ); ?>[type]" value="file"/>
<?php if ( $field['enable_price'] ) { ?>
    <input type="hidden" name="<?php echo esc_attr( $key ); ?>[price_type]" value="<?php echo esc_attr( $field['price_type'] ); ?>"/>
    <input type="hidden" name="<?php echo esc_attr( $key ); ?>[price]" value="<?php echo esc_attr( $field['price'] ); ?>"/>
    <input type="hidden" name="<?php echo esc_attr( $key ); ?>[custom_price]" value="<?php echo esc_attr( $field['custom_price'] ); ?>"/>
<?php } ?>
<small><?php echo sprintf( __( '(max file size %s)', 'wpc-product-options' ), size_format( wp_max_upload_size() ) ); ?></small>
