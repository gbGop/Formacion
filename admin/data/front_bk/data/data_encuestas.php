<?php
function dashboard_data_cluster($id_cluster)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT nombre FROM tbl_encuestas_cluster WHERE id=:id_cluster";
    $connexion->query($sql);
    $connexion->bind(':id_cluster', $id_cluster);
    $cod = $connexion->resultset();
    return ($cod[0]->nombre);
}

function Dashboard_Encuestas_Mis_Cluster($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*,
    (SELECT nombre FROM tbl_encuestas_cluster WHERE id=h.id_cluster) as nombre,
    (SELECT COUNT(id) FROM rel_encuesta_cluster WHERE id_encuesta_cluster=h.id_cluster) as num_encuestas
    FROM rel_encuestas_cluster_usuarios h
    WHERE h.rut=:rut AND h.id_empresa=:id_empresa
    GROUP BY h.id_cluster";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return ($cod);
}

function Dashboard_Encuestas_Mis_Encuestas_Cluster($rut, $id_cluster, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*,
    (SELECT nombre FROM tbl_enc_elearning WHERE id=h.id_medicion) as nombre,
    (SELECT bajada_texto FROM tbl_enc_elearning_medicion WHERE id=h.id_medicion) as escala,
    (SELECT COUNT(DISTINCT rut) FROM tbl_enc_elearning_respuestas WHERE id_encuesta=h.id_medicion AND respuesta<>'') AS num_respuestas,
    (SELECT AVG(respuesta) FROM tbl_enc_elearning_respuestas WHERE id_encuesta=h.id_medicion AND respuesta<>'') AS prom_respuestas
    FROM rel_encuesta_cluster h
    WHERE h.id_encuesta_cluster=:id_cluster
    GROUP BY h.id_medicion";
    $connexion->query($sql);
    $connexion->bind(':id_cluster', $id_cluster);
    $cod = $connexion->resultset();
    return ($cod);
}

function Dashboard_Encuestas_Mis_Encuestas_data_detalle_resumen($rut, $id_encuesta, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*,
    (SELECT COUNT(DISTINCT rut) FROM tbl_enc_elearning_respuestas WHERE id_encuesta=h.id AND respuesta<>'') AS num_respuestas,
    (SELECT AVG(respuesta) FROM tbl_enc_elearning_respuestas WHERE id_encuesta=h.id AND respuesta<>'') AS prom_respuestas
    FROM tbl_enc_elearning_medicion h
    WHERE h.id=:id_encuesta
    GROUP BY h.id";
    $connexion->query($sql);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $cod = $connexion->resultset();
    return ($cod);
}

function Dashboard_Encuestas_Cluseter_perfil($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "select count(id) as cuenta from rel_encuestas_cluster_usuarios where rut=:rut and id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return ($cod[0]->cuenta);
}

function Dashboard_Encuestas_perfil($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "select count(id) as cuenta from tbl_nomina_encuesta_editor where rut=:rut and id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return ($cod[0]->cuenta);
}

function Dashboard_Trae_Preguntas_Grafico($id_encuesta, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "select h.* from tbl_enc_elearning_preg h where h.id_encuesta=:id_encuesta and id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function Dashboard_TraeRespuestas_Preguntas_Grafico($campo_a_buscar, $campo_filtrado1, $valor1, $campo_filtrado2, $valor2)
{
    $connexion = new DatabasePDO();
    $sql = "select distinct($campo_a_buscar) as valor from tbl_usuario where $campo_filtrado1=:valor1 and $campo_filtrado2=:valor2 and $campo_a_buscar<>''";
    $connexion->query($sql);
    $connexion->bind(':valor1', $valor1);
    $connexion->bind(':valor2', $valor2);
    $cod = $connexion->resultset();
    return $cod;
}

function Dashboard_Encuestas_BuscaRespuestasPorCadaPregunta_NoVacias($id_pregunta, $id_encuesta, $id_empresa, $filtro_division, $filtro_region, $filtro_jefatura)
{
    $connexion = new DatabasePDO();
    if ($filtro_division <> "") {
        $jq_div = " and h.division='" . $filtro_division . "' ";
    }
    if ($filtro_region <> "") {
        $jq_reg = " and h.region='" . $filtro_region . "' ";
    }
    if ($filtro_jefatura <> "") {
        $jq_jef = " and h.esjefe='" . $filtro_jefatura . "' ";
    }
    $sql = "select count(h.id) as cuenta from tbl_enc_elearning_respuestas h where h.id_encuesta=:id_encuesta $jq_div $jq_reg $jq_jef and h.id_pregunta=:id_pregunta and h.id_empresa=:id_empresa and h.respuesta<>''";
    $connexion->query($sql);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_pregunta', $id_pregunta);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod[0]->cuenta;
}

function Dashboard_Encuestas_BuscaRespuestasPorCadaPregunta($id_pregunta, $id_encuesta, $id_empresa, $filtro_division, $filtro_region, $filtro_jefatur)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.respuesta, COUNT(h.respuesta) as cuenta_respuesta, (SELECT tipo FROM tbl_enc_elearning_preg WHERE id_encuesta=h.id_encuesta AND id_pregunta=h.id_pregunta) AS tipo
FROM tbl_enc_elearning_respuestas h
WHERE h.id_encuesta=:id_encuesta";
    $bindings = array(':id_encuesta' => $id_encuesta);
    if (!empty($filtro_division)) {
        $sql .= " AND h.division=:division ";
        $bindings[':division'] = $filtro_division;
    }
    if (!empty($filtro_region)) {
        $sql .= " AND h.region=:region ";
        $bindings[':region'] = $filtro_region;
    }
    if (!empty($filtro_jefatur)) {
        $sql .= " AND h.esjefe=:esjefe ";
        $bindings[':esjefe'] = $filtro_jefatur;
    }
    $sql .= " AND h.id_pregunta=:id_pregunta AND h.id_empresa=:id_empresa GROUP BY h.respuesta ";
    $bindings[':id_pregunta'] = $id_pregunta;
    $bindings[':id_empresa'] = $id_empresa;
    $connexion->query($sql);
    foreach ($bindings as $key => $value) {
        $connexion->bind($key, $value);
    }
    $cod = $connexion->resultset();
    return $cod;
}

function dashboard_DatosEncuesta_Resultados($id_encuesta, $filtro_division, $filtro_region, $filtro_jefatura)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*, (SELECT COUNT(id) FROM tbl_nomina_encuesta WHERE id_encuesta=h.id) as usuarios_inscritos,
(SELECT COUNT(DISTINCT rut) FROM tbl_enc_elearning_respuestas WHERE id_encuesta=h.id";
    $bindings = array();
    if (!empty($filtro_division)) {
        $sql .= " AND division=:division ";
        $bindings[':division'] = $filtro_division;
    }
    if (!empty($filtro_region)) {
        $sql .= " AND region=:region ";
        $bindings[':region'] = $filtro_region;
    }
    if (!empty($filtro_jefatura)) {
        $sql .= " AND esjefe=:esjefe ";
        $bindings[':esjefe'] = $filtro_jefatura;
    }
    $sql .= ") as usuarios_respuestas FROM tbl_enc_elearning h WHERE h.id=:id_encuesta";
    $bindings[':id_encuesta'] = $id_encuesta;
    $connexion->query($sql);
    foreach ($bindings as $key => $value) {
        $connexion->bind($key, $value);
    }
    $cod = $connexion->resultset();
    return $cod;
}

function dashboard_filtros_Encuestas($id_encuesta, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql_1 = "select distinct(h.division) as division from tbl_enc_elearning_respuestas h where h.id_encuesta=:id_encuesta and h.id_empresa=:id_empresa and h.division is not null and h.division<>'' order by h.division;";
    $connexion->query($sql_1);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod_1 = $connexion->resultset();

    $sql_2 = "select distinct(h.region) as region from tbl_enc_elearning_respuestas h where h.id_encuesta=:id_encuesta and h.id_empresa=:id_empresa and h.region is not null and h.region<>'' order by h.region;
    ";
    $connexion->query($sql_2);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod_2 = $connexion->resultset();

    $arreglo[0] = $cod_1;
    $arreglo[1] = $cod_2;

    return $arreglo;
}

function CuentaRespuestas_Encuesta_12($rut, $id_encuesta, $id_empresa)
{
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");

    $sql = " select count(id) as cuenta from tbl_enc_elearning_respuestas where rut=:rut and id_medicion=:id_encuesta and id_empresa=:id_empresa ";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return ($cod[0]->cuenta);
}

function Lista_mediciones_jefe_data($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "
    select h.*, (select nombre from tbl_enc_elearning_medicion where id=h.id_medicion) as medicion, (select fecha1 from tbl_enc_elearning_medicion where id=h.id_medicion) as fecha1, (select fecha2 from tbl_enc_elearning_medicion where id=h.id_medicion) as fecha2, (select fecha3 from tbl_enc_elearning_medicion where id=h.id_medicion) as fecha3,
    (select id_encuesta from tbl_enc_elearning_medicion where id=h.id_medicion) as id_enc,
    (select nombre_completo from tbl_usuario where rut=h.rut) as nombre, (select cargo from tbl_usuario where rut=h.rut) as cargo,
    (select gerencia from tbl_usuario where rut=h.rut) as gerencia,
    (select count(id) from tbl_enc_elearning_preg where id_encuesta=(select id_encuesta from tbl_enc_elearning_medicion where id=h.id_medicion)) as NumPreg,
    (select count(id) from tbl_enc_elearning_respuestas where id_medicion=h.id_medicion and rut_colaborador=h.rut) as NumResp,
    round((select count(id) from tbl_enc_elearning_respuestas where id_medicion=h.id_medicion and rut_colaborador=h.rut)/(select count(id) from tbl_enc_elearning_preg where id_encuesta=(select id_encuesta from tbl_enc_elearning_medicion where id=h.id_medicion))) as Porcentaje
    from tbl_enc_elearning_usuarios h
    where h.jefe=:rut and h.id_empresa=:id_empresa
    order by ((select count(id) from tbl_enc_elearning_respuestas where id_medicion=h.id_medicion and rut_colaborador=h.rut)/(select count(id)
    from tbl_enc_elearning_preg where id_encuesta=(select id_encuesta from tbl_enc_elearning_medicion where id=h.id_medicion))) ASC,
    (select fecha1 from tbl_enc_elearning_medicion where id=h.id_medicion) ASC
    ";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}


function Lista_mediciones_encuestas_data($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "
            SELECT h.*, 
            (SELECT nombre FROM tbl_enc_elearning_medicion WHERE id=h.id_medicion) as medicion, 
            (SELECT fecha1 FROM tbl_enc_elearning_medicion WHERE id=h.id_medicion) as fecha1, 
            (SELECT fecha2 FROM tbl_enc_elearning_medicion WHERE id=h.id_medicion) as fecha2, 
            (SELECT fecha3 FROM tbl_enc_elearning_medicion WHERE id=h.id_medicion) as fecha3,
            (SELECT id_encuesta FROM tbl_enc_elearning_medicion WHERE id=h.id_medicion) as id_enc,
            (SELECT nombre_completo FROM tbl_usuario WHERE rut=h.rut) as nombre, 
            (SELECT cargo FROM tbl_usuario WHERE rut=h.rut) as cargo,
            (SELECT gerencia FROM tbl_usuario WHERE rut=h.rut) as gerencia,
            (SELECT COUNT(id) FROM tbl_enc_elearning_preg WHERE id_encuesta=(SELECT id_encuesta FROM tbl_enc_elearning_medicion WHERE id=h.id_medicion)) as NumPreg,
            (SELECT COUNT(id) FROM tbl_enc_elearning_respuestas WHERE id_medicion=h.id_medicion AND rut_colaborador=h.rut) as NumResp,
            ROUND((SELECT COUNT(id) FROM tbl_enc_elearning_respuestas WHERE id_medicion=h.id_medicion AND rut_colaborador=h.rut)/(SELECT COUNT(id) FROM tbl_enc_elearning_preg WHERE id_encuesta=(SELECT id_encuesta FROM tbl_enc_elearning_medicion WHERE id=h.id_medicion))) as Porcentaje
            FROM tbl_enc_elearning_usuarios h
            WHERE (h.jefe='' OR h.jefe IS NULL) AND rut=:rut AND h.id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function Lista_mediciones_encuestas_lb_data($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "
    SELECT h.*, 
        (SELECT nombre FROM tbl_enc_elearning_medicion WHERE id=h.id) AS medicion,
        (SELECT fecha1 FROM tbl_enc_elearning_medicion WHERE id=h.id) AS fecha1,
        (SELECT fecha2 FROM tbl_enc_elearning_medicion WHERE id=h.id) AS fecha2,
        (SELECT fecha3 FROM tbl_enc_elearning_medicion WHERE id=h.id) AS fecha3,
        (SELECT id_encuesta FROM tbl_enc_elearning_medicion WHERE id=h.id) AS id_enc,
        (SELECT nombre_completo FROM tbl_usuario WHERE rut=:rut) AS nombre,
        (SELECT cargo FROM tbl_usuario WHERE rut=:rut) AS cargo,
        (SELECT gerencia FROM tbl_usuario WHERE rut=:rut) AS gerencia,
        (SELECT COUNT(id) FROM tbl_enc_elearning_preg WHERE id_encuesta=(SELECT id_encuesta FROM tbl_enc_elearning_medicion WHERE id=h.id)) AS NumPreg,
        (SELECT COUNT(id) FROM tbl_enc_elearning_respuestas WHERE id_medicion=h.id AND rut_colaborador=:rut) AS NumResp,
        ROUND((SELECT COUNT(id) FROM tbl_enc_elearning_respuestas WHERE id_medicion=h.id AND rut_colaborador=:rut)/(SELECT COUNT(id) FROM tbl_enc_elearning_preg WHERE id_encuesta=(SELECT id_encuesta FROM tbl_enc_elearning_medicion WHERE id=h.id))) AS Porcentaje
    FROM tbl_enc_elearning_medicion h
    WHERE h.tipo_medicion='DIRIGIDA' AND h.id_empresa=:id_empresa";

    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function SaveMedicionData($id_empresa, $id_medicion, $id_encuesta, $id_pregunta, $respuesta, $rut, $rut_colaborador, $comentario)
{
    $comentario = utf8_decode($comentario);

    $connexion = new DatabasePDO();

    // verifica si esta en base
    $sql = "SELECT id, respuesta FROM tbl_enc_elearning_respuestas
            WHERE id_empresa=:id_empresa AND id_medicion=:id_medicion AND id_encuesta=:id_encuesta
            AND rut=:rut AND rut_colaborador=:rut_colaborador AND id_pregunta=:id_pregunta";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_medicion', $id_medicion);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':rut_colaborador', $rut_colaborador);
    $connexion->bind(':id_pregunta', $id_pregunta);
    $cod = $connexion->resultset();

    $hoy = date('Y-m-d');
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");

    if (count($cod) == 0) {
        $sql = "INSERT INTO tbl_enc_elearning_respuestas (rut, rut_colaborador, id_empresa, fecha, hora, respuesta, id_pregunta, id_medicion, id_encuesta, comentario )
                    VALUES ( :rut, :rut_colaborador, :id_empresa, :fecha, :hora, :respuesta, :id_pregunta, :id_medicion, :id_encuesta, :comentario )";
        $connexion->query($sql);
        $connexion->bind(':rut', $rut);
        $connexion->bind(':rut_colaborador', $rut_colaborador);
        $connexion->bind(':id_empresa', $id_empresa);
        $connexion->bind(':fecha', $fecha);
        $connexion->bind(':hora', $hora);
        $connexion->bind(':respuesta', $respuesta);
        $connexion->bind(':id_pregunta', $id_pregunta);
        $connexion->bind(':id_medicion', $id_medicion);
        $connexion->bind(':id_encuesta', $id_encuesta);
        $connexion->bind(':comentario', $comentario);
        $connexion->execute();
    } else {
        $sql = "UPDATE tbl_enc_elearning_respuestas SET fecha=:fecha, hora=:hora, respuesta=:respuesta, comentario=:comentario WHERE id=:id";
        $connexion->query($sql);
        $connexion->bind(':fecha', $fecha);
        $connexion->bind(':hora', $hora);
        $connexion->bind(':respuesta', $respuesta);
        $connexion->bind(':comentario', $comentario);
        $connexion->bind(':id', $cod[0]->id);
        $connexion->execute();
    }
}

function SaveMedicionDataFechaClima($id_empresa, $id_medicion, $id_encuesta, $id_pregunta, $respuesta, $rut, $rut_colaborador, $fecha, $comentario, $edad, $genero)
{
    $connexion = new DatabasePDO();

    //verifica si esta en base
    $sql = "select id,respuesta from tbl_enc_elearning_respuestas
        where id_empresa=:id_empresa and id_medicion=:id_medicion and id_tipo=:fecha and id_encuesta=:id_encuesta
        and rut=:rut and rut_colaborador=:rut_colaborador and id_pregunta=:id_pregunta";
    $comentario = utf8_decode($comentario);

    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_medicion', $id_medicion);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':rut_colaborador', $rut_colaborador);
    $connexion->bind(':id_pregunta', $id_pregunta);
    $cod = $connexion->resultset();

    if (count($cod) == 0) {
        $hoy = date('Y-m-d');
        $hora = date("H:i:s");
        $sql = "insert into tbl_enc_elearning_respuestas (rut, rut_colaborador, id_empresa, fecha, hora, respuesta, id_pregunta, id_medicion, id_encuesta, id_tipo, comentario, id_curso, id_clasificacion )
            VALUES
            ( :rut, :rut_colaborador, :id_empresa, :fecha, :hora, :respuesta, :id_pregunta, :id_medicion,
            :id_encuesta, :fecha, :comentario, :edad, :genero);";
        $connexion->query($sql);
        $connexion->bind(':rut', $rut);
        $connexion->bind(':rut_colaborador', $rut_colaborador);
        $connexion->bind(':id_empresa', $id_empresa);
        $connexion->bind(':fecha', $hoy);
        $connexion->bind(':hora', $hora);
        $connexion->bind(':respuesta', $respuesta);
        $connexion->bind(':id_pregunta', $id_pregunta);
        $connexion->bind(':id_medicion', $id_medicion);
        $connexion->bind(':id_encuesta', $id_encuesta);
        $connexion->bind(':comentario', $comentario);
        $connexion->bind(':edad', $edad);
        $connexion->bind(':genero', $genero);
        $connexion->execute();
    } else {
        $hoy = date('Y-m-d');
        $hora = date("H:i:s");
        $sql = "update tbl_enc_elearning_respuestas set fecha=:fecha, hora=:hora, respuesta=:respuesta where id=:id";
        $connexion->query($sql);
        $connexion->bind(':fecha', $hoy);
        $connexion->bind(':hora', $hora);
        $connexion->bind(':respuesta', $respuesta);
        $connexion->bind(':id', $cod[0]->id);
        $connexion->execute();
    }
}

function SaveMedicionDataFecha($id_empresa, $id_medicion, $id_encuesta, $id_pregunta, $respuesta, $rut, $rut_colaborador, $fecha)
{
    $connexion = new DatabasePDO();

    $sql = "SELECT id, respuesta FROM tbl_enc_elearning_respuestas WHERE id_empresa = :id_empresa AND id_medicion = :id_medicion AND id_tipo = :fecha AND id_encuesta = :id_encuesta AND rut = :rut AND rut_colaborador = :rut_colaborador AND id_pregunta = :id_pregunta";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_medicion', $id_medicion);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':rut_colaborador', $rut_colaborador);
    $connexion->bind(':id_pregunta', $id_pregunta);
    $cod = $connexion->resultset();

    if (count($cod) == 0) {
        $hoy = date('Y-m-d');
        $hora = date("H:i:s");

        $sql = "INSERT INTO tbl_enc_elearning_respuestas (rut, rut_colaborador, id_empresa, fecha, hora, respuesta, id_pregunta, id_medicion, id_encuesta, id_tipo) VALUES (:rut, :rut_colaborador, :id_empresa, :fecha, :hora, :respuesta, :id_pregunta, :id_medicion, :id_encuesta, :fecha)";
        $connexion->query($sql);
        $connexion->bind(':rut', $rut);
        $connexion->bind(':rut_colaborador', $rut_colaborador);
        $connexion->bind(':id_empresa', $id_empresa);
        $connexion->bind(':fecha', $hoy);
        $connexion->bind(':hora', $hora);
        $connexion->bind(':respuesta', $respuesta);
        $connexion->bind(':id_pregunta', $id_pregunta);
        $connexion->bind(':id_medicion', $id_medicion);
        $connexion->bind(':id_encuesta', $id_encuesta);
        $connexion->execute();
    } else {
        $hora = date("H:i:s");
        $sql = "UPDATE tbl_enc_elearning_respuestas SET fecha = :fecha, hora = :hora, respuesta = :respuesta WHERE id = :id";
        $connexion->query($sql);
        $connexion->bind(':fecha', $fecha);
        $connexion->bind(':hora', $hora);
        $connexion->bind(':respuesta', $respuesta);
        $connexion->bind(':id', $cod[0]->id);
        $connexion->execute();
    }
}


function BuscaComentarioMedicionLB($id_empresa, $rut, $idm)
{

    $connexion = new DatabasePDO();

    $sql = "SELECT comentario FROM tbl_enc_elearning_respuestas 
        WHERE id_medicion=:idm AND id_empresa=:id_empresa AND rut=:rut AND comentario<>'' GROUP BY comentario";

    $connexion->query($sql);

    $connexion->bind(':idm', $idm);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);

    $cod = $connexion->resultset();

    return $cod[0]->comentario;
}


function SaveMedicionDataLB($id_empresa, $id_medicion, $id_encuesta, $id_pregunta, $rut, $respuesta, $comentario, $id_proyecto, $id_tipo)
{


    $connexion = new DatabasePDO();

    if ($id_proyecto <> "") {
        $JqueryProytecto = " and id_foco=:id_foco ";
    } else {
        $JqueryProytecto = "";
    }
    if ($id_tipo <> "") {
        $JqueryTipo = " and id_tipo=:id_tipo ";
    } else {
        $JqueryTipo = "";
    }

    $sql = "select id,respuesta from tbl_enc_elearning_respuestas
    where id_empresa=:id_empresa and id_medicion=:id_medicion and  id_encuesta=:id_encuesta
    and rut=:rut  and id_pregunta=:id_pregunta $JqueryProytecto  $JqueryTipo";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_medicion', $id_medicion);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_pregunta', $id_pregunta);
    $connexion->bind(':id_foco', $id_proyecto);
    $connexion->bind(':id_tipo', $id_tipo);
    $cod = $connexion->resultset();

    $hoy = date('Y-m-d');
    $hora = date("H:i:s");
    $comentario = utf8_decode($comentario);

    if (count($cod) == 0) {
        $sql = "insert into tbl_enc_elearning_respuestas (rut, id_empresa, fecha, hora, respuesta, id_pregunta, id_medicion, id_encuesta, comentario, id_foco, id_tipo )
        VALUES
        ( :rut, :id_empresa, :fecha, :hora, :respuesta, :id_pregunta, :id_medicion, :id_encuesta, :comentario, :id_foco, :id_tipo)";
        $connexion->query($sql);
        $connexion->bind(':rut', $rut);
        $connexion->bind(':id_empresa', $id_empresa);
        $connexion->bind(':fecha', $hoy);
        $connexion->bind(':hora', $hora);
        $connexion->bind(':respuesta', $respuesta);
        $connexion->bind(':id_pregunta', $id_pregunta);
        $connexion->bind(':id_medicion', $id_medicion);
        $connexion->bind(':id_encuesta', $id_encuesta);
        $connexion->bind(':comentario', $comentario);
        $connexion->bind(':id_foco', $id_proyecto);
        $connexion->bind(':id_tipo', $id_tipo);
        $connexion->execute();
    } else {
        $sql = "update tbl_enc_elearning_respuestas set fecha=:fecha, hora=:hora, respuesta=:respuesta, comentario=:comentario
    where id=:id";
        $connexion->query($sql);
        $connexion->bind(':fecha', $fecha);
        $connexion->bind(':hora', $hora);
        $connexion->bind(':respuesta', $respuesta);
        $connexion->bind(':comentario', $comentario);
        $connexion->bind(':id', $cod[0]->id);
        $connexion->execute();
    }
  
}

function ValidaEstadoMedicionIndividual($rut, $id_empresa, $id_encuesta, $id_medicion)
{
    $connexion = new DatabasePDO();
    $sql = "
select count(h.id) as num_preguntas,
(select count(id) from tbl_enc_elearning_respuestas where id_encuesta=:id_encuesta and id_medicion=:id_medicion and rut=:rut and id_empresa=:id_empresa) as num_respuestas
from tbl_enc_elearning_preg h
where h.id_encuesta=:id_encuesta and h.id_empresa=:id_empresa";

    $connexion->query($sql);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_medicion', $id_medicion);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);

    $cod = $connexion->resultset();
    if ($cod[0]->num_respuestas >= $cod[0]->num_preguntas and $cod[0]->num_preguntas > 0) {
        $estado = "FINALIZADO";
    } else {
        $estado = "PENDIENTE";
    }
    return $estado;
}

function ValidaEstadoMedicion($rut, $id_empresa, $id_encuesta, $id_medicion, $rut_colaborador)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT count(h.id) as num_preguntas,
(SELECT count(id) FROM tbl_enc_elearning_respuestas WHERE id_encuesta=:id_encuesta AND id_medicion=:id_medicion AND rut=:rut AND rut_colaborador=:rut_colaborador AND id_empresa=:id_empresa) as num_respuestas
FROM tbl_enc_elearning_preg h
WHERE h.id_encuesta=:id_encuesta AND h.id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_medicion', $id_medicion);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':rut_colaborador', $rut_colaborador);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    if ($cod[0]->num_respuestas >= $cod[0]->num_preguntas and $cod[0]->num_preguntas > 0) {
        $estado = "<span class='badge badge-info'><span class='blanco'>FINALIZADO</span></span>";
    } else {
        $estado = "<span class='badge badge-danger'><span class='blanco'>PENDIENTE</span></span>";
    }
    return $estado;
}

function ValidaEstadoMedicionFecha($rut, $id_empresa, $id_encuesta, $id_medicion, $fecha, $rut_colaborador)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT count(h.id) as num_preguntas,
(SELECT count(id) FROM tbl_enc_elearning_respuestas WHERE id_encuesta=:id_encuesta AND id_medicion=:id_medicion AND rut=:rut AND rut_colaborador=:rut_colaborador AND id_empresa=:id_empresa AND id_tipo=:fecha) as num_respuestas
FROM tbl_enc_elearning_preg h
WHERE h.id_encuesta=:id_encuesta AND h.id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_medicion', $id_medicion);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':rut_colaborador', $rut_colaborador);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':fecha', $fecha);
    $cod = $connexion->resultset();
    if ($cod[0]->num_respuestas >= $cod[0]->num_preguntas and $cod[0]->num_preguntas > 0) {
        $estado = "FINALIZADO";
    } else {
        $estado = "PENDIENTE";
    }
    return $estado;
}

function DatosMedicion($idm, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_enc_elearning_medicion WHERE id=:id AND id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id', $idm);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function ValidaEstadoMedicionEncuesta($rut, $id_empresa, $id_encuesta, $id_medicion)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT COUNT(h.id) AS num_preguntas,
        (SELECT COUNT(id) FROM tbl_enc_elearning_respuestas WHERE id_encuesta=:id_encuesta AND id_medicion=:id_medicion AND rut=:rut
        AND rut_colaborador=:rut_colaborador AND id_empresa=:id_empresa AND id_tipo=:fecha) AS num_respuestas
        FROM tbl_enc_elearning_preg h
        WHERE h.id_encuesta=:id_encuesta AND h.id_empresa=:id_empresa";

    $connexion->query($sql);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_medicion', $id_medicion);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':rut_colaborador', $rut_colaborador);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':fecha', $fecha);

    $cod = $connexion->resultset();

    if ($cod[0]->num_respuestas >= $cod[0]->num_preguntas && $cod[0]->num_preguntas > 0) {
        $estado = "FINALIZADO";
    } else {
        $estado = "PENDIENTE";
    }

    return $estado;
}

function VerficicaPreguntaMedFinalizada($id_empresa, $id_encuesta, $id_medicion, $rut, $rut_colaborador)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT
count(tbl_enc_elearning_preg.id) as numpreg, count(tbl_enc_elearning_respuestas.respuesta) as numres
FROM
tbl_enc_elearning_preg
    left join
    tbl_enc_elearning_respuestas
    on tbl_enc_elearning_respuestas.rut=:rut
    and tbl_enc_elearning_respuestas.id_medicion=:id_medicion
    and tbl_enc_elearning_respuestas.id_encuesta=:id_encuesta
    and tbl_enc_elearning_respuestas.id_pregunta=tbl_enc_elearning_preg.id_pregunta
    WHERE
    tbl_enc_elearning_preg.id_encuesta = :id_encuesta
    AND tbl_enc_elearning_preg.id_empresa = :id_empresa
    ";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_medicion', $id_medicion);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();

    $finalizado = 0;
    if ($cod[0]->numpreg <= $cod[0]->numres && $cod[0]->numpreg > 0) {
        $finalizado = 1;
    }
    return $finalizado;
}

function PreguntasMedicion($id_empresa, $id_encuesta, $id_medicion, $rut, $rut_colaborador)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT
tbl_enc_elearning_preg.*, tbl_enc_elearning_respuestas.respuesta
FROM
tbl_enc_elearning_preg
    left join
    tbl_enc_elearning_respuestas
    on tbl_enc_elearning_respuestas.rut=:rut
    and tbl_enc_elearning_respuestas.id_medicion=:id_medicion
    and tbl_enc_elearning_respuestas.id_encuesta=:id_encuesta
    and tbl_enc_elearning_respuestas.id_pregunta=tbl_enc_elearning_preg.id_pregunta
    WHERE
    tbl_enc_elearning_preg.id_encuesta = :id_encuesta
    AND tbl_enc_elearning_preg.id_empresa = :id_empresa
    ";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_medicion', $id_medicion);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}


function PreguntasMedicionColaboradoresFecha($id_empresa, $id_encuesta, $id_medicion, $rut, $rut_colaborador, $fecha)
{
    $connexion = new DatabasePDO();

    $sql =
        "
    SELECT h.*,
        (SELECT respuesta FROM tbl_enc_elearning_respuestas WHERE id_encuesta=:id_encuesta AND id_medicion=:id_medicion
        AND id_tipo=:fecha AND rut_colaborador=:rut_colaborador AND id_pregunta=h.id_pregunta) AS respuesta
    FROM tbl_enc_elearning_preg h
        WHERE h.id_encuesta=:id_encuesta
    ";

    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_medicion', $id_medicion);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':rut_colaborador', $rut_colaborador);
    $connexion->bind(':fecha', $fecha);

    $cod = $connexion->resultset();
    return $cod;
}

function PreguntasMedicionColaboradoresLibreFull($id_empresa, $id_encuesta, $id_medicion, $rut)
{
    $connexion = new DatabasePDO();

    $sql =
        "
    SELECT h.*   
    FROM tbl_enc_elearning_preg h
        WHERE h.id_encuesta=:id_encuesta
    ";

    $connexion->query($sql);
    $connexion->bind(':id_encuesta', $id_encuesta);

    $cod = $connexion->resultset();
    return $cod;
}

function PreguntasMedicionColaboradoresLibre($id_empresa, $id_encuesta, $id_medicion, $rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*, (SELECT respuesta FROM tbl_enc_elearning_respuestas WHERE id_encuesta = :id_encuesta AND id_medicion = :id_medicion AND id_pregunta = h.id_pregunta AND rut = :rut) AS respuesta FROM tbl_enc_elearning_preg h WHERE h.id_encuesta = :id_encuesta AND (h.linkpregunta IS NULL OR h.linkpregunta = '')";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_medicion', $id_medicion);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}

function PreguntasMedicionColaboradoresLibreOpcion($id_empresa, $id_encuesta, $id_medicion, $rut, $opcion)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*, (SELECT respuesta FROM tbl_enc_elearning_respuestas WHERE id_encuesta = :id_encuesta AND id_medicion = :id_medicion AND id_pregunta = h.id_pregunta AND rut = :rut) AS respuesta FROM tbl_enc_elearning_preg h WHERE h.id_encuesta = :id_encuesta AND h.linkopcion = :opcion";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_medicion', $id_medicion);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':opcion', $opcion);
    $cod = $connexion->resultset();
    return $cod;
}

function PreguntasMedicionColaboradoresLibreLInkeada($id_pregunta, $id_empresa, $id_encuesta, $id_medicion, $rut)
{
    $connexion = new DatabasePDO();
    $sql =
        "
    select h.*,
        (select respuesta from tbl_enc_elearning_respuestas where id_encuesta=:id_encuesta and id_medicion=:id_medicion
        and id_pregunta=h.id_pregunta and rut=:rut)  as respuesta

    from tbl_enc_elearning_preg h
        where h.id_encuesta=:id_encuesta
        AND h.linkpregunta =:id_pregunta

        order by linkopcion asc

    ";
    $connexion->query($sql);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_medicion', $id_medicion);
    $connexion->bind(':id_pregunta', $id_pregunta);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}


function datos_Encuesta_front_lb($id_encuesta)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT
    *
    FROM
    tbl_enc_elearning_medicion

    where id=:id_encuesta";
    $connexion->query($sql);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $cod = $connexion->resultset();
    return $cod;
}

function ActualizaComentariosEncGeneral($rut, $id_empresa, $id_objeto, $id_encuesta, $comentario_final, $comentario_final2)
{
    $connexion = new DatabasePDO();
    $sql = "UPDATE tbl_enc_satis_comentarios_finales
    SET comentario= :comentario_final, comentario2= :comentario_final2
    where id_empresa=:id_empresa and rut=:rut and id_objeto=:id_objeto and id_encuesta=:id_encuesta  AND (rut_relator = '' or rut_relator is null) ";
    $connexion->query($sql);
    $connexion->bind(':comentario_final', $comentario_final);
    $connexion->bind(':comentario_final2', $comentario_final2);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_objeto', $id_objeto);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->execute();
}

function TieneComentarioFinalEncSatisGeneral($rut, $id_empresa, $id_objeto, $id_encuesta)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT *
FROM tbl_enc_satis_comentarios_finales
WHERE rut = :rut
AND id_empresa = :id_empresa
AND id_objeto = :id_objeto";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_objeto', $id_objeto);
    $cod = $connexion->resultset();
    return $cod;
}

function ActualizaRespuestasPorPReguntaEncuesta($rut, $id_empresa, $id_objeto, $id_encuesta, $id_pregunta, $respuesta)
{
    $connexion = new DatabasePDO();
    $sql = "UPDATE tbl_enc_satis_respuestas
SET respuesta = :respuesta
WHERE id_empresa = :id_empresa
AND rut = :rut
AND id_objeto = :id_objeto
AND id_encuesta = :id_encuesta
AND id_pregunta = :id_pregunta";
    $connexion->query($sql);
    $connexion->bind(':respuesta', $respuesta);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_objeto', $id_objeto);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_pregunta', $id_pregunta);
    $connexion->execute();
}

function TieneRespuestasPorPReguntaEncuesta($rut, $id_empresa, $id_objeto, $id_encuesta, $id_pregunta)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_enc_satis_respuestas
WHERE rut = :rut
AND id_empresa = :id_empresa
AND id_objeto = :id_objeto
AND id_encuesta = :id_encuesta
AND id_pregunta = :id_pregunta";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_objeto', $id_objeto);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_pregunta', $id_pregunta);
    $cod = $connexion->resultset();
    return $cod;
}

function ENC_CuentaDeRespuestasPorPregunta_7opciones($id_empresa, $id_encuesta, $id_pregunta, $id_objeto)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT COUNT(h.id) AS cuenta,
                   (SELECT COUNT(id) FROM tbl_enc_satis_respuestas WHERE id_empresa=:id_empresa AND id_encuesta=:id_encuesta AND id_objeto=:id_objeto AND id_pregunta=:id_pregunta AND respuesta='1') AS cuenta1,
                   (SELECT COUNT(id) FROM tbl_enc_satis_respuestas WHERE id_empresa=:id_empresa AND id_encuesta=:id_encuesta AND id_objeto=:id_objeto AND id_pregunta=:id_pregunta AND respuesta='2') AS cuenta2,
                   (SELECT COUNT(id) FROM tbl_enc_satis_respuestas WHERE id_empresa=:id_empresa AND id_encuesta=:id_encuesta AND id_objeto=:id_objeto AND id_pregunta=:id_pregunta AND respuesta='3') AS cuenta3,
                   (SELECT COUNT(id) FROM tbl_enc_satis_respuestas WHERE id_empresa=:id_empresa AND id_encuesta=:id_encuesta AND id_objeto=:id_objeto AND id_pregunta=:id_pregunta AND respuesta='4') AS cuenta4,
                   (SELECT COUNT(id) FROM tbl_enc_satis_respuestas WHERE id_empresa=:id_empresa AND id_encuesta=:id_encuesta AND id_objeto=:id_objeto AND id_pregunta=:id_pregunta AND respuesta='5') AS cuenta5,
                   (SELECT COUNT(id) FROM tbl_enc_satis_respuestas WHERE id_empresa=:id_empresa AND id_encuesta=:id_encuesta AND id_objeto=:id_objeto AND id_pregunta=:id_pregunta AND respuesta='6') AS cuenta6,
                   (SELECT COUNT(id) FROM tbl_enc_satis_respuestas WHERE id_empresa=:id_empresa AND id_encuesta=:id_encuesta AND id_objeto=:id_objeto AND id_pregunta=:id_pregunta AND respuesta='7') AS cuenta7
            FROM tbl_enc_satis_respuestas h
            WHERE h.id_empresa=:id_empresa AND h.id_encuesta=:id_encuesta AND h.id_objeto=:id_objeto AND h.id_pregunta=:id_pregunta";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_objeto', $id_objeto);
    $connexion->bind(':id_pregunta', $id_pregunta);
    $cod = $connexion->resultset();
    return $cod;
}

function EncTraePaginaMayor($id_encuesta)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT MAX(DISTINCT(pagina)) AS pagina_mayor
            FROM tbl_enc_satis_preg
            INNER JOIN tbl_enc_satis_dim ON tbl_enc_satis_dim.iddimension=tbl_enc_satis_preg.iddimension
            INNER JOIN tbl_enc_satis ON tbl_enc_satis.idencuesta=tbl_enc_satis_dim.idencuesta
            WHERE tbl_enc_satis.idencuesta=:id_encuesta";
    $connexion->query($sql);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $cod = $connexion->resultset();
    return $cod;
}

function InsertoRegistroRespuestaEncSatis($rut, $id_pregunta, $respuesta, $id_encuesta, $id_empresa, $id_inscripcion, $id_objeto, $rut_relator)
{
    $connexion = new DatabasePDO();
    $sql = "INSERT INTO tbl_enc_satis_respuestas(id_encuesta, id_pregunta, id_empresa, rut, respuesta, fecha, hora, id_inscripcion, id_objeto, rut_relator) " . "VALUES (:id_encuesta, :id_pregunta, :id_empresa, :rut, :respuesta, :fecha, :hora, :id_inscripcion, :id_objeto, :rut_relator);";
    $connexion->query($sql);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_pregunta', $id_pregunta);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':respuesta', $respuesta);
    $connexion->bind(':fecha', date("Y-m-d"));
    $connexion->bind(':hora', date("H:i:s"));
    $connexion->bind(':id_inscripcion', $id_inscripcion);
    $connexion->bind(':id_objeto', $id_objeto);
    $connexion->bind(':rut_relator', $rut_relator);
    $connexion->execute();
}

function EncActualizaRegistroSesion($rut, $id_empresa, $pagina, $id_objeto, $id_encuesta)
{
    $connexion = new DatabasePDO();
    $sql = "UPDATE tbl_enc_satis_sesion SET pagina=:pagina, fecha=:fecha, hora=:hora WHERE rut=:rut AND id_empresa=:id_empresa AND id_objeto=:id_objeto AND idencuesta=:id_encuesta";
    $connexion->query($sql);
    $connexion->bind(':pagina', $pagina);
    $connexion->bind(':fecha', date("Y-m-d"));
    $connexion->bind(':hora', date("H:i:s"));
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_objeto', $id_objeto);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->execute();
}

function EncInsertaRegistroSesion($rut, $id_empresa, $pagina, $id_inscripcion, $id_objeto, $id_encuesta)
{
    $connexion = new DatabasePDO();
    $sql = "INSERT INTO tbl_enc_satis_sesion(rut, pagina, id_empresa, fecha, hora, id_inscripcion, id_objeto, idencuesta) " . "VALUES (:rut, :pagina, :id_empresa, :fecha, :hora, :id_inscripcion, :id_objeto, :id_encuesta);";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':pagina', $pagina);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':fecha', date("Y-m-d"));
    $connexion->bind(':hora', date("H:i:s"));
    $connexion->bind(':id_inscripcion', $id_inscripcion);
    $connexion->bind(':id_objeto', $id_objeto);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->execute();
}

function EncSatisTopBottom($id_inscripcion, $limit, $order)
{
    $connexion = new DatabasePDO();
    $sql = "
        SELECT
            tbl_enc_satis.idencuesta,
            tbl_enc_satis.nombre,
            tbl_enc_satis_dim.iddimension,
            tbl_enc_satis_dim.dimension,
            tbl_enc_satis_preg.idpregunta,
            tbl_enc_satis_preg.pregunta,
            AVG(tbl_enc_satis_opc.puntaje) as promedio_por_pregunta
        FROM
            tbl_enc_satis_respuestas
            INNER JOIN tbl_enc_satis_preg ON tbl_enc_satis_preg.idpregunta = tbl_enc_satis_respuestas.id_pregunta
            INNER JOIN tbl_enc_satis_dim ON tbl_enc_satis_dim.iddimension = tbl_enc_satis_preg.iddimension
            INNER JOIN tbl_enc_satis ON tbl_enc_satis.idencuesta = tbl_enc_satis_dim.idencuesta
            inner join tbl_enc_satis_opc on tbl_enc_satis_opc.idopcion=tbl_enc_satis_preg.idopcion and tbl_enc_satis_respuestas.respuesta=tbl_enc_satis_opc.opcion
        where tbl_enc_satis_respuestas.id_inscripcion=:id_inscripcion
        group BY tbl_enc_satis_preg.idpregunta
        order BY promedio_por_pregunta $order limit :limit
    ";
    $connexion->query($sql);
    $connexion->bind(':id_inscripcion', $id_inscripcion);
    $connexion->bind(':limit', $limit, PDO::PARAM_INT);
    $cod = $connexion->resultset();
    return $cod;
}

function EncSatisPromedioPorDimension($id_inscripcion, $id_dimension)
{
    $connexion = new DatabasePDO();
    $sql = "
        SELECT
            tbl_enc_satis.idencuesta,
            tbl_enc_satis.nombre,
            tbl_enc_satis_dim.iddimension,
            tbl_enc_satis_dim.dimension,
            AVG(tbl_enc_satis_opc.puntaje) as promedio
        FROM
            tbl_enc_satis_respuestas
            INNER JOIN tbl_enc_satis_preg ON tbl_enc_satis_preg.idpregunta = tbl_enc_satis_respuestas.id_pregunta
            INNER JOIN tbl_enc_satis_dim ON tbl_enc_satis_dim.iddimension = tbl_enc_satis_preg.iddimension
            INNER JOIN tbl_enc_satis ON tbl_enc_satis.idencuesta = tbl_enc_satis_dim.idencuesta
            INNER JOIN tbl_enc_satis_opc ON tbl_enc_satis_opc.idopcion = tbl_enc_satis_preg.idopcion AND tbl_enc_satis_respuestas.respuesta = tbl_enc_satis_opc.opcion
        WHERE
            tbl_enc_satis_respuestas.id_inscripcion = :id_inscripcion
            AND tbl_enc_satis_dim.iddimension = :id_dimension
        GROUP BY
            tbl_enc_satis_dim.iddimension
        ORDER BY
            tbl_enc_satis.idencuesta,
            tbl_enc_satis_dim.iddimension
    ";
    $connexion->query($sql);
    $connexion->bind(':id_inscripcion', $id_inscripcion);
    $connexion->bind(':id_dimension', $id_dimension);
    $cod = $connexion->resultset();
    return $cod;
}

function DimensionPorEncuesta($id_encuesta)
{
    $connexion = new DatabasePDO();
    $sql = "
        SELECT tbl_enc_satis.idencuesta, tbl_enc_satis.nombre, tbl_enc_satis_dim.iddimension, tbl_enc_satis_dim.dimension
        FROM tbl_enc_satis_dim
        INNER JOIN tbl_enc_satis ON tbl_enc_satis.idencuesta = tbl_enc_satis_dim.idencuesta
        WHERE tbl_enc_satis.idencuesta = :id_encuesta
    ";
    $connexion->query($sql);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $cod = $connexion->resultset();
    return $cod;
}

function InsertoRegistroFinEncSatis($rut, $id_encuesta, $id_empresa, $id_inscripcion, $id_objeto)
{
    $connexion = new DatabasePDO();
    $sql = "
        INSERT INTO tbl_enc_satis_finalizados(rut, id_encuesta, id_empresa, fecha, hora, id_inscripcion, id_objeto) 
        VALUES (:rut, :id_encuesta, :id_empresa, :fecha, :hora, :id_inscripcion, :id_objeto)
    ";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':fecha', date("Y-m-d"));
    $connexion->bind(':hora', date("H:i:s"));
    $connexion->bind(':id_inscripcion', $id_inscripcion);
    $connexion->bind(':id_objeto', $id_objeto);
    $connexion->execute();
}

function DatosPreguntaPorId($id)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT tbl_evaluaciones_preguntas.*, tbl_evaluaciones_tipo_preguntas.feedback
            FROM tbl_evaluaciones_preguntas
            LEFT JOIN tbl_evaluaciones_tipo_preguntas ON tbl_evaluaciones_tipo_preguntas.id=tbl_evaluaciones_preguntas.tipo
            WHERE tbl_evaluaciones_preguntas.id=:id";
    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $cod = $connexion->resultset();
    return $cod;
}

function CategoriasNoticiasPorEmpresa($id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT tbl_noticias_categorias.*, 
                   (SELECT COUNT(*) AS total FROM tbl_noticias WHERE categoria=tbl_noticias_categorias.id AND tbl_noticias.id_empresa=:id_empresa) AS total_noticia,
                   (SELECT COUNT(*) AS total_sub_catego FROM tbl_noticias_subcategorias WHERE tbl_noticias_subcategorias.id_categoria=tbl_noticias_categorias.id) AS tota_sub_cart
            FROM tbl_noticias_categorias
            WHERE tbl_noticias_categorias.id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function InsertaVotoUnico($id, $rut)
{
    $connexion = new DatabasePDO();
    $sql = "INSERT INTO tbl_galeria_votos(id_archivo, id_usuario) VALUES (:id, :rut)";
    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $connexion->bind(':rut', $rut);
    $connexion->execute();
}

function VerificoEncuestaFinalizadaPorInsc($id_encuesta, $id_empresa, $rut, $id_inscripcion)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_enc_satis_finalizados WHERE rut = :rut AND id_encuesta = :id_encuesta AND id_empresa = :id_empresa AND id_inscripcion = :id_inscripcion";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_inscripcion', $id_inscripcion);
    $cod = $connexion->resultset();
    return $cod;
}

function VerificoEncuestaFinalizada($id_encuesta, $id_empresa, $rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_enc_satis_finalizados WHERE rut = :rut AND id_encuesta = :id_encuesta AND id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function DDatosEncSatis($id_encuesta)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_enc_satis WHERE idencuesta = :id_encuesta";
    $connexion->query($sql);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $cod = $connexion->resultset();
    return $cod;
}
