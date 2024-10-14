<?php


function SelectDistinctEmpresaPorInscripcion($id_inscripcion){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "
	
SELECT DISTINCT
	(tbl_inscripcion_usuarios.empresa),
	tbl_empresa_holding.empresa as nombre_empresa_holding
FROM
	tbl_inscripcion_usuarios
INNER JOIN tbl_empresa_holding ON tbl_empresa_holding.rut= tbl_inscripcion_usuarios.empresa
WHERE
	id_inscripcion = '$id_inscripcion'
	
	
			";
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}



function DatosDeCorreoDadoId($id_correo){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "
	
	select * from tbl_correos where id='$id_correo'
	
	
			";
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}



function ActualizaDatosOtec($rut, $nombre_otec, $direccion_otec, $telefono_otec, $email_otec, $contacto_otec)
{
	global $c_host,$c_user,$c_pass,$c_db;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "UPDATE tbl_otec
			SET 
			nombre= '$nombre_otec', 
			direccion= '$direccion_otec', 
			telefono= '$telefono_otec', 
			email= '$email_otec', 
			contacto= '$contacto_otec'

			WHERE rut = '$rut' ";
	$database->setquery($sql);
	$database->query();
}


function InsertaOtec($rut, $nombre_otec, $direccion_otec, $telefono_otec, $email_otec, $contacto_otec){
	global $c_host,$c_user,$c_pass,$c_db;	

	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	$sql = "INSERT INTO tbl_otec(rut, nombre, direccion, telefono, email, contacto ) ".
					   "VALUES ('$rut', '$nombre_otec', '$direccion_otec', '$telefono_otec', '$email_otec', '$contacto_otec');";

	$database->setquery($sql);
	$database->query();	

}


function TotalObjetivosIndividuales($arreglo_post){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	
	
	if($arreglo_post["area"]) $filtro.=" and base_evaluado.area='".$arreglo_post["area"]."'";



	$sql = "
			SELECT
	tbl_objetivos_individuales.*, tbl_sgd_relaciones.evaluado,
	tbl_sgd_relaciones.evaluador,
	tbl_sgd_relaciones.validador,
	base_evaluado.nombre_completo,
	base_evaluado.rut_completo AS rut_completo_evaluado,
	base_evaluado.area AS area,
	base_evaluado.cargo AS cargo,
	tbl_objetivos_dimension.nombre AS nombre_dimension,
	tbl_sgd_relaciones.evaluador AS rut_evaluador,
	base_evaluador.rut_completo AS rut_completo_evaluador,
	base_evaluador.nombre_completo AS nombre_evaluador,
	base_evaluador.cargo AS cargo_evaluador, 
base_validador.rut_completo as rut_completo_validador, 
tbl_sgd_relaciones.validador as rut_validador, 
base_validador.nombre_completo as nombre_validador, 
base_validador.cargo as cargo_validador
FROM
	tbl_objetivos_individuales
INNER JOIN tbl_sgd_relaciones ON tbl_sgd_relaciones.evaluado = tbl_objetivos_individuales.rut
INNER JOIN tbl_usuario AS base_evaluado ON base_evaluado.rut = tbl_sgd_relaciones.evaluado
INNER JOIN tbl_usuario AS base_evaluador ON base_evaluador.rut = tbl_sgd_relaciones.evaluador
INNER JOIN tbl_usuario AS base_validador ON base_validador.rut = tbl_sgd_relaciones.validador
INNER JOIN tbl_objetivos_dimension ON tbl_objetivos_individuales.dimension = tbl_objetivos_dimension.id
WHERE
	1 $filtro
";

	$database->setquery(utf8_decode($sql));
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}





function DistincCentroCostoInscripcion($id_inscripcion){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "SELECT DISTINCT
	(
		centro_costo
	) AS centro_costo from tbl_inscripcion_usuarios
WHERE
	id_inscripcion = '$id_inscripcion'
			";
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}



function DistincEmpresasEnInscripcionesDeUsuariosPorInscripcion($id_inscripcion){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "SELECT
	distinct(tbl_inscripcion_usuarios.empresa) , tbl_empresa_holding.empresa as nombre_empresa

FROM
	tbl_inscripcion_usuarios

inner JOIN tbl_empresa_holding
on tbl_empresa_holding.rut=tbl_inscripcion_usuarios.empresa
WHERE
	id_inscripcion = '$id_inscripcion'
			";
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}




function ObtenerTotalDeEvaluadores($arreglo_post){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	
	
	if($arreglo_post["area"]) $filtro.=" and tbl_usuario.area='".$arreglo_post["area"]."'";



	$sql = "SELECT DISTINCT
	(
		tbl_sgd_relaciones.evaluador
	) AS rut_evaluador,
	nombre,
	apaterno,
	amaterno,
	nombre_completo,
	cargo,
	gerencia,
	area,
	email,
	(
		SELECT
			count(*) AS total
		FROM
			tbl_sgd_relaciones
		WHERE
			tbl_sgd_relaciones.evaluado <> tbl_sgd_relaciones.evaluador
		AND tbl_sgd_relaciones.evaluador = rut_evaluador
	) AS total_evaluados,
	(
		SELECT
			count(*) AS total
		FROM
			tbl_clave
		INNER JOIN tbl_sgd_relaciones AS tabla_relaciones1 ON tabla_relaciones1.evaluado = tbl_clave.rut
		WHERE
			tabla_relaciones1.evaluador = rut_evaluador
		AND tbl_clave.cambiado = '1'
	) AS total_claves_cambiadas,
	(
		SELECT
			count(*) AS total
		FROM
			tbl_objetivos_indviduales_finalizado
		INNER JOIN tbl_sgd_relaciones AS tabla_relaciones2 ON tabla_relaciones2.evaluado = tbl_objetivos_indviduales_finalizado.evaluado
		WHERE
			tabla_relaciones2.evaluador = rut_evaluador
	) AS total_proposicion_finalizadas,
	(
		SELECT
			count(*) AS total
		FROM
			tbl_objetivos_ajustes_jefe
		INNER JOIN tbl_sgd_relaciones AS tabla_relaciones3 ON tabla_relaciones3.evaluado = tbl_objetivos_ajustes_jefe.evaluado
		AND tabla_relaciones3.evaluador = tbl_objetivos_ajustes_jefe.evaluador
		WHERE
			tabla_relaciones3.evaluador = rut_evaluador
	) AS total_fijacion_finalizados,
	(
		SELECT
			count(*) AS total
		FROM
			tbl_objetivos_validaciones
		INNER JOIN tbl_sgd_relaciones AS tabla_relaciones4 ON tabla_relaciones4.evaluado = tbl_objetivos_validaciones.evaluado
		AND tabla_relaciones4.evaluador = tbl_objetivos_validaciones.evaluador
		WHERE
			tabla_relaciones4.evaluador = rut_evaluador
	) AS total_validacion_finalizados
FROM
	tbl_sgd_relaciones
INNER JOIN tbl_usuario ON tbl_usuario.rut = tbl_sgd_relaciones.evaluador where 1 $filtro
";



	$database->setquery(utf8_decode($sql));
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}


function ObtenerTotalDetalleEvaluadoEvaluador($arreglo_post){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	
	if($arreglo_post["area"]) $filtro.=" and tbl_usuario.area='".$arreglo_post["area"]."'";



	$sql = "SELECT 
	tbl_sgd_relaciones.evaluado as rut_evaluado, 
	tbl_sgd_relaciones.evaluador as rut_evaluador,
	tbl_sgd_relaciones.validador as rut_validador,
	tbl_usuario.rut_completo as rut_completo_evaluado,
	tbl_usuario.nombre,
	tbl_usuario.apaterno,
	tbl_usuario.amaterno,
	tbl_usuario.nombre_completo,
	tbl_usuario.cargo,
	tbl_usuario.gerencia,
	tbl_usuario.area,
	tbl_usuario.email,
	base_evaluador.nombre as nombre_evaluador, 
	base_evaluador.rut_completo as rut_completo_evaluador, 
	base_evaluador.cargo as cargo_evaluador, 
	base_validador.nombre as nombre_validador, 
	base_validador.rut_completo as rut_completo_validador, 
	base_validador.cargo as cargo_validador, 
	
	(
		SELECT
			count(*) AS total
		FROM
			tbl_sgd_relaciones
		WHERE
			tbl_sgd_relaciones.evaluado = rut_evaluado
		AND tbl_sgd_relaciones.evaluador = rut_evaluador
	) AS total_evaluados,
	(
		SELECT
			count(*) AS total
		FROM
			tbl_clave
		INNER JOIN tbl_sgd_relaciones AS tabla_relaciones1 ON tabla_relaciones1.evaluado = tbl_clave.rut
		WHERE
			tabla_relaciones1.evaluador = rut_evaluador
		 and tabla_relaciones1.evaluado = rut_evaluado
		AND tbl_clave.cambiado = '1'
	) AS total_claves_cambiadas,
	(
		SELECT
			count(*) AS total
		FROM
			tbl_objetivos_indviduales_finalizado
		INNER JOIN tbl_sgd_relaciones AS tabla_relaciones2 ON tabla_relaciones2.evaluado = tbl_objetivos_indviduales_finalizado.evaluado
		WHERE
			tabla_relaciones2.evaluador = rut_evaluador and tabla_relaciones2.evaluado = rut_evaluado
	) AS total_proposicion_finalizadas,
	(
		SELECT
			count(*) AS total
		FROM
			tbl_objetivos_ajustes_jefe
		INNER JOIN tbl_sgd_relaciones AS tabla_relaciones3 ON tabla_relaciones3.evaluado = tbl_objetivos_ajustes_jefe.evaluado
		AND tabla_relaciones3.evaluador = tbl_objetivos_ajustes_jefe.evaluador
		WHERE
			tabla_relaciones3.evaluador = rut_evaluador and tabla_relaciones3.evaluado = rut_evaluado
	) AS total_fijacion_finalizados,
	(
		SELECT
			count(*) AS total
		FROM
			tbl_objetivos_validaciones
		INNER JOIN tbl_sgd_relaciones AS tabla_relaciones4 ON tabla_relaciones4.evaluado = tbl_objetivos_validaciones.evaluado
		AND tabla_relaciones4.evaluador = tbl_objetivos_validaciones.evaluador
		WHERE
			tabla_relaciones4.evaluador = rut_evaluador and tabla_relaciones4.evaluado = rut_evaluado
	) AS total_validacion_finalizados
FROM
	tbl_sgd_relaciones
INNER JOIN tbl_usuario ON tbl_usuario.rut = tbl_sgd_relaciones.evaluado
inner join tbl_usuario as base_evaluador on base_evaluador.rut=tbl_sgd_relaciones.evaluador
inner join tbl_usuario as base_validador on base_validador.rut=tbl_sgd_relaciones.validador

where 1 $filtro
order by rut_evaluador 
	
";

	$database->setquery(utf8_decode($sql));
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}






function ObtenerHorarios($tipo){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select * from tbl_horario where  tipo='$tipo'
			";

	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}







function ActualizaDatosCierreUsuarios($id_inscripcion, $rut, $porcentaje_asistencia, $caso_especial, $nota, $estado, $viatico_utilizado, $movilizacion_utilizado)
{
	global $c_host,$c_user,$c_pass,$c_db;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "UPDATE tbl_inscripcion_cierre
			SET 
			porcentaje_asistencia= '$porcentaje_asistencia', 
			caso_especial= '$caso_especial', 
			nota= '$nota', 
			estado= '$estado', 
			viatico_utilizado= '$viatico_utilizado', 
			movilizacion_utilizada= '$movilizacion_utilizado' 
			
			




			WHERE id_inscripcion = '$id_inscripcion' and rut='$rut'";
	$database->setquery($sql);
	$database->query();
}



function VerificaEstaEnCierreSoloIdInscripcion($id_inscripcion, $id_empresa_holding){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	if($id_empresa){
		$sql = "SELECT
	tbl_inscripcion_cierre.*
FROM
	tbl_inscripcion_cierre

inner join tbl_inscripcion_usuarios
on tbl_inscripcion_usuarios.rut=tbl_inscripcion_cierre.rut and tbl_inscripcion_usuarios.id_inscripcion='18' and tbl_inscripcion_usuarios.empresa='$id_empresa_holding'
WHERE
	tbl_inscripcion_cierre.id_inscripcion = '$id_inscripcion' 	";
	}else{
		$sql = "select * from tbl_inscripcion_cierre where  id_inscripcion='$id_inscripcion' 	";	
	}
	

	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}




function VerificaEstaEnCierre($id_inscripcion, $rut){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select * from tbl_inscripcion_cierre where  id_inscripcion='$id_inscripcion' and rut='$rut'
			";
			
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}



function BorraUsuariosCierre($id_inscripcion){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "DELETE FROM tbl_inscripcion_cierre WHERE id_inscripcion='$id_inscripcion'";
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}




function ActualizaDatosRelevantesInscripcion($id_inscripcion, $mejor_alumno, $comentario_mejor_alumno, $alumno_destacado, $comentario_alumno_destacado, $mejora_continua, $comentario_mejora_continua, $porcentaje_satisfaccion, $comentario_porcentaje_satisfaccion)
{
	global $c_host,$c_user,$c_pass,$c_db;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "UPDATE tbl_inscripcion_datos_relevantes
			SET 
			mejor_alumno= '$mejor_alumno', 
			mejor_alumno_comentario= '$comentario_mejor_alumno', 
			alumno_destacado= '$mejor_alumno', 
			alumno_destacado_comentario= '$comentario_alumno_destacado', 
			mejora_continua= '$mejora_continua', 
			mejora_continua_comentario= '$comentario_mejora_continua', 
			porcentaje_satisfaccion= '$porcentaje_satisfaccion', 
			porcentaje_satisfaccion_comentario= '$comentario_porcentaje_satisfaccion'
			




			WHERE id_inscripcion = '$id_inscripcion'";
	$database->setquery($sql);
	$database->query();
}


function BorraInscripcionFinalizada($id_inscripcion){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "DELETE FROM tbl_inscripcion_finalizado WHERE id_inscripcion='$id_inscripcion'";
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}



function InsertaInscripcionFinalizada($id_inscripcion){
	global $c_host,$c_user,$c_pass,$c_db;	
	
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	$sql = "INSERT INTO tbl_inscripcion_finalizado
							(id_inscripcion)  
					VALUES 	('$id_inscripcion');";

	$database->setquery($sql);
	$database->query();	

}


function EstaFinalizadaInscripcion($id_inscripcion){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select * from tbl_inscripcion_finalizado where  id_inscripcion='$id_inscripcion'
			";
			
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}



function TieneDatosRelevantesPorInscripcion($id_inscripcion){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "
	
	SELECT
	tbl_inscripcion_datos_relevantes.*, b_mejor_alumno.nombre AS nombre_mejor_alumno,
	b_mejor_alumno.apaterno AS apaterno_mejor_alumno,
	b_mejor_alumno.amaterno AS amaterno_mejor_alumno,
	b_alumno_destacado.nombre AS nombre_alumno_destacado,
	b_alumno_destacado.apaterno AS apaterno_alumno_destacado,
	b_alumno_destacado.amaterno AS amaterno_alumno_destacado,
	b_mejora_continua.nombre nombre_mejora_continua,
	b_mejora_continua.apaterno AS apaterno_mejora_continua,
	b_mejora_continua.amaterno AS amaterno_mejora_continua
FROM
	tbl_inscripcion_datos_relevantes
LEFT JOIN tbl_usuario AS b_mejor_alumno ON b_mejor_alumno.rut = tbl_inscripcion_datos_relevantes.mejor_alumno
LEFT JOIN tbl_usuario AS b_alumno_destacado ON b_alumno_destacado.rut = tbl_inscripcion_datos_relevantes.alumno_destacado
LEFT JOIN tbl_usuario AS b_mejora_continua ON b_mejora_continua.rut = tbl_inscripcion_datos_relevantes.mejora_continua
WHERE
	id_inscripcion = '$id_inscripcion'
	
	
			";
			
			
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}


function InsertarDatosRelevantesInscripcion($id_inscripcion, $mejor_alumno, $comentario_mejor_alumno, $alumno_destacado, $comentario_alumno_destacado, $mejora_continua, $comentario_mejora_continua, $porcentaje_satisfaccion, $comentario_porcentaje_satisfaccion){
	global $c_host,$c_user,$c_pass,$c_db;	
	
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	$sql = "INSERT INTO tbl_inscripcion_datos_relevantes
							(mejor_alumno, mejor_alumno_comentario, alumno_destacado, alumno_destacado_comentario, mejora_continua, mejora_continua_comentario, porcentaje_satisfaccion, porcentaje_satisfaccion_comentario, id_inscripcion)  
					VALUES 	( '$mejor_alumno', '$comentario_mejor_alumno', '$alumno_destacado', '$comentario_alumno_destacado', '$mejora_continua', '$comentario_mejora_continua', '$porcentaje_satisfaccion', '$comentario_porcentaje_satisfaccion', '$id_inscripcion');";

	$database->setquery($sql);
	$database->query();	

}



function InsertaUsuariosFinalizadosInscripcion($rut, $id_inscripcion, $porcentaje_asistencia, $caso_especial, $nota, $estado, $viatico_utilizado, $movilizacion_utilizada){
	global $c_host,$c_user,$c_pass,$c_db;	

	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	$sql = "INSERT INTO tbl_inscripcion_cierre
							(rut, id_inscripcion, porcentaje_asistencia, caso_especial, nota, estado, viatico_utilizado, movilizacion_utilizada) ".
			
	"VALUES 				( '$rut', '$id_inscripcion', '$porcentaje_asistencia', '$caso_especial', '$nota', '$estado', '$viatico_utilizado', '$movilizacion_utilizada');";
	
	
			
	$database->setquery($sql);
	$database->query();	

}



function ActualizaImagenInscripcion($id, $archivo)
{
	global $c_host,$c_user,$c_pass,$c_db;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "UPDATE tbl_inscripcion_curso SET archivo= '$archivo' WHERE id = '$id'";
	$database->setquery($sql);
	$database->query();
}



function ValorSelectDinaico2Cursos(){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select tbl_curso.*, tbl_curso_clasificacion.clasificacion as nombre_clasificacion from tbl_curso
			inner join tbl_curso_clasificacion
			on tbl_curso_clasificacion.id=tbl_curso.clasificacion
			where tbl_curso.id_empresa='19'
			order by tbl_curso_clasificacion.clasificacion asc
			";
	
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}

function ObtengoUltimoIdInscripcion(){
	
	
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select max(id) as ultimo_id from tbl_inscripcion_curso
		
		
		";

		$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
	
	
	
	
	
}



function InsertaInscritosPorInsc($rut, $movilizacion, $viatico, $id_inscripcion, $tramo, $empresa, $gerencia, $centro_costo, $cargo){
	global $c_host,$c_user,$c_pass,$c_db;	
	$rut_completo=$rut."-".$dv;
	$nombre_completo=$nombre." ".$apaterno." ".$amaterno;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	$sql = "INSERT INTO tbl_inscripcion_usuarios
							(id_inscripcion, rut, movilizacion, viatico, tramo_sence, empresa, gerencia, centro_costo, cargo) ".
			
	"VALUES 				( '$id_inscripcion', '$rut', '$movilizacion', '$viatico', '$tramo', '$empresa', '$gerencia', '$centro_costo', '$cargo');";
			
	$database->setquery($sql);
	$database->query();	

}



function ActualizaInscripcionCurso($arreglo_post)
{
	global $c_host,$c_user,$c_pass,$c_db;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	
	//TRATO LAS FECHAS
	//primero separo con explode
    $fechas= explode('-', trim($arreglo_post["reservation"]));
	$fecha_inicio=$fechas[0];
	$fecha_inicio = str_replace("/","-",$fecha_inicio);

	$fecha_termino=$fechas[1];
	$fecha_termino = str_replace("/","-",$fecha_termino);
	
	
	
	
	
	$sql = "
	
	UPDATE tbl_inscripcion_curso SET 
		fecha_inicio= '".trim($fecha_inicio)."',
		fecha_termino= '".trim($fecha_termino)."',
		numero_dias= '".$arreglo_post["numero_dias"]."',
		abierto_cerrado= '".$arreglo_post["abiertocerrado"]."',
		valor_curso= '".$arreglo_post["valor_curso"]."',
		valor_hora_curso= '".$arreglo_post["valor_hora_curso"]."',
		direccion= '".$arreglo_post["direccion"]."',
		comuna= '".$arreglo_post["comuna"]."',
		ciudad= '".$arreglo_post["ciudad"]."',
		comentarios= '".$arreglo_post["comentario"]."',
		id_curso= '".Decodear3($arreglo_post["idc"])."',
		lunes_d_am= '".$arreglo_post["HOR_LUNES_D_AM"]."',
		lunes_h_am= '".$arreglo_post["HOR_LUNES_H_AM"]."',
		lunes_d_pm= '".$arreglo_post["HOR_LUNES_D_PM"]."',
		lunes_h_pm= '".$arreglo_post["HOR_LUNES_H_PM"]."',
		martes_d_am= '".$arreglo_post["HOR_MARTES_D_AM"]."',
		martes_h_am= '".$arreglo_post["HOR_MARTES_H_AM"]."',
		martes_d_pm= '".$arreglo_post["HOR_MARTES_D_PM"]."',
		martes_h_pm= '".$arreglo_post["HOR_MARTES_H_PM"]."',
		miercoles_d_am= '".$arreglo_post["HOR_MIERCOLES_D_AM"]."',
		miercoles_h_am= '".$arreglo_post["HOR_MIERCOLES_H_AM"]."',
		miercoles_d_pm= '".$arreglo_post["HOR_MIERCOLES_D_PM"]."',
		miercoles_h_pm= '".$arreglo_post["HOR_MIERCOLES_H_PM"]."',
		jueves_d_am= '".$arreglo_post["HOR_JUEVES_D_AM"]."',
		jueves_h_am= '".$arreglo_post["HOR_JUEVES_H_AM"]."',
		jueves_d_pm= '".$arreglo_post["HOR_JUEVES_D_PM"]."',
		jueves_h_pm= '".$arreglo_post["HOR_JUEVES_H_PM"]."',
		viernes_d_am= '".$arreglo_post["HOR_VIERNES_D_AM"]."',
		viernes_h_am= '".$arreglo_post["HOR_VIERNES_H_AM"]."',
		viernes_d_pm= '".$arreglo_post["HOR_VIERNES_D_PM"]."',
		viernes_h_pm= '".$arreglo_post["HOR_VIERNES_H_PM"]."' 		
		
	
		
			
			
		WHERE id = '".Decodear3($arreglo_post["ide"])."'
	
	";
	
	
	$database->setquery($sql);
	$database->query();
}





function TotalInscripciones(){
	
	
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select 
			tbl_inscripcion_curso.*, 
			tbl_curso.nombre as nombre_curso, 
			tbl_curso.cantidad_maxima_participantes, 
			tbl_curso_clasificacion.clasificacion as nombre_clasificacion, 
			tbl_curso_tipo.nombre as nombre_tipo, 
			(select sum(viatico) as suma_viatico from tbl_inscripcion_usuarios where tbl_inscripcion_usuarios.id_inscripcion=tbl_inscripcion_curso.id) as suma_total_viaticos,
			(select sum(movilizacion) as suma_movilizacion from tbl_inscripcion_usuarios where tbl_inscripcion_usuarios.id_inscripcion=tbl_inscripcion_curso.id) as suma_total_movilizacion, 

	(select count(*) from tbl_inscripcion_usuarios where tbl_inscripcion_usuarios.id_inscripcion=tbl_inscripcion_curso.id) as total_inscritos
	
	from tbl_inscripcion_curso

		inner join tbl_curso
		on tbl_curso.id=tbl_inscripcion_curso.id_curso
		
		inner join tbl_curso_clasificacion
		on tbl_curso_clasificacion.id=tbl_curso.clasificacion
		
		inner join tbl_curso_tipo
		on tbl_curso_tipo.id=tbl_curso.tipo
		
		order by tbl_inscripcion_curso.id DESC
		
		";
		$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
	
	
	
	
	
}





function TotalInscripcionesPorEmpresaHolding($id_inscripcion, $id_empresa_holding){
	
	
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select 
			tbl_inscripcion_curso.*, 
			
			tbl_curso.nombre as nombre_curso, 
			
			tbl_curso.cantidad_maxima_participantes, 
			
			tbl_curso_clasificacion.clasificacion as nombre_clasificacion, 
			
			tbl_curso_tipo.nombre as nombre_tipo, 
			
			
			(select sum(viatico) as suma_viatico from tbl_inscripcion_usuarios where tbl_inscripcion_usuarios.id_inscripcion=tbl_inscripcion_curso.id and tbl_inscripcion_usuarios.empresa='$id_empresa_holding') as suma_total_viaticos,
			
			
			(select sum(movilizacion) as suma_movilizacion from tbl_inscripcion_usuarios where tbl_inscripcion_usuarios.id_inscripcion=tbl_inscripcion_curso.id and tbl_inscripcion_usuarios.empresa='$id_empresa_holding') as suma_total_movilizacion,
			 

	(select count(*) from tbl_inscripcion_usuarios where tbl_inscripcion_usuarios.id_inscripcion=tbl_inscripcion_curso.id and tbl_inscripcion_usuarios.empresa='$id_empresa_holding') as total_inscritos
	
	from tbl_inscripcion_curso

		inner join tbl_curso
		on tbl_curso.id=tbl_inscripcion_curso.id_curso
		
		inner join tbl_curso_clasificacion
		on tbl_curso_clasificacion.id=tbl_curso.clasificacion
		
		inner join tbl_curso_tipo
		on tbl_curso_tipo.id=tbl_curso.tipo
		
		where tbl_inscripcion_curso.id='$id_inscripcion'
		
		order by tbl_inscripcion_curso.id DESC
		
		";
		
		
		$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
	
	
	
	
	
}



function InsertaInscripcion($arreglo_post){
	global $c_host,$c_user,$c_pass,$c_db;	
	$rut_completo=$rut."-".$dv;
	$nombre_completo=$nombre." ".$apaterno." ".$amaterno;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	//TRATO LAS FECHAS
	//primero separo con explode
    $fechas= explode('-', trim($_POST["reservation"]));
	$fecha_inicio=$fechas[0];
	$fecha_inicio = str_replace("/","-",$fecha_inicio);

	$fecha_termino=$fechas[1];
	$fecha_termino = str_replace("/","-",$fecha_termino);
	
	

	
	
	
	$sql = "INSERT INTO tbl_inscripcion_curso
							(fecha_inicio, fecha_termino, numero_dias, abierto_cerrado, valor_curso, valor_hora_curso, direccion, comuna, ciudad, comentarios, id_curso, 
							
							lunes_d_am, lunes_h_am, lunes_d_pm, lunes_h_pm, 
							martes_d_am, martes_h_am, martes_d_pm, martes_h_pm, 
							miercoles_d_am, miercoles_h_am, miercoles_d_pm, miercoles_h_pm, 
							jueves_d_am, jueves_h_am, jueves_d_pm, jueves_h_pm, 
							viernes_d_am, viernes_h_am, viernes_d_pm, viernes_h_pm
							
							)VALUES

							('".trim($fecha_inicio)."', '".trim($fecha_termino)."', '".$arreglo_post['numero_dias']."', '".$arreglo_post['abiertocerrado']."', '".$arreglo_post['valor_curso']."', '".$arreglo_post['valor_hora_curso']."', '".$arreglo_post['direccion']."', '".$arreglo_post['comuna']."', '".$arreglo_post['ciudad']."', '".$arreglo_post['comentario']."', '".Decodear3($arreglo_post['idc'])."', 
							'".$arreglo_post['HOR_LUNES_D_AM']."', '".$arreglo_post['HOR_LUNES_H_AM']."', '".$arreglo_post['HOR_LUNES_D_PM']."', '".$arreglo_post['HOR_LUNES_H_PM']."',
							'".$arreglo_post['HOR_MARTES_D_AM']."', '".$arreglo_post['HOR_MARTES_H_AM']."', '".$arreglo_post['HOR_MARTES_D_PM']."', '".$arreglo_post['HOR_MARTES_H_PM']."',
							'".$arreglo_post['HOR_MIERCOLES_D_AM']."', '".$arreglo_post['HOR_MIERCOLES_H_AM']."', '".$arreglo_post['HOR_MIERCOLES_D_PM']."', '".$arreglo_post['HOR_MIERCOLES_H_PM']."',
							'".$arreglo_post['HOR_JUEVES_D_AM']."', '".$arreglo_post['HOR_JUEVES_H_AM']."', '".$arreglo_post['HOR_JUEVES_D_PM']."', '".$arreglo_post['HOR_JUEVES_H_PM']."',
							'".$arreglo_post['HOR_VIERNES_D_AM']."', '".$arreglo_post['HOR_VIERNES_H_AM']."', '".$arreglo_post['HOR_VIERNES_D_PM']."', '".$arreglo_post['HOR_VIERNES_H_PM']."'
	
			);";
	$database->setquery($sql);
	$database->query();	

}


function TotalUsuariosPorInscripcion2($id_inscripcion){
	
	
	
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select 
			tbl_inscripcion_usuarios.*, 
			tbl_usuario.nombre, 
			tbl_usuario.dv, 
			tbl_usuario.apaterno, 
			tbl_usuario.amaterno, 
			tbl_usuario.nombre_completo, 
			tbl_usuario.fecha_nacimiento,
			tbl_usuario.genero,
			tbl_usuario.codigo_escolaridad,
			tbl_usuario.codigo_nivel,
			tbl_usuario.tipo_contrato,
			tbl_usuario.tramo_sence,
			tbl_usuario.email,
			tbl_usuario.comuna as comuna_usuario,
			tbl_usuario.nacionalidad,
			tbl_usuario.direccion_particular, 
			tbl_curso.numero_identificador,
			tbl_curso.nombre as nombre_curso, 
			tbl_curso.numero_horas, 
			tbl_curso.valor_hora, 
			tbl_curso.cbc, 
			tbl_empresa_holding.empresa as nombre_empresa_holding, 
			tbl_otec.nombre as nombre_otec,
			tbl_otec.direccion as direccion_otec,
			tbl_otec.telefono as fono_otec, 
			tbl_inscripcion_curso.ciudad as ciudad_inscripcion, 
			tbl_inscripcion_curso.fecha_inicio as fecha_inicio_ins, 
			tbl_inscripcion_curso.fecha_termino as fecha_termino_ins,
			tbl_inscripcion_curso.comentarios as comentario_inscripcion_curso,
			tbl_inscripcion_curso.direccion as direccion_inscripcion
			
			from tbl_inscripcion_usuarios 
			
			left join tbl_usuario
			on tbl_usuario.rut=tbl_inscripcion_usuarios.rut
			
			left join tbl_empresa_holding
			on tbl_empresa_holding.rut=tbl_inscripcion_usuarios.empresa
			
			inner join tbl_inscripcion_curso
			on tbl_inscripcion_curso.id=tbl_inscripcion_usuarios.id_inscripcion
			
			inner join tbl_curso
			on tbl_inscripcion_curso.id_curso=tbl_curso.id
			
			inner join tbl_otec
			on tbl_otec.rut=tbl_curso.rut_otec
			
			where id_inscripcion='$id_inscripcion'	";

		$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
	
	
	
	
}



function TotalUsuariosPorInscripcion($id_inscripcion, $id_empresa_holding){
	
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	if($id_empresa_holding){
			$sql = "select 
			tbl_inscripcion_usuarios.*, 
			tbl_usuario.nombre_completo, 
			tbl_usuario.nombre, 
			tbl_usuario.apaterno, 
			tbl_usuario.amaterno, 
			tbl_usuario.rut_completo, tbl_usuario.rut as rut_sin_dv, tbl_usuario.email, tbl_empresa_holding.empresa as nombre_empresa_holding
			
			from tbl_inscripcion_usuarios 
			
			left join tbl_usuario
			on tbl_usuario.rut=tbl_inscripcion_usuarios.rut
			
			left join tbl_empresa_holding
			on tbl_empresa_holding.rut=tbl_inscripcion_usuarios.empresa
			
			where id_inscripcion='$id_inscripcion' and empresa='$id_empresa_holding' 	";
	}else{
			$sql = "select 
			tbl_inscripcion_usuarios.*, 
			tbl_usuario.nombre_completo, 
			tbl_usuario.nombre, 
			tbl_usuario.apaterno, 
			tbl_usuario.amaterno, 
			tbl_usuario.rut_completo, tbl_usuario.rut as rut_sin_dv, tbl_usuario.email, tbl_empresa_holding.empresa as nombre_empresa_holding
			
			from tbl_inscripcion_usuarios 
			
			left join tbl_usuario
			on tbl_usuario.rut=tbl_inscripcion_usuarios.rut
			
			left join tbl_empresa_holding
			on tbl_empresa_holding.rut=tbl_inscripcion_usuarios.empresa
			
			where id_inscripcion='$id_inscripcion' 	";
	}
	


			
		$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
	
}




function TotalUsuariosPorInscripcionCerrados2($id_inscripcion, $id_empresa_holding){
	
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	
	if($id_empresa_holding){
		$sql = "
	
	SELECT
	tbl_inscripcion_cierre.*, 
	tbl_usuario.nombre_completo,
	tbl_usuario.nombre,
	tbl_usuario.apaterno,
	tbl_usuario.amaterno,
	tbl_usuario.rut_completo,
	tbl_usuario.rut as rut_sin_dv,
	tbl_usuario.tramo_sence,
	tbl_usuario.email,
	tbl_inscripcion_excepciones.descripcion AS descripcion_caso_especial,
	tbl_inscripcion_excepciones.porcentaje AS cobertura_sence_caso_especial
FROM
	tbl_inscripcion_usuarios
LEFT JOIN tbl_inscripcion_cierre ON tbl_inscripcion_cierre.rut = tbl_inscripcion_usuarios.rut
AND tbl_inscripcion_cierre.id_inscripcion = tbl_inscripcion_usuarios.id_inscripcion

LEFT JOIN tbl_usuario ON tbl_usuario.rut = tbl_inscripcion_usuarios.rut

LEFT JOIN tbl_inscripcion_excepciones ON tbl_inscripcion_excepciones.id = tbl_inscripcion_cierre.caso_especial

WHERE
	tbl_inscripcion_usuarios.id_inscripcion = '$id_inscripcion' and tbl_inscripcion_usuarios.empresa='$id_empresa_holding'
	
	
	";
		
	}else{
		
	$sql = "
	
	SELECT
	tbl_inscripcion_cierre.*, 
	tbl_usuario.nombre_completo,
	tbl_usuario.nombre,
	tbl_usuario.apaterno,
	tbl_usuario.amaterno,
	tbl_usuario.rut_completo,
	tbl_usuario.rut as rut_sin_dv,
	tbl_usuario.tramo_sence,
	tbl_usuario.email,
	tbl_inscripcion_excepciones.descripcion AS descripcion_caso_especial,
	tbl_inscripcion_excepciones.porcentaje AS cobertura_sence_caso_especial
FROM
	tbl_inscripcion_usuarios
LEFT JOIN tbl_inscripcion_cierre ON tbl_inscripcion_cierre.rut = tbl_inscripcion_usuarios.rut
AND tbl_inscripcion_cierre.id_inscripcion = tbl_inscripcion_usuarios.id_inscripcion

LEFT JOIN tbl_usuario ON tbl_usuario.rut = tbl_inscripcion_usuarios.rut

LEFT JOIN tbl_inscripcion_excepciones ON tbl_inscripcion_excepciones.id = tbl_inscripcion_cierre.caso_especial

WHERE
	tbl_inscripcion_usuarios.id_inscripcion = '$id_inscripcion'
	
	
	";	
	}
	


		$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
	
}




function TotalUsuariosPorInscripcionCerrados2SoloCasosEspeciales($id_inscripcion){
	
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "
	
	SELECT
	tbl_inscripcion_cierre.*, 
	tbl_usuario.nombre_completo,
	tbl_usuario.nombre,
	tbl_usuario.apaterno,
	tbl_usuario.amaterno,			
	tbl_usuario.rut_completo,
	tbl_usuario.rut as rut_sin_dv,
	tbl_usuario.tramo_sence,
	tbl_usuario.email,
	tbl_inscripcion_excepciones.descripcion AS descripcion_caso_especial,
	tbl_inscripcion_excepciones.porcentaje AS cobertura_sence_caso_especial
FROM
	tbl_inscripcion_usuarios
LEFT JOIN tbl_inscripcion_cierre ON tbl_inscripcion_cierre.rut = tbl_inscripcion_usuarios.rut
AND tbl_inscripcion_cierre.id_inscripcion = tbl_inscripcion_usuarios.id_inscripcion

LEFT JOIN tbl_usuario ON tbl_usuario.rut = tbl_inscripcion_usuarios.rut

LEFT JOIN tbl_inscripcion_excepciones ON tbl_inscripcion_excepciones.id = tbl_inscripcion_cierre.caso_especial

WHERE
	tbl_inscripcion_usuarios.id_inscripcion = '$id_inscripcion' and (tbl_inscripcion_cierre.porcentaje_asistencia='0' or tbl_inscripcion_cierre.caso_especial <>'')
	
	
	";


		$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
	
}




function TotalUsuariosPorInscripcionCerrados($id_inscripcion){
	
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select 
			tbl_inscripcion_cierre.*, 
			tbl_usuario.nombre_completo, 
			tbl_usuario.rut_completo, 
			tbl_usuario.nombre, 
			tbl_usuario.apaterno, 
			tbl_usuario.amaterno, 						
			tbl_usuario.tramo_sence, 
			tbl_usuario.email, 
			tbl_inscripcion_excepciones.descripcion as descripcion_caso_especial, 
			tbl_inscripcion_excepciones.porcentaje as cobertura_sence_caso_especial
			
			
			from tbl_inscripcion_cierre 
			
			left join tbl_usuario
			on tbl_usuario.rut=tbl_inscripcion_cierre.rut
			
			left join tbl_inscripcion_excepciones
			on tbl_inscripcion_excepciones.id=tbl_inscripcion_cierre.caso_especial
			
			
			
			where id_inscripcion='$id_inscripcion'	";

		$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
	
}




function DatosInscripcion2DadoIDCurso($id_curso){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "
	
			select 
				tbl_inscripcion_curso.*, 
				tbl_curso.numero_horas, 
				tbl_curso.valor_hora_sence, 
				(select count(*) as total from tbl_inscripcion_usuarios where tbl_inscripcion_usuarios.id_inscripcion = tbl_inscripcion_curso.id) as num_participantes, 
				(select sum(viatico) as suma_viatico from tbl_inscripcion_usuarios where tbl_inscripcion_usuarios.id_inscripcion = tbl_inscripcion_curso.id) as suma_viatico,
				(select sum(movilizacion) as suma_movilizacion from tbl_inscripcion_usuarios where tbl_inscripcion_usuarios.id_inscripcion = tbl_inscripcion_curso.id) as suma_movilizacion
				
			from tbl_inscripcion_curso 
			inner join tbl_curso
			on tbl_curso.id=tbl_inscripcion_curso.id_curso
			where tbl_inscripcion_curso.id_curso='$id_curso'
			
			";

		$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}




function DatosInscripcion2($id_inscripcion){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "
	
			select 
			tbl_inscripcion_curso.*, 
			tbl_curso.numero_horas, 
			tbl_curso.fecha_inicio as fecha_inicio_curso, 
			tbl_curso.fecha_finalizacion as fecha_termino_curso, 
			tbl_curso.valor_hora_sence
			
			from tbl_inscripcion_curso 
			inner join tbl_curso
			on tbl_curso.id=tbl_inscripcion_curso.id_curso
			where tbl_inscripcion_curso.id='$id_inscripcion'
			
			";
		$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}




function DatosInscripcionConMasInfo($id_inscripcion){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "
	
			select 
			tbl_inscripcion_curso.*, 
			tbl_curso.numero_horas, 
			tbl_curso.valor_hora as valor_h, 
			tbl_curso.objetivo_curso,
			tbl_curso.nombre as nombre_curso,
			tbl_curso.valor_hora_sence as valor_hs, 
			tbl_curso.fecha_inicio as fecha_inicio_curso, 
			tbl_curso.fecha_finalizacion as fecha_termino_curso, 
			tbl_curso.valor_hora_sence, 
			(select count(*) from tbl_inscripcion_usuarios where tbl_inscripcion_usuarios.id_inscripcion=tbl_inscripcion_curso.id) as total_inscritos
			
			from tbl_inscripcion_curso 
			inner join tbl_curso
			on tbl_curso.id=tbl_inscripcion_curso.id_curso
			where tbl_inscripcion_curso.id='$id_inscripcion'
			
			";
		$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}



function DatosInscripcion2121($id_inscripcion){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "
	
			select 
			tbl_inscripcion_curso.*, tbl_curso.numero_horas, tbl_curso.valor_hora_sence
			from tbl_inscripcion_curso 
			inner join tbl_curso
			on tbl_curso.id=tbl_inscripcion_curso.id_curso
			where tbl_inscripcion_curso.id='$id_inscripcion'
			
			";
		$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}


function DatosInscripcion($id_inscripcion){
	
	
	
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select * from tbl_inscripcion_curso where id='$id_inscripcion'	";

		$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
	
	
	
	
}


function ActualizaUsuario(
				$rut, $dv, $nombre, $apaterno, $amaterno, $fecha_nacimiento, $genero, 
				$email, $nacionalidad, $direccion_particular, $codigo_escolaridad, $codigo_nivel, 
				$gerencia, $cargo, $unidad_negocio, $centro_costo, $empresa_holding, 
				$fecha_antiguedad, $tipo_contrato, $tramo_sence, $perfil_evaluacion)
{
	global $c_host,$c_user,$c_pass,$c_db;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "
	
	UPDATE tbl_usuario 
		SET nombre= '$nombre', 
		apaterno= '$apaterno', 
		amaterno= '$amaterno', 
		nombre_completo= '$nombre_completo', 
		cargo= '$cargo', 
		fecha_nacimiento= '$fecha_nacimiento', 
		genero= '$genero', 
		email= '$email', 
		nacionalidad= '$nacionalidad', 
		direccion_particular= '$direccion_particular', 
		codigo_escolaridad= '$codigo_escolaridad', 
		codigo_nivel= '$codigo_nivel', 
		gerencia= '$gerencia', 
		unidad_negocio= '$unidad_negocio', 
		centro_costo= '$centro_costo', 
		empresa_holding= '$empresa_holding', 
		fecha_antiguedad= '$fecha_antiguedad', 
		tipo_contrato= '$tipo_contrato', 
		tramo_sence= '$tramo_sence', 
		perfil_evaluacion= '$perfil_evaluacion'
	
		
			
			
		WHERE rut = '$rut'
	
	";
	
	
	$database->setquery($sql);
	$database->query();
}




function InsertaUsuario(
				$rut, $dv, $nombre, $apaterno, $amaterno, $fecha_nacimiento, $genero, 
				$email, $nacionalidad, $direccion_particular, $codigo_escolaridad, $codigo_nivel, 
				$gerencia, $cargo, $unidad_negocio, $centro_costo, $empresa_holding, 
				$fecha_antiguedad, $tipo_contrato, $tramo_sence, $perfil_evaluacion, $id_empresa){
	global $c_host,$c_user,$c_pass,$c_db;	
	$rut_completo=$rut."-".$dv;
	$nombre_completo=$nombre." ".$apaterno." ".$amaterno;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	$sql = "INSERT INTO tbl_usuario
							(rut, dv, rut_completo, nombre, apaterno, amaterno, 
							nombre_completo, cargo, fecha_nacimiento, genero, email, 
							nacionalidad, direccion_particular, codigo_escolaridad, 
							codigo_nivel, gerencia,  unidad_negocio, 
							centro_costo, empresa_holding, fecha_antiguedad, 
							tipo_contrato, tramo_sence, perfil_evaluacion, id_empresa) ".
			
	"VALUES 				('$rut', '$dv', '$rut_completo', '$nombre', 
							'$apaterno', '$amaterno', '$nombre_completo', 
							'$cargo', '$fecha_nacimiento', '$genero', 
							'$email', '$nacionalidad','$direccion_particular', 
							'$codigo_escolaridad','$codigo_nivel', '$gerencia', 
							 '$unidad_negocio', '$centro_costo', '$empresa_holding', 
							'$fecha_antiguedad', '$tipo_contrato', '$tramo_sence', '$perfil_evaluacion', '$id_empresa'
			
			);";
	$database->setquery($sql);
	$database->query();	

}



function InsertaUsuarioTemp(
				$rut, $dv, $nombre, $apaterno, $amaterno, $fecha_nacimiento, $genero, 
				$email, $nacionalidad, $direccion_particular, $codigo_escolaridad, $codigo_nivel, 
				$gerencia, $cargo, $unidad_negocio, $centro_costo, $empresa_holding, 
				$fecha_antiguedad, $tipo_contrato, $tramo_sence, $perfil_evaluacion, $accion){
	global $c_host,$c_user,$c_pass,$c_db;	
	$rut_completo=$rut."-".$dv;
	$nombre_completo=$nombre." ".$apaterno." ".$amaterno;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	$sql = "INSERT INTO tbl_usuario_temp
							(rut, dv, rut_completo, nombre, apaterno, amaterno, 
							nombre_completo, cargo, fecha_nacimiento, genero, email, 
							nacionalidad, direccion_particular, codigo_escolaridad, 
							codigo_nivel, gerencia,  unidad_negocio, 
							centro_costo, empresa_holding, fecha_antiguedad, 
							tipo_contrato, tramo_sence, perfil_evaluacion, id_empresa, accion) ".
			
	"VALUES 				('$rut', '$dv', '$rut_completo', '$nombre', 
							'$apaterno', '$amaterno', '$nombre_completo', 
							'$cargo', '$fecha_nacimiento', '$genero', 
							'$email', '$nacionalidad','$direccion_particular', 
							'$codigo_escolaridad','$codigo_nivel', '$gerencia', 
							 '$unidad_negocio', '$centro_costo', '$empresa_holding', 
							'$fecha_antiguedad', '$tipo_contrato', '$tramo_sence', '$perfil_evaluacion', '19', '$accion'
			
			);";
			
	$database->setquery($sql);
	$database->query();	

}




function TodosLosCursosTemp(){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "SELECT * from tbl_curso_temp ";
		$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}

function CancelaProcesamientoPrevioUsuarios(){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "truncate tbl_usuario_temp;

		";
		$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
}


function CancelaProcesamientoPrevio(){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "truncate tbl_curso_temp;

		";
		$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
}


function TotalUsuariosTemp(){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "SELECT
	tbl_usuario_temp.*, tbl_empresa_holding.empresa  as nombre_empresa_holding
FROM
	tbl_usuario_temp
	inner join tbl_empresa_holding
	on tbl_empresa_holding.rut=tbl_usuario_temp.empresa_holding
		";
		$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}


function TotalCursosTemp(){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "SELECT
	tbl_curso_temp.*, tbl_modalidad_curso.modalidad as nombre_modalidad, tbl_curso_clasificacion.clasificacion as nombre_clasificacion
FROM
	tbl_curso_temp
INNER JOIN tbl_modalidad_curso ON tbl_curso_temp.modalidad = tbl_modalidad_curso.id
left join tbl_curso_clasificacion
on tbl_curso_clasificacion.id=tbl_curso_temp.clasificacion
 
order by tbl_curso_temp.id desc 

		";
		$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}


function InsertaCursoTemp($nombre_curso, $descripcion_curso, $modalidad, $tipo_curso, $prerequisito_curso, $archivo, $objetivo_curso, $sence, $numero_horas, $cantidad_maxima_participantes, $rut_otec, $clasificacion_curso, $cbc,  $cod_identificador, $accion, $id_curso, $valor_hora, $valor_hora_sence){
	global $c_host,$c_user,$c_pass,$c_db;	

	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	$sql = "INSERT INTO tbl_curso_temp(nombre, descripcion, modalidad, tipo, prerequisito, imagen, objetivo_curso, sence, numero_horas,cantidad_maxima_participantes, rut_otec, clasificacion, cbc, numero_identificador,  accion, id_curso, valor_hora, valor_hora_sence) ".
					   "VALUES ('$nombre_curso', '$descripcion_curso', '$modalidad', '$tipo_curso', '$prerequisito_curso', '$archivo', '$objetivo_curso', '$sence', '$numero_horas', '$cantidad_maxima_participantes', '$rut_otec', '$clasificacion_curso', '$cbc', '$cod_identificador',  '$accion', $id_curso, '$valor_hora', '$valor_hora_sence');";
	
	$database->setquery($sql);
	$database->query();	

}


function VerificaCodIdentificador($cod){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "SELECT * from tbl_curso where numero_identificador='$cod'
		";
		$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}


function VerificaCodSence($codSence){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "SELECT * from tbl_curso where cod_sence='$codSence'
		";
		$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}


function SelectParaPaginacion($tabla){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "SELECT * from $tabla where id_empresa=19
		";
		$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}

function SelectParaPaginacionUsuario($tabla, $id_empresa){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "SELECT * from $tabla where id_empresa=$id_empresa
		";
		$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}

function TotalCursosParaPaginacion($arreglo_post, $pagina){
	global $c_host,$c_user,$c_pass,$c_db;
	
	if(!$pagina){
		$pagina=0;
	}else if($pagina==1){
		$pagina=0;
	}else{
		$pagina=($pagina-1)*10;
	}
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	$filtro="";
	if($arreglo_post["filtro_clasificacion"]<>0){
		$filtro.=" and tbl_curso.clasificacion='".$arreglo_post["filtro_clasificacion"]."'";
	}
	
	if($arreglo_post["filtro_tipo_curso"]<>0){
		$filtro.=" and tbl_curso.tipo='".$arreglo_post["filtro_tipo_curso"]."'";
	}
	
	if($arreglo_post["filtro_sence"]<>0){
		$filtro.=" and tbl_curso.sence='".$arreglo_post["filtro_sence"]."'";
	}
	
	if($arreglo_post["filtro_modalidad"]<>0){
		$filtro.=" and tbl_curso.modalidad='".$arreglo_post["filtro_modalidad"]."'";
	}
	
	
	if($arreglo_post["filtro_otec"]<>0){
		$filtro.=" and tbl_curso.rut_otec='".$arreglo_post["filtro_otec"]."'";
	}
	
	$sql = "SELECT
	tbl_curso.*, tbl_modalidad_curso.modalidad as nombre_modalidad, tbl_curso_clasificacion.clasificacion as nombre_clasificacion, tbl_otec.nombre as nombre_otec, 
	tbl_curso_tipo.nombre as nombre_tipo_curso
FROM
	tbl_curso
INNER JOIN tbl_modalidad_curso ON tbl_curso.modalidad = tbl_modalidad_curso.id
left join tbl_curso_clasificacion
on tbl_curso_clasificacion.id=tbl_curso.clasificacion

left join tbl_otec
on tbl_otec.rut=tbl_curso.rut_otec

left join tbl_curso_tipo
on tbl_curso_tipo.id=tbl_curso.tipo

where 1 $filtro and tbl_curso.activo=0 and id_empresa=19
order by tbl_curso.id desc limit $pagina, 10

		";

		$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}


function DatosOtecDadoRut($rut_otec){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "SELECT * from tbl_otec where rut='$rut_otec'
		";
		
		$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}




function TotalOtecs(){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "SELECT* from tbl_otec
		";
		$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}


function ActualizaActivoInactivoCurso($id)
{
	global $c_host,$c_user,$c_pass,$c_db;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "UPDATE tbl_curso SET activo= '1' WHERE id = '$id'";
	$database->setquery($sql);
	$database->query();
}



function DatosporTBLCursos($curso){
	global $c_host,$c_user,$c_pass,$c_db;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);	
	$sql = "
		select * from tbl_dnc_base_cursos_consolidado
where curso='$curso'";
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	return $cod;
}

function DatosporCursos($curso){
	global $c_host,$c_user,$c_pass,$c_db;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);	
	$sql = "
		select * from tbl_dnc_base_consolidado
where curso='$curso' group by conducta_esperada";
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	return $cod;
}


function DatosporpRankingRIORIZACIONCursos($gerencia){
	global $c_host,$c_user,$c_pass,$c_db;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);	
	$sql = "select count(curso) as cuenta, curso from tbl_dnc_base_consolidado where gerencia='$gerencia' and priorizacioncursos>0 group by curso order by count(curso) DESC";


	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	return $cod;
}


function DatosporpRIORIZACIONCursos($curso){
	global $c_host,$c_user,$c_pass,$c_db;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);	
	$sql = "
		select * from tbl_dnc_base_consolidado
where curso='$curso' and priorizacioncursos>0 group by cargo order by gerencia, nivel, cargo";
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	return $cod;
}

function DatosconsolidadoDncCursos() {
	global $c_host,$c_user,$c_pass,$c_db;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);	
	$sql = "select * from tbl_dnc_base_consolidado GROUP BY curso";
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	return $cod;	
}


function DatosconsolidadoListaGerencia() {
	global $c_host,$c_user,$c_pass,$c_db;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);	
	$sql = "select h.* from tbl_dnc_base_consolidado h group by gerencia";
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	return $cod;	
}

function DatosconsolidadoListaCARGOS() {
	global $c_host,$c_user,$c_pass,$c_db;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);	
$sql = "select h.* from tbl_dnc_base_consolidado h where gerencia<>'MANTENIMIENTO' group by cargo order by gerencia ASC, nivel ASC ";
//	echo "Sql $sql";
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	return $cod; 	
}




function DatosconsolidadoListaCursos() {
	global $c_host,$c_user,$c_pass,$c_db;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);	
$sql = "select h.* from tbl_dnc_base_consolidado h where priorizacioncursos>0 group by curso order by curso ASC";

// 	echo "Sql $sql";
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	return $cod;	
}




function DatosconsolidadoBuscaPrioridadListaCARGOS($codigo,$curso){

	global $c_host,$c_user,$c_pass,$c_db;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);	

$sql = "select h.* from tbl_dnc_base_consolidado h where curso='$curso' and codigo='$codigo' and priorizacioncursos>0";
	//echo "<br />$sql";

	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	

if($codigo=='62' and $curso=='Liderazgo Adaptativo'){
//	echo "<br />$sql";
// print_r($cod);

}
	
	return $cod;	



}
function DatosconsolidadoDncCursosG() {
	global $c_host,$c_user,$c_pass,$c_db;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);	
	$sql = "select h.*, (SELECT COUNT(*) FROM tbl_dnc_base_consolidado where curso=h.curso and priorizacioncursos>0) as cuenta from tbl_dnc_base_consolidado h where priorizacioncursos>0 group by curso 
order by (SELECT COUNT(*) FROM tbl_dnc_base_consolidado where curso=h.curso and priorizacioncursos>0) DESC";
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	return $cod;	
}



function DatosconsolidadoDncCursosT() {
	global $c_host,$c_user,$c_pass,$c_db;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);	
	$sql = "select * from tbl_dnc_base_consolidado where gt='T' GROUP BY curso";
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	return $cod;	
}



function TematicasconsolidadoDadoCursosCargo($curso) {
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);	
	$sql = "
		select tematica from tbl_dnc_base_cursos_consolidado
where  curso='$curso'
GROUP BY tematica

			";

	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;	
	
}
function PerfilAdmin($id){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select * from tbl_admin_perfiles where id='$id'

			";
			
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}

function DatosconsolidadoCursosDadoAgrupados($id_cargo){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sqlDistinctDimension = "
		select * from tbl_dnc_base_consolidado
where id_cargo='$id_cargo' 
GROUP BY dimension
order by curso

			";
			
			
	
	$sql = "
		select * from tbl_dnc_base_consolidado
where id_cargo='$id_cargo'  
GROUP BY curso
order by curso

			";
			
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}


function DatosconsolidadoCursosG(){
	
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "
		select * from tbl_dnc_base_consolidado
where gt='G' 
GROUP BY curso

			";
			
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;	
}

function DatosconsolidadoCursosT(){
	
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "
		select * from tbl_dnc_base_consolidado
where gt='T' 
GROUP BY curso

			";
			
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;	
}


function DatosconsolidadoCursosDadoAgrupadosP($id_cargo){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select * from tbl_dnc_base_consolidado where id_cargo='$id_cargo' 
	 and priorizacioncursos is not NULL GROUP BY curso order by priorizacioncursos ASC";
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}


function DatosconsolidadoCursosDadoAgrupadosG($id_cargo){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "
		select * from tbl_dnc_base_consolidado
where id_cargo='$id_cargo' and gt='G' 
GROUP BY curso
order by priorizacion

			";
			
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}


function DatosconsolidadoCursosDadoAgrupadosT($id_cargo){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sqlDistinctDimension = "
		select * from tbl_dnc_base_consolidado
where id_cargo='$id_cargo' and gt='T'
GROUP BY curso
order by priorizacion

			";
			
			
	
	$sql = "
		select * from tbl_dnc_base_consolidado
where id_cargo='$id_cargo' and gt='T'
GROUP BY curso
order by priorizacion

			";
			
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}


function DatosconsolidadoCursosDadoDimension($dimension){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	
	$sql = "
		select * from tbl_dnc_relacion_dimension_curso where dimension='$dimension'
			";
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}


function DatosconsolidadoConductasDadoIdCargo($id_cargo){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	
	$sql = "
		select * from tbl_dnc_base_consolidado where id_cargo='$id_cargo' group by conducta order by priorizacion
			";
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}


function DatosconsolidadoDnc($tipo, $nivel, $gerencia){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	$sq="";
	if($nivel<>"-"){
		$sq.=" and nivel='$nivel'";
	}
	if($gerencia<>"-"){
		$sq.=" and gerencia='$gerencia'";
	}
	$sql = "
		select * from 
			tbl_dnc_base_consolidado 
			where (gt='G' or gt='T') ".$sq." 
			
			
			group by cargo, objetivo_cargo
			order by gerencia, nivel
			";
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}


function ObtenerTodosLosCursosDinamico($id_curso){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "SELECT
	tbl_curso.*, rel_nivel_curso.id_nivel AS nivel,
	tbl_programa.nombre AS NombrePrograma, 
	(select count(*) from tbl_objeto where tbl_objeto.id_curso=tbl_curso.id) as total_objetos
FROM
	tbl_curso
INNER JOIN rel_nivel_curso ON rel_nivel_curso.id_curso = tbl_curso.id
INNER JOIN tbl_nivel ON tbl_nivel.id = rel_nivel_curso.id_nivel
INNER JOIN tbl_programa ON tbl_programa.id = tbl_nivel.id_programa

where tbl_curso.id='$id_curso'";
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}

function ObtenerTodosLosCursos($arreglo_post){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	$filtro="";
	if($arreglo_post["curso"]>0){
		$filtro.=" and tbl_curso.id='".$arreglo_post["curso"]."' ";
	}
	
	if($arreglo_post["programa"]>0){
		$filtro.=" and tbl_programa.id='".$arreglo_post["programa"]."' ";
	}
	
	
	
	
	$sql = "SELECT
	tbl_curso.*, rel_nivel_curso.id_nivel AS nivel,
	tbl_programa.nombre AS NombrePrograma, 
	(select count(*) from tbl_objeto where tbl_objeto.id_curso=tbl_curso.id) as total_objetos
FROM
	tbl_curso
INNER JOIN rel_nivel_curso ON rel_nivel_curso.id_curso = tbl_curso.id
INNER JOIN tbl_nivel ON tbl_nivel.id = rel_nivel_curso.id_nivel
INNER JOIN tbl_programa ON tbl_programa.id = tbl_nivel.id_programa where 1 $filtro "
;

;
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}


function ObtenerUltimaPregunta(){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select max(id) as ultimo_id from tbl_evaluaciones_preguntas";
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}


function VerificaObjetoFinalizadoDadoObjetoRut($id_objeto, $rut){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select nota from tbl_objetos_finalizados where rut='$rut' and id_objeto='$id_objeto'";
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}


function TotalObjetosEvaluacionesDadoCurso($id_curso){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select * from tbl_objeto where id_curso='$id_curso'";
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}







function ActualizarObjetivoDNCADMIN($objetivo, $conducta1, $conducta2, $conducta3, $id_objetivo)
{
	global $c_host,$c_user,$c_pass,$c_db;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	$sql = "update tbl_dnc_objetivos set objetivo='$objetivo', conducta1='$conducta1', conducta2='$conducta2', conducta3='$conducta3' where id='$id_objetivo';";
	$database->setquery($sql);
	$database->query();
}

function TraeObjetivosDadoCargoYJefe($jefe, $cargo){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select * from tbl_dnc_objetivos where jefe='$jefe' and cargo='$cargo'";
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}









function ListadoJefes($POST){
	global $c_host,$c_user,$c_pass,$c_db;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	$filtro="";
	if($POST["empresa"]){		$filtro.=" and negocio='".$POST["empresa"]."'";	}
	if($POST["zona"]){		$filtro.=" and zona='".$POST["zoa"]."'";	}
	if($POST["gerencia"]){		$filtro.=" and gerencia='".$POST["gerencia"]."'";	}
	if($POST["area"]){		$filtro.=" and area='".$POST["area"]."'";	}
	if($POST["local"]){		$filtro.=" and local='".$POST["local"]."'";	}
	$sql = "select distinct(jefe)
	from tbl_usuario
	where jefe is not null $filtro
	
	";
	
	
	
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	return $cod;
}

/*
Creador: chis
Motivo: Obtener noticias dado id.
FEcha 01 03 2015
*/
function DatosNoticias($id){
	global $c_host,$c_user,$c_pass,$c_db;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select * from tbl_noticias where id=".$id;
	//echo $sql;
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	return $cod;
}


function InsertaNoticia($titulo, $contenido, $tag, $categoria, $perfil, $fecha_creacion, $datos_subida, $estado){
	global $c_host,$c_user,$c_pass,$c_db;	

	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	$sql = "INSERT INTO tbl_noticias(titulo, contenido, tag, categoria, perfil, fecha_creacion, portada, estado) ".
					   "VALUES ('$titulo', '$contenido', '$tag', '$categoria', '$perfil', '$fecha_creacion', '$datos_subida', '$estado');";

	$database->setquery($sql);
	$database->query();	

}
function TotalNoticias(){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select * from tbl_noticias";
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}

function TodosCategorias(){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select * from tbl_noticias_categorias";
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}

function TodosEstados(){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select * from tbl_noticias_estados";
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}

function UnaCategoria($id){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select categoria from tbl_noticias_categorias where id ='$id'";

	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}

function TodosLosUsuariosParaReporte($arreglo_post){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$filtro="";
	if($arreglo_post["malla"]>0) $filtro=" and rel_malla_programa.id_malla='".$arreglo_post["malla"]."' ";
	if($arreglo_post["curso"]>0) $filtro.=" and tbl_curso.id='".$arreglo_post["curso"]."' ";
	if($arreglo_post["gerencia"]) $filtro.=" and tbl_usuario.gerencia='".$arreglo_post["gerencia"]."' ";
	if($arreglo_post["un"]) $filtro.=" and tbl_usuario.unidad_negocio='".$arreglo_post["un"]."' ";
	if($arreglo_post["area"]) $filtro.=" and tbl_usuario.area='".$arreglo_post["area"]."' ";
	
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "SELECT
	tbl_usuario.rut,
	tbl_usuario.rut_completo,
	tbl_usuario.nombre_completo,
	tbl_usuario.gerencia,
	tbl_usuario.email,
	tbl_usuario.telefono,
	tbl_usuario.cargo as cargo,
	tbl_usuario.id_cargo as id_cargo,
	rel_malla.id_malla as id_malla,
	tbl_programa.nombre as nombre_programa,
	tbl_nivel.nombre as nombre_nivel,
	tbl_curso.nombre as nombre_curso,
	tbl_curso.id as id_curso
FROM
	tbl_usuario
INNER JOIN rel_malla ON rel_malla.valor = tbl_usuario.id_cargo
INNER JOIN rel_malla_programa ON rel_malla_programa.id_malla = rel_malla.id_malla
INNER JOIN tbl_programa ON tbl_programa.id = rel_malla_programa.id_programa
INNER JOIN tbl_nivel ON tbl_nivel.id_programa = tbl_programa.id
INNER JOIN rel_nivel_curso ON rel_nivel_curso.id_nivel = tbl_nivel.id
INNER JOIN tbl_curso ON tbl_curso.id = rel_nivel_curso.id_curso
where 1 $filtro";
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}



function TotalUsuarios($arreglo_post, $id_empresa, $pagina, $usuarios_por_pagina){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	
	if(!$pagina){
		$pagina=0;
	}else if($pagina==1){
		$pagina=0;
	}else{
		$pagina=($pagina-1)*10;
	}
	
	
	
	$filtro="";
	if($arreglo_post["gerencia"]) $filtro=" and tbl_usuario.gerencia='".$arreglo_post["gerencia"]."'";
	if($arreglo_post["un"]) $filtro.=" and tbl_usuario.unidad_negocio='".$arreglo_post["un"]."'";
	if($arreglo_post["area"]) $filtro.=" and tbl_usuario.area='".$arreglo_post["area"]."'";
	
	$sql = "
		select tbl_usuario.*, tbl_empresa.nombre_empresa, tbl_empresa_holding.empresa as nombre_empresa_holding
			from tbl_usuario
inner join tbl_empresa
on tbl_empresa.id=tbl_usuario.id_empresa
left join tbl_empresa_holding
on tbl_empresa_holding.rut=tbl_usuario.empresa_holding

where tbl_usuario.id_empresa='$id_empresa' $filtro order by apaterno asc limit $pagina, $usuarios_por_pagina
			";
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}


function DatosTablaUsuarioTodosSK(){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select * from tbl_usuario where id_empresa='19' order by apaterno ";
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}


function DatosTablaUsuario($rut){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select * from tbl_usuario where rut='$rut'";
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}

function TodosPerfiles(){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select DISTINCT(cargo) from tbl_usuario";
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}

function ActualizaNoticiaSinImagen($id_noticia, $titulo, $contenido, $tag, $categoria, $perfil, $estado)
{
	global $c_host,$c_user,$c_pass,$c_db;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "UPDATE tbl_noticias SET titulo= '$titulo', contenido='$contenido', tag='$tag', categoria='$categoria', perfil='$perfil', estado='$estado'  WHERE id = '$id_noticia'";
	$database->setquery($sql);
	$database->query();
}

function ActualizaNoticiaConImagen($id_noticia, $titulo, $contenido, $tag, $categoria, $perfil, $estado, $portada)
{
	global $c_host,$c_user,$c_pass,$c_db;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "UPDATE tbl_noticias SET titulo= '$titulo', contenido='$contenido', tag='$tag', categoria='$categoria', perfil='$perfil', estado='$estado'
	, portada='$portada'  WHERE id = '$id_noticia'";
	$database->setquery($sql);
	$database->query();
}

function BorrarNoticia($id_noticia){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "DELETE FROM tbl_noticias WHERE id='$id_noticia'";
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}

//FIN CHIS
////////////////////////
////////////////////////




/*
Creador: Daniel
Motivo: Insertar registro en tabla de objeto
Fecha: 21/01/2014
*/

function InsertaEmpresa($archivo, $nombre_empresa, $responsable_empresa, $email_empresa, $css_ruta, $css_nombre, $titulo_empresa){
	global $c_host,$c_user,$c_pass,$c_db;	

	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	$sql = "INSERT INTO tbl_empresa(nombre_empresa, responsable, correo, archivo, css_ruta, css_nombre, titulo_pagina) ".
					   "VALUES ('$nombre_empresa', '$responsable_empresa', '$email_empresa', '$archivo', '$css_ruta', '$css_nombre', '$titulo_empresa');";
	
	$database->setquery($sql);
	$database->query();	

}




/*
Creador: Daniel
Motivo: Insertar Relacion Nivel Curso
Fecha: 21/01/2014
*/

function InsertaRelacionMallaPrograma($id_malla, $id_programa){
	global $c_host,$c_user,$c_pass,$c_db;	

	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	$sql = "INSERT INTO rel_malla_programa(id_malla, id_programa) ".
					   "VALUES ('$id_malla', '$id_programa');";
	
	$database->setquery($sql);
	$database->query();	

}



/*
Creador: Daniel
Motivo: TraerRelaciones Malla programa dado malla

*/
function RelMallaPrograma($id_malla){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select rel_malla_programa.*, tbl_programa.nombre as nombre_programa
	from rel_malla_programa 
	inner join tbl_programa
	on tbl_programa.id=rel_malla_programa.id_programa
	where rel_malla_programa.id_malla='$id_malla'";
			
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}


/*
Creador: Daniel
Motivo: Listado de preguntas dado id evaluacion

*/
function ListadoDePreguntasDadaEvaluacion($id_evaluacion){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select tbl_evaluaciones_preguntas.*, tbl_evaluaciones_tipo_preguntas.tipo as tipo_pregunta
				from tbl_evaluaciones_preguntas 
				inner join tbl_evaluaciones_tipo_preguntas
				on tbl_evaluaciones_tipo_preguntas.id=tbl_evaluaciones_preguntas.tipo
				where evaluacion='$id_evaluacion'";
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}




/*
Creador: Daniel
Motivo: Inserta Pregunta por Evaluacion

*/

function InsertaPreguntaPorEvaluacion($pregunta, $id_evaluacion, $tipo, $url_archivo, $url_carpeta){
	global $c_host,$c_user,$c_pass,$c_db;	

	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	$sql = "INSERT INTO tbl_evaluaciones_preguntas(pregunta, evaluacion, tipo, url_archivo, url_carpeta) ".
					   "VALUES ('$pregunta', '$id_evaluacion', '$tipo', '$url_archivo', '$url_carpeta');";
	
	$database->setquery($sql);
	$database->query();	

}



/*
Creador: Daniel
Motivo: Total Tipos de Preguntas

*/
function ListadoTiposPreguntas(){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select * from tbl_evaluaciones_tipo_preguntas";
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}



/*
Creador: Daniel
Motivo: Insertar registro evaluacion

*/

function InsertaEvaluacion($nombre_evaluacion, $descripcion){
	global $c_host,$c_user,$c_pass,$c_db;	

	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	$sql = "INSERT INTO tbl_evaluaciones(nombre_evaluacion, descripcion) ".
					   "VALUES ('$nombre_evaluacion', '$descripcion');";
	
	$database->setquery($sql);
	$database->query();	

}



/*
Creador: Daniel
Motivo: Listado de evaluaciones

*/
function TotalEvaluaciones(){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "SELECT
	tbl_evaluaciones.*, (
		SELECT
			count(x.id) AS cnt
		FROM
			tbl_evaluaciones_preguntas x
		WHERE
			evaluacion = tbl_evaluaciones.id
	) as total_preguntas
FROM
	tbl_evaluaciones
	order by tbl_evaluaciones.id desc
	";
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}


/*
Creador: Daniel
Motivo: Insertar Relacion Nivel Curso
Fecha: 21/01/2014
*/

function InsertaRelNivelCurso($id_nivel, $id_curso){
	global $c_host,$c_user,$c_pass,$c_db;	

	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	$sql = "INSERT INTO rel_nivel_curso(id_nivel, id_curso) ".
					   "VALUES ('$id_nivel', '$id_curso');";
	
	$database->setquery($sql);
	$database->query();	

}



/*
Creador: Daniel
Motivo: Total de Cursos

*/
function TotalCursos2(){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select * from tbl_curso";
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}



/*
Creador: Daniel
Motivo: TraerRelaciones Nivel Curso dado Nivel

*/
function RelNivelCurso($id_nivel){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "
		select rel_nivel_curso.*, tbl_curso.nombre, tbl_modalidad_curso.modalidad as modalidad_curso
			from rel_nivel_curso 
			inner join tbl_curso
			on tbl_curso.id=rel_nivel_curso.id_curso
			inner join tbl_modalidad_curso
			on tbl_modalidad_curso.id=tbl_curso.modalidad
			where rel_nivel_curso.id_nivel='$id_nivel'";
			
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}



/*
Creador: Daniel
Motivo: Actulizar Objeto
Fecha: 21/01/2014
*/


function ActualizaObjeto($id_objeto, $nombre_objeto, $tipo_objeto, $descripcion_objeto, $curso)
{
	global $c_host,$c_user,$c_pass,$c_db;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "UPDATE tbl_objeto SET titulo= '$nombre_objeto', descripcion='$descripcion_objeto', tipo_objeto='$tipo_objeto', id_curso='$curso'  WHERE id = '$id_objeto'";
	$database->setquery($sql);
	$database->query();
}



/*
Creador: Daniel
Motivo: Insertar registro en tabla de objeto
Fecha: 21/01/2014
*/

function InsertaObjeto($nombre_objeto, $tipo_objeto, $descripcion_objeto, $curso, $direccion_archivo, $archivo, $extension_archivo, $ruta_sin_index, $id_evaluacion){
	global $c_host,$c_user,$c_pass,$c_db;	

	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	$sql = "INSERT INTO tbl_objeto(tipo_objeto, titulo, descripcion, id_curso, archivo, url_objeto, extension_objeto, url, id_evaluacion) ".
					   "VALUES ('$tipo_objeto', '$nombre_objeto', '$descripcion_objeto', '$curso', '$archivo', '$direccion_archivo', '$extension_archivo', '$ruta_sin_index', '$id_evaluacion');";
	
	$database->setquery($sql);
	$database->query();	

}




/*
Creador: Daniel
Motivo: Traer total de tipos de objetos
FEcha 21 01 2014
*/
function TotalTiposObjetos(){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select * from tbl_tipo_objeto";
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}








/*
Creador: Daniel
Motivo: ObtenerTotal de Objetos
FEcha 21 01 2014
*/
function TotalObjetos(){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "SELECT
	tbl_objeto.*, tbl_curso.nombre as nombre_curso, tbl_tipo_objeto.nombre as nombre_objeto
FROM
	tbl_objeto
INNER JOIN tbl_curso 
ON tbl_curso.id = tbl_objeto.id_curso
inner join tbl_tipo_objeto
on tbl_tipo_objeto.id=tbl_objeto.tipo_objeto
order by tbl_objeto.id desc
		";
		$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}




/*
Creador: Daniel
Motivo: Actulizar Curso
Fecha: 21/01/2014
*/


function ActualizaCurso($id_curso, $nombre_curso, $descripcion_curso, $modalidad, $tipo_curso, $prerequisito_curso, $objetivo_curso, $sence, $numero_horas, $cantidad_maxima_participantes, $rut_otec, $clasificacion_curso, $cbc, $valor_hora, $valor_hora_sence)
{
	global $c_host,$c_user,$c_pass,$c_db;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	if($sence=="si" && $cbc=="si"){
		$valor_hora_sence=$valor_hora_sence+(($valor_hora_sence*20)/100);
	}
	
	
	$sql = "
		UPDATE tbl_curso SET 
			nombre= '$nombre_curso', 
			descripcion='$descripcion_curso', 
			modalidad='$modalidad', 
			tipo='$tipo_curso', 
			prerequisito='$prerequisito_curso', 
			objetivo_curso='$objetivo_curso',
			sence='$sence', 
			cbc='$cbc', 
			numero_horas='$numero_horas', 
			cantidad_maxima_participantes='$cantidad_maxima_participantes', 
			valor_hora='$valor_hora', 
			rut_otec='$rut_otec', 
			clasificacion='$clasificacion_curso', 
			valor_hora_sence='$valor_hora_sence'
			
			
			WHERE id = '$id_curso'";
			
	$database->setquery($sql);
	$database->query();
}




/*
Creador: Daniel
Motivo: Insertar registro en tabla de cursos
Fecha: 21/01/2014
*/

function InsertaCurso($nombre_curso, $descripcion_curso, $modalidad, $tipo_curso, $prerequisito_curso, $archivo, $objetivo_curso, $sence, $numero_horas, $cantidad_maxima_participantes, $rut_otec, $clasificacion_curso, $cbc,  $cod_identificador, $valor_hora, $valor_hora_sence){
	
	global $c_host,$c_user,$c_pass,$c_db;	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	if($sence=="si" && $cbc=="si"){
		$valor_hora_sence=$valor_hora_sence+(($valor_hora_sence*20)/100);
	}
	$sql = "INSERT INTO tbl_curso(nombre, descripcion, modalidad, tipo, prerequisito, imagen, objetivo_curso, sence, numero_horas,cantidad_maxima_participantes, rut_otec, clasificacion, cbc, numero_identificador, valor_hora, valor_hora_sence) ".
					   "VALUES ('$nombre_curso', '$descripcion_curso', '$modalidad', '$tipo_curso', '$prerequisito_curso', '$archivo', '$objetivo_curso', '$sence', '$numero_horas', '$cantidad_maxima_participantes', '$rut_otec', '$clasificacion_curso', '$cbc', '$cod_identificador', '$valor_hora', '$valor_hora_sence');";
	
	$database->setquery($sql);
	$database->query();	

}



/*
Creador: Daniel
Motivo: Traer total de las ,modalidades
FEcha 21 01 2014
*/
function TotalModalidad(){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select * from tbl_modalidad_curso";
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}



/*
Creador: Daniel
Motivo: Obtener curso dado id.
FEcha 21 01 2014
*/
function DatosCurso($id){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "SELECT
	tbl_curso.*, tbl_curso_clasificacion.clasificacion AS nombre_clasificacion, tbl_otec.nombre as nombre_otec, tbl_modalidad_curso.modalidad as nombre_modalidad, tbl_curso_tipo.nombre as nombre_tipo
FROM
	tbl_curso
LEFT JOIN tbl_curso_clasificacion ON tbl_curso_clasificacion.id = tbl_curso.clasificacion 
LEFT JOIN tbl_otec on tbl_otec.rut=tbl_curso.rut_otec
LEFT JOIN tbl_modalidad_curso on tbl_modalidad_curso.id=tbl_curso.modalidad
LEFT JOIN tbl_curso_tipo on tbl_curso_tipo.id=tbl_curso.tipo
where tbl_curso.id = '$id'

";
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}


/*
Creador: Daniel
Motivo: ObtenerTotal de Cursos
FEcha 21 01 2014
*/
function TotalCursos($arreglo_post){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	$filtro="";
	if($arreglo_post["filtro_clasificacion"]<>0){
		$filtro.=" and tbl_curso.clasificacion='".$arreglo_post["filtro_clasificacion"]."'";
	}
	
	if($arreglo_post["filtro_tipo_curso"]<>0){
		$filtro.=" and tbl_curso.tipo='".$arreglo_post["filtro_tipo_curso"]."'";
	}
	
	if($arreglo_post["filtro_sence"]<>0){
		$filtro.=" and tbl_curso.sence='".$arreglo_post["filtro_sence"]."'";
	}
	
	if($arreglo_post["filtro_modalidad"]<>0){
		$filtro.=" and tbl_curso.modalidad='".$arreglo_post["filtro_modalidad"]."'";
	}
	
	
	if($arreglo_post["filtro_otec"]<>0){
		$filtro.=" and tbl_curso.rut_otec='".$arreglo_post["filtro_otec"]."'";
	}
	
	$sql = "SELECT
	tbl_curso.*, tbl_modalidad_curso.modalidad as nombre_modalidad, tbl_curso_clasificacion.clasificacion as nombre_clasificacion
FROM
	tbl_curso
INNER JOIN tbl_modalidad_curso ON tbl_curso.modalidad = tbl_modalidad_curso.id
left join tbl_curso_clasificacion
on tbl_curso_clasificacion.id=tbl_curso.clasificacion
where 1 $filtro and tbl_curso.activo=0
order by tbl_curso.id desc
		";

		$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}

/*
Creador: Daniel
Motivo: Actulizar Nivel
Fecha: 21/01/2014
*/


function ActualizaNivel($id_nivel, $nombre_nivel, $descripcion_nivel, $programa, $tutor)
{
	global $c_host,$c_user,$c_pass,$c_db;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "UPDATE tbl_nivel SET nombre= '$nombre_nivel', descripcion='$descripcion_nivel', tutor='$tutor', id_programa='$programa' WHERE id = '$id_nivel'";
	$database->setquery($sql);
	$database->query();
}




/*
Creador: Daniel
Motivo: Insertar registro en tabla de niveles
Fecha: 14/01/2014
*/

function InsertaNivel($nombre_nivel, $descripcion_nivel, $programa, $tutor){
	global $c_host,$c_user,$c_pass,$c_db;	

	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	$sql = "INSERT INTO tbl_nivel(nombre, descripcion, tutor, id_programa) ".
					   "VALUES ('$nombre_nivel', '$descripcion_nivel', '$tutor', '$programa');";
	$database->setquery($sql);
	$database->query();	

}




/*
Creador: Daniel
Motivo: Traer total de Tutores con sus nombres
FEcha 21 01 2014
*/
function TotalTutores(){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "
	select tbl_usuario.nombre_completo, tbl_usuario.rut, tbl_perfil.id, tbl_perfil.rol
	from tbl_usuario
	inner join tbl_perfil
	on tbl_usuario.id_perfil=tbl_perfil.id
	where tbl_perfil.id='3'";
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}


/*
Creador: Daniel
Motivo: Obtener nivel dado id.
FEcha 21 01 2014
*/
function DatosNivel($id){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select * from tbl_nivel where id='$id'";
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}

/*
Creador: Daniel
Motivo: Obtener total de programas, con cruce malla, para obtener el nombre de la malla.
FEcha 21 01 2014
*/
function TotalNiveles(){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sqlBK = "
	
	SELECT
	tbl_nivel.*, tbl_programa.nombre AS nombre_programa,
	tbl_malla.id AS id_malla,
	tbl_malla.nombre AS nombre_malla,
	tbl_usuario.nombre_completo
FROM
	tbl_nivel
INNER JOIN tbl_programa ON tbl_nivel.id_programa = tbl_programa.id
INNER JOIN tbl_malla ON tbl_malla.id = tbl_programa.id_malla
INNER JOIN tbl_usuario ON tbl_usuario.rut = tbl_nivel.tutor";
	
$sql = "
	
	SELECT
	tbl_nivel.*, tbl_programa.nombre AS nombre_programa,
	tbl_usuario.nombre_completo
FROM
	tbl_nivel
INNER JOIN tbl_programa ON tbl_nivel.id_programa = tbl_programa.id
INNER JOIN tbl_usuario ON tbl_usuario.rut = tbl_nivel.tutor
order by tbl_nivel.id desc";

	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}



/*
Creador: Daniel
Motivo: Actulizar ForDeb.
Fecha: 08/01/2014
*/


function ActualizaMalla($nombre_malla, $descripcion_malla, $tipo_malla, $certificable, $ponderacion, $tipo_fecha, $id_malla)
{
	global $c_host,$c_user,$c_pass,$c_db;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "UPDATE tbl_malla SET nombre= '$nombre_malla', descripcion='$descripcion_malla', tipo='$tipo_malla', certificable='$certificable', ponderacion='$ponderacion', tipo_fecha='$tipo_fecha'   WHERE id = '$id_malla'";
	$database->setquery($sql);
	$database->query();
}



/*
Creador: Daniel
Motivo: Actulizar ForDeb.
Fecha: 08/01/2014
*/


function ActualizaPrograma($id, $nombre, $descripcion, $id_malla)
{
	global $c_host,$c_user,$c_pass,$c_db;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "UPDATE tbl_programa SET nombre= '$nombre', descripcion='$descripcion', id_malla='$id_malla' WHERE id = '$id'";
	$database->setquery($sql);
	$database->query();
}




/*
Creador: Daniel
Motivo: Insertar un programa|
Fecha: 14/01/2014
*/

function InsertaPrograma($nombre_programa, $descripcion_programa, $id_malla, $archivo){
	global $c_host,$c_user,$c_pass,$c_db;	

	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	$sql = "INSERT INTO tbl_programa(nombre, descripcion, id_malla, archivo) ".
					   "VALUES ('$nombre_programa', '$descripcion_programa', '$id_malla', '$archivo');";
			   
			
	$database->setquery($sql);
	$database->query();	

}



/*
Creador: Daniel
Motivo: Obtener mallas

*/
function TodosLosPrograma(){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select * from tbl_programa";
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}




/*
Creador: Daniel
Motivo: Obtener total de programas, con cruce malla, para obtener el nombre de la malla.
FEcha 14 01 2014
*/
function TotalProgramas(){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sqlBK = "select tbl_programa.*, tbl_malla.nombre as nombre_malla
		from tbl_programa
		inner join tbl_malla
		on tbl_malla.id=tbl_programa.id_malla";
		
		
		$sql = "select tbl_programa.*
		from tbl_programa
		";
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}




/*
Creador: Daniel
Motivo: Insertar una malla
Fecha: 13/01/2014
*/

function InsertaMalla($nombre_malla, $descripcion_malla, $tipo_malla, $certificable, $ponderacion, $tipo_fecha){
	global $c_host,$c_user,$c_pass,$c_db;	

	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	$sql = "INSERT INTO tbl_malla(nombre, descripcion, tipo, certificable, ponderacion, tipo_fecha) ".
					   "VALUES ('$nombre_malla', '$descripcion_malla', '$tipo_malla', '$certificable', '$ponderacion', '$tipo_fecha');";
			   
	$database->setquery($sql);
	$database->query();	

}


/*
Creador: Daniel
Motivo: Obtener malla dado id.
FEcha 13 01 2014
*/
function DatosMallaBORRADO_POR_DUPLICIDAD_AL_UNIFICAR_EL_FUNCTION($id){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select * from tbl_malla where id='$id'";
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}


/*
Creador: Daniel
Motivo: Obtener total de mallas.
FEcha 13 01 2014
*/
function TotalMallas(){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select * from tbl_malla";
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}





/*
Creador: Daniel
Motivo: Obtener total de empresas.
FEcha 13 01 2014
*/
function TotalEmpresas(){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select * from tbl_empresa";
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}


/*
Creador: Daniel
Motivo: obtener info de Base de personas.
*/
function VerificoAccesoAdmin($user, $pass){
	global $c_host,$c_user,$c_pass,$c_db;
	
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	
	$sql = "select * from tbl_admin where user='$user' and pass='$pass'";
	
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	
	return $cod;
}





?>