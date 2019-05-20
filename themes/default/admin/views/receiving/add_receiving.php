<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="box">

    <!-- ***********************************************************************

      ADD PAGE TITLE AND ICON HERE

    ************************************************************************ -->

    <div class="box-header">
        <?php /*
          <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('add_product'); ?></h2>
        */ ?>
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i>Add Receiving Report</h2>
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
                echo admin_form_open_multipart("receiving/handleAddReceiving_logic", $attrib)
                ?>

                <div class="col-md-12">

                  <!-- *******************************************************
                    SELECT WAREHOUSE
                  ******************************************************** -->

                  <div class="form-group all">
                      <?php /* <label for="mcode" class="col-sm-4 control-label"><?= lang('product_code') ?> *</label> */ ?>

                      <label><?= "Warehouse *" ?></label>
                      <?php /* <label for="mcode" class="col-sm-4 control-label"><?= "Product" ?> *</label> */ ?>

                        <div class="form-group">
                            <?php
                            $whouse[''] = "";
                            foreach ($warehouses as $warehouse) {
                                $whouse[$warehouse->id] = $warehouse->name;
                            }
                            // echo form_dropdown('supply_order', $whouse, (isset($_POST['warehouse_id']) ? $_POST['warehouse_id'] : ($warehouse ? $warehouse->id : '')), 'class="form-control select" id="warehouse_id" placeholder="' . lang("select") . " " . lang("supplier") . '" required="required" style="width:100%"')
                            echo form_dropdown('warehouse_id', $whouse, (isset($_POST['warehouse_id']) ? $_POST['warehouse_id'] : ($product ? $warehouse->id : '')), 'class="form-control select" id="warehouse_id" placeholder="' . lang("select") . " " . "Select Warehouse" . '" required="required" style="width:100%"')
                            ?>
                        </div>

                  </div>

                    <!-- *******************************************************
                      SELECT SUPPLY ORDER NUMBER
                    ******************************************************** -->

                    <div class="form-group all">
                        <?php /* <label for="mcode" class="col-sm-4 control-label"><?= lang('product_code') ?> *</label> */ ?>

                        <label><?= "Supply Order Number" ?></label>
                        <?php /* <label for="mcode" class="col-sm-4 control-label"><?= "Product" ?> *</label> */ ?>

                          <div class="form-group">
                              <?php
                              $supp_ord[''] = "";
                              foreach ($supply_orders as $supply_order) {
                                  $supp_ord[$supply_order->id] = $supply_order->supply_order_number;
                              }
                              // echo form_dropdown('supply_order', $supp_ord, (isset($_POST['supply_order']) ? $_POST['supply_order'] : ($supply_order ? $supply_order->supplier_id : '')), 'class="form-control select" id="supply_order_number" placeholder="' . lang("select") . " " . lang("supplier") . '" required="required" style="width:100%"')
                              echo form_dropdown('supply_order', $supp_ord, (isset($_POST['supply_order']) ? $_POST['supply_order'] : ($supply_order ? $supply_order->supplier_id : '')), 'class="form-control select" id="supply_order_number" placeholder="' . lang("select") . " " . "Select Supply Order Number" . '" required="required" style="width:100%"')
                              ?>
                          </div>

                    </div>

                    <br>

                    <!-- ***************************************************
                    *  REFERENCE NO
                    **************************************************** -->

                    <div class="form-group all">
                        <div class="form-group">
                            <label> <?= /* lang("reference_no", "slref"); */ "Manifest Ref No" ?> </label>
                            <?php echo form_input('manifest_ref_no', (isset($_POST['manifest_ref_no']) ? $_POST['manifest_ref_no'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                        </div>
                    </div>

                    <br>

                    <!-- *******************************************************
                      MANIFEST - IMAGE
                    ******************************************************** -->

                    <div class="form-group all">
                        <?php /* <?= lang("product_image", "product_image") ?> */ ?>
                        <label><?= "Add Manifest Photo/Scan" ?></label>
                        <br>
                        <?= "(Optional) Requirements description here asdasd asdasd asdas asd." ?>
                        <input id="manifest_image" type="file" data-browse-label="<?= lang('browse'); ?>" name="product_image" data-show-upload="false"
                               data-show-preview="false" accept="image/*" class="form-control file">
                    </div>

                    <!-- *******************************************************
                      MANIFEST - ATTACH DOCUMENT
                    ******************************************************** -->

                    <div class="form-group all">
                        <label><?= /* lang("document", "document") */ "Add Manifest PDF Document" ?></label>
                        <br>
                        <?= "(Optional) Requirements description here asdasd asdasd asdas asd." ?>
                        <input id="manifest_document" type="file" data-browse-label="<?= lang('browse'); ?>" name="document" data-show-upload="false"
                               data-show-preview="false" class="form-control file">
                    </div>

                    <br>

                    <!-- *******************************************************
                      ADD RECEIVING NOTES
                    ******************************************************** -->

                    <div class="form-group all">
                        <?php /* <?= lang("product_details", "product_details") ?> */ ?>
                        <label><?= "Add Receiving Comments" ?></label>
                        <br>
                        <?= "(Optional) Requirements description here asdasd asdasd asdas asd." ?>
                        <?php /* <?= form_textarea('product_details', (isset($_POST['product_details']) ? $_POST['product_details'] : ($product ? $product->product_details : '')), 'class="form-control" id="details"'); ?> */ ?>
                        <?= form_textarea('receiving_comments', (isset($_POST['receiving_comments']) ? $_POST['receiving_comments'] : ($product ? $product->receiving_comments : '')), 'class="form-control" id="receiving_comments"'); ?>
                    </div>

                    <br>

                    <!-- *******************************************************
                      BUTTON - FORM SUBMIT
                    ******************************************************** -->

                    <div class="form-group">
                        <!-- SEND SUPPLY ORDER - BUTTON -->
                        <?php /* echo form_submit('add_product', $this->lang->line("add_product"), 'class="btn btn-primary"'); */ ?>
                        <?php echo form_submit('add_product', "Reset", 'class="btn btn-danger" id="supply_order_items-reset_button"'); ?>
                        <?php echo form_submit('add_product', "Add Receiving Report", 'class="btn btn-primary"'); ?>
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

<div class="modal" id="addReceivingItemModal" tabindex="-1" role="dialog" aria-labelledby="mModalLabel" aria-hidden="true">
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
                <button type="button" class="btn btn-primary" id="addReceivingItemButton"><?= lang('submit') ?></button>
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

              $('#supplyOrderTable tbody').empty();

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
                newTr.prependTo('#supplyOrderTable');

              })
            }

        }

        // *********************************************************************
        // ADD ITEM TO ORDER - MODAL FORM SUBMIT BUTTON
        // *********************************************************************

        $(document).on('click', '#addReceivingItemButton', function(e) {
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

            $('#addReceivingItemModal').modal('hide');

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
        $(document).on('input', '#msgToSupplier', function(e) {
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
        $(document).on('change', '#supply_order_image', function(e) {
          console.log("supply_order_image");
          // localStorage.setItem('supply_order_image', JSON.stringify(val));
          localStorage.setItem('supply_order_image', val);
        });
        $(document).on('change', '#supply_order_images', function(e) {
          console.log("supply_order_images");
          // localStorage.setItem('supply_order_images', JSON.stringify(val));
          localStorage.setItem('supply_order_images', val);
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
        // $('#msgToSupplier').on('change', function(e) {
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
