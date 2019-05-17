<style>

    span.nw{
        white-space: nowrap !important;
    }
    tr:nth-child(odd){
        background-color: #e2e3e5;
    }
</style>
<table border="1" id="auctions" style="border-collapse: collapse">
    <?php

    foreach ($params as $auction){?>

        <tr><?= $auction?></tr>

    <?php } ?>
</table>