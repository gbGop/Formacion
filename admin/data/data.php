<?php
function UsuarioEnBasePersonas($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_usuario WHERE rut = :rut AND rut <> '' AND vigencia = '0'";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    $rutKey = $cod[0]->rut;
    if ($rutKey == "") {
        $sql = "SELECT * FROM tbl_usuario WHERE email = :rut AND email <> '' AND vigencia = '0'";
        $connexion->query($sql);
        $connexion->bind(':rut', $rut);
        $cod = $connexion->resultset();
        $rutKey = $cod[0]->rut;
        if ($rutKey == "") {
            $order = array(".", "-", " ");
            $replace = '';
            $rut_contodo_limipio = str_replace($order, $replace, $rut);
            $sql = "SELECT * FROM tbl_usuario WHERE rut = :rut_contodo_limipio AND rut <> '' AND vigencia = '0'";
            $connexion->query($sql);
            $connexion->bind(':rut_contodo_limipio', $rut_contodo_limipio);
            $cod = $connexion->resultset();
            $rutKey = $cod[0]->rut;
            if ($rutKey == "") {
                $order = array(".", "-", " ");
                $replace = '';
                $rut_contodo_limipio = str_replace($order, $replace, $rut);
                $cuentacaracteres = strlen($rut_contodo_limipio);
                $cuentacaracteresmenosuno = $cuentacaracteres - 1;
                $rut_limpio_sindigito = substr($rut_contodo_limipio, 0, $cuentacaracteresmenosuno);
                $sql = "SELECT * FROM tbl_usuario WHERE rut = :rut_limpio_sindigito AND rut <> '' AND vigencia = '0'";
                $connexion->query($sql);
                $connexion->bind(':rut_limpio_sindigito', $rut_limpio_sindigito);
                $cod = $connexion->resultset();
                return $cod;
            }
            return $cod;
        } else {
            return $cod;
        }
    } else {
        return $cod;
    } exit;
}
function DataContable($id_imparticion)
{
    $connexion = new DatabasePDO();
    $sql = "

select h.*,
p.cuenta as CUENTA,
p.cuenta_glosa as CUENTA_GLOSA,
cu.cui as CUI,
p.nombre_programa as PROYECTO,
ei.email as EMAIL1,
ee.email as EMAIL2

from tbl_inscripcion_curso h
left join tbl_lms_curso c on c.id=h.id_curso
left join tbl_lms_programas_bbdd p on p.id_programa=c.id_empresa_programa
left join tbl_lms_foco f on f.codigo_foco=c.id_foco
left join tbl_finanza_cuenta cu on cu.cuenta_num=p.cuenta
left join tbl_usuario ei on ei.rut=h.ejecutivo
left join tbl_usuario ee on ee.rut=h.archivo

WHERE

h.codigo_inscripcion='$id_imparticion'

";
    //echo $sql;
    $connexion->query($sql);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;
}
function ListMallaBch()
{
    $connexion = new DatabasePDO();
    $sql = "select nombre from tbl_programa where archivo='malla_bch' and certificacion ='0' ORDER BY id ";
    //echo $sql;
    $connexion->query($sql);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;
}
function ListCategoriaBch()
{
    $connexion = new DatabasePDO();
    $sql = "select nombre from tbl_programa where archivo='categoria_bch' and certificacion ='0' ORDER BY id";
    //echo $sql;
    $connexion->query($sql);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;
}
function Downloadlistcursos1_2024()
{
    $connexion = new DatabasePDO();
    $sql = "SELECT 

                h.id as CODIGO_CURSO,
                h.nombre as NOMBRE_CURSO,
                f.descripcion as EJE,
                p.nombre_programa as PROYECTO,
                p.cuenta as CUENTA,
                p.cuenta_glosa as CUENTA_GLOSA,
                c.cui as CUI,
                h.acercade as DIVISION,
                h.rut_otec as RUT_PROVEEDOR,
                h.numero_horas as HORAS_CURSO
                
                from tbl_lms_curso h
                left join tbl_lms_foco f on f.codigo_foco=h.id_foco
                left join tbl_lms_programas_bbdd p on p.id_programa=h.id_empresa_programa
                left join tbl_finanza_cuenta c on c.cuenta_num=p.cuenta
                
                WHERE
                
                h.modalidad='1'
                and h.activo='0'
                and h.inactivo='0'
                and h.id_empresa='78'";
    $connexion->query($sql);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;
}
function UpdateActivarPrograma_2024($id_cambiar_estado_programa, $var_activar) {
    $connexion = new DatabasePDO();
    $hoy = date("Y-m-d");
    $variable = ($var_activar == "1") ? "inactivo=NULL" : "inactivo='1'";
    $sql = "UPDATE tbl_lms_programas_bbdd SET $variable WHERE id = :id";
    $connexion->query($sql);
    $connexion->bind(':id', $id_cambiar_estado_programa);
    $connexion->execute();
}
function UpdateCierreOta_2024($id_inscripcion) {
    $connexion = new DatabasePDO();
    $sql = "UPDATE tbl_inscripcion_curso SET numero_dias=1  WHERE codigo_inscripcion = '$id_inscripcion'";
    $connexion->query($sql);
    $connexion->execute();
}
function UpdateAbreOta_2024($id_inscripcion) {
    $connexion = new DatabasePDO();
    $sql = "UPDATE tbl_inscripcion_curso SET numero_dias=NULL  WHERE codigo_inscripcion = '$id_inscripcion'";
    $connexion->query($sql);
    $connexion->execute();
}
function UpdateActivarMallaCategoria_2024($id_cambiar_estado_programa, $var_activar) {
    $connexion = new DatabasePDO();
    $hoy = date("Y-m-d");
    $variable = ($var_activar == "1") ? "certificacion=0" : "certificacion='1'";
    $sql = "UPDATE tbl_programa SET $variable WHERE id = :id";
    $connexion->query($sql);
    $connexion->bind(':id', $id_cambiar_estado_programa);
    $connexion->execute();
}
function TraeDivisionesMandante()
{
    $connexion = new DatabasePDO();
    $sql = "SELECT division,silla_id_division FROM tbl_usuario where silla_id_division is not null group by silla_id_division order by division ";
    $connexion->query($sql);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;
}
function SelectCuentaContableDadoCurso_ProyectoEje2024($id_curso)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*,
        p.cuenta as CUENTA,
        p.cuenta_glosa as CUENTA_GLOSA,
        f.descripcion as EJE,
        p.nombre_programa as PROYECTO

        FROM tbl_lms_curso h
        
        left join tbl_lms_programas_bbdd p on p.id_programa=h.id_empresa_programa
        left join tbl_lms_foco f on f.codigo_foco=h.id_foco
        WHERE   h.id='$id_curso' ";
    $connexion->query($sql);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;
}
function OtaCerrada2024($id_inscripcion)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT numero_dias  FROM tbl_inscripcion_curso  WHERE codigo_inscripcion = '$id_inscripcion'";
     //   echo $sql;
    $connexion->query($sql);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod[0]->numero_dias;
}
function TraeProgramasBBDDById($id_programa)
{
    $connexion = new DatabasePDO();

    $sql = "SELECT * FROM tbl_lms_programas_bbdd WHERE id_programa = :id_programa limit 1";
    $connexion->query($sql);
    $connexion->bind(':id_programa', $id_programa);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;
}
function Trae_otec_Search_Data($search){
    //echo $search;
    $connexion = new DatabasePDO();
   /* $sql="SELECT tbl_otec.*, 
       MATCH(tbl_otec.nombre) AGAINST (:search) AS relevance_nombre,
       MATCH(tbl_otec.rut) AGAINST (:search) AS relevance_rut
  FROM tbl_otec
        WHERE MATCH(tbl_otec.nombre) AGAINST (:search) > 0
        OR  MATCH(tbl_otec.rut) AGAINST (:search) > 0
 
        ORDER BY 

 
    relevance_nombre DESC, 
    relevance_rut DESC,
    tbl_otec.nombre ASC;"; */
	$sql=" 
		SELECT h.*,
		MATCH(h.nombre) AGAINST (:search) AS relevance_nombre,
		 MATCH(h.rut) AGAINST (:search) AS relevance_rut
		FROM tbl_otec h

		WHERE h.nombre LIKE :search_param or h.rut LIKE :search_param

		order by 
			relevance_nombre DESC, 
			relevance_rut DESC,
			h.nombre ASC;
	";
	
   //echo $sql;
    $search_param = "%$search%";
    $connexion->query($sql);
    $connexion->bind(':search_param', $search_param);
	$connexion->bind(':search', $search);
    $cod = $connexion->resultset();
    return $cod;
}
function MaxIdProgramas()
{
    $connexion = new DatabasePDO();

    $sql = "SELECT MAX(id_programa) as max from tbl_lms_programas_bbdd where id_empresa_programa is null";
    //echo $sql;
    $connexion->query($sql);
    $connexion->execute();
    $cod = $connexion->resultset();
    $max=$cod[0]->max+1;
    return $max;
}
function TraeCuiyCuentaContable($cuenta)
{
    $connexion = new DatabasePDO();

    $sql = "select cui from tbl_finanza_cuenta where cuenta_num='$cuenta'";
    //echo $sql;
    $connexion->query($sql);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod[0]->cui;
}
function Reportes_detalle_headers_Preguntas_respuestas($id_encuesta)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.* from tbl_enc_elearning_preg h WHERE h.id_encuesta='$id_encuesta'  ";
    //echo $sql; exit();
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}


function Inserta_rel_lms_rut_id_curso_id_inscripcion_2022($rut, $id_curso, $id_inscripcion, $nombre_inscripcion, $fecha_inicio_inscripcion, $fecha_termino_inscripcion, $id_empresa, $opcional, $activa, $rut_ejecutivo)
{

    $nombre_inscripcion = ($nombre_inscripcion);

	$connexion = new DatabasePDO();


    $sql2 = "select id from rel_lms_rut_id_curso_id_inscripcion where 
id_curso='" . $id_curso . "' and id_inscripcion='" . $id_inscripcion . "' and rut='" . $rut . "'";
//echo "<br>sql2 $sql2</br>";
	$connexion->query($sql2);
    $cod = $connexion->resultset();
    
//print_r($cod);exit();

    if ($cod[0]->id > 0) {
        $sql3 = "UPDATE rel_lms_rut_id_curso_id_inscripcion 

                set 
                
                rut='$rut',
                id_curso='$id_curso', 
                id_inscripcion='$id_inscripcion',
                nombre_inscripcion='$nombre_inscripcion',
                fecha_inicio_inscripcion='$fecha_inicio_inscripcion',
                fecha_termino_inscripcion='$fecha_termino_inscripcion',
                id_empresa='$id_empresa',
                opcional='$opcional',
                activa='$activa',
                rut_ejecutivo='$rut_ejecutivo'
                
                where id='" . $cod[0]->id . "'
                
                ";
	$connexion->query($sql3);
    $connexion->execute();

    } else {
        $sql3 = "INSERT INTO rel_lms_rut_id_curso_id_inscripcion (rut, id_curso, id_inscripcion, nombre_inscripcion,fecha_inicio_inscripcion,fecha_termino_inscripcion,id_empresa,opcional,activa,rut_ejecutivo) 
            VALUES ('$rut', '$id_curso', '$id_inscripcion', '$nombre_inscripcion','$fecha_inicio_inscripcion','$fecha_termino_inscripcion','$id_empresa','$opcional','$activa','$rut_ejecutivo');";
	$connexion->query($sql3);
    $connexion->execute();

    }


}
function BorraUsuariosParaVolveraCrearlos_2022_por_malla($id_imparticion)
{
    $connexion = new DatabasePDO();
    $sql = "delete from tbl_id_malla_id_inscripcion_rut where id_inscripcion_malla='$id_imparticion'";
	$connexion->query($sql);
    $connexion->execute();

}
function BorraUsuariosParaVolveraCrearlos($id_imparticion)
{
    $connexion = new DatabasePDO();
    $sql = "delete from rel_lms_rut_id_curso_id_inscripcion where id_inscripcion='$id_imparticion'";
	$connexion->query($sql);
    $connexion->execute();

}
function IMPARTICION_UsuariosPorInscripcion_DotacionIniciales($id_inscripcion)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.rut,j.id, h.id_curso
            FROM tbl_inscripcion_usuarios h
            left join tbl_inscripcion_usuarios_datos_dotacion_iniciales j on j.rut=h.rut
            where h.id_inscripcion='$id_inscripcion'
            and j.id is null
           ";
    //echo $sql; exit();
    $connexion->query($sql);
    //echo $sql;
    $cod = $connexion->resultset(); 
    return $cod;
}
function Imparticion_Tbl_Usuarios_Datos_Dotacion_2023($id_inscripcion,$id_curso, $rut, $nombre_completo, $cargo, $id_cargo, $id_division, $id_area, $id_unidad, $id_empresa, $fecha)
{
    $connexion = new DatabasePDO();
    /*$sql = "SELECT nombre FROM tbl_relatores WHERE rut='$key' limit 1";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    $nombre = $cod[0]->nombre;*/
    $sql = "INSERT INTO tbl_inscripcion_usuarios_datos_dotacion_iniciales (id_inscripcion,id_curso,rut,nombre_completo,cargo,id_cargo,id_division,id_area,id_unidad,id_empresa,fecha) VALUES ('$id_inscripcion','$id_curso','$rut','$nombre_completo','$cargo','$id_cargo','$id_division','$id_area','$id_unidad','$id_empresa','$fecha')";
    //echo $sql; exit();
    $connexion->query($sql);
    $connexion->execute();
}
function QR_Detalle_Download_Data($id_imparticion)
{
    $connexion = new DatabasePDO();
    $sql = "select  h.rut,
                    h.nombre as nombre_completo,
                    h.glosa_cargo as cargo,
                    h.fecha_ingreso,
                    h.division,
                    h.area,
                    h.departamento,
                    h.zona,
                    h.seccion,
                    h.unidad,
                    h.region,
                    h.empresa,
                    h.rut_jefe,
                    h.nombre_jefe,
                    h.id_foco as OTA,
                    c.nombre as nombre_imparticion,
                    h.id_encuesta,
                    h.id_pregunta,
                    p.pregunta,
                    h.respuesta,
                    h.fecha,
                    if(h.id_pregunta='3',
                        (SELECT GROUP_CONCAT(relator SEPARATOR ',') FROM tbl_id_inscripcion_encuesta_satisfaccion WHERE id_inscripcion = h.id_foco ORDER BY id)
                        ,'') as nombre_relatores
                    
                    
                    from tbl_enc_elearning_respuestas h
                    left join tbl_inscripcion_curso c on c.codigo_inscripcion=h.id_foco
                    inner join tbl_enc_elearning_preg p on p.id_encuesta=h.id_encuesta and p.id_pregunta=h.id_pregunta
                    
                    WHERE
                    
                    h.id_encuesta='90' and h.id_foco='$id_imparticion'
                    
                    order by h.rut, h.id_pregunta;  ";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}

function QR_Detalle_Download_Usuarios_por_Imparticion_Data($id_encuesta, $id_imparticion)
{
    $connexion = new DatabasePDO();
    if($id_imparticion<>""){
        $sql = "select  h.rut,
                    h.nombre as nombre_completo,
                    h.glosa_cargo as cargo,
                    h.fecha_ingreso,
                    h.division,
                    h.area,
                    h.departamento,
                    h.zona,
                    h.seccion,
                    h.unidad,
                    h.region,
                    h.empresa,
                    h.rut_jefe,
                    h.nombre_jefe,
                    h.id_foco as OTA,
                    c.nombre as nombre_imparticion,
                    h.id_encuesta,
                    h.fecha
                    
                    
                    from tbl_enc_elearning_respuestas h
                    left join tbl_inscripcion_curso c on c.codigo_inscripcion=h.id_foco
                    inner join tbl_enc_elearning_preg p on p.id_encuesta=h.id_encuesta and p.id_pregunta=h.id_pregunta
                    
                    WHERE
                    
                    h.id_encuesta='$id_encuesta' and h.id_foco='$id_imparticion'

									group by h.rut
                    
                    order by h.rut, h.id_pregunta;  ";
    } else {
        $sql = "select  h.rut,
                    h.nombre as nombre_completo,
                    h.glosa_cargo as cargo,
                    h.fecha_ingreso,
                    h.division,
                    h.area,
                    h.departamento,
                    h.zona,
                    h.seccion,
                    h.unidad,
                    h.region,
                    h.empresa,
                    h.rut_jefe,
                    h.nombre_jefe,
                    h.id_foco as OTA,
                    c.nombre as nombre_imparticion,
                    h.id_encuesta,
                    h.fecha
                    
                    
                    from tbl_enc_elearning_respuestas h
                    left join tbl_inscripcion_curso c on c.codigo_inscripcion=h.id_foco
                    inner join tbl_enc_elearning_preg p on p.id_encuesta=h.id_encuesta and p.id_pregunta=h.id_pregunta
                    
                    WHERE
                    
                    h.id_encuesta='$id_encuesta' 

					group by h.rut, h.id_foco, h.id_encuesta
                    
                    order by h.rut, h.id_pregunta;  ";
    }
    //echo $sql; exit();

    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}
function QR_Detalle_Download_Eventos_por_Imparticion_Data($id_encuesta)
{
    $connexion = new DatabasePDO();
        $sql = "SELECT
    h.id_foco AS OTA,
    c.nombre AS nombre_imparticion,
    c.id_curso,
    cu.nombre AS nombre_curso,
    c.direccion AS lugar_imparticion,
    c.fecha_termino AS fecha_ejecucion,
    ROUND(AVG(CASE WHEN h.id_pregunta = '1' THEN h.respuesta END), 1) AS promedio_preg_1,
    ROUND(AVG(CASE WHEN h.id_pregunta = '2' THEN h.respuesta END), 1) AS promedio_preg_2,
    ROUND(AVG(CASE WHEN h.id_pregunta = '4' THEN h.respuesta END), 1) AS promedio_preg_4,
    ROUND(AVG(CASE WHEN h.id_pregunta IN ('1', '2', '4') THEN h.respuesta END), 1) AS promedio_curso,
    ROUND(AVG(CASE WHEN h.id_pregunta = '3' THEN CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(h.respuesta, ';', n.n), ';', -1) AS UNSIGNED) END), 1) AS promedio_relatores,
    ROUND((COUNT(CASE WHEN h.id_pregunta = '5' AND h.respuesta = 'SI' THEN 1 END) / COUNT(CASE WHEN h.id_pregunta = '5' THEN 1 END)) * 100, 1) AS porcentaje_recomendacion,
    ROUND(AVG(CASE WHEN h.id_pregunta < '5' THEN CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(h.respuesta, ';', n.n), ';', -1) AS UNSIGNED) END), 1) AS promedio_final   
        
FROM
    tbl_enc_elearning_respuestas h
LEFT JOIN
    tbl_inscripcion_curso c ON c.codigo_inscripcion = h.id_foco
LEFT JOIN
    tbl_lms_curso cu ON cu.id = c.id_curso
JOIN (
    SELECT 1 AS n UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5
    UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10
) n ON CHAR_LENGTH(h.respuesta) - CHAR_LENGTH(REPLACE(h.respuesta, ';', '')) >= n.n - 1
WHERE
    h.id_encuesta = '$id_encuesta' 
GROUP BY
    h.id_foco
ORDER BY
    h.id_foco; ";
    
    //echo $sql; exit();

    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}
function QR_Detalle_Download_Usuarios_por_Detalle_Imparticion_Data($id_encuesta, $id_imparticion, $rut)
{
    $connexion = new DatabasePDO();
    $sql = "select  h.id_pregunta, j.tipo, h.respuesta

                    from tbl_enc_elearning_respuestas h 
										
                    left join  tbl_enc_elearning_preg j on j.id_pregunta=h.id_pregunta and j.id_encuesta=h.id_encuesta

                    WHERE h.id_encuesta='$id_encuesta' and h.id_foco='$id_imparticion' and h.rut='$rut'

                    order by h.id_pregunta  ";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}
function QR_Detalle_Download_Usuarios_por_Detalle_Imparticion_Eventos_Data($id_encuesta, $id_imparticion)
{
    $connexion = new DatabasePDO();
    $sql = "select  h.id_pregunta, j.tipo, h.respuesta

                    from tbl_enc_elearning_respuestas h 
										
                    left join  tbl_enc_elearning_preg j on j.id_pregunta=h.id_pregunta and j.id_encuesta=h.id_encuesta

                    WHERE h.id_encuesta='$id_encuesta' and h.id_foco='$id_imparticion'   and j.tipo='PREGUNTAS_RELATORES'

                    order by h.id_pregunta  ";
    $connexion->query($sql);
    
    $cod = $connexion->resultset();
    return $cod;
}

function Fecha2021_fecha_sesion($id_imparticion, $sesion)
{
    $connexion = new DatabasePDO();
    $sql = "select * from tbl_inscripcion_curso_sesiones_horarios where id_inscripcion='$id_imparticion' and sesion='$sesion'  ";
	$connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}

function ImparticionSesionesAsistenciaPorSesion_2021($id_imparticion, $rut, $ses_1, $ses_2, $ses_3, $ses_4, $ses_5, $ses_6, $ses_7, $ses_8, $ses_9, $ses_10, $ses_11, $ses_12)
{
	$suma_sesiones=0;
    $connexion = new DatabasePDO();
    $num_sesiones_total = NumSesionesDadaImparticion2021($id_imparticion);
    $hoy = date("Y-m-d");
	$sql = "Select * from tbl_inscripcion_curso_sesiones_horarios_rut where rut='$rut' and id_inscripcion='$id_imparticion' ";
	$connexion->query($sql);
    $cod = $connexion->resultset();	
	if($cod[0]->id>0){

	} else {
		$sql = "INSERT INTO tbl_inscripcion_curso_sesiones_horarios_rut (id_inscripcion, rut) " .
        "VALUES ('$id_imparticion', '$rut');";
        $connexion->query($sql);
        $connexion->execute();
	}
    if ($ses_1 == "on") {
        $sesion1 = 1;
        $suma_sesiones++;
    }
    if ($ses_2 == "on") {
        $sesion2 = 1;
        $suma_sesiones++;
    }
    if ($ses_3 == "on") {
        $sesion3 = 1;
        $suma_sesiones++;
    }
    if ($ses_4 == "on") {
        $sesion4 = 1;
        $suma_sesiones++;
    }
    if ($ses_5 == "on") {
        $sesion5 = 1;
        $suma_sesiones++;
    }
    if ($ses_6 == "on") {
        $sesion6 = 1;
        $suma_sesiones++;
    }
    if ($ses_7 == "on") {
        $sesion7 = 1;
        $suma_sesiones++;
    }
    if ($ses_8 == "on") {
        $sesion8 = 1;
        $suma_sesiones++;
    }
    if ($ses_9 == "on") {
        $sesion9 = 1;
        $suma_sesiones++;
    }
    if ($ses_10 == "on") {
        $sesion10 = 1;
        $suma_sesiones++;
    }
    if ($ses_11 == "on") {
        $sesion11 = 1;
        $suma_sesiones++;
    }
    if ($ses_12 == "on") {
        $sesion12 = 1;
        $suma_sesiones++;
    }

    $sql = "update tbl_inscripcion_curso_sesiones_horarios_rut 
                set sesion1='$sesion1', sesion2='$sesion2', 
                sesion3='$sesion3', sesion4='$sesion4', 
                sesion5='$sesion5', sesion6='$sesion6',
                sesion7='$sesion7', sesion8='$sesion8', 
                sesion9='$sesion9', sesion10='$sesion10', 
                sesion11='$sesion11', sesion12='$sesion12',
                fecha='$hoy'
            where rut='$rut' and id_inscripcion='$id_imparticion'";

        $connexion->query($sql);
        $connexion->execute();

    $porcentaje_asistencia_sesiones = round(100 * $suma_sesiones / $num_sesiones_total);
//ActualizaAvanceRut_2021($rut, $id_imparticion, $porcentaje_asistencia_sesiones);

    return $porcentaje_asistencia_sesiones;

}

function NumSesionesDadaImparticion2021($id_imparticion)
{

    $connexion = new DatabasePDO();
//echo "<br>-> DatosCursoImparticion2021 $id_curso, $id_imparticion";
    $sql = "select count(id) as cuenta 
from tbl_inscripcion_curso_sesiones_horarios where id_inscripcion='$id_imparticion'limit 1";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod[0]->cuenta;
}

function EncuestaDeleteEncSat($id)
{
        $connexion = new DatabasePDO();
        $sql = "DELETE FROM tbl_id_inscripcion_encuesta_satisfaccion WHERE id_inscripcion = :id";
        //echo " <br>EncuestaDeleteEncSat id - $id ".$sql;
        $connexion->query($sql);
        $connexion->bind(':id', $id);
        $connexion->execute();
}
function EncuestaCheckRespuestasEncSat($id_inscripcion)
{
    $connexion = new DatabasePDO();
    $sql = "select count(h.id) as cuenta from tbl_enc_elearning_respuestas h where h.id_foco='".$id_inscripcion."'";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod[0]->cuenta;
}

function EncuestaCheckRespuestasEncSatQRFull($id_inscripcion)
{
    $connexion = new DatabasePDO();
    $sql = "select h.*,e.pregunta,e.tipo as tipo_pregunta,e.dimension_descripcion as dimension
from tbl_enc_elearning_respuestas h 
left join tbl_enc_elearning_preg e on e.id_encuesta=h.id_encuesta and e.id_pregunta=h.id_pregunta
where h.id_foco='".$id_inscripcion."' group by h.id_pregunta";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}

function EncuestaCheckRespuestasDetailEncSatQRFull($id_encuesta, $id_pregunta, $id_inscripcion)
{
    $connexion = new DatabasePDO();
    $sql = "select h.*
        from tbl_enc_elearning_respuestas h 
        where h.id_foco='".$id_inscripcion."' and h.id_pregunta='$id_pregunta'";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}

function lista_cuentas_data($id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "select h.* from tbl_finanza_cuenta h where h.id_empresa='$id_empresa' order by h.cuenta_dsc ASC";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}
function MaxIdRelatores(){
    $connexion = new DatabasePDO();
    $sql="select id from tbl_relatores order by id DESC limit 1";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod[0]->id;
}
function QR_InsertRelatorIdImparticion($id_inscripcion, $key)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT nombre FROM tbl_relatores WHERE rut='$key' limit 1";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    $nombre = $cod[0]->nombre;
    $sql = "INSERT INTO tbl_id_inscripcion_encuesta_satisfaccion (id_inscripcion,rut_relator,relator) VALUES ('$id_inscripcion', '$key', '$nombre')";
    if($nombre<>"" or $key=="creada"){
        $connexion->query($sql);
        $connexion->execute();
    }
}
function QR_IdInscripcion_Relatores_Full($id_inscripcion)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_id_inscripcion_encuesta_satisfaccion WHERE id_inscripcion=:id_inscripcion limit 1 ";
    $connexion->query($sql);
    $connexion->bind(':id_inscripcion', $id_inscripcion);
    $cod = $connexion->resultset();
    return $cod;
}
function QR_IdInscripcion_Relatores_List($id_inscripcion)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.* FROM tbl_id_inscripcion_encuesta_satisfaccion h WHERE h.id_inscripcion=:id_inscripcion ";
    $connexion->query($sql);
    $connexion->bind(':id_inscripcion', $id_inscripcion);
    $cod = $connexion->resultset();
    return $cod;
}
function UpdateFecha2022_mc_carrera_interna() {
    $connexion = new DatabasePDO();
    $hoy = date("Y-m-d");
    $sql = "update tbl_mc_carrera_interna set fecha='$hoy'";
    $connexion->query($sql);
    $connexion->execute();
}
function QR_IdInscripcion_Relatores($id_imparticion)
{
    $connexion = new DatabasePDO();

    $sql1 = "select h.codigo_inscripcion,h.tipo_audiencia
    from tbl_inscripcion_curso h
    WHERE
    h.codigo_inscripcion='$id_imparticion'";
    //echo $sql1;
    $connexion->query($sql1);
    $cod1 = $connexion->resultset();
    //echo "<pre>";print_r($cod1); echo "</pre>";
        if($cod1[0]->tipo_audiencia=="fecha_inicio_termino"){
            $sql="select h.codigo_inscripcion,h.tipo_audiencia,
                    h.id_audiencia as rut_relator,
                    r.nombre as nombre_relator
                        from tbl_inscripcion_curso h
                    left join tbl_relatores r on r.rut=h.id_audiencia
                        WHERE
                    h.codigo_inscripcion='$id_imparticion'";
        }
        if($cod1[0]->tipo_audiencia=="fecha_sesiones"){
            $sql="    select h.codigo_inscripcion,h.tipo_audiencia,
                        s.relator as rut_relator,
                        r.nombre as nombre_relator
                            from tbl_inscripcion_curso h
                        left join tbl_inscripcion_curso_sesiones_horarios s on s.id_inscripcion=h.codigo_inscripcion
                        left join tbl_relatores r on r.rut=s.relator
                            WHERE
                        h.codigo_inscripcion='$id_imparticion'
                        GROUP BY s.relator
                    ";
        }
    //echo "<br>$sql<br>";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    //echo "<pre>";print_r($cod); echo "</pre>";
    return $cod;
}
function QR_IdInscripcion_Sesiones_Relatores($id_imparticion)
{
    $connexion = new DatabasePDO();

    $sql1 = "select h.codigo_inscripcion,h.tipo_audiencia
    from tbl_inscripcion_curso h
    WHERE
    h.codigo_inscripcion='$id_imparticion'";
    //echo $sql1;
    $connexion->query($sql1);
    $cod1 = $connexion->resultset();
    //echo "<pre>";print_r($cod1); echo "</pre>";

    if($cod1[0]->tipo_audiencia=="fecha_inicio_termino"){
        $sql="select h.codigo_inscripcion,h.tipo_audiencia,
            h.fecha_inicio, h.fecha_termino,h.hora_inicio, h.hora_termino,
            TIMESTAMPDIFF(MINUTE, h.hora_inicio, h.hora_termino) AS minutos_entre_horas,
            h.id_audiencia as rut_relator,
            r.nombre as nombre_relator, j.id as relator_seleccionado

            from tbl_inscripcion_curso h
                
            left join tbl_relatores r on r.rut=h.id_audiencia
            left join tbl_id_inscripcion_encuesta_satisfaccion j on j.id_inscripcion=h.codigo_inscripcion and j.rut_relator=h.id_audiencia
            WHERE
            h.codigo_inscripcion='$id_imparticion'";
    }
    if($cod1[0]->tipo_audiencia=="fecha_sesiones"){
        $sql="
                select h.codigo_inscripcion,h.tipo_audiencia,
                s.relator as rut_relator,
                r.nombre as nombre_relator,
                s.fecha as fecha_inicio, s.fecha as fecha_termino, s.hora_desde as hora_inicio, s.hora_hasta as hora_termino,
				TIMESTAMPDIFF(MINUTE, s.hora_desde, s.hora_hasta) AS minutos_entre_horas, j.id as relator_seleccionado

                from tbl_inscripcion_curso h
                    
                left join tbl_inscripcion_curso_sesiones_horarios s on s.id_inscripcion=h.codigo_inscripcion
                left join tbl_relatores r on r.rut=s.relator
                    left join tbl_id_inscripcion_encuesta_satisfaccion j on j.id_inscripcion=h.codigo_inscripcion and j.rut_relator=s.relator
                WHERE
                h.codigo_inscripcion='$id_imparticion'
      
                ";
    }
    //echo "<br>$sql<br>";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    //echo "<pre>";print_r($cod); echo "</pre>";
    return $cod;
}
function Sesiones_2021_MaxMinFecha($id_imparticion)
{
    $connexion = new DatabasePDO();

    $sql1 = "select max(fecha) as MaxFecha 	from tbl_inscripcion_curso_sesiones_horarios where id_inscripcion='$id_imparticion'";
    $connexion->query($sql1);
    $cod1 = $connexion->resultset();

    $sql2 = "select min(fecha) as MiniFecha 	from tbl_inscripcion_curso_sesiones_horarios where id_inscripcion='$id_imparticion'";
    $connexion->query($sql2);
    $cod2 = $connexion->resultset();

    $arreglo[0] = $cod1[0]->MaxFecha;
    $arreglo[1] = $cod2[0]->MiniFecha;


    return $arreglo;


}
function AgregaSesionesImparticion($sesion, $id_inscripcion, $fecha, $hora_desde, $hora_hasta, $observacion, $id_linea, $relator, $sesion_new)
{
    $connexion = new DatabasePDO();
    $rut_creador = $_SESSION["user_"];
    $fecha_creacion = date("Y-m-d");

    $sqlMax = "select max(sesion)  as maxsesion from tbl_inscripcion_curso_sesiones_horarios where id_inscripcion='$id_inscripcion'";
    $connexion->query($sqlMax);
    $codMax = $connexion->resultset();

    $nueva_ses = $codMax[0]->maxsesion;
    if ($sesion_new == "1") {
        $sesion_ins = $nueva_ses + 1;
    } else {
        $sesion_ins = $sesion;
    }


    $observacion = ($observacion);

    $sql = "select h.* from tbl_inscripcion_curso_sesiones_horarios h where h.id='$id_linea'";

    $connexion->query($sql);
    $cod = $connexion->resultset();


    if ($cod[0]->id > 0) {
        if ($relator <> "") {
            $updateRelator = " ,	relator='$relator' ";
        } else {
            $updateRelator = " ";
        }
        $sql = "update tbl_inscripcion_curso_sesiones_horarios 
set 
id_inscripcion='$id_inscripcion',
fecha='$fecha',
hora_desde='$hora_desde',
hora_hasta='$hora_hasta',
observaciones='$observacion',
rut_creador='$rut_creador',
fecha_creacion='$fecha_creacion',
sesion='$sesion'
" . $updateRelator . "
where   id='" . $id_linea . "'";
        if ($fecha <> "" and $id_inscripcion <> "") {
            $connexion->query($sql);
            $connexion->execute();
        }
    } else {
        $sql = " INSERT INTO tbl_inscripcion_curso_sesiones_horarios (id_inscripcion,fecha,hora_desde,hora_hasta,observaciones,rut_creador,fecha_creacion,sesion, relator)
                    VALUES ('$id_inscripcion','$fecha','$hora_desde','$hora_hasta','$observacion','$rut_creador','$fecha_creacion','$sesion_ins', '$relator')";

        if ($fecha <> "" and $id_inscripcion <> "") {
            $connexion->query($sql);
            $connexion->execute();
        }
    }
    return $cod;
}

function Update_lms_Malla_2023_full($rut, $id_malla, $id_malla_antigua, $id_curso)
{
    $connexion = new DatabasePDO();
    
    $sql = "UPDATE rel_lms_malla_persona SET id_malla = :id_malla WHERE rut = :rut AND id_malla = :id_malla_antigua";
    $connexion->query($sql);
    $connexion->bind(':id_malla', $id_malla);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_malla_antigua', $id_malla_antigua);
    $connexion->execute();

    $sql = "UPDATE tbl_lms_reportes SET id_malla = :id_malla WHERE rut = :rut AND id_curso = :id_curso";
    $connexion->query($sql);
    $connexion->bind(':id_malla', $id_malla);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->execute();

    $malla = DatosMalla_2022($id_malla);
    $sql = "UPDATE tbl_lms_reportes_full SET id_malla = :id_malla, malla = :malla_nombre WHERE rut = :rut AND id_curso = :id_curso";
    $connexion->query($sql);
    $connexion->bind(':id_malla', $id_malla);
    $connexion->bind(':malla_nombre', $malla[0]->nombre);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->execute();
}


function Verifica_Aprobacion_2023($id_curso, $rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT id FROM tbl_lms_reportes WHERE rut = :rut AND id_curso = :id_curso AND estado = 'APROBADO'";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_curso', $id_curso);
    $cod = $connexion->resultset();
    return $cod[0]->id;
}

function Verifica_Ota_Activa_2023($id_curso, $rut, $id_inscripcion)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT id FROM rel_lms_rut_id_curso_id_inscripcion WHERE rut = :rut AND id_curso = :id_curso AND activa = '1' AND id_inscripcion <> :id_inscripcion";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':id_inscripcion', $id_inscripcion);
    $cod = $connexion->resultset();
    return $cod[0]->id;
}

function Verifica_Ota_Futura_2023($id_curso, $rut, $id_inscripcion)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT id FROM rel_lms_rut_id_curso_id_inscripcion WHERE rut = :rut AND id_curso = :id_curso AND activa = '0' AND id_inscripcion <> :id_inscripcion AND fecha_inicio_inscripcion > CURDATE()";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':id_inscripcion', $id_inscripcion);
    $cod = $connexion->resultset();
    return $cod[0]->id;
}
function Verifica_Otra_malla_Futura_2023($rut, $id_inscripcion_malla)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT DISTINCT h.id,
                h.id_malla AS id_malla_inscrita,
                tii.id_malla AS id_malla_inscribir,
                rlc1.id_programa AS id_programa_inscribir,
                rlc2.id_programa AS id_programa_incrito
            FROM 
                rel_lms_malla_persona h
            JOIN 
                tbl_id_malla_id_inscripcion tii ON tii.id_inscripcion_malla = :id_inscripcion_malla
            JOIN 
                rel_lms_malla_curso rlc1 ON tii.id_malla = rlc1.id_malla AND rlc1.id_empresa = '78' AND rlc1.inactivo = '0'
            JOIN 
                rel_lms_malla_curso rlc2 ON h.id_malla = rlc2.id_malla AND rlc2.id_empresa = '78' AND rlc2.inactivo = '0'
            WHERE 
                h.rut = :rut
                AND rlc1.id_programa = rlc2.id_programa;";
    
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_inscripcion_malla', $id_inscripcion_malla);
    
    $cod = $connexion->resultset();
    
    return $cod;
}

function BuscaEjeProyecto_rel_lms_id_curso_id_inscripcion($id_inscripcion)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT id_eje, id_proyecto FROM rel_lms_id_curso_id_inscripcion WHERE id_inscripcion = :id_inscripcion";
    $connexion->query($sql);
    $connexion->bind(':id_inscripcion', $id_inscripcion);
    $cod = $connexion->resultset();
    return $cod;
}
function TraeDatosEjecutivos($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.* FROM tbl_ejecutivos h WHERE rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}
function Updatetbl_rel_lms_id_curso_id_inscripcion_eje_proyecto($id_curso, $id_inscripcion, $eje, $proyecto)
{
    $connexion = new DatabasePDO();
    $sql = "UPDATE rel_lms_id_curso_id_inscripcion 
    SET id_eje = :eje, id_proyecto = :proyecto
    WHERE id_curso = :id_curso
    AND id_inscripcion = :id_inscripcion";

    $connexion->query($sql);
    $connexion->bind(':eje', $eje);
    $connexion->bind(':proyecto', $proyecto);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':id_inscripcion', $id_inscripcion);

    $connexion->execute();
}
function insertLogAdmin_2022($admin, $sw, $post_var, $get_var, $request_var)
{

    $connexion = new DatabasePDO();
    $hoy = date("Y-m-d");
    $hora = date("H:i:s");
    $get_var = json_encode($get_var);
    $post_var = json_encode($post_var);
    $request_var = json_encode($request_var);
    global $plataforma;

    //echo "<br> insertLogAdmin_2022($admin, $sw, $post_var, $get_var, $request_var, $plataforma";
    $sql = "INSERT INTO tbl_admin_log (rut, ambiente, get_var, post_var, request_var, fecha, hora, plataforma) 
    VALUES ('$admin', '$sw', '$get_var', '$post_var', '$request_var', '$hoy', '$hora', '$plataforma')";
    //echo "<br>".$sql."<br>";
    $connexion->query($sql);
    $connexion->execute();
}
function EliminiInscripcionUsuarioYInscripcionCurso_2022($codigo_imparticion)
{
    $connexion = new DatabasePDO();

    $sql = "UPDATE tbl_inscripcion_curso SET id_empresa='99' WHERE codigo_inscripcion = :codigo_imparticion";
    $connexion->query($sql);
    $connexion->bind(':codigo_imparticion', $codigo_imparticion);
    $connexion->execute();

    $sql = "UPDATE tbl_inscripcion_usuarios SET id_empresa='99' WHERE id_inscripcion = :codigo_imparticion";
    $connexion->query($sql);
    $connexion->bind(':codigo_imparticion', $codigo_imparticion);
    $connexion->execute();
}

function PerfilAdmin($id)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_admin_perfiles WHERE id = :id";
    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $cod = $connexion->resultset();
    return $cod;
}

function VerificaTipoDePerfilAdminAcceso($user)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT tbl_admin.*, tbl_admin_perfiles.template FROM tbl_admin INNER JOIN tbl_admin_perfiles ON tbl_admin_perfiles.id = tbl_admin.acceso WHERE tbl_admin.user = :user";
    $connexion->query($sql);
    $connexion->bind(':user', $user);
    $cod = $connexion->resultset();
    return $cod;
}

function TiposReportesPorEmpresa($id_empresa, $tipo_reporte)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_reportes WHERE id_empresa = :id_empresa AND tipo_reporte = :tipo_reporte";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':tipo_reporte', $tipo_reporte);
    $cod = $connexion->resultset();
    return $cod;
}
function DatosUsuarioAdmin($rut)
{
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");

    $sql = "SELECT h.*, (SELECT nombre_perfil FROM tbl_admin_perfiles WHERE id = h.acceso) AS perfil FROM tbl_admin h WHERE h.user = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}

function lista_proveedores_ejecutivos_data($id_empresa, $tipo)
{
    $connexion = new DatabasePDO();

    if ($tipo == "ejecutivos") {
        $tabla = "tbl_ejecutivos";
    } elseif ($tipo == "otec") {
        $tabla = "tbl_otec";
    } elseif ($tipo == "relatores") {
        $tabla = "tbl_relatores";
    } elseif ($tipo == "usuarios_manuales") {
        $tabla = "tbl_usuario";
    } elseif ($tipo == "usuarios_externos") {
        $tabla = "tbl_usuario";
    } elseif ($tipo == "direcciones") {
        $tabla = "tbl_direcciones";
    } elseif ($tipo == "administradores") {
        $tabla = "tbl_admin";
    } elseif ($tipo == "foco") {
        $tabla = "tbl_lms_foco";
    } elseif ($tipo == "programas") {
        $tabla = "tbl_lms_programas_bbdd";
    } elseif ($tipo == "cuentas") {
        $tabla = "tbl_finanza_cuenta";
    }
    elseif ($tipo == "mallas_bch") {
        $tabla = "tbl_programa";
    }
    elseif ($tipo == "categorias_bch") {
        $tabla = "tbl_programa";
    }
    if ($tipo == "usuarios_manuales") {
        $sql = "SELECT h.*
FROM $tabla h
WHERE id_empresa = '$id_empresa' AND empresa_holding = 'USUARIO_MANUAL'
ORDER BY h.nombre ASC";
    } elseif ($tipo == "usuarios_externos") {
        $sql = "SELECT h.*
FROM $tabla h
WHERE id_empresa = '$id_empresa' AND empresa_holding = 'USUARIO_EXTERNO'
ORDER BY h.nombre ASC";
    } elseif ($tipo == "foco") {
        $sql = "SELECT h.*
FROM $tabla h
WHERE h.id_empresa = '$id_empresa' AND h.codigo_foco NOT LIKE '%bch%'
ORDER BY h.descripcion ASC";
    } elseif ($tipo == "programas") {
        $sql = "SELECT h.*
FROM $tabla h
WHERE id_empresa = '$id_empresa' AND id_programa NOT LIKE '%bch%'
ORDER BY h.nombre_programa ASC";
    }
    elseif ($tipo == "mallas_bch") {
        $sql = "SELECT h.*
FROM $tabla h
WHERE archivo = 'malla_bch'
ORDER BY h.nombre ASC";
    }
    elseif ($tipo == "categorias_bch") {
        $sql = "SELECT h.*
FROM $tabla h
WHERE archivo = 'categoria_bch'
ORDER BY h.nombre ASC";
    }

    elseif ($tipo == "administradores") {
        $sql = "SELECT h.*, (SELECT nombre_perfil FROM tbl_admin_perfiles WHERE id = h.acceso) AS perfil
FROM $tabla h
WHERE h.id_empresa = '$id_empresa' AND (h.acceso = '108' OR h.acceso = '133')";
    } else {
        $sql = "SELECT h.*
FROM $tabla h
WHERE id_empresa = '$id_empresa'";
    }
//echo $sql;
    $connexion->query($sql);

    $cod = $connexion->resultset();

    return $cod;
}
function NombreEmpresaOtec($empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT nombre FROM tbl_otec WHERE rut = :empresa";
    $connexion->query($sql);
    $connexion->bind(':empresa', $empresa);
    $cod = $connexion->resultset();
    return $cod[0]->nombre;
}
function Servicios_lista_option()
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_finanza_servicios";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}
function Servicios_Proveedores_ListaServicios_2024($id_imparticion)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*,p.nombre as nombre_proveedor, s.servic_dsc as servicio
            FROM tbl_id_inscripcion_proveedores h
            LEFT JOIN tbl_finanza_servicios s ON s.servic_id=h.servic_id
            LEFT JOIN tbl_otec p ON p.rut=h.rut_proveedor
            
            WHERE
            h.id_inscripcion='$id_imparticion'";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}
function Regiones_id_2022($id)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT nombre FROM tbl_regiones WHERE id_regiones = :id";
    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $cod = $connexion->resultset();
    return $cod[0]->nombre;
}

function Comunas_id_2022($id_comunas)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_comunas WHERE id_comunas = :id_comunas";
    $connexion->query($sql);
    $connexion->bind(':id_comunas', $id_comunas);
    $cod = $connexion->resultset();
    return $cod[0]->nombre;
}

function Regiones_2022()
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_regiones";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}

function Comunas_dado_id_regiones_2022($id_regiones)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_comunas WHERE id_regiones = :id_regiones ORDER BY nombre";
    $connexion->query($sql);
    $connexion->bind(':id_regiones', $id_regiones);
    $cod = $connexion->resultset();
    return $cod;
}
function lista_otec_vista_data($id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.* FROM tbl_otec h WHERE h.id_empresa = :id_empresa ORDER BY h.nombre ASC";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}
function DAtosCursoDadoIdInscripcion($id_inscripcion, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT
        tbl_inscripcion_curso.*,
        tbl_lms_curso.nombre AS nombre_curso,
        tbl_lms_curso.id_programa_global AS id_programa,
        tbl_lms_curso.cantidad_maxima_participantes,
        (SELECT nombre_completo FROM tbl_usuario WHERE rut = tbl_inscripcion_curso.ejecutivo) AS nombre_ejecutivo
    FROM tbl_inscripcion_curso
    INNER JOIN tbl_lms_curso ON tbl_lms_curso.id = tbl_inscripcion_curso.id_curso
    WHERE tbl_inscripcion_curso.codigo_inscripcion = :id_inscripcion AND tbl_inscripcion_curso.id_empresa = :id_empresa
    ORDER BY tbl_inscripcion_curso.id DESC";
    
    $connexion->query($sql);
    $connexion->bind(':id_inscripcion', $id_inscripcion);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    
    return $cod;
}
function TraeDatosUsuario($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_usuario WHERE rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}
function lista_proveedores_ejecutivos_delete_data($id, $tipo)
{
    $connexion = new DatabasePDO();

    if ($tipo == "ejecutivos") {
        $sql = "DELETE FROM tbl_ejecutivos WHERE id = :id";
    } elseif ($tipo == "otec") {
        $sql = "DELETE FROM tbl_otec WHERE id = :id";
    } elseif ($tipo == "direcciones") {
        $sql = "DELETE FROM tbl_direcciones WHERE id = :id";
    } elseif ($tipo == "relatores") {
        $sql = "DELETE FROM tbl_relatores WHERE id = :id";
    } elseif ($tipo == "usuarios_manuales") {
        $sql = "DELETE FROM tbl_usuario WHERE id = :id";
    } elseif ($tipo == "usuarios_externos") {
        $sql = "DELETE FROM tbl_usuario WHERE id = :id";
    } elseif ($tipo == "administradores") {
        $sql = "DELETE FROM tbl_admin WHERE id = :id";
    } elseif ($tipo == "foco") {
        $sql = "DELETE FROM tbl_lms_foco WHERE id = :id";
    } elseif ($tipo == "programas") {
        $sql = "DELETE FROM tbl_lms_programas_bbdd WHERE id = :id";
    }
    elseif ($tipo == "mallas_bch") {
        $sql = "DELETE FROM tbl_programa WHERE id = :id";
    }

    elseif ($tipo == "categorias_bch") {
        $sql = "DELETE FROM tbl_programa WHERE id = :id";
    }

    elseif ($tipo == "cuentas") {
        $sql = "DELETE FROM tbl_finanza_cuenta WHERE cuenta_id = :id";
    }

    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $connexion->execute();
}

function proveedores_ejecutivos_save_insert_update($id_empresa, $tipo, $id, $rut, $nombre, $descripcion, $direccion, $telefono, $email, $contacto)
{

    //echo "<br>$id_empresa, $tipo, id $id, $rut, $nombre, $descripcion, $direccion, $telefono, $email, $contacto"; EXIT();

    $connexion = new DatabasePDO();

    $id = Decodear3($id);
    $rut = ($rut);
    $nombre = ($nombre);
    $descripcion = ($descripcion);
    $direccion = ($direccion);
    $telefono = ($telefono);
    $email = ($email);
    $contacto = ($contacto);

    if ($tipo == "ejecutivos") {
        $tabla = "tbl_ejecutivos";
    } elseif ($tipo == "otec") {
        $tabla = "tbl_otec";
    } elseif ($tipo == "relatores") {
        $tabla = "tbl_relatores";
    } elseif ($tipo == "usuarios_manuales") {
        $tabla = "tbl_usuario";
    } elseif ($tipo == "usuarios_externos") {
        $tabla = "tbl_usuario";
    } elseif ($tipo == "direcciones") {
        $tabla = "tbl_direcciones";
    } elseif ($tipo == "administradores") {
        $tabla = "tbl_admin";
    } elseif ($tipo == "foco") {
        $tabla = "tbl_lms_foco";
    } elseif ($tipo == "programas") {
        $tabla = "tbl_lms_programas_bbdd";
    }elseif ($tipo == "mallas_bch") {
        $tabla = "tbl_programa";
    }elseif ($tipo == "categorias_bch") {
        $tabla = "tbl_programa";
    }elseif ($tipo == "cuentas") {
        $tabla = "tbl_finanza_cuenta";
    }

    if ($tipo == "otec") {
        $rutcuatroprimeros = substr($rut, 0, 4);
        $clave = Encriptar($rutcuatroprimeros);
        $rut_otec = LimpiaRut($rut);
        $sql_insert_tbl_admin = "INSERT INTO tbl_admin		
            (user, clave_encodeada, nombre, acceso, id_empresa, email, nombre_completo, perfil, activo, filtro_division_personas)
            VALUES 
            (:rut_otec, :clave, :nombre, '103', :id_empresa, :email, :nombre, 'Proveedor', '0', '0');";

        $connexion->query($sql_insert_tbl_admin);
        $connexion->bind(':rut_otec', $rut_otec);
        $connexion->bind(':clave', $clave);
        $connexion->bind(':nombre', $nombre);
        $connexion->bind(':id_empresa', $_SESSION["id_empresa"]);
        $connexion->bind(':email', $email);
        $connexion->execute();

        $nombre = strtoupper($nombre);
        $direccion = strtoupper($direccion);
        $telefono = strtoupper($telefono);
        $email = strtoupper($email);
        $contacto = strtoupper($contacto);

        $sql_insert = "INSERT INTO $tabla
            (rut, nombre, direccion, telefono, email, contacto, id_empresa, rut_limpio) 
            VALUES (:rut, :nombre, :direccion, :telefono, :email, :contacto, :id_empresa, :rut_otec);";
    }

       $idRecord = 0;

    if ($id > 0) {
        if ($tipo == 'cuentas') {
            $sql1 = "SELECT h.* FROM $tabla h WHERE h.id_empresa = :id_empresa AND h.cuenta_id = :id";
            $connexion->query($sql1);
            $connexion->bind(':id_empresa', $id_empresa);
            $connexion->bind(':id', $id);
            $cod1 = $connexion->resultset();
            if (!empty($cod1)) {
                $idRecord = $cod1[0]->cuenta_id;
            }
        }
        elseif ($tipo == 'mallas_bch' or $tipo == 'categorias_bch') {
            $sql1 = "SELECT h.* FROM $tabla h WHERE h.id = :id";
            $connexion->query($sql1);
            $connexion->bind(':id', $id);
            $cod1 = $connexion->resultset();
            if (!empty($cod1)) {
                $idRecord = $cod1[0]->id;
            }
        }

        else {
            $sql1 = "SELECT h.* FROM $tabla h WHERE h.id_empresa = :id_empresa AND h.id = :id";
            $connexion->query($sql1);
            $connexion->bind(':id_empresa', $id_empresa);
            $connexion->bind(':id', $id);
            $cod1 = $connexion->resultset();
            if (!empty($cod1)) {
                $idRecord = $cod1[0]->id;
            }
        }
    }
    




    if ($idRecord > 0) {

        if ($tipo == "administradores") {
            $perfil = BuscaNombrePerfilAdmin($direccion);
            $sql_update = "update  $tabla set user=:rut, nombre=:nombre, nombre_completo=:nombre, acceso=:direccion where id=:id";
			$connexion->query($sql_update);
            $connexion->bind(':rut', $rut);
            $connexion->bind(':nombre', $nombre);
            $connexion->bind(':direccion', $direccion);
            $connexion->bind(':id', $id);
        }
        if ($tipo == "ejecutivos") {
            $sql_update = "update  $tabla set rut=:rut, nombre=:nombre, descripcion=:descripcion, tipo_ejecutivo=:telefono where id=:id";
			$connexion->query($sql_update);
            $connexion->bind(':rut', $rut);
            $connexion->bind(':nombre', $nombre);
            $connexion->bind(':descripcion', $descripcion);
			$connexion->bind(':telefono', $telefono);
            $connexion->bind(':id', $id);
        } elseif ($tipo == "otec") {
            $sql_update = "update  $tabla set rut=:rut, nombre=:nombre, direccion=:direccion, telefono=:telefono, email=:email, contacto=:contacto where id=:id";
			$connexion->query($sql_update);
            $connexion->bind(':rut', $rut);
            $connexion->bind(':nombre', $nombre);
            $connexion->bind(':direccion', $direccion);
			$connexion->bind(':telefono', $telefono);
			$connexion->bind(':email', $email);
			$connexion->bind(':contacto', $contacto);
            $connexion->bind(':id', $id);
        } elseif ($tipo == "direcciones") {
            $sql_update = "update  $tabla set nombre=:nombre, direccion=:direccion, tipo=:contacto where id=:id";
			$connexion->query($sql_update);
            $connexion->bind(':nombre', $nombre);
            $connexion->bind(':direccion', $direccion);
			$connexion->bind(':contacto', $contacto);
			$connexion->bind(':id', $id);
        } elseif ($tipo == "relatores") {
            $sql_update = "update  $tabla	set rut=:rut, nombre=:nombre, cargo=:direccion, tipo=:telefono, email=:email, empresa=:contacto where id=:id";
			$connexion->query($sql_update);
			$connexion->bind(':rut', $rut);
            $connexion->bind(':nombre', $nombre);
            $connexion->bind(':direccion', $direccion);
			$connexion->bind(':telefono', $telefono);
			$connexion->bind(':email', $email);
			$connexion->bind(':contacto', $contacto);
			$connexion->bind(':id', $id);
        } elseif ($tipo == "foco") {
            $sql_update = "update  $tabla	set codigo_foco=:direccion, descripcion=:nombre where id=:id";
			$connexion->query($sql_update);
			$connexion->bind(':direccion', $direccion);
            $connexion->bind(':nombre', $nombre);
			$connexion->bind(':id', $id);			
        } elseif ($tipo == "programas") {
            $sql_aux="select cuenta_num, cuenta_dsc from tbl_finanza_cuenta where cuenta_id=:descripcion";
			$connexion->query($sql_aux);
			$connexion->bind(':descripcion', $descripcion); 
			$cod_aux = $connexion->resultset();

            $sql_update = "update  $tabla	set id_programa=:direccion, nombre_programa=:nombre, cuenta_id=:descripcion,cuenta='".$cod_aux[0]->cuenta_num."', cuenta_glosa='".$cod_aux[0]->cuenta_dsc."'  where id=:id";
			$connexion->query($sql_update);
			$connexion->bind(':direccion', $direccion);
            $connexion->bind(':nombre', $nombre);
			$connexion->bind(':descripcion', $descripcion); 
			$connexion->bind(':id', $id);				

        }

        elseif ($tipo == "mallas_bch") {

            $sql_update = "update  $tabla	set nombre=:nombre  where id=:id";
            // echo $sql_update; exit();
            $connexion->query($sql_update);
            $connexion->bind(':nombre', $nombre);
            $connexion->bind(':id', $id);

        }
        elseif ($tipo == "categorias_bch") {

            $sql_update = "update  $tabla	set nombre=:nombre  where id=:id";
            // echo $sql_update; exit();
            $connexion->query($sql_update);
            $connexion->bind(':nombre', $nombre);
            $connexion->bind(':id', $id);

        }
        elseif ($tipo == "cuentas") {
            $sql_update = "update  $tabla	set cuenta_num=:rut, cuenta_dsc=:nombre where cuenta_id=:id";
			$connexion->query($sql_update);
			$connexion->bind(':rut', $rut);
            $connexion->bind(':nombre', $nombre);
			$connexion->bind(':id', $id);
			
        } elseif ($tipo == "usuarios_manuales") {
            $nombre_completo = $nombre . " " . $descripcion;

            $sql_update = "update $tabla set rut=:rut, nombre=:nombre,apaterno=:descripcion, nombre_completo=:nombre_completo, email=:direccion, 
				nombre_empresa_holding=:telefono,empresa_holding='USUARIO_MANUAL' where id=:id";
			$connexion->query($sql_update);
			$connexion->bind(':rut', $rut);
            $connexion->bind(':nombre', $nombre);
			$connexion->bind(':descripcion', $descripcion);
			$connexion->bind(':direccion', $direccion);
			$connexion->bind(':telefono', $telefono);
			$connexion->bind(':id', $id);

        } elseif ($tipo == "usuarios_externos") {
            $nombre_completo = $nombre . " " . $descripcion;

            $sql_update = "update $tabla set rut=:rut, nombre=:nombre,apaterno=:descripcion, amaterno=:contacto,nombre_completo=:nombre_completo, email=:direccion, 
				nombre_empresa_holding=:telefono, empresa_holding='USUARIO_EXTERNO' where id=:id";
			$connexion->query($sql_update);
			$connexion->bind(':rut', $rut);
            $connexion->bind(':nombre', $nombre);
			$connexion->bind(':descripcion', $descripcion);
			$connexion->bind(':contacto', $contacto);
			$connexion->bind(':direccion', $direccion);
			$connexion->bind(':telefono', $telefono);
			$connexion->bind(':id', $id);
        }
        $connexion->execute();
    } else {

        if ($tipo == "administradores") {


            $user = $rut;
            $clave = substr($rut, 0, 4);
            $nombre = $nombre;
            $acceso = $direccion;
            $id_empresa = $id_empresa;
            $email = $email;
            $clave_encodeada = Encriptar($clave);
            $nombre_completo = $nombre;
            $perfil = BuscaNombrePerfilAdmin($direccion);

            $sql_insert = "	INSERT INTO $tabla
					(user,nombre,nombre_completo,acceso, id_empresa,email, clave_encodeada) 
					VALUES 
					('$rut', '$nombre', '$nombre', '$acceso', '$id_empresa', '$email', '$clave_encodeada');";
			$connexion->query($sql_insert);
					
        }

        if ($tipo == "ejecutivos") {
            $sql_insert = "INSERT INTO $tabla
					(rut, nombre, descripcion, tipo_ejecutivo, id_empresa) 
					VALUES ('$rut', '$nombre', '$descripcion', '$telefono', '$id_empresa');";
			$connexion->query($sql_insert);
        } elseif ($tipo == "otec") {

            $sql_selOtec = "select h.*
							from $tabla h
							where h.id_empresa='$id_empresa'
							and h.rut='$rut'
							";
			
			$connexion->query($sql_selOtec);
            $codOtec = $connexion->resultset();
			
			
            if ($codOtec[0]->id > 0) {
                echo "<script>alert('Proveedor ya existe');</script>";
                echo "<script>location.href='?sw=lista_proveedores_otec&tipo=otec';    </script>";
                exit();
            }

            $sql_insert = "INSERT INTO $tabla
					(rut, nombre, direccion, telefono, email, contacto, id_empresa,rut_limpio) 
					VALUES ('$rut', '$nombre', '$direccion', '$telefono', '$email','$contacto','$id_empresa','$rut_otec');";
			$connexion->query($sql_insert);
        } elseif ($tipo == "direcciones") {


            $nombre = strtoupper($nombre);
            $direccion = strtoupper($direccion);
            $telefono = strtoupper($telefono);
            $email = strtoupper($email);

            $sql_selDirecc = "select h.*
							from $tabla h
							where h.id_empresa='$id_empresa'
							and h.nombre='$nombre'
						";
			$connexion->query($sql_selDirecc);
            $codDirecc = $connexion->resultset();
			
			
            if ($codDirecc[0]->id > 0) {
                echo "<script>alert('Direccion ya existe');</script>";
                echo "<script>location.href='?sw=lista_proveedores_otec&tipo=direcciones';    </script>";
                exit();
            }

            $sql_insert = "INSERT INTO $tabla
					(nombre, direccion, ciudad, id_empresa, comuna, tipo) 
					VALUES (UPPER('$nombre'), UPPER('$direccion'), UPPER('$telefono'), '$id_empresa', UPPER('$email'), '$contacto');";
					$connexion->query($sql_insert);


        } elseif ($tipo == "foco") {
            $sql_insert = "INSERT INTO $tabla
					(codigo_foco, descripcion, id_empresa) 
					VALUES ('$direccion', '$nombre','$id_empresa');";
					$connexion->query($sql_insert);
        } elseif ($tipo == "programas") {

                $sql_aux="select cuenta_num, cuenta_dsc from tbl_finanza_cuenta where cuenta_id='".$descripcion."'";
				$connexion->query($sql_aux);
				$cod_aux = $connexion->resultset();
            $sql_insert = "INSERT INTO $tabla
					(id_programa, nombre_programa, id_empresa, cuenta_id,cuenta, cuenta_glosa) 
					VALUES ('$direccion', '$nombre', '$id_empresa', '$descripcion', '".$cod_aux[0]->cuenta_num."', '".$cod_aux[0]->cuenta_dsc."');";
					$connexion->query($sql_insert);

        }
        elseif ($tipo == "categorias_bch") {

            $sql_aux="select max(id) as max from $tabla";
            $connexion->query($sql_aux);
            $cod_aux = $connexion->resultset();
            $next_id=$cod_aux[0]->max+1;
            $sql_insert = "INSERT INTO $tabla
					(id, nombre, archivo, certificacion) 
					VALUES ('$next_id', '$nombre', 'categoria_bch', '0');";
                //echo $sql_insert; exit();
            $connexion->query($sql_insert);

        }
        elseif ($tipo == "mallas_bch") {

            $sql_aux="select max(id) as max from $tabla";
            $connexion->query($sql_aux);
            $cod_aux = $connexion->resultset();
            $next_id=$cod_aux[0]->max+1;
            $sql_insert = "INSERT INTO $tabla
					(id, nombre, archivo, certificacion) 
					VALUES ('$next_id', '$nombre', 'malla_bch', '0');";
            //echo $sql_insert; exit();
            $connexion->query($sql_insert);

        }

        elseif ($tipo == "cuentas") {
            $sql_insert = "INSERT INTO $tabla
					(cuenta_num, cuenta_dsc) 
					VALUES ('$rut', '$nombre');";
					$connexion->query($sql_insert);
        } elseif ($tipo == "relatores") {
            $sql_insert = "INSERT INTO $tabla
					(rut, nombre, cargo, tipo, email, empresa, id_empresa) 
					VALUES ('$rut', '$nombre', '$direccion', '$telefono', '$email','$contacto','$id_empresa');";
					$connexion->query($sql_insert);
        } elseif ($tipo == "usuarios_manuales") {
            $nombre_completo = $nombre . " " . $descripcion;
            $datetime = new DateTime('tomorrow');
            $manana = $datetime->format('Y-m-d');
            $sql_insert = "INSERT INTO $tabla
					(rut, rut_completo, nombre, apaterno, nombre_completo, email, nombre_empresa_holding, empresa_holding, id_empresa,vigencia_descripcion, fecha_ingreso) 
					VALUES ('$rut','$email', '$nombre', '$descripcion', '$nombre_completo', '$direccion','$telefono','USUARIO_MANUAL','$id_empresa','USUARIO_MANUAL','$manana');";
					$connexion->query($sql_insert);
        } elseif ($tipo == "usuarios_externos") {
	        $extra='';
            $nombre_completo = $nombre . " " . $descripcion . " " . $extra;
            $datetime = new DateTime('tomorrow');
            $manana = $datetime->format('Y-m-d');
            $sql_insert = "INSERT INTO $tabla
					(rut, rut_completo, nombre, apaterno, amaterno, nombre_completo, email, nombre_empresa_holding, empresa_holding, id_empresa,vigencia_descripcion, fecha_ingreso) 
					VALUES ('$rut','$email', '$nombre',  '$descripcion', '$contacto', '$nombre_completo', '$direccion','$telefono','USUARIO_EXTERNO','$id_empresa','USUARIO_EXTERNO','$manana');";
					$connexion->query($sql_insert);
        }
       $connexion->execute();
    }       
}
function resetea_clave_2021_cambiado1($rut, $id_empresa)
{
  /*  $connexion = new DatabasePDO();

    $sql_insert = "INSERT INTO tbl_clave (rut, id_empresa) VALUES (:rut, :id_empresa)";
    $connexion->query($sql_insert);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();

    $clave = substr($rut, 0, 4);
    $sql = "UPDATE tbl_clave SET clave_encodeada = :clave, cambiado = 1, fecha_cambio = :fecha_cambio WHERE rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':clave', $clave);
    $connexion->bind(':fecha_cambio', date("Y-m-d"));
    $connexion->bind(':rut', $rut);
    $connexion->execute();

    $cod = $connexion->resultset();

    return $cod;*/
}

function Listado_TotalCursos2($id_empresa, $id_curso, $llamada)
{
    $connexion = new DatabasePDO();
    $filtro = "";

    if ($id_curso) {
        $sql_id_curso = " and tbl_lms_curso.id = :id_curso";
        $connexion->bind(':id_curso', $id_curso);
    }

    $sql_presenciales = " and tbl_lms_curso.modalidad = '2'";

    $sql = "SELECT
        tbl_lms_curso.*,
        tbl_lms_foco.descripcion AS nombre_foco,
        tbl_lms_programas_bbdd.nombre_programa,
        tbl_otec.nombre AS nombre_otec,
        (SELECT COUNT(*) AS total FROM tbl_inscripcion_curso WHERE tbl_inscripcion_curso.id_curso = tbl_lms_curso.id) AS total_inscripciones_curso,
        (SELECT COUNT(DISTINCT(rel_lms_malla_curso.id_programa)) AS total FROM rel_lms_malla_curso WHERE rel_lms_malla_curso.id_curso = tbl_lms_curso.id AND rel_lms_malla_curso.id_programa IS NOT NULL) AS total_programas,
        (SELECT COUNT(DISTINCT rut) FROM tbl_inscripcion_usuarios WHERE id_curso = tbl_lms_curso.id) AS inscritos,
        (SELECT ejecutivo FROM tbl_inscripcion_curso WHERE tbl_inscripcion_curso.id_curso = tbl_lms_curso.id LIMIT 1) AS rut_ejecutivo,
        (SELECT nombre_completo FROM tbl_usuario WHERE rut = (SELECT ejecutivo FROM tbl_inscripcion_curso WHERE tbl_inscripcion_curso.id_curso = tbl_lms_curso.id LIMIT 1)) AS ejecutivo
    FROM tbl_lms_curso
    LEFT JOIN tbl_otec ON tbl_otec.rut = tbl_lms_curso.rut_otec
    LEFT JOIN tbl_lms_foco ON tbl_lms_foco.codigo_foco = tbl_lms_curso.id_foco AND tbl_lms_foco.id_empresa = :id_empresa
    LEFT JOIN tbl_lms_programas_bbdd ON tbl_lms_programas_bbdd.id_programa = tbl_lms_curso.id_empresa_programa AND tbl_lms_programas_bbdd.id_empresa = :id_empresa
    WHERE tbl_lms_curso.activo = 0
        AND tbl_lms_curso.inactivo = 0
        AND tbl_lms_curso.id_empresa = :id_empresa $sql_id_curso
        AND tbl_lms_curso.modalidad = '2'
        AND tbl_lms_curso.id NOT LIKE '%bch%'
    ORDER BY tbl_lms_curso.id_correlativo DESC";

    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();

    return $cod;
}

function BuscaIdImparticiondadoCurso($id_curso, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.codigo_inscripcion FROM tbl_inscripcion_curso h WHERE h.id_curso = :id_curso AND h.id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function BuscaAsistentesImparticion($id_imparticion, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT COUNT(DISTINCT(h.rut)) as asistentes FROM tbl_inscripcion_usuarios h WHERE h.id_inscripcion = :id_imparticion";
    $connexion->query($sql);
    $connexion->bind(':id_imparticion', $id_imparticion);
    $cod = $connexion->resultset();
    return $cod;
}

function TraeProgramasUnicosDadoCursoDeRelMallaCurso($id_empresa, $id_curso)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT DISTINCT(rel_lms_malla_curso.id_programa), tbl_lms_programas_bbdd.nombre_programa
            FROM rel_lms_malla_curso
            INNER JOIN tbl_lms_programas_bbdd ON tbl_lms_programas_bbdd.id_programa = rel_lms_malla_curso.id_programa
            WHERE rel_lms_malla_curso.id_curso = :id_curso AND rel_lms_malla_curso.id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function EliminiInscripcionUsuarioYInscripcionCurso_ImparticionMalla_2022($codigo_imparticion, $id_malla)
{
    $connexion = new DatabasePDO();

    $sql = "DELETE FROM tbl_inscripcion_curso WHERE codigo_inscripcion = :codigo_imparticion";
    $connexion->query($sql);
    $connexion->bind(':codigo_imparticion', $codigo_imparticion);
    $connexion->execute();

    $sql = "DELETE FROM tbl_inscripcion_usuarios WHERE id_inscripcion = :codigo_imparticion";
    $connexion->query($sql);
    $connexion->bind(':codigo_imparticion', $codigo_imparticion);
    $connexion->execute();

    $sql = "DELETE FROM rel_lms_rut_id_curso_id_inscripcion WHERE id_inscripcion = :codigo_imparticion";
    $connexion->query($sql);
    $connexion->bind(':codigo_imparticion', $codigo_imparticion);
    $connexion->execute();

    $sql = "DELETE FROM rel_lms_id_curso_id_inscripcion WHERE id_inscripcion = :codigo_imparticion";
    $connexion->query($sql);
    $connexion->bind(':codigo_imparticion', $codigo_imparticion);
    $connexion->execute();

    $sql = "DELETE FROM tbl_id_malla_id_inscripcion WHERE id_inscripcion = :codigo_imparticion";
    $connexion->query($sql);
    $connexion->bind(':codigo_imparticion', $codigo_imparticion);
    $connexion->execute();

    $sql = "DELETE FROM tbl_id_malla_id_inscripcion_rut WHERE id_inscripcion_malla = :id_malla";
    $connexion->query($sql);
    $connexion->bind(':id_malla', $id_malla);
    $connexion->execute();
}

function DatosPrograma_2022($id_programa)
{
    $connexion = new DatabasePDO();

    $sql = "SELECT * FROM tbl_lms_programas_bbdd WHERE id_programa = :id_programa LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':id_programa', $id_programa);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;
}

function DatosMalla_2022($id_malla)
{
    $connexion = new DatabasePDO();

    $sql = "SELECT * FROM tbl_lms_malla WHERE id = :id_malla";
    $connexion->query($sql);
    $connexion->bind(':id_malla', $id_malla);
    $connexion->execute();
    $cod = $connexion->resultset();

    return $cod;
}

function IdProgramaIdMalla_2022($id_malla)
{
    $connexion = new DatabasePDO();

    $sql = "SELECT id_programa FROM rel_lms_malla_curso WHERE id_malla = :id_malla LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':id_malla', $id_malla);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod[0]->id_programa;
}

function BuscaIdImparticiondadoMalla($id_malla, $id_empresa)
{
    $connexion = new DatabasePDO();

    $sql = "SELECT h.*, (SELECT COUNT(id) FROM tbl_id_malla_id_inscripcion_rut WHERE id_inscripcion_malla = h.id_inscripcion_malla) AS participantes 
            FROM tbl_id_malla_id_inscripcion h        
            WHERE h.id_malla = :id_malla AND h.nombre_imparticion IS NOT NULL GROUP BY h.id_inscripcion_malla ORDER BY fecha_inicio DESC";
    $connexion->query($sql);
    $connexion->bind(':id_malla', $id_malla);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;
}
function InsertProveedorImparticion2024($id_inscripcion, $rut_proveedor, $servic_id, $servic_otro)
{
    $connexion = new DatabasePDO();
    $id_empresa='78';
    $hoy=date("Y-m-d");
    $sql = "INSERT INTO tbl_id_inscripcion_proveedores 
                    (id_inscripcion, rut_proveedor, servic_id, servic_otro, fecha, id_empresa) " .
        "VALUES ('$id_inscripcion','$rut_proveedor','$servic_id','$servic_otro','$hoy','$id_empresa')";
    //echo $sql; exit();
    $connexion->query($sql);

    $connexion->execute();
}
function InsertRutPorIdMallaPorMalla2022($id_inscripcion_malla, $rut)
{
    $connexion = new DatabasePDO();
   
    $sql = "INSERT INTO tbl_id_malla_id_inscripcion_rut (id_inscripcion_malla, rut) " .
        "VALUES (:id_inscripcion_malla, :rut)";
    $connexion->query($sql);
    $connexion->bind(':id_inscripcion_malla', $id_inscripcion_malla);
    $connexion->bind(':rut', trim($rut));
    $connexion->execute();
}
function UpdateIdMallaProgramaFocoParaIdInscripcion($nombre_inscripcion, $activa_cero, $siguiente_codigo, $id_malla, $id_programa, $id_foco)
{
    $connexion = new DatabasePDO();
    $nombre_inscripcion = ($nombre_inscripcion);

    $sql = "UPDATE rel_lms_id_curso_id_inscripcion  
            SET id_malla = :id_malla, 
                id_programa = :id_programa, 
                id_foco = :id_foco, 
                activa = :activa_cero, 
                nombre_inscripcion = :nombre_inscripcion
            WHERE id_inscripcion = :siguiente_codigo";
    $connexion->query($sql);
    $connexion->bind(':id_malla', $id_malla);
    $connexion->bind(':id_programa', $id_programa);
    $connexion->bind(':id_foco', $id_foco);
    $connexion->bind(':activa_cero', $activa_cero);
    $connexion->bind(':nombre_inscripcion', $nombre_inscripcion);
    $connexion->bind(':siguiente_codigo', $siguiente_codigo);
    $connexion->execute();

    $sql = "UPDATE tbl_inscripcion_curso  
            SET nombre = :nombre_inscripcion
            WHERE codigo_inscripcion = :siguiente_codigo";
    $connexion->query($sql);
    $connexion->bind(':nombre_inscripcion', $nombre_inscripcion);
    $connexion->bind(':siguiente_codigo', $siguiente_codigo);
    $connexion->execute();
}

function BuscaCorrelativoMalla_IdMalla_IdInscripcion2022()
{
    $connexion = new DatabasePDO();

    $sql = "SELECT MAX(id_inscripcion_malla) AS max FROM tbl_id_malla_id_inscripcion";
    $connexion->query($sql);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod[0]->max;
}

function DescargaRut_IdMalla_IdInscripcion2022($id_inscripcion_malla)
{
    $connexion = new DatabasePDO();

    $sql = "SELECT * FROM tbl_id_malla_id_inscripcion_rut WHERE id_inscripcion_malla = :id_inscripcion_malla";
    $connexion->query($sql);
    $connexion->bind(':id_inscripcion_malla', $id_inscripcion_malla);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;
}

function Id_cursos_id_Inscripcion_por_id_inscripcion_idMalla_2022($id_inscripcion_id_malla)
{
    $connexion = new DatabasePDO();

    $sql = "SELECT h.*, j.nombre 
            FROM tbl_id_malla_id_inscripcion h 
            INNER JOIN tbl_lms_curso j ON j.id = h.id_curso 
            WHERE h.id_inscripcion_malla = :id_inscripcion_id_malla"; //echo $sql;exit();
    $connexion->query($sql);
    $connexion->bind(':id_inscripcion_id_malla', $id_inscripcion_id_malla);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;
}

function BuscaIdInscripcionMalla_2022($id_malla, $id_inscripcion)
{
    $connexion = new DatabasePDO();

    $sql = "SELECT id_inscripcion_malla 
            FROM tbl_id_malla_id_inscripcion 
            WHERE id_inscripcion = :id_inscripcion 
            AND id_malla = :id_malla";
    $connexion->query($sql);
    $connexion->bind(':id_inscripcion', $id_inscripcion);
    $connexion->bind(':id_malla', $id_malla);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod[0]->id_inscripcion_malla;
}

function Update_Id_cursos_id_Inscripcion_por_id_inscripcion_idMalla_2022($id_inscripcion_id_malla, $id_curso, $id_inscripcion, $fecha_desde, $fecha_hasta, $opcional, $nombre_inscripcion, $ejecutivo, $categoria_bch, $malla_bch, $id_programa, $id_foco)
{
    $connexion = new DatabasePDO();
    $nombre_inscripcion = ($nombre_inscripcion);

    $sql = "UPDATE tbl_id_malla_id_inscripcion  
            SET id_inscripcion = :id_inscripcion,
                fecha_inicio = :fecha_desde,
                fecha_termino = :fecha_hasta,
                opcional = :opcional,
                nombre_imparticion = :nombre_inscripcion,
                ejecutivo = :ejecutivo, 
                malla_bch='$malla_bch', 
                categoria_bch='$categoria_bch',
                id_programa='$id_programa',
                id_foco='$id_foco'
            WHERE id_inscripcion_malla = :id_inscripcion_id_malla 
            AND id_curso = :id_curso";
    $connexion->query($sql);
    $connexion->bind(':id_inscripcion', $id_inscripcion);
    $connexion->bind(':fecha_desde', $fecha_desde);
    $connexion->bind(':fecha_hasta', $fecha_hasta);
    $connexion->bind(':opcional', $opcional);
    $connexion->bind(':nombre_inscripcion', $nombre_inscripcion);
    $connexion->bind(':ejecutivo', $ejecutivo);
    $connexion->bind(':id_inscripcion_id_malla', $id_inscripcion_id_malla);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->execute();
}

function insert_IdMalla_IdInscripcion2022($id_malla, $id_curso, $id_inscripcion_malla)
{
    $connexion = new DatabasePDO();
    $hoy = date("Y-m-d");

    $sql = "INSERT INTO tbl_id_malla_id_inscripcion (id_malla, id_curso, id_inscripcion_malla, fecha) 
            VALUES (:id_malla, :id_curso, :id_inscripcion_malla, :fecha)"; 
    $connexion->query($sql);
    $connexion->bind(':id_malla', $id_malla);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':id_inscripcion_malla', $id_inscripcion_malla);
    $connexion->bind(':fecha', $hoy);
    $connexion->execute();
}

function BuscoCursoDadaMallayPrograma_2022($id_malla, $id_programa)
{
    $connexion = new DatabasePDO();

    $sql = "SELECT h.*, x.nombre AS nombre_malla, w.nombre_programa,
            x.
            FROM rel_lms_malla_curso h
            JOIN tbl_lms_malla x ON x.id = h.id_malla
            JOIN tbl_lms_programas_bbdd w ON w.id_programa = h.id_programa
            WHERE h.id_malla = :id_malla 
            AND h.id_programa = :id_programa 
            AND h.inactivo ='0' 
            AND h.id_empresa ='78'";

    $connexion->query($sql);
    $connexion->bind(':id_malla', $id_malla);
    $connexion->bind(':id_programa', $id_programa);

    $cod = $connexion->resultset();
    return $cod;
}
function BuscoCursoDadaMallayPrograma_2024($id_malla, $id_programa, $id_inscripcion_malla)
{
    //echo "<br>$id_malla, $id_programa, $id_inscripcion_malla<br>,";
    $connexion = new DatabasePDO();

    $sql = "SELECT
    h.*,
    x.nombre AS nombre_malla,
    w.nombre_programa,
    i.malla_bch,
    i.categoria_bch,
    i.id_programa as id_programa,
    pbch.nombre_programa as programa,
    i.id_foco as id_foco,
    fbch.descripcion as foco
        FROM
    rel_lms_malla_curso h
    JOIN tbl_lms_malla x ON x.id = h.id_malla
    JOIN tbl_lms_programas_bbdd w ON w.id_programa = h.id_programa 
    JOIN tbl_id_malla_id_inscripcion i ON i.id_malla=h.id_malla and i.id_inscripcion_malla='$id_inscripcion_malla'
    JOIN tbl_lms_programas_bbdd pbch ON pbch.id_programa=i.id_programa
    JOIN tbl_lms_foco fbch ON fbch.codigo_foco=i.id_foco
        WHERE
    h.id_malla = '$id_malla' 
    AND h.id_programa = '$id_programa' 
    AND h.inactivo = '0' 
    AND h.id_empresa = '78'
    
        GROUP BY h.id_curso, h.id_malla";
    //echo $sql; exit();
    $connexion->query($sql);

    $cod = $connexion->resultset();
    return $cod;
}
function BuscoCursoDadaMallayPrograma_2024_First($id_malla, $id_programa)
{
    //echo "<br>$id_malla, $id_programa, $id_inscripcion_malla<br>,";
    $connexion = new DatabasePDO();

    $sql = "SELECT
    h.*,
    x.nombre AS nombre_malla,
    w.nombre_programa
        FROM
    rel_lms_malla_curso h
    JOIN tbl_lms_malla x ON x.id = h.id_malla
    JOIN tbl_lms_programas_bbdd w ON w.id_programa = h.id_programa 

        WHERE
    h.id_malla = '$id_malla' 
    AND h.id_programa = '$id_programa' 
    AND h.inactivo = '0' 
    AND h.id_empresa = '78'
    ";
    //echo $sql; exit();
    $connexion->query($sql);

    $cod = $connexion->resultset();
    return $cod;
}
function BuscaIdImparticiondadoMallaCuentaInscritos($id_malla, $id_empresa)
{
    $connexion = new DatabasePDO();

    $sql = "SELECT h.*, j.id_malla, COUNT(DISTINCT h.rut) AS cuenta
            FROM tbl_id_malla_id_inscripcion_rut h
            INNER JOIN tbl_id_malla_id_inscripcion j ON j.id_inscripcion_malla = h.id_inscripcion_malla
            WHERE j.id_malla = :id_malla";

    $connexion->query($sql);
    $connexion->bind(':id_malla', $id_malla);
    $cod = $connexion->resultset();

    return $cod[0]->cuenta;
}

function DatosCurso_2($id_curso)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_lms_curso WHERE id = :id_curso";
    $connexion->query($sql);
    $connexion->bind(':id_curso', $id_curso);
    $cod = $connexion->resultset();
    return $cod;
}
function DatosCurso_1_2024($id_curso)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*, o.nombre as nombre_otec FROM tbl_lms_curso h left join tbl_otec o on o.rut_limpio=h.rut_otec 
           
           WHERE h.id = :id_curso";
    $connexion->query($sql);
    $connexion->bind(':id_curso', $id_curso);
    $cod = $connexion->resultset();
    return $cod;
}
function ListasPresenciales_CodigoIdCurso()
{
    $connexion = new DatabasePDO();
    $sql = "SELECT MAX(id_correlativo) as idMax FROM tbl_lms_curso";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    $year = date("Y");
    $month = date("m");
    $idMax = $cod[0]->idMax + 1;
    return $year . $month . $idMax;
}

function TotalModalidad2()
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_modalidad_curso WHERE descripcion IS NULL";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}

function TotalClasificacion($id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_lms_clasificacion WHERE id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function TraeOtec($id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM `tbl_otec` WHERE id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function ListasPresenciales_Obtienefocos($id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT tbl_lms_foco.* FROM tbl_lms_foco WHERE id_empresa = :id_empresa AND codigo_foco NOT LIKE '%bch%'  AND inactivo IS NULL ORDER BY descripcion ASC";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function ListaProgramasDadoFocoDeTabla_2022_data_sinanidacion($id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_lms_programas_bbdd WHERE id_empresa = :id_empresa AND id_programa NOT LIKE '%bch%'  and inactivo is null  ORDER BY nombre_programa ASC";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function ListaProgramasDadoFocoDeTabla($id_empresa,$id_foco)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_lms_programas_bbdd WHERE id_empresa = :id_empresa AND codigo_foco = :id_foco  and inactivo is null  ORDER BY nombre_programa ASC";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
	$connexion->bind(':id_foco', $id_foco);
    $cod = $connexion->resultset();
    return $cod;
}


function ListaCursos_ListaModalidadCursos()
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_modalidad_curso";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}

function InsertaRelacionMallaClasificacionCursoAdmin($id_malla, $id_curso, $id_clasificacion, $id_empresa, $id_foco, $id_programa)
{
    $connexion = new DatabasePDO();
    $sql = "INSERT INTO rel_lms_malla_curso (id_malla, id_curso, id_clasificacion, id_empresa, id_foco, id_programa) " .
        "VALUES (:id_malla, :id_curso, :id_clasificacion, :id_empresa, :id_foco, :id_programa)";
    $connexion->query($sql);
    $connexion->bind(':id_malla', $id_malla);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':id_clasificacion', $id_clasificacion);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_foco', $id_foco);
    $connexion->bind(':id_programa', $id_programa);
    $connexion->execute();
}

function ListarTiposDocumentosFinanzaData($cat)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_finanza_tipo_documentos WHERE tipdoc_status = 1 AND tipdoc_cat = :cat ORDER BY tipdoc_dsc";
    $connexion->query($sql);
    $connexion->bind(':cat', $cat);
    $cod = $connexion->resultset();
    return $cod;
}

function InsertaCurso($nombre_curso, $descripcion_curso, $modalidad, $tipo_curso, $prerequisito_curso,
                      $archivo, $objetivo_curso, $sence, $numero_horas, $cantidad_maxima_participantes, $rut_otec,
                      $clasificacion_curso, $cbc, $cod_identificador, $valor_hora, $valor_hora_sence, $codigo_curso,
                      $id_empresa, $contenidos_cursos, $nombre_curso_sence, $foco, $programa_bbdd, $codigo_sence,
                      $programa_bbdd_global, $programa_bbdd_elearning,$var,$var2,$var3,$var4,$var5, $var6, $bajada)
{

    //$bajada=$programa_bbdd_elearning;
   // echo "<br>InsertaCurso.$programa_bbdd_elearning,$var,$var2,$var3,$var4,$var5, $var6, $var7, $bajada."; exit();
    $connexion = new DatabasePDO();

    if ($sence == "si" && $cbc == "si") {
        $valor_hora_sence = $valor_hora_sence + (($valor_hora_sence * 20) / 100);
    }

    $sql = "INSERT INTO tbl_lms_curso 
            (nombre, descripcion, modalidad, tipo, prerequisito, imagen, objetivo_curso, sence, numero_horas, 
            cantidad_maxima_participantes, rut_otec, clasificacion, cbc, numero_identificador, valor_hora, 
            valor_hora_sence, id, id_empresa, contenidos_cursos, nombre_curso_sence, id_foco, id_empresa_programa, 
            id_programa_global, codigosence, bajada) 
            VALUES 
            (:nombre_curso, :descripcion_curso, :modalidad, :tipo_curso, :prerequisito_curso, :archivo, :objetivo_curso, 
            :sence, :numero_horas, :cantidad_maxima_participantes, :rut_otec, :clasificacion_curso, :cbc, :cod_identificador, 
            :valor_hora, :valor_hora_sence, :codigo_curso, :id_empresa, :contenidos_cursos, :nombre_curso_sence, :foco, 
            :programa_bbdd_elearning, :programa_bbdd_global, :codigo_sence, :bajada)";

    $connexion->query($sql);
    $connexion->bind(':nombre_curso', $nombre_curso);
    $connexion->bind(':descripcion_curso', $descripcion_curso);
    $connexion->bind(':modalidad', $modalidad);
    $connexion->bind(':tipo_curso', $tipo_curso);
    $connexion->bind(':prerequisito_curso', $prerequisito_curso);
    $connexion->bind(':archivo', $archivo);
    $connexion->bind(':objetivo_curso', $objetivo_curso);
    $connexion->bind(':sence', $sence);
    $connexion->bind(':numero_horas', $numero_horas);
    $connexion->bind(':cantidad_maxima_participantes', $cantidad_maxima_participantes);
    $connexion->bind(':rut_otec', $rut_otec);
    $connexion->bind(':clasificacion_curso', $clasificacion_curso);
    $connexion->bind(':cbc', $cbc);
    $connexion->bind(':cod_identificador', $cod_identificador);
    $connexion->bind(':valor_hora', $valor_hora);
    $connexion->bind(':valor_hora_sence', $valor_hora_sence);
    $connexion->bind(':codigo_curso', $codigo_curso);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':contenidos_cursos', $contenidos_cursos);
    $connexion->bind(':nombre_curso_sence', $nombre_curso_sence);
    $connexion->bind(':foco', $foco);
    $connexion->bind(':programa_bbdd_elearning', $programa_bbdd_elearning);
    $connexion->bind(':programa_bbdd_global', $programa_bbdd_global);
    $connexion->bind(':codigo_sence', $codigo_sence);
    $connexion->bind(':bajada', $bajada);

    $connexion->execute();
}

function ListarServiciosFinanzaData()
{
    $connexion = new DatabasePDO();

    $sql = "SELECT * FROM tbl_finanza_servicios WHERE servic_status = 1 ORDER BY servic_dsc";
    $connexion->query($sql);
    $cod = $connexion->resultset();

    return $cod;
}

function VerificoCursoPorEmpresa($id_curso, $id_empresa)
{
    $connexion = new DatabasePDO();

    $sql = "SELECT * FROM tbl_lms_curso WHERE id = :id_curso AND id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();

    return $cod;
}

function IMPARTICIONES_traeImparticionesCreadas($id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT tbl_empresa.prefijo, tbl_inscripcion_curso.* FROM tbl_inscripcion_curso
            INNER JOIN tbl_empresa ON tbl_empresa.id = tbl_inscripcion_curso.id_empresa
            WHERE tbl_inscripcion_curso.id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function IMPARTICIONES_traeImparticionesCreadasUltima_2021($id_empresa)
{
    $connexion = new DatabasePDO();
    $year = date("Y");
    $sql = "SELECT MAX(ROUND(codigo_inscripcion)) AS cuenta_max
            FROM tbl_inscripcion_curso
            WHERE codigo_inscripcion REGEXP '^-?[0-9]+$'
              AND codigo_inscripcion LIKE :year";
    $connexion->query($sql);
    $connexion->bind(':year', $year . "%");
    $cod = $connexion->resultset();
    return $cod[0]->cuenta_max;
}

function DatosInscripcionImparticionesV2($id_inscripcion, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT tbl_inscripcion_curso.*, tbl_lms_curso.nombre AS nombre_curso,
            (SELECT modalidad FROM tbl_modalidad_curso WHERE id = tbl_lms_curso.modalidad) AS modalidad,
            tbl_lms_curso.modalidad AS id_modalidad,
            tbl_inscripcion_curso.modalidad AS id_modalidad_imparticion,
            tbl_lms_curso.cantidad_maxima_participantes
            FROM tbl_inscripcion_curso
            INNER JOIN tbl_lms_curso ON tbl_lms_curso.id = tbl_inscripcion_curso.id_curso
            WHERE tbl_inscripcion_curso.codigo_inscripcion = :id_inscripcion
              AND tbl_inscripcion_curso.id_empresa = :id_empresa
            ORDER BY tbl_inscripcion_curso.id DESC";
    $connexion->query($sql);
    $connexion->bind(':id_inscripcion', $id_inscripcion);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function TraeCursos($id_malla, $id_empresa, $id_clasificacion)
{
    $connexion = new DatabasePDO();
    $filtro_clasificacion = "";
    if ($id_clasificacion != "0" && $id_clasificacion != "") {
        $filtro_clasificacion = " and rel_lms_malla_curso.id_clasificacion='$id_clasificacion'";
    }

    if ($id_malla == "0" || $id_malla == "") {
        $sql = "SELECT tbl_lms_curso.*
                FROM tbl_lms_curso
                INNER JOIN rel_lms_malla_curso ON rel_lms_malla_curso.id_curso = tbl_lms_curso.id
                WHERE tbl_lms_curso.id_empresa = :id_empresa
                GROUP BY tbl_lms_curso.id";
    } else if ($id_malla == "-1") {
        $sql = "SELECT tbl_lms_curso.*
                FROM tbl_lms_curso
                WHERE tbl_lms_curso.id_empresa = :id_empresa
                GROUP BY tbl_lms_curso.id
                ORDER BY tbl_lms_curso.nombre";
    } else {
        $sql = "SELECT tbl_lms_curso.*
                FROM tbl_lms_curso
                INNER JOIN rel_lms_malla_curso ON rel_lms_malla_curso.id_curso = tbl_lms_curso.id
                WHERE rel_lms_malla_curso.id_malla = :id_malla
                  AND tbl_lms_curso.id_empresa = :id_empresa" . $filtro_clasificacion;
    }

    $connexion->query($sql);
    
    if ($id_malla == "0" || $id_malla == "") {
        $connexion->bind(':id_empresa', $id_empresa);
    } else if ($id_malla == "-1") {
        $connexion->bind(':id_empresa', $id_empresa);
    } else {
       $connexion->bind(':id_malla', $id_malla);
		$connexion->bind(':id_empresa', $id_empresa);
    }

    if ($id_clasificacion != "0" && $id_clasificacion != "") {
        $connexion->bind(':id_clasificacion', $id_clasificacion);
    }
    $cod = $connexion->resultset(); // Use the appropriate method to fetch the results

    return $cod;
}

function TraeEjecutivos2021($id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*
            FROM tbl_ejecutivos h
            WHERE h.id_empresa = :id_empresa
            ORDER BY h.nombre ASC";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function TraeEjecutivos2022_tipo($id_empresa, $tipo_ejecutivo)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*
            FROM tbl_ejecutivos h
            WHERE h.id_empresa = :id_empresa
              AND h.tipo_ejecutivo = :tipo_ejecutivo
            ORDER BY h.nombre ASC";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':tipo_ejecutivo', $tipo_ejecutivo);
    $cod = $connexion->resultset();
    return $cod;
}

function TraeOtec2021($id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*
            FROM tbl_otec h
            WHERE h.id_empresa = :id_empresa
            ORDER BY h.nombre ASC";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function TraeDirecciones2021($id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*
            FROM tbl_direcciones h
            ORDER BY h.tipo, h.nombre ASC";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}

function IMPARTICION_UsuariosPorInscripcionConDatos($id_inscripcion, $id_empresa)
{
    $connexion = new DatabasePDO();
    $datos_empresa = DatosEmpresa($id_empresa);
    $campo1 = $datos_empresa[0]->campo1;
    $campo2 = $datos_empresa[0]->campo2;
    $campo3 = $datos_empresa[0]->campo3;
    $sql = "SELECT tbl_inscripcion_usuarios.rut,
            (SELECT COUNT(id) AS cuenta
             FROM tbl_inscripcion_curso_sesiones_horarios
             WHERE id_inscripcion = tbl_inscripcion_usuarios.id_inscripcion) AS sesiones
            FROM tbl_inscripcion_usuarios
            WHERE tbl_inscripcion_usuarios.id_inscripcion = :id_inscripcion
              AND tbl_inscripcion_usuarios.id_empresa = :id_empresa
              AND tbl_inscripcion_usuarios.rut <> ''
              AND tbl_inscripcion_usuarios.rut <> '12345'
              AND tbl_inscripcion_usuarios.rut <> '12121212'
              AND tbl_inscripcion_usuarios.rut <> '13131313'
              AND tbl_inscripcion_usuarios.rut <> '14141414'";
    $connexion->query($sql);
    //echo $sql;
    $connexion->bind(':id_inscripcion', $id_inscripcion);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function IMPARTICION_PostulantesPorInscripcion($id_inscripcion, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_inscripcion_postulantes WHERE id_inscripcion = :id_inscripcion AND id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_inscripcion', $id_inscripcion);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function BuscaNombreCursoDadoID($id_curso)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT nombre FROM tbl_lms_curso WHERE id = :id_curso";
    $connexion->query($sql);
    $connexion->bind(':id_curso', $id_curso);
    $cod = $connexion->resultset();
    return $cod;
}

function IMPARTICION_UsuariosPorInscripcion($id_inscripcion, $id_empresa)
{
    $connexion = new DatabasePDO();

    $sql = "SELECT * FROM tbl_inscripcion_usuarios
            WHERE id_inscripcion = :id_inscripcion AND id_empresa = :id_empresa";

    $connexion->query($sql);
    $connexion->bind(':id_inscripcion', $id_inscripcion);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();

    return $cod;
}

function DatosImparticiones_Sesiones_data_2021($id_inscripcion)
{
    $connexion = new DatabasePDO();

    $sql = "SELECT h.* FROM tbl_inscripcion_curso_sesiones_horarios h WHERE h.id_inscripcion = :id_inscripcion";

    $connexion->query($sql);
    $connexion->bind(':id_inscripcion', $id_inscripcion);
    $cod = $connexion->resultset();

    return $cod;
}

function TraeRelatores2021porId($rut)
{
    $connexion = new DatabasePDO();

    $sql = "SELECT h.* FROM tbl_relatores h WHERE h.rut = :rut ORDER BY h.nombre";

    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();

    return $cod;
}

function BuscaOpcional_rel_lms_id_curso_id_inscripcion($id_inscripcion)
{
    $connexion = new DatabasePDO();

    $sql = "SELECT opcional FROM rel_lms_id_curso_id_inscripcion WHERE id_inscripcion = :id_inscripcion";

    $connexion->query($sql);
    $connexion->bind(':id_inscripcion', $id_inscripcion);
    $cod = $connexion->resultset();

    return $cod[0]->opcional;
}

function TraeRelatores2021()
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*
            FROM tbl_relatores h
            ORDER BY h.nombre ASC";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}

function BuscaIdImparticionFull_2021($id_imparticion)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*
            FROM tbl_inscripcion_curso h
            WHERE h.codigo_inscripcion = :id_imparticion";
    $connexion->query($sql);
    $connexion->bind(':id_imparticion', $id_imparticion);
    $cod = $connexion->resultset();
    return $cod;
}


function ListarCuentasFinanzaData()
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_finanza_cuenta WHERE cuenta_status = 1 ORDER BY cuenta_dsc";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}

function ListarProyectosFinanzaData()
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.* FROM tbl_lms_programas_bbdd h WHERE id_empresa = '78' AND id_programa NOT LIKE '%bch%'  and inactivo is NULL ORDER BY h.nombre_programa ASC";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}

function ListarCUIFinanzaData()
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_finanza_cui WHERE cui_status = 1 ORDER BY cui_desc";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}

function ListResponsablesData()
{
    $connexion = new DatabasePDO();
    $sql = "SELECT r.*, u.nombre_completo, tr.tipres_sla_factura, tr.tipres_sla_reembolso FROM tbl_finanaza_responsables r, tbl_usuario u, tbl_finanza_tipo_responsable tr WHERE r.respon_status = 1 AND r.respon_rutresp = u.rut AND tr.tipres_id = r.respon_tipresid";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}

function facturaFinanzaByNumdoc($numdoc)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_finanza_facturas WHERE factur_numdoc = :numdoc AND factur_status != 0";
    $connexion->query($sql);
    $connexion->bind(':numdoc', $numdoc);
    $cod = $connexion->resultset();
    return $cod;
}
function saveResponsablesFacturaData($responsable, $id)
{

    $connexion = new DatabasePDO();
    $sql = "update tbl_finanza_factura_responsables set facres_status=0 where facres_facturid=:id";
    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $connexion->execute();
    $sla = "";
    $sql = "    insert into tbl_finanza_factura_responsables 
    (facres_facturid, facres_tiprespid, facres_rut,
        facres_fecrecep, facres_fecenv, facres_sla)
values ";
    foreach ($responsable as $row) {
        if ($row['resprut'] != "") {
            $datosUser = DatosTablaUsuario($row['resprut']);
            $nombre = $datosUser[0]->nombre_completo;
            $consulTipo = DatosResponsableTipoById($row['tipo']);
            $sla = $consulTipo[0]->tipres_sla_factura;
            $values .= "($id, {$row['tipo']}, {$row['resprut']},'{$nombre}'
        '{$row['recep']}', '{$row['envio']}', $sla),";
        }
    }
    $values = substr($values, 0, -1);
    $sql .= $values;
    $connexion2 = new DatabasePDO();
    $connexion2->query($sql);
    $connexion2->execute();
}
function saveResponsablesReembolsoData($responsable, $id)
{

    $connexion = new DatabasePDO();
    $sql = "update tbl_finanza_reembolso_responsables set reemres_status=0 where reemres_reemboid=:id";
    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $connexion->execute();
    $sla = "";
    $sql = "    insert into tbl_finanza_reembolso_responsables 
    (reemres_reemboid, reemres_tiprespid, reemres_rut,reemres_nomcom,
        reemres_fecrecep, reemres_fecenv, reemres_sla)
values ";
    foreach ($responsable as $row) {
        if ($row['resprut'] != "") {
            $consulTipo = DatosResponsableTipoById($row['tipo']);
            $datosUser = DatosTablaUsuario($row['resprut']);
            $nombre = $datosUser[0]->nombre_completo;
            $sla = $consulTipo[0]->tipres_sla_factura;
            $values .= "($id, {$row['tipo']}, {$row['resprut']},'{$nombre}',
        '{$row['recep']}', '{$row['envio']}', $sla),";
        }
    }
	if($values!="") {
		$values = substr($values, 0, -1);
		$sql .= $values;
		$connexion->query($sql);
		$connexion->execute();
	}
}
function saveDocumentoFactura($nombre, $url, $idFactra)
{
    $connexion = new DatabasePDO();
    $fecha = date('Y-m-d h:i:s');
    $sql = " insert into tbl_finanza_documentos (findoc_facid,findoc_url,findoc_nombre,findoc_created) value(:idFactra,:url,:nombre,:fecha)";
    $connexion->query($sql);
    $connexion->bind(':idFactra', $idFactra);
    $connexion->bind(':url', $url);
    $connexion->bind(':nombre', $nombre);
    $connexion->bind(':fecha', $fecha);
    $connexion->execute();
}
function saveDocumentoReembolso($nombre, $url, $idRembo)
{
    $connexion = new DatabasePDO();
    $fecha = date('Y-m-d h:i:s');
    $sql = " insert into tbl_finanza_reembolso_documentos (findoc_reemid,findoc_url,findoc_nombre,findoc_created) value(:idRembo,:url,:nombre,:fecha)";
    $connexion->query($sql);
    $connexion->bind(':idRembo', $idRembo);
    $connexion->bind(':url', $url);
    $connexion->bind(':nombre', $nombre);
    $connexion->bind(':fecha', $fecha);
    $connexion->execute();
}
function DatosResponsableTipoById($id)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * from tbl_finanza_tipo_responsable where tipres_id=:id";
    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $cod = $connexion->resultset();
    return $cod;
}
function DatosTablaUsuario($rut)
{
    $connexion = new DatabasePDO();
    $sql = "select * from tbl_usuario where rut=:rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}
function deleteDatosGeneralesFacturaData($id){
    $connexion = new DatabasePDO();
    $hoy = date("Y-m-d H:i:s");
    $sql = "update tbl_finanza_facturas set factur_status=0, factur_updated=:fecha where factur_id=:id";
    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $connexion->bind(':fecha', $hoy);
    $connexion->execute();
}
function deleteDatosGeneralesReembolsoData($id){
    $connexion = new DatabasePDO();
    $hoy = date("Y-m-d H:i:s");
    $sql = "update tbl_finanza_reembolsos set reembo_status=0, reembo_updated=:fecha where reembo_id=:id";
    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $connexion->bind(':fecha', $hoy);
    $connexion->execute();
}

function deleteDocumentFacturaData($id){
    $connexion = new DatabasePDO();
    $sql = "delete from tbl_finanza_documentos where findoc_id=:id";
    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $connexion->execute();
}
function deleteDocumentReembolsoData($id){
    $connexion = new DatabasePDO();
    $sql = "delete from tbl_finanza_reembolso_documentos where findoc_id=:id";
    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $connexion->execute();
}
function DatosOtecDadoRut($rut_otec)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_otec WHERE rut_limpio = :rut_otec";
    $connexion->query($sql);
    $connexion->bind(':rut_otec', $rut_otec);
    $cod = $connexion->resultset();
    return $cod;
}

function DatosProgramaByNombre($programa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_lms_programas_bbdd WHERE nombre_programa = :programa";
    $connexion->query($sql);
    $connexion->bind(':programa', $programa);
    $cod = $connexion->resultset();
    return $cod;
}




function saveDatosGeneralesFacturaData($factur_numdoc, $factur_tipdocid, $factur_proveeid, $factur_proveerut,
                                       $factur_servid, $factur_servotro, $factur_montoto, $factur_montonet, $factur_impuest,
                                       $factur_fecemision, $factur_ota, $factur_numota, $factur_cuentaid, $factur_cuenta,
                                       $factur_proyectid, $factur_proyecto, $factur_curso, $factur_otanombre, $factur_cui,
                                       $factur_respgast, $factur_observacion, $factur_mes, $factur_anual, $factur_status, $factura_proveenom,
                                       $rut_created,$factur_cuenta_nombre
)
{
    $connexion = new DatabasePDO();
    $factur_created = date("Y-m-d H:i:s");
    $sql = "INSERT INTO tbl_finanza_facturas (factur_numdoc, factur_tipdocid, factur_proveeid, factur_proveerut,
                                  factur_servid, factur_servotro, factur_montoto, factur_montonet, factur_impuest,
                                  factur_fecemision, factur_ota, factur_numota, factur_cuentaid, factur_cuenta,
                                  factur_proyectid, factur_proyecto, factur_curso, factur_otanombre, factur_cui,
                                  factur_respgast, factur_observacion, factur_mes, factur_anual,
                                  factur_created, factur_status,factur_proveenom, factur_rut_create,factur_cuenta_nombre)
VALUES (:factur_numdoc, :factur_tipdocid, :factur_proveeid, :factur_proveerut,
                                  :factur_servid, :factur_servotro, :factur_montoto, :factur_montonet, :factur_impuest,
                                  :factur_fecemision, :factur_ota, :factur_numota, :factur_cuentaid, :factur_cuenta,
                                  :factur_proyectid, :factur_proyecto, :factur_curso, :factur_otanombre, :factur_cui,
                                  :factur_respgast, :factur_observacion, :factur_mes, :factur_anual,
                                  :factur_created, :factur_status, :factura_proveenom, :rut_created, :factur_cuenta_nombre)";
    $connexion->query($sql);
    $connexion->bind(':factur_numdoc', $factur_numdoc);
    $connexion->bind(':factur_tipdocid', $factur_tipdocid);
    $connexion->bind(':factur_proveeid', $factur_proveeid);
    $connexion->bind(':factur_proveerut', $factur_proveerut);
    $connexion->bind(':factur_servid', $factur_servid);
    $connexion->bind(':factur_servotro', $factur_servotro);
    $connexion->bind(':factur_montoto', $factur_montoto);
    $connexion->bind(':factur_montonet', $factur_montonet);
    $connexion->bind(':factur_impuest', $factur_impuest);
    $connexion->bind(':factur_fecemision', $factur_fecemision);
    $connexion->bind(':factur_ota', $factur_ota);
    $connexion->bind(':factur_numota', $factur_numota);
    $connexion->bind(':factur_cuentaid', $factur_cuentaid);
    $connexion->bind(':factur_cuenta', $factur_cuenta);
    $connexion->bind(':factur_proyectid', $factur_proyectid);
    $connexion->bind(':factur_proyecto', $factur_proyecto);
    $connexion->bind(':factur_curso', $factur_curso);
    $connexion->bind(':factur_otanombre', $factur_otanombre);
    $connexion->bind(':factur_cui', $factur_cui);
    $connexion->bind(':factur_respgast', $factur_respgast);
    $connexion->bind(':factur_observacion', $factur_observacion);
    $connexion->bind(':factur_mes', $factur_mes);
    $connexion->bind(':factur_anual', $factur_anual);
    $connexion->bind(':factur_created', $factur_created);
    $connexion->bind(':factur_status', $factur_status);
    $connexion->bind(':factura_proveenom', $factura_proveenom);
    $connexion->bind(':rut_created', $rut_created);
    $connexion->bind(':factur_cuenta_nombre', $factur_cuenta_nombre);
    $connexion->execute();

    return $connexion->lastInsertId();
}

function reembolsoFinanzaByNumdoc($numdoc)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_finanza_reembolsos WHERE reembo_numdoc = :numdoc AND reembo_status != 0";
    $connexion->query($sql);
    $connexion->bind(':numdoc', $numdoc);
    $cod = $connexion->resultset();

    return $cod;
}

function saveDatosGeneralesReembolsoData($reembo_numdoc, $reembo_tipdocid, $reembo_tipdocOtro, $reembo_proveeid, $reembo_proveerut,
                                       $reembo_servid, $reembo_servotro, $reembo_montoto,
                                       $reembo_fecemision, $reembo_ota, $reembo_numota, $reembo_cuentaid, $reembo_cuenta,
                                       $reembo_proyectid, $reembo_proyecto, $reembo_curso, $reembo_otanombre, $reembo_cui,
                                       $reembo_respgast, $reembo_observacion, $reembo_mes, $reembo_anual, $reembo_status, $reemboa_proveenom,
                                       $rut_created,$reembo_cuenta_nombre
)
{
    $connexion = new DatabasePDO();
    $reembo_created = date("Y-m-d H:i:s");
    $sql = "INSERT INTO tbl_finanza_reembolsos (reembo_numdoc, reembo_tipdocid, reembo_tipdoc_otro, reembo_proveeid, reembo_proveerut,
                                  reembo_servid, reembo_servotro, reembo_montoto,
                                  reembo_fecemision, reembo_ota, reembo_numota, reembo_cuentaid, reembo_cuenta,
                                  reembo_proyectid, reembo_proyecto, reembo_curso, reembo_otanombre, reembo_cui,
                                  reembo_respgast, reembo_observacion, reembo_mes, reembo_anual,
                                  reembo_created, reembo_status, reembo_proveenom, reembo_rut_create, reembo_cuenta_nombre)
VALUES (:reembo_numdoc, :reembo_tipdocid, :reembo_tipdocOtro, :reembo_proveeid, :reembo_proveerut,
                                  :reembo_servid, :reembo_servotro, :reembo_montoto,
                                  :reembo_fecemision, :reembo_ota, :reembo_numota, :reembo_cuentaid, :reembo_cuenta,
                                  :reembo_proyectid, :reembo_proyecto, :reembo_curso, :reembo_otanombre, :reembo_cui,
                                  :reembo_respgast, :reembo_observacion, :reembo_mes, :reembo_anual,
                                  :reembo_created, :reembo_status, :reemboa_proveenom, :rut_created, :reembo_cuenta_nombre)";
    $connexion->query($sql);
    $connexion->bind(':reembo_numdoc', $reembo_numdoc);
    $connexion->bind(':reembo_tipdocid', $reembo_tipdocid);
    $connexion->bind(':reembo_tipdocOtro', $reembo_tipdocOtro);
    $connexion->bind(':reembo_proveeid', $reembo_proveeid);
    $connexion->bind(':reembo_proveerut', $reembo_proveerut);
    $connexion->bind(':reembo_servid', $reembo_servid);
    $connexion->bind(':reembo_servotro', $reembo_servotro);
    $connexion->bind(':reembo_montoto', $reembo_montoto);
    $connexion->bind(':reembo_fecemision', $reembo_fecemision);
    $connexion->bind(':reembo_ota', $reembo_ota);
    $connexion->bind(':reembo_numota', $reembo_numota);
    $connexion->bind(':reembo_cuentaid', $reembo_cuentaid);
    $connexion->bind(':reembo_cuenta', $reembo_cuenta);
    $connexion->bind(':reembo_proyectid', $reembo_proyectid);
    $connexion->bind(':reembo_proyecto', $reembo_proyecto);
    $connexion->bind(':reembo_curso', $reembo_curso);
    $connexion->bind(':reembo_otanombre', $reembo_otanombre);
    $connexion->bind(':reembo_cui', $reembo_cui);
    $connexion->bind(':reembo_respgast', $reembo_respgast);
    $connexion->bind(':reembo_observacion', $reembo_observacion);
    $connexion->bind(':reembo_mes', $reembo_mes);
    $connexion->bind(':reembo_anual', $reembo_anual);
    $connexion->bind(':reembo_created', $reembo_created);
    $connexion->bind(':reembo_status', $reembo_status);
    $connexion->bind(':reemboa_proveenom', $reemboa_proveenom);
    $connexion->bind(':rut_created', $rut_created);
    $connexion->bind(':reembo_cuenta_nombre', $reembo_cuenta_nombre);
    $connexion->execute();

    return $connexion->lastInsertId();
}
function updateDatosGeneralesFacturaData($id, $factur_numdoc, $factur_tipdocid, $factur_proveeid, $factur_proveerut, $factur_servid, $factur_servotro, $factur_montoto, $factur_montonet, $factur_impuest, $factur_fecemision, $factur_ota, $factur_numota, $factur_cuentaid, $factur_cuenta, $factur_proyectid, $factur_proyecto, $factur_curso, $factur_otanombre, $factur_cui, $factur_respgast, $factur_observacion, $factur_mes, $factur_anual, $factur_status, $factura_proveenom, $factur_cuenta_nombre)
{
    $connexion = new DatabasePDO();
    $hoy = date("Y-m-d H:i:s");
    $sql = "UPDATE tbl_finanza_facturas SET factur_tipdocid=:factur_tipdocid, factur_proveeid=:factur_proveeid, factur_proveerut=:factur_proveerut, factur_proveenom=:factura_proveenom, factur_servid=:factur_servid, factur_servotro=:factur_servotro, factur_montoto=:factur_montoto, factur_montonet=:factur_montonet, factur_impuest=:factur_impuest, factur_fecemision=:factur_fecemision, factur_ota=:factur_ota, factur_numota=:factur_numota, factur_cuentaid=:factur_cuentaid, factur_cuenta=:factur_cuenta, factur_proyectid=:factur_proyectid, factur_proyecto=:factur_proyecto, factur_curso=:factur_curso, factur_otanombre=:factur_otanombre, factur_cui=:factur_cui, factur_respgast=:factur_respgast, factur_observacion=:factur_observacion, factur_mes=:factur_mes, factur_anual=:factur_anual, factur_status=:factur_status, factur_updated=:hoy, factur_cuenta_nombre=:factur_cuenta_nombre WHERE factur_id=:id";
    $connexion->query($sql);
    $connexion->bind(':factur_tipdocid', $factur_tipdocid);
    $connexion->bind(':factur_proveeid', $factur_proveeid);
    $connexion->bind(':factur_proveerut', $factur_proveerut);
    $connexion->bind(':factura_proveenom', $factura_proveenom);
    $connexion->bind(':factur_servid', $factur_servid);
    $connexion->bind(':factur_servotro', $factur_servotro);
    $connexion->bind(':factur_montoto', $factur_montoto);
    $connexion->bind(':factur_montonet', $factur_montonet);
    $connexion->bind(':factur_impuest', $factur_impuest);
    $connexion->bind(':factur_fecemision', $factur_fecemision);
    $connexion->bind(':factur_ota', $factur_ota);
    $connexion->bind(':factur_numota', $factur_numota);
    $connexion->bind(':factur_cuentaid', $factur_cuentaid);
    $connexion->bind(':factur_cuenta', $factur_cuenta);
    $connexion->bind(':factur_proyectid', $factur_proyectid);
    $connexion->bind(':factur_proyecto', $factur_proyecto);
    $connexion->bind(':factur_curso', $factur_curso);
    $connexion->bind(':factur_otanombre', $factur_otanombre);
    $connexion->bind(':factur_cui', $factur_cui);
    $connexion->bind(':factur_respgast', $factur_respgast);
    $connexion->bind(':factur_observacion', $factur_observacion);
    $connexion->bind(':factur_mes', $factur_mes);
    $connexion->bind(':factur_anual', $factur_anual);
    $connexion->bind(':factur_status', $factur_status);
    $connexion->bind(':hoy', $hoy);
    $connexion->bind(':factur_cuenta_nombre', $factur_cuenta_nombre);
    $connexion->bind(':id', $id);
    $connexion->execute();
}

function updateDatosGeneralesReembolsoData($id, $reembo_numdoc, $reembo_tipdocid, $reembo_tipdocOtro, $reembo_proveeid, $reembo_proveerut, $reembo_servid, $reembo_servotro, $reembo_montoto, $reembo_montonet, $reembo_impuest, $reembo_fecemision, $reembo_ota, $reembo_numota, $reembo_cuentaid, $reembo_cuenta, $reembo_proyectid, $reembo_proyecto, $reembo_curso, $reembo_otanombre, $reembo_cui, $reembo_respgast, $reembo_observacion, $reembo_mes, $reembo_anual, $reembo_status, $reemboa_proveenom, $reembo_cuenta_nombre)
{
    $connexion = new DatabasePDO();
    $hoy = date("Y-m-d H:i:s");
    $sql = "UPDATE tbl_finanza_reembolsos SET reembo_tipdocid=:reembo_tipdocid, reembo_tipdoc_otro=:reembo_tipdocOtro, reembo_proveeid=:reembo_proveeid, reembo_proveerut=:reembo_proveerut, reembo_proveenom=:reemboa_proveenom, reembo_servid=:reembo_servid, reembo_servotro=:reembo_servotro, reembo_montoto=:reembo_montoto, reembo_montonet=:reembo_montonet, reembo_impuest=:reembo_impuest, reembo_fecemision=:reembo_fecemision, reembo_ota=:reembo_ota, reembo_numota=:reembo_numota, reembo_cuentaid=:reembo_cuentaid, reembo_cuenta=:reembo_cuenta, reembo_proyectid=:reembo_proyectid, reembo_proyecto=:reembo_proyecto, reembo_curso=:reembo_curso, reembo_otanombre=:reembo_otanombre, reembo_cui=:reembo_cui, reembo_respgast=:reembo_respgast, reembo_observacion=:reembo_observacion, reembo_mes=:reembo_mes, reembo_anual=:reembo_anual, reembo_status=:reembo_status, reembo_updated=:hoy, reembo_cuenta_nombre=:reembo_cuenta_nombre WHERE reembo_id=:id";
    $connexion->query($sql);
    $connexion->bind(':reembo_tipdocid', $reembo_tipdocid);
    $connexion->bind(':reembo_tipdocOtro', $reembo_tipdocOtro);
    $connexion->bind(':reembo_proveeid', $reembo_proveeid);
    $connexion->bind(':reembo_proveerut', $reembo_proveerut);
    $connexion->bind(':reemboa_proveenom', $reemboa_proveenom);
    $connexion->bind(':reembo_servid', $reembo_servid);
    $connexion->bind(':reembo_servotro', $reembo_servotro);
    $connexion->bind(':reembo_montoto', $reembo_montoto);
    $connexion->bind(':reembo_montonet', $reembo_montonet);
    $connexion->bind(':reembo_impuest', $reembo_impuest);
    $connexion->bind(':reembo_fecemision', $reembo_fecemision);
    $connexion->bind(':reembo_ota', $reembo_ota);
    $connexion->bind(':reembo_numota', $reembo_numota);
    $connexion->bind(':reembo_cuentaid', $reembo_cuentaid);
    $connexion->bind(':reembo_cuenta', $reembo_cuenta);
    $connexion->bind(':reembo_proyectid', $reembo_proyectid);
    $connexion->bind(':reembo_proyecto', $reembo_proyecto);
    $connexion->bind(':reembo_curso', $reembo_curso);
    $connexion->bind(':reembo_otanombre', $reembo_otanombre);
    $connexion->bind(':reembo_cui', $reembo_cui);
    $connexion->bind(':reembo_respgast', $reembo_respgast);
    $connexion->bind(':reembo_observacion', $reembo_observacion);
    $connexion->bind(':reembo_mes', $reembo_mes);
    $connexion->bind(':reembo_anual', $reembo_anual);
    $connexion->bind(':reembo_status', $reembo_status);
    $connexion->bind(':hoy', $hoy);
    $connexion->bind(':reembo_cuenta_nombre', $reembo_cuenta_nombre);
    $connexion->bind(':id', $id);
    $connexion->execute();
}
function getRespByIdData($id)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT r.*, tr.tipres_desc
            FROM tbl_finanza_factura_responsables r
            LEFT JOIN tbl_finanza_tipo_responsable tr ON tr.tipres_id = r.facres_tiprespid
            WHERE facres_status = 1 AND facres_facturid = :id";
    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $cod = $connexion->resultset();

    return $cod;
}

function getRespReemboByIdData($id)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT r.*, tr.tipres_desc
            FROM tbl_finanza_reembolso_responsables r
            LEFT JOIN tbl_finanza_tipo_responsable tr ON tr.tipres_id = r.reemres_tiprespid
            WHERE reemres_status = 1 AND reemres_reemboid = :id";
    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $cod = $connexion->resultset();

    return $cod;
}

function getDocumentoByIdData($id)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_finanza_documentos WHERE findoc_facid = :id";
    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $cod = $connexion->resultset();

    return $cod;
}

function getDocumentoReemboByIdData($id)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_finanza_reembolso_documentos WHERE findoc_reemid = :id";
    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $cod = $connexion->resultset();

    return $cod;
}

function Lista_Facturas()
{
    $connexion = new DatabasePDO();
    $sql = "SELECT f.*, td.tipdoc_dsc, otec.nombre AS proveedor_nombre, ts.servic_dsc, fc.cui_desc
            FROM tbl_finanza_tipo_documentos td, tbl_finanza_facturas f
            INNER JOIN tbl_otec otec ON f.factur_proveeid = otec.id
            INNER JOIN tbl_finanza_servicios ts ON f.factur_servid = ts.servic_id
            LEFT JOIN tbl_finanza_cui fc ON fc.cui_codigo = f.factur_cui
            WHERE f.factur_tipdocid = td.tipdoc_id AND f.factur_status != 0
            ORDER BY factur_created DESC";
    $connexion->query($sql);
    $cod = $connexion->resultset();

    return $cod;
}
function proveedores_finanzasData($id_empresa, $tipo, $rut)
{

    $connexion = new DatabasePDO();
    $sql = "select h.* from tbl_otec h where id_empresa=:idEmpresa and (rut=:rut or rut_limpio=:rut)";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':idEmpresa', $id_empresa);
    $cod = $connexion->resultset();

    return $cod;
}
function colaboradores_finanzasData($rut)
{
    $connexion = new DatabasePDO();
    $sql = "select h.* from tbl_usuario h where id_empresa=78 and (rut=:rut or rut_completo=:rut)";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();

    return $cod;
}
function Lista_Reembolsos()
{
    $connexion = new DatabasePDO();
    $sql = "SELECT f.*, td.tipdoc_dsc, otec.nombre_completo AS proveedor_nombre, ts.servic_dsc, fc.cui_desc
            FROM tbl_finanza_tipo_documentos td, tbl_finanza_reembolsos f
            INNER JOIN tbl_usuario otec ON (f.reembo_proveeid = otec.rut OR f.reembo_proveeid = otec.rut_completo)
            INNER JOIN tbl_finanza_servicios ts ON f.reembo_servid = ts.servic_id
            LEFT JOIN tbl_finanza_cui fc ON fc.cui_codigo = f.reembo_cui
            WHERE f.reembo_tipdocid = td.tipdoc_id AND f.reembo_status != 0
            ORDER BY reembo_created DESC";
    $connexion->query($sql);
    $cod = $connexion->resultset();

    return $cod;
}
function lista_ota_vista_data($id_empresa, $id)
{
    $connexion = new DatabasePDO();
    $sql = "select ic.*, c.nombre as curso, pb.nombre_programa as proyecto, pb.cuenta_id, fc.cuenta_num, fc.cuenta_dsc
from tbl_inscripcion_curso ic
         left join tbl_lms_curso c on c.id = ic.id_curso 
        left join tbl_lms_programas_bbdd pb on pb.id_programa=c.id_programa_global
         left join tbl_finanza_cuenta fc on fc.cuenta_id=pb.cuenta_id 
where  ic.id_empresa=:idEmpresa and codigo_inscripcion=:id";
    $connexion->query($sql);
    $connexion->bind(':idEmpresa', $id_empresa);
    $connexion->bind(':id', $id);
    $cod = $connexion->resultset();

    return $cod;

}
function UpdateMinimo_asistenciaMinimo_nota_aprobacion_2021($id_imparticion)
{
    $connexion = new DatabasePDO();
    
    $sql = "UPDATE tbl_inscripcion_curso 
            SET minimo_asistencia = NULL 
            WHERE minimo_asistencia = '0' 
            AND codigo_inscripcion = :id_imparticion";
    $connexion->query($sql);
    $connexion->bind(':id_imparticion', $id_imparticion);
    $connexion->execute();
    
    $sql = "UPDATE tbl_inscripcion_curso 
            SET minimo_nota_aprobacion = NULL 
            WHERE minimo_nota_aprobacion = '0' 
            AND codigo_inscripcion = :id_imparticion";
    $connexion->query($sql);
    $connexion->bind(':id_imparticion', $id_imparticion);
    $connexion->execute();
}

function ImparticionesNumSesiones_2021($id_inscripcion)
{
    $connexion = new DatabasePDO();
    
    $sql = "SELECT COUNT(id) AS cuenta 
            FROM tbl_inscripcion_curso_sesiones_horarios 
            WHERE id_inscripcion = :id_inscripcion";
    $connexion->query($sql);
    $connexion->bind(':id_inscripcion', $id_inscripcion);
    $cod = $connexion->resultset();
    
    return $cod[0]->cuenta;
}

function VerificaEnTablaCierreCursoEmpresRutInscripcion2021($rut, $id_empresa, $id_inscripcion)
{
    $connexion = new DatabasePDO();
    
    $sql = "SELECT * 
            FROM tbl_inscripcion_cierre 
            WHERE rut = :rut 
            AND id_empresa = :id_empresa 
            AND id_inscripcion = :id_inscripcion";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_inscripcion', $id_inscripcion);
    $cod = $connexion->resultset();
    
    return $cod;
}
function Update_Observaciones_RutIdImparticion_2023($rut, $id_imparticion, $observaciones)
{
    $connexion = new DatabasePDO();
    $observaciones = ($observaciones);
    $sql = "UPDATE tbl_inscripcion_cierre SET observaciones = :observaciones WHERE id_inscripcion = :id_imparticion AND rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':observaciones', $observaciones);
    $connexion->bind(':id_imparticion', $id_imparticion);
    $connexion->bind(':rut', $rut);
    $connexion->execute();
}
function actualizaTablaCierreRut_2021_SoloEstado($rut, $id_curso, $id_imparticion, $asistencia, $id_empresa, $nota, $nota_diagnostico, $estado, $estado_descripcion)
{
    $connexion = new DatabasePDO();
    $sql = "UPDATE tbl_inscripcion_cierre SET estado = :estado, estado_descripcion = :estado_descripcion WHERE rut = :rut AND id_curso = :id_curso AND id_empresa = :id_empresa AND id_inscripcion = :id_imparticion";
    $connexion->query($sql);
    $connexion->bind(':estado', $estado);
    $connexion->bind(':estado_descripcion', $estado_descripcion);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_imparticion', $id_imparticion);
    $connexion->execute();
}

function InsertaTablaCierreRutCursoImparticionAsistenciaEmpresa_2021($rut, $id_curso, $id_imparticion, $asistencia, $id_empresa, $nota, $nota_diagnostico, $estado, $estado_descripcion)
{
    $connexion = new DatabasePDO();

    $sql = "INSERT INTO tbl_inscripcion_cierre (rut, porcentaje_asistencia, avance, estado, id_curso, id_empresa, id_inscripcion, nota, caso_especial, estado_descripcion) " .
        "VALUES (:rut, :asistencia, :asistencia, :estado, :id_curso, :id_empresa, :id_imparticion, :nota, :nota_diagnostico, :estado_descripcion)";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':asistencia', $asistencia);
    $connexion->bind(':estado', $estado);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_imparticion', $id_imparticion);
    $connexion->bind(':nota', $nota);
    $connexion->bind(':nota_diagnostico', $nota_diagnostico);
    $connexion->bind(':estado_descripcion', $estado_descripcion);
    $connexion->execute();
}

function DatosCursoImparticion2021($id_curso, $id_imparticion)
{
    $connexion = new DatabasePDO();

    $sql = "SELECT h.id_clasificacion, h.id_programa, h.id_foco, h.id_malla, (SELECT modalidad FROM tbl_lms_curso WHERE id = h.id_curso) AS modalidadcurso  
FROM rel_lms_malla_curso h WHERE h.id_curso = :id_curso ORDER BY h.id DESC LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->execute();
    $cod = $connexion->resultset();

    return $cod;
}

function VerificaEnTablaCierreCursoEmpresRutInscripcion($id_curso, $rut, $id_empresa, $id_inscripcion)
{
    $connexion = new DatabasePDO();

    $sql = "SELECT * FROM tbl_inscripcion_cierre WHERE id_curso = :id_curso AND rut = :rut AND id_empresa = :id_empresa AND id_inscripcion = :id_inscripcion";
    $connexion->query($sql);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_inscripcion', $id_inscripcion);
    $connexion->execute();
    $cod = $connexion->resultset();

    return $cod;
}

function TraeUsuarioRut($rut)
{
    $connexion = new DatabasePDO();
    
    $sql = "SELECT *
            FROM tbl_usuario
            WHERE rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    
    return $cod;
}

function TipoRut_2022($rut, $id, $vigencia)
{
    $connexion = new DatabasePDO();
    
    /*$sql = "SELECT id FROM tbl_usuario WHERE rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();*/
    
    if ($id > 0) {
        if ($vigencia == "USUARIO_EXTERNO") {
            $vigencia = "Externo";
        } else {
            $vigencia = "Vigente";
        }
    } else {
        $vigencia = "No Vigente";
    }

    return $vigencia;
}

function actualizaTablaCierreRut_2021($rut, $id_curso, $id_imparticion, $asistencia, $id_empresa, $nota, $nota_diagnostico, $estado, $estado_descripcion)
{
    $connexion = new DatabasePDO();
    
    $sql = "UPDATE tbl_inscripcion_cierre
            SET porcentaje_asistencia = '$asistencia',
                avance = '$asistencia',
                nota = '$nota',
                caso_especial = '$nota_diagnostico',
                estado = '$estado',
                estado_descripcion = '$estado_descripcion'
            WHERE rut = '$rut'
              AND id_curso = '$id_curso'
              AND id_empresa = '$id_empresa'
              AND id_inscripcion = '$id_imparticion'";
            //  echo $sql;
    $connexion->query($sql);
    $connexion->execute();
}

function DatosCompletosImparticion_2021($id_imparticion)
{
    $connexion = new DatabasePDO();
    
    $sql = "SELECT h.*,
            (SELECT modalidad FROM tbl_lms_curso WHERE id = h.id_curso) AS id_modalidad,
            (SELECT id_clasificacion FROM rel_lms_malla_curso WHERE id_malla = h.id_malla AND id_curso = h.id_curso) AS id_clasificacion,
            (SELECT id_programa FROM rel_lms_malla_curso WHERE id_malla = h.id_malla AND id_curso = h.id_curso) AS id_programa,
            (SELECT id_foco FROM rel_lms_malla_curso WHERE id_malla = h.id_malla AND id_curso = h.id_curso) AS id_foco
            FROM tbl_inscripcion_curso h
            WHERE h.codigo_inscripcion = :id_imparticion";
              
    $connexion->query($sql);
    $connexion->bind(':id_imparticion', $id_imparticion);
    $cod = $connexion->resultset();
    
    return $cod;
}

function Selecttbl_id_inscripcion_documentos($id_inscripcion)
{
    $connexion = new DatabasePDO();
    
    $sql = "SELECT * FROM tbl_id_inscripcion_documentos WHERE id_inscripcion = :id_inscripcion AND activo = '1'";
    $connexion->query($sql);
    $connexion->bind(':id_inscripcion', $id_inscripcion);
    $cod = $connexion->resultset();
    
    return $cod;
}
function reporte_full_sincronico_full_data($fecha_inicio, $fecha_termino, $tipo)
{
   //echo "<br>reporte_full_sincronico_full_data($fecha_inicio, $fecha_termino,$tipo)"; exit();
    $connexion = new DatabasePDO();
    $JqFI = "";
    $JqFT = "";
    if($tipo=="1"){
        if ($fecha_inicio <> "") {
            $JqFI = " AND fecha_inicio_inscripcion >= '$fecha_inicio' ";
        }
        if ($fecha_termino <> "") {
            $JqFT = " AND fecha_termino_inscripcion <= '$fecha_termino' ";
        }
        $sql = "SELECT * FROM tbl_lms_reportes_full_fbch WHERE id > 0 $JqFI $JqFT";
    }
    if($tipo=="2"){
        if ($fecha_inicio <> "") {
            $JqFI = " AND h.fecha_inicio >= '$fecha_inicio' ";
        }
        if ($fecha_termino <> "") {
            $JqFT = " AND h.fecha_termino <= '$fecha_termino' ";
        }
        $sql = "
       SELECT 
rf.rut_consultor as RUT_CONSULTOR_CAPACITACION,
rf.rut_ejecutivo as RUT_EJECUTIVO_EXTERNO,
rf.nombre_ejecutivo as EJECUTIVO_EXTERNO,
rf.sence as SENCE,
rf.otic as NOMBRE_OTIC, 
rf.eje as EJE, 
rf.proyecto as PROYECTO,
'PENDIENTE' as CODIGO_DIVISION_CREACION_CURSO, 
'PENDIENTE' as DIVISION_CREACION_CURSO,
'SINCRONICO' as MODALIDAD, 
rf.modalidad as TIPO_MODALIDAD,
h.id_curso as ID_CURSO,
rf.curso as CURSO,
h.codigo_inscripcion as ID_IMPARTICION,
h.nombre as IMPARTICION,
h.fecha_inicio as FECHA_INICIO_IMPARTICION,
h.fecha_termino as FECHA_TERMINO_IMPARTICION,
if(h.fecha_inicio>CURDATE(),'INSCRIPCION',if(h.fecha_termino<CURDATE(),'FINALIZADO','EJECUTANDO')) as ESTADO_IMPARTICION,
h.comentarios as OBSERVACION_IMPARTICION,
rf.tipo_curso as TIPO_CURSO,
h.direccion as LUGAR_IMPARTICION,
h.hora_inicio as HORA_INICIO, 
h.hora_termino as HORA_TERMINO,
rf.numero_horas as HORAS,
rf.criterio_aprobacion as CRITERIO_APROBACION, 
h.minimo_asistencia as ASISTENCIA_MINIMA,
rf.minimo_nota_aprobacion as NOTA_MINIMA, 
ROUND(AVG(CASE WHEN rf.nota_evaluacion_post IS NOT NULL AND rf.nota_evaluacion_post <> '' AND rf.nota_evaluacion_post <> '-' THEN rf.nota_evaluacion_post ELSE NULL END)) as NOTA_EVALUACION_PROMEDIO_POST,  
rf.rut_relator as RUT_RELATOR, 
'PENDIENTE' as EVALUACION_SATISFACCION_PROMEDIO, 
'PENDIENTE' as PORCENTAJE_RECOMENDACION_PROMEDIO
                                
                from tbl_inscripcion_curso h
                left join tbl_lms_reportes_full_fbch rf on rf.id_inscripcion=h.codigo_inscripcion and rf.id_curso=h.id_curso
                
                WHERE
                                
                h.id_empresa='78'
                
                $JqFI $JqFT
                GROUP BY h.id_curso,h.codigo_inscripcion";
    }
    //echo $sql;
    $connexion->query($sql);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;
}

function Inserttbl_id_inscripcion_documentos($nombre_archivo, $id_imparticion, $rut, $nombre)
{
    $connexion = new DatabasePDO();
    $nombre = ($nombre);
    $hoy = date("Y-m-d");
    
    $sql = "INSERT INTO tbl_id_inscripcion_documentos (id_inscripcion, fecha, documento, rut, activo, nombre) " .
        "VALUES (:id_inscripcion, :fecha, :documento, :rut, '1', :nombre)";
    
    $connexion->query($sql);
    $connexion->bind(':id_inscripcion', $id_imparticion);
    $connexion->bind(':fecha', $hoy);
    $connexion->bind(':documento', $nombre_archivo);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':nombre', $nombre);
    $connexion->execute();
}

function Updatetbl_id_inscripcion_documentos($id, $activo)
{
    $connexion = new DatabasePDO();
    $sql = "UPDATE tbl_id_inscripcion_documentos SET activo = :activo WHERE id = :id";
    
    $connexion->query($sql);
    $connexion->bind(':activo', $activo);
    $connexion->bind(':id', $id);
    $connexion->execute();
}
function Updatetbl_id_inscripcion_proveedores($id)
{
    $connexion = new DatabasePDO();
    $sql = "delete from  tbl_id_inscripcion_proveedores  WHERE id = '$id'";

    $connexion->query($sql);
    $connexion->execute();
}
function IMPARTICION_CreaImparticion($id_empresa, $codigo_imparticion, $id_curso, $fecha_inicio, $fecha_termino, $direccion, $ciudad, $cupos, $id_audiencia, $tipo_audiencia, $arreglo_post, $sesiones, $ejecutivo, $comentarios)
{
    $connexion = new DatabasePDO();
    $sql = "INSERT INTO tbl_inscripcion_curso (codigo_inscripcion, fecha_inicio, fecha_termino, direccion, comuna, ciudad, id_curso, id_empresa, cupos, id_audiencia, tipo_audiencia,
        lunes_d_am, lunes_h_am, lunes_d_pm, lunes_h_pm,
        martes_d_am, martes_h_am, martes_d_pm, martes_h_pm,
        miercoles_d_am, miercoles_h_am, miercoles_d_pm, miercoles_h_pm,
        jueves_d_am, jueves_h_am, jueves_d_pm, jueves_h_pm,
        viernes_d_am, viernes_h_am, viernes_d_pm, viernes_h_pm, sesiones, ejecutivo, comentarios)
        VALUES (:codigo_inscripcion, :fecha_inicio, :fecha_termino, :direccion, :comuna, :ciudad, :id_curso, :id_empresa, :cupos, :id_audiencia, :tipo_audiencia,
        :lunes_d_am, :lunes_h_am, :lunes_d_pm, :lunes_h_pm,
        :martes_d_am, :martes_h_am, :martes_d_pm, :martes_h_pm,
        :miercoles_d_am, :miercoles_h_am, :miercoles_d_pm, :miercoles_h_pm,
        :jueves_d_am, :jueves_h_am, :jueves_d_pm, :jueves_h_pm,
        :viernes_d_am, :viernes_h_am, :viernes_d_pm, :viernes_h_pm, :sesiones, :ejecutivo, :comentarios)";
	$ciudadu ="";
    $connexion->query($sql);
    $connexion->bind(':codigo_inscripcion', $codigo_imparticion);
    $connexion->bind(':fecha_inicio', $fecha_inicio);
    $connexion->bind(':fecha_termino', $fecha_termino);
    $connexion->bind(':direccion', $direccion);
    $connexion->bind(':comuna', $ciudadu);
    $connexion->bind(':ciudad', $ciudad);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':cupos', $cupos);
    $connexion->bind(':id_audiencia', $id_audiencia);
    $connexion->bind(':tipo_audiencia', $tipo_audiencia);
    $connexion->bind(':lunes_d_am', $arreglo_post["lunes_d_am"]);
    $connexion->bind(':lunes_h_am', $arreglo_post["lunes_h_am"]);
    $connexion->bind(':lunes_d_pm', $arreglo_post["lunes_d_pm"]);
    $connexion->bind(':lunes_h_pm', $arreglo_post["lunes_h_pm"]);
    $connexion->bind(':martes_d_am', $arreglo_post["martes_d_am"]);
    $connexion->bind(':martes_h_am', $arreglo_post["martes_h_am"]);
    $connexion->bind(':martes_d_pm', $arreglo_post["martes_d_pm"]);
    $connexion->bind(':martes_h_pm', $arreglo_post["martes_h_pm"]);
    $connexion->bind(':miercoles_d_am', $arreglo_post["miercoles_d_am"]);
    $connexion->bind(':miercoles_h_am', $arreglo_post["miercoles_h_am"]);
    $connexion->bind(':miercoles_d_pm', $arreglo_post["miercoles_d_pm"]);
    $connexion->bind(':miercoles_h_pm', $arreglo_post["miercoles_h_pm"]);
    $connexion->bind(':jueves_d_am', $arreglo_post["jueves_d_am"]);
    $connexion->bind(':jueves_h_am', $arreglo_post["jueves_h_am"]);
    $connexion->bind(':jueves_d_pm', $arreglo_post["jueves_d_pm"]);
    $connexion->bind(':jueves_h_pm', $arreglo_post["jueves_h_pm"]);
    $connexion->bind(':viernes_d_am', $arreglo_post["viernes_d_am"]);
    $connexion->bind(':viernes_h_am', $arreglo_post["viernes_h_am"]);
    $connexion->bind(':viernes_d_pm', $arreglo_post["viernes_d_pm"]);
    $connexion->bind(':viernes_h_pm', $arreglo_post["viernes_h_pm"]);
    $connexion->bind(':sesiones', $sesiones);
    $connexion->bind(':ejecutivo', $ejecutivo);
    $connexion->bind(':comentarios', $comentarios);
    $connexion->execute();
}

function ActualizaCurso($id_curso, $nombre_curso, $descripcion_curso,
                        $modalidad, $tipo_curso, $prerequisito_curso,
                        $objetivo_curso, $sence, $numero_horas, $cantidad_maxima_participantes,
                        $rut_otec, $clasificacion_curso, $cbc, $valor_hora, $valor_hora_sence,
                        $contenidos_cursos, $nombre_curso_sence, $foco, $programa_bbdd, $codigo_sence,
                        $nombre_imagen, $nuevo_id_curso, $id_programa_global,
                        $foco2, $foco3, $tipo_actividad, $necesidad, $proveedor, $ejecutivo, $bajada)
{
    /*echo "<br>ActualizaCurso(id curso $id_curso, nombre $nombre_curso,desc $descripcion_curso,
                        mod $modalidad, tipo $tipo_curso, prequisito $prerequisito_curso,
                        objetivo $objetivo_curso, $sence, num horas $numero_horas, $cantidad_maxima_participantes,
                      rut_otec  $rut_otec, clasificacion_curso $clasificacion_curso, $cbc, $valor_hora, $valor_hora_sence,
                       contenidos_cursos $contenidos_cursos,nombre_curso_sence $nombre_curso_sence,
                       foco $foco, programa_bbdd $programa_bbdd,  codigo_sence $codigo_sence,
                        nombre_imagen $nombre_imagen, nuevo_id_curso $nuevo_id_curso, id_programa_global $id_programa_global,
                       foco 2 $foco2, foco3 $foco3, tipo_actividad $tipo_actividad, 
                       $necesidad, $proveedor, $ejecutivo, bajada $bajada)";*/



    $nombre_curso       =   utf8_encode($nombre_curso);
    $objetivo_curso     =   utf8_encode($objetivo_curso);
    $descripcion_curso  =   utf8_encode($descripcion_curso);

    $connexion = new DatabasePDO();
    $sql = "UPDATE tbl_lms_curso SET
            nombre = '$nombre_curso',
            descripcion = '$descripcion_curso',
            modalidad = :modalidad,
            tipo = :tipo_curso,
            prerequisito = :prerequisito_curso,
            objetivo_curso = :objetivo_curso,
            sence = :sence,
            codigosence = :codigo_sence,
            cbc = :cbc,
            numero_horas = :numero_horas,
            cantidad_maxima_participantes = :cantidad_maxima_participantes,
            valor_hora = :valor_hora,
            rut_otec = :rut_otec,
            imagen = :nombre_imagen,
            clasificacion = :clasificacion_curso,
            valor_hora_sence = :valor_hora_sence,
            contenidos_cursos = :contenidos_cursos,
            nombre_curso_sence = :nombre_curso_sence,
            id_empresa_programa = :programa_bbdd,
            id_foco = :foco,
            id_programa_global = :id_programa_global,
            bajada = :bajada
                     
            WHERE id = :id_curso";

    //echo $sql;

    $connexion->query($sql);
    $connexion->bind(':modalidad', $modalidad);
    $connexion->bind(':tipo_curso', $tipo_curso);
    $connexion->bind(':prerequisito_curso', $prerequisito_curso);
    $connexion->bind(':objetivo_curso', $objetivo_curso);
    $connexion->bind(':sence', $sence);
    $connexion->bind(':codigo_sence', $codigo_sence);
    $connexion->bind(':cbc', $cbc);
    $connexion->bind(':numero_horas', $numero_horas);
    $connexion->bind(':cantidad_maxima_participantes', $cantidad_maxima_participantes);
    $connexion->bind(':valor_hora', $valor_hora);
    $connexion->bind(':rut_otec', $rut_otec);
    $connexion->bind(':nombre_imagen', $nombre_imagen);
    $connexion->bind(':clasificacion_curso', $clasificacion_curso);
    $connexion->bind(':valor_hora_sence', $valor_hora_sence);
    $connexion->bind(':contenidos_cursos', $contenidos_cursos);
    $connexion->bind(':nombre_curso_sence', $nombre_curso_sence);
    $connexion->bind(':programa_bbdd', $programa_bbdd);
    $connexion->bind(':foco', $foco);
    $connexion->bind(':id_programa_global', $id_programa_global);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':bajada', $bajada);

    $connexion->execute();
   // exit();
}

function ActualizaReportesLms_2022($id_curso, $foco, $programa_bbdd, $numero_horas)
{
    $connexion = new DatabasePDO();
    $sql = "UPDATE tbl_lms_reportes SET id_foco = :foco, id_programa = :programa_bbdd, numero_horas = :numero_horas WHERE id_curso = :id_curso";
    
    $connexion->query($sql);
    $connexion->bind(':foco', $foco);
    $connexion->bind(':programa_bbdd', $programa_bbdd);
    $connexion->bind(':numero_horas', $numero_horas);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->execute();
}

function BuscaIMparticionesDadoIdCurso_2022($id_curso, $id_empresa)
{
    $connexion = new DatabasePDO();

    $sql = "SELECT * FROM tbl_inscripcion_curso WHERE id_curso = :id_curso AND id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function Datos_curso($id)
{
    $connexion = new DatabasePDO();

    $sql = "SELECT * FROM tbl_lms_curso WHERE id = :id";
    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $cod = $connexion->resultset();

    return $cod;
}

function ActualizaActivoInactivoCurso_2022($id_curso)
{
    $connexion = new DatabasePDO();

    $sql = "UPDATE tbl_lms_curso SET id_empresa = '99' WHERE id = :id_curso";
    $connexion->query($sql);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->execute();
}

/* function TraeImparticionEmpresa($id_curso, $id_empresa, $id_modalidad)
{
	//$variables = get_defined_vars();
    //echo "<pre>";
    //print_r($variables);
    //echo "</pre>";
	//exit;
	
    $connexion = new DatabasePDO();

    $sql_curso = "";
    $sql_modalidad = "";
    $sql_proveedor = "";
	
    if ($id_curso != '') {
        $sql_curso = " AND tbl_inscripcion_curso.id_curso = :id_curso";
        $connexion->bind(':id_curso', $id_curso);
    }
    if ($id_modalidad != "") {
        $sql_modalidad = " AND tbl_lms_curso.modalidad = :id_modalidad";
        $connexion->bind(':id_modalidad', $id_modalidad);
    }

    if ($_SESSION["perfil"] == "Proveedor") {
        $sql_proveedor = " AND tbl_inscripcion_curso.ciudad LIKE :user";
        $connexion->bind(':user', $_SESSION["user_"] . "%");
    }

    $sql = "SELECT
                tbl_inscripcion_curso.id_curso,
                tbl_inscripcion_curso.codigo_inscripcion,
                tbl_lms_curso.nombre,
                tbl_lms_curso.modalidad,
                tbl_lms_curso.numero_horas,
                tbl_inscripcion_curso.nombre AS nombre_imparticion,
                tbl_inscripcion_curso.fecha_inicio,
                tbl_inscripcion_curso.fecha_termino,
                tbl_inscripcion_curso.direccion,
                tbl_inscripcion_curso.ciudad,
                tbl_inscripcion_curso.comuna,
                tbl_inscripcion_curso.cupos,
                tbl_inscripcion_curso.sesiones,
                tbl_inscripcion_curso.observacion,
                tbl_inscripcion_curso.hora_inicio,
                tbl_inscripcion_curso.hora_termino,
                tbl_inscripcion_curso.ejecutivo AS rut_ejecutivo,
                tbl_inscripcion_curso.tipo_audiencia,
                tbl_inscripcion_curso.id_audiencia,
                (SELECT nombre FROM tbl_otec WHERE rut = tbl_inscripcion_curso.ciudad) AS nombre_proveedor,
                (SELECT nombre FROM tbl_ejecutivos WHERE rut = tbl_inscripcion_curso.ejecutivo) AS nombre_ejecutivo,
                (SELECT COUNT(DISTINCT(rut)) FROM tbl_inscripcion_usuarios WHERE id_curso = tbl_lms_curso.id AND id_inscripcion = tbl_inscripcion_curso.codigo_inscripcion) AS inscritos,
                (
                    SELECT COUNT(DISTINCT(k.rut))
                    FROM tbl_inscripcion_cierre k
                    WHERE k.id_curso = tbl_lms_curso.id
                    AND k.id_inscripcion = tbl_inscripcion_curso.codigo_inscripcion
                    AND k.porcentaje_asistencia <> '' AND (SELECT id FROM tbl_inscripcion_usuarios WHERE rut = k.rut AND id_inscripcion = k.id_inscripcion LIMIT 1)
                ) AS asistentes
            FROM tbl_inscripcion_curso
            LEFT JOIN tbl_lms_curso ON tbl_lms_curso.id = tbl_inscripcion_curso.id_curso
            WHERE tbl_inscripcion_curso.id_empresa = :id_empresa
                $sql_curso
                $sql_modalidad
                $sql_proveedor
                AND tbl_inscripcion_curso.id_curso NOT LIKE 'bch_%'
            ORDER BY tbl_inscripcion_curso.id DESC";

    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
} */

function TraeImparticionEmpresa($id_curso, $id_empresa, $id_modalidad)
{
    $connexion = new DatabasePDO();

    if ($id_curso != '') {
        $sql_curso = " AND tbl_inscripcion_curso.id_curso = :id_curso";
    }
    if ($id_modalidad != '') {
        $sql_modalidad = " AND tbl_lms_curso.modalidad = :id_modalidad";
    }

    if ($_SESSION["perfil"] == "Proveedor") {
        $sql_proveedor = " AND tbl_inscripcion_curso.ciudad LIKE :user_ciudad";
    } else {
        $sql_proveedor = "";
    }

    $sql = "SELECT
        tbl_inscripcion_curso.id_curso,
        tbl_inscripcion_curso.codigo_inscripcion,
        tbl_lms_curso.nombre,
        tbl_lms_curso.modalidad,
        tbl_lms_curso.numero_horas,
        tbl_inscripcion_curso.nombre AS nombre_imparticion,
        tbl_inscripcion_curso.fecha_inicio,
        tbl_inscripcion_curso.fecha_termino,
        tbl_inscripcion_curso.direccion,
        tbl_inscripcion_curso.ciudad,
        tbl_inscripcion_curso.comuna,
        tbl_inscripcion_curso.cupos,
        tbl_inscripcion_curso.sesiones,
        tbl_inscripcion_curso.observacion,
        tbl_inscripcion_curso.hora_inicio,
        tbl_inscripcion_curso.hora_termino,
        tbl_inscripcion_curso.ejecutivo AS rut_ejecutivo,
        tbl_inscripcion_curso.tipo_audiencia,
        tbl_inscripcion_curso.id_audiencia,
        tbl_inscripcion_curso.numero_dias,
        (SELECT nombre FROM tbl_otec WHERE rut = tbl_inscripcion_curso.ciudad) AS nombre_proveedor,
        (SELECT nombre FROM tbl_ejecutivos WHERE rut = tbl_inscripcion_curso.ejecutivo) AS nombre_ejecutivo,
        (SELECT COUNT(DISTINCT rut) FROM tbl_inscripcion_usuarios WHERE id_curso = tbl_lms_curso.id AND id_inscripcion = tbl_inscripcion_curso.codigo_inscripcion) AS inscritos,
        (SELECT COUNT(DISTINCT k.rut) FROM tbl_inscripcion_cierre k WHERE k.id_curso = tbl_lms_curso.id AND k.id_inscripcion = tbl_inscripcion_curso.codigo_inscripcion AND k.porcentaje_asistencia <> '' AND (SELECT id FROM tbl_inscripcion_usuarios WHERE rut = k.rut AND id_inscripcion = k.id_inscripcion LIMIT 1)) AS asistentes
    FROM tbl_inscripcion_curso
    LEFT JOIN tbl_lms_curso ON tbl_lms_curso.id = tbl_inscripcion_curso.id_curso
    WHERE tbl_inscripcion_curso.id_empresa = :id_empresa
    $sql_curso
    $sql_modalidad
    $sql_proveedor
    AND tbl_inscripcion_curso.id_curso NOT LIKE 'bch_%'
    ORDER BY tbl_inscripcion_curso.id DESC";

    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);

    if ($id_curso != '') {
        $connexion->bind(':id_curso', $id_curso);
    }
    if ($id_modalidad != '') {
        $connexion->bind(':id_modalidad', $id_modalidad);
    }
    if ($_SESSION["perfil"] == "Proveedor") {
        $connexion->bind(':user_ciudad', $_SESSION["user_"]."%");
    }

    $cod = $connexion->resultset();
    return $cod;
}

function AdminPerfil2021($user)
{
    //echo "user $user";
    $connexion = new DatabasePDO();
    $sql = "SELECT perfil FROM tbl_admin WHERE user = :user";
    //echo $sql;
    $connexion->query($sql);
    $connexion->bind(':user', $user);
    $cod = $connexion->resultset();
    return $cod[0]->perfil;
}
function AdminPerfilAcceso2024($user)
{
    //echo "user $user";
    $connexion = new DatabasePDO();
    $sql = "SELECT acceso FROM tbl_admin WHERE user = :user";
    //echo $sql;
    $connexion->query($sql);
    $connexion->bind(':user', $user);
    $cod = $connexion->resultset();
    return $cod[0]->acceso;
}
function Total_Inscripciones($id_empresa, $id_inscripcion)
{
    $connexion = new DatabasePDO();


    $sql = "SELECT
tbl_inscripcion_cierre.id
FROM tbl_inscripcion_cierre
INNER JOIN tbl_usuario ON tbl_usuario.rut=tbl_inscripcion_cierre.rut

left join tbl_empresa_holding
on tbl_empresa_holding.rut=tbl_usuario.empresa_holding


where tbl_inscripcion_cierre.id_inscripcion='$id_inscripcion' AND tbl_inscripcion_cierre.id_empresa='$id_empresa' GROUP BY  tbl_usuario.nombre_empresa_holding";
    $connexion->query($sql);

    $cod = $connexion->resultset();
    return $cod;
}

function data_rel_id_inscripcion_curso_groupby_id_imparticion_data($id_inscripcion)
{

    $connexion = new DatabasePDO();

    $sql = "	select h.*,
(select descripcion from tbl_lms_foco where codigo_foco=h.id_foco) as foco,
(select nombre_programa from tbl_lms_programas_bbdd where id_programa=h.id_programa) as programa

from rel_lms_id_curso_id_inscripcion h 
where h.id_inscripcion='" . $id_inscripcion . "'
group by h.id_inscripcion";
//echo $sql;
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;

}
function InsertUpdatetbl_rel_lms_id_curso_id_inscripcion($id_curso, $id_inscripcion, $nombre_inscripcion, $fecha_inicio_inscripcion, $fecha_termino_inscripcion, $id_empresa, $opcional, $rut_ejecutivo, $rut_ejecutivo_externo, $sence, $cod_sence, $nombre_curso_sence, $nombre_otic, $modalidad_imparticion, $costos_asociados)
{

    $nombre_inscripcion = ($nombre_inscripcion);

    $connexion = new DatabasePDO();
    $nombre_curso_sence = ($nombre_curso_sence);

    $sql0 = "select id from tbl_inscripcion_curso where id_curso='" . $id_curso . "' and codigo_inscripcion='" . $id_inscripcion . "'";
    
	$connexion->query($sql0);
	$cod0 = $connexion->resultset();
    if ($cod0[0]->id > 0) {
        $sql1 = "update  tbl_inscripcion_curso 
        
        set codigo_inscripcion='$id_inscripcion', id_curso='$id_curso', fecha_inicio='$fecha_inicio_inscripcion', fecha_termino='$fecha_termino_inscripcion', 
            id_empresa='$id_empresa', nombre='$nombre_inscripcion', ejecutivo='$rut_ejecutivo', archivo='$rut_ejecutivo_externo',
            sence='$sence', cod_sence='$cod_sence', nombre_curso_sence='$nombre_curso_sence', otic='$nombre_otic', modalidad='$modalidad_imparticion', valor_curso='$costos_asociados'
            where id='" . $cod0[0]->id . "'
            ;";
	$connexion->query($sql1);
    $connexion->execute();

        //echo "<br>sql1 $sql1</br>";exit();
    } else {
        $sql1 = "INSERT INTO tbl_inscripcion_curso (codigo_inscripcion, id_curso, fecha_inicio, fecha_termino, id_empresa, nombre, ejecutivo, archivo, sence, cod_sence, nombre_curso_sence, otic, modalidad, valor_curso) " .
            "VALUES ('$id_inscripcion', '$id_curso', '$fecha_inicio_inscripcion', '$fecha_termino_inscripcion', '$id_empresa', '$nombre_inscripcion', '$rut_ejecutivo', '$rut_ejecutivo_externo','$sence', '$cod_sence', '$nombre_curso_sence', '$nombre_otic','$modalidad_imparticion','$costos_asociados');";
       $connexion->query($sql1);
    $connexion->execute();


    }


    $sql2 = "select id from rel_lms_id_curso_id_inscripcion where id_curso='" . $id_curso . "' and id_inscripcion='" . $id_inscripcion . "'";
    //echo "<br>sql2 $sql2</br>";
    $connexion->query($sql2);
	$cod = $connexion->resultset();
   

    if ($cod[0]->id > 0) {
        $sql3 = "UPDATE rel_lms_id_curso_id_inscripcion 
    
    set 
    
    fecha_inicio_inscripcion='$fecha_inicio_inscripcion',
    fecha_termino_inscripcion='$fecha_termino_inscripcion',
    opcional='$opcional' ,
    rut_ejecutivo='$rut_ejecutivo',
    nombre_inscripcion='$nombre_inscripcion'
    
    where id_curso='$id_curso'
    and id_inscripcion='$id_inscripcion'
    ";
      
      
	$connexion->query($sql3);
    $connexion->execute();

    } else {
        $sql3 = "INSERT INTO rel_lms_id_curso_id_inscripcion (id_curso, id_inscripcion, nombre_inscripcion, fecha_inicio_inscripcion, fecha_termino_inscripcion, id_empresa, opcional, rut_ejecutivo) " .
            "VALUES ('$id_curso', '$id_inscripcion', '$nombre_inscripcion', '$fecha_inicio_inscripcion', '$fecha_termino_inscripcion', '$id_empresa', '$opcional', '$rut_ejecutivo');";
      
	  $connexion->query($sql3);
    $connexion->execute();
    }

}
function Lista_curso_Crea_IMPARTICION_CreaImparticion($id_empresa, $codigo_imparticion, $id_curso, $fecha_inicio, $fecha_termino, $direccion, $ciudad, $cupos, $id_malla, $tipo_audiencia, $arreglo_post, $sesiones, $ejecutivo, $comentarios, $observacion, $hora_inicio, $hora_termino, $nombre, $relator, $streaming, $minimo_asistencia, $minimo_nota_aprobacion, $ejecutivo_externo)
{
    $connexion = new DatabasePDO();
    //$comentarios1=($comentarios);
    //$comentarios2=($comentarios);
    $comentarios3=utf8_encode($comentarios);
    $nombre=utf8_encode($nombre);
    $sql = "select id from tbl_inscripcion_curso where codigo_inscripcion='$codigo_imparticion' limit 1";
    $connexion->query($sql);
    $cod = $connexion->resultset();
	$malla_bch=$_POST["malla_bch"];
	$categoria_bch=$_POST["categoria_bch"];
    if ($streaming <> "") {
        $direccion = $streaming;
        $comuna = "STREAMING";
    } else {
        $direccion = $direccion;
        $comuna = "";

    }

    $id = $cod[0]->id;
    //echo "id $id";
    if ($id > 0) {

        if($relator<>""){
            $qrelator=" id_audiencia='$relator', ";
        } else {
            $qrelator="";
        }

        $sql = "
update tbl_inscripcion_curso
set 
codigo_inscripcion='" . $codigo_imparticion . "',
fecha_inicio='$fecha_inicio', 
fecha_termino='$fecha_termino', 
direccion='$direccion', 
comuna='$comuna', 
ciudad='$ciudad', 
id_curso='$id_curso', 
id_empresa='$id_empresa', 
cupos='$cupos', 
id_malla='$id_malla', 
tipo_audiencia='$tipo_audiencia', 
ejecutivo='$ejecutivo', 
comentarios='$comentarios3',
observacion='$observacion', 
hora_inicio='$hora_inicio', 
hora_termino='$hora_termino', 
nombre='" . $nombre . "',
$qrelator
archivo='" . $ejecutivo_externo . "',
minimo_asistencia='$minimo_asistencia',
minimo_nota_aprobacion='$minimo_nota_aprobacion',
abierto_cerrado='$malla_bch', 
pago='$categoria_bch'

where id='" . $id . "'";

    } else {
        $sql = "
INSERT INTO tbl_inscripcion_curso
(codigo_inscripcion, fecha_inicio, fecha_termino, direccion, comuna, ciudad, id_curso, id_empresa, cupos, id_malla, tipo_audiencia, ejecutivo, comentarios,observacion, hora_inicio, hora_termino, nombre,id_audiencia,minimo_asistencia,minimo_nota_aprobacion, abierto_cerrado, pago) " .
            "VALUES ('$codigo_imparticion', '$fecha_inicio', '$fecha_termino', '$direccion', '$comuna', '$ciudad', '$id_curso', '$id_empresa', '$cupos', '$id_malla', '$tipo_audiencia','$ejecutivo','$comentarios','$observacion', '$hora_inicio', '$hora_termino', '$nombre', '$relator', '$minimo_asistencia', '$minimo_nota_aprobacion', '$malla_bch', '$categoria_bch');";
    }


	    $connexion->query($sql);
    $connexion->execute();
   // echo $sql; exit();
}
function DatosInscripcionConCodigo($id_inscripcion)
{
    $connexion = new DatabasePDO();

    $sql = "SELECT * FROM tbl_inscripcion_curso WHERE codigo_inscripcion = :id_inscripcion";

    $connexion->query($sql);
    $connexion->bind(':id_inscripcion', $id_inscripcion);
    $cod = $connexion->resultset();

    return $cod;
}

function BuscaIdCursoDadoIdImparticion_2021($id_inscripcion)
{
    $connexion = new DatabasePDO();

    $sql = "SELECT h.* FROM tbl_inscripcion_curso h WHERE h.codigo_inscripcion = :id_inscripcion";

    $connexion->query($sql);
    $connexion->bind(':id_inscripcion', $id_inscripcion);
    $cod = $connexion->resultset();

    return $cod[0]->id_curso;
}

function InscripcionCheckImparticionrepetido_2023($id_curso, $id_imparticion)
{
    $connexion = new DatabasePDO();

    $sql = "SELECT id FROM tbl_inscripcion_curso WHERE id_curso <> :id_curso AND codigo_inscripcion = :id_imparticion";

    $connexion->query($sql);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':id_imparticion', $id_imparticion);
    $cod = $connexion->resultset();

    return $cod[0]->id;
}
function BuscaIdFoco_DadoIdMalla_IdPrograma_2022($id_malla,$id_programa){
    $connexion = new DatabasePDO();
    $sql = "
            select h.id_foco from rel_lms_malla_curso h where h.id_malla='$id_malla' and h.id_programa='$id_programa' and h.id_foco<>'' limit 1
            ";

    $connexion->query($sql);
    $cod = $connexion->resultset();

    return $cod[0]->id_foco;
}
function TraeEjecutivosDeTabla($id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "
            select h.*, h.nombre as nombre_ejecutivo
            from tbl_ejecutivos h
            order by h.nombre
            ";

    $connexion->query($sql);
    $cod = $connexion->resultset();

    return $cod;
}
function Listado_TotalMallas1($id_empresa, $id_curso, $llamada)
{
    $connexion = new DatabasePDO();

    $filtro = "";

    $sql = "SELECT h.id AS id_malla, h.nombre AS nombre_malla, h.descripcion, j.id_programa, j.nombre_programa
            FROM tbl_lms_malla h
            INNER JOIN rel_lms_malla_curso k ON k.id_malla = h.id
            INNER JOIN tbl_lms_programas_bbdd j ON j.id_programa = k.id_programa
            WHERE j.inactivo IS NULL
            AND h.formacioncontinua = '0'
            AND h.id <> 'epf'
            AND h.id <> 'bch_hist'
            GROUP BY h.id
            ORDER BY j.nombre_programa, h.nombre";

    $connexion->query($sql);
    $cod = $connexion->resultset();

    return $cod;
}
function Dashboard_Training_IdImparticion_idCurso_MiRut_data($id_inscripcion, $id_curso, $rut)
{
    //echo "Dashboard_Training_IdImparticion_idCurso_MiRut_data($id_inscripcion, $id_curso, $rut";
    $connexion = new DatabasePDO();

    $whereIdCursoIdImparticion = "";

    if ($id_curso <> "") {
        $whereIdCursoIdImparticion = " and h.id_curso = '$id_curso' ";
        //$connexion->bind(':id_curso', $id_curso);
    }
    if ($id_inscripcion <> "") {
        $whereIdCursoIdImparticion = " and h.id_inscripcion = '$id_inscripcion' ";
        //$connexion->bind(':id_inscripcion', $id_inscripcion);
    }

    $sql = "SELECT h.* FROM tbl_inscripcion_usuarios h WHERE h.id > 0 $whereIdCursoIdImparticion";

    $connexion->query($sql);
    $cod = $connexion->resultset();

    return $cod;
}

function Dashboard_check_Lms_InscripcionCierre_data($id_imparticion, $id_curso, $rut)
{
    $connexion = new DatabasePDO();

    $sql = "SELECT h.* FROM tbl_inscripcion_cierre h WHERE h.rut = :rut AND h.id_inscripcion = :id_imparticion AND h.id_curso = :id_curso";

    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_imparticion', $id_imparticion);
    $connexion->bind(':id_curso', $id_curso);

    $cod = $connexion->resultset();

    return $cod;
}
function EncuestaSatisfaccion_IdImparticionIdCurso_data($id_imparticion, $id_curso)
{
    $connexion = new DatabasePDO();

    if ($id_imparticion <> "") {
        $sql = "SELECT COUNT(id) AS cuenta_encuestas, id_encuesta FROM tbl_enc_elearning_respuestas WHERE id_curso = :id_curso AND id_tipo = '$id_imparticion' GROUP BY rut;";
    } else {
        $sql = "SELECT COUNT(id) AS cuenta_encuestas, id_encuesta FROM tbl_enc_elearning_respuestas WHERE id_curso = :id_curso GROUP BY rut;";
    }

    if ($id_curso <> "") {
        $DatosCurso = DatosCurso_2($id_curso);
    }
    if ($DatosCurso[0]->modalidad == "1") {
        $sql = "SELECT COUNT(id) AS cuenta_encuestas, id_encuesta FROM tbl_enc_elearning_respuestas WHERE id_curso = :id_curso GROUP BY rut;";
    }

    $connexion->query($sql);
    $connexion->bind(':id_curso', $id_curso);
    $cod = $connexion->resultset();

    return $cod;
}

function EncuestaSatisfaccion_BuscaPreguntas($id_encuesta)
{
    $connexion = new DatabasePDO();

    $sql = "SELECT * FROM tbl_enc_elearning_preg WHERE id_encuesta = :id_encuesta";

    $connexion->query($sql);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $cod = $connexion->resultset();

    return $cod;
}

function EncuestaSatisfaccion_BuscaRespuestas($id_encuesta, $id_pregunta, $id_imparticion, $id_curso)
{
    $connexion = new DatabasePDO();

    if ($id_imparticion <> "") {
        $sql = "SELECT * FROM tbl_enc_elearning_respuestas WHERE id_encuesta = :id_encuesta AND id_curso = :id_curso AND id_tipo = :id_imparticion AND id_pregunta = :id_pregunta";
        $connexion->bind(':id_imparticion', $id_imparticion);
    } else {
        $sql = "SELECT * FROM tbl_enc_elearning_respuestas WHERE id_encuesta = :id_encuesta AND id_curso = :id_curso AND id_pregunta = :id_pregunta";
    }

    if ($id_curso <> "") {
        $DatosCurso = DatosCurso_2($id_curso);
    }
    if ($DatosCurso[0]->modalidad == "1") {
        $sql = "SELECT * FROM tbl_enc_elearning_respuestas WHERE id_encuesta = :id_encuesta AND id_curso = :id_curso AND id_pregunta = :id_pregunta";
    }

    $connexion->query($sql);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':id_pregunta', $id_pregunta);
    $cod = $connexion->resultset();

    return $cod;
}

function EncuestaSatisfaccion_BuscaComentarios($id_encuesta, $id_imparticion, $id_curso)
{
    $connexion = new DatabasePDO();

    $sql = "SELECT * FROM tbl_enc_elearning_respuestas WHERE id_encuesta = :id_encuesta AND id_curso = :id_curso AND id_tipo = :id_imparticion AND comentario <> '' GROUP BY comentario;";

    if ($id_curso <> "") {
        $DatosCurso = DatosCurso_2($id_curso);
    }
    if ($DatosCurso[0]->modalidad == "1") {
        $sql = "SELECT * FROM tbl_enc_elearning_respuestas WHERE id_encuesta = :id_encuesta AND id_curso = :id_curso AND comentario <> '' GROUP BY comentario;";
    }

    $connexion->query($sql);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':id_imparticion', $id_imparticion);
    $cod = $connexion->resultset();

    return $cod;
}
function TraigoRegistrosPorSesionDeCheckinPorImparticion_2021($codigo_inscripcion, $id_empresa)
{
    $connexion = new DatabasePDO();

    $datos_empresa = DatosEmpresa($id_empresa);
    $campo1 = $datos_empresa[0]->campo1;
    $campo2 = $datos_empresa[0]->campo2;
    $campo3 = $datos_empresa[0]->campo3;

    $filtro1 = $filtro2 = $filtro3 = '';

    if ($campo1) {
        $filtro1 = " tbl_usuario." . $campo1 . " as campo1, ";
    }

    if ($campo2) {
        $filtro2 = " tbl_usuario." . $campo2 . " as campo2, ";
    }

    if ($campo3) {
        $filtro3 = " tbl_usuario." . $campo3 . " as campo3, ";
    }

    $sql = "
        SELECT
            h.*,
            (SELECT porcentaje_asistencia FROM tbl_inscripcion_cierre WHERE id_inscripcion = h.id_inscripcion AND rut = h.rut) AS asistencia,
            (SELECT estado_descripcion FROM tbl_inscripcion_cierre WHERE id_inscripcion = h.id_inscripcion AND rut = h.rut) AS estado_descripcion,
            (SELECT nota FROM tbl_inscripcion_cierre WHERE id_inscripcion = h.id_inscripcion AND rut = h.rut) AS nota,
            (SELECT caso_especial FROM tbl_inscripcion_cierre WHERE id_inscripcion = h.id_inscripcion AND rut = h.rut) AS nota_diagnostico
        FROM
            tbl_inscripcion_usuarios h 
        WHERE
            h.id_inscripcion = :codigo_inscripcion
            AND h.id_empresa = :id_empresa
            AND h.rut <> ''
            AND h.rut NOT IN ('12121212', '12345', '13131313', '14141414')
    ";

    $connexion->query($sql);
    $connexion->bind(':codigo_inscripcion', $codigo_inscripcion);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();

    return $cod;
}
function BorraUsuariosParaVolveraCrearlosTblInscripcionUsuarios($id_imparticion)
{
    $connexion = new DatabasePDO();

    $sql = "DELETE FROM tbl_inscripcion_usuarios WHERE id_inscripcion = :id_imparticion";
    $connexion->query($sql);
    $connexion->bind(':id_imparticion', $id_imparticion);
    $connexion->execute();
}
function BuscaRutDadoRutEmailIdSap_2021($rut)
{
    $connexion = new DatabasePDO();

    $sql = "SELECT rut FROM tbl_usuario WHERE (rut = :rut OR email = :rut OR codigo_sap = :rut) AND vigencia = '0'";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->execute();
    $cod = $connexion->resultset();

    return $cod[0]->rut;
}
function InsertaRelacionInscripcionUsuarioFull2021($rut, $id_programa, $id_inscripcion, $id_curso, $Imp, $id_empresa)
{

    $rut = BuscaRutDadoRutEmailIdSap_2021($rut);
	$connexion = new DatabasePDO();
    $Imp = DatosCompletosImparticion_2021($id_inscripcion);
    //print_r($Imp);exit();
    $hoy = date("Y-m-d");
    if ($Imp[0]->id_modalidad == "1") {

        $sqlIYU = "INSERT INTO rel_lms_id_curso_id_inscripcion
								(id_curso,
								id_inscripcion,

								nombre_inscripcion,

								fecha_inicio_inscripcion,
								fecha_termino_inscripcion,

								id_empresa,

								opcional,
								activa,

								rut_ejecutivo,

								id_malla,
								id_programa,
								id_foco
								)
								VALUES ('" . $Imp[0]->id_curso . "', '" . $Imp[0]->codigo_inscripcion . "', 

								'" . $Imp[0]->nombre . "',

								'" . $Imp[0]->fecha_inicio . "', '" . $Imp[0]->fecha_termino . "', 

								'" . $id_empresa . "',

								'', '', 

								'" . $Imp[0]->sabado_d_am . "',

								'" . $Imp[0]->id_malla . "', '" . $Imp[0]->id_programa . "', 	'" . $Imp[0]->id_foco . "');";

     $connexion->query($sqlIYU);
    $connexion->execute();

        $sqlIYU = "INSERT INTO rel_lms_rut_id_curso_id_inscripcion
								(	rut,
								id_curso,	id_inscripcion,
								nombre_inscripcion,
								fecha_inicio_inscripcion,
								fecha_termino_inscripcion,

								id_empresa,
								opcional,

								avance_historico,
								nota_historico,
								estado_historico,

								activa,

								rut_ejecutivo

								)
								VALUES ('$rut', 
								'" . $Imp[0]->id_curso . "', '" . $Imp[0]->codigo_inscripcion . "', 
								'" . $Imp[0]->nombre . "',
								'" . $Imp[0]->fecha_inicio . "', '" . $Imp[0]->fecha_termino . "',

								'" . $id_empresa . "', '',

								'','','',
								'',	'" . $Imp[0]->ejecutivo . "');";

         $connexion->query($sqlIYU);
    $connexion->execute();

    } elseif ($Imp[0]->id_modalidad == "2") {

        $sql1 = "SELECT id from tbl_inscripcion_usuarios
								where id_curso='$id_curso' and rut='$rut' and id_malla='{$Imp[0]->id_malla}' and id_empresa='$id_empresa'";

		 $connexion->query($sql1);
    	$cod1 = $connexion->resultset();
        $estaigual = count($cod1);

        if ($estaigual > 0) {

        } else {

            $modalidad_idCurso = ModalidadCurso($id_curso, $id_empresa);

            $sqlOp = "SELECT opcional from rel_lms_malla_curso
								where id_curso='$id_curso' and id_malla='$id_malla' and id_programa='$id_programa' and id_empresa='$id_empresa' limit 1";
            
			
					    $connexion->query($sqlOp);
    		$codOp = $connexion->resultset();

			$sql2 = "SELECT id from tbl_inscripcion_usuarios
								where id_curso='$id_curso' and rut='$rut' and id_inscripcion='$id_inscripcion'";

            $connexion->query($sql2);
    		$codOp = $connexion->resultset();

            //echo "sql2 $sql2";
           // print_r($codOp);
			if(count($codOp)>0){

               // echo "A";exit();

            }else{
                //echo "B";
            $sqlIYU = "INSERT INTO tbl_inscripcion_usuarios
								(id_malla, id_curso, id_inscripcion,  rut, id_empresa, fecha)
								VALUES ('presencial', '$id_curso', '$id_inscripcion', '$rut', '$id_empresa', '" . $Imp[0]->fecha_inicio . "');";

            //echo "<br>sqlIYU $sqlIYU";exit();
          $connexion->query($sqlIYU);
		$connexion->execute();
			}
        }
        //echo $sqlIYU;exit();
    } elseif ($Imp[0]->id_modalidad == "3") {

        $sql1 = "SELECT id from tbl_inscripcion_postulantes
						where id_curso='$id_curso' and rut='$rut' id_empresa='$id_empresa'";
       
			$connexion->query($sql1);
    		$cod1 = $connexion->resultset();
		
        $estaigual = count($cod1);

        if ($estaigual > 0) {

        } else {

            $modalidad_idCurso = ModalidadCurso($id_curso, $id_empresa);

            $sqlOp = "SELECT opcional from rel_lms_malla_curso
						where id_curso='$id_curso' and id_malla='{$Imp[0]->id_malla}' and id_programa='$id_programa' and id_empresa='$id_empresa' limit 1";
			
			$connexion->query($sqlOp);
    		$codOp = $connexion->resultset();

            $sqlIYU = "INSERT INTO tbl_inscripcion_postulantes
						(rut, id_inscripcion, id_curso, fecha, id_empresa)
						VALUES ('$rut', '$id_inscripcion', '$id_curso', '$hoy', '$id_empresa');";

          $connexion->query($sqlIYU);
			$connexion->execute();

        }

    }

}
function VerificaCursoEstaEnCierrePorEmpresaImparticion($id_curso, $rut, $id_empresa, $id_imparticion)
{
    $connexion = new DatabasePDO();

    $sql = "SELECT * FROM tbl_inscripcion_cierre WHERE id_curso = :id_curso AND rut = :rut AND id_empresa = :id_empresa AND id_inscripcion = :id_imparticion";
    $connexion->query($sql);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_imparticion', $id_imparticion);
    $connexion->execute();
    $cod = $connexion->resultset();

    return $cod;
}
function LMS_InsertaRegistroCierreCursoImparticion_2022($rut, $id_curso, $id_empresa, $nota, $estado, $avance, $id_inscripcion, $porcentaje_asistencia,$caso_especial)
{

   $connexion = new DatabasePDO();

    if ($estado == "Reprobado" or $estado == "REPROBADO") {
        $id_estado = "0";
        $estado = "REPROBADO";
    } else if ($estado == "Aprobado" or $estado == "APROBADO") {
        $id_estado = "1";
        $estado = "APROBADO";
    }
    if ($avance == "") {
        $avance = 0;
    }

    $Imp = DatosCompletosImparticion_2021($id_inscripcion);            //print_r($Imp);
    $Tipo_Imparticion = $Imp[0]->tipo_audiencia;
    $MinimoAsistencia = $Imp[0]->minimo_asistencia;
    $MinimoNota = $Imp[0]->minimo_nota_aprobacion;
    //echo "<br>--> Tipo_Imparticion $Tipo_Imparticion MinimoAsistencia $MinimoAsistencia MinimoNota $MinimoNota";

    if ($Tipo_Imparticion == "fecha_inicio_termino") {
        // FECHA INICIO TERMINO

        if ($MinimoAsistencia > 0 and $MinimoNota > 0) {
            //echo "<br> - caso 1";
            if ($avance >= $MinimoAsistencia) {
                if ($nota >= $MinimoNota) {
                    $estado_descripcion = "APROBADO";
                    $estado = "1";
                } else {
                    $estado_descripcion = "REPROBADO";
                    $estado = "1";
                }
            } else {
                $estado_descripcion = "REPROBADO";
                $estado = "1";
            }

        } elseif ($MinimoAsistencia > 0 and $MinimoNota == "") {
            //echo "<br> - caso 2";
            if ($avance >= $MinimoAsistencia) {
                $estado_descripcion = "APROBADO";
                $estado = "1";
            } else {
                $estado_descripcion = "REPROBADO";
                $estado = "1";
            }
        } elseif ($MinimoAsistencia == "" and $MinimoNota > 0) {
            //echo "<br> - caso 3";
            if ($nota >= $MinimoNota) {
                $estado_descripcion = "APROBADO";
                $estado = "1";
            } else {
                $estado_descripcion = "REPROBADO";
                $estado = "1";
            }
        } else {
            //echo "<br> - caso 4";
            $estado_descripcion = "APROBADO";
            $estado = "1";
        }

    } else {
        // POR SESIONES

        // formula para calcular avance segun sesiones
        $Num_sesiones = ImparticionesNumSesiones_2021($id_inscripcion);
        $avance = CalculaAvanceSegunSesiones_2022($id_inscripcion, $Num_sesiones, $rut);
        $porcentaje_asistencia = $avance;

        if ($MinimoAsistencia > 0 and $MinimoNota > 0) {
            //echo "<br> - caso 5";


            //echo "--> rut $rut avance $avance, nota $nota";
            if ($avance >= $MinimoAsistencia) {
                if ($nota >= $MinimoNota) {
                    $estado_descripcion = "APROBADO";
                    $estado = "1";
                } else {
                    $estado_descripcion = "REPROBADO";
                    $estado = "1";
                }
            } else {
                $estado_descripcion = "REPROBADO";
                $estado = "1";
            }

        } elseif ($MinimoAsistencia > 0 and $MinimoNota == "") {
            //	echo "<br> - caso 6";
            if ($avance >= $MinimoAsistencia) {
                $estado_descripcion = "APROBADO";
                $estado = "1";
            } else {
                $estado_descripcion = "REPROBADO";
                $estado = "1";
            }
        } elseif ($MinimoAsistencia == "" and $MinimoNota > 0) {
            //echo "<br> - caso 7";
            if ($nota >= $MinimoNota) {
                $estado_descripcion = "APROBADO";
                $estado = "1";
            } else {
                $estado_descripcion = "REPROBADO";
                $estado = "1";
            }
        } else {
            //echo "<br> - caso 8";
            $estado_descripcion = "APROBADO";
            $estado = "1";
        }


    }


    //echo "<br>--> estado_descripcion $estado_descripcion, estado $estado";


    // AUTOMATIZA EL ESTADO

    $sql = "INSERT INTO tbl_inscripcion_cierre (rut, nota, estado, estado_descripcion, id_curso, id_empresa, avance, id_inscripcion, porcentaje_asistencia, anulado, caso_especial) " .
        "VALUES ('$rut', '$nota','$estado', '$estado_descripcion', '$id_curso', '$id_empresa', '$avance', '$id_inscripcion', '$porcentaje_asistencia', 'AUTOMATIZADO', '$caso_especial');";

    //echo "<br><BR>".$sql;exit;


	$connexion->query($sql);
	$connexion->execute();
}
function ModalidadCurso($id_curso, $id_empresa)
{
    $connexion = new DatabasePDO();

    $sql = "SELECT modalidad FROM tbl_lms_curso WHERE id = '$id_curso' AND id_empresa = '$id_empresa'";
    $connexion->query($sql);
    $connexion->execute();

    $cod = $connexion->resultset();

    return $cod[0]->modalidad;
}
function CalculaAvanceSegunSesiones_2022($id_inscripcion, $Num_sesiones, $rut)
{


    $Ses = Busca_Todas_Las_Sesiones_2022($id_inscripcion);
    //print_r($Ses);
    $suma_todo_tiempo = 0;
    $suma_tiempos_rut = 0;
    foreach ($Ses as $unico) {
        //echo "<br> sesion ".$unico->sesion." ".$unico->hora_desde." ".$unico->hora_hasta."";
        $tiempo = TiempoTranscurrido_Full_2021($unico->hora_desde, $unico->hora_hasta);
        //echo "---> tiempo $tiempo";

        $asistencia_por_sesion_rut = Asistencia2021_Avance_Sesiones($rut, $id_inscripcion, $unico->sesion);

        //echo "<br>asistencia_por_sesion_rut .$asistencia_por_sesion_rut.<br>";
        if ($asistencia_por_sesion_rut == "checked") {
            //echo "aaaa";
            $suma_tiempos_rut = $tiempo + $suma_tiempos_rut;
        }
        $suma_todo_tiempo = $tiempo + $suma_todo_tiempo;

    }

    //echo "<br>--> CalculaAvanceSegunSesiones_2022($id_inscripcion, $Num_sesiones, $rut)";
    //echo "<br>suma_tiempos_rut $suma_tiempos_rut / suma_todo_tiempo $suma_todo_tiempo";

    $avance = round($suma_tiempos_rut * 100 / $suma_todo_tiempo);
    //echo "avance $avance";
    return $avance;

}
function Busca_Todas_Las_Sesiones_2022($id_imparticion)
{
    $connexion = new DatabasePDO();

    $sql = "SELECT * FROM tbl_inscripcion_curso_sesiones_horarios WHERE id_inscripcion = :id_imparticion";
    $connexion->query($sql);
    $connexion->bind(':id_imparticion', $id_imparticion);
    $connexion->execute();
    $cod = $connexion->resultset();

    return $cod;
}

function Asistencia2021_Avance_Sesiones($rut, $id_imparticion, $sesion)
{
    $connexion = new DatabasePDO();

    $sesion_check = "sesion" . $sesion;
    $sql = "SELECT $sesion_check AS cuenta FROM tbl_inscripcion_curso_sesiones_horarios_rut WHERE id_inscripcion = :id_imparticion AND rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':id_imparticion', $id_imparticion);
    $connexion->bind(':rut', $rut);
    $connexion->execute();
    $cod = $connexion->resultset();

    if ($cod[0]->cuenta > 0) {
        $checked = "checked";
    } else {
        $checked = "";
    }

    return $checked;
}

function LMS_ActualizaRegistroCierreCursoImparticion_2022($rut, $id_curso, $id_empresa, $nota, $estado, $avance, $id_inscripcion, $porcentaje_asistencia, $caso_especial)
{
    $connexion = new DatabasePDO();
    $estado = trim($estado);

    if ($estado == "Reprobado" or $estado == "REPROBADO") {
        $id_estado = "0";
        $estado = "REPROBADO";
    } else if ($estado == "Aprobado" or $estado == "APROBADO") {
        $id_estado = "1";
        $estado = "APROBADO";
    }

    if ($avance == "") {
        $avance = 0;
    }

    $Imp = DatosCompletosImparticion_2021($id_inscripcion);            //print_r($Imp);
    $Tipo_Imparticion = $Imp[0]->tipo_audiencia;
    $MinimoAsistencia = $Imp[0]->minimo_asistencia;
    $MinimoNota = $Imp[0]->minimo_nota_aprobacion;
    //echo "<br>--> Tipo_Imparticion $Tipo_Imparticion MinimoAsistencia $MinimoAsistencia MinimoNota $MinimoNota";

    if ($Tipo_Imparticion == "fecha_inicio_termino") {
        // FECHA INICIO TERMINO

        if ($MinimoAsistencia > 0 and $MinimoNota > 0) {
            echo "<br> - caso 1";
            if ($avance >= $MinimoAsistencia) {
                if ($nota >= $MinimoNota) {
                    $estado_descripcion = "APROBADO";
                    $estado = "1";
                } else {
                    $estado_descripcion = "REPROBADO";
                    $estado = "1";
                }
            } else {
                $estado_descripcion = "REPROBADO";
                $estado = "1";
            }

        } elseif ($MinimoAsistencia > 0 and $MinimoNota == "") {
            echo "<br> - caso 2";
            if ($avance >= $MinimoAsistencia) {
                $estado_descripcion = "APROBADO";
                $estado = "1";
            } else {
                $estado_descripcion = "REPROBADO";
                $estado = "1";
            }
        } elseif ($MinimoAsistencia == "" and $MinimoNota > 0) {
            echo "<br> - caso 3";
            if ($nota >= $MinimoNota) {
                $estado_descripcion = "APROBADO";
                $estado = "1";
            } else {
                $estado_descripcion = "REPROBADO";
                $estado = "1";
            }
        } else {
            echo "<br> - caso 4";
            $estado_descripcion = "APROBADO";
            $estado = "1";
        }

    } else {
        // POR SESIONES

        // formula para calcular avance segun sesiones
        $Num_sesiones = ImparticionesNumSesiones_2021($id_inscripcion);
        $avance = CalculaAvanceSegunSesiones_2022($id_inscripcion, $Num_sesiones, $rut);


        if ($MinimoAsistencia > 0 and $MinimoNota > 0) {
            echo "<br> - caso 5";


            //echo "--> rut $rut avance $avance, nota $nota";
            if ($avance >= $MinimoAsistencia) {
                if ($nota >= $MinimoNota) {
                    $estado_descripcion = "APROBADO";
                    $estado = "1";
                } else {
                    $estado_descripcion = "REPROBADO";
                    $estado = "1";
                }
            } else {
                $estado_descripcion = "REPROBADO";
                $estado = "1";
            }

        } elseif ($MinimoAsistencia > 0 and $MinimoNota == "") {
            echo "<br> - caso 6";
            if ($avance >= $MinimoAsistencia) {
                $estado_descripcion = "APROBADO";
                $estado = "1";
            } else {
                $estado_descripcion = "REPROBADO";
                $estado = "1";
            }
        } elseif ($MinimoAsistencia == "" and $MinimoNota > 0) {
            echo "<br> - caso 7";
            if ($nota >= $MinimoNota) {
                $estado_descripcion = "APROBADO";
                $estado = "1";
            } else {
                $estado_descripcion = "REPROBADO";
                $estado = "1";
            }
        } else {
            echo "<br> - caso 8";
            $estado_descripcion = "APROBADO";
            $estado = "1";
        }
    }
    //echo "<br>--> estado_descripcion $estado_descripcion, estado $estado";
    $sql = "update tbl_inscripcion_cierre set nota='$nota', estado_descripcion='$estado_descripcion', estado='$estado', avance='$avance', porcentaje_asistencia='$avance', caso_especial='$caso_especial'
			where rut='$rut' and id_curso='$id_curso' and id_empresa='$id_empresa' and id_inscripcion='$id_inscripcion'";
    //echo "<br>".$sql;exit();


  $connexion->query($sql);
    $connexion->execute();

}
function DeleteInscripcionUsuarios_2022($id_imparticion, $rut)
{
    $connexion = new DatabasePDO();

    $sql1 = "DELETE FROM tbl_inscripcion_usuarios WHERE id_inscripcion = :id_imparticion AND rut = :rut";
    $connexion->query($sql1);
    $connexion->bind(':id_imparticion', $id_imparticion);
    $connexion->bind(':rut', $rut);
    $connexion->execute();

    $sql2 = "DELETE FROM tbl_inscripcion_cierre WHERE id_inscripcion = :id_imparticion AND rut = :rut";
    $connexion->query($sql2);
    $connexion->bind(':id_imparticion', $id_imparticion);
    $connexion->bind(':rut', $rut);
    $connexion->execute();

    $sql3 = "DELETE FROM tbl_lms_reportes WHERE id_inscripcion = :id_imparticion AND rut = :rut";
    $connexion->query($sql3);
    $connexion->bind(':id_imparticion', $id_imparticion);
    $connexion->bind(':rut', $rut);
    $connexion->execute();

    $sql4 = "DELETE FROM tbl_inscripcion_curso_sesiones_horarios_rut WHERE id_inscripcion = :id_imparticion AND rut = :rut";
    $connexion->query($sql4);
    $connexion->bind(':id_imparticion', $id_imparticion);
    $connexion->bind(':rut', $rut);
    $connexion->execute();
}
function InsertNewInscripcionUsuarios_2022($id_inscripcion, $rut, $id_curso, $id_empresa, $fecha, $id_malla)
{
    $connexion = new DatabasePDO();

    $sql = "INSERT INTO tbl_inscripcion_usuarios (id_inscripcion, rut, id_curso, id_empresa, fecha, id_malla) " .
        "VALUES (:id_inscripcion, :rut, :id_curso, :id_empresa, :fecha, :id_malla)";
    $connexion->query($sql);
    $connexion->bind(':id_inscripcion', $id_inscripcion);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':id_malla', $id_malla);
    $connexion->execute();
}

function Listado_TotalCursos1($id_empresa, $id_curso, $llamada)
{
    $connexion = new DatabasePDO();
    $filtro = "";

    if ($id_curso) {
        $sql_id_curso = " and tbl_lms_curso.id='$id_curso'";
    }

    $sql_presenciales = " and tbl_lms_curso.modalidad='2'";

    $sql = "SELECT

tbl_lms_curso.*, 
tbl_lms_foco.descripcion AS nombre_foco,
tbl_lms_programas_bbdd.nombre_programa,
tbl_otec.nombre AS nombre_otec,
(select count(*)as total from tbl_inscripcion_curso where tbl_inscripcion_curso.id_curso=tbl_lms_curso.id ) as total_inscripciones_curso,
(SELECT COUNT(DISTINCT(rel_lms_malla_curso.id_programa))as total from rel_lms_malla_curso where rel_lms_malla_curso.id_curso=tbl_lms_curso.id and rel_lms_malla_curso.id_programa is not null) as total_programas,
(select count(DISTINCT(rut)) from tbl_inscripcion_usuarios where id_curso=tbl_lms_curso.id)  as inscritos,
(SELECT    ejecutivo    FROM    tbl_inscripcion_curso    WHERE        tbl_inscripcion_curso.id_curso = tbl_lms_curso.id limit 1) AS rut_ejecutivo,
(select nombre_completo from tbl_usuario where rut=(SELECT    ejecutivo    FROM    tbl_inscripcion_curso    WHERE        tbl_inscripcion_curso.id_curso = tbl_lms_curso.id limit 1)) as ejecutivo

FROM tbl_lms_curso

LEFT JOIN tbl_otec ON tbl_otec.rut = tbl_lms_curso.rut_otec
LEFT JOIN tbl_lms_foco ON tbl_lms_foco.codigo_foco = tbl_lms_curso.id_foco
AND tbl_lms_foco.id_empresa = '$id_empresa'
LEFT JOIN tbl_lms_programas_bbdd ON tbl_lms_programas_bbdd.id_programa = tbl_lms_curso.id_empresa_programa
AND tbl_lms_programas_bbdd.id_empresa = '$id_empresa'
WHERE
tbl_lms_curso.activo = 0
and tbl_lms_curso.inactivo = 0
AND tbl_lms_curso.id_empresa = '$id_empresa' $sql_id_curso 
and tbl_lms_curso.modalidad='1'

ORDER BY
tbl_lms_curso.id_correlativo DESC";
//echo $sql;exit();
    $connexion->query($sql);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;
}
function Listado_TotalCursos1_2024($id_empresa, $id_curso, $llamada)
{
    $connexion = new DatabasePDO();
    $filtro = "";

    if ($id_curso) {
        $sql_id_curso = " and tbl_lms_curso.id='$id_curso'";
    }



    $sql_presenciales = " and tbl_lms_curso.modalidad='2'";

    /*tbl_otec.nombre AS nombre_otec,
(select count(*)as total from tbl_inscripcion_curso where tbl_inscripcion_curso.id_curso=tbl_lms_curso.id ) as total_inscripciones_curso,
(SELECT COUNT(DISTINCT(rel_lms_malla_curso.id_programa))as total from rel_lms_malla_curso where rel_lms_malla_curso.id_curso=tbl_lms_curso.id and rel_lms_malla_curso.id_programa is not null) as total_programas,
(select count(DISTINCT(rut)) from tbl_inscripcion_usuarios where id_curso=tbl_lms_curso.id)  as inscritos,
(SELECT    ejecutivo    FROM    tbl_inscripcion_curso    WHERE        tbl_inscripcion_curso.id_curso = tbl_lms_curso.id limit 1) AS rut_ejecutivo,
(select nombre_completo from tbl_usuario where rut=(SELECT    ejecutivo    FROM    tbl_inscripcion_curso    WHERE        tbl_inscripcion_curso.id_curso = tbl_lms_curso.id limit 1)) as ejecutivo*/

//LEFT JOIN tbl_otec ON tbl_otec.rut = tbl_lms_curso.rut_otec
    $sql = "SELECT

tbl_lms_curso.*, 
tbl_lms_foco.descripcion AS nombre_foco,
tbl_lms_programas_bbdd.nombre_programa,
tbl_otec.nombre AS nombre_otec

FROM tbl_lms_curso

LEFT JOIN tbl_lms_foco ON tbl_lms_foco.codigo_foco = tbl_lms_curso.id_foco
LEFT JOIN tbl_otec ON tbl_otec.rut = tbl_lms_curso.rut_otec
                          
AND tbl_lms_foco.id_empresa = '$id_empresa'
LEFT JOIN tbl_lms_programas_bbdd ON tbl_lms_programas_bbdd.id_programa = tbl_lms_curso.id_empresa_programa
AND tbl_lms_programas_bbdd.id_empresa = '$id_empresa'
WHERE
tbl_lms_curso.activo = 0
and tbl_lms_curso.inactivo = 0
AND tbl_lms_curso.id_empresa = '$id_empresa' $sql_id_curso 
and tbl_lms_curso.modalidad='1'

ORDER BY
tbl_lms_curso.id_correlativo DESC";
//echo $sql;exit();
    $connexion->query($sql);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;
}
	function updateNumDocReembolso($id, $numdoc)
	{
		$connexion = new DatabasePDO();
		$sql = "update tbl_finanza_reembolsos set reembo_numdoc='$numdoc' where reembo_id={$id}";
		$connexion->query($sql);
		$connexion->execute();
	}
?>