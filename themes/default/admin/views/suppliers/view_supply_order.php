<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style type="text/css" media="screen">
    <?php /*
    #ViewSupplyOrderItemsTable td:nth-child(7) {
        text-align: right;
    }
    <?php if($Owner || $Admin || $this->session->userdata('show_cost')) { ?>
    #ViewSupplyOrderItemsTable td:nth-child(9) {
        text-align: right;
    }
    <?php } if($Owner || $Admin || $this->session->userdata('show_price')) { ?>
    #ViewSupplyOrderItemsTable td:nth-child(8) {
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

    // convert php arrays to javascript arrays
    var productsArray = <?php echo json_encode($products); ?>;

    var oTable;
    $(document).ready(function () {

        console.log("Data Passed to this View");
        console.log('<?php echo $supply_order; ?>');
        console.log('<?php echo $supply_order_id; ?>');
        console.log('<?php echo $supply_order_number; ?>');
        console.log('<?php echo $created_at; ?>');
        console.log('<?php echo $supplier_id; ?>');
        console.log('<?php echo $supplier_company; ?>');
        console.log('<?php echo $message_to_supplier; ?>');
        console.log('<?php echo $message_to_receiver; ?>');
        console.log('<?php echo $image; ?>');
        console.log('<?php echo $attachment; ?>');

        oTable = $('#ViewSupplyOrderItemsTable').dataTable({
            "aaSorting": [[2, "asc"], [3, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            <?php /*
            'sAjaxSource': '<?= admin_url('suppliers/getSupplyOrdersLogic'.($warehouse_id ? '/'.$warehouse_id : '').($supplier ? '?supplier='.$supplier->id : '')) ?>',
            */ ?>
            'sAjaxSource': '<?= admin_url('suppliers/handleGetSupplyOrderItems_logic' . ($supply_order_id ? '/' . $supply_order_id : ''))?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                addRowItem(aData);

                console.log(">------------------------------");

                // $this = table; table.id
                let tableID = $(this)[0].id;

                // console.log("Row ID");
                let rowID = aData[0];
                // console.log(aData[0]);

                // console.log("nRow");
                // console.log(nRow);

                $(nRow).each(function(i) {

                    $("td", this).each(function(j) {
                      console.log("".concat("row: ", i, ", col: ", j, ", value: ", $(this).text()));

                      // console.log("td new id name:");
                      let tdID = tableID + "_" + "row" + rowID + "_" + "col" + j;
                      // console.log(tdID);

                      // change text here... every td text will change
                      // $(this).text("testText");
                      var tdCell = $(this);

                      console.log("j");
                      console.log(j);

                      if (j === 1) {

                        if (productsArray !== null || productsArray !== undefined || productsArray !== 0) {

                          productsArray.map(product => {
                              console.log("product");
                              console.log(product);
                              if ($(this).text().toString() === product.id.toString()) {
                                tdCell.text(product.name);
                              }
                          });

                        }

                      }

                      // add/change this element's id
                      // $(this).attr('id', tdID);

                    });

                });

                console.log("<------------------------------");

                // var oSettings = oTable.fnSettings();
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
                null, // product column
                null, // quantity column
                // UNHIDE ACTIONS COLUMN
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

        // Each Row Item is an Array of Values
        // See them below:
        let rowItems = [];
        function addRowItem(rowItem) {
            if (rowItem && rowItem.length > 0) {
                rowItems.push(rowItem);
                // rowItem.map(item => {
                //     console.log("item");
                //     console.log(item);
                // })
            }
        };
        console.log("rowItems are:");
        console.log(rowItems);

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
          <i class="fa-fw fa fa-barcode"></i> Supply Order <?php echo $supply_order_number; ?>
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

                <div class="table-responsive">
                    <table id="SupplyOrderTable" class="table table-bordered table-condensed table-hover table-striped">

                      <!-- Table Header Row -->

                        <thead>
                        <tr class="primary">

                          <th style="width:30%; text-align: center;">Supply Order No</th>
                          <th style="width:30%; text-align: center;">Supplier</th>
                          <th style="width:30%; text-align: center;">Date</th>
                          <th style="width:30%; text-align: center;">Image</th>
                          <th style="width:30%; text-align: center;">Attachment</th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="width:30%; text-align: center;" class="dataTables_empty"><?php echo $supply_order_number; ?></td>
                            <td style="width:30%; text-align: center;" class="dataTables_empty"><?php echo $supplier_company; ?></td>
                            <td style="width:30%; text-align: center;" class="dataTables_empty"><?php echo $created_at; ?></td>
                            <!-- <td style="width:30%; text-align: center;" class="dataTables_empty"><?php echo $image; ?></td> -->
                            <td style="width:10%; text-align: center;" class="dataTables_empty">
                                <button type="button" style="border:none;" data-toggle="modal" data-target="#myModal-img">
                                    <img src="<?php echo "/assets/uploads/" . $image ?>" alt="" border="1" height="30" width="30" />
                                </button>
                            </td>
                            <td style="width:30%; text-align: center;" class="dataTables_empty"><?php echo $attachment; ?></td>
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
                    <table id="ViewSupplyOrderItemsTable" class="table table-bordered table-condensed table-hover table-striped">

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

<!-- IMAGE MODAL -->

<div class="modal fade" id="myModal-img" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width: 350px;">
    <div class="modal-content" style="width: 330px;">
      <div class="modal-header" style="display:none">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">null</h4>
      </div>
      <div class="modal-body">
        <div class="ekko-lightbox-container">
          <div>
            <img src="<?php echo "/assets/uploads/" . $image ?>" style="max-width: 100%;">
          </div>
        </div>
      </div>
      <div class="modal-footer" style="display:none">null</div>
    </div>
  </div>
</div>

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
        // var totalColumns = $("#ViewSupplyOrderItemsTable").find('tr')[0].cells.length;
        // var totalRows = $('#ViewSupplyOrderItemsTable tr').length;
        //
        // // GET COLUMN AND ROW CLICKED
        //
        // $('#ViewSupplyOrderItemsTable tbody').on('click', 'td', function() {
        //     clickedColumn = $(this).parent().children().index($(this));
        //     clickedRow = $(this).parent().parent().children().index($(this).parent());
        //     // alert('Row: ' + clickedRow + ', Column: ' + clickedColumn);
        // });
        //
        // // GET RECORD ID FOUND ON ROW CLICKED
        //
        // $('#ViewSupplyOrderItemsTable tbody').on('click', 'tr', function() {
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
        // $('#ViewSupplyOrderItemsTable tbody').css( 'cursor', 'pointer' );
        //
        // // for old IE browsers
        // $('#ViewSupplyOrderItemsTable tbody').css( 'cursor', 'hand' );
        //
        // // *********************************************************************
        // // *********************************************************************
        // // *********************************************************************

    });
</script>
