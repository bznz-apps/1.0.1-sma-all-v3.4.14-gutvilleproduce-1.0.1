<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style type="text/css" media="screen">
    <?php /*
    #ViewBillOfLadingItemsTable td:nth-child(7) {
        text-align: right;
    }
    <?php if($Owner || $Admin || $this->session->userdata('show_cost')) { ?>
    #ViewBillOfLadingItemsTable td:nth-child(9) {
        text-align: right;
    }
    <?php } if($Owner || $Admin || $this->session->userdata('show_price')) { ?>
    #ViewBillOfLadingItemsTable td:nth-child(8) {
        text-align: right;
    }
    <?php } ?>
    */ ?>
</style>

<?php /*
<!-- TIP
Use this code below, to access php values in jquery, assign php value to an html element:
<input type="hidden" id ="x_supply_order_id" value="<?php echo $supply_order_id; ?>">
Then access value inside jquery as:
console.log($('#x_supply_order_id').val());
-->
*/ ?>

<script>

    // Default DataTables Code, Leave as is... Starts here --->

    var oTable;
    $(document).ready(function () {

        console.log("Data Passed to this View");
        console.log('<?php echo "Bill of Lading ID: " . $bol_id; ?>');
        console.log('<?php echo $inspection_created_at; ?>');
        console.log('<?php echo $inspection_date; ?>');
        console.log('<?php echo $inspection_no; ?>');
        console.log('<?php echo $inspection_receiving_id; ?>');
        console.log('<?php echo $inspection_lot_n; ?>');
        console.log('<?php echo $inspection_grower_shipper; ?>');
        console.log('<?php echo $inspection_name; ?>');

        oTable = $('#ViewBillOfLadingItemsTable').dataTable({
            "aaSorting": [[2, "asc"], [3, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            <?php /*
            'sAjaxSource': '<?= admin_url('suppliers/getSupplyOrdersLogic'.($warehouse_id ? '/'.$warehouse_id : '').($supplier ? '?supplier='.$supplier->id : '')) ?>',
            */ ?>
            'sAjaxSource': '<?= admin_url('shipping/handleGetBillOfLadingItems_logic' . ($bol_id ? '/' . $bol_id : ''))?>',
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
                nRow.className = "inspection_link";
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
                null
                // null
                // null  // actions column

                <?php /*

                {"bSortable": false,"mRender": img_hl},
                null, null, null,
                <?php
                  if($Owner || $Admin) {
                    echo '{"mRender": currencyFormat}, {"mRender": currencyFormat},';
                  } else {
                    if($this->session->userdata('show_cost')) {
                      echo '{"mRender": currencyFormat},';
                    }
                    if($this->session->userdata('show_price')) {
                      echo '{"mRender": currencyFormat},';
                    }
                  }
                ?>
                {"mRender": formatQuantity},
                null,
                <?php
                  if(!$warehouse_id || !$Settings->racks) {
                    echo '{"bVisible": false},';
                  } else {
                    echo '{"bSortable": true},';
                  }
                ?>
                {"mRender": formatQuantity},
                {"bSortable": false}

                */ ?>
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
            {column_number: 1, filter_default_label: "[Product]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[Quantity]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[Shipping Qty]", filter_type: "text", data: []},
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
          <i class="fa-fw fa fa-barcode"></i> Bill of Lading No <?php echo $bol_data->bol_no; ?>
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
                            <a href="<?= admin_url('shipping/addBillOfLading_view') ?>">
                              <?php /*  <i class="fa fa-plus-circle"></i> <?= lang('add_product') ?> */ ?>
                                <i class="fa fa-plus-circle"></i> <?= "Add Bill of Lading" ?>
                            </a>
                        </li>

                        <?php /*

                        <?php if(!$warehouse_id) { ?>
                        <li>
                            <a href="<?= admin_url('products/update_price') ?>" data-toggle="modal" data-target="#myModal">
                                <i class="fa fa-file-excel-o"></i> <?= lang('update_price') ?>
                            </a>
                        </li>
                        <?php } ?>
                        <li>
                            <a href="#" id="labelProducts" data-action="labels">
                                <i class="fa fa-print"></i> <?= lang('print_barcode_label') ?>
                            </a>
                        </li>
                        <li>
                            <a href="#" id="sync_quantity" data-action="sync_quantity">
                                <i class="fa fa-arrows-v"></i> <?= lang('sync_quantity') ?>
                            </a>
                        </li>
                        <li>
                            <a href="#" id="excel" data-action="export_excel">
                                <i class="fa fa-file-excel-o"></i> <?= lang('export_to_excel') ?>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#" class="bpo" title="<b><?= $this->lang->line("delete_products") ?></b>"
                                data-content="<p><?= lang('r_u_sure') ?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button>"
                                data-html="true" data-placement="left">
                            <i class="fa fa-trash-o"></i> <?= lang('delete_products') ?>
                             </a>
                         </li>

                         */ ?>

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

                <!-- *******************************************************
                  SALE
                ******************************************************** -->

                <div class="col-md-3">
                    <?php /* <label for="mcode" class="col-sm-4 control-label"><?= lang('product_code') ?> *</label> */ ?>
                    <label><?= "Sale No" ?></label>
                    <?php /* <label for="mcode" class="col-sm-4 control-label"><?= "Product" ?> *</label> */ ?>
                    <input value="<?php echo $bol_data->bol_no ?>" name="sale_no" id="label_input_sale_no" readonly type="text" class="form-control file">
                </div>

                <!-- *******************************************************
                  SHIPPER (BILLER)
                ******************************************************** -->

                <div class="col-md-3">
                    <?php /* <label for="mcode" class="col-sm-4 control-label"><?= lang('product_code') ?> *</label> */ ?>

                    <label><?= "Shipper" ?></label>
                    <?php /* <label for="mcode" class="col-sm-4 control-label"><?= "Product" ?> *</label> */ ?>

                      <div class="form-group">
                          <?php
                          $shpper[''] = "";
                          foreach ($billers as $biller) {
                              $shpper[$biller->id] = $biller->company;
                          }
                          // echo form_dropdown('shipper_id', $shpper, (isset($_POST['shipper_id']) ? $_POST['shipper_id'] : ($biller ? $biller->shipper_id : '')), 'class="form-control select" id="bill_of_lading_shipper_id" placeholder="' . lang("select") . " " . lang("biller") . '" required="required" style="width:100%"')
                          echo form_dropdown('biller_id', $shpper, (isset($_POST['biller_id']) ? $_POST['biller_id'] : ($biller ? $biller->id : '')), 'class="form-control select" id="bill_of_lading_shipper_id" placeholder="Select Shipper" required="required" style="width:100%"')
                          ?>
                      </div>

                </div>

                <!-- *******************************************************
                  WAREHOUSE - PICK UP ADDRESS
                ******************************************************** -->

                <div class="col-md-3">
                    <?php /* <label for="mcode" class="col-sm-4 control-label"><?= lang('product_code') ?> *</label> */ ?>

                    <label><?= "Pick Up Address" ?></label>
                    <?php /* <label for="mcode" class="col-sm-4 control-label"><?= "Product" ?> *</label> */ ?>

                      <div class="form-group">
                          <?php
                          $whouse[''] = "";
                          foreach ($warehouses as $warehouse) {
                              $whouse[$warehouse->id] = $warehouse->name . " - " . $warehouse->address;
                          }
                          // echo form_dropdown('warehouse_id', $whouse, (isset($_POST['warehouse_id']) ? $_POST['warehouse_id'] : ($warehouse ? $warehouse->warehouse_id : '')), 'class="form-control select" id="bill_of_lading_warehouse_id" placeholder="' . lang("select") . " " . lang("warehouse") . '" required="required" style="width:100%"')
                          echo form_dropdown('warehouse_id', $whouse, (isset($_POST['warehouse_id']) ? $_POST['warehouse_id'] : ($warehouse ? $warehouse->warehouse_id : '')), 'class="form-control select" id="bill_of_lading_warehouse_id" placeholder="Select Warehouse" required="required" style="width:100%"')
                          ?>
                      </div>

                </div>

                <!-- ***************************************************
                *  PO
                **************************************************** -->

                <div class="col-md-3">
                    <div class="form-group">
                        <label> <?= /* lang("reference_no", "slref"); */ "PO" ?> </label>
                        <?php echo form_input('default_po', (isset($_POST['default_po']) ? $_POST['default_po'] : $slnumber), 'class="form-control input-tip" id="pay_terms"'); ?>
                    </div>
                </div>

                <div class="row"></div>
                <hr>

                <!-- ***************************************************
                *  BILL TO
                **************************************************** -->

                <div class="col-md-6">
                    <div class="form-group">
                        <label> <?= /* lang("reference_no", "slref"); */ "Bill To" ?> </label>
                        <?php echo form_input('bill_to', (isset($_POST['bill_to']) ? $_POST['bill_to'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                    </div>
                </div>

                <!-- ***************************************************
                *  PO
                **************************************************** -->

                <div class="col-md-3">
                    <div class="form-group">
                        <label> <?= /* lang("reference_no", "slref"); */ "PO" ?> </label>
                        <?php echo form_input('bill_to_po', (isset($_POST['bill_to_po']) ? $_POST['bill_to_po'] : $slnumber), 'class="form-control input-tip" id="pay_terms"'); ?>
                    </div>
                </div>

                <div class="row"></div>
                <hr>

                <!-- ***************************************************
                *  SHIP TO
                **************************************************** -->

                <div class="col-md-6">
                    <div class="form-group">
                        <label> <?= /* lang("reference_no", "slref"); */ "Ship To" ?> </label>
                        <?php echo form_input('ship_to', (isset($_POST['ship_to']) ? $_POST['ship_to'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                    </div>
                </div>

                <!-- ***************************************************
                *  PO
                **************************************************** -->

                <div class="col-md-3">
                    <div class="form-group">
                        <label> <?= /* lang("reference_no", "slref"); */ "PO" ?> </label>
                        <?php echo form_input('ship_to_po', (isset($_POST['ship_to_po']) ? $_POST['ship_to_po'] : $slnumber), 'class="form-control input-tip" id="pay_terms"'); ?>
                    </div>
                </div>

                <div class="row"></div>
                <hr>

                <!-- ***************************************************
                *  SALES TERMS
                **************************************************** -->

                <div class="col-md-3">
                    <div class="form-group">
                        <label> <?= /* lang("reference_no", "slref"); */ "Sales Terms" ?> </label>
                        <?php echo form_input('sale_terms', (isset($_POST['sales_terms']) ? $_POST['sales_terms'] : $slnumber), 'class="form-control input-tip" id="pay_terms"'); ?>
                    </div>
                </div>

                <!-- ***************************************************
                *  PAY TERMS
                **************************************************** -->

                <div class="col-md-3">
                    <div class="form-group">
                        <label> <?= /* lang("reference_no", "slref"); */ "Pay Terms" ?> </label>
                        <?php echo form_input('payment_terms', (isset($_POST['payment_terms']) ? $_POST['payment_terms'] : $slnumber), 'class="form-control input-tip" id="pay_terms"'); ?>
                    </div>
                </div>

                <div class="row"></div>
                <hr>

                <!-- ***************************************************
                *  SHIPPING DATE
                **************************************************** -->

                <?php /* if ($Owner || $Admin) { */ "" ?>
                    <?php /* <div class="col-md-4"> */ ?>
                    <div class="col-md-4">
                        <div class="form-group">
                             <label><?= /* lang("date", "sldate"); */ "Shipping Date" ?></label>
                            <?php echo form_input('shipping_date', (isset($_POST['shipping_date']) ? $_POST['shipping_date'] : ""), 'class="form-control input-tip datetime" id="" autocomplete="off"'); ?>
                        </div>
                    </div>
                <?php /* } */ "" ?>

                <!-- ***************************************************
                *  CARRIER NAME
                **************************************************** -->

                <div class="col-md-4">
                    <div class="form-group">
                        <label> <?= /* lang("reference_no", "slref"); */ "Carrier Name" ?> </label>
                        <?php echo form_input('carrier_name', (isset($_POST['carrier_name']) ? $_POST['carrier_name'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                    </div>
                </div>

                <!-- ***************************************************
                *  TRUCK BROKER
                **************************************************** -->

                <div class="col-md-4">
                    <div class="form-group">
                        <label> <?= /* lang("reference_no", "slref"); */ "Truck Broker" ?> </label>
                        <?php echo form_input('truck_broker', (isset($_POST['truck_broker']) ? $_POST['truck_broker'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                    </div>
                </div>

                <!-- ***************************************************
                *  DRIVER NAME
                **************************************************** -->

                <div class="col-md-4">
                    <div class="form-group">
                        <label> <?= /* lang("reference_no", "slref"); */ "Driver Name" ?> </label>
                        <?php echo form_input('driver_name', (isset($_POST['driver_name']) ? $_POST['driver_name'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                    </div>
                </div>

                <!-- ***************************************************
                *  DRIVER LICENSE
                **************************************************** -->

                <div class="col-md-4">
                    <div class="form-group">
                        <label> <?= /* lang("reference_no", "slref"); */ "Driver License" ?> </label>
                        <?php echo form_input('driver_license', (isset($_POST['driver_license']) ? $_POST['driver_license'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                    </div>
                </div>

                <!-- ***************************************************
                *  DRIVER PHONE
                **************************************************** -->

                <div class="col-md-4">
                    <div class="form-group">
                        <label> <?= /* lang("reference_no", "slref"); */ "Driver Phone" ?> </label>
                        <?php echo form_input('driver_phone', (isset($_POST['driver_phone']) ? $_POST['driver_phone'] : $slnumber), 'class="form-control input-tip" id="bol_form_input-driver_phone"'); ?>
                    </div>
                </div>

                <!-- ***************************************************
                *  TRUCK / TRAILER
                **************************************************** -->

                <div class="col-md-4">
                    <div class="form-group">
                        <label> <?= /* lang("reference_no", "slref"); */ "Truck / Trailer" ?> </label>
                        <?php echo form_input('truck_trailer', (isset($_POST['truck_trailer']) ? $_POST['truck_trailer'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                    </div>
                </div>

                <!-- ***************************************************
                *  TIME OUT
                **************************************************** -->

                <div class="col-md-4">
                    <div class="form-group">
                        <label> <?= /* lang("reference_no", "slref"); */ "Time Out" ?> </label>
                        <?php echo form_input('time_out', (isset($_POST['time_out']) ? $_POST['time_out'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                    </div>
                </div>

                <!-- ***************************************************
                *  TEMPERATURE
                **************************************************** -->

                <div class="col-md-4">
                    <div class="form-group">
                        <label> <?= /* lang("reference_no", "slref"); */ "Temperature (°F)" ?> </label>
                        <?php echo form_input('temperature', (isset($_POST['temperature']) ? $_POST['temperature'] : $slnumber), 'class="form-control input-tip" id="bol_form_input-temperature"'); ?>
                    </div>
                </div>

                <div class="row"></div>
                <hr>

                <!-- ***************************************************
                *  # RECORDER #
                **************************************************** -->

                <div class="col-md-4">
                    <div class="form-group">
                        <label> <?= /* lang("reference_no", "slref"); */ "Recorder #" ?> </label>
                        <?php echo form_input('recorder_no', (isset($_POST['recorder_no']) ? $_POST['recorder_no'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                    </div>
                </div>

                <!-- ***************************************************
                *  # SEAL #
                **************************************************** -->

                <div class="col-md-4">
                    <div class="form-group">
                        <label> <?= /* lang("reference_no", "slref"); */ "Seal #" ?> </label>
                        <?php echo form_input('seal_no', (isset($_POST['seal_no']) ? $_POST['seal_no'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                    </div>
                </div>

                <div class="row"></div>
                <hr>

                <!-- *******************************************************
                  IMAGE - DRIVER SIGNATURE
                ******************************************************** -->

                <div class="col-md-3">
                    <?php /* <?= lang("product_image", "product_image") ?> */ ?>
                    <label><?= "Driver Signature" ?></label>
                    <br>
                    <?= "(Optional) This field is not required." ?>
                    <input id="driver_signature_img" type="file" data-browse-label="<?= lang('browse'); ?>" name="product_image" data-show-upload="false"
                           data-show-preview="false" accept="image/*" class="form-control file">
                </div>

                <!-- *******************************************************
                  IMAGE - DRIVER LICENSE COPY
                ******************************************************** -->

                <div class="col-md-3">
                    <?php /* <?= lang("product_image", "product_image") ?> */ ?>
                    <label><?= "Driver License Copy" ?></label>
                    <br>
                    <?= "(Optional) This field is not required." ?>
                    <input id="driver_license_copy" type="file" data-browse-label="<?= lang('browse'); ?>" name="product_image" data-show-upload="false"
                           data-show-preview="false" accept="image/*" class="form-control file">
                </div>

                <!-- *******************************************************
                  IMAGE - TEM RECORDER COPY
                ******************************************************** -->

                <div class="col-md-3">
                    <?php /* <?= lang("product_image", "product_image") ?> */ ?>
                    <label><?= "Temp Recorder Copy" ?></label>
                    <br>
                    <?= "(Optional) This field is not required." ?>
                    <input id="temp_recorder_copy" type="file" data-browse-label="<?= lang('browse'); ?>" name="product_image" data-show-upload="false"
                           data-show-preview="false" accept="image/*" class="form-control file">
                </div>

                <!-- *******************************************************
                  ATTACH DOCUMENT
                ******************************************************** -->

                <div class="col-md-3">
                    <?= "" /* lang("document", "document") */ ?>
                    <label><?= "Attachments" ?></label>
                    <br>
                    <?= "(Optional) This field is not required." ?>
                    <input id="attachments" type="file" data-browse-label="<?= lang('browse'); ?>" name="document" data-show-upload="false"
                           data-show-preview="false" class="form-control file">
                </div>

                <div class="row"></div>
                <hr>

                <!-- *******************************************************
                ******************************************************** -->

                <div class="row"></div>
                <hr>

            </div>
        </div>
    </div>

    <!-- TABLE CONTENT - ITEMS LIST -->

    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <?php /* <p class="introtext"><?= lang('list_results'); ?></p> */ ?>

                <div class="table-responsive">
                    <table id="ViewBillOfLadingItemsTable" class="table table-bordered table-condensed table-hover table-striped">

                      <!-- Table Header Row -->

                        <thead>
                        <tr class="primary">

                          <?php /*

                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkth" type="checkbox" name="check"/>
                            </th>
                            <th style="min-width:40px; width: 40px; text-align: center;"><?php echo $this->lang->line("image"); ?></th>
                            <th><?= lang("code") ?></th>
                            <th><?= lang("name") ?></th>
                            <th><?= lang("brand") ?></th>
                            <th><?= lang("category") ?></th>
                            <?php
                            if ($Owner || $Admin) {
                                echo '<th>' . lang("cost") . '</th>';
                                echo '<th>' . lang("price") . '</th>';
                            } else {
                                if ($this->session->userdata('show_cost')) {
                                    echo '<th>' . lang("cost") . '</th>';
                                }
                                if ($this->session->userdata('show_price')) {
                                    echo '<th>' . lang("price") . '</th>';
                                }
                            }
                            ?>
                            <th><?= lang("quantity") ?></th>
                            <th><?= lang("unit") ?></th>
                            <th><?= lang("rack") ?></th>
                            <th><?= lang("alert_quantity") ?></th>
                            <th style="min-width:65px; text-align:center;"><?= lang("actions") ?></th>

                          */ ?>

                          <th style="min-width:30px; max-width:30px; width: 30px; text-align: center;">
                              <input class="checkbox checkth" type="checkbox" name="check"/>
                          </th>
                          <th style="width:33.33%; text-align: center;">Product</th>
                          <th style="width:33.33%; text-align: center;">Quantity</th>
                          <th style="width:33.33%; text-align: center;">Shipping Qty</th>

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

                          <?php /*

                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            <th style="min-width:40px; width: 40px; text-align: center;"><?php echo $this->lang->line("image"); ?></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <?php
                            if ($Owner || $Admin) {
                                echo '<th></th>';
                                echo '<th></th>';
                            } else {
                                if ($this->session->userdata('show_cost')) {
                                    echo '<th></th>';
                                }
                                if ($this->session->userdata('show_price')) {
                                    echo '<th></th>';
                                }
                            }
                            ?>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="width:65px; text-align:center;"><?= lang("actions") ?></th>

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

        // // *********************************************************************
        // //
        // // TABLE ROW ACTIONS
        // //
        // // *********************************************************************
        //
        // // GET TABLE COLUMNS AND ROWS INFORMATION
        //
        // var clickedColumn = null;
        // var clickedRow = null;
        // var totalColumns = $("#ViewBillOfLadingItemsTable").find('tr')[0].cells.length;
        // var totalRows = $('#ViewBillOfLadingItemsTable tr').length;
        //
        // // GET COLUMN AND ROW CLICKED
        //
        // $('#ViewBillOfLadingItemsTable tbody').on('click', 'td', function() {
        //     clickedColumn = $(this).parent().children().index($(this));
        //     clickedRow = $(this).parent().parent().children().index($(this).parent());
        //     // alert('Row: ' + clickedRow + ', Column: ' + clickedColumn);
        // });
        //
        // // GET RECORD ID FOUND ON ROW CLICKED
        //
        // $('#ViewBillOfLadingItemsTable tbody').on('click', 'tr', function() {
        //   // console.log('Clicked Row Info:');
        //   // console.log($(this));
        //
        //   // console.log(totalRows);
        //
        //   // console.log("Row Number is: " + clickedRow);
        //
        //   var itemID = $(this)[0].id;
        //
        //   // NAVIGATE ONLY IF CLICKED COLUMN WAS NOT THE LAST COLUMN
        //   if (clickedColumn !== totalColumns-1) {
        //     // ROW MUST HAVE A RECORD ID VALUE IN ITS CONTENT
        //     if (itemID !== "") {
        //       // PREVIEW ITEMID
        //       window.location.href = site.base_url + 'suppliers/previewSupplyOrder/' + itemID;
        //       // EDIT ITEMID
        //       // window.location.href = site.base_url + 'suppliers/editSupplyOrder/' + itemID;
        //     }
        //   }
        //
        // });
        //
        // // *********************************************************************
        // // DISPLAY HAND CURSOR OR POINTER WHEN HOVERING ON TABLE
        // // *********************************************************************
        //
        // $('#ViewBillOfLadingItemsTable tbody').css( 'cursor', 'pointer' );
        //
        // // for old IE browsers
        // $('#ViewBillOfLadingItemsTable tbody').css( 'cursor', 'hand' );
        //
        // // *********************************************************************
        // // *********************************************************************
        // // *********************************************************************

    });
</script>
