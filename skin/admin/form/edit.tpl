<div class="row">
    <form id="edit_transport" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$page.id_tr}" method="post" class="validate_form edit_form col-ph-12 col-lg-8">
        <div class="row">
            <div class="col-ph-12 col-md-4">
                <div class="form-group">
                    <label for="price_tr">{#price#}</label>
                    <input type="text" class="form-control" id="price_tr" name="transData[price_tr]" value="{$page.price_tr}" placeholder="{#ph_price_tr#|ucfirst}">
                </div>
            </div>
            <div class="col-ph-12 col-md-4">
                <div class="form-group">
                    <label for="price_ttc">{#ttc#}</label>
                    <input type="text" class="form-control" id="price_ttc" data-vat="{$setting.vat_rate}" value="{($page.price_tr * ($setting.vat_rate/100 + 1))|round:1|number_format:2:'.':''}" />
                </div>
            </div>
            <div class="col-ph-12 col-md-4">
                <div class="form-group">
                    <label for="name_tr">{#name_tr#}</label>
                    <input type="text" class="form-control" id="name_tr" name="transData[name_tr]" value="{$page.name_tr}" placeholder="{#ph_name_tr#|ucfirst}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-ph-12 col-md-4">
                <div class="form-group">
                    <label for="postcode_tr">{#postcode_tr#}</label>
                    <input type="text" class="form-control" id="postcode_tr" name="transData[postcode_tr]" value="{$page.postcode_tr}" placeholder="{#ph_postcode_tr#|ucfirst}">
                </div>
            </div>
            <div class="col-ph-12 col-md-4">
                <div class="form-group">
                    <label for="country_tr">{#country#|ucfirst}&nbsp;*</label>
                    <select name="transData[country_tr]" id="country_tr" class="form-control required" required>
                        <option value="">{#ph_country#|ucfirst}</option>
                        {foreach $countries as $key => $val}
                            <option value="{$val.iso}" {if $page.country_tr == $val.iso} selected{/if}>{$val.name|ucfirst}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
        </div>
        <input type="hidden" id="id_tr" name="id" value="{$page.id_tr}">
        <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
    </form>
</div>