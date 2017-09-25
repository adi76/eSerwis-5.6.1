<?php
class timer
{
	/*
		timer class, for easier calculation of the time required to execute some code
		Copyright (C) Joshua Townsend and licensed under the (modified) BSD License
		Version 1.0

		<http://www.gamingg.net>
	*/
	var $started;
	var $stopped;
	var $time;

	function timer($start = true)
	{
		if($start)
		{
			$this->start();
		}
	}

	function start()
	{
		$this->started = $this->now();
		$this->stopped = null;
		$this->time = null;
	}

	function stop()
	{
		$this->stopped = $this->now();
		$this->time = ($this->stopped - $this->started);

		return $this->time;
	}

	function get_time()
	{
		return $this->time;
	}

	function now()
	{
	   list($usec, $sec) = explode(" ", microtime());
	   return ((float)$usec + (float)$sec);
	}
}
?>