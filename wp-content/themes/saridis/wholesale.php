<?php 
    /*
        Template Name: Оптовым покупателям
    */

    get_header(); 
?>
<main class="wholesale">
    <section class="home__about">
        <div class="container">
            <div class="home__about-text elem_animate right">
                <h1 class="text_color">
                    <?=get_the_title()?>
                </h1>
                <?php if (get_field('promo-under')) : ?>
                    <div class="text">
                        <div class="default-text">
                            <?=get_field('promo-under')?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <img src="<?=THEME_IMAGES?>about-image.png" alt="О нас" class="home__about-image elem_animate opacity">
        </div>
    </section>
    <?php get_template_part('includes/home-our', null, [
        'classes' => 'pt100'
    ]) ?>
    <?php get_template_part('includes/about-info') ?>
    <?php get_template_part('includes/home-partners') ?>
    <?php get_template_part('includes/home-feedback') ?>
</main>
<?php 
    get_footer(); 
?>