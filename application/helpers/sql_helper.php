<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	if ( ! function_exists('build_where_sql'))
	{
	    function build_where_sql($options, $type) {
			
				$operand = ($type == 'like') ? " LIKE " : " = ";
				$flank = ($type == 'like') ? "%" : "";
				
				$last_key = key(array_slice($options, -1, 1, TRUE));
				$sql_string = "WHERE";
				foreach($options as $key => $value) {
					
					if($value != "" && $key != $value) {
						$sql_string .= " ".$key.$operand."'".$flank.$value.$flank."'"." AND";
					}
					
					
				}
				
				return $sql_string;
				
			}
			
	}