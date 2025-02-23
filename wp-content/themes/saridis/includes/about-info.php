<?php
    $aboutCards = get_field('partn-cards', 53);

    if ($aboutCards) : ?>
    <section class="about__info page__block">
        <div class="container">
            <div class="about__info-text page__title elem_animate right">
                <h2 class="page__block-title lined">
                    <?=deleteP(get_field('partn-title', 53))?>
                </h2>
                <div class="default-text">
                    <?=get_field('partn-descr', 53)?>
                </div>
            </div>
            <div class="about__info-cards">
                <?php
                    foreach($aboutCards as $card) {
                        ?>
                        <div class="about__info-cards-item elem_animate top">
                            <img src="<?=$card['image']?>" alt="about-info">
                            <span><?=$card['descr']?></span>
                        </div>
                        <?php
                    }
                ?>
                <?php if (get_field('partn-garant', 53)) : ?>
                    <div class="about__info-cards-garant elem_animate top text_white text_upper">
                        <span><?=deleteP(get_field('partn-garant', 53))?></span>
                        <img src="<?=THEME_IMAGES?>about-garant.png" alt="Гарантия">
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php endif; ?>