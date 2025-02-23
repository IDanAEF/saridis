<?php 
    /*
        Template Name: Детальная товара
        Post Type: catalog
    */

    get_header(); 

    global $post;

    $cut = get_field('cut') ?: 0;
    $price = get_field('price') ?: 0;
    $currPrice = round($cut != 0 ? ($price - (($price / 100) * $cut)) : $price);
?>
<main class="single-catalog">
    <?php get_template_part('includes/breadcrumbs', null, [
        'middle' => [
            [get_the_title(57), get_permalink(57)]
        ]
    ]) ?>
    <section class="single-catalog__main page__block pt0">
        <div class="container">
            <?php
                $gallery = get_field('gallery') ?: (get_field('preview-image') ? [get_field('preview-image')] : [THEME_IMAGES.'no-image.jpg']);
            ?>
            <div class="single-catalog__left elem_animate right">
                <div class="single-catalog__slider">
                    <img src="<?=THEME_IMAGES?>icons/slider-arrow.svg" alt="slider-arrow" class="arrow arrow-prev">
                    <div class="single-catalog__slider-list">
                        <div class="single-catalog__slider-track">
                            <?php
                                foreach($gallery as $galleryKey => $galleryItem) {
                                    ?>
                                    <img src="<?=isset($galleryItem['sizes']) ? $galleryItem['sizes']['thumbnail'] : $galleryItem?>" alt="<?=get_the_title()?>" class="<?=$galleryKey == 0 ? 'active' : ''?>">
                                    <?php 
                                }
                            ?>
                        </div>
                    </div>
                    <img src="<?=THEME_IMAGES?>icons/slider-arrow.svg" alt="slider-arrow" class="arrow arrow-next">
                </div>
                <div class="single-catalog__big">
                    <?php
                        foreach($gallery as $galleryKey => $galleryItem) {
                            ?>
                            <picture>
                                <img src="<?=isset($galleryItem['sizes']) ? $galleryItem['sizes']['large'] : $galleryItem?>" alt="<?=get_the_title()?>" class="<?=$galleryKey == 0 ? 'active' : ''?>">
                            </picture>
                            <?php 
                        }
                    ?>
                </div>
            </div>
            <div class="single-catalog__right elem_animate left">
                <div class="single-catalog__head">
                    <span class="isset <?=get_field('isset')['value']?> text_fz14">
                        <?=get_field('isset')['label']?>
                    </span>
                    <h1 class="text_fz40">
                        <?=get_field('big-title') ?: get_the_title()?>
                    </h1>
                    <?php if (IS_AUTH && $currPrice != 0) : ?>
                        <div class="price">
                            <?php if ($cut != 0) : ?>
                                <div class="old text_fz20">
                                    <?=$price?> ₽
                                </div>
                            <?php endif; ?>
                            <div class="curr text_fz24 text_color">
                                <?=$currPrice?> ₽
                            </div>
                            <?php if ($cut != 0) : ?>
                                <div class="cut text_fz14">
                                    <?=$cut?>% Скидка
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php
                    $brand = get_field('brand');
                    
                    if ($brand || get_field('descr')) : ?>
                    <div class="single-catalog__descr">
                        <div class="top">
                            <?php if ($brand) : ?>
                                <div class="brand text_fz14 text_fw400">
                                    <span>Бренд:</span>
                                    <?php if (get_field('icon', 'brand_'.$brand->term_id)) : ?>
                                        <img src="<?=get_field('icon', 'brand_'.$brand->term_id)['sizes']['thumbnail']?>" alt="<?=$brand->name?>">
                                    <?php else : ?>
                                        <span class="text_color text_fw500"> <?=$brand->name?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if (get_field('descr')) : ?>
                            <div class="default-text text_fz14 text_fw400">
                                <?=get_field('descr')?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php if (
                        get_field('factory') || 
                        get_field('size') || 
                        get_field('wrap') || 
                        get_field('garanty')
                    ) : ?>
                    <div class="single-catalog__params text_fz14">
                        <?php if (get_field('factory')) : ?>
                            <div class="single-catalog__params-item">
                                <span>Производство:</span>
                                <span class="text_fz400"><?=get_field('factory')?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (get_field('size')) : ?>
                            <div class="single-catalog__params-item">
                                <span>Объем:</span>
                                <span class="text_fz400"><?=get_field('size')?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (get_field('wrap')) : ?>
                            <div class="single-catalog__params-item">
                                <span>Тип упаковки:</span>
                                <span class="text_fz400"><?=get_field('wrap')?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (get_field('garanty')) : ?>
                            <div class="single-catalog__params-item">
                                <span>Срок годности:</span>
                                <span class="text_fz400"><?=get_field('garanty')?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <div class="single-catalog__bott cart-add-parent" data-id="<?=$post->ID?>">
                    <?php if (IS_AUTH) : ?>
                        <?php if ($currPrice != 0) : ?>
                            <?=outCounter()?>
                            <?=outBtn('Добавить в корзину', 18, 'cart-add', '', 'data-call-modal="cart" data-id="'.$post->ID.'" data-price="'.$currPrice.'"')?>
                        <?php endif; ?>
                        <?=outWishBtn($post->ID)?>
                    <?php else : ?>
                        <?=outBtn('Войти', 18, '', '', 'data-call-modal="auth"')?>
                    <?php endif; ?>
                </div>
                <?php
                    $comments = get_comments([
                        'no_found_rows' => true,
                        'orderby' => 'comment_date',
                        'order' => 'DESC',
                        'post_id' => $post->ID,
                        'status' => 'approve',
                        'count' => false,
                        'date_query' => null,
                        'hierarchical' => false,
                        'update_comment_meta_cache' => true,
                        'update_comment_post_cache' => false,
                    ]);

                    if (isset($comments[0])) : ?>
                    <div class="single-catalog__comments showhide-field" data-vis="4">
                        <strong class="page__block-title text_fz24 text_fw500">
                            Комментарии
                        </strong>
                        <div class="single-recipe__comments-list text_fz14 showhide-list">
                            <?php
                                foreach($comments as $comm) {
                                    ?>
                                    <article class="comment-item showhide-item hide">
                                        <div class="avatar-wrap">
                                            <?=getUserAvatar($comm->user_id)?>
                                        </div>
                                        <div class="comment-text">
                                            <div class="comment-top">
                                                <span class="comment-name"><?=get_user_meta($comm->user_id, 'company', true) ?: get_user_option('display_name', $comm->user_id)?></span>
                                                <span>•</span>
                                                <span class="comment-date">
                                                    <?=date('d.m.Y H:i', strtotime($comm->comment_date))?>
                                                </span>
                                            </div>
                                            <div class="comment-descr">
                                                <?=$comm->comment_content?>
                                            </div>
                                        </div>
                                    </article>
                                    <?php
                                }
                            ?>
                        </div>
                        <?=outBtn('Показать ещё', '', 'border page__btn showhide-more')?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php
        $args = [
            'numberposts' => 4,
            'category'    => 0,
            'orderby'     => 'date',
            'order'       => 'DESC',
            'post_type'   => 'catalog',
            'suppress_filters' => true,
        ];

        if (get_field('cats')) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'cats',
                    'field' => 'term_id',
                    'terms' => get_field('cats')->term_id
                ]
            ];
        }

        $catalog = get_posts($args);
        
        if (isset($catalog[0])) : ?>
        <section class="single-catalog__simular page__block">
            <div class="container">
                <h2 class="page__title text_color elem_animate bott">С этим покупают</h2>
                <div class="catalog__list four">
                    <?php
                        foreach($catalog as $catalogItem) {
                            get_template_part('includes/catalog-card', null, [
                                'id' => $catalogItem->ID
                            ]);
                        }
                    ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
    <?php get_template_part('includes/home-partners') ?>
    <?php get_template_part('includes/home-recipes') ?>
    <?php get_template_part('includes/home-feedback') ?>
</main>
<?php get_footer(); ?>