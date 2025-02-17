/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/es6/blocks/cart.js":
/*!***********************************!*\
  !*** ./assets/es6/blocks/cart.js ***!
  \***********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
const cart = () => {
  const getCookie = name => {
    let matches = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"));
    return matches ? decodeURIComponent(matches[1]) : undefined;
  };
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
  };
  const deleteCookie = name => {
    setCookie(name, "", {
      'max-age': -1
    });
  };
  const hideScroll = () => {
    document.querySelector('body').classList.add('fixed');
    document.querySelector('html').classList.add('fixed');
  };
  const showScroll = () => {
    document.querySelector('body').classList.remove('fixed');
    document.querySelector('html').classList.remove('fixed');
  };
  const toggleScroll = () => {
    document.querySelector('body').classList.toggle('fixed');
    document.querySelector('html').classList.toggle('fixed');
  };
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
    cartForm.addEventListener('submit', e => {
      e.preventDefault();
    });
    cartBtn.addEventListener('click', () => {
      if (cartList.classList.contains('hide')) {
        cartBtn.classList.add('disable');
        const formData = new FormData(cartForm);
        postData(cartForm.action, formData).then(res => {
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
      cartEnd = document.querySelector('.end-price');
    const rebuildPrice = () => {
      setTimeout(() => {
        let prePrice = 0,
          cutPrice = 0;
        cartRows.forEach(rowItem => {
          let counterRes = +rowItem.querySelector('.counter .counter-result').textContent.trim(),
            innerPre = +rowItem.getAttribute('data-price') * counterRes,
            innerCut = +rowItem.getAttribute('data-cut') * counterRes;
          rowItem.querySelector('.price-result-span').textContent = innerCut;
          prePrice += innerPre;
          cutPrice += innerCut;
        });
        cartPre.textContent = prePrice;
        cartEnd.textContent = cutPrice;
        if (cartCut) cartCut.textContent = prePrice - cutPrice;
      }, 200);
    };
    cartRows.forEach(row => {
      const counterMinus = row.querySelector('.counter .counter-minus'),
        counterPlus = row.querySelector('.counter .counter-plus');
      counterMinus.addEventListener('click', rebuildPrice);
      counterPlus.addEventListener('click', rebuildPrice);
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
          if (headCart.querySelector('span')) headCart.querySelector('span').textContent = fullCount;else {
            headCart.innerHTML += '<span>' + fullCount + '</span>';
          }
          if (fullCount == 0) headCart.querySelector('span').remove();
        });
        setCookie('cart', JSON.stringify(newItems), {
          'max-age': 3600 * 24 * 31
        });
      };
      if (counter) {
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
    const updateItems = id => {
      let newItems = [],
        alrIn = false;
      settedFav.forEach(favItem => {
        if (favItem != id) newItems.push(favItem);else alrIn = true;
      });
      if (!alrIn) newItems.push(id);
      settedFav = newItems;
      headerFav.forEach(headFav => {
        if (headFav.querySelector('span')) headFav.querySelector('span').textContent = settedFav.length;else {
          headFav.innerHTML += '<span>' + settedFav.length + '</span>';
        }
        if (settedFav.length == 0) headFav.querySelector('span').remove();
      });
      setCookie('favorite', JSON.stringify(newItems), {
        'max-age': 3600 * 24 * 31
      });
    };
    wishBtns.forEach(btn => {
      let itemId = +btn.getAttribute('data-id').trim();
      if (settedFav.indexOf(itemId) != -1 && !btn.classList.contains('wish-delete')) btn.classList.add('active');
      btn.addEventListener('click', () => {
        updateItems(itemId);
        if (btn.classList.contains('wish-delete')) btn.closest('.wish-parent').remove();else btn.classList.toggle('active');
      });
    });
  } catch (e) {
    console.log(e.stack);
  }
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (cart);

/***/ }),

/***/ "./assets/es6/blocks/catalog.js":
/*!**************************************!*\
  !*** ./assets/es6/blocks/catalog.js ***!
  \**************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
const catalog = () => {
  try {
    const rangeInput = document.querySelectorAll('.filter-range-line input'),
      priceInput = document.querySelectorAll('.filter-price .from-text, .filter-price .to-text'),
      range = document.querySelector('.filter-range-line .line');
    let priceGap = 0;
    const changeValue = e => {
      let minVal = +rangeInput[0].value,
        maxVal = +rangeInput[1].value;
      if (maxVal - minVal < priceGap) {
        if (e.target.className === 'range-from') rangeInput[0].value = maxVal - priceGap;else rangeInput[1].value = minVal + priceGap;
      } else {
        priceInput[0].textContent = minVal;
        priceInput[1].textContent = maxVal;
        range.style.left = minVal / rangeInput[0].max * 100 + '%';
        range.style.right = 100 - maxVal / rangeInput[1].max * 100 + '%';
      }
    };
    changeValue();
    rangeInput.forEach(input => {
      input.addEventListener('input', changeValue);
    });
  } catch (e) {
    console.log(e.stack);
  }
  try {
    const catalogList = document.querySelector('.catalog__list'),
      catalogMore = document.querySelector('.catalog__more');
    if (catalogList && catalogMore) {
      const catalogItems = catalogList.querySelectorAll('.catalog__list-item');
      let row = window.innerWidth <= 576 ? 8 : 9,
        iter = 0,
        count = catalogItems.length;
      const showMoreItems = () => {
        for (let i = iter; i < count; i++) {
          if (i >= iter + row) break;
          catalogItems[i].classList.remove('hide');
        }
        iter += row;
        if (iter >= count) catalogMore.style.display = 'none';
      };
      showMoreItems();
      catalogMore.addEventListener('click', showMoreItems);
    }
  } catch (e) {
    console.log(e.stack);
  }
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (catalog);

/***/ }),

/***/ "./assets/es6/blocks/catalogSlider.js":
/*!********************************************!*\
  !*** ./assets/es6/blocks/catalogSlider.js ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
const catalogSlider = () => {
  try {
    const sliderField = document.querySelectorAll('.single-catalog__left');
    sliderField.forEach(slider => {
      let sliderTrack = slider.querySelector('.single-catalog__slider-track'),
        sliderList = slider.querySelector('.single-catalog__slider-list'),
        slides = sliderTrack.querySelectorAll('img'),
        slidesBig = slider.querySelectorAll('.single-catalog__big img'),
        sliderRight = slider.querySelector('.arrow-next'),
        sliderLeft = slider.querySelector('.arrow-prev'),
        slideWidth = 0,
        slideHeight = 0,
        slideIndex = 0,
        slidesCount = slides.length;
      const getVisCount = () => {
        return 1;
      };
      const slide = () => {
        slides.forEach(item => item.classList.remove('active'));
        slides[slideIndex].classList.add('active');
        slidesBig.forEach(item => item.classList.remove('active'));
        slidesBig[slideIndex].classList.add('active');
        if (window.innerWidth <= 768) sliderTrack.style.transform = `translateX(-${slideIndex * slideWidth}px)`;else sliderTrack.style.transform = `translateY(-${slideIndex * slideHeight}px)`;
      };
      const moveRight = () => {
        slideIndex + getVisCount() >= slidesCount ? slideIndex = 0 : slideIndex++;
        slide();
      };
      const moveLeft = () => {
        slideIndex <= 0 ? slideIndex = slidesCount - getVisCount() : slideIndex--;
        slide();
      };
      const setSlideSizes = () => {
        slideWidth = slides[0].offsetWidth + +window.getComputedStyle(slides[0]).marginRight.replace('px', '');
        slideHeight = slides[0].offsetHeight + +window.getComputedStyle(slides[0]).marginBottom.replace('px', '');
      };
      sliderTrack.style.transition = 'transform 0.5s ease 0s';
      setSlideSizes();
      let startPos = 0;
      sliderList.addEventListener('touchstart', e => {
        if (window.innerWidth <= 768) startPos = e.changedTouches[0].screenX;
      });
      sliderList.addEventListener('touchend', e => {
        if (window.innerWidth <= 768) {
          if (startPos - e.changedTouches[0].screenX > 50) moveRight();else if (startPos - e.changedTouches[0].screenX < -50) moveLeft();
        }
      });
      sliderRight && sliderRight.addEventListener('click', moveRight);
      sliderLeft && sliderLeft.addEventListener('click', moveLeft);
      slides.forEach((slideItem, slideKey) => {
        slideItem.addEventListener('click', () => {
          slideIndex = slideKey;
          slide();
        });
      });
      window.addEventListener("resize", () => {
        setSlideSizes();
        slide();
      });
    });
  } catch (e) {
    console.log(e.stack);
  }
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (catalogSlider);

/***/ }),

/***/ "./assets/es6/blocks/forms.js":
/*!************************************!*\
  !*** ./assets/es6/blocks/forms.js ***!
  \************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
const forms = () => {
  const hideScroll = () => {
    document.querySelector('body').classList.add('fixed');
    document.querySelector('html').classList.add('fixed');
  };
  const showScroll = () => {
    document.querySelector('body').classList.remove('fixed');
    document.querySelector('html').classList.remove('fixed');
  };
  const toggleScroll = () => {
    document.querySelector('body').classList.toggle('fixed');
    document.querySelector('html').classList.toggle('fixed');
  };
  async function postData(url, data) {
    let res = await fetch(url, {
      method: "POST",
      body: data
    });
    return await res.text();
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
    const formAjax = document.querySelectorAll('form.form'),
      modal = document.querySelector('.modal'),
      modalSuccess = document.querySelector('.modal__success'),
      modalItems = document.querySelectorAll('.modal__item'),
      modalAuthError = document.querySelector('.modal__auth-error');
    formAjax.forEach(form => {
      const formBtn = form.querySelector('.btn');
      let personalSuccess = form.getAttribute('data-success') ? document.querySelector('.modal__success-' + form.getAttribute('data-success').trim()) : '';
      form.addEventListener('submit', e => {
        e.preventDefault();
        formBtn.classList.add('disable');
        const formData = new FormData(form);
        postData(form.action, formData).then(res => {
          formBtn.classList.remove('disable');
          modal.classList.add('active');
          modalItems.forEach(item => item.classList.remove('active'));
          if (res == 'auth-error') {
            modalAuthError.classList.add('active');
          } else if (res == 'restart') {
            window.location.reload();
          } else {
            if (personalSuccess) personalSuccess.classList.add('active');else modalSuccess.classList.add('active');
          }
          hideScroll();
        });
      });
    });
  } catch (e) {
    console.log(e.stack);
  }
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (forms);

/***/ }),

/***/ "./assets/es6/blocks/mask.js":
/*!***********************************!*\
  !*** ./assets/es6/blocks/mask.js ***!
  \***********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
const mask = selector => {
  let setCursorPosition = (pos, elem) => {
    elem.focus();
    if (elem.setSelectionRange) {
      elem.setSelectionRange(pos, pos);
    } else if (elem.createTextRange) {
      let range = elem.createTextRange();
      range.collapse(true);
      range.moveEnd('character', pos);
      range.moveStart('character', pos);
      range.select();
    }
  };
  function createMask(event) {
    let matrix = '+7 (___) ___-__-__',
      i = 0,
      def = matrix.replace(/\D/g, ''),
      val = this.value.replace(/\D/g, '');
    if (def.length >= val.length) {
      val = def;
    }
    this.value = matrix.replace(/./g, function (a) {
      return /[_\d]/.test(a) && i < val.length ? val.charAt(i++) : i >= val.length ? '' : a;
    });
    if (event.type === 'blur') {
      if (this.value.length == 2) {
        this.value = '';
      }
    } else {
      setCursorPosition(this.value.length, this);
    }
  }
  let inputs = document.querySelectorAll(selector);
  inputs.forEach(input => {
    input.addEventListener('input', createMask);
    input.addEventListener('focus', createMask);
    input.addEventListener('blur', createMask);
  });
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (mask);

/***/ }),

/***/ "./assets/es6/blocks/other.js":
/*!************************************!*\
  !*** ./assets/es6/blocks/other.js ***!
  \************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
const other = () => {
  const hideScroll = () => {
    document.querySelector('body').classList.add('fixed');
    document.querySelector('html').classList.add('fixed');
  };
  const showScroll = () => {
    document.querySelector('body').classList.remove('fixed');
    document.querySelector('html').classList.remove('fixed');
  };
  const toggleScroll = () => {
    document.querySelector('body').classList.toggle('fixed');
    document.querySelector('html').classList.toggle('fixed');
  };
  try {
    const bodyClickContent = document.querySelectorAll('.body-click-content'),
      bodyClickTarget = document.querySelectorAll('.body-click-target');
    document.body.addEventListener('click', e => {
      if (e.target.classList.contains('body-click-target') || e.target.classList.contains('body-click-close')) {
        e.preventDefault();
        let contentElem = e.target.getAttribute('data-content') ? document.querySelector('.body-click-content[data-content="' + e.target.getAttribute('data-content') + '"]') : e.target.nextElementSibling ? e.target.nextElementSibling : '';
        bodyClickContent.forEach(item => contentElem != item && item.classList.contains('global-hide') ? item.classList.remove('active') : '');
        bodyClickTarget.forEach(item => item.classList.contains('global-hide') && item != e.target ? item.classList.remove('active') : '');
        if (contentElem.classList.contains('body-click-content')) contentElem.classList.toggle('active');else e.target.parentElement.classList.remove('active');
        !e.target.classList.contains('not-active') ? e.target.classList.toggle('active') : '';
      } else if (!e.target.closest('.body-click-content')) {
        bodyClickContent.forEach(item => !item.classList.contains('not-global') ? item.classList.remove('active') : '');
        bodyClickTarget.forEach(item => !item.classList.contains('not-active') && !item.classList.contains('not-global') ? item.classList.remove('active') : '');
      }
    });
  } catch (e) {
    console.log(e.stack);
  }
  try {
    const targetElem = document.querySelectorAll('.elem_animate'),
      targetText = document.querySelectorAll('.text_animate');
    targetText.forEach(item => {
      let textCont = item.textContent.trim(),
        newInner = '',
        transit = 0;
      for (let i = 0; i < textCont.length; i++) {
        newInner += `<i class="or" style="transition: 0.4s all ${transit.toFixed(2)}s">${textCont[i]}</i>`;
        transit += 0.03;
      }
      item.innerHTML = newInner;
    });
    function returnHeight() {
      return window.innerWidth <= 600 ? window.innerHeight / 1.05 : window.innerHeight / 1.2;
    }
    function setAnim(mass) {
      mass.forEach(item => {
        if (returnHeight() + window.scrollY >= item.getBoundingClientRect().y + window.scrollY) {
          item.classList.add('anim');
        }
      });
    }
    setAnim(targetElem);
    setAnim(targetText);
    window.addEventListener('scroll', () => {
      setAnim(targetElem);
      setAnim(targetText);
    });
  } catch (e) {
    console.log(e.stack);
  }
  try {
    const modal = document.querySelector('.modal'),
      modalBtns = document.querySelectorAll('[data-call-modal]'),
      modalItems = document.querySelectorAll('.modal__item');
    modalBtns.forEach(btn => {
      if (btn.getAttribute('data-call-modal')) {
        btn.addEventListener('click', e => {
          e.preventDefault();
          modalItems.forEach(item => item.classList.remove('active'));
          modal.classList.add('active');
          modal.querySelector('.modal__item[data-modal="' + btn.getAttribute('data-call-modal') + '"]').classList.add('active');
          hideScroll();
        });
      }
    });
    modal.addEventListener('click', e => {
      if (e.target == modal || e.target.classList.contains('modal__close') || e.target.classList.contains('modal__hide')) {
        modalItems.forEach(item => item.classList.remove('active'));
        modal.classList.remove('active');
        showScroll();
      }
    });
  } catch (e) {
    console.log(e.stack);
  }
  try {
    const headerBurger = document.querySelector('.header__burger'),
      headerMobile = document.querySelector('.header__mobile');
    headerBurger.addEventListener('click', () => {
      headerBurger.classList.toggle('active');
      headerMobile.classList.toggle('active');
      toggleScroll();
    });
  } catch (e) {
    console.log(e.stack);
  }
  try {
    const counters = document.querySelectorAll('.counter');
    counters.forEach(counterItem => {
      const counterMinus = counterItem.querySelector('.counter-minus'),
        counterPlus = counterItem.querySelector('.counter-plus'),
        counterResult = counterItem.querySelector('.counter-result');
      let count = +counterResult.textContent;
      const setNum = (dir = 0) => {
        count += dir;
        if (count < 1) count = 1;
        counterResult.textContent = count;
      };
      counterMinus.addEventListener('click', () => setNum(-1));
      counterPlus.addEventListener('click', () => setNum(1));
    });
  } catch (e) {
    console.log(e.stack);
  }
  try {
    const showhideFields = document.querySelectorAll('.showhide-field');
    showhideFields.forEach(field => {
      const showhideList = field.querySelector('.showhide-list'),
        showhideMore = field.querySelector('.showhide-more');
      if (showhideList && showhideMore) {
        const showhideItems = showhideList.querySelectorAll('.showhide-item');
        let row = field.getAttribute('data-vis') ? +field.getAttribute('data-vis').trim() : 5,
          iter = 0,
          count = showhideItems.length;
        const showMoreItems = () => {
          for (let i = iter; i < count; i++) {
            if (i >= iter + row) break;
            showhideItems[i].classList.remove('hide');
          }
          iter += row;
          if (iter >= count) showhideMore.style.display = 'none';
        };
        showMoreItems();
        showhideMore.addEventListener('click', showMoreItems);
      }
    });
  } catch (e) {
    console.log(e.stack);
  }
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (other);

/***/ }),

/***/ "./assets/es6/blocks/scrolling.js":
/*!****************************************!*\
  !*** ./assets/es6/blocks/scrolling.js ***!
  \****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
const scrolling = () => {
  try {
    const links = document.querySelectorAll('[href^="#"]'),
      speed = 0.15;
    const goingTo = hash => {
      let widthTop = document.documentElement.scrollTop,
        toBlock = document.querySelector(hash).getBoundingClientRect().top - 50,
        start = null;
      requestAnimationFrame(step);
      function step(time) {
        if (start === null) {
          start = time;
        }
        let progress = time - start,
          r = toBlock < 0 ? Math.max(widthTop - progress / speed, widthTop + toBlock) : Math.min(widthTop + progress / speed, widthTop + toBlock);
        document.documentElement.scrollTo(0, r);
        if (r != widthTop + toBlock) {
          requestAnimationFrame(step);
        } else {
          //location.hash = hash;
        }
      }
    };
    if (window.location.hash) goingTo(window.location.hash);
    links.forEach(link => {
      link.addEventListener('click', function (event) {
        event.preventDefault();
        goingTo(this.hash);
      });
    });
  } catch (e) {
    console.log(e.stack);
  }
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (scrolling);

/***/ }),

/***/ "./assets/es6/blocks/slider.js":
/*!*************************************!*\
  !*** ./assets/es6/blocks/slider.js ***!
  \*************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
const slider = () => {
  try {
    const sliderField = document.querySelectorAll('.slider');
    sliderField.forEach(slider => {
      let sliderTrack = slider.querySelector('.slider-track'),
        sliderList = slider.querySelector('.slider-list'),
        sliderDots = slider.querySelector('.slider-dots'),
        sliderToggle = slider.querySelectorAll('.slider-toggle'),
        sliderDotsItems,
        slides = slider.querySelectorAll('.slide'),
        slideWidth = 0,
        slideRight = slider.querySelector('.right'),
        slideRightCount = slideRight ? slideRight.querySelector('.counter') : '',
        slideLeft = slider.querySelector('.left'),
        slideLeftCount = slideLeft ? slideLeft.querySelector('.counter') : '',
        slideIndex = 0,
        slidesCount = slides.length,
        transform = 0,
        changedTrans = 0,
        dragging = false,
        startDrag = 0,
        lastScale = 0;
      if (sliderDots) {
        sliderDots.innerHTML = '';
        for (let i = 0; i < slidesCount; i++) {
          sliderDots.innerHTML += '<span></span>';
        }
        sliderDotsItems = sliderDots.querySelectorAll('span');
      }
      const getVisCount = () => {
        if (window.innerWidth <= 576 && slider.getAttribute('data-mob-vis')) {
          return +slider.getAttribute('data-mob-vis');
        } else if (window.innerWidth <= 768 && slider.getAttribute('data-stablet-vis')) {
          return +slider.getAttribute('data-stablet-vis');
        } else if (window.innerWidth <= 992 && slider.getAttribute('data-tablet-vis')) {
          return +slider.getAttribute('data-tablet-vis');
        } else if (window.innerWidth <= 1400 && slider.getAttribute('data-lap-vis')) {
          return +slider.getAttribute('data-lap-vis');
        } else if (window.innerWidth < 2100 && slider.getAttribute('data-pc-vis')) {
          return +slider.getAttribute('data-pc-vis');
        } else if (slider.getAttribute('data-tv-vis')) {
          return +slider.getAttribute('data-tv-vis');
        }
        return 1;
      };
      const slide = () => {
        transform = -(slideIndex * slideWidth);
        sliderTrack.style.transform = `translateX(-${slideIndex * slideWidth}px)`;
        slides.forEach(item => item.classList.remove('active'));
        slides[slideIndex].classList.add('active');
        if (slideRightCount && slideLeftCount) {
          slideLeftCount.textContent = `${slideIndex === 0 ? slidesCount : slideIndex}/${slidesCount}`;
          slideRightCount.textContent = `${slideIndex + 2 > slidesCount ? 1 : slideIndex + 2}/${slidesCount}`;
        }
        if (sliderDots) {
          sliderDotsItems.forEach(dotItem => dotItem.classList.remove('active'));
          sliderDotsItems[slideIndex].classList.add('active');
        }
      };
      const moveRight = () => {
        slideIndex + getVisCount() >= slidesCount ? slideIndex = 0 : slideIndex++;
        slide();
      };
      const moveLeft = () => {
        slideIndex <= 0 ? slideIndex = slidesCount - getVisCount() : slideIndex--;
        slide();
      };
      const customMove = scale => {
        slideIndex += scale;
        if (slideIndex < 0) slideIndex = 0;
        if (slideIndex + getVisCount() >= slidesCount) slideIndex = slidesCount - getVisCount();
        slide();
      };
      const setSlideWidth = () => {
        slideWidth = slides[0].offsetWidth + +window.getComputedStyle(slides[0]).marginRight.replace('px', '');
      };
      const maxTransform = () => {
        return -(slideWidth * (slidesCount - getVisCount()));
      };
      const setTransform = scale => {
        changedTrans = transform + scale;
        if (changedTrans > 0) changedTrans = 0;
        if (changedTrans < maxTransform()) changedTrans = maxTransform();
        sliderTrack.style.transform = `translateX(${changedTrans}px)`;
      };
      const closeTransform = (gap = 150) => {
        if (dragging) {
          sliderTrack.classList.remove('fast');
          sliderList.classList.remove('grabbing');
          dragging = false;
          transform = changedTrans;
          let moved = -Math.ceil(lastScale / slideWidth);
          if (moved === 0) {
            if (lastScale < -gap) moved = 1;
            if (lastScale > gap) moved = -1;
          }
          customMove(moved);
          lastScale = 0;
          startDrag = 0;
        }
      };
      const openMove = pos => {
        dragging = true;
        startDrag = pos;
      };
      const moving = pos => {
        if (dragging) {
          sliderTrack.classList.add('fast');
          sliderList.classList.add('grabbing');
          lastScale = pos - startDrag;
          setTransform(pos - startDrag);
        }
      };
      slides.forEach((item, key) => {
        if (item.classList.contains('active')) slideIndex = key + getVisCount() >= slidesCount ? slidesCount - getVisCount() : key;
      });
      setSlideWidth();
      slide();
      sliderList.addEventListener('mousedown', e => openMove(e.clientX));
      sliderList.addEventListener('mousemove', e => moving(e.clientX));
      sliderList.addEventListener('mouseup', () => closeTransform(150));
      sliderList.addEventListener('mouseleave', () => closeTransform(150));
      sliderList.addEventListener('touchstart', e => openMove(e.touches[0].clientX));
      sliderList.addEventListener('touchmove', e => moving(e.touches[0].clientX));
      sliderList.addEventListener('touchend', () => closeTransform(50));
      slideRight && slideRight.addEventListener('click', moveRight);
      slideLeft && slideLeft.addEventListener('click', moveLeft);
      if (sliderDots) {
        sliderDotsItems.forEach((dotItem, dotKey) => {
          dotItem.addEventListener('click', () => {
            slideIndex = dotKey;
            slide();
          });
        });
      }
      if (sliderToggle) {
        sliderToggle.forEach(toggleField => {
          toggleField.querySelectorAll('span').forEach((toggleItem, toggleKey) => {
            toggleItem.addEventListener('click', () => {
              slideIndex = toggleKey;
              slide();
            });
          });
        });
      }
      window.addEventListener("resize", () => {
        setSlideWidth();
        slide();
      });
    });
  } catch (e) {
    console.log(e.stack);
  }
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (slider);

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry needs to be wrapped in an IIFE because it needs to be isolated against other modules in the chunk.
(() => {
/*!****************************!*\
  !*** ./assets/es6/main.js ***!
  \****************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _blocks_mask__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./blocks/mask */ "./assets/es6/blocks/mask.js");
/* harmony import */ var _blocks_scrolling__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./blocks/scrolling */ "./assets/es6/blocks/scrolling.js");
/* harmony import */ var _blocks_slider__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./blocks/slider */ "./assets/es6/blocks/slider.js");
/* harmony import */ var _blocks_catalogSlider__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./blocks/catalogSlider */ "./assets/es6/blocks/catalogSlider.js");
/* harmony import */ var _blocks_forms__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./blocks/forms */ "./assets/es6/blocks/forms.js");
/* harmony import */ var _blocks_catalog__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./blocks/catalog */ "./assets/es6/blocks/catalog.js");
/* harmony import */ var _blocks_cart__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./blocks/cart */ "./assets/es6/blocks/cart.js");
/* harmony import */ var _blocks_other__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./blocks/other */ "./assets/es6/blocks/other.js");








'use strict';
window.addEventListener('DOMContentLoaded', () => {
  (0,_blocks_mask__WEBPACK_IMPORTED_MODULE_0__["default"])('input[type="tel"]');
  (0,_blocks_scrolling__WEBPACK_IMPORTED_MODULE_1__["default"])();
  (0,_blocks_slider__WEBPACK_IMPORTED_MODULE_2__["default"])();
  (0,_blocks_catalogSlider__WEBPACK_IMPORTED_MODULE_3__["default"])();
  (0,_blocks_forms__WEBPACK_IMPORTED_MODULE_4__["default"])();
  (0,_blocks_catalog__WEBPACK_IMPORTED_MODULE_5__["default"])();
  (0,_blocks_cart__WEBPACK_IMPORTED_MODULE_6__["default"])();
  (0,_blocks_other__WEBPACK_IMPORTED_MODULE_7__["default"])();
});
})();

/******/ })()
;
//# sourceMappingURL=script.js.map