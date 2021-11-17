{extends file="cartpay/step.tpl"}

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
        <div class="col-12 col-sm-4 col-md-5 col-lg-3">
            <label class="radio-inline">
                <input type="radio" data-target="#delivery" name="contentData[type_ct]" id="type_ct[delivery]" value="delivery" checked /> {#delivery#|ucfirst}
            </label>
            <label class="radio-inline">
                <input type="radio" name="contentData[type_ct]" id="pick_up_in_store" value="pick_up_in_store" /> {#pick_up_in_store#|ucfirst}
            </label>
        </div>
    </div>
    <div id="delivery" {*class="collapse"*}>
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
                    <input id="street_ct" type="text" name="contentData[street_ct]" placeholder="{#ph_tr_street#|ucfirst}" value="" class="form-control {*required*}" {*required*}/>
                </div>
            </div>
        </div>
        <div class="row row-center">
            <div class="col-2 col-xs-3 col-sm-4 col-md-5 col-lg-6">
                <div class="form-group">
                    <label for="contentData[id_tr]">{#ph_transport_city#|ucfirst}</label>
                    <select name="contentData[id_tr]" id="contentData[id_tr]" class="form-control {*required*}" {*required*}>
                        <option disabled selected>-- {#pn_transport_city#|ucfirst} --</option>
                        {foreach $transport as $key}
                            <option value="{$key.id}">{$key.name} - {$key.postcode}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
        </div>
        <div class="row row-center">
            <div class="col-2 col-xs-3 col-sm-4 col-md-5 col-lg-6">
                <div class="form-group">
                    <label for="contentData[event_ct]">{#ph_event#|ucfirst}</label>
                    <select name="contentData[event_ct]" id="contentData[event_ct]" class="form-control {*required*}" {*required*}>
                        <option selected>-- {#pn_event#|ucfirst} --</option>
                        {foreach $eventCollection as $key}
                            <option value="{$key}">{#$key#}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
        </div>
        <div class="row row-center">
            <div class="col-12 col-sm-4 col-md-5 col-lg-4">
                <div class="form-group">
                    <label for="contentData[timeslot_ct]">{#ph_timeslot#|ucfirst}</label>
                    <select name="contentData[timeslot_ct]" id="contentData[timeslot_ct]" class="form-control">
                        <option value="">{#pn_timeslot#|ucfirst}</option>
                        <option value="the_morning">{#the_morning#}</option>
                        <option value="afternoon">{#afternoon#}</option>
                    </select>
                </div>
            </div>
            <div class="col-12 col-sm-4 col-md-5 col-lg-4">
                <div class="form-group">
                    <label for="contentData[delivery_date_ct]">{#pn_delivery_date#|ucfirst}&nbsp;:</label>
                    <input id="contentData[delivery_date_ct]" type="date" name="contentData[delivery_date_ct]" placeholder="{#ph_delivery_date#|ucfirst}" class="form-control date" pattern="{literal}[0-9]{2}/[0-9]{2}/[0-9]{4}{/literal}" />
                </div>
            </div>
        </div>
    </div>
{/block}