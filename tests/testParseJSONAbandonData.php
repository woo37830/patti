<?php

$json = '{"event":"cart.abandoned",
"thrivecart_account":"engagemorecrm",
"thrivecart_secret":"IEYDASLZ8FR7",
"base_product":"24",
"base_product_name":"EngageMore CRM Lead Integration",
"base_product_label":"Zapier Integration Bundle (monthly)",
"base_product_owner":"56894",
"viewer": {"name": "Ralph",
	"email": "ralph@tester.com",
"ip_address":"73.222.119.250",
"address":{"country":"US"},
"first_name":"Stephanie",
"last_name":"Marrone",
"checkbox_confirmation":"false"},
	"customer":{"name":"Stephanie Marrone",
"email":"baymarrones@comcast.net",
"ip_address":"73.222.119.250",
"address":{"country":"US"},
"first_name":"Stephanie",
"last_name":"Marrone",
"checkbox_confirmation":"false"},
"date":"2020-08-31 16:03:41",
"date_iso8601":"2020-08-31T16:03:41+00:00",
"date_unix":"1598889821"
}';
$arr = json_decode($json);

echo "Event: $arr->event \n";
echo "Email: " . $arr->viewer->email . "\n";

$json = '{
		"event":"cart.abandoned",
    "adminCode1": "16",
    "lng": "74.19774",
    "distance": "2.95838",
    "geonameId": 1254611,
    "toponymName": "Thengode",
    "countryId": "1269750",
    "fcl": "P",
    "population": 0,
    "countryCode": "IN",
    "name": "Thengode",
    "fclName": "city, village,...",
    "countryName": "India",
    "fcodeName": "populated place",
    "adminName1": "Maharashtra",
    "lat": "20.51997",
    "fcode": "PPL",
		"viewer": {"name": "Ralph",
			"email": "ralph@tester.com"
		}
}';
$jsonDataObject = json_decode($json);

$name = $jsonDataObject->name;
//parsed variables
//$name = $jsonDataObject->geonames[0]->name;
$adminName1 = $jsonDataObject->geonames[0]->adminName1;
$countryCode = $jsonDataObject->geonames[0]->countryCode;

echo $name . "\n";
echo $jsonDataObject->event . "\n";
echo $jsonDataObject->viewer->name . "\n";
echo $jsonDataObject->viewer->email . "\n";
echo $adminName1 . "\n";
echo $countryCode . "\n";



?>
