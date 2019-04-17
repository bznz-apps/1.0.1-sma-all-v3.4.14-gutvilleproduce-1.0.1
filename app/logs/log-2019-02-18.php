<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2019-02-18 16:37:15 --> Severity: Notice --> Undefined property: stdClass::$status /Users/Saleem/Desktop/Projects/ci/sma/themes/default/admin/views/sales/modal_view.php 179
ERROR - 2019-02-18 16:43:50 --> Query error: Unknown column 'sma_units.product_id' in 'on clause' - Invalid query: SELECT `sma_sale_items`.*, `sma_tax_rates`.`code` as `tax_code`, `sma_tax_rates`.`name` as `tax_name`, `sma_tax_rates`.`rate` as `tax_rate`, `sma_products`.`image`, `sma_products`.`details` as `details`, `sma_product_variants`.`name` as `variant`, `sma_products`.`hsn_code` as `hsn_code`, `sma_products`.`second_name` as `second_name`, `sma_products`.`unit` as `base_unit`, `sma_units`.`code` as `base_unit_code`
FROM `sma_sale_items`
LEFT JOIN `sma_products` ON `sma_products`.`id`=`sma_sale_items`.`product_id`
LEFT JOIN `sma_product_variants` ON `sma_product_variants`.`id`=`sma_sale_items`.`option_id`
LEFT JOIN `sma_tax_rates` ON `sma_tax_rates`.`id`=`sma_sale_items`.`tax_rate_id`
LEFT JOIN `sma_units` ON `sma_units`.`product_id`=`sma_products`.`unit`
WHERE `sale_id` = '26'
GROUP BY `sma_sale_items`.`id`
ORDER BY `id` ASC
ERROR - 2019-02-18 16:44:03 --> Query error: Unknown column 'sma_units.product_id' in 'on clause' - Invalid query: SELECT `sma_sale_items`.*, `sma_tax_rates`.`code` as `tax_code`, `sma_tax_rates`.`name` as `tax_name`, `sma_tax_rates`.`rate` as `tax_rate`, `sma_products`.`image`, `sma_products`.`details` as `details`, `sma_product_variants`.`name` as `variant`, `sma_products`.`hsn_code` as `hsn_code`, `sma_products`.`second_name` as `second_name`, `sma_products`.`unit` as `base_unit`, `sma_units`.`code` as `base_unit_code`
FROM `sma_sale_items`
LEFT JOIN `sma_products` ON `sma_products`.`id`=`sma_sale_items`.`product_id`
LEFT JOIN `sma_product_variants` ON `sma_product_variants`.`id`=`sma_sale_items`.`option_id`
LEFT JOIN `sma_tax_rates` ON `sma_tax_rates`.`id`=`sma_sale_items`.`tax_rate_id`
LEFT JOIN `sma_units` ON `sma_units`.`product_id`=`sma_products`.`unit`
WHERE `sale_id` = '26'
GROUP BY `sma_sale_items`.`id`
ORDER BY `id` ASC
ERROR - 2019-02-18 18:12:23 --> Could not find the language line "P822D"
ERROR - 2019-02-18 19:28:07 --> Severity: Notice --> A non well formed numeric value encountered /Users/Saleem/Desktop/Projects/ci/sma/app/libraries/Sma.php 79
ERROR - 2019-02-18 19:28:39 --> Severity: Notice --> A non well formed numeric value encountered /Users/Saleem/Desktop/Projects/ci/sma/app/libraries/Sma.php 79
ERROR - 2019-02-18 19:29:14 --> Severity: error --> Exception: syntax error, unexpected '$number' (T_VARIABLE), expecting ',' or ')' /Users/Saleem/Desktop/Projects/ci/sma/app/libraries/Sma.php 79
ERROR - 2019-02-18 19:29:34 --> Severity: Notice --> A non well formed numeric value encountered /Users/Saleem/Desktop/Projects/ci/sma/app/libraries/Sma.php 79
ERROR - 2019-02-18 19:30:26 --> Severity: Notice --> A non well formed numeric value encountered /Users/Saleem/Desktop/Projects/ci/sma/themes/default/admin/views/sales/packaging.php 33
