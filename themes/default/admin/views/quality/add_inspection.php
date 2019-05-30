<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script type="text/javascript">
    $(document).ready(function () {

        // Start inputs with empty values here
        // $('#').val("");

        // Populate text input with a single value
        $.ajax({
            url : '<?= admin_url() ?>' + 'quality/getNextReportNo',
            type : 'GET',
            data : {},
            dataType:'json',
            success : function(data) {
                let nextNo = parseInt(data);
                $('#inspection_form_input-inspection_report_no').val(nextNo);
            },
            error : function(request, error)
            {
                // alert("Request: " + JSON.stringify(request));
                alert("Error: " + JSON.stringify(error));
            }
        });

        // ON EVERY FORM INPUT CHANGE, SAVE NEW VALUES TO localStorage

        $(document).on('change', '#inspection_form_input-inspection_report_no', function(e) {
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
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i>Add Inspection</h2>
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
                echo admin_form_open_multipart("quality/handleAddInspection_logic", $attrib)
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
                          // echo form_input('supplier', (isset($_POST['supplier']) ? $_POST['supplier'] : ''), 'class="form-control ' . ($product ? '' : 'suppliers') . '" id="' . "form-add_inspection-supplier" . '" placeholder="' . lang("select") . ' ' . lang("supplier") . '" style="width:100%;"');
                          // id="form-add_inspection-supplier"
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
                          <label> <?= /* lang("reference_no", "slref"); */ "Inspection Report No *" ?> </label>
                          <?php echo form_input('inspection_report_no', (isset($_POST['inspection_report_no']) ? $_POST['inspection_report_no'] : ""), 'class="form-control input-tip" id="inspection_form_input-inspection_report_no"'); ?>
                      </div>
                  </div>
                  </div>

                  <div class="row"></div>
                  <hr>

                  <!-- *******************************************************
                    SELECT WAREHOUSE
                  ******************************************************** -->

                  <div class="col-md-4">
                      <?php /* <label for="mcode" class="col-sm-4 control-label"><?= lang('product_code') ?> *</label> */ ?>

                      <label><?= "Warehouse" ?></label>
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

                    <!-- *******************************************************
                      SELECT RECEIVING REPORT NO
                    ******************************************************** -->

                    <div class="col-md-4">
                        <?php /* <label for="mcode" class="col-sm-4 control-label"><?= lang('product_code') ?> *</label> */ ?>

                        <label><?= "Receiving Report No" ?></label>
                        <?php /* <label for="mcode" class="col-sm-4 control-label"><?= "Product" ?> *</label> */ ?>

                          <div class="form-group">
                              <?php
                              $rec_no[''] = "";
                              foreach ($receiving_reports as $receiving) {
                                  $rec_no[$receiving->id] = $receiving->receiving_report_number;
                              }
                              // echo form_dropdown('receiving_id', $rec_no, (isset($_POST['receiving_id']) ? $_POST['receiving_id'] : ($receiving ? $receiving->receiving_report_number : '')), 'class="form-control select" id="select_receiving_report_id" placeholder="' . lang("select") . " " . lang("supplier") . '" required="required" style="width:100%"')
                              echo form_dropdown('receiving_id', $rec_no, (isset($_POST['receiving_id']) ? $_POST['receiving_id'] : ($receiving ? $receiving->receiving_report_number : '')), 'class="form-control select" id="select_receiving_report_id" placeholder="' . lang("select") . " " . "Receiving Report Number" . '" required="required" style="width:100%"')
                              ?>
                          </div>

                    </div>

                    <!-- ***************************************************
                    *  LOT NO
                    **************************************************** -->

                    <div class="col-md-4">
                        <div class="form-group">
                            <label> <?= /* lang("reference_no", "slref"); */ "Lot No" ?> </label>
                            <?php echo form_input('lot_no', (isset($_POST['lot_no']) ? $_POST['lot_no'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                        </div>
                    </div>

                    <!-- ***************************************************
                    *  PRODUCT
                    **************************************************** -->

                    <div class="col-md-4">
                        <div class="form-group">
                            <label> <?= /* lang("reference_no", "slref"); */ "Product" ?> </label>
                            <?php echo form_input('product_type', (isset($_POST['product_type']) ? $_POST['product_type'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                        </div>
                    </div>

                    <!-- ***************************************************
                    *  TOTAL QTY SAMPLED
                    **************************************************** -->

                    <div class="col-md-4">
                        <div class="form-group">
                            <label> <?= /* lang("reference_no", "slref"); */ "Total Qty Sampled" ?> </label>
                            <?php echo form_input('total_qty_sampled', (isset($_POST['total_qty_sampled']) ? $_POST['total_qty_sampled'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                        </div>
                    </div>

                    <!-- ***************************************************
                    *  GROWER SHIPPER
                    **************************************************** -->

                    <div class="col-md-4">
                        <div class="form-group">
                            <label> <?= /* lang("reference_no", "slref"); */ "Grower Shipper" ?> </label>
                            <?php echo form_input('grower_shipper', (isset($_POST['grower_shipper']) ? $_POST['grower_shipper'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                        </div>
                    </div>

                    <!-- ***************************************************
                    *  INSPECTION ADDRESS
                    **************************************************** -->

                    <div class="col-md-4">
                        <div class="form-group">
                            <label> <?= /* lang("reference_no", "slref"); */ "Inspection Address" ?> </label>
                            <?php echo form_input('inspection_address', (isset($_POST['inspection_address']) ? $_POST['inspection_address'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                        </div>
                    </div>

                    <!-- ***************************************************
                    *  INSPECTION DATE
                    **************************************************** -->

                    <?php if ($Owner || $Admin) { ?>
                        <?php /* <div class="col-md-4"> */ ?>
                        <div class="col-md-4">
                            <div class="form-group">
                                 <label><?= /* lang("date", "sldate"); */ "Inspection Date" ?></label>
                                <?php echo form_input('date', (isset($_POST['date']) ? $_POST['date'] : ""), 'class="form-control input-tip datetime" id="sldate" required="required" autocomplete="off"'); ?>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- ***************************************************
                    *  INSPECTION NAME
                    **************************************************** -->

                    <div class="col-md-4">
                        <div class="form-group">
                            <label> <?= /* lang("reference_no", "slref"); */ "Inspection Name" ?> </label>
                            <?php echo form_input('inspection_name', (isset($_POST['inspection_name']) ? $_POST['inspection_name'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                        </div>
                    </div>

                    <!-- ***************************************************
                    *  PRODUCT ORIGIN
                    **************************************************** -->


                    <div class="col-md-4">
                        <div class="form-group">
                            <label> <?= /* lang("reference_no", "slref"); */ "Product Origin" ?> </label>
                            <?php echo form_input('product_origin', (isset($_POST['product_origin']) ? $_POST['product_origin'] : $slnumber), 'class="form-control input-tip" id=""'); ?>
                        </div>
                    </div>

                    <div class="row"></div>

                    <!-- *******************************************************
                      ADDITIONAL ISSUES
                    ******************************************************** -->

                    <?php /* <div class="form-group all"> */ ?>
                    <div class="col-md-6">
                      <div class="form-group">
                        <?php /* <?= lang("product_details", "product_details") ?> */ ?>
                        <label><?= "Additonal Issues" ?></label>
                        <br>
                        <?= "\n(Optional) This field is not required." ?>
                        <?php /* <?= form_textarea('product_details', (isset($_POST['product_details']) ? $_POST['product_details'] : ($product ? $product->product_details : '')), 'class="form-control" id="details"'); ?> */ ?>
                        <?= form_textarea('additional_issues', (isset($_POST['additional_issues']) ? $_POST['additional_issues'] : ($product ? $product->pallet_note : '')), 'class="form-control" id="pallet_note"'); ?>
                      </div>
                    </div>

                    <!-- *******************************************************
                      NOTES
                    ******************************************************** -->

                    <div class="col-md-6">
                     <div class="form-group">
                        <?php /* <?= lang("product_details", "product_details") ?> */ ?>
                        <label><?= "Add a Note" ?></label>
                        <br>
                        <?= "\n(Optional) This field is not required." ?>
                        <?php /* <?= form_textarea('product_details', (isset($_POST['product_details']) ? $_POST['product_details'] : ($product ? $product->product_details : '')), 'class="form-control" id="details"'); ?> */ ?>
                        <?= form_textarea('notes_comments', (isset($_POST['notes_comments']) ? $_POST['notes_comments'] : ($product ? $product->pallet_note : '')), 'class="form-control" id="pallet_note"'); ?>
                      </div>
                    </div>

                    <div class="row"></div>

                    <!-- *******************************************************
                      IMAGE
                    ******************************************************** -->

                    <div class="col-md-6">
                    <div class="form-group">
                        <?php /* <?= lang("product_image", "product_image") ?> */ ?>
                        <label><?= "Upload Image" ?></label>
                        <br>
                        <?= "(Optional) This field is not required." ?>
                        <input id="inspection_image" type="file" data-browse-label="<?= lang('browse'); ?>" name="inspection_image" data-show-upload="false"
                               data-show-preview="false" accept="image/*" class="form-control file">
                    </div>
                    </div>

                    <!-- *******************************************************
                      ATTACH DOCUMENT
                    ******************************************************** -->

                    <div class="col-md-6">
                    <div class="form-group">
                        <label><?= /* lang("document", "document") */ "Attach Document" ?></label>
                        <br>
                        <?= "(Optional) This field is not required." ?>
                        <input id="inspection_attachment" type="file" data-browse-label="<?= lang('browse'); ?>" name="inspection_attachment" data-show-upload="false"
                               data-show-preview="false" class="form-control file">
                    </div>
                    </div>

                    <!-- *******************************************************
                      IMAGE GALLERY
                    ******************************************************** -->

                    <!-- <div class="col-md-12">
                    <div class="form-group">
                        <?php /* <?= lang("product_gallery_images", "images") ?> */ ?>
                        <label><?= "Add Image Gallery" ?></label>
                        <br>
                        <?= "(Optional) This field is not required." ?>
                        <input id="inspection_images" type="file" data-browse-label="<?= lang('browse'); ?>" name="inspection_images[]" multiple="true" data-show-upload="false"
                               data-show-preview="false" class="form-control file" accept="image/*">
                    </div>
                    </div>

                    <div class="form-group">
                        <div id="img-details"></div>
                    </div>

                    <div class="row"></div>
                    <div class="row"></div>
                    <br>
                    <br> -->

                    <div class="row"></div>
                    <hr>

                    <br>

                    <!-- *******************************************************
                      + BUTTON - ADD SUPPLY ORDER ITEM
                    ******************************************************** -->

                    <div class="col-md-12">
                      <div class="form-group">
                        <div class="input-group wide-tip">
                            <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                                <i class="fa fa-2x fa-barcode addIcon"></i></a></div>
                            <?php echo form_input('add_item', '', 'readonly="true" class="form-control input-lg" id="add_item" placeholder="' . lang("add_product_to_order") . '"'); ?>
                            <?php if ($Owner || $Admin || $GP['products-add']) { ?>
                            <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                                <?php /*
                                <a href="#" id="addManually" class="tip" title="<?= lang('add_product_manually') ?>">
                                */ ?>
                                <a data-toggle="modal" data-target="#addInspectionItemsModal" href="#" id="" class="tip" title="<?= lang('add_product_manually') ?>">
                                    <i class="fa fa-2x fa-plus-circle addIcon" id="addIcon"></i>
                                </a>
                            </div>
                            <?php } if ($Owner || $Admin || $GP['sales-add_gift_card']) { ?>
                            <?php } ?>
                        </div>
                      </div>
                    </div>

                    <!-- <div class="clearfix"></div>

                    <div class="row"></div>
                    <br> -->

                    <!-- *******************************************************
                      TABLE - PALLET ITEMS
                    ******************************************************** -->

                    <div class="col-md-12">
                        <div class="control-group table-group">
                            <label class="table-label"><?= /* lang("order_items"); */ "Inspection Items" ?> *</label>

                            <div class="controls table-controls">
                                <table id="inspectionItemsTable" class="table items table-striped table-bordered table-condensed table-hover sortable_table">
                                    <thead>
                                    <tr>

                                        <!-- ***********************************
                                          TABLE - COLUMN - ID
                                        ************************************ -->

                                        <th class="col-md-4" style="width: 1% !important;"></th>

                                        <!-- ***********************************
                                          TABLE - COLUMN -
                                        ************************************ -->

                                        <th class="col-md-4" style="width: 7% !important;">Sise</th>

                                        <!-- ***********************************
                                          TABLE - COLUMN -
                                        ************************************ -->

                                        <th class="col-md-4" style="width: 7% !important;">Sample</th>

                                        <!-- ***********************************
                                          TABLE - COLUMN -
                                        ************************************ -->

                                        <th class="col-md-4" style="width: 7% !important;">Tem</th>

                                        <!-- ***********************************
                                          TABLE - COLUMN -
                                        ************************************ -->

                                        <th class="col-md-4" style="width: 7% !important;">Presion</th>

                                        <!-- ***********************************
                                          TABLE - COLUMN -
                                        ************************************ -->

                                        <th class="col-md-4" style="width: 7% !important;">Ripe</th>

                                        <!-- ***********************************
                                          TABLE - COLUMN -
                                        ************************************ -->

                                        <th class="col-md-4" style="width: 7% !important;">Mold</th>

                                        <!-- ***********************************
                                          TABLE - COLUMN -
                                        ************************************ -->

                                        <th class="col-md-4" style="width: 7% !important;">Clean</th>

                                        <!-- ***********************************
                                          TABLE - COLUMN -
                                        ************************************ -->

                                        <th class="col-md-4" style="width: 7% !important;">Color</th>

                                        <!-- ***********************************
                                          TABLE - COLUMN -
                                        ************************************ -->

                                        <th class="col-md-4" style="width: 7% !important;">Firm</th>

                                        <!-- ***********************************
                                          TABLE - COLUMN -
                                        ************************************ -->

                                        <th class="col-md-4" style="width: 7% !important;">Mechanical<br>Damage</th>

                                        <!-- ***********************************
                                          TABLE - COLUMN -
                                        ************************************ -->

                                        <th class="col-md-4" style="width: 7% !important;">Weight</th>

                                        <!-- ***********************************
                                          TABLE - COLUMN -
                                        ************************************ -->

                                        <th class="col-md-4" style="width: 7% !important;">Scars<br>Russet/Bruset</th>

                                        <!-- ***********************************
                                          TABLE - COLUMN -
                                        ************************************ -->

                                        <th class="col-md-4" style="width: 7% !important;">Over<br>Ripe</th>

                                        <!-- ***********************************
                                          TABLE - COLUMN -
                                        ************************************ -->

                                        <th class="col-md-4" style="width: 7% !important;">Total</th>

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

                    <!-- <div class="row"></div>
                    <div class="row"></div>
                    <br>
                    <br> -->

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
                        <?php echo form_submit('add_product', "Reset", 'class="btn btn-danger" id="inspection_items-reset_button"'); ?>
                        <?php echo form_submit('add_product', "Add Inspection", 'class="btn btn-primary"'); ?>
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

<div class="modal" id="addInspectionItemsModal" tabindex="-1" role="dialog" aria-labelledby="mModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                            class="fa fa-2x">&times;</i></span><span class="sr-only"><?=lang('close');?></span></button>
                <?php /* <h4 class="modal-title" id="mModalLabel"><?= lang('add_product_manually') ?></h4> */ ?>
                <h4 class="modal-title" id="mModalLabel"><?= "Add Inspection Item" ?></h4>
            </div>
            <div class="modal-body" id="pr_popover_content">

                <!-- ***********************************************************
                  FORM
                ************************************************************ -->

                <form class="form-horizontal" role="form">

                    <!-- *******************************************************
                      SISE - INPUT
                    ******************************************************** -->

                    <div class="form-group">
                        <label for="mquantity" class="col-sm-4 control-label"><?= /* lang('quantity') */ "Sise" ?></label>
                        <div class="col-sm-8">
                            <input type="text" min="0" class="form-control" id="inspection_item_sise" required>
                        </div>
                    </div>

                    <!-- *******************************************************
                      SAMPLE - INPUT
                    ******************************************************** -->

                    <div class="form-group">
                        <label for="mquantity" class="col-sm-4 control-label"><?= /* lang('quantity') */ "Sample" ?></label>
                        <div class="col-sm-8">
                            <input type="text" min="0" class="form-control" id="inspection_item_sample" required>
                        </div>
                    </div>

                    <!-- *******************************************************
                      TEM - INPUT
                    ******************************************************** -->

                    <div class="form-group">
                        <label for="mquantity" class="col-sm-4 control-label"><?= /* lang('quantity') */ "Tem" ?></label>
                        <div class="col-sm-8">
                            <input type="text" min="0" class="form-control" id="inspection_item_tem" required>
                        </div>
                    </div>

                    <!-- *******************************************************
                      PRESION - INPUT
                    ******************************************************** -->

                    <div class="form-group">
                        <label for="mquantity" class="col-sm-4 control-label"><?= /* lang('quantity') */ "Presion" ?></label>
                        <div class="col-sm-8">
                            <input type="text" min="0" class="form-control" id="inspection_item_presion" required>
                        </div>
                    </div>

                    <!-- *******************************************************
                      RIPE - INPUT
                    ******************************************************** -->

                    <div class="form-group">
                        <label for="mquantity" class="col-sm-4 control-label"><?= /* lang('quantity') */ "Ripe" ?></label>
                        <div class="col-sm-8">
                            <input type="text" min="0" class="form-control" id="inspection_item_ripe" required>
                        </div>
                    </div>

                    <!-- *******************************************************
                      MOLD - INPUT
                    ******************************************************** -->

                    <div class="form-group">
                        <label for="mquantity" class="col-sm-4 control-label"><?= /* lang('quantity') */ "Mold" ?></label>
                        <div class="col-sm-8">
                            <input type="text" min="0" class="form-control" id="inspection_item_mold" required>
                        </div>
                    </div>

                    <!-- *******************************************************
                      CLEAN - INPUT
                    ******************************************************** -->

                    <div class="form-group">
                        <label for="mquantity" class="col-sm-4 control-label"><?= /* lang('quantity') */ "Clean" ?></label>
                        <div class="col-sm-8">
                            <input type="text" min="0" class="form-control" id="inspection_item_clean" required>
                        </div>
                    </div>

                    <!-- *******************************************************
                      COLOR - INPUT
                    ******************************************************** -->

                    <div class="form-group">
                        <label for="mquantity" class="col-sm-4 control-label"><?= /* lang('quantity') */ "Color" ?></label>
                        <div class="col-sm-8">
                            <input type="text" min="0" class="form-control" id="inspection_item_color" required>
                        </div>
                    </div>

                    <!-- *******************************************************
                      FIRM - INPUT
                    ******************************************************** -->

                    <div class="form-group">
                        <label for="mquantity" class="col-sm-4 control-label"><?= /* lang('quantity') */ "Firm" ?></label>
                        <div class="col-sm-8">
                            <input type="text" min="0" class="form-control" id="inspection_item_firm" required>
                        </div>
                    </div>

                    <!-- *******************************************************
                      MECHANICAL DAMAGE - INPUT
                    ******************************************************** -->

                    <div class="form-group">
                        <label for="mquantity" class="col-sm-4 control-label"><?= /* lang('quantity') */ "Mechanical Damage" ?></label>
                        <div class="col-sm-8">
                            <input type="text" min="0" class="form-control" id="inspection_item_mechanical_damage" required>
                        </div>
                    </div>

                    <!-- *******************************************************
                      WEIGHT - INPUT
                    ******************************************************** -->

                    <div class="form-group">
                        <label for="mquantity" class="col-sm-4 control-label"><?= /* lang('quantity') */ "Weight" ?></label>
                        <div class="col-sm-8">
                            <input type="text" min="0" class="form-control" id="inspection_item_weight" required>
                        </div>
                    </div>

                    <!-- *******************************************************
                      SCARS - INPUT
                    ******************************************************** -->

                    <div class="form-group">
                        <label for="mquantity" class="col-sm-4 control-label"><?= /* lang('quantity') */ "Scars Russet/Bruset" ?></label>
                        <div class="col-sm-8">
                            <input type="text" min="0" class="form-control" id="inspection_item_scars" required>
                        </div>
                    </div>

                    <!-- *******************************************************
                      OVER RIPE - INPUT
                    ******************************************************** -->

                    <div class="form-group">
                        <label for="mquantity" class="col-sm-4 control-label"><?= /* lang('quantity') */ "Over Ripe" ?></label>
                        <div class="col-sm-8">
                            <input type="text" min="0" class="form-control" id="inspection_item_over_ripe" required>
                        </div>
                    </div>

                    <!-- *******************************************************
                      TOTAL - INPUT
                    ******************************************************** -->

                    <div class="form-group">
                        <label for="mquantity" class="col-sm-4 control-label"><?= /* lang('quantity') */ "Total" ?></label>
                        <div class="col-sm-8">
                            <input type="text" min="0" class="form-control" id="inspection_item_total" required>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="resetItemModalButton"><?= /* lang('submit') */ "Reset" ?></button>
                <button type="button" class="btn btn-primary" id="addInspectionItemButton"><?= lang('submit') ?></button>
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
          localStorage.removeItem('form-add_inspection-supplier');
          localStorage.removeItem('form-add_inspection-message_to_supplier');
          localStorage.removeItem('form-add_inspection-message_to_receiving');
          localStorage.removeItem('form-add_inspection-image');
          localStorage.removeItem('form-add_inspection-image_gallery');
          localStorage.removeItem('form-add_inspection-attachment');
          localStorage.removeItem('form-add_inspection-items');
          localStorage.removeItem('form-add_inspection-items_rows_count');
        }
        window.clearThisFormFronLocalStorage =  clearThisFormFronLocalStorage;

        // *********************************************************************
        // CHECK IF localStorage VALUES FOR INPUTS EXIST
        // *********************************************************************

        if (localStorage.getItem('form-add_inspection-supplier')) {
            // // localStorage.removeItem('slwarehouse');
            // // SET INPUT VALUE TO THE ONE ON localStorage
            // console.log("Found value for supplier is: " + localStorage.getItem('form-add_inspection-supplier'));
            // $('#supplier').val(localStorage.getItem('form-add_inspection-supplier'));
            // $('#supplier').text(localStorage.getItem('form-add_inspection-supplier')).change();
            // $('#supplier').select(localStorage.getItem('form-add_inspection-supplier'));
            // $('#supplier').filter(localStorage.getItem('form-add_inspection-supplier'));
            // // $('#supplier').selected(localStorage.getItem('form-add_inspection-supplier'));
        }

        if (localStorage.getItem('form-add_inspection-message_to_supplier')) {
            // // localStorage.removeItem('slwarehouse');
            // // SET INPUT VALUE TO THE ONE ON localStorage
            // let val = $('#form-add_inspection-message_to_supplier').val(localStorage.getItem('form-add_inspection-message_to_supplier'));
        }

        if (localStorage.getItem('inspection_document')) {
            let val = $('#inspection_document').val(localStorage.getItem('form-add_inspection-attachment'));
        }

        // *********************************************************************
        // INIT localStorage ITEM 'form-add_inspection-items'
        // *********************************************************************

        var initSupplyOrderItems = localStorage.getItem('form-add_inspection-items');
        if (initSupplyOrderItems === null || initSupplyOrderItems === undefined || initSupplyOrderItems.constructor.toString().indexOf("Array") != -1) {
          initSupplyOrderItems = [];
          localStorage.setItem('form-add_inspection-items', JSON.stringify(initSupplyOrderItems));
        }

        // *********************************************************************
        // POPULATE TABLE - SUPPLY ORDER ITEMS
        // *********************************************************************

        loadSupplyOrderItems();

        function loadSupplyOrderItems() {

            var currentOrderItems = localStorage.getItem('form-add_inspection-items');

            if (currentOrderItems && currentOrderItems.length > 0) {

              $('#inspectionItemsTable tbody').empty();

              currentOrderItems = JSON.parse(localStorage.getItem('form-add_inspection-items'));

              currentOrderItems.map(item => {

                var item_id = item.item_id;
                var row_no = item.row.row_no;

                // var product_id = item.row.product_id;
                // var product_name = item.row.product_name;
                // var product_quantity = item.row.product_quantity;

                var inspection_item_sise = item.row.inspection_item_sise;
                var inspection_item_sample = item.row.inspection_item_sample;
                var inspection_item_tem = item.row.inspection_item_tem;
                var inspection_item_presion = item.row.inspection_item_presion;
                var inspection_item_ripe = item.row.inspection_item_ripe;
                var inspection_item_mold = item.row.inspection_item_mold;
                var inspection_item_clean = item.row.inspection_item_clean;
                var inspection_item_color = item.row.inspection_item_color;
                var inspection_item_firm = item.row.inspection_item_firm;
                var inspection_item_mechanical_damage = item.row.inspection_item_mechanical_damage;
                var inspection_item_weight = item.row.inspection_item_weight;
                var inspection_item_scars = item.row.inspection_item_scars;
                var inspection_item_over_ripe = item.row.inspection_item_over_ripe;
                var inspection_item_total = item.row.inspection_item_total;

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
                            <input name="item_temp_id[]" style="text-align: center; width: 100%;" readonly type="hidden" class="rid" value="${item_id}">
                        </td>`;
                    tr_html +=
                        `<td align="center">
                            <input name="inspection_item_sise[]" style="text-align: center; width: 100%;" readonly type="text" class="rid" value="${inspection_item_sise}">
                        </td>`;
                    tr_html +=
                        `<td align="center">
                            <input name="inspection_item_sample[]" style="text-align: center; width: 100%;" readonly type="text" class="rid" value="${inspection_item_sample}">
                        </td>`;
                    tr_html +=
                        `<td align="center">
                            <input name="inspection_item_tem[]" style="text-align: center; width: 100%;" readonly type="text" class="rid" value="${inspection_item_tem}">
                        </td>`;
                    tr_html +=
                        `<td align="center">
                            <input name="inspection_item_presion[]" style="text-align: center; width: 100%;" readonly type="text" class="rid" value="${inspection_item_presion}">
                        </td>`;
                    tr_html +=
                        `<td align="center">
                            <input name="inspection_item_ripe[]" style="text-align: center; width: 100%;" readonly type="text" class="rid" value="${inspection_item_ripe}">
                        </td>`;
                    tr_html +=
                        `<td align="center">
                            <input name="inspection_item_mold[]" style="text-align: center; width: 100%;" readonly type="text" class="rid" value="${inspection_item_mold}">
                        </td>`;
                    tr_html +=
                        `<td align="center">
                            <input name="inspection_item_clean[]" style="text-align: center; width: 100%;" readonly type="text" class="rid" value="${inspection_item_clean}">
                        </td>`;
                    tr_html +=
                        `<td align="center">
                            <input name="inspection_item_color[]" style="text-align: center; width: 100%;" readonly type="text" class="rid" value="${inspection_item_color}">
                        </td>`;
                    tr_html +=
                        `<td align="center">
                            <input name="inspection_item_firm[]" style="text-align: center; width: 100%;" readonly type="text" class="rid" value="${inspection_item_firm}">
                        </td>`;
                    tr_html +=
                        `<td align="center">
                            <input name="inspection_item_mechanical_damage[]" style="text-align: center; width: 100%;" readonly type="text" class="rid" value="${inspection_item_mechanical_damage}">
                        </td>`;
                    tr_html +=
                        `<td align="center">
                            <input name="inspection_item_weight[]" style="text-align: center; width: 100%;" readonly type="text" class="rid" value="${inspection_item_weight}">
                        </td>`;
                    tr_html +=
                        `<td align="center">
                            <input name="inspection_item_scars[]" style="text-align: center; width: 100%;" readonly type="text" class="rid" value="${inspection_item_scars}">
                        </td>`;
                    tr_html +=
                        `<td align="center">
                            <input name="inspection_item_over_ripe[]" style="text-align: center; width: 100%;" readonly type="text" class="rid" value="${inspection_item_over_ripe}">
                        </td>`;
                    tr_html +=
                        `<td align="center">
                            <input name="inspection_item_total[]" style="text-align: center; width: 100%;" readonly type="text" class="rid" value="${inspection_item_total}">
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
                newTr.prependTo('#inspectionItemsTable');

              })
            }

        }


        // *********************************************************************
        // RESET ITEM DETAILS - MODAL FORM RESET BUTTON
        // *********************************************************************

        $(document).on('click', '#resetItemModalButton', function(e) {
            $('#inspection_item_sise').val("");
            $('#inspection_item_sample').val("");
            $('#inspection_item_tem').val("");
            $('#inspection_item_presion').val("");
            $('#inspection_item_ripe').val("");
            $('#inspection_item_mold').val("");
            $('#inspection_item_clean').val("");
            $('#inspection_item_color').val("");
            $('#inspection_item_firm').val("");
            $('#inspection_item_mechanical_damage').val("");
            $('#inspection_item_weight').val("");
            $('#inspection_item_scars').val("");
            $('#inspection_item_over_ripe').val("");
            $('#inspection_item_total').val("");
        });

        // *********************************************************************
        // ADD ITEM TO ORDER - MODAL FORM SUBMIT BUTTON
        // *********************************************************************

        $(document).on('click', '#addInspectionItemButton', function(e) {
            event.preventDefault();

            // var createdAt = new Date().getTime();
            // var inspection_product_id = $('#inspection_product_id').val();
            // var inspection_product_qty = $('#inspection_product_qty').val();

            var inspection_item_sise = $('#inspection_item_sise').val();
            var inspection_item_sample = $('#inspection_item_sample').val();
            var inspection_item_tem = $('#inspection_item_tem').val();
            var inspection_item_presion = $('#inspection_item_presion').val();
            var inspection_item_ripe = $('#inspection_item_ripe').val();
            var inspection_item_mold = $('#inspection_item_mold').val();
            var inspection_item_clean = $('#inspection_item_clean').val();
            var inspection_item_color = $('#inspection_item_color').val();
            var inspection_item_firm = $('#inspection_item_firm').val();
            var inspection_item_mechanical_damage = $('#inspection_item_mechanical_damage').val();
            var inspection_item_weight = $('#inspection_item_weight').val();
            var inspection_item_scars = $('#inspection_item_scars').val();
            var inspection_item_over_ripe = $('#inspection_item_over_ripe').val();
            var inspection_item_total = $('#inspection_item_total').val();

            // if (inspection_product_id && inspection_product_qty) {

                var currentOrderItems2 = JSON.parse(localStorage.getItem('form-add_inspection-items'));

                var rowCount = localStorage.getItem('form-add_inspection-items_rows_count');
                if (rowCount === null || rowCount === undefined) {
                  rowCount = 1;
                } else {
                  rowCount++;
                }
                localStorage.setItem('form-add_inspection-items_rows_count', rowCount);

                // var productsList = <?php echo json_encode($products); ?>;
                //
                // let prodName = "";
                // productsList.map(prod => {
                //   if (prod.id.toString() === inspection_product_id.toString()) {
                //     prodName = prod.name;
                //   }
                // })

                var orderItem = {
                    item_id: rowCount,
                    // createdAt,
                    row: {
                      row_no: rowCount,
                      // product_id: inspection_product_id,
                      // product_name: prodName,
                      // product_quantity: inspection_product_qty,
                      inspection_item_sise,
                      inspection_item_sample,
                      inspection_item_tem,
                      inspection_item_presion,
                      inspection_item_ripe,
                      inspection_item_mold,
                      inspection_item_clean,
                      inspection_item_color,
                      inspection_item_firm,
                      inspection_item_mechanical_damage,
                      inspection_item_weight,
                      inspection_item_scars,
                      inspection_item_over_ripe,
                      inspection_item_total
                    },
                    options: false,
                };

                let updatedOrderItems = [];
                updatedOrderItems.push(...currentOrderItems2);
                updatedOrderItems.push(orderItem);

                localStorage.setItem('form-add_inspection-items', JSON.stringify(updatedOrderItems));
                loadSupplyOrderItems();
            // }

            $('#addInspectionItemsModal').modal('hide');

            $('#inspection_product_qty').val('');
            return false;
        });

        // *********************************************************************
        // REMOVE ORDER ITEM - REMOVE ROW FROM TABLE AND OBJ FROM localStorage
        // *********************************************************************

        function removeSupplyOrderItem(rowNo) {
          // console.log("REMOVING ROW: " + rowNo);
          var orderItems = JSON.parse(localStorage.getItem('form-add_inspection-items'));
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
                localStorage.setItem('form-add_inspection-items', JSON.stringify(orderItems));
                let trID = `#row_${rowNo}`;
                $(trID).remove();
            }
          })
        }
        window.removeSupplyOrderItem =  removeSupplyOrderItem;

        // *********************************************************************
        // RESET ORDER
        // *********************************************************************

        $(document).on('click', '#inspection_items-reset_button', function(e) {
          event.preventDefault();
          localStorage.clear();
          location.reload();
        });

        //

        $(document).on('change', '#supplier', function(e) {

          let val = $('#form-add_inspection-supplier').val();

          console.log($(this).val());
          console.log("chaing");

          // localStorage.setItem('form-add_inspection-supplier', JSON.stringify(val));
          localStorage.setItem('form-add_inspection-supplier', val);

        });

        // // $(document).on('change', '#form-add_inspection-message_to_supplier', function(e) {
        // $(document).on('change', '#supplier', function(e) {
        //   // console.log($(this).val());
        //   // console.log("chaing");
        //
        //   // let val = $('#supplier').val();
        //   let val = $(this).val();
        //
        //   // // localStorage.setItem('form-add_inspection-supplier', JSON.stringify(val));
        //   localStorage.setItem('form-add_inspection-supplier', val);
        //
        // });

        // $(document).on('change', '#form-add_inspection-message_to_supplier', function(e) {
        $(document).on('input', '#pallet_note', function(e) {
          // console.log($(this).val());
          console.log("chaing");
          // let val = $('#form-add_inspection-message_to_supplier').val();
          //
          // // localStorage.setItem('form-add_inspection-message_to_supplier', JSON.stringify(val));
          // localStorage.setItem('form-add_inspection-message_to_supplier', val);
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
        $(document).on('change', '#inspection_document', function(e) {
          console.log("inspection_document");
          // localStorage.setItem('inspection_document', JSON.stringify(val));
          localStorage.setItem('form-add_inspection-attachment', val);
        });

        // $('#form-add_inspection-supplier').on('change', function(e) {
        //   // alert($(this).val());
        //   console.log($(this).val());
        //   let val = $('#form-add_inspection-supplier').val();
        //   // localStorage.setItem('form-add_inspection-supplier', JSON.stringify(val));
        //   localStorage.setItem('form-add_inspection-supplier', val);
        // });
        //
        // // $('#form-add_inspection-message_to_supplier').on('change', function(e) {
        // $('#pallet_note').on('change', function(e) {
        //   // alert($(this).val());
        //   console.log($(this).val());
        //   console.log("chaing");
        //   let val = $('#form-add_inspection-message_to_supplier').val();
        //   // localStorage.setItem('form-add_inspection-message_to_supplier', JSON.stringify(val));
        //   localStorage.setItem('form-add_inspection-message_to_supplier', val);
        // });

        // *********************************************************************
        // *********************************************************************
        // *********************************************************************

    });
</script>
