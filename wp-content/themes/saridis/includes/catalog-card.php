<?php
    $id = isset($args['id']) ? $args['id'] : '';
    $title = isset($args['title']) ? $args['title'] : '';
    $image = isset($args['image']) ? $args['image'] : '';
    $link = isset($args['link']) ? $args['link'] : '';
    $brand = isset($args['brand']) ? $args['brand'] : '';
    $cats = isset($args['cats']) ? $args['cats'] : '';
    $price = isset($args['price']) ? $args['price'] : '';
    $cut = isset($args['cut']) ? $args['cut'] : '';
    $size = isset($args['size']) ? $args['size'] : '';
    $class = isset($args['class']) ? $args['class'] : '';

    $currPrice = round($cut == 0 ? $price : ($price - (($price / 100) * $cut)));
?>
<article class="catalog__list-item <?=$class ?: ''?>">
    <div class="image">
        <a href="<?=$link?>" class="preview">
            <img src="<?=$image ? $image['sizes']['medium'] : THEME_IMAGES.'no-image.jpg'?>" alt="<?=$title?>">
        </a>
        <?php if ($brand && get_field('logo', 'brand_'.$brand->term_id)) : ?>
            <img src="<?=get_field('logo', 'brand_'.$brand->term_id)['sizes']['thumbnail']?>" alt="<?=$brand->name?>" class="brand">
        <?php endif; ?>
        <?=outWishBtn($id)?>
    </div>
    <div class="text">
        <?php if ($cats) : ?>
            <div class="cats text_fz12 text_fw400">
                <?=$cats->name?>
            </div>
        <?php endif; ?>
        <a href="<?=$link?>" class="text_fz14 text_fw500">
            <?=$title?>
        </a>
        <?php if ($size) : ?>
            <div class="size text_fz12 text_fw400"><?=$size?></div>
        <?php endif; ?>
    </div>
    <div class="bott">
        <?php if (IS_AUTH) : ?>
            <?php if ($cut != 0) : ?>
                <div class="old-price">
                    <?=$price?> ₽
                </div>
            <?php endif; ?>
            <strong class="price text_fz18">
                <?=$currPrice?> ₽
            </strong>
        <?php else : ?>
            <?=outBtn('Узнать цену', '', '', $link)?>
        <?php endif; ?>
    </div>
</article>