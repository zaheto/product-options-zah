<?php
/**
 * @var $this
 * @var $type
 */

defined( 'ABSPATH' ) || exit;

$price_type = $this->get_field_value( 'price_type', 'flat' );
?>
<input type="hidden" name="wpcpo-fields[<?php echo esc_attr( $this->field_id ); ?>][type]" value="<?php echo esc_attr( $type ); ?>"/>
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
    <label><?php esc_html_e( 'Date format', 'wpc-product-options' ); ?></label>
    <select name="wpcpo-fields[<?php echo esc_attr( $this->field_id ); ?>][date_format]">
        <option value="F j, Y" <?php selected( $this->get_field_value( 'date_format' ), 'F j, Y' ); ?>><?php echo date_i18n( 'F j, Y' ); ?></option>
        <option value="Y-m-d" <?php selected( $this->get_field_value( 'date_format' ), 'Y-m-d' ); ?>><?php echo date_i18n( 'Y-m-d' ); ?></option>
        <option value="m/d/Y" <?php selected( $this->get_field_value( 'date_format' ), 'm/d/Y' ); ?>><?php echo date_i18n( 'm/d/Y' ); ?></option>
        <option value="d/m/Y" <?php selected( $this->get_field_value( 'date_format' ), 'd/m/Y' ); ?>><?php echo date_i18n( 'd/m/Y' ); ?></option>
    </select>
</div>
<div class="wpcpo-item-line">
    <label><?php esc_html_e( 'Time format', 'wpc-product-options' ); ?></label>
    <select name="wpcpo-fields[<?php echo esc_attr( $this->field_id ); ?>][time_format]">
        <option value="g:i a" <?php selected( $this->get_field_value( 'time_format' ), 'g:i a' ); ?>><?php echo date_i18n( 'g:i a' ); ?></option>
        <option value="g:i A" <?php selected( $this->get_field_value( 'time_format' ), 'g:i A' ); ?>><?php echo date_i18n( 'g:i A' ); ?></option>
        <option value="H:i" <?php selected( $this->get_field_value( 'time_format' ), 'H:i' ); ?>><?php echo date_i18n( 'H:i' ); ?></option>
    </select>
</div>
<div class="wpcpo-item-line">
    <label>
        <input type="checkbox" value="1" name="wpcpo-fields[<?php echo esc_attr( $this->field_id ); ?>][required]" <?php checked( $this->get_field_value( 'required' ), '1' ); ?>> <?php esc_html_e( 'Required', 'wpc-product-options' ); ?>
    </label>
</div>
<div class="wpcpo-item-line">
    <label>
        <input type="checkbox" value="1" name="wpcpo-fields[<?php echo esc_attr( $this->field_id ); ?>][enable_price]" <?php checked( $this->get_field_value( 'enable_price', '0' ), '1' ); ?>> <?php esc_html_e( 'Adjust price', 'wpc-product-options' ); ?>
        <div class="checkbox-show">
            <select class="option-type <?php echo esc_attr( 'type-' . $price_type ); ?>" name="wpcpo-fields[<?php echo esc_attr( $this->field_id ); ?>][price_type]">
                <option value="flat" <?php selected( $price_type, 'flat' ); ?>><?php esc_html_e( 'Flat Fee', 'wpc-product-options' ); ?></option>
                <option value="qty" <?php selected( $price_type, 'qty' ); ?>><?php esc_html_e( 'Quantity Synced', 'wpc-product-options' ); ?></option>
                <option value="custom" <?php selected( $price_type, 'custom' ); ?>><?php esc_html_e( 'Custom Formula', 'wpc-product-options' ); ?></option>
            </select> <span>—</span>
            <span class="wpcpo-price-wrapper hint--right" aria-label="<?php esc_html_e( 'Set a price using a number (eg. "10") or percentage (eg. "10%" of product price)', 'wpc-product-options' ); ?>">
                <input type="text" class="wpcpo-price" name="wpcpo-fields[<?php echo esc_attr( $this->field_id ); ?>][price]" value="<?php echo esc_attr( $this->get_field_value( 'price' ) ); ?>"/>
            </span>
            <span class="wpcpo-price-custom-wrapper hint--right" aria-label="<?php esc_html_e( 'You can use: p (product price); q (quantity); l (string length); w (words count); v (value) in the formula, e.g: (p+2)*q/2', 'wpc-product-options' ); ?>">
                <input type="text" class="wpcpo-price-custom" name="wpcpo-fields[<?php echo esc_attr( $this->field_id ); ?>][custom_price]" value="<?php echo esc_attr( $this->get_field_value( 'custom_price' ) ); ?>" readonly/> This feature is only available on the premium version. Click <a href="https://wpclever.net/downloads/product-options/" target="_blank">here</a> to buy it for just $29!
            </span>
        </div>
    </label>
</div>
