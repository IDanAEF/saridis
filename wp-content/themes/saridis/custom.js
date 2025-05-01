"use strict";

window.addEventListener('DOMContentLoaded', () => {
    async function getData(url, data) {
        let res = await fetch(url, {
            method: "GET",
        });

        return await res.text();
    }

    try {
        const catalogDetailTabs = document.querySelectorAll('.single-catalog__tabs > .top span'),
              catalogDetailContents = document.querySelectorAll('.single-catalog__tabs .contents-item');

        catalogDetailTabs.forEach((tab, i) => {
            tab.addEventListener('click', () => {
                catalogDetailTabs.forEach(item => item.classList.remove('active'));
                catalogDetailContents.forEach(item => item.classList.remove('active'));

                tab.classList.add('active');
                catalogDetailContents[i].classList.add('active');
            });
        });
    } catch (e) {
        console.log(e.stack);
    }

    try {
        const cartRows = document.querySelectorAll('.cart__list-row');

        let setted = false;

        cartRows.forEach(rowItem => {
            if (rowItem.querySelector('.counter-multi')) {
                let multiItem = rowItem.querySelector('.counter-multi'),
                    counterList = rowItem.querySelector('.counter-list'),
                    priceRes = rowItem.querySelector('.col.multi-top .price-result');

                const setPos = () => {
                    if (!setted && window.innerWidth <= 576) {
                        setted = true;
                        setTimeout(() => {
                            priceRes.before(multiItem);
                        }, 200);
                    } else if (setted && window.innerWidth > 576) {
                        setted = false;
                        setTimeout(() => {
                            counterList.before(multiItem);
                        }, 200);
                    }
                }

                setPos();

                window.addEventListener('resize', setPos);
            }
        });
    } catch (e) {
        console.log(e.stack);
    }

    try {
        const preBlocks = document.querySelectorAll('.pre-check-block'),
              preBlocksItems = document.querySelectorAll('.pre-check-block .pre-check-item');

        const changeVisible = () => {
            const activeElems = document.querySelectorAll('.pre-check-block .pre-check-item.active');

            preBlocksItems.forEach(preItem => {
                preItem.classList.remove('hidden');
            });

            activeElems.forEach(activeElem => {
                let dataElems = activeElem.getAttribute('data-elems') 
                        ? activeElem.getAttribute('data-elems').trim().split(',') 
                        : [],
                    parentPreBlock = activeElem.closest('.pre-check-block');

                preBlocks.forEach(preBlock => {
                    if (parentPreBlock != preBlock) {
                        let preBlockElems = preBlock.querySelectorAll('.pre-check-item');

                        preBlockElems.forEach(preElem => {
                            let dataElems2 = preElem.getAttribute('data-elems') 
                                    ? preElem.getAttribute('data-elems').trim().split(',') 
                                    : [],
                                isThere = dataElems2.some(i => dataElems.indexOf(i) != -1);

                            if (!isThere) {
                                preElem.classList.remove('active');
                                preElem.classList.add('hidden');
                                preElem.querySelector('input').checked = false;
                            } else preElem.classList.remove('hidden');
                        });
                    }
                });
            });
        }

        changeVisible();

        preBlocksItems.forEach(preItem => {
            preItem.querySelector('input').addEventListener('change', changeVisible);
        });
    } catch (e) {
        console.log(e.stack);
    }
});