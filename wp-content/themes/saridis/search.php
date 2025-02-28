<?php 
    /*
        Template Name: Поиск
    */

    get_header();

    $catalog = get_posts([
        'numberposts' => -1,
        'category'    => 0,
        'orderby'     => 'date',
        'order'       => 'DESC',
        'post_type'   => 'catalog',
        'suppress_filters' => true,
    ]);

    $resp = isset($_GET['resp']) ? $_GET['resp'] : '';
?>
<main class="search">
    <?php get_template_part('includes/breadcrumbs') ?>
    <section class="search__content page__block pt0">
        <div class="container">
            <h1 class="page__block-title text_color elem_animate bott">
                <?=get_the_title()?>
            </h1>
            <form action="<?=get_permalink()?>" method="GET" class="search__form page__title">
                <div class="form-side">
                    <label class="form-label">
                        <input type="text" name="resp" value="<?=$resp?>" placeholder="Введите название товара">
                    </label>
                    <?=outBtn('Найти')?>
                </div>
            </form>
            <div class="catalog__list four elem_animate top">
                <?php
                    if (!isset($catalog[0])) echo '<span>Товаров не найдено</span>';
                    else {
                        $found = false;

                        foreach($catalog as $catalogItem) {
                            $titleLower = mb_strtolower(get_the_title($catalogItem->ID));
                            $respLower = mb_strtolower($resp);

                            if (!$resp || ($resp && strpos($titleLower, $respLower) !== false)) {
                                get_template_part('includes/catalog-card', null, [
                                    'id' => $catalogItem->ID,
                                    'class' => 'hide'
                                ]);

                                $found = true;
                            }
                        }

                        if (!$found) echo '<span>Товаров не найдено</span>';
                    }
                ?>
            </div>
            <?=outBtn('Показать ещё', '', 'page__btn catalog__more')?>
        </div>
    </section>
    <?php get_template_part('includes/home-feedback') ?>
</main>
<?php get_footer(); ?>