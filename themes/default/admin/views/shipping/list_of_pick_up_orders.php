<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style type="text/css" media="screen">
    <?php /*
    #PickUpOrdersDataTable td:nth-child(7) {
        text-align: right;
    }
    <?php if($Owner || $Admin || $this->session->userdata('show_cost')) { ?>
    #PickUpOrdersDataTable td:nth-child(9) {
        text-align: right;
    }
    <?php } if($Owner || $Admin || $this->session->userdata('show_price')) { ?>
    #PickUpOrdersDataTable td:nth-child(8) {
        text-align: right;
    }
    <?php } ?>
    */ ?>
</style>
<script>

    // Default DataTables Code, Leave as is... Starts here --->

    var oTable;
    $(document).ready(function () {
        oTable = $('#PickUpOrdersDataTable').dataTable({
            // "aaSorting": [[2, "asc"], [3, "asc"]],
            "aaSorting": [[1, "desc"]], // array of [row_number, sorting_asc_or_desc]
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            <?php /*
            'sAjaxSource': '<?= admin_url('suppliers/getSupplyOrdersLogic'.($warehouse_id ? '/'.$warehouse_id : '').($supplier ? '?supplier='.$supplier->id : '')) ?>',
            */ ?>
            'sAjaxSource': '<?= admin_url('shipping/handleGetPickUpOrders_logic')?>',
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
                null, null, null, null, null, null, null, null

                <?php /*

                {"bSortable": false,"mRender": img_hl},
                null, null, null, null,
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
            {column_number: 1, filter_default_label: "[Date]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[Pick Up Order No]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[Sale No]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[Customer PO]", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "[Order No]", filter_type: "text", data: []},
            {column_number: 6, filter_default_label: "[Sold To]", filter_type: "text", data: []},
            {column_number: 7, filter_default_label: "[Ship To]", filter_type: "text", data: []},
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
<?php if ($Owner || $GP['bulk_actions']) {
    echo admin_form_open('products/product_actions'.($warehouse_id ? '/'.$warehouse_id : ''), 'id="action-form"');
} ?>
<div class="box">
    <div class="box-header">

        <!-- View - Header - Page Title -->

        <h2 class="blue">
          <?php /*
          <i class="fa-fw fa fa-barcode"></i><?= lang('products') . ' (' . ($warehouse_id ? $warehouse->name : lang('all_warehouses')) . ')'.($supplier ? ' ('.lang('supplier').': '.($supplier->company && $supplier->company != '-' ? $supplier->company : $supplier->name).')' : ''); ?>
          */ ?>
          <i class="fa-fw fa fa-barcode"></i> Pick Up Orders
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
                            <a href="<?= admin_url('shipping/addPickUpOrder_view') ?>">
                              <?php /*  <i class="fa fa-plus-circle"></i> <?= lang('add_product') ?> */ ?>
                                <i class="fa fa-plus-circle"></i> <?= "Add Pick Up Order" ?>
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

    <!-- Table Content -->

    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?= lang('list_results'); ?></p>

                <div class="table-responsive">
                    <table id="PickUpOrdersDataTable" class="table table-bordered table-condensed table-hover table-striped">

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
                          <th style="width:14%; text-align: center;">Date</th>
                          <th style="width:14%; text-align: center;">Pick Up Order No</th>
                          <th style="width:14%; text-align: center;">Sale No</th>
                          <th style="width:14%; text-align: center;">Customer PO</th>
                          <th style="width:14%; text-align: center;">Order No</th>
                          <th style="width:14%; text-align: center;">Sold To</th>
                          <th style="width:14%; text-align: center;">Ship To</th>
                          <th style="width:fit-content; text-align:center;"><?= lang("actions") ?></th>

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
                          <th style="text-align: center;"></th>
                          <th style="text-align: center;"></th>
                          <th style="width:fit-content; text-align:center;"><?= lang("actions") ?></th>

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

        // *********************************************************************
        //
        // TABLE ROW ACTIONS
        //
        // *********************************************************************

        // GET TABLE COLUMNS AND ROWS INFORMATION

        var clickedColumn = null;
        var clickedRow = null;
        var totalColumns = $("#PickUpOrdersDataTable").find('tr')[0].cells.length;
        var totalRows = $('#PickUpOrdersDataTable tr').length;

        // GET COLUMN AND ROW CLICKED

        $('#PickUpOrdersDataTable tbody').on('click', 'td', function() {
            clickedColumn = $(this).parent().children().index($(this));
            clickedRow = $(this).parent().parent().children().index($(this).parent());
            // alert('Row: ' + clickedRow + ', Column: ' + clickedColumn);
        });

        // GET RECORD ID FOUND ON ROW CLICKED

        $('#PickUpOrdersDataTable tbody').on('click', 'tr', function() {
          // console.log('Clicked Row Info:');
          // console.log($(this));

          // console.log(totalRows);

          // console.log("Row Number is: " + clickedRow);

          var itemID = $(this)[0].id;

          // NAVIGATE ONLY IF CLICKED COLUMN WAS NOT THE LAST COLUMN
          if (clickedColumn !== totalColumns-1) {
            // ROW MUST HAVE A RECORD ID VALUE IN ITS CONTENT
            if (itemID !== "") {
              // PREVIEW ITEMID
              window.location.href = site.base_url + 'shipping/viewPickUpOrder_view/' + itemID;
              // EDIT ITEMID
              // window.location.href = site.base_url + 'suppliers/editSupplyOrder/' + itemID;
            }
          }

        });

        // *********************************************************************
        // DISPLAY HAND CURSOR OR POINTER WHEN HOVERING ON TABLE
        // *********************************************************************

        $('#PickUpOrdersDataTable tbody').css( 'cursor', 'pointer' );

        // for old IE browsers
        $('#PickUpOrdersDataTable tbody').css( 'cursor', 'hand' );

        // *********************************************************************
        // *********************************************************************
        // *********************************************************************

    });
</script>
