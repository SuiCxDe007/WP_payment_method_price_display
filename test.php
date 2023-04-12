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
    $logo1 = get_option('logo1');
    $logo2 = get_option('logo2');
    $logo3 = get_option('logo3');
    $logo4 = get_option('logo4');
    if ( $product->get_price() ) {
        $price = wc_price( $product->get_price() );
         $logox = '<img src="' . esc_url( $logo1 ) . '" width="75" alt="Logo 1">';
        // $logo2 = '<img src="https://www.dronelanka.com/wp-content/uploads/2023/04/BANK-TRANSFER-1-min.png" width="75" alt="Logo 2">';
        // $logo3 = '<img src="https://www.dronelanka.com/wp-content/uploads/2023/04/BANK-TRANSFER-min.png" width="75" alt="Logo 3">';
        // $logo4 = '<img src="https://www.dronelanka.com/wp-content/uploads/2023/04/BANK-TRANSFER-min.png" width="75" alt="Logo 4">';
        echo '<div class="product-row">';

        echo '<div class="product-box">' . $logox . '<span class="product-price">' . $price . '</span></div>';
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
// Register a new setting in the WordPress database
// Register a new setting in the WordPress database
function register_custom_settings() {
    register_setting(
        'custom_settings_group', // Option group
        'logo1', // Option name
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
                        <label for="logo1">Logo 1</label>
                    </th>
                    <td>
                        <?php $logo1 = get_option('logo1'); ?>
                        <input type="hidden" name="logo1" value="<?php echo esc_attr($logo1); ?>" />
                        <?php if ($logo1) { ?>
                            <img src="<?php echo esc_url($logo1); ?>" alt="Logo 1" /><br />
                        <?php } ?>
                        <input type="file" name="logo1_file" id="logo1_file" />
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Handle uploaded image and update the 'logo1' option in the database
function handle_logo1_upload() {
    if (!empty($_FILES['logo1_file']['name'])) {
        $file = $_FILES['logo1_file'];
        $upload_dir = wp_upload_dir();
        $upload_path = $upload_dir['path'] . '/';
        $file_name = wp_unique_filename($upload_path, $file['name']);
        $file_path = $upload_path . $file_name;
        move_uploaded_file($file['tmp_name'], $file_path);
        $logo1_url = $upload_dir['url'] . '/' . $file_name;
        update_option('logo1', $logo1_url);
  update_option('logo2', 'death came');
    }
}
add_action('admin_init', 'handle_logo1_upload');