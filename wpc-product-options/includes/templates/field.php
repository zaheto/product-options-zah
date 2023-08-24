<?php
/**
 * @var $this
 * @var $type
 * @var $file_type
 * @var $file_display
 */

defined( 'ABSPATH' ) || exit;
$type_str = ucwords( str_replace( '-', ' ', str_replace( 'appearance-', '', $type ) ) );
?>
<div class="wpcpo-item wpcpo-item-<?php echo esc_attr( $type ); ?>">
    <div class="wpcpo-item-header">
        <span class="wpcpo-item-move ui-sortable-handle">move</span>
        <span class="wpcpo-item-label"><span class="title"><?php echo esc_html( $this->get_field_value( 'title', $type_str ) ); ?></span><span class="required"><?php echo esc_html( $this->get_field_value( 'required' ) ? '*' : '' ); ?></span><span class="type"><?php echo esc_html( $type_str ); ?></span></span>
        <span class="wpcpo-item-remove"><?php esc_html_e( 'remove', 'wpc-product-options' ); ?></span>
    </div>
    <div class="wpcpo-item-content">
        <!--
        <div class="nav-tab-wrapper">
            <a href="#tab-<?php echo esc_attr( $this->field_id ); ?>-general"
               class="nav-tab nav-tab-active"><?php esc_html_e( 'General', 'wpc-product-options' ); ?></a>
            <a href="#tab-<?php echo esc_attr( $this->field_id ); ?>-displaying"
               class="nav-tab"><?php esc_html_e( 'Displaying', 'wpc-product-options' ); ?></a>
        </div>
        -->
        <div id="tab-<?php echo esc_attr( $this->field_id ); ?>-general" class="nav-tab-content active">
			<?php include $file_type; ?>
        </div>
        <div id="tab-<?php echo esc_attr( $this->field_id ); ?>-displaying" class="nav-tab-content">
			<?php include $file_display; ?>
        </div>
    </div>
</div>
