const forms = () => {
    const hideScroll = () => {
        document.querySelector('body').classList.add('fixed');
        document.querySelector('html').classList.add('fixed');
    }

    const showScroll = () => {
        document.querySelector('body').classList.remove('fixed');
        document.querySelector('html').classList.remove('fixed');
    }

    const toggleScroll = () => {
        document.querySelector('body').classList.toggle('fixed');
        document.querySelector('html').classList.toggle('fixed');
    }

    async function postData(url, data) {
        let res = await fetch(url, {
            method: "POST",
            body: data
        });

        return await res.text();
    }

    try {
        const avatarLabel = document.querySelector('.profile__settings-avatar');

        if (avatarLabel) {
            const avatarInput = avatarLabel.querySelector('input'),
                  avatarImg = avatarLabel.querySelector('.avatar-wrap img');

            avatarInput.addEventListener("change", function () {
                if (this.files[0]) {
                    const fr = new FileReader();
    
                    fr.addEventListener("load", function () {
                        avatarImg.src = fr.result;
                    }, false);
    
                    fr.readAsDataURL(this.files[0]);
                }
            });
        }
    } catch (e) {
        console.log(e.stack);
    }
    
    try {
        const checkboxFields = document.querySelectorAll('.checkbox-field');

        checkboxFields.forEach(checkbox => {
            const checkboxItems = checkbox.querySelectorAll('.checkbox-field-item');

            checkboxItems.forEach(checkboxItem => {
                let itemInp = checkboxItem.querySelector('input');

                itemInp.addEventListener('change', () => {
                    if (checkbox.classList.contains('radio')) {
                        checkboxItems.forEach(item => {
                            if (checkboxItem != item) {
                                item.classList.remove('active');
                                item.querySelector('input').checked = false;
                            }
                        });
                    }
                    
                    checkboxItem.classList.toggle('active');
                });
            });
        });
    } catch (e) {
        console.log(e.stack);
    }

    try {
        const formAjax = document.querySelectorAll('form.form, form.form-ajax'),
              modal = document.querySelector('.modal'),
              modalSuccess = document.querySelector('.modal__success'),
              modalItems = document.querySelectorAll('.modal__item'),
              modalAuthError = document.querySelector('.modal__auth-error');

        formAjax.forEach(form => {
            const formBtn = form.querySelector('.btn');

            let personalSuccess = form.getAttribute('data-success') 
                ? document.querySelector('.modal__success-'+form.getAttribute('data-success').trim()) 
                : '';

            form.addEventListener('submit', (e) => {
                e.preventDefault();

                formBtn.classList.add('disable');

                const formData = new FormData(form);

                postData(form.action, formData)
                .then((res) => {
                    formBtn.classList.remove('disable');
                    modal.classList.add('active');
                    modalItems.forEach(item => item.classList.remove('active'));

                    if (res == 'auth-error') {
                        modalAuthError.classList.add('active');
                    } else if (res == 'restart') {
                        window.location.reload();
                    } else {
                        if (personalSuccess) personalSuccess.classList.add('active');
                        else modalSuccess.classList.add('active');
                    }
                    
                    hideScroll();
                });
            });
        });
    } catch (e) {
        console.log(e.stack);
    }
}

export default forms;