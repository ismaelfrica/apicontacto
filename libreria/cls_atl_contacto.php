<?php
class atl_contacto
{
	private $id; 	//3
	private $nombre; 	//253
	private $apellido; 	//10
	private $email; 	//10
	private $estado; 	//3
	
	function __construct($id=0)
	{
		if($id > 0)
		{
			$this->id = $id;
			$this->cargar();
		}
	}

	public function __set($key, $value){
		$this->$key = $value;
	}

	public function __get($key){
		return $this->$key;
	}
	
	function guardar()
	{
		$rs = true;
		global $con;
		if($this->id > 0)
		{
			$sql = "update atl_contactos set nombre = '$this->nombre',apellido = '$this->apellido',email = '$this->email',estado = '$this->estado' 
			where id = '$this->id'";
			Ejecutar::sentencia($sql);
		}
		else
		{
			$sql = "insert into atl_contactos 
			(nombre,apellido,email,estado) 
			values
			('$this->nombre','$this->apellido','$this->email',1)
			";
			Ejecutar::sentencia($sql);
			$this->id = mysqli_insert_id(Ejecutar::getCon());
			echo mysqli_error(Ejecutar::getCon());
		}
		
		return $rs;
	}
	
	function cargar()
	{
		$sql = "select * from atl_contactos where id = '$this->id'";
		$rs = Ejecutar::sentencia($sql);
		$row = mysqli_fetch_array($rs);
		$this->id = $row['id'];
		$this->nombre = $row['nombre'];
		$this->apellido = $row['apellido'];
		$this->email = $row['email'];
		$this->estado = $row['estado'];
	
	}
	
	function listarContactos()
	{
		$sql = "select * from atl_contactos where estado=1";
		$rs = Ejecutar::sentencia($sql);
		$row = mysqli_fetch_all($rs, true);
		return json_encode($row);
	}

}
?>