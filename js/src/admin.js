var transport = (function ($, undefined) {

    function editPrice(value, vat) {
        let price = document.getElementById('price_tr');
        price.value = Math.round(Math.round((value / vat) * 1000) / 10) / 100;
    }
    function editPriceTaxInc(value, vat) {
        let priceTaxInc = document.getElementById('price_ttc');
        priceTaxInc.value = Math.round(Math.round((value * vat) * 1000) / 10) / 100;
    }

    function priceHandler() {
        let price = document.getElementById('price_tr');
        let priceTaxInc = document.getElementById('price_ttc');
        let vat_rate = parseFloat(priceTaxInc.dataset.vat)/100 + 1;

        if(vat_rate !== null && vat_rate !== '') {
            price.addEventListener('change',(e) => { editPriceTaxInc(parseFloat(price.value),vat_rate); });
            price.addEventListener('input',(e) => { editPriceTaxInc(parseFloat(price.value),vat_rate); });
            priceTaxInc.addEventListener('change',(e) => { editPrice(parseFloat(priceTaxInc.value),vat_rate); });
            priceTaxInc.addEventListener('input',(e) => { editPrice(parseFloat(priceTaxInc.value),vat_rate); });
        }
    }
    return {
        run: function(){
            priceHandler();
        }
    }
})(jQuery);