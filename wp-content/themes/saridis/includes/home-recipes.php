<?php
    $recipes = get_posts(array(
        'numberposts' => 4,
        'category'    => 0,
        'orderby'     => 'date',
        'order'       => 'DESC',
        'post_type'   => 'recipes',
        'suppress_filters' => true,
    ));

    if (isset($recipes[0])) :
?>
<section class="home__recipes page__block">
    <div class="container">
        <h2 class="page__title elem_animate bott">
            <?=get_field('rec-title', 41) ?: '
                <span class="text_color">Рецепты греческих блюд</span>
            '?>
        </h2>
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
<?php endif; ?>