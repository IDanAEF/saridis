<?php
    $ourCards = get_field('our-cards', 41);
    $classes = isset($args['classes']) ? $args['classes'] : '';
    
    if ($ourCards) : ?>
    <section class="home__our page__block <?=$classes?>">
        <div class="container">
            <div class="home__our-text elem_animate right">
                <h2 class="page__block-title lined">
                    <?=get_field('our-title', 41) ? deleteP(get_field('our-title', 41)) : '
                        <strong>Почему выбирают</strong> <span class="text_color">наши продукты</span>
                    '?>
                </h2>
                <?php if (get_field('our-descr', 41)) : ?>
                    <div class="default-text">
                        <?=get_field('our-descr', 41)?>
                    </div>
                <?php endif; ?>
                <?=outBtn('Подробнее', '', 'border page__btn', get_permalink(57))?>
            </div>
            <div class="home__our-cards text_white">
                <?php
                    foreach($ourCards as $card) {
                        ?>
                        <article class="home__our-cards-item elem_animate top">
                            <div class="wrap">
                                <img src="<?=$card['icon']?>" alt="our-card">
                                <div class="text">
                                    <strong class="text_fz20 text_fw600"><?=$card['name']?></strong>
                                    <span><?=$card['descr']?></span>
                                </div>
                            </div>
                        </article>
                        <?php
                    }
                ?>
            </div>
        </div>
    </section>
<?php endif; ?>