<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="box">

    <!-- ***********************************************************************

      ADD PAGE TITLE AND ICON HERE

    ************************************************************************ -->

    <div class="box-header">
        <?php /*
          <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('add_product'); ?></h2>
        */ ?>
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i>Add Rack</h2>
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
                echo admin_form_open_multipart("warehouses/handleAddRack_logic", $attrib)
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
                          // echo form_input('supplier', (isset($_POST['supplier']) ? $_POST['supplier'] : ''), 'class="form-control ' . ($product ? '' : 'suppliers') . '" id="' . "form-add_rack-supplier" . '" placeholder="' . lang("select") . ' ' . lang("supplier") . '" style="width:100%;"');
                          // id="form-add_rack-supplier"
                      */ ?>
                  <?php /*
                  </div>
                  */ ?>

                    <!-- *******************************************************
                      SELECT WAREHOUSE
                    ******************************************************** -->

                    <div class="col-md-4">
                    <div class="form-group all">
                        <?php /* <label for="mcode" class="col-sm-4 control-label"><?= lang('product_code') ?> *</label> */ ?>

                        <label><?= "Warehouse *" ?></label>
                        <?php /* <label for="mcode" class="col-sm-4 control-label"><?= "Product" ?> *</label> */ ?>

                          <div class="form-group">
                              <?php
                              $wrehse[''] = "";
                              foreach ($warehouses as $warehouse) {
                                  $wrehse[$warehouse->id] = $warehouse->name;
                              }
                              // echo form_dropdown('warehouse', $wrehse, (isset($_POST['warehouse']) ? $_POST['warehouse'] : ($warehouse ? $warehouse->id : '')), 'class="form-control select" id="warehouse_id" placeholder="' . lang("select") . " " . lang("supplier") . '" required="required" style="width:100%"')
                              echo form_dropdown('rack_warehouse', $wrehse, (isset($_POST['rack_warehouse']) ? $_POST['rack_warehouse'] : ($warehouse ? $warehouse->id : '')), 'class="form-control select" id="warehouse_id" placeholder="Select Warehouse" required="required" style="width:100%"')
                              ?>
                          </div>

                    </div>
                    </div>

                    <!-- ***************************************************
                    *  FLOOR LEVEL
                    **************************************************** -->

                    <div class="col-md-4">
                    <div class="form-group">
                        <label> <?= /* lang("reference_no", "slref"); */ "Floor Level *" ?> </label>
                        <?php echo form_input('rack_floor_level', (isset($_POST['rack_floor_level']) ? $_POST['rack_floor_level'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                    </div>
                    </div>

                    <!-- ***************************************************
                    *  RACK USAGE
                    **************************************************** -->

                    <div class="col-md-4">
                    <div class="form-group all">
                        <div class="form-group all">
                            <?php /* <?= lang("product_type", "type") ?> */ ?>
                            <label> <?= /* lang("reference_no", "slref"); */ "Rack Usage *" ?> </label>
                            <?php
                            // $opts = array('standard' => lang('standard'), 'combo' => lang('combo'), 'digital' => lang('digital'), 'service' => lang('service'));
                            $opts = array(
                              'default' => 'default',
                              'newest_items' => 'newest_items',
                              'clearance_items' => 'clearance_items',
                              'damaged_items' => 'damaged_items',
                              'returned_items' => 'returned_items',
                              'refurbished_items' => 'refurbished_items',
                              'expired_items' => 'expired_items',
                              'layaway_items' => 'layaway_items',
                            );
                            echo form_dropdown('rack_usage', $opts, (isset($_POST['rack_usage']) ? $_POST['rack_usage'] : ($product ? $product->type : '')), 'class="form-control" id="type" required="required"');
                            ?>
                        </div>
                    </div>
                    </div>

                    <br>

                    <!-- ***************************************************
                    *  COLUMN
                    **************************************************** -->

                    <div class="col-md-4">
                    <div class="form-group">
                        <label> <?= /* lang("reference_no", "slref"); */ "Column *" ?> </label>
                        <?php echo form_input('rack_column', (isset($_POST['rack_column']) ? $_POST['rack_column'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                    </div>
                    </div>

                    <!-- ***************************************************
                    *  ROW
                    **************************************************** -->

                    <div class="col-md-4">
                    <div class="form-group">
                        <label> <?= /* lang("reference_no", "slref"); */ "Row *" ?> </label>
                        <?php echo form_input('rack_row', (isset($_POST['rack_row']) ? $_POST['rack_row'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                    </div>
                    </div>

                    <!-- ***************************************************
                    *  Z-INDEX
                    **************************************************** -->

                    <div class="col-md-4">
                    <div class="form-group">
                        <label> <?= /* lang("reference_no", "slref"); */ "Z Index *" ?> </label>
                        <?php echo form_input('rack_z_index', (isset($_POST['rack_z_index']) ? $_POST['rack_z_index'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                    </div>
                    </div>

                    <br>

                    <!-- *******************************************************
                      RACK COMMENTS
                    ******************************************************** -->

                    <div class="col-md-12">
                    <div class="form-group all">
                        <?php /* <?= lang("product_details", "product_details") ?> */ ?>
                        <label><?= "Add Comment" ?></label>
                        <br>
                        <?= "\n(Optional) This field is not required." ?>
                        <?php /* <?= form_textarea('product_details', (isset($_POST['product_details']) ? $_POST['product_details'] : ($product ? $product->product_details : '')), 'class="form-control" id="details"'); ?> */ ?>
                        <?= form_textarea('rack_comments', (isset($_POST['rack_comments']) ? $_POST['rack_comments'] : ($product ? $product->msgToSupplier : '')), 'class="form-control" id="msgToSupplier"'); ?>
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
                        <?php echo form_submit('add_product', "Reset", 'class="btn btn-danger" id="rack_items-reset_button"'); ?>
                        <?php echo form_submit('add_product', "Add Rack", 'class="btn btn-primary"'); ?>
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

<div class="modal" id="addSupplyOrderProductModal" tabindex="-1" role="dialog" aria-labelledby="mModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                            class="fa fa-2x">&times;</i></span><span class="sr-only"><?=lang('close');?></span></button>
                <?php /* <h4 class="modal-title" id="mModalLabel"><?= lang('add_product_manually') ?></h4> */ ?>
                <h4 class="modal-title" id="mModalLabel"><?= "Add Product To Order" ?></h4>
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
                              echo form_dropdown('product', $prod, (isset($_POST['product']) ? $_POST['product'] : ($product ? $product->product_id : '')), 'class="form-control select" id="rack_product_id" placeholder="' . lang("select") . " " . lang("product") . '" required="required" style="width:100%"')
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
                            <input type="number" min="0" class="form-control" id="rack_product_qty" required>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="addSupplyOrderProductButton"><?= lang('submit') ?></button>
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

        // *********************************************************************
        // CLEAR THIS FORM VALUES FROM localStorage
        // *********************************************************************

        function clearThisFormFronLocalStorage() {
          localStorage.removeItem('form-add_rack-supplier');
          localStorage.removeItem('form-add_rack-message_to_supplier');
          localStorage.removeItem('form-add_rack-message_to_receiving');
          localStorage.removeItem('form-add_rack-image');
          localStorage.removeItem('form-add_rack-image_gallery');
          localStorage.removeItem('form-add_rack-attachment');
          localStorage.removeItem('form-add_rack-items');
          localStorage.removeItem('form-add_rack-items_rows_count');
        }
        window.clearThisFormFronLocalStorage =  clearThisFormFronLocalStorage;

        // *********************************************************************
        // CHECK IF localStorage VALUES FOR INPUTS EXIST
        // *********************************************************************

        if (localStorage.getItem('form-add_rack-supplier')) {
            // // localStorage.removeItem('slwarehouse');
            // // SET INPUT VALUE TO THE ONE ON localStorage
            // console.log("Found value for supplier is: " + localStorage.getItem('form-add_rack-supplier'));
            // $('#supplier').val(localStorage.getItem('form-add_rack-supplier'));
            // $('#supplier').text(localStorage.getItem('form-add_rack-supplier')).change();
            // $('#supplier').select(localStorage.getItem('form-add_rack-supplier'));
            // $('#supplier').filter(localStorage.getItem('form-add_rack-supplier'));
            // // $('#supplier').selected(localStorage.getItem('form-add_rack-supplier'));
        }

        if (localStorage.getItem('form-add_rack-message_to_supplier')) {
            // // localStorage.removeItem('slwarehouse');
            // // SET INPUT VALUE TO THE ONE ON localStorage
            // let val = $('#form-add_rack-message_to_supplier').val(localStorage.getItem('form-add_rack-message_to_supplier'));
        }

        if (localStorage.getItem('rack_document')) {
            let val = $('#rack_document').val(localStorage.getItem('form-add_rack-attachment'));
        }

        // *********************************************************************
        // INIT localStorage ITEM 'form-add_rack-items'
        // *********************************************************************

        var initSupplyOrderItems = localStorage.getItem('form-add_rack-items');
        if (initSupplyOrderItems === null || initSupplyOrderItems === undefined || initSupplyOrderItems.constructor.toString().indexOf("Array") != -1) {
          initSupplyOrderItems = [];
          localStorage.setItem('form-add_rack-items', JSON.stringify(initSupplyOrderItems));
        }

        // *********************************************************************
        // POPULATE TABLE - SUPPLY ORDER ITEMS
        // *********************************************************************

        loadSupplyOrderItems();

        function loadSupplyOrderItems() {

            var currentOrderItems = localStorage.getItem('form-add_rack-items');

            if (currentOrderItems && currentOrderItems.length > 0) {

              $('#supplyOrderTable tbody').empty();

              currentOrderItems = JSON.parse(localStorage.getItem('form-add_rack-items'));

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
                newTr.prependTo('#supplyOrderTable');

              })
            }

        }

        // *********************************************************************
        // ADD ITEM TO ORDER - MODAL FORM SUBMIT BUTTON
        // *********************************************************************

        $(document).on('click', '#addSupplyOrderProductButton', function(e) {
            event.preventDefault();

            var createdAt = new Date().getTime();
            var rack_product_id = $('#rack_product_id').val();
            var rack_product_qty = $('#rack_product_qty').val();

            if (rack_product_id && rack_product_qty) {

                var currentOrderItems2 = JSON.parse(localStorage.getItem('form-add_rack-items'));

                var rowCount = localStorage.getItem('form-add_rack-items_rows_count');
                if (rowCount === null || rowCount === undefined) {
                  rowCount = 1;
                } else {
                  rowCount++;
                }
                localStorage.setItem('form-add_rack-items_rows_count', rowCount);

                var productsList = <?php echo json_encode($products); ?>;

                let prodName = "";
                productsList.map(prod => {
                  if (prod.id.toString() === rack_product_id.toString()) {
                    prodName = prod.name;
                  }
                })

                var orderItem = {
                    item_id: rowCount,
                    createdAt,
                    row: {
                      row_no: rowCount,
                      product_id: rack_product_id,
                      product_name: prodName,
                      product_quantity: rack_product_qty,
                    },
                    options: false,
                };

                let updatedOrderItems = [];
                updatedOrderItems.push(...currentOrderItems2);
                updatedOrderItems.push(orderItem);

                localStorage.setItem('form-add_rack-items', JSON.stringify(updatedOrderItems));
                loadSupplyOrderItems();
            }

            $('#addSupplyOrderProductModal').modal('hide');

            $('#rack_product_qty').val('');
            return false;
        });

        // *********************************************************************
        // REMOVE ORDER ITEM - REMOVE ROW FROM TABLE AND OBJ FROM localStorage
        // *********************************************************************

        function removeSupplyOrderItem(rowNo) {
          // console.log("REMOVING ROW: " + rowNo);
          var orderItems = JSON.parse(localStorage.getItem('form-add_rack-items'));
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
                localStorage.setItem('form-add_rack-items', JSON.stringify(orderItems));
                let trID = `#row_${rowNo}`;
                $(trID).remove();
            }
          })
        }
        window.removeSupplyOrderItem =  removeSupplyOrderItem;

        // *********************************************************************
        // RESET ORDER
        // *********************************************************************

        $(document).on('click', '#rack_items-reset_button', function(e) {
          event.preventDefault();
          localStorage.clear();
          location.reload();
        });

        //

        $(document).on('change', '#supplier', function(e) {

          let val = $('#form-add_rack-supplier').val();

          console.log($(this).val());
          console.log("chaing");

          // localStorage.setItem('form-add_rack-supplier', JSON.stringify(val));
          localStorage.setItem('form-add_rack-supplier', val);

        });

        // // $(document).on('change', '#form-add_rack-message_to_supplier', function(e) {
        // $(document).on('change', '#supplier', function(e) {
        //   // console.log($(this).val());
        //   // console.log("chaing");
        //
        //   // let val = $('#supplier').val();
        //   let val = $(this).val();
        //
        //   // // localStorage.setItem('form-add_rack-supplier', JSON.stringify(val));
        //   localStorage.setItem('form-add_rack-supplier', val);
        //
        // });

        // $(document).on('change', '#form-add_rack-message_to_supplier', function(e) {
        $(document).on('input', '#msgToSupplier', function(e) {
          // console.log($(this).val());
          console.log("chaing");
          // let val = $('#form-add_rack-message_to_supplier').val();
          //
          // // localStorage.setItem('form-add_rack-message_to_supplier', JSON.stringify(val));
          // localStorage.setItem('form-add_rack-message_to_supplier', val);
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
        $(document).on('change', '#rack_image', function(e) {
          console.log("rack_image");
          // localStorage.setItem('rack_image', JSON.stringify(val));
          localStorage.setItem('rack_image', val);
        });
        $(document).on('change', '#rack_images', function(e) {
          console.log("rack_images");
          // localStorage.setItem('rack_images', JSON.stringify(val));
          localStorage.setItem('rack_images', val);
        });
        $(document).on('change', '#rack_document', function(e) {
          console.log("rack_document");
          // localStorage.setItem('rack_document', JSON.stringify(val));
          localStorage.setItem('form-add_rack-attachment', val);
        });

        // $('#form-add_rack-supplier').on('change', function(e) {
        //   // alert($(this).val());
        //   console.log($(this).val());
        //   let val = $('#form-add_rack-supplier').val();
        //   // localStorage.setItem('form-add_rack-supplier', JSON.stringify(val));
        //   localStorage.setItem('form-add_rack-supplier', val);
        // });
        //
        // // $('#form-add_rack-message_to_supplier').on('change', function(e) {
        // $('#msgToSupplier').on('change', function(e) {
        //   // alert($(this).val());
        //   console.log($(this).val());
        //   console.log("chaing");
        //   let val = $('#form-add_rack-message_to_supplier').val();
        //   // localStorage.setItem('form-add_rack-message_to_supplier', JSON.stringify(val));
        //   localStorage.setItem('form-add_rack-message_to_supplier', val);
        // });

        // *********************************************************************
        // *********************************************************************
        // *********************************************************************

    });
</script>
