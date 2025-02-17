<?php
    $menuFooter2 = wp_get_nav_menu_items('footer2');
?>
        <footer class="footer">
            <div class="container">
                <div class="footer__top">
                    <div class="footer__left text_fz14">
                        <div class="header__logo">
                            <a href="<?=get_home_url()?>" class="no-hover">
                                <img src="<?=(get_field('logo', 'option') ?: THEME_IMAGES.'logo.png')?>" alt="<?=getClearText(get_bloginfo('name'))?>">
                            </a>
                            <span>Оптовая поставка <br>греческих продуктов</span>
                        </div>
                        <?php if (get_field('footer-text', 'option')) : ?>
                            <div class="footer__descr text_fw300">
                                <?=get_field('footer-text', 'option')?>
                            </div>
                        <?php endif; ?>
                        <?php if (
                                get_field('wa', 'option') ||
                                get_field('tg', 'option') ||
                                get_field('vk', 'option')
                            ) : ?>
                            <div class="footer__social">
                                <?php if (get_field('wa', 'option')) : ?>
                                    <a href="https://wa.me/<?=str_replace('+', '', getThinPhone(get_field('wa', 'option')))?>" target="_blank">
                                        <img src="<?=THEME_IMAGES?>icons/whatsapp.svg" alt="WhatsApp">
                                    </a>
                                <?php endif; ?>
                                <?php if (get_field('tg', 'option')) : ?>
                                    <a href="<?=get_field('tg', 'option')?>" target="_blank">
                                        <img src="<?=THEME_IMAGES?>icons/telegram.svg" alt="Telegram">
                                    </a>
                                <?php endif; ?>
                                <?php if (get_field('vk', 'option')) : ?>
                                    <a href="<?=get_field('vk', 'option')?>" target="_blank">
                                        <img src="<?=THEME_IMAGES?>icons/vk.svg" alt="VK">
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="footer__right text_fz18 text_fw300">
                        <div class="footer__menu">
                            <strong class="text_fz20">Категории товаров</strong>
                            <ul>
                                <li><a href="/catalog/">Консервация</a></li>
                                <li><a href="/catalog/">Кофе и чай</a></li>
                                <li><a href="/catalog/">Макароны</a></li>
                                <li><a href="/catalog/">Оливки</a></li>
                                <li><a href="/catalog/">Оливковое масло</a></li>
                                <li><a href="/catalog/">Прочие масла, соусы и уксусы</a></li>
                                <li><a href="/catalog/">Сладости</a></li>
                                <li><a href="/catalog/">Оливковое масло</a></li>
                                <li><a href="/catalog/">Хлопья и гранолы</a></li>
                            </ul>
                        </div>
                        <?php if (isset($menuFooter2[0])) : ?>
                            <div class="footer__menu">
                                <strong class="text_fz20">Меню</strong>
                                <ul>
                                    <?php
                                        foreach($menuFooter2 as $menuItem) {
                                            ?>
                                            <li>
                                                <a href="<?=$menuItem->url?>">
                                                    <?=$menuItem->title?>
                                                </a>
                                            </li>
                                            <?php
                                        }
                                    ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <div class="footer__menu">
                            <strong class="text_fz20">Контакты</strong>
                            <ul>
                                <li>
                                    <a href="<?=get_permalink(49)?>">
                                        <img src="<?=THEME_IMAGES?>icons/point.svg" alt="Контакты">
                                        <span>Наши магазины</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="tel:<?=getThinPhone(get_field('phone', 'option'))?>" class="text_nowrap">
                                        <img src="<?=THEME_IMAGES?>icons/phone-color.svg" alt="Телефон">
                                        <span><?=get_field('phone', 'option')?></span>
                                    </a>
                                </li>
                                <li>
                                    <a href="mailto:<?=get_field('email', 'option')?>">
                                        <img src="<?=THEME_IMAGES?>icons/email.svg" alt="E-mail">
                                        <span><?=get_field('email', 'option')?></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="footer__bott text_fz18 text_fw300">
                    <span><?=get_home_url()?> Copyright <?=date('Y')?> | Продукты из Греции "САРИДИС"</span>
                    <a href="<?=get_privacy_policy_url()?>">
                        Политика конфиденциальности
                    </a>
                </div>
            </div>
        </footer>
    </div>
    
    <div class="modal">
        <div class="modal__item" data-modal="auth">
            <div class="modal__close"></div>

            <div class="modal__wrap">
                <form action="<?=admin_url('admin-ajax.php')?>?action=userauth" class="form">
                    <input type="text" name="recaptcha" hidden>

                    <strong class="form-head text_fz24 text_upper text_center">
                        Вход
                    </strong>

                    <div class="form-wrap">
                        <label class="form-label">
                            <span class="text_fz14">Логин</span>
                            <input type="text" name="authform-login" required>
                        </label>
                        <label class="form-label">
                            <span class="text_fz14">Пароль</span>
                            <input type="password" name="authform-pass" required>
                        </label>
                        <div class="form-label">
                            <?=outBtn('Отправить')?>
                        </div>
                        <div class="form-label">
                            <div class="checkbox-field text_fz14 text_fw400">
                                <label class="checkbox-field-item">
                                    <input type="checkbox" name="authform-remember" hidden>
                                    <div class="dot"></div>
                                    <span>
                                        Запомнить
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="form-label text_center text_fz18" data-call-modal="register">
                            Регистрация
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal__item" data-modal="register">
            <div class="modal__close"></div>

            <div class="modal__wrap">
                <form action="<?=admin_url('admin-ajax.php')?>?action=userreg" class="form" data-success="reg">
                    <input type="text" name="recaptcha" hidden>

                    <strong class="form-head text_fz24 text_upper text_center">
                       Регистрация
                       <span class="text_fz14 text_fw400">После отправки ваших данных, модератор отправит вам на почту логин и пароль от личного кабинета</span>
                    </strong>

                    <div class="form-wrap">
                        <label class="form-label">
                            <span class="text_fz14">Название компании</span>
                            <input type="text" name="regform-name" required>
                        </label>
                        <label class="form-label">
                            <span class="text_fz14">ИНН</span>
                            <input type="text" name="regform-inn" required>
                        </label>
                        <label class="form-label">
                            <span class="text_fz14">Телефон</span>
                            <input type="tel" name="regform-phone" required>
                        </label>
                        <label class="form-label">
                            <span class="text_fz14">Email</span>
                            <input type="email" name="regform-email" required>
                        </label>
                        <div class="form-label">
                            <?=outBtn('Отправить')?>
                        </div>
                        <div class="form-label form-privacy text_fz12">
                            <img src="<?=THEME_IMAGES?>icons/privacy.svg" alt="privacy">
                            <span>Подтверждаю свои данные и даю своё <a href="<?=get_privacy_policy_url()?>" class="text_underline" target="_blank">согласие на их обработку.</a></span>
                        </div>
                        <div class="form-label text_center text_fz18" data-call-modal="auth">
                            Войти
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php if (IS_AUTH) : ?>
            <div class="modal__item" data-modal="logout">
                <div class="modal__close"></div>

                <div class="modal__wrap">
                    <div class="form">
                        <strong class="form-head text_fz24 text_upper text_center">
                        Вы действительно хотите выйти из аккаунта?
                        </strong>
                        <?=outBtn('Выйти', '', 'middle', wp_logout_url(get_home_url()))?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="modal__item" data-modal="cart">
            <div class="modal__close"></div>

            <div class="modal__wrap">
                <div class="form">
                    <strong class="form-head text_fz24 text_upper text_center">
                       Товар добавлен в корзину
                       <img src="<?=THEME_IMAGES?>icons/success.svg" alt="Товар добавлен в корзину">
                    </strong>
                </div>
            </div>
        </div>
        <div class="modal__item modal__auth-error">
            <div class="modal__close"></div>

            <div class="modal__wrap">
                <div class="form">
                    <strong class="form-head text_fz24 text_upper text_center">
                       Ошибка!
                       <span class="text_fz14 text_fw400">Неправильно введён логин или пароль пользователя. Попробуйте ещё раз.</span>
                    </strong>
                </div>
            </div>
        </div>
        <div class="modal__item modal__success">
            <div class="modal__close"></div>

            <div class="modal__wrap">
                <div class="form">
                    <strong class="form-head text_fz24 text_upper text_center">
                       Ваша заявка успешно отправлена
                       <span class="text_fz14 text_fw400">Мы свяжемся с вами в ближайшее время</span>
                       <img src="<?=THEME_IMAGES?>icons/success.svg" alt="Ваша заявка успешно отправлена">
                    </strong>
                </div>
            </div>
        </div>
        <div class="modal__item modal__success-reg">
            <div class="modal__close"></div>

            <div class="modal__wrap">
                <div class="form">
                    <strong class="form-head text_fz24 text_upper text_center">
                       Ваша заявка успешно отправлена
                       <span class="text_fz14 text_fw400">Ожидайте письмо на почте от модератора</span>
                       <img src="<?=THEME_IMAGES?>icons/success.svg" alt="Ваша заявка успешно отправлена">
                    </strong>
                </div>
            </div>
        </div>
        <div class="modal__item modal__success-comment">
            <div class="modal__close"></div>

            <div class="modal__wrap">
                <div class="form">
                    <strong class="form-head text_fz24 text_upper text_center">
                       Спасибо за комментарий!
                       <span class="text_fz14 text_fw400">
                            Ваш комментарий появится на странице после одобрения модератора
                       </span>
                       <img src="<?=THEME_IMAGES?>icons/success.svg" alt="Ваша заявка успешно отправлена">
                    </strong>
                </div>
            </div>
        </div>
    </div>

    <?php
        wp_footer();
    ?>

    <?=get_field('code-body', 'option')?>
</body>
</html>