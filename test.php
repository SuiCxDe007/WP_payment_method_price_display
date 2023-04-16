<?php
/*
Plugin Name: Product Price Display
Plugin URI: https://www.example.com/product-price-display/
Description: Adds the product price in small text to the product page.
Version: 1.6
Author: Your Name
Author URI: https://www.example.com/
License: GPL2
*/
// Add the product boxes with logo and price
require_once ABSPATH . '/wp-admin/includes/image.php';
function product_price_display()
{
    global $product;
    $logos = array(
        'logo' => get_option('logo') ,
        'logo2' => get_option('logo2') ,
        'logo3' => get_option('logo3') ,
        'logo4' => get_option('logo4') ,
        'logo5' => get_option('logo5') ,
    );

    if ($product->get_price())
    {
        $price = wc_price($product->get_price());
        $keys = array_keys($logos);
        echo '<div class="product-row">';
        foreach ($keys as $index => $key)
        {
            $logo = $logos[$key];
            $discount = get_option($key . '_discount', 0);
            $name = get_option($key . '_payment_name', 0);
            $description = get_option($key . '_description', 0);
            $lastPrice = $product->get_price() * (1 - ($discount / 100));
            if (!empty($logo))
            {
                $logoImg = '<img src="' . esc_url($logo) . '" width="100" alt="Logo ' . ($index + 1) . '">';
                echo '<div class="product-box" data-discount="' . esc_attr($discount) . '" data-name="' . esc_attr($name) . '" data-description="' . esc_attr($description) . '">' . $logoImg . '<span class="product-price">' . wc_price($lastPrice) . '</span></div>';
            }
        }
        echo '</div>';
    }
}

add_action('woocommerce_single_product_summary', 'product_price_display', 20);

// Add styles for the product boxes
function product_box_styles()
{
    echo '<style>
        .product-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-top: 7px;
        }
        .product-box {
            width: calc(50% - 5px);
            text-align: center;
            margin-bottom: 5px;
            border: 1px solid red;
            padding: 2px;
            border-radius: 5px;
            cursor: pointer;
            position: relative;
        }
        .product-box:nth-child(2n+1) {
            margin-right: 7px;
        }
        .product-box:hover {
            box-shadow: 0 4px 8px rgba(255, 0, 0, 0.3);
            font-weight: bold;
        }
        .product-row .product-box:first-child:last-child {
            width: 100%;
        }
.modal {
            display: none;
            position: fixed;
            z-index: 99999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
        }
        .modal-header {
            display: flex;
            align-items: left;
            justify-content: space-between;
            background-color: #fff;
              font-size: 16px;
            border-bottom: 1px solid #ccc;
            padding:0;
            margin-bottom:5px;
        }
        .modal-header h5 {
            font-weight: bold;

        }

            .modal p {
                font-size:16px
            }


        .modal-content {
            background-color: #fff;
            padding: 20px;
            padding-bottom:5px;
            border-radius: 5px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: 500px;
            width: 30%;
            max-height: 80%;
            overflow-y: auto;
        }
        .modal-header .close {
    color: #8B0000;
    font-size: 30px;
    padding:0;
              padding-top: 10px;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.3s ease-in-out;
    margin-right: 5px;
}
        .close {
            color: #8B0000;
            font-size: 30px;

            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease-in-out;
        }
        .close:hover {
            color: #000;
        }
.discount-circle {
  display: inline-block;
  height: 30px;
  border-radius: 15px;
  background-color: #ff0000;
  color: #fff;
  text-align: center;
  line-height: 30px;
  font-weight: bold;
  font-size: 16px;
  margin-left: 10px;
  padding: 0 10px;
}
    /* Media query for mobile devices */
    @media only screen and (max-width: 768px) {
        .modal-content {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            max-width: 80%;
            margin: 0;
            padding: 20px;
            border: 1px solid #888;
        }
    }
    </style>';

    // Add JavaScript to show the discount modal
    echo '<script>
        jQuery(document).ready(function() {
            jQuery(".product-box").click(function() {
                var discount = jQuery(this).data("discount");
                var name = jQuery(this).data("name");
                var description = jQuery(this).data("description");
                var modal = "<div class=\"modal\"><div class=\"modal-content\"><div class=\"modal-header\"><h5>" + name + "</h5><span class=\"close\">&times;</span></div><p>" + description + "</p><p>Discount Percentage: <span class=\"discount-circle\">" + discount + "%</span></p></div></div>";
                jQuery("body").append(modal);
                jQuery(".modal").show();
                jQuery(".close").click(function() {
                    jQuery(".modal").remove();
                });
                    jQuery(".modal").click(function() {
                    jQuery(".modal").remove();
                });
            });
        });
    </script>';
}

add_action('wp_head', 'product_box_styles');

// Adjust margin top of short description
function adjust_short_desc_margin_top()
{
    echo '<style>.woocommerce-product-details__short-description { margin-top: 7px; }</style>';
}
add_action('wp_head', 'adjust_short_desc_margin_top');

function wpdocs_register_custom_settings_page()
{
    add_options_page('Custom Settings', // Page Title
    'Custom Settings', // Menu Title
    'manage_options', // Capability
    'custom_settings', // Menu Slug
    'custom_settings_page'
    // Callback function to render the page
    );
}
add_action('admin_menu', 'wpdocs_register_custom_settings_page');
// Register a new setting in the WordPress database
// Register a new setting in the WordPress database
function register_custom_settings()
{
    register_setting('custom_settings_group', // Option group
    'logoOne', // Option name
    array(
        'sanitize_callback' => 'esc_url_raw'
        // Sanitize the uploaded image URL

    ));
}

add_action('admin_init', 'register_custom_settings');

// Callback function to render the custom settings page
function custom_settings_page()
{
?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form method="post" action="options.php" enctype="multipart/form-data">
            <?php
    settings_fields('custom_settings_group');
    do_settings_sections('custom_settings');
?>
            <table class="form-table">
                <?php $logos = array(
        'logo',
        'logo2',
        'logo3',
        'logo4',
        'logo5'
    ); ?>
                <?php foreach ($logos as $logo)
    { ?>
                    <tr>
                        <th scope="row">
                            <label for="<?php echo esc_attr($logo); ?>"><?php echo esc_html($logo); ?></label>
                        </th>
                        <td>
                            <?php $logo_url = get_option($logo); ?>
                            <input type="hidden" name="<?php echo esc_attr($logo); ?>" value="<?php echo esc_attr($logo_url); ?>" />
                            <?php if ($logo_url)
        { ?>
                                <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($logo); ?>" /><br />
                                <button type="button" class="delete-logo" data-logo="<?php echo esc_attr($logo); ?>"><?php _e('Delete', 'textdomain'); ?></button>
                            <?php
        } ?>
                            <input type="file" name="<?php echo esc_attr($logo); ?>_file" id="<?php echo esc_attr($logo); ?>_file" />
                            <br>
                            <label for="<?php echo esc_attr($logo); ?>_discount">Discount Percentage</label><br>
                            <input type="number" min="0" max="100" step="0.1" name="<?php echo esc_attr($logo); ?>_discount" id="<?php echo esc_attr($logo); ?>_discount" value="<?php echo esc_attr(get_option($logo . '_discount', 0)); ?>" />

                            <br>
                            <label for="<?php echo esc_attr($logo); ?>_payment_name">Payment Method Name</label>
                            <input type="text" name="<?php echo esc_attr($logo); ?>_payment_name" id="<?php echo esc_attr($logo); ?>_payment_name" value="<?php echo esc_attr(get_option($logo . '_payment_name', '')); ?>" />
                            <br>
                            <label for="<?php echo esc_attr($logo); ?>_description">Description</label>
                            <input type="text" name="<?php echo esc_attr($logo); ?>_description" id="<?php echo esc_attr($logo); ?>_description" value="<?php echo esc_attr(get_option($logo . '_description', '')); ?>" />
                            <br>



                        </td>
                    </tr>

                <?php
    } ?>
            </table>
    <script>
    jQuery(document).ready(function($) {
        $('.delete-logo').click(function(e) {
            e.preventDefault();
            var logo = $(this).data('logo');
            if (confirm('Are you sure you want to delete the ' + logo + ' logo?')) {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    data: {
                        'action': 'delete_logo',
                        'logo': logo
                    },
                    success: function(response) {
                        // Reload the page after successful deletion
                        location.reload();
                    },
                     error: function(jqXHR, textStatus, errorThrown) {
        // Code to handle error response
        console.log('AJAX Error:', textStatus, errorThrown);
    }
                });
            }
        });
    });
</script>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Handle uploaded image and update the 'logoOne' option in the database
function handle_logo_uploads()
{
    $logos = array(
        'logo',
        'logo2',
        'logo3',
        'logo4',
        'logo5'
    );
    foreach ($logos as $logo)
    {
        $logo_url = get_option($logo);
        if (!empty($_FILES[$logo . '_file']['name']))
        {
            $file = $_FILES[$logo . '_file'];
            $upload_dir = wp_upload_dir();
            $upload_path = $upload_dir['path'] . '/';
            $file_name = wp_unique_filename($upload_path, $file['name']);
            $file_path = $upload_path . $file_name;
            move_uploaded_file($file['tmp_name'], $file_path);
            $logo_url = $upload_dir['url'] . '/' . $file_name;
            update_option($logo, $logo_url);
        }

        // Update logo discount
        if (isset($_POST[$logo . '_discount']))
        {
            $discount = floatval($_POST[$logo . '_discount']);

            update_option($logo . '_discount', $discount);
        }

        // Update payment method name
        if (isset($_POST[$logo . '_payment_name']))
        {
            $payment_name = $_POST[$logo . '_payment_name'];

            update_option($logo . '_payment_name', $payment_name);
        }

        // Update payment method description
        if (isset($_POST[$logo . '_description']))
        {
            $payment_method_description = sanitize_textarea_field($_POST[$logo . '_description']);

            update_option($logo . '_description', $payment_method_description);
        }
    }
}

add_action('admin_init', 'handle_logo_uploads');

add_action('wp_ajax_delete_logo', 'my_delete_logo_function');

function my_delete_logo_function()
{
    $logo = $_POST['logo'];
    delete_option($logo);
    delete_option($logo . '_discount');
    $logo_url = get_option($logo);
    $upload_dir = wp_upload_dir();
    $file_path = str_replace($upload_dir['url'], $upload_dir['path'], $logo_url);
    $file_path = str_replace(array(
        '/',
        '\\'
    ) , DIRECTORY_SEPARATOR, $file_path);
    if (file_exists($file_path))
    {
        unlink($file_path);
    }
    wp_die();
}

