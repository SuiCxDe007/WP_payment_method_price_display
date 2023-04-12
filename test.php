<?php
/*
Plugin Name: Product Price Display
Plugin URI: https://www.example.com/product-price-display/
Description: Adds the product price in small text to the product page.
Version: 1.7
Author: Your Name
Author URI: https://www.example.com/
License: GPL2
*/
// Add the product boxes with logo and price
function product_price_display() {
global $product;
$logo1 = get_option('logo5');
$logo2 = get_option('logo2');
$logo3 = get_option('logo3');
$logo4 = get_option('logo4');
if ( $product->get_price() ) {
    $price = wc_price( $product->get_price() );
    echo '<div class="product-row">';
    if ( !empty($logo1) ) {
        $logoImg1 = '<img src="' . esc_url( $logo1 ) . '" width="75" alt="Logo 1">';
        echo '<div class="product-box">' . $logoImg1 . '<span class="product-price">' . $price . '1</span></div>';
    }
    if ( !empty($logo2) ) {
        $logoImg2 = '<img src="' . esc_url( $logo2 ) . '" width="75" alt="Logo 2">';
        echo '<div class="product-box">' . $logoImg2 . '<span class="product-price">' . $price . '2</span></div>';
    }
    if ( !empty($logo3) ) {
        $logoImg3 = '<img src="' . esc_url( $logo3 ) . '" width="75" alt="Logo 3">';
        echo '<div class="product-box">' . $logoImg3 . '<span class="product-price">' . $price . '3</span></div>';
    }
    if ( !empty($logo4) ) {
        $logoImg4 = '<img src="' . esc_url( $logo4 ) . '" width="75" alt="Logo 4">';
        echo '<div class="product-box">' . $logoImg4 . '<span class="product-price">' . $price . '4</span></div>';
    }
    echo '</div>';
}

}
add_action( 'woocommerce_single_product_summary', 'product_price_display', 20 );

// Add styles for the product boxes
function product_box_styles() {
    echo '<style>.product-row { display: flex; flex-wrap: wrap; justify-content: space-between; margin-top: 7px; }';
    echo '.product-box { width: calc(50% - 5px); text-align: center; margin-bottom: 5px; border: 1px solid red; padding: 2px;}';
    echo '.product-box:nth-child(2n+1) { margin-right: 10px; }';
    echo '.product-box:hover { box-shadow: 0 4px 8px rgba(255, 0, 0, 0.3); }';
    echo '.product-row .product-box:first-child:last-child { width: 100%; }';
    echo '</style>';
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
// Register a new setting in the WordPress database
// Register a new setting in the WordPress database
function register_custom_settings() {
    register_setting(
        'custom_settings_group', // Option group
        'logoOne', // Option name
        array(
            'sanitize_callback' => 'esc_url_raw' // Sanitize the uploaded image URL
        )
    );
}

add_action('admin_init', 'register_custom_settings');

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
                        <label for="logoOne">Logo 1</label>
                    </th>
                    <td>
                        <?php $logoOne = get_option('logoOne'); ?>
                        <input type="hidden" name="logoOne" value="<?php echo esc_attr($logoOne); ?>" />
                        <?php if ($logoOne) { ?>
                            <img src="<?php echo esc_url($logoOne); ?>" alt="Logo 1" /><br />
                                   <input type="file" name="logoOne_file" id="logoOne_file" />
                        <?php } ?>

                    </td>
                </tr>
                 <tr>
                    <th scope="row">
                        <label for="logo5">Logo 1</label>
                    </th>
                    <td>
                        <?php $logo5 = get_option('logo5'); ?>
                        <input type="hidden" name="logo5" value="<?php echo esc_attr($logo5); ?>" />
                        <?php if ($logo5) { ?>
                            <img src="<?php echo esc_url($logo5); ?>" alt="Logo 1" /><br />
                        <?php } ?>
                        <input type="file" name="logo5_file" id="logo5_file" />
                    </td>
                </tr>
                      <tr>
                    <th scope="row">
                        <label for="logo2">Logo 2</label>
                    </th>
                    <td>
                        <?php $logo2 = get_option('logo2'); ?>
                        <input type="hidden" name="logo2" value="<?php echo esc_attr($logo2); ?>" />
                        <?php if ($logo2) { ?>
                            <img src="<?php echo esc_url($logo2); ?>" alt="Logo 2" /><br />
                        <?php } ?>
                        <input type="file" name="logo2_file" id="logo2_file" />
                    </td>
                </tr>
                      <tr>
                    <th scope="row">
                        <label for="logo3">Logo 3</label>
                    </th>
                    <td>
                        <?php $logo3 = get_option('logo3'); ?>
                        <input type="hidden" name="logo3" value="<?php echo esc_attr($logo3); ?>" />
                        <?php if ($logo3) { ?>
                            <img src="<?php echo esc_url($logo3); ?>" alt="Logo 3" /><br />
                        <?php } ?>
                        <input type="file" name="logo3_file" id="logo3_file" />
                    </td>
                </tr>
                      <tr>
                    <th scope="row">
                        <label for="logo4">Logo 4</label>
                    </th>
                    <td>
                        <?php $logo4 = get_option('logo4'); ?>
                        <input type="hidden" name="logo4" value="<?php echo esc_attr($logo4); ?>" />
                        <?php if ($logo4) { ?>
                            <img src="<?php echo esc_url($logo4); ?>" alt="Logo 4" /><br />
                        <?php } ?>
                        <input type="file" name="logo4_file" id="logo4_file" />
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Handle uploaded image and update the 'logoOne' option in the database
function handle_logo_uploads() {
$logos = array('LOGO','logo5', 'logo2', 'logo3', 'logo4');
foreach ($logos as $logo) {
if (!empty($_FILES[$logo . '_file']['name'])) {
$file = $_FILES[$logo . '_file'];
$upload_dir = wp_upload_dir();
$upload_path = $upload_dir['path'] . '/';
$file_name = wp_unique_filename($upload_path, $file['name']);
$file_path = $upload_path . $file_name;
move_uploaded_file($file['tmp_name'], $file_path);
$logo_url = $upload_dir['url'] . '/' . $file_name;
update_option($logo, $logo_url);
}
}
}
add_action('admin_init', 'handle_logo_uploads');