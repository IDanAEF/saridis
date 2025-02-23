<?php 
    define('THEME_IMAGES', get_bloginfo('template_url').'/assets/images/');
    define('CLEAR_PATH', preg_replace('/\\?.*/', '', $_SERVER['REQUEST_URI']));
    define('FULL_PATH', preg_replace('/\\?.*/', '', get_home_url().CLEAR_PATH));
    define('USER_ID', get_current_user_id());
    define('IS_AUTH', USER_ID != 0);

    add_theme_support('menus');

    function saridisAddScripts() {
        wp_enqueue_style( 'saridis_fancybox_style', get_template_directory_uri() . '/assets/css/jquery.fancybox.min.css' );
        wp_enqueue_style( 'saridis_main_style', get_template_directory_uri() . '/assets/css/style.min.css' );
        wp_enqueue_style( 'saridis_custom_style', get_template_directory_uri() . '/custom.css' );
        
        wp_enqueue_script( 'toproof_jquery_script', get_template_directory_uri() . '/assets/js/jquery.min.js', array(), null, true );
        wp_enqueue_script( 'toproof_fancybox_script', get_template_directory_uri() . '/assets/js/jquery.fancybox.min.js', array(), null, true );
        wp_enqueue_script( 'saridis_main_script', get_template_directory_uri() . '/assets/js/script.js', array(), null, true );
        wp_enqueue_script( 'saridis_custom_script', get_template_directory_uri() . '/custom.js', array(), null, true );

        wp_dequeue_style('wp-block-library');
    }

    add_action( 'wp_enqueue_scripts', 'saridisAddScripts' );

    add_action( 'template_redirect', function(){
        ob_start( function( $buffer ){
            $buffer = str_replace('type="text/javascript"', '', $buffer );
            $buffer = str_replace('type="text/css"', '', $buffer );
            $buffer = str_replace("type='text/javascript'", '', $buffer );
            $buffer = str_replace("type='text/css'", '', $buffer );
            $buffer = preg_replace("~<meta (.*?)\/>~", "<meta $1>", $buffer);
            $buffer = preg_replace("~<link (.*?)\/>~", "<link $1>", $buffer);
            $buffer = preg_replace("~<input (.*?)\/>~", "<input $1>", $buffer);
            $buffer = preg_replace("~<img (.*?)\/>~", "<img $1>", $buffer);
            $buffer = str_replace("<br />", '<br>', $buffer );
            return $buffer;
        });
    });

    function plug_disable_emoji() {
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' );
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
        add_filter( 'tiny_mce_plugins', 'plug_disable_tinymce_emoji' );
    }
    add_action( 'init', 'plug_disable_emoji', 1 );

    function plug_disable_tinymce_emoji( $plugins ) {
        return array_diff( $plugins, array( 'wpemoji' ) );
    }

    add_filter('mce_external_plugins', 'true_add_tinymce_script');
	add_filter('mce_buttons', 'true_register_mce_button');
	
	function true_add_tinymce_script($plugin_array){
        $plugin_array['text_color'] =
        get_template_directory_uri().'/assets/js/buttons.js';
		return $plugin_array;
	}
	function true_register_mce_button($buttons){
		array_push($buttons, 'text_color');
		return $buttons;
	}
    function my_theme_add_editor_styles() {
        add_editor_style( 'custom-editor-style.css' );
    }
    add_action( 'init', 'my_theme_add_editor_styles' );

    function checkCaptcha() {
        $withCapt = get_field('capt-pkey', 'option') && get_field('capt-skey', 'option');
        
        $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
        $recaptcha_secret = get_field('capt-skey', 'option');
        $recaptcha_response = $_POST['recaptcha'];
        
        $ch = curl_init();
        curl_setopt_array($ch, [
        CURLOPT_URL => $recaptcha_url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => [
            'secret' => $recaptcha_secret,
            'response' => $recaptcha_response,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ],
        CURLOPT_RETURNTRANSFER => true
        ]);
        $output = curl_exec($ch);
        curl_close($ch);

        $recaptcha = json_decode($output);

        return !$withCapt || ($recaptcha->success == true && $recaptcha->score >= 0.6);
    }

    function feedback(){
        if (checkCaptcha()) {
            $names = [
                'feedname' => 'Имя',
                'feedphone' => 'Номер телефона',
                'feedcomm' => 'Вопрос',
                'feedtheme' => 'Тема'
            ];
    
            $meta = [];
            $text = "";
    
            foreach($_POST as $name => $value) {
                if (isset($names[$name]) && $value) {
                    $meta[$name] = $value;
                    $text .= $names[$name].': '.$value.";\n";
                }
            }
    
            $post_data = [
                'post_title'    => $_POST['feedphone'],
                'post_status'   => 'publish',
                'post_type'     => 'feedback',
                'post_author'   => 1,
                'ping_status'   => 'open',
                'meta_input'    => $meta,
            ];
            $post_id = wp_insert_post($post_data);
    
            $to = get_field('email-post', 'option') ?: get_field('email', 'option');
            $from = 'wordpress@'.str_replace(['http://', 'https://'], '', get_site_url());
            $subject = get_bloginfo('name').": Новое сообщение с формы связи.";
    
            $message = "У вас новое сообщение с формы обратной связи.\n\n".$text;
    
            $mailheaders = "From: ".get_bloginfo('name')." <".$from.">\r\n";
    
            $rsf = wp_mail($to,$subject,$message,$mailheaders);
        }

        die();
    }
    add_action('wp_ajax_feedback', 'feedback');
    add_action('wp_ajax_nopriv_feedback', 'feedback');

    function newcomment(){
        if (checkCaptcha()) {
            wp_new_comment([
                'comment_post_ID' => $_POST['feedreview-post'],
                'comment_content' => $_POST['feedreview-text'],
                'comment_type' => 'comment',
                'comment_approved' => 0,
                'user_ID' => $_POST['feedreview-user'],
            ]);


            $text = "К записи: ".get_the_title($_POST['feedreview-post'])."\n";
            $text .= "Ссылка: ".get_permalink($_POST['feedreview-post'])."\n\n";
            $text .= "С текстом: \n".$_POST['feedreview-text']."\n\n";
            $text .= "ID пользователя: ".$_POST['feedreview-user']."\n\n";
            $text .= "Посмотреть все комментарии: ".get_home_url()."/wp-admin/edit-comments.php";
    
            $to = get_field('email-post', 'option') ?: get_field('email', 'option');
            $from = 'wordpress@'.str_replace(['http://', 'https://'], '', get_site_url());
            $subject = get_bloginfo('name').": Новый комментарий.";
    
            $message = "Пользователь оставил новый комментарий.\n\n".$text;
    
            $mailheaders = "From: ".get_bloginfo('name')." <".$from.">\r\n";
    
            $rsf = wp_mail($to,$subject,$message,$mailheaders);
        }

        die();
    }
    add_action('wp_ajax_newcomment', 'newcomment');
    add_action('wp_ajax_nopriv_newcomment', 'newcomment');

    function userauth(){
        if (checkCaptcha()) {
            $user = wp_signon([
                'user_login' => $_POST['authform-login'],
                'user_password' => $_POST['authform-pass'],
                'remember' => isset($_POST['authform-remember']),
            ]);

            if (is_wp_error($user)) echo 'auth-error';
            else echo 'restart';
        }

        die();
    }
    add_action('wp_ajax_userauth', 'userauth');
    add_action('wp_ajax_nopriv_userauth', 'userauth');

    function userreg(){
        if (checkCaptcha()) {
            $names = [
                'regform-name' => 'Название компании',
                'regform-inn' => 'ИНН',
                'regform-phone' => 'Номер телефона',
                'regform-email' => 'Email'
            ];
    
            $meta = [];
            $text = "";
    
            foreach($_POST as $name => $value) {
                if (isset($names[$name]) && $value) {
                    $meta[$name] = $value;
                    $text .= $names[$name].': '.$value.";\n";
                }
            }
    
            $post_data = [
                'post_title'    => $_POST['regform-email'],
                'post_status'   => 'publish',
                'post_type'     => 'registers',
                'post_author'   => 1,
                'ping_status'   => 'open',
                'meta_input'    => $meta,
            ];
            $post_id = wp_insert_post($post_data);
    
            $to = get_field('email-post', 'option') ?: get_field('email', 'option');
            $from = 'wordpress@'.str_replace(['http://', 'https://'], '', get_site_url());
            $subject = get_bloginfo('name').": Новая заявка на регистрацию.";
    
            $message = "У вас новая заявка на регистрацию.\n\n".$text;
    
            $mailheaders = "From: ".get_bloginfo('name')." <".$from.">\r\n";
    
            $rsf = wp_mail($to,$subject,$message,$mailheaders);
        }

        die();
    }
    add_action('wp_ajax_userreg', 'userreg');
    add_action('wp_ajax_nopriv_userreg', 'userreg');

    function neworder(){
        if (checkCaptcha()) {
            $cartIds = [];
            $cartSimple = [];
            $cartPrice = 0;
            $cartItems = isset($_COOKIE['cart']) && $_COOKIE['cart'] ? json_decode($_COOKIE['cart']) : [];
            
            foreach($cartItems as $cartItem) {
                $cartIds[] = $cartItem[0];
                $price = get_field('price', $cartItem[0]);
                $cut = get_field('cut', $cartItem[0]);
                $currPrice = round($cut == 0 ? $price : ($price - (($price / 100) * $cut)));

                $cartPrice += $currPrice * $cartItem[1];

                $cartSimple[$cartItem[0]] = [
                    'count' => $cartItem[1],
                    'price' => $currPrice,
                ];
            }

            $names = [
                'order-user' => 'Пользователь',
                'order-company' => 'Название компании',
                'order-inn' => 'ИНН',
                'order-phone' => 'Номер телефона',
                'order-email' => 'Email',
                'order-city' => 'Город',
                'order-street' => 'Улица'
            ];
    
            $meta = [];
            $text = "";
    
            foreach($_POST as $name => $value) {
                if (isset($names[$name]) && $value) {
                    $meta[$name] = $value;
                    $text .= $names[$name].': '.$value.";\n";
                }
            }

            $meta['order-price'] = $cartPrice;
            $meta['order-items'] = $cartIds;
            $meta['order-count'] = json_encode($cartSimple);
            
            $text .= "\nСумма заказа: ".$cartPrice."\n\n";
            $text .= "Товары:\n";

            foreach($cartSimple as $cartId => $cartItem) {
                $text .= get_the_title($cartId).", ".$cartItem['count']." шт., ".$cartItem['price']." руб.\n";
            }
    
            $post_data = [
                'post_title'    => $_POST['order-email'],
                'post_status'   => 'publish',
                'post_type'     => 'orders',
                'post_author'   => 1,
                'ping_status'   => 'open',
                'meta_input'    => $meta,
            ];
            $post_id = wp_insert_post($post_data);

            $text .= "\n\nСсылка в админ-панели: ".get_home_url()."/wp-admin/post.php?post=".$post_id."&action=edit";
    
            $to = get_field('email-post', 'option') ?: get_field('email', 'option');
            $from = 'wordpress@'.str_replace(['http://', 'https://'], '', get_site_url());
            $subject = get_bloginfo('name').": Новый заказ #".$post_id;
    
            $message = "Пользователь оставил заявку на заказ.\n\n".$text;
    
            $mailheaders = "From: ".get_bloginfo('name')." <".$from.">\r\n";
    
            $rsf = wp_mail($to,$subject,$message,$mailheaders);
        }

        die();
    }
    add_action('wp_ajax_neworder', 'neworder');
    add_action('wp_ajax_nopriv_neworder', 'neworder');

    function userupdate(){
        if (checkCaptcha()) {
            $changeArgs = [
                'ID' => $_POST['profile-user'],
                'first_name' => $_POST['profile-name'],
                'user_email' => $_POST['profile-email'],
            ];

            $customArgs = [
                'phone' => $_POST['profile-phone'],
                'inn' => $_POST['profile-inn'],
                'city' => $_POST['profile-city'],
                'street' => $_POST['profile-street'],
                'company' => $_POST['profile-company']
            ];

            if (isset($_FILES['profile-avatar']) && $_FILES['profile-avatar']) {
                if (!function_exists('wp_handle_upload'))
                    require_once(ABSPATH.'wp-admin/includes/file.php');

                $file = &$_FILES['profile-avatar'];
                $overrides = ['test_form' => false];

                $movefile = wp_handle_upload($file, $overrides);
                
                if ($movefile && empty($movefile['error'])) {
                    $attachment = array(
                        'guid' => $movefile['file'], 
                        'post_mime_type' => $movefile['type'],
                        'post_title' => preg_replace('/.*\//', '', $movefile['file']),
                        'post_content' => '',
                        'post_status' => 'inherit'
                    );

                    $attach_id = wp_insert_attachment($attachment, $movefile['file']);

                    require_once( ABSPATH . 'wp-admin/includes/image.php' );

                    $attach_data = wp_generate_attachment_metadata($attach_id, $movefile['file']);
                    wp_update_attachment_metadata($attach_id, $attach_data);

                    $customArgs['avatar-image'] = $attach_id;
                }
            }

            wp_update_user($changeArgs);

            foreach($customArgs as $argKey => $arg) {
                update_user_meta($_POST['profile-user'], $argKey, $arg);
            }
        }

        die();
    }
    add_action('wp_ajax_userupdate', 'userupdate');
    add_action('wp_ajax_nopriv_userupdate', 'userupdate');

    function getClearText($text) {
        return str_replace('"', '', strip_tags($text));
    }

    function deleteP($text) {
        return str_replace(['<p>', '</p>'], '', $text);
    }

    function getThinPhone($phone) {
        return str_replace(['(', ')', '-', ' '], '', $phone);
    }

    function outBtn($name = '', $fz = '', $classes = '', $link = '', $attr = '', $icon = '') {
        return $link ? '
                <a href="'.$link.'" class="btn '.$classes.' text_fz'.($fz ?: 18).'" '.$attr.'>
                    <span>
                        '.($name ?: 'Подробнее').'
                        '.($icon ? '<img src="'.THEME_IMAGES.'icons/'.$icon.'.svg" alt="icon">' : '').'
                    </span>
                </a>
            ' : '
                <button class="btn '.$classes.' text_fz'.($fz ?: 18).'" '.$attr.'>
                    <span>
                        '.($name ?: 'Подробнее').'
                        '.($icon ? '<img src="'.THEME_IMAGES.'icons/'.$icon.'.svg" alt="icon">' : '').'
                    </span>
                </button>
            ';
    }

    function outCounter($start = 1, $class = '') {
        return '
            <div class="counter '.$class.'">
                <img src="'.THEME_IMAGES.'icons/minus.svg" alt="oper" class="counter-oper counter-minus">
                <span class="counter-result text_fz18 text_center">
                    '.$start.'
                </span>
                <img src="'.THEME_IMAGES.'icons/plus.svg" alt="oper" class="counter-oper counter-plus">
            </div>
        ';
    }

    function outWishBtn($itemId) {
        $inFavorite = isset($_COOKIE['favorite']) && $_COOKIE['favorite'] && in_array($itemId, json_decode($_COOKIE['favorite']));

        return IS_AUTH ? '
            <span class="wishlist-btn'.($inFavorite ? ' active' : '').'" data-id="'.$itemId.'">
                <img src="'.THEME_IMAGES.'icons/wishlist.svg" alt="В избранное">
            </span>
        ' : '';
    }

    function getUserAvatar($userId) {
        return get_user_meta($userId, 'avatar-image', true) 
        ? '<img src="'.wp_get_attachment_image_src(get_user_meta($userId, 'avatar-image', true), 'thumbnail')[0].'" class="img_bg">'
        : get_avatar($userId, 150, '', '', ['class' => 'img_bg']);
    }