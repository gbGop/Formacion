<?php
function CleanHTMLWhiteList($html)
{

    if ($html === null) {
        return '';
    }
    //echo $html;
    $listaBlanca = [
        'a', 'abbr', 'acronym', 'address', 'applet', 'area', 'article', 'aside', 'audio', 'b', 'base',
        'basefont', 'bdi', 'bdo', 'bgsound', 'big', 'blink', 'blockquote', 'body', 'br', 'button', 'canvas',
        'caption', 'center', 'cite', 'code', 'col', 'colgroup', 'command', 'content', 'data', 'datalist', 'dd',
        'del', 'details', 'dfn', 'dialog', 'dir', 'div', 'dl', 'dt', 'element', 'em', 'embed', 'fieldset',
        'figcaption', 'figure', 'font', 'footer', 'form', 'frame', 'frameset', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'head', 'header', 'hgroup', 'hr', 'html', 'i', 'iframe', 'img', 'input', 'ins', 'isindex', 'kbd', 'label',
        'legend', 'li', 'link', 'listing', 'main', 'map', 'mark', 'marquee', 'menu', 'menuitem', 'meta', 'meter',
        'nav', 'nobr', 'noembed', 'noframes', 'noscript', 'object', 'ol', 'optgroup', 'option', 'output', 'p',
        'param', 'picture', 'plaintext', 'pre', 'progress', 'q', 'rb', 'rp', 'rt', 'rtc', 'ruby', 's', 'samp',
        'script', 'section', 'select', 'shadow', 'slot', 'small', 'source', 'spacer', 'span', 'strike', 'strong',
        'style', 'sub', 'summary', 'sup', 'table', 'tbody', 'td', 'template', 'textarea', 'tfoot', 'th', 'thead',
        'time', 'title', 'tr', 'track', 'tt', 'u', 'ul', 'var', 'video', 'wbr', 'xmp'
    ];
    $doc = new DOMDocument();
    libxml_use_internal_errors(false); // Suprimir errores de an�lisis
    // Cargar el HTML en el documento DOM
    $doc->loadHTML($html);
    // Recorrer todos los elementos del documento
    $elements = $doc->getElementsByTagName('*');
    foreach ($elements as $element) {
        // Obtener el nombre del tag
        $tagName = $element->tagName;
        // Si el tag no está en la lista blanca, eliminarlo
        if (!in_array($tagName, $listaBlanca, true)) {
            $parent = $element->parentNode;
            $parent->removeChild($element);
        }
    }
    return $doc->saveHTML();
}

function my_simple_crypt_encodeartkMI($string)
{
    $secret_key = getenv('SECRET_KEYMI');
    $secret_iv = getenv('SECRET_IVMI');
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
    return $output;
}

function EncodeartkMI($valor)
{
    $valor = my_simple_crypt_encodeartkMI($valor);
    return ($valor);
}

function my_simple_crypt_decodeartkMI($string)
{
    $secret_key = getenv('SECRET_KEYMI');
    $secret_iv = getenv('SECRET_IVMI');
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    return $output;
}

function DecodeartkMI($valor)
{
    $valor = my_simple_crypt_decodeartkMI($valor);
    $valor = VerificaArregloSQLInjectionDecodear($valor);
    return ($valor);
}

function my_simple_crypt_encodeartk($string)
{
    $secret_key = getenv('SECRET_KEYTK');
    $secret_iv = getenv('SECRET_IVTK');
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
    return $output;
}

function Encodeartk($valor)
{
    $valor = my_simple_crypt_encodeartk($valor);
    return ($valor);
}

function my_simple_crypt_decodeartk($string)
{
    $secret_key = getenv('SECRET_KEYTK');
    $secret_iv = getenv('SECRET_IVTK');
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    return $output;
}

function Decodeartk($valor)
{
    $valor = my_simple_crypt_decodeartk($valor);
    $valor = VerificaArregloSQLInjectionDecodear($valor);
    return ($valor);
}

function FuncionesTransversales_2020($html, $tipo_menu)
{
    $environment_sw = $_GET["sw"];
    if (isset($_SESSION["user_"]) && $_SESSION["user_"] !== '') {
        $rut = $_SESSION["user_"];
        $rut_sesion = $_SESSION["user_"];
    }
    $id_empresa = $_SESSION["id_empresa"];
    $html = str_replace("{FOOTER}", file_get_contents("views/footer.html"), $html);
    $ahora = date("Y-m-d h:i:s");
    $html = str_replace("{HEAD_DINAMICO}", file_get_contents("views/head/" . $id_empresa . "_head_mc_30.html"), $html);
    $html = str_replace("{AHORA}", $ahora, $html);

    if ($tipo_menu == "landing_2019") {
        $html = str_replace("{MENU_DINAMICO}", file_get_contents("views/menu/" . $id_empresa . "_menu_principal_2019.html"), $html);
    } elseif ($tipo_menu == "2020") {
        $html = str_replace("{MENU_DINAMICO}", file_get_contents("views/menu/" . $id_empresa . "_menu_mc_30.html"), $html);
    } elseif ($tipo_menu == "2022_pregingreso") {
        $html = str_replace("{MENU_DINAMICO}", file_get_contents("views/menu/" . $id_empresa . "_menu_mc_30_2022_preingreso.html"), $html);
    } elseif ($tipo_menu == "2023") {
        $html = str_replace("{MENU_DINAMICO_2023}", file_get_contents("views/menu/" . $id_empresa . "_menu_mc_30_2023.html"), $html);
    } else {
        $html = str_replace("{MENU_DINAMICO}", file_get_contents("views/menu/" . $id_empresa . "_menu_principal_2019.html"), $html);
    }

    $html = str_replace("{MENU_DINAMICO_mc30}", file_get_contents("views/menu/" . $id_empresa . "_menu_mc_30.html"), $html);

    $html = str_replace("{MENU_DINAMICO_EXT}", file_get_contents("views/menu/" . $id_empresa . "_menu_2020_ext.html"), $html);


    $html = str_replace("{FOOTER_DINAMICO}", file_get_contents("views/head/" . $id_empresa . "_footer_2020.html"), $html);
    $html = str_replace("{FOOTER_DINAMICO_v2}", file_get_contents("views/head/" . $id_empresa . "_footer_2020_v2.html"), $html);


    /*  if($esjefe>0 or $esjefeResponsabl>0 or $esliderEjecutivo>0){
                              $html = str_replace("{MI_EQUIPO_VISTA}",$boton_miequipo_vista, $html);
          } else {            $html = str_replace("{MI_EQUIPO_VISTA}","", $html);

      }*/
    $html = str_replace("{MENU_DINAMICO_BOTON_PERSONAS}", "<li><a href='?sw=personas_2020'><span class='title'>  #BuscaPersonas</span></a> </li>", $html);
    $html = str_replace("{MENU_DINAMICO_BOTON_PERSONAS_MOB}", "<li><a href='?sw=personas_2020'><span class='title'>  #BuscaPersonas</span></a> </li>", $html);
    /*                      $esjefe=0;
                      if($rut<>""){
                          $esjefe            = EsJefeLidertblUsuario($rut, $id_empresa);
                      }*/


    /*
    if($esjefe>0  ){
                            $html = str_replace("{MENU_DINAMICO_BOTON_MI_EQUIPO}","<li><a href='?sw=mi_equipo_consolidado_2020'><span class='title'>Mi Equipo</span></a></li>", $html);
                            $html = str_replace("{MENU_DINAMICO_BOTON_MI_EQUIPO_MOB}","<li><a href='?sw=mi_equipo_consolidado_2020'><span class='title'>Mi Equipo</span></a></li>", $html);
                         $html = str_replace("{MENU_DINAMICO_BOTON_MI_EQUIPO2}","<li><a href='?sw=mi_equipo_lider'><i class='fas fa-caret-right'></i> Mi Equipo</a></li>", $html);


    } else {*/
    $html = str_replace("{MENU_DINAMICO_BOTON_MI_EQUIPO}", "", $html);
    $html = str_replace("{MENU_DINAMICO_BOTON_MI_EQUIPO_MOB}", "", $html);
    $html = str_replace("{MENU_DINAMICO_BOTON_MI_EQUIPO2}", "", $html);
    /*}*/

    $html = str_replace("{MENU_DINAMICO_BOTON_MI_PERFIL}", "<li><a href='?sw=mi_perfil_2020'><span class='title'>Mi Perfil</span></a></li>", $html);
    $html = str_replace("{MENU_DINAMICO_BOTON_MI_PERFIL_MOB}", "<li><a href='?sw=mi_perfil_2020'><span class='title'>Mi Perfil</span></a></li>", $html);

    if (isset($rut)) {
        $html = str_replace("{RUT_ENCODEADO}", Encodear3($rut), $html);
    }

    $html = str_replace("{MENU_DINAMICO_BOTON_SGD_2}", "<li><a href='?sw=evaluacion_integral_2020'>			<span class='title'> Evaluaci&oacute;n Integral</span></a></li>", $html);
    $html = str_replace("{MENU_DINAMICO_BOTON_SGD_2_MOB}", "<li><a href='?sw=evaluacion_integral_2020'>	<span class='title'> Evaluaci&oacute;n Integral</span></a></li>", $html);

    if (isset($_SESSION["user_"]) && $_SESSION["user_"] !== '') {
        $rut = $_SESSION["user_"];
    }

    $html = str_replace("{BLOQUEFOOTERCONTENIDO}", "", $html);
    $html = str_replace("{MENU_INFERIOR}", "", $html);
    return ($html);
}
function Insert_Imparticion_Tbl_Usuarios_Datos_Dotacion_FN_2023($id_imparticion){
    $id_empresa = $_SESSION["id_empresa"];
    $hoy=date("Y-m-d");
    $part=IMPARTICION_UsuariosPorInscripcion_DotacionIniciales($id_imparticion);
        //print_r($part);
        foreach ($part as $p){
            $Usu=TraeDatosUsuario($p->rut);
            Imparticion_Tbl_Usuarios_Datos_Dotacion_2023($id_imparticion,$p->id_curso, $p->rut, $Usu[0]->nombre_completo, $Usu[0]->cargo, $Usu[0]->silla_id_cargo, $Usu[0]->silla_id_division, $Usu[0]->silla_id_area, $Usu[0]->silla_id_unidad, $_SESSION["id_empresa"], $hoy);
        }
    //exit();
}

function QR_Dashboard($PRINCIPAL, $id_imparticion)
{
    //echo "<br>QR_Dashboard $id_imparticion";
    $SesRelatores=QR_IdInscripcion_Sesiones_Relatores($id_imparticion);
    //echo "<pre>";        print_r($SesRelatores);    echo "</pre>";
    $Preg_Respuestas=EncuestaCheckRespuestasEncSatQRFull($id_imparticion);
    //echo "<pre>";        print_r($Preg_Respuestas);    echo "</pre>";
    $ultima_dimension="";

    $suma_respuestas_niv_1_7=0;
    $cuenta_respuestas_niv_1_7=0;
    $suma_respuestas_relatores=0;
    $cuenta_respuestas_relatores=0;

    foreach ($Preg_Respuestas as $P){
        $id_encuesta    =$P->id_encuesta;
        $id_pregunta    =$P->id_pregunta;
        $respuesta      =$P->respuesta;
        $dimension      =$P->dimension;
        $pregunta       =$P->pregunta;
        if($dimension<>$ultima_dimension){
            $row_encuestas_notas_2023.="<div class='col-lg-12'>".($dimension)."</div>";
        }
        //echo "<br>$id_pregunta<br>".$P->tipo_pregunta."<br>";
        if($P->tipo_pregunta=="NIV1_7"){
            //echo "<br>A NIV1_7<br>";
            $suma_respuestas=0;
            $cuenta_respuestas=0;
            //recorro respuestas
            $R=EncuestaCheckRespuestasDetailEncSatQRFull($id_encuesta, $id_pregunta, $id_imparticion);
            foreach ($R as $resp){
                $cuenta_respuestas++;
                //echo "<br>respuesta ".$resp->respuesta;
                $suma_respuestas=$resp->respuesta+$suma_respuestas;
                $suma_respuestas_niv_1_7=$resp->respuesta+$suma_respuestas_niv_1_7;
                $cuenta_respuestas_niv_1_7++;
            }
            if($cuenta_respuestas>0){
                $promedio_respuestas=round($suma_respuestas/$cuenta_respuestas,1);
            } else {
                $promedio_respuestas="-";
            }
                //RESPONDE RESULTADOS POR PREGUNTA
                $row_encuestas_notas_2023.="<div class='col-lg-6 bg-white'>".($pregunta)."</div>
                                        <div class='col-lg-3 bg-white' >".$cuenta_respuestas."</div>
                                        <div class='col-lg-3 bg-white' >".$promedio_respuestas."</div>";
        }
        elseif($P->tipo_pregunta=="PREGUNTAS_RELATORES"){

            //RESPONDE RESULTADOS POR PREGUNTA
            $row_encuestas_notas_2023.="<div class='col-lg-6 bg-white'>".($pregunta)."</div>
                                        <div class='col-lg-3 bg-white' ></div>
                                        <div class='col-lg-3 bg-white' ></div>";
            //recorrer relatores
            $suma_respuestas=0;
            $cuenta_respuestas=0;
            //echo "<br>id_imparticion $id_imparticion";
            $R = EncuestaCheckRespuestasDetailEncSatQRFull($id_encuesta, $id_pregunta, $id_imparticion);
            $cuenta_respuestas_relatores = 0;
            $suma_respuestas_relatores = 0;

            foreach ($R as $resp) {
                $cuenta_respuestas++;
                //echo "<br>respuesta " . $resp->respuesta;
                $respuestas_relator_array = explode(";", $resp->respuesta);
                //print_r($respuestas_relator_array);
                $cuenta_linea_respuestas = 0;
                foreach ($respuestas_relator_array as $resp_relator) {
                    //echo "<br>-> " . $resp_relator;
                    $cuenta_linea_respuestas++;
                    $respuestas_relatores[$cuenta_linea_respuestas][] = $resp_relator;

                    $suma_respuestas_relatores += intval($resp_relator);
                    $cuenta_respuestas_relatores++;
                }
                $suma_respuestas += intval($resp->respuesta);
            }

                    $cuenta_linea_relatores=0;
                    $Relatores=QR_IdInscripcion_Relatores_List($id_imparticion);
                    foreach ($Relatores as $R){
                        if($R->rut_relator=="creada"){continue;}
                            $cuenta_linea_relatores++;
                            //echo "<br>-> ".$R->rut_relator;
                            //echo "<br>-> ".$R->relator;
                            $relatores[$cuenta_linea_relatores]=$R->rut_relator;
                            $relatores_nombre[$cuenta_linea_relatores]=$R->relator;
                    }
                    //echo "<br>Relatores ";print_r($relatores);echo "<br>Relatores Nombre";print_r($relatores_nombre);echo "<br>Relatores Respuesta";print_r($respuestas_relatores);
            $promedios_relatores = array();
            foreach ($respuestas_relatores as $key => $arreglo) {
                $suma = array_sum($arreglo); // Suma de los valores en el arreglo
                $cantidad = count($arreglo); // Cantidad de elementos en el arreglo
                $promedio = $suma / $cantidad; // Calcula el promedio
                $promedios_relatores[$key] = $promedio; // Almacena el promedio en un nuevo arreglo
            }
                //echo "<br>Promedios ";
                //print_r($promedios_relatores);
            foreach ($Relatores as $R){
                if($R->rut_relator=="creada"){continue;}
                    $cuenta_linea_relatores_promedio++;
                //echo "<br>-> ".$R->rut_relator;
                //echo "<br>-> ".$R->relator;
                $relatores[$cuenta_linea_relatores]         =   $R->rut_relator;
                $relatores_nombre[$cuenta_linea_relatores]  =   $R->relator;
                $row_encuestas_notas_2023.="<div class='col-lg-6 bg-white'>".$R->rut_relator." ".$R->relator."</div>
                                            <div class='col-lg-3 bg-white'>".$cuenta_respuestas."</div>
                                            <div class='col-lg-3 bg-white'>".round($promedios_relatores[$cuenta_linea_relatores_promedio],1)."</div>";
            }
        }
        elseif($P->tipo_pregunta=="LIKE_NO_LIKE"){
                //recorro SI
                //recorro NO
                //echo "<br>B LIKE_NO_LIKE<br>";
            $suma_respuestas=0;
            $cuenta_respuestas=0;
            $cuenta_si=0;
            $cuenta_no=0;
            $R=EncuestaCheckRespuestasDetailEncSatQRFull($id_encuesta, $id_pregunta, $id_imparticion);
            foreach ($R as $resp){
                $cuenta_respuestas++;
                //echo "<br>respuesta ".$resp->respuesta;
                if($resp->respuesta=="SI"){
                    $cuenta_si++;
                } elseif($resp->respuesta=="NO"){
                    $cuenta_no++;
                }
                if($cuenta_respuestas>0){
                    $promedio_respuestas_si=round(100*$cuenta_si/$cuenta_respuestas,1);
                    $promedio_respuestas_no=round(100*$cuenta_no/$cuenta_respuestas,1);
                } else {
                    $promedio_respuestas_si="";
                    $promedio_respuestas_no="";
                }
                $promedio_respuestas=" SI $promedio_respuestas_si% NO $promedio_respuestas_no%";
            }
            //RESPONDE RESULTADOS POR PREGUNTA
            $row_encuestas_notas_2023.="<div class='col-lg-6 bg-white'>".($pregunta)."</div>
                                        <div class='col-lg-3 bg-white' >".$cuenta_respuestas."</div>
                                        <div class='col-lg-3 bg-white' >".$promedio_respuestas."</div>";
        }
        elseif($P->tipo_pregunta=="TEXTAREA"){
            $suma_respuestas=0;
            $cuenta_respuestas=0;
            $cuenta_si=0;
            $cuenta_no=0;
            $promedio_respuestas="";
            continue;
        }
        $ultima_dimension=$dimension;
    }
        //echo     "niv 1 7 $suma_respuestas_niv_1_7 / $cuenta_respuestas_niv_1_7";echo     "Relatores $suma_respuestas_relatores / $cuenta_respuestas_relatores";
    $promedio_niv_1_7 = 0; // Initialize the result to a default value
    $promedio_relatores = 0; // Initialize the result to a default value

// Check if $cuenta_respuestas_niv_1_7 is not zero before performing division
    if ($cuenta_respuestas_niv_1_7 != 0) {
        $promedio_niv_1_7 = round($suma_respuestas_niv_1_7 / $cuenta_respuestas_niv_1_7, 1);
    }

// Check if $cuenta_respuestas_relatores is not zero before performing division
    if ($cuenta_respuestas_relatores != 0) {
        $promedio_relatores = round($suma_respuestas_relatores / $cuenta_respuestas_relatores, 1);
    }

    $suma_final         =   $suma_respuestas_niv_1_7 + $suma_respuestas_relatores;
    $cuenta_final       =   $cuenta_respuestas_niv_1_7 + $cuenta_respuestas_relatores;
    $promedio_final = 0; // Initialize the result to a default value

// Check if $cuenta_final is not zero before performing division
    if ($cuenta_final != 0) {
        $promedio_final = round($suma_final / $cuenta_final, 1);
    }

        //echo "<br> Promedio Niv 1 y 7 $promedio_niv_1_7";    echo "<br> Promedio promedio_relatores $promedio_relatores";    echo "<br> Promedio promedio_final $promedio_final";

    $row_totales_notas_2023.="<div class='col-lg-6 bg-white'><strong>Promedio Curso</strong></div>
                                        <div class='col-lg-3 bg-white' ></div>
                                        <div class='col-lg-3 bg-white' ><strong>".$promedio_niv_1_7."</strong></div>";
    $row_totales_notas_2023.="<div class='col-lg-6 bg-white'><strong>Promedio Relatores</strong></div>
                                        <div class='col-lg-3 bg-white' ></div>
                                        <div class='col-lg-3 bg-white' ><strong>".$promedio_relatores."</strong></div>";
    $row_totales_notas_2023.="<div class='col-lg-6 bg-white'><strong>Promedio Final</strong></div>
                                        <div class='col-lg-3 bg-white' ></div>
                                        <div class='col-lg-3 bg-white' ><strong>".$promedio_final."</strong></div>";

    $PRINCIPAL = str_replace("{TABLE_PREGUNTAS_CATEGORIA_CANTIDAD_NOTA_PROMEDIO_2023}", ($row_encuestas_notas_2023), $PRINCIPAL);
    $PRINCIPAL = str_replace("{TABLE_TOTALES_CANTIDAD_NOTA_PROMEDIO_2023}", ($row_totales_notas_2023), $PRINCIPAL);

    return ($PRINCIPAL);
}
function transformDateSp($inputDate) {
    // Create a DateTime object from the input date
    $date = DateTime::createFromFormat('Y-m-d', $inputDate);

    // Format the date in the desired "dd-mm-YYYY" format
    if ($date) {
        return $date->format('d-m-Y');
    } else {
       // return "Invalid date format"; // Handle invalid input
    }
}
function TransformaHoraASegundos($tiempo)
{
    $arreglo_hora = explode(".", $tiempo);

    if (count($arreglo_hora) == 2) {
        $segundos_hora = $arreglo_hora[0] * 3600;
        $segundos_minutos = $arreglo_hora[1] * 60;
        return ($segundos_hora + $segundos_minutos);
    } else {
        $arreglo_hora = explode(",", $tiempo);

        if (count($arreglo_hora) == 2) {
            $segundos_hora = $arreglo_hora[0] * 3600;
            $segundos_minutos = $arreglo_hora[1] * 60;
            return ($segundos_hora + $segundos_minutos);
        } else {
            $segundos_hora = $tiempo * 3600;
            return ($segundos_hora);
        }
    }
}

function VerificaCodImparticion_2023($id_inscripcion) {
    $siguiente_codigo = $id_inscripcion;
    while (true) {
        $existe_array = BuscaIdImparticionFull_2021($siguiente_codigo);
        if ($existe_array[0]->id > 0) {
            $trae_imparticiones_ultima = IMPARTICIONES_traeImparticionesCreadasUltima_2021($id_empresa);
            $codigo_ultima = $trae_imparticiones_ultima;
            $siguiente_codigo = NuevoCodigoImparticion_2021($codigo_ultima);
        } else {
            break;
        }
    }
    echo "<script>alert('OTA Creada: ".$siguiente_codigo."')</script>";
    return $siguiente_codigo;
}

function Tickets_SubirArchivosTickets($FILES, $extension_doc, $prefijo, $ruta, $numero, $nombre_file)
{
    //echo "<br>Tickets_SubirArchivosTickets extension ".$extension_doc."                prefijo ".$prefijo." ruta ".$ruta." numero ".$numero." nombre_file ".$nombre_file;

    $ruta_con_archivo = $ruta . "/" . $prefijo . "." . $extension_doc;
    copy($FILES["archivo"]['tmp_name'], $ruta_con_archivo);
    $nombre_imagen_video = $prefijo . "." . $extension_doc;
    $arreglo[0]          = $ruta_con_archivo; //Ruta Completa
    $arreglo[1]          = $prefijo . "." . $extension_doc; //Nombre del Archivo
    $arreglo[2]          = $ruta . "/" . $prefijo; //Ruta del Objeto sin el index
    if($extension_doc==""){$arreglo[1]="";}
    return ($arreglo);
}
function ListaDocsPorImparticiones_2021($PRINCIPAL, $id_imparticion, $id_empresa)
{

    //print_r($datos_imparticion);
    return ($PRINCIPAL);

}
function ListaProveedoresPorImparticiones_2021($PRINCIPAL, $id_imparticion, $id_empresa)
{

    //print_r($datos_imparticion);
    return ($PRINCIPAL);

}
function ListaCuadroContablePorImparticiones_2021($PRINCIPAL, $id_imparticion, $id_empresa)
{

    //print_r($datos_imparticion);
    return ($PRINCIPAL);

}
function VerificaExtensionFilesAdmin($FILE){
    //echo "<br>file $file";

    /*FUNCION LIMPIA CABECERA*/
    $fileDir = $FILE['tmp_name'];
    if($fileDir!=""){
        $file = fopen($fileDir, "r");
        //Output lines until EOF is reached
        $palabras = array('/JS', '/JavaScript', '/Action', '/js', '/javascript', '/action');
        $encontrada=false;
        while(! feof($file)) {
            $line = fgets($file);
            foreach($palabras as $palabra) {
                if (strpos($line, $palabra) !== false) {
                    $encontrada = true;
                    break;
                }
            }
        }
    }

    if($encontrada){
        echo "<script>alert('Formato de Archivo Incompatible'); location.href='?sw=home_landing_2020';</script>";exit();
    }

    $file_name=$FILE['name'];

    $arreglo_archivo = explode(".", $file_name);
    $extension_archivo = $arreglo_archivo[1];
    //echo "<br>cuenta ".count($arreglo_archivo);		//print_r($arreglo_archivo);		//echo "<br>ex $extension_archivo";




    if(count($arreglo_archivo)<>2){
        echo "<br>archivo invalido con mas de una extension";	 echo "    <script>alert('Formato de Archivo Incompatible'); 			location.href='?sw=home_landing';    </script>";exit();
    }

    if($extension_archivo=="pdf" or $extension_archivo=="PDF" or $extension_archivo=="ppt" or $extension_archivo=="PPT"
        or $extension_archivo=="pptx" or $extension_archivo=="PPTX" or $extension_archivo=="doc" or $extension_archivo=="DOC"
        or $extension_archivo=="docx" or $extension_archivo=="DOCX" or $extension_archivo=="xls" or $extension_archivo=="XLS"
        or $extension_archivo=="xlsx" or $extension_archivo=="XLSX" or $extension_archivo=="jpg" or $extension_archivo=="JPG"
        or $extension_archivo=="jpeg" or $extension_archivo=="JPEG" or $extension_archivo=="zip" or $extension_archivo=="ZIP"
        or $extension_archivo=="png" or $extension_archivo=="PNG" or $extension_archivo=="CSV"  or $extension_archivo=="csv")	{
        //echo "<br>archivo valido";
    } else {
        echo "<br>archivo invalido";	 echo "    <script>alert('Formato de Archivo Incompatible'); 			location.href='?sw=home_landing';    </script>";exit();

    }

}

function ReporteFullSincronico_2023($PRINCIPAL, $id_curso, $id_empresa)
{

    //echo "FormularioCurso2 id_curso $id_curso";
    $datos_curso = DatosCurso_2($id_curso);
    //print_r($datos_curso);
    if ($datos_curso) {
        $id_curso = $datos_curso[0]->id;
    } else {
        $idNuevo = ListasPresenciales_CodigoIdCurso();
        $id_curso = $idNuevo;
    }


    $PRINCIPAL = str_replace("{NOM_CURSO}", ($datos_curso[0]->nombre), $PRINCIPAL);
    $PRINCIPAL = str_replace("{NOMBRE_CURSO_SENCE}", ($datos_curso[0]->nombre_curso_sence), $PRINCIPAL);
    $PRINCIPAL = str_replace("{COD_CURSO}", $id_curso, $PRINCIPAL);
    $PRINCIPAL = str_replace("{DES_CURSO}", ($datos_curso[0]->descripcion), $PRINCIPAL);
    $PRINCIPAL = str_replace("{OBJETIVO_CURSO}", ($datos_curso[0]->objetivo_curso), $PRINCIPAL);
    $PRINCIPAL = str_replace("{NUMERO_HORAS}", ($datos_curso[0]->numero_horas), $PRINCIPAL);
    $PRINCIPAL = str_replace("{CANTIDAD_MAXIMA_PARTICIPANTES}", ($datos_curso[0]->cantidad_maxima_participantes), $PRINCIPAL);
    $PRINCIPAL = str_replace("{TIPO_CURSO}", ($datos_curso[0]->tipo), $PRINCIPAL);
    $PRINCIPAL = str_replace("{CHECKED" . $datos_curso[0]->sence . "}", "checked", $PRINCIPAL);
    $PRINCIPAL = str_replace("{CHECKED" . $datos_curso[0]->cbc . "CBC}", "checked", $PRINCIPAL);
    $PRINCIPAL = str_replace("{PREREQUISITO_CURSO}", ($datos_curso[0]->prerequisito), $PRINCIPAL);
    $PRINCIPAL = str_replace("{COD_SENCE}", ($datos_curso[0]->cod_sence), $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALOR_HORA}", ($datos_curso[0]->valor_hora), $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALOR_HORA_SENCE}", ($datos_curso[0]->valor_hora_sence), $PRINCIPAL);
    $PRINCIPAL = str_replace("{COD_IDENTIFICADOR}", ($datos_curso[0]->numero_identificador), $PRINCIPAL);
    $PRINCIPAL = str_replace("{NUMERO_IDENTIFICADOR}", ($datos_curso[0]->numero_identificador), $PRINCIPAL);
    $PRINCIPAL = str_replace("{CONTENIDOS_CURSO}", ($datos_curso[0]->contenidos_cursos), $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_COD_SENCE}", ($datos_curso[0]->codigosence), $PRINCIPAL);

    if (($datos_curso[0]->imagen)) {
        $PRINCIPAL = str_replace("{BLOQUE_IMAGEN}", file_get_contents("views/capacitacion/curso/bloque_imagen_curso.html"), $PRINCIPAL);
        $PRINCIPAL = str_replace("{URL_IMAGEN}", "" . ($datos_curso[0]->imagen), $PRINCIPAL);
    } else {
        $PRINCIPAL = str_replace("{BLOQUE_IMAGEN}", "", $PRINCIPAL);
    }

    $PRINCIPAL = str_replace("{SELECTED_" . $datos_curso[0]->sence . "}", "selected='selected'", $PRINCIPAL);
    if ($datos_curso) {
        $PRINCIPAL = str_replace("{VALOR_BOTON}", "Editar Curso", $PRINCIPAL);
        $PRINCIPAL = str_replace("{ACTION}", "?sw=edcurso2", $PRINCIPAL);
        $PRINCIPAL = str_replace("{TITULO}", "Formulario Edici&oacute;n de Cursos", $PRINCIPAL);
    } else {
        $PRINCIPAL = str_replace("{VALOR_BOTON}", "Ingresar Curso", $PRINCIPAL);
        $PRINCIPAL = str_replace("{ACTION}", "?sw=adcurso2", $PRINCIPAL);
        $PRINCIPAL = str_replace("{TITULO}", "Formulario Ingreso de Cursos", $PRINCIPAL);
    }
    $PRINCIPAL = str_replace("{NOMBRE_FORMULARIO}", "FormCurso", $PRINCIPAL);
    $PRINCIPAL = str_replace("{OPTIONS_MODALIDAD}", OptionsModalidad($datos_curso[0]->modalidad), $PRINCIPAL);
    $clasificacion = TotalClasificacion($id_empresa);
    foreach ($clasificacion as $cla) {
        if ($cla->id_clasificacion == $datos_curso[0]->clasificacion) {
            $select = "selected='selected'";
        } else {
            $select = "";
        }
        $opcionClasificacion .= '<option value=' . $cla->id_clasificacion . ' ' . $select . ' >' . ($cla->clasificacion) . '</option>';
    }
    $otecs = TraeOtec($id_empresa);
    foreach ($otecs as $otec) {
        if ($cla->rut == $datos_curso[0]->rut_otec) {
            $select2 = "selected";
        } else {
            $select2 = "";
        }
        $opcionOtect .= '<option value=' . $otec->rut . ' ' . $select2 . ' >' . ($otec->nombre) . ' (' . ($otec->rut) . ')</option>';
    }
    $PRINCIPAL = str_replace("{OPTIONS_CLASIFICACION}", $opcionClasificacion, $PRINCIPAL);
    $focos = ListasPresenciales_Obtienefocos($id_empresa);
    $options_focos = "";
    foreach ($focos as $foc) {
        if ($foc->codigo_foco == $datos_curso[0]->id_foco) {
            $options_focos .= "<option value='" . $foc->codigo_foco . "' selected='selected'>" . $foc->descripcion . "</option>";
        } else {
            $options_focos .= "<option value='" . $foc->codigo_foco . "'>" . $foc->descripcion . "</option>";
        }
    }
    $PRINCIPAL = str_replace("{OPTIONS_FOCOS}", ($options_focos), $PRINCIPAL);
    //$programas_bbdd=IMPARTCION_ObtieneProgramasBBDD($id_empresa);
    $programas_bbdd = ListaProgramasDadoFocoDeTabla_2022_data_sinanidacion($id_empresa);

    $options_programas_bbdd = "";
    foreach ($programas_bbdd as $prog_bbdd) {
        if ($prog_bbdd->id_programa == $datos_curso[0]->id_empresa_programa) {
            $options_programas_bbdd .= "<option value='" . $prog_bbdd->id_programa . "' selected='selected'>" . $prog_bbdd->nombre_programa . "</option>";
        } else {
            $options_programas_bbdd .= "<option value='" . $prog_bbdd->id_programa . "'>" . $prog_bbdd->nombre_programa . "</option>";
        }
    }
    $PRINCIPAL = str_replace("{OPTIONS_PROGRAMAS_BBDD_2022_sinanidacion}", ($options_programas_bbdd), $PRINCIPAL);
    $PRINCIPAL = str_replace("{OPTIONS_RUT_OTEC}", $opcionOtect, $PRINCIPAL);
    $PRINCIPAL = str_replace("{ID_CURSO_ENCODEADO}", (Encodear3($datos_curso[0]->id)), $PRINCIPAL);
    $PRINCIPAL = str_replace("{OPTIONS_TIPO_CURSO}", Lista_OptionsGenericoNombreDadoValores("tbl_curso_tipo", $datos_curso[0]->tipo, "nombre", "id"), $PRINCIPAL);
    $PRINCIPAL = str_replace("{SELSENCE" . trim($datos_curso[0]->sence) . "}", "selected='selected'", $PRINCIPAL);
    $PRINCIPAL = str_replace("{SELCBC" . trim($datos_curso[0]->cbc) . "}", "selected='selected'", $PRINCIPAL);
    if ($datos_curso[0]->sence == "si") {
        $PRINCIPAL = str_replace("{STYLE_DISPLAY_COD_SENCE}", '', $PRINCIPAL);
        $PRINCIPAL = str_replace("{STYLE_DISPLAY_NUM_IDEN}", 'style="display:none;"', $PRINCIPAL);
    } else {
        $PRINCIPAL = str_replace("{STYLE_DISPLAY_COD_SENCE}", 'style="display:none;"', $PRINCIPAL);
        $PRINCIPAL = str_replace("{STYLE_DISPLAY_NUM_IDEN}", '', $PRINCIPAL);
    }
    return ($PRINCIPAL);
}


function  CopiaParticipantes_InscripcionPotencial_($id_inscripcion_malla)
{
    $activa=0;
    $Id_inscripciones=Id_cursos_id_Inscripcion_por_id_inscripcion_idMalla_2022($id_inscripcion_malla);
    foreach ( $Id_inscripciones as $u){
        $u->nombre_imparticion=($u->nombre_imparticion);
        //echo "<br>--> ";print_r($u);
        $Usu=DescargaRut_IdMalla_IdInscripcion2022($id_inscripcion_malla);
        BorraUsuariosParaVolveraCrearlos($u->id_inscripcion);
        foreach ($Usu as $us){
            $check1=Verifica_Aprobacion_2023($u->id_curso, $us->rut);
            //echo "<br>check1 $check1";
            $check2=Verifica_Ota_Activa_2023($u->id_curso, $us->rut, $u->id_inscripcion);
            //echo "<br>check2 $check2";
            $check3=Verifica_Ota_Futura_2023($u->id_curso, $us->rut, $u->id_inscripcion);
            //echo "<br>check3 $check3";
            $check4=Verifica_Otra_malla_Futura_2023($us->rut,$id_inscripcion_malla);

            if($check1>0 or $check2>0 or $check3>0){
                echo "<script>alert('El rut ' + '" . $us->rut . "' + ' en el curso ' + '" . $u->id_curso . "' + ' y OTA ' + '" . $u->id_inscripcion . "' + ' ya está aprobado o bien tiene una inscripción activa');</script>";
            } else {

                //cvaso alerta 4.
                if($check4[0]->id>0){
                    //echo "alerta 4 rut ".$us->rut." malla antigua ".$check4[0]->id_malla_inscrita." id malla ".$check4[0]->id_malla_inscribir." curso ".$u->id_curso;
                    echo "<script>alert('El rut ' + '" . $us->rut . "' + ' se modificara su malla a  ' + '" . $check4[0]->id_malla_inscribir . "' + '');</script>";
                    Update_lms_Malla_2023_full($us->rut,$check4[0]->id_malla_inscribir, $check4[0]->id_malla_inscrita, $u->id_curso);
                    //exit();
                }
                Inserta_rel_lms_rut_id_curso_id_inscripcion_2022($us->rut, $u->id_curso, $u->id_inscripcion, $u->nombre_imparticion, $u->fecha_inicio, $u->fecha_termino, $_SESSION["id_empresa"], $u->opcional, $activa, $u->ejecutivo);
            }
        }
    }
}



function NuevoCodigoImparticion_2021($codigo_anterior)
{
    //echo "<br>codigo_anterior $codigo_anterior";
    $cuentaCaracteres = strlen($codigo_anterior);
    //echo "<br>-> cuentaCaracteres $cuentaCaracteres";
    if ($cuentaCaracteres == "10") {
    } else {
        $codigo_anterior = 0;
    }
    $Y = date("Y");
    $M = date("m");
    $Ultimos4Digitos = substr($codigo_anterior, -4);
    $ultimos4DigitosMasUno = round($Ultimos4Digitos) + 1;
    $cuentaCaracteres4Digitos = strlen($ultimos4DigitosMasUno);
    if ($cuentaCaracteres4Digitos == 4) {
        $cuatrodigitosmasuno = $ultimos4DigitosMasUno;
    } elseif ($cuentaCaracteres4Digitos == 3) {
        $cuatrodigitosmasuno = "0" . $ultimos4DigitosMasUno;
    } elseif ($cuentaCaracteres4Digitos == 2) {
        $cuatrodigitosmasuno = "00" . $ultimos4DigitosMasUno;
    } elseif ($cuentaCaracteres4Digitos == 1) {
        $cuatrodigitosmasuno = "000" . $ultimos4DigitosMasUno;
    }
    //echo "<br>-> YYYY $Y";
    //echo "<br>-> MM $M";
    //echo "<br>-> Ultimos4Digitos $Ultimos4Digitos";
    //echo "<br>-> ultimos4DigitosMasUno $ultimos4DigitosMasUno";
    $nuevo_codigo = $Y . $M . $cuatrodigitosmasuno;
    //yyymm0001
    //echo "<br>-> <h4>nuevo_codigo $nuevo_codigo</h4>";
    //exit();
    return $nuevo_codigo;
}


function ListaImparticiones($PRINCIPAL, $id_curso, $Empresa, $excel, $id_modalidad)
{

    $Imparticiones = TraeImparticionEmpresa($id_curso, $Empresa, $id_modalidad);
    //print_r($Imparticiones);
    $perfil = AdminPerfil2021($_SESSION["admin_"]);

    $acceso=AdminPerfilAcceso2024($_SESSION["admin_"]);

    $rows_  = "";
    $rows1_ = "";
    foreach ($Imparticiones as $unico) {

        $muestra_alerta_row = "";
        $display_none_participantes = "";
        if ($perfil == "relator") {
            $vista_id = AdminPerfilVistaImparticion($_SESSION["admin_"], $unico->codigo_inscripcion);

            //echo "<br>dista id<br>";//print_r($vista_id);
            if ($vista_id == 0) {
                continue;
            }
        }
        if ($excel == "1") {
            $rows_ = file_get_contents("views/capacitacion/imparticion/row_listado_excel.html");
        } else {
            if ($unico->modalidad == "1") {

                $PRINCIPAL = str_replace("{DISPLAYNONE_MODALIDAD1}", "display:none !important;", $PRINCIPAL);

                $rows_ = file_get_contents("views/capacitacion/imparticion/row_listado_1.html");
                $rows_ = str_replace("{FECHAS_SESIONES}", $unico->fecha_inicio . " - " . $unico->fecha_termino, $rows_);


            } else {

                if ($unico->modalidad == "2") {
                    //echo "<br>->Modalidad 2";
                    $alerta_horas = DatosImparticiones_SumaHoras($id_curso, $unico->codigo_inscripcion, $unico->tipo_audiencia, $unico->fecha_inicio, $unico->fecha_termino, $unico->hora_inicio, $unico->hora_termino, $unico->modalidad, $unico->numero_horas);
                    //echo " -> alerta $alerta_horas";

                    $alerta_horas_strpos = strpos($alerta_horas, "alert alert-danger");
                    //echo "<br>";
                    //print_r($alerta_horas_strpos);

                    if ($alerta_horas_strpos <> "") {
                        //echo "MUESTRA ALERTA y oculta Participantes";
                        $muestra_alerta_row = $alerta_horas;
                        $display_none_participantes = "display:none!important";
                    }

                }


                $rows_ = file_get_contents("views/capacitacion/imparticion/row_listado.html");
            }

        }

        if($acceso=="133"){
            //echo "<br> numero dias ".$unico->numero_dias;
            if($unico->numero_dias=="1"){
                $boton_cierra_ota="<BR><a href='?sw=listaInscripciones2&aOTA=" . encodear3($unico->codigo_inscripcion) . "' class='btn btn-link'>Abrir OTA</a>";
            } else {
                $boton_cierra_ota="<BR><a href='?sw=listaInscripciones2&cOTA=" . encodear3($unico->codigo_inscripcion) . "' class='btn btn-link'>Cerrar OTA</a>";
            }


        } else {
            $boton_cierra_ota="";
        }

        $rows_ = str_replace("{DISPLAY_NONE_PARTICIPANTES}",    $display_none_participantes, $rows_);
        $rows_ = str_replace("{ALERTA_MAL_CONFIGURADO}",        $muestra_alerta_row, $rows_);
        $rows_ = str_replace("{CERRAR_IMPARTICION}",            $boton_cierra_ota, $rows_);

        $rows_ = str_replace("{CODIGOINCRIPCION}",  $unico->codigo_inscripcion, $rows_);
        $rows_ = str_replace("{NOMBRE_INSCRIPCION}", ($unico->nombre_imparticion), $rows_);
        $rows_ = str_replace("{ID_INSCRIPCION_ENCODEADO}", Encodear3($unico->codigo_inscripcion), $rows_);
        //$arreglo_objetos[0]->codigo_inscripcion=$unico->codigo_inscripcion;
        $arreglo_post["id_empresa"] = $Empresa;
        $arreglo_post["codigo_imparticion_escrito"] = $unico->codigo_inscripcion;
        $arreglo_post["imparticion"] = $unico->codigo_inscripcion;
        //$arreglo_objetos=Lms_Busca_Objetos_Enc_sat_Imparticion($arreglo_post, $Empresa);
        //$rows_=Lms_reporte_MuestraInformeDimEncNota($rows_, $Empresa, $arreglo_objetos, "", "", $excel, $arreglo_post);
        $arreglo_post_objeto = $arreglo_objetos;
        $array_para_enviar_via_url_objetos = serialize($arreglo_post_objeto);
        $array_para_enviar_via_url_objetos = urlencode($array_para_enviar_via_url_objetos);
        //$rows_ = str_replace("{ARREGLO_OBJETO}",$array_para_enviar_via_url_objetos ,$rows_);
        $array_para_enviar_via_url_post = serialize($arreglo_post);
        $array_para_enviar_via_url_post = urlencode($array_para_enviar_via_url_post);
        $rows_ = str_replace("{ARREGLO_POST}", $array_para_enviar_via_url_post, $rows_);
        if ($unico->modalidad == "1") {
            $inscritos = "<strong>" . $unico->inscritos . "</strong> Inscritos";
        } else {
            $inscritos = "<br><strong>" . $unico->inscritos . "</strong> Inscritos";
        }

        $inscritos_numero = $unico->inscritos;
        $cupos = "<br><strong>" . $unico->cupos . "</strong> Cupos";
        $cupos_numero = $unico->cupos;
        $chequeados = "<strong>" . $unico->chequeados . "</strong> Asistentes";
        $chequeados_numero = $unico->chequeados;
        if ($unico->preinscritos > 0) {
            $preinscritos = "<br><strong>" . $unico->preinscritos . "</strong> Preinscritos";
            $preinscritos_numero = $unico->preinscritos;
        } else {
            $preinscritos = "<br><strong>0</strong> Preinscritos";
            $preinscritos_numero = 0;
        }
        if ($unico->postulantes > 0) {
            $postulantes = "<br><strong>" . $unico->postulantes . "</strong> Postulantes";
            $postulantes_numero = $unico->postulantes;
        } else {
            $postulantes = "<br><strong>0</strong> Postulantes";
            $postulantes_numero = 0;
        }
        if ($unico->asistentes > 0) {
            $asistentes = "<strong>" . $unico->asistentes . "</strong> Asistentes";
            $asistentes_numero = $unico->asistentes;
        } else {
            $asistentes = "<strong>0</strong> Asistentes";
            $asistentes_numero = 0;
        }
        if ($unico->modalidad == 1) {
            $modalidad_curso = "ELEARNING";
        } else {
            $modalidad_curso = "PRESENCIAL";
        }
        $rows_ = str_replace("{MODALIDAD_CURSO}", $modalidad_curso, $rows_);

        //echo "<br>codigo_inscripcion $unico->codigo_inscripcion,";
        $Prog_Foco = data_rel_id_inscripcion_curso_groupby_id_imparticion_data($unico->codigo_inscripcion);
        //print_r($Prog_Foco);


        $rows_ = str_replace("{FOCO}", $Prog_Foco[0]->foco, $rows_);
        $rows_ = str_replace("{PROGRAMA}", $Prog_Foco[0]->programa, $rows_);
        $rows_ = str_replace("{NUM_PARTICIPANTES}", $unico->num_participantes, $rows_);
        $rows_ = str_replace("{INSCRITOS}", $inscritos, $rows_);
        $rows_ = str_replace("{INSCRITOS_NUMERO}", $inscritos_numero, $rows_);
        $rows_ = str_replace("{ASISTENTES}", $asistentes, $rows_);
        $rows_ = str_replace("{ASISTENTES_NUMERO}", $asistentes_numero, $rows_);
        $rows_ = str_replace("{PREINSCRITOS}", $preinscritos, $rows_);
        $rows_ = str_replace("{PREINSCRITOS_NUMERO}", $preinscritos_numero, $rows_);
        $rows_ = str_replace("{POSTULANTES}", $postulantes, $rows_);
        $rows_ = str_replace("{POSTULANTES_NUMERO}", $postulantes_numero, $rows_);
        $rows_ = str_replace("{CUPOS}", $cupos, $rows_);
        $rows_ = str_replace("{CUPOS_NUMERO}", $cupos_numero, $rows_);
        $rows_ = str_replace("{CHEQUEADOS}", $chequeados, $rows_);
        $rows_ = str_replace("{CHEQUEADOS_NUMERO}", $chequeados_numero, $rows_);
        if ($unico->modalidad == 1) {
            $tipo = "Elearning";
        }
        if ($unico->modalidad == 2) {
            $tipo = "Presencial";
        }
        $rows_ = str_replace("{TIPO}", $tipo, $rows_);
        $rows_ = str_replace("{DESCARGA_PARTICIPANTES}", "<a href='?sw=descargarInscritosPorImpart_2021&ci=" . encodear3($unico->codigo_inscripcion) . "' title='Descargar Participantes a Excel'><i class='fas fa-download'></i></a>", $rows_);
        $rows_ = str_replace("{DESCARGA_ASISTENTES}", "<a href='?sw=descargarInscritosPorImpart_2021&ci=" . encodear3($unico->codigo_inscripcion) . "&asistentes=1' title='Descargar Participantes a Excel'><i class='fas fa-download'></i></a>", $rows_);
        $rows_ = str_replace("{CLASIFICACION}", $unico->clasificacion, $rows_);
        $rows_ = str_replace("{CURSO}", ($unico->nombre), $rows_);
        $rows_ = str_replace("{ID_CURSO}", $unico->id_curso, $rows_);


        $rows_ = str_replace("{i}", Encodear3($unico->id_curso), $rows_);
        if ($id_modalidad == "3") {
            $cupos = "Cupos: " . $unico->cupos;
            $rows_ = str_replace("{DIRECCION}", ($cupos), $rows_);
        }
        $rows_ = str_replace("{DIRECCION}", ($unico->direccion), $rows_);
        $rows_ = str_replace("{CIUDAD}", ($unico->nombre_proveedor), $rows_);
        $rows_ = str_replace("{EJECUTIVO}", $unico->nombre_ejecutivo . "", $rows_);
        $rows_ = str_replace("{CUPOS}", ($unico->cupos), $rows_);
        //echo $unico->numero_horas;echo "<br>";
        $segundos = TransformaHoraASegundos($unico->numero_horas);
        //echo $segundos;echo " segundos del curso <br>";
        //$rows_ = str_replace("{NUMSESIONES}",($unico->sesiones),$rows_);
        $lista_sesiones_reales = "";
        $lista_sesiones_reales_XLS = "";
        $lista_objetos_reales = "";

        $rows_ = str_replace("{DETALLE_OBJETOS}", ($lista_objetos_reales), $rows_);
        $Incripcion2 = Total_Inscripciones($Empresa, $unico->codigo_inscripcion);
        $total_html2 = "";
        $rows1_ = "";
        $cuenta = "";
        $relator = "";
        $nombre_relator = "";
        $lista_sesiones = "";
        $Sesiones = DatosImparticiones_Sesiones_data_2021($unico->codigo_inscripcion);
        foreach ($Sesiones as $UniSes) {
            $lista_sesiones .= "" . formatearFechaSmall($UniSes->fecha) . " " . $UniSes->hora_desde . " - " . $UniSes->hora_hasta . "<br>";
        }

        if ($unico->tipo_audiencia == "fecha_inicio_termino") {

            if ($unico->id_audiencia <> "") {
                $relator = TraeRelatores2021porId($unico->id_audiencia);
                $nombre_relator = $relator[0]->nombre . " (" . $relator[0]->tipo . ")";
            }
            $lista_sesiones .= "" . formatearFechaSmall($unico->fecha_inicio) . " " . $unico->hora_inicio . " <br>" . formatearFechaSmall($unico->fecha_termino) . " " . $unico->hora_termino . "<br>$nombre_relator
							";
        }
        //echo "<br>-->".$unico->tipo_audiencia;
        $rows_ = str_replace("{FECHAS_SESIONES}", $lista_sesiones, $rows_);
        $rows_ = str_replace("{TABLA}", $cab . $total_html2 . "</tbody></table>", $rows_);
        $fecha_actual = date("Y-m-d");
        $rows_ = str_replace("{ESTADO}", $estado, $rows_);
        $rows_ = str_replace("{ESTADONOMBRE}", $estadonom, $rows_);
        $EstadoImparticion2022 = EstadoImparticion2022($unico->fecha_inicio, $unico->fecha_termino);
        $rows_ = str_replace("{ESTADO_EJECUCION}", $EstadoImparticion2022[0], $rows_);

        $total_html .= $rows_;
        if ($excel == 1) {
            $row_excel .= $unico->id_curso . ";";
            $row_excel .= $unico->nombre . ";";
            $row_excel .= $modalidad_curso . ";";
            $row_excel .= $unico->codigo_inscripcion . ";";
            $row_excel .= $EstadoImparticion2022[1] . ";";
            $row_excel .= $unico->fecha_inicio . ";";
            $row_excel .= $unico->fecha_termino . ";";
            $row_excel .= $unico->nombre_ejecutivo . ";";

            $row_excel .= $unico->nombre_proveedor . ";";
            $row_excel .= $unico->direccion . ";";

            $row_excel .= $asistentes_numero . ";";
            $row_excel .= $inscritos_numero . "";
            $row_excel .= "\r\n";
            //$EstadoImparticion2022[1];
            echo $row_excel;
        }
    }

    $PRINCIPAL = str_replace("{REGISTROS" . $registros_pagina . "}", "selected", $PRINCIPAL);
    $PRINCIPAL = str_replace("{PAGINACION}", $paginacion, $PRINCIPAL);
    $PRINCIPAL = str_replace("{RANGO_FECHAS}", "", $PRINCIPAL);
    $PRINCIPAL = str_replace("{BCODIGO}", $codigo, $PRINCIPAL);
    $PRINCIPAL = str_replace("{BFECHA1}", $fecha1, $PRINCIPAL);
    $PRINCIPAL = str_replace("{BFECHA2}", $fecha2, $PRINCIPAL);
    $PRINCIPAL = str_replace("{RESUMEN}", "Hay un total de " . $registros_total . " Inscripciones", $PRINCIPAL);
    $PRINCIPAL = str_replace("{ID_EMPRESA}", $Empresa, $PRINCIPAL);
    $PRINCIPAL = str_replace("{ROW_LISTADO}", ($total_html), $PRINCIPAL);
    return ($PRINCIPAL);
}

function ListaImparticiones_malla($PRINCIPAL, $id_malla, $Empresa, $excel, $id_modalidad)
{
    $Imparticiones = BuscaIdImparticiondadoMalla($id_malla, $id_empresa);
    //echo "<pre>";print_r($Imparticiones);echo "</pre>";
    $perfil = AdminPerfil2021($_SESSION["admin_"]);
    //print_r($_SESSION);  				//echo "perfil $perfil";
    $rows_ = "";
    $rows1_ = "";
    foreach ($Imparticiones as $unico) {
        $muestra_alerta_row = "";
        $display_none_participantes = "";
        if ($perfil == "relator") {
            $vista_id = AdminPerfilVistaImparticion($_SESSION["admin_"], $unico->codigo_inscripcion);
            //echo "<br>dista id<br>";//print_r($vista_id);
            if ($vista_id > 0) {
                //echo "->muestra";
            } else {
                continue;
            }
        }
        if ($excel == "1") {
            $rows_ = file_get_contents("views/capacitacion/imparticion/row_listado_excel.html");
        } else {
                $PRINCIPAL = str_replace("{DISPLAYNONE_MODALIDAD1}", "display:none !important;", $PRINCIPAL);
                $rows_ = file_get_contents("views/capacitacion/imparticion/row_listado_1_mallas.html");
                $rows_ = str_replace("{FECHAS_SESIONES}", $unico->fecha_inicio . " - " . $unico->fecha_termino, $rows_);
        }
        $rows_ = str_replace("{DISPLAY_NONE_PARTICIPANTES}", $display_none_participantes, $rows_);
        $rows_ = str_replace("{ALERTA_MAL_CONFIGURADO}", $muestra_alerta_row, $rows_);
        $rows_ = str_replace("{CODIGOINCRIPCION}", $unico->codigo_inscripcion, $rows_);
        $rows_ = str_replace("{ID_INSCRIPCION_ENCODEADO}", Encodear3($unico->codigo_inscripcion), $rows_);
        $rows_ = str_replace("{NOMBRE_IMPARTICION_MALLA}", ($unico->nombre_imparticion), $rows_);
        $rows_ = str_replace("{ID_IMPARTICION_MALLA}", ($unico->id_malla), $rows_);
        $rows_ = str_replace("{ID_IMPARTICION_MALLA_ENC}", Encodear3($unico->id_malla), $rows_);
        $rows_ = str_replace("{ID_IMPARTICION_ENC}", Encodear3($unico->id_inscripcion), $rows_);
        $rows_ = str_replace("{ID_PROGRAMA_ENCODEAR}", Encodear3(IdProgramaIdMalla_2022($unico->id_malla)), $rows_);

        $id_programa=IdProgramaIdMalla_2022($unico->id_malla);
        $malla_array=DatosMalla_2022($unico->id_malla);
        $programa_adday=DatosPrograma_2022($id_programa);
         //   echo "<br>-> id_programa $id_programa";
        $PRINCIPAL = str_replace("{NOMBRE_MALLA}",      ($malla_array[0]->nombre), $PRINCIPAL);
        $PRINCIPAL = str_replace("{NOMBRE_PROGRAMA}",   ($programa_adday[0]->nombre_programa), $PRINCIPAL);

        //echo "<br>-> ejecutivo ".$unico->ejecutivo;
        $ejecutivo_relator=TraeDatosEjecutivos($unico->ejecutivo);
        $rows_ = str_replace("{EJECUTIVO_MALLA}", ( $ejecutivo_relator[0]->nombre), $rows_);
        $arreglo_post["id_empresa"] = $Empresa;
        $arreglo_post["codigo_imparticion_escrito"] = $unico->codigo_inscripcion;
        $arreglo_post["imparticion"] = $unico->codigo_inscripcion;
        $arreglo_post_objeto = $arreglo_objetos;
        $array_para_enviar_via_url_objetos = serialize($arreglo_post_objeto);
        $array_para_enviar_via_url_objetos = urlencode($array_para_enviar_via_url_objetos);
        $array_para_enviar_via_url_post = serialize($arreglo_post);
        $array_para_enviar_via_url_post = urlencode($array_para_enviar_via_url_post);
        $rows_ = str_replace("{ARREGLO_POST}", $array_para_enviar_via_url_post, $rows_);
        if ($unico->modalidad == "1") {
            $inscritos = "<strong>" . $unico->participantes . "</strong> Inscritos";
        } else {
            $inscritos = "<strong>" . $unico->participantes . "</strong> Inscritos";
        }

        $inscritos_numero = $unico->inscritos;


        $cupos = "<br><strong>" . $unico->cupos . "</strong> Cupos";
        $cupos_numero = $unico->cupos;
        $chequeados = "<strong>" . $unico->chequeados . "</strong> Asistentes";
        $chequeados_numero = $unico->chequeados;
        if ($unico->preinscritos > 0) {
            $preinscritos = "<br><strong>" . $unico->preinscritos . "</strong> Preinscritos";
            $preinscritos_numero = $unico->preinscritos;
        } else {
            $preinscritos = "<br><strong>0</strong> Preinscritos";
            $preinscritos_numero = 0;
        }
        if ($unico->postulantes > 0) {
            $postulantes = "<br><strong>" . $unico->postulantes . "</strong> Postulantes";
            $postulantes_numero = $unico->postulantes;
        } else {
            $postulantes = "<br><strong>0</strong> Postulantes";
            $postulantes_numero = 0;
        }
        if ($unico->asistentes > 0) {
            $asistentes = "<strong>" . $unico->asistentes . "</strong> Asistentes";
            $asistentes_numero = $unico->asistentes;
        } else {
            $asistentes = "<strong>0</strong> Asistentes";
            $asistentes_numero = 0;
        }
        if ($unico->modalidad == 1) {
            $modalidad_curso = "ELEARNING";
        } else {
            $modalidad_curso = "PRESENCIAL";
        }
        $rows_ = str_replace("{MODALIDAD_CURSO}", $modalidad_curso, $rows_);
        //echo "<br>codigo_inscripcion $unico->codigo_inscripcion,";
        $Prog_Foco = data_rel_id_inscripcion_curso_groupby_id_imparticion_data($unico->codigo_inscripcion);
        //print_r($Prog_Foco);
        $rows_ = str_replace("{FOCO}", $Prog_Foco[0]->foco, $rows_);
        $rows_ = str_replace("{PROGRAMA}", $Prog_Foco[0]->programa, $rows_);
        $rows_ = str_replace("{NUM_PARTICIPANTES}", $unico->num_participantes, $rows_);
        $rows_ = str_replace("{INSCRITOS}", $inscritos, $rows_);
        $rows_ = str_replace("{INSCRITOS_NUMERO}", $inscritos_numero, $rows_);
        $rows_ = str_replace("{ASISTENTES}", $asistentes, $rows_);
        $rows_ = str_replace("{ASISTENTES_NUMERO}", $asistentes_numero, $rows_);
        $rows_ = str_replace("{PREINSCRITOS}", $preinscritos, $rows_);
        $rows_ = str_replace("{PREINSCRITOS_NUMERO}", $preinscritos_numero, $rows_);
        $rows_ = str_replace("{POSTULANTES}", $postulantes, $rows_);
        $rows_ = str_replace("{POSTULANTES_NUMERO}", $postulantes_numero, $rows_);
        $rows_ = str_replace("{CUPOS}", $cupos, $rows_);
        $rows_ = str_replace("{CUPOS_NUMERO}", $cupos_numero, $rows_);
        $rows_ = str_replace("{CHEQUEADOS}", $chequeados, $rows_);
        $rows_ = str_replace("{CHEQUEADOS_NUMERO}", $chequeados_numero, $rows_);
        if ($unico->modalidad == 1) {
            $tipo = "Elearning";
        }
        if ($unico->modalidad == 2) {
            $tipo = "Presencial";
        }
        $rows_ = str_replace("{TIPO}", $tipo, $rows_);
        $rows_ = str_replace("{DESCARGA_PARTICIPANTES}", "<a href='?sw=descargarInscritosPorImpart_2021&ci=" . encodear3($unico->codigo_inscripcion) . "' title='Descargar Participantes a Excel'><i class='fas fa-download'></i></a>", $rows_);
        $rows_ = str_replace("{DESCARGA_ASISTENTES}", "<a href='?sw=descargarInscritosPorImpart_2021&ci=" . encodear3($unico->codigo_inscripcion) . "&asistentes=1' title='Descargar Participantes a Excel'><i class='fas fa-download'></i></a>", $rows_);
        $rows_ = str_replace("{CLASIFICACION}", $unico->clasificacion, $rows_);
        $rows_ = str_replace("{CURSO}", $unico->nombre, $rows_);
        $rows_ = str_replace("{ID_CURSO}", $unico->id_curso, $rows_);


        $rows_ = str_replace("{i}", Encodear3($unico->id_curso), $rows_);
        if ($id_modalidad == "3") {
            $cupos = "Cupos: " . $unico->cupos;
            $rows_ = str_replace("{DIRECCION}", ($cupos), $rows_);
        }
        $rows_ = str_replace("{DIRECCION}", ($unico->direccion), $rows_);
        $rows_ = str_replace("{CIUDAD}", ($unico->nombre_proveedor), $rows_);
        $rows_ = str_replace("{EJECUTIVO}", $unico->nombre_ejecutivo . "", $rows_);
        $rows_ = str_replace("{CUPOS}", ($unico->cupos), $rows_);
        //echo $unico->numero_horas;echo "<br>";
        $segundos = TransformaHoraASegundos($unico->numero_horas);
        //echo $segundos;echo " segundos del curso <br>";
        //$rows_ = str_replace("{NUMSESIONES}",($unico->sesiones),$rows_);
        $lista_sesiones_reales = "";
        $lista_sesiones_reales_XLS = "";
        $lista_objetos_reales = "";

        $rows_ = str_replace("{DETALLE_OBJETOS}", ($lista_objetos_reales), $rows_);
        $Incripcion2 = Total_Inscripciones($Empresa, $unico->codigo_inscripcion);
        $total_html2 = "";
        $rows1_ = "";
        $cuenta = "";
        $relator = "";
        $nombre_relator = "";
        $lista_sesiones = "";
        $Sesiones = DatosImparticiones_Sesiones_data_2021($unico->codigo_inscripcion);
        foreach ($Sesiones as $UniSes) {
            $lista_sesiones .= "" . formatearFechaSmall($UniSes->fecha) . " " . $UniSes->hora_desde . " - " . $UniSes->hora_hasta . "<br>";
        }

        if ($unico->tipo_audiencia == "fecha_inicio_termino") {

            if ($unico->id_audiencia <> "") {
                $relator = TraeRelatores2021porId($unico->id_audiencia);
                $nombre_relator = $relator[0]->nombre . " (" . $relator[0]->tipo . ")";
            }
            $lista_sesiones .= "" . formatearFechaSmall($unico->fecha_inicio) . " " . $unico->hora_inicio . " <br>" . formatearFechaSmall($unico->fecha_termino) . " " . $unico->hora_termino . "<br>$nombre_relator
							";
        }
        //echo "<br>-->".$unico->tipo_audiencia;
        $rows_ = str_replace("{FECHAS_SESIONES}", $lista_sesiones, $rows_);
        $rows_ = str_replace("{TABLA}", $cab . $total_html2 . "</tbody></table>", $rows_);
        $fecha_actual = date("Y-m-d");
        $rows_ = str_replace("{ESTADO}", $estado, $rows_);
        $rows_ = str_replace("{ESTADONOMBRE}", $estadonom, $rows_);
        $EstadoImparticion2022 = EstadoImparticion2022($unico->fecha_inicio, $unico->fecha_termino);
        $rows_ = str_replace("{ESTADO_EJECUCION}", $EstadoImparticion2022[0], $rows_);

        $total_html .= $rows_;
        if ($excel == 1) {
            $row_excel .= $unico->id_curso . ";";
            $row_excel .= $unico->nombre . ";";
            $row_excel .= $modalidad_curso . ";";
            $row_excel .= $unico->codigo_inscripcion . ";";
            $row_excel .= $EstadoImparticion2022[1] . ";";
            $row_excel .= $unico->fecha_inicio . ";";
            $row_excel .= $unico->fecha_termino . ";";
            $row_excel .= $unico->nombre_ejecutivo . ";";

            $row_excel .= $unico->nombre_proveedor . ";";
            $row_excel .= $unico->direccion . ";";

            $row_excel .= $asistentes_numero . ";";
            $row_excel .= $inscritos_numero . "";
            $row_excel .= "\r\n";
            //$EstadoImparticion2022[1];
            echo $row_excel;
        }
    }

    $PRINCIPAL = str_replace("{REGISTROS" . $registros_pagina . "}", "selected", $PRINCIPAL);
    $PRINCIPAL = str_replace("{PAGINACION}", $paginacion, $PRINCIPAL);
    $PRINCIPAL = str_replace("{RANGO_FECHAS}", "", $PRINCIPAL);
    $PRINCIPAL = str_replace("{BCODIGO}", $codigo, $PRINCIPAL);
    $PRINCIPAL = str_replace("{BFECHA1}", $fecha1, $PRINCIPAL);
    $PRINCIPAL = str_replace("{BFECHA2}", $fecha2, $PRINCIPAL);
    $PRINCIPAL = str_replace("{RESUMEN}", "Hay un total de " . $registros_total . " Inscripciones", $PRINCIPAL);
    $PRINCIPAL = str_replace("{ID_EMPRESA}", $Empresa, $PRINCIPAL);
    $PRINCIPAL = str_replace("{ROW_LISTADO}", ($total_html), $PRINCIPAL);
    return ($PRINCIPAL);
}

function EstadoImparticion2022($fecha_inicio, $fecha_termino)
{

    $hoy = date("Y-m-d");
    if ($hoy > $fecha_termino) {
        $icono_estado = "
		Finalizada 
			<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-calendar-check' viewBox='0 0 16 16'>
			  <path d='M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z'/>
			  <path d='M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z'/>
			</svg>		
		";
        $texto_estado = "Finalizada";
    } elseif ($hoy <= $fecha_termino and $hoy >= $fecha_inicio) {
        $icono_estado = "
		Ejecutando
			<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-person-workspace' viewBox='0 0 16 16'>
			  <path d='M4 16s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H4Zm4-5.95a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z'/>
			  <path d='M2 1a2 2 0 0 0-2 2v9.5A1.5 1.5 0 0 0 1.5 14h.653a5.373 5.373 0 0 1 1.066-2H1V3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v9h-2.219c.554.654.89 1.373 1.066 2h.653a1.5 1.5 0 0 0 1.5-1.5V3a2 2 0 0 0-2-2H2Z'/>
			</svg>
		 ";
        $texto_estado = "Ejecutando";
    } else {
        $icono_estado = "
		Programada
			<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-alarm' viewBox='0 0 16 16'>
			  <path d='M8.5 5.5a.5.5 0 0 0-1 0v3.362l-1.429 2.38a.5.5 0 1 0 .858.515l1.5-2.5A.5.5 0 0 0 8.5 9V5.5z'/>
			  <path d='M6.5 0a.5.5 0 0 0 0 1H7v1.07a7.001 7.001 0 0 0-3.273 12.474l-.602.602a.5.5 0 0 0 .707.708l.746-.746A6.97 6.97 0 0 0 8 16a6.97 6.97 0 0 0 3.422-.892l.746.746a.5.5 0 0 0 .707-.708l-.601-.602A7.001 7.001 0 0 0 9 2.07V1h.5a.5.5 0 0 0 0-1h-3zm1.038 3.018a6.093 6.093 0 0 1 .924 0 6 6 0 1 1-.924 0zM0 3.5c0 .753.333 1.429.86 1.887A8.035 8.035 0 0 1 4.387 1.86 2.5 2.5 0 0 0 0 3.5zM13.5 1c-.753 0-1.429.333-1.887.86a8.035 8.035 0 0 1 3.527 3.527A2.5 2.5 0 0 0 13.5 1z'/>
			</svg>		
		 ";
        $texto_estado = "Programada";
    }

    $arreglo[0] = $icono_estado;
    $arreglo[1] = $texto_estado;
    return $arreglo;


}

function FormularioImparticion($PRINCIPAL, $id_empresa, $id_imparticion)
{



    if (!$id_imparticion) {
        //si no hay codigo de imparticion creo uno
        $trae_imparticiones = IMPARTICIONES_traeImparticionesCreadas($id_empresa);
        $trae_imparticiones_ultima = IMPARTICIONES_traeImparticionesCreadasUltima_2021($id_empresa);
        $codigo_ultima = $trae_imparticiones_ultima;
        $siguiente_codigo = NuevoCodigoImparticion_2021($codigo_ultima);


        //traigo la ultima imparticion
        if ($trae_imparticiones) {
            //$codigo_imparticion=$trae_imparticiones[0]->prefijo."_impart_".(count($trae_imparticiones)+1);
            $codigo_imparticion = $trae_imparticiones[0]->prefijo . "_impart_" . $siguiente_codigo;
            $codigo_imparticion = $siguiente_codigo;
        } else {
            $datos_empresa = DatosEmpresa($id_empresa);
            //$codigo_imparticion=$datos_empresa[0]->prefijo."_impart_".(count($trae_imparticiones)+1);
            $codigo_imparticion = $datos_empresa[0]->prefijo . "_impart_" . $siguiente_codigo;
            $codigo_imparticion = $siguiente_codigo;
        }
    } else {
        $codigo_imparticion = $id_imparticion;
    }

    //echo " codigo_imparticion ".$codigo_imparticion." id imparticion ".$id_imparticion;
    //$datos_imparticion=DatosInscripcionImparticiones($id_imparticion, $id_empresa);
    $datos_imparticion = DatosInscripcionImparticionesV2($id_imparticion, $id_empresa);
    // print_r($datos_imparticion);

    $cursos = TraeCursos("-1", $id_empresa, "");
    $option_cursos = "";
    foreach ($cursos as $curso) {
        //if($arreglo_post["curso"]==$curso->id){
        if ($datos_imparticion[0]->id_curso == $curso->id) {
            $option_cursos .= '<option value="' . $curso->id . '" selected>' . $curso->id . ' - ' . $curso->nombre . '</option>';
        } else {
            $option_cursos .= '<option value="' . $curso->id . '">' . $curso->id . ' - ' . $curso->nombre . '</option>';
        }
    }
    $PRINCIPAL = str_replace("{OPCIONES_CURSOS}", ($option_cursos), $PRINCIPAL);

    //$ejecutivos=TraeEjecutivosPorEmpresa($id_empresa);
    $ejecutivos = TraeEjecutivos2021($id_empresa);
    $options_ejecutivos = "";
    foreach ($ejecutivos as $ejec) {

        if ($datos_imparticion[0]->ejecutivo == $ejec->rut) {
            $checked = " selected ";
        } else {
            $checked = "  ";
        }
        $options_ejecutivos .= '<option value="' . $ejec->rut . '" ' . $checked . '>' . $ejec->nombre . ' - ' . $ejec->rut . '</option>';
    }
    $PRINCIPAL = str_replace("{OPTIONS_EJECUTIVOS}", ($options_ejecutivos), $PRINCIPAL);

    $ejecutivos_interno = TraeEjecutivos2022_tipo($id_empresa, "interno");
    $options_ejecutivos_interno = "";
    foreach ($ejecutivos_interno as $ejec) {

        if ($datos_imparticion[0]->ejecutivo == $ejec->rut) {
            $checked = " selected ";
        } else {
            $checked = "  ";
        }
        $options_ejecutivos_interno .= '<option value="' . $ejec->rut . '" ' . $checked . '>' . $ejec->nombre . ' - ' . $ejec->rut . '</option>';
    }
    $PRINCIPAL = str_replace("{OPTIONS_EJECUTIVOS_INTERNO}", ($options_ejecutivos_interno), $PRINCIPAL);


    $ejecutivos_externo = TraeEjecutivos2022_tipo($id_empresa, "externo");
    $options_ejecutivos_externo = "";
    foreach ($ejecutivos_externo as $ejec) {

        if ($datos_imparticion[0]->archivo == $ejec->rut) {
            $checked = " selected ";
        } else {
            $checked = "  ";
        }
        $options_ejecutivos_externo .= '<option value="' . $ejec->rut . '" ' . $checked . '>' . $ejec->nombre . ' - ' . $ejec->rut . '</option>';
    }
    $PRINCIPAL = str_replace("{OPTIONS_EJECUTIVOS_EXTERNO}", ($options_ejecutivos_externo), $PRINCIPAL);


    $otecs = TraeOtec2021($id_empresa);
    $options_otec = "";
    foreach ($otecs as $otec) {
        if ($datos_imparticion[0]->ciudad == $otec->rut) {
            $checked = " selected ";
        } else {
            $checked = "  ";
        }
        $options_otec .= '<option value="' . $otec->rut . '" ' . $checked . '>' . ($otec->nombre) . ' - ' . $otec->rut . '</option>';
    }
    $PRINCIPAL = str_replace("{OPTIONS_OTEC}", ($options_otec), $PRINCIPAL);

    $direcciones = TraeDirecciones2021($id_empresa);
    $options_direcciones = "";
    foreach ($direcciones as $direccion) {
        if ($datos_imparticion[0]->direccion == $direccion->nombre) {
            $checkedDireccion = " selected ";
        } else {
            $checkedDireccion = "  ";
        }
        $options_direcciones .= '<option value="' .$direccion->nombre . '" ' . $checkedDireccion . '>' . $direccion->nombre . '</option>';
    }

    //echo $datos_imparticion[0]->id_modalidad_imparticion;
    if($datos_imparticion[0]->id_modalidad_imparticion=="1"){$select_modalidad_01=" selected ";}
    if($datos_imparticion[0]->id_modalidad_imparticion=="2"){$select_modalidad_02=" selected ";}
    if($datos_imparticion[0]->id_modalidad_imparticion=="3"){$select_modalidad_03=" selected ";}
    if($datos_imparticion[0]->id_modalidad_imparticion=="4"){$select_modalidad_04=" selected ";}
    if($datos_imparticion[0]->id_modalidad_imparticion=="5"){$select_modalidad_05=" selected ";}
    if($datos_imparticion[0]->id_modalidad_imparticion=="6"){$select_modalidad_06=" selected ";}
    if($datos_imparticion[0]->id_modalidad_imparticion=="7"){$select_modalidad_07=" selected ";}

    $PRINCIPAL = str_replace("{SELECT_MODALIDAD_01}", ($select_modalidad_01), $PRINCIPAL);
    $PRINCIPAL = str_replace("{SELECT_MODALIDAD_02}", ($select_modalidad_02), $PRINCIPAL);
    $PRINCIPAL = str_replace("{SELECT_MODALIDAD_03}", ($select_modalidad_03), $PRINCIPAL);
    $PRINCIPAL = str_replace("{SELECT_MODALIDAD_04}", ($select_modalidad_04), $PRINCIPAL);
    $PRINCIPAL = str_replace("{SELECT_MODALIDAD_05}", ($select_modalidad_05), $PRINCIPAL);
    $PRINCIPAL = str_replace("{SELECT_MODALIDAD_06}", ($select_modalidad_06), $PRINCIPAL);
    $PRINCIPAL = str_replace("{SELECT_MODALIDAD_07}", ($select_modalidad_07), $PRINCIPAL);

    $PRINCIPAL = str_replace("{VALUE_DIRECCION}", ($options_direcciones), $PRINCIPAL);

    if($datos_imparticion[0]->valor_curso=="SI")    {   $select_costos_asociados_SI=" selected ";}
    if($datos_imparticion[0]->valor_curso=="NO")    {   $select_costos_asociados_NO=" selected ";}

    $PRINCIPAL = str_replace("{COSTOS_ASOCIADOS_SI}", ($select_costos_asociados_SI), $PRINCIPAL);
    $PRINCIPAL = str_replace("{COSTOS_ASOCIADOS_NO}", ($select_costos_asociados_NO), $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALOR_BOTON}", "Ingresar Imparticion", $PRINCIPAL);
    //Veo cuantos usuarios hay en la tabla inscripcion_usuario, para colocar la cantidad
    $usuarios_por_inscripcion = IMPARTICION_UsuariosPorInscripcion($codigo_imparticion, $id_empresa);
    $PRINCIPAL = str_replace("{TOTAL_USUARIOS_POR_INSCRIPCION}", count($usuarios_por_inscripcion), $PRINCIPAL);
    //Postulantes por Imparticion, tabla tbl_inscripcion_postulantes
    $postulantes_por_inscripcion = IMPARTICION_PostulantesPorInscripcion($codigo_imparticion, $id_empresa);
    $PRINCIPAL = str_replace("{TOTAL_POSTULANTES_POR_INSCRIPCION}", count($postulantes_por_inscripcion), $PRINCIPAL);

    $nombre_Curso_array = BuscaNombreCursoDadoID($datos_imparticion[0]->id_curso);
    $nombre_curso = $nombre_Curso_array[0]->nombre;

    $PRINCIPAL = str_replace("{VALUE_COD_IMPARTICION_FECHA}", $datos_imparticion[0]->fecha_inicio, $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_COD_IMPARTICION_FECHA_HASTA}", $datos_imparticion[0]->fecha_termino, $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_COD_IMPARTICION_HORA_DESDE}", $datos_imparticion[0]->hora_inicio, $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_COD_IMPARTICION_HORA_HASTA}", $datos_imparticion[0]->hora_termino, $PRINCIPAL);


    $PRINCIPAL = str_replace("{SCRIPT_ACTIVA_TIPO_HORARIO}", $script_activa_radio_horarios, $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_COD_IMPARTICION_OBSERVACION}", $datos_imparticion[0]->observacion, $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_COD_IMPARTICION_OBSERVACION_NEW2}", $datos_imparticion[0]->observacion, $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_COD_IMPARTICION_FECHA_HASTA}", $datos_imparticion[0]->observacion, $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_NOMBRE}",  ($datos_imparticion[0]->nombre), $PRINCIPAL);
    $PRINCIPAL = str_replace("{NOMBRE_CURSO}",  ($nombre_Curso_array[0]->nombre), $PRINCIPAL);

    if($_GET["dup"]==1){
 $trae_imparticiones = IMPARTICIONES_traeImparticionesCreadas($id_empresa);
        $trae_imparticiones_ultima = IMPARTICIONES_traeImparticionesCreadasUltima_2021($id_empresa);
        //$codigo_ultima=$trae_imparticiones_ultima[0]->codigo_inscripcion;
        //$codigo_ultima=$trae_imparticiones_ultima[0]->id;

        // echo "<br>trae_imparticiones_ultima $trae_imparticiones_ultima<br>";
        $codigo_ultima = $trae_imparticiones_ultima;
        ///////////////$arreglo=explode("_", $codigo_ultima);
        //print_r($arreglo);
        ///////////////$siguiente_codigo=$arreglo[2]+1;
        $siguiente_codigo = NuevoCodigoImparticion_2021($codigo_ultima);


        //traigo la ultima imparticion
        if ($trae_imparticiones) {
            //$codigo_imparticion=$trae_imparticiones[0]->prefijo."_impart_".(count($trae_imparticiones)+1);
            $codigo_imparticion = $trae_imparticiones[0]->prefijo . "_impart_" . $siguiente_codigo;
            $codigo_imparticion = $siguiente_codigo;
        } else {
            $datos_empresa = DatosEmpresa($id_empresa);
            //$codigo_imparticion=$datos_empresa[0]->prefijo."_impart_".(count($trae_imparticiones)+1);
            $codigo_imparticion = $datos_empresa[0]->prefijo . "_impart_" . $siguiente_codigo;
            $codigo_imparticion = $siguiente_codigo;
        }
        //$codigo_imparticion="000022222";
    }

    $PRINCIPAL = str_replace("{VALUE_COD_IMPARTICION}", $codigo_imparticion, $PRINCIPAL);
    $PRINCIPAL = str_replace("{COD_IMPARTICION_ENCODEADO}", Encodear3($codigo_imparticion), $PRINCIPAL);
    $PRINCIPAL = str_replace("{ID_EMPRESA_ENCODEADO}", Encodear3($id_empresa), $PRINCIPAL);
    $PRINCIPAL = str_replace("{FECHAINICIO}", $datos_imparticion[0]->fecha_inicio, $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_FECHA_TERMINO}", $datos_imparticion[0]->fecha_termino, $PRINCIPAL);
    $fecha_inicio = str_replace("-", "/", $datos_imparticion[0]->fecha_inicio);
    $fecha_termino = str_replace("-", "/", $datos_imparticion[0]->fecha_termino);
    $PRINCIPAL = str_replace("{VALUE_FECHAS}", $fecha_inicio . " - " . $fecha_termino, $PRINCIPAL);
    $PRINCIPAL = str_replace("{COMENTARIOS}", ($datos_imparticion[0]->comentarios), $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_STREAMING}", ($datos_imparticion[0]->direccion), $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_SESIONES}", $datos_imparticion[0]->sesiones, $PRINCIPAL);
    if ($datos_imparticion[0]->id_curso) {
        $PRINCIPAL = str_replace("{VALUE_COD_CURSO}", $datos_imparticion[0]->id_curso, $PRINCIPAL);
    } else {
        $id_curso = Decodear3($_GET["i"]);
        //echo "<br>id_curso $id_curso";
        $PRINCIPAL = str_replace("{VALUE_COD_CURSO}", $id_curso, $PRINCIPAL);
    }


    $PRINCIPAL = str_replace("{VALUE_DIRECCION}", ($datos_imparticion[0]->direccion), $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_CIUDAD}", ($datos_imparticion[0]->ciudad), $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_CUPOS}", $datos_imparticion[0]->cupos, $PRINCIPAL);
    $PRINCIPAL = str_replace("{TEXTO_HORARIOS}", $datos_imparticion[0]->texto_horarios, $PRINCIPAL);
    $PRINCIPAL = str_replace("{ACTION}", "?sw=adimparti", $PRINCIPAL);
    $PRINCIPAL = str_replace("{TITULO}", "Formulario Ingreso de Imparticiones", $PRINCIPAL);
    $PRINCIPAL = str_replace("{NOMBRE_FORMULARIO}", "FormCurso", $PRINCIPAL);
    $PRINCIPAL = str_replace("{L1H}", $datos_imparticion[0]->lunes_d_am, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L2H}", $datos_imparticion[0]->lunes_h_am, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L3H}", $datos_imparticion[0]->lunes_d_pm, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L4H}", $datos_imparticion[0]->lunes_h_pm, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L5H}", $datos_imparticion[0]->martes_d_am, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L6H}", $datos_imparticion[0]->martes_h_am, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L7H}", $datos_imparticion[0]->martes_d_pm, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L8H}", $datos_imparticion[0]->martes_h_pm, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L9H}", $datos_imparticion[0]->miercoles_d_am, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L10H}", $datos_imparticion[0]->miercoles_h_am, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L11H}", $datos_imparticion[0]->miercoles_d_pm, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L12H}", $datos_imparticion[0]->miercoles_h_pm, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L13H}", $datos_imparticion[0]->jueves_d_am, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L14H}", $datos_imparticion[0]->jueves_h_am, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L15H}", $datos_imparticion[0]->jueves_d_pm, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L16H}", $datos_imparticion[0]->jueves_h_pm, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L17H}", $datos_imparticion[0]->viernes_d_am, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L18H}", $datos_imparticion[0]->viernes_h_am, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L19H}", $datos_imparticion[0]->viernes_d_pm, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L20H}", $datos_imparticion[0]->viernes_h_pm, $PRINCIPAL);
    return ($PRINCIPAL);
}

function FormularioImparticion_BK($PRINCIPAL, $id_empresa, $id_imparticion)
{



    if (!$id_imparticion) {
        //si no hay codigo de imparticion creo uno
        $trae_imparticiones = IMPARTICIONES_traeImparticionesCreadas($id_empresa);
        $trae_imparticiones_ultima = IMPARTICIONES_traeImparticionesCreadasUltima_2021($id_empresa);
        //$codigo_ultima=$trae_imparticiones_ultima[0]->codigo_inscripcion;
        //$codigo_ultima=$trae_imparticiones_ultima[0]->id;

        // echo "<br>trae_imparticiones_ultima $trae_imparticiones_ultima<br>";
        $codigo_ultima = $trae_imparticiones_ultima;
        ///////////////$arreglo=explode("_", $codigo_ultima);
        //print_r($arreglo);
        ///////////////$siguiente_codigo=$arreglo[2]+1;
        $siguiente_codigo = NuevoCodigoImparticion_2021($codigo_ultima);


        //traigo la ultima imparticion
        if ($trae_imparticiones) {
            //$codigo_imparticion=$trae_imparticiones[0]->prefijo."_impart_".(count($trae_imparticiones)+1);
            $codigo_imparticion = $trae_imparticiones[0]->prefijo . "_impart_" . $siguiente_codigo;
            $codigo_imparticion = $siguiente_codigo;
        } else {
            $datos_empresa = DatosEmpresa($id_empresa);
            //$codigo_imparticion=$datos_empresa[0]->prefijo."_impart_".(count($trae_imparticiones)+1);
            $codigo_imparticion = $datos_empresa[0]->prefijo . "_impart_" . $siguiente_codigo;
            $codigo_imparticion = $siguiente_codigo;
        }
    } else {
        $codigo_imparticion = $id_imparticion;
    }

    //echo " codigo_imparticion ".$codigo_imparticion." id imparticion ".$id_imparticion;
    //$datos_imparticion=DatosInscripcionImparticiones($id_imparticion, $id_empresa);
    $datos_imparticion = DatosInscripcionImparticionesV2($id_imparticion, $id_empresa);
    // print_r($datos_imparticion);

    $cursos = TraeCursos("-1", $id_empresa, "");
    $option_cursos = "";
    foreach ($cursos as $curso) {
        //if($arreglo_post["curso"]==$curso->id){
        if ($datos_imparticion[0]->id_curso == $curso->id) {
            $option_cursos .= '<option value="' . $curso->id . '" selected>' . $curso->id . ' - ' . $curso->nombre . '</option>';
        } else {
            $option_cursos .= '<option value="' . $curso->id . '">' . $curso->id . ' - ' . $curso->nombre . '</option>';
        }
    }
    $PRINCIPAL = str_replace("{OPCIONES_CURSOS}", ($option_cursos), $PRINCIPAL);

    //$ejecutivos=TraeEjecutivosPorEmpresa($id_empresa);
    $ejecutivos = TraeEjecutivos2021($id_empresa);
    $options_ejecutivos = "";
    foreach ($ejecutivos as $ejec) {

        if ($datos_imparticion[0]->ejecutivo == $ejec->rut) {
            $checked = " selected ";
        } else {
            $checked = "  ";
        }
        $options_ejecutivos .= '<option value="' . $ejec->rut . '" ' . $checked . '>' . $ejec->nombre . ' - ' . $ejec->rut . '</option>';
    }
    $PRINCIPAL = str_replace("{OPTIONS_EJECUTIVOS}", ($options_ejecutivos), $PRINCIPAL);

    $ejecutivos_interno = TraeEjecutivos2022_tipo($id_empresa, "interno");
    $options_ejecutivos_interno = "";
    foreach ($ejecutivos_interno as $ejec) {

        if ($datos_imparticion[0]->ejecutivo == $ejec->rut) {
            $checked = " selected ";
        } else {
            $checked = "  ";
        }
        $options_ejecutivos_interno .= '<option value="' . $ejec->rut . '" ' . $checked . '>' . $ejec->nombre . ' - ' . $ejec->rut . '</option>';
    }
    $PRINCIPAL = str_replace("{OPTIONS_EJECUTIVOS_INTERNO}", ($options_ejecutivos_interno), $PRINCIPAL);


    $ejecutivos_externo = TraeEjecutivos2022_tipo($id_empresa, "externo");
    $options_ejecutivos_externo = "";
    foreach ($ejecutivos_externo as $ejec) {

        if ($datos_imparticion[0]->archivo == $ejec->rut) {
            $checked = " selected ";
        } else {
            $checked = "  ";
        }
        $options_ejecutivos_externo .= '<option value="' . $ejec->rut . '" ' . $checked . '>' . $ejec->nombre . ' - ' . $ejec->rut . '</option>';
    }
    $PRINCIPAL = str_replace("{OPTIONS_EJECUTIVOS_EXTERNO}", ($options_ejecutivos_externo), $PRINCIPAL);


    $otecs = TraeOtec2021($id_empresa);
    $options_otec = "";
    foreach ($otecs as $otec) {
        if ($datos_imparticion[0]->ciudad == $otec->rut) {
            $checked = " selected ";
        } else {
            $checked = "  ";
        }
        $options_otec .= '<option value="' . $otec->rut . '" ' . $checked . '>' . $otec->nombre . ' - ' . $otec->rut . '</option>';
    }
    $PRINCIPAL = str_replace("{OPTIONS_OTEC}", ($options_otec), $PRINCIPAL);

    $direcciones = TraeDirecciones2021($id_empresa);
    $options_direcciones = "";
    foreach ($direcciones as $direccion) {
        if ($datos_imparticion[0]->direccion == $direccion->tipo . "-" . $direccion->nombre) {
            $checkedDireccion = " selected ";
        } else {
            $checkedDireccion = "  ";
        }
        $options_direcciones .= '<option value="' . $direccion->tipo . '-' . $direccion->nombre . '" ' . $checkedDireccion . '>' . $direccion->tipo . "-" . $direccion->nombre . '</option>';
    }


    //echo $datos_imparticion[0]->id_modalidad_imparticion;
    if($datos_imparticion[0]->id_modalidad_imparticion=="1"){$select_modalidad_01=" selected ";}
    if($datos_imparticion[0]->id_modalidad_imparticion=="2"){$select_modalidad_02=" selected ";}
    if($datos_imparticion[0]->id_modalidad_imparticion=="3"){$select_modalidad_03=" selected ";}
    if($datos_imparticion[0]->id_modalidad_imparticion=="4"){$select_modalidad_04=" selected ";}
    if($datos_imparticion[0]->id_modalidad_imparticion=="5"){$select_modalidad_05=" selected ";}
    if($datos_imparticion[0]->id_modalidad_imparticion=="6"){$select_modalidad_06=" selected ";}
    if($datos_imparticion[0]->id_modalidad_imparticion=="7"){$select_modalidad_07=" selected ";}

    $PRINCIPAL = str_replace("{SELECT_MODALIDAD_01}", ($select_modalidad_01), $PRINCIPAL);
    $PRINCIPAL = str_replace("{SELECT_MODALIDAD_02}", ($select_modalidad_02), $PRINCIPAL);
    $PRINCIPAL = str_replace("{SELECT_MODALIDAD_03}", ($select_modalidad_03), $PRINCIPAL);
    $PRINCIPAL = str_replace("{SELECT_MODALIDAD_04}", ($select_modalidad_04), $PRINCIPAL);
    $PRINCIPAL = str_replace("{SELECT_MODALIDAD_05}", ($select_modalidad_05), $PRINCIPAL);
    $PRINCIPAL = str_replace("{SELECT_MODALIDAD_06}", ($select_modalidad_06), $PRINCIPAL);
    $PRINCIPAL = str_replace("{SELECT_MODALIDAD_07}", ($select_modalidad_07), $PRINCIPAL);

    $PRINCIPAL = str_replace("{VALUE_DIRECCION}", ($options_direcciones), $PRINCIPAL);

    if($datos_imparticion[0]->valor_curso=="SI")    {   $select_costos_asociados_SI=" selected ";}
    if($datos_imparticion[0]->valor_curso=="NO")    {   $select_costos_asociados_NO=" selected ";}

    $PRINCIPAL = str_replace("{COSTOS_ASOCIADOS_SI}", ($select_costos_asociados_SI), $PRINCIPAL);
    $PRINCIPAL = str_replace("{COSTOS_ASOCIADOS_NO}", ($select_costos_asociados_NO), $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALOR_BOTON}", "Ingresar Imparticion", $PRINCIPAL);
    //Veo cuantos usuarios hay en la tabla inscripcion_usuario, para colocar la cantidad
    $usuarios_por_inscripcion = IMPARTICION_UsuariosPorInscripcion($codigo_imparticion, $id_empresa);
    $PRINCIPAL = str_replace("{TOTAL_USUARIOS_POR_INSCRIPCION}", count($usuarios_por_inscripcion), $PRINCIPAL);
    //Postulantes por Imparticion, tabla tbl_inscripcion_postulantes
    $postulantes_por_inscripcion = IMPARTICION_PostulantesPorInscripcion($codigo_imparticion, $id_empresa);
    $PRINCIPAL = str_replace("{TOTAL_POSTULANTES_POR_INSCRIPCION}", count($postulantes_por_inscripcion), $PRINCIPAL);

    $nombre_Curso_array = BuscaNombreCursoDadoID($datos_imparticion[0]->id_curso);
    $nombre_curso = $nombre_Curso_array[0]->nombre;

    $PRINCIPAL = str_replace("{VALUE_COD_IMPARTICION_FECHA}", $datos_imparticion[0]->fecha_inicio, $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_COD_IMPARTICION_FECHA_HASTA}", $datos_imparticion[0]->fecha_termino, $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_COD_IMPARTICION_HORA_DESDE}", $datos_imparticion[0]->hora_inicio, $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_COD_IMPARTICION_HORA_HASTA}", $datos_imparticion[0]->hora_termino, $PRINCIPAL);


    $PRINCIPAL = str_replace("{SCRIPT_ACTIVA_TIPO_HORARIO}", $script_activa_radio_horarios, $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_COD_IMPARTICION_OBSERVACION}", $datos_imparticion[0]->observacion, $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_COD_IMPARTICION_OBSERVACION_NEW2}", $datos_imparticion[0]->observacion, $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_COD_IMPARTICION_FECHA_HASTA}", $datos_imparticion[0]->observacion, $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_NOMBRE}", ($datos_imparticion[0]->nombre), $PRINCIPAL);
    $PRINCIPAL = str_replace("{NOMBRE_CURSO}", ($nombre_Curso_array[0]->nombre), $PRINCIPAL);

    if($_GET["dup"]==1){
        $trae_imparticiones = IMPARTICIONES_traeImparticionesCreadas($id_empresa);
        $trae_imparticiones_ultima = IMPARTICIONES_traeImparticionesCreadasUltima_2021($id_empresa);
        //$codigo_ultima=$trae_imparticiones_ultima[0]->codigo_inscripcion;
        //$codigo_ultima=$trae_imparticiones_ultima[0]->id;

        // echo "<br>trae_imparticiones_ultima $trae_imparticiones_ultima<br>";
        $codigo_ultima = $trae_imparticiones_ultima;
        ///////////////$arreglo=explode("_", $codigo_ultima);
        //print_r($arreglo);
        ///////////////$siguiente_codigo=$arreglo[2]+1;
        $siguiente_codigo = NuevoCodigoImparticion_2021($codigo_ultima);


        //traigo la ultima imparticion
        if ($trae_imparticiones) {
            //$codigo_imparticion=$trae_imparticiones[0]->prefijo."_impart_".(count($trae_imparticiones)+1);
            $codigo_imparticion = $trae_imparticiones[0]->prefijo . "_impart_" . $siguiente_codigo;
            $codigo_imparticion = $siguiente_codigo;
        } else {
            $datos_empresa = DatosEmpresa($id_empresa);
            //$codigo_imparticion=$datos_empresa[0]->prefijo."_impart_".(count($trae_imparticiones)+1);
            $codigo_imparticion = $datos_empresa[0]->prefijo . "_impart_" . $siguiente_codigo;
            $codigo_imparticion = $siguiente_codigo;
        }
        //$codigo_imparticion="000022222";
    }

    $PRINCIPAL = str_replace("{VALUE_COD_IMPARTICION}", $codigo_imparticion, $PRINCIPAL);
    $PRINCIPAL = str_replace("{COD_IMPARTICION_ENCODEADO}", Encodear3($codigo_imparticion), $PRINCIPAL);
    $PRINCIPAL = str_replace("{ID_EMPRESA_ENCODEADO}", Encodear3($id_empresa), $PRINCIPAL);
    $PRINCIPAL = str_replace("{FECHAINICIO}", $datos_imparticion[0]->fecha_inicio, $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_FECHA_TERMINO}", $datos_imparticion[0]->fecha_termino, $PRINCIPAL);
    $fecha_inicio = str_replace("-", "/", $datos_imparticion[0]->fecha_inicio);
    $fecha_termino = str_replace("-", "/", $datos_imparticion[0]->fecha_termino);
    $PRINCIPAL = str_replace("{VALUE_FECHAS}", $fecha_inicio . " - " . $fecha_termino, $PRINCIPAL);
    $PRINCIPAL = str_replace("{COMENTARIOS}", ($datos_imparticion[0]->comentarios), $PRINCIPAL);

    $PRINCIPAL = str_replace("{VALUE_STREAMING}", ($datos_imparticion[0]->direccion), $PRINCIPAL);


    $PRINCIPAL = str_replace("{VALUE_SESIONES}", $datos_imparticion[0]->sesiones, $PRINCIPAL);
    if ($datos_imparticion[0]->id_curso) {
        $PRINCIPAL = str_replace("{VALUE_COD_CURSO}", $datos_imparticion[0]->id_curso, $PRINCIPAL);
    } else {
        $id_curso = Decodear3($_GET["i"]);
        //echo "<br>id_curso $id_curso";
        $PRINCIPAL = str_replace("{VALUE_COD_CURSO}", $id_curso, $PRINCIPAL);
    }


    $PRINCIPAL = str_replace("{VALUE_DIRECCION}", ($datos_imparticion[0]->direccion), $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_CIUDAD}", ($datos_imparticion[0]->ciudad), $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_CUPOS}", $datos_imparticion[0]->cupos, $PRINCIPAL);
    $PRINCIPAL = str_replace("{TEXTO_HORARIOS}", $datos_imparticion[0]->texto_horarios, $PRINCIPAL);
    $PRINCIPAL = str_replace("{ACTION}", "?sw=adimparti", $PRINCIPAL);
    $PRINCIPAL = str_replace("{TITULO}", "Formulario Ingreso de Imparticiones", $PRINCIPAL);
    $PRINCIPAL = str_replace("{NOMBRE_FORMULARIO}", "FormCurso", $PRINCIPAL);
    $PRINCIPAL = str_replace("{L1H}", $datos_imparticion[0]->lunes_d_am, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L2H}", $datos_imparticion[0]->lunes_h_am, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L3H}", $datos_imparticion[0]->lunes_d_pm, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L4H}", $datos_imparticion[0]->lunes_h_pm, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L5H}", $datos_imparticion[0]->martes_d_am, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L6H}", $datos_imparticion[0]->martes_h_am, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L7H}", $datos_imparticion[0]->martes_d_pm, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L8H}", $datos_imparticion[0]->martes_h_pm, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L9H}", $datos_imparticion[0]->miercoles_d_am, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L10H}", $datos_imparticion[0]->miercoles_h_am, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L11H}", $datos_imparticion[0]->miercoles_d_pm, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L12H}", $datos_imparticion[0]->miercoles_h_pm, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L13H}", $datos_imparticion[0]->jueves_d_am, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L14H}", $datos_imparticion[0]->jueves_h_am, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L15H}", $datos_imparticion[0]->jueves_d_pm, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L16H}", $datos_imparticion[0]->jueves_h_pm, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L17H}", $datos_imparticion[0]->viernes_d_am, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L18H}", $datos_imparticion[0]->viernes_h_am, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L19H}", $datos_imparticion[0]->viernes_d_pm, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L20H}", $datos_imparticion[0]->viernes_h_pm, $PRINCIPAL);
    return ($PRINCIPAL);
}

function FormularioImparticionMallas($PRINCIPAL, $id_empresa, $id_imparticion, $id_curso)
{

    if (!$id_imparticion) {
        //si no hay codigo de imparticion creo uno
        $trae_imparticiones = IMPARTICIONES_traeImparticionesCreadas($id_empresa);
        $trae_imparticiones_ultima = IMPARTICIONES_traeImparticionesCreadasUltima_2021($id_empresa);
        //$codigo_ultima=$trae_imparticiones_ultima[0]->codigo_inscripcion;
        //$codigo_ultima=$trae_imparticiones_ultima[0]->id;

        // echo "<br>trae_imparticiones_ultima $trae_imparticiones_ultima<br>";
        $codigo_ultima = $trae_imparticiones_ultima;
        ///////////////$arreglo=explode("_", $codigo_ultima);
        //print_r($arreglo);
        ///////////////$siguiente_codigo=$arreglo[2]+1;
        $siguiente_codigo = NuevoCodigoImparticion_2021($codigo_ultima);


        //traigo la ultima imparticion
        if ($trae_imparticiones) {
            //$codigo_imparticion=$trae_imparticiones[0]->prefijo."_impart_".(count($trae_imparticiones)+1);
            $codigo_imparticion = $trae_imparticiones[0]->prefijo . "_impart_" . $siguiente_codigo;
            $codigo_imparticion = $siguiente_codigo;
        } else {
            $datos_empresa = DatosEmpresa($id_empresa);
            //$codigo_imparticion=$datos_empresa[0]->prefijo."_impart_".(count($trae_imparticiones)+1);
            $codigo_imparticion = $datos_empresa[0]->prefijo . "_impart_" . $siguiente_codigo;
            $codigo_imparticion = $siguiente_codigo;
        }
    } else {
        $codigo_imparticion = $id_imparticion;
    }

    //echo " codigo_imparticion ".$codigo_imparticion." id imparticion ".$id_imparticion;
    //$datos_imparticion=DatosInscripcionImparticiones($id_imparticion, $id_empresa);
    $datos_imparticion = DatosInscripcionImparticionesV2($id_imparticion, $id_empresa);
    // print_r($datos_imparticion);

    $datos_curso = VerificoCursoPorEmpresa($id_curso, $id_empresa);
    //$ejecutivos=TraeEjecutivosPorEmpresa($id_empresa);
    $ejecutivos = TraeEjecutivos2021($id_empresa);
    $options_ejecutivos = "";
    foreach ($ejecutivos as $ejec) {

        if ($datos_imparticion[0]->ejecutivo == $ejec->rut) {
            $checked = " selected ";
        } else {
            $checked = "  ";
        }
        $options_ejecutivos .= '<option value="' . $ejec->rut . '" ' . $checked . '>' . $ejec->nombre . ' - ' . $ejec->rut . '</option>';
    }
    $PRINCIPAL = str_replace("{OPTIONS_EJECUTIVOS}", ($options_ejecutivos), $PRINCIPAL);

    $ejecutivos_interno = TraeEjecutivos2022_tipo($id_empresa, "interno");
    $options_ejecutivos_interno = "";
    foreach ($ejecutivos_interno as $ejec) {

        if ($datos_imparticion[0]->ejecutivo == $ejec->rut) {
            $checked = " selected ";
        } else {
            $checked = "  ";
        }
        $options_ejecutivos_interno .= '<option value="' . $ejec->rut . '" ' . $checked . '>' . $ejec->nombre . ' - ' . $ejec->rut . '</option>';
    }
    $PRINCIPAL = str_replace("{OPTIONS_EJECUTIVOS_INTERNO}", ($options_ejecutivos_interno), $PRINCIPAL);


    $ejecutivos_externo = TraeEjecutivos2022_tipo($id_empresa, "externo");
    $options_ejecutivos_externo = "";
    foreach ($ejecutivos_externo as $ejec) {

        if ($datos_imparticion[0]->archivo == $ejec->rut) {
            $checked = " selected ";
        } else {
            $checked = "  ";
        }
        $options_ejecutivos_externo .= '<option value="' . $ejec->rut . '" ' . $checked . '>' . $ejec->nombre . ' - ' . $ejec->rut . '</option>';
    }
    $PRINCIPAL = str_replace("{OPTIONS_EJECUTIVOS_EXTERNO}", ($options_ejecutivos_externo), $PRINCIPAL);


    $otecs = TraeOtec2021($id_empresa);
    $options_otec = "";
    foreach ($otecs as $otec) {
        if ($datos_imparticion[0]->ciudad == $otec->rut) {
            $checked = " selected ";
        } else {
            $checked = "  ";
        }
        $options_otec .= '<option value="' . $otec->rut . '" ' . $checked . '>' . $otec->nombre . ' - ' . $otec->rut . '</option>';
    }
    $PRINCIPAL = str_replace("{OPTIONS_OTEC}", ($options_otec), $PRINCIPAL);

    $direcciones = TraeDirecciones2021($id_empresa);
    $options_direcciones = "";
    foreach ($direcciones as $direccion) {
        if ($datos_imparticion[0]->direccion == $direccion->tipo . "-" . $direccion->nombre) {
            $checkedDireccion = " selected ";
        } else {
            $checkedDireccion = "  ";
        }
        $options_direcciones .= '<option value="' . $direccion->tipo . '-' . $direccion->nombre . '" ' . $checkedDireccion . '>' . $direccion->tipo . "-" . $direccion->nombre . '</option>';
    }
    $PRINCIPAL = str_replace("{VALUE_DIRECCION}", ($options_direcciones), $PRINCIPAL);

    $PRINCIPAL = str_replace("{VALOR_BOTON}", "Ingresar Imparticion", $PRINCIPAL);
    //Veo cuantos usuarios hay en la tabla inscripcion_usuario, para colocar la cantidad
    $usuarios_por_inscripcion = IMPARTICION_UsuariosPorInscripcion($codigo_imparticion, $id_empresa);
    $PRINCIPAL = str_replace("{TOTAL_USUARIOS_POR_INSCRIPCION}", count($usuarios_por_inscripcion), $PRINCIPAL);
    //Postulantes por Imparticion, tabla tbl_inscripcion_postulantes
    $postulantes_por_inscripcion = IMPARTICION_PostulantesPorInscripcion($codigo_imparticion, $id_empresa);
    $PRINCIPAL = str_replace("{TOTAL_POSTULANTES_POR_INSCRIPCION}", count($postulantes_por_inscripcion), $PRINCIPAL);

    $nombre_Curso_array = BuscaNombreCursoDadoID($datos_imparticion[0]->id_curso);
    $nombre_curso = $nombre_Curso_array[0]->nombre;

    $PRINCIPAL = str_replace("{VALUE_COD_IMPARTICION_FECHA}", $datos_imparticion[0]->fecha_inicio, $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_COD_IMPARTICION_FECHA_HASTA}", $datos_imparticion[0]->fecha_termino, $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_COD_IMPARTICION_HORA_DESDE}", $datos_imparticion[0]->hora_inicio, $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_COD_IMPARTICION_HORA_HASTA}", $datos_imparticion[0]->hora_termino, $PRINCIPAL);

    $PRINCIPAL = str_replace("{NOMBRE_CURSO_DESDE_MALLA}", ($datos_curso[0]->nombre), $PRINCIPAL);
    $PRINCIPAL = str_replace("{ID_CURSO_DESDE_MALLA}",      $datos_curso[0]->id, $PRINCIPAL);



    $PRINCIPAL = str_replace("{SCRIPT_ACTIVA_TIPO_HORARIO}", $script_activa_radio_horarios, $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_COD_IMPARTICION_OBSERVACION}", $datos_imparticion[0]->observacion, $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_COD_IMPARTICION_OBSERVACION_NEW2}", $datos_imparticion[0]->observacion, $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_COD_IMPARTICION_FECHA_HASTA}", $datos_imparticion[0]->observacion, $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_NOMBRE}", ($datos_imparticion[0]->nombre), $PRINCIPAL);
    $PRINCIPAL = str_replace("{NOMBRE_CURSO}", ($nombre_Curso_array[0]->nombre), $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_COD_IMPARTICION}", $codigo_imparticion, $PRINCIPAL);
    $PRINCIPAL = str_replace("{COD_IMPARTICION_ENCODEADO}", Encodear3($codigo_imparticion), $PRINCIPAL);
    $PRINCIPAL = str_replace("{ID_EMPRESA_ENCODEADO}", Encodear3($id_empresa), $PRINCIPAL);
    $PRINCIPAL = str_replace("{FECHAINICIO}", $datos_imparticion[0]->fecha_inicio, $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_FECHA_TERMINO}", $datos_imparticion[0]->fecha_termino, $PRINCIPAL);
    $fecha_inicio = str_replace("-", "/", $datos_imparticion[0]->fecha_inicio);
    $fecha_termino = str_replace("-", "/", $datos_imparticion[0]->fecha_termino);
    $PRINCIPAL = str_replace("{VALUE_FECHAS}", $fecha_inicio . " - " . $fecha_termino, $PRINCIPAL);
    $PRINCIPAL = str_replace("{COMENTARIOS}", ($datos_imparticion[0]->comentarios), $PRINCIPAL);

    $PRINCIPAL = str_replace("{VALUE_STREAMING}", ($datos_imparticion[0]->direccion), $PRINCIPAL);


    $PRINCIPAL = str_replace("{VALUE_SESIONES}", $datos_imparticion[0]->sesiones, $PRINCIPAL);
    if ($datos_imparticion[0]->id_curso) {
        $PRINCIPAL = str_replace("{VALUE_COD_CURSO}", $datos_imparticion[0]->id_curso, $PRINCIPAL);
    } else {
        $id_curso = Decodear3($_GET["i"]);
        //echo "<br>id_curso $id_curso";
        $PRINCIPAL = str_replace("{VALUE_COD_CURSO}", $id_curso, $PRINCIPAL);
    }


    $PRINCIPAL = str_replace("{VALUE_DIRECCION}", ($datos_imparticion[0]->direccion), $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_CIUDAD}", ($datos_imparticion[0]->ciudad), $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_CUPOS}", $datos_imparticion[0]->cupos, $PRINCIPAL);
    $PRINCIPAL = str_replace("{TEXTO_HORARIOS}", $datos_imparticion[0]->texto_horarios, $PRINCIPAL);
    $PRINCIPAL = str_replace("{ACTION}", "?sw=adimparti", $PRINCIPAL);
    $PRINCIPAL = str_replace("{TITULO}", "Formulario Ingreso de Imparticiones", $PRINCIPAL);
    $PRINCIPAL = str_replace("{NOMBRE_FORMULARIO}", "FormCurso", $PRINCIPAL);
    $PRINCIPAL = str_replace("{L1H}", $datos_imparticion[0]->lunes_d_am, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L2H}", $datos_imparticion[0]->lunes_h_am, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L3H}", $datos_imparticion[0]->lunes_d_pm, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L4H}", $datos_imparticion[0]->lunes_h_pm, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L5H}", $datos_imparticion[0]->martes_d_am, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L6H}", $datos_imparticion[0]->martes_h_am, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L7H}", $datos_imparticion[0]->martes_d_pm, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L8H}", $datos_imparticion[0]->martes_h_pm, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L9H}", $datos_imparticion[0]->miercoles_d_am, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L10H}", $datos_imparticion[0]->miercoles_h_am, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L11H}", $datos_imparticion[0]->miercoles_d_pm, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L12H}", $datos_imparticion[0]->miercoles_h_pm, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L13H}", $datos_imparticion[0]->jueves_d_am, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L14H}", $datos_imparticion[0]->jueves_h_am, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L15H}", $datos_imparticion[0]->jueves_d_pm, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L16H}", $datos_imparticion[0]->jueves_h_pm, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L17H}", $datos_imparticion[0]->viernes_d_am, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L18H}", $datos_imparticion[0]->viernes_h_am, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L19H}", $datos_imparticion[0]->viernes_d_pm, $PRINCIPAL);
    $PRINCIPAL = str_replace("{L20H}", $datos_imparticion[0]->viernes_h_pm, $PRINCIPAL);
    return ($PRINCIPAL);
}


function TiempoTranscurrido_2021($t1_hour, $t2_hour)
{
    $datetime1 = new DateTime('2014-02-11 ' . $t1_hour);
    $datetime2 = new DateTime('2014-02-11 ' . $t2_hour);
    $interval = $datetime1->diff($datetime2);
    $tiempo = $interval->format('%h') . "h:" . $interval->format('%i') . "m";
    return $tiempo;
}


function TiempoTranscurrido_Full_2021($t1_hour, $t2_hour)
{
    $datetime1 = new DateTime('2014-02-11 ' . $t1_hour);
    $datetime2 = new DateTime('2014-02-11 ' . $t2_hour);
    $interval = $datetime1->diff($datetime2);
    $tiempo = $interval->format('%h') . ":" . $interval->format('%i') . "";
//echo "<br>$t1_hour, $t2_hour -> tiempo $tiempo";
    $hora = $interval->format('%h');
    $minuto = $interval->format('%i');
    $hora_enMinutos = round($hora * 60);
    $minutos_enminutos = round($minuto * 1);
//e/cho "<br>hora_enMinutos $hora_enMinutos";
//echo "<br>minutos_enminutos $minutos_enminutos";
    $total_minutos = round($hora_enMinutos) + round($minutos_enminutos);

//echo "<br>total_minutos $total_minutos<br>";

    return $total_minutos;
}

function DatosImparticiones_SumaHoras($id_curso, $id_inscripcion, $tipo_horario, $fecha_inicio, $fecha_termino, $hora_inicio, $hora_termino, $modalidad, $numero_horas)
{
    $suma_tiempo = 0;
    //echo "<h3>DatosImparticiones_SumaHoras($id_curso, $id_inscripcion, $tipo_horario, $fecha_inicio, $fecha_termino, $hora_inicio, $hora_termino, modalidad $modalidad, numero horas $numero_horas)</h3>";
    if ($tipo_horario == "fecha_sesiones") {

        $InscripcionCursoSesiones = DatosImparticiones_Sesiones_data_2021($id_inscripcion);
        foreach ($InscripcionCursoSesiones as $ses) {
            $tiempo = TiempoTranscurrido_Full_2021($ses->hora_desde, $ses->hora_hasta);
            //echo "<br>-Minutos ".($tiempo);
            if ($tiempo > 0) {
                $suma_tiempo = $suma_tiempo + $tiempo;
            }
        }
        $numero_horas_imparticiones = $suma_tiempo;
        $numero_horas_minutos = round(60 * $numero_horas);
    } else {
        //echo "<h2>b $fecha_inicio==$fecha_termino modalidad $modalidad</h2>";
        if ($fecha_inicio == $fecha_termino) {
            $tiempo = TiempoTranscurrido_Full_2021($hora_inicio, $hora_termino);
            if ($tiempo > 0) {
                $suma_tiempo = $suma_tiempo + $tiempo;
            }
            //echo "<br>-Minutos ".($suma_tiempo);
            $numero_horas_imparticiones = $suma_tiempo;
            $numero_horas_minutos = round(60 * $numero_horas);
        } else {
            $numero_horas_imparticiones = 0;
        }
    }

    if ($numero_horas > 0) {
        if ($modalidad == "2") {
            if ($numero_horas_minutos > $numero_horas_imparticiones and $numero_horas_imparticiones > 0) {
                $diferencia_minutos = $numero_horas_minutos - $numero_horas_imparticiones;
                $alerta_curso .= "<center><div class='alert alert-danger'>Horas de Curso: $numero_horas hora(s). Faltan $diferencia_minutos minutos en las sesiones creadas.</div></center><br>";
            } elseif ($numero_horas_minutos < $numero_horas_imparticiones and $numero_horas_imparticiones > 0) {
                $diferencia_minutos = $numero_horas_imparticiones - $numero_horas_minutos;
                $alerta_curso .= "<center><div class='alert alert-danger'>Horas de Curso: $numero_horas hora(s). Sobran $diferencia_minutos minutos en las sesiones creadas.</div></center><br>";

            } else {
                $alerta_curso .= "<center><div class='alert alert-info'>Horas de Curso: $numero_horas hora(s)</div></center><br>";
            }
        } else {
            $alerta_curso .= "<center><div class='alert alert-danger'>Horas de Curso: $numero_horas hora(s)</div></center><br>";
        }


    } else {
        // no muestra horas del curso
        $alerta_curso .= "<center><div class='alert alert-danger'>El curso no tiene definida horas</div></center><br>";
    }

    return $alerta_curso;
}

function DatosImparticiones_SinASistenciaSinHoras($id_curso, $id_inscripcion, $tipo_horario, $fecha_inicio, $fecha_termino, $hora_inicio, $hora_termino, $modalidad, $numero_horas)
{
    $Imp = BuscaIdImparticionFull_2021($id_inscripcion);
    //print_r($Imp);

    if ($Imp[0]->id > 0) {
        if (($Imp[0]->minimo_asistencia == "" or $Imp[0]->minimo_asistencia == "0") and ($Imp[0]->minimo_nota_aprobacion == "" or $Imp[0]->minimo_nota_aprobacion == "0")) {
            $alerta = "<div class='alert alert-warning'><strong>Importante</strong>:
				Esta impartici&oacute;n no tiene ni asistencia m&iacute;nima ni nota m&iacute;nima definida.</div>";
        }

    } else {
        $alerta = "";
    }

    return $alerta;
}

function DatosImparticiones_Sesiones_2021($PRINCIPAL, $id_inscripcion, $id_curso)
{
    //echo "<h3>DatosImparticiones_Sesiones_2021(, $id_inscripcion</h3>";
    $InscripcionCursoSesiones = DatosImparticiones_Sesiones_data_2021($id_inscripcion);
    //print_r($InscripcionCursoSesiones);
    $i = $_GET["i"];
    $idpbbdd = $_GET["idpbbdd"];
    //echo "<br>-->i $i, idpbbdd $idpbbdd";

    $options_relatores .= "";
    $Rel = TraeRelatores2021();
    foreach ($Rel as $uniRel) {
        $options_relatores .= "<option value='" . Encodear3($uniRel->rut) . "'>" . ($uniRel->nombre) . " (" . $uniRel->tipo . ")</option>";
    }


    //echo "<h4>Cuenta Sesiones </h4>".count($InscripcionCursoSesiones)."<br>";

    if (count($InscripcionCursoSesiones) > 0) {
        //echo "<h1>a</h1>";
        foreach ($InscripcionCursoSesiones as $ses) {
            //print_r($ses);
            $cuenta_sesiones++;
            if ($cuenta_sesiones == "1") {
                $row1 .= file_get_contents("views/capacitacion/imparticion/row_imparticion_por_sesion.html");
                $row1 = str_replace("{ID_LINEA_ENC}", Encodear3($ses->id), $row1);
                $row1 = str_replace("{VALUE_COD_IMPARTICION_FECHA}", $ses->fecha, $row1);
                $row1 = str_replace("{VALUE_COD_IMPARTICION_HORA_DESDE}", $ses->hora_desde, $row1);
                $row1 = str_replace("{VALUE_COD_IMPARTICION_HORA_HASTA}", $ses->hora_hasta, $row1);
                $row1 = str_replace("{VALUE_COD_IMPARTICION_OBSERVACION}", ($ses->observacione), $row1);
                $row1 = str_replace("{ID_IMPARTICION_ENC}", Encodear3($ses->id_inscripcion), $row1);
                $row1 = str_replace("{ID_PROGRAMA_ENC}", "", $row1);
                $row1 = str_replace("{ID_SESION}", "1", $row1);
                $row1 = str_replace("{ID_SESION_NEW}", "2", $row1);
                $row1 = str_replace("{ID_CURSO_ENC}", Encodear3($id_curso), $row1);
                $row1 = str_replace("{ID_IMPARTICION_ENC}", Encodear3($id_inscripcion), $row1);
                $row1 = str_replace("{OPTION_RELATOR_NOMBRE_TIPO}", $options_relatores, $row1);
                $Rel = TraeRelatores2021porId($ses->relator);
                if ($Rel[0]->nombre <> "") {
                    $relator_nombre = "" . ($Rel[0]->nombre) . " (" . $Rel[0]->tipo . ")";
                    $rut_relator = $ses->relator;
                } else {
                    $rut_relator = "";
                    $relator_nombre = "";
                }
                $row1 = str_replace("{RELATOR_SES}", $rut_relator, $row1);
                $row1 = str_replace("{RELATOR_nombre_SES}", $relator_nombre, $row1);
                $tiempo_transcurrido = TiempoTranscurrido_2021($ses->hora_desde, $ses->hora_hasta);
                $row1 = str_replace("{NUMERO_HORAS_SESION}", $tiempo_transcurrido, $row1);

            }
            if ($cuenta_sesiones == "2") {
                $row2 .= file_get_contents("views/capacitacion/imparticion/row_imparticion_por_sesion.html");
                $row2 = str_replace("{ID_LINEA_ENC}", Encodear3($ses->id), $row2);
                $row2 = str_replace("{VALUE_COD_IMPARTICION_FECHA}", $ses->fecha, $row2);
                $row2 = str_replace("{VALUE_COD_IMPARTICION_HORA_DESDE}", $ses->hora_desde, $row2);
                $row2 = str_replace("{VALUE_COD_IMPARTICION_HORA_HASTA}", $ses->hora_hasta, $row2);
                $row2 = str_replace("{VALUE_COD_IMPARTICION_OBSERVACION}", ($ses->observacione), $row2);
                $row2 = str_replace("{ID_IMPARTICION_ENC}", Encodear3($ses->id_inscripcion), $row2);
                $row2 = str_replace("{ID_PROGRAMA_ENC}", "", $row2);
                $row2 = str_replace("{ID_SESION}", "2", $row2);
                $row2 = str_replace("{ID_SESION_NEW}", "3", $row2);
                $row2 = str_replace("{ID_CURSO_ENC}", Encodear3($id_curso), $row2);
                $row2 = str_replace("{ID_IMPARTICION_ENC}", Encodear3($id_inscripcion), $row2);
                $row2 = str_replace("{DISPLAY_AGREGAR_MAS}", "display:none;", $row2);
                $row2 = str_replace("{OPTION_RELATOR_NOMBRE_TIPO}", $options_relatores, $row2);
                $Rel = TraeRelatores2021porId($ses->relator);
                if ($Rel[0]->nombre <> "") {
                    $relator_nombre = "" . ($Rel[0]->nombre) . " (" . $Rel[0]->tipo . ")";
                    $rut_relator = $ses->relator;
                } else {
                    $rut_relator = "";
                    $relator_nombre = "";
                }
                $row2 = str_replace("{RELATOR_SES}", $rut_relator, $row2);
                $row2 = str_replace("{RELATOR_nombre_SES}", $relator_nombre, $row2);
                $tiempo_transcurrido = TiempoTranscurrido_2021($ses->hora_desde, $ses->hora_hasta);
                $row2 = str_replace("{NUMERO_HORAS_SESION}", $tiempo_transcurrido, $row2);
            }
            if ($cuenta_sesiones == "3") {
                $row3 .= file_get_contents("views/capacitacion/imparticion/row_imparticion_por_sesion.html");
                $row3 = str_replace("{ID_LINEA_ENC}", Encodear3($ses->id), $row3);
                $row3 = str_replace("{VALUE_COD_IMPARTICION_FECHA}", $ses->fecha, $row3);
                $row3 = str_replace("{VALUE_COD_IMPARTICION_HORA_DESDE}", $ses->hora_desde, $row3);
                $row3 = str_replace("{VALUE_COD_IMPARTICION_HORA_HASTA}", $ses->hora_hasta, $row3);
                $row3 = str_replace("{VALUE_COD_IMPARTICION_OBSERVACION}", ($ses->observacione), $row3);
                $row3 = str_replace("{ID_IMPARTICION_ENC}", Encodear3($ses->id_inscripcion), $row3);
                $row3 = str_replace("{ID_PROGRAMA_ENC}", "", $row3);
                $row3 = str_replace("{ID_SESION}", "3", $row3);
                $row3 = str_replace("{ID_SESION_NEW}", "4", $row3);
                $row3 = str_replace("{ID_CURSO_ENC}", Encodear3($id_curso), $row3);
                $row3 = str_replace("{ID_IMPARTICION_ENC}", Encodear3($id_inscripcion), $row3);
                $row3 = str_replace("{DISPLAY_AGREGAR_MAS}", "display:none;", $row3);
                $row3 = str_replace("{OPTION_RELATOR_NOMBRE_TIPO}", $options_relatores, $row3);
                $Rel = TraeRelatores2021porId($ses->relator);
                if ($Rel[0]->nombre <> "") {
                    $relator_nombre = "" . ($Rel[0]->nombre) . " (" . $Rel[0]->tipo . ")";
                    $rut_relator = $ses->relator;
                } else {
                    $rut_relator = "";
                    $relator_nombre = "";
                }
                $row3 = str_replace("{RELATOR_SES}", $rut_relator, $row3);
                $row3 = str_replace("{RELATOR_nombre_SES}", $relator_nombre, $row3);
                $tiempo_transcurrido = TiempoTranscurrido_2021($ses->hora_desde, $ses->hora_hasta);
                $row3 = str_replace("{NUMERO_HORAS_SESION}", $tiempo_transcurrido, $row3);

            }
            if ($cuenta_sesiones == "4") {
                $row4 .= file_get_contents("views/capacitacion/imparticion/row_imparticion_por_sesion.html");
                $row4 = str_replace("{ID_LINEA_ENC}", Encodear3($ses->id), $row4);
                $row4 = str_replace("{VALUE_COD_IMPARTICION_FECHA}", $ses->fecha, $row4);
                $row4 = str_replace("{VALUE_COD_IMPARTICION_HORA_DESDE}", $ses->hora_desde, $row4);
                $row4 = str_replace("{VALUE_COD_IMPARTICION_HORA_HASTA}", $ses->hora_hasta, $row4);
                $row4 = str_replace("{VALUE_COD_IMPARTICION_OBSERVACION}", ($ses->observacione), $row4);
                $row4 = str_replace("{ID_IMPARTICION_ENC}", Encodear3($ses->id_inscripcion), $row4);
                $row4 = str_replace("{ID_PROGRAMA_ENC}", "", $row4);
                $row4 = str_replace("{ID_SESION}", "4", $row4);
                $row4 = str_replace("{ID_SESION_NEW}", "5", $row4);
                $row4 = str_replace("{ID_CURSO_ENC}", Encodear3($id_curso), $row4);
                $row4 = str_replace("{ID_IMPARTICION_ENC}", Encodear3($id_inscripcion), $row4);
                $row4 = str_replace("{DISPLAY_AGREGAR_MAS}", "display:none;", $row4);
                $row4 = str_replace("{OPTION_RELATOR_NOMBRE_TIPO}", $options_relatores, $row4);
                $Rel = TraeRelatores2021porId($ses->relator);
                if ($Rel[0]->nombre <> "") {
                    $relator_nombre = "" . ($Rel[0]->nombre) . " (" . $Rel[0]->tipo . ")";
                    $rut_relator = $ses->relator;
                } else {
                    $rut_relator = "";
                    $relator_nombre = "";
                }
                $row4 = str_replace("{RELATOR_SES}", $rut_relator, $row4);
                $row4 = str_replace("{RELATOR_nombre_SES}", $relator_nombre, $row4);
                $tiempo_transcurrido = TiempoTranscurrido_2021($ses->hora_desde, $ses->hora_hasta);
                $row4 = str_replace("{NUMERO_HORAS_SESION}", $tiempo_transcurrido, $row4);
            }
            if ($cuenta_sesiones == "5") {
                $row5 .= file_get_contents("views/capacitacion/imparticion/row_imparticion_por_sesion.html");
                $row5 = str_replace("{ID_LINEA_ENC}", Encodear3($ses->id), $row5);
                $row5 = str_replace("{VALUE_COD_IMPARTICION_FECHA}", $ses->fecha, $row5);
                $row5 = str_replace("{VALUE_COD_IMPARTICION_HORA_DESDE}", $ses->hora_desde, $row5);
                $row5 = str_replace("{VALUE_COD_IMPARTICION_HORA_HASTA}", $ses->hora_hasta, $row5);
                $row5 = str_replace("{VALUE_COD_IMPARTICION_OBSERVACION}", ($ses->observacione), $row5);
                $row5 = str_replace("{ID_IMPARTICION_ENC}", Encodear3($ses->id_inscripcion), $row5);
                $row5 = str_replace("{ID_PROGRAMA_ENC}", "", $row5);
                $row5 = str_replace("{ID_SESION}", "5", $row5);
                $row5 = str_replace("{ID_SESION_NEW}", "6", $row5);
                $row5 = str_replace("{ID_CURSO_ENC}", Encodear3($id_curso), $row5);
                $row5 = str_replace("{ID_IMPARTICION_ENC}", Encodear3($id_inscripcion), $row5);
                $row5 = str_replace("{DISPLAY_AGREGAR_MAS}", "display:none;", $row5);
                $row5 = str_replace("{OPTION_RELATOR_NOMBRE_TIPO}", $options_relatores, $row5);
                $Rel = TraeRelatores2021porId($ses->relator);
                if ($Rel[0]->nombre <> "") {
                    $relator_nombre = "" . ($Rel[0]->nombre) . " (" . $Rel[0]->tipo . ")";
                    $rut_relator = $ses->relator;
                } else {
                    $rut_relator = "";
                    $relator_nombre = "";
                }
                $row5 = str_replace("{RELATOR_SES}", $rut_relator, $row5);
                $row5 = str_replace("{RELATOR_nombre_SES}", $relator_nombre, $row5);
                $tiempo_transcurrido = TiempoTranscurrido_2021($ses->hora_desde, $ses->hora_hasta);
                $row5 = str_replace("{NUMERO_HORAS_SESION}", $tiempo_transcurrido, $row5);
            }
            if ($cuenta_sesiones == "6") {
                $row6 .= file_get_contents("views/capacitacion/imparticion/row_imparticion_por_sesion.html");
                $row6 = str_replace("{ID_LINEA_ENC}", Encodear3($ses->id), $row6);
                $row6 = str_replace("{VALUE_COD_IMPARTICION_FECHA}", $ses->fecha, $row6);
                $row6 = str_replace("{VALUE_COD_IMPARTICION_HORA_DESDE}", $ses->hora_desde, $row6);
                $row6 = str_replace("{VALUE_COD_IMPARTICION_HORA_HASTA}", $ses->hora_hasta, $row6);
                $row6 = str_replace("{VALUE_COD_IMPARTICION_OBSERVACION}", ($ses->observacione), $row6);
                $row6 = str_replace("{ID_IMPARTICION_ENC}", Encodear3($ses->id_inscripcion), $row6);
                $row6 = str_replace("{ID_PROGRAMA_ENC}", "", $row6);
                $row6 = str_replace("{ID_SESION}", "6", $row6);
                $row6 = str_replace("{ID_SESION_NEW}", "7", $row6);
                $row6 = str_replace("{ID_CURSO_ENC}", Encodear3($id_curso), $row6);
                $row6 = str_replace("{ID_IMPARTICION_ENC}", Encodear3($id_inscripcion), $row6);
                $row6 = str_replace("{DISPLAY_AGREGAR_MAS}", "display:none;", $row6);
                $row6 = str_replace("{OPTION_RELATOR_NOMBRE_TIPO}", $options_relatores, $row6);
                $Rel = TraeRelatores2021porId($ses->relator);
                if ($Rel[0]->nombre <> "") {
                    $relator_nombre = "" . ($Rel[0]->nombre) . " (" . $Rel[0]->tipo . ")";
                    $rut_relator = $ses->relator;
                } else {
                    $rut_relator = "";
                    $relator_nombre = "";
                }
                $row6 = str_replace("{RELATOR_SES}", $rut_relator, $row6);
                $row6 = str_replace("{RELATOR_nombre_SES}", $relator_nombre, $row6);
                $tiempo_transcurrido = TiempoTranscurrido_2021($ses->hora_desde, $ses->hora_hasta);
                $row6 = str_replace("{NUMERO_HORAS_SESION}", $tiempo_transcurrido, $row6);
            }
            if ($cuenta_sesiones == "7") {
                $row7 .= file_get_contents("views/capacitacion/imparticion/row_imparticion_por_sesion.html");
                $row7 = str_replace("{ID_LINEA_ENC}", Encodear3($ses->id), $row7);
                $row7 = str_replace("{VALUE_COD_IMPARTICION_FECHA}", $ses->fecha, $row7);
                $row7 = str_replace("{VALUE_COD_IMPARTICION_HORA_DESDE}", $ses->hora_desde, $row7);
                $row7 = str_replace("{VALUE_COD_IMPARTICION_HORA_HASTA}", $ses->hora_hasta, $row7);
                $row7 = str_replace("{VALUE_COD_IMPARTICION_OBSERVACION}", ($ses->observacione), $row7);
                $row7 = str_replace("{ID_IMPARTICION_ENC}", Encodear3($ses->id_inscripcion), $row7);
                $row7 = str_replace("{ID_PROGRAMA_ENC}", "", $row7);
                $row7 = str_replace("{ID_SESION}", "7", $row7);
                $row7 = str_replace("{ID_SESION_NEW}", "8", $row7);
                $row7 = str_replace("{ID_CURSO_ENC}", Encodear3($id_curso), $row7);
                $row7 = str_replace("{ID_IMPARTICION_ENC}", Encodear3($id_inscripcion), $row7);
                $row7 = str_replace("{DISPLAY_AGREGAR_MAS}", "display:none;", $row7);
                $row7 = str_replace("{OPTION_RELATOR_NOMBRE_TIPO}", $options_relatores, $row7);
                $Rel = TraeRelatores2021porId($ses->relator);
                if ($Rel[0]->nombre <> "") {
                    $relator_nombre = "" . ($Rel[0]->nombre) . " (" . $Rel[0]->tipo . ")";
                    $rut_relator = $ses->relator;
                } else {
                    $rut_relator = "";
                    $relator_nombre = "";
                }
                $row7 = str_replace("{RELATOR_SES}", $rut_relator, $row7);
                $row7 = str_replace("{RELATOR_nombre_SES}", $relator_nombre, $row7);
                $tiempo_transcurrido = TiempoTranscurrido_2021($ses->hora_desde, $ses->hora_hasta);
                $row7 = str_replace("{NUMERO_HORAS_SESION}", $tiempo_transcurrido, $row7);
            }
            if ($cuenta_sesiones == "8") {
                $row8 .= file_get_contents("views/capacitacion/imparticion/row_imparticion_por_sesion.html");
                $row8 = str_replace("{ID_LINEA_ENC}", Encodear3($ses->id), $row8);
                $row8 = str_replace("{VALUE_COD_IMPARTICION_FECHA}", $ses->fecha, $row8);
                $row8 = str_replace("{VALUE_COD_IMPARTICION_HORA_DESDE}", $ses->hora_desde, $row8);
                $row8 = str_replace("{VALUE_COD_IMPARTICION_HORA_HASTA}", $ses->hora_hasta, $row8);
                $row8 = str_replace("{VALUE_COD_IMPARTICION_OBSERVACION}", ($ses->observacione), $row8);
                $row8 = str_replace("{ID_IMPARTICION_ENC}", Encodear3($ses->id_inscripcion), $row8);
                $row8 = str_replace("{ID_PROGRAMA_ENC}", "", $row8);
                $row8 = str_replace("{ID_SESION}", "8", $row8);
                $row8 = str_replace("{ID_SESION_NEW}", "9", $row8);
                $row8 = str_replace("{ID_CURSO_ENC}", Encodear3($id_curso), $row8);
                $row8 = str_replace("{ID_IMPARTICION_ENC}", Encodear3($id_inscripcion), $row8);
                $row8 = str_replace("{DISPLAY_AGREGAR_MAS}", "display:none;", $row8);
                $row8 = str_replace("{OPTION_RELATOR_NOMBRE_TIPO}", $options_relatores, $row8);
                $Rel = TraeRelatores2021porId($ses->relator);
                if ($Rel[0]->nombre <> "") {
                    $relator_nombre = "" . ($Rel[0]->nombre) . " (" . $Rel[0]->tipo . ")";
                    $rut_relator = $ses->relator;
                } else {
                    $rut_relator = "";
                    $relator_nombre = "";
                }
                $row8 = str_replace("{RELATOR_SES}", $rut_relator, $row8);
                $row8 = str_replace("{RELATOR_nombre_SES}", $relator_nombre, $row8);
                $tiempo_transcurrido = TiempoTranscurrido_2021($ses->hora_desde, $ses->hora_hasta);
                $row8 = str_replace("{NUMERO_HORAS_SESION}", $tiempo_transcurrido, $row8);
            }
            if ($cuenta_sesiones == "9") {
                $row9 .= file_get_contents("views/capacitacion/imparticion/row_imparticion_por_sesion.html");
                $row9 = str_replace("{ID_LINEA_ENC}", Encodear3($ses->id), $row9);
                $row9 = str_replace("{VALUE_COD_IMPARTICION_FECHA}", $ses->fecha, $row9);
                $row9 = str_replace("{VALUE_COD_IMPARTICION_HORA_DESDE}", $ses->hora_desde, $row9);
                $row9 = str_replace("{VALUE_COD_IMPARTICION_HORA_HASTA}", $ses->hora_hasta, $row9);
                $row9 = str_replace("{VALUE_COD_IMPARTICION_OBSERVACION}", ($ses->observacione), $row9);
                $row9 = str_replace("{ID_IMPARTICION_ENC}", Encodear3($ses->id_inscripcion), $row9);
                $row9 = str_replace("{ID_PROGRAMA_ENC}", "", $row9);
                $row9 = str_replace("{ID_SESION}", "9", $row9);
                $row9 = str_replace("{ID_SESION_NEW}", "10", $row9);
                $row9 = str_replace("{ID_CURSO_ENC}", Encodear3($id_curso), $row9);
                $row9 = str_replace("{DISPLAY_AGREGAR_MAS}", "display:none;", $row9);
                $row9 = str_replace("{ID_IMPARTICION_ENC}", Encodear3($id_inscripcion), $row9);
                $row9 = str_replace("{OPTION_RELATOR_NOMBRE_TIPO}", $options_relatores, $row9);
                $Rel = TraeRelatores2021porId($ses->relator);
                if ($Rel[0]->nombre <> "") {
                    $relator_nombre = "" . ($Rel[0]->nombre) . " (" . $Rel[0]->tipo . ")";
                    $rut_relator = $ses->relator;
                } else {
                    $rut_relator = "";
                    $relator_nombre = "";
                }
                $row9 = str_replace("{RELATOR_SES}", $rut_relator, $row9);
                $row9 = str_replace("{RELATOR_nombre_SES}", $relator_nombre, $row9);
                $tiempo_transcurrido = TiempoTranscurrido_2021($ses->hora_desde, $ses->hora_hasta);
                $row9 = str_replace("{NUMERO_HORAS_SESION}", $tiempo_transcurrido, $row9);
            }
            if ($cuenta_sesiones == "10") {
                $row10 .= file_get_contents("views/capacitacion/imparticion/row_imparticion_por_sesion.html");
                $row10 = str_replace("{ID_LINEA_ENC}", Encodear3($ses->id), $row10);
                $row10 = str_replace("{VALUE_COD_IMPARTICION_FECHA}", $ses->fecha, $row10);
                $row10 = str_replace("{VALUE_COD_IMPARTICION_HORA_DESDE}", $ses->hora_desde, $row10);
                $row10 = str_replace("{VALUE_COD_IMPARTICION_HORA_HASTA}", $ses->hora_hasta, $row10);
                $row10 = str_replace("{VALUE_COD_IMPARTICION_OBSERVACION}", ($ses->observacione), $row10);
                $row10 = str_replace("{ID_IMPARTICION_ENC}", Encodear3($ses->id_inscripcion), $row10);
                $row10 = str_replace("{ID_PROGRAMA_ENC}", "", $row10);
                $row10 = str_replace("{ID_SESION}", "10", $row10);
                $row10 = str_replace("{ID_SESION_NEW}", "11", $row10);
                $row10 = str_replace("{ID_CURSO_ENC}", Encodear3($id_curso), $row10);
                $row10 = str_replace("{ID_IMPARTICION_ENC}", Encodear3($id_inscripcion), $row10);
                $row10 = str_replace("{DISPLAY_AGREGAR_MAS}", "display:none;", $row10);
                $row10 = str_replace("{OPTION_RELATOR_NOMBRE_TIPO}", $options_relatores, $row10);
                $Rel = TraeRelatores2021porId($ses->relator);
                if ($Rel[0]->nombre <> "") {
                    $relator_nombre = "" . ($Rel[0]->nombre) . " (" . $Rel[0]->tipo . ")";
                    $rut_relator = $ses->relator;
                } else {
                    $rut_relator = "";
                    $relator_nombre = "";
                }
                $row10 = str_replace("{RELATOR_SES}", $rut_relator, $row10);
                $row10 = str_replace("{RELATOR_nombre_SES}", $relator_nombre, $row10);
                $tiempo_transcurrido = TiempoTranscurrido_2021($ses->hora_desde, $ses->hora_hasta);
                $row10 = str_replace("{NUMERO_HORAS_SESION}", $tiempo_transcurrido, $row10);
            }
            if ($cuenta_sesiones == "11") {
                $row11 .= file_get_contents("views/capacitacion/imparticion/row_imparticion_por_sesion.html");
                $row11 = str_replace("{ID_LINEA_ENC}", Encodear3($ses->id), $row11);
                $row11 = str_replace("{VALUE_COD_IMPARTICION_FECHA}", $ses->fecha, $row11);
                $row11 = str_replace("{VALUE_COD_IMPARTICION_HORA_DESDE}", $ses->hora_desde, $row11);
                $row11 = str_replace("{VALUE_COD_IMPARTICION_HORA_HASTA}", $ses->hora_hasta, $row11);
                $row11 = str_replace("{VALUE_COD_IMPARTICION_OBSERVACION}", ($ses->observacione), $row11);
                $row11 = str_replace("{ID_IMPARTICION_ENC}", Encodear3($ses->id_inscripcion), $row11);
                $row11 = str_replace("{ID_PROGRAMA_ENC}", "", $row11);
                $row11 = str_replace("{ID_SESION}", "11", $row11);
                $row11 = str_replace("{ID_SESION_NEW}", "12", $row11);
                $row11 = str_replace("{ID_CURSO_ENC}", Encodear3($id_curso), $row11);
                $row11 = str_replace("{ID_IMPARTICION_ENC}", Encodear3($id_inscripcion), $row11);
                $row11 = str_replace("{DISPLAY_AGREGAR_MAS}", "display:none;", $row11);
                $row11 = str_replace("{OPTION_RELATOR_NOMBRE_TIPO}", $options_relatores, $row11);
                $Rel = TraeRelatores2021porId($ses->relator);
                if ($Rel[0]->nombre <> "") {
                    $relator_nombre = "" . ($Rel[0]->nombre) . " (" . $Rel[0]->tipo . ")";
                    $rut_relator = $ses->relator;
                } else {
                    $rut_relator = "";
                    $relator_nombre = "";
                }
                $row11 = str_replace("{RELATOR_SES}", $rut_relator, $row11);
                $row11 = str_replace("{RELATOR_nombre_SES}", $relator_nombre, $row11);
                $tiempo_transcurrido = TiempoTranscurrido_2021($ses->hora_desde, $ses->hora_hasta);
                $row11 = str_replace("{NUMERO_HORAS_SESION}", $tiempo_transcurrido, $row11);
            }
            if ($cuenta_sesiones == "12") {
                $row12 .= file_get_contents("views/capacitacion/imparticion/row_imparticion_por_sesion.html");
                $row12 = str_replace("{ID_LINEA_ENC}", Encodear3($ses->id), $row12);
                $row12 = str_replace("{VALUE_COD_IMPARTICION_FECHA}", $ses->fecha, $row12);
                $row12 = str_replace("{VALUE_COD_IMPARTICION_HORA_DESDE}", $ses->hora_desde, $row12);
                $row12 = str_replace("{VALUE_COD_IMPARTICION_HORA_HASTA}", $ses->hora_hasta, $row12);
                $row12 = str_replace("{VALUE_COD_IMPARTICION_OBSERVACION}", ($ses->observacione), $row12);
                $row12 = str_replace("{ID_IMPARTICION_ENC}", Encodear3($ses->id_inscripcion), $row12);
                $row12 = str_replace("{ID_PROGRAMA_ENC}", "", $row12);
                $row12 = str_replace("{ID_SESION}", "12", $row12);
                $row12 = str_replace("{ID_SESION_NEW}", "", $row12);
                $row12 = str_replace("{ID_CURSO_ENC}", Encodear3($id_curso), $row12);
                $row12 = str_replace("{ID_IMPARTICION_ENC}", Encodear3($id_inscripcion), $row12);
                $row12 = str_replace("{DISPLAY_AGREGAR_MAS}", "display:none;", $row12);
                $row12 = str_replace("{OPTION_RELATOR_NOMBRE_TIPO}", $options_relatores, $row12);
                $Rel = TraeRelatores2021porId($ses->relator);
                if ($Rel[0]->nombre <> "") {
                    $relator_nombre = "" . ($Rel[0]->nombre) . " (" . $Rel[0]->tipo . ")";
                    $rut_relator = $ses->relator;
                } else {
                    $rut_relator = "";
                    $relator_nombre = "";
                }
                $row12 = str_replace("{RELATOR_SES}", $rut_relator, $row12);
                $row12 = str_replace("{RELATOR_nombre_SES}", $relator_nombre, $row12);
                $tiempo_transcurrido = TiempoTranscurrido_2021($ses->hora_desde, $ses->hora_hasta);
                $row12 = str_replace("{NUMERO_HORAS_SESION}", $tiempo_transcurrido, $row12);
            }


        }
    } else {
        //echo "<h1>b</h1>";
        $row1 .= file_get_contents("views/capacitacion/imparticion/row_imparticion_por_sesion.html");
        $row1 = str_replace("{ID_LINEA_ENC}", "", $row1);
        $row1 = str_replace("{VALUE_COD_IMPARTICION_FECHA}", "", $row1);
        $row1 = str_replace("{VALUE_COD_IMPARTICION_HORA_DESDE}", "", $row1);
        $row1 = str_replace("{VALUE_COD_IMPARTICION_HORA_HASTA}", "", $row1);
        $row1 = str_replace("{VALUE_COD_IMPARTICION_OBSERVACION}", "", $row1);
        $row1 = str_replace("{ID_IMPARTICION_ENC}", $i, $row1);
        $row1 = str_replace("{ID_PROGRAMA_ENC}", $idpbbdd, $row1);
        $row1 = str_replace("{ID_SESION}", "1", $row1);
        $row1 = str_replace("{ID_CURSO_ENC}", Encodear3($id_curso), $row1);
        $row1 = str_replace("{ID_IMPARTICION_ENC}", Encodear3($id_inscripcion), $row1);
        $row1 = str_replace("{OPTION_RELATOR_NOMBRE_TIPO}", $options_relatores, $row1);
        $row1 = str_replace("{RELATOR_SES}", $rut_relator, $row1);
        $row1 = str_replace("{RELATOR_nombre_SES}", "", $row1);
        //$tiempo_transcurrido=TiempoTranscurrido_2021($ses->hora_desde, $ses->hora_hasta);
        $row1 = str_replace("{NUMERO_HORAS_SESION}", "", $row1);


    }

    //echo "<h3>cuenta_sesiones $cuenta_sesiones</h3>";
    $nueva_sesion = $cuenta_sesiones + 1;
    $rowNew .= file_get_contents("views/capacitacion/imparticion/row_imparticion_por_sesion_new.html");
    $rowNew = str_replace("{ID_LINEA_ENC}", Encodear3($ses->id), $rowNew);
    $rowNew = str_replace("{VALUE_COD_IMPARTICION_FECHA}", $ses->fecha, $rowNew);
    $rowNew = str_replace("{VALUE_COD_IMPARTICION_HORA_DESDE}", $ses->hora_desde, $rowNew);
    $rowNew = str_replace("{VALUE_COD_IMPARTICION_HORA_HASTA}", $ses->hora_hasta, $rowNew);
    $rowNew = str_replace("{VALUE_COD_IMPARTICION_OBSERVACION}", $ses->observaciones, $rowNew);
    $rowNew = str_replace("{ID_IMPARTICION_ENC}", Encodear3($ses->id_inscripcion), $rowNew);
    $rowNew = str_replace("{ID_PROGRAMA_ENC}", "", $rowNew);
    $rowNew = str_replace("{ID_SESION}", $nueva_sesion, $rowNew);
    $rowNew = str_replace("{ID_SESION_NEW}", $nueva_sesion, $rowNew);
    $rowNew = str_replace("{ID_CURSO_ENC}", Encodear3($id_curso), $rowNew);
    $rowNew = str_replace("{ID_IMPARTICION_ENC}", Encodear3($id_inscripcion), $rowNew);
    $rowNew = str_replace("{DISPLAY_AGREGAR_MAS}", "", $rowNew);
    //echo "<br>-->options_relatores $options_relatores<br>";
    $rowNew = str_replace("{OPTION_RELATOR_NOMBRE_TIPO}", $options_relatores, $rowNew);
    $rowNew = str_replace("{RELATOR_SES}", "", $rowNew);
    $rowNew = str_replace("{RELATOR_nombre_SES}", "", $rowNew);
    //$tiempo_transcurrido=TiempoTranscurrido_2021($ses->hora_desde, $ses->hora_hasta);
    $rowNew = str_replace("{NUMERO_HORAS_SESION}", "", $rowNew);


    $PRINCIPAL = str_replace("{ID_IMPARTICION_ENC}", $i, $PRINCIPAL);
    $PRINCIPAL = str_replace("{ID_PROGRAMA_ENC}", $idpbbdd, $PRINCIPAL);

    $PRINCIPAL = str_replace("{FORMULARIO_HORARIOS_LINEA_1}", $row1, $PRINCIPAL);
    $PRINCIPAL = str_replace("{FORMULARIO_HORARIOS_LINEA_2}", $row2, $PRINCIPAL);
    $PRINCIPAL = str_replace("{FORMULARIO_HORARIOS_LINEA_3}", $row3, $PRINCIPAL);
    $PRINCIPAL = str_replace("{FORMULARIO_HORARIOS_LINEA_4}", $row4, $PRINCIPAL);
    $PRINCIPAL = str_replace("{FORMULARIO_HORARIOS_LINEA_5}", $row5, $PRINCIPAL);
    $PRINCIPAL = str_replace("{FORMULARIO_HORARIOS_LINEA_6}", $row6, $PRINCIPAL);
    $PRINCIPAL = str_replace("{FORMULARIO_HORARIOS_LINEA_7}", $row7, $PRINCIPAL);
    $PRINCIPAL = str_replace("{FORMULARIO_HORARIOS_LINEA_8}", $row8, $PRINCIPAL);
    $PRINCIPAL = str_replace("{FORMULARIO_HORARIOS_LINEA_9}", $row9, $PRINCIPAL);
    $PRINCIPAL = str_replace("{FORMULARIO_HORARIOS_LINEA_10}", $row10, $PRINCIPAL);
    $PRINCIPAL = str_replace("{FORMULARIO_HORARIOS_LINEA_11}", $row11, $PRINCIPAL);
    $PRINCIPAL = str_replace("{FORMULARIO_HORARIOS_LINEA_12}", $row12, $PRINCIPAL);

    $PRINCIPAL = str_replace("{FORMULARIO_HORARIOS_LINEA_AGREGAR_MAS}", $rowNew, $PRINCIPAL);


    $rowFiFTew .= file_get_contents("views/capacitacion/imparticion/row_imparticion_por_fecha_inicio_termino_new.html");
    $rowFiFTew = str_replace("{ID_LINEA_ENC}", Encodear3($ses->id), $rowFiFTew);
    $rowFiFTew = str_replace("{VALUE_COD_IMPARTICION_FECHA}", $ses->fecha, $rowFiFTew);
    $rowFiFTew = str_replace("{VALUE_COD_IMPARTICION_HORA_DESDE}", $ses->hora_desde, $rowFiFTew);
    $rowFiFTew = str_replace("{VALUE_COD_IMPARTICION_HORA_HASTA}", $ses->hora_hasta, $rowFiFTew);
    $rowFiFTew = str_replace("{VALUE_COD_IMPARTICION_OBSERVACION}", $ses->observaciones, $rowFiFTew);
    $rowFiFTew = str_replace("{ID_IMPARTICION_ENC}", Encodear3($ses->id_inscripcion), $rowFiFTew);
    $rowFiFTew = str_replace("{ID_PROGRAMA_ENC}", "", $rowFiFTew);
    $rowFiFTew = str_replace("{ID_SESION}", $nueva_sesion, $rowFiFTew);
    $rowFiFTew = str_replace("{ID_SESION_NEW}", $nueva_sesion, $rowFiFTew);
    $rowFiFTew = str_replace("{ID_CURSO_ENC}", Encodear3($id_curso), $rowFiFTew);
    $rowFiFTew = str_replace("{ID_IMPARTICION_ENC}", Encodear3($id_inscripcion), $rowFiFTew);
    $rowFiFTew = str_replace("{DISPLAY_AGREGAR_MAS}", "", $rowFiFTew);
    $rowFiFTew = str_replace("{OPTION_RELATOR_NOMBRE_TIPO}", $options_relatores, $rowFiFTew);

    $PRINCIPAL = str_replace("{FORMULARIO_HORARIOS_LINEA_FECHA_INICIO_TERMINO}", $rowFiFTew, $PRINCIPAL);

    $rowFiFTewELEARNING .= file_get_contents("views/capacitacion/imparticion/row_imparticion_por_fecha_inicio_termino_new_elearning.html");
    $rowFiFTewELEARNING = str_replace("{ID_LINEA_ENC}", Encodear3($ses->id), $rowFiFTewELEARNING);
    $rowFiFTewELEARNING = str_replace("{VALUE_COD_IMPARTICION_FECHA}", $ses->fecha, $rowFiFTewELEARNING);
    $rowFiFTewELEARNING = str_replace("{VALUE_COD_IMPARTICION_HORA_DESDE}", $ses->hora_desde, $rowFiFTewELEARNING);
    $rowFiFTewELEARNING = str_replace("{VALUE_COD_IMPARTICION_HORA_HASTA}", $ses->hora_hasta, $rowFiFTewELEARNING);
    $rowFiFTewELEARNING = str_replace("{VALUE_COD_IMPARTICION_OBSERVACION}", $ses->observaciones, $rowFiFTewELEARNING);
    $rowFiFTewELEARNING = str_replace("{ID_IMPARTICION_ENC}", Encodear3($ses->id_inscripcion), $rowFiFTewELEARNING);
    $rowFiFTewELEARNING = str_replace("{ID_PROGRAMA_ENC}", "", $rowFiFTewELEARNING);
    $rowFiFTewELEARNING = str_replace("{ID_SESION}", $nueva_sesion, $rowFiFTewELEARNING);
    $rowFiFTewELEARNING = str_replace("{ID_SESION_NEW}", $nueva_sesion, $rowFiFTewELEARNING);
    $rowFiFTewELEARNING = str_replace("{ID_CURSO_ENC}", Encodear3($id_curso), $rowFiFTewELEARNING);
    $rowFiFTewELEARNING = str_replace("{ID_IMPARTICION_ENC}", Encodear3($id_inscripcion), $rowFiFTewELEARNING);
    $rowFiFTewELEARNING = str_replace("{DISPLAY_AGREGAR_MAS}", "", $rowFiFTewELEARNING);


    $Elearning_opcional = BuscaOpcional_rel_lms_id_curso_id_inscripcion($id_inscripcion);
    //echo "<br>Elearning_opcional $Elearning_opcional";

    if ($Elearning_opcional == "1") {

        $rowFiFTewELEARNING = str_replace("{CHECKED_OPCIONAL}", " checked ", $rowFiFTewELEARNING);
        $rowFiFTewELEARNING = str_replace("{CHECKED_OBLIGATORIA}", "   ", $rowFiFTewELEARNING);

        $PRINCIPAL = str_replace("{SELECT_OPCIONAL}", " selected ", $PRINCIPAL);
        $PRINCIPAL = str_replace("{SELECT_OBLIGATORIA}", "  ", $PRINCIPAL);

    } else {

        $rowFiFTewELEARNING = str_replace("{CHECKED_OPCIONAL}", "  ", $rowFiFTewELEARNING);
        $rowFiFTewELEARNING = str_replace("{CHECKED_OBLIGATORIA}", " checked  ", $rowFiFTewELEARNING);
        $PRINCIPAL = str_replace("{SELECT_OPCIONAL}", " ", $PRINCIPAL);
        $PRINCIPAL = str_replace("{SELECT_OBLIGATORIA}", " selected ", $PRINCIPAL);


    }


    /*$ejecutivos=TraeEjecutivos2021($_SESSION["id_empresa"]);
    //print_r($ejecutivos);
    $options_ejecutivos="";
    foreach($ejecutivos as $ejec){
            $options_ejecutivos.='<option value="'.$ejec->rut.'" >'.$ejec->nombre.'</option>';
    }
    $rowFiFTewELEARNING = str_replace("{OPTIONS_EJECUTIVOS}",($options_ejecutivos),$rowFiFTewELEARNING);
		*/

    $PRINCIPAL = str_replace("{FORMULARIO_HORARIOS_LINEA_FECHA_INICIO_TERMINO_ELEARNING}", $rowFiFTewELEARNING, $PRINCIPAL);


    return ($PRINCIPAL);
}

function Dashboard_Training_IdImparticion_idCurso_MiRut($id_imparticion, $id_curso, $rut)
{


    $Num_Cursos_Pendientes = 0;
    $Num_Cursos_Aprobados = 0;
    $Num_Cursos_Reprobados = 0;
    $Num_Cursos_NoIniciado = 0;
    $Num_Cursos_Proceso = 0;
    $Num_Programas_Finalizados = 0;
    $Inscritos = Dashboard_Training_IdImparticion_idCurso_MiRut_data($id_imparticion, $id_curso, $rut);
    //print_r($Inscritos);

    //echo count($Inscritos);
    foreach ($Inscritos as $unico) {
        $cuenta_inscritos++;
        $Reporte = Dashboard_check_Lms_InscripcionCierre_data($id_imparticion, $id_curso, $unico->rut);
        //echo "<br>".$cuenta_inscritos." ".$unico->rut." - ".$Reporte[0]->estado_descripcion;

        //echo "<br>Estado ".$Reporte[0]->estado;
        if ($Reporte[0]->estado_descripcion == "NO_INICIADO" or $Reporte[0]->estado_descripcion == "") {
            $Num_Cursos_NoIniciado++;
        }
        if ($Reporte[0]->estado_descripcion == "EN_PROCESO") {
            $Num_Cursos_Proceso++;
        }
        if ($Reporte[0]->estado_descripcion == "APROBADO") {
            $Num_Cursos_Aprobados++;
        }

        //echo  $Num_Cursos_Aprobados;

        if ($Reporte[0]->estado_descripcion == "REPROBADO" or $Reporte[0]->estado_descripcion == "REPROBADO_INASISTENCIA" or $Reporte[0]->estado_descripcion == "REPROBADO_POR_INASISTENCIA") {
            $Num_Cursos_Reprobados++;
        }
    }


    $suma_cursos = $Num_Cursos_NoIniciado + $Num_Cursos_Proceso + $Num_Cursos_Aprobados + $Num_Cursos_Reprobados;
    $Num_Cursos_Pendientes = $Num_Cursos_NoIniciado + $Num_Cursos_Proceso;
    $Num_Curso_Finalizados = $Num_Cursos_Aprobados + $Num_Cursos_Reprobados;

    /* echo "<br><br>--> Num_Cursos_Pendientes $Num_Cursos_Pendientes, Num_Cursos_Aprobados $Num_Cursos_Aprobados, Num_Cursos_Reprobados $Num_Cursos_Reprobados<br>
	    					suma cursos $suma_cursos<br>";*/


    //echo "<h3>Estado Programa ".$Estado_Programa."</h3><h4>suma_cursos $suma_cursos </h4>";


    //echo "<br>- Num Total ".count($Prog)." aprobados $Num_Programas_Aprobados, reprobados $Num_Programas_Reprobados, pendientes $Num_Programas_Pendientes";
    $row_dash_equipo_directo .= file_get_contents("views/dashboard_equipo_training/row_dashboard_equipo_directo.html");

    if ($suma_cursos == 0) {
        // Set default values or handle the error, for example:
        $avance_finalizados = 0;
        $avance_aprobados = 0;
        $avance_reprobados = 0;
        $avance_enproceso = 0;
        $avance_noiniciados = 0;
        $avance_pendientes = 0;
    } else {
        $avance_finalizados = round(100 * $Num_Curso_Finalizados / $suma_cursos);
        $avance_aprobados = round(100 * $Num_Cursos_Aprobados / $suma_cursos);
        $avance_reprobados = round(100 * $Num_Cursos_Reprobados / $suma_cursos);
        $avance_enproceso = round(100 * $Num_Cursos_Proceso / $suma_cursos);
        $avance_noiniciados = round(100 * $Num_Cursos_NoIniciado / $suma_cursos);
        $avance_pendientes = round(100 * $Num_Cursos_Pendientes / $suma_cursos);
    }

    /* echo "<br>avance_finalizados $avance_finalizados,
				avance_aprobados $avance_aprobados,
				avance_reprobados $avance_reprobados,
				avance_enproceso $avance_enproceso,
				avance_noiniciados $avance_noiniciados,
				avance_pendientes $avance_pendientes";

				echo "<br><br>finalizados $Num_Programas_Finalizados,
				aprobados $Num_Programas_Aprobados,
				reprobados $Num_Programas_Reprobados,
				enproceso $Num_Programas_Proceso,
				noiniciados $Num_Programas_NoIniciado,
				Pendientes $avance_pendientes";	*/

    $arreglo[0] = round($avance_finalizados);
    $arreglo[1] = round($avance_aprobados);
    $arreglo[2] = round($avance_reprobados);
    $arreglo[3] = round($avance_enproceso);
    $arreglo[4] = round($avance_noiniciados);
    $arreglo[5] = round($avance_pendientes);

    $arreglo[10] = round($Num_Curso_Finalizados);
    $arreglo[11] = round($Num_Cursos_Aprobados);
    $arreglo[12] = round($Num_Cursos_Reprobados);
    $arreglo[13] = round($Num_Cursos_Proceso);
    $arreglo[14] = round($Num_Cursos_NoIniciado);
    $arreglo[15] = round($Num_Cursos_Pendientes);
    $arreglo[16] = count($Inscritos);

    return $arreglo;

}
function VeReportesXImp2021_fn($PRINCIPAL, $id_imparticion, $id_curso, $rut)
{
    //echo "<br>VeReportes $id_imparticion, $id_curso, $rut<br>";exit();
    $row .= file_get_contents("views/capacitacion/reportes/dashboard.html");
    $row = str_replace("{FECHA_SESION}", ($ses->fecha), $row);
    $row = str_replace("{ID_IMPARTICION_ENC}", ($_GET["i"]), $row);

    $Resultados_Tu_Avance = Dashboard_Training_IdImparticion_idCurso_MiRut($id_imparticion, $id_curso, $rut);
    //print_r($Resultados_Tu_Avance);
    $row = str_replace("{NUM_FINALIZADOS}", $Resultados_Tu_Avance[0], $row);
    $row = str_replace("{NUM_EN_PROCESO}", $Resultados_Tu_Avance[3], $row);
    $row = str_replace("{NUM_NO_INICIADOS}", $Resultados_Tu_Avance[4], $row);
    $row = str_replace("{NUM_APROBADOS}", $Resultados_Tu_Avance[1], $row);
    $row = str_replace("{NUM_REPROBADOS}", $Resultados_Tu_Avance[2], $row);
    $row = str_replace("{NUM_FINALIZADOS_TXT}", $Resultados_Tu_Avance[10], $row);
    $row = str_replace("{NUM_EN_PROCESO_TXT}", $Resultados_Tu_Avance[13], $row);
    $row = str_replace("{NUM_NO_INICIADOS_TXT}", $Resultados_Tu_Avance[14], $row);
    $row = str_replace("{NUM_APROBADOS_TXT}", $Resultados_Tu_Avance[11], $row);
    $row = str_replace("{NUM_REPROBADOS_TXT}", $Resultados_Tu_Avance[12], $row);
    $row = str_replace("{NUM_INSCRITOS_TXT}", $Resultados_Tu_Avance[16], $row);

    $datos_imparticion_V2 = DatosInscripcionImparticionesV2($id_imparticion, $_SESSION["id_empresa"]);
    //echo "id_imparticion $id_imparticion";print_r($datos_imparticion_V2);

    $row_excel_enc_sat .= "id_curso;curso;id_imparticion;fecha_inicio;fecha_termino\r\n";
    $row_excel_enc_sat .= ""
        . $datos_imparticion_V2[0]->id_curso . ";"
        . $datos_imparticion_V2[0]->nombre_curso . ";"
        . $datos_imparticion_V2[0]->codigo_inscripcion . ";"
        . $datos_imparticion_V2[0]->fecha_inicio . ";"
        . $datos_imparticion_V2[0]->fecha_termino . "\r\n";

    $EncSat = EncuestaSatisfaccion_IdImparticionIdCurso_data($id_imparticion, $id_curso);
    $cuenta = count($EncSat);

    $row_excel_enc_sat .= "\r\n";
    $row_excel_enc_sat .= "encuestas respondidas;$cuenta\r\n";
    $row_excel_enc_sat .= "\r\n";

    //echo "<br>encuestas_contestadas ".$cuenta;
    $Preg = EncuestaSatisfaccion_BuscaPreguntas($EncSat[0]->id_encuesta);
    $row_encuesta_satisfaccion_pregunta .= "
							<div class='row'>
									<div class='col-lg-6'>
										<div class='card text-white bg-primary  text-dark mb-3' style='max-width: 100%'>
										  <div class='card-header  text-center font_18px mg_20topbottom' style='text-align: left !important;'>
										  	Pregunta
										  </div>
										</div>
									</div>
									<div class='col-lg-6'>
											<div class='row'>
												<div class='col-lg-2' style='color: #0d6efd;'><br>Calificaci&oacute;n</div>
												<div class='col-lg-2' style='color: #0d6efd;'><br>Cantidad</div>
												<div class='col-lg-2' style='color: #0d6efd;'><br>Porcentaje</div>
											</div>
									</div>
									<div class='col-lg-12'><hr></div>
						</div>";

    $row_excel_enc_sat .= "pregunta;cantidad1;porcentaje1;cantidad2;porcentaje2;cantidad3;porcentaje3;cantidad4;porcentaje5;cantidad5;porcentaje5\r\n";
    foreach ($Preg as $unico) {
        //Respuestas por Pregunta
        $Resp = EncuestaSatisfaccion_BuscaRespuestas($EncSat[0]->id_encuesta, $unico->id_pregunta, $id_imparticion, $id_curso);
        $cuenta1 = 0;
        $cuenta2 = 0;
        $cuenta3 = 0;
        $cuenta4 = 0;
        $cuenta5 = 0;
        $cuenta_todas = 0;
        foreach ($Resp as $unicoR) {
            if ($unicoR->respuesta == "1") {
                $cuenta1++;
            }
            if ($unicoR->respuesta == "2") {
                $cuenta2++;
            }
            if ($unicoR->respuesta == "3") {
                $cuenta3++;
            }
            if ($unicoR->respuesta == "4") {
                $cuenta4++;
            }
            if ($unicoR->respuesta == "5") {
                $cuenta5++;
            }
            $cuenta_todas++;
            $porc_1 = round(100 * $cuenta1 / $cuenta_todas);
            $porc_2 = round(100 * $cuenta2 / $cuenta_todas);
            $porc_3 = round(100 * $cuenta3 / $cuenta_todas);
            $porc_4 = round(100 * $cuenta4 / $cuenta_todas);
            $porc_5 = round(100 * $cuenta5 / $cuenta_todas);
        }


        $respuestas = "
							<div class='row'>
								<div class='col-lg-2'>1</div>
								<div class='col-lg-2'>" . $cuenta1 . "</div>
								<div class='col-lg-2'>" . $porc_1 . "%</div>
								<div class='col-lg-6'> <div class='progress' style='height: 13px;'><div class='progress-bar' role='progressbar' style='width: " . $porc_1 . "%' aria-valuenow='" . $porc_1 . "' aria-valuemin='0' aria-valuemax='100'></div></div></div>
							</div>
							<div class='row'>
								<div class='col-lg-2'>2</div>
								<div class='col-lg-2'>" . $cuenta2 . "</div>
								<div class='col-lg-2'>" . $porc_2 . "%</div>
								<div class='col-lg-6'> <div class='progress' style='height: 13px;'><div class='progress-bar' role='progressbar' style='width: " . $porc_2 . "%' aria-valuenow='" . $porc_2 . "' aria-valuemin='0' aria-valuemax='100'></div></div></div>
							</div>
							<div class='row'>
								<div class='col-lg-2'>3</div>
								<div class='col-lg-2'>" . $cuenta3 . "</div>
								<div class='col-lg-2'>" . $porc_3 . "%</div>
								<div class='col-lg-6'> <div class='progress' style='height: 13px;'><div class='progress-bar' role='progressbar' style='width: " . $porc_3 . "%' aria-valuenow='" . $porc_3 . "' aria-valuemin='0' aria-valuemax='100'></div></div></div>
							</div>
							<div class='row'>
								<div class='col-lg-2'>4</div>
								<div class='col-lg-2'>" . $cuenta4 . "</div>
								<div class='col-lg-2'>" . $porc_4 . "%</div>
								<div class='col-lg-6'> <div class='progress' style='height: 13px;'><div class='progress-bar' role='progressbar' style='width: " . $porc_4 . "%' aria-valuenow='" . $porc_4 . "' aria-valuemin='0' aria-valuemax='100'></div></div></div>
							</div>
							<div class='row'>
								<div class='col-lg-2'>5</div>
								<div class='col-lg-2'>" . $cuenta5 . "</div>
								<div class='col-lg-2'>" . $porc_5 . "%</div>
								<div class='col-lg-6'> <div class='progress' style='height: 13px;'><div class='progress-bar' role='progressbar' style='width: " . $porc_5 . "%' aria-valuenow='" . $porc_5 . "' aria-valuemin='0' aria-valuemax='100'></div></div></div>
							</div>";
        $row_encuesta_satisfaccion_pregunta .= "
							<div class='row'>
									<div class='col-lg-6'>
										<div class='card text-white bg-primary  text-dark mb-3' style='max-width: 100%'>
										  <div class='card-header  text-center font_18px mg_20topbottom' style='text-align: left !important;height: 110px;'>
										  	" . ($unico->pregunta) . "
										  </div>
										</div>
									</div>
									<div class='col-lg-6'>
										" . $respuestas . "
									</div>
									<div class='col-lg-12'><hr></div>
						</div>";

        $row_excel_enc_sat .= "" . $unico->pregunta . ";" . $cuenta1 . ";" . $porc_1 . "%;" . $cuenta2 . ";" . $porc_2 . "%;" . $cuenta3 . ";" . $porc_3 . "%;" . $cuenta4 . ";" . $porc_4 . "%;" . $cuenta5 . ";" . $porc_5 . "%\r\n";

    }

    $Com = EncuestaSatisfaccion_BuscaComentarios($EncSat[0]->id_encuesta, $id_imparticion, $id_curso);
    $row_encuesta_satisfaccion_pregunta .= "
							<div class='row'>
									<div class='col-lg-12'>
										<div class='card text-white bg-primary  text-dark mb-3' style='max-width: 100%'>
										  <div class='card-header  text-center font_18px mg_20topbottom' style='text-align: left !important;'>
										  	Comentarios
										  </div>
										</div>
									</div>
						</div>";
    $row_excel_enc_sat .= "\r\n";
    $row_excel_enc_sat .= "Comentarios\r\n";
    foreach ($Com as $unicoC) {
        $row_encuesta_satisfaccion_pregunta .= "
								<div class='row'>
										<div class='col-lg-12'>
											<div class='' style='max-width: 100%;     padding: 15px;'>
											  <div class='' style='text-align: left !important;'>
											  	" . ($unicoC->comentario) . "
											  </div>
											</div>
										</div>
							</div>";

        $row_excel_enc_sat .= "" . $unicoC->comentario . "\r\n";

    }
    $row = str_replace("{NUM_ENCUESTAS_RESPONDIDAS}", $cuenta, $row);
    $row = str_replace("{LISTA_PREGUNTAS_ENC_SATISFACCION}", $row_encuesta_satisfaccion_pregunta, $row);
    $PRINCIPAL = str_replace("{DASHBOARD_REPORTES_2021}", $row, $PRINCIPAL);

    if ($_GET["excel"] == "2") {
        // EXCEL ENCUESTA SATISFACCION
        header('Content-Description: File Transfer');
        header('Content-Type: application/csv');
        header("Content-Disposition: attachment; filename=Encuesta_Satisfaccion_Curso_" . $datos_imparticion_V2[0]->nombre_curso . "_ID_Imparticion_" . $codigo_inscripcion . ".csv");
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

        echo $row_excel_enc_sat;
        exit();
    }


    return ($PRINCIPAL);
}

// CREACION DE CURSOS PRESENCIALES



function ListadoMallasAdmin1($PRINCIPAL, $id_empresa, $excel, $id_curso, $llamada)
{
    //echo "$id_empresa, excel $excel, $id_curso, $llamada";
    $mallas = Listado_TotalMallas1($id_empresa, $id_curso, $llamada);
    $total_html = "";
    //echo "<br><pre>";    print_r($mallas);    echo "</pre>";
    $i = 1;
    foreach ($mallas as $unico) {
        if ($excel == 1) {
            $row = file_get_contents("views/capacitacion/curso/row_listado_excel.html");
        } else if ($llamada == "imparticion_solo_preenciales") {
            $row = file_get_contents("views/capacitacion/curso/row_listado_imparticion.html");
        } else {
            $row = file_get_contents("views/capacitacion/curso/row_listado1_mallas.html");
        }
        $row = str_replace("{NUMERO}", $i, $row);
        $i++;
        $row = str_replace("{CODIGO}", "<span class='label bg-blue'>" . $unico->numero_identificador . "</span>", $row);
        $row = str_replace("{CODIGO_CURSO}", "<span class='label bg-blue'>" . $unico->id . "</span>", $row);
        $row = str_replace("{ID_MALLA}", $unico->id_malla, $row);
        $row = str_replace("{NOMBRE_MALLA}",        ($unico->nombre_malla), $row);
        $row = str_replace("{ID_PROGRAMA_ENC}",     Encodear3($unico->id_programa), $row);
        $row = str_replace("{ID_MALLA_ENCODEADO}", Encodear3($unico->id_malla), $row);
        $row = str_replace("{NOMBRE}", $unico->nombre, $row);
        $row = str_replace("{NOMBRE_XLS}", ($unico->nombre), $row);
        $row = str_replace("{FOCO}", $unico->nombre_foco, $row);
        $row = str_replace("{FOCO_XLS}", ($unico->nombre_foco), $row);
        $busca_imparticiones_array = BuscaIdImparticiondadoMalla($unico->id_malla, $id_empresa);
        if (count($busca_imparticiones_array) > 0) {
            $row = str_replace("{EJECUTIVO}", $unico->ejecutivo, $row);
        } else {
            $row = str_replace("{EJECUTIVO}", "<small></small>", $row);
        }
        $row = str_replace("{EJECUTIVO_XLS}", ($unico->ejecutivo), $row);
        $row = str_replace("{PROGRAMA}",        ($unico->nombre_programa), $row);
        $row = str_replace("{PROGRAMA_XLS}",    ($unico->nombre_programa), $row);
        $row = str_replace("{DESCRIPCION}", $unico->descripcion, $row);
        $row = str_replace("{OBJETIVO_CURSO}", $unico->objetivo_curso, $row);
        $row = str_replace("{CLASIFICACION}", $unico->nombre_clasificacion, $row);
        //$row = str_replace("{NUM_IMPARTICIONES}",$unico->total_inscripciones_curso,$row);
        //print_r($busca_imparticion_array);
        $lista_imparticion = "";
        $lista_imparticion_xls = "";
        $suma_asistentes = 0;
        $cuentaimp = 0;
        foreach ($busca_imparticiones_array as $buscimpart) {
            $cuentaimp++;
            $lista_imparticion .= $buscimpart->codigo_inscripcion . "<br>";
            $lista_imparticion_xls .= $buscimpart->codigo_inscripcion . ",";
            $cuenta_Asistententes = BuscaAsistentesImparticion($buscimpart->codigo_inscripcion, $id_empresa);
            $suma_asistentes = $suma_asistentes + $cuenta_Asistententes[0]->asistentes;
            //print_r($cuenta_Asistententes);
        }
        if ($cuentaimp == 0) {
            $lista_imparticion = "<small>[SIN_IMPARTICIONES]</small><br>";
        }
        //cuentaparticipantes
        //$cuenta_Asistententes=BuscaAsistentesCursoImparticion($unico->id, $id_empresa);
        $Asistententes = $cuenta_Asistententes[0]->asistentes;
        $row = str_replace("{NUM_IMPARTICIONES}", $lista_imparticion, $row);
        $row = str_replace("{NUM_IMPARTICIONES_XLS}", $lista_imparticion_xls, $row);
        if ($cuentaimp > 0) {
            $boton_ver = "<a href='?sw=listaInscripcionesMallas1&i=" . Encodear3($unico->id_malla) . "'  data-placement='left' title='Ver_Imparticiones'>Ver_Imparticiones (".$cuentaimp.")</a><br>";
        } else {
            $boton_ver = "";
        }
        $row = str_replace("{VER_IMPARTICIONES}", $boton_ver, $row);
        $row = str_replace("{SENCE}", $unico->sence, $row);
        $row = str_replace("{PARTICIPANTES_MAX}", $unico->cantidad_maxima_participantes, $row);
        $row = str_replace("{OTEC}", $unico->nombre_otec, $row);
        $row = str_replace("{CBC}", $unico->cbc, $row);
        $row = str_replace("{MODALIDAD}", "<span class='label bg-blue'>" . $unico->nombre_modalidad . "</span>", $row);
        //$row = str_replace("{TIPO}",$unico->tipo,$row);
        $row = str_replace("{TIPO}", $unico->nombre_tipo_curso, $row);
        $row = str_replace("{PREREQUISITOS}", $unico->prerequisito, $row);

        $preinscritos = "";
        $postulantes = "";
        $asistentes = "";

        $inscritos=BuscaIdImparticiondadoMallaCuentaInscritos($unico->id_malla, $id_empresa);

        $row = str_replace("{INSCRITOS}", $inscritos, $row);
        $row = str_replace("{PREINSCRITOS}", $preinscritos, $row);
        $row = str_replace("{POSTULANTES}", $postulantes, $row);
        $row = str_replace("{ASISTENTES}", $asistentes, $row);
        $row = str_replace("{NUM_INSCRITOS}", $unico->inscritos, $row);
        $row = str_replace("{NUM_PREINSCRITOS}", $unico->preinscritos, $row);
        $row = str_replace("{NUM_POSTULANTES}", $unico->postulantes, $row);
        $row = str_replace("{NUM_ASISTENTES}", $Asistententes, $row);

        //Por cada curso, traigl el total de programas asociados desde rel lms malla curso
        $programas = TraeProgramasUnicosDadoCursoDeRelMallaCurso($id_empresa, $unico->id);
        $acumulador_programas = "";
        foreach ($programas as $prog) {
            $acumulador_programas .= $prog->nombre_programa . "<br>";
        }
        $row = str_replace("{PROGRAMAS_ASOCIADOS}", $acumulador_programas, $row);
        $row = str_replace("{PROGRAMA_BBDD_ENCODEADO}", Encodear3($programas[0]->id_programa), $row);
        $row = str_replace("{VALOR_HORA}", colocarPesos($unico->valor_hora), $row);
        if ($unico->imagen && file_exists("../front/img/logos_cursos/" . $unico->imagen)) {
            $row = str_replace("{IMAGEN}", "<img src='../front/img/logos_cursos/" . $unico->imagen . "' width='50' >", $row);
        } else {
            $row = str_replace("{IMAGEN}", "", $row);
        }
        $row = str_replace("{VALOR_HORA_SENCE}", colocarPesos($unico->valor_hora_sence), $row);
        $row = str_replace("{NUM_HORAS}", $unico->numero_horas, $row);
        $row = str_replace("{VALOR_CURSO}", colocarPesos($unico->numero_horas * $unico->valor_hora), $row);
        $row = str_replace("{EJECUTIVO}", ($unico->ejecutivocapacitacion), $row);
        $row = str_replace("{PARTICIPANTE}", ($unico->participantes), $row);
        $row = str_replace("{VALOR_CURSO}", colocarPesos($unico->numero_horas * $unico->valor_hora), $row);
        $row = str_replace("{FECHAI}", ($unico->fecha_inicio), $row);
        $row = str_replace("{FECHAT}", ($unico->fecha_finalizacion), $row);
        $total_html .= $row;
    }
    $PRINCIPAL = str_replace("{ROW_LISTADO}", ($total_html), $PRINCIPAL);
    $PRINCIPAL = str_replace("{ROW_LISTADO_xls}", ($total_html), $PRINCIPAL);
    return ($PRINCIPAL);
}

function FuncionesTransversalesAdmin($html){
	$id_empresa = $_SESSION["id_empresa"];
	//Dado el perfil, muestro el menu izquierdo

	$perfil = PerfilAdmin($_SESSION["perfil"]);
	$acceso_admin_perfil = VerificaTipoDePerfilAdminAcceso($_SESSION["user_"]);
	//echo "<h1>".$acceso_admin_perfil[0]->template."</h1>";
	//$html = str_replace("{MENU_IZQUIERDO}",file_get_contents("views/menu_izquierdo/".$perfil[0]->template.""),$html);
	$html = str_replace("{MENU_IZQUIERDO}", file_get_contents("views/menu_izquierdo/" . $acceso_admin_perfil[0]->template . ""), $html);
	$random = rand(5, 100005);

	//Veo si existen reportes de capacitacion en la tabla, para que se coloquen en el menu izquierdo
	//Reportes Capacitacion por empresa
	$reportes_capacitacion = TiposReportesPorEmpresa($id_empresa, 1);
	foreach ($reportes_capacitacion as $unico) {
		$ul .= file_get_contents("views/menu_izquierdo/li_menu.html");
		$ul = str_replace("{NOMBRE_REPORTE}", $unico->nombre, $ul);
		$ul = str_replace("{CASE_REPORTE}", $unico->case, $ul);
	}
	$html = str_replace("{REPORTES_CAPACITACION}", $ul, $html);
	//$html = str_replace("{MENU_INFERIOR}",file_get_contents("views/menu_inferior.html"),$html);
	//$html = str_replace("{FOOTER}",file_get_contents("views/footer.html"),$html);
	//$html = str_replace("{LOGO}",file_get_contents("views/logo.html"),$html);
	$html = str_replace("{HEAD}", file_get_contents("views/head.html"), $html);
	//$html = str_replace("{HEAD_FOOTER}",file_get_contents("views/head_footer.html"),$html);
	$html = str_replace("{HEADER}", file_get_contents("views/header.html"), $html);
	$html = str_replace("{NAVEGACION}", file_get_contents("views/navegacion.html"), $html);
	$html = str_replace("{USER_TEMPLATE}", file_get_contents("views/menu_izquierdo/user_menu.html"), $html);
	$html = str_replace("{IMAGEN_USUARIO}", VerificaFotoPersonalDesdeAdmin($_SESSION["admin_"]), $html);
	$html = str_replace("{FOOTER_ADMIN}", file_get_contents("views/footer.html"), $html);
	$html = str_replace("{HOME_INICIAL}", $perfil[0]->home, $html);

	$html = str_replace("{RANDOM}", $random, $html);


	//$html = str_replace("{STATUS_SUPERIOR_DERECHA}",file_get_contents("views/status_superior_derecha.html"),$html);
	$html = str_replace("{TITTLE}", "Administracion", $html);
	$html = str_replace("{NOMBRE_USUARIO}", ($_SESSION["nombre"]), $html);
	$html = str_replace("{PERFIL_ADMIN}", ($perfil[0]->nombre_perfil), $html);

	global $Texto_Pilar, $Texto_Programa;
	$html = str_replace("{Texto_Pilar}", ($Texto_Pilar), $html);
	$html = str_replace("{Texto_Programa}", ($Texto_Programa), $html);


	return ($html);
}
function VerificaFotoPersonalDesdeAdmin($rut)
{
	$datos_usuario = UsuarioEnBasePersonas($rut);
	$rut_completo = $datos_usuario[0]->rut_completo;
	$ruta_admin = "img/personas/" . $rut;
	$ruta = "../sgd/front/img/personas/" . $rut;
	$ruta_rut_completo = "../fsgd/ront/img/personas/" . $rut_completo;
	if (file_exists($ruta_admin . ".jpg")) {
		$imagen = $ruta_admin . ".jpg";
	} else if (file_exists($ruta_admin . ".JPG")) {
		$imagen = $ruta_admin . ".JPG";
	} else if (file_exists($ruta_admin . ".gif")) {
		$imagen = $ruta_admin . ".gif";
	} else if (file_exists($ruta . ".jpg")) {
		$imagen = $ruta . ".jpg";
	} else if (file_exists($ruta . ".JPG")) {
		$imagen = $ruta . ".JPG";
	} else if (file_exists($ruta . ".gif")) {
		$imagen = $ruta . ".gif";
	} else if (file_exists($ruta_rut_completo . ".jpg")) {
		$imagen = $ruta_rut_completo . ".jpg";
	} else if (file_exists($ruta_rut_completo . ".JPG")) {
		$imagen = $ruta_rut_completo . ".JPG";
	} else if (file_exists($ruta_rut_completo . ".gif")) {
		$imagen = $ruta_rut_completo . ".gif";
	} else {
		$imagen = "../front/img/foto_perfil.jpg";
	}
	return ($imagen);
}
function lista_proveedores_ejecutivos_fn($html, $id_empresa, $tipo)
{
    $Lista = lista_proveedores_ejecutivos_data($id_empresa, $tipo);
    //print_r($Lista);
    foreach ($Lista as $unico) {
        if ($tipo == "ejecutivos") {
            $row = file_get_contents("views/proveedores_otec/row_ejecutivos.html");
            $row = str_replace("{ID_ENC}", Encodear3($unico->id), $row);
            $row = str_replace("{ID}", ($unico->id), $row);
            $row = str_replace("{RUT}", ($unico->rut), $row);
            $row = str_replace("{NOMBRE}", ($unico->nombre), $row);
            $row = str_replace("{DESCRIPCION}", ($unico->descripcion), $row);
            $row = str_replace("{ID_TIPO}", ($tipo), $row);
            $row = str_replace("{TIPO_EJECUTIVO}", ($unico->tipo_ejecutivo), $row);
        } elseif ($tipo == "otec") {
            $row = file_get_contents("views/proveedores_otec/row_proveedores.html");
            $row = str_replace("{ID_ENC}", Encodear3($unico->id), $row);
            $row = str_replace("{ID}", ($unico->id), $row);
            $row = str_replace("{RUT}", ($unico->rut), $row);
            $row = str_replace("{NOMBRE}", ($unico->nombre), $row);
            $row = str_replace("{DESCRIPCION}", ($unico->descripcion), $row);
            $row = str_replace("{DIRECCION}", ($unico->direccion), $row);
            $row = str_replace("{TELEFONO}", ($unico->telefono), $row);
            $row = str_replace("{EMAIL}", ($unico->email), $row);
            $row = str_replace("{CONTACTO}", ($unico->contacto), $row);
            $row = str_replace("{ID_TIPO}", ($tipo), $row);
        } elseif ($tipo == "relatores") {
            $row = file_get_contents("views/proveedores_otec/row_relatores.html");
            $row = str_replace("{ID_ENC}", Encodear3($unico->id), $row);
            $row = str_replace("{ID}", ($unico->id), $row);
            $row = str_replace("{RUT}", ($unico->rut), $row);
            $row = str_replace("{NOMBRE}", ($unico->nombre), $row);
            $row = str_replace("{CARGO}", ($unico->cargo), $row);
            $row = str_replace("{EMAIL}", ($unico->email), $row);
            $row = str_replace("{TIPO_RELATOR}", ($unico->tipo), $row);


            if ($unico->tipo == "Interno") {
                $Nombre_empresaa = "Banco de Chile";
            } else {
                $Nombre_empresaa = NombreEmpresaOtec($unico->empresa);
            }
            $row = str_replace("{EMPRESA}", ($Nombre_empresaa), $row);
            $row = str_replace("{ID_TIPO}", ($tipo), $row);
        } elseif ($tipo == "administradores") {
            $row = file_get_contents("views/proveedores_otec/row_administradores.html");
            $row = str_replace("{ID_ENC}", Encodear3($unico->id), $row);
            $row = str_replace("{ID}", ($unico->id), $row);
            $row = str_replace("{RUT}", ($unico->user), $row);
            $row = str_replace("{NOMBRE}", ($unico->nombre_completo), $row);
            $row = str_replace("{CARGO}", ($unico->cargo), $row);
            $row = str_replace("{EMAIL}", ($unico->email), $row);
            $row = str_replace("{TIPO_RELATOR}", ($unico->tipo), $row);
            $row = str_replace("{ACCESO}", ($unico->acceso), $row);
            $row = str_replace("{PERFIL}", ($unico->perfil), $row);
            $row = str_replace("{EMPRESA}", ($unico->empresa), $row);
            $row = str_replace("{ID_TIPO}", ($tipo), $row);


            
            
        } elseif ($tipo == "usuarios_manuales") {
            $row = file_get_contents("views/proveedores_otec/row_usuarios_manuales.html");
            $row = str_replace("{ID_ENC}", Encodear3($unico->id), $row);
            $row = str_replace("{ID}", ($unico->id), $row);
            $row = str_replace("{RUT}", ($unico->rut), $row);
            $row = str_replace("{NOMBRE}", ($unico->nombre), $row);
            $row = str_replace("{APELLIDO}", ($unico->apaterno), $row);
            $row = str_replace("{EMAIL}", ($unico->email), $row);
            $row = str_replace("{TIPO_RELATOR}", ($unico->tipo), $row);
            $row = str_replace("{EMPRESA}",($unico->nombre_empresa_holding), $row);
            $row = str_replace("{ID_TIPO}", ($tipo), $row);
        } elseif ($tipo == "usuarios_externos") {
            $row = file_get_contents("views/proveedores_otec/row_usuarios_externos.html");
            $row = str_replace("{ID_ENC}", Encodear3($unico->id), $row);
            $row = str_replace("{ID}", ($unico->id), $row);
            $row = str_replace("{RUT}", ($unico->rut), $row);
            $row = str_replace("{NOMBRE}", ($unico->nombre), $row);
            $row = str_replace("{APELLIDO}", ($unico->apaterno), $row);
            $row = str_replace("{APELLIDO_MATERNO}", ($unico->amaterno), $row);
            $row = str_replace("{EMAIL}", ($unico->email), $row);
            $row = str_replace("{TIPO_RELATOR}", ($unico->tipo), $row);
            $row = str_replace("{EMPRESA}", ($unico->nombre_empresa_holding), $row);
            $row = str_replace("{ID_TIPO}", ($tipo), $row);
        } elseif ($tipo == "direcciones") {
            $row = file_get_contents("views/proveedores_otec/row_direcciones.html");
            $row = str_replace("{ID_ENC}", Encodear3($unico->id), $row);
            $row = str_replace("{ID}", ($unico->id), $row);

            if ($unico->tipo == "Fisica") {
                $unico->ciudad = Regiones_id_2022($unico->ciudad);
                $unico->comuna = Comunas_id_2022($unico->comuna);
            }

            $row = str_replace("{NOMBRE}", ($unico->nombre), $row);
            $row = str_replace("{DIRECCION}", ($unico->direccion), $row);
            $row = str_replace("{TELEFONO}", ($unico->ciudad), $row);
            $row = str_replace("{EMAIL}", ($unico->comuna), $row);
            $row = str_replace("{CONTACTO}", ($unico->tipo), $row);
            $row = str_replace("{ID_TIPO}", ($tipo), $row);
        } elseif ($tipo == "foco") {
            $row = file_get_contents("views/proveedores_otec/row_focos.html");
            $row = str_replace("{ID_ENC}", Encodear3($unico->id), $row);
            $row = str_replace("{ID}", ($unico->id), $row);
            $row = str_replace("{NOMBRE}", ($unico->descripcion), $row);
            $row = str_replace("{DIRECCION}",($unico->codigo_foco), $row);
            $row = str_replace("{TELEFONO}", ($unico->ciudad), $row);
            $row = str_replace("{EMAIL}", ($unico->comuna), $row);
            $row = str_replace("{CONTACTO}", ($unico->tipo), $row);
            $row = str_replace("{ID_TIPO}", ($tipo), $row);
        }
        elseif ($tipo == "mallas_bch") {
            $row = file_get_contents("views/proveedores_otec/row_malla_bch.html");
            $row = str_replace("{ID_ENC}", Encodear3($unico->id), $row);
            $row = str_replace("{ID}", ($unico->id), $row);
            $row = str_replace("{NOMBRE}", ($unico->nombre), $row);
            $row = str_replace("{DIRECCION}", ($unico->id), $row);
            $row = str_replace("{TELEFONO}", ($unico->ciudad), $row);
            $row = str_replace("{EMAIL}", ($unico->comuna), $row);
            $row = str_replace("{CONTACTO}", ($unico->tipo), $row);
            $row = str_replace("{ID_TIPO}", ($tipo), $row);

            if($unico->certificacion=="1"){
                $texto_inactivo="Inactivo";
                $texto_boton_inactivo="Activar";
                $var_activar="activar=1";
            } else {
                $texto_inactivo="Activo";
                $texto_boton_inactivo="Desactivar";
                $var_activar="activar=2";
            }

            $row = str_replace("{TEXTO_INACTIVO}", ($texto_inactivo), $row);
            $row = str_replace("{NOMBRE_ACTIVAR}", ($texto_boton_inactivo), $row);
            $row = str_replace("{VAR_ACTIVAR}", ($var_activar), $row);
        }
        elseif ($tipo == "categorias_bch") {
            $row = file_get_contents("views/proveedores_otec/row_categoria_bch.html");
            $row = str_replace("{ID_ENC}", Encodear3($unico->id), $row);
            $row = str_replace("{ID}", ($unico->id), $row);
            $row = str_replace("{NOMBRE}", ($unico->nombre), $row);
            $row = str_replace("{DIRECCION}", ($unico->id), $row);
            $row = str_replace("{TELEFONO}", ($unico->ciudad), $row);
            $row = str_replace("{EMAIL}", ($unico->comuna), $row);
            $row = str_replace("{CONTACTO}", ($unico->tipo), $row);
            $row = str_replace("{ID_TIPO}", ($tipo), $row);
            if($unico->certificacion=="1"){
                $texto_inactivo="Inactivo";
                $texto_boton_inactivo="Activar";
                $var_activar="activar=1";
            } else {
                $texto_inactivo="Activo";
                $texto_boton_inactivo="Desactivar";
                $var_activar="activar=2";
            }

            $row = str_replace("{TEXTO_INACTIVO}", ($texto_inactivo), $row);
            $row = str_replace("{NOMBRE_ACTIVAR}", ($texto_boton_inactivo), $row);
            $row = str_replace("{VAR_ACTIVAR}", ($var_activar), $row);
        }elseif ($tipo == "programas") {
            $row = file_get_contents("views/proveedores_otec/row_programas.html");
            $row = str_replace("{ID_ENC}", Encodear3($unico->id), $row);
            $row = str_replace("{ID}", ($unico->id), $row);
            $row = str_replace("{NOMBRE}", ($unico->nombre_programa), $row);
            $row = str_replace("{DIRECCION}", ($unico->id_programa), $row);
            $row = str_replace("{TELEFONO}", ($unico->ciudad), $row);
            $row = str_replace("{EMAIL}", ($unico->comuna), $row);
            $row = str_replace("{CONTACTO}", ($unico->tipo), $row);
            $row = str_replace("{ID_TIPO}", ($tipo), $row);
            $lista_cuentas = lista_cuentas_data($id_empresa);
            $options_cuenta="<option value=''></option>";
            foreach ($lista_cuentas as $unicoc) {
                $selected ="";
                if($unicoc->cuenta_id==$unico->cuenta_id){
                    $selected ="selected";
                }
                $options_cuenta .= "<option value='" . $unicoc->cuenta_id . "' $selected>" . $unicoc->cuenta_num." | ".($unicoc->cuenta_dsc) ."</option>";
            }
            $row = str_replace("{OPTIONS_CUENTAS}", $options_cuenta, $row);
            if($unico->inactivo=="1"){
                $texto_inactivo="Inactivo";
                $texto_boton_inactivo="Activar";
                $var_activar="activar=1";
            } else {
                $texto_inactivo="Activo";
                $texto_boton_inactivo="Desactivar";
                $var_activar="activar=2";
            }

            $row = str_replace("{TEXTO_INACTIVO}", ($texto_inactivo), $row);
            $row = str_replace("{NOMBRE_ACTIVAR}", ($texto_boton_inactivo), $row);
            $row = str_replace("{VAR_ACTIVAR}", ($var_activar), $row);
        }elseif ($tipo == "cuentas") {
            $row = file_get_contents("views/proveedores_otec/row_cuentas.html");
            $row = str_replace("{ID_ENC}", Encodear3($unico->cuenta_id), $row);
            $row = str_replace("{ID}", ($unico->cuenta_id), $row);
            $row = str_replace("{NOMBRE}", ($unico->cuenta_dsc), $row);
            $row = str_replace("{RUT}", ($unico->cuenta_num), $row);
            $row = str_replace("{ID_TIPO}", ($tipo), $row);
        }

        $total_html .= $row;
    }
    $total_html = str_replace("{NIVEL}", "2", $total_html);
    $total_html = str_replace("{SUB_CAT}", "", $total_html);
    $total_html = str_replace("{NSUB}", "", $total_html);
    $html = str_replace("{OPTIONS_CATEGORIAS_MEJORES_PRACTICAS_NUEVO}", ($categorias_options), $html);

    $lista_otec = lista_otec_vista_data($id_empresa);
    //print_r($lista_otec);
    foreach ($lista_otec as $unico) {
        $options_otec .= "<option value='" . $unico->rut . "'>" . $unico->nombre . " (" . $unico->rut . ")</option>";
    }
    $html = str_replace("{options_proveedores_nombre_rut}", $options_otec, $html);

    $Region = Regiones_2022();
    foreach ($Region as $unico) {
        $options_regiones .= "<option value='" . $unico->id_regiones . "'>" . ($unico->nombre) . "</option>";
    }
    $html = str_replace("{options_regiones}", $options_regiones, $html);
    if ($tipo == "programas") {
        $lista_cuentas = lista_cuentas_data($id_empresa);
        $options_cuenta="<option value=''></option>";
        foreach ($lista_cuentas as $unico) {
            $options_cuenta .= "<option value='" . $unico->cuenta_id . "'>" . $unico->cuenta_num." | ".($unico->cuenta_dsc) ."</option>";
        }
    }

    if($tipo=="programas"){
        $max_id_programa=MaxIdProgramas();
    }
    $html = str_replace("{ID_PROGRAMA_NEXT}", $max_id_programa, $html);
    $html = str_replace("{LISTA}", $total_html, $html);
    $html = str_replace("{TITULO}", "Preguntas y Respuestas", $html);
    $html = str_replace("{BTN}", "Nueva Pregunta", $html);
    $html = str_replace("{BTN_A}", "?sw=admin_biblio&n=fc", $html);
    $html = str_replace("{BTN_VOLVER}", '', $html);
    $html = str_replace("{COLUMNA}", 'Pregunta', $html);
    $html = str_replace("{COLUMNA}", 'Pregunta', $html);

    global $Texto_Pilar, $Texto_Programa;
    $html = str_replace("{Texto_Pilar}", $Texto_Pilar, $html);
    $html = str_replace("{OPTIONS_CUENTAS}", $options_cuenta, $html);

    return ($html);
}
function LimpiaRut($rut)
{

	$id_empresa = $_SESSION["id_empresa"];

	if ($id_empresa <> '75') {
		$rut = str_replace(".", "", $rut);
		$arreglo_rut = explode("-", $rut);
		return ($arreglo_rut[0]);
	} else {
		return $rut;
	}

}
function LimpiarCaracteres($string){
    $string = str_replace(";", ".", $string);
    $string = str_replace("'", "", $string);
    $string = str_replace('"', '', $string);
    $string = str_replace("<br>", "", $string);
    $string = str_replace("/\r|\n/", "", $string);
    $string = str_replace("/\r|\n/", "", $string);
    $string = str_replace("/\r|\n/", "", $string);
    $string = str_replace(array("\r", "\n"), '',  $string);
    $string  = str_replace(array("\r\n", "\r", "\n"), "",  $string);
    return $string;
}
function ListadoCursosAdmin2($PRINCIPAL, $id_empresa, $excel, $id_curso, $llamada)
{
    //echo "$id_empresa, excel $excel, $id_curso, $llamada";
    $cursos = Listado_TotalCursos2($id_empresa, $id_curso, $llamada);
    $total_html = "";
    $i = 1;
    foreach ($cursos as $unico) {

        if ($excel == 1) {
            $row = file_get_contents("views/capacitacion/curso/row_listado_excel.html");
        } else if ($llamada == "imparticion_solo_preenciales") {
            $row = file_get_contents("views/capacitacion/curso/row_listado_imparticion.html");
        } else {
            $row = file_get_contents("views/capacitacion/curso/row_listado2.html");
        }

        $row = str_replace("{NUMERO}", $i, $row);
        $i++;
        $row = str_replace("{CODIGO}", "<span class='label bg-blue'>" . $unico->numero_identificador . "</span>", $row);
        $row = str_replace("{CODIGO_CURSO}", "<span class='label bg-blue'>" . $unico->id . "</span>", $row);
        $row = str_replace("{CODIGO_ENCODEADO}", Encodear3($unico->id), $row);
        $row = str_replace("{NOMBRE}", ($unico->nombre), $row);
        if ($unico->clasificacion <> "") {
            $clasificacion = " (" . $unico->clasificacion . ") ";
        } else {
            $clasificacion = "";
        }
        $row = str_replace("{CLASIFICACION}", $clasificacion, $row);

        $row = str_replace("{NOMBRE_XLS}", ($unico->nombre), $row);
        $row = str_replace("{FOCO}", ($unico->nombre_foco), $row);
        $row = str_replace("{FOCO_XLS}", ($unico->nombre_foco), $row);
        $busca_imparticiones_array = BuscaIdImparticiondadoCurso($unico->id, $id_empresa);
        if (count($busca_imparticiones_array) > 0) {
            $row = str_replace("{EJECUTIVO}", $unico->ejecutivo, $row);
        } else {
            $row = str_replace("{EJECUTIVO}", "<small></small>", $row);
        }
        $row = str_replace("{EJECUTIVO_XLS}", ($unico->ejecutivo), $row);
        $row = str_replace("{PROGRAMA}", ($unico->nombre_programa), $row);
        $row = str_replace("{PROGRAMA_XLS}", ($unico->nombre_programa), $row);
        $row = str_replace("{DESCRIPCION}", $unico->descripcion, $row);
        $row = str_replace("{OBJETIVO_CURSO}", $unico->objetivo_curso, $row);
        $row = str_replace("{CLASIFICACION}", $unico->nombre_clasificacion, $row);
        //$row = str_replace("{NUM_IMPARTICIONES}",$unico->total_inscripciones_curso,$row);
        //print_r($busca_imparticion_array);
        $lista_imparticion = "";
        $lista_imparticion_xls = "";
        $suma_asistentes = 0;
        $cuentaimp = 0;
        foreach ($busca_imparticiones_array as $buscimpart) {
            $cuentaimp++;
            $lista_imparticion .= $buscimpart->codigo_inscripcion . "<br>";
            $lista_imparticion_xls .= $buscimpart->codigo_inscripcion . ",";
            $cuenta_Asistententes = BuscaAsistentesImparticion($buscimpart->codigo_inscripcion, $id_empresa);
            $suma_asistentes = $suma_asistentes + $cuenta_Asistententes[0]->asistentes;
            //print_r($cuenta_Asistententes);
        }
        if ($cuentaimp == 0) {
            $lista_imparticion = "<small>[SIN_IMPARTICIONES]</small><br>";
        }
        //cuentaparticipantes
        //$cuenta_Asistententes=BuscaAsistentesCursoImparticion($unico->id, $id_empresa);
        $Asistententes = $cuenta_Asistententes[0]->asistentes;
        $row = str_replace("{NUM_IMPARTICIONES}", $lista_imparticion, $row);
        $row = str_replace("{NUM_IMPARTICIONES_XLS}", $lista_imparticion_xls, $row);
        if ($cuentaimp > 0) {
            $boton_ver = "<a href='?sw=listaInscripciones2&i=" . Encodear3($unico->id) . "'  data-placement='left' title='Ver_Imparticiones'>Ver_Imparticiones</a><br>";
        } else {
            $boton_ver = "";
        }
        $row = str_replace("{VER_IMPARTICIONES}", $boton_ver, $row);
        $row = str_replace("{SENCE}", $unico->sence, $row);
        $row = str_replace("{PARTICIPANTES_MAX}", $unico->cantidad_maxima_participantes, $row);
        $row = str_replace("{OTEC}", $unico->nombre_otec, $row);
        $row = str_replace("{CBC}", $unico->cbc, $row);
        $row = str_replace("{MODALIDAD}", "<span class='label bg-blue'>" . $unico->nombre_modalidad . "</span>", $row);
        //$row = str_replace("{TIPO}",$unico->tipo,$row);
        $row = str_replace("{TIPO}", $unico->nombre_tipo_curso, $row);
        $row = str_replace("{PREREQUISITOS}", $unico->prerequisito, $row);

        $preinscritos = "";
        $postulantes = "";
        $asistentes = "";
        if ($unico->total_inscripciones_curso == 0) {
            $inscritos = "<strong>0</strong> Inscritos";
            if ($unico->nombre_modalidad == "Presencial") {
                $preinscritos = "<br><strong>0</strong> Preinscritos";
                $postulantes = "<br><strong>0</strong> Postulantes";
            }
        } else {
            $inscritos = "<strong>" . $unico->inscritos . "</strong> Inscritos";
            if ($unico->nombre_modalidad == "Presencial") {
                if ($unico->preinscritos > 0) {
                    $preinscritos = "<br><strong>" . $unico->preinscritos . "</strong> Preinscritos";
                } else {
                    $preinscritos = "<br><strong>0</strong> Preinscritos";
                }
                if ($unico->postulantes > 0) {
                    $postulantes = "<br><strong>" . $unico->postulantes . "</strong> Postulantes";
                } else {
                    $postulantes = "";
                }
                if ($Asistententes > 0) {
                    $asistentes = "<br><strong>" . $Asistententes . "</strong> Asistentes";
                } else {
                    $asistentes = "<br><strong>0</strong> Asistentes";
                }
            }
        }
        $row = str_replace("{INSCRITOS}", $inscritos, $row);
        $row = str_replace("{PREINSCRITOS}", $preinscritos, $row);
        $row = str_replace("{POSTULANTES}", $postulantes, $row);
        $row = str_replace("{ASISTENTES}", $asistentes, $row);
        $row = str_replace("{NUM_INSCRITOS}", $unico->inscritos, $row);
        $row = str_replace("{NUM_PREINSCRITOS}", $unico->preinscritos, $row);
        $row = str_replace("{NUM_POSTULANTES}", $unico->postulantes, $row);
        $row = str_replace("{NUM_ASISTENTES}", $Asistententes, $row);

        //Por cada curso, traigl el total de programas asociados desde rel lms malla curso
        $programas = TraeProgramasUnicosDadoCursoDeRelMallaCurso($id_empresa, $unico->id);
        $acumulador_programas = "";
        foreach ($programas as $prog) {
            $acumulador_programas .= $prog->nombre_programa . "<br>";
        }
        $row = str_replace("{PROGRAMAS_ASOCIADOS}", $acumulador_programas, $row);
        $row = str_replace("{PROGRAMA_BBDD_ENCODEADO}", Encodear3($programas[0]->id_programa), $row);
        $row = str_replace("{VALOR_HORA}", colocarPesos($unico->valor_hora), $row);
        if ($unico->imagen && file_exists("../front/img/logos_cursos/" . $unico->imagen)) {
            $row = str_replace("{IMAGEN}", "<img src='../front/img/logos_cursos/" . $unico->imagen . "' width='50' >", $row);
        } else {
            $row = str_replace("{IMAGEN}", "", $row);
        }
        $row = str_replace("{VALOR_HORA_SENCE}", colocarPesos($unico->valor_hora_sence), $row);
        $row = str_replace("{NUM_HORAS}", $unico->numero_horas, $row);
        //$row = str_replace("{VALOR_CURSO}", colocarPesos($unico->numero_horas * $unico->valor_hora), $row);
        $row = str_replace("{EJECUTIVO}", ($unico->ejecutivocapacitacion), $row);
        $row = str_replace("{PARTICIPANTE}", ($unico->participantes), $row);
        //$row = str_replace("{VALOR_CURSO}", colocarPesos($unico->numero_horas * $unico->valor_hora), $row);
        $row = str_replace("{FECHAI}", ($unico->fecha_inicio), $row);
        $row = str_replace("{FECHAT}", ($unico->fecha_finalizacion), $row);
        $total_html .= $row;
    }
    $PRINCIPAL = str_replace("{ROW_LISTADO}", ($total_html), $PRINCIPAL);
    $PRINCIPAL = str_replace("{ROW_LISTADO_xls}", utf8_decode($total_html), $PRINCIPAL);
    return ($PRINCIPAL);
}

function colocarPesos()
{

}
function ListaColaboradoresPorImparticiones_LibroClases_2024($PRINCIPAL, $id_imparticion, $id_empresa, $excel)
{


    $datos_imparticion = DatosInscripcionImparticionesV2($id_imparticion, $id_empresa);
    //print_r($datos_imparticion); exit();


    $Num_sesiones = ImparticionesNumSesiones_2021($id_imparticion);
    if ($datos_imparticion[0]->id_modalidad == "1") {
        $display_asistencia_none = "display:none!important";
        $total_usuarios_por_inscripcion = IMPARTICION_UsuariosPorInscripcionConDatos($id_imparticion, $id_empresa);
    } else {
        $total_usuarios_por_inscripcion = IMPARTICION_UsuariosPorInscripcionConDatos($id_imparticion, $id_empresa);
    }
    $estado_ejecucion = EstadoImparticion2022($datos_imparticion[0]->fecha_inicio, $datos_imparticion[0]->fecha_termino);
    //print_r($estado);
    $PRINCIPAL = str_replace("{ESTADO_EJECUCION}", $estado_ejecucion[0], $PRINCIPAL);
    $cuenta_inscritos = count($total_usuarios_por_inscripcion);
    //if($cuenta_inscritos>0 and ($estado_ejecucion[1]=="Ejecutando" or $estado_ejecucion[1]=="Finalizada")){

    if ($cuenta_inscritos > 0) {

    } else {

        $display_asistencia_none = "display:none!important";
    }

    $PRINCIPAL = str_replace("{display_asistencia_none}", ($display_asistencia_none), $PRINCIPAL);

    if ($Num_sesiones > 0) {
        $num_sesiones = $Num_sesiones;
        //echo "sesiones $num_sesiones";
    } else {
        $num_sesiones = 0;
        //echo "sesiones $num_sesiones";
    }

    if ($datos_imparticion[0]->id_modalidad == "1") {
        $total_usuarios_por_inscripcion = "";
    }

    $inscritos_numero = "";
    // preinscritos modalidad 1 es rel_lms_rut_id_curso_id_inscripcion
    if ($datos_imparticion[0]->id_modalidad == "1") {
        //$cuenta_preinscritos_2021=Cuenta_PreInscritos_2021($id_imparticion, $datos_imparticion[0]->id_modalidad, $id_empresa);
        //$inscritos_numero.="<span class='label label-sm label-info' style='margin-bottom: 1px;'> PreInscritos </span> ".$cuenta_preinscritos_2021." Preinscritos<BR>";
    }
    // preinscritos autoinscripcion 1 es tbl_inscripcion_postulantes
    if ($datos_imparticion[0]->id_modalidad == "3") {
        $cuenta_preinscritos_2021 = Cuenta_PreInscritos_2021($id_imparticion, $datos_imparticion[0]->id_modalidad, $id_empresa);
        $inscritos_numero .= "<span class='label label-sm label-info' style='margin-bottom: 1px;'> PreInscritos </span> " . $cuenta_preinscritos_2021 . " Preinscritos<BR>";
    }
    if ($datos_imparticion[0]->cupos > 0) {
        $inscritos_numero .= "<span class='label label-sm label-info' style='margin-bottom: 1px;'> Inscritos </span> " . $cuenta_inscritos . "  inscritos de " . $datos_imparticion[0]->cupos . " cupos disponibles";
    } else {
        $inscritos_numero .= "<span class='label label-sm label-info' style='margin-bottom: 1px;'> Inscritos </span> " . $cuenta_inscritos . " inscritos.";
    }


    $PRINCIPAL = str_replace("{CUPOS_INSCRITOS}", ($inscritos_numero), $PRINCIPAL);
    //print_r($total_usuarios_por_inscripcion);

    foreach ($total_usuarios_por_inscripcion as $usu) {
        $cuentaA++;
        //echo "<br>$cuentaA $cuentaA";


        if ($excel == 1) {
            $row_listado_colaboradores .= file_get_contents("views/capacitacion/imparticion/row_colaboradores_sesiones_excel.html");
        } else {
            $row_listado_colaboradores .= file_get_contents("views/capacitacion/imparticion/row_colaboradores_libro_clases.html");
        }
        $existe_en_cierre = VerificaEnTablaCierreCursoEmpresRutInscripcion2021($usu->rut, $id_empresa, $id_imparticion);
        $row_listado_colaboradores = str_replace("{CORRELATIVO}", ($cuentaA), $row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{RUT}", ($usu->rut), $row_listado_colaboradores);
        $UsuC = TraeUsuarioRut($usu->rut);
        $row_listado_colaboradores = str_replace("{DV}", ($UsuC[0]->dv), $row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{NOMBRE}", ($UsuC[0]->nombre_completo), $row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{NOMBRE}", ($usu->rut), $row_listado_colaboradores);

    }

    $PRINCIPAL = str_replace("{ID_CURSO}", ($datos_imparticion[0]->id_curso), $PRINCIPAL);
    $PRINCIPAL = str_replace("{NOMBRE_CURSO}", ($datos_imparticion[0]->nombre_curso), $PRINCIPAL);
    $PRINCIPAL = str_replace("{ID_OTA}", ($datos_imparticion[0]->codigo_inscripcion), $PRINCIPAL);
    $PRINCIPAL = str_replace("{NOMBRE_IMPARTICION}", ($datos_imparticion[0]->nombre), $PRINCIPAL);
    $PRINCIPAL = str_replace("{FECHA_INICIO}", ($datos_imparticion[0]->fecha_inicio), $PRINCIPAL);
    $PRINCIPAL = str_replace("{FECHA_TERMINO}", ($datos_imparticion[0]->fecha_termino), $PRINCIPAL);
    $PRINCIPAL = str_replace("{DIRECCION}", ($datos_imparticion[0]->direccion), $PRINCIPAL);



    $PRINCIPAL = str_replace("{ID_MODALIDAD_BACK}", ($datos_imparticion[0]->id_modalidad), $PRINCIPAL);
    $PRINCIPAL = str_replace("{ROW_SESIONES}", ($row), $PRINCIPAL);
    $PRINCIPAL = str_replace("{ROW_LISTADO_COLABORADORES_LIBROCLASES}", ($row_listado_colaboradores), $PRINCIPAL);
    return ($PRINCIPAL);
}
function ListaColaboradoresPorImparticiones_2021($PRINCIPAL, $id_imparticion, $id_empresa, $excel)
{


    $datos_imparticion = DatosInscripcionImparticionesV2($id_imparticion, $id_empresa);
    //print_r($datos_imparticion); exit();


    $Num_sesiones = ImparticionesNumSesiones_2021($id_imparticion);
    if ($datos_imparticion[0]->id_modalidad == "1") {
        $display_asistencia_none = "display:none!important";
        $total_usuarios_por_inscripcion = IMPARTICION_UsuariosPorInscripcionConDatos($id_imparticion, $id_empresa);
    } else {
        $total_usuarios_por_inscripcion = IMPARTICION_UsuariosPorInscripcionConDatos($id_imparticion, $id_empresa);
    }
    $estado_ejecucion = EstadoImparticion2022($datos_imparticion[0]->fecha_inicio, $datos_imparticion[0]->fecha_termino);
    //print_r($estado);
    $PRINCIPAL = str_replace("{ESTADO_EJECUCION}", $estado_ejecucion[0], $PRINCIPAL);
    $cuenta_inscritos = count($total_usuarios_por_inscripcion);
    //if($cuenta_inscritos>0 and ($estado_ejecucion[1]=="Ejecutando" or $estado_ejecucion[1]=="Finalizada")){

    if ($cuenta_inscritos > 0) {

    } else {

        $display_asistencia_none = "display:none!important";
    }

    $PRINCIPAL = str_replace("{display_asistencia_none}", ($display_asistencia_none), $PRINCIPAL);

    if ($Num_sesiones > 0) {
        $num_sesiones = $Num_sesiones;
        //echo "sesiones $num_sesiones";
    } else {
        $num_sesiones = 0;
        //echo "sesiones $num_sesiones";
    }

    if ($datos_imparticion[0]->id_modalidad == "1") {
        $total_usuarios_por_inscripcion = "";
    }

    $inscritos_numero = "";
    // preinscritos modalidad 1 es rel_lms_rut_id_curso_id_inscripcion
    if ($datos_imparticion[0]->id_modalidad == "1") {
        //$cuenta_preinscritos_2021=Cuenta_PreInscritos_2021($id_imparticion, $datos_imparticion[0]->id_modalidad, $id_empresa);
        //$inscritos_numero.="<span class='label label-sm label-info' style='margin-bottom: 1px;'> PreInscritos </span> ".$cuenta_preinscritos_2021." Preinscritos<BR>";
    }
    // preinscritos autoinscripcion 1 es tbl_inscripcion_postulantes
    if ($datos_imparticion[0]->id_modalidad == "3") {
        $cuenta_preinscritos_2021 = Cuenta_PreInscritos_2021($id_imparticion, $datos_imparticion[0]->id_modalidad, $id_empresa);
        $inscritos_numero .= "<span class='label label-sm label-info' style='margin-bottom: 1px;'> PreInscritos </span> " . $cuenta_preinscritos_2021 . " Preinscritos<BR>";
    }
    if ($datos_imparticion[0]->cupos > 0) {
        $inscritos_numero .= "<span class='label label-sm label-info' style='margin-bottom: 1px;'> Inscritos </span> " . $cuenta_inscritos . "  inscritos de " . $datos_imparticion[0]->cupos . " cupos disponibles";
    } else {
        $inscritos_numero .= "<span class='label label-sm label-info' style='margin-bottom: 1px;'> Inscritos </span> " . $cuenta_inscritos . " inscritos.";
    }


    $PRINCIPAL = str_replace("{CUPOS_INSCRITOS}", ($inscritos_numero), $PRINCIPAL);
    //print_r($total_usuarios_por_inscripcion);

    foreach ($total_usuarios_por_inscripcion as $usu) {
        $cuentaA++;
        //echo "<br>$cuentaA $cuentaA";

        if($cuentaA>1000){
           // exit("3006");
        }
        if ($excel == 1) {
            $row_listado_colaboradores .= file_get_contents("views/capacitacion/imparticion/row_colaboradores_sesiones_excel.html");
        } else {
            $row_listado_colaboradores .= file_get_contents("views/capacitacion/imparticion/row_colaboradores_sesiones.html");
        }
        $existe_en_cierre = VerificaEnTablaCierreCursoEmpresRutInscripcion2021($usu->rut, $id_empresa, $id_imparticion);
        //ECHO "<BR>";PRINT_R($existe_en_cierre);
        //echo "<br><h4>1 existe_en_cierre[0]->estado_descripcion ".$existe_en_cierre[0]->estado_descripcion." select_aprobado $select_aprobado</h4>";

        $row_listado_colaboradores = str_replace("{RUT_COL_ENC}", Encodear3($usu->rut), $row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{TEXT_AREA_OBS_2023}", ($existe_en_cierre[0]->observaciones), $row_listado_colaboradores);
        if($existe_en_cierre[0]->observaciones<>""){
            //echo "<br>A";
            $icon_obs_svg="<i class='bi bi-info-circle-fill'></i>";
        } else {
            //echo "<br>B";

                    $icon_obs_svg="<i class='bi bi-info-circle'></i>";

        }
                $row_listado_colaboradores = str_replace("{ICON_OBS_SVG_2023}", ($icon_obs_svg), $row_listado_colaboradores);

        if ($num_sesiones > 0) {
            $sesion_1 = "";
            $sesion_2 = "";
            $sesion_3 = "";
            $sesion_4 = "";
            $sesion_5 = "";
            $sesion_6 = "";
            $sesion_7 = "";
            $sesion_8 = "";
            $sesion_9 = "";
            $sesion_10 = "";
            $sesion_11 = "";
            $sesion_12 = "";
            if ($num_sesiones > 0) {
                $checked_ses1 = Asistencia2021_Avance_Sesiones($usu->rut, $id_imparticion, "1");
                $fecha_sesion1 = Fecha2021_fecha_sesion($id_imparticion, "1");
                $sesion_1 = "<th class='bg_gray' width='100px' style='font-size: 11px;'>S1<br>" . $fecha_sesion1[0]->fecha . "<br>" . $fecha_sesion1[0]->hora_desde . " / " . $fecha_sesion1[0]->hora_hasta . "</th>";
                $sesion_1_td = "<td><input type='checkbox' id='ses_1' name='ses_1_" . $usu->rut . "' " . $checked_ses1 . " disabled></td>";
            }
            if ($num_sesiones > 1) {
                $checked_ses2 = Asistencia2021_Avance_Sesiones($usu->rut, $id_imparticion, "2");
                $fecha_sesion2 = Fecha2021_fecha_sesion($id_imparticion, "2");
                $sesion_2 = "<th class='bg_gray' width='100px' style='font-size: 11px;'>S2<br>" . $fecha_sesion2[0]->fecha . "<br>" . $fecha_sesion2[0]->hora_desde . " / " . $fecha_sesion2[0]->hora_hasta . "</th>";
                $sesion_2_td = "<td><input type='checkbox' id='ses_2' name='ses_2_" . $usu->rut . "' " . $checked_ses2 . " disabled></td>";
            }
            if ($num_sesiones > 2) {
                $checked_ses3 = Asistencia2021_Avance_Sesiones($usu->rut, $id_imparticion, "3");
                $fecha_sesion3 = Fecha2021_fecha_sesion($id_imparticion, "3");
                $sesion_3 = "<th class='bg_gray' width='100px' style='font-size: 11px;'>S3<br>" . $fecha_sesion3[0]->fecha . "<br>" . $fecha_sesion3[0]->hora_desde . " / " . $fecha_sesion3[0]->hora_hasta . "</th>";
                $sesion_3_td = "<td><input type='checkbox' id='ses_3' name='ses_3_" . $usu->rut . "' " . $checked_ses3 . " disabled></td>";
            }
            if ($num_sesiones > 3) {
                $checked_ses4 = Asistencia2021_Avance_Sesiones($usu->rut, $id_imparticion, "4");
                $fecha_sesion4 = Fecha2021_fecha_sesion($id_imparticion, "4");
                $sesion_4 = "<th class='bg_gray' width='100px' style='font-size: 11px;'>S4<br>" . $fecha_sesion4[0]->fecha . "<br>" . $fecha_sesion4[0]->hora_desde . " / " . $fecha_sesion4[0]->hora_hasta . "</th>";
                $sesion_4_td = "<td><input type='checkbox' id='ses_4' name='ses_4_" . $usu->rut . "' " . $checked_ses4 . " disabled></td>";
            }
            if ($num_sesiones > 4) {
                $checked_ses5 = Asistencia2021_Avance_Sesiones($usu->rut, $id_imparticion, "5");
                $fecha_sesion5 = Fecha2021_fecha_sesion($id_imparticion, "5");
                $sesion_5 = "<th class='bg_gray' width='25%' style='font-size: 11px;'>S5<br>" . $fecha_sesion5[0]->fecha . "<br>" . $fecha_sesion5[0]->hora_desde . " / " . $fecha_sesion5[0]->hora_hasta . "</th>";
                $sesion_5_td = "<td><input type='checkbox' id='ses_5' name='ses_5_" . $usu->rut . "' " . $checked_ses5 . " disabled></td>";
            }
            if ($num_sesiones > 5) {
                $checked_ses6 = Asistencia2021_Avance_Sesiones($usu->rut, $id_imparticion, "6");
                $fecha_sesion6 = Fecha2021_fecha_sesion($id_imparticion, "6");
                $sesion_6 = "<th class='bg_gray' width='25%' style='font-size: 11px;'>S6<br>" . $fecha_sesion6[0]->fecha . "<br>" . $fecha_sesion6[0]->hora_desde . " / " . $fecha_sesion6[0]->hora_hasta . "</th>";
                $sesion_6_td = "<td><input type='checkbox' id='ses_6' name='ses_6_" . $usu->rut . "' " . $checked_ses6 . " disabled></td>";
            }
            if ($num_sesiones > 6) {
                $checked_ses7 = Asistencia2021_Avance_Sesiones($usu->rut, $id_imparticion, "7");
                $fecha_sesion7 = Fecha2021_fecha_sesion($id_imparticion, "7");
                $sesion_7 = "<th class='bg_gray' width='25%' style='font-size: 11px;'>S7<br>" . $fecha_sesion7[0]->fecha . "<br>" . $fecha_sesion7[0]->hora_desde . " / " . $fecha_sesion7[0]->hora_hasta . "</th>";
                $sesion_7_td = "<td><input type='checkbox' id='ses_7' name='ses_7_" . $usu->rut . "' " . $checked_ses7 . " disabled></td>";
            }
            if ($num_sesiones > 7) {
                $checked_ses8 = Asistencia2021_Avance_Sesiones($usu->rut, $id_imparticion, "8");
                $fecha_sesion8 = Fecha2021_fecha_sesion($id_imparticion, "8");
                $sesion_8 = "<th class='bg_gray' width='25%' style='font-size: 11px;'>S8<br>" . $fecha_sesion8[0]->fecha . "<br>" . $fecha_sesion8[0]->hora_desde . " / " . $fecha_sesion8[0]->hora_hasta . "</th>";
                $sesion_8_td = "<td><input type='checkbox' id='ses_8' name='ses_8_" . $usu->rut . "' " . $checked_ses8 . " disabled></td>";
            }
            if ($num_sesiones > 8) {
                $checked_ses9 = Asistencia2021_Avance_Sesiones($usu->rut, $id_imparticion, "9");
                $fecha_sesion9 = Fecha2021_fecha_sesion($id_imparticion, "9");
                $sesion_9 = "<th class='bg_gray' width='25%' style='font-size: 11px;'>S9<br>" . $fecha_sesion9[0]->fecha . "<br>" . $fecha_sesion9[0]->hora_desde . " / " . $fecha_sesion9[0]->hora_hasta . "</th>";
                $sesion_9_td = "<td><input type='checkbox' id='ses_9' name='ses_9_" . $usu->rut . "' " . $checked_ses9 . " disabled></td>";
            }
            if ($num_sesiones > 9) {
                $checked_ses10 = Asistencia2021_Avance_Sesiones($usu->rut, $id_imparticion, "10");
                $fecha_sesion10 = Fecha2021_fecha_sesion($id_imparticion, "10");
                $sesion_10 = "<th class='bg_gray' width='25%' style='font-size: 11px;'>S10<br>" . $fecha_sesion10[0]->fecha . "<br>" . $fecha_sesion10[0]->hora_desde . " / " . $fecha_sesion10[0]->hora_hasta . "</th>";
                $sesion_10_td = "<td><input type='checkbox' id='ses_10' name='ses_10_" . $usu->rut . "' " . $checked_ses10 . " disabled></td>";
            }
            if ($num_sesiones > 10) {
                $checked_ses11 = Asistencia2021_Avance_Sesiones($usu->rut, $id_imparticion, "11");
                $fecha_sesion11 = Fecha2021_fecha_sesion($id_imparticion, "11");
                $sesion_11 = "<th class='bg_gray' width='25%' style='font-size: 11px;'>S11<br>" . $fecha_sesion11[0]->fecha . "<br>" . $fecha_sesion11[0]->hora_desde . " / " . $fecha_sesion11[0]->hora_hasta . "</th>";
                $sesion_11_td = "<td><input type='checkbox' id='ses_11' name='ses_11_" . $usu->rut . "' " . $checked_ses11 . " disabled></td>";
            }
            if ($num_sesiones > 11) {
                $checked_ses12 = Asistencia2021_Avance_Sesiones($usu->rut, $id_imparticion, "12");
                $fecha_sesion12 = Fecha2021_fecha_sesion($id_imparticion, "12");
                $sesion_12 = "<th class='bg_gray' width='25%' style='font-size: 11px;'>S12<br>" . $fecha_sesion12[0]->fecha . "<br>" . $fecha_sesion12[0]->hora_desde . " / " . $fecha_sesion12[0]->hora_hasta . "</th>";
                $sesion_12_td = "<td><input type='checkbox' id='ses_12' name='ses_12_" . $usu->rut . "' " . $checked_ses12 . " disabled></td>";
            }
            $readonly_asistencia = " readonly ";
            $suma_sesiones = "";
            //calcula asistencia
            if ($checked_ses1 == "checked") {
                $suma_sesiones++;
            }
            if ($checked_ses2 == "checked") {
                $suma_sesiones++;
            }
            if ($checked_ses3 == "checked") {
                $suma_sesiones++;
            }
            if ($checked_ses4 == "checked") {
                $suma_sesiones++;
            }
            if ($checked_ses5 == "checked") {
                $suma_sesiones++;
            }
            if ($checked_ses6 == "checked") {
                $suma_sesiones++;
            }
            if ($checked_ses7 == "checked") {
                $suma_sesiones++;
            }
            if ($checked_ses8 == "checked") {
                $suma_sesiones++;
            }
            if ($checked_ses9 == "checked") {
                $suma_sesiones++;
            }
            if ($checked_ses10 == "checked") {
                $suma_sesiones++;
            }
            if ($checked_ses11 == "checked") {
                $suma_sesiones++;
            }
            if ($checked_ses12 == "checked") {
                $suma_sesiones++;
            }
            //$porcentaje_asistencia_sesiones	= round(100*$suma_sesiones/$num_sesiones);
            //echo "<br>porcentaje_asistencia_sesiones $porcentaje_asistencia_sesiones, "


        } else {
            $readonly_asistencia = "";
        }
        //echo "<br><h4>2 existe_en_cierre[0]->estado_descripcion ".$existe_en_cierre[0]->estado_descripcion." select_aprobado $select_aprobado</h4>";


        $row_listado_colaboradores = str_replace("{SESIONES_1_td}", $sesion_1_td, $row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{SESIONES_2_td}", $sesion_2_td, $row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{SESIONES_3_td}", $sesion_3_td, $row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{SESIONES_4_td}", $sesion_4_td, $row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{SESIONES_5_td}", $sesion_5_td, $row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{SESIONES_6_td}", $sesion_6_td, $row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{SESIONES_7_td}", $sesion_7_td, $row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{SESIONES_8_td}", $sesion_8_td, $row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{SESIONES_9_td}", $sesion_9_td, $row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{SESIONES_10_td}", $sesion_10_td, $row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{SESIONES_11_td}", $sesion_11_td, $row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{SESIONES_12_td}", $sesion_12_td, $row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{readonly_asistencia}", $readonly_asistencia, $row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{NOMBRE}", ($usu->rut), $row_listado_colaboradores);
        $UsuC = TraeUsuarioRut($usu->rut);
        $row_listado_colaboradores = str_replace("{NOMBRE_COMPLETO}", $UsuC[0]->nombre_completo, $row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{CAMPO1}", $usu->campo1, $row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{CAMPO2}", $usu->campo2, $row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{CAMPO3}", $usu->campo3, $row_listado_colaboradores);
        $TipoVigencia=TipoRut_2022($usu->rut, $UsuC[0]->id, $UsuC[0]->vigencia_descripcion);
             $row_listado_colaboradores = str_replace("{TIPO_VIGENCIA}", "<span class='badge badge-info'>$TipoVigencia</span>", $row_listado_colaboradores);

        $row_listado_colaboradores = str_replace("{RUT_COMPLETO}", $usu->rut, $row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{RUT_COLABORADOR}", $usu->rut, $row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{CARGO_COLABORADOR}", $usu->cargo_colaborador, $row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{ID_IMPARTICION}", $id_imparticion, $row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{ID_IMPARTICION_ENC}", Encodear3($id_imparticion), $row_listado_colaboradores);
        //$avance_actual=CalculaAsistenciaEnBaseASesiones($usu->rut, $id_imparticion, $id_empresa);
        //echo "<br>holla ".$usu->rut." <br> "; print_r($existe_en_cierre);
        //if($existe_en_cierre[0]->porcentaje_asistencia<>''){$icono_asistencia="<i class='fas fa-check-square fa-2x' style='color: #3eae29;'></i>";} else {$icono_asistencia="";}
        //if($existe_en_cierre[0]->nota<>''){$icono_nota="<i class='fas fa-check-square fa-2x'  style='color: #3eae29;'></i>";} else {$icono_nota="";}
        //$row_listado_colaboradores = str_replace("{AVANCE_ACTUAL}",$avance_actual,$row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{AVANCE_ACTUAL}", $existe_en_cierre[0]->porcentaje_asistencia, $row_listado_colaboradores);

        /*if($num_sesiones>0){
					if($existe_en_cierre[0]->estado_descripcion=="DESISTE"	or $existe_en_cierre[0]->estado_descripcion=="JUSTIFICADO"){

					} else {
					$existe_en_cierre[0]->estado_descripcion="";
					}


				}*/
        //echo "<br><h4>3 existe_en_cierre[0]->estado_descripcion ".$existe_en_cierre[0]->estado_descripcion." select_aprobado $select_aprobado</h4>";

        //print_r($existe_en_cierre);

        $display_notas = "";
        // ESTADO AUTOMATICO
        if ($datos_imparticion[0]->minimo_asistencia > 0) {
            //echo "<br>-> aaaa";
            if ($datos_imparticion[0]->minimo_nota_aprobacion > 0) {
            } else {
                $display_notas = " display:none !important";
            }
        } else {
            if ($datos_imparticion[0]->minimo_nota_aprobacion > 0) {
            } else {
                $display_notas = " display:none !important";
            }
        }

        //print_r($existe_en_cierre);
        if ($existe_en_cierre[0]->estado_descripcion == "") {
            $select_aprobado = "";
            $select_reprobado = "";
            $select_desiste = "";
            $select_justificado = "";
        } elseif ($existe_en_cierre[0]->estado_descripcion == "APROBADO") {
            //echo "<br>a</br>";
            $select_aprobado = " selected ";
            $select_reprobado = "";
            $select_desiste = "";
            $select_justificado = "";
        } elseif ($existe_en_cierre[0]->estado_descripcion == "REPROBADO") {
            $select_aprobado = "";
            $select_reprobado = " selected ";
            $select_desiste = "";
            $select_justificado = "";
        } elseif ($existe_en_cierre[0]->estado_descripcion == "DESISTE") {
            $select_aprobado = "";
            $select_reprobado = "";
            $select_desiste = " selected ";
            $select_justificado = "";
        } elseif ($existe_en_cierre[0]->estado_descripcion == "JUSTIFICADO") {
            $select_aprobado = "";
            $select_reprobado = "";
            $select_desiste = "";
            $select_justificado = " selected ";
        }
        //echo "<br><h4>existe_en_cierre[0]->estado_descripcion ".$existe_en_cierre[0]->estado_descripcion." select_aprobado $select_aprobado</h4>";

        $row_listado_colaboradores = str_replace("{display_notas}", $display_notas, $row_listado_colaboradores);
        $PRINCIPAL = str_replace("{display_notas}", $display_notas, $PRINCIPAL);

        //print_r($existe_en_cierre);

        $row_listado_colaboradores = str_replace("{NOTA_ACTUAL}", $existe_en_cierre[0]->nota, $row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{NOTA_DIAGNOSTICO_ACTUAL}", $existe_en_cierre[0]->caso_especial, $row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{ESTADO_ACTUAL}", $existe_en_cierre[0]->estado_descripcion, $row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{ICONO_ASISTENCIA}", $icono_asistencia, $row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{ICONO_NOTA}", $icono_nota, $row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{select_aprobado}", $select_aprobado, $row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{select_aprobado}", $select_aprobado, $row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{select_reprobado}", $select_reprobado, $row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{select_desiste}", $select_desiste, $row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{select_justificado}", $select_justificado, $row_listado_colaboradores);

        actualizaTablaCierreRut_2021($usu->rut, $datos_imparticion[0]->id_curso, $id_imparticion, $existe_en_cierre[0]->porcentaje_asistencia, $id_empresa, $existe_en_cierre[0]->nota, $existe_en_cierre[0]->caso_especial, $estado, $existe_en_cierre[0]->estado_descripcion);

        $row_listado_colaboradores = str_replace("{ROW_SESIONES_POR_COLABORADOR}", ($listado_sesiones_por_colaborador), $row_listado_colaboradores);

        global $permite_subir_certificados;

        if ($existe_en_cierre[0]->observaciones <> "") {
            $archivo_certificado = "../front/docs/" . $existe_en_cierre[0]->observaciones;
            $ver_certificado = "<a href='" . $archivo_certificado . "' target='_blank' class='btn btn-link'>Ver Diploma</a> 
					<a href='?sw=VeColaboradoresXImp2021&i=" . Encodear3($id_imparticion) . "&dl=" . $existe_en_cierre[0]->id . "'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash' viewBox='0 0 16 16'><path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z'/><path fill-rule='evenodd' d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z'/></svg></a>
					<br>";
        } else {
            $archivo_certificado = "";
            $ver_certificado = "";
        }

        if ($permite_subir_certificados == "1" and $existe_en_cierre[0]->estado_descripcion == "APROBADO") {
            $upload_certificado_2021 = "
					$ver_certificado
					<div style='text-align:right;font-size:10px'>
									  <input type='file' value='Subir Excel' name='file_" . Encodear3($usu->rut) . "' style='display: inline;width: 98px;font-size: 9px;'>
        	</div>
					";
        } else {
            $upload_certificado_2021 = "";
        }
        $row_listado_colaboradores = str_replace("{ROW_CERTIFICADO_2021_INDIVIDUAL}", ($upload_certificado_2021), $row_listado_colaboradores);
        $row_listado_colaboradores = str_replace("{ROW_SESIONES_POR_COLABORADOR}", ($listado_sesiones_por_colaborador), $row_listado_colaboradores);

    }


    //echo "<br>num_sesiones $num_sesiones";
    if ($num_sesiones > 0) {
        $sesion_1 = "";
        $sesion_2 = "";
        $sesion_3 = "";
        $sesion_4 = "";
        $sesion_5 = "";
        $sesion_6 = "";
        $sesion_7 = "";
        $sesion_8 = "";
        $sesion_9 = "";
        $sesion_10 = "";
        $sesion_11 = "";
        $sesion_12 = "";
        if ($num_sesiones > 0) {
            $fecha_sesion1 = Fecha2021_fecha_sesion($id_imparticion, "1");
            $sesion_1 = "<th class='bg_gray' width='100px' style='font-size: 11px;'>S1<br>" . $fecha_sesion1[0]->fecha . "<br>" . $fecha_sesion1[0]->hora_desde . " / " . $fecha_sesion1[0]->hora_hasta . "</th>";
        }
        if ($num_sesiones > 1) {
            $fecha_sesion2 = Fecha2021_fecha_sesion($id_imparticion, "2");
            $sesion_2 = "<th class='bg_gray' width='100px' style='font-size: 11px;'>S2<br>" . $fecha_sesion2[0]->fecha . "<br>" . $fecha_sesion2[0]->hora_desde . " / " . $fecha_sesion2[0]->hora_hasta . "</th>";
        }
        if ($num_sesiones > 2) {
            $fecha_sesion3 = Fecha2021_fecha_sesion($id_imparticion, "3");
            $sesion_3 = "<th class='bg_gray' width='100px' style='font-size: 11px;'>S3<br>" . $fecha_sesion3[0]->fecha . "<br>" . $fecha_sesion3[0]->hora_desde . " / " . $fecha_sesion3[0]->hora_hasta . "</th>";
        }
        if ($num_sesiones > 3) {
            $checked_ses4 = Asistencia2021_Avance_Sesiones($usu->rut, $id_imparticion, "4");
            $sesion_4 = "<th class='bg_gray' width='100px' style='font-size: 11px;'>S4<br>" . $fecha_sesion4[0]->fecha . "<br>" . $fecha_sesion4[0]->hora_desde . " / " . $fecha_sesion4[0]->hora_hasta . "</th>";
        }
        if ($num_sesiones > 4) {
            $checked_ses5 = Asistencia2021_Avance_Sesiones($usu->rut, $id_imparticion, "5");
            $fecha_sesion5 = Fecha2021_fecha_sesion($id_imparticion, "5");
            $sesion_5 = "<th class='bg_gray' width='25%' style='font-size: 11px;'>S5<br>" . $fecha_sesion5[0]->fecha . "<br>" . $fecha_sesion5[0]->hora_desde . " / " . $fecha_sesion5[0]->hora_hasta . "</th>";
            $sesion_5_td = "<td><input type='checkbox' id='ses_5' name='ses_5_" . $usu->rut . "' " . $checked_ses5 . "></td>";
        }
        if ($num_sesiones > 5) {
            $checked_ses6 = Asistencia2021_Avance_Sesiones($usu->rut, $id_imparticion, "6");
            $fecha_sesion6 = Fecha2021_fecha_sesion($id_imparticion, "6");
            $sesion_6 = "<th class='bg_gray' width='25%' style='font-size: 11px;'>S6<br>" . $fecha_sesion6[0]->fecha . "<br>" . $fecha_sesion6[0]->hora_desde . " / " . $fecha_sesion6[0]->hora_hasta . "</th>";
            $sesion_6_td = "<td><input type='checkbox' id='ses_6' name='ses_6_" . $usu->rut . "' " . $checked_ses6 . "></td>";
        }
        if ($num_sesiones > 6) {
            $checked_ses7 = Asistencia2021_Avance_Sesiones($usu->rut, $id_imparticion, "7");
            $fecha_sesion7 = Fecha2021_fecha_sesion($id_imparticion, "7");
            $sesion_7 = "<th class='bg_gray' width='25%' style='font-size: 11px;'>S7<br>" . $fecha_sesion7[0]->fecha . "<br>" . $fecha_sesion7[0]->hora_desde . " / " . $fecha_sesion7[0]->hora_hasta . "</th>";
            $sesion_7_td = "<td><input type='checkbox' id='ses_7' name='ses_7_" . $usu->rut . "' " . $checked_ses7 . "></td>";
        }
        if ($num_sesiones > 7) {
            $checked_ses8 = Asistencia2021_Avance_Sesiones($usu->rut, $id_imparticion, "8");
            $fecha_sesion8 = Fecha2021_fecha_sesion($id_imparticion, "8");
            $sesion_8 = "<th class='bg_gray' width='25%' style='font-size: 11px;'>S8<br>" . $fecha_sesion8[0]->fecha . "<br>" . $fecha_sesion8[0]->hora_desde . " / " . $fecha_sesion8[0]->hora_hasta . "</th>";
            $sesion_8_td = "<td><input type='checkbox' id='ses_8' name='ses_8_" . $usu->rut . "' " . $checked_ses8 . "></td>";
        }
        if ($num_sesiones > 8) {
            $checked_ses9 = Asistencia2021_Avance_Sesiones($usu->rut, $id_imparticion, "9");
            $fecha_sesion9 = Fecha2021_fecha_sesion($id_imparticion, "9");
            $sesion_9 = "<th class='bg_gray' width='25%' style='font-size: 11px;'>S9<br>" . $fecha_sesion9[0]->fecha . "<br>" . $fecha_sesion9[0]->hora_desde . " / " . $fecha_sesion9[0]->hora_hasta . "</th>";
            $sesion_9_td = "<td><input type='checkbox' id='ses_9' name='ses_9_" . $usu->rut . "' " . $checked_ses9 . "></td>";
        }
        if ($num_sesiones > 9) {
            $checked_ses10 = Asistencia2021_Avance_Sesiones($usu->rut, $id_imparticion, "10");
            $fecha_sesion10 = Fecha2021_fecha_sesion($id_imparticion, "10");
            $sesion_10 = "<th class='bg_gray' width='25%' style='font-size: 11px;'>S10<br>" . $fecha_sesion10[0]->fecha . "<br>" . $fecha_sesion10[0]->hora_desde . " / " . $fecha_sesion10[0]->hora_hasta . "</th>";
            $sesion_10_td = "<td><input type='checkbox' id='ses_10' name='ses_10_" . $usu->rut . "' " . $checked_ses10 . "></td>";
        }
        if ($num_sesiones > 10) {
            $checked_ses11 = Asistencia2021_Avance_Sesiones($usu->rut, $id_imparticion, "11");
            $fecha_sesion11 = Fecha2021_fecha_sesion($id_imparticion, "11");
            $sesion_11 = "<th class='bg_gray' width='25%' style='font-size: 11px;'>S11<br>" . $fecha_sesion11[0]->fecha . "<br>" . $fecha_sesion11[0]->hora_desde . " / " . $fecha_sesion11[0]->hora_hasta . "</th>";
            $sesion_11_td = "<td><input type='checkbox' id='ses_11' name='ses_11_" . $usu->rut . "' " . $checked_ses11 . "></td>";
        }
        if ($num_sesiones > 11) {
            $checked_ses12 = Asistencia2021_Avance_Sesiones($usu->rut, $id_imparticion, "12");
            $fecha_sesion12 = Fecha2021_fecha_sesion($id_imparticion, "12");
            $sesion_12 = "<th class='bg_gray' width='25%' style='font-size: 11px;'>S12<br>" . $fecha_sesion12[0]->fecha . "<br>" . $fecha_sesion12[0]->hora_desde . " / " . $fecha_sesion12[0]->hora_hasta . "</th>";
            $sesion_12_td = "<td><input type='checkbox' id='ses_12' name='ses_12_" . $usu->rut . "' " . $checked_ses12 . "></td>";
        }
    }

    $PRINCIPAL = str_replace("{SESIONES_1_TH}", $sesion_1, $PRINCIPAL);
    $PRINCIPAL = str_replace("{SESIONES_2_TH}", $sesion_2, $PRINCIPAL);
    $PRINCIPAL = str_replace("{SESIONES_3_TH}", $sesion_3, $PRINCIPAL);
    $PRINCIPAL = str_replace("{SESIONES_4_TH}", $sesion_4, $PRINCIPAL);
    $PRINCIPAL = str_replace("{SESIONES_5_TH}", $sesion_5, $PRINCIPAL);
    $PRINCIPAL = str_replace("{SESIONES_6_TH}", $sesion_6, $PRINCIPAL);
    $PRINCIPAL = str_replace("{SESIONES_7_TH}", $sesion_7, $PRINCIPAL);
    $PRINCIPAL = str_replace("{SESIONES_8_TH}", $sesion_8, $PRINCIPAL);
    $PRINCIPAL = str_replace("{SESIONES_9_TH}", $sesion_9, $PRINCIPAL);
    $PRINCIPAL = str_replace("{SESIONES_10_TH}", $sesion_10, $PRINCIPAL);
    $PRINCIPAL = str_replace("{SESIONES_11_TH}", $sesion_11, $PRINCIPAL);
    $PRINCIPAL = str_replace("{SESIONES_12_TH}", $sesion_12, $PRINCIPAL);
    $PRINCIPAL = str_replace("{ID_MODALIDAD_BACK}", ($datos_imparticion[0]->id_modalidad), $PRINCIPAL);
    $PRINCIPAL = str_replace("{ROW_SESIONES}", ($row), $PRINCIPAL);
    $PRINCIPAL = str_replace("{ROW_LISTADO_COLABORADORES}", ($row_listado_colaboradores), $PRINCIPAL);
    return ($PRINCIPAL);
}
function FormularioCurso1($PRINCIPAL, $id_curso, $id_empresa)
{
    //echo "FormularioCurso2 id_curso $id_curso";
    $datos_curso = DatosCurso_1_2024($id_curso);
    //print_r($datos_curso);
    if ($datos_curso) {
        $id_curso = $datos_curso[0]->id;
    } else {
        $idNuevo = ListasPresenciales_CodigoIdCurso();
        $id_curso = $idNuevo;
    }

    $PRINCIPAL = str_replace("{NOM_CURSO}", ($datos_curso[0]->nombre), $PRINCIPAL);
    $PRINCIPAL = str_replace("{NOMBRE_CURSO_SENCE}", ($datos_curso[0]->nombre_curso_sence), $PRINCIPAL);
    $PRINCIPAL = str_replace("{COD_CURSO}", $id_curso, $PRINCIPAL);
    $PRINCIPAL = str_replace("{DES_CURSO}", ($datos_curso[0]->descripcion), $PRINCIPAL);

    $PRINCIPAL = str_replace("{NOMBRE_OTEC}", ($datos_curso[0]->nombre_otec), $PRINCIPAL);
    $PRINCIPAL = str_replace("{RUT_OTEC}", ($datos_curso[0]->rut_otec), $PRINCIPAL);
    $PRINCIPAL = str_replace("{ID_PROGRAMA_GLOBAL}", ($datos_curso[0]->id_programa_global), $PRINCIPAL);


    $PRINCIPAL = str_replace("{OBJETIVO_CURSO}", ($datos_curso[0]->objetivo_curso), $PRINCIPAL);
    $PRINCIPAL = str_replace("{NUMERO_HORAS}", ($datos_curso[0]->numero_horas), $PRINCIPAL);
    $PRINCIPAL = str_replace("{CANTIDAD_MAXIMA_PARTICIPANTES}", ($datos_curso[0]->cantidad_maxima_participantes), $PRINCIPAL);
    $PRINCIPAL = str_replace("{TIPO_CURSO}", ($datos_curso[0]->tipo), $PRINCIPAL);
    $PRINCIPAL = str_replace("{CHECKED" . $datos_curso[0]->sence . "}", "checked", $PRINCIPAL);
    $PRINCIPAL = str_replace("{CHECKED" . $datos_curso[0]->cbc . "CBC}", "checked", $PRINCIPAL);
    $PRINCIPAL = str_replace("{PREREQUISITO_CURSO}", ($datos_curso[0]->prerequisito), $PRINCIPAL);
    $PRINCIPAL = str_replace("{COD_SENCE}", ($datos_curso[0]->cod_sence), $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALOR_HORA}", ($datos_curso[0]->valor_hora), $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALOR_HORA_SENCE}", ($datos_curso[0]->valor_hora_sence), $PRINCIPAL);
    $PRINCIPAL = str_replace("{COD_IDENTIFICADOR}", ($datos_curso[0]->numero_identificador), $PRINCIPAL);
    $PRINCIPAL = str_replace("{NUMERO_IDENTIFICADOR}", ($datos_curso[0]->numero_identificador), $PRINCIPAL);
    $PRINCIPAL = str_replace("{CONTENIDOS_CURSO}", ($datos_curso[0]->contenidos_cursos), $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_COD_SENCE}", ($datos_curso[0]->codigosence), $PRINCIPAL);

    if (($datos_curso[0]->imagen)) {
        $PRINCIPAL = str_replace("{BLOQUE_IMAGEN}", file_get_contents("views/capacitacion/curso/bloque_imagen_curso.html"), $PRINCIPAL);
        $PRINCIPAL = str_replace("{URL_IMAGEN}", "" . ($datos_curso[0]->imagen), $PRINCIPAL);
    } else {
        $PRINCIPAL = str_replace("{BLOQUE_IMAGEN}", "", $PRINCIPAL);
    }

    $PRINCIPAL = str_replace("{SELECTED_" . $datos_curso[0]->sence . "}", "selected='selected'", $PRINCIPAL);
    if ($datos_curso) {
        $PRINCIPAL = str_replace("{VALOR_BOTON}", "Editar Curso", $PRINCIPAL);
        $PRINCIPAL = str_replace("{ACTION}", "?sw=edcurso1", $PRINCIPAL);
        $PRINCIPAL = str_replace("{TITULO}", "Formulario Edici&oacute;n de Cursos", $PRINCIPAL);
    } else {
        $PRINCIPAL = str_replace("{VALOR_BOTON}", "Ingresar Curso", $PRINCIPAL);
        $PRINCIPAL = str_replace("{ACTION}", "?sw=adcurso1", $PRINCIPAL);
        $PRINCIPAL = str_replace("{TITULO}", "Formulario Ingreso de Cursos", $PRINCIPAL);
    }
    $PRINCIPAL = str_replace("{NOMBRE_FORMULARIO}", "FormCurso", $PRINCIPAL);
    $PRINCIPAL = str_replace("{OPTIONS_MODALIDAD}", OptionsModalidad($datos_curso[0]->modalidad), $PRINCIPAL);
    $clasificacion = TotalClasificacion($id_empresa);
    foreach ($clasificacion as $cla) {
        if ($cla->id_clasificacion == $datos_curso[0]->clasificacion) {
            $select = "selected='selected'";
        } else {
            $select = "";
        }
        $opcionClasificacion .= '<option value=' . $cla->id_clasificacion . ' ' . $select . ' >' . ($cla->clasificacion) . '</option>';
    }
    $otecs = TraeOtec($id_empresa);
    foreach ($otecs as $otec) {
        if ($cla->rut == $datos_curso[0]->rut_otec) {
            $select2 = "selected";
        } else {
            $select2 = "";
        }
        $opcionOtect .= '<option value=' . $otec->rut . ' ' . $select2 . ' >' . ($otec->nombre) . ' (' . ($otec->rut) . ')</option>';
    }
    $PRINCIPAL = str_replace("{OPTIONS_CLASIFICACION}", $opcionClasificacion, $PRINCIPAL);
    $focos = ListasPresenciales_Obtienefocos($id_empresa);
    $options_focos = "";
    foreach ($focos as $foc) {
        if ($foc->codigo_foco == $datos_curso[0]->id_foco) {
            $options_focos .= "<option value='" . $foc->codigo_foco . "' selected='selected'>" . $foc->descripcion . "</option>";
        } else {
            $options_focos .= "<option value='" . $foc->codigo_foco . "'>" . $foc->descripcion . "</option>";
        }
    }
    $PRINCIPAL = str_replace("{OPTIONS_FOCOS}", ($options_focos), $PRINCIPAL);
    //$programas_bbdd=IMPARTCION_ObtieneProgramasBBDD($id_empresa);
    $programas_bbdd = ListaProgramasDadoFocoDeTabla_2022_data_sinanidacion($id_empresa);

    $options_programas_bbdd = "";
    foreach ($programas_bbdd as $prog_bbdd) {
        if ($prog_bbdd->id_programa == $datos_curso[0]->id_empresa_programa) {
            $options_programas_bbdd .= "<option value='" . $prog_bbdd->id_programa . "' selected='selected'>" . $prog_bbdd->nombre_programa . "</option>";
        } else {
            $options_programas_bbdd .= "<option value='" . $prog_bbdd->id_programa . "'>" . $prog_bbdd->nombre_programa . "</option>";
        }
    }
    $PRINCIPAL = str_replace("{OPTIONS_PROGRAMAS_BBDD_2022_sinanidacion}", ($options_programas_bbdd), $PRINCIPAL);
    $divisiones_mandante = TraeDivisionesMandante();
    $options_divisiones_mandante = "";
    foreach ($divisiones_mandante as $dm) {
        if ($dm->silla_id_division == $datos_curso[0]->bajada) {
            $options_divisiones_mandante .= "<option value='" . $dm->silla_id_division . "' selected='selected'>" . $dm->division . "</option>";
        } else {
            $options_divisiones_mandante .= "<option value='" . $dm->silla_id_division . "' >".$dm->division."</option>";
        }
    }
    $PRINCIPAL = str_replace("{OPTIONS_DIVISION_MANDANTE}", ($options_divisiones_mandante), $PRINCIPAL);
    $PRINCIPAL = str_replace("{OPTIONS_PROGRAMAS_BBDD}", ($options_programas_bbdd), $PRINCIPAL);
    $PRINCIPAL = str_replace("{OPTIONS_RUT_OTEC}", $opcionOtect, $PRINCIPAL);
    $PRINCIPAL = str_replace("{ID_CURSO_ENCODEADO}", Encodear(Encodear($datos_curso[0]->id)), $PRINCIPAL);
    $PRINCIPAL = str_replace("{OPTIONS_TIPO_CURSO}", Lista_OptionsGenericoNombreDadoValores("tbl_curso_tipo", $datos_curso[0]->tipo, "nombre", "id"), $PRINCIPAL);
    $PRINCIPAL = str_replace("{SELSENCE" . trim($datos_curso[0]->sence) . "}", "selected='selected'", $PRINCIPAL);
    $PRINCIPAL = str_replace("{SELCBC" . trim($datos_curso[0]->cbc) . "}", "selected='selected'", $PRINCIPAL);
    if ($datos_curso[0]->sence == "si") {
        $PRINCIPAL = str_replace("{STYLE_DISPLAY_COD_SENCE}", '', $PRINCIPAL);
        $PRINCIPAL = str_replace("{STYLE_DISPLAY_NUM_IDEN}", 'style="display:none;"', $PRINCIPAL);
    } else {
        $PRINCIPAL = str_replace("{STYLE_DISPLAY_COD_SENCE}", 'style="display:none;"', $PRINCIPAL);
        $PRINCIPAL = str_replace("{STYLE_DISPLAY_NUM_IDEN}", '', $PRINCIPAL);
    }
    return ($PRINCIPAL);
}
function FormularioCurso2($PRINCIPAL, $id_curso, $id_empresa)
{
    //echo "FormularioCurso2 id_curso $id_curso";
    $datos_curso = DatosCurso_2($id_curso);
    //print_r($datos_curso);
    if ($datos_curso) {
        $id_curso = $datos_curso[0]->id;
    } else {
        $idNuevo = ListasPresenciales_CodigoIdCurso();
        $id_curso = $idNuevo;
    }

    $PRINCIPAL = str_replace("{NOM_CURSO}", ($datos_curso[0]->nombre), $PRINCIPAL);
    $PRINCIPAL = str_replace("{NOMBRE_CURSO_SENCE}", ($datos_curso[0]->nombre_curso_sence), $PRINCIPAL);
    $PRINCIPAL = str_replace("{COD_CURSO}", $id_curso, $PRINCIPAL);
    $PRINCIPAL = str_replace("{DES_CURSO}", ($datos_curso[0]->descripcion), $PRINCIPAL);
    $PRINCIPAL = str_replace("{OBJETIVO_CURSO}", ($datos_curso[0]->objetivo_curso), $PRINCIPAL);
    $PRINCIPAL = str_replace("{NUMERO_HORAS}", ($datos_curso[0]->numero_horas), $PRINCIPAL);
    $PRINCIPAL = str_replace("{CANTIDAD_MAXIMA_PARTICIPANTES}", ($datos_curso[0]->cantidad_maxima_participantes), $PRINCIPAL);
    $PRINCIPAL = str_replace("{TIPO_CURSO}", ($datos_curso[0]->tipo), $PRINCIPAL);
    $PRINCIPAL = str_replace("{CHECKED" . $datos_curso[0]->sence . "}", "checked", $PRINCIPAL);
    $PRINCIPAL = str_replace("{CHECKED" . $datos_curso[0]->cbc . "CBC}", "checked", $PRINCIPAL);
    $PRINCIPAL = str_replace("{PREREQUISITO_CURSO}", ($datos_curso[0]->prerequisito), $PRINCIPAL);
    $PRINCIPAL = str_replace("{COD_SENCE}", ($datos_curso[0]->cod_sence), $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALOR_HORA}", ($datos_curso[0]->valor_hora), $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALOR_HORA_SENCE}", ($datos_curso[0]->valor_hora_sence), $PRINCIPAL);
    $PRINCIPAL = str_replace("{COD_IDENTIFICADOR}", ($datos_curso[0]->numero_identificador), $PRINCIPAL);
    $PRINCIPAL = str_replace("{NUMERO_IDENTIFICADOR}", ($datos_curso[0]->numero_identificador), $PRINCIPAL);
    $PRINCIPAL = str_replace("{CONTENIDOS_CURSO}", ($datos_curso[0]->contenidos_cursos), $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_COD_SENCE}", ($datos_curso[0]->codigosence), $PRINCIPAL);

    if (($datos_curso[0]->imagen)) {
        $PRINCIPAL = str_replace("{BLOQUE_IMAGEN}", file_get_contents("views/capacitacion/curso/bloque_imagen_curso.html"), $PRINCIPAL);
        $PRINCIPAL = str_replace("{URL_IMAGEN}", "" . ($datos_curso[0]->imagen), $PRINCIPAL);
    } else {
        $PRINCIPAL = str_replace("{BLOQUE_IMAGEN}", "", $PRINCIPAL);
    }

    $PRINCIPAL = str_replace("{SELECTED_" . $datos_curso[0]->sence . "}", "selected='selected'", $PRINCIPAL);
    if ($datos_curso) {
        $PRINCIPAL = str_replace("{VALOR_BOTON}", "Editar Curso", $PRINCIPAL);
        $PRINCIPAL = str_replace("{ACTION}", "?sw=edcurso2", $PRINCIPAL);
        $PRINCIPAL = str_replace("{TITULO}", "Formulario Edici&oacute;n de Cursos", $PRINCIPAL);
    } else {
        $PRINCIPAL = str_replace("{VALOR_BOTON}", "Ingresar Curso", $PRINCIPAL);
        $PRINCIPAL = str_replace("{ACTION}", "?sw=adcurso2", $PRINCIPAL);
        $PRINCIPAL = str_replace("{TITULO}", "Formulario Ingreso de Cursos", $PRINCIPAL);
    }
    $PRINCIPAL = str_replace("{NOMBRE_FORMULARIO}", "FormCurso", $PRINCIPAL);
    $PRINCIPAL = str_replace("{OPTIONS_MODALIDAD}", OptionsModalidad($datos_curso[0]->modalidad), $PRINCIPAL);
    $clasificacion = TotalClasificacion($id_empresa);
    foreach ($clasificacion as $cla) {
        if ($cla->id_clasificacion == $datos_curso[0]->clasificacion) {
            $select = "selected='selected'";
        } else {
            $select = "";
        }
        $opcionClasificacion .= '<option value=' . $cla->id_clasificacion . ' ' . $select . ' >' . ($cla->clasificacion) . '</option>';
    }
    $otecs = TraeOtec($id_empresa);
    foreach ($otecs as $otec) {
        if ($cla->rut == $datos_curso[0]->rut_otec) {
            $select2 = "selected";
        } else {
            $select2 = "";
        }
        $opcionOtect .= '<option value=' . $otec->rut . ' ' . $select2 . ' >' . ($otec->nombre) . ' (' . ($otec->rut) . ')</option>';
    }
    $PRINCIPAL = str_replace("{OPTIONS_CLASIFICACION}", $opcionClasificacion, $PRINCIPAL);
    $focos = ListasPresenciales_Obtienefocos($id_empresa);
    $options_focos = "";
    foreach ($focos as $foc) {
        if ($foc->codigo_foco == $datos_curso[0]->id_foco) {
            $options_focos .= "<option value='" . $foc->codigo_foco . "' selected='selected'>" . $foc->descripcion . "</option>";
        } else {
            $options_focos .= "<option value='" . $foc->codigo_foco . "'>" . $foc->descripcion . "</option>";
        }
    }
    $PRINCIPAL = str_replace("{OPTIONS_FOCOS}", ($options_focos), $PRINCIPAL);
    //$programas_bbdd=IMPARTCION_ObtieneProgramasBBDD($id_empresa);
    $programas_bbdd = ListaProgramasDadoFocoDeTabla_2022_data_sinanidacion($id_empresa);

    $options_programas_bbdd = "";
    foreach ($programas_bbdd as $prog_bbdd) {
        if ($prog_bbdd->id_programa == $datos_curso[0]->id_empresa_programa) {
            $options_programas_bbdd .= "<option value='" . $prog_bbdd->id_programa . "' selected='selected'>" . $prog_bbdd->nombre_programa . "</option>";
        } else {
            $options_programas_bbdd .= "<option value='" . $prog_bbdd->id_programa . "'>" . $prog_bbdd->nombre_programa . "</option>";
        }
    }
    $PRINCIPAL = str_replace("{OPTIONS_PROGRAMAS_BBDD_2022_sinanidacion}", ($options_programas_bbdd), $PRINCIPAL);
    $divisiones_mandante = TraeDivisionesMandante();
    $options_divisiones_mandante = "";
    foreach ($divisiones_mandante as $dm) {
        if ($dm->silla_id_division == $datos_curso[0]->bajada) {
            $options_divisiones_mandante .= "<option value='" . $dm->silla_id_division . "' selected='selected'>" . $dm->division . "</option>";
        } else {
            $options_divisiones_mandante .= "<option value='" . $dm->silla_id_division . "' >".$dm->division."</option>";
        }
    }

    $PRINCIPAL = str_replace("{OPTIONS_DIVISION_MANDANTE}", ($options_divisiones_mandante), $PRINCIPAL);
    $PRINCIPAL = str_replace("{OPTIONS_RUT_OTEC}", $opcionOtect, $PRINCIPAL);
    $PRINCIPAL = str_replace("{ID_CURSO_ENCODEADO}", (Encodear3($datos_curso[0]->id)), $PRINCIPAL);
    $PRINCIPAL = str_replace("{OPTIONS_TIPO_CURSO}", Lista_OptionsGenericoNombreDadoValores("tbl_curso_tipo", $datos_curso[0]->tipo, "nombre", "id"), $PRINCIPAL);
    $PRINCIPAL = str_replace("{SELSENCE" . trim($datos_curso[0]->sence) . "}", "selected='selected'", $PRINCIPAL);
    $PRINCIPAL = str_replace("{SELCBC" . trim($datos_curso[0]->cbc) . "}", "selected='selected'", $PRINCIPAL);
    if ($datos_curso[0]->sence == "si") {
        $PRINCIPAL = str_replace("{STYLE_DISPLAY_COD_SENCE}", '', $PRINCIPAL);
        $PRINCIPAL = str_replace("{STYLE_DISPLAY_NUM_IDEN}", 'style="display:none;"', $PRINCIPAL);
    } else {
        $PRINCIPAL = str_replace("{STYLE_DISPLAY_COD_SENCE}", 'style="display:none;"', $PRINCIPAL);
        $PRINCIPAL = str_replace("{STYLE_DISPLAY_NUM_IDEN}", '', $PRINCIPAL);
    }
    return ($PRINCIPAL);
}

function OptionsModalidad($id_modalidad)
            {
                $total_modalidad = TotalModalidad2();
                $select = "";
                foreach ($total_modalidad as $modalidad) {
                    if ($modalidad->id == $id_modalidad) {
                        $select .= '<option selected value="' . $modalidad->id . '">' . $modalidad->modalidad . '</option>';
                    } else {
                        $select .= '<option value="' . $modalidad->id . '">' . $modalidad->modalidad . '</option>';
                    }
                }
                return (($select));
            }
function Lista_OptionsGenericoNombreDadoValores($seleccionado)
{
    $valores = ListaCursos_ListaModalidadCursos();
    foreach ($valores as $val) {
        if ($val->id == $seleccionado) {
            $opciones .= "<option selected value='" . $val->id . "'>" . $val->modalidad . "</option>";
        } else {
            $opciones .= "<option value='" . $val->id . "'>" . $val->modalidad . "</option>";
        }
    }
    return (($opciones));
}			
function ListarTiposDocumentosFinanza($cat){
    $optionsTipos = "";
    $tipos = ListarTiposDocumentosFinanzaData($cat);
    foreach ($tipos as $row){
        $idEncodeado = Encodear3($row->tipdoc_id);
        $documento = (strtoupper($row->tipdoc_dsc));
        $optionsTipos .="<option value='{$idEncodeado}'>{$documento}</option>";
    }
    return $optionsTipos;
}		
function ListarServiciosFinanza(){
    $optionsServicios = "";
    $servicios = ListarServiciosFinanzaData();
    foreach ($servicios as $row){
        $idEncodeado = Encodear3($row->servic_id);
        $servicio = (strtoupper($row->servic_dsc));
        $optionsServicios .="<option value='{$idEncodeado}'>{$servicio}</option>";
    }
    return $optionsServicios;
}	
function ListarCuentasFinanza(){
    $optionsCuentas = "";
    $servicios = ListarCuentasFinanzaData();
    foreach ($servicios as $row){
        $idEncodeado = $row->cuenta_num;
        $cuenta = (strtoupper($row->cuenta_dsc));
        $optionsCuentas .="<option value='{$idEncodeado}'>{$cuenta}</option>";
    }
    return $optionsCuentas;
}
function ListarProyectosFinanza(){
    $optionsProyectos = "";
    $servicios = ListarProyectosFinanzaData();
    foreach ($servicios as $row){
        $idEncodeado = Encodear3($row->id);
        $cuenta = $row->cuenta;
        $proyecto = (strtoupper($row->nombre_programa));
        $optionsProyectos .="<option class='optionProyecto optionProyecto{$cuenta}' value='{$proyecto}'>{$proyecto}</option>";
    }
    return $optionsProyectos;
}
function ListarCUIFinanza(){
    $optionsCui = "";
    $servicios = ListarCUIFinanzaData();
    foreach ($servicios as $row){
        $idEncodeado = Encodear3($row->cui_codigo);
        $cui = (strtoupper($row->cui_desc));
        $optionsCui .="<option value='{$idEncodeado}'>{$cui}</option>";
    }
    return $optionsCui;
}
function ListResponsables(){
    $responsables = [];
    $consulResp = ListResponsablesData();
    foreach ($consulResp as $row){
        if(!isset($responsables[$row->respon_tipresid])){
            $responsables[$row->respon_tipresid] = [];
        }
        $responsables[$row->respon_tipresid][$row->respon_id]['rut']=$row->respon_rutresp;
        $responsables[$row->respon_tipresid][$row->respon_id]['nombre']=$row->nombre_completo;
        $responsables[$row->respon_tipresid][$row->respon_id]['sla']=$row->tipres_sla_factura;
        $responsables[$row->respon_tipresid][$row->respon_id]['slareem']=$row->tipres_sla_reembolso;
    }
    return $responsables;
}
function saveDatosGeneralesFactura($factur_numdoc, $factur_tipdocid, $factur_proveerut,
                                  $factur_servid, $factur_servotro, $factur_montoto, $factur_montonet, $factur_impuest,
                                  $factur_fecemision, $factur_ota, $factur_numota, $factur_cuenta,
                                  $factur_proyecto, $factur_curso, $factur_otanombre, $factur_cui,
                                  $factur_respgast, $factur_observacion, $factur_mes, $factur_anual,$factur_status,$factura_proveenom,$rut_created,$factur_cuenta_nombre){

    $result = array("code"=>1, "mensage"=>"Actualizado");
    //Se validan los datos recibidos antes de ser guardados

    //Se verifica el numero de documento proporcionado no se repita
    $consulNumDocFac = facturaFinanzaByNumdoc($factur_numdoc);
    if(count($consulNumDocFac)==0){
        //Se ubica el id del proveedor segun el rut suministrado
        $consulOtec=DatosOtecDadoRut($factur_proveerut);
        $factur_proveeid=$consulOtec[0]->id;

        //Se ubica el id de la cuenta segun el numero suministrado
        $factur_cuentaid=0;

        //Se ubica el id del proyecto segun el nombre suministrado
        $consulProyecto=DatosProgramaByNombre($factur_proyecto);
        $factur_proyectid=$consulProyecto[0]->id;

        $factur_tipdocid = Decodear3($factur_tipdocid);
        $factur_servid = Decodear3($factur_servid);
        $factur_cui = Decodear3($factur_cui);
        $factur_respgast = Decodear3($factur_respgast);
        if($factur_ota!="on"){
            $factur_ota=0;
        } else {
            $factur_ota=1;
        }
        return saveDatosGeneralesFacturaData($factur_numdoc, $factur_tipdocid, $factur_proveeid, $factur_proveerut,
                                      $factur_servid, $factur_servotro, $factur_montoto, $factur_montonet, $factur_impuest,
                                      $factur_fecemision, $factur_ota, $factur_numota, $factur_cuentaid, $factur_cuenta,
                                      $factur_proyectid, $factur_proyecto, $factur_curso, $factur_otanombre, $factur_cui,
                                      $factur_respgast, $factur_observacion, $factur_mes, $factur_anual,$factur_status,$factura_proveenom,$rut_created,$factur_cuenta_nombre);
    } else {
        $result = array("code"=>0, "mensage"=>"Número de documento ya existe");
    }
    return $result;
}
function saveDatosGeneralesReembolso($reembo_numdoc, $reembo_tipdocid,$reembo_tipdocOtro, $reembo_proveerut,
                                  $reembo_servid, $reembo_servotro, $reembo_montoto,
                                  $reembo_fecemision, $reembo_ota, $reembo_numota, $reembo_cuenta,
                                  $reembo_proyecto, $reembo_curso, $reembo_otanombre, $reembo_cui,
                                  $reembo_respgast, $reembo_observacion, $reembo_mes, $reembo_anual,$reembo_status,$reemboa_proveenom,$rut_created,$reembo_cuenta_nombre){

    $result = array("code"=>1, "mensage"=>"Actualizado");
    //Se validan los datos recibidos antes de ser guardados

    //Se verifica el numero de documento proporcionado no se repita
    $consulNumDocReemb= reembolsoFinanzaByNumdoc($reembo_numdoc);
    if(count($consulNumDocReemb)==0){
        //Se ubica el id del proveedor segun el rut suministrado
        $reembo_proveeid=$reembo_proveerut;

        //Se ubica el id de la cuenta segun el numero suministrado
        $reembo_cuentaid=0;

        //Se ubica el id del proyecto segun el nombre suministrado
        $consulProyecto=DatosProgramaByNombre($reembo_proyecto);
        $reembo_proyectid=$consulProyecto[0]->id;

        $reembo_tipdocid = Decodear3($reembo_tipdocid);
        $reembo_servid = Decodear3($reembo_servid);
        $reembo_cui = Decodear3($reembo_cui);
        if($reembo_ota!="on"){
            $reembo_ota=0;
        } else {
            $reembo_ota=1;
        }
        return saveDatosGeneralesReembolsoData($reembo_numdoc, $reembo_tipdocid,$reembo_tipdocOtro, $reembo_proveeid, $reembo_proveerut,
                                      $reembo_servid, $reembo_servotro, $reembo_montoto,
                                      $reembo_fecemision, $reembo_ota, $reembo_numota, $reembo_cuentaid, $reembo_cuenta,
                                      $reembo_proyectid, $reembo_proyecto, $reembo_curso, $reembo_otanombre, $reembo_cui,
                                      $reembo_respgast, $reembo_observacion, $reembo_mes, $reembo_anual,$reembo_status,$reemboa_proveenom,$rut_created,$reembo_cuenta_nombre);
    } else {
        $result = array("code"=>0, "mensage"=>"Número de documento ya existe");
    }
    return $result;
}

function updateDatosGeneralesFactura($id,$factur_numdoc, $factur_tipdocid, $factur_proveerut,
                                      $factur_servid, $factur_servotro, $factur_montoto, $factur_montonet, $factur_impuest,
                                      $factur_fecemision, $factur_ota, $factur_numota, $factur_cuenta,
                                      $factur_proyecto, $factur_curso, $factur_otanombre, $factur_cui,
                                      $factur_respgast, $factur_observacion, $factur_mes, $factur_anual,$factur_status,$factura_proveenom,$factur_cuenta_nombre){

    //Se validan los datos recibidos antes de ser guardados
    //Se ubica el id del proveedor segun el rut suministrado
    $consulOtec=DatosOtecDadoRut($factur_proveerut);
    $factur_proveeid=$consulOtec[0]->id;

    //Se ubica el id de la cuenta segun el numero suministrado
    $factur_cuentaid=0;

    //Se ubica el id del proyecto segun el nombre suministrado
    $consulProyecto=DatosProgramaByNombre($factur_proyecto);
    $factur_proyectid=$consulProyecto[0]->id;
    if($factur_ota!="on"){
        $factur_ota=0;
    } else {
        $factur_ota=1;
    }
    $factur_tipdocid = Decodear3($factur_tipdocid);
    $factur_servid = Decodear3($factur_servid);
    $factur_cui = Decodear3($factur_cui);
    $factur_respgast = Decodear3($factur_respgast);
    updateDatosGeneralesFacturaData($id,$factur_numdoc, $factur_tipdocid, $factur_proveeid, $factur_proveerut,
                                  $factur_servid, $factur_servotro, $factur_montoto, $factur_montonet, $factur_impuest,
                                  $factur_fecemision, $factur_ota, $factur_numota, $factur_cuentaid, $factur_cuenta,
                                  $factur_proyectid, $factur_proyecto, $factur_curso, $factur_otanombre, $factur_cui,
                                  $factur_respgast, $factur_observacion, $factur_mes, $factur_anual,$factur_status,$factura_proveenom,$factur_cuenta_nombre);
}
function updateDatosGeneralesReembolso($id,$reembo_numdoc, $reembo_tipdocid,$reembo_tipdocOtro, $reembo_proveerut,
                                      $reembo_servid, $reembo_servotro, $reembo_montoto, $reembo_montonet, $reembo_impuest,
                                      $reembo_fecemision, $reembo_ota, $reembo_numota, $reembo_cuenta,
                                      $reembo_proyecto, $reembo_curso, $reembo_otanombre, $reembo_cui,
                                      $reembo_respgast, $reembo_observacion, $reembo_mes, $reembo_anual,$reembo_status,$reemboa_proveenom,$reembo_cuenta_nombre){

    //Se validan los datos recibidos antes de ser guardados
    $reembo_proveeid=$reembo_proveerut;

    //Se ubica el id de la cuenta segun el numero suministrado
    $reembo_cuentaid=0;

    //Se ubica el id del proyecto segun el nombre suministrado
    $consulProyecto=DatosProgramaByNombre($reembo_proyecto);
    $reembo_proyectid=$consulProyecto[0]->id;
    if($reembo_ota!="on"){
        $reembo_ota=0;
    } else {
        $reembo_ota=1;
    }
    $reembo_tipdocid = Decodear3($reembo_tipdocid);
    $reembo_servid = Decodear3($reembo_servid);
    $reembo_cui = Decodear3($reembo_cui);
    $reembo_respgast= Decodear3($reembo_respgast);
    updateDatosGeneralesReembolsoData($id,$reembo_numdoc, $reembo_tipdocid,$reembo_tipdocOtro, $reembo_proveeid, $reembo_proveerut,
                                  $reembo_servid, $reembo_servotro, $reembo_montoto, $reembo_montonet, $reembo_impuest,
                                  $reembo_fecemision, $reembo_ota, $reembo_numota, $reembo_cuentaid, $reembo_cuenta,
                                  $reembo_proyectid, $reembo_proyecto, $reembo_curso, $reembo_otanombre, $reembo_cui,
                                  $reembo_respgast, $reembo_observacion, $reembo_mes, $reembo_anual,$reembo_status,$reemboa_proveenom,$reembo_cuenta_nombre);
}
function getRespById($idFac){
    $responsables = [];
    $consulRespById = getRespByIdData($idFac);
    foreach($consulRespById as $row){
        $responsables[$row->facres_tiprespid]=array("resprut"=>Encodear3($row->facres_rut),"respnom"=>$row->facres_nomcom,"tipo"=> $row->tipres_desc,"recep"=>$row->facres_fecrecep, "envio"=>$row->facres_fecenv, "sla"=>$row->facres_sla);
    }
    return $responsables;
}
function getRespReemboById($idResp){
    $responsables = [];
    $consulRespById = getRespReemboByIdData($idResp);
    foreach($consulRespById as $row){
        $responsables[$row->reemres_tiprespid]=array("resprut"=>Encodear3($row->reemres_rut),"respnom"=>$row->reemres_nomcom,"tipo"=> $row->tipres_desc, "recep"=>$row->reemres_fecrecep, "envio"=>$row->reemres_fecenv, "sla"=>$row->reemres_sla);
    }
    return $responsables;
}
function getDocumentoById($idFac){
    $documentos = getDocumentoByIdData($idFac);
    return $documentos;
}
function getDocumentoReemboById($idRem){
    $documentos = getDocumentoReemboByIdData($idRem);
    return $documentos;
}
function GetFacturas()
{
    $result = array("draw" => 0, "recordsTotal" => 0, "recordsFiltered" => 0, "data" => []);
    $facturas = Lista_Facturas();
    foreach ($facturas as $key => $row){
        $facturas[$key]->factur_tipdocid = Encodear3($row->factur_tipdocid);
        $facturas[$key]->factur_servid = Encodear3($row->factur_servid);
        $facturas[$key]->factur_cui = Encodear3($row->factur_cui);
        $facturas[$key]->factur_respgastEncodeado = Encodear3($row->factur_respgast);
    }
    $cantidad = count($facturas);
    $result['recordsTotal'] = $cantidad;
    $result['recordsFiltered'] = $cantidad;
    $result['data'] = [];
    if($cantidad>0){
        $result['data'] = $facturas;
    }
    return $result;
}

function GetReembolsos()
{
    $result = array("draw" => 0, "recordsTotal" => 0, "recordsFiltered" => 0, "data" => []);
    $reembolsos = Lista_Reembolsos();
    foreach ($reembolsos as $key => $row){
        $reembolsos[$key]->reembo_tipdocid = Encodear3($row->reembo_tipdocid);
        $reembolsos[$key]->reembo_servid = Encodear3($row->reembo_servid);
        $reembolsos[$key]->reembo_cui = Encodear3($row->reembo_cui);
        $reembolsos[$key]->reembo_respgastEncodeado = Encodear3($row->reembo_respgast);
    }
    $cantidad = count($reembolsos);
    $result['recordsTotal'] = $cantidad;
    $result['recordsFiltered'] = $cantidad;
    $result['data'] = [];
    if($cantidad>0){
        $result['data'] = $reembolsos;
    }
    return $result;
}


function NuevoCodigoImparticion_2021_bk($codigo_anterior)
{
    //echo "<br>codigo_anterior $codigo_anterior";
    $cuentaCaracteres = strlen($codigo_anterior);
    //echo "<br>-> cuentaCaracteres $cuentaCaracteres";
    if ($cuentaCaracteres == "10") {
    } else {
        $codigo_anterior = 0;
    }
    $Y = date("Y");
    $M = date("m");
    $Ultimos4Digitos = substr($codigo_anterior, -4);
    $ultimos4DigitosMasUno = round($Ultimos4Digitos) + 1;
    $cuentaCaracteres4Digitos = strlen($ultimos4DigitosMasUno);
    if ($cuentaCaracteres4Digitos == 4) {
        $cuatrodigitosmasuno = $ultimos4DigitosMasUno;
    } elseif ($cuentaCaracteres4Digitos == 3) {
        $cuatrodigitosmasuno = "0" . $ultimos4DigitosMasUno;
    } elseif ($cuentaCaracteres4Digitos == 2) {
        $cuatrodigitosmasuno = "00" . $ultimos4DigitosMasUno;
    } elseif ($cuentaCaracteres4Digitos == 1) {
        $cuatrodigitosmasuno = "000" . $ultimos4DigitosMasUno;
    }
    //echo "<br>-> YYYY $Y";
    //echo "<br>-> MM $M";
    //echo "<br>-> Ultimos4Digitos $Ultimos4Digitos";
    //echo "<br>-> ultimos4DigitosMasUno $ultimos4DigitosMasUno";
    $nuevo_codigo = $Y . $M . $cuatrodigitosmasuno;
    //yyymm0001
    //echo "<br>-> <h4>nuevo_codigo $nuevo_codigo</h4>";
    //exit();
    return $nuevo_codigo;
}

function ListadoCursosAdmin1($PRINCIPAL, $id_empresa, $excel, $id_curso, $llamada)
{
    //echo "$id_empresa, excel $excel, $id_curso, $llamada";
    $cursos = Listado_TotalCursos1_2024($id_empresa, $id_curso, $llamada);
    $total_html = "";
    //print_r($cursos);

    $i = 1;
    foreach ($cursos as $unico) {

        /*if ($excel == 1) {
            $row = file_get_contents("views/capacitacion/curso/row_listado_excel.html");
        } else */
        if ($llamada == "imparticion_solo_preenciales") {
            $row = file_get_contents("views/capacitacion/curso/row_listado_imparticion.html");
        } else {
            $row = file_get_contents("views/capacitacion/curso/row_listado1.html");
        }

        $row = str_replace("{NUMERO}", $i, $row);
        $i++;
        $row = str_replace("{CODIGO}", "<span class='label bg-blue'>" . $unico->numero_identificador . "</span>", $row);
        $row = str_replace("{CODIGO_CURSO}", "<span class='label bg-blue'>" . $unico->id . "</span>", $row);
        $row = str_replace("{CODIGO_ENCODEADO}", Encodear3($unico->id), $row);
        $row = str_replace("{NOMBRE}", ($unico->nombre), $row);
        $row = str_replace("{NOMBRE_XLS}", ($unico->nombre), $row);
        $row = str_replace("{FOCO}", ($unico->nombre_foco), $row);
        $row = str_replace("{ID_PROGRAMA_GLOBAL}", ($unico->id_programa_global), $row);
        $row = str_replace("{FOCO_XLS}", ($unico->nombre_foco), $row);
        $busca_imparticiones_array = BuscaIdImparticiondadoCurso($unico->id, $id_empresa);
        if (count($busca_imparticiones_array) > 0) {
            $row = str_replace("{EJECUTIVO}", $unico->ejecutivo, $row);
        } else {
            $row = str_replace("{EJECUTIVO}", "<small></small>", $row);
        }
        $row = str_replace("{EJECUTIVO_XLS}", ($unico->ejecutivo), $row);
        $row = str_replace("{PROGRAMA}", ($unico->nombre_programa), $row);
        $row = str_replace("{PROGRAMA_XLS}", ($unico->nombre_programa), $row);
        $row = str_replace("{DESCRIPCION}", $unico->descripcion, $row);
        $row = str_replace("{OBJETIVO_CURSO}", $unico->objetivo_curso, $row);
        $row = str_replace("{CLASIFICACION}", $unico->nombre_clasificacion, $row);
        //$row = str_replace("{NUM_IMPARTICIONES}",$unico->total_inscripciones_curso,$row);
        //print_r($busca_imparticion_array);
        $lista_imparticion = "";
        $lista_imparticion_xls = "";
        $suma_asistentes = 0;
        $cuentaimp = 0;
        foreach ($busca_imparticiones_array as $buscimpart) {
            $cuentaimp++;
            $lista_imparticion .= $buscimpart->codigo_inscripcion . "<br>";
            $lista_imparticion_xls .= $buscimpart->codigo_inscripcion . ",";
            $cuenta_Asistententes = BuscaAsistentesImparticion($buscimpart->codigo_inscripcion, $id_empresa);
            $suma_asistentes = $suma_asistentes + $cuenta_Asistententes[0]->asistentes;
            //print_r($cuenta_Asistententes);
        }
        if ($cuentaimp == 0) {
            $lista_imparticion = "<small>[SIN_IMPARTICIONES]</small><br>";
        }
        //cuentaparticipantes
        //$cuenta_Asistententes=BuscaAsistentesCursoImparticion($unico->id, $id_empresa);
        $Asistententes = $cuenta_Asistententes[0]->asistentes;
        $row = str_replace("{NUM_IMPARTICIONES}", $lista_imparticion, $row);
        $row = str_replace("{NUM_IMPARTICIONES_XLS}", $lista_imparticion_xls, $row);
        if ($cuentaimp > 0) {
            $boton_ver = "<a href='?sw=listaInscripciones1&i=" . Encodear3($unico->id) . "'  data-placement='left' title='Ver_Imparticiones'>Ver_Imparticiones</a><br>";
        } else {
            $boton_ver = "";
        }
        $row = str_replace("{VER_IMPARTICIONES}", $boton_ver, $row);
        $row = str_replace("{SENCE}", $unico->sence, $row);
        $row = str_replace("{PARTICIPANTES_MAX}", $unico->cantidad_maxima_participantes, $row);
        $row = str_replace("{NOMBRE_OTEC}", $unico->nombre_otec, $row);
        $row = str_replace("{RUT_OTEC}", $unico->rut_otec, $row);
        $row = str_replace("{CBC}", $unico->cbc, $row);
        $row = str_replace("{MODALIDAD}", "<span class='label bg-blue'>" . $unico->nombre_modalidad . "</span>", $row);
        //$row = str_replace("{TIPO}",$unico->tipo,$row);
        $row = str_replace("{TIPO}", $unico->nombre_tipo_curso, $row);
        $row = str_replace("{PREREQUISITOS}", $unico->prerequisito, $row);

        $preinscritos = "";
        $postulantes = "";
        $asistentes = "";
        if ($unico->total_inscripciones_curso == 0) {
            $inscritos = "<strong>0</strong> Inscritos";
            if ($unico->nombre_modalidad == "Presencial") {
                $preinscritos = "<br><strong>0</strong> Preinscritos";
                $postulantes = "<br><strong>0</strong> Postulantes";
            }
        } else {
            $inscritos = "<strong>" . $unico->inscritos . "</strong> Inscritos";
            if ($unico->nombre_modalidad == "Presencial") {
                if ($unico->preinscritos > 0) {
                    $preinscritos = "<br><strong>" . $unico->preinscritos . "</strong> Preinscritos";
                } else {
                    $preinscritos = "<br><strong>0</strong> Preinscritos";
                }
                if ($unico->postulantes > 0) {
                    $postulantes = "<br><strong>" . $unico->postulantes . "</strong> Postulantes";
                } else {
                    $postulantes = "";
                }
                if ($Asistententes > 0) {
                    $asistentes = "<br><strong>" . $Asistententes . "</strong> Asistentes";
                } else {
                    $asistentes = "<br><strong>0</strong> Asistentes";
                }
            }
        }
        $row = str_replace("{INSCRITOS}", $inscritos, $row);
        $row = str_replace("{PREINSCRITOS}", $preinscritos, $row);
        $row = str_replace("{POSTULANTES}", $postulantes, $row);
        $row = str_replace("{ASISTENTES}", $asistentes, $row);
        $row = str_replace("{NUM_INSCRITOS}", $unico->inscritos, $row);
        $row = str_replace("{NUM_PREINSCRITOS}", $unico->preinscritos, $row);
        $row = str_replace("{NUM_POSTULANTES}", $unico->postulantes, $row);
        $row = str_replace("{NUM_ASISTENTES}", $Asistententes, $row);

        //Por cada curso, traigl el total de programas asociados desde rel lms malla curso
        $programas = TraeProgramasUnicosDadoCursoDeRelMallaCurso($id_empresa, $unico->id);
        $acumulador_programas = "";
        foreach ($programas as $prog) {
            $acumulador_programas .= $prog->nombre_programa . "<br>";
        }
        $row = str_replace("{PROGRAMAS_ASOCIADOS}", $acumulador_programas, $row);
        $row = str_replace("{PROGRAMA_BBDD_ENCODEADO}", Encodear3($programas[0]->id_programa), $row);
        $row = str_replace("{VALOR_HORA}", colocarPesos($unico->valor_hora), $row);
        if ($unico->imagen && file_exists("../front/img/logos_cursos/" . $unico->imagen)) {
            $row = str_replace("{IMAGEN}", "<img src='../front/img/logos_cursos/" . $unico->imagen . "' width='50' >", $row);
        } else {
            $row = str_replace("{IMAGEN}", "", $row);
        }
		$numero_horas = (float)$unico->numero_horas; // Convertir a número flotante
		$valor_hora = (float)$unico->valor_hora;  // Convertir a número flotante
		
		if (is_numeric($numero_horas) && is_numeric($valor_hora)) {
			$resultado = $numero_horas * $valor_hora;
			$row = str_replace("{VALOR_CURSO}", colocarPesos($resultado), $row);
			} else {
				$row = str_replace("{VALOR_CURSO}", "", $row);
			}
        //$row = str_replace("{VALOR_CURSO}", colocarPesos($unico->numero_horas * $unico->valor_hora), $row);
		$row = str_replace("{VALOR_HORA_SENCE}", colocarPesos($unico->valor_hora_sence), $row);
        $row = str_replace("{NUM_HORAS}", $unico->numero_horas, $row);        
        $row = str_replace("{EJECUTIVO}", ($unico->ejecutivocapacitacion), $row);
        $row = str_replace("{PARTICIPANTE}", ($unico->participantes), $row);
        $row = str_replace("{FECHAI}", ($unico->fecha_inicio), $row);
        $row = str_replace("{FECHAT}", ($unico->fecha_finalizacion), $row);
        $total_html .= $row;
    }

    if($excel==1){
        echo "Excel"; exit();
    }

    $PRINCIPAL = str_replace("{ROW_LISTADO}", ($total_html), $PRINCIPAL);
    $PRINCIPAL = str_replace("{ROW_LISTADO_xls}", ($total_html), $PRINCIPAL);
    return ($PRINCIPAL);
}
function outputCSV($id_inscripcion_id_malla) {
    // Establecer encabezados HTTP para la descarga del archivo CSV
    header('Content-Description: File Transfer');
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename=Participantes_ID_Imparticion_GrupoCursos_' . $id_inscripcion_id_malla . '.csv');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Expires: 0');

    // Limpiar el buffer de salida
    if (ob_get_level()) {
        ob_end_clean();
    }

    // Abrir la salida estándar como un archivo para escribir el CSV
    $output = fopen('php://output', 'w');

    // Escribir la cabecera del CSV
    fputcsv($output, ['rut']);

    // Obtener los datos a escribir en el CSV
    $total_usuarios_ = DescargaRut_IdMalla_IdInscripcion2022($id_inscripcion_id_malla);

    // Escribir los datos en el CSV
    foreach ($total_usuarios_ as $unico) {
        fputcsv($output, [$unico->rut]);
    }

    // Cerrar el archivo de salida
    fclose($output);

    // Finalizar el script para asegurar que no se agregue contenido adicional
    exit();
}


function VerificaArregloSQLInjectionV2($arreglo)
{
    $total_arreglo = count($arreglo);

    foreach ($arreglo as $clave => $valor) {

        if (strtoupper(trim($valor)) == "") {
            continue;
        }

        $valor = str_replace("'", "", $valor);
        $valor = str_replace('"', "", $valor);
        $valor = str_replace("+'", "", $valor);
        $valor = str_replace('--', "", $valor);
        $valor = str_replace('--', "", $valor);
        $valor = str_replace('--', "", $valor);
        $valor = str_replace('&', "", $valor);
        $valor = str_replace('.php', "", $valor);
        $valor = str_replace('.js', "", $valor);
        $valor = str_replace('.vbs', "", $valor);
        $valor = str_replace('%', "", $valor);
        $valor = str_replace('&', "", $valor);
        $valor = str_replace('&amp;', "", $valor);
        $valor = str_replace('&#38;', "", $valor);
        $valor = str_replace('<', "", $valor);
        $valor = str_replace('&lt;', "", $valor);
        $valor = str_replace('&#60;', "", $valor);
        $valor = str_replace('>', "", $valor);
        $valor = str_replace('&gt;', "", $valor);
        $valor = str_replace('&#62;', "", $valor);
        $valor = str_replace('&quot;', "", $valor);
        $valor = str_replace('&#34;', "", $valor);
        $valor = str_replace('&#39;', "", $valor);
        $valor = str_replace('script', "", $valor);
        $valor = str_replace('select', "", $valor);
        $valor = str_replace('tables', "", $valor);
        $valor = str_replace('union', "", $valor);
        $valor = str_replace('information_schema', "", $valor);
        //$valor = str_replace('', "", $valor);
        $valor = str_replace('delete', "", $valor);
        $valor = str_replace('update', "", $valor);
        $valor = str_replace('show', "", $valor);
        $valor = str_replace('|', "", $valor);
        $arreglo[$clave]= $valor;
    }
    return ($arreglo);
}
function VerificaArregloSQLInjectionV3($valor)
{


    $valor = str_replace("'", "", $valor);
    $valor = str_replace('"', "", $valor);
    $valor = str_replace("+'", "", $valor);
    $valor = str_replace('--', "", $valor);
    $valor = str_replace('--', "", $valor);
    $valor = str_replace('--', "", $valor);
    $valor = str_replace('&', "", $valor);
    $valor = str_replace('.php', "", $valor);
    $valor = str_replace('.js', "", $valor);
    $valor = str_replace('.vbs', "", $valor);
    $valor = str_replace('%', "", $valor);
    $valor = str_replace('&', "", $valor);
    $valor = str_replace('&amp;', "", $valor);
    $valor = str_replace('&#38;', "", $valor);
    $valor = str_replace('<', "", $valor);
    $valor = str_replace('&lt;', "", $valor);
    $valor = str_replace('&#60;', "", $valor);
    $valor = str_replace('>', "", $valor);
    $valor = str_replace('&gt;', "", $valor);
    $valor = str_replace('&#62;', "", $valor);
    $valor = str_replace('&quot;', "", $valor);
    $valor = str_replace('&#34;', "", $valor);
    $valor = str_replace('&#39;', "", $valor);
    $valor = str_replace('script', "", $valor);
    $valor = str_replace('select', "", $valor);
    $valor = str_replace('tables', "", $valor);
    $valor = str_replace('union', "", $valor);
    $valor = str_replace('information_schema', "", $valor);
    //$valor = str_replace('', "", $valor);
    $valor = str_replace('delete', "", $valor);
    $valor = str_replace('update', "", $valor);
    $valor = str_replace('show', "", $valor);
    $valor = str_replace('|', "", $valor);

    return ($valor);
}
function VerificaArregloSQLInjectionDecodear($arreglo)
{
    $total_arreglo = is_array($arreglo) ? count($arreglo) : 0;

    /*
    if($_SESSION["user_"]=="12345"){
        echo "<br>FN VerificaArregloSQLInjectionDecodear<br>";
        print_r($arreglo);
        echo "<br>total_arreglo $total_arreglo";
        echo "<h1>Arreglo <br>$arreglo</h1>";
    }
    */

    $arreglo = str_replace('"', "", $arreglo);
    $arreglo = str_replace("+'", "", $arreglo);
    $arreglo = str_replace('--', "", $arreglo);
    $arreglo = str_replace('--', "", $arreglo);
    $arreglo = str_replace('--', "", $arreglo);
    $arreglo = str_replace('&', "", $arreglo);
    $arreglo = str_replace('.php', "", $arreglo);
    $arreglo = str_replace('.js', "", $arreglo);
    $arreglo = str_replace('.vbs', "", $arreglo);
    $arreglo = str_replace('%', "", $arreglo);
    $arreglo = str_replace('&', "", $arreglo);
    $arreglo = str_replace('&amp;', "", $arreglo);
    $arreglo = str_replace('&#38;', "", $arreglo);

    $arreglo = str_replace('&lt;', "", $arreglo);
    $arreglo = str_replace('&#60;', "", $arreglo);

    $arreglo = str_replace('&gt;', "", $arreglo);
    $arreglo = str_replace('&#62;', "", $arreglo);
    $arreglo = str_replace('&quot;', "", $arreglo);
    $arreglo = str_replace('&#34;', "", $arreglo);
    $arreglo = str_replace('&#39;', "", $arreglo);
    $arreglo = str_replace('select', "", $arreglo);
    $arreglo = str_replace('tables', "", $arreglo);
    $arreglo = str_replace('union', "", $arreglo);

    $arreglo = str_replace('information_schema', "", $arreglo);

    //$arreglo = str_replace('from', "", $arreglo);
    $arreglo = str_replace('delete', "", $arreglo);
    $arreglo = str_replace('update', "", $arreglo);
    $arreglo = str_replace('show', "", $arreglo);
    $arreglo = str_replace('|', "", $arreglo);
    $arreglo = str_replace('user()', "", $arreglo);

    /*
    if($_SESSION["user_"]=="12345"){
        echo "<br>FN VerificaArregloSQLInjectionDecodear<br>";
        print_r($arreglo);
        echo "<br>total_arreglo $total_arreglo";
        echo "<h1>Arreglo <br>$arreglo</h1>";
    }
    */



    return ($arreglo);
}
function VerificaArregloSQLInjectionLight($arreglo)
{
    $total_arreglo = count($arreglo);

    foreach ($arreglo as $clave => $valor) {

        if (strtoupper(trim($valor)) == "") {
            continue;
        }
        $valor = str_replace('--', "", $valor);
        $valor = str_replace('.php', "", $valor);
        $valor = str_replace('.js', "", $valor);
        $valor = str_replace('.vbs', "", $valor);
        $valor = str_replace('&#38;', "", $valor);
        $valor = str_replace('&lt;', "", $valor);
        $valor = str_replace('&#60;', "", $valor);
        $valor = str_replace('&gt;', "", $valor);
        $valor = str_replace('&#62;', "", $valor);
        $valor = str_replace('&quot;', "", $valor);
        $valor = str_replace('&#34;', "", $valor);
        $valor = str_replace('&#39;', "", $valor);
        $valor = str_replace('select', "", $valor);
        $valor = str_replace('delete', "", $valor);
        $valor = str_replace('update', "", $valor);
        $valor = str_replace('show', "", $valor);
        $valor = str_replace('|', "", $valor);
        $arreglo[$clave]= $valor;
    }
    return ($arreglo);
}


function my_simple_crypt_decodear($string)
{
    // you may change these values to your own
    $secret_key     = getenv('SECRET_KEY1');
    $secret_iv      = getenv('SECRET_IV1');
    $output         = false;
    $encrypt_method = "AES-256-CBC";
    $key            = hash('sha256', $secret_key);
    $iv             = substr(hash('sha256', $secret_iv), 0, 16);
    $output         = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    return $output;
}
function my_simple_crypt_encodear($string)
{
    // you may change these values to your own
    $secret_key     = getenv('SECRET_KEY1');
    $secret_iv      = getenv('SECRET_IV1');
    $output         = false;
    $encrypt_method = "AES-256-CBC";
    $key            = hash('sha256', $secret_key);
    $iv             = substr(hash('sha256', $secret_iv), 0, 16);
    $output         = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
    return $output;
}
function Encodear3($valor)
{
    //$valor=Encodear(Encodear(Encodear($valor)));
    $valor = my_simple_crypt_encodear($valor, 'e');
    return ($valor);
}
function my_simple_crypt_decodear4($string)
{
    // you may change these values to your own
    $secret_key     = getenv('SECRET_KEY4');
    $secret_iv      = getenv('SECRET_IV4');
    $output         = false;
    $encrypt_method = "AES-256-CBC";
    $key            = hash('sha256', $secret_key);
    $iv             = substr(hash('sha256', $secret_iv), 0, 16);
    $output         = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    return $output;
}
function my_simple_crypt_encodear4($string)
{
    // you may change these values to your own
    $secret_key     = getenv('SECRET_KEY4');
    $secret_iv      = getenv('SECRET_IV4');
    $output         = false;
    $encrypt_method = "AES-256-CBC";
    $key            = hash('sha256', $secret_key);
    $iv             = substr(hash('sha256', $secret_iv), 0, 16);
    $output         = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
    return $output;
}
function Encodear4($valor)
{
    //$valor=Encodear(Encodear(Encodear($valor)));
    $valor = my_simple_crypt_encodear4($valor, 'e');
    return ($valor);
}
function Decodear4($valor)
{
    //$valor=Decodear(Decodear(Decodear($valor)));
    //print_r($valor);
    $valor = my_simple_crypt_decodear4($valor, 'd');
    $valor      = VerificaArregloSQLInjectionDecodear($valor);

    return ($valor);
}
function Decodear3($valor)
{
    //$valor=Decodear(Decodear(Decodear($valor)));
    //print_r($valor);
    $valor = my_simple_crypt_decodear($valor, 'd');
    $valor      = VerificaArregloSQLInjectionDecodear($valor);
    return ($valor);
}
function Decodear5($valor)
{
    //$valor=Decodear(Decodear(Decodear($valor)));
    //print_r($valor);
    $valor = my_simple_crypt_decodear5($valor, 'd');
    //echo $valor;

    if($_SESSION["user_"]=="12345"){
        //	echo "valor $valor";

    }

    /* if($_SESSION["user_"]=="12345"){
         echo "valor $valor";

         echo "<br>Encodear5";

         $encodear5_var=Encodear5("xx' union all select
 1,2,3,4,5,6,7,user(),9,0,1,2,3,4,5,6,7,8,9,0,1,2,3,4,5,6,7,8,9,0,1,2,3,4,5,6,7,8,9,0,1,2,3,4,5,6,7,8,9,0,1,2,3,4,5,6,
 7,8,9,0,1,2,3,4,5,6,7,8,9,0,1,2,3,4,5,6,7,8,9,0,1,2,3,4,5,6, 7,8,9,0, 1,2,3,4,5,6,7,8,9,0,1,2,3,4,5,6,7,8,9,0,1,2,3,4,5
 ,6,7,8,9 from information_schema.tables limit 1 -- -");
 echo "<br><br>$encodear5_var<br>";
 echo "<br>valor<br> $valor<br>";

     }*/


    $valor      = VerificaArregloSQLInjectionDecodear($valor);
    if($_SESSION["user_"]=="12345"){
        // echo "<br>valor Luego de Verifica <br> $valor<br>";

    }
    return ($valor);
}
function Decodear6($valor)
{
    //$valor=Decodear(Decodear(Decodear($valor)));
    //print_r($valor);
    $valor = my_simple_crypt_decodear6($valor, 'd');
    $valor      = VerificaArregloSQLInjectionDecodear($valor);

    return ($valor);
}
function my_simple_crypt_decodear5($string)
{
    // you may change these values to your own
    $secret_key     = getenv('SECRET_KEY5');
    $secret_iv      = getenv('SECRET_IV5');
    $output         = false;
    $encrypt_method = "AES-256-CBC";
    $key            = hash('sha256', $secret_key);
    $iv             = substr(hash('sha256', $secret_iv), 0, 16);
    $output         = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    return $output;
}
function my_simple_crypt_encodear5($string)
{
    // you may change these values to your own
    $secret_key     = getenv('SECRET_KEY5');
    $secret_iv      = getenv('SECRET_IV5');
    $output         = false;
    $encrypt_method = "AES-256-CBC";
    $key            = hash('sha256', $secret_key);
    $iv             = substr(hash('sha256', $secret_iv), 0, 16);
    $output         = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
    return $output;
}
function Encodear5($valor)
{
    //$valor=Encodear(Encodear(Encodear($valor)));
    $valor = my_simple_crypt_encodear5($valor, 'e');
    return ($valor);
}
//VERSION 6
function my_simple_crypt_decodear6($string)
{
    // you may change these values to your own
    $secret_key     = getenv('SECRET_KEY6');
    $secret_iv      = getenv('SECRET_IV6');
    $output         = false;
    $encrypt_method = "AES-256-CBC";
    $key            = hash('sha256', $secret_key);
    $iv             = substr(hash('sha256', $secret_iv), 0, 16);
    $output         = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    return $output;
}
function my_simple_crypt_encodear6($string)
{
    // you may change these values to your own
    $secret_key     = getenv('SECRET_KEY6');
    $secret_iv      = getenv('SECRET_IV6');
    $output         = false;
    $encrypt_method = "AES-256-CBC";
    $key            = hash('sha256', $secret_key);
    $iv             = substr(hash('sha256', $secret_iv), 0, 16);
    $output         = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
    return $output;
}
function Encodear6($valor)
{
    //$valor=Encodear(Encodear(Encodear($valor)));
    $valor = my_simple_crypt_encodear6($valor, 'e');
    return ($valor);
}
//VERSION 7
function my_simple_crypt_decodear7($string)
{
    // you may change these values to your own
    $secret_key     = getenv('SECRET_KEY7');
    $secret_iv      = getenv('SECRET_IV7');
    $output         = false;
    $encrypt_method = "AES-256-CBC";
    $key            = hash('sha256', $secret_key);
    $iv             = substr(hash('sha256', $secret_iv), 0, 16);
    $output         = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    return $output;
}
function my_simple_crypt_encodear7($string)
{
    // you may change these values to your own
    $secret_key     = getenv('SECRET_KEY7');
    $secret_iv      = getenv('SECRET_IV7');
    $output         = false;
    $encrypt_method = "AES-256-CBC";
    $key            = hash('sha256', $secret_key);
    $iv             = substr(hash('sha256', $secret_iv), 0, 16);
    $output         = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
    return $output;
}
function Encodear7($valor)
{
    //$valor=Encodear(Encodear(Encodear($valor)));
    $valor = my_simple_crypt_encodear7($valor, 'e');
    return ($valor);
}
function Decodear7($valor)
{
    //$valor=Decodear(Decodear(Decodear($valor)));
    //print_r($valor);
    $valor = my_simple_crypt_decodear7($valor, 'd');
    $valor      = VerificaArregloSQLInjectionDecodear($valor);
    return ($valor);
}
//VERSION 8
function my_simple_crypt_decodear8($string)
{
    // you may change these values to your own
    $secret_key     = getenv('SECRET_KEY8');
    $secret_iv      = getenv('SECRET_IV8');
    $output         = false;
    $encrypt_method = "AES-256-CBC";
    $key            = hash('sha256', $secret_key);
    $iv             = substr(hash('sha256', $secret_iv), 0, 16);
    $output         = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    return $output;
}
function my_simple_crypt_encodear8($string)
{
    // you may change these values to your own
    $secret_key     = getenv('SECRET_KEY8');
    $secret_iv      = getenv('SECRET_IV8');
    $output         = false;
    $encrypt_method = "AES-256-CBC";
    $key            = hash('sha256', $secret_key);
    $iv             = substr(hash('sha256', $secret_iv), 0, 16);
    $output         = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
    return $output;
}
function Encodear8($valor)
{
    //$valor=Encodear(Encodear(Encodear($valor)));
    $valor = my_simple_crypt_encodear8($valor, 'e');
    return ($valor);
}
function Decodear8($valor)
{
    //$valor=Decodear(Decodear(Decodear($valor)));
    //print_r($valor);
    $valor = my_simple_crypt_decodear8($valor, 'd');
    //$valor      = VerificaArregloSQLInjectionDecodear($valor);
    return ($valor);
}
function Encodear($valor)
{

    // $valor = my_simple_crypt_decodear($valor, 'd');
    //  return ($valor);
}
function Decodear($valor)
{
    // $valor = my_simple_crypt_decodear($valor, 'd');
    //  return ($valor);
}
function my_simple_crypt_decodearEmail($string)
{
    // you may change these values to your own
    $secret_key     = getenv('SECRET_KEYEM');
    $secret_iv      = getenv('SECRET_IVEM');
    $minutos				= date("H:i");
    $output         = false;
    $encrypt_method = "AES-256-CBC";
    $key            = hash('sha256', $secret_key);
    $iv             = substr(hash('sha256', $secret_iv.$minutos), 0, 16);
    $output         = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    return $output;
}
function my_simple_crypt_encodearEmail($string)
{
    // you may change these values to your own
    $secret_key     = getenv('SECRET_KEYEM');
    $secret_iv      = getenv('SECRET_IVEM');
    $minutos				= date("H:i");
    $output         = false;
    $encrypt_method = "AES-256-CBC";
    $key            = hash('sha256', $secret_key);
    $iv             = substr(hash('sha256', $secret_iv.$minutos), 0, 16);
    $output         = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
    return $output;
}
function EncodearEmail($valor)
{
    //$valor=Encodear(Encodear(Encodear($valor)));
    $valor = my_simple_crypt_encodearEmail($valor, 'e');
    return ($valor);
}
function DecodearEmail($valor)
{
    //$valor=Decodear(Decodear(Decodear($valor)));
    //print_r($valor);
    $valor = my_simple_crypt_decodearEmail($valor, 'd');
    $valor      = VerificaArregloSQLInjectionDecodear($valor);

    return ($valor);
}
	function formatearFechaSmall($valor_fecha)
	{
		$d = date("d", strtotime($valor_fecha));
		$m = date("m", strtotime($valor_fecha));
		$a = date("Y", strtotime($valor_fecha));
		return ($d . "-" . $m . "-" . $a);
		//return($fecha2=date("d-m",strtotime($valor_fecha)));
	}
?>