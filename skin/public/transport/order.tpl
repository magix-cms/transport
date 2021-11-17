{*<pre>{$transport|print_r}</pre>*}
<ul class="list-unstyled">
    {if $transport.type eq 'delivery'}
    <li>{$transport.lastname} {$transport.firstname}</li>
    <li>{$transport.street}</li>
    <li>{$transport.postcode} {$transport.name}</li>
    <li>{#price_tr#} : <span class="main-color-text">{$transport.price}€</span></li>
        {else}
        <li>{#$transport.type#|ucfirst}</li>
    {/if}
</ul>