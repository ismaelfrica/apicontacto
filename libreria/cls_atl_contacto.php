<?php
		class atl_contacto
		{
			var $id; 	//3
			var $nombre; 	//253
			var $apellido; 	//10
			var $email; 	//10
			var $stad; 	//3
			
		
			function __construct($id=0)
			{
				if($id > 0)
				{
					$this->id = $id;
					$this->cargar();
				}
			}
			
			function guardar()
			{
				$rs = true;
				global $con;
				$this->validarEntrada();
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
			
			function validarEntrada()
			{
				$this->id = $this->id ;
				$this->nombre = $this->nombre ;
				$this->apellido = $this->apellido ;
				$this->email = $this->email ;
				$this->stad = $this->stad ;
			
			}
			
			function listarContactos()
			{
				$sql = "select * from atl_contactos where estado=1";
				$rs = Ejecutar::sentencia($sql);
				
				$contador=0;
				$coma="";
				
				$saberCantidad = mysqli_num_rows($rs);
				
				echo  "[";
				while($row = mysqli_fetch_assoc($rs))
				{
					$contador++;
					if($contador!=$saberCantidad)
					{
						
						$coma = ",";
					}else{
						$coma = "";
					}
					echo json_encode($row).$coma;
					
					
				}
				echo  "]";
			}
		
		}
?>