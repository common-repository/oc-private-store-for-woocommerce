<?php
if (!defined('ABSPATH'))
  exit;

if (!class_exists('PSFW_comman')) {

    class PSFW_comman {

        protected static $instance;

        public static function instance() {
            if (!isset(self::$instance)) {
                self::$instance = new self();
                self::$instance->init();
            }
             return self::$instance;
        }
         function init() {
            global $psfw_comman;
            $optionget = array(              
                'psfw_enable_private_store' => 'yes',
                'psfw_enable_private_whole_website' => 'yes',
                'psfw_include_p_categories' => '',
                'psfw_include_p_tags' => '',
                'psfw_login_form_title' => 'Login User',
                'psfw_registration_form_title' => 'Register User',
                'psfw_approve_registration' => 'yes',
                'psfw_pending_account_approval' => 'your account is not active please, wait for admin approval...!',
                'psfw_account_disale_email' => 'yes',
                'psfw_reject_email_subject' => 'Rejected Your Account..',
                'psfw_reject_email_message' => 'hiii, your accound has been disable.',
                'psfw_approve_email_subject' => 'Approve Your Account..',
                'psfw_approve_email_message' => 'hii, your account has been approve. welcome to our site..!',
                'psfw_account_approve_email' => 'yes',
                'psfw_disble_price_addtocartbutton'=>'yes',
                'psfw_login_to_see_price'=>'Login to see prices',
                'woo_login_title_color'=>'#000000',
                'psfw_login_to_see_price_color'=>'#000000',

            );
           
            foreach ($optionget as $key_optionget => $value_optionget) {
               $psfw_comman[$key_optionget] = get_option( $key_optionget,$value_optionget );
            }
        }
    }

    PSFW_comman::instance();
}
?>