<?php
    $partners = get_field('partners-gallery', 41);
    
    if ($partners) : ?>
    <section class="home__partners page__block">
        <div class="home__partners-wrap">
            <?php
                for($i = 0; $i < 3; $i++) {
                    ?>
                    <div class="home__partners-line infinite-line">
                        <?php
                            foreach($partners as $partner) {
                                ?>
                                <img src="<?=$partner['sizes']['medium']?>" alt="partner">
                                <?php
                            }
                        ?>
                    </div>
                    <?php
                }
            ?>
        </div>
    </section>
<?php endif; ?>