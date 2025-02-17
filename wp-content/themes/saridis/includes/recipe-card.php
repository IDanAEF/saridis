<?php
    $date = $args['date'];
    $title = $args['title'];
    $descr = isset($args['descr']) ? $args['descr'] : '';
    $userId = $args['userId'];
    $image = $args['image'];
    $link = $args['link'];

    $userName = get_user_meta($userId, 'company', true) ?: get_user_option('display_name', $userId);
    $userAvatar = getUserAvatar($userId);
?>
<article class="home__recipes-cards-item elem_animate top">
    <a href="<?=$link?>" class="image">
        <picture>
            <img src="<?=$image['sizes']['medium_large']?>" alt="<?=getClearText($title)?>" class="img_bg">
        </picture>
    </a>
    <div class="text">
        <div class="top">
            <div class="date text_fz14 text_fw400">
                <?=$date?>
            </div>
            <h3 class="text_fz16 text_fw500">
                <a href="<?=$link?>">
                    <?=$title?>
                </a>
            </h3>
            <?php if ($descr) : ?>
                <p class="text_fz14">
                    <?=$descr?>
                </p>
            <?php endif; ?>
        </div>
        <div class="bott">
            <div class="user">
                <div class="avatar-wrap">
                    <?=$userAvatar?>
                </div>
                <span><?=$userName?></span>
            </div>
            <div class="links">
                <a href="">
                    <img src="<?=THEME_IMAGES?>icons/mess.svg" alt="mess">
                </a>
                <a href="">
                    <img src="<?=THEME_IMAGES?>icons/share.svg" alt="share">
                </a>
            </div>
        </div>
    </div>
</article>