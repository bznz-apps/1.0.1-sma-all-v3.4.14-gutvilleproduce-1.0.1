<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script type="text/javascript">
    $(document).ready(function () {

        // Start inputs with empty values here
        // $('#').val("");

        // Populate text input with a single value
        $.ajax({
            url : '<?= admin_url() ?>' + 'shipping/getNextPickUpOrderReportNo',
            type : 'GET',
            data : {},
            dataType:'json',
            success : function(data) {
                let nextNo = parseInt(data);
                $('#pick_up_order_form_input-pick_up_order_no').val(nextNo);
            },
            error : function(request, error)
            {
                // alert("Request: " + JSON.stringify(request));
                alert("Error: " + JSON.stringify(error));
            }
        });

        // ON EVERY FORM INPUT CHANGE, SAVE NEW VALUES TO localStorage

        $(document).on('change', '#pick_up_order_form_input-pick_up_order_no', function(e) {
        });

    });
</script>

<script>

    // Default DataTables Code, Leave as is... Starts here --->

    var oTable;
    $(document).ready(function () {

      console.log("ready!");

      $(document).on('change', '#pick_up_order_sale_id', function(e) {
              let sale_id = $(this).val();

              console.log("changed!");
              console.log(sale_id);

              <?php
                  $urlParam = $_POST['sale_id'];
                  // $urlParam = 3;
              ?>

              oTable = $('#ViewSaleItemsInPickUpTable').dataTable({
                  "aaSorting": [[2, "asc"], [3, "asc"]],
                  "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
                  "iDisplayLength": <?= $Settings->rows_per_page ?>,
                  'bProcessing': true, 'bServerSide': true,
                  <?php /*
                  'sAjaxSource': '<?= admin_url('suppliers/getSupplyOrdersLogic'.($warehouse_id ? '/'.$warehouse_id : '').($supplier ? '?supplier='.$supplier->id : '')) ?>',
                  */
                  ?>
                  // 'sAjaxSource': '<?= /* admin_url('sales/handleGetSaleItems_logic' . ($urlParam ? '/' . $urlParam : '')) */ ""?>',
                  'sAjaxSource': '<?= admin_url() ?>' + 'sales/handleGetSaleItems_logic' + (sale_id ? '/' + sale_id : ''),
                  'fnServerData': function (sSource, aoData, fnCallback) {
                    console.log(aoData);
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
                  "bDestroy" : true, // This is needed to make sure we can load a new table if some value changes and we need to pull off new items from that id value that changed...

          // Default DataTables Code, Leave as is... Ends here <---

                  // DATATABE COLUMN OPTIONS
                  // we set option to element 0, or the first column, so that i can be checkboxes

                  "aoColumns": [
                      {"bSortable": false, "mRender": checkbox},
                      null, // product column
                      null, // quantity column
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
                  {column_number: 1, filter_default_label: "[Product]", filter_type: "text", data: []},
                  {column_number: 2, filter_default_label: "[Quantity]", filter_type: "text", data: []},

              ], "footer");

      });

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
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i>Add Pick Up Order</h2>
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
                echo admin_form_open_multipart("shipping/handleAddPickUpOrder_logic", $attrib)
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
                          // echo form_input('supplier', (isset($_POST['supplier']) ? $_POST['supplier'] : ''), 'class="form-control ' . ($product ? '' : 'suppliers') . '" id="' . "form-add_pick_up_order-supplier" . '" placeholder="' . lang("select") . ' ' . lang("supplier") . '" style="width:100%;"');
                          // id="form-add_pick_up_order-supplier"
                      */ ?>
                  <?php /*
                  </div>
                  */ ?>

                  <!-- ***************************************************
                  *  REPORT NO
                  **************************************************** -->

                  <div class="col-md-4">
                  <div class="form-group all">
                      <div class="form-group">
                          <label> <?= /* lang("reference_no", "slref"); */ "Pick Up Order No *" ?> </label>
                          <?php echo form_input('pick_up_order_no', (isset($_POST['pick_up_order_no']) ? $_POST['pick_up_order_no'] : ""), 'class="form-control input-tip" id="pick_up_order_form_input-pick_up_order_no"'); ?>
                      </div>
                  </div>
                  </div>

                  <div class="row"></div>
                  <hr>

                    <!-- *******************************************************
                      SELECT SALE
                    ******************************************************** -->

                    <div class="col-md-4">
                        <?php /* <label for="mcode" class="col-sm-4 control-label"><?= lang('product_code') ?> *</label> */ ?>

                        <label><?= "Sale No" ?></label>
                        <?php /* <label for="mcode" class="col-sm-4 control-label"><?= "Product" ?> *</label> */ ?>

                          <div class="form-group">
                              <?php
                              $sl[''] = "";
                              foreach ($sales as $sale) {
                                  $sl[$sale->id] = $sale->sale_no;
                              }
                              // echo form_dropdown('sale_id', $sl, (isset($_POST['sale_id']) ? $_POST['sale_id'] : ($sale ? $sale->sale_id : '')), 'class="form-control select" id="pick_up_order_sale_id" placeholder="' . lang("select") . " " . lang("sale") . '" required="required" style="width:100%"')
                              echo form_dropdown('sale_id', $sl, (isset($_POST['sale_id']) ? $_POST['sale_id'] : ($sale ? $sale->sale_id : '')), 'class="form-control select" id="pick_up_order_sale_id" placeholder="Select Sale No" required="required" style="width:100%"')
                              ?>
                          </div>

                    </div>

                    <!-- ***************************************************
                    *  SOLD TO
                    **************************************************** -->

                    <div class="col-md-4">
                        <div class="form-group">
                            <label> <?= /* lang("reference_no", "slref"); */ "Sold To" ?> </label>
                            <?php echo form_input('sold_to', (isset($_POST['sold_to']) ? $_POST['sold_to'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                        </div>
                    </div>

                    <!-- ***************************************************
                    *  SHIP TO
                    **************************************************** -->

                    <div class="col-md-4">
                        <div class="form-group">
                            <label> <?= /* lang("reference_no", "slref"); */ "Ship To" ?> </label>
                            <?php echo form_input('ship_to', (isset($_POST['ship_to']) ? $_POST['ship_to'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                        </div>
                    </div>

                    <div class="row"></div>
                    <hr>

                    <!-- ***************************************************
                    *  ORDER NO
                    **************************************************** -->

                    <div class="col-md-3">
                        <div class="form-group">
                            <label> <?= /* lang("reference_no", "slref"); */ "Order No" ?> </label>
                            <?php echo form_input('order_no', (isset($_POST['order_no']) ? $_POST['order_no'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                        </div>
                    </div>

                    <!-- ***************************************************
                    *  SALES LOAD
                    **************************************************** -->

                    <div class="col-md-3">
                        <div class="form-group">
                            <label> <?= /* lang("reference_no", "slref"); */ "Sales Load" ?> </label>
                            <?php echo form_input('sales_load', (isset($_POST['sales_load']) ? $_POST['sales_load'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                        </div>
                    </div>

                    <!-- ***************************************************
                    *  ORDER DATE
                    **************************************************** -->

                    <?php /* if ($Owner || $Admin) { */ "" ?>
                        <?php /* <div class="col-md-4"> */ ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                 <label><?= /* lang("date", "sldate"); */ "Order" ?></label>
                                <?php echo form_input('order_date', (isset($_POST['order_date']) ? $_POST['order_date'] : ""), 'class="form-control input-tip datetime" id="" autocomplete="off"'); ?>
                            </div>
                        </div>
                    <?php /* } */ "" ?>

                    <!-- ***************************************************
                    *  SHIP DATE
                    **************************************************** -->

                    <?php /* if ($Owner || $Admin) { */ "" ?>
                        <?php /* <div class="col-md-4"> */ ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                 <label><?= /* lang("date", "sldate"); */ "Ship" ?></label>
                                <?php echo form_input('ship_date', (isset($_POST['ship_date']) ? $_POST['ship_date'] : ""), 'class="form-control input-tip datetime" id="" autocomplete="off"'); ?>
                            </div>
                        </div>
                    <?php /* } */ "" ?>

                    <div class="row"></div>

                    <!-- ***************************************************
                    *  PAY TERMS
                    **************************************************** -->

                    <div class="col-md-3">
                        <div class="form-group">
                            <label> <?= /* lang("reference_no", "slref"); */ "Pay Terms" ?> </label>
                            <?php echo form_input('pay_terms', (isset($_POST['pay_terms']) ? $_POST['pay_terms'] : $slnumber), 'class="form-control input-tip" id="pay_terms"'); ?>
                        </div>
                    </div>

                    <!-- ***************************************************
                    *  SALE TERMS
                    **************************************************** -->

                    <div class="col-md-3">
                        <div class="form-group">
                            <label> <?= /* lang("reference_no", "slref"); */ "Sale Terms" ?> </label>
                            <?php echo form_input('sale_terms', (isset($_POST['sale_terms']) ? $_POST['sale_terms'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                        </div>
                    </div>

                    <div class="row"></div>
                    <hr>

                    <!-- ***************************************************
                    *  CUST PO
                    **************************************************** -->

                    <div class="col-md-4">
                        <div class="form-group">
                            <label> <?= /* lang("reference_no", "slref"); */ "Cust PO" ?> </label>
                            <?php echo form_input('customer_PO', (isset($_POST['customer_PO']) ? $_POST['customer_PO'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                        </div>
                    </div>

                    <!-- ***************************************************
                    *  DELIVERY
                    **************************************************** -->

                    <div class="col-md-4">
                        <div class="form-group">
                            <label> <?= /* lang("reference_no", "slref"); */ "Delivery" ?> </label>
                            <?php echo form_input('delivery', (isset($_POST['delivery']) ? $_POST['delivery'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                        </div>
                    </div>

                    <!-- ***************************************************
                    *  VIA
                    **************************************************** -->

                    <div class="col-md-4">
                        <div class="form-group">
                            <label> <?= /* lang("reference_no", "slref"); */ "Via" ?> </label>
                            <?php echo form_input('via', (isset($_POST['via']) ? $_POST['via'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                        </div>
                    </div>

                    <!-- ***************************************************
                    *  SALESPERSON
                    **************************************************** -->

                    <div class="col-md-4">
                        <div class="form-group">
                            <label> <?= /* lang("reference_no", "slref"); */ "Salesperson" ?> </label>
                            <?php echo form_input('salesperson', (isset($_POST['salesperson']) ? $_POST['salesperson'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                        </div>
                    </div>

                    <!-- ***************************************************
                    *  CARRIER
                    **************************************************** -->

                    <div class="col-md-4">
                        <div class="form-group">
                            <label> <?= /* lang("reference_no", "slref"); */ "Carrier" ?> </label>
                            <?php echo form_input('carrier', (isset($_POST['carrier']) ? $_POST['carrier'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                        </div>
                    </div>

                    <!-- ***************************************************
                    *  TRAILER LIC
                    **************************************************** -->

                    <div class="col-md-4">
                        <div class="form-group">
                            <label> <?= /* lang("reference_no", "slref"); */ "Trailer Lic" ?> </label>
                            <?php echo form_input('trailer_lic', (isset($_POST['trailer_lic']) ? $_POST['trailer_lic'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                        </div>
                    </div>

                    <!-- ***************************************************
                    *  BROKER
                    **************************************************** -->

                    <div class="col-md-4">
                        <div class="form-group">
                            <label> <?= /* lang("reference_no", "slref"); */ "Broker" ?> </label>
                            <?php echo form_input('broker', (isset($_POST['broker']) ? $_POST['broker'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                        </div>
                    </div>

                    <!-- ***************************************************
                    *  ST
                    **************************************************** -->

                    <div class="col-md-4">
                        <div class="form-group">
                            <label> <?= /* lang("reference_no", "slref"); */ "St" ?> </label>
                            <?php echo form_input('state', (isset($_POST['state']) ? $_POST['state'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                        </div>
                    </div>

                    <div class="row"></div>

                    <!-- ***************************************************
                    *  QUANTITY
                    **************************************************** -->

                    <div class="col-md-4">
                        <div class="form-group">
                            <label> <?= /* lang("reference_no", "slref"); */ "Quantity" ?> </label>
                            <?php echo form_input('qty', (isset($_POST['qty']) ? $_POST['qty'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                        </div>
                    </div>

                    <!-- ***************************************************
                    *  PALLETS
                    **************************************************** -->

                    <div class="col-md-4">
                        <div class="form-group">
                            <label> <?= /* lang("reference_no", "slref"); */ "Pallets" ?> </label>
                            <?php echo form_input('pallets', (isset($_POST['pallets']) ? $_POST['pallets'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                        </div>
                    </div>

                    <!-- ***************************************************
                    *  WEIGHT
                    **************************************************** -->

                    <div class="col-md-4">
                        <div class="form-group">
                            <label> <?= /* lang("reference_no", "slref"); */ "Weight" ?> </label>
                            <?php echo form_input('weight', (isset($_POST['weight']) ? $_POST['weight'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                        </div>
                    </div>

                    <div class="row"></div>
                    <hr>

                    <!--********************************************************
                    * SALE ITEMS
                    *********************************************************-->

                    <!-- TABLE CONTENT - ITEMS LIST -->

                    <div class="box-content">
                        <div class="row">
                            <div class="col-md-12">
                                <?php /* <p class="introtext"><?= lang('list_results'); ?></p> */ ?>
                                <label>Sale Items</label>

                                <div class="table-responsive">
                                    <table id="ViewSaleItemsInPickUpTable" class="table table-bordered table-condensed table-hover table-striped">

                                      <!-- Table Header Row -->

                                        <thead>
                                        <tr class="primary">

                                          <th style="min-width:30px; max-width:30px; width: 30px; text-align: center;">
                                              <input class="checkbox checkth" type="checkbox" name="check"/>
                                          </th>
                                          <th style="width:50%; text-align: center;">Product</th>
                                          <th style="width:50%; text-align: center;">Quantity</th>

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

                    <!-- <br> -->
                    <div class="row"></div>
                    <hr>

                    <br>

                    <!-- <label>Add Pick Up Order Items</label>
                    <br>
                    <div class="row"></div> -->

                    <!-- *******************************************************
                      + BUTTON - ADD PICK UP ORDER ITEM
                    ******************************************************** -->

                    <div class="col-md-4">
                    <div class="form-group all">
                        <div class="input-group wide-tip">
                            <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                                <i class="fa fa-2x fa-barcode addIcon"></i></a></div>
                            <?php echo form_input('add_item', '', 'readonly="true" class="form-control input-lg" id="add_item" placeholder="Add items to this pick up order"'); ?>
                            <?php if ($Owner || $Admin || $GP['products-add']) { ?>
                            <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                                <?php /*
                                <a href="#" id="addManually" class="tip" title="<?= lang('add_product_manually') ?>">
                                */ ?>
                                <a data-toggle="modal" data-target="#addPickUpOrderProductModal" href="#" id="" class="tip" title="<?= lang('add_product_manually') ?>">
                                    <i class="fa fa-2x fa-plus-circle addIcon" id="addIcon"></i>
                                </a>
                            </div>
                            <?php } if ($Owner || $Admin || $GP['sales-add_gift_card']) { ?>
                            <?php } ?>
                        </div>
                    </div>
                    </div>

                    <br>

                    <!-- *******************************************************
                      TABLE - PICK UP ORDER ITEMS
                    ******************************************************** -->

                    <div class="col-md-12">
                        <div class="control-group table-group">
                            <label class="table-label"><?= /* lang("order_items"); */ "Pick Up Order Items" ?> *</label>

                            <div class="controls table-controls">
                                <table id="Pick_Up_OrderTable" class="table items table-striped table-bordered table-condensed table-hover sortable_table">
                                    <thead>
                                    <tr>

                                        <!-- ***********************************
                                          TABLE - COLUMN - ID
                                        ************************************ -->

                                        <th class="col-md-4" style="width: 1% !important;"></th>

                                        <!-- ***********************************
                                          TABLE - COLUMN - PRODUCT
                                        ************************************ -->

                                        <th class="col-md-4" style="width: 49% !important;">Item Description</th>

                                        <!-- ***********************************
                                          TABLE - COLUMN - QUANTITY
                                        ************************************ -->

                                        <th class="col-md-4" style="width: 25% !important;">Quantity</th>

                                        <!-- ***********************************
                                          TABLE - COLUMN - PRICE
                                        ************************************ -->

                                        <th class="col-md-4" style="width: 25% !important;">Price</th>

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
                        <!-- SEND PICK UP ORDER - BUTTON -->
                        <?php /* echo form_submit('add_product', $this->lang->line("add_product"), 'class="btn btn-primary"'); */ ?>
                        <?php echo form_submit('add_product', "Reset", 'class="btn btn-danger" id="pick_up_order_items-reset_button"'); ?>
                        <?php echo form_submit('add_product', "Add Pick Up Order", 'class="btn btn-primary"'); ?>
                    </div>
                    </div>

                </div>

                <?= form_close(); ?>

            </div>

        </div>
    </div>
</div>

<!-- ***************************************************************************

  MODAL - FORM - ADD PICK UP ORDER ITEM

**************************************************************************** -->

<div class="modal" id="addPickUpOrderProductModal" tabindex="-1" role="dialog" aria-labelledby="mModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                            class="fa fa-2x">&times;</i></span><span class="sr-only"><?=lang('close');?></span></button>
                <?php /* <h4 class="modal-title" id="mModalLabel"><?= lang('add_product_manually') ?></h4> */ ?>
                <h4 class="modal-title" id="mModalLabel"><?= "Add Pick Up Order Item" ?></h4>
            </div>
            <div class="modal-body" id="pr_popover_content">

                <!-- ***********************************************************
                  FORM
                ************************************************************ -->

                <form class="form-horizontal" role="form">

                    <!-- *******************************************************
                      ITEM DESCRIPTION - INPUT
                    ******************************************************** -->

                    <div class="form-group">
                        <label class="col-sm-4 control-label"><?= /* lang('quantity') */ "Item Description" ?> *</label>
                        <div class="col-sm-8">
                            <input type="number" min="0" class="form-control" id="pick_up_order_item_description" required>
                        </div>
                    </div>

                    <!-- *******************************************************
                      QUANTITY - INPUT
                    ******************************************************** -->

                    <div class="form-group">
                        <label for="mquantity" class="col-sm-4 control-label"><?= lang('quantity') ?> *</label>
                        <div class="col-sm-8">
                            <input type="number" min="0" class="form-control" id="pick_up_order_item_qty" required>
                        </div>
                    </div>

                    <!-- *******************************************************
                      PRICE - INPUT
                    ******************************************************** -->

                    <div class="form-group">
                        <label for="mquantity" class="col-sm-4 control-label"><?= /* lang('quantity') */ "Price" ?> *</label>
                        <div class="col-sm-8">
                            <input type="number" min="0" class="form-control" id="pick_up_order_item_price" required>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="addPickUpOrderProductButton"><?= lang('submit') ?></button>
            </div>
        </div>
    </div>
</div>

<!-- ***************************************************************************

  LOGIC HANDLERS - ADD PICK UP ORDER ITEMS

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

        // *********************************************************************
        // CLEAR THIS FORM VALUES FROM localStorage
        // *********************************************************************

        function clearThisFormFronLocalStorage() {
          localStorage.removeItem('form-add_pick_up_order-supplier');
          localStorage.removeItem('form-add_pick_up_order-message_to_supplier');
          localStorage.removeItem('form-add_pick_up_order-message_to_receiving');
          localStorage.removeItem('form-add_pick_up_order-image');
          localStorage.removeItem('form-add_pick_up_order-image_gallery');
          localStorage.removeItem('form-add_pick_up_order-attachment');
          localStorage.removeItem('form-add_pick_up_order-items');
          localStorage.removeItem('form-add_pick_up_order-items_rows_count');
        }
        window.clearThisFormFronLocalStorage =  clearThisFormFronLocalStorage;

        // *********************************************************************
        // CHECK IF localStorage VALUES FOR INPUTS EXIST
        // *********************************************************************

        if (localStorage.getItem('form-add_pick_up_order-supplier')) {
            // // localStorage.removeItem('slwarehouse');
            // // SET INPUT VALUE TO THE ONE ON localStorage
            // console.log("Found value for supplier is: " + localStorage.getItem('form-add_pick_up_order-supplier'));
            // $('#supplier').val(localStorage.getItem('form-add_pick_up_order-supplier'));
            // $('#supplier').text(localStorage.getItem('form-add_pick_up_order-supplier')).change();
            // $('#supplier').select(localStorage.getItem('form-add_pick_up_order-supplier'));
            // $('#supplier').filter(localStorage.getItem('form-add_pick_up_order-supplier'));
            // // $('#supplier').selected(localStorage.getItem('form-add_pick_up_order-supplier'));
        }

        if (localStorage.getItem('form-add_pick_up_order-message_to_supplier')) {
            // // localStorage.removeItem('slwarehouse');
            // // SET INPUT VALUE TO THE ONE ON localStorage
            // let val = $('#form-add_pick_up_order-message_to_supplier').val(localStorage.getItem('form-add_pick_up_order-message_to_supplier'));
        }

        if (localStorage.getItem('pick_up_order_document')) {
            let val = $('#pick_up_order_document').val(localStorage.getItem('form-add_pick_up_order-attachment'));
        }

        // *********************************************************************
        // INIT localStorage ITEM 'form-add_pick_up_order-items'
        // *********************************************************************

        var initPickUpOrderItems = localStorage.getItem('form-add_pick_up_order-items');
        if (initPickUpOrderItems === null || initPickUpOrderItems === undefined || initPickUpOrderItems.constructor.toString().indexOf("Array") != -1) {
          initPickUpOrderItems = [];
          localStorage.setItem('form-add_pick_up_order-items', JSON.stringify(initPickUpOrderItems));
        }

        // *********************************************************************
        // POPULATE TABLE - PICK UP ORDER ITEMS
        // *********************************************************************

        loadPickUpOrderItems();

        function loadPickUpOrderItems() {

            var currentOrderItems = localStorage.getItem('form-add_pick_up_order-items');

            if (currentOrderItems && currentOrderItems.length > 0) {

              $('#Pick_Up_OrderTable tbody').empty();

              currentOrderItems = JSON.parse(localStorage.getItem('form-add_pick_up_order-items'));

              currentOrderItems.map(item => {

                var item_id = item.item_id;
                var row_no = item.row.row_no;

                var product_id = item.row.product_id;
                var product_name = item.row.product_name;
                var product_quantity = item.row.product_quantity;
                var product_price = item.row.product_price;
                // var product_price = 1000;

                // later
                // on qty input:
                // remove disable and be able to edit that input
                // add onChange prop and create and call function updateItemQty(rowNo, qty)
                // takes rowNo and qty, loads the localStorage for 'pick_upOrderItems'
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
                    tr_html +=
                        `<td align="center">
                            <input name="product_price[]" style="text-align: center; width: 100%;" readonly type="number" class="rid" value="${product_price}">
                        </td>`;
                    tr_html += `
                        <td class="text-center">
                            <i
                                id="'${row_no}'"
                                class="fa fa-times tip pointer sldel"
                                title="Remove"
                                style="cursor:pointer;"
                                onclick='removePickUpOrderItem(${row_no})'
                            >
                            </i>
                        </td>
                    `;
                    tr_html +=
                        `<td align="center">
                            <input style="text-align: center; width: 100%;" readonly type="hidden" class="rid">
                        </td>`;

                newTr.html(tr_html);
                newTr.prependTo('#Pick_Up_OrderTable');

                console.log("Order Items after last added:");
                console.log(currentOrderItems);

              })
            }

        }

        // *********************************************************************
        // ADD ITEM TO ORDER - MODAL FORM SUBMIT BUTTON
        // *********************************************************************

        $(document).on('click', '#addPickUpOrderProductButton', function(e) {
            event.preventDefault();

            var createdAt = new Date().getTime();
            var pick_up_order_product_id = $('#pick_up_order_item_description').val();
            var pick_up_order_item_description = $('#pick_up_order_item_description').val();
            var pick_up_order_item_qty = $('#pick_up_order_item_qty').val();
            var pick_up_order_item_price = $('#pick_up_order_item_price').val();

            // console.log(pick_up_order_product_id);
            // console.log(pick_up_order_item_qty);
            // console.log(pick_up_order_item_price);

            if (pick_up_order_product_id && pick_up_order_item_qty && pick_up_order_item_price) {

                var currentOrderItems2 = JSON.parse(localStorage.getItem('form-add_pick_up_order-items'));
                // console.log(currentOrderItems2);

                var rowCount = localStorage.getItem('form-add_pick_up_order-items_rows_count');
                if (rowCount === null || rowCount === undefined) {
                  rowCount = 1;
                } else {
                  rowCount++;
                }
                localStorage.setItem('form-add_pick_up_order-items_rows_count', rowCount);

                var productsList = <?php echo json_encode($products); ?>;

                // let prodName = "";
                // productsList.map(prod => {
                //   if (prod.id.toString() === pick_up_order_product_id.toString()) {
                //     prodName = prod.name;
                //   }
                // })

                var orderItem = {
                    item_id: rowCount,
                    createdAt,
                    row: {
                      row_no: rowCount,
                      product_id: pick_up_order_product_id,
                      product_name: pick_up_order_item_description,
                      product_quantity: pick_up_order_item_qty,
                      product_price: pick_up_order_item_price,
                    },
                    options: false,
                };

                let updatedOrderItems = [];
                updatedOrderItems.push(...currentOrderItems2);
                updatedOrderItems.push(orderItem);

                localStorage.setItem('form-add_pick_up_order-items', JSON.stringify(updatedOrderItems));
                loadPickUpOrderItems();
            }

            $('#addPickUpOrderProductModal').modal('hide');

            $('#pick_up_order_item_qty').val('');
            return false;
        });

        // *********************************************************************
        // REMOVE ORDER ITEM - REMOVE ROW FROM TABLE AND OBJ FROM localStorage
        // *********************************************************************

        function removePickUpOrderItem(rowNo) {
          // console.log("REMOVING ROW: " + rowNo);
          var orderItems = JSON.parse(localStorage.getItem('form-add_pick_up_order-items'));
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
                localStorage.setItem('form-add_pick_up_order-items', JSON.stringify(orderItems));
                let trID = `#row_${rowNo}`;
                $(trID).remove();
            }
          })
        }
        window.removePickUpOrderItem =  removePickUpOrderItem;

        // *********************************************************************
        // RESET ORDER
        // *********************************************************************

        $(document).on('click', '#pick_up_order_items-reset_button', function(e) {
          event.preventDefault();
          localStorage.clear();
          location.reload();
        });

        //

        $(document).on('change', '#supplier', function(e) {

          let val = $('#form-add_pick_up_order-supplier').val();

          console.log($(this).val());
          console.log("chaing");

          // localStorage.setItem('form-add_pick_up_order-supplier', JSON.stringify(val));
          localStorage.setItem('form-add_pick_up_order-supplier', val);

        });

        // // $(document).on('change', '#form-add_pick_up_order-message_to_supplier', function(e) {
        // $(document).on('change', '#supplier', function(e) {
        //   // console.log($(this).val());
        //   // console.log("chaing");
        //
        //   // let val = $('#supplier').val();
        //   let val = $(this).val();
        //
        //   // // localStorage.setItem('form-add_pick_up_order-supplier', JSON.stringify(val));
        //   localStorage.setItem('form-add_pick_up_order-supplier', val);
        //
        // });

        // $(document).on('change', '#form-add_pick_up_order-message_to_supplier', function(e) {
        $(document).on('input', '#msgToSupplier', function(e) {
          // console.log($(this).val());
          console.log("chaing");
          // let val = $('#form-add_pick_up_order-message_to_supplier').val();
          //
          // // localStorage.setItem('form-add_pick_up_order-message_to_supplier', JSON.stringify(val));
          // localStorage.setItem('form-add_pick_up_order-message_to_supplier', val);
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
        $(document).on('change', '#pick_up_order_image', function(e) {
          console.log("pick_up_order_image");
          // localStorage.setItem('pick_up_order_image', JSON.stringify(val));
          localStorage.setItem('pick_up_order_image', val);
        });
        $(document).on('change', '#pick_up_order_images', function(e) {
          console.log("pick_up_order_images");
          // localStorage.setItem('pick_up_order_images', JSON.stringify(val));
          localStorage.setItem('pick_up_order_images', val);
        });
        $(document).on('change', '#pick_up_order_document', function(e) {
          console.log("pick_up_order_document");
          // localStorage.setItem('pick_up_order_document', JSON.stringify(val));
          localStorage.setItem('form-add_pick_up_order-attachment', val);
        });

        // $('#form-add_pick_up_order-supplier').on('change', function(e) {
        //   // alert($(this).val());
        //   console.log($(this).val());
        //   let val = $('#form-add_pick_up_order-supplier').val();
        //   // localStorage.setItem('form-add_pick_up_order-supplier', JSON.stringify(val));
        //   localStorage.setItem('form-add_pick_up_order-supplier', val);
        // });
        //
        // // $('#form-add_pick_up_order-message_to_supplier').on('change', function(e) {
        // $('#msgToSupplier').on('change', function(e) {
        //   // alert($(this).val());
        //   console.log($(this).val());
        //   console.log("chaing");
        //   let val = $('#form-add_pick_up_order-message_to_supplier').val();
        //   // localStorage.setItem('form-add_pick_up_order-message_to_supplier', JSON.stringify(val));
        //   localStorage.setItem('form-add_pick_up_order-message_to_supplier', val);
        // });

        // *********************************************************************
        // *********************************************************************
        // *********************************************************************

    });
</script>
