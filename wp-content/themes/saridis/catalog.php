<?php 
    /*
        Template Name: Каталог
    */

    get_header(); 

    $getBrand = isset($_GET['filterBrand']) ? $_GET['filterBrand'] : '';
    $getCat = isset($_GET['filterCat']) ? $_GET['filterCat'] : '';
    $getPriceFrom = isset($_GET['price-from']) ? $_GET['price-from'] : '';
    $getPriceTo = isset($_GET['price-to']) ? $_GET['price-to'] : '';

    $tax_query = [];
    $minPrice = 0;
    $maxPrice = 0;

    if ($getBrand && $getCat)
        $tax_query['relation'] = 'AND';

    if ($getCat)
        $tax_query[] = [
            'taxonomy' => 'cats',
            'field' => 'term_id',
            'terms' => $getCat
        ];

    if ($getBrand)
        $tax_query[] = [
            'taxonomy' => 'brand',
            'field' => 'term_id',
            'terms' => $getBrand
        ];

    $catalogArgs = [
        'numberposts' => -1,
        'category'    => 0,
        'orderby'     => 'date',
        'order'       => 'DESC',
        'post_type'   => 'catalog',
        'suppress_filters' => true,
        'tax_query' => $tax_query
    ];
    $catalogDef = get_posts($catalogArgs);
    $catalogAll = get_posts([
        'numberposts' => -1,
        'category'    => 0,
        'orderby'     => 'date',
        'order'       => 'DESC',
        'post_type'   => 'catalog',
        'suppress_filters' => true,
    ]);

    foreach($catalogAll as $catalogItem) {
        $cut = get_field('cut', $catalogItem->ID);
        $price = get_field('price', $catalogItem->ID);
        $currPrice = round($cut != 0 ? ($price - (($price / 100) * $cut)) : $price);

        if ($minPrice === 0 || $currPrice < $minPrice)
            $minPrice = $currPrice;
        if ($maxPrice === 0 || $currPrice > $maxPrice)
            $maxPrice = $currPrice;
    }

    $priceRange =!(!$getPriceFrom || !$getPriceTo || ($getPriceFrom == $minPrice && $getPriceTo == $maxPrice));
    $catalog = $priceRange ? [] : $catalogDef;

    if ($priceRange) {
        foreach($catalogDef as $catalogItem) {
            $cut = get_field('cut', $catalogItem->ID);
            $price = get_field('price', $catalogItem->ID);
            $currPrice = round($cut != 0 ? ($price - (($price / 100) * $cut)) : $price);

            if ($getPriceFrom && $getPriceTo) {
                if ($currPrice >= $getPriceFrom && $currPrice <= $getPriceTo)
                    $catalog[] = $catalogItem;
            }
        }
    }

    $catalogCount = count($catalog);
?>
<main class="catalog">
    <?php get_template_part('includes/breadcrumbs') ?>
    <section class="catalog__content page__block pt0">
        <div class="container">
            <div class="catalog__main">
                <form action="<?=get_permalink()?>" method="GET" class="catalog__filter body-click-content" data-content="catalog-filter">
                    <div class="catalog__filter-close body-click-close"></div>
                    <?=outBtn('Фильтр', '', 'round catalog__filter-btn desk', '', '', 'filter')?>
                    <?=outBtn('Применить', '', 'round catalog__filter-btn mobile')?>
                    <div class="catalog__filter-block">
                        <div class="top text_fz20 body-click-target not-global active">
                            <span>Категории</span>
                            <img src="<?=THEME_IMAGES?>icons/up.svg" alt="up">
                        </div>
                        <div class="bott checkbox-field radio text_fz14 text_fw400 body-click-content not-global active">
                            <?php
                                $cats = get_terms([
                                    'taxonomy' => 'cats',
                                    'hide_empty' => false,
                                ]);

                                foreach($cats as $catsItem) {
                                    ?>
                                    <label class="checkbox-field-item<?=$getCat == $catsItem->term_id ? ' active' : ''?>">
                                        <input type="checkbox" name="filterCat" value="<?=$catsItem->term_id?>"<?=$getCat == $catsItem->term_id ? ' checked' : ''?> hidden>
                                        <div class="dot"></div>
                                        <span>
                                            <?=$catsItem->name?>
                                        </span>
                                    </label>
                                    <?php
                                }
                            ?>
                        </div>
                    </div>
                    <?php if (IS_AUTH) : ?>
                        <div class="catalog__filter-block">
                            <div class="top text_fz20 body-click-target not-global active">
                                <span>Цена</span>
                                <img src="<?=THEME_IMAGES?>icons/up.svg" alt="up">
                            </div>
                            <div class="bott checkbox-field radio text_fz14 text_fw400 body-click-content not-global active">
                                <div class="filter-range">
                                    <div class="filter-range-line">
                                        <span class="line"></span>
                                        <input type="range" name="price-from" class="range-from" min="<?=$minPrice?>" max="<?=$maxPrice?>" value="<?=$getPriceFrom ?: $minPrice?>">
                                        <input type="range" name="price-to" class="range-to" min="<?=$minPrice?>" max="<?=$maxPrice?>" value="<?=$getPriceTo ?: $maxPrice?>">
                                    </div>
                                </div>
                                <div class="filter-price text_fz14">
                                    <span class="from-text"><?=$minPrice?></span> - <span class="to-text"><?=$maxPrice?></span> ₽
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="catalog__filter-block">
                        <div class="top text_fz20 body-click-target not-global active">
                            <span>Бренд</span>
                            <img src="<?=THEME_IMAGES?>icons/up.svg" alt="up">
                        </div>
                        <div class="bott checkbox-field radio text_fz14 text_fw400 body-click-content not-global active">
                            <?php
                                $brand = get_terms([
                                    'taxonomy' => 'brand',
                                    'hide_empty' => false,
                                ]);

                                foreach($brand as $brandItem) {
                                    ?>
                                    <label class="checkbox-field-item catalog__filter-brand<?=$getBrand == $brandItem->term_id ? ' active' : ''?>">
                                        <input type="checkbox" name="filterBrand" value="<?=$brandItem->term_id?>"<?=$getBrand == $brandItem->term_id ? ' checked' : ''?> hidden>
                                        <span>
                                            <?php if (get_field('icon', 'brand_'.$brandItem->term_id)) : ?>
                                                <img src="<?=get_field('icon', 'brand_'.$brandItem->term_id)['sizes']['thumbnail']?>" alt="<?=$brandItem->name?>">
                                            <?php endif; ?>
                                            <?=$brandItem->name?>
                                        </span>
                                    </label>
                                    <?php
                                }
                            ?>
                        </div>
                    </div>
                    <a href="<?=get_permalink()?>" class="catalog__filter-remove text_fz18 text_fw600">
                        <span>Сбросить фильтры</span>
                        <img src="<?=THEME_IMAGES?>icons/cross.svg" alt="remove">
                    </a>
                </form>
                <div class="catalog__right">
                    <h1 class="page__title text_color elem_animate bott">
                        <?=get_the_title()?>
                    </h1>
                    <div class="catalog__info text_fw300">
                        <?=outBtn('Фильтр', '', 'round catalog__filter-call body-click-target', '', 'data-content="catalog-filter"', 'filter')?>
                        <div class="catalog__rating text_fz14 text_fw400">
                            <span>Сортировать по:</span>
                            <div class="catalog__rating-wrap">
                                <div class="catalog__rating-name body-click-target">
                                    <span>По умолчанию</span>
                                    <img src="<?=THEME_IMAGES?>icons/up.svg" alt="up">
                                </div>
                                <div class="catalog__rating-list body-click-content">
                                    <span data-sort="default">По умолчанию</span>
                                    <span data-sort="rating">По рейтингу</span>
                                    <span data-sort="price-up">Цена, по возрастанию</span>
                                    <span data-sort="price-down">Цена, по убыванию</span>
                                </div>
                            </div>
                        </div>
                        <span><strong><?=$catalogCount?></strong> Найденные результаты</span>
                    </div>
                    <div class="catalog__list elem_animate top">
                        <?php
                            if (!isset($catalog[0])) echo '<span>Товаров не найдено</span>';
                            else {
                                foreach($catalog as $catalogItem) {
                                    get_template_part('includes/catalog-card', null, [
                                        'id' => $catalogItem->ID,
                                        'class' => 'hide'
                                    ]);
                                }
                            }
                        ?>
                    </div>
                    <?=outBtn('Показать ещё', '', 'page__btn catalog__more')?>
                </div>
            </div>
        </div>
    </section>
    <?php get_template_part('includes/home-partners') ?>
    <?php get_template_part('includes/home-recipes') ?>
    <?php get_template_part('includes/home-feedback') ?>
</main>
<?php 
    get_footer(); 
?>