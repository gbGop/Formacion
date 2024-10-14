<?php
function VerificaPaso1($rut, $id_empresa){
    $connexion = new DatabasePDO();
    $sql = "SELECT count(id) as cuenta FROM tbl_becas WHERE id_empresa=:id_empresa AND rut=:rut AND montotal IS NOT NULL";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return ($cod[0]->cuenta);
}

function VerificaPaso2($rut, $id_empresa){
    $connexion = new DatabasePDO();
    $sql = "SELECT count(id) as cuenta FROM tbl_becas WHERE id_empresa=:id_empresa AND rut=:rut AND certificado_notas IS NOT NULL";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return ($cod[0]->cuenta);
}

function VerificaPaso3($rut, $id_empresa){
    $connexion = new DatabasePDO();
    $sql = "SELECT count(id) as cuenta FROM tbl_becas WHERE id_empresa=:id_empresa AND rut=:rut AND boletinpsu IS NOT NULL";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return ($cod[0]->cuenta);
}

function Becas_SAVE_1($rut,$id_empresa,$fecha,$hora,$estado,$fecha_estado,$tipo_beca,$nombre_beca,$ficha_postulacion,$certificado_notas,$pago_arancel,$certificado_carga,$concentracion_notas,$licencia_edmedia,$boletinpsu,
                      $certificado_egreso,$certificado_acredita,$ficha_vivienda_rut_propietario,$ficha_vivienda_nombre_propietario,$ficha_vivienda_fecha_escritura,$ficha_vivienda_valor,$ficha_vivienda_direccion,$target_file_vivienda_foto_escritura,
                      $target_file_vivienda_cedula,$target_file_vivienda_residencia,$target_file_vivienda_matrimonio,$num_celular,$anexo,$carrera,$otracarrera,$tipocarrera,$casaestudios,$otracasa,$prosecucion,$semestre,
                      $montototal,$fecha_inicio,$fecha_termino, $dia, $dia2, $fecha_nac,$tipo_wf, $solicitud1, $solicitud2){
    $connexion = new DatabasePDO();

    $sql="SELECT id FROM tbl_becas WHERE id_empresa=:id_empresa AND rut=:rut";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();

    if($cod[0]->id<>''){

        $sql = "UPDATE tbl_becas SET
            carrera=:carrera,
            otracarrera=:otracarrera,
            vivienda_residencia=:vivienda_residencia,
            solicitud1=:solicitud1,
            casaestudios=:casaestudios,
            otracasa=:otracasa,
            prosecucion=:prosecucion,
            semestre=:semestre,
            anexo=:anexo,
            fecha_inicio=:fecha_inicio,
            fecha_termino=:fecha_termino,
            dia=:dia,
            solicitud2=:solicitud2,
            ficha_vivienda_direccion=:ficha_vivienda_direccion,
            montotal=:montotal,
            ficha_vivienda_valor=:ficha_vivienda_valor,
            tipo_wf=:tipo_wf
        WHERE id=:id";

        $connexion->query($sql);
        $connexion->bind(':carrera', $carrera);
        $connexion->bind(':otracarrera', $otracarrera);
        $connexion->bind(':vivienda_residencia', $target_file_vivienda_residencia);
        $connexion->bind(':solicitud1', $solicitud1);
        $connexion->bind(':casaestudios', $casaestudios);
        $connexion->bind(':otracasa', $otracasa);
        $connexion->bind(':prosecucion', $prosecucion);
        $connexion->bind(':semestre', $semestre);
        $connexion->bind(':anexo', $anexo);
        $connexion->bind(':fecha_inicio', $fecha_inicio);
        $connexion->bind(':fecha_termino', $fecha_termino);
        $connexion->bind(':dia', $dia);
        $connexion->bind(':solicitud2', $solicitud2);
        $connexion->bind(':ficha_vivienda_direccion', $ficha_vivienda_direccion);
        $connexion->bind(':montotal', $montototal);
        $connexion->bind(':ficha_vivienda_valor', $ficha_vivienda_valor);
        $connexion->bind(':tipo_wf', $tipo_wf);
        $connexion->bind(':id', $cod->id);
        $connexion->execute();
    }


}

function Becas_SAVE_2($rut,$id_empresa,$fecha,$hora,$estado,$fecha_estado,$tipo_beca,$nombre_beca,$ficha_postulacion,$certificado_notas,$pago_arancel,$certificado_carga,$concentracion_notas,$licencia_edmedia,$boletinpsu,
                      $certificado_egreso,$certificado_acredita,$ficha_vivienda_rut_propietario,$ficha_vivienda_nombre_propietario,$ficha_vivienda_fecha_escritura,$ficha_vivienda_valor,$ficha_vivienda_direccion,$target_file_vivienda_foto_escritura,
                      $target_file_vivienda_cedula,$target_file_vivienda_residencia,$target_file_vivienda_matrimonio, $num_celular, $anexo, $carrera, $otracarrera,
                      $tipocarrera, $casaestudios, $otracasa, $prosecucion, $semestre, $montototal,$fecha_inicio, $fecha_termino, $dia, $dia2, $fecha_nac, $tipo_wf){

    $connexion = new DatabasePDO();

    $sql="select id from tbl_becas where id_empresa=:id_empresa and rut=:rut";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();

    if($pago_arancel<>"")	        { $Qpa=" , pago_arancel='$pago_arancel' "; }                else { $Qpa=" ";}
    if($certificado_carga<>"")	    { $Qcc=" , certificado_carga='$certificado_carga' "; }      else { $Qcc=" ";}
    if($certificado_notas<>"")	    { $Qcnc=" , certificado_notas='$certificado_notas' "; }     else { $Qcnc=" ";}
    if($concentracion_notas<>"")	{ $Qcn=" , concentracion_notas='$concentracion_notas' "; }  else { $Qcn=" ";}
    if($licencia_edmedia<>"")	    { $Qle=" , licencia_edmedia='$licencia_edmedia' "; }        else { $Qle=" ";}
    if($boletinpsu<>"")	            { $Qbp=" , boletinpsu='$boletinpsu' "; }                    else { $Qbp=" ";}
    if($certificado_egreso<>"")	    { $Qce=" , certificado_egreso='$certificado_egreso' "; }    else { $Qce=" ";}
    if($certificado_acredita<>"")	{ $Qca=" , certificado_acredita='$certificado_acredita' "; }    else { $Qca=" ";}
    if($cod[0]->id<>''){
        $id_update=$cod[0]->id;
        $sql = "update tbl_becas set ficha_postulacion='1' $Qpa $Qcc $Qcn $Qle $Qbp $Qce $Qca $Qcnc where id=:id";
        $connexion->query($sql);
        $connexion->bind(':id', $cod[0]->id);
        $connexion->execute();
    } else {

    }
}

function Becas_SAVE_3($rut,$id_empresa,$fecha,$hora,$estado,$fecha_estado,$tipo_beca,$nombre_beca,$ficha_postulacion,$certificado_notas,$pago_arancel,$certificado_carga,$concentracion_notas,$licencia_edmedia,$boletinpsu,
                      $certificado_egreso,$certificado_acredita,$ficha_vivienda_rut_propietario,$ficha_vivienda_nombre_propietario,$ficha_vivienda_fecha_escritura,$ficha_vivienda_valor,$ficha_vivienda_direccion,$target_file_vivienda_foto_escritura,
                      $target_file_vivienda_cedula,$target_file_vivienda_residencia,$target_file_vivienda_matrimonio, $num_celular, $anexo, $carrera, $otracarrera, $tipocarrera, $casaestudios,
                      $otracasa, $prosecucion, $semestre, $montototal,$fecha_inicio, $fecha_termino, $dia, $dia2, $fecha_nac, $tipo_wf){

    $connexion = new DatabasePDO();

    $sql="select id from tbl_becas where id_empresa=:id_empresa and rut=:rut
    and tipo_beca=:tipo_beca";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':tipo_beca', $tipo_beca);
    $cod = $connexion->resultset();

    if(!empty($cod)){
        $id_update=$cod[0]->id;
        $sql = "update tbl_becas set boletinpsu=:boletinpsu where id=:id_update";
        $connexion->query($sql);
        $connexion->bind(':boletinpsu', $boletinpsu);
        $connexion->bind(':id_update', $id_update);
        $connexion->execute();
    } else {

    }
}

function Becas_2020_Actualiza_Datos_usuario($rut, $id_empresa, $tipo_beca, $celular, $email_personal, $acepto) {
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $hora  = date("H:i:s");
    $jefe=LimpiaRutFront($jefe);
    $nombre=utf8_decode($nombre);
    $familia_cargo=utf8_decode($familia_cargo);
    //echo "<br>Becas_2020_Actualiza_Datos_usuario($rut, $id_empresa, $tipo_beca, $celular, $email_personal, $acepto)";
    $sql1="select id from tbl_becas where id_empresa=:id_empresa and rut=:rut";
    //echo "<br>".$sql1."<br>";
    $connexion->query($sql1);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $cod1 = $connexion->resultset();
   // print_r($cod1);
    if($cod1[0]->id>0){

    } else {
        echo "<br>a<br>";
        $sql = "INSERT INTO tbl_becas (rut, id_empresa, tipo_beca, fecha, hora, estado, nombre_beca, celular, email, fechaacepto)
            VALUES (:rut, :id_empresa, :tipo_beca, :fecha, :hora, 'POSTULACION', :tipo_beca, :celular, :email_personal, :fecha_acepto)";
        $connexion->query($sql);
        $connexion->bind(':rut', $rut);
        $connexion->bind(':id_empresa', $id_empresa);
        $connexion->bind(':tipo_beca', $tipo_beca);
        $connexion->bind(':fecha', $fecha);
        $connexion->bind(':hora', $hora);
        $connexion->bind(':celular', $celular);
        $connexion->bind(':email_personal', $email_personal);
        $connexion->bind(':fecha_acepto', $fecha);
        $connexion->execute();
        //echo "<br>b<br>";
    }
}

function Becas_2020_Check_postulacion($rut, $id_empresa){
    $connexion = new DatabasePDO();

    $sql = "SELECT id FROM tbl_nomina_accesos_2020 WHERE rut = :rut AND tipo = 'beca' AND id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
    $cod = $connexion->resultset();
    $codok = "";
    if($cod[0]->id > 0){
        $codok = 1;
    }
    return $codok;
}
function Becas_Patrocinio_equipo_1($rut){
    $id_empresa="78";
    $connexion = new DatabasePDO();
    $sql="select * from tbl_becas where rutjefe1=:rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}
    
function Becas_Patrocinio_equipo_2($rut){
    $id_empresa="78";
    $connexion = new DatabasePDO();
    $sql="select * from tbl_becas where rutjefe2=:rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}
    
function Becas_2020_Busca_Universidades_data($id_empresa){
    $connexion = new DatabasePDO();
    $sql = "select * from tbl_becas_institucion_2020 where id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}
    
function Postulable_2020_full($id, $id_empresa){
    $connexion = new DatabasePDO();
    $sql="select * from tbl_postulables where id_postulable=:id and id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}
    
function Becas_2020_Nomina($rut, $id_empresa){
    $connexion = new DatabasePDO();
    $sql = "select (select count(id) from tbl_becas where rut=h.rut and ficha_postulacion='1') as cuenta from tbl_becas_usuario h where h.rut=:rut and h.id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod[0]->cuenta;
}

function Becas_2020_SoyJefe1o2($rut, $id_empresa){

    $connexion = new DatabasePDO();

    $sql = "SELECT COUNT(id) as cuenta  
            FROM tbl_becas 
            WHERE (rutjefe1=:rut OR rutjefe2=:rut) AND id_empresa=:id_empresa";
    
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $result = $connexion->resultset();
    
    return $result[0]->cuenta;
}

function Becas_2020_SaveJefe1($rut_col, $rut, $comentario1, $comentario2, $id_empresa){

    $connexion = new DatabasePDO();

    $fecha = date("Y-m-d");

    $comentario1  = utf8_decode($comentario1);
    $comentario2  = utf8_decode($comentario2);

    $sql = "UPDATE tbl_becas 
            SET comentario1jefe1=:comentario1, comentario2jefe1=:comentario2
            WHERE rut=:rut_col AND id_empresa=:id_empresa";
    
    $connexion->query($sql);
    $connexion->bind(':comentario1', $comentario1);
    $connexion->bind(':comentario2', $comentario2);
    $connexion->bind(':rut_col', Decodear3($rut_col));
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
}

function Becas_2020_Envio_Solicitud_Patrocinio($rut, $id_empresa){

    $connexion = new DatabasePDO();

    $fecha = date("Y-m-d");

    $Usua=DatosUsuario_($rut, $id_empresa);
    $UsuaJ=DatosUsuario_($Usua[0]->jefe, $id_empresa);

    $sql = "UPDATE tbl_becas 
            SET cartapatrocinio1='1', fechacartapatrocinio1=:fecha,
                rutjefe1=:rut_jefe, rutjefe2=:rut_jefe2, enviadoajefe='1'
            WHERE rut=:rut AND id_empresa=:id_empresa";
    
    $connexion->query($sql);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':rut_jefe', $Usua[0]->jefe);
    $connexion->bind(':rut_jefe2', $UsuaJ[0]->jefe);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
}
function Becas_2020_Jefe_Valida_Carta($rut, $rut_col, $a, $id_empresa){
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");

    $Usua = DatosUsuario_($rut, $id_empresa);
    $UsuaJ = DatosUsuario_($Usua[0]->jefe, $id_empresa);

    $sql = "UPDATE tbl_becas SET validacionjefe1 = :a, fechavalidacionjefe1 = :fecha WHERE rut = :rut_col AND id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':a', $a);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':rut_col', $rut_col);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
}

function Becas_2020_Jefe_de_Jefe_Valida_Carta($rut, $rut_col, $a, $id_empresa){
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");

    $Usua = DatosUsuario_($rut, $id_empresa);
    $UsuaJ = DatosUsuario_($Usua[0]->jefe, $id_empresa);

    $sql = "UPDATE tbl_becas SET validacionjefe2 = :a, fechavalidacionjefe2 = :fecha WHERE rut = :rut_col AND id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':a', $a);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':rut_col', $rut_col);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
}


function Becas_Check_Encuesta_2021($rut, $id_encuesta){
    $connexion = new DatabasePDO();

    $sql = "SELECT id_encuesta from tbl_nomina_accesos_2020 where rut=:rut and tipo='encuesta' and (id_encuesta=:id_encuesta) and id_empresa=:id_empresa";

    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_empresa', $_SESSION["id_empresa"]);
    $cod = $connexion->resultset();

    return $cod[0]->id_encuesta;
}

function Becas_2020_Check_patrocinio($rut, $id_empresa){
    $connexion = new DatabasePDO();

    $sql = "SELECT id from tbl_nomina_accesos_2020 where rut=:rut and tipo='validar_beca' and id_empresa=:id_empresa";

    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();

    $codok="";
    if($cod[0]->id>0){
        $codok=1;
    }
    return $codok;
}

function Becas_2020_Check_Postulacion_Go_to_TipoBecas($rut, $id_empresa){
    $connexion = new DatabasePDO();

    $sql = "select h.tipo_beca from tbl_becas h where h.rut=:rut and id_empresa=:id_empresa";

    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();

    return $cod;
}

function Becas_2020_Check_Solicitud_Jefe1($rut, $id_empresa){
    $connexion = new DatabasePDO();

    $sql = "select h.rutjefe1 from tbl_becas h where h.rutjefe1=:rut and h.validacionjefe1 is null and id_empresa=:id_empresa";

    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();

    return $cod;
}

function Becas_2020_Check_Solicitud_Jefe2($rut, $id_empresa){
    $connexion = new DatabasePDO();

    $sql = "select h.rutjefe2 from tbl_becas h where h.rutjefe2=:rut and h.validacionjefe2 is null and h.validacionjefe1='1' and id_empresa=:id_empresa";

    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();

    return $cod;
}
function Becas_2020_Linea($rut_col, $id_empresa){
    $connexion = new DatabasePDO();
    $sql = "SELECT h.* FROM tbl_becas h WHERE h.rut=:rut AND id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut_col);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function Becas2020_Lista_Carrera($tipo_beca, $rut, $id_empresa){
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*, (SELECT carrera FROM tbl_becas WHERE rut='12345') AS carrera_save FROM tbl_becas_carreras_2020 h WHERE tipo_beca=:tipo_beca AND id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':tipo_beca', $tipo_beca);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return ($cod);
}

function Busca_Postulaciones_Nomina_rut($rut, $id_empresa){
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_nomina_postulacion WHERE rut=:rut AND id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function Postulaciones_busca_PostulacionesValidar($rut, $id_empresa){
    $connexion = new DatabasePDO();
    $sql= "SELECT h.*, (SELECT nombre_completo FROM tbl_usuario WHERE rut=h.rut) AS nombre_completo, (SELECT dimension FROM tbl_postulables WHERE id_postulable=h.id_postulable) AS dimension, (SELECT nombre FROM tbl_postulables WHERE id_postulable=h.id_postulable) AS nombre, (SELECT descripcion FROM tbl_postulables WHERE id_postulable=h.id_postulable) AS descripcion, h.jefe FROM tbl_postulaciones h WHERE h.id_empresa=:id_empresa AND h.jefe=:rut AND h.jefeok=''";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}
function CuentaPostulaciones($rut, $tipo_postulable, $id_empresa){

    $connexion = new DatabasePDO();
    $sql= "
    SELECT    count(id) as cuenta

        from tbl_postulaciones

    where id_empresa=:id_empresa and tipo_postulable=:tipo_postulable and rut=:rut  and jefeok<>'NO' and jefeok<>'NO_POR_FECHA'
    ";

    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':tipo_postulable', $tipo_postulable);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;

}

function CuentaPostulacionesFecha($rut, $tipo_postulable, $fecha, $id_empresa){

    $connexion = new DatabasePDO();
    $sql= "
    SELECT    count(h.id) as cuenta

        from tbl_postulaciones  h

    where h.id_empresa=:id_empresa and h.fecha>:fecha and h.tipo_postulable=:tipo_postulable and h.rut=:rut  and h.jefeok<>'NO' and h.jefeok<>'NO_POR_FECHA'

    ";

    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':tipo_postulable', $tipo_postulable);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;

}

function CuentaPostulacionesItem($rut, $tipo_postulable, $ide, $id_empresa){

    $connexion = new DatabasePDO();
    $sql= "
    SELECT    count(id) as cuenta

        from tbl_postulaciones

    where id_empresa=:id_empresa and tipo_postulable=:tipo_postulable and rut=:rut
    and id_postulable=:ide and jefeok<>'NO' and jefeok<>'NO_POR_FECHA'
    ";

    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':tipo_postulable', $tipo_postulable);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':ide', $ide);
    $cod = $connexion->resultset();
    return $cod;

}

function Becas_SAVE($rut,$id_empresa,$fecha,$hora,$estado,$fecha_estado,
                    $tipo_beca,$nombre_beca,$ficha_postulacion,$certificado_notas,$pago_arancel,
                    $certificado_carga,$concentracion_notas,$licencia_edmedia,$boletinpsu,
                    $certificado_egreso,$certificado_acredita,
                    $ficha_vivienda_rut_propietario,
                    $ficha_vivienda_nombre_propietario,
                    $ficha_vivienda_fecha_escritura,
                    $ficha_vivienda_valor,
                    $ficha_vivienda_direccion,
                    $target_file_vivienda_foto_escritura,
                    $target_file_vivienda_cedula,
                    $target_file_vivienda_residencia,
                    $target_file_vivienda_matrimonio,    $num_celular,
                    $anexo,
                    $carrera,
                    $otracarrera,
                    $tipocarrera,
                    $casaestudios,
                    $otracasa,
                    $prosecucion,
                    $semestre,
                    $montototal,$fecha_inicio,
                    $fecha_termino, $dia, $dia2, $fecha_nac,
                    $tipo_wf){
    
    $connexion = new DatabasePDO();
    
    $sql="select id from tbl_becas where id_empresa=:id_empresa and rut=:rut
    and tipo_beca=:tipo_beca";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':tipo_beca', $tipo_beca);
    $cod = $connexion->resultset();
    if($cod[0]->id<>''){
        
        $id_update=$cod[0]->id;
        $sql = "UPDATE tbl_becas SET ficha_postulacion=:ficha_postulacion, certificado_notas=:certificado_notas,
        pago_arancel=:pago_arancel, certificado_carga=:certificado_carga, concentracion_notas=:concentracion_notas,
        licencia_edmedia=:licencia_edmedia, boletinpsu=:boletinpsu, certificado_egreso=:certificado_egreso,
        certificado_acredita=:certificado_acredita, ficha_vivienda_rut_propietario=:ficha_vivienda_rut_propietario, 
        ficha_vivienda_nombre_propietario=:ficha_vivienda_nombre_propietario, ficha_vivienda_fecha_escritura=:ficha_vivienda_fecha_escritura, 
        ficha_vivienda_valor=:ficha_vivienda_valor, ficha_vivienda_direccion=:ficha_vivienda_direccion, 
        vivienda_foto_escritura=:target_file_vivienda_foto_escritura, vivienda_cedula=:target_file_vivienda_cedula, 
        vivienda_residencia=:target_file_vivienda_residencia, vivienda_matrimonio=:target_file_vivienda_matrimonio,
        num_celular=:num_celular, anexo=:anexo, carrera=:carrera, otracarrera=:otracarrera, tipocarrera=:tipocarrera,
        casaestudios=:casaestudios, otracasa=:otracasa, prosecucion=:prosecucion, semestre=:semestre,
        fecha_inicio=:fecha_inicio, fecha_termino=:fecha_termino, dia=:dia, dia2=:dia2, fecha_nac=:fecha_nac,
        tipo_wf=:tipo_wf WHERE id=:id_update";

        $connexion->query($sql);
        $connexion->bind(':ficha_postulacion', $ficha_postulacion);
        $connexion->bind(':certificado_notas', $certificado_notas);
        $connexion->bind(':pago_arancel', $pago_arancel);
        $connexion->bind(':certificado_carga', $certificado_carga);
        $connexion->bind(':concentracion_notas', $concentracion_notas);
        $connexion->bind(':licencia_edmedia', $licencia_edmedia);
        $connexion->bind(':boletinpsu', $boletinpsu);
        $connexion->bind(':certificado_egreso', $certificado_egreso);
        $connexion->bind(':certificado_acredita', $certificado_acredita);
        $connexion->bind(':ficha_vivienda_rut_propietario', $ficha_vivienda_rut_propietario);
        $connexion->bind(':ficha_vivienda_nombre_propietario', $ficha_vivienda_nombre_propietario);
        $connexion->bind(':ficha_vivienda_fecha_escritura', $ficha_vivienda_fecha_escritura);
        $connexion->bind(':ficha_vivienda_valor', $ficha_vivienda_valor);
        $connexion->bind(':ficha_vivienda_direccion', $ficha_vivienda_direccion);
        $connexion->bind(':target_file_vivienda_foto_escritura', $target_file_vivienda_foto_escritura);
        $connexion->bind(':target_file_vivienda_cedula', $target_file_vivienda_cedula);
        $connexion->bind(':target_file_vivienda_residencia', $target_file_vivienda_residencia);
        $connexion->bind(':target_file_vivienda_matrimonio', $target_file_vivienda_matrimonio);
        $connexion->bind(':num_celular', $num_celular);
        $connexion->bind(':anexo', $anexo);
        $connexion->bind(':carrera', $carrera);
        $connexion->bind(':otracarrera', $otracarrera);
        $connexion->bind(':tipocarrera', $tipocarrera);
        $connexion->bind(':casaestudios', $casaestudios);
        $connexion->bind(':otracasa', $otracasa);
        $connexion->bind(':prosecucion', $prosecucion);
        $connexion->bind(':semestre', $semestre);
        $connexion->bind(':fecha_inicio', $fecha_inicio);
        $connexion->bind(':fecha_termino', $fecha_termino);
        $connexion->bind(':dia', $dia);
        $connexion->bind(':dia2', $dia2);
        $connexion->bind(':fecha_nac', $fecha_nac);
        $connexion->bind(':tipo_wf', $tipo_wf);
        $connexion->bind(':id_update', $id_update);
        $connexion->execute();
    }else {
        $fecha = date("Y-m-d");
        $hora  = date("H:i:s");
        $fecha_estado = $fecha;
        $estado = "POSTULACION";
    
        $sql = "INSERT INTO tbl_becas 
        (rut, id_empresa, fecha, hora, estado, fecha_estado, tipo_beca, nombre_beca, ficha_postulacion, certificado_notas, pago_arancel,
        certificado_carga, concentracion_notas, licencia_edmedia, boletinpsu, certificado_egreso, certificado_acredita,
        ficha_vivienda_rut_propietario, ficha_vivienda_nombre_propietario, ficha_vivienda_fecha_escritura, ficha_vivienda_valor,
        ficha_vivienda_direccion, vivienda_foto_escritura, vivienda_cedula, vivienda_residencia, vivienda_matrimonio, num_celular,
        anexo, carrera, otracarrera, tipocarrera, casaestudios, otracasa, prosecucion, semestre, montotal, fecha_inicio,
        fecha_termino, dia, dia2, fecha_nac, tipo_wf) 
        VALUES (:rut, :id_empresa, :fecha, :hora, :estado, :fecha_estado, :tipo_beca, :nombre_beca, :ficha_postulacion, :certificado_notas,
        :pago_arancel, :certificado_carga, :concentracion_notas, :licencia_edmedia, :boletinpsu, :certificado_egreso, :certificado_acredita,
        :ficha_vivienda_rut_propietario, :ficha_vivienda_nombre_propietario, :ficha_vivienda_fecha_escritura, :ficha_vivienda_valor,
        :ficha_vivienda_direccion, :target_file_vivienda_foto_escritura, :target_file_vivienda_cedula, :target_file_vivienda_residencia,
        :target_file_vivienda_matrimonio, :num_celular, :anexo, :carrera, :otracarrera, :tipocarrera, :casaestudios, :otracasa, :prosecucion, :semestre, 
        :montotal, :fecha_inicio, :fecha_termino, :dia, :dia2, :fecha_nac, :tipo_wf)";
    
        $connexion->query($sql);
        $connexion->bind(':rut', $rut);
        $connexion->bind(':id_empresa', $id_empresa);
        $connexion->bind(':fecha', $fecha);
        $connexion->bind(':hora', $hora);
        $connexion->bind(':estado', $estado);
        $connexion->bind(':fecha_estado', $fecha_estado);
        $connexion->bind(':tipo_beca', $tipo_beca);
        $connexion->bind(':nombre_beca', $nombre_beca);
        $connexion->bind(':ficha_postulacion', $ficha_postulacion);
        $connexion->bind(':certificado_notas', $certificado_notas);
        $connexion->bind(':pago_arancel', $pago_arancel);
        $connexion->bind(':certificado_carga', $certificado_carga);
        $connexion->bind(':concentracion_notas', $concentracion_notas);
        $connexion->bind(':licencia_edmedia', $licencia_edmedia);
        $connexion->bind(':boletinpsu', $boletinpsu);
        $connexion->bind(':certificado_egreso', $certificado_egreso);
        $connexion->bind(':certificado_acredita', $certificado_acredita);
        $connexion->bind(':ficha_vivienda_rut_propietario', $ficha_vivienda_rut_propietario);
        $connexion->bind(':ficha_vivienda_nombre_propietario', $ficha_vivienda_nombre_propietario);
        $connexion->bind(':ficha_vivienda_fecha_escritura', $ficha_vivienda_fecha_escritura);
        $connexion->bind(':ficha_vivienda_valor', $ficha_vivienda_valor);
        $connexion->bind(':ficha_vivienda_direccion', $ficha_vivienda_direccion);
        $connexion->bind(':target_file_vivienda_foto_escritura', $target_file_vivienda_foto_escritura);
        $connexion->bind(':target_file_vivienda_cedula', $target_file_vivienda_cedula);
        $connexion->bind(':target_file_vivienda_residencia', $target_file_vivienda_residencia);
        $connexion->bind(':target_file_vivienda_matrimonio', $target_file_vivienda_matrimonio);
        $connexion->bind(':num_celular', $num_celular);
        $connexion->bind(':anexo', $anexo);
        $connexion->bind(':carrera', $carrera);
        $connexion->bind(':otracarrera', $otracarrera);
        $connexion->bind(':tipocarrera', $tipocarrera);
        $connexion->bind(':casaestudios', $casaestudios);
        $connexion->bind(':otracasa', $otracasa);
        $connexion->bind(':prosecucion', $prosecucion);
        $connexion->bind(':semestre', $semestre);
        $connexion->bind(':fecha_inicio', $fecha_inicio);
        $connexion->bind(':fecha_termino', $fecha_termino);
        $connexion->bind(':dia', $dia);
        $connexion->bind(':dia2', $dia2);
        $connexion->bind(':fecha_nac', $fecha_nac);
        $connexion->bind(':tipo_wf', $tipo_wf);
        $connexion->execute();
    }
}

function BuscaMiPostulacionBecas($tipo_beca, $rut) {
    $connexion = new DatabasePDO();
    $sql = "SELECT COUNT(id) AS cuenta FROM tbl_becas WHERE rut = :rut AND tipo_beca = :tipo_beca";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':tipo_beca', $tipo_beca);
    $cod = $connexion->resultset();
    return $cod[0]->cuenta;
}

function Postulaciones_SAVE($rut,$id_empresa,$fecha,$hora,$estado,$fecha_estado,$jefeok,
                            $tipo_postulable, $id_postulable, $id_registro){

    $connexion = new DatabasePDO();
    $Usua=DatosUsuario_($rut, $id_empresa);
    $sql="select id from tbl_postulaciones where id_empresa=:id_empresa and rut=:rut
    and tipo_postulable=:tipo_postulable and id_postulable=:id_postulable";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':tipo_postulable', $tipo_postulable);
    $connexion->bind(':id_postulable', $id_postulable);
    $cod = $connexion->resultset();

    $jefe=$Usua[0]->jefe;
    // echo "<br><br>"; echo $sql;
    // sleep(5); echo "<br><br>";

    if($cod[0]->id<>''){
        echo "No puedes postular al mismo item dos veces"; sleep(3);
    } else {

        if($id_registro<>''){
            $sql = "update
                    tbl_postulaciones
                set jefeok=:jefeok,fechajefe=:fechajefe
                    where id=:id_registro";
            $connexion->query($sql);
            $connexion->bind(':jefeok', $jefeok);
            $connexion->bind(':fechajefe', $fechajefe);
            $connexion->bind(':id_registro', $id_registro);
            $connexion->execute();

        } else {
            $fecha = date("Y-m-d");
            $hora  = date("H:i:s");
            $fecha_estado=$fecha;

            $sql   = "INSERT INTO
                    tbl_postulaciones

                (rut,id_empresa,fecha,hora,estado,fecha_estado,tipo_postulable,
                nombre_postulable,descripcion_postulacion,jefeok,
                fechajefe,validacionok,fechavalidacion, id_postulable, jefe)
                values
                (:rut,:id_empresa,:fecha,:hora,:estado,:fecha_estado,:tipo_postulable,
                :nombre_postulable,:descripcion_postulacion,:jefeok,
                :fechajefe,
                :validacionok,:fechavalidacion,:id_postulable,:jefe)";

            $connexion->query($sql);
            $connexion->bind(':rut', $rut);
            $connexion->bind(':id_empresa', $id_empresa);
            $connexion->bind(':fecha', $fecha);
            $connexion->bind(':hora', $hora);
            $connexion->bind(':estado', $estado);
            $connexion->bind(':fecha_estado', $fecha_estado);
            $connexion->bind(':tipo_postulable', $tipo_postulable);
            $connexion->bind(':nombre_postulable', $nombre_postulable);
            $connexion->bind(':descripcion_postulacion', $descripcion_postulacion);
            $connexion->bind(':jefeok', $jefeok);
            $connexion->bind(':fechajefe', $fechajefe);
            $connexion->bind(':validacionok', $validacionok);
            $connexion->bind(':fechavalidacion', $fechavalidacion);
            $connexion->bind(':id_postulable', $id_postulable);
            $connexion->bind(':jefe', $jefe);
            $connexion->execute();
       
        }
    
    }
}

function Postulaciones_SAVE_2020($rut, $id_empresa, $fecha, $hora, $estado, $fecha_estado, $jefeok,
                                 $tipo_postulable, $id_postulable, $id_registro, $id_evento){

    $connexion = new DatabasePDO();
    $Usua = DatosUsuario_($rut, $id_empresa);

    $sql = "select id from tbl_postulaciones where id_empresa=:id_empresa and rut=:rut
            and tipo_postulable=:tipo_postulable and id_postulable=:id_postulable";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':tipo_postulable', $tipo_postulable);
    $connexion->bind(':id_postulable', $id_postulable);
    $cod = $connexion->resultset();

    $jefe = $Usua[0]->jefe;

    if($cod[0]->id <> ''){
        echo "No puedes postular al mismo item dos veces";
        sleep(3);
    } else {

        if($id_registro <> ''){

            $sql = "update tbl_postulaciones set jefeok=:jefeok, fechajefe=:fechajefe where id=:id_registro";
            $connexion->query($sql);
            $connexion->bind(':jefeok', $jefeok);
            $connexion->bind(':fechajefe', $fechajefe);
            $connexion->bind(':id_registro', $id_registro);
            $connexion->execute();

        } else {

                $fecha = date("Y-m-d");
                $hora  = date("H:i:s");

                $fecha_estado = $fecha;

                $sql   = "INSERT INTO tbl_postulaciones
                        (rut, id_empresa, fecha, hora, estado, fecha_estado, tipo_postulable,
                        nombre_postulable, descripcion_postulacion, jefeok, fechajefe, validacionok,
                        fechavalidacion, id_postulable, jefe, id_evento)
                        values
                        (:rut, :id_empresa, :fecha, :hora, :estado, :fecha_estado, :tipo_postulable,
                        :nombre_postulable, :descripcion_postulacion, :jefeok, :fechajefe, :validacionok,
                        :fechavalidacion, :id_postulable, :jefe, :id_evento)";

                $connexion->query($sql);
                $connexion->bind(':rut', $rut);
                $connexion->bind(':id_empresa', $id_empresa);
                $connexion->bind(':fecha', $fecha);
                $connexion->bind(':hora', $hora);
                $connexion->bind(':estado', $estado);
                $connexion->bind(':fecha_estado', $fecha_estado);
                $connexion->bind(':tipo_postulable', $tipo_postulable);
                $connexion->bind(':nombre_postulable', $nombre_postulable);
                $connexion->bind(':descripcion_postulacion', $descripcion_postulacion);
                $connexion->bind(':jefeok', $jefeok);
                $connexion->bind(':fechajefe', $fechajefe);
                $connexion->bind(':validacionok', $validacionok);
                $connexion->bind(':fechavalidacion', $fechavalidacion);
                $connexion->bind(':id_postulable', $id_postulable);
                $connexion->bind(':jefe', $jefe);
                $connexion->bind(':id_evento', $id_evento);
                $connexion->execute();

            }

                }
}
function Becas_busca_MisPostulaciones($rut, $id_empresa){
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_becas WHERE id_empresa = :id_empresa AND rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}

function Becas_busca_Postulaciones_vistaJefe($rut, $id_empresa){
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*, u.nombre_completo FROM tbl_becas h INNER JOIN tbl_usuario u ON h.rut = u.rut WHERE h.id_empresa = :id_empresa AND h.rutjefe1 = :rut ORDER BY h.id DESC";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}

function Becas_busca_Postulaciones_vistaJefedeJefe($rut, $id_empresa){
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*, u.nombre_completo, u2.jefe, u3.nombre_completo AS nombre_jefe FROM tbl_becas h INNER JOIN tbl_usuario u ON h.rut = u.rut INNER JOIN tbl_usuario u2 ON u.jefe = u2.rut INNER JOIN tbl_usuario u3 ON h.rutjefe2 = u3.rut WHERE h.id_empresa = :id_empresa AND h.rutjefe2 = :rut AND h.validacionjefe1 = '1'";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}
function Becas_busca_MisPostulacionesaValidar($rut, $id_empresa){
    $connexion = new DatabasePDO();

    $sql="
    SELECT h.*, 
    (SELECT nombre_completo FROM tbl_usuario WHERE rut=h.rut) AS nombre_completo
    FROM tbl_becas h 
    WHERE h.id_empresa=:id_empresa 
    AND h.tipo_wf='SI'
    AND 
    (
        (SELECT jefe FROM tbl_usuario WHERE rut=h.rut)=:rut
    )
    AND (h.validacionok IS NULL)
    ";

    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();

    return $cod;
}

function Becas_busca_MisPostulacionesaValidargud($rut, $id_empresa){
    $connexion = new DatabasePDO();

    $sql="
        SELECT h.*
        , (SELECT premio FROM tbl_premios WHERE id_premio = h.id_premio) AS premio
        , (SELECT nombre_completo FROM tbl_usuario WHERE rut=h.rut) AS nombre_completo
        FROM tbl_premios_canje h 
        WHERE h.id_empresa=:id_empresa 
        AND h.estadovalidacion='2'
        AND 
        (
            (SELECT jefe FROM tbl_usuario WHERE rut=h.rut)=:rut
            OR (SELECT lider FROM tbl_usuario WHERE rut=h.rut)=:rut 
            OR (SELECT responsable FROM tbl_usuario WHERE rut=h.rut)=:rut
        )
    ";

    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();

    return $cod;
}

function Becas_busca_MisPostulacionesaMisgud($rut, $id_empresa){
    $connexion = new DatabasePDO();

    $sql="
        SELECT h.*
        , (SELECT premio FROM tbl_premios WHERE id_premio = h.id_premio) AS premio
        , (SELECT nombre_completo FROM tbl_usuario WHERE rut=h.rut) AS nombre_completo
        FROM tbl_premios_canje h 
        WHERE h.id_empresa=:id_empresa 
        AND h.rut=:rut
    ";

    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();

    return $cod;
}
function Postulaciones_busca_CanjePuntos($rut, $segmento, $id_empresa){
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*, (SELECT premio FROM tbl_premios WHERE id_premio=h.id_premio AND segmento=:segmento) as premio 
            FROM tbl_premios_canje h 
            WHERE h.id_empresa=:id_empresa AND h.rut=:rut AND h.estadovalidacion<>'3' AND h.estadovalidacion<>'1' 
            ORDER BY id DESC";
    $connexion->query($sql);
    $connexion->bind(':segmento', $segmento);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}

function Postulaciones_busca_MisPostulaciones($rut, $id_empresa){
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*, (SELECT dimension FROM tbl_postulables WHERE id_postulable=h.id_postulable) as dimension,
            (SELECT nombre FROM tbl_postulables WHERE id_postulable=h.id_postulable) as nombre,
            (SELECT descripcion FROM tbl_postulables WHERE id_postulable=h.id_postulable) as descripcion_postulable,
            (SELECT fecha FROM tbl_postulables WHERE id_postulable=h.id_postulable) as fecha_postulable,
            (SELECT fecha_desactivacion FROM tbl_postulables WHERE id_postulable=h.id_postulable) as fecha_desactivacion 
            FROM tbl_postulaciones h WHERE h.id_empresa=:id_empresa AND h.rut=:rut";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}

function Becas_Datos_Tipo($rut, $id_empresa, $tipo){
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_becas WHERE tipo_beca=:tipo AND rut=:rut AND id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':tipo', $tipo);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function ActualizaPostulablesValidacionOk($rut, $id_empresa, $validacionok){
    $connexion = new DatabasePDO();
    $sql = "UPDATE tbl_postulaciones SET validacionok=:validacionok WHERE id_empresa=:id_empresa AND rut=:rut";
    $connexion->query($sql);
    $connexion->bind(':validacionok', $validacionok);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $connexion->execute();
}
function BuscaPostulableFullValidacionOk($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*, 
            (SELECT nombre FROM tbl_postulables WHERE id_postulable=h.id_postulable) AS nombre_curso 
            FROM tbl_postulaciones h 
            WHERE h.rut=:rut AND h.id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

