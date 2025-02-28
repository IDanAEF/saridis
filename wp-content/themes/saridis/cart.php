<?php 
    /*
        Template Name: Корзина
    */

    get_header();

    $cartSimple = [];
    $cartIds = [];
    $cartItems = isset($_COOKIE['cart']) && $_COOKIE['cart'] ? json_decode($_COOKIE['cart']) : [];
    
    foreach($cartItems as $cartItem) {
        $cartSimple[$cartItem[0]] = $cartItem[1];
        $cartIds[] = $cartItem[0];
    }
?>
<main class="cart">
    <?php get_template_part('includes/breadcrumbs') ?>
    <section class="cart__content page__block pt0">
        <div class="container">
            <h1 class="page__title text_color">
                <?=get_the_title()?>
            </h1>
            <div class="cart__top">
                <a href="<?=get_permalink(57)?>" class="text_fw600">
                    Назад к покупкам
                </a>
                <div class="cart__top-cut text_fw300">
                    Размер скидки зависит от суммы заказа.
                    <div class="cart__top-cut-list text_fz12 text_fw400">
                        <span data-dir="to" data-price="40000" data-cut="10" class="active">
                            До <strong>40 000 ₽</strong> - <strong class="text_color">10% скидки</strong>
                        </span>
                        <span data-dir="from" data-price="40000" data-cut="20">
                            От <strong>40 000 ₽</strong> - <strong class="text_color">20% скидки</strong>
                        </span>
                        <span data-dir="from" data-price="70000" data-cut="40">
                            От <strong>70 000 ₽</strong> - <strong class="text_color">40% скидки</strong>
                        </span>
                    </div>
                </div>
            </div>
            <div class="cart__content-main">
                <?php
                    $cart = $cartIds ? get_posts([
                        'numberposts' => -1,
                        'category'    => 0,
                        'include'     => $cartIds,
                        'orderby'     => 'date',
                        'order'       => 'DESC',
                        'post_type'   => 'catalog',
                        'suppress_filters' => true,
                    ]) : [];
                    
                    if (isset($cart[0])) : ?>
                    <div class="cart__list elem_animate top">
                        <div class="cart__list-row head text_fz14 text_upper">
                            <div class="col">Продукты</div>
                            <div class="col">Цены</div>
                            <div class="col">Количество</div>
                            <div class="col">Итог</div>
                        </div>
                        <?php
                            $fullPrice = 0;
                            $fullPriceCut = 0;

                            foreach($cart as $cartItem) {
                                $image = get_field('preview-image', $cartItem->ID) 
                                    ? getImgSize(get_field('preview-image', $cartItem->ID)) 
                                    : THEME_IMAGES.'no-image.jpg';

                                $price = get_field('price', $cartItem->ID) ?: 0;
                                $cut = get_field('cut', $cartItem->ID) ?: 0;
                                $currPrice = round($cut == 0 ? $price : ($price - (($price / 100) * $cut)));
                                $resultPrice = $currPrice*$cartSimple[$cartItem->ID];

                                $fullPrice += $price*$cartSimple[$cartItem->ID];
                                $fullPriceCut += $resultPrice;
                                ?>
                                <div class="cart__list-row cart-add-parent" data-id="<?=$cartItem->ID?>" data-price="<?=$price?>" data-cut="<?=$currPrice?>">
                                    <a href="<?=get_permalink($cartItem->ID)?>" class="col name">
                                        <img src="<?=$image?>" alt="<?=getClearText(get_the_title($cartItem->ID))?>">
                                        <span><?=get_the_title($cartItem->ID)?></span>
                                    </a>
                                    <div class="col price">
                                        <span class="curr cart-add-price"><?=$currPrice?> ₽</span>
                                        <?php if ($cut) : ?>
                                            <span class="old"><?=$price?> ₽</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col">
                                        <?=outCounter($cartSimple[$cartItem->ID], 'responsive')?>
                                    </div>
                                    <div class="col">
                                        <div class="price-result cart-add-result">
                                            <span class="price-result-span"><?=$resultPrice?></span> ₽
                                        </div>
                                        <img src="<?=THEME_IMAGES?>icons/delete.svg" alt="Удалить" class="delete mla cart-delete" data-id="<?=$cartItem->ID?>">
                                    </div>
                                </div>
                                <?php
                            }
                        ?>
                        <div class="cart__list-bott">
                            <?=outBtn('Вернуться в магазин', '', '', get_permalink(57))?>
                        </div>
                    </div>
                    <form action="<?=admin_url('admin-ajax.php')?>?action=neworder" class="cart__form hide">
                        <input type="text" name="order-user" value="<?=USER_ID?>" hidden>
                        <input type="text" name="order-price" value="<?=$fullPriceCut?>" hidden>
                        <input type="text" name="order-personal" value="10" hidden>
                        <input type="text" name="recaptcha" hidden>

                        <div class="cart__form-block">
                            <div class="form-row">
                                <label class="form-label">
                                    <span class="text_fz14">Название компании</span>
                                    <input type="text" name="order-company" value="<?=get_user_meta(USER_ID, 'company', true)?>" required>
                                </label>
                                <label class="form-label">
                                    <span class="text_fz14">Ваш город</span>
                                    <input type="text" name="order-city" value="<?=get_user_meta(USER_ID, 'city', true)?>" required>
                                </label>
                            </div>
                            <div class="form-row">
                                <label class="form-label">
                                    <span class="text_fz14">ИНН</span>
                                    <input type="text" name="order-inn" value="<?=get_user_meta(USER_ID, 'inn', true)?>" required>
                                </label>
                                <label class="form-label">
                                    <span class="text_fz14">Название улицы</span>
                                    <input type="text" name="order-street" value="<?=get_user_meta(USER_ID, 'street', true)?>" required>
                                </label>
                            </div>
                            <label class="form-label">
                                <span class="text_fz14">Телефон</span>
                                <input type="tel" name="order-phone" value="<?=get_user_meta(USER_ID, 'phone', true)?>" placeholder="+7 (___) ___-__-__" required>
                            </label>
                            <label class="form-label">
                                <span class="text_fz14">Email</span>
                                <input type="email" name="order-email" value="<?=get_user_option('user_email', USER_ID)?>" required>
                            </label>
                        </div>
                        <div class="cart__form-block">
                            <strong class="text_fz24 text_fw500">Дополнительная информация</strong>
                            <label class="form-label">
                                <textarea name="order-info" class="big" placeholder="Примечания к вашему заказу, например, особые указания по доставке (необязательно)"></textarea>
                            </label>
                        </div>
                    </form>
                    <div class="cart__content-side elem_animate left">
                        <strong class="text_fz20 text_fw500">Итог</strong>
                        <div class="single-catalog__params text_fz14">
                            <div class="single-catalog__params-item">
                                <span class="text_fz400">Промежуточный итог:</span>
                                <span><span class="pre-price"><?=$fullPrice?></span> ₽</span>
                            </div>
                            <?php if ($fullPrice != $fullPriceCut) : ?>
                                <div class="single-catalog__params-item">
                                    <span class="text_fz400">Скидки с товаров:</span>
                                    <span><span class="cut-price">-<?=$fullPrice - $fullPriceCut?></span> ₽</span>
                                </div>
                            <?php endif; ?>
                            <div class="single-catalog__params-item over-price-item">
                                <span class="text_fz400">Персональная скидка:</span>
                                <span><span class="over-price">10</span>%</span>
                            </div>
                            <div class="single-catalog__params-item">
                                <span class="text_fz400">Итого к оплате:</span>
                                <span><span class="end-price"><?=$fullPriceCut?></span> ₽</span>
                            </div>
                        </div>
                        <?=outBtn('К оформлению')?>
                    </div>
                <?php else : ?>
                    Нет товаров в корзине
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php get_template_part('includes/home-feedback') ?>
</main>
<?php get_footer(); ?>