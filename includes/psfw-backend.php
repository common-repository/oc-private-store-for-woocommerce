<?php

if (!defined('ABSPATH'))
  exit;

if (!class_exists('PSFW_admin_menu')) {

    class PSFW_admin_menu {

        protected static $PSFW_instance;

        function psfw_submenu_page() {
            add_menu_page(__( 'woocommerce', 'Private Store' ),'Private Store','manage_options','private-store',array($this, 'psfw_callback'));
        }

        function psfw_callback() {
        	global $psfw_comman;	                           		
        	?>
        	<div class="psfw-container">
	            <form method="post" >
	            	<div class="wrap">
	                	<h2><?php echo __('Woocomerce Private Store Settings','oc-private-store-for-woocommerce');?></h2>
	            	</div>
	                <ul class="tabs">
	                    <li class="tab-link" data-tab="psfw-tab-general"><?php echo __('General','oc-private-store-for-woocommerce');?></li>
	                    <li class="tab-link" data-tab="psfw-tab-registration-form-settings"><?php echo __('Registration Form Settings','oc-private-store-for-woocommerce');?></li>
	                    <li class="tab-link" data-tab="psfw-tab-new-user-registration-settings"><?php echo __('New User Registration Settings','oc-private-store-for-woocommerce');?></li>
	                </ul>
	                <div id="psfw-tab-general" class="psfw-tab-content psfw-current"> 
	                	<h3 class="table_header_text"><?php echo __('General Control Setting','oc-private-store-for-woocommerce');?></h3>
	                    <table class="data_table">
	                        <tbody>
	                            <tr>
	                                <th>
	                                	<label><?php echo __('Enable Private Store','oc-private-store-for-woocommerce');?></label>
	                                </th>
	                                <td>
	                                	<input type="checkbox" name="psfw_comman[psfw_enable_private_store]" value="yes"<?php if ($psfw_comman['psfw_enable_private_store'] == "yes" ) { echo "checked"; } ?>>                     	
	                                	<label><?php echo __('Enable Private Store Registered User','oc-private-store-for-woocommerce');?></label>
	                                </td>
	                            </tr>
	                            <tr>
	                                <th>
	                                	<label><?php echo __('Private Whole Website?','oc-private-store-for-woocommerce');?></label>
	                                </th>
	                                <td>
	                                	<input type="checkbox" class="whole_web_check" name="psfw_comman[psfw_enable_private_whole_website]" value="yes"<?php if ($psfw_comman['psfw_enable_private_whole_website'] == "yes" ) { echo "checked"; } ?>>
	                                	<label><?php echo __('Enable This Option To Private Whole Website For Guest Users','oc-private-store-for-woocommerce');?></label>
	                                </td>
	                            </tr>
	                            <tr class="product_private">
	                                <th>
	                                	<label><?php echo __('Private Products Price and Add to cart Button','oc-private-store-for-woocommerce');?></label>
	                                </th>
	                                <td>
	                                	<input type="checkbox"  name="psfw_comman[psfw_disble_price_addtocartbutton]" value="yes"<?php if ($psfw_comman['psfw_disble_price_addtocartbutton'] == "yes" ) { echo "checked"; } ?>>
	                                	<label><?php echo __('If this Enable option then  below product show but not show price and Add to cart button for guest user And not Check this option Then below product not show for guest user','oc-private-store-for-woocommerce');?></label>
	                                </td>
	                            </tr>
	                           	<tr class="product_private">
	                           		<th>
	                           			<label><?php echo __('Login to see prices text' , 'oc-private-store-for-woocommerce'); ?></label>
	                           		</th>
	                           		<td>
	                                	<input type="text" name="psfw_comman[psfw_login_to_see_price]" value="<?php echo $psfw_comman['psfw_login_to_see_price'];?>">
	                            		
	                                </td>
	                           	</tr>
	                           	<tr>
	                           		<th>
	                           			<label><?php echo __('Login to see prices text Color' , 'oc-private-store-for-woocommerce'); ?></label>
	                           		</th>
	                           		<td>
	                            		<input type="text" class="color-picker" data-alpha="true" data-default-color="<?php echo $psfw_comman['psfw_login_to_see_price_color']; ?>" name="psfw_comman[psfw_login_to_see_price_color]" value="<?php echo $psfw_comman['psfw_login_to_see_price_color']; ?>"/>  
	                            	</td>
	                           	</tr>
	                            <tr class="product_private">
	                                <th>
	                                	<label><?php echo __('Private Products','oc-private-store-for-woocommerce');?></label>
	                                </th>
	                                <td>
	                                	<select id="psfw_select_product" name="psfw_select2[]" multiple="multiple" style="width:60%;">
				                           	<?php 
				                           		$productsa = get_option('wg_combo');
				                           		foreach ($productsa as $value) {
			                              		$productc = wc_get_product( $value );
			                                 		$title = $productc->get_name();
			                                 		$title = ( mb_strlen( $title ) > 50 ) ? mb_substr( $title, 0, 49 ) . '...' : $title; ?>
				                                 		<option value="<?php echo $value;?>" selected="selected"><?php echo $title;?></option>
				                                 	<?php   
				                           		}
				                           	?>
			                           	</select> 
	                                	<p><?php echo __('Private Product for guest User','oc-private-store-for-woocommerce');?></p>
	                                </td>
	                            </tr>
	                            <tr class="product_private">
	                                <th>
	                                	<label><?php echo __('Private Product Categories','oc-private-store-for-woocommerce');?></label>
	                                </th>
	                                <td>
	                                	<select id="psfw_select_cats" name="psfw_cats_select2[]" multiple="multiple" style="width:60%;" disabled>
					                           	<?php
					                           		$appended_terms = get_option('wg_cats_select2');
					                           		if( $appended_terms ) {
										                foreach( $appended_terms as $term_id ) {
										                    $term_name = get_term( $term_id )->name;
										                    $term_name = ( mb_strlen( $term_name ) > 50 ) ? mb_substr( $term_name, 0, 49 ) . '...' : $term_name;
										                    echo '<option value="' . $term_id . '" selected="selected">' . $term_name . '</option>';
										                }
										            }
					                           	?>
				                           </select> 
	                                	<label class="ocsc_pro_link"><?php echo __('Only available in pro version' ,'oc-private-store-for-woocommerce' ); ?> <a href="https://xthemeshop.com/product/oc-private-store-for-woocommerce-pro/" target="_blank">link</a></label>
	                                </td>
	                            </tr>
	                            <tr class="product_private">
	                                <th>
	                                	<label><?php echo __('Private Product Tags','oc-private-store-for-woocommerce');?></label>
	                                </th>
	                                <td>
	                                	<select id="psfw_select_tags" name="psfw_tags_select2[]" multiple="multiple" style="width:60%;" disabled>
				                           	<?php
				                           		$appended_terms = get_option('psfw_tags_select2');
				                           		if( $appended_terms ) {
									                foreach( $appended_terms as $term_id ) {
									                    $term_name = get_term( $term_id )->name;
									                    $term_name = ( mb_strlen( $term_name ) > 50 ) ? mb_substr( $term_name, 0, 49 ) . '...' : $term_name;
									                    echo '<option value="' . $term_id . '" selected="selected">' . $term_name . '</option>';
									                }
									            }
				                           	?>
				                        </select>
	                                	<label class="ocsc_pro_link"><?php echo __('Only available in pro version' ,'oc-private-store-for-woocommerce' ); ?> <a href="https://xthemeshop.com/product/oc-private-store-for-woocommerce-pro/" target="_blank">link</a></label>
	                                </td>
	                            </tr>
	                            <tr>
	                                <th>
	                                	<label><?php echo __('Private Pages','oc-private-store-for-woocommerce');?></label>
	                                </th>
	                                <td>
	                                	<select id="wg_select_pags" name="wg_pags_select2[]" multiple="multiple" style="width:60%;" >
				                           	<?php
				                           		$appended_pags = get_option('wg_pags_select2');

				                           		if( $appended_pags ) {
									                foreach( $appended_pags as $page_id ) {
									                    $term_name = get_page( $page_id )->post_title;
									                    $term_name = ( mb_strlen( $term_name ) > 50 ) ? mb_substr( $term_name, 0, 49 ) . '...' : $term_name;
									                    echo '<option value="' . $page_id . '" selected="selected">' . $term_name . '</option>';
									                }
									            }
				                           	?>
				                           </select>
	                                	<p><?php echo __('Private Pages for guest User','oc-private-store-for-woocommerce');?></p>
	                                </td>
	                            </tr>
	                        </tbody>                         
	                    </table>
	                </div>               
	                <div id="psfw-tab-registration-form-settings" class="psfw-tab-content">
	                	<h3 class="table_header_text"><?php echo __('Private User Login/Registration Form Setting','oc-private-store-for-woocommerce');?></h3>
	                    <table class="data_table">
	                        <tbody>
	                            <tr>
	                            	<th>
	                            		<label><?php echo __('Login Form Title','oc-private-store-for-woocommerce');?></label>	                            		
	                            	</th>
	                            	<td>
	                            		<input type="text" name="psfw_comman[psfw_login_form_title]" value="<?php echo $psfw_comman['psfw_login_form_title'];?>">
	                            		<p><?php echo __('Enter the login form title','oc-private-store-for-woocommerce');?></p>
	                            	</td>
	                            </tr>
	                            <tr>
	                            	<th>
	                            		<label><?php echo __('Registration Form Title','oc-private-store-for-woocommerce');?></label>	                            		
	                            	</th>
	                            	<td>
	                            		<input type="text" name="psfw_comman[psfw_registration_form_title]" value="<?php echo $psfw_comman['psfw_registration_form_title'];?>">
	                            		<p><?php echo __('Enter the registration form title','oc-private-store-for-woocommerce');?></p>
	                            	</td>
	                            </tr>
	                            <tr>
	                            	<th>
	                            		<label><?php echo __('Form Title Color','oc-private-store-for-woocommerce');?></label>	                            		
	                            	</th>
	                            	<td>
	                            		<input type="text" class="color-picker" data-alpha="true" data-default-color="<?php echo $psfw_comman['woo_login_title_color']; ?>" name="psfw_comman[woo_login_title_color]" value="<?php echo $psfw_comman['woo_login_title_color']; ?>"/>  
	                            	</td>
	                            </tr>		
	                                                      
	                        </tbody>                        
	                    </table>
	                </div>
	                <div id="psfw-tab-new-user-registration-settings" class="psfw-tab-content">
	                	<h3 class="table_header_text"><?php echo __('Approve New Users Registration Settings','oc-private-store-for-woocommerce');?></h3>
	                    <table class="data_table">
	                        <tbody>
	                            <tr>
	                                <th>
	                                	<label><?php echo __('Manually Approve New Registration','oc-private-store-for-woocommerce');?></label>
	                                </th>
	                                <td>
	                                	<input type="checkbox" name="psfw_comman[psfw_approve_registration]" value="yes"<?php if($psfw_comman['psfw_approve_registration'] == 'yes'){echo "checked";}?>>
	                                	<p><?php echo __('Disable manual approval of new users registration.','oc-private-store-for-woocommerce');?></p>
	                                </td>
	                            </tr>
	                            
	                            <tr>
	                                <th>
	                                	<label><?php echo __('Message For Pending Account For Approval','oc-private-store-for-woocommerce');?></label>
	                                </th>
	                                <td>
	                                	<input type="text" name="psfw_comman[psfw_pending_account_approval]" value="<?php echo $psfw_comman['psfw_pending_account_approval'];?>">
	                                	<p><?php echo __('Message for users when account is pending for approval.','oc-private-store-for-woocommerce');?></p>
	                                	
	                                </td>
	                            </tr>
	                            <tr>
	                                <th>
	                                	<label><?php echo __('Account Disable Email','oc-private-store-for-woocommerce');?></label>
	                                </th>
	                                <td>
	                                	<input type="checkbox" name="psfw_comman[psfw_account_disale_email]" value="yes" checked disabled>
	                                	
	                                	<label class="ocsc_pro_link"><?php echo __('Only available in pro version' ,'oc-private-store-for-woocommerce' ); ?> <a href="https://xthemeshop.com/product/oc-private-store-for-woocommerce-pro/" target="_blank">link</a></label>
	                                </td>
	                            </tr>
	                            <tr>
	                                <th>
	                                	<label><?php echo __('Account Reject Email Subject','oc-private-store-for-woocommerce');?></label>
	                                </th>
	                                <td>
	                                	<input type="text" name="psfw_comman[psfw_reject_email_subject]" value="<?php echo $psfw_comman['psfw_reject_email_subject'];?>" disabled>
	                                	
	                                	<label class="ocsc_pro_link"><?php echo __('Only available in pro version' ,'oc-private-store-for-woocommerce' ); ?> <a href="https://xthemeshop.com/product/oc-private-store-for-woocommerce-pro/" target="_blank">link</a></label>
	                                </td>
	                            </tr>
	                            <tr>
	                                <th>
	                                	<label><?php echo __('Account Reject Email message','oc-private-store-for-woocommerce');?></label>
	                                </th>
	                                <td>
	                                	<textarea rows="5" cols="30" name="psfw_comman[psfw_reject_email_message]" disabled><?php echo $psfw_comman['psfw_reject_email_message'];?></textarea>
	                                	<label class="ocsc_pro_link"><?php echo __('Only available in pro version' ,'oc-private-store-for-woocommerce' ); ?> <a href="https://xthemeshop.com/product/oc-private-store-for-woocommerce-pro/" target="_blank">link</a></label>
	                                </td>
	                            </tr>
	                            <tr>
	                                <th>
	                                	<label><?php echo __('Account Approve Email','oc-private-store-for-woocommerce');?></label>
	                                </th>
	                                <td>
	                                	<input type="checkbox" name="psfw_account_approve_email" value="yes" checked disabled>
	                                	
	                                	<label class="ocsc_pro_link"><?php echo __('Only available in pro version' ,'oc-private-store-for-woocommerce' ); ?> <a href="https://xthemeshop.com/product/oc-private-store-for-woocommerce-pro/" target="_blank">link</a></label>
	                                </td>
	                            </tr>
	                            <tr>
	                                <th>
	                                	<label><?php echo __('Account Approve Email Subject','oc-private-store-for-woocommerce');?></label>
	                                </th>
	                                <td>
	                                	<input type="text" name="psfw_comman[psfw_approve_email_subject]" value="<?php echo $psfw_comman['psfw_approve_email_subject'];?>" disabled>
	                                
	                                	<label class="ocsc_pro_link"><?php echo __('Only available in pro version' ,'oc-private-store-for-woocommerce' ); ?> <a href="https://xthemeshop.com/product/oc-private-store-for-woocommerce-pro/" target="_blank">link</a></label>
	                                </td>
	                            </tr>
	                            <tr>
	                                <th>
	                                	<label><?php echo __('Account Approve Email message','oc-private-store-for-woocommerce');?></label>
	                                </th>
	                                <td>
	                                	<textarea rows="5" cols="30" name="psfw_comman[psfw_approve_email_message]" disabled><?php echo $psfw_comman['psfw_approve_email_message'];?></textarea>
	                                	<label class="ocsc_pro_link"><?php echo __('Only available in pro version' ,'oc-private-store-for-woocommerce' ); ?> <a href="https://xthemeshop.com/product/oc-private-store-for-woocommerce-pro/" target="_blank">link</a></label>
	                                </td>
	                            </tr>
	                        </tbody>
	                    </table>
	                </div> 
	                <div class="submit_button">
	                    <input type="hidden" name="psfw_private_store" value="psfw_save_option">
	                    <input type="submit" value="Save changes" name="submit" class="button-primary" id="psfw-btn-space">
	                </div>              
	            </form>  
	        </div>
        <?php
        }

        function custom_user_profile_fields($user){?>
    		<table class="form-table">
            	<tr>
                	<th><label>Approval confirmation</label></th>
                	<td>
                    	<select name="approval_confirmation">
                      		<option value="confirm_approve"<?php if(isset($user->ID) && get_user_meta($user->ID,'approval_confirmation',true) == 'confirm_approve'){echo "selected";}?>><?php echo __('Approve Confirm','oc-private-store-for-woocommerce');?></option>
                      		<option value="denied_user"<?php if(isset($user->ID) && get_user_meta($user->ID,'approval_confirmation',true) == 'denied_user'){echo "selected";}?>><?php echo __('Denied Users','oc-private-store-for-woocommerce');?></option>
                      		<option value="not_confirm_approve"<?php if(isset($user->ID) && get_user_meta($user->ID,'approval_confirmation',true) == 'not_confirm_approve'){echo "selected";}?>><?php echo __('Approve Not Confirm','oc-private-store-for-woocommerce');?></option>
                    	</select>
                	</td>
            	</tr>
            	<tr>
            		<th><label>Approval confirmation</label></th>
            		<td>
            			<select name="approval_confirmation">
            				<option value="confirm_approve">Confirmation Approve</option>
            				<option value="confirmation_approval">approve not confirmation</option>
            			</select>
            		</td>
            	</tr>
        	</table>
        	<?php
      	
        }


        function save_custom_user_profile_fields($user_id){
            
            if(!current_user_can('manage_options')){
              	return false;
            }

            update_user_meta( $user_id, 'approval_confirmation', sanitize_text_field($_POST['approval_confirmation']) );

            if ( isset( $_POST['approval_confirmation'] ))
		    {
		        update_user_meta($user_id, 'approval_confirmation', sanitize_text_field($_POST['approval_confirmation']));
		    }
        } 


		function mktbn_user_register( $user_id )
		{

		    if ( isset( $_POST['approval_confirmation'] ))
		    {
		        update_user_meta($user_id, 'approval_confirmation', sanitize_text_field($_POST['approval_confirmation']));
		    }

		}

        function PSFW_recursive_sanitize_text_field( $array ) {
            foreach ( $array as $key => &$value ) {
                if ( is_array( $value ) ) {
                    $value = $this->PSFW_recursive_sanitize_text_field($value);
                }else{
                    $value = sanitize_text_field( $value );
                }
            }
            return $array;
        
        }

        function psfw_save_option() {
	        if( current_user_can('administrator') ) { 
	            if(isset($_REQUEST['psfw_private_store']) && $_REQUEST['psfw_private_store'] == 'psfw_save_option'){


	                //if(!empty($_REQUEST['psfw_comman'])){
	                	/* exit;*/
	                    $isecheckbox = array(
	                        'psfw_enable_private_store',
	                        'psfw_enable_private_whole_website',
	                        'psfw_disble_price_addtocartbutton',
	                        'psfw_include_p_categories',
	                        'psfw_include_p_tags',
	                        'psfw_approve_registration',
	                       
	                        

	                    );

	                    foreach ($isecheckbox as $key_isecheckbox => $value_isecheckbox) {
	                        if(!isset($_REQUEST['psfw_comman'][$value_isecheckbox])){
	                            $_REQUEST['psfw_comman'][$value_isecheckbox] ='no';
	                        }
	                    }                    
	                   
	                   	$psfw_select2 = $this->PSFW_recursive_sanitize_text_field( $_REQUEST['psfw_select2'] );
	            		update_option('wg_combo', $psfw_select2, 'yes');

	            		$psfw_cats_select2 = $this->PSFW_recursive_sanitize_text_field( $_REQUEST['psfw_cats_select2'] );
	        			update_option('wg_cats_select2', $psfw_cats_select2, 'yes');

	        			$psfw_tags_select2 = $this->PSFW_recursive_sanitize_text_field( $_REQUEST['psfw_tags_select2'] );
	        			update_option('psfw_tags_select2', $psfw_tags_select2, 'yes');

	        			$wg_pags_select2 = $this->PSFW_recursive_sanitize_text_field( $_REQUEST['wg_pags_select2'] );
	        			update_option('wg_pags_select2', $wg_pags_select2, 'yes');

	        			$psfw_form_bg_image = $this->PSFW_recursive_sanitize_text_field( $_REQUEST['psfw_form_bg_image'] );
	        			update_option('psfw_form_bg_image', $psfw_form_bg_image, 'yes');
	         
	                    foreach ($_REQUEST['psfw_comman'] as $key_psfw_comman => $value_psfw_comman) {
	                        update_option($key_psfw_comman, sanitize_text_field($value_psfw_comman), 'yes');
	                    }
	                 
	                wp_redirect( admin_url( '/admin.php?page=private-store' ) );
	                exit;     
	            }
	        }
	    }

	    function PSFW_product_ajax() {
            $return = array();
            $post_types = array( 'product','product_variation');
            $search_results = new WP_Query( array( 
                's'=> sanitize_text_field($_GET['q']),
                'post_status' => 'publish',
                'post_type' => $post_types,
                'posts_per_page' => -1,
                'meta_query' => array(
	                array(
	                    'key' => '_stock_status',
	                    'value' => 'instock',
	                    'compare' => '=',
	                )
	            )
            ));

            if( $search_results->have_posts() ) :
               	while( $search_results->have_posts() ) : $search_results->the_post();   
                  	$productc = wc_get_product( $search_results->post->ID );
                  	if ( $productc && $productc->is_in_stock() && $productc->is_purchasable() ) {
						$title = $search_results->post->post_title;
						$price = $productc->get_price_html();
						$return[] = array( $search_results->post->ID, $title, $price);   
                  	}
               	endwhile;
            endif;
            echo json_encode( $return );
            die;
      	}

      	function PSFW_cats_ajax() {
      
         	$return = array();

            $product_categories = get_terms( 'product_cat', $cat_args );

            if( !empty($product_categories) ){
                foreach ($product_categories as $key => $category) {
                    $category->term_id;
                    $title = ( mb_strlen( $category->name ) > 50 ) ? mb_substr( $category->name, 0, 49 ) . '...' : $category->name;
                    $return[] = array( $category->term_id, $title );
                }
            }

            echo json_encode( $return );
            die;
      	}

      	function PSFW_tags_ajax() {
      
         	$return = array();

         	$args = array(
			    'number'     => '',
			    'orderby'    => '',
			    'order'      => '',
			    'hide_empty' => '',
			    'include'    => ''
			);

			$product_tags = get_terms( 'product_tag', $args );

            if( !empty($product_tags) ){
                foreach ($product_tags as $key => $tag) {
                    $tag->term_id;
                    $title = ( mb_strlen( $tag->name ) > 50 ) ? mb_substr( $tag->name, 0, 49 ) . '...' : $tag->name;
                    $return[] = array( $tag->term_id, $title );
                }
            }

            echo json_encode( $return );
            die;
      	}

      	function PSFW_pages_ajax() {
      
         	$return = array();

            $pages = get_pages();

            if( !empty($pages) ){
                foreach ($pages as $key => $page) {
                    $page->ID;
                    $title = $page->post_title;
                    $return[] = array( $page->ID, $title );
                }
            }

            echo json_encode( $return );
            die;
      	}

        function init() {
        	global $psfw_comman;
            add_action( 'admin_menu',  array($this, 'psfw_submenu_page'));
            add_action( 'init',  array($this, 'psfw_save_option'));
		    add_action( 'wp_ajax_nopriv_wg_product_ajax',array($this, 'PSFW_product_ajax') );
		    add_action( 'wp_ajax_wg_product_ajax', array($this, 'PSFW_product_ajax') );
		 	add_action( 'wp_ajax_nopriv_wg_cats_ajax',array($this, 'PSFW_cats_ajax') );
		    add_action( 'wp_ajax_wg_cats_ajax', array($this, 'PSFW_cats_ajax') );
		    add_action( 'wp_ajax_nopriv_wg_tags_ajax',array($this, 'PSFW_tags_ajax') );
		    add_action( 'wp_ajax_wg_tags_ajax', array($this, 'PSFW_tags_ajax') );
		    add_action( 'wp_ajax_nopriv_wg_pages_ajax',array($this, 'PSFW_pages_ajax') );
		    add_action( 'wp_ajax_wg_pages_ajax', array($this, 'PSFW_pages_ajax') );
		    if($psfw_comman['psfw_approve_registration'] == 'yes'){
	            add_action( 'show_user_profile', array($this,'custom_user_profile_fields') );
	            add_action( 'edit_user_profile', array($this,'custom_user_profile_fields') );
	            add_action( 'user_new_form', array($this,'custom_user_profile_fields') );
	            add_action( 'edit_user_profile_update', array($this,'save_custom_user_profile_fields') );
	            add_action( 'personal_options_update', array($this,'save_custom_user_profile_fields') );
        		add_action( 'user_register', array($this,'mktbn_user_register'), 10, 1 );
          	}
        }

        public static function PSFW_instance() {
            if (!isset(self::$PSFW_instance)) {
                self::$PSFW_instance = new self();
                self::$PSFW_instance->init();
            }
            return self::$PSFW_instance;
        }
    }
    PSFW_admin_menu::PSFW_instance();
}

?>