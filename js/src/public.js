window.addEventListener('load',() => {
    let transportOptions = document.querySelectorAll('[name="contentData[type_ct]"]');
    let deliveryOption = document.getElementById('type_ct_delivery');
    let deliveryData = document.getElementById('delivery-data');
    let deliveryDataRequired = document.querySelectorAll('.to-required');

    deliveryData.collapse = new Collapse(deliveryOption);
    deliveryOption.removeEventListener('click',deliveryData.collapse.toggle);

    transportOptions.forEach((opt) => {
        opt.addEventListener('change',() => {
            if(deliveryOption.checked && !deliveryData.classList.contains('in')) {
                deliveryData.collapse.show();
                deliveryDataRequired.forEach((r) => {
                    let id = r.getAttribute('id');
                    r.required = true;
                    r.setAttribute('required','required');
                    if($ !== undefined) $('#'+id).rules('add',{required: true});
                });
            }
            else if(!deliveryOption.checked && deliveryData.classList.contains('in')) {
                deliveryDataRequired.forEach((r) => {
                    let id = r.getAttribute('id');
                    r.required = false;
                    r.removeAttribute('required');
                    if($ !== undefined) $('#'+id).rules('remove');
                });
                deliveryData.collapse.hide();
            }
        });
    });
});