<?php
  $str =  <<<EOF
  ( received
      , email
      , request_json
      , status
      , commit_hash
      , branch

      ) VALUES
      ( '2020-07-24 01:17:34'
      , 'susan@susanflores.com'
      , '{"event":"order.subscription_payment","mode":"live","mode_int":"2","thrivecart_account":"engagemorecrm","thrivecart_secret":"IEYDASLZ8FR7","base_product":"17","base_product_name":"$69\/Month EngageMore Patti Special Friend Price","base_product_label":"$69\/Month Patti's Special Friend Pricing","base_payment_plan":"31670","base_product_owner":"56894","currency":"USD","recurring_payment_idx":"6","customer_id":"16099259","customer":{"id":"16099259","email":"susan@susanflores.com","contactno":"9152091701","ip_address":"72.179.132.163","address":{"country":"US"},"first_name":"Susan","last_name":"Flores","name":"Susan Flores","checkbox_confirmation":"false"},"affiliate":"null","date":"2020-07-24 05:17:34","date_iso8601":"2020-07-24T05:17:34+00:00","date_unix":"1595567854","order":{"id":"3230022","invoice_id":"000000141","processor":"stripe","total":"6900","total_str":"69.00","total_gross":"6900","total_gross_str":"69.00","date":"2020-01-24 04:15:51","date_iso8601":"2020-01-24T04:15:51+00:00","date_unix":"1579839351","tracking_id":"null","tax":"null"},"order_id":"3230022","invoice_id":"000000141-6","subscription":{"type":"product","id":"17","product_id":"17","name":"$69\/Month EngageMore Patti Special Friend Price","label":"$69\/Month Patti's Special Friend Pricing","processor":"stripe","currency":"USD","invoice_id":"000000141","amount":"6900","amount_str":"69.00","amount_gross":"6900","amount_gross_str":"69.00","tax_paid":"0","tax_paid_str":"0.00","frequency":"month","frequency_days":"30","remaining_rebills":"null","invoice_str":"000000141-6","recurring_payment_idx":"6","next_payment_date":"1598246253","next_payment_date_iso8601":"2020-08-24T05:17:33+00:00"}}'
      , 'order.subscription_payment for product-17'
      , '3a94d08'
      , 'add-free-trial'
      )
EOF;

//      echo htmlspecialchars($str, ENT_COMPAT); // Will only convert double quotes
      echo str_replace("\/","_",$str);
?>
