<?php get_header(); ?>
<main class="page">
    <?php get_template_part('includes/breadcrumbs', null, [
        'end' => 'Ошибка 404'
    ]) ?>
    <section class="page__404 page__block pt0">
        <div class="container">
            <h1 class="page__title text_color">Ошибка 404</h1>
            <div class="default-text">
                <h2>Страница не найдена</h2>
                <p>Вы неправильно набрали адрес или такой страницы больше не существует</p>
                <p><a href="<?=get_home_url()?>">Перейти на главную страницу</a></p>
            </div>
        </div>
    </section>
</main>
<?php get_footer(); ?>