{*<pre>{$transport|print_r}</pre>*}
<ul class="list-unstyled">
    {if $transport.type eq 'delivery'}
    <li>{$transport.delivery_date|date_format:'%d-%m-%Y'} {#$transport.timeslot#}</li>
    <li>{#ph_event#}: {#$transport.event#}</li>
    <li>{$transport.lastname} {$transport.firstname}</li>
    <li>{$transport.street}</li>
    <li>{$transport.postcode} {$transport.name}</li>
        {$pricettc = ($transport.price * 1.21)|string_format:"%.2f"}
    <li>{#price_tr#} : <span class="main-color-text">{$pricettc}€</span></li>
        {else}
        <li>{#$transport.type#|ucfirst}</li>
    {/if}
</ul>