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

function FuncionesTransversales($html)
{
    $environment_sw = $_GET["sw"];
    $rut = $_SESSION["user_"];
    $rut_sesion = $_SESSION["user_"];
    $id_empresa = $_SESSION["id_empresa"];
    $html = str_replace("{FOOTER}", file_get_contents("views/footer.html"), $html);
    $html = str_replace("{HEAD}", file_get_contents("views/head.html"), $html);
    $html = str_replace("{HEAD_DINAMICO}", file_get_contents("views/head/" . $id_empresa . "_head.html"), $html);
    $html = str_replace("{FOOTER_DINAMICO}", file_get_contents("views/head/" . $id_empresa . "_footer.html"), $html);
    $html = str_replace("{RUT_ENCODEADO}", Encodear3($rut), $html);
    $html = str_replace("{MENU_DINAMICO_BOTON_RECONOCE}", "", $html);
    $html = str_replace("{BLOQUEFOOTERCONTENIDO}", "", $html);
    $html = str_replace("{MENU_INFERIOR}", "", $html);
    $hoy = date("Ymdhi");
    $rut = $_SESSION["user_"];
    $html = str_replace("{HEAD}", file_get_contents("views/head.html"), $html);
    $html = str_replace("{AHORA}", $hoy, $html);
    return ($html);
}

function Map_of_2020_revisor_fn($PRINCIPAL, $foto, $read)
{

    $rut = $_SESSION["user_"];
    $id_empresa = $_SESSION["id_empresa"];
    $imagen_ofic = "img/" . $foto;

    $PRINCIPAL = str_replace("{imagen_ofic}", $imagen_ofic, $PRINCIPAL);

    $Lista_Map_rut_Revisor_List = map_of_2020_revisor($rut);

    $close = "";
    foreach ($Lista_Map_rut_Revisor_List as $un) {

        if ($un->cerrada > 0) {
            $estado = " <center> <div class='alert alert-info'>Enviada a Revisor</div> </center>";
        } else {
            if ($un->id1 > 0) {
                $estado = " <center> <div class='alert alert-warning'>En Proceso</div> </center>";
            } else {
                $estado = " <center> <div class='alert alert-danger'>No Iniciada</div> </center>";
            }
        }


        if ($un->cerrada_revisor > 0 and $un->cerrada_revisor >= $un->total) {
            $estado = " <center> <div class='alert alert-success'>Validada por Revisor</div> </center>";

        }

        //echo "<br>Revisor ".$un->cerrada_revisor." de ".$un->total;

        $cuenta_lista_map++;
        $row_lista_mis_ofic_revisor .= "

				<div class='col-lg-4'>" . utf8_encode($un->nombre_oficina) . "</div>
				<div class='col-lg-4'>" . utf8_encode($un->nombre_completo) . "<br>
				<small>Fecha Asignaci&oacute;n: " . FechaCastellano($un->fecha_asignacion) . "</small>
				</div>
				<div class='col-lg-3'>" . $estado . "</div>
				<div class='col-lg-1' ><a href='?sw=map_of_2020_revisor&vm=1&foto=" . Encodear3($un->foto) . "' style='" . $display_read_delete . "' class='btn btn-info'><span style='color:#fff'>Ingresar</span></a></div>
				<div class='col-lg-12'><hr></div>

			";
        $close = $un->close;
    }


    $PRINCIPAL = str_replace("{NOMBRE_OFICINA}", utf8_encode($un->nombre_oficina), $PRINCIPAL);

    $PRINCIPAL = str_replace("{Lista_oficinas_asignadas_revision}", $row_lista_mis_ofic_revisor, $PRINCIPAL);

    $PRINCIPAL = str_replace("{titulo_lista_mis_impresoras}", $titulo_lista_mis_impresoras, $PRINCIPAL);
    $PRINCIPAL = str_replace("{row_lista_mis_impresoras}", $row_lista_mis_impresoras, $PRINCIPAL);
    $PRINCIPAL = str_replace("{boton_enviar_impresora}", $boton_enviar_impresora, $PRINCIPAL);
    $PRINCIPAL = str_replace("{alerta_enviada}", $alerta_enviada, $PRINCIPAL);


    return ($PRINCIPAL);
}

function Map_of_2020_validacion_fn($PRINCIPAL, $foto, $read)
{

    $rut = $_SESSION["user_"];
    $id_empresa = $_SESSION["id_empresa"];
    //$imagen_ofic		= "img/".$foto;

    $PRINCIPAL = str_replace("{imagen_ofic}", $imagen_ofic, $PRINCIPAL);

    $Lista_Map_rut_Revisor_List = map_of_2020_validacion($rut);
    //print_r($Lista_Map_rut_Revisor_List);

    $close = "";
    foreach ($Lista_Map_rut_Revisor_List as $un) {

        if ($un->validacion == "SI") {
            $estado = " <center> <div class='alert alert-success'>Validaci&oacute;n</div> </center>";
        } elseif ($un->validacion == "NO") {
            $estado = " <center> <div class='alert alert-danger'>No Validada</div> 
					<br>Comentario: " . $un->validacion_comentario . " </center>";
        } else {
            $estado = " <center> <div class='alert alert-info'>Pendiente de Validaci&oacute;n</div> </center>";
        }


        $cuenta_lista_map++;

        if ($un->fecha_validacion <> '') {
            $fecha_val = FechaCastellano($un->fecha_validacion);
        } else {
            $fecha_val = "";
        }
        $row_lista_mis_ofic_revisor .= "

				<div class='col-lg-4'>" . utf8_encode($un->nombre_oficina) . "</div>
				<div class='col-lg-4'>" . utf8_encode($un->nombre_completo) . "<br>
				<small> " . $fecha_val . " </small>
				</div>
				<div class='col-lg-3'>" . $estado . "</div>
				<div class='col-lg-1' ><a href='?sw=map_of_2020_validador&vm=1&foto=" . Encodear3($un->foto) . "' style='" . $display_read_delete . "' class='btn btn-info'><span style='color:#fff'>Ingresar</span></a></div>
				<div class='col-lg-12'><hr></div>

			";
        $close = $un->close;
    }


    $PRINCIPAL = str_replace("{NOMBRE_OFICINA}", utf8_encode($un->nombre_oficina), $PRINCIPAL);


    if ($un->validacion == "") {
        $boton_form = "
		     	<form action='?sw=map_of_2020_validador&vm=1&foto=" . ($_GET["foto"]) . "' method='post' name='validar' id='validar'>
			     	<br>
		     			<input type='radio' name='validacion' value='SI' 	style='margin-right: 15px;	margin-left: 15px;' required> SI, VALIDO  
		     			<input type='radio' name='validacion' value='NO'  style='margin-right: 15px; 	margin-left: 15px;' required> NO, NO VALIDO
		     		
		     		<div><br>Comentarios:</div>
		     			<textarea class='form form-control' name='comentario_validacion'></textarea>
		     		<div>
		     		<center><br>
		     			<input type='hidden' id='valida_check' name='valida_check' value='1'>
		     			<input type='submit' class='btn btn-info' value='Enviar'>
		     		</center>
		     		<br>
		     	</form>
	     	 ";

    } elseif ($un->validacion == "SI") {
        $boton_form = "<center><div class='alert alert-success'>Validada<br>" . $un->validacion_comentario . "</center>";
    } elseif ($un->validacion == "NO") {
        $boton_form = "<center><div class='alert alert-danger'>No Validada<br>" . $un->validacion_comentario . "</center>";
    }


    $PRINCIPAL = str_replace("{Lista_oficinas_asignadas_revision}", $row_lista_mis_ofic_revisor, $PRINCIPAL);

    $PRINCIPAL = str_replace("{titulo_lista_mis_impresoras}", $titulo_lista_mis_impresoras, $PRINCIPAL);
    $PRINCIPAL = str_replace("{row_lista_mis_impresoras}", $row_lista_mis_impresoras, $PRINCIPAL);
    $PRINCIPAL = str_replace("{boton_enviar_impresora}", $boton_enviar_impresora, $PRINCIPAL);
    $PRINCIPAL = str_replace("{alerta_enviada}", $alerta_enviada, $PRINCIPAL);

    $PRINCIPAL = str_replace("{BOTON_VALIDAR_FINAL_FORM_ALERTA}", $boton_form, $PRINCIPAL);


    $PRINCIPAL = str_replace("{imagen_ofic_validar}", "img/" . $un->foto_validacion, $PRINCIPAL);


    return ($PRINCIPAL);
}

function Map_of_2020_fn($PRINCIPAL, $foto, $read)
{


    $rut = $_SESSION["user_"];
    $id_empresa = $_SESSION["id_empresa"];
    $imagen_ofic = "img/" . $foto;
    $Ofic = Map_Ofic_Data_foto($foto);
    $PRINCIPAL = str_replace("{imagen_ofic}", $imagen_ofic, $PRINCIPAL);

    // list of checkbox
    for ($i = 1; $i <= 420; $i++) {

        if ($i > 0 and $i < 10) {
            $i_vista = "00" . $i;
        }
        if ($i >= 10 and $i < 100) {
            $i_vista = "0" . $i;
        }
        if ($i >= 100) {
            $i_vista = "" . $i;
        }

        $row_checkbox .= file_get_contents("views/map/" . $id_empresa . "_row_checkbox.html");
        $row_checkbox = str_replace("{_codigo_i}", $i, $row_checkbox);
        $row_checkbox = str_replace("{_codigo_i_vista}", $i_vista, $row_checkbox);
        $row_checkbox = str_replace("{foto}", $foto, $row_checkbox);

        $Resp = map_of_2020_by_rut($i, $foto, $rut);
        if ($read == "1" and $Resp[0]->numero_serie == "") {
            $display = " display:none; ";
            $display_read = "  ";
            $display_read_b = "  ";
            $background_color = " ";
        } elseif ($read == "" and $Resp[0]->numero_serie == "") {
            $display = " ";
            $display_read = " display:none;";
            $display_read_b = " ";
            $background_color = " ";
        } else {
            $display = " ";
            $display_read = " display:none; ";
            $display_read_b = " display:none; ";
            $background_color = "   background: rgba(255, 255, 255, 1);";
        }

        $row_checkbox = str_replace("{DISPLAY_NONE}", $display, $row_checkbox);
        $row_checkbox = str_replace("{DISPLAY_READ}", $display_read, $row_checkbox);
        $row_checkbox = str_replace("{DISPLAY_READ_B}", $display_read_b, $row_checkbox);
        $row_checkbox = str_replace("{BACKGROUNDCOLOR}", $background_color, $row_checkbox);
        $row_checkbox = str_replace("{NUMERO_SERIE}", $Resp[0]->numero_serie, $row_checkbox);
        $row_checkbox = str_replace("{TIPO_IMPRESORA}", $Resp[0]->tipo_impresora, $row_checkbox);
        $row_checkbox = str_replace("{TIPO_CONEXION}", $Resp[0]->tipo_conexion, $row_checkbox);
        $row_checkbox = str_replace("{CANTIDAD_BANDEJAS}", $Resp[0]->cantidad_bandejas, $row_checkbox);
        $row_checkbox = str_replace("{MUEBLE}", $Resp[0]->mueble, $row_checkbox);
        $row_checkbox = str_replace("{OBSERVACIONES}", utf8_encode($Resp[0]->observaciones), $row_checkbox);

        if ($Resp[0]->numero_serie <> "") {
            $checked = " checked ";
            $cuenta_checked++;
        } else {
            $checked = "  ";
        }
        $row_checkbox = str_replace("{CHECKED}", $checked, $row_checkbox);
    }

    if ($cuenta_checked > 0) {
        $titulo_lista_mis_impresoras = "

							<ol class='breadcrumb'>
						    <li class='breadcrumb-item'>
						    <a href='?sw=map_of_2020'><i class='fas fa-caret-right'></i> Mi lista de Impresoras </a></li>
						</ol>		
		
		";

        if ($read == "1") {
            $display_read_delete = " display:none; ";
        } else {
            $display_read_delete = "";
        }
        $Lista_Map_rut_List = map_of_2020_by_rut_list($foto, $rut);
        //print_r($Lista_Map_rut_List);
        $close = "";
        foreach ($Lista_Map_rut_List as $un) {
            $cuenta_lista_map++;
            $row_lista_mis_impresoras .= "
			<div class='row'>
				<div class='col-lg-1'>" . $cuenta_lista_map . "</div>
				<div class='col-lg-2'>ID " . $un->id_cuadricula . "</div>
				<div class='col-lg-8'>
					
				 N&uacute;mero de Serie: " . $un->numero_serie . ", Tipo de Impresora: " . $un->tipo_impresora . "
				 Tipo Conexi&oacute;n: " . $un->tipo_conexion . "<br>
				 Cantidad de Bandejas: " . $un->cantidad_bandejas . ", Mueble: " . $un->mueble . "
				 <br>
				 Observaciones: " . utf8_encode($un->observaciones) . "	
			
				</div>
				<div class='col-lg-1' ><a href='?sw=map_of_2020&vm=1&foto=" . Encodear3($foto) . "&del=1&id=" . Encodear3($un->id) . "' style='" . $display_read_delete . "'><span style='color:#999'><i class='fas fa-trash-alt'></i></span></a></div>
				<div class='col-lg-12'><hr></div>
			</div>
			";
            $close = $un->close;
        }

    }
    if ($cuenta_lista_map > 0) {
        $boton_enviar_impresora = "
	<center>
		<a href='?sw=map_of_2020&vm=1&foto=" . Encodear3($foto) . "&close=1' class='btn btn-info'>Enviar Encuesta y Registrar Datos</a>
	</center>";


    } else {
        $titulo_lista_mis_impresoras = "";
        $row_lista_mis_impresoras = "";

    }

    if ($close == "1") {
        $alerta_enviada = "<center><div class='alert alert-success'>Encuesta Enviada Correctamente</div></center><br><br>";
        $boton_enviar_impresora = "<center><div class='alert alert-success'>Encuesta Enviada Correctamente</div></center>";

    } else {
        $alerta_enviada = "";

    }


    $PRINCIPAL = str_replace("{NOMBRE_OFICINA}", utf8_encode($Ofic[0]->nombre_enc), $PRINCIPAL);

    $PRINCIPAL = str_replace("{ROW_CHECKBOX}", $row_checkbox, $PRINCIPAL);

    $PRINCIPAL = str_replace("{titulo_lista_mis_impresoras}", $titulo_lista_mis_impresoras, $PRINCIPAL);
    $PRINCIPAL = str_replace("{row_lista_mis_impresoras}", $row_lista_mis_impresoras, $PRINCIPAL);
    $PRINCIPAL = str_replace("{boton_enviar_impresora}", $boton_enviar_impresora, $PRINCIPAL);
    $PRINCIPAL = str_replace("{alerta_enviada}", $alerta_enviada, $PRINCIPAL);


    return ($PRINCIPAL);
}

function Map_of_2020_recheck_revisor_fn($PRINCIPAL, $foto, $read)
{


    $rut = $_SESSION["user_"];
    $id_empresa = $_SESSION["id_empresa"];
    $imagen_ofic = "img/" . $foto;
    $Ofic = Map_Ofic_Data_foto($foto);
    $PRINCIPAL = str_replace("{imagen_ofic}", $imagen_ofic, $PRINCIPAL);
    //print_r($Ofic);
    //echo "fotO $foto";
    $rut_col = map_of_2020_by_rut_list_limit1_revisor_foto_dadorut($foto);
    //echo $rut_col;
    // list of checkbox
    for ($i = 1; $i <= 420; $i++) {

        if ($i > 0 and $i < 10) {
            $i_vista = "00" . $i;
        }
        if ($i >= 10 and $i < 100) {
            $i_vista = "0" . $i;
        }
        if ($i >= 100) {
            $i_vista = "" . $i;
        }


        $Resp = map_of_2020_by_rut($i, $foto, $rut_col);


        if ($Resp[0]->close_revisor == "1") {
            $row_checkbox .= file_get_contents("views/map/" . $id_empresa . "_row_checkbox_revisor_read.html");

        } else {
            $row_checkbox .= file_get_contents("views/map/" . $id_empresa . "_row_checkbox_revisor.html");

        }


        $row_checkbox = str_replace("{_codigo_i}", $i, $row_checkbox);
        $row_checkbox = str_replace("{_codigo_i_vista}", $i_vista, $row_checkbox);
        $row_checkbox = str_replace("{foto}", $foto, $row_checkbox);

        $Resp = map_of_2020_by_rut($i, $foto, $rut_col);


        if ($Resp[0]->accion == "Reemplazar") {
            $COLOR_ID = "#eab106";
        }

        if ($Resp[0]->accion == "Retirar") {
            $COLOR_ID = "#F44336";
        }

        if ($Resp[0]->accion == "Instalar") {
            $COLOR_ID = "#4CAF50";
        }


        if ($read == "1" and $Resp[0]->numero_serie == "") {
            $display = " display:none; ";
            $display_read = "  ";
            $display_read_b = "  ";
            $background_color = " ";
            $readonlye = "";
            $mantener = "";
            $modificar_id = "";
            $retirar = "";
            $instalar = "";
            $requerimiento = "";
            $form_superior = "";
            $form_inferior = "";
            $requerimiento_comentario = "";
        } elseif ($read == "" and $Resp[0]->numero_serie == "") {
            $display = " ";
            $display_read = " display:none;";
            $display_read_b = " ";
            $background_color = " ";
            $readonlye = "";


            $form_superior = "
        							<br>
        							<div class='alert alert-info'>
        								<form action='?sw=map_of_2020_revisor_save&vm=1&foto=UDBQdDVTVFcvYTlSaFE0Zm02RVJjK2t5ZURWK2hKemxMRm9Ya2ROQ3BTTT0=' method='post'>
        										<strong>Selecciona una Acci&oacute;n:</strong><br>
        								";

            $mantener = "";
            $modificar_id = "";
            $retirar = "";
            $instalar = "
        		<br>
        			
        		
        		<input type='radio' name='accion' value='Instalar' 	checked	style='transform: scale(1);'> Instalar
        		<input type='hidden' name='insertar' value='1'";

            $requerimiento_comentario = "<br><strong>Requerimiento:</strong><br><textarea name='requerimiento' class='form form-control'></textarea>";
            $form_inferior = "<br>
        		<center><input type='submit' value='Guardar y Validar' class='btn btn-info'></center>
        </div>";
        } else {
            $display = " ";
            $display_read = " display:none; ";
            $display_read_b = " display:none; ";
            $background_color = "   background: rgba(255, 255, 255, 1);";
            $readonlye = "onclick='return false;'";

            $form_superior = "
        							<br>
        							<div class='alert alert-info'>
        								<form action='?sw=map_of_2020_revisor_save&vm=1&foto=UDBQdDVTVFcvYTlSaFE0Zm02RVJjK2t5ZURWK2hKemxMRm9Ya2ROQ3BTTT0=' method='post'>
        										<strong>Selecciona una Acci&oacute;n:</strong><br>
        								";

            $mantener = "<input type='radio' name='accion' value='Mantener' 		style='transform: scale(1);' 	required	> Mantener";
            $modificar_id = "<input type='radio' name='accion' value='Reemplazar' 	style='transform: scale(1); padding-left:20px;' required> Reemplazar";
            $retirar = "<input type='radio' name='accion' value='Retirar' 		style='transform: scale(1);padding-left:20px;' required> Retirar";
            $instalar = "";
            $requerimiento_comentario = "<br><strong>Requerimiento:</strong><br><textarea name='requerimiento' class='form form-control'></textarea>";

            $form_inferior = "<br>
        		<center><input type='submit' value='Guardar y Validar' class='btn btn-info'></center>
        </div>";

        }

        if ($Resp[0]->close_revisor == "1") {
            $form_superior = "<br><div class='alert alert-info'>
				Acci&oacute;n: " . utf8_encode($Resp[0]->accion);
            $form_inferior = "</div>";
            $requerimiento_comentario = "Requerimiento: " . utf8_encode($Resp[0]->requerimiento);
            $mantener = "";
            $retirar = "";
            $instalar = "";
            $modificar_id = "";
        }

        $row_checkbox = str_replace("{COLOR_ID}", $COLOR_ID, $row_checkbox);


        $row_checkbox = str_replace("{MANTENER}", $mantener, $row_checkbox);
        $row_checkbox = str_replace("{MODIFICAR_ID}", $modificar_id, $row_checkbox);
        $row_checkbox = str_replace("{RETIRAR}", $retirar, $row_checkbox);
        $row_checkbox = str_replace("{INSTALAR}", $instalar, $row_checkbox);
        $row_checkbox = str_replace("{REQUERIMIENTO_COMENTARIO}", $requerimiento_comentario, $row_checkbox);

        $row_checkbox = str_replace("{FORM_superior}", $form_superior, $row_checkbox);
        $row_checkbox = str_replace("{FORM_inferior}", $form_inferior, $row_checkbox);


        $row_checkbox = str_replace("{DISPLAY_NONE}", $display, $row_checkbox);
        $row_checkbox = str_replace("{DISPLAY_READ}", $display_read, $row_checkbox);
        $row_checkbox = str_replace("{DISPLAY_READ_B}", $display_read_b, $row_checkbox);
        $row_checkbox = str_replace("{BACKGROUNDCOLOR}", $background_color, $row_checkbox);
        $row_checkbox = str_replace("{READONLY}", $readonlye, $row_checkbox);

        $row_checkbox = str_replace("{NUMERO_SERIE}", $Resp[0]->numero_serie, $row_checkbox);
        $row_checkbox = str_replace("{TIPO_IMPRESORA}", $Resp[0]->tipo_impresora, $row_checkbox);
        $row_checkbox = str_replace("{TIPO_CONEXION}", $Resp[0]->tipo_conexion, $row_checkbox);
        $row_checkbox = str_replace("{CANTIDAD_BANDEJAS}", $Resp[0]->cantidad_bandejas, $row_checkbox);
        $row_checkbox = str_replace("{MUEBLE}", $Resp[0]->mueble, $row_checkbox);
        $row_checkbox = str_replace("{OBSERVACIONES}", utf8_encode($Resp[0]->observaciones), $row_checkbox);

        if ($Resp[0]->numero_serie <> "") {
            $checked = " checked ";
            $cuenta_checked++;
        } else {
            $checked = "  ";
        }
        $row_checkbox = str_replace("{CHECKED}", $checked, $row_checkbox);
    }

    //echo "<br>cuenta $cuenta_checked<br>";


    if ($cuenta_checked > 0) {

        $titulo_lista_mis_impresoras = "

							<ol class='breadcrumb'>
						    <li class='breadcrumb-item'>
						    <a href='?sw=map_of_2020'><i class='fas fa-caret-right'></i> Lista de Impresoras </a></li>
						</ol>		
		
		";

        if ($read == "1") {
            $display_read_delete = " display:none; ";
        } else {
            $display_read_delete = "";
        }
        $Lista_Map_rut_List = map_of_2020_by_rut_list($foto, $rut_col);
        //print_r($Lista_Map_rut_List);
        $close = "";
        foreach ($Lista_Map_rut_List as $un) {
            $cuenta_lista_map++;


            if ($un->accion == "Reemplazar") {
                $COLOR_ID = "#eab106";
            }

            if ($un->accion == "Retirar") {
                $COLOR_ID = "#F44336";
            }

            if ($un->accion == "Instalar") {
                $COLOR_ID = "#4CAF50";
            }


            if ($un->accion == "Instalar") {
                $row_lista_mis_impresoras .= "
			<div class='row'>
				<div class='col-lg-1'>" . $cuenta_lista_map . "</div>
				<div class='col-lg-2'>ID " . $un->id_cuadricula . "</div>
				<div class='col-lg-8'>
				Acci&oacute;n: <strong><span style='color: " . $COLOR_ID . ";'>" . utf8_encode($un->accion) . "</span></strong> <br>
				Requerimiento: <strong>" . utf8_encode($un->requerimiento) . "</strong> 	<br>
				<br>
				 N&uacute;mero de Serie: " . $un->numero_serie . ", Tipo de Impresora: " . $un->tipo_impresora . "
				 Tipo Conexi&oacute;n: " . $un->tipo_conexion . "<br>
				 Cantidad de Bandejas: " . $un->cantidad_bandejas . ", Mueble: " . $un->mueble . "
				 <br>
				 Observaciones: " . utf8_encode($un->observaciones) . "	
				</div>
				
				
				<div class='col-lg-1' >
				
				<a href='?sw=map_of_2020_revisor&vm=1&foto=" . Encodear3($foto) . "&del=1&id=" . Encodear3($un->id) . "' style='" . $display_read_delete . "'><span style='color:#999'><i class='fas fa-trash-alt'></i></span></a>
				
				</div>
				<div class='col-lg-12'><hr></div>
			</div>
			";
            } elseif ($un->accion == "Reemplazar" or $un->accion == "Retirar") {


                $row_lista_mis_impresoras .= "
			<div class='row'>
				<div class='col-lg-1'>" . $cuenta_lista_map . "</div>
				<div class='col-lg-2'>ID " . $un->id_cuadricula . "</div>
				<div class='col-lg-8'>
				Acci&oacute;n: <strong><span style='color: " . $COLOR_ID . ";'> " . utf8_encode($un->accion) . " </span></strong> <br>
				Requerimiento: <strong>" . utf8_encode($un->requerimiento) . "</strong> 	<br>
				<br>
				 N&uacute;mero de Serie: " . $un->numero_serie . ", Tipo de Impresora: " . $un->tipo_impresora . "
				 Tipo Conexi&oacute;n: " . $un->tipo_conexion . "<br>
				 Cantidad de Bandejas: " . $un->cantidad_bandejas . ", Mueble: " . $un->mueble . "
				 <br>
				 Observaciones: " . utf8_encode($un->observaciones) . "	
				</div>
				
				
				<div class='col-lg-1' >
				
				<a href='?sw=map_of_2020_revisor&vm=1&foto=" . Encodear3($foto) . "&edit_revisor=1&id=" . Encodear3($un->id) . "' style='" . $display_read_delete . "'><span style='color:#999'><i class='fas fa-trash-alt'></i></span></a>
				
				</div>
				<div class='col-lg-12'><hr></div>
			</div>
			";
            } else {
                $row_lista_mis_impresoras .= "
			<div class='row'>
				<div class='col-lg-1'>" . $cuenta_lista_map . "</div>
				<div class='col-lg-2'>ID " . $un->id_cuadricula . "</div>
				<div class='col-lg-8'>
				<div class='alert alert-warning'>Pendiente</div> 
				
				 N&uacute;mero de Serie: " . $un->numero_serie . ", Tipo de Impresora: " . $un->tipo_impresora . "
				 Tipo Conexi&oacute;n: " . $un->tipo_conexion . "<br>
				 Cantidad de Bandejas: " . $un->cantidad_bandejas . ", Mueble: " . $un->mueble . "
				 <br>
				 Observaciones: " . utf8_encode($un->observaciones) . "	
				</div>
				
				
				<div class='col-lg-1' >
	

				
				
				</div>
				<div class='col-lg-12'><hr></div>
			</div>
			";
            }


            $close = $un->close;
        }

    }


    /*
    if($cuenta_lista_map>0)		{



        $boton_enviar_impresora="
        <center>
            <a href='?sw=map_of_2020&vm=1&foto=".Encodear3($foto)."&close=1' class='btn btn-info'>Enviar Encuesta y Registrar Datos</a>
        </center>";


    } else {
        $titulo_lista_mis_impresoras="";
        $row_lista_mis_impresoras="";

    }*/

    if ($close == "1") {
        $alerta_enviada = "<center><div class='alert alert-success'>Encuesta Enviada Correctamente</div></center><br><br>";
        $boton_enviar_impresora = "<center><div class='alert alert-success'>Encuesta Enviada Correctamente</div></center>";

    } else {
        $alerta_enviada = "";

    }


    $PRINCIPAL = str_replace("{NOMBRE_OFICINA_revisor}", utf8_encode($Ofic[0]->nombre_enc), $PRINCIPAL);

    $PRINCIPAL = str_replace("{ROW_CHECKBOX}", $row_checkbox, $PRINCIPAL);

    $PRINCIPAL = str_replace("{titulo_lista_mis_impresoras_revisor}", $titulo_lista_mis_impresoras, $PRINCIPAL);
    $PRINCIPAL = str_replace("{row_lista_mis_impresoras_revisor}", $row_lista_mis_impresoras, $PRINCIPAL);
    $PRINCIPAL = str_replace("{boton_enviar_impresora}", $boton_enviar_impresora, $PRINCIPAL);
    $PRINCIPAL = str_replace("{alerta_enviada}", $alerta_enviada, $PRINCIPAL);


    return ($PRINCIPAL);
}

function FuncionesTransversalesLogin($html)
{


    return ($html);
}

function Header_MC_2020_Perfil($PRINCIPAL, $rut_col)
{

    $row_menu_equipo_2020 .= file_get_contents("views/ev_agile/78_menu_ev_ag_header.html");
    $PRINCIPAL = str_replace("{HEADER_DINAMICO_MC_2020}", $row_menu_equipo_2020, $PRINCIPAL);
    $PRINCIPAL = str_replace("{FOOTER_DINAMICO_MC_2020}", "", $PRINCIPAL);

    return ($PRINCIPAL);

}


function Bch_2020_Perfil_Usuario($rut)
{

    //$id_empresa       = $_SESSION["id_empresa"];
    //echo "a";
    $esjefe = rutesjefe_2020_v2($rut);
    //echo "b";
    //print_r($esjefe);


    if ($esjefe[0]->cuenta > 0) {
        $Jefe = "Jefe";
    } else {
        $Jefe = "No Jefe";
    }
//echo "<br>Jefe $Jefe";
//Riesgo o vulnerable
//	No Trabajando / otro motivo	No Trabajando / Permiso extraordinario , mayores 65 a�os, enfermedad riesgo o caso social
//si el registro de la columna "rut" est� en la columna "rut_jefe", es Jefe. En caso contrario es No Jefe.


    $EsVulnerable = Bch_2020_Check_Contigencia_Vulnerable($rut);
    if ($EsVulnerable > 0) {
        $subperfil = "Vulnerable";
    } else {
        $arreglo_usu = Bch_2020_Check_Area_Depto($rut);

        //print_r($arreglo_usu);

        if (
            $arreglo_usu[0]->area == "Gerencia Sucursales Metropolitana" or
            $arreglo_usu[0]->area == "Gerencia Sucursales Regionales" or
            $arreglo_usu[0]->area == "Gerencia Red Edwards y Bca.Privada" or

            $arreglo_usu[0]->area == "Gerencia Banca Privada" or
            $arreglo_usu[0]->area == "Gerencia Sucursales BCH" or
            $arreglo_usu[0]->area == "Red Sucursales Consumo"
        ) {
            $subperfil = "Red";
        } elseif (
            $arreglo_usu[0]->departamento == "Subgcia. Banca Telefonica Pers."

        ) {
            $subperfil = "Contact Center";
        } else {
            $subperfil = "Resto Banco";
        }
    }

    if ($rut == "12345") {
        $subperfil = "Red";
        $Jefe = "Jefe";
    }

    if ($rut == "12888860") {
        $subperfil = "Red";
        $Jefe = "Jefe";
    }

    if ($rut == "13403288") {
        $subperfil = "Red";
        $Jefe = "No Jefe";
    }

    $perfil = "$subperfil $Jefe";//echo "<br>$perfil";

//echo "<br>rut $rut ";
    if ($rut == "10008") {
        $perfil = "Full";
    }


    if ($rut == "10009") {
        $perfil = "Semi_Full";
    }

//Red - Jefe
//Red - No Jefe

//CONTACT CENTER
//cod_depto: hoja contact_center
//6699	Subgcia. Banca Telefonica Pers.	4524	Fonobank Empresas

//Contact Center - Jefe
//Contact Center - No Jefe

// ELSE 
//Resto Banco - Jefe
//Resto Banco - No Jefe

    return $perfil;

}

function Bch_2020_Perfil_Usuario_Netflix($rut)
{


    $esjefe = rutesjefe_2020_v2($rut);


    if ($esjefe[0]->cuenta > 0) {
        $Jefe = "Jefe";
    } else {
        $Jefe = "NoJefe";
    }

    $perfil = $Jefe;

    return $perfil;

}