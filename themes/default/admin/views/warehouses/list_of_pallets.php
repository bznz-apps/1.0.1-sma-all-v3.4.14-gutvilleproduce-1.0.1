<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style type="text/css" media="screen"></style>
<script>

    // Default DataTables Code, Leave as is... Starts here --->

    // convert php arrays to javascript arrays
    var receivingsArray = <?php echo json_encode($receivings); ?>;
    var warehousesArray = <?php echo json_encode($warehouses); ?>;
    var racksArray = <?php echo json_encode($racks); ?>;

    var oTable;
    $(document).ready(function () {
        oTable = $('#PalletsDataTable').dataTable({
            // "aaSorting": [[2, "asc"], [3, "asc"]],
            "aaSorting": [[1, "desc"]], // array of [row_number, sorting_asc_or_desc]
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            <?php /*
            'sAjaxSource': '<?= admin_url('receiving/getSupplyOrdersLogic'.($warehouse_id ? '/'.$warehouse_id : '').($supplier ? '?supplier='.$supplier->id : '')) ?>',
            */ ?>
            'sAjaxSource': '<?= admin_url('warehouses/handleGetPallets_logic')?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {


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

                    if (j === 4) {

                      if (receivingsArray !== null || receivingsArray !== undefined || receivingsArray !== 0) {

                        receivingsArray.map(receiving => {
                            console.log("receiving");
                            console.log(receiving);
                            if ($(this).text().toString() === receiving.id.toString()) {
                              tdCell.text(receiving.receiving_report_number);
                            }
                        });

                      }

                    };
                    if (j === 6) {

                      if (warehousesArray !== null || warehousesArray !== undefined || warehousesArray !== 0) {

                        warehousesArray.map(warehouse => {
                            console.log("warehouse");
                            console.log(warehouse);
                            if ($(this).text().toString() === warehouse.id.toString()) {
                              tdCell.text(warehouse.name);
                            }
                        });

                      }

                    };
                    if (j === 7) {

                      if (racksArray !== null || racksArray !== undefined || racksArray !== 0) {

                        racksArray.map(rack => {
                            console.log("rack");
                            console.log(rack);
                            if ($(this).text().toString() === rack.id.toString()) {
                              tdCell.text(rack.name);
                            }
                        });

                      }

                    };

                    // add/change this element's id
                    // $(this).attr('id', tdID);

                  });

              });

              console.log("<------------------------------");


                // var oSettings = oTable.fnSettings();
                nRow.id = aData[0];
                nRow.className = "pallet_link";
                nRow.style = "text-align: center;";
                // nRow.className = "product_link";
                //if(aData[7] > aData[9]){ nRow.className = "product_link warning"; } else { nRow.className = "product_link"; }

                // console.log("aData");
                // console.log(aData);
                //
                //   console.log("Column 4 - Receiving");
                //   console.log(aData[4]);
                //   console.log("Column 6 - Warehouse");
                //   console.log(aData[6]);
                //   console.log("Column 7 - Rack");
                //   console.log(aData[7]);
                //
                //   aData[4] = "tes";
                //   aData[6] = "tes";
                //   aData[7] = "tes";


                // aData.map((row, index) => {
                //   console.log("Row ");
                //   console.log("Column 4 - Receiving");
                //   console.log("Column 6 - Warehouse");
                //   console.log("Column 7 - Rack");
                // })

                // console.log("aData[0]");
                // console.log(aData[0]);

                // HERE ACCESS ROW VALUES

                // console.log("nRow");
                // console.log(nRow);

                // console.log("nRow.cells");
                // console.log(nRow.cells);

                // var cellsArr = [...nRow.cells];

                // console.log(nRow.cells[4]); // receiving
                // console.log(nRow.cells[6]); // warehouse
                // console.log(nRow.cells[7]); // rack
                //
                // nRow.cells[4].value = "receiving";
                // nRow.cells[6].value = "warehouse";
                // nRow.cells[7].value = "rack";

                // console.log("nRow.cells[4].id");
                // console.log(nRow.cells[4]);

                // var row_index = $('td').parent().index();
                // var col_index = $('td').index();

                // Add ID tag to TD element
                // $(nRow.cells[4]).attr('id', 'test');
                // console.log(nRow.cells[4].val);

                // console.log(cellsArr);
                //
                // cellsArr.map((cell, index) => {
                //     // console.log("Cell with Index: " + index);
                //     // console.log(cell);
                //     $("#PalletsDataTable > tbody > tr > td").val("test");
                // })

                // let nRows = nRow.cells;
                // nRows.map(cell => {
                //   console.log("cell");
                //   console.log(cell);
                // })

                // https://stackoverflow.com/questions/31975437/searching-for-a-value-in-an-html-table-and-change-its-td-value
                // https://stackoverflow.com/questions/4996521/jquery-selecting-each-td-in-a-tr

                // $('#PalletsDataTable > tbody').each(function(index) {
                // });

                $('#PalletsDataTable > tbody').each(function(index) {
                    console.log("----------------------- <");
                    console.log("tbody tr rows");
                    console.log("$(this).children().context.children");
                    console.log($(this).children().context.children);
                    console.log($(this).children().context.children[0]);
                    console.log($(this).children().context.children[1]);
                    console.log($(this).children().context.children[2]);
                    console.log($(this).children().context.children.cells);
                    var trs = $(this).children().context.children;
                    var rowsArr = [...trs];
                });

                return nRow;
            },

            // Default DataTables Code, Leave as is... Ends here <---

            "aoColumns": [
                {"bSortable": false, "mRender": checkbox},
                null, null, null, null, null, null, null,
                {"bSortable": false,"mRender": img_hl}, // use this object to render the image
                null, null
            ]

        })
        .fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[Date]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[Code]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[Supply Order No]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[Receiving Ref No]", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "[Manifest Ref No]", filter_type: "text", data: []},
            {column_number: 6, filter_default_label: "[Warehouse]", filter_type: "text", data: []},
            {column_number: 7, filter_default_label: "[Rack]", filter_type: "text", data: []},
            {column_number: 8, filter_default_label: "[Image]", filter_type: "text", data: []},
            {column_number: 9, filter_default_label: "[Attachment]", filter_type: "text", data: []},
        ], "footer");
    });
</script>

<script>
    $(document).ready(function () {

        // function mapTable() {
        //     var tableBody = document.getElementById("#PalletsDataTable");
        //     console.log(tableBody);
        //
        //     // Reset the table
        //     // tableBody.innerHTML = "";
        //
        //     // Build the new table
        //     tableBody.forEach(function(row) {
        //         // var newRow = document.createElement("tr");
        //         // tableBody.appendChild(newRow);
        //         console.log("row");
        //         console.log(row);
        //
        //         if (row instanceof Array) {
        //             row.forEach(function(cell) {
        //                 // var newCell = document.createElement("td");
        //                 // newCell.textContent = cell;
        //                 // newRow.appendChild(newCell);
        //                 console.log("cell");
        //                 console.log(cell);
        //             });
        //         } else {
        //             // newCell = document.createElement("td");
        //             // newCell.textContent = row;
        //             // newRow.appendChild(newCell);
        //         }
        //     });
        // }
        // mapTable();

        // var test = $("#PalletsDataTable tbody tr").map(function () {
        //     console.log("========================");
        //     var $row = $(this);
        //     console.log($row);
        // }).get();

        $('#PalletsDataTable > tbody').each(function(index) {

            // console.log("-----------------------");
            //
            // console.log("tBody");
            // console.log("$(this)");
            // console.log($(this));
            //
            // console.log("-----------------------");
            //
            // console.log("tBody children");
            // console.log("$(this).children()");
            // console.log($(this).children());

            // console.log("----------------------- <");
            // console.log("tbody tr rows");
            // console.log("$(this).children().context.children");
            // console.log($(this).children().context.children);
            // console.log($(this).children().context.children[0]);
            // console.log($(this).children().context.children[1]);
            // console.log($(this).children().context.children[2]);
            // console.log($(this).children().context.children.cells);
            // var trs = $(this).children().context.children;
            // var rowsArr = [...trs];

            // console.log("-----------------------");
            // $($(this).children().context.children).each(function(index) {
            //     console.log("row index: " + index);
            //     console.log($(this));
            // });

            // console.log("-----------------------");
            // rowsArr.map((tr, index) => {
            //     console.log("row index: " + index);
            //     console.log(tr);
            // });

            // context: tbody
            // childNodes
            // 0
            // cells
            // 0, 1, 2, ...
            // 2
            // firstChild
            // data


            // console.log("index");
            // console.log(index);

            // var firstTd = $(this).find('td:first');
            // console.log("firstTd");
            // console.log(firstTd);

            // console.log("td value");
            // console.log($(this).val());

            // $(this).children('td').each (function(a) {
            //     console.log("td value");
            //     console.log(a);
            // });

            // $(this).find('td').each (function() {
            //     console.log("td value");
            //     console.log($(this).val());
            // });

            // $.each(this.cells, function(){
            //     console.log("td value");
            //     console.log($(this).val());
            // });

        });
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
          <i class="fa-fw fa fa-barcode"></i> Pallets
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
                            <a href="<?= admin_url('warehouses/addPallet_view') ?>">
                              <?php /*  <i class="fa fa-plus-circle"></i> <?= lang('add_product') ?> */ ?>
                                <i class="fa fa-plus-circle"></i> <?= "Add Pallet" ?>
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
                    <table id="PalletsDataTable" class="table table-bordered table-condensed table-hover table-striped">

                      <!-- Table Header Row -->

                        <thead>
                        <tr class="primary">

                          <th style="min-width:30px; max-width:30px; width: 30px; text-align: center;">
                              <input class="checkbox checkth" type="checkbox" name="check"/>
                          </th>
                          <th style="width:10%; text-align: center;">Date</th>
                          <th style="width:10%; text-align: center;">Code</th>
                          <th style="width:10%; text-align: center;">Supply<br>Order No</th>
                          <th style="width:10%; text-align: center;">Receiving<br>Ref No</th>
                          <th style="width:10%; text-align: center;">Manifest<br>Ref No</th>
                          <th style="width:10%; text-align: center;">Warehouse</th>
                          <th style="width:10%; text-align: center;">Rack</th>
                          <th style="width:10%; text-align: center;">Image</th>
                          <th style="width:10%; text-align: center;">Attachment</th>
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
                          <th style="text-align: center;"></th>
                          <th style="text-align: center;"></th>
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
        var totalColumns = $("#PalletsDataTable").find('tr')[0].cells.length;
        var totalRows = $('#PalletsDataTable tr').length;

        // GET COLUMN AND ROW CLICKED

        $('#PalletsDataTable tbody').on('click', 'td', function() {
            clickedColumn = $(this).parent().children().index($(this));
            clickedRow = $(this).parent().parent().children().index($(this).parent());
            // alert('Row: ' + clickedRow + ', Column: ' + clickedColumn);
        });

        // GET RECORD ID FOUND ON ROW CLICKED

        $('#PalletsDataTable tbody').on('click', 'tr', function() {
          // console.log('Clicked Row Info:');
          // console.log($(this));

          // console.log(totalRows);

          // console.log("Row Number is: " + clickedRow);

          var itemID = $(this)[0].id;

          // NAVIGATE ONLY IF CLICKED COLUMN WAS NOT THE LAST COLUMN THAT HAS ACTIONS
          // AND IF THE CLICKED COLUMN IS NOT THE ONE THAT HAS IMAGE OR ATTACHMENT
          if (clickedColumn !== totalColumns-1 && clickedColumn !== totalColumns-2 && clickedColumn !== totalColumns-3) {
            // ROW MUST HAVE A RECORD ID VALUE IN ITS CONTENT
            if (itemID !== "") {
              // PREVIEW ITEMID
              window.location.href = site.base_url + 'warehouses/viewPallet_view/' + itemID;
              // EDIT ITEMID
              // window.location.href = site.base_url + 'receiving/editSupplyOrder/' + itemID;
            }
          }

        });

        // *********************************************************************
        // DISPLAY HAND CURSOR OR POINTER WHEN HOVERING ON TABLE
        // *********************************************************************

        $('#PalletsDataTable tbody').css( 'cursor', 'pointer' );

        // for old IE browsers
        $('#PalletsDataTable tbody').css( 'cursor', 'hand' );

        // *********************************************************************
        // *********************************************************************
        // *********************************************************************

    });
</script>
