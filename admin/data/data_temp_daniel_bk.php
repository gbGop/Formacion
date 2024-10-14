<?php
function AbrirEvaluacion($rut_evaluado, $rut_evaluador, $id_proceso){
	//echo "resetea";
	//exit;
	global $c_host,$c_user,$c_pass,$c_db;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	//tabla finalizado
	$sql = "delete from tbl_sgd_finalizados_evaluado_evaluador  
		where evaluado='$rut_evaluado' and id_proceso='$id_proceso' and evaluador='$rut_evaluador'
	";
	$database->setquery($sql);
	$database->query();
	$sql = "delete from tbl_sgd_sesion_evaluacion  
		where evaluado='$rut_evaluado' and id_proceso='$id_proceso' and evaluador='$rut_evaluador'
	";
	$database->setquery($sql);
	$database->query();
	$sql = "delete from tbl_calibracion_liberados  
		where evaluado='$rut_evaluado' and id_proceso='$id_proceso' and evaluador='$rut_evaluador'
	";
	$database->setquery($sql);
	$database->query();
	$sql = "delete from tbl_sgd_feedback_evaluado  
		where evaluado='$rut_evaluado' and id_proceso='$id_proceso' and evaluador='$rut_evaluador'
	";
	$database->setquery($sql);
	$database->query();
	$sql = "
	delete from tbl_sgd_feedback_finalizado  
		where evaluado='$rut_evaluado' and id_proceso='$id_proceso' and evaluador='$rut_evaluador'
	";
	$database->setquery($sql);
	$database->query();
	
}


function ReseteaDataEvaluacion($rut_evaluado, $rut_evaluador, $id_proceso){
	//echo "resetea";
	//exit;
	global $c_host,$c_user,$c_pass,$c_db;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	//tabla finalizado
	$sql = "delete from tbl_sgd_finalizados_evaluado_evaluador  
		where evaluado='$rut_evaluado' and id_proceso='$id_proceso' and evaluador='$rut_evaluador'
	";
	$database->setquery($sql);
	$database->query();
	$sql = "delete from  tbl_sgd_respuestas 
		where evaluado='$rut_evaluado' and id_proceso='$id_proceso' and evaluador='$rut_evaluador'
	";
	$database->setquery($sql);
	$database->query();
	$sql = "delete from tbl_sgd_respuestas_log  
		where evaluado='$rut_evaluado' and id_proceso='$id_proceso' and evaluador='$rut_evaluador'	";
	$database->setquery($sql);
	$database->query();
	$sql = "delete from tbl_sgd_sesion_evaluacion  
		where evaluado='$rut_evaluado' and id_proceso='$id_proceso' and evaluador='$rut_evaluador'
	";
	$database->setquery($sql);
	$database->query();
	$sql = "delete from tbl_calibracion_liberados  
		where evaluado='$rut_evaluado' and id_proceso='$id_proceso' and evaluador='$rut_evaluador'
	";
	$database->setquery($sql);
	$database->query();
	$sql = "
	delete from tbl_sgd_comentario_competencia  
		where evaluado='$rut_evaluado' and id_proceso='$id_proceso' and evaluador='$rut_evaluador'
	";
	$database->setquery($sql);
	$database->query();
	$sql = "delete from tbl_sgd_feedback_evaluado  
		where evaluado='$rut_evaluado' and id_proceso='$id_proceso' and evaluador='$rut_evaluador'
	";
	$database->setquery($sql);
	$database->query();
	$sql = "
	delete from tbl_sgd_feedback_finalizado  
		where evaluado='$rut_evaluado' and id_proceso='$id_proceso' and evaluador='$rut_evaluador'
	";
	$database->setquery($sql);
	$database->query();
	$sql = "
	delete from tbl_sgd_comentarios_finales  
		where evaluado='$rut_evaluado' and id_proceso='$id_proceso' and evaluador='$rut_evaluador'
	";
	$database->setquery($sql);
	$database->query();
	
}


function TraspasaDataEvaluacion($rut_evaluado, $rut_evaluador, $id_proceso, $rut_validador, $rut_calibrador, $rut_evaluador_original){
	//echo "traspasa";
	//exit;
	
	global $c_host,$c_user,$c_pass,$c_db;
	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	//tabla finalizado
	$sql = "update tbl_sgd_finalizados_evaluado_evaluador set 
		evaluador='$rut_evaluador'
		where evaluado='$rut_evaluado' and id_proceso='$id_proceso' and evaluador='$rut_evaluador_original'
	";
	$database->setquery($sql);
	$database->query();
	$sql = "update tbl_sgd_respuestas set 
		evaluador='$rut_evaluador'
		where evaluado='$rut_evaluado' and id_proceso='$id_proceso' and evaluador='$rut_evaluador_original'
	";
	$database->setquery($sql);
	$database->query();
	$sql = "update tbl_sgd_respuestas_log set 
		evaluador='$rut_evaluador'
		where evaluado='$rut_evaluado' and id_proceso='$id_proceso' and evaluador='$rut_evaluador_original'	";
	$database->setquery($sql);
	$database->query();
	$sql = "update tbl_sgd_sesion_evaluacion set 
		evaluador='$rut_evaluador'
		where evaluado='$rut_evaluado' and id_proceso='$id_proceso' and evaluador='$rut_evaluador_original'
	";
	$database->setquery($sql);
	$database->query();
	$sql = "update tbl_calibracion_liberados set 
		evaluador='$rut_evaluador'
		where evaluado='$rut_evaluado' and id_proceso='$id_proceso' and evaluador='$rut_evaluador_original'
	";
	$database->setquery($sql);
	$database->query();
	$sql = "
	update tbl_sgd_comentario_competencia set 
		evaluador='$rut_evaluador'
		where evaluado='$rut_evaluado' and id_proceso='$id_proceso' and evaluador='$rut_evaluador_original'
	";
	$database->setquery($sql);
	$database->query();
	$sql = "
	update tbl_sgd_feedback_evaluado set 
		evaluador='$rut_evaluador'
		where evaluado='$rut_evaluado' and id_proceso='$id_proceso' and evaluador='$rut_evaluador_original'
	";
	$database->setquery($sql);
	$database->query();
	$sql = "
	update tbl_sgd_feedback_finalizado set 
		evaluador='$rut_evaluador'
		where evaluado='$rut_evaluado' and id_proceso='$id_proceso' and evaluador='$rut_evaluador_original'
	";
	$database->setquery($sql);
	$database->query();
	$sql = "
	update tbl_sgd_comentarios_finales set 
		evaluador='$rut_evaluador'
		where evaluado='$rut_evaluado' and id_proceso='$id_proceso' and evaluador='$rut_evaluador_original'
	";
	$database->setquery($sql);
	$database->query();
	
}

function TotalUsuariosPorEmpresa2($id_empresa){
	
	global $c_host, $c_user, $c_pass, $c_db;
	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);
	$sql = "SELECT count(*) as total from tbl_usuario where id_empresa='$id_empresa' and vigencia<>1";
	
	$database -> setquery($sql);
	$database -> query();
	$datos = $database -> listObjects();
	return $datos;
	

}



function AccesosUnicos($id_empresa){
	
	global $c_host, $c_user, $c_pass, $c_db;
	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);
	$sql = "SELECT
	st1.rut as rut_tbl_usuario,
	st2.rut as rut_log
FROM
	tbl_usuario st1
inner JOIN tbl_log_sistema st2 ON(st1.rut = st2.rut)
WHERE
	st1.id_empresa = '$id_empresa'
AND st1.vigencia <> 1
GROUP BY
	st1.rut order by rut_tbl_usuario";
	
	$database -> setquery($sql);
	$database -> query();
	$datos = $database -> listObjects();
	return $datos;
	

}




function TraeDistictDadoCampoPorEmpresaPorCampo1($campo2, $id_empresa, $campo1, $valor_campo1){
	
	global $c_host, $c_user, $c_pass, $c_db;
	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);
	$sql = " select distinct($campo2) as valor from tbl_usuario where id_empresa='$id_empresa' and $campo2<>'' and $campo1='$valor_campo1' order by valor";
	
	$database -> setquery($sql);
	$database -> query();
	$datos = $database -> listObjects();
	return $datos;
	

}



function TraeDistictDadoCampoPorEmpresa($campo, $id_empresa){
	
	global $c_host, $c_user, $c_pass, $c_db;
	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);
	$sql = " select distinct($campo) as valor from tbl_usuario where id_empresa='$id_empresa' and $campo<>'' order by valor";
	$database -> setquery($sql);
	$database -> query();
	$datos = $database -> listObjects();
	return $datos;
	

}



function TotalPreguntasPorEmpresa2($id_empresa){
	
	global $c_host, $c_user, $c_pass, $c_db;
	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);
	$sql = " SELECT
	tbl_sgd_preguntas.*,tbl_sgd_componente.nombre, tbl_sgd_componente.id as id_componente
FROM
	tbl_sgd_preguntas
inner join tbl_sgd_componente
on tbl_sgd_componente.id=tbl_sgd_preguntas.id_competencia
WHERE
	tbl_sgd_preguntas.id_empresa = '$id_empresa'";
	
	$database -> setquery($sql);
	$database -> query();
	$datos = $database -> listObjects();
	return $datos;
	

}


function DistinctIndicadores(){
	
	global $c_host, $c_user, $c_pass, $c_db;
	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);
	$sql = " select distinct(nombre_indicador) from tbl_indicadoresgestion where nombre_indicador<>''";
	$database -> setquery($sql);
	$database -> query();
	$datos = $database -> listObjects();
	return $datos;
	

}


function EjecutaQueryIndicadores($post, $numero){
	global $c_host, $c_user, $c_pass, $c_db;
	$database = new database($c_host, $c_user, $c_pass);
	
	if($post["familia"]<>"-"){
		$filtro.=" and familia='".$post["familia"]."'";
	} 
	
	if($post["subfamilia"]<>"-"){
		$filtro.=" and subfamilia='".$post["subfamilia"]."'";
	} 
	
	if($post["indicadores"]<>"-"){
		$filtro.=" and nombre_indicador='".$post["indicadores"]."'";
	} 
	
	if($post["nombre_gerencia".$numero]<>"-"){
		$filtro.=" and nombre_gerencia='".$post["nombre_gerencia".$numero]."'";
	} 
	
	if($post["nombre_unidad".$numero]<>"-"){
		$filtro.=" and nombre_unidad='".$post["nombre_unidad".$numero]."'";
	} 
	
	if($post["agno".$numero]<>"-"){
		$filtro.=" and anho='".$post["agno".$numero]."'";
	} 
	
	
	$database -> setDb($c_db);
	$sql = " Select mes, sum(valor) as resultadomes, unidadmedida_indicador from

tbl_indicadoresgestion


where mes<>'' ".utf8_decode($filtro)."
group by mes
";


	$database -> setquery($sql);
	$database -> query();
	$datos = $database -> listObjects();
	return $datos;
}
function DistinctAgnos(){ 
	
	global $c_host, $c_user, $c_pass, $c_db;
	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);
	$sql = " select distinct(anho) from tbl_indicadoresgestion where anho<>''";
	$database -> setquery($sql);
	$database -> query();
	$datos = $database -> listObjects();
	return $datos;
	

}

function DistinctUnidades(){
	
	global $c_host, $c_user, $c_pass, $c_db;
	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);
	$sql = " select distinct(nombre_unidad) from tbl_indicadoresgestion where nombre_unidad<>''";
	$database -> setquery($sql);
	$database -> query();
	$datos = $database -> listObjects();
	return $datos;
	

}


function DistinctGerencias(){
	
	global $c_host, $c_user, $c_pass, $c_db;
	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);
	$sql = " select distinct(nombre_gerencia) from tbl_indicadoresgestion where nombre_gerencia<>''";
	$database -> setquery($sql);
	$database -> query();
	$datos = $database -> listObjects();
	return $datos;
	

}


function TraeIndicadoresSelect($subfamilia){
	
	global $c_host, $c_user, $c_pass, $c_db;
	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);
	$subfamilia=utf8_decode($subfamilia);
	$sql = " select distinct(nombre_indicador) from tbl_indicadoresgestion where subfamilia='$subfamilia'";
	$database -> setquery($sql);
	$database -> query();
	$datos = $database -> listObjects();
	return $datos;
	

}

function TraeSubFamilias($id_familia){
	
	global $c_host, $c_user, $c_pass, $c_db;
	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);
	$sql = " select distinct(subfamilia) from tbl_indicadoresgestion where familia='$id_familia'";
	$database -> setquery($sql);
	$database -> query();
	$datos = $database -> listObjects();
	return $datos;
	

}

function DistinctSubFamilia(){
	
	global $c_host, $c_user, $c_pass, $c_db;
	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);
	$sql = " select distinct(subfamilia) from tbl_indicadoresgestion where subfamilia<>''";
	$database -> setquery($sql);
	$database -> query();
	$datos = $database -> listObjects();
	return $datos;
	

}


function DistinctFamilia(){
	
	global $c_host, $c_user, $c_pass, $c_db;
	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);
	$sql = " select distinct(familia) from tbl_indicadoresgestion where familia<>''";
	$database -> setquery($sql);
	$database -> query();
	$datos = $database -> listObjects();
	return $datos;
	

}


function ESTADISTICAS_8($id, $case, $id_empresa, $fecha_inicio, $fecha_termino){
	
	global $c_host, $c_user, $c_pass, $c_db;
	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);
	if($fecha_inicio && $fecha_termino){
		$filtro_fechas="and fecha>='$fecha_inicio' and fecha<='$fecha_termino'";
	}
	
	$sql = " select count(*) as visitas_por_ambiente_home, id_tipo, tbl_galeria.categoria as nombre from tbl_galeria_log 
inner join tbl_galeria
on tbl_galeria.id=id_tipo
where id_tipo='$id'
$filtro_fechas
	";
	$database -> setquery($sql);
	$database -> query();
	$datos = $database -> listObjects();
	return $datos;
	

}


function ESTADISTICAS_7($id, $case, $id_empresa, $fecha_inicio, $fecha_termino){
	
	global $c_host, $c_user, $c_pass, $c_db;
	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);
	
	if($fecha_inicio && $fecha_termino){
		$filtro_fechas="and fecha>='$fecha_inicio' and fecha<='$fecha_termino'";
	}
	
	
	$sql = " SELECT
	count(h.id)AS visitas_por_ambiente_home,
	h.*, tbl_noticias.titulo as nombre
FROM
	tbl_log_sistema h

inner join tbl_noticias
on tbl_noticias.id=id_detalle
WHERE
	h.id_empresa = '$id_empresa'
AND ambiente = '$case'
AND id_detalle = '$id'
$filtro_fechas

ORDER BY
	count(h.id)DESC
	";
	$database -> setquery($sql);
	$database -> query();
	$datos = $database -> listObjects();
	return $datos;
	

}

function ESTADISTICAS_6($id, $case, $id_empresa, $fecha_inicio, $fecha_termino){
	
	global $c_host, $c_user, $c_pass, $c_db;
	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);
	
	if($fecha_inicio && $fecha_termino){
		$filtro_fechas="and fecha>='$fecha_inicio' and fecha<='$fecha_termino'";
	}
	
	
	
	$sql = " SELECT
	tbl_biblio_categorias.categoria as nombre, count(h.id)AS visitas_por_ambiente_home,
	h.*
FROM
	tbl_log_sistema h
inner join tbl_biblio_categorias
on tbl_biblio_categorias.id=id_detalle
WHERE
	h.id_empresa = '$id_empresa'
AND ambiente = '$case'
AND id_detalle = '$id'

$filtro_fechas


ORDER BY
	count(h.id)DESC
	";
	
	$database -> setquery($sql);
	$database -> query();
	$datos = $database -> listObjects();
	return $datos;
	

}


function ESTADISTICAS_5($case, $id_empresa, $fecha_inicio, $fecha_termino){
	
	global $c_host, $c_user, $c_pass, $c_db;
	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);
	if($fecha_inicio && $fecha_termino){
		$filtro_fechas="and fecha>='$fecha_inicio' and fecha<='$fecha_termino'";
	}
	
	$sql = "select 
	distinct(id_detalle) 
	from tbl_log_sistema where id_empresa='$id_empresa' and ambiente='$case' 
	$filtro_fechas
	and id_detalle <>''";
	$database -> setquery($sql);
	$database -> query();
	$datos = $database -> listObjects();
	return $datos;
	

}

function ESTADISTICAS_4_1($case, $fecha_inicio, $fecha_termino, $id_empresa){
	
	global $c_host, $c_user, $c_pass, $c_db;
	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);
	if($fecha_inicio && $fecha_termino){
		$filtro_fechas="and fecha>='$fecha_inicio' and fecha<='$fecha_termino'";
	}
	
	$sql = "select count(h.id) as visitas_por_ambiente_home,h.* from tbl_log_sistema h where h.id_empresa='$id_empresa' and ambiente='$case' 
	$filtro_fechas
	order by count(id) DESC";
	$database -> setquery($sql);
	$database -> query();
	$datos = $database -> listObjects();
	return $datos;
	

}

function ESTADISTICAS_4($fecha_inicio, $fecha_termino, $id_empresa){
	
	global $c_host, $c_user, $c_pass, $c_db;
	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);
	if($fecha_inicio && $fecha_termino){
		$filtro_fechas="and fecha>='$fecha_inicio' and fecha<='$fecha_termino'";
	}
	
	$sql = "select * from tbl_case_estadisticas where id_empresa='$id_empresa' $filtro_fechas ";
	$database -> setquery($sql);
	$database -> query();
	$datos = $database -> listObjects();
	return $datos;
	

}


function ESTADISTICAS_3($fecha_inicio, $fecha_termino, $id_empresa){
	
	global $c_host, $c_user, $c_pass, $c_db;
	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);
	
	if($fecha_inicio && $fecha_termino){
		$filtro_fechas="and fecha>='$fecha_inicio' and fecha<='$fecha_termino'";
	}
	
	$sql = "SELECT
	count(h.id)AS visitas_por_ambiente_home,
	h.*, tbl_menu_nivel2.nombre, id_nivel1, tbl_menu_nivel1.nombre as nombre_nivel1
FROM
	tbl_log_sistema h
inner join tbl_menu_nivel2
on tbl_menu_nivel2.id=id_menu_nivel
left join tbl_menu_nivel1
on tbl_menu_nivel1.id=id_nivel1
WHERE
	h.id_empresa = '$id_empresa'
AND ambiente = 'web'
AND id_menu_nivel IS NOT NULL
AND menu_nivel = '2' 
$filtro_fechas 
GROUP BY
	id_menu_nivel";
	$database -> setquery($sql);
	$database -> query();
	$datos = $database -> listObjects();
	return $datos;
	

}

function ESTADISTICAS_2($fecha_inicio, $fecha_termino, $id_empresa){
	
	global $c_host, $c_user, $c_pass, $c_db;
	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);
	
	if($fecha_inicio && $fecha_termino){
		$filtro_fechas="and fecha>='$fecha_inicio' and fecha<='$fecha_termino'";
	}
	
	
	$sql = "SELECT
	count(h.id)AS visitas_por_ambiente_home,
	h.*, tbl_menu_nivel1.nombre
FROM
	tbl_log_sistema h
inner join tbl_menu_nivel1
on tbl_menu_nivel1.id=id_menu_nivel
WHERE
	h.id_empresa = '$id_empresa'
AND ambiente = 'web'
AND id_menu_nivel IS NOT NULL
AND menu_nivel = '1' 
$filtro_fechas
GROUP BY
	id_menu_nivel";
	
	$database -> setquery($sql);
	$database -> query();
	$datos = $database -> listObjects();
	return $datos;
	

}

function ESTADISTICAS_1_VISITAS($fecha_inicio, $fecha_termino, $id_empresa){
	
	global $c_host, $c_user, $c_pass, $c_db;
	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);
	
	if($fecha_inicio && $fecha_termino){
		$filtro_fechas="and fecha>='$fecha_inicio' and fecha<='$fecha_termino'";
	}
	$sql = " select 
			count(h.id) as visitas_por_ambiente_home,h.* 
			from 
			tbl_log_sistema h 
			where h.id_empresa='$id_empresa' 
			and ambiente='visitas' 
			$filtro_fechas 
			order by count(id) DESC";
	
	
	$database -> setquery($sql);
	$database -> query();
	$datos = $database -> listObjects();
	return $datos;
	

}


function ESTADISTICAS_1($fecha_inicio, $fecha_termino, $id_empresa){
	
	global $c_host, $c_user, $c_pass, $c_db;
	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);
	
	if($fecha_inicio && $fecha_termino){
		$filtro_fechas="and fecha>='$fecha_inicio' and fecha<='$fecha_termino'";
	}
	$sql = " select 
			count(h.id) as visitas_por_ambiente_home,h.* 
			from 
			tbl_log_sistema h 
			where h.id_empresa='$id_empresa' 
			and ambiente='home_tnt' 
			$filtro_fechas 
			order by count(id) DESC";
	
	$database -> setquery($sql);
	$database -> query();
	$datos = $database -> listObjects();
	return $datos;
	

}



function TraigoRelacionEvaluadoEvaluadorValidadorAdmin($evaluado, $evaluador, $id_proceso) {
	global $c_host, $c_user, $c_pass, $c_db;
	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);
	$sql = "
			SELECT
			baseevaluado.perfil_evaluacion as codigo_perfil_evaluacion,
	tbl_sgd_relaciones.subperfil as codigo_subperfil,
	tbl_sgd_relaciones.evaluado,
	baseevaluado.rut_completo AS rutcompletoevaluado,
	baseevaluado.nombre_completo AS nombre_evaluado,
	baseevaluado.cargo AS cargoevaluado,
	tbl_sgd_relaciones.evaluador,
	baseevaluador.rut_completo AS rutcompletoevaluador,
	baseevaluador.nombre_completo AS nombre_evaluador,
	baseevaluador.cargo AS cargoevaluador,
	tbl_sgd_relaciones.validador,
	basevalidador.nombre_completo AS nombre_validador,
	basevalidador.rut_completo AS rutcompletovalidador,
	basevalidador.cargo AS cargovalidador,
	tbl_sgd_relaciones.calibrador,
	basecalibrador.nombre_completo AS nombre_calibrador,
	basecalibrador.rut_completo AS rutcompletocalibrador,
	basecalibrador.cargo AS cargocalibrador,
	tbl_sgd_perfiles_ponderaciones.descripcion AS perfil_evaluacion, 
	tbl_sgd_subperfiles.perfil as descripcion_subperfil, 
	tbl_sgd_relaciones.comentario
FROM
	tbl_sgd_relaciones
INNER JOIN tbl_usuario AS baseevaluado ON baseevaluado.rut = tbl_sgd_relaciones.evaluado
INNER JOIN tbl_usuario AS baseevaluador ON baseevaluador.rut = tbl_sgd_relaciones.evaluador
LEFT JOIN tbl_usuario AS basevalidador ON basevalidador.rut = tbl_sgd_relaciones.validador
LEFT JOIN tbl_usuario AS basecalibrador ON basecalibrador.rut = tbl_sgd_relaciones.calibrador
LEFT JOIN tbl_sgd_perfiles_ponderaciones ON tbl_sgd_perfiles_ponderaciones.perfil = baseevaluado.perfil_evaluacion
LEFT JOIN tbl_sgd_subperfiles on tbl_sgd_subperfiles.id=tbl_sgd_relaciones.subperfil
WHERE
	tbl_sgd_relaciones.id_proceso = '$id_proceso' and tbl_sgd_relaciones.evaluado='$evaluado' and tbl_sgd_relaciones.evaluador='$evaluador'
	";
	
	$database -> setquery($sql);
	$database -> query();
	$datos = $database -> listObjects();
	return $datos;
}


function AdminInsertarRegistroRelacionEvaluadoEvaluador($evaluado, $evaluador, $validador, $calibrador, $id_proceso, $comentario, $perfil, $subperfil, $id_empresa){
	global $c_host,$c_user,$c_pass,$c_db;	

	$database = new database($c_host,$c_user,$c_pass);
	$database->setDb($c_db);
	$sql = "insert into tbl_sgd_relaciones(evaluado, evaluador, validador, calibrador, id_proceso, subperfil, id_empresa, comentario, perfil_evaluacion_competencias)
			values ('$evaluado', '$evaluador', '$validador', '$calibrador', '$id_proceso', '$subperfil', '$id_empresa', '$comentario', '$perfil') ";	
	$database->setquery($sql);
	$database->query();	

}


function allPersonasPorEmpresaAdmin($buscar, $id_empresa) {
	global $c_host, $c_user, $c_pass, $c_db;
	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);
	$sql = "SELECT * FROM tbl_usuario WHERE id_empresa='$id_empresa' and (nombre_completo like '%" . $buscar . "%' OR rut like '%" . $buscar . "%') ORDER BY nombre_completo";
	$database -> setquery($sql);
	$database -> query();
	$datos = $database -> listObjects();
	return $datos;
}

function RelacionesPorProcesoParaGestion($id_empresa, $id_proceso, $campo1, $campo2, $campo3, $datos_empresa) {

	global $c_host, $c_user, $c_pass, $c_db;
	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);

	$sql = "
	SELECT
	tbl_sgd_relaciones.evaluado,
	baseevaluado.rut_completo AS rutcompletoevaluado,
	baseevaluado.nombre_completo AS nombre_evaluado,
	baseevaluado.cargo AS cargoevaluado,
	
	baseevaluado.".$datos_empresa[0]->campo1." AS campo1_evaluado,
	baseevaluado.".$datos_empresa[0]->campo2." AS campo2_evaluado,
	baseevaluado.".$datos_empresa[0]->campo3." AS campo3_evaluado,
		
	tbl_sgd_relaciones.evaluador,
	baseevaluador.rut_completo AS rutcompletoevaluador,
	baseevaluador.nombre_completo AS nombre_evaluador,
	baseevaluador.cargo AS cargoevaluador,
	baseevaluador.".$datos_empresa[0]->campo1." AS campo1_evaluador,
	baseevaluador.".$datos_empresa[0]->campo2." AS campo2_evaluador,
	baseevaluador.".$datos_empresa[0]->campo3." AS campo3_evaluador,
	
	
	tbl_sgd_relaciones.validador,
	basevalidador.nombre_completo AS nombre_validador,
	basevalidador.rut_completo AS rutcompletovalidador,
	basevalidador.cargo AS cargovalidador,
	basevalidador.".$datos_empresa[0]->campo1." AS campo1_validador,
	basevalidador.".$datos_empresa[0]->campo2." AS campo2_validador,
	basevalidador.".$datos_empresa[0]->campo3." AS campo3_validador,
	
	
	tbl_sgd_relaciones.calibrador,
	basecalibrador.nombre_completo AS nombre_calibrador,
	basecalibrador.rut_completo AS rutcompletocalibrador,
	basecalibrador.cargo AS cargocalibrador,
	basecalibrador.".$datos_empresa[0]->campo1." AS campo1_calibrador,
	basecalibrador.".$datos_empresa[0]->campo2." AS campo2_calibrador,
	basecalibrador.".$datos_empresa[0]->campo3." AS campo3_calibrador,
	
	
	tbl_sgd_perfiles_ponderaciones.descripcion AS perfil_evaluacion, 
	tbl_sgd_subperfiles.perfil as descripcion_subperfil
FROM
	tbl_sgd_relaciones
INNER JOIN tbl_usuario AS baseevaluado ON baseevaluado.rut = tbl_sgd_relaciones.evaluado
INNER JOIN tbl_usuario AS baseevaluador ON baseevaluador.rut = tbl_sgd_relaciones.evaluador
LEFT JOIN tbl_usuario AS basevalidador ON basevalidador.rut = tbl_sgd_relaciones.validador
LEFT JOIN tbl_usuario AS basecalibrador ON basecalibrador.rut = tbl_sgd_relaciones.calibrador
LEFT JOIN tbl_sgd_perfiles_ponderaciones ON tbl_sgd_perfiles_ponderaciones.perfil = tbl_sgd_relaciones.perfil_evaluacion_competencias
LEFT JOIN tbl_sgd_subperfiles on tbl_sgd_subperfiles.id=tbl_sgd_relaciones.subperfil
WHERE
	tbl_sgd_relaciones.id_proceso = '$id_proceso' 
	";

	
	$database -> setquery($sql);
	$database -> query();
	$cod = $database -> listObjects();

	return $cod;

}

function TraigoTotalProcesosPorEmpresa($id_empresa) {
	global $c_host, $c_user, $c_pass, $c_db;

	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);

	$sql = "select * from tbl_sgd_proceso_anual where id_empresa='$id_empresa' order by ano desc";
	$database -> setquery($sql);
	$database -> query();
	$cod = $database -> listObjects();

	return $cod;
}

function TraigoAlternativasDadoIdGrupoAlternativa($id_grupo_alternativa) {
	global $c_host, $c_user, $c_pass, $c_db;

	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);

	$sql = "select * from tbl_sgd_alternativas where id_grupo_alternativa='$id_grupo_alternativa'";

	$database -> setquery($sql);
	$database -> query();
	$cod = $database -> listObjects();

	return $cod;
}

function TotalPerfilesDeEvaluacionSgd($id_empresa) {
	global $c_host, $c_user, $c_pass, $c_db;

	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);

	$sql = "select * from tbl_sgd_perfiles_ponderaciones where id_empresa='$id_empresa' ";

	$database -> setquery($sql);
	$database -> query();
	$cod = $database -> listObjects();

	return $cod;
}

function TotalPreguntasPorEmpresa($id_empresa) {
	global $c_host, $c_user, $c_pass, $c_db;

	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);

	$sql = "select * from tbl_sgd_preguntas where id_empresa='$id_empresa' ";

	$database -> setquery($sql);
	$database -> query();
	$cod = $database -> listObjects();

	return $cod;
}

function TotalAlternativasPorEmpresa($id_empresa) {
	global $c_host, $c_user, $c_pass, $c_db;

	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);

	$sql = "select distinct(id_grupo_alternativa) from tbl_sgd_alternativas where id_empresa='$id_empresa' ";

	$database -> setquery($sql);
	$database -> query();
	$cod = $database -> listObjects();

	return $cod;
}

function TotalCompetenciasDadoEmpresa($id_empresa) {
	global $c_host, $c_user, $c_pass, $c_db;

	$database = new database($c_host, $c_user, $c_pass);
	$database -> setDb($c_db);

	$sql = "select * from tbl_sgd_componente where id_empresa='$id_empresa' ";

	$database -> setquery($sql);
	$database -> query();
	$cod = $database -> listObjects();

	return $cod;
}
?>