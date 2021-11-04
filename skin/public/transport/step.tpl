{extends file="cartpay/step.tpl"}

{block name="step:name"}{#transport_step#}{/block}
{block name="step:content"}
    {*<pre>{$transport|print_r}</pre>*}
    <div class="row row-center">
        <div class="col-12 col-sm-4 col-md-5 col-lg-4">
            <div class="form-group">
                <label for="firstname_ct" class="is_empty">{#pn_tr_firstname#|ucfirst}&nbsp;:</label>
                <input id="firstname_ct" type="text" name="contentData[firstname_ct]" placeholder="{#ph_tr_firstname#|ucfirst}" class="form-control"/>
            </div>
        </div>
        <div class="col-sm-4 col-md-5 col-lg-4">
            <div class="form-group">
                <label for="lastname_ct" class="is_empty">{#pn_tr_lastname#|ucfirst}&nbsp;:</label>
                <input id="lastname_ct" type="text" name="contentData[lastname_ct]" placeholder="{#ph_tr_lastname#|ucfirst}" class="form-control"/>
            </div>
        </div>
    </div>
    <div class="row row-center">
        <div class="col-12 col-md-6">
            <div class="form-group">
                <label for="street_ct" class="is_empty">{#pn_tr_street#|ucfirst}*&nbsp;:</label>
                <input id="street_ct" type="text" name="contentData[street_ct]" placeholder="{#ph_tr_street#|ucfirst}" value="" class="form-control required" required/>
            </div>
        </div>
    </div>
    <div class="row row-center">
        <div class="col-2 col-xs-3 col-sm-4 col-md-5 col-lg-6">
            <div class="form-group">
                <label for="contentData[id_tr]">{#ph_transport_city#|ucfirst}</label>
                <select name="contentData[id_tr]" id="contentData[id_tr]" class="form-control required" required>
                    <option disabled selected>-- {#pn_transport_city#|ucfirst} --</option>
                    {foreach $transport as $key}
                        <option value="{$key.id}">{$key.name} - {$key.postcode}</option>
                    {/foreach}
                </select>
            </div>
        </div>
    </div>
{/block}