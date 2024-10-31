<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'psfw_kit' ) ) {
	class psfw_kit {
		protected static $_plugins = array(
			'edit-variations-in-cart-for-woocommerce'             => array(
				'name' => 'Edit Variations In Cart For WooCommerce',
				'slug' => 'edit-variations-in-cart-for-woocommerce',
				'file' => 'WooCommerce-edit-variations-in-cart-main.php'
			),
			'oc-private-store-for-woocommerce'         => array(
				'name' => 'OC Private Store For Woocommerce',
				'slug' => 'oc-private-store-for-woocommerce',
				'file' => 'private-store-main.php'
			),
			'sales-count-product-for-woocommerce'            => array(
				'name' => 'Sales Count Product for WooCommerce',
				'slug' => 'sales-count-product-for-woocommerce',
				'file' => 'Sales_count_poduct_main.php'
			),
			'elegant-free-shipping-bar-for-woocommerce'            => array(
				'name' => 'Elegant Free Shipping Bar for WooCommerce',
				'slug' => 'elegant-free-shipping-bar-for-woocommerce',
				'file' => 'Elegant_Free_Shipping_Bar_main.php'
			),
			'products-attachments-for-woocommerce'              => array(
				'name' => 'Products Attachments For Woocommerce',
				'slug' => 'products-attachments-for-woocommerce',
				'file' => 'product_attechment_main.php'
			),
			'pre-order-for-woocommerce'           => array(
				'name' => 'Pre Order for WooCommerce',
				'slug' => 'pre-order-for-woocommerce',
				'file' => 'pre_order_main.php'
			),
			'store-opening-hours-for-woocommerce'             => array(
				'name' => 'Store Opening Hours For WooCommerce',
				'slug' => 'store-opening-hours-for-woocommerce',
				'file' => 'store_opening_hours_main.php'
			),
			'custom-fields-registration-for-woocommerce'                   => array(
				'name' => 'Custom Fields Registration For Woocommerce',
				'slug' => 'custom-fields-registration-for-woocommerce',
				'file' => 'custom_fields_registration_main.php'
			),
			'add-gift-wrapper-for-woocommerce'                   => array(
				'name' => 'Add Gift Wrapper For Woocommerce',
				'slug' => 'add-gift-wrapper-for-woocommerce',
				'file' => 'woo-gift-wrapper-main.php'
			)
		);

		function __construct() {
			// admin scripts
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

			// settings page
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		}

		function admin_scripts() {
		}

		function admin_menu() {
			add_submenu_page( 'private-store', esc_html__( 'Essential Kit', 'psfw' ), esc_html__( 'Essential Kit', 'psfw' ), 'manage_options', 'psfw_kit', array(
				$this,
				'settings_page'
			) );
		}

		function settings_page() {
			add_thickbox();
			?>
            <div class="wrap">
                <h1>Essential Kit</h1>
                <div class="wp-list-table widefat plugin-install-network">
					<?php
					if ( ! function_exists( 'plugins_api' ) ) {
						include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
					}

					if ( isset( $_GET['action'], $_GET['plugin'] ) && ( $_GET['action'] === 'activate' ) && wp_verify_nonce( $_GET['_wpnonce'], 'activate-plugin_' . $_GET['plugin'] ) ) {
						activate_plugin( $_GET['plugin'], '', false, true );
					}

					if ( isset( $_GET['action'], $_GET['plugin'] ) && ( $_GET['action'] === 'deactivate' ) && wp_verify_nonce( $_GET['_wpnonce'], 'deactivate-plugin_' . $_GET['plugin'] ) ) {
						deactivate_plugins( $_GET['plugin'], '', false, true );
					}

					$updated      = false;
					$plugins_info = get_site_transient( 'psfw_kit_plugins_info' );

					foreach ( self::$_plugins as $_plugin ) {
						if ( ! isset( $plugins_info[ $_plugin['slug'] ] ) ) {
							$_plugin_info = plugins_api(
								'plugin_information',
								array(
									'slug'   => $_plugin['slug'],
									'fields' => array(
										'short_description',
										'version',
										'active_installs',
										'downloaded',
									),
								)
							);

							if ( ! is_wp_error( $_plugin_info ) ) {
								$plugin_info                      = array(
									'name'              => $_plugin_info->name,
									'slug'              => $_plugin_info->slug,
									'version'           => $_plugin_info->version,
									'rating'            => $_plugin_info->rating,
									'num_ratings'       => $_plugin_info->num_ratings,
									'downloads'         => $_plugin_info->downloaded,
									'last_updated'      => $_plugin_info->last_updated,
									'homepage'          => $_plugin_info->homepage,
									'short_description' => $_plugin_info->short_description,
									'active_installs'   => $_plugin_info->active_installs,
								);
								$plugins_info[ $_plugin['slug'] ] = $plugin_info;
								$updated                          = true;
							} else {
								$plugin_info = array(
									'name' => $_plugin['name'],
									'slug' => $_plugin['slug']
								);
							}
						} else {
							$plugin_info = $plugins_info[ $_plugin['slug'] ];
						}

						$details_link = network_admin_url(
							'plugin-install.php?tab=plugin-information&amp;plugin=' . $plugin_info['slug'] . '&amp;TB_iframe=true&amp;width=600&amp;height=550'
						);
						?>
                        <div class="plugin-card <?php echo esc_attr( $_plugin['slug'] ); ?>"
                             id="<?php echo esc_attr( $_plugin['slug'] ); ?>">
                            <div class="plugin-card-top">
                                <a href="<?php echo esc_url( $details_link ); ?>" class="thickbox">
                                    <img src="<?php echo esc_url('https://ps.w.org/'.$_plugin['slug'].'/assets/icon-128x128.png'); ?>"
                                         class="plugin-icon" alt=""/>
                                </a>
                                <div class="name column-name">
                                    <h3>
                                        <a class="thickbox" href="<?php echo esc_url( $details_link ); ?>">
											<?php echo $plugin_info['name']; ?>
                                        </a>
                                    </h3>
                                </div>
                                <div class="action-links">
                                    <ul class="plugin-action-buttons">
                                        <li>
											<?php if ( $this->is_plugin_installed( $_plugin ) ) {
												if ( $this->is_plugin_active( $_plugin ) ) {
													?>
                                                    <a href="<?php echo esc_url( $this->deactivate_plugin_link( $_plugin ) ); ?>"
                                                       class="button deactivate-now">
														<?php esc_html_e( 'Deactivate', 'psfw' ); ?>
                                                    </a>
													<?php
												} else {
													?>
                                                    <a href="<?php echo esc_url( $this->activate_plugin_link( $_plugin ) ); ?>"
                                                       class="button activate-now">
														<?php esc_html_e( 'Activate', 'psfw' ); ?>
                                                    </a>
													<?php
												}
											} else { ?>
                                                <a href="<?php echo esc_url( $this->install_plugin_link( $_plugin ) ); ?>"
                                                   class="install-now button wpckit-install-now">
													<?php esc_html_e( 'Install Now', 'psfw' ); ?>
                                                </a>
											<?php } ?>
                                        </li>
                                        <li>
                                            <a href="<?php echo esc_url( $details_link ); ?>"
                                               class="thickbox open-plugin-details-modal"
                                               aria-label="<?php echo esc_attr( sprintf( esc_html__( 'More information about %s', 'psfw' ), $plugin_info['name'] ) ); ?>"
                                               data-title="<?php echo esc_attr( $plugin_info['name'] ); ?>">
												<?php esc_html_e( 'More Details', 'psfw' ); ?>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="desc column-description">
                                    <p><?php echo esc_html( $plugin_info['short_description'] ); ?></p>
                                </div>
                            </div>
							<?php 
								echo '<div class="plugin-card-bottom">';

								if ( isset( $plugin_info['rating'], $plugin_info['num_ratings'] ) ) { ?>
                                    <div class="vers column-rating">
										<?php
										wp_star_rating(
											array(
												'rating' => $plugin_info['rating'],
												'type'   => 'percent',
												'number' => $plugin_info['num_ratings'],
											)
										);
										?>
                                        <span class="num-ratings">(<?php echo esc_html( number_format_i18n( $plugin_info['num_ratings'] ) ); ?>)</span>
                                    </div>
								<?php }

								if ( isset( $plugin_info['version'] ) ) { ?>
                                    <div class="column-updated">
										<?php echo esc_html__( 'Version', 'psfw' ) . ' ' . $plugin_info['version']; ?>
                                    </div>
								<?php }

								if ( isset( $plugin_info['active_installs'] ) ) { ?>
                                    <div class="column-downloaded">
										<?php echo number_format_i18n( $plugin_info['active_installs'] ) . esc_html__( '+ Active Installations', 'psfw' ); ?>
                                    </div>
								<?php }

								if ( isset( $plugin_info['last_updated'] ) ) { ?>
                                    <div class="column-compatibility">
                                        <strong><?php esc_html_e( 'Last Updated:', 'psfw' ); ?></strong>
                                        <span><?php printf( esc_html__( '%s ago', 'psfw' ), esc_html( human_time_diff( strtotime( $plugin_info['last_updated'] ) ) ) ); ?></span>
                                    </div>
								<?php }

								echo '</div>';
							 ?>
                        </div>
						<?php
					}

					if ( $updated ) {
						set_site_transient( 'psfw_kit_plugins_info', $plugins_info, 24 * HOUR_IN_SECONDS );
					}
					?>
                </div>
            </div>
			<?php
		}

		public function is_plugin_installed( $plugin ) {
			return file_exists( WP_PLUGIN_DIR . '/' . $plugin['slug'] . '/' . $plugin['file'] );
		}

		public function is_plugin_active( $plugin) {
			return is_plugin_active( $plugin['slug'] . '/' . $plugin['file'] );
		}

		public function install_plugin_link( $plugin ) {
			return wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . $plugin['slug'] ), 'install-plugin_' . $plugin['slug'] );
		}

		public function activate_plugin_link( $plugin) {
			
			return wp_nonce_url( admin_url( 'admin.php?page=psfw_kit&action=activate&plugin=' . $plugin['slug'] . '/' . $plugin['file'] . '#' . $plugin['slug'] ), 'activate-plugin_' . $plugin['slug'] . '/' . $plugin['file'] );
			
		}

		public function deactivate_plugin_link( $plugin ) {
			return wp_nonce_url( admin_url( 'admin.php?page=psfw_kit&action=deactivate&plugin=' . $plugin['slug'] . '/' . $plugin['file'] . '#' . $plugin['slug'] ), 'deactivate-plugin_' . $plugin['slug'] . '/' . $plugin['file'] );
		}
	}

	new psfw_kit();
}
