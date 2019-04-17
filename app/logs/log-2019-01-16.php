<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2019-01-16 18:33:53 --> Query error: Table 'sma.sma_promos' doesn't exist - Invalid query: SELECT `sma_promos`.`id` as `id`, `sma_promos`.`name`, CONCAT(p2b.name, '(', `p2b`.`code`, ')') as product2buy, `quantity2buy`, CONCAT(p2g.name, '(', `p2g`.`code`, ')') as product2get, `quantity2get`, `sma_promos`.`start_date`, `sma_promos`.`end_date`
FROM `sma_promos`
LEFT JOIN `sma_products` as `p2b` ON `p2b`.`id`=`sma_promos`.`product2buy`
LEFT JOIN `sma_products` as `p2g` ON `p2g`.`id`=`sma_promos`.`product2get`
ORDER BY `sma_promos`.`name` ASC
 LIMIT 10
