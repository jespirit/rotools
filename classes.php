<?php

include_once 'functions.php';

class ResultClass {
	var $result;
	var $row;

	function ResultClass($arg1) {
		$this->row = FALSE;
		$this->result = $arg1;
	}

	function fetch_row() {
		if ($this->result !== TRUE && $this->result !== FALSE)
			$this->row = mysqli_fetch_row($this->result);
		else
			$this->row = FALSE;
		return $this->row;
	}

	function fetch_assoc() {
		if ($this->result !== TRUE && $this->result !== FALSE)
			$this->row = mysqli_fetch_assoc($this->result);
		else
			$this->row = FALSE;
		return $this->row;
	}

	function count() {
		if ($this->result)
			return mysqli_num_rows($this->result);
		return 0;
	}

	function row($pos) {
		if (isset($this->row[$pos]))
			return $this->row[$pos];
		return FALSE;
	}

	function free() {
		if (empty($this->result))
			return;
		if ($this->result !== TRUE && $this->result !== FALSE)
			mysqli_free_result($this->result);
	}

}

class QueryClass {
	var $rag_link;
	var $cp_link;
	var $log_link;
	var $stmt;
	var $q, $params;

	function QueryClass($rag_addr, $rag_username, $rag_password, $rag_db, $cp_addr, $cp_username, $cp_password, $cp_db, $log_db) {
		global $lang;

		$this->rag_link = mysqli_connect($rag_addr,$rag_username,$rag_password,$rag_db) or die($lang['DB_ERROR']);
		$this->cp_link = mysqli_connect($cp_addr,$cp_username,$cp_password,$cp_db) or die($lang['DB_ERROR']);
		$this->log_link = mysqli_connect($rag_addr,$rag_username,$rag_password,$log_db) or die($lang['DB_ERROR']);
	}

	function Prepare($query, $database /*, $types, ... */) {
		switch ($database) {
			case 0:
				$this->stmt = $this->rag_link->prepare($query);
				break;
			case 1:
				$this->stmt = $this->cp_link->prepare($query);
				break;
			case 2:
				$this->stmt = $this->log_link->prepare($query);
				break;
		}
		
		$q = $query;  // save query
		
		if ($this->stmt) {
			if (func_num_args() >= 4) {  // are there parameters to bind?
				$this->params = array_slice(func_get_args(), 2);
				call_user_func_array(array($this->stmt, "bind_param"), refValues($this->params));
			}
		}
		else
			trigger_error('Failed to prepare statement for ' . $query);
		
		return $this->stmt;
	}
	
	function Query($stmt) {
		$ret = $stmt->execute();
		if ($ret) {
			$result = $stmt->get_result();
			if ($result)
				return $result;
			else
				return TRUE;  // Non-SELECT statement
		}

		return FALSE;  // FALSE because of failure
	}
	
	/**
	 * Replaces any parameter placeholders in a query with the value of that
	 * parameter. Useful for debugging. Assumes anonymous parameters from 
	 * $params are are in the same order as specified in $query
	 *
	 * @param string $query The sql query with parameter placeholders
	 * @param array $params The array of substitution parameters
	 * @return string The interpolated query
	 */
	function Interpolate() {
		if (!isset($this->q))
			return FALSE;
		$keys = array();

		# build a regular expression for each parameter
		foreach ($this->params as $key => $value) {
			if (is_string($key)) {
				$keys[] = '/:'.$key.'/';
			} else {
				$keys[] = '/[?]/';
			}
		}

		$limit = 1;
		$query = preg_replace($keys, $this->params, $this->q, $limit, $count);

		#trigger_error('replaced '.$count.' keys');

		return $query;
	}

	function finish() {
		if (empty($this->stmt))
			return;
		if ($this->stmt !== TRUE && $this->stmt !== FALSE)
			$this->stmt->close();
	}
}

?>
