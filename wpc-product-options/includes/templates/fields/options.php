<?php
/**
 * @var $this
 * @var $options
 * @var $type
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="wpcpo-inner-options">
    <div class="inner-header">
		<?php if ( $type === 'image-radio' ) { ?>
            <span class="inner-header-image"><?php esc_html_e( 'Image', 'wpc-product-options' ); ?></span>
		<?php } else { ?>
            <span class="inner-header-name"><?php esc_html_e( 'Label', 'wpc-product-options' ); ?></span>
		<?php } ?>
        <span class="inner-header-value"><?php esc_html_e( 'Value *', 'wpc-product-options' ); ?></span>
        <span class="inner-header-price"><?php esc_html_e( 'Price', 'wpc-product-options' ); ?></span>
    </div>
    <div class="inner-content">
		<?php
		foreach ( $options as $k => $option ) {
			$this->get_option_field( $option, $type );
		}
		?>
    </div>
    <div class="inner-footer">
        <button type="button" class="button wpcpo-add-new-option" data-id="<?php echo esc_attr( $this->field_id ); ?>"><?php esc_html_e( 'Add option', 'wpc-product-options' ); ?></button>
    </div>
</div>
