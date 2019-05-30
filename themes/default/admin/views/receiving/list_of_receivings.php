<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style type="text/css" media="screen"></style>
<script>

    // Default DataTables Code, Leave as is... Starts here --->

    var oTable;
    $(document).ready(function () {
        oTable = $('#ReceivingsDataTable').dataTable({
            "aaSorting": [[2, "asc"], [3, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            <?php /*
            'sAjaxSource': '<?= admin_url('receiving/getSupplyOrdersLogic'.($warehouse_id ? '/'.$warehouse_id : '').($supplier ? '?supplier='.$supplier->id : '')) ?>',
            */ ?>
            'sAjaxSource': '<?= admin_url('receiving/handleGetReceiving_logic')?>',
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

            "aoColumns": [
                {"bSortable": false, "mRender": checkbox},
                null, null, null, null,
                {"bSortable": false,"mRender": img_hl}, // use this object to render the image
                {"bSortable": false,"mRender": attachment2}, // use this object to download the attachment
                null, null //, null
            ]

        })
        .fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[Date]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[Receiving No]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[Supply Order No]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[Manifest No]", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "[Image]", filter_type: "text", data: []},
            {column_number: 6, filter_default_label: "[Attachment]", filter_type: "text", data: []},
            {column_number: 7, filter_default_label: "[Comments]", filter_type: "text", data: []},
            // {column_number: 8, filter_default_label: "[Status]", filter_type: "text", data: []},
        ], "footer");
    });
</script>
<?php
// if ($Owner || $GP['bulk_actions']) {
//     echo admin_form_open('products/product_actions'.($warehouse_id ? '/'.$warehouse_id : ''), 'id="action-form"');
// }
?>
<div class="box">
    <div class="box-header">

        <!-- View - Header - Page Title -->

        <h2 class="blue">
          <?php /*
          <i class="fa-fw fa fa-barcode"></i><?= lang('products') . ' (' . ($warehouse_id ? $warehouse->name : lang('all_warehouses')) . ')'.($supplier ? ' ('.lang('supplier').': '.($supplier->company && $supplier->company != '-' ? $supplier->company : $supplier->name).')' : ''); ?>
          */ ?>
          <i class="fa-fw fa fa-barcode"></i> Receivings
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
                            <a href="<?= admin_url('receiving/addReceiving_view') ?>">
                              <?php /*  <i class="fa fa-plus-circle"></i> <?= lang('add_product') ?> */ ?>
                                <i class="fa fa-plus-circle"></i> <?= "Add Receiving" ?>
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

    <!-- Table Content -->

    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?= lang('list_results'); ?></p>

                <div class="table-responsive">
                    <table id="ReceivingsDataTable" class="table table-bordered table-condensed table-hover table-striped">

                      <!-- Table Header Row -->

                        <thead>
                        <tr class="primary">

                            <th style="min-width:30px; max-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkth" type="checkbox" name="check"/>
                            </th>

                            <th style="width:10%; text-align: center;">Date</th>
                            <th style="width:10%; text-align: center;">Receiving<br>Report No</th>
                            <th style="width:10%; text-align: center;">Supply<br>Order No</th>
                            <th style="width:10%; text-align: center;">Manifest<br>Ref No</th>
                            <th style="width:10%; text-align: center;">Image</th>
                            <th style="width:10%; text-align: center;">Attachment</th>
                            <th style="width:30%; text-align: center;">Comments</th>
                            <?php /* <th style="width:10%; text-align: center;">Status</th> */ ?>

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
                            <?php /* <th style="text-align: center;"></th> */ ?>

                            <th style="width:fit-content; text-align:center;"><?= lang("actions") ?></th>

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
        var totalColumns = $("#ReceivingsDataTable").find('tr')[0].cells.length;
        var totalRows = $('#ReceivingsDataTable tr').length;

        // GET COLUMN AND ROW CLICKED

        $('#ReceivingsDataTable tbody').on('click', 'td', function() {
            clickedColumn = $(this).parent().children().index($(this));
            clickedRow = $(this).parent().parent().children().index($(this).parent());
            // alert('Row: ' + clickedRow + ', Column: ' + clickedColumn);
        });

        // GET RECORD ID FOUND ON ROW CLICKED

        $('#ReceivingsDataTable tbody').on('click', 'tr', function() {
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
              window.location.href = site.base_url + 'receiving/viewReceiving_view/' + itemID;
              // EDIT ITEMID
              // window.location.href = site.base_url + 'receiving/editSupplyOrder/' + itemID;
            }
          }

        });

        // *********************************************************************
        // DISPLAY HAND CURSOR OR POINTER WHEN HOVERING ON TABLE
        // *********************************************************************

        $('#ReceivingsDataTable tbody').css( 'cursor', 'pointer' );

        // for old IE browsers
        $('#ReceivingsDataTable tbody').css( 'cursor', 'hand' );

        // *********************************************************************
        // *********************************************************************
        // *********************************************************************

    });
</script>
