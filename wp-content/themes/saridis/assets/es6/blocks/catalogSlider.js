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

                if (window.innerWidth <= 768)
                    sliderTrack.style.transform = `translateX(-${slideIndex * slideWidth}px)`;
                else 
                    sliderTrack.style.transform = `translateY(-${slideIndex * slideHeight}px)`;
            }

            const moveRight = () => {
                slideIndex + getVisCount() >= slidesCount ? slideIndex = 0 : slideIndex++;
                slide();
            }

            const moveLeft = () => {
                slideIndex <= 0 ? slideIndex = slidesCount - getVisCount() : slideIndex--;
                slide();
            }

            const setSlideSizes = () => {
                slideWidth = slides[0].offsetWidth + +window.getComputedStyle(slides[0]).marginRight.replace('px', '');
                slideHeight = slides[0].offsetHeight + +window.getComputedStyle(slides[0]).marginBottom.replace('px', '');
            }

            sliderTrack.style.transition = 'transform 0.5s ease 0s';

            setSlideSizes();

            let startPos = 0;
        
            sliderList.addEventListener('touchstart', (e) => {
                if (window.innerWidth <= 768) startPos = e.changedTouches[0].screenX;
            });
        
            sliderList.addEventListener('touchend', (e) => {
                if (window.innerWidth <= 768) {
                    if (startPos - e.changedTouches[0].screenX > 50)
                        moveRight();
                    else if (startPos - e.changedTouches[0].screenX < -50)
                        moveLeft();
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
}

export default catalogSlider;