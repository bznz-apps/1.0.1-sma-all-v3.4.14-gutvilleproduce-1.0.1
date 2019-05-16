<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script type="text/javascript">
    var count = 1, an = 1, product_variant = 0, DT = <?= $Settings->default_tax_rate ?>,
        product_tax = 0, invoice_tax = 0, product_discount = 0, order_discount = 0, total_discount = 0, total = 0, allow_discount = <?= ($Owner || $Admin || $this->session->userdata('allow_discount')) ? 1 : 0; ?>,
        tax_rates = <?php echo json_encode($tax_rates); ?>;
    //var audio_success = new Audio('<?=$assets?>sounds/sound2.mp3');
    //var audio_error = new Audio('<?=$assets?>sounds/sound3.mp3');
    $(document).ready(function () {
        if (localStorage.getItem('remove_slls')) {
            if (localStorage.getItem('slitems')) {
                localStorage.removeItem('slitems');
            }
            if (localStorage.getItem('sldiscount')) {
                localStorage.removeItem('sldiscount');
            }
            if (localStorage.getItem('sltax2')) {
                localStorage.removeItem('sltax2');
            }
            if (localStorage.getItem('slref')) {
                localStorage.removeItem('slref');
            }
            if (localStorage.getItem('slshipping')) {
                localStorage.removeItem('slshipping');
            }
            if (localStorage.getItem('slwarehouse')) {
                localStorage.removeItem('slwarehouse');
            }
            if (localStorage.getItem('slnote')) {
                localStorage.removeItem('slnote');
            }
            if (localStorage.getItem('slinnote')) {
                localStorage.removeItem('slinnote');
            }
            if (localStorage.getItem('slcustomer')) {
                localStorage.removeItem('slcustomer');
            }
            if (localStorage.getItem('slbiller')) {
                localStorage.removeItem('slbiller');
            }
            if (localStorage.getItem('slcurrency')) {
                localStorage.removeItem('slcurrency');
            }
            if (localStorage.getItem('sldate')) {
                localStorage.removeItem('sldate');
            }
            if (localStorage.getItem('slsale_status')) {
                localStorage.removeItem('slsale_status');
            }
            if (localStorage.getItem('slpayment_status')) {
                localStorage.removeItem('slpayment_status');
            }
            if (localStorage.getItem('paid_by')) {
                localStorage.removeItem('paid_by');
            }
            if (localStorage.getItem('amount_1')) {
                localStorage.removeItem('amount_1');
            }
            if (localStorage.getItem('paid_by_1')) {
                localStorage.removeItem('paid_by_1');
            }
            if (localStorage.getItem('pcc_holder_1')) {
                localStorage.removeItem('pcc_holder_1');
            }
            if (localStorage.getItem('pcc_type_1')) {
                localStorage.removeItem('pcc_type_1');
            }
            if (localStorage.getItem('pcc_month_1')) {
                localStorage.removeItem('pcc_month_1');
            }
            if (localStorage.getItem('pcc_year_1')) {
                localStorage.removeItem('pcc_year_1');
            }
            if (localStorage.getItem('pcc_no_1')) {
                localStorage.removeItem('pcc_no_1');
            }
            if (localStorage.getItem('cheque_no_1')) {
                localStorage.removeItem('cheque_no_1');
            }
            if (localStorage.getItem('payment_note_1')) {
                localStorage.removeItem('payment_note_1');
            }
            if (localStorage.getItem('slpayment_term')) {
                localStorage.removeItem('slpayment_term');
            }
            localStorage.removeItem('remove_slls');
        }
        <?php if($quote_id) { ?>
        // localStorage.setItem('sldate', '<?= $this->sma->hrld($quote->date) ?>');
        localStorage.setItem('slcustomer', '<?= $quote->customer_id ?>');
        localStorage.setItem('slbiller', '<?= $quote->biller_id ?>');
        localStorage.setItem('slwarehouse', '<?= $quote->warehouse_id ?>');
        localStorage.setItem('slnote', '<?= str_replace(array("\r", "\n"), "", $this->sma->decode_html($quote->note)); ?>');
        localStorage.setItem('sldiscount', '<?= $quote->order_discount_id ?>');
        localStorage.setItem('sltax2', '<?= $quote->order_tax_id ?>');
        localStorage.setItem('slshipping', '<?= $quote->shipping ?>');
        localStorage.setItem('slitems', JSON.stringify(<?= $quote_items; ?>));
        <?php } ?>
        <?php if($this->input->get('customer')) { ?>
        if (!localStorage.getItem('slitems')) {
            localStorage.setItem('slcustomer', <?=$this->input->get('customer');?>);
        }
        <?php } ?>
        <?php if ($Owner || $Admin) { ?>
        if (!localStorage.getItem('sldate')) {
            $("#sldate").datetimepicker({
                format: site.dateFormats.js_ldate,
                fontAwesome: true,
                language: 'sma',
                weekStart: 1,
                todayBtn: 1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                forceParse: 0
            }).datetimepicker('update', new Date());
        }
        $(document).on('change', '#sldate', function (e) {
            localStorage.setItem('sldate', $(this).val());
        });
        if (sldate = localStorage.getItem('sldate')) {
            $('#sldate').val(sldate);
        }
        <?php } ?>
        $(document).on('change', '#slbiller', function (e) {
            localStorage.setItem('slbiller', $(this).val());
        });
        if (slbiller = localStorage.getItem('slbiller')) {
            $('#slbiller').val(slbiller);
        }
        if (!localStorage.getItem('slref')) {
            localStorage.setItem('slref', '<?=$slnumber?>');
        }
        if (!localStorage.getItem('sltax2')) {
            localStorage.setItem('sltax2', <?=$Settings->default_tax_rate2;?>);
        }
        ItemnTotals();
        $('.bootbox').on('hidden.bs.modal', function (e) {
            $('#add_item').focus();
        });
        $("#add_item").autocomplete({
            source: function (request, response) {
                if (!$('#slcustomer').val()) {
                    $('#add_item').val('').removeClass('ui-autocomplete-loading');
                    bootbox.alert('<?=lang('select_above');?>');
                    $('#add_item').focus();
                    return false;
                }
                $.ajax({
                    type: 'get',
                    url: '<?= admin_url('sales/suggestions'); ?>',
                    dataType: "json",
                    data: {
                        term: request.term,
                        warehouse_id: $("#slwarehouse").val(),
                        customer_id: $("#slcustomer").val()
                    },
                    success: function (data) {
                        $(this).removeClass('ui-autocomplete-loading');
                        response(data);
                    }
                });
            },
            minLength: 1,
            autoFocus: false,
            delay: 250,
            response: function (event, ui) {
                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                    bootbox.alert('<?= lang('no_match_found') ?>', function () {
                        $('#add_item').focus();
                    });
                    $(this).removeClass('ui-autocomplete-loading');
                    $(this).removeClass('ui-autocomplete-loading');
                    $(this).val('');
                }
                else if (ui.content.length == 1 && ui.content[0].id != 0) {
                    ui.item = ui.content[0];
                    $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                    $(this).autocomplete('close');
                    $(this).removeClass('ui-autocomplete-loading');
                }
                else if (ui.content.length == 1 && ui.content[0].id == 0) {
                    bootbox.alert('<?= lang('no_match_found') ?>', function () {
                        $('#add_item').focus();
                    });
                    $(this).removeClass('ui-autocomplete-loading');
                    $(this).val('');
                }
            },
            select: function (event, ui) {
                event.preventDefault();
                if (ui.item.id !== 0) {
                    var row = add_invoice_item(ui.item);
                    if (row)
                        $(this).val('');
                } else {
                    bootbox.alert('<?= lang('no_match_found') ?>');
                }
            }
        });
        $(document).on('change', '#gift_card_no', function () {
            var cn = $(this).val() ? $(this).val() : '';
            if (cn != '') {
                $.ajax({
                    type: "get", async: false,
                    url: site.base_url + "sales/validate_gift_card/" + cn,
                    dataType: "json",
                    success: function (data) {
                        if (data === false) {
                            $('#gift_card_no').parent('.form-group').addClass('has-error');
                            bootbox.alert('<?=lang('incorrect_gift_card')?>');
                        } else if (data.customer_id !== null && data.customer_id !== $('#slcustomer').val()) {
                            $('#gift_card_no').parent('.form-group').addClass('has-error');
                            bootbox.alert('<?=lang('gift_card_not_for_customer')?>');

                        } else {
                            $('#gc_details').html('<small>Card No: ' + data.card_no + '<br>Value: ' + data.value + ' - Balance: ' + data.balance + '</small>');
                            $('#gift_card_no').parent('.form-group').removeClass('has-error');
                        }
                    }
                });
            }
        });
    });
</script>

<!-- ***************************************************************************

  VIEW PAGE CONTENT

**************************************************************************** -->

<div class="box">

    <!-- ***********************************************************************

      VIEW PAGE HEADER TITLE AND ICON

    ************************************************************************ -->

    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('add_sale'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <!-- ***********************************************************

                  VIEW PAGE INSTRUCTION MESSAGE

                ************************************************************ -->

                <p class="introtext"><?php echo lang('enter_info'); ?></p>

                <!-- ***********************************************************

                  CONTROLLER FUNCTION CALL

                ************************************************************ -->

                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo admin_form_open_multipart("sales/add", $attrib);
                if ($quote_id) {
                    echo form_hidden('quote_id', $quote_id);
                }
                ?>

                <!-- ***********************************************************
                *  ROW 1
                ************************************************************ -->

                <div class="row">
                    <div class="col-lg-12">

                        <!-- ***************************************************
                        *  DATE
                        **************************************************** -->

                        <?php if ($Owner || $Admin) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("date", "sldate"); ?>
                                    <?php echo form_input('date', (isset($_POST['date']) ? $_POST['date'] : ""), 'class="form-control input-tip datetime" id="sldate" required="required"'); ?>
                                </div>
                            </div>
                        <?php } ?>

                        <!-- ***************************************************
                        *  REFERENCE NO
                        **************************************************** -->

                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?= /* lang("reference_no", "slref"); */ "Customer Purchase Order" ?></label>
                                <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : $slnumber), 'class="form-control input-tip" id="slref"'); ?>
                            </div>
                        </div>

                        <!-- ***************************************************
                        *  BILLER
                        **************************************************** -->

                        <?php if ($Owner || $Admin || !$this->session->userdata('biller_id')) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("biller", "slbiller"); ?>
                                    <?php
                                    $bl[""] = "";
                                    foreach ($billers as $biller) {
                                        $bl[$biller->id] = $biller->company != '-' ? $biller->company : $biller->name;
                                    }
                                    echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : $Settings->default_biller), 'id="slbiller" data-placeholder="' . lang("select") . ' ' . lang("biller") . '" required="required" class="form-control input-tip select" style="width:100%;"');
                                    ?>
                                </div>
                            </div>
                        <?php } else {
                            $biller_input = array(
                                'type' => 'hidden',
                                'name' => 'biller',
                                'id' => 'slbiller',
                                'value' => $this->session->userdata('biller_id'),
                            );

                            echo form_input($biller_input);
                        } ?>

                        <div class="clearfix"></div>

                        <!-- ***************************************************
                        *  ROW 2
                        **************************************************** -->

                        <div class="col-md-12">
                            <div class="panel panel-warning">

                                <!-- *******************************************
                                *  WARNING_MESSAGE
                                ******************************************** -->

                                <div
                                    class="panel-heading"><?= lang('please_select_these_before_adding_product') ?></div>

                                <!-- *******************************************
                                *  WAREHOUSE
                                ******************************************** -->

                                <div class="panel-body" style="padding: 5px;">
                                    <?php if ($Owner || $Admin || !$this->session->userdata('warehouse_id')) { ?>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <?= lang("warehouse", "slwarehouse"); ?>
                                                <?php
                                                $wh[''] = '';
                                                foreach ($warehouses as $warehouse) {
                                                    $wh[$warehouse->id] = $warehouse->name;
                                                }
                                                echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : $Settings->default_warehouse), 'id="slwarehouse" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("warehouse") . '" required="required" style="width:100%;" ');
                                                ?>
                                            </div>
                                        </div>
                                    <?php } else {
                                        $warehouse_input = array(
                                            'type' => 'hidden',
                                            'name' => 'warehouse',
                                            'id' => 'slwarehouse',
                                            'value' => $this->session->userdata('warehouse_id'),
                                        );

                                        echo form_input($warehouse_input);
                                    } ?>

                                <!-- *******************************************
                                *  CUSTOMER
                                ******************************************** -->

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <?= "" /* lang("customer", "slcustomer"); */ ?>
                                            <label><?= /* lang("customer", "slcustomer"); */ "Bill To" ?> *</label>
                                            <!-- <div class="input-group"> -->
                                                <?php
                                                echo form_input('customer', (isset($_POST['customer']) ? $_POST['customer'] : ""), 'id="slcustomer" data-placeholder="' . lang("select") . ' ' . lang("customer") . '" required="required" class="form-control input-tip" style="width:100%;"');
                                                ?>
                                                <!--
                                                <div class="input-group-addon no-print" style="padding: 2px 8px; border-left: 0;">
                                                    <a href="#" id="toogle-customer-read-attr" class="external">
                                                        <i class="fa fa-pencil" id="addIcon" style="font-size: 1.2em;"></i>
                                                    </a>
                                                </div>
                                                <div class="input-group-addon no-print" style="padding: 2px 7px; border-left: 0;">
                                                    <a href="#" id="view-customer" class="external" data-toggle="modal" data-target="#myModal">
                                                        <i class="fa fa-eye" id="addIcon" style="font-size: 1.2em;"></i>
                                                    </a>
                                                </div>
                                                <?php "" /* if ($Owner || $Admin || $GP['customers-add']) { */ ?>
                                                <div class="input-group-addon no-print" style="padding: 2px 8px;">
                                                    <a href="<?= "" /* admin_url('customers/add'); */ ?>" id="add-customer"class="external" data-toggle="modal" data-target="#myModal*/ ">
                                                        <i class="fa fa-plus-circle" id="addIcon"  style="font-size: 1.2em;"></i>
                                                    </a>
                                                </div>
                                                <?php "" /* } */ ?>
                                                -->
                                            <!-- </div> -->
                                        </div>
                                    </div>


                                    <!-- *******************************************
                                    *  SHIPPING - SHIP TO
                                    ******************************************** -->

                                    <div class="col-md-4">
                                        <?php /* <label for="mcode" class="col-sm-4 control-label"><?= lang('product_code') ?> *</label> */ ?>

                                        <label><?= "Ship To" ?></label>
                                        <?php /* <label for="mcode" class="col-sm-4 control-label"><?= "Product" ?> *</label> */ ?>

                                          <div class="form-group">
                                              <?php
                                              $cstmer[''] = "";
                                              foreach ($customers as $customer) {
                                                  $cstmer[$customer->id] = $customer->company . " - " . $customer->address;
                                              }
                                              // echo form_dropdown('customer_id', $cstmer, (isset($_POST['customer_id']) ? $_POST['customer_id'] : ($customer ? $customer->customer_id : '')), 'class="form-control select" id="sale_customer_id" placeholder="' . lang("select") . " " . lang("customer") . '" required="required" style="width:100%"')
                                              echo form_dropdown('ship_to_customer_id', $cstmer, (isset($_POST['ship_to_customer_id']) ? $_POST['ship_to_customer_id'] : ($customer ? $customer->customer_id : '')), 'class="form-control select" id="sale_customer_id" placeholder="Select Customer" required="required" style="width:100%"')
                                              ?>
                                          </div>

                                    </div>


                                </div>
                            </div>

                        </div>

                        <!-- ***************************************************
                        *  ROW 3
                        *  SEARCH BAR PRODUCT
                        **************************************************** -->

                        <div class="col-md-12" id="sticker">
                            <div class="well well-sm">
                                <div class="form-group" style="margin-bottom:0;">
                                    <div class="input-group wide-tip">

                                        <!-- ***********************************
                                        *  ICON
                                        ************************************ -->

                                        <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                                            <i class="fa fa-2x fa-barcode addIcon"></i></a>
                                        </div>

                                        <!-- ***********************************
                                        *  FORM - 1 INPUT - SEARCH BAR
                                        ************************************ -->

                                        <?php echo form_input('add_item', '', 'readonly="true" class="form-control input-lg" id="add_item" placeholder="' . lang("add_product_to_order") . '"'); ?>

                                        <!-- ***********************************
                                        *  ADD MANUALLY BUTTON
                                        ************************************ -->

                                        <?php if ($Owner || $Admin || $GP['products-add']) { ?>
                                        <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                                            <a href="#" id="addManually" class="tip" title="<?= lang('add_product_manually') ?>">
                                                <i class="fa fa-2x fa-plus-circle addIcon" id="addIcon"></i>
                                            </a>
                                        </div>

                                        <!-- ***********************************
                                        *  GIFT CARD BUTTON
                                        ************************************ -->

                                        <?php } ?>

                                        <?php /* } if ($Owner || $Admin || $GP['sales-add_gift_card']) { */ ?>
                                        <!-- <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;"> -->
                                            <!-- <a href="#" id="sellGiftCard" class="tip" title=" --> <?= /* lang('sell_gift_card') */ "" ?> <!-- "> -->
                                               <!-- <i class="fa fa-2x fa-credit-card addIcon" id="addIcon"></i> -->
                                            <!-- </a> -->
                                        <!-- </div> -->
                                        <?php /* } */ ?>

                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>

                        <!-- ***************************************************
                        *  ROW 4
                        *  TABLE
                        **************************************************** -->

                        <div class="col-md-12">
                            <div class="control-group table-group">
                                <label class="table-label"><?= lang("order_items"); ?> *</label>

                                <div class="controls table-controls">
                                    <table id="slTable" class="table items table-striped table-bordered table-condensed table-hover sortable_table">
                                        <thead>
                                          <tr>

                                              <!-- *****************************
                                              *  COLUMN HEAD - PRODUCT CODE/NAME
                                              ****************************** -->

                                              <th class="col-md-4"><?= lang('product') . ' (' . lang('code') .' - '.lang('name') . ')'; ?></th>

                                              <!-- *****************************
                                              *  COLUMN HEAD - SERIAL NO
                                              ****************************** -->

                                              <?php
                                              if ($Settings->product_serial) {
                                                  echo '<th class="col-md-2">' . lang("serial_no") . '</th>';
                                              }
                                              ?>

                                              <!-- *****************************
                                              *  COLUMN HEAD - NET UNIT PRICE
                                              ****************************** -->

                                              <th class="col-md-1"><?= lang("net_unit_price"); ?></th>

                                              <!-- *****************************
                                              *  COLUMN HEAD - QUANTITY
                                              ****************************** -->

                                              <th class="col-md-1"><?= lang("quantity"); ?></th>

                                              <!-- *****************************
                                              *  COLUMN HEAD - DISCOUNT
                                              ****************************** -->

                                              <?php
                                              if ($Settings->product_discount && ($Owner || $Admin || $this->session->userdata('allow_discount'))) {
                                                  echo '<th class="col-md-1">' . lang("discount") . '</th>';
                                              }
                                              ?>

                                              <!-- *****************************
                                              *  COLUMN HEAD - TAX
                                              ****************************** -->

                                              <?php
                                              if ($Settings->tax1) {
                                                  echo '<th class="col-md-1">' . lang("product_tax") . '</th>';
                                              }
                                              ?>

                                              <!-- *****************************
                                              *  COLUMN HEAD - SUBTOTAL
                                              ****************************** -->

                                              <th>
                                                  <?= lang("subtotal"); ?>
                                                  (<span class="currency"><?= $default_currency->code ?></span>)
                                              </th>

                                              <!-- *****************************
                                              *  COLUMN HEAD - ACTION TRASH
                                              ****************************** -->

                                              <th style="width: 30px !important; text-align: center;">
                                                  <i class="fa fa-trash-o" style="opacity:0.5; filter:alpha(opacity=50);"></i>
                                              </th>
                                          </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot></tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- ***************************************************
                        *  ORDER TAX
                        **************************************************** -->

                        <?php if ($Settings->tax2) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("order_tax", "sltax2"); ?>
                                    <?php
                                    $tr[""] = "";
                                    foreach ($tax_rates as $tax) {
                                        $tr[$tax->id] = $tax->name;
                                    }
                                    echo form_dropdown('order_tax', $tr, (isset($_POST['order_tax']) ? $_POST['order_tax'] : $Settings->default_tax_rate2), 'id="sltax2" data-placeholder="' . lang("select") . ' ' . lang("order_tax") . '" class="form-control input-tip select" style="width:100%;"');
                                    ?>
                                </div>
                            </div>
                        <?php } ?>

                        <!-- ***************************************************
                        *  ORDER DISCOUNT
                        **************************************************** -->

                        <?php if ($Owner || $Admin || $this->session->userdata('allow_discount')) { ?>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("order_discount", "sldiscount"); ?>
                                    <?php echo form_input('order_discount', '', 'class="form-control input-tip" id="sldiscount"'); ?>
                                </div>
                            </div>
                        <?php } ?>

                        <!-- ***************************************************
                        *  SHIPPING COSTS
                        **************************************************** -->

                        <div class="col-md-4">
                            <div class="form-group">
                                <?= "" /* lang("shipping", "slshipping"); */ ?>
                                <label><?= "Shipping Cost" ?></label>
                                <?php echo form_input('shipping', '', 'class="form-control input-tip" type="number" id="slshipping"'); ?>
                            </div>
                        </div>

                        <!-- ***************************************************
                        *  ATTACH DOCUMENT
                        **************************************************** -->

                        <div class="col-md-4">
                            <div class="form-group">
                                <?= lang("document", "document") ?>
                                <input id="document" type="file" data-browse-label="<?= lang('browse'); ?>" name="document" data-show-upload="false"
                                       data-show-preview="false" class="form-control file">
                            </div>
                        </div>

                        <!-- ***************************************************
                        *  SALE STATUS
                        **************************************************** -->

                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("sale_status", "slsale_status"); ?>
                                <?php $sst = array('pending' => lang('pending'), 'completed' => lang('completed'));
                                echo form_dropdown('sale_status', $sst, '', 'id="sale_form-sale_status" class="form-control input-tip" required="required" id="slsale_status" disabled'); ?>

                            </div>
                        </div>

                        <!-- ***************************************************
                        *  SALES TERMS
                        **************************************************** -->

                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= "" /* lang("payment_term", "slpayment_term"); */ ?>
                                <label><?= /* lang("payment_term", "slpayment_term"); */ "Sales Terms" ?></label>
                                <?php "" /* echo form_input('sales_term', '', 'class="form-control tip" data-trigger="focus" data-placement="top" title="' . lang('payment_term_tip') . '" id="slpayment_term"'); */ ?>
                                <?php echo form_input('sales_terms', '', 'class="form-control tip" data-trigger="focus" data-placement="top" title="Sales Term" id="slsales_terms"'); ?>
                            </div>
                        </div>

                        <!-- ***************************************************
                        *  PAYMENT TERM
                        **************************************************** -->

                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("payment_term", "slpayment_term"); ?>
                                <?php echo form_input('payment_term', '', 'class="form-control tip" data-trigger="focus" data-placement="top" title="' . lang('payment_term_tip') . '" id="slpayment_term"'); ?>

                            </div>
                        </div>

                        <!-- ***************************************************
                        *  PAYMENT STATUS
                        **************************************************** -->

                        <?php if ($Owner || $Admin || $GP['sales-payments']) { ?>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <?= lang("payment_status", "slpayment_status"); ?>
                                <?php $pst = array('pending' => lang('pending'), 'due' => lang('due'), 'partial' => lang('partial'), 'paid' => lang('paid'));
                                echo form_dropdown('payment_status', $pst, '', 'class="form-control input-tip" required="required" id="slpayment_status" disabled'); ?>

                            </div>
                        </div>
                        <?php
                        } else {
                            echo form_hidden('payment_status', 'pending');
                        }
                        ?>

                        <div class="clearfix"></div>

                        <!-- ***************************************************
                        *  ELEMENTS WILL BE DISPLAYED OR HIDDEN, DEPENDING ON
                        *  PAYMENT STATUS
                        **************************************************** -->

                        <div id="payments" style="display: none;">
                            <div class="col-md-12">
                                <div class="well well-sm well_1">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <?= lang("payment_reference_no", "payment_reference_no"); ?>
                                                    <?= form_input('payment_reference_no', (isset($_POST['payment_reference_no']) ? $_POST['payment_reference_no'] : $payment_ref), 'class="form-control tip" id="payment_reference_no"'); ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="payment">
                                                    <div class="form-group ngc">
                                                        <?= lang("amount", "amount_1"); ?>
                                                        <input name="amount-paid" type="text" id="amount_1"
                                                               class="pa form-control kb-pad amount"/>
                                                    </div>
                                                    <div class="form-group gc" style="display: none;">
                                                        <?= lang("gift_card_no", "gift_card_no"); ?>
                                                        <input name="gift_card_no" type="text" id="gift_card_no"
                                                               class="pa form-control kb-pad"/>

                                                        <div id="gc_details"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <?= lang("paying_by", "paid_by_1"); ?>
                                                    <select name="paid_by" id="paid_by_1" class="form-control paid_by">
                                                        <?= $this->sma->paid_opts(); ?>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="pcc_1" style="display:none;">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input name="pcc_no" type="text" id="pcc_no_1"
                                                               class="form-control" placeholder="<?= lang('cc_no') ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input name="pcc_holder" type="text" id="pcc_holder_1"
                                                               class="form-control"
                                                               placeholder="<?= lang('cc_holder') ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <select name="pcc_type" id="pcc_type_1"
                                                                class="form-control pcc_type"
                                                                placeholder="<?= lang('card_type') ?>">
                                                            <option value="Visa"><?= lang("Visa"); ?></option>
                                                            <option
                                                                value="MasterCard"><?= lang("MasterCard"); ?></option>
                                                            <option value="Amex"><?= lang("Amex"); ?></option>
                                                            <option value="Discover"><?= lang("Discover"); ?></option>
                                                        </select>
                                                        <!-- <input type="text" id="pcc_type_1" class="form-control" placeholder="<?= lang('card_type') ?>" />-->
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <input name="pcc_month" type="text" id="pcc_month_1"
                                                               class="form-control" placeholder="<?= lang('month') ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">

                                                        <input name="pcc_year" type="text" id="pcc_year_1"
                                                               class="form-control" placeholder="<?= lang('year') ?>"/>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">

                                                        <input name="pcc_ccv" type="text" id="pcc_cvv2_1"
                                                               class="form-control" placeholder="<?= lang('cvv2') ?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pcheque_1" style="display:none;">
                                            <div class="form-group"><?= lang("cheque_no", "cheque_no_1"); ?>
                                                <input name="cheque_no" type="text" id="cheque_no_1"
                                                       class="form-control cheque_no"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <?= lang('payment_note', 'payment_note_1'); ?>
                                            <textarea name="payment_note" id="payment_note_1"
                                                      class="pa form-control kb-text payment_note"></textarea>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="total_items" value="" id="total_items" required="required"/>

                        <!-- ***************************************************
                        *  ROW FOR NOTES
                        **************************************************** -->

                        <div class="row" id="bt">
                            <div class="col-md-12">

                                <!-- *******************************************
                                *  SALE NOTES
                                ******************************************** -->

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?= lang("sale_note", "slnote"); ?>
                                        <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control" id="slnote" style="margin-top: 10px; height: 100px;"'); ?>

                                    </div>
                                </div>

                                <!-- *******************************************
                                *  STAFF NOTES
                                ******************************************** -->

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?= lang("staff_note", "slinnote"); ?>
                                        <?php echo form_textarea('staff_note', (isset($_POST['staff_note']) ? $_POST['staff_note'] : ""), 'class="form-control" id="slinnote" style="margin-top: 10px; height: 100px;"'); ?>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- ***************************************************
                        *  FORM BUTTONS - SUBMIT AND RESET
                        **************************************************** -->

                        <div class="col-md-12">
                            <div
                                class="fprom-group"><?php echo form_submit('add_sale', lang("submit"), 'id="add_sale" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                <button type="button" class="btn btn-danger" id="reset"><?= lang('reset') ?></div>
                        </div>
                    </div>
                </div>

                <!-- ***************************************************
                *  LAST ROW - TABLE - TOTALS
                **************************************************** -->

                <div id="bottom-total" class="well well-sm" style="margin-bottom: 0;">
                    <table class="table table-bordered table-condensed totals" style="margin-bottom:0;">
                        <tr class="warning">
                            <td><?= lang('items') ?> <span class="totals_val pull-right" id="titems">0</span></td>
                            <td><?= lang('total') ?> <span class="totals_val pull-right" id="total">0.00</span></td>
                            <?php if ($Owner || $Admin || $this->session->userdata('allow_discount')) { ?>
                            <td><?= lang('order_discount') ?> <span class="totals_val pull-right" id="tds">0.00</span></td>
                            <?php }?>
                            <?php if ($Settings->tax2) { ?>
                                <td><?= lang('order_tax') ?> <span class="totals_val pull-right" id="ttax2">0.00</span></td>
                            <?php } ?>
                            <td><?= lang('shipping') ?> <span class="totals_val pull-right" id="tship">0.00</span></td>
                            <td><?= lang('grand_total') ?> <span class="totals_val pull-right" id="gtotal">0.00</span></td>
                        </tr>
                    </table>
                </div>

                <?php echo form_close(); ?>

            </div>

        </div>
    </div>
</div>

<!-- ***************************************************************************

  PAGE MODALS BELOW (3)
  THIS PAGE USES MODALS FROM OTHER FILES, EXAMPLE ADD CUSTOMER MODAL, TAKEN FROM
  THE /customers/add.php file

**************************************************************************** -->

<!-- ***************************************************************************
  MODAL - THIS MODAL SEEMS TO BE USED OTHER FILES
**************************************************************************** -->

<div class="modal" id="prModal" tabindex="-1" role="dialog" aria-labelledby="prModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                            class="fa fa-2x">&times;</i></span><span class="sr-only"><?=lang('close');?></span></button>
                <h4 class="modal-title" id="prModalLabel"></h4>
            </div>
            <div class="modal-body" id="pr_popover_content">
                <form class="form-horizontal" role="form">
                    <?php if ($Settings->tax1) { ?>
                        <div class="form-group">
                            <label class="col-sm-4 control-label"><?= lang('product_tax') ?></label>
                            <div class="col-sm-8">
                                <?php
                                $tr[""] = "";
                                foreach ($tax_rates as $tax) {
                                    $tr[$tax->id] = $tax->name;
                                }
                                echo form_dropdown('ptax', $tr, "", 'id="ptax" class="form-control pos-input-tip" style="width:100%;"');
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($Settings->product_serial) { ?>
                        <div class="form-group">
                            <label for="pserial" class="col-sm-4 control-label"><?= lang('serial_no') ?></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="pserial">
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="pquantity" class="col-sm-4 control-label"><?= lang('quantity') ?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="pquantity">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="punit" class="col-sm-4 control-label"><?= lang('product_unit') ?></label>
                        <div class="col-sm-8">
                            <div id="punits-div"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="poption" class="col-sm-4 control-label"><?= lang('product_option') ?></label>
                        <div class="col-sm-8">
                            <div id="poptions-div"></div>
                        </div>
                    </div>
                    <?php if ($Settings->product_discount && ($Owner || $Admin || $this->session->userdata('allow_discount'))) { ?>
                        <div class="form-group">
                            <label for="pdiscount"
                                   class="col-sm-4 control-label"><?= lang('product_discount') ?></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="pdiscount">
                            </div>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="pprice" class="col-sm-4 control-label"><?= lang('unit_price') ?></label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="pprice" <?= ($Owner || $Admin || $GP['edit_price']) ? '' : 'readonly'; ?>>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th style="width:25%;"><?= lang('net_unit_price'); ?></th>
                            <th style="width:25%;"><span id="net_price"></span></th>
                            <th style="width:25%;"><?= lang('product_tax'); ?></th>
                            <th style="width:25%;"><span id="pro_tax"></span></th>
                        </tr>
                    </table>
                    <input type="hidden" id="punit_price" value=""/>
                    <input type="hidden" id="old_tax" value=""/>
                    <input type="hidden" id="old_qty" value=""/>
                    <input type="hidden" id="old_price" value=""/>
                    <input type="hidden" id="row_id" value=""/>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="editItem"><?= lang('submit') ?></button>
            </div>
        </div>
    </div>
</div>

<!-- ***************************************************************************
  MODAL - ADD PRODUCT MANUALLY
**************************************************************************** -->

<div class="modal" id="mModal" tabindex="-1" role="dialog" aria-labelledby="mModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i
                            class="fa fa-2x">&times;</i></span><span class="sr-only"><?=lang('close');?></span></button>
                <h4 class="modal-title" id="mModalLabel"><?= lang('add_product_manually') ?></h4>
            </div>
            <div class="modal-body" id="pr_popover_content">
                <form class="form-horizontal" role="form">

                    <!-- *******************************************************
                      SELECT PRODUCT
                    ******************************************************** -->

                    <div class="form-group">
                        <?php /* <label for="mcode" class="col-sm-4 control-label"><?= lang('product_code') ?> *</label> */ ?>

                        <label class="col-sm-4 control-label"><?= "Select Product *" ?></label>
                        <?php /* <label for="mcode" class="col-sm-4 control-label"><?= "Product" ?> *</label> */ ?>

                          <div class="col-sm-8">
                              <?php
                              $prodct[''] = "";
                              foreach ($products as $product) {
                                  $prodct[$product->id] = $product->name;
                              }
                              echo form_dropdown('product', $prodct, (isset($_POST['product']) ? $_POST['product'] : ($product ? $product->id : '')), 'class="form-control select" id="mproduct_id" placeholder="' . lang("select") . " " . lang("product") . '" required="required" style="width:100%"')
                              ?>
                          </div>

                    </div>

                    <!-- *******************************************************
                      PRODUCT CODE
                    ******************************************************** -->

                    <div class="form-group">
                        <label for="mcode" class="col-sm-4 control-label"><?= lang('product_code') ?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="mcode" readonly="true">
                        </div>
                    </div>

                    <!-- *******************************************************
                      PRODUCT NAME
                    ******************************************************** -->

                    <div class="form-group">
                        <label for="mname" class="col-sm-4 control-label"><?= lang('product_name') ?> *</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="mname" readonly="true">
                        </div>
                    </div>

                    <!-- *******************************************************
                      SELECT PALLET
                    ******************************************************** -->

                    <div class="form-group">
                        <?php /* <label for="mcode" class="col-sm-4 control-label"><?= lang('product_code') ?> *</label> */ ?>

                        <label class="col-sm-4 control-label"><?= "From Pallet *" ?></label>
                        <?php /* <label for="mcode" class="col-sm-4 control-label"><?= "Product" ?> *</label> */ ?>

                          <div class="col-sm-8">
                              <?php
                              $prodFromPallet[''] = "";
                              // foreach ($pallets as $pallet) {
                              //     // $prodFromPallet[$pallet->id] = $pallet->code;
                              //     $prodFromPallet[$pallet->id] = $pallet->warehouse_id;
                              //
                              //     // warehouse - pallet code - rack name - prod qty available in pallet
                              //     // warehouse_id
                              //     // code
                              //     // rack_id
                              //     // pallet_items pallet_id
                              // }
                              foreach ($palletsMoreInfo as $pallet) {

                                  // $productsAndQty = "";
                                  // foreach ($pallet['pallet_items'] as $item) {
                                  //   // code...
                                  //   $productsAndQty = $item['product_id'] . " Qty " . $item['quantity'];
                                  // }

                                  // $prodFromPallet[$pallet['pallet_data']->id] = $pallet['pallet_data']->created_at;
                                  $prodFromPallet[$pallet['pallet_data']->id] =
                                    "Pallet Code: "
                                    .  $pallet['pallet_data']->code
                                    . " - Rack: "
                                    . $pallet['rack_name']
                                    . " - Warehouse: "
                                    . $pallet['warehouse_name']
                                    ;
                              }
                              echo form_dropdown('pallet_id', $prodFromPallet, (isset($_POST['pallet']) ? $_POST['pallet_id'] : ($pallet ? $pallet->id : '')), 'class="form-control select" id="mpallet_id" placeholder="' . lang("select") . " " . lang("pallet") . '" required="required" style="width:100%"')
                              ?>
                          </div>

                    </div>

                    <!-- *******************************************************
                      PRODUCT TAX
                    ******************************************************** -->

                    <?php if ($Settings->tax1) { ?>
                        <div class="form-group">
                            <label for="mtax" class="col-sm-4 control-label"><?= lang('product_tax') ?> *</label>

                            <div class="col-sm-8">
                                <?php
                                $tr[""] = "";
                                foreach ($tax_rates as $tax) {
                                    $tr[$tax->id] = $tax->name;
                                }
                                echo form_dropdown('mtax', $tr, "", 'id="mtax" class="form-control input-tip select" style="width:100%;"');
                                ?>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- *******************************************************
                      PRODUCT QTY
                    ******************************************************** -->

                    <div class="form-group">
                        <label for="mquantity" class="col-sm-4 control-label"><?= lang('quantity') ?> *</label>

                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="mquantity">
                        </div>
                    </div>

                    <!-- *******************************************************
                      UNIT
                    ******************************************************** -->

                    <div class="form-group">
                        <label for="munit" class="col-sm-4 control-label"><?= lang('unit') ?> *</label>

                        <div class="col-sm-8">
                            <?php
                            $uts[""] = "";
                            foreach ($units as $unit) {
                                $uts[$unit->id] = $unit->name;
                            }
                            echo form_dropdown('munit', $uts, "", 'id="munit" class="form-control input-tip select" style="width:100%;"');
                            ?>
                        </div>
                    </div>

                    <!-- *******************************************************
                      PRODUCT DISCOUNT
                    ******************************************************** -->

                    <?php if ($Settings->product_discount && ($Owner || $Admin || $this->session->userdata('allow_discount'))) { ?>
                        <div class="form-group">
                            <label for="mdiscount"
                                   class="col-sm-4 control-label"><?= lang('product_discount') ?></label>

                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="mdiscount">
                            </div>
                        </div>
                    <?php } ?>

                    <!-- *******************************************************
                      UNIT PRICE
                    ******************************************************** -->

                    <div class="form-group">
                        <label for="mprice" class="col-sm-4 control-label"><?= lang('unit_price') ?> *</label>

                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="mprice">
                        </div>
                    </div>

                    <!-- *******************************************************
                      TABLE
                    ******************************************************** -->

                    <table class="table table-bordered table-striped">
                        <tr>
                            <th style="width:25%;"><?= lang('net_unit_price'); ?></th>
                            <th style="width:25%;"><span id="mnet_price"></span></th>
                            <th style="width:25%;"><?= lang('product_tax'); ?></th>
                            <th style="width:25%;"><span id="mpro_tax"></span></th>
                        </tr>
                    </table>
                </form>
            </div>

            <!-- *******************************************************
              ADD ITEM MODAL - SUBMIT BUTTON
            ******************************************************** -->

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="addItemManually"><?= lang('submit') ?></button>
            </div>
        </div>
    </div>
</div>

<!-- ***************************************************************************
  MODAL - SELL GIFT CARD
**************************************************************************** -->

<div class="modal" id="gcModal" tabindex="-1" role="dialog" aria-labelledby="mModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                        class="fa fa-2x">&times;</i></button>
                <h4 class="modal-title" id="myModalLabel"><?= lang('sell_gift_card'); ?></h4>
            </div>
            <div class="modal-body">
                <p><?= lang('enter_info'); ?></p>

                <div class="alert alert-danger gcerror-con" style="display: none;">
                    <button data-dismiss="alert" class="close" type="button">×</button>
                    <span id="gcerror"></span>
                </div>
                <div class="form-group">
                    <?= lang("card_no", "gccard_no"); ?> *
                    <div class="input-group">
                        <?php echo form_input('gccard_no', '', 'class="form-control" id="gccard_no"'); ?>
                        <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;"><a href="#"
                                                                                                           id="genNo"><i
                                    class="fa fa-cogs"></i></a></div>
                    </div>
                </div>
                <input type="hidden" name="gcname" value="<?= lang('gift_card') ?>" id="gcname"/>

                <div class="form-group">
                    <?= lang("value", "gcvalue"); ?> *
                    <?php echo form_input('gcvalue', '', 'class="form-control" id="gcvalue"'); ?>
                </div>
                <div class="form-group">
                    <?= lang("price", "gcprice"); ?> *
                    <?php echo form_input('gcprice', '', 'class="form-control" id="gcprice"'); ?>
                </div>
                <div class="form-group">
                    <?= lang("customer", "gccustomer"); ?>
                    <?php echo form_input('gccustomer', '', 'class="form-control" id="gccustomer"'); ?>
                </div>
                <div class="form-group">
                    <?= lang("expiry_date", "gcexpiry"); ?>
                    <?php echo form_input('gcexpiry', $this->sma->hrsd(date("Y-m-d", strtotime("+2 year"))), 'class="form-control date" id="gcexpiry"'); ?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" id="addGiftCard" class="btn btn-primary"><?= lang('sell_gift_card') ?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#gccustomer').select2({
            minimumInputLength: 1,
            ajax: {
                url: site.base_url + "customers/suggestions",
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        term: term,
                        limit: 10
                    };
                },
                results: function (data, page) {
                    if (data.results != null) {
                        return {results: data.results};
                    } else {
                        return {results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            }
        });
        $('#genNo').click(function () {
            var no = generateCardNo();
            $(this).parent().parent('.input-group').children('input').val(no);
            return false;
        });
    });
</script>

<script type="text/javascript">

    // document.getElementById('#quantity_1556660855776').readonly = true;

    $(document).ready(function () {

      $(document).on('click', '#add_sale', function(e) {
          // sate_status dropdown is disabled by default, which means user cannot select a value
          // and when submitting the form, its value wont be sent to the controller
          // so when submitting the sale we set the sale_status enabled again to send its value to the controller
          $('#slsale_status').prop('disabled', false);
          $('#slpayment_status').prop('disabled', false);
          
      });

      $(document).on('click', '#reset', function(e) {
          localStorage.removeItem('form-add_sale-items');
      });

      // $("#quantity_1556660855776").attr("disabled", true);
      $("#quantity_1556660855776").prop("disabled", "disabled");
      $("#quantity_1556660855776").prop("disabled", true);
      // $("#slwarehouse").attr("disabled", true);
      // $("#slwarehouse").attr("readonly", true);

        // $('#check').change(function() {
        //     $("#1556660855776").prop("hidden", true);
        //     $("#serial_1556660855776").attr("readonly", true);
        //     $("#quantity_1556660855776").attr("readonly", true);
        // })
        //
        //   $("#1556660855776").attr("disabled", true);
        //   $("#serial_1556660855776").attr("readonly", true);
        //   $("#quantity_1556660855776").attr("readonly", true);
        //   $("#quantity_1556660855776").attr("type", "number");

        // document.getElementById('telheaderid').yourattribute = "your_value";

        // <i class="pull-right fa fa-edit tip pointer edit" id="" data-item="1556660855776" title="Edit" style="cursor:pointer;"></i>
        // <input class="form-control input-sm rserial" name="serial[]" type="text" id="" value="">
        // <input class="form-control text-center rquantity" tabindex="3" name="quantity[]" type="text" value="2" data-id="1556660855776" data-item="1556660855776" id="" onclick="this.select();">

        var products = <?php echo json_encode($products); ?>;
        function populateProdCodeAndName() {
            let product_id = $('#mproduct_id').val();
            if (product_id !== "" || product_id !== undefined || product_id !== null) {
                products.map(prod => {
                    if (prod.id.toString() === product_id.toString()) {
                        $('#mcode').val(prod.code);
                        $('#mname').val(prod.name);
                    }
                })
            }
        }
        // window.populateProdCodeAndName =  populateProdCodeAndName;
        populateProdCodeAndName();

        $('#addManually').on("click",function(e){
            populateProdCodeAndName();
        });

        $(document).on('change', '#mproduct_id', function(e) {
            populateProdCodeAndName();
            $('#mquantity').val("");
        });

        $(document).on('change', '#mpallet_id', function(e) {
            $('#mquantity').val("");
        });

        // *********************************************************************
        //  HANDLE SELECTING PRODUCT QTY FROM PALLET ITEMS
        // *********************************************************************

        $(document).on('change', '#mquantity', function(e) {

            // -----------------------------------------------------------------
            // Make sure input value is number and greater than 0
            // -----------------------------------------------------------------

            // parseInt($(this).val());

            if ($(this).val() <= 0) {
                alert("Please add a product quantity greater than 0.");
                $(this).val("");
            };
            if (isNaN($(this).val()) === true) {
                alert("Please add only numbers in product quantity.");
                $(this).val("");
            };
            // if (!(Number.isInteger($(this).val()))) {
            //     alert("Please add only integer numbers in product quantity.");
            //     $(this).val("");
            // };

            // -----------------------------------------------------------------
            // Find prodQty in palletItem
            // -----------------------------------------------------------------

            // loop through $palletsMoreInfo array and find palletId, productId
            // match those with the selected palletId and productId, get qty

            // here we receive an array coming from the controller, we then loop
            // through it, and add a nested loop, then compare values from this
            // view to make sure we can get the right prod qty
            // best approach is to do an ajax request amd get real time prod qty

            // let selectedProductId = $('#mproduct_id').val();
            // let selectedPalletId = $('#mpallet_id').val();
            //
            // let palletProdQty = 0;
            // let palletData;
            //
            // var palletsList = <?php echo json_encode($palletsMoreInfo); ?>;
            // palletsList.map(pallet => {
            //     if (pallet.pallet_data.id.toString() === selectedPalletId.toString()) {
            //         // prodName = pallet.name;
            //         // console.log(pallet);
            //         // palletData = pallet;
            //
            //         pallet.pallet_items.map(palletItem => {
            //             if (palletItem.product_id.toString() === selectedProductId.toString()) {
            //                 palletProdQty += parseInt(palletItem.quantity);
            //                 // console.log(palletProdQty);
            //             }
            //         })
            //
            //     }
            //     // console.log(pallet);
            // });

            // -----------------------------------------------------------------
            // Get available prod qty from pallet
            // -----------------------------------------------------------------

            // get prod qty available from pallet_id
            // make sure we only get back pallet items with status "available"
            // make sure to also get tne prod qty already added in this order
            // from the prod qty available, rest prod qty already added in this order, and rest prod qty selected in this from
            // then make sure to alert the user about it...

            let palletIdVal = $('#mpallet_id').val();
            let prodIdVal = $('#mproduct_id').val();
            let prodQtyVal = $('#mquantity').val();

            $.ajax({
                // url : 'http://voicebunny.comeze.com/index.php',
                // url : '<?= admin_url() ?>' + 'sales/isPalletProdQtyAvailable/' + palletIdVal,
                url : '<?= admin_url() ?>' + 'sales/getAvailableProdQtyFromPallet/' + palletIdVal + "/" + prodIdVal + "/" + prodQtyVal,
                type : 'GET',
                data : { palletIdVal },
                // line below works with type 'POST'
                // and data can be accessed on controller as: $prodId = $this->input->post('prod_id');
                // data : 'prod_id=' + prodIdVal + '&prod_qty=' + prodQtyVal,
                dataType:'json',
                success : function(data) {

                    console.log("ajax data - getAvailableProdQtyFromPallet");
                    // console.log(data);
                    // console.log(data.aaData);
                    // console.log(JSON.stringify(data));

                    // alert('Data: '+ JSON.stringify(data)); // data is equal to the n of prod qty available
                    // alert('Data: '+ JSON.stringify(data.aaData));

                    let availablePalletProdQty;
                    // availablePalletProdQty = JSON.stringify(data);
                    availablePalletProdQty = parseInt(data);
                    addProdQtyFromPallet(availablePalletProdQty);

                    // we just got the available prod qty from pallet
                    // get prod qty already added to this sale order from this same prodId and palletId
                    // palletAvailableProdQty - prodQtyFromThisPallerAlreadyInTHeOrder - prodQty in this form

                    // call function and pass the available product from this pallet

                },
                error : function(request,error)
                {
                    alert("Request: " + JSON.stringify(request));
                    // alert("Error: " + JSON.stringify(error));
                }
            });

            // // -----------------------------------------------------------------
            // // GET PROD QTY SELCECTED, AND CHECK PROD QTY ALREADY ADDED TO ORDER
            // // -----------------------------------------------------------------
            //
            // // get order items
            // // if order item prod id is equal to the selected prod id
            // // check order id prod qty
            // // loop through order items and for every prod id with same pallet id
            // // add an object with orderItems { prodId, palletId, qty } and sum qty
            // // total prod qty from the same pallet
            // // get that qty and rest it to the palletProdQty here...
            // // if selected prod qty is > than palletProdQty - current order items qty (same prod id on same pallet id)
            // // then send message that pallet has X real num in total, but order already have selected some prds from this pallet
            // // remove order items or add less items from this pallet or chose another pallet...
            //
            // // localStorage.clear();
            //
            // var orderItems = JSON.parse(localStorage.getItem('form-add_sale-items'));
            // // console.log("orderItems after qty change");
            // // console.log(orderItems);
            //
            // if (orderItems === null || orderItems === undefined) {
            //   if ($(this).val() > palletProdQty) {
            //       alert("Product quantity selected is " + $(this).val() + ", product quantity on pallet is " + palletProdQty + ". Product quantity selected exceeds the product quantity on pallet with code " + palletData.pallet_data.code + ", found in rack name " + palletData.rack_name + ", on warehouse " + palletData.warehouse_name + ".");
            //       $(this).val("");
            //   }
            // }
            //
            // if (orderItems !== null || orderItems !== undefined) {
            //     let totalProdQtyFromSamePallet = 0;
            //     orderItems.map(orderItem => {
            //         if (orderItem.prodId.toString() === selectedProductId.toString()
            //         && orderItem.palletId.toString() === selectedPalletId.toString()) {
            //             totalProdQtyFromSamePallet += parseInt(orderItem.qty);
            //         }
            //     });
            //     let palletProdQtyLeft = parseInt(palletProdQty) - parseInt(totalProdQtyFromSamePallet);
            //     if ($(this).val() > palletProdQtyLeft) {
            //         // alert("Product quantity selected is " + $(this).val() + ", but you already added " + totalProdQtyFromSamePallet + " products from this pallet to this sale. Product quantity left on this pallet is " + palletProdQtyLeft + ". Add same product quantity or less from this pallet or select another pallet with enough product quantity.");
            //         if (totalProdQtyFromSamePallet > 0) {
            //             alert("Product quantity selected is " + $(this).val() + ", but you already added " + totalProdQtyFromSamePallet + " products from this pallet to this sale. Product quantity left on this pallet is " + palletProdQtyLeft + ". Add same product quantity or less from this pallet or select another pallet with enough product quantity.");
            //         } else {
            //             alert("Product quantity selected is " + $(this).val() + ". Product quantity available on this pallet is " + palletProdQtyLeft + ". Select another pallet with enough product quantity or in case this pallet still has available items add equal or less product quantity.");
            //         }
            //         $(this).val("");
            //     }
            //
            //     // console.log("totalProdQtyFromSamePallet");
            //     // console.log(totalProdQtyFromSamePallet);
            //     // console.log("palletProdQtyLeft");
            //     // console.log(palletProdQtyLeft);
            // };





            // -----------------------------------------------------------------
            // Get localStorage items, see if we already added same prodId from same palletId to this sale items
            // -----------------------------------------------------------------
            // -----------------------------------------------------------------
            // GET PROD QTY SELCECTED, AND CHECK PROD QTY ALREADY ADDED TO ORDER
            // -----------------------------------------------------------------

            function addProdQtyFromPallet(availablePalletProdQty) {

                console.log("addProdQtyFromPallet");
                console.log(availablePalletProdQty);

                // get order items
                // if order item prod id is equal to the selected prod id
                // check order id prod qty
                // loop through order items and for every prod id with same pallet id
                // add an object with orderItems { prodId, palletId, qty } and sum qty
                // total prod qty from the same pallet
                // get that qty and rest it to the palletProdQty here...
                // if selected prod qty is > than palletProdQty - current order items qty (same prod id on same pallet id)
                // then send message that pallet has X real num in total, but order already have selected some prds from this pallet
                // remove order items or add less items from this pallet or chose another pallet...

                // localStorage.clear();

                var orderItems = JSON.parse(localStorage.getItem('form-add_sale-items'));
                // console.log("orderItems after qty change");
                // console.log(orderItems);

                // see if qty input is > availablePalletProdQty
                if (orderItems === null || orderItems === undefined) {
                    if ($('#mquantity').val() > availablePalletProdQty) {
                        // alert("Product quantity selected is " + $('#mquantity').val() + ", product quantity on pallet is " + availablePalletProdQty + ". Product quantity selected exceeds the product quantity on pallet with code " + palletData.pallet_data.code + ", found in rack name " + palletData.rack_name + ", on warehouse " + palletData.warehouse_name + ".");
                        alert("Product quantity selected is " + $('#mquantity').val() + ", product quantity on pallet is " + availablePalletProdQty + ". Product quantity selected exceeds the product quantity on pallet.");
                        $('#mquantity').val("");
                    }
                }

                if (orderItems !== null || orderItems !== undefined) {
                    let totalProdQtyFromSamePallet = 0;
                    orderItems.map(orderItem => {
                        if (orderItem.prodId.toString() === prodIdVal.toString()
                        && orderItem.palletId.toString() === palletIdVal.toString()) {
                            totalProdQtyFromSamePallet += parseInt(orderItem.qty);
                        }
                    });
                    let palletProdQtyLeft = parseInt(availablePalletProdQty) - parseInt(totalProdQtyFromSamePallet);
                    if ($('#mquantity').val() > palletProdQtyLeft) {
                    // qty you want to add exceeds qty available on pallet
                        // alert("Product quantity selected is " + $('#mquantity').val() + ", but you already added " + totalProdQtyFromSamePallet + " products from this pallet to this sale. Product quantity left on this pallet is " + palletProdQtyLeft + ". Add same product quantity or less from this pallet or select another pallet with enough product quantity.");
                        if (totalProdQtyFromSamePallet > 0) {
                            // You already added products from this pallet
                            // You are trying to add more products from this pallet
                            // but qty left is less than the qty you want to add
                            alert("Product quantity selected is " + $('#mquantity').val() + ", but you already added " + totalProdQtyFromSamePallet + " products from this pallet to this sale. Product quantity left on this pallet is " + palletProdQtyLeft + ". Add same product quantity or less from this pallet or select another pallet with enough product quantity.");
                        } else {
                            // You havent add products from this pallet but
                            // qty left is less than the qty you want to add
                            alert("Product quantity selected is " + $('#mquantity').val() + ". Product quantity available on this pallet is " + palletProdQtyLeft + ". Select another pallet with enough product quantity or in case this pallet still has available items add equal or less product quantity.");
                        }
                        $('#mquantity').val("");

                    } else {

                        // addSaleItem();

                    };

                    // console.log("totalProdQtyFromSamePallet");
                    // console.log(totalProdQtyFromSamePallet);
                    // console.log("palletProdQtyLeft");
                    // console.log(palletProdQtyLeft);
                }

            }





        });

        // *********************************************************************
        //  HANDLE ADDING AN ITEM TO THIS SALE - MODAL FORM SUBMIT
        // *********************************************************************

        $('#addItemManually').on("click", function(e) {

            // -----------------------------------------------------------------
            // Make sure all required fields are not empty
            // -----------------------------------------------------------------

            if (
               $('#mproduct_id').val() === "" || $('#mproduct_id').val() === null || $('#mproduct_id').val() === undefined
            || $('#mcode').val() === "" || $('#mcode').val() === null || $('#mcode').val() === undefined
            || $('#mname').val() === "" || $('#mname').val() === null || $('#mname').val() === undefined
            || $('#mpallet_id').val() === "" || $('#mpallet_id').val() === null || $('#mpallet_id').val() === undefined
            || $('#mtax').val() === "" || $('#mtax').val() === null || $('#mtax').val() === undefined
            || $('#mquantity').val() === "" || $('#mquantity').val() === null || $('#mquantity').val() === undefined
            || $('#munit').val() === "" || $('#munit').val() === null || $('#munit').val() === undefined
            || $('#mprice').val() === "" || $('#mprice').val() === null || $('#mprice').val() === undefined
            ) {
                alert("The field labels marked with * are required input fields.")
                e.preventDefault();
                return false;
            };

            // -----------------------------------------------------------------
            // Add new sale item to localStorage
            // -----------------------------------------------------------------

            function addSaleItem() {

                console.log("addSaleItem");

                // Make sure all values are not empty and contain numbers

                // get order items, with pallet info
                // if undefined, create a new array and add  a new object...
                // { prodId, qty, palletId }

                let prodId = $('#mproduct_id').val();
                let qty = $('#mquantity').val();
                let palletId = $('#mpallet_id').val();

                let prodIdHasVal = false;
                let qtyHasVal = false;
                let palletIdHasVal = false;

                function isNumber(n) {
                  return !isNaN(parseFloat(n)) && isFinite(n);
                }

                if (prodId !== "" || prodId !== undefined || prodId !== null) {
                    if (isNumber(prodId)) {
                        prodIdHasVal = true;
                    }
                }
                if (qty !== "" || qty !== undefined || qty !== null) {
                    if (isNumber(qty)) {
                        qtyHasVal = true;
                    }
                }
                if (palletId !== "" || palletId !== undefined || palletId !== null) {
                    if (isNumber(palletId)) {
                        palletIdHasVal = true;
                    }
                }

                if (prodIdHasVal === true && qtyHasVal === true && palletIdHasVal === true) {
                    console.log("ProductID: " + prodId + " - PalletID: " + qty + " - Qty: " + palletId);

                    let orderItem = { prodId, qty, palletId };

                    var orderItems = JSON.parse(localStorage.getItem('form-add_sale-items'));
                    console.log("orderItems");
                    console.log(orderItems);

                    if (orderItems === null || orderItems === undefined) {
                        orderItems = [];
                    }

                    let updatedOrderItems = [];
                    updatedOrderItems.push(...orderItems);
                    updatedOrderItems.push(orderItem);

                    localStorage.setItem('form-add_sale-items', JSON.stringify(updatedOrderItems));

                    console.log("orderItems");
                    console.log(orderItems);

                    // later, when removing item from table, remove it from this localStorage as well..
                };

            };
            addSaleItem();

        });

    });
</script>


<!-- sale_form-sale_status -->
