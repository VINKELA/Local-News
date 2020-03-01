<?php

    require(__DIR__ . "/../includes/config.php");

    // numerically indexed array of places
    $places = [];

    // TODO: search database for places matching $_GET["geo"]
    $rows;
   	$geo = $_GET["geo"];
   	if(($data = strchr($geo, ",")) === false)
	{
		$data = strchr($geo, " ");
	}
	trim($data);

   	if ($data === false)
   	{
			trim($geo);
			$city = $geo;
   		$rows = query("SELECT * FROM places WHERE place_name LIKE ? OR admin_name1 LIKE ? OR postal_code LIKE ? ", $city."%",$city."%",$city."%");
   	}
   	else
   	{

   		if(($city = strchr($geo, ",", true)) === false)
		{
			$city = strchr($geo, " ", true);
		}
   		trim($city,",");
		print("\n".$city."\n");
   		$data1 = trim($data, ",");
		$state = trim($data1);
		if(($temp = strchr($state, ",", true)) === false)
		{
			$temp = strchr($state, " ",true);
		}
		if($temp != false)
		{
			$state = $temp;
		}
   		trim($state,",");
		print("\n".$state."\n");
   		$data2 = strchr($data1, ",");
		if(($data2 = strchr($data1, ",")) == false)
		{
			$data2 = strchr($data1, " ");
		}
   		if($data2 != false)
   		{

   			$postcode = trim($data2, ",");
   			$rows = query("SELECT * FROM places WHERE (place_name  LIKE ? AND postal_code LIKE ? AND admin_name1 LIKE ?) OR (place_name  LIKE ? AND postal_code LIKE ?)OR(place_name  LIKE ? AND admin_name1 LIKE ?)OR(admin_name1  LIKE ? AND postal_code LIKE ?)OR place_name  LIKE ? OR postal_code LIKE ? OR admin_name1 LIKE ? ",$city."%", $postcode."%", $state."%"."%",$city."%", $postcode."%",$city."%",$state."%",$state."%",$postcode."%",$city."%",$postcode."%", $state."%");

   		}
   		else
   		{

   			$rows = query("SELECT * FROM places WHERE (place_name LIKe ? AND admin_name1  LIKE ? )OR(place_name  LIKE ? AND postal_code LIKE ?)OR(admin_name1  LIKE ? AND postal_code LIKE ?)OR place_name  LIKE ? OR postal_code LIKE ? OR admin_name1 LIKE ? ",$city."%", $state."%",$city."%",$postcode."%",$state."%",$postcode."%",$city."%", $postcode."%", $state."%");

   		}

   	}
   	foreach ($rows as $row) 
   	{
   		$places[] = 
   		[
   			"id" => $row["id"],
   			"country_code" => $row["country_code"],
   			"postal_code" => $row["postal_code"],
   			"place_name" => $row["place_name"],
   			"admin_name1" => $row["admin_name1"],
   			"admin_name2" => $row["admin_name2"],
   			"admin_name3"=> $row["admin_name3"],
   			"admin_code1" => $row["admin_code1"],
   			"admin_code2" => $row["admin_code2"],
   			"admin_code3" => $row["admin_code3"],
   			"latitude" => $row["latitude"],
   			"longitude" => $row["longitude"],
   			"accuracy" => $row["accuracy"],
   		];
   	}

    // output places as JSON (pretty-printed for debugging convenience)
    header("Content-type: application/json");
    print(json_encode($places, JSON_PRETTY_PRINT));

?>
