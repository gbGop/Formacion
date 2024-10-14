<?php
function Casino_tbl_inscripcion_casinos_rut_casino_conpermiso($rut){
  $connexion = new DatabasePDO();
  $sql = "SELECT id FROM tbl_usuario WHERE rut=:rut AND regional='13'";
  $connexion->query($sql);
  $connexion->bind(':rut', $rut);
  $cod = $connexion->resultset();
  return ($cod[0]->id);
}
function Casino_tbl_inscripcion_casinos_ya_inscrito($rut){
  $connexion = new DatabasePDO();
  $hoy=date("Y-m-d");
  $sql = "SELECT h.id FROM tbl_mc_inscripcion_casinos h WHERE h.rut=:rut AND h.fecha=:fecha";
  $connexion->query($sql);
  $connexion->bind(':rut', $rut);
  $connexion->bind(':fecha', $hoy);
  $cod = $connexion->resultset();
  return ($cod[0]->id);
}

function Casino_Insert_tbl_mc_inscripcion_casinos($rut, $fecha, $casino, $turno)
{
  $connexion = new DatabasePDO();
  $hoy   = date('Y-m-d');
  $fecha = date("Y-m-d");
  $hora  = date("H:i:s");
  $sql   = "INSERT INTO tbl_mc_inscripcion_casinos (rut, fecha, casino, turno) VALUES (:rut, :fecha, :casino, :turno)";
  $connexion->query($sql);
  $connexion->bind(':rut', $rut);
  $connexion->bind(':fecha', $fecha);
  $connexion->bind(':casino', $casino);
  $connexion->bind(':turno', $turno);
  $connexion->execute();
}

function Casino_tbl_inscripcion_casinos_read_POR_RUT($fecha, $rut){
  $connexion = new DatabasePDO();
  $sql = "SELECT * FROM tbl_mc_inscripcion_casinos WHERE fecha=:fecha AND rut=:rut";
  $connexion->query($sql);
  $connexion->bind(':fecha', $fecha);
  $connexion->bind(':rut', $rut);
  $cod = $connexion->resultset();
  return ($cod);
}

function tbl_inscripcion_casinos_read_full() {
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_mc_inscripcion_casinos";
    $connexion->query($sql);

  $cod = $connexion->resultset();
  return $cod; 
}

function Casino_tbl_inscripcion_casinos_read_inscripciones($fecha, $casino, $turno) {
    $connexion = new DatabasePDO();
    $sql = "SELECT COUNT(id) AS inscripciones FROM tbl_mc_inscripcion_casinos WHERE fecha=:fecha AND casino=:casino AND turno=:turno";
    $connexion->query($sql);
    $connexion->bind(":fecha", $fecha);
    $connexion->bind(":casino", $casino);
    $connexion->bind(":turno", $turno);
    $cod = $connexion->resultset();
  return ($cod[0]->inscripciones);
}

function ContigenciaBuscaAntigenoFecha($rut, $id_empresa) {
  $connexion = new DatabasePDO();
  $dias_semana = Week();
  
  $datos_ultima_dosis_semana = UltimaDosisenLaSemana($rut, $dias_semana[1], $dias_semana[0]);
  
  if(count($datos_ultima_dosis_semana) > 0) {
      $antigeno_actualizacion = "<br><strong>Test Ant&iacute;geno</strong><br><div class='alert alert-success'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-calendar2-check' viewBox='0 0 16 16'>
    <path d='M10.854 8.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7.5 10.793l2.646-2.647a.5.5 0 0 1 .708 0z'/>
    <path d='M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM2 2a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V3a1 1 0 0 0-1-1H2z'/>
    <path d='M2.5 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H3a.5.5 0 0 1-.5-.5V4z'/>
  </svg> Ultimo Registro de la semana: ".$datos_ultima_dosis_semana[0]->fecha.". Resultado ".$datos_ultima_dosis_semana[0]->resultado_anf."</div>";
      } else {
          $antigeno_actualizacion = "<br><strong>Test Ant&iacute;geno</strong><br><div class='alert alert-warning'>Sin Registro Ultima semana";
      }
  return $antigeno_actualizacion;
  }
function UltimaDosisenLaSemana($rut, $fecha_inicio_semana, $fecha_hoy){
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_contingencia_2021_antigeno WHERE rut = :rut AND fecha BETWEEN :fecha_inicio_semana AND :fecha_hoy ORDER BY id DESC LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':fecha_inicio_semana', $fecha_inicio_semana);
    $connexion->bind(':fecha_hoy', $fecha_hoy);
    $cod = $connexion->resultset();
    return ($cod);
    }
    
function DatoUbicacionUnico($rut){
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_contingencia_ubicacion_colaboradores WHERE rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return ($cod);
    }
    
function DatoRegimenUnico($rut){
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_contingencia_2021 WHERE num_rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return ($cod);
    }
    
function Contingencia_2021_usuario_retorno($rut){
    $connexion = new DatabasePDO();
    $sql = "SELECT COUNT(id) AS cuenta FROM tbl_contingencia_colaborador_retornado_2021 WHERE rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return ($cod[0]->cuenta);
    }
function Contingencia_2023_modalidadtrabajo($rut){
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_contingencia_colaborador_retornado_2021 WHERE rut = :rut limit 1";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
    }	

    function Contingencia_2021_Mi_Dosis($rut) {
      $connexion = new DatabasePDO();
      $sql = "SELECT COUNT(id) AS cuenta FROM tbl_contingencia_2021_dosis WHERE rut = :rut";
      $connexion->query($sql);
      $connexion->bind(':rut', $rut);
      $result = $connexion->resultset();
      if ($result[0]->cuenta > 0) {
          $data = "NO";
      } else {
          $data = "SI";
      }
      return $data;
  }
  
function Contigencia_2021_Insert_Dosis($rut, $dosis, $fecha_vacunacion, $nombre_vacuna){

		global $c_host, $c_user, $c_pass, $c_db;
    $database = new database($c_host, $c_user, $c_pass);
    $database->setDb($c_db);	
    
    if($rut==""){
    	echo "<script>alert('Error en la peticion, intentalo nuevamente'); location.href='?sw=mi_equipo_consolidado_contingencia_colaborador_21';</script>";exit();
    }
    
		$sql="		select * from tbl_contingencia_2021_dosis where rut='".($rut)."'
		  	";
	    $fecha_hoy 				= date("Y-m-d");	
		
		//echo $sql;
		
		//echo "<br>fecha_vacunacion $fecha_vacunacion";
	if($fecha_vacunacion>"2021-01-01"){
		//echo "OK";
	} else {
		//echo "fecha no OK";
		    		    	echo "<script>alert('Fecha inconsistente. Vuelve a intentar.');
    		    	location.href='?sw=mi_equipo_consolidado_contingencia_colaborador_21';</script>";
    		    	exit();
	}
		//exit();
    $database->setquery($sql);
    $database->query();
    $cod = $database->listObjects();
    if($dosis=="1era Dosis"){
    	if($nombre_vacuna==""){
    		    	echo "<script>alert('Debes seleccionar el nombre de la vacuna.');
    		    	location.href='?sw=mi_equipo_consolidado_contingencia_colaborador_21';</script>";
    		    	exit();

    	}
    	$fecha_vacunacion1=$fecha_vacunacion;
    	$queryfe="fecha_vacunacion='".$fecha_vacunacion1."',";
    } elseif($dosis=="2da Dosis"){
    	if($nombre_vacuna==""){
    		    	echo "<script>alert('Debes seleccionar el nombre de la vacuna.');
    		    	location.href='?sw=mi_equipo_consolidado_contingencia_colaborador_21';</script>";
    		    	exit();

    	}    	
    	$fecha_vacunacion2=$fecha_vacunacion;
    	$queryfe="fecha_vacunacion2='".$fecha_vacunacion2."',";
    }
	elseif($dosis=="3ra Dosis"){
    	if($nombre_vacuna==""){
    		    	echo "<script>alert('Debes seleccionar el nombre de la vacuna.');
    		    	location.href='?sw=mi_equipo_consolidado_contingencia_colaborador_21';</script>";
    		    	exit();

    	}    	
    	$fecha_vacunacion3=$fecha_vacunacion;
    	$queryfe="fecha_vacunacion3='".$fecha_vacunacion3."',";
    }
	elseif($dosis=="4ta Dosis"){
    	if($nombre_vacuna==""){
    		    	echo "<script>alert('Debes seleccionar el nombre de la vacuna.');
    		    	location.href='?sw=mi_equipo_consolidado_contingencia_colaborador_21';</script>";
    		    	exit();

    	}    	
    	$fecha_vacunacion4=$fecha_vacunacion;
    	$queryfe="fecha_vacunacion4='".$fecha_vacunacion4."',";
    }           
     else {
    	$fecha_vacunacion3=$fecha_vacunacion;

    	$queryfe="fecha_probable_vacunacion='".$fecha_vacunacion."',";
    }
    if($cod["0"]->id>0){
    

    
    if($dosis=="3ra Dosis")
    
    {
		 		$sql = "
				update tbl_contingencia_2021_dosis set 

				dosis='".$dosis."',
				".$queryfe."
				
				nombre_vacuna3='".$nombre_vacuna."',
				fecha='".$fecha_hoy."'

		    where  rut='".$rut."'";         	
    	
    }
    elseif($dosis=="4ta Dosis")
    
    {
		 		$sql = "
				update tbl_contingencia_2021_dosis set 

				dosis='".$dosis."',
				".$queryfe."
				
				nombre_vacuna4='".$nombre_vacuna."',
				fecha='".$fecha_hoy."'

		    where  rut='".$rut."'";         	
    	
    }      
     else {
		 		$sql = "
				update tbl_contingencia_2021_dosis set 

				dosis='".$dosis."',
				".$queryfe."
				
				nombre_vacuna='".$nombre_vacuna."',
				fecha='".$fecha_hoy."'

		    where  rut='".$rut."'";       	
    }
    

        $database->setquery($sql);
    $database->query();
    } else {
				 $sql = "
				    INSERT INTO tbl_contingencia_2021_dosis
				    (rut,fecha,dosis,fecha_vacunacion,fecha_vacunacion2,fecha_probable_vacunacion,nombre_vacuna)
				    VALUES 
				    (
				'".$rut."', 
				'".$fecha_hoy."', '".utf8_decode($dosis)."', '".$fecha_vacunacion1."', '".$fecha_vacunacion2."','".$fecha_vacunacion3."',
				'".$nombre_vacuna."'
				    )";
    $database->setquery($sql);
    $database->query();				    
    }
		    		    	echo "<script>alert('Registro de Proceso de Vacunaci\u00F3n actualizado.');
    		    	location.href='?sw=mi_equipo_consolidado_contingencia_colaborador_21&col=".Encodear3($rut)."';</script>";
    		    	exit();}

function Contingencia_2021_Dosis_Download() {
                $connexion = new DatabasePDO();
                $sql = "SELECT * FROM tbl_contingencia_2021_dosis WHERE rut <> ''";
                $connexion->query($sql);
                $cod = $connexion->resultset();
                return $cod;	
            }
            
function Contingencia_2021_Casino_Download($hoy) {
                $connexion = new DatabasePDO();
                $sql = "SELECT h.*, (SELECT nombre_completo FROM tbl_usuario WHERE rut = h.rut) as nombre_completo FROM tbl_mc_inscripcion_casinos h WHERE h.rut <> '' AND h.fecha = :hoy";
                $connexion->query($sql);
                $connexion->bind(':hoy', $hoy);
                $cod = $connexion->resultset();
                return $cod;
            }
            
function Contigencia_2021_Vista_Dosis($rut) {
                $connexion = new DatabasePDO();
                $sql = "SELECT * FROM tbl_contingencia_2021_dosis WHERE rut = :rut";
                $connexion->query($sql);
                $connexion->bind(':rut', $rut);
                $cod = $connexion->resultset();
                return $cod;
            }            

function Contingencia_2021_Futuro_data($rut,$hoy,$id_empresa){
	
              $connexion = new DatabasePDO();
          
            $sql="SELECT * FROM tbl_contigencia_2021_futuro WHERE rut=:rut AND fecha_inicio>:hoy AND fecha_termino>:hoy";
            $connexion->query($sql);
            $connexion->bind(':rut', $rut);
            $connexion->bind(':hoy', $hoy);
            $cod = $connexion->resultset();
            return ($cod);	
          }
          
function UpdateJefeContingencia2021($rut_col, $status, $fecha, $user )
          {
            $connexion = new DatabasePDO();
          
            $porciones = explode("-", $status);
              $regimen=utf8_decode($porciones[0]);
              $motivo=utf8_decode($porciones[1]);
              
              $fecha_hoy = date("Y-m-d");
              
            $sql = "UPDATE tbl_contingencia_2021_dia SET 
              regimen=:regimen,
              motivo=:motivo,
              rut_actualizador=:user,
              fecha_actualizacion=:fecha_hoy,
              edit='1'
              WHERE rut=:rut_col AND fecha=:fecha";
            
            $connexion->query($sql);
            $connexion->bind(':regimen', $regimen);
            $connexion->bind(':motivo', $motivo);
            $connexion->bind(':user', $user);
            $connexion->bind(':fecha_hoy', $fecha_hoy);
            $connexion->bind(':rut_col', $rut_col);
            $connexion->bind(':fecha', $fecha);
            $connexion->execute();	
          } 

function FechaDDMMYYYY($fecha){
	$porciones = explode("-", $fecha);
	$fecha_reformate=$porciones[2]."-".$porciones[1]."-".$porciones[0];
	return $fecha_reformate;
}

function MiPermiso($rut, $ide){
	$connexion = new DatabasePDO();

	$sql = "SELECT * FROM tbl_permiso_temporal WHERE rut = :rut AND id = :ide";
	$connexion->query($sql);
	$connexion->bind(':rut', $rut);
	$connexion->bind(':ide', $ide);
	$cod = $connexion->resultset();
	return $cod;
}

function MiPoliza($rut, $ide){
	$connexion = new DatabasePDO();

	$sql = "SELECT * FROM tbl_codigo_poliza WHERE rut = :rut AND id = :ide";
	$connexion->query($sql);
	$connexion->bind(':rut', $rut);
	$connexion->bind(':ide', $ide);
	$cod = $connexion->resultset();
	return $cod;
}

function Contingencia_SoyDivisionPersonas($rut){
	$connexion = new DatabasePDO();

	$sql = "SELECT id FROM tbl_usuario WHERE rut = :rut AND division = 'Division Personas y Organizacion'";
	$connexion->query($sql);
	$connexion->bind(':rut', $rut);
	$cod = $connexion->resultset();
	return $cod[0]->id;
}

function MisPermimosUnico($rut){
	$connexion = new DatabasePDO();

	$sql = "SELECT * FROM tbl_permiso_temporal WHERE rut = :rut";
	$connexion->query($sql);
	$connexion->bind(':rut', $rut);
	$cod = $connexion->resultset();
	return $cod;
}

function MisPolizasUnico($rut){
  $connexion = new DatabasePDO();
  $sql = "SELECT * FROM tbl_codigo_poliza WHERE rut=:rut";
  $connexion->query($sql);
  $connexion->bind(':rut', $rut);
  $cod = $connexion->resultset();
  return ($cod);
}

function Contingencia_2021_reset($id){
  $connexion = new DatabasePDO();
  $sql = "UPDATE tbl_contingencia_2021 SET 
      rut_actualizador='',
      fecha_actualizacion='',
      hora_actualizacion='',
      regimen=regimen_historico,
      motivo=motivo_historico,
      fecha_inicio=fecha_inicio_historico,
      fecha_termino=fecha_termino_historico,
      regimen_historico='',
      motivo_historico='',
      regimen_futuro='',
      motivo_futuro='',
      fecha_inicio_futuro='',
      fecha_termino_futuro='',
      fecha_inicio_historico='',
      fecha_termino_historico=''
      WHERE id=:id";
  $connexion->query($sql);
  $connexion->bind(':id', $id);
  $connexion->execute();   
  //echo "<br>id $id<br>";
  //exit();
}

function Contingencia_2021_Update_Futuro($rut, $rut_enc_post, $status_rut, $detalle_rut, $fi_rut, $ft_rut){

  $connexion = new DatabasePDO();

  $sql_1 = "SELECT * FROM tbl_contingencia_2021 WHERE num_rut = :rut_enc_post";
  $connexion->query($sql_1);
  $connexion->bind(':rut_enc_post', $rut_enc_post);
  $cod_1 = $connexion->resultset();

  $fecha_actualizacion = date("Y-m-d");
  $hora_actualizacion = date("H:i:s");

  $rut_actualizador = $_SESSION["user_"];

  $porciones = explode("-", $status_rut);
  $regimen = $porciones[0];
  $motivo = $porciones[1];
  
  $fi_rut_menosuno = date('Y-m-d', strtotime($fi_rut. ' -1 day'));

  if ($cod_1[0]->regimen_historico <> "") {
      //no hago nada porque tiene la info oficial
      $update_historico="";
  } else {
      $update_historico=", regimen_historico='".$cod_1[0]->regimen."', motivo_historico='".$cod_1[0]->motivo."', fecha_inicio_historico='".$cod_1[0]->fecha_inicio."', fecha_termino_historico='".$cod_1[0]->fecha_termino."'";
  }

  $sql = "UPDATE tbl_contingencia_2021 SET rut_actualizador=:rut_actualizador, fecha_actualizacion=:fecha_actualizacion, hora_actualizacion=:hora_actualizacion, regimen_futuro=:regimen, motivo_futuro=:motivo, fecha_termino=:fi_rut_menosuno, fecha_inicio_futuro=:fi_rut, fecha_termino_futuro=:ft_rut".$update_historico." WHERE id=:id";
  
  $connexion->query($sql);
  $connexion->bind(':rut_actualizador', $rut_actualizador);
  $connexion->bind(':fecha_actualizacion', $fecha_actualizacion);
  $connexion->bind(':hora_actualizacion', $hora_actualizacion);
  $connexion->bind(':regimen', $regimen);
  $connexion->bind(':motivo', $motivo);
  $connexion->bind(':fi_rut_menosuno', $fi_rut_menosuno);
  $connexion->bind(':fi_rut', $fi_rut);
  $connexion->bind(':ft_rut', $ft_rut);
  $connexion->bind(':id', $cod_1[0]->id);
  $cod = $connexion->resultset();
}  


function Contingencia_2021_NuevoRegimen_DadoActual_data($regimen_actual, $motivo_actual) {
  $connexion = new DatabasePDO();
  $sql = "SELECT * FROM tbl_contingencia_2021_option WHERE regimen_actual=:regimen_actual AND motivo_actual=:motivo_actual ORDER BY regimen_nuevo, motivo_nuevo";
  $connexion->query($sql);
  $connexion->bind(':regimen_actual', $regimen_actual);
  $connexion->bind(':motivo_actual', $motivo_actual);
  $cod = $connexion->resultset();
  return $cod;   
}

function Contingencia_Inserta_Linea21($rut, $rut_col, $status, $detalle, $fi_rut, $ft_rut, $fecha, $id_empresa, $nombre, $cargo, $division, $rut_jefe, $nombre_jefe, $futuro_hoy) {
  $connexion = new DatabasePDO();
  $fecha_hoy = date("Y-m-d");
  $hora_hoy = date("H:i:s");
  $hora_hoy2 = date("H:i:s");

  $porciones = explode("-", $status);
  $regimen = $porciones[0];
  $motivo = $porciones[1];

  if($futuro_hoy=="hoy") {
      $sql_1 = "SELECT * FROM tbl_contingencia_2021 WHERE num_rut=:num_rut";
      $connexion->query($sql_1);
      $connexion->bind(':num_rut', $rut_col);
      $cod_1 = $connexion->resultset();

      if($cod_1[0]->id > 0) {
          $sql = "UPDATE tbl_contingencia_2021 SET regimen=:regimen, motivo=:motivo, fecha_inicio=:fecha_inicio, fecha_termino=:fecha_termino, rut_actualizador=:rut, fecha_actualizacion=:fecha_hoy, hora_actualizacion=:hora_hoy, regimen_historico=:regimen_historico, motivo_historico=:motivo_historico, fecha_inicio_historico=:fecha_inicio_historico, fecha_termino_historico=:fecha_termino_historico WHERE num_rut=:num_rut";
          $connexion->query($sql);
          $connexion->bind(':regimen', $regimen);
          $connexion->bind(':motivo', $motivo);
          $connexion->bind(':fecha_inicio', $fi_rut);
          $connexion->bind(':fecha_termino', $ft_rut);
          $connexion->bind(':rut', $rut);
          $connexion->bind(':fecha_hoy', $fecha_hoy);
          $connexion->bind(':hora_hoy', $hora_hoy);
          $connexion->bind(':regimen_historico', $cod_1[0]->regimen);
          $connexion->bind(':motivo_historico', $cod_1[0]->motivo);
          $connexion->bind(':fecha_inicio_historico', $cod_1[0]->fecha_inicio);
          $connexion->bind(':fecha_termino_historico', $cod_1[0]->fecha_termino);
          $connexion->bind(':num_rut', $rut_col);
          $connexion->execute();
      }
  }

  if($futuro_hoy=="futuro") {
      // add your future code here if required
  }
}


function MiEquipoContingenciaStatus2021($rut,$hoy,$id_empresa) {
  $connexion = new DatabasePDO();

  $sql = "SELECT * FROM tbl_contingencia_2021 
          WHERE num_rut = :rut 
          AND fecha_inicio <= :hoy 
          AND fecha_termino >= :hoy";
  
  $connexion->query($sql);
  $connexion->bind(':rut', $rut);
  $connexion->bind(':hoy', $hoy);
  $cod = $connexion->resultset();
  return ($cod);	
}

function Contingencia_2021_MiEquipoo($rut, $id_empresa) {
  $connexion = new DatabasePDO();

  $sql = "SELECT rut, nombre_completo AS nombre_completo 
          FROM tbl_usuario h 
          WHERE h.jefe = :rut 
          AND h.id_empresa = :id_empresa 
          UNION 
          SELECT h.rut, 
                 (SELECT nombre_completo FROM tbl_usuario WHERE rut = h.rut) AS nombre_completo 
          FROM tbl_rut_backup h 
          WHERE h.rut_jefe = :rut 
          AND (SELECT id FROM tbl_usuario WHERE rut = h.rut) > 0 
          ORDER BY nombre_completo";
  
  $connexion->query($sql);
  $connexion->bind(':rut', $rut);
  $connexion->bind(':id_empresa', $id_empresa);
  $cod = $connexion->resultset();
  return $cod;
}

function Contingencia_mi_equipo_consolidado_contingencia_DownloadSinFiltroGroupbyRut($id_empresa) {
  $connexion = new DatabasePDO();

  $sql = "SELECT * FROM tbl_contingencia_2020 
          WHERE id_empresa = :id_empresa 
          GROUP BY rut";
  
  $connexion->query($sql);
  $connexion->bind(':id_empresa', $id_empresa);
  $cod = $connexion->resultset();
  return $cod;
}

function Contingencia_mi_equipo_consolidado_contingencia_21_DownloadSinFiltroGroupbyRut_FI_FT($fi,$ft,$id_empresa) {
  $connexion = new DatabasePDO();
  $sql = "SELECT * FROM tbl_contingencia_2021_dia WHERE id_empresa = :id_empresa AND fecha >= :fi AND fecha <= :ft GROUP BY rut";
  $connexion->query($sql);
  $connexion->bind(':id_empresa', $id_empresa);
  $connexion->bind(':fi', $fi);
  $connexion->bind(':ft', $ft);
  $cod = $connexion->resultset();
  return $cod;
}

function ContingenciaDiasHabiles($fecha_inicio, $fecha_hoy) {
  $connexion = new DatabasePDO();
  $sql = "SELECT COUNT(id) AS cuenta FROM tbl_fecha_laboral WHERE fecha >= :fecha_inicio AND fecha <= :fecha_hoy";
  $connexion->query($sql);
  $connexion->bind(':fecha_inicio', $fecha_inicio);
  $connexion->bind(':fecha_hoy', $fecha_hoy);
  $cod = $connexion->resultset();
  return $cod[0]->cuenta;
}

function ContingenciaRecuento_DatosUsuario_2021_data($rut, $fecha_inicio, $id_empresa)
{
$connexion = new DatabasePDO();

$sql1 = "SELECT COUNT(id) as cuenta FROM tbl_contingencia_2021_dia WHERE rut=:rut AND (regimen='Presencial') AND fecha>=:fecha_inicio";
$connexion->query($sql1);
$connexion->bind(':rut', $rut);
$connexion->bind(':fecha_inicio', $fecha_inicio);
$cod1 = $connexion->resultset();

$sql2 = "SELECT COUNT(id) as cuenta FROM tbl_contingencia_2021_dia WHERE rut=:rut AND (regimen='Trabajando en Casa por contingencia' OR regimen='Trabajando en Casa') AND fecha>=:fecha_inicio";
$connexion->query($sql2);
$connexion->bind(':rut', $rut);
$connexion->bind(':fecha_inicio', $fecha_inicio);
$cod2 = $connexion->resultset();

$sql3 = "SELECT COUNT(id) as cuenta FROM tbl_contingencia_2021_dia WHERE rut=:rut AND (regimen='En Casa Sin Trabajar por contingencia' OR regimen='En Casa Sin Trabajar') AND fecha>=:fecha_inicio";
$connexion->query($sql3);
$connexion->bind(':rut', $rut);
$connexion->bind(':fecha_inicio', $fecha_inicio);
$cod3 = $connexion->resultset();

$sql4 = "SELECT COUNT(id) as cuenta FROM tbl_contingencia_2021_dia WHERE rut=:rut AND (regimen='Ausente') AND fecha>=:fecha_inicio";
$connexion->query($sql4);
$connexion->bind(':rut', $rut);
$connexion->bind(':fecha_inicio', $fecha_inicio);
$cod4 = $connexion->resultset();

$arreglo[0] = $cod1[0]->cuenta;
$arreglo[1] = $cod2[0]->cuenta;
$arreglo[2] = $cod3[0]->cuenta;
$arreglo[3] = $cod4[0]->cuenta;

return $arreglo;
}

function ContingenciaRecuento_DatosUsuario_data($rut, $fecha_inicio, $id_empresa)
{
    $connexion = new DatabasePDO();

    $sql1 = "SELECT COUNT(h.id) AS cuenta FROM tbl_contingencia_2020 h WHERE h.rut=:rut AND (
                h.status='No Trabajando / Licencia Medica' OR
                h.status='No Trabajando / No se ha podido contactar' OR
                h.status='No Trabajando / otro motivo' OR
                h.status='No Trabajando / Permiso extraordinario , mayores 65 a�os, enfermedad riesgo o caso social' OR
                h.status='No Trabajando / Permiso por beneficio (Familiar, cambio domicilio, banco de puntos, entre otros)' OR
                h.status='No trabajando / Sin informaci�n del colaborador' OR
                h.status='No Trabajando / Vacaciones'
            ) AND h.fecha>=:fecha_inicio";

    $connexion->query($sql1);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':fecha_inicio', $fecha_inicio);
    $cod1 = $connexion->resultset();

    $sql2 = "SELECT COUNT(h.id) AS cuenta FROM tbl_contingencia_2020 h WHERE h.rut=:rut AND (
                h.status='Trabajando / Trabajo en Casa' OR
                h.status='Trabajando / Trabajo en Casa con Permiso por Precauci�n COVID 19'
            ) AND h.fecha>=:fecha_inicio";

    $connexion->query($sql2);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':fecha_inicio', $fecha_inicio);
    $cod2 = $connexion->resultset();

    $sql3 = "SELECT COUNT(h.id) AS cuenta FROM tbl_contingencia_2020 h WHERE h.rut=:rut AND (
                h.status='Trabajando / Instalaci�n Banco' OR
                h.status='Trabajando / Ubicaci�n Alterna Ciudad de los Valles' OR
                h.status='Trabajando / Ubicaci�n Alterna Estadio' OR
                h.status='Trabajando / Ubicaci�n Alterna Sotero Sanz'
            ) AND h.fecha>=:fecha_inicio";

    $connexion->query($sql3);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':fecha_inicio', $fecha_inicio);
    $cod3 = $connexion->resultset();

    $arreglo[0] = $cod1[0]->cuenta;
    $arreglo[1] = $cod2[0]->cuenta;
    $arreglo[2] = $cod3[0]->cuenta;

    return $arreglo;
}

function ContingenciaRecuento_DatosUsuario_21_data($rut, $fecha_inicio, $id_empresa)
{
    $connexion = new DatabasePDO();
    
    $sql1 = "select count(h.id) as cuenta from tbl_contingencia_2021_dia h where h.rut=:rut and 
            (
                    h.regimen='Presencial'
            )       
            and h.fecha>=:fecha_inicio";

    $connexion->query($sql1);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':fecha_inicio', $fecha_inicio);
    $cod1 = $connexion->resultset();

    $sql2 = "
            select count(h.id) as cuenta from tbl_contingencia_2021_dia h where h.rut=:rut and 
            (
                    h.regimen='Trabajando en Casa por contingencia' or
                    h.regimen='Trabajando en Casa'
            ) 
            and h.fecha>=:fecha_inicio";

    $connexion->query($sql2);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':fecha_inicio', $fecha_inicio);
    $cod2 = $connexion->resultset();

    $sql3="
            select count(h.id) as cuenta from tbl_contingencia_2021_dia h where h.rut=:rut and 
            (
                    h.regimen='En Casa Sin Trabajar' or
                    h.regimen='En Casa Sin Trabajar por contingencia' 
            ) 
            and h.fecha>=:fecha_inicio";

    $connexion->query($sql3);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':fecha_inicio', $fecha_inicio);
    $cod3 = $connexion->resultset();


    $sql4="
            select count(h.id) as cuenta from tbl_contingencia_2021_dia h where h.rut=:rut and 
            (
                    h.regimen='Ausente'
            ) 
            and h.fecha>=:fecha_inicio";

    $connexion->query($sql4);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':fecha_inicio', $fecha_inicio);
    $cod4 = $connexion->resultset();
    
    $arreglo[1]=$cod1[0]->cuenta;
    $arreglo[2]=$cod2[0]->cuenta;
    $arreglo[3]=$cod3[0]->cuenta;
    $arreglo[4]=$cod4[0]->cuenta;

    return ($arreglo);
}

function Contingencia_2020_insert_new_ubicacion($Ub, $temporalidad, $rut_col) {
  $connexion = new DatabasePDO();
  $fecha_hoy = date("Y-m-d");
  $hora_hoy = date("H:i:s");
  $Ubic = ContingenciaBuscaDireccionId($Ub);
  $temporalidad = utf8_decode($temporalidad);
  $sql_del = "DELETE FROM tbl_contingencia_ubicacion_colaboradores WHERE rut=:rut";
  $connexion->query($sql_del);
  $connexion->bind(':rut', $rut_col);
  $connexion->execute();
  
  $sql = "INSERT INTO tbl_contingencia_ubicacion_colaboradores (rut,direccion,piso,referencia,temporalidad,fecha,hora,rut_actualizador,id_empresa,comuna,ciudad) 
          VALUES (:rut, :direccion, :piso, :referencia, :temporalidad, :fecha, :hora, :rut_actualizador, :id_empresa, :comuna, :ciudad)";
  $connexion->query($sql);
  $connexion->bind(':rut', $rut_col);
  $connexion->bind(':direccion', $Ubic[0]->direccion);
  $connexion->bind(':piso', $Ubic[0]->piso);
  $connexion->bind(':referencia', $Ubic[0]->referencia);
  $connexion->bind(':temporalidad', $temporalidad);
  $connexion->bind(':fecha', $fecha_hoy);
  $connexion->bind(':hora', $hora_hoy);
  $connexion->bind(':rut_actualizador', $_SESSION["user_"]);
  $connexion->bind(':id_empresa', $_SESSION["id_empresa"]);
  $connexion->bind(':comuna', $Ubic[0]->comuna);
  $connexion->bind(':ciudad', $Ubic[0]->ciudad);
  $connexion->execute();
  }
	
  function Contingencia_2021_insert_new_ubicacion($Ub,$temporalidad,$rut_col){
    $connexion = new DatabasePDO();
    $fecha_hoy = date("Y-m-d");
    $hora_hoy = date("H:i:s");
    $Ubic = ContingenciaBuscaDireccionId($Ub);
    $temporalidad = utf8_decode($temporalidad);
		
    $sql_del = "DELETE FROM tbl_contingencia_ubicacion_colaboradores WHERE rut = :rut_col";
    $connexion->query($sql_del);
    $connexion->bind(':rut_col', $rut_col);
    $connexion->execute();

    $sql = "INSERT INTO tbl_contingencia_ubicacion_colaboradores
            (rut, direccion, piso, referencia, temporalidad, fecha, hora, rut_actualizador, id_empresa, comuna, ciudad)
            VALUES 
            (:rut_col, :direccion, :piso, :referencia, :temporalidad, :fecha_hoy, :hora_hoy, :user_, :id_empresa, :comuna, :ciudad)";
    $connexion->query($sql);
    $connexion->bind(':rut_col', $rut_col);
    $connexion->bind(':direccion', $Ubic[0]->direccion);
    $connexion->bind(':piso', $Ubic[0]->piso);
    $connexion->bind(':referencia', $Ubic[0]->referencia);
    $connexion->bind(':temporalidad', $temporalidad);
    $connexion->bind(':fecha_hoy', $fecha_hoy);
    $connexion->bind(':hora_hoy', $hora_hoy);
    $connexion->bind(':user_', $_SESSION["user_"]);
    $connexion->bind(':id_empresa', $_SESSION["id_empresa"]);
    $connexion->bind(':comuna', $Ubic[0]->comuna);
    $connexion->bind(':ciudad', $Ubic[0]->ciudad);
    $connexion->execute();
}
	
function ContingenciaBuscaDireccionId($id){
  $connexion = new DatabasePDO();
  $sql = "SELECT * FROM tbl_contingencia_ubicacion WHERE id = :id";
  $connexion->query($sql);
  $connexion->bind(':id', $id);
  $cod = $connexion->resultset();
  return $cod;
  }
  
function ContingenciaBuscaDireccion(){
  $connexion = new DatabasePDO();
  $sql = "SELECT * FROM tbl_contingencia_ubicacion";
  $connexion->query($sql);
  $cod = $connexion->resultset();
  return $cod;
  }
  
function Contingencia_Duplica_Linea($rut, $ayer, $hoy, $rut_col, $id_empresa){
  $connexion = new DatabasePDO();
  $array_linea = MiEquipoContingenciaStatus($rut_col,$hoy,$ayer,$id_empresa);
  $Usua=DatosUsuario_($rut_col, $id_empresa);
  $UsuaJ=DatosUsuario_($Usua[0]->jefe, $id_empresa);
  Contingencia_Inserta_Linea($rut, $rut_col, $array_linea[0]->status, $array_linea[0]->detalle, $hoy, $id_empresa, $Usua[0]->nombre, $Usua[0]->cargo, $Usua[0]->division, $UsuaJ[0]->rut, $UsuaJ[0]->nombre_completo);
  }

  function Contingencia_Inserta_Linea($rut, $rut_col, $status, $detalle, $fecha, $id_empresa, $nombre, $cargo, $division, $rut_jefe, $nombre_jefe) {
    
    $connexion = new DatabasePDO();
    
    $fecha_hoy = date("Y-m-d");
    $hora_hoy = date("H:i:s");
    $hora_hoy2 = date("H:i:s");
    
    $sql_1 = "SELECT id FROM tbl_contingencia_2020 WHERE rut = :rut_col AND fecha = :fecha";
    $connexion->query($sql_1);
    $connexion->bind(':rut_col', $rut_col);
    $connexion->bind(':fecha', $fecha);
    $cod_1 = $connexion->resultset();
    
    if ($cod_1[0]->id > 0) {
        $sql = "UPDATE tbl_contingencia_2020 SET status = :status, detalle = :detalle, edit = '', rut_actualizador = :rut, fecha_actualizacion = :fecha_hoy, hora_actualizacion = :hora_hoy WHERE rut = :rut_col AND fecha = :fecha";
    } else {
        if ($fecha_hoy <> $fecha) {
            $hora_hoy = "";
        }
        $sql = "INSERT INTO tbl_contingencia_2020 (rut, status, detalle, fecha, id_empresa, rut_actualizador, fecha_actualizacion, hora_actualizacion, nombre, cargo, division, rut_jefe, nombre_jefe, hora) VALUES (:rut_col, :status, :detalle, :fecha, :id_empresa, :rut, :fecha_hoy, :hora_hoy2, :nombre, :cargo, :division, :rut_jefe, :nombre_jefe, :hora_hoy)";
    }
    
    $connexion->query($sql);
    $connexion->bind(':rut_col', $rut_col);
    $connexion->bind(':status', $status);
    $connexion->bind(':detalle', $detalle);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':fecha_hoy', $fecha_hoy);
    $connexion->bind(':hora_hoy', $hora_hoy);
    $connexion->bind(':nombre', $nombre);
    $connexion->bind(':cargo', $cargo);
    $connexion->bind(':division', $division);
    $connexion->bind(':rut_jefe', $rut_jefe);
    $connexion->bind(':nombre_jefe', $nombre_jefe);
    $connexion->bind(':hora_hoy2', $hora_hoy2);
    
    $connexion->execute();
}

function ContingenciaBuscaAlertas($rut, $id_empresa){
    
    $connexion = new DatabasePDO();
    
    $fecha_hoy = date("Y-m-d");
    
    $sql = "SELECT * FROM tbl_rut_alerta WHERE rut = :rut AND fecha_inicio <= :fecha_hoy AND fecha_termino >= :fecha_hoy";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':fecha_hoy', $fecha_hoy);
    $cod = $connexion->resultset();
    
    return $cod;	
}

function ContingenciaDatosUsuario_($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT nombre_completo, cargo, fecha_ingreso, teletrabajo
            FROM tbl_usuario
            WHERE rut = :rut AND id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $datos = $connexion->resultset();
    return $datos;
}

function ContingenciaDatosUsuarioUbicacion_($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT *
            FROM tbl_contingencia_ubicacion_colaboradores
            WHERE rut = :rut AND id_empresa = :id_empresa
            ORDER BY id DESC LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $datos = $connexion->resultset();
    return $datos;
}

function Contingencia_2020_rutesjefe($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT COUNT(id) AS cuenta FROM tbl_usuario WHERE jefe = :rut AND id_empresa = :id_empresa
            UNION
            SELECT COUNT(id) AS cuenta FROM tbl_rut_backup WHERE rut_jefe = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();

    if($cod[0]->cuenta > 0 || $cod[1]->cuenta > 0) {
        $cod = 1;
    } else {
        $cod = 0;
    }

    return $cod;
}

function Contingencia_2020_MiEquipoo($rut, $id_empresa)
{
$connexion = new DatabasePDO();
$sql = "select rut, nombre_completo as nombre_completo from tbl_usuario h where h.jefe=:rut and h.id_empresa=:id_empresa 
	UNION
	select h.rut,(select nombre_completo from tbl_usuario where rut=h.rut) as nombre_completo from tbl_rut_backup h where h.rut_jefe=:rut and (select id from tbl_usuario where rut=h.rut)>0
order by nombre_completo
 ";
$connexion->query($sql);
$connexion->bind(':rut', $rut);
$connexion->bind(':id_empresa', $id_empresa);
$cod = $connexion->resultset();

return $cod;
}

function Contingencia_De_Linea($rut, $hoy, $rut_col, $id_empresa){
$connexion = new DatabasePDO();
$fecha = date("Y-m-d");
$sql = "update tbl_contingencia_2020 set edit='1' where rut=:rut_col and fecha=:hoy";
$connexion->query($sql);
$connexion->bind(':rut_col', $rut_col);
$connexion->bind(':hoy', $hoy);
$connexion->execute();
}

function Contingencia_MiEquipoContingenciaStatusUltimaFechaEscritaNoHoyData($rut,$hoy,$id_empresa) {
$connexion = new DatabasePDO();
$sql="select * from tbl_contingencia_2020 where id_empresa=:id_empresa and rut=:rut and fecha!=:hoy order by fecha desc limit 1";
$connexion->query($sql);
$connexion->bind(':id_empresa', $id_empresa);
$connexion->bind(':rut', $rut);
$connexion->bind(':hoy', $hoy);
$cod = $connexion->resultset();
return $cod;
}

function Contingencia_Truncatetbl_rut_backup(){
$connexion = new DatabasePDO();
$fecha = date("Y-m-d");
$sql = "TRUNCATE TABLE tbl_rut_backup";
$connexion->query($sql);
$cod = $connexion->resultset();
return $cod;
}

function Contingencia_Truncatetbl_rut_alert(){
  $connexion = new DatabasePDO();
  $fecha = date("Y-m-d");
  $sql   = "TRUNCATE TABLE tbl_rut_alerta ";
  $connexion->query($sql);
  $cod = $connexion->resultset();
  return ($cod);
}

function Contingencia_Truncatetbl_contingencia_ubicacion(){
  $connexion = new DatabasePDO();
  $fecha = date("Y-m-d");
  $sql   = "TRUNCATE TABLE tbl_contingencia_ubicacion ";
  $connexion->query($sql);
  $cod = $connexion->resultset();
  return ($cod);
}

function Contingencia_tbl_rut_backup_Insert_Hijos($columnA,$columnB,$columnC,$columnD){
  $connexion = new DatabasePDO();
  $fecha = date("Y-m-d");
  $hora  = date("H:i:s");
  $sql   = "insert into tbl_rut_backup (rut,rut_jefe,fecha_inicio,fecha_termino)
      VALUES
      (:columnA,:columnB,:columnC,:columnD)";
  $connexion->query($sql);
  $connexion->bind(':columnA', $columnA);
  $connexion->bind(':columnB', $columnB);
  $connexion->bind(':columnC', $columnC);
  $connexion->bind(':columnD', $columnD);
  $connexion->execute();
}

function Contingencia_tbl_ubicacion_Insert($columnA,$columnB,$columnC,$columnD,$columnE){
  $connexion = new DatabasePDO();
  $fecha = date("Y-m-d");
  $hora  = date("H:i:s");
  $sql   = "insert into tbl_contingencia_ubicacion (direccion, piso, referencia, comuna, ciudad)
      VALUES
      (:columnA,:columnB,:columnC,:columnD,:columnE)";
  $connexion->query($sql);
  $connexion->bind(':columnA', $columnA);
  $connexion->bind(':columnB', $columnB);
  $connexion->bind(':columnC', $columnC);
  $connexion->bind(':columnD', $columnD);
  $connexion->bind(':columnE', $columnE);
  $connexion->execute();
}

function Contingencia_tbl_rut_alert_Insert_Hijos($columnA,$columnB,$columnC,$columnD){
  $connexion = new DatabasePDO();
  $fecha = date("Y-m-d");
  $hora  = date("H:i:s");
  $sql   = "INSERT INTO tbl_rut_alerta (rut, alerta, fecha_inicio, fecha_termino) 
            VALUES (:columnA, :columnB, :columnC, :columnD)";
  $connexion->query($sql);
  $connexion->bind(':columnA', $columnA);
  $connexion->bind(':columnB', $columnB);
  $connexion->bind(':columnC', $columnC);
  $connexion->bind(':columnD', $columnD);
  $connexion->execute();
}

function Contingencia_mi_equipo_consolidado_backup_Download($id_empresa){
  $connexion = new DatabasePDO();
  $sql = "SELECT * FROM tbl_rut_backup";
  $connexion->query($sql);
  $cod = $connexion->resultset();
  return $cod;
}

function Contingencia_mi_equipo_consolidado_alertDownload($id_empresa){
  $connexion = new DatabasePDO();
  $sql = "SELECT * FROM tbl_rut_alerta";
  $connexion->query($sql);
  $cod = $connexion->resultset();
  return $cod;
}

function Contingencia_mi_equipo_consolidado_ubicacionDownload($id_empresa){
  $connexion = new DatabasePDO();
  $sql = "SELECT * FROM tbl_contingencia_ubicacion";
  $connexion->query($sql);
  $cod = $connexion->resultset();
  return $cod;
}

function Contingencia_mi_equipo_consolidado_ubicacionColaboradoresDownload($id_empresa){
  $connexion = new DatabasePDO();
  $sql = "SELECT * FROM tbl_contingencia_ubicacion_colaboradores";
  $connexion->query($sql);
  $cod = $connexion->resultset();
  return $cod;
}

function Contingencia_mi_equipo_consolidado_contingencia_Download($id_empresa){
  $connexion = new DatabasePDO();
  
  $d2 = date('y-m-d', strtotime('-120 days'));
  
  $sql="select * from tbl_contingencia_2020 where id_empresa=:id_empresa and fecha>=:d2 order by fecha DESC ";
  $connexion->query($sql);
  $connexion->bind(':id_empresa', $id_empresa);
  $connexion->bind(':d2', $d2);
  $cod = $connexion->resultset();
  return $cod;        
  }
  
  function Contingencia_mi_equipo_consolidado_contingencia_DownloadSinFiltro($id_empresa){
  $connexion = new DatabasePDO();
  
  $sql="select * from tbl_contingencia_2020 where id_empresa=:id_empresa order by fecha DESC ";
  $connexion->query($sql);
  $connexion->bind(':id_empresa', $id_empresa);
  $cod = $connexion->resultset();
  return $cod;        
  }
  
  function Contingencia_ColocaEventosCalendario_data($id_empresa, $tipo, $filtro_cui, $filtro_rut, $filtro_tipo, $rut_usuario)
  {
  $connexion = new DatabasePDO();
  
  $queryTipo = "";  $queryRUT = "";   $queryRegistro="";
  if ($tipo <> '') {
      $queryTipo = " and id_categoria=:tipo ";
  }
  
  if($nombrelikecui<>''){
   $queryCui = " and nombre like '%$filtro_cui%' ";
  }
  
  if($filtro_rut<>''){
   $queryRUT = " and descripcion like '%$filtro_rut%' ";
  }
  
  
  if($filtro_tipo<>''){
   $queryRegistro = " and icono = '$filtro_tipo' ";
  }
  
  $sql = "select h.status, h.detalle, h.fecha from tbl_contingencia_2020 h where h.rut=:rut_usuario  order by h.fecha desc";
  
  $connexion->query($sql);
  $connexion->bind(':rut_usuario', $rut_usuario);
  $cod = $connexion->resultset();
  return $cod;
  }

  function Contingencia_ColocaEventosCalendario_21_data($id_empresa, $tipo, $filtro_cui, $filtro_rut, $filtro_tipo, $rut_usuario)
  {
      $connexion = new DatabasePDO();
      $queryTipo = "";  
      $queryRUT = "";   
      $queryRegistro = "";
  
      if ($tipo <> '') {
          $queryTipo = " and id_categoria=:tipo ";
      }
  
      if($filtro_cui<>''){
       $queryCui = " and nombre like :filtro_cui ";
      }
  
      if($filtro_rut<>''){
       $queryRUT = " and descripcion like :filtro_rut ";
      }
  
      if($filtro_tipo<>''){
       $queryRegistro = " and icono = :filtro_tipo ";
      }
  
      $sql = "select h.* from tbl_contingencia_2021_dia h where h.rut=:rut_usuario and h.fecha>='2021-05-01' ".$queryTipo.$queryCui.$queryRUT.$queryRegistro." order by h.fecha desc";
      
      $connexion->query($sql);
      $connexion->bind(':rut_usuario', $rut_usuario);
  
      if ($tipo <> '') {
          $connexion->bind(':tipo', $tipo);
      }
  
      if($filtro_cui<>''){
          $connexion->bind(':filtro_cui', "%$filtro_cui%");
      }
  
      if($filtro_rut<>''){
          $connexion->bind(':filtro_rut', "%$filtro_rut%");
      }
  
      if($filtro_tipo<>''){
          $connexion->bind(':filtro_tipo', $filtro_tipo);
      }
  
      $cod = $connexion->resultset();
      return $cod;
  }
function Contingencia_TurnosEventosCalendario_23_data($id_empresa, $tipo, $filtro_cui, $filtro_rut, $filtro_tipo, $rut_usuario, $rut_jefe)
{
    $connexion = new DatabasePDO();
    $queryTipo = "";
    $queryRUT = "";
    $queryRegistro = "";

    if ($tipo <> '') {
        $queryTipo = " and id_categoria=:tipo ";
    }

    if($filtro_cui<>''){
        $queryCui = " and nombre like :filtro_cui ";
    }

    if($filtro_rut<>''){
        $queryRUT = " and descripcion like :filtro_rut ";
    }

    if($filtro_tipo<>''){
        $queryRegistro = " and icono = :filtro_tipo ";
    }
    $year=date("Y");
    $year_next=$year+1;
    //$sql = "select h.* from tbl_turnos_diarios h where h.rut_jefe=:rut_jefe and h.fecha>='2023-05-01' ".$queryTipo.$queryCui.$queryRUT.$queryRegistro." order by h.fecha desc";
        $sql= "select h.*,w.nombre_completo from tbl_turnos_diarios h
                inner join tbl_reportes_online_usuario w on w.rut=h.rut

                where w.jefe=:rut_jefe and h.fecha>='".$year."-01-01'  and h.fecha<'".$year_next."-01-01' ";
    $connexion->query($sql);
    if($rut_jefe<>''){
        $connexion->bind(':rut_jefe', $rut_jefe);
    }


    if ($tipo <> '') {
        $connexion->bind(':tipo', $tipo);
    }

    if($filtro_cui<>''){
        $connexion->bind(':filtro_cui', "%$filtro_cui%");
    }

    if($filtro_rut<>''){
        $connexion->bind(':filtro_rut', "%$filtro_rut%");
    }

    if($filtro_tipo<>''){
        $connexion->bind(':filtro_tipo', $filtro_tipo);
    }

    $cod = $connexion->resultset();
    return $cod;
}

function BuscaTurnosUsuarioData($rut) {
	$connexion = new DatabasePDO();
	$sql="select teletrabajo from tbl_usuario where rut=:rut";
	$connexion->query($sql);
	$connexion->bind(':rut', $rut);
	$cod = $connexion->resultset();
	return $cod[0]->teletrabajo;
}

function GuardaTurnosColaborador($rut, $fecha, $modalidad, $rut_jefe,$fecha_hoy) {
	//$variables = get_defined_vars();
    //echo "<pre>";
    //print_r($variables);
    //echo "</pre>";
	$connexion = new DatabasePDO();
	//$fecha_hoy = date("Y-m-d");
	
	$sql_del = "DELETE FROM tbl_turnos_diarios WHERE rut=:rut and fecha=:fecha";
  $connexion->query($sql_del);
  $connexion->bind(':rut', $rut);
  $connexion->bind(':fecha', $fecha);
  $connexion->execute();
	
	$sql   = "insert into tbl_turnos_diarios (rut, fecha, modalidad, rut_jefe, fecha_registro) VALUES (:rut,:fecha,:modalidad,:rut_jefe,:fecha_hoy)";
	$connexion->query($sql);
	$connexion->bind(':rut', $rut);
	$connexion->bind(':fecha', $fecha);
	$connexion->bind(':modalidad', $modalidad);
	$connexion->bind(':rut_jefe', $rut_jefe);
	$connexion->bind(':fecha_hoy', $fecha_hoy);
	$connexion->execute();
} 
 
