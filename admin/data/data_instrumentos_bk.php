<?php


function TraelPerfiles_sgd($id_empresa) {
	global $c_host, $c_user, $c_pass, $c_db;

	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);

	$sql = "select * from tbl_sgd_perfiles_ponderaciones where id_empresa='$id_empresa' ";

	$database -> setquery($sql);
	$database -> query();
	$cod = $database -> listObjects();

	return $cod;
}

function TraePerfilInstrumento($id) {
	global $c_host, $c_user, $c_pass, $c_db;

	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);

	$sql = "select * from tbl_sgd_perfiles_ponderaciones where id='$id' ";

	$database -> setquery($sql);
	$database -> query();
	$cod = $database -> listObjects();

	return $cod;
}

function TraeCompetenciaInstrumento($id) {
	global $c_host, $c_user, $c_pass, $c_db;

	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);

	$sql = "select * from tbl_sgd_componente where id='$id' ";

	$database -> setquery($sql);
	$database -> query();
	$cod = $database -> listObjects();

	return $cod;
}

function TraePreguntaInstrumento($id) {
	global $c_host, $c_user, $c_pass, $c_db;

	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);

	$sql = "select * from tbl_sgd_preguntas where id='$id' ";

	$database -> setquery($sql);
	$database -> query();
	$cod = $database -> listObjects();

	return $cod;
}

function TraeAlternativasInstrumento($id_empresa) {
	global $c_host, $c_user, $c_pass, $c_db;

	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);

	$sql = "select * from tbl_sgd_alternativas where id_empresa='$id_empresa' GROUP BY id_grupo_alternativa ORDER BY id_grupo_alternativa";

	$database -> setquery($sql);
	$database -> query();
	$cod = $database -> listObjects();

	return $cod;
}

function TraeAlternativasInstrumentoID($id) {
	global $c_host, $c_user, $c_pass, $c_db;

	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);

	$sql = "select * from tbl_sgd_alternativas where id_grupo_alternativa='$id'";

	$database -> setquery($sql);
	$database -> query();
	$cod = $database -> listObjects();

	return $cod;
}


function nuevo_perfil_instrumento($perfil,$descripcion,$descripcion_texto,$id,$id_empresa,$obj_empresa,$obj_area,$obj_individuales,$competencias) {
	global $c_host, $c_user, $c_pass, $c_db;

	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);
	if ($id==""){
		$sql = "INSERT INTO tbl_sgd_perfiles_ponderaciones(perfil,descripcion,descripcion_texto,id_empresa,objetivos_empresa,objetivos_area,objetivos_individuales,competencias) 
	    VALUES ('$perfil','$descripcion','$descripcion_texto','$id_empresa','$obj_empresa','$obj_area','$obj_individuales','$competencias');";
		$e="Perfil ingresado con exito";
	}else{
		$sql = "UPDATE tbl_sgd_perfiles_ponderaciones SET perfil='$perfil',descripcion='$descripcion',descripcion_texto='$descripcion_texto',objetivos_empresa='$obj_empresa',objetivos_area='$obj_area',objetivos_individuales='$obj_individuales',competencias='$competencias' WHERE id = '$id'";
	    $e="Perfil editado con exito";
		}		   
	$database->setquery($sql);
	$database->query();
	echo $e;
}

function nueva_competencia_instrumento($competencia,$descripcion,$id,$id_empresa) {
	global $c_host, $c_user, $c_pass, $c_db;

	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);
	if ($id==""){
		$sql = "INSERT INTO tbl_sgd_componente(nombre,descripcion,id_empresa) 
	    VALUES ('$competencia','$descripcion','$id_empresa');";
		$e=(mysql_insert_id())+1;
	}else{
		$sql = "UPDATE tbl_sgd_componente SET nombre='$competencia',descripcion='$descripcion' WHERE id = '$id'";
	    $e="";
		}		   
	$database->setquery($sql);
	$database->query();
	echo $e;
}

function nueva_pregunta_instrumento($pregunta,$descripcion,$id,$id_empresa,$id_competencia,$comentario) {
	global $c_host, $c_user, $c_pass, $c_db;

	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);
	if ($id==""){
		$sql = "INSERT INTO tbl_sgd_preguntas(pregunta,descripcion,id_empresa,id_competencia,tiene_comentarios) 
	    VALUES ('$pregunta','$descripcion','$id_empresa','$id_competencia','$comentario');";
		$e="Pregunta ingresada con exito";
	}else{
		$sql = "UPDATE tbl_sgd_preguntas SET pregunta='$pregunta',descripcion='$descripcion',id_competencia='$id_competencia',tiene_comentarios='$comentario' WHERE id = '$id'";
	    $e="Pregunta editada con exito";
		}		   
	$database->setquery($sql);
	$database->query();
	echo $e;
}

function nueva_alternativa_instrumento($alternativa,$puntaje,$id_grupo,$id_empresa,$descripcion) {
	global $c_host, $c_user, $c_pass, $c_db;

	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);
	
		$sql = "INSERT INTO tbl_sgd_alternativas(alternativa,puntaje,id_grupo_alternativa,id_empresa,descripcion) 
	    VALUES ('$alternativa','$puntaje','$id_grupo','$id_empresa','$descripcion');";

	$database->setquery($sql);
	$database->query();

}


function ultimoIdGrupoAlternativa(){
	global $c_host,$c_user,$c_pass,$c_db;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	$sql = "SELECT MAX(id_grupo_alternativa) as ultimo from tbl_sgd_alternativas";
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	return $cod[0]->ultimo;

}



function CompetenciasPerfiles($perfil){
	global $c_host,$c_user,$c_pass,$c_db;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	$sql = "	
		SELECT
	rel_sgd_perfil_competencias.*, tbl_sgd_componente.nombre AS nombre_componente, tbl_sgd_componente.descripcion AS descripcion_componente,
	tbl_sgd_componente.id_dimension
FROM
	rel_sgd_perfil_competencias
INNER JOIN tbl_sgd_componente ON rel_sgd_perfil_competencias.id_componente = tbl_sgd_componente.id
WHERE
	perfil_evaluacion = '$perfil' order by tbl_sgd_componente.id_dimension
	";
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	return $cod;

}

function InsertaCompetenciaPerfil($perfil, $id_competencia){
	global $c_host,$c_user,$c_pass,$c_db;	

	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	$sql = "INSERT INTO rel_sgd_perfil_competencias(perfil_evaluacion, id_componente) ".
					   "VALUES ('$perfil', '$id_competencia');";
	
	$database->setquery($sql);
	$database->query();	

}

function TotalCompetenciasEmpresa($id_empresa) {
	global $c_host, $c_user, $c_pass, $c_db;

	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);

	$sql = "select * from tbl_sgd_componente where id_empresa='$id_empresa' ";

	$database -> setquery($sql);
	$database -> query();
	$cod = $database -> listObjects();

	return $cod;
}


function TraePerfilesCompetencias($id_competencia){
	global $c_host,$c_user,$c_pass,$c_db;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	$sql = "	SELECT
	rel_sgd_perfil_competencias.*, tbl_sgd_perfiles_ponderaciones.descripcion as nombre_perfil
FROM
	rel_sgd_perfil_competencias
inner join tbl_sgd_perfiles_ponderaciones
on tbl_sgd_perfiles_ponderaciones.id=rel_sgd_perfil_competencias.perfil_evaluacion
WHERE
	id_componente = '$id_competencia'	";
	$database->setquery($sql);
	$database->query();
	$cod = $database->listObjects();
	return $cod;

}

function InsertaPreguntaCompetencia($id_competencia, $id_pregunta){
	global $c_host,$c_user,$c_pass,$c_db;	

	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	$sql = "UPDATE tbl_sgd_preguntas SET id_competencia='$id_competencia' WHERE id = '$id_pregunta'";
	    $e="Competencia editada con exito";
	
	$database->setquery($sql);
	$database->query();	

}
function InsertaAlternativaPregunta($id_pregunta, $id_alternativa){
	global $c_host,$c_user,$c_pass,$c_db;	

	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	$sql = "UPDATE tbl_sgd_preguntas SET id_alternativa='$id_alternativa' WHERE id = '$id_pregunta'";

	$database->setquery($sql);
	$database->query();	


}


function TraePreguntasEmpresa($id_empresa) {
	global $c_host, $c_user, $c_pass, $c_db;

	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);

	$sql = "select * from tbl_sgd_preguntas where id_empresa='$id_empresa' ";

	$database -> setquery($sql);
	$database -> query();
	$cod = $database -> listObjects();

	return $cod;
}

function TraeAlternativasPorEmpresa($id_empresa) {
	global $c_host, $c_user, $c_pass, $c_db;

	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);

	$sql = "select distinct(id_grupo_alternativa) from tbl_sgd_alternativas where id_empresa='$id_empresa' ";

	$database -> setquery($sql);
	$database -> query();
	$cod = $database -> listObjects();

	return $cod;
}

function TraigoAlternativasDadoIdGrupoAlternativa2($id_grupo_alternativa) {
	global $c_host, $c_user, $c_pass, $c_db;

	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);

	$sql = "select * from tbl_sgd_alternativas where id_grupo_alternativa='$id_grupo_alternativa'";

	$database -> setquery($sql);
	$database -> query();
	$cod = $database -> listObjects();

	return $cod;
}


?>