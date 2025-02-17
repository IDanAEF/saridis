<?php
    $middle = isset($args['middle']) ? $args['middle'] : [];
    $end = isset($args['end']) ? $args['end'] : '';
    $htmlElem = isset($args['htmlElem']) ? $args['htmlElem'] : '';
    $class = isset($args['class']) ? $args['class'] : '';

    echo '<'.($htmlElem ?: 'section').' class="page__breadcrumbs elem_animate opacity">';
?>
    <div class="container">
        <div class="page__breadcrumbs-wrap <?=$class?>">
            <a href="<?=get_home_url()?>">
                <img src="<?=THEME_IMAGES?>icons/home.svg" alt="Главная">
                <span>Главная</span>
            </a>
            <img src="<?=THEME_IMAGES?>icons/bread-arrow.svg" alt="bread-arrow">
            <?php
                foreach($middle as $link) {
                    ?>
                    <a href="<?=$link[1]?>"><?=$link[0]?></a>
                    <img src="<?=THEME_IMAGES?>icons/bread-arrow.svg" alt="bread-arrow">
                    <?php
                }
            ?>
            <span class="text_color"><?=$end ?: get_the_title()?></span>
        </div>
    </div>
<?php
    echo '</'.($htmlElem ?: 'section').'>';
?>