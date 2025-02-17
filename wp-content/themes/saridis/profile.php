<?php 
    /*
        Template Name: Личный кабинет
    */

    get_header(); 

    $sect = isset($_GET['sect']) ? $_GET['sect'] : '';
?>
<main class="profile">
    <?php get_template_part('includes/breadcrumbs') ?>
    <section class="profile__content page__block pt0">
        <div class="container">
            <h1 class="page__title text_color">
                <?=get_the_title()?>
            </h1>
            <?php if (!IS_AUTH) : ?>
                <span>Вы не авторизованы</span>
            <?php else : ?>
                <div class="profile__main">
                    <div class="profile__sidebar text_fw400 elem_animate right">
                        <a href="<?=get_home_url()?>/profile/" class="profile__sidebar-item no-hover<?=!$sect ? ' active' : ''?>">
                            <img src="<?=THEME_IMAGES?>icons/profile.svg" alt="Профиль">
                            <span>Профиль</span>
                        </a>
                        <a href="<?=get_home_url()?>/profile/?sect=history" class="profile__sidebar-item no-hover<?=$sect == 'history' || $sect == 'history-detail' ? ' active' : ''?>">
                            <img src="<?=THEME_IMAGES?>icons/history.svg" alt="История заказов">
                            <span>История заказов</span>
                        </a>
                        <a href="<?=get_home_url()?>/profile/?sect=favorite" class="profile__sidebar-item no-hover<?=$sect == 'favorite' ? ' active' : ''?>">
                            <img src="<?=THEME_IMAGES?>icons/favorite.svg" alt="Избранное">
                            <span>Избранное</span>
                        </a>
                        <a href="<?=get_home_url()?>/profile/?sect=in-active" class="profile__sidebar-item no-hover<?=$sect == 'in-active' ? ' active' : ''?>">
                            <img src="<?=THEME_IMAGES?>icons/orders.svg" alt="Корзина покупок">
                            <span>Корзина покупок</span>
                        </a>
                        <a href="<?=get_home_url()?>/profile/?sect=settings" class="profile__sidebar-item no-hover<?=$sect == 'settings' ? ' active' : ''?>">
                            <img src="<?=THEME_IMAGES?>icons/settings.svg" alt="Настройки">
                            <span>Настройки</span>
                        </a>
                        <span class="profile__sidebar-item no-hover" data-call-modal="logout">
                            <img src="<?=THEME_IMAGES?>icons/quit.svg" alt="Выйти">
                            <span>Выйти</span>
                        </span>
                    </div>
                    <div class="profile__info">
                        <?php if (!$sect) : ?>
                            <div class="profile__blocks elem_animate bott">
                                <div class="profile__blocks-item profile__general">
                                    <div class="avatar-wrap">
                                        <?=getUserAvatar(USER_ID)?>
                                    </div>
                                    <strong class="text_fz20 text_fw500">
                                        <?=get_user_option('display_name', USER_ID)?>
                                    </strong>
                                    <span class="text_fz14">Покупатель</span>
                                    <a href="/profile/?sect=settings" class="text_color">Редактировать профиль</a>
                                </div>
                                <div class="profile__blocks-item profile__detail">
                                    <span class="title text_fz14 text_upper">Платежный адрес</span>
                                    <strong class="text_fz18 text_fw500">
                                        <?=get_user_meta(USER_ID, 'company', true)?>
                                    </strong>
                                    <span class="address text_fz14 text_fw300">
                                        <?=get_user_meta(USER_ID, 'city', true)?>, <?=get_user_meta(USER_ID, 'street', true)?>
                                    </span>
                                    <?php
                                        $email = get_user_option('user_email', USER_ID);
                                        $phone = get_user_meta(USER_ID, 'phone', true);
                                    ?>
                                    <div class="contacts text_fw400">
                                        <a href="mailto:<?=$email?>">
                                            <?=$email?>
                                        </a>
                                        <a href="tel:<?=getThinPhone($phone)?>">
                                            <?=$phone?>
                                        </a>
                                    </div>

                                    <a href="/profile/?sect=settings" class="text_color">Изменить адрес</a>
                                </div>
                            </div>
                            <?php
                                $lastHistory = get_posts([
                                    'numberposts' => 3,
                                    'category'    => 0,
                                    'orderby'     => 'date',
                                    'order'       => 'DESC',
                                    'post_type'   => 'orders',
                                    'meta_key' => 'order-user',
                                    'meta_value' => USER_ID,
                                    'suppress_filters' => true,
                                ]);

                                if (isset($lastHistory[0])) : ?>
                                <div class="profile__history elem_animate top">
                                    <div class="head page__title">
                                        <strong class="text_fz20 text_fw500">История последних заказов</strong>
                                        <a href="<?=get_home_url()?>/profile/?sect=history" class="text_color text_underline">Посмотреть все</a>
                                    </div>
                                    <div class="body text_fz14">
                                        <div class="row top text_fz12 text_upper">
                                            <span>id</span>
                                            <span>Дата</span>
                                            <span>цена</span>
                                            <span>статус</span>
                                            <span></span>
                                        </div>
                                        <?php
                                            foreach($lastHistory as $histElem) {
                                                $orderStatus = get_field('order-status', $histElem->ID);
                                                ?>
                                                <div class="row text_fw400">
                                                    <span>#<?=$histElem->ID?></span>
                                                    <span><?=get_the_date('d.m.Y', $histElem->ID)?></span>
                                                    <span><?=get_field('order-price', $histElem->ID)?> ₽</span>
                                                    <span><?=$orderStatus ? $orderStatus['label'] : 'Заказ получен'?></span>
                                                    <span>
                                                        <a href="<?=get_home_url()?>/profile/?sect=history-detail&id=<?=$histElem->ID?>" class="text_color">
                                                            Подробно
                                                        </a>
                                                    </span>
                                                </div>
                                                <?php
                                            }
                                        ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($sect == 'history') : ?>
                            <?php
                                $lastHistory = get_posts([
                                    'numberposts' => -1,
                                    'category'    => 0,
                                    'orderby'     => 'date',
                                    'order'       => 'DESC',
                                    'post_type'   => 'orders',
                                    'meta_key' => 'order-user',
                                    'meta_value' => USER_ID,
                                    'suppress_filters' => true,
                                ]);

                                if (isset($lastHistory[0])) : ?>
                                <div class="profile__history elem_animate top showhide-field" data-vis="12">
                                    <div class="head page__title">
                                        <strong class="text_fz20 text_fw500">История последних заказов</strong>
                                    </div>
                                    <div class="body text_fz14 showhide-list">
                                        <div class="row top text_fz12 text_upper">
                                            <span>id</span>
                                            <span>Дата</span>
                                            <span>цена</span>
                                            <span>статус</span>
                                            <span></span>
                                        </div>
                                        <?php
                                            foreach($lastHistory as $histElem) {
                                                $orderStatus = get_field('order-status', $histElem->ID);
                                                ?>
                                                <div class="row text_fw400 showhide-item hide">
                                                    <span>#<?=$histElem->ID?></span>
                                                    <span><?=get_the_date('d.m.Y', $histElem->ID)?></span>
                                                    <span><?=get_field('order-price', $histElem->ID)?> ₽</span>
                                                    <span><?=$orderStatus ? $orderStatus['label'] : 'Заказ получен'?></span>
                                                    <span>
                                                        <a href="<?=get_home_url()?>/profile/?sect=history-detail&id=<?=$histElem->ID?>" class="text_color">
                                                            Подробно
                                                        </a>
                                                    </span>
                                                </div>
                                                <?php
                                            }
                                        ?>
                                    </div>
                                    <?=outBtn('Показать ещё', '', 'border page__btn showhide-more')?>
                                </div>
                            <?php else : ?>
                                История пуста
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($sect == 'history-detail' ) : ?>
                            <?php
                                $histId = isset($_GET['id']) ? $_GET['id'] : '';

                                if ($histId) {
                                    $orderCount = json_decode(get_field('order-count', $histId), true);
                                    $orderItems = get_field('order-items', $histId);
                                    $orderUser = get_field('order-user', $histId);
                                    $orderStatus = get_field('order-status', $histId) ? get_field('order-status', $histId)['value'] : 'get';

                                    $cutPrice = 0;
                                    $fullPrice = 0;

                                    foreach($orderCount as $orderId => $orderArr) {
                                        $price = get_field('price', $orderId);
                                        $cut = get_field('cut', $orderId);
                                        $currPrice = round($cut == 0 ? $price : ($price - (($price / 100) * $cut)));

                                        $fullPrice += $price * $orderArr['count'];
                                        $cutPrice += $currPrice * $orderArr['count'];
                                    }
                                    ?>
                                    <div class="profile__history profile__history-detail elem_animate top">
                                        <div class="head page__title">
                                            <strong class="text_fz20 text_fw500">
                                                <span>Детали заказа</span>
                                                <span>•</span>
                                                <span class="date text_fz14 text_fw400">
                                                    <?=get_the_date('d.m.Y', $histId)?>
                                                </span>
                                                <span>•</span>
                                                <span class="count text_fz14 text_fw400">
                                                    <?=count($orderItems)?> товаров
                                                </span>
                                            </strong>
                                            <a href="<?=get_home_url()?>/profile/?sect=history" class="text_color text_underline">Вернуться к списку</a>
                                        </div>
                                        <div class="body text_fz14">
                                            <div class="profile__history-detail-row text_fz14">
                                                <div class="profile__history-detail-block">
                                                    <div class="title text_upper">
                                                        <span class="gray">Платежный адрес</span>
                                                    </div>
                                                    <div class="info">
                                                        <strong class="text_fz18 text_fw500">
                                                            <?=get_user_meta(USER_ID, 'company', true)?>
                                                        </strong>
                                                        <span class="address text_fw300">
                                                            <?=get_user_meta(USER_ID, 'city', true)?>, <?=get_user_meta(USER_ID, 'street', true)?>
                                                        </span>
                                                        <?php
                                                            $email = get_user_option('user_email', USER_ID);
                                                            $phone = get_user_meta(USER_ID, 'phone', true);
                                                        ?>
                                                        <div class="contacts text_fz16 text_fw400">
                                                            <a href="mailto:<?=$email?>">
                                                                 <span class="gray text_fz12">Email</span><br>
                                                                <?=$email?>
                                                            </a>
                                                            <a href="tel:<?=getThinPhone($phone)?>">
                                                                <span class="gray text_fz12">Телефон</span><br>
                                                                <?=$phone?>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="profile__history-detail-block">
                                                    <div class="title text_upper">
                                                        <span class="gray">Адрес доставки</span>
                                                    </div>
                                                    <div class="info">
                                                        <strong class="text_fz18 text_fw500">
                                                            <?=get_field('order-company', $histId)?>
                                                        </strong>
                                                        <span class="address text_fw300">
                                                            <?=get_field('order-city', $histId)?>, <?=get_field('order-street', $histId)?>
                                                        </span>
                                                        <?php
                                                            $email = get_field('order-email', $histId);
                                                            $phone = get_field('order-phone', $histId);
                                                        ?>
                                                        <div class="contacts text_fz16 text_fw400">
                                                            <a href="mailto:<?=$email?>">
                                                                <span class="gray text_fz12">Email</span><br>
                                                                <?=$email?>
                                                            </a>
                                                            <a href="tel:<?=getThinPhone($phone)?>">
                                                                <span class="gray text_fz12">Телефон</span><br>
                                                                <?=$phone?>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="profile__history-detail-block">
                                                    <div class="title text_upper">
                                                        <span class="gray">ID:</span> #<?=$histId?><br>
                                                        <span class="gray">Способ оплаты:</span> Рассчетный счет
                                                    </div>
                                                    <div class="info">
                                                        <div class="single-catalog__params text_fz14">
                                                            <div class="single-catalog__params-item">
                                                                <span class="text_fz400">Промежуточный итог:</span>
                                                                <span><?=$fullPrice?> ₽</span>
                                                            </div>
                                                            <?php if ($fullPrice != $cutPrice) : ?>
                                                                <div class="single-catalog__params-item">
                                                                    <span class="text_fz400">Скидка:</span>
                                                                    <span>-<?=$fullPrice - $cutPrice?> ₽</span>
                                                                </div>
                                                            <?php endif; ?>
                                                            <?php if (get_field('order-delivery', $histId)) : ?>
                                                                <div class="single-catalog__params-item">
                                                                    <span class="text_fz400">Доставка:</span>
                                                                    <span><?=get_field('order-delivery', $histId)?> ₽</span>
                                                                </div>
                                                            <?php endif; ?>
                                                            <div class="single-catalog__params-item">
                                                                <span class="text_fz400">Итого к оплате:</span>
                                                                <span><?=get_field('order-price', $histId)?> ₽</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="profile__history-detail-range text_fz14 text_center">
                                                <?php
                                                    $statusIndex = [
                                                        'get' => 1,
                                                        'work' => 2,
                                                        'onway' => 3,
                                                        'done' => 4
                                                    ];

                                                    $currIndex = $statusIndex[$orderStatus];
                                                ?>
                                                <div class="range-item<?=$currIndex != 1 && $currIndex > 1 ? ' checked' : ($currIndex == 1 ? ' active' : '')?>">
                                                    <div class="circle">
                                                        01
                                                    </div>
                                                    Заказ получен
                                                </div>
                                                <div class="range-item<?=$currIndex != 2 && $currIndex > 2 ? ' checked' : ($currIndex == 2 ? ' active' : '')?>">
                                                    <div class="circle">
                                                        02
                                                    </div>
                                                    Обработка
                                                </div>
                                                <div class="range-item<?=$currIndex != 3 && $currIndex > 3 ? ' checked' : ($currIndex == 3 ? ' active' : '')?>">
                                                    <div class="circle">
                                                        <span>03</span>
                                                    </div>
                                                    В пути
                                                </div>
                                                <div class="range-item<?=$currIndex != 4 && $currIndex > 4 ? ' checked' : ($currIndex == 4 ? ' active' : '')?>">
                                                    <div class="circle">
                                                        <span>04</span>
                                                    </div>
                                                    Доставлен
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cart__list elem_animate top">
                                        <div class="cart__list-row head text_fz14 text_upper">
                                            <div class="col">Продукты</div>
                                            <div class="col">Цены</div>
                                            <div class="col">Количество</div>
                                            <div class="col">Итог</div>
                                        </div>
                                        <?php
                                            foreach($orderCount as $orderId => $orderArr) {
                                                $image = get_field('preview-image', $orderId) 
                                                    ? get_field('preview-image', $orderId)['sizes']['thumbnail'] 
                                                    : THEME_IMAGES.'no-image.jpg';

                                                $price = get_field('price', $orderId);
                                                $cut = get_field('cut', $orderId);
                                                $currPrice = round($cut == 0 ? $price : ($price - (($price / 100) * $cut)));
                                                ?>
                                                <div class="cart__list-row wish-parent cart-add-parent">
                                                    <a href="<?=get_permalink($orderId)?>" class="col name">
                                                        <img src="<?=$image?>" alt="<?=getClearText(get_the_title($orderId))?>">
                                                        <span><?=get_the_title($orderId)?></span>
                                                    </a>
                                                    <div class="col price">
                                                        <span class="curr"><?=$currPrice?> ₽</span>
                                                        <?php if ($cut) : ?>
                                                            <span class="old"><?=$price?> ₽</span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="col">
                                                        <?=$orderArr['count']?> шт.
                                                    </div>
                                                    <div class="col">
                                                        <span class="price-result">
                                                            <?=$currPrice * $orderArr['count']?> ₽
                                                        </span>
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        ?>
                                    </div>
                                    <?php
                                } else {
                                    echo 'Неверные данные';
                                }
                            ?>
                        <?php endif; ?>
                        <?php if ($sect == 'favorite') : ?>
                            <?php
                                $favorite = isset($_COOKIE['favorite']) && json_decode($_COOKIE['favorite']) ? get_posts([
                                    'numberposts' => -1,
                                    'category'    => 0,
                                    'include'     => json_decode($_COOKIE['favorite']),
                                    'orderby'     => 'date',
                                    'order'       => 'DESC',
                                    'post_type'   => 'catalog',
                                    'suppress_filters' => true,
                                ]) : [];
                                
                                if (isset($favorite[0])) : ?>
                                <div class="cart__list elem_animate top">
                                    <div class="cart__list-row head text_fz14 text_upper">
                                        <div class="col">Продукты</div>
                                        <div class="col">Цены</div>
                                        <div class="col">Станус наличия</div>
                                        <div class="col"></div>
                                    </div>
                                    <?php
                                        foreach($favorite as $favoriteItem) {
                                            $image = get_field('preview-image', $favoriteItem->ID) 
                                                ? get_field('preview-image', $favoriteItem->ID)['sizes']['thumbnail'] 
                                                : THEME_IMAGES.'no-image.jpg';

                                            $price = get_field('price', $favoriteItem->ID);
                                            $cut = get_field('cut', $favoriteItem->ID);
                                            $currPrice = round($cut == 0 ? $price : ($price - (($price / 100) * $cut)));
                                            ?>
                                            <div class="cart__list-row wish-parent cart-add-parent">
                                                <a href="<?=get_permalink($favoriteItem->ID)?>" class="col name">
                                                    <img src="<?=$image?>" alt="<?=getClearText(get_the_title($favoriteItem->ID))?>">
                                                    <span><?=get_the_title($favoriteItem->ID)?></span>
                                                </a>
                                                <div class="col price">
                                                    <span class="curr"><?=$currPrice?> ₽</span>
                                                    <?php if ($cut) : ?>
                                                        <span class="old"><?=$price?> ₽</span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col">
                                                    <span class="isset <?=get_field('isset', $favoriteItem->ID)['value']?> text_fz14">
                                                        <?=get_field('isset', $favoriteItem->ID)['label']?>
                                                    </span>
                                                </div>
                                                <div class="col">
                                                    <?php
                                                        $btnClass = 'wish-delete';

                                                        if (get_field('isset', $favoriteItem->ID)['value'] == 'out')
                                                            $btnClass .= ' disable';
                                                        else $btnClass .= ' cart-add';
                                                    ?>
                                                    <?=outBtn('Добавить в корзину', 14, $btnClass, '', 'data-call-modal="cart" data-id="'.$favoriteItem->ID.'"')?>
                                                    <img src="<?=THEME_IMAGES?>icons/delete.svg" alt="Удалить" class="delete wish-delete" data-id="<?=$favoriteItem->ID?>">
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    ?>
                                </div>
                            <?php else : ?>
                                Нет товаров в избранном
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($sect == 'in-active') : ?>
                            <?php
                                $lastHistory = get_posts([
                                    'numberposts' => -1,
                                    'category'    => 0,
                                    'orderby'     => 'date',
                                    'order'       => 'DESC',
                                    'post_type'   => 'orders',
                                    'meta_key' => 'order-user',
                                    'meta_value' => USER_ID,
                                    'suppress_filters' => true,
                                ]);

                                if (isset($lastHistory[0])) : ?>
                                <div class="profile__history elem_animate top">
                                    <div class="head page__title">
                                        <strong class="text_fz20 text_fw500">Действующие заказы</strong>
                                    </div>
                                    <div class="body text_fz14">
                                        <div class="row top text_fz12 text_upper">
                                            <span>id</span>
                                            <span>Дата</span>
                                            <span>цена</span>
                                            <span>статус</span>
                                            <span></span>
                                        </div>
                                        <?php
                                            foreach($lastHistory as $histElem) {
                                                $orderStatus = get_field('order-status', $histElem->ID);

                                                if ($orderStatus && $orderStatus['value'] == 'done') continue;
                                                ?>
                                                <div class="row text_fw400">
                                                    <span>#<?=$histElem->ID?></span>
                                                    <span><?=get_the_date('d.m.Y', $histElem->ID)?></span>
                                                    <span><?=get_field('order-price', $histElem->ID)?> ₽</span>
                                                    <span><?=$orderStatus ? $orderStatus['label'] : 'Заказ получен'?></span>
                                                    <span>
                                                        <a href="<?=get_home_url()?>/profile/?sect=history-detail&id=<?=$histElem->ID?>" class="text_color">
                                                            Подробно
                                                        </a>
                                                    </span>
                                                </div>
                                                <?php
                                            }
                                        ?>
                                    </div>
                                </div>
                            <?php else : ?>
                                Нет действующих заказов
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($sect == 'settings') : ?>
                            <form action="<?=admin_url('admin-ajax.php')?>?action=userupdate" class="profile__settings elem_animate top form-ajax" data-success="save">
                                <input type="text" name="profile-user" value="<?=USER_ID?>" hidden>

                                <label class="profile__settings-avatar">
                                    <input type="file" name="profile-avatar" hidden>

                                    <div class="avatar-wrap">
                                        <?=getUserAvatar(1)?>
                                    </div>
                                    <span class="text_fz18 text_center">Выбрать фото</span>
                                </label>
                                <div class="profile__settings-info">
                                    <div class="profile__settings-block">
                                        <strong class="text_fz20 text_fw500">Настройки учетной записи</strong>
                                        <div class="form-wrap">
                                            <label class="form-label">
                                                <span class="text_fz12">Ваше имя</span>
                                                <input type="text" name="profile-name" value="<?=get_user_option('user_firstname', USER_ID)?>">
                                            </label>
                                            <label class="form-label">
                                                <span class="text_fz12">Телефон</span>
                                                <input type="tel" name="profile-phone" placeholder="+7 (___) ___-__-__" value="<?=get_user_meta(USER_ID, 'phone', true)?>" required>
                                            </label>
                                            <label class="form-label">
                                                <span class="text_fz12">Email</span>
                                                <input type="email" name="profile-email" value="<?=get_user_option('user_email', USER_ID)?>" required>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="profile__settings-block">
                                        <strong class="text_fz20 text_fw500">Платежный адрес</strong>
                                        <div class="form-wrap">
                                            <label class="form-label">
                                                <span class="text_fz12">Название компании</span>
                                                <input type="text" name="profile-company" value="<?=get_user_meta(USER_ID, 'company', true)?>" required>
                                            </label>
                                            <label class="form-label">
                                                <span class="text_fz12">Ваш город</span>
                                                <input type="text" name="profile-city" value="<?=get_user_meta(USER_ID, 'city', true)?>" required>
                                            </label>
                                            <label class="form-label">
                                                <span class="text_fz12">Название улицы</span>
                                                <input type="text" name="profile-street" value="<?=get_user_meta(USER_ID, 'street', true)?>" required>
                                            </label>
                                            <label class="form-label">
                                                <span class="text_fz12">ИНН</span>
                                                <input type="text" name="profile-inn" value="<?=get_user_meta(USER_ID, 'inn', true)?>" required>
                                            </label>
                                            <div class="form-label">
                                                <?=outBtn('Сохранить изменения')?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php 
    get_footer(); 
?>