<?php

	//verificar si el id existe
	function verificarIDExiste($id){
		$sql = "select * from atl_contactos where id = '$id' and estado=1";
		$rs = Ejecutar::sentencia($sql);
		
		$validar = true;
		if($id!=0)
		{
			if(mysqli_num_rows($rs)==0)
			{
				$validar = false;
			}
		}
		
		return $validar;
	}
	
	//verificar si correo existe
	function validarEmailExiste($email){
		$sql = "select * from atl_contactos where email = '$email' and estado=1";
		$rs = Ejecutar::sentencia($sql);
		
		$validar = false;
		
			if(mysqli_num_rows($rs)>0)
			{
				$validar = true;
			}
		
		return $validar;
	}