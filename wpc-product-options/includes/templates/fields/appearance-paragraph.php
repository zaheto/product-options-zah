<?php
/**
 * @var $this
 * @var $type
 */

defined( 'ABSPATH' ) || exit;
?>
<input type="hidden" name="wpcpo-fields[<?php echo esc_attr( $this->field_id ); ?>][type]" value="<?php echo esc_attr( $type ); ?>"/>
<div class="wpcpo-item-line">
    <label><strong><?php esc_html_e( 'Paragraph', 'wpc-product-options' ); ?> *</strong>
        <textarea class="input-block wpcpo-input-not-empty" name="wpcpo-fields[<?php echo esc_attr( $this->field_id ); ?>][paragraph]"><?php echo esc_attr( $this->get_field_value( 'paragraph' ) ); ?></textarea>
    </label>
</div>
