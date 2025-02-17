<?php
    $seoTitle = get_field('seo-title') ?: get_bloginfo('name').' - '.get_the_title();
    $seoDescription = get_field('seo-description');
    $seoKeywords = get_field('seo-keywords');

    if (is_404()) $seoTitle .= 'Ошибка 404';

    global $isHome;
    global $fronPage;
    global $post;

    $fronPageID = get_option('page_on_front');
    $isHome = is_front_page() || is_home();
    $menuHeader = wp_get_nav_menu_items('header');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$seoTitle?></title>
    <meta name="title" content="<?=$seoTitle?>">
    <meta property="og:title" content="<?=$seoTitle?>">
    <meta property="twitter:title" content="<?=$seoTitle?>">

    <?php if ($seoDescription) : ?>
        <meta name="description" content="<?=$seoDescription?>">
        <meta property="og:description" content="<?=$seoDescription?>">
        <meta property="twitter:description" content="<?=$seoDescription?>">
    <?php endif; ?>

    <?php if ($seoKeywords) : ?>
        <meta name="keywords" content="<?=$seoKeywords?>">
    <?php endif; ?>

    <meta property="og:type" content="website">
    <meta property="og:url" content="<?=get_permalink()?>">
    <meta property="og:image" content="<?=get_field('messanger', 'option')?>">
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?=get_permalink()?>">
    <meta property="twitter:image" content="<?=get_field('messanger', 'option')?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">

    <?php
        echo '
            <script type="application/ld+json">
                {
                    "@context": "https://schema.org",
                    "@type": "Organization",
                    "name": "'.get_bloginfo('name').'",
                    "url": "'.get_permalink().'",
                    "logo": "'.(get_field('logo', 'option') ?: THEME_IMAGES.'logo.png').'",
                    "contactPoint": [
                        {
                            "@type": "ContactPoint",
                            "telephone": "'.get_field('phone', 'option').'",
                            "contactType": "customer service",
                            "email": "'.get_field('email', 'option').'"
                        }
                    ]
                }
            </script>
        ';
    ?>

    <?php if (get_field('capt-pkey', 'option') && get_field('capt-skey', 'option')) : ?>
        <script src="https://www.google.com/recaptcha/api.js?render=<?=get_field('capt-pkey', 'option')?>"></script>
        <script>
            grecaptcha.ready(function () {
                const setToken = () => {
                    grecaptcha.execute('<?=get_field('capt-pkey', 'option')?>', { action: 'check' }).then(function (token) {
                        let captInput = document.querySelectorAll('input[name="recaptcha"]');
                        
                        captInput.forEach(inp => {
                            inp.value = token;
                        })
                    });
                }
    
                setToken();
                setInterval(setToken, 90*1000);
            });
        </script>
    <?php endif; ?>

    <?php
        wp_head();
    ?>

    <?=get_field('code-head', 'option')?>
</head>
<body>
    <div class="page-wrap<?=(!$isHome && $post->ID != 53 ? ' offset' : '')?>">
        <header class="header">
            <div class="container text_white">
                <div class="header__left">
                    <div class="header__logo text_fz14 text_fw500">
                        <a href="<?=get_home_url()?>" class="no-hover">
                            <img src="<?=(get_field('logo', 'option') ?: THEME_IMAGES.'logo.png')?>" alt="<?=getClearText(get_bloginfo('name'))?>">
                        </a>
                        <span>Оптовая поставка <br>греческих продуктов</span>
                    </div>
                    <?php if (isset($menuHeader[0])) : ?>
                        <nav class="header__nav text_fw700">
                            <ul>
                                <?php
                                    foreach($menuHeader as $menuItem) {
                                        $class = '';

                                        if (FULL_PATH == $menuItem->url)
                                            $class = ' active';
                                        ?>
                                        <li>
                                            <a href="<?=$menuItem->url?>" class="no-hover<?=$class?>">
                                                <?=$menuItem->title?>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                    <a href="tel:<?=getThinPhone(get_field('phone', 'option'))?>" class="header__phone text_fz18 text_fw700 no-hover">
                        <img src="<?=THEME_IMAGES?>icons/phone.svg" alt="Телефон">
                        <span><?=get_field('phone', 'option')?></span>
                    </a>
                </div>
                <div class="header__right">
                    <a href="" class="header__search">
                        <img src="<?=THEME_IMAGES?>icons/search.svg" alt="Поиск">
                    </a>
                    <?php if (IS_AUTH) : ?>
                        <?php
                            $favCount = isset($_COOKIE['favorite']) && $_COOKIE['favorite'] ? count(json_decode($_COOKIE['favorite'])) : 0;
                            $cartCount = 0;

                            $cartItems = isset($_COOKIE['cart']) && $_COOKIE['cart'] ? json_decode($_COOKIE['cart']) : [];
                            foreach($cartItems as $cartItem) {
                                $cartCount += $cartItem[1];
                            }
                        ?>
                        <a href="<?=get_home_url()?>/profile/?sect=favorite" class="header__favorite">
                            <img src="<?=THEME_IMAGES?>icons/wishlist.svg" alt="В избранное">
                            <?php if ($favCount > 0) : ?>
                                <span><?=$favCount?></span>
                            <?php endif; ?>
                        </a>
                        <span class="line"></span>
                        <a href="/cart/" class="header__cart">
                            <img src="<?=THEME_IMAGES?>icons/cart.svg" alt="Корзина">
                            <?php if ($cartCount > 0) : ?>
                                <span><?=$cartCount?></span>
                            <?php endif; ?>
                        </a>
                    <?php endif; ?>
                    <?php if (IS_AUTH) : ?>
                        <a href="<?=get_home_url()?>/profile/" class="header__user no-hover">
                            <?=getUserAvatar(USER_ID)?>
                        </a>
                    <?php else : ?>
                        <span class="header__user no-hover" data-call-modal="auth">
                            <img src="<?=THEME_IMAGES?>icons/user.svg" alt="Профиль" class="default">
                        </span>
                    <?php endif; ?>
                    <img src="<?=THEME_IMAGES?>icons/burger.svg" alt="Меню" class="header__burger">
                </div>
            </div>
        </header>