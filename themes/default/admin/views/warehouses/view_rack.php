<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style type="text/css" media="screen">
    <?php /*
    #RackItemsTable td:nth-child(7) {
        text-align: right;
    }
    <?php if($Owner || $Admin || $this->session->userdata('show_cost')) { ?>
    #RackItemsTable td:nth-child(9) {
        text-align: right;
    }
    <?php } if($Owner || $Admin || $this->session->userdata('show_price')) { ?>
    #RackItemsTable td:nth-child(8) {
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
        console.log('<?php echo $rack_id; ?>');
        console.log('<?php echo $rack_warehouse; ?>');
        console.log('<?php echo $rack_column; ?>');
        console.log('<?php echo $rack_row; ?>');
        console.log('<?php echo $rack_z_index; ?>');
        console.log('<?php echo $rack_floor_level; ?>');
        console.log('<?php echo $rack_usage; ?>');
        console.log('<?php echo $rack_status; ?>');

        oTable = $('#RackItemsTable').dataTable({
            "aaSorting": [[2, "asc"], [3, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            <?php /*
            'sAjaxSource': '<?= admin_url('suppliers/getSupplyOrdersLogic'.($warehouse_id ? '/'.$warehouse_id : '').($supplier ? '?supplier='.$supplier->id : '')) ?>',
            */ ?>
            'sAjaxSource': '<?= admin_url('warehouses/handleGetRackItems_logic' . ($rack_id ? '/' . $rack_id : ''))?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                console.log("aData:");
                console.log(aData);
                var oSettings = oTable.fnSettings();
                nRow.id = aData[0];
                nRow.className = "rack_item_link";
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
            {column_number: 1, filter_default_label: "[Pallet Code]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[Image]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[Attachment]", filter_type: "text", data: []},
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
          <i class="fa-fw fa fa-barcode"></i> Rack <?php echo $rack_column . "-" . $rack_row . "" . $rack_z_index . " - Floor Level " . $rack_floor_level; ?>
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
                            <a href="<?= admin_url('warehouses/addRack_view') ?>">
                              <?php /*  <i class="fa fa-plus-circle"></i> <?= lang('add_product') ?> */ ?>
                                <i class="fa fa-plus-circle"></i> <?= "Add Rack" ?>
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

                            <th style="width:14%; text-align: center;">Warehouse</th>
                            <th style="width:14%; text-align: center;">Name</th>
                            <th style="width:11%; text-align: center;">Column</th>
                            <th style="width:11%; text-align: center;">Row</th>
                            <th style="width:11%; text-align: center;">Z-Index</th>
                            <th style="width:11%; text-align: center;">Floor<br>Level</th>
                            <th style="width:11%; text-align: center;">Usage</th>
                            <th style="width:11%; text-align: center;">Status</th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr>

                            <td style="width:14%; text-align: center;" class="dataTables_empty"><?php echo $rack_warehouse; ?></td>
                            <td style="width:14%; text-align: center;" class="dataTables_empty"><?php echo $rack_name; ?></td>
                            <td style="width:11%; text-align: center;" class="dataTables_empty"><?php echo $rack_column; ?></td>
                            <td style="width:11%; text-align: center;" class="dataTables_empty"><?php echo $rack_row; ?></td>
                            <td style="width:11%; text-align: center;" class="dataTables_empty"><?php echo $rack_z_index; ?></td>
                            <td style="width:11%; text-align: center;" class="dataTables_empty"><?php echo $rack_floor_level; ?></td>
                            <td style="width:11%; text-align: center;" class="dataTables_empty"><?php echo $rack_usage; ?></td>
                            <td style="width:11%; text-align: center;" class="dataTables_empty"><?php echo $rack_status; ?></td>

                        </tr>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="box-header" style="background-color: white"></div>
    <div class="box-header">
        <h2 class="blue">
            <?php /*
            <i class="fa-fw fa fa-barcode"></i><?= lang('products') . ' (' . ($warehouse_id ? $warehouse->name : lang('all_warehouses')) . ')'.($supplier ? ' ('.lang('supplier').': '.($supplier->company && $supplier->company != '-' ? $supplier->company : $supplier->name).')' : ''); ?>
            */ ?>
            Rack <?php "" /* echo $rack_column . "-" . $rack_row . "" . $rack_z_index . " - Floor Level " . $rack_floor_level; */ ?> Items

        </h2>
    </div>


    <!-- TABLE CONTENT - ITEMS LIST -->

    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <?php /* <p class="introtext"><?= lang('list_results'); ?></p> */ ?>

                <div class="table-responsive">
                    <table id="RackItemsTable" class="table table-bordered table-condensed table-hover table-striped">

                      <!-- Table Header Row -->

                        <thead>
                        <tr class="primary">

                          <th style="min-width:30px; max-width:30px; width: 30px; text-align: center;">
                              <input class="checkbox checkth" type="checkbox" name="check"/>
                          </th>

                              <th style="width:33%; text-align: center;">Pallet Code</th>
                              <th style="width:33%; text-align: center;">Image</th>
                              <th style="width:33%; text-align: center;">Attachment</th>

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
