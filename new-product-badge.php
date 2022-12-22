<?php
/*
Plugin Name: New Product Badge
Plugin URI: https://chat.openai.com/chat/
Description: Add a new product badge to your WooCommerce products. By ChatGPT.
Version: 1.0
Author: ChatGPT
Author URI: https://chat.openai.com/chat/
License: GPLv2 or later
Text Domain: new-product-badge
*/

class New_Product_Badge {
    public function __construct() {
        // Register the settings
        add_filter( 'woocommerce_product_settings', array( $this, 'array_register_settings' ) );

        // Add the new product badge to the products
        add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'display_new_product_badge' ) );
        add_action( 'woocommerce_single_product_summary', array( $this, 'display_new_product_badge' ), 5 );
    }

    public function array_register_settings($settings){
        $settings[] = array(
                'title' => __( 'New Product Badge Settings', 'woocommerce' ),
                'type'  => 'title',
                'id'    => 'new_product_badge_section',
            );
            $settings[] = array(
                'name' => __( 'Number of days that a product is considered new', 'new-product-badge' ),
                'type' => 'number',
                'id' => 'new_product_badge_days',
                'css' => 'min-width:300px;',
                'std' => '7', // Default value
                'desc' => __( 'Enter the number of days that a product is considered new. This is used to determine whether to display the new product badge on the product page.', 'new-product-badge' ),
                'desc_tip' => true,
            );
            $settings[] = array(
                'name' => __( 'Text of the new product badge', 'new-product-badge' ),
                'type' => 'text',
                'id' => 'new_product_badge_text',
                'css' => 'min-width:300px;',
                'std' => 'New', // Default value
                'desc' => __( 'Enter the text to display in the new product badge. This is used to label products as new on the product page.', 'new-product-badge' ),
                'desc_tip' => true,
            );
            $settings[] = array(
                'name' => __( 'CSS styling for the new product badge', 'new-product-badge' ),
                'type' => 'textarea',
                'id' => 'new_product_badge_css',
                'css' => 'min-width:300px;',
                'std' => 'background-color: #ff0000; color: #ffffff; font-size: 12px;', // Default value
                'desc' => __( 'Enter the CSS styling for the new product badge. This is used to style the new product badge on the product page.', 'new-product-badge' ),
                'desc_tip' => true,
            );
            $settings[] = array(
                'type' => 'sectionend',
                'id'   => 'new_product_badge_section',
            );
        return $settings;
    }

    public function new_product_badge_section_callback() {
        echo __( 'Customize the appearance and behavior of the new product badge.', 'new-product-badge' );
    }

    public function new_product_badge_days_callback() {
        // Get the current value of the setting
        $value = get_option( 'new_product_badge_days', 30 );
    
        // Display the field
        echo '<input type="number" name="new_product_badge_days" value="' . esc_attr( $value ) . '" min="1" step="1">';
    }
    
    public function new_product_badge_text_callback() {
        // Get the current value of the setting
        $value = get_option( 'new_product_badge_text', 'New!' );
    
        // Display the field
        echo '<input type="text" name="new_product_badge_text" value="' . esc_attr( $value ) . '">';
    }

    public function display_new_product_badge() {
        global $product;
    
        // Get the number of days that a product is considered new from the plugin settings
        $days_new = get_option( 'new_product_badge_days', 30 );
    
        // Get the current time and the product's publish date
        $now = time();
        $published_date = get_the_time( 'U', $product->get_id() );
    
        // Calculate the difference in seconds between the current time and the product's publish date
        $difference = $now - $published_date;
    
        // Calculate the number of days that have passed since the product was published
        $days_passed = floor( $difference / ( 60 * 60 * 24 ) );
    
        // If the number of days passed is less than the number of days that a product is considered new, display the new product badge
        if ( $days_passed < $days_new ) {
            // Get the text of the new product badge from the plugin settings
            $text = get_option( 'new_product_badge_text', 'New!' );

            // Get the CSS styling of the new product badge
            $new_product_badge_css = get_option( 'new_product_badge_css' );

            echo '<span class="new-product-badge" style="' . esc_attr( $new_product_badge_css ) . '">'.$text.'</span>';
        }
    }
    
    
    

    // The rest of the plugin code will go here
}
$new_product_badge = new New_Product_Badge();
