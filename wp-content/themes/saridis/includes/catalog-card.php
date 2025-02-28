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
<article class="catalog__list-item <?=$class ?: ''?>" data-price="<?=$currPrice?>" data-rating="<?=$rating?>">
    <div class="image">
        <a href="<?=$link?>" class="preview">
            <img src="<?=$image ? getImgSize($image, 'medium') : THEME_IMAGES.'no-image.jpg'?>" alt="<?=$title?>">
        </a>
        <?php if ($brand && (get_field('logo', 'brand_'.$brand->term_id) || get_field('icon', 'brand_'.$brand->term_id))) : ?>
            <img src="<?=get_field('logo', 'brand_'.$brand->term_id) ? get_field('logo', 'brand_'.$brand->term_id)['sizes']['thumbnail'] : get_field('icon', 'brand_'.$brand->term_id)['sizes']['thumbnail']?>" alt="<?=$brand->name?>" class="brand">
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
            <?php if ($currPrice != 0) : ?>
                <?php if ($cut != 0) : ?>
                    <div class="old-price">
                        <?=$price?> ₽
                    </div>
                <?php endif; ?>
                <strong class="price text_fz18">
                    <?=$currPrice?> ₽
                </strong>
            <?php endif; ?>
        <?php else : ?>
            <?=outBtn('Узнать цену', '', '', $link)?>
        <?php endif; ?>
    </div>
</article>