<?php

if (!defined('ABSPATH'))
  exit;


if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

if (!class_exists('PSFW_frontend_menu')) {

    class PSFW_frontend_menu {
      var $_admin_page = 'new-user-approve-admin';
        protected static $PSFW_front_instance;  
        
        function login_form_for_user(){
          global $psfw_comman,$post;
          if($psfw_comman['psfw_enable_private_store'] == 'yes'){
            if( !is_user_logged_in() ){
              if( is_shop() || is_product_category() || is_product() ){
                if($psfw_comman['psfw_enable_private_whole_website'] == 'yes'){
                  $my_account = get_permalink ( get_option( 'woocommerce_myaccount_page_id' ) );
                  wp_redirect( $my_account );
                  exit();
                }               
              }
              $appended_pags = get_option('wg_pags_select2');
              if (!empty($appended_pags)) {
                if( in_array( get_the_id(), $appended_pags ) ){
                  if($psfw_comman['psfw_enable_private_whole_website'] == 'yes'){
                    $my_account = get_permalink ( get_option( 'woocommerce_myaccount_page_id' ) );
                    wp_redirect( $my_account );
                    exit();
                  }               
                }                
              }
            }    
          }      
        } 

        function PSFW_translate_woocommerce_strings( $translated, $untranslated, $domain ) {
          global $psfw_comman;
          if ( ! is_admin() && 'woocommerce' === $domain ) {
            switch ( $translated ) {         
              case 'Login':         
                $translated = $psfw_comman['psfw_login_form_title'];
              break;         
              case 'Register':         
                $translated = $psfw_comman['psfw_registration_form_title'];
              break;
            }
          }             
          return $translated;         
        }

        function custom_pre_get_posts_query( $q ) {
          global $psfw_comman;
          if($psfw_comman['psfw_enable_private_whole_website'] != 'yes' && $psfw_comman['psfw_disble_price_addtocartbutton'] != "yes"){
            $productsa = get_option('wg_combo');
            $q->set( 'post__not_in', $productsa );
            // if (!empty($productsa) || empty($productsa)) {
            //   $appended_terms = get_option('wg_cats_select2');
            //   if(!empty($appended_terms)){
            //     foreach ($appended_terms as $term) {  
            //       $tax_query = (array) $q->get( 'tax_query' );
            //       $tax_query[] = array(
            //         'taxonomy' => 'product_cat',
            //         'field' => 'term_id',
            //         'terms' => array( $term ),
            //         'operator' => 'NOT IN'
            //       );
            //       $q->set( 'tax_query', $tax_query );
            //     }  
            //   }
            // }
            // if (!empty($productsa) || empty($productsa) && !empty($appended_terms) || empty($appended_terms)) {
            //   $appended_tags = get_option('psfw_tags_select2');
            //   if(!empty($appended_tags)){
            //     foreach ($appended_tags as $tags) {
            //       $tax_query = (array) $q->get( 'tax_query' );
            //       $tax_query[] = array(
            //         'taxonomy' => 'product_tag',
            //         'field' => 'term_id',
            //         'terms' => array( $tags ),
            //         'operator' => 'NOT IN'
            //       );
            //       $q->set( 'tax_query', $tax_query );
            //     }  
            //   } 
            // }
          }
        }    
  

       
        function PSFW_new_user_approve_autologout(){
          if ( is_user_logged_in() ) {
            $current_user = wp_get_current_user();
            $user_id = $current_user->ID;
            if ( get_user_meta($user_id, 'approval_confirmation', true )  === 'confirm_approve' ){ 
              $approved = true;
            }else{
              $approved = false;
            }     
      
            if ( $approved ){ 
                return $redirect_url;
            }else{
              wp_logout();
              wp_clear_auth_cookie();
              return add_query_arg( 'confirm_approve', 'false', get_permalink( get_option('woocommerce_myaccount_page_id') ) );
            }
          }
        }

        function my_custom_function_name($user, $password){
          global $psfw_comman;
          if (isset($user->user_pass)) {            
            if ($user->roles['0'] == 'administrator') {
              return $user;
            }else{
              if (get_user_meta($user->ID, 'approval_confirmation', true) == 'confirm_approve') {
                  return $user;
              }
              return new WP_Error('pending_approval', $psfw_comman['psfw_pending_account_approval'] );
            }          
          }
        }

        function createMyMenus() {
          add_users_page( __( 'Users Pending Approval', 'new-user-panding' ),'Users Pending Approval','manage_options','panding-new-users',array($this, 'psfw_callback_panding'));
          add_users_page( __( 'Approved Users', 'new-user-approve' ),'Approved Users','manage_options','approve-new-users',array($this, 'psfw_callback_approvel'));
          add_users_page( __( 'Denied Users', 'new-user-denied' ),'Denied Users','manage_options','denied-new-users',array($this, 'psfw_callback_denied'));
        }
        
        function psfw_callback_panding( ){
          $exampleListTable = new pafw_panding_List_Table();
          $exampleListTable->prepare_items();                  
          ?>
          <div class="psfw-container">
            <div class="wrap">
              <h2>User Registration Approval</h2>                  
            </div>
            <form  method="post" class="wczp_list_postcode">
              <?php
                $page  = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRIPPED );
                $paged = filter_input( INPUT_GET, 'paged', FILTER_SANITIZE_NUMBER_INT );

                printf( '<input type="hidden" name="page" value="%s" />', $page );
                printf( '<input type="hidden" name="paged" value="%d" />', $paged ); 
              ?>
              <?php $exampleListTable->display(); ?>
            </form>
          </div>
          <?php
        }    

        function psfw_callback_approvel(){
          $exampleListTable = new pafw_approve_List_Table();
          $exampleListTable->prepare_items();
          ?>
          <div class="psfw-container">
            <div class="wrap">
              <h2>Approved Users</h2>                  
            </div>
            <form  method="post" class="wczp_list_postcode">
              <?php
                $page  = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRIPPED );
                $paged = filter_input( INPUT_GET, 'paged', FILTER_SANITIZE_NUMBER_INT );

                printf( '<input type="hidden" name="page" value="%s" />', $page );
                printf( '<input type="hidden" name="paged" value="%d" />', $paged ); 
              ?>
              <?php $exampleListTable->display(); ?>
            </form>
          </div>
          <?php 
        }

        function psfw_callback_denied(){
          $exampleListTable = new pafw_denied_List_Table();
          $exampleListTable->prepare_items();      
          ?>
          <div class="psfw-container">
            <div class="wrap">
              <h2>Denied Users</h2>                  
            </div>
            <form  method="post" class="wczp_list_postcode">
              <?php
                $page  = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRIPPED );
                $paged = filter_input( INPUT_GET, 'paged', FILTER_SANITIZE_NUMBER_INT );
                printf( '<input type="hidden" name="page" value="%s" />', $page );
                printf( '<input type="hidden" name="paged" value="%d" />', $paged ); 
              ?>
              <?php $exampleListTable->display(); ?>
            </form>
          </div>
          <?php
        }

        function update_meta_value(){
          global $psfw_comman;
          if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'panding_to_approve'){
            $user_detail = get_userdata(sanitize_text_field($_REQUEST['user']));
            $admin_email = get_option( 'admin_email' );
            $name = $user_detail->display_name;
            $email = $admin_email;
            $message = "hii, your account has been approve. welcome to our site..!";
            $to = $user_detail->user_email;
            $subject = "Approve Your Account..";
            $headers = 'welcome message';
            // if ($psfw_comman['psfw_account_approve_email'] == 'yes') {
              wp_mail($to, $subject, $message, $headers);
            // }
            update_user_meta( sanitize_text_field($_REQUEST['user']), 'approval_confirmation', 'confirm_approve');
            wp_redirect(admin_url('/users.php?page=panding-new-users'));
            exit();
          }

          if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'denied_to_approve'){
            $user_detail = get_userdata(sanitize_text_field($_REQUEST['user']));
            $admin_email = get_option( 'admin_email' );
            $name = $user_detail->display_name;
            $email = $admin_email;
            $message = "hii, your account has been approve. welcome to our site..!";
            $to = $user_detail->user_email;
            $subject = "Approve Your Account..";
            $headers = 'welcome message';
            // if ($psfw_comman['psfw_account_approve_email'] == 'yes') {
              wp_mail($to, $subject, $message, $headers);
            // }
            update_user_meta( sanitize_text_field($_REQUEST['user']), 'approval_confirmation', 'confirm_approve');
            wp_redirect(admin_url('/users.php?page=denied-new-users'));
            exit();
          }

          if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'approve_to_denied'){
            $user_detail = get_userdata(sanitize_text_field($_REQUEST['user']));
            $admin_email = get_option( 'admin_email' );
            $name = $user_detail->display_name;
            $email = $admin_email;
            $message = "hiii, your accound has been disable.";
            $to = $user_detail->user_email;
            $subject = "Rejected Your Account..";
            $headers = 'reject message';
            // if ($psfw_comman['psfw_account_disale_email'] == 'yes') {
              wp_mail($to, $subject, $message, $headers);
            // }
            update_user_meta( sanitize_text_field($_REQUEST['user']), 'approval_confirmation', 'denied_user');
            wp_redirect(admin_url('/users.php?page=approve-new-users'));
            exit();
          }

          if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'panding_to_denied'){
            $user_detail = get_userdata(sanitize_text_field($_REQUEST['user']));
            $admin_email = get_option( 'admin_email' );
            $name = $user_detail->display_name;
            $email = $admin_email;
            $message = "hiii, your accound has been disable.";
            $to = $user_detail->user_email;
            $subject = "Rejected Your Account..";
            $headers = 'reject message';
            // if ($psfw_comman['psfw_account_disale_email'] == 'yes') {
              wp_mail($to, $subject, $message, $headers);
            // }
            update_user_meta( sanitize_text_field($_REQUEST['user']), 'approval_confirmation', 'denied_user');
            wp_redirect(admin_url('/users.php?page=panding-new-users'));
            exit();
          }
        }

        function psfw_hide_price_addcart_not_logged_in( $price, $product ) {
          global $psfw_comman;           
          if($psfw_comman['psfw_enable_private_whole_website'] != "yes" && $psfw_comman['psfw_disble_price_addtocartbutton'] == "yes"  && !empty(get_option('wg_combo'))){
            if ( ! is_user_logged_in() ) { 
              
              if(in_array(get_the_id() , get_option('wg_combo')) && !empty(get_option('wg_combo'))){
                // print_r(get_the_id());
                $price = '<div><a  style="color:'.$psfw_comman['psfw_login_to_see_price_color'].';" href="'.get_permalink(wc_get_page_id('myaccount')).'">'.__($psfw_comman['psfw_login_to_see_price'], 'bbloomer' ) . '</a></div>';
                remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
                remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
              }else{
                add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
                add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
              }
            }
          }
          return $price;
        }


        function woo_login_regt_style(){
           global $psfw_comman;       
          ?>
          <style type="text/css">
            #customer_login h2{
              color : <?php echo $psfw_comman['woo_login_title_color'] ?>;
            }
          </style>

          <?php 
        }
                 
        function init() {
          global $psfw_comman; 
          add_filter( 'woocommerce_get_price_html',array($this, 'psfw_hide_price_addcart_not_logged_in'), 9999, 2 );  
          add_action( 'wp' ,array($this,'login_form_for_user') );
          add_filter( 'gettext', array($this,'PSFW_translate_woocommerce_strings'), 999, 3 );
          add_action( 'woocommerce_product_query', array($this,'custom_pre_get_posts_query') );
          if($psfw_comman['psfw_approve_registration'] == 'yes'){
            add_filter( 'authenticate', array($this,'my_custom_function_name'), 30, 3);
            add_action( 'woocommerce_registration_redirect', array($this,'PSFW_new_user_approve_autologout'), 2 );  
          }
          add_action('wp_footer' ,array($this,'woo_login_regt_style'));
          add_action( 'admin_menu', array($this,'createMyMenus'));
          add_action( 'init', array($this,'update_meta_value'));
          

        }

        public static function PSFW_front_instance() {
          if (!isset(self::$PSFW_front_instance)) {
              self::$PSFW_front_instance = new self();
              self::$PSFW_front_instance->init();
          }
          return self::$PSFW_front_instance;
        }

    }
    PSFW_frontend_menu::PSFW_front_instance();
}


class pafw_approve_List_Table extends WP_List_Table {
  public function __construct() {
    parent::__construct(
      array(
          'singular' => 'singular_form',
          'plural'   => 'plural_form',
          'ajax'     => false
      )
    );
  }


  public function prepare_items() {
    $columns = $this->get_columns();
    $hidden = $this->get_hidden_columns();
    $sortable = $this->get_sortable_columns();
    $data = $this->table_data();
    usort( $data, array( &$this, 'sort_data' ) );
    $perPage = 5;
    $currentPage = $this->get_pagenum();
    $totalItems = count($data);
    $this->set_pagination_args( array(
      'total_items' => $totalItems,
      'per_page'    => $perPage
    ));
    $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
    $this->_column_headers = array($columns, $hidden, $sortable);
    $this->items = $data;
    $this->process_bulk_action();
  }

  public function get_columns() {
    $columns = array(
      'id'     => 'ID',
      'title'  => 'Users Name',
      'email'  => 'E-mail',
      'role'   => 'User Role',
      'action' => 'Action',
    );
    return $columns;
  }

  public function get_hidden_columns() {
    return array();
  }

  public function get_sortable_columns() {
    return array('id' => array('id', false));
  }

  private function table_data() {
    $data = array();
      $q = new WP_User_Query( 
        array(
          'orderby'  => 'ID',
          'wp_user'   => array(
          'relation'  => 'AND',
          )
        ) 
    );
    $user_query = $q->results;
    foreach ($user_query as $value) {
      $user_info = get_user_meta($value->ID);
      if (isset($user_info['approval_confirmation']['0'])) {
        if($user_info['approval_confirmation']['0'] == 'confirm_approve'){
          $data[] = array(
            'id'    => $value->ID,
            'title' =>  get_avatar($value->user_email).'<a href='. get_edit_user_link( $value->ID ).'>'.$value->display_name.'</a>',
            'email' => '<a href=mailto:'. $value->user_email.'>'. $value->user_email.'</a>' ,
            'role' => $value->roles['0'],
            'action'=>'action',          
          );
        }  
      }
    }
    if ($user_query != 'administrator') {
      return $data;
    }        
  }

  public function column_default( $item, $column_name ) {
    $denied_link =  get_option( 'siteurl' ) . '?action=approve_to_denied&user=' . $item['id'] ;
      switch( $column_name ) {
        case 'id':
          return $item['id'];
        case 'title':
          return $item['title'];
        case 'email':
          return $item['email'];
        case 'role':
          return $item['role'];            
        case 'action':
        $user = new WP_User( $item['id'] );           
        if($user->roles['0'] == 'administrator'){     
          return false;
        }    
        return '<a class="button" href="'.$denied_link.'">Deny</a>';
        default:
        return print_r( $item, true ) ;
      }
  }

  function column_cb($item) {
    return sprintf(
      '<input type="checkbox" name="id[]" value="%s" />', $item['id']
    );    
  }
}

class pafw_denied_List_Table extends WP_List_Table {
  public function __construct() {
    parent::__construct(
      array(
          'singular' => 'singular_form',
          'plural'   => 'plural_form',
          'ajax'     => false
      )
    );
  }


  public function prepare_items() {
    $columns = $this->get_columns();
    $hidden = $this->get_hidden_columns();
    $sortable = $this->get_sortable_columns();
    $data = $this->table_data();
    usort( $data, array( &$this, 'sort_data' ) );
    $perPage = 5;
    $currentPage = $this->get_pagenum();
    $totalItems = count($data);
    $this->set_pagination_args( array(
      'total_items' => $totalItems,
      'per_page'    => $perPage
    ));
    $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
    $this->_column_headers = array($columns, $hidden, $sortable);
    $this->items = $data;
    $this->process_bulk_action();
  }
 

  public function get_columns() {
    $columns = array(
      'id'     => 'ID',
      'title'  => 'Users Name',
      'email'  => 'E-mail',
      'role'   => 'User Role',
      'action' => 'Action',
    );
    return $columns;
  }
 

  public function get_hidden_columns() {
    return array();
  }


  public function get_sortable_columns() {
    return array('id' => array('id', false));
  }


  private function table_data() {
    $data = array();
    $q = new WP_User_Query( 
      array(
        'orderby'  => 'ID',
        'wp_user'    => array(
            'relation'  => 'AND',
        )
      ) 
    );
    $user_query = $q->results;
    foreach ($user_query as $value) {
      $user_info = get_user_meta($value->ID);
      if (isset($user_info['approval_confirmation']['0'])) {  
        if($user_info['approval_confirmation']['0'] == 'denied_user'){
          $data[] = array(
            'id'    => $value->ID,
            'title' =>  get_avatar($value->user_email).'<a href='. get_edit_user_link( $value->ID ).'>'.$value->display_name.'</a>',
            'email' => '<a href=mailto:'. $value->user_email.'>'. $value->user_email.'</a>' ,
            'role' => $value->roles['0'],
            'action'=>'action',          
          );
        }
      }
    }
    return $data;
  }
 

  public function column_default( $item, $column_name ) {
    $approve_link =  get_option( 'siteurl' ) . '?action=denied_to_approve&user=' . $item['id'] ;
    switch( $column_name ) {
      case 'id':
        return $item['id'];
      case 'title':
        return $item['title'];
      case 'email':
        return $item['email'];
      case 'role':
        return $item['role'];
      case 'action':                
        return '<a class="button" href="'.$approve_link.'">Approve</a>';    
      default:
        return print_r( $item, true ) ;
    }
  }


  function column_cb($item) {
    return sprintf(
      '<input type="checkbox" name="id[]" value="%s" />', $item['id']
    );    
  }
}

class pafw_panding_List_Table extends WP_List_Table {
  public function __construct() {
    parent::__construct(
      array(
        'singular' => 'singular_form',
        'plural'   => 'plural_form',
        'ajax'     => false
      )
    );
  }


  public function prepare_items() {
    $columns = $this->get_columns();
    $hidden = $this->get_hidden_columns();
    $sortable = $this->get_sortable_columns();
    $data = $this->table_data();
    usort( $data, array( &$this, 'sort_data' ) );
    $perPage = 5;
    $currentPage = $this->get_pagenum();
    $totalItems = count($data);
    $this->set_pagination_args( array(
      'total_items' => $totalItems,
      'per_page'    => $perPage
    ));
    $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
    $this->_column_headers = array($columns, $hidden, $sortable);
    $this->items = $data;
    $this->process_bulk_action();
  }
 

  public function get_columns() {
    $columns = array(
      'id'     => 'ID',
      'title'  => 'Users Name',
      'email'  => 'E-mail',
      'role'   => 'User Role',
      'action' => 'Action',
    );
    return $columns;
  }
 

  public function get_hidden_columns() {
    return array();
  }


  public function get_sortable_columns() {
    return array('id' => array('id', false));
  }


  private function table_data() {
    $data = array();
    $q = new WP_User_Query( 
      array(
        'orderby'  => 'ID',
        'wp_user'    => array(
            'relation'  => 'AND',                 
        )
      ) 
    );
    $user_query = $q->results;
    foreach ($user_query as $value) {
      $user_info = get_user_meta($value->ID);
      if (isset($user_info['approval_confirmation']['0'])) {
        if($user_info['approval_confirmation']['0'] == 'not_confirm_approve'){
          $data[] = array(
            'id'    => $value->ID,
            'title' =>  get_avatar($value->user_email).'<a href='. get_edit_user_link( $value->ID ).'>'.$value->display_name.'</a>',
            'email' => '<a href=mailto:'. $value->user_email.'>'. $value->user_email.'</a>' ,
            'role' => $value->roles['0'],
            'action'=>'action',          
          );
        }
      }
    }
    return $data;
  }
 

  public function column_default( $item, $column_name ) {
    $approve_link =  get_option( 'siteurl' ) . '?action=panding_to_approve&user=' . $item['id'] ;
    $denied_link =  get_option( 'siteurl' ) . '?action=panding_to_denied&user=' . $item['id'] ;
    switch( $column_name ) {
      case 'id':
        return $item['id'];
      case 'title':
        return $item['title'];
      case 'email':
        return $item['email'];
      case 'role':
        return $item['role'];
      case 'action':                
        return '<a class="button" href="'.$approve_link.'">Approve</a>&nbsp&nbsp<a class="button" href="'.$denied_link.'">Deny</a>';    
      default:
        return print_r( $item, true ) ;
    }
  }


  function column_cb($item) {
    return sprintf(
      '<input type="checkbox" name="id[]" value="%s" />', $item['id']
    );    
  }
}