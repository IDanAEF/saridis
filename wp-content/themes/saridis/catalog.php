<?php 
    /*
        Template Name: Каталог
    */

    get_header(); 

    $getBrand = isset($_GET['filterBrand']) ? $_GET['filterBrand'] : '';
    $getCat = isset($_GET['filterCat']) ? $_GET['filterCat'] : '';
    $getFactory = isset($_GET['filterFactory']) ? $_GET['filterFactory'] : '';
    $getWrap = isset($_GET['filterWrap']) ? $_GET['filterWrap'] : '';
    $getPriceFrom = isset($_GET['price-from']) ? $_GET['price-from'] : '';
    $getPriceTo = isset($_GET['price-to']) ? $_GET['price-to'] : '';
    $getFormat = isset($_COOKIE['catalogFormat']) ? $_COOKIE['catalogFormat'] : '';

    $tax_query = [];
    $factory = [];
    $factoryElems = [];
    $wrap = [];
    $wrapElems = [];
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

        $itemFactory = get_field('factory', $catalogItem->ID);
        $itemWrap = get_field('wrap', $catalogItem->ID);

        if ($minPrice === 0 || $currPrice < $minPrice)
            $minPrice = $currPrice;
        if ($maxPrice === 0 || $currPrice > $maxPrice)
            $maxPrice = $currPrice;

        if ($itemFactory && !in_array($itemFactory, $factory))
            $factory[] = $itemFactory;
        else if ($itemFactory)
            $factoryElems[$itemFactory][] = $catalogItem->ID;

        if ($itemWrap && !in_array($itemWrap, $wrap))
            $wrap[] = $itemWrap;
        else if ($itemWrap)
            $wrapElems[$itemWrap][] = $catalogItem->ID;
    }

    $metaQ = [];
    $catalogArgs = [
        'numberposts' => -1,
        'category'    => 0,
        'orderby'     => 'date',
        'order'       => 'DESC',
        'post_type'   => 'catalog',
        'suppress_filters' => true,
        'tax_query' => $tax_query
    ];

    if ($getFactory !== '') {
        $metaQ['relation'] = 'AND';
        $metaQ[] = [
            'key' => 'factory',
            'value' => $factory[$getFactory]
        ];
    }

    if ($getWrap !== '') {
        $metaQ['relation'] = 'AND';
        $metaQ[] = [
            'key' => 'wrap',
            'value' => $wrap[$getWrap]
        ];
    }
    
    if ($metaQ) $catalogArgs['meta_query'] = $metaQ;
    $catalogDef = get_posts($catalogArgs);

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
                    <!--<?=outBtn('Фильтр', '', 'round catalog__filter-btn desk', '', '', 'filter')?>-->
                    <?=outBtn('Применить', '', 'round catalog__filter-btn mobile')?>
                    <div class="catalog__filter-block pre-check-block">
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
                                    $catsElems = get_objects_in_term($catsItem->term_id, 'cats');
                                    ?>
                                    <label class="checkbox-field-item pre-check-item cat-choose<?=$getCat == $catsItem->term_id ? ' active' : ''?>" data-elems="<?=$catsElems ? implode(',', $catsElems) : ''?>">
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
                    <div class="catalog__filter-block pre-check-block">
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
                                    $brandElems = get_objects_in_term($brandItem->term_id, 'brand');
                                    ?>
                                    <label class="checkbox-field-item pre-check-item catalog__filter-brand<?=$getBrand == $brandItem->term_id ? ' active' : ''?>" data-elems="<?=$brandElems ? implode(',', $brandElems) : ''?>">
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
                    <?php if (count($factory) > 1) : ?>
                        <div class="catalog__filter-block pre-check-block">
                            <div class="top text_fz20 body-click-target not-global active">
                                <span>Производство</span>
                                <img src="<?=THEME_IMAGES?>icons/up.svg" alt="up">
                            </div>
                            <div class="bott checkbox-field radio text_fz14 text_fw400 body-click-content not-global active">
                                <?php
                                    foreach($factory as $factoryKey => $factoryItem) {
                                        ?>
                                        <label class="checkbox-field-item pre-check-item <?=$getFactory == $factoryKey ? ' active' : ''?>" data-elems="<?=isset($factoryElems[$factoryItem]) ? implode(',', $factoryElems[$factoryItem]) : ''?>">
                                            <input type="checkbox" name="filterFactory" value="<?=$factoryKey?>"<?=$getFactory == $factoryKey ? ' checked' : ''?> hidden>
                                            <div class="dot"></div>
                                            <span>
                                                <?=$factoryItem?>
                                            </span>
                                        </label>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($wrap) : ?>
                        <div class="catalog__filter-block pre-check-block">
                            <div class="top text_fz20 body-click-target not-global active">
                                <span>Тип упаковки</span>
                                <img src="<?=THEME_IMAGES?>icons/up.svg" alt="up">
                            </div>
                            <div class="bott checkbox-field radio text_fz14 text_fw400 body-click-content not-global active">
                                <?php
                                    foreach($wrap as $wrapKey => $wrapItem) {
                                        ?>
                                        <label class="checkbox-field-item pre-check-item <?=$getWrap == $wrapKey ? ' active' : ''?>" data-elems="<?=isset($wrapElems[$wrapItem]) ? implode(',', $wrapElems[$wrapItem]) : ''?>">
                                            <input type="checkbox" name="filterWrap" value="<?=$wrapKey?>"<?=$getWrap == $wrapKey ? ' checked' : ''?> hidden>
                                            <div class="dot"></div>
                                            <span>
                                                <?=$wrapItem?>
                                            </span>
                                        </label>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <a href="<?=get_permalink()?>" class="catalog__filter-remove text_fz18 text_fw600">
                        <span>Сбросить фильтры</span>
                        <img src="<?=THEME_IMAGES?>icons/cross.svg" alt="remove">
                    </a>
                </form>
                <div class="catalog__right">
                    <h1 class="page__title text_color elem_animate bott">
                        <?=$getCat ? get_term($getCat)->name : get_the_title()?>
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
                        <div class="catalog__format">
                            <span><strong><?=$catalogCount?></strong> Найденные результаты</span>
                            <div class="catalog__format-wrap">
                                <span data-format="default" class="<?=!$getFormat || $getFormat == 'default' ? 'active' : ''?>">
                                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0 0H3V3H0V0Z" fill="black" />
                                        <path d="M6 0H9V3H6V0Z" fill="black" />
                                        <path d="M12 0H15V3H12V0Z" fill="black" />
                                        <path d="M0 12H3V15H0V12Z" fill="black" />
                                        <path d="M6 12H9V15H6V12Z" fill="black" />
                                        <path d="M12 12H15V15H12V12Z" fill="black" />
                                        <path d="M0 6H3V9H0V6Z" fill="black" />
                                        <path d="M6 6H9V9H6V6Z" fill="black" />
                                        <path d="M12 6H15V9H12V6Z" fill="black" />
                                    </svg>
                                </span>
                                <span data-format="row-detail" class="<?=$getFormat && $getFormat == 'row-detail' ? 'active' : ''?>">
                                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0 0H3V3H0V0Z" fill="black" />
                                        <path d="M6 0H9V3H6V0Z" fill="black" />
                                        <path d="M9 0H12V3H9V0Z" fill="black" />
                                        <path d="M9 6H12V9H9V6Z" fill="black" />
                                        <path d="M9 12H12V15H9V12Z" fill="black" />
                                        <path d="M12 0H15V3H12V0Z" fill="black" />
                                        <path d="M0 12H3V15H0V12Z" fill="black" />
                                        <path d="M6 12H9V15H6V12Z" fill="black" />
                                        <path d="M12 12H15V15H12V12Z" fill="black" />
                                        <path d="M0 6H3V9H0V6Z" fill="black" />
                                        <path d="M6 6H9V9H6V6Z" fill="black" />
                                        <path d="M12 6H15V9H12V6Z" fill="black" />
                                    </svg>
                                </span>
                                <!-- <span data-format="row" class="<?=$getFormat && $getFormat == 'row' ? 'active' : ''?>">
                                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0 0H3V2.5H0V0Z" fill="black" />
                                        <path d="M6 0H9V2.5H6V0Z" fill="black" />
                                        <path d="M3 0H6V2.5H3V0Z" fill="black" />
                                        <path d="M3 4H6V6.5H3V4Z" fill="black" />
                                        <path d="M3 8.5H6V11H3V8.5Z" fill="black" />
                                        <path d="M9 0H12V2.5H9V0Z" fill="black" />
                                        <path d="M9 4H12V6.5H9V4Z" fill="black" />
                                        <path d="M9 8.5H12V11H9V8.5Z" fill="black" />
                                        <path d="M12 0H15V2.5H12V0Z" fill="black" />
                                        <path d="M0 8.5H3V11H0V8.5Z" fill="black" />
                                        <path d="M6 8.5H9V11H6V8.5Z" fill="black" />
                                        <path d="M12 8.5H15V11H12V8.5Z" fill="black" />
                                        <path d="M3 12.5H6V15H3V12.5Z" fill="black" />
                                        <path d="M9 12.5H12V15H9V12.5Z" fill="black" />
                                        <path d="M0 12.5H3V15H0V12.5Z" fill="black" />
                                        <path d="M6 12.5H9V15H6V12.5Z" fill="black" />
                                        <path d="M12 12.5H15V15H12V12.5Z" fill="black" />
                                        <path d="M0 4H3V6.5H0V4Z" fill="black" />
                                        <path d="M6 4H9V6.5H6V4Z" fill="black" />
                                        <path d="M12 4H15V6.5H12V4Z" fill="black" />
                                    </svg>
                                </span> -->
                            </div>
                        </div>
                    </div>
                    <div class="catalog__list <?=$getFormat?> elem_animate top">
                        <?php
                            if (!isset($catalog[0])) echo '<span>Товаров не найдено</span>';
                            else {
                                $tempName = 'includes/catalog-card';

                                if ($getFormat && $getFormat == 'row') 
                                    $tempName = 'includes/catalog-card-row';

                                if ($getFormat && $getFormat == 'row-detail') 
                                    $tempName = 'includes/catalog-card-rowd';

                                foreach($catalog as $catalogItem) {
                                    get_template_part($tempName, null, [
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