<?php 
    /*
        Template Name: Контакты
    */
    get_header();
?>
<main class="contacts">
    <?php get_template_part('includes/breadcrumbs') ?>
    <section class="contacts__content page__block pt0">
        <div class="container">
            <h1 class="page__title text_color elem_animate bott">
                <?=get_the_title()?>
            </h1>
            <div class="contacts__content-row">
                <div class="contacts__content-text text_fz18">
                    <div class="contacts__content-col">
                        <div class="contacts__content-item elem_animate top">
                            <img src="<?=THEME_IMAGES?>icons/phone-color.svg" alt="Телефон">
                            <div class="text">
                                <span>Телефоны для связи:</span>
                                <a href="tel:<?=getThinPhone(get_field('phone', 'option'))?>">
                                    <strong><?=get_field('phone', 'option')?></strong>
                                </a>
                            </div>
                        </div>
                        <div class="contacts__content-item elem_animate top">
                            <img src="<?=THEME_IMAGES?>icons/email.svg" alt="E-mail">
                            <div class="text">
                                <span>E-mail:</span>
                                <a href="mailto:<?=get_field('email', 'option')?>">
                                    <?=get_field('email', 'option')?>
                                </a>
                            </div>
                        </div>
                        <div class="contacts__content-item elem_animate top">
                            <?=outBtn('В магазин', '', '', get_permalink(57))?>
                        </div>
                    </div>
                    <div class="contacts__content-col">
                        <div class="contacts__content-item elem_animate top">
                            <img src="<?=THEME_IMAGES?>icons/time.svg" alt="График работы">
                            <div class="text">
                                <span>График работы:</span>
                                <strong><?=get_field('time', 'option')?></strong>
                            </div>
                        </div>
                        <div class="contacts__content-item elem_animate top">
                            <img src="<?=THEME_IMAGES?>icons/map-point.svg" alt="Мы находимся по адресу">
                            <div class="text">
                                <span>Мы находимся по адресу:</span>
                                <strong><?=get_field('address', 'option')?></strong>
                            </div>
                        </div>
                        <div class="contacts__content-item elem_animate top">
                            <div class="text">
                                <span>Наши магазины</span>
                                <a href="<?=get_field('map-link', 'option')?>" target="_blank" class="text_color">
                                    <img src="<?=THEME_IMAGES?>icons/map-list.svg" alt="Открыть в навигаторе">
                                    Открыть в навигаторе
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="contacts__content-map elem_animate opacity" id="contacts-map"></div>
            </div>
        </div>
    </section>
    <?php get_template_part('includes/home-feedback') ?>
</main>
<?php if (get_field('map-coord', 'option')) : ?>
    <script src="https://api-maps.yandex.ru/2.1/?apikey=5727f775-e56b-498e-bb2a-a48a1702a7f7&amp;lang=ru_RU"></script>
    <script>
        ymaps.ready(function () {
            var contactsMap = new ymaps.Map('contacts-map', {
                center: [<?=get_field('map-coord', 'option')?>],
                zoom: 17
            }, {
                searchControlProvider: 'yandex#search'
            });

            contactsMapPlacemark = new ymaps.Placemark([<?=get_field('map-coord', 'option')?>], {
                hintContent: '<?=get_field('address', 'option')?>',
            });

            contactsMap.geoObjects.add(contactsMapPlacemark);
        });
    </script>
<?php endif; ?>
<?php get_footer(); ?>