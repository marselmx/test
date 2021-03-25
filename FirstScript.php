<?php
/**
 * Функция возвращает true если json валидна
 *
 * @param $json
 *
 * @return boolean
 *
 */
function ValidationJson($json): bool {
	
	//если true то кавычки " открытые, если false то кавычки " закрытые
	$isQuot = false;
	
	//количество фигурных скобок
	$countCurly = 0;
	
	//количество квадратных скобок
	$countSquare= 0;
	
	//убираем пробелы и переносы строк
	$json_str = [];
	for ($i = 0 ; $json[$i] != ""; $i++) {
		
		if($json[$i] == " ")  continue;
		if($json[$i] == "\b") continue;
		if($json[$i] == "\n") continue;
		if($json[$i] == "\t") continue;
		if($json[$i] == "\r") continue;
		if($json[$i] == "\v") continue;
		if($json[$i] == "") continue;
	
		$json_str[] = $json[$i];
	}
	
	//проверяем данные
	for ($i = 0 ; $json_str[$i] != ""; $i++) {
		
		//проверяем первый символ 
		if($json_str[0] != '{') 
			return false;
		
		//производим проверку корректность сопаставление символов
		if(!$isQuot){
			
			if( $json_str[$i] == '{'  && !( $json_str[$i+1] == '"' || $json_str[$i+1] == '}' ) ) 
				return false;
			
			if( $json_str[$i] == '}'  && !( $json_str[$i+1] == ',' || $json_str[$i+1] == '}' || $json_str[$i+1] == "") ) 
				return false;
			
			if( $json_str[$i] == '['  && !( $json_str[$i+1] == '"' ) )
				return false;

			if( $json_str[$i] == ']'  && !( $json_str[$i+1] == ',' || $json_str[$i+1] == '}' ) )
				return false;

			if( $json_str[$i] == ':'  && !( $json_str[$i+1] == '"' || $json_str[$i+1] == '{' || $json_str[$i+1] == '[' ) )
				return false;

			if( $json_str[$i] == ','  && !( $json_str[$i+1] == '"') )
				return false;
			
			//проверяем корректность на кол-во фигурных скобок
			if($json_str[$i] == '{') 
				$countCurly++;
			
			if($json_str[$i] == '}') 
				$countCurly--;
			
			if($countCurly < 0 )
				return false;
			
			//проверяем корректность на кол-во квадратных скобок
			if($json_str[$i] == '['){
				$countSquare++;
			}
			
			if($json_str[$i] == ']'){
				$countSquare--;
			}
			
			if($countSquare < 0 )
				return false;
			
			if($countSquare > 0){
				if($json_str[$i] == ':')
					return false;
				
				if($json_str[$i] == '{')
					return false;
				
				if($json_str[$i] == '}')
					return false;
			}
		
		}
		
		//если были открыты и закрыты кавычки
		if($json_str[$i] == '"'){
			
			if(!$isQuot) {
				if( !($json_str[$i-1] == "{" || $json_str[$i-1] == ":" || $json_str[$i-1] == "," || $json_str[$i-1] == "[")  )
					return false;
				$isQuot = true;
			}
			else{
				if( !($json_str[$i+1] == "}" || $json_str[$i+1] == ":" || $json_str[$i+1] == "," || $json_str[$i+1] == "]")  )
					return false;
				$isQuot = false;
			}
			
		}

		echo $i." - [".$json_str[$i]."] *".$json_str[$i+1]."* {".$countCurly."} [".$countSquare."] ".var_dump($isQuot)."<br>";
		
	}
	
	if($countCurly != 0) return false;
	if($countSquare != 0) return false;
    return true;
}