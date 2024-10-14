<?php
function MisIncentivos_Datos_rut($rut)
{
    $connexion = new DatabasePDO();
    //WHERE rut_jefe=:rut_jefe
    $sql = "select silla_id_cargo, silla_id_division, silla_id_unidad, nombre_completo, rut_completo, cargo, rut from tbl_usuario where rut='$rut' ";
    //echo $sql;
    $connexion->query($sql);
    //$connexion->bind(':rut_jefe', $rut_jefe);
    $cod = $connexion->resultset();
    $arreglo["id_cargo"] = $cod[0]->silla_id_cargo;
    $arreglo["id_division"] = $cod[0]->silla_id_division;
    $arreglo["id_unidad"] = $cod[0]->silla_id_unidad;
    $arreglo["id_unidad"] = $cod[0]->silla_id_unidad;
    $arreglo["nombre_completo"] = $cod[0]->nombre_completo;
    $arreglo["rut_completo"] = $cod[0]->rut_completo;
    $arreglo["rut"] = $cod[0]->rut;
    $arreglo["cargo"] = $cod[0]->cargo;
    //print_r($arreglo);exit();
    return $arreglo;
}

function MisIncentivos_ListaMisAudencias_rut($rut, $id_cargo, $id_division, $id_unidad)
{
    $connexion = new DatabasePDO();

    $audiencias = [];
    $audienciasString = '';

    //Se obtiene las audiencias segun la estructura organizacional proporcionada
    $sql = "SELECT id
            FROM mis_incentivos_estructura_audiencias ma
            WHERE (cargos LIKE :idCargo AND unidades IS NULL AND divisiones IS NULL)
              OR (unidades LIKE :idUnidad AND cargos IS NULL AND divisiones IS NULL)
              OR (divisiones LIKE :idDivision AND cargos IS NULL AND unidades IS NULL)
              OR (cargos LIKE :idCargo AND unidades LIKE :idUnidad AND divisiones IS NULL)
              OR (cargos LIKE :idCargo AND divisiones LIKE :idDivision AND unidades IS NULL)
              OR (cargos is null AND unidades LIKE :idDivision AND divisiones LIKE :idDivision)
              OR (cargos LIKE :idCargo AND divisiones LIKE :idDivision AND unidades LIKE :idUnidad)";
    //echo "<br>".$sql;
    $connexion->query($sql);
    $connexion->bind(":idCargo", "%$id_cargo%");
    $connexion->bind(":idDivision", "%$id_division%");
    $connexion->bind(":idUnidad", "%$id_unidad%");
    $result = $connexion->resultset();

    //Se verifica no se encuentre excluido de alguna de esas audiencias por el rut
    foreach ($result as $row) {
        array_push($audiencias, $row->id);
        $audienciasString .= "$row->id,";
    }
    $sql = "SELECT id_audiencia FROM mis_incentivos_audiencia_usuarios WHERE id_usuario=:rut and estado=1 and tipo=1 and FIND_IN_SET(id_audiencia, '$audienciasString') > 0";

    $connexion->query($sql);
    $connexion->bind(":rut", $rut);
    $excluido = $connexion->resultset();
    foreach ($excluido as $rowe) {
        $indice = array_search($rowe->id_audiencia, $audiencias);
        if ($indice !== false) {
            unset($audiencias[$indice]);
        }
    }

    $sql = "SELECT id_audiencia FROM mis_incentivos_audiencia_usuarios mau,  mis_incentivos_audiencia ma WHERE mau.id_audiencia=ma.id and ma.estado=1 and mau.id_usuario=:rut and mau.estado=1 and mau.tipo=2";
    $connexion->query($sql);
    $connexion->bind(":rut", $rut);
    $incluidos = $connexion->resultset();
    foreach ($incluidos as $rowe) {
        $indice = array_search($rowe->id_audiencia, $audiencias);
        if ($indice === false) {
            array_push($audiencias, $rowe->id_audiencia);
        }
    }

    //print_r($audiencias);
    //echo "FIN";
    //echo count($audiencias);
    if (count($audiencias) > 0) {
        $sql = "SELECT id FROM mis_incentivos_audiencia WHERE id IN (" . implode(',', $audiencias) . ")";
        $connexion->query($sql);
        $audienciasDatos = $connexion->resultset();
    }
    //print_r($audienciasDatos); exit();
    //Se obtienen los datos de las audiencias encontradas


    return $audienciasDatos;
}

function publicacionVista($rut, $publicacion)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT view_rut FROM mis_incentivos_publicaciones_views where view_rut='$rut' and publicacion_codigo=" . $publicacion;
    //echo $sql;
    $connexion->query($sql);
    return $connexion->resultset();
}

function MisIncentivos_MiEquipo($jefe)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT rut FROM tbl_usuario where jefe='$jefe'";
    //echo $sql;
    $connexion->query($sql);
    //$connexion->bind(':jefe', $jefe);
    $cod = $connexion->resultset();
    return $cod;
}

function MisIncentivos_Publicaciones_por_idAudiencia($id_audiencia)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM mis_incentivos_publicaciones where id_audiencia='$id_audiencia' and estado='1'";
    $connexion->query($sql);
    //$connexion->bind(':id_audiencia', $id_audiencia);
    $cod = $connexion->resultset();
    return $cod;
}

function MisIncentivos_rut($rut)
{
    $connexion = new DatabasePDO();
    //WHERE rut_jefe=:rut_jefe
    $sql = "SELECT * FROM mis_incentivos_publicaciones where estado='1' limit 2";
    $connexion->query($sql);
    //$connexion->bind(':rut_jefe', $rut_jefe);
    $cod = $connexion->resultset();
    return $cod;
}

function MisIncentivos_IdPublicacion($id)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM mis_incentivos_publicaciones where estado='1' and id='$id'";
    $connexion->query($sql);
    //$connexion->bind(':id', $id);
    $cod = $connexion->resultset();
    return $cod;
}

function MisIncentivos_documento($id_publicacion)
{
    $connexion = new DatabasePDO();
    //WHERE rut_jefe=:rut_jefe
    $sql = "SELECT documento_base_64 as documento FROM mis_incentivos_publicaciones mp,mis_incentivos_documento md where mp.id_documento=md.id and mp.id=:id_publicacion ";
    $connexion->query($sql);
    $connexion->bind(':id_publicacion', $id_publicacion);
    $cod = $connexion->resultset();
    return $cod[0]->documento;
}

function InsertarVisualizacion($rut, $id_publicacion)
{
    $connexion = new DatabasePDO();
    $hoy = date("Y-m-d");
    $hora = date("H:i:s");
    $sql = "INSERT INTO mis_incentivos_publicaciones_views (publicacion_codigo, view_rut, fecha,hora) VALUES (:publicacion_codigo, :view_rut, :fecha,:hora)";
    $connexion->query($sql);
    $connexion->bind(':publicacion_codigo', $id_publicacion);
    $connexion->bind(':view_rut', $rut);
    $connexion->bind(':fecha', $hoy);
    $connexion->bind(':hora', $hora);
    $connexion->execute();
}

function MisIncentivos_groupby_options($tipo)
{
    $connexion = new DatabasePDO();
    //WHERE rut_jefe=:rut_jefe
    $sql = "SELECT nombre_item FROM mis_incentivos_publicaciones_options where tipo=:tipo";
    //echo $sql;
    $connexion->query($sql);
    $connexion->bind(':tipo', $tipo);
    $cod = $connexion->resultset();
    return $cod;
}

function MisIncentivos_publicaciones_options_traduccion($id_item)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM mis_incentivos_publicaciones_options where id_item=:id_item ";
    $connexion->query($sql);
    $connexion->bind(':id_item', $id_item);
    $cod = $connexion->resultset();
    return $cod[0]->nombre_item;
}

function MisIncentivos_avatar($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT avatar FROM tbl_avatar WHERE rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();

    if (!empty($cod)) {
        return $cod[0]->avatar;
    } else {
        return "img/sinfoto.png";
    }
}


function MisIncentivos_EquipoParaIdAudiencia_SoyJefe($rut, $id_audiencia)
{
    //echo "<br>MisIncentivos_EquipoParaIdAudiencia_SoyJefe($id_audiencia, rut $rut)";
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_usuario where jefe=:rut ";
    //echo $sql;
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}

function MisIncentivos_DatosRut($rut)
{
    //echo "<br>MisIncentivos_EquipoParaIdAudiencia_SoyJefe($id_audiencia, rut $rut)";
    $connexion = new DatabasePDO();
    $sql = "SELECT nombre_completo, cargo, rut FROM tbl_usuario where rut=:rut ";
    //echo $sql;
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}