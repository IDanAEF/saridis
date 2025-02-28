const cart = () => {
    const getCookie = (name) =>  {
        let matches = document.cookie.match(new RegExp(
          "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
        ));

        return matches ? decodeURIComponent(matches[1]) : undefined;
    }

    const setCookie = (name, value, options = {}) => {
        options = {
          path: '/',
          // при необходимости добавьте другие значения по умолчанию
          ...options
        };
      
        if (options.expires instanceof Date) {
          options.expires = options.expires.toUTCString();
        }
      
        let updatedCookie = encodeURIComponent(name) + "=" + encodeURIComponent(value);
      
        for (let optionKey in options) {
          updatedCookie += "; " + optionKey;
          let optionValue = options[optionKey];
          if (optionValue !== true) {
            updatedCookie += "=" + optionValue;
          }
        }
      
        document.cookie = updatedCookie;
    }

    const deleteCookie = (name) => {
        setCookie(name, "", {
            'max-age': -1
        })
    }

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
        const cartBtn = document.querySelector('.cart__content-side .btn'),
              cartForm = document.querySelector('.cart__form'),
              cartList = document.querySelector('.cart__list'),
              modal = document.querySelector('.modal'),
              modalSuccess = document.querySelector('.modal__success'),
              modalItems = document.querySelectorAll('.modal__item');

        cartForm.addEventListener('submit', (e) => {
            e.preventDefault();
        });

        cartBtn.addEventListener('click', () => {
            if (cartList.classList.contains('hide')) {
                cartBtn.classList.add('disable');

                const formData = new FormData(cartForm);

                postData(cartForm.action, formData)
                .then((res) => {
                    cartBtn.classList.remove('disable');
                    modal.classList.add('active');
                    modalItems.forEach(item => item.classList.remove('active'));
                    modalSuccess.classList.add('active');
                    
                    hideScroll();

                    setTimeout(() => {
                        deleteCookie('cart');
                        window.location.reload();
                    }, 2000);
                });
            }

            cartForm.classList.remove('hide');
            cartList.classList.add('hide');
        });
    } catch (e) {
        console.log(e.stack);
    }

    try {
        const cartRows = document.querySelectorAll('.cart__list-row.cart-add-parent'),
              cartPre = document.querySelector('.pre-price'),
              cartCut = document.querySelector('.cut-price'),
              cartEnd = document.querySelector('.end-price'),
              cartInpOrderPrice = document.querySelector('input[name="order-price"]'),
              cartInpOrderPersonal = document.querySelector('input[name="order-personal"]'),
              overPriceItem = document.querySelector('.over-price-item'),
              overPriceSpan = overPriceItem.querySelector('.over-price'),
              overCutItems = document.querySelectorAll('.cart__top-cut-list > span');

        const rebuildPrice = () => {
            setTimeout(() => {
                let prePrice = 0,
                    cutPrice = 0,
                    overPrice = 0;

                cartRows.forEach(rowItem => {
                    let counterRes = +rowItem.querySelector('.counter .counter-result').textContent.trim(),
                        innerPre = +rowItem.getAttribute('data-price') * counterRes,
                        innerCut = +rowItem.getAttribute('data-cut') * counterRes;

                    rowItem.querySelector('.price-result-span').textContent = innerCut;
                    prePrice += innerPre;
                    cutPrice += innerCut;
                });

                overPrice = cutPrice;

                overCutItems.forEach(cutItem => {
                    let cutItemDir = cutItem.getAttribute('data-dir'),
                        cutItemPrice = +cutItem.getAttribute('data-price'),
                        cutItemPerc = +cutItem.getAttribute('data-cut');
                    
                    if (
                        (cutItemDir == 'to' && cutItemPrice > cutPrice) || 
                        (cutItemDir == 'from' && cutItemPrice <= cutPrice)
                    ) {
                        overCutItems.forEach(item => item.classList.remove('active'));
                        cutItem.classList.add('active');
                        
                        overPriceSpan.textContent = cutItemPerc;
                        cartInpOrderPersonal.value = cutItemPerc;
                        overPrice = Math.round(cutPrice - (cutItemPerc * (cutPrice / 100)));
                    }
                });

                cartPre.textContent = prePrice;
                cartEnd.textContent = overPrice;
                cartInpOrderPrice.value = overPrice;
                if (cartCut) cartCut.textContent = prePrice - cutPrice;
            }, 200);
        }

        rebuildPrice();

        cartRows.forEach(row => {
            const counterMinus = row.querySelector('.counter .counter-minus'),
                  counterPlus = row.querySelector('.counter .counter-plus'),
                  counterInput = row.querySelector('.counter .counter-input'),
                  counterListItems = row.querySelectorAll('.counter .counter-list span');
            
            counterMinus.addEventListener('click', rebuildPrice);
            counterPlus.addEventListener('click', rebuildPrice);
            counterListItems.forEach(listItem => {
                listItem.addEventListener('click', rebuildPrice);
            });

            let inpTimeout;

            counterInput.addEventListener('input', () => {
                clearTimeout(inpTimeout);
                inpTimeout = setTimeout(rebuildPrice, 200);
            });
        });
    } catch (e) {
        console.log(e.stack);
    }

    try {
        const cartAddParent = document.querySelectorAll('.cart-add-parent'),
              headerCart = document.querySelectorAll('.header__cart');

        let settedCart = getCookie('cart') ? JSON.parse(getCookie('cart')) : [];

        cartAddParent.forEach(parent => {
            const addBtn = parent.querySelector('.cart-add'),
                  deleteBtn = parent.querySelector('.cart-delete'),
                  counter = parent.querySelector('.counter');

            let addId = +parent.getAttribute('data-id');

            const updateCart = (action = '') => {
                let addCount = counter ? +counter.querySelector('.counter-result').textContent.trim() : 1,
                    newItems = [],
                    fullCount = 0,
                    alrIn = false;
                
                if (action == 'delete') {
                    settedCart.forEach(cartItem => {
                        if (cartItem[0] != addId) {
                            fullCount += cartItem[1];
                            newItems.push(cartItem);
                        }
                    });

                    parent.remove();
                } else if (action == 'responsive') {
                    settedCart.forEach(cartItem => {
                        if (cartItem[0] == addId) {
                            cartItem[1] = addCount;
                        }

                        fullCount += cartItem[1];
                        newItems.push(cartItem);
                    });
                } else {
                    settedCart.forEach(cartItem => {
                        if (cartItem[0] == addId) {
                            cartItem[1] += addCount;
                            alrIn = true;
                        }
    
                        fullCount += cartItem[1];
                        newItems.push(cartItem);
                    });
    
                    if (!alrIn) {
                        newItems.push([addId, addCount]);
                        fullCount += addCount;
                    }
                }

                headerCart.forEach(headCart => {
                    if (headCart.querySelector('span'))
                        headCart.querySelector('span').textContent = fullCount;
                    else {
                        headCart.innerHTML += '<span>'+fullCount+'</span>';
                    }
        
                    if (fullCount == 0) headCart.querySelector('span').remove();
                });

                setCookie('cart', JSON.stringify(newItems), {'max-age': 3600*24*31});
            }

            if (counter && counter.classList.contains('responsive')) {
                let inpTimeout;

                counter.querySelector('.counter-input').addEventListener('input', () => {
                    clearTimeout(inpTimeout);
                    inpTimeout = setTimeout(() => {
                        updateCart('responsive');
                    }, 200);
                });
                counter.querySelector('.counter-minus').addEventListener('click', () => {
                    setTimeout(() => {
                        updateCart('responsive');
                    }, 200);
                });
                counter.querySelector('.counter-plus').addEventListener('click', () => {
                    setTimeout(() => {
                        updateCart('responsive');
                    }, 200);
                });
                counter.querySelectorAll('.counter-list span').forEach(listItem => {
                    listItem.addEventListener('click', () => {
                        setTimeout(() => {
                            updateCart('responsive');
                        }, 200);
                    });
                });
            }
            deleteBtn && deleteBtn.addEventListener('click', () => updateCart('delete'));
            addBtn && addBtn.addEventListener('click', updateCart);
        });
    } catch (e) {
        console.log(e.stack);
    }

    try {
        const wishBtns = document.querySelectorAll('.wishlist-btn, .wish-delete'),
              headerFav = document.querySelectorAll('.header__favorite');

        let settedFav = getCookie('favorite') ? JSON.parse(getCookie('favorite')) : [];

        const updateItems = (id) => {
            let newItems = [],
                alrIn = false;

            settedFav.forEach(favItem => {
                if (favItem != id) newItems.push(favItem);
                else alrIn = true;
            });

            if (!alrIn) newItems.push(id);

            settedFav = newItems;

            headerFav.forEach(headFav => {
                if (headFav.querySelector('span'))
                    headFav.querySelector('span').textContent = settedFav.length;
                else {
                    headFav.innerHTML += '<span>'+settedFav.length+'</span>';
                }
    
                if (settedFav.length == 0) headFav.querySelector('span').remove();
            });

            setCookie('favorite', JSON.stringify(newItems), {'max-age': 3600*24*31});
        }

        wishBtns.forEach(btn => {
            let itemId = +btn.getAttribute('data-id').trim();

            if (settedFav.indexOf(itemId) != -1 && !btn.classList.contains('wish-delete'))
                btn.classList.add('active');

            btn.addEventListener('click', () => {
                updateItems(itemId)

                if (btn.classList.contains('wish-delete'))
                    btn.closest('.wish-parent').remove();
                else btn.classList.toggle('active');
            });
        });
    } catch (e) {
        console.log(e.stack);
    }
}

export default cart;