const catalog = () => {
    try {
        const rangeInput = document.querySelectorAll('.filter-range-line input'),
              priceInput = document.querySelectorAll('.filter-price .from-text, .filter-price .to-text'),
              range = document.querySelector('.filter-range-line .line');
        
        let priceGap = 0;

        const changeValue = (e) => {
            let minVal = +rangeInput[0].value,
                maxVal = +rangeInput[1].value;

            if (maxVal - minVal < priceGap) {
                if (e.target.className === 'range-from')
                    rangeInput[0].value = maxVal - priceGap;
                else
                    rangeInput[1].value = minVal + priceGap;
            } else {
                priceInput[0].textContent = minVal;
                priceInput[1].textContent = maxVal;

                range.style.left = ((minVal / rangeInput[0].max) * 100) + '%';
                range.style.right = 100 - (maxVal / rangeInput[1].max) * 100 + '%';
            }
        }

        changeValue();

        rangeInput.forEach(input =>{
            input.addEventListener('input', changeValue);
        });
    } catch (e) {
        console.log(e.stack);
    }

    try {
        const catalogList = document.querySelector('.catalog__list'),
              catalogMore = document.querySelector('.catalog__more'),
              catalogRating = document.querySelector('.catalog__rating');

        if (catalogList && catalogMore) {
            let catalogItems = catalogList.querySelectorAll('.catalog__list-item'),
                catalogItemsDef = catalogList.innerHTML;

            let row = window.innerWidth <= 576 ? 8 : 9,
                iter = 0,
                count = catalogItems.length;

            const showMoreItems = () => {
                for (let i = iter; i < count; i++) {
                    if (i >= iter + row) break;

                    if (catalogItems[i]) catalogItems[i].classList.remove('hide');
                }

                iter += row;

                if (iter >= count) catalogMore.style.display = 'none';
            }

            showMoreItems();

            catalogMore.addEventListener('click', showMoreItems);

            if (catalogRating) {
                const ratingName = catalogRating.querySelector('.catalog__rating-name'),
                      ratingNameSpan = ratingName.querySelector('span'),
                      ratingList = catalogRating.querySelector('.catalog__rating-list'),
                      ratingListItems = ratingList.querySelectorAll('span');

                const setSort = (name = 'По умолчанию', sort = 'default') => {
                    ratingName.classList.remove('active');
                    ratingList.classList.remove('active');
                    ratingNameSpan.textContent = name;

                    let inCheck = [];

                    catalogList.innerHTML = '';

                    if (sort != 'default') {
                        let sortArr = Array.from(catalogItems);

                        if (sort == 'price-up') {
                            sortArr.sort((a, b) => {
                                return +a.getAttribute('data-price') - +b.getAttribute('data-price');
                            });
                        }
    
                        if (sort == 'price-down') {
                            sortArr.sort((a, b) => {
                                return +b.getAttribute('data-price') - +a.getAttribute('data-price');
                            });
                        }
    
                        if (sort == 'rating') {
                            sortArr.sort((a, b) => {
                                return +b.getAttribute('data-rating') - +a.getAttribute('data-rating');
                            });
                        }

                        sortArr.forEach(sortItem => {
                            sortItem.classList.add('hide');
                            catalogList.innerHTML += sortItem.outerHTML
                        });
                    }

                    if (sort == 'default') {
                        catalogList.innerHTML = catalogItemsDef;
                    }

                    iter = 0;
                    catalogItems = catalogList.querySelectorAll('.catalog__list-item');
                    showMoreItems();
                }

                ratingListItems.forEach(listItem => {
                    listItem.addEventListener('click', () => {
                        setSort(listItem.textContent.trim(), listItem.getAttribute('data-sort').trim());
                    });
                });
            }
        }
    } catch (e) {
        console.log(e.stack);
    }
}

export default catalog;