{if $cart.type_ct eq 'delivery'}
<fieldset>
    <h3>{#delivery#}</h3>
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <div class="form-group">
                <label for="transport[lastname]">{#lastname#|ucfirst} :</label>
                <input id="transport[lastname]" type="text" name="transport[lastname]" value="{$cart.lastname_ct}" placeholder="{#ph_lastname#}" disabled class="form-control" />
            </div>
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="form-group">
                <label for="transport[firstname]">{#firstname#|ucfirst} :</label>
                <input id="transport[firstname]" type="text" name="transport[firstname]" value="{$cart.firstname_ct}" placeholder="{#ph_firstname#}" disabled class="form-control" />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <div class="form-group">
                <label for="transport[street]">{#street_ac#|ucfirst} :</label>
                <input id="transport[street]" type="text" name="transport[street]" value="{$cart.street_ct}" placeholder="{#ph_street#}" disabled class="form-control" />
            </div>
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="form-group">
                <label for="transport[city]">{#city_ac#|ucfirst} :</label>
                <input id="transport[city]" type="text" name="transport[city]" value="{$cart.city_ct}" placeholder="{#ph_city#}" disabled class="form-control" />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <div class="form-group">
                <label for="transport[postcode]">{#postcode_ac#|ucfirst} :</label>
                <input id="transport[postcode]" type="text" name="transport[postcode]" value="{$cart.postcode_ct}" placeholder="{#ph_postcode#}" disabled class="form-control" />
            </div>
        </div>
        <div class="col-xs-12 col-md-6">
            <div class="form-group">
                <label for="transport[country]">{#country_ac#|ucfirst} :</label>
                <input id="transport[country]" type="text" name="transport[country]" value="{#$cart.country_tr#}" placeholder="{#ph_country#}" disabled class="form-control" />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <div class="form-group">
                <label for="transport[price]">{#price#|ucfirst} :</label>
                <input id="transport[price]" type="text" name="transport[price]" value="{if $cart.price_tr != NULL}{$cart.price_tr}{else}0{/if}" placeholder="{#ph_price#}" disabled class="form-control" />
            </div>
        </div>
    </div>
</fieldset>
    {else}
<fieldset>
<h3>{#delivery#}</h3>
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <div class="form-group">
                <input id="transport[price]" type="text" name="transport[type]" value="{#$cart.type_ct#}" disabled class="form-control" />
            </div>
        </div>
    </div>
</fieldset>
{/if}