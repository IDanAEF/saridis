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
            }

            const moveRight = () => {
                slideIndex + getVisCount() >= slidesCount ? slideIndex = 0 : slideIndex++;
                slide();
            }

            const moveLeft = () => {
                slideIndex <= 0 ? slideIndex = slidesCount - getVisCount() : slideIndex--;
                slide();
            }

            const customMove = (scale) => {
                slideIndex += scale;
                if (slideIndex < 0) slideIndex = 0;
                if (slideIndex + getVisCount() >= slidesCount) slideIndex = slidesCount - getVisCount();
                slide();
            }

            const setSlideWidth = () => {
                slideWidth = slides[0].offsetWidth + +window.getComputedStyle(slides[0]).marginRight.replace('px', '');
            }

            const maxTransform = () => {
                return -(slideWidth * (slidesCount - getVisCount()));
            }

            const setTransform = (scale) => {
                changedTrans = transform + scale;

                if (changedTrans > 0) changedTrans = 0;
                if (changedTrans < maxTransform()) changedTrans = maxTransform();
                
                sliderTrack.style.transform = `translateX(${changedTrans}px)`;
            }

            const closeTransform = (gap = 150) => {
                if (dragging) {
                    sliderTrack.classList.remove('fast');
                    sliderList.classList.remove('grabbing');
                    dragging = false;
                    transform = changedTrans;

                    let moved = -(Math.ceil(lastScale / slideWidth));

                    if (moved === 0) {
                        if (lastScale < -gap) moved = 1;
                        if (lastScale > gap) moved = -1;
                    }

                    customMove(moved);
                    
                    lastScale = 0;
                    startDrag = 0;
                }
            }

            const openMove = (pos) => {
                dragging = true;
                startDrag = pos;
            }
            
            const moving = (pos) => {
                if (dragging) {
                    sliderTrack.classList.add('fast');
                    sliderList.classList.add('grabbing');
                    lastScale = pos - startDrag;
                    setTransform(pos - startDrag);
                }
            }

            slides.forEach((item, key) => {
                if (item.classList.contains('active'))
                    slideIndex = key + getVisCount() >= slidesCount ? slidesCount - getVisCount() : key;
            });

            setSlideWidth();
            slide();

            sliderList.addEventListener('mousedown', (e) => openMove(e.clientX));
            sliderList.addEventListener('mousemove', (e) => moving(e.clientX));
            sliderList.addEventListener('mouseup', () => closeTransform(150));
            sliderList.addEventListener('mouseleave', () => closeTransform(150));
        
            sliderList.addEventListener('touchstart', (e) => openMove(e.touches[0].clientX));
            sliderList.addEventListener('touchmove', (e) => moving(e.touches[0].clientX));
            sliderList.addEventListener('touchend', () => closeTransform(50));

            slideRight && slideRight.addEventListener('click', moveRight);
            slideLeft && slideLeft.addEventListener('click', moveLeft);

            if (sliderDots) {
                sliderDotsItems.forEach((dotItem, dotKey) => {
                    dotItem.addEventListener('click', () => {
                        slideIndex = dotKey;
                        slide();
                    })
                });
            }

            if (sliderToggle) {
                sliderToggle.forEach(toggleField => {
                    toggleField.querySelectorAll('span').forEach((toggleItem, toggleKey) => {
                        toggleItem.addEventListener('click', () => {
                            slideIndex = toggleKey;
                            slide();
                        })
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
}

export default slider;