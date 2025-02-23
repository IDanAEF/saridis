<?php 
    /*
        Template Name: Рецепты
    */
    get_header();

    $recipes = get_posts(array(
        'numberposts' => -1,
        'category'    => 0,
        'orderby'     => 'date',
        'order'       => 'DESC',
        'post_type'   => 'recipes',
        'suppress_filters' => true,
    ));
?>
<main class="recipes">
    <?php get_template_part('includes/breadcrumbs') ?>
    <section class="recipes__content page__block pt0">
        <div class="container">
            <h1 class="page__title elem_animate bott">
                <?=get_field('main-title') ? deleteP(get_field('main-title')) : get_the_title()?>
            </h1>
            <div class="home__recipes-cards">
                <?php
                    foreach($recipes as $recipe) {
                        get_template_part('includes/recipe-card', null, [
                            'id' => $recipe->ID,
                            'userId' => $recipe->post_author
                        ]);
                    }
                ?>
            </div>
        </div>
    </section>
    <?php get_template_part('includes/home-feedback') ?>
</main>
<?php get_footer(); ?>