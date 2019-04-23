<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style type="text/css" media="screen">
    <?php /*
    #ReceivingItemsTable td:nth-child(7) {
        text-align: right;
    }
    <?php if($Owner || $Admin || $this->session->userdata('show_cost')) { ?>
    #ReceivingItemsTable td:nth-child(9) {
        text-align: right;
    }
    <?php } if($Owner || $Admin || $this->session->userdata('show_price')) { ?>
    #ReceivingItemsTable td:nth-child(8) {
        text-align: right;
    }
    <?php } ?>
    */ ?>
</style>

<?php /*
<!-- TIP
Use this code below, to access php values in jquery, assign php value to an html element:
<input type="hidden" id ="x_supply_order_id" value="<?php echo $receiving_report->id; ?>">
Then access value inside jquery as:
console.log($('#x_supply_order_id').val());
-->
*/ ?>

<script>

    // Default DataTables Code, Leave as is... Starts here --->

    var oTable;
    $(document).ready(function () {

        console.log("Data Passed to this View");
        console.log('<?php echo $receiving_report; ?>');
        console.log('<?php echo $pallets; ?>');
        console.log('<?php echo $palletsWithItems; ?>');

        oTable = $('#ReceivingItemsTable').dataTable({
            "aaSorting": [[2, "asc"], [3, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            <?php /*
            'sAjaxSource': '<?= admin_url('suppliers/getSupplyOrdersLogic'.($warehouse_id ? '/'.$warehouse_id : '').($supplier ? '?supplier='.$supplier->id : '')) ?>',
            */ ?>
            'sAjaxSource': '<?= admin_url('receiving/handleGetReceivingItems_logic' . ($receiving_report->id ? '/' . $receiving_report->id : ''))?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                var oSettings = oTable.fnSettings();
                nRow.id = aData[0];
                nRow.className = "supply_order_link";
                nRow.style = "text-align: center;";
                // nRow.className = "product_link";
                //if(aData[7] > aData[9]){ nRow.className = "product_link warning"; } else { nRow.className = "product_link"; }
                return nRow;
            },

    // Default DataTables Code, Leave as is... Ends here <---

            // DATATABE COLUMN OPTIONS
            // we set option to element 0, or the first column, so that i can be checkboxes

            "aoColumns": [
                {"bSortable": false, "mRender": checkbox},
                null,
                null,
                null,
                null,
                null
                // UNHIDE ACTIONS COLUMN
                // null  // actions column
            ]

        })

        // Last Row, In order to filter data by column row value
        // Dont forget to add .lang language

        .fnSetFilteringDelay().dtFilter([
            <?php /*
              // Description:
              // Line below is just an example of using the var lang for localization:
              {column_number: 2, filter_default_label: "[<?=lang('code');?>]", filter_type: "text", data: []},
            */ ?>
            {column_number: 1, filter_default_label: "[Date]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[Code]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[Rack]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[Image]", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "[Attachment]", filter_type: "text", data: []},
            <?php /*
            // Description:
            // Check if user is of type Owner or Admin... then based on that choose if we want to show columns Cost and Price
            $col = 5;
            if($Owner || $Admin) {
                echo '{column_number : 6, filter_default_label: "['.lang('cost').']", filter_type: "text", data: [] },';
                echo '{column_number : 7, filter_default_label: "['.lang('price').']", filter_type: "text", data: [] },';
                $col += 2;
            } else {
                if($this->session->userdata('show_cost')) { $col++; echo '{column_number : '.$col.', filter_default_label: "['.lang('cost').']", filter_type: "text", data: [] },'; }
                if($this->session->userdata('show_price')) { $col++; echo '{column_number : '.$col.', filter_default_label: "['.lang('price').']", filter_type: "text, data: []" },'; }
            }
            */ ?>
            <?php /*
            // Description:
            // Based on the above, if Cost and Price columns are shown, the column numbers below are calculated...
            {column_number: <?php $col++; echo $col; ?>, filter_default_label: "[<?=lang('quantity');?>]", filter_type: "text", data: []},
            {column_number: <?php $col++; echo $col; ?>, filter_default_label: "[<?=lang('unit');?>]", filter_type: "text", data: []},
            {column_number: <?php $col++; echo $col; ?>, filter_default_label: "[<?=lang('alert_quantity');?>]", filter_type: "text", data: []},
            */ ?>

        ], "footer");

        // console.log("oTable is:");
        // console.log(oTable);

    });
</script>

<?php
  // UNHIDE ACTIONS COLUMN
  /*
  if ($Owner || $GP['bulk_actions']) {
    echo admin_form_open('products/product_actions'.($warehouse_id ? '/'.$warehouse_id : ''), 'id="action-form"');
  }
  */
?>
<div class="box">
    <div class="box-header">

        <!-- View - Header - Page Title -->

        <h2 class="blue">
          <?php /*
          <i class="fa-fw fa fa-barcode"></i><?= lang('products') . ' (' . ($warehouse_id ? $warehouse->name : lang('all_warehouses')) . ')'.($supplier ? ' ('.lang('supplier').': '.($supplier->company && $supplier->company != '-' ? $supplier->company : $supplier->name).')' : ''); ?>
          */ ?>
          <i class="fa-fw fa fa-barcode"></i> Receiving Report <?php echo $receiving_report->receiving_report_number; ?>
        </h2>

        <!-- View - Header - Right Side Menu Items -->

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="icon fa fa-tasks tip" data-placement="left" title="<?= lang("actions") ?>"></i>
                    </a>
                    <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">

                        <li>
                            <a href="<?= admin_url('suppliers/addSupplyOrder_view') ?>">
                              <?php /*  <i class="fa fa-plus-circle"></i> <?= lang('add_product') ?> */ ?>
                                <i class="fa fa-plus-circle"></i> <?= "Add Supply Order" ?>
                            </a>
                        </li>

                    </ul>
                </li>
                <?php if (!empty($warehouses)) { ?>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-building-o tip" data-placement="left" title="<?= lang("warehouses") ?>"></i></a>
                        <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">
                            <li><a href="<?= admin_url('products') ?>"><i class="fa fa-building-o"></i> <?= lang('all_warehouses') ?></a></li>
                            <li class="divider"></li>
                            <?php
                            foreach ($warehouses as $warehouse) {
                                echo '<li><a href="' . admin_url('products/' . $warehouse->id) . '"><i class="fa fa-building"></i>' . $warehouse->name . '</a></li>';
                            }
                            ?>
                        </ul>
                    </li>
                <?php } ?>
            </ul>
        </div>

    </div>

    <!-- TABLE CONTENT - INFO -->

    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <?php /* <p class="introtext"><?= lang('list_results'); ?></p> */ ?>

                <div class="table-responsive">
                    <table id="ReceivingReportTable" class="table table-bordered table-condensed table-hover table-striped">

                      <!-- Table Header Row -->

                        <thead>
                        <tr class="primary">

                            <th style="width:10%; text-align: center;">Date</th>
                            <th style="width:10%; text-align: center;">Receiving<br>Report No</th>
                            <th style="width:10%; text-align: center;">Supply<br>Order No</th>
                            <th style="width:10%; text-align: center;">Manifest<br>Ref No</th>
                            <th style="width:10%; text-align: center;">Image</th>
                            <th style="width:10%; text-align: center;">Attachment</th>
                            <th style="width:30%; text-align: center;">Comments</th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr>

                            <td style="width:10%; text-align: center;" class="dataTables_empty"><?php echo $receiving_report->created_at; ?></td>
                            <td style="width:10%; text-align: center;" class="dataTables_empty"><?php echo $receiving_report->receiving_report_number; ?></td>
                            <td style="width:10%; text-align: center;" class="dataTables_empty"><?php echo $receiving_report->supply_order_id; ?></td>
                            <td style="width:10%; text-align: center;" class="dataTables_empty"><?php echo $receiving_report->manifest_ref_no; ?></td>
                            <td style="width:10%; text-align: center;" class="dataTables_empty"><?php echo $receiving_report->image; ?></td>
                            <td style="width:10%; text-align: center;" class="dataTables_empty"><?php echo $receiving_report->attachment; ?></td>
                            <td style="width:30%; text-align: center;" class="dataTables_empty"><?php echo $receiving_report->comments; ?></td>

                        </tr>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLE CONTENT - ITEMS LIST -->

    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <?php /* <p class="introtext"><?= lang('list_results'); ?></p> */ ?>

                <div class="table-responsive">
                    <table id="ReceivingItemsTable" class="table table-bordered table-condensed table-hover table-striped">

                      <!-- Table Header Row -->

                        <thead>
                        <tr class="primary">

                          <th style="min-width:30px; max-width:30px; width: 30px; text-align: center;">
                              <input class="checkbox checkth" type="checkbox" name="check"/>
                          </th>

                              <th style="width:20%; text-align: center;">Date</th>
                              <th style="width:30%; text-align: center;">Code</th>
                              <th style="width:30%; text-align: center;">Rack</th>
                              <th style="width:10%; text-align: center;">Image</th>
                              <th style="width:10%; text-align: center;">Attachment</th>

                          <?php /*
                          // UNHIDE ACTIONS COLUMN
                          <th style="width:20px; text-align:center;"><?= lang("actions") ?></th>
                          */ ?>

                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="11" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
                        </tr>
                        </tbody>

                        <!-- Table Footer Row - Filter -->

                        <tfoot class="dtFilter">

                        <tr class="active">

                          <th style="min-width:30px; max-width:30px; width: 30px; text-align: center;">
                              <input class="checkbox checkft" type="checkbox" name="check"/>
                          </th>

                              <th style="text-align: center;"></th>
                              <th style="text-align: center;"></th>
                              <th style="text-align: center;"></th>
                              <th style="text-align: center;"></th>
                              <th style="text-align: center;"></th>

                          <?php /*
                          // UNHIDE ACTIONS COLUMN
                          <th style="width:20px; text-align:center;"><?= lang("actions") ?></th>
                          */ ?>

                        </tr>
                        </tfoot>

                    </table>
                </div>
            </div>
        </div>
    </div>



    <!-- PALLET DETAILS -->

    <!-- TABLE CONTENT - INFO -->

    <?php

        // echo '<pre>'; print_r($palletsWithItems); echo '</pre>';

        foreach ($palletsWithItems as $onePalletWithItem) {
    ?>

            <div class="box-content">
                <div class="row">
                    <div class="col-lg-12">

                        <div class="table-responsive">
                            <table id="" class="table table-bordered table-condensed table-hover table-striped">

                                <!-- Table Header Row -->

                                <thead>
                                <tr class="primary">

                                    <th style="width:20%; text-align: center;">Date</th>
                                    <th style="width:30%; text-align: center;">Code</th>
                                    <th style="width:30%; text-align: center;">Rack</th>
                                    <th style="width:10%; text-align: center;">Image</th>
                                    <th style="width:10%; text-align: center;">Attachment</th>

                                </tr>
                                </thead>
                                <tbody>
                                <tr>

                                    <td style="width:20%; text-align: center;" class="dataTables_empty"><?php echo $onePalletWithItem['created_at']; ?></td>
                                    <td style="width:30%; text-align: center;" class="dataTables_empty"><?php echo $onePalletWithItem['code']; ?></td>
                                    <td style="width:30%; text-align: center;" class="dataTables_empty"><?php echo $onePalletWithItem['rack_id']; ?></td>
                                    <td style="width:10%; text-align: center;" class="dataTables_empty"><?php echo $onePalletWithItem['image']; ?></td>
                                    <td style="width:10%; text-align: center;" class="dataTables_empty"><?php echo $onePalletWithItem['attachment']; ?></td>

                                </tr>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <?php
                $palletItems = $onePalletWithItem['items'];
                // echo '<pre>'; print_r($palletItems); echo '</pre>';
                // echo "<br>";

                foreach ($palletItems as $item => $value) {

                  // echo "palletItems[item]->product_id: " . $palletItems[$item]->product_id;
                  // echo "<br>";
                  // echo "palletItems[item]->quantity: " . $palletItems[$item]->quantity;
                  // echo "<br>";

            ?>

                    <div class="box-content">
                        <div class="row">
                            <div class="col-lg-12">

                                <div class="table-responsive">
                                    <table id="" class="table table-bordered table-condensed table-hover table-striped">

                                        <!-- Table Header Row -->

                                        <thead>
                                        <tr class="primary">

                                            <th style="width:50%; text-align: center;">Product</th>
                                            <th style="width:50%; text-align: center;">Quantity</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>

                                            <td style="width:50%; text-align: center;" class="dataTables_empty"><?php echo $palletItems[$item]->product_id; ?></td>
                                            <td style="width:50%; text-align: center;" class="dataTables_empty"><?php echo $palletItems[$item]->quantity; ?></td>

                                        </tr>
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

            <?php
                }
            ?>

    <?php
        }
    ?>

</div>
<?php if ($Owner || $GP['bulk_actions']) { ?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?= form_submit('performAction', 'performAction', 'id="action-form-submit"') ?>
    </div>
    <?= form_close() ?>
<?php } ?>

<script type="text/javascript">
    $(document).ready(function () {
    });
</script>
