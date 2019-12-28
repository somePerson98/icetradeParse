<?php
/** @var array $addition */
/** @var array $auctions */
$thead = $addition;
?>
<style>

    /*span.nw{*/
    /*    white-space: nowrap !important;*/
    /*    background-color: #0b2e13;*/
    /*}*/
</style>

<table border="1" id="auctions" style="border-collapse: collapse">
    <thead><?=$thead?></thead>
    <?php foreach ($auctions as $auction){?>

        <tr><?="<td>" . $auction['key_word'] ."</td> ". $auction['item']?></tr>

    <?php } ?>
</table>