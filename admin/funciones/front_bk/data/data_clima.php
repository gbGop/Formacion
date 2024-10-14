<?php

function CLIMA_TieneAccesoResultados2022($rut)
{
	$connexion = new DatabasePDO();
	$sql = "SELECT * FROM tbl_clima_acceso_resultados_2022 WHERE rut = :rut";
	$connexion->query($sql);
	$connexion->bind(':rut', $rut);
	$datos = $connexion->resultset();
	return $datos;
}

function DependientesVistaMiEstructuraMenor3($rut)
{
	$connexion = new DatabasePDO();

	$sql = "SELECT
	tbl_clima_dot_resultados.num_rut as rut,
	tbl_clima_dot_resultados.nombre,
	tbl_clima_dot_resultados.glosa_cargo_act as cargo,
	tbl_clima_dot_resultados.glosa_division, 
	(select count(num_rut) as total from tbl_clima_dot_resultados as a where a.rut_jefe_1=tbl_clima_dot_resultados.num_rut) as es_jefe_o_no, 
	tbl_clima_resultados_jefe_proceso.resultado_final as resultado, 
	tbl_clima_resultados_jefe_proceso.dependientes_finalizados as total_finalizados

	FROM
	tbl_clima_dot_resultados

	left join tbl_clima_resultados_jefe_proceso on tbl_clima_resultados_jefe_proceso.rut=tbl_clima_dot_resultados.num_rut
	WHERE
	rut_jefe_1 = :rut
	and
	(select count(num_rut) as total from tbl_clima_dot_resultados as a where a.rut_jefe_1=tbl_clima_dot_resultados.num_rut)<3

	order by tbl_clima_resultados_jefe_proceso.resultado_final desc;
	";

	$connexion->query($sql);
	$connexion->bind(':rut', $rut);
	$datos = $connexion->resultset();
	return $datos;
}

function CLIMA_VeoSiTieneDependientesPAraSeccionMiEquipo2021($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT
            tbl_clima_dependencias_proceso2021.rut,
            tbl_clima_resultado_jefe.resultado as resultado, 
            nombre,
            cargo,
            (SELECT count(id) AS es_jefe FROM tbl_clima_dependencias_proceso2021 AS a WHERE a.jefe_1 = tbl_clima_dependencias_proceso2021.rut) AS jefe_o_no 
        FROM
            tbl_clima_dependencias_proceso2021 
            LEFT JOIN tbl_clima_resultado_jefe ON tbl_clima_resultado_jefe.rut = tbl_clima_dependencias_proceso2021.rut
        WHERE
            jefe_1 = :rut
            AND (
                SELECT count(id) AS es_jefe 
                FROM tbl_clima_dependencias_proceso2021 AS a 
                WHERE a.jefe_1 = tbl_clima_dependencias_proceso2021.rut
            )
        ORDER BY tbl_clima_resultado_jefe.resultado DESC";

    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $datos = $connexion->resultset();
    return $datos;
}

function ItemPorProfundizacion($categoria, $rut, $tipo)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_clima_resultados_profundizacion_divisional WHERE rut=:rut AND categoria=:categoria AND tipo=:tipo";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':categoria', $categoria);
    $connexion->bind(':tipo', $tipo);
    $datos = $connexion->resultset();
    return $datos;
}
function DatosCategoriaPorProcesoParaProfundizacion()
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_clima_profundizacion_orden ORDER BY orden";
    $connexion->query($sql);
    $datos = $connexion->listObjects();
    return $datos;
}

function DatosGlobalProfundizacionP($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_clima_acceso_profundizacion_por_proceso WHERE rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $datos = $connexion->listObjects();
    return $datos;
}
function DependientesVistaMiEstructura($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT
        tbl_clima_dot_resultados.num_rut as rut,
        tbl_clima_dot_resultados.nombre,
        tbl_clima_dot_resultados.glosa_cargo_act as cargo,
        tbl_clima_dot_resultados.glosa_division, 
        (select count(num_rut) as total from tbl_clima_dot_resultados as a where a.rut_jefe_1=tbl_clima_dot_resultados.num_rut) as es_jefe_o_no, 
        tbl_clima_resultados_jefe_proceso.resultado_final as resultado, 
        tbl_clima_resultados_jefe_proceso.dependientes_finalizados as total_finalizados
    FROM
        tbl_clima_dot_resultados 
        left join tbl_clima_resultados_jefe_proceso on tbl_clima_resultados_jefe_proceso.rut=tbl_clima_dot_resultados.num_rut
    WHERE
        rut_jefe_1 = :rut and 
        (select count(num_rut) as total from tbl_clima_dot_resultados as a where a.rut_jefe_1=tbl_clima_dot_resultados.num_rut) > 2
    order by tbl_clima_resultados_jefe_proceso.resultado_final desc";
    
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $datos = $connexion->resultset();
    return $datos;
}
function DatosCategorialEstructuraP($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_clima_resultado_categoria_estructura_proceso WHERE rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $datos = $connexion->resultset();
    return $datos;
}

function DatosGlobalEstructuraP($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_clima_resultados_estructura_proceso WHERE rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $datos = $connexion->resultset();
    return $datos;
}

function DatosCategorialJEfeP($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_clima_resultado_categoria_jefatura_proceso WHERE rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $datos = $connexion->resultset();
    return $datos;
}

function DatosGlobalJEfeP($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_clima_resultados_jefe_proceso WHERE rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $datos = $connexion->resultset();
    return $datos;
}

function VeoDatosClimaDotacion($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_clima_dot WHERE num_rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $datos = $connexion->resultset();
    return $datos;
}
function DatosUsuarioTblUsuarioClima($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_usuario WHERE rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $datos = $connexion->resultset();
    return $datos;
}

function CLIMA_MiEstructura($rut, $rut_jefe)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT
                tbl_clima_dependencias.rut,
                tbl_clima_resultado_jefe.resultado as resultado, 
                nombre,
                cargo,
                (SELECT count(id) AS es_jefe FROM tbl_clima_dependencias AS a WHERE a.jefe_1 = tbl_clima_dependencias.rut) AS jefe_o_no 
            FROM
                tbl_clima_dependencias 
                LEFT JOIN tbl_clima_resultado_jefe ON tbl_clima_resultado_jefe.rut = tbl_clima_dependencias.rut
            WHERE
                jefe_1 = :rut_jefe";
    $connexion->query($sql);
    $connexion->bind(':rut_jefe', $rut_jefe);
    $datos = $connexion->resultset();
    return $datos;
}

function CLIMA_VeoMiJefeDatos($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT 
                tbl_clima_dependencias.jefe_1, 
                a.nombre AS nombre_jefe, 
                a.cargo AS cargo_jefe 
            FROM 
                tbl_clima_dependencias 
                INNER JOIN tbl_clima_dependencias AS a ON a.rut = tbl_clima_dependencias.jefe_1 
                INNER JOIN tbl_usuario ON tbl_usuario.rut = tbl_clima_dependencias.jefe_1 
            WHERE 
                tbl_clima_dependencias.rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $datos = $connexion->resultset();
    return $datos;
}
function CLIMA_VeoSiTieneDependientesPAraSeccionMiEquipoVersion2($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT
            tbl_clima_dependencias.rut,
            tbl_clima_resultado_jefe.resultado as resultado, 
            nombre,
            cargo,
            ( SELECT count( id ) AS es_jefe FROM tbl_clima_dependencias AS a WHERE a.jefe_1 = tbl_clima_dependencias.rut ) AS jefe_o_no 
        FROM
            tbl_clima_dependencias 
            
            left join tbl_clima_resultado_jefe on tbl_clima_resultado_jefe.rut=tbl_clima_dependencias.rut
        WHERE
            jefe_1 = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $datos = $connexion->resultset();
    return $datos;
}

function TraeUsuariosConNombreNullTraigoDatosDesdeDependencias($rut)
{
    $connexion = new DatabasePDO();
    $sql = "select *  from tbl_clima_dependencias  where rut=:rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $datos = $connexion->resultset();
    return $datos;
}

function TraeUsuariosConNombreNull()
{
    $connexion = new DatabasePDO();
    $sql = "select *  from tbl_clima_resultado_jefe  where (nombre_jefe  is null or nombre_jefe ='') and resultado  is not null";
    $connexion->query($sql);
    $datos = $connexion->resultset();
    return $datos;
}

function DatosDesdeClimaDependencias($rut)
{
    $connexion = new DatabasePDO();
    $sql = "select nombre as nombre_completo from tbl_clima_dependencias where rut=:rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $datos = $connexion->resultset();
    return $datos;
}

function ValidaDependenciaJefeColaborador($colaborador, $jefe)
{
    $connexion = new DatabasePDO();
    $sql = "select * from tbl_clima_dependencias where rut=:colaborador and (jefe_1=:jefe or jefe_2=:jefe or jefe_3=:jefe or jefe_4=:jefe or jefe_5=:jefe or jefe_6=:jefe or jefe_7=:jefe)";
    $connexion->query($sql);
    $connexion->bind(':colaborador', $colaborador);
    $connexion->bind(':jefe', $jefe);
    $datos = $connexion->resultset();
    return $datos;
}

function AccesoAResultadoBanco($rut) {
	$connexion = new DatabasePDO();
	$sql = "SELECT * FROM tbl_clima_acceso_resultados_banco WHERE rut = :rut";
	$connexion->query($sql);
	$connexion->bind(':rut', $rut);
	$datos = $connexion->resultset();
	return $datos;
}
	
function ListadoJefeActualizaSoloNombre($rut, $nombre_completo) {
	$connexion = new DatabasePDO();
	$sql = "UPDATE tbl_clima_resultado_jefe SET nombre_jefe = :nombre_completo WHERE rut = :rut";
	$connexion->query($sql);
	$connexion->bind(':nombre_completo', $nombre_completo);
	$connexion->bind(':rut', $rut);
	$connexion->execute();
}
	
function ListadoJefeActualiza($rut, $resultado, $nombre_completo, $n_invitados_participar, $n_participantes, $tasa_respuesta, $resultado_banco, $resultado_jefatura, $resultado_companero, $resultado_apoyo, $resultado_ambito, $resultado_nivel_recomendacion, $recomendacion2, $recomendacion1) {
	$connexion = new DatabasePDO();
	$sql = "UPDATE tbl_clima_resultado_jefe SET nombre_jefe = :nombre_completo, resultado = :resultado, n_invitados_participar = :n_invitados_participar, n_participantes = :n_participantes, tasa_respuesta = :tasa_respuesta, resultado_banco = :resultado_banco, resultado_jefatura = :resultado_jefatura, resultado_companero = :resultado_companero, resultado_apoyo = :resultado_apoyo, resultado_ambito = :resultado_ambito, resultado_nivel_recomendacion = :resultado_nivel_recomendacion, recomendacion2 = :recomendacion2, recomendacion1 = :recomendacion1 WHERE rut = :rut";
	$connexion->query($sql);
	$connexion->bind(':nombre_completo', $nombre_completo);
	$connexion->bind(':resultado', $resultado);
	$connexion->bind(':n_invitados_participar', $n_invitados_participar);
	$connexion->bind(':n_participantes', $n_participantes);
	$connexion->bind(':tasa_respuesta', $tasa_respuesta);
	$connexion->bind(':resultado_banco', $resultado_banco);
	$connexion->bind(':resultado_jefatura', $resultado_jefatura);
	$connexion->bind(':resultado_companero', $resultado_companero);
	$connexion->bind(':resultado_apoyo', $resultado_apoyo);
	$connexion->bind(':resultado_ambito', $resultado_ambito);
	$connexion->bind(':resultado_nivel_recomendacion', $resultado_nivel_recomendacion);
	$connexion->bind(':recomendacion2', $recomendacion2);
	$connexion->bind(':recomendacion1', $recomendacion1);
	$connexion->bind(':rut', $rut);
	$connexion->execute();
}
function ListadoJefes(){
    $connexion = new DatabasePDO();
    $sql = "SELECT tbl_clima_resultado_jefe.*, nombre_completo
            FROM tbl_clima_resultado_jefe 
            LEFT JOIN tbl_usuario ON tbl_usuario.rut = tbl_clima_resultado_jefe.rut 
            WHERE tbl_clima_resultado_jefe.rut = '9672746'";
    $connexion->query($sql);
    $datos = $connexion->resultset();
    return $datos;    
}


function AccesoAEstructura($rut){
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_clima_acceso_estructura WHERE rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $datos = $connexion->resultset();
    return $datos;    
}


function AccesoAReportesDirectos($rut){
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_clima_acceso_miequipo WHERE rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $datos = $connexion->resultset();
    return $datos;    
}


function AccesoAProfundizacion($rut){
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_clima_acceso_profundizacion WHERE rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $datos = $connexion->resultset();
    return $datos;    
}

function ClimaCategoriasPorEvaluacion($id_evaluacion, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1, $connexion) {

    if($cod_division_ajustado){
        $filtro_cod_division_ajustado = "and tbl_clima_dot_enc.cod_division_ajustado='" . Encodeartk($cod_division_ajustado) . "'";
    }

    if($cod_area_ajustado){
        $filtro_cod_area_ajustado = " and tbl_clima_dot_enc.cod_area_ajustado='" . Encodeartk($cod_area_ajustado) . "'";
    }

    if($cod_depto_ajustado){
        $filtro_cod_depto_ajustado = " and tbl_clima_dot_enc.cod_depto_ajustado='" . Encodeartk($cod_depto_ajustado) . "'";
    }

    if($cod_zona_ajustado){
        $filtro_cod_zona_ajustado = " and tbl_clima_dot_enc.cod_zona_ajustado='" . Encodeartk($cod_zona_ajustado) . "'";
    }

    if($cod_secc_ajustado){
        $filtro_cod_secc_ajustado = " and tbl_clima_dot_enc.cod_secc_ajustado='" . Encodeartk($cod_secc_ajustado) . "'";
    }

    if($unidad_cuipr_ajustado){
        $filtro_unidad_cuipr_ajustado = " and tbl_clima_dot_enc.unidad_cuipr_ajustado='" . Encodeartk($unidad_cuipr_ajustado) . "'";
    }

    if($cod_sucursal_ajustado){
        $filtro_cod_sucursal_ajustado = " and tbl_clima_dot_enc.cod_sucursal_ajustado='" . Encodeartk($cod_sucursal_ajustado) . "'";
    }

    if($sexo){
        $filtro_sexo = "and tbl_clima_dot_enc.sexo='" . Encodeartk($sexo) . "'";
    }

    if($rut_jefe){
        $filtro_rut_jefe = "and (tbl_clima_dot_enc.jefe_1='" . Encodeartk($rut_jefe) . "' or jefe_2='" . Encodeartk($rut_jefe) . "' or jefe_3='" . Encodeartk($rut_jefe) . "' or jefe_4='" . Encodeartk($rut_jefe) . "' or jefe_5='" . Encodeartk($rut_jefe) . "' or jefe_6='" . Encodeartk($rut_jefe) . "' or jefe_7='" . Encodeartk($rut_jefe) . "')";
    }

    if($rut_jefe_nivel1){
        $filtro_rut_jefe_nivel1 = "and (tbl_clima_dot_enc.jefe_1='" . Encodeartk($rut_jefe_nivel1) . "')";
    }
	$connexion = new DatabasePDO();
	$sql = "SELECT tbl_clima_categoria.*,
	(SELECT tbl_clima_pregunta.id FROM tbl_clima_pregunta WHERE tbl_clima_pregunta.id_parte = 1 AND tbl_clima_pregunta.id_categoria = tbl_clima_categoria.id_categoria) AS id_pregunta_por_di,
	(SELECT count(id) AS total FROM tbl_clima_respuestas INNER JOIN tbl_clima_dot_enc ON tbl_clima_dot_enc.num_rut = tbl_clima_respuestas.rut $filtro_cod_division_ajustado $filtro_sexo $filtro_cod_area_ajustado $filtro_cod_depto_ajustado  $filtro_cod_zona_ajustado $filtro_cod_secc_ajustado $filtro_unidad_cuipr_ajustado $filtro_cod_sucursal_ajustado $filtro_rut_jefe $filtro_rut_jefe_nivel1 WHERE tbl_clima_respuestas.VALUE = '5' AND tbl_clima_respuestas.id_pregunta = id_pregunta_por_di) AS resp_nivel_5,
	(SELECT count(id) AS total FROM tbl_clima_respuestas INNER JOIN tbl_clima_dot_enc ON tbl_clima_dot_enc.num_rut = tbl_clima_respuestas.rut $filtro_cod_division_ajustado $filtro_sexo $filtro_cod_area_ajustado $filtro_cod_depto_ajustado  $filtro_cod_zona_ajustado $filtro_cod_secc_ajustado $filtro_unidad_cuipr_ajustado $filtro_cod_sucursal_ajustado $filtro_rut_jefe $filtro_rut_jefe_nivel1 WHERE tbl_clima_respuestas.id_pregunta = id_pregunta_por_di) AS resp_total 
	FROM tbl_clima_categoria 
	WHERE id_evaluacion = :id_evaluacion";

	$connexion->query($sql);
	$connexion->bind(':id_evaluacion', $id_evaluacion);


	$datos = $connexion->resultset();
	return $datos;
}

function TotalUsuariosRespondidos($cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1){
	if($cod_area_ajustado){
		$filtro_cod_area_ajustado=" and tbl_clima_dot_enc.cod_area_ajustado='".Encodeartk($cod_area_ajustado)."'";
		}
		
		if($cod_division_ajustado){
			$filtro_cod_division_ajustado=" and tbl_clima_dot_enc.cod_division_ajustado='".Encodeartk($cod_division_ajustado)."'";
		}
		
		if($cod_depto_ajustado){
			$filtro_cod_depto_ajustado=" and tbl_clima_dot_enc.cod_depto_ajustado='".Encodeartk($cod_depto_ajustado)."'";
		}
		
		if($cod_zona_ajustado){
			$filtro_cod_zona_ajustado=" and tbl_clima_dot_enc.cod_zona_ajustado='".Encodeartk($cod_zona_ajustado)."'";
		}
		
		if($cod_secc_ajustado){
			$filtro_cod_secc_ajustado=" and tbl_clima_dot_enc.cod_secc_ajustado='".Encodeartk($cod_secc_ajustado)."'";
		}
		
		if($unidad_cuipr_ajustado){
			$filtro_unidad_cuipr_ajustado=" and tbl_clima_dot_enc.unidad_cuipr_ajustado='".Encodeartk($unidad_cuipr_ajustado)."'";
		}
		
		if($cod_sucursal_ajustado){
			$filtro_cod_sucursal_ajustado=" and tbl_clima_dot_enc.cod_sucursal_ajustado='".Encodeartk($cod_sucursal_ajustado)."'";
		}
		
		if($sexo){
			$filtro_sexo="and tbl_clima_dot_enc.sexo='".Encodeartk($sexo)."'";
		}
		
		if($rut_jefe){
			$filtro_rut_jefe="and (tbl_clima_dot_enc.jefe_1='".Encodeartk($rut_jefe)."' or jefe_2='".Encodeartk($rut_jefe)."' or jefe_3='".Encodeartk($rut_jefe)."' or jefe_4='".Encodeartk($rut_jefe)."' or jefe_5='".Encodeartk($rut_jefe)."' or jefe_6='".Encodeartk($rut_jefe)."' or jefe_7='".Encodeartk($rut_jefe)."')";
		}
		
		if($rut_jefe_nivel1){
			$filtro_rut_jefe_nivel1="and (tbl_clima_dot_enc.jefe_1='".Encodeartk($rut_jefe_nivel1)."')";
		}
		
	
	$connexion = new DatabasePDO();
	
	$sql = "select count(distinct(rut)) as total from tbl_clima_respuestas inner join tbl_clima_dot_enc on tbl_clima_dot_enc.num_rut=tbl_clima_respuestas.rut $filtro_cod_division_ajustado $filtro_sexo $filtro_cod_area_ajustado $filtro_cod_depto_ajustado $filtro_cod_zona_ajustado $filtro_cod_secc_ajustado $filtro_unidad_cuipr_ajustado $filtro_cod_sucursal_ajustado  $filtro_rut_jefe $filtro_rut_jefe_nivel1 ";
	
	
	$connexion->query($sql);
	$datos = $connexion->resultset();
	return $datos;
}
	
function TotalUsuariosFiltrados($cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1){

	$connexion = new DatabasePDO();
	
	if($cod_area_ajustado){
		$filtro_cod_area_ajustado=" and tbl_clima_dot_enc.cod_area_ajustado=:cod_area_ajustado";
	}
	
	if($cod_division_ajustado){
		$filtro_cod_division_ajustado=" and tbl_clima_dot_enc.cod_division_ajustado=:cod_division_ajustado";
	}
		
	if($cod_depto_ajustado){
		$filtro_cod_depto_ajustado=" and tbl_clima_dot_enc.cod_depto_ajustado=:cod_depto_ajustado";
	}
	
	if($cod_zona_ajustado){
		$filtro_cod_zona_ajustado=" and tbl_clima_dot_enc.cod_zona_ajustado=:cod_zona_ajustado";
	}
	
	if($cod_secc_ajustado){
		$filtro_cod_secc_ajustado=" and tbl_clima_dot_enc.cod_secc_ajustado=:cod_secc_ajustado";
	}
	
	if($unidad_cuipr_ajustado){
		$filtro_unidad_cuipr_ajustado=" and tbl_clima_dot_enc.unidad_cuipr_ajustado=:unidad_cuipr_ajustado";
	}
	
	if($cod_sucursal_ajustado){
		$filtro_cod_sucursal_ajustado=" and tbl_clima_dot_enc.cod_sucursal_ajustado=:cod_sucursal_ajustado";
	}
		
	if($sexo){
		$filtro_sexo="and tbl_clima_dot_enc.sexo=:sexo";
	}
	
	if($rut_jefe){
		$filtro_rut_jefe="and (tbl_clima_dot_enc.jefe_1=:rut_jefe or jefe_2=:rut_jefe or jefe_3=:rut_jefe or jefe_4=:rut_jefe or jefe_5=:rut_jefe or jefe_6=:rut_jefe or jefe_7=:rut_jefe)";
	
	}
		
	if($rut_jefe_nivel1){
		$filtro_rut_jefe_nivel1="and (tbl_clima_dot_enc.jefe_1=:rut_jefe_nivel1)";
	}
	
	$sql = "
	SELECT
	count(*) AS total
	FROM
	tbl_clima_dot_enc
	where num_rut<>''
	$filtro_cod_division_ajustado $filtro_sexo $filtro_cod_area_ajustado
	$filtro_cod_depto_ajustado $filtro_cod_zona_ajustado $filtro_cod_secc_ajustado $filtro_unidad_cuipr_ajustado $filtro_cod_sucursal_ajustado $filtro_rut_jefe $filtro_rut_jefe_nivel1 ";

	
	$connexion->query($sql);

	if($cod_division_ajustado){
	$connexion->bind(':cod_division_ajustado', Encodeartk($cod_division_ajustado));
	}

	if($sexo){
	$connexion->bind(':sexo', Encodeartk($sexo));
	}

	if($cod_area_ajustado){
	$connexion->bind(':cod_area_ajustado', Encodeartk($cod_area_ajustado));
	}

	if($cod_depto_ajustado){
	$connexion->bind(':cod_depto_ajustado', Encodeartk($cod_depto_ajustado));
	}

	if($cod_zona_ajustado){
	$connexion->bind(':cod_zona_ajustado', Encodeartk($cod_zona_ajustado));
	}

	if($cod_secc_ajustado){
	$connexion->bind(':cod_secc_ajustado', Encodeartk($cod_secc_ajustado));
	}

	if($unidad_cuipr_ajustado){
	$connexion->bind(':unidad_cuipr_ajustado', Encodeartk($unidad_cuipr_ajustado));
	}

	if($cod_sucursal_ajustado){
	$connexion->bind(':cod_sucursal_ajustado', Encodeartk($cod_sucursal_ajustado));
	}

	if($rut_jefe){
	$connexion->bind(':rut_jefe', Encodeartk($rut_jefe));
	}

	if($rut_jefe_nivel1){
	$connexion->bind(':rut_jefe_nivel1', Encodeartk($rut_jefe_nivel1));
	}

	$result = $connexion->resultset();
	return $result;
}

function ClimaRecomendacion1($cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1){
	
	if($cod_division_ajustado){
		$filtro_cod_division_ajustado="and tbl_clima_dot_enc.cod_division_ajustado='".Encodeartk($cod_division_ajustado)."'";
	}
	
	if($cod_area_ajustado){
		$filtro_cod_area_ajustado=" and tbl_clima_dot_enc.cod_area_ajustado='".Encodeartk($cod_area_ajustado)."'";
	}
	
	if($cod_depto_ajustado){
		$filtro_cod_depto_ajustado=" and tbl_clima_dot_enc.cod_depto_ajustado='".Encodeartk($cod_depto_ajustado)."'";
	}
	
	if($cod_zona_ajustado){
		$filtro_cod_zona_ajustado=" and tbl_clima_dot_enc.cod_zona_ajustado='".Encodeartk($cod_zona_ajustado)."'";
	}
	
	if($cod_secc_ajustado){
		$filtro_cod_secc_ajustado=" and tbl_clima_dot_enc.cod_secc_ajustado='".Encodeartk($cod_secc_ajustado)."'";
	}
	
	if($unidad_cuipr_ajustado){
		$filtro_unidad_cuipr_ajustado=" and tbl_clima_dot_enc.unidad_cuipr_ajustado='".Encodeartk($unidad_cuipr_ajustado)."'";
	}
	
	if($cod_sucursal_ajustado){
		$filtro_cod_sucursal_ajustado=" and tbl_clima_dot_enc.cod_sucursal_ajustado='".Encodeartk($cod_sucursal_ajustado)."'";
	}
	
	if($sexo){
		$filtro_sexo="and tbl_clima_dot_enc.sexo='".Encodeartk($sexo)."'";
	}
	
	if($rut_jefe){
		$filtro_rut_jefe="and (tbl_clima_dot_enc.jefe_1='".Encodeartk($rut_jefe)."' or jefe_2='".Encodeartk($rut_jefe)."' or jefe_3='".Encodeartk($rut_jefe)."' or jefe_4='".Encodeartk($rut_jefe)."' or jefe_5='".Encodeartk($rut_jefe)."' or jefe_6='".Encodeartk($rut_jefe)."' or jefe_7='".Encodeartk($rut_jefe)."')";
	}
	
	if($rut_jefe_nivel1){
		$filtro_rut_jefe_nivel1="and (tbl_clima_dot_enc.jefe_1='".Encodeartk($rut_jefe_nivel1)."')";
	}
	$connexion = new DatabasePDO();
	$sql = "select count(id) as total from tbl_clima_respuestas inner join tbl_clima_dot_enc on tbl_clima_dot_enc.num_rut=tbl_clima_respuestas.rut $filtro_cod_division_ajustado $filtro_sexo $filtro_cod_area_ajustado $filtro_cod_depto_ajustado  $filtro_cod_zona_ajustado $filtro_cod_secc_ajustado $filtro_unidad_cuipr_ajustado $filtro_cod_sucursal_ajustado  $filtro_rut_jefe $filtro_rut_jefe_nivel1 where id_pregunta='22' and (value='1' or value='2' or value='3' or value='4' or value='5' or value='6');";
    
    $connexion->query($sql);
    $result = $connexion->resultset();
    return $result;
}
	
function ClimaRecomendacion3($cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1){

    $connexion = new DatabasePDO();
	
	if($cod_division_ajustado){
		$filtro_cod_division_ajustado="and tbl_clima_dot_enc.cod_division_ajustado=:cod_division_ajustado";
        $connexion->bind(':cod_division_ajustado', Encodeartk($cod_division_ajustado));
	}
	
	if($cod_area_ajustado){
		$filtro_cod_area_ajustado=" and tbl_clima_dot_enc.cod_area_ajustado=:cod_area_ajustado";
        $connexion->bind(':cod_area_ajustado', Encodeartk($cod_area_ajustado));
	}
	
	if($cod_depto_ajustado){
		$filtro_cod_depto_ajustado=" and tbl_clima_dot_enc.cod_depto_ajustado=:cod_depto_ajustado";
        $connexion->bind(':cod_depto_ajustado', Encodeartk($cod_depto_ajustado));
	}
	
	if($cod_zona_ajustado){
		$filtro_cod_zona_ajustado=" and tbl_clima_dot_enc.cod_zona_ajustado=:cod_zona_ajustado";
        $connexion->bind(':cod_zona_ajustado', Encodeartk($cod_zona_ajustado));
	}
	
	if($cod_secc_ajustado){
		$filtro_cod_secc_ajustado=" and tbl_clima_dot_enc.cod_secc_ajustado=:cod_secc_ajustado";
        $connexion->bind(':cod_secc_ajustado', Encodeartk($cod_secc_ajustado));
	}
	
	if($unidad_cuipr_ajustado){
		$filtro_unidad_cuipr_ajustado=" and tbl_clima_dot_enc.unidad_cuipr_ajustado=:unidad_cuipr_ajustado";
        $connexion->bind(':unidad_cuipr_ajustado', Encodeartk($unidad_cuipr_ajustado));
	}
	
	if($cod_sucursal_ajustado){
		$filtro_cod_sucursal_ajustado=" and tbl_clima_dot_enc.cod_sucursal_ajustado=:cod_sucursal_ajustado";
        $connexion->bind(':cod_sucursal_ajustado', Encodeartk($cod_sucursal_ajustado));
	}
	
	if($sexo){
		$filtro_sexo="and tbl_clima_dot_enc.sexo=:sexo"; 
        $connexion->bind(':sexo', Encodeartk($sexo));
	}
	
	if($rut_jefe){
		$filtro_rut_jefe="and (tbl_clima_dot_enc.jefe_1=:rut_jefe or jefe_2=:rut_jefe or jefe_3=:rut_jefe or jefe_4=:rut_jefe or jefe_5=:rut_jefe or jefe_6=:rut_jefe or jefe_7=:rut_jefe)";
        $connexion->bind(':rut_jefe', Encodeartk($rut_jefe));
	}
		
    if($rut_jefe_nivel1){
        $filtro_rut_jefe_nivel1="and (tbl_clima_dot_enc.jefe_1=:rut_jefe_nivel1)";
        $connexion->bind(':rut_jefe_nivel1', Encodeartk($rut_jefe_nivel1));
    }
	
    $sql = "select count(id) as total from tbl_clima_respuestas inner join tbl_clima_dot_enc on tbl_clima_dot_enc.num_rut=tbl_clima_respuestas.rut where id_pregunta='22' and (value='7') $filtro_cod_division_ajustado $filtro_sexo $filtro_cod_area_ajustado $filtro_cod_depto_ajustado $filtro_cod_zona_ajustado $filtro_cod_secc_ajustado $filtro_unidad_cuipr_ajustado $filtro_cod_sucursal_ajustado $filtro_rut_jefe $filtro_rut_jefe_nivel1";
	
    $connexion->query($sql);
    $datos = $connexion->resultset();
    return $datos;	
	
}

function ClimaRecomendacion2($cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1) {
	$connexion = new DatabasePDO();

	if($cod_division_ajustado){
		$filtro_cod_division_ajustado="and tbl_clima_dot_enc.cod_division_ajustado=:cod_division_ajustado";
        $connexion->bind(':cod_division_ajustado', Encodeartk($cod_division_ajustado));
	}
	
	if($cod_area_ajustado){
		$filtro_cod_area_ajustado=" and tbl_clima_dot_enc.cod_area_ajustado=:cod_area_ajustado";
        $connexion->bind(':cod_area_ajustado', Encodeartk($cod_area_ajustado));
	}
	
	if($cod_depto_ajustado){
		$filtro_cod_depto_ajustado=" and tbl_clima_dot_enc.cod_depto_ajustado=:cod_depto_ajustado";
        $connexion->bind(':cod_depto_ajustado', Encodeartk($cod_depto_ajustado));
	}
	
	if($cod_zona_ajustado){
		$filtro_cod_zona_ajustado=" and tbl_clima_dot_enc.cod_zona_ajustado=:cod_zona_ajustado";
        $connexion->bind(':cod_zona_ajustado', Encodeartk($cod_zona_ajustado));
	}
	
	if($cod_secc_ajustado){
		$filtro_cod_secc_ajustado=" and tbl_clima_dot_enc.cod_secc_ajustado=:cod_secc_ajustado";
        $connexion->bind(':cod_secc_ajustado', Encodeartk($cod_secc_ajustado));
	}
	
	if($unidad_cuipr_ajustado){
		$filtro_unidad_cuipr_ajustado=" and tbl_clima_dot_enc.unidad_cuipr_ajustado=:unidad_cuipr_ajustado";
        $connexion->bind(':unidad_cuipr_ajustado', Encodeartk($unidad_cuipr_ajustado));
	}
	
	if($cod_sucursal_ajustado){
		$filtro_cod_sucursal_ajustado=" and tbl_clima_dot_enc.cod_sucursal_ajustado=:cod_sucursal_ajustado";
        $connexion->bind(':cod_sucursal_ajustado', Encodeartk($cod_sucursal_ajustado));
	}
	
	if($sexo){
		$filtro_sexo="and tbl_clima_dot_enc.sexo=:sexo"; 
        $connexion->bind(':sexo', Encodeartk($sexo));
	}
	
	if($rut_jefe){
		$filtro_rut_jefe="and (tbl_clima_dot_enc.jefe_1=:rut_jefe or jefe_2=:rut_jefe or jefe_3=:rut_jefe or jefe_4=:rut_jefe or jefe_5=:rut_jefe or jefe_6=:rut_jefe or jefe_7=:rut_jefe)";
        $connexion->bind(':rut_jefe', Encodeartk($rut_jefe));
	}
		
    if($rut_jefe_nivel1){
        $filtro_rut_jefe_nivel1="and (tbl_clima_dot_enc.jefe_1=:rut_jefe_nivel1)";
        $connexion->bind(':rut_jefe_nivel1', Encodeartk($rut_jefe_nivel1));
    }

	$sql = "select count(id) as total from tbl_clima_respuestas inner join tbl_clima_dot_enc on tbl_clima_dot_enc.num_rut=tbl_clima_respuestas.rut $filtro_cod_division_ajustado $filtro_sexo $filtro_cod_area_ajustado $filtro_cod_depto_ajustado $filtro_cod_zona_ajustado $filtro_cod_secc_ajustado $filtro_unidad_cuipr_ajustado $filtro_cod_sucursal_ajustado $filtro_rut_jefe $filtro_rut_jefe_nivel1 where id_pregunta='22' and (value='8' or value='9' or value='10')";
	
    $connexion->query($sql);
    $datos = $connexion->resultset();
    return $datos;	
}

function RespuestasProfundizacion($id_pregunta, $opcion, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1){
	$connexion = new DatabasePDO();

	if($cod_division_ajustado){
		$filtro_cod_division_ajustado="and tbl_clima_dot_enc.cod_division_ajustado=:cod_division_ajustado";
        $connexion->bind(':cod_division_ajustado', Encodeartk($cod_division_ajustado));
	}
	
	if($cod_area_ajustado){
		$filtro_cod_area_ajustado=" and tbl_clima_dot_enc.cod_area_ajustado=:cod_area_ajustado";
        $connexion->bind(':cod_area_ajustado', Encodeartk($cod_area_ajustado));
	}
	
	if($cod_depto_ajustado){
		$filtro_cod_depto_ajustado=" and tbl_clima_dot_enc.cod_depto_ajustado=:cod_depto_ajustado";
        $connexion->bind(':cod_depto_ajustado', Encodeartk($cod_depto_ajustado));
	}
	
	if($cod_zona_ajustado){
		$filtro_cod_zona_ajustado=" and tbl_clima_dot_enc.cod_zona_ajustado=:cod_zona_ajustado";
        $connexion->bind(':cod_zona_ajustado', Encodeartk($cod_zona_ajustado));
	}
	
	if($cod_secc_ajustado){
		$filtro_cod_secc_ajustado=" and tbl_clima_dot_enc.cod_secc_ajustado=:cod_secc_ajustado";
        $connexion->bind(':cod_secc_ajustado', Encodeartk($cod_secc_ajustado));
	}

	if($unidad_cuipr_ajustado){
		$filtro_unidad_cuipr_ajustado=" and tbl_clima_dot_enc.unidad_cuipr_ajustado=:unidad_cuipr_ajustado";
        $connexion->bind(':unidad_cuipr_ajustado', Encodeartk($unidad_cuipr_ajustado));
	}
	
	if($cod_sucursal_ajustado){
		$filtro_cod_sucursal_ajustado=" and tbl_clima_dot_enc.cod_sucursal_ajustado=:cod_sucursal_ajustado";
        $connexion->bind(':cod_sucursal_ajustado', Encodeartk($cod_sucursal_ajustado));
	}
	
	if($sexo){
		$filtro_sexo="and tbl_clima_dot_enc.sexo=:sexo"; 
        $connexion->bind(':sexo', Encodeartk($sexo));
	}
	
	if($rut_jefe){
		$filtro_rut_jefe="and (tbl_clima_dot_enc.jefe_1=:rut_jefe or jefe_2=:rut_jefe or jefe_3=:rut_jefe or jefe_4=:rut_jefe or jefe_5=:rut_jefe or jefe_6=:rut_jefe or jefe_7=:rut_jefe)";
        $connexion->bind(':rut_jefe', Encodeartk($rut_jefe));
	}
		
    if($rut_jefe_nivel1){
        $filtro_rut_jefe_nivel1="and (tbl_clima_dot_enc.jefe_1=:rut_jefe_nivel1)";
        $connexion->bind(':rut_jefe_nivel1', Encodeartk($rut_jefe_nivel1));
    }

	
    if($opcion){
        $filtro_opcion="and value like :opcion";
        $connexion->bind(':opcion', '%' . $opcion . ';%');
    }
    $sql = "select count(tbl_clima_respuestas.id) as total from tbl_clima_respuestas 
        inner join tbl_clima_dot_enc on tbl_clima_dot_enc.num_rut=tbl_clima_respuestas.rut 
        $filtro_cod_division_ajustado $filtro_sexo $filtro_cod_area_ajustado 
        $filtro_cod_depto_ajustado  $filtro_cod_zona_ajustado $filtro_cod_secc_ajustado $filtro_unidad_cuipr_ajustado $filtro_cod_sucursal_ajustado  $filtro_rut_jefe $filtro_rut_jefe_nivel1
        where id_pregunta=:id_pregunta $filtro_opcion ;";
    
    $connexion->query($sql);
    $connexion->bind(':id_pregunta', $id_pregunta);
    $datos = $connexion->resultset();
    return $datos;    
}


?>