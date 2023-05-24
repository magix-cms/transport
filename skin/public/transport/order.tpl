{*<pre>{$transport|print_r}</pre>*}
<ul class="list-unstyled">
    {*<pre>{$setting|print_r}</pre>*}
    {if $transport.type eq 'delivery'}
    {*<li>{$transport.delivery_date|date_format:'%d-%m-%Y'} {#$transport.timeslot#}</li>*}
    {*<li>{#ph_event#}: {#$transport.event#}</li>*}
    <li>{$transport.lastname} {$transport.firstname}</li>
    <li>{$transport.street}</li>
    <li>{$transport.postcode} {$transport.city}</li>
        {$pricettc = ($transport.price * ($setting.vat_rate/100 + 1))|string_format:"%.2f"}
        {$pricehtva = $transport.price|string_format:"%.2f"}
    <li>{#price_tr#} : <span class="main-color-text">{if $setting.price_display === 'tinc'}{$pricettc}{else}{$pricehtva}{/if}€</span></li>
        {else}
        <li>{#$transport.type#|ucfirst}</li>
    {/if}
</ul>