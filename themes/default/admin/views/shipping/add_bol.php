<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script>

    // Default DataTables Code, Leave as is... Starts here --->

    function isNumber(n) {
      return !isNaN(parseFloat(n)) && isFinite(n);
    }

    var oTable;
    $(document).ready(function () {

      var product_shipping_qty = document.body.getElementsByTagName("sale_product_shipping_qty")[0]; // item.row.product_price;
      console.log("product_shipping_qty");
      console.log(product_shipping_qty);

      $(document).on('change', '#bol_form_input-temperature', function(e) {
          let isInputNumber = isNumber($(this).val());
          if ($(this).val() !== "" && isInputNumber !== true) {
              alert("Please add numeric values only.");
              $(this).val("");
          }
      });

      $(document).on('change', '#bol_form_input-driver_phone', function(e) {
          let isInputNumber = isNumber($(this).val());
          if ($(this).val() !== "" && isInputNumber !== true) {
              alert("Please add numeric values only.");
              $(this).val("");
          }
      });

      $(document).on('change', '#bill_of_lading_sale_id', function(e) {
              let sale_id = $(this).val();

              console.log("changed!");
              console.log(sale_id);

              <?php
                  $urlParam = $_POST['sale_id'];
                  // $urlParam = 3;
              ?>

              let sale_items_data; // = [];

              $.ajax({
                  // url : 'http://voicebunny.comeze.com/index.php',
                  url : '<?= admin_url() ?>' + 'sales/handleGetSaleItems_logic' + (sale_id ? '/' + sale_id : ''),
                  type : 'GET',
                  data : {
                      // 'numberOfWords' : 10
                      sale_id
                  },
                  dataType:'json',
                  success : function(data) {
                      // alert('Data: '+ JSON.stringify(data));
                      // alert('Data: '+ JSON.stringify(data.aaData));
                      // console.log(data);
                      // console.log(data.aaData);
                      $('#Add_BOL_Items_Table tbody').empty();
                      loadSaleItems(data.aaData)
                  },
                  error : function(request,error)
                  {
                      alert("Request: "+JSON.stringify(request));
                  }
              });

              // ---------------------------------------------------------------
              // Change Input Values
              // ---------------------------------------------------------------

              // Grab Sale Id Data
              // Replace Input IDs Values
              // Save in localStorage new input values

              function loadSaleItems(sale_items_data) {

                  if (sale_items_data && sale_items_data.length > 0) {

                      sale_items_data.map((item, index) => {

                          var item_id = item[0];
                          var row_no = item[0];

                          var product_id = item[0]; // item.row.product_id;
                          var product_name = item[1]; // item.row.product_name;
                          var product_quantity = item[2]; // item.row.product_quantity;
                          var product_shipping_qty = ""; // item.row.product_price;

                          var newTr = $('<tr id="row_' + row_no + '" class="row_' + item_id + '" data-item-id="' + item_id + '"></tr>');
                          var tr_html =
                                  `<td align="center">
                                      <input name="sale_product_id[]" style="text-align: center; width: 100%;" readonly type="hidden" class="rid" value="${product_id}">
                                  </td>`;
                              tr_html +=
                                  `<td align="center">
                                      <input name="sale_product_name[]" style="text-align: center; width: 100%;" readonly type="text" class="rid" value="${product_name}">
                                  </td>`;
                              tr_html +=
                                  `<td align="center">
                                      <input name="sale_product_quantity[]" style="text-align: center; width: 100%;" readonly type="number" class="rid" value="${product_quantity}">
                                  </td>`;
                              tr_html +=
                                  `<td align="center">
                                      <input name="sale_product_shipping_qty[]" style="text-align: center; width: 100%;" type="number" class="rid" id="sale_items_row_${row_no}">
                                  </td>`;
                              // tr_html += `
                              //     <td class="text-center">
                              //         <i
                              //             id="'${row_no}'"
                              //             class="fa fa-times tip pointer sldel"
                              //             title="Remove"
                              //             style="cursor:pointer;"
                              //             onclick='removePickUpOrderItem(${row_no})'
                              //         >
                              //         </i>
                              //     </td>
                              // `;
                              tr_html +=
                                  `<td align="center">
                                      <input style="text-align: center; width: 100%;" readonly type="hidden" class="rid">
                                  </td>`;

                          newTr.html(tr_html);
                          newTr.prependTo('#Add_BOL_Items_Table');

                      })

                  }

              }

              ////////

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
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i>Add Bill of Lading</h2>
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
                echo admin_form_open_multipart("shipping/handleAddBillOfLading_logic", $attrib)
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
                          // echo form_input('supplier', (isset($_POST['supplier']) ? $_POST['supplier'] : ''), 'class="form-control ' . ($product ? '' : 'suppliers') . '" id="' . "form-add_bill_of_lading-supplier" . '" placeholder="' . lang("select") . ' ' . lang("supplier") . '" style="width:100%;"');
                          // id="form-add_bill_of_lading-supplier"
                      */ ?>
                  <?php /*
                  </div>
                  */ ?>

                    <!-- *******************************************************
                      SELECT SALE
                    ******************************************************** -->

                    <div class="col-md-3">
                        <?php /* <label for="mcode" class="col-sm-4 control-label"><?= lang('product_code') ?> *</label> */ ?>

                        <label><?= "Sale No" ?></label>
                        <?php /* <label for="mcode" class="col-sm-4 control-label"><?= "Product" ?> *</label> */ ?>

                          <div class="form-group">
                              <?php
                              $sl[''] = "";
                              foreach ($sales as $sale) {
                                  $sl[$sale->id] = $sale->sale_no;
                              }
                              // echo form_dropdown('sale_id', $sl, (isset($_POST['sale_id']) ? $_POST['sale_id'] : ($sale ? $sale->sale_id : '')), 'class="form-control select" id="bill_of_lading_sale_id" placeholder="' . lang("select") . " " . lang("sale") . '" required="required" style="width:100%"')
                              echo form_dropdown('sale_id', $sl, (isset($_POST['sale_id']) ? $_POST['sale_id'] : ($sale ? $sale->id : '')), 'class="form-control select" id="bill_of_lading_sale_id" placeholder="Select Sale No" required="required" style="width:100%"')
                              ?>
                          </div>

                    </div>

                    <!-- *******************************************************
                      SELECT SHIPPER (BILLER)
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
                      SELECT WAREHOUSE
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
                        <?= "(Optional) Requirements description here asdasd asdasd asdas asd." ?>
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
                        <?= "(Optional) Requirements description here asdasd asdasd asdas asd." ?>
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
                        <?= "(Optional) Requirements description here asdasd asdasd asdas asd." ?>
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
                        <?= "(Optional) Requirements description here asdasd asdasd asdas asd." ?>
                        <input id="attachments" type="file" data-browse-label="<?= lang('browse'); ?>" name="document" data-show-upload="false"
                               data-show-preview="false" class="form-control file">
                    </div>

                    <div class="row"></div>
                    <hr>

                    <!-- *******************************************************
                      TABLE - SALE ITEMS
                    ******************************************************** -->

                    <div class="col-md-12">
                        <div class="control-group table-group">
                            <label class="table-label"><?= /* lang("order_items"); */ "Sale Items" ?> *</label>

                            <div class="controls table-controls">
                                <table id="Add_BOL_Items_Table" class="table items table-striped table-bordered table-condensed table-hover sortable_table">
                                    <thead>
                                    <tr>

                                        <!-- ***********************************
                                          TABLE - COLUMN - ID
                                        ************************************ -->

                                        <th class="col-md-4" style="width: 1% !important;"></th>

                                        <!-- ***********************************
                                          TABLE - COLUMN - PRODUCT
                                        ************************************ -->

                                        <th class="col-md-4" style="width: 49% !important;">Product</th>

                                        <!-- ***********************************
                                          TABLE - COLUMN - QUANTITY
                                        ************************************ -->

                                        <th class="col-md-4" style="width: 25% !important;">Quantity</th>

                                        <!-- ***********************************
                                          TABLE - COLUMN - PRICE
                                        ************************************ -->

                                        <th class="col-md-4" style="width: 25% !important;">Shipping Quantity</th>

                                        <!-- ***********************************
                                          TABLE - COLUMN - DELETE BUTTON
                                        ************************************ -->

                                        <!--
                                        <th style="width: 8% !important; text-align: center;">
                                            <i class="fa fa-trash-o" style="opacity:0.5; filter:alpha(opacity=50);"></i>
                                        </th>
                                        -->

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

                    <br>

                    <br>
                    <div class="row"></div>
                    <hr>

                    <!-- /////////////////////////////////////////////////// -->

                    <!-- *******************************************************
                      BUTTON - FORM SUBMIT
                    ******************************************************** -->

                    <div class="form-group">
                        <!-- SEND PICK UP ORDER - BUTTON -->
                        <?php /* echo form_submit('add_product', $this->lang->line("add_product"), 'class="btn btn-primary"'); */ ?>
                        <?php echo form_submit('add_product', "Reset", 'class="btn btn-danger" id="bill_of_lading_items-reset_button"'); ?>
                        <?php echo form_submit('add_product', "Save Bill of Lading", 'class="btn btn-primary"'); ?>
                    </div>

                </div>

                <?= form_close(); ?>

            </div>

        </div>
    </div>
</div>

<!-- ***************************************************************************

  LOGIC HANDLERS

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
        // RESET ORDER
        // *********************************************************************

        $(document).on('click', '#bill_of_lading_items-reset_button', function(e) {
          event.preventDefault();
          localStorage.clear();
          location.reload();
        });

        // // $(document).on('change', '#form-add_bill_of_lading-message_to_supplier', function(e) {
        // $(document).on('change', '#supplier', function(e) {
        //   // console.log($(this).val());
        //   // console.log("chaing");
        //
        //   // let val = $('#supplier').val();
        //   let val = $(this).val();
        //
        //   // // localStorage.setItem('form-add_bill_of_lading-supplier', JSON.stringify(val));
        //   localStorage.setItem('form-add_bill_of_lading-supplier', val);
        //
        // });

        // $(document).on('change', '#form-add_bill_of_lading-message_to_supplier', function(e) {
        $(document).on('input', '#msgToSupplier', function(e) {
          // console.log($(this).val());
          console.log("chaing");
          // let val = $('#form-add_bill_of_lading-message_to_supplier').val();
          //
          // // localStorage.setItem('form-add_bill_of_lading-message_to_supplier', JSON.stringify(val));
          // localStorage.setItem('form-add_bill_of_lading-message_to_supplier', val);
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
        $(document).on('change', '#bill_of_lading_image', function(e) {
          console.log("bill_of_lading_image");
          // localStorage.setItem('bill_of_lading_image', JSON.stringify(val));
          localStorage.setItem('bill_of_lading_image', val);
        });
        $(document).on('change', '#bill_of_lading_images', function(e) {
          console.log("bill_of_lading_images");
          // localStorage.setItem('bill_of_lading_images', JSON.stringify(val));
          localStorage.setItem('bill_of_lading_images', val);
        });
        $(document).on('change', '#bill_of_lading_document', function(e) {
          console.log("bill_of_lading_document");
          // localStorage.setItem('bill_of_lading_document', JSON.stringify(val));
          localStorage.setItem('form-add_bill_of_lading-attachment', val);
        });

        // $('#form-add_bill_of_lading-supplier').on('change', function(e) {
        //   // alert($(this).val());
        //   console.log($(this).val());
        //   let val = $('#form-add_bill_of_lading-supplier').val();
        //   // localStorage.setItem('form-add_bill_of_lading-supplier', JSON.stringify(val));
        //   localStorage.setItem('form-add_bill_of_lading-supplier', val);
        // });
        //
        // // $('#form-add_bill_of_lading-message_to_supplier').on('change', function(e) {
        // $('#msgToSupplier').on('change', function(e) {
        //   // alert($(this).val());
        //   console.log($(this).val());
        //   console.log("chaing");
        //   let val = $('#form-add_bill_of_lading-message_to_supplier').val();
        //   // localStorage.setItem('form-add_bill_of_lading-message_to_supplier', JSON.stringify(val));
        //   localStorage.setItem('form-add_bill_of_lading-message_to_supplier', val);
        // });

        // *********************************************************************
        // *********************************************************************
        // *********************************************************************

    });
</script>
