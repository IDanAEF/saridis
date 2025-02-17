import mask from "./blocks/mask";
import scrolling from "./blocks/scrolling";
import slider from "./blocks/slider";
import catalogSlider from "./blocks/catalogSlider";
import forms from "./blocks/forms";
import catalog from "./blocks/catalog";
import cart from "./blocks/cart";
import other from "./blocks/other";

'use strict';

window.addEventListener('DOMContentLoaded', () => {
    mask('input[type="tel"]');
    scrolling();
    slider();
    catalogSlider();
    forms();
    catalog();
    cart();
    other();
});