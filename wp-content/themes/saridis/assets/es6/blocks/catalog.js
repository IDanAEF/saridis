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
            }

            showMoreItems();

            catalogMore.addEventListener('click', showMoreItems);
        }
    } catch (e) {
        console.log(e.stack);
    }
}

export default catalog;