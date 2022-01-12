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
            .content {
                /*width: 58mm;*/
                font-size: 12px;
                padding: 5px;
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
                font-size: 12px;
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
    <body onLoad="window.print();">
        <div class="content">
            <div style="text-align:left;">
                <p><img style="display:inline-block;" height="58px" src="<?php echo base_url().AZAPP;?>assets/images/logo.png"><br>
                </p>
            </div>
            <div class="title">
                <div align="left"><strong><?php echo $outlet['outlet_name'];?></strong><br>
                  <?php echo $outlet['address'];;?><br>
                  <?php echo $outlet['phone'];;?><br>
              </div>
          </div>

            <div class="head-desc">
                <div class="date"><strong>Tanggal :</strong>
                  <?php
                        echo Date("d-m-Y H:i", strtotime($data['date']));
                    ?>
                </div>
                <div class="user">
                    <strong>
                    <?php
                        echo $data['customer_name'];
                    ?>
                </strong></div>
            </div>
            
            <div class="nota">
                <div align="left"><strong>No Trx : </strong><?php echo $data['code'];?>
                </div>
            </div>

            <div class="separate"></div>

            <div class="transaction">
                <table class="transaction-table" cellspacing="0" cellpadding="0">
                    <?php
                        $arr_discount = array();
                        foreach ($transaction as $key => $value) {
                            $total = $value['price'] - $value['discount'] + $value['tax'] + $value['add_cost'];
                            echo "<tr>";
                            echo "  <td class='name'>".$value['product_name']."</td>";
                            echo "  <td class='qty'>".az_thousand_separator_decimal($value['qty'])."</td>";
                            echo "  <td class='sell-price'>".az_thousand_separator($total)."</td>";
                            echo "  <td class='final-price'>".az_thousand_separator($value['qty'] * $total)."</td>";
                            echo "</tr>";
                        }
                    ?>
                    
                    <tr class="price-tr">
                        <td colspan="4">
                            <div class="separate-line"></div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="final-price">
                            <?php echo azlang('Sell Price');?>
                        </td>
                        <td class="final-price">
                            <?php echo az_thousand_separator($data['grand_total']);?>
                        </td>
                    </tr>
                    <?php
                        if ($data['grand_tax'] > 0) {
                    ?>
                    <tr>
                        <td colspan="3" class="final-price">
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
                        <td colspan="3" class="final-price">
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
                        <td colspan="3" class="final-price">
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
                        <td colspan="4">
                            <div class="separate-line"></div>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="3" class="final-price">
                            TOTAL
                        </td>
                        <td class="final-price">
                            <?php echo az_thousand_separator($data['grand_total_final']);?>
                        </td>
                    </tr>
                </table>
            </div>
            <div>
                <table cellpadding="4" cellspacing="0" class="table-description" style="border-collapse: collapse;font-size:10px;">
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
            <div style="margin-top:5px;margin-bottom:5px;">
                <div>Tanggal Selesai: <?php echo Date('d-m-Y', strtotime($data['duedate']));?></div>
                <div>Status Bayar: <?php echo azlang($data['pay']);?></div>
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