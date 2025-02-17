<section class="home__feedback page__block elem_animate top">
    <div class="container">
        <div class="home__feedback-wrap">
            <img src="<?=THEME_IMAGES?>feedback-back.jpg" alt="feedback-back" class="img_bg">
            <img src="<?=THEME_IMAGES?>feedback-image.png" alt="feedback-image" class="home__feedback-image">

            <div class="home__feedback-content">
                <div class="text text_white">
                    <h2 class="page__block-title">
                        <?=get_field('feedback-title', 41) ? deleteP(get_field('feedback-title', 41)) : 'Если у вас есть вопросы или предложения, свяжитесь с&nbsp;нашей командой'?>
                    </h2>
                    <p>
                        <?=get_field('feedback-descr', 41) ?: 'Мы всегда готовы выслушать вас и предложить лучшее решение для вашего бизнеса'?>
                    </p>
                </div>
                <form action="<?=admin_url('admin-ajax.php')?>?action=feedback" class="form">
                    <input type="text" name="feedtheme" value="Задать вопрос" hidden>
                    <input type="text" name="recaptcha" hidden>

                    <strong class="form-head text_fz24 text_upper text_center">
                        Задать вопрос
                    </strong>

                    <div class="form-wrap">
                        <label class="form-label">
                            <span class="text_fz12">Ваше имя</span>
                            <input type="text" name="feedname" required>
                        </label>
                        <label class="form-label">
                            <span class="text_fz12">Телефон</span>
                            <input type="tel" name="feedphone" placeholder="+7 (___) ___-__-__" required>
                        </label>
                        <label class="form-label">
                            <span class="text_fz12">Ваш вопрос</span>
                            <textarea name="feedcomm"></textarea>
                        </label>
                        <div class="form-label">
                            <?=outBtn('Отправить')?>
                        </div>
                        <div class="form-privacy text_fz12">
                            <img src="<?=THEME_IMAGES?>icons/privacy.svg" alt="privacy">
                            <span>Подтверждаю свои данные и даю своё <a href="<?=get_privacy_policy_url()?>" class="text_underline" target="_blank">согласие на их обработку.</a></span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>