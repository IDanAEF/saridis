<?php get_header(); ?>
<main class="page">
    <?php get_template_part('includes/breadcrumbs') ?>
    <section class="page__content page__block pt0">
        <div class="container">
            <h1 class="page__title text_color">
                <?=get_the_title()?>
            </h1>
            <div class="default-text">
                <?=get_field('content')?>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>