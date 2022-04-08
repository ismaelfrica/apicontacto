<?php

class conexion
{
	function __destruct()
	{
		Ejecutar::cerrar();
		
	}
}

class Ejecutar
{
	public static $instancia = null;
	private static $clase = null;
	static function cerrar()
	{
		mysqli_close(self::$instancia);
	}
	
	static function conectar()
	{
		self::$instancia = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die("No se pudo conectar a la base de datos");
		
		/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
		self::$clase = new conexion();
	}
	
	static function getCon()
	{
		self::checkConexion();
		return self::$instancia;
	}
	
	static function checkConexion()
	{
		
		if(self::$instancia==null)
		{
			
			self::conectar();
		}
	}
	
	static function sentencia($sql, $parametros = array())
	{
		self::checkConexion();
		
		$resultado = mysqli_query(self::$instancia, $sql);
		return $resultado;
	}
	
	static function execute($sql, $parametros = array())
	{
		
		self::checkConexion();
		$stmt = mysqli_prepare(self::$instancia, $sql);
			
		$tipos = "";
		
		foreach($parametros as $param)
		{
			$tipos .= substr(strtolower(gettype($param)), 0, 1);
		}
		mysqli_stmt_bind_param($stmt, $tipos, $parametros[0],$parametros[1]);
		
		echo mysqli_stmt_execute($stmt);
		
		
	}
	
}