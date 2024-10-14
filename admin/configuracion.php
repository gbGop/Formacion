<?php
$envFilePath = '../secrets_mc.env';
$envVariables = [];
$lines = file($envFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($lines as $line) {
    if ($line !== '' && strpos($line, '=') !== false && strpos($line, '#') !== 0) {
        list($key, $value) = explode('=', $line, 2);
        $key = strtoupper(str_replace(['.', '-'], '_', $key));
        if (preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $key)) {
            $envVariables[$key] = $value;
        } else {
        }
    }
}

foreach ($envVariables as $key => $value) {
    $value=addslashes($value);
    $value=htmlspecialchars($value);
    $key=htmlspecialchars($key);
    if (preg_match('/^[A-Z0-9_]{10,29}$/', $key)) {
        putenv("$key=$value");
    } else {
    }
}

require_once ("../_config_.php");
		$permite_subir_certificados = 1;
		
		$Num_Rows_Default = 1;
		$url_front="https://www.gcloud.cl";
		$url_front_admin="https://www.gcloud.cl";
		$from="notificaciones@gcloud.cl";
		$nombrefrom="Más Conectados BCH";
		$tipo="text/html";
		$titulo1="";
		$url=$url_front;
		$texto_url="Ir a Más Conectados BCH";
		$url_objetos = "https://www.ngd.cl/bch/objetos";
		$url_objetos_corta = "../front/objetos";
		$logo="https://www.masconectadosbch.cl/front/img/bch_logo_conectados_trans.png";
		$texto4="masconectadosbch.cl";
		$permite_subir_certificados="";
        $plataforma="BCH";
		$Texto_Pilar		=	"Eje";
		$Texto_Programa	=	"Proyecto";

		// display none hace que desaparezca opcion
		$Desaparece_Descripcion_CreacionEdicionCursos = "display:none!important;";
		$Desaparece_IDSap_ListaParticipantes = "NO_MUESTRA";
        $url_front_token_redirect="https://www.goptest.cl/bch_masco/front/?sw=LinkEnc_Redirect_enc&token";

?>