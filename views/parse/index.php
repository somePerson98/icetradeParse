<style>

    span.nw{
        white-space: nowrap !important;
        background-color: #0b2e13;
    }
</style>
<table border="1" id="auctions" style="border-collapse: collapse">
    <?php

    foreach ($params as $auction){?>

            <tr><?= $auction?></tr>

    <?php } ?>
</table>
