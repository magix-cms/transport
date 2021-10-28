<div class="row">
    <form id="add_transport" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=add" method="post" class="validate_form add_form col-ph-12 col-lg-8 collapse in">
        <div class="row">
            <div class="col-ph-12 col-md-3">
                <div class="form-group">
                    <label for="price_tr">{#price#}</label>
                    <input type="text" class="form-control" id="price_tr" name="transData[price_tr]" value="" placeholder="{#ph_price_tr#|ucfirst}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-ph-12 col-md-6">
                <div class="form-group">
                    <label for="name_tr">{#name_tr#}</label>
                    <input type="text" class="form-control" id="name_tr" name="transData[name_tr]" value="" placeholder="{#ph_name_tr#|ucfirst}">
                </div>
            </div>
            <div class="col-ph-12 col-md-3">
                <div class="form-group">
                    <label for="postcode_tr">{#postcode_tr#}</label>
                    <input type="text" class="form-control" id="postcode_tr" name="transData[postcode_tr]" value="" placeholder="{#ph_postcode_tr#|ucfirst}">
                </div>
            </div>
        </div>
        <div id="submit" class="col-ph-12 col-md-6">
            <button class="btn btn-main-theme pull-right" type="submit" name="action" value="add">{#save#|ucfirst}</button>
        </div>
    </form>
</div>