{extends file="cartpay/step.tpl"}

{block name="step:formclass"} actions{/block}
{block name="step:name"}{#transport_step#}{/block}
{block name="step:content"}
    {assign var="eventCollection" value=[
    "wedding",
    "communion",
    "birthday",
    "inauguration",
    "funeral",
    "others"
    ]}
    {*<pre>{$transport|print_r}</pre>*}
    <div class="row row-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6">
            <div class="form-group">
                <div class="action quotation">
                    <input type="radio" name="contentData[type_ct]" id="type_ct_pick_up_in_store" value="pick_up_in_store" class="not-nice"/>
                    <div class="icon">
                        <i class="material-icons ico ico-storefront"></i>
                    </div>
                    <div class="text">
                        <p class="h3">{#pick_up_in_store#}</p>
                        <p class="help-block">{#pick_up_in_store_desc#}</p>
                    </div>
                    <label for="type_ct_pick_up_in_store" title="{#pick_up_in_store#}">
                        <span class="sr-only">{#pick_up_in_store#}</span>
                    </label>
                </div>
                <div class="action quotation">
                    <input type="radio" name="contentData[type_ct]" data-target="#delivery-data" id="type_ct_delivery" value="delivery" class="not-nice" required/>
                    <div class="icon">
                        <i class="material-icons ico ico-local_shipping"></i>
                    </div>
                    <div class="text">
                        <p class="h3">{#delivery#}</p>
                        <p class="help-block">{#delivery_desc#}</p>
                    </div>
                    <label for="type_ct_delivery" title="{#delivery#}">
                        <span class="sr-only">{#delivery#}</span>
                    </label>
                </div>
            </div>
            {*<pre>{$account|print_r}</pre>*}
            {*<pre>{$transport|print_r}</pre>*}
            <div id="delivery-data" class="collapse">
                <div class="validate_form">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="firstname_ct" class="is_empty">{#pn_tr_firstname#|ucfirst}&nbsp;:</label>
                                <input id="firstname_ct" type="text" name="contentData[firstname_ct]" placeholder="{#ph_tr_firstname#|ucfirst}" value="{if $account}{$account.firstname}{/if}" class="form-control"/>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="lastname_ct" class="is_empty">{#pn_tr_lastname#|ucfirst}&nbsp;:</label>
                                <input id="lastname_ct" type="text" name="contentData[lastname_ct]" placeholder="{#ph_tr_lastname#|ucfirst}" value="{if $account}{$account.lastname}{/if}" class="form-control"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="street_ct" class="is_empty">{#pn_tr_street#|ucfirst}*&nbsp;:</label>
                                <input id="street_ct" type="text" name="contentData[street_ct]" placeholder="{#ph_tr_street#|ucfirst}" value="{if isset($account.address.delivery) && !empty($account.address.delivery)}{$account.address.delivery.street}{/if}" class="form-control to-required" {*required*}/>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="city_ct" class="is_empty">{#pn_city_ct#|ucfirst}&nbsp;*:</label>
                                <input id="city_ct" type="text" name="contentData[city_ct]" placeholder="{#ph_city_ct#|ucfirst}" value="{if isset($account.address.delivery) && !empty($account.address.delivery)}{$account.address.delivery.town}{/if}" class="form-control to-required" {*required*}/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="postcode_ct" class="is_empty">{#pn_postcode_ct#|ucfirst}&nbsp;*:</label>
                                <input id="postcode_ct" type="text" name="contentData[postcode_ct]" placeholder="{#ph_postcode_ct#|ucfirst}" value="{if isset($account.address.delivery) && !empty($account.address.delivery)}{$account.address.delivery.postcode}{/if}" class="form-control to-required" {*required*}/>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                {country_data}
                                <label for="contentData[id_tr]">{#pn_transport_country#|ucfirst}&nbsp;*:</label>
                                <select name="contentData[id_tr]" id="contentData[id_tr]" class="form-control to-required" {*required*}>
                                    <option disabled selected>-- {#pn_transport_country#|ucfirst} --</option>
                                    {foreach $transport as $key}
                                        <option value="{$key.id}"{*{if isset($account.address.delivery) && !empty($account.address.delivery) && {#$account.address.delivery.country#} === $key.country} selected{/if}*}>{$key.country}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                    </div>
                    {*<div class="row">
                        <div class="col-12 col-sm-4">
                            <div class="form-group">
                                <label for="contentData[event_ct]">{#ph_event#|ucfirst} *</label>
                                <select name="contentData[event_ct]" id="contentData[event_ct]" class="form-control to-required">
                                    <option disabled selected>-- {#pn_event#|ucfirst} --</option>
                                    {foreach $eventCollection as $key}
                                        <option value="{$key}">{#$key#}</option>
                                    {/foreach}
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="form-group">
                                <label for="contentData[timeslot_ct]">{#ph_timeslot#|ucfirst} *</label>
                                <select name="contentData[timeslot_ct]" id="contentData[timeslot_ct]" class="form-control to-required">
                                    <option disabled selected value="">{#pn_timeslot#|ucfirst}</option>
                                    <option value="the_morning">{#the_morning#}</option>
                                    <option value="afternoon">{#afternoon#}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="form-group">
                                <label for="contentData[delivery_date_ct]">{#pn_delivery_date#|ucfirst}&nbsp;:</label>
                                <input id="contentData[delivery_date_ct]" min="{($smarty.now + 86400)|date_format:'%Y-%m-%d'}" type="date" name="contentData[delivery_date_ct]" placeholder="{#ph_delivery_date#|ucfirst}" class="form-control date to-required" pattern="{literal}[0-9]{2}/[0-9]{2}/[0-9]{4}{/literal}" />
                            </div>
                        </div>
                    </div>*}
                </div>
            </div>
        </div>
    </div>
{/block}

{block name="scripts" append}
    {$transport_js_files = [
        'group' => [],
        'normal' => [],
        'defer' => [
            "/plugins/transport/js/{if $setting.mode === 'dev'}src/{/if}public{if $setting.mode !== 'dev'}.min{/if}.js"
        ]
    ]}
    {$js_files = array_merge_recursive($js_files,$transport_js_files)}
{/block}