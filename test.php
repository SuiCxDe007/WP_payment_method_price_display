<?php
/*
Plugin Name: Product Price Display
Plugin URI: https://www.example.com/product-price-display/
Description: Adds the product price in small text to the product page.
Version: 1.3
Author: Your Name
Author URI: https://www.example.com/
License: GPL2
*/
// Add the product boxes with logo and price
function product_price_display() {
    global $product;
   global $product;

    $logo1 = get_option('logo1');
    $logo2 = get_option('logo2');
    $logo3 = get_option('logo3');
    $logo4 = get_option('logo4');
    if ( $product->get_price() ) {
        $price = wc_price( $product->get_price() );
        // $logo1 = '<img src="https://www.dronelanka.com/wp-content/uploads/2023/04/BANK-TRANSFER-1-min.png" width="75" alt="Logo 1">';
        // $logo2 = '<img src="https://www.dronelanka.com/wp-content/uploads/2023/04/BANK-TRANSFER-1-min.png" width="75" alt="Logo 2">';
        // $logo3 = '<img src="https://www.dronelanka.com/wp-content/uploads/2023/04/BANK-TRANSFER-min.png" width="75" alt="Logo 3">';
        // $logo4 = '<img src="https://www.dronelanka.com/wp-content/uploads/2023/04/BANK-TRANSFER-min.png" width="75" alt="Logo 4">';
        echo '<div class="product-row">';
        echo '<div class="product-box">' . $logo1 . '<span class="product-price">' . $price . '</span></div>';
        echo '<div class="product-box">' . $logo2 . '<span class="product-price">' . $price . '</span></div>';
        echo '</div>';
        echo '<div class="product-row">';
        echo '<div class="product-box">' . $logo3 . '<span class="product-price">' . $price . '</span></div>';
        echo '<div class="product-box">' . $logo4 . '<span class="product-price">' . $price . '</span></div>';
        echo '</div>';
    }
}
add_action( 'woocommerce_single_product_summary', 'product_price_display', 20 );

// Add styles for the product boxes
function product_box_styles() {
    echo '<style>.product-row { display: flex; flex-wrap: wrap; justify-content: space-between; margin-top: 7px; }';
    echo '.product-box { width: calc(50% - 5px); text-align: center; margin-bottom: 2px; border: 1px solid red; padding: 2px;}';
    echo '.product-box:nth-child(2n+1) { margin-right: 10px; }';
    echo '.product-box:hover { box-shadow: 0 4px 8px rgba(255, 0, 0, 0.3); }</style>';
}
add_action( 'wp_head', 'product_box_styles' );

// Adjust margin top of short description
function adjust_short_desc_margin_top() {
    echo '<style>.woocommerce-product-details__short-description { margin-top: 7px; }</style>';
}
add_action( 'wp_head', 'adjust_short_desc_margin_top' );

function wpdocs_register_custom_settings_page() {
    add_options_page(
        'Custom Settings', // Page Title
        'Custom Settings', // Menu Title
        'manage_options', // Capability
        'custom_settings', // Menu Slug
        'custom_settings_page' // Callback function to render the page
    );
}
add_action('admin_menu', 'wpdocs_register_custom_settings_page');

// Callback function to render the custom settings page
function custom_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form method="post" action="options.php" enctype="multipart/form-data">
            <?php
                settings_fields('custom_settings_group');
                do_settings_sections('custom_settings');
            ?>
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="logo1">Logo 1</label>
                    </th>
                    <td>
                        <input type="file" name="logo1" id="logo1" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="logo2">Logo 2</label>
                    </th>
                    <td>
                        <input type="file" name="logo2" id="logo2" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="logo3">Logo 3</label>
                    </th>
                    <td>
                        <input type="file" name="logo3" id="logo3" />
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Register a new setting in the WordPress database
function register_custom_settings() {
    register_setting(
        'custom_settings_group', // Option group
        'logo1' // Option name
    );
    register_setting(
        'custom_settings_group', // Option group
        'logo2' // Option name
    );
    register_setting(
        'custom_settings_group', // Option group
        'logo3' // Option name
    );

    register_setting(
        'custom_settings_group', // Option group
        'logo4' // Option name
    );
}
add_action('admin_init', 'register_custom_settings');
