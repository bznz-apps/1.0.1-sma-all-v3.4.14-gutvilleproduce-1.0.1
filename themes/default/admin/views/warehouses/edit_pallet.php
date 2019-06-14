<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script type="text/javascript">
    $(document).ready(function () {

        $(document).on('click', '#supply_order_items-reset_button', function(e) {
            // event.preventDefault();
            $('#pallet_code').val("<?php echo $palletData->code; ?>");
            $('#pallet_barcode_symbology').val("<?php echo $palletData->barcode_symbology; ?>");
            $('#select_warehouse_id').val("<?php echo $palletData->warehouse_id; ?>");
            $('#select_rack_id').val("<?php echo $palletData->rack_id; ?>");
            $('#select_receiving_report_id').val("<?php echo $palletData->receiving_report_id; ?>");
            // $('#input_pallet_image').val("<?php echo $palletData->image; ?>");
            $('#pallet_note').val("<?php echo $palletData->description; ?>");
        });


        $('#pallet_code').val("<?php echo $palletData->code; ?>");
        $('#pallet_barcode_symbology').val("<?php echo $palletData->barcode_symbology; ?>");
        $('#select_warehouse_id').val("<?php echo $palletData->warehouse_id; ?>");
        $('#select_rack_id').val("<?php echo $palletData->rack_id; ?>");
        $('#select_receiving_report_id').val("<?php echo $palletData->receiving_report_id; ?>");
        // $('#input_pallet_image').val("<?php echo $palletData->image; ?>");
        $('#pallet_note').val("<?php echo $palletData->description; ?>");

        // Start inputs with empty values here
        // $('#').val("");

        // // Populate text input with a single value
        // $.ajax({
        //     url : '<?= admin_url() ?>' + 'shipping/getNextBOLReportNo',
        //     type : 'GET',
        //     data : {},
        //     dataType:'json',
        //     success : function(data) {
        //         let nextNo = parseInt(data);
        //         $('#bol_form_input-bol_no').val(nextNo);
        //     },
        //     error : function(request, error)
        //     {
        //         // alert("Request: " + JSON.stringify(request));
        //         alert("Error: " + JSON.stringify(error));
        //     }
        // });

        // // ON EVERY FORM INPUT CHANGE, SAVE NEW VALUES TO localStorage
        //
        // $(document).on('change', '#bol_form_input-bol_no', function(e) {
        // });

    });
</script>

<div class="box">

    <!-- ***********************************************************************

      ADD PAGE TITLE AND ICON HERE

    ************************************************************************ -->

    <div class="box-header">
        <?php /*
          <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('add_product'); ?></h2>
        */ ?>
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i>Edit Pallet <?php echo $palletData->code ?></h2>
    </div>

    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <!-- ***********************************************************

                  ADD INTRO OR INSTRUCTIONS TEXT HERE

                ************************************************************ -->

                <p class="introtext"><?php echo lang('enter_info'); ?></p>

                <!-- ***********************************************************

                  CALL FUNCTION FROM CONTROLLER HERE

                ************************************************************ -->

                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                // echo admin_form_open_multipart("products/add", $attrib)
                echo admin_form_open_multipart("warehouses/handleEditPallet_logic/" . $palletData->id, $attrib)
                ?>

                <div class="col-md-12">



                  <!-- *******************************************************
                    SELECT SUPPLIER - USE THIS INPUT WHEN MANY RECORDS DISPLAYED IN dropdown
                  ******************************************************** -->

                  <?php /*
                  <div class="form-group all">
                  */ ?>
                      <?php /*
                          <?= lang("product_image", "product_image") ?>
                      */ ?>
                      <?php /*
                          // Uncomment this one
                          <label><?= "Select Supplier *" ?></label>
                      */ ?>
                      <?php /*
                          // Ucomment just this line below
                          echo form_input('supplier', (isset($_POST['supplier']) ? $_POST['supplier'] : ''), 'class="form-control ' . ($product ? '' : 'suppliers') . '" id="' . ($product && ! empty($product->supplier1) ? 'supplier1' : 'supplier') . '" placeholder="' . lang("select") . ' ' . lang("supplier") . '" style="width:100%;"');
                          // echo form_input('supplier', (isset($_POST['supplier']) ? $_POST['supplier'] : ''), 'class="form-control ' . ($product ? '' : 'suppliers') . '" id="' . "form-add_supply_order-supplier" . '" placeholder="' . lang("select") . ' ' . lang("supplier") . '" style="width:100%;"');
                          // id="form-add_supply_order-supplier"
                      */ ?>
                  <?php /*
                  </div>
                  */ ?>

                  <!-- *******************************************************
                    PALLET CODE
                  ******************************************************** -->

                  <div class="col-md-6">
                  <div class="form-group">
                      <label><?= "Pallet Code *" /* lang("product_code", "code") */ ?></label>
                      <div class="input-group">
                          <?= form_input('code', (isset($_POST['code']) ? $_POST['code'] : ''), 'class="form-control" id="pallet_code"') ?>
                          <span class="input-group-addon pointer" id="random_num" style="padding: 1px 10px;">
                              <i class="fa fa-random"></i>
                          </span>
                      </div>
                      <span class="help-block"><?= lang('you_scan_your_barcode_too') ?></span>
                  </div>
                  </div>

                  <!-- *******************************************************
                    BARCODE SYMBOLOGY
                  ******************************************************** -->

                  <div class="col-md-6">
                  <div class="form-group">
                      <?= lang("barcode_symbology", "barcode_symbology") . " (Default is Code128)" ?>
                      <?php
                      $bs = array('code25' => 'Code25', 'code39' => 'Code39', 'code128' => 'Code128', 'ean8' => 'EAN8', 'ean13' => 'EAN13', 'upca' => 'UPC-A', 'upce' => 'UPC-E');
                      echo form_dropdown('barcode_symbology', $bs, (isset($_POST['barcode_symbology']) ? $_POST['barcode_symbology'] : ($product ? $product->barcode_symbology : 'code128')), 'class="form-control select" id="pallet_barcode_symbology" required="required" style="width:100%;"');
                      ?>
                  </div>
                  </div>

                  <div class="row"></div>

                  <!-- *******************************************************
                    SELECT WAREHOUSE
                  ******************************************************** -->

                  <div class="col-md-6">
                  <div class="form-group">
                      <?php /* <label for="mcode" class="col-sm-4 control-label"><?= lang('product_code') ?> *</label> */ ?>

                      <label><?= "Warehouse *" ?></label>
                      <?php /* <label for="mcode" class="col-sm-4 control-label"><?= "Product" ?> *</label> */ ?>

                        <div class="form-group">
                            <?php
                            $whouse[''] = "";
                            foreach ($warehouses as $warehouse) {
                                $whouse[$warehouse->id] = $warehouse->name;
                            }
                            // echo form_dropdown('warehouse_id', $whouse, (isset($_POST['warehouse_id']) ? $_POST['warehouse_id'] : ($warehouse ? $warehouse->name : '')), 'class="form-control select" id="select_warehouse_id" placeholder="' . lang("select") . " " . lang("supplier") . '" required="required" style="width:100%"')
                            echo form_dropdown('warehouse_id', $whouse, (isset($_POST['warehouse_id']) ? $_POST['warehouse_id'] : ($warehouse ? $warehouse->name : '')), 'class="form-control select" id="select_warehouse_id" placeholder="' . lang("select") . " " . "Warehouse" . '" required="required" style="width:100%"')
                            ?>
                        </div>

                  </div>
                  </div>

                  <!-- *******************************************************
                    SELECT RACK
                  ******************************************************** -->

                  <div class="col-md-6">
                  <div class="form-group">
                      <?php /* <label for="mcode" class="col-sm-4 control-label"><?= lang('product_code') ?> *</label> */ ?>

                      <label><?= "Rack" ?></label>
                      <?php /* <label for="mcode" class="col-sm-4 control-label"><?= "Product" ?> *</label> */ ?>

                        <div class="form-group">
                            <?php

                            // // if(!isset($warehouse_id) || trim($warehouse_id) == '')
                            // if ($_POST['rack_id'] == "")
                            // {
                            //
                            //   // isset($_POST['rack_id'])
                            //   //echo form_dropdown('rack_id', $whouse_rack, (isset($_POST['rack_id']) ? $_POST['rack_id'] : ($rack ? $rack->id : '')), 'class="form-control select" id="select_rack_id" placeholder="' . lang("select") . " " . "Warehouse First" . '" required="required" style="width:100%"')
                            // }
                            // else
                            // {
                            //     $whouse_rack[''] = "";
                            //     foreach ($racks as $rack) {
                            //         // $whouse_rack[$rack->id] = $rack->id;
                            //         $whouse_rack[$rack->id] = $rack->name;
                            //     }
                            //     // echo form_dropdown('warehouse_id', $whouse_rack, (isset($_POST['warehouse_id']) ? $_POST['warehouse_id'] : ($rack ? $rack->name : '')), 'class="form-control select" id="select_warehouse_id" placeholder="' . lang("select") . " " . lang("supplier") . '" required="required" style="width:100%"')
                            //     echo form_dropdown('rack_id', $whouse_rack, (isset($_POST['rack_id']) ? $_POST['rack_id'] : ($rack ? $rack->id : '')), 'class="form-control select" id="select_rack_id" placeholder="' . lang("select") . " " . "Rack" . '" required="required" style="width:100%"')
                            // }

                            $whouse_rack[''] = "";
                            foreach ($racks as $rack) {
                                // $whouse_rack[$rack->id] = $rack->id;

                                $warehouse_name = "";
                                $rack_name = "";

                                foreach ($warehouses as $wh) {
                                    if ($wh->id == $rack->warehouse_id) {
                                      $warehouse_name = $wh->name;
                                    }
                                }

                                // $whouse_rack[$rack->id] = $rack->name;
                                $whouse_rack[$rack->id] = $rack->name . " - " . $warehouse_name;

                                // if ($rack->warehouse_id == $_POST['warehouse_id']) {
                                //   $whouse_rack[$rack->id] = $rack->name;
                                // }
                            }
                            // echo form_dropdown('warehouse_id', $whouse_rack, (isset($_POST['warehouse_id']) ? $_POST['warehouse_id'] : ($rack ? $rack->name : '')), 'class="form-control select" id="select_warehouse_id" placeholder="' . lang("select") . " " . lang("supplier") . '" required="required" style="width:100%"')
                            echo form_dropdown('rack_id', $whouse_rack, (isset($_POST['rack_id']) ? $_POST['rack_id'] : ($rack ? $rack->name : '')), 'class="form-control select" id="select_rack_id" placeholder="' . lang("select") . " " . "Rack" . '" required="required" style="width:100%"')

                            ?>
                        </div>

                  </div>
                  </div>

                  <div class="row"></div>

                    <!-- *******************************************************
                      SELECT RECEIVING REPORT NO
                    ******************************************************** -->

                    <div class="col-md-6">
                    <div class="form-group">
                        <?php /* <label for="mcode" class="col-sm-4 control-label"><?= lang('product_code') ?> *</label> */ ?>

                        <br>
                        <label><?= "Receiving Report No" ?></label>
                        <?php /* <label for="mcode" class="col-sm-4 control-label"><?= "Product" ?> *</label> */ ?>

                          <div class="form-group">
                              <?php
                              $rec_rep[''] = "";
                              foreach ($receiving_reports as $receiving) {
                                  $rec_rep[$receiving->id] = $receiving->receiving_report_number;
                              }
                              // echo form_dropdown('receiving_id', $rec_rep, (isset($_POST['receiving_id']) ? $_POST['receiving_id'] : ($receiving ? $receiving->receiving_report_number : '')), 'class="form-control select" id="select_receiving_report_id" placeholder="' . lang("select") . " " . lang("supplier") . '" required="required" style="width:100%"')
                              echo form_dropdown('receiving_id', $rec_rep, (isset($_POST['receiving_id']) ? $_POST['receiving_id'] : ($receiving ? $receiving->receiving_report_number : '')), 'class="form-control select" id="select_receiving_report_id" placeholder="' . lang("select") . " " . "Receiving Report Number" . '" required="required" style="width:100%"')
                              ?>
                          </div>

                    </div>
                    </div>

                    <!-- *******************************************************
                      IMAGE
                    ******************************************************** -->

                    <div class="col-md-6">
                    <div class="form-group">
                        <?php /* <?= lang("product_image", "product_image") ?> */ ?>
                        <label><?= "Upload Image" ?></label>
                        <br>
                        <?= "(Optional) This field is not required." ?>
                        <input id="input_pallet_image" type="file" data-browse-label="<?= lang('browse'); ?>" name="input_pallet_image" data-show-upload="false"
                               data-show-preview="false" accept="image/*" class="form-control file">
                    </div>
                    </div>

                    <div class="row"></div>

                    <!-- *******************************************************
                      PALLET NOTES
                    ******************************************************** -->

                    <div class="col-md-12">
                    <div class="form-group all">
                        <?php /* <?= lang("product_details", "product_details") ?> */ ?>
                        <label><?= "Add a Note" ?></label>
                        <br>
                        <?= "\n(Optional) This field is not required." ?>
                        <?php /* <?= form_textarea('product_details', (isset($_POST['product_details']) ? $_POST['product_details'] : ($product ? $product->product_details : '')), 'class="form-control" id="details"'); ?> */ ?>
                        <?= form_textarea('pallet_note', (isset($_POST['pallet_note']) ? $_POST['pallet_note'] : ($product ? $product->pallet_note : '')), 'class="form-control" id="pallet_note"'); ?>
                    </div>
                    </div>

                    <div class="row"></div>
                    <hr>

                    <br>

                    <!-- *******************************************************
                      + BUTTON - ADD SUPPLY ORDER ITEM
                    ******************************************************** -->

                    <div class="col-md-12">
                    <div class="form-group all">
                        <div class="input-group wide-tip">
                            <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                                <i class="fa fa-2x fa-barcode addIcon"></i></a></div>
                            <?php echo form_input('add_item', '', 'readonly="true" class="form-control input-lg" id="add_item" placeholder="' . lang("add_product_to_order") . '"'); ?>
                            <?php if ($Owner || $Admin || $GP['products-add']) { ?>
                            <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                                <?php /*
                                <a href="#" id="addManually" class="tip" title="<?= lang('add_product_manually') ?>">
                                */ ?>
                                <a data-toggle="modal" data-target="#addPalletItemsModal" href="#" id="" class="tip" title="<?= lang('add_product_manually') ?>">
                                    <i class="fa fa-2x fa-plus-circle addIcon" id="addIcon"></i>
                                </a>
                            </div>
                            <?php } if ($Owner || $Admin || $GP['sales-add_gift_card']) { ?>
                            <?php } ?>
                        </div>
                    </div>
                    </div>

                    <!-- <div class="clearfix"></div> -->
                    <!-- <br> -->

                    <!-- *******************************************************
                      TABLE - PALLET ITEMS
                    ******************************************************** -->

                    <div class="col-md-12">
                        <div class="control-group table-group">
                            <label class="table-label"><?= /* lang("order_items"); */ "Pallet Items" ?> *</label>

                            <div class="controls table-controls">
                                <table id="palletItemsTable" class="table items table-striped table-bordered table-condensed table-hover sortable_table">
                                    <thead>
                                    <tr>

                                        <!-- ***********************************
                                          TABLE - COLUMN - ID
                                        ************************************ -->

                                        <th class="col-md-4" style="width: 1% !important;"></th>

                                        <!-- ***********************************
                                          TABLE - COLUMN - PRODUCT
                                        ************************************ -->

                                        <th class="col-md-4" style="width: 70% !important;">Product</th>

                                        <!-- ***********************************
                                          TABLE - COLUMN - QUANTITY
                                        ************************************ -->

                                        <th class="col-md-4" style="width: 20% !important;">Quantity</th>

                                        <!-- ***********************************
                                          TABLE - COLUMN - DELETE BUTTON
                                        ************************************ -->

                                        <th style="width: 8% !important; text-align: center;">
                                            <i class="fa fa-trash-o" style="opacity:0.5; filter:alpha(opacity=50);"></i>
                                        </th>

                                        <!-- ***********************************
                                          TABLE - COLUMN - JUST FOR PATTERN
                                        ************************************ -->

                                        <th class="col-md-4" style="width: 1% !important;"></th>

                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot></tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row"></div>
                    <hr>

                    <br>

                    <!-- *******************************************************
                      BUTTON - FORM SUBMIT
                    ******************************************************** -->

                    <div class="col-md-12">
                    <div class="form-group">
                        <!-- SEND SUPPLY ORDER - BUTTON -->
                        <?php /* echo form_submit('add_product', $this->lang->line("add_product"), 'class="btn btn-primary"'); */ ?>
                        <?php echo form_submit('add_product', "Reset", 'class="btn btn-danger" id="supply_order_items-reset_button"'); ?>
                        <?php echo form_submit('add_product', "Edit Pallet", 'class="btn btn-primary"'); ?>
                    </div>
                    </div>

                </div>

                <?= form_close(); ?>

            </div>

        </div>
    </div>
</div>

<!-- ***************************************************************************

  MODAL - FORM - ADD SUPPLY ORDER ITEM

**************************************************************************** -->

<div class="modal" id="addPalletItemsModal" tabindex="-1" role="dialog" aria-labelledby="mModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                            class="fa fa-2x">&times;</i></span><span class="sr-only"><?=lang('close');?></span></button>
                <?php /* <h4 class="modal-title" id="mModalLabel"><?= lang('add_product_manually') ?></h4> */ ?>
                <h4 class="modal-title" id="mModalLabel"><?= "Add Pallet Item" ?></h4>
            </div>
            <div class="modal-body" id="pr_popover_content">

                <!-- ***********************************************************
                  FORM
                ************************************************************ -->

                <form class="form-horizontal" role="form">

                    <!-- *******************************************************
                      PRODUCT - INPUT
                    ******************************************************** -->

                    <div class="form-group">
                        <?php /* <label for="mcode" class="col-sm-4 control-label"><?= lang('product_code') ?> *</label> */ ?>
                        <label for="mcode" class="col-sm-4 control-label"><?= "Product" ?> *</label>

                        <div class="col-sm-8">

                          <div class="form-group">
                              <?php
                              $prod[''] = "";
                              foreach ($products as $product) {
                                  $prod[$product->id] = $product->name;
                              }
                              echo form_dropdown('product', $prod, (isset($_POST['product']) ? $_POST['product'] : ($product ? $product->product_id : '')), 'class="form-control select" id="supply_order_product_id" placeholder="' . lang("select") . " " . lang("product") . '" required="required" style="width:100%"')
                              ?>
                          </div>

                        </div>
                    </div>

                    <!-- *******************************************************
                      QUANTITY - INPUT
                    ******************************************************** -->

                    <div class="form-group">
                        <label for="mquantity" class="col-sm-4 control-label"><?= lang('quantity') ?> *</label>
                        <div class="col-sm-8">
                            <input type="number" min="0" class="form-control" id="supply_order_product_qty" required>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="addPalletItemButton"><?= lang('submit') ?></button>
            </div>
        </div>
    </div>
</div>

<!-- ***************************************************************************

  LOGIC HANDLERS - ADD SUPPLY ORDER ITEMS

**************************************************************************** -->

<script type="text/javascript">
    $(document).ready(function () {

        // *********************************************************************
        // CLEAR ALL localStorage ITEMS
        // *********************************************************************

        // localStorage.clear();

        function clearLocalStorage() {
          localStorage.clear();
        }
        window.clearLocalStorage =  clearLocalStorage;

        function getProductName(productID) {
            // WANTED TO DO IT THIS WAY, BUT AJAX IS ASYNC AND SINCE IT TAKES
            // A WHILE TO LOAD, THE OTHER CODE RUNS FIRST AND PRODUCT ID IS UNDEFINED
            // CAUSE THIS IS LOADING...

            // console.log("before AJAX");
            // $.ajax({
            //     url : '<?= admin_url() ?>' + 'products/getProductName/' + productID,
            //     type : 'GET',
            //     data : {},
            //     dataType:'json',
            //     success : function(data) {
            //         // let nextNo = parseInt(data);
            //         // $('#bol_form_input-bol_no').val(nextNo);
            //         console.log("data");
            //         console.log(data);
            //         return data;
            //     },
            //     error : function(request, error)
            //     {
            //         // alert("Request: " + JSON.stringify(request));
            //         alert("Error: " + JSON.stringify(error));
            //     }
            // });
            // console.log("after AJAX");

            var products = <?php echo json_encode($products); ?>;

        }

        function loadPalletItems() {
          localStorage.clear();

          // clear palletItems, just in case there were few
          var palletItems = <?php echo json_encode($palletItems); ?>;
          console.log("palletItems are:");
          console.log(palletItems);

          palletItems.map((palletItem, index) => {
            console.log("mapping index: " + index);
            var createdAt = new Date().getTime();
            var supply_order_product_id = palletItem.product_id;
            var supply_order_product_qty = palletItem.quantity;
            var rowCount = index + 1;

            var productsList = <?php echo json_encode($products); ?>;
            let prodName = "";
            productsList.map(prod => {
              if (prod.id.toString() === supply_order_product_id.toString()) {
                prodName = prod.name;
              }
            })

            var orderItem = {
                item_id: rowCount,
                createdAt,
                row: {
                  row_no: rowCount,
                  product_id: supply_order_product_id,
                  product_name: prodName,
                  product_quantity: supply_order_product_qty,
                },
                options: false,
            };

            // load orderitems...
            var currentOrderItems = JSON.parse(localStorage.getItem('form-add_supply_order-items'));

            let updatedOrderItems = [];

            // if is nul, create array and push orderItem
            if (currentOrderItems === null || currentOrderItems === undefined) {
                updatedOrderItems.push(orderItem);
            } else {
                updatedOrderItems.push(...currentOrderItems);
                updatedOrderItems.push(orderItem);
            }

            localStorage.setItem('form-add_supply_order-items', JSON.stringify(updatedOrderItems));
            loadSupplyOrderItems();
          });

          // var testOrderItems = JSON.parse(localStorage.getItem('form-add_supply_order-items'));
          // console.log("test order items");
          // console.log(testOrderItems);

        }
        window.loadPalletItems =  loadPalletItems;
        loadPalletItems();

        // *********************************************************************
        // CLEAR THIS FORM VALUES FROM localStorage
        // *********************************************************************

        function clearThisFormFronLocalStorage() {
          localStorage.removeItem('form-add_supply_order-supplier');
          localStorage.removeItem('form-add_supply_order-message_to_supplier');
          localStorage.removeItem('form-add_supply_order-message_to_receiving');
          localStorage.removeItem('form-add_supply_order-image');
          localStorage.removeItem('form-add_supply_order-image_gallery');
          localStorage.removeItem('form-add_supply_order-attachment');
          localStorage.removeItem('form-add_supply_order-items');
          localStorage.removeItem('form-add_supply_order-items_rows_count');
        }
        window.clearThisFormFronLocalStorage =  clearThisFormFronLocalStorage;

        // *********************************************************************
        // CHECK IF localStorage VALUES FOR INPUTS EXIST
        // *********************************************************************

        if (localStorage.getItem('form-add_supply_order-supplier')) {
            // // localStorage.removeItem('slwarehouse');
            // // SET INPUT VALUE TO THE ONE ON localStorage
            // console.log("Found value for supplier is: " + localStorage.getItem('form-add_supply_order-supplier'));
            // $('#supplier').val(localStorage.getItem('form-add_supply_order-supplier'));
            // $('#supplier').text(localStorage.getItem('form-add_supply_order-supplier')).change();
            // $('#supplier').select(localStorage.getItem('form-add_supply_order-supplier'));
            // $('#supplier').filter(localStorage.getItem('form-add_supply_order-supplier'));
            // // $('#supplier').selected(localStorage.getItem('form-add_supply_order-supplier'));
        }

        if (localStorage.getItem('form-add_supply_order-message_to_supplier')) {
            // // localStorage.removeItem('slwarehouse');
            // // SET INPUT VALUE TO THE ONE ON localStorage
            // let val = $('#form-add_supply_order-message_to_supplier').val(localStorage.getItem('form-add_supply_order-message_to_supplier'));
        }

        if (localStorage.getItem('supply_order_document')) {
            let val = $('#supply_order_document').val(localStorage.getItem('form-add_supply_order-attachment'));
        }

        // *********************************************************************
        // INIT localStorage ITEM 'form-add_supply_order-items'
        // *********************************************************************

        var initSupplyOrderItems = localStorage.getItem('form-add_supply_order-items');
        if (initSupplyOrderItems === null || initSupplyOrderItems === undefined || initSupplyOrderItems.constructor.toString().indexOf("Array") != -1) {
          initSupplyOrderItems = [];
          localStorage.setItem('form-add_supply_order-items', JSON.stringify(initSupplyOrderItems));
        }

        // *********************************************************************
        // POPULATE TABLE - SUPPLY ORDER ITEMS
        // *********************************************************************

        loadSupplyOrderItems();

        function loadSupplyOrderItems() {

            var currentOrderItems = localStorage.getItem('form-add_supply_order-items');

            if (currentOrderItems && currentOrderItems.length > 0) {

              $('#palletItemsTable tbody').empty();

              currentOrderItems = JSON.parse(localStorage.getItem('form-add_supply_order-items'));

              currentOrderItems.map(item => {

                var item_id = item.item_id;
                var row_no = item.row.row_no;

                var product_id = item.row.product_id;
                var product_name = item.row.product_name;
                var product_quantity = item.row.product_quantity;

                // later
                // on qty input:
                // remove disable and be able to edit that input
                // add onChange prop and create and call function updateItemQty(rowNo, qty)
                // takes rowNo and qty, loads the localStorage for 'supplyOrderItems'
                // maps thru it and find the object where rowNo is equal to the one passed to this updateItemQty(rowNo, qty) functino
                // then updates the qty,
                // saves new data on new array and save that array back to the localStorage

                var newTr = $('<tr id="row_' + row_no + '" class="row_' + item_id + '" data-item-id="' + item_id + '"></tr>');
                var tr_html =
                        `<td align="center">
                            <input name="product_id[]" style="text-align: center; width: 100%;" readonly type="hidden" class="rid" value="${product_id}">
                        </td>`;
                    tr_html +=
                        `<td align="center">
                            <input name="product_name[]" style="text-align: center; width: 100%;" readonly type="text" class="rid" value="${product_name}">
                        </td>`;
                    tr_html +=
                        `<td align="center">
                            <input name="product_quantity[]" style="text-align: center; width: 100%;" readonly type="number" class="rid" value="${product_quantity}">
                        </td>`;
                    tr_html += `
                        <td class="text-center">
                            <i
                                id="'${row_no}'"
                                class="fa fa-times tip pointer sldel"
                                title="Remove"
                                style="cursor:pointer;"
                                onclick='removeSupplyOrderItem(${row_no})'
                            >
                            </i>
                        </td>
                    `;
                    tr_html +=
                        `<td align="center">
                            <input style="text-align: center; width: 100%;" readonly type="hidden" class="rid">
                        </td>`;

                newTr.html(tr_html);
                newTr.prependTo('#palletItemsTable');

              })
            }

        }

        // *********************************************************************
        // ADD ITEM TO ORDER - MODAL FORM SUBMIT BUTTON
        // *********************************************************************

        $(document).on('click', '#addPalletItemButton', function(e) {
            event.preventDefault();

            var createdAt = new Date().getTime();
            var supply_order_product_id = $('#supply_order_product_id').val();
            var supply_order_product_qty = $('#supply_order_product_qty').val();

            if (supply_order_product_id && supply_order_product_qty) {

                var currentOrderItems2 = JSON.parse(localStorage.getItem('form-add_supply_order-items'));

                var rowCount = localStorage.getItem('form-add_supply_order-items_rows_count');
                if (rowCount === null || rowCount === undefined) {
                  rowCount = 1;
                } else {
                  rowCount++;
                }
                localStorage.setItem('form-add_supply_order-items_rows_count', rowCount);

                var productsList = <?php echo json_encode($products); ?>;

                let prodName = "";
                productsList.map(prod => {
                  if (prod.id.toString() === supply_order_product_id.toString()) {
                    prodName = prod.name;
                  }
                })

                var orderItem = {
                    item_id: rowCount,
                    createdAt,
                    row: {
                      row_no: rowCount,
                      product_id: supply_order_product_id,
                      product_name: prodName,
                      product_quantity: supply_order_product_qty,
                    },
                    options: false,
                };

                let updatedOrderItems = [];
                updatedOrderItems.push(...currentOrderItems2);
                updatedOrderItems.push(orderItem);

                localStorage.setItem('form-add_supply_order-items', JSON.stringify(updatedOrderItems));
                loadSupplyOrderItems();
            }

            $('#addPalletItemsModal').modal('hide');

            $('#supply_order_product_qty').val('');
            return false;
        });

        // *********************************************************************
        // REMOVE ORDER ITEM - REMOVE ROW FROM TABLE AND OBJ FROM localStorage
        // *********************************************************************

        function removeSupplyOrderItem(rowNo) {
          // console.log("REMOVING ROW: " + rowNo);
          var orderItems = JSON.parse(localStorage.getItem('form-add_supply_order-items'));
          orderItems.map(item => {
            if (rowNo.toString() === item.row.row_no.toString()) {
                // HELPER FUNCTION TO REMOVE OBJECT FROM ARRAY BY OBJECT ATTRIBUTE
                var removeByAttr = function(arr, attr, value){
                    var i = arr.length;
                    while(i--){
                       if( arr[i]
                           && arr[i].hasOwnProperty(attr)
                           && (arguments.length > 2 && arr[i][attr] === value ) ) {

                           arr.splice(i,1);

                       }
                    }
                    return arr;
                }
                // HELPER FUNCTION CALL
                removeByAttr(orderItems, 'item_id', rowNo);
                localStorage.setItem('form-add_supply_order-items', JSON.stringify(orderItems));
                let trID = `#row_${rowNo}`;
                $(trID).remove();
            }
          })
        }
        window.removeSupplyOrderItem =  removeSupplyOrderItem;

        // *********************************************************************
        // RESET ORDER
        // *********************************************************************

        $(document).on('click', '#supply_order_items-reset_button', function(e) {
          event.preventDefault();
          localStorage.clear();
          location.reload();
        });

        //

        $(document).on('change', '#supplier', function(e) {

          let val = $('#form-add_supply_order-supplier').val();

          console.log($(this).val());
          console.log("chaing");

          // localStorage.setItem('form-add_supply_order-supplier', JSON.stringify(val));
          localStorage.setItem('form-add_supply_order-supplier', val);

        });

        // // $(document).on('change', '#form-add_supply_order-message_to_supplier', function(e) {
        // $(document).on('change', '#supplier', function(e) {
        //   // console.log($(this).val());
        //   // console.log("chaing");
        //
        //   // let val = $('#supplier').val();
        //   let val = $(this).val();
        //
        //   // // localStorage.setItem('form-add_supply_order-supplier', JSON.stringify(val));
        //   localStorage.setItem('form-add_supply_order-supplier', val);
        //
        // });

        // $(document).on('change', '#form-add_supply_order-message_to_supplier', function(e) {
        $(document).on('input', '#pallet_note', function(e) {
          // console.log($(this).val());
          console.log("chaing");
          // let val = $('#form-add_supply_order-message_to_supplier').val();
          //
          // // localStorage.setItem('form-add_supply_order-message_to_supplier', JSON.stringify(val));
          // localStorage.setItem('form-add_supply_order-message_to_supplier', val);
        });

        $(document).on('change', '#supplier', function(e) {
          console.log("supplier");
        });
        $(document).on('hover', '#msg_to_supplier', function(e) {
          console.log("msg_to_supplier");
        });
        $(document).on('hover', '#msg_to_receiving', function(e) {
          console.log("msg_to_receiving");
        });
        $(document).on('change', '#pallet_image', function(e) {
          console.log("pallet_image");
          // localStorage.setItem('pallet_image', JSON.stringify(val));
          localStorage.setItem('pallet_image', val);
        });
        $(document).on('change', '#pallet_images', function(e) {
          console.log("pallet_images");
          // localStorage.setItem('pallet_images', JSON.stringify(val));
          localStorage.setItem('pallet_images', val);
        });
        $(document).on('change', '#supply_order_document', function(e) {
          console.log("supply_order_document");
          // localStorage.setItem('supply_order_document', JSON.stringify(val));
          localStorage.setItem('form-add_supply_order-attachment', val);
        });

        // $('#form-add_supply_order-supplier').on('change', function(e) {
        //   // alert($(this).val());
        //   console.log($(this).val());
        //   let val = $('#form-add_supply_order-supplier').val();
        //   // localStorage.setItem('form-add_supply_order-supplier', JSON.stringify(val));
        //   localStorage.setItem('form-add_supply_order-supplier', val);
        // });
        //
        // // $('#form-add_supply_order-message_to_supplier').on('change', function(e) {
        // $('#pallet_note').on('change', function(e) {
        //   // alert($(this).val());
        //   console.log($(this).val());
        //   console.log("chaing");
        //   let val = $('#form-add_supply_order-message_to_supplier').val();
        //   // localStorage.setItem('form-add_supply_order-message_to_supplier', JSON.stringify(val));
        //   localStorage.setItem('form-add_supply_order-message_to_supplier', val);
        // });

        // *********************************************************************
        // *********************************************************************
        // *********************************************************************

    });
</script>
