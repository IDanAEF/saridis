<?php 
    /*
        Template Name: Детальная рецепта
        Post Type: recipes
    */

    get_header(); 

    global $post;
?>
<main class="single-recipe">
    <?php get_template_part('includes/breadcrumbs', null, [
        'middle' => [
            [get_the_title(67), get_permalink(67)]
        ]
    ]) ?>
    <section class="single-recipe__content page__block pt0">
        <div class="container">
            <h1 class="page__title text_color elem_animate bott">
                <?=get_the_title()?>
            </h1>
            <div class="single-recipe__main">
                <div class="single-recipe__left">
                    <?php if (get_field('detail-image')) : ?>
                        <picture>
                            <img src="<?=get_field('detail-image')['url']?>" alt="<?=get_the_title()?>" class="single-recipe__image elem_animate top">
                        </picture>
                    <?php endif; ?>
                    <div class="single-recipe__head elem_animate top">
                        <div class="single-recipe__user">
                            <div class="avatar">
                                <?=getUserAvatar($post->post_author);?>
                            </div>
                            <div class="text">
                                <div class="name">
                                    <?=get_user_meta($post->post_author, 'company', true) ?: get_user_option('display_name', $post->post_author)?>
                                </div>
                                <div class="meta text_fz14 text_fw400">
                                    <span class="date">
                                        <?=get_the_date('d.m.Y')?>
                                    </span>
                                    <?php if (get_field('preview-time')) : ?>
                                        <span>•</span>
                                        <span class="read">
                                            <?=get_field('preview-time')?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (get_field('detail-descr')) : ?>
                        <div class="single-recipe__descr default-text text_fw400 elem_animate opacity">
                            <?=get_field('detail-descr')?>
                        </div>
                    <?php endif; ?>
                    <?php
                        $steps = get_field('detail-steps');
                        
                        if ($steps) : ?>
                        <div class="single-recipe__steps">
                            <?php
                                foreach($steps as $stepKey => $step) {
                                    ?>
                                    <article class="single-recipe__steps-item elem_animate top">
                                        <?php if ($step['image']) : ?>
                                            <img src="<?=$step['image']['sizes']['medium']?>" alt="Шаг <?=($stepKey + 1)?>">
                                        <?php endif; ?>
                                        <div class="text">
                                            <h2 class="text_fz30 page__block-title">Шаг <?=($stepKey + 1)?></h2>
                                            <div class="default-text text_fz18">
                                                <?=$step['descr']?>
                                            </div>
                                        </div>
                                    </article>
                                    <?php
                                }
                            ?>
                        </div>
                    <?php endif; ?>
                    <?php if (IS_AUTH) : ?>
                        <div class="single-recipe__reviews page__block pb0">
                            <strong class="page__block-title text_fz24 text_fw500">
                                Оставить комментарий
                            </strong>
                            <form action="<?=admin_url('admin-ajax.php')?>?action=newcomment" class="form p0" data-success="comment">
                                <input type="text" name="feedreview-post" value="<?=$post->ID?>" hidden>
                                <input type="text" name="feedreview-user" value="<?=USER_ID?>" hidden>

                                <label class="form-label">
                                    <span class="text_fz14">Комментарий</span>
                                    <textarea name="feedreview-text" class="big"></textarea>
                                </label>
                                <div class="form-label">
                                    <?=outBtn('Отправить', '', 'fit')?>
                                </div>

                                <input type="text" name="recaptcha" hidden>
                            </form>
                        </div>
                    <?php endif; ?>
                    <?php
                        $comments = get_comments([
                            'no_found_rows' => true,
                            'orderby' => 'comment_date',
                            'order' => 'DESC',
                            'post_id' => $post->ID,
                            'status' => 'approve',
                            'count' => false,
                            'date_query' => null,
                            'hierarchical' => false,
                            'update_comment_meta_cache' => true,
                            'update_comment_post_cache' => false,
                        ]);

                        if (isset($comments[0])) : ?>
                        <div class="single-recipe__comments page__block pb0 showhide-field">
                            <strong class="page__block-title text_fz24 text_fw500">
                                Комментарии
                            </strong>
                            <div class="single-recipe__comments-list text_fz14 showhide-list">
                                <?php
                                    foreach($comments as $comm) {
                                        ?>
                                        <article class="comment-item showhide-item hide">
                                            <div class="avatar-wrap">
                                                <?=getUserAvatar($comm->user_id)?>
                                            </div>
                                            <div class="comment-text">
                                                <div class="comment-top">
                                                    <span class="comment-name">
                                                        <?=get_user_meta($comm->user_id, 'company', true) ?: get_user_option('display_name', $comm->user_id)?>
                                                    </span>
                                                    <span>•</span>
                                                    <span class="comment-date">
                                                        <?=date('d.m.Y H:i', strtotime($comm->comment_date))?>
                                                    </span>
                                                </div>
                                                <div class="comment-descr">
                                                    <?=$comm->comment_content?>
                                                </div>
                                            </div>
                                        </article>
                                        <?php
                                    }
                                ?>
                            </div>
                            <?=outBtn('Показать ещё', '', 'border page__btn showhide-more')?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="single-recipe__right elem_animate left">
                    <?php
                        $gallery = get_field('detail-gallery');
                        
                        if ($gallery) : ?>
                        <div class="single-recipe__right-item">
                            <strong class="text_fz20 text_fw500">Галерея</strong>
                            <div class="wrap">
                                <div class="single-recipe__gallery">
                                    <?php
                                        foreach($gallery as $image) {
                                            ?>
                                            <a href="<?=$image['url']?>" data-fancybox="recipe-gallery">
                                                <img src="<?=$image['sizes']['thumbnail']?>" alt="<?=get_the_title()?> - Галерея">
                                            </a>
                                            <?php
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php
                        $recipes = get_posts(array(
                            'numberposts' => 3,
                            'category'    => 0,
                            'exlude'      => [$post->ID],
                            'orderby'     => 'date',
                            'order'       => 'DESC',
                            'post_type'   => 'recipes',
                            'suppress_filters' => true,
                        ));

                        if (isset($recipes[0])) :?>
                        <div class="single-recipe__right-item">
                            <strong class="text_fz20 text_fw500">Недавно добавленный</strong>
                            <div class="wrap">
                                <?php
                                    foreach($recipes as $recipe) {
                                        ?>
                                        <a href="<?=get_permalink($recipe->ID)?>" class="single-recipe__recent">
                                            <img src="<?=get_field('preview-image', $recipe->ID)['sizes']['thumbnail']?>" alt="<?=get_the_title($recipe->ID)?>">
                                            <div class="text">
                                                <strong class="text_fw500"><?=get_the_title($recipe->ID)?></strong>
                                                <span class="text_fz14"><?=get_the_date('d.m.Y', $recipe->ID)?></span>
                                            </div>
                                        </a>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <?php get_template_part('includes/home-feedback') ?>
</main>
<?php get_footer(); ?>