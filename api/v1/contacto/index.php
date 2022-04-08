<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: POST");
$method = $_SERVER['REQUEST_METHOD'];
if($method == "OPTIONS") {
	die();
}

include("../../../libreria/engine.php");

//recibo los parametros 
$data = json_decode(file_get_contents('php://input'), true);

//instancio la clase
$contacto = new atl_contacto();

//mensaje para devolver cuando existe algun error
$mensaje=array();
//valido si recibo parametros
if(count($data)==0)
{
	//muestro listado
	$contacto->listarContactos();
}else{
	
	//declaramos las variables asignada en el for
	$id="";
	$nombre="";
	$apellido="";
	$email="";
	$estado="";
	
	//boolean para verificar que todo este bien
	
	$todoBien = true;
	
	for ($i = 0;$i < count($data);$i++) {
		
		$mensaje = array();

		//Dectactamos si viene vacio o no
		$validarID = isset($data[$i]['id']);
		$validarNombre = isset($data[$i]['nombre']);
		$validarApellido = isset($data[$i]['apellido']);
		$validarEmail = isset($data[$i]['email']);
		$validarEstado = isset($data[$i]['estado']);
		
		//Hacemos la validacion y devolvemos el mensaje si hay algo mal
		if($validarNombre=="")
		{
			$mensaje[] = "Error al procesar, el campo nombre es obligatorio";
			$todoBien = false;
		}else{
			$nombre =  $data[$i]['nombre'];
		}
		
		if($validarApellido=="")
		{
			$mensaje[] = "Error al procesar, el campo apellido es obligatorio";
			$todoBien = false;
		}else{
			$apellido =  $data[$i]['apellido'];
		}
		
		if($validarEmail=="")
		{
			$mensaje[] = "Error al procesar, el campo email es obligatorio";
			$todoBien = false;
		}else{
			$email =  $data[$i]['email'];
		}
		
		if($validarEstado=="")
		{
			$mensaje[] = "Error al procesar, el campo estado es obligatorio";
			$todoBien = false;
		}else{
			$estado =  $data[$i]['estado'];
		}
		
		if($todoBien==false)
		{
			echo json_encode($mensaje);
			die;
		}else{
			$contacto->id=$data[$i]['id'];
			$contacto->nombre = $nombre;
			$contacto->apellido = $apellido;
			$contacto->email = $email;
			$contacto->estado = $estado;
			
			if($contacto->estado!=0)
			{
				//Validar si Email existe
				if(validarEmailExiste($contacto->email)===false){
					//validamos si el id existe
					if(verificarIDExiste($contacto->id)===true)
					{	
						if($contacto->id==0)
						{
							$mensaje[] = "Registro guardado";
						}else{
							$mensaje[] = "Registro actualizado";
						}
					}else{
						$mensaje[] = "Error al procesar, el email ". $contacto->email." ya existe";
					}
					
				}else{
					$mensaje[] = "Error al procesar, el ID# ".$contacto->id." no existe, favor revisar";
				}
			}else{
				
				$contacto->cargar();
				if($contacto->estado==0)
				{
					$mensaje[] = "El ID# ".$contacto->id." no existe";
				}else{
					$mensaje[] = "El ID# ".$contacto->id." fue eliminado";
				}
			}
			$contacto->guardar();
			echo json_encode($mensaje);
			//Limpiamos los mensjes
			unset($mensaje); 
		}
		
	
	}
}


