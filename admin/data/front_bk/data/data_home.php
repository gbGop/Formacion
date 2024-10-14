<?php
function LinkEnc_Redirect_enc_data($tipo){
    $connexion = new DatabasePDO();
    $sql = "SELECT LinkEnc_Redirect FROM tbl_LinkEnc_Redirect_enc WHERE tipo = :tipo";
    $connexion->query($sql);
    $connexion->bind(':tipo', $tipo);
    $cod = $connexion->resultset();
    return $cod[0]->LinkEnc_Redirect;
}
function LogExcepciones($rut){
    $connexion = new DatabasePDO();
    $sql = "SELECT id FROM tbl_login_excepciones WHERE rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod[0]->id;
}
function LogEmailExternos_acceso_data($rut){
    $connexion = new DatabasePDO();
    $sql = "SELECT id FROM tbl_externos_acceso WHERE rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod[0]->id;
}

function LogEmailExternos_acceso_Lista_data($rut){
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_externos_acceso WHERE rut = '10192901'";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}

function LogEmail_Log_Login_data_home($email, $email_request, $rut, $error) {
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    $sql = "
    INSERT INTO tbl_log_login (fecha,hora,email,email_request,rut, error)
    VALUES (:fecha, :hora, :email, :email_request, :rut, :error)";
    $connexion->query($sql);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':hora', $hora);
    $connexion->bind(':email', $email);
    $connexion->bind(':email_request', $email_request);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':error', $error);
    $connexion->execute();
    //echo "    <script>         location.href='?sw=Tickets_AutoAtencion_2020&sa_rating=1';     </script>"; exit;
}
function Map2021_accesos($rut){
    $connexion = new DatabasePDO();
    $sql1="SELECT id FROM tbl_nomina_accesos_2020 WHERE rut=:rut AND tipo='map'";
    $sql2="SELECT id FROM tbl_nomina_accesos_2020 WHERE rut=:rut AND tipo='map_validacion'";
    $sql3="SELECT id FROM tbl_nomina_accesos_2020 WHERE rut=:rut AND tipo='map_revisor'";
    $sql4="SELECT id FROM tbl_nomina_accesos_2020 WHERE rut=:rut AND tipo='map_reporte'";
    $sql5="SELECT id FROM tbl_nomina_accesos_2020 WHERE rut=:rut AND tipo='map_reporte_v2'";
    $connexion->query($sql1);
    $connexion->bind(':rut', $rut);
    $cod1 = $connexion->resultset();
    $connexion->query($sql2);
    $connexion->bind(':rut', $rut);
    $cod2 = $connexion->resultset();
    $connexion->query($sql3);
    $connexion->bind(':rut', $rut);
    $cod3 = $connexion->resultset();
    $connexion->query($sql4);
    $connexion->bind(':rut', $rut);
    $cod4 = $connexion->resultset();
    $connexion->query($sql5);
    $connexion->bind(':rut', $rut);
    $cod5 = $connexion->resultset();
    $arreglo[1]=$cod1[0]->id;
    $arreglo[2]=$cod2[0]->id;
    $arreglo[3]=$cod3[0]->id;
    $arreglo[4]=$cod4[0]->id;
    $arreglo[5]=$cod5[0]->id;
    return $arreglo;
}

function MapUpdate_2021($foto, $validacion, $comentario_validacion){
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    $comentario_validacion=utf8_decode("comentario_validacion");
    $sql = "UPDATE tbl_map_of_2020 SET validacion_comentario=:comentario_validacion, validacion=:validacion, fecha_validacion=:fecha WHERE foto=:foto";
    $connexion->query($sql);
    $connexion->bind(':comentario_validacion', $comentario_validacion);
    $connexion->bind(':validacion', $validacion);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':foto', $foto);
    $connexion->execute();
}
function map_of_2020_check_foto_id_foto_data($foto){
    $connexion = new DatabasePDO();
    $sql="SELECT h.*, (SELECT nombre_oficina FROM tbl_map_of_2020_revisor WHERE foto = h.foto LIMIT 1) AS nombre_oficina
          FROM tbl_map_of_2020 h
          WHERE foto=:foto
          GROUP BY foto";
    $connexion->query($sql);
    $connexion->bind(':foto', $foto);
    $cod = $connexion->resultset();
    return $cod;
}

function BuscaRespuestasEncuesta_idEncuesta($id_encuesta, $rut){
    $connexion = new DatabasePDO();
    $sql = "SELECT id FROM tbl_enc_elearning_respuestas WHERE rut = :rut AND id_encuesta = :id_encuesta LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $cod = $connexion->resultset();
    return $cod[0]->id;
}

function BuscaNominaEncuesta_idEncuesta($id_encuesta, $rut){
    $connexion = new DatabasePDO();
    $sql = "SELECT id FROM tbl_nomina_accesos_2020 WHERE id_encuesta = :id_encuesta AND rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod[0]->id;
}

function BuscaNominaEncuesta79($rut){
    $connexion = new DatabasePDO();
    $sql = "SELECT id FROM tbl_nomina_accesos_2020 WHERE id_encuesta = '79' AND rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod[0]->id;
}

function BuscaRespuestasEncuesta79($rut){
    $connexion = new DatabasePDO();
    $sql = "SELECT id FROM tbl_enc_elearning_respuestas WHERE rut = :rut AND id_encuesta = '79' LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod[0]->id;
}
function Promedio_ObjetosFinalizados_no_Opcionales($rut, $id_curso){
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*, (SELECT cm FROM tbl_objeto WHERE id=h.id_objeto) as cm
            FROM tbl_objetos_finalizados h
            WHERE h.rut=:rut AND h.id_curso=:id_curso AND h.nota>0
            AND ((SELECT cm FROM tbl_objeto WHERE id=h.id_objeto)<>'DIAGNOSTICO'
            OR (SELECT cm FROM tbl_objeto WHERE id=h.id_objeto) IS NULL)";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_curso', $id_curso);
    $cod = $connexion->resultset();
    return $cod;
}

function Objetos_Existe_SiguienteObjeto($id_objeto){
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*, (SELECT id FROM tbl_objeto WHERE id_curso=h.id_curso AND orden=(h.orden+1)) as siguiente 
            FROM tbl_objeto h WHERE h.id=:id_objeto";
    $connexion->query($sql);
    $connexion->bind(':id_objeto', $id_objeto);
    $cod = $connexion->resultset();
    return $cod[0]->siguiente;        
}

function map_of_2020_by_rut_list_limit1_revisor($foto, $rut){
    $connexion = new DatabasePDO();
    $sql = "SELECT close_revisor FROM tbl_map_of_2020 WHERE rut_revisor=:rut AND foto=:foto LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':foto', $foto);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod[0]->close_revisor;        
}

function map_of_2020_by_rut_list_limit1_revisor_foto_dadorut($foto){
    $connexion = new DatabasePDO();
    $sql = "SELECT rut FROM tbl_map_of_2020_revisor WHERE foto=:foto LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':foto', $foto);
    $cod = $connexion->resultset();
    return $cod[0]->rut;        
}
function map_of_2020_revisor($rut){
    $connexion = new DatabasePDO();
    $sql="	
        select h.*, 
        (select nombre_completo from tbl_usuario where rut=h.rut) as nombre_completo,
        (select close from tbl_map_of_2020 where foto=h.foto  and rut=h.rut and close='1' limit 1) as cerrada, 
        (SELECT count(id) AS cuenta FROM tbl_map_of_2020 WHERE foto=h.foto AND rut=h.rut AND close_revisor='1') AS cerrada_revisor,
        (SELECT count(id) AS cuenta FROM tbl_map_of_2020 WHERE foto=h.foto AND rut=h.rut) AS total,
        (select id from tbl_map_of_2020 where foto=h.foto and rut=h.rut and numero_serie<>'' limit 1) as id1,
        (select fecha from tbl_nomina_accesos_2020 where rut=h.rut AND id_tipo=h.foto) AS fecha_asignacion
        from tbl_map_of_2020_revisor h where h.rut_revisor=:rut
        order by h.nombre_oficina ASC";

    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;		
}

function map_of_2020_validacion($rut){
    $connexion = new DatabasePDO();
    $sql="	
        select j.*, 
        (SELECT nombre_oficina FROM tbl_map_of_2020_revisor where foto=j.foto limit 1) AS nombre_oficina
        from  tbl_map_of_2020 j
        where j.rut=:rut and j.close='1' and j.close_revisor='1'
        group by j.foto
        order by (SELECT nombre_oficina FROM tbl_map_of_2020_revisor where foto=j.foto limit 1) ASC";

    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;		
}
function map_of_2020_by_rut($id_cuadricula, $foto, $rut){
	
    $connexion = new DatabasePDO();
	
    $sql="	
    	select 
    	* 
    	from tbl_map_of_2020 
    	where 
    	rut=:rut and foto=:foto and id_cuadricula=:id_cuadricula
    	limit 1";
	  
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':foto', $foto);
    $connexion->bind(':id_cuadricula', $id_cuadricula);
    $cod = $connexion->resultset();
    return $cod;		
	
}

function map_of_2020_updaterevisor($id){
	
    $connexion = new DatabasePDO();
	
    $sql="	
    	update tbl_map_of_2020 
    	set accion=NULL,
    	requerimiento=NULL,
    	rut_revisor=NULL,
    	fecha_revisor=NULL,
    	close_revisor=NULL
    	where id=:id";
	 
    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $cod = $connexion->execute();
    return $cod;	
	
}

function map_of_2020_delete($id){
	
    $connexion = new DatabasePDO();
	
    $sql="	
    	delete from tbl_map_of_2020 
    	where id=:id";
	 
    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $cod = $connexion->execute();
    return $cod;	
	
}

function map_of_2020_update_close($foto, $rut){
	
    $connexion = new DatabasePDO();
	
    $sql="	
    	update tbl_map_of_2020 
    	set close='1'
    	where rut=:rut and foto=:foto";
	  
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':foto', $foto);
    $cod = $connexion->execute();
    return $cod;	
	
}

function map_of_2020_download_data(){
	
    $connexion = new DatabasePDO();
	
    $sql="select * from tbl_map_of_2020";
	  
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;		
	
}
function map_of_2020_download_v2_data(){
    $connexion = new DatabasePDO();
    $sql="SELECT h.*,
        (SELECT fecha FROM tbl_nomina_accesos_2020 WHERE rut=h.rut AND id_tipo=h.foto) AS fecha_carga,
        (SELECT COUNT(id) FROM tbl_map_of_2020 WHERE rut=h.rut AND foto=h.foto) AS resp,
        IF ((SELECT close FROM tbl_map_of_2020 WHERE rut=h.rut AND foto=h.foto LIMIT 1)='1','RESPONDIDO',
            IF((SELECT COUNT(id) FROM tbl_map_of_2020 WHERE rut=h.rut AND foto=h.foto)>'0','EN_PROCESO','NO_RESPONDIDO')
        ) AS estado_encuestado,
        IF ((SELECT close_revisor FROM tbl_map_of_2020 WHERE rut=h.rut AND foto=h.foto LIMIT 1)='1','VALIDADO_POR_REVISOR',
            IF((SELECT COUNT(id) FROM tbl_map_of_2020 WHERE rut=h.rut AND foto=h.foto AND rut_revisor=h.rut_revisor)>'0','EN_PROCESO','NO_VALIDADO')
        ) AS estado_revisor
        FROM tbl_map_of_2020_revisor h
        ORDER BY h.rut";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}

function map_of_2020_by_rut_list_limit1($foto, $rut){
    $connexion = new DatabasePDO();
    $sql="SELECT close FROM tbl_map_of_2020 WHERE rut=:rut AND foto=:foto LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':foto', $foto);
    $cod = $connexion->resultset();
    return $cod[0]->close;
}
function map_of_2020_by_rut_list($foto, $rut){
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_map_of_2020 WHERE rut=:rut AND foto=:foto ORDER BY id_cuadricula";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':foto', $foto);
    $cod = $connexion->resultset();
    return $cod;   
}

function map_of_2020_save_data($rut, $id_empresa, $fecha_ahora, $numero_serie, $tipo_impresora, $tipo_conexion, $cantidad_bandejas, $mueble, $observaciones, $id_cuadricula, $foto) {
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $sql = "INSERT INTO tbl_map_of_2020 (rut, fecha, numero_serie, tipo_impresora, tipo_conexion, cantidad_bandejas, mueble, observaciones, id_cuadricula, foto) 
	VALUES (:rut, :fecha_ahora, :numero_serie, :tipo_impresora, :tipo_conexion, :cantidad_bandejas, :mueble, :observaciones, :id_cuadricula, :foto)";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':fecha_ahora', $fecha_ahora);
    $connexion->bind(':numero_serie', $numero_serie);
    $connexion->bind(':tipo_impresora', $tipo_impresora);
    $connexion->bind(':tipo_conexion', $tipo_conexion);
    $connexion->bind(':cantidad_bandejas', $cantidad_bandejas);
    $connexion->bind(':mueble', $mueble);
    $connexion->bind(':observaciones', $observaciones);
    $connexion->bind(':id_cuadricula', $id_cuadricula);
    $connexion->bind(':foto', $foto);
    $connexion->execute();
}
function map_of_2020_busca_rut_dado_foto_revisor($foto) {
    $connexion = new DatabasePDO();
    $sql = "SELECT rut FROM tbl_map_of_2020_revisor WHERE foto=:foto";
    $connexion->query($sql);
    $connexion->bind(':foto', $foto);
    $datos = $connexion->resultset();
    return $datos[0]->rut;
}

function map_of_2020_relator_save_data($rut, $id_empresa, $fecha_ahora, $numero_serie, $tipo_impresora, $tipo_conexion, $cantidad_bandejas, $mueble, $observaciones, $id_cuadricula, $foto, $accion, $requerimiento) {
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $rut_user = map_of_2020_busca_rut_dado_foto_revisor($foto);
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    $pregunta = utf8_decode($pregunta);
    $numero_serie = utf8_decode($numero_serie);
    $requerimiento = utf8_decode($requerimiento);
    $observaciones = utf8_decode($observaciones);
    $sql = "INSERT INTO tbl_map_of_2020 (rut, fecha, numero_serie, tipo_impresora, tipo_conexion, cantidad_bandejas, mueble, observaciones, id_cuadricula, foto, close, accion, requerimiento, rut_revisor, fecha_revisor, close_revisor) VALUES (:rut_user, :fecha_ahora, :numero_serie, :tipo_impresora, :tipo_conexion, :cantidad_bandejas, :mueble, :observaciones, :id_cuadricula, :foto, 1, :accion, :requerimiento, :user, :fecha_ahora, 1)";
    $connexion->query($sql);
    $connexion->bind(':rut_user', $rut_user);
    $connexion->bind(':fecha_ahora', $fecha_ahora);
    $connexion->bind(':numero_serie', $numero_serie);
    $connexion->bind(':tipo_impresora', $tipo_impresora);
    $connexion->bind(':tipo_conexion', $tipo_conexion);
    $connexion->bind(':cantidad_bandejas', $cantidad_bandejas);
    $connexion->bind(':mueble', $mueble);
    $connexion->bind(':observaciones', $observaciones);
    $connexion->bind(':id_cuadricula', $id_cuadricula);
    $connexion->bind(':foto', $foto);
    $connexion->bind(':accion', $accion);
    $connexion->bind(':requerimiento', $requerimiento);
    $connexion->bind(':user', $_SESSION["user_"]);
    $connexion->execute();
}
function map_of_2020_relator_update_save_data($numero_serie, 
                                             $tipo_impresora,
                                             $tipo_conexion,
                                             $cantidad_bandejas,
                                             $mueble,
                                             $observaciones,
                                             $id_cuadricula,
                                             $foto,
                                             $accion,
                                             $requerimiento)
{      
    $connexion = new DatabasePDO();
    
    $numero_serie = utf8_decode($numero_serie);
    $requerimiento = utf8_decode($requerimiento);
    $observaciones = utf8_decode($observaciones);
    $fecha = date("Y-m-d");    
    
    $sql = "UPDATE tbl_map_of_2020 
            SET accion = :accion, requerimiento = :requerimiento, rut_revisor = :rut_revisor, 
                fecha_revisor = :fecha_revisor, numero_serie = :numero_serie, tipo_impresora = :tipo_impresora, 
                tipo_conexion = :tipo_conexion, cantidad_bandejas = :cantidad_bandejas, mueble = :mueble, 
                observaciones = :observaciones, close_revisor = :close_revisor
            WHERE id_cuadricula = :id_cuadricula AND foto = :foto";
    
    $connexion->query($sql);
    $connexion->bind(':accion', $accion);
    $connexion->bind(':requerimiento', $requerimiento);
    $connexion->bind(':rut_revisor', $_SESSION["user_"]);
    $connexion->bind(':fecha_revisor', $fecha);
    $connexion->bind(':numero_serie', $numero_serie);
    $connexion->bind(':tipo_impresora', $tipo_impresora);
    $connexion->bind(':tipo_conexion', $tipo_conexion);
    $connexion->bind(':cantidad_bandejas', $cantidad_bandejas);
    $connexion->bind(':mueble', $mueble);
    $connexion->bind(':observaciones', $observaciones);
    $connexion->bind(':close_revisor', 1);
    $connexion->bind(':id_cuadricula', $id_cuadricula);
    $connexion->bind(':foto', $foto);
    
    $connexion->execute();                
}
function Map_Ofic_Data_foto($foto){
	
    $connexion = new DatabasePDO();
    $sql="SELECT * FROM tbl_nomina_accesos_2020 WHERE id_tipo = :id_tipo";
	  
    $connexion->query($sql);
    $connexion->bind(':id_tipo', $foto);
    $cod = $connexion->resultset();
    return $cod;	
} 

function Map_Ofic_BuscaJpg($rut){
	
    $connexion = new DatabasePDO();
    $sql="SELECT * FROM tbl_nomina_accesos_2020 WHERE rut = :rut AND tipo = 'map'";
	  
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;	
}

function ActualizaIdInscripcionNull_RUT($rut)
{
    $connexion = new DatabasePDO();
    $sql = "UPDATE tbl_inscripcion_cierre h SET id_inscripcion=h.id_curso WHERE 
            (SELECT modalidad FROM tbl_lms_curso WHERE id=h.id_curso)='1' AND
            (h.id_inscripcion IS NULL OR h.id_inscripcion='') AND h.rut=:rut";
	  
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->execute();
}
function BuscaInscripcionCierreDuplicadosRut($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.rut, h.id_inscripcion, h.id_curso,
    (select modalidad from tbl_lms_curso where id=h.id_curso), COUNT(*)
    FROM tbl_inscripcion_cierre h
    where   h.rut=:rut and
    (select modalidad from tbl_lms_curso where id=h.id_curso) is not NULL
    and (select modalidad from tbl_lms_curso where id=h.id_curso)<>'4'
    GROUP BY h.rut, h.id_inscripcion, h.id_curso
    HAVING COUNT(*) > 1";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}

function Carrusel2020_tipo($tipo, $id_empresa, $perfil_usuario){
    $connexion = new DatabasePDO();
    $sql="select * 
            from tbl_carrusel_home_2020 
            where tipo=:tipo and id_empresa=:id_empresa
            and (perfil=:perfil_usuario or perfil is null) 
            order by orden ASC";
    $connexion->query($sql);
    $connexion->bind(':tipo', $tipo);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':perfil_usuario', $perfil_usuario);
    $cod = $connexion->resultset();
    return $cod; 
}
function Carrusel2020_tipo_Clasificaciones_Netflix($tipo, $id_empresa, $perfil_usuario){
    $connexion = new DatabasePDO();
    $sql="select * from tbl_carrusel_home_clasificaciones_2020 where tipo=:tipo and id_empresa=:id_empresa and (perfil=:perfil_usuario or perfil is null) order by orden ASC";
    $connexion->query($sql);
    $connexion->bind(':tipo', $tipo);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':perfil_usuario', $perfil_usuario);
    $cod = $connexion->resultset();
    return $cod;    
}


function BuscaNomina2020_rut_tabla($rut, $tabla, $field, $data, $id_empresa){
    $id_empresa="78";
    $connexion = new DatabasePDO();
    $sql="select * from $tabla where $field=:data and rut=:rut and id_empresa=:id_empresa order by id ASC LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':data', $data);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;    
} 


function BuscaNomina2020_rut_tabla_webinar($rut, $tabla, $field, $data, $id_empresa){
    $id_empresa="78";
    $connexion = new DatabasePDO();
    $sql="SELECT h.*, (SELECT count(id) AS cuenta FROM tbl_enc_elearning_respuestas WHERE rut = h.rut AND id_encuesta = h.id_encuesta AND id_tipo = h.id_tipo) AS cuenta from $tabla h where h.$field=:data and h.rut=:rut and h.id_empresa=:id_empresa and (SELECT count(id) AS cuenta FROM tbl_enc_elearning_respuestas WHERE rut = h.rut AND id_encuesta = h.id_encuesta AND id_tipo = h.id_tipo)='0' ORDER BY id ASC limit 1";
    $connexion->query($sql);
    $connexion->bind(':data', $data);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;    
}
function Accesosdirectos2020_tipo($tipo, $id_empresa, $perfil, $rut){
	if(strpos($perfil, "No Jefe") !== false){
		$qyerJefe=" and solojefe is null";
	} else{
		$qyerJefe=" and nojefe is null";
	}
	
	if($rut=="17175208"){
		 $qyerJefe=" and nojefe is null";
	}
	
	$connexion = new DatabasePDO();
	$sql="select * from tbl_accesos_directos_home_2020 where tipo=:tipo and id_empresa=:id_empresa $qyerJefe order by orden";
	
	if($_SESSION["rut"]=="12345"){
		//echo $sql;
	}
	
	$connexion->query($sql);
	$connexion->bind(':tipo', $tipo);
	$connexion->bind(':id_empresa', $id_empresa);
	$cod = $connexion->resultset();
	return $cod;
}
	

	
function BuscaNominaEncuestaRut($rut, $id_encuesta, $id_empresa){
	$connexion = new DatabasePDO();
	$sql = "select count(id) as cuenta from tbl_nomina_encuesta where id_encuesta=:id_encuesta and rut=:rut and id_empresa=:id_empresa";
	$connexion->query($sql);
	$connexion->bind(':id_encuesta', $id_encuesta);
	$connexion->bind(':rut', $rut);
	$connexion->bind(':id_empresa', $id_empresa);
	
	$cod = $connexion->resultset();
	return $cod[0]->cuenta;
}
function BuscaPopupHoy_2020($rut, $id_empresa){
	$connexion = new DatabasePDO();
	$fecha_ahora_scoring = date("Y-m-d");		
	$sql = "SELECT * FROM tbl_popup WHERE rut=:rut AND id_empresa='78' AND fecha=:fecha_ahora_scoring AND visto IS NULL";
	
	if($rut=="12852821"){
		//echo $sql;
	}
	
	$connexion->query($sql);
	$connexion->bind(':rut', $rut);
	$connexion->bind(':fecha_ahora_scoring', $fecha_ahora_scoring);
	$cod = $connexion->resultset();
	return $cod;	
}

function UpdatePopupHoy_2020($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $fecha_ahora_scoring = date("Y-m-d");		
    $sql = "UPDATE tbl_popup SET visto='1'
    WHERE rut=:rut AND id_empresa='78' AND fecha=:fecha_ahora_scoring";

    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':fecha_ahora_scoring', $fecha_ahora_scoring);
    $connexion->execute();
}

function BuscaScoring_2020($rut, $id_empresa){
	$connexion = new DatabasePDO();
	$fecha_ahora_scoring = date("Y-m-d");		
	$sql = "SELECT * FROM tbl_scoring WHERE rut=:rut AND id_empresa='78' AND fecha_prueba=:fecha_ahora_scoring";
	
	if($rut=="12345"){
		//echo $sql;
	}
	
	$connexion->query($sql);
	$connexion->bind(':rut', $rut);
	$connexion->bind(':fecha_ahora_scoring', $fecha_ahora_scoring);
	$cod = $connexion->resultset();
	return $cod;	
}

function BuscaScoring_Finalizado_TblScoring2020($rut, $id_empresa){
	/*$c_db="bchmc_scoring";
	$connexion = new DatabasePDO();
	$connexion->setDb($c_db);
	
	$fecha_ahora_scoring = date("Y-m-d");		
	$sql = "SELECT finalizado FROM tbl_checklist_rut_cargo WHERE rut=:rut AND fecha=:fecha_ahora_scoring AND finalizado='1'";
	
	$connexion->query($sql);
	$connexion->bind(':rut', $rut);
	$connexion->bind(':fecha_ahora_scoring', $fecha_ahora_scoring);
	$cod = $connexion->resultset();
	
	//print_r($cod);
	
	return $cod[0]->finalizado;	*/
}

function Encuesta_Pendientes_Webinars($rut_col){
    $connexion = new DatabasePDO();
    $sql = "select h.*,
            (select count(id) as cuenta from tbl_enc_elearning_respuestas where rut=h.rut 
            and id_encuesta=h.id_encuesta and id_tipo=h.id_charla) as resp
            from tbl_nomina_charla h 
            where h.fecha>='2020-07-01'
            and h.rut=:rut_col
            and (select count(id) as cuenta from tbl_enc_elearning_respuestas where rut=h.rut 
            and id_encuesta=h.id_encuesta and id_tipo=h.id_charla)='0' order by id DESC 
            limit 1";
    $connexion->query($sql);
    $connexion->bind(':rut_col', $rut_col);
    $cod = $connexion->resultset();
    return $cod;    
}

function Busca_Encuestas_Nomina_rut($rut, $id_empresa){
    $connexion = new DatabasePDO();
    $sql = "select * from tbl_nomina_encuesta where rut=:rut and id_empresa=:id_empresa";        
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;        
}