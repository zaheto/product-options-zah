<?php
/**
 * @var $this
 * @var $type
 */

defined( 'ABSPATH' ) || exit;
?>
<input type="hidden" name="wpcpo-fields[<?php echo esc_attr( $this->field_id ); ?>][type]" class="wpcpo-type-val" value="<?php echo esc_attr( $type ); ?>"/>
<div class="wpcpo-item-line">
    <label><strong><?php esc_html_e( 'Title', 'wpc-product-options' ); ?> *</strong>
        <input type="text" class="input-block sync-label wpcpo-input-not-empty" name="wpcpo-fields[<?php echo esc_attr( $this->field_id ); ?>][title]" value="<?php echo esc_attr( $this->get_field_value( 'title', ucwords( str_replace( '-', ' ', $type ) ) ) ); ?>">
    </label>
</div>
<div class="wpcpo-item-line">
    <label>
        <input type="checkbox" value="1" name="wpcpo-fields[<?php echo esc_attr( $this->field_id ); ?>][hide_title]" <?php checked( $this->get_field_value( 'hide_title' ), '1' ); ?>> <?php esc_html_e( 'Hide title', 'wpc-product-options' ); ?>
    </label>
</div>
<div class="wpcpo-item-line">
    <label>
        <input type="checkbox" value="1" name="wpcpo-fields[<?php echo esc_attr( $this->field_id ); ?>][show_desc]" <?php checked( $this->get_field_value( 'show_desc' ), '1' ); ?>> <?php esc_html_e( 'Add description', 'wpc-product-options' ); ?>
        <textarea class="input-block checkbox-show" name="wpcpo-fields[<?php echo esc_attr( $this->field_id ); ?>][desc]"><?php echo esc_attr( $this->get_field_value( 'desc' ) ); ?></textarea>
    </label>
</div>
<div class="wpcpo-item-line">
    <label>
        <input type="checkbox" value="1" name="wpcpo-fields[<?php echo esc_attr( $this->field_id ); ?>][required]" <?php checked( $this->get_field_value( 'required' ), '1' ); ?>> <?php esc_html_e( 'Required', 'wpc-product-options' ); ?>
    </label>
</div>
<div class="wpcpo-item-line">
	<?php $this->get_options_field( $type ); ?>
</div>
<div class="wpcpo-item-line">
    <label>
        <input type="checkbox" value="1" name="wpcpo-fields[<?php echo esc_attr( $this->field_id ); ?>][default_value]" <?php checked( $this->get_field_value( 'default_value' ), '1' ); ?>> <?php esc_html_e( 'Default value', 'wpc-product-options' ); ?>
        <input type="text" class="checkbox-show" name="wpcpo-fields[<?php echo esc_attr( $this->field_id ); ?>][value]" value="<?php echo esc_attr( $this->get_field_value( 'value' ) ); ?>"/>
    </label>
</div>