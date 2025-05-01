<?php
    $id = isset($args['id']) ? $args['id'] : '';
    $class = isset($args['class']) ? $args['class'] : '';

    $title = get_the_title($id);
    $image = get_field('preview-image', $id);
    $link = get_permalink($id);
    $brand = get_field('brand', $id);
    $cats = get_field('cats', $id);
    $price = get_field('price', $id) ?: 0;
    $cut = get_field('cut', $id) ?: 0;
    $size = get_field('size', $id);
    $rating = get_field('rating', $id) ?: 0;

    $currPrice = round($cut == 0 ? $price : ($price - (($price / 100) * $cut)));
?>
<div class="cart__list-row cart-add-parent catalog-rel-item <?=$class ?: ''?>" data-id="<?=$id?>" data-price="<?=$currPrice?>" data-rating="<?=$rating?>">
    <a href="<?=$link?>" class="col name">
        <img src="<?=$image ? getImgSize($image, 'medium') : THEME_IMAGES.'no-image.jpg'?>" alt="<?=getClearText($title)?>">
        <span><?=$title?><br><span class="text_fz14" style="color: #959595"><?=$size?></span></span>
    </a>
    <div class="col price">
        <?php if (IS_AUTH) : ?>
            <span class="curr"><?=$currPrice?> ₽</span>
            <?php if ($cut) : ?>
                <span class="old"><?=$price?> ₽</span>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <div class="col">
        <span class="isset <?=get_field('isset', $id)['value']?> text_fz14">
            <?=get_field('isset', $id)['label']?>
        </span>
    </div>
    <div class="col">
        <?php
            $btnClass = '';

            if (get_field('isset', $id)['value'] == 'out')
                $btnClass .= ' disable';
            else $btnClass .= ' cart-add';
        ?>
        <?php if (IS_AUTH) : ?>
            <?=outWishBtn($id)?>
            <?=outBtn('Добавить в корзину', 14, $btnClass, '', 'data-call-modal="cart" data-id="'.$id.'"')?>
        <?php else : ?>
            <?=outBtn('Узнать цену', '', '', '', 'data-call-modal="register"')?>
        <?php endif; ?>
    </div>
</div>