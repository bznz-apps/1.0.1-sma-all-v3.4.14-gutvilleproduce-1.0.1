<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2018-12-26 13:31:33 --> Could not find the language line "logout_successful"
ERROR - 2018-12-26 13:31:33 --> Could not find the language line "logout_successful"
ERROR - 2018-12-26 13:43:58 --> Query error: Table 'sma.sma_payment' doesn't exist - Invalid query: SELECT SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS returned
FROM `sma_payment`
LEFT JOIN `sma_sales` ON `sma_sales`.`id`=`sma_payments`.`return_id`
WHERE `type` = 'returned'
AND `sma_payments`.`date` > '2018-07-18 12:46:30'
AND `sma_payments`.`created_by` = '4'
ERROR - 2018-12-26 13:48:01 --> Severity: Notice --> Undefined variable: data /Users/Saleem/Desktop/Projects/ci/sma/app/models/admin/Pos_model.php 1320
ERROR - 2018-12-26 13:49:36 --> Query error: Unknown column 'sma_sales.type' in 'where clause' - Invalid query: SELECT SUM( COALESCE( grand_total, 0 ) ) AS total, SUM( COALESCE( amount, 0 ) ) AS returned
FROM `sma_payments`
LEFT JOIN `sma_sales` ON `sma_sales`.`id`=`sma_payments`.`return_id`
WHERE `sma_sales`.`type` = 'returned'
AND `sma_payments`.`date` > '2018-07-18 12:46:30'
AND `sma_payments`.`created_by` = '4'
