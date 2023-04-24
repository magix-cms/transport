{if $transport.type eq 'delivery'}
{*{$transport.delivery_date|date_format:'%d-%m-%Y'} {#$transport.timeslot#}<br/>
{#ph_event#}: {#$transport.event#}<br/>*}
{$transport.lastname} {$transport.firstname}<br/>
{$transport.street}<br/>
{$transport.postcode} {$transport.city}<br/>
{$transport.country}<br/>
    {else}
{#$transport.type#|ucfirst}
{/if}