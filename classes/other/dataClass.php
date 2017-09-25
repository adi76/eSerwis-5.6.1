<?


/**
 * Todo List
 * 
 * Impostare la lista delle stringe base di PHP 
 * 
*/
/**
 * Version History
 * 
 * 0.0.2
 * Impostato correttamente l'accesso alle aree critiche automatiche tramite _matchAutomatic
 * 0.0.1 
 * Impostate le prime interfacce di gestione del _timestamp e del _timeArray
 *
 */

interface dateInterface
{
	
}

class dateClass implements dateInterface 
{
	/**
	 * Returns today's date and time
	 *
	 */
	private $_timestamp;
	
	private $_timeArray;
	
	private $_matchAutomatic;

	private function _set_timestamp($now)
	{
		
		if ($now === true) 
			$this -> _timestamp = time();
	}
	private function _get_timestamp_month($value)
	{
		return mktime(date("h"),date("i"),intval(date("s")),intval($value),date("d"),date("Y"));
	}
	private function _get_timestamp_year($value)
	{
		return mktime(date("h"),date("i"),intval(date("s")),date("m"),date("d"),$value);
	}
	private function _get_timestamp_day($value)
	{
		return mktime(date("h"),date("i"),intval(date("s")),date("m"),$value,date("Y"));
	}
	public function Date($now)
	{
		$this -> _set_timestamp($now);
		return date("D M d h:i:s Y",$this -> _timestamp);
	}
	/**
	 * This function return anti o post meridian. The bool value set the Capitals Letter or Minusculs Letter
	 *
	 * @param boolean $format
	 * @param boolean $now
	 * @return String
	 */
	public function get_APmeridian($format = true,$now = false)
	{
		if ($now) $this -> _timeArray["APmeridian"] = date("a");
		if ($this -> _timeArray["APmeridian"] == null) 
			throw new Exception("Not Initialized",0);
		
		if ($format === true) 
			return strtoupper($this -> _timeArray["APmeridian"]);
		else 
			 return strtolower($this -> _timeArray["APmeridian"]);
	}
	public function get_Swatchtime($now = false)
	{
		if ($now) $this -> _timeArray["Swatchtime"] = date("B");
		if ($this -> _timeArray["Swatchtime"] == null) 
			throw new Exception("Not Initialized",0);
		return strtoupper($this -> _timeArray["Swatchtime"]);
	}
	public function get_Day($now = false)
	{
		if ($now) $this -> _timeArray["day"] = date("d");
		if ($this -> _timeArray["day"] == NULL) 
			throw new Exception("Not Initialized",0);
		return $this -> _timeArray["day"];
	}
	public function get_Dayweek($now = false)
	{
		if ($now) $this -> _timeArray["week"] = date("w");
		if ($this -> _timeArray["week"] == NULL) 
			throw new Exception("Not Initialized",0);
		return $this -> _timeArray["week"];
	}
	public function get_Dayweekword($now = false)
	{
		if ($now) $this -> _timeArray["weekword"] = date("D");
		if ($this -> _timeArray["weekword"] == NULL) 
			throw new Exception("Not Initialized",0);
		return $this -> _timeArray["weekword"];
	}
	public function get_Dayweekwordlong($now = false)
	{
		if ($now) $this -> _timeArray["weekwordlong"] = date("l");
		if ($this -> _timeArray["weekwordlong"] == NULL) 
			throw new Exception("Not Initialized",0);
		return $this -> _timeArray["weekwordlong"];
	}
	public function get_Daynotzero($now = false)
	{
		if ($now) $this -> _timeArray["daynotzero"] = date("j");
		if ($this -> _timeArray["daynotzero"] == NULL) 
			throw new Exception("Not Initialized",0);
		return $this -> _timeArray["daynotzero"];
	}
	public function get_Dayofmonth($now = false)
	{
		if ($now) $this -> _timeArray["dayofmonth"] = date("t");
		if ($this -> _timeArray["dayofmonth"] == NULL) 
			throw new Exception("Not Initialized",0);
		return $this -> _timeArray["dayofmonth"];
	}
	public function get_Dayofyear($now = false)
	{
		if ($now) $this -> _timeArray["dayofyear"] = date("z");
		if ($this -> _timeArray["dayofyear"] == NULL) 
			throw new Exception("Not Initialized",0);
		return $this -> _timeArray["dayofyear"];
	}
	public function get_Month($now = false)
	{
		if ($now) $this -> _timeArray["month"] = date("m");
		if ($this -> _timeArray["month"] == NULL) 
			throw new Exception("Not Initialized",0);
		return $this -> _timeArray["month"];
	}
	public function get_Monthword($now = false)
	{
		if ($now) $this -> _timeArray["monthword"] = date("M");
		if ($this -> _timeArray["monthword"] == NULL) 
			throw new Exception("Not Initialized",0);
		return $this -> _timeArray["monthword"];
	}
	public function get_Monthwordlong($now = false)
	{
		if ($now) $this -> _timeArray["monthwordlong"] = date("F");
		if ($this -> _timeArray["monthwordlong"] == NULL) 
			throw new Exception("Not Initialized",0);
		return $this -> _timeArray["monthwordlong"];
	}
	public function get_Monthnotzero($now = false)
	{
		if ($now) $this -> _timeArray["monthnotzero"] = date("n");
		if ($this -> _timeArray["monthnotzero"] == NULL) 
			throw new Exception("Not Initialized",0);
		return $this -> _timeArray["monthnotzero"];
	}
	public function get_FullYear($now = false)
	{
		if ($now) $this -> _timeArray["fullyear"] = date("Y");
		if ($this -> _timeArray["fullyear"] == NULL) 
			throw new Exception("Not Initialized",0);
		return $this -> _timeArray["fullyear"];
	}
	public function get_Year($now = false)
	{
		if ($now) $this -> _timeArray["year"] = date("y");
		if ($this -> _timeArray["year"] == NULL) 
			throw new Exception("Not Initialized",0);
		return $this -> _timeArray["year"];
	}
	public function get_YearBisestile($now = false)
	{
		if ($now) $this -> _timeArray["yearbisestile"] = date("L");
		if ($this -> _timeArray["yearbisestile"] == NULL) 
			throw new Exception("Not Initialized",0);
		return $this -> _timeArray["yearbisestile"];
	}
	public function get_Hours($now = false)
	{
		if ($now) $this -> _timeArray["hours"] = date("H");
		if ($this -> _timeArray["hours"] == NULL) 
			throw new Exception("Not Initialized",0);
		return $this -> _timeArray["hours"];
	}
	public function get_Hoursamnotzero($now = false)
	{
		if ($now) $this -> _timeArray["hoursamnotzero"] = date("g");
		if ($this -> _timeArray["hoursamnotzero"] == NULL) 
			throw new Exception("Not Initialized",0);
		return $this -> _timeArray["hoursamnotzero"];
	}
	public function get_Hoursam($now = false)
	{
		if ($now) $this -> _timeArray["hoursam"] = date("h");
		if ($this -> _timeArray["hoursam"] == NULL) 
			throw new Exception("Not Initialized",0);
		return $this -> _timeArray["hoursam"];
	}
	public function get_Hoursnotzero($now = false)
	{
		if ($now) $this -> _timeArray["hoursnotzero"] = date("G");
		if ($this -> _timeArray["hoursnotzero"] == NULL) 
			throw new Exception("Not Initialized",0);
		return $this -> _timeArray["hoursnotzero"];
	}
	public function get_Hourslegal($now = false)
	{
		if ($now) $this -> _timeArray["hourslegal"] = date("I");
		if ($this -> _timeArray["hourslegal"] == NULL) 
			throw new Exception("Not Initialized",0);
		return $this -> _timeArray["hourslegal"];
	}
	public function get_Hoursgreenwich($now = false)
	{
		if ($now) $this -> _timeArray["hoursgreenwich"] = date("O");
		if ($this -> _timeArray["hoursgreenwich"] == NULL) 
			throw new Exception("Not Initialized",0);
		return $this -> _timeArray["hoursgreenwich"];
	}
	public function get_Minutes($now = false)
	{
		if ($now) $this -> _timeArray["minutes"] = date("i");
		if ($this -> _timeArray["minutes"] == NULL) 
			throw new Exception("Not Initialized",0);
		return $this -> _timeArray["minutes"];
	}
	public function get_Seconds($now = false)
	{
		if ($now) $this -> _timeArray["seconds"] = date("s");
		if ($this -> _timeArray["seconds"] == NULL) 
			throw new Exception("Not Initialized",0);
		return $this -> _timeArray["seconds"];
	}
	public function get_Milliseconds()
	{
		/* di questa funzione posso avere solo i Millisecondi attuali */
		return substr($st = microtime(),0,strpos($st," "));
	}
	public function get_Time($now)
	{
		$this -> _set_timestamp($now);
		return $this -> _timestamp;
	}
	public function getTimezoneOffset()
	{
		
	}
	/**
	 * The parse() method takes a date string and returns the number 
	 * of milliseconds since midnight of January 1, 1970.
	 *
	 */
	public function parse($string)
	{
		return strtotime($string) - mktime(0,0,0,1,1,1970);
	}
	/**
	 * This is function to set AM or PM
	 *
	 * @param string $value
	 */
	public function set_APmeridian($value)
	{
		
		/* si effettua un parsing tramite regex per controllare che il $value sia corretto altrimenti viene 
		   inviata un'eccezione 
		   */
		if (eregi("^[ap]{1}[mM]{1}$",$value) === false)
			throw new Exception("$value is not valid parameter",0);
		$this -> _timeArray["APmeridinan"] = $value;
	}
	public function set_Swatchtime($value)
	{
		/* si effettua un parsing tramite regex per controllare che il $value sia corretto altrimenti viene 
		   inviata un'eccezione 
		   */
		if (eregi("^[\d]$",$value) === false)
			throw new Exception("$value is not valid parameter",0);
		$value == null ? $this -> _timeArray["Swatchtime"] = date("B") : $this -> _timeArray["Swatchtime"] = $value;
	}
	public function set_Day($value)
	{
		if (eregi("^[0-9]{2}$",$value) === false)
			throw new Exception("$value is not valid parameter",0);
		$value == null ?  $this -> _timeArray["day"] = date("d") : $this -> _timeArray["day"] = $value;
	}
	public function set_Dayweek($value)
	{
		
		if (eregi("^[\d]{1}$",$value) === false)
			throw new Exception("$value is not valid parameter",0);
		$value == null ?  $this -> _timeArray["week"] = date("w") : $this -> _timeArray["week"] = $value;
		if ($this -> _matchAutomatic == 0)
		{
			$this -> set_Dayweekword(date("D"),$this -> _get_timestamp_day($value));
			$this -> set_Dayweekwordlong(date("l"),$this -> _get_timestamp_day($value));
		}
		 
	}
	public function set_Dayweekword($value)
	{
		if (eregi("^[a-zA-Z]{3}$",$value) === false)
			throw new Exception("$value is not valid parameter",0);
		$value == null ?  $this -> _timeArray["weekword"] = date("D") : $this -> _timeArray["weekword"] = $value;
		
	}
	public function set_Dayweekworklong($value)
	{
		
		if (eregi("^[a-zA-Z]$",$value) === false)
			throw new Exception("$value is not valid parameter",0);
		$value == null ?  $this -> _timeArray["weekwordlong"] = date("l") : $this -> _timeArray["weekwordlong"] = $value;
		
	}
	public function set_Daynotzero($value)
	{
		
		if (eregi("^[0-9]{1-2}$",$value) === false)
			throw new Exception("$value is not valid parameter",0);
		$value == null ?  $this -> _timeArray["daynotzero"] = date("j") : $this -> _timeArray["daynotzero"] = $value;
		$this -> _timeArray["daynotzero"] = $value;
	}
	public function set_Dayofmonth($value)
	{		
		if (($value != null) && (eregi("^[[:digit:]]{2}$",$value) === false))
			throw new Exception("$value is not valid parameter",0);
		$value == null ?  $this -> _timeArray["dayofmonth"] = date("t") : $this -> _timeArray["dayofmonth"] = $value;
	}
	public function set_Dayofyear($value)
	{
		
		if (eregi("^[\d]{1-3}$",$value) === false)
			throw new Exception("$value is not valid parameter",0);
		$value == null ?  $this -> _timeArray["dayofyear"] = date("z") : $this -> _timeArray["dayofyear"] = $value;
		$this -> _timeArray["dayofyear"] = $value;
	}
	/**
	 * This function set a number of month
	 *
	 * @param int $value
	 */
	public function set_Month($value)
	{
		if (eregi("^[0-9]{1,2}$",$value) === false)
			throw new Exception("$value is not valid parameter",0);
		$value == null ?  $this -> _timeArray["month"] = date("m") : $this -> _timeArray["month"] = $value;
		
		/**
		 * conoscendo il valore del mese posso già impostare altri valori 
		 * imposto i giorni del mese associato
		 * imposto il mese testuale, parola lunga e corta
		 * imposto il mese senza zeri
		 */
		if ($this -> _matchAutomatic == 0)
		{ 
			$this -> _matchAutomatic = 1; //imposto come già effettuato il matchAutomatic
			$this -> set_Dayofmonth(date("t",$this -> _get_timestamp_month($value)));
			$this -> set_Monthword(date("M",$this -> _get_timestamp_month($value)));
			$this -> set_Monthwordlong(date("F",$this -> _get_timestamp_month($value)));
			$this -> set_Monthnotzero(date("n",$this -> _get_timestamp_month($value)));	
			$this -> _matchAutomatic = 0;
		}
	}
	public function set_Monthword($value)
	{
		if (eregi("^[a-zA-Z]{3}$",$value) === false)
			throw new Exception("$value is not valid parameter",0);
		$value == null ?  $this -> _timeArray["monthword"] = date("M") : $this -> _timeArray["monthword"] = $value;
		$this -> _timeArray["monthword"] = $value;
		if ($this -> _matchAutomatic == 0)
		{ 
			$this -> _matchAutomatic = 1; //imposto come già effettuato il matchAutomatic
			$this -> set_Dayofmonth(date("t",$this -> _get_timestamp_month($value)));
			$this -> set_Month(date("m",$this -> _get_timestamp_month($value)));
			$this -> set_Monthwordlong(date("F",$this -> _get_timestamp_month($value)));
			$this -> set_Monthnotzero(date("n",$this -> _get_timestamp_month($value)));	
			$this -> _matchAutomatic = 0;
		}		
	}
	public function set_Monthwordlong($value)
	{
		if (eregi("^[a-zA-Z]{1,}$",$value) === false)
			throw new Exception("$value is not valid parameter",0);
		$value == null ?  $this -> _timeArray["monthwordlong"] = date("F") : $this -> _timeArray["monthwordlong"] = $value;
		if ($this -> _matchAutomatic == 0)
		{ 
			$this -> _matchAutomatic = 1; //imposto come già effettuato il matchAutomatic
			$this -> set_Dayofmonth(date("t",$this -> _get_timestamp_month($value)));
			$this -> set_Month(date("m",$this -> _get_timestamp_month($value)));
			$this -> set_Monthword(date("M",$this -> _get_timestamp_month($value)));
			$this -> set_Monthnotzero(date("n",$this -> _get_timestamp_month($value)));	
			$this -> _matchAutomatic = 0;
		}		
		
	}
	public function set_Monthnotzero($value)
	{
		if (eregi("^[0-9]{1,2}$",$value) === false)
			throw new Exception("$value is not valid parameter",0);
		$value == null ?  $this -> _timeArray["monthnotzero"] = date("n") : $this -> _timeArray["monthnotzero"] = $value;
		if ($this -> _matchAutomatic == 0)
		{ 
			$this -> _matchAutomatic = 1; //imposto come già effettuato il matchAutomatic
			$this -> set_Dayofmonth(date("t",$this -> _get_timestamp_month($value)));
			$this -> set_Month(date("m",$this -> _get_timestamp_month($value)));
			$this -> set_Monthword(date("M",$this -> _get_timestamp_month($value)));
			$this -> set_Monthnotzero(date("n",$this -> _get_timestamp_month($value)));	
			$this -> _matchAutomatic = 0;
		}		
	}
	public function set_Year($value)
	{
		if (eregi("^[0-9]{2}$",$value) === false)
			throw new Exception("$value is not valid parameter",0);
		$value == null ?  $this -> _timeArray["year"] = date("y") : $this -> _timeArray["year"] = $value;
		if ($this -> _matchAutomatic == 0)
		{
			$this -> _matchAutomatic = 1;
			$this -> set_FullYear(date("Y",$this -> _get_timestamp_year($value)));
			$this -> set_YearBisestile(date("L"),$this -> _get_timestamp_year($value));
			$this -> _matchAutomatic = 0;
		}
	}
	public function set_FullYear($value)
	{
		if (eregi("^[0-9]{4}$",$value) === false)
			throw new Exception("$value is not valid parameter",0);
		$value == null ?  $this -> _timeArray["fullyear"] = date("Y") : $this -> _timeArray["fullyear"] = $value;
		if ($this -> _matchAutomatic == 0)
		{
			$this -> _matchAutomatic = 1;
			$this -> set_Year(date("y",$this -> _get_timestamp_year($value)));
			$this -> set_YearBisestile(date("L"),$this -> _get_timestamp_year($value));
			$this -> _matchAutomatic = 0;
		}
	}
	public function set_YearBisestile($value)
	{
		if (eregi("^[0-1]{1}$",$value) === false)
			throw new Exception("$value is not valid parameter",0);
		$value == null ?  $this -> _timeArray["yearbisestile"] = date("L") : $this -> _timeArray["yearbisestile"] = $value;
	}
	
	public function set_Hours($value)
	{
		if (eregi("^[0-9]{2}$",$value) === false)
			throw new Exception("$value is not valid parameter",0);
		$value == null ?  $this -> _timeArray["hours"] = date("H") : $this -> _timeArray["hours"] = $value;
	}
	public function set_Hoursamnotzero($value)
	{
		if (eregi("^[0-9]{2}$",$value) === false)
			throw new Exception("$value is not valid parameter",0);
		$value == null ?  $this -> _timeArray["hoursnotzero"] = date("G") : $this -> _timeArray["hoursnotzero"] = $value;
	}
	public function set_Hoursam($value)
	{
		if (eregi("^[0-9]{2}$",$value) === false)
			throw new Exception("$value is not valid parameter",0);
		$value == null ?  $this -> _timeArray["hoursam"] = date("h") : $this -> _timeArray["hoursam"] = $value;
	}
	public function set_Hourslegal($value)
	{
		if (eregi("^[0-1]{1}$",$value) === false)
			throw new Exception("$value is not valid parameter",0);
		$value == null ?  $this -> _timeArray["hourslegal"] = date("I") : $this -> _timeArray["hourslegal"] = $value;
	}
	public function set_HoursGreenwich($value)
	{
		if (eregi("^[\+\-]{1}[0-9]{4}$",$value) === false)
			throw new Exception("$value is not valid parameter",0);
		$value == null ?  $this -> _timeArray["hoursgreenwich"] = date("O") : $this -> _timeArray["hoursgreenwich"] = $value;
	}
	public function set_Hoursnotzero($value)
	{
		if (eregi("^[0-9]{2}$",$value) === false)
			throw new Exception("$value is not valid parameter",0);
		$value == null ?  $this -> _timeArray["hoursamnotzero"] = date("g") : $this -> _timeArray["hoursamnotzero"] = $value;
	}
	public function set_Minutes($value)
	{
		$this -> _timeArray["minutes"] = $value;	
	}
	public function set_Seconds($value)
	{
		$this -> _timeArray["seconds"] = $value;
	}
	public function set_Milliseconds()
	{
		//imposta i millisecondi attuali 
		$this -> _timeArray["milliseconds"] = substr($st = microtime(),0,strpos($st," "));
	}
	public function set_Time()
	{
		
	}
	public function toSource()
	{
		return $this -> _timeArray;
	}
	public function toString()
	{
		
	}
	public function toGMTString()
	{
		
	}
	public function toUTCString()
	{
		
	}
	public function toLocaleString()
	{
		
	}
	public function UTC()
	{
		
	}
	public function valueOf()
	{
		
	}
	
	
}

/*
	
}*/









?>