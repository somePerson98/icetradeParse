<style>

    #auctions-block td.lst.top{
        padding: 15px;
    }
    #auctions-block td.lst.top:nth-child(even){
        background: #d6d6d6;
    }
</style>
<table border="1" id="auctions-block" style="border-collapse: collapse">
    <thead><?=$thead?></thead>
    <?php foreach ($parsed as $auction){?>
            <tr class="auction-row"><?=$auction['item']?></tr>
    <?php } ?>
</table>
