<?php
//чтение данных из файла input.csv
$addresses = [];
if (($handle = fopen("input.csv", "r")) !== false) {
	
	while ( ($data = fgetcsv($handle, 1000, ",")) !== false ) {
		
		if($data[2] == "да")
			$addresses[$data[0]][] = $data[1];
		
	}
	
	fclose($handle);
}

//запись данных в файл output.csv
$fp = fopen('output.csv', 'w');
fputcsv($fp, ["Улица", "Дом", "Активный"], ',');
foreach($addresses as $street => $houses){
	
	for ($i=0; $i < count($houses) - 1; $i++) {
		for ($j=0; $j < (count($houses) - 1) - $i; $j++) {
			if ($houses[$j] > $houses[$j + 1]){
				$tmp = $houses[$j + 1];
				$houses[$j + 1] = $houses[$j];
				$houses[$j] = $tmp;
			}
		}
	}
	
	foreach($houses as $house)
		fputcsv($fp, [$street, $house, "да"], ',');
}
fclose($fp);
