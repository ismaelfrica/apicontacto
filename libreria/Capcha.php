<?php

class Capcha{
	
	private $cad = "";
	private $str = "ABCDEFGHIJKLMNPQRSTUVWXYZadabcdefghijklmnopqrstuvwxyz123456789";
	
	public function __construct(){
		// Solo de 8 caracteres
		for($i=0; $i < 15; $i++) { 
			$ran = rand(0, 61);
			$this->cad .= $this->str[$ran];
		}
	}
	
	public function __toString(){
		
		return $this->cad;
	}
}