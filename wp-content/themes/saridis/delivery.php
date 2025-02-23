<?php 
    /*
        Template Name: Доставка
    */

    get_header(); 
?>
<main class="delivery">
    <?php get_template_part('includes/breadcrumbs') ?>
    <section class="delivery__content page__block pt0">
        <div class="container">
            <h1 class="page__title text_color elem_animate bott">
                <?=get_the_title()?>
            </h1>
            <div class="delivery__cards text_fz20 text_white">
                <?php
                    $deliveryCards = get_field('delivery-cards') ?: [];

                    foreach($deliveryCards as $card) {
                        ?>
                        <article class="delivery__cards-item home__our-cards-item elem_animate top">
                            <div class="wrap">
                                <span><?=deleteP($card['text'])?></span>
                            </div>
                        </article>
                        <?php
                    }
                ?>
            </div>
        </div>
    </section>
    <?php get_template_part('includes/home-feedback') ?>
</main>
<?php 
    get_footer(); 
?>