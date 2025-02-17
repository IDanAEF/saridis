<?php 
    /*
        Template Name: О компании
    */

    get_header(); 
?>
<main class="about">
    <section class="about__promo text_white elem_animate opacity">
        <img src="<?=THEME_IMAGES?>promo-back.jpg" alt="promo-back" class="img_bg">
        <?php get_template_part('includes/breadcrumbs', null, [
            'htmlElem' => 'div',
            'class' => 'text_white'
        ]) ?>
        <div class="container">
            <div class="about__promo-text">
                <h1 class="text_fz120 page__block-title"><?=get_the_title()?></h1>
                <div class="default-text text1 text_fw700 text_fz20">
                    <?=get_field('promo-text1')?>
                </div>
                <div class="default-text text2 text_fw400">
                    <?=get_field('promo-text2')?>
                </div>
            </div>
            <img src="<?=THEME_IMAGES?>about-image-page.png" alt="<?=get_the_title()?>" class="about__promo-image">
        </div>
    </section>
    <?php get_template_part('includes/home-feedback') ?>
</main>
<?php 
    get_footer(); 
?>