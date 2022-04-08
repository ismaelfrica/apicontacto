<?php

	//verificar si el id existe
	function verificarIDExiste($id){
		$sql = "select * from atl_contactos where id = '$id' and estado=1";
		$rs = Ejecutar::sentencia($sql);
		
		$validar = false;
		if($id!=0)
		{
			if(mysqli_num_rows($rs)==0)
			{
				$validar = true;
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
	
	function validarRegistro($data,$origen)
	{
		//instancio la clase
		$contacto = new atl_contacto();
		//Booleando para saber que todo esta bien para procedera ejecutar una accion
		$todoBien = true;

		$mensaje = array();
		//Validamos que si es eliminar solo necesitamos el id, los demas campos no son necesarios
		if($origen=="eliminar")
		{
			$validarId = isset($data['id']);
			
			if($validarId!="")
			{
				$contacto->id=$data['id'];
			}else{
				$todoBien = false;
				$mensaje[] = "El ID es necesario para poder ejecutar esta accion";
			}
			//Estos valores no se van a cambiar es solo par que pase la validacion que no puede estar en blanco
			$contacto->id = $data['id'];
			$contacto->cargar();
			$nombre = $contacto->nombre;
			$apellido =$contacto->apellido;
			$email = $contacto->email;
			$estado = 0;
		}else{

			//Dectactamos si viene vacio o no
			$validarId = isset($data['id']) ? $data['id'] : 0;
			//Hacemos la validacion y devolvemos el mensaje si hay algo mal
			if(!isset($data['nombre']) || empty($data['nombre']))
			{
				$mensaje[] = "Error al procesar, el campo nombre es obligatorio";
				$todoBien = false;
			}else{
				$nombre =  $data['nombre'];
			}
			
			if(!isset($data['apellido']) || empty($data['apellido']))
			{
				$mensaje[] = "Error al procesar, el campo apellido es obligatorio";
				$todoBien = false;
			}else{
				$apellido =  $data['apellido'];
			}
			
			if(!isset($data['email']) || empty($data['email']))
			{
				$mensaje[] = "Error al procesar, el campo email es obligatorio";
				$todoBien = false;
			}else{
				$email =  $data['email'];
			}
		}
		
		if($todoBien==false){
			echo json_encode($mensaje);
			die;
		}else{
			if($validarId!="")
			{
				$contacto->id=$data['id'];
			}else{
				$contacto->id=0;
			}
			
			if($nombre=='')
			{
				$mensaje[] = "Error al procesar, el campo nombre es obligatorio";
				$todoBien = false;
			}else{
				$contacto->nombre = $nombre;	
			}
			
			if($apellido=='')
			{
				$mensaje[] = "Error al procesar, el campo apellido es obligatorio";
				$todoBien = false;
			}else{
				$contacto->apellido = $apellido;	
			}
			
			if($email=='')
			{
				$mensaje[] = "Error al procesar, el campo email es obligatorio";
				$todoBien = false;
			}else{
				$contacto->email = $email;	
			}
			
		//Inicio del Switch para empezar accionar por tipo de peticion
		switch ($origen){
			case "agregar":
				//Validar si Email existe
				if(validarEmailExiste($contacto->email)===true){
					$mensaje[] = "Error al procesar, el email ". $contacto->email." ya existe";
					$todoBien = false;
				}else{
					$contacto->nombre = $data['nombre'];
					$contacto->apellido = $data['apellido'];
					$contacto->email = $data['email'];
					$contacto->estado = 1;
					$contacto->guardar();
					$mensaje[] = "Datos insertados";
					
				}
			break;
			
			case "editar":
				//Validar si ID existe
				if(verificarIDExiste($contacto->id)===true){
					$mensaje[] = "Error al procesar, el ID ". $contacto->id." no existe";
					$todoBien = false;
				}else{
					if($todoBien===true){
						$contacto->id = $contacto->id;
						$contacto->nombre = $data['nombre'];
						$contacto->apellido = $data['apellido'];
						$contacto->email = $data['email'];
						$contacto->estado = 1;
						$contacto->guardar();
						$mensaje[] = "Datos actualizados";
					}
				}
			break;
			
			case "eliminar":
				//Validar si ID existe
				if(verificarIDExiste($contacto->id)===true){
					$mensaje[] = "Error al procesar, el ID ". $contacto->id." no existe";
					$todoBien = false;
				}else{
					if($todoBien===true){
						$contacto->id =  $contacto->id;
						$contacto->nombre =  $contacto->nombre;
						$contacto->apellido =  $contacto->apellido;
						$contacto->email =  $contacto->email;
						$contacto->estado = 0;
						$contacto->guardar();
						$mensaje[] = "Dato eliminado";
					}
				}
				break;
			}
		}
		echo json_encode($mensaje);
		//Limpiamos los mensajes
		unset($mensaje); 
	}