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
<div class="cart__list-row big cart-add-parent catalog-rel-item <?=$class ?: ''?>" data-id="<?=$id?>" data-price="<?=$currPrice?>" data-rating="<?=$rating?>">
    <a href="<?=$link?>" class="col name">
        <img src="<?=$image ? getImgSize($image, 'medium') : THEME_IMAGES.'no-image.jpg'?>" alt="<?=getClearText($title)?>">
        <span><?=$title?></span>
    </a>
    <div class="col detail-descr">
        <div class="detail-descr-wrap">
            <?php if (get_field('descr', $id)) : ?>
                <div class="detail-descr-text default-text text_fz14">
                    <?=get_field('descr', $id)?>
                </div>
            <?php endif; ?>
            <?php 
                $itemsIn = get_field('items-in', $id) || get_field('items-in', $id) == 0 ? get_field('items-in', $id) : 12;
                
                if (
                    get_field('factory', $id) || 
                    get_field('size', $id) || 
                    get_field('wrap', $id) || 
                    $itemsIn != 0 || 
                    get_field('garanty', $id)
                ) : ?>
                <div class="catalog__filter-block">
                    <div class="top text_fz20 body-click-target not-global">
                        <span>Характеристики</span>
                        <img src="<?=THEME_IMAGES?>icons/up.svg" alt="up">
                    </div>
                    <div class="bott body-click-content not-global">
                        <div class="single-catalog__params text_fz14">
                            <?php if (get_field('factory', $id)) : ?>
                                <div class="single-catalog__params-item">
                                    <span>Производство:</span>
                                    <span class="text_fz400"><?=get_field('factory', $id)?></span>
                                </div>
                            <?php endif; ?>
                            <?php if (get_field('size', $id)) : ?>
                                <div class="single-catalog__params-item">
                                    <span>Объем/Вес:</span>
                                    <span class="text_fz400"><?=get_field('size', $id)?></span>
                                </div>
                            <?php endif; ?>
                            <?php if (get_field('wrap', $id)) : ?>
                                <div class="single-catalog__params-item">
                                    <span>Тип упаковки:</span>
                                    <span class="text_fz400"><?=get_field('wrap', $id)?></span>
                                </div>
                            <?php endif; ?>
                            <?php if (get_field('garanty', $id)) : ?>
                                <div class="single-catalog__params-item">
                                    <span>Срок годности:</span>
                                    <span class="text_fz400"><?=get_field('garanty', $id)?></span>
                                </div>
                            <?php endif; ?>
                            <?php if ($itemsIn != 0) : ?>
                                <div class="single-catalog__params-item">
                                    <span>Количество шт. в упаковке:</span>
                                    <span class="text_fz400"><?=$itemsIn?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="detail-descr-bott">
                <div class="price">
                    <?php if (IS_AUTH) : ?>
                        <span class="curr text_fw700"><?=$currPrice?> ₽</span>
                        <?php if ($cut) : ?>
                            <span class="old text_fw700"><?=$price?> ₽</span>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <span class="isset <?=get_field('isset', $id)['value']?> text_fz14">
                    <?=get_field('isset', $id)['label']?>
                </span>
            </div>
        </div>
    </div>
    <div class="col right-border">
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