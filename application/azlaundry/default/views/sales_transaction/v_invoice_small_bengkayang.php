<?php
    $ci =& get_instance();
    $ci->load->helper('az_config');
    $ci->load->helper('az_core');
    $store_name = az_get_config('app_name');
    $store_description = az_get_config('app_description');
?>
<html moznomarginboxes mozdisallowselectionprint>
    <head>
        <title>
            <?php echo $outlet['outlet_name'].' - '.azlang('Print Nota');?>
        </title>
        <style type="text/css">
            html {
                font-family: "Verdana";
            }
            html, body, .content {
                padding: 0px;
                margin: 0px;
            }
            .content {
                /*width: 58mm;*/
                font-size: 16pt;
                padding: 0px;
            }
            .content .title {
                text-align: center;
            }
            .content .head-desc {
                margin-top: 10px;
                display: table;
                width: 100%;
            }
            .content .head-desc > div {
                display: table-cell;
            }
            .content .head-desc .user {
                text-align: right;
            }
            .content .nota {
                text-align: center;
                margin-top: 5px;
                margin-bottom: 5px;
            }
            .content .separate {
                margin-top: 10px;
                margin-bottom: 15px;
                border-top: 1px dashed #000;
            }
            .content .transaction-table {
                width: 100%;
                font-size: 16pt;
            }
            .content .transaction-table .name {
                width: 185px;
            }
            .content .transaction-table .qty {
                text-align: center;
            }
            .content .transaction-table .sell-price, .content .transaction-table .final-price {
                text-align: right;
                width: 65px;
            }
            .content .transaction-table tr td {
                vertical-align: top;
            }
            .content .transaction-table .price-tr td {
                padding-top: 7px;
                padding-bottom: 7px;
            }
            .content .transaction-table .discount-tr td {
                padding-top: 7px;
                padding-bottom: 7px;
            }
            .content .transaction-table .separate-line {
                height: 1px;
                border-top: 1px dashed #000;
            }
            .content .thanks {
                margin-top: 15px;
                text-align: center;
            }
            .content .azost {
                margin-top:5px;
                text-align: center;
                font-size:10px;
            }
            /*@media print {
                @page  { 
                    width: 80mm;
                    margin: 0mm;
                }
            }*/

        </style>
    </head>
    <body onload="window.print();">
        <div class="content">
            <div style="text-align:center;">
                <img style="display:inline-block;" height="58px" src="<?php echo base_url().AZAPP;?>assets/images/logo.png">
            </div>
            <div class="title" style="font-size:20pt" align="center">
                <?php echo $outlet['outlet_name'];?>
            </div>
            <div align="center">
                <?php echo $outlet['address'];?><br>
                <?php echo $outlet['phone'];?><br>
            </div>

            <div class="head-desc" style="margin-top:5pt;">
                <div class="date" align="center">
                    <?php
                        echo Date("d-m-Y H:i", strtotime($data['date']));
                    ?>
                </div>
            </div>
            <div>
                <div class="user" style="font-size: 20pt;margin-top:5pt" align="center">
                    <?php
                        echo $data['customer_name'];
                    ?>
                </div>
            </div>
            
            <div class="nota">
                <?php echo $data['code'];?>
            </div>

            <div class="separate"></div>

            <div class="transaction">
                <table class="transaction-table" cellspacing="0" cellpadding="0">
                    <?php
                        $arr_discount = array();
                        foreach ($transaction as $key => $value) {
                            $total = $value['price'] - $value['discount'] + $value['tax'] + $value['add_cost'];
                            echo "<tr>";
                            echo "  <td class='name'>".$value['product_name']."<br>";
                            echo "  ".az_thousand_separator_decimal($value['qty']);
                            echo "  x".az_thousand_separator($total)."</td>";
                            echo "  <td class='final-price'>".az_thousand_separator($value['qty'] * $total)."</td>";
                            echo "</tr>";
                        }
                    ?>
                    
                    <tr class="price-tr">
                        <td colspan="2">
                            <div class="separate-line"></div>
                        </td>
                    </tr>
                    <tr>
                        <td class="final-price">
                            Harga
                        </td>
                        <td class="final-price">
                            <?php echo az_thousand_separator($data['grand_total']);?>
                        </td>
                    </tr>
                    <?php
                        if ($data['grand_tax'] > 0) {
                    ?>
                    <tr>
                        <td class="final-price">
                            <?php echo azlang('Tax');?>
                        </td>
                        <td class="final-price">
                            <?php echo az_thousand_separator($data['grand_tax']);?>
                        </td>
                    </tr>
                    <?php
                        }
                    ?>
                    <?php
                        if ($data['grand_discount'] > 0) {
                    ?>
                    <tr>
                        <td class="final-price">
                            <?php echo azlang('Discount');?>
                        </td>
                        <td class="final-price">
                            <?php echo az_thousand_separator($data['grand_discount']);?>
                        </td>
                    </tr>
                    <?php
                        }
                    ?>

                    <?php
                        if ($data['grand_add_cost'] > 0) {
                    ?>
                    <tr>
                        <td class="final-price">
                            <?php echo azlang('Add Cost');?>
                        </td>
                        <td class="final-price">
                            <?php echo az_thousand_separator($data['grand_add_cost']);?>
                        </td>
                    </tr>
                    <?php
                        }
                    ?>

                    <tr class="discount-tr">
                        <td colspan="2">
                            <div class="separate-line"></div>
                        </td>
                    </tr>

                    <tr>
                        <td class="final-price" style="font-size:18pt;">
                            TOTAL
                        </td>
                        <td class="final-price" style="font-size:18pt">
                            <?php echo az_thousand_separator($data['grand_total_final']);?>
                        </td>
                    </tr>
                </table>
            </div>
            <div>
                <table cellpadding="4" cellspacing="0" class="table-description" style="border-collapse: collapse;font-size:12pt;">
                    <tr>
                        <th><?php echo azlang('#');?></th>
                        <th><?php echo azlang('Description');?></th>
                        <th><?php echo azlang('Qty');?></th>
                    </tr>
                    <?php 
                        $total_qty = 0;
                        foreach ($transaction_detail as $key => $value) {
                            $total_qty += $value['detail_qty'];
                    ?>
                    <tr>
                        <td><?php echo $key + 1;?></td>
                        <td><?php echo $value['detail_description'];?></td>
                        <td><?php echo $value['detail_qty'];?></td>
                    </tr>
                    <?php
                        }
                    ?>
                    <tr>
                        <td colspan="3">Total Qty: <?php echo $total_qty;?></td>
                    </tr>
                </table>
            </div>
            <div style="margin-top:5px;margin-bottom:3px;">
                <div style="margin-bottom:10px">Tanggal Selesai:<br><?php echo Date('d-m-Y', strtotime($data['duedate']));?></div>
                <div>Status Bayar:<br><?php echo azlang($data['pay']);?></div>
            </div>
            <div style="margin-top:10px;margin-bottom:10px;">
                <?php echo $data['note'];?>
            </div>
            <div>
                <?php echo az_get_config('app_footer_nota');?>
            </div>
        </div>
    </body>
</html>