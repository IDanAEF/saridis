<?php get_header(); ?>
<main class="page">
    <?php get_template_part('includes/breadcrumbs') ?>
    <section class="page__content page__block pt0">
        <div class="container">
            <h1 class="page__title text_color elem_animate bott">
                <?=get_the_title()?>
            </h1>
            <div class="default-text elem_animate opacity">
                <?=get_field('content')?>
            </div>
        </div>
    </section>
    <?php get_template_part('includes/home-feedback') ?>
</main>
<?php get_footer(); ?>