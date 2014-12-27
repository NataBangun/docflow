<?php

if ( ! function_exists('oci8_send_email'))
{
	function oci8_send_email($values) 
	{		
		$CI =& get_instance();		
		$conn = $CI->db->conn_id;
		
		$values['V_SUBJECT'] = "[DOCFLOW] FOR TESTING PURPOSE & PLEASE IGNORE: " . $values['V_SUBJECT'];	
		$values['V_MESSAGE'] = $values['V_MESSAGE'] . '<br><br>' 
                            . 'To: ' . $values['V_TO'] . '<br>' 
                            . 'Cc: ' . $values['V_CC'] . '<br>'
                            . 'Bcc: ' . $values['V_BCC'] . '<br>';
		$values['V_TO'] = 'suryo.hartanto@lintasarta.co.id';
		$values['V_CC'] = null;
		$values['V_BCC'] = null;
		$values['V_SERVER'] = '10.24.19.34';
		
		if (isset($values['V_ATTACH_TYPE']) && $values['V_ATTACH_TYPE']!=null) {
			$stid = oci_parse($conn, "
				BEGIN 
					SP_SEND_MAIL(
						:V_SENDER, 
						:V_TO, 
						:V_CC, 
						:V_BCC, 
						:V_SUBJECT, 
						:V_MESSAGE,
						:V_ATTACH_TYPE,
						:V_ATTACH_NAME,
						:V_ATTACH_CLOB,
						:V_SERVER
					); 
				END;
				");
			$msg_lob = oci_new_descriptor($conn, OCI_D_LOB);
			$atc_lob = oci_new_descriptor($conn, OCI_D_LOB);
			
			oci_bind_by_name($stid, ':V_SENDER', $values['V_SENDER']);
			oci_bind_by_name($stid, ':V_TO', $values['V_TO']);
			oci_bind_by_name($stid, ':V_CC', $values['V_CC']);
			oci_bind_by_name($stid, ':V_BCC', $values['V_BCC']);
			oci_bind_by_name($stid, ':V_SUBJECT', $values['V_SUBJECT']);		
			oci_bind_by_name($stid, ':V_MESSAGE', $msg_lob, -1, OCI_B_CLOB);
			oci_bind_by_name($stid, ':V_ATTACH_TYPE', $values['V_ATTACH_TYPE']);		
			oci_bind_by_name($stid, ':V_ATTACH_NAME', $values['V_ATTACH_NAME']);		
			oci_bind_by_name($stid, ':V_ATTACH_CLOB', $atc_lob, -1, OCI_B_CLOB);
			oci_bind_by_name($stid, ':V_SERVER', $values['V_SERVER']);		
			$msg_lob->writeTemporary(chunk_split(base64_encode($values['V_MESSAGE'])));
			$atc_lob->writeTemporary(chunk_split(base64_encode($values['V_ATTACH_CLOB'])));
			
			$level = error_reporting(); // backup error level
			error_reporting(0); // turn off error level
			$oci_result = oci_execute($stid);  // executes and commits
			if (!$oci_result) {
				$result = oci_error($stid);
				//var_dump($result);
				$result = $result['message'];
			}
			error_reporting($level); // turn on with backup error level
			
			$msg_lob->close();
			$atc_lob->close();
			oci_free_statement($stid);
		} else {
			$stid = oci_parse($conn, "
				BEGIN 
					SP_SEND_MAIL(
						:V_SENDER, 
						:V_TO, 
						:V_CC, 
						:V_BCC, 
						:V_SUBJECT, 
						:V_MESSAGE,
						NULL,
						NULL,
						NULL,
						:V_SERVER
					); 
				END;
				");
			$msg_lob = oci_new_descriptor($conn, OCI_D_LOB);
			
			oci_bind_by_name($stid, ':V_SENDER', $values['V_SENDER']);
			oci_bind_by_name($stid, ':V_TO', $values['V_TO']);
			oci_bind_by_name($stid, ':V_CC', $values['V_CC']);
			oci_bind_by_name($stid, ':V_BCC', $values['V_BCC']);
			oci_bind_by_name($stid, ':V_SUBJECT', $values['V_SUBJECT']);		
			oci_bind_by_name($stid, ':V_MESSAGE', $msg_lob, -1, OCI_B_CLOB);
			oci_bind_by_name($stid, ':V_SERVER', $values['V_SERVER']);		
			$msg_lob->writeTemporary(chunk_split(base64_encode($values['V_MESSAGE'])));
			
			$level = error_reporting(); // backup error level
			error_reporting(0); // turn off error level
			$oci_result = oci_execute($stid);  // executes and commits
			if (!$oci_result) {
				$result = oci_error($stid);
				//var_dump($result);
				$result = $result['message'];
			}
			error_reporting($level); // turn on with backup error level

			$msg_lob->close();
			oci_free_statement($stid);
		}
		// oci_close($conn);
		if (!isset($result)) {
			return "1";
		} else {
			return $result;
		}
	}
}

if ( ! function_exists('oci8_insert_bind'))
{
	function oci8_insert_bind($table, $values) 
	{		
		$CI =& get_instance();
		
		$query = $CI->db->query('SELECT * FROM ALL_TAB_COLS WHERE OWNER=USER AND TABLE_NAME=? ORDER BY COLUMN_ID ASC', array($table));
		$fields = $query->result_array();
		
		$columns = array();
		$bind_columns = array();
		$sql_clob = "";
		$keys = array_keys($values);
		foreach ($fields as $k=>$v) {
			if (in_array($v['COLUMN_NAME'], $keys)) {
				$columns[] = $v['COLUMN_NAME'];
				if ($v['DATA_TYPE'] == 'CLOB') {
					$bind_columns[] = 'EMPTY_CLOB()';
					$sql_clob = "RETURNING {$v['COLUMN_NAME']} INTO :{$v['COLUMN_NAME']}";
				} else {
					$bind_columns[] = ':'.$v['COLUMN_NAME'];
				}
			}
		}
		$sql = "INSERT INTO $table (".implode(",", $columns).") VALUES (".implode(",", $bind_columns).") ".$sql_clob;
		
		$stid = oci_parse($CI->db->conn_id, $sql);
		
		foreach ($fields as $k=>$v) {
			if (in_array($v['COLUMN_NAME'], $keys)) {
				if ($v['DATA_TYPE'] == 'CLOB') {
					$clob = oci_new_descriptor($CI->db->conn_id, OCI_D_LOB);
					$clob_value = $values[ $v['COLUMN_NAME'] ];
					oci_bind_by_name($stid, ':'.$v['COLUMN_NAME'], $clob, -1, OCI_B_CLOB);
				} else {
					if ($v['DATA_TYPE'] == 'NUMBER') {
						$type = SQLT_INT;
					} else {
						$type = SQLT_CHR;
					}
					oci_bind_by_name($stid, ':'.$v['COLUMN_NAME'], $values[ $v['COLUMN_NAME'] ], $v['DATA_LENGTH'], $type);
				}
			}
		}
		
		if (isset($clob)) {
			// oci_execute($stid, OCI_NO_AUTO_COMMIT);  // use OCI_DEFAULT for PHP <= 5.3.1
			oci_execute($stid, OCI_DEFAULT);  // use OCI_DEFAULT for PHP <= 5.3.1
			$clob->save($clob_value);
			oci_commit($CI->db->conn_id);
		} else {
			oci_execute($stid);  // executes and commits
		}
		
		oci_free_statement($stid);
		// oci_close($conn);
		return "1";

	}
}

if ( ! function_exists('oci8_update_bind'))
{
	function oci8_update_bind($table, $values, $where_values) 
	{		
		$CI =& get_instance();
		
		$query = $CI->db->query('SELECT * FROM ALL_TAB_COLS WHERE OWNER=USER AND TABLE_NAME=? ORDER BY COLUMN_ID ASC', array($table));
		$fields = $query->result_array();
		
		$sql_columns = array();
		// $columns = array();
		// $bind_columns = array();
		$sql_clob = "";
		$keys = array_keys($values);
		foreach ($fields as $k=>$v) {
			if (in_array($v['COLUMN_NAME'], $keys)) {
				$column = $v['COLUMN_NAME'];
				if ($v['DATA_TYPE'] == 'CLOB') {
					$bind_column = 'EMPTY_CLOB()';
					$sql_clob = "RETURNING {$v['COLUMN_NAME']} INTO :{$v['COLUMN_NAME']}";
				} else {
					$bind_column = ':'.$v['COLUMN_NAME'];
				}
				$sql_columns[] = $column .'='. $bind_column;
			}			
		}
		$sql_where_values = array();
		foreach ($where_values as $k=>$v) {
			$sql_where_values[] = $k .'=:'.$k;
		}
		if (count($sql_where_values) > 0) {
			$sql_where = 'WHERE '.implode(",", $sql_where_values);
		} else {
			$sql_where = '';
		}
		$sql = "UPDATE $table SET ".implode(",", $sql_columns)." ". $sql_where ." ".$sql_clob;
		
		$stid = oci_parse($CI->db->conn_id, $sql);
		
		$where_keys = array_keys($where_values);
		foreach ($fields as $k=>$v) {
			if (in_array($v['COLUMN_NAME'], $keys)) {
				if ($v['DATA_TYPE'] == 'CLOB') {
					$clob = oci_new_descriptor($CI->db->conn_id, OCI_D_LOB);
					$clob_value = $values[ $v['COLUMN_NAME'] ];
					oci_bind_by_name($stid, ':'.$v['COLUMN_NAME'], $clob, -1, OCI_B_CLOB);
				} else {
					if ($v['DATA_TYPE'] == 'NUMBER') {
						$type = SQLT_INT;
					} else {
						$type = SQLT_CHR;
					}
					oci_bind_by_name($stid, ':'.$v['COLUMN_NAME'], $values[ $v['COLUMN_NAME'] ], $v['DATA_LENGTH'], $type);
				}
			}
			if (in_array($v['COLUMN_NAME'], $where_keys)) {
				if ($v['DATA_TYPE'] == 'NUMBER') {
					$type = SQLT_INT;
				} else {
					$type = SQLT_CHR;
				}
				oci_bind_by_name($stid, ':'.$v['COLUMN_NAME'], $where_values[ $v['COLUMN_NAME'] ], $v['DATA_LENGTH'], $type);
			}
		}
		
		if (isset($clob)) {
			// oci_execute($stid, OCI_NO_AUTO_COMMIT);  // use OCI_DEFAULT for PHP <= 5.3.1
			oci_execute($stid, OCI_DEFAULT);  // use OCI_DEFAULT for PHP <= 5.3.1
			$clob->save($clob_value);
			oci_commit($CI->db->conn_id);
		} else {
			oci_execute($stid);  // executes and commits
		}
		
		oci_free_statement($stid);
		// oci_close($conn);
		return "1";

	}
}

