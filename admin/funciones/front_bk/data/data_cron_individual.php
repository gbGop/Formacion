<?php
function Cron_Data_Individual_Cursos_Busca_SinAvance($rut) {
    $connexion = new DatabasePDO();
    $sql = "
        SELECT h.rut, h.id_curso, h.avance, h.id_programa, 
        (
            SELECT avance 
            FROM tbl_inscripcion_cierre 
            WHERE rut=h.rut AND id_curso=h.id_curso AND id_empresa='78' 
            ORDER BY avance DESC LIMIT 1 
        ) AS avanceCierre
        FROM tbl_lms_reportes h 
        WHERE h.rut=:rut AND (h.avance IS NULL OR h.avance<100) AND h.id_programa<>'bch_histor'
        AND (
            SELECT avance 
            FROM tbl_inscripcion_cierre 
            WHERE rut=h.rut AND id_curso=h.id_curso AND id_empresa='78' LIMIT 1
        ) > 0
    ";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;
}
function Cron_Data_Individual_Cursos_Busca_SinResutlados($rut) {
    $connexion = new DatabasePDO();

    $sqlUpdate_1_1 = "UPDATE tbl_lms_reportes SET resultado='-' WHERE resultado IS NULL AND rut=:rut";
    $connexion->query($sqlUpdate_1_1);
    $connexion->bind(':rut', $rut);
    $connexion->execute();

    $sql = "SELECT h.id, h.rut, h.id_curso, h.avance, h.id_programa, ROUND(h.resultado, 0), h.estado,
            ROUND((SELECT nota FROM tbl_inscripcion_cierre WHERE rut=h.rut AND id_curso=h.id_curso AND id_empresa='78' 
            ORDER BY avance DESC, nota DESC LIMIT 1), 0) AS notaCierre
            FROM tbl_lms_reportes h WHERE h.rut=:rut AND (h.resultado IS NULL OR h.resultado<>(SELECT nota FROM tbl_inscripcion_cierre WHERE rut=h.rut AND id_curso=h.id_curso AND id_empresa='78' 
            ORDER BY avance DESC, nota DESC LIMIT 1)) AND h.id_programa<>'bch_histor'
            AND ROUND((SELECT nota FROM tbl_inscripcion_cierre WHERE rut=h.rut AND id_curso=h.id_curso AND id_empresa='78' 
            ORDER BY avance DESC, nota DESC LIMIT 1), 0) > ROUND(h.resultado, 0)
            AND h.avance='100'";

    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;
}

function SumaPuntosUsuarioCursoEmpresa($rut, $id_curso, $id_empresa){
    $connexion = new DatabasePDO();
    $sql = "SELECT SUM(puntos) AS puntos, SUM(medallas) AS medallas,
(SELECT SUM(puntos) FROM tbl_objeto WHERE id_curso=:id_curso LIMIT 1) AS puntosPotenciales
FROM tbl_gamificado_puntos WHERE rut=:rut AND id_empresa=:id_empresa AND id_curso=:id_curso";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;
}
function BuscaHorasCurso($id_curso){
    $connexion = new DatabasePDO();
    $sql = "SELECT numero_horas FROM tbl_lms_curso WHERE id=:id_curso AND numero_horas<>'' LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;
}

function BuscaModalidadCurso($id_curso) {
    $connexion = new DatabasePDO();
    $sql = "SELECT modalidad FROM tbl_lms_curso WHERE id=:id_curso LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod[0]->modalidad;
}
function Buscac1c2c3c4_2017($id_empresa) {

    $connexion = new DatabasePDO();

    $sql = "SELECT campo1, campo2, campo3, campo4 FROM tbl_empresa WHERE id=:id_empresa";

    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
    $cod = $connexion->resultset();

    return $cod;
}
function BuscaDatosUsuarios2017($hc1, $hc2, $hc3, $hc4, $id_empresa, $rut) {
    $connexion = new DatabasePDO($c_host, $c_user, $c_pass, $c_db);
    $sql = "select h.rut_completo, h.nombre_completo, h.cargo, h.email,
$hc1 as c1, $hc2 as c2,$hc3 as c3,$hc4 as c4
from tbl_usuario h where h.rut=:rut and h.id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;
}
function BuscaRelMallaOpcionalExternos($id_curso, $id_malla, $id_programa, $id_empresa) {
    $connexion = new DatabasePDO();
    $sql = "SELECT h.opcional, h.inactivo,
            (SELECT opcional FROM tbl_lms_programas_bbdd WHERE id_programa=h.id_programa LIMIT 1) AS programa_opcional,
            (SELECT numero_horas FROM tbl_lms_curso WHERE id=h.id_curso LIMIT 1) AS numero_horas
            FROM rel_lms_malla_curso h 
            WHERE h.id_programa=:id_programa AND h.id_curso=:id_curso AND h.id_malla=:id_malla AND h.id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_programa', $id_programa);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':id_malla', $id_malla);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;
}
function BuscaCodigoOpcional($id_curso, $id_malla, $id_programa, $id_empresa) {
    $connexion = new DatabasePDO();
    $sql = "SELECT opcional FROM rel_lms_malla_curso WHERE id_curso=:id_curso AND id_malla=:id_malla AND id_programa=:id_programa AND id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':id_malla', $id_malla);
    $connexion->bind(':id_programa', $id_programa);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod[0]->opcional;
}


function Reporte2017_data_programa($id_programa) {
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_lms_programas_bbdd WHERE id_programa=:id_programa ORDER BY id ASC";
    $connexion->query($sql);
    $connexion->bind(':id_programa', $id_programa);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;
}
function BuscaUsuariosPorPrograma($id_programa, $id_empresa, $rut) {
    $connexion = new DatabasePDO();
    $sql = "
    SELECT h.*,
    (SELECT id_programa FROM rel_lms_malla_curso WHERE id_malla=h.id_malla LIMIT 1) AS id_programa,
    (SELECT id_clasificacion FROM rel_lms_malla_curso WHERE id_malla=h.id_malla AND id_curso=h.id_curso LIMIT 1) AS id_clasificacion,
    (SELECT opcional FROM rel_lms_malla_curso WHERE id_malla=h.id_malla AND id_curso=h.id_curso LIMIT 1) AS curso_opcional,
    (SELECT opcional FROM tbl_lms_programas_bbdd WHERE id_programa=(SELECT id_programa FROM rel_lms_malla_curso WHERE id_malla=h.id_malla LIMIT 1) LIMIT 1) AS programa_opcional,
    (SELECT opcional FROM rel_lms_malla_persona WHERE id_malla=h.id_malla AND rut=h.rut LIMIT 1) AS curso_opcional_malla,
    (SELECT inactivo FROM rel_lms_malla_curso WHERE id_malla=h.id_malla AND id_curso=h.id_curso LIMIT 1) AS curso_inactivo,
    (SELECT numero_horas FROM tbl_lms_curso WHERE id=h.id_curso LIMIT 1) AS numero_horas
    FROM tbl_inscripcion_usuarios h WHERE
    (SELECT id_programa FROM rel_lms_malla_curso WHERE id_malla=h.id_malla LIMIT 1) = :id_programa
    AND (SELECT inactivo FROM rel_lms_malla_curso WHERE id_malla=h.id_malla AND id_curso=h.id_curso LIMIT 1) <> 1
    AND h.rut = :rut
    AND h.id_empresa = :id_empresa";

    if($rut == "12793621") {
        // echo "<br>BuscaUsuariosPorPrograma<br>".$sql;
    }

    $connexion->query($sql);
    $connexion->bind(':id_programa', $id_programa);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;
}
function actualizaAvancesIrregularesIdProgEmpresa($rut, $id_programa){
    $connexion = new DatabasePDO();
    $sql="SELECT
            h.*,
            (SELECT avance from tbl_inscripcion_cierre WHERE rut = h.rut AND id_curso = h.id_curso ORDER BY avance DESC LIMIT 1) avance_2021,
            (SELECT nota from tbl_inscripcion_cierre WHERE rut = h.rut AND id_curso = h.id_curso ORDER BY nota DESC LIMIT 1) nota2021
        FROM
            tbl_inscripcion_cierre h 
        WHERE
            id_curso = h.id_curso 
            AND rut = :rut 
            and (SELECT avance from tbl_inscripcion_cierre WHERE rut = h.rut AND id_curso = h.id_curso ORDER BY avance DESC LIMIT 1) > 0
            and (SELECT avance from tbl_inscripcion_cierre WHERE rut = h.rut AND id_curso = h.id_curso ORDER BY avance DESC LIMIT 1) > h.avance
        ORDER BY
            avance DESC";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->execute();
    $cod = $connexion->resultset();
    foreach ($cod as $unico){
        $sql2 = "update tbl_inscripcion_cierre set avance=:avance, nota=:nota where id_curso=:id_curso and rut=:rut";
        $connexion->query($sql2);
        $connexion->bind(':avance', $unico->avance_2021);
        $connexion->bind(':nota', $unico->nota2021);
        $connexion->bind(':id_curso', $unico->id_curso);
        $connexion->bind(':rut', $rut);
        $connexion->execute();
    }

    $sql = "update tbl_lms_reportes h set
                h.avance=(select avance from tbl_inscripcion_cierre where id_curso=h.id_curso and rut=h.rut order by avance DESC limit 1),
                h.resultado=(select nota from tbl_inscripcion_cierre where id_curso=h.id_curso and rut=h.rut order by nota DESC limit 1)
            where
                (select avance from tbl_inscripcion_cierre where id_curso=h.id_curso and rut=h.rut limit 1)>=h.avance
                and h.rut=:rut and h.id_programa=:id_programa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_programa', $id_programa);
    $connexion->execute();
}
function TieneTriviaCurso_Cron_reportes($id_curso, $id_empresa) {
    $connexion = new DatabasePDO();
    $sql = "SELECT COUNT(id) AS cuenta FROM tbl_objeto WHERE id_curso=:id_curso AND id_empresa=:id_empresa AND id_evaluacion IS NOT NULL";
    $connexion->query($sql);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod[0]->cuenta;
}
function UltimaOtaInscripcionUsuarios2022($rut, $id_curso) {
    $connexion = new DatabasePDO();

    $sql = "SELECT h.*, (SELECT fecha FROM tbl_inscripcion_usuarios WHERE rut=h.rut AND id_curso=h.id_curso ORDER BY fecha DESC LIMIT 1) AS FechaInscripcionReal
            FROM tbl_lms_reportes h
            WHERE h.rut=:rut AND h.id_curso=:id_curso
            ORDER BY (SELECT fecha FROM tbl_inscripcion_usuarios WHERE rut=h.rut AND id_curso=h.id_curso ORDER BY fecha DESC LIMIT 1)
            LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod[0]->FechaInscripcionReal;
}
function DatosInscripcionCierre_2017($rut,$id_curso, $id_empresa){
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*,
(SELECT fecha FROM tbl_inscripcion_usuarios WHERE rut=:rut AND id_curso=:id_curso ORDER BY fecha DESC LIMIT 1) AS fecha_inscripcion,
(SELECT id_malla FROM tbl_inscripcion_usuarios WHERE rut=:rut AND id_curso=:id_curso ORDER BY fecha DESC LIMIT 1) AS id_malla,
(SELECT opcional FROM rel_lms_malla_curso WHERE id_malla=(SELECT id_malla FROM tbl_inscripcion_usuarios WHERE rut=:rut AND id_curso=:id_curso ORDER BY fecha DESC LIMIT 1) AND id_curso=:id_curso LIMIT 1) AS opcional,
(SELECT COUNT(id) AS objetos FROM tbl_objeto WHERE id_curso=:id_curso AND opcional<>1) AS objetos,
(SELECT COUNT(id) AS objetos FROM tbl_objetos_finalizados WHERE id_curso=:id_curso AND rut=:rut AND (SELECT opcional FROM tbl_objeto WHERE id=id_objeto) <> 1) AS objetos_finalizados,
(ROUND(100 * (SELECT COUNT(id) AS objetos FROM tbl_objetos_finalizados WHERE id_curso=:id_curso AND rut=:rut AND (SELECT opcional FROM tbl_objeto WHERE id=id_objeto) <> 1) / (SELECT COUNT(id) AS objetos FROM tbl_objeto WHERE id_curso=:id_curso AND opcional<>1))) AS total
FROM tbl_inscripcion_cierre h
WHERE h.rut=:rut AND h.id_curso=:id_curso AND id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;
}
function DatosInscripcionCursoOpcional_2017($id_malla, $id_curso, $id_empresa){
    $connexion = new DatabasePDO();
    $sql = "SELECT opcional FROM rel_lms_malla_curso WHERE id_curso=:id_curso AND id_empresa=:id_empresa AND id_malla=:id_malla LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_malla', $id_malla);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;
}
function LMS_REPORTE_VerificaFechasObjetoFinalizadoDadoObjetoPrimeroFront($id_curso, $rut){
        $connexion = new DatabasePDO();
    $sql = "SELECT
    MIN(CASE WHEN fecha = min_fecha THEN fecha END) AS fecha_inicio,
    MAX(CASE WHEN fecha = max_fecha THEN fecha END) AS fecha_termino,
    MIN(CASE WHEN fecha = min_fecha THEN hora END) AS hora_inicio,
    MAX(CASE WHEN fecha = max_fecha THEN hora END) AS hora_termino
FROM
    tbl_objetos_finalizados,
    (SELECT MIN(fecha) AS min_fecha, MAX(fecha) AS max_fecha FROM tbl_objetos_finalizados WHERE rut=:rut AND id_curso=:id_curso) AS t
WHERE
    rut=:rut AND id_curso=:id_curso
GROUP BY
    id_curso";
        $connexion->query($sql);
        $connexion->bind(':rut', $rut);
        $connexion->bind(':id_curso', $id_curso);
        $connexion->execute();
        $cod = $connexion->resultset();
        return $cod;
    }


function ActualizaLmsReporteInscripcionCierreRUT($rut) {
    $connexion = new DatabasePDO();

    $sql = "SELECT h.id_inscripcion, h.rut, h.id_empresa, (SELECT nombre_completo FROM tbl_usuario WHERE rut=h.rut) as nombre_completo,
    h.id_curso, h.id_malla,
    (SELECT id_programa FROM rel_lms_malla_curso WHERE id_malla=h.id_malla AND id_curso=h.id_curso AND id_empresa=h.id_empresa LIMIT 1) as id_programa,
    (SELECT id_clasificacion FROM rel_lms_malla_curso WHERE id_malla=h.id_malla AND id_curso=h.id_curso AND id_empresa=h.id_empresa LIMIT 1) as id_clasificacion,
    (SELECT count(id) FROM tbl_lms_reportes WHERE id_curso=h.id_curso AND rut=h.rut) as LmsReportes,
    (SELECT count(id) FROM tbl_inscripcion_cierre WHERE id_curso=h.id_curso AND rut=h.rut) as Cierre,
    (SELECT nota FROM tbl_inscripcion_cierre WHERE id_curso=h.id_curso AND rut=h.rut) as Nota,
    (SELECT avance FROM tbl_inscripcion_cierre WHERE id_curso=h.id_curso AND rut=h.rut) as Avance,
    (SELECT estado_descripcion FROM tbl_inscripcion_cierre WHERE id_curso=h.id_curso AND rut=h.rut) as EstadoDescripcion
    FROM tbl_inscripcion_usuarios h
    WHERE (SELECT count(id) FROM tbl_lms_reportes WHERE id_curso=h.id_curso AND rut=h.rut) ='0'
    AND (SELECT modalidad FROM tbl_lms_curso WHERE id=h.id_curso)=1
    AND (SELECT nombre_completo FROM tbl_usuario WHERE rut=h.rut)<>'USUARIO_INACTIVO'
    AND h.rut=:rut";

    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->execute();
    $cod = $connexion->resultset();

    foreach ($cod as $unico) {
        $estado_curso_opcional = BuscaCodigoOpcional($unico->id_curso,  $unico->id_malla, $unico->id_programa, $unico->id_empresa);

        $sql = "INSERT INTO tbl_lms_reportes (rut, id_curso, id_malla, id_programa, id_clasificacion, avance, resultado, estado, id_empresa, curso_opcional)
        VALUES (:rut, :id_curso, :id_malla, :id_programa, :id_clasificacion, :avance, :resultado, :estado, :id_empresa, :curso_opcional)";

        $connexion->query($sql);
        $connexion->bind(':rut', $unico->rut);
        $connexion->bind(':id_curso', $unico->id_curso);
        $connexion->bind(':id_malla', $unico->id_malla);
        $connexion->bind(':id_programa', $unico->id_programa);
        $connexion->bind(':id_clasificacion', $unico->id_clasificacion);
        $connexion->bind(':avance', $unico->Avance);
        $connexion->bind(':resultado', $unico->Nota);
        $connexion->bind(':estado', $unico->EstadoDescripcion);
        $connexion->bind(':id_empresa', $unico->id_empresa);
        $connexion->bind(':curso_opcional', $estado_curso_opcional);
        $connexion->execute();
    }
}
function Verifica_Inserta_tbl_lms_reportes_2017($rut, $nombre, $cargo, $email, $c1, $c2, $c3, $id_malla,
$id_clasificacion, $id_curso, $avance, $puntos, $resultados, $medallas, $id_empresa,
$rut_completo, $id_foco, $id_programa, $fecha_inicio, $fecha_final, $c4, $curso_opcional,
$numero_horas,
$hora_inicio_curso, $hora_termino_curso,
$estado, $fecha_inscripcion, $intentos) {

    $connexion = new DatabasePDO();
    if ($numero_horas == "") {$numero_horas = 0;	}
    if($numero_horas==0){
        $array_numero_horas=BuscaHorasCurso($id_curso);
        $numero_horas=$array_numero_horas[0]->numero_horas;
    }

    $modalidad=BuscaModalidadCurso($id_curso);

    if ($avance > 100 and $avance <> '') {$avance = 100;}
    if($avance == 100 and $fecha_final=='0000-00-00' and $fecha_inicio<>'0000-00-00'){$fecha_final=$fecha_inicio;}
    if($avance == 100 and $fecha_inicio=='0000-00-00' and $fecha_final<>'0000-00-00'){    $fecha_inicio=$fecha_final;    }
    if ($id_foco == '') {$resultado_foco = buscaFocodesdeProgramaData($id_programa, $id_empresa);$id_foco = $resultado_foco[0] -> codigo_foco;}
    if ($curso_opcional == "") {$curso_opcional = 0;}
    if ($puntos == "") {$puntos = 0;}
    if ($medallas == "") {$medallas = 0;}
    if($rut_completo==''){$rut_completo="USUARIO INACTIVO";}
    if($nombre==''){$nombre="USUARIO INACTIVO";}

$SQL_VERIFICA = "select id

from tbl_lms_reportes

where rut='$rut' and c1='$c1'and c2='$c2' and c3='$c3' and
c4='$c4' and id_foco='$id_foco' and id_clasificacion='$id_clasificacion' and
id_curso='$id_curso' and id_programa='$id_programa' and id_malla='$id_malla' and
fecha_inicio='$fecha_inicio' and fecha_termino='$fecha_final' and curso_opcional='$curso_opcional' and numero_horas='$numero_horas' and avance='$avance' and
puntos='$puntos' and resultado='$resultados' and medallas='$medallas' and hora_inicio='$hora_inicio_curso' and hora_termino='$hora_termino_curso' and 
estado='$estado' and fecha_inscripcion='$fecha_inscripcion' and intentos='$intentos'


";
//echo $SQL_VERIFICA;
if ($avance>100) {$avance = 100;}

    $connexion->query($SQL_VERIFICA);
    $connexion->execute();
    $cod = $connexion->resultset();

        $escribe_lms_reportes = count($cod);

        //echo "<br>Verifica<br>".$SQL_VERIFICA;

                if ($escribe_lms_reportes == 0) {
                    $sql_borra = " delete from tbl_lms_reportes where rut='$rut' and id_curso='$id_curso' and id_empresa='$id_empresa' ";
                    $connexion->query($sql_borra);
                    $connexion->execute();

                                //echo "<br>... borra<br>";
                                //echo $sql_borra;

                                $sql = "
                                
                                INSERT INTO
                                
                                tbl_lms_reportes
                                
                                (rut, nombre, cargo, email, c1, c2, c3, c4, id_malla, id_clasificacion,
                                id_curso, avance, puntos, resultado, medallas, fecha, hora,
                                id_empresa, rut_completo, id_foco, id_programa, fecha_inicio,
                                fecha_termino, curso_opcional, numero_horas, hora_inicio, hora_termino,
                                estado,  fecha_inscripcion, intentos,modalidadcurso)
                                VALUES
                                ('$rut', '$nombre', '$cargo', '$email', '$c1', '$c2', '$c3', '$c4', '$id_malla', '$id_clasificacion',
                                '$id_curso', '$avance', '$puntos', '$resultados', '$medallas',
                                '" . date("Y-m-d") . "', '" . date("H:i:s") . "',
                                '$id_empresa', '$rut_completo',		'$id_foco', '$id_programa', '$fecha_inicio',
                                '$fecha_final','$curso_opcional','$numero_horas',
                                '$hora_inicio_curso','$hora_termino_curso','$estado','$fecha_inscripcion','$intentos','$modalidad');";

                                    $connexion->query($sql);
                                    $connexion->execute();



                }
return $cod;

}
function GuardaDatosTablaReporte2017RutPrograma($rut, $id_programa) {

    $connexion = new DatabasePDO();

    actualizaAvancesIrregularesIdProgEmpresa($rut, $id_programa);

    $Programas = Reporte2017_data_programa($id_programa);
    foreach ($Programas as $Programa) {
        $arrayC = Buscac1c2c3c4_2017($Programa -> id_empresa);
        $hc1 = " h." . $arrayC[0] -> campo1;
        $hc2 = " h." . $arrayC[0] -> campo2;
        $hc3 = " h." . $arrayC[0] -> campo3;
        $hc4 = " h." . $arrayC[0] -> campo4;

        $cuenta_usuarios_Internos = 0;
        $Arreglo_Ruts_Programa_interno = BuscaUsuariosPorPrograma($Programa -> id_programa, $Programa -> id_empresa, $rut);

        foreach ($Arreglo_Ruts_Programa_interno as $A_Rut_Pr_int) {
            $cuenta_usuarios_Internos++;
            $datos_usuario_clave_2017 = BuscaDatosUsuarios2017($hc1, $hc2, $hc3, $hc4, $Programa -> id_empresa, $A_Rut_Pr_int -> rut);
            //$arreglo_post[curso] = $A_Rut_Pr_int -> id_curso;
            $datos_inscripcion_cierre=DatosInscripcionCierre_2017($A_Rut_Pr_int->rut,$A_Rut_Pr_int ->id_curso,$Programa -> id_empresa);
            $estado_curso=$datos_inscripcion_cierre[0]->estado_descripcion;
            if($avance==''){$avance==0;}
            $opcional=0;
            $curso_opcional=0;

            if($A_Rut_Pr_int->curso_opcional=="1" or $A_Rut_Pr_int->programa_opcional<>'' or $A_Rut_Pr_int->curso_opcional_malla=="1" )
            {
                $opcional=1;
                $curso_opcional=1;
            }

            if($datos_inscripcion_cierre[0]->avance>0){
                $avance=$datos_inscripcion_cierre[0]->avance;	} else {
                $avance=0;
            }

            if($avance==''){$avance==0;$estado_curso="NO_INICIADO";}

            $Arraypuntos=SumaPuntosUsuarioCursoEmpresa($A_Rut_Pr_int -> rut, $A_Rut_Pr_int ->id_curso, $Programa -> id_empresa);
            $puntos=$Arraypuntos[0]->puntos;
            $puntosPotenciales=$Arraypuntos[0]->puntosPotenciales;
            $medallas=$Arraypuntos[0]->medallas;

            if($A_Rut_Pr_int->programa_opcional<>'' or $A_Rut_Pr_int->curso_opcional=="1" or $A_Rut_Pr_int->curso_opcional_malla=="1"){
                $puntos=$puntos*2;
            }

            $tienetrivia=0;
            $tienetrivia=TieneTriviaCurso_Cron_reportes($A_Rut_Pr_int ->id_curso,$Programa -> id_empresa);
            $resultado="-";
            if($tienetrivia>0){
                $resultado=$datos_inscripcion_cierre[0]->nota;

                if($resultado==''){$resultado="-";}

            } else {
                $resultado="-";
            }

            $ReproBadoInasistencia=EstadoReprobadoPorInasistencia($A_Rut_Pr_int -> rut, $A_Rut_Pr_int->id_curso);
            //echo "<br>ReproBadoInasistencia $ReproBadoInasistencia";
            if($ReproBadoInasistencia=="REPROBADO_POR_INASISTENCIA"){
                continue;
            }

            $fecha_curso = LMS_REPORTE_VerificaFechasObjetoFinalizadoDadoObjetoPrimeroFront($A_Rut_Pr_int ->id_curso, $A_Rut_Pr_int -> rut);
            $fec_inicio_curso = $fecha_curso[0] -> fecha_inicio;
            $fec_termino_curso = $fecha_curso[0] -> fecha_termino;
            $hora_inicio_curso = $fecha_curso[0] -> hora_inicio;
            $hora_termino_curso = $fecha_curso[0] -> hora_termino;

            if($avance==0){
                $fec_inicio_curso = "0000-00-00";
                $fec_termino_curso = "0000-00-00";
                $puntos=0;
            }
            if($fec_termino_curso=="0000-00-00"){
                $hora_termino_curso="00:00:00";
            }
            if($avance==100){
                $puntos=$puntosPotenciales;

                if($tienetrivia>0){
                }
                else {
                    $estado_curso="APROBADO";
                }
            }

            if($avance==100 and $estado_curso==""){
                $estado_curso="APROBADO";
            }
            if($avance>0 and $avance<100){
                $fec_termino_curso = "0000-00-00";
                $estado_curso="EN_PROCESO";
                $resultado="-";
            }
            if($avance==0 or $avance==""){
                $fec_termino_curso = "0000-00-00";
                $estado_curso="NO_INICIADO";
                $resultado="-";
            }
            $Fecha_inscripcion_ultima_ota=UltimaOtaInscripcionUsuarios2022($A_Rut_Pr_int -> rut, $A_Rut_Pr_int->id_curso);

            Verifica_Inserta_tbl_lms_reportes_2017($A_Rut_Pr_int -> rut, $datos_usuario_clave_2017[0] -> nombre_completo,
                $datos_usuario_clave_2017[0] -> cargo, $datos_usuario_clave_2017[0] -> email,
                $datos_usuario_clave_2017[0] -> c1, $datos_usuario_clave_2017[0] -> c2, $datos_usuario_clave_2017[0] -> c3,
                $A_Rut_Pr_int->id_malla, $A_Rut_Pr_int->id_clasificacion, $A_Rut_Pr_int->id_curso,
                $avance,
                $puntos,
                $resultado,
                $medallas,
                $Programa -> id_empresa, $datos_usuario_clave_2017[0] -> rut_completo, $Programa -> codigo_foco,
                $id_programa,
                $fec_inicio_curso, $fec_termino_curso, $datos_usuario_clave_2017[0] -> c4,
                $curso_opcional, $A_Rut_Pr_int ->numero_horas,
                $hora_inicio_curso, $hora_termino_curso,
                $estado_curso,
                $Fecha_inscripcion_ultima_ota,
                $intentos
            );
        }
    }

    return ($PRINCIPAL);
}
function EstadoReprobadoPorInasistencia($rut, $id_curso){

    $connexion = new DatabasePDO();
    $sql = "select estado  from tbl_lms_reportes where id_curso=:id_curso and rut=:rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_curso', $id_curso);
    //$connexion->execute();
    $cod = $connexion->resultset();

    return $cod[0]->estado;


}
?>