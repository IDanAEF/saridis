<?php
    /*
        Template Name: Главная страница
    */
    get_header();
?>
<main class="home">
    <h1 style="display: none;"><?=get_bloginfo('name')?></h1>
    <section class="home__promo slider text_white elem_animate opacity">
        <img src="<?=THEME_IMAGES?>promo-back.jpg" alt="promo-back" class="img_bg">
        <img src="<?=THEME_IMAGES?>promo-line.png" alt="promo-back" class="home__promo-line">

        <div class="slider-list">
            <div class="slider-track">
                <?php
                    $promoBanners = get_field('promo-banners') ?: [];
                    $promoBannersCount = count($promoBanners);

                    foreach($promoBanners as $bannerKey => $banner) {
                        ?>
                        <div class="home__promo-item slide">
                            <div class="container">
                                <?php if ($banner['title1'] || $banner['title2']) : ?>
                                    <h2 class="page__block-title text_fz120 text_upper">
                                        <?php if ($banner['title1']) : ?>
                                            <span><?=$banner['title1']?></span>
                                        <?php endif; ?>
                                        <?php if ($banner['title2']) : ?>
                                            <span><?=$banner['title2']?></span>
                                        <?php endif; ?>
                                    </h2>
                                <?php endif; ?>
                                <?php if ($banner['image']) : ?>
                                    <picture>
                                        <img src="<?=$banner['image']['sizes']['large']?>" alt="promo-image" class="home__promo-image">
                                    </picture>
                                <?php endif; ?>
                                <div class="text">
                                    <?php if ($banner['descr']) : ?>
                                        <p class="home__promo-descr"><?=$banner['descr']?></p>
                                    <?php endif; ?>
                                    <?php if ($banner['descr']) : ?>
                                        <?=outBtn('Подробнее', '', 'page__btn border-white', $banner['link'])?>
                                    <?php endif; ?>
                                    <?php if ($promoBannersCount > 1) : ?>
                                        <div class="slider-toggle text_fz18 text_fw600">
                                            <?php
                                                for($j = 0; $j < $promoBannersCount; $j++) {
                                                    ?>
                                                    <span class="<?=$j === $bannerKey ? 'active' : ''?>">0<?=($j + 1)?></span>
                                                    <?php
                                                }
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                ?>
            </div>
        </div>
    </section>
    <section class="home__about">
        <div class="container">
            <div class="home__about-text elem_animate right">
                <h2 class="text_fz20 text_fw700">
                    <?=get_field('about-title') ? deleteP(get_field('about-title')) : '
                        <strong><span class="text_color">Саридис</span></strong> — компания с многолетним опытом производства оливкового масла, которая сохранила традиции и любовь к этому делу по сей день.
                    '?>
                </h2>
                <div class="text">
                    <?php if (get_field('about-descr')) : ?>
                        <div class="default-text">
                            <?=get_field('about-descr')?>
                        </div>
                    <?php endif; ?>
                    <?=outBtn('Подробнее', '', 'border page__btn', get_permalink(53))?>
                </div>
            </div>
            <img src="<?=THEME_IMAGES?>about-image.png" alt="О нас" class="home__about-image elem_animate opacity">
        </div>
    </section>
    <?php
        $cats = get_terms([
            'taxonomy' => 'cats',
            'hide_empty' => false,
        ]);

        if (isset($cats[0])) : ?>
        <section class="home__cats page__block">
            <div class="container">
                <div class="home__cats-cards text_fz22 text_center">
                    <?php
                        foreach($cats as $catsItem) {
                            if (!get_field('in-main', $catsItem->taxonomy.'_'.$catsItem->term_id) || !get_field('poster', $catsItem->taxonomy.'_'.$catsItem->term_id)) continue;
                            ?>
                            <a href="<?=get_permalink(57)?>?filterCat=<?=$catsItem->term_id?>" class="home__cats-cards-item no-hover elem_animate top">
                                <picture>
                                    <img src="<?=get_field('poster', $catsItem->taxonomy.'_'.$catsItem->term_id)['sizes']['medium']?>" alt="<?=$catsItem->name?>" class="poster">
                                </picture>
                                <span><?=$catsItem->name?></span>
                                <img src="<?=THEME_IMAGES?>icons/cat-plus.svg" alt="plus" class="plus">
                            </a>
                            <?php
                        }
                    ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
    <?php get_template_part('includes/home-our') ?>
    <section class="home__how page__block">
        <div class="container">
            <div class="home__how-wrap">
                <div class="home__how-left elem_animate right">
                    <h2 class="page__block-title lined">
                        <?=get_field('how-title') ? deleteP(get_field('how-title')) : '
                            <span class="text_color">Как заказать?</span>
                        '?>
                    </h2>
                    <?php if (get_field('how-descr')) : ?>
                        <div class="default-text">
                            <?=get_field('how-descr')?>
                        </div>
                    <?php endif; ?>
                    <?=outBtn('Подробнее', '', 'border page__btn', get_permalink(55))?>
                </div>
                <img src="<?=THEME_IMAGES?>how-phone1.png" alt="phone1" class="phone phone1 elem_animate opacity">
                <?php if (get_field('how-title2') || get_field('how-descr2')) :?>
                    <div class="home__how-right default-text elem_animate left">
                        <?php if (get_field('how-title2')) :?>
                            <div class="title text_fz24">
                                <?=get_field('how-title2')?>
                            </div>
                        <?php endif; ?>
                        <?php if (get_field('how-descr2')) :?>
                            <div class="descr">
                                <?=get_field('how-descr2')?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <img src="<?=THEME_IMAGES?>how-phone2.png" alt="phone2" class="phone phone2 elem_animate opacity">
                <?php endif; ?>
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