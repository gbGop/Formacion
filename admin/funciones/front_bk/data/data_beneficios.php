<?php
function Vestuario_Nomina_Botones($rut) {
    $connexion = new DatabasePDO();
    $sql = "SELECT id FROM tbl_vestuario_rut WHERE rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod[0]->id;
}

function Vestuario_Insert_Inscripcion_Rut_tbl_chileactivo_inscripcion($rut, $nombre_archivo, $id_empresa) {
    $connexion = new DatabasePDO();

    $hoy = date("Y-m-d");
    $hora = date("H:i:s");

    $sql = "INSERT INTO tbl_vestuario_declaracion_vigilantes (rut, fecha, archivo) VALUES (:rut, :fecha, :archivo)";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':fecha', $hoy);
    $connexion->bind(':archivo', $nombre_archivo);
    $connexion->execute();
}

function PopupFrontVestuario($modulo) {
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_vestuario_popup WHERE modulo = :modulo";
    $connexion->query($sql);
    $connexion->bind(':modulo', $modulo);
    $cod = $connexion->resultset();
    return ($cod);
}

function DownloadFile_2022_data($id) {
    $connexion = new DatabasePDO();
    $sql = "SELECT h.* FROM tbl_beneficios_archivos_url h WHERE h.id = :id";
    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $cod = $connexion->resultset();
    return $cod;
}
function FichaData_rut($rut) {
    $connexion = new DatabasePDO();
    $sql = "SELECT h.* FROM tbl_potencial_formularios_respuestas h WHERE h.id_form = '1' AND h.idc01 <>'' AND h.rut=:rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}

function FichaData_personales_rut($rut) {
    $connexion = new DatabasePDO();
    $sql = "SELECT h.* FROM tbl_usuario_cel_email h WHERE h.rut=:rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}

function BeneficiosPreguntasFrecuentes_data($idm) {
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_beneficios_pregunta_respuesta WHERE id_dimension=:idm";
    $connexion->query($sql);
    $connexion->bind(':idm', $idm);
    $cod = $connexion->resultset();
    return $cod;
}

function Buscatbl_vestuario_atributo($id_cargo) {
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_vestuario_atributo WHERE codigo_cargo=:id_cargo LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':id_cargo', $id_cargo);
    $cod = $connexion->resultset();
    return $cod;
}

function Busca_opc_uniforme($rut) {
    $connexion = new DatabasePDO();
    $sql = "SELECT COUNT(id) AS cuenta FROM tbl_usuario WHERE rut=:rut AND opc_uniforme='DEUNI00001'";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod[0]->cuenta;
}
function VestuarioUpdateRating($id, $rating){
    $connexion = new DatabasePDO();

    $hoy = date('Y-m-d');

    $update="UPDATE tbl_vestuario_usuario_talla 
        SET rating=:rating
        WHERE id=:id";
    
    $connexion->query($update);
    $connexion->bind(':rating', $rating);
    $connexion->bind(':id', $id);
    $connexion->execute();   
}

function VestuarioUpdateRecibidas($rut, $idpv, $recibido, $problema_talla, $comentarios){
    $connexion = new DatabasePDO();

    if($recibido=="SI"){
        $estado="Recibida Conforme";
    }
    if($recibido=="NO"){
        $estado="Recibida Disconforme";
    }
    
    $hoy = date('Y-m-d');

    $update="UPDATE tbl_vestuario_usuario_talla 
        SET recibida=:recibido,
            fecha_recibida=:hoy,
            comentario_talla=:problema_talla,
            comentario_recibida=:comentarios,
            estado=:estado
        WHERE id=:idpv";
    
    $connexion->query($update);
    $connexion->bind(':recibido', $recibido);
    $connexion->bind(':hoy', $hoy);
    $connexion->bind(':problema_talla', $problema_talla);
    $connexion->bind(':comentarios', $comentarios);
    $connexion->bind(':estado', $estado);
    $connexion->bind(':idpv', $idpv);
    $connexion->execute();
}

function Vestuario_TieneDatosTallaPorUsuarioPorPrendaTemporada($rut, $temporada, $periodo){
    $connexion = new DatabasePDO();

    $sql = "SELECT h.*, 
            (SELECT id_temporada FROM tbl_vestuario_prenda WHERE id=h.id_prenda) as id_temporada,
            (SELECT periodo FROM tbl_vestuario_prenda WHERE id=h.id_prenda) as periodo,
            (SELECT prenda FROM tbl_vestuario_prenda WHERE id=h.id_prenda) as prenda
            FROM tbl_vestuario_usuario_talla h
            WHERE h.rut=:rut
            AND (SELECT id_temporada FROM tbl_vestuario_prenda WHERE id=h.id_prenda)=:temporada
            AND (SELECT periodo FROM tbl_vestuario_prenda WHERE id=h.id_prenda)=:periodo";

    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':temporada', $temporada);
    $connexion->bind(':periodo', $periodo);
    $datos = $connexion->resultset();
    return $datos;
}
function VestuarioBuscaTemporada($temporada,$periodo){
	$connexion = new DatabasePDO();
	$sql= "SELECT temporada FROM tbl_vestuario_prenda WHERE id_temporada=:temporada LIMIT 1";
	$connexion->query($sql);
	$connexion->bind(':temporada', $temporada);
	$cod = $connexion->resultset();
	$temporada=$cod[0]->temporada." - ".$periodo;
	return $temporada;
}

function Vestuario_TieneDatosTallaPorUsuarioPorPrenda($rut, $id_prenda){
	$connexion = new DatabasePDO();
	$sql= "SELECT COUNT(id) as cuenta FROM tbl_vestuario_usuario_talla WHERE rut=:rut AND id_prenda=:id_prenda";
	$connexion->query($sql);
	$connexion->bind(':rut', $rut);
	$connexion->bind(':id_prenda', $id_prenda);
	$cod = $connexion->resultset();
	return $cod[0]->cuenta;
}

function VestuarioDelId($delp){
	$connexion = new DatabasePDO();
	$sql = "DELETE FROM tbl_vestuario_usuario_talla WHERE id=:id";
	$connexion->query($sql);
	$connexion->bind(':id', $delp);
	$connexion->execute();
}

function Vestuario_ActualizaEstado($id, $estado){
		$connexion = new DatabasePDO();
		$hoy = date('Y-m-d');
	if($estado=="ec"){
		$update="UPDATE tbl_vestuario_usuario_talla SET enconfeccion='1', fechaenconfeccion='".$hoy."' WHERE id='".$id."' ";
	}
	if($estado=="lr"){
		$update="UPDATE tbl_vestuario_usuario_talla SET listaretiro='1', fechaelistaretiro='".$hoy."' WHERE id='".$id."' ";
	}
	if($estado=="re"){
		$update="UPDATE tbl_vestuario_usuario_talla SET retirada='1', fecharetirada='".$hoy."' WHERE id='".$id."' ";
		}
		$connexion->query($update);
		$connexion->execute();
}
function VestuarioUpdateValidadas($rut){
    $connexion = new DatabasePDO();
    $update = "UPDATE tbl_vestuario_usuario_talla SET validacion = '1' WHERE rut = :rut";
    $connexion->query($update);
    $connexion->bind(':rut', $rut);
    $connexion->execute();
}

function Vestuario_MisPrendas($rut, $temporada, $segmento){
    $connexion = new DatabasePDO();
    $sql = "
        SELECT h.*, 
            (SELECT prenda FROM tbl_vestuario_prenda WHERE id = h.id_prenda) AS nombre_prenda,
            (SELECT descripcion FROM tbl_vestuario_prenda WHERE id = h.id_prenda) AS descripcion_prenda,
            (SELECT imagen FROM tbl_vestuario_prenda WHERE id = h.id_prenda) AS imagen_prenda
        FROM tbl_vestuario_usuario_talla h 
        WHERE h.rut = :rut AND h.temporada = :temporada AND h.segmento = :segmento";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':temporada', $temporada);
    $connexion->bind(':segmento', $segmento);
    $cod = $connexion->resultset();
    return $cod;
}

function Vestuario_TodasPrendasTemporadaSegmento($temporada){
    $connexion = new DatabasePDO();
    $sql = "
        SELECT h.*, 
            (SELECT rut_completo FROM tbl_usuario WHERE rut = h.rut) AS rut_completo,
            (SELECT nombre_completo FROM tbl_usuario WHERE rut = h.rut) AS nombre_completo,
            (SELECT email FROM tbl_usuario WHERE rut = h.rut) AS email,
            (SELECT prenda FROM tbl_vestuario_prenda WHERE id = h.id_prenda) AS nombre_prenda,
            (SELECT descripcion FROM tbl_vestuario_prenda WHERE id = h.id_prenda) AS descripcion_prenda,
            (SELECT imagen FROM tbl_vestuario_prenda WHERE id = h.id_prenda) AS imagen_prenda
        FROM tbl_vestuario_usuario_talla h 
        WHERE h.temporada = :temporada";
    $connexion->query($sql);
    $connexion->bind(':temporada', $temporada);
    $cod = $connexion->resultset();
    return $cod;
}
function VestuarioInsertUpdate($rut, $tipo_talla, $talla) {
    $connexion = new DatabasePDO();
    
    $sql = "SELECT * FROM tbl_vestuario_tallas_rut WHERE tipo_talla = :tipo_talla AND rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':tipo_talla', $tipo_talla);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
   
    if ($cod[0]->id > 0) {
        $update = "UPDATE tbl_vestuario_tallas_rut SET valor = :talla WHERE id = :id";
        $connexion->query($update);
        $connexion->bind(':talla', $talla);
        $connexion->bind(':id', $cod[0]->id);
        $connexion->execute();
    } else {
        $fecha = date("Y-m-d");
        $sql   = "INSERT INTO tbl_vestuario_tallas_rut (valor, tipo_talla, rut, fecha) VALUES (:talla, :tipo_talla, :rut, :fecha)";    
        $connexion->query($sql);
        $connexion->bind(':talla', $talla);
        $connexion->bind(':tipo_talla', $tipo_talla);
        $connexion->bind(':rut', $rut);
        $connexion->bind(':fecha', $fecha);
        $connexion->execute();
    }
    return ($avance); 	
}

function Uniformes_mi_cita_showroom($rut) {
    $connexion = new DatabasePDO();
    
    $sql = "SELECT * FROM tbl_beneficios_eventos WHERE id_agenda = '2000' AND ocupada = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}
function VestuarioVerSiTengotallas($rut){
	$connexion = new DatabasePDO();
	$sql = "SELECT COUNT(id) as cuenta FROM tbl_vestuario_usuario_talla WHERE rut = :rut";
	$connexion->query($sql);
	$connexion->bind(':rut', $rut);
	$cod = $connexion->resultset();
	return $cod[0]->cuenta;    
}

function BuscaBeneficios_2020($search){
	$connexion = new DatabasePDO();
	$search=utf8_decode($search);
	$sql="
		 SELECT h.*, h.id_beneficio as id_beneficios,
		(SELECT nombre FROM tbl_beneficios WHERE id_beneficios=h.id_beneficio) AS nombre, 
		(SELECT id_categoria FROM tbl_beneficios WHERE id_beneficios=h.id_beneficio) AS id_categoria, 
		MATCH (palabra_clave) AGAINST (:search) AS relevance 
		FROM tbl_beneficios_palabras_claves h 
		WHERE MATCH (h.palabra_clave) AGAINST (:search) AND MATCH (h.palabra_clave) AGAINST (:search)
		GROUP BY h.id_beneficio
		ORDER BY MATCH (palabra_clave) AGAINST (:search) DESC";

	$connexion->query($sql);
	$connexion->bind(':search', $search);
	$datos = $connexion->resultset();

	return $datos;
}

function VestuarioTraigoTallas($id){
	$connexion = new DatabasePDO();
	$sql = "SELECT * FROM tbl_vestuario_tallas";
	$connexion->query($sql);
	$cod = $connexion->resultset();
	return $cod;
}

function VestuarioTotalIngresados($rut, $temporada, $id_prenda){
	$connexion = new DatabasePDO();
	$sql= "SELECT
	tbl_vestuario_usuario_talla.*,
	tbl_vestuario_prenda.prenda AS nombre_prenda ,
	baseusuario.nombre_completo, baseusuario.cargo, id_cargo, baseusuario.division, baseusuario.email
	FROM
	tbl_vestuario_usuario_talla
	INNER JOIN tbl_vestuario_prenda ON tbl_vestuario_prenda.id = tbl_vestuario_usuario_talla.id_prenda
	INNER JOIN tbl_usuario as baseusuario ON baseusuario.rut = tbl_vestuario_usuario_talla.rut";
	$connexion->query($sql);
	$cod = $connexion->resultset();
	return $cod;
}
function VestuarioActualiza($rut, $id_prenda, $temporada, $talla, $comentario, $segmento, $maternal){
	$connexion = new DatabasePDO();
	$update="update tbl_vestuario_usuario_talla set talla=:talla, comentario=:comentario,
	segmento=:segmento, maternal=:maternal
	where rut=:rut and temporada=:temporada and id_prenda=:id_prenda";
	$connexion->query($update);
	$connexion->bind(':rut', $rut);
	$connexion->bind(':temporada', $temporada);
	$connexion->bind(':id_prenda', $id_prenda);
	$connexion->bind(':talla', $talla);
	$connexion->bind(':comentario', $comentario);
	$connexion->bind(':segmento', $segmento);
	$connexion->bind(':maternal', $maternal);
	$connexion->execute();
	return ($avance);
}

function VestuarioInserta($rut, $id_prenda, $temporada, $talla, $comentario, $segmento, $maternal){
	$connexion = new DatabasePDO();
	$fecha = date("Y-m-d");
	$hora = date("H:i:s");
	$sql = "insert into tbl_vestuario_usuario_talla (rut, temporada, id_prenda, talla, fecha, hora, comentario, segmento, maternal)
	VALUES (:rut, :temporada, :id_prenda, :talla, :fecha, :hora, :comentario, :segmento, :maternal)";
	$connexion->query($sql);
	$connexion->bind(':rut', $rut);
	$connexion->bind(':temporada', $temporada);
	$connexion->bind(':id_prenda', $id_prenda);
	$connexion->bind(':talla', $talla);
	$connexion->bind(':fecha', $fecha);
	$connexion->bind(':hora', $hora);
	$connexion->bind(':comentario', $comentario);
	$connexion->bind(':segmento', $segmento);
	$connexion->bind(':maternal', $maternal);
	$connexion->execute();
}

function TieneDatosTallaPorUsuarioPorPrenda($rut, $temporada, $id_prenda){
	$connexion = new DatabasePDO();
	$sql= "select * from tbl_vestuario_usuario_talla where rut=:rut and id_prenda=:id_prenda and temporada=:temporada";
	$connexion->query($sql);
	$connexion->bind(':rut', $rut);
	$connexion->bind(':temporada', $temporada);
	$connexion->bind(':id_prenda', $id_prenda);
	$cod = $connexion->resultset();
	return $cod;
}

function Vestuario_tipo_Talla($tipo_talla){
	$connexion = new DatabasePDO();
	$sql= "select * from tbl_vestuario_tallas where tipo_talla=:tipo_talla";
	$connexion->query($sql);
	$connexion->bind(':tipo_talla', $tipo_talla);
	$cod = $connexion->resultset();
	return $cod;
}
function Vestuario_tipo_Talla_rut($tipo_talla, $rut){
    $connexion = new DatabasePDO();

    $sql = "SELECT valor FROM tbl_vestuario_tallas_rut WHERE tipo_talla=:tipo_talla AND rut=:rut";
    $connexion->query($sql);
    $connexion->bind(':tipo_talla', $tipo_talla);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();

    return $cod[0]->valor;	
}

function VestuarioTraePrendas($segmento, $temporada){
    $connexion = new DatabasePDO();

    $sql = "SELECT tbl_vestuario_prenda_temporada_segmento.*, tbl_vestuario_prenda.prenda, bloque_comentario, tbl_vestuario_prenda.tipo_talla, tbl_vestuario_prenda.imagen, tbl_vestuario_prenda.descripcion, tbl_vestuario_prenda.encuentra_talla
            FROM tbl_vestuario_prenda_temporada_segmento
            INNER JOIN tbl_vestuario_prenda ON tbl_vestuario_prenda.id=tbl_vestuario_prenda_temporada_segmento.id_prenda
            WHERE tbl_vestuario_prenda_temporada_segmento.temporada=:temporada AND tbl_vestuario_prenda.segmento=:segmento";

    $connexion->query($sql);
    $connexion->bind(':temporada', $temporada);
    $connexion->bind(':segmento', $segmento);
    $cod = $connexion->resultset();

    return $cod;
}

function Vestuario_Trae_prendas_dada_Grupo($segmento, $temporada, $periodo, $grupo_prenda, $rut){
    $connexion = new DatabasePDO();

    $sql = "SELECT h.*, (SELECT maternal FROM tbl_vestuario_usuario_talla WHERE id_prenda=h.id AND rut=:rut LIMIT 1) AS maternal
            FROM tbl_vestuario_prenda h
            WHERE h.id_temporada=:temporada AND h.segmento=:segmento AND h.periodo=:periodo AND h.tipo_prenda=:grupo_prenda";

    $connexion->query($sql);
    $connexion->bind(':temporada', $temporada);
    $connexion->bind(':segmento', $segmento);
    $connexion->bind(':periodo', $periodo);
    $connexion->bind(':grupo_prenda', $grupo_prenda);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();

    return $cod;
}

function Vestuario_Trae_Grupos_prendas($segmento, $temporada, $periodo){
    $connexion = new DatabasePDO();

    $sql = "SELECT * FROM tbl_vestuario_prenda WHERE id_temporada=:temporada AND segmento=:segmento AND periodo=:periodo GROUP BY tipo_prenda";

    $connexion->query($sql);
    $connexion->bind(':temporada', $temporada);
    $connexion->bind(':segmento', $segmento);
    $connexion->bind(':periodo', $periodo);
    $cod = $connexion->resultset();

    return $cod;
}
function VestuarioObtenerSegmento($genero){
	$connexion = new DatabasePDO();
	$sql= "SELECT * FROM tbl_vestuario_segmento WHERE genero = :genero LIMIT 1";
	$connexion->query($sql);
	$connexion->bind(':genero', $genero);
	$cod = $connexion->resultset();
	return $cod;
}

function Vestuario_temporada_activa(){
	$connexion = new DatabasePDO();
	$sql= "SELECT * FROM tbl_vestuario_temporada_activa ORDER BY id DESC LIMIT 1";
	$connexion->query($sql);
	$cod = $connexion->resultset();
	return $cod;
}

function VestuarioObtenerTemporada($mes){
	$connexion = new DatabasePDO();
	$sql= "SELECT * FROM tbl_vestuario_temporada WHERE mes = :mes";
	$connexion->query($sql);
	$connexion->bind(':mes', $mes);
	$cod = $connexion->resultset();
	return $cod;
}

function Beneficios_Busca_Todos_beneficios($id_empresa){
	$connexion = new DatabasePDO();
	$sql = "SELECT * FROM tbl_beneficios WHERE id_empresa = :id_empresa ORDER BY nombre ASC";
	$connexion->query($sql);
	$connexion->bind(':id_empresa', $id_empresa);
	$cod = $connexion->resultset();
	return $cod;
}

function Beneficios_Evento_AnularId($id){
	$connexion = new DatabasePDO();
	$hoy = date('Y-m-d');
	$update="UPDATE tbl_beneficios_eventos SET descripcion = 'Disponible', link_acceso = '?sw=calendario_event&idr={IDEVENTO}', target = '1', postulable = 'SI', ocupada = '', nombre_completo = '', modalidad = '' WHERE id_evento = :id";
	$connexion->query($update);
	$connexion->bind(':id', $id);
	$connexion->execute();
}
function Beneficios_busca_MisPostulaciones($rut, $id_empresa) {
    $connexion = new DatabasePDO();
    $sql = "
        SELECT
            h.id_agenda as id_dato_1,
            h.id_especialista as id_dato_2,
            h.fecha_inicio as fecha_realizada,
            h.hora_inicio as hora_realizada,
            NULL as rut_jefe,
            descripcion as estado,
            NULL as validacion1,
            NULL as validacion2,
            NULL as validacion3,
            NULL as validacion4,
            NULL as fechavalidacion1,
            NULL as comentarios_validacion,
            'Atencion_Profesional' as tipo,
            h.id as id_resp,
            NULL as id_form
        FROM tbl_beneficios_eventos h
        WHERE h.id_empresa=:id_empresa AND h.ocupada=:rut
        UNION
        SELECT
            h.id_form as id_dato_1,
            h.id_beneficio as id_dato_2,
            h.fecha as fecha_realizada,
            h.hora as hora_realizada,
            rut_jefe as rut_jefe,
            estado as estado,
            validacion1 as validacion1,
            validacion2 as validacion2,
            validacion3 as validacion3,
            validacion4 as validacion4,
            fechavalidacion1 as fechavalidacion1,
            comentarios_validacion as comentarios_validacion,
            'Solicitud_Beneficios' as tipo,
            h.id as id_resp,
            h.id_form as id_form
        FROM tbl_beneficios_formularios_respuestas h
        WHERE h.id_empresa=:id_empresa AND h.rut=:rut
        ORDER BY fecha_realizada DESC
    ";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}
function Chileactivo_mis_encuestas_data($id_empresa) {
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_enc_elearning_medicion WHERE id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function Beneficios_Busca_Forms_groupbydimension($id_empresa) {
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_beneficios_formularios WHERE (descripcion='Beneficios' OR descripcion='Imprimir') GROUP BY dimension";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}

function Beneficios_Busca_Forms_Dimension($id_empresa,$dimension) {
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_beneficios_formularios WHERE (descripcion='Beneficios' OR descripcion='Impresion') AND dimension = :dimension";
    $connexion->query($sql);
    $connexion->bind(':dimension', $dimension);
    $cod = $connexion->resultset();
    return $cod;
}

function Beneficios_Busca_Forms($id_empresa) {
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_beneficios_formularios WHERE descripcion='Beneficios'";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}

function Beneficios_BuscaIdAgenda($id) {
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_beneficios_agenda WHERE id = :id";
    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $cod = $connexion->resultset();
    return $cod;
}

function Beneficios_BuscaIdEspecialista($id) {
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_beneficios_especialistas WHERE rut = :id";
    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $cod = $connexion->resultset();
    return $cod;
}

function Beneficios_CheckDatosPersonas($rut){
	$connexion = new DatabasePDO();
	$sql="select * from tbl_usuario_cel_email where rut=:rut";
	$connexion->query($sql);
	$connexion->bind(':rut', $rut);
	$cod = $connexion->resultset();
	return $cod;
}

function Beneficios_BuscaFormulario($id){
	$connexion = new DatabasePDO();
	$sql="select * from tbl_beneficios_formularios where id=:id";
	$connexion->query($sql);
	$connexion->bind(':id', $id);
	$cod = $connexion->resultset();
	return $cod;
}

function Beneficios_BuscaIdBeneficios($id){
	$connexion = new DatabasePDO();
	$sql="select * from tbl_beneficios where id_beneficios=:id";
	$connexion->query($sql);
	$connexion->bind(':id', $id);
	$cod = $connexion->resultset();
	return $cod;
}

function Beneficios_Agendamiento_2020_datos_particulares($rut){
	$connexion = new DatabasePDO();
	$sql="select * from tbl_usuario_cel_email where rut=:rut";
	$connexion->query($sql);
	$connexion->bind(':rut', $rut);
	$cod = $connexion->resultset();
	return $cod;
}

function Beneficios_Busca_Agendas($id_empresa){
	$connexion = new DatabasePDO();
	$sql="select * from tbl_beneficios where id_agenda is not null and id_agenda<>'2000'";
	$connexion->query($sql);
	$cod = $connexion->resultset();
	return $cod;
}

function Beneficios_Evento_Update_Fecha_Ficha($id_evento){
	$connexion = new DatabasePDO();
	$hoy = date('Y-m-d');
	$update="update tbl_beneficios_eventos set fecha_ficha=:hoy where id_evento=:id_evento";
	$connexion->query($update);
	$connexion->bind(':hoy', $hoy);
	$connexion->bind(':id_evento', $id_evento);
	$connexion->execute();

}
function Beneficios_Especialista_Tbl($rut, $id_empresa){
    $connexion = new DatabasePDO();
    $sql="select h.* from tbl_beneficios_especialistas h where h.id_empresa=:id_empresa and h.rut=:rut";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}

function Chileactivo_BuscaRespuesta($rut, $id_empresa, $id_beneficio_inscripcion){
    $connexion = new DatabasePDO();
    $sql="select id from tbl_beneficios_formularios_respuestas where id_empresa=:id_empresa and rut=:rut and id_beneficio=:id_beneficio_inscripcion limit 1";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_beneficio_inscripcion', $id_beneficio_inscripcion);
    $cod = $connexion->resultset();
    return $cod[0]->id;
}

function Noticias_2020_publicadas_data($id_empresa){
    $connexion = new DatabasePDO();
    $sql = "select tbl_noticias.* from tbl_noticias where tbl_noticias.id_empresa=:id_empresa and slider_activo='0' and principal=0 and estado=1 order by orden is null, orden asc, fecha_creacion desc";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function Noticias_Vestuario_2020_publicadas_data($id_empresa){
    $connexion = new DatabasePDO();
    $sql = "select tbl_noticias_vestuario.* from tbl_noticias_vestuario where tbl_noticias_vestuario.id_empresa=:id_empresa and slider_activo='0' and principal=0 and estado=1 order by orden is null, orden asc, fecha_creacion desc";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function Noticias_2020_publicadas_id_noticia_data($id_noticia,$id_empresa){
    $connexion = new DatabasePDO();
    $sql = "select tbl_noticias.* from tbl_noticias where tbl_noticias.id_empresa=:id_empresa and slider_activo='0' and principal=0 and estado=1 and id=:id_noticia order by orden is null, orden asc, fecha_creacion desc";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_noticia', $id_noticia);
    $cod = $connexion->resultset();
    return $cod;
}
function Noticias_Vestuario_2020_publicadas_id_noticia_data($id_noticia,$id_empresa){
    $connexion = new DatabasePDO();
    $sql = "SELECT tbl_noticias_vestuario.* FROM tbl_noticias_vestuario WHERE tbl_noticias_vestuario.id_empresa=:id_empresa AND slider_activo='0' AND principal=0 AND estado=1 AND id=:id_noticia ORDER BY orden IS NULL, orden ASC, fecha_creacion DESC";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_noticia', $id_noticia);
    $cod = $connexion->resultset();
    return $cod;
}

function Chileactivo_Busca_Dimension_por_tipo($id_empresa, $tipo){
    $connexion = new DatabasePDO();
    $sql = "SELECT h.* FROM tbl_chileactivo h WHERE h.id_empresa=:id_empresa AND h.plantilla=:tipo GROUP BY h.id_dimension ORDER BY dimension";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':tipo', $tipo);
    $cod = $connexion->resultset();
    return $cod;
}

function Vestuario_Busca_Dimension_por_tipo($id_empresa, $tipo){
    $connexion = new DatabasePDO();
    $sql = "SELECT h.* FROM tbl_vestuario h WHERE h.id_empresa=:id_empresa AND h.plantilla=:tipo GROUP BY h.id_dimension ORDER BY orden";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':tipo', $tipo);
    $cod = $connexion->resultset();
    return $cod;
}

/*function Chileactivo_Busca_Dimension_por_tipo($id_empresa, $tipo){ 
    $connexion = new DatabasePDO();
    $sql = "SELECT h.* FROM tbl_chileactivo h WHERE h.id_empresa=:id_empresa AND h.plantilla=:tipo GROUP BY h.id_dimension ORDER BY dimension";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':tipo', $tipo);
    $cod = $connexion->resultset();
    return $cod;
} */


function Videoteca_2020_publicadas_data($id_empresa) {
  $connexion = new DatabasePDO();
  $sql = "SELECT tbl_noticias.*
          FROM tbl_noticias
          WHERE tbl_noticias.id_empresa = :id_empresa
          AND slider_activo = '0'
          AND principal = 1
          AND estado = 1
          ORDER BY orden IS NULL, orden ASC, fecha_creacion DESC";
  $connexion->query($sql);
  $connexion->bind(':id_empresa', $id_empresa);
  $cod = $connexion->resultset();
  return $cod;
}

function Videoteca_2020_publicadas_id_noticia_data($id_noticia, $id_empresa) {
  $connexion = new DatabasePDO();
  $sql = "SELECT tbl_noticias.*
          FROM tbl_noticias
          WHERE tbl_noticias.id_empresa = :id_empresa
          AND slider_activo = '0'
          AND principal = 1
          AND estado = 1
          AND id = :id_noticia
          ORDER BY orden IS NULL, orden ASC, fecha_creacion DESC";
  $connexion->query($sql);
  $connexion->bind(':id_empresa', $id_empresa);
  $connexion->bind(':id_noticia', $id_noticia);
  $cod = $connexion->resultset();
  return $cod;
}

function Chileactivo_Categoria_cantidad_id_dimension($id_dimension, $id_empresa) {
  $connexion = new DatabasePDO();
  $sql = "SELECT COUNT(h.id) AS cuenta
          FROM tbl_chileactivo h
          WHERE h.id_empresa = :id_empresa
          AND h.id_dimension = :id_dimension";
  $connexion->query($sql);
  $connexion->bind(':id_empresa', $id_empresa);
  $connexion->bind(':id_dimension', $id_dimension);
  $cod = $connexion->resultset();
  return $cod[0]->cuenta;
}

function Beneficios_Especialistas_por_Agenda($id_agenda, $id_empresa){
  $connexion = new DatabasePDO();
  $sql = "SELECT h.* FROM tbl_beneficios_especialistas h WHERE h.id_empresa = :id_empresa AND h.id_agenda = :id_agenda ORDER BY h.nombre ASC";
  $connexion->query($sql);
  $connexion->bind(':id_empresa', $id_empresa);
  $connexion->bind(':id_agenda', $id_agenda);
  $cod = $connexion->resultset();
  return $cod;
  }
  
function Beneficios_Agenda_Vista_Full($id_agenda){
  $connexion = new DatabasePDO();
  $sql = "SELECT h.* FROM tbl_beneficios_agenda h WHERE h.id = :id_agenda";
  $connexion->query($sql);
  $connexion->bind(':id_agenda', $id_agenda);
  $cod = $connexion->resultset();
  return $cod;
}
  
function BeneAgenda_2020_Save_Evento($idr, $rut, $nombre_completo, $modalidad, $id_empresa){
  $connexion = new DatabasePDO();
  $update = "UPDATE tbl_beneficios_eventos SET descripcion = 'Ocupada', modalidad = :modalidad, ocupada = :rut, icono = 'danger', postulable = 'NO', target = '', link_acceso = '', nombre_completo = :nombre_completo WHERE id_evento = :idr";
  $connexion->query($update);
  $connexion->bind(':modalidad', $modalidad);
  $connexion->bind(':rut', $rut);
  $connexion->bind(':nombre_completo', $nombre_completo);
  $connexion->bind(':idr', $idr);
  $connexion->execute();
}
  
function Ben_Agenda($id_empresa, $id_agenda){
  $connexion = new DatabasePDO();
  $sql = "SELECT h.* FROM tbl_beneficios_agenda h WHERE h.id_empresa = :id_empresa AND h.id = :id_agenda";
  $connexion->query($sql);
  $connexion->bind(':id_empresa', $id_empresa);
  $connexion->bind(':id_agenda', $id_agenda);
  $cod = $connexion->resultset();
  return $cod;
}

  function Ben_Especialista($id_empresa, $rut_especialista) {
    $connexion = new DatabasePDO();
    $sql = "SELECT h.* FROM tbl_beneficios_especialistas h WHERE h.id_empresa = :id_empresa AND h.rut = :rut_especialista";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut_especialista', $rut_especialista);
    $cod = $connexion->resultset();
    return $cod;
}

function Beneficios_DatosBeneficio($id_beneficio, $id_empresa) {
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_beneficios WHERE id_beneficios = :id_beneficio AND id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_beneficio', $id_beneficio);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function Beneficios_BuscaIdBeneficio_Dado_IdAgenda($id_agenda, $id_empresa) {
    $connexion = new DatabasePDO();
    $sql = "SELECT id_beneficios FROM tbl_beneficios WHERE id_agenda = :id_agenda AND id_empresa = :id_empresa LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':id_agenda', $id_agenda);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return ($cod[0]->id_beneficios);
}

function Chileactivo_DatosChileactivo($id_beneficio, $id_empresa) {
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_chileactivo WHERE id_beneficios = :id_beneficio AND id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_beneficio', $id_beneficio);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function Beneficios_Agenda_2020_VistaEvento($idr){
  $connexion = new DatabasePDO();
  $sql="select h.*,
  (select nombre from tbl_beneficios_agenda where id=h.id_agenda) as agenda,
  (select nombre from tbl_beneficios_especialistas where rut=h.id_especialista) as especialista,
  (select email from tbl_beneficios_especialistas where rut=h.id_especialista) as email_especialista
  from tbl_beneficios_eventos h where h.id=:idr";
  $connexion->query($sql);
  $connexion->bind(':idr', $idr);
  $cod = $connexion->resultset();
  return $cod;
  }
  
function Agenda_Eventos_2020_id_agenda_especialista($id_empresa, $id_agenda, $id_especialista, $filtro_cui, $filtro_rut, $filtro_tipo){
  $connexion = new DatabasePDO();
  $queryTipo = "";
  $queryRUT = "";
  $queryRegistro="";
  
  if ($tipo <> '') {
      $queryTipo = " and id_categoria=:tipo";
  }
  
  if($nombrelikecui<>''){
      $queryCui = " and nombre like :filtro_cui";
  }
  
  if($filtro_rut<>''){
      $queryRUT = " and descripcion like :filtro_rut";
  }
  
  if($filtro_tipo<>''){
      $queryRegistro = " and icono = :filtro_tipo";
  }
  
  if($id_agenda<>''){
      $Query_Agenda = " and id_agenda = :id_agenda";
  }
  
  if($id_especialista<>''){
      $Query_Especialista = " and id_especialista = :id_especialista";
  }
  
  $hoy = date("Y-m-d");
  $sql = "select h.*
          from tbl_beneficios_eventos h 
          where h.id_empresa=:id_empresa $Query_Agenda $Query_Especialista  $queryCui $queryRUT $queryRegistro
          and h.fecha_inicio>=:hoy";
  $connexion->query($sql);
  $connexion->bind(':id_empresa', $id_empresa);
  $connexion->bind(':id_agenda', $id_agenda);
  $connexion->bind(':id_especialista', $id_especialista);
  $connexion->bind(':filtro_cui', '%'.$filtro_cui.'%');
  $connexion->bind(':filtro_rut', '%'.$filtro_rut.'%');
  $connexion->bind(':filtro_tipo', $filtro_tipo);
  $connexion->bind(':hoy', $hoy);
  $cod = $connexion->resultset();
  return $cod;
  }

function EVENTOS_TraeEventos($id_empresa, $tipo, $filtro_cui, $filtro_rut, $filtro_tipo)
  {
  $connexion = new DatabasePDO();
  $queryTipo = "";
  $queryCui = "";
  $queryRUT = "";
  $queryRegistro = "";
  
  if ($tipo <>'') {
      $queryTipo = " AND id_categoria = :tipo";
  }
  
  if ($filtro_cui <>'') {
      $queryCui = " AND nombre LIKE :filtro_cui";
  }
  
  if ($filtro_rut <>'') {
      $queryRUT = " AND descripcion LIKE :filtro_rut";
  }
  
  if ($filtro_tipo <>'') {
      $queryRegistro = " AND icono = :filtro_tipo";
  }
  
  $sql = "SELECT * FROM tbl_eventos WHERE id_empresa = :id_empresa $queryTipo $queryCui $queryRUT $queryRegistro";
  
  $connexion->query($sql);
  $connexion->bind(':id_empresa', $id_empresa);
  $connexion->bind(':tipo', $tipo);
  $connexion->bind(':filtro_cui', '%' . $filtro_cui . '%');
  $connexion->bind(':filtro_rut', '%' . $filtro_rut . '%');
  $connexion->bind(':filtro_tipo', $filtro_tipo);
  
  $datos = $connexion->resultset();
  
  return $datos;
  }
  
function Chileactivo_Curso_Full_por_IdPrograma($rut, $id_programa, $id_empresa)
  {
  $connexion = new DatabasePDO();
  $sql = "SELECT h.* FROM rel_lms_malla_curso h WHERE h.id_programa = :id_programa AND h.id_empresa = :id_empresa";
  
  $connexion->query($sql);
  $connexion->bind(':id_programa', $id_programa);
  $connexion->bind(':id_empresa', $id_empresa);
  $cod = $connexion->resultset();
  
  return $cod;
  }
  
function Beneficio_BuscaCargas($rut)
  {
  $connexion = new DatabasePDO();
  
  $sql = "SELECT * FROM tbl_beneficios_cargas WHERE rut = :rut";
  
  $connexion->query($sql);
  $connexion->bind(':rut', $rut);
  $cod = $connexion->resultset();
  
  return $cod;
  }

function BeneficiosSavedatoPersonales_data($rut, $celular, $email, $direccion, $comuna, $ciudad, $region, $tipo) {
    $connexion = new DatabasePDO();

    $hoy = date("Y-m-d");
    $hora = date("H:i:s");

    $rut = utf8_decode($rut);
    $celular = utf8_decode($celular); 
    $email = utf8_decode($email);
    $direccion = utf8_decode($direccion);
    $comuna = utf8_decode($comuna); 
    $ciudad = utf8_decode($ciudad);
    $region = utf8_decode($region);

    if ($tipo == "Insert") {
        $sql = "INSERT INTO tbl_usuario_cel_email (rut, celular, email_particular, region, ciudad, comuna, direccion) 
                VALUES (:rut, :celular, :email, :region, :ciudad, :comuna, :direccion)";
        $connexion->query($sql);
        $connexion->bind(':rut', $rut);
        $connexion->bind(':celular', $celular);
        $connexion->bind(':email', $email);
        $connexion->bind(':region', $region);
        $connexion->bind(':ciudad', $ciudad);
        $connexion->bind(':comuna', $comuna);
        $connexion->bind(':direccion', $direccion);
    }

    if ($tipo == "Update") {
        $sql = "UPDATE tbl_usuario_cel_email SET celular = :celular, email_particular = :email, region = :region, ciudad = :ciudad, comuna = :comuna, direccion = :direccion
                WHERE rut = :rut";
        $connexion->query($sql);
        $connexion->bind(':rut', $rut);
        $connexion->bind(':celular', $celular);
        $connexion->bind(':email', $email);
        $connexion->bind(':region', $region);
        $connexion->bind(':ciudad', $ciudad);
        $connexion->bind(':comuna', $comuna);
        $connexion->bind(':direccion', $direccion);
    }       

    $connexion->execute();
}

function Beneficio_BuscaIDBeneficioDadoForm($id_form) {
    $connexion = new DatabasePDO();

    $sql = "SELECT id_beneficios FROM tbl_beneficios WHERE (id_form1 = :id_form OR id_form2 = :id_form)";

    $connexion->query($sql);
    $connexion->bind(':id_form', $id_form);
    $cod = $connexion->resultset();
    return $cod[0]->id_beneficios;
}

function Beneficio_Respuesta_Id_99($id) {
    $connexion = new DatabasePDO();

    $sql = "UPDATE tbl_beneficios_formularios_respuestas SET id_empresa = '99' WHERE id = :id";

    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $connexion->execute();
}

function Beneficio_Tipo_Field_WithValue_data($idl, $name_field) {
  $connexion = new DatabasePDO();
  $sql = "SELECT $name_field as data FROM tbl_beneficios_formularios_respuestas WHERE id=:id LIMIT 1";
  $connexion->query($sql);
  $connexion->bind(':id', $idl);
  $cod = $connexion->resultset();
  return $cod[0]->data;
}

function Beneficio_Id_Full_data($idl) {
  $connexion = new DatabasePDO();
  $sql = "SELECT * FROM tbl_beneficios_formularios_respuestas WHERE id=:id LIMIT 1";
  $connexion->query($sql);
  $connexion->bind(':id', $idl);
  $cod = $connexion->resultset();
  return $cod;
}

function Chileactivo_Check_Inscripcion_Beneficios_Form_respuesta_Form_data($rut, $id_beneficios, $id_form, $id_empresa){
  $connexion = new DatabasePDO();
  $sql = "SELECT COUNT(id) as cuenta FROM tbl_beneficios_formularios_respuestas WHERE rut=:rut AND id_beneficio=:id_beneficios AND id_form=:id_form AND id_empresa=:id_empresa";
  $connexion->query($sql);
  $connexion->bind(':rut', $rut);
  $connexion->bind(':id_beneficios', $id_beneficios);
  $connexion->bind(':id_form', $id_form);
  $connexion->bind(':id_empresa', $id_empresa);
  $cod = $connexion->resultset();
  return $cod[0]->cuenta;
}

function Chileactivo_Busca_Inscripcion_Rut_tbl_chileactivo_inscripcion($rut, $id_empresa){
  $connexion = new DatabasePDO();
  $sql = "SELECT id FROM tbl_chileactivo_inscripcion WHERE rut=:rut AND id_empresa=:id_empresa";
  $connexion->query($sql);
  $connexion->bind(':rut', $rut);
  $connexion->bind(':id_empresa', $id_empresa);
  $cod = $connexion->resultset();
  return $cod[0]->id;
}

function Chileactivo_Insert_Inscripcion_Rut_tbl_chileactivo_inscripcion($rut, $nombre_archivo, $id_empresa){
  $connexion = new DatabasePDO();
  $hoy = date("Y-m-d");
  $hora = date("H:i:s");
  
  $sql = "INSERT INTO tbl_chileactivo_inscripcion (rut, fecha, hora, documento, id_empresa) 
          VALUES (:rut, :fecha, :hora, :documento, :id_empresa)";
  
  $connexion->query($sql);
  $connexion->bind(':rut', $rut);
  $connexion->bind(':fecha', $hoy);
  $connexion->bind(':hora', $hora);
  $connexion->bind(':documento', $nombre_archivo);
  $connexion->bind(':id_empresa', $id_empresa);
  $connexion->execute();
  
  $sql = "UPDATE tbl_chileactivo_deinscripcion SET id_empresa='99' WHERE rut=:rut";
  $connexion->query($sql);
  $connexion->bind(':rut', $rut);
  $connexion->execute();
  }
  
function Chileactivo_Insert_Inscripcion_Rut_tbl_chileactivo_desinscripcion($rut, $nombre_archivo, $motivo, $id_empresa){
  $connexion = new DatabasePDO();
  $hoy = date("Y-m-d");
  $hora = date("H:i:s");
  
  $sql = "INSERT INTO tbl_chileactivo_deinscripcion (rut, fecha, hora, documento, motivo, id_empresa) 
          VALUES (:rut, :fecha, :hora, :documento, :motivo, :id_empresa)";
  
  $connexion->query($sql);
  $connexion->bind(':rut', $rut);
  $connexion->bind(':fecha', $hoy);
  $connexion->bind(':hora', $hora);
  $connexion->bind(':documento', $nombre_archivo);
  $connexion->bind(':motivo', $motivo);
  $connexion->bind(':id_empresa', $id_empresa);
  $connexion->execute();
  
  $sql = "UPDATE tbl_chileactivo_inscripcion SET id_empresa='99' WHERE rut=:rut";
  $connexion->query($sql);
  $connexion->bind(':rut', $rut);
  $connexion->execute();
  }
  
  function Chileactivo_Check_Fields_Llenar_tbl_usuario_cel_email($rut){
  $connexion = new DatabasePDO();
  
  $sql = "SELECT * FROM tbl_usuario_cel_email WHERE rut=:rut";
  $connexion->query($sql);
  $connexion->bind(':rut', $rut);
  $cod = $connexion->resultset();
  return $cod;
  }

function Chileactivo_Update_Data_personal_tbl_usuario_cel_email($rut, $celular,$email_particular,$region,$ciudad,$comuna,$direccion){
    $connexion = new DatabasePDO();
    
    $sql = "DELETE FROM tbl_usuario_cel_email  where rut=:rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->execute();
    
    $celular = utf8_decode($celular);
    $email_particular = utf8_decode($email_particular);
    $region = utf8_decode($region);
    $ciudad = utf8_decode($ciudad);
    $comuna = utf8_decode($comuna);
    $direccion = utf8_decode($direccion);
    
    $sql = "INSERT INTO tbl_usuario_cel_email (rut, celular,email_particular,region,ciudad,comuna,direccion) 
        VALUES (:rut, :celular, :email_particular, :region, :ciudad, :comuna, :direccion)";
    
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':celular', $celular);
    $connexion->bind(':email_particular', $email_particular);
    $connexion->bind(':region', $region);
    $connexion->bind(':ciudad', $ciudad);
    $connexion->bind(':comuna', $comuna);
    $connexion->bind(':direccion', $direccion);
    $connexion->execute();
    
    $cod = $connexion->rowCount();
    return $cod;
    }
    
function Datos_Programa($tipo, $id_empresa, $perfil_usuario){
    $connexion = new DatabasePDO();
    
    $sql="SELECT * 
      FROM tbl_carrusel_home_clasificaciones_2020 
      WHERE tipo=:tipo AND id_empresa=:id_empresa AND (perfil=:perfil_usuario OR perfil IS NULL) 
      ORDER BY orden ASC";
    
    $connexion->query($sql);
    $connexion->bind(':tipo', $tipo);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':perfil_usuario', $perfil_usuario);
    $connexion->execute();
    
    $cod = $connexion->resultset();
    return $cod;
}
    
function Netflix_Lista_Cursos_Por_Programa_v2($id_programa, $id_empresa){
    $connexion = new DatabasePDO();
    
    $sql = "SELECT h.* 
        FROM rel_lms_malla_curso h 
        WHERE h.id_programa=:id_programa AND h.id_empresa=:id_empresa";
    
    $connexion->query($sql);
    $connexion->bind(':id_programa', $id_programa);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
    
    $cod = $connexion->resultset();
    return $cod;		
    }

function BuscaNomina2020Giftcard($rut, $tipo, $id_empresa){
      $id_empresa = "78";
      $connexion = new DatabasePDO();
      $sql = "SELECT * FROM tbl_nomina_accesos_2020 WHERE tipo = :tipo AND rut = :rut AND id_empresa = :id_empresa";
      $connexion->query($sql);
      $connexion->bind(':tipo', $tipo);
      $connexion->bind(':rut', $rut);
      $connexion->bind(':id_empresa', $id_empresa);
      $cod = $connexion->resultset();
      return $cod;
  }
?>