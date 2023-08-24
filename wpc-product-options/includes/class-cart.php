<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Wpcpo_Cart' ) ) {
	class Wpcpo_Cart {
		protected static $instance = null;

		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function __construct() {
			// Load cart data per page load
			add_filter( 'woocommerce_get_cart_item_from_session', [ $this, 'get_cart_item_from_session' ], 20, 2 );

			// Validation
			add_filter( 'woocommerce_add_to_cart_validation', [ $this, 'add_to_cart_validation' ], 10, 2 );

			// Add item data to the cart
			add_filter( 'woocommerce_add_cart_item_data', [ $this, 'add_cart_item_data' ], 10, 2 );

			// Get item data to display
			add_filter( 'woocommerce_get_item_data', [ $this, 'get_item_data' ], 10, 2 );

			// Add meta to order
			add_filter( 'woocommerce_checkout_create_order_line_item', [ $this, 'order_line_item' ], 10, 3 );

			// Before calculate totals
			add_action( 'woocommerce_before_mini_cart_contents', [ $this, 'before_mini_cart_contents' ], 999999 );
			add_action( 'woocommerce_before_calculate_totals', [ $this, 'before_calculate_totals' ], 999999 );

			// Cart item price & subtotal
			add_filter( 'woocommerce_cart_item_price', [ $this, 'cart_item_price' ], 999999, 2 );
			add_filter( 'woocommerce_cart_item_subtotal', [ $this, 'cart_item_subtotal' ], 999999, 2 );
		}

		private function clean_custom_price( $custom_price ) {
			return preg_replace( '/[^0-9\+\-\*\/\(\)\.vpqlw]/', '', $custom_price );
		}

		private function word_count( $string ) {
			$formatted_string = preg_replace( '/\s+/', ' ', trim( wp_strip_all_tags( $string ) ) );
			$words            = explode( ' ', $formatted_string );

			return apply_filters( 'wpcpo_word_count', count( $words ), $string );
		}

		private function get_custom_price( $custom_price, $quantity, $product_price, $value ) {
			return 0;
		}

		public function add_to_cart_validation( $passed, $product_id ) {
			if ( isset( $_REQUEST['order_again'] ) ) {
				return $passed;
			}

			$_product = wc_get_product( $product_id );

			if ( ( $fields = Wpcpo_Frontend::get_required_fields( $_product ) ) && ! empty( $fields ) ) {
				$has_required_fields = true;
				$post_data           = $_POST;

				foreach ( $fields as $key => $field ) {
					if ( isset( $field['options'] ) && ! empty( $field['options'] ) ) {
						$has_required_options = false;

						foreach ( $field['options'] as $option_key => $option ) {
							if ( isset( $post_data[ $option_key ] ) && ( isset( $post_data[ $option_key ]['value'] ) && $post_data[ $option_key ]['value'] != '' ) ) {
								$has_required_options = true;
								break;
							}
						}

						if ( isset( $post_data[ $key ] ) && ( isset( $post_data[ $key ]['value'] ) && $post_data[ $key ]['value'] != '' ) ) {
							$has_required_options = true;
						}

						if ( ! $has_required_options ) {
							$has_required_fields = false;
							break;
						}
					} else {
						if ( ! isset( $post_data[ $key ] ) || ! isset( $post_data[ $key ]['value'] ) || $post_data[ $key ]['value'] == '' ) {
							$has_required_fields = false;
							break;
						}
					}
				}

				if ( ! $has_required_fields ) {
					wc_add_notice( esc_html__( 'You cannot add this product to the cart.', 'wpc-product-options' ), 'error' );

					return false;
				}
			}

			return $passed;
		}

		public function add_cart_item_data( $cart_item_data, $product_id ) {
			if ( isset( $_POST ) && ! empty( $product_id ) ) {
				$post_data = $_POST;
			} else {
				return $cart_item_data;
			}

			$cart_item_data['wpcpo-options'] = [];

			foreach ( $post_data as $key => $data ) {
				if ( preg_match( '/^wpcpo-/', $key ) && isset( $data['value'] ) && $data['value'] !== '' ) {
					// check file
					if ( isset( $data['type'] ) && $data['type'] === 'file' ) {
						if ( ! empty( $_FILES[ $key ] ) && ! empty( $_FILES[ $key ]['name'] ) ) {
							$upload                            = $this->handle_upload( $_FILES[ $key ] );
							$data['value']                     = basename( wc_clean( $upload['url'] ) );
							$data['file_url']                  = wc_clean( $upload['url'] );
							$cart_item_data['wpcpo-options'][] = $data;
						}

						continue;
					}

					$cart_item_data['wpcpo-options'][] = $data;
					unset( $_POST[ $key ] );
				}
			}

			return $cart_item_data;
		}

		public function handle_upload( $file ) {
			include_once( ABSPATH . 'wp-admin/includes/file.php' );
			include_once( ABSPATH . 'wp-admin/includes/media.php' );

			add_filter( 'upload_dir', [ $this, 'upload_dir' ] );

			$upload = wp_handle_upload( $file, [ 'test_form' => false ] );

			remove_filter( 'upload_dir', [ $this, 'upload_dir' ] );

			return $upload;
		}

		public function upload_dir( $path_data ) {
			global $woocommerce;

			if ( empty( $path_data['subdir'] ) ) {
				$path_data['path']   = $path_data['path'] . '/wpcpo_product_options_uploads/' . md5( $woocommerce->session->get_customer_id() );
				$path_data['url']    = $path_data['url'] . '/wpcpo_product_options_uploads/' . md5( $woocommerce->session->get_customer_id() );
				$path_data['subdir'] = '/wpcpo_product_options_uploads/' . md5( $woocommerce->session->get_customer_id() );
			} else {
				$subdir              = '/wpcpo_product_options_uploads/' . md5( $woocommerce->session->get_customer_id() );
				$path_data['path']   = str_replace( $path_data['subdir'], $subdir, $path_data['path'] );
				$path_data['url']    = str_replace( $path_data['subdir'], $subdir, $path_data['url'] );
				$path_data['subdir'] = str_replace( $path_data['subdir'], $subdir, $path_data['subdir'] );
			}

			return apply_filters( 'woocommerce_product_addons_upload_dir', $path_data );
		}

		public function get_cart_item_from_session( $cart_item, $session_values ) {
			if ( ! empty( $session_values['wpcpo-options'] ) ) {
				$cart_item['wpcpo-options'] = $session_values['wpcpo-options'];
			}

			return $cart_item;
		}

		public function get_item_data( $other_data, $cart_item ) {
			if ( ! empty( $cart_item['wpcpo-options'] ) ) {
				foreach ( $cart_item['wpcpo-options'] as $option ) {
					$data = [
						'name'    => $option['title'],
						'value'   => isset( $option['label'] ) && $option['label'] !== '' ? $option['label'] : $option['value'],
						'display' => '',
					];

					if ( ! empty( $option['type'] ) ) {
						if ( $option['type'] === 'color-picker' ) {
							$data['value'] = '<span class="box-color-picker" style="background: ' . $option['value'] . '"></span> ' . $option['value'];
						}

						if ( ( $option['type'] === 'image-radio' ) && ! empty( $option['image'] ) ) {
							$data['value'] = '<span class="box-image-radio">' . wp_get_attachment_image( $option['image'] ) . '</span>';
						}
					}

					if ( ! empty( $option['display_price'] ) ) {
						$data['display'] = $data['value'] . ' (' . wc_price( $option['display_price'] ) . ')';
					}

					$other_data[] = $data;
				}
			}

			return $other_data;
		}

		public function order_line_item( $item, $cart_item_key, $values ) {
			if ( ! empty( $values['wpcpo-options'] ) ) {
				foreach ( $values['wpcpo-options'] as $option ) {
					if ( isset( $option['value'] ) && $option['value'] !== '' ) {
						$option_value = isset( $option['label'] ) && $option['label'] !== '' ? $option['label'] : $option['value'];
						$item->add_meta_data( $option['title'], $option_value );
					}
				}
			}
		}

		public function before_mini_cart_contents() {
			WC()->cart->calculate_totals();
		}

		public function before_calculate_totals( $cart_object ) {
			if ( ! defined( 'DOING_AJAX' ) && is_admin() ) {
				// This is necessary for WC 3.0+
				return;
			}

			foreach ( $cart_object->cart_contents as $cart_item_key => $cart_item ) {
				if ( ! empty( $cart_item['wpcpo-options'] ) ) {
					$quantity         = (float) apply_filters( 'wpcpo_cart_item_qty', $cart_item['quantity'], $cart_item );
					$price_bc         = $price = (float) apply_filters( 'wpcpo_cart_item_price', $cart_item['data']->get_price(), $cart_item );
					$regular_price_bc = $regular_price = (float) apply_filters( 'wpcpo_cart_item_regular_price', $cart_item['data']->get_regular_price(), $cart_item );
					$sale_price_bc    = $sale_price = (float) apply_filters( 'wpcpo_cart_item_sale_price', $cart_item['data']->get_sale_price(), $cart_item );

					if ( isset( WC()->cart->cart_contents[ $cart_item_key ]['woosb_price'] ) ) {
						$price_bc = $regular_price_bc = $sale_price_bc = (float) WC()->cart->cart_contents[ $cart_item_key ]['woosb_price'];
					}

					if ( isset( WC()->cart->cart_contents[ $cart_item_key ]['wooco_price'] ) ) {
						$price_bc = $regular_price_bc = $sale_price_bc = (float) WC()->cart->cart_contents[ $cart_item_key ]['wooco_price'];
					}

					// Save the price before price type calculations to be used later
					$cart_item['wpcpo_price_before_calc']         = apply_filters( 'wpcpo_price_before_calc', $price_bc, $cart_item );
					$cart_item['wpcpo_regular_price_before_calc'] = apply_filters( 'wpcpo_regular_price_before_calc', $regular_price_bc, $cart_item );
					$cart_item['wpcpo_sale_price_before_calc']    = apply_filters( 'wpcpo_sale_price_before_calc', $sale_price_bc, $cart_item );

					foreach ( $cart_item['wpcpo-options'] as $key => $field ) {
						$price_type = ! empty( $field['price_type'] ) ? $field['price_type'] : '';
						$price_val  = ! empty( $field['price'] ) ? $field['price'] : 0;

						switch ( $price_type ) {
							case 'flat':
								if ( strpos( $price_val, '%' ) !== false ) {
									$calc_price    = $price_bc * (float) $price_val / 100;
									$price         += $calc_price / $quantity;
									$regular_price += $calc_price / $quantity;
									$sale_price    += $calc_price / $quantity;

									$cart_item['wpcpo-options'][ $key ]['display_price'] = $calc_price;
								} else {
									$price         += $price_val / $quantity;
									$regular_price += $price_val / $quantity;
									$sale_price    += $price_val / $quantity;

									$cart_item['wpcpo-options'][ $key ]['display_price'] = $price_val;
								}

								break;
							case 'custom':
								$calc_price    = $this->get_custom_price( $field['custom_price'], $quantity, $price_bc, $field['value'] );
								$price         += $calc_price / $quantity;
								$regular_price += $calc_price / $quantity;
								$sale_price    += $calc_price / $quantity;

								$cart_item['wpcpo-options'][ $key ]['display_price'] = $calc_price;

								break;
							default:
								// qty
								if ( strpos( $price_val, '%' ) !== false ) {
									$calc_price    = $price_bc * (float) $price_val / 100;
									$price         += $calc_price;
									$regular_price += $calc_price;
									$sale_price    += $calc_price;

									$cart_item['wpcpo-options'][ $key ]['display_price'] = $calc_price * $quantity;
								} else {
									$price         += (float) $price_val;
									$regular_price += (float) $price_val;
									$sale_price    += (float) $price_val;

									$cart_item['wpcpo-options'][ $key ]['display_price'] = $price_val * $quantity;
								}

								break;
						}
					}

					$cart_item['wpcpo_price'] = $price;
					$cart_item['data']->set_price( $price );
					$cart_item['data']->set_regular_price( $regular_price );
					$cart_item['data']->set_sale_price( $sale_price );

					// should remove in PHP9
					//$cart_item['data']->wpcpo = 'yes';

					// save $cart_item
					WC()->cart->cart_contents[ $cart_item_key ] = $cart_item;
				}
			}
		}

		public function cart_item_price( $price, $cart_item ) {
			if ( ! empty( $cart_item['wpcpo_price'] ) ) {
				$price = (float) $cart_item['wpcpo_price'];

				if ( ! empty( $cart_item['woosb_price'] ) ) {
					$price += (float) $cart_item['woosb_price'];
				}

				if ( ! empty( $cart_item['wooco_price'] ) ) {
					$price += (float) $cart_item['wooco_price'];
				}

				return wc_price( $price );
			}

			return $price;
		}

		public function cart_item_subtotal( $subtotal, $cart_item = null ) {
			if ( ! empty( $cart_item['wpcpo_price'] ) ) {
				$price = (float) $cart_item['wpcpo_price'];

				if ( ! empty( $cart_item['woosb_price'] ) ) {
					$price += (float) $cart_item['woosb_price'];
				}

				if ( ! empty( $cart_item['wooco_price'] ) ) {
					$price += (float) $cart_item['wooco_price'];
				}

				$subtotal = wc_price( $price * (float) $cart_item['quantity'] );

				if ( wc_tax_enabled() && WC()->cart->display_prices_including_tax() && ! wc_prices_include_tax() ) {
					$subtotal .= ' <small class="tax_label">' . WC()->countries->inc_tax_or_vat() . '</small>';
				}
			}

			return $subtotal;
		}
	}
}

return Wpcpo_Cart::instance();
