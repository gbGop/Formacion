<?php
function Amonestacion_UpdateGestor($ida,$estado,$comentario) {   
    $connexion = new DatabasePDO();
    $hoy = date("Y-m-d");
    $update = "UPDATE tbl_reconoce_gracias SET estado=:estado, fecha_validacion='$hoy', comentarios=:comentario WHERE id=:ida";
    $connexion->query($update);
    $connexion->bind(':ida', $ida);
    $connexion->bind(':estado', $estado);
    $connexion->bind(':comentario', $comentario);
    $connexion->execute();
}
function Amonestacion_UpdateGestor_v2($ida,$estado,$comentario, $categoria) { 
    $rut_gestor=$_SESSION["user_"];
    //echo "<br> Amonestacion_UpdateGestor_v2($ida,$estado,$comentario, $categoria)"; exit();
    $connexion = new DatabasePDO();
    $hoy = date("Y-m-d");
    $categoria=utf8_decode($categoria);
    $comentario=utf8_decode($comentario);
    $update = "UPDATE tbl_reconoce_gracias SET estado=:estado, categorita=:categoria, id_categoria=:categoria, fecha_validacion='$hoy', comentarios=:comentario, agrupacion=:agrupacion WHERE id=:ida";
    $connexion->query($update);
    $connexion->bind(':ida', $ida);
    $connexion->bind(':estado', $estado);
    $connexion->bind(':categoria', $categoria);
    $connexion->bind(':comentario', $comentario);
    $connexion->bind(':agrupacion', $rut_gestor);
    $connexion->execute();
}
function Amonestacion_UpdateOK($ida) {
    $connexion = new DatabasePDO();
    $hoy = date("Y-m-d");
    $update = "UPDATE tbl_reconoce_gracias SET estado='OK' WHERE id=:ida";
    $connexion->query($update);
    $connexion->bind(':ida', $ida);
    $connexion->execute();
}
function Amonestacion_BuscaGestor($rut_jefe){
    $connexion = new DatabasePDO();
    $sql = "SELECT rut_gestor, rut_gestor_backup FROM	tbl_gestores h INNER JOIN	tbl_usuario w on w.rut=:rut_jefe WHERE 	w.id_gerencia=h.cui";
    $connexion->query($sql);
    $connexion->bind(':rut_jefe', $rut_jefe);
    $cod = $connexion->resultset();
    return $cod;
}
function Amonestacion_PorId($id){
    $connexion = new DatabasePDO();
    //echo "id $id";
    $sql = "SELECT * FROM tbl_reconoce_gracias WHERE id=:id";
    //echo $sql;
    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $cod = $connexion->resultset();
    return $cod;
}
function Amonestaciones_misamonestaciones($rut){
    //echo "rut $rut";
    $connexion = new DatabasePDO();
    //echo "id $id";
    $sql = "SELECT * FROM tbl_reconoce_gracias WHERE tipo='AMONESTACION' and rut_remitente=:rut and estado<>'PENDIENTE' order by id DESC";
    //echo $sql;
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}

function Amonestacion_SoyGestor($rut){
    $connexion = new DatabasePDO();
    $sql = "SELECT rut_gestor FROM tbl_gestores WHERE rut_gestor=:rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod[0]->rut_gestor;
}
function FC_cursos_Destacados_2022_data(){
    $connexion = new DatabasePDO();
    $sql1="SELECT * FROM tbl_lms_curso WHERE destacado_fc='1'";
    $connexion->query($sql1);
    $cod1 = $connexion->resultset();
    return $cod1;
}

function ONA_Jefatura_data($rut){
    $connexion = new DatabasePDO();
    $sql1="SELECT nivel_influencia, total_nominaciones FROM tbl_ona_nominaciones_jefaturas WHERE rut=:rut";
    $sql2="SELECT comunicacion,asesoramiento,confianza,politica,nominaciones_nivel FROM tbl_ona_nominaciones_jefaturas WHERE rut=:rut AND categoria='interno'";
    $sql3="SELECT comunicacion,asesoramiento,confianza,politica,nominaciones_nivel FROM tbl_ona_nominaciones_jefaturas WHERE rut=:rut AND categoria='externo'";
    $connexion->query($sql1);
    $connexion->bind(':rut', $rut);
    $cod1 = $connexion->resultset();

    $connexion->query($sql2);
    $connexion->bind(':rut', $rut);
    $cod2 = $connexion->resultset();

    $connexion->query($sql3);
    $connexion->bind(':rut', $rut);
    $cod3 = $connexion->resultset();

    $arreglo[1]=$cod1;
    $arreglo[2]=$cod2;
    $arreglo[3]=$cod3;

    return $arreglo;
}

function ONA_Unidad_data($rut){
    $connexion = new DatabasePDO();
    $sql1="SELECT nivel_influencia, total_nominaciones FROM tbl_ona_nominaciones_unidad WHERE rut=:rut";
    $sql2="SELECT comunicacion,asesoramiento,confianza,politica,nominaciones_nivel FROM tbl_ona_nominaciones_unidad WHERE rut=:rut AND categoria='interno'";
    $sql3="SELECT comunicacion,asesoramiento,confianza,politica,nominaciones_nivel FROM tbl_ona_nominaciones_unidad WHERE rut=:rut AND categoria='externo'";
    $connexion->query($sql1);
    $connexion->bind(':rut', $rut);
    $cod1 = $connexion->resultset();

    $connexion->query($sql2);
    $connexion->bind(':rut', $rut);
    $cod2 = $connexion->resultset();

    $connexion->query($sql3);
    $connexion->bind(':rut', $rut);
    $cod3 = $connexion->resultset();

    $arreglo[1]=$cod1;
    $arreglo[2]=$cod2;
    $arreglo[3]=$cod3;

    return $arreglo;
}

function ONA_Reportes_data($rut){
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*,
            (SELECT comunicacion FROM tbl_ona_nominaciones_reportes_directos WHERE rut=:rut AND nombre=h.nombre AND categoria='externo') as comunicacion_ext,
            (SELECT asesoramiento FROM tbl_ona_nominaciones_reportes_directos WHERE rut=:rut AND nombre=h.nombre AND categoria='externo') as asesoramiento_ext,
            (SELECT confianza FROM tbl_ona_nominaciones_reportes_directos WHERE rut=:rut AND nombre=h.nombre AND categoria='externo') as confianza_ext,
            (SELECT politica FROM tbl_ona_nominaciones_reportes_directos WHERE rut=:rut AND nombre=h.nombre AND categoria='externo') as politica_ext
            FROM tbl_ona_nominaciones_reportes_directos h WHERE h.rut=:rut AND h.categoria='interno'";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod1 = $connexion->resultset();
    return $cod1;
}

function ONA_Colaboracion_data($rut){
    $connexion = new DatabasePDO();
    $sql = "SELECT h.* FROM tbl_ona_resultados_colaboracion h WHERE h.rut=:rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod1 = $connexion->resultset();
    return $cod1;
}

function ONA_Iframe_data($rut){
    $connexion = new DatabasePDO();
    $sql = "SELECT h.iframe_contenido FROM tbl_ona_rutas_html h WHERE h.rut=:rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod1 = $connexion->resultset();
    return $cod1[0]->iframe_contenido;
}

function Reco_2022_Contenidos($tipo){
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_reconoce_contenido WHERE tipo=:tipo";
    $connexion->query($sql);
    $connexion->bind(':tipo', $tipo);
    $cod = $connexion->resultset();
    return $cod;
}

function Rec_Busca_Puntos_Col_Disponibles_2021($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "
        SELECT
            sum(h.puntos) AS Puntos_Recibidos,
            (
                SELECT estado
                FROM tbl_puntos_jefe_colaboracion_entregados_2021
                WHERE rut = h.rut AND id_empresa = h.id_empresa
            ) AS Puntos_RepartidosEstado,
            (
                SELECT rut_col
                FROM tbl_puntos_jefe_colaboracion_entregados_2021
                WHERE rut = h.rut AND id_empresa = h.id_empresa
            ) AS Puntos_RepartidosRut,
            (SELECT sum(puntos) FROM tbl_puntos_jefe_colaboracion_entregados_2021 WHERE rut=h.rut AND id_empresa=h.id_empresa) AS Puntos_Repartidos
        FROM tbl_puntos_jefe_colaboracion_2021 h
        WHERE h.rut = :rut AND h.id_empresa = :id_empresa
    ";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $datos = $connexion->resultset();
    return $datos;
}

function Rec_Busca_Puntos_Col_Disponibles_2022($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "
        SELECT
            sum(h.puntos) AS Puntos_Recibidos,
            (
                SELECT
                    estado
                FROM
                    tbl_puntos_jefe_colaboracion_entregados_2023
                WHERE
                    rut = h.rut
                AND id_empresa = h.id_empresa
            ORDER BY id desc limit 1) AS Puntos_RepartidosEstado,
            (
                SELECT
                    rut_col
                FROM
                    tbl_puntos_jefe_colaboracion_entregados_2023
                WHERE
                    rut = h.rut
                AND id_empresa = h.id_empresa
            ORDER BY id desc limit 1) AS Puntos_RepartidosRut,
            (select sum(puntos) from tbl_puntos_jefe_colaboracion_entregados_2023 where rut=h.rut and id_empresa=h.id_empresa) as Puntos_Repartidos

        FROM
            tbl_puntos_jefe_colaboracion_2023 h
        WHERE
             h.rut = :rut
            AND h.id_empresa = :id_empresa
    ";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $datos = $connexion->resultset();
    return $datos;
}

function BuscaDatosReconocimiento($idrg, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "select * from tbl_reconoce_gracias where id_empresa=:id_empresa and id=:idrg";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':idrg', $idrg);
    $datos = $connexion->resultset();
    return $datos;
}

function Rec_Inserta_Reconocimiento_Colaboracion_pendiente_2021_col($rut_remitente, $rut_destinatario, $id_categoria, $categoria, $fecha, $hora, $id_empresa, $tipo, $puntos, $mensaje, $rut_jefatura, $estado)
{
    // echo "$rut_remitente,$rut_destinatario,$categoria,$fecha,$hora,$id_empresa,$tipo,$puntos,$mensaje, $rut_jefatura, $estado";
    // echo "<br> id_categoria $id_categoria', categoria $categoria'";

    $id_categoria="Reconocimiento Colaboracion 2021";
    $categoria="COLABORACION 2021";

    $connexion = new DatabasePDO();
    $mensaje = utf8_decode($mensaje);
    $categoria = utf8_decode($categoria);

    $sql ="SELECT id FROM tbl_reconoce_gracias WHERE rut_remitente=:rut_remitente AND rut_destinatario=:rut_destinatario AND tipo='COLABORACION' AND id_empresa=:id_empresa AND fecha>='2021-01-01' ORDER BY id DESC LIMIT 1";

    $connexion->query($sql);
    $connexion->bind(':rut_remitente', $rut_remitente);
    $connexion->bind(':rut_destinatario', $rut_destinatario);
    $connexion->bind(':id_empresa', $id_empresa);

    $cod2 = $connexion->resultset();

    $Usua=DatosUsuario_($rut_destinatario, $id_empresa);

    $division = $Usua[0]->division;
    $cargo = $Usua[0]->cargo;
    $area = $Usua[0]->area;
    $local = $Usua[0]->local;
    $departamento = $Usua[0]->departamento;
    $region = $Usua[0]->regional;

    if($cod2[0]->id>0){
        $sql = "UPDATE tbl_reconoce_gracias SET estado='PENDIENTE' WHERE rut_remitente=:rut_remitente AND rut_destinatario=:rut_destinatario AND tipo='COLABORACION' AND id_empresa=:id_empresa AND estado<>'OK'";

    }   else {
        $sql = "INSERT INTO tbl_reconoce_gracias (rut_remitente,rut_destinatario,id_categoria,categorita,fecha,hora,id_empresa,tipo,puntos,mensaje,estado,rut_jefatura,division, cargo, area, local,departamento, region )
                VALUES (:rut_remitente, :rut_destinatario, :id_categoria, :categoria, :fecha, :hora, :id_empresa, 'COLABORACION', :puntos, :mensaje, :estado, :rut_jefatura, :division, :cargo, :area, :local, :departamento, :region)";

        }

        $connexion->query($sql);
        $connexion->bind(':rut_remitente', $rut_remitente);
        $connexion->bind(':rut_destinatario', $rut_destinatario);
        $connexion->bind(':id_categoria', $id_categoria);
        $connexion->bind(':categoria', $categoria);
        $connexion->bind(':fecha', $fecha);
        $connexion->bind(':hora', $hora);
        $connexion->bind(':id_empresa', $id_empresa);
        $connexion->bind(':puntos', $puntos);
        $connexion->bind(':mensaje', $mensaje);
        $connexion->bind(':estado', $estado);
        $connexion->bind(':rut_jefatura', $rut_jefatura);
        $connexion->bind(':division', $division);
        $connexion->bind(':cargo', $cargo);
        $connexion->bind(':area', $area);
        $connexion->bind(':local', $local);
        $connexion->bind(':departamento', $departamento);
        $connexion->bind(':region', $region);
        $connexion->execute();
    
    $sql_entregado = "INSERT INTO tbl_puntos_jefe_colaboracion_entregados_2021 (rut, fecha, puntos, tipo, id_empresa, rut_col, estado) VALUES (:rut_remitente, :fecha, :puntos, :categoria, :id_empresa, :rut_destinatario, 'PENDIENTE')";
    $connexion->query($sql_entregado);
    $connexion->bind(':rut_remitente', $rut_remitente);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':puntos', $puntos);
    $connexion->bind(':categoria', $categoria);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut_destinatario', $rut_destinatario);
    $connexion->execute();

    $cod = $connexion->resultset();

    return $cod;
}

function Rec_Inserta_Reconocimiento_Colaboracion_pendiente_2022_col($rut_remitente, $rut_destinatario, $id_categoria, $categoria, $fecha, $hora, $id_empresa, $tipo, $puntos, $mensaje, $rut_jefatura, $estado, $tipo_entrega)
{
    $year=date("Y");
    $id_categoria="Reconocimiento Colaboracion $year";
    $categoria="COLABORACION $year";

    $connexion = new DatabasePDO();
    //$mensaje = utf8_encode($mensaje);
    $categoria = utf8_decode($categoria);
    $fecha_desde=$year."-01-01";
    $sql = "SELECT id FROM tbl_reconoce_gracias WHERE rut_remitente=:rut_remitente AND rut_destinatario=:rut_destinatario AND tipo='COLABORACION' AND id_empresa=:id_empresa 
                                      AND fecha>='$fecha_desde' ORDER BY id DESC LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':rut_remitente', $rut_remitente);
    $connexion->bind(':rut_destinatario', $rut_destinatario);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod2 = $connexion->resultset();

    $Usua=DatosUsuario_($rut_destinatario, $id_empresa);

    $division	=	$Usua[0]->division;
    $cargo		=	$Usua[0]->cargo;
    $area			=	$Usua[0]->area;
    $local		=	$Usua[0]->local;
    $departamento		=	$Usua[0]->departamento;
    $region		=	$Usua[0]->regional;

    if($cod2[0]->id>0){
        $sql = "UPDATE tbl_reconoce_gracias SET estado='PENDIENTE' WHERE rut_remitente=:rut_remitente AND rut_destinatario=:rut_destinatario AND tipo='COLABORACION' AND id_empresa=:id_empresa AND estado<>'OK'";
    } else {
        $sql = "INSERT INTO tbl_reconoce_gracias (rut_remitente, rut_destinatario, id_categoria, categorita, fecha, hora, id_empresa, tipo, puntos, mensaje, estado, rut_jefatura, division, cargo, area, local, departamento, region, comentarios) 
                VALUES (:rut_remitente, :rut_destinatario, :id_categoria, :categoria, :fecha, :hora, :id_empresa, 'COLABORACION', :puntos, :mensaje, :estado, :rut_jefatura, :division, :cargo, :area, :local, :departamento, :region, '$tipo_entrega')";
    }

    $connexion->query($sql);
    $connexion->bind(':rut_remitente', $rut_remitente);
    $connexion->bind(':rut_destinatario', $rut_destinatario);
    $connexion->bind(':id_categoria', $id_categoria);
    $connexion->bind(':categoria', $categoria);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':hora', $hora);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':puntos', $puntos);
    $connexion->bind(':mensaje', $mensaje);
    $connexion->bind(':estado', $estado);
    $connexion->bind(':rut_jefatura', $rut_jefatura);
    $connexion->bind(':division', $division);
    $connexion->bind(':cargo', $cargo);
    $connexion->bind(':area', $area);
    $connexion->bind(':local', $local);
    $connexion->bind(':departamento', $departamento);
    $connexion->bind(':region', $region);
    $connexion->execute();

	$sql_verifica = "SELECT * FROM tbl_puntos_jefe_colaboracion_entregados_2023 where rut=:rut_remitente and puntos=:puntos and rut_col=:rut_destinatario";
	$connexion->query($sql_verifica);
    $connexion->bind(':rut_remitente', $rut_remitente);
    $connexion->bind(':rut_destinatario', $rut_destinatario);
    $connexion->bind(':puntos', $puntos);
    $cod_verifica = $connexion->resultset();
	if($cod_verifica[0]->id>0){
		
	}else{
		$sql_entregado = "INSERT INTO tbl_puntos_jefe_colaboracion_entregados_2023 (rut, fecha, puntos, tipo, id_empresa, rut_col, estado) VALUES (:rut_remitente, :fecha, :puntos, :categoria, :id_empresa, :rut_destinatario, 'PENDIENTE')";
		$connexion->query($sql_entregado);
		$connexion->bind(':rut_remitente', $rut_remitente);
		$connexion->bind(':fecha', $fecha);
		$connexion->bind(':puntos', $puntos);
		$connexion->bind(':categoria', $categoria);
		$connexion->bind(':id_empresa', $id_empresa);
		$connexion->bind(':rut_destinatario', $rut_destinatario);
		$connexion->execute();
	}


    //$cod = $connexion->resultset();

    //return $cod;
}

function Rec_busca_reconocimientos_2021($rut, $tipo, $avalidar, $id_empresa)
{
$connexion = new DatabasePDO();
if ($avalidar == "PENDIENTE") {
$query = " h.rut_jefatura=:rut ";
} else {
$query = " h.rut_remitente=:rut ";
}
$sql = "
select h.*,
(select nombre_completo from tbl_usuario where rut=h.rut_destinatario) as nombre_destinatario,
(select nombre_completo from tbl_usuario where rut=h.rut_remitente) as nombre_remitente,
(select nombre_completo from tbl_usuario where rut=h.rut_jefatura) as nombre_jefatura,
(select cargo from tbl_usuario where rut=h.rut_destinatario) as cargo_destinatario,
(select cargo from tbl_usuario where rut=h.rut_remitente) as cargo_remitente,
(select cargo from tbl_usuario where rut=h.rut_jefatura) as cargo_jefatura,
(select departamento from tbl_usuario where rut=h.rut_destinatario) as departamento_destinatario,
(select departamento from tbl_usuario where rut=h.rut_remitente) as departamento_remitente,
(select departamento from tbl_usuario where rut=h.rut_jefatura) as departamento_jefatura
from tbl_reconoce_gracias h
where h.tipo=:tipo AND $query and h.estado=:avalidar and id_empresa=:id_empresa
order by fecha DESC, id DESC
";
$connexion->query($sql);
$connexion->bind(':rut', $rut);
$connexion->bind(':tipo', $tipo);
$connexion->bind(':avalidar', $avalidar);
$connexion->bind(':id_empresa', $id_empresa);
$datos = $connexion->resultset();
return $datos;
}

function Rec_busca_reconocimientos_2022($rut, $tipo, $avalidar, $id_empresa)
{
    $connexion = new DatabasePDO();
    if ($avalidar == "PENDIENTE") {
        $query = " h.rut_jefatura=:rut ";
    } else {
        $query = " h.rut_remitente=:rut ";
    }
    $sql = "

        select h.*,

    (select nombre_completo from tbl_usuario where rut=h.rut_destinatario) as nombre_destinatario,
    (select nombre_completo from tbl_usuario where rut=h.rut_remitente) as nombre_remitente,
    (select nombre_completo from tbl_usuario where rut=h.rut_jefatura) as nombre_jefatura,

    (select cargo from tbl_usuario where rut=h.rut_destinatario) as cargo_destinatario,
    (select cargo from tbl_usuario where rut=h.rut_remitente) as cargo_remitente,
    (select cargo from tbl_usuario where rut=h.rut_jefatura) as cargo_jefatura,

    (select departamento from tbl_usuario where rut=h.rut_destinatario) as departamento_destinatario,
    (select departamento from tbl_usuario where rut=h.rut_remitente) as departamento_remitente,
    (select departamento from tbl_usuario where rut=h.rut_jefatura) as departamento_jefatura



    from tbl_reconoce_gracias h

    where h.tipo=:tipo AND $query and h.estado=:avalidar and id_empresa=:id_empresa

    order by fecha DESC, id DESC

    ";
    //echo $sql;
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':tipo', $tipo);
    $connexion->bind(':avalidar', $avalidar);
    $connexion->bind(':id_empresa', $id_empresa);
    $datos = $connexion->resultset();
    return $datos;
}



function Rec_Inserta_Puntos_bancopuntos_2021($rut_remitente, $rut_destinatario, $fecha, $puntos, $tipo, $descripcion, $id_reco, $id_empresa, $cui)
{
    $connexion = new DatabasePDO();
    $mensaje = utf8_decode($mensaje);

    $tipo = utf8_decode($tipo);
    $descripcion = utf8_decode($descripcion);

    $descripcion = "COLABORACION 2021";

    $sql = "INSERT INTO tbl_premios_puntos_usuarios (rut, fecha, puntos, tipo, descripcion, id_empresa, id_reconoce_gracias)
            VALUES (:rut_destinatario, :fecha, :puntos, :tipo, :descripcion, :id_empresa, :id_reco)";
    $connexion->query($sql);
    $connexion->bind(':rut_destinatario', $rut_destinatario);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':puntos', $puntos);
    $connexion->bind(':tipo', $tipo);
    $connexion->bind(':descripcion', $descripcion);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_reco', $id_reco);
    $connexion->execute();

    $sql = "UPDATE tbl_puntos_jefe_colaboracion_entregados_2021 SET estado='OK'
            WHERE rut=:rut_remitente AND rut_col=:rut_destinatario AND tipo=:tipo";
    $connexion->query($sql);
    $connexion->bind(':rut_remitente', $rut_remitente);
    $connexion->bind(':rut_destinatario', $rut_destinatario);
    $connexion->bind(':tipo', $tipo);
    $connexion->execute();

    $sql = "UPDATE tbl_reconoce_gracias SET estado='OK', fecha=:current_date
            WHERE rut_remitente=:rut_remitente AND rut_destinatario=:rut_destinatario AND categorita=:tipo";
    $connexion->query($sql);
    $connexion->bind(':rut_remitente', $rut_remitente);
    $connexion->bind(':rut_destinatario', $rut_destinatario);
    $connexion->bind(':tipo', $tipo);
    $connexion->bind(':current_date', date("Y-m-d"));
    $connexion->execute();
}


function Rec_Delete_Puntos_bancopuntos_2021($rut_remitente, $rut_destinatario, $fecha, $puntos, $tipo, $descripcion, $id_reco,  $id_empresa, $cui)
{
    $connexion = new DatabasePDO();
    
    $tipo = utf8_decode($tipo);
    $descripcion = utf8_decode($descripcion);

    $sql = "DELETE from tbl_puntos_jefe_colaboracion_entregados_2021 where rut=:rut_remitente and rut_col=:rut_destinatario";
    $connexion->query($sql);
    $connexion->bind(':rut_remitente', $rut_remitente);
    $connexion->bind(':rut_destinatario', $rut_destinatario);
    $connexion->execute();

    $sql = "UPDATE tbl_reconoce_gracias set estado='RECHAZADO', puntos='0' where id=:id_reco";
    $connexion->query($sql);
    $connexion->bind(':id_reco', $id_reco);
    $connexion->execute();
}

function Rec_Inserta_Puntos_bancopuntos_2022($rut_remitente, $rut_destinatario, $fecha, $puntos, $tipo, $descripcion,$id_reco,  $id_empresa, $cui)
{
    $connexion = new DatabasePDO();
    $mensaje = utf8_decode($mensaje);
    $tipo = utf8_decode($tipo);
    $descripcion = utf8_decode($descripcion);
    $year=date("Y");
    $descripcion="COLABORACION $year";

    $sql = "INSERT INTO tbl_premios_puntos_usuarios (rut, fecha, puntos, tipo, descripcion, id_empresa, id_reconoce_gracias)
            VALUES (:rut_destinatario, :fecha, :puntos, :tipo, :descripcion, :id_empresa, :id_reco)";
    $connexion->query($sql);
    $connexion->bind(':rut_destinatario', $rut_destinatario);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':puntos', $puntos);
    $connexion->bind(':tipo', $tipo);
    $connexion->bind(':descripcion', $descripcion);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_reco', $id_reco);
    $connexion->execute();

    $sql = "UPDATE tbl_puntos_jefe_colaboracion_entregados_2023 SET estado='OK'
            WHERE rut=:rut_remitente AND rut_col=:rut_destinatario AND tipo=:tipo";
    $connexion->query($sql);
    $connexion->bind(':rut_remitente', $rut_remitente);
    $connexion->bind(':rut_destinatario', $rut_destinatario);
    $connexion->bind(':tipo', $tipo);
    $connexion->execute();

    $sql = "UPDATE tbl_reconoce_gracias SET estado='OK', fecha=:date
            WHERE rut_remitente=:rut_remitente AND rut_destinatario=:rut_destinatario AND categorita=:tipo";
    $connexion->query($sql);
    $connexion->bind(':rut_remitente', $rut_remitente);
    $connexion->bind(':rut_destinatario', $rut_destinatario);
    $connexion->bind(':tipo', $tipo);
    $connexion->bind(':date', date("Y-m-d"));
    $connexion->execute();
}


function Rec_Delete_Puntos_bancopuntos_2022($rut_remitente, $rut_destinatario, $fecha, $puntos, $tipo, $descripcion,$id_reco,  $id_empresa, $cui)
{
    $connexion = new DatabasePDO();

    $sql = "DELETE from tbl_puntos_jefe_colaboracion_entregados_2023 
            where rut=:rut_remitente and rut_col=:rut_destinatario";

    $connexion->query($sql);
    $connexion->bind(':rut_remitente', $rut_remitente);
    $connexion->bind(':rut_destinatario', $rut_destinatario);
    $connexion->execute();

    $sql = "UPDATE tbl_reconoce_gracias set estado='RECHAZADO', puntos='0'
            where id=:id_reco";

    $connexion->query($sql);
    $connexion->bind(':id_reco', $id_reco);
    $connexion->execute();
}

function BuscaNombreAreaZonaDivision_Area2022($dato,$campo_busqueda,$dato_resultado){
    $connexion = new DatabasePDO();
    if($data3<>""){ $query3=" and h.$c3='$data3' ";}
    $query2=" and h.$c2='$data2' ";
    $query1=" and h.$c1='$data1' ";
    $sql_cols="
    select $dato_resultado as data_txt
    from tbl_reportes_online_usuario_area h where h.id>0
    and $campo_busqueda='".$dato."'
    limit 1
    ";
    //echo $sql_cols;
    $connexion->query($sql_cols);
    $cod_cols = $connexion->resultset();
    return $cod_cols[0]->data_txt;
    }
    
    function OrgChart_lista_cols_data($data1,$data2,$data3, $c1, $c2, $c3){
    $connexion = new DatabasePDO();
    if($data3<>""){ $query3=" and h.$c3='$data3' ";}
    $query2=" and h.$c2='$data2' ";
    $query1=" and h.$c1='$data1' ";
    
    if($data3<>""){     $query3=" and h.silla_id_zona='$data3' ";}
    $query2=" and h.silla_id_area='$data2' ";
    $query1=" and h.silla_id_division='$data1' ";
    
    $sql_cols="
        select h.*,
        (select perfil_evaluacion from tbl_usuario where rut=h.rut) as nivel
        from tbl_reportes_online_usuario_area h where h.id>0  $query1 $query2 $query3
                                                and h.dependientes>0
        group by h.rut
        order by h.nivel DESC,  h.genero DESC
    ";
    $sql_cols="
    
                SELECT h.*, COUNT(uo.id) AS num_subordinates 
                FROM tbl_usuarios_orgchart h 
                INNER JOIN tbl_usuarios_orgchart uo ON uo.jefe = h.rut 
                GROUP BY h.rut 
                HAVING num_subordinates > 0 
                $query1 $query2 $query3
    ";
    
    $connexion->query($sql_cols);
    $cod_cols = $connexion->resultset();
    return $cod_cols;
    }

function OrgChart_C_Options_data($ca_dado_data,$ca_dado_field, $cb_buscado_field){
        $connexion = new DatabasePDO();
        $sql="select $cb_buscado_field from tbl_reportes_online_usuario_area where $ca_dado_field=:ca_dado_data
        and $cb_buscado_field is not null
        and $cb_buscado_field<>''
        group by $cb_buscado_field order by $cb_buscado_field";
        $connexion->query($sql);
        $connexion->bind(':ca_dado_data', $ca_dado_data);
        $cod = $connexion->resultset();
        return $cod;
}
        
function OrgChart_C_Options_porIdC_data($ca_dado_data,$ca_dado_field, $cb_buscado_field,$ca_id_dado_field, $cb_id_buscado_field){
        $connexion = new DatabasePDO();
        $sql="select $cb_buscado_field as data_txt,$cb_id_buscado_field as data_id from tbl_usuarios_orgchart
        where $ca_id_dado_field=:ca_dado_data
        and $cb_buscado_field is not null
        and $cb_buscado_field<>''
        group by $cb_buscado_field order by $cb_buscado_field";
        $connexion->query($sql);
        $connexion->bind(':ca_dado_data', $ca_dado_data);
        $cod = $connexion->resultset();
        return $cod;
}
        
function Check_Agradecimiento_2022($rut_remitente, $rut_destinatario){
        $hoy = date("Y-m-d");
        $connexion = new DatabasePDO();
        $sql="select count(id) as cuenta
        from tbl_reconoce_gracias
        where
        rut_remitente=:rut_remitente
        and rut_destinatario=:rut_destinatario
        and fecha=:hoy";
        $connexion->query($sql);
        $connexion->bind(':rut_remitente', $rut_remitente);
        $connexion->bind(':rut_destinatario', $rut_destinatario);
        $connexion->bind(':hoy', $hoy);
        $cod = $connexion->resultset();
        return utf8_encode($cod[0]->cuenta);
}

function Talento_Personas_NoEquipo_Search_Data($search){
    $connexion = new DatabasePDO();
    $sql="SELECT tbl_usuario.*, MATCH (tbl_usuario.nombre_completo) AGAINST (:search) AS relevance
          FROM tbl_usuario
          WHERE MATCH (tbl_usuario.nombre_completo) AGAINST (:search) > 0
          AND tbl_usuario.nombre_empresa_holding <> ''
          AND tbl_usuario.tipo_contrato <> 'CDCON00002'
          AND tbl_usuario.jefe <> :user
          ORDER BY relevance DESC, tbl_usuario.nombre_completo ASC";
    $connexion->query($sql);
    $connexion->bind(':search', $search);
    $connexion->bind(':user', $_SESSION["user_"]);
    $cod = $connexion->resultset();
    return $cod;
}

function BuscaAvatar2021($rut){
    $connexion = new DatabasePDO();
    $sql = "SELECT avatar FROM tbl_avatar WHERE rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return utf8_encode($cod[0]->avatar);
}

function Potencial_Checkear_sucesor($rut_suceder){
    $connexion = new DatabasePDO();
    $sql = "SELECT id FROM tbl_potencial_sucesion WHERE rut_suceder = :rut_suceder AND tipo = 'sucesor'";
    $connexion->query($sql);
    $connexion->bind(':rut_suceder', $rut_suceder);
    $cod = $connexion->resultset();
    return utf8_encode($cod[0]->id);
}

function Potencial_BuscaAlerta($rut){
    $connexion = new DatabasePDO();
    $sql="	
        select
        h.idc04 as alerta
        from tbl_potencial_formularios_respuestas h
        where h.rut = :rut and h.id_form = '3'";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();

    if($cod[0]->alerta=="SI"){

        $sql1="	
            select
            h.idc05 as observacion
            from tbl_potencial_formularios_respuestas h
            where h.rut = :rut and h.id_form = '3'";
        //echo $sql1;
        $connexion->query($sql1);
        $connexion->bind(':rut', $rut);
        $cod1 = $connexion->resultset();
        $alerta="<center><div class='alert alert-info'>Alerta</div>
        ".$cod1[0]->observacion."
        </center>";
    } else {
        $alerta="";
    }
    return ($alerta);
}

function Potencial_nomenclatura($cargo){
    /*$connexion = new DatabasePDO();
    $sql="    select cargo_completo from tbl_mc_nomenclatura_cargos where cargo_corto=:cargo limit 1      ";
    //echo $sql;
    $connexion->query($sql);
    $connexion->bind(':cargo', $cargo);
    $cod = $connexion->resultset();
    if($cod[0]->cargo_completo==""){
        $cod[0]->cargo_completo=$cargo;
    }
    return utf8_encode($cod[0]->cargo_completo);
    */
    return utf8_encode($cargo);
}

function Potencial_nomenclatura_sinutf8($cargo){
    /*$connexion = new DatabasePDO();
    $sql="    select cargo_completo from tbl_mc_nomenclatura_cargos where cargo_corto=:cargo limit 1      ";
    //echo $sql;
    $connexion->query($sql);
    $connexion->bind(':cargo', $cargo);
    $cod = $connexion->resultset();
    if($cod[0]->cargo_completo==""){
        $cod[0]->cargo_completo=$cargo;
    }
    return ($cod[0]->cargo_completo);*/
    return ($cargo);

}

function Potencial_abreviatura_sinutf8($glosa){
    /* $connexion = new DatabasePDO();
    $sql = "SELECT glosa_completa FROM tbl_abreviaturas_glosas WHERE glosa_actual = :glosa LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':glosa', $glosa);
    $cod = $connexion->resultset(); */
    return $glosa;
}

function Potencial_abreviatura($glosa){
    /*$connexion = new DatabasePDO();
    $sql = "SELECT glosa_completa FROM tbl_abreviaturas_glosas WHERE glosa_actual = :glosa LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':glosa', $glosa);
    $cod = $connexion->resultset(); */
    return utf8_encode($cod[0]->glosa_completa);
}

function Talento_CargoCritico($rut_col){
    $connexion = new DatabasePDO();
    $sql = "SELECT idc26 AS cargo_critico FROM tbl_potencial_cargos_criticos WHERE rut = :rut_col";
    $connexion->query($sql);
    $connexion->bind(':rut_col', $rut_col);
    $cod = $connexion->resultset();
    return $cod[0]->cargo_critico;
}

function Talento_CargoCritico_Rx($rut_col) {
    $connexion = new DatabasePDO();
    $esRx=0;

    $sql1 = "SELECT idc26 as cargo_critico FROM tbl_potencial_cargos_criticos WHERE rut = :rut_col";
    $connexion->query($sql1);
    $connexion->bind(':rut_col', $rut_col);
    $cod1 = $connexion->resultset();

    if($cod1[0]->cargo_critico == "SI") {

    } else {
        $sql2 = "SELECT idc06 as respuesta FROM tbl_potencial_formularios_respuestas WHERE rut = :rut_col AND id_form = '3'";
        $connexion->query($sql2);
        $connexion->bind(':rut_col', $rut_col);
        $cod2 = $connexion->resultset();

        if($cod2[0]->respuesta == "SI") {

        } else {
            $esRx = 1;
        }
    }

    return $esRx;
}

function Talento_CargoCritico_Rx_SINPincharCritico($rut_col){
    $connexion = new DatabasePDO();
    $esRx=0;

    $sql1 = "SELECT h.idc26 as cargo_critico FROM tbl_potencial_cargos_criticos h WHERE h.rut = :rut_col";
    $connexion->query($sql1);
    $connexion->bind(':rut_col', $rut_col);
    $cod1 = $connexion->resultset();

    if($cod1[0]->cargo_critico=="SI"){
        $sql2 = "SELECT h.idc06 as respuesta FROM tbl_potencial_formularios_respuestas h WHERE h.rut = :rut_col AND h.id_form='3'";
        $connexion->query($sql2);
        $connexion->bind(':rut_col', $rut_col);
        $cod2 = $connexion->resultset();

        if($cod2[0]->respuesta=="SI"){

        } else {
            $esRx=1;
        }

    } else {
        $sql2 = "SELECT h.idc06 as respuesta FROM tbl_potencial_formularios_respuestas h WHERE h.rut = :rut_col AND h.id_form='3'";
        $connexion->query($sql2);
        $connexion->bind(':rut_col', $rut_col);
        $cod2 = $connexion->resultset();

        if($cod2[0]->respuesta=="SI"){

        } else {
            $esRx=1;
        }
    }

    return $esRx;
}


function PotencialUpdateRutSuceder($rut_cambio, $rut_col1, $tiporeemplazo, $cargo_cambio, $division_cambio){
    $connexion = new DatabasePDO();
    $hoy = date("Y-m-d");
    $update = "UPDATE tbl_potencial_sucesion SET rut_suceder=:rut_cambio, fecha=:hoy, tipo_cambio=:tiporeemplazo, cargo_cambio=:cargo_cambio, division_cambio=:division_cambio WHERE rut_suceder=:rut_col1";
    $connexion->query($update);
    $connexion->bind(':rut_cambio', $rut_cambio);
    $connexion->bind(':hoy', $hoy);
    $connexion->bind(':tiporeemplazo', $tiporeemplazo);
    $connexion->bind(':cargo_cambio', $cargo_cambio);
    $connexion->bind(':division_cambio', $division_cambio);
    $connexion->bind(':rut_col1', $rut_col1);
    $connexion->execute();
}

function Clima2021_perfil($rut){
    $connexion = new DatabasePDO();
    $sql = "SELECT perfil FROM tbl_clima_2021 WHERE rut=:rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod[0]->perfil;
}

function PotencialLimpiaTextosTrim($texto){
    $texto = str_replace(";", ".", $texto);
    $texto = str_replace("<br>", "", $texto);
    $texto = str_replace("/\r|\n/", "", $texto);
    $texto = str_replace("/\r|\n/", "", $texto);
    $texto = str_replace("/\r|\n/", "", $texto);
    $texto = str_replace(array("\r", "\n"), '',  $texto);
    $texto = str_replace(array("\r\n", "\r", "\n"), "",  $texto);
    return $texto;
}

function Potencial_ficha_talento_editor_campos($idf){
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_potencial_formularios WHERE id=:idf";
    $connexion->query($sql);
    $connexion->bind(':idf', $idf);
    $cod = $connexion->resultset();
    return $cod;
}

function Potencial_ficha_talento_editor_data($idf, $division)
{
    $connexion = new DatabasePDO();
    
    $sql3 = "SELECT * FROM tbl_potencial_formularios WHERE id = :idf";
    $connexion->query($sql3);
    $connexion->bind(':idf', $idf);
    $cod3 = $connexion->resultset();
    
    $Campos1 = "Rut;Nombre_Completo;Division;";
    
    if ($cod3[0]->idc01 <> "") {
        $sql4 = "SELECT nombre FROM tbl_potencial_formularios_campos WHERE id = :idc01";
        $connexion->query($sql4);
        $connexion->bind(':idc01', $cod3[0]->idc01);
        $cod4 = $connexion->resultset();
        $Campos1 .= "" . $cod4[0]->nombre . ";";
    }
    
    if ($cod3[0]->idc02 <> "") {
        $sql4 = "SELECT nombre FROM tbl_potencial_formularios_campos WHERE id = :idc02";
        $connexion->query($sql4);
        $connexion->bind(':idc02', $cod3[0]->idc02);
        $cod4 = $connexion->resultset();
        $Campos1 .= "" . ($cod4[0]->nombre) . ";";
    }
    
    if ($cod3[0]->idc03 <> "") {
        $sql4 = "SELECT nombre FROM tbl_potencial_formularios_campos WHERE id = :idc03";
        $connexion->query($sql4);
        $connexion->bind(':idc03', $cod3[0]->idc03);
        $cod4 = $connexion->resultset();
        $Campos1 .= "" . ($cod4[0]->nombre) . ";";
    }
    
    if ($cod3[0]->idc04 <> "") {
        $sql4 = "SELECT nombre FROM tbl_potencial_formularios_campos WHERE id = :idc04";
        $connexion->query($sql4);
        $connexion->bind(':idc04', $cod3[0]->idc04);
        $cod4 = $connexion->resultset();
        $Campos1 .= "" . ($cod4[0]->nombre) . ";";
    }
    
    if ($cod3[0]->idc05 <> "") {
        $sql4 = "SELECT nombre FROM tbl_potencial_formularios_campos WHERE id = :idc05";
        $connexion->query($sql4);
        $connexion->bind(':idc05', $cod3[0]->idc05);
        $cod4 = $connexion->resultset();
        $Campos1 .= "" . ($cod4[0]->nombre) . ";";
    }

    if($cod3[0]->idc06 <> ""){
        $sql4 = "SELECT nombre FROM tbl_potencial_formularios_campos WHERE id = :idc06";
        $connexion->query($sql4);
        $connexion->bind(':idc06', $cod3[0]->idc06);
        $cod4 = $connexion->resultset();
        $Campos1 .= "".($cod4[0]->nombre).";";
    }
    
    if($cod3[0]->idc07 <> ""){
        $sql4 = "SELECT nombre FROM tbl_potencial_formularios_campos WHERE id = :idc07";
        $connexion->query($sql4);
        $connexion->bind(':idc07', $cod3[0]->idc07);
        $cod4 = $connexion->resultset();
        $Campos1 .= "".($cod4[0]->nombre).";";
    }
    
    if($cod3[0]->idc08 <> ""){
        $sql4 = "SELECT nombre FROM tbl_potencial_formularios_campos WHERE id = :idc08";
        $connexion->query($sql4);
        $connexion->bind(':idc08', $cod3[0]->idc08);
        $cod4 = $connexion->resultset();
        $Campos1 .= "".($cod4[0]->nombre).";";
    }
    
    if($cod3[0]->idc09 <> ""){
        $sql4 = "SELECT nombre FROM tbl_potencial_formularios_campos WHERE id = :idc09";
        $connexion->query($sql4);
        $connexion->bind(':idc09', $cod3[0]->idc09);
        $cod4 = $connexion->resultset();
        $Campos1 .= "".($cod4[0]->nombre).";";
    }
    
    if($cod3[0]->idc10 <> ""){
        $sql4 = "SELECT nombre FROM tbl_potencial_formularios_campos WHERE id = :idc10";
        $connexion->query($sql4);
        $connexion->bind(':idc10', $cod3[0]->idc10);
        $cod4 = $connexion->resultset();
        $Campos1 .= "".($cod4[0]->nombre).";";
    }
    
    if($cod3[0]->idc11 <> ""){
        $sql4 = "SELECT nombre FROM tbl_potencial_formularios_campos WHERE id = :idc11";
        $connexion->query($sql4);
        $connexion->bind(':idc11', $cod3[0]->idc11);
        $cod4 = $connexion->resultset();
        $Campos1 .= "".($cod4[0]->nombre).";";
    }

    if($cod3[0]->idc12<>""){
        $sql4 = " select	h.nombre	from tbl_potencial_formularios_campos h where id=:idc12";
        $connexion->query($sql4);
        $connexion->bind(':idc12', $cod3[0]->idc12);
        $cod4 = $connexion->resultset();
        $Campos1.="".($cod4[0]->nombre).";";


    }

    if($cod3[0]->idc13<>""){
        $sql4 = " select	h.nombre	from tbl_potencial_formularios_campos h where id=:idc13";
        $connexion->query($sql4);
        $connexion->bind(':idc13', $cod3[0]->idc13);
        $cod4 = $connexion->resultset();
        $Campos1.="".($cod4[0]->nombre).";";


    }

    if($cod3[0]->idc14<>""){
        $sql4 = " select	h.nombre	from tbl_potencial_formularios_campos h where id=:idc14";
        $connexion->query($sql4);
        $connexion->bind(':idc14', $cod3[0]->idc14);
        $cod4 = $connexion->resultset();
        $Campos1.="".($cod4[0]->nombre).";";


    }

    if($cod3[0]->idc15<>""){
        $sql4 = " select	h.nombre	from tbl_potencial_formularios_campos h where id=:idc15";
        $connexion->query($sql4);
        $connexion->bind(':idc15', $cod3[0]->idc15);
        $cod4 = $connexion->resultset();
        $Campos1.="".($cod4[0]->nombre).";";


    }

    if($cod3[0]->idc16<>""){
        $sql4 = " select	h.nombre	from tbl_potencial_formularios_campos h where id=:idc16";
        $connexion->query($sql4);
        $connexion->bind(':idc16', $cod3[0]->idc16);
        $cod4 = $connexion->resultset();
        $Campos1.="".($cod4[0]->nombre).";";


    }

    if($cod3[0]->idc17<>""){
        $sql4 = " select	h.nombre	from tbl_potencial_formularios_campos h where id=:idc17";
        $connexion->query($sql4);
        $connexion->bind(':idc17', $cod3[0]->idc17);
        $cod4 = $connexion->resultset();
        $Campos1.="".($cod4[0]->nombre).";";


    }

    if($cod3[0]->idc18<>""){
        $sql4 = " select	h.nombre	from tbl_potencial_formularios_campos h where id=:idc18";
        $connexion->query($sql4);
        $connexion->bind(':idc18', $cod3[0]->idc18);
        $cod4 = $connexion->resultset();
        $Campos1.="".($cod4[0]->nombre).";";
    }
    
    if($cod3[0]->idc19<>""){
        $sql4 = " select	h.nombre	from tbl_potencial_formularios_campos h where id=:idc19";
        $connexion->query($sql4);
        $connexion->bind(':idc19', $cod3[0]->idc19);
        $cod4 = $connexion->resultset();
        $Campos1.="".($cod4[0]->nombre).";";
    }
    
    if($cod3[0]->idc20<>""){
        $sql4 = " select	h.nombre	from tbl_potencial_formularios_campos h where id=:idc20";
        $connexion->query($sql4);
        $connexion->bind(':idc20', $cod3[0]->idc20);
        $cod4 = $connexion->resultset();
        $Campos1.="".($cod4[0]->nombre).";";
    }
    
    if($cod3[0]->idc21<>""){
        $sql4 = " select	h.nombre	from tbl_potencial_formularios_campos h where id=:idc21";
        $connexion->query($sql4);
        $connexion->bind(':idc21', $cod3[0]->idc21);
        $cod4 = $connexion->resultset();
        $Campos1.="".($cod4[0]->nombre).";";
    }
    
    if($cod3[0]->idc22<>""){
        $sql4 = " select	h.nombre	from tbl_potencial_formularios_campos h where id=:idc22";
        $connexion->query($sql4);
        $connexion->bind(':idc22', $cod3[0]->idc22);
        $cod4 = $connexion->resultset();
        $Campos1.="".($cod4[0]->nombre).";";
    }
    
    if($cod3[0]->idc23<>""){
        $sql4 = " select	h.nombre	from tbl_potencial_formularios_campos h where id=:idc23";
        $connexion->query($sql4);
        $connexion->bind(':idc23', $cod3[0]->idc23);
        $cod4 = $connexion->resultset();
        $Campos1.="".($cod4[0]->nombre).";";
    }

    if($cod3[0]->idc24<>""){
        $sql4 = " select	h.nombre	from tbl_potencial_formularios_campos h where id=:idc24";
        $connexion->query($sql4);
        $connexion->bind(':idc24', $cod3[0]->idc24);
        $cod4 = $connexion->resultset();
        $Campos1.="".($cod4[0]->nombre).";";
    }
    
    if($cod3[0]->idc25<>""){
        $sql4 = " select	h.nombre	from tbl_potencial_formularios_campos h where id=:idc25";
        $connexion->query($sql4);
        $connexion->bind(':idc25', $cod3[0]->idc25);
        $cod4 = $connexion->resultset();
        $Campos1.="".($cod4[0]->nombre).";";
    }
    
    if($cod3[0]->idc26<>""){
        $sql4 = " select	h.nombre	from tbl_potencial_formularios_campos h where id=:idc26";
        $connexion->query($sql4);
        $connexion->bind(':idc26', $cod3[0]->idc26);
        $cod4 = $connexion->resultset();
        $Campos1.="".($cod4[0]->nombre).";";
    }

    if($division<>""){
        $queryDivision=" and (u.division)='$division'  ";
    } else {
        $queryDivision="";
    }

    $sql = " SELECT
                    h.*,
                    u.nombre_completo AS nombre_col,
                    u.division AS division_col
                FROM tbl_potencial_formularios_respuestas h
                LEFT JOIN tbl_usuario u ON h.rut = u.rut
                WHERE h.id_form = '".$idf."'
                $queryDivision
                GROUP BY h.rut; ";
    
    $connexion->query($sql);
    $connexion->bind(':id_form', $idf);
    $cod = $connexion->resultset();
    foreach ($cod as $unico){
    
        $Datos.="".$unico->rut.";".$unico->nombre_col.";".$unico->division_col.";";
    
        if ($cod3[0]->idc01 <> "") {
            $sql4 = "select h.nombre from tbl_potencial_formularios_campos h where id='".$cod3[0]->idc01."'";
            $connexion->query($sql4);
            $cod4 = $connexion->resultset();
            $Campos .= "".($cod4[0]->nombre).";";

            $sql2 = "select h.idc01 as dato from tbl_potencial_formularios_respuestas h where h.id_form='$idf' and h.rut='". $unico->rut."'";
            $connexion->query($sql2);
            $cod2 = $connexion->resultset();
            $Datos .= "".PotencialLimpiaTextosTrim($cod2[0]->dato).";";
        }

        if ($cod3[0]->idc02 <> "") {
            $sql4 = "select h.nombre from tbl_potencial_formularios_campos h where id='".$cod3[0]->idc02."'";
            $connexion->query($sql4);
            $cod4 = $connexion->resultset();
            $Campos .= "".($cod4[0]->nombre).";";

            $sql2 = "select h.idc02 as dato from tbl_potencial_formularios_respuestas h where h.id_form='$idf' and h.rut='". $unico->rut."'";
            $connexion->query($sql2);
            $cod2 = $connexion->resultset();
            $Datos .= "".PotencialLimpiaTextosTrim($cod2[0]->dato).";";
        }

        if ($cod3[0]->idc03 <> "") {
            $sql4 = "select h.nombre from tbl_potencial_formularios_campos h where id='".$cod3[0]->idc03."'";
            $connexion->query($sql4);
            $cod4 = $connexion->resultset();
            $Campos .= "".($cod4[0]->nombre).";";

            $sql2 = "select h.idc03 as dato from tbl_potencial_formularios_respuestas h where h.id_form='$idf' and h.rut='". $unico->rut."'";
            $connexion->query($sql2);
            $cod2 = $connexion->resultset();
            $Datos .= "".PotencialLimpiaTextosTrim($cod2[0]->dato).";";
        }

        if ($cod3[0]->idc04 <> "") {
            $sql4 = "SELECT h.nombre FROM tbl_potencial_formularios_campos h WHERE id='" . $cod3[0]->idc04 . "'";
            $connexion->query($sql4);
            $cod4 = $connexion->resultset();
            $Campos .= "".($cod4[0]->nombre).";";
            
            $sql2 = "SELECT h.idc04 AS dato FROM tbl_potencial_formularios_respuestas h WHERE h.id_form='$idf' AND h.rut='". $unico->rut."'";
            $connexion->query($sql2);
            $cod2 = $connexion->resultset();
            $Datos .= "".PotencialLimpiaTextosTrim($cod2[0]->dato).";";
        }
        
        if ($cod3[0]->idc05 <> "") {
            $sql4 = "SELECT h.nombre FROM tbl_potencial_formularios_campos h WHERE id='" . $cod3[0]->idc05 . "'";
            $connexion->query($sql4);
            $cod4 = $connexion->resultset();
            $Campos .= "".($cod4[0]->nombre).";";
            
            $sql2 = "SELECT h.idc05 AS dato FROM tbl_potencial_formularios_respuestas h WHERE h.id_form='$idf' AND h.rut='". $unico->rut."'";
            $connexion->query($sql2);
            $cod2 = $connexion->resultset();
            $Datos .= "".PotencialLimpiaTextosTrim($cod2[0]->dato).";";
        }
        
        if ($cod3[0]->idc06 <> "") {
            $sql4 = "SELECT h.nombre FROM tbl_potencial_formularios_campos h WHERE id='" . $cod3[0]->idc06 . "'";
            $connexion->query($sql4);
            $cod4 = $connexion->resultset();
            $Campos .= "".($cod4[0]->nombre).";";
            
            $sql2 = "SELECT h.idc06 AS dato FROM tbl_potencial_formularios_respuestas h WHERE h.id_form='$idf' AND h.rut='". $unico->rut."'";
            $connexion->query($sql2);
            $cod2 = $connexion->resultset();
            $Datos .= "".PotencialLimpiaTextosTrim($cod2[0]->dato).";";
        }

        if($cod3[0]->idc07<>""){
            $sql4 = " select	h.nombre	from tbl_potencial_formularios_campos h where id='".$cod3[0]->idc07."'";
            $connexion->query($sql4);
            $cod4 = $connexion->resultset();
            $Campos.="".($cod4[0]->nombre).";";

            $sql2 = " select	h.idc07 as dato  from tbl_potencial_formularios_respuestas h where h.id_form='$idf' and h.rut='". $unico->rut."'";
            $connexion->query($sql2);
            $cod2 = $connexion->resultset();
            $Datos.="".PotencialLimpiaTextosTrim($cod2[0]->dato).";";
        }

        if($cod3[0]->idc08<>""){
            $sql4 = " select	h.nombre	from tbl_potencial_formularios_campos h where id='".$cod3[0]->idc08."'";
            $connexion->query($sql4);
            $cod4 = $connexion->resultset();
            $Campos.="".($cod4[0]->nombre).";";

            $sql2 = " select	h.idc08 as dato  from tbl_potencial_formularios_respuestas h where h.id_form='$idf' and h.rut='". $unico->rut."'";
            $connexion->query($sql2);
            $cod2 = $connexion->resultset();
            $Datos.="".PotencialLimpiaTextosTrim($cod2[0]->dato).";";
        }

        if($cod3[0]->idc09<>""){
            $sql4 = " select	h.nombre	from tbl_potencial_formularios_campos h where id='".$cod3[0]->idc09."'";
            $connexion->query($sql4);
            $cod4 = $connexion->resultset();
            $Campos.="".($cod4[0]->nombre).";";

            $sql2 = " select	h.idc09 as dato  from tbl_potencial_formularios_respuestas h where h.id_form='$idf' and h.rut='". $unico->rut."'";
            $connexion->query($sql2);
            $cod2 = $connexion->resultset();
            $Datos.="".PotencialLimpiaTextosTrim($cod2[0]->dato).";";
        }

        if($cod3[0]->idc10<>""){
            $sql4 = " select	h.nombre	from tbl_potencial_formularios_campos h where id='".$cod3[0]->idc10."'";
            $connexion->query($sql4);
            $cod4 = $connexion->resultset();
            $Campos.="".($cod4[0]->nombre).";";

            $sql2 = " select	h.idc10 as dato  from tbl_potencial_formularios_respuestas h where h.id_form='$idf' and h.rut='". $unico->rut."'";
            $connexion->query($sql2);
            $cod2 = $connexion->resultset();
            $Datos.="".PotencialLimpiaTextosTrim($cod2[0]->dato).";";
        }

        if($cod3[0]->idc11<>""){
            $sql4 = " select	h.nombre	from tbl_potencial_formularios_campos h where id='".$cod3[0]->idc11."'";
            $connexion->query($sql4);
            $cod4 = $connexion->resultset();
            $Campos.="".($cod4[0]->nombre).";";

            $sql2 = " select	h.idc11 as dato  from tbl_potencial_formularios_respuestas h where h.id_form='$idf' and h.rut='". $unico->rut."'";
            $connexion->query($sql2);
            $cod2 = $connexion->resultset();
            $Datos.="".PotencialLimpiaTextosTrim($cod2[0]->dato).";";
        }

        if($cod3[0]->idc12<>""){
            $sql4 = " select	h.nombre	from tbl_potencial_formularios_campos h where id='".$cod3[0]->idc12."'";
            $connexion->query($sql4);
            $cod4 = $connexion->resultset();
            $Campos.="".($cod4[0]->nombre).";";

            $sql2 = " select	h.idc12 as dato  from tbl_potencial_formularios_respuestas h where h.id_form='$idf' and h.rut='". $unico->rut."'";
            $connexion->query($sql2);
            $cod2 = $connexion->resultset();
            $Datos.="".PotencialLimpiaTextosTrim($cod2[0]->dato).";";
        }

        if($cod3[0]->idc13<>""){
            $sql4 = " select	h.nombre	from tbl_potencial_formularios_campos h where id='".$cod3[0]->idc13."'";
            $connexion->query($sql4);
            $cod4 = $connexion->resultset();
            $Campos.="".($cod4[0]->nombre).";";

            $sql2 = " select	h.idc13 as dato  from tbl_potencial_formularios_respuestas h where h.id_form='$idf' and h.rut='". $unico->rut."'";
            $connexion->query($sql2);
            $cod2 = $connexion->resultset();
            $Datos.="".PotencialLimpiaTextosTrim($cod2[0]->dato).";";
        }


        if($cod3[0]->idc14<>""){
            $sql4 = " select	h.nombre	from tbl_potencial_formularios_campos h where id='".$cod3[0]->idc14."'";
            $connexion->query($sql4);
            $cod4 = $connexion->resultset();
            $Campos.="".($cod4[0]->nombre).";";

            $sql2 = " select	h.idc14 as dato  from tbl_potencial_formularios_respuestas h where h.id_form='$idf' and h.rut='". $unico->rut."'";
            $connexion->query($sql2);
            $cod2 = $connexion->resultset();
            $Datos.="".PotencialLimpiaTextosTrim($cod2[0]->dato).";";
        }

        if($cod3[0]->idc15<>""){
            $sql4 = " select	h.nombre	from tbl_potencial_formularios_campos h where id='".$cod3[0]->idc15."'";
            $connexion->query($sql4);
            $cod4 = $connexion->resultset();
            $Campos.="".($cod4[0]->nombre).";";

            $sql2 = " select	h.idc15 as dato  from tbl_potencial_formularios_respuestas h where h.id_form='$idf' and h.rut='". $unico->rut."'";
            $connexion->query($sql2);
            $cod2 = $connexion->resultset();
            $Datos.="".PotencialLimpiaTextosTrim($cod2[0]->dato).";";
        }

        if($cod3[0]->idc16<>""){
            $sql4 = " select	h.nombre	from tbl_potencial_formularios_campos h where id='".$cod3[0]->idc16."'";
            $connexion->query($sql4);
            $cod4 = $connexion->resultset();
            $Campos.="".($cod4[0]->nombre).";";

            $sql2 = " select	h.idc16 as dato  from tbl_potencial_formularios_respuestas h where h.id_form='$idf' and h.rut='". $unico->rut."'";
            $connexion->query($sql2);
            $cod2 = $connexion->resultset();
            $Datos.="".PotencialLimpiaTextosTrim($cod2[0]->dato).";";
        }

        if($cod3[0]->idc17<>""){
            $sql4 = " select	h.nombre	from tbl_potencial_formularios_campos h where id='".$cod3[0]->idc17."'";
            $connexion->query($sql4);
            $cod4 = $connexion->resultset();
            $Campos.="".($cod4[0]->nombre).";";

            $sql2 = " select	h.idc17 as dato  from tbl_potencial_formularios_respuestas h where h.id_form='$idf' and h.rut='". $unico->rut."'";
            $connexion->query($sql2);
            $cod2 = $connexion->resultset();
            $Datos.="".PotencialLimpiaTextosTrim($cod2[0]->dato).";";
        }

        if($cod3[0]->idc18<>""){
            $sql4 = " select	h.nombre	from tbl_potencial_formularios_campos h where id='".$cod3[0]->idc18."'";
            $connexion->query($sql4);
            $cod4 = $connexion->resultset();
            $Campos.="".($cod4[0]->nombre).";";

            $sql2 = " select	h.idc18 as dato  from tbl_potencial_formularios_respuestas h where h.id_form='$idf' and h.rut='". $unico->rut."'";
            $connexion->query($sql2);
            $cod2 = $connexion->resultset();
            $Datos.="".PotencialLimpiaTextosTrim($cod2[0]->dato).";";
        }


        if($cod3[0]->idc19<>""){
            $sql4 = " select	h.nombre	from tbl_potencial_formularios_campos h where id='".$cod3[0]->idc19."'";
            $connexion->query($sql4);
            $cod4 = $connexion->resultset();
            $Campos.="".($cod4[0]->nombre).";";

            $sql2 = " select	h.idc19 as dato  from tbl_potencial_formularios_respuestas h where h.id_form='$idf' and h.rut='". $unico->rut."'";
            $connexion->query($sql2);
            $cod2 = $connexion->resultset();
            $Datos.="".PotencialLimpiaTextosTrim($cod2[0]->dato).";";
        }

        if($cod3[0]->idc20<>""){
            $sql4 = " select	h.nombre	from tbl_potencial_formularios_campos h where id='".$cod3[0]->idc20."'";
            $connexion->query($sql4);
            $cod4 = $connexion->resultset();
            $Campos.="".($cod4[0]->nombre).";";

            $sql2 = " select	h.idc20 as dato  from tbl_potencial_formularios_respuestas h where h.id_form='$idf' and h.rut='". $unico->rut."'";
            $connexion->query($sql2);
            $cod2 = $connexion->resultset();
            $Datos.="".PotencialLimpiaTextosTrim($cod2[0]->dato).";";
        }

        if($cod3[0]->idc21<>""){
            $sql4 = " select	h.nombre	from tbl_potencial_formularios_campos h where id='".$cod3[0]->idc21."'";
            $connexion->query($sql4);
            $cod4 = $connexion->resultset();
            $Campos.="".($cod4[0]->nombre).";";

            $sql2 = " select	h.idc21 as dato  from tbl_potencial_formularios_respuestas h where h.id_form='$idf' and h.rut='". $unico->rut."'";
            $connexion->query($sql2);
            $cod2 = $connexion->resultset();
            $Datos.="".PotencialLimpiaTextosTrim($cod2[0]->dato).";";
        }

        if($cod3[0]->idc22<>""){
            $sql4 = " select	h.nombre	from tbl_potencial_formularios_campos h where id='".$cod3[0]->idc22."'";
            $connexion->query($sql4);
            $cod4 = $connexion->resultset();
            $Campos.="".($cod4[0]->nombre).";";

            $sql2 = " select	h.idc22 as dato  from tbl_potencial_formularios_respuestas h where h.id_form='$idf' and h.rut='". $unico->rut."'";
            $connexion->query($sql2);
            $cod2 = $connexion->resultset();
            $Datos.="".PotencialLimpiaTextosTrim($cod2[0]->dato).";";
        }

        if($cod3[0]->idc23<>""){
            $sql4 = " select	h.nombre	from tbl_potencial_formularios_campos h where id='".$cod3[0]->idc23."'";
            $connexion->query($sql4);
            $cod4 = $connexion->resultset();
            $Campos.="".($cod4[0]->nombre).";";

            $sql2 = " select	h.idc23 as dato  from tbl_potencial_formularios_respuestas h where h.id_form='$idf' and h.rut='". $unico->rut."'";
            $connexion->query($sql2);
            $cod2 = $connexion->resultset();
            $Datos.="".PotencialLimpiaTextosTrim($cod2[0]->dato).";";
        }
        if($cod3[0]->idc24<>""){
            $sql4 = " select	h.nombre	from tbl_potencial_formularios_campos h where id='".$cod3[0]->idc24."'";
            $connexion->query($sql4);
            $cod4 = $connexion->resultset();
            $Campos.="".($cod4[0]->nombre).";";

            $sql2 = " select	h.idc24 as dato  from tbl_potencial_formularios_respuestas h where h.id_form='$idf' and h.rut='". $unico->rut."'";
            $connexion->query($sql2);
            $cod2 = $connexion->resultset();
            $Datos.="".PotencialLimpiaTextosTrim($cod2[0]->dato).";";
        }
        if($cod3[0]->idc25<>""){
            $sql4 = " select	h.nombre	from tbl_potencial_formularios_campos h where id='".$cod3[0]->idc25."'";
            $connexion->query($sql4);
            $cod4 = $connexion->resultset();
            $Campos.="".($cod4[0]->nombre).";";

            $sql2 = " select	h.idc25 as dato  from tbl_potencial_formularios_respuestas h where h.id_form='$idf' and h.rut='". $unico->rut."'";
            $connexion->query($sql2);
            $cod2 = $connexion->resultset();
            $Datos.="".PotencialLimpiaTextosTrim($cod2[0]->dato).";";
        }
        $Datos.="\r\n";

    }

    $arreglo[0]=$Campos1;
    $arreglo[1]=$Datos;

    return $arreglo;
}

function Datos_Idiomas(){
    $connexion = new DatabasePDO();
    $sql = "SELECT idioma FROM tbl_da_idiomas";
    $connexion->query($sql);
    $datos = $connexion->resultset();
    return $datos;
}

function Datos_Sucursales(){
    $connexion = new DatabasePDO();
    $sql = "SELECT sucursal FROM tbl_da_sucursales";
    $connexion->query($sql);
    $datos = $connexion->resultset();
    return $datos;
}

function Datos_Anos(){
    $connexion = new DatabasePDO();
    $sql = "SELECT ano_estudio FROM tbl_da_anos_estudio";
    $connexion->query($sql);
    $datos = $connexion->resultset();
    return $datos;
}

function Datos_Paises(){
    $connexion = new DatabasePDO();
    $sql = "SELECT pais FROM tbl_da_pais";
    $connexion->query($sql);
    $datos = $connexion->resultset();
    return $datos;
}

function Datos_Academico_Del($idl) {
    $connexion = new DatabasePDO();
    $idl = Decodear3($idl);
    $sql = "DELETE FROM tbl_da_formularios_respuestas WHERE id=:idl";
    $connexion->query($sql);
    $connexion->bind(':idl', $idl);
    $cod = $connexion->listObjects();
    return $cod;
}

function Datos_Academicos_Save_Beneficio_Data($id_form,$id_beneficio,$idc01,$idc02,$idc03,$idc04,$idc05,$idc06,$idc07,$idc08,$idc09,$idc10,$idc11,$idc12,$idc13,$idc14,$idc15,
    $idc16,$idc17,$idc18,$idc19,$idc20,$idc21,$idc22,$idc23,$idc24,$idc25,$id_empresa,$fecha,$hora,$rut,$nombre,$cargo,$email,$division,$area,$cui,$glosa_cui,$departamento,$zona,
    $region,$rut_jefe,$archivo1,$archivo2,$archivo3,$archivo4,$archivo5,$idl) {
    $conexion = new DatabasePDO();

    $fecha = date("Y-m-d");
    $hora = date("H:i:s");

    $documento = explode(".", $archivo1);

    if ($id_form == "2") {
        if ($idc01 == "" and $idc02 == "") {
            echo "<script>alert('Debes Indicar por lo menos un Estudio');window.history.back();</script>";
            exit();
        }

        if ($idc04 == "" and $idc05 == "") {
            echo "<script>alert('Debes Indicar por lo menos una Institucion');window.history.back();</script>";
            exit();
        }
    }

    if ($idl > 0) {
            if ($idc05 <> "") {
                $qidc05 = " idc05='$idc05',";
            }
            if ($idc06 <> "") {
                $qidc06 = " idc06='$idc06',";
            }

            if ($idc08 <> "") {
                $qidc08 = " idc08='$idc08',";
            }

            if ($documento[1] <> "") {
                $Jqarchivo1 = ", archivo1='" . $documento[0] . "." . $documento[1] . "'";
            }

            $sql = "
                UPDATE tbl_da_formularios_respuestas SET 
                    idc01='$idc01',
                    idc02='$idc02',
                    idc03='$idc03',
                    idc04='$idc04',
                    $qidc05
                    $qidc06
                    idc07='$idc07',
                    $qidc08
                    idc09='$idc09',
                    idc10='$idc10',
                    idc11='$idc11',
                    idc12='$idc12',
                    idc13='$idc13',
                    idc14='$idc14',
                    idc15='$idc15',
                    idc16='$idc16',
                    idc17 = '$idc17',
                    idc18 = '$idc18',
                    idc19 = '$idc19',
                    idc20 = '$idc20'
                    $Jqarchivo1
                    WHERE id = '$idl'";
                    $connexion->query($sql);
                    $connexion->execute();

        } else {
            $archivo_certificado = "";
            if ($documento[1] != "") {
                $archivo_certificado = "" . $documento[0] . "." . $documento[1] . "";
            }

            $sql = "INSERT INTO tbl_da_formularios_respuestas (
                id_form,id_beneficio,idc01,idc02,idc03,idc04,idc05,idc06,idc07,idc08,idc09,idc10,idc11,idc12,idc13,idc14,idc15,idc16,idc17,idc18,idc19,idc20,idc21,idc22,
                idc23,idc24,idc25,id_empresa,fecha,hora,rut,nombre,cargo,email,division,area,cui,glosa_cui,departamento,zona,region,rut_jefe,archivo1,archivo2,archivo3,archivo4,archivo5)
                VALUES (
                    
                :id_form,:id_beneficio,:idc01,:idc02,:idc03,:idc04,:idc05,:idc06,:idc07,:idc08,:idc09,:idc10,:idc11,:idc12,:idc13,:idc14,:idc15,:idc16,:idc17,
                :idc18,:idc19,:idc20,:idc21,:idc22,:idc23,:idc24,:idc25,:id_empresa,:fecha,:hora,:rut,:nombre,:cargo,:email,:division,:area,:cui,:glosa_cui,:departamento,
                :zona,:region,:rut_jefe,:archivo_certificado,:archivo2,:archivo3,:archivo4,:archivo5)";
            
                $connexion->query($sql);
                $connexion->bind(':id_form', $id_form);
                $connexion->bind(':id_beneficio', $id_beneficio);
                $connexion->bind(':idc01', $idc01);
                $connexion->bind(':idc02', $idc02);
                $connexion->bind(':idc03', $idc03);
                $connexion->bind(':idc04', $idc04);
                $connexion->bind(':idc05', $idc05);
                $connexion->bind(':idc06', $idc06);
                $connexion->bind(':idc07', $idc07);
                $connexion->bind(':idc08', $idc08 );
                $connexion->bind(':idc09', $idc09 );
                $connexion->bind(':idc10', $idc10 );
                $connexion->bind(':idc11', $idc11 );
                $connexion->bind(':idc12', $idc12 );
                $connexion->bind(':idc13', $idc13 );
                $connexion->bind(':idc14', $idc14 );
                $connexion->bind(':idc15', $idc15 );
                $connexion->bind(':idc16', $idc16 );
                $connexion->bind(':idc17', $idc17 );
                $connexion->bind(':idc18', $idc18 );
                $connexion->bind(':idc19', $idc19 );
                $connexion->bind(':idc20', $idc20 );
                $connexion->bind(':idc21', $idc21 );
                $connexion->bind(':idc22', $idc22 );
                $connexion->bind(':idc23', $idc23 );
                $connexion->bind(':idc24', $idc24 );
                $connexion->bind(':idc25', $idc25 );
                $connexion->bind(':id_empresa', $id_empresa );
                $connexion->bind(':fecha', $fecha );
                $connexion->bind(':hora', $hora );
                $connexion->bind(':rut', $rut );
                $connexion->bind(':nombre', $nombre );
                $connexion->bind(':cargo', $cargo );
                $connexion->bind(':email', $email );
                $connexion->bind(':division', $division );
                $connexion->bind(':area', $area );
                $connexion->bind(':cui', $cui );
                $connexion->bind(':glosa_cui', $glosa_cui );
                $connexion->bind(':departamento', $departamento );
                $connexion->bind(':zona', $zona );
                $connexion->bind(':region', $region );
                $connexion->bind(':rut_jefe', $rut_jefe );
                $connexion->bind(':archivo_certificado', $archivo_certificado );
                $connexion->bind(':archivo2', $archivo2 );
                $connexion->bind(':archivo3', $archivo3 );
                $connexion->bind(':archivo4', $archivo4 );
                $connexion->bind(':archivo5', $archivo5 );
                $connexion->execute();
            }
}

function Datos_Academicos_tbl_formularios_respuestas_data($id_form, $rut_col){
    $connexion = new DatabasePDO();
    $sql="SELECT * FROM tbl_da_formularios_respuestas WHERE id_form=:id_form AND rut=:rut_col";
    $connexion->query($sql);
    $connexion->bind(':id_form', $id_form);
    $connexion->bind(':rut_col', $rut_col);
    $cod = $connexion->resultset();
    return $cod;
}

function Potencial_Divisiones(){
    $connexion = new DatabasePDO();
    $sql = "SELECT h.division FROM tbl_usuario AS h WHERE h.division <> 'Gerencia General' AND h.division<>'' GROUP BY h.division";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}

function Datos_Academicos_Respuesta_Id_99($id){
    $connexion = new DatabasePDO();
    $sql = "UPDATE tbl_da_formularios_respuestas SET id_empresa='99' WHERE id=:id";
    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $connexion->execute();
}

function Datos_Academicos_Tipo_Field_WithValue_data($idl,$name_field) {
    $connexion = new DatabasePDO();
    $sql= "SELECT $name_field AS data FROM tbl_da_formularios_respuestas WHERE id=:idl LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':idl', $idl);
    $cod = $connexion->resultset();
    return $cod[0]->data;
}

function Datos_Academicos_Trae_Formulario_Campos_data($id_campo, $id_empresa) {
    $connexion = new DatabasePDO();

    $sql = "SELECT h.* FROM tbl_da_formularios_campos h WHERE h.id_empresa = :id_empresa AND h.id = :id_campo";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_campo', $id_campo);
    $cod = $connexion->resultset();
    return $cod;
}

function Datos_Academicos_Trae_Carreras_data() {
    $connexion = new DatabasePDO();

    $sql = "SELECT h.* FROM tbl_da_carreras h ORDER BY h.carreras";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}

function Datos_Academicos_Trae_instituciones_data() {
    $connexion = new DatabasePDO();

    $sql = "SELECT h.* FROM tbl_da_instituciones h ORDER BY h.institucion";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}

function Datos_Academicos_Check_respuestas($rut, $idf, $id_empresa) {
    $connexion = new DatabasePDO();

    $sql = "SELECT COUNT(id) as cuenta FROM tbl_da_formularios_respuestas WHERE rut = :rut AND id_form = :idf";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':idf', $idf);
    $cod = $connexion->resultset();
    return $cod[0]->cuenta;
}

function Datos_Academicos_Trae_Formulario_data($id_form, $id_empresa) {
    $connexion = new DatabasePDO();

    $sql = "SELECT h.* FROM tbl_da_formularios h WHERE h.id_empresa = :id_empresa AND h.id = :id_form";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_form', $id_form);
    $cod = $connexion->resultset();
    return $cod;
}

function Datos_Academicos_Id_Full_data($idl) {
    $connexion = new DatabasePDO();

    $sql = "SELECT * FROM tbl_da_formularios_respuestas WHERE id = :idl LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':idl', $idl);
    $cod = $connexion->resultset();
    return $cod;
}

function Datos_Academicos_CheckIdRespuestas($idf,$rut_col){

    $connexion = new DatabasePDO();
    $sql="select id from tbl_da_formularios_respuestas where id_form=:idf and rut=:rut_col";
    $connexion->query($sql);
    $connexion->bind(':idf', $idf);
    $connexion->bind(':rut_col', $rut_col);
    $cod = $connexion->resultset();
    return ($cod[0]->id);
}

function Datos_Academicos_Busca_Forms_groupbydimension($id_empresa){

    $connexion = new DatabasePDO();
    $sql="select * from tbl_da_formularios where id_empresa=:id_empresa group by descripcion order by id ASC";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function Datos_Academicos_Busca_Forms_Dimension($id_empresa,$dimension){

    $connexion = new DatabasePDO();
    $sql="select * from tbl_da_formularios where descripcion=:dimension order by id ASC";
    $connexion->query($sql);
    $connexion->bind(':dimension', $dimension);
    $cod = $connexion->resultset();
    return $cod;
}

function PotencialBuscaDisponibilidad($rut_col) {
    
    $connexion = new DatabasePDO();

//echo "<h3>potencial BUsca Disponibilidad $rut_col</h3>";

$sql1	=	"select h.idc05 as dentro_chile from tbl_potencial_formularios_respuestas h where h.id_form ='6' and h.rut=:rut_col";
$connexion->query($sql1);
$connexion->bind(':rut_col', $rut_col);
$cod1 = $connexion->resultset();

$sql2	=	"select h.idc06 as fuera_chile from tbl_potencial_formularios_respuestas h where h.id_form ='6' and h.rut=:rut_col";
$connexion->query($sql2);
$connexion->bind(':rut_col', $rut_col);
$cod2 = $connexion->resultset();

$sql3	=	"select h.idc08 as viajar from tbl_potencial_formularios_respuestas h where h.id_form ='6' and h.rut=:rut_col";
$connexion->query($sql3);
$connexion->bind(':rut_col', $rut_col);
$cod3 = $connexion->resultset();

$sql4	=	"select h.idc26 as cargo_critico from tbl_potencial_cargos_criticos h where h.rut=:rut_col";
$connexion->query($sql4);
$connexion->bind(':rut_col', $rut_col);
$cod4 = $connexion->resultset();
    
    if($cod1[0]->dentro_chile<>""){ 
        $respuesta1 = "<li class='d-flex ai-c py-4 fw-b c-brand bbw-1'>
                            <div class='bullet_lightBlue'></div> 
                            Disponibilidad para traslados dentro de Chile: <span class='c-brand-weight'> ".$cod1[0]->dentro_chile."</span>
                        </li>";
    } else {
        $respuesta1 = "<li class='d-flex ai-c py-4 fw-b c-brand bbw-1'>
                            <div class='bullet_lightBlue'></div> 
                            Disponibilidad para traslados dentro de Chile: <span class='c-brand-weight'> Sin informaci&oacute;n</span>
                        </li>";
    }
    
    if($cod2[0]->fuera_chile<>"")	{ $respuesta2="<li class='d-flex ai-c py-4 fw-b c-brand bbw-1'>
        <div class='bullet_lightBlue'></div> 
              Disponibilidad para traslados fuera de Chile: <span class='c-brand-weight'> ".$cod2[0]->fuera_chile."</span>
      </li>		";}
    else {
    $respuesta2="<li class='d-flex ai-c py-4 fw-b c-brand bbw-1'>
            <div class='bullet_lightBlue'></div> 
                Disponibilidad para traslados fuera de Chile: <span class='c-brand-weight'> Sin informaci&oacute;n</span>
        </li>	";}

    if($cod3[0]->viajar<>"")			{ $respuesta3="<li class='d-flex ai-c py-4 fw-b c-brand bbw-1'>
            <div class='bullet_lightBlue'></div> 
                Disponibilidad para viajar: <span class='c-brand-weight'> ".$cod3[0]->viajar."</span>
        </li>		";}
    else {
    $respuesta3="<li class='d-flex ai-c py-4 fw-b c-brand bbw-1'>
            <div class='bullet_lightBlue'></div> 
                Disponibilidad para viajar: <span class='c-brand-weight'> Sin informaci&oacute;n</span>
        </li> ";}
        if($cod4[0]->cargo_critico<>"")			{ $respuesta4="<li class='d-flex ai-c py-4 fw-b c-brand bbw-1'>
            <div class='bullet_lightBlue'></div> 
                  Cargo cr&iacute;tico: <span class='c-brand-weight'> ".$cod4[0]->cargo_critico."</span>
          </li>		";}
    else {
    $respuesta4="<li class='d-flex ai-c py-4 fw-b c-brand bbw-1'>
                <div class='bullet_lightBlue'></div> 
                    Cargo cr&iacute;tico: <span class='c-brand-weight'> Sin informaci&oacute;n</span>
            </li> ";}     
        $arreglo[1]=$respuesta1;
        $arreglo[2]=$respuesta2;
        $arreglo[3]=$respuesta3;
        $arreglo[4]=$respuesta4;
    
        return $arreglo;
}

function Talento_del($id){
    $connexion = new DatabasePDO();
    $sql="	
        delete from tbl_potencial_sucesion where id=:id
    ";
    
    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $connexion->execute();
    
    return $connexion->rowCount();
    }
    
function Talento_Update_Sucesions_Map($rut_suceder, $rut_col, $tipo, $int){
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    
    if($int > 0){
        $sql = "
            INSERT INTO tbl_potencial_sucesion (rut_suceder, tipo, rut_col, rut, fecha, tipo_cambio)
            VALUES (:rut_suceder, :tipo, :rut_col, :user, :fecha, 'Interino')
        ";
    } else {
        $sql = "
            INSERT INTO tbl_potencial_sucesion (rut_suceder, tipo, rut_col, rut, fecha)
            VALUES (:rut_suceder, :tipo, :rut_col, :user, :fecha)
        ";
    }
    
    $connexion->query($sql);
    $connexion->bind(':rut_suceder', $rut_suceder);
    $connexion->bind(':tipo', $tipo);
    $connexion->bind(':rut_col', $rut_col);
    $connexion->bind(':user', $_SESSION["user_"]);
    $connexion->bind(':fecha', $fecha);
    $connexion->execute();
    
    $sql1 = "UPDATE tbl_potencial_sucesion SET cerrado = NULL WHERE rut_suceder = :rut_suceder";
    $connexion->query($sql1);
    $connexion->bind(':rut_suceder', $rut_suceder);
    $connexion->execute();
    
    return $connexion->rowCount();
    }

function Talento_Busca_Mapa_Interino($rut_suceder, $interino){
        $connexion = new DatabasePDO();
        $sql = "SELECT id FROM tbl_potencial_sucesion WHERE tipo_cambio=:interino AND rut_suceder=:rut_suceder LIMIT 1";
        $connexion->query($sql);
        $connexion->bind(':interino', $interino);
        $connexion->bind(':rut_suceder', $rut_suceder);
        $cod = $connexion->resultset();
        return $cod[0]->id;
    }
    
function Talento_Busca_Sucesions_map($rut_suceder, $tipo, $interino){
        $connexion = new DatabasePDO();
    
        if($interino>0){
            $QueryInterino=" AND h.tipo_cambio='Interino'";
        } else {
            $QueryInterino=" AND h.tipo_cambio IS NULL";
        }
    
        $sql = "SELECT h.* FROM tbl_potencial_sucesion h WHERE h.rut_suceder=:rut_suceder AND h.tipo=:tipo $QueryInterino ORDER BY h.id ASC";
        $connexion->query($sql);
        $connexion->bind(':rut_suceder', $rut_suceder);
        $connexion->bind(':tipo', $tipo);
        $cod = $connexion->resultset();
        return $cod;
    }
    

function Talento_Busca_Sucesions_Interino_map($rut_suceder){
        $connexion = new DatabasePDO();
        $sql="	
            SELECT h.*
            FROM tbl_potencial_sucesion h
            WHERE h.rut_suceder=:rut_suceder
            AND h.tipo_cambio<>''
            ORDER BY cargo_cambio DESC
            LIMIT 1";
        $connexion->query($sql);
        $connexion->bind(':rut_suceder', $rut_suceder);
        $cod = $connexion->resultset();
        return $cod;
    }
    
function Talento_Busca_Sucesions_map_init($rut_suceder){
        // Leave this function unchanged
    }
    
function Potencial_Fecha_Actualizacion_TblUsuario(){
        $connexion = new DatabasePDO();
        $sql="SELECT fecha FROM tbl_cron_sillas ORDER BY fecha DESC LIMIT 1";
        $connexion->query($sql);
        $cod = $connexion->resultset();
        return $cod[0]->fecha;
    }
    
function Potencial_Talento_lista_vista_divisiones($division){
        $connexion = new DatabasePDO();
        $sql="SELECT * FROM tbl_potencial_formularios_respuestas WHERE division=:division GROUP BY rut";
        $connexion->query($sql);
        $connexion->bind(':division', $division);
        $cod = $connexion->resultset();
        return $cod;
    }
    
function Talento_Divisiones_Options_data($id_empresa){
        $connexion = new DatabasePDO();
        $sql="SELECT division FROM tbl_usuario WHERE id_empresa=:id_empresa AND vigencia_descripcion='' AND division<>'' GROUP BY division ORDER BY division";
        $connexion->query($sql);
        $connexion->bind(':id_empresa', $id_empresa);
        $cod = $connexion->resultset();
        return $cod;
    }    

function Talento_Divisiones_Options_IdDivision_data($id_empresa){
        $connexion = new DatabasePDO();
        $sql="select silla_division as c1, silla_id_division as c1_id from tbl_usuarios_orgchart
        where silla_division<>'' group by silla_division order by silla_division";
        $connexion->query($sql);
        $cod = $connexion->resultset();
        return $cod;
        }
        
function Potencial_tbl_sucesion_full($rut_col,$division,$id_empresa, $criticos, $groupby){
        $connexion = new DatabasePDO();
        
        if($criticos=="1"){
            $queryCriticos= " and (select j.idc26 from tbl_potencial_cargos_criticos  j where j.rut = h.rut_suceder)='SI' ";
        }
        elseif($criticos=="2"){
            $queryCriticos= " and (select j.idc26 from tbl_potencial_cargos_criticos  j where j.rut = h.rut_suceder)='SI' ";
        }else {
            $queryCriticos= " ";
        }
        
        $sql= "select h.*,
        (select nombre_completo from tbl_usuario where rut=h.rut_suceder) as nombre_completo_a_suceder,
        
        (select id_cargo from tbl_usuario where rut=h.rut_suceder) as id_cargo_a_suceder,
        (select cargo from tbl_usuario where rut=h.rut_suceder) as cargo_a_suceder,
        (select division from tbl_usuario where rut=h.rut_suceder) as division_a_suceder,
        (select genero from tbl_usuario where rut=h.rut_suceder) as genero_a_suceder,
        (select j.idc26 from tbl_potencial_cargos_criticos j where j.rut = h.rut_suceder) as critico,
        
        (SELECT k.idc26 FROM tbl_potencial_cargos_criticos k WHERE k.rut = h.rut_suceder) as cargo_critico,
        (select b.idc06 from tbl_potencial_formularios_respuestas b where rut= h.rut_suceder and b.id_form ='3') as reporte_linea_1_2,
        
        (select nombre_completo from tbl_usuario where rut=h.rut_col) as nombre_completo_sucesor,
        (select id_cargo from tbl_usuario where rut=h.rut_col) as id_cargo_sucesor,
        (select cargo from tbl_usuario where rut=h.rut_col) as cargo_sucesor,
        (select division from tbl_usuario where rut=h.rut_col) as division_sucesor,
        (select genero from tbl_usuario where rut=h.rut_col) as genero_sucesor
        
        from tbl_potencial_sucesion h
        where cerrado='1'
        $queryCriticos
        $groupby
        ";

        $connexion->query($sql);
        $cod = $connexion->resultset();
        return $cod;
        }

function Potencial_tbl_formularios_full($rut_col, $division, $id_empresa){
    $connexion = new DatabasePDO();
    $datos_usuario = DatosUsuario_($rut_col, $id_empresa, $connexion);
    $datos_usuario_jefe = DatosUsuario_($datos_usuario[0]->jefe, $id_empresa, $connexion);
    
    $sql_bac = "SELECT rut FROM tbl_mc_bac WHERE rut=:rut";
    $connexion->query($sql_bac);
    $connexion->bind(':rut', $rut_col);
    $connexion->execute();
    $bac = $connexion->resultset();
    
    $sql_relacionados = "SELECT estado FROM tbl_mc_potencial_relacionados WHERE rut=:rut";
    $connexion->query($sql_relacionados);
    $connexion->bind(':rut', $rut_col);
    $connexion->execute();
    $relacionados = $connexion->resultset();
    
    $sql_form1 = "SELECT * FROM tbl_potencial_formularios_respuestas WHERE id_form = '1' AND rut=:rut";
    $connexion->query($sql_form1);
    $connexion->bind(':rut', $rut_col);
    $connexion->execute();
    $form1 = $connexion->resultset();
    
    $sql_form2 = "SELECT * FROM tbl_potencial_formularios_respuestas WHERE id_form = '2' AND rut=:rut";
    $connexion->query($sql_form2);
    $connexion->bind(':rut', $rut_col);
    $connexion->execute();
    $form2 = $connexion->resultset();
    
    $sql_form3 = "SELECT * FROM tbl_potencial_formularios_respuestas WHERE id_form = '3' AND rut=:rut";
    $connexion->query($sql_form3);
    $connexion->bind(':rut', $rut_col);
    $connexion->execute();
    $form3 = $connexion->resultset();
    
    $sql_form4 = "SELECT * FROM tbl_potencial_formularios_respuestas WHERE id_form = '4' AND rut=:rut";
    $connexion->query($sql_form4);
    $connexion->bind(':rut', $rut_col);
    $connexion->execute();
    $form4 = $connexion->resultset();
    
    $sql_form5 = "SELECT * FROM tbl_potencial_formularios_respuestas WHERE id_form = '5' AND rut=:rut";
    $connexion->query($sql_form5);
    $connexion->bind(':rut', $rut_col);
    $connexion->execute();
    $form5 = $connexion->resultset();

    
    $sql_form6="SELECT * FROM tbl_potencial_formularios_respuestas 	  WHERE id_form = '6' AND rut='".$rut_col."'";

    $connexion->query($sql_form6);
    $connexion->bind(':rut', $rut_col);
    $connexion->execute();
    $form6 = $connexion->resultset();

    $sql_form_desempeno="	SELECT * FROM tbl_mc_tramo_desempeno 	  WHERE num_rut='".$rut_col."'";
    $connexion->query($sql_form_desempeno);
    $connexion->bind(':rut', $rut_col);
    $connexion->execute();
    $form_desempeno = $connexion->resultset();

    $sql_form_carrera_interna="	SELECT * FROM tbl_mc_carrera_interna	  WHERE rut='".$rut_col."' order by fec_ini DESC limit 5";
    $connexion->query($sql_form_carrera_interna);
    $connexion->bind(':rut', $rut_col);
    $connexion->execute();
    $form_carrera_interna = $connexion->resultset();

    foreach ($form_carrera_interna as $unico){
        $cuenta_carrera_interna++;
        $carrera_interna[$cuenta_carrera_interna]=$unico;
    }
    
    $sql_form_potencia_metas_2020="SELECT * FROM tbl_mc_potencial_metas WHERE rut=:rut_col AND periodo='2020'";
    $connexion->query($sql_form_potencia_metas_2020);
    $connexion->bind(':rut_col', $rut_col);
    $connexion->execute();
    $form_potencial_metas_2020 = $connexion->resultset();
    
    $sql_form_potencia_metas_2019="SELECT * FROM tbl_mc_potencial_metas WHERE rut=:rut_col AND periodo='2019'";
    $connexion->query($sql_form_potencia_metas_2019);
    $connexion->bind(':rut_col', $rut_col);
    $connexion->execute();
    $form_potencial_metas_2019 = $connexion->resultset();
    
    $sql_form_potencia_metas_2018="SELECT * FROM tbl_mc_potencial_metas WHERE rut=:rut_col AND periodo='2018'";
    $connexion->query($sql_form_potencia_metas_2018);
    $connexion->bind(':rut_col', $rut_col);
    $connexion->execute();
    $form_potencial_metas_2018 = $connexion->resultset();
    
    $sql_form_potencia_metas_2017="SELECT * FROM tbl_mc_potencial_metas WHERE rut=:rut_col AND periodo='2017'";
    $connexion->query($sql_form_potencia_metas_2017);
    $connexion->bind(':rut_col', $rut_col);
    $connexion->execute();
    $form_potencial_metas_2017 = $connexion->resultset();
    
    $sql_form_potencia_competencias_2019="SELECT * FROM tbl_mc_potencial_competencias WHERE rut=:rut_col AND periodo='2019'";
    $connexion->query($sql_form_potencia_competencias_2019);
    $connexion->bind(':rut_col', $rut_col);
    $connexion->execute();
    $form_potencial_competencias_2019 = $connexion->resultset();

    
    $sql_form_potencia_competencias_2018="	SELECT * FROM tbl_mc_potencial_competencias	  WHERE rut=:rut_col and periodo='2018'";
    $connexion->query($sql_form_potencia_competencias_2018);
    $connexion->bind(':rut_col', $rut_col);
    $connexion->execute();
    $form_potencial_competencias_2018 = $connexion->resultset();

    $sql_form_potencia_competencias_2017="	SELECT * FROM tbl_mc_potencial_competencias	  WHERE rut=:rut_col and periodo='2017'";
    $connexion->query($sql_form_potencia_competencias_2017);
    $connexion->bind(':rut_col', $rut_col);
    $connexion->execute();
    $form_potencial_competencias_2017 = $connexion->resultset();


    $sql_form_potencia_clima_2019="	SELECT * FROM tbl_mc_potencial_clima	  WHERE rut=:rut_col and periodo='2019'";
    $connexion->query($sql_form_potencia_clima_2019);
    $connexion->bind(':rut_col', $rut_col);
    $connexion->execute();
    $form_potencial_clima_2019 = $connexion->resultset();


    //sucesores

    $sql_form_potencia_clima_2019="	SELECT * FROM tbl_mc_potencial_clima	  WHERE rut=:rut_col and periodo='2019'";
    $connexion->query($sql_form_potencia_clima_2019);
    $connexion->bind(':rut_col', $rut_col);
    $connexion->execute();
    $form_potencial_clima_2019 = $connexion->resultset();


    $sql_form_potencia_suc1="	  select h.*, (select cargo from tbl_usuario where rut=h.rut_col) as nombre_suc from tbl_potencial_sucesion h where h.tipo = 'sucesor' and h.rut_suceder =:rut_col limit 0,1;";
    $connexion->query($sql_form_potencia_suc1);
    $connexion->bind(':rut_col', $rut_col);
    $connexion->execute();
    $suc1 = $connexion->resultset();

    $sql_form_potencia_suc2="	  select h.*, (select cargo from tbl_usuario where rut=h.rut_col) as nombre_suc from tbl_potencial_sucesion h where h.tipo = 'sucesor' and h.rut_suceder =:rut_col limit 1,1;";
    $connexion->query($sql_form_potencia_suc2);
    $connexion->bind(':rut_col', $rut_col);
    $connexion->execute();
    $suc2 = $connexion->resultset();

    $sql_form_potencia_suc3="	  select h.*, (select cargo from tbl_usuario where rut=h.rut_col) as nombre_suc from tbl_potencial_sucesion h where h.tipo = 'sucesor' and h.rut_suceder =:rut_col limit 2,1;";
    $connexion->query($sql_form_potencia_suc3);
    $connexion->bind(':rut_col', $rut_col);
    $connexion->execute();
    $suc3 = $connexion->resultset();

    $sql_form_potencia_suc4="	  select h.*, (select cargo from tbl_usuario where rut=h.rut_col) as nombre_suc from tbl_potencial_sucesion h where h.tipo = 'sucesor_potencial' and h.rut_suceder =:rut_col limit 0,1;";
    $connexion->query($sql_form_potencia_suc4);
    $connexion->bind(':rut_col', $rut_col);
    $connexion->execute();
    $suc4 = $connexion->resultset();

    $sql_form_potencia_suc5="	  select h.*, (select cargo from tbl_usuario where rut=h.rut_col) as nombre_suc from tbl_potencial_sucesion h where h.tipo = 'sucesor_potencial' and h.rut_suceder =:rut_col limit 1,1;";
    $connexion->query($sql_form_potencia_suc5);
    $connexion->bind(':rut_col', $rut_col);
    $connexion->execute();
    $suc5 = $connexion->resultset();

    $sql_form_potencia_suc6="	  select h.*, (select cargo from tbl_usuario where rut=h.rut_col) as nombre_suc from tbl_potencial_sucesion h where h.tipo = 'sucesor_potencial' and h.rut_suceder =:rut_col limit 2,1;";
    $connexion->query($sql_form_potencia_suc6);
    $connexion->bind(':rut_col', $rut_col);
    $connexion->execute();
    $suc6 = $connexion->resultset();

    $sql_form_potencia_suc7="	  select h.*, (select cargo from tbl_usuario where rut=h.rut_col) as nombre_suc from tbl_potencial_sucesion h where h.tipo = 'reemplazo' and h.rut_suceder =:rut_col limit 0,1;";
    $connexion->query($sql_form_potencia_suc7);
    $connexion->bind(':rut_col', $rut_col);
    $connexion->execute();
    $suc7 = $connexion->resultset();

    $sql_form_potencia_suc8="	  select h.*, (select cargo from tbl_usuario where rut=h.rut_col) as nombre_suc from tbl_potencial_sucesion h where h.tipo = 'reemplazo' and h.rut_suceder =:rut_col limit 1,1;"; 
    $connexion->query($sql_form_potencia_suc8);
    $connexion->bind(':rut_col', $rut_col);
    $connexion->execute();
    $suc8 = $connexion->resultset();

    $sql_form_potencia_suc9="	  select h.*, (select cargo from tbl_usuario where rut=h.rut_col) as nombre_suc from tbl_potencial_sucesion h where h.tipo = 'reemplazo' and h.rut_suceder =:rut_col limit 2,1;";
    $connexion->query($sql_form_potencia_suc9);
    $connexion->bind(':rut_col', $rut_col);
    $connexion->execute();
    $suc9 = $connexion->resultset();
    

    $sql_bac="	SELECT rut FROM tbl_mc_bac 	  WHERE rut='".$rut_col."'";
    $connexion->query($sql_bac);
    $connexion->bind(':rut_col', $rut_col);
    $connexion->execute();
    $bac = $connexion->resultset();

    $sql_relacionados="	SELECT estado FROM tbl_mc_potencial_relacionados 	  WHERE rut='".$rut_col."'";
    $connexion->query($sql_relacionados);
    $connexion->bind(':rut_col', $rut_col);
    $connexion->execute();
    $relacionados = $connexion->resultset();

    $sql_agradecimientos="	SELECT count( h.tipo ) AS agradecimiento FROM tbl_reconoce_gracias_full h WHERE rut_destinatario = '".$rut_col."' AND tipo = 'GRACIAS';";
    $connexion->query($sql_agradecimientos);
    $connexion->bind(':rut_col', $rut_col);
    $connexion->execute();
    $agradecimentos = $connexion->resultset();


    $sql_reconocimientos="	SELECT count( h.tipo ) AS reconocimiento FROM tbl_reconoce_gracias_full h WHERE rut_destinatario = '".$rut_col."' AND tipo = 'RECONOCIMIENTO';";
    $connexion->query($sql_reconocimientos);
    $connexion->bind(':rut_col', $rut_col);
    $connexion->execute();
    $reconocimientos = $connexion->resultset();

    $sql_amonestaciones="	SELECT count( h.tipo ) AS amonestaciones FROM tbl_reconoce_gracias_full h WHERE rut_destinatario = '".$rut_col."' AND tipo = 'AMONESTACION'";
    $connexion->query($sql_amonestaciones);
    $connexion->bind(':rut_col', $rut_col);
    $connexion->execute();
    $amonestaciones = $connexion->resultset();

    
    //echo $sql;

    //rut	nombre_completo	edad	estado_civil	cargo	jefe	division	area	zona	seccion
    //1	2	3	4	5	6	7	8	9	10

    $arreglo[1]=$rut_col;
    $arreglo[2]=$datos_usuario[0]->nombre_completo;
    $arreglo[3]=$datos_usuario[0]->edad;
    $arreglo[4]=$form1[0]->idc01;
    $arreglo[5]=$datos_usuario[0]->cargo;
    $arreglo[6]=$datos_usuario_jefe[0]->nombre_completo;
    $arreglo[7]=$datos_usuario[0]->division;
    $arreglo[8]=$datos_usuario[0]->area;
    $arreglo[9]=$datos_usuario[0]->zona;
    $arreglo[10]=$datos_usuario[0]->centro_costo;


    //oficina	region	nivel	antiguedad	zona_salarial	profesion_1	universidad_1	profesion_2	universidad_2	postgrado_1
    //11	12	13	14	15	16	17	18	19	20

    $arreglo[11]=$datos_usuario[0]->sucursal;
    $arreglo[12]=$datos_usuario[0]->regional;
    $arreglo[13]=$datos_usuario[0]->perfil_evaluacion;
    $arreglo[14]=Potencial_calculoAnos_v2($datos_usuario[0]->familia_cargo);
    $arreglo[15]=$form_desempeno[0]->zona_salarial;

    $arreglo[16]=PotencialLimpiaTextosTrim($form2[0]->idc01);
    $arreglo[17]=PotencialLimpiaTextosTrim($form2[0]->idc02);
    $arreglo[18]=PotencialLimpiaTextosTrim($form2[0]->idc03);
    $arreglo[19]=PotencialLimpiaTextosTrim($form2[0]->idc04);
    $arreglo[20]=PotencialLimpiaTextosTrim($form2[0]->idc05);

    //universidad_postgrado_1	postgrado_2	universidad_postgrado_2	idioma_1	nivel_1	idioma_2	nivel_2	cargo_anterior_1	empresa_anterior_1	periodo_anterior_1
    //21	22	23	24	25	26	27	28	29	30

    $arreglo[21]=PotencialLimpiaTextosTrim($form2[0]->idc06);
    $arreglo[22]=PotencialLimpiaTextosTrim($form2[0]->idc07);
    $arreglo[23]=PotencialLimpiaTextosTrim($form2[0]->idc08);
    $arreglo[24]=PotencialLimpiaTextosTrim($form2[0]->idc09);
    $arreglo[25]=PotencialLimpiaTextosTrim($form2[0]->idc10);
    $arreglo[26]=PotencialLimpiaTextosTrim($form2[0]->idc11);
    $arreglo[27]=PotencialLimpiaTextosTrim($form2[0]->idc12);

    $arreglo[28]=PotencialLimpiaTextosTrim($form4[0]->idc01);
    $arreglo[29]=PotencialLimpiaTextosTrim($form4[0]->idc02);
    $arreglo[30]=PotencialLimpiaTextosTrim($form4[0]->idc03);

    //cargo_anterior_2	empresa_anterior_2	periodo_anterior_2	cargo_anterior_3	empresa_anterior_3	periodo_anterior_3	cargo_1	periodo_1	cargo_2	periodo_2
    //31	32	33	34	35	36	37	38	39	40

    $arreglo[31]=PotencialLimpiaTextosTrim($form4[0]->idc04);
    $arreglo[32]=PotencialLimpiaTextosTrim($form4[0]->idc05);
    $arreglo[33]=PotencialLimpiaTextosTrim($form4[0]->idc06);
    $arreglo[34]=PotencialLimpiaTextosTrim($form4[0]->idc07);
    $arreglo[35]=PotencialLimpiaTextosTrim($form4[0]->idc08);
    $arreglo[36]=PotencialLimpiaTextosTrim($form4[0]->idc09);

    $arreglo[37]=$carrera_interna[1]->cargo_glosa;$a="";
    if($carrera_interna[1]->fec_ter=="" and $carrera_interna[1]->fec_ini<>""){$carrera_interna[1]->fec_ter="actualidad";}
    if($carrera_interna[1]->fec_ini<>""){$a=" a ";}

    $arreglo[38]=$carrera_interna[1]->fec_ini." $a ".$carrera_interna[1]->fec_ter;

    $arreglo[39]=$carrera_interna[2]->cargo_glosa;$a="";
    if($carrera_interna[2]->fec_ter=="" and $carrera_interna[2]->fec_ini<>""){$carrera_interna[2]->fec_ter="actualidad";}
    if($carrera_interna[2]->fec_ini<>""){$a=" a ";}

    $arreglo[40]=$carrera_interna[2]->fec_ini." $a ".$carrera_interna[2]->fec_ter;

    //cargo_3	periodo_3	cargo_4	periodo_4	cargo_5	periodo_5	proyecto_1	rol_proyecto_1	periodo_proyecto_1	proyecto_2
    //41	42	43	44	45	46	47	48	49	50

    $arreglo[41]=$carrera_interna[3]->cargo_glosa;$a="";
    if($carrera_interna[3]->fec_ter=="" and $carrera_interna[3]->fec_ini<>""){$carrera_interna[3]->fec_ter="actualidad";}
    if($carrera_interna[3]->fec_ini<>""){$a=" a ";}
    $arreglo[42]=$carrera_interna[3]->fec_ini." $a ".$carrera_interna[3]->fec_ter;

    $arreglo[43]=$carrera_interna[4]->cargo_glosa;$a="";
    if($carrera_interna[4]->fec_ter=="" and $carrera_interna[4]->fec_ini<>""){$carrera_interna[4]->fec_ter="actualidad";}
    if($carrera_interna[4]->fec_ini<>""){$a=" a ";}

    $arreglo[44]=$carrera_interna[4]->fec_ini." $a ".$carrera_interna[4]->fec_ter;
    $arreglo[45]=$carrera_interna[5]->cargo_glosa;$a="";
    if($carrera_interna[5]->fec_ter=="" and $carrera_interna[5]->fec_ini<>""){$carrera_interna[5]->fec_ter="actualidad";}
    if($carrera_interna[5]->fec_ini<>""){$a=" a ";}

    $arreglo[46]=$carrera_interna[5]->fec_ini." $a ".$carrera_interna[5]->fec_ter;

    $arreglo[47]=PotencialLimpiaTextosTrim($form5[0]->idc01);
    $arreglo[48]=PotencialLimpiaTextosTrim($form5[0]->idc02);
    $arreglo[49]=PotencialLimpiaTextosTrim($form5[0]->idc03);
    $arreglo[50]=PotencialLimpiaTextosTrim($form5[0]->idc04);

    //rol_proyecto_2	periodo_proyecto_2	proyecto_3	rol_proyecto_3	periodo_proyecto_3	proyecto_4	rol_proyecto_4	periodo_proyecto_4	proyecto_5	rol_proyecto_5
    //51	52	53	54	55	56	57	58	59	60

    $arreglo[51]=PotencialLimpiaTextosTrim($form5[0]->idc05);
    $arreglo[52]=PotencialLimpiaTextosTrim($form5[0]->idc06);
    $arreglo[53]=PotencialLimpiaTextosTrim($form5[0]->idc07);
    $arreglo[54]=PotencialLimpiaTextosTrim($form5[0]->idc08);
    $arreglo[55]=PotencialLimpiaTextosTrim($form5[0]->idc09);
    $arreglo[56]=PotencialLimpiaTextosTrim($form5[0]->idc10);
    $arreglo[57]=PotencialLimpiaTextosTrim($form5[0]->idc11);
    $arreglo[58]=PotencialLimpiaTextosTrim($form5[0]->idc12);
    $arreglo[59]=PotencialLimpiaTextosTrim($form5[0]->idc13);
    $arreglo[60]=PotencialLimpiaTextosTrim($form5[0]->idc14);

    //periodo_proyecto_5	tramo_gestion_desempeno	meta_2020	meta_2019	meta_2018	meta_2017	periodo_competencia_2019	nota_final_2019	nota_ascendente_2019	nota_descendente_2019
    //61	62	63	64	65	66	67	68	69	70

    $arreglo[61]=PotencialLimpiaTextosTrim($form5[0]->idc15);

    $arreglo[62]=$form_desempeno[0]->tramo_gestion_desempeno;

    $arreglo[63]=$form_potencial_metas_2020[0]->meta;
    $arreglo[64]=$form_potencial_metas_2019[0]->meta;
    $arreglo[65]=$form_potencial_metas_2018[0]->meta;
    $arreglo[66]=$form_potencial_metas_2017[0]->meta;


    $arreglo[67]=$form_potencial_competencias_2019[0]->periodo;
    $arreglo[68]=$form_potencial_competencias_2019[0]->final;
    $arreglo[69]=$form_potencial_competencias_2019[0]->ascendente;
    $arreglo[70]=$form_potencial_competencias_2019[0]->descendente;

    //periodo_competencia_2018	nota_final_2018	nota_ascendente_2018	nota_descendente_2018	periodo_competencia_2017	nota_final_2017	nota_ascendente_2017	nota_descendente_2017	periodo_clima	resultado_jefatura
    //71	72	73	74	75	76	77	78	79	80

    $arreglo[71]=$form_potencial_competencias_2018[0]->periodo;
    $arreglo[72]=$form_potencial_competencias_2018[0]->final;
    $arreglo[73]=$form_potencial_competencias_2018[0]->ascendente;
    $arreglo[74]=$form_potencial_competencias_2018[0]->descendente;

    $arreglo[75]=$form_potencial_competencias_2017[0]->periodo;
    $arreglo[76]=$form_potencial_competencias_2017[0]->final;
    $arreglo[77]=$form_potencial_competencias_2017[0]->ascendente;
    $arreglo[78]=$form_potencial_competencias_2017[0]->descendente;

    $arreglo[79]=$form_potencial_clima_2019[0]->periodo;
    $arreglo[80]=$form_potencial_clima_2019[0]->clima;




    //recomendaci�n_potencial	periodo_potencial	sucesor_1	cargo_sucesor_1	sucesor_2	cargo_sucesor_2	sucesor_3	cargo_sucesor_3	suc_potencial_1	potencial_cargo_1
    //81	82	83	84	85	86	87	88	89	90

    $arreglo[81]=$form3[0]->idc01;
    $arreglo[82]=$form3[0]->idc02;

    $arreglo[83]=$suc1[0]->rut_col;
    $arreglo[84]=PotencialLimpiaTextosTrim($suc1[0]->nombre_suc);

    $arreglo[85]=$suc2[0]->rut_col;
    $arreglo[86]=PotencialLimpiaTextosTrim($suc2[0]->nombre_suc);

    $arreglo[87]=$suc3[0]->rut_col;
    $arreglo[88]=PotencialLimpiaTextosTrim($suc3[0]->nombre_suc);

    $arreglo[89]=$suc4[0]->rut_col;
    $arreglo[90]=PotencialLimpiaTextosTrim($suc4[0]->nombre_suc);

    //suc_potencial_2	potencial_cargo2	suc_potencial_3	potencial_cargo_3	reemplazo_1	cargo_reemplazo_1	reemplazo_2	cargo_reemplazo_2	reemplazo_3	cargo_reemplazo_3
    //91	92	93	94	95	96	97	98	99	100

    $arreglo[91]=$suc5[0]->rut_col;
    $arreglo[92]=PotencialLimpiaTextosTrim($suc5[0]->nombre_suc);

    $arreglo[93]=$suc6[0]->rut_col;
    $arreglo[94]=PotencialLimpiaTextosTrim($suc6[0]->nombre_suc);

    $arreglo[95]=$suc7[0]->rut_col;
    $arreglo[96]=PotencialLimpiaTextosTrim($suc7[0]->nombre_suc);

    $arreglo[97]=$suc8[0]->rut_col;
    $arreglo[98]=PotencialLimpiaTextosTrim($suc8[0]->nombre_suc);

    $arreglo[99]=$suc9[0]->rut_col;
    $arreglo[100]=PotencialLimpiaTextosTrim($suc9[0]->nombre_suc);


    //bac	declaracion_relacionados	agradecimiento	reconocimiento	amonestaciones	106	107	108	109
    //101	102	103	104	105	106	107	108	109
    if($bac[0]->rut<>""){$bac_txt="SI";} else {$bac_txt="NO";}


    $arreglo[101]=$bac_txt;
    $arreglo[102]=PotencialLimpiaTextosTrim($relacionados[0]->estado);
    $arreglo[103]=PotencialLimpiaTextosTrim($agradecimentos[0]->agradecimiento);
    $arreglo[104]=PotencialLimpiaTextosTrim($reconocimientos[0]->reconocimiento);
    $arreglo[105]=PotencialLimpiaTextosTrim($amonestaciones[0]->amonestaciones);

    $arreglo[106]=PotencialLimpiaTextosTrim($form6[0]->idc20);
    $arreglo[107]=PotencialLimpiaTextosTrim($form6[0]->idc21);
    $arreglo[108]=PotencialLimpiaTextosTrim($form6[0]->idc22);
    $arreglo[109]=PotencialLimpiaTextosTrim($form6[0]->idc23);
    $arreglo[110]=PotencialLimpiaTextosTrim($form6[0]->idc24);
    $arreglo[111]=PotencialLimpiaTextosTrim($form6[0]->idc25);
    $arreglo[112]=PotencialLimpiaTextosTrim($form6[0]->idc26);

    return $arreglo;

}

function Potencial_List_tbl_mc_potencial_metas($rut){
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_mc_potencial_metas WHERE rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
    }
    
function Potencial_List_tbl_mc_carrera_interna($rut){
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_mc_carrera_interna WHERE rut = :rut ORDER BY fec_ini DESC";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
    }
    
function Potencial_List_tbl_mc_tramo_desempeno($rut){
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_mc_tramo_desempeno WHERE num_rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
    }
    
function Potencial_List_tbl_mc_potencial_competencias($rut){
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_mc_potencial_competencias WHERE rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
    }
    
function Potencial_List_tbl_mc_potencial_clima($rut){
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_mc_potencial_clima WHERE rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
    }
function PotencialOpenMapa($rut){
        $connexion = new DatabasePDO();
        $sql = "UPDATE tbl_potencial_sucesion SET cerrado = null WHERE rut_suceder = :rut";
        $connexion->query($sql);
        $connexion->bind(':rut', $rut);
        $cod = $connexion->resultset();
        return $cod;
        }
        
function Potencial_List_tbl_mc_potencial_relacionados($rut){
        $connexion = new DatabasePDO();
        $sql = "SELECT h.* FROM tbl_mc_potencial_relacionados h WHERE h.rut = :rut";
        $connexion->query($sql);
        $connexion->bind(':rut', $rut);
        $cod = $connexion->resultset();
        return $cod;
        }
        
function Potencial_List_tbl_mc_potencial_bac($rut){
        $connexion = new DatabasePDO();
        $sql = "SELECT * from tbl_mc_bac WHERE rut=:rut";
        $connexion->query($sql);
        $connexion->bind(':rut', $rut);
        $cod = $connexion->resultset();
        return $cod;
        }
        
function Potencial_tbl_potencial_sucesion($rut_col){
        $connexion = new DatabasePDO();
        $sql = "SELECT * from tbl_potencial_sucesion WHERE rut_suceder=:rut_col ORDER BY tipo DESC";
        $connexion->query($sql);
        $connexion->bind(':rut_col', $rut_col);
        $cod = $connexion->resultset();
        return $cod;
        }

function Potencial_tbl_potencial_sucesion_tipo($rut_col, $tipo){
            $connexion = new DatabasePDO();
            $sql="SELECT * from tbl_potencial_sucesion where rut_suceder=:rut_col and tipo=:tipo order by id ASC ";
            $connexion->query($sql);
            $connexion->bind(':rut_col', $rut_col);
            $connexion->bind(':tipo', $tipo);
            $cod = $connexion->resultset();
            return $cod;
        }
        
function Potencial_tbl_potencial_a_suceder_tipo($rut_col){
            $connexion = new DatabasePDO();
            $sql="SELECT h.rut as rut_col, h.nombre_completo, h.silla_cargo
                    FROM tbl_usuario h
                    INNER JOIN tbl_potencial_sucesion k
                    ON h.rut = k.rut_suceder
                    WHERE k.rut_col=:rut_col and k.tipo <> 'reemplazo'
                    GROUP BY k.rut_suceder";
            $connexion->query($sql);
            $connexion->bind(':rut_col', $rut_col);
            $cod = $connexion->resultset();
            return $cod;
        }
        
function Potencial_tbl_potencial_sucesion_cargo_tipo($cargo, $tipo){
            $connexion = new DatabasePDO();
            $sql="SELECT * from tbl_usuario where cargo=:cargo";
            $connexion->query($sql);
            $connexion->bind(':cargo', $cargo);
            $cod = $connexion->resultset();
            return $cod;
        }
        
function Potencial_tbl_potencial_sucesion_mapa_por_cargo_tipo($cargo, $division, $tipo, $solo_criticos, $QueryOficialInterina){
    $connexion = new DatabasePDO();
    
    if($solo_criticos=="1"){
        $queryCriticos= " and (select j.idc26 from tbl_potencial_cargos_criticos  j where j.rut = h.rut_suceder)='SI' ";
    } else {
        $queryCriticos= " ";
    }
    
    if($division<>""){
    
        //echo "division $division";exit();
    
        if($division=="Todas"){
            $sql="	
                        select h.*, 
                        (select division from tbl_usuario where rut=h.rut_suceder) as division,
                        (select division from tbl_usuario where rut=h.rut_suceder) as division2
                        
                         from tbl_potencial_sucesion h 
    
                        where id>0
                        
                        and (select division from tbl_usuario where rut=h.rut_suceder) is not null
                        
                        $QueryOficialInterina
    
                        $queryCriticos
    
                        group by h.rut_suceder  
                        order by 
                        
                        (select division from tbl_usuario where rut=h.rut_suceder) asc,
                        
                         (
                                SELECT
                                    perfil_evaluacion
                                FROM
                                    tbl_usuario
                                WHERE
                                    rut = h.rut_suceder
                            ) DESC,
                            
                            (select cargo from tbl_usuario where rut=h.rut_suceder) asc
                            
                            ";
        }
        
        elseif($division=="Divisionales"){
            $sql="	
						select h.*, 
						(select division from tbl_usuario where rut=h.rut_suceder) as division,
						(select division from tbl_usuario where rut=h.rut_suceder) as division2
						
						from tbl_potencial_sucesion h 

						where id>0
						
						and (select division from tbl_usuario where rut=h.rut_suceder) is not null
						and (select rut from tbl_potencial_sucesion_gerencia where rut=h.rut_suceder) is not null

						$QueryOficialInterina

						$queryCriticos

						group by h.rut_suceder  
						order by 
						
						(select division from tbl_usuario where rut=h.rut_suceder) asc,
						
						 (
								SELECT
									perfil_evaluacion
								FROM
									tbl_usuario
								WHERE
									rut = h.rut_suceder
							) DESC,
							
							(select cargo from tbl_usuario where rut=h.rut_suceder) asc";
            
        }else {
            $sql="SELECT h.*, (SELECT division FROM tbl_usuario WHERE rut=h.rut_suceder) AS division FROM tbl_potencial_sucesion h WHERE ((SELECT division FROM tbl_usuario WHERE rut = h.rut_suceder) = :division OR h.division_cambio = :division) $QueryOficialInterina $queryCriticos GROUP BY h.rut_suceder ORDER BY (SELECT perfil_evaluacion FROM tbl_usuario WHERE rut = h.rut_suceder) DESC, (SELECT cargo FROM tbl_usuario WHERE rut=h.rut_suceder) ASC";

        }
    } else {
        $sql="SELECT h.*, (SELECT cargo FROM tbl_usuario WHERE rut=h.rut_suceder) AS cargo FROM tbl_potencial_sucesion h WHERE (SELECT cargo FROM tbl_usuario WHERE rut=h.rut_suceder) = :cargo $QueryOficialInterina $queryCriticos GROUP BY h.rut_suceder ORDER BY (SELECT perfil_evaluacion FROM tbl_usuario WHERE rut=h.rut_suceder) DESC, (SELECT cargo FROM tbl_usuario WHERE rut=h.rut_suceder) ASC";
    }
    
    //echo $sql; exit();
    $connexion->query($sql);
    $connexion->bind(':division', $division);
    $connexion->bind(':QueryOficialInterina', $QueryOficialInterina);
    $connexion->bind(':queryCriticos', $queryCriticos);
    $connexion->bind(':cargo', $cargo);
    $cod = $connexion->resultset();
    return $cod;

}

function Potencial_BuscaFechaUltima($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT fecha FROM tbl_potencial_sucesion WHERE rut_suceder = :rut ORDER BY fecha DESC LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod[0]->fecha;
}

function Potencial_tbl_formularios_respuestas_data($id_form, $rut_col)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_potencial_formularios_respuestas WHERE id_form = :id_form AND rut = :rut_col";
    $connexion->query($sql);
    $connexion->bind(':id_form', $id_form);
    $connexion->bind(':rut_col', $rut_col);
    $cod = $connexion->resultset();
    return $cod;
}

function Potencial_tbl_formularios_respuestas_data_ultimaFecha($id_form, $rut_col)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT fecha FROM tbl_potencial_formularios_respuestas WHERE id_form = :id_form AND rut = :rut_col ORDER BY fecha DESC LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':id_form', $id_form);
    $connexion->bind(':rut_col', $rut_col);
    $cod = $connexion->resultset();
    return $cod[0]->fecha;
}

function Potencial_Talento_Sucesores_vista_cerrados($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_potencial_sucesion WHERE cerrado = '1' GROUP BY rut_suceder";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}

function Potencial_talento_busca_cierre_mapa($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT cerrado FROM tbl_potencial_sucesion WHERE rut_suceder = :rut LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod[0]->cerrado;
}

function Potencial_Talento_Close_map($rut) {
    $connexion = new DatabasePDO();
    $hoy = date("Y-m-d");
    $update = "UPDATE tbl_potencial_sucesion SET cerrado='1', fecha='$hoy' WHERE rut_suceder=:rut";
    $connexion->query($update);
    $connexion->bind(':rut', $rut);
    $connexion->execute();
}

function Potencial_Talento_Comentario_map($rut, $comentario) {
    $connexion = new DatabasePDO();
    $hoy = date("Y-m-d");
    $comentario = utf8_decode($comentario);
    $update = "UPDATE tbl_potencial_sucesion SET comentario_mapa=:comentario WHERE rut_suceder=:rut";
    $connexion->query($update);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':comentario', $comentario);
    $connexion->execute();
}

function Potencial_Talento_Lista_Comentario_map($rut) {
    $connexion = new DatabasePDO();
    $update = "SELECT comentario_mapa FROM tbl_potencial_sucesion WHERE rut_suceder=:rut";
    $connexion->query($update);
    $connexion->bind(':rut', $rut);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod[0]->comentario_mapa;
}

function Potencial_Sucesion_BuscaPerfil($rut) {
    $connexion = new DatabasePDO();
    $sql = "SELECT perfil FROM tbl_potencial_perfil WHERE rut=:rut LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod[0]->perfil;
}

function Potencial_OrgChart_BuscaPerfil($rut) {
    $connexion = new DatabasePDO();
    $sql = "SELECT perfil FROM tbl_org_chart_perfil WHERE rut=:rut LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod[0]->perfil;
}


function Talento_Sucesion_Save_Data($rut_col1,$rut_col2,$rut_col3, $rut){
    $connexion = new DatabasePDO();

    $sql= "select * from tbl_potencial_sucesion where rut_suceder=:rut_col1";
    $connexion->query($sql);
    $connexion->bind(':rut_col1', $rut_col1);
    $cod = $connexion->resultset();

    $fecha = date("Y-m-d");
    if($cod[0]->id>0){
        $update="update tbl_potencial_sucesion set rut_sucesor=:rut_col2, rut_reemplazo=:rut_col3, fecha=:fecha, rut=:rut where id=:id";
        $connexion->query($update);
        $connexion->bind(':rut_col2', $rut_col2);
        $connexion->bind(':rut_col3', $rut_col3);
        $connexion->bind(':fecha', $fecha);
        $connexion->bind(':rut', $rut);
        $connexion->bind(':id', $cod[0]->id);
        $connexion->execute();
    } else {
        $hora  = date("H:i:s");
        $sql   = "insert into tbl_potencial_sucesion (rut_suceder, rut_sucesor, rut_reemplazo, rut, fecha) VALUES (:rut_col1,:rut_col2,:rut_col3,:rut,:fecha)";
        $connexion->query($sql);
        $connexion->bind(':rut_col1', $rut_col1);
        $connexion->bind(':rut_col2', $rut_col2);
        $connexion->bind(':rut_col3', $rut_col3);
        $connexion->bind(':rut', $rut);
        $connexion->bind(':fecha', $fecha);
        $connexion->execute();
    }
    return ($avance);
}

function Talento_Sucesion_BuscaData_Data($rut_col1){
    $connexion = new DatabasePDO();

    $sql= "select * from tbl_potencial_sucesion where rut_suceder=:rut_col1";
    $connexion->query($sql);
    $connexion->bind(':rut_col1', $rut_col1);
    $cod = $connexion->resultset();

    return ($cod);
}

function Potencial_Prism($rut, $documento, $nombre){

    $connexion = new DatabasePDO();

    $sql= "SELECT * FROM tbl_potencial_prism WHERE rut=:rut AND nombre_archivo=:nombre";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':nombre', $nombre);
    $cod = $connexion->resultset();
    $fecha = date("Y-m-d");

    if($cod[0]->id>0){
        $update="UPDATE tbl_potencial_prism SET documento=:documento, fecha=:fecha, nombre_archivo=:nombre WHERE id=:id";
        $connexion->query($update);
        $connexion->bind(':documento', $documento);
        $connexion->bind(':fecha', $fecha);
        $connexion->bind(':nombre', $nombre);
        $connexion->bind(':id', $cod[0]->id);
        $connexion->execute();
    } else {
        $fecha = date("Y-m-d");
        $hora  = date("H:i:s");
        $sql   = "INSERT INTO tbl_potencial_prism (documento, rut, fecha, nombre_archivo) VALUES (:documento, :rut, :fecha, :nombre)";

        $connexion->query($sql);
        $connexion->bind(':documento', $documento);
        $connexion->bind(':rut', $rut);
        $connexion->bind(':fecha', $fecha);
        $connexion->bind(':nombre', $nombre);
        $connexion->execute();
    }
    return ($avance);

}

function Potencial_Busca_Cursos_MiEquipo_data($tipo, $id_empresa, $rut){

    $connexion = new DatabasePDO();

    $sql="SELECT h.*, (SELECT nombre FROM tbl_lms_curso WHERE id=h.id_curso) AS curso, (SELECT nombre_programa FROM tbl_lms_programas_bbdd WHERE id_programa=h.id_programa) AS programa
          FROM tbl_lms_reportes h 
          WHERE h.rut=:rut AND h.id_empresa=:id_empresa AND h.modalidadcurso='1' AND h.avance='100'
          ORDER BY (SELECT nombre_programa FROM tbl_lms_programas_bbdd WHERE id_programa=h.id_programa)";

    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;

}

function Potencial_Busca_Doc_Prism($rut){
    $connexion = new DatabasePDO();
    $sql="SELECT * FROM tbl_potencial_prism WHERE rut=:rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
    }
    
function Potencial_Busca_Forms_Dimension($id_empresa,$dimension){
    $connexion = new DatabasePDO();
    $sql="SELECT * FROM tbl_potencial_formularios WHERE descripcion=:dimension";
    $connexion->query($sql);
    $connexion->bind(':dimension', $dimension);
    $cod = $connexion->resultset();
    return $cod;
    }
    
function Potencial_Busca_Forms_groupbydimension($id_empresa){
    $connexion = new DatabasePDO();
    $sql="SELECT * FROM tbl_potencial_formularios WHERE id_empresa=:id_empresa GROUP BY descripcion";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
    }
    
function Potencial_Id_Full_data($idl) {
    $connexion = new DatabasePDO();
    $sql= "SELECT * FROM tbl_potencial_formularios_respuestas WHERE id=:idl LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':idl', $idl);
    $cod = $connexion->resultset();
    return $cod;
    }
    
function Potencial_Trae_Formulario_data($id_form, $id_empresa){
    $connexion = new DatabasePDO();
    $sql="SELECT * FROM tbl_potencial_formularios WHERE id_empresa=:id_empresa AND id=:id_form";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_form', $id_form);
    $cod = $connexion->resultset();
    return $cod;
    }

function Potencial_Check_respuestas($rut, $idf, $id_empresa){
        $connexion = new DatabasePDO();
        $sql="select count(id) as cuenta from tbl_potencial_formularios_respuestas where rut=:rut and id_form=:idf";
        $connexion->query($sql);
        $connexion->bind(':rut', $rut);
        $connexion->bind(':idf', $idf);
        $cod = $connexion->resultset();
        return ($cod[0]->cuenta);
    }
    
function Potencial_Trae_Formulario_Campos_data_v2($id_campo, $id_empresa){
        $connexion = new DatabasePDO();
        $sql="select h.* from tbl_potencial_formularios_campos h where h.id_empresa=:id_empresa and h.id=:id_campo";
        $connexion->query($sql);
        $connexion->bind(':id_empresa', $id_empresa);
        $connexion->bind(':id_campo', $id_campo);
        $cod = $connexion->resultset();
        return ($cod);
    }
    
function Potencial_CheckIdRespuestas($idf,$rut_col){
        $connexion = new DatabasePDO();
        $sql="select id from tbl_potencial_formularios_respuestas where id_form=:idf and rut=:rut_col";
        $connexion->query($sql);
        $connexion->bind(':idf', $idf);
        $connexion->bind(':rut_col', $rut_col);
        $cod = $connexion->resultset();
        return ($cod[0]->id);
    }
    
function Potencial_Tipo_Field_WithValue_data($idl,$name_field) {
        $connexion = new DatabasePDO();
        $sql= "select $name_field as data from tbl_potencial_formularios_respuestas where id=:idl limit 1";
        $connexion->query($sql);
        $connexion->bind(':idl', $idl);
        $cod = $connexion->resultset();
        return $cod[0]->data;
    }
    
function Potencial_Trae_Formulario_Campos_data($id_campo, $id_empresa){
        $connexion = new DatabasePDO();
        $sql="select h.* from tbl_potencial_formularios_campos h where h.id_empresa=:id_empresa and h.id=:id_campo";
        $connexion->query($sql);
        $connexion->bind(':id_empresa', $id_empresa);
        $connexion->bind(':id_campo', $id_campo);
        $cod = $connexion->resultset();
        return ($cod);
    }
    
function Potencial_Sucesion_calculaedad($fecha){
        $tiempo = strtotime($fecha);
        $ahora = time();
        $edad = ($ahora-$tiempo)/(60*60*24*365.25);
        $edad = floor($edad);
        return $edad;
    }
    
function OrgChartIconosPorRut($rut){

        $connexion = new DatabasePDO();
    
        $sql0 = "SELECT nombre_completo FROM tbl_usuarios_orgchart WHERE rut = :rut";
        $connexion->query($sql0);
        $connexion->bind(':rut', $rut);
        $cod0 = $connexion->resultset();
    
        $sql1 = "SELECT perfil FROM tbl_org_chart_criticos_perfiles WHERE rut = :rut";
        $connexion->query($sql1);
        $connexion->bind(':rut', $rut);
        $cod1 = $connexion->resultset();
    
        if($cod1[0]->perfil == "estrategico"){ 
            $icono3 = "1"; 
        }
        if($cod1[0]->perfil == "operacional"){ 
            $icono4 = "1"; 
        }
    
        $sql2 = "SELECT tipo FROM tbl_potencial_sucesion WHERE rut_col = :rut AND tipo = 'sucesor'";
        $connexion->query($sql2);
        $connexion->bind(':rut', $rut);
        $cod2 = $connexion->resultset();
        if($cod2[0]->tipo == "sucesor"){ 
            $icono1 = "1"; 
        }
    
        $sql2 = "SELECT tipo FROM tbl_potencial_sucesion WHERE rut_col = :rut AND tipo = 'sucesor_potencial'";
        $connexion->query($sql2);
        $connexion->bind(':rut', $rut);
        $cod2 = $connexion->resultset();
        if($cod2[0]->tipo == "sucesor_potencial"){ 
            $icono2 = "1"; 
        }
    
        $sql2 = "SELECT tipo FROM tbl_potencial_sucesion WHERE rut_col = :rut AND tipo = 'reemplazo'";
        $connexion->query($sql2);
        $connexion->bind(':rut', $rut);
        $cod2 = $connexion->resultset();
        if($cod2[0]->tipo == "reemplazo"){ 
            $icono6 = "1"; 
        }
    
        $sql2 = "SELECT id FROM tbl_potencial_sucesion WHERE rut_suceder = :rut AND tipo = 'sucesor'";
        $connexion->query($sql2);
        $connexion->bind(':rut', $rut);
        $cod2 = $connexion->resultset();
        if($cod2[0]->id > 0){ 
            $icono7 = "1"; 
        }
    
        $arreglo[1] = $icono1;
        $arreglo[2] = $icono2;
        $arreglo[3] = $icono3;
        $arreglo[4] = $icono4;
        $arreglo[5] = $icono5;
        $arreglo[6] = $icono6;
        $arreglo[7] = $icono7;
    
        return $arreglo;
    }
    
function OrgChartIconosAll(){
        $connexion = new DatabasePDO();
        $sql="SELECT h.* FROM tbl_org_chart_icon h";
        $connexion->query($sql);
        $cod = $connexion->resultset();
        return $cod;
    }
    
function OrgChart_MiJefe($rut){
        $connexion = new DatabasePDO();
        $sql="SELECT h.jefe FROM tbl_usuarios_orgchart h WHERE rut=:rut AND h.vigencia='0'";
        $connexion->query($sql);
        $connexion->bind(':rut', $rut);
        $cod = $connexion->resultset();
        return $cod[0]->jefe;
    }
    
function OrgChart_DatosUsuario($rut){
        $connexion = new DatabasePDO();
        $sql="SELECT h.rut, h.nombre_completo, h.cargo, h.division, h.silla_unidad FROM tbl_usuarios_orgchart h WHERE rut=:rut AND h.vigencia='0'";
        $connexion->query($sql);
        $connexion->bind(':rut', $rut);
        $cod = $connexion->resultset();
        return $cod;
    }
    
function OrgChart_MiEquipo($rut){
        $connexion = new DatabasePDO();
        $sql="SELECT h.rut, h.nombre_completo, h.cargo, h.division, h.silla_unidad, (SELECT COUNT(id) FROM tbl_usuario WHERE jefe=h.rut) AS num_equipo, h.perfil_evaluacion, h.genero FROM tbl_usuarios_orgchart h WHERE jefe=:rut AND h.vigencia='0' ORDER BY h.perfil_evaluacion DESC, h.genero DESC, h.nombre_completo ASC";
        $connexion->query($sql);
        $connexion->bind(':rut', $rut);
        $cod = $connexion->resultset();
        return $cod;
    }
    
function OrgChart_Cuenta_MiEquipo($rut){
        $connexion = new DatabasePDO();
        $sql="	select count(id) as cuenta from tbl_usuarios_orgchart where jefe = :rut and vigencia='0'
        
        and jefe<>rut
        and rut<>34000000 and rut<>35000000 and rut<>33000000 and rut<>6972382	
        
        ";
        $connexion->query($sql);
        $connexion->bind(':rut', $rut);
        $cod = $connexion->resultset();
        return $cod[0]->cuenta;
    }
    
function OrgChart_Personas_Vigentes($search){

        $search      = VerificaArregloSQLInjectionV3($search);

        $connexion = new DatabasePDO();
        $sql="	
                SELECT
                tbl_usuarios_orgchart.*,
              MATCH (tbl_usuarios_orgchart.nombre_completo) AGAINST ('$search') AS relevance,
                'vigente' as vigente
            FROM tbl_usuarios_orgchart
            WHERE MATCH (tbl_usuarios_orgchart.nombre_completo) AGAINST ('+ $search') 
            and MATCH (tbl_usuarios_orgchart.nombre_completo) AGAINST ('+ $search') >0.01
            and tbl_usuarios_orgchart.nombre_empresa_holding<>''
            and tbl_usuarios_orgchart.rut<>'35000000'
            and tbl_usuarios_orgchart.rut<>'33000000'
            and tbl_usuarios_orgchart.rut<>'34000000'
            and tbl_usuarios_orgchart.rut<>'6972382'
            and tbl_usuarios_orgchart.vigencia_descripcion=''
        ORDER BY
            vigente DESC,
            relevance DESC
                 
                 ";
        //echo "<br>".$search;
        //echo "<br>".$sql;
        $connexion->query($sql);
        $cod = $connexion->resultset();
        //print_r($cod);exit();
    return $cod;
    }
    
function Talento_Personas_Search_Data($search){
        $connexion = new DatabasePDO();
        $sql="	
        
            SELECT
        tbl_usuario.*,
      MATCH (tbl_usuario.nombre_completo) AGAINST (:search) AS relevance
    
      FROM tbl_usuario
    
      WHERE MATCH (tbl_usuario.nombre_completo) AGAINST (:search) 
      and MATCH (tbl_usuario.nombre_completo) AGAINST (:search) >0
        and tbl_usuario.nombre_empresa_holding<>''
        
         ORDER BY relevance DESC, tbl_usuario.nombre_completo ASC";
    
        $connexion->query($sql);
        $connexion->bind(':search', $search);
        $cod = $connexion->resultset();
        return $cod;
    }
    
function Talento_Personas_Vigentes_NoVigentesSearch_Data($search){
        $connexion = new DatabasePDO();
        $sql="SELECT
                tbl_usuario_egresados.*,
                  MATCH (tbl_usuario_egresados.nombre_completo) AGAINST (:search) AS relevance,
                'no_vigente' as vigente
              FROM tbl_usuario_egresados
              WHERE MATCH (tbl_usuario_egresados.nombre_completo) AGAINST ('+' :search) 
                  and MATCH (tbl_usuario_egresados.nombre_completo) AGAINST ('+' :search) > 0.01
                and tbl_usuario_egresados.nombre_empresa_holding <> ''
                UNION 
                SELECT
                tbl_usuario.*,
                  MATCH (tbl_usuario.nombre_completo) AGAINST (:search) AS relevance,
                'vigente' as vigente
              FROM tbl_usuario
              WHERE MATCH (tbl_usuario.nombre_completo) AGAINST ('+' :search) 
                  and MATCH (tbl_usuario.nombre_completo) AGAINST ('+' :search) > 0.01
                and tbl_usuario.nombre_empresa_holding <> ''
                ORDER BY
                vigente DESC,
                relevance DESC";
        //echo $sql;
        $connexion->query($sql);
        $connexion->bind(':search', $search);
        $cod = $connexion->resultset();
        return $cod;
    }
    
function DatosUsuarioVigenteNoVigente($rut){
        $connexion = new DatabasePDO();
        $sql="SELECT *,
              'vigente' as vigente
              FROM tbl_usuario
              WHERE rut=:rut
              UNION 
              SELECT *,
              'no_vigente' as vigente
              FROM tbl_usuario_egresados
              WHERE rut=:rut";
        $connexion->query($sql);
        $connexion->bind(':rut', $rut);
        $cod = $connexion->resultset();
        return $cod;
    }
    

function Talento_Personas_Search_Cargo_Data($search) {

        $connexion = new DatabasePDO();
        $sql = "SELECT tbl_usuario.*, MATCH(tbl_usuario.cargo) AGAINST (:search) AS relevance
            FROM tbl_usuario
            WHERE MATCH(tbl_usuario.cargo) AGAINST (:search) > 0
            AND tbl_usuario.nombre_empresa_holding <> ''
            GROUP BY tbl_usuario.cargo
            ORDER BY relevance DESC, tbl_usuario.cargo ASC LIMIT 24";
        $connexion->query($sql);
        $connexion->bind(':search', $search);
        $cod = $connexion->resultset();
        return $cod;
    
    }
    
function Busca_Reco_MiEquipo($tipo, $id_empresa, $rut) {
    
        if ($tipo == "GRACIAS") {
            $qtip = "";
        } else {
            $qtip = " and h.mensaje <> '2017'";
        }
        
        if ($tipo == "RECONOCIMIENTO") {
            $Query_NoReco = " and (h.tipo = '$tipo' or h.tipo = 'COLABORACION')";
        } else {
            $Query_NoReco = " and h.tipo = '$tipo'";
        }
    
        $connexion = new DatabasePDO();
        $sql1_dest = "SELECT h.*, (SELECT id_empresa FROM tbl_reconoce_gracias_full WHERE id = h.id) AS id_empresa_full
            FROM tbl_reconoce_gracias h
            WHERE (h.rut_destinatario = :rut)
            $Query_NoReco
            AND h.estado = 'OK' $qtip
            AND (
                (SELECT id_empresa FROM tbl_reconoce_gracias_full WHERE id = h.id) IS NULL
                OR (SELECT id_empresa FROM tbl_reconoce_gracias_full WHERE id = h.id) = '78'
            )
            ORDER BY h.fecha DESC
            LIMIT 3";
        $connexion->query($sql1_dest);
        $connexion->bind(':rut', $rut);
        $cod1 = $connexion->resultset();
    
        $sql2_rem = "SELECT h.*
            FROM tbl_reconoce_gracias h
            WHERE (h.rut_remitente = :rut)
            AND h.tipo = '$tipo'
            AND h.estado = 'OK' $qtip
            ORDER BY h.fecha DESC
            LIMIT 3";
        $connexion->query($sql2_rem);
        $connexion->bind(':rut', $rut);
        $cod2 = $connexion->resultset();
    
        $cod_total = array_merge($cod1, $cod2);
        return $cod_total;
    
    }
    

function Busca_Reco_MiEquipo_Vista_jefe($tipo, $id_empresa, $jefe){

        $connexion = new DatabasePDO();
    
        $sql_0="select rut from tbl_usuario where jefe=:jefe";
        $connexion->query($sql_0);
        $connexion->bind(':jefe', $jefe);
        $cod0 = $connexion->resultset();
    
        $cuenta_or=0;
        $query_destinatario="";
        $query_destinatario.="( ";
    
        foreach ($cod0 as $uni){
            $query_destinatario.=" h.rut_destinatario='".$uni->rut."' ";
    
            $query_remitente.=" h.rut_remitente='".$uni->rut."' ";
    
            $cuenta_or++;
    
            if($cuenta_or<count($cod0)){
                $query_destinatario.=" or ";
                $query_remitente.=" or ";
            }
        }
    
        $query_destinatario.=" )";
        $cuenta++;
        if($tipo=="GRACIAS"){$qtip="";}else{$qtip=" and h.mensaje<>'2017'"; $qtip=" ";}
    
        $sql1_dest="select h.* from tbl_reconoce_gracias h 
            where $query_destinatario
            and h.tipo=:tipo 				
            and h.estado='OK'  $qtip 
            
            order by h.fecha DESC";
        $connexion->query($sql1_dest);
        $connexion->bind(':tipo', $tipo);
        $cod1 = $connexion->resultset();
    
        $sql2_rem="
            select h.* 
                from tbl_reconoce_gracias h 
                    where $query_remitente
                                    and h.tipo=:tipo  				
                                    and h.estado='OK'  $qtip 
            order by h.fecha DESC
            ";
        $connexion->query($sql2_rem);
        $connexion->bind(':tipo', $tipo);
        $cod2 = $connexion->resultset();
    
        $cod_total = ($cod1);
        return $cod_total;
    }
function Rec_Update_Reconocimiento_Amonestacion($rut_remitente, $rut_destinatario, $id_categoria, $categoria, $fecha, $hora, $id_empresa, $tipo, $puntos, $mensaje, $cui, $estado, $ida)
{
    //echo "Rec_Update_Reconocimiento_Amonestacion $rut_remitente, $rut_destinatario, id categoria $id_categoria, categoria $categoria, $fecha, $hora, $id_empresa, $tipo, $puntos, $mensaje, $cui, $estado, $ida";
    $mensaje = ($mensaje);
    $categoria = ($categoria);
    $hoy = date("Y-m-d");
    $Usua = DatosUsuario_($rut_destinatario, $id_empresa);
    $rut_jefatura = $Usua[0]->jefe;
    $division = $Usua[0]->division;
    $cargo = $Usua[0]->cargo;
    $area = $Usua[0]->area;
    $local = $Usua[0]->local;
    $departamento = $Usua[0]->departamento;
    $region = $Usua[0]->regional;
    /*echo "<br>Bind<br>";    echo "$rut_remitente,$rut_destinatario,$id_categoria,<br>$categoria,$fecha,$hora,<br>$id_empresa,$tipo,$mensaje,    <br>$estado,$cui,$rut_jefatura,<br>$division,$cargo,$area,<br>$local,$departamento,$region,<br>$hoy,$ida";*/
    $connexion = new DatabasePDO();
    $sql = "UPDATE tbl_reconoce_gracias SET
                    rut_remitente = :rut_remitente, 
                    rut_destinatario = :rut_destinatario, 
                    id_categoria = :id_categoria, 
                    categorita = :categoria, 
                    fecha = :fecha, 
                    hora = :hora, 
                    id_empresa = :id_empresa, 
                    tipo = :tipo, 
                    mensaje = :mensaje, 
                    estado = :estado, 
                    cui = :cui, 
                    rut_jefatura = :rut_jefatura, 
                    division = :division, 
                    cargo = :cargo, 
                    area = :area, 
                    local = :local, 
                    departamento = :departamento, 
                    region = :region, 
                    fecha_registro_jefe = :hoy
                WHERE id = :id";

    $connexion->query($sql);
    $connexion->bind(':rut_remitente', $rut_remitente);
    $connexion->bind(':rut_destinatario', $rut_destinatario);
    $connexion->bind(':id_categoria', $id_categoria);
    $connexion->bind(':categoria', $categoria);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':hora', $hora);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':tipo', $tipo);
    $connexion->bind(':mensaje', $mensaje);
    $connexion->bind(':estado', $estado);
    $connexion->bind(':cui', $cui);
    $connexion->bind(':rut_jefatura', $rut_jefatura);
    $connexion->bind(':division', $division);
    $connexion->bind(':cargo', $cargo);
    $connexion->bind(':area', $area);
    $connexion->bind(':local', $local);
    $connexion->bind(':departamento', $departamento);
    $connexion->bind(':region', $region);
    $connexion->bind(':hoy', $hoy);
    $connexion->bind(':id', $ida);
    $connexion->execute();
    //echo $sql;    exit();
    return $ida;
}
function Rec_Update_Reconocimiento_Amonestacion_gestor($ida)
{
    $connexion = new DatabasePDO();
    $hoy=date("Y-m-d");
    $sql = "UPDATE tbl_reconoce_gracias SET
                    estado = 'OK', 
                    fecha_registro_jefe = '$hoy'
                WHERE id = :id";

    $connexion->query($sql);
    $connexion->bind(':id', $ida);
    $connexion->execute();
    //echo $sql;    exit();
    return $ida;
}

function Rec_Inserta_Reconocimiento_Amonestacion($rut_remitente, $rut_destinatario, $id_categoria, $categoria, $fecha, $hora, $id_empresa, $tipo, $puntos, $mensaje, $cui, $estado)
{
    //echo "$rut_remitente,$rut_destinatario,$categoria,$fecha,$hora,$id_empresa,$tipo,$puntos,$mensaje, $rut_jefatura, $estado";
    $connexion = new DatabasePDO();
    $mensaje = ($mensaje);
    $categoria= ($categoria);

    if($tipo=="GRACIAS") {
        $puntos="0";
    }

    $hoy = date("Y-m-d");
    $Usua=DatosUsuario_($rut_destinatario, $id_empresa);
    $rut_jefatura=	$Usua[0]->jefe;
    $division	=	$Usua[0]->division;
    $cargo		=	$Usua[0]->cargo;
    $area			=	$Usua[0]->area;
    $local		=	$Usua[0]->local;
    $departamento		=	$Usua[0]->departamento;
    $region		=	$Usua[0]->regional;

    $sql = "INSERT INTO tbl_reconoce_gracias (rut_remitente, rut_destinatario, id_categoria, categorita, fecha, hora, id_empresa, tipo, puntos, mensaje, estado, cui, rut_jefatura, division, cargo, area, local, departamento, region, fecha_registro_jefe) VALUES (:rut_remitente, :rut_destinatario, :id_categoria, :categoria, :fecha, :hora, :id_empresa, :tipo, :puntos, :mensaje, :estado, :cui, :rut_jefatura, :division, :cargo, :area, :local, :departamento, :region, :hoy)";

    $connexion->query($sql);
    $connexion->bind(':rut_remitente', $rut_remitente);
    $connexion->bind(':rut_destinatario', $rut_destinatario);
    $connexion->bind(':id_categoria', $id_categoria);
    $connexion->bind(':categoria', $categoria);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':hora', $hora);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':tipo', $tipo);
    $connexion->bind(':puntos', $puntos);
    $connexion->bind(':mensaje', $mensaje);
    $connexion->bind(':estado', $estado);
    $connexion->bind(':cui', $cui);
    $connexion->bind(':rut_jefatura', $rut_jefatura);
    $connexion->bind(':division', $division);
    $connexion->bind(':cargo', $cargo);
    $connexion->bind(':area', $area);
    $connexion->bind(':local', $local);
    $connexion->bind(':departamento', $departamento);
    $connexion->bind(':region', $region);
    $connexion->bind(':hoy', $hoy);
    $connexion->execute();


    $sql ="SELECT id FROM tbl_reconoce_gracias WHERE rut_remitente=:rut_remitente AND rut_destinatario = :rut_destinatario AND id_empresa = :id_empresa ORDER BY id DESC LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':rut_remitente', $rut_remitente);
    $connexion->bind(':rut_destinatario', $rut_destinatario);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
    $cod = $connexion->resultset();

    return $cod[0]->id;
}

function Gestores_Amonestaciones_Pendientes($rut_gestor){
    $connexion = new DatabasePDO();

    $hoy = date("Y-m-d");
    $sql = "select h.*, j.rut_gestor 
                from tbl_reconoce_gracias h 
                    inner join tbl_gestores j on j.rut_gestor=:rut_gestor
										inner join tbl_usuario w on w.rut=h.rut_remitente
                where h.tipo='AMONESTACION' and h.estado='PENDIENTE'
                and j.cui=w.id_gerencia";
    //echo $sql;
    $connexion->query($sql);
    $connexion->bind(':rut_gestor', $rut_gestor);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;
}
function MiPerfil_2020_update_recibo($acr_deco){

    $connexion = new DatabasePDO();

    $hoy = date("Y-m-d");
    $sql = "UPDATE tbl_reconoce_gracias SET acuse_recibo=:hoy WHERE id=:acr_deco";

    $connexion->query($sql);
    $connexion->bind(':hoy', $hoy);
    $connexion->bind(':acr_deco', $acr_deco);
    $connexion->execute();

}

function Amonestacion2020_Tipo_Amonestacion($amonestacion){

    $Tipo_Amonestacion = "";

    if($amonestacion == "bch_leve") {
        $Tipo_Amonestacion = "Amonestación Leve";
    } else if($amonestacion == "bch_grave") {
        $Tipo_Amonestacion = "Amonestación Grave";
    }

    return $Tipo_Amonestacion;
}


function BuscaCountResultadosMiEquipo($rut, $id_empresa) {
    $connexion = new DatabasePDO();

    $date_hace_12_meses = date("Y-m-d", strtotime("-12 months"));

    $sql1 = "SELECT COUNT(id) as cuenta FROM tbl_reconoce_gracias WHERE rut_destinatario = :rut AND tipo = 'GRACIAS' AND estado = 'OK'";
    $connexion->query($sql1);
    $connexion->bind(':rut', $rut);
    $cod1 = $connexion->resultset();

    $sql2 = "SELECT COUNT(id) as cuenta FROM tbl_reconoce_gracias WHERE rut_destinatario = :rut AND (tipo = 'RECONOCIMIENTO' OR tipo = 'COLABORACION') AND estado = 'OK'";
    $connexion->query($sql2);
    $connexion->bind(':rut', $rut);
    $cod2 = $connexion->resultset();

    $sql3 = "SELECT COUNT(id) as cuenta FROM tbl_lms_reportes_full WHERE rut = :rut AND estado = 'APROBADO'";
    $connexion->query($sql3);
    $connexion->bind(':rut', $rut);
    $cod3 = $connexion->resultset();


    $sql5 = "SELECT COUNT(id) as cuenta FROM tbl_usuario WHERE jefe = :rut";
    $connexion->query($sql5);
    $connexion->bind(':rut', $rut);
    $cod5 = $connexion->resultset();

    $sql6 = "SELECT COUNT(id) as cuenta FROM tbl_badges_ganados WHERE rut = :rut";
    $connexion->query($sql6);
    $connexion->bind(':rut', $rut);
    $cod6 = $connexion->resultset();

    $sql9 = "SELECT COUNT(id) as cuenta FROM tbl_reconoce_gracias WHERE rut_destinatario = :rut AND tipo = 'AMONESTACION' AND estado = 'OK' AND fecha > :fecha";
    $connexion->query($sql9);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':fecha', $date_hace_12_meses);
    $cod9 = $connexion->resultset();

    $array[1] = $cod1['cuenta'];
    $array[2] = $cod2['cuenta'];
    $array[3] = $cod3['cuenta'];
    $array[4] = $cod4['cuenta'];
    $array[5] = $cod5['cuenta'];
    $array[6] = $cod6['cuenta'];
    $array[9] = $cod9['cuenta'];

    return $array;
}



function Potencial_Respuesta_Id_99($id){
    $connexion = new DatabasePDO();

    $sql = "UPDATE tbl_beneficios_formularios_respuestas SET id_empresa = '99' WHERE id = :id";
    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $connexion->execute();
}

function Potencial_Save_Beneficio_Data($id_form,$id_beneficio,$idc01,$idc02,$idc03,$idc04,$idc05,$idc06,$idc07,$idc08,$idc09,$idc10,$idc11,$idc12,$idc13,$idc14,$idc15,$idc16,$idc17,$idc18,$idc19,$idc20,
$idc21,$idc22,$idc23,$idc24,$idc25,$idc26,$id_empresa,$rut,$nombre,$cargo,$email,$division,$area,$cui,$glosa_cui,$departamento,$zona,$region,$rut_jefe,$archivo1,$archivo2,$archivo3,
$archivo4,$archivo5) {
        echo "<br>$id_form,$id_beneficio, idc01 $idc01,$idc02,idc 03 $idc03,$idc04,idc 05 $idc05, idc 06 $idc06,$idc07,$idc08,$idc09,$idc10,$idc11,$idc12,$idc13,$idc14,$idc15,$idc16,$idc17,$idc18,$idc19,$idc20,
        $idc21,$idc22,$idc23,$idc24,$idc25,$idc26,$id_empresa,<br>rut $rut,$nombre,$cargo,$email,$division,$area,$cui,$glosa_cui,$departamento,$zona,$region,$rut_jefe,$archivo1,$archivo2,$archivo3,
        $archivo4,$archivo5<br>";
        $connexion = new DatabasePDO();

        $fecha = date("Y-m-d");
        $hora = date("H:i:s");

        $sql_check = "SELECT id FROM tbl_potencial_formularios_respuestas WHERE rut=:rut AND id_form=:id_form";
        $connexion->query($sql_check);
        $connexion->bind(':rut', $rut);
        $connexion->bind(':id_form', $id_form);
        $cod_check = $connexion->resultset();

        if ($cod_check[0]->id>0) {
            $sql = "
                UPDATE tbl_potencial_formularios_respuestas SET 
                idc01=:idc01, idc02=:idc02, idc03=:idc03, idc04=:idc04, idc05=:idc05, idc06=:idc06, idc07=:idc07, idc08=:idc08, idc09=:idc09, idc10=:idc10, idc11=:idc11, idc12=:idc12, idc13=:idc13,
                idc14=:idc14, idc15=:idc15, idc16=:idc16, idc17=:idc17, idc18=:idc18, idc19=:idc19, idc20=:idc20, idc21=:idc21, idc22=:idc22, idc23=:idc23, idc24=:idc24, idc25=:idc25, idc26=:idc26, fecha=:fecha
                WHERE id=:id";

            $connexion->query($sql);
            $connexion->bind(':id', $cod_check->id);
        } else {
            $sql = "INSERT INTO tbl_potencial_formularios_respuestas (
                id_form,id_beneficio,idc01,idc02,idc03,idc04,idc05,idc06,idc07,idc08,idc09,idc10,idc11,idc12,idc13,idc14,idc15,idc16,idc17,idc18,idc19,idc20,idc21,idc22,idc23,idc24,idc25,idc26,id_empresa,
                fecha,hora,rut,nombre,cargo,email,division,area,cui,glosa_cui,departamento,zona,region,rut_jefe,archivo1,archivo2,archivo3,archivo4,archivo5
            ) VALUES (
                :id_form,:id_beneficio,:idc01,:idc02,:idc03,:idc04,:idc05,:idc06,:idc07,:idc08,:idc09,:idc10,:idc11, :idc12, :idc13, :idc14, :idc15, :idc16, :idc17, :idc18,
                :idc19, :idc20, :idc21, :idc22, :idc23, :idc24, :idc25, :idc26, :id_empresa, :fecha,
                :hora, :rut, :nombre, :cargo, :email, :division, :area, :cui, :glosa_cui, :departamento,
                :zona,:region,:rut_jefe,:archivo1,:archivo2,:archivo3,:archivo4,:archivo5)";




                $connexion->query($sql);
                $connexion->bind(':id_form', $id_form);
                $connexion->bind(':id_beneficio', $id_beneficio);
                $connexion->bind(':idc01', $idc01);
                $connexion->bind(':idc02', $idc02);
                $connexion->bind(':idc03', $idc03);
                $connexion->bind(':idc04', $idc04);
                $connexion->bind(':idc05', $idc05);
                $connexion->bind(':idc06', $idc06);
                $connexion->bind(':idc07', $idc07);
                $connexion->bind(':idc08', $idc08 );
                $connexion->bind(':idc09', $idc09 );
                $connexion->bind(':idc10', $idc10 );
                $connexion->bind(':idc11', $idc11 );
                $connexion->bind(':idc12', $idc12 );
                $connexion->bind(':idc13', $idc13 );
                $connexion->bind(':idc14', $idc14 );
                $connexion->bind(':idc15', $idc15 );
                $connexion->bind(':idc16', $idc16 );
                $connexion->bind(':idc17', $idc17 );
                $connexion->bind(':idc18', $idc18 );
                $connexion->bind(':idc19', $idc19 );
                $connexion->bind(':idc20', $idc20 );
                $connexion->bind(':idc21', $idc21 );
                $connexion->bind(':idc22', $idc22 );
                $connexion->bind(':idc23', $idc23 );
                $connexion->bind(':idc24', $idc24 );
                $connexion->bind(':idc25', $idc25 );
                $connexion->bind(':idc26', $idc26 );
                $connexion->bind(':id_empresa', $id_empresa );
                $connexion->bind(':fecha', $fecha );
                $connexion->bind(':hora', $hora );
                $connexion->bind(':rut', $rut );
                $connexion->bind(':nombre', $nombre );
                $connexion->bind(':cargo', $cargo );
                $connexion->bind(':email', $email );
                $connexion->bind(':division', $division );
                $connexion->bind(':area', $area );
                $connexion->bind(':cui', $cui );
                $connexion->bind(':glosa_cui', $glosa_cui );
                $connexion->bind(':departamento', $departamento );
                $connexion->bind(':zona', $zona );
                $connexion->bind(':region', $region );
                $connexion->bind(':rut_jefe', $rut_jefe );
                $connexion->bind(':archivo1', $archivo1 );
                $connexion->bind(':archivo2', $archivo2 );
                $connexion->bind(':archivo3', $archivo3 );
                $connexion->bind(':archivo4', $archivo4 );
                $connexion->bind(':archivo5', $archivo5 );
                $connexion->execute();

        }
    echo "$sql";exit();
}

?>