<?php
function SelectCount_Tickets_SaveNuevoTicket_data_2020($rut) {
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    $sql = "SELECT count(id) as cuenta FROM tbl_tickets WHERE rut=:rut AND fecha=:fecha AND estado='pendiente'";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':fecha', $fecha);
    $cod = $connexion->resultset();
    return $cod[0]->cuenta;
}
function Tickets_Autoatencion_Search_Question_IDPregunta_2021($id_pregunta, $id_empresa) {
$connexion = new DatabasePDO();
$sql = "SELECT  tbl_tickets_preguntas_2021.*    FROM tbl_tickets_preguntas_2021 WHERE id=:id_pregunta   LIMIT 1";
$connexion->query($sql);
$connexion->bind(':id_pregunta', $id_pregunta);
$cod = $connexion->resultset();
return $cod;
}
function Insert_Tbl_Tickets_Preguntas_Usuario_data($pregunta, $rut, $id_empresa){
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $hora  = date("H:i:s");
    $pregunta = utf8_decode($pregunta);
    $sql = "INSERT INTO tbl_tickets_preguntas_usuario (rut, pregunta, fecha, hora, id_empresa) 
            VALUES (:rut, :pregunta, :fecha, :hora, :id_empresa)";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':pregunta', $pregunta);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':hora', $hora);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
}

function Tickets_Top10_data(){
    $connexion = new DatabasePDO();
    $id_empresa = "78";
    $sql = "SELECT * FROM tbl_tickets_preguntas_2021 WHERE top_ten IS NOT NULL ORDER BY top_ten ASC";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}

function Tickets_Autoatencion_Search_Question_FullTexto_2021($pregunta, $id_empresa){
    $connexion = new DatabasePDO();
    $pregunta  = ($pregunta);
    $sql = "SELECT *,
            MATCH(pregunta1,pregunta2,pregunta3,pregunta4,pregunta5,pregunta6,pregunta7,pregunta8,pregunta9,pregunta10)
            AGAINST (:pregunta) AS relevance
            FROM tbl_tickets_preguntas_2021
            WHERE MATCH(pregunta1,pregunta2,pregunta3,pregunta4,pregunta5,pregunta6,pregunta7,pregunta8,pregunta9,pregunta10)
            AGAINST (:pregunta IN BOOLEAN MODE)
            AND id_empresa = :id_empresa
            LIMIT 10";

    $connexion->query($sql);
    $connexion->bind(':pregunta', $pregunta);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}
function Tickets_Autoatencion_Search_Question_FullTexto($pregunta, $id_empresa) {
    $connexion = new DatabasePDO();
    $pregunta = utf8_decode($pregunta);

    $queryFiltro="";

    $sql = "
    SELECT
    tbl_tickets_preguntas.*,
    (select respuesta from tbl_tickets_respuestas where id_respuesta=tbl_tickets_preguntas.id_respuesta) as Respuesta,
    (select tip_ejecutivo from tbl_tickets_respuestas where id_respuesta=tbl_tickets_preguntas.id_respuesta) as TipEjecutivo,
    MATCH (pregunta) AGAINST (:pregunta) AS relevance,
    (SELECT tip_ejecutivo FROM tbl_tickets_respuestas WHERE id_respuesta = tbl_tickets_preguntas.id_respuesta) AS TipEjecutivo,
    MATCH (subcategoria) AGAINST (:pregunta) AS relevance2
    FROM tbl_tickets_preguntas
    WHERE MATCH (pregunta) AGAINST (:pregunta) and MATCH (subcategoria) AGAINST (:pregunta) >0

    $queryFiltro
    GROUP BY tbl_tickets_preguntas.id_respuesta

    ORDER BY (MATCH (pregunta) AGAINST (:pregunta) + MATCH (subcategoria) AGAINST (:pregunta)) DESC
    LIMIT 12;
    ";

    //echo "<br>Tickets_Autoatencion_Search_Question_FullTexto<br>".$sql."<br>";
    $connexion->query($sql);
    $connexion->bind(':pregunta', $pregunta);
    $cod = $connexion->resultset();

    //print_r($cod);

    return $cod;
}

function Tickets_Autoatencion_Search_Question_IDPregunta($id_pregunta, $id_empresa) {
    $connexion = new DatabasePDO();

    $sql = "
    SELECT
    tbl_tickets_preguntas.*,
    (select respuesta from tbl_tickets_respuestas where id_respuesta=tbl_tickets_preguntas.id_respuesta) as Respuesta,
    (select tip_ejecutivo from tbl_tickets_respuestas where id_respuesta=tbl_tickets_preguntas.id_respuesta) as TipEjecutivo
    FROM tbl_tickets_preguntas
    WHERE id_pregunta=:id_pregunta
    LIMIT 1;
    ";

    //echo "<br>Tickets_Autoatencion_Search_Question_IDPregunta<br>".$sql."<br>";
    $connexion->query($sql);
    $connexion->bind(':id_pregunta', $id_pregunta);
    $cod = $connexion->resultset();

    //print_r($cod);

    return $cod;
}

function Tickets_2020_Busca_3_destacados($id_empresa) {
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_tickets_preguntas WHERE destacado = '1'";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}

function Tickets_2020_BuscaRating_Respuesta($rut, $id_respuesta, $id_empresa) {
    $connexion = new DatabasePDO();
    $sql = "SELECT rating, COUNT(id) as cuenta FROM tbl_tickets_respuestas_rating WHERE id_respuesta = :id_respuesta AND rut = :rut GROUP BY rating";
    $connexion->query($sql);
    $connexion->bind(':id_respuesta', $id_respuesta);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}

function Tickets_SaveRatingRespuestaTicket_data_2020($rut, $id_respuesta, $rating, $id_empresa) {
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    $sql = "INSERT INTO tbl_tickets_respuestas_rating (id_respuesta, rut, rating, fecha, hora, id_empresa) VALUES (:id_respuesta, :rut, :rating, :fecha, :hora, :id_empresa)";
    $connexion->query($sql);
    $connexion->bind(':id_respuesta', $id_respuesta);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':rating', $rating);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':hora', $hora);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
}

function Tickets_SaveRatingRespuestaTicket_data_2023($rut, $id_respuesta, $rating, $id_empresa, $comentario) {
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    $comentario = utf8_decode($comentario);
    $sql = "INSERT INTO tbl_tickets_respuestas_rating (id_respuesta, rut, rating, fecha, hora, id_empresa, comentario) VALUES (:id_respuesta, :rut, :rating, :fecha, :hora, :id_empresa, :comentario)";
    $connexion->query($sql);
    $connexion->bind(':id_respuesta', $id_respuesta);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':rating', $rating);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':hora', $hora);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':comentario', $comentario);
    $connexion->execute();
}

function Tickets_SaveRating_Update_RespuestaTicket_data_2020($rut, $idt, $rating, $id_empresa) {
$connexion = new DatabasePDO();
$fecha = date("Y-m-d");
$hora = date("H:i:s");
$sql = "UPDATE tbl_tickets SET rating_cierre = :rating WHERE id = :idt";
$connexion->query($sql);
$connexion->bind(':rating', $rating);
$connexion->bind(':idt', $idt);
$connexion->execute();
}

function Tickets_SaveNuevoTicket_DadoIdTicket_data_2020($rut, $id_ticket, $estado, $id_empresa, $asunto){
    $connexion = new DatabasePDO();
    $asunto = utf8_decode($asunto);
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    $sql = "
    INSERT INTO tbl_tickets_mis_tickets (rut, id_ticket, estado, id_empresa, fecha,hora, asunto)
    VALUES (:rut, :id_ticket, :estado, :id_empresa, :fecha, :hora, :asunto)";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_ticket', $id_ticket);
    $connexion->bind(':estado', $estado);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':hora', $hora);
    $connexion->bind(':asunto', $asunto);
    $connexion->execute();
}
    
function Tickets_Cerrar_Ticket_data_2020_Ext($idt, $rut) {
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    $sql = "  
    update tbl_tickets set estado='cerrado', fecha_cierre=:fecha_cierre, hora_cierre=:hora_cierre, rut_cierre=:rut_cierre
    where id=:idt";
    $connexion->query($sql);
    $connexion->bind(':fecha_cierre', $fecha);
    $connexion->bind(':hora_cierre', $hora);
    $connexion->bind(':rut_cierre', $rut);
    $connexion->bind(':idt', $idt);
    $connexion->execute();
}
    
function Tickets_ReAbierto_Ticket_data_2020_Ext($idt) {
    $connexion = new DatabasePDO();
    $sql = "
    update tbl_tickets set estado='reabierto'
    where id=:idt";
    $connexion->query($sql);
    $connexion->bind(':idt', $idt);
    $connexion->execute();
}
    
function Tickets_Cerrar_Ticket_data_2020($idt, $rut) {
    $connexion = new DatabasePDO();
    $sql = "
    update tbl_tickets_mis_tickets set estado='cerrado'
    where id_ticket=:idt";
    $connexion->query($sql);
    $connexion->bind(':idt', $idt);
    $connexion->execute();
}
function Tickets_Insert_Interaccion_2020($id, $interaccion, $archivo, $rut, $perfil, $id_empresa) {
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    $interaccion = utf8_decode($interaccion);

    $sql = "INSERT INTO tbl_tickets_comentarios (id_ticket, rut, id_empresa, comentario, fecha, hora, archivo, perfil)
    VALUES (:id, :rut, :id_empresa, :interaccion, :fecha, :hora, :archivo, :perfil)";

    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':interaccion', $interaccion);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':hora', $hora);
    $connexion->bind(':archivo', $archivo);
    $connexion->bind(':perfil', $perfil);
    $connexion->execute();
}

function Tickets_Detalle_id_Data($id, $id_empresa) {
    $connexion = new DatabasePDO();
    $sql = "SELECT h.* FROM tbl_tickets h WHERE h.id_empresa = :id_empresa AND h.id = :id";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id', $id);
    $cod = $connexion->resultset();
    return $cod;
}

function Tickets_BuscaAdmin($rut) {
    $connexion = new DatabasePDO();
    $sql = "SELECT h.* FROM tbl_admin h WHERE h.user = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}
function Tickets_SaveNuevoTicket_data_2020($asunto, $descripcion, $id_categoria, $id_subcategoria, $archivo, $rut , $id_empresa, $nombre_completo, $cargo, $email, $division, $empresa, $archivo2) {
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    $asunto = utf8_decode($asunto);
    $descripcion = utf8_decode($descripcion);
    $estado = "pendiente";

    $sql = "SELECT id FROM tbl_tickets WHERE rut=:rut AND fecha=:fecha AND asunto=:asunto ORDER BY id DESC LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':asunto', $asunto);
    $cod = $connexion->resultset();
    //print_r($cod);
    //echo "sql $sql";exit();

    if ($cod[0]->id > 0) {

    } else {
        $sql = "INSERT INTO tbl_tickets (rut, fecha, hora, id_categoria, id_subcategoria, asunto, descripcion, archivo, estado, fecha_estado, rut_ejecutivo, id_empresa, nombre_completo, cargo, email, division, empresa, archivo2)
        VALUES (:rut, :fecha, :hora, :id_categoria, :id_subcategoria, :asunto, :descripcion, :archivo, :estado, '', '', :id_empresa, :nombre_completo, :cargo, :email, :division, :empresa, :archivo2)";
        //echo "sql $sql";exit();
        $connexion->query($sql);
        $connexion->bind(':rut', $rut);
        $connexion->bind(':fecha', $fecha);
        $connexion->bind(':hora', $hora);
        $connexion->bind(':id_categoria', $id_categoria);
        $connexion->bind(':id_subcategoria', $id_subcategoria);
        $connexion->bind(':asunto', $asunto);
        $connexion->bind(':descripcion', $descripcion);
        $connexion->bind(':archivo', $archivo);
        $connexion->bind(':estado', $estado);
        $connexion->bind(':id_empresa', $id_empresa);
        $connexion->bind(':nombre_completo', $nombre_completo);
        $connexion->bind(':cargo', $cargo);
        $connexion->bind(':email', $email);
        $connexion->bind(':division', $division);
        $connexion->bind(':empresa', $empresa);
        $connexion->bind(':archivo2', $archivo2);
        $connexion->execute();
        $cod = $connexion->lastInsertId();
    }

    $sql = "SELECT id FROM tbl_tickets WHERE rut=:rut AND fecha=:fecha AND asunto=:asunto ORDER BY id DESC LIMIT 1";
    //echo "sql $sql";exit();
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':asunto', $asunto);
    $cod = $connexion->resultset();

    return $cod[0]->id;
}
function Tickets_Busca_Ejecutivo_2020($id_empresa) {
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_admin WHERE id_empresa = :id_empresa AND email <> '' AND go = '1' LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function Tickets_Comentarios_Tickets_2020($idt) {
    $connexion = new DatabasePDO();
    $cod = array();
    if($idt != "") {
        $sql = "SELECT h.*, (SELECT rut FROM tbl_tickets WHERE id = h.id_ticket) AS rut_ticket FROM tbl_tickets_comentarios h WHERE h.id_ticket = :idt AND h.perfil <> '3' ORDER BY h.id DESC";
        $connexion->query($sql);
        $connexion->bind(':idt', $idt);
        $cod = $connexion->resultset();
    }
    $rut_tickets = !empty($cod[0]->rut_ticket) ? $cod[0]->rut_ticket : "";
    if($rut_tickets != "" && $rut_tickets == $_SESSION["user_"]) {
        //do nothing
    } else {
        if(count($cod) > 0) {
            if($rut_tickets != "" && $rut_tickets == $_SESSION["user_"]) {
                //do nothing
            } else {
                $cod = "";
                echo "<script>alert('Acceso prohibido'); location.href='?sw=cap_2021';</script>"; exit();
            }
        } else {
            $cod = "";
        }
    }
    return $cod;
}

function Tickets_Info_Tickets_2020($idt) {
    $connexion = new DatabasePDO();
    $cod = array();
    $rut_tickets = "";
    $sql = "SELECT * FROM tbl_tickets WHERE id = :idt";
    if($idt <>"") {
        $connexion->query($sql);
        $connexion->bind(':idt', $idt);
        $cod = $connexion->resultset();
    }
    $rut_tickets=$cod[0]->rut;
    if($rut_tickets <>"" and $rut_tickets == $_SESSION["user_"]) {
        
    } else {
        $cod = "";
        echo "<script>alert('Acceso prohibido'); location.href='?sw=cap_2021';</script>"; exit();
    }
    return $cod;
}

function Tickets_2020_mis_requerimientos($rut, $id_empresa) {
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_tickets WHERE id_empresa = :id_empresa AND rut = :rut ORDER BY id DESC";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}