<?php
function CursosNominaAcceso2020($rut, $nomina) {
    $connexion = new DatabasePDO();
    $sql = "SELECT id FROM tbl_nomina_accesos_2020 WHERE rut=:rut AND tipo=:nomina";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':nomina', $nomina);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod[0]->id;
}
function TblUsuarioQA_data(){
    $connexion = new DatabasePDO();
    $sql="SELECT * FROM tbl_usuario where id>100 and rut>1000000 ";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}
function UpdateUsuarioQA_data($rut,$cuenta_init, $nombre){
    $connexion = new DatabasePDO();
    $hoy=date("Y-m-d");
    $sql = "update tbl_usuario 
                        set rut=:cuenta_init, rut_completo=:cuenta_init,
                            apaterno='Apaterno',amaterno='Amaterno',
                            nombre_completo='$nombre Apaterno Amaterno',
                            email='javier@gop.cl',emailBK='javier@gop.cl'
                        where rut= :rut";

    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':cuenta_init', $cuenta_init);
    $connexion->execute();
}
function UpdateJefeQA_data($rut,$cuenta_init){
    $connexion = new DatabasePDO();
    $hoy=date("Y-m-d");
    $sql = "update tbl_usuario 
                        set jefe=:cuenta_init
                        where jefe= :rut";

    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':cuenta_init', $cuenta_init);
    $connexion->execute();
}
function Create_Avatar_2022(){
    $connexion = new DatabasePDO();
    $sql="SELECT * FROM tbl_avatar_temp";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}
function InsertaTomaAntigeno($contacto_ant, $sintomas_ant, $resultado_anf, $rut, $rut_sesion){
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    $pregunta=utf8_decode($pregunta);
    $sql = "INSERT INTO tbl_contingencia_2021_antigeno (contacto_ant, sintomas_ant, resultado_anf, rut, fecha, hora, rut_sesion)
                VALUES (:contacto_ant, :sintomas_ant, :resultado_anf, :rut, :fecha, :hora, :rut_sesion)";
    $connexion->query($sql);
    $connexion->bind(':contacto_ant', $contacto_ant);
    $connexion->bind(':sintomas_ant', $sintomas_ant);
    $connexion->bind(':resultado_anf', $resultado_anf);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':hora', $hora);
    $connexion->bind(':rut_sesion', $rut_sesion);
    $connexion->execute();
//echo "sql $sql"; exit();
}
function CLIMA_TieneAccesoResultados($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_clima_acceso_resultados WHERE rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $datos = $connexion->resultset();
    return $datos;
}
function CLIMA_VeoSiTieneReportesDirectos($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_clima_dependencias WHERE jefe_2 = :rut OR jefe_3 = :rut OR jefe_4 = :rut OR jefe_5 = :rut OR jefe_6 = :rut OR jefe_7 = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $datos = $connexion->resultset();
    return $datos;
}
function CLIMA_VeoSiTieneDependientesPAraSeccionMiEquipo($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT tbl_clima_dependencias.rut, tbl_clima_resultado_jefe.resultado AS resultado, nombre, cargo, (SELECT COUNT(id) AS es_jefe FROM tbl_clima_dependencias AS a WHERE a.jefe_1 = tbl_clima_dependencias.rut) AS jefe_o_no FROM tbl_clima_dependencias LEFT JOIN tbl_clima_resultado_jefe ON tbl_clima_resultado_jefe.rut = tbl_clima_dependencias.rut WHERE jefe_1 = :rut AND (SELECT COUNT(id) AS es_jefe FROM tbl_clima_dependencias AS a WHERE a.jefe_1 = tbl_clima_dependencias.rut) ORDER BY tbl_clima_resultado_jefe.resultado DESC";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $datos = $connexion->resultset();
    return $datos;
}
function Clima_Mis_planes_jefes($rut, $id_empresa){
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_clima_planes_mejora WHERE rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}
function Clima_Escribe_planes($ambito, $acciones, $responsable, $fecha_seguimiento, $rut, $id_empresa){

    $connexion = new DatabasePDO();
    $ambito = utf8_decode($ambito);
    $acciones = utf8_decode($acciones);
    $responsable = utf8_decode($responsable);
    $fecha = date("Y-m-d");

    $sql = "INSERT INTO tbl_clima_planes_mejora (rut, fecha, ambito, acciones, responsable, fecha_seguimiento, id_empresa) 
    VALUES (:rut, :fecha, :ambito, :acciones, :responsable, :fecha_seguimiento, :id_empresa)";

    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':ambito', $ambito);
    $connexion->bind(':acciones', $acciones);
    $connexion->bind(':responsable', $responsable);
    $connexion->bind(':fecha_seguimiento', $fecha_seguimiento);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();

}
function Clima_Actualiza_planes($id_plan, $resultado, $comentarios){

    $connexion = new DatabasePDO();
    $resultado = utf8_decode($resultado);
    $comentarios = utf8_decode($comentarios);

    $sql = "UPDATE tbl_clima_planes_mejora SET resultado = :resultado, comentarios = :comentarios WHERE id = :id_plan";

    $connexion->query($sql);
    $connexion->bind(':resultado', $resultado);
    $connexion->bind(':comentarios', $comentarios);
    $connexion->bind(':id_plan', $id_plan);
    $connexion->execute();

}
function ObtieneDAtoUnico_tbl_clima_cuentanos($rut)
{
$connexion = new DatabasePDO();
$sql = "SELECT tbl_clima_cuentanos.* FROM tbl_clima_cuentanos WHERE rut=:rut ORDER BY id DESC LIMIT 1";
$connexion->query($sql);
$connexion->bind(':rut', $rut);
$datos = $connexion->resultset();
return $datos;
}
function Insert_tbl_clima_cuentanos($rut, $motivo, $email, $contacto, $motivo_contacto, $telefono, $dimension, $nombre, $cargo, $division)
{
$connexion = new DatabasePDO();
$fecha = date("Y-m-d");
$hora = date("H:i:s");
$pregunta=utf8_decode($pregunta);
$sql = "INSERT INTO tbl_clima_cuentanos (fecha, hora, rut, motivo, email, contacto, motivo_contacto, telefono, dimension, nombre, cargo, division, origen)
VALUES (:fecha, :hora, :rut, :motivo, :email, :contacto, :motivo_contacto, :telefono, :dimension, :nombre, :cargo, :division, 'masconectados')";
$connexion->query($sql);
$connexion->bind(':fecha', $fecha);
$connexion->bind(':hora', $hora);
$connexion->bind(':rut', $rut);
$connexion->bind(':motivo', $motivo);
$connexion->bind(':email', $email);
$connexion->bind(':contacto', $contacto);
$connexion->bind(':motivo_contacto', $motivo_contacto);
$connexion->bind(':telefono', $telefono);
$connexion->bind(':dimension', $dimension);
$connexion->bind(':nombre', $nombre);
$connexion->bind(':cargo', $cargo);
$connexion->bind(':division', $division);
$connexion->execute();
}
function Rec_cambia_estado_Reco_2021($rut, $val, $idrg, $id_empresa)
{
$connexion = new DatabasePDO();
$sql = "SELECT COUNT(id) as cuenta FROM tbl_reconoce_gracias h WHERE h.rut_jefatura=:rut AND h.id_empresa=:id_empresa AND h.id=:idrg";
$connexion->query($sql);
$connexion->bind(':rut', $rut);
$connexion->bind(':id_empresa', $id_empresa);
$connexion->bind(':idrg', $idrg);
$datos = $connexion->resultset();
if ($datos[0]->cuenta >= 1) {
$hoy = date("Y-m-d");
if ($val == 1) {
$sql = "UPDATE tbl_reconoce_gracias SET estado='OK', fecha_validacion=:hoy WHERE id=:idrg";
} elseif ($val == 2) {
$sql = "UPDATE tbl_reconoce_gracias SET estado='RECHAZADO', fecha_validacion=:hoy WHERE id=:idrg";
}
$connexion->query($sql);
$connexion->bind(':hoy', $hoy);
$connexion->bind(':idrg', $idrg);
$connexion->execute();
}
return $datos;
}
function BuscaParametro2020_rut_tabla($rut, $data, $id_empresa){
$connexion = new DatabasePDO();

$sql = "SELECT h.* FROM tbl_parametros_accesos_2020 h WHERE h.tipo=:data LIMIT 1";
$connexion->query($sql);
$connexion->bind(':data', $data);
$cod = $connexion->resultset();

$sql2 = "SELECT id FROM tbl_usuario WHERE tipo_contrato=:dato1 AND nombre_empresa_holding=:dato2 AND rut=:rut";
$connexion->query($sql2);
$connexion->bind(':dato1', $cod[0]->dato1);
$connexion->bind(':dato2', $cod[0]->dato2);
$connexion->bind(':rut', $rut);
$cod2 = $connexion->resultset();

return $cod2;
}
function ListaEsJefetblUsuario_SN($rut)
{
$connexion = new DatabasePDO();

$sql = "SELECT * FROM tbl_usuario WHERE jefe=:rut";
$connexion->query($sql);
$connexion->bind(':rut', $rut);
$cod = $connexion->resultset();

return $cod;
}
function UsuarioEnBasePersonaPorEmpresa($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "select * from tbl_usuario  where rut=:rut and id_empresa=:id_empresa";
        // echo "<br>UsuarioEnBasePersonaPorEmpresa($rut, $id_empresa) -> ".$sql;exit();
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}
function Usuario_Filtro_TipoSucursal($rut){
$id_empresa="78";
$connexion = new DatabasePDO();

$sql="SELECT servicio FROM tbl_usuario WHERE rut=:rut";
$connexion->query($sql);
$connexion->bind(':rut', $rut);
$cod = $connexion->resultset();

if($cod[0]->servicio=="0" or $cod[0]->servicio=="1" or $cod[0]->servicio=="521" or $cod[0]->servicio=="588" or $cod[0]->servicio=="722" or $cod[0]->servicio=="874"){
    $tipo_sucursal="casa_matriz";
} else {
    $tipo_sucursal="sucursal";
}
return $tipo_sucursal;     
}
function Usuario_Empresa_Holding($rut){
$id_empresa="78";
$connexion = new DatabasePDO();

$sql="SELECT empresa_holding FROM tbl_usuario WHERE rut=:rut";
$connexion->query($sql);
$connexion->bind(':rut', $rut);
$cod = $connexion->resultset();

return $cod[0]->empresa_holding;        
}
function BuscaPersonas2020_Autocomplete($search) {
    $connexion = new DatabasePDO();
    $search = utf8_decode($search);
    $sql = "SELECT tbl_usuario.*, MATCH(nombre_completo) AGAINST (:search) AS relevance
            FROM tbl_usuario
            WHERE MATCH(nombre_completo) AGAINST (:search) > 0";
    $connexion->query($sql);
    $connexion->bind(':search', $search);
    $datos = $connexion->resultset();
    return $datos;
}
function Agradecer_Data_Muestra_Equipo_2020_checkbox($colsend) {
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_usuario WHERE jefe=:colsend";
    $connexion->query($sql);
    $connexion->bind(':colsend', $colsend);
    $cod = $connexion->resultset();
    return $cod;
}
function CheckUsuarioVigente_2020($rut_masivo) {
    $connexion = new DatabasePDO();
    $sql = "SELECT id FROM tbl_usuario WHERE rut=:rut_masivo";
    $connexion->query($sql);
    $connexion->bind(':rut_masivo', $rut_masivo);
    $cod = $connexion->resultset();
    return $cod[0]->id;
}
function Biblioteca_Perfiles_2020($rut)
{
    $connexion = new DatabasePDO();
    $id_empresa = $_SESSION["id_empresa"];
    $perfil_biblioteca_2020 = "";
    $usu = DatosUsuario_($rut, $id_empresa);
    if (($usu[0]->cargo == "Ejecutivo Banca Personas" || $usu[0]->cargo == "Ejecutivo Banca Personas Sr." || $usu[0]->cargo == "Ejecutivo Banca Joven" || $usu[0]->cargo == "Ejecutivo Banca Preferente" || $usu[0]->cargo == "Ejecutivo Formacion Bca.Pers.") && $usu[0]->fecha_antiguedad >= '2020-03-01') {
        $perfil_biblioteca_2020 = "Personas";
    }
    if ($usu[0]->cargo == "Ejecutivo Banca Empresas" || $usu[0]->cargo == "Ejecutivo Banca Empresas Sr." || $usu[0]->cargo == "Ejecutivo Formacion Bca.Emp") {
        $perfil_biblioteca_2020 = "Empresas";
    }
    $sql_nomina_perfil = "SELECT perfil FROM tbl_biblio_perfiles_nomina WHERE rut = :rut";
    $connexion->query($sql_nomina_perfil);
    $connexion->bind(':rut', $rut);
    $cod_nomina = $connexion->resultset();
    if ($cod_nomina['perfil'] != "") {
        $perfil_biblioteca_2020 = $cod_nomina['perfil'];
    }
    return $perfil_biblioteca_2020;
}
function Bch_2020_Check_Area_Depto($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT area, departamento FROM tbl_usuario WHERE rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $result = $connexion->resultset();
    return $result;
}
function Bch_2020_Check_ExternoCiber($rut){
    // Crear una instancia de DatabasePDO
    $connexion = new DatabasePDO();

    // Preparar consulta SQL con marcador de posición
    $sql = "SELECT id FROM tbl_usuario WHERE rut = :rut AND vigencia_descripcion = 'externo_ciberseguridad'";

    // Ejecutar consulta SQL con valor vinculado
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);

    // Obtener resultados como objeto
    $cod = $connexion->resultset();

    // Devolver el valor de id
    return $cod[0]->id;
}
function Bch_2020_Check_Contigencia_Vulnerable($rut){
    // Crear una instancia de DatabasePDO
    $connexion = new DatabasePDO();

    // Preparar consulta SQL con marcador de posición
    $sql = "SELECT h.status FROM tbl_contingencia_2020 h WHERE h.rut = :rut ORDER BY h.id DESC LIMIT 1";

    // Ejecutar consulta SQL con valor vinculado
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);

    // Obtener resultados como objeto
    $cod = $connexion->resultset();

    // Evaluar si el status es vulnerable o no
    if(utf8_encode($cod[0]->status) == "No Trabajando / Permiso extraordinario , mayores 65 años, enfermedad riesgo o caso social" or $cod[0]->status == "'No Trabajando / otro motivo'"){
        $vulnerable = "1";
    } else {
        $vulnerable = "";
    }

    // Devolver el valor de vulnerable
    return $vulnerable;
}
function BuscaFullUsuario_SoloEmpresaNoExternos($id_empresa) {
    $connexion = new DatabasePDO();
    $fecha_hoy = date("Y-m-d");
    $sql = "SELECT * FROM tbl_usuario WHERE (vigencia_descripcion='' OR vigencia_descripcion IS NULL) AND id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}
function Biblio_Archivo_Full($id_archivo, $id_empresa) {
    $connexion = new DatabasePDO();
    $sql = "
        select h.*, 
        (select categoria from tbl_biblio_categorias where id=h.id_categoria) as categoria,
        (select id_categoria from tbl_biblio_categorias where id=h.id_categoria) as id_dimension,
        (select categoria from tbl_biblio_categorias where id=(select id_categoria from tbl_biblio_categorias where id=h.id_categoria)) as dimension
        from tbl_biblio_archivos h where h.id=:id_archivo
    ";
    $connexion->query($sql);
    $connexion->bind(':id_archivo', $id_archivo);
    $cod = $connexion->resultset();
    return $cod;
}
function Biblio_Save_2020($id_archivo, $rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    $fechahora = date("Y-m-d H:i:s");

    $Usua = DatosUsuario_($rut, $id_empresa);
    $Archivo = Biblio_Archivo_Full($id_archivo, $id_empresa);

    $sql = "INSERT INTO tbl_biblio_vistas (fecha,hora,rut,nombre,cargo,gerencia,id_archivo,archivo,id_categoria,categoria,id_dimension,dimension,id_empresa) VALUES (:fecha, :hora, :rut, :nombre, :cargo, :gerencia, :id_archivo, :archivo, :id_categoria, :categoria, :id_dimension, :dimension, :id_empresa)";

    $connexion->query($sql);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':hora', $hora);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':nombre', $Usua[0]->nombre_completo);
    $connexion->bind(':cargo', $Usua[0]->cargo);
    $connexion->bind(':gerencia', $Usua[0]->gerencia);
    $connexion->bind(':id_archivo', $id_archivo);
    $connexion->bind(':archivo', $Archivo[0]->titulo);
    $connexion->bind(':id_categoria', $Archivo[0]->id_categoria);
    $connexion->bind(':categoria', $Archivo[0]->categoria);
    $connexion->bind(':id_dimension', $Archivo[0]->id_dimension);
    $connexion->bind(':dimension', $Archivo[0]->dimension);
    $connexion->bind(':id_empresa', $id_empresa);

    $connexion->execute();
}
function BibliotecaSearchFullTexto_data($q, $id_empresa) {
    $connexion = new DatabasePDO();
    $q = utf8_decode($q);
    
    $sql = "
    SELECT
    tbl_biblio_archivos.*,
    ( SELECT categoria FROM tbl_biblio_categorias WHERE id = tbl_biblio_archivos.id_categoria ) AS subcategoria,
    ( SELECT id_categoria FROM tbl_biblio_categorias WHERE id = tbl_biblio_archivos.id_categoria ) AS id_categoria,
    ( SELECT categoria FROM tbl_biblio_categorias WHERE id = ( SELECT id_categoria FROM tbl_biblio_categorias WHERE id = tbl_biblio_archivos.id_categoria ) ) AS categoria,
    MATCH (titulo) AGAINST (:q) AS relevance
    FROM tbl_biblio_archivos
    WHERE MATCH (titulo) AGAINST (:q) and MATCH (titulo) AGAINST (:q) >0 and vigente='SI'
    ORDER BY descargas DESC, relevance DESC limit 30;
    ";
    $connexion->query($sql);
    $connexion->bind(':q', $q);
    $cod = $connexion->resultset();
    return $cod;
}
function Biblio_2019_Vista_Categorias_data($id_empresa, $rut, $id_dimension){
    $connexion = new DatabasePDO();
    $sql="select * from tbl_biblio_categorias where id_empresa=:id_empresa and id_categoria='0' order by categoria ASC";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return ($cod);
}
function Biblio_2019_Vista_Categorias_2020_Filtro_Biblio_data($id_empresa, $rut, $id_dimension, $filtro){
    $connexion = new DatabasePDO();
    $sql="select * from tbl_biblio_categorias where id_empresa=:id_empresa and id_categoria='0' $filtro order by categoria ASC";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return ($cod);
}
function Biblio_2019_Vista_SubCategorias_data($id_empresa, $rut, $id_cat){
    $connexion = new DatabasePDO();
    $sql="
    select h.*, (select count(id) from tbl_biblio_archivos where id_categoria =h.id and vigente='SI') as num_archivos from tbl_biblio_categorias h where h.id_empresa=:id_empresa and h.id_categoria=:id_cat
    and (select count(id) from tbl_biblio_archivos where id_categoria =h.id and vigente='SI')>0
    order by h.categoria ";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_cat', $id_cat);
    $cod = $connexion->resultset();
    return ($cod);
}
function Biblio_2019_Vista_Archivos_data($id_empresa, $rut, $id_subcat){
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_biblio_archivos WHERE id_categoria = :id_subcat AND vigente = 'SI' ORDER BY titulo";
    $connexion->query($sql);
    $connexion->bind(':id_subcat', $id_subcat);
    $cod = $connexion->resultset();
    return $cod;
}
function Contingencia_MiEquipo($rut, $id_empresa){
    $connexion = new DatabasePDO();
    $sql = "SELECT rut FROM tbl_usuario WHERE jefe = :rut AND id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}
function MiEquipoContingenciaStatus($rut, $hoy, $ayer, $id_empresa){
    $connexion = new DatabasePDO();
    $hoy = date("Y-m-d");
    $sql = "SELECT * FROM tbl_contingencia_2020 WHERE rut = :rut AND fecha <> :hoy AND status <> '' ORDER BY fecha DESC LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':hoy', $hoy);
    $cod = $connexion->resultset();
    return $cod;
}
function MiEquipoContingenciaStatusHoy($rut, $fecha, $id_empresa){
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_contingencia_2020 WHERE rut = :rut AND fecha = :fecha ORDER BY fecha DESC LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':fecha', $fecha);
    $cod = $connexion->resultset();
    return $cod;
}
function BuscaTblUsuarioRut($id_empresa){
    $connexion = new DatabasePDO();
    $sql = "SELECT rut_completo FROM tbl_usuario WHERE id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}
function MatrizIncentivosBuscaDocumentoJefeActivo($id_empresa){
    $connexion = new DatabasePDO();
    $sql = "SELECT id_documento FROM tbl_audiencias_documento_jefe WHERE activo = 1 AND id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod[0]->documento;
}
function Analitica_Save_Ambiente_Tiempo_Ejecucion($ambiente, $rut, $tiempo){
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    $sql = "INSERT INTO tbl_analitica_ambiente_tiempo_ejecucion (ambiente, rut, tiempo, fecha, hora) VALUES (:ambiente, :rut, :tiempo, :fecha, :hora)";
    $connexion->query($sql);
    $connexion->bind(':ambiente', $ambiente);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':tiempo', $tiempo);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':hora', $hora);
    $connexion->execute();
}
function TraigoUltimoJsonPorRutDINAMICO($rut, $id_objeto) {
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_json_ext WHERE rut = :rut AND id_objeto = :id_objeto ORDER BY fecha DESC, hora DESC LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_objeto', $id_objeto);
    $cod = $connexion->resultset();
    return $cod;
}
function TraigoUltimoJsonPorRutbch_cib_m1($rut) {
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_json_ext WHERE rut = :rut AND id_objeto = 'bch_cib_m1' ORDER BY fecha DESC, hora DESC LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}
function BuscaNoQuiereReconocer($rut, $id_empresa){
    $connexion = new DatabasePDO();
    $sql = "SELECT COUNT(id) as cuenta FROM tbl_reco_noquiereconocer WHERE rut = :rut AND id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod[0]->cuenta;
}
function EsparteMiEquipo($rut_col, $rut){
    $connexion = new DatabasePDO();
    $sql = "SELECT COUNT(id) AS cuenta FROM tbl_usuario WHERE rut = :rut_col AND jefe = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut_col', $rut_col);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return ($cod[0]->cuenta);
}
function InsertBuscaNoQuiereReconocer($rut, $comentario, $id_cui, $id_empresa){
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");	
    $hora = date("H:i:s");	
    $comentario = utf8_decode($comentario);
    $sql = "INSERT INTO tbl_reco_noquiereconocer (rut, comentario, cui, fecha, id_empresa) VALUES (:rut, :comentario, :id_cui, :fecha, :id_empresa)";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':comentario', $comentario);
    $connexion->bind(':id_cui', $id_cui);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
}
function VerificoCiberSeguridadJsonCompleto($rut){
    $connexion = new DatabasePDO();
    $sql = "SELECT DISTINCT(rut) FROM tbl_json_ext WHERE json_completo LIKE '%complete:true%' AND rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return ($cod);
}
function UsuarioTieneHistoricos($evaluado){
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_sgd_notas_finales_historicos WHERE rut = :evaluado";
    $connexion->query($sql);
    $connexion->bind(':evaluado', $evaluado);
    $cod = $connexion->resultset();
    return $cod;
}
function BuscamiEquipo($rut, $id_empresa){

    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_usuario WHERE jefe = :rut AND id_empresa = :id_empresa ORDER BY nombre_completo ASC";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;    
}
function BuscaNomina($rut, $tipo, $id_empresa){
    
    $id_empresa="78";
    $connexion = new DatabasePDO();
    $sql = "SELECT COUNT(id) as cuenta FROM tbl_nomina_acceso_directo WHERE nomina = :tipo AND rut = :rut AND id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':tipo', $tipo);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod[0]->cuenta;    
}
function BuscaNomina2020($rut, $tipo, $id_empresa){
    
    $id_empresa="78";
    $connexion = new DatabasePDO();
    $sql = "SELECT COUNT(id) as cuenta FROM tbl_nomina_acceso_directo_2020 WHERE nomina = :tipo AND rut = :rut AND id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':tipo', $tipo);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod[0]->cuenta;    
}
    
function BuscaNomina2020Pin($rut, $tipo, $id_empresa){
   
    $id_empresa="78";
    $connexion = new DatabasePDO();
    $sql = "SELECT id_tipo FROM tbl_nomina_accesos_2020 WHERE tipo = :tipo AND rut = :rut AND id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':tipo', $tipo);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod[0]->id_tipo;    
}
function Accesosdirectos2019_tipo($tipo, $id_empresa){
    $connexion = new DatabasePDO();
    $sql="SELECT * FROM tbl_accesos_directos_home WHERE tipo=:tipo AND id_empresa=:id_empresa ORDER BY orden";
    $connexion->query($sql);
    $connexion->bind(':tipo', $tipo);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;	
}

function CanalesContacto2019_tipo($tipo, $id_empresa){
    $connexion = new DatabasePDO();
    $sql="SELECT * FROM tbl_canales_home WHERE tipo=:tipo AND id_empresa=:id_empresa ORDER BY orden";
    $connexion->query($sql);
    $connexion->bind(':tipo', $tipo);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;	
}

function Carrusel2019_tipo($tipo, $id_empresa){
    $connexion = new DatabasePDO();
    $sql="SELECT * FROM tbl_carrusel_home WHERE tipo=:tipo AND id_empresa=:id_empresa ORDER BY orden ASC";
    $connexion->query($sql);
    $connexion->bind(':tipo', $tipo);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;	
}

function OfertaAbierta_Tipo_Ultimos($rut, $tipo, $id_empresa){
    $connexion = new DatabasePDO();
    if($tipo=="UltimoEscuchados"){
        $sql="SELECT h.*, 
            (SELECT nombre FROM tbl_oferta_abierta WHERE id_item=h.id_item) as nombre,
            (SELECT imagen FROM tbl_oferta_abierta WHERE id_item=h.id_item) as imagen,
            (SELECT descripcion FROM tbl_oferta_abierta WHERE id_item=h.id_item) as descripcion,
            (SELECT dimension FROM tbl_oferta_abierta WHERE id_item=h.id_item) as dimension
            FROM tbl_oferta_abierta_vistos h WHERE h.rut=:rut AND h.id_empresa=:id_empresa ORDER BY h.fecha DESC LIMIT 10";
    }
    if($tipo=="NuevosLanzamientos"){
        $sql="SELECT h.* FROM tbl_oferta_abierta h WHERE h.id_empresa='78' ORDER BY h.fecha DESC LIMIT 10";
    }
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':tipo', $tipo);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;	
}

function BuscaResultadosInformePec($rut) {
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_informe_pec WHERE rut = :rut ORDER BY proceso DESC";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}

function BuscaReco2019sinReco($id_empresa, $rut) {
    $connexion = new DatabasePDO();
    $sql = "SELECT COUNT(h.id) AS cuenta FROM tbl_cui_rut_jefe h WHERE h.rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    $cuenta = $cod[0]->cuenta;

    if ($cuenta > 0) {
        $sql2 = "SELECT COUNT(id) AS cuenta FROM tbl_reconoce_gracias WHERE rut_remitente = :rut AND cui IS NOT NULL AND Year(fecha) = '2019'";
        $connexion->query($sql2);
        $connexion->bind(':rut', $rut);
        $cod = $connexion->resultset();
        $cuenta2 = $cod[0]->cuenta;
    }

    if ($cuenta > 0 && $cuenta2 == 0) {
        $muestra = 1;	
        return $muestra;
    }
}

function BuscaComentariosPrivadosGeneral($rut_col, $rut_jefe) {
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_retro_bitacora WHERE rut_colaborador = :rut_col AND perfil = 'privado' AND rut_jefe = :rut_jefe ORDER BY id DESC";
    $connexion->query($sql);
    $connexion->bind(':rut_col', $rut_col);
    $connexion->bind(':rut_jefe', $rut_jefe);
    $cod = $connexion->resultset();
    return $cod;
}

function BuscaComentariosPrivados($id_plan) {
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_retro_bitacora WHERE id_plan=:id_plan AND perfil='privado'";
    $connexion->query($sql);
    $connexion->bind(':id_plan', $id_plan);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;
}

function SaveVisitaInventivos($rut, $tipo, $id_empresa) {
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");    
    $hora = date("H:i:s");
    $sql = "INSERT INTO tbl_audiencia_estadistica (rut, fecha, hora, ambiente, id_empresa) " .
           "VALUES (:rut, :fecha, :hora, :tipo, :id_empresa)";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':hora', $hora);
    $connexion->bind(':tipo', $tipo);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
}

function VeClima($rut) {
    $connexion = new DatabasePDO();
    $sql = "SELECT COUNT(id) AS cuenta FROM tbl_clima_2019 WHERE rut=:rut AND listo='not yet participated'";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod[0]->cuenta;
}

function VeClimaFull($rut) {
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_clima_2019 WHERE rut=:rut AND listo='not yet participated'";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;
}




function VeClimamIeNCUESTA($rut){

    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_clima_2019 WHERE rut=:rut AND listo IS NULL";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;

}

function Incentivos_BuscaMiAudiencia($rut, $id_empresa, $fecha_cargo){

    $connexion = new DatabasePDO();
    $sql = "
		SELECT h.id_audiencia, (SELECT COUNT(id) FROM tbl_audiencia_publicaciones WHERE id_audiencia=h.id_audiencia) AS cuenta
		FROM tbl_audiencia_id_audiencia_rut h 
		WHERE h.rut=:rut AND h.id_empresa=:id_empresa AND (SELECT COUNT(id) FROM tbl_audiencia_publicaciones WHERE id_audiencia=h.id_audiencia) > 0
		ORDER BY id DESC";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;

}

function Incentivos_Mis_Incentivos_data($id_audiencia, $id_empresa){

    $connexion = new DatabasePDO();
    
    $sql = "
    select h.*, 
        (select nombre from tbl_audiencia where id=h.id_audiencia) as nombre_audiencia,
        (select descripcion from tbl_audiencia where id=h.id_audiencia) as descripcion_audiencia,
        (select nombre from tbl_audiencia_documentos where id=h.id_documento) as nombre_documento,
        (select descripcion from tbl_audiencia_documentos where id=h.id_documento) as descripcion_documento,
        (select filename from tbl_audiencia_documentos where id=h.id_documento) as filename_documento,
        (select filename_enc from tbl_audiencia_documentos where id=h.id_documento) as filename_enc_documento
    from tbl_audiencia_publicaciones h 
    where h.id_audiencia=:id_audiencia and h.id_empresa=:id_empresa order by id DESC" ;
    
    $connexion->query($sql);
    $connexion->bind(':id_audiencia', $id_audiencia);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;	
}
    
function Incentivos_Mis_Incentivos_Documentos_Complementarios_data($id_audiencia, $id_empresa){
    
    $connexion = new DatabasePDO();
    
    $sql = "
    select h.*,
        (select nombre from tbl_audiencia_documentos where id=h.id_documento) as nombre_documento,
        (select descripcion from tbl_audiencia_documentos where id=h.id_documento) as descripcion_documento,
        (select filename from tbl_audiencia_documentos where id=h.id_documento) as filename_documento,
        (select filename_enc from tbl_audiencia_documentos where id=h.id_documento) as filename_enc_documento
    from tbl_audiencias_documento_jefe h 
    where h.id_audiencia=:id_audiencia and h.id_empresa=:id_empresa";
    
    $connexion->query($sql);
    $connexion->bind(':id_audiencia', $id_audiencia);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;
}
    
function BuscaCharla($rut, $id_charla, $id_empresa) {
    $connexion = new DatabasePDO();
    $sql = "SELECT COUNT(id) as cuenta FROM tbl_nomina_charla WHERE rut=:rut AND id_charla=:id_charla AND id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_charla', $id_charla);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod->cuenta;
}

function BuscaCharlaFull($rut, $id_charla, $id_empresa) {
    $connexion = new DatabasePDO();
    $sql = "SELECT COUNT(h.id) as cuenta, h.*, (SELECT nombre FROM tbl_postulables WHERE id_postulable=:id_charla) as nombre FROM tbl_nomina_charla h WHERE h.rut=:rut AND h.id_charla=:id_charla AND h.id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_charla', $id_charla);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function BuscaScoring($rut, $id_empresa) {
    $connexion = new DatabasePDO();
    $sql = "SELECT COUNT(id) as cuenta FROM tbl_scoring WHERE rut=:rut AND id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod->cuenta;
}

function ObtengoDimensionesDadoPerfilOpcional1($perfil) {
    $connexion = new DatabasePDO();
    $sql = "SELECT DISTINCT(tbl_sgd_componente.id_dimension), tbl_sgd_dimensiones.opcional FROM tbl_sgd_componente INNER JOIN rel_sgd_perfil_competencias ON rel_sgd_perfil_competencias.id_componente=tbl_sgd_componente.id INNER JOIN tbl_sgd_dimensiones ON tbl_sgd_dimensiones.id=tbl_sgd_componente.id_dimension WHERE rel_sgd_perfil_competencias.perfil_evaluacion=:perfil AND tbl_sgd_dimensiones.opcional='1'";
    $connexion->query($sql);
    $connexion->bind(':perfil', $perfil);
    $cod = $connexion->resultset();
    return $cod;
}
function ObtengoDimensionesDadoPerfilOpcional0($perfil)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT DISTINCT tbl_sgd_componente.id_dimension, tbl_sgd_dimensiones.opcional
    FROM tbl_sgd_componente
    INNER JOIN rel_sgd_perfil_competencias ON rel_sgd_perfil_competencias.id_componente = tbl_sgd_componente.id
    INNER JOIN tbl_sgd_dimensiones ON tbl_sgd_dimensiones.id = tbl_sgd_componente.id_dimension
    WHERE rel_sgd_perfil_competencias.perfil_evaluacion = :perfil AND tbl_sgd_dimensiones.opcional = '0'";
    $connexion->query($sql);
    $connexion->bind(':perfil', $perfil);
    $cod = $connexion->resultset();
    return $cod;
}

function BuscaReconocimientoPendientes2019($rut, $tipo, $id_empresa)
{
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $sql = "SELECT * FROM tbl_reconoce_gracias WHERE (id_categoria = 'Reconocimiento Seleccion del Chile 2019' OR id_categoria = 'COLABORACION') AND rut_jefatura = :rut AND estado = :tipo AND id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':tipo', $tipo);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function BuscaReconocimientoRemitenteAprobado2019($rut, $tipo, $id_empresa)
{
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $sql = "SELECT * FROM tbl_reconoce_gracias WHERE (id_categoria = 'Reconocimiento Seleccion del Chile 2019' OR id_categoria = 'COLABORACION') AND rut_remitente = :rut AND estado = :tipo AND id_empresa = :id_empresa AND fecha > '2019-12-01'";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':tipo', $tipo);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function MisCuiReco2019_data($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $sql = "SELECT h.*, (SELECT COUNT(id) FROM tbl_reco_noquiereconocer WHERE rut = h.rut AND cui = h.cui) AS noquiere
    FROM tbl_cui_rut_jefe h
    WHERE h.rut = :rut AND h.id_empresa = :id_empresa AND (SELECT COUNT(id) FROM tbl_reco_noquiereconocer WHERE rut = h.rut AND cui = h.cui) = '0'";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}
function MisCuiReco2019_a_repartir_data($cui, $id_empresa) {
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $sql = "SELECT COALESCE(SUM(puntos), 0) AS puntos FROM tbl_premios_puntos_usuarios_cui_jefes WHERE cui = :cui AND id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':cui', $cui);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod[0]->puntos;
}

function MisCuiReco2019_a_repartidos_data($cui, $id_empresa) {
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $sql = "SELECT COALESCE(SUM(puntos), 0) AS puntos FROM tbl_premios_puntos_usuarios_jefe_entregados_cui WHERE cui = :cui AND id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':cui', $cui);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod[0]->puntos;
}

function IsJefeRutJefedeRutCol($rut_col, $rut_jefe, $id_empresa) {
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $sql = "SELECT COUNT(id) AS cuenta FROM tbl_usuario WHERE rut = :rut_col AND jefe = :rut_jefe AND id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut_col', $rut_col);
    $connexion->bind(':rut_jefe', $rut_jefe);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod[0]->cuenta;
}

function CursoStoryInsertaValoresGet($rut, $id_objeto, $id_curso, $tipo, $valor) {
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    $sql = "INSERT INTO tbl_lms_cursos_story (rut, id_curso, id_objeto, tipo, nota, fecha, hora)
            VALUES (:rut, :id_curso, :id_objeto, :tipo, :valor, :fecha, :hora)";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':id_objeto', $id_objeto);
    $connexion->bind(':tipo', $tipo);
    $connexion->bind(':valor', $valor);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':hora', $hora);
    $connexion->execute();
}

function TiemposPorObjetoInserta($rut, $id_objeto, $tiempo_inicio)
{
    $connexion = new DatabasePDO();

    $fecha = date("Y-m-d");
    $hora  = date("H:i:s");
    $sql   = "INSERT INTO tbl_lms_tiempos_objeto (rut, id_objeto, tiempo_inicio, fecha) VALUES (:rut, :id_objeto, :tiempo_inicio, :fecha)";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_objeto', $id_objeto);
    $connexion->bind(':tiempo_inicio', $hora);
    $connexion->bind(':fecha', $fecha);
    $connexion->execute();
}


function EliminarIntentosFallidosDelDiaUser($rut, $id_empresa) {
    $connexion = new DatabasePDO();

    $fecha = date("Y-m-d");
    $sql = "DELETE FROM tbl_login_intento_acceso WHERE rut=:rut AND fecha=:fecha";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':fecha', $fecha);
    $connexion->execute();
}


function RetroActualizaAvance($tipo_fecha, $id_plan, $valor){
    $connexion = new DatabasePDO();

    if($tipo_fecha=="fecha1"){
        $sql="UPDATE tbl_retro_plan SET avance_fecha1=:valor WHERE id=:id_plan";
    }
    if($tipo_fecha=="fecha2"){
        $sql="UPDATE tbl_retro_plan SET avance_fecha2=:valor WHERE id=:id_plan";
    }   
    if($tipo_fecha=="fecha3"){
        $sql="UPDATE tbl_retro_plan SET avance_fecha3=:valor WHERE id=:id_plan";
    }   
    
    $connexion->query($sql);
    $connexion->bind(':valor', $valor);
    $connexion->bind(':id_plan', $id_plan);
    $connexion->execute();
}

function ActualizaEstadoUsuarioVigencia1Cero($rut){ 
    $connexion = new DatabasePDO();

    $sql="UPDATE tbl_usuario SET vigencia='0' WHERE rut=:rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->execute();
}
function ActualizaEstadoUsuarioVigencia1($rut){
    $connexion = new DatabasePDO();
    $sql = "UPDATE tbl_usuario SET vigencia='1' WHERE rut=:rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->execute();
}
    
function TraeIntendosAccesosFallidos($rut){
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $sql="SELECT * FROM tbl_login_intento_acceso WHERE rut=:rut AND fecha=:fecha";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':fecha', $fecha);
    $cod = $connexion->resultset();
    return ($cod);
}
    
function InsertaAccesoIntento($rut){
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    $sql = "INSERT INTO tbl_login_intento_acceso (rut,fecha,hora, ip_compartido, ip_proxy, ip_acceso)
    VALUES (:rut,:fecha,:hora,:ip_compartido,:ip_proxy,:ip_acceso)";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':hora', $hora);
    $connexion->bind(':ip_compartido', $_SERVER['HTTP_CLIENT_IP']);
    $connexion->bind(':ip_proxy', $_SERVER['HTTP_X_FORWARDED_FOR']);
    $connexion->bind(':ip_acceso', $_SERVER['REMOTE_ADDR']);
    $connexion->execute();
}
    
function Not_EncDisconSgd($rut, $id_empresa){
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*, (SELECT COUNT(id) FROM tbl_enc_elearning_respuestas WHERE id_medicion='16' AND rut=h.rut) AS cuenta
    FROM tbl_sgd_rut_encuesta h
    WHERE h.rut=:rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return ($cod);
}
function Not_ReconocidoPendiente($rut, $id_empresa){
    $connexion = new DatabasePDO();
    $sql = "select h.*, (select nombre_completo from tbl_usuario where rut=h.rut_destinatario) as NombreDestinatario,
    (select count(id) from tbl_reconoce_gracias where rut_remitente=h.rut_remitente and rut_destinatario=h.rut_destinatario and estado='OK') as Lineas
    from tbl_reconoce_gracias h WHERE
    h.estado='APROBADO' and (h.tipo='COLABORACION' OR h.tipo='RECONOCIMIENTO')
    AND (select count(id) from tbl_reconoce_gracias where rut_remitente=h.rut_remitente and rut_destinatario=h.rut_destinatario and estado='OK')=0
    AND h.id_empresa=:id_empresa
    and h.rut_remitente=:rut";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}
    
function Not_AunNoReconoceCol($rut, $id_empresa){
    $connexion = new DatabasePDO();
    $sql = "select h.*, (select count(id) from tbl_reconoce_gracias where rut_remitente=h.rut and categorita='COLABORACION' and estado='OK') as Reconocimientos
    from tbl_premios_puntos_usuarios_jefe_col h where h.rut=:rut and h.puntos>0 and (select count(id) from tbl_reconoce_gracias where rut_remitente=h.rut and categorita='COLABORACION' and estado='OK') ='0'";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return ($cod[0]->id);
}
function Not_AunNoReconoceSel($rut, $id_empresa) {
    $connexion = new DatabasePDO();
    $sql = "
        SELECT h.*, (
            SELECT COUNT(id) FROM tbl_reconoce_gracias
            WHERE rut_remitente=h.rut AND categorita='SELECCION DEL CHILE' AND estado='OK' AND fecha>='2018-05-01'
        )
        FROM tbl_premios_puntos_usuarios_jefe h
        WHERE h.rut=:rut AND h.puntos>0 AND (
            SELECT COUNT(id) FROM tbl_reconoce_gracias
            WHERE rut_remitente=h.rut AND categorita='SELECCION DEL CHILE' AND estado='OK' AND fecha>='2018-05-01'
        )='0'
    ";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return ($cod[0]->id);
}

function Not_FueReconocido($rut, $tipo, $id_empresa) {
    $connexion = new DatabasePDO();
    $sql = "
        SELECT h.id
        FROM tbl_reconoce_gracias h
        WHERE h.id_empresa=:id_empresa AND h.mensaje<>'2017' AND h.tipo='RECONOCIMIENTO' AND h.categorita=:tipo
        AND h.estado='OK' AND h.rut_destinatario=:rut
    ";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':tipo', $tipo);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return ($cod[0]->id);
}

function BuscaNominaPostulableRut($rut, $id_postulable, $id_empresa) {
    $connexion = new DatabasePDO();
    $sql = "
        SELECT COUNT(id) as cuenta
        FROM tbl_nomina_postulacion
        WHERE id_postulable=:id_postulable AND rut=:rut AND id_empresa=:id_empresa
    ";
    $connexion->query($sql);
    $connexion->bind(':id_postulable', $id_postulable);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return ($cod[0]->cuenta);
}

function Not_buscaRespuestas($rut, $id_medicion, $id_encuesta, $id_empresa){

    $connexion = new DatabasePDO();
    $sql = "
        SELECT count(id) as cuenta
        FROM tbl_enc_elearning_respuestas
        WHERE id_medicion = :id_medicion AND id_encuesta = :id_encuesta AND rut = :rut AND id_empresa = :id_empresa
    ";
    $connexion->query($sql);
    $connexion->bind(':id_medicion', $id_medicion);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return ($cod[0]->cuenta);

}

function Not_Busca_induccion_usuario($rut, $id_empresa){

    $connexion = new DatabasePDO();
    $sql = "
        SELECT h.id_malla as malla
        FROM tbl_lms_reportes h
        WHERE h.rut = :rut AND h.id_empresa = :id_empresa AND h.id_programa LIKE '%bch_induc%' LIMIT 1
    ";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return ($cod[0]->malla);

}

function TraigoUltimoJsonPorRut($rut) {

    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_json_ext WHERE rut = :rut ORDER BY fecha DESC, hora DESC";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return ($cod);

}

function InsertaSQLDinamico($sql) {

    $connexion = new DatabasePDO();
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;

}

function EliminaMetodologia($id) {
    $connexion = new DatabasePDO();
    $sql="DELETE FROM tbl_checklist_metodologia WHERE id=:id";
    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $connexion->execute();
}
    
function EliminaEstudios($id) {
    $connexion = new DatabasePDO();
    $sql="DELETE FROM tbl_checklist_estudios_formales WHERE id=:id";
    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $connexion->execute();
}
    
function EliminaExperiencias($id) {
    $connexion = new DatabasePDO();
    $sql="DELETE FROM tbl_checklist_experiencias_laborales WHERE id=:id";
    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $connexion->execute();
}
    
function InsertaExperienciaLaboral($rut, $id_empresa, $ec_11, $ec_12, $ec_13) {
    $connexion = new DatabasePDO();
    $hoy = date('Y-m-d');
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    $sql = "INSERT INTO tbl_checklist_experiencias_laborales (rut, id_empresa, tareas, empresa, duracion)
    VALUES (:rut, :id_empresa, :ec_11, :ec_12, :ec_13)";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':ec_11', $ec_11);
    $connexion->bind(':ec_12', $ec_12);
    $connexion->bind(':ec_13', $ec_13);
    $connexion->execute();
}
    
function DatosExperienciasLaborales($id_empresa, $rut) {
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_checklist_experiencias_laborales WHERE rut=:rut AND id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;
}

function InsertaEstudiosFormales($rut, $id_empresa, $ec_tes, $ec_fi, $ec_tit, $ec_ft, $ec_cf, $ec_sta, $ec_tes_otro, $ec_tit_otro, $ec_cf_otro)
{
    $connexion = new DatabasePDO();
    $hoy   = date('Y-m-d');
    $fecha = date("Y-m-d");
    $hora  = date("H:i:s");
    $sql   = "INSERT INTO tbl_checklist_estudios_formales (rut, id_empresa, tipo_estudio, fecha_inicio, nombre_estudio, fecha_termino, casa_estudio, situacion_actual, otro_estudio, otro_nombre_estudio, otra_casa_estudio)
              VALUES (:rut, :id_empresa, :ec_tes, :ec_fi, :ec_tit, :ec_ft, :ec_cf, :ec_sta, :ec_tes_otro, :ec_tit_otro, :ec_cf_otro)";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':ec_tes', $ec_tes);
    $connexion->bind(':ec_fi', $ec_fi);
    $connexion->bind(':ec_tit', $ec_tit);
    $connexion->bind(':ec_ft', $ec_ft);
    $connexion->bind(':ec_cf', $ec_cf);
    $connexion->bind(':ec_sta', $ec_sta);
    $connexion->bind(':ec_tes_otro', $ec_tes_otro);
    $connexion->bind(':ec_tit_otro', $ec_tit_otro);
    $connexion->bind(':ec_cf_otro', $ec_cf_otro);
    $connexion->execute();
}


function DatosOtrosEstudiosFormales($id_empresa, $rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT tbl_checklist_estudios_formales.*, nombre_estudio.nombre_item, nombre_casa_estudio.nombre_item as descripcion_casa_estudio
            FROM tbl_checklist_estudios_formales
            LEFT JOIN tbl_checklist_item as nombre_estudio ON nombre_estudio.id_item = tbl_checklist_estudios_formales.nombre_estudio AND nombre_estudio.tipo = 'titulos'
            LEFT JOIN tbl_checklist_item as nombre_casa_estudio ON nombre_casa_estudio.id_item = tbl_checklist_estudios_formales.casa_estudio AND nombre_casa_estudio.tipo = 'centro_formacion'
            WHERE rut = :rut AND tbl_checklist_estudios_formales.id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}


function BuscaAgradecimientobasadoenId($id, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*
            FROM tbl_reconoce_gracias h
            WHERE h.id_empresa = :id_empresa AND h.id = :id";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id', $id);
    $cod = $connexion->resultset();
    return $cod;
}

function Ben_benDimension($id_empresa, $id_dimension){
    $connexion = new DatabasePDO();

    $sql = "SELECT h.* FROM tbl_beneficios h WHERE h.id_empresa = :id_empresa AND h.id_dimension = :id_dimension GROUP BY h.id_dimension";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_dimension', $id_dimension);
    $cod = $connexion->resultset();
    return $cod;
}

function Ben_benItemsDimension($id_empresa, $id_dimension, $id_items){
    $connexion = new DatabasePDO();

    $sql = "SELECT h.* FROM tbl_beneficios h WHERE h.id_empresa = :id_empresa AND h.id_dimension = :id_dimension AND h.id_beneficios = :id_items GROUP BY h.id_beneficios";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_dimension', $id_dimension);
    $connexion->bind(':id_items', $id_items);
    $cod = $connexion->resultset();
    return $cod;
}

function BuscaTotalLineaRecoPendiente($id_enc){
    $connexion = new DatabasePDO();

    $sql = "SELECT h.* FROM tbl_reconoce_gracias h WHERE h.id = :id_enc";
    $connexion->query($sql);
    $connexion->bind(':id_enc', $id_enc);
    $cod = $connexion->resultset();
    return $cod;
}

function Ben_benlistaPorDimension_data($id_empresa, $rut, $id_dimension){
    $connexion = new DatabasePDO();
    $QueryFiltro = "";
    if ($id_empresa == "61") {
        $Usua = DatosUsuario_($rut, $id_empresa);
        $mundo = $Usua[0]->mundo;
        $QueryFiltro = " AND (h.filtro1 <> '$mundo') AND (h.filtro2 <> '$mundo')";
    }

    $sql = "SELECT h.* FROM tbl_beneficios h WHERE h.id_empresa = :id_empresa AND h.id_dimension = :id_dimension $QueryFiltro ORDER BY h.orden, h.nombre";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_dimension', $id_dimension);
    $cod = $connexion->resultset();
    return $cod;
}

function ass_save_respuesta_data($id_instrumento,$id_empresa,$fecha,$hora,$id_skill,$col_respuesta,$col_comentario, $rut_colaborador,$jefe_respuesta,$jefe_comentario,$rut_jefe) {

    $connexion = new DatabasePDO();
    $sql="select id from tbl_checklist_respuestas where id_empresa=:id_empresa and rut_colaborador=:rut_colaborador
    and id_instrumento=:id_instrumento and id_skill=:id_skill";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut_colaborador', $rut_colaborador);
    $connexion->bind(':id_instrumento', $id_instrumento);
    $connexion->bind(':id_skill', $id_skill);
    $cod = $connexion->resultset();
    
    if($rut_colaborador==''){
        echo "Falta RUT del Colaborador"; 
        sleep(3);
    } else {
        if($cod[0]->id<>'' and $rut_jefe<>''){
            $fecha = date("Y-m-d");
            $sql = "update tbl_checklist_respuestas set rut_jefe=:rut_jefe, jefe_respuesta=:jefe_respuesta,
                fecha_calibracion=:fecha where id_empresa=:id_empresa and rut_colaborador=:rut_colaborador
                and id_instrumento=:id_instrumento and id_skill=:id_skill ";
            $connexion->query($sql);
            $connexion->bind(':rut_jefe', $rut_jefe);
            $connexion->bind(':jefe_respuesta', $jefe_respuesta);
            $connexion->bind(':fecha', $fecha);
            $connexion->bind(':id_empresa', $id_empresa);
            $connexion->bind(':rut_colaborador', $rut_colaborador);
            $connexion->bind(':id_instrumento', $id_instrumento);
            $connexion->bind(':id_skill', $id_skill);
            $connexion->execute();
        } else  if($cod[0]->id<>'' and $rut_colaborador<>''){
            $sql = "update tbl_checklist_respuestas set rut_colaborador=:rut_colaborador, col_respuesta=:col_respuesta
                where id_empresa=:id_empresa and rut_colaborador=:rut_colaborador and id_instrumento=:id_instrumento
                and id_skill=:id_skill";
            $connexion->query($sql);
            $connexion->bind(':rut_colaborador', $rut_colaborador);
            $connexion->bind(':col_respuesta', $col_respuesta);
            $connexion->bind(':id_empresa', $id_empresa);
            $connexion->bind(':rut_colaborador', $rut_colaborador);
            $connexion->bind(':id_instrumento', $id_instrumento);
            $connexion->bind(':id_skill', $id_skill);
            $connexion->execute();
        }else {
            $fecha = date("Y-m-d");
            $hora  = date("H:i:s");
            $fecha_estado=$fecha;
            $sql   = "INSERT INTO tbl_checklist_respuestas
            (id_instrumento,id_empresa,fecha,hora,id_skill,col_respuesta,col_comentario,rut_colaborador,jefe_respuesta,jefe_comentario,rut_jefe)
            VALUES (:id_instrumento, :id_empresa, :fecha, :hora, :id_skill, :col_respuesta, :col_comentario, :rut_colaborador, :jefe_respuesta, :jefe_comentario, :rut_jefe)";
            $connexion->query($sql);
            $connexion->bind(':id_instrumento', $id_instrumento);
            $connexion->bind(':id_empresa', $id_empresa);
            $connexion->bind(':fecha', $fecha);
            $connexion->bind(':hora', $hora);
            $connexion->bind(':id_skill', $id_skill);
            $connexion->bind(':col_respuesta', $col_respuesta);
            $connexion->bind(':col_comentario', $col_comentario);
            $connexion->bind(':rut_colaborador', $rut_colaborador);
            $connexion->bind(':jefe_respuesta', $jefe_respuesta);
            $connexion->bind(':jefe_comentario', $jefe_comentario);
            $connexion->bind(':rut_jefe', $rut_jefe);
            $connexion->execute();
        }
    }

}


function ass_save_respuesta_ec_data($id_instrumento, $id_empresa, $fecha, $hora, $id_skill, $col_respuesta, $col_comentario, $rut_colaborador, $jefe_respuesta, $jefe_comentario, $rut_jefe, $ec, $respuesta_abierta)
{
$connexion = new DatabasePDO();
$respuesta_abierta = utf8_decode($respuesta_abierta);
$sql = "SELECT id FROM tbl_checklist_respuestas WHERE id_empresa=:id_empresa AND rut_colaborador=:rut_colaborador AND id_instrumento=:id_instrumento AND id_skill=:id_skill AND ec=:ec";
$connexion->query($sql);
$connexion->bind(':id_empresa', $id_empresa);
$connexion->bind(':rut_colaborador', $rut_colaborador);
$connexion->bind(':id_instrumento', $id_instrumento);
$connexion->bind(':id_skill', $id_skill);
$connexion->bind(':ec', $ec);
$cod = $connexion->resultset();

if ($rut_colaborador == '') {
    echo "Falta RUT del Colaborador";
    sleep(3);
} else {
    if ($cod[0]->id <> '' && $rut_jefe <> '') {
        $sql = "UPDATE tbl_checklist_respuestas SET rut_jefe=:rut_jefe, jefe_respuesta=:jefe_respuesta WHERE id_empresa=:id_empresa AND ec=:ec AND rut_colaborador=:rut_colaborador AND id_instrumento=:id_instrumento AND id_skill=:id_skill";
        $connexion->query($sql);
        $connexion->bind(':rut_jefe', $rut_jefe);
        $connexion->bind(':jefe_respuesta', $jefe_respuesta);
        $connexion->bind(':id_empresa', $id_empresa);
        $connexion->bind(':ec', $ec);
        $connexion->bind(':rut_colaborador', $rut_colaborador);
        $connexion->bind(':id_instrumento', $id_instrumento);
        $connexion->bind(':id_skill', $id_skill);
        $connexion->execute();
    } elseif ($cod[0]->id <> '' && $rut_colaborador <> '' && $respuesta_abierta <> '') {
        $sql = "UPDATE tbl_checklist_respuestas SET rut_colaborador=:rut_colaborador, respuesta_abierta=:respuesta_abierta WHERE id_empresa=:id_empresa AND ec=:ec AND rut_colaborador=:rut_colaborador AND id_instrumento=:id_instrumento AND id_skill=:id_skill";
        $connexion->query($sql);
        $connexion->bind(':rut_colaborador', $rut_colaborador);
        $connexion->bind(':respuesta_abierta', $respuesta_abierta);
        $connexion->bind(':id_empresa', $id_empresa);
        $connexion->bind(':ec', $ec);
        $connexion->bind(':rut_colaborador', $rut_colaborador);
        $connexion->bind(':id_instrumento', $id_instrumento);
        $connexion->bind(':id_skill', $id_skill);
        $connexion->execute();
    } else {
        $fecha = date("Y-m-d");
        $hora  = date("H:i:s");
        $fecha_estado = $fecha;

        $sql = "INSERT INTO tbl_checklist_respuestas
                    (id_instrumento, id_empresa, fecha, hora, id_skill, col_respuesta, col_comentario, rut_colaborador, jefe_respuesta, jefe_comentario, rut_jefe, ec, respuesta_abierta)
                VALUES 
                    (:id_instrumento, :id_empresa, :fecha, :hora, :id_skill, '', :col_comentario, :rut_colaborador, :jefe_respuesta, :jefe_comentario, :rut_jefe, :ec, :respuesta_abierta)";
        $connexion->query($sql);
        $connexion->bind(':id_instrumento', $id_instrumento);
        $connexion->bind(':id_empresa', $id_empresa);
        $connexion->bind(':fecha', $fecha);
        $connexion->bind(':hora', $hora);
        $connexion->bind(':id_skill', $id_skill);
        $connexion->bind(':col_comentario', $col_comentario);
        $connexion->bind(':rut_colaborador', $rut_colaborador);
        $connexion->bind(':jefe_respuesta', $jefe_respuesta);
        $connexion->bind(':jefe_comentario', $jefe_comentario);
        $connexion->bind(':rut_jefe', $rut_jefe);
        $connexion->bind(':ec', $ec);
        $connexion->bind(':respuesta_abierta', $respuesta_abierta);
        $connexion->execute();
    }
}
}


function Ass_busca_rut_ins_perfil_ec_respuestas($rut_col,$id_inst,  $id_empresa){
    $connexion = new DatabasePDO();
    $sql="
    select h.jefe_respuesta from tbl_checklist_respuestas h where h.id_empresa=:id_empresa and h.rut_colaborador=:rut_col and h.id_instrumento=:id_inst and jefe_respuesta<>'' and ec='ec'
    ";
    //echo "<br>Ass_busca_rut_ins_perfil_ec_respuestas<br>".$sql;exit();
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut_col', $rut_col);
    $connexion->bind(':id_inst', $id_inst);
    $cod = $connexion->resultset();
    //echo "<br>$sql";
    return $cod;
}

function TraigoOrganigramasPorEmpresa($id_empresa){
    $connexion = new DatabasePDO();
    $sql="select * from tbl_organigrama_gerencia where id_empresa=:id_empresa";
    //echo $sql;
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    //echo "<br>$sql";
    return $cod;
}

function BuscaASignacionPuntosGamificado($tipo, $rut, $id_empresa){
    $connexion = new DatabasePDO();
    $sql="SELECT count(id) as cuenta FROM tbl_gamificado_puntos WHERE rut=:rut and id_empresa=:id_empresa and id_curso LIKE :tipo limit 1";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':tipo', $tipo.'%');
    $cod = $connexion->resultset();
    //echo "<br>$sql cuenta ".$cod[0]->cuenta;
    if($cod[0]->cuenta>1){$cod[0]->cuenta=1;}
    return $cod[0]->cuenta;
}
function Triv_BuscaDimensionesArana($id_evaluacion, $id_empresa){
    $connexion = new DatabasePDO();
    $sql="SELECT h.*, (SELECT nombre FROM tbl_conductas WHERE id=h.id_conducta) AS Dimension FROM tbl_evaluaciones_preguntas h WHERE h.evaluacion=:id_evaluacion GROUP BY (SELECT nombre FROM tbl_conductas WHERE id=h.id_conducta)";
    $connexion->query($sql);
    $connexion->bind(':id_evaluacion', $id_evaluacion);
    $cod = $connexion->resultset();
    //echo "<br>$sql";
    return $cod;
}

function Triv_BuscaDatosArana($rut,$id_evaluacion,$dimension, $id_empresa)    {
    $connexion = new DatabasePDO();
    $sql="SELECT h.*, (SELECT puntaje FROM tbl_evaluaciones_respuestas WHERE rut=:rut AND id_pregunta=h.id) AS puntaje, (SELECT nombre FROM tbl_conductas WHERE id=h.id_conducta) AS Dimension FROM tbl_evaluaciones_preguntas h WHERE h.evaluacion=:id_evaluacion AND (SELECT nombre FROM tbl_conductas WHERE id=h.id_conducta)=:dimension";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_evaluacion', $id_evaluacion);
    $connexion->bind(':dimension', $dimension);
    // echo "<br>$sql";
    $cod = $connexion->resultset();
    return $cod;
}
function InsertTblAnalitica($rut, $id_empresa, $ambiente, $detalle){

$connexion = new DatabasePDO();
$fecha = date("Y-m-d");
$hora = date("H:i:s");
$fechahora = date("Y-m-d H:i:s");

$sql   = "
INSERT INTO tbl_analitica
(rut, ambiente, detalle, fecha, hora, fechahora, id_empresa)
" . "VALUES
(:rut, :ambiente, :detalle, :fecha, :hora, :fechahora, :id_empresa)";
//echo "<br><br>$sql<br><br>";sleep(10);
$connexion->query($sql);
$connexion->bind(':rut', $rut);
$connexion->bind(':ambiente', $ambiente);
$connexion->bind(':detalle', $detalle);
$connexion->bind(':fecha', $fecha);
$connexion->bind(':hora', $hora);
$connexion->bind(':fechahora', $fechahora);
$connexion->bind(':id_empresa', $id_empresa);
$connexion->execute();
}

function BuscaAvanceCursoRut($rut, $id_curso, $id_empresa){

$connexion = new DatabasePDO();
$sql = "select * from tbl_inscripcion_cierre   where rut=:rut and id_empresa=:id_empresa and id_curso=:id_curso order by avance DESC limit 1";
//echo $sql;
$connexion->query($sql);
$connexion->bind(':rut', $rut);
$connexion->bind(':id_empresa', $id_empresa);
$connexion->bind(':id_curso', $id_curso);
$cod = $connexion->resultset();

return $cod;
}

function BuscaUsuariosInscripcionUsuariosMalla($rut, $id_malla, $id_empresa){

$connexion = new DatabasePDO();
$sql = "select * from tbl_inscripcion_usuarios   where rut=:rut and id_empresa=:id_empresa and id_malla=:id_malla";
//echo $sql;
$connexion->query($sql);
$connexion->bind(':rut', $rut);
$connexion->bind(':id_empresa', $id_empresa);
$connexion->bind(':id_malla', $id_malla);
$cod = $connexion->resultset();

return $cod;
}

function ActualizaRec($id_enc,$tipo){

$connexion = new DatabasePDO();
$hoy=date("Y-m-d");
$sql = "update tbl_reconoce_gracias set estado=:tipo, fecha_validacion=:hoy where id=:id_enc";
$connexion->query($sql);
$connexion->bind(':tipo', $tipo);
$connexion->bind(':hoy', $hoy);
$connexion->bind(':id_enc', $id_enc);
$connexion->execute();
}

function BuscoRespuestasDadoRutCursoInscripcion($rut, $id_curso, $id_inscripcion,$id_objeto){

$connexion = new DatabasePDO();
$sql="select count(id) as cuenta from tbl_evaluaciones_respuestas where rut=:rut and id_objeto=:id_objeto;";
$connexion->query($sql);
$connexion->bind(':rut', $rut);
$connexion->bind(':id_objeto', $id_objeto);
$cod = $connexion->resultset();

$sql="select count(id) as cuenta from tbl_evaluaciones_respuestas where rut=:rut and id_objeto=:id_objeto and id_inscripcion=:id_inscripcion;";
$connexion->query($sql);
$connexion->bind(':rut', $rut);
$connexion->bind(':id_objeto', $id_objeto);
$connexion->bind(':id_inscripcion', $id_inscripcion);
$cod2 = $connexion->resultset();

$borrar=0;

if($cod[0]->cuenta>0 and $cod2[0]->cuenta==0)  {$borrar=1;}

return $borrar;
}

function VerificaUsuarioEmailBK($email, $id_empresa){

$connexion = new DatabasePDO();
$sql="select rut from tbl_usuario where emailBK=:email and id_empresa=:id_empresa";
$connexion->query($sql);
$connexion->bind(':email', $email);
$connexion->bind(':id_empresa', $id_empresa);
$cod = $connexion->resultset();

return $cod[0]->rut;
}

function VerificaUsuarioEmail($email, $id_empresa){
    $connexion = new DatabasePDO();
    $sql = "SELECT rut FROM tbl_usuario WHERE email=:email AND id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':email', $email);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod[0]->rut;
}

function VerificaUsuarioUserRut($user, $id_empresa){
    $connexion = new DatabasePDO();
    $sql = "SELECT rut FROM tbl_usuario WHERE rut=:user";
    $connexion->query($sql);
    $connexion->bind(':user', $user);
    $cod = $connexion->resultset();
    return $cod[0]->rut;
}

function LogEmailInsertUsuario($rut,$nombre,$email,$rut_empresa,$codigo,$id_empresa){
    $connexion = new DatabasePDO();
    $sql = "SELECT id FROM tbl_usuario WHERE id_empresa=:id_empresa AND rut=:rut";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    $nombre = utf8_decode($nombre);
    if ($cod[0]->id > 0) {
        $sql = "UPDATE tbl_usuario SET nombre_completo=:nombre, email=:email, empresa_holding=:rut_empresa, division=:codigo WHERE rut=:rut";
    } else {
        $sql = "INSERT INTO tbl_usuario (rut, id_empresa, nombre_completo, email, empresa_holding, division) VALUES (:rut, :id_empresa, :nombre, :email, :rut_empresa, :codigo)";
    }
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':nombre', $nombre);
    $connexion->bind(':email', $email);
    $connexion->bind(':rut_empresa', $rut_empresa);
    $connexion->bind(':codigo', $codigo);
    $connexion->execute();
    return $codigo;
}

function LogInsertUsuario($rut,$id_empresa){
    $connexion = new DatabasePDO();
    $sql = "INSERT INTO tbl_usuario (rut, id_empresa) VALUES (:rut, :id_empresa)";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
}

function EstaEnInscripcionCierre($rut, $id_curso, $id_empresa){
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_inscripcion_cierre WHERE rut=:rut AND id_curso=:id_curso AND id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':id_empresa', $id_empresa);
    $datos = $connexion->resultset();
    return $datos;
}

function BuscaEmailCodRut($id, $rut_col,$id_empresa){
    $connexion = new DatabasePDO();
    $sql = "SELECT COUNT(id) AS cuenta FROM tbl_log_emails WHERE rut=:rut AND dato=:id AND id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut_col);
    $connexion->bind(':id', $id);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod[0]->cuenta;
}

function BuscaSucursalCui_data($id_cui_col,$id_empresa){
    $connexion = new DatabasePDO();
    $sql = "SELECT sucursal FROM tbl_usuario WHERE local=:id_cui_col AND id_empresa=:id_empresa AND sucursal<>'' LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':id_cui_col', $id_cui_col);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
return $cod;
}

function CertEscribeInscripcionCierreEnProceso($rut, $id_malla) {
    $connexion = new DatabasePDO();
    $id_empresa = $_SESSION["id_empresa"];
    $sql = "
        SELECT h.rut, h.id_inscripcion, h.id_curso,
            (SELECT COUNT(DISTINCT(id)) FROM tbl_inscripcion_cierre WHERE id_curso=h.id_curso AND estado='1' AND rut=h.rut) AS cuenta_inscripcion_cierre,
            (SELECT COUNT(DISTINCT(id)) FROM tbl_objetos_finalizados WHERE id_objeto=(SELECT id FROM tbl_objeto WHERE id_curso=h.id_curso AND tipo_objeto=5 LIMIT 1) AND rut=h.rut) AS cuenta_objeto_finalizado,
            (SELECT COUNT(DISTINCT(id)) FROM tbl_evaluaciones_respuestas_intentos WHERE id_objeto=(SELECT id FROM tbl_objeto WHERE id_curso=h.id_curso AND tipo_objeto=5 LIMIT 1) AND rut=h.rut) AS cuenta_usuarios_respuestas_intentos,
            (SELECT COUNT(DISTINCT(id)) FROM tbl_evaluaciones_respuestas WHERE id_objeto=(SELECT id FROM tbl_objeto WHERE id_curso=h.id_curso AND tipo_objeto=5 LIMIT 1 ) AND rut=h.rut) AS cuenta_usuarios_respuestas
        FROM tbl_inscripcion_usuarios h
        WHERE h.id_malla=:id_malla AND h.rut=:rut AND (SELECT id FROM tbl_objeto WHERE id_curso=h.id_curso AND tipo_objeto=5 LIMIT 1) IS NOT NULL
            AND (SELECT COUNT(DISTINCT(id)) FROM tbl_inscripcion_cierre WHERE id_curso=h.id_curso AND estado='1' AND rut=h.rut)='0'
            AND (SELECT COUNT(DISTINCT(id)) FROM tbl_evaluaciones_respuestas WHERE id_objeto=(SELECT id FROM tbl_objeto WHERE id_curso=h.id_curso AND tipo_objeto=5 LIMIT 1) AND rut=h.rut) > 0
    ";
    $connexion->query($sql);
    $connexion->bind(':id_malla', $id_malla);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();

    foreach($cod as $unico) {
        if($unico->cuenta_inscripcion_cierre == 0) {
            $sql = "INSERT INTO tbl_inscripcion_cierre (rut, id_empresa, estado, avance, id_curso, id_inscripcion) VALUES (:rut, :id_empresa, '3', '50', :id_curso, :id_inscripcion)";
            $connexion->query($sql);
            $connexion->bind(':rut', $rut);
            $connexion->bind(':id_empresa', $id_empresa);
            $connexion->bind(':id_curso', $unico->id_curso);
            $connexion->bind(':id_inscripcion', $unico->id_inscripcion);
        } else {
            $sql = "UPDATE tbl_inscripcion_cierre SET id_empresa=:id_empresa, estado='3', avance='50' WHERE rut=:rut AND id_curso=:id_curso AND id_inscripcion=:id_inscripcion AND id_empresa=:id_empresa";
            $connexion->query($sql);
            $connexion->bind(':rut', $rut);
            $connexion->bind(':id_empresa', $id_empresa);
            $connexion->bind(':id_curso', $unico->id_curso);
            $connexion->bind(':id_inscripcion', $unico->id_inscripcion);
        }
    }

    $connexion->execute();
    $cod = $connexion->resultset();

    return $cod;
}

function VerificaRutPremioExclusion($rut,$id_premio, $id_empresa) {
    $connexion = new DatabasePDO();
    $sql = "SELECT id FROM tbl_premios_puntos_excepcion WHERE rut = :rut AND id_premio = :id_premio AND id_empresa = :id_empresa LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_premio', $id_premio);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod[0]->id;
}

function LMS_MiEquipoVista_Data($id_empresa, $rut, $id_curso, $id_programa, $id_foco){
    $connexion = new DatabasePDO();

    $limit = " limit 10 ";
    $groupby = " group by h.$groupby ";

    if ($id_programa == "") {
        $query_id_programa = "  ";
        $query_id_programaH = "  ";
    }
    else {
        $query_id_programa = "        AND tbl_lms_reportes.id_programa = :id_programa";
        $query_id_programaH = "        AND h.id_programa = :id_programa";
    }

    if ($id_foco == "") {
        $query_id_foco = "  ";
        $query_id_focoH = "  ";
    }
    else {
        $query_id_foco = "        AND tbl_lms_reportes.id_foco = :id_foco";
        $query_id_focoH = "        AND h.id_foco = :id_foco";
    }

    if ($rut == "") {
        $query_rut = "  ";
    }
    else {
        $query_rut = " and
            (
                h.jefe = :rut

            )
            ";
        $limit = " limit 10 ";
        $order = "order by poc_aprobados DESC, total_medallas DESC, total_puntos DESC ";
    }

    $sql = "select h.rut, h.nombre ,  h.cargo, h.c1, h.c2, h.c3, h.c4,
        sum(h.puntos) as total_puntos,
        sum(h.medallas) as total_medallas,
        count(id) as total_cursos,
        (
                SELECT
                    count(tbl_lms_reportes.id)
                FROM
                    tbl_lms_reportes
                INNER JOIN rel_lms_malla_curso ON rel_lms_malla_curso.id_curso = tbl_lms_reportes.id_curso
                WHERE
                    tbl_lms_reportes.id_empresa = :id_empresa
                AND rut = h.rut
                $query_id_programa
                $query_id_foco
                AND rel_lms_malla_curso.inactivo = '0'
            and tbl_lms_reportes.estado='APROBADO'
            ) AS num_aprobados,
        (
                SELECT
                    count(tbl_lms_reportes.id)
                FROM
                    tbl_lms_reportes
                INNER JOIN rel_lms_malla_curso ON rel_lms_malla_curso.id_curso = tbl_lms_reportes.id_curso
                WHERE
                    tbl_lms_reportes.id_empresa = :id_empresa
                AND rut = h.rut
                $query_id_programa
                $query_id_foco
                AND rel_lms_malla_curso.inactivo = '0'
            and (tbl_lms_reportes.estado='APROBADO' or tbl_lms_reportes.estado='REPROBADO')
            ) AS num_realizados

        from tbl_lms_reportes h

        where

        h.id_empresa=:id_empresa

        and h.id_curso<>''

        $query_id_programaH
        $query_id_focoH
        $query_rut

        and (select inactivo from rel_lms_malla_curso where id_curso=h.id_curso and id_malla=h.id_malla)='0'
        and h.nombre<>''
        and h.nombre<>'USUARIO INACTIVO'
        group by h.rut

        ORDER BY

        h.nombre";


    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    
    return $cod;
}
function LMS_MiEquipoVista_Cursos_disponibles_Data($id_empresa, $rut, $id_curso, $id_programa, $id_foco)
{
    $connexion = new DatabasePDO();
    $limit = " limit 10 ";
    $groupby = " group by h.$groupby ";

    // $order = "order by poc_aprobados DESC, total_medallas DESC, total_puntos DESC ";

    if ($id_programa == "") {
        $query_id_programa = "  ";
        $query_id_programaH = "  ";
    }
    else {
        $query_id_programa = "        AND tbl_lms_reportes.id_programa = :id_programa";
        $query_id_programaH = "        AND h.id_programa = :id_programa";
        $connexion->bind(':id_programa', $id_programa);
    }

    if ($id_foco == "") {
        $query_id_foco = "  ";
        $query_id_focoH = "  ";
    }
    else {
        $query_id_foco = "        AND tbl_lms_reportes.id_foco = :id_foco";
        $query_id_focoH = "        AND h.id_foco = :id_foco";
        $connexion->bind(':id_foco', $id_foco);
    }

    if ($rut == "") {
        $query_rut = "  ";
    }
    else {
        $query_rut = " and
            (
                h.jefe = :rut

            )
            ";
        $limit = " limit 10 ";
        $order = "order by poc_aprobados DESC, total_medallas DESC, total_puntos DESC ";
        $connexion->bind(':rut', $rut);
    }

    $sql = "select h.* from tbl_lms_curso h where h.modalidad='2' order by h.nombre limit 50";
    $connexion->query($sql);
    $cod = $connexion->resultset();

    // echo "<br />DATA Ranking_desafios_avance_Data <br />$sql<br />";

    return $cod;
}


function LMS_MiEquiposVista_Cursos_Data($id_empresa, $rut, $id_curso, $id_programa, $id_foco)
{
    $connexion = new DatabasePDO();

    $limit = " limit 10 ";
    $groupby = " group by h.$groupby ";

    if ($id_empresa == "61") {
        $quer_quita = " and h.id_programa <> 'rf_cert_rg' and h.id_programa <> 'rf_cert_rgcn' ";
    } else {
        $quer_quita = "";
    }

    if ($id_programa == "") {
        $query_id_programa = "  ";
        $query_id_programaH = "  ";
    } else {
        $query_id_programa = "        AND tbl_lms_reportes.id_programa = :id_programa";
        $query_id_programaH = "        AND h.id_programa = :id_programa";
    }

    if ($id_foco == "") {
        $query_id_foco = "  ";
        $query_id_focoH = "  ";
    } else {
        $query_id_foco = "        AND tbl_lms_reportes.id_foco = :id_foco";
        $query_id_focoH = "        AND h.id_foco = :id_foco";
    }

    if ($rut == "") {
        $query_rut = "  ";
    } else {
        $query_rut = " and
            (
                h.rut = :rut

            )
            ";
        $limit = " limit 10 ";
        $order = "order by poc_aprobados DESC, total_medallas DESC, total_puntos DESC ";
    }

    $sql = "

            select h.*,
            (select round(avg(avance)) from tbl_lms_reportes where rut=h.rut and id_programa=h.id_programa and curso_opcional='0' ) as AvancePromedio,

            (select nombre from tbl_lms_curso where id=h.id_curso limit 1) as nombre_curso,
            (
            SELECT
            nombre_programa
            FROM
            tbl_lms_programas_bbdd
            WHERE
            id_programa = h.id_programa
            LIMIT 1
            ) as nombre_programa,
            (select opcional from tbl_lms_programas_bbdd where id_programa=h.id_programa limit 1) as opcional_programa,
            (select inactivo from tbl_lms_programas_bbdd where id_programa=h.id_programa limit 1) as inactivo_programa
            from tbl_lms_reportes h
            where
            h.id_empresa=:id_empresa
            and h.id_curso<>''

            $query_id_programaH
            $query_id_focoH
            $query_rut
            and (select inactivo from rel_lms_malla_curso where id_curso=h.id_curso and id_malla=h.id_malla)='0'
            and h.nombre<>'' and h.nombre<>'USUARIO INACTIVO'

            AND (select opcional from tbl_lms_programas_bbdd where id_programa=h.id_programa limit 1) is null
            AND (select inactivo from tbl_lms_programas_bbdd where id_programa=h.id_programa limit 1) is null
            AND

            (
            (
            SELECT
            opcional
            FROM
            rel_lms_malla_persona
            WHERE
            id_malla = h.id_malla and rut=h.rut
            LIMIT 1
            ) is null

            or

                        (
                            SELECT
                                opcional
                            FROM
                                rel_lms_malla_persona
                            WHERE
                                id_malla = h.id_malla and rut=h.rut
                            LIMIT 1
                        ) =''
            )
            $quer_quita

            GROUP BY h.id_programa

            ORDER BY (
            SELECT
            nombre_programa
            FROM
            tbl_lms_programas_bbdd
            WHERE
            id_programa = h.id_programa
            LIMIT 1
            )

            ";


$connexion->query($sql);
$connexion->bind(':id_empresa', $id_empresa);
$cod = $connexion->resultset();

return $cod;
}


function Obtener_medallas_sin_puntos($rut, $id_empresa){
    $connexion = new DatabasePDO();
    $sql = "
    select h.*, (select titulo from tbl_objeto where id=h.id_objeto) as NombreObjeto,
    (select id_curso from tbl_objeto where id=h.id_objeto) as IdCurso,
    (select nombre from tbl_lms_curso where id=(select id_curso from tbl_objeto where id=h.id_objeto) )as Curso
    from tbl_gamificado_puntos h where h.rut=:rut and h.id_empresa=:id_empresa
    and h.medallas>0
    and (SELECT id FROM tbl_premios_puntos_usuarios WHERE rut=h.rut and descripcion=(select nombre from tbl_lms_curso where id=(select id_curso from tbl_objeto where id=h.id_objeto) )) is null
    ";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function Save_Medallas_sin_puntos($rut,$fecha,$puntos,$tipo,$descripcion,$id_empresa)
{
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $hora  = date("H:i:s");
    $sql   = "insert into tbl_premios_puntos_usuarios (rut,fecha,puntos,tipo,descripcion,id_empresa) values (:rut, :fecha, :puntos, :tipo, :descripcion, :id_empresa)";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':puntos', $puntos);
    $connexion->bind(':tipo', $tipo);
    $connexion->bind(':descripcion', $descripcion);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
}
function VerificaEtapa1Col($rut, $id_cargo, $id_empresa){
    $connexion = new DatabasePDO();

    $sql = "SELECT count(id) AS cuenta FROM tbl_checklist_respuestas WHERE id_instrumento=:id_cargo AND id_empresa=:id_empresa AND rut_colaborador=:rut";

    $connexion->query($sql);
    $connexion->bind(':id_cargo', $id_cargo);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);

    $cod = $connexion->resultset();

    return $cod[0]->cuenta;
}
function ObtenerMedallasBajo80($rut, $id_empresa) {
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*, (SELECT medallas FROM tbl_objeto WHERE id = h.id_objeto) as medallas
            FROM tbl_objetos_finalizados h 
            WHERE h.rut = :rut AND h.id_objeto LIKE 'lg_desafio_%' AND nota >= 75";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}

function Save_Medallas_Bajo80($rut, $id_objeto, $medallas) {
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $hora  = date("H:i:s");
    $sql   = "UPDATE tbl_gamificado_puntos SET medallas = :medallas 
              WHERE rut = :rut AND id_objeto = :id_objeto";
    $connexion->query($sql);
    $connexion->bind(':medallas', $medallas);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_objeto', $id_objeto);
    $connexion->execute();
}

function Ranking_desafios_avance_C1C2C3C4_Data($c1c2c3c4, $id_programa, $id_foco, $id_empresa, $jefe, $groupby)
{
    $connexion = new DatabasePDO();
    $limit = " limit 10 ";

    if ($id_empresa == "75") {
        $query_Sacar = " and h.c1<>'gerencia' and h.c1<>'GERENCIA GENERAL'";
    } else {
        $query_Sacar = "";
    }

    $wherec1c2c3c4 = " and h.$groupby <>'' ";
    $qgerencia = " h.$groupby as gerencia ";
    $groupby = " group by h.$groupby ";

    if ($id_programa == "") {
        $query_id_programa = "  ";
        $query_id_programaH = "  ";
    } else {
        $query_id_programa = " AND tbl_lms_reportes.id_programa = :id_programa";
        $query_id_programaH = " AND h.id_programa = :id_programa";
    }

    if ($id_foco == "") {
        $query_id_foco = "  ";
        $query_id_focoH = "  ";
    } else {
        $query_id_foco = " AND tbl_lms_reportes.id_foco = :id_foco";
        $query_id_focoH = " AND h.id_foco = :id_foco";
    }

    if ($rut == "") {
        $query_rut = "  ";
    } else {
        $query_rut = " and h.rut=:rut and h.id_malla=:id_malla";
        $limit = " limit 10 ";
        $order = "order by poc_aprobados DESC, total_medallas DESC, total_puntos DESC ";
    }

    if ($jefe == "") {
        $query_jefe = "  ";
    } else {
        $query_jefe = " and (
        (select jefe from tbl_usuario where rut=h.rut) = :jefe or (select lider from tbl_usuario where rut=h.rut) = :jefe
        )";
        $limit = "";
        $order = " order by h.id_malla,  poc_aprobados DESC, total_medallas DESC, total_puntos DESC ";
    }

    $sql = "select h.c1, h.c2, h.c3, h.c4,
        sum(h.puntos) as total_puntos,
        sum(h.medallas) as total_medallas,
        count(id) as total_cursos,
        round(avg(avance)) as avance,
        round(avg(avance)) AS avance,
        sum(avance) AS SumAvance,
        count(avance) AS CountAvance,
        round(sum(avance) / count(avance)) AS PorcAvance,
        $qgerencia
        from tbl_lms_reportes h
        where
        h.id_empresa= :id_empresa
        and h.id_curso<> ''
        $query_id_programaH
        $query_id_focoH
        and (select inactivo from rel_lms_malla_curso where id_curso=h.id_curso and id_malla=h.id_malla)='0'
        and h.nombre<> ''
        and h.nombre<> 'USUARIO INACTIVO'
        $wherec1c2c3c4
        $query_Sacar
        $groupby
        order by round(sum(avance) / count(avance))  DESC
    ";
$connexion->query($sql);
$connexion->bind(':id_empresa', $id_empresa);
$cod = $connexion->resultset();
return $cod;
}

function Wa_Ranking_desafios($rut, $id_malla, $id_empresa, $jefe) {
    $connexion = new DatabasePDO();

    $limit =" limit 10 ";    $order ="order by poc_aprobados DESC, total_medallas DESC, total_puntos DESC ";
    if($rut==""){

        $query_rut="  ";
    } else {

        $query_rut=" and h.rut='$rut' and h.id_malla='$id_malla'";
        $limit =" limit 10 ";
        $order ="order by poc_aprobados DESC, total_medallas DESC, total_puntos DESC ";
    }


    if($jefe==""){
        $query_jefe="  ";

    $sql="select h.rut, h.nombre ,
    (select inactivo from rel_lms_malla_curso where id_curso=h.id_curso and id_malla=h.id_malla),
    sum(h.puntos) as total_puntos,
    sum(h.medallas) as total_medallas,
    count(id) as total_cursos,
    (
            SELECT
                count(tbl_lms_reportes.id)
            FROM
                tbl_lms_reportes
            INNER JOIN rel_lms_malla_curso ON rel_lms_malla_curso.id_curso = tbl_lms_reportes.id_curso
            WHERE
                tbl_lms_reportes.id_empresa = '$id_empresa'
            AND rut = h.rut
            AND rel_lms_malla_curso.id_malla = '$id_malla'
            AND rel_lms_malla_curso.id_clasificacion = 'wa_desafios'
            AND rel_lms_malla_curso.inactivo = '0'
        and tbl_lms_reportes.estado='APROBADO'
        ) AS num_aprobados,
    (
            SELECT
                count(tbl_lms_reportes.id)
            FROM
                tbl_lms_reportes
            INNER JOIN rel_lms_malla_curso ON rel_lms_malla_curso.id_curso = tbl_lms_reportes.id_curso
            WHERE
                tbl_lms_reportes.id_empresa = '$id_empresa'
            AND rut = h.rut
            AND rel_lms_malla_curso.id_malla = '$id_malla'
            AND rel_lms_malla_curso.id_clasificacion = 'wa_desafios'
            AND rel_lms_malla_curso.inactivo = '0'
        and (tbl_lms_reportes.estado='APROBADO' or tbl_lms_reportes.estado='REPROBADO')
        ) AS num_realizados,



    round(100*(( SELECT count(tbl_lms_reportes.id) FROM tbl_lms_reportes INNER JOIN rel_lms_malla_curso ON rel_lms_malla_curso.id_curso = tbl_lms_reportes.id_curso WHERE tbl_lms_reportes.id_empresa = '$id_empresa' AND rut = h.rut AND rel_lms_malla_curso.id_malla = '$id_malla' AND rel_lms_malla_curso.id_clasificacion = 'wa_desafios' AND rel_lms_malla_curso.inactivo = '0' and tbl_lms_reportes.estado='APROBADO' )/count(id))) as poc_aprobados,
    round(
            (
                (
                    SELECT
                        AVG(tbl_lms_reportes.avance)
                    FROM
                        tbl_lms_reportes
                    INNER JOIN rel_lms_malla_curso ON rel_lms_malla_curso.id_curso = tbl_lms_reportes.id_curso
                    WHERE
                        tbl_lms_reportes.id_empresa = '$id_empresa'
                    AND rut = h.rut
                    AND tbl_lms_reportes.id_malla = '$id_malla'
                    AND tbl_lms_reportes.id_clasificacion = 'wa_desafios'
                )
            )
        ) as avance
    from tbl_lms_reportes h

    where

    h.id_empresa='$id_empresa'

    and h.id_curso<>''

    and h.id_clasificacion='wa_desafios'

    $query_rut

    $query_jefe


    and (select inactivo from rel_lms_malla_curso where id_curso=h.id_curso and id_malla=h.id_malla)='0'
    and h.nombre<>''
    and h.nombre<>'USUARIO INACTIVO'
    group by h.rut
        $order
        $limit
    ";

        } else {
            $query_jefe=" and (
            (select jefe from tbl_usuario where rut=h.rut) ='$jefe' or (select lider from tbl_usuario where rut=h.rut) ='$jefe'
            )";

            $limit="";
            $order =" order by h.id_malla,  poc_aprobados DESC, total_medallas DESC, total_puntos DESC ";

    $sql="select h.rut, h.nombre ,
    (select inactivo from rel_lms_malla_curso where id_curso=h.id_curso and id_malla=h.id_malla),
    sum(h.puntos) as total_puntos,
    sum(h.medallas) as total_medallas,
    count(id) as total_cursos,
    (
            SELECT
                count(tbl_lms_reportes.id)
            FROM
                tbl_lms_reportes
            WHERE
                tbl_lms_reportes.id_empresa = '$id_empresa'
            AND rut =h.rut
            AND tbl_lms_reportes.id_clasificacion = 'wa_desafios'
            AND (
                tbl_lms_reportes.estado = 'APROBADO'
            )
        ) AS num_aprobados,
        (
    SELECT
                count(tbl_lms_reportes.id)
            FROM
                tbl_lms_reportes
            WHERE
                tbl_lms_reportes.id_empresa = '$id_empresa'
            AND rut =h.rut
            AND tbl_lms_reportes.id_clasificacion = 'wa_desafios'
            AND (
                tbl_lms_reportes.estado = 'APROBADO'
                OR tbl_lms_reportes.estado = 'REPROBADO'
            )
        ) AS num_realizados,
        round(
            100 * (
                (
    SELECT
                count(tbl_lms_reportes.id)
            FROM
                tbl_lms_reportes
            WHERE
                tbl_lms_reportes.id_empresa = '$id_empresa'
            AND rut =h.rut
            AND tbl_lms_reportes.id_clasificacion = 'wa_desafios'
            AND (
                tbl_lms_reportes.estado = 'APROBADO'
            )
                ) / count(id)
            )
        ) AS poc_aprobados,
        round(
            (
                (
                    SELECT
                        AVG(tbl_lms_reportes.avance)
                    FROM
                        tbl_lms_reportes
                    WHERE
                        tbl_lms_reportes.id_empresa = '$id_empresa'
                    AND rut = h.rut
                    AND tbl_lms_reportes.id_clasificacion = 'wa_desafios'
                )
            )
        ) AS avance
    from tbl_lms_reportes h

    where

    h.id_empresa='$id_empresa'

    and h.id_curso<>''

    and h.id_clasificacion='wa_desafios'

    $query_rut

    $query_jefe


    and (select inactivo from rel_lms_malla_curso where id_curso=h.id_curso and id_malla=h.id_malla)='0'
    and h.nombre<>''
    and h.nombre<>'USUARIO INACTIVO'
    group by h.rut
        $order
        $limit
    ";
  }
  $connexion->query($sql);
  $connexion->bind(':id_empresa', $id_empresa);
  $cod = $connexion->resultset();
  return $cod;
}

function mi_equipo_lider_xls_data($tipo_reporte, $rut, $id_empresa)
{
    $connexion = new DatabasePDO();

    if ($tipo_reporte == "Consolidado") {
        $jquery = "GROUP BY h.rut";
    } elseif ($tipo_reporte == "Detalle") {
        $jquery = "GROUP BY h.rut, h.id_curso";
    }

    $sql = "
        SELECT
            h.rut_completo,
            h.nombre,
            h.cargo,
            h.email,
            h.c1,
            h.c2,
            h.c3,
            h.id_malla,
            h.id_curso,
            (
                SELECT
                    nombre
                FROM
                    tbl_lms_curso
                WHERE
                    id = h.id_curso
            ) AS curso,
            sum(h.puntos),
            sum(h.medallas),
            h.estado,
            round(avg(h.avance)) as avance,
            (
                SELECT
                    inactivo
                FROM
                    rel_lms_malla_curso
                WHERE
                    id_curso = h.id_curso
                    AND id_malla = h.id_malla
                LIMIT 1
            ) AS inactivo,
            round(avg(h.resultado)) AS nota
        FROM
            tbl_lms_reportes h
        WHERE
            (
                h.jefe = :rut
                OR h.lider = :rut
            )
        $jquery";

    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();

    //echo "<br><br>SQL $sql<br>";

    return $cod;
}

function Ranking_Cursos_individual_c3_c4($id_curso, $id_tipo, $campoc3, $campoc4, $c3, $c4, $id_empresa) {

    $connexion = new DatabasePDO();

    //echo  "$id_curso, $id_tipo, $id_empresa";
    if ($id_tipo == "individual") {

        $sql = "SELECT h.*, 
            (SELECT $campoc3 FROM tbl_usuario WHERE rut=h.rut) as c3, 
            (SELECT $campoc4 FROM tbl_usuario WHERE rut=h.rut) as c4 
        FROM tbl_inscripcion_cierre h 
        WHERE h.id_curso=:id_curso AND id_empresa=:id_empresa 
            AND (SELECT $campoc3 FROM tbl_usuario WHERE rut=h.rut)=:c3 
            AND (SELECT $campoc4 FROM tbl_usuario WHERE rut=h.rut)=:c4 
        ORDER BY nota DESC, id ASC LIMIT 10";

        $connexion->query($sql);
        $connexion->bind(':id_curso', $id_curso);
        $connexion->bind(':id_empresa', $id_empresa);
        $connexion->bind(':c3', $c3);
        $connexion->bind(':c4', $c4);
    }

    if ($id_tipo == "c3") {

        $sql = "SELECT round(avg(h.nota)) as nota, 
            (SELECT $campoc3 FROM tbl_usuario WHERE rut=h.rut) AS c3, 
            count(id) as Cuenta, 
            (SELECT count(id) FROM tbl_usuario WHERE id_empresa=h.id_empresa AND $campoc3=(
                SELECT $campoc3 FROM tbl_usuario WHERE rut=h.rut
            )), 
            round(100*(count(id)/(SELECT count(id) FROM tbl_usuario WHERE id_empresa=h.id_empresa AND $campoc3=(
                SELECT $campoc3 FROM tbl_usuario WHERE rut=h.rut
            ))),1) as avance, 
            (SELECT $campoc4 FROM tbl_usuario WHERE rut=h.rut) AS c4 
        FROM tbl_inscripcion_cierre h 
        WHERE h.id_curso=:id_curso AND id_empresa=:id_empresa 
        GROUP BY (SELECT $campoc3 FROM tbl_usuario WHERE rut=h.rut) 
        ORDER BY round(100*(count(id)/(SELECT count(id) FROM tbl_usuario WHERE id_empresa=h.id_empresa AND $campoc3=(
            SELECT $campoc3 FROM tbl_usuario WHERE rut=h.rut
        )))) ASC, nota DESC LIMIT 10";

        $connexion->query($sql);
        $connexion->bind(':id_curso', $id_curso);
        $connexion->bind(':id_empresa', $id_empresa);
    }

    if($id_tipo=="c4") {

        $sql = "SELECT round(avg(h.nota)) as nota, 
                    (SELECT $campoc4 FROM tbl_usuario WHERE rut = h.rut) AS c3, 
                    count(id) as Cuenta,
                    (SELECT count(id) FROM tbl_usuario WHERE id_empresa=h.id_empresa and $campoc4=(SELECT $campoc3 FROM tbl_usuario WHERE rut = h.rut)),
                    round(100*(count(id)/(SELECT count(id) FROM tbl_usuario WHERE id_empresa=h.id_empresa and $campoc4=(SELECT $campoc4 FROM tbl_usuario WHERE rut = h.rut))),1) as avance,
                    (SELECT $campoc4 FROM tbl_usuario WHERE rut = h.rut) AS c4
                FROM tbl_inscripcion_cierre h
                WHERE h.id_curso = :id_curso AND id_empresa = :id_empresa
                GROUP BY (SELECT $campoc4 FROM tbl_usuario WHERE rut = h.rut)
                ORDER BY round(100*(count(id)/(SELECT count(id) FROM tbl_usuario WHERE id_empresa=h.id_empresa and $campoc4=(SELECT $campoc4 FROM tbl_usuario WHERE rut = h.rut)))) ASC, nota DESC
                LIMIT 10";
                
        $connexion->query($sql);
        $connexion->bind(':id_curso', $id_curso);
        $connexion->bind(':id_empresa', $id_empresa);
        $cod = $connexion->resultset();

        //echo "<br><br>SQL $sql<br>";

        return $cod;
    }
}

function BuscaTemplatePrograma($id_programa, $id_empresa){
    $connexion = new DatabasePDO();
    $sql="SELECT template_programa FROM tbl_lms_programas_bbdd WHERE id_programa = :id_programa AND id_empresa = :id_empresa LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':id_programa', $id_programa);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod[0]->template_programa;
}

function BuscaJefaturaReco($rut, $id_empresa){
    $connexion = new DatabasePDO();
    $sql="SELECT jefe FROM tbl_usuario WHERE rut = :rut AND id_empresa = :id_empresa LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod[0]->jefe;
}

function nombre_categoria_reco($categoria, $id_empresa){
    $connexion = new DatabasePDO();
    $sql="SELECT categoria FROM tbl_reconoce_categorias WHERE id_categoria = :categoria AND id_empresa = :id_empresa LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':categoria', $categoria);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod[0]->categoria;
}



function nombre_categoria_reco_full($categoria, $id_empresa){

    $connexion = new DatabasePDO();
    $sql="select *
    from tbl_reconoce_categorias
    where id_categoria=:categoria and id_empresa=:id_empresa limit 1";
    $connexion->query($sql);
    $connexion->bind(':categoria', $categoria);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}


function BuscaComentariosFinalEncuestasat($id_encuesta,$id_medicion, $rut, $id_empresa){

    $connexion = new DatabasePDO();
    $sql="select comentario from tbl_enc_elearning_respuestas where
    id_encuesta=:id_encuesta and id_medicion=:id_medicion and id_empresa=:id_empresa
    and rut=:rut and comentario<>''
    limit 1";
    $connexion->query($sql);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_medicion', $id_medicion);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}


function BuscaCantidadInstanciasInscripcionUsuario($id_curso, $id_malla, $id_empresa, $rut){

    $connexion = new DatabasePDO();
    $sql="select * from tbl_inscripcion_usuarios where id_curso=:id_curso and id_malla=:id_malla and rut=:rut and id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':id_malla', $id_malla);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}


function BuscaCantidadInstanciasInscripcionUsuario_RelLmsMallaPersona2020($id_malla, $id_empresa, $rut){

    $connexion = new DatabasePDO();
    $sql="select * from rel_lms_malla_persona where id_malla=:id_malla and rut=:rut and id_empresa=:id_empresa";
    if($rut=="12345"){
        //echo $sql;    
    }
    $connexion->query($sql);
    $connexion->bind(':id_malla', $id_malla);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function Busca_Reco_Aprobado($rut_destinatario, $rut_remitente, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT id FROM tbl_reconoce_gracias WHERE estado='APROBADO' AND rut_remitente=:rut_remitente AND rut_destinatario=:rut_destinatario AND id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut_destinatario', $rut_destinatario);
    $connexion->bind(':rut_remitente', $rut_remitente);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod[0]->id;
}

function premios_puntos_a_vencer($rut, $saldo_puntos, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT puntos, fecha FROM tbl_premios_puntos_usuarios WHERE rut=:rut AND id_empresa=:id_empresa ORDER BY id DESC LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    $puntos = $cod[0]->puntos;
    if($puntos >= $saldo_puntos)
    {
        $puntos_a_vencer = $saldo_puntos;
    }
    else
    {
        $puntos_a_vencer = $puntos;
    }

    $new_fecha = date('Y-m-d H:i:s', strtotime('+1 years', strtotime($cod[0]->fecha)));

    $arreglo[0]->puntos = $puntos_a_vencer;
    $arreglo[0]->fecha = $new_fecha;

    return $arreglo;
}

function VerificaEstadoCursoRut($rut, $id_curso, $id_empresa) {

    $connexion = new DatabasePDO();
    $sql="SELECT avance FROM tbl_inscripcion_cierre WHERE id_curso=:id_curso AND rut=:rut AND id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod[0]->avance;
}
    
function Lms_Busca_MasdeunaMalla($rut, $id_programa, $id_empresa) {
    
    $connexion = new DatabasePDO();
    $sql="SELECT COUNT(h.id) as cuenta FROM rel_lms_malla_persona h WHERE rut=:rut AND (SELECT id_programa FROM rel_lms_malla_curso WHERE id_malla=h.id_malla LIMIT 1) =:id_programa AND h.id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_programa', $id_programa);
    $connexion->bind(':id_empresa', $id_empresa);
	$cod = $connexion->resultset();
    return $cod[0]->cuenta;
}
    
function Lms_Busca_MasdeunaMalla_Array($rut, $id_programa, $id_empresa) {
    
    $connexion = new DatabasePDO();
    $sql="SELECT id_malla FROM rel_lms_malla_persona h WHERE rut=:rut AND (SELECT id_programa FROM rel_lms_malla_curso WHERE id_malla=h.id_malla LIMIT 1) =:id_programa AND h.id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_programa', $id_programa);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}
    
function RelInt_Busca_Relatores_data($id_empresa){
    
    $connexion = new DatabasePDO();
    $sql= "SELECT h.* FROM tbl_badges_ganados h WHERE h.id_empresa=:id_empresa AND h.badge='Relator Interno' AND (SELECT rut FROM tbl_usuario WHERE rut=h.rut)<>''
    ORDER BY (SELECT nombre_completo FROM tbl_usuario WHERE rut=h.rut)";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
    
}

function BuscaProgramaInd($rut, $id_empresa, $id_programa) {
    $connexion = new DatabasePDO();

    $sql = "
        select count(h.id) as cuenta
        from rel_lms_malla_persona h where h.id_empresa=:id_empresa and h.rut=:rut
        and (select id_programa from rel_lms_malla_curso where id_malla=h.id_malla limit 1)=:id_programa
    ";

    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_programa', $id_programa);
    $cod = $connexion->resultset();

    return $cod;
}


function BuscaCursosProgramaData($id_programa, $id_empresa){
    $connexion = new DatabasePDO();

    $sql="select h.*,
        (select nombre from tbl_lms_curso where id=h.id_curso) as nombre_curso,
        (select descripcion from tbl_lms_curso where id=h.id_curso) as descripcion_curso,
        (select imagen from tbl_lms_curso where id=h.id_curso) as imagen_curso
        from rel_lms_malla_curso h
        where h.id_programa=:id_programa and h.id_empresa=:id_empresa order by h.orden_curso";

    $connexion->query($sql);
    $connexion->bind(':id_programa', $id_programa);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();

    return $cod;
}


function BuscaRutdadoEmail($email, $id_empresa){
    $connexion = new DatabasePDO();

    $sql="
        select * from tbl_usuario where email=:email and id_empresa=:id_empresa limit 1
    ";

    $connexion->query($sql);
    $connexion->bind(':email', $email);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();

    return $cod;
}


function premios_buscaSaldo($rut, $id_empresa){
    $connexion = new DatabasePDO();

    $sql="
        select sum(puntos) as puntos, 'ingreso' from tbl_premios_puntos_usuarios
        where rut=:rut and id_empresa=:id_empresa
        UNION
        select (1)*sum(puntos) as puntos,  'egreso' from tbl_premios_canje
        where rut=:rut and id_empresa=:id_empresa
    ";

    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();

    return $cod;
}

function PremioBuscaNombre($id_premio, $id_empresa){
    $connexion = new DatabasePDO();

    $sql="SELECT premio FROM tbl_premios WHERE id_premio=:id_premio AND id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_premio', $id_premio);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();

    return $cod[0]->premio;
}

function premios_busca_cartola($rut, $id_empresa, $limit){
    $connexion = new DatabasePDO();

    if($limit<>''){ $qlimit=" limit $limit ";} else {$qlimit=""; }

    $sql="SELECT id, rut, fecha, puntos, tipo, descripcion, 'ingreso'
    FROM tbl_premios_puntos_usuarios
    WHERE rut=:rut AND id_empresa=:id_empresa
    UNION
    SELECT id, rut, fecha, puntos, id_premio, estadovalidacion, 'egreso'
    FROM tbl_premios_canje
    WHERE rut=:rut AND id_empresa=:id_empresa AND estadovalidacion<>'3'
    ORDER BY fecha DESC, id DESC $qlimit";

    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();

    return $cod;
}

function DatosEvento($id_evento, $id_empresa){
    $connexion = new DatabasePDO();

    $sql="SELECT h.* FROM tbl_eventos h WHERE h.id_empresa=:id_empresa AND h.codigo=:id_evento";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_evento', $id_evento);
    $cod = $connexion->resultset();

    return $cod;
}

function DatosPostulable($id_postulable, $id_empresa){
    $connexion = new DatabasePDO();
    $sql="select h.* from tbl_postulables h where h.id_empresa=:id_empresa and h.id_postulable=:id_postulable";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_postulable', $id_postulable);
    $cod = $connexion->resultset();
    return $cod;
}
    
function DatosPremioId($id_premio, $id_empresa){
    
    $connexion = new DatabasePDO();
    $sql="select h.* from tbl_premios h where h.id_empresa=:id_empresa and h.id_premio=:id_premio limit 1";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_premio', $id_premio);
    $cod = $connexion->resultset();
    return $cod;
}
    
function Inscripcion_Eventos_SAVE($rut,$id_empresa,$fecha,$hora,$estado,$codigo,$id_categoria,$id_dimension){
    
       $connexion = new DatabasePDO();
    
    $sql="select id from tbl_eventos_inscritos where id_empresa=:id_empresa and rut=:rut and codigo=:codigo";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':codigo', $codigo);
    $cod = $connexion->resultset();
    
    if($cod[0]->id<>''){
        echo "No puedes postular al mismo item dos veces";
        sleep(3);
    } else {
        $fecha = date("Y-m-d");
        $hora  = date("H:i:s");
        $fecha_estado=$fecha;
        $sql   = "INSERT INTO tbl_eventos_inscritos (rut,id_empresa,fecha,hora,estado,codigo,id_categoria,id_dimension)
                  values (:rut,:id_empresa,:fecha,:hora,:estado,:codigo,:id_categoria,:id_dimension)";
        $connexion->query($sql);
        $connexion->bind(':rut', $rut);
        $connexion->bind(':id_empresa', $id_empresa);
        $connexion->bind(':fecha', $fecha);
        $connexion->bind(':hora', $hora);
        $connexion->bind(':estado', $estado);
        $connexion->bind(':codigo', $codigo);
        $connexion->bind(':id_categoria', $id_categoria);
        $connexion->bind(':id_dimension', $id_dimension);
        $connexion->execute();
    }
}
function buscaDatosPremio($ide, $id_empresa){
    $connexion = new DatabasePDO();

    $sql = "SELECT id_premio FROM tbl_premios_canje WHERE id_empresa = :id_empresa AND id = :ide LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':ide', $ide);
    $cod = $connexion->resultset();

    return $cod[0]->id_premio;
}

function buscaDatosBeneItem($ide, $id_empresa){
    $connexion = new DatabasePDO();

    $sql = "SELECT nombre FROM tbl_beneficios WHERE id_empresa = :id_empresa AND id_beneficios = :ide LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':ide', $ide);
    $cod = $connexion->resultset();

    return $cod[0]->nombre;
}

function buscaDatosPostulacion($ide, $id_empresa){
    $connexion = new DatabasePDO();

    $sql = "SELECT id_postulable FROM tbl_postulables WHERE id_empresa = :id_empresa AND id = :ide";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':ide', $ide);
    $cod = $connexion->resultset();

    return $cod[0]->id_postulable;
}

function buscaDatosPostulacionRealizada($ide, $id_empresa){
    $connexion = new DatabasePDO();

    $sql = "SELECT id_postulable FROM tbl_postulaciones WHERE id_empresa = :id_empresa AND id = :ide";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':ide', $ide);
    $cod = $connexion->resultset();

    return $cod[0]->id_postulable;
}
function EliminaCanjePuntosCero($id, $id_empresa, $puntos) {

    $connexion = new DatabasePDO();

    $fecha = date("Y-m-d");
    $hora  = date("H:i:s");
    $fecha_estado=$fecha;

    $sql="update tbl_premios_canje set puntos='0' where id_empresa=:id_empresa and id=:id";

    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id', $id);
    $connexion->execute();

}

function Postulaciones_DesisteUsuarios($id,$id_empresa,$v){

    $connexion = new DatabasePDO();

    $fecha = date("Y-m-d");
    $hora  = date("H:i:s");
    $fecha_estado=$fecha;

    $sql="delete from tbl_postulaciones  where id_empresa=:id_empresa and id=:id";

    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id', $id);
    $connexion->execute();

}

function Postulaciones_UPDATEJEFE($id,$id_empresa,$v){

    $connexion = new DatabasePDO();

    $fecha = date("Y-m-d");
    $hora  = date("H:i:s");
    $fecha_estado=$fecha;

    $sql="update tbl_postulaciones set jefeok=:v,fechajefe=:fecha where id_empresa=:id_empresa and id=:id";

    $connexion->query($sql);
    $connexion->bind(':v', $v);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id', $id);
    $connexion->execute();

}

function Beneficios_UPDATEJEFE($id,$id_empresa,$v){

    $connexion = new DatabasePDO();

    $fecha = date("Y-m-d");
    $hora  = date("H:i:s");
    $fecha_estado=$fecha;

    $sql="update tbl_premios_canje set estadovalidacion=:v,fechavalidacion=:fecha where id_empresa=:id_empresa and id=:id";

    $connexion->query($sql);
    $connexion->bind(':v', $v);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id', $id);
    $connexion->execute();

}

function BuscarFiltroPositivoFC($rut, $id_empresa){
    $connexion = new DatabasePDO();
    
    $sql="
    select count(h.id) as cuenta
    from tbl_postulables_usuarios_filtro h
    where h.rut=:rut and h.id_empresa=:id_empresa
    ";
    
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
    $cod = $connexion->resultset();
    
    return $cod[0]->cuenta;
}
    
function BuscarFiltroPositivoFCFull($rut, $id_empresa){
    
    $connexion = new DatabasePDO();
    
    $sql="
    select h.*
    from tbl_postulables_usuarios_filtro h
    where h.rut=:rut and h.id_empresa=:id_empresa
    ";
    
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
    $cod = $connexion->resultset();
    
    return $cod;
}
    
function EMAIL_TodosTablaEmailRut(){
    
    $connexion = new DatabasePDO();
    
    $sql="select * from rut_email";
    
    $connexion->query($sql);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;
}
    
function VerificoCursoModalidad4EnOtraMalla($id_curso, $id_empresa){
    
    $connexion = new DatabasePDO();
    
    $sql="select * from rel_lms_malla_curso where id_curso=:id_curso and id_empresa=:id_empresa and id_malla<>'e_p_f'";
    
    $connexion->query($sql);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
    $cod = $connexion->resultset();
    
    return $cod;
}
    
function EMAIL_BancoChileREG_Contrato(){
    $connexion = new DatabasePDO();
    
    $sql="select * from rut_email where region<>'13' and tipo='CDCON00001'";
    
    $connexion->query($sql);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;
}
function EMAIL_BancoChileREG_PlazoFijo(){
    $connexion = new DatabasePDO();
    $sql="SELECT * FROM rut_email WHERE region <> '13' AND tipo = 'CDCON00002'";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}

function EMAIL_BancoChileRM(){
    $connexion = new DatabasePDO();
    $sql="SELECT * FROM rut_email WHERE region = '13'";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}

function RankingGerencialGlobal_data(){
    
}

function BancoChilePlazoFijo(){
    $connexion = new DatabasePDO();
    $sql="SELECT * FROM rut_email WHERE tipo = 'CDCON00002'";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}

function BancoChileIndefinidos(){
    $connexion = new DatabasePDO();
    $sql="SELECT * FROM rut_email WHERE tipo = 'CDCON00001'";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}

function RankingIndividualGlobal_data($id_programa, $id_empresa){
    $id_empresa = '62';
    $connexion = new DatabasePDO();
    $sql="
        SELECT h.*, (SELECT SUM(puntos) FROM tbl_lms_reportes WHERE rut = h.rut) AS puntostotal
        FROM tbl_lms_reportes h
        WHERE id_empresa = :id_empresa
        GROUP BY h.rut ORDER BY (SELECT SUM(puntos) FROM tbl_lms_reportes WHERE rut = h.rut) DESC LIMIT 10
    ";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function RankingIndividualGlobalTop1_data($id_programa, $id_empresa){
    $id_empresa='62';
    $connexion = new DatabasePDO();
    $sql="SELECT h.*, (SELECT SUM(puntos) FROM tbl_lms_reportes WHERE rut=h.rut) as puntostotal, (SELECT biografia FROM tbl_usuario_biografia WHERE rut=h.rut LIMIT 1) as biografia
        FROM tbl_lms_reportes h
        WHERE id_empresa=:id_empresa
        GROUP BY h.rut
        ORDER BY (SELECT SUM(puntos) FROM tbl_lms_reportes WHERE rut=h.rut) DESC
        LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function buscaNominaRutIdEvento($rut, $id_charla){
    $connexion = new DatabasePDO();
    $sql="SELECT COUNT(id) as cuenta FROM tbl_nomina_charla WHERE rut=:rut AND id_charla=:id_charla";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_charla', $id_charla);
    $cod = $connexion->resultset();
    return $cod[0]->cuenta;    
}

function MedBuscaIdEncuestraMed($idm, $id_empresa){
    $connexion = new DatabasePDO();
    $sql="SELECT * FROM tbl_enc_elearning_medicion WHERE id=:idm AND id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':idm', $idm);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function PostulableBuscaId($idm, $id_empresa){
    $connexion = new DatabasePDO();
    $sql="SELECT * FROM tbl_postulables WHERE id_postulable=:idm AND id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':idm', $idm);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function BuscaAvanceIdCursoActualizaReportes($rut, $id_curso, $id_empresa){
    $connexion = new DatabasePDO();
    
    $sql="select avance, nota from tbl_inscripcion_cierre where id_curso=:id_curso and rut=:rut and id_empresa=:id_empresa";
    
    
    $connexion->query($sql);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    
    
    if($cod[0]->avance>0){
    
            $sql_repo="select count(id) as cuenta from tbl_lms_reportes where id_curso=:id_curso and rut=:rut and id_empresa=:id_empresa";
            $connexion->query($sql_repo);
            $connexion->bind(':id_curso', $id_curso);
            $connexion->bind(':rut', $rut);
            $connexion->bind(':id_empresa', $id_empresa);
            $cod_repo = $connexion->resultset();
    
    
            if($cod_repo[0]->cuenta==0){
             $sql_repo_insupdate="
                INSERT INTO tbl_lms_reportes (rut, id_curso, id_empresa, avance, resultado)
            " . "VALUES (:rut, :id_curso, :id_empresa, '".$cod[0]->avance. "', '".$cod[0]->nota."') ";
                $connexion->query($sql_repo_insupdate);
                $connexion->bind(':id_curso', $id_curso);
                $connexion->bind(':rut', $rut);
                $connexion->bind(':id_empresa', $id_empresa);
                $connexion->execute();
    
            } else {
            $sql_repo_insupdate="
                UPDATE tbl_lms_reportes SET avance='".$cod[0]->avance."', resultado='".$cod[0]->nota."' where rut=:rut and id_curso=:id_curso and id_empresa=:id_empresa";
                $connexion->query($sql_repo_insupdate);
                $connexion->bind(':id_curso', $id_curso);
                $connexion->bind(':rut', $rut);
                $connexion->bind(':id_empresa', $id_empresa);
                $connexion->execute();
    
            }
    
    }
    
    return $cod;
}
    
function BuscaEstadisticasUsuario($rut_perfil, $id_empresa){
    
    $connexion = new DatabasePDO();
    
    $sql="
    select h.rut,
    
    (select fecha from tbl_log_sistema where rut=:rut_perfil and id_empresa=:id_empresa order by id ASC limit 1) as fecha_inicio,
    (select fecha from tbl_log_sistema where rut=:rut_perfil and id_empresa=:id_empresa order by id DESC limit 1) as fecha_ultima,
    
    (select COUNT(h.id) as aprobados from tbl_inscripcion_usuarios h where h.rut=:rut_perfil and h.id_empresa=:id_empresa
    and (select estado_descripcion from tbl_inscripcion_cierre where id_inscripcion= h.id_inscripcion and rut=h.rut)='APROBADO') as cursos_aprobados
    
    from tbl_usuario h where h.rut=:rut_perfil and h.id_empresa=:id_empresa;";
    
    $connexion->query($sql);
    $connexion->bind(':rut_perfil', $rut_perfil);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
    
}
function TieneRespuestaPorObjetiAlternativaPreguntaRut($rut, $id_objeto, $id_pregunta, $alternativa, $intento){
    $connexion = new DatabasePDO();

    $sql="SELECT * FROM tbl_evaluaciones_respuestas WHERE rut=:rut AND id_pregunta=:id_pregunta AND id_alternativa=:alternativa AND id_objeto=:id_objeto AND numero_intento=:intento";

    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_pregunta', $id_pregunta);
    $connexion->bind(':alternativa', $alternativa);
    $connexion->bind(':id_objeto', $id_objeto);
    $connexion->bind(':intento', $intento);

    $cod = $connexion->resultset();
    return $cod;
}

function BuscaPuntosEmblemas($rut, $id_empresa){
    $connexion = new DatabasePDO();

    $sql = "
        SELECT
            count(h.id) as emblemas,
            (select sum(puntos) from tbl_gamificado_puntos where rut=:rut and id_empresa=:id_empresa) as puntos,
            (select sum(medallas) from tbl_gamificado_puntos where rut=:rut and id_empresa=:id_empresa) as medallas
        FROM tbl_badges_ganados h
        WHERE h.rut=:rut and h.id_empresa=:id_empresa";

    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);

    $cod = $connexion->resultset();
    return $cod;
}


function busca_c1_c2_c3_c4_jefe($rut, $id_empresa)
{
    $c1="";$c2="";$c3="";$c4="";

    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_lms_reportes WHERE rut = :rut AND id_empresa = :id_empresa GROUP BY rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    $c1  = $cod[0]->c1;
    $c2  = $cod[0]->c2;
    $c3  = $cod[0]->c3;
    $c4  = $cod[0]->c4;
    
    $sql = "SELECT COUNT(id) AS cuenta FROM tbl_usuario WHERE (jefe = :rut OR lider = :rut OR responsable = :rut) AND id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    
    if ($cod[0]->cuenta > 0) {
        $jefe = $rut;
    } else {
        $sql = "SELECT jefe FROM tbl_usuario WHERE rut = :rut AND id_empresa = :id_empresa";
        $connexion->query($sql);
        $connexion->bind(':rut', $rut);
        $connexion->bind(':id_empresa', $id_empresa);
        $cod = $connexion->resultset();
        $jefe = $cod[0]->jefe;
    }
    
    $array[0]->c1   = $c1;
    $array[0]->c2   = $c2;
    $array[0]->c3   = $c3;
    $array[0]->c4   = $c4;
    $array[0]->jefe = $jefe;

    return $array;
}

function lms_avance_promedio_c1_c2_c3_c4_jefe($rut, $c1, $c2, $c3, $c4, $jefe, $programa, $curso, $id_empresa) {
    $connexion = new DatabasePDO();
    if ($programa <> '') {
        $qr_pr = " and h.id_programa='$programa' ";
    }
    if ($curso <> '') {
        $qr_cr = " and h.id_curso='$curso' ";
    }
    $sqlT = "select avg(h.avance) as avanceTOT from tbl_lms_reportes h where h.curso_opcional='0'
    and ((select opcional from tbl_lms_programas_bbdd where id_programa=h.id_programa) is null or (select opcional from tbl_lms_programas_bbdd where id_programa=h.id_programa)='')
    and h.id_empresa='$id_empresa' and (select modalidad from tbl_lms_curso where id=h.id_curso)='1' $qr_pr $qr_cr";
    $connexion->query($sqlT);
    $codT = $connexion->resultset();
    $sqlC1 = "select avg(h.avance) as avanceC1 from tbl_lms_reportes h where h.curso_opcional='0'
    and ((select opcional from tbl_lms_programas_bbdd where id_programa=h.id_programa) is null or (select opcional from tbl_lms_programas_bbdd where id_programa=h.id_programa)='')
    and h.c1='$c1' and h.id_empresa='$id_empresa' and (select modalidad from tbl_lms_curso where id=h.id_curso)='1' $qr_pr $qr_cr";
    $connexion->query($sqlC1);
    $codC1 = $connexion->resultset();
    $sqlC2 = "select avg(h.avance) as avanceC2 from tbl_lms_reportes h where h.curso_opcional='0'
    and ((select opcional from tbl_lms_programas_bbdd where id_programa=h.id_programa) is null or (select opcional from tbl_lms_programas_bbdd where id_programa=h.id_programa)='')
    and h.c2='$c2' and h.id_empresa='$id_empresa' and (select modalidad from tbl_lms_curso where id=h.id_curso)='1' $qr_pr $qr_cr";
    $connexion->query($sqlC2);
    $codC2 = $connexion->resultset();
    $sqlC3 = "select avg(h.avance) as avanceC3 from tbl_lms_reportes h where h.curso_opcional='0'
    and ((select opcional from tbl_lms_programas_bbdd where id_programa=h.id_programa) is null or (select opcional from tbl_lms_programas_bbdd where id_programa=h.id_programa)='')
    and h.c3='$c3' and h.id_empresa='$id_empresa' and (select modalidad from tbl_lms_curso where id=h.id_curso)='1' $qr_pr $qr_cr";
    $connexion->query($sqlC3);
    $codC3 = $connexion->resultset();
    $sqlC4 = "select avg(h.avance) as avanceC4 from tbl_lms_reportes h where h.curso_opcional='0'
    and ((select opcional from tbl_lms_programas_bbdd where id_programa=h.id_programa) is null or (select opcional from tbl_lms_programas_bbdd where id_programa=h.id_programa)='')
    and h.c4='$c4' and h.id_empresa='$id_empresa' and (select modalidad from tbl_lms_curso where id=h.id_curso)='1' $qr_pr $qr_cr";
    $connexion->query($sqlC4);
    $codC4 = $connexion->resultset();
    $sqlJF = "select avg(h.avance) as AVANCEjefe from tbl_lms_reportes h where h.curso_opcional='0' and (select jefe from tbl_usuario where rut=h.rut)='$jefe' and h.id_empresa='$id_empresa'
    and ((select opcional from tbl_lms_programas_bbdd where id_programa=h.id_programa) is null or (select opcional from tbl_lms_programas_bbdd where id_programa=h.id_programa)='')
    and (select modalidad from tbl_lms_curso where id=h.id_curso)='1' $qr_pr $qr_cr";
    $connexion->query($sqlJF);
    $codJF = $connexion->resultset();
    $sqlMF = "select avg(h.avance) as AVANCEmi from tbl_lms_reportes h where h.curso_opcional='0'
    and ((select opcional from tbl_lms_programas_bbdd where id_programa=h.id_programa) is null or (select opcional from tbl_lms_programas_bbdd where id_programa=h.id_programa)='')
    and h.rut='$jefe' and h.id_empresa='$id_empresa' and (select modalidad from tbl_lms_curso where id=h.id_curso)='1' $qr_pr $qr_cr";
    $connexion->query($sqlMF);
    $codMF = $connexion->resultset();
    $array[0] = round($codC1[0]->avanceC1);
    $array[1] = round($codC2[0]->avanceC2);
    $array[2] = round($codC3[0]->avanceC3);
    $array[3] = round($codC4[0]->avanceC4);
    $array[4] = round($codJF[0]->AVANCEjefe);
    $array[5] = round($codT[0]->avanceTOT);
    $array[6] = round($codMF[0]->AVANCEmi);
    //print_r($array);
    return $array;
}
function BuscaSucursalCui($rut, $id_empresa) {
    $connexion = new DatabasePDO();
    $sql = "SELECT local as cui, seccion as nombresucursal, nombre_completo as nombre_completo FROM tbl_usuario WHERE rut=:rut AND id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function SaveBadge($rut, $id_empresa, $id_orden) {
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*, 
        (SELECT COUNT(id) FROM tbl_badges_ganados WHERE rut=:rut AND id_empresa=h.id_empresa AND id_badge=h.id_badge) as cuentaganados 
        FROM tbl_badges h WHERE h.id_orden=:id_orden AND h.id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_orden', $id_orden);
    $cod = $connexion->resultset();
    if ($cod[0]->cuentaganados == 0) {
        $hoy   = date('Y-m-d');
        $fecha = date("Y-m-d");
        $hora  = date("H:i:s");
        $sql   = "INSERT INTO tbl_badges_ganados (rut, id_empresa, fecha, hora, id_badge, badge ) VALUES ( :rut, :id_empresa, :fecha, :hora, :id_badge, :badge )";
        $connexion->query($sql);
        $connexion->bind(':rut', $rut);
        $connexion->bind(':id_empresa', $id_empresa);
        $connexion->bind(':fecha', $fecha);
        $connexion->bind(':hora', $hora);
        $connexion->bind(':id_badge', $cod[0]->id_badge);
        $connexion->bind(':badge', $cod[0]->nombre);
        $connexion->execute();
        $BadgeGanado = 1;
    } else {
        $BadgeGanado = 0;
    }
    return $BadgeGanado;
}

function lista_badges_por_rut($rut, $id_empresa) {
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*, (SELECT fecha FROM tbl_badges_ganados WHERE id_badge=h.id_badge AND rut=:rut AND id_empresa=h.id_empresa) as fecha FROM tbl_badges h WHERE h.id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function lista_reconocimientos_badges_por_rut($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*,
            (SELECT nombre_completo FROM tbl_usuario WHERE rut=h.rut_destinatario) AS destinatario,
            (SELECT nombre_completo FROM tbl_usuario WHERE rut=h.rut_remitente) AS remitente
            FROM tbl_reconoce_gracias h
            WHERE h.id_empresa=:id_empresa AND h.rut_destinatario=:rut ORDER BY id DESC";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}

function lista_reconocimientos_viaId($id, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*
            FROM tbl_reconoce_gracias h
            WHERE h.id_empresa=:id_empresa AND h.id=:id";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id', $id);
    $cod = $connexion->resultset();
    return $cod;
}

function lista_PersonasEquipo($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*,
            (SELECT nombre_completo FROM tbl_usuario WHERE rut=h.rut) AS nombre_destinatario,
            (SELECT cargo FROM tbl_usuario WHERE rut=h.rut) AS cargo_destinatario,
            (SELECT avatar FROM tbl_usuario_biografia WHERE rut=h.rut) AS avatar_destinatario,
            (SELECT departamento FROM tbl_usuario WHERE rut=h.rut) AS departamento_destinatario,
            (SELECT COUNT(id) FROM tbl_reconoce_gracias WHERE rut_destinatario=h.rut AND id_empresa=:id_empresa AND tipo='RECONOCIMIENTO' AND estado='OK') AS num_reconocimientos_destinatario,
            (SELECT COUNT(id) FROM tbl_reconoce_gracias WHERE rut_destinatario=h.rut AND id_empresa=:id_empresa AND tipo='GRACIAS' AND estado='OK') AS num_gracias_destinatario,
            (SELECT COUNT(id) FROM tbl_badges_ganados WHERE rut=h.rut AND id_empresa=:id_empresa) AS numbadges
            FROM tbl_usuario h
            WHERE (jefe=:rut OR lider=:rut OR responsable=:rut) AND id_empresa=:id_empresa ORDER BY (SELECT nombre_completo FROM tbl_usuario WHERE rut=h.rut) ASC";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function lista_PersonasReconocidasEquipo($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "select h.*,
        (select nombre_completo from tbl_usuario where rut=h.rut) as nombre_destinatario,
        (select cargo from tbl_usuario where rut=h.rut) as cargo_destinatario,
        (select avatar from tbl_usuario_biografia where rut=h.rut) as avatar_destinatario,
        (select departamento from tbl_usuario where rut=h.rut) as departamento_destinatario,
        (select count(id) from tbl_reconoce_gracias where rut_destinatario=h.rut and id_empresa=:id_empresa and tipo='RECONOCIMIENTO' and estado='OK') AS num_reconocimientos_destinatario,
        (select count(id) from tbl_reconoce_gracias where rut_destinatario=h.rut and id_empresa=:id_empresa and tipo='GRACIAS' and estado='OK') AS num_gracias_destinatario,
        (select count(id) from tbl_badges_ganados where rut=h.rut and id_empresa=:id_empresa) as numbadges
    from tbl_usuario h
    where (jefe=:rut or lider=:rut or responsable=:rut) and id_empresa=:id_empresa
    order by (select nombre_completo from tbl_usuario where rut=h.rut) Asc";

    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}


function lista_badges_por_equipo($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "select h.*, (select fecha from tbl_badges_ganados where id_badge=h.id_badge and rut=:rut and id_empresa=h.id_empresa) as fecha
        from tbl_badges h
        where h.id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function TraigoInscripcionUsuarioPorCursoEmpresaUsuario($id_empresa, $rut, $id_curso)
{
    $connexion = new DatabasePDO();
    $sql = "select * from tbl_inscripcion_usuarios where rut=:rut and id_empresa=:id_empresa and id_curso=:id_curso";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_curso', $id_curso);
    $cod = $connexion->resultset();
    return $cod;
}

function TraigoInscripcionUsuarioPorCursoEmpresaUsuarioImparticiones($id_empresa, $rut, $id_curso, $id_inscripcion)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_inscripcion_usuarios WHERE rut=:rut AND id_empresa=:id_empresa AND id_curso=:id_curso AND id_inscripcion=:id_inscripcion";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':id_inscripcion', $id_inscripcion);
    $cod = $connexion->resultset();
    return $cod;
}

function BuscaUsuarioDadoidgr($idgr){

    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_reconoce_gracias WHERE id=:idgr";
    $connexion->query($sql);
    $connexion->bind(':idgr', $idgr);
    $cod = $connexion->resultset();
    return $cod;
}

function BuscaUsuarioDadoFullTexto($q, $id_empresa) {

    $connexion = new DatabasePDO();
    $q = utf8_decode($q);
    if($id_empresa=="78") {
        $queryFiltro=" AND tbl_usuario.division='Division Personas y Organizacion' ";
         $queryFiltro=" ";
    } else {
       $queryFiltro=" ";
    }
    $sql = "

    SELECT tbl_usuario.*,
       MATCH (nombre_completo) AGAINST (:q) AS relevance
    FROM tbl_usuario
    WHERE MATCH (nombre_completo) AGAINST (:q) AND MATCH (nombre_completo) AGAINST (:q) >0 AND tbl_usuario.nombre_empresa_holding<>''
    $queryFiltro
    ORDER BY relevance DESC limit 12;

    ";

    $connexion->query($sql);
    $connexion->bind(':q', $q);
    $cod = $connexion->resultset();
    return $cod;
}
function BuscaUsuarioDadoFullTextoContratoIndef($q, $id_empresa) {

    $connexion = new DatabasePDO();

    $q  = utf8_decode($q);
    if($id_empresa=="78") {
        $queryFiltro=" and tbl_usuario.division='Division Personas y Organizacion' ";
        $queryFiltro=" ";
    } else {
        $queryFiltro=" ";
    }

    $sql = "

    SELECT tbl_usuario.*,
       MATCH (nombre_completo) AGAINST (:q) AS relevance
    FROM tbl_usuario
    WHERE MATCH (nombre_completo) AGAINST (:q) and MATCH (nombre_completo) AGAINST (:q) >0
    and tbl_usuario.tipo_contrato ='CDCON00001'
    and tbl_usuario.empresa_holding='1000'
    $queryFiltro
    ORDER BY relevance DESC limit 60;

    ";

    $connexion->query($sql);
    $connexion->bind(':q', $q);
    $cod = $connexion->resultset();

    return $cod;

}

function BuscaUsuarioDadoFullTextoContratoIndefEmpresaHolding($q, $empresa_holding, $id_empresa) {

    $connexion = new DatabasePDO();

    $q  = utf8_decode($q);
    if($id_empresa=="78") {
        $queryFiltro=" and tbl_usuario.division='Division Personas y Organizacion' ";
        $queryFiltro=" ";
    } else {
        $queryFiltro=" ";
    }

    $sql = "

    SELECT tbl_usuario.*,
       MATCH (nombre_completo) AGAINST (:q) AS relevance
    FROM tbl_usuario
    WHERE MATCH (nombre_completo) AGAINST (:q) and MATCH (nombre_completo) AGAINST (:q) >0
    and tbl_usuario.tipo_contrato ='CDCON00001'
    and tbl_usuario.nombre_empresa_holding=:empresa_holding
    $queryFiltro
    ORDER BY relevance DESC limit 60;

    ";

    $connexion->query($sql);
    $connexion->bind(':q', $q);
    $connexion->bind(':empresa_holding', $empresa_holding);
    $cod = $connexion->resultset();

    return $cod;

}

function BuscaUsuarioDadoFullTextoNoEquipo($q, $id_empresa) {

    $connexion = new DatabasePDO();

    $q  = utf8_decode($q);
    $rut = $_SESSION["user_"];
    if($id_empresa=="78") {
        //$queryFiltro=" and tbl_usuario.division='Division Personas y Organizacion' and  ";
        $queryFiltro=" ";
    } else {
        $queryFiltro=" ";
    }

    $sql = "

    SELECT tbl_usuario.*,
       MATCH (nombre_completo) AGAINST (:q) AS relevance
    FROM tbl_usuario
    WHERE MATCH (nombre_completo) AGAINST (:q) and MATCH (nombre_completo) AGAINST (:q) >0

    $queryFiltro
    AND tbl_usuario.jefe  <> :rut
    and tbl_usuario.tipo_contrato = 'CDCON00001'
    and (tbl_usuario.empresa_holding='1000' or tbl_usuario.empresa_holding='1002')

    ORDER BY relevance DESC limit 12;

    ";

    $connexion->query($sql);
    $connexion->bind(':q', $q);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();

    return $cod;

}
function VideosPorCategoria($categoria)
{
    $connexion = new DatabasePDO();
    $sql = "select * from tbl_lms_video where categoria=:categoria";
    $connexion->query($sql);
    $connexion->bind(':categoria', $categoria);
    $cod = $connexion->resultset();
    return $cod;
}

function BuscaDatosReconocimientoAgradecimientos($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "
        select h.*,
        (select count(id) from tbl_reconoce_gracias where tipo='GRACIAS' and estado='OK' and id_empresa=:id_empresa and rut_destinatario=:rut) as num_gracias,
        (select count(id) from tbl_reconoce_gracias where (tipo='RECONOCIMIENTO' or tipo='COLABORACION')  and estado='OK' and id_empresa=:id_empresa and rut_destinatario=:rut) as num_reconocimientos
        from tbl_reconoce_gracias h where h.estado='OK' and id_empresa=:id_empresa and h.rut_destinatario=:rut order by h.fecha DESC
    ";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $datos = $connexion->resultset();
    return $datos;
}

function BuscaDatosReconocimientoAgradecimientosEquipo($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "
        select h.*,
        (select count(h.id) as cuenta from tbl_reconoce_gracias h where h.tipo='GRACIAS' and h.estado='OK' and h.id_empresa=:id_empresa and (select jefe from tbl_usuario where rut=h.rut_destinatario)=:rut) as num_gracias,
        (select count(h.id) as cuenta from tbl_reconoce_gracias h where (tipo='RECONOCIMIENTO' or tipo='COLABORACION')  and h.estado='OK' and h.id_empresa=:id_empresa and (select jefe from tbl_usuario where rut=h.rut_destinatario)=:rut) as num_reconocimientos
        from tbl_reconoce_gracias h
        where h.estado='OK' and id_empresa=:id_empresa and (select jefe from tbl_usuario where rut=h.rut_destinatario)=:rut
        group by (select jefe from tbl_usuario where rut=h.rut_destinatario)=:rut
    ";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $datos = $connexion->resultset();
    return $datos;
}

function BuscaUltimosIngresosBiografiasoloClick($id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "
    SELECT h.*,
    (SELECT nombre_completo FROM tbl_usuario WHERE rut=h.rut) AS nombre,
    (SELECT cargo FROM tbl_usuario WHERE rut=h.rut) AS cargo,
    (SELECT division FROM tbl_usuario WHERE rut=h.rut) AS division
    FROM tbl_usuario_biografia h
    WHERE id_empresa=:id_empresa AND (SELECT nombre_completo FROM tbl_usuario WHERE rut=h.rut) <> ''
    ORDER BY h.id DESC
    LIMIT 12";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $datos = $connexion->resultset();
    return $datos;
}

function CuentaReconocimientosGraciasJefe($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "
    SELECT COUNT(h.id) as cuenta
    FROM tbl_reconoce_gracias h
    WHERE h.tipo='RECONOCIMIENTO' AND h.estado='OK' AND id_empresa=:id_empresa AND h.rut_destinatario=:rut
    ORDER BY h.fecha DESC";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $datos = $connexion->resultset();
    return $datos;
}
function TotalReconocimientosGraciasVistaPerfil($rut, $id_empresa, $tipo)
{
    $connexion = new DatabasePDO();
    if ($tipo == "") {
        $query = " and (h.rut_remitente=:rut or h.rut_destinatario=:rut) ";
    }
    if ($tipo == "recibidos") {
        $query = " and h.rut_destinatario=:rut ";
    }
    if ($tipo == "enviados") {
        $query = " and h.rut_remitente=:rut ";
    }
    $sql = "

        select h.*,

        (select nombre_completo from tbl_usuario where rut=h.rut_destinatario) as nombre_destinatario,
        (select nombre_completo from tbl_usuario where rut=h.rut_remitente) as nombre_remitente,
        (select nombre_completo from tbl_usuario where rut=h.rut_jefatura) as nombre_jefatura,

        (select cargo from tbl_usuario where rut=h.rut_destinatario) as cargo_destinatario,
        (select cargo from tbl_usuario where rut=h.rut_remitente) as cargo_remitente,
        (select cargo from tbl_usuario where rut=h.rut_jefatura) as cargo_jefatura,

        (select avatar from tbl_usuario_biografia  where rut=h.rut_destinatario) as avatar_destinatario,
        (select avatar from tbl_usuario_biografia  where rut=h.rut_remitente) as avatar_remitente,

        (select departamento from tbl_usuario where rut=h.rut_destinatario) as departamento_destinatario,
        (select departamento from tbl_usuario where rut=h.rut_remitente) as departamento_remitente,
        (select departamento from tbl_usuario where rut=h.rut_jefatura) as departamento_jefatura,

        (select count(id) from tbl_reconoce_gracias where rut_destinatario=h.rut_destinatario and id_empresa=:id_empresa and tipo='RECONOCIMIENTO' and estado='OK') AS num_reconocimientos_destinatario,
        (select count(id) from tbl_reconoce_gracias where rut_destinatario=h.rut_destinatario and id_empresa=:id_empresa and tipo='GRACIAS' and estado='OK') AS num_gracias_destinatario,

        (select count(id) from tbl_reconoce_gracias where rut_destinatario=h.rut_remitente and id_empresa=:id_empresa and tipo='RECONOCIMIENTO' and estado='OK') AS num_reconocimientos_rut_remitente,
        (select count(id) from tbl_reconoce_gracias where rut_destinatario=h.rut_remitente and id_empresa=:id_empresa and tipo='GRACIAS' and estado='OK') AS num_gracias_rut_remitente

        from tbl_reconoce_gracias h

        where h.estado='OK' and id_empresa=:id_empresa

        $query

        order by h.fecha DESC

        ";
    //echo $sql;
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
    $datos = $connexion->resultset();
    return $datos;
}
function TotalReconocimientosGraciasVistaPerfilEquipo($rut, $id_empresa, $tipo)
{
    $connexion = new DatabasePDO();
    if ($tipo == "") {
        $query = "
            and ( (select jefe from tbl_usuario where rut=h.rut_destinatario)='$rut'  or (select jefe from tbl_usuario where rut=h.rut_remitenteo)='$rut')
        ";
    }
    if ($tipo == "recibidos") {
        $query = " and  (select jefe from tbl_usuario where rut=h.rut_destinatario)='$rut' ";
    }
    if ($tipo == "enviados") {
        $query = " and (select jefe from tbl_usuario where rut=h.rut_remitente)='$rut' ";
    }
    $sql = "
        select h.*,

        (select nombre_completo from tbl_usuario where rut=h.rut_destinatario) as nombre_destinatario,
        (select nombre_completo from tbl_usuario where rut=h.rut_remitente) as nombre_remitente,
        (select nombre_completo from tbl_usuario where rut=h.rut_jefatura) as nombre_jefatura,

        (select cargo from tbl_usuario where rut=h.rut_destinatario) as cargo_destinatario,
        (select cargo from tbl_usuario where rut=h.rut_remitente) as cargo_remitente,
        (select cargo from tbl_usuario where rut=h.rut_jefatura) as cargo_jefatura,

        (select avatar from tbl_usuario_biografia  where rut=h.rut_destinatario) as avatar_destinatario,
        (select avatar from tbl_usuario_biografia  where rut=h.rut_remitente) as avatar_remitente,

        (select departamento from tbl_usuario where rut=h.rut_destinatario) as departamento_destinatario,
        (select departamento from tbl_usuario where rut=h.rut_remitente) as departamento_remitente,
        (select departamento from tbl_usuario where rut=h.rut_jefatura) as departamento_jefatura,

        (select count(id) from tbl_reconoce_gracias where rut_destinatario=h.rut_destinatario and id_empresa=:id_empresa and tipo='RECONOCIMIENTO' and estado='OK') AS num_reconocimientos_destinatario,
        (select count(id) from tbl_reconoce_gracias where rut_destinatario=h.rut_destinatario and id_empresa=:id_empresa and tipo='GRACIAS' and estado='OK') AS num_gracias_destinatario,

        (select count(id) from tbl_reconoce_gracias where rut_destinatario=h.rut_remitente and id_empresa=:id_empresa and tipo='RECONOCIMIENTO' and estado='OK') AS num_reconocimientos_rut_remitente,
        (select count(id) from tbl_reconoce_gracias where rut_destinatario=h.rut_remitente and id_empresa=:id_empresa and tipo='GRACIAS' and estado='OK') AS num_gracias_rut_remitente

        from tbl_reconoce_gracias h

        where h.estado='OK' and id_empresa=:id_empresa

        $query

        order by h.fecha DESC

        ";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $datos = $connexion->resultset();
    return $datos;
}
function TotalReconocimientosGraciasJefe($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "
        SELECT h.*,
            u_destinatario.nombre_completo as nombre_destinatario,
            u_remitente.nombre_completo as nombre_remitente,
            u_jefatura.nombre_completo as nombre_jefatura,
            u_destinatario.cargo as cargo_destinatario,
            u_remitente.cargo as cargo_remitente,
            u_jefatura.cargo as cargo_jefatura,
            ub_destinatario.avatar as avatar_destinatario,
            ub_remitente.avatar as avatar_remitente,
            u_destinatario.departamento as departamento_destinatario,
            u_remitente.departamento as departamento_remitente,
            u_jefatura.departamento as departamento_jefatura,
            (SELECT COUNT(id) FROM tbl_reconoce_gracias WHERE rut_destinatario=h.rut_destinatario AND id_empresa=:id_empresa AND tipo='RECONOCIMIENTO' AND estado='OK') AS num_reconocimientos,
            (SELECT COUNT(id) FROM tbl_reconoce_gracias WHERE rut_destinatario=h.rut_destinatario AND id_empresa=:id_empresa AND tipo='GRACIAS' AND estado='OK') AS num_gracias
        FROM tbl_reconoce_gracias h
        LEFT JOIN tbl_usuario u_destinatario ON h.rut_destinatario = u_destinatario.rut
        LEFT JOIN tbl_usuario u_remitente ON h.rut_remitente = u_remitente.rut
        LEFT JOIN tbl_usuario u_jefatura ON h.rut_jefatura = u_jefatura.rut
        LEFT JOIN tbl_usuario_biografia ub_destinatario ON h.rut_destinatario = ub_destinatario.rut
        LEFT JOIN tbl_usuario_biografia ub_remitente ON h.rut_remitente = ub_remitente.rut
        WHERE h.tipo='RECONOCIMIENTO' AND h.estado='OK' AND id_empresa=:id_empresa AND (h.rut_remitente=:rut OR h.rut_destinatario=:rut)
        ORDER BY h.fecha DESC
    ";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $datos = $connexion->resultset();
    return $datos;
}
function TotalReconocimientosGraciasJeferut($idr, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "
        SELECT h.*,
            (SELECT nombre_completo FROM tbl_usuario WHERE rut=h.rut_destinatario) AS nombre_destinatario,
            (SELECT nombre_completo FROM tbl_usuario WHERE rut=h.rut_remitente) AS nombre_remitente,
            (SELECT nombre_completo FROM tbl_usuario WHERE rut=h.rut_jefatura) AS nombre_jefatura,
            (SELECT cargo FROM tbl_usuario WHERE rut=h.rut_destinatario) AS cargo_destinatario,
            (SELECT cargo FROM tbl_usuario WHERE rut=h.rut_remitente) AS cargo_remitente,
            (SELECT cargo FROM tbl_usuario WHERE rut=h.rut_jefatura) AS cargo_jefatura,
            (SELECT avatar FROM tbl_usuario_biografia WHERE rut=h.rut_destinatario) AS avatar_destinatario,
            (SELECT avatar FROM tbl_usuario_biografia WHERE rut=h.rut_remitente) AS avatar_remitente,
            (SELECT departamento FROM tbl_usuario WHERE rut=h.rut_destinatario) AS departamento_destinatario,
            (SELECT departamento FROM tbl_usuario WHERE rut=h.rut_remitente) AS departamento_remitente,
            (SELECT departamento FROM tbl_usuario WHERE rut=h.rut_jefatura) AS departamento_jefatura,
            (SELECT COUNT(id) FROM tbl_reconoce_gracias WHERE rut_destinatario=h.rut_destinatario AND id_empresa=:id_empresa AND tipo='RECONOCIMIENTO' AND estado='OK') AS num_reconocimientos,
            (SELECT COUNT(id) FROM tbl_reconoce_gracias WHERE rut_destinatario=h.rut_destinatario AND id_empresa=:id_empresa AND tipo='GRACIAS' AND estado='OK') AS num_gracias
        FROM tbl_reconoce_gracias h
        WHERE h.id=:idr AND h.estado='OK' AND id_empresa=:id_empresa
        ORDER BY h.fecha DESC
    ";
    $connexion->query($sql);
    $connexion->bind(':idr', $idr);
    $connexion->bind(':id_empresa', $id_empresa);
    $datos = $connexion->resultset();
    return $datos;
}
function DataChartAgradecimientos($id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*, 
        (SELECT COUNT(id) FROM tbl_reconoce_gracias WHERE id_empresa=:id_empresa AND tipo='GRACIAS') AS cuenta, 
        (SELECT COUNT(id) FROM tbl_reconoce_gracias WHERE id_empresa=:id_empresa AND tipo='GRACIAS' AND id_categoria=h.id_categoria) AS cuentaCat, 
        ROUND(100 * (SELECT COUNT(id) FROM tbl_reconoce_gracias WHERE id_empresa=:id_empresa AND tipo='GRACIAS' AND id_categoria=h.id_categoria) 
        / (SELECT COUNT(id) FROM tbl_reconoce_gracias WHERE id_empresa=:id_empresa AND tipo='GRACIAS')) AS PromCat 

        FROM tbl_reconoce_categorias h WHERE h.id_empresa=:id_empresa 

        ORDER BY 
        ROUND(100 * (SELECT COUNT(id) FROM tbl_reconoce_gracias WHERE id_empresa=:id_empresa AND tipo='GRACIAS' AND id_categoria=h.id_categoria) 
        / (SELECT COUNT(id) FROM tbl_reconoce_gracias WHERE id_empresa=:id_empresa AND tipo='GRACIAS')) DESC";

    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $datos = $connexion->resultset();
    return $datos;
}

function DataChartDonutAgradecimientos($id_empresa, $div)
{
    $connexion = new DatabasePDO();
    if($div==""){$qdiv="";}
    if($div<>""){$qdiv=" AND h.agrupacion=:div ";}

    $sql = "SELECT h.categorita, COUNT(h.id) AS Cuenta 
            FROM tbl_reconoce_gracias h WHERE h.id_empresa=:id_empresa AND h.tipo='GRACIAS' AND estado='OK' $qdiv 
            GROUP BY h.categorita ORDER BY COUNT(h.id) DESC";

    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    if($div<>""){$connexion->bind(':div', $div);}
    $datos = $connexion->resultset();
    return $datos;
}

function DataChartDonutReco($id_empresa, $div)
{
    $connexion = new DatabasePDO();
    if($div==""){$qdiv="";}
    if($div<>""){$qdiv=" AND h.agrupacion=:div ";}

    $sql = "SELECT h.categorita, COUNT(h.id) AS Cuenta 
            FROM tbl_reconoce_gracias h WHERE h.id_empresa=:id_empresa AND h.tipo='RECONOCIMIENTO' AND estado='OK' $qdiv 
            GROUP BY h.categorita ORDER BY COUNT(h.id) DESC";

    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    if($div<>""){$connexion->bind(':div', $div);}
    $datos = $connexion->resultset();
    return $datos;
}
function REC_busca_reconocidos($id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "
        select h.*,
        (select count(id) from  tbl_reconoce_gracias where rut_destinatario=h.rut_destinatario and id_empresa=:id_empresa and tipo='RECONOCIMIENTO' and estado='OK') AS num_reconocimientos,
        (select count(id) from  tbl_reconoce_gracias where rut_destinatario=h.rut_destinatario and id_empresa=:id_empresa and tipo='GRACIAS'  and estado='OK') AS num_gracias
        from tbl_reconoce_gracias h
        where h.tipo='RECONOCIMIENTO'  and h.estado='OK' and id_empresa=:id_empresa
        order by h.fecha DESC
        limit 20
        ";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $datos = $connexion->resultset();
    return $datos;
}

function REC_busca_reconocidos_ver($id, $id_empresa)
{
    $connexion = new DatabasePDO();
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
        (select departamento from tbl_usuario where rut=h.rut_jefatura) as departamento_jefatura,
        (select count(id) from  tbl_reconoce_gracias where rut_destinatario=h.rut_destinatario and id_empresa=:id_empresa and tipo='RECONOCIMIENTO' and estado='OK') AS num_reconocimientos,
        (select count(id) from  tbl_reconoce_gracias where rut_destinatario=h.rut_destinatario and id_empresa=:id_empresa and tipo='GRACIAS'  and estado='OK') AS num_gracias
        from tbl_reconoce_gracias h
        where h.tipo='RECONOCIMIENTO'  and h.estado='OK' and id_empresa=:id_empresa
        and id=:id
        order by h.fecha DESC
        ";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id', $id);
    $datos = $connexion->resultset();
    return $datos;
}
function REC_CheckagrupacionGracias($id_empresa) {
    $connexion = new DatabasePDO();

    $sql="select * from tbl_reconoce_gracias where tipo='GRACIAS' and agrupacion is null";

    $connexion->query($sql);
    $datos = $connexion->resultset();

    if(count($datos)>0){
        foreach ($datos as $unico){
            $array_destinatario=DatosUsuario_($unico->rut_destinatario, $id_empresa);
            $division= $array_destinatario[0]->division;
            Rec_ActualizaAgrupacionGracias($connexion, $unico->id, $division);
        }
    }
}

function Rec_ActualizaAgrupacionGracias($connexion, $id, $division){
    $sql = "
        update tbl_reconoce_gracias set agrupacion='$division' where id='$id'
    ";

    $connexion->query($sql);
}

function REC_menu_agrupacion_division($id_empresa, $tipo_cargo)
{
    $connexion = new DatabasePDO();

    $sql = "
        select h.division, h.nombre_division from tbl_division h where h.id_empresa=:id_empresa
    ";

    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $datos = $connexion->resultset();
    return $datos;
}
function REC_busca_gracias_division($id_empresa, $division, $limite)
{
    $connexion = new DatabasePDO();
    
    if($limite > 0){
        $limit = " LIMIT $limite";
    }
    if($limite == 0){
        $limit = " ";
    }
    if($division <> "") {
        $div = " AND h.agrupacion=:division";
    }
    if($division == "") {
        $div = "";
    }

    $sql = "
        SELECT h.*,
            (SELECT COUNT(id) FROM tbl_reconoce_gracias WHERE rut_destinatario=h.rut_destinatario AND id_empresa=:id_empresa AND tipo='RECONOCIMIENTO' AND estado='OK') AS num_reconocimientos,
            (SELECT COUNT(id) FROM tbl_reconoce_gracias WHERE rut_destinatario=h.rut_destinatario AND id_empresa=:id_empresa AND tipo='GRACIAS' AND estado='OK') AS num_gracias
        FROM tbl_reconoce_gracias h
        WHERE h.tipo='GRACIAS' AND h.estado='OK' AND id_empresa=:id_empresa $div
        ORDER BY h.fecha DESC, h.id DESC $limit";
    
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    if($division <> "") {
        $connexion->bind(':division', $division);
    }
    $datos = $connexion->resultset();
    return $datos;
}

function REC_busca_reco_division($id_empresa, $division, $limite)
{
    $connexion = new DatabasePDO();
    
    if($limite > 0){
        $limit = " LIMIT $limite";
    }
    if($limite == 0){
        $limit = " ";
    }
    if($division <> "") {
        $div = " AND h.agrupacion=:division";
    }
    if($division == "") {
        $div = "";
    }

    $sql = "
        SELECT h.*,
            (SELECT COUNT(id) FROM tbl_reconoce_gracias WHERE rut_destinatario=h.rut_destinatario AND id_empresa=:id_empresa AND tipo='RECONOCIMIENTO' AND estado='OK') AS num_reconocimientos,
            (SELECT COUNT(id) FROM tbl_reconoce_gracias WHERE rut_destinatario=h.rut_destinatario AND id_empresa=:id_empresa AND tipo='GRACIAS' AND estado='OK') AS num_gracias
        FROM tbl_reconoce_gracias h
        WHERE h.tipo='RECONOCIMIENTO' AND h.estado='OK' AND id_empresa=:id_empresa $div
        ORDER BY h.fecha DESC, h.id DESC $limit";

    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    if($division <> "") {
        $connexion->bind(':division', $division);
    }
    $datos = $connexion->resultset();
    return $datos;
}
function REC_busca_gracias_division_global($id_empresa, $division, $limite)
{
    $connexion = new DatabasePDO();

    if($limite>0){$limit=" limit $limite";}
    if($limite==0){$limit=" ";}
    if($division<>"")  {$div=" and h.agrupacion=:division";}
    if($division=="")  {$div="";}

    $sql = "

        select h.*

        from tbl_reconoce_gracias h

        where h.tipo='GRACIAS'  and h.estado='OK' and id_empresa=:id_empresa

        $div
        order by h.fecha DESC, h.id DESC

        $limit

        ";

    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':division', $division);
    $connexion->execute();
    $datos = $connexion->resultset();
    return $datos;
}

function REC_busca_reco_division_global($id_empresa, $division, $limite)
{
    $connexion = new DatabasePDO();

    if($limite>0){$limit=" limit $limite";}
    if($limite==0){$limit=" ";}
    if($division<>"")  {$div=" and h.agrupacion=:division";}
    if($division=="")  {$div="";}

    $sql = "

        select h.*

        from tbl_reconoce_gracias h

        where h.tipo='RECONOCIMIENTO'  and h.estado='OK' and id_empresa=:id_empresa

        $div
        order by h.fecha DESC, h.id DESC

        $limit

        ";

    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':division', $division);
    $connexion->execute();
    $datos = $connexion->resultset();
    return $datos;
}
function REC_busca_gracias($id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "
        SELECT h.*,
            (SELECT COUNT(id) FROM tbl_reconoce_gracias WHERE rut_destinatario=h.rut_destinatario AND id_empresa=:id_empresa AND tipo='RECONOCIMIENTO' AND estado='OK') AS num_reconocimientos,
            (SELECT COUNT(id) FROM tbl_reconoce_gracias WHERE rut_destinatario=h.rut_destinatario AND id_empresa=:id_empresa AND tipo='GRACIAS' AND estado='OK') AS num_gracias
        FROM tbl_reconoce_gracias h
        WHERE h.tipo='GRACIAS' AND h.estado='OK' AND id_empresa=:id_empresa
        ORDER BY h.fecha DESC, h.id DESC
    ";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $datos = $connexion->resultset();
    return $datos;
}

function REC_busca_reconocimientos_reco_cola($id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "
        SELECT h.*,
            (SELECT COUNT(id) FROM tbl_reconoce_gracias WHERE rut_destinatario=h.rut_destinatario AND id_empresa=:id_empresa AND tipo='RECONOCIMIENTO' AND estado='OK') AS num_reconocimientos,
            (SELECT COUNT(id) FROM tbl_reconoce_gracias WHERE rut_destinatario=h.rut_destinatario AND id_empresa=:id_empresa AND tipo='GRACIAS' AND estado='OK') AS num_gracias
        FROM tbl_reconoce_gracias h
        WHERE h.tipo='RECONOCIMIENTO' AND h.estado='OK' AND id_empresa=:id_empresa
        ORDER BY h.fecha DESC, h.id DESC
    ";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $datos = $connexion->resultset();
    return $datos;
}

function REC_busca_gracias_carrusel($id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "
        SELECT h.*,
            (SELECT COUNT(id) FROM tbl_reconoce_gracias WHERE rut_destinatario=h.rut_destinatario AND id_empresa=:id_empresa AND tipo='RECONOCIMIENTO' AND estado='OK') AS num_reconocimientos,
            (SELECT COUNT(id) FROM tbl_reconoce_gracias WHERE rut_destinatario=h.rut_destinatario AND id_empresa=:id_empresa AND tipo='GRACIAS' AND estado='OK') AS num_gracias
        FROM tbl_reconoce_gracias h
        WHERE h.tipo='GRACIAS' AND h.estado='OK' AND id_empresa=:id_empresa
        ORDER BY h.fecha DESC
        LIMIT 10
    ";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $datos = $connexion->resultset();
    return $datos;
}

function REC_busca_reco_cola_carrusel($id_empresa){
    $connexion = new DatabasePDO();
    $sql = "
    SELECT h.*,
    (SELECT COUNT(id) FROM tbl_reconoce_gracias WHERE rut_destinatario=h.rut_destinatario AND id_empresa=:id_empresa AND tipo='RECONOCIMIENTO' AND estado='OK') AS num_reconocimientos,
    (SELECT COUNT(id) FROM tbl_reconoce_gracias WHERE rut_destinatario=h.rut_destinatario AND id_empresa=:id_empresa AND tipo='GRACIAS' AND estado='OK') AS num_gracias
    FROM tbl_reconoce_gracias h
    WHERE h.tipo='RECONOCIMIENTO' AND h.estado='OK' AND id_empresa=:id_empresa
    ORDER BY h.fecha DESC
    LIMIT 10";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $datos = $connexion->resultset();
return $datos;
}

function Rec_cambia_estado($rut, $val, $idrg, $id_empresa){
    $connexion = new DatabasePDO();
    $sql = "SELECT COUNT(id) AS cuenta FROM tbl_reconoce_gracias WHERE rut_jefatura=:rut AND id_empresa=:id_empresa AND id=:idrg";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':idrg', $idrg);
    $datos = $connexion->resultset();
    if ($datos[0]->cuenta >= 1) {
    $hoy = date("Y-m-d");
    if ($val == 1) {
    $sql = "UPDATE tbl_reconoce_gracias SET estado='OK', fecha_validacion=:fecha_validacion WHERE id=:idrg";
    } elseif ($val == 2) {
    $sql = "UPDATE tbl_reconoce_gracias SET estado='RECHAZADO', fecha_validacion=:fecha_validacion WHERE id=:idrg";
    }
    $connexion->query($sql);
    $connexion->bind(':fecha_validacion', $hoy);
    $connexion->bind(':idrg', $idrg);
    $connexion->execute();
    }
    return $datos;
}
function Rec_cambia_estado_Reco_2019($rut, $val, $idrg, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT COUNT(id) as cuenta FROM tbl_reconoce_gracias h WHERE h.rut_jefatura = :rut AND h.id_empresa = :id_empresa AND h.id = :idrg";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':idrg', $idrg);
    $datos = $connexion->resultset();

    if ($datos[0]->cuenta >= 1) {
        $hoy = date("Y-m-d");
        if ($val == 1) {
            $sql = "UPDATE tbl_reconoce_gracias SET estado = 'OK', fecha_validacion = :hoy WHERE id = :idrg";
        } elseif ($val == 2) {
            $sql = "UPDATE tbl_reconoce_gracias SET estado = 'RECHAZADO', fecha_validacion = :hoy WHERE id = :idrg";
        }
        $connexion->query($sql);
        $connexion->bind(':hoy', $hoy);
        $connexion->bind(':idrg', $idrg);
        $connexion->execute();
    }

    return $datos;
}

function Rec_cambia_estado_Reco($rut, $val, $idrg, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "select count(id) as cuenta from tbl_reconoce_gracias h where h.rut_jefatura=:rut and h.id_empresa=:id_empresa and h.id=:idrg";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':idrg', $idrg);
    $datos = $connexion->resultset();

    if ($datos[0]->cuenta >= 1) {
        $hoy = date("Y-m-d");
        if ($val == 1) {
            $sql = "update tbl_reconoce_gracias set estado='APROBADO', fecha_validacion=:hoy where id=:idrg";
        } elseif ($val == 2) {
            $sql = "update tbl_reconoce_gracias set estado='RECHAZADO', fecha_validacion=:hoy where id=:idrg";
        }
        $connexion->query($sql);
        $connexion->bind(':hoy', $hoy);
        $connexion->bind(':idrg', $idrg);
        $connexion->execute();
    }

    return $datos;
}

function Rec_busca_reconocimientos($rut, $tipo, $avalidar, $id_empresa)
{
    $connexion = new DatabasePDO();
    if ($avalidar == "POR_VALIDAR") {
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
        where h.tipo='RECONOCIMIENTO' AND $query and h.estado=:tipo and id_empresa=:id_empresa
        order by fecha DESC, id DESC
    ";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':tipo', $tipo);
    $connexion->bind(':id_empresa', $id_empresa);
    $datos = $connexion->resultset();
    return $datos;
}
function BuscaValidacionRecoAprobadaJefe($rut, $colsend, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "
        SELECT h.*
        FROM tbl_reconoce_gracias h
        WHERE h.tipo='RECONOCIMIENTO'
        AND h.rut_remitente=:rut
        AND h.rut_destinatario=:colsend
        AND h.estado='APROBADO' AND id_empresa=:id_empresa
        LIMIT 1
    ";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':colsend', $colsend);
    $connexion->bind(':id_empresa', $id_empresa);
    $datos = $connexion->resultset();
    return $datos;
}

function BuscaValidacionRecoColAprobadaJefe($rut, $colsend, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "
        SELECT h.*
        FROM tbl_reconoce_gracias h
        WHERE h.tipo='COLABORACION'
        AND h.rut_remitente=:rut
        AND h.rut_destinatario=:colsend
        AND h.estado='APROBADO' AND id_empresa=:id_empresa
        AND h.fecha>='2019-11-15'
        LIMIT 1
    ";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':colsend', $colsend);
    $connexion->bind(':id_empresa', $id_empresa);
    $datos = $connexion->resultset();
    return $datos;
}function Rec_busca_reconocimientos_landing($rut, $tipo, $avalidar, $id_empresa)
{
    $connexion = new DatabasePDO();
    if ($avalidar == "POR_VALIDAR") {
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

    where (h.tipo='RECONOCIMIENTO'or h.tipo='COLABORACION') AND $query and h.estado=:tipo and id_empresa=:id_empresa

    order by fecha DESC, id DESC

    ";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':tipo', $tipo);
    $connexion->bind(':id_empresa', $id_empresa);
    $datos = $connexion->resultset();
    return $datos;
}

function BuscaJefeDadoRut($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "select h.jefe from tbl_usuario h where h.rut=:rut and h.id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $datos = $connexion->resultset();
    return $datos;
}
function Rec_Busca_reconocimientos_Disponibles($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "
        SELECT 
            SUM(h.rec_disponibles) AS otorgados,
            (SELECT COUNT(id) FROM tbl_reconoce_gracias WHERE tipo='RECONOCIMIENTO' AND rut_remitente=:rut AND estado='OK') AS cuenta_gastados,
            (SELECT COUNT(id) FROM tbl_reconoce_gracias WHERE tipo='RECONOCIMIENTO' AND rut_remitente=:rut AND estado='POR_VALIDAR') AS cuenta_pendientes,
            (SELECT COUNT(id) FROM tbl_reconoce_gracias WHERE tipo='RECONOCIMIENTO' AND rut_jefatura=:rut AND estado='POR_VALIDAR') AS porvalidar,
            (SUM(h.rec_disponibles)-(SELECT COUNT(id) FROM tbl_reconoce_gracias WHERE tipo='RECONOCIMIENTO' AND rut_remitente=:rut AND estado<>'RECHAZADO')) AS saldo
        FROM 
            tbl_reconoce_rec_disponibles h 
        WHERE 
            h.rut=:rut 
            AND h.id_empresa=:id_empresa
    ";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $datos = $connexion->resultset();
    return $datos;
}

function Rec_Busca_Puntos_Disponibles($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "
        SELECT 
            SUM(h.puntos) AS Puntos_Recibidos,
            (SELECT SUM(puntos) FROM tbl_premios_puntos_usuarios_jefe_entregados WHERE rut=h.rut AND id_empresa=h.id_empresa) AS Puntos_Repartidos
        FROM 
            tbl_premios_puntos_usuarios_jefe h 
        WHERE 
            h.rut=:rut 
            AND h.id_empresa=:id_empresa
    ";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $datos = $connexion->resultset();
    return $datos;
}

function Rec_Busca_Puntos_Col_Disponibles($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "
        SELECT 
            SUM(h.puntos) AS Puntos_Recibidos,
            (SELECT SUM(puntos) FROM tbl_premios_puntos_usuarios_jefe_entregados_col WHERE rut=h.rut AND id_empresa=h.id_empresa) AS Puntos_Repartidos
        FROM 
            tbl_premios_puntos_usuarios_jefe_col h 
        WHERE 
            h.rut=:rut 
            AND h.id_empresa=:id_empresa
    ";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $datos = $connexion->resultset();
    return $datos;
}
function Rec_Busca_Puntos_Cui_Disponibles($cui, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "
        SELECT
            sum(h.puntos) AS Puntos_Recibidos,
            (SELECT sum(puntos) FROM tbl_premios_puntos_usuarios_jefe_entregados_cui WHERE cui=h.cui AND id_empresa=h.id_empresa) AS Puntos_Repartidos
        FROM
            tbl_premios_puntos_usuarios_cui_jefes h
        WHERE
             h.cui = :cui
            AND h.id_empresa = :id_empresa
    ";
    $connexion->query($sql);
    $connexion->bind(':cui', $cui);
    $connexion->bind(':id_empresa', $id_empresa);
    $datos = $connexion->resultset();
    return $datos;
}

function buscar_email_clave($email)
{
    $connexion = new DatabasePDO();
    $sql = "
        SELECT
            h.rut,
            h.nombre_completo,
            h.email,
            h.id_empresa,
            (SELECT clave_encodeada FROM tbl_clave WHERE rut=h.rut LIMIT 1) AS clave
        FROM
            tbl_usuario h
        WHERE
            h.email = :email
        LIMIT 1
    ";
    $connexion->query($sql);
    $connexion->bind(':email', $email);
    $datos = $connexion->resultset();
    return $datos;
}

function UsuarioPorDepartamento($id_empresa, $codigo_departamento)
{
    $connexion = new DatabasePDO();
    $sql = "
        SELECT
            rut,
            nombre_completo
        FROM
            tbl_usuario
        WHERE
            departamento = :codigo_departamento
            AND id_empresa = :id_empresa
        ORDER BY
            nombre_completo ASC
    ";
    $connexion->query($sql);
    $connexion->bind(':codigo_departamento', $codigo_departamento);
    $connexion->bind(':id_empresa', $id_empresa);
    $datos = $connexion->resultset();
    return $datos;
}

function allCuiData($buscar, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "
        SELECT
            DISTINCT(area)
        FROM
            tbl_usuario
        WHERE
            id_empresa = :id_empresa
            AND id_gerencia = :buscar
            AND vigencia = '0'
        ORDER BY
            area
    ";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':buscar', $buscar);
    $datos = $connexion->resultset();
    return $datos;
}
function allCuiOficData($buscar, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT sucursal, id_gerencia FROM tbl_usuario
    WHERE id_empresa=:id_empresa
    and id_gerencia=:buscar
    and vigencia='0' group by id_gerencia limit 1";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':buscar', $buscar);
    $datos = $connexion->resultset();
    return $datos;
}

function VerificoPerfilamentoDeBibliotecaUsuario($id_categoria, $id_empresa, $rut)
{
    $connexion = new DatabasePDO();
    $sql = "select * from tbl_biblio_perfilamiento where id_categoria=:id_categoria and id_empresa=:id_empresa and rut=:rut";
    $connexion->query($sql);
    $connexion->bind(':id_categoria', $id_categoria);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}

function VerificoPerfilamentoDeBiblioteca($id_categoria, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "select * from tbl_biblio_perfilamiento where id_categoria=:id_categoria and id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_categoria', $id_categoria);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function BuscaBioCarrouselUsuario($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "select rut_destinatario as rut, mensaje as texto from tbl_reconoce_gracias  where rut_destinatario=:rut
    UNION
    select rut as rut, biografia as texto from tbl_usuario_biografia where rut=:rut
    UNION
    select rut as rut, sueno as texto from tbl_usuario_biografia where rut=:rut
    UNION
    select rut as rut, logros as texto from tbl_usuario_biografia where rut=:rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}
function ValidaJefeDirecto($rut_destinatario, $rut_remitente, $id_empresa){
    $connexion = new DatabasePDO();

    $sql = "SELECT jefe FROM tbl_usuario WHERE rut = :rut_destinatario AND id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut_destinatario', $rut_destinatario);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();

    if ($cod[0]->jefe == $rut_remitente) {
        return 1;
    } else {
        return 0;
    }
}

function Rec_Inserta_Reconocimiento($rut_remitente, $rut_destinatario, $id_categoria, $categoria, $fecha, $hora, $id_empresa, $tipo, $puntos, $mensaje, $cui, $estado, $cbox1){
    $connexion = new DatabasePDO();

    $mensaje = utf8_decode($mensaje);
    $categoria = utf8_decode($categoria);

    if ($tipo == "GRACIAS") {
        $puntos = "0";
    }

    $Usua = DatosUsuario_($rut_destinatario, $id_empresa);
    $rut_jefatura = $Usua[0]->jefe;
    $division = $Usua[0]->division;
    $cargo = $Usua[0]->cargo;
    $area = $Usua[0]->area;
    $local = $Usua[0]->local;
    $departamento = $Usua[0]->departamento;
    $region = $Usua[0]->regional;

    $sql = "INSERT INTO tbl_reconoce_gracias (rut_remitente,rut_destinatario,id_categoria,categorita,fecha,hora,id_empresa,tipo,puntos,mensaje,estado,cui,rut_jefatura,division, cargo, area, local,departamento, region, comentarios )
    VALUES (:rut_remitente, :rut_destinatario, :id_categoria, :categoria, :fecha, :hora, :id_empresa, :tipo, :puntos, :mensaje, :estado, :cui, :rut_jefatura, :division, :cargo, :area, :local, :departamento, :region, :cbox1)";

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
    $connexion->bind(':cbox1', $cbox1);

    $connexion->execute();

    $sql = "SELECT id FROM tbl_reconoce_gracias WHERE rut_remitente = :rut_remitente AND rut_destinatario = :rut_destinatario AND id_empresa = :id_empresa ORDER BY id DESC LIMIT 1";

    $connexion->query($sql);
    $connexion->bind(':rut_remitente', $rut_remitente);
    $connexion->bind(':rut_destinatario', $rut_destinatario);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod[0]->id;
}

function Rec_Inserta_Reconocimiento_pendiente($rut_remitente, $rut_destinatario, $id_categoria, $categoria, $fecha, $hora, $id_empresa, $tipo, $puntos, $mensaje, $rut_jefatura, $estado)
{
    $connexion = new DatabasePDO();

    $mensaje = utf8_decode($mensaje);
    $categoria = utf8_decode($categoria);

    $Usua = DatosUsuario_($rut_destinatario, $id_empresa);

    $division = $Usua[0]->division;
    $cargo = $Usua[0]->cargo;
    $area = $Usua[0]->area;
    $local = $Usua[0]->local;
    $departamento = $Usua[0]->departamento;
    $region = $Usua[0]->regional;

    $sql = "Select id from tbl_reconoce_gracias where rut_remitente=:rut_remitente and rut_destinatario=:rut_destinatario and tipo='RECONOCIMIENTO' and id_empresa=:id_empresa order by id DESC limit 1";
    $connexion->query($sql);
    $connexion->bind(':rut_remitente', $rut_remitente);
    $connexion->bind(':rut_destinatario', $rut_destinatario);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod2 = $connexion->resultset();

    if ($cod2[0]->id > 0) {
        $sql = "update tbl_reconoce_gracias set estado='PENDIENTE' where  rut_remitente=:rut_remitente and rut_destinatario=:rut_destinatario and tipo='RECONOCIMIENTO' and id_empresa=:id_empresa and estado<>'OK'";
    } else {
        $sql = "INSERT INTO tbl_reconoce_gracias (rut_remitente, rut_destinatario, id_categoria, categorita, fecha, hora, id_empresa, tipo, puntos, mensaje, estado, rut_jefatura, division, cargo, area, local, departamento, region)
        VALUES (:rut_remitente, :rut_destinatario, :id_categoria, :categoria, :fecha, :hora, :id_empresa, :tipo, 'PENDIENTE', :mensaje, :estado, :rut_jefatura, :division, :cargo, :area, :local, :departamento, :region)";
    }

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
    $connexion->bind(':rut_jefatura', $rut_jefatura);
    $connexion->bind(':division', $division);
    $connexion->bind(':cargo', $cargo);
    $connexion->bind(':area', $area);
    $connexion->bind(':local', $local);
    $connexion->bind(':departamento', $departamento);
    $connexion->bind(':region', $region);
    $connexion->execute();

}    
function Rec_Inserta_Reconocimiento_pendiente_col($rut_remitente, $rut_destinatario, $id_categoria, $categoria, $fecha, $hora, $id_empresa, $tipo, $puntos, $mensaje, $rut_jefatura, $estado)
{
    $connexion = new DatabasePDO();
    $mensaje = utf8_decode($mensaje);
    $categoria = utf8_decode($categoria);
    $sql = "Select id from tbl_reconoce_gracias where rut_remitente=:rut_remitente and rut_destinatario=:rut_destinatario and tipo='COLABORACION'  and id_empresa=:id_empresa and fecha>='2019-11-15' order by id DESC limit 1";
    $connexion->query($sql);
    $connexion->bind(':rut_remitente', $rut_remitente);
    $connexion->bind(':rut_destinatario', $rut_destinatario);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod2 = $connexion->resultset();

    $Usua = DatosUsuario_($rut_destinatario, $id_empresa);

    $division = $Usua[0]->division;
    $cargo = $Usua[0]->cargo;
    $area = $Usua[0]->area;
    $local = $Usua[0]->local;
    $departamento = $Usua[0]->departamento;
    $region = $Usua[0]->regional;

    if ($cod2[0]->id > 0) {
        $sql = "update tbl_reconoce_gracias set estado='PENDIENTE' where rut_remitente=:rut_remitente and rut_destinatario=:rut_destinatario and tipo='COLABORACION'  and id_empresa=:id_empresa and estado<>'OK'";
    } else {
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

}

function Rec_borra_jefe_entregado($idrg, $id_empresa) {
    $connexion = new DatabasePDO();

    $sql = "DELETE FROM tbl_premios_puntos_usuarios_jefe_entregados 
            WHERE id_reconoce_gracias = :idrg AND id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':idrg', $idrg);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
}

function Rec_Inserta_Puntos($cui, $rut_remitente, $rut_destinatario, $fecha, $puntos, $tipo, $descripcion,  $id_reco, $id_empresa )
{
    $connexion = new DatabasePDO();
    $descripcion = utf8_decode($descripcion);
    $tipo = utf8_decode($tipo);
    $sql = "INSERT INTO tbl_premios_puntos_usuarios_jefe_entregados_cui (cui, rut_jefe, rut_col, fecha, puntos, tipo, descripcion, id_empresa,id_reconoce_gracias) VALUES ('$cui', '$rut_remitente', '$rut_destinatario', '$fecha','$puntos', '$tipo', '$descripcion','$id_empresa','$id_reco')";
    
    //$connexion->query();
}

function Rec_Inserta_PuntoscOL2019($rut, $rut_destinatario, $fecha, $puntos, $tipo, $descripcion,  $id_reco, $id_empresa )
{
    $connexion = new DatabasePDO();
    $descripcion = utf8_decode($descripcion);
    $tipo = utf8_decode($tipo);
    $sql = "INSERT INTO tbl_premios_puntos_usuarios_jefe_entregados_col (rut, rut_col, fecha, puntos, tipo, descripcion, id_empresa,id_reconoce_gracias) VALUES ('$rut', '$rut_destinatario', '$fecha','$puntos', '$tipo', '$descripcion','$id_empresa','$id_reco')";
    
    //$connexion->query();
}

function Rec_Inserta_PuntosCol($rut_remitente, $rut_destinatario, $fecha, $puntos, $tipo, $descripcion,  $id_reco, $id_empresa )
{
    $connexion = new DatabasePDO();
    $descripcion = utf8_decode($descripcion);
    $tipo = utf8_decode($tipo);
    $sql = "INSERT INTO tbl_premios_puntos_usuarios_jefe_entregados_col (rut, rut_col, fecha, puntos, tipo, descripcion, id_empresa,id_reconoce_gracias) VALUES ('$rut_remitente', '$rut_destinatario', '$fecha','$puntos', '$tipo', '$descripcion','$id_empresa','$id_reco')";
    
    //$connexion->query();
}

function Rec_Inserta_Puntos_bancopuntos($rut_remitente, $rut_destinatario, $fecha, $puntos, $tipo, $descripcion,$id_reco,  $id_empresa)
{
    $connexion = new DatabasePDO();
    $descripcion = utf8_decode($descripcion);
    $tipo = utf8_decode($tipo);

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
}

function Rec_Inserta_Puntos_bancopuntos_2019($rut_remitente, $rut_destinatario, $fecha, $puntos, $tipo, $descripcion,$id_reco,  $id_empresa, $cui)
{
    $connexion = new DatabasePDO();
    $descripcion = utf8_decode($descripcion);
    $tipo = utf8_decode($tipo);
    $descripcion="SELECCION DEL CHILE 2019";

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

    $sql = "INSERT INTO tbl_premios_puntos_usuarios_jefe_entregados_cui (cui, fecha, puntos, tipo, descripcion, id_empresa, rut_col, id_reconoce_gracias, rut_jefe)
            VALUES (:cui, :fecha, :puntos, :tipo, :descripcion, :id_empresa, :rut_destinatario, '', :rut_remitente)";

    $connexion->query($sql);
    $connexion->bind(':cui', $cui);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':puntos', $puntos);
    $connexion->bind(':tipo', $tipo);
    $connexion->bind(':descripcion', $descripcion);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut_destinatario', $rut_destinatario);
    $connexion->bind(':rut_remitente', $rut_remitente);

    $connexion->execute();
}
function TraePalabraSqlInjection()
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_sql_injection";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}

function buscaPalabraSqlInjection($palabra)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_sql_injection WHERE palabra = :palabra OR palabra LIKE :palabra_like";
    $connexion->query($sql);
    $connexion->bind(':palabra', $palabra);
    $connexion->bind(':palabra_like', '%' . $palabra . '%');
    $cod = $connexion->resultset();
    return $cod;
}

function SaveLogEmails($id_empresa, $tipo, $subject, $to, $nombreto, $fecha, $statusCode, $headers, $response, $tipomensaje, $rut, $key)
{
    $connexion = new DatabasePDO();
    /*$ultimo_id = ObtengoUltimoRegistroMP($id_empresa);
    $nuevo_id  = $ultimo_id[0]->id;
    $nuevo_id  = $nuevo_id + 1;
    $codigo    = $id_empresa . "_mp" . $nuevo_id; */
    $sql       = "INSERT INTO tbl_log_emails (
    tipo, asunto,
    rut,  id_empresa,
    fecha, statusCode,
    headers, body,dato, email) " . "VALUES  (
    :tipomensaje, :subject,
    :rut,:id_empresa,
    :fecha,'',
    '','',
    :key, '')";

    $connexion->query($sql);
    $connexion->bind(':tipomensaje', $tipomensaje);
    $connexion->bind(':subject', $subject);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':key', $key);
    $connexion->execute();
}

function FiltroCuiAgenda($id_empresa) {
    $connexion = new DatabasePDO();
    $sql="SELECT * FROM tbl_eventos WHERE id_empresa = :id_empresa AND nombre <> ''  GROUP BY nombre ORDER BY nombre ASC";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}
function FiltroRutAgenda($id_empresa) {
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_eventos WHERE id_empresa = :id_empresa AND descripcion <> '' GROUP BY descripcion ORDER BY descripcion ASC";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function DatosUsuario_($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.* FROM tbl_usuario h WHERE rut = :rut AND id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function DatosUsuario_historicos_($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.* FROM tbl_usuario_historicos h WHERE rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}
function TraeDatosBiografiayUsuario($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*,
    (SELECT biografia FROM tbl_usuario_biografia WHERE rut=h.rut AND id_empresa=h.id_empresa) AS biografia,
    (SELECT sueno FROM tbl_usuario_biografia WHERE rut=h.rut AND id_empresa=h.id_empresa) AS sueno,
    (SELECT logros FROM tbl_usuario_biografia WHERE rut=h.rut AND id_empresa=h.id_empresa) AS logros,
    (SELECT celular FROM tbl_usuario_biografia WHERE rut=h.rut AND id_empresa=h.id_empresa) AS micelular,
    (SELECT avatar FROM tbl_usuario_biografia WHERE rut=h.rut AND id_empresa=h.id_empresa) AS miavatar,
    (SELECT estudios FROM tbl_usuario_biografia WHERE rut=h.rut AND id_empresa=h.id_empresa) AS estudios,
    (SELECT profesion FROM tbl_usuario_biografia WHERE rut=h.rut AND id_empresa=h.id_empresa) AS profesion,
    (SELECT fecha_cumpleanos FROM tbl_usuario_biografia WHERE rut=h.rut AND id_empresa=h.id_empresa) AS fecha_cumpleanos,
    (SELECT fecha_ingreso FROM tbl_usuario_biografia WHERE rut=h.rut AND id_empresa=h.id_empresa) AS fecha_ingreso,
    (SELECT recuerdos FROM tbl_usuario_biografia WHERE rut=h.rut AND id_empresa=h.id_empresa) AS recuerdos,
    (SELECT titulo FROM tbl_usuario_biografia WHERE rut=h.rut AND id_empresa=h.id_empresa) AS titulo,
    (SELECT capacitacion FROM tbl_usuario_biografia WHERE rut=h.rut AND id_empresa=h.id_empresa) AS capacitacion
    FROM tbl_usuario h
    WHERE rut=:rut AND id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $datos = $connexion->resultset();
    return $datos;
}

function Profile_check_data_biografia($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT id FROM tbl_usuario_biografia WHERE rut = :rut AND id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}
function BuscaestadoCapsula($rut, $id_objeto)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT id FROM `tbl_objetos_finalizados` WHERE `id_objeto` = :id_objeto and rut=:rut";
    $connexion->query($sql);
    $connexion->bind(':id_objeto', $id_objeto);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}

function calculaavancev2($id_curso, $rut)
{
    $connexion = new DatabasePDO();
    $sql = "select round(100*(count(id)/4)) as avance from tbl_objetos_finalizados where id_curso=:id_curso and rut=:rut";
    $connexion->query($sql);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}

function BuscaEmail($rut)
{
    $connexion = new DatabasePDO();
    $sql = "select email from tbl_usuario where rut=:rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}

function vio_vide_intro($rut, $ambiente, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "select count(h.id) as cuenta from tbl_banner_visto h where h.rut=:rut and h.id_empresa=:id_empresa and h.ambiente=:ambiente";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':ambiente', $ambiente);
    $cod = $connexion->resultset();
    return $cod;
}
function inserta_vio_video($rut, $ambiente, $id_empresa)
{
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $sql = "INSERT INTO tbl_banner_visto (rut, id_empresa, ambiente, fecha) 
            VALUES (:rut, :id_empresa, :ambiente, :fecha)";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':ambiente', $ambiente);
    $connexion->bind(':fecha', $fecha);
    $connexion->execute();
}


function ambiente_visto_cuenta($rut, $ambiente, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT COUNT(h.id) AS cuenta 
            FROM tbl_ambiente_visto h 
            WHERE h.rut=:rut AND h.id_empresa=:id_empresa AND h.ambiente=:ambiente";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':ambiente', $ambiente);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod[0]->cuenta;
}


function ambiente_visto_inserta($rut, $ambiente, $id_empresa)
{
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $sql = "INSERT INTO tbl_ambiente_visto (rut, id_empresa, ambiente, fecha) 
            VALUES (:rut, :id_empresa, :ambiente, :fecha)";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':ambiente', $ambiente);
    $connexion->bind(':fecha', $fecha);
    $connexion->execute();
}


function premio_solicitud_jefe_validacion($id, $id_empresa, $rut, $tipo)
{
    $connexion = new DatabasePDO();
    if ($tipo == 1) {
        $sql = "UPDATE tbl_premios_canje SET estadovalidacion='1' WHERE id=:id";
    } elseif ($tipo == 3) {
        $sql = "DELETE FROM tbl_premios_canje WHERE id=:id";
    }
    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;
}


function rutesvigente($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT vigencia FROM tbl_usuario h WHERE h.rut=:rut AND h.id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod[0]->vigencia;
}
function jefesaldopuntos_reco($rut, $id_empresa){
    $connexion = new DatabasePDO();
    $sql = "select h.rut, sum(puntos) as Recibidos,
            (select sum(puntos) from tbl_premios_puntos_usuarios_jefe_entregados where rut=h.rut) as CuentaEntregados
            from tbl_premios_puntos_usuarios_jefe h
            where h.rut=:rut and h.id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    $saldo = $cod[0]->Recibidos - $cod[0]->CuentaEntregados;
    return $saldo;
}


function BuscaCuiAsociadosaRut($rut){
    $connexion = new DatabasePDO();
    $sql = "select cui from tbl_cui_rut_jefe where rut=:rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;	
}


function jefesaldopuntos_reco_cui_COL($rut, $id_empresa){
    $connexion = new DatabasePDO();
    $sql = "select h.rut, sum(puntos) as Recibidos,
            (select sum(puntos) from tbl_premios_puntos_usuarios_jefe_entregados_col where rut=h.rut and id_empresa=h.id_empresa) as CuentaEntregados
            from tbl_premios_puntos_usuarios_jefe_col h
            where h.rut=:rut and h.id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    $saldo = $cod[0]->Recibidos - $cod[0]->CuentaEntregados;
    return $saldo;
}

function jefesaldopuntos_reco_cui($cui, $id_empresa){
    $connexion = new DatabasePDO();
    $sql = "
    select h.cui, sum(puntos) as Recibidos,
    (select sum(puntos) from tbl_premios_puntos_usuarios_jefe_entregados_cui where cui=:cui) as CuentaEntregados
    from tbl_premios_puntos_usuarios_cui_jefes h
    
    where h.cui=:cui and h.id_empresa=:id_empresa
     ";
    $connexion->query($sql);
    $connexion->bind(':cui', $cui);
    $connexion->bind(':id_empresa', $id_empresa);
    $datos = $connexion->resultset();
    
    $saldo= $datos[0]->Recibidos  -   $datos[0]->CuentaEntregados;
    
    return $saldo;
}
    
function jefesaldopuntos_reco_col($rut, $id_empresa){
    $connexion = new DatabasePDO();
    $sql = "
    select h.rut, sum(puntos) as Recibidos,
    (select sum(puntos) from tbl_premios_puntos_usuarios_jefe_entregados_col where rut=h.rut) as CuentaEntregados
    from tbl_premios_puntos_usuarios_jefe_col h
    
    where h.rut=:rut and h.id_empresa=:id_empresa
     ";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $datos = $connexion->resultset();
    
    $saldo= $datos[0]->Recibidos  -   $datos[0]->CuentaEntregados;
    
    return $saldo;
}
    
function Reco_espartedeEquipo($colsend, $rut) {
    $connexion = new DatabasePDO();
    $sql = "select count(id) as cuenta from tbl_usuario where rut=:colsend and jefe=:rut ";
    $connexion->query($sql);
    $connexion->bind(':colsend', $colsend);
    $connexion->bind(':rut', $rut);
    $datos = $connexion->resultset();
    return $datos[0]->cuenta;
}
    
function rutesjefe($rut, $id_empresa){
    $connexion = new DatabasePDO();
    $sql = "select count(h.id) as cuenta from tbl_usuario h where h.jefe=:rut and h.id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $datos = $connexion->resultset();
    return $datos;
}
function rutesjefe_2020_v2($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT COUNT(id) AS cuenta FROM tbl_usuario WHERE jefe = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}

function premios_solicitudes_pendientes($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "
    SELECT h.*,
        (SELECT nombre_completo FROM tbl_usuario WHERE rut=h.rut) AS nombre_persona,
        (SELECT premio FROM tbl_premios WHERE id_premio=h.id_premio AND id_empresa=:id_empresa) AS nombre_premio
    FROM tbl_premios_canje h
    WHERE
    (
        h.rut = :rut OR h.rut_jefe=:rut
        OR (SELECT responsable FROM tbl_usuario WHERE rut=h.rut AND id_empresa=h.id_empresa LIMIT 1)=:rut
        OR (SELECT lider FROM tbl_usuario WHERE rut=h.rut AND id_empresa=h.id_empresa LIMIT 1)=:rut
    )
    AND h.id_empresa=:id_empresa AND h.estadovalidacion='2' ORDER BY h.id DESC;
    ";

    //echo $sql;
    sleep(1);

    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}
function premios_guarda_solicitud($rut, $id_premio, $rut_jefe, $id_empresa, $fecha_texto, $fecha_solicitud, $hora_inicio, $hora_termino)
{
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    $sql2 = "SELECT * FROM tbl_premios WHERE id_premio=:id_premio AND id_empresa=:id_empresa";
    $connexion->query($sql2);
    $connexion->bind(':id_premio', $id_premio);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod2 = $connexion->resultset();
    $sql3 = "SELECT * FROM tbl_premios_canje WHERE id_premio=:id_premio AND id_empresa=:id_empresa AND rut=:rut
    AND fecha=:fecha AND hora_inicio=:hora_inicio AND hora_termino=:hora_termino";
    $connexion->query($sql3);
    $connexion->bind(':id_premio', $id_premio);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':hora_inicio', $hora_inicio);
    $connexion->bind(':hora_termino', $hora_termino);
    $cod3 = $connexion->resultset();
    $data_usuario_puntos = premios_buscadatos($rut, $id_empresa);
    $validacionpuntos = 0;
    if ($data_usuario_puntos[0]->puntossaldo >= $cod2[0]->puntos) {
    $validacionpuntos = 1;
    }
    if (count($cod3) == 0 and $validacionpuntos == 1) {
    $sql = "INSERT INTO tbl_premios_canje (rut, id_premio, fecha, hora, puntos, rut_jefe, id_empresa, estadovalidacion,
    fecha_texto, fecha_solicitud,hora_inicio,hora_termino) " . "VALUES (:rut, :id_premio, :fecha, :hora, :puntos, :rut_jefe, :id_empresa, '2',
    :fecha_texto, :fecha_solicitud,:hora_inicio,:hora_termino)";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_premio', $id_premio);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':hora', $hora);
    $connexion->bind(':puntos', $cod2[0]->puntos);
    $connexion->bind(':rut_jefe', $rut_jefe);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':fecha_texto', $fecha_texto);
    $connexion->bind(':fecha_solicitud', $fecha_solicitud);
    $connexion->bind(':hora_inicio', $hora_inicio);
    $connexion->bind(':hora_termino', $hora_termino);
    $connexion->execute();
    $swa = "solicitud";
    } elseif ($validacionpuntos == 0) {
    $swa = "sinpuntos";
    } elseif (count($cod3) > 0) {
    $swa = "solicitudyarealizada";
    }

    //echo "sql $sql";

    return $swa;
}

function premios_guarda_solicitud_estado($rut, $id_premio, $rut_jefe, $id_empresa, $fecha_texto, $fecha_solicitud, $hora_inicio, $hora_termino, $estado)
{
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    $sql2 = "SELECT h.* FROM tbl_premios h WHERE h.id_premio=:id_premio AND h.id_empresa=:id_empresa";
    $connexion->query($sql2);
    $connexion->bind(':id_premio', $id_premio);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod2 = $connexion->resultset();
    $sql3 = "SELECT h.* FROM tbl_premios_canje h WHERE h.id_premio=:id_premio AND h.id_empresa=:id_empresa AND h.rut=:rut AND h.fecha=:fecha AND h.hora_inicio=:hora_inicio AND h.hora_termino=:hora_termino";
    $connexion->query($sql3);
    $connexion->bind(':id_premio', $id_premio);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':hora_inicio', $hora_inicio);
    $connexion->bind(':hora_termino', $hora_termino);
    $cod3 = $connexion->resultset();
    $cod3 = array();

    $data_usuario_puntos = premios_buscadatos($rut, $id_empresa);
    $validacionpuntos = 0;
    if ($data_usuario_puntos[0]->puntossaldo >= $cod2[0]->puntos) {
        $validacionpuntos = 1;
    }
    if (count($cod3) == 0 and $validacionpuntos == 1) {
        $sql = "INSERT INTO tbl_premios_canje (rut, id_premio, fecha, hora, puntos, rut_jefe, id_empresa, estadovalidacion, fecha_texto, fecha_solicitud, hora_inicio, hora_termino) VALUES (:rut, :id_premio, :fecha, :hora, :puntos, :rut_jefe, :id_empresa, :estado, :fecha_texto, :fecha_solicitud, :hora_inicio, :hora_termino)";
        $connexion->query($sql);
        $connexion->bind(':rut', $rut);
        $connexion->bind(':id_premio', $id_premio);
        $connexion->bind(':fecha', $fecha);
        $connexion->bind(':hora', $hora);
        $connexion->bind(':puntos', $cod2[0]->puntos);
        $connexion->bind(':rut_jefe', $rut_jefe);
        $connexion->bind(':id_empresa', $id_empresa);
        $connexion->bind(':estado', $estado);
        $connexion->bind(':fecha_texto', $fecha_texto);
        $connexion->bind(':fecha_solicitud', $fecha_solicitud);
        $connexion->bind(':hora_inicio', $hora_inicio);
        $connexion->bind(':hora_termino', $hora_termino);
        $cod = $connexion->resultset();
        $swa = "solicitud";
    } elseif ($validacionpuntos == 0) {
        $swa = "sinpuntos";

    } elseif (count($cod3) > 0) {
        $swa = "solicitudyarealizada";
    }

    //echo "sql $sql";

    return $swa;
}

function premios_buscadatos($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT sum(h.puntos) as puntosrecibidos, 
           (IFNULL((SELECT sum(puntos) FROM tbl_premios_canje WHERE rut=:rut AND id_empresa=:id_empresa), 0)) as puntoscanjeados, 
           (sum(h.puntos) - (IFNULL((SELECT sum(puntos) FROM tbl_premios_canje WHERE rut=:rut AND id_empresa=:id_empresa), 0))) as puntossaldo 
           FROM tbl_premios_puntos_usuarios h WHERE h.rut=:rut AND h.id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function premios_ultimos_canjes($id_empresa, $rut, $limit)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*, (SELECT premio FROM tbl_premios WHERE id_premio=h.id_premio AND id_empresa=:id_empresa) as nombre_premio 
            FROM tbl_premios_canje h WHERE h.rut=:rut AND h.id_empresa=:id_empresa ORDER BY fecha DESC LIMIT :limit";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':limit', $limit, PDO::PARAM_INT);
    $cod = $connexion->resultset();
    return $cod;
}

function premios_ranking_canjes($id_empresa, $limit)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*, COUNT(h.id) as cuenta, (SELECT premio FROM tbl_premios WHERE id_premio=h.id_premio AND id_empresa=:id_empresa) as nombre_premio 
            FROM tbl_premios_canje h WHERE h.id_empresa=:id_empresa AND h.estadovalidacion='1' 
            GROUP BY h.id_premio 
            ORDER BY COUNT(h.id) DESC LIMIT :limit";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':limit', $limit, PDO::PARAM_INT);
    $cod = $connexion->resultset();
    return $cod;
}
function premios_ultimos_canjes_globales($id_empresa, $limit)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*, (SELECT premio FROM tbl_premios WHERE id_premio=h.id_premio AND id_empresa=:id_empresa) AS nombre_premio
            FROM tbl_premios_canje h
            WHERE h.id_empresa=:id_empresa AND h.estadovalidacion='1'
            ORDER BY fecha DESC LIMIT :limit";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':limit', $limit, PDO::PARAM_INT);
    $cod = $connexion->resultset();
    return $cod;
}

function premios_ultimos_puntos_recibidos($id_empresa, $rut, $limit)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*
            FROM tbl_premios_puntos_usuarios h
            WHERE h.rut=:rut AND h.id_empresa=:id_empresa
            ORDER BY fecha DESC LIMIT :limit";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':limit', $limit, PDO::PARAM_INT);
    $cod = $connexion->resultset();
    return $cod;
}

function TotalUsuariosMiRut($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*, (SELECT COUNT(id) FROM tbl_lms_acciones_pendientes WHERE rut_colaborador=h.rut) AS cuenta
            FROM tbl_usuario h
            WHERE h.rut=:rut AND h.vigencia='0'
            ORDER BY (SELECT COUNT(id) FROM tbl_lms_acciones_pendientes WHERE rut_colaborador=h.rut) DESC, h.nombre ASC";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}
function traigoDimensionPremios_data($segmento, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*, (SELECT COUNT(id) FROM tbl_premios WHERE id_dimension=h.id_dimension AND segmento=h.segmento) AS cuenta FROM tbl_premios_dimension h WHERE h.segmento=:segmento AND h.id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':segmento', $segmento);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function OfertaAbiertaDimensionData($id_empresa)
{
    $connexion = new DatabasePDO();
    $sql="
    SELECT h.id_dimension AS id, dimension AS text FROM tbl_lms_dimension_curso h WHERE h.id_empresa=:id_empresa GROUP BY h.id_dimension
    UNION SELECT h.id_competencia1 AS id, competencia1 AS text FROM tbl_lms_dimension_curso h WHERE h.id_empresa=:id_empresa AND competencia1<>'' GROUP BY h.competencia1
    ";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function OfertaAbiertaCursosDimensionData($id_empresa)
{
    $connexion = new DatabasePDO();
    $sql="SELECT h.* FROM tbl_lms_dimension_curso h WHERE h.id_empresa=:id_empresa ORDER BY h.curso";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function BuscarChecklistTipo($tipo,$id_empresa)
{
    $connexion = new DatabasePDO();
    $sql="SELECT h.* FROM tbl_checklist_item h WHERE h.id_empresa=:id_empresa AND h.tipo=:tipo ORDER BY h.nombre_item ASC";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':tipo', $tipo);
    $cod = $connexion->resultset();
    return $cod;
}

function BuscarChecklistTipoId($tipo,$id_empresa)
{
    $connexion = new DatabasePDO();
    $sql="SELECT h.* FROM tbl_checklist_item h WHERE h.id_empresa=:id_empresa AND h.tipo=:tipo ORDER BY h.id ASC";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':tipo', $tipo);
    $cod = $connexion->resultset();
    return $cod;
}
function Rec_Busca_reconocimiento_landing($rut, $tipo, $id_empresa){

    $connexion = new DatabasePDO();
    $sql = "SELECT *, 
            (SELECT nombre_completo FROM tbl_usuario WHERE rut=h.rut_destinatario) as nombre_completo 
            FROM tbl_reconoce_gracias h 
            WHERE h.rut_remitente = :rut 
            AND h.estado='APROBADO' 
            AND h.categorita='' 
            AND h.id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    
    if($cod[0]->id > 0){
        $alerta="<br><div class='alert alert-success'><strong>Felicitaciones</strong>: Ya fue validado tu reconocimiento para ".$cod[0]->nombre_completo.". Vuelve a realizar el Reconocimiento y podrás finalizarlo correctamente. </div>";
    }
    
    $sql2 = "UPDATE tbl_reconoce_gracias h 
            SET h.categorita='SELECCION DEL CHILE' 
            WHERE h.rut_remitente = :rut 
            AND h.estado='APROBADO' 
            AND h.categorita='' 
            AND h.id_empresa = :id_empresa";
    $connexion->query($sql2);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
    
    return $alerta;
}
    
function traigoDimensionPremiosDimension_Item_data($id_dim, $segmento, $id_empresa){
    
    $connexion = new DatabasePDO();
    if($id_dim==""){
        $queryDim=" ";
    }else{
        $queryDim=" and id_dimension=:id_dim ";
    }
    
    $sql = "SELECT * FROM tbl_premios_dimension WHERE segmento=:segmento $queryDim AND id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':segmento', $segmento);
    $connexion->bind(':id_empresa', $id_empresa);
    if($queryDim !== " "){
        $connexion->bind(':id_dim', $id_dim);
    }
    
    $cod = $connexion->resultset();
    return $cod;
}

function traigoDimensionPremiosDimension_Item_Todos_data($segmento, $id_empresa, $id_dim = ''){
    $connexion = new DatabasePDO();
    if($id_dim==""){
    $queryDim=" ";
    } else {
    $queryDim=" and id_dimension=:id_dim ";
    $connexion->bind(':id_dim', $id_dim);
    }
    $sql = "SELECT * FROM tbl_premios_dimension WHERE segmento=:segmento $queryDim AND id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':segmento', $segmento);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function traigoDimensionPremios_data_rut($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*,
    (SELECT premio FROM tbl_premios WHERE id_premio=h.id_premio) AS premio,
    (SELECT premioludico FROM tbl_premios WHERE id_premio=h.id_premio) AS premioludico,
    (SELECT puntos FROM tbl_premios WHERE id_premio=h.id_premio) AS puntos,
    (SELECT id_premio FROM tbl_premios WHERE id_premio=h.id_premio) AS id_premio,
    (SELECT condiciones FROM tbl_premios WHERE id_premio=h.id_premio) AS condiciones,
    (SELECT requisitos FROM tbl_premios WHERE id_premio=h.id_premio) AS requisitos
    FROM tbl_premios_al_rut h WHERE h.rut=:rut AND h.id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function traigoPremios_data($segmento, $id_empresa, $iddimension)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*,
    (SELECT count(id) FROM tbl_premios_canje WHERE id_premio=h.id_premio AND id_empresa=h.id_empresa AND puntos>0) AS canjes,
    (h.stock - (SELECT count(id) FROM tbl_premios_canje WHERE id_premio=h.id_premio AND id_empresa=h.id_empresa AND puntos>0)) AS saldo
    FROM tbl_premios h
    WHERE h.segmento=:segmento AND h.id_empresa=:id_empresa AND h.id_dimension=:iddimension ORDER BY h.orden ASC";
    $connexion->query($sql);
    $connexion->bind(':segmento', $segmento);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':iddimension', $iddimension);
    $cod = $connexion->resultset();
    return $cod;
}
function traigoPremio_data($id_premio, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*,
            (SELECT COUNT(id) FROM tbl_premios_canje WHERE id_premio=h.id_premio AND id_empresa=h.id_empresa AND puntos>0) AS canjes,
            (h.stock - (SELECT COUNT(id) FROM tbl_premios_canje WHERE id_premio=h.id_premio AND id_empresa=h.id_empresa AND puntos>0)) AS saldo
            FROM tbl_premios h
            WHERE h.id_premio=:id_premio AND h.id_empresa=:id_empresa LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':id_premio', $id_premio);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function traigoPremios_Portfolio_data($id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*,
            (SELECT COUNT(id) FROM tbl_premios_canje WHERE id_premio=h.id_premio AND id_empresa=h.id_empresa AND puntos>0) AS canjes,
            (h.stock - (SELECT COUNT(id) FROM tbl_premios_canje WHERE id_premio=h.id_premio AND id_empresa=h.id_empresa AND puntos>0)) AS saldo
            FROM tbl_premios h
            WHERE h.id_empresa=:id_empresa ORDER BY h.orden ASC";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function traigoPremios($id_premio, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_premios WHERE id_premio=:id_premio AND id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_premio', $id_premio);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function traigoIdCanjePremiosId($id_premio, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_premios_canje WHERE id=:id_premio AND id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_premio', $id_premio);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}
function busca_premios_datos_usuario($rut, $tipo, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT dato from tbl_premios_datos_usuarios WHERE rut=:rut and id_empresa=:id_empresa and tipo=:tipo";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':tipo', $tipo);
    $cod = $connexion->resultset();
    return $cod;
}

function busca_premios_solicitudes_jefe($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.rut,
            (select rut_completo from tbl_usuario where rut= h.rut) as RutCompleto,
            (select nombre_completo from tbl_usuario where rut= h.rut) as Nombre,
            (select cargo from tbl_usuario where rut= h.rut) as Cargo,
            (select gerencia from tbl_usuario where rut= h.rut) as Gerencia,
            (select premio from tbl_premios where id_premio=h.id_premio) as NombrePremio,
            h.*
            from tbl_premios_canje h where
            (select jefe from tbl_usuario where rut=h.rut)=:rut
            or
            (select lider from tbl_usuario where rut=h.rut)=:rut
            or
            (select responsable from tbl_usuario where rut=h.rut)=:rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}

function VerificaSesionChequeado($rut, $id_imparticion, $id_empresa, $sesionid)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT count(id) as cuenta FROM `rel_lms_inscripcion_usuario_checkin` WHERE `rut` = :rut and codigo_imparticion=:id_imparticion and id_empresa=:id_empresa and sesion=:sesionid";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_imparticion', $id_imparticion);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':sesionid', $sesionid);
    $cod = $connexion->resultset();
    return $cod;
}
function ActualizaDatosMiPerfil($rut, $id_empresa, $biografia)
{
    $connexion = new DatabasePDO();
    $sql = "UPDATE tbl_miperfil SET biografia=:biografia WHERE id_empresa=:id_empresa AND rut=:rut";
    $connexion->query($sql);
    $connexion->bind(':biografia', $biografia);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $connexion->execute();
}

function InsertaDatosMiPerfil($rut, $id_empresa, $biografia)
{
    $connexion = new DatabasePDO();
    $sql = "INSERT INTO tbl_miperfil(rut, id_empresa, biografia) VALUES (:rut, :id_empresa, :biografia)";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':biografia', $biografia);
    $connexion->execute();
}

function TraeDatosMiPerfil($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_miperfil WHERE rut=:rut AND id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $datos = $connexion->resultset();
    return $datos;
}

function galeria_registra_archivoPorObjetoVideo($nombre_archivo, $id_objeto, $id_empresa, $rut_autor, $estado, $id_malla, $titulo_video, $descripcion_video)
{
    $connexion = new DatabasePDO();
    $sql = "INSERT INTO tbl_galeria_archivos(archivo,id_objeto,fecha,tipo, id_empresa, rut_autor, id_malla, titulo_video, descripcion_video) " . "VALUES (:nombre_archivo, :id_objeto, :fecha, :estado, :id_empresa, :rut_autor, :id_malla, :titulo_video, :descripcion_video)";
    $connexion->query($sql);
    $connexion->bind(':nombre_archivo', $nombre_archivo);
    $connexion->bind(':id_objeto', $id_objeto);
    $connexion->bind(':fecha', date("Y-m-d"));
    $connexion->bind(':estado', $estado);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut_autor', $rut_autor);
    $connexion->bind(':id_malla', $id_malla);
    $connexion->bind(':titulo_video', $titulo_video);
    $connexion->bind(':descripcion_video', $descripcion_video);
    $connexion->execute();
}

function ObtenerVideosMasMegusta($id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT
    tbl_galeria_archivos., nombre_completo,
    (SELECT count(id)AS total FROM tbl_megusta_registro WHERE id_imagen_galeria = tbl_galeria_archivos.id)AS total_megusta
    FROM
    tbl_galeria_archivos
    INNER JOIN tbl_usuario ON tbl_usuario.rut = tbl_galeria_archivos.rut_autor
    WHERE
    tbl_galeria_archivos.id_empresa = :id_empresa
    and tbl_galeria_archivos.titulo_video is not null and tbl_galeria_archivos.descripcion_video is not null

    order by total_megusta desc limit 10";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function VerificoEvaluadosFinalizadosPorEvaluador2($rut_fijador, $id_proceso, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT DISTINCT
    (
    tbl_objetivos_validaciones.evaluado
    ),
    tbl_usuario.nombre_completo
    FROM
    tbl_objetivos_validaciones
    INNER JOIN tbl_usuario ON tbl_usuario.rut = tbl_objetivos_validaciones.evaluado
    inner join tbl_sgd_relaciones on tbl_sgd_relaciones.evaluado=tbl_objetivos_validaciones.evaluado and tbl_sgd_relaciones.id_proceso=:id_proceso
    WHERE
    tbl_sgd_relaciones.evaluador = :rut_fijador
    AND tbl_objetivos_validaciones.id_empresa = :id_empresa
    AND tbl_objetivos_validaciones.id_proceso = :id_proceso";
    $connexion->query($sql);
    $connexion->bind(':rut_fijador', $rut_fijador);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_proceso', $id_proceso);
    $cod = $connexion->resultset();
    return $cod;
}

function EliminaFinalizadoPorDimension($id_empresa, $id_proceso, $evaluado, $id_dimension)
{
    $connexion = new DatabasePDO();
    $sql = "DELETE FROM tbl_objetivos_indviduales_finalizado WHERE evaluado = :evaluado AND id_dimension = :id_dimension AND id_empresa = :id_empresa AND id_proceso = :id_proceso";
    $connexion->query($sql);
    $connexion->bind(':evaluado', $evaluado);
    $connexion->bind(':id_dimension', $id_dimension);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_proceso', $id_proceso);
    $connexion->execute();
}

function com_vot_recono_busca_datos($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.rut, h.puntos as SumaPuntos,
    (SELECT COUNT(id) FROM tbl_mp_interacciones WHERE id_empresa = :id_empresa AND rut = :rut) as CuentaReconocimientosRecibidos,
    (SELECT COUNT(id) FROM tbl_mp_interacciones WHERE id_empresa = :id_empresa AND rut_remitente = :rut) as CuentaReconocimientosOtorgados
    FROM tbl_mp_interacciones h WHERE id_empresa = :id_empresa AND rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $datos = $connexion->resultset();
    return $datos;
}

function InsertaEntablaInteraccion($id_empresa, $rut, $rut_remitente, $mensaje, $puntos, $tipo, $id_mp)
{
    $connexion = new DatabasePDO();
    $sql = "INSERT INTO tbl_mp_interacciones(rut, id_empresa, fecha, rut_remitente, mensaje, puntos, tipo, id_mp)
    VALUES (:rut, :id_empresa, :fecha, :rut_remitente, :mensaje, :puntos, :tipo, :id_mp)";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':fecha', date("Y-m-d"));
    $connexion->bind(':rut_remitente', $rut_remitente);
    $connexion->bind(':mensaje', $mensaje);
    $connexion->bind(':puntos', $puntos);
    $connexion->bind(':tipo', $tipo);
    $connexion->bind(':id_mp', $id_mp);
    $connexion->execute();

    $last_insert_id = $connexion->lastInsertId();
    $sql_last_id = "SELECT :valor as valor";
    $connexion->query($sql_last_id);
    $connexion->bind(':valor', $last_insert_id);
    $cod = $connexion->resultset();
    return $cod;
}

function Reco_BuscaRecoDadosPersona($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT COUNT(id) as cuenta FROM tbl_reconoce_gracias WHERE id_empresa = :id_empresa AND rut_remitente = :rut";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod[0]->cuenta;
}
function lms_busca_posibles_postulaciones($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "
    select h.* from tbl_inscripcion_postulantes h where h.rut=:rut and h.id_empresa=:id_empresa group by h.id_curso
    ";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function TraeEmailUsuario($rut, $id_empresa){

    $connexion = new DatabasePDO();
    $sql = "

    select email from tbl_usuario where rut=:rut and id_empresa=:id_empresa

    ";

    //echo $sql;
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod[0]->email;

}

function buscacursosaprobadospornivel_data($id_orden_nivel, $rut, $id_empresa, $id_malla)
{
    $connexion = new DatabasePDO();
    $sql = "

    select h.*,
    (select estado from tbl_inscripcion_cierre where rut=:rut and id_curso=h.idcurso and id_empresa=:id_empresa and estado='1') as aprobado
    from tbl_gamificado_nivel h
    where h.idorden=:id_orden_nivel and h.idmalla=:id_malla and h.idempresa=:id_empresa

    ";
    $connexion->query($sql);
    $connexion->bind(':id_orden_nivel', $id_orden_nivel);
    $connexion->bind(':id_malla', $id_malla);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}

function EsJefeResponsabletblUsuario($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "
    select count(id) as cuenta from tbl_usuario where responsable=:rut and id_empresa=:id_empresa
    ";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod[0]->cuenta;
}
function EsLiderEjecutivotblUsuario($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT COUNT(id) AS cuenta FROM tbl_usuario WHERE lider = :rut AND id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod[0]->cuenta;
}

function TraigoMalladadoRutCursoEmpresa($rut, $id_curso, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_inscripcion_usuarios WHERE rut = :rut AND id_curso = :id_curso AND id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function TraigoObjetosDadoRutYMalla($rut, $malla, $id_objeto)
{
    $connexion = new DatabasePDO();
    if ($id_objeto) {
        $filtro_objeto = " AND tbl_objeto.id = :id_objeto";
    }
    if ($_GET["sw"] == "MuestraBloqueColaboradoresPorJefe" && $_SESSION["id_objeto_para_vistaequipo"]) {
        $filtro_objeto = " AND tbl_objeto.id = :id_objeto_para_vistaequipo";
    }
    $sql = "SELECT tbl_objeto.id AS id_objeto, tbl_objeto.titulo AS nombre_objeto, tbl_objetos_finalizados.nota, rel_lms_malla_curso.id_curso, tbl_objeto.extension_objeto
            FROM rel_lms_malla_curso
            INNER JOIN tbl_objeto ON tbl_objeto.id_curso = rel_lms_malla_curso.id_curso
            LEFT JOIN tbl_objetos_finalizados ON tbl_objetos_finalizados.id_objeto = tbl_objeto.id AND tbl_objetos_finalizados.rut = :rut
            WHERE (rel_lms_malla_curso.id_malla = :malla AND tbl_objeto.tipo_objeto = '5') OR (rel_lms_malla_curso.id_malla = :malla AND tbl_objeto.extension_objeto = '23') $filtro_objeto";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':malla', $malla);
    if ($id_objeto) {
        $connexion->bind(':id_objeto', $id_objeto);
    }
    if ($_GET["sw"] == "MuestraBloqueColaboradoresPorJefe" && $_SESSION["id_objeto_para_vistaequipo"]) {
        $connexion->bind(':id_objeto_para_vistaequipo', $_SESSION["id_objeto_para_vistaequipo"]);
    }
    $cod = $connexion->resultset();
    return $cod;
}
function LMS_ConsultaRutSegunEmail_data($email, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.* FROM tbl_usuario h WHERE h.email=:email AND h.id_empresa=:id_empresa AND h.vigencia='0' AND h.rut<>''";
    $connexion->query($sql);
    $connexion->bind(':email', $email);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function Lms_Busca_Acciones_Pendientes_dataVistaAgente($rut, $rut_colaborador, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT
    h.*,
    (SELECT nombre FROM tbl_lms_curso WHERE id=h.id_curso AND id_empresa=h.id_empresa) AS nombre_curso,
    (SELECT titulo FROM tbl_objeto WHERE id=h.id_objeto AND id_curso=h.id_curso) AS nombre_objeto,
    (SELECT descripcion FROM tbl_objeto WHERE id=h.id_objeto AND id_curso=h.id_curso) AS descripcion_objeto

    FROM
    tbl_lms_acciones_pendientes h
    WHERE
    h.rut_jefe = :rut
    AND h.rut_colaborador = :rut_colaborador
    AND h.id_empresa = :id_empresa
    AND h.estado='1'";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':rut_colaborador', $rut_colaborador);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function TraeMensajesPorAgente($rut_agente, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "
    SELECT
    tbl_mensajes_principal.*, base_creador.jefe,
    (SELECT COUNT(*) FROM tbl_mensajes_respuestas WHERE tbl_mensajes_respuestas.id_mensaje=tbl_mensajes_principal.id AND tipo_creador='2') AS total_respuestas_agente
    FROM
    tbl_mensajes_principal

    INNER JOIN tbl_usuario AS base_creador
    ON base_creador.rut=tbl_mensajes_principal.rut_creador
    WHERE
    tbl_mensajes_principal.id_empresa = :id_empresa
    AND base_creador.jefe=:rut_agente

    ";
    $connexion->query($sql);
    $connexion->bind(':rut_agente', $rut_agente);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function DatosTema($tema)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_tema WHERE id_tema=:tema";
    $connexion->query($sql);
    $connexion->bind(':tema', $tema);
    $cod = $connexion->resultset();
    return $cod;
}
function ListaEsJefetblUsuario($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "
    select * from tbl_usuario where jefe=:rut and id_empresa=:id_empresa
    ";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function EsJefetblUsuario($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "
    select count(id) as cuenta from tbl_usuario where jefe=:rut and id_empresa=:id_empresa
    ";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod[0]->cuenta;
}

function EsJefeLidertblUsuario($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "
    select count(id) as cuenta from tbl_usuario where jefe=:rut and id_empresa=:id_empresa
    ";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod[0]->cuenta;
}

function EliminoRespuestasTrivias($rut, $id_objeto)
{
    $connexion = new DatabasePDO();
    $sql = "DELETE from tbl_evaluaciones_sesion WHERE rut= :rut and id_objeto=:id_objeto";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_objeto', $id_objeto);
    $connexion->execute();

    $sql = "DELETE from tbl_evaluaciones_respuestas WHERE rut= :rut and id_objeto=:id_objeto";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_objeto', $id_objeto);
    $connexion->execute();
}

function PreguntasEvalDadoIdObjeto($id_objeto)
{
    $connexion = new DatabasePDO();
    $sql = "select tbl_evaluaciones.nombre_evaluacion, tbl_evaluaciones_preguntas.pregunta, tbl_evaluaciones_preguntas.id_grupo_alternativas, tbl_evaluaciones_preguntas.orden as orden_preguntas from tbl_evaluaciones
    inner join tbl_evaluaciones_preguntas
    on tbl_evaluaciones_preguntas.evaluacion=tbl_evaluaciones.id
    where id_objeto=:id_objeto
    order by orden_preguntas asc
    ";
    $connexion->query($sql);
    $connexion->bind(':id_objeto', $id_objeto);
    $cod = $connexion->resultset();
    return $cod;
}
function InsertaAlternativaPAraEvalPreguntas($id_grupo_alternativas, $alternativa, $correcta, $orden)
{
    $connexion = new DatabasePDO();
    $sql = "INSERT INTO tbl_evaluaciones_alternativas(
    id_grupo_alternativas, alternativa, correcta, orden
    ) " . "VALUES (
    :id_grupo_alternativas, :alternativa, :correcta, :orden
    );";
    $connexion->query($sql);
    $connexion->bind(':id_grupo_alternativas', $id_grupo_alternativas);
    $connexion->bind(':alternativa', $alternativa);
    $connexion->bind(':correcta', $correcta);
    $connexion->bind(':orden', $orden);
    $connexion->execute();
}

function ObtenerUltimoGrupoAlternativa()
{
    $connexion = new DatabasePDO();
    $sql = "SELECT MAX(id_grupo_alternativas) AS numero_mayor FROM tbl_evaluaciones_alternativas";
    $connexion->query($sql);
    $datos = $connexion->resultset();
    return $datos;
}

function InsertaPreguntasPorEvaluacion($pregunta, $id_evaluacion, $orden, $id_grupo_alternativas)
{
    $connexion = new DatabasePDO();
    $sql = "INSERT INTO tbl_evaluaciones_preguntas(
    pregunta, tipo, evaluacion, orden, id_grupo_alternativas
    ) " . "VALUES (:pregunta, 1, :id_evaluacion, :orden, :id_grupo_alternativas
    );";
    $connexion->query($sql);
    $connexion->bind(':pregunta', $pregunta);
    $connexion->bind(':id_evaluacion', $id_evaluacion);
    $connexion->bind(':orden', $orden);
    $connexion->bind(':id_grupo_alternativas', $id_grupo_alternativas);
    $connexion->execute();
}

function EvaluacionDadoIdObjeto($id_empresa, $id_curso, $id_objeto)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_evaluaciones WHERE id_empresa = :id_empresa AND id_curso = :id_curso AND id_objeto = :id_objeto";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':id_objeto', $id_objeto);
    $datos = $connexion->resultset();
    return $datos;
}
function CreoEvaluacionPorObjeto($id_empresa, $id_curso, $id_objeto)
{
    $connexion = new DatabasePDO();
    $sql = "INSERT INTO tbl_evaluaciones(nombre_evaluacion, id_empresa, id_curso, id_objeto) 
            VALUES (:id_objeto, :id_empresa, :id_curso, :id_objeto)";
    $connexion->query($sql);
    $connexion->bind(':id_objeto', $id_objeto);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->execute();
    $lastInsertId = $connexion->lastInsertId();
    $sql = "UPDATE tbl_objeto SET id_evaluacion = :lastInsertId WHERE id = :id_objeto";
    $connexion->query($sql);
    $connexion->bind(':lastInsertId', $lastInsertId);
    $connexion->bind(':id_objeto', $id_objeto);
    $connexion->execute();
    return $lastInsertId;
}

function TraigoIdEncuestaDadoIdCurso($id_empresa, $id_curso)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT tbl_lms_programas_bbdd.id_enc_sat 
            FROM rel_lms_malla_curso 
            LEFT JOIN tbl_lms_programas_bbdd ON tbl_lms_programas_bbdd.id_programa = rel_lms_malla_curso.id_programa
            WHERE id_curso = :id_curso AND rel_lms_malla_curso.id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
    $result = $connexion->resultset();
    return $result;
}

function InsertoObjetoCheckin($arreglo, $id_empresa, $id_curso, $orden, $id_curso_real)
{
    $connexion = new DatabasePDO();
    if ($id_curso_real) {
        $datos_encuesta = TraigoIdEncuestaDadoIdCurso($id_empresa, $id_curso_real);
        if ($datos_encuesta[0]->id_enc_sat) {
            $id_encuesta = $datos_encuesta[0]->id_enc_sat;
        } else {
            $id_encuesta = $arreglo->id_encuesta;
        }
    } else {
        $id_encuesta = $arreglo->id_encuesta;
    }
    if ($arreglo->tipo_objeto <> "6") {
        $id_encuesta = 0;
    }
    $sql = "INSERT INTO tbl_objeto(
     id,
     tipo_objeto,
     titulo,
     titulo_relator,
     descripcion,
     id_curso,
     extension_objeto,
     id_encuesta,
     orden,
     imagen,
     id_empresa,
     titulo_principal,
     pregunta_principal,
     bajada_principal,
     titulo_secundario,
     bajada_secundario,
     subtitulo_principal,
     bajada_subtitulo_principal,
     opcional,
     checkin,
     url_volver_checkin
     ) " . "VALUES (
     :id,
     :tipo_objeto,
     :titulo,
     :titulo_relator,
     :descripcion,
     :id_curso,
     :extension_objeto,
     :id_encuesta,
     :orden,
     :imagen,
     :id_empresa,
     :titulo_principal,
     :pregunta_principal,
     :bajada_principal,
     :titulo_secundario,
     :bajada_secundario,
     :subtitulo_principal,
     :bajada_subtitulo_principal,
     :opcional,
     :checkin,
     :url_volver_checkin
     )    ;";
    $connexion->query($sql);
    $connexion->bind(':id', $id_curso . "_" . $arreglo->id);
    $connexion->bind(':tipo_objeto', $arreglo->tipo_objeto);
    $connexion->bind(':titulo', $arreglo->titulo);
    $connexion->bind(':titulo_relator', $arreglo->titulo_relator);
    $connexion->bind(':descripcion', $arreglo->descripcion);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':extension_objeto', $arreglo->extension_objeto);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':orden', $orden);
    $connexion->bind(':imagen', $arreglo->imagen);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':titulo_principal', $arreglo->titulo_principal);
    $connexion->bind(':pregunta_principal', $arreglo->pregunta_principal);
    $connexion->bind(':bajada_principal', $arreglo->bajada_principal);
    $connexion->bind(':titulo_secundario', $arreglo->titulo_secundario);
    $connexion->bind(':bajada_secundario', $arreglo->bajada_secundario);
    $connexion->bind(':subtitulo_principal', $arreglo->subtitulo_principal);
    $connexion->bind(':bajada_subtitulo_principal', $arreglo->bajada_subtitulo_principal);
    $connexion->bind(':opcional', $arreglo->opcional);
    $connexion->bind(':opcional', $arreglo->checkin);
    $connexion->bind(':opcional', $arreglo->url_volver_checkin);
    $connexion->execute();
}

function CHECK_TraeObjetosPAraIngresar()
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_checkin_objeto";
    $connexion->query($sql);
    $cod = $connexion->resultset();
    return $cod;
}

function CHECK_ObjetosPorCurso($id_curso)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_objeto WHERE id_curso = :id_curso";
    $connexion->query($sql);
    $connexion->bind(':id_curso', $id_curso);
    $cod = $connexion->resultset();
    return $cod;
}

function MP_TraigoSubCategoriasDadoCategoria($id_categoria, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_mp_subcategorias WHERE id_empresa = :id_empresa AND id_categoria = :id_categoria";
    $connexion->query($sql);
    $connexion->bind(':id_categoria', $id_categoria);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function ComentariosPorMallaEmpresaLimit($id_malla, $id_empresa, $limit)
{
    $connexion = new DatabasePDO();
    if ($limit) {
        $limit_string = "LIMIT :limit";
        $connexion->bind(':limit', $limit);
    } else {
        $limit_string = "";
    }
    $sql = "
        SELECT
            tbl_objeto_comentarios.*,
            nombre_completo,
            tbl_lms_curso.nombre AS valor_categoria
        FROM tbl_objeto_comentarios
        INNER JOIN tbl_usuario ON tbl_usuario.rut=tbl_objeto_comentarios.rut
        INNER JOIN tbl_lms_curso ON tbl_lms_curso.id=tbl_objeto_comentarios.id_curso
        WHERE tbl_objeto_comentarios.id_empresa=:id_empresa AND id_malla=:id_malla
        ORDER BY id DESC
        $limit_string";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_malla', $id_malla);
    $cod = $connexion->resultset();
    return $cod;
}

function ComentariosPorMallaEmpresa($id_malla, $id_empresa, $limit)
{
    $connexion = new DatabasePDO();
    if ($limit) {
    $limite = "limit $limit";
    }
    $sql = "
    select
    tbl_objeto_comentarios., nombre_completo, tbl_lms_curso.nombre as valor_categoria,
    (select count(id) as total from tbl_objeto_comentarios_megusta where tbl_objeto_comentarios_megusta.id_comentario=tbl_objeto_comentarios.id ) as total_megusta

    from tbl_objeto_comentarios
    inner join tbl_usuario
    on tbl_usuario.rut=tbl_objeto_comentarios.rut


    inner join tbl_lms_curso
    on tbl_lms_curso.id=tbl_objeto_comentarios.id_curso


    where tbl_objeto_comentarios.id_empresa=:id_empresa and id_malla=:id_malla
    order by total_megusta desc
    $limite";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_malla', $id_malla);
    $cod = $connexion->resultset();
    return $cod;
}

function InsertoComentarioPorMallaEmpresa($rut, $comentario, $id_curso, $id_malla, $id_empresa)
{
    $connexion = new DatabasePDO();
    $existe_comentario = ComentariosPorObjetoDuplicado($id_objeto, $comentario, $rut);
    if (!$existe_comentario) {
    $sql = "INSERT INTO tbl_objeto_comentarios(rut, comentario, fecha, hora, id_malla, id_empresa, id_curso) " . "VALUES (:rut, :comentario, :fecha, :hora, :id_malla, :id_empresa, :id_curso)";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':comentario', $comentario);
    $connexion->bind(':fecha', date("Y-m-d"));
    $connexion->bind(':hora', date("H:i:s"));
    $connexion->bind(':id_malla', $id_malla);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->execute();
    } else {
    echo "<script>
    alert('Comentario ya existe');
    </script>";
    }
}

function ObtenerCursosDadoMalla2($id_malla)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT tbl_lms_curso.nombre, tbl_lms_curso.id FROM rel_lms_malla_curso INNER JOIN tbl_lms_curso ON tbl_lms_curso.id=rel_lms_malla_curso.id_curso WHERE rel_lms_malla_curso.id_malla=:id_malla";
    $connexion->query($sql);
    $connexion->bind(':id_malla', $id_malla);
    $cod = $connexion->resultset();
    return $cod;
}

function ObtenerRankigPorAvanceMallaempresa($limit, $id_empresa, $id_malla)
{
    $connexion = new DatabasePDO();
    if ($id_empresa == 52) {
    $sql = "
    SELECT
    tbl_inscripcion_cierre.rut,
    nombre_completo,
    cargo,
    AVG(tbl_inscripcion_cierre.avance) AS promedio,
    rel_lms_malla_persona.rut,
    rel_lms_malla_persona.id_malla,
    COUNT(tbl_objeto_comentarios.id) AS cuentacomentarios,
    SUM(tbl_gamificado_puntos.medallas) AS cuentamedallas,
    SUM(tbl_gamificado_puntos.puntos) AS cuentapuntos,
    ROUND(COUNT() (1+AVG(tbl_inscripcion_cierre.avance)) (1+(COUNT(tbl_objeto_comentarios.id)/5)) (1+(SUM(tbl_gamificado_puntos.medallas)/5))) AS valor
    FROM tbl_inscripcion_cierre
    INNER JOIN rel_lms_malla_persona ON rel_lms_malla_persona.rut = tbl_inscripcion_cierre.rut
    INNER JOIN tbl_usuario ON tbl_usuario.rut = tbl_inscripcion_cierre.rut
    LEFT JOIN tbl_objeto_comentarios ON tbl_objeto_comentarios.rut = tbl_inscripcion_cierre.rut
    LEFT JOIN tbl_gamificado_puntos ON tbl_gamificado_puntos.rut = tbl_inscripcion_cierre.rut
    LEFT JOIN tbl_gamificado_puntos_consolidado ON tbl_gamificado_puntos_consolidado.rut = tbl_inscripcion_cierre.rut
    WHERE rel_lms_malla_persona.id_malla = :id_malla
    AND tbl_inscripcion_cierre.id_empresa = :id_empresa
    GROUP BY tbl_inscripcion_cierre.rut
    ORDER BY cuentamedallas DESC,cuentapuntos DESC LIMIT 3;";
    } else {
    $sql = "
    SELECT
    tbl_inscripcion_cierre.rut,
    nombre_completo,
    cargo,
    AVG(tbl_inscripcion_cierre.avance) AS promedio,
    rel_lms_malla_persona.rut,
    rel_lms_malla_persona.id_malla,
    COUNT(DISTINCT tbl_objeto_comentarios.id) AS cuentacomentarios,
    tbl_gamificado_puntos_consolidado.medallas AS cuentamedallas,
    ROUND(COUNT()(1+AVG(tbl_inscripcion_cierre.avance))(1+COUNT(DISTINCT tbl_objeto_comentarios.id)/5)(1+tbl_gamificado_puntos_consolidado.medallas/5)) AS valor
    FROM tbl_inscripcion_cierre
    INNER JOIN rel_lms_malla_persona ON rel_lms_malla_persona.rut=tbl_inscripcion_cierre.rut
    INNER JOIN tbl_usuario ON tbl_usuario.rut=tbl_inscripcion_cierre.rut
    LEFT JOIN tbl_objeto_comentarios ON tbl_objeto_comentarios.rut=tbl_inscripcion_cierre.rut
    LEFT JOIN tbl_gamificado_puntos_consolidado ON tbl_gamificado_puntos_consolidado.rut=tbl_inscripcion_cierre.rut
    WHERE id_malla=:id_malla AND tbl_inscripcion_cierre.id_empresa=:id_empresa
    GROUP BY tbl_inscripcion_cierre.rut
    ORDER BY valor DESC
    LIMIT :limit
    ";
    }
    $connexion->query($sql);
    $connexion->bind(':id_malla', $id_malla);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':limit', $limit, PDO::PARAM_INT);
    $cod = $connexion->resultset();
    return $cod;
}

function ObtenerRankigPorAvanceMallaempresaIndividual($limit, $id_empresa, $id_malla)
{
$connexion = new DatabasePDO();
$sql = "SELECT h.rut, nombre_completo, cargo, avg(h.avance) as promedio,
rel_lms_malla_persona.rut, rel_lms_malla_persona.id_malla,
(SELECT sum(puntos) FROM tbl_gamificado_puntos
WHERE rut=h.rut AND id_empresa=h.id_empresa AND id_malla=:id_malla) as puntos
FROM tbl_inscripcion_cierre h
INNER JOIN rel_lms_malla_persona ON rel_lms_malla_persona.rut=h.rut
INNER JOIN tbl_usuario ON tbl_usuario.rut=h.rut
WHERE id_malla=:id_malla AND h.id_empresa=:id_empresa
GROUP BY h.rut ORDER BY puntos DESC LIMIT :limit";
$connexion->query($sql);
$connexion->bind(':id_malla', $id_malla);
$connexion->bind(':id_empresa', $id_empresa);
$connexion->bind(':limit', $limit, PDO::PARAM_INT);
$cod = $connexion->resultset();
return $cod;
}
function PuntosPersonalesHomeBciRev_data($rut, $id_empresa, $id_malla)
{
    $connexion = new DatabasePDO();
    $sql = "
        SELECT tbl_inscripcion_cierre.rut, nombre_completo, cargo, AVG(tbl_inscripcion_cierre.avance) AS promedio, 
            rel_lms_malla_persona.rut, rel_lms_malla_persona.id_malla,
            (SELECT COUNT(id) FROM tbl_objeto_comentarios WHERE rut=tbl_inscripcion_cierre.rut) AS cuentacomentarios,
            (SELECT medallas FROM tbl_gamificado_puntos_consolidado WHERE rut=tbl_inscripcion_cierre.rut) AS cuentamedallas,
            (ROUND(
                COUNT(*) *
                (1 + AVG(tbl_inscripcion_cierre.avance)) *
                (1 + (SELECT COUNT(id) FROM tbl_objeto_comentarios WHERE rut=tbl_inscripcion_cierre.rut) / 5) *
                (1 + (SELECT medallas FROM tbl_gamificado_puntos_consolidado WHERE rut=tbl_inscripcion_cierre.rut) / 5)
            )) AS valor
        FROM tbl_inscripcion_cierre
        INNER JOIN rel_lms_malla_persona ON rel_lms_malla_persona.rut=tbl_inscripcion_cierre.rut
        INNER JOIN tbl_usuario ON tbl_usuario.rut=tbl_inscripcion_cierre.rut
        WHERE id_malla=:id_malla AND tbl_inscripcion_cierre.id_empresa=:id_empresa AND tbl_inscripcion_cierre.rut=:rut
        GROUP BY tbl_inscripcion_cierre.rut
        ORDER BY valor DESC";
    $connexion->query($sql);
    $connexion->bind(':id_malla', $id_malla);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}
function MegustaPorComentarioReply($id_comentario)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_objeto_comentarios_reply_megusta WHERE id_comentario = :id_comentario";
    $connexion->query($sql);
    $connexion->bind(':id_comentario', $id_comentario);
    $cod = $connexion->resultset();
    return $cod;
}

function InsertarMegustaPorComentarioDeObjetoReply($id_comentario, $rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "INSERT INTO tbl_objeto_comentarios_reply_megusta (id_comentario, rut, id_empresa, fecha, hora) VALUES (:id_comentario, :rut, :id_empresa, :fecha, :hora)";
    $connexion->query($sql);
    $connexion->bind(':id_comentario', $id_comentario);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':fecha', date("Y-m-d"));
    $connexion->bind(':hora', date("H:i:s"));
    $connexion->execute();
}

function MegustaPorComentarioRutReply($id_comentario, $rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_objeto_comentarios_reply_megusta WHERE id_comentario = :id_comentario AND rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':id_comentario', $id_comentario);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}

function ObtenerUltimocomentarioIngresadoPorObjRut($id_objeto, $rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT MAX(id) AS ultimo FROM tbl_objeto_comentarios WHERE id_objeto = :id_objeto AND rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':id_objeto', $id_objeto);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}

function InsertarComentarioPorEnc2($id_empresa, $id_objeto, $rut, $id_encuesta, $comentario, $comentario2, $rut_relator)
{
    $connexion = new DatabasePDO();
    $sql = "INSERT INTO tbl_enc_satis_comentarios_finales (id_encuesta, id_objeto, id_empresa, rut, comentario, fecha, hora, comentario2, rut_relator) VALUES (:id_encuesta, :id_objeto, :id_empresa, :rut, :comentario, :fecha, :hora, :comentario2, :rut_relator)";
    $connexion->query($sql);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_objeto', $id_objeto);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':comentario', $comentario);
    $connexion->bind(':fecha', date("Y-m-d"));
    $connexion->bind(':hora', date("H:i:s"));
    $connexion->bind(':comentario2', $comentario2);
    $connexion->bind(':rut_relator', $rut_relator);
    $connexion->execute();
}
function InsertarComentarioPorEnc($id_empresa, $id_objeto, $rut, $id_encuesta, $comentario, $comentario2)
{
    $connexion = new DatabasePDO();
    $sql = "INSERT INTO tbl_enc_satis_comentarios_finales(id_encuesta, id_objeto, id_empresa, rut, comentario, fecha, hora, comentario2) " . "VALUES (:id_encuesta, :id_objeto, :id_empresa, :rut, :comentario, :fecha, :hora, :comentario2);";
    $connexion->query($sql);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_objeto', $id_objeto);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':comentario', $comentario);
    $connexion->bind(':fecha', date("Y-m-d"));
    $connexion->bind(':hora', date("H:i:s"));
    $connexion->bind(':comentario2', $comentario2);
    $connexion->execute();
}

function VerificoEncuestaFinalizadaConIDObjeto($id_encuesta, $id_empresa, $rut, $id_objeto)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_enc_satis_finalizados WHERE rut = :rut AND id_encuesta = :id_encuesta AND id_empresa = :id_empresa AND id_objeto = :id_objeto";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_objeto', $id_objeto);
    $cod = $connexion->resultset();
    return $cod;
}

function ObtenerCompromisosDadoIdObjeto($id_objeto, $id_empresa, $limit)
{
    $datos_empresa = DatosEmpresa($id_empresa);
    $connexion = new DatabasePDO();
    if ($limit == "") {
        $limit = " LIMIT 10";
    } else {
        $limit = " LIMIT $limit";
    }
    $sql = "SELECT tbl_objeto_compromisos.*, nombre_completo, tbl_usuario.rut, (SELECT count(*) as total FROM tbl_objeto_compromisos_megusta WHERE tbl_objeto_compromisos_megusta.id_compromiso=tbl_objeto_compromisos.id) as total_megusta
            FROM tbl_objeto_compromisos
            INNER JOIN tbl_usuario ON tbl_usuario.rut=tbl_objeto_compromisos.rut
            WHERE tbl_objeto_compromisos.id_objeto=:id_objeto
            ORDER BY total_megusta DESC, fecha, hora DESC $limit";
    $connexion->query($sql);
    $connexion->bind(':id_objeto', $id_objeto);
    $cod = $connexion->resultset();
    return $cod;
}

function MegustaPorCompromisoRut($id_compromiso, $rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_objeto_compromisos_megusta WHERE id_compromiso=:id_compromiso AND rut=:rut";
    $connexion->query($sql);
    $connexion->bind(':id_compromiso', $id_compromiso);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}
function MegustaPorCompromiso($id_compromiso)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_objeto_compromisos_megusta WHERE id_compromiso = :id_compromiso";
    $connexion->query($sql);
    $connexion->bind(':id_compromiso', $id_compromiso);
    $cod = $connexion->resultset();
    return $cod;
}

function InsertarMegustaPorCompromisoDeObjeto($id_compromiso, $rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "INSERT INTO tbl_objeto_compromisos_megusta(id_compromiso, rut, id_empresa, fecha, hora) " . "VALUES (:id_compromiso, :rut, :id_empresa, :fecha, :hora)";
    $connexion->query($sql);
    $connexion->bind(':id_compromiso', $id_compromiso);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':fecha', date("Y-m-d"));
    $connexion->bind(':hora', date("H:i:s"));
    $connexion->execute();
}

function CompromisosPorObjetosDatos($id_objeto)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT tbl_objeto_compromisos.*, tbl_usuario.nombre_completo FROM tbl_objeto_compromisos INNER JOIN tbl_usuario ON tbl_usuario.rut = tbl_objeto_compromisos.rut WHERE tbl_objeto_compromisos.id_objeto = :id_objeto";
    $connexion->query($sql);
    $connexion->bind(':id_objeto', $id_objeto);
    $cod = $connexion->resultset();
    return $cod;
}function InsertaCompromisoPorObjetoRut($rut, $id_objeto, $compromiso, $meta, $plan, $id_empresa, $pa1, $pc1, $pa2, $pc2, $pa3, $pc3)
{
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $hora  = date("H:i:s");
    $sql   = "INSERT INTO tbl_objeto_compromisos(id_objeto, rut, compromiso, meta, plan, fecha, hora, pa1, pc1, pa2, pc2, pa3, pc3) " . "VALUES (:id_objeto, :rut, :compromiso, :meta, :plan, :fecha, :hora, :pa1, :pc1, :pa2, :pc2, :pa3, :pc3);";
    $connexion->query($sql);
    $connexion->bind(':id_objeto', $id_objeto);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':compromiso', $compromiso);
    $connexion->bind(':meta', $meta);
    $connexion->bind(':plan', $plan);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':hora', $hora);
    $connexion->bind(':pa1', $pa1);
    $connexion->bind(':pc1', $pc1);
    $connexion->bind(':pa2', $pa2);
    $connexion->bind(':pc2', $pc2);
    $connexion->bind(':pa3', $pa3);
    $connexion->bind(':pc3', $pc3);
    $connexion->execute();
}

function ReplysPorComentarios($id_comentario)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT tbl_objeto_comentarios_respuestas.*, tbl_usuario.nombre_completo
            FROM tbl_objeto_comentarios_respuestas
            INNER JOIN tbl_usuario ON tbl_usuario.rut=tbl_objeto_comentarios_respuestas.rut
            WHERE id_comentario=:id_comentario";
    $connexion->query($sql);
    $connexion->bind(':id_comentario', $id_comentario);
    $cod = $connexion->resultset();
    return $cod;
}
function InsertaReplyComentarioPorObjeto($id_empresa, $rut, $comentario, $id_comentario)
{
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $hora  = date("H:i:s");
    $sql   = "INSERT INTO tbl_objeto_comentarios_respuestas(comentario, rut, id_comentario, id_empresa, fecha, hora) " . "VALUES (:comentario, :rut, :id_comentario, :id_empresa, :fecha, :hora);";
    $connexion->query($sql);
    $connexion->bind(':comentario', $comentario);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_comentario', $id_comentario);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':hora', $hora);
    $connexion->execute();
}

function DatosCursoTodos($id_curso)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_lms_curso WHERE id = :id_curso";
    $connexion->query($sql);
    $connexion->bind(':id_curso', $id_curso);
    $cod = $connexion->resultset();
    return $cod;
}

function DatosCursoTodos_2020($id_curso)
{
    $connexion = new DatabasePDO();
    $sql = "
        SELECT h.*, 
            (SELECT id_programa FROM rel_lms_malla_curso WHERE id_curso=h.id LIMIT 1) AS id_programa,
            (SELECT opcional FROM tbl_lms_programas_bbdd WHERE id_programa=(SELECT id_programa FROM rel_lms_malla_curso WHERE id_curso=h.id LIMIT 1) LIMIT 1) AS opcional_programa
        FROM tbl_lms_curso h    
        WHERE h.id = :id_curso";
    $connexion->query($sql);
    $connexion->bind(':id_curso', $id_curso);
    $cod = $connexion->resultset();
    return $cod;
}
function VerificaEstadoPlanesPorProcesoEmpresa($rut, $id_proceso_plan, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_planes_estados WHERE evaluado = :rut AND id_empresa = :id_empresa AND id_proceso_plan = :id_proceso_plan";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_proceso_plan', $id_proceso_plan);
    $cod = $connexion->resultset();
    return $cod;
}

function TotalMensajerPorObjetoMensajeria($id_objeto)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_mensajes_principal WHERE id_objeto = :id_objeto";
    $connexion->query($sql);
    $connexion->bind(':id_objeto', $id_objeto);
    $cod = $connexion->resultset();
    return $cod;
}

function TotalPreguntasDadoIdObjetoEmpresa($id_objeto)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*, (SELECT COUNT(id) FROM tbl_evaluaciones_preguntas WHERE evaluacion=h.id) as cuenta FROM tbl_evaluaciones h WHERE h.id_objeto = :id_objeto";
    $connexion->query($sql);
    $connexion->bind(':id_objeto', $id_objeto);
    $cod = $connexion->resultset();
    return $cod;
}

function TotalComentariosDadoIdObjetoEmpresa($id_objeto)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_objeto_comentarios WHERE id_objeto = :id_objeto";
    $connexion->query($sql);
    $connexion->bind(':id_objeto', $id_objeto);
    $cod = $connexion->resultset();
    return $cod;
}

function VerificoEncuestaFinalizada2($id_encuesta, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_enc_satis_finalizados WHERE id_encuesta = :id_encuesta AND id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}
function VerificoEncuestaFinalizadaCheckin($id_encuesta, $id_objeto, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_enc_satis_finalizados WHERE id_encuesta = :id_encuesta AND id_objeto = :id_objeto AND id_empresa = :id_empresa GROUP BY rut";
    $connexion->query($sql);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_objeto', $id_objeto);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function TodasFotosPorObjetoEmpresa($id_objeto, $id_empresa)
{
    $datos_empresa = DatosEmpresa($id_empresa);
    $connexion = new DatabasePDO();
    $limit = "";
    $sql = "SELECT tbl_galeria_archivos.*, tbl_usuario.nombre_completo
            FROM tbl_galeria_archivos
            INNER JOIN tbl_usuario ON tbl_usuario.rut = tbl_galeria_archivos.rut_autor
            WHERE tbl_galeria_archivos.id_objeto = :id_objeto
            AND tbl_galeria_archivos.id_empresa = :id_empresa
            AND tbl_galeria_archivos.estado IS NULL
            ORDER BY id DESC
            $limit";
    $connexion->query($sql);
    $connexion->bind(':id_objeto', $id_objeto);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function ListadoParticipantesDadoCursiEmpresaSinRelator($id_curso, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT tbl_CI_participantes.*, tbl_usuario.nombre_completo, tbl_usuario.cargo
            FROM tbl_CI_participantes
            INNER JOIN tbl_usuario ON tbl_usuario.rut = tbl_CI_participantes.rut
            WHERE tbl_CI_participantes.id_empresa = :id_empresa
            AND tbl_CI_participantes.id_curso = :id_curso
            AND tbl_CI_participantes.relator <> '1'";
    $connexion->query($sql);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function ListadoParticipantesDadoInscripcionEmpresaSinRelator($id_inscripcion, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "
    select tbl_inscripcion_usuarios.rut, tbl_inscripcion_curso.* , tbl_usuario.nombre_completo, tbl_usuario.cargo from tbl_inscripcion_curso

    inner join tbl_inscripcion_usuarios on tbl_inscripcion_curso.codigo_inscripcion=tbl_inscripcion_usuarios.id_inscripcion
    inner join tbl_usuario on tbl_usuario.rut=tbl_inscripcion_usuarios.rut
    inner join rel_lms_inscripcion_usuario_checkin
    on rel_lms_inscripcion_usuario_checkin.rut=tbl_inscripcion_usuarios.rut and rel_lms_inscripcion_usuario_checkin.codigo_imparticion=tbl_inscripcion_usuarios.id_inscripcion and
    r el_lms_inscripcion_usuario_checkin.id_empresa=tbl_inscripcion_usuarios.id_empresa

    where tbl_inscripcion_curso.id_empresa=:id_empresa and tbl_inscripcion_curso.codigo_inscripcion=:id_inscripcion and tbl_inscripcion_usuarios.relator<>'1'
    ";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_inscripcion', $id_inscripcion);
    $cod = $connexion->resultset();
    return $cod;
}

function IdCursoDadoObjeto($id_objeto)
{
    $connexion = new DatabasePDO();
    $sql = "select id_curso from tbl_objeto
    where id=:id_objeto";
    $connexion->query($sql);
    $connexion->bind(':id_objeto', $id_objeto);
    $cod = $connexion->resultset();
    return $cod[0]->id_curso;
}

function ENC_PromedioDeRespuestasPorPregunta($id_empresa, $id_encuesta, $id_pregunta)
{
    $connexion = new DatabasePDO();
    $sql = "select avg(respuesta) as promedio_pregunta from tbl_enc_satis_respuestas
    where id_empresa=:id_empresa and id_encuesta=:id_encuesta and id_pregunta=:id_pregunta";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_pregunta', $id_pregunta);
    $cod = $connexion->resultset();
    return $cod;
}
function ENC_CuentaDeRespuestasPorPregunta($id_empresa, $id_encuesta, $id_pregunta, $id_objeto)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT COUNT(h.id) AS cuenta,
            (SELECT COUNT(id) FROM tbl_enc_satis_respuestas WHERE id_empresa=:id_empresa AND id_encuesta=:id_encuesta AND id_objeto=:id_objeto AND id_pregunta=:id_pregunta AND respuesta='1') AS cuentarojo,
            (SELECT COUNT(id) FROM tbl_enc_satis_respuestas WHERE id_empresa=:id_empresa AND id_encuesta=:id_encuesta AND id_objeto=:id_objeto AND id_pregunta=:id_pregunta AND respuesta='3') AS cuentaamarillo,
            (SELECT COUNT(id) FROM tbl_enc_satis_respuestas WHERE id_empresa=:id_empresa AND id_encuesta=:id_encuesta AND id_objeto=:id_objeto AND id_pregunta=:id_pregunta AND respuesta='5') AS cuentaverde
            FROM tbl_enc_satis_respuestas h
            WHERE h.id_empresa=:id_empresa AND h.id_encuesta=:id_encuesta AND h.id_objeto=:id_objeto AND h.id_pregunta=:id_pregunta";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_objeto', $id_objeto);
    $connexion->bind(':id_pregunta', $id_pregunta);
    $cod = $connexion->resultset();
    return $cod;
}function ENC_CuentaDeRespuestasPorPregunta7op($id_empresa, $id_encuesta, $id_pregunta, $id_objeto)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT COUNT(h.id) AS cuenta,
           (SELECT COUNT(id) FROM tbl_enc_satis_respuestas WHERE id_empresa=:id_empresa AND id_encuesta=:id_encuesta AND id_objeto=:id_objeto AND id_pregunta=:id_pregunta AND respuesta='1') AS cuenta1,
           (SELECT COUNT(id) FROM tbl_enc_satis_respuestas WHERE id_empresa=:id_empresa AND id_encuesta=:id_encuesta AND id_objeto=:id_objeto AND id_pregunta=:id_pregunta AND respuesta='2') AS cuenta2,
           (SELECT COUNT(id) FROM tbl_enc_satis_respuestas WHERE id_empresa=:id_empresa AND id_encuesta=:id_encuesta AND id_objeto=:id_objeto AND id_pregunta=:id_pregunta AND respuesta='3') AS cuenta3,
           (SELECT COUNT(id) FROM tbl_enc_satis_respuestas WHERE id_empresa=:id_empresa AND id_encuesta=:id_encuesta AND id_objeto=:id_objeto AND id_pregunta=:id_pregunta AND respuesta='4') AS cuenta4,
           (SELECT COUNT(id) FROM tbl_enc_satis_respuestas WHERE id_empresa=:id_empresa AND id_encuesta=:id_encuesta AND id_objeto=:id_objeto AND id_pregunta=:id_pregunta AND respuesta='5') AS cuenta5,
           (SELECT COUNT(id) FROM tbl_enc_satis_respuestas WHERE id_empresa=:id_empresa AND id_encuesta=:id_encuesta AND id_objeto=:id_objeto AND id_pregunta=:id_pregunta AND respuesta='6') AS cuenta6,
           (SELECT COUNT(id) FROM tbl_enc_satis_respuestas WHERE id_empresa=:id_empresa AND id_encuesta=:id_encuesta AND id_objeto=:id_objeto AND id_pregunta=:id_pregunta AND respuesta='7') AS cuenta7,
           (SELECT COUNT(id) FROM tbl_enc_satis_respuestas WHERE id_empresa=:id_empresa AND id_encuesta=:id_encuesta AND id_objeto=:id_objeto AND id_pregunta=:id_pregunta) AS cuentatodas
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

function ENC_TraigoPreguntaDadaDimenion($id_dimension)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_enc_satis_preg WHERE iddimension = :id_dimension";
    $connexion->query($sql);
    $connexion->bind(':id_dimension', $id_dimension);
    $cod = $connexion->resultset();
    return $cod;
}

function ENC_TraigoDimensionesPorEncuesta($id_empresa, $id_encuesta)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_enc_satis_dim WHERE idencuesta = :id_encuesta AND id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $cod = $connexion->resultset();
    return $cod;
}

function ENC_EVI_TraigoDimensionesPorEncuestaPromedioDeRespuestasPorPregunta($id_empresa, $id_encuesta, $id_pregunta)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT AVG(respuesta) as promedio_pregunta FROM tbl_enc_evidencias_respuestas WHERE id_empresa = :id_empresa AND id_encuesta = :id_encuesta AND id_pregunta = :id_pregunta";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_pregunta', $id_pregunta);
    $cod = $connexion->resultset();
    return $cod;
}

function ENC_EVI_CuentaDeRespuestasPorPregunta($id_empresa, $id_encuesta, $id_pregunta, $id_objeto)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT COUNT(h.id) as cuenta,
            (SELECT COUNT(id) FROM tbl_enc_evidencias_respuestas WHERE id_empresa = :id_empresa AND id_encuesta = :id_encuesta AND id_objeto = :id_objeto AND id_pregunta = :id_pregunta AND respuesta = '1') AS cuentarojo,
            (SELECT COUNT(id) FROM tbl_enc_evidencias_respuestas WHERE id_empresa = :id_empresa AND id_encuesta = :id_encuesta AND id_objeto = :id_objeto AND id_pregunta = :id_pregunta AND respuesta = '3') AS cuentaamarillo,
            (SELECT COUNT(id) FROM tbl_enc_evidencias_respuestas WHERE id_empresa = :id_empresa AND id_encuesta = :id_encuesta AND id_objeto = :id_objeto AND id_pregunta = :id_pregunta AND respuesta = '5') AS cuentaverde
            FROM tbl_enc_evidencias_respuestas h
            WHERE h.id_empresa = :id_empresa AND h.id_encuesta = :id_encuesta AND h.id_objeto = :id_objeto AND h.id_pregunta = :id_pregunta";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_pregunta', $id_pregunta);
    $connexion->bind(':id_objeto', $id_objeto);
    $cod = $connexion->resultset();
    return $cod;
}
function ENC_EVI_CuentaDeRespuestasPorPregunta7op($id_empresa, $id_encuesta, $id_pregunta, $id_objeto)
{
    $connexion = new DatabasePDO();
    $sql = "select count(h.id) as cuenta,

     (select count(id) from tbl_enc_evidencias_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta and id_objeto=:id_objeto and id_pregunta=:id_pregunta and respuesta='1') as cuenta1,
     (select count(id) from tbl_enc_evidencias_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta and id_objeto=:id_objeto and id_pregunta=:id_pregunta and respuesta='2') as cuenta2,
     (select count(id) from tbl_enc_evidencias_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta and id_objeto=:id_objeto and id_pregunta=:id_pregunta) as cuentatodas

     from tbl_enc_evidencias_respuestas h

     where h.id_empresa=:id_empresa and h.id_encuesta=:id_encuesta and h.id_objeto=:id_objeto and h.id_pregunta=:id_pregunta";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_objeto', $id_objeto);
    $connexion->bind(':id_pregunta', $id_pregunta);
    $cod = $connexion->resultset();
    return $cod;
}

function ENC_EVI_TraigoPreguntaDadaDimenion($id_dimension)
{
    $connexion = new DatabasePDO();
    $sql = "select * from tbl_enc_evidencias_preg where iddimension=:id_dimension";
    $connexion->query($sql);
    $connexion->bind(':id_dimension', $id_dimension);
    $cod = $connexion->resultset();
    return $cod;
}

function ENC_EVI_TraigoDimensionesPorEncuesta($id_empresa, $id_encuesta)
{
    $connexion = new DatabasePDO();
    $sql = "select * from tbl_enc_evidencias_dim where idencuesta=:id_encuesta and id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function ENC_CuentaDeRespuestasPorPregunta_7opciones_Criterio($id_empresa, $id_encuesta, $id_pregunta, $array_objetos, $criterio)
{
    $connexion = new DatabasePDO();
    $cuenta_arreglo = count($array_objetos);
    if ($cuenta_arreglo == 1) {
        $id_objeto            = $array_objetos[0]->id;
        $query_arreglo_objeto = " and id_objeto='$id_objeto' ";
    }
    if ($cuenta_arreglo > 1) {
        $query_arreglo_objetoI = " and ( ";
        foreach ($array_objetos as $array_objeto) {
            $query_arreglo_objetoM .= " id_objeto='" . $array_objeto->id . "' OR ";
        }
        $query_arreglo_objetoF = " id<>id) ";
        $query_arreglo_objeto  = $query_arreglo_objetoI . $query_arreglo_objetoM . $query_arreglo_objetoF;
    }
    $sql = "select count(h.id) as cuenta,
            (select count(id) from tbl_enc_satis_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta $query_arreglo_objeto and id_pregunta=:id_pregunta and respuesta='1') as cuenta1,
            (select count(id) from tbl_enc_satis_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta $query_arreglo_objeto and id_pregunta=:id_pregunta and respuesta='2') as cuenta2,
            (select count(id) from tbl_enc_satis_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta $query_arreglo_objeto and id_pregunta=:id_pregunta and respuesta='3') as cuenta3,
            (select count(id) from tbl_enc_satis_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta $query_arreglo_objeto and id_pregunta=:id_pregunta and respuesta='4') as cuenta4,
            (select count(id) from tbl_enc_satis_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta $query_arreglo_objeto and id_pregunta=:id_pregunta and respuesta='5') as cuenta5,
            (select count(id) from tbl_enc_satis_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta $query_arreglo_objeto and id_pregunta=:id_pregunta and respuesta='6') as cuenta6,
            (select count(id) from tbl_enc_satis_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta $query_arreglo_objeto and id_pregunta=:id_pregunta and respuesta='7') as cuenta7,
            (select count(id) from tbl_enc_satis_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta $query_arreglo_objeto and id_pregunta=:id_pregunta) as cuentaPreg
            from tbl_enc_satis_respuestas h
            where h.id_empresa=:id_empresa
            and h.id_encuesta=:id_encuesta
            and h.id_objeto=:id_objeto
            and h.id_pregunta=:id_pregunta";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_pregunta', $id_pregunta);
    $connexion->bind(':id_objeto', $id_objeto);
    $cod = $connexion->resultset();
    return $cod;
}    
function ENC_CuentaDeRespuestasPorPregunta_7opciones_Promedio($id_empresa, $id_encuesta, $id_pregunta, $array_objetos, $criterio)
{
    $connexion = new DatabasePDO();
    $cuenta_arreglo = count($array_objetos);
    if ($cuenta_arreglo == 1) {
    $id_objeto = $array_objetos[0]->id;
    $query_arreglo_objeto = " and id_objeto='$id_objeto' ";
    }
    if ($cuenta_arreglo > 1) {
    $query_arreglo_objetoI = " and ( ";
    foreach ($array_objetos as $array_objeto) {
    $query_arreglo_objetoM .= " id_objeto='" . $array_objeto->id . "' OR ";
    }
    $query_arreglo_objetoF = " id<>id) ";
    $query_arreglo_objeto = $query_arreglo_objetoI . $query_arreglo_objetoM . $query_arreglo_objetoF;
    }
    $sql = "select count(h.id) as cuenta,
    (select avg(respuesta) as promedio from tbl_enc_satis_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta $query_arreglo_objeto and id_pregunta=:id_pregunta) as promedio
    from tbl_enc_satis_respuestas h
    where h.id_empresa=:id_empresa and h.id_encuesta=:id_encuesta and h.id_objeto=:id_objeto and h.id_pregunta=:id_pregunta";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_pregunta', $id_pregunta);
    $cod = $connexion->resultset();
    return $cod;
}

function ENC_EVI_CuentaDeRespuestasPorPregunta_7opciones_Criterio($id_empresa, $id_encuesta, $id_pregunta, $array_objetos, $criterio)
{
    $connexion = new DatabasePDO();

    $cuenta_arreglo = count($array_objetos);
    if ($cuenta_arreglo == 1) {
        $id_objeto            = $array_objetos[0]->id;
        $query_arreglo_objeto = " and id_objeto='$id_objeto' ";
    }
    if ($cuenta_arreglo > 1) {
        $query_arreglo_objetoI = " and ( ";
        foreach ($array_objetos as $array_objeto) {
            $query_arreglo_objetoM .= " id_objeto='" . $array_objeto->id . "' OR ";
        }
        $query_arreglo_objetoF = " id<>id) ";
        $query_arreglo_objeto  = $query_arreglo_objetoI . $query_arreglo_objetoM . $query_arreglo_objetoF;
    }
    $sql = "select count(h.id) as cuenta,
            (select count(id) from tbl_enc_evidencias_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta $query_arreglo_objeto and id_pregunta=:id_pregunta and resp_diag='1') as cuentaD1,
            (select count(id) from tbl_enc_evidencias_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta $query_arreglo_objeto and id_pregunta=:id_pregunta and resp_diag='2') as cuentaD2,
            (select count(id) from tbl_enc_evidencias_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta $query_arreglo_objeto and id_pregunta=:id_pregunta and resp_diag='3') as cuentaD3,
            (select count(id) from tbl_enc_evidencias_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta $query_arreglo_objeto and id_pregunta=:id_pregunta and resp_diag='4') as cuentaD4,
            (select count(id) from tbl_enc_evidencias_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta $query_arreglo_objeto and id_pregunta=:id_pregunta and resp_diag='5') as cuentaD5,
            (select count(id) from tbl_enc_evidencias_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta $query_arreglo_objeto and id_pregunta=:id_pregunta and resp_diag='6') as cuentaD6,
            (select count(id) from tbl_enc_evidencias_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta $query_arreglo_objeto and id_pregunta=:id_pregunta and resp_diag='7') as cuentaD7,
            (select count(id) from tbl_enc_evidencias_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta $query_arreglo_objeto and id_pregunta=:id_pregunta and resp_diag<>'') as cuentaD,

            
            (select count(id) from tbl_enc_evidencias_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta   $query_arreglo_objeto and id_pregunta=:id_pregunta and resp_experto='1') as cuentaE1,
            (select count(id) from tbl_enc_evidencias_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta   $query_arreglo_objeto and id_pregunta=:id_pregunta and resp_experto='2') as cuentaE2,
            (select count(id) from tbl_enc_evidencias_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta   $query_arreglo_objeto and id_pregunta=:id_pregunta and resp_experto='3') as cuentaE3,
            (select count(id) from tbl_enc_evidencias_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta   $query_arreglo_objeto and id_pregunta=:id_pregunta and resp_experto='4') as cuentaE4,
            (select count(id) from tbl_enc_evidencias_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta   $query_arreglo_objeto and id_pregunta=:id_pregunta and resp_experto='5') as cuentaE5,
            (select count(id) from tbl_enc_evidencias_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta   $query_arreglo_objeto and id_pregunta=:id_pregunta and resp_experto='6') as cuentaE6,
            (select count(id) from tbl_enc_evidencias_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta   $query_arreglo_objeto and id_pregunta=:id_pregunta and resp_experto='7') as cuentaE7,
            (select count(id) from tbl_enc_evidencias_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta   $query_arreglo_objeto and id_pregunta=:id_pregunta and resp_experto<>'') as cuentaE,


            (select count(id) from tbl_enc_evidencias_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta   $query_arreglo_objeto and id_pregunta=:id_pregunta and resp_comp='1') as cuentaC1,
            (select count(id) from tbl_enc_evidencias_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta   $query_arreglo_objeto and id_pregunta=:id_pregunta and resp_comp='2') as cuentaC2,
            (select count(id) from tbl_enc_evidencias_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta   $query_arreglo_objeto and id_pregunta=:id_pregunta and resp_comp='3') as cuentaC3,
            (select count(id) from tbl_enc_evidencias_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta   $query_arreglo_objeto and id_pregunta=:id_pregunta and resp_comp='4') as cuentaC4,
            (select count(id) from tbl_enc_evidencias_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta   $query_arreglo_objeto and id_pregunta=:id_pregunta and resp_comp='5') as cuentaC5,
            (select count(id) from tbl_enc_evidencias_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta   $query_arreglo_objeto and id_pregunta=:id_pregunta and resp_comp='6') as cuentaC6,
            (select count(id) from tbl_enc_evidencias_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta   $query_arreglo_objeto and id_pregunta=:id_pregunta and resp_comp='7') as cuentaC7,
            (select count(id) from tbl_enc_evidencias_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta   $query_arreglo_objeto and id_pregunta=:id_pregunta and resp_comp<>'') as cuentaC,


            (select count(id) from tbl_enc_evidencias_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta   $query_arreglo_objeto and id_pregunta=:id_pregunta and resp_jefe='2') as cuentaJ2,
            (select count(id) from tbl_enc_evidencias_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta   $query_arreglo_objeto and id_pregunta=:id_pregunta and resp_jefe='3') as cuentaJ3,
            (select count(id) from tbl_enc_evidencias_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta   $query_arreglo_objeto and id_pregunta=:id_pregunta and resp_jefe='4') as cuentaJ4,
            (select count(id) from tbl_enc_evidencias_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta   $query_arreglo_objeto and id_pregunta=:id_pregunta and resp_jefe='5') as cuentaJ5,
            (select count(id) from tbl_enc_evidencias_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta   $query_arreglo_objeto and id_pregunta=:id_pregunta and resp_jefe='6') as cuentaJ6,
            (select count(id) from tbl_enc_evidencias_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta   $query_arreglo_objeto and id_pregunta=:id_pregunta and resp_jefe='7') as cuentaJ7,
            (select count(id) from tbl_enc_evidencias_respuestas where id_empresa=:id_empresa and id_encuesta=:id_encuesta   $query_arreglo_objeto and id_pregunta=:id_pregunta and resp_jefe<>'') as cuentaD
            from tbl_enc_evidencias_respuestas h
            where h.id_empresa=:id_empresa
            and h.id_encuesta=:id_encuesta
            and h.id_objeto=:id_objeto
            and h.id_pregunta=:id_pregunta";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_objeto', $id_objeto);
    $connexion->bind(':id_pregunta', $id_pregunta);
    $cod = $connexion->resultset();
    return $cod;
}

function ENC_EVI_CuentaDeRespuestasPorPregunta_7opciones_Promedio($id_empresa, $id_encuesta, $id_pregunta, $array_objetos, $criterio)
{
    $connexion = new DatabasePDO();
    $cuenta_arreglo = count($array_objetos);
    if ($cuenta_arreglo == 1) {
        $id_objeto = $array_objetos[0]->id;
        $query_arreglo_objeto = " and id_objeto=:id_objeto";
        $connexion->bind(':id_objeto', $id_objeto);
    }
    if ($cuenta_arreglo > 1) {
        $query_arreglo_objetoI = " and ( ";
        $query_arreglo_objetoM = "";
        foreach ($array_objetos as $array_objeto) {
            $query_arreglo_objetoM .= " id_objeto=:id_objeto" . $array_objeto->id . " OR ";
            $connexion->bind(':id_objeto' . $array_objeto->id, $array_objeto->id);
        }
        $query_arreglo_objetoF = " id<>id) ";
        $query_arreglo_objeto  = $query_arreglo_objetoI . $query_arreglo_objetoM . $query_arreglo_objetoF;
    }
    $sql = "select count(h.id) as cuenta,
            (select avg(respuesta) as promedio 
             from tbl_enc_satis_respuestas 
             where id_empresa=:id_empresa 
             and id_encuesta=:id_encuesta 
             $query_arreglo_objeto 
             and id_pregunta=:id_pregunta) as promedio
            from tbl_enc_satis_respuestas h
            where h.id_empresa=:id_empresa
            and h.id_encuesta=:id_encuesta
            and h.id_objeto=:id_objeto
            and h.id_pregunta=:id_pregunta";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_pregunta', $id_pregunta);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod;
}
function TotalMensajesPorEmpresaCursoObjeto($id_empresa, $id_curso, $id_objeto)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT tbl_mensajes_principal.*, tbl_usuario.nombre AS nombre_creador,
            tbl_usuario.apaterno, tbl_usuario.amaterno, tbl_mensaje_tipo.nombre AS nombre_titulo,
            (SELECT count(*) AS total_respuestas
             FROM tbl_mensajes_respuestas
             WHERE id_mensaje = tbl_mensajes_principal.id) AS total_comen
            FROM tbl_mensajes_principal
            INNER JOIN tbl_usuario ON tbl_usuario.rut = tbl_mensajes_principal.rut_creador
            INNER JOIN tbl_mensaje_tipo ON tbl_mensaje_tipo.id = tbl_mensajes_principal.tipo_mensaje
            WHERE tbl_mensajes_principal.id_empresa = :id_empresa 
            AND tbl_mensajes_principal.id_curso = :id_curso 
            AND tbl_mensajes_principal.id_objeto = :id_objeto
            ORDER BY id DESC";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':id_objeto', $id_objeto);
    $cod = $connexion->resultset();
    return $cod;
}

function TotalMensajesPorRutEmpresaCursoObjeto($rut, $id_empresa, $id_curso, $id_objeto)
{
    $connexion = new DatabasePDO();
    $sql = "
     SELECT
     tbl_mensajes_principal.*, tbl_usuario.nombre AS nombre_creador,
     tbl_usuario.apaterno,
     tbl_usuario.amaterno,
     tbl_mensaje_tipo.nombre AS nombre_titulo,
     (
     SELECT
     count(*)AS total_respuestas
     FROM
     tbl_mensajes_respuestas
     WHERE
     id_mensaje = tbl_mensajes_principal.id
     )AS total_comen
from
     tbl_mensajes_principal
inner JOIN tbl_usuario ON tbl_usuario.rut = tbl_mensajes_principal.rut_creador
inner JOIN tbl_mensaje_tipo ON tbl_mensaje_tipo.id = tbl_mensajes_principal.tipo_mensaje
where
     tbl_mensajes_principal.rut_creador = :rut
AND tbl_mensajes_principal.id_empresa = :id_empresa and tbl_mensajes_principal.id_curso=:id_curso and tbl_mensajes_principal.id_objeto=:id_objeto
order BY
     id DESC";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':id_objeto', $id_objeto);
    $cod = $connexion->resultset();
    return $cod;
}
function MPInsertaNuevaCategoriaMP($id_empresa, $categoria, $id_categoria)
{
    $connexion = new DatabasePDO();
    $sql = "INSERT INTO tbl_mp_categorias(id_empresa, id_categoria, categoria, muestra) VALUES (:id_empresa, :id_categoria, :categoria, 1)";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_categoria', $id_categoria);
    $connexion->bind(':categoria', $categoria);
    $connexion->execute();
}

function MPObtengoUltimaCategoriaEnLaBase($id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_mp_categorias WHERE id_empresa = :id_empresa ORDER BY id_categoria DESC LIMIT 1";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function TraeCategoriasMejoresPracticasParaCombo($id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_mp_categorias WHERE id_empresa = :id_empresa AND muestra = 0 AND nombre <> '' ORDER BY categoria ASC";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function TraePlanesValidadosPorProcesoPlan($evaluado, $id_proceso_plan)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT tbl_planes_ingresados.*, tbl_sgd_componente.nombre AS nombre_competencia, tbl_planes_plazos.nombre AS plazo_nombre
            FROM tbl_planes_ingresados
            INNER JOIN tbl_sgd_componente ON tbl_sgd_componente.id = tbl_planes_ingresados.id_competencia
            INNER JOIN tbl_planes_plazos ON tbl_planes_plazos.id = tbl_planes_ingresados.plazo
            WHERE evaluado = :evaluado AND tbl_planes_ingresados.id_proceso = :id_proceso_plan AND jefe_sugiere IS NOT NULL 
            ORDER BY tbl_planes_ingresados.id DESC";
    $connexion->query($sql);
    $connexion->bind(':evaluado', $evaluado);
    $connexion->bind(':id_proceso_plan', $id_proceso_plan);
    $cod = $connexion->resultset();
    return $cod;
}
function TraeDatosPorPlanesPorProcesoPlan($evaluado, $id_proceso_plan)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT tbl_planes_ingresados.*, tbl_sgd_componente.nombre as nombre_competencia, tbl_planes_plazos.nombre as plazo_nombre
     FROM tbl_planes_ingresados
     INNER JOIN tbl_sgd_componente
     ON tbl_sgd_componente.id=tbl_planes_ingresados.id_competencia
     INNER JOIN tbl_planes_plazos
     ON tbl_planes_plazos.id=tbl_planes_ingresados.plazo
     WHERE evaluado=:evaluado AND tbl_planes_ingresados.id_proceso=:id_proceso_plan ORDER BY tbl_planes_ingresados.id DESC";
    $connexion->query($sql);
    $connexion->bind(':evaluado', $evaluado);
    $connexion->bind(':id_proceso_plan', $id_proceso_plan);
    $cod = $connexion->resultset();
    return $cod;
}

function ListadoParticipantesDadoCursiEmpresa($id_curso, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT tbl_CI_participantes.*, tbl_usuario.nombre_completo, tbl_usuario.cargo
     FROM tbl_CI_participantes
     INNER JOIN tbl_usuario
     ON tbl_usuario.rut=tbl_CI_participantes.rut
     WHERE tbl_CI_participantes.id_empresa=:id_empresa AND tbl_CI_participantes.id_curso=:id_curso";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_curso', $id_curso);
    $cod = $connexion->resultset();
    return $cod;
}

function TraeObjetosDadoCursoCheck($id_curso, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_objeto WHERE id_empresa=:id_empresa AND id_curso=:id_curso";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_curso', $id_curso);
    $cod = $connexion->resultset();
    return $cod;
}

function InsertaRegistroMallaPersona($id_malla, $rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT COUNT(id) AS cuenta FROM rel_lms_malla_persona WHERE id_empresa=:id_empresa AND rut=:rut AND id_malla=:id_malla";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_malla', $id_malla);
    $cod    = $connexion->resultset();
    $cuenta = $cod[0]->cuenta;
    if ($cuenta == '0') {
        $sql = "INSERT INTO rel_lms_malla_persona(id_malla, rut, id_empresa) VALUES (:id_malla, :rut, :id_empresa)";
        $connexion->query($sql);
        $connexion->bind(':id_malla', $id_malla);
        $connexion->bind(':rut', $rut);
        $connexion->bind(':id_empresa', $id_empresa);
        $connexion->execute();
    }
}
function TraeDatosPorPlanesPorProceso($evaluado, $id_proceso)
{
    $connexion = new DatabasePDO();
    $sql = "select tbl_planes_ingresados.*, tbl_sgd_componente.nombre as nombre_competencia, tbl_planes_plazos.nombre as plazo_nombre
            from tbl_planes_ingresados
            inner join tbl_sgd_componente
            on tbl_sgd_componente.id=tbl_planes_ingresados.id_competencia
            inner join tbl_planes_plazos
            on tbl_planes_plazos.id=.tbl_planes_ingresados.plazo
            where evaluado=:evaluado and tbl_planes_ingresados.id_proceso_evaluacion=:id_proceso order by tbl_planes_ingresados.id desc";
    $connexion->query($sql);
    $connexion->bind(':evaluado', $evaluado);
    $connexion->bind(':id_proceso', $id_proceso);
    $cod = $connexion->resultset();
    return $cod;
}

function MPOtrasPracticas($id_mp, $tipo, $id_empresa, $limit)
{
    $connexion = new DatabasePDO();
    $sql = "select h.* from tbl_mp h where h.id_mp=:id_mp and id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_mp', $id_mp);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod1 = $connexion->resultset();
    $id_cat = $cod1[0]->id_categoria;
    $id_amb = $cod1[0]->id_ambito;
    $query_ord = "
        ORDER BY
        (1+(select count(id) from tbl_mp_interacciones where id_mp=h.id_mp and tipo='MEGUSTA')) *(1+(select count(id) from tbl_mp_interacciones where id_mp=h.id_mp and tipo='FAVORITA'))
        DESC
        limit $limit
        ";
    if ($tipo == "cat") {
        $sql = "select h.* from tbl_mp h where h.id_categoria=:id_cat and id_empresa=:id_empresa and id_estado>=5 $query_ord";
        $connexion->query($sql);
        $connexion->bind(':id_cat', $id_cat);
        $connexion->bind(':id_empresa', $id_empresa);
    }
    if ($tipo == "amb") {
        $sql = "select h.* from tbl_mp h where h.id_ambito=:id_amb and id_empresa=:id_empresa and id_estado>=5 $query_ord";
        $connexion->query($sql);
        $connexion->bind(':id_amb', $id_amb);
        $connexion->bind(':id_empresa', $id_empresa);
    }
    $cod = $connexion->resultset();
    return $cod;
}
function MPPreguntas($id_empresa, $limit)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.* FROM tbl_mp h WHERE h.rut IS NULL AND id_empresa = :id_empresa AND h.pregunta <> '' AND h.autor_pregunta <> '' ORDER BY fecha DESC, id DESC LIMIT :limit";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':limit', $limit, PDO::PARAM_INT);
    $cod = $connexion->resultset();
    return $cod;
}

function DatosMP($id_empresa, $id_mp)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.* FROM tbl_mp h WHERE h.id_mp = :id_mp AND h.id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_mp', $id_mp);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function DatosIdPostComment($id_post)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.* FROM tbl_mp_comentarios h WHERE h.id = :id_post";
    $connexion->query($sql);
    $connexion->bind(':id_post', $id_post);
    $cod = $connexion->resultset();
    return $cod;
}

function MP_buscapregunta($id_empresa, $id_mp)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*, (SELECT nombre_completo FROM tbl_usuario WHERE rut = h.autor_pregunta) AS nombrecompleto FROM tbl_mp h WHERE id_mp = :id_mp AND id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_mp', $id_mp);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function MPActualizaEstado($id_mp, $estado)
{
    $connexion = new DatabasePDO();
    $sql = "UPDATE tbl_mp SET id_estado = :estado WHERE id_mp = :id_mp";
    $connexion->query($sql);
    $connexion->bind(':estado', $estado);
    $connexion->bind(':id_mp', $id_mp);
    $connexion->execute();
}
function MPEditaAdmin($id_mp, $id_empresa, $titulo, $contenido)
{
    $connexion = new DatabasePDO();
    $sql = "UPDATE tbl_mp SET nombre=:titulo, contenido=:contenido WHERE id_mp=:id_mp AND id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':titulo', $titulo);
    $connexion->bind(':contenido', $contenido);
    $connexion->bind(':id_mp', $id_mp);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->execute();
}

function TraeTodasPracticas($id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*, 
    (SELECT nombre_completo FROM tbl_usuario WHERE rut=h.rut) AS nombreautor, 
    (SELECT categoria FROM tbl_mp_categorias WHERE id_categoria=h.id_categoria) AS categoria, 
    (SELECT estado FROM tbl_mp_estados WHERE id_estado=h.id_estado) AS estado, 
    (SELECT COUNT(id) FROM tbl_mp_interacciones WHERE id_mp=h.id_mp AND id_empresa=:id_empresa AND tipo='VISITA') AS numvisitas, 
    (SELECT COUNT(id) FROM tbl_mp_interacciones WHERE id_mp=h.id_mp AND id_empresa=:id_empresa AND tipo='MEGUSTA') AS nummegusta, 
    (SELECT COUNT(id) FROM tbl_mp_interacciones WHERE id_mp=h.id_mp AND id_empresa=:id_empresa AND tipo='FAVORITA') AS numfavorita, 
    (SELECT COUNT(id) FROM tbl_mp_interacciones WHERE id_mp=h.id_mp AND id_empresa=:id_empresa AND tipo='COMENTARIO') AS numcolaboraciones 
    FROM tbl_mp h WHERE h.id_empresa=:id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}

function MPTotalInteraccionesPorMP($id_mp, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT tbl_mp_interacciones.*, nombre_completo FROM tbl_mp_interacciones INNER JOIN tbl_usuario ON tbl_usuario.rut=tbl_mp_interacciones.rut WHERE tbl_mp_interacciones.id_empresa=:id_empresa AND tbl_mp_interacciones.id_mp=:id_mp AND tbl_mp_interacciones.tipo='COMENTARIO' ORDER BY id DESC";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_mp', $id_mp);
    $cod = $connexion->resultset();
    return $cod;
}

function MPInsertaComentario($id_mp, $rut, $id_empresa, $tipo, $contenido)
{
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $hora = date("H:i:s");
    $sql = "INSERT INTO tbl_mp_interacciones(id_mp, rut, id_empresa, tipo, fecha, contenido) VALUES (:id_mp, :rut, :id_empresa, :tipo, :fecha, :contenido)";
    $connexion->query($sql);
    $connexion->bind(':id_mp', $id_mp);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':tipo', $tipo);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':contenido', $contenido);
    $connexion->execute();
}
function MPInserVistaPr($id_mp, $id_empresa, $rut)
{
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $hora  = date("H:i:s");
    $sql   = "INSERT INTO tbl_mp_interacciones(id_mp, rut, id_empresa, fecha, tipo) " . "VALUES (:id_mp, :rut, :id_empresa, :fecha, 'VISITA');";
    $connexion->query($sql);
    $connexion->bind(':id_mp', $id_mp);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':fecha', $fecha);
    $connexion->execute();
}

function MPInserDescargaPr($id_mp, $id_empresa, $rut)
{
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $hora  = date("H:i:s");
    $sql   = "INSERT INTO tbl_mp_interacciones(id_mp, rut, id_empresa, fecha, tipo) " . "VALUES (:id_mp, :rut, :id_empresa, :fecha, 'DESCARGA');";
    $connexion->query($sql);
    $connexion->bind(':id_mp', $id_mp);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':fecha', $fecha);
    $connexion->execute();
}

function MPInsertaRegistroMegusta($id_mp, $rut, $id_empresa, $tipo)
{
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $hora  = date("H:i:s");
    $sql   = "INSERT INTO tbl_mp_interacciones(id_mp, rut, id_empresa, tipo, fecha) " . "VALUES (:id_mp, :rut, :id_empresa, :tipo, :fecha);";
    $connexion->query($sql);
    $connexion->bind(':id_mp', $id_mp);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':tipo', $tipo);
    $connexion->bind(':fecha', $fecha);
    $connexion->execute();
}

function BuscaFechaInscripcionUsuario($rut, $id_malla){
    $connexion = new DatabasePDO();
    $sql = "
     SELECT fecha from tbl_inscripcion_usuarios where rut=:rut and id_malla=:id_malla and fecha<>'' limit 1";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_malla', $id_malla);
    $connexion->execute();
    $cod = $connexion->resultset();
    return $cod[0]->fecha;
}

function InsertaDatosMejoresPracticasCompleto($rut, $id_empresa, $id_categoria, $id_ambito, $nombre, $contenido, $tag, $archivo, $objetivo, $aplicacion, $metrica, $impacto, $id_subcategoria, $problema, $kpi_afectado, $cuantificacion, $coach, $nombre_archivo2)
{
    $connexion = new DatabasePDO();
    if ($id_empresa == 53) {
        $ultimo_id = ObtengoUltimoRegistroMP($id_empresa);
        $nuevo_id  = $ultimo_id[0]->id;
        $nuevo_id  = $nuevo_id + 1;
        $codigo    = $id_empresa . "_mp" . $nuevo_id;
        $sql       = "INSERT INTO tbl_mp(id_mp, rut, id_empresa, fecha, id_estado, id_categoria, id_ambito, nombre, contenido,  archivo, objetivo, aplicacion, metrica, impacto, id_subcategoria, problema, kpi_afectado, cuantificacion, coach, archivo2) " . "VALUES ('$codigo', '$rut', '$id_empresa', '" . date("Y-m-d") . "', '1','$id_categoria','$id_ambito', '$nombre', '$contenido', '$archivo', '$objetivo', '$aplicacion' , '$metrica' , '$impacto', '$id_subcategoria', '$problema', '$kpi_afectado', '$cuantificacion', '$coach', '$nombre_archivo2' );";
        $connexion->query($sql);
        $connexion->execute();
        $sql = "INSERT INTO tbl_mp_historicos(id_mp, rut, id_empresa, fecha, id_estado, id_categoria, id_ambito, nombre, contenido,  archivo, objetivo, aplicacion, metrica, impacto, id_subcategoria, problema, kpi_afectado, cuantificacion, coach, archivo2) " . "VALUES ('$codigo', '$rut', '$id_empresa', '" . date("Y-m-d") . "', '1','$id_categoria','$id_ambito', '$nombre', '$contenido',  '$archivo', '$objetivo' , '$aplicacion' , '$metrica' , '$impacto', '$id_subcategoria', '$problema', '$kpi_afectado', '$cuantificacion', '$coach', '$nombre_archivo2' );";
        $connexion->query($sql);
        $connexion->execute();
        return ($codigo);
    } else {
        $ultimo_id = ObtengoUltimoRegistroMP($id_empresa);
        $nuevo_id  = $ultimo_id[0]->id;
        $nuevo_id  = $nuevo_id + 1;
        $sql       = "INSERT INTO tbl_mp(id_mp, rut, id_empresa, fecha, id_estado, id_categoria, id_ambito, nombre, contenido,  archivo, objetivo, aplicacion, metrica, impacto, id_subcategoria, problema, kpi_afectado, cuantificacion, coach) " . "VALUES ('bci_mp_" . $id_empresa . "_" . $nuevo_id . "', '$rut', '$id_empresa', '" . date("Y-m-d") . "', '1','$id_categoria','$id_ambito', '$nombre', '$contenido', '$archivo', '$objetivo', '$aplicacion' , '$metrica' , '$impacto', '$id_subcategoria', '$problema', '$kpi_afectado', '$cuantificacion', '$coach' );";
        $connexion->query($sql);
        $connexion->execute();
        $sql = "INSERT INTO tbl_mp_historicos(id_mp, rut, id_empresa, fecha, id_estado, id_categoria, id_ambito, nombre, contenido,  archivo, objetivo, aplicacion, metrica, impacto, id_subcategoria, problema, kpi_afectado, cuantificacion, coach) " . "VALUES ('bci_mp_" . $id_empresa . "_" . $nuevo_id . "', '$rut', '$id_empresa', '" . date("Y-m-d") . "', '1','$id_categoria','$id_ambito', '$nombre', '$contenido',  '$archivo', '$objetivo' , '$aplicacion' , '$metrica' , '$impacto', '$id_subcategoria', '$problema', '$kpi_afectado', '$cuantificacion', '$coach' );";
        $connexion->query($sql);
        $connexion->execute();
        return ("bci_mp_" . $nuevo_id);
    }
}

function InsertaDatosMejoresPracticasPreguntaCompleto($rut, $id_empresa, $nombre)
{
    $connexion = new DatabasePDO();
    $ultimo_id = ObtengoUltimoRegistroMP($id_empresa);
    $nuevo_id  = $ultimo_id[0]->id;
    $nuevo_id  = $nuevo_id + 1;
    $sql       = "INSERT INTO tbl_mp(id_mp, autor_pregunta, id_empresa, fecha, id_estado,  pregunta) " . "VALUES ('bci_mp_" . $nuevo_id . "', :rut, :id_empresa, '" . date("Y-m-d") . "', '0', :nombre );";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':nombre', $nombre);
    $connexion->execute();

    $sql = "INSERT INTO tbl_mp_historicos(id_mp, autor_pregunta, id_empresa, fecha, id_estado,  pregunta) " . "VALUES ('bci_mp_" . $nuevo_id . "', :rut, :id_empresa, '" . date("Y-m-d") . "', '0', :nombre );";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':nombre', $nombre);
    $connexion->execute();

    return ("bci_mp_" . $nuevo_id);
}

function TraeEvaluadosDadoEvaluadoSinMiPerfil($rut)
{
    $connexion = new DatabasePDO();
    $sql = "
     SELECT
     evaluado AS rut,
     tbl_sgd_relaciones.evaluador, perfil_evaluacion_competencias as perfil_evaluacion, id_proceso
from
     tbl_sgd_relaciones
inner JOIN tbl_usuario ON tbl_usuario.rut = evaluado
where
     tbl_sgd_relaciones.evaluado = :rut
AND tbl_sgd_relaciones.evaluado <> tbl_sgd_relaciones.evaluador
order by nombre_completo asc
     ";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}

function UsuarioEnBasePersonasConRelacionEvaluadoEvaluadorDadoEvaluado($evaluado, $id_proceso)
{
    $connexion = new DatabasePDO();
    $sql = "
     SELECT
     DISTINCT(
     tbl_sgd_relaciones.perfil_evaluacion_competencias
     )AS perfil_evaluacion,


     rut,
     nombre,
     apaterno,
     amaterno,
     nombre_completo,
     cargo,
     gerencia,
     tbl_usuario.id_empresa,
     id_area,
     tbl_sgd_relaciones.evaluador
from
     tbl_usuario
inner JOIN tbl_sgd_relaciones
on tbl_sgd_relaciones.evaluado = :evaluado
AND tbl_sgd_relaciones.id_proceso = :id_proceso
where
     rut = :evaluado
     ";

    $connexion->query($sql);
    $connexion->bind(':evaluado', $evaluado);
    $connexion->bind(':id_proceso', $id_proceso);
    $cod = $connexion->resultset();
    return $cod;
}
function UsuarioTieneRespuestaPorPregunta($id_pregunta, $rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_concurso_respuesta WHERE id_pregunta = :id_pregunta AND rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':id_pregunta', $id_pregunta);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}

function InsertaRespuestaPreguntaConcurso($rut, $respuesta, $id_pregunta)
{
    $connexion = new DatabasePDO();
    $sql = "INSERT INTO tbl_concurso_respuesta (respuesta, id_pregunta, fecha, hora, rut) VALUES (:respuesta, :id_pregunta, :fecha, '', :rut)";
    $connexion->query($sql);
    $connexion->bind(':respuesta', $respuesta);
    $connexion->bind(':id_pregunta', $id_pregunta);
    $connexion->bind(':fecha', date("Y-m-d"));
    $connexion->bind(':rut', $rut);
    $connexion->execute();
}
function TraePreguntaActivaConcurso($id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_concurso_pregunta WHERE id_empresa = :id_empresa AND activo = '1'";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}
function Vota_Reconocimiento_Interacciones($id_empresa, $tipo_vota, $limit)
{
    $connexion = new DatabasePDO();

    if ($tipo_vota == "REC_RECIENTES") {
        $sql = "SELECT h.*, SUM(h.puntos) AS cuenta 
                FROM tbl_mp_interacciones h 
                WHERE id_empresa=:id_empresa 
                GROUP BY rut 
                ORDER BY SUM(h.puntos) DESC 
                LIMIT :limit";
        $connexion->query($sql);
        $connexion->bind(':id_empresa', $id_empresa);
        $connexion->bind(':limit', $limit, PDO::PARAM_INT);
    }
    if ($tipo_vota == "REC_MAS_AGRACIADOS") {
        $sql = "SELECT h.*, SUM(h.puntos) AS cuenta 
                FROM tbl_mp_interacciones h 
                WHERE id_empresa=:id_empresa 
                GROUP BY rut 
                ORDER BY SUM(h.puntos) DESC 
                LIMIT :limit";
        $connexion->query($sql);
        $connexion->bind(':id_empresa', $id_empresa);
        $connexion->bind(':limit', $limit, PDO::PARAM_INT);
    }
    if ($tipo_vota == "REC_MR_MRS_GRACIAS") {
        $sql = "SELECT h.*, SUM(h.puntos) AS cuenta 
                FROM tbl_mp_interacciones h 
                WHERE id_empresa=:id_empresa AND h.rut <> '' 
                GROUP BY rut 
                ORDER BY h.puntos * SUM(h.puntos) * (SELECT COUNT(id) FROM tbl_mp_interacciones WHERE rut_remitente=h.rut AND id_empresa=h.id_empresa) DESC 
                LIMIT :limit";
            $connexion->query($sql);
            $connexion->bind(':id_empresa', $id_empresa);
            $connexion->bind(':limit', $limit, PDO::PARAM_INT);
    }
    if ($tipo_vota == "REC_MAS_RECONOCEDORES") {
        $sql = "SELECT h.*, COUNT(id) AS cuenta 
                FROM tbl_mp_interacciones h 
                WHERE id_empresa=:id_empresa 
                GROUP BY rut 
                ORDER BY (SELECT COUNT(id) FROM tbl_mp_interacciones WHERE rut_remitente=h.rut AND id_empresa=h.id_empresa) DESC 
                LIMIT :limit";
        $connexion->query($sql);
        $connexion->bind(':id_empresa', $id_empresa);
        $connexion->bind(':limit', $limit, PDO::PARAM_INT);
    }
    if ($tipo_vota == "REC_VALORES") {
        $sql = "SELECT h.*, 
                       (SELECT COUNT(id) FROM tbl_mp_interacciones WHERE categoria=h.categoria) AS cuenta,
                       (SELECT COUNT(id) FROM tbl_mp_interacciones WHERE id_empresa=:id_empresa),
                       ROUND(100*(SELECT COUNT(id) FROM tbl_mp_interacciones WHERE categoria=h.categoria)/(SELECT COUNT(id) FROM tbl_mp_interacciones WHERE id_empresa=:id_empresa)) AS porcentaje
                FROM tbl_mp_interacciones h 
                WHERE id_empresa=:id_empresa 
                GROUP BY h.categoria
                ORDER BY (SELECT COUNT(id) FROM tbl_mp_interacciones WHERE categoria=h.categoria) DESC";
        $connexion->query($sql);
        $connexion->bind(':id_empresa', $id_empresa);
       
    }
    if ($tipo_mas == "MASAPOYADOR") {
        $sql = "SELECT h.*, COUNT(id) AS cuenta 
                FROM tbl_mp_interacciones h 
                WHERE id_empresa=:id_empresa AND (tipo='MEGUSTA' OR tipo='FAVORITA') 
                GROUP BY rut 
                ORDER BY COUNT(id) DESC 
                LIMIT :limit";
        $connexion->query($sql);
        $connexion->bind(':id_empresa', $id_empresa);
        $connexion->bind(':limit', $limit, PDO::PARAM_INT);
    }
    if ($tipo_mas == "MASCOLABORATIVO") {
        $sql = "SELECT h.*, COUNT(id) AS cuenta 
                FROM tbl_mp_interacciones h 
                WHERE id_empresa=:id_empresa AND tipo='COMENTARIO' 
                GROUP BY rut 
                ORDER BY COUNT(id) DESC 
                LIMIT :limit";
        $connexion->query($sql);
        $connexion->bind(':id_empresa', $id_empresa);
        $connexion->bind(':limit', $limit, PDO::PARAM_INT);
    }
    if ($tipo_mas == "MASPROPOSITIVO") {
        $sql = "SELECT h.rut, COUNT(id) AS cuenta 
                FROM tbl_mp h 
                WHERE id_empresa=:id_empresa AND (id_estado>=5) 
                GROUP BY rut 
                ORDER BY COUNT(id) DESC 
                LIMIT :limit";
        $connexion->query($sql);
        $connexion->bind(':id_empresa', $id_empresa);
        $connexion->bind(':limit', $limit, PDO::PARAM_INT);
    }
    if ($tipo_vota == "MASMASTER") {
        $sql = "SELECT h.rut, COUNT(id) AS cuenta FROM tbl_mp h WHERE id_empresa=:id_empresa AND (id_estado>=5) GROUP BY rut ORDER BY COUNT(id) DESC LIMIT :limit";
        $connexion->query($sql);
        $connexion->bind(':id_empresa', $id_empresa);
        $connexion->bind(':limit', $limit, PDO::PARAM_INT);
    }
    $cod = $connexion->resultset();
    return $cod;
}
function DistinctCampoUsuario($id_empresa, $campo)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT DISTINCT $campo as valor FROM tbl_usuario WHERE id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}
function Com_Premios_Detalle($segmento, $idi, $id_empresa){

    //echo "$segmento, $idi, $id_empresa";
    $connexion = new DatabasePDO();
    //$segmento=utf8_encode($segmento);
    //$idi=Decodear3($idi);
    $sql = "SELECT h.*, (SELECT foto FROM tbl_premios_dimension WHERE id_dimension = h.id_dimension LIMIT 1) as foto
    FROM tbl_premios h WHERE h.id_premio = :idi AND h.id_empresa = :id_empresa AND h.segmento = :segmento";

    //echo $sql;
    $connexion->query($sql);
    $connexion->bind(':idi', $idi);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':segmento', $segmento);
    $cod = $connexion->resultset();
    return $cod;
}
function EvaluadosDadoEvaluadorParaBitacoraPorProceso($evaluador, $id_proceso)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT tbl_sgd_relaciones.*, tbl_sgd_subperfiles.perfil AS nombre_subperfil, tbl_usuario.perfil_evaluacion
    FROM tbl_sgd_relaciones
    LEFT JOIN tbl_sgd_subperfiles ON tbl_sgd_subperfiles.id = tbl_sgd_relaciones.subperfil
    INNER JOIN tbl_usuario ON tbl_usuario.rut = tbl_sgd_relaciones.evaluado
    WHERE tbl_sgd_relaciones.evaluador = :evaluador AND tbl_sgd_relaciones.id_proceso = :id_proceso
    ORDER BY subperfil, tbl_sgd_relaciones.evaluado ASC";
    $connexion->query($sql);
    $connexion->bind(':evaluador', $evaluador);
    $connexion->bind(':id_proceso', $id_proceso);
    $cod = $connexion->resultset();
    return $cod;
}
function ObtengoUltimoProcesoEvaluacionDadoEmpresa($id_empresa)
{
    $datos_empresa = DatosEmpresa($id_empresa);
    $connexion = new DatabasePDO();
    $sql = "SELECT
     tbl_sgd_proceso_evaluacion.*
from
     tbl_sgd_proceso_evaluacion


inner join tbl_sgd_proceso_anual
on tbl_sgd_proceso_evaluacion.id_proceso_anual=tbl_sgd_proceso_anual.id
where
     tbl_sgd_proceso_anual.id_empresa = :id_empresa
AND tbl_sgd_proceso_anual.activo = '1'
AND (tbl_sgd_proceso_evaluacion.tipo_proceso='1' or tbl_sgd_proceso_evaluacion.tipo_proceso='2') order by tbl_sgd_proceso_evaluacion.fecha_inicio desc limit 1";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}
function ProcesosEvalPorEmpresaYTipoEvaluacion($id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "select tbl_sgd_proceso_evaluacion.*, tbl_sgd_proceso_anual.ano
     from tbl_sgd_proceso_evaluacion
     inner join tbl_sgd_proceso_anual
     on tbl_sgd_proceso_anual.id=tbl_sgd_proceso_evaluacion.id_proceso_anual
     where
     tbl_sgd_proceso_evaluacion.tipo_proceso='1' and
     tbl_sgd_proceso_evaluacion.id_empresa=:id_empresa
     order by tbl_sgd_proceso_anual.ano desc";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}
function GAMIFICACIONTotalPuntosPorObjetoDadoRutEmpresa($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT
     tbl_gamificado_puntos.*, tbl_objeto.titulo, baseautormegusta.nombre_completo as nombre_autor_megusta
from
     tbl_gamificado_puntos


left join tbl_objeto
on tbl_objeto.id=tbl_gamificado_puntos.id_objeto


left join tbl_usuario as baseautormegusta
on baseautormegusta.rut=tbl_gamificado_puntos.rut_autor_megusta


where
     tbl_gamificado_puntos.rut = :rut
AND tbl_gamificado_puntos.id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}
function ActualizoAvatar($rut, $avatar)
{
    $connexion = new DatabasePDO();
    $sql = "update tbl_usuario
     set avatar=:avatar where rut=:rut";
    $connexion->query($sql);
    $connexion->bind(':avatar', $avatar);
    $connexion->bind(':rut', $rut);
    $connexion->execute();
}
function ObtenerDatosAvatar($id_avatar)
{
    $connexion = new DatabasePDO();
    $sql = "select * from tbl_avatar where id=:id_avatar";
    $connexion->query($sql);
    $connexion->bind(':id_avatar', $id_avatar);
    $cod = $connexion->resultset();
    return $cod;
}
function TotalReconocimientosPorCategoriaGenericoPorAno($id_categoria, $id_empresa, $ano)
{
    $connexion = new DatabasePDO();
    $sql = "
        SELECT
            tbl_salonfama_publicados.*, 
            tbl_salonfama_categorias.nombre AS nombre_categoria,
            tbl_salonfama_categorias.titulo_listado AS titulo_pagina,
            tbl_salonfama_categorias.descripcion AS descripcion_categoria,
            imagen,
            tbl_usuario.nombre,
            tbl_usuario.apaterno,
            tbl_usuario.amaterno,
            tbl_usuario.nombre_completo,
            tbl_usuario.cargo,
            tbl_salonfama_categorias.template_row AS template_row,
            tbl_salonfama_categorias.imagen_form,
            tbl_salonfama_categorias.imagen_inicio_pagina
        FROM
            tbl_salonfama_publicados
        INNER JOIN tbl_salonfama_categorias 
            ON tbl_salonfama_categorias.id = tbl_salonfama_publicados.id_tipo_reconocimiento
        INNER JOIN tbl_usuario 
            ON tbl_usuario.rut = tbl_salonfama_publicados.rut
        WHERE
            idempresa = :id_empresa  
            AND tbl_salonfama_publicados.ano = :ano
            AND id_tipo_reconocimiento = :id_categoria
        ORDER BY
            tbl_salonfama_publicados.id DESC
    ";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':ano', $ano);
    $connexion->bind(':id_categoria', $id_categoria);
    $cod = $connexion->resultset();
    return $cod;
}
function ValidoTokem($tokem)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_registro_login WHERE tokem = :tokem";
    $connexion->query($sql);
    $connexion->bind(':tokem', $tokem);
    $cod = $connexion->resultset();
    return $cod;
}
function InsertaRegistroLogin($arreglo_post)
{
    $connexion = new DatabasePDO();
    $fecha = date("Y-m-d");
    $hora  = date("H:i:s");
    $tokem = base64_encode($arreglo_post["user"] . "&" . $fecha . "&" . $hora);
    $sql   = "INSERT INTO tbl_registro_login(rut, nombre, cargo, celular, email, fecha_ingreso, rut_jefe, nombre_jefe, cargo_jefe, correo_jefe, tokem, fecha, hora, id_empresa) " . "VALUES (:rut, :nombre, :cargo, :celular, :email, :fecha_ingreso, :rut_jefe, :nombre_jefe, :cargo_jefe, :correo_jefe, :tokem, :fecha, :hora, '58')";
    $connexion->query($sql);
    $connexion->bind(':rut', utf8_decode($arreglo_post["user"]));
    $connexion->bind(':nombre', utf8_decode($arreglo_post["nombre"]));
    $connexion->bind(':cargo', utf8_decode($arreglo_post["cargo"]));
    $connexion->bind(':celular', utf8_decode($arreglo_post["celular"]));
    $connexion->bind(':email', utf8_decode($arreglo_post["email"]));
    $connexion->bind(':fecha_ingreso', utf8_decode($arreglo_post["fecha_ingreso"]));
    $connexion->bind(':rut_jefe', utf8_decode($arreglo_post["rut_jefe"]));
    $connexion->bind(':nombre_jefe', utf8_decode($arreglo_post["nombre_jefe"]));
    $connexion->bind(':cargo_jefe', utf8_decode($arreglo_post["cargo_jefe"]));
    $connexion->bind(':correo_jefe', utf8_decode($arreglo_post["correo_jefe"]));
    $connexion->bind(':tokem', $tokem);
    $connexion->bind(':fecha', $fecha);
    $connexion->bind(':hora', $hora);
    $connexion->execute();
    return ($tokem);
}
function InsertaVioBanner($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "INSERT INTO tbl_banner_visto(rut, id_empresa, fecha, hora) " . "VALUES (:rut, :id_empresa, :fecha, :hora)";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':fecha', date("Y-m-d"));
    $connexion->bind(':hora', date("H:i:s"));
    $connexion->execute();
}
function InsertaVioBannerDinamicoPorAmbiente($rut, $id_empresa, $ambiente)
{
    $connexion = new DatabasePDO();
    $sql = "INSERT INTO tbl_banner_visto(rut, id_empresa, fecha, hora, ambiente) " . "VALUES (:rut, :id_empresa, :fecha, :hora, :ambiente)";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':fecha', date("Y-m-d"));
    $connexion->bind(':hora', date("H:i:s"));
    $connexion->bind(':ambiente', $ambiente);
    $connexion->execute();
}
function VioBannerPorAmbiente($rut, $id_empresa, $ambiente)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_banner_visto WHERE rut = :rut AND id_empresa = :id_empresa AND ambiente = :ambiente";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':ambiente', $ambiente);
    $cod = $connexion->resultset();
    return $cod;
}
function VioBanner($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_banner_visto WHERE rut = :rut AND id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}
function CertificacionesPorPrograma($id_programa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_certificacion WHERE id_programa = :id_programa";
    $connexion->query($sql);
    $connexion->bind(':id_programa', $id_programa);
    $cod = $connexion->resultset();
    return $cod;
}
function TotalUsuariosPorRutJefe($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT h.*, (SELECT COUNT(id) FROM tbl_lms_acciones_pendientes WHERE rut_colaborador=h.rut) AS cuenta FROM tbl_usuario h WHERE (h.jefe = :rut OR h.responsable = :rut) AND h.vigencia = '0' ORDER BY (SELECT COUNT(id) FROM tbl_lms_acciones_pendientes WHERE rut_colaborador=h.rut) DESC, h.nombre ASC";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}
function TotalUsuariosDependientesJefe($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_usuario WHERE (jefe = :rut OR responsable = :rut) AND vigencia = '0' ORDER BY nombre ASC";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}
function TotalUsuariosPorRutLiderEjecutivos($rut)
{
    $connexion = new DatabasePDO();
    $sql = "
     SELECT
        h.*,
        (
            SELECT COUNT(id)
            FROM tbl_lms_acciones_pendientes
            WHERE rut_colaborador=h.rut
        ) AS cuenta
     FROM tbl_usuario h
     WHERE h.lider=:rut
        AND h.vigencia='0'
     ORDER BY cuenta DESC, h.nombre ASC
    ";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}
function TotalReconocimientosPorCategoria($id_categoria, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "
     SELECT
        tbl_salonfama_publicados.*,
        tbl_salonfama_categorias.nombre AS nombre_categoria,
        tbl_salonfama_categorias.titulo_listado AS titulo_pagina,
        tbl_salonfama_categorias.descripcion AS descripcion_categoria,
        tbl_salonfama_valores.nombre AS nombre_valor,
        imagen,
        tbl_usuario.nombre,
        tbl_usuario.apaterno,
        tbl_usuario.amaterno,
        tbl_usuario.nombre_completo,
        tbl_usuario.cargo,
        tbl_salonfama_categorias.template_row AS template_row,
        tbl_salonfama_categorias.imagen_form,
        tbl_salonfama_categorias.imagen_inicio_pagina,
        tbl_salonfama_conductas.nombre AS nombre_conducta_valor
     FROM tbl_salonfama_publicados
     INNER JOIN tbl_salonfama_categorias
        ON tbl_salonfama_categorias.id = tbl_salonfama_publicados.id_tipo_reconocimiento
     INNER JOIN tbl_salonfama_valores
        ON tbl_salonfama_valores.id = tbl_salonfama_publicados.id_valor
     LEFT JOIN tbl_salonfama_conductas
        ON tbl_salonfama_conductas.id = tbl_salonfama_publicados.id_conducta
     INNER JOIN tbl_usuario
        ON tbl_usuario.rut = tbl_salonfama_publicados.rut
     WHERE idempresa = :id_empresa
        AND id_tipo_reconocimiento = :id_categoria
     ORDER BY nombre_valor, tbl_salonfama_publicados.id DESC
    ";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_categoria', $id_categoria);
    $cod = $connexion->resultset();
    return $cod;
}
function TotalReconocimientosPorRut($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "
    SELECT tbl_salonfama_publicados.*, tbl_salonfama_categorias.nombre as nombre_categoria,
    tbl_salonfama_categorias.descripcion as descripcion_categoria, imagen
    FROM tbl_salonfama_publicados
    INNER JOIN tbl_salonfama_categorias ON tbl_salonfama_categorias.id = tbl_salonfama_publicados.id_tipo_reconocimiento
    WHERE rut = :rut AND idempresa = :id_empresa
    ";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}
function DatosEmpresaHolding($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_empresa_holding WHERE rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}
function EncTieneSesion($rut, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_enc_satis_sesion WHERE rut = :rut AND id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}
function EncTieneSesionPorEncuesta($rut, $id_empresa, $id_encuesta, $id_objeto)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_enc_satis_sesion WHERE rut = :rut AND id_empresa = :id_empresa AND idencuesta = :id_encuesta AND id_objeto = :id_objeto";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $connexion->bind(':id_objeto', $id_objeto);
    $cod = $connexion->resultset();
    return $cod;
}
function EncTieneSesionPorObjeto($rut, $id_empresa, $id_objeto, $id_encuesta)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_enc_satis_sesion WHERE rut = :rut AND id_empresa = :id_empresa AND id_objeto = :id_objeto AND idencuesta = :id_encuesta";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':id_objeto', $id_objeto);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $cod = $connexion->resultset();
    return $cod;
}
function allPersonasPorEmpresa($buscar, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_usuario WHERE id_empresa = :id_empresa AND (nombre_completo LIKE :buscar OR rut LIKE :buscar) AND vigencia = '0' ORDER BY nombre_completo";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':buscar', "%$buscar%");
    $datos = $connexion->resultset();
    return $datos;
}
function allPersonasPorEmpresaSinDivPersona($buscar, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_usuario WHERE id_empresa = :id_empresa AND rut LIKE :buscar AND vigencia = '0' AND division <> 'Division Personas y Organizacion' ORDER BY nombre_completo";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':buscar', "%$buscar%");
    $datos = $connexion->resultset();
    return $datos;
}
function allPersonasPorEmpresaSinDivPersonaJefe($buscar, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_usuario WHERE id_empresa = :id_empresa AND rut LIKE :buscar AND vigencia = '0' AND division <> 'Division Personas y Organizacion' AND (SELECT COUNT(id) FROM tbl_usuario WHERE jefe LIKE :buscar) > 0 ORDER BY nombre_completo";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':buscar', "%$buscar%");
    $datos = $connexion->resultset();
    return $datos;
}
function allSucursalesPorEmpresa($buscar, $id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_sucursales WHERE id_empresa = :id_empresa and nombre like :buscar ORDER BY nombre";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':buscar', "%{$buscar}%", PDO::PARAM_STR);
    $datos = $connexion->resultset();
    return $datos;
}
function allPersonas($buscar)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_usuario WHERE nombre_completo like :buscar OR rut like :buscar ORDER BY nombre_completo";
    $connexion->query($sql);
    $connexion->bind(':buscar', "%{$buscar}%", PDO::PARAM_STR);
    $datos = $connexion->resultset();
    return $datos;
}
function DatosExtensionDeObjeto($id)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_objetos_extension WHERE id = :id";
    $connexion->query($sql);
    $connexion->bind(':id', $id);
    $cod = $connexion->resultset();
    return $cod;
}
function EncOpcionesDadoId($id_opciones)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_enc_satis_opc WHERE idopcion = :id_opciones ORDER BY orden ASC";
    $connexion->query($sql);
    $connexion->bind(':id_opciones', $id_opciones);
    $cod = $connexion->resultset();
    return $cod;
}
function EncTraigoPreguntasDadaPaginaIdEncuesta($id_encuesta, $pagina)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT tbl_enc_satis_preg_tipo.template, tbl_enc_satis_preg_tipo.template_opcion, tbl_enc_satis_preg.*, tbl_enc_satis_dim.dimension as nombre_dimension, tbl_enc_satis_dim.visible FROM tbl_enc_satis_preg INNER JOIN tbl_enc_satis_dim ON tbl_enc_satis_dim.iddimension=tbl_enc_satis_preg.iddimension INNER JOIN tbl_enc_satis ON tbl_enc_satis.idencuesta=tbl_enc_satis_dim.idencuesta INNER JOIN tbl_enc_satis_preg_tipo ON tbl_enc_satis_preg_tipo.id=tbl_enc_satis_preg.tipo_pregunta WHERE pagina=:pagina AND tbl_enc_satis.idencuesta=:id_encuesta";
    $connexion->query($sql);
    $connexion->bind(':pagina', $pagina);
    $connexion->bind(':id_encuesta', $id_encuesta);
    $cod = $connexion->resultset();
    return $cod;
}
function ObtenerEncuestaDadiIdObjeto($id_objeto)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT tbl_enc_satis.*
            FROM tbl_objeto
            INNER JOIN tbl_enc_satis
                ON tbl_enc_satis.idencuesta = tbl_objeto.id_encuesta
            WHERE tbl_objeto.id = :id_objeto";
    $connexion->query($sql);
    $connexion->bind(':id_objeto', $id_objeto);
    $cod = $connexion->resultset();
    return $cod;
}
function DatosUsuarioLeftJefeDeTblUsuario($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT tbl_usuario.*,
            base_jefe.nombre as nombre_jefe,
            base_jefe.apaterno as apaterno_jefe,
            base_jefe.amaterno as amaterno_jefe
            FROM tbl_usuario
            LEFT JOIN tbl_usuario as base_jefe ON base_jefe.rut = tbl_usuario.jefe
            WHERE tbl_usuario.rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}
function DatosUsuarioLeftJefe($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT tbl_usuario.*,
            basejefe.nombre_completo as nombre_evaluador,
            tbl_sgd_perfiles_ponderaciones.descripcion as nombre_perfil
            FROM tbl_usuario
            LEFT JOIN tbl_usuario as basejefe ON basejefe.rut = tbl_usuario.evaluador
            LEFT JOIN tbl_sgd_perfiles_ponderaciones ON tbl_usuario.perfil_evaluacion = tbl_sgd_perfiles_ponderaciones.perfil
            WHERE tbl_usuario.rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}
function TraeUsuariosDadoCriterio($campo, $valor, $arreglo_post)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_usuario WHERE $campo = :valor";
    $connexion->query($sql);
    $connexion->bind(':valor', $valor);
    $cod = $connexion->resultset();
    return $cod;
}
function ListadoUsuariosLideres($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT tbl_usuario.*, 
                   (SELECT COUNT(*) 
                    FROM tbl_usuario AS aux 
                    WHERE aux.jefe = tbl_usuario.rut) AS total, 
                   base_jefe.nombre_completo AS nombre_jefe, 
                   base_jefe.rut_completo AS rut_jefe 
            FROM tbl_usuario 
            LEFT JOIN tbl_usuario AS base_jefe 
            ON base_jefe.rut = tbl_usuario.jefe 
            WHERE tbl_usuario.jefe = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}
function EsJefe($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT tbl_usuario.*, 
                   (SELECT COUNT(*) 
                    FROM tbl_usuario AS aux 
                    WHERE aux.jefe = tbl_usuario.rut) AS total, 
                   base_jefe.nombre_completo AS nombre_jefe, 
                   base_jefe.rut_completo AS rut_jefe 
            FROM tbl_usuario 
            LEFT JOIN tbl_usuario AS base_jefe 
            ON base_jefe.rut = tbl_usuario.jefe 
            WHERE tbl_usuario.jefe = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}
function InsertaLogNavegacion($rut, $id_objeto, $tiempo_navegado, $id_curso)
{
    $connexion = new DatabasePDO();
    if ($tiempo_navegado == "00:00:00") {
        $tiempo_navegado = "00:01:00";
    }
    $sql = "INSERT INTO tbl_logs_navegacion(rut, id_objeto, tiempo_navegacion, id_curso, fecha, hora) " . "VALUES (:rut, :id_objeto, :tiempo_navegado, :id_curso, :fecha, :hora);";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_objeto', $id_objeto);
    $connexion->bind(':tiempo_navegado', $tiempo_navegado);
    $connexion->bind(':id_curso', $id_curso);
    $connexion->bind(':fecha', date("Y-m-d"));
    $connexion->bind(':hora', date("H:i:s"));
    $connexion->execute();
}
function ObtengoTipoCorreo($tipo)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_correos WHERE tipo = :tipo";
    $connexion->query($sql);
    $connexion->bind(':tipo', $tipo);
    $cod = $connexion->resultset();
    return $cod;
}
function DatosEmpresa($id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_empresa WHERE id = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}
function VerificaClaveAccesoAdmin($rut, $clave)
{
    $connexion = new DatabasePDO();

    $clave = utf8_decode($clave);
    $clave = Encriptar($clave);
    $sql = "SELECT * FROM tbl_admin WHERE user = :rut AND clave_encodeada = :clave";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':clave', $clave);
    $connexion->execute();
    $cod = $connexion->resultset();

    return $cod;
}
function InsertaLogSistema($rut, $ambiente, $id_empresa, $id_detalle, $subcategoria, $id_archivo, $nivel, $id_menu_nivel)
{
    $connexion = new DatabasePDO();
    if($ambiente=="sdriesgo_TEsT_"){
        $sql = "INSERT INTO tbl_log_sistema(rut, ambiente, fecha, hora, ip, id_empresa, id_detalle, subcategoria, id_archivo, menu_nivel, id_menu_nivel, variables_post, variables_get) " . "VALUES (:rut, :ambiente, :fecha, :hora, :ip, :id_empresa, :id_detalle, :subcategoria, :id_archivo, :nivel, :id_menu_nivel, :variables_post, :variables_get)";
        $connexion->query($sql);
        $connexion->bind(':rut', $rut);
        $connexion->bind(':ambiente', $ambiente);
        $connexion->bind(':fecha', date("Y-m-d"));
        $connexion->bind(':hora', date("H:i:s"));
        $connexion->bind(':ip', $_SERVER['REMOTE_ADDR']);
        $connexion->bind(':id_empresa', $id_empresa);
        $connexion->bind(':id_detalle', $id_detalle);
        $connexion->bind(':subcategoria', $subcategoria);
        $connexion->bind(':id_archivo', $id_archivo);
        $connexion->bind(':nivel', $nivel);
        $connexion->bind(':id_menu_nivel', $id_menu_nivel);
        $connexion->bind(':variables_post', $_POST["value"]);
        $connexion->bind(':variables_get', json_encode($_GET));
        $connexion->execute();


        $sql = "INSERT INTO tbl_json_ext(rut, fecha, hora, json_completo) " . "VALUES (:rut, :fecha, :hora, :json_completo)";
        $connexion->query($sql);
        $connexion->bind(':rut', $rut);
        $connexion->bind(':fecha', date("Y-m-d"));
        $connexion->bind(':hora', date("H:i:s"));
        $connexion->bind(':json_completo', $_POST["value"]);
        $connexion->execute();


    }else if($ambiente=="sdriesgo" || $ambiente=="sdData") {
        $sql = "INSERT INTO tbl_log_sistema(rut, ambiente, fecha, hora, ip, id_empresa, id_detalle, subcategoria, id_archivo, menu_nivel, id_menu_nivel, variables_post, variables_get) VALUES (:rut, :ambiente, :fecha, :hora, :ip, :id_empresa, :id_detalle, :subcategoria, :id_archivo, :menu_nivel, :id_menu_nivel, :variables_post, :variables_get)";
        $connexion->query($sql);
        $connexion->bind(':rut', $rut);
        $connexion->bind(':ambiente', $ambiente);
        $connexion->bind(':fecha', date("Y-m-d"));
        $connexion->bind(':hora', date("H:i:s"));
        $connexion->bind(':ip', $_SERVER['REMOTE_ADDR']);
        $connexion->bind(':id_empresa', $id_empresa);
        $connexion->bind(':id_detalle', $id_detalle);
        $connexion->bind(':subcategoria', $subcategoria);
        $connexion->bind(':id_archivo', $id_archivo);
        $connexion->bind(':menu_nivel', $nivel);
        $connexion->bind(':id_menu_nivel', $id_menu_nivel);
        $connexion->bind(':variables_post', json_encode($_POST));
        $connexion->bind(':variables_get', json_encode($_GET));
        $connexion->execute();

        $sql = "INSERT INTO tbl_json_ext(rut, fecha, hora, json_completo, id_curso, id_objeto, simulador) VALUES (:rut, :fecha, :hora, :json_completo, :id_curso, :id_objeto, :simulador)";
        $connexion->query($sql);
        $connexion->bind(':rut', $rut);
        $connexion->bind(':fecha', date("Y-m-d"));
        $connexion->bind(':hora', date("H:i:s"));
        $connexion->bind(':json_completo', $_POST["value"]);
        $connexion->bind(':id_curso', $_GET["idc"]);
        $connexion->bind(':id_objeto', $_GET["ido"]);
        $connexion->bind(':simulador', $_GET["simulador"]);
        $connexion->execute();

        InsertaRegistroFinalizadoObjetoAjax($rut, $_GET["idc"], $_GET["ido"], $_GET["simulador"]);
    }else{
        $sql = "INSERT INTO tbl_log_sistema(rut, ambiente, fecha, hora, ip, id_empresa, id_detalle, subcategoria, id_archivo, menu_nivel, id_menu_nivel, variables_post, variables_get) VALUES (:rut, :ambiente, :fecha, :hora, :ip, :id_empresa, :id_detalle, :subcategoria, :id_archivo, :menu_nivel, :id_menu_nivel, :variables_post, :variables_get)";
        $connexion->query($sql);
        $connexion->bind(':rut', $rut);
        $connexion->bind(':ambiente', $ambiente);
        $connexion->bind(':fecha', date("Y-m-d"));
        $connexion->bind(':hora', date("H:i:s"));
        $connexion->bind(':ip', $_SERVER['REMOTE_ADDR']);
        $connexion->bind(':id_empresa', $id_empresa);
        $connexion->bind(':id_detalle', $id_detalle);
        $connexion->bind(':subcategoria', $subcategoria);
        $connexion->bind(':id_archivo', $id_archivo);
        $connexion->bind(':menu_nivel', $nivel);
        $connexion->bind(':id_menu_nivel', $id_menu_nivel);
        $connexion->bind(':variables_post', json_encode($_POST));
        $connexion->bind(':variables_get', json_encode($_GET));
        $connexion->execute();
    }
}
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
function UsuarioAdminRutEmpresa($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_admin WHERE user = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}
function UsuarioAdminEmpresa($id_empresa)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT * FROM tbl_admin WHERE id_empresa = :id_empresa";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $cod = $connexion->resultset();
    return $cod;
}
function UsuarioEnBasePersonas2($rut)
{
    $connexion = new DatabasePDO();
    $sql = "
        SELECT tbl_usuario.perfil_evaluacion, tbl_sgd_perfiles_ponderaciones.descripcion AS nombre_perfil
        FROM tbl_usuario
        INNER JOIN tbl_sgd_perfiles_ponderaciones ON tbl_usuario.perfil_evaluacion = tbl_sgd_perfiles_ponderaciones.perfil
        WHERE rut = :rut
    ";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}
function UsuarioEnBasePersonas2PorProceso($rut, $id_proceso)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT DISTINCT tbl_sgd_relaciones.perfil_evaluacion_competencias AS perfil_evaluacion,
            tbl_sgd_perfiles_ponderaciones.descripcion AS nombre_perfil
            FROM tbl_sgd_relaciones
            INNER JOIN tbl_sgd_perfiles_ponderaciones ON tbl_sgd_relaciones.perfil_evaluacion_competencias = tbl_sgd_perfiles_ponderaciones.perfil
            WHERE tbl_sgd_relaciones.evaluado = :rut AND tbl_sgd_relaciones.id_proceso = :id_proceso";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $connexion->bind(':id_proceso', $id_proceso);
    $cod = $connexion->resultset();
    return $cod;
}
function UsuarioEnBasePersonasInnerArea($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT tbl_usuario.*, tbl_area.area AS nombre_area
            FROM tbl_usuario
            INNER JOIN tbl_area ON tbl_area.id = tbl_usuario.id_area
            WHERE rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}
function UsuarioEnBaseDePersonasInnerEmpresa($rut)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT tbl_usuario.*, tbl_empresa.campo1, tbl_empresa.campo2, tbl_empresa.campo3
            FROM tbl_usuario
            INNER JOIN tbl_empresa ON tbl_empresa.id = tbl_usuario.id_empresa
            WHERE rut = :rut";
    $connexion->query($sql);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}
function editar_archivo($descripcion, $nombre, $id_cat, $id, $imagen)
{
    $connexion = new DatabasePDO();
    if (existe_archivo($nombre, $id_cat, $id) == 0) {
        $sql = "UPDATE tbl_biblio_archivos
                SET titulo = :titulo,
                imagen = :imagen,
                descripcion = :descripcion
                WHERE id = :id";
        $connexion->query($sql);
        $connexion->bind(':titulo', utf8_decode($nombre));
        $connexion->bind(':imagen', utf8_decode($imagen));
        $connexion->bind(':descripcion', utf8_decode($descripcion));
        $connexion->bind(':id', $id);
        $connexion->execute();
        guarda_log_biblioteca("Edita", $id, "1", "Archivo");
    } else {
        return "1";
    }
}
function biblio_edita_sub_categoria($subcategoria, $descripcion, $categoria, $id_sub, $imagen, $id_mundo, $id_comunidad)
{
    $connexion = new DatabasePDO();
    if (existe_sub_categoria($subcategoria, $id_sub) == 0) {
    $sql = "UPDATE tbl_biblio_categorias
    SET categoria = :subcategoria,
    descripcion = :descripcion,
    id_mundo = :id_mundo,
    id_comunidad = :id_comunidad
    WHERE id = :id_sub;";
    $connexion->query($sql);
    $connexion->bind(':subcategoria', utf8_decode($subcategoria));
    $connexion->bind(':descripcion', utf8_decode($descripcion));
    $connexion->bind(':id_mundo', utf8_decode($id_mundo));
    $connexion->bind(':id_comunidad', utf8_decode($id_comunidad));
    $connexion->bind(':id_sub', $id_sub);
    $connexion->execute();
    guarda_log_biblioteca("Edita", $id_sub, "1", "Subcategoria");
    } else {
    return "1";
    }
}
function Postulacion_2020_CVS_Download($id_empresa) {
    $connexion = new DatabasePDO();
    $sql= "
		SELECT h.rut, h.id_evento, h.fecha, h.hora, p.categoria AS Evento, p.fecha AS FechaEvento, p.dato4 AS HoraEvento
		FROM tbl_postulaciones h
		JOIN tbl_postulables p ON h.id_evento = p.id_evento
		WHERE YEAR(h.fecha_estado) = YEAR(CURDATE())
		ORDER BY h.fecha, p.categoria, p.fecha, p.dato4
    ";
    $connexion->query($sql);
    $datos = $connexion->resultset();
    return $datos;
}
function Postulaciones_SAVE_DELETE_INSCRIPCION_2020($rut, $id_empresa, $ide) {
    $connexion = new DatabasePDO();
    $sql = "delete from tbl_postulaciones where id_empresa=:id_empresa and id_evento=:ide and rut=:rut";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':ide', $ide);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->execute();
    return $cod;
}
function Verificasiesempresa($ide)
{
    $connexion = new DatabasePDO();
    $sql = "SELECT id FROM tbl_empresa WHERE id = :ide";
    $connexion->query($sql);
    $connexion->bind(':ide', $ide);
    $cod = $connexion->resultset();
    return count($cod);
}
function BuscaEvaluadorEvaluadoSgd($evaluado){

}
function mp_buscaDATOSPERSONAS($rut, $id_empresa)
{
	$connexion = new DatabasePDO();
    $sql = "
        SELECT h.*,
            (SELECT biografia FROM tbl_usuario_biografia WHERE rut=h.rut AND id_empresa=h.id_empresa LIMIT 1) AS biografia,
            (SELECT sueno FROM tbl_usuario_biografia WHERE rut=h.rut AND id_empresa=h.id_empresa LIMIT 1) AS sueno,
            (SELECT logros FROM tbl_usuario_biografia WHERE rut=h.rut AND id_empresa=h.id_empresa LIMIT 1) AS logros,
            (SELECT avatar FROM tbl_usuario_biografia WHERE rut=h.rut AND id_empresa=h.id_empresa LIMIT 1) AS avatarcomunidad,
            (SELECT celular FROM tbl_usuario_biografia WHERE rut=h.rut AND id_empresa=h.id_empresa LIMIT 1) AS celular
        FROM tbl_usuario h
        WHERE h.id_empresa=:id_empresa AND h.rut=:rut
    ";
    $connexion->query($sql);
    $connexion->bind(':id_empresa', $id_empresa);
    $connexion->bind(':rut', $rut);
    $cod = $connexion->resultset();
    return $cod;
}
?>