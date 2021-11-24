{if $transport.type eq 'delivery'}
{$transport.lastname} {$transport.firstname}<br/>
{$transport.street}<br/>
{$transport.postcode} {$transport.name}<br/>
    {else}
{#$transport.type#|ucfirst}
{/if}