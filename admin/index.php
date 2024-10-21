<?php
	session_start();
	error_reporting(0);
	ini_set('max_execution_time', 400);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
	
	header('X-Frame-Options: SAMEORIGIN');
	header('X-XSS-Protection: 1; mode=block');
	
	if (session_status() !== PHP_SESSION_ACTIVE) {
		ini_set('session.cookie_httponly', 1);
		ini_set('session.use_only_cookies', 1);
		ini_set('session.cookie_secure', 1);
		ini_set('session.cookie_httponly', 1);
		ini_set('session.use_only_cookies', 1);
	}
	
	$seccion = $_GET["sw"];
	$seccion = preg_replace('/[\'" +%&<>]/', '', $seccion);
	$seccion = str_replace(['.php', '.js'], '', $seccion);
	
	if ($_SESSION["admin_"] == '' and $seccion != 'login' and $seccion != 'logout' and $seccion != 'checkUserCopec' and $seccion != 'prevCa' and $seccion != 'entrada' and $seccion != 'entradaCap') {
		session_start();
		$_SESSION = array();
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
		}
		unset($_COOKIE[session_name()]);
		echo "<script>location.href='" . getenv("HTTP_URL") . "/admin/?sw=login';</script>";
		exit();
	}
	
	require_once "includes/include.php";
	$arreglo_post = $_POST;
	$arreglo_get = $_GET;
	$arreglo_request = $_REQUEST;
	
	if ($seccion <> "lista_audiencias") {
		$post = VerificaArregloSQLInjectionV2($arreglo_post);
		$get = VerificaArregloSQLInjectionV2($arreglo_get);
		$request = VerificaArregloSQLInjectionV2($arreglo_request);
	}
	else {
		$post = $arreglo_post;
		$get = $arreglo_get;
		$request = $arreglo_request;
	}
	insertLogAdmin_2022($_SESSION["admin_"], $get["sw"], $post, $get, $request);
	
	$_SESSION['LAST_ACTIVITY'] = time();
	if (!$seccion) {
		session_start();
		$_SESSION = array();
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
		}
		// Finalmente, destruir la sesión.
		session_destroy();
		echo "<script>location.href='" . getenv("HTTP_URL") . "/admin/?sw=login';</script>";
		exit();
	}
	elseif ($seccion == "login") {
		$PRINCIPAL = file_get_contents("views/login.html");
		$PRINCIPAL = str_replace("{HEAD}", file_get_contents("views/head.html"), $PRINCIPAL);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "loginu") {
		$PRINCIPAL = file_get_contents("views/login_user.html");
		$PRINCIPAL = str_replace("{HEAD}", file_get_contents("views/head.html"), $PRINCIPAL);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "login_id") {
		session_start();
		// Destruir todas las variables de sesión.
		$_SESSION = array();
		session_destroy();
		$PRINCIPAL = file_get_contents("views/login_id.html");
		$PRINCIPAL = str_replace("{HEAD}", file_get_contents("views/head.html"), $PRINCIPAL);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "home_landing_admin") {
		
		$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/home_landing/entorno.html"));
		$id_empresa = $_SESSION["id_empresa"];
		$id_dimension = $get["id_dimension"];
		
		$Adm = DatosUsuarioAdmin($_SESSION["user_"]);
		
		$PRINCIPAL = str_replace("{NOMBRE_ADMIN}", $Adm[0]->nombre_completo, $PRINCIPAL);
		$PRINCIPAL = str_replace("{NOMBRE_ADMINISTRADOR}", ($Adm[0]->nombre_completo), $PRINCIPAL);
		$PRINCIPAL = str_replace("{PERFIL_ADMINISTRADOR}", ($Adm[0]->perfil), $PRINCIPAL);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "entrada") {
		$id_empresa_desde_login = Decodear3($post["iem"]);
		$rut = ($post["userid"]);
		$rut = $post["userid"];
		
		$total_intentos = 1;
		$rut = htmlentities(trim($rut));
		
		$clave = (($post["password"]));
		$clave = htmlentities(trim($clave));
		$word = trim($get["word"]);
		$tema = Decodear3(trim(($post["tema"])));
		$rutcontodo = str_replace(" ", "", $rut);
		$rut = str_replace(".", "", $rut);
		$rut = str_replace(" ", "", $rut);
		$arreglo_rut = explode("-", $rut);
		$rut = $arreglo_rut[0];
		
		$_SESSION["user_"] = $rut;
		$_SESSION["admin_"] = $rut;
		$_SESSION["id_empresa"] = "78";
		echo "<script>location.href='?sw=home_landing_admin';</script>";
		exit;
	}
	elseif ($seccion == "prevCa") {
		$PRINCIPAL = file_get_contents("views/login_captcha.html");
		$PRINCIPAL = str_replace("{HEAD}", file_get_contents("views/head.html"), $PRINCIPAL);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
		exit();
	}
	elseif ($seccion == "entradaCap") {
				$valor = $post["captcha"];
		
		//GOOGLE CAPTCHA
		class ReCaptchaResponse
		{
			public $success;
			public $errorCodes;
		}
		
		class ReCaptcha
		{
			private static $_signupUrl = "https://www.google.com/recaptcha/admin";
			private static $_siteVerifyUrl = "https://www.google.com/recaptcha/api/siteverify?";
			private $_secret;
			private static $_version = "php_1.0";
			
			/**
			 * Constructor.
			 *
			 * @param string $secret shared secret between site and ReCAPTCHA server.
			 */
			function ReCaptcha($secret)
			{
				if ($secret == null || $secret == "") {
					die("To use reCAPTCHA you must get an API key from <a href='" . self::$_signupUrl . "'>" . self::$_signupUrl . "</a>");
				}
				$this->_secret = $secret;
			}
			
			/**
			 * Encodes the given data into a query string format.
			 *
			 * @param array $data array of string elements to be encoded.
			 *
			 * @return string - encoded request.
			 */
			private function _encodeQS($data)
			{
				$req = "";
				foreach ($data as $key => $value) {
					$req .= $key . '=' . urlencode(stripslashes($value)) . '&';
				}
				// Cut the last '&'
				$req = substr($req, 0, strlen($req) - 1);
				return $req;
			}
			
			/**
			 * Submits an HTTP GET to a reCAPTCHA server.
			 *
			 * @param string $path url path to recaptcha server.
			 * @param array $data array of parameters to be sent.
			 *
			 * @return array response
			 */
			private function _submitHTTPGet($path, $data)
			{
				$req = $this->_encodeQS($data);
				$response = file_get_contents($path . $req);
				return $response;
			}
			
			/**
			 * Calls the reCAPTCHA siteverify API to verify whether the user passes
			 * CAPTCHA test.
			 *
			 * @param string $remoteIp IP address of end user.
			 * @param string $response response string from recaptcha verification.
			 *
			 * @return ReCaptchaResponse
			 */
			public function verifyResponse($remoteIp, $response)
			{
				// Discard empty solution submissions
				if ($response == null || strlen($response) == 0) {
					$recaptchaResponse = new ReCaptchaResponse();
					$recaptchaResponse->success = false;
					$recaptchaResponse->errorCodes = 'missing-input';
					return $recaptchaResponse;
				}
				$getResponse = $this->_submitHttpGet(self::$_siteVerifyUrl, array('secret' => $this->_secret, 'remoteip' => $remoteIp, 'v' => self::$_version, 'response' => $response));
				$answers = json_decode($getResponse, true);
				$recaptchaResponse = new ReCaptchaResponse();
				if (trim($answers ['success']) == true) {
					$recaptchaResponse->success = true;
				}
				else {
					$recaptchaResponse->success = false;
					$recaptchaResponse->errorCodes = $answers [error - codes];
				}
				return $recaptchaResponse;
			}
		}
		
		
		
		// Get a key from https://www.google.com/recaptcha/admin/create
		if ($post["g-recaptcha-response"]) {
			//ini_set('display_errors', 1);ini_set('display_startup_errors', 1);error_reporting(E_ALL);
			$secret = getenv('SECRET_CAPTCHA');
			$response = null;
			// comprueba la clave secreta
			$reCaptcha = new ReCaptcha($secret);
			$response = $reCaptcha->verifyResponse($_SERVER["REMOTE_ADDR"], $post["g-recaptcha-response"]);
			if ($response != null && $response->success) {
				// Si el c&oacute;digo es correcto, seguimos procesando el formulario como siempre
				
				$rut = Decodear3($_SESSION["toku"]);
				$clave = Decodear3($_SESSION["tokc"]);
				
				
				$existe_base = UsuarioAdminRutEmpresa($rut);
								if ($existe_base) {
					
					$total_intentos = 3;
					$verifica_clave = VerificaClaveAccesoAdmin($rut, $clave);
					
					
					if ($verifica_clave) {
						session_start();
						// INGRESO CORRECTO
						
						$_SESSION["user_"] = $rut;
						$_SESSION["admin_"] = $rut;
						$home_admin = $arrayEmpresa[0]->home_admin;
						
						
						$_SESSION["id_empresa"] = $existe_base[0]->id_empresa;
						$arrayEmpresa = BuscaEmpresaUserRut($rut);
						$home_admin = $arrayEmpresa[0]->home_admin;
						echo "
						                            <script>
						                                location.href='?sw=" . $home_admin . "';
						                            </script>";
						exit;
					}
					else {
						// CLAVE INCORRECTA
						session_start();
						echo " <script>alert('Ups, las credenciales no son correctas'); location.href='?sw=logout';    </script>";
						exit;
						echo "<script>location.href='" . getenv("HTTP_URL") . "/admin/?sw=login';</script>";
						exit;
					}
				}
				else {
					echo " <script>alert('Ups, las credenciales no son correctas'); location.href='?sw=logout';    </script>";
					exit;
				}
			}
		}
		else {
			echo " <script>alert('Ups, las credenciales no son correctas'); location.href='?sw=logout';    </script>";
			exit;
			
			echo "<script>location.href='" . getenv("HTTP_URL") . "/admin/?sw=login';</script>";
			exit;
		}
	} // CREACION DE CURSOS PRESENCIALES
	
	elseif ($seccion == "FB_2024_ajax_cuenta_contable_cui") {
		$id_programa = $get["selected_value"];
		$Prg = TraeProgramasBBDDById($id_programa);
		$cuenta = $Prg[0]->cuenta;
		$cuenta_glosa = $Prg[0]->cuenta_glosa;
		$cui = TraeCuiyCuentaContable($cuenta);
		
		// Create an associative array with the data
		$arreglo = array('cuenta' => $cuenta, 'cuenta_glosa' => $cuenta_glosa, 'cui' => $cui);
		
		// Encode the array to JSON format
		$json_response = json_encode($arreglo);
		
		// Set the appropriate content type header
		header('Content-Type: application/json');
		
		// Output the JSON response
		echo $json_response;
		exit();
	}
	elseif ($seccion == "FB_2024_ajax_otec") {
		
		$OtecS = Trae_otec_Search_Data($post['query']);
		
		$options_search_otec = "";
		foreach ($OtecS as $o) {
			$options_search_otec .= "<a href='#'>" . $o->nombre . " - " . LimpiaRut($o->rut) . " </a><br>";
		}
		echo $options_search_otec;
		exit();
	}
	elseif ($seccion == "accioncurso1") {
		
		$id_empresa = $_SESSION["id_empresa"];
		$id_curso = Decodear3($get["i"]);
		if ($id_curso) {
			$PRINCIPAL = FormularioCurso1(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/curso/formulario_edita1.html")), $id_curso, $_SESSION["id_empresa"]);
		}
		else {
			$PRINCIPAL = FormularioCurso1(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/curso/formulario_ingresa1.html")), $id_curso, $_SESSION["id_empresa"]);
		}
		global $Texto_Pilar, $Texto_Programa;
		$PRINCIPAL = str_replace("{Texto_Pilar}", ($Texto_Pilar), $PRINCIPAL);
		$PRINCIPAL = str_replace("{Texto_Programa}", ($Texto_Programa), $PRINCIPAL);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	
	elseif ($seccion == "listmallas1") {
		$id_empresa = $_SESSION["id_empresa"];
		$exportar_a_excel = $post["ex"];
		$pagina = $get["p"];
		$excel = $get["excel"];
		if ($exportar_a_excel == "1" or $excel == "1") {
			$arreglo_post = $post;
			$fechahoy = date("Y-m-d") . " " . date("H:i:s");
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=Cursos_" . $fechahoy . ".xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			$PRINCIPAL = ListadoMallasAdmin1(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/curso/entorno_listado_excel_mallas.html")), $id_empresa, $excel);
			global $Texto_Pilar, $Texto_Programa;
			$PRINCIPAL = str_replace("{Texto_Pilar}", ($Texto_Pilar), $PRINCIPAL);
			$PRINCIPAL = str_replace("{Texto_Programa}", ($Texto_Programa), $PRINCIPAL);
			
			echo($PRINCIPAL);
			exit;
		}
		else {
			$arreglo_post = $post;
			$PRINCIPAL = ListadoMallasAdmin1(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/curso/entorno_listado_cursos1_mallas.html")), $id_empresa, "", "", "");
			global $Texto_Pilar, $Texto_Programa;
			$PRINCIPAL = str_replace("{Texto_Pilar}", ($Texto_Pilar), $PRINCIPAL);
			$PRINCIPAL = str_replace("{Texto_Programa}", ($Texto_Programa), $PRINCIPAL);
			
			echo CleanHTMLWhiteList($PRINCIPAL);
			exit;
		}
	}
	elseif ($seccion == "listcursos1") {
		$id_empresa = $_SESSION["id_empresa"];
		$exportar_a_excel = $post["ex"];
		$pagina = $get["p"];
		$excel = $get["excel"];
		if ($exportar_a_excel == "1" or $excel == "1") {
			$arreglo_post = $post;
			$fechahoy = date("Y-m-d") . " " . date("H:i:s");
			// Set the HTTP headers
			header('Content-Description: File Transfer');
			header('Content-Type: text/csv'); // Use text/csv instead of application/csv
			header('Content-Disposition: attachment; filename="Cursos_Mod1_' . $fechahoy . '.csv"'); // Use double quotes for filename
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			
			echo "CODIGO_CURSO;NOMBRE_CURSO;EJE;PROYECTO;CUENTA;CUENTA_GLOSA;CUI;DIVISION;RUT_PROVEEDOR;HORAS_CURSO\r\n";
			$ListaDownload = Downloadlistcursos1_2024();
			
			foreach ($ListaDownload as $d) {
				echo $d->CODIGO_CURSO . ";" . $d->NOMBRE_CURSO . ";" . $d->EJE . ";" . $d->PROYECTO . ";" . $d->CUENTA . ";" . $d->CUENTA_GLOSA . ";" . $d->CUI . ";" . $d->DIVISION . ";" . $d->RUT_PROVEEDOR . ";" . $d->HORAS_CURSO . "\r\n";
			}
			exit;
		}
		else {
			$arreglo_post = $post;
			$PRINCIPAL = ListadoCursosAdmin1(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/curso/entorno_listado_cursos1.html")), $id_empresa, $excel, "", "");
			global $Texto_Pilar, $Texto_Programa;
			$PRINCIPAL = str_replace("{Texto_Pilar}", ($Texto_Pilar), $PRINCIPAL);
			$PRINCIPAL = str_replace("{Texto_Programa}", ($Texto_Programa), $PRINCIPAL);
			
			echo CleanHTMLWhiteList($PRINCIPAL);
			exit;
		}
	}
	elseif ($seccion == "adcurso1") {
		
		$id_empresa = $_SESSION["id_empresa"];
		$codigo_curso = (trim($post["codigo_curso"]));
		$nombre_curso = (trim($post["nombre_curso"]));
		$nombre_curso_sence = (trim($post["nombre_curso_sence"]));
		$descripcion_curso = (trim($post["descripcion_curso"]));
		$objetivo_curso = (trim($post["objetivo_curso"]));
		$modalidad = trim($post["modalidad"]);
		$ejecutivo = trim($post["ejecutivo"]);
		$sence = trim($post["sence"]);
		$foco = trim($post["foco"]);
		$foco2 = trim($post["foco2"]);
		$foco3 = trim($post["foco3"]);
		$tipo_actividad = trim($post["tipo_actividad"]);
		$necesidad = trim($post["necesidad"]);
		$proveedor = trim($post["proveedor"]);
		$ejecutivo = trim($post["ejecutivo"]);
		//presencial
		$programa_bbdd = trim($post["programa_bbdd"]);
		//elearning
		$programa_bbdd_global = trim($post["programa_bbdd_global"]);
		$programa_bbdd_elearning = trim($post["programa_bbdd_elearning"]);
		
		if ($modalidad == 1) {
			//$programa_bbdd_global=$programa_bbdd;
			// $programa_bbdd_elearning=$programa_bbdd;
			//InsertaRelacionMallaClasificacionCursoAdmin("presencial", $codigo_curso, "presencial", $id_empresa, $foco, $programa_bbdd);
		}
		
		$cbc = trim($post["cbc"]);
		$numero_horas = trim($post["numero_horas"]);
		$numero_horas = str_replace('.', ',', $numero_horas);
		$numero_horas = str_replace(';', ',', $numero_horas);
		$cantidad_maxima_participantes = trim($post["cantidad_maxima_participantes"]);
		$rut_otec = trim($post["rut_otec"]);
		$clasificacion_curso = trim($post["clasificacion_curso"]);
		$tipo_curso = (trim($post["tipo_curso"]));
		$prerequisito_curso = (trim($post["prerequisito_curso"]));
		$cod_sence = (trim($post["cod_sence"]));
		$cod_identificador = (trim($post["cod_identificador"]));
		$valor_hora = (trim($post["valor_hora"]));
		$valor_hora_sence = (trim($post["valor_hora_sence"]));
		$contenidos_cursos = (trim($post["contenidos_cursos"]));
		if ($cod_sence) {
			$numero_identificador = $cod_sence;
		}
		elseif ($cod_identificador) {
			$numero_identificador = $cod_identificador;
		}
		$codigo_sence = $post["cod_sence"];
		
		if ($post["tipo_imagen"] == "0") {
			$tamano = $_FILES["archivo"]['size'];
			$tipo = $_FILES["archivo"]['type'];
			$archivo = $_FILES["archivo"]['name'];
			$prefijo = substr(md5(uniqid(rand())), 0, 6);
			$arreglo_archivo = explode(".", $archivo);
			$extension_archivo = $arreglo_archivo[1];
			
			$ruta_logo_curso = "../front/img/";
			
			if ($archivo != "") {
				$datos_subida = SuboArchivo($_FILES, $extension_archivo, $prefijo, $ruta_logo_curso);
			}
		}
		else {
			$datos_subida[1] = "Img_cursos_default_" . $post["tipo_imagen"] . ".jpg";
		}
		
		InsertaCurso($nombre_curso, $descripcion_curso, $modalidad, $tipo_curso, $prerequisito_curso, $datos_subida[1], $objetivo_curso, $sence, $numero_horas, $cantidad_maxima_participantes, $rut_otec, $clasificacion_curso, $cbc, $numero_identificador, $valor_hora, $valor_hora_sence, $codigo_curso, $id_empresa, $contenidos_cursos, $nombre_curso_sence, $foco, $programa_bbdd, $codigo_sence, $programa_bbdd_global, $programa_bbdd_elearning, $foco2, $foco3, $tipo_actividad, $necesidad, $proveedor, $ejecutivo);
		
		echo "<script>location.href='?sw=listcursos1';</script>";
		exit;
	}
	elseif ($seccion == "edcurso1") {
		
		$id_empresa = $_SESSION["id_empresa"];
		
		$id_curso = Decodear3($post["id"]);
		$nombre_curso = (trim($post["nombre_curso"]));
		$descripcion_curso = ($post["descripcion_curso"]);
		$modalidad = ($post["modalidad"]);
		$tipo_curso = ($post["tipo_curso"]);
		$prerequisito_curso = ($post["prerequisito_curso"]);
		$nombre_curso_sence = (trim($post["nombre_curso_sence"]));
		$foco = trim($post["foco"]);
		$foco2 = trim($post["foco2"]);
		$foco3 = trim($post["foco3"]);
		$tipo_actividad = trim($post["tipo_actividad"]);
		$necesidad = trim($post["necesidad"]);
		$proveedor = trim($post["proveedor"]);
		$programa_bbdd_global = trim($post["programa_bbdd_global"]);
		$programa_bbdd = trim($post["programa_bbdd"]);
		$contenidos_cursos = (trim($post["contenidos_cursos"]));
		$objetivo_curso = (trim($post["objetivo_curso"]));
		$sence = trim($post["sence"]);
		$codigo_sence = (trim($post["codigo_sence"]));
		$ejecutivo = trim($post["ejecutivo"]);
		$sence = trim($post["sence"]);
		$cbc = trim($post["cbc"]);
		$numero_horas = trim($post["numero_horas"]);
		$cantidad_maxima_participantes = trim($post["cantidad_maxima_participantes"]);
		$rut_otec = trim($post["rut_otec"]);
		$clasificacion_curso = trim($post["clasificacion_curso"]);
		$valor_hora = trim($post["valor_hora"]);
		$valor_hora_sence = $post["valor_hora_sence"];
		$nuevo_id_curso = $post["codigo_curso"];
		$prerequisito_curso = ($post["prerequisito_curso"]);
		//Actualizo PRograma
		if ($post["tipo_imagen"] == "0") {
			$tamano = $_FILES["archivo"]['size'];
			$tipo = $_FILES["archivo"]['type'];
			$archivo = $_FILES["archivo"]['name'];
			$prefijo = substr(md5(uniqid(rand())), 0, 6);
			$arreglo_archivo = explode(".", $archivo);
			$extension_archivo = $arreglo_archivo[1];
			
			//$ruta_logo_curso="../front/img/logos_cursos/";
			$ruta_logo_curso = "../front/img/";
			
			if ($archivo != "") {
				// guardamos el archivo a la carpeta files
				//$destino =  "../school/objetos/".$prefijo.".jpg";
				//copy($_FILES['archivo']['tmp_name'],$destino);
				$datos_subida = SuboArchivo($_FILES, $extension_archivo, $prefijo, $ruta_logo_curso);
				
				$nombre_imagen = $datos_subida[1];
			}
		}
		elseif ($post["tipo_imagen"] != "imagenoriginal") {
			$nombre_imagen = "Img_cursos_default_" . $post["tipo_imagen"] . ".jpg";
		}
		$bajada = $post["division_mandante"];
		ActualizaCurso($nuevo_id_curso, $nombre_curso, $descripcion_curso, $modalidad, $tipo_curso, $prerequisito_curso, $objetivo_curso, $sence, $numero_horas, $cantidad_maxima_participantes, $rut_otec, $submodalidad, $cbc, $valor_hora, $valor_hora_sence, $contenidos_cursos, $nombre_curso_sence, $foco, $programa_bbdd, $codigo_sence, $nombre_imagen, $nuevo_id_curso, $programa_bbdd_global, $foco2, $foco3, $tipo_actividad, $necesidad, $proveedor, $ejecutivo, $bajada);
		// UpdateInscripcionCursoElearning($id_curso, $ejecutivo, $id_empresa);
		// UpdateRelLmsMallaCursoElearning($id_curso, $programa_bbdd, $foco, $id_empresa);
		echo "    <script>        location.href='?sw=listcursos1';    </script>";
		exit;
		exit;
	}
	elseif ($seccion == "edcurso2") {
		
		$id_empresa = $_SESSION["id_empresa"];
		
		$id_curso = (Decodear3($post["id"]));
		$nombre_curso = (trim($post["nombre_curso"]));
		$descripcion_curso = ($post["descripcion_curso"]);
		$modalidad_arreglo = ($post["modalidad"]);
		
		$Mod = explode(";", $modalidad_arreglo);
		$modalidad = $Mod[0];
		$submodalidad = $Mod[1];
		
		$tipo_curso = ($post["tipo_curso"]);
		$prerequisito_curso = ($post["prerequisito_curso"]);
		
		$nombre_curso_sence = (trim($post["nombre_curso_sence"]));
		
		$foco = trim($post["foco"]);
		$foco2 = trim($post["foco2"]);
		
		$foco3 = trim($post["foco3"]);
		$tipo_actividad = trim($post["tipo_actividad"]);
		$necesidad = trim($post["necesidad"]);
		$proveedor = trim($post["proveedor"]);
		
		
		$programa_bbdd_global = trim($post["programa_bbdd_global"]);
		$programa_bbdd = trim($post["programa_bbdd"]);
		$contenidos_cursos = (trim($post["contenidos_cursos"]));
		
		$objetivo_curso = (trim($post["objetivo_curso"]));
		$sence = trim($post["sence"]);
		
		$codigo_sence = (trim($post["codigo_sence"]));
		$ejecutivo = trim($post["ejecutivo"]);
		$sence = trim($post["sence"]);
		
		$cbc = trim($post["cbc"]);
		$numero_horas = trim($post["numero_horas"]);
		$cantidad_maxima_participantes = trim($post["cantidad_maxima_participantes"]);
		$rut_otec = trim($post["rut_otec"]);
		$clasificacion_curso = trim($post["clasificacion_curso"]);
		$valor_hora = trim($post["valor_hora"]);
		$valor_hora_sence = $post["valor_hora_sence"];
		
		$nuevo_id_curso = $post["codigo_curso"];
		$prerequisito_curso = ($post["prerequisito_curso"]);
		//Actualizo PRograma
		if ($post["tipo_imagen"] == "0") {
			$tamano = $_FILES["archivo"]['size'];
			$tipo = $_FILES["archivo"]['type'];
			$archivo = $_FILES["archivo"]['name'];
			$prefijo = substr(md5(uniqid(rand())), 0, 6);
			$arreglo_archivo = explode(".", $archivo);
			$extension_archivo = $arreglo_archivo[1];
			
			//$ruta_logo_curso="../front/img/logos_cursos/";
			$ruta_logo_curso = "../front/img/";
			
			if ($archivo != "") {
				// guardamos el archivo a la carpeta files
				//$destino =  "../school/objetos/".$prefijo.".jpg";
				//copy($_FILES['archivo']['tmp_name'],$destino);
				$datos_subida = SuboArchivo($_FILES, $extension_archivo, $prefijo, $ruta_logo_curso);
				$nombre_imagen = $datos_subida[1];
			}
		}
		elseif ($post["tipo_imagen"] != "imagenoriginal") {
			$nombre_imagen = "Img_cursos_default_" . $post["tipo_imagen"] . ".jpg";
		}
		$bajada = $post["division_mandante"];
		
		ActualizaCurso($id_curso, $nombre_curso, $descripcion_curso, $modalidad, $tipo_curso, $prerequisito_curso, $objetivo_curso, $sence, $numero_horas, $cantidad_maxima_participantes, $rut_otec, $submodalidad, $cbc, $valor_hora, $valor_hora_sence, $contenidos_cursos, $nombre_curso_sence, $foco, $programa_bbdd, $codigo_sence, $nombre_imagen, $nuevo_id_curso, $programa_bbdd_global, $foco2, $foco3, $tipo_actividad, $necesidad, $proveedor, $ejecutivo, $bajada);
		
		
		ActualizaReportesLms_2022($id_curso, $foco, $programa_bbdd, $numero_horas);
		
		echo "
    <script>
        location.href='?sw=listcursos2';
    </script>";
		exit;
		
		
		exit;
	}
	elseif ($seccion == "listaInscripciones1") {
		$i = $get["i"];
		$id_curso = Decodear3($i);
		$excel = $get["excel"];
		$id_empresa = $_SESSION["id_empresa"];
		if ($excel == 1) {
			$fechahoy = date("Y-m-d");
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=Imparticiones_" . $fechahoy . ".xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			
			$encabezado = file_get_contents("views/capacitacion/imparticion/entorno_listado_excel2.html");
			echo $encabezado;
			ListaImparticiones(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/imparticion/entorno_listado.html")), $id_curso, $_SESSION["id_empresa"], $excel, "1");
			exit;
		}
		else {
			$PRINCIPAL = ListaImparticiones(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/imparticion/entorno_listado.html")), $id_curso, $_SESSION["id_empresa"], "", "1");
		}
		
		$PRINCIPAL = str_replace("{ID_MODALIDAD}", "1", $PRINCIPAL);
		
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "listaInscripcionesMallas1") {
		$i = $get["i"];
		$id_malla = Decodear3($i);
		$excel = $get["excel"];
		$id_empresa = $_SESSION["id_empresa"];
		if ($excel == 1) {
			$fechahoy = date("Y-m-d");
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=Imparticiones_" . $fechahoy . ".xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			
			$encabezado = file_get_contents("views/capacitacion/imparticion/entorno_listado_excel2.html");
			echo $encabezado;
			ListaImparticiones_malla(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/imparticion/entorno_listado_mallas.html")), $id_malla, $_SESSION["id_empresa"], $excel, "1");
			exit;
		}
		else {
			$PRINCIPAL = ListaImparticiones_malla(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/imparticion/entorno_listado_mallas.html")), $id_malla, $_SESSION["id_empresa"], "", "1");
		}
		
		$PRINCIPAL = str_replace("{ID_MODALIDAD}", "1", $PRINCIPAL);
		
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	
	
	elseif ($seccion == "accioncurso2") {
		
		$id_empresa = $_SESSION["id_empresa"];
		$id_curso = Decodear3($get["i"]);
		if ($id_curso) {
			$PRINCIPAL = FormularioCurso2(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/curso/formulario_edita.html")), $id_curso, $_SESSION["id_empresa"]);
			
			$Curso = VerificoCursoPorEmpresa($id_curso, $id_empresa);
			
			if ($Curso[0]->modalidad == "2") {
				if ($Curso[0]->clasificacion == "Charla") {
					$select_2_charla = " selected ";
				}
				if ($Curso[0]->clasificacion == "Seminario") {
					$select_2_seminario = " selected ";
				}
				if ($Curso[0]->clasificacion == "Streaming") {
					$select_2_streaming = " selected ";
				}
				if ($Curso[0]->clasificacion == "Taller") {
					$select_2_taller = " selected ";
				}
				if ($Curso[0]->clasificacion == "Webinar") {
					$select_2_webinar = " selected ";
				}
			}
			
			$PRINCIPAL = str_replace("{select_2_charla}", ($select_2_charla), $PRINCIPAL);
			$PRINCIPAL = str_replace("{select_2_seminario}", ($select_2_seminario), $PRINCIPAL);
			$PRINCIPAL = str_replace("{select_2_streaming}", ($select_2_streaming), $PRINCIPAL);
			$PRINCIPAL = str_replace("{select_2_taller}", ($select_2_taller), $PRINCIPAL);
			$PRINCIPAL = str_replace("{select_2_webinar}", ($select_2_webinar), $PRINCIPAL);
		}
		else {
			$PRINCIPAL = FormularioCurso2(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/curso/formulario_ingresa.html")), $id_curso, $_SESSION["id_empresa"]);
		}
		global $Texto_Pilar, $Texto_Programa, $Desaparece_Descripcion_CreacionEdicionCursos;
		$PRINCIPAL = str_replace("{Texto_Pilar}", ($Texto_Pilar), $PRINCIPAL);
		$PRINCIPAL = str_replace("{Texto_Programa}", ($Texto_Programa), $PRINCIPAL);
		$PRINCIPAL = str_replace("{Desaparece_Descripcion_CreacionEdicionCursos}", ($Desaparece_Descripcion_CreacionEdicionCursos), $PRINCIPAL);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	
	elseif ($seccion == "reporte_full_sincronico") {
		$id_empresa = $_SESSION["id_empresa"];
		$exportar_a_excel = $post["ex"];
		$pagina = $get["p"];
		$excel = $get["excel"];
		if ($exportar_a_excel == "1" or $excel == "1") {
			$fecha_inicio = $post["fecha_inicio"];
			$fecha_termino = $post["fecha_termino"];
			$fechahoy = date("Y-m-d");
			header('Content-Description: File Transfer');
			header('Content-Type: application/csv');
			header("Content-Disposition: attachment; filename=ReporteFullSincronico_Participantes_" . $fechahoy . ".csv");
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			echo "RUT;CARGO;COD_CARGO;CUI;MODALIDAD;TIPO_MODALIDAD;ID_CURSO;CURSO;ID_IMPARTICION;FECHA_INICIO_IMPARTICION;FECHA_TERMINO_IMPARTICION;OBSERVACION_COLABORADOR;TIPO_CURSO;LUGAR_IMPARTICION;HORA_INICIO;HORA_TERMINO;HORAS;ASISTENCIA;NOTA_EVALUACION_PRE;NOTA_EVALUACION_POST;ESTADO_COLABORADOR;EVALUACION_SATISFACCION;RECOMENDACION\r\n";
			
			if ($fecha_inicio <> "" and $fecha_termino <> "") {
				$reporte_full_cod = reporte_full_sincronico_full_data($fecha_inicio, $fecha_termino, $excel);
								foreach ($reporte_full_cod as $u) {
					echo "" . $u->rut . ";" . ($u->cargo) . ";" . $u->id_cargo . ";" . $u->id_unidad . ";" . $u->modalidad . ";" . ($u->tipo_modalidad) . ";" . $u->id_curso . ";" . ($u->curso) . ";" . $u->id_inscripcion . ";" . $u->fecha_inicio_inscripcion . ";" . $u->fecha_termino_inscripcion . ";" . ($u->observacion_colaborador) . ";" . $u->tipo_curso . ";" . ($u->lugar_imparticion) . ";" . $u->hora_inicio . ";" . $u->hora_termino . ";" . $u->numero_horas . ";" . $u->asistencia . ";" . $u->nota_evaluacion_pre . ";" . $u->nota_evaluacion_post . ";" . $u->estado . ";" . $u->evaluacion_satisfaccion . ";" . $u->recomendacion_curso . "\r\n";
				}
			}
			exit;
		}
		elseif ($exportar_a_excel == "2" or $excel == "2") {
			$fecha_inicio = $post["fecha_inicio"];
			$fecha_termino = $post["fecha_termino"];
			$fechahoy = date("Y-m-d");
			header('Content-Description: File Transfer');
			header('Content-Type: application/csv');
			header("Content-Disposition: attachment; filename=ReporteFullSincronico_Eventos_" . $fechahoy . ".csv");
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			echo "RUT_CONSULTOR_CAPACITACION;RUT_EJECUTIVO_EXTERNO;EJECUTIVO_EXTERNO;SENCE;NOMBRE_OTIC;EJE;PROYECTO;CODIGO_DIVISION_CREACION_CURSO;DIVISION_CREACION_CURSO;MODALIDAD;TIPO_MODALIDAD;ID_CURSO;CURSO;ID_IMPARTICION;IMPARTICION;FECHA_INICIO_IMPARTICION;FECHA_TERMINO_IMPARTICION;ESTADO_IMPARTICION;OBSERVACION_IMPARTICION;TIPO_CURSO;LUGAR_IMPARTICION;HORA_INICIO;HORA_TERMINO;HORAS;CRITERIO_APROBACION;ASISTENCIA_MINIMA;NOTA_MINIMA;NOTA_EVALUACION_PROMEDIO_POST;RUT_RELATOR;EVALUACION_SATISFACCION_PROMEDIO;PORCENTAJE_RECOMENDACION_PROMEDIO\r\n";
						if ($fecha_inicio <> "" and $fecha_termino <> "") {
				
				$reporte_full_cod = reporte_full_sincronico_full_data($fecha_inicio, $fecha_termino, $excel);
				
				foreach ($reporte_full_cod as $u) {
					$u->OBSERVACION_IMPARTICION = LimpiarCaracteres($u->OBSERVACION_IMPARTICION);
					//$u->OBSERVACION_IMPARTICION == str_replace("", ($Texto_Programa), $PRINCIPAL);
					echo "" . $u->RUT_CONSULTOR_CAPACITACION . ";" . ($u->RUT_EJECUTIVO_EXTERNO) . ";" . ($u->EJECUTIVO_EXTERNO) . ";" . $u->SENCE . ";" . ($u->NOMBRE_OTIC) . ";" . ($u->EJE) . ";" . ($u->PROYECTO) . ";" . $u->CODIGO_DIVISION_CREACION_CURSO . ";" . ($u->DIVISION_CREACION_CURSO) . ";" . ($u->MODALIDAD) . ";" . $u->TIPO_MODALIDAD . ";" . $u->ID_CURSO . ";" . ($u->CURSO) . ";" . $u->ID_IMPARTICION . ";" . ($u->IMPARTICION) . ";" . $u->FECHA_INICIO_IMPARTICION . ";" . $u->FECHA_TERMINO_IMPARTICION . ";" . $u->ESTADO_IMPARTICION . ";" . ($u->OBSERVACION_IMPARTICION) . ";" . $u->TIPO_CURSO . ";" . ($u->LUGAR_IMPARTICION) . ";" . $u->HORA_INICIO . ";" . $u->HORA_TERMINO . ";" . $u->HORAS . ";" . $u->CRITERIO_APROBACION . ";" . $u->ASISTENCIA_MINIMA . ";" . $u->NOTA_MINIMA . ";" . $u->NOTA_EVALUACION_PROMEDIO_POST . ";" . $u->RUT_RELATOR . ";" . $u->EVALUACION_SATISFACCION_PROMEDIO . ";" . $u->PORCENTAJE_RECOMENDACION_PROMEDIO . "\r\n";
				}
			}
			exit;
		}
		elseif ($exportar_a_excel == "3" or $excel == "3") {
			$id_encuesta = '90';
			$Header_Preguntas = Reportes_detalle_headers_Preguntas_respuestas($id_encuesta);
			
			
			foreach ($Header_Preguntas as $h) {
				$row_header_preguntas .= $h->pregunta . ";";
				if ($h->tipo == "PREGUNTAS_RELATORES") {
					$row_header_preguntas .= "Relator1;Respuesta;Relator2;Respuesta;Relator3;Respuesta;Relator4;Respuesta;Relator5;Respuesta;Relator6;Respuesta;";
				}
			}
			
			
			$row_header_preguntas = ($row_header_preguntas);
			header('Content-Description: File Transfer');
			header('Content-Type: application/csv');
			header("Content-Disposition: attachment; filename=Resultados_Encuesta_por_Participantes" . $codigo_inscripcion . ".csv");
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			echo "rut;nombre;cargo;fecha_ingreso;division;area;departamento;zona;seccion;unidad;region;empresa;rut_jefe;nombre_jefe;id_imparticion;nombre_imparticion;" . $row_header_preguntas . "fecha\r\n";
			
			$UsuariosRespuestas_ = QR_Detalle_Download_Usuarios_por_Imparticion_Data($id_encuesta, $id_imparticion);
			
			foreach ($UsuariosRespuestas_ as $u) {
				
				$row_respuestas_only = "";
				$resp_detalle = QR_Detalle_Download_Usuarios_por_Detalle_Imparticion_Data($id_encuesta, $u->OTA, $u->rut);
				
				foreach ($resp_detalle as $resp) {
					
					if ($resp->tipo == "PREGUNTAS_RELATORES") {
						$Rel_headers = QR_IdInscripcion_Relatores_List($u->OTA);
						$num_relator = 0;
						
						$rel_1 = "";
						$rel_R1 = "";
						$rel_2 = "";
						$rel_R2 = "";
						$rel_3 = "";
						$rel_R3 = "";
						$rel_4 = "";
						$rel_R4 = "";
						$rel_5 = "";
						$rel_R5 = "";
						$rel_6 = "";
						$rel_R6 = "";
						$respRelatores = explode(";", $resp->respuesta);
						
						foreach ($Rel_headers as $r) {
							if ($r->relator <> "") {
								$num_relator++;
								if ($num_relator == "1") {
									$rel_1 = $r->relator;
									$rel_R1 = $respRelatores[0];
								}
								if ($num_relator == "2") {
									$rel_2 = $r->relator;
									$rel_R2 = $respRelatores[1];
								}
								if ($num_relator == "3") {
									$rel_3 = $r->relator;
									$rel_R3 = $respRelatores[2];
								}
								if ($num_relator == "4") {
									$rel_4 = $r->relator;
									$rel_R4 = $respRelatores[3];
								}
								if ($num_relator == "5") {
									$rel_5 = $r->relator;
									$rel_R5 = $respRelatores[4];
								}
								if ($num_relator == "6") {
									$rel_6 = $r->relator;
									$rel_R6 = $respRelatores[5];
								}
							}
						}
						
						$row_respuestas_only .= ";$rel_1;$rel_R1;$rel_2;$rel_R2;$rel_3;$rel_R3;$rel_4;$rel_R4;$rel_5;$rel_R5;$rel_6;$rel_R6;";
					}
					else {
						$row_respuestas_only .= $resp->respuesta . ";";
					}
				}
				$row_respuestas_preguntas = $u->rut . ";" . $u->nombre_completo . ";" . $u->cargo . ";" . $u->fecha_ingreso . ";" . $u->division . ";" . $u->area . ";" . $u->departamento . ";" . $u->zona . ";" . $u->seccion . ";" . $u->unidad . ";" . $u->region . ";" . $u->empresa . ";" . $u->rut_jefe . ";" . $u->nombre_jefe . ";" . $u->OTA . ";" . $u->nombre_imparticion . ";" . $row_respuestas_only . "" . $u->fecha . "\r\n";
				//$u->respuesta = str_replace(";", ",", $u->respuesta);
				echo "" . ($row_respuestas_preguntas);
			}
			exit();
		}
		elseif ($exportar_a_excel == "4" or $excel == "4") {
			$id_encuesta = '90';
			$Header_Preguntas = Reportes_detalle_headers_Preguntas_respuestas($id_encuesta);
			
			foreach ($Header_Preguntas as $h) {
				$row_header_preguntas .= $h->pregunta . ";";
				if ($h->tipo == "PREGUNTAS_RELATORES") {
					// $row_header_preguntas.="Relator1;Respuesta;Relator2;Respuesta;Relator3;Respuesta;Relator4;Respuesta;Relator5;Respuesta;Relator6;Respuesta;";
				}
			}
			
			
			$row_header_preguntas = ($row_header_preguntas);
			header('Content-Description: File Transfer');
			header('Content-Type: application/csv');
			header("Content-Disposition: attachment; filename=Resultados_Encuesta_Satisfaccion_por_Eventos_" . $codigo_inscripcion . ".csv");
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			echo "OTA;Nombre_Evento;Nombre_Actividad;Lugar_Ejecucion;Fecha_Ejecucion;Relator_1;Relator_1_Promedio;Relator_2;Relator_2_Promedio;Relator_3;Relator_3_Promedio;Relator_4;Relator_4_Promedio;Relator_5;Relator_5_Promedio;Relator_6;Relator_6_Promedio;Relatores_Promedio;Curso_Cumplio_Expectivas_Promedio;Curso_contribuira_mejorar_desemp_laboral_Promedio;Nota_Curso_Promedio;PromedioTotalCurso;Recomendacion_Porcentaje;Promedio_Final;\r\n";
			
			$EventosRespuestas_ = QR_Detalle_Download_Eventos_por_Imparticion_Data($id_encuesta, $id_imparticion);
			
			foreach ($EventosRespuestas_ as $u) {
				$rel_1 = $rel_R1 = "";
				$rel_2 = $rel_R2 = "";
				$rel_3 = $rel_R3 = "";
				$rel_4 = $rel_R4 = "";
				$rel_5 = $rel_R5 = "";
				$rel_6 = $rel_R6 = "";
				
				$RRelFinal = array(); // Initialize the array for each iteration
				$row_respuestas_only = "";
				$resp_detalle = QR_Detalle_Download_Usuarios_por_Detalle_Imparticion_Eventos_Data($id_encuesta, $u->OTA);
				foreach ($resp_detalle as $respd) {
					$RD = explode(";", $respd->respuesta);
					$num_respe_relator = 0;
					foreach ($RD as $respdet) {
						$num_respe_relator++;
						if ($respdet <> "") {
							$RRelFinal[$num_respe_relator][] = $respdet;
						}
					}
				}
				
				// print_r($RRelFinal);
				$suma_averageRDR = 0;
				$cuenta_averageRDR = 0;
				for ($ird = 1; $ird <= 6; $ird++) {
					$averageRDR = "";
					
					if (isset($RRelFinal[$ird]) && is_array($RRelFinal[$ird])) {
						$sumRDR = array_sum($RRelFinal[$ird]);
						$countRDR = count($RRelFinal[$ird]);
						$averageRDR = $sumRDR / $countRDR;
						
					}
					else {
						// Handle the case where $RRelFinal[$ird] is null or not an array
					}
					
					if ($ird == 1) {
						$rel_R1 = $averageRDR;
						if ($averageRDR > 0) {
							
							$suma_averageRDR = $suma_averageRDR + $averageRDR;
							$cuenta_averageRDR++;
						}
					}
					if ($ird == 2) {
						$rel_R2 = $averageRDR;
						if ($averageRDR > 0) {
							
							$suma_averageRDR = $suma_averageRDR + $averageRDR;
							$cuenta_averageRDR++;
						}
					}
					if ($ird == 3) {
						$rel_R3 = $averageRDR;
						if ($averageRDR > 0) {
							
							$suma_averageRDR = $suma_averageRDR + $averageRDR;
							$cuenta_averageRDR++;
						}
					}
					if ($ird == 4) {
						$rel_R4 = $averageRDR;
						if ($averageRDR > 0) {
							
							$suma_averageRDR = $suma_averageRDR + $averageRDR;
							$cuenta_averageRDR++;
						}
					}
					if ($ird == 5) {
						$rel_R5 = $averageRDR;
						if ($averageRDR > 0) {
							
							$suma_averageRDR = $suma_averageRDR + $averageRDR;
							$cuenta_averageRDR++;
						}
					}
					if ($ird == 6) {
						$rel_R6 = $averageRDR;
						if ($averageRDR > 0) {
							
							$suma_averageRDR = $suma_averageRDR + $averageRDR;
							$cuenta_averageRDR++;
						}
					}
				}
				
				
				$Rel_headers = QR_IdInscripcion_Relatores_List($u->OTA);
				$num_relator = 0;
				$respRelatores = explode(";", $resp->respuesta);
				
				foreach ($Rel_headers as $r) {
					if ($r->relator <> "") {
						$num_relator++;
						if ($num_relator == "1") {
							$rel_1 = $r->relator;
						}
						if ($num_relator == "2") {
							$rel_2 = $r->relator;
						}
						if ($num_relator == "3") {
							$rel_3 = $r->relator;
						}
						if ($num_relator == "4") {
							$rel_4 = $r->relator;
						}
						if ($num_relator == "5") {
							$rel_5 = $r->relator;
						}
						if ($num_relator == "6") {
							$rel_6 = $r->relator;
						}
					}
				}
				if ($cuenta_averageRDR > 0) {
					$promedio_relatores = round($suma_averageRDR / $cuenta_averageRDR, 1);
				}
				$promedio_general = round(($u->promedio_preg_1 + $u->promedio_preg_2 + $u->promedio_preg_4) / 3, 1);
				$promedio_final = round(($promedio_general + $promedio_relatores) / 2, 1);
				$row_respuestas_preguntas = $u->OTA . ";" . $u->nombre_imparticion . ";" . $u->nombre_curso . ";" . $u->lugar_imparticion . ";" . $u->fecha_ejecucion . ";" . $rel_1 . ";" . $rel_R1 . ";" . $rel_2 . ";" . $rel_R2 . ";" . $rel_3 . ";" . $rel_R3 . ";" . $rel_4 . ";" . $rel_R4 . ";" . $rel_5 . ";" . $rel_R5 . ";" . $rel_6 . ";" . $rel_R6 . ";" . $promedio_relatores . ";" . $u->promedio_preg_1 . ";" . $u->promedio_preg_2 . ";" . $u->promedio_preg_4 . ";" . $promedio_general . ";" . $u->porcentaje_recomendacion . ";" . $promedio_final . " \r\n";
				//$u->respuesta = str_replace(";", ",", $u->respuesta);
				echo "" . ($row_respuestas_preguntas);
			}
			exit();
		}
		else {
			$arreglo_post = $post;
			$PRINCIPAL = ReporteFullSincronico_2023(FuncionesTransversalesAdmin(file_get_contents("views/curso/entorno_reportes_full_sincronico.html")), "", $id_empresa);
			global $Texto_Pilar, $Texto_Programa;
			$PRINCIPAL = str_replace("{Texto_Pilar}", ($Texto_Pilar), $PRINCIPAL);
			$PRINCIPAL = str_replace("{Texto_Programa}", ($Texto_Programa), $PRINCIPAL);
			echo CleanHTMLWhiteList($PRINCIPAL);
			exit;
		}
	}
	
	
	elseif ($seccion == "listcursos2") {
		$id_empresa = $_SESSION["id_empresa"];
		$exportar_a_excel = $post["ex"];
		$pagina = $get["p"];
		$excel = $get["excel"];
		if ($exportar_a_excel == "1" or $excel == "1") {
			$arreglo_post = $post;
			$fechahoy = date("Y-m-d") . " " . date("H:i:s");
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=Cursos_" . $fechahoy . ".xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			$PRINCIPAL = ListadoCursosAdmin2(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/curso/entorno_listado_excel.html")), $id_empresa, $excel, "", "");
			global $Texto_Pilar, $Texto_Programa;
			$PRINCIPAL = str_replace("{Texto_Pilar}", ($Texto_Pilar), $PRINCIPAL);
			$PRINCIPAL = str_replace("{Texto_Programa}", ($Texto_Programa), $PRINCIPAL);
			echo($PRINCIPAL);
			exit;
		}
		else {
			$arreglo_post = $post;
			$PRINCIPAL = ListadoCursosAdmin2(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/curso/entorno_listado_cursos2.html")), $id_empresa, "", "", "");
			global $Texto_Pilar, $Texto_Programa;
			$PRINCIPAL = str_replace("{Texto_Pilar}", ($Texto_Pilar), $PRINCIPAL);
			$PRINCIPAL = str_replace("{Texto_Programa}", ($Texto_Programa), $PRINCIPAL);
			echo CleanHTMLWhiteList($PRINCIPAL);
			exit;
		}
	}
	elseif ($seccion == "adcurso2") {
		
		$id_empresa = $_SESSION["id_empresa"];
		$codigo_curso = (trim($post["codigo_curso"]));
		$nombre_curso = (trim($post["nombre_curso"]));
		$nombre_curso_sence = (trim($post["nombre_curso_sence"]));
		$descripcion_curso = (trim($post["descripcion_curso"]));
		$objetivo_curso = (trim($post["objetivo_curso"]));
		$modalidad_arreglo = trim($post["modalidad"]);
		$ejecutivo = trim($post["ejecutivo"]);
		$sence = trim($post["sence"]);
		$foco = trim($post["foco"]);
		$foco2 = trim($post["foco2"]);
		$foco3 = trim($post["foco3"]);
		$tipo_actividad = trim($post["tipo_actividad"]);
		$necesidad = trim($post["necesidad"]);
		$proveedor = trim($post["proveedor"]);
		$ejecutivo = trim($post["ejecutivo"]);
		//presencial
		$programa_bbdd = trim($post["programa_bbdd"]);
		//elearning
		$programa_bbdd_global = trim($post["programa_bbdd_global"]);
		$programa_bbdd_elearning = trim($post["programa_bbdd_elearning"]);
		
		
		$Mod = explode(";", $modalidad_arreglo);
		$modalidad = $Mod[0];
		$submodalidad = $Mod[1];
		
		
		if ($modalidad == 2) {
			$programa_bbdd_global = $programa_bbdd;
			$programa_bbdd_elearning = $programa_bbdd;
			InsertaRelacionMallaClasificacionCursoAdmin("presencial", $codigo_curso, "presencial", $id_empresa, $foco, $programa_bbdd);
		}
		
		$cbc = trim($post["cbc"]);
		$numero_horas = trim($post["numero_horas"]);
		$numero_horas = str_replace('.', ',', $numero_horas);
		$numero_horas = str_replace(';', ',', $numero_horas);
		$cantidad_maxima_participantes = trim($post["cantidad_maxima_participantes"]);
		$rut_otec = trim($post["rut_otec"]);
		$clasificacion_curso = trim($post["clasificacion_curso"]);
		$tipo_curso = (trim($post["tipo_curso"]));
		$prerequisito_curso = (trim($post["prerequisito_curso"]));
		$cod_sence = (trim($post["cod_sence"]));
		$cod_identificador = (trim($post["cod_identificador"]));
		$valor_hora = (trim($post["valor_hora"]));
		$valor_hora_sence = (trim($post["valor_hora_sence"]));
		$contenidos_cursos = (trim($post["contenidos_cursos"]));
		if ($cod_sence) {
			$numero_identificador = $cod_sence;
		}
		elseif ($cod_identificador) {
			$numero_identificador = $cod_identificador;
		}
		$codigo_sence = $post["cod_sence"];
		
		if ($post["tipo_imagen"] == "0") {
			$tamano = $_FILES["archivo"]['size'];
			$tipo = $_FILES["archivo"]['type'];
			$archivo = $_FILES["archivo"]['name'];
			$prefijo = substr(md5(uniqid(rand())), 0, 6);
			$arreglo_archivo = explode(".", $archivo);
			$extension_archivo = $arreglo_archivo[1];
			
			$ruta_logo_curso = "../front/img/";
			
			if ($archivo != "") {
				$datos_subida = SuboArchivo($_FILES, $extension_archivo, $prefijo, $ruta_logo_curso);
			}
		}
		else {
			$datos_subida[1] = "Img_cursos_default_" . $post["tipo_imagen"] . ".jpg";
		}
		
		$clasificacion_curso = $submodalidad;
		$ejecutivo = trim($post["ejecutivo"]);
		$bajada = $post["division_mandante"];
		
		InsertaCurso($nombre_curso, $descripcion_curso, $modalidad, $tipo_curso, $prerequisito_curso, $datos_subida[1], $objetivo_curso, $sence, $numero_horas, $cantidad_maxima_participantes, $rut_otec, $clasificacion_curso, $cbc, $numero_identificador, $valor_hora, $valor_hora_sence, $codigo_curso, $id_empresa, $contenidos_cursos, $nombre_curso_sence, $foco, $programa_bbdd, $codigo_sence, $programa_bbdd_global, $programa_bbdd_elearning, $foco2, $foco3, $tipo_actividad, $necesidad, $proveedor, $ejecutivo, $bajada);
		
		echo "<script>location.href='?sw=listcursos2';</script>";
		exit;
	}
	elseif ($seccion == "listaInscripciones2") {
		$i = $get["i"];
		$id_curso = Decodear3($i);
		$excel = $get["excel"];
		$id_empresa = $_SESSION["id_empresa"];
		
		
		if ($get["cOTA"] <> "") {
			$c_ota = Decodear3($get["cOTA"]);
			UpdateCierreOta_2024($c_ota);
			echo "<script>alert('OTA cerrada exitosamente')</script>";
		}
		if ($get["aOTA"] <> "") {
			$a_ota = Decodear3($get["aOTA"]);
			UpdateAbreOta_2024($a_ota);
			echo "<script>alert('OTA abierta exitosamente')</script>";
		}
		
		
		if ($excel == 1) {
			ob_start();
			$fechahoy = date("Y-m-d");
			header('Content-Description: File Transfer');
			header('Content-Type: text/csv');
			header('Content-Disposition: attachment; filename=Lista_Imparticiones.csv');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			
			echo "id_curso;curso;modalidad;id_imparticion;estado_ejecucion;fecha_inicio;fecha_termino;ejecutivo;proveedor;datos;asistentes;inscritos\r\n";
			
			ListaImparticiones(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/imparticion/entorno_listado.html")), $id_curso, $_SESSION["id_empresa"], $excel, "2");
			exit;
		}
		else {
			$PRINCIPAL = ListaImparticiones(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/imparticion/entorno_listado.html")), $id_curso, $_SESSION["id_empresa"], "", "2");
		}
		$PRINCIPAL = str_replace("{ID_MODALIDAD}", "2", $PRINCIPAL);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "accioncurso3") {
		
		$id_empresa = $_SESSION["id_empresa"];
		$id_curso = Decodear3($get["i"]);
		
		if ($id_curso) {
			$PRINCIPAL = FormularioCurso3(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/curso/formulario_edita3.html")), $id_curso, $_SESSION["id_empresa"]);
		}
		else {
			$PRINCIPAL = FormularioCurso3(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/curso/formulario_ingresa3.html")), $id_curso, $_SESSION["id_empresa"]);
		}
		global $Texto_Pilar, $Texto_Programa;
		$PRINCIPAL = str_replace("{Texto_Pilar}", ($Texto_Pilar), $PRINCIPAL);
		$PRINCIPAL = str_replace("{Texto_Programa}", ($Texto_Programa), $PRINCIPAL);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "listcursos3") {
		$id_empresa = $_SESSION["id_empresa"];
		$exportar_a_excel = $post["ex"];
		$pagina = $get["p"];
		$excel = $get["excel"];
		if ($exportar_a_excel == "1" or $excel == "1") {
			$arreglo_post = $post;
			$fechahoy = date("Y-m-d") . " " . date("H:i:s");
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=Cursos_" . $fechahoy . ".xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			$PRINCIPAL = ListadoCursosAdmin3(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/curso/entorno_listado_excel.html")), $id_empresa, $excel);
			global $Texto_Pilar, $Texto_Programa;
			$PRINCIPAL = str_replace("{Texto_Pilar}", ($Texto_Pilar), $PRINCIPAL);
			$PRINCIPAL = str_replace("{Texto_Programa}", ($Texto_Programa), $PRINCIPAL);
			echo($PRINCIPAL);
			exit;
		}
		else {
			$arreglo_post = $post;
			$PRINCIPAL = ListadoCursosAdmin3(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/curso/entorno_listado_cursos3.html")), $id_empresa);
			global $Texto_Pilar, $Texto_Programa;
			$PRINCIPAL = str_replace("{Texto_Pilar}", ($Texto_Pilar), $PRINCIPAL);
			$PRINCIPAL = str_replace("{Texto_Programa}", ($Texto_Programa), $PRINCIPAL);
			echo CleanHTMLWhiteList($PRINCIPAL);
			exit;
		}
	}
	elseif ($seccion == "adcurso3") {
		
		$id_empresa = $_SESSION["id_empresa"];
		$codigo_curso = (trim($post["codigo_curso"]));
		$nombre_curso = (trim($post["nombre_curso"]));
		$nombre_curso_sence = (trim($post["nombre_curso_sence"]));
		$descripcion_curso = (trim($post["descripcion_curso"]));
		$objetivo_curso = (trim($post["objetivo_curso"]));
		$modalidad = trim($post["modalidad"]);
		$ejecutivo = trim($post["ejecutivo"]);
		$sence = trim($post["sence"]);
		$foco = trim($post["foco"]);
		$foco2 = trim($post["foco2"]);
		$foco3 = trim($post["foco3"]);
		$tipo_actividad = trim($post["tipo_actividad"]);
		$necesidad = trim($post["necesidad"]);
		$proveedor = trim($post["proveedor"]);
		$ejecutivo = trim($post["ejecutivo"]);
		$programa_bbdd = trim($post["programa_bbdd"]);
		$programa_bbdd_global = trim($post["programa_bbdd_global"]);
		
		
		if ($modalidad == 3) {
			$programa_bbdd_global = $programa_bbdd;
			$programa_bbdd_elearning = $programa_bbdd;
			InsertaRelacionMallaClasificacionCursoAdmin("autoinscripcion", $codigo_curso, "autoinscripcion", $id_empresa, $foco, $programa_bbdd);
		}
		
		$cbc = trim($post["cbc"]);
		$numero_horas = trim($post["numero_horas"]);
		$numero_horas = str_replace('.', ',', $numero_horas);
		$numero_horas = str_replace(';', ',', $numero_horas);
		
		$cantidad_maxima_participantes = trim($post["cantidad_maxima_participantes"]);
		$rut_otec = trim($post["rut_otec"]);
		$clasificacion_curso = trim($post["clasificacion_curso"]);
		$tipo_curso = (trim($post["tipo_curso"]));
		$prerequisito_curso = (trim($post["prerequisito_curso"]));
		$cod_sence = (trim($post["cod_sence"]));
		$cod_identificador = (trim($post["cod_identificador"]));
		$valor_hora = (trim($post["valor_hora"]));
		$valor_hora_sence = (trim($post["valor_hora_sence"]));
		$contenidos_cursos = (trim($post["contenidos_cursos"]));
		if ($cod_sence) {
			$numero_identificador = $cod_sence;
		}
		elseif ($cod_identificador) {
			$numero_identificador = $cod_identificador;
		}
		$codigo_sence = $post["cod_sence"];
		
		if ($post["tipo_imagen"] == "0") {
			$tamano = $_FILES["archivo"]['size'];
			$tipo = $_FILES["archivo"]['type'];
			$archivo = $_FILES["archivo"]['name'];
			$prefijo = substr(md5(uniqid(rand())), 0, 6);
			$arreglo_archivo = explode(".", $archivo);
			$extension_archivo = $arreglo_archivo[1];
			$ruta_logo_curso = "../front/img/";
			if ($archivo != "") {
				// guardamos el archivo a la carpeta files
				//$destino =  "../school/objetos/".$prefijo.".jpg";
				//copy($_FILES['archivo']['tmp_name'],$destino);
				$datos_subida = SuboArchivo($_FILES, $extension_archivo, $prefijo, $ruta_logo_curso);
			}
		}
		else {
			$datos_subida[1] = "Img_cursos_default_" . $post["tipo_imagen"] . ".jpg";
		}
		
		InsertaCurso($nombre_curso, $descripcion_curso, $modalidad, $tipo_curso, $prerequisito_curso, $datos_subida[1], $objetivo_curso, $sence, $numero_horas, $cantidad_maxima_participantes, $rut_otec, $clasificacion_curso, $cbc, $numero_identificador, $valor_hora, $valor_hora_sence, $codigo_curso, $id_empresa, $contenidos_cursos, $nombre_curso_sence, $foco, $programa_bbdd, $codigo_sence, $programa_bbdd_global, $programa_bbdd_elearning, $foco2, $foco3, $tipo_actividad, $necesidad, $proveedor, $ejecutivo);
		
		echo "<script>location.href='?sw=listcursos3'; </script>";
		exit;
	}
	elseif ($seccion == "edcurso3") {
		
		$id_empresa = $_SESSION["id_empresa"];
		
		$id_curso = Decodear3($post["id"]);
		$nombre_curso = (trim($post["nombre_curso"]));
		$descripcion_curso = ($post["descripcion_curso"]);
		$modalidad = ($post["modalidad"]);
		$tipo_curso = ($post["tipo_curso"]);
		$prerequisito_curso = ($post["prerequisito_curso"]);
		
		$nombre_curso_sence = (trim($post["nombre_curso_sence"]));
		
		$foco = trim($post["foco"]);
		$foco2 = trim($post["foco2"]);
		
		$foco3 = trim($post["foco3"]);
		$tipo_actividad = trim($post["tipo_actividad"]);
		$necesidad = trim($post["necesidad"]);
		$proveedor = trim($post["proveedor"]);
		
		
		$programa_bbdd_global = trim($post["programa_bbdd_global"]);
		$programa_bbdd = trim($post["programa_bbdd"]);
		$contenidos_cursos = (trim($post["contenidos_cursos"]));
		
		$objetivo_curso = (trim($post["objetivo_curso"]));
		$sence = trim($post["sence"]);
		
		$codigo_sence = (trim($post["codigo_sence"]));
		$ejecutivo = trim($post["ejecutivo"]);
		$sence = trim($post["sence"]);
		
		$cbc = trim($post["cbc"]);
		$numero_horas = trim($post["numero_horas"]);
		$cantidad_maxima_participantes = trim($post["cantidad_maxima_participantes"]);
		$rut_otec = trim($post["rut_otec"]);
		$clasificacion_curso = trim($post["clasificacion_curso"]);
		$valor_hora = trim($post["valor_hora"]);
		$valor_hora_sence = $post["valor_hora_sence"];
		
		$nuevo_id_curso = $post["codigo_curso"];
		$prerequisito_curso = ($post["prerequisito_curso"]);
		//Actualizo PRograma
		if ($post["tipo_imagen"] == "0") {
			$tamano = $_FILES["archivo"]['size'];
			$tipo = $_FILES["archivo"]['type'];
			$archivo = $_FILES["archivo"]['name'];
			$prefijo = substr(md5(uniqid(rand())), 0, 6);
			$arreglo_archivo = explode(".", $archivo);
			$extension_archivo = $arreglo_archivo[1];
			
			//$ruta_logo_curso="../front/img/logos_cursos/";
			$ruta_logo_curso = "../front/img/";
			
			if ($archivo != "") {
				// guardamos el archivo a la carpeta files
				//$destino =  "../school/objetos/".$prefijo.".jpg";
				//copy($_FILES['archivo']['tmp_name'],$destino);
				$datos_subida = SuboArchivo($_FILES, $extension_archivo, $prefijo, $ruta_logo_curso);
				
				$nombre_imagen = $datos_subida[1];
			}
		}
		elseif ($post["tipo_imagen"] != "imagenoriginal") {
			$nombre_imagen = "Img_cursos_default_" . $post["tipo_imagen"] . ".jpg";
		}
		
		ActualizaCurso($id_curso, $nombre_curso, $descripcion_curso, $modalidad, $tipo_curso, $prerequisito_curso, $objetivo_curso, $sence, $numero_horas, $cantidad_maxima_participantes, $rut_otec, $clasificacion_curso, $cbc, $valor_hora, $valor_hora_sence, $contenidos_cursos, $nombre_curso_sence, $foco, $programa_bbdd, $codigo_sence, $nombre_imagen, $nuevo_id_curso, $programa_bbdd_global, $foco2, $foco3, $tipo_actividad, $necesidad, $proveedor, $ejecutivo);
		
		
		echo "
    <script>
        location.href='?sw=listcursos3';
    </script>";
		exit;
		
		
		exit;
	}
	elseif ($seccion == "listaInscripciones3") {
		$i = $get["i"];
		$id_curso = Decodear3($i);
		$excel = $get["excel"];
		$id_empresa = $_SESSION["id_empresa"];
		
		if ($excel == 1) {
			$fechahoy = date("Y-m-d");
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=Imparticiones_" . $fechahoy . ".xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			
			$encabezado = file_get_contents("views/capacitacion/imparticion/entorno_listado_excel2.html");
			echo $encabezado;
			ListaImparticiones(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/imparticion/entorno_listado.html")), $id_curso, $_SESSION["id_empresa"], $excel, "3");
			exit;
		}
		else {
			$PRINCIPAL = ListaImparticiones(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/imparticion/entorno_listado.html")), $id_curso, $_SESSION["id_empresa"], "", "3");
		}
		$PRINCIPAL = str_replace("{ID_MODALIDAD}", "3", $PRINCIPAL);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "listAgrupaciones") {
		
		$idAgrup = Decodear3($get["idAgrup"]);
		
		if ($post["agrupacion_nueva"] == "1") {
			
			$id_agrupacion = reportes_online_maxAgrupacion();
			foreach ($post as $key => $value) {
				
				if ($key == "nombre_agrupacion" or $key == "agrupacion_nueva") {
					continue;
				}
				$nombre_agrupacion = $post["nombre_agrupacion"];
				
				reportes_online_Insert_agrupacion_data($id_agrupacion, $nombre_agrupacion, $key);
			}
		}
		
		$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/reportes_online/entorno.html"));
		$PRINCIPAL = str_replace("{ENTORNO}", reportes_online_lista_agrupaciones(FuncionesTransversalesAdmin(file_get_contents("views/reportes_online/index_agrupaciones.html")), $_SESSION["id_empresa"], $filtro, $excel, $idAgrup), $PRINCIPAL);
		$datos_empresa = DatosEmpresa($id_empresa);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "filtro_programabbdd_por_foco_creacion_cursos") {
		$foco = $post["foco"];
		$arreglo_post = $post;
		$id_empresa = $_SESSION["id_empresa"];
				// Traigo los programas, de la tabla rel_lms_malla_curso con foco y empresa
		
		$programas = ListasPresenciales_ProgramasDadoFocoDeTablaPrograma($id_empresa, $foco);
		$options = '<script src="js/bootstrap-select.js"></script>';
		$options .= '<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />';
		$options .= '<link href="css/go_admin.css" rel="stylesheet" type="text/css" />';
		$options .= '<link href="css/go_admin.css" rel="stylesheet" type="text/css" />';
		$options .= "<select class='form-control'  name='programa_bbdd' id='programa_bbdd' required='required'>";
		$options .= "<option value='0'>Todos</option>";
		foreach ($programas as $prog) {
			$options .= "<option value='" . $prog->id_programa . "'>" . $prog->nombre_programa . "</option>";
		}
		
		$options .= "</select>";
		echo($options);
	}
	
	
	elseif ($seccion == "MuestraBloqueCursoSeleccionadoDesdeCurso") {
		$id_curso = Decodear3($get["i"]);
		
		if ($get["del"] <> "") {
			$id_linea = Decodear3($get["del"]);
			DeleteSesionImparticion2021($id_linea);
		}
		
		$id_programa = Decodear3($get["idpbbdd"]);
		$id_inscripcion = Decodear3($get["i"]);
		$id_imparticion = Decodear3($get["id_imparticion"]);
		
		
		if ($id_imparticion <> "") {
			//$id_imparticion
		}
		
		$id_empresa = $_SESSION["id_empresa"];
		$datos_curso = VerificoCursoPorEmpresa($id_curso, $id_empresa);
		
		if ($datos_curso[0]->modalidad == "") {
			$formulario = file_get_contents("views/capacitacion/imparticion/formulario_ingresa_elearning.html");
		}
		elseif ($datos_curso[0]->modalidad == 1) {
			$DISPLAY_MODALIDAD2 = " display:none !important";
			
			$formulario = file_get_contents("views/capacitacion/imparticion/formulario_ingresa_elearning1.html");
			$mallas = TraeMallaDadoProgramaCurso($id_empresa, $id_programa, $id_curso);
			foreach ($mallas as $mall) {
				$option_malla .= "<option value='" . $mall->id_malla . "'>" . ($mall->nombre_malla) . "</option>;";
			}
			$formulario = str_replace("{OPTIONS_MALLAS}", $option_malla, $formulario);
			$back_to_list_cursos = "listcursos1";
			$back_to_list_cursos = "listaInscripciones1&i=" . $get["i"] . "";
		}
		elseif ($datos_curso[0]->modalidad == 2) {
			$DISPLAY_MODALIDAD1 = " display:none !important";
			$formulario = file_get_contents("views/capacitacion/imparticion/formulario_ingresa_presencial2.html");
			$back_to_list_cursos = "listcursos2";
			$back_to_list_cursos = "listaInscripciones2&i=" . $get["i"] . "";
		}
		elseif ($datos_curso[0]->modalidad == 3) {
			$formulario = file_get_contents("views/capacitacion/imparticion/formulario_ingresa_presencial3.html");
			$back_to_list_cursos = "listcursos3";
			$back_to_list_cursos = "listaInscripciones3&i=" . $get["i"] . "";
		}
		else {
			$formulario = file_get_contents("views/capacitacion/imparticion/formulario_ingresa_presencial.html");
		}
		
		// malla_bch / categoria_bch
		$malla_bch = ListMallaBch();
		
		foreach ($malla_bch as $unicomalla) {
			$option_malla_bch .= "<option value='" . $unicomalla->nombre . "'>" . ($unicomalla->nombre) . "</option>;";
		}
		
		$categoria_bch = ListCategoriaBch();
		foreach ($categoria_bch as $unicocat) {
			$option_categoria_bch .= "<option value='" . $unicocat->nombre . "'>" . ($unicocat->nombre) . "</option>;";
		}
		
		$formulario = str_replace("{OPTIONS_MALLA_BCH}", $option_malla_bch, $formulario);
		$formulario = str_replace("{OPTIONS_CATEGORIA_BCH}", $option_categoria_bch, $formulario);
		$formulario = FormularioImparticion(FuncionesTransversalesAdmin($formulario), $id_empresa, $id_imparticion);
		$formulario = DatosImparticiones_Sesiones_2021($formulario, $id_imparticion, $id_curso);
		$Imp = BuscaIdImparticionFull_2021($id_imparticion);
		$alerta_horas = DatosImparticiones_SumaHoras($id_curso, $id_imparticion, $Imp[0]->tipo_audiencia, $Imp[0]->fecha_inicio, $Imp[0]->fecha_termino, $Imp[0]->hora_inicio, $Imp[0]->hora_termino, $datos_curso[0]->modalidad, $datos_curso[0]->numero_horas);
		$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/imparticion/formulario_ingresa_desde_curso.html"));
		
		$alerta_sinasistencia_sin_horas = DatosImparticiones_SinASistenciaSinHoras($id_curso, $id_imparticion, $Imp[0]->tipo_audiencia, $Imp[0]->fecha_inicio, $Imp[0]->fecha_termino, $Imp[0]->hora_inicio, $Imp[0]->hora_termino, $datos_curso[0]->modalidad, $datos_curso[0]->numero_horas);
		
		$PRINCIPAL = str_replace("{alert_de_imparticion_sin_asistencia_nota_aprobacion}", $alerta_sinasistencia_sin_horas, $PRINCIPAL);
		$PRINCIPAL = str_replace("{DISPLAY_MODALIDAD1}", $DISPLAY_MODALIDAD1, $PRINCIPAL);
		$PRINCIPAL = str_replace("{DISPLAY_MODALIDAD2}", $DISPLAY_MODALIDAD2, $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_INSCRIPCION_ENCODEADO}", Encodear3($id_imparticion), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_CURSO}", $id_curso, $PRINCIPAL);
		$PRINCIPAL = str_replace("{CODIGOINCRIPCION}", $id_imparticion, $PRINCIPAL);
		$formulario = str_replace("{ALERTA_HORAS_SESIONES_FECHA_INICIO_TERMINO_CURSO_ID_IMPARTICION}", $alerta_horas, $formulario);
		$PRINCIPAL = str_replace("{FORMULARIO_HORARIOS_LINEA_1}", "1", $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_EMPRESA}", $_SESSION["id_empresa"], $PRINCIPAL);
		$PRINCIPAL = str_replace("{BACK_TO_LIST_CURSOS}", $back_to_list_cursos, $PRINCIPAL);
		$PRINCIPAL = str_replace("{BLOQUE_CURSO_SELECCIONADO}", $formulario, $PRINCIPAL);
		
		
		
		if (Decodear3($get["id_imparticion"]) <> "") {
			$PRINCIPAL = str_replace("{EDITAR}", "1", $PRINCIPAL);
		}
		else {
			$PRINCIPAL = str_replace("{EDITAR}", "0", $PRINCIPAL);
		}
		$PRINCIPAL = str_replace("{VALUE_COD_IMPARTICION_FECHA_DESDE_NEW2}", $Imp[0]->fecha_inicio, $PRINCIPAL);
		$PRINCIPAL = str_replace("{VALUE_COD_IMPARTICION_FECHA_HASTA_NEW2}", $Imp[0]->fecha_termino, $PRINCIPAL);
		$PRINCIPAL = str_replace("{VALUE_COD_IMPARTICION_HORA_DESDE_NEW2}", $Imp[0]->hora_inicio, $PRINCIPAL);
		$PRINCIPAL = str_replace("{VALUE_COD_IMPARTICION_HORA_HASTA_NEW2}", $Imp[0]->hora_termino, $PRINCIPAL);
		$PRINCIPAL = str_replace("{RELATOR}", $Imp[0]->id_audiencia, $PRINCIPAL);
		$CuentaContableProyEje = SelectCuentaContableDadoCurso_ProyectoEje2024($id_curso);
		
		$PRINCIPAL = str_replace("{CUENTA_CONTABLE}", $CuentaContableProyEje[0]->CUENTA, $PRINCIPAL);
		$PRINCIPAL = str_replace("{NOMBRE_CUENTA_CONTABLE}", $CuentaContableProyEje[0]->CUENTA_GLOSA, $PRINCIPAL);
		$PRINCIPAL = str_replace("{EJE}", $CuentaContableProyEje[0]->EJE, $PRINCIPAL);
		$PRINCIPAL = str_replace("{PROYECTO}", $CuentaContableProyEje[0]->PROYECTO, $PRINCIPAL);
		$Rel = TraeRelatores2021porId($Imp[0]->id_audiencia);
		if ($Rel[0]->nombre <> "") {
			$relator_nombre = "" . ($Rel[0]->nombre) . " (" . $Rel[0]->tipo . ")";
			$rut_relator = $Imp[0]->id_audiencia;
		}
		else {
			$rut_relator = "";
			$relator_nombre = "";
		}
		$PRINCIPAL = str_replace("{RELATOR}", $rut_relator, $PRINCIPAL);
		$PRINCIPAL = str_replace("{RELATOR_nombre}", $relator_nombre, $PRINCIPAL);
		
		if ($Imp[0]->tipo_audiencia == "fecha_sesiones") {
			$script_activa_radio_horarios = "
		    	$(document).ready(function() {
						$('#radio_fecha_sesiones').click();
					});
    	";
		}
		elseif ($Imp[0]->tipo_audiencia == "fecha_inicio_termino") {
			$script_activa_radio_horarios = "
				$(document).ready(function() {
								$('#radio_fecha_inicio_termino').click();
							});
    	";
		}
		else {
			$script_activa_radio_horarios = "";
		}
		
		$PRINCIPAL = str_replace("{OPTIONS_MALLA_BCH_SAVED}", $Imp[0]->abierto_cerrado, $PRINCIPAL);
		$PRINCIPAL = str_replace("{OPTIONS_CATEGORIA_BCH_SAVED}", $Imp[0]->pago, $PRINCIPAL);
		
		$PRINCIPAL = str_replace("{MINIMO_ASISTENCIA}", $Imp[0]->minimo_asistencia, $PRINCIPAL);
		$PRINCIPAL = str_replace("{MINIMO_NOTA_APROBACION}", $Imp[0]->minimo_nota_aprobacion, $PRINCIPAL);
		if ($Imp[0]->sence == "SI") {
			$sence_si = " selected ";
		}
		elseif ($Imp[0]->sence == "NO") {
			$sence_no = " selected ";
		}
		else {
			$sence_no = " ";
			$sence_si = " ";
		}
		$PRINCIPAL = str_replace("{SENCE_SI}", $sence_si, $PRINCIPAL);
		$PRINCIPAL = str_replace("{SENCE_NO}", $sence_no, $PRINCIPAL);
		$PRINCIPAL = str_replace("{COD_SENCE}", $Imp[0]->cod_sence, $PRINCIPAL);
		$PRINCIPAL = str_replace("{NOMBRE_CURSO_SENCE}", ($Imp[0]->nombre_curso_sence), $PRINCIPAL);
		if ($Imp[0]->otic == "SOFOFA") {
			$select_sofofa = " selected ";
		}
		elseif ($Imp[0]->otic == "BANOTIC") {
			$select_sofofa = " selected ";
		}
		else {
			$select_banotic = "  ";
		}
		
		$PRINCIPAL = str_replace("{SELECT_SOFOFA}", $select_sofofa, $PRINCIPAL);
		$PRINCIPAL = str_replace("{SELECT_BANOTIC}", $select_banotic, $PRINCIPAL);
		
		$ota_cerrada = OtaCerrada2024($id_imparticion);
		if ($ota_cerrada > 0) {
			$PRINCIPAL = str_replace("{DISPLAY_OTA_ABIERTA}", "display:none!important", $PRINCIPAL);
		}
		
		$PRINCIPAL = str_replace("{SCRIPT_ACTIVA_TIPO_HORARIO_BOTONES}", $script_activa_radio_horarios, $PRINCIPAL);
		$PRINCIPAL = str_replace("{NOMBRE_CURSO_imparticion}", ($datos_curso[0]->nombre), $PRINCIPAL);
		$PRINCIPAL = str_replace("{NOMBRE_EJECUTIVOS}", "Ejecutivos", $PRINCIPAL);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	
	elseif ($seccion == "MuestraBloqueCursoSeleccionadoDesdeMalla") {
		
		$id_malla = Decodear3($get["i"]);
		
		if ($get["del"] <> "") {
			$id_linea = Decodear3($get["del"]);
			DeleteSesionImparticion2021($id_linea);
		}
		$id_programa = Decodear3($get["idpbbdd"]);
		$id_inscripcion = Decodear3($get["i"]);
		$id_imparticion = Decodear3($get["id_imparticion"]);
		$id_foco = BuscaIdFoco_DadoIdMalla_IdPrograma_2022($id_malla, $id_programa);
		$id_malla_enc = Decodear3($get["id_malla_enc"]);
		
		if ($id_malla_enc <> "") {
			$id_malla = $id_malla_enc;
			$_SESSION["id_inscripcion_id_malla"] = BuscaIdInscripcionMalla_2022($id_malla, $id_inscripcion);
		}
		
		
		$id_empresa = $_SESSION["id_empresa"];
		$datos_malla = BuscoCursoDadaMallayPrograma_2024($id_malla, $id_programa, $_SESSION["id_inscripcion_id_malla"]);
		$datos_malla_first = BuscoCursoDadaMallayPrograma_2024_First($id_malla, $id_programa);
		if ($get["first"] == "1") {
			$_SESSION["id_inscripcion_id_malla"] = "";
			$proximo_id_inscripcion = BuscaCorrelativoMalla_IdMalla_IdInscripcion2022();
			$proximo_id_inscripcion = $proximo_id_inscripcion + 1;
		}
		
		foreach ($datos_malla_first as $unico) {
			if ($get["first"] == "1") {
				insert_IdMalla_IdInscripcion2022($id_malla, $unico->id_curso, $proximo_id_inscripcion);
			}
		}
		
		if ($get["first"] == "1") {
			$_SESSION["id_inscripcion_id_malla"] = $proximo_id_inscripcion;
		}
		
		
		$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/imparticion/formulario_ingresa_desde_malla.html"));
		
		// Recorre Tabla Inscripcion Id malla
		$Id_cursos_id_Inscripcion_por_id_inscripcion_idMalla = Id_cursos_id_Inscripcion_por_id_inscripcion_idMalla_2022($_SESSION["id_inscripcion_id_malla"]);
		
		foreach ($Id_cursos_id_Inscripcion_por_id_inscripcion_idMalla as $u) {
			
			$row_id_curso .= file_get_contents("views/capacitacion/imparticion/row_id_curso_por_malla.html");
			$row_id_curso = str_replace("{ID_CURSO}", $u->id_curso, $row_id_curso);
			$row_id_curso = str_replace("{NOMBRE_CURSO}", ($u->nombre), $row_id_curso);
			$row_id_curso = str_replace("{ID_INSCRIPCION}", $u->id_inscripcion, $row_id_curso);
			$EjeProyecto = BuscaEjeProyecto_rel_lms_id_curso_id_inscripcion($u->id_inscripcion);
			Insert_Imparticion_Tbl_Usuarios_Datos_Dotacion_FN_2023($u->id_inscripcion);
		}
		$check_opcional = "";
		$check_obligatorio = "";
		if ($u->opcional == "1") {
			$check_opcional = " checked ";
		}
		else {
			$check_obligatorio = " checked ";
		}
		$PRINCIPAL = str_replace("{CHECKED_OBLIGATORIO}", $check_obligatorio, $PRINCIPAL);
		$PRINCIPAL = str_replace("{CHECKED_OPCIONAL}", $check_opcional, $PRINCIPAL);
		$PRINCIPAL = str_replace("{VALOR_FECHA_DESDE}", $u->fecha_inicio, $PRINCIPAL);
		$PRINCIPAL = str_replace("{VALOR_FECHA_HASTA}", $u->fecha_termino, $PRINCIPAL);
		$PRINCIPAL = str_replace("{VALOR_NOMBRE_IMPARTICION}", ($u->nombre_imparticion), $PRINCIPAL);
		$PRINCIPAL = str_replace("{VALOR_EJECUTIVO}", ($u->ejecutivo), $PRINCIPAL);
		$Ejec = TraeDatosEjecutivos($u->ejecutivo);
		$PRINCIPAL = str_replace("{VALOR_EJECUTIVO_NOMBRE}", ($Ejec[0]->nombre), $PRINCIPAL);
		
		$hoy = date("Y-m-d");
		$alerta_delete = "";
		if ($get["del"] == "1") {
			
			if ($u->fecha_inicio <= $hoy) {
				$alerta_delete = "<div class='alert alert-danger'>Las imparticiones ya están en curso. Ya no puedes eliminar estas imparticiones</div>";
			}
			else {
				$alerta_delete = "<div class='alert alert-danger'>
                                <a href='?sw=dltImpartmalla&i=" . $get["i"] . "&id_malla_enc=" . $get["id_malla_enc"] . "&idpbbdd=" . $get["idpbbdd"] . "'>
                                    <span style='color: #01225d;'>Eliminar Imparticiones Definitivamente</span>
                                 </a>
                            </div>";
			}
		}
		if ($get["edit"] == "1") {
			
			if ($u->fecha_inicio <= $hoy) {
				$alerta_delete = "<div class='alert alert-info'>Las imparticiones ya están en curso. Ya no las puedes editar</div>";
				$boton_editar = "";
			}
			else {
				$alerta_delete = "";
				$boton_editar = "<input type='submit' class='btn btn-info' name='guardar' value='Editar'>";
			}
		}
		else {
			$boton_editar = "<input type='submit' class='btn btn-info' name='guardar' value='Guardar'>";
		}
		
		$PRINCIPAL = str_replace("{ALERTA_DELETE_2022}", $alerta_delete, $PRINCIPAL);
		$PRINCIPAL = str_replace("{BOTON_GUARDAR_O_NO_SE_PUEDE_EDITAR}", $boton_editar, $PRINCIPAL);
		
		if ($u->id_inscripcion <> "") {
			$agregar_participantes = "";
		}
		else {
			$agregar_participantes = "display:none!important";
		}
		// print_r($datos_malla);
		$PRINCIPAL = str_replace("{DESPLIEGA_BLOQUE_PARTICIPANTES}", $agregar_participantes, $PRINCIPAL);
		$PRINCIPAL = str_replace("{NOMBRE_MALLA}", ($datos_malla[0]->nombre_malla), $PRINCIPAL);
		$PRINCIPAL = str_replace("{NOMBRE_PROGRAMA}", ($datos_malla[0]->nombre_programa), $PRINCIPAL);
		
		$PRINCIPAL = str_replace("{OPTIONS_MALLA_BCH_SAVED}", ($datos_malla[0]->malla_bch), $PRINCIPAL);
		$PRINCIPAL = str_replace("{OPTIONS_CATEGORIA_BCH_SAVED}", ($datos_malla[0]->categoria_bch), $PRINCIPAL);
		$PRINCIPAL = str_replace("{OPTIONS_FOCO_ID_SAVED}", ($datos_malla[0]->id_foco), $PRINCIPAL);
		$PRINCIPAL = str_replace("{OPTIONS_FOCO_SAVED}", ($datos_malla[0]->foco), $PRINCIPAL);
		$PRINCIPAL = str_replace("{OPTIONS_PROGRAMA_ID_SAVED}", ($datos_malla[0]->id_programa), $PRINCIPAL);
		$PRINCIPAL = str_replace("{OPTIONS_PROGRAMA_SAVED}", ($datos_malla[0]->programa), $PRINCIPAL);
		
		$PRINCIPAL = str_replace("{BLOQUE_MALLA_SELECCIONADO}", $formulario_mallas, $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_PROGRAMA_ENC}", $get["idpbbdd"], $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_MALLA_ENCODEADO}", $get["i"], $PRINCIPAL);
		
		$PRINCIPAL = str_replace("{ID_FOCO_ENCODEADO}", Encodear3($id_foco), $PRINCIPAL);
		$PRINCIPAL = str_replace("{LISTA_DE_CURSOS_DE_MALLA_DESDE_INSCRIPCION}", ($row_id_curso), $PRINCIPAL);
		//$datos_curso = VerificoCursoPorEmpresa($id_curso, $id_empresa);
		
		$ejecutivos_new = TraeEjecutivosDeTabla($id_empresa);
		$options_ejecutivos_new = "";
		foreach ($ejecutivos_new as $ejec) {
			$options_ejecutivos_new .= "<option value='" . $ejec->rut . "'>" . $ejec->nombre . "</option>";
		}
		
		$PRINCIPAL = str_replace("{OPTIONS_EJECUTIVOS_NEW}", ($options_ejecutivos_new), $PRINCIPAL);
		
		$DISPLAY_MODALIDAD2 = " display:none !important";
		$back_to_list_cursos = "listaInscripciones1&i=" . $get["i"] . "";
		$back_to_list_cursos = "listmallas1";
		
		
		$alerta_horas = DatosImparticiones_SumaHoras($id_curso, $id_imparticion, $Imp[0]->tipo_audiencia, $Imp[0]->fecha_inicio, $Imp[0]->fecha_termino, $Imp[0]->hora_inicio, $Imp[0]->hora_termino, $datos_curso[0]->modalidad, $datos_curso[0]->numero_horas);
		
		
		$alerta_sinasistencia_sin_horas = DatosImparticiones_SinASistenciaSinHoras($id_curso, $id_imparticion, $Imp[0]->tipo_audiencia, $Imp[0]->fecha_inicio, $Imp[0]->fecha_termino, $Imp[0]->hora_inicio, $Imp[0]->hora_termino, $datos_curso[0]->modalidad, $datos_curso[0]->numero_horas);
		$PRINCIPAL = str_replace("{alert_de_imparticion_sin_asistencia_nota_aprobacion}", $alerta_sinasistencia_sin_horas, $PRINCIPAL);
		
		$PRINCIPAL = str_replace("{DISPLAY_MODALIDAD1}", $DISPLAY_MODALIDAD1, $PRINCIPAL);
		$PRINCIPAL = str_replace("{DISPLAY_MODALIDAD2}", $DISPLAY_MODALIDAD2, $PRINCIPAL);
		
		$usuarios_preinscritos = DescargaRut_IdMalla_IdInscripcion2022($_SESSION["id_inscripcion_id_malla"]);
		$cuenta_usuarios_preinscritos = count($usuarios_preinscritos);
		$PRINCIPAL = str_replace("{CUENTA_PREINSCRITOS}", $cuenta_usuarios_preinscritos, $PRINCIPAL);
		
		$PRINCIPAL = str_replace("{ID_INSCRIPCION_ENCODEADO}", Encodear3($id_imparticion), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_CURSO}", $id_curso, $PRINCIPAL);
		$PRINCIPAL = str_replace("{CODIGOINCRIPCION}", $id_imparticion, $PRINCIPAL);
		$formulario = str_replace("{ALERTA_HORAS_SESIONES_FECHA_INICIO_TERMINO_CURSO_ID_IMPARTICION}", $alerta_horas, $formulario);
		$PRINCIPAL = str_replace("{FORMULARIO_HORARIOS_LINEA_1}", "1", $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_EMPRESA}", $_SESSION["id_empresa"], $PRINCIPAL);
		$PRINCIPAL = str_replace("{BACK_TO_LIST_CURSOS}", $back_to_list_cursos, $PRINCIPAL);
		
		
		$PRINCIPAL = str_replace("{VALUE_COD_IMPARTICION_FECHA_DESDE_NEW2}", $Imp[0]->fecha_inicio, $PRINCIPAL);
		$PRINCIPAL = str_replace("{VALUE_COD_IMPARTICION_FECHA_HASTA_NEW2}", $Imp[0]->fecha_termino, $PRINCIPAL);
		$PRINCIPAL = str_replace("{VALUE_COD_IMPARTICION_HORA_DESDE_NEW2}", $Imp[0]->hora_inicio, $PRINCIPAL);
		$PRINCIPAL = str_replace("{VALUE_COD_IMPARTICION_HORA_HASTA_NEW2}", $Imp[0]->hora_termino, $PRINCIPAL);
		$PRINCIPAL = str_replace("{RELATOR}", $Imp[0]->id_audiencia, $PRINCIPAL);
		
		$Rel = TraeRelatores2021porId($Imp[0]->id_audiencia);
		if ($Rel[0]->nombre <> "") {
			$relator_nombre = "" . ($Rel[0]->nombre) . " (" . $Rel[0]->tipo . ")";
			$rut_relator = $Imp[0]->id_audiencia;
		}
		else {
			$rut_relator = "";
			$relator_nombre = "";
		}
		$PRINCIPAL = str_replace("{RELATOR}", $rut_relator, $PRINCIPAL);
		$PRINCIPAL = str_replace("{RELATOR_nombre}", $relator_nombre, $PRINCIPAL);
		
		if ($Imp[0]->tipo_audiencia == "fecha_sesiones") {
			$script_activa_radio_horarios = "
		    	$(document).ready(function() {
						$('#radio_fecha_sesiones').click();
					});
    	";
		}
		elseif ($Imp[0]->tipo_audiencia == "fecha_inicio_termino") {
			$script_activa_radio_horarios = "
				$(document).ready(function() {
								$('#radio_fecha_inicio_termino').click();
							});
    	";
		}
		else {
			$script_activa_radio_horarios = "";
		}
		
		$PRINCIPAL = str_replace("{MINIMO_ASISTENCIA}", $Imp[0]->minimo_asistencia, $PRINCIPAL);
		$PRINCIPAL = str_replace("{MINIMO_NOTA_APROBACION}", $Imp[0]->minimo_nota_aprobacion, $PRINCIPAL);
		if ($Imp[0]->sence == "SI") {
			$sence_si = " selected ";
		}
		elseif ($Imp[0]->sence == "NO") {
			$sence_no = " selected ";
		}
		else {
			$sence_no = " ";
			$sence_si = " ";
		}
		$PRINCIPAL = str_replace("{SENCE_SI}", $sence_si, $PRINCIPAL);
		$PRINCIPAL = str_replace("{SENCE_NO}", $sence_no, $PRINCIPAL);
		$PRINCIPAL = str_replace("{COD_SENCE}", $Imp[0]->cod_sence, $PRINCIPAL);
		$PRINCIPAL = str_replace("{NOMBRE_CURSO_SENCE}", ($Imp[0]->nombre_curso_sence), $PRINCIPAL);
		if ($Imp[0]->otic == "SOFOFA") {
			$select_sofofa = " selected ";
		}
		elseif ($Imp[0]->otic == "BANOTIC") {
			$select_sofofa = " selected ";
		}
		else {
			$select_banotic = "  ";
		}
		
		$PRINCIPAL = str_replace("{SELECT_SOFOFA}", $select_sofofa, $PRINCIPAL);
		$PRINCIPAL = str_replace("{SELECT_BANOTIC}", $select_banotic, $PRINCIPAL);
		
		// malla_bch / categoria_bch
		$malla_bch = ListMallaBch();
		
		foreach ($malla_bch as $unicomalla) {
			$option_malla_bch .= "<option value='" . $unicomalla->nombre . "'>" . ($unicomalla->nombre) . "</option>;";
		}
		
		$categoria_bch = ListCategoriaBch();
		foreach ($categoria_bch as $unicocat) {
			$option_categoria_bch .= "<option value='" . $unicocat->nombre . "'>" . ($unicocat->nombre) . "</option>;";
		}
		
		$PRINCIPAL = str_replace("{OPTIONS_MALLA_BCH}", $option_malla_bch, $PRINCIPAL);
		$PRINCIPAL = str_replace("{OPTIONS_CATEGORIA_BCH}", $option_categoria_bch, $PRINCIPAL);
		
		$PRINCIPAL = str_replace("{SCRIPT_ACTIVA_TIPO_HORARIO_BOTONES}", $script_activa_radio_horarios, $PRINCIPAL);
		$PRINCIPAL = str_replace("{NOMBRE_CURSO_imparticion}", ($datos_curso[0]->nombre), $PRINCIPAL);
		$PRINCIPAL = str_replace("{NOMBRE_EJECUTIVOS}", "Ejecutivos", $PRINCIPAL);
		$focos = ListasPresenciales_Obtienefocos($id_empresa);
		$options_focos = "";
		foreach ($focos as $foc) {
			if ($foc->codigo_foco == $datos_curso[0]->id_foco) {
				$options_focos .= "<option value='" . $foc->codigo_foco . "' selected='selected'>" . $foc->descripcion . "</option>";
			}
			else {
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
			}
			else {
				$options_programas_bbdd .= "<option value='" . $prog_bbdd->id_programa . "'>" . $prog_bbdd->nombre_programa . "</option>";
			}
		}
		$PRINCIPAL = str_replace("{OPTIONS_PROGRAMAS_BBDD_2022_sinanidacion}", ($options_programas_bbdd), $PRINCIPAL);
		
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	
	
	elseif ($seccion == "adimparti_malla") {
		
		$id_inscripcion_id_malla = $_SESSION["id_inscripcion_id_malla"];
		$id_empresa = $_SESSION["id_empresa"];
		$fecha_desde = $post["fecha_desde"];
		$fecha_hasta = $post["fecha_hasta"];
		$opcional = $post["opcional"];
		
		$categoria_bch = $post["categoria_bch"];
		$malla_bch = $post["malla_bch"];
		
		$i = $post["i"];
		$idpbbdd = $post["idpbbdd"];
		$id_malla = Decodear3($post["id_malla_enc"]);
		$id_programa = Decodear3($post["id_programa_enc"]);
		$id_foco = Decodear3($post["id_foco_enc"]);
		$ejeM = ($post["foco"]);
		$proyectoM = ($post["programa_bbdd"]);
		$ejecutivo = $post["ejecutivo"];
		$nombre_inscripcion = $post["nombre_imparticion"];
		$activa_cero = 0;
		$Lista_Cursos_para_CrearInscripcion = Id_cursos_id_Inscripcion_por_id_inscripcion_idMalla_2022($id_inscripcion_id_malla);
		foreach ($Lista_Cursos_para_CrearInscripcion as $u) {
			
			if (empty($u->id_inscripcion)) {
				$trae_imparticiones_ultima = IMPARTICIONES_traeImparticionesCreadasUltima_2021($id_empresa);
				$siguiente_codigo_p = NuevoCodigoImparticion_2021($trae_imparticiones_ultima);
				$siguiente_codigo = VerificaCodImparticion_2023($siguiente_codigo_p);
			}
			else {
				$siguiente_codigo = $u->id_inscripcion;
			}
			

			InsertUpdatetbl_rel_lms_id_curso_id_inscripcion($u->id_curso, $siguiente_codigo, "", $fecha_desde, $fecha_hasta, $id_empresa, $opcional, $ejecutivo, $rut_ejecutivo_externo, $sence, $cod_sence, $nombre_curso_sence, $nombre_otic, "", "");
			Update_Id_cursos_id_Inscripcion_por_id_inscripcion_idMalla_2022($id_inscripcion_id_malla, $u->id_curso, $siguiente_codigo, $fecha_desde, $fecha_hasta, $opcional, $nombre_inscripcion, $ejecutivo, $categoria_bch, $malla_bch, $proyectoM, $ejeM);
			UpdateIdMallaProgramaFocoParaIdInscripcion($nombre_inscripcion, $activa_cero, $siguiente_codigo, $id_malla, $id_programa, $id_foco);
			Updatetbl_rel_lms_id_curso_id_inscripcion_eje_proyecto($u->id_curso, $siguiente_codigo, $ejeM, $proyectoM);
		}
		echo "<script>alert('Creadas todas las inscripciones de los cursos');</script>";
		echo "<script>location.href='?sw=MuestraBloqueCursoSeleccionadoDesdeMalla&i=" . $i . "&idpbbdd=" . $idpbbdd . "';    </script>";
		exit();
	}
	
	elseif ($seccion == "adimparti") {
		
		if ($post["ed"] == "1") {
			$id_imparticion_decod = ($post["cod_imparticion"]);
		}
		else {
			$id_imparticion_decod = VerificaCodImparticion_2023($post["cod_imparticion"]);
			$post["cod_imparticion"] = $id_imparticion_decod;
		}
		
		if ($post["imparticion_elearning"] == "1") {
			
			if ($post["opcional"] == "on") {
				$opcional = "1";
			}
			else {
				$opcional = "0";
			}
			
			InsertUpdatetbl_rel_lms_id_curso_id_inscripcion($post["curso"], $id_imparticion_decod, $post["nombre"], $post["fecha_desde_new_2"], $post["fecha_hasta_new_2"], $_SESSION["id_empresa"], $opcional, $post["ejecutivo"], $post["ejecutivo_externo"], $post["sence"], $post["cod_sence"], $post["nombre_curso_sence"], $post["nombre_otic"], $post["tipo_modalidad"], $post["costos_asociados"]);
			
		}
		else {
			
			
			$existe_otra_imparticion_2023 = InscripcionCheckImparticionrepetido_2023($post["curso"], $id_imparticion_decod);
			
			
			if ($existe_otra_imparticion_2023 > 0) {
				$nuevo_cod1 = NuevoCodigoImparticion_2021($id_imparticion_decod);
				$existe_otra_imparticion_2023_1 = InscripcionCheckImparticionrepetido_2023($post["curso"], $nuevo_cod1);
				
				if ($existe_otra_imparticion_2023_1 > 0) {
					$nuevo_cod1 = NuevoCodigoImparticion_2021($nuevo_cod1);
					$existe_otra_imparticion_2023_1 = InscripcionCheckImparticionrepetido_2023($post["curso"], $nuevo_cod1);
					
				}
				
				$post["cod_imparticion"] = $nuevo_cod1;
				$id_imparticion_decod = $post["cod_imparticion"];
			}
			
			if ($post["opcional"] == "1") {
				$opcional = "1";
			}
			else {
				$opcional = "0";
			}
			
			InsertUpdatetbl_rel_lms_id_curso_id_inscripcion($post["curso"], $id_imparticion_decod, $post["nombre"], $post["fecha_desde_new_2"], $post["fecha_hasta_new_2"], $_SESSION["id_empresa"], $opcional, $post["ejecutivo"], $post["ejecutivo_externo"], $post["sence"], $post["cod_sence"], $post["nombre_curso_sence"], $post["nombre_otic"], $post["tipo_modalidad"], $post["costos_asociados"]);
			
		}
		
		
		$id_imparticion_decod = Decodear3($post["id_imparticion_enc"]);
		if ($id_imparticion_decod <> "") {
			$id_imparticion_encoded = $post["id_imparticion_enc"];
		}
		else {
			$id_imparticion_decod = $post["cod_imparticion"];
			$id_imparticion_encoded = Encodear3($id_imparticion_decod);
		}
		
		if ($post["tipo_horario"] == "fecha_sesiones") {
			// calcula Fecha Inicio / Termino de la Impartición
			//	echo "Fechas por Sesiones ";
			
			if ($post["fecha1"] <> "") {
				$relator = Decodear3($post["relator_SES1"]);
				AgregaSesionesImparticion("1", $id_imparticion_decod, $post["fecha1"], $post["hora_desde1"], $post["hora_hasta1"], $post["observacion"], Decodear3($post["id_linea1"]), $relator, "");
			}
			if ($post["fecha2"] <> "") {
				$relator = Decodear3($post["relator_SES2"]);
				AgregaSesionesImparticion("2", $id_imparticion_decod, $post["fecha2"], $post["hora_desde2"], $post["hora_hasta2"], $post["observacion"], Decodear3($post["id_linea2"]), $relator, "");
			}
			if ($post["fecha3"] <> "") {
				$relator = Decodear3($post["relator_SES3"]);
				AgregaSesionesImparticion("3", $id_imparticion_decod, $post["fecha3"], $post["hora_desde3"], $post["hora_hasta3"], $post["observacion"], Decodear3($post["id_linea3"]), $relator, "");
			}
			if ($post["fecha4"] <> "") {
				$relator = Decodear3($post["relator_SES4"]);
				AgregaSesionesImparticion("4", $id_imparticion_decod, $post["fecha4"], $post["hora_desde4"], $post["hora_hasta4"], $post["observacion"], Decodear3($post["id_linea4"]), $relator, "");
			}
			if ($post["fecha5"] <> "") {
				$relator = Decodear3($post["relator_SES5"]);
				AgregaSesionesImparticion("5", $id_imparticion_decod, $post["fecha5"], $post["hora_desde5"], $post["hora_hasta5"], $post["observacion"], Decodear3($post["id_linea5"]), $relator, "");
			}
			if ($post["fecha6"] <> "") {
				$relator = Decodear3($post["relator_SES6"]);
				AgregaSesionesImparticion("6", $id_imparticion_decod, $post["fecha6"], $post["hora_desde6"], $post["hora_hasta6"], $post["observacion"], Decodear3($post["id_linea6"]), $relator, "");
			}
			
			if ($post["fecha7"] <> "") {
				$relator = Decodear3($post["relator_SES7"]);
				AgregaSesionesImparticion("7", $id_imparticion_decod, $post["fecha7"], $post["hora_desde7"], $post["hora_hasta7"], $post["observacion"], Decodear3($post["id_linea7"]), $relator, "");
			}
			if ($post["fecha8"] <> "") {
				$relator = Decodear3($post["relator_SES8"]);
				AgregaSesionesImparticion("8", $id_imparticion_decod, $post["fecha8"], $post["hora_desde8"], $post["hora_hasta8"], $post["observacion"], Decodear3($post["id_linea8"]), $relator, "");
			}
			if ($post["fecha9"] <> "") {
				$relator = Decodear3($post["relator_SES9"]);
				AgregaSesionesImparticion("9", $id_imparticion_decod, $post["fecha9"], $post["hora_desde9"], $post["hora_hasta9"], $post["observacion"], Decodear3($post["id_linea9"]), $relator, "");
			}
			if ($post["fecha10"] <> "") {
				$relator = Decodear3($post["relator_SES10"]);
				AgregaSesionesImparticion("1", $id_imparticion_decod, $post["fecha10"], $post["hora_desde10"], $post["hora_hasta10"], $post["observacion"], Decodear3($post["id_linea10"]), $relator, "");
			}
			if ($post["fecha11"] <> "") {
				$relator = Decodear3($post["relator_SES11"]);
				AgregaSesionesImparticion("11", $id_imparticion_decod, $post["fecha11"], $post["hora_desde11"], $post["hora_hasta11"], $post["observacion"], Decodear3($post["id_linea11"]), $relator, "");
			}
			if ($post["fecha12"] <> "") {
				$relator = Decodear3($post["relator_SES12"]);
				AgregaSesionesImparticion("12", $id_imparticion_decod, $post["fecha12"], $post["hora_desde12"], $post["hora_hasta12"], $post["observacion"], Decodear3($post["id_linea12"]), $relator, "");
			}
			
			if ($post["sesion_NEW"] > 0 and $post["hora_desde_NEW"] <> "") {
				$relator = Decodear3($post["relator_NEW"]);
				AgregaSesionesImparticion($post["sesion_NEW"], $id_imparticion_decod, $post["fecha_NEW"], $post["hora_desde_NEW"], $post["hora_hasta_NEW"], $post["observacion_NEW"], "", $relator, "1");
			}
			
			$Fechas_MaxMin = Sesiones_2021_MaxMinFecha($id_imparticion_decod);

			$fecha_inicio = $Fechas_MaxMin[1];
			$fecha_termino = $Fechas_MaxMin[0];
			$hora_desde = "";
			$hora_hasta = "";
		}
		else {
			
			$fecha_inicio = $post["fecha_desde_new_2"];
			$fecha_termino = $post["fecha_hasta_new_2"];
			$hora_desde = $post["hora_desde_new_2"];
			$hora_hasta = $post["hora_hasta_new_2"];
			$relator = Decodear3($post["relator"]);
		}
		
		
		
		
		$id_empresa = $_SESSION["id_empresa"];
		$datos_curso = VerificoCursoPorEmpresa($post["curso"], $id_empresa);
		$id_curso_2021 = BuscaIdCursoDadoIdImparticion_2021($post["curso"]);
		$id_empresa = $_SESSION["id_empresa"];
		$id_curso = $post["curso"];
		$codigo_imparticion = ($post["cod_imparticion"]);
		$existe_inscripcion = DatosInscripcionConCodigo($codigo_imparticion);
		if ($existe_inscripcion) {
		
		}
		
		$direccion = ($post["direccion"]);
		$ciudad = ($post["ciudad"]);
		$cupos = ($post["cupos"]);
		$ejecutivo = ($post["ejecutivo"]);
		$id_malla = ($post["id_malla"]);
		
		$nombre = ($post["nombre"]);
		$streaming = ($post["streaming"]);
		$comentarios = trim(($post["comentarios"]));
		$minimo_asistencia = ($post["minimo_asistencia"]);
		$minimo_nota_aprobacion = ($post["minimo_nota_aprobacion"]);
		$datos_post = $post;
		Lista_curso_Crea_IMPARTICION_CreaImparticion($id_empresa, $codigo_imparticion, $datos_curso[0]->id, $fecha_inicio, $fecha_termino, $direccion, $ciudad, $cupos, $id_malla, $post["tipo_horario"], $datos_post, $sesiones, $ejecutivo, $comentarios, $observacion, $hora_desde, $hora_hasta, $nombre, $relator, $streaming, $minimo_asistencia, $minimo_nota_aprobacion, $post["ejecutivo_externo"]);
		$id_curso_enc = isset($post["id_curso_enc"]) ? htmlspecialchars($post["id_curso_enc"], ENT_QUOTES) : ''; // Sanitizar
		$id_imparticion_encoded = htmlspecialchars($id_imparticion_encoded, ENT_QUOTES, 'UTF-8');
		echo "<script>location.href='?sw=MuestraBloqueCursoSeleccionadoDesdeCurso&i=" . $id_curso_enc . "&id_imparticion=" . $id_imparticion_encoded . "';</script>";
		exit;
	}
	elseif ($seccion == "edimparti") {
		$id_empresa = $_SESSION["id_empresa"];
		$id_curso = $post["curso"];
		$codigo_imparticion = ($post["cod_imparticion"]);
		$nuevo_codigo_imparticion = ($post["codigo_inscripcion"]);
		$id_empresa = $_SESSION["id_empresa"];
		$id_curso = $post["curso"];
		$codigo_imparticion = ($post["cod_imparticion"]);
		$nuevo_codigo_imparticion = ($post["codigo_inscripcion"]);
		$comentarios = ($post["comentarios"]);
		$direccion = ($post["direccion"]);
		$ciudad = ($post["ciudad"]);
		$cupos = ($post["cupos"]);
		$sesiones = ($post["sesiones"]);
		$ejecutivo = ($post["ejecutivo"]);
		$datos_curso = VerificoCursoPorEmpresa($id_curso, $id_empresa);
		$id_audiencia = $post["id_audiencia"];
		$tipo_audiencia = $post["tipo_audiencia"];
		
		if ($datos_curso[0]->modalidad == 2 or $datos_curso[0]->modalidad == 3 or $datos_curso[0]->modalidad == 4) {
			
			IMPARTICION_ActualizaImparticion($id_empresa, $codigo_imparticion, $id_curso, $post["fecha"], $post["fecha"], $post["direccion"], $post["ciudad"], $post["cupos"], $id_audiencia, $tipo_audiencia, $datos_post, $sesiones, $ejecutivo, $comentarios, $nuevo_codigo_imparticion, $post["hora_desde"], $post["hora_hasta"], $post["observacion"]);
			
			
			if ($_FILES['excel']['name'] <> "") {
				$archivo = $_FILES['excel']['name'];
				VerificaExtensionFilesAdmin($_FILES["excel"]);
				if ($archivo) {
					$tipo = $_FILES['excel']['type'];
					$destino = "tmp_ev_" . $archivo;
					if (copy($_FILES['excel']['tmp_name'], $destino)) {
					}
					else {
						$error_grave = "Error Al subir Archivo<br />";
					}
					if (file_exists("tmp_ev_" . $archivo)) {
						// Elimina Usuario en la tabla tbl
						IMPARTICION_EliminaUsuariosInscripcionUsuarioT($codigo_imparticion, $id_empresa);
						require_once 'clases/PHPExcel.php';
						
						require_once 'clases/PHPExcel/Reader/Excel2007.php';
						
						$objReader = new PHPExcel_Reader_Excel2007();
						$objPHPExcel = $objReader->load("tmp_ev_" . $archivo);
						$objFecha = new PHPExcel_Shared_Date();
						$objPHPExcel->setActiveSheetIndex(0);
						$HojaActiva = $objPHPExcel->getActiveSheet();
						$total_filas = $HojaActiva->getHighestRow();
						$lastColumn = $HojaActiva->getHighestColumn();
						$total_columnas = PHPExcel_Cell::columnIndexFromString($lastColumn);
						$UltimaColumna = "A";
						$j = 0;
						$_DATOS_EXCEL = array();
						// Obtengo los nombres de los campos
						for ($i = 0; $i <= $total_columnas; $i++) {
							$_campo[$i] = trim(($HojaActiva->getCell($UltimaColumna . "1")->getCalculatedValue()));
							$UltimaColumna++;
						}
						// Obtengo datos de filas
						for ($fila = 2; $fila <= $total_filas; $fila++) {
							for ($columna = 0; $columna <= $total_columnas - 1; $columna++) {
								// $_DATOS_EXCEL[$j][$_campo[$columna]] = trim($HojaActiva->getCellByColumnAndRow($columna, $fila)->getCalculatedValue());
								$_DATOS_EXCEL[$j][$columna + 1] = (trim($HojaActiva->getCellByColumnAndRow($columna, $fila)->getCalculatedValue()));
							}
							$j++;
						}
					}
					else {
						$error_grave = "Necesitas primero importar el archivo";
					}
					$total_errores = 0;
					$total_actualizar = 0;
					$total_insertar = 0;
					$usuario_inexistente = 0;
					$curso_inexistente = 0;
					$linea = 2;
					for ($l = 1; $l <= count($_DATOS_EXCEL); $l++) {
						$rut = LimpiaRut($_DATOS_EXCEL[$l - 1][1]);
						if ($tipo_audiencia == 1) {
							$estado_inscripcion = "INSCRITO";
							$inscrito = 1;
						}
						elseif ($tipo_audiencia == 2) {
							$estado_inscripcion = "AUTOSIN";
							$inscrito = "";
						}
						elseif ($tipo_audiencia == 3) {
							$estado_inscripcion = "AUTO";
							$inscrito = "";
						}
						$verifca_usuario_en_base = DatosTablaUsuarioPorEmpresaV2($rut, $id_empresa);
						if ($verifca_usuario_en_base) {
							IMPARTICION_InsertaInscripcionUsuariosT($codigo_imparticion, $rut, $id_curso, $id_empresa, $estado_inscripcion, $inscrito);
						}
						else {
							$no_esta_en_tabla_usuario++;
							$cadena_rut_no_estan_en_base .= "$rut" . ";";
						}
					}
				}
			}
			
			if ($_FILES['excel_postulantes']['name'] <> "") {
				$archivo = $_FILES['excel_postulantes']['name'];
				VerificaExtensionFilesAdmin($_FILES["excel_postulantes"]);
				// bloque Postulaciones Excel
				if ($archivo) {
					$tipo = $_FILES['excel_postulantes']['type'];
					$destino = "tmp_ev__postulantes_" . $archivo;
					if (copy($_FILES['excel_postulantes']['tmp_name'], $destino)) {
					}
					else {
						$error_grave = "Error Al subir Archivo<br />";
					}
					if (file_exists("tmp_ev__postulantes_" . $archivo)) {
						// Elimina Usuario en la tabla tbl
						// IMPARTICION_EliminaUsuariosInscripcionUsuario($codigo_imparticion, $id_empresa);
						// IMPARTICION_EliminaPostulantesInscripcionUsuario($codigo_imparticion, $id_empresa);
						require_once 'clases/PHPExcel.php';
						
						require_once 'clases/PHPExcel/Reader/Excel2007.php';
						
						$objReader = new PHPExcel_Reader_Excel2007();
						$objPHPExcel = $objReader->load("tmp_ev__postulantes_" . $archivo);
						$objFecha = new PHPExcel_Shared_Date();
						$objPHPExcel->setActiveSheetIndex(0);
						$HojaActiva = $objPHPExcel->getActiveSheet();
						$total_filas = $HojaActiva->getHighestRow();
						$lastColumn = $HojaActiva->getHighestColumn();
						$total_columnas = PHPExcel_Cell::columnIndexFromString($lastColumn);
						$UltimaColumna = "A";
						$j = 0;
						$_DATOS_EXCEL = array();
						// Obtengo los nombres de los campos
						for ($i = 0; $i <= $total_columnas; $i++) {
							$_campo[$i] = trim(($HojaActiva->getCell($UltimaColumna . "1")->getCalculatedValue()));
							$UltimaColumna++;
						}
						// Obtengo datos de filas
						for ($fila = 2; $fila <= $total_filas; $fila++) {
							for ($columna = 0; $columna <= $total_columnas - 1; $columna++) {
								// $_DATOS_EXCEL[$j][$_campo[$columna]] = trim($HojaActiva->getCellByColumnAndRow($columna, $fila)->getCalculatedValue());
								$_DATOS_EXCEL[$j][$columna + 1] = (trim($HojaActiva->getCellByColumnAndRow($columna, $fila)->getCalculatedValue()));
							}
							$j++;
						}
					}
					else {
						$error_grave = "Necesitas primero importar el archivo";
					}
					$total_errores = 0;
					$total_actualizar = 0;
					$total_insertar = 0;
					$usuario_inexistente = 0;
					$curso_inexistente = 0;
					$linea = 2;
					$tipo_inscripcion = IMPARTICION_DistinctTipovalidacionPorInscripcion($codigo_imparticion, $id_empresa);
					$tipo_validacion = $tipo_inscripcion[0]->valor;
					for ($l = 1; $l <= count($_DATOS_EXCEL); $l++) {
						$rut = LimpiaRut($_DATOS_EXCEL[$l - 1][1]);
						$verifca_usuario_en_base = DatosTablaUsuarioPorEmpresaV2($rut, $id_empresa);
						if ($verifca_usuario_en_base) {
							// si esta en la base, verifico si se encuentra ya en la inscripcion
							$existe_usuario_en_la_tabla_postulaciones = IMPARTICION_PostulantesPorInscripcionDadoRut($codigo_imparticion, $id_empresa, $rut);
							if ($existe_usuario_en_la_tabla_postulaciones) {
								// No hace nada
							}
							else {
								// Si no existe, lo inserta
								InsertaPostulantePorInscripcion($rut, $id_empresa, $codigo_imparticion, $id_curso, $tipo_validacion);
							}
							// InsertaPostulantePorInscripcion($rut, $id_empresa, $codigo_imparticion, $id_curso, $tipo_validacion);
						}
						else {
							$no_esta_en_tabla_usuario_postulante++;
							$cadena_rut_no_estan_en_base_postulante .= "$rut" . ";";
						}
					}
				}
			}
			// fin bloque Postulaciones Excel
		}
		// Subida de Excel
		if ($no_esta_en_tabla_usuario > 0) {
			echo "
    <script>
    location.href='?sw=editImparticion&i=" . Encodear3($codigo_imparticion) . "&cadena=$cadena_rut_no_estan_en_base';
    </script>";
			exit;
		}
		else {
			if ($fechaT1 != '' and $fechaT2 != '' and $horaH1 != '' and $horaH2 != '') {
				UpdateInscripcionUsuarioFechasfromRel($codigo_imparticion, $id_empresa);
				echo "
    <script>
    location.href='?sw=listaInscripcionesT&i=" . Encodear3($id_curso) . "';
    </script>";
				exit;
			}
			else {
				echo "
    <script>
    location.href='?sw=listaInscripciones2&i=" . Encodear3($id_curso) . "';
    </script>";
				exit;
			}
		}
	}
	elseif ($seccion == "editImparticion") {
		$id_empresa = $_SESSION["id_empresa"];
		$id_imparticion = Decodear3($get["i"]);
		
		if (Decodear3($get["sesdel"])) {
			
			$id_seborra = Decodear3($get["sesdel"]);
			DelSesImpEn($id_seborra, $id_empresa);
		}
		
		$cadena_ruts = $get["cadena"];
		$cadena_ruts_postulantes = $get["cadena_post"];
		$arreglo_rut_inexistentes = explode(";", $cadena_ruts);
		$arreglo_rut_inexistentes_postulantes = explode(";", $cadena_ruts_postulantes);
		$datos_imparticion = DatosInscripcionImparticionesV2($id_imparticion, $id_empresa);
		
		
		$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/imparticion/formulario_edita.html"));
		
		$j = 1;
		for ($i = 1; $i < count($arreglo_rut_inexistentes); $i++) {
			$rut_inexistentes .= $arreglo_rut_inexistentes[$i - 1];
			$j++;
			if ($j < count($arreglo_rut_inexistentes)) {
				$rut_inexistentes .= ", ";
			}
		}
		
		$j = 1;
		for ($ik = 1; $ik < count($arreglo_rut_inexistentes_postulantes); $ik++) {
			$rut_inexistentes_postulantes .= $arreglo_rut_inexistentes_postulantes[$ik - 1];
			$j++;
			if ($j < count($arreglo_rut_inexistentes_postulantes)) {
				$rut_inexistentes_postulantes .= ", ";
			}
		}
		
		$formulario = file_get_contents("views/capacitacion/imparticion/formulario_edita_presencial.html");
		$formulario = FormularioImparticion(FuncionesTransversalesAdmin($formulario), $id_empresa, $id_imparticion);
		
		//$usuarios_relatores=DatosTablaUsuarioTodosPorEmpresa($id_empresa);
		$usuarios_relatores = TraeRelatoresPorEmpresa2($id_empresa);
		$options_relatores = "<option value=''> </option>";
		foreach ($usuarios_relatores as $rel) {
			$options_relatores .= "<option value='" . $rel->rut . "'>" . $rel->nombre . "</option>";
		}
		$formulario = str_replace("{OPTIONS_RELATORES}", ($options_relatores), $formulario);
		$PRINCIPAL = str_replace("{BLOQUE_FORMULARIO}", $formulario, $PRINCIPAL);
		
		//Aca hago replace para que se listen las sesiones
		$PRINCIPAL = str_replace("{BLOQUE_SESIONES_CREADAS}", ColocaSesionesPorImparticion($id_empresa, $id_imparticion), $PRINCIPAL);
		
		$PRINCIPAL = str_replace("{ID_EMPRESA}", $_SESSION["id_empresa"], $PRINCIPAL);
		$PRINCIPAL = str_replace("{BLOQUE_DETALLE_CURSO}", ListadoCursosAdmin2(file_get_contents("views/capacitacion/imparticion/entorno_listado_imparticion.html"), $id_empresa, "", $datos_imparticion[0]->id_curso), $PRINCIPAL);
		if ($rut_inexistentes) {
			$PRINCIPAL = str_replace("{BLOQUE_RUTS}", "(" . $rut_inexistentes . " )", $PRINCIPAL);
		}
		else {
			$PRINCIPAL = str_replace("{BLOQUE_RUTS}", "", $PRINCIPAL);
		}
		
		if ($rut_inexistentes_postulantes) {
			$PRINCIPAL = str_replace("{BLOQUE_RUTS_POSTULANTES}", "(" . $rut_inexistentes_postulantes . " )", $PRINCIPAL);
		}
		else {
			$PRINCIPAL = str_replace("{BLOQUE_RUTS_POSTULANTES}", "", $PRINCIPAL);
		}
		
		
		$PRINCIPAL = str_replace("{TOTAL_USUARIOS_POR_INSCRIPCION_QUE_NO_ESTAN_EN_TBL_USUARIO}", $i - 1, $PRINCIPAL);
		$PRINCIPAL = str_replace("{TOTAL_POSTULANTES_POR_INSCRIPCION_QUE_NO_ESTAN_EN_TBL_USUARIO}", $ik - 1, $PRINCIPAL);
		
		$PRINCIPAL = str_replace("{NOMBRE_CURSO_imparticion}", ($datos_imparticion[0]->nombre_curso), $PRINCIPAL);
		
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "addImparticion") {
		$id_empresa = $_SESSION["id_empresa"];
		$id_curso = Decodear3($get["i"]);
		
		if ($id_curso) {
		}
		else {
			$PRINCIPAL = FormularioImparticion(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/imparticionT/formulario_ingresa.html")), $id_empresa);
			$PRINCIPAL = str_replace("{ID_EMPRESA}", $_SESSION["id_empresa"], $PRINCIPAL);
			$PRINCIPAL = ListadoCursosAdmin2(FuncionesTransversalesAdmin($PRINCIPAL), $id_empresa, "", "", "imparticion_solo_preenciales");
			
			$PRINCIPAL = ListadocMallas($PRINCIPAL, $id_empresa, "imparticiones");
		}
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	
	elseif ($seccion == "accionParticipantesImparticion_2022_porMalla") {
		//tbl_id_malla_id_inscripcion_rut
		$id_imparticion = $_SESSION["id_inscripcion_id_malla"];
		$id_empresa = $_SESSION["id_empresa"];
		
		
		$id_empresa = $_SESSION["id_empresa"];
		$insertados = 0;
		VerificaExtensionFilesAdmin($_FILES["file"]);
		if (isset($_FILES["file"])) {
			if ($_FILES["file"]["error"] > 0) {
				echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
			}
			else {
				if (file_exists("upload/" . $_FILES["file"]["name"])) {
				}
				else {
					$storagename = "uploaded_file.txt";
					move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $storagename);
				}
			}
		}
		else {
			echo "No file selected <br />";
		}
		
		if (isset($storagename) && $file = fopen("upload/" . $storagename, "r")) {
			$firstline = fgets($file, 10096);
			//Gets the number of fields, in CSV-files the names of the fields are mostly given in the first line
			$num = strlen($firstline) - strlen(str_replace(";", "", $firstline));
			//save the different fields of the firstline in an array called fields
			$fields = array();
			$fields = explode(";", $firstline, ($num + 1));
			$line = array();
			$i = 0;
			
			while ($line[$i] = fgets($file, 10096)) {
				$dsatz[$i] = array();
				$dsatz[$i] = explode(";", $line[$i], ($num + 1));
				$i++;
			}
			
			$cuentaK = 0;
			for ($k = 0; $k != ($num + 1); $k++) {
				
				$cuentaK++;
			}
			$count_total_loop = count($dsatz);
			
			if ($count_total_loop > 0) {
				BorraUsuariosParaVolveraCrearlos_2022_por_malla($id_imparticion);
			}
			
			$estado_txt = "";
			
			
			foreach ($dsatz as $key => $number) {
				$row_csv = "";
				$cuenta = 0;
				foreach ($number as $k => $content) {
					$content = str_replace('+', '', $content);
					$content = str_replace('=', '', $content);
					$content = str_replace(';', '.', $content);
					$content = addcslashes($content, "'=\\");
					if ($k == "0") {
						$rut = $content;
					}
				}
				
				
				
				$id_curso = $datos_rel_inscripcion[0]->id_curso;
				$id_inscripcion = $id_imparticion_GET;
				$nombre_inscripcion = $datos_rel_inscripcion[0]->nombre_inscripcion;
				$fecha_inicio_inscripcion = $datos_rel_inscripcion[0]->fecha_inicio_inscripcion;
				$fecha_termino_inscripcion = $datos_rel_inscripcion[0]->fecha_termino_inscripcion;
				$id_empresa = $datos_rel_inscripcion[0]->id_empresa;
				$opcional = $datos_rel_inscripcion[0]->opcional;
				$activa = '0';
				$rut_ejecutivo = $datos_rel_inscripcion[0]->rut_ejecutivo;
				
				InsertRutPorIdMallaPorMalla2022($id_imparticion, $rut);
				
				echo "<center><span style='position: absolute;z-index:$current;background:#36C6D3; padding:10px; color:#FFF'>Actualizando $cuenta_loop de $count_total_loop Registros</span></center>";

				
			}

		}
		
		CopiaParticipantes_InscripcionPotencial_($id_imparticion);
		echo "<script>alert('Actualizados los participantes');
    				location.href='?sw=MuestraBloqueCursoSeleccionadoDesdeMalla&i=" . $post["i"] . "&idpbbdd=" . $post["idpbbdd"] . "';</script>";
		exit;
	}
	elseif ($seccion == "accionParticipantesImparticion_2022") {
		$id_imparticion = Decodear3($post["ci"]);
		$id_imparticion_GET = Decodear3($post["ci"]);
		$Imp = DatosCompletosImparticion_2021($id_imparticion_GET);
		$id_modalidad = $Imp[0]->id_modalidad;
		$id_empresa = $_SESSION["id_empresa"];
		$datos_curso = DAtosCursoDadoIdInscripcion($id_imparticion_GET, $id_empresa);
		$datos_rel_inscripcion = data_rel_id_inscripcion_curso_groupby_id_imparticion_data($id_imparticion_GET);
		$id_curso = $datos_curso[0]->id_curso;
		
		$id_empresa = $_SESSION["id_empresa"];
		$insertados = 0;
		VerificaExtensionFilesAdmin($_FILES["file"]);
		if (isset($_FILES["file"])) {
			if ($_FILES["file"]["error"] > 0) {
				echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
			}
			else {
				if (file_exists("upload/" . $_FILES["file"]["name"])) {
				}
				else {
					$storagename = "uploaded_file.txt";
					move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $storagename);
				}
			}
		}
		else {
			echo "No file selected <br />";
		}
		
		if (isset($storagename) && $file = fopen("upload/" . $storagename, "r")) {
			$firstline = fgets($file, 10096);
			//Gets the number of fields, in CSV-files the names of the fields are mostly given in the first line
			$num = strlen($firstline) - strlen(str_replace(";", "", $firstline));
			//save the different fields of the firstline in an array called fields
			$fields = array();
			$fields = explode(";", $firstline, ($num + 1));
			$line = array();
			$i = 0;
			
			while ($line[$i] = fgets($file, 10096)) {
				$dsatz[$i] = array();
				$dsatz[$i] = explode(";", $line[$i], ($num + 1));
				$i++;
			}
			
			$cuentaK = 0;
			for ($k = 0; $k != ($num + 1); $k++) {
				
				$cuentaK++;
			}

			$count_total_loop = count($dsatz);
			
			if ($count_total_loop > 0) {
				BorraUsuariosParaVolveraCrearlos($id_imparticion_GET);
			}
			
			$estado_txt = "";

			
			
			foreach ($dsatz as $key => $number) {
				$row_csv = "";
				$cuenta = 0;
				foreach ($number as $k => $content) {
					$content = str_replace('+', '', $content);
					$content = str_replace('=', '', $content);
					$content = str_replace(';', '.', $content);
					$content = addcslashes($content, "'=\\");
					if ($k == "0") {
						$rut = $content;
					}
				}
				
				
				
				$id_curso = $datos_rel_inscripcion[0]->id_curso;
				$id_inscripcion = $id_imparticion_GET;
				$nombre_inscripcion = $datos_rel_inscripcion[0]->nombre_inscripcion;
				$fecha_inicio_inscripcion = $datos_rel_inscripcion[0]->fecha_inicio_inscripcion;
				$fecha_termino_inscripcion = $datos_rel_inscripcion[0]->fecha_termino_inscripcion;
				$id_empresa = $datos_rel_inscripcion[0]->id_empresa;
				$opcional = $datos_rel_inscripcion[0]->opcional;
				$activa = '0';
				$rut_ejecutivo = $datos_rel_inscripcion[0]->rut_ejecutivo;
				
				Inserta_rel_lms_rut_id_curso_id_inscripcion_2022($rut, $id_curso, $id_inscripcion, $nombre_inscripcion, $fecha_inicio_inscripcion, $fecha_termino_inscripcion, $id_empresa, $opcional, $activa, $rut_ejecutivo);
				
				
				echo "<center><span style='position: absolute;z-index:$current;background:#36C6D3; padding:10px; color:#FFF'>Actualizando $cuenta_loop de $count_total_loop Registros</span></center>";

				
			}

		}
		
		echo "<script>alert('Actualizado los datos. Insertados: " . $insertados . " - Actualizados: " . $actualizados . "');
    				location.href='?sw=VeColaboradoresXImp2021&i=" . Encodear3($id_imparticion) . "';</script>";
		exit;
	}
	elseif ($seccion == "QR_2023") {
		$id_imparticion = Decodear3($get["i"]);
		
		if ($get["download"] == 1) {
			//$Datos_Enc_Detalle=QR_Detalle_Download_Data($id_imparticion);
			$id_encuesta = '90';
			$Header_Preguntas = Reportes_detalle_headers_Preguntas_respuestas($id_encuesta);
			
			
			
			foreach ($Header_Preguntas as $h) {
				$row_header_preguntas .= $h->pregunta . ";";
				if ($h->tipo == "PREGUNTAS_RELATORES") {
					$row_header_preguntas .= "Relator1;Respuesta;Relator2;Respuesta;Relator3;Respuesta;Relator4;Respuesta;Relator5;Respuesta;Relator6;Respuesta;";
				}
			}
			
			
			$row_header_preguntas = ($row_header_preguntas);
			header('Content-Description: File Transfer');
			header('Content-Type: application/csv');
			header("Content-Disposition: attachment; filename=Resultados_Encuesta_Imparticion_" . $codigo_inscripcion . ".csv");
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			echo "rut;nombre;cargo;fecha_ingreso;division;area;departamento;zona;seccion;unidad;region;empresa;rut_jefe;nombre_jefe;id_imparticion;nombre_imparticion;" . $row_header_preguntas . "fecha\r\n";
			
			$UsuariosRespuestas_ = QR_Detalle_Download_Usuarios_por_Imparticion_Data($id_encuesta, $id_imparticion);
			
			foreach ($UsuariosRespuestas_ as $u) {
				
				$row_respuestas_only = "";
				$resp_detalle = QR_Detalle_Download_Usuarios_por_Detalle_Imparticion_Data($id_encuesta, $id_imparticion, $u->rut);
				
				foreach ($resp_detalle as $resp) {
					
					if ($resp->tipo == "PREGUNTAS_RELATORES") {
						$Rel_headers = QR_IdInscripcion_Relatores_List($id_imparticion);
						$num_relator = 0;
						
						$rel_1 = "";
						$rel_R1 = "";
						$rel_2 = "";
						$rel_R2 = "";
						$rel_3 = "";
						$rel_R3 = "";
						$rel_4 = "";
						$rel_R4 = "";
						$rel_5 = "";
						$rel_R5 = "";
						$rel_6 = "";
						$rel_R6 = "";
						$respRelatores = explode(";", $resp->respuesta);
						
						foreach ($Rel_headers as $r) {
							if ($r->relator <> "") {
								$num_relator++;
								if ($num_relator == "1") {
									$rel_1 = $r->relator;
									$rel_R1 = $respRelatores[0];
								}
								if ($num_relator == "2") {
									$rel_2 = $r->relator;
									$rel_R2 = $respRelatores[1];
								}
								if ($num_relator == "3") {
									$rel_3 = $r->relator;
									$rel_R3 = $respRelatores[2];
								}
								if ($num_relator == "4") {
									$rel_4 = $r->relator;
									$rel_R4 = $respRelatores[3];
								}
								if ($num_relator == "5") {
									$rel_5 = $r->relator;
									$rel_R5 = $respRelatores[4];
								}
								if ($num_relator == "6") {
									$rel_6 = $r->relator;
									$rel_R6 = $respRelatores[5];
								}
							}
						}
						
						$row_respuestas_only .= ";$rel_1;$rel_R1;$rel_2;$rel_R2;$rel_3;$rel_R3;$rel_4;$rel_R4;$rel_5;$rel_R5;$rel_6;$rel_R6;";
					}
					else {
						$row_respuestas_only .= $resp->respuesta . ";";
					}
				}
				$row_respuestas_preguntas = $u->rut . ";" . $u->nombre_completo . ";" . $u->cargo . ";" . $u->fecha_ingreso . ";" . $u->division . ";" . $u->area . ";" . $u->departamento . ";" . $u->zona . ";" . $u->seccion . ";" . $u->unidad . ";" . $u->region . ";" . $u->empresa . ";" . $u->rut_jefe . ";" . $u->nombre_jefe . ";" . $u->OTA . ";" . $u->nombre_imparticion . ";" . $row_respuestas_only . "" . $u->fecha . "\r\n";

				echo "" . ($row_respuestas_preguntas);
			}
			exit();
		}

		if (Decodear3($get["ididel"]) <> "") {
			
			EncuestaDeleteEncSat(Decodear3($get["ididel"]));
			
		}
		
		if ($post["crear_enc_satisf"] == "1") {
			QR_InsertRelatorIdImparticion($id_imparticion, "creada");
			
			foreach ($post as $key => $value) {
				// $arr[3] will be updated with each value from $arr...
				if ($key <> "crear_enc_satisf") {
					QR_InsertRelatorIdImparticion($id_imparticion, $key);
				}
			}
		}
		
		$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/imparticion/entorno_listado_QR.html"));
		// veo si existe ya encuesta creada
		$QR_relatores_full = QR_IdInscripcion_Relatores_Full($id_imparticion);
		
		$SesRelatores_seleccionados = QR_IdInscripcion_Sesiones_Relatores($id_imparticion);
		
		foreach ($SesRelatores_seleccionados as $Srs) {
			if ($Srs->relator_seleccionado <> "") {
				$checkbox = "<input type='checkbox' name='" . $Sr->rut_relator . "' value='" . $Sr->rut_relator . "' checked >";
			}
			else {
				$checkbox = "";
			}
			
			$checkbox_ses_relatores_sel .= "
                                    <div class='col-lg-2 bg-white '>" . transformDateSp($Srs->fecha_inicio) . "</div>
                                        <div class='col-lg-2 bg-white '>" . $Srs->hora_inicio . "</div>
                                        <div class='col-lg-2 bg-white '>" . $Srs->hora_termino . "</div>
                                        <div class='col-lg-2 bg-white '>" . ($Srs->nombre_relator) . "</div>
                                        <div class='col-lg-2 bg-white'>" . $Srs->minutos_entre_horas . "</div>
                                        <div class='col-lg-2 bg-white '>" . $checkbox . "</div>
        
        ";
		}
		$PRINCIPAL = str_replace("{CHECKBOX_SES_RELATORES_SELECCIONADOS}", ($checkbox_ses_relatores_sel), $PRINCIPAL);
		
		//eliminar encuesta solo si no hay respuestas
		
		
		$cuenta_respuestas = EncuestaCheckRespuestasEncSat($id_imparticion);
		if ($cuenta_respuestas > 0) {
			$boton_eliminar = "";
		}
		else {
			$boton_eliminar = "<a href='?sw=QR_2023&i=" . $get["i"] . "&ididel=" . $get["i"] . "' class='btn btn-link'>Eliminar Encuesta</a>";
		}
		
		
		$PRINCIPAL = str_replace("{ELIMINAR_ENCUESTA}", ($boton_eliminar), $PRINCIPAL);
		
		$SesRelatores = QR_IdInscripcion_Sesiones_Relatores($id_imparticion);
		
		foreach ($SesRelatores as $Sr) {
			if ($Sr->nombre_relator <> "") {
				$checkbox = "<input type='checkbox' name='" . $Sr->rut_relator . "' value='" . $Sr->rut_relator . "' >";
			}
			else {
				$checkbox = "";
			}
			
			$checkbox_ses_relatores .= "
                                    <div class='col-lg-2 bg-white '>" . transformDateSp($Sr->fecha_inicio) . "</div>
                                        <div class='col-lg-2 bg-white '>" . $Sr->hora_inicio . "</div>
                                        <div class='col-lg-2 bg-white '>" . $Sr->hora_termino . "</div>
                                        <div class='col-lg-2 bg-white '>" . ($Sr->nombre_relator) . "</div>
                                        <div class='col-lg-2 bg-white'>" . $Sr->minutos_entre_horas . "</div>
                                        <div class='col-lg-2 bg-white '>" . $checkbox . "</div>
        
        ";
		}
		$display_none_qr_form = "";
		$display_none_qr = "";
		if ($QR_relatores_full[0]->id > 0) {
			$display_none_qr_form = "display:none !important";
		}
		else {
			$display_none_qr = "display:none !important";
		}
		
		$id_empresa = $_SESSION["id_empresa"];
		$datos_imparticion = DatosInscripcionImparticionesV2($id_imparticion, $id_empresa);
		global $url_front_token_redirect;
		
		$link = "&tipo=enc_sat&idi=" . $get["i"] . "";
		//$link_enc=Encodear3($link);
		$url_qr = $url_front_token_redirect . $link;
		
		$url_token_create_qr = urlencode($url_qr);
		
		$contents = file_get_contents("https://quickchart.io/qr?text=" . $url_token_create_qr . "&size=450");
		$qr_img = "<img src='data:image/png;base64," . base64_encode($contents) . "'>";
		
		$PRINCIPAL = str_replace("{CHECKBOX_SES_RELATORES}", ($checkbox_ses_relatores), $PRINCIPAL);
		$PRINCIPAL = str_replace("{display_none_qr_form}", ($display_none_qr_form), $PRINCIPAL);
		$PRINCIPAL = str_replace("{display_none_qr}", ($display_none_qr), $PRINCIPAL);
		
		$PRINCIPAL = str_replace("{LINK}", ($url_qr), $PRINCIPAL);
		///$PRINCIPAL = str_replace("{IMG}", "<a href='".$contents_api_url."' class='btn btn-link' target='_blank'>Ver QR </a>", $PRINCIPAL);
		$PRINCIPAL = str_replace("{IMAGEN_QR}", $qr_img, $PRINCIPAL);
		$PRINCIPAL = str_replace("{i}", Encodear3($datos_imparticion[0]->id_curso), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_IMPARTICION_DOC}", ($datos_imparticion[0]->codigo_inscripcion), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_IMPARTICION_ENCODEADO}", Encodear3($id_imparticion), $PRINCIPAL);
		$PRINCIPAL = str_replace("{LINK}", ($url_qr), $PRINCIPAL);
		$PRINCIPAL = str_replace("{IMG}", "<a href='" . $apiUrl . "' class='btn btn-link' target='_blank'>Ver QR </a>", $PRINCIPAL);
		$PRINCIPAL = str_replace("{i}", Encodear3($datos_imparticion[0]->id_curso), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_CURSO}", ($datos_imparticion[0]->id_curso), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_CURSO_ENCODEADO}", Encodear3($datos_imparticion[0]->id_curso), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_IMPARTICION_DOC}", ($datos_imparticion[0]->codigo_inscripcion), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_IMPARTICION_ENCODEADO}", Encodear3($id_imparticion), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_INSCRIPCION_ENCODEADO}", Encodear3($id_imparticion), $PRINCIPAL);
		$PRINCIPAL = QR_Dashboard($PRINCIPAL, $id_imparticion);
		$ota_cerrada = OtaCerrada2024($id_imparticion);
		if ($ota_cerrada > 0) {
			$PRINCIPAL = str_replace("{DISPLAY_OTA_ABIERTA}", "display:none!important", $PRINCIPAL);
		}
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
		exit();
	}
	elseif ($seccion == "VeProveedoresXImp2021") {
		$id_imparticion = Decodear3($get["i"]);
		
		if (Decodear3($get["dl"]) <> "") {
			Updatetbl_id_inscripcion_proveedores(Decodear3($get["dl"]));
		}
		
		$id_empresa = $_SESSION["id_empresa"];
		$datos_imparticion = DatosInscripcionImparticionesV2($id_imparticion, $id_empresa);
		$PRINCIPAL = ListaProveedoresPorImparticiones_2021(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/imparticion/entorno_listado_proveedores.html")), $id_imparticion, $_SESSION["id_empresa"]);
		$PRINCIPAL = str_replace("{ID_INSCRIPCION_ENCODEADO}", Encodear3($id_imparticion), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_IMPARTICION_ENCODEADO}", Encodear3($id_imparticion), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_CURSO_ENCODEADO}", Encodear3($datos_imparticion[0]->id_curso), $PRINCIPAL);
		$PRINCIPAL = str_replace("{i}", Encodear3($datos_imparticion[0]->id_curso), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_IMPARTICION_DOC}", ($datos_imparticion[0]->codigo_inscripcion), $PRINCIPAL);
		$PRINCIPAL = str_replace("{NOMBRE_MODALIDAD_DOC}", ($datos_imparticion[0]->modalidad), $PRINCIPAL);
		$PRINCIPAL = str_replace("{NOMBRE_IMPARTICION}", ($datos_imparticion[0]->nombre), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_CURSO_DOC}", ($datos_imparticion[0]->id_curso), $PRINCIPAL);
		$PRINCIPAL = str_replace("{NOMBRE_CURSO_DOC}", ($datos_imparticion[0]->nombre_curso), $PRINCIPAL);
		$PRINCIPAL = str_replace("{DATOS}", ($datos), $PRINCIPAL);
		
		
		$documentos = "";
		$cuentadoc = 0;
		$checked = "";
		
		$otecs = TraeOtec2021($id_empresa);
		$options_otec = "";
		foreach ($otecs as $otec) {
			$options_otec .= '<option value="' . $otec->rut . '" ' . $checked . '>' . ($otec->nombre) . '</option>';
		}
		$PRINCIPAL = str_replace("{OPTIONS_OTEC}", ($options_otec), $PRINCIPAL);
		
		$servicios = Servicios_lista_option();
		$options_servicios = "";
		foreach ($servicios as $s) {
			$options_servicio .= '<option value="' . $s->servic_id . '" ' . $checked . '>' . ($s->servic_dsc) . '</option>';
		}
		$PRINCIPAL = str_replace("{OPTIONS_SERVICIO}", ($options_servicio), $PRINCIPAL);
		
		$proveedores_servicios = Servicios_Proveedores_ListaServicios_2024($id_imparticion);
		
		foreach ($proveedores_servicios as $d) {
			$cuentaser++;
			$proveedores_lista .= "
        <div class='col-lg-7'>" . ($d->nombre_proveedor) . "</div>
        <div class='col-lg-3'>" . ($d->servicio) . " " . ($d->servic_otro) . "</div>
        <div class='col-lg-2'><a href='?sw=VeProveedoresXImp2021&i=" . Encodear3($id_imparticion) . "&dl=" . Encodear3($d->id) . "'  class='btn btn-link'>Eliminar</a></div>";
		}
		if ($cuentaser > 0) {
		}
		else {
			$proveedores_lista .= "<div class='col-lg-12'>Aun no existen proveedores para esta OTA</div>";
		}
		$PRINCIPAL = str_replace("{LISTA_PROVEEDORES_POR_IMPARTICION}", ($proveedores_lista), $PRINCIPAL);
		$PRINCIPAL = str_replace("{OPTIONS_SERVICIO}", ($options_servicio), $PRINCIPAL);
		$ota_cerrada = OtaCerrada2024(Decodear3($get["i"]));
		if ($ota_cerrada > 0) {
			$PRINCIPAL = str_replace("{DISPLAY_OTA_ABIERTA}", "display:none!important", $PRINCIPAL);
		}
		$PRINCIPAL = str_replace("{LISTA_DOCUMENTOS_POR_IMPARTICION}", ($documentos), $PRINCIPAL);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "VeNotificarCuadroContable") {
		$id_imparticion = Decodear3($get["i"]);
		
		if (Decodear3($get["dl"]) <> "") {
			Updatetbl_id_inscripcion_proveedores(Decodear3($get["dl"]));
		}
		
		$id_empresa = $_SESSION["id_empresa"];
		$datos_imparticion = DatosInscripcionImparticionesV2($id_imparticion, $id_empresa);
		$PRINCIPAL = ListaCuadroContablePorImparticiones_2021(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/imparticion/entorno_listado_notificacion.html")), $id_imparticion, $_SESSION["id_empresa"]);
		$PRINCIPAL = str_replace("{ID_INSCRIPCION_ENCODEADO}", Encodear3($id_imparticion), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_IMPARTICION_ENCODEADO}", Encodear3($id_imparticion), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_CURSO_ENCODEADO}", Encodear3($datos_imparticion[0]->id_curso), $PRINCIPAL);
		$PRINCIPAL = str_replace("{i}", Encodear3($datos_imparticion[0]->id_curso), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_IMPARTICION_DOC}", ($datos_imparticion[0]->codigo_inscripcion), $PRINCIPAL);
		$PRINCIPAL = str_replace("{NOMBRE_MODALIDAD_DOC}", ($datos_imparticion[0]->modalidad), $PRINCIPAL);
		$PRINCIPAL = str_replace("{NOMBRE_IMPARTICION}", ($datos_imparticion[0]->nombre), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_CURSO_DOC}", ($datos_imparticion[0]->id_curso), $PRINCIPAL);
		$PRINCIPAL = str_replace("{NOMBRE_CURSO_DOC}", ($datos_imparticion[0]->nombre_curso), $PRINCIPAL);
		$PRINCIPAL = str_replace("{DATOS}", ($datos), $PRINCIPAL);
		$documentos = "";
		$cuentadoc = 0;
		$checked = "";
		
		$DataContable = DataContable($id_imparticion);
		
		$PRINCIPAL = str_replace("{DATA_CONTABLE_1}", ($DataContable[0]->CUENTA), $PRINCIPAL);
		$PRINCIPAL = str_replace("{DATA_CONTABLE_2}", ($DataContable[0]->CUENTA_GLOSA), $PRINCIPAL);
		$PRINCIPAL = str_replace("{DATA_CONTABLE_3}", ($DataContable[0]->CUI), $PRINCIPAL);
		$PRINCIPAL = str_replace("{DATA_CONTABLE_4}", ($DataContable[0]->nombre), $PRINCIPAL);
		$PRINCIPAL = str_replace("{DATA_CONTABLE_5}", ($DataContable[0]->codigo_inscripcion), $PRINCIPAL);
		$PRINCIPAL = str_replace("{DATA_CONTABLE_6}", ($DataContable[0]->PROYECTO), $PRINCIPAL);
		
		$PRINCIPAL = str_replace("{EMAIL1}", ($DataContable[0]->EMAIL1), $PRINCIPAL);
		$PRINCIPAL = str_replace("{EMAIL2}", ($DataContable[0]->EMAIL2), $PRINCIPAL);
		
		$PRINCIPAL = str_replace("{LISTA_DOCUMENTOS_POR_IMPARTICION}", ($documentos), $PRINCIPAL);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "accionEnviaNotificacionPorImparticion_2021") {
		$subject = "" . $post["OTA"] . " " . $post["OTA_NOMBRE"];
		$titulo = "" . $post["OTA"] . " " . $post["OTA_NOMBRE"];
		
		$rut = $usuario->rut;
		$email = $post["email1"];
		$email2 = $post["email2"];
		$nombre_completo = $usuario->nombre;
		
		$to = $email;
		$nombreto = $email;
		
		$to2 = $email2;
		$nombreto2 = $email2;
		
		$url = $usuario->url;
		$texto_url = $usuario->texto_url;
		$subtitulo = "<br>
Cuadro Contable<br>
<strong>1.- Informaci&oacute;n para contabilización &Aacute;rea de Administraci&oacute;n:</strong>
                                        <br><br>
                                        Nro de Cuenta: " . $post["DATA_CONTABLE_1"] . "<br>
                                        Nombre de la Cuenta: " . $post["DATA_CONTABLE_2"] . "<br>
                                        Centro de Costo: " . $post["DATA_CONTABLE_3"] . "<br>
                                        <br><br>
                                            <strong>2.- Informaci&oacute;n de uno interno por Depto. Desarrollo y Formaci&oacute;n:</strong>
                                        <br><br>
                                        Nombre de la OTA: " . $post["DATA_CONTABLE_4"] . "<br>
                                        Nro de OTA: " . $post["DATA_CONTABLE_5"] . "<br>
                                        Nombre proyecto o División: " . $post["DATA_CONTABLE_6"] . "<br>";
		
		SendGrid_Email($to, $nombreto, $from, $nombrefrom, $tipo, $subject, $titulo, $subtitulo, $texto, $url, $texto_url, $texto2, $texto3, $texto4, $logo, $id_empresa, $url2, "Email_Masivo", $rut, $id_notificacion);
		SendGrid_Email($to2, $nombreto2, $from, $nombrefrom, $tipo, $subject, $titulo, $subtitulo, $texto, $url, $texto_url, $texto2, $texto3, $texto4, $logo, $id_empresa, $url2, "Email_Masivo", $rut, $id_notificacion);
		exit();
	}
	elseif ($seccion == "accionSubeProveedorPorImparticion_2021") {
		
		if ($post["servicio_otorgado"] == 8) {
		}
		else {
			$post["otros_input"] = "";
		}
		InsertProveedorImparticion2024(Decodear3($get["i"]), $post["proveedor"], $post["servicio_otorgado"], $post["otros_input"]);
		$i = isset($get["i"]) ? htmlspecialchars($get["i"], ENT_QUOTES) : ''; // Sanitizar
		echo "<script>location.href='?sw=VeProveedoresXImp2021&i=" . $i . "';</script>";
		exit();
	}
	elseif ($seccion == "VeDocsXImp2021") {
		$id_imparticion = Decodear3($get["i"]);
		
		if (Decodear3($get["dl"]) <> "") {
			Updatetbl_id_inscripcion_documentos(Decodear3($get["dl"]), "0");
		}
		
		$id_empresa = $_SESSION["id_empresa"];
		$datos_imparticion = DatosInscripcionImparticionesV2($id_imparticion, $id_empresa);
		$PRINCIPAL = ListaDocsPorImparticiones_2021(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/imparticion/entorno_listado_documentos.html")), $id_imparticion, $_SESSION["id_empresa"]);
		$PRINCIPAL = str_replace("{ID_INSCRIPCION_ENCODEADO}", Encodear3($id_imparticion), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_IMPARTICION_ENCODEADO}", Encodear3($id_imparticion), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_CURSO_ENCODEADO}", Encodear3($datos_imparticion[0]->id_curso), $PRINCIPAL);
		$PRINCIPAL = str_replace("{i}", Encodear3($datos_imparticion[0]->id_curso), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_IMPARTICION_DOC}", ($datos_imparticion[0]->codigo_inscripcion), $PRINCIPAL);
		$PRINCIPAL = str_replace("{NOMBRE_MODALIDAD_DOC}", ($datos_imparticion[0]->modalidad), $PRINCIPAL);
		$PRINCIPAL = str_replace("{NOMBRE_IMPARTICION}", ($datos_imparticion[0]->nombre), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_CURSO_DOC}", ($datos_imparticion[0]->id_curso), $PRINCIPAL);
		$PRINCIPAL = str_replace("{NOMBRE_CURSO_DOC}", ($datos_imparticion[0]->nombre_curso), $PRINCIPAL);
		$PRINCIPAL = str_replace("{DATOS}", ($datos), $PRINCIPAL);
		$documentos = "";
		$cuentadoc = 0;
		$array_docs = Selecttbl_id_inscripcion_documentos($id_imparticion);
		foreach ($array_docs as $d) {
			$cuentadoc++;
			$documentos .= "
        <div class='col-lg-10'><a href='upload/" . $d->documento . "' target='_blank' class='btn btn-link'>" . ($d->nombre) . "</a></div>
        <div class='col-lg-2'><a href='?sw=VeDocsXImp2021&i=" . Encodear3($id_imparticion) . "&dl=" . Encodear3($d->id) . "'  class='btn btn-link'>Eliminar</a></div>";
		}
		if ($cuentadoc > 0) {
		}
		else {
			$documentos .= "<div class='col-lg-12'>Aun no existen documentos para esta OTA</div>";
		}
		$PRINCIPAL = str_replace("{LISTA_DOCUMENTOS_POR_IMPARTICION}", ($documentos), $PRINCIPAL);
		
		$ota_cerrada = OtaCerrada2024($id_imparticion);
		if ($ota_cerrada > 0) {
			$PRINCIPAL = str_replace("{DISPLAY_OTA_ABIERTA}", "display:none!important", $PRINCIPAL);
		}
		
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "VeColaboradoresXImp2021") {
		$id_imparticion = Decodear3($get["i"]);
		
		$excel = $get["exc"];
		$id_empresa = $_SESSION["id_empresa"];
		
		if (Decodear3($get["del_rut_enc"]) <> "") {
						DeleteInscripcionUsuarios_2022($id_imparticion, Decodear3($get["del_rut_enc"]));
			echo "<script>alert('Participante " . Decodear3($get["del_rut_enc"]) . " eliminado de la imparticion');</script>";
		}
		UpdateMinimo_asistenciaMinimo_nota_aprobacion_2021($id_imparticion);
		if ($get["dl"] <> "") {
			
			actualizaArchivoImparticionRut_Id_2021($get["dl"]);
		}
		$datos_imparticion = DatosInscripcionImparticionesV2($id_imparticion, $id_empresa);
		if ($post["rut_col"] <> "") {
			$hoy = date("Y-m-d");
			
			$Usu = TraeDatosUsuario($post["rut_col"]);
			if ($Usu[0]->rut <> "") {
				InsertNewInscripcionUsuarios_2022($datos_imparticion[0]->codigo_inscripcion, $post["rut_col"], $datos_imparticion[0]->id_curso, $datos_imparticion[0]->id_empresa, $hoy, "presencial");
			}
			else {
				echo "<script>alert('Rut no fue encontrado en la Base de Datos'); </script>";
			}
			
		}
		if ($datos_imparticion[0]->id_modalidad == "1") {
			Update_rel_lms_id_curso_id_inscripcion_IdMalla_Programa_foco_2022($datos_imparticion[0]->codigo_inscripcion, $datos_imparticion[0]->id_curso);
			CheckUsuariosInscritosQueNoEstenPreInscritos($datos_imparticion[0]->codigo_inscripcion, $datos_imparticion[0]->id_curso);
		}
		//Funcion que llena Id Malla, Id Programa, Id Foco para modalidad 1.
		if ($excel == 1) {
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=" . $id_imparticion . "_Asistencia.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			$PRINCIPAL = ListaColaboradoresPorImparticiones_2021(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/imparticion/entorno_listado_colaboradores_sesiones_excel.html")), $id_imparticion, $_SESSION["id_empresa"], $excel);
			echo CleanHTMLWhiteList($PRINCIPAL);
			exit;
			exit;
		}
		else {
			if ($datos_imparticion[0]->id_modalidad == "1") {
				$PRINCIPAL = ListaColaboradoresPorImparticiones_2021(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/imparticion/entorno_listado_colaboradores_sesiones_1.html")), $id_imparticion, $_SESSION["id_empresa"]);
				$cuenta_potenciales = SumaparticipantesPotenciales_idInscripcion_2022($datos_imparticion[0]->id_curso, $id_imparticion);
				$inscritos_potenciales = "
	    				<span class='label label-sm label-info' style='margin-bottom: 1px;'> PreInscritos </span>
	    				" . $cuenta_potenciales . "
	    				<br>
	    				";
				$PRINCIPAL = str_replace("{INSCRITOS_POTENCIALES}", ($inscritos_potenciales), $PRINCIPAL);
			}
			else {
				$PRINCIPAL = ListaColaboradoresPorImparticiones_2021(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/imparticion/entorno_listado_colaboradores_sesiones.html")), $id_imparticion, $_SESSION["id_empresa"], "");
				$ota_cerrada = OtaCerrada2024($id_imparticion);
				if ($ota_cerrada > 0) {
					$PRINCIPAL = str_replace("{DISPLAY_OTA_ABIERTA}", "display:none!important", $PRINCIPAL);
				}
			}
		}
		$PRINCIPAL = str_replace("{ID_INSCRIPCION_ENCODEADO}", Encodear3($id_imparticion), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_IMPARTICION_ENCODEADO}", Encodear3($id_imparticion), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_CURSO_ENCODEADO}", Encodear3($datos_imparticion[0]->id_curso), $PRINCIPAL);
		$PRINCIPAL = str_replace("{i}", Encodear3($datos_imparticion[0]->id_curso), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_MODALIDAD}", ($datos_imparticion[0]->codigo_inscripcion), $PRINCIPAL);
		$PRINCIPAL = str_replace("{NOMBRE_MODALIDAD}", ($datos_imparticion[0]->modalidad), $PRINCIPAL);
		$PRINCIPAL = str_replace("{NOMBRE_IMPARTICION}", ($datos_imparticion[0]->nombre), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_CURSO}", ($datos_imparticion[0]->id_curso), $PRINCIPAL);
		$PRINCIPAL = str_replace("{NOMBRE_CURSO}", ($datos_imparticion[0]->nombre_curso), $PRINCIPAL);
		if ($datos_imparticion[0]->minimo_asistencia <> "") {
			
			$minimo_asistencia = "<br><span class='label label-sm label-info' style='    margin-bottom: 1px;'> Asistencia M&iacute;nima </span> " . $datos_imparticion[0]->minimo_asistencia . "%";
		}
		else {
			
			$minimo_asistencia = "";
		}
		if ($datos_imparticion[0]->minimo_nota_aprobacion <> "") {
			$nota_minima_aprobacion = "<br><span class='label label-sm label-info' style='    margin-bottom: 1px;'> Nota Aprobaci&oacute;n </span> " . $datos_imparticion[0]->minimo_nota_aprobacion . "%";
		}
		else {
			$nota_minima_aprobacion = "";
		}
		$PRINCIPAL = str_replace("{NOTA_MINIMA_APROBACION}", ($nota_minima_aprobacion), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ASISTENCIA_MINIMA}", ($minimo_asistencia), $PRINCIPAL);
		$datos .= "Inicio: " . $datos_imparticion[0]->fecha_inicio . " - T&eacute;rmino: " . $datos_imparticion[0]->fecha_termino . "";
		$PRINCIPAL = str_replace("{DATOS}", ($datos), $PRINCIPAL);
		Insert_Imparticion_Tbl_Usuarios_Datos_Dotacion_FN_2023($id_imparticion);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "LibroClasesXImp2021") {
		$id_imparticion = Decodear3($get["i"]);
		
		$excel = $get["exc"];
		$id_empresa = $_SESSION["id_empresa"];
		$PRINCIPAL = ListaColaboradoresPorImparticiones_LibroClases_2024(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/imparticion/entorno_libro_clases.html")), $id_imparticion, $_SESSION["id_empresa"], "");
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "VeReportesXImp2021") {
		$id_imparticion = Decodear3($get["i"]);
		$id_curso = Decodear3($get["idc"]);
		$enc_sat = ($get["EncSat"]);
		$excel = $get["exc"];
		$id_empresa = $_SESSION["id_empresa"];
		
		$datos_imparticion = DatosInscripcionImparticionesV2($id_imparticion, $id_empresa);
		
		$Imp = DatosCompletosImparticion_2021($id_imparticion);
		$PRINCIPAL = VeReportesXImp2021_fn(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/reportes/entorno.html")), $id_imparticion, $datos_imparticion[0]->id_curso, $rut, $id_empresa, $enc_sat);
		$PRINCIPAL = str_replace("{ID_MODALIDAD_BACK}", $Imp[0]->id_modalidad, $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_IMPARTICION_ENCODEADO}", Encodear3($id_imparticion), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_CURSO_ENCODEADO}", Encodear3($datos_imparticion[0]->id_curso), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_MODALIDAD}", ($datos_imparticion[0]->codigo_inscripcion), $PRINCIPAL);
		$PRINCIPAL = str_replace("{NOMBRE_MODALIDAD}", ($datos_imparticion[0]->modalidad), $PRINCIPAL);
		$PRINCIPAL = str_replace("{NOMBRE_IMPARTICION}", ($datos_imparticion[0]->nombre), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_CURSO}", ($datos_imparticion[0]->id_curso), $PRINCIPAL);
		$PRINCIPAL = str_replace("{NOMBRE_CURSO}", ($datos_imparticion[0]->nombre_curso), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_INSCRIPCION_ENCODEADO}", Encodear3($id_imparticion), $PRINCIPAL);
		$PRINCIPAL = str_replace("{i}", Encodear3($datos_imparticion[0]->id_curso), $PRINCIPAL);
		
		$datos .= "Inicio: " . $datos_imparticion[0]->fecha_inicio . " - T&eacute;rmino: " . $datos_imparticion[0]->fecha_termino . "";
		$PRINCIPAL = str_replace("{DATOS}", ($datos), $PRINCIPAL);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "VeColaboradoresXImpXsess") {
		$id_imparticion = Decodear3($get["i"]);
		$id_curso = Decodear3($i);
		$excel = $get["exc"];
		$id_empresa = $_SESSION["id_empresa"];
		
		if ($excel == 1) {
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=" . $id_imparticion . "_Asistencia.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			
			$PRINCIPAL = ListaColaboradoresPorImparticionesSesiones(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/imparticion/entorno_listado_colaboradores_sesiones_excel.html")), $id_imparticion, $_SESSION["id_empresa"], $excel);
			echo CleanHTMLWhiteList($PRINCIPAL);
			exit;
			exit;
		}
		else {
			$PRINCIPAL = ListaColaboradoresPorImparticionesSesiones(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/imparticion/entorno_listado_colaboradores_sesiones.html")), $id_imparticion, $_SESSION["id_empresa"]);
		}
		
		$datos_imparticion = DatosInscripcionImparticionesV2($id_imparticion, $id_empresa);
		
		$PRINCIPAL = str_replace("{ID_IMPARTICION_ENCODEADO}", Encodear3($id_imparticion), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_CURSO_ENCODEADO}", Encodear($datos_imparticion[0]->id_curso), $PRINCIPAL);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "descargarInscritosPorImpart_2021") {
		$codigo_inscripcion = Decodear3($get["ci"]);
		$sp = $get["sp"];
		
		$id_empresa = $_SESSION["id_empresa"];
		$datos_curso = DAtosCursoDadoIdInscripcion($codigo_inscripcion, $id_empresa);
		
				header('Content-Description: File Transfer');
		header('Content-Type: application/csv');
		
		if ($sp == "1") {
			header("Content-Disposition: attachment; filename=Participantes_ID_Imparticion_" . $codigo_inscripcion . ".csv");
		}
		else {
			header("Content-Disposition: attachment; filename=Asistencia_ID_Imparticion_" . $codigo_inscripcion . ".csv");
		}
		
		//header("Content-Disposition: attachment; filename=Participantes_ID_Imparticion_" . $codigo_inscripcion . ".csv");
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		
		$Num_sesiones = ImparticionesNumSesiones_2021($codigo_inscripcion);
		
		if ($sp == "1") {
			echo "rut\r\n";
		}
		else {
			$rut_sesiones_s = "";
			if ($Num_sesiones == "12") {
				$rut_sesiones_s .= ";";
				$rut_sesiones_s .= "asistencia_s1_(SI);";
				$rut_sesiones_s .= "asistencia_s2_(SI);";
				$rut_sesiones_s .= "asistencia_s3_(SI);";
				$rut_sesiones_s .= "asistencia_s4_(SI);";
				$rut_sesiones_s .= "asistencia_s5_(SI);";
				$rut_sesiones_s .= "asistencia_s6_(SI);";
				$rut_sesiones_s .= "asistencia_s7_(SI);";
				$rut_sesiones_s .= "asistencia_s8_(SI);";
				$rut_sesiones_s .= "asistencia_s9_(SI);";
				$rut_sesiones_s .= "asistencia_s10_(SI);";
				$rut_sesiones_s .= "asistencia_s11_(SI);";
				$rut_sesiones_s .= "asistencia_s12_(SI);";
			}
			if ($Num_sesiones == "11") {
				$rut_sesiones_s .= ";";
				$rut_sesiones_s .= "asistencia_s1_(SI);";
				$rut_sesiones_s .= "asistencia_s2_(SI);";
				$rut_sesiones_s .= "asistencia_s3_(SI);";
				$rut_sesiones_s .= "asistencia_s4_(SI);";
				$rut_sesiones_s .= "asistencia_s5_(SI);";
				$rut_sesiones_s .= "asistencia_s6_(SI);";
				$rut_sesiones_s .= "asistencia_s7_(SI);";
				$rut_sesiones_s .= "asistencia_s8_(SI);";
				$rut_sesiones_s .= "asistencia_s9_(SI);";
				$rut_sesiones_s .= "asistencia_s10_(SI);";
				$rut_sesiones_s .= "asistencia_s11_(SI);";
			}
			if ($Num_sesiones == "10") {
				$rut_sesiones_s .= ";";
				$rut_sesiones_s .= "asistencia_s1_(SI);";
				$rut_sesiones_s .= "asistencia_s2_(SI);";
				$rut_sesiones_s .= "asistencia_s3_(SI);";
				$rut_sesiones_s .= "asistencia_s4_(SI);";
				$rut_sesiones_s .= "asistencia_s5_(SI);";
				$rut_sesiones_s .= "asistencia_s6_(SI);";
				$rut_sesiones_s .= "asistencia_s7_(SI);";
				$rut_sesiones_s .= "asistencia_s8_(SI);";
				$rut_sesiones_s .= "asistencia_s9_(SI);";
				$rut_sesiones_s .= "asistencia_s10_(SI);";
			}
			if ($Num_sesiones == "9") {
				$rut_sesiones_s .= ";";
				$rut_sesiones_s .= "asistencia_s1_(SI);";
				$rut_sesiones_s .= "asistencia_s2_(SI);";
				$rut_sesiones_s .= "asistencia_s3_(SI);";
				$rut_sesiones_s .= "asistencia_s4_(SI);";
				$rut_sesiones_s .= "asistencia_s5_(SI);";
				$rut_sesiones_s .= "asistencia_s6_(SI);";
				$rut_sesiones_s .= "asistencia_s7_(SI);";
				$rut_sesiones_s .= "asistencia_s8_(SI);";
				$rut_sesiones_s .= "asistencia_s9_(SI);";
			}
			if ($Num_sesiones == "8") {
				$rut_sesiones_s .= ";";
				$rut_sesiones_s .= "asistencia_s1_(SI);";
				$rut_sesiones_s .= "asistencia_s2_(SI);";
				$rut_sesiones_s .= "asistencia_s3_(SI);";
				$rut_sesiones_s .= "asistencia_s4_(SI);";
				$rut_sesiones_s .= "asistencia_s5_(SI);";
				$rut_sesiones_s .= "asistencia_s6_(SI);";
				$rut_sesiones_s .= "asistencia_s7_(SI);";
				$rut_sesiones_s .= "asistencia_s8_(SI);";
			}
			if ($Num_sesiones == "7") {
				$rut_sesiones_s .= ";";
				$rut_sesiones_s .= "asistencia_s1_(SI);";
				$rut_sesiones_s .= "asistencia_s2_(SI);";
				$rut_sesiones_s .= "asistencia_s3_(SI);";
				$rut_sesiones_s .= "asistencia_s4_(SI);";
				$rut_sesiones_s .= "asistencia_s5_(SI);";
				$rut_sesiones_s .= "asistencia_s6_(SI);";
				$rut_sesiones_s .= "asistencia_s7_(SI);";
			}
			if ($Num_sesiones == "6") {
				$rut_sesiones_s .= ";";
				$rut_sesiones_s .= "asistencia_s1_(SI);";
				$rut_sesiones_s .= "asistencia_s2_(SI);";
				$rut_sesiones_s .= "asistencia_s3_(SI);";
				$rut_sesiones_s .= "asistencia_s4_(SI);";
				$rut_sesiones_s .= "asistencia_s5_(SI);";
				$rut_sesiones_s .= "asistencia_s6_(SI);";
			}
			if ($Num_sesiones == "5") {
				$rut_sesiones_s .= ";";
				$rut_sesiones_s .= "asistencia_s1_(SI);";
				$rut_sesiones_s .= "asistencia_s2_(SI);";
				$rut_sesiones_s .= "asistencia_s3_(SI);";
				$rut_sesiones_s .= "asistencia_s4_(SI);";
				$rut_sesiones_s .= "asistencia_s5_(SI);";
			}
			
			if ($Num_sesiones == "4") {
				$rut_sesiones_s .= ";";
				$rut_sesiones_s .= "asistencia_s1_(SI);";
				$rut_sesiones_s .= "asistencia_s2_(SI);";
				$rut_sesiones_s .= "asistencia_s3_(SI);";
				$rut_sesiones_s .= "asistencia_s4_(SI);";
			}
			if ($Num_sesiones == "3") {
				$rut_sesiones_s .= ";";
				$rut_sesiones_s .= "asistencia_s1_(SI);";
				$rut_sesiones_s .= "asistencia_s2_(SI);";
				$rut_sesiones_s .= "asistencia_s3_(SI);";
			}
			if ($Num_sesiones == "2") {
				$rut_sesiones_s .= ";";
				$rut_sesiones_s .= "asistencia_s1_(SI);";
				$rut_sesiones_s .= "asistencia_s2_(SI);";
			}
			if ($Num_sesiones == "1") {
				$rut_sesiones_s .= ";";
				$rut_sesiones_s .= "asistencia_s1_(SI);";
			}
			
			if ($Num_sesiones > 0) {
				echo "rut;nota_diagnostico_(0_100);nota_final(0_100)" . $rut_sesiones_s . "\r\n";
			}
			else {
				echo "rut;asistencia_(0_100);nota_diagnostico_(0_100);nota_final(0_100)\r\n";
			}
		}
		$total_usuarios_ = TraigoRegistrosPorSesionDeCheckinPorImparticion_2021($codigo_inscripcion, $id_empresa);
		
		foreach ($total_usuarios_ as $unico) {
			$row_csv_sesiones = "";
			$check_s1 = "";
			$check_s2 = "";
			$check_s3 = "";
			$check_s4 = "";
			$check_s5 = "";
			$check_s6 = "";
			$check_s7 = "";
			$check_s8 = "";
			$check_s9 = "";
			$check_s10 = "";
			$check_s11 = "";
			$check_s12 = "";
			
			if ($Num_sesiones > 0) {
				$check_s1 = Asistencia2021_Avance_Sesiones($unico->rut, $codigo_inscripcion, "1");
				if ($check_s1 == "checked") {
					$row_csv_sesiones .= "SI;";
				}
				else {
					$row_csv_sesiones .= "NO;";
				}
			}
			if ($Num_sesiones > 1) {
				$check_s2 = Asistencia2021_Avance_Sesiones($unico->rut, $codigo_inscripcion, "2");
				if ($check_s2 == "checked") {
					$row_csv_sesiones .= "SI;";
				}
				else {
					$row_csv_sesiones .= "NO;";
				}
			}
			if ($Num_sesiones > 2) {
				$check_s3 = Asistencia2021_Avance_Sesiones($unico->rut, $codigo_inscripcion, "3");
				if ($check_s3 == "checked") {
					$row_csv_sesiones .= "SI;";
				}
				else {
					$row_csv_sesiones .= "NO;";
				}
			}
			if ($Num_sesiones > 3) {
				$check_s4 = Asistencia2021_Avance_Sesiones($unico->rut, $codigo_inscripcion, "4");
				if ($check_s4 == "checked") {
					$row_csv_sesiones .= "SI;";
				}
				else {
					$row_csv_sesiones .= "NO;";
				}
			}
			if ($Num_sesiones > 4) {
				$check_s5 = Asistencia2021_Avance_Sesiones($unico->rut, $codigo_inscripcion, "5");
				if ($check_s5 == "checked") {
					$row_csv_sesiones .= "SI;";
				}
				else {
					$row_csv_sesiones .= "NO;";
				}
			}
			if ($Num_sesiones > 5) {
				$check_s6 = Asistencia2021_Avance_Sesiones($unico->rut, $codigo_inscripcion, "6");
				if ($check_s6 == "checked") {
					$row_csv_sesiones .= "SI;";
				}
				else {
					$row_csv_sesiones .= "NO;";
				}
			}
			if ($Num_sesiones > 6) {
				$check_s7 = Asistencia2021_Avance_Sesiones($unico->rut, $codigo_inscripcion, "7");
				if ($check_s7 == "checked") {
					$row_csv_sesiones .= "SI;";
				}
				else {
					$row_csv_sesiones .= "NO;";
				}
			}
			if ($Num_sesiones > 7) {
				$check_s8 = Asistencia2021_Avance_Sesiones($unico->rut, $codigo_inscripcion, "8");
				if ($check_s8 == "checked") {
					$row_csv_sesiones .= "SI;";
				}
				else {
					$row_csv_sesiones .= "NO;";
				}
			}
			if ($Num_sesiones > 8) {
				$check_s9 = Asistencia2021_Avance_Sesiones($unico->rut, $codigo_inscripcion, "9");
				if ($check_s9 == "checked") {
					$row_csv_sesiones .= "SI;";
				}
				else {
					$row_csv_sesiones .= "NO;";
				}
			}
			if ($Num_sesiones > 9) {
				$check_s10 = Asistencia2021_Avance_Sesiones($unico->rut, $codigo_inscripcion, "10");
				if ($check_s10 == "checked") {
					$row_csv_sesiones .= "SI;";
				}
				else {
					$row_csv_sesiones .= "NO;";
				}
			}
			if ($Num_sesiones > 10) {
				$check_s11 = Asistencia2021_Avance_Sesiones($unico->rut, $codigo_inscripcion, "11");
				if ($check_s11 == "checked") {
					$row_csv_sesiones .= "SI;";
				}
				else {
					$row_csv_sesiones .= "NO;";
				}
			}
			if ($Num_sesiones > 11) {
				$check_s12 = Asistencia2021_Avance_Sesiones($unico->rut, $codigo_inscripcion, "12");
				if ($check_s12 == "checked") {
					$row_csv_sesiones .= "SI;";
				}
				else {
					$row_csv_sesiones .= "NO;";
				}
			}
			
			if ($sp == "1") {
				echo "" . $unico->rut . "\r\n";
			}
			else {
				if ($Num_sesiones > 0) {
					echo "" . $unico->rut . ";" . $unico->nota_diagnostico . ";" . $unico->nota . ";" . $row_csv_sesiones . "\r\n";
				}
				else {
					echo "" . $unico->rut . ";" . $unico->asistencia . ";" . $unico->nota_diagnostico . ";" . $unico->nota . "\r\n";
				}
			}
		}
		exit();
	}
	
	elseif ($seccion == "descargarReportesPorImpart_2023") {
		$codigo_inscripcion = Decodear3($get["ci"]);
		$sp = $get["sp"];
		
		$id_empresa = $_SESSION["id_empresa"];
		$datos_curso = DAtosCursoDadoIdInscripcion($codigo_inscripcion, $id_empresa);
		
				header('Content-Description: File Transfer');
		header('Content-Type: application/csv');
		
		if ($sp == "1") {
			header("Content-Disposition: attachment; filename=Resultados_ID_Imparticion_" . $codigo_inscripcion . ".csv");
		}
		else {
			header("Content-Disposition: attachment; filename=Resultados_ID_Imparticion_" . $codigo_inscripcion . ".csv");
		}
		
		//header("Content-Disposition: attachment; filename=Participantes_ID_Imparticion_" . $codigo_inscripcion . ".csv");
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		
		$Num_sesiones = ImparticionesNumSesiones_2021($codigo_inscripcion);
		
		if ($sp == "1") {
			echo "rut\r\n";
		}
		else {
			$rut_sesiones_s = "";
			if ($Num_sesiones == "12") {
				$rut_sesiones_s .= ";";
				$rut_sesiones_s .= "asistencia_s1_(SI);";
				$rut_sesiones_s .= "asistencia_s2_(SI);";
				$rut_sesiones_s .= "asistencia_s3_(SI);";
				$rut_sesiones_s .= "asistencia_s4_(SI);";
				$rut_sesiones_s .= "asistencia_s5_(SI);";
				$rut_sesiones_s .= "asistencia_s6_(SI);";
				$rut_sesiones_s .= "asistencia_s7_(SI);";
				$rut_sesiones_s .= "asistencia_s8_(SI);";
				$rut_sesiones_s .= "asistencia_s9_(SI);";
				$rut_sesiones_s .= "asistencia_s10_(SI);";
				$rut_sesiones_s .= "asistencia_s11_(SI);";
				$rut_sesiones_s .= "asistencia_s12_(SI);";
			}
			if ($Num_sesiones == "11") {
				$rut_sesiones_s .= ";";
				$rut_sesiones_s .= "asistencia_s1_(SI);";
				$rut_sesiones_s .= "asistencia_s2_(SI);";
				$rut_sesiones_s .= "asistencia_s3_(SI);";
				$rut_sesiones_s .= "asistencia_s4_(SI);";
				$rut_sesiones_s .= "asistencia_s5_(SI);";
				$rut_sesiones_s .= "asistencia_s6_(SI);";
				$rut_sesiones_s .= "asistencia_s7_(SI);";
				$rut_sesiones_s .= "asistencia_s8_(SI);";
				$rut_sesiones_s .= "asistencia_s9_(SI);";
				$rut_sesiones_s .= "asistencia_s10_(SI);";
				$rut_sesiones_s .= "asistencia_s11_(SI);";
			}
			if ($Num_sesiones == "10") {
				$rut_sesiones_s .= ";";
				$rut_sesiones_s .= "asistencia_s1_(SI);";
				$rut_sesiones_s .= "asistencia_s2_(SI);";
				$rut_sesiones_s .= "asistencia_s3_(SI);";
				$rut_sesiones_s .= "asistencia_s4_(SI);";
				$rut_sesiones_s .= "asistencia_s5_(SI);";
				$rut_sesiones_s .= "asistencia_s6_(SI);";
				$rut_sesiones_s .= "asistencia_s7_(SI);";
				$rut_sesiones_s .= "asistencia_s8_(SI);";
				$rut_sesiones_s .= "asistencia_s9_(SI);";
				$rut_sesiones_s .= "asistencia_s10_(SI);";
			}
			if ($Num_sesiones == "9") {
				$rut_sesiones_s .= ";";
				$rut_sesiones_s .= "asistencia_s1_(SI);";
				$rut_sesiones_s .= "asistencia_s2_(SI);";
				$rut_sesiones_s .= "asistencia_s3_(SI);";
				$rut_sesiones_s .= "asistencia_s4_(SI);";
				$rut_sesiones_s .= "asistencia_s5_(SI);";
				$rut_sesiones_s .= "asistencia_s6_(SI);";
				$rut_sesiones_s .= "asistencia_s7_(SI);";
				$rut_sesiones_s .= "asistencia_s8_(SI);";
				$rut_sesiones_s .= "asistencia_s9_(SI);";
			}
			if ($Num_sesiones == "8") {
				$rut_sesiones_s .= ";";
				$rut_sesiones_s .= "asistencia_s1_(SI);";
				$rut_sesiones_s .= "asistencia_s2_(SI);";
				$rut_sesiones_s .= "asistencia_s3_(SI);";
				$rut_sesiones_s .= "asistencia_s4_(SI);";
				$rut_sesiones_s .= "asistencia_s5_(SI);";
				$rut_sesiones_s .= "asistencia_s6_(SI);";
				$rut_sesiones_s .= "asistencia_s7_(SI);";
				$rut_sesiones_s .= "asistencia_s8_(SI);";
			}
			if ($Num_sesiones == "7") {
				$rut_sesiones_s .= ";";
				$rut_sesiones_s .= "asistencia_s1_(SI);";
				$rut_sesiones_s .= "asistencia_s2_(SI);";
				$rut_sesiones_s .= "asistencia_s3_(SI);";
				$rut_sesiones_s .= "asistencia_s4_(SI);";
				$rut_sesiones_s .= "asistencia_s5_(SI);";
				$rut_sesiones_s .= "asistencia_s6_(SI);";
				$rut_sesiones_s .= "asistencia_s7_(SI);";
			}
			if ($Num_sesiones == "6") {
				$rut_sesiones_s .= ";";
				$rut_sesiones_s .= "asistencia_s1_(SI);";
				$rut_sesiones_s .= "asistencia_s2_(SI);";
				$rut_sesiones_s .= "asistencia_s3_(SI);";
				$rut_sesiones_s .= "asistencia_s4_(SI);";
				$rut_sesiones_s .= "asistencia_s5_(SI);";
				$rut_sesiones_s .= "asistencia_s6_(SI);";
			}
			if ($Num_sesiones == "5") {
				$rut_sesiones_s .= ";";
				$rut_sesiones_s .= "asistencia_s1_(SI);";
				$rut_sesiones_s .= "asistencia_s2_(SI);";
				$rut_sesiones_s .= "asistencia_s3_(SI);";
				$rut_sesiones_s .= "asistencia_s4_(SI);";
				$rut_sesiones_s .= "asistencia_s5_(SI);";
			}
			
			if ($Num_sesiones == "4") {
				$rut_sesiones_s .= ";";
				$rut_sesiones_s .= "asistencia_s1_(SI);";
				$rut_sesiones_s .= "asistencia_s2_(SI);";
				$rut_sesiones_s .= "asistencia_s3_(SI);";
				$rut_sesiones_s .= "asistencia_s4_(SI);";
			}
			if ($Num_sesiones == "3") {
				$rut_sesiones_s .= ";";
				$rut_sesiones_s .= "asistencia_s1_(SI);";
				$rut_sesiones_s .= "asistencia_s2_(SI);";
				$rut_sesiones_s .= "asistencia_s3_(SI);";
			}
			if ($Num_sesiones == "2") {
				$rut_sesiones_s .= ";";
				$rut_sesiones_s .= "asistencia_s1_(SI);";
				$rut_sesiones_s .= "asistencia_s2_(SI);";
			}
			if ($Num_sesiones == "1") {
				$rut_sesiones_s .= ";";
				$rut_sesiones_s .= "asistencia_s1_(SI);";
			}
			
			if ($Num_sesiones > 0) {
				echo "rut;nombre;cargo;division;zona;departamento;cui;estado;asistencia_(0_100);nota_(0_100)" . $rut_sesiones_s . "\r\n";
			}
			else {
				echo "rut;nombre;cargo;division;zona;departamento;cui;estado;asistencia_(0_100);nota_(0_100)\r\n";
			}
		}
		
		//ob_clean();
		
		
		$total_usuarios_ = TraigoRegistrosPorSesionDeCheckinPorImparticion_2021($codigo_inscripcion, $id_empresa);
		
		foreach ($total_usuarios_ as $unico) {
			$Usu = reportes_online_usuario_rut($unico->rut);
			
			$row_csv_sesiones = "";
			$check_s1 = "";
			$check_s2 = "";
			$check_s3 = "";
			$check_s4 = "";
			$check_s5 = "";
			$check_s6 = "";
			$check_s7 = "";
			$check_s8 = "";
			$check_s9 = "";
			$check_s10 = "";
			$check_s11 = "";
			$check_s12 = "";
			
			if ($Num_sesiones > 0) {
				$check_s1 = Asistencia2021_Avance_Sesiones($unico->rut, $codigo_inscripcion, "1");
				if ($check_s1 == "checked") {
					$row_csv_sesiones .= "SI;";
				}
				else {
					$row_csv_sesiones .= "NO;";
				}
			}
			if ($Num_sesiones > 1) {
				$check_s2 = Asistencia2021_Avance_Sesiones($unico->rut, $codigo_inscripcion, "2");
				if ($check_s2 == "checked") {
					$row_csv_sesiones .= "SI;";
				}
				else {
					$row_csv_sesiones .= "NO;";
				}
			}
			if ($Num_sesiones > 2) {
				$check_s3 = Asistencia2021_Avance_Sesiones($unico->rut, $codigo_inscripcion, "3");
				if ($check_s3 == "checked") {
					$row_csv_sesiones .= "SI;";
				}
				else {
					$row_csv_sesiones .= "NO;";
				}
			}
			if ($Num_sesiones > 3) {
				$check_s4 = Asistencia2021_Avance_Sesiones($unico->rut, $codigo_inscripcion, "4");
				if ($check_s4 == "checked") {
					$row_csv_sesiones .= "SI;";
				}
				else {
					$row_csv_sesiones .= "NO;";
				}
			}
			if ($Num_sesiones > 4) {
				$check_s5 = Asistencia2021_Avance_Sesiones($unico->rut, $codigo_inscripcion, "5");
				if ($check_s5 == "checked") {
					$row_csv_sesiones .= "SI;";
				}
				else {
					$row_csv_sesiones .= "NO;";
				}
			}
			if ($Num_sesiones > 5) {
				$check_s6 = Asistencia2021_Avance_Sesiones($unico->rut, $codigo_inscripcion, "6");
				if ($check_s6 == "checked") {
					$row_csv_sesiones .= "SI;";
				}
				else {
					$row_csv_sesiones .= "NO;";
				}
			}
			if ($Num_sesiones > 6) {
				$check_s7 = Asistencia2021_Avance_Sesiones($unico->rut, $codigo_inscripcion, "7");
				if ($check_s7 == "checked") {
					$row_csv_sesiones .= "SI;";
				}
				else {
					$row_csv_sesiones .= "NO;";
				}
			}
			if ($Num_sesiones > 7) {
				$check_s8 = Asistencia2021_Avance_Sesiones($unico->rut, $codigo_inscripcion, "8");
				if ($check_s8 == "checked") {
					$row_csv_sesiones .= "SI;";
				}
				else {
					$row_csv_sesiones .= "NO;";
				}
			}
			if ($Num_sesiones > 8) {
				$check_s9 = Asistencia2021_Avance_Sesiones($unico->rut, $codigo_inscripcion, "9");
				if ($check_s9 == "checked") {
					$row_csv_sesiones .= "SI;";
				}
				else {
					$row_csv_sesiones .= "NO;";
				}
			}
			if ($Num_sesiones > 9) {
				$check_s10 = Asistencia2021_Avance_Sesiones($unico->rut, $codigo_inscripcion, "10");
				if ($check_s10 == "checked") {
					$row_csv_sesiones .= "SI;";
				}
				else {
					$row_csv_sesiones .= "NO;";
				}
			}
			if ($Num_sesiones > 10) {
				$check_s11 = Asistencia2021_Avance_Sesiones($unico->rut, $codigo_inscripcion, "11");
				if ($check_s11 == "checked") {
					$row_csv_sesiones .= "SI;";
				}
				else {
					$row_csv_sesiones .= "NO;";
				}
			}
			if ($Num_sesiones > 11) {
				$check_s12 = Asistencia2021_Avance_Sesiones($unico->rut, $codigo_inscripcion, "12");
				if ($check_s12 == "checked") {
					$row_csv_sesiones .= "SI;";
				}
				else {
					$row_csv_sesiones .= "NO;";
				}
			}
			
			if ($sp == "1") {
				echo "" . $unico->rut . "\r\n";
			}
			else {
				if ($Num_sesiones > 0) {
					echo "" . $unico->rut . ";" . $Usu[0]->nombre_completo . ";" . $Usu[0]->cargo . ";" . $Usu[0]->c1 . ";" . $Usu[0]->c2 . ";" . $Usu[0]->c3 . ";" . $Usu[0]->c4 . ";" . $unico->estado_descripcion . ";" . $unico->asistencia . ";" . $unico->nota . ";" . $row_csv_sesiones . "\r\n";
				}
				else {
					echo "" . $unico->rut . ";" . $Usu[0]->nombre_completo . ";" . $Usu[0]->cargo . ";" . $Usu[0]->c1 . ";" . $Usu[0]->c2 . ";" . $Usu[0]->c3 . ";" . $Usu[0]->c4 . ";" . $unico->estado_descripcion . ";" . $unico->asistencia . ";" . $unico->nota . "\r\n";
				}
			}
		}
		exit();
		
		
		exit();
	}
	
	
	elseif ($seccion == "dltc") {
		$id_curso = Decodear3($get["i"]);
		
		$id_empresa = $_SESSION["id_empresa"];
		
		$Imp = BuscaIMparticionesDadoIdCurso_2022($id_curso, $id_empresa);
		
		foreach ($Imp as $imparticiones) {
			EliminiInscripcionUsuarioYInscripcionCurso_2022($imparticiones->codigo_inscripcion);
		}
		$Curso = Datos_curso($id_curso);
		
		ActualizaActivoInactivoCurso_2022($id_curso);
		
		
		if ($Curso[0]->modalidad == "1") {
			echo "<script>alert('Curso, Imparticiones y participantes eliminados');location.href='?sw=listcursos1';</script>";
			exit;
		}
		else {
			echo "<script>alert('Curso, Imparticiones y participantes eliminados');location.href='?sw=listcursos2';</script>";
			exit;
		}
	}
	elseif ($seccion == "dltImpart") {
		$id_imparticion = Decodear3($get["i"]);
		
		
		$Imp = BuscaIdImparticionFull_2021($id_imparticion);
		$Curso = Datos_curso($Imp[0]->id_curso);
		EliminiInscripcionUsuarioYInscripcionCurso_2022($id_imparticion);
		if ($Curso[0]->modalidad == "1") {
			echo "<script>alert('Imparticion y participantes eliminados');location.href='?sw=listaInscripciones1&i=" . Encodear3($Imp[0]->id_curso) . "';</script>";
			exit;
		}
		else {
			echo "<script>alert('Imparticion y participantes eliminados');location.href='?sw=listaInscripciones2&i=" . Encodear3($Imp[0]->id_curso) . "';</script>";
			exit;
		}
	}
	elseif ($seccion == "dltImpartmalla") {
		$id_imparticion = Decodear3($get["i"]);
		$id_malla = Decodear3($get["id_malla_enc"]);
		$id_programa = Decodear3($get["idpbbdd"]);
		$id_empresa = $_SESSION["id_empresa"];
		
		
		$id_imparticion_ = BuscaIdInscripcionMalla_2022($id_malla, $id_imparticion);
		
		
		$Imparticiones = Id_cursos_id_Inscripcion_por_id_inscripcion_idMalla_2022($id_imparticion_);
		
		foreach ($Imparticiones as $u) {
			
			EliminiInscripcionUsuarioYInscripcionCurso_ImparticionMalla_2022($u->id_inscripcion, $id_imparticion_);
		}
		
		
		
		echo "<script>alert('Imparticion  y participantes eliminados');location.href='?sw=listmallas1';</script>";
		exit;
	}
	
	elseif ($seccion == "descargarInscritosPorImpart_Elearning_2022") {
		$codigo_inscripcion = Decodear3($get["ci"]);
		
		$id_empresa = $_SESSION["id_empresa"];
		$datos_curso = DAtosCursoDadoIdInscripcion($codigo_inscripcion, $id_empresa);
		
		header('Content-Description: File Transfer');
		header('Content-Type: application/csv');
		header("Content-Disposition: attachment; filename=Participantes_ID_Imparticion_" . $codigo_inscripcion . ".csv");
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		//ob_clean();
		echo "rut\r\n";
		
		$total_usuarios_ = TraigoRegistrosPorSesionDeCheckinElearning_Rel_Rut_idInscripcion_PreInscritos_PorImparticion_2022($codigo_inscripcion, $id_empresa);
		
		foreach ($total_usuarios_ as $unico) {
			echo "" . $unico->rut . "\r\n";
		}
		exit();
	}
	
	elseif ($seccion == "descargarInscritosPorImpart_Elearning_2022_por_malla") {
		header('Content-Description: File Transfer');
		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename=Participantes_ID_Imparticion_GrupoCursos_' . $_SESSION["id_inscripcion_id_malla"] . '.csv');
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
		fputcsv($output, ['RUT']);
		
		// Obtener los datos a escribir en el CSV
		$total_usuarios_ = DescargaRut_IdMalla_IdInscripcion2022($_SESSION["id_inscripcion_id_malla"]);
		
		// Escribir los datos en el CSV
		foreach ($total_usuarios_ as $unico) {
			fputcsv($output, [$unico->rut]);
		}
		
		// Cerrar el archivo de salida
		fclose($output);
		
		// Finalizar el script para asegurar que no se agregue contenido adicional
		exit();
	}
	
	elseif ($seccion == "descargarUsuariosExternos_2022") {
		$codigo_inscripcion = Decodear3($get["ci"]);
		
		$id_empresa = $_SESSION["id_empresa"];
		$datos_curso = DAtosCursoDadoIdInscripcion($codigo_inscripcion, $id_empresa);
		
				header('Content-Description: File Transfer');
		header('Content-Type: application/csv');
		header("Content-Disposition: attachment; filename=UsuariosExternos" . $codigo_inscripcion . ".csv");
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		//ob_clean();
		echo "rut;nombre;apellido_paterno;apellido_materno;email;empresa\r\n";
		exit();
	}
	elseif ($seccion == "accionSube_descargarUsuariosExternos_2022") {
		$insertados = 0;
		$actualizados = 0;
		$id_empresa = $_SESSION["id_empresa"];
		VerificaExtensionFilesAdmin($_FILES["file"]);
		if (isset($_FILES["file"])) {
			if ($_FILES["file"]["error"] > 0) {
				echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
			}
			else {
				if (file_exists("upload/" . $_FILES["file"]["name"])) {
				}
				else {
					$storagename = "uploaded_file.txt";
					move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $storagename);
				}
			}
		}
		else {
			echo "No file selected <br />";
		}
		if (isset($storagename) && $file = fopen("upload/" . $storagename, "r")) {
			$firstline = fgets($file, 10096);
			$num = strlen($firstline) - strlen(str_replace(";", "", $firstline));
			$fields = array();
			$fields = explode(";", $firstline, ($num + 1));
			$line = array();
			$i = 0;
			
			while ($line[$i] = fgets($file, 10096)) {
				$dsatz[$i] = array();
				$dsatz[$i] = explode(";", $line[$i], ($num + 1));
				$i++;
			}
			$cuentaK = 0;
			for ($k = 0; $k != ($num + 1); $k++) {
				$cuentaK++;
			}
			
			$count_total_loop = count($dsatz);
			$estado_txt = "";
			foreach ($dsatz as $key => $number) {
				$row_csv = "";
				$cuenta = 0;
				foreach ($number as $k => $content) {
					$content = str_replace('+', '', $content);
					$content = str_replace('=', '', $content);
					$content = str_replace(';', '.', $content);
					$content = addcslashes($content, "'=\\");
					if ($k == "0") {
						$rut_completo = $content;
					}
					if ($k == "1") {
						$nombre = trim($content);
					}
					if ($k == "2") {
						$apellido = trim($content);
					}
					if ($k == "3") {
						$materno = trim($content);
					}
					if ($k == "4") {
						$email = trim($content);
					}
					if ($k == "5") {
						$empresa = trim($content);
					}
				}
				
				$rut = LimpiaRut($rut_completo);
				$empresa = ($empresa);
				$nombre = ($nombre);
				$apellido = ($apellido);
				
				proveedores_ejecutivos_save_insert_update($id_empresa, "usuarios_externos", "", $rut, $nombre, $apellido, $email, $empresa, $rut_completo, $materno);
				resetea_clave_2021_cambiado1($rut, $id_empresa);
				$cuenta_loop++;
				echo "<center><span style='position: absolute;z-index:$current;background:#36C6D3; padding:10px; color:#FFF'>Actualizando $cuenta_loop de $count_total_loop Registros</span></center>";
			}
		}
		echo "<script>alert('Usuarios Externos Ingresados');
    	location.href='?sw=lista_proveedores_otec&tipo=usuarios_externos&masivo=1';</script>";
		exit;
	}
	elseif ($seccion == "descargarUsuariosManuales_2022") {
		$codigo_inscripcion = Decodear3($get["ci"]);
		
		$id_empresa = $_SESSION["id_empresa"];
		$datos_curso = DAtosCursoDadoIdInscripcion($codigo_inscripcion, $id_empresa);
		
				header('Content-Description: File Transfer');
		header('Content-Type: application/csv');
		header("Content-Disposition: attachment; filename=UsuariosManuales" . $codigo_inscripcion . ".csv");
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		//ob_clean();
		echo "rut;nombre;apellido;email;empresa\r\n";
		exit();
	}
	elseif ($seccion == "accionSube_descargarUsuariosManuales_2022") {
		$insertados = 0;
		$actualizados = 0;
		$id_empresa = $_SESSION["id_empresa"];
		VerificaExtensionFilesAdmin($_FILES["file"]);
		if (isset($_FILES["file"])) {
			if ($_FILES["file"]["error"] > 0) {
				echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
			}
			else {
				if (file_exists("upload/" . $_FILES["file"]["name"])) {
				}
				else {
					$storagename = "uploaded_file.txt";
					move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $storagename);
				}
			}
		}
		else {
			echo "No file selected <br />";
		}
		if (isset($storagename) && $file = fopen("upload/" . $storagename, "r")) {
			$firstline = fgets($file, 10096);
			$num = strlen($firstline) - strlen(str_replace(";", "", $firstline));
			$fields = array();
			$fields = explode(";", $firstline, ($num + 1));
			$line = array();
			$i = 0;
			
			while ($line[$i] = fgets($file, 10096)) {
				$dsatz[$i] = array();
				$dsatz[$i] = explode(";", $line[$i], ($num + 1));
				$i++;
			}
			$cuentaK = 0;
			for ($k = 0; $k != ($num + 1); $k++) {
				$cuentaK++;
			}
			
			$count_total_loop = count($dsatz);
			$estado_txt = "";
			foreach ($dsatz as $key => $number) {
				$row_csv = "";
				$cuenta = 0;
				foreach ($number as $k => $content) {
					$content = str_replace('+', '', $content);
					$content = str_replace('=', '', $content);
					$content = str_replace(';', '.', $content);
					$content = addcslashes($content, "'=\\");
					if ($k == "0") {
						$rut_completo = $content;
					}
					if ($k == "1") {
						$nombre = trim($content);
					}
					if ($k == "2") {
						$apellido = trim($content);
					}
					if ($k == "3") {
						$email = trim($content);
					}
					if ($k == "4") {
						$empresa = trim($content);
					}
				}
				
				$rut = LimpiaRut($rut_completo);
				$empresa = ($empresa);
				$nombre = ($nombre);
				$apellido = ($apellido);
				
				proveedores_ejecutivos_save_insert_update($id_empresa, "usuarios_manuales", "", $rut, $nombre, $apellido, $email, $empresa, $rut_completo, '');
				resetea_clave_2021_cambiado1($rut, $id_empresa);
				$cuenta_loop++;
				echo "<center><span style='position: absolute;z-index:$current;background:#36C6D3; padding:10px; color:#FFF'>Actualizando $cuenta_loop de $count_total_loop Registros</span></center>";
			}
		}
		echo "<script>alert('Usuarios Ingresados');
    	location.href='?sw=lista_proveedores_otec&tipo=usuarios_manuales&masivo=1';</script>";
		exit;
	}
	elseif ($seccion == "accionSubedocPorImparticion_2021") {
		$id_imparticion = Decodear3($post["ci"]);
		$id_imparticion_GET = Decodear3($post["ci"]);
		$nombre = $post["nombre"];
		$insertados = 0;
		$actualizados = 0;
		$sp = $get["sp"];
		$Imp = DatosCompletosImparticion_2021($id_imparticion_GET);
		$Tipo_Imparticion = $Imp[0]->tipo_audiencia;
		$id_modalidad = $Imp[0]->id_modalidad;
		$id_empresa = $_SESSION["id_empresa"];
		$insertados = 0;
		$tamano_archivo = $_FILES["archivo"]['size'];
		$tipo_archivo = $_FILES["archivo"]['type'];
		$archivo_archivo = $_FILES["archivo"]['name'];
		if ($_FILES["archivo"]["name"] <> "") {
			VerificaExtensionFiles($_FILES["archivo"]["name"]);
		}
		$arreglo_archivo = explode(".", $archivo_archivo);
		$extension_archivo = $arreglo_archivo[1];
		$rutaarchivo = "upload";
		$prefijo = $id_imparticion . "_" . $rut . "_" . substr(md5(uniqid(rand())), 0, 6);
		if ($arreglo_archivo != "") {
			$nombre_archivo = $id_empresa . "_" . $prefijo;
			$datos_subida_archivo = Tickets_SubirArchivosTickets($_FILES, $extension_archivo, $nombre_archivo, $rutaarchivo, 1, $camp->campo);
			$nombre_archivo = $datos_subida_archivo[1];
		}
		
		
		Inserttbl_id_inscripcion_documentos($nombre_archivo, $id_imparticion, $_SESSION["admin_"], $nombre);
		echo "<script>alert('Documento Subido Exitosamente');location.href='?sw=VeDocsXImp2021&i=" . Encodear3($id_imparticion) . "&masivo=1';</script>";
		exit;
	}
	
	elseif ($seccion == "accionSubeNotasPorImparticion_2021") {
		$id_imparticion = Decodear3($post["ci"]);
		$id_imparticion_GET = Decodear3($post["ci"]);
		$insertados = 0;
		$actualizados = 0;
		$sp = $get["sp"];
		
		$Imp = DatosCompletosImparticion_2021($id_imparticion_GET);
		
		$Tipo_Imparticion = $Imp[0]->tipo_audiencia;
		$id_modalidad = $Imp[0]->id_modalidad;
		$id_empresa = $_SESSION["id_empresa"];
		
		$datos_curso = DAtosCursoDadoIdInscripcion($id_imparticion_GET, $id_empresa);
		$id_curso = $datos_curso[0]->id_curso;
		
		$id_empresa = $_SESSION["id_empresa"];
		$insertados = 0;
		VerificaExtensionFilesAdmin($_FILES["file"]);
		
		if (isset($_FILES["file"])) {
			if ($_FILES["file"]["error"] > 0) {
				echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
			}
			else {
				if (file_exists("upload/" . $_FILES["file"]["name"])) {
				}
				else {
					$storagename = "uploaded_file.txt";
					move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $storagename);
				}
			}
		}
		else {
			echo "No file selected <br />";
		}
		if (isset($storagename) && $file = fopen("upload/" . $storagename, "r")) {
			$firstline = fgets($file, 10096);
			//Gets the number of fields, in CSV-files the names of the fields are mostly given in the first line
			$num = strlen($firstline) - strlen(str_replace(";", "", $firstline));
			//save the different fields of the firstline in an array called fields
			$fields = array();
			$fields = explode(";", $firstline, ($num + 1));
			$line = array();
			$i = 0;
			
			while ($line[$i] = fgets($file, 10096)) {
				$dsatz[$i] = array();
				$dsatz[$i] = explode(";", $line[$i], ($num + 1));
				$i++;
			}
			
			$cuentaK = 0;
			for ($k = 0; $k != ($num + 1); $k++) {
				
				$cuentaK++;
			}

			$count_total_loop = count($dsatz);
			
			if ($count_total_loop > 0 and $sp == "1") {
				BorraUsuariosParaVolveraCrearlosTblInscripcionUsuarios($id_imparticion_GET);
				
			}
			
			$estado_txt = "";
			
			foreach ($dsatz as $key => $number) {
				$row_csv = "";
				$cuenta = 0;
				foreach ($number as $k => $content) {
					$content = str_replace('+', '', $content);
					$content = str_replace('=', '', $content);
					$content = str_replace(';', '.', $content);
					$content = addcslashes($content, "'=\\");
					
					if ($Tipo_Imparticion == "fecha_inicio_termino") {
						if ($k == "0") {
							$rut = $content;
						}
						if ($k == "1") {
							$asistencia = trim($content);
							$asistencia = str_replace(",", "", $asistencia);
							if ($asistencia == "") {
								$asistencia = 0;
							}
							if ($asistencia <> "" and $asistencia > 100) {
								$asistencia = 100;
							}
						}
						if ($k == "3") {
							$nota = trim($content);
							$nota = str_replace(",", "", $nota);
							if ($nota == "") {
								$nota = 0;
							}
							if ($nota <> "" and $nota > 100) {
								$nota = 100;
							}
						}
						if ($k == "2") {
							$nota_d = trim($content);
							$nota_d = str_replace(",", "", $nota_d);
							if ($nota_d == "") {
								$nota_d = 0;
							}
							if ($nota_d <> "" and $nota_d > 100) {
								$nota_d = 100;
							}
						}
						if ($k == "4") {
							$estado = trim($content);
						}
					}
					else {
						if ($k == "0") {
							$rut = $content;
						}
						if ($k == "1") {
							$nota_d = trim($content);
							$nota_d = str_replace(",", "", $nota_d);
							if ($nota_d == "") {
								$nota_d = 0;
							}
							if ($nota_d <> "" and $nota_d > 100) {
								$nota_d = 100;
							}
						}
						if ($k == "2") {
							$nota = trim($content);
							$nota = trim($content);
							$nota = str_replace(",", "", $nota);
							if ($nota == "") {
								$nota = 0;
							}
							if ($nota <> "" and $nota > 100) {
								$nota = 100;
							}
						}
						if ($k == "3") {
							$S1 = $content;
						}
						if ($k == "4") {
							$S2 = $content;
						}
						if ($k == "5") {
							$S3 = $content;
						}
						if ($k == "6") {
							$S4 = $content;
						}
						if ($k == "7") {
							$S5 = $content;
						}
						if ($k == "8") {
							$S6 = $content;
						}
						if ($k == "9") {
							$S7 = $content;
						}
						if ($k == "10") {
							$S8 = $content;
						}
						if ($k == "11") {
							$S9 = $content;
						}
						if ($k == "12") {
							$S10 = $content;
						}
						if ($k == "13") {
							$S11 = $content;
						}
						if ($k == "14") {
							$S12 = $content;
						}
					}
				}
				
				
				
				
				$rut = trim($rut);
				
				$rut = BuscaRutDadoRutEmailIdSap_2021($rut);
				
				if ($rut <> "") {
					InsertaRelacionInscripcionUsuarioFull2021($rut, $datos_curso[0]->id_programa, $id_imparticion_GET, $datos_curso[0]->id_curso, $Imp, $id_empresa);
				}
				
				
				
				
				$Num_sesiones = ImparticionesNumSesiones_2021($id_imparticion_GET);
				if ($Num_sesiones > 0) {
					$S1 = trim($S1);
					$S2 = trim($S2);
					$S3 = trim($S3);
					$S4 = trim($S4);
					$S5 = trim($S5);
					$S6 = trim($S6);
					$S7 = trim($S7);
					$S8 = trim($S8);
					$S9 = trim($S9);
					$S10 = trim($S10);
					$S11 = trim($S11);
					$S12 = trim($S12);
					
					$ses_1 = "";
					$ses_2 = "";
					$ses_3 = "";
					$ses_4 = "";
					$ses_5 = "";
					$ses_6 = "";
					$ses_7 = "";
					$ses_8 = "";
					$ses_9 = "";
					$ses_10 = "";
					$ses_11 = "";
					$ses_12 = "";
					if ($S1 == "SI" or $S1 == "Si" or $S1 == "si" or $S1 == "1" or $S1 == "x") {
						$ses_1 = "on";
					}
					if ($S2 == "SI" or $S2 == "Si" or $S2 == "si" or $S2 == "1" or $S2 == "x") {
						$ses_2 = "on";
					}
					if ($S3 == "SI" or $S3 == "Si" or $S3 == "si" or $S3 == "1" or $S3 == "x") {
						$ses_3 = "on";
					}
					
					if ($S4 == "SI" or $S4 == "Si" or $S4 == "si" or $S4 == "1" or $S4 == "x") {
						$ses_4 = "on";
					}
					if ($S5 == "SI" or $S5 == "Si" or $S5 == "si" or $S5 == "1" or $S5 == "x") {
						$ses_5 = "on";
					}
					if ($S6 == "SI" or $S6 == "Si" or $S6 == "si" or $S6 == "1" or $S6 == "x") {
						$ses_6 = "on";
					}
					
					if ($S7 == "SI" or $S7 == "Si" or $S7 == "si" or $S7 == "1" or $S7 == "x") {
						$ses_7 = "on";
					}
					if ($S8 == "SI" or $S8 == "Si" or $S8 == "si" or $S8 == "1" or $S8 == "x") {
						$ses_8 = "on";
					}
					if ($S9 == "SI" or $S9 == "Si" or $S9 == "si" or $S9 == "1" or $S9 == "x") {
						$ses_9 = "on";
					}
					
					if ($S10 == "SI" or $S10 == "Si" or $S10 == "si" or $S10 == "1" or $S10 == "x") {
						$ses_10 = "on";
					}
					if ($S11 == "SI" or $S11 == "Si" or $S11 == "si" or $S11 == "1" or $S11 == "x") {
						$ses_11 = "on";
					}
					if ($S12 == "SI" or $S12 == "Si" or $S12 == "si" or $S12 == "1" or $S12 == "x") {
						$ses_12 = "on";
					}
					
					ImparticionSesionesAsistenciaPorSesion_2021($id_imparticion_GET, $rut, $ses_1, $ses_2, $ses_3, $ses_4, $ses_5, $ses_6, $ses_7, $ses_8, $ses_9, $ses_10, $ses_11, $ses_12);
				}
				
				
				if ($sp == "1") {
				}
				else {
					$esta_cierre = VerificaCursoEstaEnCierrePorEmpresaImparticion($id_curso, $rut, $id_empresa, $id_imparticion_GET);
					
					if ($esta_cierre) {
						LMS_ActualizaRegistroCierreCursoImparticion_2022($rut, $id_curso, $id_empresa, $nota, $estado, $asistencia, $id_imparticion_GET, $asistencia, $nota_d);
						$actualizados++;
					}
					else {
						
						LMS_InsertaRegistroCierreCursoImparticion_2022($rut, $id_curso, $id_empresa, $nota, $estado, $asistencia, $id_imparticion_GET, $asistencia, $nota_d);
						$insertados++;
					}
				}
				
				
				$cuenta_loop++;

				echo "<center><span style='position: absolute;z-index:$current;background:#36C6D3; padding:10px; color:#FFF'>Actualizando $cuenta_loop de $count_total_loop Registros</span></center>";

				
			}

		}
		
		
		echo "<script>alert('Datos Actualizados');
    location.href='?sw=VeColaboradoresXImp2021&i=" . Encodear3($id_imparticion) . "&masivo=1';</script>";
		
		exit;
	}
	elseif ($seccion == "lista_proveedores_otec") {
		$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/proveedores_otec/entorno.html"));
		$id_empresa = $_SESSION["id_empresa"];
		$tipo = $get["tipo"];
		if (Decodear3($get["del"]) <> "") {
			lista_proveedores_ejecutivos_delete_data(Decodear3($get["del"]), $tipo);
		}
		
		if (Decodear3($get["change_active"]) <> "") {
			$id_cambiar_estado_programa = Decodear3($get["change_active"]);
			$var_activar = $get["activar"];
			if ($tipo == "programas") {
				UpdateActivarPrograma_2024($id_cambiar_estado_programa, $var_activar);
			}
			elseif ($tipo == "mallas_bch") {
				UpdateActivarMallaCategoria_2024($id_cambiar_estado_programa, $var_activar);
			}
			elseif ($tipo == "categorias_bch") {
				UpdateActivarMallaCategoria_2024($id_cambiar_estado_programa, $var_activar);
			}
		}
		
		if ($tipo == "otec") {
			$PRINCIPAL = str_replace("{ENTORNO}", lista_proveedores_ejecutivos_fn(FuncionesTransversalesAdmin(file_get_contents("views/proveedores_otec/entorno_index_proveedores.html")), $id_empresa, $tipo), $PRINCIPAL);
		}
		elseif ($tipo == "administradores") {
			$PRINCIPAL = str_replace("{ENTORNO}", lista_proveedores_ejecutivos_fn(FuncionesTransversalesAdmin(file_get_contents("views/proveedores_otec/entorno_index_administradores.html")), $id_empresa, $tipo), $PRINCIPAL);
		}
		elseif ($tipo == "ejecutivos") {
			$PRINCIPAL = str_replace("{ENTORNO}", lista_proveedores_ejecutivos_fn(FuncionesTransversalesAdmin(file_get_contents("views/proveedores_otec/entorno_index_ejecutivos.html")), $id_empresa, $tipo), $PRINCIPAL);
		}
		elseif ($tipo == "relatores") {
			$PRINCIPAL = str_replace("{ENTORNO}", lista_proveedores_ejecutivos_fn(FuncionesTransversalesAdmin(file_get_contents("views/proveedores_otec/entorno_index_relatores.html")), $id_empresa, $tipo), $PRINCIPAL);
		}
		elseif ($tipo == "usuarios_manuales") {
			$PRINCIPAL = str_replace("{ENTORNO}", lista_proveedores_ejecutivos_fn(FuncionesTransversalesAdmin(file_get_contents("views/proveedores_otec/entorno_index_usuarios_manuales.html")), $id_empresa, $tipo), $PRINCIPAL);
		}
		elseif ($tipo == "usuarios_externos") {
			$PRINCIPAL = str_replace("{ENTORNO}", lista_proveedores_ejecutivos_fn(FuncionesTransversalesAdmin(file_get_contents("views/proveedores_otec/entorno_index_usuarios_externos.html")), $id_empresa, $tipo), $PRINCIPAL);
		}
		elseif ($tipo == "direcciones") {
			$PRINCIPAL = str_replace("{ENTORNO}", lista_proveedores_ejecutivos_fn(FuncionesTransversalesAdmin(file_get_contents("views/proveedores_otec/entorno_index_direcciones.html")), $id_empresa, $tipo), $PRINCIPAL);
		}
		elseif ($tipo == "foco") {
			$PRINCIPAL = str_replace("{ENTORNO}", lista_proveedores_ejecutivos_fn(FuncionesTransversalesAdmin(file_get_contents("views/proveedores_otec/entorno_index_focos.html")), $id_empresa, $tipo), $PRINCIPAL);
		}
		elseif ($tipo == "programas") {
			$PRINCIPAL = str_replace("{ENTORNO}", lista_proveedores_ejecutivos_fn(FuncionesTransversalesAdmin(file_get_contents("views/proveedores_otec/entorno_index_programas.html")), $id_empresa, $tipo), $PRINCIPAL);
		}
		elseif ($tipo == "cuentas") {
			$PRINCIPAL = str_replace("{ENTORNO}", lista_proveedores_ejecutivos_fn(FuncionesTransversalesAdmin(file_get_contents("views/proveedores_otec/entorno_index_cuentas.html")), $id_empresa, $tipo), $PRINCIPAL);
		}
		elseif ($tipo == "mallas_bch") {
			$PRINCIPAL = str_replace("{ENTORNO}", lista_proveedores_ejecutivos_fn(FuncionesTransversalesAdmin(file_get_contents("views/proveedores_otec/entorno_index_mallas_bch.html")), $id_empresa, $tipo), $PRINCIPAL);
		}
		elseif ($tipo == "categorias_bch") {
			$PRINCIPAL = str_replace("{ENTORNO}", lista_proveedores_ejecutivos_fn(FuncionesTransversalesAdmin(file_get_contents("views/proveedores_otec/entorno_index_categorias.html")), $id_empresa, $tipo), $PRINCIPAL);
		}
		$PRINCIPAL = str_replace("{ID_TIPO}", ($tipo), $PRINCIPAL);
		echo CleanHTMLWhiteList(($PRINCIPAL));
		exit;
	}
	elseif ($seccion == "comunas_dado_regiones") {
		$region = $post["region"];
		
		$id_empresa = $_SESSION["id_empresa"];
		$comunas = Comunas_dado_id_regiones_2022($region);
		
		$options = "";
		$options .= "<option value=''></option>";
		foreach ($comunas as $comuna) {
			$options .= "<option value='" . $comuna->id_comunas . "'>" . ($comuna->nombre) . "</option>";
		}
		echo($options);
	}
	elseif ($seccion == "proveedores_ejecutivos_save") {
		$id_empresa = $_SESSION["id_empresa"];
		
		
		if ($post["tipo"] == "otec") {
			$post["nombre"] = str_replace("Ñ", "N", $post["nombre"]);
			$post["nombre"] = str_replace("ñ", "n", $post["nombre"]);
			proveedores_ejecutivos_save_insert_update($id_empresa, $post["tipo"], $post["id"], $post["rut"], $post["nombre"], $post["descripcion"], $post["direccion"], $post["telefono"], $post["email"], $post["contacto"]);
		}
		elseif ($post["tipo"] == "ejecutivos") {
			proveedores_ejecutivos_save_insert_update($id_empresa, $post["tipo"], $post["id"], $post["rut"], $post["nombre"], $post["descripcion"], $post["direccion"], $post["tipo_ejecutivo"], $post["email"], $post["contacto"]);
		}
		elseif ($post["tipo"] == "administradores") {
			proveedores_ejecutivos_save_insert_update($id_empresa, $post["tipo"], $post["id"], $post["rut"], $post["nombre"], $post["descripcion"], $post["direccion"], $post["telefono"], $post["email"], $post["contacto"]);
		}
		elseif ($post["tipo"] == "relatores") {
			
			if ($post["tipo_relator"] == "Interno") {
				$post["rut"] = LimpiaRut($post["rut"]);
				$Usu = TraeDatosUsuario($post["rut"]);
				if ($Usu[0]->nombre_completo == "") {
					echo "<script>alert('Usuario No Encontrado en Base de Datos');location.href='?sw=lista_proveedores_otec&tipo=relatores';    </script>";
					exit();
				}
				else {
					proveedores_ejecutivos_save_insert_update($id_empresa, $post["tipo"], $post["id"], $post["rut"], $Usu[0]->nombre_completo, "", $Usu[0]->cargo, $post["tipo_relator"], $Usu[0]->email, $Usu[0]->nombre_empresa_holding);
				}
			}
			elseif ($post["tipo_relator"] == "Externo") {
				$post["rut"] = ($post["rut"]);

				if ($post["nombre"] == "") {
					echo "<script>alert('Debes ingresar el nombre');location.href='?sw=lista_proveedores_otec&tipo=relatores';    </script>";
					exit();
				}
				else {
					proveedores_ejecutivos_save_insert_update($id_empresa, $post["tipo"], $post["id"], $post["rut"], $post["nombre"], "", $post["cargo"], $post["tipo_relator"], $post["email"], $post["empresa"]);
				}
			}
			elseif ($post["tipo_relator"] == "Extranjero") {
				$post["rut"] = ($post["rut"]);

				if ($post["nombre"] == "") {
					echo "<script>alert('Debes ingresar el nombre');location.href='?sw=lista_proveedores_otec&tipo=relatores';    </script>";
					exit();
				}
				else {
					$MaxId = MaxIdRelatores();
					$RutExtranjero = $MaxId + 1;
					
					proveedores_ejecutivos_save_insert_update($id_empresa, $post["tipo"], $post["id"], $RutExtranjero, $post["nombre"], "", $post["cargo"], $post["tipo_relator"], $post["email"], $post["empresa"]);
				}
			}
			//					proveedores_ejecutivos_save_insert_update($id_empresa, $post["tipo"], $post["id"], $post["rut"], $post["nombre"], $post["descripcion"], $post["direccion"], $post["telefono"], $post["email"], $post["contacto"]);
			
			
		}
		elseif ($post["tipo"] == "usuarios_manuales") {
			$rut_completo = str_replace(".", "", $post["rut"]);
			$post["rut"] = LimpiaRut($post["rut"]);
			$Usu = TraeDatosUsuario($post["rut"]);
			if ($Usu[0]->rut <> "" and $Usu[0]->empresa_holding == "") {
				echo "<script>alert('Usuario ya existe en la base de datos');location.href='?sw=lista_proveedores_otec&tipo=usuarios_manuales';    </script>";
				exit();
			}
			else {

				proveedores_ejecutivos_save_insert_update($id_empresa, $post["tipo"], $post["id"], $post["rut"], $post["nombre"], $post["apellido"], $post["email"], $post["empresa"], $rut_completo, "");
				
				resetea_clave_2021_cambiado1($post["rut"], $id_empresa);
			}
		}
		elseif ($post["tipo"] == "usuarios_externos") {
			$rut_completo = str_replace(".", "", $post["rut"]);
			$post["rut"] = LimpiaRut($post["rut"]);
			$Usu = TraeDatosUsuario($post["rut"]);
			if ($Usu[0]->rut <> "" and $Usu[0]->empresa_holding == "") {
				echo "<script>alert('Usuario ya existe en la base de datos');location.href='?sw=lista_proveedores_otec&tipo=usuarios_externos';    </script>";
				exit();
			}
			else {

				proveedores_ejecutivos_save_insert_update($id_empresa, $post["tipo"], $post["id"], $post["rut"], $post["nombre"], $post["apellido"], $post["email"], $post["empresa"], $rut_completo, $post["apellido_materno"]);
				
				resetea_clave_2021_cambiado1($post["rut"], $id_empresa);
			}
		}
		elseif ($post["tipo"] == "direcciones") {
			proveedores_ejecutivos_save_insert_update($id_empresa, $post["tipo"], $post["id"], $post["rut"], $post["nombre"], $post["descripcion"], $post["direccion"], $post["telefono"], $post["email"], $post["contacto"]);
		}
		elseif ($post["tipo"] == "foco") {
			proveedores_ejecutivos_save_insert_update($id_empresa, $post["tipo"], $post["id"], $post["rut"], $post["nombre"], $post["descripcion"], $post["direccion"], $post["telefono"], $post["email"], $post["contacto"]);
		}
		elseif ($post["tipo"] == "programas") {
			proveedores_ejecutivos_save_insert_update($id_empresa, $post["tipo"], $post["id"], $post["rut"], $post["nombre"], $post["descripcion"], $post["direccion"], $post["telefono"], $post["email"], $post["contacto"]);
		}
		elseif ($post["tipo"] == "mallas_bch") {
			proveedores_ejecutivos_save_insert_update($id_empresa, $post["tipo"], $post["id"], $post["rut"], $post["nombre"], $post["descripcion"], $post["direccion"], $post["telefono"], $post["email"], $post["contacto"]);
		}
		elseif ($post["tipo"] == "categorias_bch") {
			proveedores_ejecutivos_save_insert_update($id_empresa, $post["tipo"], $post["id"], $post["rut"], $post["nombre"], $post["descripcion"], $post["direccion"], $post["telefono"], $post["email"], $post["contacto"]);
		}
		elseif ($post["tipo"] == "cuentas") {
			proveedores_ejecutivos_save_insert_update($id_empresa, $post["tipo"], $post["id"], $post["rut"], $post["nombre"], $post["descripcion"], $post["direccion"], $post["telefono"], $post["email"], $post["contacto"]);
		}
		$tipo = isset($post["tipo"]) ? htmlspecialchars($post["tipo"], ENT_QUOTES) : ''; // Sanitizar
		echo "<script>location.href='?sw=lista_proveedores_otec&tipo=" . $tipo . "';    </script>";
		exit();
	} // CREACION DE CURSOS PRESENCIALES
	elseif ($seccion == "reporteJson_3_Pack_Cash_V6") {
		$datos_clientes = file_get_contents("../front/objetos/bch_cash_management/3_Pack_Cash_V6/assets/documents/puntajeDB.json");
		$json_clientes = json_decode($datos_clientes, true);
		
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=reporteJson_3_Pack_Cash_V6.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		

		foreach ($json_clientes as $clave => $valor) {
			// $array[3] se actualizará con cada valor de $array...
			?>
			<table>

			<tr>
				<td>Rut</td>
				<td>Nombre</td>
				<td>Cargo</td>
				<td>Division</td>
				<td>Fecha</td>
				<td>Correctas</td>
				<td>Total Preguntas</td>
				<td>Estado</td>
			</tr>
			<?php
			for ($i = 0; $i < count($valor); $i++) {
				$datos_usuario = SillasTotalRegistrosDataTemporalDadoIDTODOTBLUSUARIO(Decodear3($valor[$i]["rut"]));
				if (!$datos_usuario)
					continue;
				?>
				<tr>
					<td><?php echo $datos_usuario[0]->rut_completo ?></td>
					<td><?php echo $datos_usuario[0]->nombre_completo ?></td>
					<td><?php echo $datos_usuario[0]->cargo ?></td>
					<td><?php echo $datos_usuario[0]->division ?></td>
					<td><?php echo $valor[$i]["fecha"] ?></td>
					<?php
						$arreglo = explode("/", $valor[$i]["puntuacion"]);
					?>

					<td><?php echo $arreglo[0] ?></td>
					<td><?php echo $arreglo[1] ?></td>
					<td><?php echo $valor[$i]["estado"] ?></td>

				</tr>
				
				
				<?php
			}
		}
	}
	elseif ($seccion == "reporteJson_Pack_Riesgo_V4") {
		$datos_clientes = file_get_contents("../front/objetos/bch_riesgo_agricola/2_Pack_Riesgo_V4/assets/documents/puntajeDB.json");
		$json_clientes = json_decode($datos_clientes, true);
		
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=reporteJson_Pack_Riesgo_V4.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		foreach ($json_clientes as $clave => $valor) {
			// $array[3] se actualizará con cada valor de $array...
			?>
			<table>

			<tr>
				<td>Rut</td>
				<td>Nombre</td>
				<td>Cargo</td>
				<td>Division</td>
				<td>Fecha</td>
				<td>Correctas</td>
				<td>Total Preguntas</td>
				<td>Estado</td>
			</tr>
			<?php
			for ($i = 0; $i < count($valor); $i++) {
				$datos_usuario = SillasTotalRegistrosDataTemporalDadoIDTODOTBLUSUARIO(Decodear3($valor[$i]["rut"]));
				if (!$datos_usuario)
					continue;
				?>
				<tr>
					<td><?php echo $datos_usuario[0]->rut_completo ?></td>
					<td><?php echo $datos_usuario[0]->nombre_completo ?></td>
					<td><?php echo $datos_usuario[0]->cargo ?></td>
					<td><?php echo $datos_usuario[0]->division ?></td>
					<td><?php echo $valor[$i]["fecha"] ?></td>
					<?php
						$arreglo = explode("/", $valor[$i]["puntuacion"]);
					?>

					<td><?php echo $arreglo[0] ?></td>
					<td><?php echo $arreglo[1] ?></td>
					<td><?php echo $valor[$i]["estado"] ?></td>

				</tr>
				
				
				<?php
			}
		}
	}
	elseif ($seccion == "logout") {
		// Destruir todas las variables de sesión.
		$_SESSION = array();
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
		}
		session_destroy();
		echo "    <script>location.href='?sw=login';    </script>";
		exit;
	} 
	elseif ($seccion == "rel_id_inscripcion_rut_curso") {
		$id_empresa = $_SESSION["id_empresa"];
		
		
		if ($post["create_filter"] == "1") {
			$Arrayc1c2c3c4 = reportes_online_trae_c1c2c3c4($id_empresa);
			
			if ($post["c1"] <> "") {
				$_SESSION["c1h"] = " and (select c1 from tbl_reportes_online_usuario where rut=h.rut)='" . $post["c1"] . "' ";
				$_SESSION["c1text"] = " " . $Arrayc1c2c3c4[0]->c1_texto . " ='" . $post["c1"] . "' ";
				$cuenta_filter++;
			}
			else {
				$_SESSION["c1h"] = "";
				$_SESSION["c1text"] = "";
			}
			
			if ($post["c2"] <> "") {
				$_SESSION["c2h"] = " and (select c2 from tbl_reportes_online_usuario where rut=h.rut)='" . $post["c2"] . "' ";
				$_SESSION["c2text"] = " " . $Arrayc1c2c3c4[0]->c2_texto . " ='" . $post["c2"] . "' ";
				$cuenta_filter++;
			}
			else {
				$_SESSION["c2h"] = "";
				$_SESSION["c2text"] = "";
			}
			
			if ($post["c3"] <> "") {
				$_SESSION["c3h"] = " and (select c3 from tbl_reportes_online_usuario where rut=h.rut)='" . $post["c3"] . "' ";
				$_SESSION["c3text"] = " " . $Arrayc1c2c3c4[0]->c3_texto . " ='" . $post["c3"] . "' ";
				$cuenta_filter++;
			}
			else {
				$_SESSION["c3h"] = "";
				$_SESSION["c3text"] = "";
			}
			
			if ($post["c4"] <> "") {
				$_SESSION["c4h"] = " and (select c4 from tbl_reportes_online_usuario where rut=h.rut)='" . $post["c4"] . "' ";
				$_SESSION["c4text"] = " " . $Arrayc1c2c3c4[0]->c4_texto . " ='" . $post["c4"] . "' ";
				$cuenta_filter++;
			}
			else {
				$_SESSION["c4h"] = "";
				$_SESSION["c4text"] = "";
			}
			
			if ($post["rut_colaborador"] <> "") {
				$_SESSION["rut_colaborador"] = " and (select rut from tbl_reportes_online_usuario where rut=h.rut)='" . $post["rut_colaborador"] . "' ";
				$UsuCol = TraeDatosUsuario($post["rut_colaborador"]);
				$_SESSION["rut_colaborador_text"] = " Rut Colaborador = " . $post["rut_colaborador"] . " - " . $UsuCol[0]->nombre_completo . " ";
				$cuenta_filter++;
			}
			else {
				$_SESSION["rut_colaborador"] = "";
				$_SESSION["rut_colaborador_text"] = "";
			}
		}
		
		
		if ($cuenta_filter > 0) {
			$_SESSION["cuenta_filter"] = "1";
		}
		else {
			$_SESSION["cuenta_filter"] = "";
		}
		
		if ($get["clean"] == "1") {
			$_SESSION["c1text"] = "";
			$_SESSION["c2text"] = "";
			$_SESSION["c3text"] = "";
			$_SESSION["c4text"] = "";
			
			$_SESSION["c1h"] = "";
			$_SESSION["c2h"] = "";
			$_SESSION["c3h"] = "";
			$_SESSION["c4h"] = "";
			
			$_SESSION["op_rc_anos"] = "";
			$_SESSION["op_rc_programa"] = "";
			$_SESSION["op_rc_gerencia"] = "";
			$_SESSION["op_rc_colaboradores"] = "";
		}
		
		$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/reportes_online/entorno.html"));
		$PRINCIPAL = str_replace("{ENTORNO}", fn_rel_id_inscripcion_rut_curso_lista(FuncionesTransversalesAdmin(file_get_contents("views/reportes_online/index_rel_id_inscripcion_rut_curso.html")), $_SESSION["id_empresa"], $filtro, $post, $get, $request, $excel), $PRINCIPAL);
		
		$datos_empresa = DatosEmpresa($id_empresa);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "usuarios_insert_tbl_rel_id_inscripcion_rut_curso") {
		$id_empresa = $_SESSION["id_empresa"];
		
		
		$programa_malla = explode(";", $post["programa_malla"]);
		
		data_rel_id_inscripcion_curso_Insert($post["id_inscripcion"], ($post["nombre_inscripcion"]), $post["fecha_inicio"], $post["fecha_termino"], $id_empresa, $post["rut_ejecutivo"], $post["id_curso"], $post["opcional"], $programa_malla[0], $programa_malla[1]);
		
		
		
		VerificaExtensionFilesAdmin($_FILES["file"]);
		if (isset($post["action"])) {
			if (isset($_FILES["file"])) {
				if ($_FILES["file"]["error"] > 0) {
					echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
				}
				else {
					
					if (file_exists("upload/" . $_FILES["file"]["name"])) {
						//	echo $_FILES["file"]["name"] . " already exists. ";
					}
					else {
						//Store file in directory "upload" with the name of "uploaded_file.txt"
						$storagename = "uploaded_file.txt";
						move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $storagename);
						
					}
				}
			}
			else {
				echo "No file selected <br />";
			}
			
			if (isset($storagename) && $file = fopen("upload/" . $storagename, "r")) {
				
				
				$firstline = fgets($file, 10096);
				//Gets the number of fields, in CSV-files the names of the fields are mostly given in the first line
				$num = strlen($firstline) - strlen(str_replace(";", "", $firstline));
				//save the different fields of the firstline in an array called fields
				$fields = array();
				$fields = explode(";", $firstline, ($num + 1));
				$line = array();
				$i = 0;
				
				while ($line[$i] = fgets($file, 10096)) {
					$dsatz[$i] = array();
					$dsatz[$i] = explode(";", $line[$i], ($num + 1));
					$i++;
				}
				
				$cuentaK = 0;
				for ($k = 0; $k != ($num + 1); $k++) {
					
					$cuentaK++;
				}
				ini_set('output_buffering', 0);
				ini_set('zlib.output_compression', 0);
				
				if (!ob_get_level()) {
					ob_start();
				}
				else {
					ob_end_clean();
					ob_start();
				}
				$count_total_loop = count($dsatz);
				
				
				foreach ($dsatz as $key => $number) {
					$row_csv = "";
					$cuenta = 0;
					foreach ($number as $k => $content) {
						$cuenta++;
						$content = str_replace("'", "", $content);
						$content = str_replace(";", ",", $content);
						$content = str_replace('+', '', $content);
						$content = str_replace('=', '', $content);
						$content = str_replace(';', '.', $content);
						$content = addcslashes($content, "'=\\");
						$row_csv .= "'" . trim(strip_tags($content)) . "'";
						
						
						
					}
					
					
					$row_csv .= ", '" . $post["id_curso"] . "', '" . $post["id_inscripcion"] . "','" . $post["fecha_inicio"] . "','" . $post["fecha_termino"] . "','" . $post["rut_ejecutivo"] . "'";

					data_rel_id_inscripcion_rut_curso_Insert($row_csv);
					
					
					$cuenta_loop++;
					
					echo "<center><span style='position: absolute;z-index:$current;background:#36C6D3; padding:10px; color:#FFF'>Actualizando $cuenta_loop de $count_total_loop Registros</span></center>";
					flush();
					ob_flush();
				}
			}
		}
		$id_curso = isset($post["id_curso"]) ? htmlspecialchars($post["id_curso"], ENT_QUOTES) : ''; // Sanitizar
		echo "<script> alert('base actualizada correctamente'); location.href='?sw=rel_id_inscripcion_rut_curso&id_curso=" . $id_curso . "';</script>";
		unlink("upload/" . $storagename);
		exit;
	}
	elseif ($seccion == "usuarios_update_tbl_rel_id_inscripcion_rut_curso") {
		$id_empresa = $_SESSION["id_empresa"];
		
		
		if ($post["accion_id_inscripcion"] == "1") {
			VerificaExtensionFilesAdmin($_FILES["file"]);
			
			if (isset($_FILES["file"])) {
				if ($_FILES["file"]["error"] > 0) {
					echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
				}
				else {
					if (file_exists("upload/" . $_FILES["file"]["name"])) {
					
					}
					else {
						//Store file in directory "upload" with the name of "uploaded_file.txt"
						$storagename = "uploaded_file.txt";
						move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $storagename);
						
					}
				}
			}
			else {
				echo "No file selected <br />";
			}
			
			if (isset($storagename) && $file = fopen("upload/" . $storagename, "r")) {
				
				$firstline = fgets($file, 10096);
				//Gets the number of fields, in CSV-files the names of the fields are mostly given in the first line
				$num = strlen($firstline) - strlen(str_replace(";", "", $firstline));
				//save the different fields of the firstline in an array called fields
				$fields = array();
				$fields = explode(";", $firstline, ($num + 1));
				$line = array();
				$i = 0;
				
				while ($line[$i] = fgets($file, 10096)) {
					$dsatz[$i] = array();
					$dsatz[$i] = explode(";", $line[$i], ($num + 1));
					$i++;
				}
				
				$cuentaK = 0;
				for ($k = 0; $k != ($num + 1); $k++) {
					
					$cuentaK++;
				}
				ini_set('output_buffering', 0);
				ini_set('zlib.output_compression', 0);
				if (!ob_get_level()) {
					ob_start();
				}
				else {
					ob_end_clean();
					ob_start();
				}
				$count_total_loop = count($dsatz);
				
				foreach ($dsatz as $key => $number) {
					$row_csv = "";
					$cuenta = 0;
					foreach ($number as $k => $content) {
						$cuenta++;
						$content = str_replace("'", "", $content);
						$content = str_replace(";", ",", $content);
						$content = str_replace('+', '', $content);
						$content = str_replace('=', '', $content);
						$content = str_replace(';', '.', $content);
						$content = addcslashes($content, "'=\\");
						$rut_col = "" . trim(strip_tags($content)) . "";
						
					}
					
					
					
					if ($post["tipo_accion"] == "Eliminar") {
						data_rel_id_inscripcion_rut_curso_Delete($post["id_inscripcion"], $rut_col);
					}
					
					if ($post["tipo_accion"] == "Agregar") {
						$Inscripcion = data_rel_id_inscripcion_full_inscripcion($post["id_inscripcion"]);
						
						$row_csv = "'" . $rut_col . "',
										       								'" . $Inscripcion[0]->id_curso . "',
																					'" . $Inscripcion[0]->id_inscripcion . "',
																					'" . $Inscripcion[0]->fecha_inicio_inscripcion . "',
																					'" . $Inscripcion[0]->fecha_termino_inscripcion . "',
																					'" . $Inscripcion[0]->rut_ejecutivo . "'";
						
						data_rel_id_inscripcion_rut_curso_Insert($row_csv);
					}
					
					
					$cuenta_loop++;
					
					echo "<center><span style='position: absolute;z-index:$current;background:#36C6D3; padding:10px; color:#FFF'>Actualizando $cuenta_loop de $count_total_loop Registros</span></center>";
					flush();
					ob_flush();
				}
			}
			
			
			unlink("upload/" . $storagename);
			$id_inscripcion= isset($post["id_inscripcion"]) ? htmlspecialchars($post["id_inscripcion"], ENT_QUOTES) : ''; // Sanitizar
			echo "<script> alert('base actualizada correctamente'); location.href='?sw=rel_id_inscripcion_rut_curso&id_inscripcion=" . $id_inscripcion . "';</script>";
			exit;
		}
		
		if ($post["accion_editar_fecha"] == "1") {
			data_rel_id_inscripcion_rut_curso_Update($post["id_inscripcion"], $post["fecha_inicio"], $post["fecha_termino"], $post["rut_ejecutivo"]);
			$id_inscripcion= isset($post["id_inscripcion"]) ? htmlspecialchars($post["id_inscripcion"], ENT_QUOTES) : ''; // Sanitizar
			echo "<script> alert('base actualizada correctamente'); location.href='?sw=rel_id_inscripcion_rut_curso&id_inscripcion=" . $id_inscripcion . "';</script>";
			exit;
		}
		
		if ($get["del_id_inscripcion_del"] <> "") {
			data_rel_id_inscripcion_rut_curso_Delete_Full($get["del_id_inscripcion_del"]);
			echo "<script> alert('Id Inscripcion eliminada'); location.href='?sw=rel_id_inscripcion_rut_curso';</script>";
			exit;
		}
	}

	elseif ($seccion == "reportes_online") {
		
		//Check2020_Reportes_IdProgramaNull_data($_SESSION["id_empresa"]);
		reportes_online_cron_data($_SESSION["id_empresa"]);
		echo "<script>location.href='?sw=reportes_online_updated';</script>";
	}
	elseif ($seccion == "reportes_online_updated") {
		$id_empresa = $_SESSION["id_empresa"];

		
		if ($get["clean"] == "1") {
			$_SESSION["c1text"] = "";
			$_SESSION["c2text"] = "";
			$_SESSION["c3text"] = "";
			$_SESSION["c4text"] = "";
			
			$_SESSION["c1h"] = "";
			$_SESSION["c2h"] = "";
			$_SESSION["c3h"] = "";
			$_SESSION["c4h"] = "";
			$_SESSION["dato_final_filtro"] = "";
			
			$_SESSION["op_rc_anos"] = "";
			$_SESSION["op_rc_programa"] = "";
			$_SESSION["op_rc_gerencia"] = "";
			$_SESSION["op_rc_colaboradores"] = "";
		}
		
		$texto_filtro = "";
		if ($post["create_filter"] == "1") {
			$_SESSION["c1h"] = "";
			$_SESSION["c1text"] = "";
			$Arrayc1c2c3c4 = reportes_online_trae_c1c2c3c4($id_empresa);
			$cuenta_c1_check = 0;
			foreach ($post as $key => $value) {
				$cuenta_c1_check++;
				if (strpos($key, "Gerencia") !== false) {
					
					$NombresC1CheckBoxs .= $value . ". ";
					$QueryC1Checxbox .= " (select c1 from tbl_reportes_online_usuario where rut=h.rut)='" . $value . "' or ";
					$dato_final = $value;
					$_SESSION["dato_final_filtro"] = $dato_final;
					$dato_txt_c1_titulo = "Gerencia";
				}
			}
			if ($cuenta_c1_check > 0) {
				$_SESSION["c1h"] = "";
				$_SESSION["c1text"] = "";
				$QueryC1Checxbox .= " (select c1 from tbl_reportes_online_usuario where rut=h.rut)='" . $dato_final . "' ";
				$_SESSION["c1h"] = " and (" . $QueryC1Checxbox . ") ";
				$_SESSION["c1text"] = " " . $dato_txt_c1_titulo . "";
			}
			if ($post["c2"] <> "") {
				$_SESSION["c2h"] = " and (select c2 from tbl_reportes_online_usuario where rut=h.rut)='" . $post["c2"] . "' ";
				$_SESSION["c2text"] = " " . $Arrayc1c2c3c4[0]->c2_texto . " ='" . $post["c2"] . "' ";
				$cuenta_filter++;
			}
			else {
				$_SESSION["c2h"] = "";
				$_SESSION["c2text"] = "";
			}
			if ($post["c3"] <> "") {
				$_SESSION["c3h"] = " and (select c3 from tbl_reportes_online_usuario where rut=h.rut)='" . $post["c3"] . "' ";
				$_SESSION["c3text"] = " " . $Arrayc1c2c3c4[0]->c3_texto . " ='" . $post["c3"] . "' ";
				$cuenta_filter++;
			}
			else {
				$_SESSION["c3h"] = "";
				$_SESSION["c3text"] = "";
			}
			if ($post["c4"] <> "") {
				$_SESSION["c4h"] = " and (select c4 from tbl_reportes_online_usuario where rut=h.rut)='" . $post["c4"] . "' ";
				$_SESSION["c4text"] = " " . $Arrayc1c2c3c4[0]->c4_texto . " ='" . $post["c4"] . "' ";
				$cuenta_filter++;
			}
			else {
				$_SESSION["c4h"] = "";
				$_SESSION["c4text"] = "";
			}
			if ($post["rut_colaborador"] <> "") {
				$_SESSION["rut_colaborador"] = " and (select rut from tbl_reportes_online_usuario where rut=h.rut)='" . $post["rut_colaborador"] . "' ";
				$UsuCol = TraeDatosUsuario($post["rut_colaborador"]);
				$_SESSION["rut_colaborador_text"] = " Rut Colaborador = " . $post["rut_colaborador"] . " - " . $UsuCol[0]->nombre_completo . " ";
				$cuenta_filter++;
			}
			else {
				$_SESSION["rut_colaborador"] = "";
				$_SESSION["rut_colaborador_text"] = "";
			}
		}
		
		
		if ($cuenta_filter > 0) {
			$_SESSION["cuenta_filter"] = "1";
			//$texto_filtro="".$_SESSION["dato_final_filtro"]."";
		}
		else {
			$_SESSION["cuenta_filter"] = "";
		}
		
		
		
		
		if ($post["agrupacion_nueva"] == "1") {
			
			$id_agrupacion = reportes_online_maxAgrupacion();
			foreach ($post as $key => $value) {
				
				
				if ($key == "nombre_agrupacion" or $key == "agrupacion_nueva") {
					continue;
				}
				$nombre_agrupacion = $post["nombre_agrupacion"];
				
				reportes_online_Insert_agrupacion_data($id_agrupacion, $nombre_agrupacion, $key);
			}
		}
		$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/reportes_online/entorno.html"));
		$PRINCIPAL = str_replace("{ENTORNO}", reportes_online_lista_cursos_programa(FuncionesTransversalesAdmin(file_get_contents("views/reportes_online/index.html")), $_SESSION["id_empresa"], $filtro, $excel), $PRINCIPAL);
		//$_SESSION["dato_final_filtro"]
		$datos_empresa = DatosEmpresa($id_empresa);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "reportes_online_descarga") {
		
		global $Desaparece_IDSap_ListaParticipantes;
		if ($post["download_reportes_completos"] == "1") {
			
			if ($post["rc_anos"] <> "") {
				$_SESSION["op_rc_anos"] = $post["rc_anos"];
			}
			else {
				$_SESSION["op_rc_anos"] = "";
			}
			if ($post["rc_programa"] <> "") {
				$_SESSION["op_rc_programa"] = $post["rc_programa"];
			}
			else {
				$_SESSION["op_rc_programa"] = "";
			}
			if ($post["rc_gerencia"] <> "") {
				$_SESSION["op_rc_gerencia"] = $post["rc_gerencia"];
			}
			else {
				$_SESSION["op_rc_gerencia"] = "";
			}
			if ($post["rc_colaboradores"] <> "") {
				$_SESSION["op_rc_colaboradores"] = $post["rc_colaboradores"];
			}
			else {
				$_SESSION["op_rc_colaboradores"] = "";
			}
			
			$get["tipo"] = $post["rc_tipo"];
		}
		
		$cuenta_usuarios_inscritos = 0;
		$estado_finalizado = 0;
		$estado_en_proceso = 0;
		$estado_no_iniciado = 0;
		$estado_aprobado = 0;
		$estado_reprobado = 0;
		$LISTA_PREGUNTAS_ENC_SATISFACCION = "";
		
		if ($get["tipo"] == "Curso") {
			if ($get["vista"] == "graph") {
				$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/reportes_online/entorno.html"));
				$PRINCIPAL = str_replace("{ENTORNO}", FuncionesTransversalesAdmin(file_get_contents("views/reportes_online/index_graf.html")), $PRINCIPAL);
				
				
				$PRINCIPAL = str_replace("{NOMBRE-CURSO}", ($Cur[0]->nombre), $PRINCIPAL);
				
				if ($get["enc_sat"] == "1") {
					
					
					$row_excel_enc_sat .= "id_curso;curso;id_imparticion;fecha_inicio;fecha_termino\r\n";
					$row_excel_enc_sat .= "" . $datos_imparticion_V2[0]->id_curso . ";" . $datos_imparticion_V2[0]->nombre_curso . ";" . $datos_imparticion_V2[0]->codigo_inscripcion . ";" . $datos_imparticion_V2[0]->fecha_inicio . ";" . $datos_imparticion_V2[0]->fecha_termino . "\r\n";
					
					$EncSat = EncuestaSatisfaccion_IdImparticionIdCurso_data("", $get["id"]);
					
					$cuenta = count($EncSat);
					
					$row_excel_enc_sat .= "\r\n";
					$row_excel_enc_sat .= "encuestas respondidas;$cuenta\r\n";
					$row_excel_enc_sat .= "\r\n";
					
					
					$row_encuesta_satisfaccion_pregunta .= "
			<div class='card' style='width: 100%'>
				  <div class='card-header txt_align_center'>
				   Encuesta de Satisfacción
				  </div>
				  <div style='text-align:right;'>
				  	<a href='?sw=VeReportesXImp2021&amp;i=" . Encodear3($get["id_inscripcion"]) . "&amp;excel=2' class='btn btn-link'>Descargar Datos Encuestas Satisfaccion</a>
				  </div>
				  <div class='card-body    mg_20topbottom' style='padding-left: 20px;'>Encuestas Respondidas: $cuenta</div>
				  ";
					
					
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
						$Resp = EncuestaSatisfaccion_BuscaRespuestas($EncSat[0]->id_encuesta, $unico->id_pregunta, "", $get["id"]);
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
					$Com = EncuestaSatisfaccion_BuscaComentarios($EncSat[0]->id_encuesta, "", $get["id"]);
					$row_encuesta_satisfaccion_pregunta .= "
					<div class='card'>
							<div class='row'>
									<div class='col-lg-12'>
										<div class='card text-white bg-primary  text-dark mb-3' style='max-width: 100%'>
										  <div class='card-header  text-center font_18px mg_20topbottom' style='text-align: left !important;'>
										  	Comentarios
										  </div>
										</div>
									</div>
							</div>
						<div class='card-body'>
						
						";
					$row_excel_enc_sat .= "\r\n";
					$row_excel_enc_sat .= "Comentarios\r\n";
					foreach ($Com as $unicoC) {
						$row_encuesta_satisfaccion_pregunta .= "
								
										
											<div class='' style='max-width: 100%;     padding: 15px;'>
											  <div class='' style='text-align: left !important;'>
											 
													<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-arrow-right-short' viewBox='0 0 16 16'>
													  <path fill-rule='evenodd' d='M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z'/>
													</svg>
											 	" . ($unicoC->comentario) . "
											  </div>
											</div>
							";
						$row_excel_enc_sat .= "" . $unicoC->comentario . "\r\n";
					}
					
					$row_encuesta_satisfaccion_pregunta .= "
							
							</div>
					</div>
				</div>";
					
					$LISTA_PREGUNTAS_ENC_SATISFACCION = $row_encuesta_satisfaccion_pregunta;
					
					if ($get["excel"] == "2") {
						// EXCEL ENCUESTA SATISFACCION
						header('Content-Description: File Transfer');
						header('Content-Type: application/csv');
						header("Content-Disposition: attachment; filename=Participantes_ID_Imparticion_" . $codigo_inscripcion . ".csv");
						header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
						
						echo $row_excel_enc_sat;
						exit();
					}
				}
				$PRINCIPAL = str_replace("{ID_CURSO_ENV}", ($datos_imparticion_V2[0]->id_curso), $PRINCIPAL);
			}
			else {
				$DatosCurso = DatosCursoDadoId($get["id"], $_SESSION["id_empresa"]);
				
				header('Content-Description: File Transfer');
				header('Content-Type: application/csv');
				header("Content-Disposition: attachment; filename=Reporte_Online_" . $get["tipo"] . "_" . $DatosCurso[0]->nombre . "_" . $get["id"] . ".csv");
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			}
			
			$Campos = reportes_online_trae_c1c2c3c4($_SESSION["id_empresa"]);
			
			
			$array_lms_reportes_dado_id_curso = reportes_online_lms_reportes_dado_id_curso($get["id"]);
			$handle = fopen('php://output', 'w');
			
			if ($get["vista"] == "graph") {
				$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/reportes_online/entorno.html"));
				$PRINCIPAL = str_replace("{ENTORNO}", FuncionesTransversalesAdmin(file_get_contents("views/reportes_online/index_graf.html")), $PRINCIPAL);
				$Cur = DatosCursoDadoId($get["id"], $_SESSION["id_empresa"]);
				$PRINCIPAL = str_replace("{NOMBRE-CURSO}", ($Cur[0]->nombre), $PRINCIPAL);
				if ($Desaparece_IDSap_ListaParticipantes == "SI_MUESTRA") {
					$PRINCIPAL = str_replace("{TH_ID_SAP}", "<th class='width40'>IdSap</th>", $PRINCIPAL);
				}
				else {
					$PRINCIPAL = str_replace("{TH_ID_SAP}", "", $PRINCIPAL);
				}
			}
			else {
				//ob_clean();
				echo "rut;rut_completo;nombre_completo;cargo;email;empresa;" . $Campos[0]->c1_texto . ";" . $Campos[0]->c2_texto . ";" . $Campos[0]->c3_texto . ";" . $Campos[0]->c4_texto . ";rut_jefe;id_curso;curso;avance;estado;evaluacion;fecha_inicio;fecha_termino;id_inscripcion;fecha_inscripcion_desde;fecha_inscripcion_hasta\r\n";
			}
			
			foreach ($array_lms_reportes_dado_id_curso as $unico) {
				if ($unico->avance == "100") {
					$unico->resultado = round($unico->resultado);
				}
				else {
					$unico->resultado = "";
				}
				
				$usu = reportes_online_usuario_rut($unico->rut);
				
				//	print_r($Fechas_termino); exit();
				
				if ($usu[0]->nombre_completo == "") {
					continue;
				}
				
				if ($unico->avance >= 100) {
					// Chequea Resultado
					$estado_finalizado++;
					if ($unico->estado == "APROBADO") {
						$estado_aprobado++;
					}
					if ($unico->estado == "REPROBADO") {
						$estado_reprobado++;
					}
					
					$cuenta_usuarios_inscritos++;
					$resultado = round($suma_resultados / $cuenta_resultados);
					$Fecha_inicio = $Fechas_inicio;
					$Fecha_termino = $Fechas_termino;
					
				}
				elseif ($unico->avance > 0 and $unico->avance < 100) {
					$resultado = "-";
					$estado_en_proceso++;
					$cuenta_usuarios_inscritos++;
					//$estado="EN_PROCESO";
					$Fecha_inicio = $Fechas_inicio;
					$Fecha_termino = "-";
				}
				else {
					$estado_no_iniciado++;
					$cuenta_usuarios_inscritos++;
					$resultado = "-";
					$Fecha_inicio = "-";
					$Fecha_termino = "-";
				}
				
				if ($get["vista"] == "graph") {
					
					$muestra_id_sap = "";
					if ($Desaparece_IDSap_ListaParticipantes == "SI_MUESTRA") {
						$IdSap = reportes_online_usuario_rut_traeIdSapC3($unico->rut);
						$muestra_id_sap = "<td>" . $IdSap . "</td>";
					}
					
					
					$row_lista_participantes .= "
																			<tr>
																					<td>" . $unico->rut . "</td>
																					" . $muestra_id_sap . "
																					<td>" . ($usu[0]->nombre_completo) . "</td>
																					<td>" . $unico->avance . "</td>
																					<td>" . $unico->estado . "</td>
																					<td>" . $unico->resultado . "</td>
																			</tr>

																			";
				}
				else {
					$cur = reportes_online_curso_dado_idcurso($get["id"]);
					$id_inscripcion = reportes_online_inscripcion_dado_idcurso_rut($unico->rut, $get["id"]);
					
					$Fechas_inicio = reportes_online_lms_reportes_consolidado_fechas_dado_id_programa("fecha_inicio", "min", $get["id"], $unico->rut, "id_curso");
					
					
					$Fechas_termino = reportes_online_lms_reportes_consolidado_fechas_dado_id_programa("fecha_termino", "max", $get["id"], $unico->rut, "id_curso");
					
					
					echo "" . $usu[0]->rut . ";" . $usu[0]->rut_completo . ";" . $usu[0]->nombre_completo . ";" . $usu[0]->cargo . ";" . $usu[0]->email . ";" . $usu[0]->empresa . ";" . $usu[0]->c1 . ";" . $usu[0]->c2 . ";" . $usu[0]->c3 . ";" . $usu[0]->c4 . ";" . $usu[0]->jefe . ";" . $cur[0]->id_curso . ";" . $cur[0]->curso . ";" . $unico->avance . ";" . $unico->estado . ";" . $unico->resultado . ";" . $Fechas_inicio . ";" . $Fechas_termino . ";" . $id_inscripcion[0]->id_inscripcion . ";" . $id_inscripcion[0]->fecha_inicio_inscripcion . ";" . $id_inscripcion[0]->fecha_termino_inscripcion . "\r\n";
				}
				//fputcsv($handle, $row);
			}
		}
		
		if ($get["tipo"] == "ProgramaDetalle") {
			header('Content-Description: File Transfer');
			header('Content-Type: application/csv');
			header("Content-Disposition: attachment; filename=Reporte_Online_" . $get["tipo"] . "_ID_" . $get["id"] . ".csv");
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			
			
			$Campos = reportes_online_trae_c1c2c3c4($_SESSION["id_empresa"]);
			
			
			
			
			$array_lms_reportes_dado_id_programa = reportes_online_lms_reportes_dado_id_programa($get["id"]);
			
			
			
			$handle = fopen('php://output', 'w');
			//ob_clean();
			echo "rut;rut_completo;nombre_completo;cargo;email;empresa;" . $Campos[0]->c1_texto . ";" . $Campos[0]->c2_texto . ";" . $Campos[0]->c3_texto . ";" . $Campos[0]->c4_texto . ";rut_jefe;id_curso;curso;id_malla;malla;id_programa;programa;avance;estado;evaluacion;fecha_inicio;fecha_termino;id_inscripcion;fecha_inscripcion_desde;fecha_inscripcion_hasta\r\n";
			
			
			foreach ($array_lms_reportes_dado_id_programa as $unico) {
				
				
				
				if ($unico->avance == "100") {
					$unico->resultado = round($unico->resultado);
				}
				else {
					$unico->resultado = "";
				}
				
				$usu = reportes_online_usuario_rut($unico->rut);
				$cur = reportes_online_curso_dado_idcursorut($unico->id_curso, $unico->rut);
				
				$Fechas_inicio = reportes_online_lms_reportes_consolidado_fechas_dado_id_programa("fecha_inicio", "min", $unico->id_curso, $unico->rut, "id_curso");
				$Fechas_termino = reportes_online_lms_reportes_consolidado_fechas_dado_id_programa("fecha_termino", "max", $unico->id_curso, $unico->rut, "id_curso");
				$id_inscripcion = reportes_online_inscripcion_dado_idcurso_rut($unico->rut, $unico->id_curso);
				
				
				if ($usu[0]->nombre_completo == "") {
					continue;
				}
				
				if ($get["vista"] == "graph") {
					if ($unico->avance >= 100) {
						// Chequea Resultado
						$estado_finalizado++;
						$cuenta_usuarios_inscritos++;
						$resultado = round($suma_resultados / $cuenta_resultados);
						$Fecha_inicio = $Fechas_inicio;
						$Fecha_termino = $Fechas_termino;
						
					}
					elseif ($unico->avance > 0 and $unico->avance < 100) {
						$resultado = "-";
						$estado_en_proceso++;
						$cuenta_usuarios_inscritos++;
						//$estado="EN_PROCESO";
						$Fecha_inicio = $Fechas_inicio;
						$Fecha_termino = "-";
					}
					else {
						$estado_no_iniciado++;
						$cuenta_usuarios_inscritos++;
						$resultado = "-";
						$Fecha_inicio = "-";
						$Fecha_termino = "-";

					}
					
					
					$row_lista_participantes .= "
																			<tr>
																					<td>" . $unico->rut . "</td>
																					<td>" . $usu[0]->nombre_completo . "</td>
																					<td>" . $unico->avance . "</td>
																					<td>" . $unico->estado . "</td>
																					<td>" . $unico->resultado . "</td>
																			</tr>

																			";
				}
				else {
					echo "" . $usu[0]->rut . ";" . $usu[0]->rut_completo . ";" . $usu[0]->nombre_completo . ";" . $usu[0]->cargo . ";" . $usu[0]->email . ";" . $usu[0]->empresa . ";" . $usu[0]->c1 . ";" . $usu[0]->c2 . ";" . $usu[0]->c3 . ";" . $usu[0]->c4 . ";" . $usu[0]->jefe . ";" . $cur[0]->id_curso . ";" . $cur[0]->curso . ";" . $cur[0]->id_malla . ";" . $cur[0]->malla . ";" . $cur[0]->id_programa . ";" . $cur[0]->programa . ";" . $unico->avance . ";" . $unico->estado . ";" . $unico->resultado . ";" . $Fechas_inicio . ";" . $Fechas_termino . ";" . $id_inscripcion[0]->id_inscripcion . ";" . $id_inscripcion[0]->fecha_inicio_inscripcion . ";" . $id_inscripcion[0]->fecha_termino_inscripcion . "\r\n";
				}
				//fputcsv($handle, $row);
			}
			
			if ($get["vista"] == "graph") {
			}
			else {
				//ob_flush();
				//fclose($handle);
				//die();
				exit();
			}
		}
		
		if ($get["tipo"] == "ProgramaDetalle_historico") {
			header('Content-Description: File Transfer');
			header('Content-Type: application/csv');
			header("Content-Disposition: attachment; filename=Reporte_Online_" . $get["tipo"] . "_ID_" . $get["id"] . "_Historico.csv");
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			
			$Campos = reportes_online_trae_c1c2c3c4($_SESSION["id_empresa"]);
			
			
			
			
			$array_lms_reportes_dado_id_programa = reportes_online_lms_reportes_dado_id_programa($get["id"]);
			
			
			
			$handle = fopen('php://output', 'w');
			//ob_clean();
			echo "rut;rut_completo;nombre_completo;cargo;email;empresa;" . $Campos[0]->c1_texto . ";" . $Campos[0]->c2_texto . ";" . $Campos[0]->c3_texto . ";" . $Campos[0]->c4_texto . ";rut_jefe;vigencia;id_curso;curso;id_malla;malla;id_programa;programa;avance;estado;evaluacion;fecha_inicio;fecha_termino;id_inscripcion;fecha_inscripcion_desde;fecha_inscripcion_hasta\r\n";
			
			
			foreach ($array_lms_reportes_dado_id_programa as $unico) {
				
				
				
				if ($unico->avance == "100") {
					$unico->resultado = round($unico->resultado);
				}
				else {
					$unico->resultado = "";
				}
				
				$usu = reportes_online_usuario_rut($unico->rut);
				$cur = reportes_online_curso_dado_idcursorut($unico->id_curso, $unico->rut);
				$curso = reportes_online_curso_dado_idcurso($unico->id_curso);
				$Fechas_inicio = reportes_online_lms_reportes_consolidado_fechas_dado_id_programa("fecha_inicio", "min", $unico->id_curso, $unico->rut, "id_curso");
				$Fechas_termino = reportes_online_lms_reportes_consolidado_fechas_dado_id_programa("fecha_termino", "max", $unico->id_curso, $unico->rut, "id_curso");
				$id_inscripcion = reportes_online_inscripcion_dado_idcurso_rut($unico->rut, $unico->id_curso);
				
				
				if ($usu[0]->nombre_completo == "") {
					$vigente = "NO_VIGENTE";
				}
				else {
					$vigente = "VIGENTE";
				}
				
				if ($get["vista"] == "graph") {
					if ($unico->avance >= 100) {
						// Chequea Resultado
						$estado_finalizado++;
						$cuenta_usuarios_inscritos++;
						$resultado = round($suma_resultados / $cuenta_resultados);
						$Fecha_inicio = $Fechas_inicio;
						$Fecha_termino = $Fechas_termino;
						
					}
					elseif ($unico->avance > 0 and $unico->avance < 100) {
						
						$resultado = "-";
						$estado_en_proceso++;
						$cuenta_usuarios_inscritos++;
						//$estado="EN_PROCESO";
						$Fecha_inicio = $Fechas_inicio;
						$Fecha_termino = "-";
					}
					else {
						$estado_no_iniciado++;
						$cuenta_usuarios_inscritos++;
						$resultado = "-";
						$Fecha_inicio = "-";
						$Fecha_termino = "-";
						
					}
					
					
					$row_lista_participantes .= "
																			<tr>
																					<td>" . $unico->rut . "</td>
																					<td>" . $usu[0]->nombre_completo . "</td>
																					<td>" . $unico->avance . "</td>
																					<td>" . $unico->estado . "</td>
																					<td>" . $unico->resultado . "</td>
																			</tr>

																			";
				}
				else {
					echo "" . $unico->rut . ";" . $usu[0]->rut_completo . ";" . $usu[0]->nombre_completo . ";" . $usu[0]->cargo . ";" . $usu[0]->email . ";" . $usu[0]->empresa . ";" . $usu[0]->c1 . ";" . $usu[0]->c2 . ";" . $usu[0]->c3 . ";" . $usu[0]->c4 . ";" . $usu[0]->jefe . ";" . $vigente . ";" . $curso[0]->id_curso . ";" . $curso[0]->curso . ";" . $cur[0]->id_malla . ";" . $cur[0]->malla . ";" . $curso[0]->id_programa . ";" . $curso[0]->programa . ";" . $unico->avance . ";" . $unico->estado . ";" . $unico->resultado . ";" . $Fechas_inicio . ";" . $Fechas_termino . ";" . $id_inscripcion[0]->id_inscripcion . ";" . $id_inscripcion[0]->fecha_inicio_inscripcion . ";" . $id_inscripcion[0]->fecha_termino_inscripcion . "\r\n";
				}
				//fputcsv($handle, $row);
			}
			
			if ($get["vista"] == "graph") {
			}
			else {
				//ob_flush();
				//fclose($handle);
				//die();
				exit();
			}
		}
		
		if ($get["tipo"] == "ProgramaConsolidado") {
			if ($get["vista"] == "graph") {
				$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/reportes_online/entorno.html"));
				$PRINCIPAL = str_replace("{ENTORNO}", FuncionesTransversalesAdmin(file_get_contents("views/reportes_online/index_graf_agrup.html")), $PRINCIPAL);
				
			}
			else {
				header('Content-Description: File Transfer');
				header('Content-Type: application/csv');
				header("Content-Disposition: attachment; filename=Reporte_Online_" . $get["tipo"] . "_ID_" . $get["id"] . ".csv");
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			}
			
			$Campos = reportes_online_c1_c2_c3_c4($_SESSION["id_empresa"]);
			
			
			
			$array_lms_reportes_dado_id_programa_consolidado = reportes_online_lms_reportes_consolidado_dado_id_programa($get["id"]);
			
			if ($get["vista"] == "graph") {
				$Prog = ObtieneDatosProgramasPorEmpresa($get["id"], $_SESSION["id_empresa"]);
				$PRINCIPAL = str_replace("{NOMBRE-CURSO}", ($Prog[0]->nombre_programa), $PRINCIPAL);
				if ($Desaparece_IDSap_ListaParticipantes == "SI_MUESTRA") {
					$PRINCIPAL = str_replace("{TH_ID_SAP}", "<th class='width40'>IdSap</th>", $PRINCIPAL);
				}
				else {
					$PRINCIPAL = str_replace("{TH_ID_SAP}", "", $PRINCIPAL);
				}
			}
			else {
				$handle = fopen('php://output', 'w');
				//ob_clean();
				echo "rut;rut_completo;nombre_completo;cargo;email;empresa;" . $Campos[1] . ";" . $Campos[2] . ";" . $Campos[3] . ";" . $Campos[4] . ";rut_jefe;id_programa;programa;id_malla;malla;avance;estado;evaluacion;fecha_inicio;fecha_termino\r\n";
			}
			$rut_antiguo = "";
			
			foreach ($array_lms_reportes_dado_id_programa_consolidado as $unico2) {
				
				
				
				$usu = reportes_online_usuario_rut($unico2->rut);
				if ($usu[0]->nombre_completo == "") {
					continue;
				}
				$CuentaLineaFull++;
				$CuentaCursos = reportes_online_lms_reportes_consolidado_cuenta_cursos_dado_id_programa($get["id"], $unico2->rut);
				
				if ($get["vista"] == "graph") {
				}
				else {
					$Fechas_inicio = reportes_online_lms_reportes_consolidado_fechas_dado_id_programa("fecha_inicio", "min", $get["id"], $unico2->rut, "id_programa");
					$Fechas_termino = reportes_online_lms_reportes_consolidado_fechas_dado_id_programa("fecha_termino", "max", $get["id"], $unico2->rut, "id_programa");
				}
				
				$Reporte = reportes_online_lms_reportes_busca_avance_resultado_dado_rut_id_curso($unico2->rut, $unico2->id_curso);
				
				if ($rut_antiguo == "" or $rut_antiguo <> $unico2->rut) {
					//reset
					$suma_avance = 0;
					$cuenta_avance = 0;
					$cuenta_resultado = 0;
					$suma_resultado = 0;
					$suma_avance = 0;
					$Estado_Resultado = "";
					$Estado_Avance = "";

					$cuenta_avance++;
					$suma_avance = $suma_avance + $Reporte[0]->avance;
					if ($Reporte[0]->resultado <> "-") {
						$suma_resultado = $suma_resultado + $Reporte[0]->resultado;
						$cuenta_resultado++;
					}
					
					$rut_antiguo = $unico2->rut;
				}
				elseif ($rut_antiguo == $unico2->rut) {
					$cuenta_avance++;
					$suma_avance = $suma_avance + $Reporte[0]->avance;
					if ($Reporte[0]->resultado <> "-") {
						$suma_resultado = $suma_resultado + $Reporte[0]->resultado;
						$cuenta_resultado++;
					}
				}
				
				
				
				if ($cuenta_avance > 0) {
					$Estado_Avance = round($suma_avance / $cuenta_avance);
				}
				else {
					$Estado_Avance = "";
				}
				
				
				if ($cuenta_resultado > 0) {
					$Estado_Resultado = round($suma_resultado / $cuenta_resultado);
				}
				else {
					$Estado_Resultado = "";
				}
				
				
				
				if ($Estado_Avance >= 100) {
					// Chequea Resultado
					$estado_finalizado++;
					$cuenta_usuarios_inscritos++;
					
					$estado = "FINALIZADO";
					$Fecha_inicio = $Fechas_inicio;
					$Fecha_termino = $Fechas_termino;
					
				}
				elseif ($Estado_Avance > 0 and $Estado_Avance < 100) {
										$Estado_Resultado = "-";
					$estado_en_proceso++;
					$cuenta_usuarios_inscritos++;
					$estado = "EN_PROCESO";
					$Fecha_inicio = $Fechas_inicio;
					$Fecha_termino = "-";
				}
				else {
					$estado_no_iniciado++;
					$cuenta_usuarios_inscritos++;
					$Estado_Resultado = "-";
					$estado = "NO_INICIADO";
					$Fecha_inicio = "-";
					$Fecha_termino = "-";
					
				}
				
				
				if ($CuentaCursos == $cuenta_avance) {
					if ($get["vista"] == "graph") {
						$muestra_id_sap = "";
						if ($Desaparece_IDSap_ListaParticipantes == "SI_MUESTRA") {
							$IdSap = reportes_online_usuario_rut_traeIdSapC3($rut_antiguo);
							$muestra_id_sap = "<td>" . $IdSap . "</td>";
						}
						
						$row_lista_participantes .= "
																										<tr>
																												<td>" . $rut_antiguo . "</td>
																												" . $muestra_id_sap . "
																												<td>" . ($usu[0]->nombre_completo) . "</td>
																												<td>" . $Estado_Avance . "</td>
																												<td>" . $estado . "</td>
																												<td>" . $Estado_Resultado . "</td>
																										</tr>

																										";
					}
					else {
						//$IdMalla=reportes_online_Malla_dado_id_curso_rut_programa($usu[0]->rut, $unico2->id_curso, $get["id"]);
						//$Malla=reportes_online_nombremalla($IdMalla);
						//$Prog=reportes_online_curso_dado_programa($get["id"]);
						
						echo "" . $rut_antiguo . ";" . $usu[0]->rut_completo . ";" . $usu[0]->nombre_completo . ";" . $usu[0]->cargo . ";" . $usu[0]->email . ";" . $usu[0]->empresa . ";" . $usu[0]->c1 . ";" . $usu[0]->c2 . ";" . $usu[0]->c3 . ";" . $usu[0]->c4 . ";" . $usu[0]->jefe . ";" . $unico2->id_programa . ";" . $unico2->programa . ";" . $unico2->id_malla . ";" . $unico2->malla . ";" . $Estado_Avance . ";" . $estado . ";" . $Estado_Resultado . ";" . $Fecha_inicio . ";" . $Fecha_termino . "\r\n";
					}
				}
			}
	
			
			if ($get["vista"] == "graph") {
			}
			else {
				//ob_flush();
				//fclose($handle);
				//die();
				exit();
			}
		}
		
		if ($get["tipo"] == "Colaborador") {
			if ($get["vista"] == "graph") {
				$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/reportes_online/entorno.html"));
				$PRINCIPAL = str_replace("{ENTORNO}", FuncionesTransversalesAdmin(file_get_contents("views/reportes_online/index_graf.html")), $PRINCIPAL);
			}
			else {
				header('Content-Description: File Transfer');
				header('Content-Type: application/csv');
				header("Content-Disposition: attachment; filename=Reporte_Online_" . $get["tipo"] . "_RUT_" . $_SESSION["rut_colaborador"] . ".csv");
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			}
			
			$Campos = reportes_online_trae_c1c2c3c4($_SESSION["id_empresa"]);

			$array_lms_reportes_dado_rut = reportes_online_lms_reportes_dado_rut($_SESSION["op_rc_colaboradores"]);
			$handle = fopen('php://output', 'w');
			
			if ($get["vista"] == "graph") {
				$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/reportes_online/entorno.html"));
				$PRINCIPAL = str_replace("{ENTORNO}", FuncionesTransversalesAdmin(file_get_contents("views/reportes_online/index_graf.html")), $PRINCIPAL);
			}
			else {
				//ob_clean();
				echo "rut;rut_completo;nombre_completo;cargo;email;empresa;" . $Campos[0]->c1_texto . ";" . $Campos[0]->c2_texto . ";" . $Campos[0]->c3_texto . ";" . $Campos[0]->c4_texto . ";rut_jefe;id_curso;curso;id_programa;programa;avance;estado;evaluacion;curso_opcional;fecha_inicio;fecha_termino;id_inscripcion;fecha_inscripcion_desde;fecha_inscripcion_hasta\r\n";
			}
			
			foreach ($array_lms_reportes_dado_rut as $unico) {
				$usu = reportes_online_usuario_rut($unico->rut);
				$cur = reportes_online_curso_dado_idcurso_lmsCurso($unico->id_curso, $unico->id_programa);
				//$reporte=reportes_online_usuario_rut_idCurso_lmsReportes($unico->rut, $unico->id_curso);
				$Fechas_inicio = reportes_online_lms_reportes_consolidado_fechas_dado_id_programa("fecha_inicio", "min", $unico->id_curso, $unico->rut, "id_curso");
				$Fechas_termino = reportes_online_lms_reportes_consolidado_fechas_dado_id_programa("fecha_termino", "max", $unico->id_curso, $unico->rut, "id_curso");
				
				$id_inscripcion = reportes_online_inscripcion_dado_idcurso_rut($unico->rut, $unico->id_curso);
				
				
				if ($unico->avance == "100") {
					$unico->resultado = round($unico->resultado);
				}
				else {
					$unico->resultado = "";
				}
				
				//$unico->estado		= $reporte[0]->estado;
				//$unico->resultado = $reporte[0]->resultado;
				
				if ($usu[0]->nombre_completo == "") {
					continue;
				}
				
				if ($get["vista"] == "graph") {
					if ($unico->avance >= 100) {
						// Chequea Resultado
						$estado_finalizado++;
						$cuenta_usuarios_inscritos++;
						$resultado = round($suma_resultados / $cuenta_resultados);
						$Fecha_inicio = $Fechas_inicio;
						$Fecha_termino = $Fechas_termino;
						
					}
					elseif ($unico->avance > 0 and $unico->avance < 100) {
						// Chequea Resultado
						$resultado = "-";
						$estado_en_proceso++;
						$cuenta_usuarios_inscritos++;
						//$estado="EN_PROCESO";
						$Fecha_inicio = $Fechas_inicio;
						$Fecha_termino = "-";
					}
					else {
						$estado_no_iniciado++;
						$cuenta_usuarios_inscritos++;
						$resultado = "-";
						$Fecha_inicio = "-";
						$Fecha_termino = "-";

					}
					
					
					$row_lista_participantes .= "
																			<tr>
																					<td>" . $unico->rut . "</td>
																					<td>" . $usu[0]->nombre_completo . "</td>
																					<td>" . $unico->avance . "</td>
																					<td>" . $unico->estado . "</td>
																					<td>" . $unico->resultado . "</td>
																			</tr>

																			";
				}
				else {
					echo "" . $usu[0]->rut . ";" . $usu[0]->rut_completo . ";" . $usu[0]->nombre_completo . ";" . $usu[0]->cargo . ";" . $usu[0]->email . ";" . $usu[0]->empresa . ";" . $usu[0]->c1 . ";" . $usu[0]->c2 . ";" . $usu[0]->c3 . ";" . $usu[0]->c4 . ";" . $usu[0]->jefe . ";" . $unico->id_curso . ";" . $cur[0]->curso . ";" . $unico->id_programa . ";" . $cur->programa . ";" . $unico->avance . ";" . $unico->estado . ";" . $unico->resultado . ";" . $unico->curso_opcional . ";" . $Fechas_inicio . ";" . $Fechas_termino . ";" . $id_inscripcion[0]->id_inscripcion . ";" . $id_inscripcion[0]->id_inscripcion . ";" . $id_inscripcion[0]->fecha_inicio_inscripcion . ";" . $id_inscripcion[0]->fecha_termino_inscripcion . "\r\n";
				}
				//fputcsv($handle, $row);
			}
			
			if ($get["vista"] == "graph") {
			}
			else {
				//ob_flush();
				//fclose($handle);
				//die();
				exit();
			}
		}
		
		if ($get["tipo"] == "Completo") {
			if ($get["vista"] == "graph") {
				$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/reportes_online/entorno.html"));
				$PRINCIPAL = str_replace("{ENTORNO}", FuncionesTransversalesAdmin(file_get_contents("views/reportes_online/index_graf.html")), $PRINCIPAL);
			}
			else {
				header('Content-Description: File Transfer');
				header('Content-Type: application/csv');
				header("Content-Disposition: attachment; filename=Reporte_Online_" . $get["tipo"] . ".csv");
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			}
			
			
			$Campos = reportes_online_trae_c1c2c3c4($_SESSION["id_empresa"]);

			
			$array_lms_reportes_dado_completo = reportes_online_lms_reportes_dado_completo();
			$handle = fopen('php://output', 'w');
			
			if ($get["vista"] == "graph") {
				$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/reportes_online/entorno.html"));
				$PRINCIPAL = str_replace("{ENTORNO}", FuncionesTransversalesAdmin(file_get_contents("views/reportes_online/index_graf.html")), $PRINCIPAL);
			}
			else {
				//ob_clean();
				echo "rut;rut_completo;nombre_completo;cargo;email;empresa;" . $Campos[0]->c1_texto . ";" . $Campos[0]->c2_texto . ";" . $Campos[0]->c3_texto . ";" . $Campos[0]->c4_texto . ";rut_jefe;id_curso;curso;id_programa;programa;id_malla;malla;avance;estado;evaluacion;curso_opcional;fecha_inicio;fecha_termino;Anho_Inscripcion;id_inscripcion;fecha_inscripcion_desde;fecha_inscripcion_hasta;numero_horas\r\n";
			}
			
			foreach ($array_lms_reportes_dado_completo as $unico) {
				$usu = reportes_online_usuario_rut($unico->rut);
				$cur = reportes_online_curso_dado_idcursorut($unico->id_curso, $unico->rut);
				if ($cur[0]->curso == "") {
					$cur = reportes_online_curso_rel_lms_malla_curso($unico->id_curso);
				}
				//$reporte=reportes_online_usuario_rut_idCurso_lmsReportes($unico->rut, $unico->id_curso);
				$Fechas_inicio = reportes_online_lms_reportes_consolidado_fechas_dado_id_programa("fecha_inicio", "min", $unico->id_curso, $unico->rut, "id_curso");
				$Fechas_termino = reportes_online_lms_reportes_consolidado_fechas_dado_id_programa("fecha_termino", "max", $unico->id_curso, $unico->rut, "id_curso");
				
				$id_inscripcion = reportes_online_inscripcion_dado_idcurso_rut($unico->rut, $unico->id_curso);
				
				
				if ($unico->avance == "100") {
					$unico->resultado = round($unico->resultado);
				}
				else {
					$unico->resultado = "";
				}
				
				if ($usu[0]->nombre_completo == "") {
					continue;
				}
				
				if ($get["vista"] == "graph") {
					if ($unico->avance >= 100) {
						// Chequea Resultado
						$estado_finalizado++;
						$cuenta_usuarios_inscritos++;
						$resultado = round($suma_resultados / $cuenta_resultados);
						$Fecha_inicio = $Fechas_inicio;
						$Fecha_termino = $Fechas_termino;
						
					}
					elseif ($unico->avance > 0 and $unico->avance < 100) {
						// Chequea Resultado
												$resultado = "-";
						$estado_en_proceso++;
						$cuenta_usuarios_inscritos++;
						//$estado="EN_PROCESO";
						$Fecha_inicio = $Fechas_inicio;
						$Fecha_termino = "-";
					}
					else {
						$estado_no_iniciado++;
						$cuenta_usuarios_inscritos++;
						$resultado = "-";
						$Fecha_inicio = "-";
						$Fecha_termino = "-";

					}
					
					
					$row_lista_participantes .= "
																			<tr>
																					<td>" . $unico->rut . "</td>
																					<td>" . $usu[0]->nombre_completo . "</td>
																					<td>" . $unico->avance . "</td>
																					<td>" . $unico->estado . "</td>
																					<td>" . $unico->resultado . "</td>
																			</tr>

																			";
				}
				else {
					echo "" . $usu[0]->rut . ";" . $usu[0]->rut_completo . ";" . $usu[0]->nombre_completo . ";" . $usu[0]->cargo . ";" . $usu[0]->email . ";" . $usu[0]->empresa . ";" . $usu[0]->c1 . ";" . $usu[0]->c2 . ";" . $usu[0]->c3 . ";" . $usu[0]->c4 . ";" . $usu[0]->jefe . ";" . $unico->id_curso . ";" . $cur[0]->curso . ";" . $cur[0]->id_programa . ";" . $cur[0]->programa . ";" . $cur[0]->id_malla . ";" . $cur[0]->malla . ";" . $unico->avance . ";" . $unico->estado . ";" . $unico->resultado . ";" . $unico->curso_opcional . ";" . $Fechas_inicio . ";" . $Fechas_termino . ";" . $unico->year . ";" . $id_inscripcion[0]->id_inscripcion . ";" . $id_inscripcion[0]->fecha_inicio_inscripcion . ";" . $id_inscripcion[0]->fecha_termino_inscripcion . ";" . $unico->numero_horas . "\r\n";
				}
				//fputcsv($handle, $row);
			}
			
			if ($get["vista"] == "graph") {
			}
			else {
				//	ob_flush();
				//	fclose($handle);
				//	die();
				exit();
			}
		}
		
		if ($get["tipo"] == "Historico") {
			if ($get["vista"] == "graph") {
				$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/reportes_online/entorno.html"));
				$PRINCIPAL = str_replace("{ENTORNO}", FuncionesTransversalesAdmin(file_get_contents("views/reportes_online/index_graf.html")), $PRINCIPAL);
			}
			else {
				header('Content-Description: File Transfer');
				header('Content-Type: application/csv');
				header("Content-Disposition: attachment; filename=Reporte_Historico_" . $get["tipo"] . ".csv");
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			}
			
			
			$Campos = reportes_online_trae_c1c2c3c4($_SESSION["id_empresa"]);

			
			$array_lms_reportes_dado_completo = reportes_online_lms_reportes_dado_completo();
			$handle = fopen('php://output', 'w');
			
			if ($get["vista"] == "graph") {
				$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/reportes_online/entorno.html"));
				$PRINCIPAL = str_replace("{ENTORNO}", FuncionesTransversalesAdmin(file_get_contents("views/reportes_online/index_graf.html")), $PRINCIPAL);
			}
			else {
				//ob_clean();
				
				
				echo "rut;rut_completo;nombre_completo;cargo;email;empresa;" . $Campos[0]->c1_texto . ";" . $Campos[0]->c2_texto . ";" . $Campos[0]->c3_texto . ";" . $Campos[0]->c4_texto . ";rut_jefe;vigencia_colaborador;id_curso;curso;id_programa;programa;id_malla;malla;avance;estado;evaluacion;curso_opcional;fecha_inicio;fecha_termino;Anho_Inscripcion;id_inscripcion;fecha_inscripcion_desde;fecha_inscripcion_hasta;numero_horas\r\n";
			}
			
			foreach ($array_lms_reportes_dado_completo as $unico) {
				if ($ultimo_rut == $unico->rut) {
				}
				else {
					$usu = reportes_online_usuario_rut($unico->rut);
				}
				if ($ultimo_curso == $unico->id_curso) {
				}
				else {
					$curso = reportes_online_curso_dado_idcurso_lms_curso($unico->id_curso);
				}
				
				
				$cur = reportes_online_curso_dado_idcursorut($unico->id_curso, $unico->rut);
				if ($cur[0]->curso == "") {
					$cur = reportes_online_curso_rel_lms_malla_curso($unico->id_curso);
				}
				//$reporte=reportes_online_usuario_rut_idCurso_lmsReportes($unico->rut, $unico->id_curso);
				$Fechas_inicio = reportes_online_lms_reportes_consolidado_fechas_dado_id_programa("fecha_inicio", "min", $unico->id_curso, $unico->rut, "id_curso");
				$Fechas_termino = reportes_online_lms_reportes_consolidado_fechas_dado_id_programa("fecha_termino", "max", $unico->id_curso, $unico->rut, "id_curso");
				$id_inscripcion = reportes_online_inscripcion_dado_idcurso_rut($unico->rut, $unico->id_curso);
				
				$ultimo_rut = $unico->rut;
				$ultimo_curso = $unico->id_curso;
				
				if ($unico->avance == "100") {
					$unico->resultado = round($unico->resultado);
				}
				else {
					$unico->resultado = "";
				}
				if ($usu[0]->nombre_completo == "") {
					$vigencia = "NO_VIGENTE";
				}
				else {
					$vigencia = "VIGENTE";
				}
				echo "" . $unico->rut . ";" . $usu[0]->rut_completo . ";" . $usu[0]->nombre_completo . ";" . $usu[0]->cargo . ";" . $usu[0]->email . ";" . $usu[0]->empresa . ";" . $usu[0]->c1 . ";" . $usu[0]->c2 . ";" . $usu[0]->c3 . ";" . $usu[0]->c4 . ";" . $usu[0]->jefe . ";" . $vigencia . ";" . $unico->id_curso . ";" . $curso[0]->nombre . ";" . $curso[0]->id_programa_global . ";" . $curso[0]->programa . ";" . $cur[0]->id_malla . ";" . $cur[0]->malla . ";" . $unico->avance . ";" . $unico->estado . ";" . $unico->resultado . ";" . $unico->curso_opcional . ";" . $Fechas_inicio . ";" . $Fechas_termino . ";" . $unico->year . ";" . $id_inscripcion[0]->id_inscripcion . ";" . $id_inscripcion[0]->fecha_inicio_inscripcion . ";" . $id_inscripcion[0]->fecha_termino_inscripcion . ";" . $unico->numero_horas . "\r\n";
			}
			
			exit();
		}
		
		if ($get["tipo"] == "Agrupaciones") {
			header('Content-Description: File Transfer');
			header('Content-Type: application/csv');
			header("Content-Disposition: attachment; filename=Reporte_Online_" . $get["tipo"] . "_ID_" . $get["id"] . ".csv");
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			
			$Campos = reportes_online_trae_c1c2c3c4($_SESSION["id_empresa"]);

			
			$array_lms_reportes_dado_id_agrupacion = reportes_online_lms_reportes_dado_id_agrupacion($get["id"]);
			
			$handle = fopen('php://output', 'w');

			
			$cursos_nombres = reportes_online_ListaCursoDadoIdAgrupacion($array_lms_reportes_dado_id_agrupacion[0]->id_agrupacion);
			$cuenta_cursos = count($cursos_nombres);
			foreach ($cursos_nombres as $unico_curso) {
				$cuenta_cursos_2++;
				//$row_cursos_nombres.="".$unico_curso->nombre_curso.";OTA;";
				$row_cursos_nombres .= "" . $unico_curso->nombre_curso . ";";
				if ($cuenta_cursos_2 >= $cuenta_cursos) {
					$row_query_curso .= " id_curso='" . $unico_curso->id_curso . "' ";
				}
				else {
					$row_query_curso .= " id_curso='" . $unico_curso->id_curso . "' or ";
				}
			}
			//ob_clean();
			echo "rut;rut_completo;nombre_completo;cargo;email;empresa;" . $Campos[0]->c1_texto . ";" . $Campos[0]->c2_texto . ";" . $Campos[0]->c3_texto . ";" . $Campos[0]->c4_texto . ";rut_jefe;agrupacion;" . $row_cursos_nombres . "\r\n";
			//}
			
			
			
			
			foreach ($array_lms_reportes_dado_id_agrupacion as $unico) {
				$estado = "";
				
				$Lista_Rut_Completa = reportes_onlines_usuarios_detalle_agrupaciones($row_query_curso);
				foreach ($Lista_Rut_Completa as $unico_per) {
					$estado = "";
					foreach ($cursos_nombres as $unico_curso) {
						$Reporte = reportes_online_lms_reporte($unico_curso->id_curso, $unico_per->rut);
						$estado .= "" . $Reporte[0]->estado . ";";
						//$estado.="".data_rel_id_inscripcion_curso_rut_unico_limit_ultima_data($unico_per->rut, $unico_curso->id_curso).";";
					}
					
					$usu = reportes_online_usuario_rut($unico_per->rut);
					if ($usu[0]->nombre_completo == "") {
						continue;
					}
					
					
					echo "" . $usu[0]->rut . ";" . $usu[0]->rut_completo . ";" . $usu[0]->nombre_completo . ";" . $usu[0]->cargo . ";" . $usu[0]->email . ";" . $usu[0]->empresa . ";" . $usu[0]->c1 . ";" . $usu[0]->c2 . ";" . $usu[0]->c3 . ";" . $usu[0]->c4 . ";" . $usu[0]->jefe . ";" . $unico->nombre . ";" . $estado . "\r\n";
				}
				//fputcsv($handle, $row);
			}
			
			
			//ob_flush();
			//fclose($handle);
			//die();
			exit();
		}
		
		if ($get["tipo"] == "AgrupacionesHistorico") {
			header('Content-Description: File Transfer');
			header('Content-Type: application/csv');
			header("Content-Disposition: attachment; filename=Reporte_Agrupacion_Historico_" . $get["tipo"] . ".csv");
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			$Campos = reportes_online_trae_c1c2c3c4($_SESSION["id_empresa"]);
			$array_lms_reportes_dado_id_agrupacion = reportes_online_lms_reportes_dado_id_agrupacion($get["id"]);
			$handle = fopen('php://output', 'w');
			$cursos_nombres = reportes_online_ListaCursoDadoIdAgrupacion($array_lms_reportes_dado_id_agrupacion[0]->id_agrupacion);
			$cuenta_cursos = count($cursos_nombres);
			foreach ($cursos_nombres as $unico_curso) {
				$cuenta_cursos_2++;
				//$row_cursos_nombres.="".$unico_curso->nombre_curso.";OTA;";
				$row_cursos_nombres .= "" . $unico_curso->nombre_curso . ";";
				if ($cuenta_cursos_2 >= $cuenta_cursos) {
					$row_query_curso .= " id_curso='" . $unico_curso->id_curso . "' ";
				}
				else {
					$row_query_curso .= " id_curso='" . $unico_curso->id_curso . "' or ";
				}
			}
			echo "rut;rut_completo;nombre_completo;cargo;email;empresa;" . $Campos[0]->c1_texto . ";" . $Campos[0]->c2_texto . ";" . $Campos[0]->c3_texto . ";" . $Campos[0]->c4_texto . ";rut_jefe;vigencia_colaborador;id_curso;curso;id_programa;programa;id_malla;malla;avance;estado;evaluacion;curso_opcional;fecha_inicio;fecha_termino;id_inscripcion;fecha_inscripcion_desde;fecha_inscripcion_hasta\r\n";
			
			
			$array_lms_reportes_dado_completo = reportes_online_lms_reportes_dado_completo_or_id_course($row_query_curso);
			
			foreach ($array_lms_reportes_dado_completo as $unico) {
				$usu = reportes_online_usuario_rut($unico->rut);
				$cur = reportes_online_curso_dado_idcursorut($unico->id_curso, $unico->rut);
				$curso = reportes_online_curso_dado_idcurso($unico->id_curso);
				//$reporte=reportes_online_usuario_rut_idCurso_lmsReportes($unico->rut, $unico->id_curso);
				$Fechas_inicio = reportes_online_lms_reportes_consolidado_fechas_dado_id_programa("fecha_inicio", "min", $unico->id_curso, $unico->rut, "id_curso");
				$Fechas_termino = reportes_online_lms_reportes_consolidado_fechas_dado_id_programa("fecha_termino", "max", $unico->id_curso, $unico->rut, "id_curso");
				$id_inscripcion = reportes_online_inscripcion_dado_idcurso_rut($unico->rut, $unico->id_curso);
				
				if ($unico->avance == "100") {
					$unico->resultado = round($unico->resultado);
				}
				else {
					$unico->resultado = "";
				}
				
				if ($usu[0]->nombre_completo == "") {
					$vigencia = "NO_VIGENTE";
				}
				else {
					$vigencia = "VIGENTE";
				}
				echo "" . $unico->rut . ";" . $usu[0]->rut_completo . ";" . $usu[0]->nombre_completo . ";" . $usu[0]->cargo . ";" . $usu[0]->email . ";" . $usu[0]->empresa . ";" . $usu[0]->c1 . ";" . $usu[0]->c2 . ";" . $usu[0]->c3 . ";" . $usu[0]->c4 . ";" . $usu[0]->jefe . ";" . $vigencia . ";" . $unico->id_curso . ";" . $curso[0]->curso . ";" . $curso[0]->id_programa . ";" . $curso[0]->programa . ";" . $cur[0]->id_malla . ";" . $cur[0]->malla . ";" . $unico->avance . ";" . $unico->estado . ";" . $unico->resultado . ";" . $unico->curso_opcional . ";" . $Fechas_inicio . ";" . $Fechas_termino . ";" . $id_inscripcion[0]->id_inscripcion . ";" . $id_inscripcion[0]->fecha_inicio_inscripcion . ";" . $id_inscripcion[0]->fecha_termino_inscripcion . "\r\n";
			}
			
			exit();
		}
		
		if ($get["tipo"] == "AgrupacionesConsolidadoC1") {
			$array_lms_reportes_dado_id_agrupacion = reportes_online_lms_reportes_dado_id_agrupacion($get["id"]);
			
			
			header('Content-Description: File Transfer');
			header('Content-Type: application/csv');
			header("Content-Disposition: attachment; filename=Reporte_Online_Consolidado_" . $array_lms_reportes_dado_id_agrupacion[0]->nombre . "_" . $get["tipo"] . "_ID_" . $get["id"] . ".csv");
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			
			$Campos = reportes_online_trae_c1c2c3c4($_SESSION["id_empresa"]);
			
			
			$nombreC1 = $Campos[0]->c1_texto;
			$CampoC1 = $Campos[0]->c1;
			
			$handle = fopen('php://output', 'w');
			
			//if($get["vista"]=="graph"){
			//$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/reportes_online/entorno.html"));
			//$PRINCIPAL = str_replace("{ENTORNO}", FuncionesTransversalesAdmin(file_get_contents("views/reportes_online/index_graf.html")), $PRINCIPAL);
			//} else {
			
			$cursos_nombres = reportes_online_ListaCursoDadoIdAgrupacion($array_lms_reportes_dado_id_agrupacion[0]->id_agrupacion);
			$cuenta_cursos = count($cursos_nombres);
			foreach ($cursos_nombres as $unico_curso) {
				$cuenta_cursos_2++;
				//$row_cursos_nombres.="".$unico_curso->nombre_curso.";OTA;";
				$row_cursos_nombres_estadisticas .= "" . $unico_curso->nombre_curso . ";Inscritos;Realizados;Aprobados;Reprobados;%Avance;";
				if ($cuenta_cursos_2 >= $cuenta_cursos) {
					$row_query_curso .= " h.id_curso='" . $unico_curso->id_curso . "' ";
				}
				else {
					$row_query_curso .= " h.id_curso='" . $unico_curso->id_curso . "' or ";
				}
			}
			
			//$row_total_nombres.="Total;Inscritos;Asistentes;Aprobados;Reprobados;%Avance;";
			
			//ob_clean();
			echo $nombreC1 . ";" . $row_cursos_nombres_estadisticas . $row_total_nombres . "\r\n";
			//}
			
			
			
			
			foreach ($array_lms_reportes_dado_id_agrupacion as $unico) {
				$estado = "";
				
				$Lista_Gerencias = reportes_onlines_usuarios_detalle_agrupaciones_groupbyC1($row_query_curso);
				//solo agrupa gerencia

				foreach ($Lista_Gerencias as $unico_per) {
					$estado = "";
					foreach ($cursos_nombres as $unico_curso) {
						$Array_Inscritos = reportes_online_usuariosInscritosOnline($unico_curso->id_curso, $unico_per->c1);
						
						$Inscritos = 0;
						$Inscritos = count($Array_Inscritos);
						$Finalizados = 0;
						$Aprobados = 0;
						$Reprobados = 0;
						foreach ($Array_Inscritos as $Uniavance) {
							if ($Uniavance->avance == "100") {
								$Finalizados++;
							}
							if ($Uniavance->estado == "APROBADO") {
								$Aprobados++;
							}
							if ($Uniavance->estado == "REPROBADO") {
								$Reprobados++;
							}
						}
						
						
						if ($Inscritos > 0) {
							$avance = round(100 * $Finalizados / $Inscritos);
						}
						else {
							$avance = "0";
						}
						
						
						$estado .= "" . $unico_curso->nombre_curso . ";" . $Inscritos . ";" . $Finalizados . ";" . $Aprobados . ";" . $Reprobados . ";" . $avance . ";";
						//$estado.="".data_rel_id_inscripcion_curso_rut_unico_limit_ultima_data($unico_per->rut, $unico_curso->id_curso).";";
					}
					
					//$usu=reportes_online_usuario_rut($unico_per->rut);
					//if($usu[0]->nombre_completo=="") {continue;}
					
					
					echo "" . $unico_per->c1 . ";" . $estado . "\r\n";
				}
				//fputcsv($handle, $row);
			}
			
			
			//ob_flush();
			//fclose($handle);
			//die();
			exit();
		}
		
		
		$PRINCIPAL = str_replace("{NUM_INSCRITOS}", $cuenta_usuarios_inscritos, $PRINCIPAL);
		
		$PRINCIPAL = str_replace("{NUM_APROBADOS}", $estado_aprobado, $PRINCIPAL);
		$PRINCIPAL = str_replace("{NUM_REPROBADOS}", $estado_reprobado, $PRINCIPAL);
		
		$PRINCIPAL = str_replace("{NUM_FINALIZADOS}", $estado_finalizado, $PRINCIPAL);
		$PRINCIPAL = str_replace("{NUM_EN_PROCESO}", $estado_en_proceso, $PRINCIPAL);
		$PRINCIPAL = str_replace("{NUM_NO_INICIADOS}", $estado_no_iniciado, $PRINCIPAL);
		$PRINCIPAL = str_replace("{ROW_LISTADO_USUARIOS}", $row_lista_participantes, $PRINCIPAL);
		$PRINCIPAL = str_replace("{TIPO}", $get["tipo"], $PRINCIPAL);
		$PRINCIPAL = str_replace("{NOMBRE}", $get["bch_numoat"], $PRINCIPAL);
		
		$PRINCIPAL = str_replace("{ID_CURSO_ENV}", $get["id"], $PRINCIPAL);
		
		
		$PRINCIPAL = str_replace("{LISTA_PREGUNTAS_ENC_SATISFACCION}", $LISTA_PREGUNTAS_ENC_SATISFACCION, $PRINCIPAL);
		
		
		if ($get["vista"] == "graph") {
		}
		else {
			echo "\r\n";
			echo "Usuarios_Inscritos;Aprobados;Reprobados;Finalizados;EnProceso;NoIniciado\r\n";
			echo "" . $cuenta_usuarios_inscritos . ";" . $estado_aprobado . ";" . $estado_reprobado . ";" . $estado_finalizado . ";" . $estado_en_proceso . ";" . $estado_no_iniciado . "\r\n";
			
			//	ob_flush();
			//	fclose($handle);
			// 	die();
			exit();
		}
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
		
		exit();
	} 
	elseif ($seccion == "notificaciones_email") {
		$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/notificaciones_email/entorno.html"));
		$id_empresa = $_SESSION["id_empresa"];
		$id_instancia = $get["id_instancia"];
		$tipo_instancia = $get["tipo_instancia"];
		
		
		if ($id_instancia <> "") {
			$PRINCIPAL = str_replace("{ENTORNO}", notificaciones_email_lista_notificaciones(FuncionesTransversalesAdmin(file_get_contents("views/notificaciones_email/entorno_notificaciones_sin_crear.html")), $id_empresa, $id_categoria, $id_instancia, $tipo_instancia), $PRINCIPAL);
		}
		else {
			$PRINCIPAL = str_replace("{ENTORNO}", notificaciones_email_lista_notificaciones(FuncionesTransversalesAdmin(file_get_contents("views/notificaciones_email/entorno_notificaciones.html")), $id_empresa, $id_categoria, $id_instancia, $tipo_instancia), $PRINCIPAL);
		}
		
		$datos_empresa = DatosEmpresa($id_empresa);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "notificaciones_log_email_automaticas") {
		$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/notificaciones_email/entorno.html"));
		$id_empresa = $_SESSION["id_empresa"];
		$id_instancia = $get["id_instancia"];
		$tipo_instancia = $get["tipo_instancia"];
		
		$PRINCIPAL = str_replace("{ENTORNO}", notificaciones_log_email_automaticas_lista_notificaciones(FuncionesTransversalesAdmin(file_get_contents("views/notificaciones_email/entorno_notificaciones_log_email_automaticas.html")), $id_empresa, $id_categoria, $id_instancia, $tipo_instancia), $PRINCIPAL);
		
		$excel = $get["excel"];
		if ($excel == "1") {
			header('Content-Description: File Transfer');
			header('Content-Type: application/csv');
			header("Content-Disposition: attachment; filename=Log_Envio_Emails_Automaticos.csv");
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			
			$handle = fopen('php://output', 'w');
			//ob_clean();
			echo "tipo;id_curso;curso;id_inscripcion;rut;nombre_completo;email;fecha\r\n";
			$array_logemails_ = notificaciones_log_email_automaticas_lista_notificaciones_data($id_empresa, $id_instancia, $tipo_instancia);
			
			foreach ($array_logemails_ as $unico) {
				echo $unico->tipo . ";" . $unico->id_curso . ";" . $unico->nombre_curso . ";" . $unico->id_inscripcion . ";" . $unico->rut . ";" . $unico->nombre_completo . ";" . $unico->email . ";" . $unico->fecha . "\r\n";
			}
			exit();
		}
		
		
		$datos_empresa = DatosEmpresa($id_empresa);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "notificaciones_email_notificaciones_Save") {
		$id_empresa = $_SESSION["id_empresa"];
		$id = Decodear3($post['id']);
		$nombre = $post['nombre'];
		$subject = $post['subject'];
		$titulo = $post['titulo'];
		$texto = $post['texto'];
		notificaciones_email_notificaciones_Save_data($id, $nombre, $subject, $titulo, $texto, $id_empresa);
		echo "<script>location.href='?sw=notificaciones_email';</script>";
	}
	elseif ($seccion == "notificaciones_email_update_users") {
		$id_notificacion = Decodear3($get["id"]);
		$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/notificaciones_email/entorno.html"));
		$id_empresa = $_SESSION["id_empresa"];
		$Notificacion = notificaciones_email_notificaciones_usuarios_envios($id_notificacion, $id_empresa);
		$num_usuarios = $Notificacion[0]->usuarios;
		$num_envios = $Notificacion[0]->emails_enviados;
		$PRINCIPAL = str_replace("{ENTORNO}", lista_encuestas_fn_lb(FuncionesTransversalesAdmin(file_get_contents("views/notificaciones_email/update_users_notificaciones.html")), $id_empresa, $id_categoria), $PRINCIPAL);
		$PRINCIPAL = str_replace("{NOMBRE_NOTIFICACION}", ($Notificacion[0]->nombre), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_NOTIFICACION_ENC}", Encodear3($id_notificacion), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_NOTIFICACION}", $id_notificacion, $PRINCIPAL);
		$PRINCIPAL = str_replace("{NUM_USUARIOS}", $num_usuarios, $PRINCIPAL);
		$PRINCIPAL = str_replace("{NUM_ENVIOS}", $num_envios, $PRINCIPAL);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
		exit();
	}
	elseif ($seccion == "notificaciones_email_descargar_usuarios") {
		$id_notificacion = Decodear3($get["id"]);
		$id_empresa = $_SESSION["id_empresa"];
		$lista_usuarios_notificaciones = notificaciones_email_descargar_usuarios_data($id_empresa, $id_notificacion);
		
		require_once 'clases/PHPExcel.php';
		$datos_empresa = DatosEmpresa($id_empresa);
		$objPHPExcel = new PHPExcel();
		$styleArray = array('font' => array('bold' => true, 'color' => array('rgb' => '000000'), 'size' => 12, 'name' => 'Calibri'));
		$objPHPExcel->getProperties()->setCreator("GO Partners")->setLastModifiedBy("GO Partners")->setTitle("Usuarios")->setSubject("Usuarios")->setDescription("Plantilla para carga masiva de usuarios")->setKeywords("Excel Office 2007 openxml php")->setCategory("Plantilla usuarios");
		$lastColumn = $objPHPExcel->getActiveSheet()->getHighestColumn();
		$lastColumn2 = $objPHPExcel->getActiveSheet()->getHighestColumn();
		$camposMostrar = jListarCamposEmpresaSubidaUsuarios(2, $idEmpresa);
		$nombreCampos = jListarCamposEmpresaSubidaUsuarios(3, $idEmpresa);
		$selected = '';
		$i = 0;
		foreach ($camposMostrar as $value) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn . "1", $value);
			$objPHPExcel->getActiveSheet()->getStyle($lastColumn . "1")->applyFromArray($styleArray);
			$lastColumn++;
			$i++;
		}
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A1", "Rut");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B1", "Nombre");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C1", "Email");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D1", "Id_Notificacion");
		$i = 1;
		foreach ($lista_usuarios_notificaciones as $unico) {
			$i++;
			$lastColumn2 = "A";
			$A = 0;
			foreach ($nombreCampos as $value) {
				if ($A == 0) {
					$A = 1;
					$lastColumn2 = "A";
				}
				else {
					$lastColumn2++;
				}
			}
			$lastColumn2++;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $i, ($unico->rut));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B" . $i, ($unico->nombre));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C" . $i, ($unico->email));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D" . $i, ($unico->id_notificacion));
		}
		$sheet = $objPHPExcel->getActiveSheet();
		$cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
		$cellIterator->setIterateOnlyExistingCells(true);
		foreach ($cellIterator as $cell) {
			$sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
		}
		$objPHPExcel->getActiveSheet()->setTitle('Usuarios');
		$objPHPExcel->setActiveSheetIndex(0);
		$fechahoy = date("Y-m-d") . "_" . date("H:i:s");
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $id_notificacion . '_' . $fechahoy . '.xlsx"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}
	elseif ($seccion == "notificaciones_email_usuarios_update") {
		$id_notificacion = Decodear3($post["id_notificacion"]);
		$archivo = $_FILES['excel']['name'];
		VerificaExtensionFilesAdmin($_FILES["excel"]);
		$tipo = $_FILES['excel']['type'];
		$id_empresa = $_SESSION["id_empresa"];
		
		if ($id_notificacion > 0) {
			notificaciones_email_truncate_Notificaciones_Usuario($id_notificacion);
		}
		
		$destino = "tmp_encuesta_" . $archivo;
		if (copy($_FILES['excel']['tmp_name'], $destino)) {
		}
		else {
			$error_grave = "Error Al subir Archivo<br />";
		}
		if (file_exists("tmp_encuesta_" . $archivo)) {
			require_once 'clases/PHPExcel.php';
			require_once 'clases/PHPExcel/Reader/Excel2007.php';
			$objReader = new PHPExcel_Reader_Excel2007();
			$objPHPExcel = $objReader->load("tmp_encuesta_" . $archivo);
			$objFecha = new PHPExcel_Shared_Date();
			$objPHPExcel->setActiveSheetIndex(0);
			$HojaActiva = $objPHPExcel->getActiveSheet();
			$total_filas = $HojaActiva->getHighestRow();
			$lastColumn = $HojaActiva->getHighestColumn();
			$total_columnas = PHPExcel_Cell::columnIndexFromString($lastColumn);
			$UltimaColumna = "A";
			$j = 0;
			$_DATOS_EXCEL = array();
			for ($i = 0; $i <= $total_columnas; $i++) {
				$_campo[$i] = trim(($HojaActiva->getCell($UltimaColumna . "1")->getCalculatedValue()));
				$UltimaColumna++;
			}
			for ($fila = 2; $fila <= $total_filas; $fila++) {
				for ($columna = 0; $columna <= $total_columnas - 1; $columna++) {
					$_DATOS_EXCEL[$j][$columna + 1] = (trim($HojaActiva->getCellByColumnAndRow($columna, $fila)->getCalculatedValue()));
				}
				$j++;
			}
		}
		else {
			$error_grave = "Necesitas primero importar el archivo";
		}
		$total_errores = 0;
		$total_actualizar = 0;
		$total_insertar = 0;
		$usuario_inexistente = 0;
		$curso_inexistente = 0;
		$linea = 2;
		$no_esta_en_tabla_usuario = 0;
		
		if (count($_DATOS_EXCEL) < 1) {
			echo "  <script>location.href='?sw=notificaciones_email';</script>";
			exit;
		}
		
		
		
		for ($l = 1; $l <= count($_DATOS_EXCEL); $l++) {
			outputProgress($l, $fila);
			$rut = LimpiaRut($_DATOS_EXCEL[$l - 1][1]);
			$array_usu = notificaciones_email_nombre_dado_rut_data($rut, $id_empresa);
			$email = $array_usu[0]->email;
			$nombre = $array_usu[0]->nombre_completo;
			if ($rut <> "" and $id_notificacion > 0 and $email <> "") {
				notificaciones_email_Inserta_Nueva_Encuesta_Usuario_Tipo_Editor_Visualizador($rut, $nombre, $email, $id_notificacion, $id_empresa);
			}
		}
		
		echo "<script>location.href='?sw=notificaciones_email';</script>";
		exit;
	}
	elseif ($seccion == "notificaciones_email_send_emails") {
		$id_empresa = $_SESSION["id_empresa"];
		$id_notificacion = Decodear3($get["id"]);
		$tipo = $get["tipo"];
		$array_envios = notificaciones_email_envios_usuarios($id_notificacion, $tipo, $id_empresa);
		
		$Notificacion = notificaciones_email_notificaciones_data($id_notificacion, $id_empresa);
		
		$subject = ($Notificacion[0]->subject);
		$titulo = ($Notificacion[0]->titulo);
		$texto = ($Notificacion[0]->texto);
		$subtitulo1 = "";
		$tipo = "text/html";
		$from = getenv('EMAIL_FROM');
		$nombrefrom = getenv('EMAIL_FROM_NAME');
		$num_envios = 0;
		foreach ($array_envios as $usuario) {
			$rut = $usuario->rut;
			$email = $usuario->email;
			$nombre_completo = $usuario->nombre;
			$num_envios++;
			$to = $email;
			$nombreto = $email;
			$url = $usuario->url;
			$texto_url = $usuario->texto_url;
			
			SendGrid_Email($to, $nombreto, $from, $nombrefrom, $tipo, $subject, $titulo, $subtitulo, $texto, $url, $texto_url, $texto2, $texto3, $texto4, $logo, $id_empresa, $url, "Email_Masivo", $rut, $id_notificacion);
			notificaciones_email_ActualizoEstadoEnvioCorreoPorProceso($usuario->rut, $id_notificacion, $id_empresa);
		}
		
		echo "<center>Total de Usuarios Enviados " . ($num_envios) . "</center>";
		
		//   echo "Se enviaron todos los emails";
		echo "<script>location.href='?sw=notificaciones_email';</script>";
		exit;
	}
	elseif ($seccion == "lista_encuestas_lb_descargar_usuarios") {
		$id_encuesta = $get["id_encuesta"];
		$tipo_usuario = $get["tipo_usuario"];
		$id_empresa = $_SESSION["id_empresa"];
		
		
		if ($tipo_usuario == "Usuario") {
			$lista_usuarios_encuesta = Lista_Encuestas_Tipo_Usuario($id_empresa, $id_encuesta, $tipo_usuario);
		}
		else {
			$lista_usuarios_encuesta = Lista_Encuestas_Tipo_EditorVisualizador($id_empresa, $id_encuesta, $tipo_usuario);
		}
		
		header('Content-Description: File Transfer');
		header('Content-Type: application/csv');
		header("Content-Disposition: attachment; filename=Usuarios_Por_Encuesta_" . $id_encuesta . ".csv");
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		
		$handle = fopen('php://output', 'w');
		ob_clean();
		echo "rut;\r\n";
		foreach ($lista_usuarios_encuesta as $unico) {
			echo $unico->rut . ";\r\n";
			//fputcsv($handle, $row);
		}
		
		ob_flush();
		fclose($handle);
		die();
		exit();
		
		
		foreach ($lista_usuarios_encuesta as $unico) {
			//	print_r($unico);
			$i++;
			$lastColumn2 = "A";
			$A = 0;
			foreach ($nombreCampos as $value) {
				if ($A == 0) {
					$A = 1;
					$lastColumn2 = "A";
				}
				else {
					$lastColumn2++;
				}
			}
			$lastColumn2++;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $i, ($unico->rut));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B" . $i, ($unico->id_encuesta));
		}
		
		
		exit;
	}
	elseif ($seccion == "lista_cluster_lb_descargar_usuarios") {
		$id_cluster = $get["id_cluster"];
		$tipo_usuario = $get["tipo_usuario"];
		$id_empresa = $_SESSION["id_empresa"];
		
		if ($tipo_usuario == "Usuario") {
			$lista_usuarios_cluster = Lista_Cluster_Tipo_Usuario($id_empresa, $id_cluster, $tipo_usuario);
			$txt_usuario_descarga = "Visualizador";
		}
		else {
			//$lista_usuarios_encuesta = Lista_Encuestas_Tipo_EditorVisualizador($id_empresa, $id_encuesta, $tipo_usuario);
		}
		require_once 'clases/PHPExcel.php';
		$datos_empresa = DatosEmpresa($id_empresa);
		$objPHPExcel = new PHPExcel();
		$styleArray = array('font' => array('bold' => true, 'color' => array('rgb' => '000000'), 'size' => 12, 'name' => 'Calibri'));
		$objPHPExcel->getProperties()->setCreator("GO Partners")->setLastModifiedBy("GO Partners")->setTitle("Usuarios")->setSubject("Usuarios")->setDescription("Plantilla para carga masiva de usuarios")->setKeywords("Excel Office 2007 openxml php")->setCategory("Plantilla usuarios");
		$lastColumn = $objPHPExcel->getActiveSheet()->getHighestColumn();
		$lastColumn2 = $objPHPExcel->getActiveSheet()->getHighestColumn();
		$camposMostrar = jListarCamposEmpresaSubidaUsuarios(2, $idEmpresa);
		$nombreCampos = jListarCamposEmpresaSubidaUsuarios(3, $idEmpresa);
		$selected = '';
		$i = 0;
		foreach ($camposMostrar as $value) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn . "1", $value);
			$objPHPExcel->getActiveSheet()->getStyle($lastColumn . "1")->applyFromArray($styleArray);
			$lastColumn++;
			$i++;
		}
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A1", "Rut");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B1", "Id_Cluster");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C1", "Perfil");
		$i = 1;
		foreach ($lista_usuarios_cluster as $unico) {
			$i++;
			$lastColumn2 = "A";
			$A = 0;
			foreach ($nombreCampos as $value) {
				if ($A == 0) {
					$A = 1;
					$lastColumn2 = "A";
				}
				else {
					$lastColumn2++;
				}
			}
			$lastColumn2++;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $i, ($unico->rut));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B" . $i, ($unico->id_cluster));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C" . $i, ($txt_usuario_descarga));
		}
		$sheet = $objPHPExcel->getActiveSheet();
		$cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
		$cellIterator->setIterateOnlyExistingCells(true);
		foreach ($cellIterator as $cell) {
			$sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
		}
		$objPHPExcel->getActiveSheet()->setTitle('Usuarios');
		$objPHPExcel->setActiveSheetIndex(0);
		$fechahoy = date("Y-m-d") . "_" . date("H:i:s");
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $id_cluster . '_' . $tipo_usuario . '_' . $fechahoy . '.xlsx"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}
	elseif ($seccion == "Mediciones_Charts_Respuestas_EncuestaLB") {
		$id_med = $get["idMed"];
		$med = DatosMedicionAdmin($id_med);
		
		$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/graficos/grafico_entorno.html"));
		$id_empresa = $_SESSION["id_empresa"];
		$PRINCIPAL = str_replace("{ENTORNO_GRAFICOS_MEDICIONES}", lista_respuestas_encuestas_grafico(FuncionesTransversalesAdmin(file_get_contents("views/graficos/grafico_entorno_global.html")), $id_empresa, $id_med), $PRINCIPAL);
		
		$PRINCIPAL = str_replace("{TITULO_MEDICION}", ($med[0]->nombre), $PRINCIPAL);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "DejarNoEvaluable") {
		$evaluado = Decodear3($get["ev"]);
		$evaluador = Decodear3($get["evr"]);
		$id_proceso = Decodear3($get["ip"]);
		
		
		ActualizaPerfilEvaluacionNoEvaluable($evaluado, $evaluador, $id_proceso);;
		
		echo "
							<script>
								alert('Usuario dejado como no evaluable')
                                location.href='?sw=SGD_Matriz&b=si';
                            </script>";
	}
	elseif ($seccion == "AbrirCal") {
		$evaluado = Decodear3($get["ev"]);
		$evaluador = Decodear3($get["evr"]);
		$id_proceso = Decodear3($get["ip"]);
		
		echo "$evaluado, $evaluador, $id_proceso";
		BorraRegistroValidacionCalibracion($evaluado, $evaluador, $id_proceso);
		
		
		echo "
							<script>
								alert('Registro de validacion eliminado')
                                location.href='?sw=SGD_Matriz&b=si';
                            </script>";
	}
	elseif ($seccion == "UpdateClave") {
		UpdateClavedata();
	}
	elseif ($seccion == "checkUserAdmin") {
		session_start();
		
		$nombre_completo = $get["fullName"];
		$nombre = $get["GivenName"];
		$apellido = $get["FamilyName"];
		$imagenUrl = $get["imageUrl"];
		$email = $get["email"];
		$user_content_key = $get["user_content_key"];
		$arreglo_email = $arreglo_archivo = explode("@", $email);
		
		if ($arreglo_email[1] == "gop.cl") {
			$key = $email;
			$arrayEmpresa = BuscaEmpresaUserRut($rut);
			
			$id_empresa = $arrayEmpresa[0]->id_empresa;
			$_SESSION["id_empresa"] = $id_empresa;
			$acceso = $arrayEmpresa[0]->acceso;
			$home_admin = $arrayEmpresa[0]->home_admin;
			$_SESSION["user_"] = $key;
			$_SESSION["admin_"] = $key;
			
			$verifica_clave_Google = VerificoAccesoAdminGoogle($key);
			
			
			if ($verifica_clave_Google) {
				$_SESSION["prefijo"] = $verifica_clave_Google[0]->prefijo;
				$_SESSION["nombre"] = $verifica_clave_Google[0]->nombre;
				$_SESSION["perfil"] = $verifica_clave_Google[0]->acceso;
				$_SESSION["id_empresa"] = $verifica_clave_Google[0]->id_empresa;
			}
			
			if (count($verifica_clave_Google) == 0) {
				echo "
                            <script>
                                location.href='?sw=login_id';
                            </script>";
				exit;
			}
			
			if ($key == "") {
				echo "
                                    <script>
                                        location.href='?sw=login_id';
                                    </script>";
				exit;
			}
			
			echo "
                            <script>
                                location.href='?sw=" . $home_admin . "';
                            </script>";
			exit;
		}
		else {
			echo "
                            <script>
                                location.href='?sw=login_id';
                            </script>";
			exit;
		}
	}
	elseif ($seccion == "GeneraPlantillaCursos") {
		/** Incluir la libreria PHPExcel */
		require_once 'clases/PHPExcel.php';
		
		$idEmpresa = $post['id_empresa'];
		
		// Crea un nuevo objeto PHPExcel
		$objPHPExcel = new PHPExcel();
		
		$styleArray = array('font' => array('bold' => true, 'color' => array('rgb' => '000000'), 'size' => 12, 'name' => 'Calibri'));
		
		// Establecer propiedades
		$objPHPExcel->getProperties()->setCreator("Gop")->setLastModifiedBy("Gop")->setTitle("Plantilla Cursos")->setSubject("Plantilla Cursos")->setDescription("Plantilla para carga cursos")->setKeywords("Excel Office 2007 openxml php")->setCategory("Plantilla Cursos");
		
		$lastColumn = $objPHPExcel->getActiveSheet()->getHighestColumn();
		$lastColumn2 = $objPHPExcel->getActiveSheet()->getHighestColumn();
		
		$camposMostrar = jListarCamposEmpresaSubidaCursos(2, $idEmpresa);
		$nombreCampos = jListarCamposEmpresaSubidaCursos(3, $idEmpresa);
		$selected = '';
		$i = 0;
		
		foreach ($camposMostrar as $value) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn . "1", $value);
			$objPHPExcel->getActiveSheet()->getStyle($lastColumn . "1")->applyFromArray($styleArray);
			$lastColumn++;
			$i++;
		}
		
		$cursos = TraeCursosPorEmpresaParaSubidaMasiva($idEmpresa);
		$i = 1;
		
		
		
		foreach ($cursos as $unico) {
			$i++;
			$lastColumn2 = "A";
			$A = 0;
			
			foreach ($nombreCampos as $value) {
				if ($A == 0) {
					$A = 1;
					$lastColumn2 = "A";
				}
				else {
					$lastColumn2++;
				}
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($unico->$value));
			}
		}
		
		$sheet = $objPHPExcel->getActiveSheet();
		$cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
		$cellIterator->setIterateOnlyExistingCells(true);
		
		/** @var PHPExcel_Cell $cell */
		foreach ($cellIterator as $cell) {
			$sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
		}
		
		// Renombrar Hoja
		$objPHPExcel->getActiveSheet()->setTitle('Usuarios');
		
		// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
		$objPHPExcel->setActiveSheetIndex(0);
		
		// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="plantilla_cursos.xlsx"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}
	elseif ($seccion == "procesa_excel_cursos") {
		$idEmpresa = $_SESSION["id_empresa"];
		
		if (jValidarEmpresaSubidaCursos($idEmpresa)) {
			$res = jListarCamposEmpresaSubidaCursos(1, $idEmpresa);
			
			$arr = explode("|", $res);
			
			$arrObli = explode(",", $arr[0]);
			$arrOpci = explode(",", $arr[1]);
			
			$camposObli = "";
			$camposOpci = "";
			
			for ($i = 1; $i < count($arrObli); $i++) {
				$camposObli .= "<tr><td>" . $arrObli[$i] . "</td></tr>";
			}
			
			for ($i = 1; $i < count($arrOpci); $i++) {
				$camposOpci .= "<tr><td>" . $arrOpci[$i] . "</td></tr>";
			}
			
			$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/cursospresenciales/formulario_subida_masivo_cursos_new.html"));
			$PRINCIPAL = str_replace("{CAMPOS_OBLI}", $camposObli, $PRINCIPAL);
			$PRINCIPAL = str_replace("{CAMPOS_OPCI}", $camposOpci, $PRINCIPAL);
			$PRINCIPAL = str_replace("{ID_EMPRESA}", $idEmpresa, $PRINCIPAL);
			$PRINCIPAL = str_replace("{TAB1}", "active", $PRINCIPAL);
			$PRINCIPAL = str_replace("{CLASTAB1}", "tab-pane active", $PRINCIPAL);
			$PRINCIPAL = str_replace("{TAB2}", "", $PRINCIPAL);
			$PRINCIPAL = str_replace("{CLASTAB2}", "tab-pane", $PRINCIPAL);
			$PRINCIPAL = str_replace("{DISPLAYFORM1}", "display:none;", $PRINCIPAL);
			$PRINCIPAL = str_replace("{DISPLAYFORM2}", "inline", $PRINCIPAL);
			$PRINCIPAL = str_replace("{ENTORNO_PREVIA}", "", $PRINCIPAL);
			$PRINCIPAL = str_replace("{TITULO2}", "Formulario de subida", $PRINCIPAL);
			$cursos = TraeCursosPorEmpresaParaSubidaMasiva($idEmpresa);
			$PRINCIPAL = str_replace("{TOTALU}", count($cursos), $PRINCIPAL);
			$PRINCIPAL = str_replace("{TOT_ACTUALIZAR}", "0", $PRINCIPAL);
			$PRINCIPAL = str_replace("{TOT_INSERTAR}", "0", $PRINCIPAL);
			$PRINCIPAL = str_replace("{TOT_DESVINCULAR}", "0", $PRINCIPAL);
			$PRINCIPAL = str_replace("{TOT_ERRORES}", "0", $PRINCIPAL);
			$PRINCIPAL = str_replace("{ERRORES}", "", $PRINCIPAL);
			$PRINCIPAL = str_replace("{DISPLAYERROR}", "display:none;", $PRINCIPAL);
		}
		else {
			echo "no hay data en la tabla de cursos campos";
		}
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "procesa_subida_masiva_cursos") {
		$idEmpresa = $_SESSION["id_empresa"];
		
		CancelaProcesamientoPrevioCursos($idEmpresa);
		
		extract($post);
		
		$error_grave = "error";
		
		if ($action == "upload") {
			//cargamos el archivo al servidor con el mismo nombre
			//solo le agregue el sufijo tmp_ev_
			$archivo = $_FILES['excel']['name'];
			VerificaExtensionFilesAdmin($_FILES["excel"]);
			$tipo = $_FILES['excel']['type'];
			$destino = "tmp_ev_" . $archivo;
			
			if (copy($_FILES['excel']['tmp_name'], $destino)) {
			
			}
			else {
				$error_grave = "Error Al subir Archivo<br>";
			}
			
			if (file_exists("tmp_ev_" . $archivo)) {
				require_once 'clases/PHPExcel.php';
				require_once 'clases/PHPExcel/Reader/Excel2007.php';
				
				$objReader = new PHPExcel_Reader_Excel2007();
				$objPHPExcel = $objReader->load("tmp_ev_" . $archivo);
				$objFecha = new PHPExcel_Shared_Date();
				$objPHPExcel->setActiveSheetIndex(0);
				$HojaActiva = $objPHPExcel->getActiveSheet();
				$total_filas = $HojaActiva->getHighestRow();
				$lastColumn = $HojaActiva->getHighestColumn();
				$total_columnas = PHPExcel_Cell::columnIndexFromString($lastColumn);
				$UltimaColumna = "A";
				$j = 0;
				$_DATOS_EXCEL = array();
				
				//Obtengo los nombres de los campos
				for ($i = 0; $i <= $total_columnas; $i++) {
					$_campo[$i] = trim(($HojaActiva->getCell($UltimaColumna . "1")->getCalculatedValue()));
					$UltimaColumna++;
				}
				
				//Obtengo datos de filas
				for ($fila = 2; $fila <= $total_filas; $fila++) {
					for ($columna = 0; $columna <= $total_columnas - 1; $columna++) {
						$_DATOS_EXCEL[$j][$_campo[$columna]] = trim($HojaActiva->getCellByColumnAndRow($columna, $fila)->getCalculatedValue());
					}
					$j++;
				}
			}
			else {
				$error_grave = "Necesitas primero importar el archivo";
			}
			
			$total_errores = 0;
			$total_actualizar = 0;
			$total_insertar = 0;
			$total_desvincular = 0;
			$lineaT = "";
			$linea = 2;
			
			//var_dump($_DATOS_EXCEL);
			
			foreach ($_DATOS_EXCEL as $key => $item) {
				$coma = "";
				$values = "";
				$registros = "";
				$rut = "";
				$error_columna = "";
				$parar = 0;
				$error_grave = "";
				$mensaje_errores = "";
				
				
				foreach ($item as $key2 => $valor) {
					
					
					$campoTabla = jCampoVisualCambioCampoTablaCursos($idEmpresa, $key2);
					
					$esObligatorio = jValidarObligatorioCampoCursos($idEmpresa, $key2);
					
					if ($key2 == "Codigo Curso" && $valor != "") {
						$codigo_curso = $valor;
					}
					
					$values .= $coma . $campoTabla;
					$registros .= $coma . "'" . $valor . "'";
					$coma = ",";
				}
				
				//Verifica si el curso / empresa existe
				$existe_curso = VerificoCursoPorEmpresa($codigo_curso, $idEmpresa);
				if ($existe_curso) {
					$accion = "ACTUALIZAR";
					$total_actualizar++;
					//$error_grave = ActualizaCursoMasivo($values,$idEmpresa, $codigo_curso);
				}
				else {
					$accion = "INSERTAR";
					$total_insertar++;
					$mensaje_nuevo = "Se encontrar nuevos cursos para ingresar!";
				}
				
				$error_grave = InsertaCursoTemporal($values, ($registros), $idEmpresa, $accion);
				
				if (strlen($error_columna) > 0) {
					$lineaT .= "Linea [ $linea ] - Columna [ $error_columna ]<br/>";
					$total_errores++;
				}
				
				$linea++;
			}
		}
		
		//CARGA VISTA
		
		if (jValidarEmpresaSubidaCursos($idEmpresa)) {
			$res = jListarCamposEmpresaSubidaCursos(1, $idEmpresa);
			
			$arr = explode("|", $res);
			
			$arrObli = explode(",", $arr[0]);
			$arrOpci = explode(",", $arr[1]);
			
			$table = "";
			$mayor = 0;
			
			if (count($arrObli) >= count($arrOpci)) {
				$mayor = count($arrObli);
			}
			else {
				$mayor = count($arrOpci);
			}
			
			for ($i = 1; $i < $mayor; $i++) {
				$table .= "<tr>";
				
				if ($i <= count($arrObli)) {
					$table .= "<td>" . $arrObli[$i] . "</td>";
				}
				else {
					$table .= "<td></td>";
				}
				
				if ($i <= count($arrOpci)) {
					$table .= "<td>" . $arrOpci[$i] . "</td>";
				}
				else {
					$table .= "<td></td>";
				}
				
				$table .= "</tr>";
			}
			
			$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/cursospresenciales/formulario_subida_masivo_cursos_new.html"));
			$PRINCIPAL = str_replace("{CAMPOS}", $table, $PRINCIPAL);
			$PRINCIPAL = str_replace("{ID_EMPRESA}", $idEmpresa, $PRINCIPAL);
			$totalusuarios = TraeCursosPorEmpresaParaSubidaMasiva($idEmpresa);
			$PRINCIPAL = str_replace("{TOTALU}", count($totalusuarios), $PRINCIPAL);
			
			if (!$error_grave) {
				$PRINCIPAL = str_replace("{DISPLAYFORM1}", "", $PRINCIPAL);
				$PRINCIPAL = str_replace("{DISPLAYFORM2}", "none", $PRINCIPAL);
				$PRINCIPAL = str_replace("{DISPLAYERROR}", "display:none;", $PRINCIPAL);
			}
			else {
				$PRINCIPAL = str_replace("{DISPLAYFORM1}", "display:none;", $PRINCIPAL);
				$PRINCIPAL = str_replace("{DISPLAYFORM2}", "inline", $PRINCIPAL);
				$PRINCIPAL = str_replace("{DISPLAYERROR}", "", $PRINCIPAL);
			}
			
			$PRINCIPAL = str_replace("{DISPLAYFORM1}", "", $PRINCIPAL);
			$PRINCIPAL = str_replace("{DISPLAYFORM2}", "none", $PRINCIPAL);
			$PRINCIPAL = str_replace("{TAB1}", "", $PRINCIPAL);
			$PRINCIPAL = str_replace("{CLASTAB1}", "tab-pane", $PRINCIPAL);
			$PRINCIPAL = str_replace("{TAB2}", "active", $PRINCIPAL);
			$PRINCIPAL = str_replace("{CLASTAB2}", "tab-pane active", $PRINCIPAL);
			$PRINCIPAL = str_replace("{TITULO2}", "Vista previa", $PRINCIPAL);
			$PRINCIPAL = str_replace("{TOT_ACTUALIZAR}", $total_actualizar, $PRINCIPAL);
			$PRINCIPAL = str_replace("{TOT_INSERTAR}", $total_insertar, $PRINCIPAL);
			$PRINCIPAL = str_replace("{TOT_DESVINCULAR}", $total_desvincular, $PRINCIPAL);
			$PRINCIPAL = str_replace("{TOT_ERRORES}", $total_errores, $PRINCIPAL);
			$PRINCIPAL = str_replace("{ERRORES}", $lineaT, $PRINCIPAL);
			$PRINCIPAL = str_replace("{ERROR_GRAVE}", $error_grave, $PRINCIPAL);
			
			if (!$error_grave) {
				$PRINCIPAL = str_replace("{ENTORNO_PREVIA}", (ListadoCursosMasivosPrevioVisualiza($idEmpresa)), $PRINCIPAL);
			}
			else {
				$PRINCIPAL = str_replace("{ENTORNO_PREVIA}", "", $PRINCIPAL);
			}
		}
		else {
			$campos = TraeCamposPlantilla();
			$CamposEmpresa = TraeCampos($idEmpresa);
			
			foreach ($campos as $unico) {
				$checkbox = '<div class="checkbox">
                             <label>
                                <input name="campo[]" type="checkbox" ' . $unico->checked . ' ' . $unico->disabled . ' value="' . $unico->campo . '"> ' . $unico->nombre . '
                              </label>
                            </div>';
				
				if ($unico->tipo == "obligatorio") {
					$checkbox .= '<input name="campo[]" type="hidden" value="' . $unico->campo . '">';
					$obligatorio .= $checkbox;
				}
				
				if ($unico->tipo == "importante") {
					$importante .= $checkbox;
				}
				
				if ($unico->tipo == "opcional") {
					$opcional .= $checkbox;
				}
			}
			
			$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/basepersonas/formulario_subida_masivo.html"));
			$PRINCIPAL = str_replace("{OBLIGATORIOS}", $obligatorio, $PRINCIPAL);
			$PRINCIPAL = str_replace("{IMPORTANTES}", $importante, $PRINCIPAL);
			$PRINCIPAL = str_replace("{OPCIONALES}", $opcional, $PRINCIPAL);
			$totalusuarios = CuentaUsuarios($idEmpresa);
			$PRINCIPAL = str_replace("{TOTALU}", colocarPesos($totalusuarios), $PRINCIPAL);
			
			if (!$error_grave) {
				$PRINCIPAL = str_replace("{DISPLAYFORM1}", "", $PRINCIPAL);
				$PRINCIPAL = str_replace("{DISPLAYFORM2}", "none", $PRINCIPAL);
				$PRINCIPAL = str_replace("{DISPLAYERROR}", "display:none;", $PRINCIPAL);
			}
			else {
				$PRINCIPAL = str_replace("{DISPLAYFORM1}", "display:none;", $PRINCIPAL);
				$PRINCIPAL = str_replace("{DISPLAYFORM2}", "inline", $PRINCIPAL);
				$PRINCIPAL = str_replace("{DISPLAYERROR}", "", $PRINCIPAL);
			}
			
			$PRINCIPAL = str_replace("{DISPLAYFORM1}", "", $PRINCIPAL);
			$PRINCIPAL = str_replace("{DISPLAYFORM2}", "none", $PRINCIPAL);
			$PRINCIPAL = str_replace("{TAB1}", "", $PRINCIPAL);
			$PRINCIPAL = str_replace("{CLASTAB1}", "tab-pane", $PRINCIPAL);
			$PRINCIPAL = str_replace("{TAB2}", "active", $PRINCIPAL);
			$PRINCIPAL = str_replace("{CLASTAB2}", "tab-pane active", $PRINCIPAL);
			$PRINCIPAL = str_replace("{TITULO2}", "Vista previa", $PRINCIPAL);
			$PRINCIPAL = str_replace("{TOT_ACTUALIZAR}", $total_actualizar, $PRINCIPAL);
			$PRINCIPAL = str_replace("{TOT_INSERTAR}", $total_insertar, $PRINCIPAL);
			$PRINCIPAL = str_replace("{TOT_ERRORES}", $total_errores, $PRINCIPAL);
			$PRINCIPAL = str_replace("{ERRORES}", $lineaT, $PRINCIPAL);
			$PRINCIPAL = str_replace("{ERROR_GRAVE}", $error_grave, $PRINCIPAL);
			
			if (!$error_grave) {
				$PRINCIPAL = str_replace("{ENTORNO_PREVIA}", (ListadoUsuariosMasivosPrevio($idEmpresa)), $PRINCIPAL);
			}
			else {
				$PRINCIPAL = str_replace("{ENTORNO_PREVIA}", "", $PRINCIPAL);
			}
		}
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "Vista_Rut_Foto") {
		$id_empresa = $_SESSION["id_empresa"];
		$Array_Usuario = ListaTodosUsuario($id_empresa);
		foreach ($Array_Usuario as $unico) {
			$avatar = VerificaFotoPersonalAdminV2($unico->rut);
			$avatar = str_replace('../', '', $avatar);
			$urlAvatar = getenv("AVATAR_URL");
			echo "<br>" . ($unico->rut) . ";" . ($unico->nombre_completo) . ";" . ($unico->cargo) . ";" . ($unico->division) . ";" . "$urlAvatar" . $avatar;
		}
	}
	elseif ($seccion == "inicio") {
		$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/home.html"));
		//        $PRINCIPAL=FuncionesTransversalesAdmin(file_get_contents("views/home.html"));
		//$PRINCIPAL=Multifiltro(ListadoJefatura(FuncionesTransversalesAdmin(file_get_contents("views/dnc/home_consolidado.html")),$post));
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
		
	}
	elseif ($seccion == "addnoticia") {
		$id_noticia = Decodear3($get["i"]);
		
		$PRINCIPAL = FormularioNoticia(FuncionesTransversalesAdmin(file_get_contents("views/noticias/form_add.html")), $id_noticia);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "lms_slider") {
		$id_empresa = $_SESSION["id_empresa"];
		$del_idfoto = ($get["del_idfoto"]);
		
		if ($del_idfoto != '') {
			slider_delete($del_idfoto, $id_empresa);
		}
		
		$PRINCIPAL = FormularioNoticia(FuncionesTransversalesAdmin(file_get_contents("views/sliders/sliders_entorno.html")), $id_noticia);
		$fotos = TraeLmsSliders($id_empresa);
		
		
		foreach ($fotos as $unico) {
			
			$row .= file_get_contents("views/sliders/sliders_row.html");
			$row = str_replace("{ID}", ($unico->id), $row);
			$row = str_replace("{IMAGEN}", ($unico->foto), $row);
			$row = str_replace("{RUT}", ($unico->rut), $row);
			$row = str_replace("{NOMBRE_COMPLETO}", ($unico->nombre_completo), $row);
			$row = str_replace("{MENSAJE}", ($unico->mensaje), $row);
		}
		
		$PRINCIPAL = str_replace("{LISTA_IMAGENES_SUBIDAS_MP}", $row, $PRINCIPAL);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "vista_norm") {
		$id_empresa = $_SESSION["id_empresa"];
		$del_idfoto = ($get["del_idfoto"]);
		
		echo "
                            <script>
                                location.href='/sites/normativos/front/?sw=entrada';
                            </script>";
		exit;
	}
	elseif ($seccion == "adminEval") {
		$PRINCIPAL = jListarEvaluaciones(FuncionesTransversalesAdmin(file_get_contents("views/evaluaciones/admin/listEval.html")));
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "pruebaBuscador") {
	
	}
	elseif ($seccion == "reportdin") {
		$id_empresa = $_SESSION["id_empresa"];
		$tipo_reporte = $post["tipo_reporte"];
		$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/reportes/dinamicos/entorno.html"));
		$PRINCIPAL = str_replace("{FILTROS}", FiltrosReportes(file_get_contents("views/reportes/dinamicos/filtros.html"), $arreglo_post, $id_empresa), $PRINCIPAL);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
		if ($tipo_reporte) {
			$datos_listado_cursos = ReporteDinamico($arreglo_post, $id_empresa, $tipo_reporte);
		}
		
		$PRINCIPAL_FOOTER = FuncionesTransversalesAdmin(file_get_contents("views/reportes/dinamicos/footer.html"));
		echo $PRINCIPAL_FOOTER;
	}
	elseif ($seccion == "SGD_SeguimientoEvaluadorEvaluadoEvaluador") {
		$id_empresa = $_SESSION["id_empresa"];
		$fechahoy = date("Y-m-d") . " " . date("H:i:s");
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=" . $exportar . $fechahoy . ".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		$avance_fijacion = ReporteAvanceEvalCial();
		?>
		<table>
		<tr>
			<td>Rut Evaluado</td>
			<td>Nombre Evaluado</td>
			<td>Cargo Evaluado</td>
			<td>Gerencia Evaluado</td>
			<td>Rut Evaluador</td>
			<td>Nombre Evaluador</td>
			<td>Cargo Evaluador</td>
			<td>Gerencia Evaluador</td>
			<td>Fecha de Finalizacion</td>


		</tr>
		<?php
		foreach ($avance_fijacion as $dat) {
			echo "<tr>";
			echo "<td>" . $dat->rut_evaluado . "</td>";
			echo "<td>" . $dat->nombre_evaluado . "</td>";
			echo "<td>" . $dat->cargo_evaluado . "</td>";
			echo "<td>" . $dat->gerencia_evaluado . "</td>";
			
			echo "<td>" . $dat->rut_evaluador . "</td>";
			echo "<td>" . $dat->nombre_evaluador . "</td>";
			echo "<td>" . $dat->cargo_evaluador . "</td>";
			echo "<td>" . $dat->gerencia_evaluador . "</td>";
			echo "<td>" . $dat->fecha_finalizacion . "</td>";
			
			echo "</tr>";
		}
	}
	elseif ($seccion == "sgd_reportes_fijacion") {
		$id_empresa = $_SESSION["id_empresa"];
		$fechahoy = date("Y-m-d") . " " . date("H:i:s");
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=" . $exportar . $fechahoy . ".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		$avance_fijacion = ReporteFijacionCial();
		?>
		<table>
		<tr>
			<td>Rut Evaluado</td>
			<td>Nombre Evaluado</td>
			<td>Cargo Evaluado</td>
			<td>Gerencia Evaluado</td>
			<td>Centro Costo Evaluado</td>
			<td>Descriptor</td>
			<td>Tipo de Medicion</td>
			<td>Ponderacion</td>
			<td>Fecha</td>
			<td>No Cumple</td>
			<td>Cumple</td>
			<td>Sobresale</td>
		</tr>
		<?php
		foreach ($avance_fijacion as $dat) {
			echo "<tr>";
			echo "<td>" . $dat->rut_completo . "</td>";
			echo "<td>" . $dat->nombre_completo . "</td>";
			echo "<td>" . $dat->cargo . "</td>";
			echo "<td>" . $dat->gerencia . "</td>";
			echo "<td>" . $dat->centro_costo . "</td>";
			echo "<td>" . $dat->Descriptor . "</td>";
			echo "<td>" . $dat->TipoMedicion . "</td>";
			echo "<td>" . $dat->Ponderacion . "</td>";
			echo "<td>" . $dat->Fecha . "</td>";
			echo "<td>" . $dat->NoCumple . "</td>";
			echo "<td>" . $dat->Cumple . "</td>";
			echo "<td>" . $dat->Sobresale . "</td>";
			
			echo "</tr>";
		}
	}
	elseif ($seccion == "sgd_reportes_avance_fijacion") {
		$id_empresa = $_SESSION["id_empresa"];
		$fechahoy = date("Y-m-d") . " " . date("H:i:s");
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=" . $exportar . $fechahoy . ".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		$avance_fijacion = ReporteAvanceFijacionCial();
		?>
		<table>
		<tr>
			<td>Rut Evaluado</td>
			<td>Nombre Evaluado</td>
			<td>Cargo Evaluado</td>
			<td>Gerencia Evaluado</td>
			<td>Centro Costo Evaluado</td>
			<td>Rut Evaluador</td>
			<td>Nombre Evaluador</td>
			<td>Cargo Evaluador</td>
			<td>Gerencia Evaluador</td>
			<td>Centro Costo Evaluador</td>
			<td>Total Objetivos Ingresados</td>
			<td>Total Finalizados</td>
		</tr>
		<?php
		foreach ($avance_fijacion as $dat) {
			echo "<tr>";
			echo "<td>" . $dat->rut_evaluado . "</td>";
			echo "<td>" . $dat->nombre_evaluado . "</td>";
			echo "<td>" . $dat->cargo_evaluado . "</td>";
			echo "<td>" . $dat->gerencia_evaluado . "</td>";
			echo "<td>" . $dat->centro_costo_evaluado . "</td>";
			
			echo "<td>" . $dat->rut_evaluador . "</td>";
			echo "<td>" . $dat->nombre_evaluador . "</td>";
			echo "<td>" . $dat->cargo_evaluador . "</td>";
			echo "<td>" . $dat->gerencia_evaluador . "</td>";
			echo "<td>" . $dat->centro_costo_evaluador . "</td>";
			
			echo "<td>" . $dat->total_objetivos_ingresados . "</td>";
			echo "<td>" . $dat->total_finalizados . "</td>";
			
			echo "</tr>";
		}
	}
	elseif ($seccion == "checkEscuelaBp") {
		$id_empresa = $_SESSION["id_empresa"];
		//traigo las respeustas de las pregutnas de esta empresa
		
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=CheckList.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		
		?>
		<table>

		<tr>
			<td>Rut Colaborador</td>
			<td>Nombre ColaboradorColaborador</td>
			<td>Rut Jefe</td>
			<td>Nombre Jefe</td>
			<?php
				
				//traigo los dias
				$dias = TraifoDiasEscualeBP();
				foreach ($dias as $dia) {
					//Traigo las actividades por dia
					$actividades = TraigoActividadesPorDiaEscuelaBP($dia->dia);
					foreach ($actividades as $act) {
						?>
						<td><?php echo "Dia " . $dia->dia . " " . $act->pauta; ?></td>
						
						<?php
					}
					?>

					<td>Comentario</td>
					<td>Asistencia</td>
					<td>Fecha</td>
					<?php
				}
			
			?>

		</tr>
		
		<?php
		//Traigo los usuarios
		$usuarios = TraigoUsuariosEscuelaBP();
		foreach ($usuarios as $usu) {
			?>

			<tr>
				<td><?php echo $usu->rut; ?></td>
				<td><?php echo $usu->nombre_completo; ?></td>
				<td><?php echo $usu->lider; ?></td>
				<td><?php echo $usu->nombre_lider; ?></td>
				<?php
					foreach ($dias as $dia) {
						//Traigo las actividades por dia
						$actividades = TraigoActividadesPorDiaEscuelaBP($dia->dia);
						foreach ($actividades as $act) {
							//tiene check por dia / actividad
							$check = TieneCheckPorDiaColaboradorActividad($dia->dia, $usu->rut, $act->id);
							?>

							<td><?php echo $check[0]->respuesta; ?></td>
							
							<?php
						}
						$comentarios_finales = TieneComentariosEscuelaBpCHECK($dia->dia, $usu->rut);
						?>

						<td><?php echo $comentarios_finales[0]->comentario_final; ?></td>
						<td><?php echo $comentarios_finales[0]->asistencia; ?></td>
						<td><?php echo $comentarios_finales[0]->fecha; ?></td>
						<?php
					}
				
				?>


			</tr>
			
			<?php
		}
	}
	elseif ($seccion == "Pregunta") {
		$id_empresa = $_SESSION["id_empresa"];
		//traigo las respeustas de las pregutnas de esta empresa
		
		$respuestas = TraeRespuestasPorPreguntaConcurso($id_empresa);
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=RespuestasConcursos.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		
		?>
		<table border="1">
		<tr>
			<td></td>
			<td>Pregunta</td>
			<td>Colaborador</td>
			<td>Respuesta</td>
			<td>Fecha</td>
		</tr>
		<?php
		$i = 1;
		foreach ($respuestas as $res) {
			echo "
<tr>
<td>$i</td>
<td>" . $res->pregunta . "</td>
<td>" . $res->nombre_completo . "</td>
<td>" . $res->respuesta . "</td>
<td>" . $res->fecha . "</td>
</tr>

";
			$i++;
		}
	}
	elseif ($seccion == "listmejorespracticasExcel") {
		$id_empresa = $_SESSION["id_empresa"];
		//traigo las respeustas de las pregutnas de esta empresa
		
		$Practicas = TraeMejoresPracticasPorEmpresa($idproceso, $id_empresa);
		
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=Lista_Mejores_Practicas.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		?>
		<table border="1">
		<tr>
			<td>COD_PRACTICA</td>
			<td>TITULO</td>
			<td>OBJETIVO</td>
			<td>MEDICION_EXITO</td>
			<td>CONTENIDO</td>
			<td>ESTADO</td>
			<td>CATEGORIA</td>
			<td>FECHA</td>
			<td>AUTOR</td>
			<td>VISITAS</td>
			<td>DESCARGAS</td>
			<td>MEGUSTA</td>
			<td>FAVORITA</td>
			<td>COMENTARIOS</td>

		</tr>
		<?php
		$i = 1;
		foreach ($Practicas as $Practica) {
			echo "
<tr>
<td>" . $Practica->id_mp . "</td>
<td>" . $Practica->nombre . "</td>
<td>" . $Practica->objetivo . "</td>
<td>" . $Practica->impacto . "</td>
<td>" . strip_tags($Practica->contenido) . "</td>
<td>" . $Practica->nombreestado . "</td>
<td>" . $Practica->nombrecategoria . "</td>
<td>" . $Practica->fecha . "</td>
<td>" . $Practica->nombreautor . "</td>
<td>" . $Practica->numvisitas . "</td>
<td>" . $Practica->numdescargas . "</td>
<td>" . $Practica->nummegusta . "</td>
<td>" . $Practica->numfavorita . "</td>
<td>" . $Practica->numcomentarios . "</td>

</tr>

";
			$i++;
		}
		echo "
<table>";
	}
	elseif ($seccion == "edobjs") {
		$cargo = Decodear3($get["ca"]);
		$jefe = Decodear3($get["rj"]);
		echo $jefe;
		$PRINCIPAL = ListadoObjetivosPorCargo(FuncionesTransversalesAdmin(file_get_contents("views/dnc/entorno_detalle_objetivos.html")), $jefe, $cargo);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "ediobadmin") {
		$jefe = Decodear3($post["j"]);
		$cargo = Decodear3($post["c"]);
		$objetivos_dado_cargo_jefe = TraeObjetivosDadoCargoYJefe($jefe, $cargo);
		foreach ($objetivos_dado_cargo_jefe as $ob) {
			$objetivo = ($post["objetivo" . $ob->id]);
			$conducta1 = ($post["conducta1" . $ob->id]);
			$conducta2 = ($post["conducta2" . $ob->id]);
			$conducta3 = ($post["conducta3" . $ob->id]);
			//Actualizo AHORA
			ActualizarObjetivoDNCADMIN($objetivo, $conducta1, $conducta2, $conducta3, $ob->id);
		}
		
		echo "
<script>
alert('OBjetivos Actualizados Correctamente');
location.href='?sw=veobj&i=$jefe';
</script>";
		exit;
	}
	elseif ($seccion == "veobj") {
		$rut = $get["i"];
		
		$PRINCIPAL = ListadoCargosPorJefe(FuncionesTransversalesAdmin(file_get_contents("views/dnc/entorno_detalle_cargos.html")), $rut);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "envCorreoMasivo") {
		$id_inscripcion = Decodear3($get["ii"]);
		$id_proceso = Decodear3($get["ip"]);
		if ($id_inscripcion) {
			$usuarios = TraigoListadoUsuariosAEnviarCorreoPorInscripcion($id_inscripcion, 1);
		}
		else {
			$usuarios = TraigoListadoUsuariosAEnviarCorreoPorProcesoMasivo($id_inscripcion, 1);
		}
		
		if ($usuarios) {
			$rut = $usuarios[0]->rut;
			$tipo_correo = 7;
			$parametro = "?sw=envCorreoMasivo&ii=" . Encodear3($id_inscripcion);
			$html = file_get_contents("views/timmer.html");
			$html = str_replace("{URL}", $parametro, $html);
			echo $html;
			$TEMPLATE = ReplaceDatosDeCorreo($rut, $tipo_correo);
			//Dada la inscripcion, traigo el listado de los usuarios
			EnviarCorreosPruebas($rut, $TEMPLATE, $tipo_correo, $id_inscripcion);
			echo $TEMPLATE;
		}
		else {
			echo "No quedan mas usuarios";
		}
	}
	elseif ($seccion == "notificaciones_busca_pendientes_elimina") {
		DeleteNotificacionesPendientesCargosNoPermitidos();
	}
	elseif ($seccion == "notificaciones_busca_pendientes") {
		$id_empresa = $_SESSION["id_empresa"];
		NotificacionesBuscaPendientesAutomatico($id_empresa, $url_front, $url_front_admin_full, $logo, $from, $nombrefrom);
		echo "pendientes Creadas";
		
	}
	elseif ($seccion == "notificaciones_automaticas_envio") {
		$id_empresa = $_SESSION["id_empresa"];
		
		NotificacionesAutomaticas_envio($id_empresa, $url_front, getenv("HTTP_URL"), $logo, $from, $nombrefrom);
		echo "Notificaciones Enviadas";
		
		echo "
<script>
    location.href='?sw=notificaciones_asignacion';
</script>";
		exit;
	}
	elseif ($seccion == "envCorreoMasivoPorProceso") {
		$id_proceso = Decodear3($get["ip"]);
		$total_usuarios_por_proceso_enviados = TraigoUsuariosDadoIdPRocesoTotalEnviados($id_proceso);
		$total_usuarios_por_proceso = TraigoUsuariosDadoIdPRocesoTotal($id_proceso);
		$tipo_correo = $total_usuarios_por_proceso[0]->tipo_correo;
		$tipo = "text/html";
		$id_empresa = $_SESSION["id_empresa"];
		$usuarios = TraigoListadoUsuariosAEnviarCorreoPorProcesoMasivo($id_proceso, "");
		
		// print_r($usuarios); exit();
		foreach ($usuarios as $usuario) {
			$rut = $usuario->rut;
			$email = $usuario->email;
			$nombre_completo = $usuario->nombre_completo;
			
			echo "<br>Envío a rut $rut,  email  $email";
			//
			$to = $email;
			$subject = ($usuario->subject);
			$titulo1 = ($usuario->titulo1);
			$subtitulo1 = ($usuario->nombre_completo) . ", " . ($usuario->subtitulo1);
			$texto1 = ($usuario->texto1);
			$texto2 = ($usuario->texto2);
			$texto3 = ($usuario->texto3);
			$texto4 = ($usuario->texto4);
			$url = $usuario->url;
			$texto_url = $usuario->texto_url;
			
			SendGrid_Email($to, $nombreto, $from, $nombrefrom, $tipo, $subject, $titulo1, $subtitulo1, $texto1, $url, $texto_url, $texto2, $texto3, $texto4, $logo, $id_empresa, $url, "Email_Masivo", $rut, $id_inscripcion);
			
			ActualizoEstadoEnvioCorreoPorProceso($rut, $id_proceso);
		}
		
		echo "<center>Total de Usuarios Enviados" . count($total_usuarios_por_proceso) . "</center>";
		sleep(3);
		//   echo "Se enviaron todos los emails";
		echo "
<script>
location.href='?sw=listProcesosCorreos';
</script>";
		exit;
	}
	elseif ($seccion == "EnvioInvitaciones_EvalMedicionUnica") {
		//Busca Usuarios Activos para Enviar Invitacion Hoy
		$idMed = $get["idMed"];
		$id_proceso = Decodear3($get["ip"]);
		
		$tipo_correo = $total_usuarios_por_proceso[0]->tipo_correo;
		
		$id_empresa = $_SESSION["id_empresa"];
		
		$hoy = date("Y-m-d");
		$mediciones = BuscaMediciones_UnicaData($id_empresa, $idMed);
		
		
		foreach ($mediciones as $unico) {
			
			
			$array_encuesta = MedBuscaIdEncuestraMed($unico->id, $id_empresa);
			$id_encuesta = $array_encuesta[0]->id_encuesta;
			
			//por cada medicion ir a buscar usuarios pendientes
			$arr_usuario = BuscaMedicionesPendientes_hoy($unico->id, $id_encuesta, $hoy, $id_empresa);
			$tipo = "text/html";
			foreach ($arr_usuario as $arr_usu) {
				//    print_r($arr_usu); sleep(5);
				
				if ($arr_usu->NumPreguntas >= $arr_usu->NumRespuestas) {
					//    echo "se envia invitacion";
					$to = $arr_usu->email;
					$nombreto = $arr_usu->nombre_completo;
					
					if ($id_empresa == "62") {
						$from = getenv('EMAIL_FROM');
						$nombrefrom = getenv('EMAIL_FROM_NAME');
						$tipo = "text/html";
						$subject = ("Te invitamos a realizar una Evaluación de Impacto");
						$titulo1 = ("Te invitamos a realizar Evaluación de Impacto de la Capacitación sobre " . $arr_usu->nombre_completo_col);
						$subtitulo1 = ("La evaluación " . $arr_usu->medicion . " para " . $arr_usu->nombre_completo_col . " es parte de nuestro proceso de certificación de la calidad de la capacitación.");
						$texto1 = ("¡No te tomará más de 10 minutos!");
						$texto2 = "Ingresa a nuestra plataforma de Capacitación y responde no más de 10 preguntas.";
						//$texto2=($texto2);
						$texto3 = "";
						$texto4 = "";
						$url = getenv("MC_URL");
						$texto_url = "Ingresa a Capacitacionbci.cl";
						$logo = "";
						$id_empresa = $id_empresa;
						$rut = $arr_usu->jefe;
						$id_inscripcion = $unico->id;
						
						
						SendGrid_Email($to, $nombreto, $from, $nombrefrom, $tipo, $subject, $titulo1, $subtitulo1, $texto1, $url, $texto_url, $texto2, $texto3, $texto4, $logo, $id_empresa, $url, "Email_Masivo_Med", $rut, $id_inscripcion, "");
					}
				}
			}
		}
		
		echo "
<script>
location.href='?sw=lista_Mediciones_med';
</script>";
		exit;
	}
	elseif ($seccion == "EnvioInvitaciones_EvalMedicion") {
		//Busca Usuarios Activos para Enviar Invitacion Hoy
		$idMed = $get["idMed"];
		$id_proceso = Decodear3($get["ip"]);
		
		$tipo_correo = $total_usuarios_por_proceso[0]->tipo_correo;
		
		$id_empresa = $_SESSION["id_empresa"];
		
		$hoy = date("Y-m-d");
		$mediciones = BuscaMediciones_hoy($id_empresa, $hoy);
		
		
		
		foreach ($mediciones as $unico) {
			
			
			$array_encuesta = MedBuscaIdEncuestraMed($unico->id, $id_empresa);
			$id_encuesta = $array_encuesta[0]->id_encuesta;
			
			//por cada medicion ir a buscar usuarios pendientes
			$arr_usuario = BuscaMedicionesPendientes_hoy($unico->id, $id_encuesta, $hoy, $id_empresa);
			$tipo = "text/html";
			foreach ($arr_usuario as $arr_usu) {
				//    print_r($arr_usu); sleep(5);
				
				if ($arr_usu->NumPreguntas >= $arr_usu->NumRespuestas) {
					//    echo "se envia invitacion";
					$to = $arr_usu->email;
					$nombreto = $arr_usu->nombre_completo;
					
					if ($id_empresa == "62") {
						$from = getenv('EMAIL_FROM');
						$nombrefrom = getenv('EMAIL_FROM_NAME');
					}
					
					$tipo = "text/html";
					$subject = ("Te invitamos a realizar una Evaluación de Impacto");
					$titulo1 = ("Te invitamos a realizar Evaluación de Impacto de la Capacitación sobre " . $arr_usu->nombre_completo_col);
					$subtitulo1 = ("La evaluación " . $arr_usu->medicion . " para " . $arr_usu->nombre_completo_col . " es parte de nuestro proceso de certificación de la calidad de la capacitación.");
					$texto1 = ("¡No te tomará más de 10 minutos!");
					$texto2 = "Ingresa a nuestra plataforma de Capacitación y responde no más de 10 preguntas.";
					//$texto2=($texto2);
					$texto3 = "";
					$texto4 = "";
					$url = "https://gcloud.cl";
					$texto_url = "Ingresa a Capacitacionbci.cl";
					$logo = "";
					$id_empresa = $id_empresa;
					$rut = $arr_usu->jefe;
					$id_inscripcion = $unico->id;
					
					
					SendGrid_Email($to, $nombreto, $from, $nombrefrom, $tipo, $subject, $titulo1, $subtitulo1, $texto1, $url, $texto_url, $texto2, $texto3, $texto4, $logo, $id_empresa, $url, "Email_Masivo_Med", $rut, $id_inscripcion, "");
					//ActualizoEstadoEnvioCorreoPorProceso($rut, $id_proceso);
				}
			}
			// ActualizoEstadoEnvioCorreoPorProceso($rut, $id_proceso);
		}
		
		echo "
<script>
location.href='?sw=lista_Mediciones_med';
</script>";
		exit;
	}
	elseif ($seccion == "enviaCorreoTipo") {
		if ($get["mas"] == "1") {
			$rut = Decodear3($get["r"]);
			$tipo_correo = $get["tipoCorreo"];
		}
		$rut = Decodear3($post["r"]);
		$tipo_correo = $post["tipoCorreo"];
		//Traigo datos del Correo
		
		$TEMPLATE = ReplaceDatosDeCorreo($rut, $tipo_correo);
		
		//EnviaCorreoPrueba($rut, $TEMPLATE);
		EnviarCorreosPruebas($rut, $TEMPLATE, $tipo_correo, "");
		//EnviarCorreosPruebasGmail($rut, $TEMPLATE);
		?>
		<center>
			<?php
				echo $TEMPLATE;
			?>
		</center>
		<?php
	}
	elseif ($seccion == "enviaCorreoTipoPorProceso") {
		$id_proceso = Decodear3($post["idp"]);
		$rut = Decodear3($post["r"]);
		
		$tipo = "text/html";
		$id_empresa = $_SESSION["id_empresa"];
		$usuarios = TraigoListadoUsuariosAEnviarCorreoPorProcesoMasivoRut($id_proceso, $rut);
		
		print_r($usuarios);
		foreach ($usuarios as $usuario) {
			$rut = $usuario->rut;
			$email = $usuario->email;
			$nombre_completo = $usuario->nombre_completo;
			
			echo "<br>Envío a rut $rut,  email  $email";
			//
			$to = $email;
			$subject = ($usuario->subject);
			$titulo1 = ($usuario->titulo1);
			$subtitulo1 = ($usuario->nombre_completo) . ", " . ($usuario->subtitulo1);
			$texto1 = ($usuario->texto1);
			$texto2 = ($usuario->texto2);
			$texto3 = ($usuario->texto3);
			$texto4 = ($usuario->texto4);
			$url = $usuario->url;
			$texto_url = $usuario->texto_url;
			
			SendGrid_Email($to, $nombreto, $from, $nombrefrom, $tipo, $subject, $titulo1, $subtitulo1, $texto1, $url, $texto_url, $texto2, $texto3, $texto4, $logo, $id_empresa, $url, "Email_Masivo", $rut, $id_inscripcion);
		}
		
		ActualizoEstadoEnvioCorreoPorProceso($rut, $id_proceso);
	}
	elseif ($seccion == "Download_Respuestas_EncuestaLB") {
		$idMed = $get["idMed"];
		$DatosMed = DatosMedicionAdmin($idMed);
		$nombre_medicion = $DatosMed[0]->nombre;
		$tipo_individual = $DatosMed[0]->tipo_medicion;
		$id_encuesta = $DatosMed[0]->id_encuesta;
		$GroupRuts = BuscaUsuariosRespondieronEncLBSinUsuario_CadaLineaRespuesta($idMed);
		
		
		$hoy = date("Y-m-d");
		
		header("content-type:application/csv;charset=ISO 8859-1");
		header('Content-Disposition: attachment; filename=Resultado_Encuestas_' . $idMed . '.csv');
		echo "Rut;Nombre;Perfil;Glosa_cargo;Fecha_ingreso;Division;Area;Departamento;Zona;Seccion;Cod_unidad;Unidad;Oficina;Region;Empresa;Rut_Jefe;Nombre_Jefe;Fecha;Hora;Encuesta;IdProsci;IdEvento;Desc_Evento;Pregunta;Respuesta;Comentario\r\n";
		
		foreach ($GroupRuts as $u) {
			$u->comentario = str_replace("<br>", "", $u->comentario);
			$u->comentario = str_replace("/\r|\n/", "", $u->comentario);
			$u->comentario = str_replace(array("\r", "\n"), '', $u->comentario);
			$u->comentario = str_replace(array("\r\n", "\r", "\n"), "", $u->comentario);
			$u->comentario = strip_tags(trim($u->comentario));
			
			if ($u->id_foco <> "") {
				$Prosci_proyecto = Prosci_Lista_Proyectos_DadoId_data($u->id_foco, "78");
				$Proyecto = $Prosci_proyecto[0]->nombre;
			}
			else {
				$Proyecto = "";
			}
			
			if ($u->id_tipo <> "") {
				
				$Postulable = Lista_Postulable_por_id_full($u->id_tipo);
				$Tipo = $Postulable[0]->nombre;
				$Tipo_Desc = $Postulable[0]->descripcion;
			}
			else {
				$Tipo = "";
				$Tipo_Desc = "";
			}
			
			$Pregunta_Array = BuscaPreguntasIdPregunta($u->id_pregunta, $id_encuesta);
			$Pregunta = $Pregunta_Array[0]->pregunta;
			$Encuesta = $nombre_medicion;
			
			
			echo $u->rut . ";" . $u->nombre . ";" . $u->perfil . ";" . $u->glosa_cargo . ";" . $u->fecha_ingreso . ";" . $u->division . ";" . $u->area . ";" . $u->departamento . ";" . $u->zona . ";" . $u->seccion . ";" . $u->cod_unidad . ";" . $u->unidad . ";" . $u->oficina . ";" . $u->region . ";" . $u->empresa . ";" . $u->rut_jefe . ";" . $u->nombre_jefe . ";" . $u->fecha . ";" . $u->hora . ";" . $Encuesta . ";" . $Proyecto . ";" . $Tipo . ";" . $Tipo_Desc . ";" . $Pregunta . ";" . $u->respuesta . ";" . $u->comentario . ";\r\n";
		}
		
		
		exit();
	}
	elseif ($seccion == "Download_Respuestas_EncuestaLB_BK") {
		$idMed = $get["idMed"];
		
		$DatosMed = DatosMedicionAdmin($idMed);
		$nombre_medicion = $DatosMed[0]->nombre;
		$tipo_individual = $DatosMed[0]->tipo_medicion;
		//
		
		
		
		require_once 'clases/PHPExcel.php';
		$objPHPExcel = new PHPExcel();
		$styleArray = array('font' => array('bold' => true, 'color' => array('rgb' => '000000'), 'size' => 12, 'name' => 'Calibri'));
		
		$objPHPExcel->getProperties()->setCreator("GO Partners")->setLastModifiedBy("GO Partners")->setTitle("Respuestas por Medición")->setSubject("Respuestas por Medición")->setDescription("Respuestas por Medición")->setKeywords("Excel Office 2007 openxml php")->setCategory("Respuestas por Medición");
		
		$objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFill()->getStartColor()->setARGB('E8E8E8E8');
		
		$objPHPExcel->getActiveSheet()->getStyle("A6:F6")->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A6:F6')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A6:AZ6')->getFill()->getStartColor()->setARGB('d9edf7');
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A1", "Resultados por Encuesta " . ($nombre_medicion) . " (IdMed: $idMed) ");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B1", "");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B1", "");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C1", "");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D1", "");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("E1", "");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A2", "");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B2", "");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C2", "");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D2", "");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("E2", "");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("G4", "");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("G5", "");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("G6", "");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A6", "RUT");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B6", "NOMBRE");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C6", "CARGO");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D6", "EMAIL");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("E6", "DIVISION");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("F6", "SUCURSAL");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("G6", "");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L7", "");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L8", "");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L9", "");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L10", "");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L10", "");
		$i = 6;
		
		$cuenta_arrayfecha++;
		$i++;
		
		$TodasPreguntas = BuscaPreguntaDadaMedicion($idMed);
		// print_r($TodasPreguntas); exit();
		$letra = "F";
		foreach ($TodasPreguntas as $pregunta) {
			$letra++;
			$questionid++;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra . "5", "Preg_" . ($questionid));
		}
		
		$letra = "F";
		foreach ($TodasPreguntas as $pregunta) {
			$letra++;

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra . "6", ($pregunta->pregunta));
		}
		
		$letra = "F";
		foreach ($TodasPreguntas as $pregunta) {
			$letra++;
			
			$questionid++;
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra . "4", ($pregunta->tipo));
		}
		
		$GroupRuts = BuscaUsuariosRespondieronEncLBSinUsuario($idMed);
		
		
		foreach ($GroupRuts as $user_unico) {
			
			$Usu = TraeDatosUsuario($user_unico->rut);
			$UsuJ = TraeDatosUsuario($Usu[0]->jefe);

			if ($Usu[0]->nombre_completo == "") {
				$Usu[0]->nombre_completo = "";
			}
			if ($Usu[0]->nombre_completo == "") {
				$Usu[0]->cargo = "";
			}
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $i, ($user_unico->rut));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("B" . $i, ($Usu[0]->nombre_completo));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("C" . $i, ($Usu[0]->cargo));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("D" . $i, ($Usu[0]->email));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("E" . $i, ($Usu[0]->division));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("F" . $i, ($Usu[0]->ubicacion));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("G" . $i, ($Usu[0]->gerenciaR3));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("H" . $i, ($UsuJ[0]->rut));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("I" . $i, ($UsuJ[0]->nombre_completo));
			
			if ($tipo_individual != "INDIVIDUAL") {
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("J" . $i, ($UsuJ[0]->cargo));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("K" . $i, ($array_fecha));
			}
			else {
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("J" . $i, ($user_unico->fecha_carga));
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("K" . $i, ($user_unico->grupo));
			}
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("L" . $i, ($user_unico->fecharespuesta));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue("M" . $i, ($user_unico->comentario));
			
			$letra = "F";
			foreach ($TodasPreguntas as $pregunta) {
				$Respuestas_UsuariosIDPreg = BuscaRespuestasUsuarioMedEncLB($user_unico->rut, $pregunta->id_pregunta, $idMed, $array_fecha);

				$letra++;
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra . $i, ($Respuestas_UsuariosIDPreg[0]->respuesta));
			}
			$i++;
		}
		$sheet = $objPHPExcel->getActiveSheet();
		$cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
		$cellIterator->setIterateOnlyExistingCells(true);
		
		$objPHPExcel->getActiveSheet()->setTitle('Respuestas');
		
		$objPHPExcel->setActiveSheetIndex(0);
		
		$fechahoy = date("Y-m-d") . "_" . date("H:i:s");
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="Resultados_Encuesta_' . $idMed . '.xlsx"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}
	elseif ($seccion == "Encuestas_Replicar") {
		$id_empresa = $_SESSION["id_empresa"];
		$idMed = $get["idMed"];
		
		$idMed_Max = Encuestas_Busca_IdUltimoMasunoDos();
		
		$Med = BuscaMediciones_UnicaData($id_empresa, $idMed);
		$idMedNuevo = $idMed_Max[0]->maximo + 1;
		
		//medicion
		Insert_tbl_enc_elearning_medicion($idMedNuevo, $Med[0]->nombre, $Med[0]->descripcion, $id_empresa, $Med[0]->tipo_medicion, $idMedNuevo, $Med[0]->footerfinal);
		
		//elearning
		Insert_tbl_enc_elearning($idMedNuevo, $Med[0]->nombre, $Med[0]->descripcion, $id_empresa);
		
		$Preg = Trae_Preguntas_Grafico($idMed, $id_empresa);
		$cuenta_preg = 0;
		foreach ($Preg as $unico) {
			$cuenta_preg++;
			
			
			
			Insert_tbl_enc_elearning_preg($idMedNuevo, $cuenta_preg, $unico->pregunta, $id_empresa, $unico->tipo, $unico->dimension, $unico->alt1, $unico->alt2, $unico->alt3, $unico->alt4, $unico->alt5, $unico->alt6, $unico->alt7, $unico->alt8, $unico->alt9, $unico->alt10, $unico->dimension_descripcion, $unico->descripcion_pregunta, $unico->linkpregunta, $unico->linkopcion, $unico->alt11, $unico->alt12, $unico->alt13, $unico->alt14, $unico->alt15, $unico->alt16, $unico->alt17, $unico->alt18, $unico->alt19, $unico->alt20);
			
			//id_encuesta,id_pregunta,pregunta,id_empresa,tipo,dimension,alt1,alt2,alt3,alt4,alt5,alt6,alt7,alt8,alt9,alt10,
			//dimension_descripcion,descripcion_pregunta,linkpregunta,linkopcion,alt11,alt12,alt13,alt14,alt15,alt16,alt17,alt18,alt19,alt20
			
			
		}
		
		echo "<script>alert('Duplicacion Realizada Exitosamente');</script>";
		echo "<script>location.href='?sw=lista_encuestas_lb';    </script>";
		
		exit();
	}
	elseif ($seccion == "lista_encuestas_lb") {
		$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/encuesta_lb/entorno.html"));
		$id_empresa = $_SESSION["id_empresa"];
		$ie = $get["ie"];
		$desactiva = $get["desactiva"];
		$activa = $get["activa"];
		
		$prosci = $get["prosci"];
		
		//SE ACTUALIZA TBL RESPUESTAS fullName
		$fecha_inicial_check = "2020-07-01";
		ReporteFullRespuestas_Cron($fecha_inicial_check);
		
		
		if ($ie <> '' and $activa == 1) {
			Enc_ActualizaEstado($ie, "");
		}
		if ($ie <> '' and $desactiva == 1) {
			Enc_ActualizaEstado($ie, "desactiva");
		}
		
		$PRINCIPAL = str_replace("{ENTORNO_MEDICIONES}", lista_encuestas_fn_lb(FuncionesTransversalesAdmin(file_get_contents("views/encuesta_lb/entorno_mediciones_row.html")), $id_empresa, $id_categoria), $PRINCIPAL);
		
		
		$datos_empresa = DatosEmpresa($id_empresa);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "lista_encuestas_visualizacion_cluster") {
		$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/encuesta_lb/entorno.html"));
		$id_empresa = $_SESSION["id_empresa"];
		$ie = $get["ie"];
		$desactiva = $get["desactiva"];
		$activa = $get["activa"];
		if ($ie <> '' and $activa == 1) {
			Enc_ActualizaEstado($ie, "");
		}
		if ($ie <> '' and $desactiva == 1) {
			Enc_ActualizaEstado($ie, "desactiva");
		}
		
		$PRINCIPAL = str_replace("{ENTORNO_MEDICIONES}", lista_encuestas_visualizacion_cluster_fn_lb(FuncionesTransversalesAdmin(file_get_contents("views/encuesta_lb/entorno_mediciones_cluster_row.html")), $id_empresa, $id_categoria), $PRINCIPAL);
		
		$datos_empresa = DatosEmpresa($id_empresa);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "lista_encuestas_visualizacion_cluster_prosci") {
		$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/encuesta_lb/entorno.html"));
		$id_empresa = $_SESSION["id_empresa"];
		$ie = $get["ie"];
		$desactiva = $get["desactiva"];
		$activa = $get["activa"];
		if ($ie <> '' and $activa == 1) {
			Enc_ActualizaEstado($ie, "");
		}
		if ($ie <> '' and $desactiva == 1) {
			Enc_ActualizaEstado($ie, "desactiva");
		}
		
		$PRINCIPAL = str_replace("{ENTORNO_MEDICIONES}", lista_encuestas_visualizacion_cluster_prosci_fn_lb(FuncionesTransversalesAdmin(file_get_contents("views/encuesta_lb/entorno_mediciones_cluster_row.html")), $id_empresa, $id_categoria), $PRINCIPAL);
		
		$datos_empresa = DatosEmpresa($id_empresa);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "update_users_encuestas_lb") {
		$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/encuesta_lb/entorno.html"));
		
		$id_empresa = $_SESSION["id_empresa"];
		
		$Encuesta_array = DatosMedicionAdminUsers($get["id_encuesta"], $id_empresa);
		$num_usuarios = $Encuesta_array[0]->usuarios;
		$num_editores = $Encuesta_array[0]->editores;
		$num_visualizadores = $Encuesta_array[0]->visualizadores;
		
		if ($get["perfil"] == "Usuario") {
			$tipo_usuarios = "Usuarios Inscritos";
			$tipo_usuario = "Usuario";
			$encuesta = $Encuesta_array[0]->nombre;
			$num_usuarios = $num_usuarios;
		}
		
		if ($get["perfil"] == "Editor") {
			$tipo_usuarios = "Editores";
			$tipo_usuario = "Editor";
			$encuesta = $Encuesta_array[0]->nombre;
			$num_usuarios = $num_editores;
		}
		
		if ($get["perfil"] == "Visualizador") {
			$tipo_usuarios = "Visualizadores Resultados";
			$tipo_usuario = "Visualizador";
			$encuesta = $Encuesta_array[0]->nombre;
			$num_usuarios = $num_visualizadores;
		}
		
		$PRINCIPAL = str_replace("{ENTORNO_MEDICIONES}", lista_encuestas_fn_lb(FuncionesTransversalesAdmin(file_get_contents("views/encuesta_lb/update_users_encuesta.html")), $id_empresa, $id_categoria), $PRINCIPAL);
		//$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/encuesta_lb/update_users_encuesta.html"));
		
		$PRINCIPAL = str_replace("{TIPO_USUARIOS}", $tipo_usuarios, $PRINCIPAL);
		$PRINCIPAL = str_replace("{TIPO_USUARIO}", $tipo_usuario, $PRINCIPAL);
		$PRINCIPAL = str_replace("{ENCUESTA}", ($encuesta), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_ENCUESTA}", $get["id_encuesta"], $PRINCIPAL);
		$PRINCIPAL = str_replace("{NUM_TIPO_USUARIOS}", $num_usuarios, $PRINCIPAL);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
		exit();
	}
	elseif ($seccion == "update_users_encuestas_cluster_lb") {
		$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/encuesta_lb/entorno.html"));
		
		$id_empresa = $_SESSION["id_empresa"];
		
		$Clusterarray = DatosClusterAdminUsers($get["id_cluster"], $id_empresa);
		$num_usuarios = $Clusterarray[0]->usuarios;
		
		if ($get["perfil"] == "Usuario") {
			$tipo_usuarios = "Usuarios Visualizadores";
			$tipo_usuario = "Usuario";
			$cluster = $Clusterarray[0]->nombre;
			$num_usuarios = $num_usuarios;
		}
		
		
		$PRINCIPAL = str_replace("{ENTORNO_MEDICIONES}", lista_encuestas_visualizacion_cluster_fn_lb(FuncionesTransversalesAdmin(file_get_contents("views/encuesta_lb/update_users_cluster.html")), $id_empresa, $id_categoria), $PRINCIPAL);
		//$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/encuesta_lb/update_users_encuesta.html"));
		
		$PRINCIPAL = str_replace("{TIPO_USUARIOS}", $tipo_usuarios, $PRINCIPAL);
		$PRINCIPAL = str_replace("{TIPO_USUARIO}", $tipo_usuario, $PRINCIPAL);
		$PRINCIPAL = str_replace("{CLUSTER}", ($cluster), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_CLUSTER}", $get["id_cluster"], $PRINCIPAL);
		$PRINCIPAL = str_replace("{NUM_TIPO_USUARIOS}", $num_usuarios, $PRINCIPAL);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
		exit();
	}
	elseif ($seccion == "lista_encuestas_lb_update") {
		
		$tipo_usuario = $post["tipo_usuario"];
		$id_encuesta = $post["id_encuesta"];
		$archivo = $_FILES['excel']['name'];
		VerificaExtensionFilesAdmin($_FILES["excel"]);
		
		$tipo = $_FILES['excel']['type'];
		$id_empresa = $_SESSION["id_empresa"];
		
		
		if ($id_encuesta > 0) {
			if ($tipo_usuario == "Usuario") {
				Truncate_Encuesta_Usuario($id_encuesta);
			}
			else {
				Truncate_Encuesta_Usuario_Editor_Visualizador($id_encuesta, $tipo_usuario);
			}
		}
		
		$fileName = $_FILES["excel"]["tmp_name"];
		if ($_FILES["excel"]["size"] > 0) {
			$file = fopen($fileName, "r");
			
			
			while (($column = fgetcsv($file, 10000, ";")) !== FALSE) {
				$columnB = explode(";", $column[0]);
				$cuenta++;
				
				
				$rut = LimpiaRut($column[0]);
				$data_rut = TraeDatosUsuario($rut);
				
				if ($data_rut[0]->rut <> "") {
					if ($rut <> "" and $id_encuesta > 0 and $tipo_usuario <> "") {
						if ($tipo_usuario == "Usuario") {
							Inserta_Nueva_Encuesta_Usuario_Tipo($rut, $id_encuesta, $id_empresa);
						}
						else {
							Inserta_Nueva_Encuesta_Usuario_Tipo_Editor_Visualizador($rut, $id_encuesta, $tipo_usuario, $id_empresa);
						}
					}
				}
				else {
					if ($rut <> "") {
						echo "<div class='alert alert-danger'><span style='position: absolute;z-index:$current;background:#36C6D3; padding:10px; color:#FFF'>Rut " . $rut . " no se encuentra en la base </span></div>";
					}
				}
				
				
				$l++;
				
				echo "<center><span style='position: absolute;z-index:$current;background:#36C6D3; padding:10px; color:#FFF'>Inscribiendo Masivamente $l</span></center>";
				flush();
				ob_flush();
				
				
				
			}
		}
		
		echo "<script>location.href='?sw=lista_encuestas_lb';</script>";
		exit;
		
		
		$destino = "tmp_encuesta_" . $archivo;
		if (copy($_FILES['excel']['tmp_name'], $destino)) {
		}
		else {
			$error_grave = "Error Al subir Archivo<br />";
		}
		if (file_exists("tmp_encuesta_" . $archivo)) {
			require_once 'clases/PHPExcel.php';
			require_once 'clases/PHPExcel/Reader/Excel2007.php';
			$objReader = new PHPExcel_Reader_Excel2007();
			$objPHPExcel = $objReader->load("tmp_encuesta_" . $archivo);
			$objFecha = new PHPExcel_Shared_Date();
			$objPHPExcel->setActiveSheetIndex(0);
			$HojaActiva = $objPHPExcel->getActiveSheet();
			$total_filas = $HojaActiva->getHighestRow();
			$lastColumn = $HojaActiva->getHighestColumn();
			$total_columnas = PHPExcel_Cell::columnIndexFromString($lastColumn);
			$UltimaColumna = "A";
			$j = 0;
			$_DATOS_EXCEL = array();
			for ($i = 0; $i <= $total_columnas; $i++) {
				$_campo[$i] = trim(($HojaActiva->getCell($UltimaColumna . "1")->getCalculatedValue()));
				$UltimaColumna++;
			}
			for ($fila = 2; $fila <= $total_filas; $fila++) {
				for ($columna = 0; $columna <= $total_columnas - 1; $columna++) {
					$_DATOS_EXCEL[$j][$columna + 1] = (trim($HojaActiva->getCellByColumnAndRow($columna, $fila)->getCalculatedValue()));
				}
				$j++;
			}
		}
		else {
			$error_grave = "Necesitas primero importar el archivo";
		}
		$total_errores = 0;
		$total_actualizar = 0;
		$total_insertar = 0;
		$usuario_inexistente = 0;
		$curso_inexistente = 0;
		$linea = 2;
		$no_esta_en_tabla_usuario = 0;
		
		if (count($_DATOS_EXCEL) < 1) {
			echo "  <script>location.href='?sw=lista_encuestas_lb';</script>";
			exit;
		}
		
		ini_set('output_buffering', 0);
		ini_set('zlib.output_compression', 0);
		if (!ob_get_level()) {
			ob_start();
		}
		else {
			ob_end_clean();
			ob_start();
		}
		
		$fila = count($_DATOS_EXCEL);
		for ($l = 1; $l <= count($_DATOS_EXCEL); $l++) {
		}
		
		
	}
	elseif ($seccion == "gestion_admin_lb") {
		$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/encuesta_lb/entorno.html"));
		$id_empresa = $_SESSION["id_empresa"];
		
		$ie = $get["ie"];
		$desactiva = $get["desactiva"];
		$activa = $get["activa"];
		
		
		$PRINCIPAL = str_replace("{ENTORNO_MEDICIONES}", lista_admin_encuestas_fn_lb(FuncionesTransversalesAdmin(file_get_contents("views/encuesta_lb/entorno_mediciones_admin_row.html")), $id_empresa, $id_categoria), $PRINCIPAL);
		
		
		$datos_empresa = DatosEmpresa($id_empresa);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "subcategoriaDadaCategoria") {
		$categoria = $post["elegido"];
		
		// Traigo las subcategorias dada la categoria
		
		$subcategorias = SubcategoriasDadaCategoria($categoria);
		foreach ($subcategorias as $cat) {
			echo "<option value='" . $cat->id . "'>" . $cat->nombre . "</option>";
		}
	}
	elseif ($seccion == "ActualizaDescripcionImagen") {
		$id_archivo = $post["id_imagen"];
		$nueva_descripcion = $post["descripcion"];
		ActualizaDescripcionImagenConId($id_archivo, $nueva_descripcion);
		echo "$nueva_descripcion";
	}
	elseif ($seccion == "admin_galeria") {
		$id_empresa = $_SESSION["id_empresa"];
		$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/galeria/entorno.html"));
		if ($get["n"] == "1") {

			
			$PRINCIPAL = str_replace("{ENTORNO_GALERIA}", lista_carpetas(FuncionesTransversalesAdmin(file_get_contents("views/admin/galeria/entorno_categorias.html")), $id_empresa), $PRINCIPAL);
		}
		
		if ($get["n"] == "2") {
			
			
			$PRINCIPAL = str_replace("{ENTORNO_GALERIA}", lista_subcarpetas(FuncionesTransversalesAdmin(file_get_contents("views/admin/galeria/admin.html")), $get["id"], $get["cn"]), $PRINCIPAL);
		}
		
		if ($get["n"] == "3") {
			
			
			$PRINCIPAL = str_replace("{ENTORNO_GALERIA}", lista_galeria_archivos(FuncionesTransversalesAdmin(file_get_contents("views/admin/galeria/archivos.html")), $get["id"], $get["cn"], $get["id_sub"], $get["nsub"]), $PRINCIPAL);
		}
		
		if ($get["n"] == "fc") {
			
			
			$PRINCIPAL = str_replace("{ENTORNO_GALERIA}", nueva_carpeta(file_get_contents("views/admin/galeria/nueva_categoria.html"), $get["id_cat"]), $PRINCIPAL);
		}
		
		if ($get["n"] == "fs") {
			
			
			$PRINCIPAL = str_replace("{ENTORNO_GALERIA}", nuevo_album(file_get_contents("views/admin/galeria/nueva_sub_categoria.html"), $get["id_cat"], $get["cn"], $get["id_subcat"]), $PRINCIPAL);
		}
		
		if ($get["n"] == "fa") {
			
			
			$PRINCIPAL = str_replace("{ENTORNO_GALERIA}", subir_archivo_galeria(file_get_contents("views/admin/galeria/subir_archivo.html"), $get["id"], $get["cn"], $get["id_sub"], $get["nsub"], $get["id_ar"]), $PRINCIPAL);
		}
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "admin_biblio") {
		$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/biblioteca/entorno.html"));
		$id_empresa = $_SESSION["id_empresa"];
		
		
		if ($get["n"] == "1") {
			
			$PRINCIPAL = str_replace("{ENTORNO_BIBLIOTECA}", lista_categorias(FuncionesTransversalesAdmin(file_get_contents("views/admin/biblioteca/entorno_categorias.html")), $id_empresa), $PRINCIPAL);
		}
		if ($get["n"] == "2") {
			
			$PRINCIPAL = str_replace("{ENTORNO_BIBLIOTECA}", lista_subcategorias(FuncionesTransversalesAdmin(file_get_contents("views/admin/biblioteca/admin.html")), $get["id"], $get["cn"]), $PRINCIPAL);
		}
		if ($get["n"] == "3") {
			
			$PRINCIPAL = str_replace("{ENTORNO_BIBLIOTECA}", lista_archivos(FuncionesTransversalesAdmin(file_get_contents("views/admin/biblioteca/admin.html")), $get["id"], $get["cn"], $get["id_sub"], $get["nsub"]), $PRINCIPAL);
		}
		if ($get["n"] == "fc") {
			
			$PRINCIPAL = str_replace("{ENTORNO_BIBLIOTECA}", nueva_cat(file_get_contents("views/admin/biblioteca/nueva_categoria.html"), $get["id_cat"]), $PRINCIPAL);
		}
		if ($get["n"] == "fs") {
			
			$PRINCIPAL = str_replace("{ENTORNO_BIBLIOTECA}", nueva_sub_cat(file_get_contents("views/admin/biblioteca/nueva_sub_categoria.html"), $get["id_cat"], $get["cn"], $get["id_subcat"]), $PRINCIPAL);
		}
		if ($get["n"] == "fa") {
			
			$PRINCIPAL = str_replace("{ENTORNO_BIBLIOTECA}", subir_archivo(file_get_contents("views/admin/biblioteca/subir_archivo.html"), $get["id"], $get["cn"], $get["id_sub"], $get["nsub"], $get["id_ar"]), $PRINCIPAL);
		}
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "admin_preguntasyrespuestas") {
		$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/preguntasyrespuestas/entorno.html"));
		$id_empresa = $_SESSION["id_empresa"];
		
		
		if ($get["n"] == "1") {
			
			$PRINCIPAL = str_replace("{ENTORNO_PREGUNTASYRESPUESTAS}", lista_preguntasyrespuestas(FuncionesTransversalesAdmin(file_get_contents("views/preguntasyrespuestas/entorno_categorias.html")), $id_empresa, $id_categoria), $PRINCIPAL);
		}
		
		if ($get["n"] == "2") {
			
			$PRINCIPAL = str_replace("{ENTORNO_BIBLIOTECA}", lista_subcategorias(FuncionesTransversalesAdmin(file_get_contents("views/admin/biblioteca/admin.html")), $get["id"], $get["cn"]), $PRINCIPAL);
		}
		if ($get["n"] == "3") {
			
			$PRINCIPAL = str_replace("{ENTORNO_BIBLIOTECA}", lista_archivos(FuncionesTransversalesAdmin(file_get_contents("views/admin/biblioteca/admin.html")), $get["id"], $get["cn"], $get["id_sub"], $get["nsub"]), $PRINCIPAL);
		}
		if ($get["n"] == "fc") {
			
			$PRINCIPAL = str_replace("{ENTORNO_BIBLIOTECA}", nueva_cat(file_get_contents("views/admin/biblioteca/nueva_categoria.html"), $get["id_cat"]), $PRINCIPAL);
		}
		if ($get["n"] == "fs") {
			
			$PRINCIPAL = str_replace("{ENTORNO_BIBLIOTECA}", nueva_sub_cat(file_get_contents("views/admin/biblioteca/nueva_sub_categoria.html"), $get["id_cat"], $get["cn"], $get["id_subcat"]), $PRINCIPAL);
		}
		if ($get["n"] == "fa") {
			
			$PRINCIPAL = str_replace("{ENTORNO_BIBLIOTECA}", subir_archivo(file_get_contents("views/admin/biblioteca/subir_archivo.html"), $get["id"], $get["cn"], $get["id_sub"], $get["nsub"], $get["id_ar"]), $PRINCIPAL);
		}
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "sorta") {
		InsertLogPrueba();
	}
	elseif ($seccion == "ordenarSliders") {
		$id_empresa = Decodear3($post["id_emp"]);
		
		$tnoticias1 = TotalNoticiasSlider($id_empresa);
		foreach ($tnoticias1 as $not) {

			ActualizoSliderActivo($not->id, $post["orden_" . $not->id]);
		}
		echo "
<script>

location.href='?sw=linoticias#BloqueSlider';
</script>";
		exit;
	}
	elseif ($seccion == "lipaginas") {
		$id_empresa = $_SESSION["id_empresa"];
		$PRINCIPAL = (ListadoPaginasParaAdmin(FuncionesTransversalesAdmin(file_get_contents("views/paginas/listado.html")), $id_empresa));
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "linoticias") {
		$id_empresa = $_SESSION["id_empresa"];
		$PRINCIPAL = (ListadoNoticiasAdmin(FuncionesTransversalesAdmin(file_get_contents("views/noticias/listado_noticias.html")), $id_empresa));
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "ordenarNoticiasCampoOrden") {
		//aca deberia traer todas las noticias con estado 4, y no son slider de la empresa correspondiente
		
		$noticias = TodasLasNoticiasPublicadasSinSlider($_SESSION["id_empresa"]);
		foreach ($noticias as $not) {
			if ($post["orden_" . $not->id] > 0) {
				//Actualizo el campo orden de la tabla noticia
				ActualizaCampoOrdenPorIdNoticia($not->id, $post["orden_" . $not->id]);
			}
		}
		echo "
<script>

location.href='?sw=linoticias';
</script>";
		exit;
	}
	elseif ($seccion == "edPaginaAccion") {
		$id_pagina = Decodear3($post["id_pagina"]);
		$contenido = ($post["editor"]);
		//Aca actualizo el contenido de la tabla dado el id
		ActualizoContenidosPagina($id_pagina, $contenido);
		
		echo "
<script>

location.href='?sw=lipaginas';
</script>";
		exit;
	}
	elseif ($seccion == "edpagina") {
		$id_pagina = Decodear3($get["i"]);
		$PRINCIPAL = FormularioPagina(FuncionesTransversalesAdmin(file_get_contents("views/paginas/form_add.html")), $id_pagina);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "edinoti") {
		$id_empresa = Decodear3($get["i"]);
		$PRINCIPAL = FormularioNoticia(FuncionesTransversalesAdmin(file_get_contents("views/noticias/form_add.html")), $id_empresa);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "addcategoria") {
		$id_menu = Decodear3($get["i"]);
		$PRINCIPAL = AccionCategoria(FuncionesTransversalesAdmin(file_get_contents("views/categorias/formulario.html")), $id_menu);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "dltsubmenu") {
		$id_submenu = Decodear3($get["is"]);
		$id_menu = Decodear3($get["i"]);
		
		BorroSubMenu($id_submenu);
		
		echo "
<script>
alert('Submenu Eliminado');
location.href='?sw=asigsub&i=" . Encodear3($id_menu) . "';
</script>";
		exit;
	}
	elseif ($seccion == "addmenu") {
		$id_menu = Decodear3($get["i"]);
		$PRINCIPAL = AccionMenu(FuncionesTransversalesAdmin(file_get_contents("views/menu_secundario/formulario.html")), $id_menu);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "addcat") {
		$categoria = ($post["nombre"]);
		$descripcion = ($post["descripcion"]);
		echo "$categoria $descripcion";
		InsertaCategoriaNoticia($categoria, $descripcion);
		//Inserto registro de la categoria
		
		echo "
<script>

location.href='?sw=listcategorias';
</script>";
		exit;
	}
	elseif ($seccion == "addm") {
		$nombre = ($post["nombre"]);
		$descripcion = ($post["descripcion"]);
		$tipo_link = $post["tipo_contenido"];
		if ($tipo_link == "2") {
			$link = ($post["template_predefinido"]);
		}
		elseif ($tipo_link == "3") {
			$link = ($post["link"]);
		}
		else {
			$link = "";
		}
		
		$id_empresa = $_SESSION["id_empresa"];
		InsertaMenuN1($nombre, $link, $id_empresa, $descripcion, $tipo_link);
		
		echo "
<script>

location.href='?sw=listmenus';
</script>";
		exit;
	}
	elseif ($seccion == "edm") {
		$id_menu = Decodear3($post["idme"]);
		$nombre = ($post["nombre"]);
		$tipo_link = $post["tipo_contenido"];
		$id_submenu = Decodear3($post["is"]);
		if ($tipo_link == "2") {
			$link = ($post["template_predefinido"]);
		}
		elseif ($tipo_link == "3") {
			$link = ($post["link"]);
		}
		else {
			$link = "";
		}
		
		
		
		$id_empresa = $_SESSION["id_empresa"];
		
		//DAtos Submenu
		$nombre_submenu = ($post["nombre_submenu"]);
		$tipo_link_submenu = $post["tipo_contenido_submenu"];
		
		if ($tipo_link_submenu == "2") {
			$link_submenu = ($post["template_predefinido_submenu"]);
		}
		elseif ($tipo_link_submenu == "3") {
			$link_submenu = ($post["link_submenu"]);
		}
		else {
			$link_submenu = "";
		}
		
		if ($id_submenu) {
			
			//Actualiza Datos de Submenu
			ActualizarSubMenu($id_submenu, $nombre_submenu, $link_submenu, $tipo_link_submenu);
		}
		else {
			InsertaMenuN2($nombre_submenu, $link_submenu, $id_menu, $tipo_link_submenu);
		}
		
		ActualizaDatosMenuN1($nombre, $link, $id_menu);

		
		echo "
<script>

location.href='?sw=listmenus';
</script>";
		exit;
	}
	elseif ($seccion == "listmenus") {
		$PRINCIPAL = ListadoMenus(FuncionesTransversalesAdmin(file_get_contents("views/menu_secundario/entorno_listado.html")), $_SESSION["id_empresa"]);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "listcomunidades") {
		$PRINCIPAL = ListadoComunidades(FuncionesTransversalesAdmin(file_get_contents("views/comunidades/entorno_listado.html")), $_SESSION["id_empresa"]);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "listmundos") {
		$PRINCIPAL = ListadoMundos(FuncionesTransversalesAdmin(file_get_contents("views/mundos/entorno_listado.html")), $_SESSION["id_empresa"]);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "listcategorias") {
		$PRINCIPAL = ListadoCategorias(FuncionesTransversalesAdmin(file_get_contents("views/categorias/entorno_listado.html")), $_SESSION["id_empresa"]);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "eddatoscat") {
		$id_categoria = Decodear3($post["idcat"]);
		$nombre_categoria = ($post["nombre"]);
		$descripcion = ($post["descripcion"]);
		//aca actualizo los datos de la categoria
		ActualizoDatosCategoriaNoticia($id_categoria, $nombre_categoria, $descripcion);
		echo "
<script>
alert('Noticia Editada exitosamente');
location.href='?sw=asigsubc&i=" . Encodear3($id_categoria) . "';
</script>";
		exit;
	}
	elseif ($seccion == "dltsubcat") {
		$id_categoria = Decodear3($get["i"]);
		$id_sub_categoria = Decodear3($get["is"]);
		
		BorroSubCategoria($id_sub_categoria);
		echo "$id_categoria, $id_sub_categoria";
		
		echo "
<script>
alert('SubCategoria Eliminada');
location.href='?sw=asigsubc&i=" . Encodear3($id_categoria) . "';
</script>";
		exit;
	}
	elseif ($seccion == "asigsubc") {
		$id_menu = Decodear3($get["i"]);
		$id_subcategoria = Decodear3($get["is"]);
		
		$PRINCIPAL = AccionSubCategoria(FuncionesTransversalesAdmin(file_get_contents("views/categorias/formularioSubCategoria.html")), $id_menu, $id_subcategoria);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "addsubcate") {
		$id_categoria = Decodear3($post["idcat"]);
		$nombre = ($post["nombre"]);
		$descripcion = ($post["descripcion"]);
		echo "$id_categoria, $nombre, $descripcion";
		InsertaSubCategoriaNoticia($nombre, $descripcion, $id_categoria);
		
		echo "
<script>
alert('Subcategoria Ingresada');
location.href='?sw=asigsubc&i=" . Encodear3($id_categoria) . "';
</script>";
		exit;
	}
	elseif ($seccion == "eddsubcate") {
		$id_categoria = Decodear3($post["idcat"]);
		$id_subcategoria = Decodear3($post["idsubcat"]);
		$nombre = ($post["nombre"]);
		$descripcion = ($post["descripcion"]);
		echo "$id_categoria, $id_subcategoria, $nombre, $descripcion";
		//Actualizo los datos
		ActualizoSubCategoria($id_subcategoria, $nombre, $descripcion);
		
		echo "
<script>
alert('Subcategoria Modificada');
location.href='?sw=asigsubc&i=" . Encodear3($id_categoria) . "';
</script>";
		exit;
	}
	elseif ($seccion == "asigsub") {
		$id_menu = Decodear3($get["i"]);
		$id_submenu = Decodear3($get["is"]);
		
		$PRINCIPAL = AccionMenu(FuncionesTransversalesAdmin(file_get_contents("views/menu_secundario/fomulario_nivel2.html")), $id_menu, $id_submenu);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "deletenoticia") {
		$id_noticia = $get['id_noticia'];
		BorrarNoticia($id_noticia);
		echo "
<script>
alert('Noticia eliminada completamente.');
location.href='?sw=linoticias';
</script>";
		exit;
	}
	elseif ($seccion == "listusuarios") {
		$PRINCIPAL = (ListadoUsuarios(FuncionesTransversalesAdmin(file_get_contents("views/personas/entorno_personas.html"))));
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "mempre") {
		$PRINCIPAL = (ListadoEmpresas(FuncionesTransversalesAdmin(file_get_contents("views/empresa/entorno_listado_empresas.html"))));
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "muestraDatosFiltro2") {
		$nombre_indicador = $post["elegido"];
		$datos_Campos = TraeDistictDadoCampoPorEmpresa($nombre_indicador, $_SESSION["id_empresa"]);
		echo "<option value='-'>Todas</option>";
		foreach ($datos_Campos as $dat) {
			echo "<option>" . ($dat->valor) . "</option>";
		}
	}
	elseif ($seccion == "muestraDatosFiltro2Campo2") {
		$campo1 = $post["elegido"];
		
		$campos = TraeCampos($_SESSION["id_empresa"]);
		//Dado lo datos del campo 1, obtengo datos del campo 2
		$datos_Campos = TraeDistictDadoCampoPorEmpresaPorCampo1($campos[0]->campo2, $_SESSION["id_empresa"], $campos[0]->campo1, $campo1);
		echo "<option value='0'>- Seleccione -</option>";
		foreach ($datos_Campos as $dato) {
			echo "<option value='" . ($dato->valor) . "'>" . ($dato->valor) . "</option>";
		}
	}
	elseif ($seccion == "muestraDatosFiltro3Campo3") {
		$campo2 = $post["elegido"];
		
		$campos = TraeCampos($_SESSION["id_empresa"]);
		//Dado lo datos del campo 1, obtengo datos del campo 2
		$datos_Campos = TraeDistictDadoCampoPorEmpresaPorCampo1($campos[0]->campo3, $_SESSION["id_empresa"], $campos[0]->campo2, $campo2);
		echo "<option value='0'>Seleccione</option>";
		foreach ($datos_Campos as $dato) {
			echo "<option value='" . ($dato->valor) . "'>" . ($dato->valor) . "</option>";
		}
	}
	elseif ($seccion == "indicadores1") {
		$id_empresa = $_SESSION["id_empresa"];
		$DATOS = FiltrosIndicadores(FuncionesTransversalesAdmin(file_get_contents("views/indicadores/index.html")));
		$PRINCIPAL = $DATOS[0];
		$PRINCIPAL = str_replace("{BLOQUE_DATA}", "", $PRINCIPAL);
		$PRINCIPAL = str_replace("{BLOQUE_GRAFICO}", "", $PRINCIPAL);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "muestrasubfamilia") {
		$id_familia = $post["elegido"];
		$subfamilias = TraeSubFamilias($id_familia);
		echo "<option value='-'>Todas</option>";
		
		foreach ($subfamilias as $da) {
			echo "<option value='" . ($da->subfamilia) . "'>" . ($da->subfamilia) . "</option>";
		}
	}
	elseif ($seccion == "procesa_reporte_indicadores") {
		$numero_series = 3;
		for ($i = 1; $i <= $numero_series; $i++) {
			$valores = EjecutaQueryIndicadores($post, $i);
			$arreglo_datos_indicadores[$i]["valores"] = $valores;
		}
		
		$id_empresa = $_SESSION["id_empresa"];
		$DATOS = FiltrosIndicadores(FuncionesTransversalesAdmin(file_get_contents("views/indicadores/index.html")), $post, $numero_series);
		$PRINCIPAL = $DATOS[0];
		$PRINCIPAL = MuestraIndicadores($PRINCIPAL, $valores, $arreglo_datos_indicadores, $numero_series, $DATOS[1], $DATOS[2]);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "nombreindicador") {
		$nombre_indicador = $post["elegido"];

		$indicadores = TraeIndicadoresSelect($nombre_indicador);
		echo "<option value='-'>Todas</option>";
		
		foreach ($indicadores as $da) {
			echo "<option value='" . ($da->nombre_indicador) . "'>" . ($da->nombre_indicador) . "</option>";
		}
	}
	elseif ($seccion == "homeconsolidado") {
		$id_empresa = $_SESSION["id_empresa"];
		$PRINCIPAL = FiltrosReportes(FuncionesTransversalesAdmin(file_get_contents("views/reportes/home_consolidado_solo_filtros.html")), $arreglo_post);
		$PRINCIPAL = str_replace("{FILTROS}", FiltrosReportes(file_get_contents("views/reportes/encabezado_solo_filtros.html"), $arreglo_post, $id_empresa), $PRINCIPAL);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "TNT_reportes") {
		$PRINCIPAL = FiltrosReportes(FuncionesTransversalesAdmin(file_get_contents("views/reportes/tnt_home_reportes_consolidados.html")), $arreglo_post);
		$PRINCIPAL = str_replace("{FILTROS}", FiltrosReportes(file_get_contents("views/reportes/tnt_filtros_superiores.html"), $arreglo_post), $PRINCIPAL);
		$PRINCIPAL = str_replace("{RESULTADOS}", FiltrosReportes(file_get_contents("views/reportes/tnt_resultados_consolidados.html"), $arreglo_post), $PRINCIPAL);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "sk_reportes") {
		$arreglo_post = $post;
		
		$PRINCIPAL = FiltrosReportes(FuncionesTransversalesAdmin(file_get_contents("views/reportes/sk_home_reportes_consolidados.html")), $arreglo_post);
		$PRINCIPAL = str_replace("{FILTROS}", FiltrosReportes(file_get_contents("views/reportes/sk_filtros_superiores.html"), $arreglo_post), $PRINCIPAL);
		$PRINCIPAL = str_replace("{RESULTADOS}", FiltrosReportes(file_get_contents("views/reportes/sk_resultados_consolidados.html"), $arreglo_post), $PRINCIPAL);
		$PRINCIPAL = ConsolidadoTotal($_SESSION["id_empresa"], $PRINCIPAL, $arreglo_post);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "sk_reportes_encsat") {
		$arreglo_post = $post;
		
		$PRINCIPAL = FiltrosReportes(FuncionesTransversalesAdmin(file_get_contents("views/reportes/enc_satisfaccion/sk_enc_sat_home_reportes_consolidados.html")), $arreglo_post);
		$PRINCIPAL = str_replace("{FILTROS}", FiltrosReportes(file_get_contents("views/reportes/enc_satisfaccion/sk_enc_sat_filtros_superiores.html"), $arreglo_post), $PRINCIPAL);
		$PRINCIPAL = str_replace("{RESULTADOS}", FiltrosReportes(file_get_contents("views/reportes/enc_satisfaccion/sk_enc_sat_resultados_consolidados.html"), $arreglo_post), $PRINCIPAL);
		$PRINCIPAL = ConsolidadoTotal($_SESSION["id_empresa"], $PRINCIPAL, $arreglo_post);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "muestra_centro_costo") {
		$id_empresa_holding = $post["elegido"];
		
		$datos = DatosParaCombo1($id_empresa_holding);
		echo "<option value='0'>-Seleccione-</option>";
		foreach ($datos as $da) {
			echo "<option value='" . $da->centro_costo . "'>" . $da->centro_costo . "</option>";
		}
	}
	elseif ($seccion == "muestra_programas_dada_malla") {
		$id_malla = $post["elegido"];
		//Total programas por malla
		$programas = ProgramasDadoMalla($id_malla);
		echo "<option value=''>Todas</option>";
		
		foreach ($programas as $da) {
			echo "<option value='" . ($da->id) . "'>" . ($da->nombre) . "</option>";
		}
	}
	elseif ($seccion == "muestra_cursos_dado_programa") {
		$id_programa = $post["elegido2"];
		$total = CantidadCursosPorPrograma($id_programa);
		echo "<option value=''>Todas</option>";
		
		foreach ($total as $da) {
			echo "<option value='" . ($da->id) . "'>" . ($da->nombre) . "</option>";
		}
	}
	elseif ($seccion == "muestra_unidad_negocio") {
		$centro_costo = $post["elegido2"];
		$datos = DatosParaCombo2($centro_costo);
		
		foreach ($datos as $da) {
			echo "<option value='" . $da->centro_costo . "'>" . $da->unidad_negocio . "</option>";
		}
	}
	elseif ($seccion == "homeconsolidadoobj") {
		$PRINCIPAL = FiltrosReportes(FuncionesTransversalesAdmin(file_get_contents("views/reportes/home_consolidado_solo_filtros.html")), $arreglo_post);
		$PRINCIPAL = str_replace("{FILTROS}", FiltrosReportes(file_get_contents("views/reportes_obj/encabezado_solo_filtros.html"), $arreglo_post), $PRINCIPAL);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "consolidado_dnc") {
		$arreglo_post = $post;
		$PRINCIPAL = MuestraConsolidadoPorCargo(FiltrosReportes(FuncionesTransversalesAdmin(file_get_contents("views/dnc/dnc_vista_cargo_rod.html")), $arreglo_post), $arreglo_post);
		$PRINCIPAL = MuestraConsolidadoPorCursoG(FiltrosReportes(FuncionesTransversalesAdmin($PRINCIPAL), $arreglo_post), $arreglo_post);
		$PRINCIPAL = MuestraConsolidadoPorCursoT(FiltrosReportes(FuncionesTransversalesAdmin($PRINCIPAL), $arreglo_post), $arreglo_post);
		$PRINCIPAL = MuestraConsolidadoPorCurso(FiltrosReportes(FuncionesTransversalesAdmin($PRINCIPAL), $arreglo_post), $arreglo_post);
		$PRINCIPAL = MuestraConsolidadorankingporCursoG(FiltrosReportes(FuncionesTransversalesAdmin($PRINCIPAL), $arreglo_post), $arreglo_post);
		$PRINCIPAL = MuestraReporteCursosVsCargo(FiltrosReportes(FuncionesTransversalesAdmin($PRINCIPAL), $arreglo_post), $arreglo_post);
		$PRINCIPAL = MuestraReporteCursosPorCargo(FiltrosReportes(FuncionesTransversalesAdmin($PRINCIPAL), $arreglo_post), $arreglo_post);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "listmallas") {
		$PRINCIPAL = ListadoMallas(FuncionesTransversalesAdmin(file_get_contents("views/malla/entorno_listado_mallas.html")));
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "Likes_Capsulas") {
		$hoy = date("Y-m-d");
		header("content-type:application/csv;charset=ISO 8859-1");
		header('Content-Disposition: attachment; filename=Capsulas_likes_' . $hoy . '.csv');
		echo "Fecha;Hora;Curso;Modulo;Recomendacion;Rut;Nombre;Cargo;Division;Area\r\n";
		
		$reco = Capsulas_likes_data();
		
		foreach ($reco as $u) {
			$usu = TraeDatosUsuario($u->rut);
			
			$u->Curso = str_replace("<br>", "", $u->Curso);
			$u->Curso = str_replace("/\r|\n/", "", $u->Curso);
			$u->Curso = str_replace(array("\r", "\n"), '', $u->Curso);
			$u->Curso = str_replace(array("\r\n", "\r", "\n"), "", $u->Curso);
			$u->Curso = strip_tags(trim($u->Curso));
			
			$u->modulo = str_replace("<br>", "", $u->modulo);
			$u->modulo = str_replace("/\r|\n/", "", $u->modulo);
			$u->modulo = str_replace(array("\r", "\n"), '', $u->modulo);
			$u->modulo = str_replace(array("\r\n", "\r", "\n"), "", $u->modulo);
			$u->modulo = strip_tags(trim($u->modulo));
			
			
			echo $u->fecha . ";" . $u->hora . ";" . $u->Curso . ";" . $u->modulo . ";" . $u->rating . ";" . $usu[0]->rut . ";" . $usu[0]->nombre_completo . ";" . $usu[0]->cargo . ";" . $usu[0]->division . ";" . $usu[0]->area . ";\r\n";
		}
	}
	elseif ($seccion == "reporte_compra_cartera") {
		$id_objeto = $get["ido"];
		$id_malla = $get["im"];
		header('Content-type: text/plain');
		$fechahoy = date("Y-m-d") . " " . date("H:i:s");
		header('Content-Disposition: attachment; filename=Reporte_' . $id_objeto . '_' . $fechahoy . '.csv');
		
		echo "rut;nombre_completo;cargo;email;division;gerencia;zona;oficina;cui;unidad;Estado;Numero Intentos de Evaluacion;Nota;Fecha;Tipo; \r\n";
		$datos = TotalUsuariosCompraCartera($id_objeto, $id_malla);
		foreach ($datos as $dat) {
			$arreglo_json = "";
			echo $dat->rut . ";";
			echo $dat->nombre_completo . ";";
			echo $dat->cargo . ";";
			echo $dat->email . ";";
			echo $dat->division . ";";
			echo $dat->gerencia . ";";
			echo $dat->zona . ";";
			echo $dat->sucursal . ";";
			echo $dat->cui . ";";
			echo $dat->unidad_negocio . ";";
			
			if ($dat->json) {
				$json_a_enviar = $dat->json;
				$json_a_enviar = str_replace("contenido:", '"contenido":', $json_a_enviar);
				$json_a_enviar = str_replace("complete:", '"complete":', $json_a_enviar);
				$json_a_enviar = str_replace("trailer:", '"trailer":', $json_a_enviar);
				$json_a_enviar = str_replace("intro:", '"intro":', $json_a_enviar);
				$json_a_enviar = str_replace("failedAttempts:", '"failedAttempts":', $json_a_enviar);
				$json_a_enviar = str_replace("chapters:", '"chapters":', $json_a_enviar);
				$json_a_enviar = str_replace("ch1:", '"ch1":', $json_a_enviar);
				$json_a_enviar = str_replace("ch2:", '"ch2":', $json_a_enviar);
				$json_a_enviar = str_replace("ch3:", '"ch3":', $json_a_enviar);
				$json_a_enviar = str_replace("attemptsQuiz:", '"attemptsQuiz":', $json_a_enviar);
				$json_a_enviar = str_replace("currentAttempt:", '"currentAttempt":', $json_a_enviar);
				$json_a_enviar = str_replace("attempts:", '"attempts":', $json_a_enviar);
				$json_a_enviar = str_replace("attemps:", '"attemps":', $json_a_enviar);
				$json_a_enviar = str_replace("nq:", '"nq":', $json_a_enviar);
				$json_a_enviar = str_replace("op:", '"op":', $json_a_enviar);
				$json_a_enviar = str_replace("ic:", '"ic":', $json_a_enviar);
				$json_a_enviar = str_replace("isAprovedd:", '"isAprovedd":', $json_a_enviar);
				$json_a_enviar = str_replace("vi:", '"vi":', $json_a_enviar);
				$json_a_enviar = str_replace("vc:", '"vc":', $json_a_enviar);
				$json_a_enviar = str_replace("v1:", '"v1":', $json_a_enviar);
				$json_a_enviar = str_replace("v2:", '"v2":', $json_a_enviar);
				$json_a_enviar = str_replace("v3:", '"v3":', $json_a_enviar);
				$json_a_enviar = str_replace("bestScore:", '"bestScore":', $json_a_enviar);
				$json_a_enviar = str_replace("score:", '"score":', $json_a_enviar);
				$json_a_enviar = str_replace("id:", '"id":', $json_a_enviar);
				$json_a_enviar = str_replace("attemp:", '"attemp":', $json_a_enviar);
				$json_a_enviar = str_replace("answer:", '"answer":', $json_a_enviar);
				$json_a_enviar = str_replace("isCorrect:", '"isCorrect":', $json_a_enviar);
				
				$json_a_enviar = str_replace("idc:", '"idc":', $json_a_enviar);
				$json_a_enviar = str_replace("ido:", '"ido":', $json_a_enviar);
				$json_a_enviar = str_replace("data:", '"data":', $json_a_enviar);
				$json_a_enviar = str_replace("percentage:", '"percentage":', $json_a_enviar);
				$json_a_enviar = str_replace("q1:", '"q1":', $json_a_enviar);
				$json_a_enviar = str_replace("q2:", '"q2":', $json_a_enviar);
				$json_a_enviar = str_replace("q3:", '"q3":', $json_a_enviar);
				$json_a_enviar = str_replace("q4:", '"q4":', $json_a_enviar);
				$json_a_enviar = str_replace("q5:", '"q5":', $json_a_enviar);
				
				$arreglo_json = json_decode($json_a_enviar);
				
				
				if ($arreglo_json->complete) {
				
					echo "Aprobado;";
					
					echo count($arreglo_json->attemps) . ";";
				}
				else {
					if (count($arreglo_json->attemps) > 0) {
						echo " Reprobado;";
						echo count($arreglo_json->attemps) . ";";
					}
					else {
						echo " En Proceso;";
						echo "0;";
					}
				}

			}
			else {
				echo "No Iniciado;";
				echo "0;";
			}

			echo $arreglo_json->bestScore . ";";
			echo $dat->fecha . ";";
			
			if ($dat->opcional == "1") {
				echo "Opcional;";
			}
			else {
				echo "Obligatorio;";
			}
			
			
			echo "\r\n";
		}
	}
	elseif ($seccion == "reporte2019") {
		header('Content-type: text/plain');
		header('Content-Disposition: attachment; filename=Reporte_Evaluacion_' . $tipo . " " . $txt . '.csv');
		
		echo "evaluado;evaluado_nombre_completo;evaluado_cargo;evaluado_email;evaluado_division;evaluado_gerencia;evaluado_zona;evaluado_dpto;evaluado_oficina;evaluado_region;evaluado_unidad;evaluador;evaluador_nombre_completo;evaluador_cargo;evaluador_email;evaluador_division;evaluador_gerencia;Autoevaluacion;Ev. Ascendente (corresponde evaluacion);Ev. Ascendente (al menos 3 evaluaciones);Ev. Descendente ;Calibracion;Plan;Retro;Informe\r\n";
		$datos = SGD_Reporte2019MT();
		
		foreach ($datos as $dat) {
			echo $dat->evaluado . ";";
			echo $dat->nombre_completo . ";";
			echo $dat->cargo . ";";
			echo $dat->email . ";";
			echo $dat->division . ";";
			echo $dat->gerencia . ";";
			echo $dat->zona . ";";
			echo $dat->departamento . ";";
			echo $dat->oficina . ";";
			echo $dat->region . ";";
			echo $dat->unidad_negocio . ";";
			echo $dat->rut_evaluador . ";";
			echo $dat->nombre_evaluador . ";";
			echo $dat->cargo_evaluador . ";";
			echo $dat->email_evaluador . ";";
			echo $dat->division_evaluador . ";";
			echo $dat->area_evaluador . ";";
			if ($dat->id_ae) {
				echo "SI;";
			}
			else {
				echo "NO;";
			}
			
			
			$id_asc = BuscaAscRel2019Finalizado($dat->evaluado, $dat->rut_evaluador);
			
			if ($dat->total_lo_evaluan_ascendente > 2) {
				echo "si;";
				if ($dat->total_eval_asc_me_han_hecho > 2) {
					echo "si;";
				}
				else {
					echo "no;";
				}
			}
			else {
				echo "no;no corresponde ev;";
			}
			
			if ($dat->me_hicieron_eval_desc) {
				echo "SI;";
			}
			else {
				echo "NO;";
			}
			
			
			if ($dat->registro_validacion_evaluacion == "si") {
				echo "SI;";
			}
			else {
				echo "NO;";
			}
			
			if ($dat->plan_accion_finalizado == "si") {
				echo "SI;";
			}
			else {
				echo "NO;";
			}
			
			if ($dat->retro_realizada == "si") {
				echo "SI;";
			}
			else {
				echo "NO;";
			}
			if ($dat->vio_informe) {
				echo "SI\r\n";
			}
			else {
				echo "NO\r\n";
			}
		}
	}
	elseif ($seccion == "reporteFeed") {
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=Reporte_Seguimiento_SGD_Feedback" . $fechahoy . ".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		$feedback = ReporteFeedback();
		
		?>
		<table id="tabla1" class="table table-bordered table-hover table-condensed">
		<tr>
			<th class="bg_gray" width="20%">RutEvaluado</th>
			<th class="bg_gray" width="20%">Evaluado</th>
			<th class="bg_gray" width="20%">Cargo Evaluado</th>
			<th class="bg_gray" width="20%">Gerencia Evaluado</th>
			<th class="bg_gray" width="20%">RutEvaluador</th>
			<th class="bg_gray" width="10%">Evaluador</th>
			<th class="bg_gray" width="10%">Cargo Evaluador</th>
			<th class="bg_gray" width="10%">Gerencia Evaluador</th>

			<th class="bg_gray" width="10%">fecha_calibracion_realizada</th>
			<th class="bg_gray" width="10%">fecha_liberacion_informe_a_evaluado</th>
			<th class="bg_gray" width="10%">Recibi_Feedback_de_mi_Jefatura</th>
			<th class="bg_gray" width="10%">Nota</th>
			<th class="bg_gray" width="10%">Estas_de_acuerdo_con_tu_evaluacion</th>
			<th class="bg_gray" width="10%">Ingresa_comentarios_sobre_el_Feedback_Recibido</th>
		</tr>
		<?php
		foreach ($feedback as $fee) {
			?>
			<tr>
				<td><?php echo $fee->rut_evaluado; ?></td>
				<td><?php echo $fee->nombre_completo; ?></td>
				<td><?php echo $fee->cargo_evaluado; ?></td>
				<td><?php echo $fee->gerenciaevaluado; ?></td>
				<td><?php echo $fee->rut_evaluador; ?></td>
				<td><?php echo $fee->nombre_evaluador; ?></td>
				<td><?php echo $fee->cargo_evaluador; ?></td>
				<td><?php echo $fee->gerenciaevaluador; ?></td>

				<td><?php echo $fee->fecha_calibracion_realizada; ?></td>
				<td><?php echo $fee->fecha_liberacion_informe_a_evaluado; ?></td>
				<td><?php echo $fee->Recibi_Feedback_de_mi_Jefatura; ?></td>
				<td><?php echo $fee->nota; ?></td>
				<td><?php echo $fee->Estas_de_acuerdo_con_tu_evaluacion; ?></td>
				<td><?php echo $fee->Ingresa_comentarios_sobre_el_Feedback_Recibido; ?></td>


			</tr>
			<?php
		}
	}
	elseif ($seccion == "reporteResultadoActual") {
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=Resultado" . $fechahoy . ".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		$feedback = reporteResultadoActual();
		
		?>
		<table id="tabla1" class="table table-bordered table-hover table-condensed">
		<tr>
			<th class="bg_gray" width="20%">RutEvaluado</th>
			<th class="bg_gray" width="20%">Evaluado</th>
			<th class="bg_gray" width="20%">Cargo Evaluado</th>

			<th class="bg_gray" width="20%">Gerencia Evaluado</th>
			<th class="bg_gray" width="20%">Local Evaluado</th>
			<th class="bg_gray" width="20%">Zonal Evaluado</th>
			<th class="bg_gray" width="20%">Gerente RF Evaluado</th>


			<th class="bg_gray" width="20%">RutEvaluador</th>
			<th class="bg_gray" width="10%">Evaluador</th>
			<th class="bg_gray" width="10%">Cargo Evaluador</th>
			<th class="bg_gray" width="10%">Gerencia Evaluador</th>

			<th class="bg_gray" width="10%">perfilEvaluacion</th>
			<th class="bg_gray" width="10%">NotaSabe</th>
			<th class="bg_gray" width="10%">NotaPuede</th>
			<th class="bg_gray" width="10%">NotaQuiere</th>
			<th class="bg_gray" width="10%">NotaFinal</th>

			<th class="bg_gray" width="10%">notaLider</th>
			<th class="bg_gray" width="10%">p1Lider</th>
			<th class="bg_gray" width="10%">c1lider</th>
			<th class="bg_gray" width="10%">p2Lider</th>
			<th class="bg_gray" width="10%">c2lider</th>
			<th class="bg_gray" width="10%">p3Lider</th>
			<th class="bg_gray" width="10%">c3lider</th>

			<th class="bg_gray" width="10%">notaMiembro</th>
			<th class="bg_gray" width="10%">p1Miembro</th>
			<th class="bg_gray" width="10%">c1Miembror</th>
			<th class="bg_gray" width="10%">p2Miembro</th>
			<th class="bg_gray" width="10%">c2Miembro</th>
			<th class="bg_gray" width="10%">p3Miembro</th>
			<th class="bg_gray" width="10%">c3Miembro</th>

			<th class="bg_gray" width="10%">notaColaborador</th>
			<th class="bg_gray" width="10%">p1Colaborador</th>
			<th class="bg_gray" width="10%">c1Colaborador</th>
			<th class="bg_gray" width="10%">p2Colaborador</th>
			<th class="bg_gray" width="10%">c2Colaborador</th>
			<th class="bg_gray" width="10%">p3Colaborador</th>
			<th class="bg_gray" width="10%">c3Colaborador</th>


			<th class="bg_gray" width="10%">notaProveedor</th>
			<th class="bg_gray" width="10%">p1Proveedor</th>
			<th class="bg_gray" width="10%">c1Proveedor</th>
			<th class="bg_gray" width="10%">p2Proveedor</th>
			<th class="bg_gray" width="10%">c2Proveedor</th>
			<th class="bg_gray" width="10%">p3Proveedor</th>
			<th class="bg_gray" width="10%">c3Proveedor</th>

			<th class="bg_gray" width="10%">notaGestor</th>
			<th class="bg_gray" width="10%">p1Gestor</th>
			<th class="bg_gray" width="10%">c1Gestor</th>
			<th class="bg_gray" width="10%">p2Gestor</th>
			<th class="bg_gray" width="10%">c2Gestor</th>
			<th class="bg_gray" width="10%">p3Gestor</th>
			<th class="bg_gray" width="10%">c3Gestor</th>

			<th class="bg_gray" width="10%">notaServicio</th>
			<th class="bg_gray" width="10%">p1Servicio</th>
			<th class="bg_gray" width="10%">c1Servicio</th>
			<th class="bg_gray" width="10%">p2Servicio</th>
			<th class="bg_gray" width="10%">c2Servicio</th>
			<th class="bg_gray" width="10%">p3Servicio</th>
			<th class="bg_gray" width="10%">c3Servicio</th>

			<th class="bg_gray" width="10%">Comentario Final Evaluacion</th>


		</tr>
		<?php
		foreach ($feedback as $fee) {
			?>
			<tr>
				<td><?php echo $fee->evaluado; ?></td>
				<td><?php echo $fee->nombre_evaluado; ?></td>
				<td><?php echo $fee->cargo_evaluado; ?></td>
				<td><?php echo $fee->gerencia_evaluado; ?></td>

				<td><?php echo $fee->local_evaluado; ?></td>
				<td><?php echo $fee->zonal_evaluado; ?></td>
				<td><?php echo $fee->gerente_rf_evaluado; ?></td>

				<td><?php echo $fee->evaluador; ?></td>
				<td><?php echo $fee->nombre_evaluador; ?></td>
				<td><?php echo $fee->cargo_evaluador; ?></td>


				<td><?php echo $fee->perfil_evaluacion_competencias; ?></td>
				<td><?php echo $fee->notaSabe; ?></td>
				<td><?php echo $fee->notaPuede; ?></td>
				<td><?php echo $fee->notaQuiere; ?></td>
				<td><?php echo $fee->nota_final; ?></td>

				<td><?php echo $fee->nota_lider; ?></td>


				<td><?php echo $fee->p1_lider; ?></td>
				<td><?php echo $fee->cp1_lider; ?></td>
				<td><?php echo $fee->p2_lider; ?></td>
				<td><?php echo $fee->cp2_lider; ?></td>
				<td><?php echo $fee->p3_lider; ?></td>
				<td><?php echo $fee->cp3_lider; ?></td>

				<td><?php echo $fee->nota_miembro; ?></td>
				<td><?php echo $fee->p1_miembro; ?></td>
				<td><?php echo $fee->cp1_miembro; ?></td>
				<td><?php echo $fee->p2_miembro; ?></td>
				<td><?php echo $fee->cp2_miembro; ?></td>
				<td><?php echo $fee->p3_miembro; ?></td>
				<td><?php echo $fee->cp3_miembro; ?></td>


				<td><?php echo $fee->nota_colaborador; ?></td>
				<td><?php echo $fee->p1_colaborador; ?></td>
				<td><?php echo $fee->cp1_colaborador; ?></td>
				<td><?php echo $fee->p2_colaborador; ?></td>
				<td><?php echo $fee->cp2_colaborador; ?></td>
				<td><?php echo $fee->p3_colaborador; ?></td>
				<td><?php echo $fee->cp3_colaborador; ?></td>

				<td><?php echo $fee->nota_proveedor; ?></td>
				<td><?php echo $fee->p1_proveedor; ?></td>
				<td><?php echo $fee->cp1_proveedor; ?></td>
				<td><?php echo $fee->p2_proveedor; ?></td>
				<td><?php echo $fee->cp2_proveedor; ?></td>
				<td><?php echo $fee->p3_proveedor; ?></td>
				<td><?php echo $fee->cp3_proveedor; ?></td>

				<td><?php echo $fee->nota_gestor; ?></td>
				<td><?php echo $fee->p1_gestor; ?></td>
				<td><?php echo $fee->cp1_gestor; ?></td>
				<td><?php echo $fee->p2_gestor; ?></td>
				<td><?php echo $fee->cp2_gestor; ?></td>
				<td><?php echo $fee->p3_gestor; ?></td>
				<td><?php echo $fee->cp3_gestor; ?></td>


				<td><?php echo $fee->nota_servicio; ?></td>
				<td><?php echo $fee->p1_servicio; ?></td>
				<td><?php echo $fee->cp1_servicio; ?></td>
				<td><?php echo $fee->p2_servicio; ?></td>
				<td><?php echo $fee->cp2_servicio; ?></td>
				<td><?php echo $fee->p3_servicio; ?></td>
				<td><?php echo $fee->cp3_servicio; ?></td>


				<td><?php echo $fee->comentario_final_evaluacion; ?></td>


			</tr>
			<?php
		}
	}
	elseif ($seccion == "SGD_ResultadosEvaluadoNew") {
		$id_empresa = $_SESSION["id_empresa"];
		if ($id_empresa == 79) {
			$id_proceso = '114';
		}
		elseif ($id_empresa == 76) {
			$id_proceso = '112';
		}
		else {
			$id_proceso = $id_proceso_sgd;
		}
		$id_proceso_get = Decodear3($get["i"]);
		if ($id_proceso_get) {
			$id_proceso = $id_proceso_get;
		}
		$excel = $get["excel"];
		if ($excel == "1") {
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=SGD_Resultado.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			
			$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/sgd/resultados/" . $id_empresa . "_entorno_listado_excel.html"));
			$total_dimensiones = TraeDimensionesParaProcesoPorPErfilesDeEvaluacion($id_proceso, $id_empresa);
			$sumaDimPunt = 0;
			$Cuentadimpunt = 0;
			if ($id_empresa == 79) {
				$cuentaDime = 1;
			}
			else {
				$cuentaDime = 2;
			}
			$cuentaDime = count($total_dimensiones);
			//Traigo las dimensiones por emoresa
			
			$contadodor_dimensiones = 1;
			foreach ($total_dimensiones as $dimensi) {
				$encabezado_dimensiones .= '<th class="bg_gray">' . ($dimensi->nombre) . '</th>';
				$resultados_dimensiones .= '<td class="miniSt">{DIM' . $contadodor_dimensiones . '}</td>';
				//Traigo las competencias por cada dimension
				$competencias = CompetenciasPorDimension($dimensi->id_dimension);
				foreach ($competencias as $comp) {
					$encabezado_dimensiones .= '<th class="bg_gray">' . ($comp->nombre) . '</th>';
					$resultados_dimensiones .= '<td class="miniSt">{RES_' . $comp->id . '}</td>';
					//SQL para traer los promedios por cada competencia
					$sql_promedios_competencias .= "(select avg(puntaje) from tbl_sgd_respuestas where tbl_sgd_respuestas.id_proceso='" . $id_proceso . "' and  tbl_sgd_respuestas.evaluado=h.evaluado and tbl_sgd_respuestas.evaluador=h.evaluador and tbl_sgd_respuestas.id_proceso=h.id_proceso and tbl_sgd_respuestas.id_competencia='" . $comp->id . "') as res_comp_" . $comp->id . ", ";
				}
				$contadodor_dimensiones++;
			}
			$PRINCIPAL = str_replace("{ROW_ENCABEZADO_DIMENSIONES}", $encabezado_dimensiones, $PRINCIPAL);
			
			//Traigo las dimensiones de los objetivos
			$dimensiones_objetivos = DimensionesPorEmpresaResultados($id_empresa);
			$contador_dimensiones_objetivos = 0;
			foreach ($dimensiones_objetivos as $dimen_obj) {
				$encabezado_dimensiones_objetivos .= '<th class="bg_gray">Puntaje Alcanzado ' . ($dimen_obj->nombre) . '</th>';
				$encabezado_dimensiones_objetivos .= '<th class="bg_gray">Resultado ' . ($dimen_obj->nombre) . '</th>';
			}
			
			$PRINCIPAL = str_replace("{ROW_ENCABEZADO_DIMENSIONES_OBJETIVOS}", $encabezado_dimensiones_objetivos, $PRINCIPAL);
			
			echo CleanHTMLWhiteList($PRINCIPAL);
			exit;
			$PRINCIPAL = SGD_Resultados_EvaluacionNew("", $id_empresa, $id_proceso, $excel);
			$PRINCIPAL = str_replace("{ID_PROCESO}", $id_proceso, $PRINCIPAL);
			$PRINCIPAL = str_replace("{ID_PROCESO_ENCODEADO}", Encodear3($id_proceso), $PRINCIPAL);
		}
		else {
			$PRINCIPAL = SGD_Resultados_EvaluacionNew(FuncionesTransversalesAdmin(file_get_contents("views/sgd/resultados/" . $id_empresa . "_entorno_listado.html")), $id_empresa, $id_proceso, $excel);
			$PRINCIPAL = str_replace("{ID_PROCESO}", $id_proceso, $PRINCIPAL);
			$PRINCIPAL = str_replace("{ID_PROCESO_ENCODEADO}", Encodear3($id_proceso), $PRINCIPAL);
			echo CleanHTMLWhiteList($PRINCIPAL);
			exit;
		}
	}
	elseif ($seccion == "SGD_ResultadosEvaluado") {
		$id_empresa = $_SESSION["id_empresa"];
		if ($id_empresa == 79) {
			$id_proceso = '114';
		}
		else {
			$id_proceso = $id_proceso_sgd;
		}
		$id_proceso_get = Decodear3($get["i"]);
		if ($id_proceso_get) {
			$id_proceso = $id_proceso_get;
		}
		$excel = $get["excel"];
		if ($excel == "1") {
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=SGD_Resultado.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			
			$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/sgd/resultados/" . $id_empresa . "_entorno_listado_excel.html"));
			echo CleanHTMLWhiteList($PRINCIPAL);
			exit;
			$PRINCIPAL = SGD_Resultados_Evaluacion("", $id_empresa, $id_proceso, $excel);
			$PRINCIPAL = str_replace("{ID_PROCESO}", $id_proceso, $PRINCIPAL);
		}
		else {
			$PRINCIPAL = SGD_Resultados_Evaluacion(FuncionesTransversalesAdmin(file_get_contents("views/sgd/resultados/" . $id_empresa . "_entorno_listado.html")), $id_empresa, $id_proceso, $excel);
			$PRINCIPAL = str_replace("{ID_PROCESO}", $id_proceso, $PRINCIPAL);
			echo CleanHTMLWhiteList($PRINCIPAL);
			exit;
		}
	}
	elseif ($seccion == "actualizadatostblusuario") {

		//Traigo los datos de la tabla de usuarios
		$usuarios = DatosTablaUsuarioPorEmpresa2($_SESSION["id_empresa"]);
		?>
		<table border="1">
		<?php
		foreach ($usuarios as $usu) {
			?>

			<tr>
				<td><?php echo $usu->rut; ?></td>
				<td><?php echo $usu->nombre_completo; ?></td>
				<?php //veo si esta en la tabla temporal
					
					$esta_en_tabla_temporal = EstaEnTablaTemporal($usu->rut);
					if ($esta_en_tabla_temporal) {
						?>
						<td>ACTUALIZA</td>
						
						<?php
						//Actualiza los datos
						$gerencia = $esta_en_tabla_temporal[0]->gerencia;
						$local = $esta_en_tabla_temporal[0]->local;
						$zonal = $esta_en_tabla_temporal[0]->zonal;
						$gerente_rf = $esta_en_tabla_temporal[0]->gerente_rf;
						ActualizaDatosTblUsuario($usu->rut, $gerencia, $local, $zonal, $gerente_rf);
						
						?>
						
						<?php
					}
					else {
						?>
						<td>NO</td>
						
						<?php
					}
				
				?>


			</tr>
			
			<?php
		}
	}
	elseif ($seccion == "SGD_CalibracionEvaluado") {
		$arreglo_post = $post;
		$id_proceso = $id_proceso_sgd;
		$id_empresa = $_SESSION["id_empresa"];
		$excel = $get["excel"];
		if ($excel == "1") {
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=SGD_Calibracion.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			
			$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/sgd/calibracion/entorno_listado_excel.html"));
			echo CleanHTMLWhiteList($PRINCIPAL);
			exit;
			$PRINCIPAL = SGD_Resultados_Calibracion("", $id_empresa, $id_proceso, $excel);
			$PRINCIPAL = str_replace("{ID_PROCESO}", $id_proceso, $PRINCIPAL);
		}
		else {
			$PRINCIPAL = SGD_Resultados_Calibracion(FuncionesTransversalesAdmin(file_get_contents("views/sgd/calibracion/entorno_listado.html")), $id_empresa, $id_proceso, $excel, $arreglo_post);
			$PRINCIPAL = str_replace("{ID_PROCESO}", $id_proceso, $PRINCIPAL);
			echo CleanHTMLWhiteList($PRINCIPAL);
			exit;
		}
	}
	elseif ($seccion == "SGD_Matriz_updateData") {
		
		$id_sgd_relaciones = $post["id_sgd_relaciones"];
		$id_solicitud = $post["id_solicitud"];
		
		$evaluado = LimpiaRut($post["NuevoEvaluado"]);
		$evaluador = LimpiaRut($post["NuevoEvaluador"]);
		$validador = LimpiaRut($post["NuevoValidador"]);
		$calibrador = LimpiaRut($post["NuevoCalibrador"]);
		$id_proceso = LimpiaRut($post["id_proceso"]);
		$perfil_evaluacion = ($post["perfil"]);
		
		$evaluador_original = $post["evaluador_original"];
		$validador_original = $post["validador_original"];
		$calibrador_original = $post["calibrador_original"];
		$calibrador_original = $post["calibrador_original"];
		$perfil_original = $post["perfil_original"];
		
		SGD_MATRIZ_updateData($id_solicitud, $id_sgd_relaciones, $evaluado, $evaluador, $validador, $calibrador, $perfil_evaluacion);
		SGD_ActualizaDatosEvaluacionRespuestas($evaluador_original, $evaluado, $evaluador, $id_proceso);
		
		if ($perfil_original != $perfil_evaluacion) {
			//Cambio el perfil
			SGD_ActualizaPerfilEval($evaluado, $evaluador, $id_proceso, $perfil_evaluacion);
			
			///Borro todos los registros del antiguo perfil
			SGD_borra_respuestas_Sesion_finalizado_datos_calculados($evaluado, $evaluador, $id_proceso);
		}
		
		echo "
<script>
alert('Datos Actualizados exitosamente');
location.href='?sw=SGD_Matriz&b=si&re=" . Encodear3($evaluado) . "&i=" . Encodear3($id_proceso) . "';
</script>";
		exit;
	}
	elseif ($seccion == "SGD_Matriz_InsertData") {
		$id_empresa = $_SESSION["id_empresa"];
		
		$id_proceso = $post["id_proceso"];
		$evaluado = LimpiaRut($post["NuevoEvaluado"]);
		$evaluador = LimpiaRut($post["NuevoEvaluador"]);
		$validador = LimpiaRut($post["NuevoValidador"]);
		$calibrador = LimpiaRut($post["NuevoCalibrador"]);
		$perfil = ($post["nuevo_perfil_eval"]);
		$subperfil = ($post["nuevo_sub_perfil_eval"]);
		
		SGD_MATRIZ_InsertDataFull($id_proceso, $evaluado, $evaluador, $validador, $calibrador, $id_empresa, $perfil, $subperfil);
		
		echo "
<script>
alert('Datos Actualizados exitosamente');
location.href='?sw=SGD_Matriz&i=" . Encodear33($id_proceso) . "&b=si';
</script>";
		exit;
	}
	elseif ($seccion == "asignaUsuarioAMallaPrograma") {
		$fechahoy = date("Y-m-d") . " " . date("H:i:s");

		
		$rut = $post["rut"];
		$id_malla = $post["malla_seleccionada"];
		$id_empresa = $_SESSION["id_empresa"];
		$opcional = $post["opcional"];
		$opcional_inscripcion = $post["opcional"];
		$id_programa = $post["id_programa"];
		$hoy = date("Y-m-d");

		
		Ingresa_malla_personaConOpcional($rut, $id_malla, $id_empresa, $opcional, $hoy, $id_programa, $opcional_inscripcion);
		
	}
	elseif ($seccion == "eliminaRelacionMallaUsuario") {
		$rut = Decodear3($get["r"]);
		$id_malla = Decodear3($get["imall"]);
		$id_empresa = $_SESSION["id_empresa"];
		
		BorraUsuarioRelacionMallaUsuario($id_malla, $rut, $id_empresa);
		
		echo "
        <script>

        location.href='?sw=listProgramas';
        </script>";
	}
	elseif ($seccion == "ConsultaUsuarioPRogramaMalla") {
		$fechahoy = date("Y-m-d") . " " . date("H:i:s");
		$id_empresa = $_SESSION["id_empresa"];
		$codigo_programa = $post["codigo_programa"];
		$rut = preg_replace("[\s+]", "", $post["rut"]);
		
		$arreglo_ruts = explode(";", trim($rut));
		
		foreach ($arreglo_ruts as $rut_unico) {
			$rut = limpiaRut($rut_unico);
			
			//Veo si este rut tiene asociada la malla y programa asociada
			$tiene_malla = BuscaUsuarioMallaPrograma($rut, $id_malla, $codigo_programa, $id_empresa);

			$datos_usuario = DatosTablaUsuario($rut);
			
			if (!$datos_usuario) {
				echo '<div class="etiqueta  label-danger">Usuario con rut <b>' . $rut . '</b>, no existe en la base de datos.</div>';
			}
			else {
				if ($tiene_malla) {
					$template = file_get_contents("views/capacitacion/programasbbdd/bloque_existe_usuario_malla.html");
					
					if ($tiene_malla[0]->opcional == 1) {
						$Opcional = "opcional";
					}
					else {
						$Opcional = "obligatoria";
					}
					$template = str_replace("{TEXTO}", '<div class="etiqueta  label-info"><b>' . ($datos_usuario[0]->nombre_completo) . '</b><br>esta inscrito en la malla: ' . ($tiene_malla[0]->nombremalla) . ' (' . $tiene_malla[0]->id_malla . '), ' . $Opcional . '</div>', $template);
					
					$template = str_replace("{OPCIONAL}", $Opcional, $template);
					$template = str_replace("{ID_PROGRAMA}", $codigo_programa, $template);
					$template = str_replace("{ID_PROGRAMA_ENCODEADO}", Encodear3($codigo_programa), $template);
					$template = str_replace("{ID_MALLA}", ($tiene_malla[0]->id_malla), $template);
					$template = str_replace("{ID_MALLA_ENCODEADA}", Encodear3($tiene_malla[0]->id_malla), $template);
					$template = str_replace("{RUT}", ($rut), $template);
					$template = str_replace("{RUT_ENCODEADO}", Encodear3($rut), $template);
					
					$mallas_por_programa = IMPARTCION_TraigoMallasPorProgramaPorEmpresa($codigo_programa, $id_empresa);
					$options_mallas = "";
					foreach ($mallas_por_programa as $malla) {
						$options_mallas .= "<option value='" . $malla->id_malla . "'>" . ($malla->nombre_malla) . "  (" . $malla->id_malla . ")</option>";
					}
					$template = str_replace("{SELECCION_MALLA}", ($options_mallas), $template);
					
					echo $template;
				}
				else {
					$mallas_por_programa = IMPARTCION_TraigoMallasPorProgramaPorEmpresa($codigo_programa, $id_empresa);
					$options_mallas = "";
					foreach ($mallas_por_programa as $malla) {
						$options_mallas .= "<option value='" . $malla->id_malla . "'>" . ($malla->nombre_malla) . "</option>";
					}
					
					$template = file_get_contents("views/capacitacion/programasbbdd/bloque_no_existe_usuario_malla.html");
					$template = str_replace("{TEXTO}", '<div class="etiqueta  label-danger"><b>' . ($datos_usuario[0]->nombre_completo) . '</b><br>NO esta inscrito</div>', $template);
					$template = str_replace("{ID_PROGRAMA}", $codigo_programa, $template);
					$template = str_replace("{ID_PROGRAMA_ENCODEADO}", Encodear3($codigo_programa), $template);
					$template = str_replace("{SELECCION_MALLA}", ($options_mallas), $template);
					
					$template = str_replace("{ID_MALLA}", ($id_malla), $template);
					$template = str_replace("{ID_MALLA_ENCODEADA}", Encodear3($id_malla), $template);
					$template = str_replace("{RUT}", ($rut), $template);
					$template = str_replace("{RUT_ENCODEADO}", Encodear3($rut), $template);
					echo $template;
				}
			}
		}
	}
	elseif ($seccion == "listProgramas") {
		$id_empresa = $_SESSION["id_empresa"];
		$id_programa = $get["id_programa"];
		$id_curso = $get["id_curso"];
		//actualiza LMS Reportes de inscripcion usuario
		ActualizaDatosLmsReportesUsuariosNull();
		$PRINCIPAL = ListadoProgramasBBDD(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/programasbbdd/entorno_listado.html")), $id_empresa, $id_programa, $id_curso);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "listProgramasI") {
		$id_empresa = $_SESSION["id_empresa"];
		$id_programa = $get["id_programa"];
		$id_curso = $get["id_curso"];
		//actualiza LMS Reportes de inscripcion usuario
		ActualizaDatosLmsReportesUsuariosNull();
		$PRINCIPAL = ListadoProgramasBBDDI(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/programasbbddI/entorno_listado.html")), $id_empresa, $id_programa, $id_curso);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "listProgramaCursoAsistenciaExts") {
		$id_empresa = $_SESSION["id_empresa"];
		$id_curso = $get["id_curso"];
		
		
		$PRINCIPAL = ActualizaAsistenciaCursoExt(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/programasbbdd/entorno_listado_cursos_ext.html")), $id_empresa, $id_curso);
		$PRINCIPAL = str_replace("{ID_CURSO}", ($id_curso), $PRINCIPAL);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "listniveles") {
		$PRINCIPAL = ListadoNivelesAdmin(FuncionesTransversalesAdmin(file_get_contents("views/nivel/entorno_listado.html")));
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "histprocesomasivocorreos") {
		$id_proceso = Decodear3($get["i"]);
		
		$PRINCIPAL = ListadoUsuariosProProcesoEnvioCorreo(FuncionesTransversalesAdmin(file_get_contents("views/correos/listado_usuarios_por_proceso.html")), $id_proceso);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "listcursos") {
		$id_empresa = $_SESSION["id_empresa"];
		
		$exportar_a_excel = $post["ex"];
		$pagina = $get["p"];
		if ($exportar_a_excel == "1") {
			$arreglo_post = $post;
			$fechahoy = date("Y-m-d") . " " . date("H:i:s");
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=" . $fechahoy . ".xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			
			$PRINCIPAL = FiltrosListadoCursos(ListadoCursosAdmin(FuncionesTransversalesAdmin(file_get_contents("views/curso/entorno_listado_excel.html")), $arreglo_post, 0, $id_empresa), $arreglo_post);
			echo($PRINCIPAL);
		}
		else {
			$arreglo_post = $post;
			$PRINCIPAL = FiltrosListadoCursos(ListadoCursosAdmin(FuncionesTransversalesAdmin(file_get_contents("views/curso/entorno_listado.html")), $arreglo_post, $pagina, $id_empresa), $arreglo_post, $pagina);
			echo CleanHTMLWhiteList($PRINCIPAL);
			exit;
		}
	}
	elseif ($seccion == "subirmasivousuarios") {
		//PRIMERO QUE RESETEO LA TABLA, CON TODOS LOS QUE ESTAN EN ESTADO 0 DEL MISMO PROCESO
		$id_empresa = $_SESSION["id_empresa"];
		$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/correos/formulario_subida_usuarios.html"));
		
		//Traigo las unidades de negocio
		$campos_distinct = TRaeDistincTablaUsuario($id_empresa, "unidad_negocio");
		foreach ($campos_distinct as $valor) {
			$html_option .= "<option value='" . $valor->valor . "'>" . $valor->valor . "</option>";
		}
		$PRINCIPAL = str_replace("{OPTIONS_UNIDAD_NEGOCIO}", ($html_option), $PRINCIPAL);
		
		$id_proceso = Decodear3($get["i"]);
		if ($id_proceso) {
			$PRINCIPAL = str_replace("{ID_PROCESO_ENCODEADO}", Encodear3($id_proceso), $PRINCIPAL);
			TruncarTablaUsuariosEnvioCorreoPorProceso($id_proceso);
		}
		
		if ($post["idpro"]) {
			$id_proceso = Decodear3($post["idpro"]);
			TruncarTablaUsuariosEnvioCorreoPorProceso($id_proceso);
			$PRINCIPAL = str_replace("{ID_PROCESO_ENCODEADO}", Encodear3($id_proceso), $PRINCIPAL);
			//Hago todo el tema del excel.
			//SECCION PARA SUBIR EXCEL
			//cargamos el archivo al servidor con el mismo nombre
			//solo le agregue el sufijo tmp_ev_
			$archivo = $_FILES['excel_masivo']['name'];
			
			VerificaExtensionFilesAdmin($_FILES["excel_masivo"]);
			
			$tipo = $_FILES['excel_masivo']['type'];
			$destino = "tmp_ev_" . $archivo;
			if (copy($_FILES['excel_masivo']['tmp_name'], $destino)) {
			
			}
			else {
			
			}
			if (file_exists("tmp_ev_" . $archivo)) {
				/** Clases necesarias */
				require_once 'clases/PHPExcel.php';
				require_once 'clases/PHPExcel/Reader/Excel2007.php';
				// Cargando la hoja de cálculo
				$objReader = new PHPExcel_Reader_Excel2007();
				$objPHPExcel = $objReader->load("tmp_ev_" . $archivo);
				$objFecha = new PHPExcel_Shared_Date();
				// Asignar hoja de excel activa
				$objPHPExcel->setActiveSheetIndex(0);
				//conectamos con la base de datos
				// Llenamos el arreglo con los datos  del archivo xlsx
				for ($i = 2; $i <= 15000; $i++) {
					$_DATOS_EXCEL[$i]['rut'] = ($objPHPExcel->getActiveSheet()->getCell('A' . $i)->getCalculatedValue());
					$_DATOS_EXCEL[$i]['email'] = ($objPHPExcel->getActiveSheet()->getCell('B' . $i)->getCalculatedValue());
				}
			} //si por algo no cargo el archivo tmp_ev_
			else {
				echo "Necesitas primero importar el archivo";
			}
			$errores = 0;
			//recorremos el arreglo multidimensional
			//del excel e ir insertandolos en la BD
			$total_insertar = 0;
			$error = 0;
			foreach ($_DATOS_EXCEL as $valor) {
				if (!$valor["rut"]) {
					continue;
				}
				InsertoUsuariosPorProcesoCorreo(LimpiaRut($valor["rut"]), $valor["email"], $_SESSION["id_empresa"], $id_proceso);
				$total_insertar++;
			}
			
			//Aca Muestro el listado
			
			//FIN DE SECCION PARA SUBIR EXCEL
		}
		
		if ($post["unidad_negocio"]) {
			//traigo todos los usuarios con ese criterio
			$usuarios = TRaeTablaUsuarioDadoUnidadNegocio($id_empresa, $post["unidad_negocio"]);
			foreach ($usuarios as $usu) {
				InsertoUsuariosPorProcesoCorreo($usu->rut, $usu->nombre_completo, $usu->cargo, $usu->email, $usu->gerencia, $usu->id_empr, $id_proceso);
				$total_insertar++;
			}
		}
		if ($total_insertar > 0) {
			$entorno_usuarios = file_get_contents("views/correos/entorno_listado_usuarios_por_proceso.html");
			$entorno_usuarios = str_replace("{ID_PROCESO_ENCODEADO}", Encodear3($id_proceso), $entorno_usuarios);
			
			$listado = TraigoUsuariosDadoIdPRoceso($id_proceso);
			$row = "";
			$i = 1;
			foreach ($listado as $unico) {
				$row .= file_get_contents("views/correos/row_listado_usuarios_por_proceso.html");
				$row = str_replace("{NUMERO}", $i, $row);
				$row = str_replace("{RUT}", $unico->rut, $row);
				$row = str_replace("{NOMBRE}", $unico->nombre, $row);
				$row = str_replace("{CARGO}", $unico->cargo, $row);
				$row = str_replace("{GERENCIA}", $unico->gerencia, $row);
				$row = str_replace("{EMAIL}", $unico->email, $row);
			}
			$entorno_usuarios = str_replace("{ROW_LISTADO_USUARIOS_DETALLE}", ($row), $entorno_usuarios);
			$PRINCIPAL = str_replace("{LISTADO_USUARIOS}", $entorno_usuarios, $PRINCIPAL);
		}
		else {
			$PRINCIPAL = str_replace("{LISTADO_USUARIOS}", "", $PRINCIPAL);
		}
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "PreviewCorreoNewsletter") {
		$id_noticia = $post["id_noticia"];
		$fila = $post["fila"];
		$columna = $post["columna"];
		$id_correo = $post["id_correo"];
		//Actualizo o inserto relacion noticia
		$verifico = VerificoNoticiaFilaColumna($id_correo, $fila, $columna);
		if ($verifico) {
			//Actualizo
			ActualizaRelacionNoticiaCorreo($id_correo, $id_noticia, $fila, $columna);
		}
		else {
			//Inserto
			
			InsertaRelacionCorreoNoticia($id_correo, $id_noticia, $fila, $columna);
		}
		
		$detalle_correo = DatosDeCorreoDadoId($id_correo);
		$template_correo = ColocaNoticiasEnTemplateDecorreo(file_get_contents("../front/views/correos/" . $detalle_correo[0]->template . ""), $id_correo);
		echo $template_correo;
	}
	elseif ($seccion == "crearProcesoEnvio") {
		$rut_otec = Decodear3($get["rot"]);
		$id_empresa = $_SESSION["id_empresa"];
		$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/correos/" . $id_empresa . "_formulario_ingresa.html"));
		$PRINCIPAL = str_replace("{OPTIONS_TIPOS_CORREOS}", OptionsGenericoNombreDadoValoresPorEmpresa("tbl_correos", "", "asunto", "id", "order by asunto", $_SESSION["id_empresa"]), $PRINCIPAL);
		$PRINCIPAL = str_replace("{OPTIONS_TIPOS_CORREOS_DESDE_BASE_TBL_CORREOS_TIPO}", OptionsGenericoNombreDadoValoresPorEmpresa("tbl_correos_tipo", "", "tipo", "id", "order by tipo", $_SESSION["id_empresa"]), $PRINCIPAL);
		
		//traigo el listado de template de correos, y los muestro
		$total_correos = DatosDeCorreoDadoIdEmpresa($id_empresa);
		
		foreach ($listado_noticias as $news) {
		}
		foreach ($total_correos as $tot_correo) {
			$html .= "<table border='1' width='100%'>";
			for ($fila = 1; $fila <= $tot_correo->filas; $fila++) {
				$html .= "<tr>";
				for ($columna = 1; $columna <= $tot_correo->columnas; $columna++) {
					//Combobox para las noticias
					
					$listado_noticias = TodasLasNoticiasPublicadasSinSlider($id_empresa);
					$options = "<select onchange='MuestraPreview(this.value, \"" . $fila . "\" , \"" . $columna . "\", \"" . $tot_correo->id . "\");'  name='" . $tot_correo->filas . $tot_correo->columnas . "'>";
					foreach ($listado_noticias as $news) {
						$options .= "<option value='" . $news->id . "'>" . $news->titulo . "</option>";
					}
					$options .= "</select>";
					
					$html .= "<td><br>Sección <b>Fila $fila Columna $columna</b> <br><br><br>" . $options . "<br><br> </td>";
					//$html.="<td>ACA</td>";
				}
				$html .= "</tr>";
			}
			$html .= "</table>";
		}
		$PRINCIPAL = str_replace("{BLOQUE_TEMPLATES}", $html, $PRINCIPAL);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "adprocesenv") {
		$nombre_proceso = ($post["nombre_proceso"]);
		$descripcion_proceso = ($post["descripcion_proceso"]);
		$tipoCorreo = $post["tipoCorreo"];
		$id_empresa = $_SESSION["id_empresa"];
		
		$subject = ($post["subject"]);
		$titulo1 = ($post["titulo1"]);
		$subtitulo1 = ($post["subtitulo1"]);
		$texto1 = ($post["texto1"]);
		$texto2 = ($post["texto2"]);
		$texto3 = ($post["texto3"]);
		$texto4 = ($post["texto4"]);
		$texto_url = ($post["texto_url"]);
		$url = ($post["url"]);
		
		InsertaProcesoEnvio($nombre_proceso, $descripcion_proceso, $tipoCorreo, $id_empresa, $subject, $titulo1, $subtitulo1, $texto1, $texto2, $texto3, $texto4, $texto_url, $url);
		
		echo "
<script>

location.href='?sw=listProcesosCorreos';
</script>";
		
		//SECCION PARA SUBIR EXCEL
		
		//cargamos el archivo al servidor con el mismo nombre
		//solo le agregue el sufijo tmp_ev_
	}
	elseif ($seccion == "adprocesenvusuarios") {
		$nombre_proceso = ($post["nombre_proceso"]);
		$descripcion_proceso = ($post["descripcion_proceso"]);
		$tipoCorreo = $post["tipoCorreo"];
	}
	elseif ($seccion == "listusuco") {
		$arreglo_post = $post;
		
		$pagina = $get["p"];
		
		$arreglo_url = $get["arra"];
		
		$miarray = $get["arra"];
		$array_para_recibir_via_url = stripslashes($miarray);
		$array_para_recibir_via_url = urldecode($array_para_recibir_via_url);
		$matriz_completa = unserialize($array_para_recibir_via_url, ['allowed_classes' => false]);
		if ($matriz_completa) {
			$arreglo_post = $matriz_completa;
		}
		$PRINCIPAL = ListadoUsuarios(FuncionesTransversalesAdmin(file_get_contents("views/basepersonas/entorno_listado_correos.html")), $arreglo_post, $pagina, "correos");
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "procesa_excel_aBK") {
		$campos = TraeCamposPlantilla();
		$CamposEmpresa = TraeCampos($_SESSION["id_empresa"]);
		
		foreach ($campos as $unico) {
			$checkbox = '<div class="checkbox">
<label>
<input name="campo[]" type="checkbox" ' . $unico->checked . ' ' . $unico->disabled . ' value="' . $unico->campo . '"> ' . $unico->nombre . '
</label>
</div>';
			
			if ($unico->tipo == "obligatorio") {
				$checkbox .= '<input name="campo[]" type="hidden" value="' . $unico->campo . '">';
				$obligatorio .= $checkbox;
			}
			
			if ($unico->tipo == "importante") {
				$importante .= $checkbox;
			}
			
			if ($unico->tipo == "opcional") {
				$opcional .= $checkbox;
			}
		}
		
		$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/basepersonas/formulario_subida_masivo.html"));
		$PRINCIPAL = str_replace("{OBLIGATORIOS}", $obligatorio, $PRINCIPAL);
		$PRINCIPAL = str_replace("{IMPORTANTES}", $importante, $PRINCIPAL);
		$PRINCIPAL = str_replace("{OPCIONALES}", $opcional, $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_EMPRESA}", $_SESSION["id_empresa"], $PRINCIPAL);
		$totalusuarios = CuentaUsuarios($_SESSION["id_empresa"]);
		$PRINCIPAL = str_replace("{TAB1}", "active", $PRINCIPAL);
		$PRINCIPAL = str_replace("{CLASTAB1}", "tab-pane active", $PRINCIPAL);
		$PRINCIPAL = str_replace("{TAB2}", "", $PRINCIPAL);
		$PRINCIPAL = str_replace("{CLASTAB2}", "tab-pane", $PRINCIPAL);
		$PRINCIPAL = str_replace("{DISPLAYFORM1}", "display:none;", $PRINCIPAL);
		$PRINCIPAL = str_replace("{DISPLAYFORM2}", "inline", $PRINCIPAL);
		$PRINCIPAL = str_replace("{ENTORNO_PREVIA}", "", $PRINCIPAL);
		$PRINCIPAL = str_replace("{TITULO2}", "Formulario de subida", $PRINCIPAL);
		$PRINCIPAL = str_replace("{TOTALU}", colocarPesos($totalusuarios), $PRINCIPAL);
		$PRINCIPAL = str_replace("{TOT_ACTUALIZAR}", "0", $PRINCIPAL);
		$PRINCIPAL = str_replace("{TOT_INSERTAR}", "0", $PRINCIPAL);
		$PRINCIPAL = str_replace("{TOT_ERRORES}", "0", $PRINCIPAL);
		$PRINCIPAL = str_replace("{ERRORES}", "", $PRINCIPAL);
		$PRINCIPAL = str_replace("{DISPLAYERROR}", "display:none;", $PRINCIPAL);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "procesa_excel_a") {
		$idEmpresa = $_SESSION["id_empresa"];
		
		if (jValidarEmpresaSubidaUsuarios($idEmpresa)) {
			$res = jListarCamposEmpresaSubidaUsuarios(1, $idEmpresa);
			$arr = explode("|", $res);
			$arrObli = explode(",", $arr[0]);
			$arrOpci = explode(",", $arr[1]);
			$camposObli = "";
			$camposOpci = "";
			
			for ($i = 1; $i < count($arrObli); $i++) {
				$camposObli .= "<tr><td>" . $arrObli[$i] . "</td></tr>";
			}
			
			for ($i = 1; $i < count($arrOpci); $i++) {
				$camposOpci .= "<tr><td>" . $arrOpci[$i] . "</td></tr>";
			}
			
			$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/basepersonas/formulario_subida_masivo_new.html"));
			$PRINCIPAL = str_replace("{CAMPOS_OBLI}", $camposObli, $PRINCIPAL);
			$PRINCIPAL = str_replace("{CAMPOS_OPCI}", $camposOpci, $PRINCIPAL);
			$PRINCIPAL = str_replace("{ID_EMPRESA}", $idEmpresa, $PRINCIPAL);
			$totalusuarios = CuentaUsuarios($idEmpresa);
			$PRINCIPAL = str_replace("{TAB1}", "active", $PRINCIPAL);
			$PRINCIPAL = str_replace("{CLASTAB1}", "tab-pane active", $PRINCIPAL);
			$PRINCIPAL = str_replace("{TAB2}", "", $PRINCIPAL);
			$PRINCIPAL = str_replace("{CLASTAB2}", "tab-pane", $PRINCIPAL);
			$PRINCIPAL = str_replace("{DISPLAYFORM1}", "display:none;", $PRINCIPAL);
			$PRINCIPAL = str_replace("{DISPLAYFORM2}", "inline", $PRINCIPAL);
			$PRINCIPAL = str_replace("{ENTORNO_PREVIA}", "", $PRINCIPAL);
			$PRINCIPAL = str_replace("{TITULO2}", "Formulario de subida", $PRINCIPAL);
			$PRINCIPAL = str_replace("{TOTALU}", colocarPesos($totalusuarios), $PRINCIPAL);
			$PRINCIPAL = str_replace("{TOT_ACTUALIZAR}", "0", $PRINCIPAL);
			$PRINCIPAL = str_replace("{TOT_INSERTAR}", "0", $PRINCIPAL);
			$PRINCIPAL = str_replace("{TOT_DESVINCULAR}", "0", $PRINCIPAL);
			$PRINCIPAL = str_replace("{TOT_ERRORES}", "0", $PRINCIPAL);
			$PRINCIPAL = str_replace("{ERRORES}", "", $PRINCIPAL);
			$PRINCIPAL = str_replace("{DISPLAYERROR}", "display:none;", $PRINCIPAL);
		}
		else {
			$campos = TraeCamposPlantilla();
			$CamposEmpresa = TraeCampos($idEmpresa);
			
			foreach ($campos as $unico) {
				$checkbox = '<div class="checkbox">
<label>
<input name="campo[]" type="checkbox" ' . $unico->checked . ' ' . $unico->disabled . ' value="' . $unico->campo . '"> ' . $unico->nombre . '
</label>
</div>';
				
				if ($unico->tipo == "obligatorio") {
					$checkbox .= '<input name="campo[]" type="hidden" value="' . $unico->campo . '">';
					$obligatorio .= $checkbox;
				}
				
				if ($unico->tipo == "importante") {
					$importante .= $checkbox;
				}
				
				if ($unico->tipo == "opcional") {
					$opcional .= $checkbox;
				}
			}
			
			$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/basepersonas/formulario_subida_masivo.html"));
			$PRINCIPAL = str_replace("{OBLIGATORIOS}", $obligatorio, $PRINCIPAL);
			$PRINCIPAL = str_replace("{IMPORTANTES}", $importante, $PRINCIPAL);
			$PRINCIPAL = str_replace("{OPCIONALES}", $opcional, $PRINCIPAL);
			$PRINCIPAL = str_replace("{ID_EMPRESA}", $idEmpresa, $PRINCIPAL);
			$totalusuarios = CuentaUsuarios($idEmpresa);
			$PRINCIPAL = str_replace("{TAB1}", "active", $PRINCIPAL);
			$PRINCIPAL = str_replace("{CLASTAB1}", "tab-pane active", $PRINCIPAL);
			$PRINCIPAL = str_replace("{TAB2}", "", $PRINCIPAL);
			$PRINCIPAL = str_replace("{CLASTAB2}", "tab-pane", $PRINCIPAL);
			$PRINCIPAL = str_replace("{DISPLAYFORM1}", "display:none;", $PRINCIPAL);
			$PRINCIPAL = str_replace("{DISPLAYFORM2}", "inline", $PRINCIPAL);
			$PRINCIPAL = str_replace("{ENTORNO_PREVIA}", "", $PRINCIPAL);
			$PRINCIPAL = str_replace("{TITULO2}", "Formulario de subida", $PRINCIPAL);
			$PRINCIPAL = str_replace("{TOTALU}", colocarPesos($totalusuarios), $PRINCIPAL);
			$PRINCIPAL = str_replace("{TOT_ACTUALIZAR}", "0", $PRINCIPAL);
			$PRINCIPAL = str_replace("{TOT_INSERTAR}", "0", $PRINCIPAL);
			$PRINCIPAL = str_replace("{TOT_ERRORES}", "0", $PRINCIPAL);
			$PRINCIPAL = str_replace("{ERRORES}", "", $PRINCIPAL);
			$PRINCIPAL = str_replace("{DISPLAYERROR}", "display:none;", $PRINCIPAL);
		}
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "generaReporteSinFotos") {
		VerificaFotoPersonalParaReporte($_SESSION["id_empresa"]);
	}
	elseif ($seccion == "dltrelcurmallacla") {
		$id_empresa = $_SESSION["id_empresa"];
		$id_curso = $get["idc"];
		$id_malla = $get["idm"];
		$id_clasificacion = $get["idclas"];
		EliminarelacionMalaCursoclasificacionEmpresa($id_malla, $id_clasificacion, $id_curso, $id_empresa);
		echo "<script>location.href='?sw=vista_mallas_clasificaciones_cursos_objetos';</script>";
	}
	elseif ($seccion == "listusu") {
		
		$id_empresa = $_SESSION["id_empresa"];
		$ejecuta = $get["exe"];
		$id_empresa = $_SESSION["id_empresa"];
		$exportar_a_excel = $get["ex"];
		$rut_colaborador = Decodear3($get["rcol"]);
		
		$num_colab_activos = num_colaboradores_activos($id_empresa);
		$num_usuarios = $num_colab_activos[0]->CuentaUsuarios;
		
		if ($rut_colaborador) {
			echo "<script>location.href='?sw=accionbasepersonas&r=" . Encodear3($rut_colaborador) . "';</script>";
			exit;
		}
		if ($exportar_a_excel == "1") {
			$fechahoy = date("Y-m-d") . " " . date("H:i:s");
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=Tbl_Usuario_" . $fechahoy . ".xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			$PRINCIPAL = ListadoUsuarios(FuncionesTransversalesAdmin(file_get_contents("views/basepersonas/" . $id_empresa . "_entorno_listado_excel.html")), $_SESSION["id_empresa"], "", "", "", "", "", "", "", $exportar_a_excel);
			echo($PRINCIPAL);
		}
		else {
			if ($ejecuta == 1) {
				$PRINCIPAL = ListadoUsuarios(FuncionesTransversalesAdmin(file_get_contents("views/basepersonas/" . $id_empresa . "_entorno_listado.html")), $_SESSION["id_empresa"], "", "", "", "", "", "", "", "", $rut_colaborador);
			}
			elseif ($ejecuta == 2) {
				$PRINCIPAL = ListadoUsuariosRutColaborador(FuncionesTransversalesAdmin(file_get_contents("views/basepersonas/" . $id_empresa . "_entorno_listado.html")), $_SESSION["id_empresa"], $rut_colaborador);
			}
			else {
				$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/basepersonas/" . $id_empresa . "_entorno_listado_previo.html"));
			}
		}
		
		$PRINCIPAL = str_replace("{ID_EMPRESA}", $id_empresa, $PRINCIPAL);
		$PRINCIPAL = str_replace("{TOTAL_USUARIOS_ACTIVOS}", $num_usuarios, $PRINCIPAL);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "listusumallas") {
		$id_empresa = $_SESSION["id_empresa"];
		
		$id_empresa = $_SESSION["id_empresa"];
		$exportar_a_excel = $get["ex"];
		
		if ($exportar_a_excel == "1") {
			$fechahoy = date("Y-m-d") . " " . date("H:i:s");
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=" . $fechahoy . ".xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			$PRINCIPAL = ListadoUsuarios(FuncionesTransversalesAdmin(file_get_contents("views/basepersonas/" . $id_empresa . "_entorno_listado_excel.html")), $_SESSION["id_empresa"], "", "", "", "", "", "", "", $exportar_a_excel);
			echo($PRINCIPAL);
			exit;
		}
		else {

			$PRINCIPAL = ListadoUsuarios(FuncionesTransversalesAdmin(file_get_contents("views/basepersonas/" . $id_empresa . "_entorno_listado.html")), $_SESSION["id_empresa"]);

		}
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "listdncsolicitudes") {
		$id_empresa = $_SESSION["id_empresa"];
		$id_empresa = $_SESSION["id_empresa"];
		$exportar_a_excel = $get["ex"];
		
		if ($exportar_a_excel == "1") {
			$fechahoy = date("Y-m-d") . " " . date("H:i:s");
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=DNC_Solicitudes_Detalle_" . $fechahoy . ".xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			
			$PRINCIPAL = DNCListadoSolicitudesDetalle(FuncionesTransversalesAdmin(file_get_contents("views/dnc_solicitudes/" . $id_empresa . "_entorno_listado_excel.html")), $_SESSION["id_empresa"], $exportar_a_excel);
			echo($PRINCIPAL);
			exit;
		}
		else {

			$PRINCIPAL = DNCListadoSolicitudesDetalle(FuncionesTransversalesAdmin(file_get_contents("views/dnc_solicitudes/" . $id_empresa . "_entorno_listado.html")), $_SESSION["id_empresa"]);

		}
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "estadisticas2") {
		$id_empresa = $_SESSION["id_empresa"];
		$case = $get["case"];
		
		$PRINCIPAL = GeneraEstadisticasGeneralesPorCaseDetalle(FuncionesTransversalesAdmin(file_get_contents("views/estadisticas/entorno_listado.html")), $id_empresa, $case);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "estadisticas1") {
		$arreglo_fechas = explode("-", $post["daterange-btn"]);
		$fecha_inicio = trim(str_replace("/", "-", $arreglo_fechas[0]));
		$fecha_termino = trim(str_replace("/", "-", $arreglo_fechas[1]));
		$id_empresa = $_SESSION["id_empresa"];
		$tipo_estadistica = $post["tipo_estadistica"];
		
		$tipo_est = $get["tipo_est"];
		
		$PRINCIPAL = GeneraEstadisticasGeneralesPorCase(FuncionesTransversalesAdmin(file_get_contents("views/estadisticas/entorno_listado.html")), $id_empresa, $fecha_inicio, $fecha_termino, $tipo_est);
		
		//Traigo los environment
		$case_Estadisticas = Estadisticas_distinct($id_empresa);
		$options_case = "";
		foreach ($case_Estadisticas as $case) {
			$options_case .= "<option value='" . $case->case . "'>" . $case->nombre . "</option>";
		}
		$PRINCIPAL = str_replace("{OPTIONS_ESTADISTICAS}", $options_case, $PRINCIPAL);
		if ($post["daterange-btn"]) {
			$PRINCIPAL = str_replace("{VALOR_RANGO_FECHAS}", $post["daterange-btn"], $PRINCIPAL);
		}
		else {
			$PRINCIPAL = str_replace("{VALOR_RANGO_FECHAS}", "", $PRINCIPAL);
		}
		
		$estadisticas = ColocaEstadisticas($id_empresa);
		
		$PRINCIPAL = str_replace("{ROW_LISTADO_ESTADISTICAS2}", $estadisticas, $PRINCIPAL);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "listparticipantes") {
		$PRINCIPAL = ListaParticipantes(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/participantes/entorno_listado.html")), $_SESSION["id_empresa"], $get["r"], $get["t"], $get["p"], $get["d"], $get["c1"], $get["c2"], $get["c3"], $get["c4"]);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "excelEstadisticas") {
		$tipo = $get["tipo"];
		$id_empresa = $_SESSION["id_empresa"];
		$listado = Estadisticas_Query($id_empresa, $tipo);
		$fechahoy = date("Y-m-d") . " " . date("H:i:s");
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=" . $fechahoy . ".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		?>
		<table>
		<tr>
			<td>RUT</td>
			<td>Nombre</td>
			<td>Cargo</td>
			<td>Email</td>
			<td>Campo1</td>
			<td>Campo2</td>
			<td>Campo3</td>
			<td>Cantidad de Visitas</td>
		</tr>
		<?php
		
		foreach ($listado as $list) {
			?>
			<tr>
			<td><?php echo $list->rut_completo; ?></td>
			<td><?php echo $list->nombre_completo; ?></td>
			<td><?php echo $list->cargo; ?></td>
			<td><?php echo $list->email; ?></td>
			<td><?php echo $list->campo1; ?></td>
			<td><?php echo $list->campo2; ?></td>
			<td><?php echo $list->campo3; ?></td>
			<td><?php echo $list->total; ?></td>
			
			<?php
		}
	}
	elseif ($seccion == "listusuSiga") {
		$arreglo_post = $post;
		$exportar_a_excel = $post["ex"];
		$pagina = $get["p"];
		$miarray = $get["arra"];
		$array_para_recibir_via_url = stripslashes($miarray);
		$array_para_recibir_via_url = urldecode($array_para_recibir_via_url);
		$matriz_completa = unserialize($array_para_recibir_via_url, ['allowed_classes' => false]);
		if ($matriz_completa) {
			$arreglo_post = $matriz_completa;
		}
		$id_empresa = $_SESSION["id_empresa"];
		
		if ($exportar_a_excel == "1") {
			$fechahoy = date("Y-m-d") . " " . date("H:i:s");
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=" . $fechahoy . ".xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			$PRINCIPAL = ListadoUsuarios(FuncionesTransversalesAdmin(file_get_contents("views/basepersonas/siga/entorno_listado_excel.html")), $arreglo_post, $pagina);
		}
		else {

			
			$PRINCIPAL = FiltrosReportes(ListadoUsuariosSiga(FuncionesTransversalesAdmin(file_get_contents("views/basepersonas/siga/entorno_listado.html")), $arreglo_post, $pagina, "usuarios"), $arreglo_post, $id_empresa);
		}
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "listusuauto") {
		$arreglo_post = $post;
		$exportar_a_excel = $post["ex"];
		$pagina = $get["p"];
		$miarray = $get["arra"];
		$array_para_recibir_via_url = stripslashes($miarray);
		$array_para_recibir_via_url = urldecode($array_para_recibir_via_url);
		$matriz_completa = unserialize($array_para_recibir_via_url, ['allowed_classes' => false]);
		if ($matriz_completa) {
			$arreglo_post = $matriz_completa;
		}
		$id_empresa = $_SESSION["id_empresa"];
		
		$PRINCIPAL = FiltrosListadoCursos(ListadoUsuariosAutoInscritos(FuncionesTransversalesAdmin(file_get_contents("views/basepersonas/entorno_listado_auto_inscritos.html")), $arreglo_post, $pagina, "usuarios"), $arreglo_post);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "SaveaddProcesosPorAn") {
		$tipo_proceso_evaluacion = $post["tipo_proceso_evaluacion"];
		$id_proceso_anual = Decodear3($post["ipa"]);
		$nombre_proceso = ($post["nombre_proceso"]);
		$descripcion_proceso = ($post["descripcion_proceso"]);
		$arreglo_fechas = explode("-", $post["fechas"]);
		$fecha_inicio = trim(str_replace("/", "-", $arreglo_fechas[0]));
		$fecha_termino = trim(str_replace("/", "-", $arreglo_fechas[1]));
		$id_empresa = $_SESSION["id_empresa"];
		$datos_proceso_anual = DatosProcesoAnualPorIdAnual($id_proceso_anual);
		//traigo el id del proceso anterior
		$ultimo_id = TraeUltimoIdProcesoPorEmpresa($id_empresa);
		$nuevo_id_proceso_evaluacion = $ultimo_id[0]->ultimo_id + 1;
		InsertarProcesoEvalPorProcesoAnual($nombre_proceso, $fecha_inicio, $fecha_termino, $id_empresa, $id_proceso_anual, $nuevo_id_proceso_evaluacion, $tipo_proceso_evaluacion, "SGD " . $datos_proceso_anual[0]->ano, "1", "sgd2", "1", "1", "Frecuencia de notas", "#EA312C", "#EA312C", "", "", "", $descripcion_proceso);
		if ($id_empresa == 28) {
			InsertaEtapaPorProcesoChilquinta("Autoevaluación", $nuevo_id_proceso_evaluacion, $id_empresa, "1", '<span class="fa fa-circle-thin text-iconos fs14 mr10"></span>', 'fa fa-circle-thin text-iconos', "");
			InsertaEtapaPorProcesoChilquinta("Evaluación de Equipo", $nuevo_id_proceso_evaluacion, $id_empresa, "2", '<span class="fa fa-circle-thin text-iconos fs14 mr10"></span>', 'fa fa-circle-thin text-iconos', "1");
			InsertaEtapaPorProcesoChilquinta("Informes Liberados", $nuevo_id_proceso_evaluacion, $id_empresa, "3", '<span class="fa fa-circle-thin text-iconos fs14 mr10"></span>', 'fa fa-circle-thin text-iconos', "");
			InsertaEtapaPorProcesoChilquinta("Feedback", $nuevo_id_proceso_evaluacion, $id_empresa, "4", '<span class="fa fa-circle-thin text-iconos fs14 mr10"></span>', 'fa fa-circle-thin text-iconos', "");
		}
		
		if ($id_empresa == 28) {
			//Si es empresa chilquinta, aca se crea el proceso de Calibracion, y el proceso de planes de accion
			//Seccion para ingresar proceso del plan
			InsertarProcesoEvalPorProcesoAnual("Planificacion de Actividades", $fecha_inicio, $fecha_termino, $id_empresa, $id_proceso_anual, $nuevo_id_proceso_evaluacion + 1, "3", "", "0", "homePlanes", "3", "", "", "", "", $nuevo_id_proceso_evaluacion);
			InsertaEtapaPorProcesoChilquinta("Ingreso de Planes", $nuevo_id_proceso_evaluacion + 1, $id_empresa, "1", '<span class="fa fa-circle-thin text-iconos fs14 mr10"></span>', 'fa fa-circle-thin text-iconos', "");
			InsertaEtapaPorProcesoChilquinta("Validación de Planes", $nuevo_id_proceso_evaluacion + 1, $id_empresa, "2", '<span class="fa fa-circle-thin text-iconos fs14 mr10"></span>', 'fa fa-circle-thin text-iconos', "");
			
			//Seccion para ingresar proceso de Calibracion
			InsertarProcesoEvalPorProcesoAnual("Calibracion", $fecha_inicio, $fecha_termino, $id_empresa, $id_proceso_anual, $nuevo_id_proceso_evaluacion + 2, "5", "Calibracion", "0", "calibracion", "2", "0", "", "", "", "", $nuevo_id_proceso_evaluacion, "si");
			InsertaEtapaPorProcesoChilquinta("Calibración", $nuevo_id_proceso_evaluacion + 2, $id_empresa, "1", '<span class="fa fa-circle-thin text-iconos fs14 mr10"></span>', 'fa fa-circle-thin text-iconos', "");
		}
		
		echo "<script>location.href='?sw=addProcesosPorAn&i=" . Encodear3($id_proceso_anual) . "';</script>";
	}
	elseif ($seccion == "addProcesosPorAn") {
		$id_empresa = $_SESSION["id_empresa"];
		$id_proceso_anual = Decodear3($get["i"]);
		$PRINCIPAL = ColocaDatosProcesoAnual(FuncionesTransversalesAdmin(file_get_contents("views/procesos/" . $id_empresa . "_formulario_agrega_procesos_por_ano.html")), $id_proceso_anual, $id_empresa);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "ActualizaDatosProcesoAnual") {
		$id_proceso = $post["id_proceso"];
		$nombre = ($post["nombre"]);
		$descripcion = ($post["descripcion"]);
		$fecha_inicio = ($post["fecha_inicio"]);
		$fecha_termino = ($post["fecha_termino"]);
		//Actualizo los datos del proceso
		//ActualizaDatosProcesoEvaluacion($id_proceso, $nombre, $descripcion_proceso, $fecha_inicio_proceso, $fecha_termino_proceso);
		ActualizaDatosProcesoAnual($id_proceso, $nombre, $descripcion, $fecha_inicio, $fecha_termino);
		echo '<div class="etiqueta  label-success">Datos Actualizados</div>';
	}
	elseif ($seccion == "ActualizaDatosProcesoEvaluacion") {
		$id_proceso = $post["id_proceso"];
		$nombre = ($post["nombre"]);
		$descripcion_proceso = ($post["descripcion_proceso"]);
		$fecha_inicio_proceso = ($post["fecha_inicio_proceso"]);
		$fecha_termino_proceso = ($post["fecha_termino_proceso"]);
		//Actualizo los datos del proceso
		ActualizaDatosProcesoEvaluacion($id_proceso, $nombre, $descripcion_proceso, $fecha_inicio_proceso, $fecha_termino_proceso);
		echo '<div class="etiqueta  label-success">Datos Actualizados</div>';
	}
	elseif ($seccion == "GeneraReporteBitacora") {
		EncabezadoExcel("SabanaBitacora");
		$ano = $get["agn"];
		$PRINCIPAL = GeneraBitacora($_SESSION["id_empresa"], $ano);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "GeneraReportePlanes") {
		EncabezadoExcel("SabanaPlanes");
		$ano = $get["agn"];
		$PRINCIPAL = GeneraBitacora($_SESSION["id_empresa"], $ano);
		$PRINCIPAL = GeneraPlanesDeAccion(FuncionesTransversalesAdmin(file_get_contents("views/procesos/reportes/resultadosVersion2/planes/entorno_listado_relaciones_excel.html")), $arreglo_post, $pagina, "usuarios", 1, "", 1);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "openEval") {
		$id = Decodear3($get["i"]);
		$id_proceso = Decodear3($get["ip"]);
		EliminaRegistroEvaluadoEvaluadorFinalizado($id);
		echo "
<script>
location.href='?sw=veRelacionesPorPoceso&i=" . Encodear3($id_proceso) . "';
</script>";
	}
	elseif ($seccion == "ProcesoActivoAnual") {
		$id_proceso_activo = Decodear3($post["proceso_activo"]);
		$id_empresa = $_SESSION["id_empresa"];
		
		$datos_procesos_anuales = ProcesosAnualesPorEmpresa($id_empresa);
		
		foreach ($datos_procesos_anuales as $proc_anual) {
			//por cada proceso, veo si esta desativado o no
			if ($post["proceso_desactivado_" . $proc_anual->id] == "on") {
				//si ya esta desactivado, lo desactivo
				$datos_proceso = DatosProcesoAnualPorIdAnual($proc_anual->id);
				if ($datos_proceso[0]->desactivado == "1") {
					ActualizanActivaPorProcesoAnual($proc_anual->id, $id_empresa);
				}
				else {
					ActualizanDesactivadoPorProcesoAnual($proc_anual->id, $id_empresa);
				}
			}
		}
		
		ActualizoProcesoAnualActivo($id_proceso_activo, $id_empresa);
		
		//Traigo los procesos anuales por empresa
		
		echo "
<script>
location.href='?sw=ProcAnual';
</script>";
	}
	elseif ($seccion == "GuardaDatosHomeSGD") {
		$id_empresa = $_SESSION["id_empresa"];
		$titulo = ($post["titulo"]);
		$introduccion = ($post["introduccion"]);
		$competencias_transversales = ($post["competencias_transversales"]);
		$competencias_liderazgo = ($post["competencias_liderazgo"]);
		ActualizarDatosHomeSGD($id_empresa, $titulo, $introduccion, $competencias_transversales, $competencias_liderazgo);
		echo "
<script>
location.href='?sw=ContenidoInicial';
</script>";
	}
	elseif ($seccion == "dltTipsSGD") {
		$id = Decodear3($get["i"]);
		EliminaBloqueTips($id);
		
		echo "
<script>
location.href='?sw=ContenidosTips';
</script>";
	}
	elseif ($seccion == "dltImportanteSGD") {
		$id = Decodear3($get["i"]);
		EliminaBloqueImportante($id);
		
		echo "
<script>
location.href='?sw=ContenidosImportante';
</script>";
	}
	elseif ($seccion == "AddDatoTip") {
		$texto = ($post["texto"]);
		InsertaTextoTips($_SESSION["id_empresa"], $texto);
		echo "
<script>
location.href='?sw=ContenidosTips';
</script>";
	}
	elseif ($seccion == "AddDatoImportante") {
		$texto = ($post["texto"]);
		InsertaTextoImportante($_SESSION["id_empresa"], $texto);
		echo "
<script>
location.href='?sw=ContenidosImportante';
</script>";
	}
	elseif ($seccion == "addTips") {
		$id_empresa = $_SESSION["id_empresa"];
		$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/contenidos/tips/" . $_SESSION["id_empresa"] . "_form.html"));
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "addImportante") {
		$id_empresa = $_SESSION["id_empresa"];
		$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/contenidos/importante/" . $_SESSION["id_empresa"] . "_form.html"));
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "ContenidoInicial") {
		$id_empresa = $_SESSION["id_empresa"];
		$PRINCIPAL = BloqueContenidosHomeInicial(FuncionesTransversalesAdmin(file_get_contents("views/contenidos/" . $id_empresa . "_index.html")), $id_empresa);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "ContenidosImportante") {
		$id_empresa = $_SESSION["id_empresa"];
		$PRINCIPAL = BloqueContenidosImportante(FuncionesTransversalesAdmin(file_get_contents("views/contenidos/importante/" . $id_empresa . "_index.html")), $id_empresa);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "ContenidosTips") {
		$id_empresa = $_SESSION["id_empresa"];
		$PRINCIPAL = BloqueContenidosTips(FuncionesTransversalesAdmin(file_get_contents("views/contenidos/tips/" . $id_empresa . "_index.html")), $id_empresa);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "ProcAnual") {
		$arreglo_post = $post;
		$exportar_a_excel = $post["ex"];
		$pagina = $get["p"];
		$miarray = $get["arra"];
		$array_para_recibir_via_url = stripslashes($miarray);
		$array_para_recibir_via_url = urldecode($array_para_recibir_via_url);
		$matriz_completa = unserialize($array_para_recibir_via_url, ['allowed_classes' => false]);
		if ($matriz_completa) {
			$arreglo_post = $matriz_completa;
		}
		$id_empresa = $_SESSION["id_empresa"];
		$PRINCIPAL = ListadoProcesosAnuales(FuncionesTransversalesAdmin(file_get_contents("views/procesos/" . $id_empresa . "_entorno_listado.html")), $arreglo_post, $pagina, "usuarios");
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
	elseif ($seccion == "RankingPorCompetencias") {
		$grafico = GraficoPorCompetenciasPorEvaluadorCalibrador("", 1, "", "", "admin");
		
		echo "div grafico1";
		echo $grafico;
		echo "grafico ranking competencias";
	}
	elseif ($seccion == "ResumenEvaluadoEvaluadoresPorCriterio") {
		$id_proceso = $get["i"];
		$campo_criterio = $get["campo_criterio"];
		
		$tabla = ListadoRelacionesEvaluadoEvaluadorProProceso(FuncionesTransversalesAdmin(file_get_contents("views/procesos/reportes/tabla_superior_resumen_evaluado_evaluador.html")), $arreglo_post, $pagina, "usuarios", $id_proceso, $rut_evaluador, $exportar_a_excel, $campo_criterio, "", "");
		$tabla = str_replace("{ENCABEZADO_DINAMICO}", MuestraEncabezadoPorProceso($id_proceso), $tabla);
		$tabla = str_replace("{CRITERIO}", $campo_criterio, $tabla);
		echo $tabla;
	}
	elseif ($seccion == "veRelacionesPorPoceso") {
		$id_empresa = $_SESSION["id_empresa"];
		$datos_empresa = DatosEmpresa($id_empresa);
		$id_proceso = Decodear3($get["i"]);
		$rut_evaluador = Decodear3($get["revr"]);
		
		$criterio = Decodear3($get["cri"]);
		$valor_criterio = Decodear3($get["valcri"]);
		
		$arreglo_post = $post;
		$exportar_a_excel = $get["ex"];
		$pagina = $get["p"];
		$miarray = $get["arra"];
		$array_para_recibir_via_url = stripslashes($miarray);
		$array_para_recibir_via_url = urldecode($array_para_recibir_via_url);
		$matriz_completa = unserialize($array_para_recibir_via_url, ['allowed_classes' => false]);
		if ($matriz_completa) {
			$arreglo_post = $matriz_completa;
		}
		
		if ($exportar_a_excel == 1) {
			EncabezadoExcel("ReporteEvaluadoEvaluador");
			$PRINCIPAL = ListadoRelacionesEvaluadoEvaluadorProProceso(FuncionesTransversalesAdmin(file_get_contents("views/procesos/reportes/entorno_listado_relaciones_proceso_excel.html")), $arreglo_post, $pagina, "usuarios", $id_proceso, $rut_evaluador, $exportar_a_excel);
		}
		else {
			$PRINCIPAL = ListadoRelacionesEvaluadoEvaluadorProProceso(FuncionesTransversalesAdmin(file_get_contents("views/procesos/reportes/entorno_listado_relaciones_proceso.html")), $arreglo_post, $pagina, "usuarios", $id_proceso, $rut_evaluador, $exportar_a_excel, "", $criterio, $valor_criterio);
		}
		
		echo CleanHTMLWhiteList($PRINCIPAL);exit;
		?>
		<script type="text/javascript">
			$(window).load(function () {
				$("#capa").load('?sw=ResumenEvaluadoEvaluadoresPorCriterio&i=<?php echo $id_proceso; ?>&campo_criterio=<?php echo $datos_empresa[0]->campo1; ?>
				');
			});
		</script>

		<script type="text/javascript">
			$(window).load(function () {
				$("#capa2").load('?sw=ResumenEvaluadoEvaluadoresPorCriterio&i=<?php echo $id_proceso; ?>&campo_criterio=<?php echo $datos_empresa[0]->campo2; ?>
				');
			});
		</script>

		<script type="text/javascript">
			$(window).load(function () {
				$("#capa3").load('?sw=ResumenEvaluadoEvaluadoresPorCriterio&i=<?php echo $id_proceso; ?>&campo_criterio=<?php echo $datos_empresa[0]->campo3; ?>
				');
			});
		</script>
	
	<?php
		} else if ($seccion == "ReporteDetalleEstado") {
		$id_empresa = $_SESSION["id_empresa"];
		$datos_empresa = DatosEmpresa($id_empresa);
		$id_proceso = Decodear3($get["i"]);
		
		$exportar_a_excel = $get["ex"];
		
		if ($exportar_a_excel == 1) {
			EncabezadoExcel("ReporteAvanceEstados");
			$PRINCIPAL = ListadoDetalleEstados(FuncionesTransversalesAdmin(file_get_contents("views/procesos/reportes/28_entorno_listado_relaciones_estados_excel.html")), $arreglo_post, $pagina, "usuarios", $id_proceso, $rut_evaluador, $exportar_a_excel, "", $criterio, $valor_criterio);
		}
		else {
			$PRINCIPAL = ListadoDetalleEstados(FuncionesTransversalesAdmin(file_get_contents("views/procesos/reportes/28_entorno_listado_relaciones_estados.html")), $arreglo_post, $pagina, "usuarios", $id_proceso, $rut_evaluador, $exportar_a_excel, "", $criterio, $valor_criterio);
		}
		
		$PRINCIPAL = str_replace("{ID_PROCESO_ENCODEADO}", Encodear3($id_proceso), $PRINCIPAL);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
		else if ($seccion == "ResumenEvaluadoresPorCriterio") {
		$id_proceso = $get["idproceso"];
		$campo_criterio = $get["campo_criterio"];
		
		$tabla = ListadoEvaluadoresPorProceso(FuncionesTransversalesAdmin(file_get_contents("views/procesos/reportes/tabla_superior_resumen.html")), $arreglo_post, $pagina, "usuarios", $id_proceso, "", $campo_criterio, "", "");
		$tabla = str_replace("{ENCABEZADO_DINAMICO}", MuestraEncabezadoPorProceso($id_proceso), $tabla);
		$tabla = str_replace("{CRITERIO}", $campo_criterio, $tabla);
		echo $tabla;
	}
		else if ($seccion == "ResumenEvaluadoresPorCriterioFijacion") {
		$id_proceso = $get["idproceso"];
		$campo_criterio = $get["campo_criterio"];
		
		$tabla = ListadoEvaluadoresPorProcesoFijacion(FuncionesTransversalesAdmin(file_get_contents("views/procesos/reportes/" . $_SESSION["id_empresa"] . "_tabla_superior_resumen_fijacion.html")), $arreglo_post, $pagina, "usuarios", $id_proceso, $exportar_a_excel, "", $criterio, $valor_criterio, $_SESSION["id_empresa"], $campo_criterio);
		
		$tabla = str_replace("{ENCABEZADO_DINAMICO}", MuestraEncabezadoPorProceso($id_proceso), $tabla);
		$tabla = str_replace("{CRITERIO}", $campo_criterio, $tabla);
		echo $tabla;
	}
		else if ($seccion == "veEvaluadorPorProcesoResultados") {
		$id_empresa = $_SESSION["id_empresa"];
		$id_proceso = Decodear3($get["i"]);
		$criterio = Decodear3($get["cri"]);
		$valor_criterio = Decodear3($get["valcri"]);
		$arreglo_post = $post;
		$exportar_a_excel = $get["ex"];
		$pagina = $get["p"];
		$miarray = $get["arra"];
		$array_para_recibir_via_url = stripslashes($miarray);
		$array_para_recibir_via_url = urldecode($array_para_recibir_via_url);
		$matriz_completa = unserialize($array_para_recibir_via_url, ['allowed_classes' => false]);
		if ($matriz_completa) {
			$arreglo_post = $matriz_completa;
		}
		
		if ($exportar_a_excel == 1) {
			$PRINCIPAL = ListadoEvaluadoresPorProcesoResultados(FuncionesTransversalesAdmin(file_get_contents("views/procesos/reportes/resultados/entorno_listado_evaluadores_excel.html")), $arreglo_post, $pagina, "usuarios", $id_proceso, $exportar_a_excel);
			echo CleanHTMLWhiteList($PRINCIPAL);
			exit;
			exit;
		}
		else {
			$PRINCIPAL = ListadoEvaluadoresPorProcesoResultados(FuncionesTransversalesAdmin(file_get_contents("views/procesos/reportes/resultados/entorno_listado_evaluadores.html")), $arreglo_post, $pagina, "usuarios", $id_proceso, $exportar_a_excel, "", $criterio, $valor_criterio);
		}
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
		else if ($seccion == "veRelacionesPorPocesoResultados") {
		$id_empresa = $_SESSION["id_empresa"];
		$id_proceso = Decodear3($get["i"]);
		$rut_evaluador = Decodear3($get["revr"]);
		$resumen = $get["resumen"];
		$filtro1 = $post["filtro1"];
		$filtro2 = $post["filtro2"];
		$criterio = Decodear3($get["cri"]);
		$valor_criterio = Decodear3($get["valcri"]);
		
		$tipo_reporte = $post["tipo_reporte"];
		
		$arreglo_post = $post;
		$exportar_a_excel = $get["ex"];
		$pagina = $get["p"];
		$miarray = $get["arra"];
		$array_para_recibir_via_url = stripslashes($miarray);
		$array_para_recibir_via_url = urldecode($array_para_recibir_via_url);
		$matriz_completa = unserialize($array_para_recibir_via_url, ['allowed_classes' => false]);
		if ($matriz_completa) {
			$arreglo_post = $matriz_completa;
		}
		
		if ($exportar_a_excel == 1) {
			if ($resumen == 1) {
				EncabezadoExcel("ReporteAvanceResumen");
				$PRINCIPAL = ListadoRelacionesEvaluadoEvaluadorProProcesoResultados(FuncionesTransversalesAdmin(file_get_contents("views/procesos/reportes/resultadosVersion2/entorno_listado_relaciones_resumen_excel.html")), $arreglo_post, $pagina, "usuarios", $id_proceso, $rut_evaluador, $exportar_a_excel, "", "", "", "", "", "", $resumen);
			}
			else {
				EncabezadoExcel("SabanaResultados");
				$PRINCIPAL = ListadoRelacionesEvaluadoEvaluadorProProcesoResultados(FuncionesTransversalesAdmin(file_get_contents("views/procesos/reportes/resultadosVersion2/" . $_SESSION["id_empresa"] . "_entorno_listado_relaciones_excel.html")), $arreglo_post, $pagina, "usuarios", $id_proceso, $rut_evaluador, $exportar_a_excel);
			}
			
			echo CleanHTMLWhiteList($PRINCIPAL);
			exit;
			exit;
		}
		else {
			if ($tipo_reporte) {
				$PRINCIPAL = FiltrosReportesAvancesSgd(ListadoRelacionesEvaluadoEvaluadorProProcesoResultados(FuncionesTransversalesAdmin(ColocaFiltros3Campos(file_get_contents("views/procesos/reportes/resultadosVersion2/" . $id_empresa . "_entorno_listado_relaciones.html"), $id_empresa, $arreglo_post)), $arreglo_post, $pagina, "usuarios", $id_proceso, $rut_evaluador, $exportar_a_excel, "", $criterio, $valor_criterio, $tipo_reporte, $filtro1, $filtro2), $arreglo_post, $id_empresa);
			}
			else {
				$PRINCIPAL = FiltrosReportesAvancesSgd(FuncionesTransversalesAdmin(ColocaFiltros3Campos(file_get_contents("views/procesos/reportes/resultadosVersion2/" . $id_empresa . "_entorno_listado_relaciones_sin_datos.html"), $id_empresa, $arreglo_post)), $arreglo_post, $id_empresa);
				$PRINCIPAL = str_replace("{ID_PROCESO_ENCODEADO}", Encodear3($id_proceso), $PRINCIPAL);
				$PRINCIPAL = str_replace("{ID_PROCESO}", Encodear3($id_proceso), $PRINCIPAL);
				echo CleanHTMLWhiteList($PRINCIPAL);
				exit;
				exit;
			}
			
			$PRINCIPAL = str_replace("{ID_PROCESO_ENCODEADO}", Encodear3($id_proceso), $PRINCIPAL);
			$PRINCIPAL = str_replace("{ID_PROCESO}", Encodear3($id_proceso), $PRINCIPAL);
		}
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
		exit;
	?>
		<script type="text/javascript">
			$(window).load(function () {
				$("#grafico1").load('?sw=RankingPorCompetencias&i=<?php echo $id_proceso; ?>&campo_criterio = cargo');
			});
		</script>

		<script type="text/javascript">
			$(window).load(function () {
				$("#capa").load('?sw=ResumenEvaluadoEvaluadoresPorCriterio&i=<?php echo $id_proceso; ?>&campo_criterio = cargo');
			});
		</script>
	
	<?php ; ?>
		<script type="text/javascript">
			$(window).load(function () {
				$("#capa2").load('?sw=ResumenEvaluadoEvaluadoresPorCriterio&i=<?php echo $id_proceso; ?>&campo_criterio = gerencia');
			});
		</script>
	
	<?php
		}
		else if ($seccion == "veRelacionesPorPocesoResultadosBK") {
		$id_empresa = $_SESSION["id_empresa"];
		$id_proceso = Decodear3($get["i"]);
		$rut_evaluador = Decodear3($get["revr"]);
		
		$criterio = Decodear3($get["cri"]);
		$valor_criterio = Decodear3($get["valcri"]);
		
		$arreglo_post = $post;
		$exportar_a_excel = $post["excel"];
		$pagina = $get["p"];
		$miarray = $get["arra"];
		$array_para_recibir_via_url = stripslashes($miarray);
		$array_para_recibir_via_url = urldecode($array_para_recibir_via_url);
		$matriz_completa = unserialize($array_para_recibir_via_url, ['allowed_classes' => false]);
		if ($matriz_completa) {
			$arreglo_post = $matriz_completa;
		}
		
		if ($exportar_a_excel == 1) {
			EncabezadoExcel("ReporteEvaluadoEvaluador");
			$PRINCIPAL = ListadoRelacionesEvaluadoEvaluadorProProcesoResultados(FuncionesTransversalesAdmin(file_get_contents("views/procesos/reportes/resultados/entorno_listado_relaciones_excel.html")), $arreglo_post, $pagina, "usuarios", $id_proceso, $rut_evaluador, $exportar_a_excel);
		}
		else {
			$PRINCIPAL = ListadoRelacionesEvaluadoEvaluadorProProcesoResultados(FuncionesTransversalesAdmin(ColocaFiltros3Campos(file_get_contents("views/procesos/reportes/resultados/entorno_listado_relaciones.html"), $id_empresa, $arreglo_post)), $arreglo_post, $pagina, "usuarios", $id_proceso, $rut_evaluador, $exportar_a_excel, "", $criterio, $valor_criterio);
			$PRINCIPAL = str_replace("{GRAFICO_LINEAL}", ConsolidadoLineal("", "", $id_proceso, $id_empresa, "admin", $arreglo_post), $PRINCIPAL);
			$PRINCIPAL = str_replace("{GRAFICO_BARRA}", GraficoPorCompetenciasPorEvaluadorCalibrador("", $id_proceso, "", "", "admin", $arreglo_post), $PRINCIPAL);
			
			$PRINCIPAL = FuncionParaObtenerCalculoMientrasAvanzaPagina($PRINCIPAL, "", $id_proceso, $id_empresa, "", "", "admin", $arreglo_post);
			$PRINCIPAL = ColocaAvanceEvaluadorDadoLosFinalizados($PRINCIPAL, "", $id_proceso, $id_empresa, "admin", $arreglo_post);
		}
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	?>
		<script type="text/javascript">
			$(window).load(function () {
				$("#grafico1").load('?sw=RankingPorCompetencias&i=<?php echo $id_proceso; ?>&campo_criterio = cargo');
			});
		</script>
		<script type="text/javascript">
			$(window).load(function () {
				$("#capa").load('?sw=ResumenEvaluadoEvaluadoresPorCriterio&i=<?php echo $id_proceso; ?> &campo_criterio = cargo');
			});
		</script>
		<script type="text/javascript">
			$(window).load(function () {
				$("#capa2").load('?sw=ResumenEvaluadoEvaluadoresPorCriterio&i=<?php echo $id_proceso; ?>&campo_criterio = gerencia');
			});
		</script>
	
	<?php
		}
		else if ($seccion == "resetEval") {
		$rut_evaluado = Decodear3($get["re"]);
		$rut_evaluador = Decodear3($get["revr"]);
		$id_proceso = Decodear3($get["ip"]);
		
		ReseteaDataEvaluacion($rut_evaluado, $rut_evaluador, $id_proceso);
		echo "
<script>
location.href='?sw=GestionRelacionesPorProceso&i=" . Encodear3($id_proceso) . "';
</script>";
	}
		else if ($seccion == "abrEval") {
		$rut_evaluado = Decodear3($get["re"]);
		$rut_evaluador = Decodear3($get["revr"]);
		$id_proceso = Decodear3($get["ip"]);
		
		AbrirEvaluacion($rut_evaluado, $rut_evaluador, $id_proceso);
		echo "
<script>
location.href='?sw=GestionRelacionesPorProceso&i=" . Encodear3($id_proceso) . "';
</script>";
	} else if ($seccion == "GestionRelacionesPorProceso") {
		$id_empresa = $_SESSION["id_empresa"];
		$id_proceso = Decodear3($get["i"]);
		$rut_evaluador = Decodear3($get["revr"]);
		
		$criterio = Decodear3($get["cri"]);
		$valor_criterio = Decodear3($get["valcri"]);
		
		$arreglo_post = $post;
		$exportar_a_excel = $get["ex"];
		$pagina = $get["p"];
		$miarray = $get["arra"];
		$array_para_recibir_via_url = stripslashes($miarray);
		$array_para_recibir_via_url = urldecode($array_para_recibir_via_url);
		$matriz_completa = unserialize($array_para_recibir_via_url, ['allowed_classes' => false]);
		if ($matriz_completa) {
			$arreglo_post = $matriz_completa;
		}
		
		if ($exportar_a_excel == 1) {
			EncabezadoExcel("ReporteEvaluadoEvaluador");
			$PRINCIPAL = GestionListadoRelacionesPorProceso(FuncionesTransversalesAdmin(file_get_contents("views/sgd_relaciones/" . $id_empresa . "_entorno_listado_relaciones_excel.html")), $id_proceso, $rut_evaluador, $exportar_a_excel, $id_empresa);
		}
		else {
			$PRINCIPAL = GestionListadoRelacionesPorProceso(FuncionesTransversalesAdmin(file_get_contents("views/sgd_relaciones/" . $id_empresa . "_entorno_listado_relaciones.html")), $id_proceso, $rut_evaluador, $exportar_a_excel, $id_empresa);
		}
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	?>
		<script type="text/javascript">
			$(window).load(function () {
				$("#capa").load('?sw=ResumenEvaluadoEvaluadoresPorCriterio&i=<?php echo $id_proceso; ?>
				&
				campo_criterio = cargo
				');
			});
		</script>
	
	<?php ; ?>
		<script type="text/javascript">
			$(window).load(function () {
				$("#capa2").load('?sw=ResumenEvaluadoEvaluadoresPorCriterio&i=<?php echo $id_proceso; ?>
				&
				campo_criterio = gerencia
				');
			});
		</script>
	
	<?php
		}
		else if ($seccion == "veEvaluadorPorProceso") {
		$id_empresa = $_SESSION["id_empresa"];
		$datos_empresa = DatosEmpresa($id_empresa);
		$id_proceso = Decodear3($get["i"]);
		$criterio = Decodear3($get["cri"]);
		$valor_criterio = Decodear3($get["valcri"]);
		$arreglo_post = $post;
		$exportar_a_excel = $get["ex"];
		$pagina = $get["p"];
		$miarray = $get["arra"];
		$array_para_recibir_via_url = stripslashes($miarray);
		$array_para_recibir_via_url = urldecode($array_para_recibir_via_url);
		$matriz_completa = unserialize($array_para_recibir_via_url, ['allowed_classes' => false]);
		if ($matriz_completa) {
			$arreglo_post = $matriz_completa;
		}
		
		if ($exportar_a_excel == 1) {
			EncabezadoExcel("ReporteEvaluador");
			$PRINCIPAL = ListadoEvaluadoresPorProceso(FuncionesTransversalesAdmin(file_get_contents("views/procesos/reportes/entorno_listado_evaluadores_proceso_excel.html")), $arreglo_post, $pagina, "usuarios", $id_proceso, $exportar_a_excel, "", $criterio, $valor_criterio, $id_empresa);
		}
		else {
		
			$PRINCIPAL = FiltrosReportesAvancesSgd(ListadoEvaluadoresPorProceso(FuncionesTransversalesAdmin(file_get_contents("views/procesos/reportes/" . $id_empresa . "_entorno_listado_evaluadores_proceso.html")), $arreglo_post, $pagina, "usuarios", $id_proceso, $exportar_a_excel, "", $criterio, $valor_criterio, $id_empresa), $arreglo_post, $id_empresa);
			
			$PRINCIPAL = colocaDatosInformeBoletin($PRINCIPAL, $arreglo_post, $id_empresa, $id_proceso);
			$PRINCIPAL = str_replace("{ID_PROCESO}", Encodear3($id_proceso), $PRINCIPAL);
		}
		
		if ($exportar_a_excel == 1) {
			echo($PRINCIPAL);
			exit;
		}
		else {
			echo CleanHTMLWhiteList($PRINCIPAL);
			exit;
		}
		?>
		<!--
<script type="text/javascript">
$(window).load(function() {
$("#capa").load('?sw=ResumenEvaluadoresPorCriterio&idproceso=<?php echo $id_proceso; ?>&campo_criterio=<?php echo $datos_empresa[0]->campo1; ?>');
});
</script>

<script type="text/javascript">
$(window).load(function() {
$("#capa2").load('?sw=ResumenEvaluadoresPorCriterio&idproceso=<?php echo $id_proceso; ?>&campo_criterio=<?php echo $datos_empresa[0]->campo2; ?>');
});
</script>

<script type="text/javascript">
$(window).load(function() {
$("#capa3").load('?sw=ResumenEvaluadoresPorCriterio&idproceso=<?php echo $id_proceso; ?>&campo_criterio=<?php echo $datos_empresa[0]->campo3; ?>');
});
</script>
-->
		
		<?php
	} else if ($seccion == "veEvaluadorPorProcesoFijacion") {
		$id_empresa = $_SESSION["id_empresa"];
		$datos_empresa = DatosEmpresa($id_empresa);
		$id_proceso = Decodear3($get["i"]);
		$criterio = Decodear3($get["cri"]);
		$valor_criterio = Decodear3($get["valcri"]);
		$arreglo_post = $post;
		$exportar_a_excel = $get["ex"];
		$pagina = $get["p"];
		$miarray = $get["arra"];
		$array_para_recibir_via_url = stripslashes($miarray);
		$array_para_recibir_via_url = urldecode($array_para_recibir_via_url);
		$matriz_completa = unserialize($array_para_recibir_via_url, ['allowed_classes' => false]);
		if ($matriz_completa) {
			$arreglo_post = $matriz_completa;
		}
		
		$tipo = $get["tipo"];
		if ($tipo == 2) {
		if ($exportar_a_excel == 1) {
			EncabezadoExcel("ReporteEvaluador");
			$PRINCIPAL = ListadoEvaluadoresPorProcesoFijacion(FuncionesTransversalesAdmin(file_get_contents("views/procesos/reportes/" . $id_empresa . "_entorno_listado_evaluadores_proceso_fijacion_excel.html")), $arreglo_post, $pagina, "usuarios", $id_proceso, $exportar_a_excel, "", $criterio, $valor_criterio, $id_empresa, "");
		}
		else {
			$PRINCIPAL = ListadoEvaluadoresPorProcesoFijacion(FuncionesTransversalesAdmin(file_get_contents("views/procesos/reportes/" . $id_empresa . "_entorno_listado_evaluadores_proceso_fijacion.html")), $arreglo_post, $pagina, "usuarios", $id_proceso, $exportar_a_excel, "", $criterio, $valor_criterio, $id_empresa, "");
			$PRINCIPAL = str_replace("{ID_PROCESO_ENCODEADO}", Encodear3($id_proceso), $PRINCIPAL);
		}
		
		if ($exportar_a_excel == 1) {
			echo($PRINCIPAL);
		}
		else {
			echo CleanHTMLWhiteList($PRINCIPAL);
			exit;
		}
	?>
		<script type="text/javascript">
			$(window).load(function () {
				$("#capa").load('?sw=ResumenEvaluadoresPorCriterioFijacion&idproceso=<?php echo $id_proceso; ?>&campo_criterio=<?php echo $datos_empresa[0]->campo1; ?>
				');
			});
		</script>

		<script type="text/javascript">
			$(window).load(function () {
				$("#capa2").load('?sw=ResumenEvaluadoresPorCriterioFijacion&idproceso=<?php echo $id_proceso; ?>&campo_criterio=<?php echo $datos_empresa[0]->campo2; ?>
				');
			});
		</script>

		<script type="text/javascript">
			$(window).load(function () {
				$("#capa3").load('?sw=ResumenEvaluadoresPorCriterioFijacion&idproceso=<?php echo $id_proceso; ?>&campo_criterio=<?php echo $datos_empresa[0]->campo3; ?>
				');
			});
		</script>
	
	<?php
		}
		elseif ($tipo == 6) {
			$id_proceso = Decodear3($get["i"]);
			
			$PRINCIPAL = ListadoEvaluadoresPorProcesoMediociclo(FuncionesTransversalesAdmin(file_get_contents("views/procesos/reportes/" . $id_empresa . "_entorno_listado_evaluadores_proceso_medio_ciclo.html")), $arreglo_post, $pagina, "usuarios", $id_proceso, $exportar_a_excel, "", $criterio, $valor_criterio, $id_empresa, "");
			$PRINCIPAL = str_replace("{ID_PROCESO_ENCODEADO}", Encodear3($id_proceso), $PRINCIPAL);
			
			echo CleanHTMLWhiteList($PRINCIPAL);
			exit;
		}
		}
		else if ($seccion == "veEvaluadoEvaluadorPorProcesoFijacion") {
		$id_empresa = $_SESSION["id_empresa"];
		$datos_empresa = DatosEmpresa($id_empresa);
		$id_proceso = Decodear3($get["i"]);
		$criterio = Decodear3($get["cri"]);
		$valor_criterio = Decodear3($get["valcri"]);
		$arreglo_post = $post;
		$exportar_a_excel = $get["ex"];
		$pagina = $get["p"];
		$miarray = $get["arra"];
		
		$fechahoy = date("Y-m-d") . "|" . date("H:i:s");
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=SabanaRelacionesJefeColaborador" . $fechahoy . ".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		
		$PRINCIPAL = ListadoEvaluadoEvaluadoresPorProcesoFijacion(FuncionesTransversalesAdmin(file_get_contents("views/procesos/reportes/" . $id_empresa . "_entorno_listado_evaluado_evaluadores_proceso_fijacion.html")), $arreglo_post, $pagina, "usuarios", $id_proceso, $exportar_a_excel, "", $criterio, $valor_criterio, $id_empresa, "");
		echo($PRINCIPAL);
	}
		else if ($seccion == "veEvaluadoEvaluadorPorProcesoMedioCiclo") {
		$id_empresa = $_SESSION["id_empresa"];
		$datos_empresa = DatosEmpresa($id_empresa);
		$id_proceso = Decodear3($get["i"]);
		$criterio = Decodear3($get["cri"]);
		$valor_criterio = Decodear3($get["valcri"]);
		$arreglo_post = $post;
		$exportar_a_excel = $get["ex"];
		$pagina = $get["p"];
		$miarray = $get["arra"];
		
		$fechahoy = date("Y-m-d") . "|" . date("H:i:s");
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=SabanaRelacionesJefeColaboradorMediociclo" . $fechahoy . ".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		
		$PRINCIPAL = ListadoEvaluadoEvaluadoresPorProcesoMediociclo(FuncionesTransversalesAdmin(file_get_contents("views/procesos/reportes/" . $id_empresa . "_entorno_listado_evaluadores_proceso_medio_ciclo_excel.html")), $arreglo_post, $pagina, "usuarios", $id_proceso, $exportar_a_excel, "", $criterio, $valor_criterio, $id_empresa, "");
		echo($PRINCIPAL);
	}
		else if ($seccion == "relPreguntasAlternativas") {
		$id_pregunta = Decodear3($get["i"]);
		$PRINCIPAL = ListadoRelacionesPreguntasAlternativas(FuncionesTransversalesAdmin(file_get_contents("views/preguntas/formulario_rel_competencia_pregunta.html")), $id_pregunta);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
		
		else if ($seccion == "perfilEvaluacion") {
		$PRINCIPAL = Tabla_Instrumentos(FuncionesTransversalesAdmin(file_get_contents("views/instrumentos/entorno_listado.html")), $_SESSION["id_empresa"]);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	} else if ($seccion == "listbaspersonas") {
		$exportar_a_excel = $post["ex"];
		$pagina = $get["p"];
		if ($exportar_a_excel == "1") {
			$arreglo_post = $post;
			$fechahoy = date("Y-m-d") . " " . date("H:i:s");
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=" . $fechahoy . ".xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			
			$PRINCIPAL = FiltrosListadoCursos(ListadoCursosAdmin(FuncionesTransversalesAdmin(file_get_contents("views/basepersonas/entorno_listado_excel.html")), $arreglo_post, 0), $arreglo_post);
			echo($PRINCIPAL);
		}
		else {
			$arreglo_post = $post;
			$PRINCIPAL = FiltrosListadoCursos(ListadoCursosAdmin(FuncionesTransversalesAdmin(file_get_contents("views/basepersonas/entorno_listado.html")), $arreglo_post, $pagina), $arreglo_post, $pagina);
			echo CleanHTMLWhiteList($PRINCIPAL);
			exit;
		}
	}
		else if ($seccion == "agreotec") {
		$rut = ($post["rut_otec"]);
		$nombre_otec = ($post["nombre_otec"]);
		$direccion_otec = ($post["direccion_otec"]);
		$telefono_otec = ($post["telefono_otec"]);
		$email_otec = ($post["email_otec"]);
		$contacto_otec = ($post["contacto_otec"]);
		InsertaOtec($rut, $nombre_otec, $direccion_otec, $telefono_otec, $email_otec, $contacto_otec);
		
		echo "
<script>

location.href='?sw=listotec';
</script>";
	} else if ($seccion == "DeleteProcesoAnual") {
		$id_proceso_anual = ($get["iproan"]);
		
		EliminaProcesoAnual($id_proceso_anual);
		
		echo "
<script>
location.href='?sw=ProcAnual';
</script>";
		exit;
	}
		else if ($seccion == "DeleteProcesoEval") {
		$id_proceso_anual = Decodear3($get["iproan"]);
		$id_proceso = Decodear3($get["ip"]);
		
		EliminaProcesoEval($id_proceso);
		EliminaProcesoEval($id_proceso + 1);
		EliminaProcesoEval($id_proceso + 2);
		echo "
<script>
location.href='?sw=ProcAnual';
</script>";
		exit;
	} else if ($seccion == "edotec") {
		$id = Decodear3($post["id"]);
		$rut = ($post["rut_otec"]);
		$nombre_otec = ($post["nombre_otec"]);
		$direccion_otec = ($post["direccion_otec"]);
		$telefono_otec = ($post["telefono_otec"]);
		$email_otec = ($post["email_otec"]);
		$contacto_otec = ($post["contacto_otec"]);
		ActualizaDatosOtec($rut, $nombre_otec, $direccion_otec, $telefono_otec, $email_otec, $contacto_otec);
		
		echo "
<script>

location.href='?sw=listotec';
</script>";
	}
		else if ($seccion == "addotec") {
		$rut_otec = Decodear3($get["rot"]);
		$PRINCIPAL = formularioOtec(FuncionesTransversalesAdmin(file_get_contents("views/otec/formulario_ingresa.html")), $rut_otec);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
		else if ($seccion == "listProcesosCorreos") {
		$id_empresa = $_SESSION["id_empresa"];
		$PRINCIPAL = ListadoProcesosCorreos(FuncionesTransversalesAdmin(file_get_contents("views/correos/entorno_listado_procesos.html")), $id_empresa);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	} else if ($seccion == "listotec") {
		$arreglo_post = $post;
		$exportar_a_excel = $post["ex"];
		$pagina = $get["p"];
		$id_empresa = $_SESSION["id_empresa"];
		
		$PRINCIPAL = ListadoOtec(FuncionesTransversalesAdmin(file_get_contents("views/otec/entorno_listado.html")), $id_empresa);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
		else if ($seccion == "listobjetos") {
		$PRINCIPAL = ListadoObjetosAdmin(FuncionesTransversalesAdmin(file_get_contents("views/objeto/entorno_listado.html")));
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	} else if ($seccion == "addaccrel") {
		$id_empresa = Decodear3($post["id_empresa"]);
		$id_proceso = Decodear3($post["id_proceso"]);
		$rut_evaluado = $post["rutevaluado"];
		$rut_evaluador = $post["rutevaluador"];
		$rut_validador = $post["rutvalidador"];
		$rut_calibrador = $post["rutcalibrador"];
		$perfil_evaluacion = $post["perfil_evaluacion"];
		$subperfil_evaluacion = $post["subperfil_evaluacion"];
		$comentarios = ($post["comentarios"]);
		
		if ($id_empresa == 28) {
			$rut_validador = $rut_calibrador;
		}
		//Inserto Registro de relacion evaluado evaluador
		AdminInsertarRegistroRelacionEvaluadoEvaluador($rut_evaluado, $rut_evaluador, $rut_validador, $rut_calibrador, $id_proceso, $comentarios, $perfil_evaluacion, $subperfil_evaluacion, $id_empresa);
		
		echo "
<script>
alert('Registro ingresado exitosamente');
location.href='?sw=GestionRelacionesPorProceso&i=" . Encodear3($id_proceso) . "';
</script>";
	}
		else if ($seccion == "updaccrel") {
		$id_empresa = Decodear3($post["id_empresa"]);
		$id_proceso = Decodear3($post["id_proceso"]);
		$rut_evaluado = $post["rutevaluado"];
		$rut_evaluador = $post["rutevaluador"];
		$rut_validador = $post["rutvalidador"];
		$rut_calibrador = $post["rutcalibrador"];
		$rut_evaluador_original = $post["evaluador_original"];
		
		if ($id_empresa == 22) {
			$rut_calibrador = $post["rutvalidador"];
		}
		if ($id_empresa == 28) {
			$rut_validador = $post["rutcalibrador"];
		}
		$perfil_evaluacion = $post["perfil_evaluacion"];
		$subperfil_evaluacion = $post["subperfil_evaluacion"];
		$comentarios = ($post["comentarios"]);
		$data = trim($post["data"]);
		if ($data == "traspasa") {
			$alert = "Si el evaluado tiene evaluacion, por el evaluador, la informacion fue traspasada al nuevo evaluador";
		}
		elseif ($data == "resetea") {
			$alert = "La evaluacion ha sido reseteada";
		}
		//Inserto Registro de relacion evaluado evaluador
		if ($id_empresa == 22) {
			ActualizzaRelacion($rut_evaluado, $rut_evaluador, $rut_validador, $rut_calibrador, $comentarios);
		}
		elseif ($id_empresa == 28) {
			ActualizaRelacionEval($rut_evaluado, $rut_evaluador, $rut_validador, $rut_calibrador, $comentarios, $rut_evaluador_original, $perfil_evaluacion, $subperfil_evaluacion, $id_proceso);
			if ($data == "traspasa") {
				//funcion para traspasar la info de un evaluador a otro.
				TraspasaDataEvaluacion($rut_evaluado, $rut_evaluador, $id_proceso, $rut_validador, $rut_calibrador, $rut_evaluador_original);
			}
			elseif ($data == "resetea") {
				ReseteaDataEvaluacion($rut_evaluado, $rut_evaluador, $id_proceso);
			}
		}
		
		echo "
<script>
alert('Relacion Modificada')
location.href='?sw=GestionRelacionesPorProceso&i=" . Encodear3($id_proceso) . "';
</script>";
	}
		else if ($seccion == "autocompletarUsuarios") {
		$id_empresa = $_SESSION["id_empresa"];
		$buscar = ($get["q"]);
		$allData = allPersonasPorNombrePorEmpresaAdmin($buscar, $id_empresa);
		echo $allData;
	} else if ($seccion == "EjemplocomboBox") {
		$id_empresa = $_SESSION["id_empresa"];
		$usuarios = DatosTablaUsuarioTodosPorEmpresa($id_empresa);
		foreach ($usuarios as $usu) {
			$options .= "<option value='" . $usu->rut . "'>" . $usu->nombre_completo . "</option>";
		}
		echo $options;
	}
		else if ($seccion == "autocompletarNombreTNT") {
		$id_empresa = $_SESSION["id_empresa"];
		$buscar = ($get["q"]);
		$allData = allPersonasPorNombrePorEmpresa($buscar, $id_empresa);
		echo $allData;
	} else if ($seccion == "accionmalla") {
		$id_malla = Decodear3($get["i"]);
		
		if ($id_malla) {
			$PRINCIPAL = FormularioMalla(FuncionesTransversalesAdmin(file_get_contents("views/malla/formulario_malla.html")), $id_malla);
		}
		else {
			$PRINCIPAL = FormularioMalla(FuncionesTransversalesAdmin(file_get_contents("views/malla/formulario_malla_ingreso.html")), $id_malla);
		}
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
		else if ($seccion == "admallla") {
		$nombre_malla = (trim($post["nombre_malla"]));
		$descripcion_malla = (trim($post["descripcion_malla"]));
		$tipo_malla = trim($post["tipo_malla"]);
		$certificable = trim($post["certificable"]);
		$ponderacion = trim($post["ponderacion"]);
		$tipo_fecha = trim($post["tipo_fecha"]);
		//Agrego la malla a la tabla tbl_malla
		InsertaMalla(($nombre_malla), ($descripcion_malla), $tipo_malla, $certificable, $ponderacion, $tipo_fecha);
		echo "
<script>
alert('Malla ingresada exitosamente');
location.href='?sw=listmallas';
</script>";
	} else if ($seccion == "accionprograma") {
		$id_programa = Decodear3($get["i"]);
		if ($id_programa) {
			$PRINCIPAL = FormularioPrograma(FuncionesTransversalesAdmin(file_get_contents("views/programa/formulario_edita.html")), $id_programa);
		}
		else {
			$PRINCIPAL = FormularioPrograma(FuncionesTransversalesAdmin(file_get_contents("views/programa/formulario_ingresa.html")), $id_programa);
		}
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
		else if ($seccion == "relcursonivel") {
		$id_nivel = Decodear3($get["i"]);
		
		$PRINCIPAL = ListadoRelacionesNivelCurso(FuncionesTransversalesAdmin(file_get_contents("views/nivel/formulario_rel_nivel_curso.html")), $id_nivel);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
		else if ($seccion == "relmallaprog") {
		$id_malla = Decodear3($get["i"]);
		$PRINCIPAL = ListadoRelacionesMallaPrograma(FuncionesTransversalesAdmin(file_get_contents("views/malla/formulario_rel_malla_programa.html")), $id_malla);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
		else if ($seccion == "saveSeguPorEvPorCat") {
		$rut_evaluado = Decodear3($post["re"]);
		$id_categoria = Decodear3($post["ic"]);
		$pasos = PasosPorSeguimientoCategoria($id_categoria);
		$contador_datos = 0;
		foreach ($pasos as $pasos_evaluado) {
			//Verifico si tiene respuesta
			$tiene_respuesta = SeguimientoVerificoRespuestaPorRutCatePaso($rut_evaluado, $pasos_evaluado->id, $id_categoria);
			if ($tiene_respuesta) {
				//actualiza
				if (trim($post[$rut_evaluado . "_" . $pasos_evaluado->id])) {
					SeguimientoActualizaRespuestas($rut_evaluado, $pasos_evaluado->id, $id_categoria, ($post[$rut_evaluado . "_" . $pasos_evaluado->id]));
					$contador_datos++;
				}
			}
			else {
				//inserto
				if (trim($post[$rut_evaluado . "_" . $pasos_evaluado->id])) {
					SeguimientoInsertoRespuesta($rut_evaluado, $pasos_evaluado->id, $id_categoria, ($post[$rut_evaluado . "_" . $pasos_evaluado->id]));
					$contador_datos++;
				}
			}
		}
		
		if ($contador_datos == count($pasos)) {
			//Inserto Registro de Finalizado por Categoria
			SeguimientoInsertoFinalizadoCategoria($rut_evaluado, $id_categoria);
		}
		
		echo "
<script>
location.href='?sw=seguimiento&ic=" . Encodear3($id_categoria) . "#ROW_" . $rut_evaluado . "';
</script>";
		exit;
		
	}
		else if ($seccion == "seguimiento") {
		$id_malla = Decodear3($get["i"]);
		$id_empresa = $_SESSION["id_empresa"];
		$categoria = $get["categoria"];
		$id_categoria_por_get = Decodear3($get["ic"]);
		$excel = $get["ex"];
		if ($id_categoria_por_get) {
			$categoria = $id_categoria_por_get;
		}
		if ($categoria) {
			$datos_categoria = SeguimientoTotalCategoriasPorEmpresaDadoId($categoria);
			if ($excel == "1") {
				EncabezadoExcel("Seguimiento");
				$PRINCIPAL = SeguimientoPasos(FuncionesTransversalesAdmin(file_get_contents("views/seguimiento/seguimiento_excel.html")), $categoria, $id_empresa, $excel);
			}
			else {
				$PRINCIPAL = SeguimientoPasos(FuncionesTransversalesAdmin(file_get_contents("views/seguimiento/" . $datos_categoria[0]->template)), $categoria, $id_empresa, $excel);
			}
		}
		else {
			$PRINCIPAL = ListadoUsuariosResumenSeguimiento(FuncionesTransversalesAdmin(file_get_contents("views/seguimiento/seguimiento_sin_listado.html")), $id_empresa);
		}
		
		$PRINCIPAL = str_replace("{OPTIONS_CATEGORIAS}", OptionsGenericoNombreDadoValoresPorEmpresa("tbl_admin_seguimiento_categorias", $categoria, "nombre", "id", "order by nombre asc", $id_empresa), $PRINCIPAL);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
		else if ($seccion == "dltcompperfil") {
		$id_competencia = Decodear3($get["idc"]);
		$id_perfil = Decodear3($get["idper"]);
		
		EliminaRelacionCompetenciaPerfilEvaluacion($id_perfil, $id_competencia);
		echo "
<script>

location.href='?sw=relPerfilesCompetencias&i=" . Encodear3($id_perfil) . "';
</script>";
	}
		else if ($seccion == "relPerfilesCompetencias") {
		$id_perfil = Decodear3($get["i"]);
		$PRINCIPAL = ListadoRelacionesPerfilCompetencias(FuncionesTransversalesAdmin(file_get_contents("views/instrumentos/formulario_rel_perfil_competencia.html")), $id_perfil);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
		else if ($seccion == "relCompetenciasPreguntas") {
		$id_competencia = Decodear3($get["i"]);
		
		$PRINCIPAL = ListadoRelacionesCompetenciasPreguntas2(FuncionesTransversalesAdmin(file_get_contents("views/competencias/formulario_rel_competencia_pregunta.html")), $id_competencia);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
		else if ($seccion == "adrel") {
		$id_nivel = Decodear3($post["in"]);
		if ($post["cursos"]) {
			for ($i = 0; $i < count($post["cursos"]); $i++) {
				//Inserto un registro en la tabla rel nivel curso
				InsertaRelNivelCurso($id_nivel, $post["cursos"][$i]);
			}
			
			echo "
<script>
alert('Cursos relacionados correctamente');
location.href='?sw=relcursonivel&i=" . Encodear($id_nivel) . "';
</script>";
			exit;
		}
	}
		else if ($seccion == "adrelm") {
		$id_malla = Decodear3($post["i"]);
		if ($post["programas"]) {
			for ($i = 0; $i < count($post["programas"]); $i++) {
				//Inserto un registro en la tabla rel nivel curso
				InsertaRelacionMallaPrograma($id_malla, $post["programas"][$i]);
			}
			
			echo "
<script>
alert('Programas relacionados correctamente');
location.href='?sw=relmallaprog&i=" . Encodear($id_malla) . "';
</script>";
			exit;
		}
	}
		else if ($seccion == "adrelpc") {
		$id_perfil = Decodear3($post["i"]);
		if ($post["competencias"]) {
			for ($i = 0; $i < count($post["competencias"]); $i++) {
				//Inserto un registro en la tabla rel nivel curso
				InsertaCompetenciaPerfil($id_perfil, $post["competencias"][$i]);
			}
			
			echo "
<script>
alert('Competencia Asociada correctamente');
location.href='?sw=relPerfilesCompetencias&i=" . Encodear3($id_perfil) . "';
</script>";
			exit;
		}
	}
		else if ($seccion == "adrelpregcomp") {
		$id_competencia = Decodear3($post["i"]);
		if ($post["competencias"]) {
			for ($i = 0; $i < count($post["competencias"]); $i++) {
				//Inserto un registro en la tabla rel nivel curso
				InsertaPreguntaCompetencia($id_competencia, $post["competencias"][$i]);
			}
			
			echo "
<script>
alert('Pregunta Asociada con exito');
location.href='?sw=relCompetenciasPreguntas&i=" . Encodear3($id_competencia) . "';
</script>";
			exit;
		}
	}
		else if ($seccion == "ednivel") {
		$id_nivel = Decodear3($post["id"]);
		$nombre_nivel = (trim($post["nombre_nivel"]));
		$descripcion_nivel = ($post["descripcion_nivel"]);
		$programa = ($post["programa"]);
		$tutor = ($post["tutor"]);
		//Actualizo PRograma
		ActualizaNivel($id_nivel, $nombre_nivel, $descripcion_nivel, $programa, $tutor);
		echo "
<script>
alert('Datos Actualizados exitosamente');
location.href='?sw=listniveles';
</script>";
		exit;
	}
		else if ($seccion == "desuario") {
		$rut = trim(Decodear3($get["r"]));
		DesvinculaUsuario($rut);
		echo "
<script>
location.href='?sw=listusu';
</script>";
		exit;
	}
		else if ($seccion == "desuarioSeg") {
		$rut = trim(Decodear3($get["r"]));
		DesvinculaUsuario($rut);
		echo "
<script>
location.href='?sw=seguimiento&categoria=5';
</script>";
		exit;
	}
		else if ($seccion == "desuarioSiga") {
		$rut = trim(Decodear3($get["r"]));
		DesvinculaUsuario($rut);
		echo "
<script>
location.href='?sw=listusuSiga';
</script>";
		exit;
	}
		
		else if ($seccion == "accionbasepersonasSiga") {
		$rut = Decodear3($get["r"]);
		if ($rut) {
			$PRINCIPAL = FormularioUsuarioSIGA(FuncionesTransversalesAdmin(file_get_contents("views/basepersonas/formulario_ingresa_SIGA.html")), $rut);
		}
		else {
			$PRINCIPAL = FormularioUsuarioSIGA(FuncionesTransversalesAdmin(file_get_contents("views/basepersonas/formulario_ingresa_SIGA.html")), $rut);
		}
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
		else if ($seccion == "accionbasepersonasSiga") {
		$rut = Decodear3($get["r"]);
		if ($rut) {
			$PRINCIPAL = FormularioUsuarioSIGA(FuncionesTransversalesAdmin(file_get_contents("views/basepersonas/formulario_ingresa_SIGA.html")), $rut);
		}
		else {
			$PRINCIPAL = FormularioUsuarioSIGA(FuncionesTransversalesAdmin(file_get_contents("views/basepersonas/formulario_ingresa_SIGA.html")), $rut);
		}
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
		else if ($seccion == "eddrelac") {
		$id_proceso = Decodear3($get["i"]);
		$rut_evaluado = Decodear3($get["re"]);
		$rut_evaluador = Decodear3($get["revr"]);
		$id_empresa = $_SESSION["id_empresa"];
		$PRINCIPAL = FormularioEditaRelacionSGD(FuncionesTransversalesAdmin(file_get_contents("views/sgd_relaciones/" . $id_empresa . "_formulario_edita.html")), $id_empresa, $id_proceso, $rut_evaluado, $rut_evaluador);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
		else if ($seccion == "addNuevaRelacion") {
		$id_proceso = Decodear3($get["i"]);
		$id_empresa = $_SESSION["id_empresa"];
		
		if ($rut) {

		}
		else {
			$PRINCIPAL = FormularioIngresoRelacionSGD(FuncionesTransversalesAdmin(file_get_contents("views/sgd_relaciones/" . $id_empresa . "_formulario_ingresa.html")), $id_empresa, $id_proceso);
		}
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
		else if ($seccion == "accionbasepersonas") {
		$rut = Decodear3($get["r"]);
		$id_empresa = $_SESSION["id_empresa"];
		$reset = ($get["resetpass"]);
		
		if ($reset == 1) {
			resetea_clave2017($rut);
		}
		
		$clave_mod = buscaModificacionclave($rut);
		
		
		if ($clave_mod[0]->cambiado == 1) {
			$txt = "Clave modificada el " . $clave_mod[0]->fecha_cambio;
		}
		else {
			$txt = "Clave No Modificada.";
		}
		
		if ($rut) {
			if ($_SESSION["id_empresa"] != 28) {
				if ($id_empresa == 76) {
					$PRINCIPAL = FormularioUsuario(ColocaCamposParaFormulario(FuncionesTransversalesAdmin(file_get_contents("views/basepersonas/" . $id_empresa . "_vista_usuario_dinamico.html")), $id_empresa, "editar"), $rut, $_SESSION["id_empresa"]);
				}
				elseif ($id_empresa == 74) {
					$PRINCIPAL = FormularioUsuario(ColocaCamposParaFormulario(FuncionesTransversalesAdmin(file_get_contents("views/basepersonas/" . $id_empresa . "_vista_usuario_dinamico.html")), $id_empresa, "editar"), $rut, $_SESSION["id_empresa"]);
				}
				elseif ($id_empresa == 79) {
					$PRINCIPAL = FormularioUsuario(ColocaCamposParaFormulario(FuncionesTransversalesAdmin(file_get_contents("views/basepersonas/" . $id_empresa . "_vista_usuario_dinamico.html")), $id_empresa, "editar"), $rut, $_SESSION["id_empresa"]);
				}
				elseif ($id_empresa == 61) {
					$PRINCIPAL = FormularioUsuario(ColocaCamposParaFormulario(FuncionesTransversalesAdmin(file_get_contents("views/basepersonas/" . $id_empresa . "_vista_usuario_dinamico.html")), $id_empresa, "editar"), $rut, $_SESSION["id_empresa"]);
				}
				elseif ($id_empresa == 78) {
					$PRINCIPAL = FormularioUsuario(ColocaCamposParaFormulario(FuncionesTransversalesAdmin(file_get_contents("views/basepersonas/" . $id_empresa . "_vista_usuario_dinamico.html")), $id_empresa, "editar"), $rut, $_SESSION["id_empresa"]);
				}
				else {
					$PRINCIPAL = FormularioUsuario(ColocaCamposParaFormulario(FuncionesTransversalesAdmin(file_get_contents("views/basepersonas/vista_usuario_dinamico.html")), $id_empresa, "editar"), $rut, $_SESSION["id_empresa"]);
				}
				
				$PRINCIPAL = ColocaDatosPorPersonaweb($PRINCIPAL, $rut, $post["fecha_inicio"], $post["fecha_termino"], $id_empresa);
				
				$PRINCIPAL = str_replace("{RUT_ENCODEADO}", $get["r"], $PRINCIPAL);
				$PRINCIPAL = str_replace("{URL_FRONT}", $url_front, $PRINCIPAL);
				$PRINCIPAL = str_replace("{MODIFICACIONCLAVE}", $txt, $PRINCIPAL);
				
				$PRINCIPAL = ColocaDatosPerfil($PRINCIPAL, $rut);
			}
		}
		else {
			if ($_SESSION["id_empresa"] != 28) {
				$PRINCIPAL = FormularioUsuario(FuncionesTransversalesAdmin(file_get_contents("views/basepersonas/formulario_ingresa_dinamico.html")), $rut, $_SESSION["id_empresa"]);
				$PRINCIPAL = ColocaCamposParaFormulario($PRINCIPAL, $id_empresa, "ingresar");
			}
			else {
				$PRINCIPAL = FormularioUsuario(FuncionesTransversalesAdmin(file_get_contents("views/basepersonas/" . $_SESSION["id_empresa"] . "_formulario_ingresa.html")), $rut, $_SESSION["id_empresa"]);
			}
		}
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
		else if ($seccion == "lms_lista_historicos_admin") {
		$id_empresa = $_SESSION["id_empresa"];
		$rut = $get["rut"];
		$rut_enc = Encodear3($rut);
		$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/historicos/" . $id_empresa . "_entorno.html"));
		$PRINCIPAL = str_replace("{ENTORNO}", Lista_Busca_Cursos_historicos_admin(FuncionesTransversalesAdmin(file_get_contents("views/historicos/" . $id_empresa . "_listado_historicos.html")), $id_empresa, $rut), $PRINCIPAL);
		$PRINCIPAL = str_replace("{BOTON_VOLVER}", "<a href='?sw=accionbasepersonas&r=" . $rut_enc . "' class='btn btn-primary'> Volver </a>", $PRINCIPAL);
		$PRINCIPAL = str_replace("{RUT}", $rut, $PRINCIPAL);
		
		$datos_empresa = DatosEmpresa($id_empresa);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
		else if ($seccion == "lms_lista_cursos_historicos_admin") {
		$id_empresa = $_SESSION["id_empresa"];
		$rut = $post["rut"];
		
		$array_usuario = TraeDatosUsuario($rut);
		
		foreach ($post as $unico) {
			if ($unico != $rut and $unico != "Generar Certificados") {
				
				$data_array_insc = VistaCursosHistoricosDataAdminPorCurso($rut, $unico, $id_empresa);
				$txt_cursos .= "<tr>
                <td style='    TEXT-TRANSFORM: uppercase;'>" . $data_array_insc[0]->curso . "</td>
                <td>" . $data_array_insc[0]->fecha_inicio . "</td>
                <td>" . $data_array_insc[0]->fecha_termino . "</td>
                <td>" . $data_array_insc[0]->hora . "</td>
           </tr>";
			}
		}
		
		$arraycursos = VistaCursosHistoricosCertificadosData($rut, $post, $id_empresa);
		$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/historicos/" . $id_empresa . "_certificado_cursos.html"));
		$hoy_full = date("Y-m-d");
		$hoy = fechaCastellano($hoy_full);
		$PRINCIPAL = str_replace("{RUT_COMPLETO}", $array_usuario[0]->rut_completo, $PRINCIPAL);
		$PRINCIPAL = str_replace("{NOMBRE_COMPLETO}", $array_usuario[0]->nombre_completo, $PRINCIPAL);
		$PRINCIPAL = str_replace("{FECHA_ANTIGUEDAD}", $array_usuario[0]->fecha_antiguedad, $PRINCIPAL);
		$PRINCIPAL = str_replace("{FECHA_ACTUAL}", $hoy, $PRINCIPAL);
		$PRINCIPAL = str_replace("{LISTA_CURSOS_HISTORICOS}", $txt_cursos, $PRINCIPAL);
		
		$datos_empresa = DatosEmpresa($id_empresa);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
		else if ($seccion == "lms_lista_historicos_admin_2") {
		$rut = $get["rut"];
		$id_empresa = $_SESSION["id_empresa"];
		$id_gerencia = $post["id_gerencia"];
		$id_departamento = $post["id_departamento"];
		$id_zona = $post["id_zona"];
		$gerencia = $post["gerencia"];
		$DatosUsuario = mp_buscaDATOSPERSONAS($rut, $id_empresa);
		$PRINCIPAL = FuncionesTransversales(file_get_contents("views/historicos/" . $id_empresa . "_listado_historicos.html"));
		if ($rut_enc != '') {
			$rut = $rut_enc;
			$formulario = "";
		}
		$listado_cursos_historicos = Busca_Cursos_historicos($PRINCIPAL, $id_empresa, $rut);
		

		$PRINCIPAL = str_replace("{LISTADO_CURSOS_HISTORICOS}", $listado_cursos_historicos, $PRINCIPAL);
		$PRINCIPAL = BarraNavegacion($PRINCIPAL, $seccion);
		$PRINCIPAL = str_replace("{MENU_SECUNDARIO}", "", $PRINCIPAL);
		$PRINCIPAL = str_replace("{BARRA_NAVEGACION_BUSCADOR}", "", $PRINCIPAL);
		if ($rut_enc != '') {
			$PRINCIPAL = str_replace("{BOTON_VOLVER}", "&nbsp;<a href='?sw=mi_equipo_lider' class='btn btn-info btn-sm'><i class='fas fa-angle-left'></i> volver</a>", $PRINCIPAL);
			$PRINCIPAL = str_replace("{NOMBRE_COMPLETO_PERSONA}", " de " . $DatosUsuario[0]->nombre_completo, $PRINCIPAL);
		}
		if ($rut_enc == "") {
			$formulario = "    <div class='alert alert-info' role='alert'>
    <strong>¿Necesitas un certificado de cursos realizados?</strong>
    Selecciona los cursos que requieres y luego
    <input type='submit' class='btn btn-info ' value='Genera tu Certificado Aquí'>
</div>  ";
			$PRINCIPAL = str_replace("{NOMBRE_COMPLETO_PERSONA}", "", $PRINCIPAL);
			$PRINCIPAL = str_replace("{BOTON_VOLVER}", "&nbsp;<a href='?sw=home_landing' class='btn btn-info btn-sm'><i class='fas fa-angle-left'></i> volver</a>", $PRINCIPAL);
		}
		$PRINCIPAL = str_replace("{FORM_CERTIFICADO}", $formulario, $PRINCIPAL);
		$PRINCIPAL = str_replace("{INSIGNIA}", "", $PRINCIPAL);
		$PRINCIPAL = str_replace("{VALUE_NOMBRE}", "", $PRINCIPAL);
		$PRINCIPAL = str_replace("{CONTENIDO_INGRESADO}", "", $PRINCIPAL);
		$PRINCIPAL = str_replace("{VALUE_OBJETIVO}", "", $PRINCIPAL);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
		else if ($seccion == "adProcAnual") {
		$id_empresa = $_SESSION["id_empresa"];
		$nombre_proceso = ($post["nombre_proceso"]);
		$descripcion_proceso = ($post["descripcion_proceso"]);
		$ano = $post["ano"];
		//Antes que todo, verifico si para este año y para esta empresa, ya existe un proceso del mismo año
		$tiene_proceso_anual = VerificaProcesoAnualPorEmpresa($ano, $id_empresa);
		if ($tiene_proceso_anual) {
			echo "<script>
alert('Ya existe proceso con el año ingresado');
window.history.back();
</script>";
			exit;
		}
		InsertaProcesoAnual($nombre_proceso, $descripcion_proceso, $ano, $id_empresa);
		echo "
<script>
location.href='?sw=ProcAnual';
</script>";
		exit;
	}
		else if ($seccion == "accionProcesoAnual") {
		$id = Decodear3($get["ipa"]);
		if ($rut) {
			$PRINCIPAL = FormularioProcesosAnuales(FuncionesTransversalesAdmin(file_get_contents("views/procesos/formulario_ingresa.html")), $id);
		}
		else {
			$PRINCIPAL = FormularioProcesosAnuales(FuncionesTransversalesAdmin(file_get_contents("views/procesos/formulario_ingresa.html")), $idt);
		}
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
		else if ($seccion == "accioncursoPrueba") {
		$PRINCIPAL = FormularioCurso(FuncionesTransversalesAdmin(file_get_contents("views/curso/formulario_ingresa_prueba.html")), $id_curso);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
		else if ($seccion == "accioncurso") {
		$id_curso = Decodear3($get["i"]);
		if ($id_curso) {
			$PRINCIPAL = FormularioCurso(FuncionesTransversalesAdmin(file_get_contents("views/curso/formulario_edita.html")), $id_curso);
		}
		else {
			$PRINCIPAL = FormularioCurso(FuncionesTransversalesAdmin(file_get_contents("views/curso/formulario_ingresa.html")), $id_curso);
		}
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
		else if ($seccion == "GR") {
		$id_inscripcion = Decodear3($get["ii"]);
		$rut_empresa_holding = Decodear3($get["reh"]);
		
		$datos_inscripcion = DatosInscripcionConMasInfoV2($id_inscripcion, $rut_empresa_holding);
		
		//Datos y Valores
		$listado_usuarios = ListadoUsuariosPorInscripcion($id_inscripcion, "reporte", $rut_empresa_holding);
		$listado_usuarios_cerrados = ListadoUsuariosCerradosPorInscripcion($id_inscripcion, "pdf", $rut_empresa_holding, "");
		
		$PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/reporte/entorno.html"));
		$PRINCIPAL = str_replace("{FECHA_INICIO}", $datos_inscripcion[0]->fecha_inicio, $PRINCIPAL);
		$PRINCIPAL = str_replace("{FECHA_TERMINO}", $datos_inscripcion[0]->fecha_termino, $PRINCIPAL);
		$PRINCIPAL = str_replace("{NUMERO}", $id_inscripcion, $PRINCIPAL);
		
		//VALOR TOTAL.
		$tiene_datos_reales = VerificaEstaEnCierreSoloIdInscripcion($id_inscripcion, "");
		if ($tiene_datos_reales) {
			$PRINCIPAL = str_replace("{VALOR_TOTAL}", colocarPesos($listado_usuarios_cerrados[2] + $listado_usuarios_cerrados[1]), $PRINCIPAL);
			$PRINCIPAL = str_replace("{VALOR_TOTAL_FRANQUICIA}", colocarPesos($listado_usuarios_cerrados[1]), $PRINCIPAL);
			$PRINCIPAL = str_replace("{VALOR_TOTAL_EMPRESA}", colocarPesos($listado_usuarios_cerrados[1]), $PRINCIPAL);
			$PRINCIPAL = str_replace("{TOTAL_HORAS_CAPACITACION}", $datos_inscripcion[0]->numero_horas, $PRINCIPAL);
			$PRINCIPAL = str_replace("{TOTAL_CAPACITADOS}", $datos_inscripcion[0]->total_inscritos, $PRINCIPAL);
			$PRINCIPAL = str_replace("{OBJETIVO_GENERAL}", ($datos_inscripcion[0]->objetivo_curso), $PRINCIPAL);
			$PRINCIPAL = str_replace("{OBJETIVOS_ESPECIFICOS}", (""), $PRINCIPAL);
			$PRINCIPAL = str_replace("{PROPOSITO}", (""), $PRINCIPAL);
			$PRINCIPAL = str_replace("{OBERVACIONES}", ($datos_inscripcion[0]->comentarios), $PRINCIPAL);
			$PRINCIPAL = str_replace("{NOMBRE_CURSO}", ($datos_inscripcion[0]->nombre_curso), $PRINCIPAL);
			$PRINCIPAL = str_replace("{LISTADO_PARTICIPANTES_INSCRITOS}", ($listado_usuarios[0]), $PRINCIPAL);
			$PRINCIPAL = str_replace("{LISTADO_PARTICIPANTES}", ($listado_usuarios_cerrados[0]), $PRINCIPAL);
			$PRINCIPAL = str_replace("{VALOR_POR_PARTICIPANTE}", colocarPesos($datos_inscripcion[0]->numero_horas * $datos_inscripcion[0]->valor_h), $PRINCIPAL);
			//Distinct de las Empresas para mostrarlas.
						$empresas = DistincEmpresasEnInscripcionesDeUsuariosPorInscripcionV2($id_inscripcion, $rut_empresa_holding);
			foreach ($empresas as $emp) {
				$html_empresas .= $emp->nombre_empresa . "<br>";
			}
			$PRINCIPAL = str_replace("{NOMBRE_EMPRESA}", ($html_empresas), $PRINCIPAL);
			
			//Centros de costos
			$centros_costos = DistincCentroCostoInscripcion($id_inscripcion);
			foreach ($centros_costos as $cc) {
				$html_cc .= "" . $cc->centro_costo . "<br>";
			}
			
			$PRINCIPAL = str_replace("{LISTADO_CENTRO_COSTO}", ($html_cc), $PRINCIPAL);
			
			//DATOS RELEVANTES
			//Mejor Alumno - Mejor Nota
			$datos_relevantes = TieneDatosRelevantesPorInscripcion($id_inscripcion);
			if ($datos_relevantes[0]->mejor_alumno) {
				if ($rut_empresa_holding) {
					//Verifica
					$verifica = VerificaUsuarioDadoIdInscripcionRutEmpresaHolding($id_inscripcion, $rut_empresa_holding, $datos_relevantes[0]->mejor_alumno);
					if ($verifica) {
						$PRINCIPAL = str_replace("{BLOQUE_MEJOR_NOTA}", file_get_contents("views/reporte/mejor_nota.html"), $PRINCIPAL);
						$PRINCIPAL = str_replace("{NOMBRE}", $datos_relevantes[0]->nombre_mejor_alumno . " " . $datos_relevantes[0]->apaterno_mejor_alumno . " " . $datos_relevantes[0]->amaterno_mejor_alumno, $PRINCIPAL);
						$PRINCIPAL = str_replace("{OBSERVACION}", $datos_relevantes[0]->mejor_alumno_comentario, $PRINCIPAL);
						$PRINCIPAL = str_replace("{TITULO}", "Mejor Nota", $PRINCIPAL);
						$nota1 = VerificaEstaEnCierre($id_inscripcion, $datos_relevantes[0]->mejor_alumno);
						$PRINCIPAL = str_replace("{NOTA}", $nota1[0]->nota, $PRINCIPAL);
					}
					else {
						$PRINCIPAL = str_replace("{BLOQUE_MEJOR_NOTA}", "", $PRINCIPAL);
					}
				}
				else {
					$PRINCIPAL = str_replace("{BLOQUE_MEJOR_NOTA}", file_get_contents("views/reporte/mejor_nota.html"), $PRINCIPAL);
					$PRINCIPAL = str_replace("{NOMBRE}", $datos_relevantes[0]->nombre_mejor_alumno . " " . $datos_relevantes[0]->apaterno_mejor_alumno . " " . $datos_relevantes[0]->amaterno_mejor_alumno, $PRINCIPAL);
					$PRINCIPAL = str_replace("{OBSERVACION}", $datos_relevantes[0]->mejor_alumno_comentario, $PRINCIPAL);
					$PRINCIPAL = str_replace("{TITULO}", "Mejor Nota", $PRINCIPAL);
					$nota1 = VerificaEstaEnCierre($id_inscripcion, $datos_relevantes[0]->mejor_alumno);
					$PRINCIPAL = str_replace("{NOTA}", $nota1[0]->nota, $PRINCIPAL);
				}
			}
			else {
				$PRINCIPAL = str_replace("{BLOQUE_MEJOR_NOTA}", "", $PRINCIPAL);
			}
			
			//Participacion Destacada
			if ($datos_relevantes[0]->alumno_destacado) {
				if ($rut_empresa_holding) {
					$verifica = VerificaUsuarioDadoIdInscripcionRutEmpresaHolding($id_inscripcion, $rut_empresa_holding, $datos_relevantes[0]->alumno_destacado);
					if ($verifica) {
						$PRINCIPAL = str_replace("{BLOQUE_PARTICIPACION_DESTACADA}", file_get_contents("views/reporte/mejor_nota.html"), $PRINCIPAL);
						$PRINCIPAL = str_replace("{NOMBRE}", $datos_relevantes[0]->nombre_alumno_destacado . " " . $datos_relevantes[0]->apaterno_alumno_destacado . " " . $datos_relevantes[0]->amaterno_alumno_destacado, $PRINCIPAL);
						$PRINCIPAL = str_replace("{OBSERVACION}", $datos_relevantes[0]->alumno_destacado_comentario, $PRINCIPAL);
						$PRINCIPAL = str_replace("{TITULO}", "Participaci&oacute;n Destacada", $PRINCIPAL);
						$nota2 = VerificaEstaEnCierre($id_inscripcion, $datos_relevantes[0]->alumno_destacado);
						$PRINCIPAL = str_replace("{NOTA}", $nota2[0]->nota, $PRINCIPAL);
					}
					else {
						$PRINCIPAL = str_replace("{BLOQUE_PARTICIPACION_DESTACADA}", "", $PRINCIPAL);
					}
				}
				else {
					$PRINCIPAL = str_replace("{BLOQUE_PARTICIPACION_DESTACADA}", file_get_contents("views/reporte/mejor_nota.html"), $PRINCIPAL);
					$PRINCIPAL = str_replace("{NOMBRE}", $datos_relevantes[0]->nombre_alumno_destacado . " " . $datos_relevantes[0]->apaterno_alumno_destacado . " " . $datos_relevantes[0]->amaterno_alumno_destacado, $PRINCIPAL);
					$PRINCIPAL = str_replace("{OBSERVACION}", $datos_relevantes[0]->alumno_destacado_comentario, $PRINCIPAL);
					$PRINCIPAL = str_replace("{TITULO}", "Participaci&oacute;n Destacada", $PRINCIPAL);
					$nota2 = VerificaEstaEnCierre($id_inscripcion, $datos_relevantes[0]->alumno_destacado);
					$PRINCIPAL = str_replace("{NOTA}", $nota2[0]->nota, $PRINCIPAL);
				}
			}
			else {
				$PRINCIPAL = str_replace("{BLOQUE_PARTICIPACION_DESTACADA}", "", $PRINCIPAL);
			}
			
			//Mejora Continua
			if ($datos_relevantes[0]->mejora_continua) {
				if ($rut_empresa_holding) {
					$verifica = VerificaUsuarioDadoIdInscripcionRutEmpresaHolding($id_inscripcion, $rut_empresa_holding, $datos_relevantes[0]->mejora_continua);
					if ($verifica) {
						$PRINCIPAL = str_replace("{BLOQUE_MEJORA_CONTINUA}", file_get_contents("views/reporte/mejor_nota.html"), $PRINCIPAL);
						$PRINCIPAL = str_replace("{NOMBRE}", $datos_relevantes[0]->nombre_mejora_continua . " " . $datos_relevantes[0]->apaterno_mejora_continua . " " . $datos_relevantes[0]->amaterno_mejora_continua, $PRINCIPAL);
						$PRINCIPAL = str_replace("{OBSERVACION}", $datos_relevantes[0]->mejora_continua_comentario, $PRINCIPAL);
						$PRINCIPAL = str_replace("{TITULO}", "Mejora Continua", $PRINCIPAL);
						$nota3 = VerificaEstaEnCierre($id_inscripcion, $datos_relevantes[0]->mejora_continua);
						$PRINCIPAL = str_replace("{NOTA}", $nota3[0]->nota, $PRINCIPAL);
					}
					else {
						$PRINCIPAL = str_replace("{BLOQUE_MEJORA_CONTINUA}", "", $PRINCIPAL);
					}
				}
				else {
					$PRINCIPAL = str_replace("{BLOQUE_MEJORA_CONTINUA}", file_get_contents("views/reporte/mejor_nota.html"), $PRINCIPAL);
					$PRINCIPAL = str_replace("{NOMBRE}", $datos_relevantes[0]->nombre_mejora_continua . " " . $datos_relevantes[0]->apaterno_mejora_continua . " " . $datos_relevantes[0]->amaterno_mejora_continua, $PRINCIPAL);
					$PRINCIPAL = str_replace("{OBSERVACION}", $datos_relevantes[0]->mejora_continua_comentario, $PRINCIPAL);
					$PRINCIPAL = str_replace("{TITULO}", "Mejora Continua", $PRINCIPAL);
					$nota3 = VerificaEstaEnCierre($id_inscripcion, $datos_relevantes[0]->mejora_continua);
					$PRINCIPAL = str_replace("{NOTA}", $nota3[0]->nota, $PRINCIPAL);
				}
			}
			else {
				$PRINCIPAL = str_replace("{BLOQUE_MEJORA_CONTINUA}", "", $PRINCIPAL);
			}
			
			//Bloque de Inasistencias
			//veo si hay inasistentes en la tabla de Incripcion cierre
			$casos_especiales = TotalUsuariosPorInscripcionCerrados2SoloCasosEspeciales($id_inscripcion);
			$PRINCIPAL = str_replace("{BLOQUE_INASISTENCIAS_CASOS_ESPECIALES}", file_get_contents("views/reporte/bloque_inasistencias_caso_especial.html"), $PRINCIPAL);
			$PRINCIPAL = str_replace("{TITULO}", "Inasistencias o Casos Especiales", $PRINCIPAL);
			
			if ($casos_especiales) {

				$listado_usuarios_casos_especiales = ListadoUsuariosCerradosPorInscripcion($id_inscripcion, "casos_especiales", "", "");
				
				$PRINCIPAL = str_replace("{ROW_CASOS_ESPECIALES}", ($listado_usuarios_casos_especiales[0]), $PRINCIPAL);
			}
			else {
				$PRINCIPAL = str_replace("{ROW_CASOS_ESPECIALES}", ' <tr><td colspan="3">Sin inasistencias o casos especiales</td></tr>', $PRINCIPAL);
			}
		}
		else {
		}
		
		echo($PRINCIPAL);
	}
		else if ($seccion == "InfEncXInsc") {
		$id_inscripcion = Decodear3($get["ii"]);
		$PRINCIPAL = FiltrosReportes(FuncionesTransversalesAdmin(file_get_contents("views/reportes/enc_satisfaccion/sk_enc_sat_home_reportes_consolidados.html")), $arreglo_post);
		$PRINCIPAL = str_replace("{RESULTADOS}", FiltrosReportes(file_get_contents("views/reportes/enc_satisfaccion/sk_enc_sat_resultados_consolidados.html"), $arreglo_post), $PRINCIPAL);
		$datos_inscripcion = DatosInscripcion2($id_inscripcion);
		$PRINCIPAL = str_replace("{NOMBRE_CURSO}", $datos_inscripcion[0]->nombre_curso, $PRINCIPAL);
		$PRINCIPAL = str_replace("{FECHA_INICIO}", $datos_inscripcion[0]->fecha_inicio, $PRINCIPAL);
		$PRINCIPAL = str_replace("{FECHA_TERMINO}", $datos_inscripcion[0]->fecha_termino, $PRINCIPAL);
		$PRINCIPAL = str_replace("{NUMERO_PARTICIPANTES}", $datos_inscripcion[0]->total_inscritos, $PRINCIPAL);
		$PRINCIPAL = str_replace("{ENCUESTAS_RESPONDIDAS}", $datos_inscripcion[0]->total_encuestas_finalizadas, $PRINCIPAL);
		$PRINCIPAL = str_replace("{PORCENTAJE_FINALIZADOS}", ($datos_inscripcion[0]->total_encuestas_finalizadas * 100) / $datos_inscripcion[0]->total_inscritos, $PRINCIPAL);
		
		$listado_usuarios = ListadoUsuariosPorInscripcion($id_inscripcion, "reporte", "");
		$PRINCIPAL = str_replace("{LISTADO_PARTICIPANTES}", $listado_usuarios[0], $PRINCIPAL);
		
		//Traigo dimensiones dada la encuesta
		$id_encuesta = $datos_inscripcion[0]->id_encuesta_satisfaccion;
		
		$dimensiones = EncSatisPromedioPorDimension($id_inscripcion);
		
		$acumulador_total = 0;
		foreach ($dimensiones as $uni) {
			$row_dim .= file_get_contents("views/reportes/enc_satisfaccion/sk_row_enc_sat_resultados_row_dim.html");
			$row_dim = str_replace("{NOMBRE}", $uni->dimension, $row_dim);
			$row_dim = str_replace("{NOTA}", FormatearPorcentajes1Decimal(TransformarEscalaEncSatisParaGrafico($uni->promedio)), $row_dim);
			$row_dim = str_replace("{NOTA_SIN_FORMATO}", (TransformarEscalaEncSatisParaGrafico($uni->promedio)), $row_dim);
			$acumulador_total = $acumulador_total + $uni->promedio;
		}
		$PRINCIPAL = str_replace("{LISTADO_DIMENSIONES}", ($row_dim), $PRINCIPAL);
		$PRINCIPAL = str_replace("{NOTA_FINAL}", FormatearPorcentajes1Decimal(TransformarEscalaEncSatisParaGrafico($acumulador_total / count($dimensiones))), $PRINCIPAL);
		$PRINCIPAL = str_replace("{NOTA_FINAL_SIN_FORMATO}", TransformarEscalaEncSatisParaGrafico($acumulador_total / count($dimensiones)), $PRINCIPAL);
		
		//TOP PREGUNTAS
		$top_preguntas = EncSatisTopBottom($id_inscripcion, 4, "desc");
		foreach ($top_preguntas as $preg) {
			$row_preg .= file_get_contents("views/reportes/enc_satisfaccion/sk_row_enc_sat_resultados_row_dim.html");
			$row_preg = str_replace("{NOMBRE}", ($preg->pregunta), $row_preg);
			$row_preg = str_replace("{NOTA}", FormatearPorcentajes1Decimal(TransformarEscalaEncSatisParaGrafico($preg->promedio_por_pregunta)), $row_preg);
			$row_preg = str_replace("{NOTA_SIN_FORMATO}", (TransformarEscalaEncSatisParaGrafico($preg->promedio_por_pregunta)), $row_preg);
		}
		
		$PRINCIPAL = str_replace("{ROW_TOP}", ($row_preg), $PRINCIPAL);
		
		//BOTTON PREGUNTAS
		$top_preguntas = EncSatisTopBottom($id_inscripcion, 4, "asc");
		$row_preg = "";
		foreach ($top_preguntas as $preg) {
			$row_preg .= file_get_contents("views/reportes/enc_satisfaccion/sk_row_enc_sat_resultados_row_dim.html");
			$row_preg = str_replace("{NOMBRE}", ($preg->pregunta), $row_preg);
			$row_preg = str_replace("{NOTA}", FormatearPorcentajes1Decimal(TransformarEscalaEncSatisParaGrafico($preg->promedio_por_pregunta)), $row_preg);
			$row_preg = str_replace("{NOTA_SIN_FORMATO}", (TransformarEscalaEncSatisParaGrafico($preg->promedio_por_pregunta)), $row_preg);
		}
		
		$PRINCIPAL = str_replace("{ROW_BOTTON}", ($row_preg), $PRINCIPAL);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
		else if ($seccion == "InfEncXInsc2") {
		$id_inscripcion = Decodear3($get["ii"]);
		$PRINCIPAL = FiltrosReportes(FuncionesTransversalesAdmin(file_get_contents("views/reporte/reporteGW.html")), $arreglo_post);
		
		$PRINCIPAL = str_replace("{LISTADO_DIMENSIONES}", ($row_dim), $PRINCIPAL);
		$PRINCIPAL = str_replace("{NOTA_FINAL}", FormatearPorcentajes1Decimal(TransformarEscalaEncSatisParaGrafico($acumulador_total / count($dimensiones))), $PRINCIPAL);
		$PRINCIPAL = str_replace("{NOTA_FINAL_SIN_FORMATO}", TransformarEscalaEncSatisParaGrafico($acumulador_total / count($dimensiones)), $PRINCIPAL);
		
		//TOP PREGUNTAS
		
		$PRINCIPAL = str_replace("{ROW_BOTTON}", ($row_preg), $PRINCIPAL);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
		else if ($seccion == "listinsc") {
		$arreglo_post = $post;
		$orderBy = $get["orderBy"];
		$PRINCIPAL = FiltrosReportes(ListadoInscripciones(FuncionesTransversalesAdmin(file_get_contents("views/inscripcion/entorno_listado.html")), $arreglo_post, $orderBy), $arreglo_post);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
		else if ($seccion == "listaInscripcionesT") {
		$i = $get["i"];
		$id_curso = Decodear3($i);
		$excel = $get["excel"];
		$id_empresa = $_SESSION["id_empresa"];
		
		if ($excel == 1) {
			$fechahoy = date("Y-m-d");
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=ImparticionesT_" . $fechahoy . ".xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			$encabezado = file_get_contents("views/capacitacion/imparticionT/entorno_listado_excel2.html");
			echo $encabezado;
			ListaImparticionesT(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/imparticionT/entorno_listado.html")), $id_curso, $_SESSION["id_empresa"], $excel);
			exit;
		}
		else {
			$PRINCIPAL = ListaImparticionesT(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/imparticionT/" . $id_empresa . "_entorno_listado.html")), $id_curso, $_SESSION["id_empresa"], "");
		}
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
		else if ($seccion == "listaEncuestasSatisfaccion") {
		$i = $get["i"];
		$id_curso = Decodear3($i);
		$excel = $get["excel"];
		$id_empresa = $_SESSION["id_empresa"];
		
		
		$sendemail = 0;
		$id_inscripcion = $get["idinscripcion"];
		$sendemail = $get["sendemail"];
		
		$rut_solo = $get["solorut"];
		$rut_individual = $get["rut_individual"];
		

		
		if ($excel == 1) {
			$fechahoy = date("Y-m-d");
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=Encuesta_Satisfaccion_Reporte_detalle_" . $fechahoy . ".xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			
			$encabezado = file_get_contents("views/capacitacion/encuesta_satisfaccion/" . $id_empresa . "_entorno_listado_excel.html");
			echo $encabezado;
			ListaEncuestaSatisfaccion(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/encuesta_satisfaccion/entorno_listado.html")), $id_curso, $id_inscripcion, $_SESSION["id_empresa"], $excel);
			exit;
		}
		elseif ($sendemail == 1) {
			$PRINCIPAL = EnvioListaEncuestaSatisfaccion(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/encuesta_satisfaccion/" . $id_empresa . "_entorno_listado.html")), $id_curso, $id_inscripcion, $_SESSION["id_empresa"], $rut_solo, $rut_individual, $url_front, $url_objetos, $url_objetos_corta, $from, $nombrefrom, $logo, $texto4);
		}
		else {
			$PRINCIPAL = ListaEncuestaSatisfaccion(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/encuesta_satisfaccion/" . $id_empresa . "_entorno_listado.html")), $id_curso, $id_inscripcion, $_SESSION["id_empresa"], "");
		}
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
		else if ($seccion == "VeSesionXImp") {
		$id_imparticion = Decodear3($get["i"]);
		$id_curso = Decodear3($i);

		
		$PRINCIPAL = ListaSesionesPorImparticiones(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/imparticion/entorno_listado_sesiones.html")), $id_imparticion, $_SESSION["id_empresa"]);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
		else if ($seccion == "subeNotasPorImparticion") {
		$id_imparticion = Decodear3($get["i"]);
		$id_curso = Decodear3($i);

		
		$PRINCIPAL = ListaSesionesPorImparticiones(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/imparticion/entorno_subida_notas_imparticiones.html")), $id_imparticion, $_SESSION["id_empresa"]);
		$PRINCIPAL = str_replace("{CODIGO_INSCRIPCION_ENCODEADO}", Encodear3($id_imparticion), $PRINCIPAL);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
		else if ($seccion == "ActualizaAsistenciaGlobalPorImparticion") {
		$id_empresa = $_SESSION["id_empresa"];
		$id_imparticion = $post["id_imparticion"];
		$porcentaje_avance = round($post["porcentaje_avance"]);
		$nota = round($post["nota"]);
		$datos_imparticion = DatosInscripcionImparticionesV2($id_imparticion, $id_empresa);
		$rut = $post["rut"];
		//Veo si el usuario, con rut, empresa, curso, imparticion, está en inscripcion cierre, si no, lo agrego, si está, lo edito
		$existe_en_cierre = VerificaEnTablaCierreCursoEmpresRutInscripcion($datos_imparticion[0]->id_curso, $rut, $id_empresa, $id_imparticion);
		if ($existe_en_cierre) {
			
			actualizaTablaCierreRut($rut, $datos_imparticion[0]->id_curso, $id_imparticion, $porcentaje_avance, $id_empresa, $nota);
		}
		else {
			
			InsertaTablaCierreRutCursoImparticionAsistenciaEmpresa($rut, $datos_imparticion[0]->id_curso, $id_imparticion, $porcentaje_avance, $id_empresa, $nota);
		}
		
		$existe_en_cierre = VerificaEnTablaCierreCursoEmpresRutInscripcion($datos_imparticion[0]->id_curso, $rut, $id_empresa, $id_imparticion);
		$bloque_asistencia = file_get_contents("views/capacitacion/imparticion/bloque_coloca_asistencia_total.html");
		
	
		$bloque_asistencia = str_replace("{AVANCE_ACTUAL}", $existe_en_cierre[0]->porcentaje_asistencia, $bloque_asistencia);
		$bloque_asistencia = str_replace("{ICONO}", "<img src='img/ok.ico' width='15px'>", $bloque_asistencia);
		$bloque_asistencia = str_replace("{RUT_COLABORADOR}", $rut, $bloque_asistencia);
		echo $bloque_asistencia;
	}
		else if ($seccion == "ColocaAvanceAsistenciaPorSesionImparticion") {
		$id_imparticion = $post["id_imparticion"];
		$id_sesion = $post["id_sesion"];
		$rut = $post["rut"];
		$id_empresa = $_SESSION["id_empresa"];
		$avance = CalculaAsistenciaEnBaseASesiones($rut, $id_imparticion, $id_empresa);
		
		$datos_imparticion = DatosInscripcionImparticionesV2($id_imparticion, $id_empresa);
		
		$existe_en_cierre = VerificaEnTablaCierreCursoEmpresRutInscripcion($datos_imparticion[0]->id_curso, $rut, $id_empresa, $id_imparticion);
		if ($existe_en_cierre) {
			actualizaTablaCierreRut($rut, $datos_imparticion[0]->id_curso, $id_imparticion, round($avance), $id_empresa);
		}
		else {
			InsertaTablaCierreRutCursoImparticionAsistenciaEmpresa($rut, $datos_imparticion[0]->id_curso, $id_imparticion, round($avance), $id_empresa);
		}
		
		$existe_en_cierre = VerificaEnTablaCierreCursoEmpresRutInscripcion($datos_imparticion[0]->id_curso, $rut, $id_empresa, $id_imparticion);
		
		$bloque_asistencia = file_get_contents("views/capacitacion/imparticion/bloque_coloca_asistencia_total.html");
		$barra = '<div class="progress">
  <div class="progress-bar progress-bar-striped" role="progressbar" style="width: ' . $avance . '%" aria-valuenow="' . $avance . '" aria-valuemin="0" aria-valuemax="100"></div>
</div>';
		
		$bloque_asistencia = str_replace("{AVANCE_ACTUAL}", round($existe_en_cierre[0]->porcentaje_asistencia), $bloque_asistencia);
		$bloque_asistencia = str_replace("{ICONO}", "<img src='img/ok.ico' width='15px'> ", $bloque_asistencia);
		$bloque_asistencia = str_replace("{RUT_COLABORADOR}", $rut, $bloque_asistencia);
		
		
		echo $bloque_asistencia;
	}
		else if ($seccion == "ColocaAsistenciaPorSesionImparticion") {
		
		$id_imparticion = $post["id_imparticion"];
		$id_sesion = $post["id_sesion"];
		$rut = $post["rut"];
		$id_empresa = $_SESSION["id_empresa"];
		$usuario_chequeado = TraigoRegistrosPorSesionDeCheckinPorUsuario($id_imparticion, $id_sesion, $id_empresa, $rut);
		if ($usuario_chequeado) {
			//Elimino Registro
			BorroRegistrosInscritosCheckinPorSesionImparticionPorUsuario($id_imparticion, $id_empresa, $id_sesion, $rut);
			
		}
		else {
			//Inserto Registro
			$datos_sesion = DatosSesionDadoId($id_sesion);
			if ($datos_sesion[0]->desde_am) {
				$hora_inicio = $datos_sesion[0]->desde_am;
			}
			elseif ($datos_sesion[0]->desde_pm) {
				$hora_inicio = $datos_sesion[0]->desde_pm;
			}
			InsertaRegistroCheckinSesionImparticion($rut, $id_imparticion, $id_empresa, $datos_sesion[0]->fecha, $hora_inicio, $id_sesion);
			
		}
	}
		else if ($seccion == "VeColaboradoresXImpXsessResumen") {
		$id_imparticion = Decodear3($get["i"]);
		$id_curso = Decodear3($i);
		$excel = $get["exc"];
		$id_empresa = $_SESSION["id_empresa"];
		
		
		if ($excel == 1) {
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=" . $id_imparticion . "_Asistencia.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			
			$PRINCIPAL = ListaColaboradoresPorImparticionesSesiones(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/imparticion/entorno_listado_colaboradores_sesiones_excel.html")), $id_imparticion, $_SESSION["id_empresa"], $excel);
			echo CleanHTMLWhiteList($PRINCIPAL);
			exit;
			exit;
		}
		else {
			$PRINCIPAL = ListaColaboradoresPorImparticionesSesionesResumen(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/imparticion/entorno_listado_colaboradores_sesiones_resumen.html")), $id_imparticion, $_SESSION["id_empresa"]);
		}
		
		$datos_imparticion = DatosInscripcionImparticionesV2($id_imparticion, $id_empresa);
		
		$PRINCIPAL = str_replace("{ID_IMPARTICION_ENCODEADO}", Encodear3($id_imparticion), $PRINCIPAL);
		$PRINCIPAL = str_replace("{ID_CURSO_ENCODEADO}", Encodear($datos_imparticion[0]->id_curso), $PRINCIPAL);
		
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
	}
		else if ($seccion == "GuardaAsistenciasPorImparticion") {
		$id_empresa = $_SESSION["id_empresa"];
		$id_imparticion = Decodear3($post["id_imparticion"]);
		$id_curso = Decodear3($post["id_curso"]);

		$total_sesiones = InsSesionesImparticion($id_empresa, $id_imparticion);
		
		$total_usuarios_por_inscripcion = IMPARTICION_UsuariosPorInscripcionConDatos($id_imparticion, $id_empresa);
		foreach ($total_usuarios_por_inscripcion as $usu) {
			foreach ($total_sesiones as $sesion) {
				if ($post["asistencia_" . $usu->rut . "_" . $sesion->id . "_" . $id_imparticion] == "on") {
					$usuario_chequeado = TraigoRegistrosPorSesionDeCheckinPorUsuario($id_imparticion, $sesion->id, $id_empresa, $usu->rut);
					if ($usuario_chequeado) {

					}
					else {
						//Inserto Registro
						$datos_sesion = DatosSesionDadoId($sesion->id);
						if ($datos_sesion[0]->desde_am) {
							$hora_inicio = $datos_sesion[0]->desde_am;
						}
						elseif ($datos_sesion[0]->desde_pm) {
							$hora_inicio = $datos_sesion[0]->desde_pm;
						}
						InsertaRegistroCheckinSesionImparticion($usu->rut, $id_imparticion, $id_empresa, $datos_sesion[0]->fecha, $hora_inicio, $sesion->id);

					}
				}
				else {
					$usuario_chequeado = TraigoRegistrosPorSesionDeCheckinPorUsuario($id_imparticion, $sesion->id, $id_empresa, $usu->rut);
					if ($usuario_chequeado) {
						BorroRegistrosInscritosCheckinPorSesionImparticionPorUsuario($id_imparticion, $id_empresa, $sesion->id, $usu->rut);
						sleep(2);
						echo "Ausente";
					}
				}
				
				echo "<script>location.href='?sw=VeColaboradoresXImpXsess&i=" . Encodear3($id_imparticion) . "';</script>";
			}
			
			$avance = CalculaAsistenciaEnBaseASesiones($usu->rut, $id_imparticion, $id_empresa);
			
			$datos_imparticion = DatosInscripcionImparticionesV2($id_imparticion, $id_empresa);
			
			$existe_en_cierre = VerificaEnTablaCierreCursoEmpresRutInscripcion($datos_imparticion[0]->id_curso, $usu->rut, $id_empresa, $id_imparticion);
			if ($existe_en_cierre) {
				actualizaTablaCierreRut($usu->rut, $datos_imparticion[0]->id_curso, $id_imparticion, round($avance), $id_empresa);
			}
			else {
				InsertaTablaCierreRutCursoImparticionAsistenciaEmpresa($usu->rut, $datos_imparticion[0]->id_curso, $id_imparticion, round($avance), $id_empresa);
			}
		}
	}
		else if ($seccion == "GuardaAsistenciasPorImparticionAsisNota"){
		$id_empresa = $_SESSION["id_empresa"];
		$id_imparticion = Decodear3($post["id_imparticion"]);

		$total_usuarios_por_inscripcion = IMPARTICION_UsuariosPorInscripcionConDatos($id_imparticion, $id_empresa);
		foreach ($total_usuarios_por_inscripcion as $usu) {
			$datos_imparticion = DatosInscripcionImparticionesV2($id_imparticion, $id_empresa);
			
			$existe_en_cierre = VerificaEnTablaCierreCursoEmpresRutInscripcion($datos_imparticion[0]->id_curso, $usu->rut, $id_empresa, $id_imparticion);
			if ($existe_en_cierre) {
				actualizaTablaCierreRut($usu->rut, $datos_imparticion[0]->id_curso, $id_imparticion, $post["avance_" . $usu->rut], $id_empresa, $post["nota_" . $usu->rut]);
			}
			else {
				InsertaTablaCierreRutCursoImparticionAsistenciaEmpresa($usu->rut, $datos_imparticion[0]->id_curso, $id_imparticion, $post["avance_" . $usu->rut], $id_empresa, $post["nota_" . $usu->rut]);
			}
		}
		echo "<script>location.href='?sw=VeColaboradoresXImp2021&i=" . Encodear3($id_imparticion) . "';</script>";
	}
		
		else if ($seccion == "GuardaAsistenciasPorImparticionAsisNota_2021"){
		$id_empresa = $_SESSION["id_empresa"];
		$id_imparticion = Decodear3($post["id_imparticion"]);

		
		
		$total_usuarios_por_inscripcion = IMPARTICION_UsuariosPorInscripcionConDatos($id_imparticion, $id_empresa);
		$datos_imparticion = DatosInscripcionImparticionesV2($id_imparticion, $id_empresa);
		foreach ($total_usuarios_por_inscripcion as $usu) {
			$existe_en_cierre = VerificaEnTablaCierreCursoEmpresRutInscripcion($datos_imparticion[0]->id_curso, $usu->rut, $id_empresa, $id_imparticion);
			
			$estado = "3";
			if ($post["estado_" . $usu->rut] == "APROBADO") {
				$estado = "1";
			}
			if ($post["estado_" . $usu->rut] == "REPROBADO") {
				$estado = "1";
			}
			
			
			$id_curso = $datos_imparticion[0]->id_curso;
			$DatosCursoImparticion2021 = DatosCursoImparticion2021($datos_imparticion[0]->id_curso, $id_imparticion);
			UpdateLmsReportes_Insert_update_2021($usu->rut, $DatosCursoImparticion2021[0]->id_foco, $DatosCursoImparticion2021[0]->id_clasificacion, $datos_imparticion[0]->id_curso, $id_empresa, $DatosCursoImparticion2021[0]->modalidadcurso, $DatosCursoImparticion2021[0]->id_programa, $DatosCursoImparticion2021[0]->id_malla, $id_imparticion);
			
			if ($existe_en_cierre) {
				
				if ($datos_imparticion[0]->minimo_nota_aprobacion > 0) {
					if ($post["nota_" . $usu->rut] >= $datos_imparticion[0]->minimo_nota_aprobacion) {
						$post["estado_" . $usu->rut] = "APROBADO";
					}
					else {
						$post["estado_" . $usu->rut] = "REPROBADO";
					}
				}
				
				actualizaTablaCierreRut_2021($usu->rut, $datos_imparticion[0]->id_curso, $id_imparticion, $post["avance_" . $usu->rut], $id_empresa, $post["nota_" . $usu->rut], $post["nota_diagnostico_" . $usu->rut], $estado, $post["estado_" . $usu->rut]);
			}
			else {
				InsertaTablaCierreRutCursoImparticionAsistenciaEmpresa_2021($usu->rut, $datos_imparticion[0]->id_curso, $id_imparticion, $post["avance_" . $usu->rut], $id_empresa, $post["nota_" . $usu->rut], $post["nota_diagnostico_" . $usu->rut], $estado, $post["estado_" . $usu->rut]);
			}
			
			
			ImparticionSesionesAsistenciaPorSesion_2021($id_imparticion, $usu->rut, $post["ses_1_" . $usu->rut], $post["ses_2_" . $usu->rut], $post["ses_3_" . $usu->rut], $post["ses_4_" . $usu->rut], $post["ses_5_" . $usu->rut], $post["ses_6_" . $usu->rut], $post["ses_7_" . $usu->rut], $post["ses_8_" . $usu->rut], $post["ses_9_" . $usu->rut], $post["ses_10_" . $usu->rut], $post["ses_11_" . $usu->rut], $post["ses_12_" . $usu->rut]);
		}
		
		$archivo = "";
		if (count($_FILES) > 0) {
			foreach ($_FILES as $keyFile => $valueFile) {
				$archivo = "";

				$keyFile_Array = explode("_", $keyFile);
				$rut_save = Decodear3($keyFile_Array[1]);
				
				if ($valueFile["name"] <> "") {
					
					VerificaExtensionFilesAdmin($valueFile["name"]);
					$rand = rand(11111111, 99999999);
					$destino_enc = "certificado_" . $id_imparticion . "_" . $rut_save . "_" . $rand . ".pdf";
					if (copy($valueFile["tmp_name"], "../front/docs/" . $destino_enc)) {
						$archivo = $destino_enc;
						
						actualizaArchivoImparticionRut_2021($rut_save, $id_imparticion, $archivo);
					}
					else {
						$error_grave = "Error Al subir Archivo<br />";
					}
				}
			}
		}
		echo "<script>location.href='?sw=VeColaboradoresXImp2021&i=" . Encodear3($id_imparticion) . "';</script>";
	}
		
		else if ($seccion == "GuardaAsistenciasPorImparticionAsisNota_Manual_2021"){
		$id_empresa = $_SESSION["id_empresa"];
		$id_imparticion = Decodear3($post["id_imparticion"]);
		
		$datos_imparticion = DatosInscripcionImparticionesV2($id_imparticion, $id_empresa);
		$total_usuarios_por_inscripcion = IMPARTICION_UsuariosPorInscripcionConDatos($id_imparticion, $id_empresa);
		foreach ($total_usuarios_por_inscripcion as $usu) {
			
			$existe_en_cierre = VerificaEnTablaCierreCursoEmpresRutInscripcion($datos_imparticion[0]->id_curso, $usu->rut, $id_empresa, $id_imparticion);
			$id_curso = $datos_imparticion[0]->id_curso;
			$DatosCursoImparticion2021 = DatosCursoImparticion2021($datos_imparticion[0]->id_curso, $id_imparticion);
			if ($existe_en_cierre) {
				actualizaTablaCierreRut_2021_SoloEstado($usu->rut, $datos_imparticion[0]->id_curso, $id_imparticion, $AvanceSesion, $id_empresa, $post["nota_" . $usu->rut], $post["nota_diagnostico_" . $usu->rut], $estado, $post["estado_" . $usu->rut]);
			}
			else {
				InsertaTablaCierreRutCursoImparticionAsistenciaEmpresa_2021($usu->rut, $datos_imparticion[0]->id_curso, $id_imparticion, $post["avance_" . $usu->rut], $id_empresa, $post["nota_" . $usu->rut], $post["nota_diagnostico_" . $usu->rut], $estado, $post["estado_" . $usu->rut]);
			}
			
			
			Update_Observaciones_RutIdImparticion_2023($usu->rut, $id_imparticion, $post["observaciones_" . $usu->rut . "_" . $post["id_imparticion"]]);
			
		}
		
		echo "<script>location.href='?sw=VeColaboradoresXImp2021&i=" . Encodear3($id_imparticion) . "&manual=1';</script>";
	}
		
		else if ($seccion == "sgd_reportes_capacitacion") {
		$id_empresa = $_SESSION["id_empresa"];
		$arreglo_post = $post;
		$array_para_enviar_via_url = serialize($arreglo_post);
		$array_para_enviar_via_url = urlencode($array_para_enviar_via_url);
		$filtros_superiores = "";
		$exportar_a_excel = $post["excel"];
		
		$arreglo_post = sgd_buscar_proceso($id_empresa);
		
		if (count($arreglo_post) > 0) {
		if ($exportar_a_excel == 1) {
		$fechahoy = date("Y-m-d");
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=Reporte_Seguimiento_SGD_" . $fechahoy . ".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
	?>
		<table id="tabla1" class="table table-bordered table-hover table-condensed">
		<tr>
			<th class="bg_gray" width="20%">RutEvaluado</th>
			<th class="bg_gray" width="20%">Evaluado</th>

			<th class="bg_gray" width="20%">Gerencia Evaluado</th>
			<th class="bg_gray" width="20%">Local Evaluado</th>
			<th class="bg_gray" width="20%">Zonal</th>
			<th class="bg_gray" width="20%">Gerente RF</th>


			<th class="bg_gray" width="20%">RutEvaluador</th>
			<th class="bg_gray" width="10%">Evaluador</th>
			<th class="bg_gray" width="10%">Tipo</th>
			<th class="bg_gray" width="10%">Estado</th>
		</tr>
	<?php
		ReporteSGDSeguimiento(FuncionesTransversalesAdmin("views/sgd/entorno_sgd_excel.html"), $id_empresa, $arreglo_post, "", $exportar_a_excel);
		echo($PRINCIPAL);
		exit;
		}
		else {
			$PRINCIPAL = ReporteSGDSeguimiento(FuncionesTransversalesAdmin(file_get_contents("views/sgd/" . $id_empresa . "_entorno_sgd_sin_listado_usuarios.html")), $id_empresa, $arreglo_post, "");
		}
		}
		else {
			$PRINCIPAL = ReporteSGDSeguimiento(FuncionesTransversalesAdmin(file_get_contents("views/sgd/entorno_sgd_previa.html")), $id_empresa, "", "");
		}
		
		$PRINCIPAL = str_replace("{FILTROS_SELECCIONADOS}", $filtros_superiores, $PRINCIPAL);
		echo CleanHTMLWhiteList($PRINCIPAL);
		exit;
		
		$datos_empresa = DatosEmpresa($id_empresa);
		$campos_Empresa = DatosEmpresaCampos($id_empresa);
		
		if (count($arreglo_post) > 0 && $exportar_a_excel != 1) {
	?>

		<script type="text/javascript">
			$(window).load(function () {
				$("#capaResultadosGenerales").load('?sw=lms_reportes_sgd_por_criterio_resultados_geneles&campo_criterio=<?php echo $campos_Empresa[0]->campo2; ?>&nombre_campo=c2&array_post=<?php echo $array_para_enviar_via_url; ?>
				');
			});
		</script>

		<script type="text/javascript">
			$(window).load(function () {
				$("#capa2").load('?sw=lms_reportes_sgd_por_criterio&campo_criterio=<?php echo $campos_Empresa[0]->campo1; ?>&nombre_campo=c1&array_post=<?php echo $array_para_enviar_via_url; ?>
				');
			});
		</script>

		<script type="text/javascript">
			$(window).load(function () {
				$("#capa3").load('?sw=lms_reportes_sgd_por_criterio&campo_criterio=<?php echo $campos_Empresa[0]->campo2; ?>&nombre_campo=c2&array_post=<?php echo $array_para_enviar_via_url; ?>
				');
			});
		</script>

		<script type="text/javascript">
			$(window).load(function () {
				$("#capa4").load('?sw=lms_reportes_sgd_por_criterio&campo_criterio=<?php echo $campos_Empresa[0]->campo3; ?>&nombre_campo=c3&array_post=<?php echo $array_para_enviar_via_url; ?>
				');
			});
		</script>
		
		<?php
	}
	}
	elseif ($seccion == "sgd_resultados") {
    $id_empresa = $_SESSION["id_empresa"];
    $arreglo_post = $post;
    $array_para_enviar_via_url = serialize($arreglo_post);
    $array_para_enviar_via_url = urlencode($array_para_enviar_via_url);
    $filtros_superiores = "";
    $exportar_a_excel = $post["excel"];

    if ($arreglo_post["tipo_reporte"] == "detalle") {
        $fechahoy = date("Y-m-d");
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Reporte_Detalle_Resultado_SGD_" . $fechahoy . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        $PRINCIPAL = ReporteSGDResultadosEvaluadoEvaluador(FuncionesTransversalesAdmin(file_get_contents("views/sgd/entorno_sgd_resultado_usuarios_detalle_excel.html")), $id_empresa, $arreglo_post, "");
        echo ($PRINCIPAL);
        exit;
    } else {
        if ($exportar_a_excel == 1) {
            $fechahoy = date("Y-m-d");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=Reporte_Detalle_Resultado_SGD_" . $fechahoy . ".xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            $PRINCIPAL = ReporteSGDResultadosEvaluadoEvaluador(FuncionesTransversalesAdmin(file_get_contents("views/sgd/entorno_sgd_resultado_usuarios_excel.html")), $id_empresa, $arreglo_post, "", $exportar_a_excel);
            echo CleanHTMLWhiteList($PRINCIPAL);exit;
            exit;
        } else {
            $PRINCIPAL = ReporteSGDResultadosEvaluadoEvaluador(FuncionesTransversalesAdmin(file_get_contents("views/sgd/entorno_sgd_resultado_usuarios.html")), $id_empresa, $arreglo_post, "");
        }
    }

    $PRINCIPAL = str_replace("{FILTROS_SELECCIONADOS}", $filtros_superiores, $PRINCIPAL);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;

    $datos_empresa = DatosEmpresa($id_empresa);
    $campos_Empresa = DatosEmpresaCampos($id_empresa);

    if (count($arreglo_post) > 0 && $exportar_a_excel != 1) {
        ?>

<script type="text/javascript">
    $(window).load(function() {
$("#capa2").load('?sw=lms_reportes_sgd_por_criterio_resultados&campo_criterio=<?php echo $campos_Empresa[0]->campo1; ?>&nombre_campo=c1&array_post=<?php echo $array_para_enviar_via_url; ?>
    ');
    });
</script>

<script type="text/javascript">
    $(window).load(function() {
$("#capa3").load('?sw=lms_reportes_sgd_por_criterio_resultados&campo_criterio=<?php echo $campos_Empresa[0]->campo2; ?>&nombre_campo=c2&array_post=<?php echo $array_para_enviar_via_url; ?>
    ');
    });
</script>

<?php
}
}
        else if ($seccion == "lms_reportes_sgd_por_criterio_resultados_geneles") {
			$PRINCIPAL="";
    $id_empresa = $_SESSION["id_empresa"];
    $criterio = $get["campo_criterio"];
    $miarray = $get["array_post"];
    $nombre_campo = $get["nombre_campo"];
    $array_para_recibir_via_url = stripslashes($miarray);
    $array_para_recibir_via_url = urldecode($array_para_recibir_via_url);
    
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
        else if ($seccion == "lms_reportes_sgd_por_criterio") {
    $id_empresa = $_SESSION["id_empresa"];
    $criterio = $get["campo_criterio"];
    $miarray = $get["array_post"];
    $nombre_campo = $get["nombre_campo"];


    $array_para_recibir_via_url = stripslashes($miarray);
    $array_para_recibir_via_url = urldecode($array_para_recibir_via_url);
    $arreglo_post = unserialize($array_para_recibir_via_url,['allowed_classes' => false]);
    $PRINCIPAL = ReporteCapacitacionPorcriterioSGD(FuncionesTransversalesAdmin(file_get_contents("views/sgd/entorno_capacitacion_por_criterio.html")), $id_empresa, $arreglo_post, $criterio, $nombre_campo, "si");

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
        else if ($seccion == "lms_reportes_sgd_por_criterio_resultados") {
    $id_empresa = $_SESSION["id_empresa"];
    $criterio = $get["campo_criterio"];
    $miarray = $get["array_post"];
    $nombre_campo = $get["nombre_campo"];


    $array_para_recibir_via_url = stripslashes($miarray);
    $array_para_recibir_via_url = urldecode($array_para_recibir_via_url);
    $arreglo_post = unserialize($array_para_recibir_via_url,['allowed_classes' => false]);
    $PRINCIPAL = ReporteCapacitacionPorcriterioSGDResultados(FuncionesTransversalesAdmin(file_get_contents("views/sgd/entorno_resultados_por_criterio.html")), $id_empresa, $arreglo_post, $criterio, $nombre_campo, "si");

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
        else if ($seccion == "lms_reportes_capacitacion_por_criterio") {
    $id_empresa = $_SESSION["id_empresa"];
    $criterio = $get["campo_criterio"];
    $miarray = $get["array_post"];
    $nombre_campo = $get["nombre_campo"];

    $array_para_recibir_via_url = stripslashes($miarray);
    $array_para_recibir_via_url = urldecode($array_para_recibir_via_url);
    $arreglo_post = unserialize($array_para_recibir_via_url,['allowed_classes' => false]);
    $PRINCIPAL = ReporteCapacitacionPorcriterio(FuncionesTransversalesAdmin(file_get_contents("views/reportes/capacitacion/entorno_capacitacion_por_criterio.html")), $id_empresa, $arreglo_post, $criterio, $nombre_campo, "si");

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
        else if ($seccion == "MuestraBox") {
    $id_empresa = $_SESSION["id_empresa"];
    $criterio = $get["campo_criterio"];
    $miarray = $get["array_post"];
    $nombre_campo = $get["nombre_campo"];

    $array_para_recibir_via_url = stripslashes($miarray);
    $array_para_recibir_via_url = urldecode($array_para_recibir_via_url);
    $arreglo_post = unserialize($array_para_recibir_via_url,['allowed_classes' => false]);

    $PRINCIPAL = ReporteSGDResultadosEvaluadoEvaluador(FuncionesTransversalesAdmin(file_get_contents("views/sgd/box/principal.html")), $id_empresa, $arreglo_post, "", "", "box");

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "lms_reportes_capacitacion_por_criterio_resultados_genelesTrivia") {
    $id_empresa = $_SESSION["id_empresa"];
    $criterio = $get["campo_criterio"];
    $miarray = $get["array_post"];
    $nombre_campo = $get["nombre_campo"];

    $array_para_recibir_via_url = stripslashes($miarray);
    $array_para_recibir_via_url = urldecode($array_para_recibir_via_url);
    $arreglo_post = unserialize($array_para_recibir_via_url,['allowed_classes' => false]);
    $PRINCIPAL = ReporteResultadosGeneralesConsolidadoTrivias(FuncionesTransversalesAdmin(file_get_contents("views/reportes/capacitacion/entorno_consolidado_resultados_trivias.html")), $id_empresa, $arreglo_post, $criterio, $nombre_campo);

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "lms_reportes_capacitacion_por_criterio_resultados_genelesPorMalla") {
    $id_empresa = $_SESSION["id_empresa"];
    $criterio = $get["campo_criterio"];
    $miarray = $get["array_post"];
    $nombre_campo = $get["nombre_campo"];

    $array_para_recibir_via_url = stripslashes($miarray);
    $array_para_recibir_via_url = urldecode($array_para_recibir_via_url);
    $arreglo_post = unserialize($array_para_recibir_via_url,['allowed_classes' => false]);
    if ($arreglo_post["foco"] or $arreglo_post["programabbdd"] or $arreglo_post["malla"]) {
        $malla = REL_MALLA_CURSO_TraeMallasDadoFoco($id_empresa, $arreglo_post);

        if ($malla) {
            $grafico_por_malla = file_get_contents("views/reportes/capacitacion/entorno_consolidado_resultados_generales_malla_encabezado.html");
            $arreglo_post["malla"] = "";

            $grafico_por_malla_malla .= ReporteResultadosGeneralesConsolidado(FuncionesTransversalesAdmin(file_get_contents("views/reportes/capacitacion/entorno_consolidado_resultados_generales_general.html")), $id_empresa, $arreglo_post, $criterio, $nombre_campo, "general");
            $grafico_por_malla_malla = str_replace("{NOMBRE_MALLA}", "General", $grafico_por_malla_malla);
            $grafico_por_malla_malla = str_replace("{CODIGO_MALLA}", "", $grafico_por_malla_malla);

            foreach ($malla as $mall) {
                $arreglo_post["malla"] = $mall->id_malla;
                $grafico_por_malla_malla .= ReporteResultadosGeneralesConsolidado(FuncionesTransversalesAdmin(file_get_contents("views/reportes/capacitacion/entorno_consolidado_resultados_generales_malla.html")), $id_empresa, $arreglo_post, $criterio, $nombre_campo);
                $grafico_por_malla_malla = str_replace("{NOMBRE_MALLA}", ($mall->nombre), $grafico_por_malla_malla);
                $grafico_por_malla_malla = str_replace("{CODIGO_MALLA}", ($mall->id_malla), $grafico_por_malla_malla);
            }
            $grafico_por_malla = str_replace("{ROW_RESUMENES_POR_MALLA}", $grafico_por_malla_malla, $grafico_por_malla);
        }
    }


    echo $grafico_por_malla;
}
else if ($seccion == "lms_reportes_capacitacion_por_criterio_resultados_geneles") {
    $id_empresa = $_SESSION["id_empresa"];
    $criterio = $get["campo_criterio"];
    $miarray = $get["array_post"];
    $nombre_campo = $get["nombre_campo"];

    $array_para_recibir_via_url = stripslashes($miarray);
    $array_para_recibir_via_url = urldecode($array_para_recibir_via_url);
    $arreglo_post = unserialize($array_para_recibir_via_url,['allowed_classes' => false]);
    if ($id_empresa == 52) {
        $PRINCIPAL = ReporteResultadosGeneralesConsolidado(FuncionesTransversalesAdmin(file_get_contents("views/reportes/capacitacion/52_entorno_consolidado_resultados_generales.html")), $id_empresa, $arreglo_post, $criterio, $nombre_campo);
    } else {
        $PRINCIPAL = ReporteResultadosGeneralesConsolidado(FuncionesTransversalesAdmin(file_get_contents("views/reportes/capacitacion/entorno_consolidado_resultados_generales.html")), $id_empresa, $arreglo_post, $criterio, $nombre_campo);
    }

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "filtro_malla_por_foco") {
    $foco = $post["foco"];
    $arreglo_post = $post;
    $id_empresa = $_SESSION["id_empresa"];
//Traigo los programas, de la tabla rel_lms_malla_curso con foco y empresa
    $programas = REL_MALLA_CURSO_TraeMallasDadoFoco($id_empresa, $arreglo_post);
    $options = "";
    $options .= "<option value='0'>Todas</option>";
    foreach ($programas as $prog) {
        $options .= "<option value='" . $prog->id_malla . "'>" . $prog->nombre . " (" . $prog->id_malla . ")</option>";
    }
    echo ($options);
}
else if ($seccion == "filtro_malla_por_PROGRAMAbbddo") {
    $programabbdd = $post["programabbdd"];
    $arreglo_post = $post;
    $id_empresa = $_SESSION["id_empresa"];
//Traigo los programas, de la tabla rel_lms_malla_curso con foco y empresa
    $programas = REL_MALLA_CURSO_TraeMallasDadoFoco($id_empresa, $arreglo_post);
    $options = "";
    $options .= "<option value='0'>Todas</option>";
    foreach ($programas as $prog) {
        $options .= "<option value='" . $prog->id_malla . "'>" . $prog->nombre . " (" . $prog->id_malla . ")</option>";
    }
    echo ($options);
}
 elseif ($seccion == "filtro_programabbdd_por_foco_creacion_cursosElearning") {
    $foco = $post["foco"];
    $arreglo_post = $post;
    $id_empresa = $_SESSION["id_empresa"];

    // Traigo los programas, de la tabla rel_lms_malla_curso con foco y empresa

    $programas = TraeProgramasDadoFocoDeTablaProgramaElearningSoloGlobal($id_empresa, $foco);
    $options = '<script src="js/bootstrap-select.js"></script>';
    $options .= '<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />';
    $options .= '<link href="css/go_admin.css" rel="stylesheet" type="text/css" />';
    $options .= "<select class='form-control'  name='programa_bbdd_global' id='programa_bbdd_global' required='required'  onchange='ColocaProgramasElearningPorFoco();'>";
    $options .= "<option value='0'>Todos</option>";
    foreach ($programas as $prog) {
        $options .= "<option value='" . $prog->id_programa . "'>" . $prog->nombre_programa . "</option>";
    }

    $options .= "</select>";
    echo ($options);
}
else if ($seccion == "filtro_programabbdd_por_foco_creacion_cursosElearningDadoProgGlobal") {
    
    $programa = $post["programa_bbdd_global"];
    $arreglo_post = $post;
    $id_empresa = $_SESSION["id_empresa"];

    // Traigo los programas, de la tabla rel_lms_malla_curso con foco y empresa

    $programas = TraeProgramasDadoFocoDeTablaProgramaElearningDadoGlobalDistinct($id_empresa, $programa);
    $options = '<script src="js/bootstrap-select.js"></script>';
    $options .= '<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />';
    $options .= '<link href="css/go_admin.css" rel="stylesheet" type="text/css" />';
    $options .= "<select class='form-control'  name='programa_bbdd_elearning' id='programa_bbdd_elearning' required='required' >";
    $options .= "<option value='0'>Todos</option>";
    foreach ($programas as $prog) {
        $options .= "<option value='" . $prog->id_programa . "'>" . $prog->nombre_programa . "</option>";
    }

    $options .= "</select>";
    echo ($options);
}
else if ($seccion == "mallapordivision") {
    $id_tipo = $post["id_tipo"];

    $id_empresa = $_SESSION["id_empresa"];
//Traigo los programas, de la tabla rel_lms_malla_curso con foco y empresa
    $id_dptos = BuscaIdMalladadaIdTipo($id_empresa, $id_tipo);

    $options = "";
    $options .= "<option value=''></option>";
    foreach ($id_dptos as $dptos) {
        $options .= "<option value='" . $dptos->id_malla . "'>" . $dptos->id_malla . "</option>";
    }

    echo ($options);
}
else if ($seccion == "jefepormallapordivision") {
    $id_tipo = $post["id_tipo"];
    $id_malla = $post["id_malla"];

    $id_empresa = $_SESSION["id_empresa"];
//Traigo los programas, de la tabla rel_lms_malla_curso con foco y empresa
    $id_dptos = BuscaJefeIdMalladadaIdTipo($id_empresa, $id_tipo, $id_malla);

    $options = "";
    $options .= "<option value=''></option>";
    foreach ($id_dptos as $dptos) {
        $options .= "<option value='" . $dptos->id_foco . "'>" . $dptos->id_programa . "</option>";
    }

    echo ($options);
}
else if ($seccion == "filtro_programaGlobal_por_foco") {
    $foco = $post["foco"];
    $e = $post["ejecutivo"];
    $arreglo_post = $post;

    $id_empresa = $_SESSION["id_empresa"];
//Traigo los programas, de la tabla rel_lms_malla_curso con foco y empresa
    $programas = TraeProgramasGlobalDadoFoco($id_empresa, $arreglo_post);

    $options = "";
    $options .= "<option value='0'>Todos</option>";
    foreach ($programas as $prog) {
        $options .= "<option value='" . $prog->id_programa . "'>" . $prog->nombre_programa . "</option>";
    }
    echo ($options);
}
else if ($seccion == "filtro_programabbdd_elearning_por_foco_programa") {
    $foco = $post["foco"];
    $e = $post["ejecutivo"];
    $arreglo_post = $post;
    $id_empresa = $_SESSION["id_empresa"];

//Traigo los programas, de la tabla rel_lms_malla_curso con foco y empresa
    $programas = TraeProgramasElearningDadoFocoProgramaGlobal($id_empresa, $arreglo_post[foco], $arreglo_post[programaglobal]);

    $options = "";
    $options .= "<option value='0'>Todos</option>";
    foreach ($programas as $prog) {
        $options .= "<option value='" . $prog->id_programa . "'>" . $prog->nombre_programa . "</option>";
    }
    echo ($options);
}
else if ($seccion == "filtro_programabbdd_por_foco") {
    $foco = $post["foco"];
    $e = $post["ejecutivo"];
    $arreglo_post = $post;

    $id_empresa = $_SESSION["id_empresa"];
//Traigo los programas, de la tabla rel_lms_malla_curso con foco y empresa
    $programas = REL_MALLA_CURSO_TraeProgramasDadoFoco($id_empresa, $arreglo_post);

    $options = "";
    $options .= "<option value='0'>Todos</option>";
    foreach ($programas as $prog) {
        $options .= "<option value='" . $prog->id_programa . "'>" . $prog->nombre . "</option>";
    }
    echo ($options);
}
else if ($seccion == "filtro_objetos_por_foco") {
    $arreglo_post = $post;

    $id_empresa = $_SESSION["id_empresa"];
//Traigo los programas, de la tabla rel_lms_malla_curso con foco y empresa
    $programas = REL_MALLA_CURSO_TraeObjetosDadoFoco($id_empresa, $arreglo_post);
    $options = "";
    $options .= "<option value='0'>Todos</option>";
    foreach ($programas as $prog) {
        $options .= "<option value='" . $prog->id . "'>" . $prog->titulo . "</option>";
    }
    echo ($options);
}
else if ($seccion == "filtro_ejecutivos_reportes") {
    $arreglo_post = $post;
    $id_empresa = $_SESSION["id_empresa"];
//Traigo los programas, de la tabla rel_lms_malla_curso con foco y empresa
    $imparticiones = REL_MALLA_CURSO_TraeImparticionesDadoFoco($id_empresa, $arreglo_post);
    $options = "";
    $options .= "<option value='0'>Todas</option>";
    foreach ($imparticiones as $prog) {
        $options .= "<option value='" . $prog->codigo_inscripcion . "'>" . $prog->codigo_inscripcion . "</option>";
    }
    echo ($options);
}
else if ($seccion == "filtro_imparticion_por_foco") {
    $foco = $post["foco"];
    $arreglo_post = $post;
    $id_empresa = $_SESSION["id_empresa"];
//Traigo los programas, de la tabla rel_lms_malla_curso con foco y empresa
    $programas = REL_MALLA_CURSO_TraeImparticionesDadoFoco($id_empresa, $arreglo_post);
    $options = "";
    $options .= "<option value='0'>Todas</option>";
    foreach ($programas as $prog) {
        $options .= "<option value='" . $prog->codigo_inscripcion . "'>" . $prog->codigo_inscripcion . "</option>";
    }
    echo ($options);
}
else if ($seccion == "filtro_curso_por_foco_id") {
    $foco = $post["foco"];
    $arreglo_post = $post;
    $id_empresa = $_SESSION["id_empresa"];
//Traigo los programas, de la tabla rel_lms_malla_curso con foco y empresa
    $programas = REL_MALLA_CURSO_TraeCursosDadoFoco($id_empresa, $arreglo_post);

    $options = "";
    $options .= "<option value='0'>Todos</option>";
    foreach ($programas as $prog) {
        $options .= "<option value='" . $prog->id_curso . "'>" . $prog->id_curso . "</option>";
    }
    echo ($options);
}

else if ($seccion == "filtro_curso_por_foco") {
    $foco = $post["foco"];
    $arreglo_post = $post;
    $id_empresa = $_SESSION["id_empresa"];
//Traigo los programas, de la tabla rel_lms_malla_curso con foco y empresa
    $programas = REL_MALLA_CURSO_TraeCursosDadoFoco($id_empresa, $arreglo_post);

    $options = "";
    $options .= "<option value='0'>Todos</option>";
    foreach ($programas as $prog) {
        $options .= "<option value='" . $prog->id_curso . "'>" . $prog->nombre . " (" . $prog->id_curso . ")</option>";
    }
    echo ($options);
}
else if ($seccion == "filtro_ejecutivos_por_programas") {
    $ejecutivo = $post["ejecutivo"];
    $id_empresa = $_SESSION["id_empresa"];

    $programas = ProgramasPorEjecutivoEmpresa($ejecutivo, $id_empresa);
    $options = "";
    $options .= "<option value='0'>Todos</option>";
    foreach ($programas as $prog) {
        $options .= "<option value='" . $prog->id_programa . "'>" . $prog->nombre_programa . " (" . $prog->id_programa . ")</option>";
    }
    echo ($options);
}
else if ($seccion == "filtro_ejecutivos_por_mallas") {
    $ejecutivo = $post["ejecutivo"];
    $id_empresa = $_SESSION["id_empresa"];

    $mallas = MallasPorEjecutivoEmpresa($ejecutivo, $id_empresa);
    $options = "";
    $options .= "<option value='0'>Todos</option>";
    foreach ($mallas as $mall) {
        $options .= "<option value='" . $mall->id_malla . "'>" . $mall->nombre_malla . " (" . $mall->id_malla . ")</option>";
    }
    echo ($options);
}
else if ($seccion == "filtro_ejecutivos_por_clasificacion") {
    $ejecutivo = $post["ejecutivo"];
    $id_empresa = $_SESSION["id_empresa"];

    $clasificaciones = ClasificacionPorEjecutivoEmpresa($ejecutivo, $id_empresa);
    $options = "";
    $options .= "<option value='0'>Todos</option>";
    foreach ($clasificaciones as $cla) {
        $options .= "<option value='" . $cla->id_clasificacion . "'>" . $cla->nombre_clasificacion . " (" . $cla->id_clasificacion . ")</option>";
    }
    echo ($options);
}
else if ($seccion == "filtro_ejecutivos_por_foco") {
    $ejecutivo = $post["ejecutivo"];
    $id_empresa = $_SESSION["id_empresa"];

    $focos = FocosPorEjecutivoEmpresa($ejecutivo, $id_empresa);
    $options = "";
    $options .= "<option value='0'>Todos</option>";
    foreach ($focos as $foc) {
        $options .= "<option value='" . $foc->id_foco . "'>" . $foc->descripcion . " (" . $foc->id_foco . ")</option>";
    }
    echo ($options);
}
else if ($seccion == "filtro_ejecutivos_por_curso") {
    $ejecutivo = $post["ejecutivo"];
    $id_empresa = $_SESSION["id_empresa"];

    $cursos = CursosPorEjecutivo($ejecutivo);
    $options = "";
    $options .= "<option value='0'>Todos</option>";
    foreach ($cursos as $cur) {
        $options .= "<option value='" . $cur->id_curso . "'>" . $cur->nombre_curso . " (" . $cur->id_curso . ")</option>";
    }
    echo ($options);
}
else if ($seccion == "filtro_clasificacion_por_foco") {
    $foco = $post["foco"];
    $arreglo_post = $post;
    $id_empresa = $_SESSION["id_empresa"];
    $options .= "<option value='0'>Todas</option>";
//Traigo los programas, de la tabla rel_lms_malla_curso con foco y empresa
    $clasificacion = REL_MALLA_CURSO_TraeClasificacionesDadoFoco($id_empresa, $arreglo_post);
    $options = "";
    $options .= "<option value='0'>Todas</option>";

    foreach ($clasificacion as $valor) {
        $options .= "<option value='" . $valor->id_clasificacion . "'>" . $valor->nombre . " (" . $valor->id_clasificacion . ")</option>";
    }
    echo ($options);
}
else if ($seccion == "filtro_objetos_por_curso") {
    $arreglo_post = $post;
    $id_empresa = $_SESSION["id_empresa"];
    $objetos = REL_MALLA_CURSO_TraeObjetosDadoFoco($id_empresa, $arreglo_post);
    $options .= "<option value='0'>Todos</option>";
    foreach ($objetos as $valor) {
        $options .= "<option value='" . $valor->id_objeto . "'>" . $valor->titulo . " - (" . $valor->id_objeto . ")</option>";
    }
    echo ($options);
}
else if ($seccion == "filtros_dinamicos_por_campos") {
    $id_empresa = $_SESSION["id_empresa"];
    $datos_empresa = DatosEmpresa($id_empresa);
    $arreglo_post = $post;
    print_r($arreglo_post);
//traigo el total de campos de subida para el filtro
    $campos_subida = REL_MALLA_CURSO_trarCamposSubidaParaReporte($id_empresa);
    $total_vueltas = ceil(count($campos_subida) / 4);
    $html_filtros = "";
    $vuelta = 1;

    foreach ($campos_subida as $campo) {
        if ($vuelta == 1) {
            $html_filtros .= "<div class=''>";
        }
        $html_filtros .= file_get_contents("views/reportes/capacitacion/filtros_dinamicos.html");
        $html_filtros = str_replace("{NOMBRECAMPOAMOSTRAR}", ($campo->nombrecampoamostrar), $html_filtros);
        $html_filtros = str_replace("{ID}", ($campo->id), $html_filtros);
        $html_filtros = str_replace("{CAMPO}", ($campo->campo), $html_filtros);
        $valores_option = TraigoValoresDadoCampoPorEmpreas($campo->campo, $id_empresa, $arreglo_post);
        $options = "";
        $options .= "<option value='0'>Todos</option>";
        foreach ($valores_option as $valor) {
            if ($post[$campo->campo]) {
                for ($contador_valores = 0; $contador_valores < count($post[$campo->campo]); $contador_valores++) {

                    if ($post[$campo->campo][$contador_valores] == $valor->valor) {
                        $selected = "selected='selected'";
                        break;
                    } else {
                        $selected = "";
                    }
                }
            }
            $selected = "selected='selected'";
            $options .= "<option $selected  value='" . $valor->valor . "'>" . $valor->valor . "</option>";
        }
        $html_filtros = str_replace("{OPTIONS}", ($options), $html_filtros);
        if ($vuelta == 4) {
            $html_filtros .= "</div>";
            $vuelta = 1;
        } else {
            $vuelta++;
        }
    }

    echo ($html_filtros);
}
else if ($seccion == "lms_reportes_capacitacion_Full") {
    $id_empresa = $_SESSION["id_empresa"];
    $fechahoy = date("Y-m-d");
    $ejecutivo = $post["ejecutivo"];
    $nombre_ejecutivo = buscanombre($ejecutivo);
    $fecha_inicio = $post["fecha_inicio"];
    $fecha_termino = $post["fecha_termino"];

    $txt_e = "";
    $txt_fi = "";
    $txt_ft = "";

    if ($ejecutivo != '') {$txt_e = "_" . $nombre_ejecutivo[0]->nombre_completo;}
    if ($fecha_inicio != '') {$txt_fi = "_" . $fecha_inicio;}
    if ($fecha_termino != '') {$txt_ft = "_" . $fecha_termino;}

    $txt = $txt_e . $txt_fi . $txt_ft;

    header('Content-type: text/plain');
    header('Content-Disposition: attachment; filename=Reporte_Full_' . $txt . '.txt');
    echo "CORRELATIVO IMPARTICION;RUT PARTICIPANTE;NOMBRE COMPLETO;CARGO;UNIDAD (CENCO);DEPENDENCIA (R3);FONDO (R2);GERENCIA (R1);EMPRESA;JEFE PERS.;JEFE PERS. NOMBRE;EJECUTIVO; EJECUTIVO NOMBRE;FOCO ESTRATEGICO;PROGRAMA;NOMBRE INTERNO IMPARTICION;TIPO CURSO;ANNO INICIO;MES INICIO;FECHA INICIO;ANNO FIN;MES TERMINO;FECHA TERMINO;FORMATO STATUS;ASISTENCIA;EVALUACION;HORAS TOTALES;LUGAR DE EJECUCION(DIRECCION);CURSO OPCIONAL\r\n";
    $Total_Rows_full = Tragotb_DatosdesdeFull_Filtros($ejecutivo, $fecha_inicio, $fecha_termino);

    foreach ($Total_Rows_full as $Unico) {
        $numhoras = str_replace('.', ',', $Unico->horas);

        echo $Unico->id_inscripcion . ";" . $Unico->rut_completo . ";" . $Unico->nombre . ";" . $Unico->cargo . ";" . $Unico->c4 . ";" . $Unico->c3 . ";" . $Unico->c2 . ";" . $Unico->c1 . ";" . $Unico->empresa . ";" . $Unico->rut_jefe . ";" . $Unico->jefe . ";" . $Unico->ejecutivo . ";" . $Unico->nombre_ejecutivo . ";" . $Unico->foco . ";" . $Unico->programa . ";" . $Unico->curso . ";" . $Unico->tipo_curso . ";" . $Unico->anno_inicio . ";" . $Unico->mes_inicio . ";" . $Unico->fecha_inicio . ";" . $Unico->anno_termino . ";" . $Unico->mes_termino . ";" . $Unico->fecha_termino . ";" . $Unico->status . ";" . $Unico->asistencia . ";" . $Unico->evaluacion . ";" . $numhoras . ";" . $Unico->lugar . ";" . $Unico->curso_opcional . "\r\n";
    }
}
else if ($seccion == "VeLinkEnc") {
    $i = $get["i"];
    $ienc = $get["ienc"];
    $ii = $get["ii"];
    $r = $get["r"];

    $url = $url_front . "/front/?sw=homeEncuesta&i={ID_OBJETO_ENCODEADO}&ienc={ID_ENCUESTA_ENCODEADA}&tt=2&idc=&ii={ID_INSCRIPCION_ENCODEADA}&r={RUT_USUARIO_ENCODEADO}&hl=1";
    $url = str_replace("{ID_OBJETO_ENCODEADO}", $i, $url);
    $url = str_replace("{ID_ENCUESTA_ENCODEADA}", $ienc, $url);
    $url = str_replace("{ID_INSCRIPCION_ENCODEADA}", $ii, $url);
    $url = str_replace("{RUT_USUARIO_ENCODEADO}", $r, $url);
    echo $url;
}
else if ($seccion == "lms_reportes_capacitacion_por_ejecutivos") {
    $id_empresa = $_SESSION["id_empresa"];

    if (isset($post['web'])) {
        $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/reportes/capacitacion/entorno_capacitacion_ejecutivos_con_datos.html"));
        $PRINCIPAL = ColocaDatosPorEjecutivo($PRINCIPAL, $post["ejecutivo"], $post["fecha_inicio"], $post["fecha_termino"], $id_empresa);
    } else if (isset($post['web_txt'])) {
        //export web filter
        if ($post["ejecutivo"] != '') {$txt_e = "_" . $post["ejecutivo"];}
        if ($post["fecha_inicio"] != '') {$txt_fi = "_" . $fecha_inicio;
            $txt_show_fi = " del " . $fecha_inicio;}
        if ($post["fecha_termino"] != '') {$txt_ft = "_" . $fecha_termino;
            $txt_show_ft = " al " . $fecha_termino;}
        $txt = $txt_e . $txt_fi . $txt_ft;
        header('Content-type: text/plain');
        header('Content-Disposition: attachment; filename=Reporte_por_Ejecutivo_Resumen_' . $txt . '.csv');
        echo "CODIGO IMPARTICION;EJECUTIVO;EJECUTIVO NOMBRE;FOCO ESTRATEGICO;PROGRAMA;PROGRAMA_ELEARNING;NOMBRE INTERNO IMPARTICION;TIPO CURSO;ANNO INICIO;MES INICIO;FECHA INICIO;ANNO FIN;MES TERMINO;FECHA TERMINO;FORMATO STATUS;HORAS TOTALES;LUGAR DE EJECUCION(DIRECCION);INSCRITOS;ASISTENTES;INCOMPLETOS;INASISTENTES;\r\n";
        $Total_Rows_full = ConsultaDataPorEjecutivo($post["ejecutivo"], $post["fecha_inicio"], $post["fecha_termino"]);
        foreach ($Total_Rows_full as $Unico) {
            $numhoras = str_replace('.', ',', $Unico->horas);
            echo $Unico->id_inscripcion . ";" . $Unico->rut_ejecutivo . ";" . $Unico->ejecutivo . ";" . $Unico->foco_global . ";" . $Unico->programa_global . ";" . $Unico->programa . ";" . $Unico->curso . ";" . $Unico->tipo_curso . ";" . $Unico->anno_inicio . ";" . $Unico->mes_inicio . ";" . $Unico->fecha_inicio . ";" . $Unico->anno_termino . ";" . $Unico->mes_termino . ";" . $Unico->fecha_termino . ";" . $Unico->status . ";" . $numhoras . ";" . $Unico->lugar . ";" . $Unico->inscritos . ";" . $Unico->asistentes . ";" . $Unico->incompletos . ";" . $Unico->inasistentes . "\r\n";
        }
    } else if (isset($post['txt'])) {
        //export txt action
        if ($post["ejecutivo"] != '' and $post["ejecutivo"] != '0') {$txt_e = "_" . $post["ejecutivo"];}
        if ($post["fecha_inicio"] != '') {$txt_fi = "_" . $fecha_inicio;
            $txt_show_fi = " del " . $fecha_inicio;}
        if ($post["fecha_termino"] != '') {$txt_ft = "_" . $fecha_termino;
            $txt_show_ft = " al " . $fecha_termino;}
        $txt = $txt_e . $txt_fi . $txt_ft;
        header('Content-type: text/plain');
        header('Content-Disposition: attachment; filename=Reporte_Completo_' . $txt . '.csv');
        echo "CORRELATIVO IMPARTICION;RUT PARTICIPANTE;NOMBRE COMPLETO;CARGO;UNIDAD (CENCO);DEPENDENCIA (R3);FONDO (R2);GERENCIA (R1);EMPRESA;JEFE PERS.;JEFE PERS. NOMBRE;EJECUTIVO; EJECUTIVO NOMBRE;FOCO ESTRATEGICO;PROGRAMA;PROGRAMA_ELEARNING;NOMBRE INTERNO IMPARTICION;TIPO CURSO;ANNO INICIO;MES INICIO;FECHA INICIO;ANNO FIN;MES TERMINO;FECHA TERMINO;FORMATO STATUS;ASISTENCIA;EVALUACION;HORAS TOTALES;LUGAR DE EJECUCION(DIRECCION);CURSO OPCIONAL; ID_CURSO;\r\n";
        $Total_Rows_full = Tragotb_DatosdesdeFull_Filtros($post["ejecutivo"], $post["fecha_inicio"], $post["fecha_termino"]);
        
        foreach ($Total_Rows_full as $Unico) {
            if ($Unico->status == "Presencial") {
                $Unico->programa_global = $Unico->programa;
                $Unico->programa = "";
            }

            $nombre = preg_replace("/\r|\n/", "", $Unico->nombre);
            $cargo = preg_replace("/\r|\n/", "", $Unico->cargo);
            $c4 = preg_replace("/\r|\n/", "", $Unico->c4);
            $c3 = preg_replace("/\r|\n/", "", $Unico->c3);
            $c2 = preg_replace("/\r|\n/", "", $Unico->c2);
            $c1 = preg_replace("/\r|\n/", "", $Unico->c1);
            $jefe = preg_replace("/\r|\n/", "", $Unico->jefe);
            $ejecutivo = preg_replace("/\r|\n/", "", $Unico->ejecutivo);
            $curso = preg_replace("/\r|\n/", "", $Unico->curso);
            $lugar = preg_replace("/\r|\n/", "", $Unico->lugar);

            $numhoras = str_replace('.', ',', $Unico->horas);
            echo $Unico->id_inscripcion . ";" . $Unico->rut_completo . ";"
            . $nombre . ";" . $cargo . ";" . $c4 . ";" . $c3 . ";"
            . $c2 . ";" . $c1 . ";" . $Unico->empresa . ";" . $Unico->rut_jefe . ";"
            . $jefe . ";" . $Unico->rut_ejecutivo . ";" . $ejecutivo . ";"
            . $Unico->foco . ";" . $Unico->programa_global . ";" . $Unico->programa . ";" . $curso . ";" . $Unico->tipo_curso . ";"
            . $Unico->anno_inicio . ";" . $Unico->mes_inicio . ";" . $Unico->fecha_inicio . ";"
            . $Unico->anno_termino . ";" . $Unico->mes_termino . ";" . $Unico->fecha_termino . ";"
            . $Unico->status . ";" . $Unico->asistencia . ";" . $Unico->evaluacion . ";" . $numhoras . ";"
            . $lugar . ";" . $Unico->curso_opcional . ";" . $Unico->id_curso . ";\r\n";
        }
    } else {
        $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/reportes/capacitacion/entorno_capacitacion_ejecutivos.html"));
    }

    $PRINCIPAL = str_replace("{VAR_ARREGLO}", $array_para_enviar_via_url, $PRINCIPAL);

    $datos_empresa = DatosEmpresa($id_empresa);
    $nombre_ejecutivo = TraeNombreUsuario($post["ejecutivo"]);

    $PRINCIPAL = str_replace("{NOMBRE_EJECUTIVO}", $nombre_ejecutivo[0]->nombre_completo, $PRINCIPAL);

    $PRINCIPAL = str_replace("{FECHA_INICIO}", $txt_show_fi, $PRINCIPAL);
    $PRINCIPAL = str_replace("{FECHA_TERMINO}", $txt_show_ft, $PRINCIPAL);

    $PRINCIPAL = str_replace("{C1_VALOR}", $datos_empresa[0]->campo1, $PRINCIPAL);
    $PRINCIPAL = str_replace("{C2_VALOR}", $datos_empresa[0]->campo2, $PRINCIPAL);
    $PRINCIPAL = str_replace("{C3_VALOR}", $datos_empresa[0]->campo3, $PRINCIPAL);

//Ejecutivos
    $ejecutivos = TraeEjecutivosDeTabla($id_empresa);
    $options_ejecutivos = "";
    foreach ($ejecutivos as $ejec) {
        $options_ejecutivos .= "<option value='" . $ejec->rut . "'>" . $ejec->nombre_ejecutivo . "</option>";
    }

    $PRINCIPAL = str_replace("{OPTIONS_EJECUTIVOS}", ($options_ejecutivos), $PRINCIPAL);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "lms_reportes_capacitacion_por_relatores") {
    $id_empresa = $_SESSION["id_empresa"];

    if (isset($post['web_txt'])) {
        if ($post["relator"] != '') {$txt_e = "_" . $post["relator"];}
        if ($post["fecha_inicio"] != '') {$txt_fi = "_" . $fecha_inicio;
            $txt_show_fi = " del " . $fecha_inicio;}
        if ($post["fecha_termino"] != '') {$txt_ft = "_" . $fecha_termino;
            $txt_show_ft = " al " . $fecha_termino;}
        $txt = $txt_e . $txt_fi . $txt_ft;
        header('Content-type: text/plain');
        header('Content-Disposition: attachment; filename=Reporte_por_Relator_Imparticion_' . $txt . '.csv');
        echo "CODIGO IMPARTICION;RUT_RELATOR;NOMBRE_RELATOR;TIPO_RELATOR;EMPRESA_RELATOR;EJECUTIVO;EJECUTIVO NOMBRE;NOMBRE INTERNO IMPARTICION;FECHA INICIO;FECHA TERMINO;HORAS_RELATOR\r\n";
        $Total_Rows_full = ConsultaDataPorRelator($post["relator"], $post["fecha_inicio"], $post["fecha_termino"], "", $id_empresa);
                foreach ($Total_Rows_full as $Unico) {

            echo $Unico->codigo_inscripcion . ";" . $Unico->RutRelator . ";" . $Unico->NombreRelator . ";" . $Unico->CargoRelator . ";" . $Unico->EmpresaRelator . ";" . $Unico->RutEjecutivo . ";" . $Unico->NombreEjecutivo . ";" . $Unico->NombreCurso . ";" . $Unico->fecha_inicio . ";" . $Unico->fecha_termino . ";" . $Unico->numero_horas . "\r\n";
        }
    } elseif (isset($post['txt'])) {
        if ($post["relator"] != '') {$txt_e = "_" . $post["relator"];}
        if ($post["fecha_inicio"] != '') {$txt_fi = "_" . $fecha_inicio;
            $txt_show_fi = " del " . $fecha_inicio;}
        if ($post["fecha_termino"] != '') {$txt_ft = "_" . $fecha_termino;
            $txt_show_ft = " al " . $fecha_termino;}
        $txt = $txt_e . $txt_fi . $txt_ft;
        header('Content-type: text/plain');
        header('Content-Disposition: attachment; filename=Reporte_por_Relator_Imparticion_' . $txt . '.csv');
        echo "RUT_RELATOR;NOMBRE_RELATOR;TIPO_RELATOR;EMPRESA_RELATOR;HORAS_RELATOR\r\n";
        $Total_Rows_full = ConsultaDataPorRelator($post["relator"], $post["fecha_inicio"], $post["fecha_termino"], "1", $id_empresa);
        foreach ($Total_Rows_full as $Unico) {

            echo $Unico->RutRelator . ";" . $Unico->NombreRelator . ";" . $Unico->CargoRelator . ";" . $Unico->EmpresaRelator . ";" . $Unico->numero_horas . "\r\n";
        }
    } else {
        $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/reportes/capacitacion/entorno_capacitacion_relatores.html"));
    }

    $PRINCIPAL = str_replace("{VAR_ARREGLO}", $array_para_enviar_via_url, $PRINCIPAL);

    $datos_empresa = DatosEmpresa($id_empresa);
    $nombre_ejecutivo = TraeNombreUsuario($post["ejecutivo"]);

    $PRINCIPAL = str_replace("{NOMBRE_EJECUTIVO}", $nombre_ejecutivo[0]->nombre_completo, $PRINCIPAL);

    $PRINCIPAL = str_replace("{FECHA_INICIO}", $txt_show_fi, $PRINCIPAL);
    $PRINCIPAL = str_replace("{FECHA_TERMINO}", $txt_show_ft, $PRINCIPAL);

    $PRINCIPAL = str_replace("{C1_VALOR}", $datos_empresa[0]->campo1, $PRINCIPAL);
    $PRINCIPAL = str_replace("{C2_VALOR}", $datos_empresa[0]->campo2, $PRINCIPAL);
    $PRINCIPAL = str_replace("{C3_VALOR}", $datos_empresa[0]->campo3, $PRINCIPAL);

//Ejecutivos
    $ejecutivos = TraeEjecutivosDeTabla($id_empresa);
    $options_ejecutivos = "";
    foreach ($ejecutivos as $ejec) {
        $options_ejecutivos .= "<option value='" . $ejec->rut . "'>" . $ejec->nombre_ejecutivo . "</option>";
    }
    $PRINCIPAL = str_replace("{OPTIONS_EJECUTIVOS}", ($options_ejecutivos), $PRINCIPAL);

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "lms_reportes_capacitacion_por_persona") {
    $id_empresa = $_SESSION["id_empresa"];
    $rut_usuario = LimpiaRut($post["rut"]);

    if (isset($post['web'])) {
        //web action

        $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/reportes/capacitacion/entorno_capacitacion_personas_con_datos.html"));
        $PRINCIPAL = ColocaDatosPorPersona($PRINCIPAL, $rut_usuario, $post["fecha_inicio"], $post["fecha_termino"], $id_empresa);
    } else if (isset($post['txt'])) {
        //export txt action

        if ($rut_usuario != '') {$txt_e = "_" . $rut_usuario;}
        if ($post["fecha_inicio"] != '') {$txt_fi = "_" . $fecha_inicio;
            $txt_show_fi = " del " . $fecha_inicio;}
        if ($post["fecha_termino"] != '') {$txt_ft = "_" . $fecha_termino;
            $txt_show_ft = " al " . $fecha_termino;}

        $txt = $txt_e . $txt_fi . $txt_ft;

        header('Content-type: text/plain');
        header('Content-Disposition: attachment; filename=Reporte_' . $txt . '.csv');
        echo "CORRELATIVO IMPARTICION;RUT PARTICIPANTE;NOMBRE COMPLETO;CARGO;UNIDAD (CENCO);DEPENDENCIA (R3);FONDO (R2);GERENCIA (R1);EMPRESA;JEFE PERS.;JEFE PERS. NOMBRE;EJECUTIVO; EJECUTIVO NOMBRE;FOCO ESTRATEGICO;PROGRAMA;NOMBRE INTERNO IMPARTICION;TIPO CURSO;ANNO INICIO;MES INICIO;FECHA INICIO;ANNO FIN;MES TERMINO;FECHA TERMINO;FORMATO STATUS;ASISTENCIA;EVALUACION;HORAS TOTALES;LUGAR DE EJECUCION(DIRECCION);CURSO OPCIONAL\r\n";
        $Total_Rows_full = Tragotb_DatosdesdeFull_FiltrosPersona($rut_usuario, $post["fecha_inicio"], $post["fecha_termino"]);

        foreach ($Total_Rows_full as $Unico) {
            $numhoras = str_replace('.', ',', $Unico->horas);

            echo $Unico->id_inscripcion . ";" . $Unico->rut_completo . ";" . $Unico->nombre . ";" . $Unico->cargo . ";" . $Unico->c4 . ";" . $Unico->c3 . ";" . $Unico->c2 . ";" . $Unico->c1 . ";" . $Unico->empresa . ";" . $Unico->rut_jefe . ";"
            . $Unico->jefe . ";" . $Unico->rut_ejecutivo . ";" . $Unico->ejecutivo . ";" . $Unico->foco . ";" . $Unico->programa . ";" . $Unico->curso . ";" . $Unico->tipo_curso . ";" . $Unico->anno_inicio . ";" . $Unico->mes_inicio . ";" . $Unico->fecha_inicio . ";" . $Unico->anno_termino . ";" . $Unico->mes_termino . ";" . $Unico->fecha_termino . ";" . $Unico->status . ";" . $Unico->asistencia . ";" . $Unico->evaluacion . ";" . $numhoras . ";" . $Unico->lugar . ";" . $Unico->curso_opcional . "\r\n";
        }
    } else {
        $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/reportes/capacitacion/entorno_capacitacion_personas.html"));
    }

    $PRINCIPAL = str_replace("{VAR_ARREGLO}", $array_para_enviar_via_url, $PRINCIPAL);

    $datos_empresa = DatosEmpresa($id_empresa);
    $dataUser = TraeDatosUsuario($rut_usuario);

    $PRINCIPAL = str_replace("{NOMBRE_PERSONA}", $dataUser[0]->nombre_completo, $PRINCIPAL);
    $PRINCIPAL = str_replace("{CARGO}", $dataUser[0]->cargo, $PRINCIPAL);
    $PRINCIPAL = str_replace("{GERENCIA}", $dataUser[0]->gerencia, $PRINCIPAL);

    $PRINCIPAL = str_replace("{FECHA_INICIO}", $txt_show_fi, $PRINCIPAL);
    $PRINCIPAL = str_replace("{FECHA_TERMINO}", $txt_show_ft, $PRINCIPAL);

    $PRINCIPAL = str_replace("{C1_VALOR}", $datos_empresa[0]->campo1, $PRINCIPAL);
    $PRINCIPAL = str_replace("{C2_VALOR}", $datos_empresa[0]->campo2, $PRINCIPAL);
    $PRINCIPAL = str_replace("{C3_VALOR}", $datos_empresa[0]->campo3, $PRINCIPAL);

//Ejecutivos
    $ejecutivos = TraeEjecutivosDeTabla($id_empresa);
    $options_ejecutivos = "";
    foreach ($ejecutivos as $ejec) {
        $options_ejecutivos .= "<option value='" . $ejec->rut . "'>" . $ejec->nombre_ejecutivo . "</option>";
    }
    $PRINCIPAL = str_replace("{OPTIONS_EJECUTIVOS}", ($options_ejecutivos), $PRINCIPAL);

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "lms_reportes_capacitacion_completo") {
    $id_empresa = $_SESSION["id_empresa"];

    if ($post["ejecutivo"]) {
        $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/reportes/capacitacion/entorno_capacitacion_completo_con_datos.html"));
        $PRINCIPAL = ColocaDatosPorEjecutivo($PRINCIPAL, $post["ejecutivo"], $id_empresa);
    } else {
        $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/reportes/capacitacion/entorno_capacitacion_completo.html"));
    }

    $PRINCIPAL = str_replace("{VAR_ARREGLO}", $array_para_enviar_via_url, $PRINCIPAL);

    $datos_empresa = DatosEmpresa($id_empresa);

    $PRINCIPAL = str_replace("{C1_VALOR}", $datos_empresa[0]->campo1, $PRINCIPAL);
    $PRINCIPAL = str_replace("{C2_VALOR}", $datos_empresa[0]->campo2, $PRINCIPAL);
    $PRINCIPAL = str_replace("{C3_VALOR}", $datos_empresa[0]->campo3, $PRINCIPAL);

//Ejecutivos
    $ejecutivos = TraeEjecutivosDeTabla($id_empresa);
    $options_ejecutivos = "";
    foreach ($ejecutivos as $ejec) {
        $options_ejecutivos .= "<option value='" . $ejec->rut . "'>" . $ejec->nombre_ejecutivo . "</option>";
    }
    $PRINCIPAL = str_replace("{OPTIONS_EJECUTIVOS}", ($options_ejecutivos), $PRINCIPAL);

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "lms_reportes_capacitacion_por_usuarios") {
    $id_empresa = $_SESSION["id_empresa"];

    $rut = LimpiaRut($post["rut"]);
    if ($rut) {
        $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/reportes/capacitacion/entorno_capacitacion_usuarios_con_datos.html"));
        $PRINCIPAL = ColocaDatosPorUsuarioDatos($PRINCIPAL, $rut, $id_empresa);
    } else {
        $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/reportes/capacitacion/entorno_capacitacion_usuarios.html"));
    }

    $datos_empresa = DatosEmpresa($id_empresa);

    $PRINCIPAL = str_replace("{C1_VALOR}", $datos_empresa[0]->campo1, $PRINCIPAL);
    $PRINCIPAL = str_replace("{C2_VALOR}", $datos_empresa[0]->campo2, $PRINCIPAL);
    $PRINCIPAL = str_replace("{C3_VALOR}", $datos_empresa[0]->campo3, $PRINCIPAL);

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "lms_reportes_inscripcion_cursos_presenciales") {
    $id_empresa = $_SESSION["id_empresa"];
    $arreglo_post = $post;
    $array_para_enviar_via_url = serialize($arreglo_post);
    $array_para_enviar_via_url = urlencode($array_para_enviar_via_url);
    $filtros_superiores = "";
    $exportar_a_excel = $post["excel"];

    if (count($arreglo_post) > 0) {
        if ($exportar_a_excel == 1) {
            $fechahoy = date("Y-m-d");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=Reporte_Consolidado_Inscripciones_Cursos_" . $fechahoy . ".xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            ?>
<table id="tabla1" class="table table-bordered table-hover table-condensed">

<?php
$PRINCIPAL = ReporteInscripcionCursos(FuncionesTransversalesAdmin(file_get_contents("views/reportes/inscripcionpresencial/entorno_capacitacion_excel.html")), $id_empresa, $arreglo_post, "", "1");
            echo ($PRINCIPAL);

            exit;
        } else {
            $PRINCIPAL = ReporteInscripcionCursos(FuncionesTransversalesAdmin(file_get_contents("views/reportes/inscripcionpresencial/entorno_capacitacion.html")), $id_empresa, $arreglo_post, "", "");
        }
    } else {
        $PRINCIPAL = ReporteInscripcionCursos(FuncionesTransversalesAdmin(file_get_contents("views/reportes/inscripcionpresencial/entorno_capacitacion_previa.html")), $id_empresa, $arreglo_post, "", "");
    }


    if ($arreglo_post["malla"]) {
        $datos_malla = Datos_Malla($arreglo_post["malla"]);
        if ($datos_malla[0]->nombre != '') {$filtros_superiores .= "Malla: " . ($datos_malla[0]->nombre) . " | ";}
    }

    if ($arreglo_post["curso"]) {
        $datos_curso = Datos_curso($arreglo_post["curso"]);
        if ($datos_curso[0]->nombre != '') {$filtros_superiores .= "Curso: " . ($datos_curso[0]->nombre) . " | ";}
    }

    if ($arreglo_post["ejecutivo"]) {
        if ($arreglo_post["ejecutivo"]) {$filtros_superiores .= "Ejecutivo: " . ($arreglo_post["ejecutivo"]) . " | ";}
    }

    if ($arreglo_post["fechas"]) {
        if ($arreglo_post["fechas"]) {$filtros_superiores .= "Fechas: " . ($arreglo_post["fechas"]) . " ";}
    }

    $PRINCIPAL = str_replace("{FILTROS_SELECCIONADOS}", $filtros_superiores, $PRINCIPAL);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;

    $datos_empresa = DatosEmpresa($id_empresa);
}
else if ($seccion == "lms_reportes_encuesta_satisfaccion") {
    $id_empresa = $_SESSION["id_empresa"];
    $arreglo_post = $post;
    $array_para_enviar_via_url = serialize($arreglo_post);
    $array_para_enviar_via_url = urlencode($array_para_enviar_via_url);
    $filtros_superiores = "";
    $exportar_a_excel = $post["excel"];

    if (count($arreglo_post) > 0) {
        if ($exportar_a_excel == 1) {
            $fechahoy = date("Y-m-d");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=Reporte_Detalle_Enc_Sat_" . $fechahoy . ".xls");
            header("Pragma: no-cache");
            header("Expires: 0");
            ?>
<table id="tabla1" class="table table-bordered table-hover table-condensed">
<?php
$PRINCIPAL = ReporteEncuestaSatisfaccion(FuncionesTransversalesAdmin(file_get_contents("views/reportes/enc_satisfaccion/entorno_capacitacion_excel.html")), $id_empresa, $arreglo_post, "", $exportar_a_excel);
            echo ($PRINCIPAL);

            exit;
        } else {
            $PRINCIPAL = ReporteEncuestaSatisfaccion(FuncionesTransversalesAdmin(file_get_contents("views/reportes/enc_satisfaccion/entorno_capacitacion.html")), $id_empresa, $arreglo_post, "", "");

            $array_para_enviar_via_url_post = serialize($arreglo_post);
            $array_para_enviar_via_url_post = urlencode($array_para_enviar_via_url_post);
            $PRINCIPAL = str_replace("{ARREGLO_POST}", $array_para_enviar_via_url_post, $PRINCIPAL);
        }
    } else {
        $PRINCIPAL = ReporteEncuestaSatisfaccion(FuncionesTransversalesAdmin(file_get_contents("views/reportes/enc_satisfaccion/entorno_capacitacion_previa.html")), $id_empresa, $arreglo_post, "", "");
    }


    if ($arreglo_post["malla"]) {
        $datos_malla = Datos_Malla($arreglo_post["malla"]);
        if ($datos_malla[0]->nombre != '') {$filtros_superiores .= "Malla: " . ($datos_malla[0]->nombre) . " | ";}
    }

    if ($arreglo_post["curso"]) {
        $datos_curso = Datos_curso($arreglo_post["curso"]);
        if ($datos_curso[0]->nombre != '') {$filtros_superiores .= "Curso: " . ($datos_curso[0]->nombre) . " | ";}
    }

    if ($arreglo_post["ejecutivo"]) {
        if ($arreglo_post["ejecutivo"]) {$filtros_superiores .= "Ejecutivo: " . ($arreglo_post["ejecutivo"]) . " | ";}
    }

    if ($arreglo_post["fechas"]) {
        if ($arreglo_post["fechas"]) {$filtros_superiores .= "Fechas: " . ($arreglo_post["fechas"]) . " ";}
    }

    $PRINCIPAL = str_replace("{FILTROS_SELECCIONADOS}", $filtros_superiores, $PRINCIPAL);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;

    $datos_empresa = DatosEmpresa($id_empresa);

    if (count($arreglo_post) > 0 && $exportar_a_excel != 1) {
        ?>
<script type="text/javascript">
    $(window).load(function() {
$("#capa").load('?sw=lms_reportes_encuesta_satisfaccion_por_criterio&campo_criterio=<?php echo $datos_empresa[0]->campo1; ?>&array_post=<?php echo $array_para_enviar_via_url; ?>
    ');
    });
</script>

<script type="text/javascript">
    $(window).load(function() {
$("#capa2").load('?sw=lms_reportes_encuesta_satisfaccion_por_criterio&campo_criterio=<?php echo $datos_empresa[0]->campo2; ?>&array_post=<?php echo $array_para_enviar_via_url; ?>
    ');
    });
</script>

<script type="text/javascript">
    $(window).load(function() {
$("#capa3").load('?sw=lms_reportes_encuesta_satisfaccion_por_criterio&campo_criterio=<?php echo $datos_empresa[0]->campo3; ?>&array_post=<?php echo $array_para_enviar_via_url; ?>
    ');
    });
</script>

<?php
}
}
else if ($seccion == "Lms_reporte_MuestraInformeEncParticipantesExcel") {
    $id_empresa = $_SESSION["id_empresa"];
    $excel = 1;

    $miarray_objeto = $get["arreglo_objeto"];
    $array_para_recibir_via_url_objeto = stripslashes($miarray_objeto);
    $array_para_recibir_via_url_objeto = urldecode($array_para_recibir_via_url_objeto);
    $arreglo_post_objetos = unserialize($array_para_recibir_via_url_objeto,['allowed_classes' => false]);

    $miarray = $get["arreglo_post"];
    $array_para_recibir_via_url_post = stripslashes($miarray);
    $array_para_recibir_via_url_post = urldecode($array_para_recibir_via_url_post);
    $arreglo_post = unserialize($array_para_recibir_via_url_post,['allowed_classes' => false]);

    if ($arreglo_post["imparticion"]) {
        $nombre_archivo = $arreglo_post["imparticion"];
    } else if ($arreglo_post["cursos"]) {
        $nombre_archivo = $arreglo_post["cursos"];
    }

    if ($arreglo_post_objetos[0]->codigo_inscripcion) {
        $nombre_archivo = $arreglo_post_objetos[0]->codigo_inscripcion;
    } else if ($arreglo_post_objetos["cursos"]) {
        $nombre_archivo = $arreglo_post_objetos["cursos"];
    }

    $fechahoy = date("Y-m-d");
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=" . $nombre_archivo . "_" . $get["tipo_reporte"] . "_" . $fechahoy . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo "<meta charset='UTF-8'>";

    if ($get["tipo_reporte"] == "participantes") {
        $PRINCIPAL = Lms_reporte_MuestraInformeEncParticipantes($PRINCIPAL, $id_empresa, $arreglo_post_objetos, $arreglo_post, $valor, $excel);
    }

    if ($get["tipo_reporte"] == "listadocursos") {
        $PRINCIPAL = Lms_reporte_MuestraListaCursosEnc($PRINCIPAL, $id_empresa, $arreglo_post, $excel);
    }

    if ($get["tipo_reporte"] == "resultadosencsatisfaccion") {

        $PRINCIPAL = Lms_reporte_MuestraInformeEnc($PRINCIPAL, $id_empresa, $arreglo_post_objetos, $arreglo_post, $valor, $excel);
    }

    if ($get["tipo_reporte"] == "resultadosencLiterales") {
        $PRINCIPAL = Lms_reporte_MuestraInformeEncLiterales($PRINCIPAL, $id_empresa, $arreglo_post_objetos, $arreglo_post, $valor, $excel);
    }
    if ($get["tipo_reporte"] == "detallePreguntas") {
        $PRINCIPAL = Lms_reporte_MuestraInformeEncPregunta($PRINCIPAL, $id_empresa, $arreglo_post_objetos, "", "", $excel, "", $arreglo_post_objetos);
    }

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "Procesa_Dashboard") {
    $id_empresa = $_SESSION["id_empresa"];
    DeleteTbltbl_Dash_estadistica($id_empresa);
    Com_ProcesoDashBoard($id_empresa);

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if($seccion=="EncodearClavesAdmin"){
	$claves=TraeClavesTotalAdmin();
	foreach($claves as $cla){
		echo $cla->pass." - ";
		echo Encodear3($cla->pass);
		echo "<br>";
		AdminActualizaClaveEncodeada($cla->id, Encodear3($cla->pass));
	}

}
else if ($seccion == "admin_dashboard") {

    $filtro         = $post['filtro'];
    $fecha_inicio   = $post['fecha_inicio'];
    $fecha_termino  = $post['fecha_termino'];

    

    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/dashboard/entorno.html"));
    $id_empresa = $_SESSION["id_empresa"];
    $PRINCIPAL = str_replace("{ENTORNO}", lista_admin_dashboard(FuncionesTransversalesAdmin(file_get_contents("views/dashboard/entorno_dashboard.html")), $id_empresa, $id_ola, $fecha_inicio, $fecha_termino), $PRINCIPAL);
    $datos_empresa = DatosEmpresa($id_empresa);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "EmpresasExt_Save") {
    $id_empresa = $_SESSION["id_empresa"];
    $ejecutivo = $_SESSION["user_"];
    $rut = $post['rut'];
    $nombre = $post['nombre'];
    $descripcion = $post['descripcion'];
    $dominio = $post['dominio'];
    $malla = $post['malla'];
    

    Empresas_Ext_Save_data($rut, $nombre, $descripcion, $dominio, $malla, $ejecutivo, $id_empresa);
    echo "<script>        location.href='?sw=ext_empresas&id_dimension=" . $id_dimension . "';        </script>";
}
else if ($seccion == "EmpresasExtDivision_Save") {
    $id_empresa = $_SESSION["id_empresa"];
    $ejecutivo = $_SESSION["user_"];
    $rut = $post['rut'];
    $codigo = $post['codigo'];
    $division = ($post['division']);
    $sindicato = ($post['sindicato']);


    EmpresasExtDivision_Save_data($rut, $codigo, $division, $sindicato, $ejecutivo, $id_empresa);
    echo "<script>        location.href='?sw=ext_empresas&id_dimension=" . $id_dimension . "';        </script>";
}
else if ($seccion == "Eventos_Save") {
    $id_empresa = $_SESSION["id_empresa"];

    $id = $post['id'];
    $codigo = $post['codigo'];
    $id_dimension = $post['id_dimension'];

    $nombre = $post['nombre'];
    $descripcion = $post['descripcion'];
    $instruccion = $post['instruccion'];
    $id_categoria = $post['id_categoria'];
    $postulable = $post['postulable'];

    $fecha_inicio = $post['fecha_inicio'];
    $hora_inicio = $post['hora_inicio'];
    $fecha_termino = $post['fecha_termino'];
    $hora_termino = $post['hora_termino'];
    $direccion = $post['direccion'];
    $region = $post['region'];
    $link = $post['link'];
    $visible = $post['visible'];


    Eventos_Save_data($codigo, $id_dimension, $nombre, $descripcion, $instruccion, $id_categoria, $postulable,
        $fecha_inicio, $hora_inicio, $fecha_termino, $hora_termino, $direccion, $region, $link, $visible, $id_empresa);
    echo "<script>        location.href='?sw=eventos&id_dimension=" . $id_dimension . "';        </script>";
}
else if ($seccion == "emb_tareas_Save") {
    $id_empresa = $_SESSION["id_empresa"];

    $id = $post['id'];
    $codigo = $post['codigo'];
    $nombre = $post['nombre'];
    $descripcion = $post['descripcion'];
    $link_material = $post['link_material'];
    $accion = $post['accion'];
    $descripcion_accion = $post['descripcion_accion'];
    $duracion = $post['duracion'];
    $fecha_termino = $post['fecha_termino'];

    $id = $post['id'];
    $codigo = $post['codigo'];
    $id_grupo_interes = $post['id_grupo_interes'];
    $nombre = $post['nombre'];
    $descripcion = $post['descripcion'];
    $duracion = $post['duracion'];

    $link_material = $post['link_material'];
    $link_material2 = $post['link_material2'];
    $link_material3 = $post['link_material3'];

    $pregunta1 = $post['pregunta1'];
    $pregunta2 = $post['pregunta2'];
    $pregunta3 = $post['pregunta3'];

    $accion = $post['accion'];
    $descripcion_accion = $post['descripcion_accion'];
    $link_archivo_trabajo = $post['link_archivo_trabajo'];
    $instruccion_tutor = $post['instruccion_tutor'];
    $link_tutor = $post['link_tutor'];

    emb_tareas_Save_data($codigo, $id_grupo_interes, $nombre, $descripcion, $duracion, $link_material, $link_material2, $link_material3,
        $accion, $descripcion_accion, $link_archivo_trabajo, $instruccion_tutor, $link_tutor,
        $pregunta1, $pregunta2, $pregunta3, $fecha_termino, $id_empresa);
    echo "<script>        location.href='?sw=emb_tareas';        </script>";
}
else if ($seccion == "notificaciones_Save") {
    $id_empresa = $_SESSION["id_empresa"];

    
    $id = $post['id'];
    $codigo = $post['codigo'];
    $nombre = $post['nombre'];
    $descripcion = $post['descripcion'];
    $tipomensaje = $post['tipomensaje'];

    $subject = $post['subject'];
    $titulo1 = $post['titulo1'];
    $subtitulo1 = $post['subtitulo1'];
    $texto1 = $post['texto1'];
    $texto2 = $post['texto2'];
    $texto3 = $post['texto3'];
    $texto4 = $post['texto4'];

    $tipo = "text/html";

    notificaciones_Save_data($codigo, $nombre, $descripcion, $tipomensaje,
        $subject, $titulo1, $subtitulo1, $texto1, $texto2, $texto3, $texto4, $logo, $url_base, $texto_url, $logo, $tipo, $id_empresa);

    echo "<script>        location.href='?sw=notificaciones';        </script>";
}
else if ($seccion == "notificaciones") {
    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/notificaciones/entorno.html"));
    $id_empresa = $_SESSION["id_empresa"];
    $PRINCIPAL = str_replace("{ENTORNO}", lista_notificaciones(FuncionesTransversalesAdmin(file_get_contents("views/notificaciones/entorno_notificaciones.html")), $id_empresa, $id_categoria), $PRINCIPAL);
    $datos_empresa = DatosEmpresa($id_empresa);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "notificaciones_a_buscar") {
    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/notificaciones/entorno.html"));
    $id_empresa = $_SESSION["id_empresa"];

    $generar = ($post['generar']);
    $id_programa = ($post['id_programa']);
    if ($generar == 1) {
        NotificacionesBuscaPendientesAutomatico($id_empresa, $url_front, $url_front_admin_full, $logo, $from, $nombrefrom, $id_programa);
    }

    $copia = ($get['copia']);
    $tipoenvio = ($get['tipo']);

    if ($copia == 1) {
        Copia_lista_notificaciones_a_buscar_detalle($id_empresa, $tipoenvio);
        echo "Copia Lista";
    }

    $PRINCIPAL = str_replace("{ENTORNO}", lista_notificaciones_a_buscar(FuncionesTransversalesAdmin(file_get_contents("views/notificaciones/entorno_notificaciones_buscar.html")), $id_empresa, $id_categoria), $PRINCIPAL);
    $datos_empresa = DatosEmpresa($id_empresa);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "notificaciones_Save") {
    $id_empresa = $_SESSION["id_empresa"];

    $id = $post['id'];
    $codigo = $post['codigo'];
    $nombre = $post['nombre'];
    $descripcion = $post['descripcion'];
    $link_material = $post['link_material'];
    $accion = $post['accion'];
    $descripcion_accion = $post['descripcion_accion'];
    $duracion = $post['duracion'];

    $id = $post['id'];
    $codigo = $post['codigo'];
    $id_grupo_interes = $post['id_grupo_interes'];
    $nombre = $post['nombre'];
    $descripcion = $post['descripcion'];
    $duracion = $post['duracion'];

    $link_material = $post['link_material'];
    $link_material2 = $post['link_material2'];
    $link_material3 = $post['link_material3'];

    $accion = $post['accion'];
    $descripcion_accion = $post['descripcion_accion'];
    $link_archivo_trabajo = $post['link_archivo_trabajo'];
    $instruccion_tutor = $post['instruccion_tutor'];
    $link_tutor = $post['link_tutor'];

    emb_tareas_Save_data($codigo, $id_grupo_interes, $nombre, $descripcion, $duracion, $link_material, $link_material2, $link_material3,
        $accion, $descripcion_accion, $link_archivo_trabajo, $instruccion_tutor, $link_tutor, $id_empresa);
    echo "<script>        location.href='?sw=emb_tareas';        </script>";
}
else if ($seccion == "notificaciones_Edit") {
    $id_empresa = $_SESSION["id_empresa"];
    $rut = LimpiaRut($post['rut']);
    $id_tarea = ($post['id_tarea']);
    $link = ($post['link']);
    $comentarios = ($post['comentarios']);
    $trabajaste = ($post['trabajaste']);
    $estado = ($post['estado']);

    emb_tareas_Edit_data($rut, $id_tarea, $link, $comentarios, $trabajaste, $estado, $id_empresa);

    echo "<script>location.href='?sw=emb_relemb';    </script>";
}
else if ($seccion == "reportes_online_personas_2020") {
    $id_empresa = $_SESSION["id_empresa"];
		$user_admin	=	$_SESSION["admin_"];

    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/reportes_full/" . $id_empresa . "_entorno.html"));
		$PRINCIPAL = str_replace("{ENTORNO}", FuncionesTransversalesAdmin(file_get_contents("views/reportes_full/" . $id_empresa . "_entorno_reportes_online.html")), $PRINCIPAL);
		$rut_usuario	=	LimpiaRut($post["rut_usuario"]);

		
		$Usu=TraeUsuarioRut($rut_usuario);

				if($rut_usuario<>""){

							// Existe usuario
							$Usu						= TraeDatosUsuario($rut_usuario);

							if($Usu[0]->rut==""){
								// No Existe
		        		$arreglo_row_avance = lms_integracion_lista_programas_ADMIN_NoExiste($rut_usuario, $id_empresa);
								$usuario_vigente=" Colaborador No Vigente ";

							} else {
								// Si Existe
		        		$arreglo_row_avance = lms_integracion_lista_programas_ADMIN_2022($rut_usuario, $id_empresa);
								$usuario_vigente=" Colaborador Vigente ";

							}

						foreach ($arreglo_row_avance as $unico){



							$row_avance_div_tit=
									"
										<div class='row'>
											<div class='col-lg-4' style='background-color: #e7e7e7;    font-weight: 600;    padding-top: 10px;    padding-bottom: 10px;'>Curso</div>
											<div class='col-lg-2' style='background-color: #e7e7e7;    font-weight: 600;    padding-top: 10px;    padding-bottom: 10px;'>Tipo</div>
											<div class='col-lg-2' style='background-color: #e7e7e7;    font-weight: 600;    padding-top: 10px;    padding-bottom: 10px;'>Modalidad</div>
											<div class='col-lg-1' style='background-color: #e7e7e7;    font-weight: 600;    padding-top: 10px;    padding-bottom: 10px;'>Avance</div>
											<div class='col-lg-1' style='background-color: #e7e7e7;    font-weight: 600;    padding-top: 10px;    padding-bottom: 10px;'>Nota</div>
											<div class='col-lg-2' style='background-color: #e7e7e7;    font-weight: 600;    padding-top: 10px;    padding-bottom: 10px; padding-left: 40px;'>Estado</div>
										</div>
										<div class='row'>
									";

							$estado="";

										if($unico->estado=="APROBADO")			{ $estado="<div class='alert alert-success'>".$unico->estado."</div>";}
										elseif($unico->estado=="REPROBADO")	{ $estado="<div class='alert alert-danger'>".$unico->estado."</div>";}
										else { $estado="<div class='alert alert-info'>".$unico->estado."</div>";}

										if($unico->modalidad=="1"){
											$modalidad="Virtual";
										} else {
											$modalidad="Presencial";
										}
											if($unico->curso_opcional=="1"){
											$tipo="Opcional";
										} else {
											$tipo="Obligatorio";
										}


							$row_avance_div_row.=
									"
										<div class='row'>
											<div class='col-lg-4' style='padding-left: 30px;padding-top: 10px;'><strong>".($unico->curso)."</strong>
													<br>
													 ".$unico->id_curso."
											</div>
											<div class='col-lg-2'  style='padding-top: 10px;'>".$tipo."</div>
											<div class='col-lg-2'  style='padding-top: 10px;'>".$modalidad."</div>
											<div class='col-lg-1'  style='padding-top: 10px;'>".$unico->avance."</div>
											<div class='col-lg-1'  style='padding-top: 10px;'>".$unico->nota."</div>
											<div class='col-lg-2'  style='padding-right: 30px;padding-top: 10px;'>".$estado."</div>
										</div>
									";


							$row_ficha_personas=
								"<strong>".$unico->rut_completo." ".($unico->nombre_completo)."</strong>, ".($unico->cargo).". <strong>$usuario_vigente</strong>";

							$row_avance_div_tit_final=
								"
										</div>
										
								";
						}

				} else {
						$arreglo_row_avance_pendientes[0]="";
        		$row_avance_div_tit="";
						$row_avance_div_row="";
						$row_ficha_personas="";
						$row_avance_div_tit_final="";
        		$style_hide=" display:none; ";
				}

        $PRINCIPAL = str_replace("{ocultar_lista}", $style_hide, $PRINCIPAL);
        $PRINCIPAL = str_replace("{RUT_ENVIADO}", $Usu[0]->rut, $PRINCIPAL);
        $PRINCIPAL = str_replace("{NOMBRE_ENVIADO}", $Usu[0]->nombre_completo, $PRINCIPAL);
        $PRINCIPAL = str_replace("{row_avance_div_tit}", $row_avance_div_tit, $PRINCIPAL);
        $PRINCIPAL = str_replace("{row_avance_div_row}", $row_avance_div_row, $PRINCIPAL);
        $PRINCIPAL = str_replace("{row_ficha_personas}", $row_ficha_personas, $PRINCIPAL);
        $PRINCIPAL = str_replace("{row_avance_div_tit_final}", $row_avance_div_tit_final, $PRINCIPAL);

    		$datos_empresa = DatosEmpresa($id_empresa);

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "reportes_online_cursos_2020") {

    $id_empresa = $_SESSION["id_empresa"];


    $array_duplicaciones=ReporteFull_Duplicados_data($id_empresa);
    foreach ($array_duplicaciones as $unico){
        BorraDuplicacionesReporteFull($unico->rut, $unico->id_inscripcion, $unico->id_curso, $unico->asistencia, $unico->evaluacion);
    }

	    	$cursoscol=$post[cursoscol];
  		  $vigente=$post[vigente];
					// BUSCA NOMBRE ARCHIVO

        $id_foco        = $post[foco];
        $id_programa    = $post[programaglobal];
        $id_programa_elearning = $post[programabbdd];
        $id_malla       = $post[malla];
        $id_curso       = $post[cursos];
        $id_curso3      = $post[cursos3];
        $tipo_filtro    = $post[tipo_filtro];

        if($id_curso3<>'0' and $id_curso=='0'){$id_curso=$id_curso3;}

        $imparticion    = $post[imparticion];
        $ejecutivo      = $post[ejecutivo];
        $modalidad      = $post[modalidad];
        $fecha_inicio   = $post[fecha_inicio];
        $fecha_termino  = $post[fecha_termino];
        $estado         = $post[estado];

        $txt= $id_foco.$id_programa.$id_programa_elearning.$id_malla.$id_curso. $fecha_inicio.$fecha_termino. $estado;
		    

    if ($vigente== "cursoscol") {
        
        $id_foco        = $post[foco];
        $id_programa    = $post[programaglobal];
        $id_programa_elearning = $post[programabbdd];
        $id_malla       = $post[malla];
        $id_curso       = $post[cursos];
        $id_curso3      = $post[cursos3];
        $tipo_filtro    = $post[tipo_filtro];
        if($id_curso3<>'0' and $id_curso=='0'){$id_curso=$id_curso3;}
        $imparticion    = $post[imparticion];
        $ejecutivo      = $post[ejecutivo];
        $modalidad      = $post[modalidad];
        $fecha_inicio   = $post[fecha_inicio];
        $fecha_termino  = $post[fecha_termino];
        $estado         = $post[estado];
        header('Content-type: text/plain');
        header('Content-Disposition: attachment; filename=Reporte_Full_' . $txt . '.csv');
        echo "rut;rut_completo;nombre;cargo;c1;c2;c3;c4;";

$array_rut_id_curso=lms_reportes_full_excel_solocurso_data($id_empresa, $id_foco, $id_programa, $id_programa_elearning, $id_malla, $id_curso,
$tipo_filtro, $imparticion, $ejecutivo, $modalidad, $fecha_inicio, $fecha_termino, $estado, $Unico->rut);
$k=0;
   foreach ($array_rut_id_curso as $Unico3){

                    echo  $Unico3->id_curso.  ";" .
                    $Unico3->curso.  ";" .
                    "Fecha_inicio;" .
                    "Fecha_termino;" .
                    "Modalidad;" .
                    "Asistencia;" .
                    "Evaluacion;" .
                    "Estado;" ;

$array_id_curso[$k]= $Unico3->id_curso;
     $k++;
                }
         echo "\r\n";


      $Total_Rows_full = lms_reportes_full_excel_filtro_rut_data($id_empresa, $id_foco, $id_programa, $id_programa_elearning, $id_malla, $id_curso,
        $tipo_filtro, $imparticion, $ejecutivo, $modalidad, $fecha_inicio, $fecha_termino, $estado);
        foreach ($Total_Rows_full as $Unico) {
           if ($Unico->status == "Presencial") {
                $Unico->programa_global = $Unico->programa;
                $Unico->id_programa_global = $Unico->id_programa;
                $Unico->programa = "";
                $Unico->id_programa = "";
            }
            $ESvigente=UsuarioVigente($Unico->rut);
            if($ESvigente>0){
            }   else {
                if($vigente=="vigente"){continue;}
                $Unico->rut_completo="";
                $Unico->nombre="USUARIO_NO_VIGENTE";
                $Unico->cargo="";
                $Unico->c1="";
                $Unico->c2="";
                $Unico->c3="";
                $Unico->c4="";
            }
                echo $Unico->rut. ";" .
                $Unico->rut_completo. ";" .
                $Unico->nombre. ";" .
                $Unico->cargo. ";" .
                $Unico->c1. ";" .
                $Unico->c2. ";" .
                $Unico->c3. ";" .
                $Unico->c4. ";" ;



foreach ($array_id_curso as $Unico3){
	$array_rut_id_curso_l2=lms_reportes_full_excel_solocurso_rut_data($id_empresa, $id_foco, $id_programa, $id_programa_elearning, $id_malla,
$Unico3,
$tipo_filtro, $imparticion, $ejecutivo, $modalidad, $fecha_inicio, $fecha_termino, $estado, $Unico->rut);

if(count($array_rut_id_curso_l2)==0){
                        echo  $Unico3.  ";" .
                    "".  ";" .
                    "".  ";" .
                    "".  ";" .
                    "".  ";" .
                    "".  ";" .
                    "".  ";" .
                    "".  ";" ;
}   else {

                foreach ($array_rut_id_curso_l2 as $Unico4){

                    echo  $Unico4->id_curso.  ";" .
                    $Unico4->curso.  ";" .
                    $Unico4->fecha_inicio.  ";" .
                    $Unico4->fecha_termino.  ";" .
                    $Unico4->status.  ";" .
                    $Unico4->asistencia.  ";" .
                    $Unico4->evaluacion.  ";" .
                    $Unico4->estado.  ";" ;

          			}

        }
    }
             echo "\r\n";

            }
                   exit();
        }

    if ($post[GeneraExcel] == "GeneraExcel") {
        $id_foco        = $post[foco];
        $id_programa    = $post[programaglobal];
        $id_programa_elearning = $post[programabbdd];
        $id_malla       = $post[malla];
        $id_curso       = $post[cursos];
        $id_curso3      = $post[cursos3];
        $tipo_filtro    = $post[tipo_filtro];
        if($id_curso3<>'0' and $id_curso=='0'){$id_curso=$id_curso3;}
        $imparticion    = $post[imparticion];
        $ejecutivo      = $post[ejecutivo];
        $modalidad      = $post[modalidad];
        $fecha_inicio   = $post[fecha_inicio];
        $fecha_termino  = $post[fecha_termino];
        $estado         = $post[estado];
        header('Content-type: text/plain');
        header('Content-Disposition: attachment; filename=Reporte_Full_' . $txt . '.csv');
        echo "rut;rut_completo;nombre;cargo;c1;c2;c3;c4;id_foco;foco;id_programa_global;programa_global;id_programa;programa;id_curso;curso;id_inscripcion;nombre_inscripcion;id_malla;malla;fecha_inicio_inscripcion;fecha_termino_inscripcion;tipo_curso;fecha_inicio;anno_inicio;mes_inicio;fecha_termino;anno_termino;mes_termino;estado;modalidad;asistencia;evaluacion;horas;lugar;curso_opcional;rut_ejecutivo;ejecutivo;rut_jefe;jefe;empresa\r\n";
        $Total_Rows_full = lms_reportes_full_excel_data($id_empresa, $id_foco, $id_programa, $id_programa_elearning, $id_malla, $id_curso,
        $tipo_filtro, $imparticion, $ejecutivo, $modalidad, $fecha_inicio, $fecha_termino, $estado);
        foreach ($Total_Rows_full as $Unico) {
           if ($Unico->status == "Presencial") {
                $Unico->programa_global = $Unico->programa;
                $Unico->id_programa_global = $Unico->id_programa;
                $Unico->programa = "";
                $Unico->id_programa = "";
            }
            $ESvigente=UsuarioVigente($Unico->rut);
            if($ESvigente>0){
            }   else {
                if($vigente=="vigente"){continue;}
                $Unico->rut_completo="";
                $Unico->nombre="USUARIO_NO_VIGENTE";
                $Unico->cargo="";
                $Unico->c1="";
                $Unico->c2="";
                $Unico->c3="";
                $Unico->c4="";
            }
                echo $Unico->rut. ";" .
                $Unico->rut_completo. ";" .
                $Unico->nombre. ";" .
                $Unico->cargo. ";" .
                $Unico->c1. ";" .
                $Unico->c2. ";" .
                $Unico->c3. ";" .
                $Unico->c4. ";" .
                $Unico->id_foco. ";" .
                $Unico->foco. ";" .
                $Unico->id_programa_global. ";" .
                $Unico->programa_global. ";" .
                $Unico->id_programa. ";" .
                $Unico->programa. ";" .
                $Unico->id_curso. ";" .
                $Unico->curso. ";" .
                $Unico->id_inscripcion. ";" .
                $Unico->nombre_inscripcion. ";" .
                $Unico->id_malla. ";" .
                $Unico->malla. ";" .
                $Unico->fecha_inicio_inscripcion. ";" .
                $Unico->fecha_termino_inscripcion. ";" .
                $Unico->tipo_curso. ";" .
                $Unico->fecha_inicio. ";" .
                $Unico->anno_inicio. ";" .
                $Unico->mes_inicio. ";" .
                $Unico->fecha_termino. ";" .
                $Unico->anno_termino. ";" .
                $Unico->mes_termino. ";" .
                $Unico->estado. ";" .
                $Unico->status. ";" .
                $Unico->asistencia. ";" .
                $Unico->evaluacion. ";" .
                $Unico->horas. ";" .
                $Unico->lugar. ";" .
                $Unico->curso_opcional. ";" .
                $Unico->rut_ejecutivo. ";" .
                $Unico->ejecutivo. ";" .
                $Unico->rut_jefe. ";" .
                $Unico->jefe. ";" .
                $Unico->empresa. "\r\n";
            }
             exit();
        }


			if($post["id_programa_express"]<>""){



			}

    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/reportes_full/" . $id_empresa . "_entorno.html"));
    $PRINCIPAL = str_replace("{ENTORNO}", Reporte_Full_Filtros(FuncionesTransversalesAdmin(file_get_contents("views/reportes_full/" . $id_empresa . "_entorno_reportes_full_online.html")), $id_empresa, $arreglo_post, $excel), $PRINCIPAL);

    $datos_empresa = DatosEmpresa($id_empresa);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;

}
else if ($seccion == "reportes_online_cursos_2020_v2") {

    $id_empresa = $_SESSION["id_empresa"];


    $array_duplicaciones=ReporteFull_Duplicados_data($id_empresa);
    foreach ($array_duplicaciones as $unico){
        BorraDuplicacionesReporteFull($unico->rut, $unico->id_inscripcion, $unico->id_curso, $unico->asistencia, $unico->evaluacion);
    }

	    	$cursoscol=$post[cursoscol];
  		  $vigente=$post[vigente];
					// BUSCA NOMBRE ARCHIVO

        $id_foco        = $post[foco];
        $id_programa    = $post[programaglobal];
        $id_programa_elearning = $post[programabbdd];
        $id_malla       = $post[malla];
        $id_curso       = $post[cursos];
        $id_curso3      = $post[cursos3];
        $tipo_filtro    = $post[tipo_filtro];

        if($id_curso3<>'0' and $id_curso=='0'){$id_curso=$id_curso3;}

        $imparticion    = $post[imparticion];
        $ejecutivo      = $post[ejecutivo];
        $modalidad      = $post[modalidad];
        $fecha_inicio   = $post[fecha_inicio];
        $fecha_termino  = $post[fecha_termino];
        $estado         = $post[estado];

        $txt= $id_foco.$id_programa.$id_programa_elearning.$id_malla.$id_curso. $fecha_inicio.$fecha_termino. $estado;
		    

    if ($post[GeneraExcel] == "GeneraExcel") {
        $id_foco        = $post[foco];
        $id_programa    = $post[programaglobal];
        $id_programa_elearning = $post[programabbdd];
        $id_malla       = $post[malla];
        $id_curso       = $post[cursos];
        $id_curso3      = $post[cursos3];
        $tipo_filtro    = $post[tipo_filtro];
        if($id_curso3<>'0' and $id_curso=='0'){$id_curso=$id_curso3;}
        $imparticion    = $post[imparticion];
        $ejecutivo      = $post[ejecutivo];
        $modalidad      = $post[modalidad];
        $fecha_inicio   = $post[fecha_inicio];
        $fecha_termino  = $post[fecha_termino];
        $estado         = $post[estado];
        header('Content-type: text/plain');
        header('Content-Disposition: attachment; filename=Reporte_Full_' . $txt . '.csv');
        echo "rut;rut_completo;nombre;cargo;c1;c2;c3;c4;id_foco;foco;id_programa_global;programa_global;id_programa;programa;id_curso;curso;id_inscripcion;nombre_inscripcion;id_malla;malla;fecha_inicio_inscripcion;fecha_termino_inscripcion;tipo_curso;fecha_inicio;anno_inicio;mes_inicio;fecha_termino;anno_termino;mes_termino;estado;modalidad;asistencia;evaluacion;horas;lugar;curso_opcional;rut_ejecutivo;ejecutivo;rut_jefe;jefe;empresa\r\n";
        $Total_Rows_full = lms_reportes_full_excel_data($id_empresa, $id_foco, $id_programa, $id_programa_elearning, $id_malla, $id_curso,
        $tipo_filtro, $imparticion, $ejecutivo, $modalidad, $fecha_inicio, $fecha_termino, $estado);
        foreach ($Total_Rows_full as $Unico) {
           if ($Unico->status == "Presencial") {
                $Unico->programa_global = $Unico->programa;
                $Unico->id_programa_global = $Unico->id_programa;
                $Unico->programa = "";
                $Unico->id_programa = "";
            }
            $ESvigente=UsuarioVigente($Unico->rut);
            if($ESvigente>0){
            }   else {
                if($vigente=="vigente"){continue;}
                $Unico->rut_completo="";
                $Unico->nombre="USUARIO_NO_VIGENTE";
                $Unico->cargo="";
                $Unico->c1="";
                $Unico->c2="";
                $Unico->c3="";
                $Unico->c4="";
            }
                echo $Unico->rut. ";" .
                $Unico->rut_completo. ";" .
                $Unico->nombre. ";" .
                $Unico->cargo. ";" .
                $Unico->c1. ";" .
                $Unico->c2. ";" .
                $Unico->c3. ";" .
                $Unico->c4. ";" .
                $Unico->id_foco. ";" .
                $Unico->foco. ";" .
                $Unico->id_programa_global. ";" .
                $Unico->programa_global. ";" .
                $Unico->id_programa. ";" .
                $Unico->programa. ";" .
                $Unico->id_curso. ";" .
                $Unico->curso. ";" .
                $Unico->id_inscripcion. ";" .
                $Unico->nombre_inscripcion. ";" .
                $Unico->id_malla. ";" .
                $Unico->malla. ";" .
                $Unico->fecha_inicio_inscripcion. ";" .
                $Unico->fecha_termino_inscripcion. ";" .
                $Unico->tipo_curso. ";" .
                $Unico->fecha_inicio. ";" .
                $Unico->anno_inicio. ";" .
                $Unico->mes_inicio. ";" .
                $Unico->fecha_termino. ";" .
                $Unico->anno_termino. ";" .
                $Unico->mes_termino. ";" .
                $Unico->estado. ";" .
                $Unico->status. ";" .
                $Unico->asistencia. ";" .
                $Unico->evaluacion. ";" .
                $Unico->horas. ";" .
                $Unico->lugar. ";" .
                $Unico->curso_opcional. ";" .
                $Unico->rut_ejecutivo. ";" .
                $Unico->ejecutivo. ";" .
                $Unico->rut_jefe. ";" .
                $Unico->jefe. ";" .
                $Unico->empresa. "\r\n";
            }
             exit();
        }


			if($post["id_programa_express"]<>""){



			}

    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/reportes_full/" . $id_empresa . "_entorno.html"));
    $PRINCIPAL = str_replace("{ENTORNO}", Reporte_Full_Filtros_v2(FuncionesTransversalesAdmin(file_get_contents("views/reportes_full/" . $id_empresa . "_entorno_reportes_full_online_v2.html")), $id_empresa, $arreglo_post, $excel), $PRINCIPAL);

    $datos_empresa = DatosEmpresa($id_empresa);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;

}
else if ($seccion == "buffer") {


// Fallback para garantir que não vai ter a configuração no php.ini

ini_set("zlib.output_compression",0);
ini_set("output_buffering",0);

ini_set("implicit_flush",1);

ob_end_flush();
ob_start();
set_time_limit(0);
error_reporting(0);
     if (ob_get_level() == 0) ob_start();


foreach (range(1, 5) as $value)
{
    echo "Imprimindo {$value}";
    ob_flush();
    flush(); //ie working must
    sleep(1);
}
     ob_end_flush();


}
else if ($seccion == "lms_reportes_full") {
    $id_empresa = $_SESSION["id_empresa"];
    $array_duplicaciones=ReporteFull_Duplicados_data($id_empresa);
    foreach ($array_duplicaciones as $unico){
        BorraDuplicacionesReporteFull($unico->rut, $unico->id_inscripcion, $unico->id_curso, $unico->asistencia, $unico->evaluacion);
    }
    $cursoscol=$post[cursoscol];
    $vigente=$post[vigente];
// BUSCA NOMBRE ARCHIVO
        $id_foco        = $post[foco];
        $id_programa    = $post[programaglobal];
        $id_programa_elearning = $post[programabbdd];
        $id_malla       = $post[malla];
        $id_curso       = $post[cursos];
        $id_curso3      = $post[cursos3];
        $tipo_filtro    = $post[tipo_filtro];
        if($id_curso3<>'0' and $id_curso=='0'){$id_curso=$id_curso3;}
        $imparticion    = $post[imparticion];
        $ejecutivo      = $post[ejecutivo];
        $modalidad      = $post[modalidad];
        $fecha_inicio   = $post[fecha_inicio];
        $fecha_termino  = $post[fecha_termino];
        $estado         = $post[estado];

        $txt= $id_foco.$id_programa.$id_programa_elearning.$id_malla.$id_curso. $fecha_inicio.$fecha_termino. $estado;
		    

    if ($vigente== "cursoscol") {
        
        $id_foco        = $post[foco];
        $id_programa    = $post[programaglobal];
        $id_programa_elearning = $post[programabbdd];
        $id_malla       = $post[malla];
        $id_curso       = $post[cursos];
        $id_curso3      = $post[cursos3];
        $tipo_filtro    = $post[tipo_filtro];
        if($id_curso3<>'0' and $id_curso=='0'){$id_curso=$id_curso3;}
        $imparticion    = $post[imparticion];
        $ejecutivo      = $post[ejecutivo];
        $modalidad      = $post[modalidad];
        $fecha_inicio   = $post[fecha_inicio];
        $fecha_termino  = $post[fecha_termino];
        $estado         = $post[estado];
        header('Content-type: text/plain');
        header('Content-Disposition: attachment; filename=Reporte_Full_' . $txt . '.csv');
        echo "rut;rut_completo;nombre;cargo;c1;c2;c3;c4;";

$array_rut_id_curso=lms_reportes_full_excel_solocurso_data($id_empresa, $id_foco, $id_programa, $id_programa_elearning, $id_malla, $id_curso,
$tipo_filtro, $imparticion, $ejecutivo, $modalidad, $fecha_inicio, $fecha_termino, $estado, $Unico->rut);
$k=0;
   foreach ($array_rut_id_curso as $Unico3){

                    echo  $Unico3->id_curso.  ";" .
                    $Unico3->curso.  ";" .
                    "Fecha_inicio;" .
                    "Fecha_termino;" .
                    "Modalidad;" .
                    "Asistencia;" .
                    "Evaluacion;" .
                    "Estado;" ;

$array_id_curso[$k]= $Unico3->id_curso;
     $k++;
                }
         echo "\r\n";


      $Total_Rows_full = lms_reportes_full_excel_filtro_rut_data($id_empresa, $id_foco, $id_programa, $id_programa_elearning, $id_malla, $id_curso,
        $tipo_filtro, $imparticion, $ejecutivo, $modalidad, $fecha_inicio, $fecha_termino, $estado);
        foreach ($Total_Rows_full as $Unico) {
           if ($Unico->status == "Presencial") {
                $Unico->programa_global = $Unico->programa;
                $Unico->id_programa_global = $Unico->id_programa;
                $Unico->programa = "";
                $Unico->id_programa = "";
            }
            $ESvigente=UsuarioVigente($Unico->rut);
            if($ESvigente>0){
            }   else {
                if($vigente=="vigente"){continue;}
                $Unico->rut_completo="";
                $Unico->nombre="USUARIO_NO_VIGENTE";
                $Unico->cargo="";
                $Unico->c1="";
                $Unico->c2="";
                $Unico->c3="";
                $Unico->c4="";
            }
                echo $Unico->rut. ";" .
                $Unico->rut_completo. ";" .
                $Unico->nombre. ";" .
                $Unico->cargo. ";" .
                $Unico->c1. ";" .
                $Unico->c2. ";" .
                $Unico->c3. ";" .
                $Unico->c4. ";" ;

foreach ($array_id_curso as $Unico3){

$array_rut_id_curso_l2=lms_reportes_full_excel_solocurso_rut_data($id_empresa, $id_foco, $id_programa, $id_programa_elearning, $id_malla,
$Unico3,
$tipo_filtro, $imparticion, $ejecutivo, $modalidad, $fecha_inicio, $fecha_termino, $estado, $Unico->rut);
if(count($array_rut_id_curso_l2)==0){
                        echo  $Unico3.  ";" .
                    "".  ";" .
                    "".  ";" .
                    "".  ";" .
                    "".  ";" .
                    "".  ";" .
                    "".  ";" .
                    "".  ";" ;
}   else {

                foreach ($array_rut_id_curso_l2 as $Unico4){

                    echo  $Unico4->id_curso.  ";" .
                    $Unico4->curso.  ";" .
                    $Unico4->fecha_inicio.  ";" .
                    $Unico4->fecha_termino.  ";" .
                    $Unico4->status.  ";" .
                    $Unico4->asistencia.  ";" .
                    $Unico4->evaluacion.  ";" .
                    $Unico4->estado.  ";" ;

          }

                }
    }
             echo "\r\n";

            }
                   exit();
        }

    if ($post[GeneraExcel] == "GeneraExcel") {
        $id_foco        = $post[foco];
        $id_programa    = $post[programaglobal];
        $id_programa_elearning = $post[programabbdd];
        $id_malla       = $post[malla];
        $id_curso       = $post[cursos];
        $id_curso3      = $post[cursos3];
        $tipo_filtro    = $post[tipo_filtro];
        if($id_curso3<>'0' and $id_curso=='0'){$id_curso=$id_curso3;}
        $imparticion    = $post[imparticion];
        $ejecutivo      = $post[ejecutivo];
        $modalidad      = $post[modalidad];
        $fecha_inicio   = $post[fecha_inicio];
        $fecha_termino  = $post[fecha_termino];
        $estado         = $post[estado];
        header('Content-type: text/plain');
        header('Content-Disposition: attachment; filename=Reporte_Full_' . $txt . '.csv');
        echo "rut;rut_completo;nombre;cargo;c1;c2;c3;c4;id_foco;foco;id_programa_global;programa_global;id_programa;programa;id_curso;curso;id_inscripcion;nombre_inscripcion;id_malla;malla;fecha_inicio_inscripcion;fecha_termino_inscripcion;tipo_curso;fecha_inicio;anno_inicio;mes_inicio;fecha_termino;anno_termino;mes_termino;estado;modalidad;asistencia;evaluacion;horas;lugar;curso_opcional;rut_ejecutivo;ejecutivo;rut_jefe;jefe;empresa\r\n";
        $Total_Rows_full = lms_reportes_full_excel_data($id_empresa, $id_foco, $id_programa, $id_programa_elearning, $id_malla, $id_curso,
        $tipo_filtro, $imparticion, $ejecutivo, $modalidad, $fecha_inicio, $fecha_termino, $estado);
        foreach ($Total_Rows_full as $Unico) {
           if ($Unico->status == "Presencial") {
                $Unico->programa_global = $Unico->programa;
                $Unico->id_programa_global = $Unico->id_programa;
                $Unico->programa = "";
                $Unico->id_programa = "";
            }
            $ESvigente=UsuarioVigente($Unico->rut);
            if($ESvigente>0){
            }   else {
                if($vigente=="vigente"){continue;}
                $Unico->rut_completo="";
                $Unico->nombre="USUARIO_NO_VIGENTE";
                $Unico->cargo="";
                $Unico->c1="";
                $Unico->c2="";
                $Unico->c3="";
                $Unico->c4="";
            }
                echo $Unico->rut. ";" .
                $Unico->rut_completo. ";" .
                $Unico->nombre. ";" .
                $Unico->cargo. ";" .
                $Unico->c1. ";" .
                $Unico->c2. ";" .
                $Unico->c3. ";" .
                $Unico->c4. ";" .
                $Unico->id_foco. ";" .
                $Unico->foco. ";" .
                $Unico->id_programa_global. ";" .
                $Unico->programa_global. ";" .
                $Unico->id_programa. ";" .
                $Unico->programa. ";" .
                $Unico->id_curso. ";" .
                $Unico->curso. ";" .
                $Unico->id_inscripcion. ";" .
                $Unico->nombre_inscripcion. ";" .
                $Unico->id_malla. ";" .
                $Unico->malla. ";" .
                $Unico->fecha_inicio_inscripcion. ";" .
                $Unico->fecha_termino_inscripcion. ";" .
                $Unico->tipo_curso. ";" .
                $Unico->fecha_inicio. ";" .
                $Unico->anno_inicio. ";" .
                $Unico->mes_inicio. ";" .
                $Unico->fecha_termino. ";" .
                $Unico->anno_termino. ";" .
                $Unico->mes_termino. ";" .
                $Unico->estado. ";" .
                $Unico->status. ";" .
                $Unico->asistencia. ";" .
                $Unico->evaluacion. ";" .
                $Unico->horas. ";" .
                $Unico->lugar. ";" .
                $Unico->curso_opcional. ";" .
                $Unico->rut_ejecutivo. ";" .
                $Unico->ejecutivo. ";" .
                $Unico->rut_jefe. ";" .
                $Unico->jefe. ";" .
                $Unico->empresa. "\r\n";
            }
             exit();
        }


			if($post["id_programa_express"]<>""){

					Check2020_Reportes_IdProgramaNull_data($_SESSION["id_empresa"]);
					$hoy = date("Y-m-d");

					header('Content-type: text/plain');
        	header('Content-Disposition: attachment; filename=Reporte_Online_' . $post["id_programa_express"] . '_' . $hoy . '.csv');

						$Programa=ObtieneDatosProgramasPorEmpresa($post["id_programa_express"], $id_empresa);
						$id_programa_express= isset($post["id_programa_express"]) ? htmlspecialchars($post["id_programa_express"], ENT_QUOTES) : ''; // Sanitizar
        		echo "IdPrograma;".$id_programa_express."\r\n";
        		echo "Programa;".$Programa[0]->nombre_programa."\r\n";
        		echo "\r\n";

				// Resumen
						$num_ausentismo=0;$num_noiniciados=0;$num_enproceso=0;$num_aprobados=0;$num_reprobados=0;$num_participantes=0;

					 $array_programa = Reportes_Express_Resultado($post["id_programa_express"],$post["fecha_inicio_exp"],$post["fecha_termino_exp"]);
		       foreach ($array_programa as $Unico_reporte){


							$num_participantes++;
							$arreglo_division[$Unico_reporte->c1]["num_participantes"]=$arreglo_division[$Unico_reporte->c1]["num_participantes"]+1;
							$arreglo_nombre_empresa[$Unico_reporte->nombre_empresa_holding]["num_participantes"]=$arreglo_nombre_empresa[$Unico_reporte->nombre_empresa_holding]["num_participantes"]+1;
							$explode_fi	= [];
							$explode_ft	= [];
							$fecha_inicio		="";
							$fecha_termino	="";
							$Ausentismo="";
							$Ausentismo_individual="";

							if($Unico_reporte->fecha_desde<>''){
								$explode_fi	=	explode("/", $Unico_reporte->fecha_desde);
								$explode_ft	=	explode("/", $Unico_reporte->fecha_hasta);
								$fi=$explode_fi[2]."-".$explode_fi[1]."-".$explode_fi[0];
								$ft=$explode_ft[2]."-".$explode_ft[1]."-".$explode_ft[0];
								
								if($hoy>=$fi and $hoy<=$ft and ($Unico_reporte->estado=="NO_INICIADO" or $Unico_reporte->estado=="EN_PROCESO")){
														$Ausentismo="Ausentismo";
														$num_ausentismo++;
														$Ausentismo_individual="SI";
														$arreglo_division[$Unico_reporte->c1]["num_ausentismo"]=$arreglo_division[$Unico_reporte->c1]["num_ausentismo"]+1;
														$arreglo_nombre_empresa[$Unico_reporte->nombre_empresa_holding]["num_ausentismo"]=$arreglo_nombre_empresa[$Unico_reporte->nombre_empresa_holding]["num_ausentismo"]+1;

								} else {
														$Ausentismo_individual="SI";
														if($Unico_reporte->estado=="NO_INICIADO"){
															$num_noiniciados++;
															$arreglo_division[$Unico_reporte->c1]["num_noiniciados"]=$arreglo_division[$Unico_reporte->c1]["num_noiniciados"]+1;
															$arreglo_nombre_empresa[$Unico_reporte->nombre_empresa_holding]["num_noiniciados"]=$arreglo_nombre_empresa[$Unico_reporte->nombre_empresa_holding]["num_noiniciados"]+1;
															}
														if($Unico_reporte->estado=="EN_PROCESO"){
															$num_enproceso++;
															$arreglo_division[$Unico_reporte->c1]["num_enproceso"]=$arreglo_division[$Unico_reporte->c1]["num_enproceso"]+1;
															$arreglo_nombre_empresa[$Unico_reporte->nombre_empresa_holding]["num_enproceso"]=$arreglo_nombre_empresa[$Unico_reporte->nombre_empresa_holding]["num_enproceso"]+1;
															}
									$Ausentismo="";
								}

								

							} else {
														if($Unico_reporte->estado=="NO_INICIADO"){$num_noiniciados++;
															$arreglo_division[$Unico_reporte->c1]["num_noiniciados"]=$arreglo_division[$Unico_reporte->c1]["num_noiniciados"]+1;
															$arreglo_nombre_empresa[$Unico_reporte->nombre_empresa_holding]["num_noiniciados"]=$arreglo_nombre_empresa[$Unico_reporte->nombre_empresa_holding]["num_noiniciados"]+1;
															}
														if($Unico_reporte->estado=="EN_PROCESO"){$num_enproceso++;
															$arreglo_division[$Unico_reporte->c1]["num_enproceso"]=$arreglo_division[$Unico_reporte->c1]["num_enproceso"]+1;
															$arreglo_nombre_empresa[$Unico_reporte->nombre_empresa_holding]["num_enproceso"]=$arreglo_nombre_empresa[$Unico_reporte->nombre_empresa_holding]["num_enproceso"]+1;

															}
							}

							if($Unico_reporte->estado=="APROBADO"){
								$num_aprobados++;
								$arreglo_division[$Unico_reporte->c1]["num_aprobados"]=$arreglo_division[$Unico_reporte->c1]["num_aprobados"]+1;
								$arreglo_nombre_empresa[$Unico_reporte->nombre_empresa_holding]["num_aprobados"]=$arreglo_nombre_empresa[$Unico_reporte->nombre_empresa_holding]["num_aprobados"]+1;
								}
							if($Unico_reporte->estado=="REPROBADO"){
								$num_reprobados++;
								$arreglo_division[$Unico_reporte->c1]["num_reprobados"]=$arreglo_division[$Unico_reporte->c1]["num_reprobados"]+1;
								$arreglo_nombre_empresa[$Unico_reporte->nombre_empresa_holding]["num_reprobados"]=$arreglo_nombre_empresa[$Unico_reporte->nombre_empresa_holding]["num_reprobados"]+1;
								}

							$num_activos=$num_participantes-$num_ausentismo;


          }

								echo "Total;Participantes;Ausentismo;Activos;Aprobados;Reprobados;En Proceso;No Iniciados\r\n";

								echo "Banco;".$num_participantes.";".$num_ausentismo.";".$num_activos.";".$num_aprobados.";".$num_reprobados.";".$num_enproceso.";".$num_noiniciados."\r\n";

						echo "\r\n";
        		echo "\r\n";

								echo "Division;Participantes;Ausentismo;Activos;Aprobados;Reprobados;En Proceso;No Iniciados\r\n";

									foreach ($arreglo_division as $key => $value){

														$value["num_activos"]=$value["num_participantes"]-$value["num_ausentismo"];
														echo "$key;".$value["num_participantes"].";".$value["num_ausentismo"].";".$value["num_activos"].";".$value["num_aprobados"].";".$value["num_reprobados"].";".$value["num_enproceso"].";".$value["num_noiniciados"]."\r\n";



									}

						echo "\r\n";
        		echo "\r\n";

								echo "Empresa;Participantes;Ausentismo;Activos;Aprobados;Reprobados;En Proceso;No Iniciados\r\n";

								foreach ($arreglo_nombre_empresa as $key => $value){
									$value["num_activos"]=$value["num_participantes"]-$value["num_ausentismo"];
														echo "$key;".$value["num_participantes"].";".$value["num_ausentismo"].";".$value["num_activos"].";".$value["num_aprobados"].";".$value["num_reprobados"].";".$value["num_enproceso"].";".$value["num_noiniciados"]."\r\n";


									}


						echo "\r\n";
        		echo "\r\n";

								foreach ($arreglo_division as $key => $value){
									$value["num_activos"]=$value["num_participantes"]-$value["num_ausentismo"];
														echo "$key;".$value["num_participantes"].";".$value["num_ausentismo"].";".$value["num_activos"].";".$value["num_aprobados"].";".$value["num_reprobados"].";".$value["num_enproceso"].";".$value["num_noiniciados"]."\r\n";


									}





						echo "\r\n";
        		echo "\r\n";
        		echo "Reprobados: Avance 100% y Nota bajo 80% - Aprobados: Avance 100% y Nota igual o sobre 80%. Ausentismo sobre No iniciados y En Proceso\r\n";
        		echo "\r\n";
        		echo "\r\n";

        		echo "rut;rut_completo;nombre;cargo;division;zona;departamento;cui;id_programa;programa;id_malla;malla;id_curso;curso;avance_asistencia;resultado_evaluacion;fecha_inicio;fecha_termino;curso_opcional;estado;fecha_inscripcion;jefe;Ausentismo;Evaluacion_Satistaccion;Comentario_Satisfaccion\r\n";

		       foreach ($array_programa as $Unico_reporte){
		       	$Ausentismo_Individual2="";
							if($Unico_reporte->fecha_desde<>''){
								$explode_fi	=	explode("/", $Unico_reporte->fecha_desde);
								$explode_ft	=	explode("/", $Unico_reporte->fecha_hasta);
								$fi=$explode_fi[2]."-".$explode_fi[1]."-".$explode_fi[0];
								$ft=$explode_ft[2]."-".$explode_ft[1]."-".$explode_ft[0];
								
								if($hoy>=$fi and $hoy<=$ft){
														$Ausentismo_Individual2="Ausentismo";

								} else {
														$Ausentismo_Individual2="";
								}

		       	}

								$Unico_reporte->Comentario_Satisfaccion = preg_replace("/[\r\n|\n|\r]+/", PHP_EOL, $Unico_reporte->Comentario_Satisfaccion);
								$Unico_reporte->Comentario_Satisfaccion = preg_replace("/[\r\n|\n|\r]+/", " ", $Unico_reporte->Comentario_Satisfaccion);
								echo  $Unico_reporte->rut.  ";" .$Unico_reporte->rut_completo.  ";" .$Unico_reporte->nombre.  ";" .$Unico_reporte->cargo.  ";" .$Unico_reporte->c1.  ";" .$Unico_reporte->c2.  ";" .$Unico_reporte->c3.  ";" .$Unico_reporte->c4.  ";" .$Unico_reporte->id_programa.  ";" .$Unico_reporte->programa.  ";" .$Unico_reporte->id_malla.  ";" .$Unico_reporte->malla.  ";" .$Unico_reporte->id_curso.  ";" .$Unico_reporte->curso.  ";" .$Unico_reporte->avance_asistencia.  ";" .round($Unico_reporte->resultado_evaluacion).  ";" .$Unico_reporte->fecha_inicio.  ";" .$Unico_reporte->fecha_termino.  ";" .$Unico_reporte->curso_opcional.  ";" .$Unico_reporte->estado.  ";" .$Unico_reporte->fecha_inscripcion.  ";" .$Unico_reporte->jefe.  ";" .$Ausentismo_Individual2.";" .$Unico_reporte->Evaluacion_Satistaccion.  ";" .$Unico_reporte->Comentario_Satisfaccion."\r\n";

          }
				exit();

			}

			if($post["rut_express"]<>""){
						$hoy = date("Y-m-d");
						header('Content-type: text/plain');
        		header('Content-Disposition: attachment; filename=Reporte_Online_' . $post["rut_express"] . '_' . $hoy . '.csv');



        		echo "rut;rut_completo;nombre;cargo;division;zona;departamento;cui;id_programa;programa;id_malla;malla;id_curso;curso;avance_asistencia;resultado_evaluacion;fecha_inicio;fecha_termino;curso_opcional;estado;fecha_inscripcion;jefe;Evaluacion_Satistaccion;Comentario_Satisfaccion\r\n";

					 $array_programa = Reportes_Express_Resultado($post["id_programa_express"],$post["fecha_inicio_exp"],$post["fecha_termino_exp"],$post["rut_express"]);
		       foreach ($array_programa as $Unico_reporte){
								$Unico_reporte->Comentario_Satisfaccion = preg_replace("/[\r\n|\n|\r]+/", PHP_EOL, $Unico_reporte->Comentario_Satisfaccion);
								$Unico_reporte->Comentario_Satisfaccion = preg_replace("/[\r\n|\n|\r]+/", " ", $Unico_reporte->Comentario_Satisfaccion);
								echo  $Unico_reporte->rut.  ";" .$Unico_reporte->rut_completo.  ";" .$Unico_reporte->nombre.  ";" .$Unico_reporte->cargo.  ";" .$Unico_reporte->c1.  ";" .$Unico_reporte->c2.  ";" .$Unico_reporte->c3.  ";" .$Unico_reporte->c4.  ";" .$Unico_reporte->id_programa.  ";" .$Unico_reporte->programa.  ";" .$Unico_reporte->id_malla.  ";" .$Unico_reporte->malla.  ";" .$Unico_reporte->id_curso.  ";" .$Unico_reporte->curso.  ";" .$Unico_reporte->avance_asistencia.  ";" .$Unico_reporte->resultado_evaluacion.  ";" .$Unico_reporte->fecha_inicio.  ";" .$Unico_reporte->fecha_termino.  ";" .$Unico_reporte->curso_opcional.  ";" .$Unico_reporte->estado.  ";" .$Unico_reporte->fecha_inscripcion.  ";" .$Unico_reporte->jefe.  ";" .$Unico_reporte->Evaluacion_Satistaccion.  ";" .$Unico_reporte->Comentario_Satisfaccion."\r\n";

          }
				exit();

			}


    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/reportes_full/" . $id_empresa . "_entorno.html"));
    $PRINCIPAL = str_replace("{ENTORNO}", Reporte_Full_Filtros(FuncionesTransversalesAdmin(file_get_contents("views/reportes_full/" . $id_empresa . "_entorno_reportes_full.html")), $id_empresa, $arreglo_post, $excel), $PRINCIPAL);



    $datos_empresa = DatosEmpresa($id_empresa);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;

}
else if ($seccion == "lms_reportes_full_v2") {
    $id_empresa = $_SESSION["id_empresa"];
    // BUSCA DUPLICACIONES REPORTES FULL
    $array_duplicaciones=ReporteFull_Duplicados_data($id_empresa);
    foreach ($array_duplicaciones as $unico){
        BorraDuplicacionesReporteFull($unico->rut, $unico->id_inscripcion, $unico->id_curso, $unico->asistencia, $unico->evaluacion);
    }
    $cursoscol=$post[cursoscol];
    $vigente=$post[vigente];
// BUSCA NOMBRE ARCHIVO
        $id_foco        = $post[foco];
        $id_programa    = $post[programaglobal];
        $id_programa_elearning = $post[programabbdd];
        $id_malla       = $post[malla];
        $id_curso       = $post[cursos];
        $id_curso3      = $post[cursos3];
        $tipo_filtro    = $post[tipo_filtro];
        if($id_curso3<>'0' and $id_curso=='0'){$id_curso=$id_curso3;}
        $imparticion    = $post[imparticion];
        $ejecutivo      = $post[ejecutivo];
        $modalidad      = $post[modalidad];
        $fecha_inicio   = $post[fecha_inicio];
        $fecha_termino  = $post[fecha_termino];
        $estado         = $post[estado];

        $txt= $id_foco.$id_programa.$id_programa_elearning.$id_malla.$id_curso. $fecha_inicio.$fecha_termino. $estado;
		    


    if ($post[GeneraExcel] == "GeneraExcel") {
        $id_foco        = $post[foco];
        $id_programa    = $post[programaglobal];
        $id_programa_elearning = $post[programabbdd];
        $id_malla       = $post[malla];
        $id_curso       = $post[cursos];
        $id_curso3      = $post[cursos3];
        $tipo_filtro    = $post[tipo_filtro];
        if($id_curso3<>'0' and $id_curso=='0'){$id_curso=$id_curso3;}
        $imparticion    = $post[imparticion];
        $ejecutivo      = $post[ejecutivo];
        $modalidad      = $post[modalidad];
        $fecha_inicio   = $post[fecha_inicio];
        $fecha_termino  = $post[fecha_termino];
        $estado         = $post[estado];
        header('Content-type: text/plain');
        header('Content-Disposition: attachment; filename=Reporte_Full_' . $txt . '.csv');
        echo "rut;rut_completo;nombre;cargo;c1;c2;c3;c4;id_foco;foco;id_programa_global;programa_global;id_programa;programa;id_curso;curso;id_inscripcion;nombre_inscripcion;id_malla;malla;fecha_inicio_inscripcion;fecha_termino_inscripcion;tipo_curso;fecha_inicio;anno_inicio;mes_inicio;fecha_termino;anno_termino;mes_termino;estado;modalidad;asistencia;evaluacion;horas;lugar;curso_opcional;rut_ejecutivo;ejecutivo;rut_jefe;jefe;empresa\r\n";
        $Total_Rows_full = lms_reportes_full_excel_data($id_empresa, $id_foco, $id_programa, $id_programa_elearning, $id_malla, $id_curso,
        $tipo_filtro, $imparticion, $ejecutivo, $modalidad, $fecha_inicio, $fecha_termino, $estado);
        foreach ($Total_Rows_full as $Unico) {
           if ($Unico->status == "Presencial") {
                $Unico->programa_global = $Unico->programa;
                $Unico->id_programa_global = $Unico->id_programa;
                $Unico->programa = "";
                $Unico->id_programa = "";
            }
            $ESvigente=UsuarioVigente($Unico->rut);
            if($ESvigente>0){
            }   else {
                if($vigente=="vigente"){continue;}
                $Unico->rut_completo="";
                $Unico->nombre="USUARIO_NO_VIGENTE";
                $Unico->cargo="";
                $Unico->c1="";
                $Unico->c2="";
                $Unico->c3="";
                $Unico->c4="";
            }
                echo $Unico->rut. ";" .
                $Unico->rut_completo. ";" .
                $Unico->nombre. ";" .
                $Unico->cargo. ";" .
                $Unico->c1. ";" .
                $Unico->c2. ";" .
                $Unico->c3. ";" .
                $Unico->c4. ";" .
                $Unico->id_foco. ";" .
                $Unico->foco. ";" .
                $Unico->id_programa_global. ";" .
                $Unico->programa_global. ";" .
                $Unico->id_programa. ";" .
                $Unico->programa. ";" .
                $Unico->id_curso. ";" .
                $Unico->curso. ";" .
                $Unico->id_inscripcion. ";" .
                $Unico->nombre_inscripcion. ";" .
                $Unico->id_malla. ";" .
                $Unico->malla. ";" .
                $Unico->fecha_inicio_inscripcion. ";" .
                $Unico->fecha_termino_inscripcion. ";" .
                $Unico->tipo_curso. ";" .
                $Unico->fecha_inicio. ";" .
                $Unico->anno_inicio. ";" .
                $Unico->mes_inicio. ";" .
                $Unico->fecha_termino. ";" .
                $Unico->anno_termino. ";" .
                $Unico->mes_termino. ";" .
                $Unico->estado. ";" .
                $Unico->status. ";" .
                $Unico->asistencia. ";" .
                $Unico->evaluacion. ";" .
                $Unico->horas. ";" .
                $Unico->lugar. ";" .
                $Unico->curso_opcional. ";" .
                $Unico->rut_ejecutivo. ";" .
                $Unico->ejecutivo. ";" .
                $Unico->rut_jefe. ";" .
                $Unico->jefe. ";" .
                $Unico->empresa. "\r\n";
            }
             exit();
        }


			if($post["id_programa_express"]<>""){

					Check2020_Reportes_IdProgramaNull_data($_SESSION["id_empresa"]);

					header('Content-Type: text/csv');
        	header('Content-Disposition: attachment; filename=Reporte_Online_' . $post["id_programa_express"] . '_' . $hoy . '.csv');


					$hoy = date("Y-m-d");


						$Programa=ObtieneDatosProgramasPorEmpresa($post["id_programa_express"], $id_empresa);


				// Resumen
						$num_ausentismo=0;$num_noiniciados=0;$num_enproceso=0;$num_aprobados=0;$num_reprobados=0;$num_participantes=0;

					 $array_programa = Reportes_Express_Resultado_v2($post["id_programa_express"],$post["fecha_inicio_exp"],$post["fecha_termino_exp"]);
		       foreach ($array_programa as $Unico_reporte){

							$num_participantes++;
							$arreglo_division[$Unico_reporte->c1]["num_participantes"]=$arreglo_division[$Unico_reporte->c1]["num_participantes"]+1;
							$arreglo_nombre_empresa[$Unico_reporte->nombre_empresa_holding]["num_participantes"]=$arreglo_nombre_empresa[$Unico_reporte->nombre_empresa_holding]["num_participantes"]+1;
							$explode_fi	= [];
							$explode_ft	= [];
							$fecha_inicio		="";
							$fecha_termino	="";
							$Ausentismo="";
							$Ausentismo_individual="";


	         echo "rut;rut_completo;nombre;cargo;division;zona;departamento;cui;id_programa;programa;id_malla;malla;id_curso;curso;avance_asistencia;resultado_evaluacion;fecha_inicio;fecha_termino;curso_opcional;estado;fecha_inscripcion;jefe;Ausentismo;Vigencia;Evaluacion_Satistaccion;Comentario_Satisfaccion\r\n";

		       foreach ($array_programa as $Unico_reporte){

				       	$Ausentismo_Individual2="";
									if($Unico_reporte->fecha_desde<>''){
										$explode_fi	=	explode("/", $Unico_reporte->fecha_desde);
										$explode_ft	=	explode("/", $Unico_reporte->fecha_hasta);
										$fi=$explode_fi[2]."-".$explode_fi[1]."-".$explode_fi[0];
										$ft=$explode_ft[2]."-".$explode_ft[1]."-".$explode_ft[0];
										
										if($hoy>=$fi and $hoy<=$ft){
																$Ausentismo_Individual2="Ausentismo";

										} else {
																$Ausentismo_Individual2="";
										}

				       	}

										$Unico_reporte->Comentario_Satisfaccion = preg_replace("/[\r\n|\n|\r]+/", PHP_EOL, $Unico_reporte->Comentario_Satisfaccion);
										$Unico_reporte->Comentario_Satisfaccion = preg_replace("/[\r\n|\n|\r]+/", " ", $Unico_reporte->Comentario_Satisfaccion);
										echo  $Unico_reporte->rut.  ";" .$Unico_reporte->rut_completo.  ";" .$Unico_reporte->nombre.  ";" .$Unico_reporte->cargo.  ";" .$Unico_reporte->c1.  ";" .$Unico_reporte->c2.  ";" .$Unico_reporte->c3.  ";" .$Unico_reporte->c4.  ";" .$Unico_reporte->id_programa.  ";" .$Unico_reporte->programa.  ";" .$Unico_reporte->id_malla.  ";" .$Unico_reporte->malla.  ";" .$Unico_reporte->id_curso.  ";" .$Unico_reporte->curso.  ";" .$Unico_reporte->avance_asistencia.  ";" .round($Unico_reporte->resultado_evaluacion).  ";" .$Unico_reporte->fecha_inicio.  ";" .$Unico_reporte->fecha_termino.  ";" .$Unico_reporte->curso_opcional.  ";" .$Unico_reporte->estado.  ";" .$Unico_reporte->fecha_inscripcion.  ";" .$Unico_reporte->jefe.  ";" .$Ausentismo_Individual2.";" .$Vigencia.";" .$Unico_reporte->Evaluacion_Satistaccion.  ";" .$Unico_reporte->Comentario_Satisfaccion."\r\n";

          }
				exit();

			}

			}
    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/reportes_full/" . $id_empresa . "_entorno.html"));
    $PRINCIPAL = str_replace("{ENTORNO}", Reporte_Full_Filtros(FuncionesTransversalesAdmin(file_get_contents("views/reportes_full/" . $id_empresa . "_entorno_reportes_full.html")), $id_empresa, $arreglo_post, $excel), $PRINCIPAL);



    $datos_empresa = DatosEmpresa($id_empresa);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;

}
else if ($seccion == "formacion_continua") {
    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/formacion_continua/entorno.html"));
    $id_empresa = $_SESSION["id_empresa"];
    $PRINCIPAL = str_replace("{ENTORNO}", lista_formacion_continua(FuncionesTransversalesAdmin(file_get_contents("views/formacion_continua/entorno_formacioncontinua.html")), $id_empresa, $id_categoria), $PRINCIPAL);
    $datos_empresa = DatosEmpresa($id_empresa);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "eventos_gestion") {
    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/eventos/entorno.html"));
    $id_empresa = $_SESSION["id_empresa"];
    $id_dimension = $get["id_dimension"];
    $PRINCIPAL = str_replace("{ENTORNO}", lista_eventos_gestion(FuncionesTransversalesAdmin(file_get_contents("views/eventos/entorno_eventos_gestion.html")), $id_empresa, $id_dimension), $PRINCIPAL);

    $PRINCIPAL = str_replace("{ID_DIMENSION}", ($id_dimension), $PRINCIPAL);

    $datos_empresa = DatosEmpresa($id_empresa);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "ext_empresas_gestion") {
    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/ext_empresas/entorno.html"));
    $id_empresa = $_SESSION["id_empresa"];
    $id_dimension = $get["id_dimension"];
    $PRINCIPAL = str_replace("{ENTORNO}", lista_Ext_Empresas_gestion(FuncionesTransversalesAdmin(file_get_contents("views/ext_empresas/entorno_ext_empresas_gestion.html")), $id_empresa, $id_dimension), $PRINCIPAL);

    $PRINCIPAL = str_replace("{ID_DIMENSION}", ($id_dimension), $PRINCIPAL);

    $datos_empresa = DatosEmpresa($id_empresa);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "lista_notificaciones_textos") {
    $id_empresa = $_SESSION["id_empresa"];
	$array_post=$post;

$filtro_creador=$post["filtro_creador"];
$filtro_mes_ano=$post["filtro_mes_ano"];

$titulo			=	$post["titulo"];
$asunto			=	$post["asunto"];
$texto			=	$post["texto"];


if($get["del"]==1 and $get["id_not"]){


	DeleteNotificacionCreacionMatriz($get["id_not"]);

}

if($titulo<>"" and $asunto<>"" and $texto<>""){

	$titulo		=	($titulo);
	$asunto		=	($asunto);
	$texto		=	($texto);
// "texto $texto<br>";
$texto=str_replace("_SaltoLinea_", "<br>", $texto);
$texto=str_replace(" strong ", "<strong>", $texto);



    Insert_notificaciones_creacion($titulo, $asunto, $texto, $id_empresa);

}

    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/incentivos/entorno.html"));

    $PRINCIPAL = str_replace("{ENTORNO}", lista_notificaciones_creacion_fn(FuncionesTransversalesAdmin(file_get_contents("views/incentivos/entorno_audiencias_creacion.html")), $id_empresa, $id_categoria, $campo1, $campo2, $campo3, $campo4, $campo5, $campo6, $excluir, $nombre_audiencia, $descripcion_audiencia, $unico_filtro, $id_documento, $filtro_creador, $filtro_mes_ano), $PRINCIPAL);

    $datos_empresa = DatosEmpresa($id_empresa);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "lista_notificaciones") {
    $id_empresa 		= 	$_SESSION["id_empresa"];
	$array_post			=	$post;

$id_publicacion_envio	=	Decodear3($post["id_publicacion"]);
$tipo_notificaciones	=	($post["tipo_notificaciones"]);




// sendgrid
if($id_publicacion_envio<>"" and $tipo_notificaciones<>"")
{

	$array_notificaciones	=	Notificaciones_Creacion_DadoId($tipo_notificaciones,$id_empresa);
	
	$array_publicacion	=AudienciaPublicacion($id_publicacion_envio,$id_empresa);
	
	$fecha        	=fechaCastellano($fecha_inicio);
	$tipo        	="text/html";
	$titulo1    	="";
	$texto_url    	="";
	$texto1       	="";

	$texto2       	="";
	$texto3        	="";

	
	$array_lista_audiencia=Audiencia_lista_tbl_audiencia_id_audiencia_rut_sinEnviar($array_publicacion[0]->id_audiencia, $id_publicacion_envio,  $id_empresa);

	
$titulo1			=	($array_notificaciones[0]->titulo);
$subject			=	($array_notificaciones[0]->asunto);
$subtitulo1			=	($array_notificaciones[0]->texto);

    foreach($array_lista_audiencia as $arrayEnvioEmail)
			{
$to					=$arrayEnvioEmail->email;
$rut_sent			=$arrayEnvioEmail->rut;
//$to					="rod@gop.cl";

SendGrid_Email($to, $to, $from, $nombrefrom, $tipo, $subject, $titulo1,$subtitulo1,$texto1,$url,$texto_url, $texto2, $texto3, $texto4, $logo, $id_empresa, $url, "Matriz de Incentivos", $rut_sent, $id_inscripcion, "mis_incentivos");

AudienciaInsertNotificaciones($arrayEnvioEmail->rut,$id_publicacion_envio,$id_empresa);

	$i++;

	echo "<br>Notificacion $i enviada a ".$arrayEnvioEmail->email;
    flush();
    ob_flush();

}

echo "<script>window.location.href = '?sw=lista_notificaciones'</script>";


}

	$nombre_publicacion			=$post["nombre_documento"];
	$descripcion_documento		=$post["descripcion_documento"];

	$id_documento				=$post["documento"];
	$id_audiencia				=$post["audiencia"];

	$fecha_inicio				=$post["fecha_inicio"];
	$fecha_termino				=$post["fecha_termino"];

    $PRINCIPAL 		= FuncionesTransversalesAdmin(file_get_contents("views/incentivos/entorno.html"));
    $PRINCIPAL 		= str_replace("{ENTORNO}", lista_noti_fn(FuncionesTransversalesAdmin(file_get_contents("views/incentivos/entorno_notificaciones.html")), $id_empresa, $id_categoria, $campo1, $campo2, $campo3, $campo4, $campo5, $campo6, $excluir, $nombre_audiencia, $descripcion_audiencia, $unico_filtro, $id_documento), $PRINCIPAL);






    $datos_empresa = DatosEmpresa($id_empresa);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "vista_usuario_matriz") {
    $id_empresa = $_SESSION["id_empresa"];
	$array_post=$post;
		$nombre_publicacion			=$post["nombre_documento"];
	$descripcion_documento		=$post["descripcion_documento"];

	$id_documento					=$post["documento"];
	$id_audiencia					=$post["audiencia"];

	$fecha_inicio				=$post["fecha_inicio"];
	$fecha_termino				=$post["fecha_termino"];

    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/incentivos/entorno.html"));
    $PRINCIPAL = str_replace("{ENTORNO}", lista_talent(FuncionesTransversalesAdmin(file_get_contents("views/incentivos/entorno_vista_usuario.html")), $id_empresa, $id_categoria, $campo1, $campo2, $campo3, $campo4, $campo5, $campo6, $excluir, $nombre_audiencia, $descripcion_audiencia, $unico_filtro, $id_documento), $PRINCIPAL);
    $datos_empresa = DatosEmpresa($id_empresa);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "talent_perfiles") {
    $id_empresa = $_SESSION["id_empresa"];

    //procesa "Resultados".
    DeleteTbltbl_skills_resultados($id_empresa);
    talent_procesa($id_empresa);

    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/talent/entorno.html"));
    $PRINCIPAL = str_replace("{ENTORNO}", lista_talent(FuncionesTransversalesAdmin(file_get_contents("views/talent/entorno_talent.html")), $id_empresa, $id_categoria), $PRINCIPAL);

    $datos_empresa = DatosEmpresa($id_empresa);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "ReportesInscripcionesT") {
    $id_empresa = $_SESSION["id_empresa"];

    
    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/certificacionT/entorno.html"));
    $PRINCIPAL = str_replace("{ENTORNO}", lista_certificacionT(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/certificacionT/entorno_certificacion.html")), $id_empresa, $id_categoria), $PRINCIPAL);

    $datos_empresa = DatosEmpresa($id_empresa);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "academia_lider_vista_gestion") {
    $id_empresa = $_SESSION["id_empresa"];

    $r1 = $post['r1'];
    $r2 = $post['r2'];
    $r3 = $post['r3'];

    
    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/academia_lider/entorno.html"));
    $PRINCIPAL = str_replace("{ENTORNO}", lista_academia_lider(FuncionesTransversalesAdmin(file_get_contents("views/academia_lider/entorno_academia_gestion.html")), $id_empresa, $r1, $r2, $r3), $PRINCIPAL);
    $datos_empresa = DatosEmpresa($id_empresa);
    echo ($PRINCIPAL);
}
else if ($seccion == "academia_lider_vista") {
    $id_empresa = $_SESSION["id_empresa"];

    $r1 = $post['r1'];
    $r2 = $post['r2'];
    $r3 = $post['r3'];

    
    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/academia_lider/entorno.html"));
    $PRINCIPAL = str_replace("{ENTORNO}", lista_academia_lider(FuncionesTransversalesAdmin(file_get_contents("views/academia_lider/entorno_academia.html")), $id_empresa, $r1, $r2, $r3), $PRINCIPAL);
    $datos_empresa = DatosEmpresa($id_empresa);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "Clima_Encuesta_Seguimiento") {
    $id_empresa = $_SESSION["id_empresa"];
    $r1 = $post['r1'];
    $r2 = $post['r2'];
    $r3 = $post['r3'];
    $filtro = $post['filtro'];

    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/clima/entorno.html"));
    $PRINCIPAL = str_replace("{ENTORNO}", lista_clima_Seguimiento(FuncionesTransversalesAdmin(file_get_contents("views/clima/entorno_clima_seguimiento.html")), $id_empresa, $filtro), $PRINCIPAL);
    $datos_empresa = DatosEmpresa($id_empresa);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "Clima_Encuesta_Reportes") {
    $id_empresa = $_SESSION["id_empresa"];

    $r1 = $post['r1'];
    $r2 = $post['r2'];
    $r3 = $post['r3'];
    $filtro = $post['filtro'];

    $id_tipo = $post['id_tipo'];
    $id_malla = $post['id_malla'];
    $id_jefatura = $post['id_jefatura'];

    if ($id_tipo != '') {$filtro1 = $id_tipo;} else { $filtro1 = "";}
    if ($id_malla != '') {$filtro2 = $id_malla;} else { $filtro2 = "";}
    if ($id_jefatura != '') {$filtro3 = $id_jefatura;} else { $filtro3 = "";}

    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/clima/entorno.html"));
    $PRINCIPAL = str_replace("{ENTORNO}", lista_clima_Reportes(FuncionesTransversalesAdmin(file_get_contents("views/clima/entorno_clima_reportes.html")), $id_empresa, $filtro1, $filtro2, $filtro3), $PRINCIPAL);
    $datos_empresa = DatosEmpresa($id_empresa);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "formacioncontinua_validacion_admin") {
    $id_empresa = $_SESSION["id_empresa"];
    $id_tipo = $request["id"];
    $idenc = Decodear3($get["idenc"]);
    $idFC = Decodear3($get["idFC"]);
    $v = ($get["v"]);


    FC_actualizaEstado($idenc, $id_tipo, $v, $id_empresa);
      echo "<script>        location.href='?sw=formacioncontinua_validacion&id=$id_tipo';        </script>";
    exit();

}
else if ($seccion == "formacioncontinua_validacion") {
    $id_empresa = $_SESSION["id_empresa"];
    $id_tipo = $request["id"];
    $idenc = Decodear3($get["idenc"]);
    $d = $get["d"];
    $v = $get["v"];

    $array_usuario = TraeUsuarioBecas($idenc, $id_empresa);
    $rut_usuario = $array_usuario[0]->rut;
    $unico = TraeDatosUsuario($rut_usuario);
    $estado = "";
    if ($idenc != '' and $d != '') {
        if ($d == '1') {$estado = "SI";}
        if ($d == '2') {$estado = "NO";}
        $fechahoy = date("Y-m-d");
        BecasUpdateDocumentacion($idenc, $estado, $fechahoy);

        if ($id_empresa == "78") {
            $to = $unico[0]->email;
            $nombreto = $unico[0]->nombre_completo;
            $tipo = "text/html";
            $subject = "Documentación OK en la postulación a " . $array_usuario[0]->nombre_beca;
            $titulo1 = "Documentación OK en la postulación a " . $array_usuario[0]->nombre_beca;
            $subtitulo1 = "Estimad@ " . $unico[0]->nombre_completo . ", hemos revisado tu documentación y se ha subido correctamente.";
            $texto1 = "Durante los próximos días estaremos revisando si cumples con los requisitos para obtener la beca y/o premios.";
            $texto_url = "Ingresa aquí";
            $texto2 = "<br>¡Atento a los resultados!";
            $texto3 = "";
            $texto4 = "";
            $tipomensaje = "Becas_DocsOk";
            $url = $url_front;
            $rut = $rut_usuario;
            $key = $rut_usuario;
            echo "envio a $to";
            sleep(5);

        }
    }

    

    if ($idenc != '' and $v != '') {
        if ($v == '1') {$estado = "SI";}
        if ($v == '2') {$estado = "NO";}

        $fechahoy = date("Y-m-d");
        BecasUpdateValidacion($idenc, $estado, $fechahoy);

        if ($id_empresa == "78") {
            $to = $unico[0]->email;
            $nombreto = $unico[0]->nombre_completo;
            $tipo = "text/html";
            $subject = "Falta Documentación en la postulación a " . $array_usuario[0]->nombre_beca;
            $titulo1 = "Falta Documentación en la postulación a " . $array_usuario[0]->nombre_beca;
            $subtitulo1 = "Estimad@ " . $unico[0]->nombre_completo . ", hemos detectado un error en la carga de tus documentos al momento de realizar tu postulación.";
            $texto1 = "Nos pondremos en contacto contigo para informarte una vez que actualicemos tu postulación y puedas subir nuevamente la documentación completa.";
            $texto_url = "Ingresa aquí";
            $texto2 = "<br>En caso de dudas, contáctate con el equipo de Beneficios y Calidad de Vida a <span classbeneficiosrf@cencosud.cl";
            $texto3 = "";
            $texto4 = "";
            $url = $url_front;
            $tipomensaje = "Becas_DocsOk";
            $rut = $rut_usuario;
            $key = $rut_usuario;

        }
    }

    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/formacion_continua/entorno.html"));
    $id_empresa = $_SESSION["id_empresa"];
    $PRINCIPAL = str_replace("{ENTORNO}", lista_formacion_continua_validacion(FuncionesTransversalesAdmin(file_get_contents("views/formacion_continua/lista_formacioncontinua.html")), $id_empresa, $id_tipo), $PRINCIPAL);
    $datos_empresa = DatosEmpresa($id_empresa);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "eventos_gestion_detalle") {
    $id_empresa = $_SESSION["id_empresa"];
    $id_dimension = $request["id_dimension"];
    $codigo = $request["codigo"];


    $rut_col=$get["rutcol"];
    $del=$get["del"];

    

    if($del<>'' and $rut_col<>''){
        EventosBorrarInscrito($rut_col,$codigo, $id_empresa);
    }
    
    $id_tipo = $request["id"];
    $idenc = Decodear3($get["idenc"]);
    $d = $get["d"];
    $v = $get["v"];

    $send_emails = $get["send_emails"];
    

    if ($send_emails == 1) {
        $envioUsuarioEmail = TraeInscritosEventosEmails($codigo, $id_dimension, $id_empresa);
        foreach ($envioUsuarioEmail as $unico) {
            $id_empresa = $unico->id_empresa;
            $to = $unico->email;
            $nombreto = $unico->nombre_completo;

            $subject = "¡Has sido invitado al evento " . ($unico->Nombre) . "! ";
            $titulo1 = "¡Felicitaciones!<br><br>¡Has sido invitado al evento " . ($unico->Nombre) . "!";

            $subtitulo1 = "Descripción: " . ($unico->Descripcion);

            $texto1 = "<br>Fecha de Inicio: " . $unico->Fecha_Inicio . " " . $unico->Hora_Inicio;
            $texto2 = "<br>Fecha de Termino: " . $unico->Fecha_Termino . " " . $unico->Hora_Termino;

            $fechainicio_googlecalendar = str_replace('-', '', $unico->Fecha_Inicio);
            $fechatermino_googlecalendar = str_replace('-', '', $unico->Fecha_Termino);

            $horainicio_googlecalendar = str_replace(':', '', $unico->Hora_Inicio);
            $horatermino_googlecalendar = str_replace(':', '', $unico->Hora_Termino);

            if ($unico->LinkAcceso != '') {
                $direccion = "Link: " . $unico->LinkAcceso . ". <br><br><strong>Importante: " . $unico->Instruccion . "</strong>";
            } else {

                $direccion = "Direccion: " . $unico->Direccion . ", " . $unico->Region . "<br><br><strong>Importante: " . $unico->Instruccion . "</strong>";
            }
            $texto1 = ($texto1);
            $texto2 = ($texto2);
            $texto3 = $direccion;

            if ($unico->LinkAcceso != '') {
                $url = "https://www.google.com/calendar/render?action=TEMPLATE&text=" .
                ($unico->Nombre) .
                "&dates=" . $fechainicio_googlecalendar . "T" . $horainicio_googlecalendar . "/" .
                $fechatermino_googlecalendar . "T" . $horatermino_googlecalendar . "&location=" . $unico->LinkAcceso . "&details=" .
                ($unico->Descripcion) . ". Link: " . $unico->LinkAcceso . ". Importante: " . ($unico->Instruccion) . "    &sf=true&output=xml
                    ";
            } else {

                $url = "https://www.google.com/calendar/render?action=TEMPLATE&text=" .
                ($unico->Nombre) .
                "&dates=" . $fechainicio_googlecalendar . "T" . $horainicio_googlecalendar . "/" .
                $fechatermino_googlecalendar . "T" . $horatermino_googlecalendar . "&location=" . $unico->Direccion . ", " . $unico->Region . "&details=" .
                ($unico->Descripcion) . ". Importante: " . ($unico->Instruccion) . ". " .
                ($unico->Direccion) . " " . ($unico->Region) . "&sf=true&output=xml
                    ";
            }

            $texto_url = "Agrégalo a tu calendario ahora";


            $tipomensaje = "Email_invitacion_evento";
            $rut = $unico->rut;
            $key = $codigo;
            $template = "";


            SendGrid_Email($to, $nombreto, $from, $nombrefrom, $tipo, $subject, $titulo1, $subtitulo1, $texto1, $url, $texto_url,
                $texto2, $texto3, $texto4, $logo, $id_empresa, $url, $tipomensaje, $rut, $key, $template);
                    }
    }

    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/eventos/entorno.html"));
    $id_empresa = $_SESSION["id_empresa"];
    $PRINCIPAL = str_replace("{ENTORNO}", lista_eventos_lista_inscritos(FuncionesTransversalesAdmin(file_get_contents("views/eventos/entorno_lista_inscritos_eventos.html")), $id_empresa, $id_dimension, $codigo), $PRINCIPAL);

    $PRINCIPAL = str_replace("{CODIGO}", $codigo, $PRINCIPAL);
    $PRINCIPAL = str_replace("{ID_DIMENSION}", $id_dimension, $PRINCIPAL);

    $datos_empresa = DatosEmpresa($id_empresa);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "empresas_ext_Usuarios_Detalle") {
    $id_empresa = $_SESSION["id_empresa"];
    $id_dimension = $request["id_dimension"];
    $rut_ext = $request["rut_ext"];
    
    $id_tipo = $request["id"];
    $idenc = Decodear3($get["idenc"]);
    $d = $get["d"];
    $v = $get["v"];

    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/ext_empresas/entorno.html"));
    $id_empresa = $_SESSION["id_empresa"];
    $PRINCIPAL = str_replace("{ENTORNO}", lista_detalle_usuarios_empresas_Ext(FuncionesTransversalesAdmin(file_get_contents("views/ext_empresas/entorno_lista_inscritos_ext.html")), $id_empresa, $rut_ext), $PRINCIPAL);

    $PRINCIPAL = str_replace("{CODIGO}", $codigo, $PRINCIPAL);
    $PRINCIPAL = str_replace("{ID_DIMENSION}", $id_dimension, $PRINCIPAL);

    $datos_empresa = DatosEmpresa($id_empresa);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "CertificacionT_vista") {
    $id_empresa = $_SESSION["id_empresa"];

    
    $id_tipo = $request["id"];
    $idenc = Decodear3($get["idenc"]);
    $d = $get["d"];
    $v = $get["v"];
    $id_curso = $request["id_curso"];
    $id_inscripcion = $request["id_inscripcion"];

    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/certificacionT/entorno.html"));
    $id_empresa = $_SESSION["id_empresa"];
    $PRINCIPAL = str_replace("{ENTORNO}", CertificacionT_lista_usuarios_vista(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/certificacionT/list_certificacionT.html")), $id_empresa, $id_curso, $id_inscripcion), $PRINCIPAL);
    $datos_empresa = DatosEmpresa($id_empresa);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "talent_vista") {
    $id_empresa = $_SESSION["id_empresa"];

    
    $id_tipo = $request["id"];
    $idenc = Decodear3($get["idenc"]);
    $d = $get["d"];
    $v = $get["v"];

    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/talent/entorno.html"));
    $id_empresa = $_SESSION["id_empresa"];
    $PRINCIPAL = str_replace("{ENTORNO}", lista_talent_vista(FuncionesTransversalesAdmin(file_get_contents("views/talent/lista_talent.html")), $id_empresa, $id_tipo), $PRINCIPAL);
    $datos_empresa = DatosEmpresa($id_empresa);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "talent_vista_perfil") {
    $id_empresa = $_SESSION["id_empresa"];

    

    $id_tipo = $request["id_tipo"];
    $rut_col = $request["rut_col"];

    $idenc = Decodear3($get["idenc"]);
    $d = $get["d"];
    $v = $get["v"];

    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/talent/entorno_solo.html"));
    $id_empresa = $_SESSION["id_empresa"];
    $PRINCIPAL = str_replace("{ENTORNO}", lista_talent_profile_vista(FuncionesTransversalesAdmin(file_get_contents("views/talent/talent_profile.html")), $id_empresa, $id_tipo, $rut_col), $PRINCIPAL);
    $datos_empresa = DatosEmpresa($id_empresa);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "notificaciones_asignacion_final") {
    $id_empresa = $_SESSION["id_empresa"];

    $notificaciones_no_enviadas = NotificacionesNoEnviadas($id_empresa);
    

    foreach ($notificaciones_no_enviadas as $unico) {
        
        $array_usuario = TraeDatosUsuario($unico->rut);
        $array_notificaciones = TraeDatosNotificaciones($unico->id_notificacion, $id_empresa);
        

        Notificaciones_crea_not_automaticas($array_notificaciones[0]->tipo_mensaje,
            $array_notificaciones[0]->nombre, $array_usuario[0]->rut, $unico->fecha_envio, $id_empresa,
            $array_usuario[0]->email, $array_usuario[0]->nombre_completo, $from, ($nombrefrom),
            $array_notificaciones[0]->tipo,
            $array_notificaciones[0]->subject,
            $array_notificaciones[0]->titulo1,
            $array_notificaciones[0]->subtitulo1,
            $array_notificaciones[0]->texto1,
            $array_notificaciones[0]->url,
            $array_notificaciones[0]->texto_url,
            $array_notificaciones[0]->texto2,
            $array_notificaciones[0]->texto3,
            $array_notificaciones[0]->texto4,
            $array_notificaciones[0]->logo,
            $array_notificaciones[0]->tipomensaje,
            $array_usuario[0]->rut, $array_notificaciones[0]->template);
    }

    echo "<script>location.href='?sw=notificaciones_asignacion';    </script>";
}
else if ($seccion == "usuarios_insert_tbl_usuario_temporal_empresa") {
    $id_empresa = $_SESSION["id_empresa"];
    

    DeleteFullTbl("tbl_usuario_temporal", $id_empresa);

		extract($post);
    $error_grave = "error";
    $archivo = $_FILES['excel']['name'];VerificaExtensionFilesAdmin($_FILES["excel"]);
    $tipo = $_FILES['excel']['type'];
    $destino = "tmp_archivo_" . $archivo;
    if (copy($_FILES['excel']['tmp_name'], $destino)) {} else { $error_grave = "Error Al subir Archivo<br />";}
    if (file_exists("tmp_archivo_" . $archivo)) {
        require_once 'clases/PHPExcel.php';
        require_once 'clases/PHPExcel/Reader/Excel2007.php';
        $objReader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $objReader->load("tmp_archivo_" . $archivo);
        $objFecha = new PHPExcel_Shared_Date();
	      $objPHPExcel->setActiveSheetIndex(0);
        $HojaActiva = $objPHPExcel->getActiveSheet();
        $total_filas = $HojaActiva->getHighestRow();
        $lastColumn = $HojaActiva->getHighestColumn();
        $total_columnas = PHPExcel_Cell::columnIndexFromString($lastColumn);
        $UltimaColumna = "A";
        $j = 0;
        $_DATOS_EXCEL = array();
        for ($i = 0; $i <= $total_columnas; $i++) {$_campo[$i] = trim(($HojaActiva->getCell($UltimaColumna . "1")->getCalculatedValue()));
            $UltimaColumna++;}
        for ($fila = 2; $fila <= $total_filas; $fila++) {for ($columna = 0; $columna <= $total_columnas - 1; $columna++) {$_DATOS_EXCEL[$j][$_campo[$columna]] = trim($HojaActiva->getCellByColumnAndRow($columna, $fila)->getCalculatedValue());}
            $j++;}


    } else { $error_grave = "Necesitas importar el archivo";}
    $total_errores = 0;
    $total_actualizar = 0;
    $total_insertar = 0;
    $total_desabilitar = 0;
    $total_a_eliminar = 0;
    $lineaT = "";
    $linea = 2;


		$count_total_loop=count($_DATOS_EXCEL);
		ini_set('output_buffering', 0);
		ini_set('zlib.output_compression', 0);
		if( !ob_get_level() ){ ob_start(); }
		else { ob_end_clean(); ob_start(); }


foreach ($_DATOS_EXCEL as $unico) {


				$cuenta_loop++;
        echo "<center><span style='position: absolute;z-index:$current;background:#36C6D3; padding:10px; color:#FFF'>Actualizando Base Temporal $cuenta_loop de $count_total_loop Registros</span></center>";
        flush();
 				ob_flush();

        $jk++;

				$l++;
				


		$rut            =LimpiaRut($unico['rut']);
		$rut_completo   =LimpiaRut($unico['rut'])."-".$unico['dv'];
		$nombre         = ($unico['nombres']);
		$apaterno       = ($unico['apell_paterno']);
		$amaterno       = ($unico['apell_materno']);
		$nombre_completo= ($unico['nombres'])." ".($unico['apell_paterno'])." ".($unico['apell_materno']);

		$nombre = str_replace("'", "", 			$nombre);
		$apaterno = str_replace("'", "", 		$apaterno);
		$amaterno = str_replace("'", "", 		$amaterno);
		$nombre_completo = str_replace("'", "", $nombre_completo);

		$cargo          = ($unico['cargo']);
		$rut_duplicado  =0;
		$codigo_proceso =0;
		$id_perfil      ='';
		$id_rol         ='';
		$id_cargo       =$unico['cod_cargo'];
		$id_empresa     ='78';
		$jefe           =$unico['rut_jefe'];

		if($jefe=="35000000" or $jefe=="34000000" or $jefe=="33000000"){
					$jefe="8196251";
		}



		if($jefe=="16886519"){
					$jefe="9309690";
		}

		$dependencia    ="";
		$fecha_ingreso  =FechaExcelPhp($unico['fec_ingreso']);

			$email          =$unico['email'];
			$emailBK        =$unico['email'];
			$negocio        ="";
			$zona           =$unico['glosa_zona'];
			$gerencia       ="";
			$area           =$unico['glosa_area'];
			$departamento   =$unico['glosa_depto'];
			$local          =$unico['cod_Unidad'];
			$responsable    ="";
			$telefono       =$unico['fono_Directo'];
			$celular        ="";
			$unidad_negocio =$unico['glosa_unidad'];
			$id_area        =$unico['cod_area'];
			$evaluador          ="";
			$perfil_evaluacion  =$unico['nivel'];
			$seccion        =$unico['glosa_ext'];
			$ubicacion      =$unico['dir_1_unidad'];
			$fecha_nacimiento   ="";
			$genero             =$unico['cod_sexo'];
			$nacionalidad       ="";
			$direccion_particular   ="";
			$codigo_escolaridad     ="";
			$codigo_nivel           =$unico['tramo_edad'];
			$id_centro_costo        =$unico['cod_secc'];
			$centro_costo           =$unico['glosa_secc'];
			$comuna                 ="";
			$fecha_antiguedad       =FechaExcelPhp($unico['fecha_cargo']);
			$tipo_contrato          =$unico['Tipo_CTTO'];
			$tramo_sence            ="";
			$dv                     =$unico['dv'];
			$empresa_holding        =$unico['empresa'];
			$division               =$unico['glosa_division'];
			$porvalidar             ="";
			$idprivado              ="";
			$antiguedad             ="";
			$lider                  ="";
			$tallergrupo ="";
			$mundo ="TODOS";
			$subgerencia ="";
			$estadocivil="";
			$vigencia="0";
			$id_unidad_negocio      =$unico['cui_depen'];
			$escolaridad ="";
			$sucursal               =$unico['glosa_ofi'];
			$nombre_empresa_holding =$unico['glosa_emp'];
			$servicio               =$unico['oficina'];
			$tipo_servicio          =$unico['glosa_emp'];
			$comunidad              ="";
			$id_comunidad           ="";
			$vigencia_descripcion   ="";
			$nombre_jefatura        ="";
			$cargo_jefatura="";
			$telefono_jefatura="";
			$email_jefatura="";
			$id_perfil_competencia="";
			$perfil_competencia="";
			$anexo                  =$unico['anexo'];
			$id_gerencia            =$unico['cod_Unidad'];
			$id_subgerencia="";
			$codigo_sap="";
			$regional               =$unico['region'];
			$avatar="";
			$zonal="";
			$vicepresidencia="";
			$gerenciaR2="";
			$gerenciaR3="";
			$userid_moodle="";
			$operador=$unico['jornada'];
			$organica=$unico['Jor_HH'];
			$nivel_inicio=$unico['Jor_MM'];
			$pais="";
			$familia_cargo  =FechaExcelPhp($unico['Fec_indem']);
			$tipo_cargo=$unico['Control_jornada'];
			$codigo_negociacion=$unico['cod_negoc'];
			$fecha_reconocimiento=$unico['fec_ing_rec'];
			$fecha_ingreso_vacaciones  =FechaExcelPhp($unico['fec_ing_vac']);
			$dias_derecho=$unico['dias_dere'];
			$dias_pendientes=$unico['dias_pend'];
			$dias_pendientes_real=$unico['dias_real'];
			$cod_cargo_ant=$unico['cod_cargo_ant'];
			$gls_cargo_ant=$unico['gls_cargo_ant'];
			$ant_agno_reconoci=$unico['ant_agno_reconoci'];
			$tipo_renta=$unico['tipo_renta'];
			$edad=$unico['edad'];
			$fec_nacim=$unico['fec_nacim'];

			//sillas
			$silla_id_cargo=$unico['cod_cargo'];
			$silla_cargo=$unico['cargo'];

			$silla_id_division=$unico['cod_division'];
			$silla_division=$unico['glosa_division'];

			$silla_id_area=$unico['cod_area'];
			$silla_area=$unico['glosa_area'];

			$silla_id_departamento=$unico['cod_depto'];
			$silla_departamento=$unico['glosa_depto'];

			$silla_id_zona=$unico['cod_zona'];
			$silla_zona=$unico['glosa_zona'];

			$silla_id_seccion=$unico['cod_secc'];
			$silla_seccion=$unico['glosa_secc'];

			$silla_id_oficina=$unico['cod_oficina'];
			$silla_oficina=$unico['glosa_oficina'];

			$silla_id_unidad=$unico['cod_Unidad'];
			$silla_unidad=$unico['glosa_unidad'];
			$opc_uniforme=$unico['opc_uniforme'];

			$est_civil=$unico['est_civil'];
			$est_civil_glosa=$unico['est_civil_glosa'];
			$isapre_codigo=$unico['isapre_codigo'];
			$isapre_glosa=$unico['isapre_glosa'];
			$sucur_ciudad=$unico['sucur_ciudad'];
			$sucur_comuna=$unico['sucur_comuna'];
			$sindi_codigo=$unico['sindi_codigo'];
			$sindi_glosa=$unico['sindi_glosa'];











usuario_insert_usuario_temp(
$rut,
$rut_completo,
$nombre,
$apaterno,
$amaterno,
$nombre_completo,
$cargo,
$rut_duplicado,
$codigo_proceso,
$id_perfil,
$id_rol,
$id_cargo,
$id_empresa,
$jefe,
$dependencia,
$fecha_ingreso,
$email,
$emailBK,
$negocio,
$zona,
$gerencia,
$area,
$departamento,
$local,
$responsable,
$telefono,
$celular,
$unidad_negocio,
$id_area,
$evaluador,
$perfil_evaluacion,
$seccion,
$ubicacion,
$fecha_nacimiento,
$genero,
$nacionalidad,
$direccion_particular,
$codigo_escolaridad,
$codigo_nivel,
$id_centro_costo,
$centro_costo,
$comuna,
$fecha_antiguedad,
$tipo_contrato,
$tramo_sence,
$dv,
$empresa_holding,
$division,
$porvalidar,
$idprivado,
$antiguedad,
$lider,
$tallergrupo,
$mundo,
$subgerencia,
$estadocivil,
$vigencia,
$id_unidad_negocio,
$escolaridad,
$sucursal,
$nombre_empresa_holding,
$servicio,
$tipo_servicio,
$comunidad,
$id_comunidad,
$vigencia_descripcion,
$nombre_jefatura,
$cargo_jefatura,
$telefono_jefatura,
$email_jefatura,
$id_perfil_competencia,
$perfil_competencia,
$anexo,
$id_gerencia,
$id_subgerencia,
$codigo_sap,
$regional,
$avatar,
$zonal,
$vicepresidencia,
$gerenciaR2,
$gerenciaR3,
$userid_moodle,
$operador,
$organica,
$nivel_inicio,
$pais,
$familia_cargo,
$tipo_cargo,

$codigo_negociacion,
$fecha_reconocimiento,
$fecha_ingreso_vacaciones,

$dias_derecho,
$dias_pendientes,
$dias_pendientes_real,

$cod_cargo_ant,
$gls_cargo_ant,
$ant_agno_reconoci,
$tipo_renta,
$edad,
$fec_nacim,

$silla_id_cargo,
$silla_cargo,

$silla_id_division,
$silla_division,

$silla_id_area,
$silla_area,

$silla_id_departamento,
$silla_departamento,

$silla_id_zona,
$silla_zona,

$silla_id_seccion,
$silla_seccion,

$silla_id_oficina,
$silla_oficina,

$silla_id_unidad,
$silla_unidad,
$opc_uniforme,

$est_civil,
$est_civil_glosa,
$isapre_codigo,
$isapre_glosa,
$sucur_ciudad,
$sucur_comuna,
$sindi_codigo,
$sindi_glosa

);

    }
    echo "<script> location.href='?sw=actualizacion_usuarios&temporal_actualizar=1';</script>";
    exit;
}
else if ($seccion == "gestion_usuario_data") {
    require_once 'clases/PHPExcel.php';
    $id_empresa = $_SESSION["id_empresa"];
    $objPHPExcel = new PHPExcel();
    $styleArray = array('font' => array('bold' => false, 'color' => array('rgb' => '222222'), ));
    $objPHPExcel->getProperties()->setCreator("GO")->setLastModifiedBy("GO")->setTitle("Plantilla")->setSubject("Plantilla")->setDescription("Plantilla")->setKeywords("Excel Office 2007 openxml php")->setCategory("Plantilla");
    $lastColumn = $objPHPExcel->getActiveSheet()->getHighestColumn();
    $lastColumn2 = $objPHPExcel->getActiveSheet()->getHighestColumn();

    $arrColumns = array(
        "rut",
        "rut_completo",
        "nombre",
        "apaterno",
        "amaterno",
        "nombre_completo",
        "cargo",
        "id_cargo",
        "id_empresa",
        "jefe",
        "dependencia",
        "fecha_ingreso",
        "email",
        "zona",
        "gerencia",
        "area",
        "departamento",
        "local",
        "responsable",
        "telefono",
        "unidad_negocio",
        "id_area",
        "seccion",
        "ubicacion",
        "fecha_nacimiento",
        "genero",
        "centro_costo",
        "fecha_antiguedad",
        "tipo_contrato",
        "dv",
        "empresa_holding",
        "division",
        "lider",
        "mundo",
        "subgerencia",
        "vigencia",
        "id_unidad_negocio",
        "escolaridad",
        "sucursal",
        "nombre_empresa_holding",
        "tipo_servicio",
        "vigencia_descripcion",
        "nombre_jefatura",
        "anexo",
        "id_gerencia",
        "regional",
        "avatar",
        "zonal",
        "gerenciaR2",
        "gerenciaR3",
        "vicepresidencia");

    foreach ($arrColumns as $row) {
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn . "1", $row);
        $objPHPExcel->getActiveSheet()->getStyle($lastColumn . "1")->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle($lastColumn . "1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D1ECF1');
        if ($lastColumn == "B" || $lastColumn == "C" || $lastColumn == "D") {
            $objPHPExcel->getActiveSheet()->getStyle($lastColumn . "1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D4EDDA');
        }
        $lastColumn++;
        $i++;
    }
    $i = 1;
    $listas = listarRegistrosTablaGlobalFiltro("tbl_usuario", "id_empresa", $id_empresa, "");

    foreach ($listas as $row1) {
        $arr_usu = TraeDatosUsuario($row1->rut);

        $i++;
        $cont = 1;

        
        $lastColumn2 = "A";
        $A = 0;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->rut));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->rut_completo));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->nombre));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->apaterno));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->amaterno));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->nombre_completo));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->cargo));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->id_cargo));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->id_empresa));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->jefe));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->dependencia));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->fecha_ingreso));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->email));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->zona));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->gerencia));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->area));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->departamento));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->local));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->responsable));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->telefono));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->unidad_negocio));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->id_area));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->seccion));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->ubicacion));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->fecha_nacimiento));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->genero));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->centro_costo));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->fecha_antiguedad));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->tipo_contrato));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->dv));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->empresa_holding));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->division));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->lider));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->mundo));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->subgerencia));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->vigencia));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->id_unidad_negocio));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->escolaridad));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->sucursal));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->nombre_empresa_holding));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->tipo_servicio));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->vigencia_descripcion));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->nombre_jefatura));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->anexo));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->id_gerencia));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->regional));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->avatar));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->zonal));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->gerenciaR2));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->gerenciaR3));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->vicepresidencia));
        $lastColumn2++;
    }
    $i = 1;
    $sheet = $objPHPExcel->getActiveSheet();
    $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(true);
    /** @var PHPExcel_Cell $cell */
    foreach ($cellIterator as $cell) {
        $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
    }
    $objPHPExcel->getActiveSheet()->setTitle('Datos');
    $objPHPExcel->setActiveSheetIndex(0);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

    header('Content-Disposition: attachment;filename="GO_Usuarios.xlsx"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
}
else if ($seccion == "gestion_usuario_clave") {
    require_once 'clases/PHPExcel.php';
    $id_empresa = $_SESSION["id_empresa"];
    $objPHPExcel = new PHPExcel();
    $styleArray = array('font' => array('bold' => false, 'color' => array('rgb' => '222222'), ));
    $objPHPExcel->getProperties()->setCreator("GO")->setLastModifiedBy("GO")->setTitle("Plantilla")->setSubject("Plantilla")->setDescription("Plantilla")->setKeywords("Excel Office 2007 openxml php")->setCategory("Plantilla");
    $lastColumn = $objPHPExcel->getActiveSheet()->getHighestColumn();
    $lastColumn2 = $objPHPExcel->getActiveSheet()->getHighestColumn();
    $arrColumns = array("rut", "clave", "cambiado", "fecha_cambio", "fecha", "id_empresa");
    foreach ($arrColumns as $row) {
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn . "1", $row);
        $objPHPExcel->getActiveSheet()->getStyle($lastColumn . "1")->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle($lastColumn . "1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D1ECF1');
        if ($lastColumn == "B" || $lastColumn == "C" || $lastColumn == "D") {
            $objPHPExcel->getActiveSheet()->getStyle($lastColumn . "1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D4EDDA');
        }
        $lastColumn++;
        $i++;
    }
    $i = 1;
    $listas = listarRegistrosTablaGlobalFiltro("tbl_clave", "id_empresa", $id_empresa, "");


    foreach ($listas as $row1) {
        $arr_usu = TraeDatosUsuario($row1->rut);

        $i++;
        $cont = 1;
        $lastColumn2 = "A";
        $A = 0;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->rut));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, Decodear3(($row1->clave_encodeada)));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->cambiado));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->fecha_cambio));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->fecha));
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->id_empresa));
        $lastColumn2++;
    }
    $i = 1;
    $sheet = $objPHPExcel->getActiveSheet();
    $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(true);
    /** @var PHPExcel_Cell $cell */
    foreach ($cellIterator as $cell) {
        $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
    }
    $objPHPExcel->getActiveSheet()->setTitle('Datos');
    $objPHPExcel->setActiveSheetIndex(0);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="GO_Claves.xlsx"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
}
else if ($seccion == "puntos_descarga_asignacion") {
    require_once 'clases/PHPExcel.php';
    $id_empresa = $_SESSION["id_empresa"];
    $objPHPExcel = new PHPExcel();
    $styleArray = array('font' => array('bold' => false, 'color' => array('rgb' => '222222'), ));
    $objPHPExcel->getProperties()->setCreator("GO")->setLastModifiedBy("GO")->setTitle("Plantilla")->setSubject("Plantilla")->setDescription("Plantilla")->setKeywords("Excel Office 2007 openxml php")->setCategory("Plantilla");
    $lastColumn = $objPHPExcel->getActiveSheet()->getHighestColumn();
    $lastColumn2 = $objPHPExcel->getActiveSheet()->getHighestColumn();
    $arrColumns = array("RUT", "Nombre", "Cargo", "Segmento", "Fecha", "Puntos", "Descripcion");
    foreach ($arrColumns as $row) {
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn . "1", $row);
        $objPHPExcel->getActiveSheet()->getStyle($lastColumn . "1")->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle($lastColumn . "1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D1ECF1');
        if ($lastColumn == "B" || $lastColumn == "C" || $lastColumn == "D") {
            $objPHPExcel->getActiveSheet()->getStyle($lastColumn . "1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D1ECF1');
        }
        $lastColumn++;
        $i++;
    }
    $i = 1;
    $listas = listarRegistrosTablaGlobalFiltro("tbl_premios_puntos_usuarios", "id_empresa", $id_empresa, "");

    foreach ($listas as $row1) {
        $arr_usu = TraeDatosUsuario($row1->rut);

        $i++;
        $cont = 1;
        $lastColumn2 = "A";
        $A = 0;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->rut));
        $lastColumn2++;

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($arr_usu[0]->nombre_completo));
        $lastColumn2++;

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($arr_usu[0]->cargo));
        $lastColumn2++;

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($arr_usu[0]->mundo));
        $lastColumn2++;

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->fecha));
        $lastColumn2++;

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->puntos));
        $lastColumn2++;

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->descripcion));
        $lastColumn2++;
    }
    $i = 1;
    $sheet = $objPHPExcel->getActiveSheet();
    $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(true);
    /** @var PHPExcel_Cell $cell */
    foreach ($cellIterator as $cell) {
        $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
    }
    $objPHPExcel->getActiveSheet()->setTitle('Datos');
    $objPHPExcel->setActiveSheetIndex(0);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="GO_template_asignacion_puntos.xlsx"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
}
else if ($seccion == "Cursos_Ext_Descarga_notas") {
    require_once 'clases/PHPExcel.php';
    $id_empresa = $_SESSION["id_empresa"];
    $id_curso = $post['id_curso'];

    $objPHPExcel = new PHPExcel();
    $styleArray = array('font' => array('bold' => false, 'color' => array('rgb' => '222222'), ));
    $objPHPExcel->getProperties()->setCreator("GO")->setLastModifiedBy("GO")->setTitle("Plantilla")->setSubject("Plantilla")->setDescription("Plantilla")->setKeywords("Excel Office 2007 openxml php")->setCategory("Plantilla");
    $lastColumn = $objPHPExcel->getActiveSheet()->getHighestColumn();
    $lastColumn2 = $objPHPExcel->getActiveSheet()->getHighestColumn();
    $arrColumns = array("RUT", "Nombre", "Curso", "Estado", "Avance", "Nota", "Fecha");
    foreach ($arrColumns as $row) {
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn . "1", $row);
        $objPHPExcel->getActiveSheet()->getStyle($lastColumn . "1")->applyFromArray($styleArray);
        $objPHPExcel->getActiveSheet()->getStyle($lastColumn . "1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D1ECF1');
        if ($lastColumn == "B" || $lastColumn == "C" || $lastColumn == "D") {
            $objPHPExcel->getActiveSheet()->getStyle($lastColumn . "1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('D1ECF1');
        }
        $lastColumn++;
        $i++;
    }
    $i = 1;
    $lineasCursos = CursosExtUsuariosNotasAsistencia($id_empresa, $id_curso);

    foreach ($listas as $row1) {
        $arr_usu = TraeDatosUsuario($row1->rut);

        $i++;
        $cont = 1;
        $lastColumn2 = "A";
        $A = 0;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->rut));
        $lastColumn2++;

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->nombreusuario));
        $lastColumn2++;

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->nombrecurso));
        $lastColumn2++;

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->estado_descripcion));
        $lastColumn2++;

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->avance));
        $lastColumn2++;

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->nota));
        $lastColumn2++;

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn2 . $i, ($row1->fecha));
        $lastColumn2++;
    }
    $i = 1;
    $sheet = $objPHPExcel->getActiveSheet();
    $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(true);
    /** @var PHPExcel_Cell $cell */
    foreach ($cellIterator as $cell) {
        $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
    }
    $objPHPExcel->getActiveSheet()->setTitle('Datos');
    $objPHPExcel->setActiveSheetIndex(0);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="GO_template_asignacion_cursos_externos.xlsx"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
}
else if ($seccion == "usuarios_update_empresa") {
    $id_empresa = $_SESSION["id_empresa"];
    
    extract($post);
    $error_grave = "error";
    $archivo = $_FILES['excel']['name'];VerificaExtensionFilesAdmin($_FILES["excel"]);
    $tipo = $_FILES['excel']['type'];
    $destino = "tmp_archivo_" . $archivo;
    if (copy($_FILES['excel']['tmp_name'], $destino)) {} else { $error_grave = "Error Al subir Archivo<br />";}
    if (file_exists("tmp_archivo_" . $archivo)) {
        require_once 'clases/PHPExcel.php';
        require_once 'clases/PHPExcel/Reader/Excel2007.php';
        $objReader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $objReader->load("tmp_archivo_" . $archivo);
        $objFecha = new PHPExcel_Shared_Date();
        $objPHPExcel->setActiveSheetIndex(0);
        $HojaActiva = $objPHPExcel->getActiveSheet();
        $total_filas = $HojaActiva->getHighestRow();
        $lastColumn = $HojaActiva->getHighestColumn();
        $total_columnas = PHPExcel_Cell::columnIndexFromString($lastColumn);
        $UltimaColumna = "A";
        $j = 0;
        $_DATOS_EXCEL = array();
        for ($i = 0; $i <= $total_columnas; $i++) {$_campo[$i] = trim(($HojaActiva->getCell($UltimaColumna . "1")->getCalculatedValue()));
            $UltimaColumna++;}
        for ($fila = 2; $fila <= $total_filas; $fila++) {for ($columna = 0; $columna <= $total_columnas - 1; $columna++) {$_DATOS_EXCEL[$j][$_campo[$columna]] = trim($HojaActiva->getCellByColumnAndRow($columna, $fila)->getCalculatedValue());}
            $j++;}

        DeleteFullTbl("tbl_usuario", $id_empresa);
    } else { $error_grave = "Necesitas importar el archivo";}
    $total_errores = 0;
    $total_actualizar = 0;
    $total_insertar = 0;
    $total_desabilitar = 0;
    $total_a_eliminar = 0;
    $lineaT = "";
    $linea = 2;

    foreach ($_DATOS_EXCEL as $unico) {
        $l++;
        outputProgress($l, $fila);

        usuarios_update_empresa_data(
            LimpiaRut($unico['rut']), $unico['rut_completo'], $unico['nombre'], $unico['apaterno'], $unico['amaterno'],
            $unico['nombre_completo'], $unico['cargo'], $unico['id_cargo'], $unico['id_empresa'], $unico['jefe'],
            $unico['dependencia'], $unico['fecha_ingreso'], $unico['email'], $unico['zona'], $unico['gerencia'],
            $unico['area'], $unico['departamento'], $unico['local'], $unico['responsable'], $unico['telefono'],
            $unico['unidad_negocio'], $unico['id_area'], $unico['seccion'], $unico['ubicacion'], $unico['fecha_nacimiento'],
            $unico['genero'], $unico['centro_costo'], $unico['fecha_antiguedad'], $unico['tipo_contrato'], $unico['dv'],
            $unico['empresa_holding'], $unico['division'], $unico['lider'], $unico['mundo'], $unico['subgerencia'],
            $unico['vigencia'], $unico['id_unidad_negocio'], $unico['escolaridad'], $unico['sucursal'], $unico['nombre_empresa_holding'],
            $unico['tipo_servicio'], $unico['vigencia_descripcion'], $unico['nombre_jefatura'], $unico['anexo'], $unico['id_gerencia'],
            $unico['regional'], $unico['avatar'], $unico['zonal'], $unico['gerenciaR2'], $unico['gerenciaR3'], $unico['vicepresidencia'], $id_empresa);
    }
    echo "<script> location.href='?sw=actualizacion_usuarios';</script>";
    exit;
}
else if ($seccion == "Eventos_Inscritos_update") {
    $id_empresa = $_SESSION["id_empresa"];
    $codigo = $request["codigo"];
    $id_dimension = $request["id_dimension"];
    
    extract($post);
    $error_grave = "error";
    $archivo = $_FILES['excel']['name'];VerificaExtensionFilesAdmin($_FILES["excel"]);
    $tipo = $_FILES['excel']['type'];
    $destino = "tmp_archivo_" . $archivo;
    if (copy($_FILES['excel']['tmp_name'], $destino)) {} else { $error_grave = "Error Al subir Archivo<br />";}
    if (file_exists("tmp_archivo_" . $archivo)) {
        require_once 'clases/PHPExcel.php';
        require_once 'clases/PHPExcel/Reader/Excel2007.php';
        $objReader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $objReader->load("tmp_archivo_" . $archivo);
        $objFecha = new PHPExcel_Shared_Date();
        $objPHPExcel->setActiveSheetIndex(0);
        $HojaActiva = $objPHPExcel->getActiveSheet();
        $total_filas = $HojaActiva->getHighestRow();
        $lastColumn = $HojaActiva->getHighestColumn();
        $total_columnas = PHPExcel_Cell::columnIndexFromString($lastColumn);
        $UltimaColumna = "A";
        $j = 0;
        $_DATOS_EXCEL = array();
        for ($i = 0; $i <= $total_columnas; $i++) {$_campo[$i] = trim(($HojaActiva->getCell($UltimaColumna . "1")->getCalculatedValue()));
            $UltimaColumna++;}
        for ($fila = 2; $fila <= $total_filas; $fila++) {for ($columna = 0; $columna <= $total_columnas - 1; $columna++) {$_DATOS_EXCEL[$j][$_campo[$columna]] = trim($HojaActiva->getCellByColumnAndRow($columna, $fila)->getCalculatedValue());}
            $j++;}
    } else { $error_grave = "Necesitas importar el archivo";}
    $total_errores = 0;
    $total_actualizar = 0;
    $total_insertar = 0;
    $total_desabilitar = 0;
    $total_a_eliminar = 0;
    $lineaT = "";
    $linea = 2;

    if ($codigo != '' and $id_dimension != '') {
        $id_categoria = BuscaIdCategoriaEvento($codigo, $id_dimension, $id_empresa);
        BorraInscritosEventos($codigo, $id_dimension, $id_empresa);
        foreach ($_DATOS_EXCEL as $unico) {
            ActualizaEventosInscritos($codigo, $id_categoria, $id_dimension, LimpiaRut($unico['RUT']), $id_empresa);
        }
    }
    echo "<script> location.href='?sw=eventos_gestion_detalle&id_dimension=" . $id_dimension . "&codigo=" . $codigo . "';</script>";
    exit;
}
else if($seccion=="desbloq"){

	EliminarIntentosFallidosDelDia($_SESSION["rut_reset"], $id_empresa);

	//Dejar al usuario en vigencia 0
	ActualizaEstadoUsuarioVigencia1Cero($_SESSION["rut_reset"]);
	//Dejar clave reseteada
	$total_digitos=strlen($_SESSION["rut_reset"]);
	CambiarClaveDesdeAdmin($_SESSION["rut_reset"], $_SESSION["rut_reset"][$total_digitos-4].$_SESSION["rut_reset"][$total_digitos-3].$_SESSION["rut_reset"][$total_digitos-2].$_SESSION["rut_reset"][$total_digitos-1]);
	if($_SESSION["rut_reset"]==""){
		    echo "<script>alert('Error'); location.href='?sw=logout';</script>";

	} else {
			echo "<script>alert('Cuenta Desbloqueada ".$_SESSION["rut_reset"]."');location.href='?sw=actualizacion_usuarios';</script>";
	}

	$_SESSION["rut_reset"]="";
	exit;
}
else if($seccion=="VCl"){

}
else if ($seccion == "notificaciones_update_asignacion") {
    $id_empresa = $_SESSION["id_empresa"];
    extract($post);
    $error_grave = "error";
    $archivo = $_FILES['excel']['name'];VerificaExtensionFilesAdmin($_FILES["excel"]);
    $tipo = $_FILES['excel']['type'];
    $destino = "tmp_archivo_" . $archivo;
    if (copy($_FILES['excel']['tmp_name'], $destino)) {} else { $error_grave = "Error Al subir Archivo<br />";}
    if (file_exists("tmp_archivo_" . $archivo)) {
        require_once 'clases/PHPExcel.php';
        require_once 'clases/PHPExcel/Reader/Excel2007.php';
        $objReader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $objReader->load("tmp_archivo_" . $archivo);
        $objFecha = new PHPExcel_Shared_Date();
        $objPHPExcel->setActiveSheetIndex(0);
        $HojaActiva = $objPHPExcel->getActiveSheet();
        $total_filas = $HojaActiva->getHighestRow();
        $lastColumn = $HojaActiva->getHighestColumn();
        $total_columnas = PHPExcel_Cell::columnIndexFromString($lastColumn);
        $UltimaColumna = "A";
        $j = 0;
        $_DATOS_EXCEL = array();
        for ($i = 0; $i <= $total_columnas; $i++) {$_campo[$i] = trim(($HojaActiva->getCell($UltimaColumna . "1")->getCalculatedValue()));
            $UltimaColumna++;}
        for ($fila = 2; $fila <= $total_filas; $fila++) {for ($columna = 0; $columna <= $total_columnas - 1; $columna++) {$_DATOS_EXCEL[$j][$_campo[$columna]] = trim($HojaActiva->getCellByColumnAndRow($columna, $fila)->getCalculatedValue());}
            $j++;}

        DeleteFullTbl("tbl_notificaciones_usuarios", $id_empresa);
    } else { $error_grave = "Necesitas importar el archivo";}
    $total_errores = 0;
    $total_actualizar = 0;
    $total_insertar = 0;
    $total_desabilitar = 0;
    $total_a_eliminar = 0;
    $lineaT = "";
    $linea = 2;

    foreach ($_DATOS_EXCEL as $unico) {

        $notificacion = BuscaNombreNotificacion($unico['Id_Notificacion'], $id_empresa);

        notificaciones_insert_asignacion(LimpiaRut($unico['RUT']), ($unico['Fecha']), ($unico['Id_Notificacion']), $notificacion, $id_empresa);
    }
    echo "<script> location.href='?sw=notificaciones_asignacion';</script>";
    exit;
}
else if ($seccion == "actualizacion_usuarios") {
    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/usuarios_gestion/entorno.html"));
    $id_empresa = $_SESSION["id_empresa"];
    $temporal_actualizar = $get["temporal_actualizar"];
    $procesar = $get["procesar"];
		
		$clave_nueva=$CLXIdDpw&_9255;
		$clave_enc=Encriptar($clave_nueva);
		 		$descargar_xls = $get["descargar_xls"];

		 		if($descargar_xls==1){
		 			//MODIFICAR CAMPOS DE EMPRESA A EMPRESA
		 		    header('Content-type: text/plain');
		        header('Content-Disposition: attachment; filename=Base_Usuarios_' . $rut_enviado . '.csv');
		        echo "rut;rut_completo;id_sap;nombres;apell_paterno;apell_materno;nombre_completo;cargo;familia_cargo;rut_jefe;fec_ingreso;email;gerencia;area;ubicacion;tipo_contrato;nombre_empresa;modalidad_trabajo;personal_a_cargo\r\n";
		        $Total_Rows_full = BuscaFullUsuario_SoloEmpresaNoExternos($id_empresa);
		        foreach ($Total_Rows_full as $Unico) {

		        	$perfil=Bch_2020_Perfil_Usuario($Unico->rut);

							if($Unico->rut==""){continue;}
								echo $Unico->rut
									. ";"  . $Unico->rut_completo
									. ";"  . $Unico->codigo_sap
									. ";"  . $Unico->nombre
									. ";"  . $Unico->apaterno
									. ";"  . $Unico->amaterno
									. ";"  . $Unico->nombre_completo
									. ";"  . $Unico->cargo
									. ";"  . $Unico->familia_cargo
									. ";"  . $Unico->jefe
									. ";"  . $Unico->fecha_ingreso
									. ";"  . $Unico->email
									. ";"  . $Unico->gerencia
									. ";"  . $Unico->area
									. ";"  . $Unico->ubicacion
									. ";"  . $Unico->tipo_contrato
									. ";"  . $Unico->nombre_empresa_holding
									. ";"  . $Unico->modalidad_trabajo
									. ";"  . $Unico->personal_a_cargo
									. "\r\n";
									}
									exit();

		 		}

				$bloqueo="";
				if($post["bc"]<>""){


					$rut_COL=$post["bc"];
					$rut_COL = limpiaRut($rut_COL);
					$clave=buscaModificacionclave($rut_COL);
					//Verifico si está bloqueado
					//Intentos del día de hoy
					$intentos=TraeIntendosAccesosFallidos($rut_COL);
					$_SESSION["rut_reset"]=$rut_COL;

						$bloqueo.="<br><br><a target='' href='?sw=desbloq'>Presiona para desbloquear clave</a>";
					$bloqueo.="<br>Rut: ".$rut_COL.".
						Total de intentos fallidos el día de hoy: ".count($intentos)."
					
					";
				}

			if($post["idSap"]<>""){
					$rut_COL=$post["idSap"];
					$rut_col=BuscaRutDadoRutEmailIdSap_2021($rut_COL);
					$Usu=TraeUsuarioRut($rut_col);
					$datos_usuario="
					<br>
					<div class='alert alert-info' style='text-align:left;'>
						<div class='row'>
							<div class='col-lg-3'><strong>Rut</strong></div><div class='col-lg-9'>".($Usu[0]->rut)."</div>
							<div class='col-lg-3'><strong>Rut Completo</strong></div><div class='col-lg-9'>".($Usu[0]->rut_completo)."</div>
							<div class='col-lg-3'><strong>IDSap</strong></div><div class='col-lg-9'>".($Usu[0]->codigo_sap)."</div>

							<div class='col-lg-3'><strong>Nombre</strong></div><div class='col-lg-9'>".($Usu[0]->nombre_completo)."</div>
							<div class='col-lg-3'><strong>Cargo</strong></div><div class='col-lg-9'>".($Usu[0]->cargo)."</div>
							<div class='col-lg-3'><strong>Email</strong></div><div class='col-lg-9'>".($Usu[0]->email)."</div>

							<div class='col-lg-3'><strong>Gerencia</strong></div><div class='col-lg-9'>".($Usu[0]->gerencia)."</div>
							<div class='col-lg-3'><strong>Area</strong></div><div class='col-lg-9'>".($Usu[0]->area)."</div>

							<div class='col-lg-3'><strong>Familia</strong></div><div class='col-lg-9'>".($Usu[0]->familia_cargo)."</div>

							<div class='col-lg-3'><strong>Jefe</strong></div><div class='col-lg-9'>".($Usu[0]->jefe)."</div>
							<div class='col-lg-3'><strong>Fecha Ingreso</strong></div><div class='col-lg-9'>".($Usu[0]->fecha_ingreso)."</div>
							<div class='col-lg-3'><strong>Ubicacion</strong></div><div class='col-lg-9'>".($Usu[0]->ubicacion)."</div>

							<div class='col-lg-3'><strong>Tipo Contrato</strong></div><div class='col-lg-9'>".($Usu[0]->tipo_contrato)."</div>
							<div class='col-lg-3'><strong>Empresa</strong></div><div class='col-lg-9'>".($Usu[0]->nombre_empresa_holding)."</div>
							<div class='col-lg-3'><strong>Modalidad Trabajo</strong></div><div class='col-lg-9'>".($Usu[0]->modalidad_trabajo)."</div>

							<div class='col-lg-3'><strong>Personal a Cargo</strong></div><div class='col-lg-9'>".($Usu[0]->personal_a_cargo)."</div>


						</div>
					</div>
						";

			}

		   foreach ($array_admin_clave as $unico){
			$clave=randomPassword();

			$clave_nueva=$clave[0].$clave[1].$clave[2];
			$clave_enc=Encriptar($clave_nueva);
			    }

        foreach ($array_sinclave as $unico){
           
           $newstring = substr($unico->rut, -4);
           
           $clave_encodeada=Encriptar_2($newstring, $unico->rut);

    }

	$temporal_actualizar=1;

    $PRINCIPAL = str_replace("{ENTORNO}", lista_gestion_usuarios(FuncionesTransversalesAdmin(file_get_contents("views/usuarios_gestion/entorno_usuarios.html")), $id_empresa, $temporal_actualizar, $procesar), $PRINCIPAL);

    $PRINCIPAL = str_replace("{BLOQUEO}",					$bloqueo,$PRINCIPAL);
    $PRINCIPAL = str_replace("{DATOS_USUARIOS}",	$datos_usuario,$PRINCIPAL);



    $datos_empresa = DatosEmpresa($id_empresa);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "vista_incognita") {

		global $url_vista_incognita;

		$rut_enc=Encodear3($post["bc"]);
		
		$id_empresa = $_SESSION["id_empresa"];
		$Datos_usu=TraeDatosUsuario($post["bc"]);
		if($post["bc"]<>"" and $Datos_usu[0]->rut){
			echo "<script>alert('Ingresando a Vista Incognita de ".($Datos_usu[0]->nombre_completo)."');</script>";
			echo "<script>location.href='".$url_vista_incognita."".$rut_enc."';</script>";
			exit();
		}

    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/usuarios_gestion/entorno.html"));
    $PRINCIPAL = str_replace("{ENTORNO}", FuncionesTransversalesAdmin(file_get_contents("views/usuarios_gestion/entorno_vista_incognita.html")), $PRINCIPAL);
    $PRINCIPAL = str_replace("{BLOQUEO}",					$bloqueo,$PRINCIPAL);
    $PRINCIPAL = str_replace("{DATOS_USUARIOS}",	$datos_usuario,$PRINCIPAL);
    $datos_empresa = DatosEmpresa($id_empresa);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "actualizacion_permiso_unico") {
    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/usuarios_gestion/entorno.html"));
    $id_empresa = $_SESSION["id_empresa"];

		$descargar_xls = $get["descargar_xls"];
 		if($descargar_xls==1){
 		    header('Content-type: text/plain');
        header('Content-Disposition: attachment; filename=BBDD_Permiso_Unico_Colectivo_' . $rut_enviado . '.csv');
        echo "rut;fec_ingreso_indem;Fecha_semana;\r\n";


        $Total_Rows_full = Lista_usuario_permiso_unico_data($id_empresa);
	        foreach ($Total_Rows_full as $Unico) {
									echo
									$Unico->rut
									. ";"  . $Unico->fec_ingreso_indem
									. ";"  . $Unico->Fecha_semana
									. "\r\n";
					}
					exit();
 		}


    $PRINCIPAL = str_replace("{ENTORNO}", lista_gestion_usuarios_cd(FuncionesTransversalesAdmin(file_get_contents("views/usuarios_gestion/entorno_usuarios_permiso_unico.html")), $id_empresa, $temporal_actualizar, $procesar), $PRINCIPAL);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "actualizacion_contingencia_ubicacion_colaboradores") {
    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/usuarios_gestion/entorno.html"));
    $id_empresa = $_SESSION["id_empresa"];

		$descargar_xls = $get["descargar_xls"];
 		if($descargar_xls==1){
 		    header('Content-type: text/plain');
        header('Content-Disposition: attachment; filename=BBDD_Ubicacion_Colaboradores_' . $rut_enviado . '.csv');
        echo "rut;direccion;piso;referencia;temporalidad;fecha;hora;rut_actualizador;id_empresa;comuna;ciudad;\r\n";


        $Total_Rows_full = Lista_usuario_ubicacion_colaboradores_data($id_empresa);
	        foreach ($Total_Rows_full as $Unico) {
									echo
									$Unico->rut
									. ";"  . $Unico->direccion
									. ";"  . $Unico->piso
									. ";"  . $Unico->referencia
									. ";"  . $Unico->temporalidad
									. ";"  . $Unico->fecha
									. ";"  . $Unico->hora
									. ";"  . $Unico->rut_actualizador
									. ";"  . $Unico->id_empresa
									. ";"  . $Unico->comuna
									. ";"  . $Unico->ciudad
									. "\r\n";
					}
					exit();
 		}


    $PRINCIPAL = str_replace("{ENTORNO}", lista_gestion_usuarios_ubicaciones(FuncionesTransversalesAdmin(file_get_contents("views/usuarios_gestion/entorno_usuarios_ubicacion_colaboradores.html")), $id_empresa, $temporal_actualizar, $procesar), $PRINCIPAL);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "actualizacion_control_dotacion_2021_dia") {
    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/usuarios_gestion/entorno.html"));
    $id_empresa = $_SESSION["id_empresa"];

		$descargar_xls = $get["descargar_xls"];
 		if($descargar_xls==1){
 		    header('Content-type: text/plain');
        header('Content-Disposition: attachment; filename=BBDD_ControlDotacion_2021_dia_' . $rut_enviado . '.csv');
        echo "rut;regimen;motivo;fecha;rut_actualizador;fecha_actualizacion;hora_actualizacion;nombre;cargo;division;rut_jefe;nombre_jefe;edit;\r\n";



        $Total_Rows_full = Lista_usuario_tbl_contingencia_2021_dia_data($id_empresa);
	        foreach ($Total_Rows_full as $Unico) {
									echo 		 $Unico->rut
									. ";"  . $Unico->regimen
									. ";"  . $Unico->motivo
									. ";"  . $Unico->fecha
									. ";"  . $Unico->rut_actualizador
									. ";"  . $Unico->fecha_actualizacion
									. ";"  . $Unico->hora_actualizacion
									. ";"  . $Unico->nombre
									. ";"  . $Unico->cargo
									. ";"  . $Unico->division
									. ";"  . $Unico->rut_jefe
									. ";"  . $Unico->nombre_jefe
									. ";"  . $Unico->edit
									. "\r\n";
					}
					exit();
 		}


    $PRINCIPAL = str_replace("{ENTORNO}", lista_gestion_dotacion_dia_21(FuncionesTransversalesAdmin(file_get_contents("views/usuarios_gestion/entorno_usuarios_dia_2021.html")), $id_empresa, $temporal_actualizar, $procesar), $PRINCIPAL);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "actualizacion_control_dotacion_opciones") {
    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/usuarios_gestion/entorno.html"));
    $id_empresa = $_SESSION["id_empresa"];

		$descargar_xls = $get["descargar_xls"];
 		if($descargar_xls==1){
 		    header('Content-type: text/plain');
        header('Content-Disposition: attachment; filename=BBDD_Control_Dotacion_Opciones_' . $rut_enviado . '.csv');
        echo "regimen_actual;motivo_actual;regimen_nuevo;motivo_nuevo;fecha_inicio;fecha_fin;muestra_modificar_regimen;\r\n";

        $Total_Rows_full = Lista_usuario_tbl_contingencia_2021_option_data($id_empresa);
	        foreach ($Total_Rows_full as $Unico) {
									echo
									$Unico->regimen_actual
									. ";"  . $Unico->motivo_actual
									. ";"  . $Unico->regimen_nuevo
									. ";"  . $Unico->motivo_nuevo
									. ";"  . $Unico->fecha_inicio
									. ";"  . $Unico->fecha_fin
									. ";"  . $Unico->muestra_modificar_regimen
									. "\r\n";
					}
					exit();
 		}


    $PRINCIPAL = str_replace("{ENTORNO}", lista_gestion_usuarios_dotacion_opciones(FuncionesTransversalesAdmin(file_get_contents("views/usuarios_gestion/entorno_usuarios_dotacion_opciones.html")), $id_empresa, $temporal_actualizar, $procesar), $PRINCIPAL);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "usuarios_insert_tbl_usuario_ausentismo_empresa") {
    $id_empresa = $_SESSION["id_empresa"];
    
    VerificaExtensionFilesAdmin($_FILES["file"]);
    if ( isset($post["action"]) ) {
			   if ( isset($_FILES["file"])) {
			        if ($_FILES["file"]["error"] > 0) {	echo "Return Code: " . $_FILES["file"]["error"] . "<br />";	}
			        else {

			             if (file_exists("upload/" . $_FILES["file"]["name"])) {
					           
			             }
			             else {
			             
			            $storagename = "uploaded_file.txt";
			            move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $storagename);
			            
			            }
			        }
			     } else {
			             echo "No file selected <br />";
			     }

			     if ( isset($storagename) && $file = fopen( "upload/" . $storagename , r ) ) {

			     	 DeleteFullTbltbl_usuario_ausentismo();

						    
						    $firstline = fgets ($file, 10096 );
						        //Gets the number of fields, in CSV-files the names of the fields are mostly given in the first line
						    $num = strlen($firstline) - strlen(str_replace(";", "", $firstline));
						        //save the different fields of the firstline in an array called fields
						    $fields = array();
						    $fields = explode( ";", $firstline, ($num+1) );
						    $line = array();
						    $i = 0;

						    while ( $line[$i] = fgets ($file, 10096) ) {
						        $dsatz[$i] = array();
						        $dsatz[$i] = explode( ";", $line[$i], ($num+1) );
						        $i++;
						    }
						        
						         $cuentaK=0;
						    for ( $k = 0; $k != ($num+1); $k++ ) {
						       
						       $cuentaK++;
						    }
						    foreach ($dsatz as $key => $number) {
						       $row_csv="";
						       $cuenta=0;
						        foreach ($number as $k => $content) {
						             		$cuenta++;
						             		$content = str_replace(' ', '', $content);
                                            $content = str_replace('+', '', $content);
                                            $content = str_replace('=', '', $content);
                                            $content = str_replace(';', '.', $content);
                                            $content = addcslashes($content, "'=\\");
						            		$row_csv.="'".trim(strip_tags($content))."'";
						            		if($cuenta<7){$row_csv.=",";}
						        }
						        Insert_Lista_ausentismo_data($row_csv);
						    }
						}
			}

    unlink("upload/" . $storagename);

    ControlDotacionInasistencia_data();

    echo "<script> alert('base actualizada correctamente'); location.href='?sw=actualizacion_usuarios_licencias';</script>";


    exit;
}
else if ($seccion == "actualizacion_usuarios_hhee") {
    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/usuarios_gestion/entorno.html"));
    $id_empresa = $_SESSION["id_empresa"];

		$descargar_xls = $get["descargar_xls"];
 		if($descargar_xls==1){
 		    header('Content-type: text/plain');
        header('Content-Disposition: attachment; filename=Lista_Usuario_HH_EE' . $rut_enviado . '.csv');
        echo "num_rut;fecha;minutos_extras;minutos_retro;minutos_informada\r\n";







        $Total_Rows_full = Lista_hhee_data($id_empresa);
	        foreach ($Total_Rows_full as $Unico) {
							echo $Unico->rut
								. ";"  . $Unico->fecha
								. ";"  . $Unico->minutos_extras
								. ";"  . $Unico->minutos_retro
								. ";"  . $Unico->minutos_informada


								. "\r\n";
					}
				exit();
 		}

    $PRINCIPAL = str_replace("{ENTORNO}", lista_gestion_usuarios_licencias(FuncionesTransversalesAdmin(file_get_contents("views/usuarios_gestion/entorno_usuarios_hhee.html")), $id_empresa, $temporal_actualizar, $procesar), $PRINCIPAL);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "usuarios_insert_tbl_usuario_hhee_empresa") {
    $id_empresa = $_SESSION["id_empresa"];
    
    VerificaExtensionFilesAdmin($_FILES["file"]);
    if ( isset($post["action"]) ) {
			   if ( isset($_FILES["file"])) {
			        if ($_FILES["file"]["error"] > 0) {	echo "Return Code: " . $_FILES["file"]["error"] . "<br />";	}
			        else {

			             if (file_exists("upload/" . $_FILES["file"]["name"])) {
					           
			             }
			             else {
			                    //Store file in directory "upload" with the name of "uploaded_file.txt"
			            $storagename = "uploaded_file.txt";
			            move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $storagename);
			            
			            }
			        }
			     } else {
			             echo "No file selected <br />";
			     }

			     if ( isset($storagename) && $file = fopen( "upload/" . $storagename , r ) ) {

			     	 DeleteFullTbltbl_escritorio_horas_extras();

						    
						    $firstline = fgets ($file, 10096 );
						        //Gets the number of fields, in CSV-files the names of the fields are mostly given in the first line
						    $num = strlen($firstline) - strlen(str_replace(";", "", $firstline));
						        //save the different fields of the firstline in an array called fields
						    $fields = array();
						    $fields = explode( ";", $firstline, ($num+1) );
						    $line = array();
						    $i = 0;

						    while ( $line[$i] = fgets ($file, 10096) ) {
						        $dsatz[$i] = array();
						        $dsatz[$i] = explode( ";", $line[$i], ($num+1) );
						        $i++;
						    }
						        
						         $cuentaK=0;
						    for ( $k = 0; $k != ($num+1); $k++ ) {
						       
						       $cuentaK++;
						    }
		ini_set('output_buffering', 0);
		ini_set('zlib.output_compression', 0);

		if( !ob_get_level() ){ ob_start(); }
		else { ob_end_clean(); ob_start(); }
		$count_total_loop=count($dsatz);

						    foreach ($dsatz as $key => $number) {
						       $row_csv="";
						       $cuenta=0;
						        foreach ($number as $k => $content) {
						             		$cuenta++;
						             		$content = str_replace(' ', '', $content);
                                                                 $content = str_replace('+', '', $content);
                                                                    $content = str_replace('=', '', $content);
                                                                    $content = str_replace(';', '.', $content);
                                                                    $content = addcslashes($content, "'=\\");
						            		$row_csv.="'".trim(strip_tags($content))."'";
						            		if($cuenta<5){$row_csv.=",";}
						        }

				$cuenta_loop++;

        echo "<center><span style='position: absolute;z-index:$current;background:#36C6D3; padding:10px; color:#FFF'>Actualizando $cuenta_loop de $count_total_loop Registros</span></center>";
        flush();
 				ob_flush();

						        Insert_Lista_hhee_data($row_csv);
						    }
					  
						}
			}

    echo "<script> alert('base actualizada correctamente'); location.href='?sw=actualizacion_usuarios_hhee';</script>";
    unlink("upload/" . $storagename);
    exit;
}
else if ($seccion == "ReporteFullUsuario") {
    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/usuarios_gestion/entorno.html"));
    $id_empresa = $_SESSION["id_empresa"];
    $temporal_actualizar = $get["temporal_actualizar"];
    $rut_enviado = LimpiaRut($post["rut"]);


    if ($rut_enviado<>"") {
        header('Content-type: text/plain');
        header('Content-Disposition: attachment; filename=Reporte_Full_Usuario_' . $rut_enviado . '.csv');
        echo "RUT;NOMBRE;CARGO;DIVISION;ID_IMPARTICION;IMPARTICION;ID_CURSO;CURSO;PROGRAMA;FECHA_INICIO;FECHA_TERMINO;NUM_HORAS;ESTADO;\r\n";
        $Total_Rows_full = BuscaFullUsuario($rut_enviado, $id_empresa);
        foreach ($Total_Rows_full as $Unico) {
			echo $Unico->rut
			. ";" 	. $Unico->nombre
			. ";" 	. $Unico->cargo
			. ";" 	. $Unico->c1
			.";"	. $Unico->id_inscripcion
			. ";".$Unico->nombre_inscripcion
			. ";".$Unico->id_curso
			. ";".$Unico->curso
			. ";".$Unico->programa
			. ";".$Unico->fecha_inicio
			. ";".$Unico->fecha_termino
			. ";".$Unico->horas
			. ";".$Unico->estado
			. "\r\n";

		}
				exit();
	}

    $PRINCIPAL = str_replace("{ENTORNO}", lista_gestion_usuarios(FuncionesTransversalesAdmin(file_get_contents("views/usuarios_gestion/entorno_reporte_full_usuario.html")), $id_empresa, $temporal_actualizar, $procesar), $PRINCIPAL);
    $datos_empresa = DatosEmpresa($id_empresa);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "notificaciones_asignacion") {
    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/notificaciones/entorno.html"));
    $id_empresa = $_SESSION["id_empresa"];
    $PRINCIPAL = str_replace("{ENTORNO}", lista_asignacion_notificaciones(FuncionesTransversalesAdmin(file_get_contents("views/notificaciones/entorno_notificaciones_asignacion.html")), $id_empresa, $id_categoria), $PRINCIPAL);
    $datos_empresa = DatosEmpresa($id_empresa);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "PP_save_relatores") {
    $id_empresa = $_SESSION["id_empresa"];
    $rut_experto = LimpiaRut($post['rut_experto']);
    $nombrecat = MP_BuscaCatDadoId($post['grupo_interes_mp_nuevo'], $id_empresa);
    
    MP_SavenuevoExpertogrupointeres(($post['grupo_interes_mp_nuevo']), $nombrecat, $rut_experto, $id_empresa);
    echo "
        <script>
        location.href='?sw=mejores_practicas_edit_expertos';
        </script>";
}
else if ($seccion == "usuarios_relatores") {
    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/relatores/entorno.html"));
    $id_empresa = $_SESSION["id_empresa"];
    $excel = $get["excel"];
    if ($excel == 1) {
        header('Content-type: text/plain');
        header('Content-Disposition: attachment; filename=Reporte_por_Ejecutivo_Resumen_' . $txt . '.csv');
        echo "RUT;NOMBRE_RELATOR;EMPRESA;TIPO;\r\n";
        $Total_Rows_full = TraeRelatoresPorEmpresa2($id_empresa);
        foreach ($Total_Rows_full as $Unico) {
            echo $Unico->rut_completo . ";" . $Unico->nombre . ";" . $Unico->empresa . ";" . $Unico->cargo . "\r\n";
        }
        exit();
    } else {
        $PRINCIPAL = str_replace("{ENTORNO_RELATORES}", lista_relatores(FuncionesTransversalesAdmin(file_get_contents("views/relatores/entorno_expertos.html")), $id_empresa, $id_categoria), $PRINCIPAL);
    }
    $datos_empresa = DatosEmpresa($id_empresa);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "lista_Mediciones_med") {
    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/mediciones/entorno.html"));
    $id_empresa = $_SESSION["id_empresa"];

    $PRINCIPAL = str_replace("{ENTORNO_MEDICIONES}", lista_mediciones_row(FuncionesTransversalesAdmin(file_get_contents("views/mediciones/entorno_mediciones_row.html")), $id_empresa, $id_categoria), $PRINCIPAL);

    $datos_empresa = DatosEmpresa($id_empresa);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "lista_evaluaciones") {
    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/evaluaciones/entorno.html"));
    $id_empresa = $_SESSION["id_empresa"];
    $PRINCIPAL = str_replace("{ENTORNO_EVALUACIONES}", lista_evaluaciones(FuncionesTransversalesAdmin(file_get_contents("views/evaluaciones/entorno_evaluaciones.html")), $id_empresa, $id_categoria), $PRINCIPAL);
    $datos_empresa = DatosEmpresa($id_empresa);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "lista_banco_preguntas_lb") {
    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/encuestas_creacion_lb/entorno.html"));
    $id_empresa = $_SESSION["id_empresa"];
    $PRINCIPAL = str_replace("{ENTORNO_BANCO_PREGUNTA}", lista_banco_preguntas_detalle(FuncionesTransversalesAdmin(file_get_contents("views/encuestas_creacion_lb/entorno_encuestas.html")), $id_empresa, $id_categoria), $PRINCIPAL);
    $datos_empresa = DatosEmpresa($id_empresa);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "lista_evaluaciones_med") {


 	$query_enc=Encodear3("SELECT
*
FROM
tbl_usuario
WHERE
cargo <> ''
AND id_empresa = '78'
AND (
id_cargo='6015'
or id_cargo='6037'
or id_cargo='6121'
or id_cargo='6019'
or id_cargo='6048'
or id_cargo='6111'
or id_cargo='6090'
or id_cargo='6160'
or id_cargo='6045'
or id_cargo='6211'
or id_cargo='6074'
or id_cargo='6207'
or id_cargo='6043'
or id_cargo='6041'
or id_cargo='6107'
or id_cargo='6238'
or id_cargo='6280'
or id_cargo='6189'
or id_cargo='6275'
or id_cargo='6082'
or id_cargo='6193'
or id_cargo='6114'
or id_cargo='6281'
or id_cargo='7015'
or id_cargo='6063'
or id_cargo='6480'
or id_cargo='6471'
or id_cargo='6511'
or id_cargo='6046'
or id_cargo='6072'
or id_cargo='9997'
or id_cargo='6564'
or id_cargo='6601'
or id_cargo='6600'
or id_cargo='6603'
or id_cargo='6606'
or id_cargo='6592'
or id_cargo='6036'
or id_cargo='6008'
or id_cargo='6167'
or id_cargo='6285'
or id_cargo='6635'
or id_cargo='6647'
or id_cargo='6578'
or id_cargo='6066'
or id_cargo='6469'
or id_cargo='6706'
or id_cargo='6634'
or id_cargo='6685'
or id_cargo='6527'
or id_cargo='6579'
or id_cargo='6492'
or id_cargo='6691'
or id_cargo='6638'
or id_cargo='6622'
or id_cargo='6047'
or id_cargo='6454'
or id_cargo='6715'
or id_cargo='6187'
or id_cargo='6738'
or id_cargo='6208'
or id_cargo='6530'
or id_cargo='6798'
or id_cargo='6780'
or id_cargo='6800'
or id_cargo='6785'
or id_cargo='6781'
or id_cargo='6129'
or id_cargo='6808'
or id_cargo='6799'
or id_cargo='6064'
or id_cargo='6692'
or id_cargo='6555'
or id_cargo='6821'
or id_cargo='6608'
or id_cargo='6628'
or id_cargo='6832'
or id_cargo='6696'
or id_cargo='6796'
or id_cargo='6827'
or id_cargo='6826'
or id_cargo='6560'
or id_cargo='6561'
or id_cargo='7287'
or id_cargo='6853'
or id_cargo='6855'
or id_cargo='6857'
or id_cargo='6575'
or id_cargo='6820'
or id_cargo='6871'
or id_cargo='6681'
or id_cargo='6727'
or id_cargo='6879'
or id_cargo='6884'
or id_cargo='7016'
or id_cargo='6078'
or id_cargo='6893'
or id_cargo='6009'
or id_cargo='6563'
or id_cargo='6894'
or id_cargo='6846'
or id_cargo='6925'
or id_cargo='6942'
or id_cargo='6956'
or id_cargo='6926'
or id_cargo='6209'
or id_cargo='6929'
or id_cargo='6935'
or id_cargo='6617'
or id_cargo='7017'
or id_cargo='7020'
or id_cargo='7014'
or id_cargo='7022'
or id_cargo='7034'
or id_cargo='7053'
or id_cargo='7051'
or id_cargo='6920'
or id_cargo='6242'
or id_cargo='7058'
or id_cargo='6905'
or id_cargo='7071'
or id_cargo='6968'
or id_cargo='6131'
or id_cargo='7003'
or id_cargo='7799'
or id_cargo='7104'
or id_cargo='6055'
or id_cargo='7106'
or id_cargo='7133'
or id_cargo='7172'
or id_cargo='7170'
or id_cargo='7177'
or id_cargo='7175'
or id_cargo='7184'
or id_cargo='7171'
or id_cargo='7173'
or id_cargo='7190'
or id_cargo='7181'
or id_cargo='7165'
or id_cargo='7186'
or id_cargo='7174'
or id_cargo='7169'
or id_cargo='7168'
or id_cargo='7191'
or id_cargo='6928'
or id_cargo='6670'
or id_cargo='6283'
or id_cargo='7047'
or id_cargo='6321'
or id_cargo='7216'
or id_cargo='6912'
or id_cargo='7223'
or id_cargo='7247'
or id_cargo='7256'
or id_cargo='7244'
or id_cargo='7251'
or id_cargo='7263'
or id_cargo='6924'
or id_cargo='7102'
or id_cargo='7266'
or id_cargo='7259'
or id_cargo='6803'
or id_cargo='7281'
or id_cargo='6133'
or id_cargo='6688'
or id_cargo='7305'
or id_cargo='7320'
or id_cargo='7316'
or id_cargo='6910'
or id_cargo='7303'
or id_cargo='6507'
or id_cargo='6506'
or id_cargo='7330'
or id_cargo='6482'
or id_cargo='7105'
or id_cargo='7337'
or id_cargo='6906'
or id_cargo='7032'
or id_cargo='6044'
or id_cargo='6703'
or id_cargo='6986'
or id_cargo='6892'
or id_cargo='7349'
or id_cargo='7267'
or id_cargo='7185'
or id_cargo='7043'
or id_cargo='6859'
or id_cargo='7350'
or id_cargo='6379'
or id_cargo='6665'
or id_cargo='7368'
or id_cargo='6604'
or id_cargo='7178'
or id_cargo='7018'
or id_cargo='7390'
or id_cargo='7378'
or id_cargo='7101'
or id_cargo='7054'
or id_cargo='7393'
or id_cargo='7383'
or id_cargo='7394'
or id_cargo='7250'
or id_cargo='7089'
or id_cargo='6723'
or id_cargo='7408'
or id_cargo='7410'
or id_cargo='6875'
or id_cargo='6042'
or id_cargo='7426'
or id_cargo='7245'
or id_cargo='6491'
or id_cargo='7340'
or id_cargo='7445'
or id_cargo='7092'
or id_cargo='7468'
or id_cargo='7088'
or id_cargo='6155'
or id_cargo='7147'
or id_cargo='7425'
or id_cargo='7508'
or id_cargo='7486'
or id_cargo='7502'
or id_cargo='7319'
or id_cargo='7523'
or id_cargo='7514'
or id_cargo='7495'
or id_cargo='7455'
or id_cargo='7458'
or id_cargo='7498'
or id_cargo='7454'
or id_cargo='7534'
or id_cargo='7441'
or id_cargo='6128'
or id_cargo='7459'
or id_cargo='6695'
or id_cargo='6933'
or id_cargo='6842'
or id_cargo='6939'
or id_cargo='6599'
or id_cargo='7545'
or id_cargo='6934'
or id_cargo='6771'
or id_cargo='7547'
or id_cargo='6843'
or id_cargo='6938'
or id_cargo='7301'
or id_cargo='6317'
or id_cargo='6214'
or id_cargo='7549'
or id_cargo='7543'
or id_cargo='7049'
or id_cargo='6954'
or id_cargo='7559'
or id_cargo='7553'
or id_cargo='7558'
or id_cargo='7561'
or id_cargo='7564'
or id_cargo='7179'
or id_cargo='7568'
or id_cargo='7566'
or id_cargo='7246'
or id_cargo='6944'
or id_cargo='8087'
or id_cargo='8119'
or id_cargo='8083'
or id_cargo='8165'
or id_cargo='8175'
or id_cargo='8071'
or id_cargo='8046'
or id_cargo='8070'
or id_cargo='8052'
or id_cargo='8177'
or id_cargo='8048'
or id_cargo='8051'
or id_cargo='8107'
or id_cargo='8084'
or id_cargo='8035'
or id_cargo='8117'
or id_cargo='7586'
or id_cargo='8178'
or id_cargo='8054'
or id_cargo='8065'
or id_cargo='8144'
or id_cargo='8005'
or id_cargo='8099'
or id_cargo='8179'
or id_cargo='8155'
or id_cargo='8037'
or id_cargo='8072'
or id_cargo='8055'
or id_cargo='8168'
or id_cargo='8012'
or id_cargo='8022'
or id_cargo='7593'
or id_cargo='8103'
or id_cargo='8167'
or id_cargo='8180'
or id_cargo='8001'
or id_cargo='8150'
or id_cargo='8044'
or id_cargo='8057'
or id_cargo='8164'
or id_cargo='8151'
or id_cargo='7397'
or id_cargo='8080'
or id_cargo='7550'
or id_cargo='8152'
or id_cargo='8116'
or id_cargo='8032'
or id_cargo='8161'
or id_cargo='8166'
or id_cargo='8011'
or id_cargo='8042'
or id_cargo='8066'
or id_cargo='8006'
or id_cargo='8146'
or id_cargo='8003'
or id_cargo='8059'
or id_cargo='8097'
or id_cargo='8101'
or id_cargo='8092'
or id_cargo='8153'
or id_cargo='8113'
or id_cargo='8181'
or id_cargo='8036'
or id_cargo='8154'
or id_cargo='8174'
or id_cargo='8014'
or id_cargo='7597'
or id_cargo='8013'
or id_cargo='8096'
or id_cargo='8027'
or id_cargo='8078'
or id_cargo='8176'
or id_cargo='8033'
or id_cargo='7538'
or id_cargo='8185'
or id_cargo='8124'
or id_cargo='8028'
or id_cargo='8079'
or id_cargo='8112'
or id_cargo='8141'
or id_cargo='8094'
or id_cargo='8043'
or id_cargo='8004'
or id_cargo='8016'
or id_cargo='8050'
or id_cargo='8189'
or id_cargo='7578'
or id_cargo='8009'
or id_cargo='8183'
or id_cargo='8171'
or id_cargo='8030'
or id_cargo='7565'
or id_cargo='7572'
or id_cargo='7341'
or id_cargo='7615'
or id_cargo='8192'
or id_cargo='7630'
or id_cargo='8196'
or id_cargo='6097'
or id_cargo='7588'
or id_cargo='8142'
or id_cargo='7103'
or id_cargo='7625'
or id_cargo='7651'
or id_cargo='7629'
or id_cargo='7626'
or id_cargo='8200'
or id_cargo='8208'
or id_cargo='8215'
or id_cargo='8062'
or id_cargo='7649'
or id_cargo='7617'
or id_cargo='6811'
or id_cargo='8063'
or id_cargo='7648'
or id_cargo='6707'
or id_cargo='8201'
or id_cargo='8202'
or id_cargo='8213'
or id_cargo='7678'
or id_cargo='8128'
or id_cargo='8134'
or id_cargo='8138'
or id_cargo='8082'
or id_cargo='8211'
or id_cargo='7632'
or id_cargo='7633'
or id_cargo='7046'
or id_cargo='7674'
or id_cargo='8224'
or id_cargo='8216'
or id_cargo='7670'
or id_cargo='7675'
or id_cargo='8218'
or id_cargo='8219'
or id_cargo='7669'
or id_cargo='7693'
or id_cargo='8205'
or id_cargo='7643'
or id_cargo='7694'
or id_cargo='7710'
or id_cargo='8073'
or id_cargo='8125'
or id_cargo='6672'
or id_cargo='7673'
or id_cargo='7701'
or id_cargo='7707'
or id_cargo='7709'
or id_cargo='8228'
or id_cargo='8222'
or id_cargo='7639'
or id_cargo='7060'
or id_cargo='8139'
or id_cargo='7713'
or id_cargo='7716'
or id_cargo='7642'
or id_cargo='8233'
or id_cargo='7711'
or id_cargo='7640'
or id_cargo='7718'
or id_cargo='8123'
or id_cargo='6147'
or id_cargo='8135'
or id_cargo='8108'
or id_cargo='7722'
or id_cargo='8121'
or id_cargo='7729'
or id_cargo='7730'
or id_cargo='7725'
or id_cargo='7712'
or id_cargo='7731'
or id_cargo='7601'
or id_cargo='6526'
or id_cargo='7723'
or id_cargo='7628'
or id_cargo='8231'
or id_cargo='8237'
or id_cargo='8241'
or id_cargo='7548'
or id_cargo='7734'
or id_cargo='8239'
or id_cargo='8026'
or id_cargo='8243'
or id_cargo='7124'
or id_cargo='8282'
or id_cargo='8238'
or id_cargo='7736'
or id_cargo='8188'
or id_cargo='8023'
or id_cargo='7740'
or id_cargo='7744'
or id_cargo='7219'
or id_cargo='7520'
or id_cargo='8015'
or id_cargo='7724'
or id_cargo='7721'
or id_cargo='7697'
or id_cargo='8245'
or id_cargo='8244'
or id_cargo='6282'
or id_cargo='8253'
or id_cargo='7606'
or id_cargo='7742'
or id_cargo='7478'
or id_cargo='8255'
or id_cargo='8212'
or id_cargo='6472'
or id_cargo='7063'
or id_cargo='8242'
or id_cargo='8089'
or id_cargo='6932'
or id_cargo='8249'
or id_cargo='8247'
or id_cargo='8127'
or id_cargo='7404'
or id_cargo='8182'
or id_cargo='7728'
or id_cargo='8246'
or id_cargo='7735'
or id_cargo='7743'
or id_cargo='8258'
or id_cargo='7577'
or id_cargo='8257'
or id_cargo='7752'
or id_cargo='7757'
or id_cargo='7741'
or id_cargo='8069'
or id_cargo='6140'
or id_cargo='7760'
or id_cargo='8262'
or id_cargo='7761'
or id_cargo='8263'
or id_cargo='7248'
or id_cargo='8264'
or id_cargo='7763'
or id_cargo='8265'
or id_cargo='7766'
or id_cargo='7391'
or id_cargo='7765'
or id_cargo='7708'
or id_cargo='7764'
or id_cargo='8230'
or id_cargo='7605'
or id_cargo='6117'
or id_cargo='7774'
or id_cargo='7772'
or id_cargo='7773'
or id_cargo='7663'
or id_cargo='6529'
or id_cargo='8252'
or id_cargo='7487'
or id_cargo='7779'
or id_cargo='6844'
or id_cargo='6981'
or id_cargo='8266'
or id_cargo='7778'
or id_cargo='7166'
or id_cargo='6098'
or id_cargo='7167'
or id_cargo='7768'
or id_cargo='8204'
or id_cargo='8085'
or id_cargo='7519'
or id_cargo='7717'
or id_cargo='7507'
or id_cargo='7381'
or id_cargo='7785'
or id_cargo='7780'
or id_cargo='7793'
or id_cargo='7787'
or id_cargo='7790'
or id_cargo='7784'
or id_cargo='7737'
or id_cargo='6212'
or id_cargo='6782'
or id_cargo='7019'
or id_cargo='6462'
or id_cargo='6102'
or id_cargo='7808'
or id_cargo='8197'
or id_cargo='6947'
or id_cargo='8038'
or id_cargo='7745'
or id_cargo='7803'
or id_cargo='7797'
or id_cargo='8271'
or id_cargo='7800'
or id_cargo='7604'
or id_cargo='7804'
or id_cargo='7814'
or id_cargo='7807'
or id_cargo='8280'
or id_cargo='8158'
or id_cargo='8235'
or id_cargo='7786'
or id_cargo='7789'
or id_cargo='8281'
or id_cargo='8275'
or id_cargo='7810'
or id_cargo='8274'
or id_cargo='8273'
or id_cargo='7809'
or id_cargo='8294'
or id_cargo='7819'
or id_cargo='7664'
or id_cargo='7594'
or id_cargo='7833'
or id_cargo='7794'
or id_cargo='8284'
or id_cargo='7824'
or id_cargo='7825'
or id_cargo='7821'
or id_cargo='8285'
or id_cargo='8251'
or id_cargo='7832'
or id_cargo='7813'
or id_cargo='8075'
or id_cargo='8286'
or id_cargo='8277'
or id_cargo='7820'
or id_cargo='7641'
or id_cargo='7838'
or id_cargo='7189'
or id_cargo='8289'
or id_cargo='8288'
or id_cargo='7836'
or id_cargo='7012'
or id_cargo='8291'
or id_cargo='7840'
or id_cargo='7339'
or id_cargo='7798'
or id_cargo='7831'
or id_cargo='7837'
or id_cargo='7839'
or id_cargo='8290'
or id_cargo='8292'
or id_cargo='7812'
or id_cargo='7511'
or id_cargo='7815'
or id_cargo='7841'
or id_cargo='8293'
or id_cargo='7822'
or id_cargo='7829'
or id_cargo='7823'
or id_cargo='8295'
or id_cargo='6099'
or id_cargo='6100'
or id_cargo='7598'
or id_cargo='6053'
or id_cargo='6005'
or id_cargo='6029'
or id_cargo='6034'
or id_cargo='6016'
or id_cargo='6035'
or id_cargo='6135'
or id_cargo='6083'
or id_cargo='6085'
or id_cargo='6017'
or id_cargo='6020'
or id_cargo='6028'
or id_cargo='6003'
or id_cargo='6205'
or id_cargo='6001'
or id_cargo='6021'
or id_cargo='6054'
or id_cargo='6077'
or id_cargo='6039'
or id_cargo='6079'
or id_cargo='6022'
or id_cargo='6237'
or id_cargo='6279'
or id_cargo='6277'
or id_cargo='6276'
or id_cargo='6300'
or id_cargo='6089'
or id_cargo='6052'
or id_cargo='6206'
or id_cargo='6000'
or id_cargo='6325'
or id_cargo='6062'
or id_cargo='6200'
or id_cargo='6075'
or id_cargo='6194'
or id_cargo='6536'
or id_cargo='6545'
or id_cargo='6512'
or id_cargo='6567'
or id_cargo='6565'
or id_cargo='6568'
or id_cargo='6539'
or id_cargo='6544'
or id_cargo='6619'
or id_cargo='6566'
or id_cargo='6073'
or id_cargo='6632'
or id_cargo='6675'
or id_cargo='6026'
or id_cargo='6713'
or id_cargo='6739'
or id_cargo='6611'
or id_cargo='6234'
or id_cargo='6767'
or id_cargo='6195'
or id_cargo='6900'
or id_cargo='6899'
or id_cargo='6002'
or id_cargo='6901'
or id_cargo='6971'
or id_cargo='6967'
or id_cargo='6296'
or id_cargo='7025'
or id_cargo='7026'
or id_cargo='6993'
or id_cargo='6992'
or id_cargo='6997'
or id_cargo='7057'
or id_cargo='7056'
or id_cargo='7055'
or id_cargo='7061'
or id_cargo='6025'
or id_cargo='7095'
or id_cargo='7136'
or id_cargo='7137'
or id_cargo='7112'
or id_cargo='7193'
or id_cargo='7157'
or id_cargo='7198'
or id_cargo='7029'
or id_cargo='7314'
or id_cargo='7343'
or id_cargo='7344'
or id_cargo='7345'
or id_cargo='7342'
or id_cargo='7395'
or id_cargo='7388'
or id_cargo='7376'
or id_cargo='7402'
or id_cargo='7389'
or id_cargo='7401'
or id_cargo='7413'
or id_cargo='7416'
or id_cargo='7411'
or id_cargo='7307'
or id_cargo='7448'
or id_cargo='6849'
or id_cargo='7449'
or id_cargo='7442'
or id_cargo='7474'
or id_cargo='7509'
or id_cargo='7526'
or id_cargo='7460'
or id_cargo='6848'
or id_cargo='7192'
or id_cargo='7535'
or id_cargo='6595'
or id_cargo='7576'
or id_cargo='7575'
or id_cargo='7471'
or id_cargo='7443'
or id_cargo='7650'
or id_cargo='7647'
or id_cargo='7377'
or id_cargo='7680'
or id_cargo='7714'
or id_cargo='7715'
or id_cargo='7369'
or id_cargo='7524'
or id_cargo='7688'
or id_cargo='7770'
or id_cargo='7769'
or id_cargo='7130'
or id_cargo='7681'
or id_cargo='7792'
or id_cargo='7802'
or id_cargo='7805'
or id_cargo='7806'
or id_cargo='7141'
or id_cargo='7801'
or id_cargo='7290'
or id_cargo='6192'
or id_cargo='6737'
or id_cargo='6144'
or id_cargo='6239'
or id_cargo='6123'
or id_cargo='7407'
or id_cargo='6725'
or id_cargo='6137'
or id_cargo='7139'
or id_cargo='6764'
or id_cargo='8053'
or id_cargo='7475'
or id_cargo='7150'
or id_cargo='7518'
or id_cargo='7515'
or id_cargo='7522'
or id_cargo='7517'
or id_cargo='7660'
or id_cargo='8229'
)
AND rut <> ''
OR rut = ''
ORDER BY
nombre_completo ASC");

 	
 	$query_dec=Decodear3($query_enc);
 	

    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/mediciones/entorno.html"));
    $id_empresa = $_SESSION["id_empresa"];
    $PRINCIPAL = str_replace("{ENTORNO_MEDICIONES}", lista_mediciones(FuncionesTransversalesAdmin(file_get_contents("views/mediciones/entorno_evaluaciones.html")), $id_empresa, $id_categoria), $PRINCIPAL);
    $datos_empresa = DatosEmpresa($id_empresa);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "preguntados_usuario") {
    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/preguntados/entorno.html"));
    $id_empresa = $_SESSION["id_empresa"];
    $id_objeto = "be_preguntados_m1";
    $excel = $get["excel"];

    if ($excel == 1) {
        $txt = $txt_e . $txt_fi . $txt_ft;
        header('Content-type: text/plain');
        header('Content-Disposition: attachment; filename=Reporte_por_Usuario_Preguntados_' . $txt . '.csv');
        echo "RUT;NOMBRE_USUARIO;DESAFIOS_JUGADOS;DESAFIOS_GANADOS;PUNTOS;\r\n";
        $Total_Rows_full = lista_usuarios_preguntados_excel($id_empresa, $id_objeto, $excel);
                foreach ($Total_Rows_full as $Unico) {

            echo $Unico->rut_completo . ";" . ($Unico->nombre_completo) . ";" . $Unico->vecesjugado . ";" . $Unico->total_veces_ganado . ";" . $Unico->suma_correctas . "\r\n";
        }
    } else {
        $PRINCIPAL = str_replace("{ENTORNO_USUARIO_PREGUNTADOS}", lista_usuarios_preguntados(FuncionesTransversalesAdmin(file_get_contents("views/preguntados/entorno_usuarios_preguntados.html")), $id_empresa, $id_objeto, $excel), $PRINCIPAL);
        $datos_empresa = DatosEmpresa($id_empresa);
        echo CleanHTMLWhiteList($PRINCIPAL);exit;
    }
}
else if ($seccion == "preguntados_pregunta") {
    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/preguntados/entorno_preguntas.html"));
    $id_empresa = $_SESSION["id_empresa"];
    $id_objeto = "be_preguntados_m1";
    $excel = $get["excel"];

    if ($excel == 1) {
        $txt = $txt_e . $txt_fi . $txt_ft;
        header('Content-type: text/plain');
        header('Content-Disposition: attachment; filename=Reporte_por_Preguntas_Preguntados_' . $txt . '.csv');
        echo "PREGUNTA;VECES_RESPONDIDA;VECES_CORRECTA;PORCENTAJE;\r\n";
        $Total_Rows_full = lista_preguntas_preguntados_excel($id_empresa, $id_objeto, $excel);
        
        foreach ($Total_Rows_full as $Unico) {

            echo $Unico->pregunta . ";" . ($Unico->Respondida) . ";" . $Unico->Correcta . ";" . $Unico->Porcentaje . "\r\n";
        }
    } else {
        $PRINCIPAL = str_replace("{ENTORNO_PREGUNTAS_PREGUNTADOS}", lista_preguntas_preguntados(FuncionesTransversalesAdmin(file_get_contents("views/preguntados/entorno_preguntas_preguntados.html")), $id_empresa, $id_objeto, $excel), $PRINCIPAL);
        $datos_empresa = DatosEmpresa($id_empresa);
        echo CleanHTMLWhiteList($PRINCIPAL);exit;
    }
}
else if ($seccion == "usuarios_ejecutivos") {
    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/ejecutivos/entorno.html"));
    $id_empresa = $_SESSION["id_empresa"];

    $PRINCIPAL = str_replace("{ENTORNO_EJECUTIVOS}", lista_ejecutivos(FuncionesTransversalesAdmin(file_get_contents("views/ejecutivos/entorno_expertos.html")), $id_empresa, $id_categoria), $PRINCIPAL);
    $datos_empresa = DatosEmpresa($id_empresa);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "intro") {
    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/mejores_practicas/mp_estadisticas.html"));
    $id_empresa = $_SESSION["id_empresa"];

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "mejores_practicas_estadisticas_Excel") {
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-type:   application/x-msexcel; charset=utf-8");
    header("Content-Disposition: attachment; filename=MP_estadisticas.xls");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private", false);

    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/mejores_practicas/entorno_estadisticas.html"));
    $id_empresa = $_SESSION["id_empresa"];
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
    Mejores_Practicas_Estadisticas(FuncionesTransversalesAdmin(file_get_contents("views/mejores_practicas/entorno_practicas.html")), $id_empresa, $id_categoria);
}
else if ($seccion == "puntos_estadisticas_Excel") {
    $id_empresa = $_SESSION["id_empresa"];
    require_once 'clases/PHPExcel.php';
    $objPHPExcel = new PHPExcel();
    $objPHPExcel = new PHPExcel();
    $styleArray = array('font' => array('bold' => true, 'color' => array('rgb' => '000000'), 'size' => 12, 'name' => 'Calibri'));
    $objPHPExcel->getProperties()
        ->setCreator("GO Partners")
        ->setLastModifiedBy("GO Partners")
        ->setTitle("Canje de Puntos")
        ->setSubject("Canje de Puntos")
        ->setDescription("Canje de Puntos")
        ->setKeywords("Excel Office 2007 openxml php")
        ->setCategory("Plantilla usuarios");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A1", "RUT");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B1", "NOMBRE");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C1", "CARGO");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D1", "SEGMENTO");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E1", "ITEM");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F1", "DIMENSION");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G1", "FECHA");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H1", "HORA");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I1", "ESTADO_VALIDACION");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J1", "PUNTOS");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K1", "FECHA_USO");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L1", "HORA_INICIO");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("M1", "HORA_TERMINO");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("N1", "RUT_JEFE");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("O1", "NOMBRE_JEFE");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("P1", "CARGO_JEFE");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("Q1", "RUT_BACKUP1");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("R1", "NOMBRE_BACKUP1");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("S1", "RUT_BACKUP2");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("T1", "NOMBRE_BACKUP2");
    $i = 2;
    $Arreglo = Puntos_Query_Descarga($id_empresa);
    foreach ($Arreglo as $usu) {
        $nombre_array = TraeDatosUsuario($usu->rut);
        $nombre_jefe_array = TraeDatosUsuario($usu->rut_jefe);
        $nombre_backup1 = TraeDatosUsuario($usu->rut_backup1);
        $nombre_backup2 = TraeDatosUsuario($usu->rut_backup2);
        $jk++;
        if ($nombre_array[0]->rut_completo == "") {continue;}
        $estado = "";
        if ($usu->estadovalidacion == "1") {$estado = "CANJEADO";}
        if ($usu->estadovalidacion == "2") {$estado = "PENDIENTE";}
        if ($usu->estadovalidacion == "3") {$estado = "RECHAZADO";}
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $i, ($nombre_array[0]->rut_completo));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B" . $i, ($nombre_array[0]->nombre_completo));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C" . $i, ($nombre_array[0]->cargo));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D" . $i, ($nombre_array[0]->mundo));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E" . $i, ($usu->Item));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F" . $i, ($usu->Dimension));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G" . $i, ($usu->fechasolicitud));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H" . $i, ($usu->horasolicitud));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I" . $i, ($estado));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J" . $i, ($usu->puntos));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K" . $i, ($usu->fechaUso));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L" . $i, ($usu->HoraInicioUso));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("M" . $i, ($usu->HoraTerminoUso));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("N" . $i, ($nombre_jefe_array[0]->rut_completo));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("O" . $i, ($nombre_jefe_array[0]->nombre_completo));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("P" . $i, ($nombre_jefe_array[0]->cargo));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("Q" . $i, ($nombre_backup1[0]->rut_completo));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("R" . $i, ($nombre_backup1[0]->nombre_completo));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("S" . $i, ($nombre_backup2[0]->rut_completo));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("T" . $i, ($nombre_backup2[0]->nombre_completo));
        $i++;
    }
    $sheet = $objPHPExcel->getActiveSheet();
    $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(true);
    /** @var PHPExcel_Cell $cell */
    foreach ($cellIterator as $cell) {
        $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
    }
    $objPHPExcel->getActiveSheet()->setTitle('Usuarios');
    $objPHPExcel->setActiveSheetIndex(0);
    $fechahoy = date("Y-m-d") . "_" . date("H:i:s");
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="GO_Canje_Puntos_Usuarios_' . $fechahoy . '.xlsx"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
}
else if ($seccion == "adcurm") {
    $PRINCIPAL = FormularioCurso(FuncionesTransversalesAdmin(file_get_contents("views/curso/entorno_subida_excel.html")), $id_curso);


    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "procesa_excel") {
    CancelaProcesamientoPrevio();
    extract($post);
    if ($action == "upload") {
//cargamos el archivo al servidor con el mismo nombre
        //solo le agregue el sufijo tmp_ev_
        $archivo = $_FILES['excel']['name'];
        VerificaExtensionFilesAdmin($_FILES["excel"]);
        $tipo = $_FILES['excel']['type'];
        $destino = "tmp_ev_" . $archivo;
        if (copy($_FILES['excel']['tmp_name'], $destino)) {

        } else {
            echo "Error Al Cargar el Archivo";
        }
        if (file_exists("tmp_ev_" . $archivo)) {
/** Clases necesarias */
            require_once 'clases/PHPExcel.php';
            require_once 'clases/PHPExcel/Reader/Excel2007.php';
// Cargando la hoja de cálculo
            $objReader = new PHPExcel_Reader_Excel2007();
            $objPHPExcel = $objReader->load("tmp_ev_" . $archivo);
            $objFecha = new PHPExcel_Shared_Date();
// Asignar hoja de excel activa
            $objPHPExcel->setActiveSheetIndex(0);
//conectamos con la base de datos
            // Llenamos el arreglo con los datos  del archivo xlsx
            for ($i = 2; $i <= 47; $i++) {
                $_DATOS_EXCEL[$i]['numero_identificador'] = ($objPHPExcel->getActiveSheet()->getCell('A' . $i)->getCalculatedValue());
                $_DATOS_EXCEL[$i]['nombre_curso'] = ($objPHPExcel->getActiveSheet()->getCell('B' . $i)->getCalculatedValue());
                $_DATOS_EXCEL[$i]['descripcion'] = ($objPHPExcel->getActiveSheet()->getCell('C' . $i)->getCalculatedValue());
                $_DATOS_EXCEL[$i]['objetivo_curso'] = ($objPHPExcel->getActiveSheet()->getCell('D' . $i)->getCalculatedValue());
                $_DATOS_EXCEL[$i]['clasificacion_curso'] = $objPHPExcel->getActiveSheet()->getCell('E' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['modalidad_curso'] = $objPHPExcel->getActiveSheet()->getCell('F' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['tipo_curso'] = $objPHPExcel->getActiveSheet()->getCell('G' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['sence'] = $objPHPExcel->getActiveSheet()->getCell('H' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['cbc'] = $objPHPExcel->getActiveSheet()->getCell('I' . $i)->getCalculatedValue();

                $_DATOS_EXCEL[$i]['valor_hora'] = $objPHPExcel->getActiveSheet()->getCell('J' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['valor_hora_sence'] = $objPHPExcel->getActiveSheet()->getCell('K' . $i)->getCalculatedValue();

                $_DATOS_EXCEL[$i]['numero_horas'] = $objPHPExcel->getActiveSheet()->getCell('L' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['cantidad_max_par'] = $objPHPExcel->getActiveSheet()->getCell('M' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['rut_otec'] = $objPHPExcel->getActiveSheet()->getCell('N' . $i)->getCalculatedValue();
            }
        }
//si por algo no cargo el archivo tmp_ev_
        else {
            echo "Necesitas primero importar el archivo";
        }
        $errores = 0;
//recorremos el arreglo multidimensional
        //para ir recuperando los datos obtenidos
        //del excel e ir insertandolos en la BD
        $total_actualizar = 0;
        $total_insertar = 0;
        $error = 0;
        foreach ($_DATOS_EXCEL as $valor) {
            if ($valor["numero_identificador"]) {
//Verifico si existe
                $verifico_registro = VerificaCodIdentificador($valor["numero_identificador"]);
                if ($verifico_registro) {
                    InsertaCursoTemp($valor["nombre_curso"], $valor["descripcion"], $valor["modalidad_curso"], $valor["tipo_curso"], "", "", $valor["objetivo_curso"], $valor["sence"], $valor["numero_horas"], $valor["cantidad_max_par"], $valor["rut_otec"], $valor["clasificacion_curso"], $valor["cbc"], $valor["numero_identificador"], "Actualizar", $verifico_registro[0]->id, $valor['valor_hora'], $valor['valor_hora_sence']);
                    $total_actualizar++;
                } else {
                    InsertaCursoTemp($valor["nombre_curso"], $valor["descripcion"], $valor["modalidad_curso"], $valor["tipo_curso"], "", "", $valor["objetivo_curso"], $valor["sence"], $valor["numero_horas"], $valor["cantidad_max_par"], $valor["rut_otec"], $valor["clasificacion_curso"], $valor["cbc"], $valor["numero_identificador"], "Insertar", 0, $valor['valor_hora'], $valor['valor_hora_sence']);
                    $total_insertar++;
                }
            }
        }

    }
    $PRINCIPAL = FormularioCurso(FuncionesTransversalesAdmin(file_get_contents("views/curso/entorno_subida_excel.html")), $id_curso);
//CON ESTA FUNCION, GENERO EL LISTADO PREVIO, PARA QUE SE VISUALIZE LA INFORMACION DE LOS QUE SE VAN A SUBIR

    $PRINCIPAL = str_replace("{ENTORNO_PREVIA}", (ListadoCursosMasivosPrevio($total_actualizar, $total_insertar)), $PRINCIPAL);

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "procesa_excel_u") {
    $idEmpresa = $_SESSION["id_empresa"];

    CancelaProcesamientoPrevioUsuarios();

    extract($post);

    $error_grave = "error";

    if ($action == "upload") {
//cargamos el archivo al servidor con el mismo nombre
        //solo le agregue el sufijo tmp_ev_
        $archivo = $_FILES['excel']['name'];VerificaExtensionFilesAdmin($_FILES["excel"]);
        $tipo = $_FILES['excel']['type'];
        $destino = "back_temp_" . $archivo;

        if (copy($_FILES['excel']['tmp_name'], $destino)) {

        } else {
            $error_grave = "Error al subir Excel<br>";
        }

        if (file_exists("back_temp_" . $archivo)) {
            require_once 'clases/PHPExcel.php';
            require_once 'clases/PHPExcel/Reader/Excel2007.php';

            $objReader = new PHPExcel_Reader_Excel2007();
            $objPHPExcel = $objReader->load("back_temp_" . $archivo);
            $objFecha = new PHPExcel_Shared_Date();
            $objPHPExcel->setActiveSheetIndex(0);
            $HojaActiva = $objPHPExcel->getActiveSheet();
            $total_filas = $HojaActiva->getHighestRow();
            $lastColumn = $HojaActiva->getHighestColumn();
            $total_columnas = PHPExcel_Cell::columnIndexFromString($lastColumn);
            $UltimaColumna = "A";
            $j = 0;
            $_DATOS_EXCEL = array();

//Obtengo los nombres de los campos
            for ($i = 0; $i <= $total_columnas; $i++) {
                $_campo[$i] = trim(($HojaActiva->getCell($UltimaColumna . "1")->getCalculatedValue()));
                $UltimaColumna++;
            }

//Obtengo datos de filas
            for ($fila = 2; $fila <= $total_filas; $fila++) {
                for ($columna = 0; $columna <= $total_columnas - 1; $columna++) {
                    $_DATOS_EXCEL[$j][$_campo[$columna]] = trim($HojaActiva->getCellByColumnAndRow($columna, $fila)->getCalculatedValue());
                }
                $j++;
            }
        } else {
            $error_grave = "No has subido el Excel";
        }

        $total_errores = 0;
        $total_actualizar = 0;
        $total_insertar = 0;
        $total_desvincular = 0;
        $lineaT = "";
        $linea = 2;





        foreach ($_DATOS_EXCEL as $key => $item) {

            $coma = "";
            $values = "";
            $registros = "";
            $rut = "";
            $error_columna = "";
            $parar = 0;
            $error_grave = "";
            $mensaje_errores = "";
            $cuenta_campos_linea = 0;
            $valuesReal = "";
            $registrosReal = "";

            foreach ($item as $key2 => $valor) {
                $valor = limpiarSimbolosExtranosSinPuntos($valor);

                $campoTabla = jCampoVisualCambioCampoTabla($idEmpresa, $key2);
                if ($key2 == "id_malla") {
                    $campoTabla = "id_malla";
                    $id_malla_value = $valor;
                } else {

                    if ($campoTabla == "jefe") {
                        $cuerpoJefe = explode("-", $valor);
                        $valor = $cuerpoJefe[0]; // porción1
                        $valor = limpiarSimbolosExtranos($valor);

                    }
                    if ($campoTabla == "rut_completo") {
                        $valor = limpiarSimbolosExtranos($valor);
                    }

                    if ($campoTabla == "fecha_antiguedad") {

                        
                        $FormatoFechaYYYYMMDD = validadFormatoFecha($valor);


                        if ($FormatoFechaYYYYMMDD == 1) {
                            $valor = $valor;
                        } else {
                            $valor = transformfechaexcelaphp($valor);
                        }

                    }

                    if ($campoTabla != '' and $campoTabla != ' ') {
                        $valuesReal .= $coma . $campoTabla;
                        $registrosReal .= $coma . "'" . $valor . "'";
                        $coma = ",";
                    }
                }
                $esObligatorio = jValidarObligatorioCampo($idEmpresa, $key2);

                if ($campoTabla == "jefe") {
                    $cuerpoJefe = explode("-", $valor);
                    $valor = $cuerpoJefe[0]; // porción1
                    $valor = limpiarSimbolosExtranos($valor);
                }
                $values .= $coma . $campoTabla;
                $registros .= $coma . "'" . $valor . "'";
                $coma = ",";

                if ($esObligatorio == 1 && $valor == "" || $valor == " ") {
                    $error_columna .= $key2 . ", ";
                }


                if ($campoTabla == "rut_completo") {
                    $cuerpoRut = explode("-", $valor);
                    $rut = $cuerpoRut[0]; // porción1
                    $rut = limpiarSimbolosExtranos($rut);
                }

                if ($campoTabla == "nombre") {$nombre = $valor;}
                if ($campoTabla == "apaterno") {$apaterno = $valor;}
                $nombre_completo = $nombre . " " . $apaterno;
                $cuenta_campos_linea++;
                $userField[$cuenta_campos_linea] = $campoTabla;
                $userValue[$cuenta_campos_linea] = $valor;
            }
            $cuenta_campos_lineaA = $cuenta_campos_linea + 1;
            $cuenta_campos_lineaB = $cuenta_campos_linea + 2;
            $cuenta_campos_lineaC = $cuenta_campos_linea + 3;

            $userField[$cuenta_campos_lineaA] = "rut";
            $userField[$cuenta_campos_lineaB] = "nombre_completo";

            $userValue[$cuenta_campos_lineaA] = $rut;
            $userValue[$cuenta_campos_lineaB] = $nombre_completo;

            $values = $values . " , rut, nombre_completo";
            $valuesReal = $valuesReal . " , rut, nombre_completo";
            $registros = $registros . ", '$rut', '$nombre_completo'";
            $registrosReal = $registrosReal . ", '$rut', '$nombre_completo'";

//PERSONAS

            $accion = "";
            $accion = DatosTablaUsuarioCompleto($userField, $userValue, $cuenta_campos_lineaA, $rut);

            if ($accion == "Insertar") {

                $registrosReal = ($registrosReal);
                InsertTblUsuarioEmpresa($valuesReal, $registrosReal, $idEmpresa);
            } elseif ($accion == "Actualizar") {
                $registrosReal = ($registrosReal);

                UpdateTblUsuarioEmpresaMasivo($rut, $valuesReal, $registrosReal, $idEmpresa);
            } else {
// Nada
            }

            $linea++;
        }
    }

    if (jValidarEmpresaSubidaUsuarios($idEmpresa)) {} else {}

    echo "
<script>swal('Actualización Realizada Exitosamente', '', 'success');</script>
<script>location.href='?sw=procesa_excel_a';</script>";

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "procesa_excel_uBK") {
    CancelaProcesamientoPrevioUsuarios();
    extract($post);
    $id_empresa = $_SESSION["id_empresa"];
    $error_grave = "error";
    if ($action == "upload") {
//cargamos el archivo al servidor con el mismo nombre
        //solo le agregue el sufijo tmp_ev_
        $archivo = $_FILES['excel']['name'];VerificaExtensionFilesAdmin($_FILES["excel"]);
        $tipo = $_FILES['excel']['type'];
        $destino = "tmp_ev_" . $archivo;
        if (copy($_FILES['excel']['tmp_name'], $destino)) {

        } else {
            $error_grave = "Error Al subir Archivo<br>";
        }
        if (file_exists("tmp_ev_" . $archivo)) {
            require_once 'clases/PHPExcel.php';
            require_once 'clases/PHPExcel/Reader/Excel2007.php';

            $objReader = new PHPExcel_Reader_Excel2007();
            $objPHPExcel = $objReader->load("tmp_ev_" . $archivo);
            $objFecha = new PHPExcel_Shared_Date();
            $objPHPExcel->setActiveSheetIndex(0);
            $HojaActiva = $objPHPExcel->getActiveSheet();
            $total_filas = $HojaActiva->getHighestRow();
            $lastColumn = $HojaActiva->getHighestColumn();
            $total_columnas = PHPExcel_Cell::columnIndexFromString($lastColumn);
            $UltimaColumna = "A";
            $j = 0;
            $_DATOS_EXCEL = array();

//Obtengo los nombres de los campos
            for ($i = 0; $i <= $total_columnas; $i++) {
                $_campo[$i] = trim(($HojaActiva->getCell($UltimaColumna . "1")->getCalculatedValue()));
                $UltimaColumna++;
            }
//Obtengo datos de filas
            for ($fila = 2; $fila <= $total_filas; $fila++) {
                for ($columna = 0; $columna <= $total_columnas - 1; $columna++) {
                    $_DATOS_EXCEL[$j][$_campo[$columna]] = trim($HojaActiva->getCellByColumnAndRow($columna, $fila)->getCalculatedValue());
                }
                $j++;
            }
        } else {
            $error_grave = "Necesitas primero importar el archivo";
        }

        $total_errores = 0;
        $total_actualizar = 0;
        $total_insertar = 0;
        $linea = 2;
        foreach ($_DATOS_EXCEL as $key => $item) {
            if ($item["rut_duplicado"] == "1") {
                $item["rut"] = $item["codigo_sap"];
            }
            $coma = "";
            $values = "";
            $registros = "";
            $rut = "";
            $error_columna = "";
            $parar = 0;
            $error_grave = "";
            foreach ($item as $key2 => $valor) {
                $values .= $coma . $key2;
                $registros .= $coma . "'" . $valor . "'";
                $coma = ",";
                if ($key2 == "rut" && $valor != "") {
                    $rut = $valor;
                }
            }

            if ($rut != "") {
                $verifico_registro = DatosTablaUsuario($rut);
                if ($verifico_registro) {
                    $accion = "ACTUALIZAR";
                    $total_actualizar++;
                    $mensaje_actualizar = "Se encontraron usuarios existentes en la base para actualizar!";
                } else {
                    $accion = "INSERTAR";
                    $total_insertar++;
                    $mensaje_nuevo = "Se encontrar nuevos usuarios para ingresar!";
                }

                $error_grave = InsertaUsuarioTemporal($values, ($registros), $id_empresa, $accion);
                if ($error_grave) {
                    break;
                }
            } else {
                $lineaT .= "<p>Columna [rut] - Linea [" . $linea . "]</p>";
                $total_errores++;
                $mensaje_errores = "Se encontraron errores en los datos!";
            }
            $linea++;
        }
    }

//CARGA VISTA
    $campos = TraeCamposPlantilla();
    $CamposEmpresa = TraeCampos($_SESSION["id_empresa"]);

    foreach ($campos as $unico) {
        $checkbox = '<div class="checkbox">
<label>
<input name="campo[]" type="checkbox" ' . $unico->checked . ' ' . $unico->disabled . ' value="' . $unico->campo . '"> ' . $unico->nombre . '
</label>
</div>';

        if ($unico->tipo == "obligatorio") {
            $checkbox .= '<input name="campo[]" type="hidden" value="' . $unico->campo . '">';
            $obligatorio .= $checkbox;
        }

        if ($unico->tipo == "importante") {
            $importante .= $checkbox;
        }

        if ($unico->tipo == "opcional") {
            $opcional .= $checkbox;
        }
    }

    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/basepersonas/formulario_subida_masivo.html"));
    $PRINCIPAL = str_replace("{OBLIGATORIOS}", $obligatorio, $PRINCIPAL);
    $PRINCIPAL = str_replace("{IMPORTANTES}", $importante, $PRINCIPAL);
    $PRINCIPAL = str_replace("{OPCIONALES}", $opcional, $PRINCIPAL);
    $totalusuarios = CuentaUsuarios($_SESSION["id_empresa"]);
    $PRINCIPAL = str_replace("{TOTALU}", colocarPesos($totalusuarios), $PRINCIPAL);
    if (!$error_grave) {
        $PRINCIPAL = str_replace("{DISPLAYFORM1}", "", $PRINCIPAL);
        $PRINCIPAL = str_replace("{DISPLAYFORM2}", "none", $PRINCIPAL);
        $PRINCIPAL = str_replace("{DISPLAYERROR}", "display:none;", $PRINCIPAL);
    } else {
        $PRINCIPAL = str_replace("{DISPLAYFORM1}", "display:none;", $PRINCIPAL);
        $PRINCIPAL = str_replace("{DISPLAYFORM2}", "inline", $PRINCIPAL);
        $PRINCIPAL = str_replace("{DISPLAYERROR}", "", $PRINCIPAL);
        $total_errores = 0;
        $total_actualizar = 0;
        $total_insertar = 0;
        $lineaT = "";
    }
    $PRINCIPAL = str_replace("{DISPLAYFORM1}", "", $PRINCIPAL);
    $PRINCIPAL = str_replace("{DISPLAYFORM2}", "none", $PRINCIPAL);
    $PRINCIPAL = str_replace("{TAB1}", "", $PRINCIPAL);
    $PRINCIPAL = str_replace("{CLASTAB1}", "tab-pane", $PRINCIPAL);
    $PRINCIPAL = str_replace("{TAB2}", "active", $PRINCIPAL);
    $PRINCIPAL = str_replace("{CLASTAB2}", "tab-pane active", $PRINCIPAL);
    $PRINCIPAL = str_replace("{TITULO2}", "Vista previa", $PRINCIPAL);
    $PRINCIPAL = str_replace("{TOT_ACTUALIZAR}", $total_actualizar, $PRINCIPAL);
    $PRINCIPAL = str_replace("{TOT_INSERTAR}", $total_insertar, $PRINCIPAL);
    $PRINCIPAL = str_replace("{TOT_ERRORES}", $total_errores, $PRINCIPAL);
    $PRINCIPAL = str_replace("{ERRORES}", $lineaT, $PRINCIPAL);
    $PRINCIPAL = str_replace("{ERROR_GRAVE}", $error_grave, $PRINCIPAL);

    if (!$error_grave) {
        $PRINCIPAL = str_replace("{ENTORNO_PREVIA}", (ListadoUsuariosMasivosPrevio($id_empresa)), $PRINCIPAL);
    } else {
        $PRINCIPAL = str_replace("{ENTORNO_PREVIA}", "", $PRINCIPAL);
    }
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "procesa_excel_inscripciones") {
    $id_inscripcion = Decodear3($post["idi"]);

    extract($post);
    if ($action == "upload") {
//cargamos el archivo al servidor con el mismo nombre
        //solo le agregue el sufijo tmp_ev_
        $archivo = $_FILES['excel']['name'];VerificaExtensionFilesAdmin($_FILES["excel"]);
        $tipo = $_FILES['excel']['type'];
        $destino = "tmp_ev_" . $archivo;
        if (copy($_FILES['excel']['tmp_name'], $destino)) {

        } else {
            echo "Error Al Cargar el Archivo";
        }
        if (file_exists("tmp_ev_" . $archivo)) {
/** Clases necesarias */
            require_once 'clases/PHPExcel.php';
            require_once 'clases/PHPExcel/Reader/Excel2007.php';
// Cargando la hoja de cálculo
            $objReader = new PHPExcel_Reader_Excel2007();
            $objPHPExcel = $objReader->load("tmp_ev_" . $archivo);
            $objFecha = new PHPExcel_Shared_Date();
// Asignar hoja de excel activa
            $objPHPExcel->setActiveSheetIndex(0);
//conectamos con la base de datos
            // Llenamos el arreglo con los datos  del archivo xlsx
            for ($i = 2; $i <= 47; $i++) {
                $_DATOS_EXCEL[$i]['rut'] = $objPHPExcel->getActiveSheet()->getCell('A' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['movilizacion'] = $objPHPExcel->getActiveSheet()->getCell('B' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['viatico'] = $objPHPExcel->getActiveSheet()->getCell('C' . $i)->getCalculatedValue();
                if ($_DATOS_EXCEL[$i]['rut'] == "") {
                    continue;
                }

                $datos_usuario = UsuarioEnBasePersonas($_DATOS_EXCEL[$i]['rut']);

                InsertaInscritosPorInsc($_DATOS_EXCEL[$i]['rut'], $_DATOS_EXCEL[$i]['movilizacion'], $_DATOS_EXCEL[$i]['viatico'], $id_inscripcion, $datos_usuario[0]->tramo_sence, $datos_usuario[0]->empresa_holding, $datos_usuario[0]->gerencia, $datos_usuario[0]->centro_costo, $datos_usuario[0]->cargo);
            }

            echo "
<script>
location.href='?sw=accioninsc&ii=" . Encodear3($id_inscripcion) . "#seccionInscripcionParticipantes';
</script>";
            exit;
        }
//si por algo no cargo el archivo tmp_ev_
        else {
            echo "Necesitas primero importar el archivo";
        }

    }
    $PRINCIPAL = FormularioCurso(FuncionesTransversalesAdmin(file_get_contents("views/basepersonas/entorno_subida_excel.html")), $id_curso);
//CON ESTA FUNCION, GENERO EL LISTADO PREVIO, PARA QUE SE VISUALIZE LA INFORMACION DE LOS QUE SE VAN A SUBIR

    $PRINCIPAL = str_replace("{ENTORNO_PREVIA}", ListadoUsuariosMasivosPrevio($total_actualizar, $total_insertar), $PRINCIPAL);

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "procesa_excel_cierre_inscripciones") {
    $id_inscripcion = Decodear3($post["idi"]);
//elimino todos los de la id inscripcion
    BorraUsuariosCierre($id_inscripcion);

    extract($post);
    if ($action == "upload") {
//cargamos el archivo al servidor con el mismo nombre
        //solo le agregue el sufijo tmp_ev_
        $archivo = $_FILES['excel']['name'];VerificaExtensionFilesAdmin($_FILES["excel"]);
        $tipo = $_FILES['excel']['type'];
        $destino = "tmp_ev_" . $archivo;
        if (copy($_FILES['excel']['tmp_name'], $destino)) {

        } else {
            echo "Error Al Cargar el Archivo";
        }
        if (file_exists("tmp_ev_" . $archivo)) {
/** Clases necesarias */
            require_once 'clases/PHPExcel.php';
            require_once 'clases/PHPExcel/Reader/Excel2007.php';
// Cargando la hoja de cálculo
            $objReader = new PHPExcel_Reader_Excel2007();
            $objPHPExcel = $objReader->load("tmp_ev_" . $archivo);
            $objFecha = new PHPExcel_Shared_Date();
// Asignar hoja de excel activa
            $objPHPExcel->setActiveSheetIndex(0);
//conectamos con la base de datos
            // Llenamos el arreglo con los datos  del archivo xlsx
            for ($i = 2; $i <= 47; $i++) {
                $_DATOS_EXCEL[$i]['rut'] = $objPHPExcel->getActiveSheet()->getCell('A' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['porcentaje'] = $objPHPExcel->getActiveSheet()->getCell('H' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['caso_especial'] = $objPHPExcel->getActiveSheet()->getCell('I' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['nota'] = $objPHPExcel->getActiveSheet()->getCell('J' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['estado'] = $objPHPExcel->getActiveSheet()->getCell('K' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['viatico_utilizado'] = $objPHPExcel->getActiveSheet()->getCell('L' . $i)->getCalculatedValue();
                $_DATOS_EXCEL[$i]['monto_utilizado'] = $objPHPExcel->getActiveSheet()->getCell('M' . $i)->getCalculatedValue();

                if ($_DATOS_EXCEL[$i]['rut'] == "") {
                    continue;
                }

                InsertaUsuariosFinalizadosInscripcion($_DATOS_EXCEL[$i]['rut'], $id_inscripcion, $_DATOS_EXCEL[$i]['porcentaje'], $_DATOS_EXCEL[$i]['caso_especial'], $_DATOS_EXCEL[$i]['nota'], $_DATOS_EXCEL[$i]['estado'], $_DATOS_EXCEL[$i]['viatico_utilizado'], $_DATOS_EXCEL[$i]['monto_utilizado']);
            }

            echo "
<script>
location.href='?sw=accioninsc&ii=" . Encodear3($id_inscripcion) . "#seccionCierreParticipantes';
</script>";
            exit;
        }
//si por algo no cargo el archivo tmp_ev_
        else {
            echo "Necesitas primero importar el archivo";
        }

    }
    $PRINCIPAL = FormularioCurso(FuncionesTransversalesAdmin(file_get_contents("views/basepersonas/entorno_subida_excel.html")), $id_curso);
//CON ESTA FUNCION, GENERO EL LISTADO PREVIO, PARA QUE SE VISUALIZE LA INFORMACION DE LOS QUE SE VAN A SUBIR

    $PRINCIPAL = str_replace("{ENTORNO_PREVIA}", ListadoUsuariosMasivosPrevio($total_actualizar, $total_insertar), $PRINCIPAL);

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "canceproCursos") {
    CancelaProcesamientoPrevioCursos($_SESSION["id_empresa"]);
    echo "
<script>
location.href='?sw=listcursos2Presenciales';
</script>";
    exit;
}
else if ($seccion == "canceprousu") {
    CancelaProcesamientoPrevioUsuarios();
    echo "
<script>
location.href='?sw=procesa_excel_a';
</script>";
    exit;
}
else if ($seccion == "canceprocierres") {
//CancelaProcesamientoPrevioUsuarios($_SESSION["id_empresa"]);
    CancelaProcesamientoPrevioInscripcionCierre($_SESSION["id_empresa"]);
    echo "
<script>
location.href='?sw=addIncripcionesCierre';
</script>";
    exit;
}
else if ($seccion == "cancepro") {
    CancelaProcesamientoPrevio();
    echo "
<script>
location.href='?sw=listcursos';
</script>";
    exit;
}
else if ($seccion == "aceppro") {
    AceptaProcesamientoPrevio();
    echo "
<script>
location.href='?sw=listcursos';
</script>";
    exit;
}
else if ($seccion == "aceprinscCierre") {
    AceptaProcesamientoPrevioIncricionesCierre($_SESSION["id_empresa"]);
    echo "
<script>
location.href='?sw=addIncripcionesCierre';
</script>";
    exit;
}
else if ($seccion == "acepproCursos") {
    AceptaProcesamientoPrevioCursosMasivos($_SESSION["id_empresa"]);

    echo "
<script>
location.href='?sw=listcursos2';
</script>";
    exit;
}
else if ($seccion == "acepprou") {
    AceptaProcesamientoPrevioU($_SESSION["id_empresa"]);

    echo "
<script>
location.href='?sw=listusu&exe=1';
</script>";
    exit;
}
else if ($seccion == "accionobjeto") {
    $id_objeto = Decodear3($get["i"]);
    if ($id_objeto) {
        $PRINCIPAL = FormularioObjeto(FuncionesTransversalesAdmin(file_get_contents("views/objeto/formulario_edita.html")), $id_objeto);
    } else {

        $PRINCIPAL = FormularioObjeto(FuncionesTransversalesAdmin(file_get_contents("views/objeto/formulario_ingresa.html")), $id_objeto);
    }

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "addeval") {
    $PRINCIPAL = ListadoEvaluaciones(FuncionesTransversalesAdmin(file_get_contents("views/evaluaciones/entorno_agrega_encuesta.html")));

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "accionEvaluacion") {
    $id_eval = Decodear3($get["i"]);
    if ($id_eval) {
        $PRINCIPAL = FormularioEvaluacion(FuncionesTransversalesAdmin(file_get_contents("views/evaluaciones/formulario_edita.html")), $id_eval);
    } else {

        $PRINCIPAL = FormularioEvaluacion(FuncionesTransversalesAdmin(file_get_contents("views/evaluaciones/formulario_ingresa.html")), $id_eval);
    }
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "adeval") {
    $nombre_evaluacion = (trim($post["nombre_evaluacion"]));
    $descripcion_evaluacion = (trim($post["descripcion_evaluacion"]));
//inserta registro
    InsertaEvaluacion($nombre_evaluacion, $descripcion_evaluacion);

    echo "
<script>
alert('Evaluacion creada correctamente. Recuerda que ahora debes ingresarle las preguntas');
location.href='?sw=addeval';
</script>";
}
else if ($seccion == "adtexto") {

    $texto_pregunta = (trim($post["descripcion_pregunta"]));
    $id_evaluacion = Decodear3($get["i"]);
    $tipo_pregunta = $post["tippre"];
    InsertaPreguntaPorEvaluacion($texto_pregunta, $id_evaluacion, $tipo_pregunta, "", "");

//Obtengo la ultmia pregunta con un MAX()
    $ultimo_id = ObtenerUltimaPregunta();
}
else if ($seccion == "vista_mallas_clasificaciones_cursos_objetos") {

    $id_malla = $post["malla"];
    $id_programa = $request["programa"];
    $rut = LimpiaRut($post["rut"]);
    $id_evaluacion = $post["id_evaluacion"];
    $id_objeto = $get["id_objeto"];
    $id_curso_sent = $get["id_curso_sent"];
    $id_empresa = $_SESSION["id_empresa"];


    if ($id_objeto != '' and $id_curso_sent == "") {
        $id_curso_sent_array = BuscaCursoDadoIdObjeto($id_objeto, $id_empresa);
        $id_curso_sent = $id_curso_sent_array[0]->id_curso;
    }

    if ($id_evaluacion != '' and $id_objeto != '') {
        UpdateEvaluacionObjeto($id_objeto, $_SESSION["id_empresa"], $id_evaluacion);
    }

    $PRINCIPAL = ListadoLMSMallas(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/lms_malla_clas_cursos/entorno_listado_lms_mallas.html")), $_SESSION["id_empresa"], $id_malla, $id_programa, $rut, $url_objetos_corta, $id_curso_sent);

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "vista_mallas_clasificaciones_cursos_objetos_saveMalla") {
    $id_empresa = $_SESSION["id_empresa"];
    $malla_nombre = $post["malla_nombre"];
    $malla_descripcion = $post["malla_descripcion"];
    $id_malla_sugerido = $post["id_malla_sugerido"];
    $id_programa = $post["id_programa"];
    $id_foco = $post["id_foco"];

    InsertMallaLmsMalla($id_malla_sugerido, $malla_nombre, $malla_descripcion, $id_empresa);
    InsertClasLmsClas($id_malla_sugerido, $malla_nombre, $malla_descripcion, $id_empresa);
    InsertMallaRelLmsMallaCla($id_malla_sugerido, $id_programa, $id_empresa);

    echo "
                    <script>
                    location.href='?sw=vista_mallas_clasificaciones_cursos_objetos&programa=" . $post[id_programa] . "';
                    </script>";
    exit;

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "vista_mallas_clasificaciones_cursos_objetosInsertaRel") {
    $id_empresa = $_SESSION["id_empresa"];
    $id_malla = $post["id_malla"];
    $id_programa = $post["id_programa"];
    $id_clasificacion = $post["id_clasificacion"];
    $id_curso = $post["id_curso"];

    InsertMallaRelLmsMallaClaCurso($id_malla, $id_clasificacion, $id_curso, $id_programa, $id_empresa);

    echo "
                    <script>
                    location.href='?sw=vista_mallas_clasificaciones_cursos_objetos&programa=" . $post[id_programa] . "';
                    </script>";
    exit;

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "vista_mallas_clasificaciones_cursos_objetos_saveObj") {
    $id_empresa = $_SESSION["id_empresa"];
    $nombre_objeto = $post["objeto_nombre"];
    $objeto_descripcion = $post["objeto_descripcion"];
    $objeto_tipo = $post["objeto_tipo"];
    $objeto_url = $post["objeto_url"];
    $objeto_duracion = $post["objeto_duracion"];
    $objeto_orden = $post["objeto_orden"];
    $objeto_numpreguntas = $post["objeto_numpreguntas"];
    $objeto_aprobacion = $post["objeto_aprobacion"];
    $id_curso = $post["id_curso"];
    $id_programa = $post["id_programa"];
    $id_objeto = $post["id_objeto"];
    $id_objeto_editar = $post["id_objeto_editar"];
    $id_evaluacion = $post["id_evaluacion"];
    $objeto_url = $post["objeto_url"];
    $objeto_permitir_ver_nota = $post["objeto_permitir_ver_nota"];
    $objeto_recalcular_notas = $post["objeto_recalcular_notas"];
    $objeto_distribucion_opciones = $post["objeto_distribucion_opciones"];
    $objeto_duracion_evaluacion_total = $post["objeto_duracion_evaluacion_total"];
    $objeto_intentos = $post["objeto_intentos"];

    $accion_edita = $post["edit"];
    $objeto_tipo_evaluacion = $post["objeto_tipo_evaluacion"];
    $objeto_nombre_evaluacion = $post["objeto_nombre_evaluacion"];

    $fecha_inicio = $post["fecha_inicio"];
    $hora_inicio = $post["hora_inicio"];
    $fecha_termino = $post["fecha_termino"];
    $hora_termino = $post["hora_termino"];

    if ($objeto_nombre_evaluacion != "" and $nombre_objeto == '') {$nombre_objeto = $objeto_nombre_evaluacion;}
    if ($objeto_tipo_evaluacion != "" and $objeto_tipo == '') {$objeto_tipo = $objeto_tipo_evaluacion;}

    $titulo_cierre = $post["titulo_cierre"];
    $texto_cierre = $post["texto_cierre"];

    $tamano = $_FILES["archivo"]['size'];
    $tipo = $_FILES["archivo"]['type'];
    $archivo = $_FILES["archivo"]['name'];

    VerificaExtensionFilesAdmin($_FILES["archivo"]);

    $prefijo = substr(md5(uniqid(rand())), 0, 6);
    $arreglo_archivo = explode(".", $archivo);
    $extension_archivo = $arreglo_archivo[1];
    $objeto_tipo_array = explode("-", $objeto_tipo);

    $extension_objeto = $objeto_tipo_array[1];
    $tipo_objeto = $objeto_tipo_array[0];

    if ($tipo_objeto == '5') {
        if ($accion_edita != 1) {
                        CreaNuevoObjetoEvaluacionSinEvaluacion($id_objeto, $nombre_objeto, $objeto_descripcion, $tipo_objeto, $extension_objeto, $objeto_url, $objeto_duracion,
                $objeto_orden, $objeto_numpreguntas, $objeto_aprobacion, $id_curso, $objeto_permitir_ver_nota, $objeto_recalcular_notas,
                $objeto_distribucion_opciones, $objeto_duracion_evaluacion_total, $objeto_permitir_ver_nota, $id_empresa, $titulo_cierre, $texto_cierre,
                $objeto_intentos, $fecha_inicio, $hora_inicio, $fecha_termino, $hora_termino);
        } else {
            
            EditaObjetoEvaluacion($id_objeto, $nombre_objeto, $objeto_descripcion, $tipo_objeto, $extension_objeto, $objeto_url, $objeto_duracion,
                $objeto_orden, $objeto_numpreguntas, $objeto_aprobacion, $id_curso, $objeto_permitir_ver_nota, $objeto_recalcular_notas,
                $objeto_distribucion_opciones, $objeto_duracion_evaluacion_total, $objeto_permitir_ver_nota, $id_empresa, $titulo_cierre, $texto_cierre,
                $objeto_intentos, $fecha_inicio, $hora_inicio, $fecha_termino, $hora_termino);
        }
    }
    if ($tipo_objeto == '3') {
        if ($accion_edita != 1) {
            $datos_subida = SuboArchivoObjetos($_FILES, $extension_archivo, $prefijo, $objeto_url);
            $ruta_para_base = str_replace("../front/", "", $objeto_url);
            CreaNuevoObjeto($id_objeto, $nombre_objeto, $objeto_descripcion, $tipo_objeto, $extension_objeto, $ruta_para_base . "/" . $datos_subida[1], $objeto_duracion, $objeto_orden, "", "", $id_curso, "", $id_empresa);
        } else {

            $url_sola = "../front/objetos/" . $id_objeto . "";
            $datos_subida_edit = SuboArchivoObjetos($_FILES, $extension_archivo, $prefijo, $url_sola);
            $url_sola = str_replace("../front/", "", $url_sola);

            if ($datos_subida_edit[1]) {
                $url_objeto_editar = $url_sola . "/" . $datos_subida_edit[1];
            } else {
                $url_objeto_editar = "";
            }

            EditaObjetoEvaluacionContenido($id_objeto, $nombre_objeto, $objeto_descripcion, $tipo_objeto, $extension_objeto,
                $url_objeto_editar, $objeto_duracion, $objeto_orden, "", "", $id_curso, "", $id_empresa);
        }
    }

    echo "
    <script>
            location.href='?sw=vista_mallas_clasificaciones_cursos_objetos&programa=" . $post[id_programa] . "&id_curso_sent=" . $id_curso . "';
    </script>";
    exit;

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "vista_mallas_clasificaciones_cursos_objetos_saveObj_eliminapregunta") {
    $id_empresa = $_SESSION["id_empresa"];
    $nombre_objeto = $post["objeto_nombre"];
    $objeto_descripcion = $post["objeto_descripcion"];
    $objeto_tipo = $post["objeto_tipo"];
    $objeto_url = $post["objeto_url"];
    $objeto_duracion = $post["objeto_duracion"];
    $objeto_orden = $post["objeto_orden"];
    $objeto_numpreguntas = $post["objeto_numpreguntas"];
    $objeto_aprobacion = $post["objeto_aprobacion"];
    $id_curso = $post["id_curso"];
    $id_programa = $post["id_programa"];
    $id_objeto = $post["id_objeto"];
    $id_evaluacion = $post["id_evaluacion"];
    $objeto_url = $post["objeto_url"];
    $objeto_permitir_ver_nota = $post["objeto_permitir_ver_nota"];
    $objeto_recalcular_notas = $post["objeto_recalcular_notas"];
    $objeto_distribucion_opciones = $post["objeto_distribucion_opciones"];
    $objeto_duracion_evaluacion_total = $post["objeto_duracion_evaluacion_total"];
    $objeto_intentos = $post["objeto_intentos"];

    $id_pregunta = $post["id_pregunta"];

    $accion_edita = $post["edit"];

    $objeto_tipo_evaluacion = $post["objeto_tipo_evaluacion"];
    $objeto_nombre_evaluacion = $post["objeto_nombre_evaluacion"];

    $fecha_inicio = $post["fecha_inicio"];
    $hora_inicio = $post["hora_inicio"];
    $fecha_termino = $post["fecha_termino"];
    $hora_termino = $post["hora_termino"];

    $titulo_cierre = $post["titulo_cierre"];
    $texto_cierre = $post["texto_cierre"];

    $tamano = $_FILES["archivo"]['size'];
    $tipo = $_FILES["archivo"]['type'];
    $archivo = $_FILES["archivo"]['name'];
    VerificaExtensionFilesAdmin($_FILES["archivo"]);
    $prefijo = substr(md5(uniqid(rand())), 0, 6);
    $arreglo_archivo = explode(".", $archivo);
    $extension_archivo = $arreglo_archivo[1];

    $objeto_tipo_array = explode("-", $objeto_tipo);

    $extension_objeto = $objeto_tipo_array[1];
    $tipo_objeto = $objeto_tipo_array[0];

    UpdatePreguntaIdOBjetoTodos($id_evaluacion, $id_objeto, $id_pregunta, "_99");

    echo "
                    <script>
                    location.href='?sw=vista_mallas_clasificaciones_cursos_objetos&programa=" . $post[id_programa] . "';
                    </script>";
    exit;

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "ListadoMantenedor") {
    $id_empresa = $_SESSION["id_empresa"];
    $arreglo_post = $post;
    if ($get["tipo"]) {
        $arreglo_post = $get;
    }
    $PRINCIPAL = FuncionListadoMantenedores(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/mantenedor/index.html")), $id_empresa, $arreglo_post);
    $PRINCIPAL = str_replace("{ID_EMPRESA}", $id_empresa, $PRINCIPAL);

    if ($post["tipo"] == "objeto") {
        $PRINCIPAL = str_replace("{DISPLAY_TIPO_OBJETO}", "", $PRINCIPAL);
        $PRINCIPAL = str_replace("{SELECTED" . $post["tipo_objeto"] . "}", "selected", $PRINCIPAL);
    } else {
        $PRINCIPAL = str_replace("{DISPLAY_TIPO_OBJETO}", 'style="display: none"', $PRINCIPAL);
    }

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "audiencias") {
    $id_empresa = $_SESSION["id_empresa"];
    $arreglo_post = $post;

    $PRINCIPAL = FuncionListadoMantenedores(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/audiencias/index.html")), $id_empresa, $arreglo_post);
    $PRINCIPAL = str_replace("{ID_EMPRESA}", $id_empresa, $PRINCIPAL);



    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "programas") {
    $id_empresa = $_SESSION["id_empresa"];
    $arreglo_post = $post;

    $PRINCIPAL = FuncionListadoMantenedores(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/programas/index.html")), $id_empresa, $arreglo_post);
    $PRINCIPAL = str_replace("{ID_EMPRESA}", $id_empresa, $PRINCIPAL);

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "audiencias_programas") {
    $id_empresa = $_SESSION["id_empresa"];
    $arreglo_post = $post;

    $PRINCIPAL = FuncionListadoMantenedores(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/audiencias_programas/index.html")), $id_empresa, $arreglo_post);
    $PRINCIPAL = str_replace("{ID_EMPRESA}", $id_empresa, $PRINCIPAL);

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "creacAudie") {
    $id_empresa = $_SESSION["id_empresa"];
    $campos = AUDIENCIAS_TraeCampos($id_empresa);
    $listado_campos = "";

//Creo audiencia
    AUDIENCIA_CreaAudiencia($id_empresa);
    $id_ultimo = AUDIENCIA_TraeUltimo($id_empresa);
    $id_audiencia = $id_ultimo[0]->ultimo_id;
    foreach ($campos as $camp) {
        if ($post[$camp->campo]) {

            //Lo guardo en la tabla de
            AUDIENCIA_InsertaCamposinAudiencia($id_empresa, $camp->campo, $id_audiencia);
        }
    }
    echo "
<script>
location.href='?sw=audiencias_creacion2&ia=" . Encodear3($id_audiencia) . "';
</script>";
}
else if ($seccion == "audiencias_creacion") {
    $id_empresa = $_SESSION["id_empresa"];

    $PRINCIPAL = CreacionAudiencias(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/audiencias/audiencias_creacion.html")), $id_empresa);
    $PRINCIPAL = str_replace("{ID_EMPRESA}", $id_empresa, $PRINCIPAL);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "GuardaCampoPorCampo") {
    $id_empresa = $_SESSION["id_empresa"];
    $id_audiencia = Decodear3($get["ia"]);

    $campos = AUDIENCIA_CamposPorAudiencia($id_empresa, $id_audiencia);
    $arreglo_post = $post;

    foreach ($campos as $camp) {

        $detalle_campos = AUDIENCIA_SelectDistincSinTotalPorValor($id_empresa, $camp->campo);

        foreach ($detalle_campos as $det) {
            $valor = ($det->valor);
            $valor_real = $det->valor;
            $valor = str_replace(" ", "_", $valor);
            if ($post[$valor]) {

                //aca inserto los valores
                AUDIENCIA_InsertaCamposValores($id_audiencia, $id_empresa, $camp->campo, $valor_real);
            }
        }
    }

//Subida en excel
    //cargamos el archivo al servidor con el mismo nombre
    //solo le agregue el sufijo tmp_ev_
    $archivo = $_FILES['excel']['name'];VerificaExtensionFilesAdmin($_FILES["excel"]);

    $tipo = $_FILES['excel']['type'];
    $destino = "tmp_ev_" . $archivo;
    if (copy($_FILES['excel']['tmp_name'], $destino)) {

    } else {
        $error_grave = "Error Al subir Archivo<br>";
    }

    if (file_exists("tmp_ev_" . $archivo)) {
        require_once 'clases/PHPExcel.php';
        require_once 'clases/PHPExcel/Reader/Excel2007.php';

        $objReader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $objReader->load("tmp_ev_" . $archivo);
        $objFecha = new PHPExcel_Shared_Date();
        $objPHPExcel->setActiveSheetIndex(0);
        $HojaActiva = $objPHPExcel->getActiveSheet();
        $total_filas = $HojaActiva->getHighestRow();
        $lastColumn = $HojaActiva->getHighestColumn();
        $total_columnas = PHPExcel_Cell::columnIndexFromString($lastColumn);

        $UltimaColumna = "A";
        $j = 0;

        $_DATOS_EXCEL = array();
//Obtengo los nombres de los campos
        for ($i = 0; $i <= $total_columnas; $i++) {
            $_campo[$i] = trim(($HojaActiva->getCell($UltimaColumna . "1")->getCalculatedValue()));
            $UltimaColumna++;
        }

//Obtengo datos de filas
        for ($fila = 2; $fila <= $total_filas; $fila++) {
            for ($columna = 0; $columna <= $total_columnas - 1; $columna++) {

                $_DATOS_EXCEL[$j][$columna + 1] = (trim($HojaActiva->getCellByColumnAndRow($columna, $fila)->getCalculatedValue()));
            }
            $j++;
        }
    } else {
        $error_grave = "Necesitas primero importar el archivo";
    }

    $total_errores = 0;
    $total_actualizar = 0;
    $total_insertar = 0;
    $usuario_inexistente = 0;
    $curso_inexistente = 0;
    $linea = 2;

    for ($l = 1; $l <= count($_DATOS_EXCEL); $l++) {
        $rut = $_DATOS_EXCEL[$l - 1][1];

//Inserto Relacion audiencia - usuario
        $rut = LimpiaRut($rut);
        AUDIENCIA_InsertaRelacionRutAudiencia($rut, $id_audiencia, $id_empresa);
    }

    echo "
<script>
location.href='?sw=audiencias_creacion3&ia=" . Encodear3($id_audiencia) . "';
</script>";
}
else if ($seccion == "audiencias_creacion2") {
    $id_empresa = $_SESSION["id_empresa"];
    $id_audiencia = Decodear3($get["ia"]);
    $PRINCIPAL = ListarCamposPorAudienciaOEmpresa(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/audiencias/audiencias_creacion2.html")), $id_empresa, $id_audiencia);
    $PRINCIPAL = str_replace("{ID_EMPRESA}", $id_empresa, $PRINCIPAL);
    $PRINCIPAL = str_replace("{ID_AUDIENCIA_ENCODEADA}", Encodear3($id_audiencia), $PRINCIPAL);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "finAudiencia") {
    $id_audiencia = Decodear3($get["ia"]);
    $id_empresa = $_SESSION["id_empresa"];

    $nombre = ($post["nombre"]);
    $descripcion = ($post["descripcion"]);

    AUDIENCIA_ActualizaDatos($id_audiencia, $nombre, $descripcion);

    $datos_audiencia = ArmaQueryaudiencias($id_audiencia, $id_empresa);
    $total_usuarios = $datos_audiencia[3];
    foreach ($total_usuarios as $usu) {
        AUDIENCIA_InsertaRelacionRutAudiencia($usu->rut, $id_audiencia, $id_empresa);
    }

    echo "
<script>
location.href='?sw=audiencias_lista&ia=" . Encodear3($id_audiencia) . "';
</script>";
}
else if ($seccion == "audiencias_creacion3") {
    $id_empresa = $_SESSION["id_empresa"];
    $id_audiencia = Decodear3($get["ia"]);

    $PRINCIPAL = MuestraFormfinalAudiencia(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/audiencias/audiencias_creacion3.html")), $id_empresa, $id_audiencia);
    $PRINCIPAL = str_replace("{ID_EMPRESA}", $id_empresa, $PRINCIPAL);
    $PRINCIPAL = str_replace("{ID_AUDIENCIA_ENCODEADA}", Encodear3($id_audiencia), $PRINCIPAL);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "duplicaAudiencia") {
    $id_empresa = $_SESSION["id_empresa"];
    $id_audiencia = Decodear3($get["ia"]);
//aca duplico la audiencia
    //Obtengo lso campos de la audiencia, y los inserto a la nueva audiencia
    $campos_por_audiencia = AUDIENCIA_CamposPorAudiencia($id_empresa, $id_audiencia);
    $datos_audiencia = AUDIENCIA_TraeDatosPorId($id_empresa, $id_audiencia);
    AUDIENCIA_CreaAudienciaDuplicada($id_empresa, $datos_audiencia[0]->nombre . "_duplicado", $datos_audiencia[0]->descripcion);
    $id_ultimo = AUDIENCIA_TraeUltimo($id_empresa);
    $id_audiencia_ultimo = $id_ultimo[0]->ultimo_id;
//CAMPOS
    foreach ($campos_por_audiencia as $campo) {
        AUDIENCIA_InsertaCamposinAudiencia($id_empresa, $campo->campo, $id_audiencia_ultimo);
    }

//VALORES POR CAMPO
    $valores = Audiencias_DatosValores_tbl_audiencia_valores_audiencia($id_audiencia, $id_empresa);
    foreach ($valores as $valor) {
        AUDIENCIA_InsertaCamposValores($id_audiencia_ultimo, $valor->id_empresa, $valor->campo, $valor->valor);
    }

//RUT POR AUDIENCIA
    $listado_usuarios = Audiencias_RutPorAudienciaEmpresa($id_audiencia, $id_empresa);
    foreach ($listado_usuarios as $usu) {
        AUDIENCIA_InsertaRelacionRutAudiencia($usu->rut, $id_audiencia_ultimo, $id_empresa);
    }

    echo "
<script>
location.href='?sw=audiencias_lista';
</script>";
}
else if ($seccion == "audiencias_lista") {
    $id_empresa = $_SESSION["id_empresa"];

    $PRINCIPAL = ListadoAudiencias(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/audiencias/audiencias_lista.html")), $id_empresa);
    $PRINCIPAL = str_replace("{ID_EMPRESA}", $id_empresa, $PRINCIPAL);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "descargarTotalMasInscritosPorPrograma") {
    $id_programa = $get["ip"];
    $id_empresa = $_SESSION["id_empresa"];

    $total_mallas_por_programa = TraeMallasDadoPrograma($id_empresa, $id_programa);
    $total_usuarios_por_programa = UsuariosPorEmpresa($id_empresa, $total_mallas_por_programa);

    require_once 'clases/PHPExcel.php';

    $datos_empresa = DatosEmpresa($id_empresa);
// Crea un nuevo objeto PHPExcel
    $objPHPExcel = new PHPExcel();

    $styleArray = array(
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => '000000'),
            'size' => 12,
            'name' => 'Calibri'
        ));

// Establecer propiedades
    $objPHPExcel->getProperties()
        ->setCreator("GO Partners")
        ->setLastModifiedBy("GO Partners")
        ->setTitle("Usuarios por Imparticion")
        ->setSubject("Usuarios por Imparticion")
        ->setDescription("Plantilla para carga masiva de usuarios")
        ->setKeywords("Excel Office 2007 openxml php")
        ->setCategory("Plantilla usuarios");

    $lastColumn = $objPHPExcel->getActiveSheet()->getHighestColumn();
    $lastColumn2 = $objPHPExcel->getActiveSheet()->getHighestColumn();

    $camposMostrar = jListarCamposEmpresaSubidaUsuarios(2, $idEmpresa);
    $nombreCampos = jListarCamposEmpresaSubidaUsuarios(3, $idEmpresa);

    $selected = '';
    $i = 0;

    foreach ($camposMostrar as $value) {
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn . "1", $value);
        $objPHPExcel->getActiveSheet()->getStyle($lastColumn . "1")->applyFromArray($styleArray);
        $lastColumn++;
        $i++;
    }
//vuelta adicional para id_malla

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A1", "Rut");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B1", "Nombre");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C1", "Cargo");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D1", "Email");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E1", $datos_empresa[0]->campo1);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F1", $datos_empresa[0]->campo2);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G1", $datos_empresa[0]->campo3);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H1", "id_malla");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I1", "nombre_malla");

    $i = 1;


    $id_malla = "";
    foreach ($total_usuarios_por_programa as $unico) {
        $i++;
        $lastColumn2 = "A";
        $A = 0;
        $id_malla = "";

        foreach ($nombreCampos as $value) {
            if ($A == 0) {
                $A = 1;
                $lastColumn2 = "A";
            } else {
                $lastColumn2++;
            }
        }

//AGREGA MALLA
        $lastColumn2++;
        $id_malla = TraeMallaUsuarioSubida($unico->rut, $idEmpresa);


        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $i, ($unico->rut));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B" . $i, ($unico->nombre_completo));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C" . $i, ($unico->cargo));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D" . $i, ($unico->email));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E" . $i, ($unico->c1_colaborador));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F" . $i, ($unico->c2_colaborador));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G" . $i, ($unico->c3_colaborador));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H" . $i, ($unico->id_malla));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I" . $i, ($unico->nombre_malla));
    }

    $sheet = $objPHPExcel->getActiveSheet();
    $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(true);

/** @var PHPExcel_Cell $cell */
    foreach ($cellIterator as $cell) {
        $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
    }

// Renombrar Hoja
    $objPHPExcel->getActiveSheet()->setTitle('Usuarios');

// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
    $objPHPExcel->setActiveSheetIndex(0);

// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
    $fechahoy = date("Y-m-d") . "_" . date("H:i:s");
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $id_programa . '_inscritos_' . $fechahoy . '.xlsx"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
}
else if ($seccion == "descargarInscritosPorPrograma") {
    $id_programa = $get["ip"];
    $id_empresa = $_SESSION["id_empresa"];

    $total_mallas_por_programa = TraeMallasDadoPrograma($id_empresa, $id_programa);
    $total_usuarios_por_programa = totalUsuariosPorProgramaConMallas($id_empresa, $total_mallas_por_programa);

    require_once 'clases/PHPExcel.php';

    $datos_empresa = DatosEmpresa($id_empresa);
// Crea un nuevo objeto PHPExcel
    $objPHPExcel = new PHPExcel();

    $styleArray = array(
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => '000000'),
            'size' => 12,
            'name' => 'Calibri'
        ));

// Establecer propiedades
    $objPHPExcel->getProperties()
        ->setCreator("GO Partners")
        ->setLastModifiedBy("GO Partners")
        ->setTitle("Usuarios por Imparticion")
        ->setSubject("Usuarios por Imparticion")
        ->setDescription("Plantilla para carga masiva de usuarios")
        ->setKeywords("Excel Office 2007 openxml php")
        ->setCategory("Plantilla usuarios");

    $lastColumn = $objPHPExcel->getActiveSheet()->getHighestColumn();
    $lastColumn2 = $objPHPExcel->getActiveSheet()->getHighestColumn();

    $camposMostrar = jListarCamposEmpresaSubidaUsuarios(2, $idEmpresa);
    $nombreCampos = jListarCamposEmpresaSubidaUsuarios(3, $idEmpresa);

    $selected = '';
    $i = 0;

    foreach ($camposMostrar as $value) {
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn . "1", $value);
        $objPHPExcel->getActiveSheet()->getStyle($lastColumn . "1")->applyFromArray($styleArray);
        $lastColumn++;
        $i++;
    }
//vuelta adicional para id_malla

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A1", "Rut");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B1", "Id_Malla");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C1", "Opcional");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D1", "Fecha Inscripcion");

    $id_malla = "";
    foreach ($total_usuarios_por_programa as $unico) {
        $i++;
        $lastColumn2 = "A";
        $A = 0;
        $id_malla = "";

        foreach ($nombreCampos as $value) {
            if ($A == 0) {
                $A = 1;
                $lastColumn2 = "A";
            } else {
                $lastColumn2++;
            }
        }

//AGREGA MALLA
        $lastColumn2++;
        $id_malla = TraeMallaUsuarioSubida($unico->rut, $idEmpresa);


        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $i, ($unico->rut));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B" . $i, ($unico->id_malla));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C" . $i, ($unico->opcional));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D" . $i, ($unico->fecha));
    }

    $sheet = $objPHPExcel->getActiveSheet();
    $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(true);

/** @var PHPExcel_Cell $cell */
    foreach ($cellIterator as $cell) {
        $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
    }

// Renombrar Hoja
    $objPHPExcel->getActiveSheet()->setTitle('Usuarios');

// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
    $objPHPExcel->setActiveSheetIndex(0);

// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
    $fechahoy = date("Y-m-d") . "_" . date("H:i:s");
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $id_programa . '_inscritos_' . $fechahoy . '.xlsx"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	//ob_end_clean();
	ob_end_clean();
    $objWriter->save('php://output');
    exit;
}
else if ($seccion == "descargarInscritosPorProgramaI") {
    $id_programa = $get["ip"];
    $id_empresa = $_SESSION["id_empresa"];

    $total_mallas_por_programa = TraeMallasDadoPrograma($id_empresa, $id_programa);
    $total_usuarios_por_programa = totalUsuariosPorInscripcionUsuarioProgramaConMallas($id_empresa, $total_mallas_por_programa);
        require_once 'clases/PHPExcel.php';

    $datos_empresa = DatosEmpresa($id_empresa);
// Crea un nuevo objeto PHPExcel
    $objPHPExcel = new PHPExcel();

    $styleArray = array(
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => '000000'),
            'size' => 12,
            'name' => 'Calibri'
        ));

// Establecer propiedades
    $objPHPExcel->getProperties()
        ->setCreator("GO Partners")
        ->setLastModifiedBy("GO Partners")
        ->setTitle("Usuarios por Imparticion")
        ->setSubject("Usuarios por Imparticion")
        ->setDescription("Plantilla para carga masiva de usuarios")
        ->setKeywords("Excel Office 2007 openxml php")
        ->setCategory("Plantilla usuarios");

    $lastColumn = $objPHPExcel->getActiveSheet()->getHighestColumn();
    $lastColumn2 = $objPHPExcel->getActiveSheet()->getHighestColumn();

    $camposMostrar = jListarCamposEmpresaSubidaUsuarios(2, $idEmpresa);
    $nombreCampos = jListarCamposEmpresaSubidaUsuarios(3, $idEmpresa);

    $selected = '';
    $i = 0;

    foreach ($camposMostrar as $value) {
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn . "1", $value);
        $objPHPExcel->getActiveSheet()->getStyle($lastColumn . "1")->applyFromArray($styleArray);
        $lastColumn++;
        $i++;
    }
//vuelta adicional para id_malla

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A1", "Rut");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B1", "Id_Inscripcion");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C1", "Id_Curso");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D1", "Id_Malla");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E1", "Id_Programa");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F1", "Opcional");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G1", "Fecha Inscripcion");

    $i = 1;
	
    $id_malla = "";
    foreach ($total_usuarios_por_programa as $unico) {
        $i++;
        $lastColumn2 = "A";
        $A = 0;
        $id_malla = "";

        foreach ($nombreCampos as $value) {
            if ($A == 0) {
                $A = 1;
                $lastColumn2 = "A";
            } else {
                $lastColumn2++;
            }
        }

//AGREGA MALLA
        $lastColumn2++;
        $id_malla = TraeMallaUsuarioSubida($unico->rut, $idEmpresa);


        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $i, ($unico->rut));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B" . $i, ($unico->id_inscripcion));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C" . $i, ($unico->id_curso));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D" . $i, ($unico->id_malla));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E" . $i, $id_programa);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F" . $i, ($unico->opcional));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G" . $i, ($unico->fecha));

    }


    $sheet = $objPHPExcel->getActiveSheet();
    $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(true);

/** @var PHPExcel_Cell $cell */
    foreach ($cellIterator as $cell) {
        $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
    }

// Renombrar Hoja
    $objPHPExcel->getActiveSheet()->setTitle('Inscripciones');

// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
    $objPHPExcel->setActiveSheetIndex(0);

// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
    $fechahoy = date("Y-m-d") . "_" . date("H:i:s");
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $id_programa . '_inscripciones_' . $fechahoy . '.xlsx"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
}
else if ($seccion == "Download_Respuestas_EvalMedicion") {
    $idMed = $get["idMed"];
    $DatosMed = DatosMedicionAdmin($idMed);
    $nombre_medicion = $DatosMed[0]->nombre;
    $tipo_individual = $DatosMed[0]->tipo_medicion;
    
    $total_usuarios_por_medicion = totalUsuariosPorMedicion($id_empresa, $id_medicion);
    require_once 'clases/PHPExcel.php';

    $datos_empresa = DatosEmpresa($id_empresa);
    // Crea un nuevo objeto PHPExcel
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()
        ->setCreator("GO Partners")
        ->setLastModifiedBy("GO Partners")
        ->setTitle("Respuestas por Medición")
        ->setSubject("Respuestas por Medición")
        ->setDescription("Respuestas por Medición")
        ->setKeywords("Excel Office 2007 openxml php")
        ->setCategory("Respuestas por Medición");

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A1", "Resultados por Medición $nombremedicion (IdMed: $idMed) ");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B1", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B1", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C1", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D1", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E1", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A2", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B2", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C2", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D2", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E2", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G4", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G5", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G6", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A6", "RUT");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B6", "NOMBRE");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C6", "CARGO");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D6", "EMAIL");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E6", "C1");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F6", "C2");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G6", "C3");

    if ($tipo_individual == "INDIVIDUAL") {
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H6", "RUT_JEFE");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I6", "NOMBRE_JEFE");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J6", "CARGO_JEFE");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K6", "FECHA_MEDICION");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L6", "FECHA_RESPUESTA");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("M6", "COMENTARIO");
    } else {

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H6", "");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I6", "");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J6", "FECHA_CARGA");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K6", "GRUPO");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L6", "FECHA_RESPUESTAS");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("M6", "COMENTARIO");
    }

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L7", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L8", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L9", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L10", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L10", "");

    $i = 6;



    $cuenta_arrayfecha++;
    $i++;
        

        $TodasPreguntas = BuscaPreguntaDadaMedicion($idMed);
        $letra = "M";foreach ($TodasPreguntas as $pregunta) {
            $letra++;
            $questionid++;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra . "5", "Preg_" . ($questionid));
        }

        $letra = "M";foreach ($TodasPreguntas as $pregunta) {
            $letra++;
            
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra . "6", ($pregunta->pregunta));
        }

        $letra = "M";foreach ($TodasPreguntas as $pregunta) {
            $letra++;
            
            $questionid++;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra . "4", ($pregunta->tipo));
        }


        $GroupRuts = BuscaUsuariosRespondieronMedSinUsuario($idMed);

             foreach ($GroupRuts as $user_unico) {
        $Datos_usu=TraeDatosUsuario($user_unico->rut);
        $Datos_jef=TraeDatosUsuario($Datos_usu[0]->jefe);

            if ($Datos_usu->nombre_completo == "") {$user_unico->nombre_completo = "USUARIO INACTIVO";}

        if($tipo_individual == "LIBRE_CONFIDENCIAL"){
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $i, "*");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B" . $i, "*");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C" . $i, "*");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D" . $i, "*");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E" . $i, "*");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F" . $i, "*");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G" . $i, "*");
        }   else {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $i, ($user_unico->rut));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B" . $i, ($Datos_usu[0]->nombre_completo));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C" . $i, ($Datos_usu[0]->cargo));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D" . $i, ($Datos_usu[0]->email));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E" . $i, ($Datos_usu[0]->gerencia));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F" . $i, ($Datos_usu[0]->gerenciaR2));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G" . $i, ($Datos_usu[0]->gerenciaR3));
        }


    if ($tipo_individual == "INDIVIDUAL") {

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H" . $i, ($Datos_jef[0]->rut));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I" . $i, ($Datos_jef[0]->nombre_completo));
            } else {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H" . $i, "");
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I" . $i, "");

    }

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J" . $i, ($user_unico->fecha_carga));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K" . $i, ($user_unico->grupo));

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L" . $i, ($user_unico->fecha));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("M" . $i, ($user_unico->comentario));

            $letra = "M";

            foreach ($TodasPreguntas as $pregunta) {
                
                 if ($tipo_individual == "INDIVIDUAL") {
  $Respuestas_UsuariosIDPreg = BuscaRespuestasUsuarioMedSinFecha($user_unico->rut, $pregunta->id_pregunta, $idMed, $array_fecha);
                    } else {
       $Respuestas_UsuariosIDPreg = BuscaRespuestasUsuarioMedSinFechaRUT($user_unico->rut, $pregunta->id_pregunta, $idMed, $array_fecha);

                    }

                $letra++;
                
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra . $i, ($Respuestas_UsuariosIDPreg[0]->respuesta));
            }
            $i++;
        }




    $sheet = $objPHPExcel->getActiveSheet();
    $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(true);

/** @var PHPExcel_Cell $cell */
    foreach ($cellIterator as $cell) {
        $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
    }

// Renombrar Hoja
    $objPHPExcel->getActiveSheet()->setTitle('Usuarios');

// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
    $objPHPExcel->setActiveSheetIndex(0);

// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
    $fechahoy = date("Y-m-d") . "_" . date("H:i:s");
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Medicion_' . $id_medicion . '_inscritos_' . $fechahoy . '.xlsx"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
}
else if ($seccion == "descargarInscritosPorMedicion") {
    $id_medicion = $get["id"];
    $id_empresa = $_SESSION["id_empresa"];

    $total_usuarios_por_medicion = totalUsuariosPorMedicion($id_empresa, $id_medicion);
    require_once 'clases/PHPExcel.php';

    $datos_empresa = DatosEmpresa($id_empresa);
// Crea un nuevo objeto PHPExcel
    $objPHPExcel = new PHPExcel();

    $styleArray = array(
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => '000000'),
            'size' => 12,
            'name' => 'Calibri'
        ));

// Establecer propiedades
    $objPHPExcel->getProperties()
        ->setCreator("GO Partners")
        ->setLastModifiedBy("GO Partners")
        ->setTitle("Usuarios por Medición")
        ->setSubject("Usuarios por Medición")
        ->setDescription("Plantilla para carga masiva de usuarios")
        ->setKeywords("Excel Office 2007 openxml php")
        ->setCategory("Plantilla usuarios");

    $lastColumn = $objPHPExcel->getActiveSheet()->getHighestColumn();
    $lastColumn2 = $objPHPExcel->getActiveSheet()->getHighestColumn();

    $camposMostrar = jListarCamposEmpresaSubidaUsuarios(2, $idEmpresa);
    $nombreCampos = jListarCamposEmpresaSubidaUsuarios(3, $idEmpresa);

    $selected = '';
    $i = 0;

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A1", "ID Medicion");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B1", "Nombre Medicion");

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C1", "Rut Colaborador");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D1", "Nombre Colaborador");

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E1", "Rut Jefe");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F1", "Nombre Jefe");

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G1", "Fecha_Carga");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H1", "Grupo");

    $i = 1;
    $id_malla = "";

    foreach ($total_usuarios_por_medicion as $unico) {
        $i++;
        $lastColumn2 = "A";
        $A = 0;
        $id_malla = "";

        foreach ($nombreCampos as $value) {
            if ($A == 0) {
                $A = 1;
                $lastColumn2 = "A";
            } else {
                $lastColumn2++;
            }
        }

        if ($unico->nombre_completo_colaborador == "") {
            $unico->nombre_completo_colaborador = "USUARIO INACTIVO";
        }
//AGREGA MALLA
        $lastColumn2++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $i, ($id_medicion));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B" . $i, ($unico->Medicion));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C" . $i, ($unico->rut));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D" . $i, ($unico->nombre_completo_colaborador));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E" . $i, ($unico->rut_completo_jefe));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F" . $i, ($unico->nombre_completo_jefe));
    }

    $sheet = $objPHPExcel->getActiveSheet();
    $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(true);

/** @var PHPExcel_Cell $cell */
    foreach ($cellIterator as $cell) {
        $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
    }

// Renombrar Hoja
    $objPHPExcel->getActiveSheet()->setTitle('Usuarios');

// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
    $objPHPExcel->setActiveSheetIndex(0);

// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
    $fechahoy = date("Y-m-d") . "_" . date("H:i:s");
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Medicion_' . $id_medicion . '_inscritos_' . $fechahoy . '.xlsx"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
}
else if ($seccion == "descargarTotUsuariosPorEmpresa") {
    $id_empresa = $_SESSION["id_empresa"];
    $total_usuarios_por_empresa = DatosTablaUsuarioPorEmpresa2($id_empresa);

    require_once 'clases/PHPExcel.php';

    $datos_empresa = DatosEmpresa($id_empresa);
// Crea un nuevo objeto PHPExcel
    $objPHPExcel = new PHPExcel();

    $styleArray = array(
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => '000000'),
            'size' => 12,
            'name' => 'Calibri'
        ));

// Establecer propiedades
    $objPHPExcel->getProperties()
        ->setCreator("GO Partners")
        ->setLastModifiedBy("GO Partners")
        ->setTitle("Usuarios")
        ->setSubject("Usuarios")
        ->setDescription("Plantilla para carga masiva de usuarios")
        ->setKeywords("Excel Office 2007 openxml php")
        ->setCategory("Plantilla usuarios");

    $lastColumn = $objPHPExcel->getActiveSheet()->getHighestColumn();
    $lastColumn2 = $objPHPExcel->getActiveSheet()->getHighestColumn();

    $camposMostrar = jListarCamposEmpresaSubidaUsuarios(2, $id_empresa);
    $nombreCampos = jListarCamposEmpresaSubidaUsuarios(3, $id_empresa);

    $selected = '';
    $i = 0;

    foreach ($camposMostrar as $value) {
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn . "1", $value);
        $objPHPExcel->getActiveSheet()->getStyle($lastColumn . "1")->applyFromArray($styleArray);
        $lastColumn++;
        $i++;
    }

    $i = 1;

    $campos_subida = listarEmpresaSubidaUsuarios($id_empresa);
    foreach ($total_usuarios_por_empresa as $unico) {
        $i++;
        $lastColumn2 = "A";
        $A = 0;
        $id_malla = "";

        foreach ($nombreCampos as $value) {
            if ($A == 0) {
                $A = 1;
                $lastColumn2 = "A";
            } else {
                $lastColumn2++;
            }
        }

//AGREGA MALLA
        $lastColumn2++;

        $letra = "A";
        foreach ($campos_subida as $camp) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra . $i, ($unico->{$camp->campo}));
            $letra++;
        }
    }

    $sheet = $objPHPExcel->getActiveSheet();
    $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(true);

/** @var PHPExcel_Cell $cell */
    foreach ($cellIterator as $cell) {
        $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
    }

// Renombrar Hoja
    $objPHPExcel->getActiveSheet()->setTitle('Usuarios');

// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
    $objPHPExcel->setActiveSheetIndex(0);

// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
    $fechahoy = date("Y-m-d") . "_" . date("H:i:s");
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Base_Colaboradores_' . $fechahoy . '.xlsx"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
}
else if ($seccion == "descargarInscritosPorSesion") {
    $codigo_inscripcion = Decodear3($get["ci"]);
    $id_sesion = Decodear3($get["i"]);

    $id_empresa = $_SESSION["id_empresa"];

    $total_usuarios_por_sesion = TraigoRegistrosPorSesionDeCheckin($codigo_inscripcion, $id_sesion, $id_empresa);

    require_once 'clases/PHPExcel.php';

    $datos_empresa = DatosEmpresa($id_empresa);
// Crea un nuevo objeto PHPExcel
    $objPHPExcel = new PHPExcel();

    $styleArray = array(
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => '000000'),
            'size' => 12,
            'name' => 'Calibri'
        ));

// Establecer propiedades
    $objPHPExcel->getProperties()
        ->setCreator("GO Partners")
        ->setLastModifiedBy("GO Partners")
        ->setTitle("Usuarios por Imparticion")
        ->setSubject("Usuarios por Imparticion")
        ->setDescription("Plantilla para carga masiva de usuarios")
        ->setKeywords("Excel Office 2007 openxml php")
        ->setCategory("Plantilla usuarios");

    $lastColumn = $objPHPExcel->getActiveSheet()->getHighestColumn();
    $lastColumn2 = $objPHPExcel->getActiveSheet()->getHighestColumn();

    $camposMostrar = jListarCamposEmpresaSubidaUsuarios(2, $idEmpresa);
    $nombreCampos = jListarCamposEmpresaSubidaUsuarios(3, $idEmpresa);

    $selected = '';
    $i = 0;

    foreach ($camposMostrar as $value) {
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn . "1", $value);
        $objPHPExcel->getActiveSheet()->getStyle($lastColumn . "1")->applyFromArray($styleArray);
        $lastColumn++;
        $i++;
    }
//vuelta adicional para id_malla

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A1", "Rut");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B1", "Nombre");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C1", "Cargo");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D1", "Email");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E1", $datos_empresa[0]->campo1);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F1", $datos_empresa[0]->campo2);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G1", $datos_empresa[0]->campo3);

    $i = 1;

    $id_malla = "";
    foreach ($total_usuarios_por_sesion as $unico) {
        $i++;
        $lastColumn2 = "A";
        $A = 0;
        $id_malla = "";

        foreach ($nombreCampos as $value) {
            if ($A == 0) {
                $A = 1;
                $lastColumn2 = "A";
            } else {
                $lastColumn2++;
            }
        }

//AGREGA MALLA
        $lastColumn2++;
        $id_malla = TraeMallaUsuarioSubida($unico->rut, $idEmpresa);


        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $i, ($unico->rut));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B" . $i, ($unico->nombre_completo));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C" . $i, ($unico->cargo));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D" . $i, ($unico->email));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E" . $i, ($unico->c1_colaborador));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F" . $i, ($unico->c2_colaborador));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G" . $i, ($unico->c3_colaborador));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H" . $i, ($unico->id_malla));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I" . $i, ($unico->nombre_malla));
    }

    $sheet = $objPHPExcel->getActiveSheet();
    $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(true);

/** @var PHPExcel_Cell $cell */
    foreach ($cellIterator as $cell) {
        $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
    }

// Renombrar Hoja
    $objPHPExcel->getActiveSheet()->setTitle('Usuarios');

// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
    $objPHPExcel->setActiveSheetIndex(0);

// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
    $fechahoy = date("Y-m-d") . "_" . date("H:i:s");
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $id_programa . '_inscritos_' . $fechahoy . '.xlsx"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
}
else if ($seccion == "GeneraExcelUsuarioPorInscripcionCurso") {
    require_once 'clases/PHPExcel.php';

    $id_imparticion = Decodear3($get['im']);
    $id_empresa = Decodear3($get['iem']);
    $datos_empresa = DatosEmpresa($id_empresa);
// Crea un nuevo objeto PHPExcel
    $objPHPExcel = new PHPExcel();

    $styleArray = array(
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => '000000'),
            'size' => 12,
            'name' => 'Calibri'
        ));

// Establecer propiedades
    $objPHPExcel->getProperties()
        ->setCreator("GO Partners")
        ->setLastModifiedBy("GO Partners")
        ->setTitle("Usuarios por Imparticion")
        ->setSubject("Usuarios por Imparticion")
        ->setDescription("Plantilla para carga masiva de usuarios")
        ->setKeywords("Excel Office 2007 openxml php")
        ->setCategory("Plantilla usuarios");

    $lastColumn = $objPHPExcel->getActiveSheet()->getHighestColumn();
    $lastColumn2 = $objPHPExcel->getActiveSheet()->getHighestColumn();

    $camposMostrar = jListarCamposEmpresaSubidaUsuarios(2, $idEmpresa);
    $nombreCampos = jListarCamposEmpresaSubidaUsuarios(3, $idEmpresa);

    $selected = '';
    $i = 0;

    foreach ($camposMostrar as $value) {
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn . "1", $value);
        $objPHPExcel->getActiveSheet()->getStyle($lastColumn . "1")->applyFromArray($styleArray);
        $lastColumn++;
        $i++;
    }
//vuelta adicional para id_malla

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A1", "rut");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B1", "Nombre");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C1", "Cargo");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D1", $datos_empresa[0]->campo1);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E1", $datos_empresa[0]->campo2);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F1", $datos_empresa[0]->campo3);

    $usuarios = IMPARTICION_UsuariosPorInscripcionConDatosUsuario($id_imparticion, $id_empresa, $datos_empresa);
    $i = 1;

    $id_malla = "";
    foreach ($usuarios as $unico) {
        $i++;
        $lastColumn2 = "A";
        $A = 0;
        $id_malla = "";

        foreach ($nombreCampos as $value) {
            if ($A == 0) {
                $A = 1;
                $lastColumn2 = "A";
            } else {
                $lastColumn2++;
            }
        }

//AGREGA MALLA
        $lastColumn2++;
        $id_malla = TraeMallaUsuarioSubida($unico->rut, $idEmpresa);


        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $i, ($unico->rut));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B" . $i, ($unico->nombre_completo));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C" . $i, ($unico->cargoUsuario));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D" . $i, ($unico->c1));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E" . $i, ($unico->c2));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F" . $i, ($unico->c3));
    }

    $sheet = $objPHPExcel->getActiveSheet();
    $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(true);

/** @var PHPExcel_Cell $cell */
    foreach ($cellIterator as $cell) {
        $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
    }

// Renombrar Hoja
    $objPHPExcel->getActiveSheet()->setTitle('Usuarios');

// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
    $objPHPExcel->setActiveSheetIndex(0);

// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Base_Usuarios.xlsx"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
}
else if ($seccion == "GeneraExcelPostulantesPorInscripcionCurso") {
    require_once 'clases/PHPExcel.php';

    $id_imparticion = Decodear3($get['im']);
    $id_empresa = Decodear3($get['iem']);
    $datos_empresa = DatosEmpresa($id_empresa);
// Crea un nuevo objeto PHPExcel
    $objPHPExcel = new PHPExcel();

    $styleArray = array(
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => '000000'),
            'size' => 12,
            'name' => 'Calibri'
        ));

// Establecer propiedades
    $objPHPExcel->getProperties()
        ->setCreator("GO Partners")
        ->setLastModifiedBy("GO Partners")
        ->setTitle("Postulantes por Imparticion")
        ->setSubject("Postulantes por Imparticion")
        ->setDescription("Plantilla para carga masiva de postulantes")
        ->setKeywords("Excel Office 2007 openxml php")
        ->setCategory("Plantilla postulantes");

    $lastColumn = $objPHPExcel->getActiveSheet()->getHighestColumn();
    $lastColumn2 = $objPHPExcel->getActiveSheet()->getHighestColumn();

    $camposMostrar = jListarCamposEmpresaSubidaUsuarios(2, $idEmpresa);
    $nombreCampos = jListarCamposEmpresaSubidaUsuarios(3, $idEmpresa);

    $selected = '';
    $i = 0;

    foreach ($camposMostrar as $value) {
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($lastColumn . "1", $value);
        $objPHPExcel->getActiveSheet()->getStyle($lastColumn . "1")->applyFromArray($styleArray);
        $lastColumn++;
        $i++;
    }
//vuelta adicional para id_malla

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A1", "rut");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B1", "Nombre");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C1", "Cargo");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D1", $datos_empresa[0]->campo1);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E1", $datos_empresa[0]->campo2);
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F1", $datos_empresa[0]->campo3);

    $usuarios = IMPARTICION_PostulantesPorInscripcionConDatosUsuario($id_imparticion, $id_empresa, $datos_empresa);

    $i = 1;

    $id_malla = "";
    foreach ($usuarios as $unico) {
        $i++;
        $lastColumn2 = "A";
        $A = 0;
        $id_malla = "";

        foreach ($nombreCampos as $value) {
            if ($A == 0) {
                $A = 1;
                $lastColumn2 = "A";
            } else {
                $lastColumn2++;
            }
        }

//AGREGA MALLA
        $lastColumn2++;
        $id_malla = TraeMallaUsuarioSubida($unico->rut, $idEmpresa);


        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $i, ($unico->rut));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B" . $i, ($unico->nombre_completo));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C" . $i, ($unico->cargo));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D" . $i, ($unico->c1));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E" . $i, ($unico->c2));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F" . $i, ($unico->c3));
    }

    $sheet = $objPHPExcel->getActiveSheet();
    $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(true);

/** @var PHPExcel_Cell $cell */
    foreach ($cellIterator as $cell) {
        $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
    }

// Renombrar Hoja
    $objPHPExcel->getActiveSheet()->setTitle('Usuarios');

// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
    $objPHPExcel->setActiveSheetIndex(0);

// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Base_Postulantes.xlsx"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
}
else if ($seccion == "excAudie") {
    $id_empresa = $_SESSION["id_empresa"];
    $id_audiencia = Decodear3($get["ia"]);
    $fechahoy = date("Y-m-d") . " " . date("H:i:s");
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=" . $fechahoy . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    $campos = AUDIENCIA_ValoresDatoAudienciaEmpresaSoloCampo($id_empresa, $id_audiencia);
    $total_campos = 1;
    foreach ($campos as $camp) {
        if ($total_campos > 1) {
            $sql2 .= " and ";
        }
        $sql2 .= "(";
//por cada campo, traigo los valores de esta audiencia
        $valores_por_campo = AUDIENCIA_ValoresDatoAudienciaEmpresaSoloCampoSoloValor($id_empresa, $id_audiencia, $camp->campo);
        $i = 1;
        foreach ($valores_por_campo as $dat) {
            if ($i == 1) {
                $or = "";
            } else {
                $or = " or ";
            }
            $sql2 .= " " . $or . "" . $dat->campo . "='" . $dat->valor . "'";
            $i++;
        }
        $sql2 .= ")";
        $total_campos++;
    }
    $datos_finales = AUDIENCIA_QueryDinamicaListadoUsuarios($id_empresa, $id_audiencia, $sql2);
    ?>
<table>
<tr>
<td>Rut</td>
<td>Nombre</td>
<td>Cargo</td>
<td>Familia Cargo</td>
<td>Tipo Cargo</td>
<td>Gerencia</td>
<td>Fondo R2</td>
<td>Dependencia R3</td>
<td>SubDep</td>
<td>Uni_Org</td>
<td>Cenco</td>
<td>Regional</td>
<td>Genero</td>
<td>Estado Civil</td>
<td>Nombre Jefe</td>
<td>Empresa</td>

</tr>

<?php
foreach ($datos_finales as $usu) {
        ?>
<tr>
<td><?php echo $usu->rut_completo; ?></td>
<td><?php echo $usu->nombre; ?></td>
<td><?php echo $usu->cargo; ?></td>
<td><?php echo $usu->familia_cargo; ?></td>
<td><?php echo $usu->tipo_cargo; ?></td>
<td><?php echo $usu->gerencia; ?></td>
<td><?php echo $usu->gerenciaR2; ?></td>
<td><?php echo $usu->gerenciaR3; ?></td>
<td><?php echo $usu->dependencia; ?></td>
<td><?php echo $usu->unidad_negocio; ?></td>
<td><?php echo $usu->centro_costo; ?></td>
<td><?php echo $usu->regional; ?></td>
<td><?php echo $usu->genero; ?></td>
<td><?php echo $usu->estadocivil; ?></td>
<td><?php echo $usu->nombre_jefatura; ?></td>
<td><?php echo $usu->empresa_holding; ?></td>
</tr>
<?php

//Aca traigo listado de usuarios con excel
    }

    $listado_usuarios_excel = Audiencias_RutPorAudienciaEmpresa($id_audiencia, $id_empresa);
    foreach ($listado_usuarios_excel as $usu_exce) {
        ?>

<tr>
<td><?php echo $usu_exce->rut; ?></td>
<td><?php echo $usu_exce->nombre; ?></td>
<td><?php echo $usu_exce->cargo; ?></td>
<td><?php echo $usu_exce->familia_cargo; ?></td>
<td><?php echo $usu_exce->tipo_cargo; ?></td>
<td><?php echo $usu_exce->gerencia; ?></td>
<td><?php echo $usu_exce->gerenciaR2; ?></td>
<td><?php echo $usu_exce->gerenciaR3; ?></td>
<td><?php echo $usu_exce->dependencia; ?></td>
<td><?php echo $usu_exce->unidad_negocio; ?></td>
<td><?php echo $usu_exce->centro_costo; ?></td>
<td><?php echo $usu_exce->regional; ?></td>
<td><?php echo $usu_exce->genero; ?></td>
<td><?php echo $usu_exce->estadocivil; ?></td>
<td><?php echo $usu_exce->nombre_jefatura; ?></td>
<td><?php echo $usu_exce->empresa_holding; ?></td>
</tr>

<?php
}
}
else if ($seccion == "SaveMantenedorCampos") {
    $tipo = $post["tipo"];
    $tipo_objeto = $post["tipo_objeto"];
    $id_empresa = $_SESSION["id_empresa"];
//Traigo los datos de este tipo
    $datos_tipo = MANTENEDOR_TraeTipoEmpresa($tipo, $id_empresa);
    $nombre_tabla = $datos_tipo[0]->tabla;
    $campos = MANTENEDOR_TraeCamporPorTipoEmpresa($id_empresa, $tipo);
    $campos_tabla = "";
    $valores = "";
    foreach ($campos as $camp) {
        $campos_tabla .= $camp->campo . ", ";
        if ($camp->tipo_input == "file") {
            $tamano_archivo = $_FILES[$camp->campo]['size'];
            $tipo_archivo = $_FILES[$camp->campo]['type'];
            $archivo_archivo = $_FILES[$camp->campo]['name'];
            VerificaExtensionFilesAdmin($_FILES[$camp->campo]);
            $arreglo_archivo = explode(".", $archivo_archivo);
            $extension_archivo = $arreglo_archivo[1];
            $rutaarchivo = "../front/img";
            $prefijo = substr(md5(uniqid(rand())), 0, 6);
            if ($arreglo_archivo != "") {
                $nombre_archivo = $id_empresa . "_" . $prefijo;
                $datos_subida_archivo = SubirArchivosGenericos($_FILES, $extension_archivo, $nombre_archivo, $rutaarchivo, 1, $camp->campo);
                $nombre_archivo = $datos_subida_archivo[1];
            }

            $valores .= "'" . ($nombre_archivo) . "', ";
        } else {
            $valores .= "'" . ($post[$camp->campo]) . "', ";
        }
    }
    if ($tipo_objeto) {
        $id_curso = $post["id_curso"];
//cuento el total de objetos por este curso, y por la empresa
        $total_objetos = TotalObjetosEvaluacionesDadoCursoYEmpresa($id_curso, $id_empresa);
        if ($tipo_objeto == 7 and $tipo == "objeto") {
            $ruta_curso = "../front/objetos/" . $id_curso;
            if (!is_dir($ruta_curso)) {
//Directory does not exist, so lets create it.
                mkdir($ruta_curso, 0755);
            }
            $ruta_objeto = "../front/objetos/" . $id_curso . "/" . $id_curso . "_" . (count($total_objetos) + 1);
            if (!is_dir($ruta_objeto)) {
//Directory does not exist, so lets create it.
                mkdir($ruta_objeto, 0755);
            }

            $tamano_archivo = $_FILES["video"]['size'];
            $tipo_archivo = $_FILES["video"]['type'];
            $archivo_archivo = $_FILES["video"]['name'];
            VerificaExtensionFilesAdmin($_FILES["video"]);
            $arreglo_archivo = explode(".", $archivo_archivo);
            $extension_archivo = $arreglo_archivo[1];
            $rutaarchivo = $ruta_objeto;
            $rutaarchivo_para_guardar = "objetos/" . $id_curso . "/" . $id_curso . "_" . (count($total_objetos) + 1);

            if ($arreglo_archivo != "") {
                $nombre_archivo = $id_curso . "_" . (count($total_objetos) + 1);
                $datos_subida_archivo = SubirArchivosGenericos($_FILES, $extension_archivo, $nombre_archivo, $rutaarchivo, 1, "video");
                $nombre_archivo = $datos_subida_archivo[1];
            }

            $duracion_video = $post["duracion_video"];
            $campos_tabla .= "tipo_objeto, extension_objeto, id, archivo, url, url_objeto, orden, duracion_video, ";
            $valores .= "'3', '7', '" . $id_curso . "_" . (count($total_objetos) + 1) . "', '$rutaarchivo_para_guardar/$nombre_archivo', '$rutaarchivo_para_guardar/$nombre_archivo', '$rutaarchivo_para_guardar/$nombre_archivo', '" . (count($total_objetos) + 1) . "', '$duracion_video', ";
        }
        if ($tipo_objeto == "trivia" and $tipo == "objeto") {
            $id_evaluacion = $post["id_evaluacion"];
            $campos_tabla .= "tipo_objeto, id_evaluacion, id, orden, ";
            $valores .= "'5', '$id_evaluacion', '" . $id_curso . "_" . (count($total_objetos) + 1) . "', '" . (count($total_objetos) + 1) . "', ";
        }
        if ($tipo_objeto == "descargaArchivo" and $tipo == "objeto") {
            $ruta_curso = "../front/objetos/" . $id_curso;
            if (!is_dir($ruta_curso)) {
//Directory does not exist, so lets create it.
                mkdir($ruta_curso, 0755);
            }
            $ruta_objeto = "../front/objetos/" . $id_curso . "/" . $id_curso . "_" . (count($total_objetos) + 1);
            if (!is_dir($ruta_objeto)) {
//Directory does not exist, so lets create it.
                mkdir($ruta_objeto, 0755);
            }
            $tamano_archivo = $_FILES["archivo_descarga"]['size'];
            $tipo_archivo = $_FILES["archivo_descarga"]['type'];
            $archivo_archivo = $_FILES["archivo_descarga"]['name'];
            VerificaExtensionFilesAdmin($_FILES["archivo_descarga"]);
            $arreglo_archivo = explode(".", $archivo_archivo);
            $extension_archivo = $arreglo_archivo[1];
            $rutaarchivo = $ruta_objeto;
            $rutaarchivo_para_guardar = "objetos/" . $id_curso . "/" . $id_curso . "_" . (count($total_objetos) + 1);

            if ($arreglo_archivo != "") {
                $nombre_archivo = $id_curso . "_" . (count($total_objetos) + 1);
                $datos_subida_archivo = SubirArchivosGenericos($_FILES, $extension_archivo, $nombre_archivo, $rutaarchivo, 1, "archivo_descarga");
                $nombre_archivo = $datos_subida_archivo[1];
            }

            $campos_tabla .= "tipo_objeto, extension_objeto, id, orden, archivo_descarga, ";
            $valores .= "'3', '24', '" . $id_curso . "_" . (count($total_objetos) + 1) . "', '" . (count($total_objetos) + 1) . "', '$nombre_archivo', ";
        }
    }
    MANTENEDOR_InsertaConCamposDinamicos($id_empresa, $nombre_tabla, $campos_tabla, $valores);

    echo "
<script>
alert('Datos ingresados exitosamente');
location.href='?sw=ListadoMantenedor&tipo=" . $tipo . "';
</script>";
}
else if ($seccion == "accionCrea") {
    $id_empresa = $_SESSION["id_empresa"];
    $arreglo_post = $post;

    $PRINCIPAL = FuncionMantenedores(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/mantenedor/index.html")), $id_empresa, $arreglo_post);
    $PRINCIPAL = str_replace("{ID_EMPRESA}", $id_empresa, $PRINCIPAL);
    $PRINCIPAL = str_replace("{VALUE_TIPO_OBJETO}", $post["tipo_objeto"], $PRINCIPAL);
    if ($post["tipo_objeto"] && $post["tipo"] == "objeto") {
        $PRINCIPAL = str_replace("{DISPLAY_TIPO_OBJETO}", "", $PRINCIPAL);
        $PRINCIPAL = str_replace("{SELECTED" . $post["tipo_objeto"] . "}", "selected", $PRINCIPAL);
    } else {
        $PRINCIPAL = str_replace("{DISPLAY_TIPO_OBJETO}", 'style="display: none"', $PRINCIPAL);
    }

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "LMS_DesactivaCurso") {
    $id_curso = $post[id_curso];
    $id_malla = $post[id_malla];
    $id_clasificacion = $post[id_clasificacion];
    $id_empresa = $post[id_empresa];

    LMS_DesactivaActivaCurso_relMalla($id_curso, $id_clasificacion, $id_malla, $id_empresa, "1");

    echo '<a   class="btn btn-danger btn-xs" onclick="LmsActivaCurso_' . $id_curso . '_' . $id_malla . '_' . $id_clasificacion . '();">Activar</a>';
}
else if ($seccion == "LMS_ActivaCurso") {
    $id_curso = $post[id_curso];
    $id_malla = $post[id_malla];
    $id_clasificacion = $post[id_clasificacion];
    $id_empresa = $post[id_empresa];

    LMS_DesactivaActivaCurso_relMalla($id_curso, $id_clasificacion, $id_malla, $id_empresa, "0");
    echo '<a class="btn btn-info btn-xs"     onclick="LmsDesactivaCurso_' . $id_curso . '_' . $id_malla . '_' . $id_clasificacion . '();">Desactivar</a>';
}
else if ($seccion == "ListadoClasificacionesComboBox") {
//traigo las clasificacioen scon ese id de empresa
    $id_empresa = $_SESSION["id_empresa"];
    $total_clasificaciones = TotalClasificacion($id_empresa);
    $html_select = "<select>";
    foreach ($total_clasificaciones as $clasif) {
        $html_select .= "<option >" . $clasif->clasificacion . "</option>";
    }
    $html_select .= "</select>";
    echo ($html_select);
}
else if ($seccion == "ResetarEvaluacionObjeto") {

    $id_objeto = $post[id_objeto];
    $rut = $post[rut];


    LmsReseteoEvaluacion($id_objeto, $rut);

}
else if ($seccion == "addRelMCC") {
    $id_curso = $post["idc"];
    $id_malla = $post["idm"];
    $id_clasificacion = $post["idcl"];
    $id_empresa = $post["id_empresa"];
    InsertaRelacionMallaClasificacionCursoAdmin($id_malla, $id_curso, $id_clasificacion, $id_empresa,'','');

    echo "
<script>
location.href='?sw=vista_mallas_clasificaciones_cursos_objetos';
</script>";
}
else if ($seccion == "ListadoCursosComboBox") {
//con el id de la clasificacion y el id de empresa, traigo los cursos y los muestro en comboboxc
    $id_malla = $post["id_malla"];
    $id_clasificacion = $post["id_clasificacion"];
    $id_empresa = $_SESSION["id_empresa"];
    $cursos = CursosPorEmpresaV2SoloElearning($id_empresa);

    $html_select = "<form method='post' action='?sw=addRelMCC' name='$id_malla_$id_clasificacion'><select name='idc'>";
    foreach ($cursos as $cur) {
        $html_select .= "<option value='" . $cur->id . "'>" . $cur->nombre . "</option>";
    }
    $html_select .= "</select><br>
<input type'button' value='guardar' class='btn btn-info btn-xs' onclick=\"if (confirm('Seguro que deseas asignar el curso seleccionado a la categoria " . $id_clasificacion . "?')) document.$id_malla_$id_clasificacion.submit();\">";

    $html_select .= "

<input type='hidden' value='$id_malla' name='idm'>
<input type='hidden' value='$id_clasificacion' name='idcl'>
<input type='hidden' value='$id_empresa' name='id_empresa'>

</form>";
    echo ($html_select);
}
else if ($seccion == "accioncmalla") {
    $id_malla = Decodear3($get["i"]);

    if ($id_malla) {
        $PRINCIPAL = FormulariocMalla(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/malla/formulario_malla.html")), $id_malla);
    } else {
        $PRINCIPAL = FormulariocMalla(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/malla/formulario_malla_ingreso.html")), $id_malla);
    }

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "adcmalllabk") {
    $nombre_malla = (trim($post["nombre_malla"]));
    $descripcion_malla = (trim($post["descripcion_malla"]));
    $tipo_malla = trim($post["tipo_malla"]);
    $certificable = trim($post["certificable"]);
    $ponderacion = trim($post["ponderacion"]);
    $tipo_fecha = trim($post["tipo_fecha"]);
//Agrego la malla a la tabla tbl_malla
    InsertaMalla(($nombre_malla), ($descripcion_malla), $tipo_malla, $certificable, $ponderacion, $tipo_fecha);
    echo "
<script>
alert('Malla ingresada exitosamente');
location.href='?sw=listcmallas';
</script>";
}
else if ($seccion == "FinalizaObjetoDesdeAdmin") {
    $rut = $post["rut"];
    $id_objeto = $post["id_objeto"];
    $id_empresa = $_SESSION["id_empresa"];
    FinalizaObjetoFullDatos($rut, $id_objeto, $id_empresa);
    $objeto_finalizado = ObjetoFinalizadoDadoIdYRut($rut, $id_objeto);
    $resultado = file_get_contents("views/capacitacion/lms_malla_clas_cursos/row_aprobado.html");
    $resultado = str_replace("{NOTA}", $objeto_finalizado[0]->nota, $resultado);

    echo $resultado;
}
else if ($seccion == "adcmallla") {
    $nombre_malla = (trim($post["nombre_malla"]));
    $descripcion_malla = (trim($post["descripcion_malla"]));
    $tipo_malla = trim($post["tipo_malla"]);
    $codigo_malla = ($post["codigo_malla"]);
    $certificable = trim($post["certificable"]);
    $ponderacion = trim($post["ponderacion"]);
    $tipo_fecha = trim($post["tipo_fecha"]);
//Agrego la malla a la tabla tbl_malla
    InsertaMalla2($codigo_malla, $nombre_malla, $descripcion_malla, $tipo_malla, $certificable, $ponderacion, $tipo_fecha, $_SESSION["id_empresa"]);
    echo "
<script>
alert('Malla ingresada exitosamente');
location.href='?sw=listcmallas';
</script>";
}
else if ($seccion == "edcmallla") {

    $nombre_malla = ($post["nombre_malla"]);
    $descripcion_malla = ($post["descripcion_malla"]);
    $tipo_malla = ($post["tipo_malla"]);
    $codigo_malla = ($post["codigo_malla"]);
    $certificable = ($post["certificable"]);
    $ponderacion = ($post["ponderacion"]);
    $tipo_fecha = ($post["tipo_fecha"]);
    $id_malla = Decodear3($post["id"]);
//Edita Malla
    ActualizaMalla2($codigo_malla, $nombre_malla, $descripcion_malla, $tipo_malla, $certificable, $ponderacion, $tipo_fecha, $id_malla, $_SESSION["id_empresa"]);
    echo "
<script>
alert('Categoria editada exitosamente');
location.href='?sw=listcmallas';
</script>";
}
else if ($seccion == "relMallaCurso") {
    $id_empresa = $_SESSION["id_empresa"];
    $id_curso = Decodear3($get["i"]);

    if ($id_curso) {
    } else {
        $PRINCIPAL = FormularioImparticion(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/rel_malla_curso/formulario_ingresa.html")), $id_empresa);

        $PRINCIPAL = ColocaFiltrosElearning($PRINCIPAL, $id_empresa);

        $PRINCIPAL = str_replace("{ID_EMPRESA}", $_SESSION["id_empresa"], $PRINCIPAL);
        $PRINCIPAL = ListadoCursosAdmin2(FuncionesTransversalesAdmin($PRINCIPAL), $id_empresa, "", "", "imparticion_solo_preenciales");

        $PRINCIPAL = ListadocMallas($PRINCIPAL, $id_empresa, "imparticiones");
    }

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "relmallacurso_") {
    $id_malla = Decodear3($get["i"]);


    $PRINCIPAL = ListadoRelacionesMallaCurso(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/malla/formulario_rel_malla_curso.html")), $id_malla, $_SESSION["id_empresa"]);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "adrelmc") {
    $id_malla = Decodear3($post["i"]);
    if ($post["cursos"]) {
        for ($i = 0; $i < count($post["cursos"]); $i++) {
//Inserto un registro en la tabla rel nivel curso
            InsertaRelacionMallaCurso($id_malla, $post["cursos"][$i]);
            InsertaInscripcionMallaCurso($id_malla, $post["cursos"][$i]);
        }

        echo "
<script>
alert('Cursos relacionados correctamente');
location.href='?sw=relmallacurso&i=" . Encodear($id_malla) . "';
</script>";
        exit;
    }
}
else if ($seccion == "listmallaclasificacion") {
    if ($get["orden"] != "") {
        if ($get["campo"] == "orden_curso") {
            ordena_campo_curso($get["orden_anterior"], $get["orden"], $get["tabla"], $get["campo"], $_SESSION["id_empresa"], $get["id_malla"], $get["id_clasificacion"]);
        } else {
            ordena_campo_clasificacion($get["orden_anterior"], $get["orden"], $get["tabla"], $get["campo"], $_SESSION["id_empresa"], $get["id_malla"], $get["id_clasificacion"]);
        }
    }

    $PRINCIPAL = ListadoMallaClasificacion(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/malla/entorno_listado_malla_clasificacion.html")), $get["m"], $_SESSION["id_empresa"]);

    if ($get["vc"] == "1") {
        $PRINCIPAL = str_replace("{BTNCURSO1}", "display:none;", $PRINCIPAL);
        $PRINCIPAL = str_replace("{BTNCURSO2}", "", $PRINCIPAL);
        $PRINCIPAL = str_replace("{TCURSOS}", "", $PRINCIPAL);
    } else {
        $PRINCIPAL = str_replace("{BTNCURSO1}", "", $PRINCIPAL);
        $PRINCIPAL = str_replace("{BTNCURSO2}", "display:none;", $PRINCIPAL);
        $PRINCIPAL = str_replace("{TCURSOS}", "display:none;", $PRINCIPAL);
    }

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "listclasificacion") {
    $PRINCIPAL = ListadoClasificacion(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/clasificacion/entorno_listado_clasificacion.html")));

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "accionclasificacion") {
    $id_categoria = $get["i"];

    if ($id_categoria) {
        $PRINCIPAL = FormularioClasificacion(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/clasificacion/formulario_clasificacion.html")), $id_categoria);
    } else {
        $PRINCIPAL = FormularioClasificacion(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/clasificacion/formulario_clasificacion_ingreso.html")), $id_categoria);
    }

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "adclasificacion") {
    $id_clasificacion = (trim($post["id_clasificacion"]));
    $clasificacion = (trim($post["clasificacion"]));
    $color = trim($post["color"]);
    $color2 = trim($post["color2"]);
    $imagen = $_FILES["imagen"]['name'];
    VerificaExtensionFilesAdmin($_FILES["imagen"]);
    $imagen_back = $_FILES["imagen_back"]['name'];

    move_uploaded_file($_FILES['imagen']['tmp_name'], "img/capacitacion/" . $_FILES["imagen"]['name']);
    move_uploaded_file($_FILES['imagen_back']['tmp_name'], "img/capacitacion/back/" . $_FILES["imagen_back"]['name']);

    InsertaClasificacion($id_clasificacion, $clasificacion, $color, $imagen, $imagen_back, $_SESSION["id_empresa"], $color2);
    echo "
<script>
alert('Clasificación ingresada exitosamente');
location.href='?sw=listclasificacion';
</script>";
}
else if ($seccion == "edclasificacion") {
    $id = (trim($post["id"]));
    $id_clasificacion = (trim($post["id_clasificacion"]));
    $clasificacion = (trim($post["clasificacion"]));
    $color = trim($post["color"]);
    $color2 = trim($post["color2"]);
    $imagen = trim($post["imagen"]);
    $imagen_back = trim($post["imagen_back"]);
    $imagen = $_FILES["imagen"]['name'];
    VerificaExtensionFilesAdmin($_FILES["imagen"]);
    $imagen_back = $_FILES["imagen_back"]['name'];
    if ($imagen != "") {
        move_uploaded_file($_FILES['imagen']['tmp_name'], "img/capacitacion/" . $_FILES["imagen"]['name']);
    }
    if ($imagen_back != "") {
        move_uploaded_file($_FILES['imagen_back']['tmp_name'], "img/capacitacion/back/" . $_FILES["imagen_back"]['name']);
    }

    ActualizaClasificacion($id, $id_clasificacion, $clasificacion, $color, $imagen, $imagen_back, $_SESSION["id_empresa"], $color2);
    echo "
<script>
alert('Clasificacion editada exitosamente');
location.href='?sw=listclasificacion';
</script>";
}
else if ($seccion == "accioncursoT") {
    $id_curso = Decodear3($get["i"]);
    if ($id_curso) {
        $PRINCIPAL = FormularioCursoT(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/cursoT/formulario_edita.html")), $id_curso, $_SESSION["id_empresa"]);
    } else {
        $PRINCIPAL = FormularioCursoT(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/cursoT/formulario_ingresa.html")), $id_curso, $_SESSION["id_empresa"]);
    }
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "accioncurso2Elearning") {
    $id_curso = Decodear3($get["i"]);

    //

    if ($id_curso) {
        $PRINCIPAL = FormularioCurso2Elearning(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/cursoelearning/formulario_edita.html")), $id_curso, $_SESSION["id_empresa"]);
    } else {
        $PRINCIPAL = FormularioCurso2Elearning(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/cursoelearning/formulario_ingresa.html")), $id_curso, $_SESSION["id_empresa"]);
    }

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "subeRelMallaPersonaPorMalla") {
    

    if ($post["multi"] == "on") {$multimalla = "si";} else {$multimalla = "no";}


    $id_programa = $post["ip"];
    $archivo = $_FILES['excel']['name'];VerificaExtensionFilesAdmin($_FILES["excel"]);
    $tipo = $_FILES['excel']['type'];
    $id_empresa = $_SESSION["id_empresa"];
    $destino = "tmp_ev_subida_rellmsmallapersona" . $archivo;

    if (copy($_FILES['excel']['tmp_name'], $destino)) {
    } else {
        $error_grave = "Error Al subir Archivo<br />";
    }

    if (file_exists("tmp_ev_subida_rellmsmallapersona" . $archivo)) {
        require_once 'clases/PHPExcel.php';
        require_once 'clases/PHPExcel/Reader/Excel2007.php';

        $objReader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $objReader->load("tmp_ev_subida_rellmsmallapersona" . $archivo);
        $objFecha = new PHPExcel_Shared_Date();
        $objPHPExcel->setActiveSheetIndex(0);
        $HojaActiva = $objPHPExcel->getActiveSheet();
        $total_filas = $HojaActiva->getHighestRow();
        $lastColumn = $HojaActiva->getHighestColumn();
        $total_columnas = PHPExcel_Cell::columnIndexFromString($lastColumn);
        $UltimaColumna = "A";
        $j = 0;
        $_DATOS_EXCEL = array();
        for ($i = 0; $i <= $total_columnas; $i++) {
            $_campo[$i] = trim(($HojaActiva->getCell($UltimaColumna . "1")->getCalculatedValue()));
            $UltimaColumna++;
        }
        for ($fila = 2; $fila <= $total_filas; $fila++) {
            for ($columna = 0; $columna <= $total_columnas - 1; $columna++) {
                $_DATOS_EXCEL[$j][$columna + 1] = (trim($HojaActiva->getCellByColumnAndRow($columna, $fila)->getCalculatedValue()));
            }
            $j++;
        }

    } else {
        $error_grave = "Necesitas primero importar el archivo";
    }

    $total_errores = 0;
    $total_actualizar = 0;
    $total_insertar = 0;
    $usuario_inexistente = 0;
    $curso_inexistente = 0;
    $linea = 2;
    $no_esta_en_tabla_usuario = 0;
    $datos_programa = ObtieneDatosProgramasPorEmpresa($id_programa, $id_empresa);
    $total_mallas_por_programa = TraeMallasDadoPrograma($id_empresa, $id_programa);




    if (count($_DATOS_EXCEL) < 1) {	echo "  <script>location.href='?sw=listProgramas';</script>";exit;	}
    		BorroRelacionesMallaPersonaPorempresaYPrograma($id_empresa, $total_mallas_por_programa);
   			
   ob_start();
 $total=count($_DATOS_EXCEL);
    for ($l = 1; $l <= count($_DATOS_EXCEL); $l++) {

				echo "<center><span style='position: absolute;z-index:$current;background:#36C6D3; padding:10px; color:#FFF'>Actualizando Masivamente $l de $total Registros.</span></center>";
				doFlush();


        $rut = LimpiaRut($_DATOS_EXCEL[$l - 1][1]);
        $id_malla = ($_DATOS_EXCEL[$l - 1][2]);
        $ok_malla = VerificaMallaidPrograma($id_malla, $id_programa);
        $hoy = date('Y-m-d');
        $opcional = ($_DATOS_EXCEL[$l - 1][3]);
        $fecha_inscripcion = ($_DATOS_EXCEL[$l - 1][4]);

        $array_activo = VerificaRutActivo($rut);
        if ($array_activo[0]->vigencia == '1') {
            continue;
        }

        if ($id_malla and count($ok_malla) > 0) {
            InsertaRelacionMallaPersonaempresaOpcional($rut, $id_malla, $id_empresa, $opcional, $hoy, $id_programa, $multimalla);
            $miListaCursosDadaMalla = TraeSoloCursos($id_malla, $id_empresa);
            foreach ($miListaCursosDadaMalla as $miListaCursoDadaMalla) {
                if (checkIsAValidDate($fecha_inscripcion) == true) {
                } else {
                    $UNIX_DATE = ($fecha_inscripcion - 25569) * 86400;
                    $fecha_inscripcion = gmdate("Y-m-d", $UNIX_DATE);
                }

                if($opcional=="1" or  $opcional=="Si"){$opcional_inscripcion="Si";}
                InsertaRelacionInscripcionUsuarioMallaPersonaempresa($rut, $id_malla, $miListaCursoDadaMalla->id_curso, $id_empresa, $hoy, $id_programa, $multimalla, $fecha_inscripcion, $opcional_inscripcion);
            }
        }
        
        $array_reportes_inscripcion = ACT_BuscaRUTCursoMallaPrograma_Reportes_Inscripcion($rut, $miListaCursoDadaMalla->id_curso, $id_malla, $id_programa);
 		}


    $array_reportes_inscripcionNO = ACT_BuscaRUTCursoMallaPrograma_Reportes_NoEn_Inscripcion($id_programa);
			
    	// busca en Reportes que no estén en Inscripcion Usuarios
    	// VERIFICA

    ActualizaDatosLmsReportesUsuarios_data($id_empresa);

    echo "
			<script>location.href='?sw=listProgramas';</script>";
    exit;
}
else if ($seccion == "subeUsuariosporMedicion") {
    $id_medicion = $post["id"];
    $archivo = $_FILES['excel']['name'];VerificaExtensionFilesAdmin($_FILES["excel"]);
    $tipo = $_FILES['excel']['type'];
    $id_empresa = $_SESSION["id_empresa"];
    $destino = "tmp_ev_subida_usuarios_medicion" . $archivo;

    if (copy($_FILES['excel']['tmp_name'], $destino)) {
    } else {
        $error_grave = "Error Al subir Archivo<br>";
    }

    if (file_exists("tmp_ev_subida_usuarios_medicion" . $archivo)) {
        require_once 'clases/PHPExcel.php';
        require_once 'clases/PHPExcel/Reader/Excel2007.php';

        $objReader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $objReader->load("tmp_ev_subida_usuarios_medicion" . $archivo);
        $objFecha = new PHPExcel_Shared_Date();
        $objPHPExcel->setActiveSheetIndex(0);
        $HojaActiva = $objPHPExcel->getActiveSheet();
        $total_filas = $HojaActiva->getHighestRow();
        $lastColumn = $HojaActiva->getHighestColumn();
        $total_columnas = PHPExcel_Cell::columnIndexFromString($lastColumn);

        $UltimaColumna = "A";
        $j = 0;

        $_DATOS_EXCEL = array();
        //Obtengo los nombres de los campos
        for ($i = 0; $i <= $total_columnas; $i++) {
            $_campo[$i] = trim(($HojaActiva->getCell($UltimaColumna . "1")->getCalculatedValue()));
            $UltimaColumna++;
        }

        //Obtengo datos de filas
        for ($fila = 2; $fila <= $total_filas; $fila++) {
            for ($columna = 0; $columna <= $total_columnas - 1; $columna++) {
                $_DATOS_EXCEL[$j][$columna + 1] = (trim($HojaActiva->getCellByColumnAndRow($columna, $fila)->getCalculatedValue()));
            }
            $j++;
        }
    } else {
        $error_grave = "Necesitas primero importar el archivo";
    }

    $total_errores = 0;
    $total_actualizar = 0;
    $total_insertar = 0;
    $usuario_inexistente = 0;
    $curso_inexistente = 0;
    $linea = 2;
    $no_esta_en_tabla_usuario = 0;


    if (count($_DATOS_EXCEL) < 1) {
        echo "
        <script>
            location.href='?sw=lista_Mediciones_med';
        </script>";
        exit;
    }

    BorrarUsuarioMedicion($id_empresa, $id_medicion);


    $array_encuesta = MedBuscaIdEncuestraMed($id_medicion, $id_empresa);
    $id_encuesta = $array_encuesta[0]->id_encuesta;

    for ($l = 1; $l <= count($_DATOS_EXCEL); $l++) {

        $rut_colaborador = LimpiaRut($_DATOS_EXCEL[$l - 1][3]);
        $rut_jefe = LimpiaRut($_DATOS_EXCEL[$l - 1][5]);
        $fecha_carga = ($_DATOS_EXCEL[$l - 1][7]);
        $grupo = ($_DATOS_EXCEL[$l - 1][8]);
        $hoy = date('Y-m-d');


        InsertaUsuarioMedicion($rut_colaborador, $rut_jefe, $id_medicion, $id_encuesta, $hoy, $id_empresa, $fecha_carga, $grupo);
    }

    echo "
<script>
location.href='?sw=lista_Mediciones_med';
</script>";
    exit;
}
else if ($seccion == "accionSubeNotasPorImparticion") {
    $id_imparticion = Decodear3($post["ci"]);

    $archivo = $_FILES['excel']['name'];VerificaExtensionFilesAdmin($_FILES["excel"]);
    $tipo = $_FILES['excel']['type'];
    $id_empresa = $_SESSION["id_empresa"];

    $datos_curso = DAtosCursoDadoIdInscripcion($id_imparticion, $id_empresa);
    $id_curso = $datos_curso[0]->id_curso;

    $destino = "tmp_ev_subida_usuariocheckinsesion" . $archivo;
    if (copy($_FILES['excel']['tmp_name'], $destino)) {
    } else {
        $error_grave = "Error Al subir Archivo<br>";
    }

    if (file_exists("tmp_ev_subida_usuariocheckinsesion" . $archivo)) {
        require_once 'clases/PHPExcel.php';
        require_once 'clases/PHPExcel/Reader/Excel2007.php';

        $objReader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $objReader->load("tmp_ev_subida_usuariocheckinsesion" . $archivo);
        $objFecha = new PHPExcel_Shared_Date();
        $objPHPExcel->setActiveSheetIndex(0);
        $HojaActiva = $objPHPExcel->getActiveSheet();
        $total_filas = $HojaActiva->getHighestRow();
        $lastColumn = $HojaActiva->getHighestColumn();
        $total_columnas = PHPExcel_Cell::columnIndexFromString($lastColumn);

        $UltimaColumna = "A";
        $j = 0;

        $_DATOS_EXCEL = array();
//Obtengo los nombres de los campos
        for ($i = 0; $i <= $total_columnas; $i++) {
            $_campo[$i] = trim(($HojaActiva->getCell($UltimaColumna . "1")->getCalculatedValue()));
            $UltimaColumna++;
        }

//Obtengo datos de filas
        for ($fila = 2; $fila <= $total_filas; $fila++) {
            for ($columna = 0; $columna <= $total_columnas - 1; $columna++) {
                $_DATOS_EXCEL[$j][$columna + 1] = (trim($HojaActiva->getCellByColumnAndRow($columna, $fila)->getCalculatedValue()));
            }
            $j++;
        }
    } else {
        $error_grave = "Necesitas primero importar el archivo";
    }

    $total_errores = 0;
    $total_actualizar = 0;
    $total_insertar = 0;
    $usuario_inexistente = 0;
    $curso_inexistente = 0;
    $linea = 2;
    $no_esta_en_tabla_usuario = 0;

    $actualizados = 0;
    $insertados = 0;

    
    for ($l = 1; $l <= count($_DATOS_EXCEL); $l++) {
        $rut = LimpiaRut($_DATOS_EXCEL[$l - 1][1]);
        
        $nota = $_DATOS_EXCEL[$l - 1][8];
        $estado = $_DATOS_EXCEL[$l - 1][9];
        $porcentaje_asistencia = $_DATOS_EXCEL[$l - 1][10];

        $esta_cierre = VerificaCursoEstaEnCierrePorEmpresaImparticion($id_curso, $rut, $id_empresa, $id_imparticion);


        if ($esta_cierre) {
            

            LMS_ActualizaRegistroCierreCursoImparticion($rut, $id_curso, $id_empresa, $nota, $estado, $porcentaje_asistencia, $id_imparticion, $porcentaje_asistencia);
            $actualizados++;
        } else {

            LMS_InsertaRegistroCierreCursoImparticion($rut, $id_curso, $id_empresa, $nota, $estado, $porcentaje_asistencia, $id_imparticion, $porcentaje_asistencia);

            $insertados++;
        }
//Veo si esta en Inscripcion Cierre1
    }


    echo "<script>alert('Actualizado los datos. Insertados: " . $insertados . " - Actualizados: " . $actualizados . "');location.href='?sw=VeColaboradoresXImpXsess&i=" . Encodear3($id_imparticion) . "';</script>";
    exit;
}
else if ($seccion == "subeUsuariosPorEmpresa") {
    $archivo = $_FILES['excel']['name'];VerificaExtensionFilesAdmin($_FILES["excel"]);
    $tipo = $_FILES['excel']['type'];
    $id_empresa = $_SESSION["id_empresa"];
    $destino = "back_subida_porempresa" . $archivo;
    if (copy($_FILES['excel']['tmp_name'], $destino)) {
    } else {
        $error_grave = "Error Al subir Archivo<br>";
    }
    if (file_exists("back_subida_porempresa" . $archivo)) {
        require_once 'clases/PHPExcel.php';
        require_once 'clases/PHPExcel/Reader/Excel2007.php';
        $objReader = new PHPExcel_Reader_Excel2007();
        $objPHPExcel = $objReader->load("back_subida_porempresa" . $archivo);
        $objFecha = new PHPExcel_Shared_Date();
        $objPHPExcel->setActiveSheetIndex(0);
        $HojaActiva = $objPHPExcel->getActiveSheet();
        $total_filas = $HojaActiva->getHighestRow();
        $lastColumn = $HojaActiva->getHighestColumn();
        $total_columnas = PHPExcel_Cell::columnIndexFromString($lastColumn);

        $UltimaColumna = "A";
        $j = 0;

        $_DATOS_EXCEL = array();
//Obtengo los nombres de los campos
        for ($i = 0; $i <= $total_columnas; $i++) {
            $_campo[$i] = trim(($HojaActiva->getCell($UltimaColumna . "1")->getCalculatedValue()));
            $UltimaColumna++;
        }

//Obtengo datos de filas
        for ($fila = 2; $fila <= $total_filas; $fila++) {
            for ($columna = 0; $columna <= $total_columnas - 1; $columna++) {
                $_DATOS_EXCEL[$j][$columna + 1] = (trim($HojaActiva->getCellByColumnAndRow($columna, $fila)->getCalculatedValue()));
            }
            $j++;
        }
    } else {
        $error_grave = "Necesitas primero importar el archivo";
    }
    $total_errores = 0;
    $total_actualizar = 0;
    $total_insertar = 0;
    $usuario_inexistente = 0;
    $curso_inexistente = 0;
    $linea = 2;
    $no_esta_en_tabla_usuario = 0;

    //Aca borro los usuarios de la tabla de relacion checkin imparticion por sesion
    BorrarUsuariosPorEmpresa2($id_empresa);
    $campos_empresa = listarEmpresaSubidaUsuarios($id_empresa);
//Borro lo de la tabla usuario
    $query1 = "insert into tbl_usuario (id_empresa, rut, ";
    $total_campos = 0;
    foreach ($campos_empresa as $camp) {
        $total_campos++;
        $query1 .= $camp->campo . "";
        if ($total_campos < count($campos_empresa)) {
            $query1 .= ", ";
        }
    }
    $query1 .= ")";

    for ($l = 1; $l <= count($_DATOS_EXCEL); $l++) {

        $letra_excel = "A";
        $rut_limpio = LimpiaRut($_DATOS_EXCEL[$l - 1][1]);
        $query_por_usuario = "values('" . $id_empresa . "', '" . $rut_limpio . "', ";
        $contador_vueltas = 0;
        foreach ($campos_empresa as $camp) {
            $contador_vueltas++;

            if ($camp->campo == 'fecha_antiguedad') {
                $checkFecha = check_date($_DATOS_EXCEL[$l - 1][$contador_vueltas]);
                if ($checkFecha != '') {
                    $fecha_data = $checkFecha;
                } else {
                    $fecha_data = transformfechaexcelaphp($_DATOS_EXCEL[$l - 1][$contador_vueltas]);
                }
                $query_por_usuario .= "'" . $fecha_data . "'";
            } elseif ($camp->campo == 'fecha_nacimiento') {

                $checkFecha = check_date($_DATOS_EXCEL[$l - 1][$contador_vueltas]);
                if ($checkFecha != '') {
                    $fecha_data = $checkFecha;
                } else {
                    $fecha_data = transformfechaexcelaphp($_DATOS_EXCEL[$l - 1][$contador_vueltas]);
                }
                $query_por_usuario .= "'" . $fecha_data . "'";
            } elseif ($camp->campo == 'fecha_ingreso') {

                $checkFecha = check_date($_DATOS_EXCEL[$l - 1][$contador_vueltas]);
                if ($checkFecha != '') {
                    $fecha_data = $checkFecha;
                } else {
                    $fecha_data = transformfechaexcelaphp($_DATOS_EXCEL[$l - 1][$contador_vueltas]);
                }
                $query_por_usuario .= "'" . $fecha_data . "'";
            } elseif ($camp->campo == 'responsable') {

                $rut_responsable = LimpiaRut($_DATOS_EXCEL[$l - 1][$contador_vueltas]);
                $query_por_usuario .= "'" . $rut_responsable . "'";
            } elseif ($camp->campo == 'jefe') {

                $rut_jefe = LimpiaRut($_DATOS_EXCEL[$l - 1][$contador_vueltas]);
                $query_por_usuario .= "'" . $rut_jefe . "'";
            } else {
                $query_por_usuario .= "'" . $_DATOS_EXCEL[$l - 1][$contador_vueltas] . "'";
            }

            if ($contador_vueltas < count($campos_empresa)) {
                $query_por_usuario .= ", ";
            }
        }
        $query_por_usuario .= ")";


        $query_completa = $query1 . $query_por_usuario;

        InsertaConQuery(($query_completa));

         }

    echo "
<script>
location.href='?sw=listusu';
</script>";
}
else if ($seccion == "DescargaMallaImparticion") {
    $id_imparticion = Decodear3($get["ci"]);
    $id_empresa = $_SESSION["id_empresa"];
    $datos_empresa = DatosEmpresa($id_empresa);
//Traigo el listado de usuarios de la tabla inscripcion_usuarios
    $total_usuarios = IMPARTICION_DatosPorRelMallaPersona($id_imparticion, $id_empresa, $datos_empresa);
    $fechahoy = date("Y-m-d") . " " . date("H:i:s");
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=" . $fechahoy . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    ?>
<table>
<tr>
<td>RUT</td>
<td>NOMBRE</td>
<td>CARGO</td>

<td>C1</td>
<td>C2</td>
<td>C3</td>

</tr>

<?php
foreach ($total_usuarios as $usu) {
        ?>
<tr>
<td><?php echo $usu->rut_completo; ?></td>
<td><?php echo $usu->nombre_completo; ?></td>
<td><?php echo $usu->cargo; ?></td>
<td><?php echo $usu->campo1; ?></td>
<td><?php echo $usu->campo2; ?></td>
<td><?php echo $usu->campo3; ?></td>
</tr>

<?php
}
    ?>
</table>
<?php
}
else if ($seccion == "descargaPreInscritos") {
    $id_imparticion = Decodear3($get["ci"]);
    $id_curso = Decodear3($get["idc"]);
    $nombreCurso = BuscaCursoDadoImp($id_imparticion);


    $id_empresa = $_SESSION["id_empresa"];
    $datos_empresa = DatosEmpresa($id_empresa);

//Traigo el listado de usuarios de la tabla inscripcion_usuarios
    $total_usuarios = IMPARTICION_DatosPorInscripcion($id_imparticion, $id_empresa, $datos_empresa, $id_curso);
    $datos_curso = VerificoCursoPorEmpresa($id_curso, $id_empresa);
    $datos_rel_malla_curso = TraeMallaCursoConDatos($id_curso, $id_empresa);

    $fechahoy = date("Y-m-d") . " " . date("H:i:s");

    header("Content-Type: application/vnd.ms-excel");

    header("Content-Disposition: attachment; filename=" . ($nombreCurso[0]->id_curso) . "_" . $id_imparticion . "_Preinscritos_" . $fechahoy . ".xls");

    header("Pragma: no-cache");

    header("Expires: 0");

    ?>
<table>
<tr>
<td>RUT</td>
<td>NOMBRE</td>
<td>CARGO</td>
<td>EMAIL</td>
<td>C1</td>
<td>C2</td>
<td>C3</td>
<td>ESTADO</td>
<td>FECHA</td>
<td>HORA</td>
<td>ID_IMPARTICION</td>
<td>ID_CURSO</td>
<td>NOMBRE_CURSO</td>
<?php if ($datos_curso[0]->modalidad != 1) {?>
<td>FECHA_INICIO</td>
<td>FECHA_TERMINO</td>
<td>CUPOS</td>
<td>DIRECCION</td>
<td>LUGAR</td>
<?php
} else {
        ?>
<td>ID_CLASIFICACION</td>
<td>NOMRE_CLASIFICACION</td>
<td>ID_MALLA</td>
<td>NOMBRE_MALLA</td>
<td>ID_PROGRAMA</td>
<td>NOMBRE_PROGRAMA</td>
<td>ID_FOCO</td>
<td>NOMBRE_FOCO</td>

<?php
}
    ?>
</tr>

<?php

    $listado_postulantes_preinscritos_Cero = IMPARTICION_PostulantesPorInscripcionConDatosUsuarioConPreinscritoCero($id_imparticion, $id_empresa, $datos_empresa, $id_curso);
    foreach ($listado_postulantes_preinscritos_Cero as $unico) {
        ?>
<tr>
<td><?php echo $unico->rut_completo; ?></td>
<td><?php echo $unico->nombre_completo; ?></td>
<td><?php echo $unico->cargo; ?></td>
<td><?php echo $unico->email; ?></td>

<td><?php echo $unico->c1; ?></td>
<td><?php echo $unico->c2; ?></td>
<td><?php echo $unico->c3; ?></td>
<td><?php echo $unico->preinscrito; ?></td>
<td><?php echo $unico->fecha; ?></td>
<td><?php echo $unico->hora; ?></td>
<td><?php echo $unico->id_inscripcion; ?></td>
<td><?php echo $unico->id_curso; ?></td>
<td><?php echo $unico->nombre_curso; ?></td>
<?php if ($datos_curso[0]->modalidad != 1) {?>
<td><?php echo $unico->fecha_inicio; ?></td>
<td><?php echo $unico->fecha_termino; ?></td>
<td><?php echo $unico->cupos; ?></td>
<td><?php echo $unico->direccion; ?></td>
<td><?php echo $unico->lugar; ?></td>

<?php } else {?>
<td><?php echo $datos_rel_malla_curso[0]->id_clasificacion; ?></td>
<td><?php echo $datos_rel_malla_curso[0]->nombre_clasificacion; ?></td>
<td><?php echo $datos_rel_malla_curso[0]->id_malla; ?></td>
<td><?php echo $datos_rel_malla_curso[0]->nombre_malla; ?></td>
<td><?php echo $datos_rel_malla_curso[0]->id_programa; ?></td>
<td><?php echo $datos_rel_malla_curso[0]->nombre_programa; ?></td>
<td><?php echo $datos_rel_malla_curso[0]->id_foco; ?></td>
<td><?php echo $datos_rel_malla_curso[0]->nombre_programa; ?></td>

<?php }?>

</tr>

<?php
}
    ?>
</table>
<?php
}
else if ($seccion == "DescargaPresencialImparticion") {
    $id_imparticion = Decodear3($get["ci"]);
    $id_curso = Decodear3($get["idc"]);
    $nombreCurso = BuscaCursoDadoImp($id_imparticion);
    $asistentes = ($get["asistentes"]);
    $id_empresa = $_SESSION["id_empresa"];
    $datos_empresa = DatosEmpresa($id_empresa);
//Traigo el listado de usuarios de la tabla inscripcion_usuarios
    $total_usuarios = IMPARTICION_DatosPorInscripcionEstado($id_imparticion, $id_empresa, $datos_empresa, $id_curso, $asistentes);



    $datos_curso = VerificoCursoPorEmpresa($id_curso, $id_empresa);
    $datos_rel_malla_curso = TraeMallaCursoConDatos($id_curso, $id_empresa);

    $fechahoy = date("Y-m-d") . " " . date("H:i:s");
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=" . $nombreCurso[0]->id_curso . "_" . $nombreCurso[0]->nombre_curso . "_" . $id_imparticion . "_Inscritos_" . $fechahoy . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    ?>
<table>
	<tr>
		<td>RUT</td>
</tr>
<?php
foreach ($total_usuarios as $usu) {

        ?>
						<tr>
						<td><?php echo $usu->rut; ?></td>
						</tr>

				<?php
		} ?>

			</table>
<?php

}
else if ($seccion == "adimpartiEl") {
    $id_empresa = $_SESSION["id_empresa"];

    $id_malla = $post["idmalla"];
    $codigo_imparticion = ($post["cod_imparticion"]);

    $datos_curso = VerificoCursoPorEmpresa($id_curso, $id_empresa);

    $id_audiencia = $post["id_audiencia"];

    IMPARTICION_CreaImparticion($id_empresa, $codigo_imparticion, $id_curso, $fecha_inicio, $fecha_termino, $direccion, $ciudad, $cupos, $id_audiencia, $tipo_audiencia, $datos_post,'','','');

//Ahora, por cada usuario de la Audiencia, inserto en tabla rel_malla_persona
    $datos_audiencia = ArmaQueryaudiencias($id_audiencia, $id_empresa);

    $total_usuarios = $datos_audiencia[3];
    foreach ($total_usuarios as $usu) {
        InsertaRelacionMallaPersonaImparticion($usu->rut, $id_empresa, $id_malla, $codigo_imparticion);
    }

    echo "
<script>
location.href='?sw=listaInscripciones2&i=" . Encodear($id_curso) . "';
</script>";
    exit;
}
else if ($seccion == "cargabase") {
    $empresa = "41";
//trae malla de empresa
    $Malla = TraeMallas($empresa);
    $i = 0;
    foreach ($Malla as $m) {
//trae cursos de malla
        $Curso = TraeCursos($m->id_malla, $empresa,'');

        foreach ($Curso as $c) {
            $i++;
//incripcion simple

            InsertaInscripcionCurso($m->id_malla, $c->id_curso, $empresa, "mininscr" . $i);

            $Usuarios = TraeUsuariosEmpresa($empresa);
            foreach ($Usuarios as $u) {
                InsertaInscripcionUsuario($u->rut, $c->id_curso, $empresa, "mininscr" . $i);
            }
        }
    }
}
else if ($seccion == "listcursos2Presenciales") {
    $id_empresa = $_SESSION["id_empresa"];

    $exportar_a_excel = $post["ex"];
    $pagina = $get["p"];
    if ($exportar_a_excel == "1") {
        $arreglo_post = $post;
        $fechahoy = date("Y-m-d") . " " . date("H:i:s");
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=" . $fechahoy . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $PRINCIPAL = ListadoCursosAdmin2(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/curso/entorno_listado_excel.html")), $id_empresa);
        echo ($PRINCIPAL);
    } else {
        $arreglo_post = $post;
        $PRINCIPAL = ListadoCursosAdmin2(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/curso/entorno_listado.html")), $id_empresa);
        echo CleanHTMLWhiteList($PRINCIPAL);exit;
    }
}
else if ($seccion == "Duplicarcurso") {
    $id_empresa = $_SESSION["id_empresa"];
    $id_curso = Decodear3($get["i"]);

    $total_cursos = Audiencias_totalcursosPorEmpresa($id_empresa);
//traigo los datos dado el id del curso
    $datos_curso_a_duplicar = VerificoCursoPorEmpresa($id_curso, $id_empresa);

    $datos_ultimo = Audiencias_PorEmpresaUltima($id_empresa);
    $prefijo = $datos_ultimo[0]->prefijo;
    $total = count($datos_ultimo);
    $total = $total + 1;
    $nuevo_id_curso = $prefijo . "_" . $total;

    InsertaCurso($datos_curso_a_duplicar[0]->nombre . "_duplicado",
        $datos_curso_a_duplicar[0]->descripcion,
        $datos_curso_a_duplicar[0]->modalidad,
        $datos_curso_a_duplicar[0]->tipo,
        $datos_curso_a_duplicar[0]->prerequisito,
        $datos_curso_a_duplicar[0]->archivo,
        $datos_curso_a_duplicar[0]->objetivo,
        $datos_curso_a_duplicar[0]->sence,
        $datos_curso_a_duplicar[0]->numero_horas,
        $datos_curso_a_duplicar[0]->cantidad_maxima_participantes,
        $datos_curso_a_duplicar[0]->rut_otec,
        $datos_curso_a_duplicar[0]->clasificacion,
        $datos_curso_a_duplicar[0]->cbc,
        $datos_curso_a_duplicar[0]->cod_identificador,
        $datos_curso_a_duplicar[0]->valor_hora,
        $datos_curso_a_duplicar[0]->valor_hora_sence,
        $nuevo_id_curso,
        $datos_curso_a_duplicar[0]->id_empresa,
        $datos_curso_a_duplicar[0]->contenidos_cursos,
        $datos_curso_a_duplicar[0]->nombre_curso_sence,
        $datos_curso_a_duplicar[0]->id_foco,
        $datos_curso_a_duplicar[0]->id_empresa_programa);

    if ($datos_curso_a_duplicar[0]->modalidad == 2) {
//Inserta en tabla asociada a
        InsertaRelacionMallaClasificacionCursoAdmin("presencial", $nuevo_id_curso, "presencial", $id_empresa, $datos_curso_a_duplicar[0]->id_foco, $datos_curso_a_duplicar[0]->id_empresa_programa);
    }


    echo "<script>location.href='?sw=accioncurso2&i=" . Encodear($nuevo_id_curso) . "';</script>";exit;
}
else if ($seccion == "Duplicarcurso") {
    $id_empresa = $_SESSION["id_empresa"];
    $id_curso = Decodear3($get["i"]);

    $total_cursos = Audiencias_totalcursosPorEmpresa($id_empresa);
//traigo los datos dado el id del curso
    $datos_curso_a_duplicar = VerificoCursoPorEmpresa($id_curso, $id_empresa);

    $datos_ultimo = Audiencias_PorEmpresaUltima($id_empresa);
    $prefijo = $datos_ultimo[0]->prefijo;
    $total = count($datos_ultimo);
    $total = $total + 1;
    $nuevo_id_curso = $prefijo . "_" . $total;

    InsertaCurso($datos_curso_a_duplicar[0]->nombre . "_duplicado",
        $datos_curso_a_duplicar[0]->descripcion,
        $datos_curso_a_duplicar[0]->modalidad,
        $datos_curso_a_duplicar[0]->tipo,
        $datos_curso_a_duplicar[0]->prerequisito,
        $datos_curso_a_duplicar[0]->archivo,
        $datos_curso_a_duplicar[0]->objetivo,
        $datos_curso_a_duplicar[0]->sence,
        $datos_curso_a_duplicar[0]->numero_horas,
        $datos_curso_a_duplicar[0]->cantidad_maxima_participantes,
        $datos_curso_a_duplicar[0]->rut_otec,
        $datos_curso_a_duplicar[0]->clasificacion,
        $datos_curso_a_duplicar[0]->cbc,
        $datos_curso_a_duplicar[0]->cod_identificador,
        $datos_curso_a_duplicar[0]->valor_hora,
        $datos_curso_a_duplicar[0]->valor_hora_sence,
        $nuevo_id_curso,
        $datos_curso_a_duplicar[0]->id_empresa,
        $datos_curso_a_duplicar[0]->contenidos_cursos,
        $datos_curso_a_duplicar[0]->nombre_curso_sence,
        $datos_curso_a_duplicar[0]->id_foco,
        $datos_curso_a_duplicar[0]->id_empresa_programa);

    if ($datos_curso_a_duplicar[0]->modalidad == 2) {
//Inserta en tabla asociada a
        InsertaRelacionMallaClasificacionCursoAdmin("presencial", $nuevo_id_curso, "presencial", $id_empresa, $datos_curso_a_duplicar[0]->id_foco, $datos_curso_a_duplicar[0]->id_empresa_programa);
    }


    echo "<script>location.href='?sw=accioncurso2&i=" . Encodear($nuevo_id_curso) . "';</script>";exit;
}
else if ($seccion == "DuplicarcursoT") {
    $id_empresa = $_SESSION["id_empresa"];
    $id_curso = Decodear3($get["i"]);

    $total_cursos = Audiencias_totalcursosPorEmpresa($id_empresa);
//traigo los datos dado el id del curso
    $datos_curso_a_duplicar = VerificoCursoPorEmpresa($id_curso, $id_empresa);

    $datos_ultimo = Audiencias_PorEmpresaUltima($id_empresa);
    $prefijo = $datos_ultimo[0]->prefijo;
    $total = count($datos_ultimo);
    $total = $total + 1;
    $nuevo_id_curso = $prefijo . "_" . $total;

    InsertaCurso($datos_curso_a_duplicar[0]->nombre . "_duplicado",
        $datos_curso_a_duplicar[0]->descripcion,
        $datos_curso_a_duplicar[0]->modalidad,
        $datos_curso_a_duplicar[0]->tipo,
        $datos_curso_a_duplicar[0]->prerequisito,
        $datos_curso_a_duplicar[0]->archivo,
        $datos_curso_a_duplicar[0]->objetivo,
        $datos_curso_a_duplicar[0]->sence,
        $datos_curso_a_duplicar[0]->numero_horas,
        $datos_curso_a_duplicar[0]->cantidad_maxima_participantes,
        $datos_curso_a_duplicar[0]->rut_otec,
        $datos_curso_a_duplicar[0]->clasificacion,
        $datos_curso_a_duplicar[0]->cbc,
        $datos_curso_a_duplicar[0]->cod_identificador,
        $datos_curso_a_duplicar[0]->valor_hora,
        $datos_curso_a_duplicar[0]->valor_hora_sence,
        $nuevo_id_curso,
        $datos_curso_a_duplicar[0]->id_empresa,
        $datos_curso_a_duplicar[0]->contenidos_cursos,
        $datos_curso_a_duplicar[0]->nombre_curso_sence,
        $datos_curso_a_duplicar[0]->id_foco,
        $datos_curso_a_duplicar[0]->id_empresa_programa);

    if ($datos_curso_a_duplicar[0]->modalidad == 4) {
//Inserta en tabla asociada a
        InsertaRelacionMallaClasificacionCursoAdmin("e_p_f", $nuevo_id_curso, "e_p_f", $id_empresa, $datos_curso_a_duplicar[0]->id_foco, $datos_curso_a_duplicar[0]->id_empresa_programa);
    }


    echo "<script>location.href='?sw=accioncursoT&i=" . Encodear($nuevo_id_curso) . "';</script>";exit;
}
else if ($seccion == "listcursosT") {
    
    $id_empresa = $_SESSION["id_empresa"];
    $exportar_a_excel = $post["ex"];
    $pagina = $get["p"];
    $excel = $get["excel"];
    
    if ($exportar_a_excel == "1" or $excel == "1") {
        $arreglo_post = $post;
        $fechahoy = date("Y-m-d") . " " . date("H:i:s");
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Cursos_T_" . $fechahoy . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        $PRINCIPAL = ListadoCursosAdmin2T(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/cursoT/entorno_listado_excel.html")), $id_empresa, $excel);
        echo ($PRINCIPAL);
        exit;
    } else {
        $arreglo_post = $post;
        $PRINCIPAL = ListadoCursosAdmin2T(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/cursoT/entorno_listado_cursos.html")), $id_empresa);
        echo CleanHTMLWhiteList($PRINCIPAL);exit;
    }
}
else if ($seccion == "listcursos2elearning") {

    $id_empresa = $_SESSION["id_empresa"];
    $exportar_a_excel = $post["ex"];
    $pagina = $get["p"];
    $excel = $get["excel"];


    if ($exportar_a_excel == "1" or $excel == "1") {
        $arreglo_post = $post;
        $fechahoy = date("Y-m-d") . " " . date("H:i:s");
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Cursos_" . $fechahoy . ".xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        $PRINCIPAL = ListadoCursosAdmin2Elearning(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/cursoelearning/entorno_listado_excel.html")), $id_empresa, $excel);
        echo ($PRINCIPAL);
        exit;
    } else {
        $arreglo_post = $post;
        $PRINCIPAL = ListadoCursosAdmin2Elearning(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/cursoelearning/entorno_listado_cursos.html")), $id_empresa);
        echo CleanHTMLWhiteList($PRINCIPAL);exit;
    }
}
else if ($seccion == "LmsexportarImparticionesXLS") {
    $id_empresa = $_SESSION["id_empresa"];
    $exportar_a_excel = $post["ex"];
    $pagina = $get["p"];
    $arreglo_post = $post;
    $fechahoy = date("Y-m-d") . " " . date("H:i:s");
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=Lista_Imparticiones_" . $fechahoy . ".xls");
    header("Pragma: no-cache");
    $PRINCIPAL = LmsexportarImparticionesXLS_fn(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/curso/entorno_listado_imparticiones_excel.html")), $id_empresa, $exportar_a_excel);
    echo ($PRINCIPAL);
    exit;
}
else if ($seccion == "LmsexportarCursosXLS") {
    $id_empresa = $_SESSION["id_empresa"];
    $exportar_a_excel = $post["ex"];
    $pagina = $get["p"];
    $arreglo_post = $post;
    $fechahoy = date("Y-m-d") . " " . date("H:i:s");
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=Cursos_" . $fechahoy . ".xls");
    header("Pragma: no-cache");
    $PRINCIPAL = LmsexportarCursosXLS_fn(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/curso/entorno_listado_cursos_excel.html")), $id_empresa, $exportar_a_excel);
    echo ($PRINCIPAL);
    exit;
}
else if ($seccion == "MuestraBloqueDetalleCurso") {

    $id_curso = $post["id_curso"];
    $id_empresa = $_SESSION["id_empresa"];

    $PRINCIPAL = ListadoCursosAdmin2(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/cursospresenciales/entorno_listado_imparticion.html")), $id_empresa, "", $id_curso);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "MuestraBloqueMallaSeleccionado") {
    $id_malla = $post["id_malla"];
    $id_empresa = $_SESSION["id_empresa"];

    $formulario = file_get_contents("views/capacitacion/imparticion/formulario_ingresa_elearning.html");

    $formulario = FormularioImparticion(FuncionesTransversalesAdmin($formulario), $id_empresa);
    $formulario = ListadocMallas($formulario, $id_empresa, "imparticiones", $id_malla);

    $formulario = str_replace("{VALUE_MALLA}", $id_malla, $formulario);

    echo $formulario;
}
else if ($seccion == "MuestraBloqueCursoSeleccionado") {
    $id_curso = $post["id_curso"];
    $id_empresa = $_SESSION["id_empresa"];
    $datos_curso = VerificoCursoPorEmpresa($id_curso, $id_empresa);
    echo $datos_curso[0]->modalidad;
    if ($datos_curso[0]->modalidad == 1) {
        $formulario = file_get_contents("views/capacitacion/imparticion/formulario_ingresa_elearning.html");
    } else if ($datos_curso[0]->modalidad == 2 && $datos_curso[0]->sence == "si") {
        $formulario = file_get_contents("views/capacitacion/imparticion/formulario_ingresa_presencial_sence.html");
    } else if ($datos_curso[0]->modalidad == 2 && $datos_curso[0]->sence == "no") {
        $formulario = file_get_contents("views/capacitacion/imparticion/formulario_ingresa_presencial.html");
    } else if ($datos_curso[0]->modalidad == 2) {
        $formulario = file_get_contents("views/capacitacion/imparticion/formulario_ingresa_presencial.html");
    }

    $formulario = FormularioImparticion(FuncionesTransversalesAdmin($formulario), $id_empresa,'');
    $formulario = ListadoCursosAdmin2(FuncionesTransversalesAdmin($formulario), $id_empresa, "", $id_curso,'');
    $formulario = str_replace("{VALUE_COD_CURSO}", $id_curso, $formulario);
	header('Content-Type: text/plain');
    echo $formulario;
}
else if ($seccion == "MuestraBloqueCursoSeleccionadot") {
    $id_curso = $post["id_curso"];
    $id_empresa = $_SESSION["id_empresa"];
    $datos_curso = VerificoCursoPorEmpresa($id_curso, $id_empresa);
    echo $datos_curso[0]->modalidad;
    if ($datos_curso[0]->modalidad == 1) {
        $formulario = file_get_contents("views/capacitacion/imparticion/formulario_ingresa_elearning.html");
    } else if ($datos_curso[0]->modalidad == 2 && $datos_curso[0]->sence == "si") {
        $formulario = file_get_contents("views/capacitacion/imparticion/formulario_ingresa_presencial_sence.html");
    } else if ($datos_curso[0]->modalidad == 2 && $datos_curso[0]->sence == "no") {
        $formulario = file_get_contents("views/capacitacion/imparticion/formulario_ingresa_presencial.html");
    } else if ($datos_curso[0]->modalidad == 2) {
        $formulario = file_get_contents("views/capacitacion/imparticion/formulario_ingresa_presencial.html");
    }

    $formulario = FormularioImparticion(FuncionesTransversalesAdmin($formulario), $id_empresa);
    $formulario = ListadoCursosAdmin2(FuncionesTransversalesAdmin($formulario), $id_empresa, "", $id_curso);
    $formulario = str_replace("{VALUE_COD_CURSO}", $id_curso, $formulario);
    echo $formulario;
}
else if ($seccion == "MuestraBloqueAudienciaSeleccionada") {
    $id_empresa = $_SESSION["id_empresa"];
    $id_audiencia = $post["id_audiencia"];
    $PRINCIPAL = ListadoAudiencias(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/imparticion/entorno_listado_formulario_imparticion.html")), $id_empresa, $id_audiencia);
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "listmallaclasificacioncursos") {
    $PRINCIPAL = ListadoMallaClasificacionCurso(FuncionesTransversalesAdmin(file_get_contents("views/capacitacion/malla/entorno_listado_malla_clasificacion_curso.html")), $get["m"]);

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "addNuevaRelacionMasivo") {
    $id_empresa = $_SESSION["id_empresa"];
    $id_proceso = $get["i"];

    CancelaProcesamientoPrevioRelaciones($_SESSION["id_empresa"]);
    extract($post);
    $error_grave = "error";

    if ($action == "upload") {
//cargamos el archivo al servidor con el mismo nombre
        //solo le agregue el sufijo tmp_ev_
        $archivo = $_FILES['excel']['name'];VerificaExtensionFilesAdmin($_FILES["excel"]);
        $tipo = $_FILES['excel']['type'];
        $destino = "tmp_ev_" . $archivo;
        if (copy($_FILES['excel']['tmp_name'], $destino)) {

        } else {
            $error_grave = "Error Al subir Archivo<br>";
        }
        if (file_exists("tmp_ev_" . $archivo)) {
            require_once 'clases/PHPExcel.php';
            require_once 'clases/PHPExcel/Reader/Excel2007.php';

            $objReader = new PHPExcel_Reader_Excel2007();
            $objPHPExcel = $objReader->load("tmp_ev_" . $archivo);
            $objFecha = new PHPExcel_Shared_Date();
            $objPHPExcel->setActiveSheetIndex(0);
            $HojaActiva = $objPHPExcel->getActiveSheet();
            $total_filas = $HojaActiva->getHighestRow();
            $lastColumn = $HojaActiva->getHighestColumn();
            $total_columnas = PHPExcel_Cell::columnIndexFromString($lastColumn);
            $UltimaColumna = "A";
            $j = 0;
            $_DATOS_EXCEL = array();

//Obtengo los nombres de los campos
            for ($i = 0; $i <= $total_columnas; $i++) {
                $_campo[$i] = trim(($HojaActiva->getCell($UltimaColumna . "1")->getCalculatedValue()));
                $UltimaColumna++;
            }
//Obtengo datos de filas
            for ($fila = 2; $fila <= $total_filas; $fila++) {
                for ($columna = 0; $columna <= $total_columnas - 1; $columna++) {
                    $_DATOS_EXCEL[$j][$_campo[$columna]] = trim($HojaActiva->getCellByColumnAndRow($columna, $fila)->getCalculatedValue());
                }
                $j++;
            }
        } else {
            $error_grave = "Necesitas primero importar el archivo";
        }

        $total_errores = 0;
        $total_actualizar = 0;
        $total_insertar = 0;
        $linea = 2;
        foreach ($_DATOS_EXCEL as $key => $item) {
            $coma = "";
            $values = "";
            $registros = "";
            $rut = "";
            $error_columna = "";
            $parar = 0;
            $error_grave = "";
            foreach ($item as $key2 => $valor) {
                $values .= $coma . $key2;
                $registros .= $coma . "'" . $valor . "'";
                $coma = ",";
                if ($key2 == "evaluado" && $valor != "") {
                    $rut = $valor;
                }
            }

            if ($rut != "") {
                $verifico_registro = DatosTablaRelacion($rut);

                InsertaRelacionTemporal($values, $registros, $id_empresa, Decodear3($id_proceso));
                $total_insertar++;

                if ($error_grave) {
                    break;
                }
            } else {
                $lineaT .= "<p>Columna [rut] - Linea [" . $linea . "]</p>";
                $total_errores++;
                $mensaje_errores = "Se encontraron errores en los datos!";
            }
            $linea++;
        }

//CARGA VISTA

        $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/sgd_relaciones/formulario_relaciones_masivo.html"));

        $PRINCIPAL = str_replace("{ID_EMPRESA}", $_SESSION["id_empresa"], $PRINCIPAL);
        $PRINCIPAL = str_replace("{ID_PROCESO}", $get["i"], $PRINCIPAL);
        $totalrelaciones = CuentaRelaciones($_SESSION["id_empresa"], Decodear3($id_proceso));

        $PRINCIPAL = str_replace("{TOTALU}", colocarPesos($totalrelaciones), $PRINCIPAL);

        if (!$error_grave) {
            $PRINCIPAL = str_replace("{DISPLAYFORM1}", "", $PRINCIPAL);
            $PRINCIPAL = str_replace("{DISPLAYFORM2}", "none", $PRINCIPAL);
            $PRINCIPAL = str_replace("{DISPLAYERROR}", "display:none;", $PRINCIPAL);
        } else {
            $PRINCIPAL = str_replace("{DISPLAYFORM1}", "display:none;", $PRINCIPAL);
            $PRINCIPAL = str_replace("{DISPLAYFORM2}", "inline", $PRINCIPAL);
            $PRINCIPAL = str_replace("{DISPLAYERROR}", "", $PRINCIPAL);
            $total_errores = 0;
            $total_actualizar = 0;
            $total_insertar = 0;
            $lineaT = "";
        }

        $PRINCIPAL = str_replace("{TITULO2}", "Vista previa", $PRINCIPAL);
        $PRINCIPAL = str_replace("{TOT_ACTUALIZAR}", "", $PRINCIPAL);
        $PRINCIPAL = str_replace("{TOT_INSERTAR}", $total_insertar, $PRINCIPAL);
        $PRINCIPAL = str_replace("{TOT_ERRORES}", $total_errores, $PRINCIPAL);
        $PRINCIPAL = str_replace("{ERRORES}", $lineaT, $PRINCIPAL);
        $PRINCIPAL = str_replace("{ERROR_GRAVE}", $error_grave, $PRINCIPAL);

        if (!$error_grave) {
            $PRINCIPAL = str_replace("{ENTORNO_PREVIA}", (ListadoRelacionesMasivosPrevio($id_empresa)), $PRINCIPAL);
        } else {
            $PRINCIPAL = str_replace("{ENTORNO_PREVIA}", "", $PRINCIPAL);
        }
        echo CleanHTMLWhiteList($PRINCIPAL);exit;
    } else {

        $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/sgd_relaciones/formulario_relaciones_masivo.html"));

        $PRINCIPAL = str_replace("{ID_EMPRESA}", $_SESSION["id_empresa"], $PRINCIPAL);
        $PRINCIPAL = str_replace("{ID_PROCESO}", $id_proceso, $PRINCIPAL);
        $totalrelaciones = CuentaRelaciones($_SESSION["id_empresa"], Decodear3($id_proceso));
        $PRINCIPAL = str_replace("{DISPLAYFORM1}", "display:none;", $PRINCIPAL);
        $PRINCIPAL = str_replace("{DISPLAYFORM2}", "inline", $PRINCIPAL);
        $PRINCIPAL = str_replace("{ENTORNO_PREVIA}", "", $PRINCIPAL);
        $PRINCIPAL = str_replace("{TITULO2}", "Formulario de subida", $PRINCIPAL);
        $PRINCIPAL = str_replace("{TOTALU}", colocarPesos($totalrelaciones), $PRINCIPAL);
        $PRINCIPAL = str_replace("{TOT_ACTUALIZAR}", "0", $PRINCIPAL);
        $PRINCIPAL = str_replace("{TOT_INSERTAR}", "0", $PRINCIPAL);
        $PRINCIPAL = str_replace("{TOT_ERRORES}", "0", $PRINCIPAL);
        $PRINCIPAL = str_replace("{ERRORES}", "", $PRINCIPAL);
        $PRINCIPAL = str_replace("{DISPLAYERROR}", "display:none;", $PRINCIPAL);

        echo CleanHTMLWhiteList($PRINCIPAL);exit;
    }
}
else if ($seccion == "addIncripcionesCierre") {
    $id_empresa = $_SESSION["id_empresa"];

    CancelaProcesamientoPrevioInscripcionCierre($_SESSION["id_empresa"]);
    extract($post);
    $error_grave = "error";

    if ($action == "upload") {
//cargamos el archivo al servidor con el mismo nombre
        //solo le agregue el sufijo tmp_ev_
        $archivo = $_FILES['excel']['name'];VerificaExtensionFilesAdmin($_FILES["excel"]);
        $tipo = $_FILES['excel']['type'];
        $destino = "tmp_ev_" . $archivo;
        if (copy($_FILES['excel']['tmp_name'], $destino)) {

        } else {
            $error_grave = "Error Al subir Archivo<br>";
        }

        if (file_exists("tmp_ev_" . $archivo)) {
            require_once 'clases/PHPExcel.php';
            require_once 'clases/PHPExcel/Reader/Excel2007.php';

            $objReader = new PHPExcel_Reader_Excel2007();
            $objPHPExcel = $objReader->load("tmp_ev_" . $archivo);
            $objFecha = new PHPExcel_Shared_Date();
            $objPHPExcel->setActiveSheetIndex(0);
            $HojaActiva = $objPHPExcel->getActiveSheet();
            $total_filas = $HojaActiva->getHighestRow();
            $lastColumn = $HojaActiva->getHighestColumn();
            $total_columnas = PHPExcel_Cell::columnIndexFromString($lastColumn);
            $UltimaColumna = "A";
            $j = 0;
            $_DATOS_EXCEL = array();
//Obtengo los nombres de los campos
            for ($i = 0; $i <= $total_columnas; $i++) {
                $_campo[$i] = trim(($HojaActiva->getCell($UltimaColumna . "1")->getCalculatedValue()));
                $UltimaColumna++;
            }

//Obtengo datos de filas
            for ($fila = 2; $fila <= $total_filas; $fila++) {
                for ($columna = 0; $columna <= $total_columnas - 1; $columna++) {
                    $_DATOS_EXCEL[$j][$columna + 1] = (trim($HojaActiva->getCellByColumnAndRow($columna, $fila)->getCalculatedValue()));
                }
                $j++;
            }
        } else {
            $error_grave = "Necesitas primero importar el archivo";
        }

        $total_errores = 0;
        $total_actualizar = 0;
        $total_insertar = 0;
        $usuario_inexistente = 0;
        $curso_inexistente = 0;
        $linea = 2;

        for ($l = 1; $l <= count($_DATOS_EXCEL); $l++) {
            $rut = $_DATOS_EXCEL[$l - 1][1];

            $porcionidcurso = $_DATOS_EXCEL[$l - 1][2];
            $Arrayid_curso = explode(".", $porcionidcurso);
            $id_curso = $Arrayid_curso[0];

            $nota = $_DATOS_EXCEL[$l - 1][3];
            $porcentaje_asistencia = $_DATOS_EXCEL[$l - 1][4];
            $estado = ($_DATOS_EXCEL[$l - 1][5]);
            $comentario = ($_DATOS_EXCEL[$l - 1][6]);
            $fecha_inicio = $_DATOS_EXCEL[$l - 1][7];
            $fecha_termino = $_DATOS_EXCEL[$l - 1][8];
            $ubicacion = ($_DATOS_EXCEL[$l - 1][9]);
            $region = ($_DATOS_EXCEL[$l - 1][10]);
            $lugar_ejecucion = ($_DATOS_EXCEL[$l - 1][11]);
            $rut_jefe = $_DATOS_EXCEL[$l - 1][12];
            $nombre_jefe = ($_DATOS_EXCEL[$l - 1][13]);
            $rut_entrenador = $_DATOS_EXCEL[$l - 1][14];
            $nombre_entrenador = ($_DATOS_EXCEL[$l - 1][15]);
            $observaciones = ($_DATOS_EXCEL[$l - 1][16]);
//Veo si existe el usuario
            $existe_usuario = DatosTablaUsuarioPorEmpresaV2($rut, $_SESSION["id_empresa"]);
            if (!$existe_usuario) {

                $usuario_inexistente++;
                $accion = "NoExisteUsuario";
            } else {
//Veo si existe el curso
                $existe_curso = VerificoCursoPorEmpresa($id_curso, $_SESSION["id_empresa"]);
                if (!$existe_curso) {
                    $curso_inexistente++;
                    $accion = "NoExisteCurso";
                } else {
//Veso si el curso, rut y empresa existen.
                    $existe_cursoempresarut = VerificaCursoEstaEnCierrePorEmpresa($id_curso, $rut, $_SESSION["id_empresa"]);
                    if ($existe_cursoempresarut) {
                        $accion = "Actualiza";
                        $total_actualizar++;
                    } else {
                        $accion = "Inserta";
                        $total_insertar++;
                    }
                }
            }
//Inserta en tabla temporal
            InsertaDatosTablaTemporalCierreCapacitaciones($rut, $id_curso, $nota, $porcentaje_asistencia,
                $estado, $comentario, $fecha_inicio, $fecha_termino, $ubicacion, $region, $lugar_ejecucion, $rut_jefe,
                $nombre_jefe, $rut_entrenador, $nombre_entrenador, $observaciones, $accion, $_SESSION["id_empresa"]);
        }


//CARGA VISTA

        $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/inscripcion/cierre/formulario_inscripcion_cierre_masivo_previa.html"));
        $PRINCIPAL = str_replace("{ID_EMPRESA}", $_SESSION["id_empresa"], $PRINCIPAL);
        $PRINCIPAL = str_replace("{ID_PROCESO}", $get["i"], $PRINCIPAL);
        $totalrelaciones = CuentaRelaciones($_SESSION["id_empresa"], Decodear3($id_proceso));

        $PRINCIPAL = str_replace("{ID_EMPRESA}", $_SESSION["id_empresa"], $PRINCIPAL);
        $PRINCIPAL = str_replace("{ID_PROCESO}", $id_proceso, $PRINCIPAL);
        $totalrelaciones = CuentaRelaciones($_SESSION["id_empresa"], Decodear3($id_proceso));

        $PRINCIPAL = str_replace("{TITULO2}", "Formulario de subida", $PRINCIPAL);

        $PRINCIPAL = str_replace("{TOTALU}", count(TotalCierresInscripcionesPorEmpresa($id_empresa)), $PRINCIPAL);
        $PRINCIPAL = str_replace("{TOT_ACTUALIZACIONES}", $total_actualizar, $PRINCIPAL);
        $PRINCIPAL = str_replace("{TOT_INSERTAR}", $total_insertar, $PRINCIPAL);

        $PRINCIPAL = str_replace("{TOT_USU_INEX}", $usuario_inexistente, $PRINCIPAL);
        $PRINCIPAL = str_replace("{TOT_CUR_INEX}", $curso_inexistente, $PRINCIPAL);

        $PRINCIPAL = str_replace("{TOT_ERRORES}", "0", $PRINCIPAL);
        $PRINCIPAL = str_replace("{ERRORES}", "", $PRINCIPAL);
        $PRINCIPAL = str_replace("{DISPLAYERROR}", "display:none;", $PRINCIPAL);


        $PRINCIPAL = str_replace("{ENTORNO_PREVIA}", ListadoCierresMasivosPrevio($id_empresa), $PRINCIPAL);
        echo CleanHTMLWhiteList($PRINCIPAL);exit;
    } else {

        $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/inscripcion/cierre/formulario_inscripcion_cierre_masivo.html"));

        $PRINCIPAL = str_replace("{ID_EMPRESA}", $_SESSION["id_empresa"], $PRINCIPAL);
        $PRINCIPAL = str_replace("{ID_PROCESO}", $id_proceso, $PRINCIPAL);
        $totalrelaciones = CuentaRelaciones($_SESSION["id_empresa"], Decodear3($id_proceso));
        $PRINCIPAL = str_replace("{DISPLAYFORM1}", "display:none;", $PRINCIPAL);
        $PRINCIPAL = str_replace("{DISPLAYFORM2}", "inline", $PRINCIPAL);
        $PRINCIPAL = str_replace("{ENTORNO_PREVIA}", "", $PRINCIPAL);
        $PRINCIPAL = str_replace("{TITULO2}", "Formulario de subida", $PRINCIPAL);

        $PRINCIPAL = str_replace("{TOTALU}", count(TotalCierresInscripcionesPorEmpresa($id_empresa)), $PRINCIPAL);
        $PRINCIPAL = str_replace("{TOT_ACTUALIZAR}", "0", $PRINCIPAL);
        $PRINCIPAL = str_replace("{TOT_INSERTAR}", "0", $PRINCIPAL);
        $PRINCIPAL = str_replace("{TOT_ERRORES}", "0", $PRINCIPAL);
        $PRINCIPAL = str_replace("{ERRORES}", "", $PRINCIPAL);
        $PRINCIPAL = str_replace("{DISPLAYERROR}", "display:none;", $PRINCIPAL);

        echo CleanHTMLWhiteList($PRINCIPAL);exit;
    }
}
else if ($seccion == "aceprelaciones") {
    AceptaProcesamientoPrevioRelaciones($_SESSION["id_empresa"]);
    $id_proceso = $get["i"];
    echo "
<script>
location.href='?sw=GestionRelacionesPorProceso&i=" . $id_proceso . "';
</script>";
}
else if ($seccion == "licomentarios") {
    $id_empresa = $_SESSION["id_empresa"];
    $PRINCIPAL = (ListadoComentariosAdmin(FuncionesTransversalesAdmin(file_get_contents("views/noticias/listado_comentarios.html")), $id_empresa));
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "sabanaRespuestasTrivias") {
    $id_evaluacion = $get["idEval"];

    //Traigo las respuestas de esta evaluacion
    $preguntas = ListadoDePreguntasDadaEvaluacionOrdenadas($id_evaluacion);
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=Trivia_" . $id_evaluacion . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    ?>
<table>
<tr>
<td>Rut</td>
<td>Nombre Colaborador</td>
<td>Trivia</td>
<td>Pregunta</td>
<td>Alternativa Seleccionada</td>
<td>Correcta / Incorrecta</td>
</tr>
<?php
$respuestas = ListadoRespuestasTriviaVersion1($id_evaluacion);
    foreach ($respuestas as $resp) {
        if ($resp->correcta == 1) {$text_correcta = "Correcta";
        }
        if ($resp->correcta != 1) {$text_correcta = "Incorrecta";
        }

        echo "<tr>";
        echo "<td>" . $resp->rut . "</td>";
        echo "<td>" . $resp->nombre_completo . "</td>";
        echo "<td>" . $resp->nombre_evaluacion . "</td>";
        echo "<td>" . strip_tags(htmlspecialchars_decode($resp->pregunta)) . "</td>";
        echo "<td>" . strip_tags(htmlspecialchars_decode($resp->alternativa)) . "</td>";
        echo "<td>" . strip_tags(htmlspecialchars_decode($text_correcta)) . "</td>";

        echo "</tr>";
    }
    ?>

</table>
<?php
}
else if ($seccion == "viewEval") {
    $idEval = $get["idEval"];
    $programa = $get["programa"];

    if (!$idEval) {
        //Creo la evaluacion
        $id_objeto = Decodear3($get["ido"]);
        $id_curso = Decodear3($get["idc"]);
        $id_empresa = $_SESSION["id_empresa"];
        InsertaEvaluacionDesdeAdmin("Trivia " . $id_objeto . " " . $id_curso, $id_empresa, $id_curso, $id_objeto);
    }
    
    if ($programa != '{ID_PROGRAMA}') {
        $PRINCIPAL = jListarPreguntas(FuncionesTransversalesAdmin(file_get_contents("views/evaluaciones/admin/listPreg.html")), $idEval, $programa);
    } else {
        $PRINCIPAL = jListarPreguntas(FuncionesTransversalesAdmin(file_get_contents("views/evaluaciones/admin/listPreg_sinPrograma.html")), $idEval, $programa);
    }
    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "viewEvalMediciones") {
    $idEval = $get["idEval"];
    $programa = $get["programa"];

    if (!$idEval) {
        //Creo la evaluacion
        $id_objeto = Decodear3($get["ido"]);
        $id_curso = Decodear3($get["idc"]);
        $id_empresa = $_SESSION["id_empresa"];
        InsertaEvaluacionDesdeAdmin("Trivia " . $id_objeto . " " . $id_curso, $id_empresa, $id_curso, $id_objeto);
    }

    $PRINCIPAL = jListarPreguntasMedicion(FuncionesTransversalesAdmin(file_get_contents("views/mediciones/admin/listPreg_sinPrograma.html")), $idEval, $programa);

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "viewEvalEncuestasLB") {
    $idEval = $get["idEval"];
    $programa = $get["programa"];



    $PRINCIPAL = ListarPreguntasEncuestas_LB(FuncionesTransversalesAdmin(file_get_contents("views/encuestas_creacion_lb/admin/listPreg_sinPrograma.html")), $idEval, $programa);

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "ActualizarNotasEval") {
    $idEval = $get["idEval"];
    $id_objeto = Decodear3($get["ido"]);
    $id_curso = Decodear3($get["idc"]);
    $datoscurso = Datos_curso($id_curso);
    $datosobjeto = Datos_objeto($id_objeto);
    $id_empresa = $_SESSION["id_empresa"];


    ActualizarnotasIdObjeto($id_objeto, $id_curso, $idEval, $id_empresa);
}
else if ($seccion == "Download_Respuestas_Eval") {
    $idEval = $get["idEval"];
    $id_objeto = Decodear3($get["ido"]);
    $id_curso = Decodear3($get["idc"]);
    $datoscurso = Datos_curso($id_curso);
    $datosobjeto = Datos_objeto($id_objeto);

    $nombrecurso = ($datoscurso[0]->nombre);
    $nombreobjeto = ($datosobjeto[0]->titulo);

    $fecha_inicio = $datosobjeto[0]->fecha_inicio;
    $fecha_termino = $datosobjeto[0]->fecha_termino;

    require_once 'clases/PHPExcel.php';
    $objPHPExcel = new PHPExcel();

    $styleArray = array(
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => '000000'),
            'size' => 12,
            'name' => 'Calibri'
        ));

    $objPHPExcel->getProperties()
        ->setCreator("GO Partners")
        ->setLastModifiedBy("GO Partners")
        ->setTitle("Respuestas por Evaluación")
        ->setSubject("Respuestas por Evaluación")
        ->setDescription("Respuestas por Evaluación")
        ->setKeywords("Excel Office 2007 openxml php")
        ->setCategory("Respuestas por Evaluación");

    $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFill()->getStartColor()->setARGB('E5E5E5');

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A1", "Resultados por curso / $nombrecurso / $nombreobjeto / IdEval: $idEval ");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B1", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B1", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C1", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D1", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E1", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A2", "$fecha_inicio $fecha_termino");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B2", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C2", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D2", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E2", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G4", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G5", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G6", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A6", "RUT");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B6", "NOMBRE");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C6", "CARGO");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D6", "EMAIL");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E6", "GERENCIA");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F6", "GERENCIAR2");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G6", "GERENCIAR3");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H6", "RESULTADO");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I6", "META");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J6", "BRECHA");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K6", "RESPUESTA CORRECTA");

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K7", "PORCENTAJE RESPUESTAS CORRECTAS");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K8", "RESPUESTAS CORRECTAS");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K9", "RESPUESTAS INCORRECTAS");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K10", "PREGUNTAS OMITIDAS");

    $TodasPreguntas = BuscaPreguntaDadaEvaluacion($idEval);

    $letra = "K";
    foreach ($TodasPreguntas as $pregunta) {
        $letra++;
        
        $questionid++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra . "4", ($questionid));
    }

    $letra = "K";
    foreach ($TodasPreguntas as $pregunta) {
        $letra++;
        
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra . "5", ($pregunta->pregunta));
    }

    $letra = "K";
    foreach ($TodasPreguntas as $pregunta) {
        $letra++;
        
        $questionid++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra . "6", ($pregunta->Altcorrecta));
    }

    $i = 10;



    if ($idEval != "" and $id_objeto == "" and $id_curso == "") {

        $cuenta = 0;
        $ArrayObjetos = BuscaObjetosEval_IDeEval($idEval);
        foreach ($ArrayObjetos as $ArrayObjeto) {
            $ArregloObj[$cuenta] = $ArrayObjeto->id;
            $cuenta++;
        }
        $esarreglo = 1;
    } else {
        $ArregloObj = $id_objeto;
        $esarreglo = 0;
    }


    $GroupRuts = BuscaUsuariosRespondieronEval($ArregloObj, $esarreglo);
    foreach ($GroupRuts as $user_unico) {
        $i++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $i, ($user_unico->rut_completo));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B" . $i, ($user_unico->nombre_completo));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C" . $i, ($user_unico->cargo));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D" . $i, ($user_unico->email));

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E" . $i, ($user_unico->gerencia));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F" . $i, ($user_unico->gerenciaR2));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G" . $i, ($user_unico->gerenciaR3));

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H" . $i, ($user_unico->nota));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I" . $i, ($user_unico->nota_aprobacion));
        $brecha = "";
        if ($user_unico->nota_aprobacion != '' and $user_unico->nota != '') {
            $brecha = $user_unico->nota - $user_unico->nota_aprobacion;
        } else {
           $brecha="";
        }
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J" . $i, ($brecha));

        $letra = "K";
        foreach ($TodasPreguntas as $pregunta) {
            $Respuestas_UsuariosIDPreg = BuscaRespuestasUsuarioObjetoIdPregunta($user_unico->rut, $pregunta->id, $ArregloObj, $esarreglo);

            
            $letra++;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra . $i, ($Respuestas_UsuariosIDPreg[0]->alternativa . $Respuestas_UsuariosIDPreg[0]->comentarios));
        }
    }

    $GroupRutsSinRespuestas = BuscaUsuariosRespondieronEvalSinRespuestas($ArregloObj, $esarreglo);
    foreach ($GroupRutsSinRespuestas as $user_unico_sinresp) {
        $i++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $i, ($user_unico_sinresp->rut_completo));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B" . $i, ($user_unico_sinresp->nombre_completo));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C" . $i, ($user_unico_sinresp->cargo));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D" . $i, ($user_unico_sinresp->email));

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E" . $i, ($user_unico_sinresp->gerencia));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F" . $i, ($user_unico_sinresp->gerenciaR2));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G" . $i, ($user_unico_sinresp->gerenciaR3));

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H" . $i, ($user_unico_sinresp->nota));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I" . $i, ($user_unico_sinresp->nota_aprobacion));
    }

    $k = $i + 2;
    $GroupRuts = BuscaUsuariosRespondieronEval($ArregloObj, $esarreglo);
    foreach ($GroupRuts as $user_unico) {
        $k++;

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $k, ($user_unico->rut_completo));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B" . $k, ($user_unico->nombre_completo));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C" . $k, ($user_unico->cargo));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D" . $k, ($user_unico->email));

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E" . $k, ($user_unico->gerencia));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F" . $k, ($user_unico->gerenciaR2));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G" . $k, ($user_unico->gerenciaR3));

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H" . $k, ($user_unico->nota));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I" . $k, ($user_unico->nota_aprobacion));
        $brecha = "";
        if ($user_unico->nota_aprobacion != '' and $user_unico->nota != '') {
            $brecha = $user_unico->nota - $user_unico->nota_aprobacion;
        } else {
           $brecha="";
        }

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J" . $k, ($brecha));

        $letra = "K";

        foreach ($TodasPreguntas as $pregunta) {
            $Respuestas_UsuariosIDPreg = BuscaRespuestasUsuarioObjetoIdPregunta($user_unico->rut, $pregunta->id, $ArregloObj, $esarreglo);

            $letra++;
            $txt = "";

            
            if ($Respuestas_UsuariosIDPreg[0]->correcta === '1') {
                $txt = "1";
                $arreglo_preguntas_correctas_pregunta[$pregunta->id]++;
            } elseif ($Respuestas_UsuariosIDPreg[0]->correcta === '0') {
                $txt = "0";
                $arreglo_preguntas_incorrectas_pregunta[$pregunta->id]++;
            } else {
                $txt = "";
            }
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra . $k, ($txt));
        }
    }

    $GroupRutsSinRespuestas = BuscaUsuariosRespondieronEvalSinRespuestas($ArregloObj, $esarreglo);
    foreach ($GroupRutsSinRespuestas as $user_unico_sinresp) {
        $k++;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $k, ($user_unico_sinresp->rut_completo));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B" . $k, ($user_unico_sinresp->nombre_completo));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C" . $k, ($user_unico_sinresp->cargo));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D" . $k, ($user_unico_sinresp->email));

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E" . $k, ($user_unico_sinresp->gerencia));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F" . $k, ($user_unico_sinresp->gerenciaR2));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G" . $k, ($user_unico_sinresp->gerenciaR3));

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H" . $k, ($user_unico_sinresp->nota));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I" . $k, ($user_unico_sinresp->nota_aprobacion));
    }




    $letra = "l";

    foreach ($TodasPreguntas as $pregunta) {
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra . "7", round((($arreglo_preguntas_correctas_pregunta[$pregunta->id] * 100)) / ($arreglo_preguntas_correctas_pregunta[$pregunta->id] + $arreglo_preguntas_incorrectas_pregunta[$pregunta->id])) . "%");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra . "8", $arreglo_preguntas_correctas_pregunta[$pregunta->id] + 0);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra . "9", $arreglo_preguntas_incorrectas_pregunta[$pregunta->id] + 0);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra . "10", "0");
        $letra++;
    }

    $sheet = $objPHPExcel->getActiveSheet();
    $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(true);

// Renombrar Hoja
    $objPHPExcel->getActiveSheet()->setTitle('Respuestas');

// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
    $objPHPExcel->setActiveSheetIndex(0);

// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
    $fechahoy = date("Y-m-d") . "_" . date("H:i:s");
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Resultados_' . $idEval . '_' . $id_curso . '_' . $id_objeto . '.xlsx"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
}
else if ($seccion == "Download_Respuestas_EvalMedicion_BK") {
    $idMed = $get["idMed"];
    
    $DatosMed = DatosMedicionAdmin($idMed);
    $nombre_medicion = $DatosMed[0]->nombre;
    $tipo_individual = $DatosMed[0]->tipo_medicion;
    

    require_once 'clases/PHPExcel.php';
    $objPHPExcel = new PHPExcel();
    $styleArray = array(
        'font' => array(
            'bold' => true,
            'color' => array('rgb' => '000000'),
            'size' => 12,
            'name' => 'Calibri'
        ));

    $objPHPExcel->getProperties()
        ->setCreator("GO Partners")
        ->setLastModifiedBy("GO Partners")
        ->setTitle("Respuestas por Medición")
        ->setSubject("Respuestas por Medición")
        ->setDescription("Respuestas por Medición")
        ->setKeywords("Excel Office 2007 openxml php")
        ->setCategory("Respuestas por Medición");

    $objPHPExcel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFill()->getStartColor()->setARGB('E8E8E8E8');

    $objPHPExcel->getActiveSheet()->getStyle("A6:L6")->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A6:L6')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
    $objPHPExcel->getActiveSheet()->getStyle('A6:L6')->getFill()->getStartColor()->setARGB('E8E8E8E8');

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A1", "Resultados por Medición $nombremedicion (IdMed: $idMed) ");

    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B1", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B1", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C1", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D1", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E1", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A2", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B2", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C2", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D2", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E2", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G4", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G5", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G6", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A6", "RUT");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B6", "NOMBRE");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C6", "CARGO");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D6", "EMAIL");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E6", "GERENCIA");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F6", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G6", "");

    if ($tipo_individual == "INDIVIDUAL") {
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H6", "RUT_JEFE");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I6", "NOMBRE_JEFE");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J6", "CARGO_JEFE");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K6", "FECHA_MEDICION");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L6", "FECHA_RESPUESTA");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("M6", "COMENTARIO");
    } else {

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H6", "");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I6", "");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J6", "FECHA_CARGA");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K6", "GRUPO");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L6", "FECHA_RESPUESTAS");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("M6", "COMENTARIO");
    }
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L7", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L8", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L9", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L10", "");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L10", "");
    $i = 6;

        $cuenta_arrayfecha++;
        $i++;
        

        $TodasPreguntas = BuscaPreguntaDadaMedicion($idMed);
        
        $letra = "M";foreach ($TodasPreguntas as $pregunta) {
            $letra++;
            $questionid++;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra . "5", "Preg_" . ($questionid));
        }

        $letra = "M";foreach ($TodasPreguntas as $pregunta) {
            $letra++;
            
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra . "6", ($pregunta->pregunta));
        }

        $letra = "M";foreach ($TodasPreguntas as $pregunta) {
            $letra++;
            
            $questionid++;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra . "4", ($pregunta->tipo));
        }

        $GroupRuts = BuscaUsuariosRespondieronMedSinUsuario($idMed);

        foreach ($GroupRuts as $user_unico) {

        $Datos_usu=TraeDatosUsuario($user_unico->rut);
        $Datos_jef=TraeDatosUsuario($Datos_usu[0]->jefe);

            if ($Datos_usu->nombre_completo == "") {$user_unico->nombre_completo = "USUARIO INACTIVO";}

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A" . $i, ($user_unico->rut));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B" . $i, ($Datos_usu[0]->nombre_completo));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C" . $i, ($Datos_usu[0]->cargo));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D" . $i, ($Datos_usu[0]->email));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E" . $i, ($Datos_usu[0]->gerencia));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("F" . $i, ($Datos_usu[0]->gerenciaR2));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("G" . $i, ($Datos_usu[0]->gerenciaR3));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("H" . $i, ($Datos_jef[0]->rut));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("I" . $i, ($Datos_jef[0]->nombre_completo));

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue("J" . $i, ($user_unico->fecha_carga));
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue("K" . $i, ($user_unico->grupo));

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("L" . $i, ($user_unico->fecharespuesta));
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("M" . $i, ($user_unico->comentario));

            $letra = "M";

            foreach ($TodasPreguntas as $pregunta) {
                
                $Respuestas_UsuariosIDPreg = BuscaRespuestasUsuarioMedSinFecha($user_unico->rut, $pregunta->id_pregunta, $idMed, $array_fecha);
                echo "<br><br> poregunta por rut ";
                
                $letra++;
                echo "<br>rut ".$user_unico->rut." ".$pregunta->id_pregunta;
                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($letra . $i, ($Respuestas_UsuariosIDPreg[0]->respuesta));
            }
            $i++;
        }

    $sheet = $objPHPExcel->getActiveSheet();
    $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(true);

// Renombrar Hoja

    $objPHPExcel->getActiveSheet()->setTitle('Respuestas');

// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.

    $objPHPExcel->setActiveSheetIndex(0);

// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.

    $fechahoy = date("Y-m-d") . "_" . date("H:i:s");
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Resultados_Medicion.xlsx"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
}
else if ($seccion == "dltPreg") {
    $id_pregunta = $get["idPreg"];
    
    $datos_pregunta = EVALUACION_DatosPregunta($id_pregunta);
    
    $orden = $datos_pregunta[0]->orden;
    $id_grupo_alternativas = $datos_pregunta[0]->id_grupo_alternativas;
    $id_evaluacion = $datos_pregunta[0]->evaluacion;
    EVALUACION_EliminaPreguntasAlternativas($id_pregunta, $id_grupo_alternativas);
    //ahora cambio el orden de las demas preguntas,
    EVALUACION_ActualizoORdenPreguntas($orden, $id_evaluacion);

    echo "<script>location.href='?sw=viewEval&idEval=" . $id_evaluacion . "';</script>";
}
else if ($seccion == "clave_update_empresa_uno_a_uno") {
    $id_empresa = $_SESSION["id_empresa"];

    $rut = $post["rut"];
    $clave = $post["clave"];


    clave_update_empresa_uno_a_uno_data($rut, $clave, $id_empresa);

    echo "<script>        location.href='?sw=actualizacion_usuarios';        </script>";
}
else if ($seccion == "editPregMed") {
    $idEval = $get["idEval"];
    $idGrupoAlter = $get["idGrup"];

    $PRINCIPAL = EditarPreguntaAlternativas(FuncionesTransversalesAdmin(file_get_contents("views/mediciones/admin/addEditPreg.html")), $idEval, $idGrupoAlter);

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "veInformeBoletin") {
    $id_empresa = $_SESSION["id_empresa"];
    $id_proceso = Decodear3($get["i"]);

    $arreglo_post = $post;

    $PRINCIPAL = FiltrosReportesAvancesSgd
        (FuncionesTransversalesAdmin
        (file_get_contents
            ("views/procesos/reportes/resultadosVersion2/" . $id_empresa . "_entorno_listado_boletin_sin_datos.html"
            )
        ), $arreglo_post, $id_empresa);

    $PRINCIPAL = str_replace("{ID_PROCESO}", Encodear3($id_proceso), $PRINCIPAL);
    $PRINCIPAL = colocaDatosInformeBoletin($PRINCIPAL, $arreglo_post, $id_empresa, $id_proceso);

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "addBibliObj") {
    $PRINCIPAL = jAgregarObjetoBiblioteca(FuncionesTransversalesAdmin(file_get_contents("views/biblioteca_objetos/addEditObj.html")));

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if (strpos($seccion, "editBibliObj|") !== false) {
    $arr = explode("|", $seccion);

    $PRINCIPAL = jBuscarObjetoBiblioteca(FuncionesTransversalesAdmin(file_get_contents("views/biblioteca_objetos/addEditObj.html")), $arr[1]);

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "subirPreg") {
    $idEval = $get["idEval"];
    $programa = $get["programa"];

    $PRINCIPAL = SubirPreguntasMasivo(FuncionesTransversalesAdmin(file_get_contents("views/evaluaciones/admin/subirPreg.html")), $idEval, $programa);

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "subirExcelPreg") {
    $idEval = $get["idEval"];
    $programa = $get["programa"];

    $idEvalPrograma = $idEval . "__" . $programa;
    $idEmpresa = $_SESSION["id_empresa"];


    EliminarDatosPreguntasAlternativasTmp();

    extract($post);

    $error_grave = "error";

    if ($action == "upload") {
//cargamos el archivo al servidor con el mismo nombre
        //solo le agregue el sufijo tmp_ev_
        $archivo = $_FILES['excel']['name'];VerificaExtensionFilesAdmin($_FILES["excel"]);
        $tipo = $_FILES['excel']['type'];
        $destino = "tmp_ev_" . $archivo;

        if (copy($_FILES['excel']['tmp_name'], $destino)) {

        } else {
            $error_grave = "Error Al subir Archivo<br>";
        }

        if (file_exists("tmp_ev_" . $archivo)) {
            require_once 'clases/PHPExcel.php';
            require_once 'clases/PHPExcel/Reader/Excel2007.php';

            $objReader = new PHPExcel_Reader_Excel2007();
            $objPHPExcel = $objReader->load("tmp_ev_" . $archivo);
            $objFecha = new PHPExcel_Shared_Date();
            $objPHPExcel->setActiveSheetIndex(0);
            $HojaActiva = $objPHPExcel->getActiveSheet();
            $total_filas = $HojaActiva->getHighestRow();
            $lastColumn = $HojaActiva->getHighestColumn();
            $total_columnas = PHPExcel_Cell::columnIndexFromString($lastColumn);
            $UltimaColumna = "A";
            $j = 0;
            $_DATOS_EXCEL = array();

//Obtengo los nombres de los campos
            for ($i = 0; $i <= $total_columnas; $i++) {
                $_campo[$i] = trim(($HojaActiva->getCell($UltimaColumna . "1")->getCalculatedValue()));
                $UltimaColumna++;
            }

//Obtengo datos de filas
            for ($fila = 2; $fila <= $total_filas; $fila++) {
                for ($columna = 0; $columna <= $total_columnas - 1; $columna++) {
                    $_DATOS_EXCEL[$j][$_campo[$columna]] = trim($HojaActiva->getCellByColumnAndRow($columna, $fila)->getCalculatedValue());
                }
                $j++;
            }
        } else {
            $error_grave = "Necesitas importar el archivo";
        }

        $total_errores = 0;
        $total_actualizar = 0;
        $total_insertar = 0;
        $total_desabilitar = 0;
        $total_a_eliminar = 0;
        $lineaT = "";
        $linea = 2;

        InsertarRegistroParaCalculoTablaPreguntasTmp();

        foreach ($_DATOS_EXCEL as $key => $item) {
            $error_columna = "";
            $error_grave = "";
            $mensaje_errores = "";
            $idPregunta = 0;
            $valuesPregunta = "";
            $valuesAlternativas = "";
            $coma = "";
            $separador1 = "";
            $separador2 = "";

            foreach ($item as $key2 => $valor) {
                if ($valor == "A" or $valor == "a") {$valor = 1;}
                if ($valor == "B" or $valor == "b") {$valor = 2;}
                if ($valor == "C" or $valor == "c") {$valor = 3;}
                if ($valor == "D" or $valor == "d") {$valor = 4;}
                if ($valor == "E" or $valor == "e") {$valor = 5;}
                if ($valor == "F" or $valor == "f") {$valor = 6;}
                if ($valor == "G" or $valor == "g") {$valor = 7;}
                if ($valor == "H" or $valor == "h") {$valor = 8;}
                if ($valor == "I" or $valor == "i") {$valor = 9;}
                if ($valor == "J" or $valor == "j") {$valor = 10;}

                if (strpos($key2, "Alternativa ") !== false) {
                    $valuesAlternativas .= $separador1 . $valor;

                    
                    $separador1 = "|";

                    if ($key2 == "Alternativa Correcta" && $valor == "" || $valor == " ") {
                        $error_columna .= $coma . $key2;
                        $coma = ",";
                    }

                    if ($key2 == "Alternativa 1" && $valor == "" || $valor == " ") {
                        $error_columna .= $coma . $key2;
                        $coma = ",";
                    }

                    if ($key2 == "Alternativa 2" && $valor == "" || $valor == " ") {
                        $error_columna .= $coma . $key2;

                        $coma = ",";
                    }
                } else {

                    if ($key2 == "Id Pregunta") {
                        if ($valor == "" || $valor == " ") {
                            $idPregunta = 0;
                            $valor = 0;
                        } else {

                            $idPregunta = $valor;
                        }
                    }

                    if ($key2 == "Orden Pregunta") {
                        if ($valor == "" || $valor == " ") {
                            $cuenta++;
                            $valor = $cuenta;
                        } else {

                            $valor = $valor;
                        }
                    }

                    if ($key2 == "Id Grupo Alternativas" && $valor == "" || $valor == " ") {
                        $valor = 0;
                    }

                    $valuesPregunta .= $separador2 . $valor;
                    $separador2 = "|";

                    
                    if ($key2 == "Texto de la pregunta") {
                        if ($valor == "" || $valor == " ") {
                            $error_columna .= $coma . $key2;
                            $coma = ",";
                        }
                    }
                }
            }


            if (ExistePregunta($idEval, $idPregunta)) {
                $accion = "ACTUALIZAR";
                $total_actualizar++;
                $mensaje_actualizar = "Se encontraron preguntas para actualizar!";
            } else {
                $accion = "INSERTAR";
                $total_insertar++;
                $mensaje_nuevo = "Se encontraron nuevas preguntas para ingresar!!";
            }

            $error_grave = InsertarPreguntasAlternativasTmp(($valuesPregunta), ($valuesAlternativas), $accion, $idEval, $idEmpresa);

            if ($error_grave != "") {
                break;
            }

            if (strlen($error_columna) > 0) {
                $lineaT .= "Linea [ $linea ] - Columna [ $error_columna ]<br/>";
                $total_errores++;
            }

            $linea++;
        }
    }

//LOAD VIEW

    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/evaluaciones/admin/subirPreg.html"));

    $totalPreg = ContarRegistrosTablaGlobalFiltro("tbl_evaluaciones_preguntas", "evaluacion", $idEval);
    $evaluacion = BuscarEvaluacion($idEval);

    foreach ($evaluacion as $row) {
        $PRINCIPAL = str_replace("{NOMBRE_EVAL}", $row->nombre_evaluacion, $PRINCIPAL);
        $PRINCIPAL = str_replace("{ID_EVAL}", $row->id, $PRINCIPAL);
    }

    $PRINCIPAL = str_replace("{ID_EMPRESA}", $idEmpresa, $PRINCIPAL);
    $PRINCIPAL = str_replace("{TOTAL_PREG}", $totalPreg, $PRINCIPAL);

    if (!$error_grave) {
        $PRINCIPAL = str_replace("{DISPLAYFORM1}", "", $PRINCIPAL);
        $PRINCIPAL = str_replace("{DISPLAYFORM2}", "none", $PRINCIPAL);
        $PRINCIPAL = str_replace("{DISPLAYERROR}", "display:none;", $PRINCIPAL);
    } else {

        $PRINCIPAL = str_replace("{DISPLAYFORM1}", "display:none;", $PRINCIPAL);
        $PRINCIPAL = str_replace("{DISPLAYFORM2}", "inline", $PRINCIPAL);
        $PRINCIPAL = str_replace("{DISPLAYERROR}", "", $PRINCIPAL);
    }

    $PRINCIPAL = str_replace("{DISPLAYFORM1}", "", $PRINCIPAL);
    $PRINCIPAL = str_replace("{DISPLAYFORM2}", "none", $PRINCIPAL);
    $PRINCIPAL = str_replace("{TAB1}", "", $PRINCIPAL);
    $PRINCIPAL = str_replace("{CLASTAB1}", "tab-pane", $PRINCIPAL);
    $PRINCIPAL = str_replace("{TAB2}", "active", $PRINCIPAL);
    $PRINCIPAL = str_replace("{CLASTAB2}", "tab-pane active", $PRINCIPAL);
    $PRINCIPAL = str_replace("{TITULO2}", "Vista previa", $PRINCIPAL);
    $PRINCIPAL = str_replace("{TOT_ACTUALIZAR}", $total_actualizar, $PRINCIPAL);
    $PRINCIPAL = str_replace("{TOT_INSERTAR}", $total_insertar, $PRINCIPAL);
    $PRINCIPAL = str_replace("{TOT_DESVINCULAR}", $total_desvincular, $PRINCIPAL);
    $PRINCIPAL = str_replace("{TOT_ERRORES}", $total_errores, $PRINCIPAL);
    $PRINCIPAL = str_replace("{ERRORES}", $lineaT, $PRINCIPAL);
    $PRINCIPAL = str_replace("{ERROR_GRAVE}", $error_grave, $PRINCIPAL);

    $PRINCIPAL = str_replace("{PROGRAMA}", $programa, $PRINCIPAL);
    $PRINCIPAL = str_replace("{IDEVALPROGRAMA}", $idEvalPrograma, $PRINCIPAL);

    if (!$error_grave) {
        $PRINCIPAL = str_replace("{ENTORNO_PREVIA}", (ListarPreguntasMasivoPreview($idEmpresa, $idEval)), $PRINCIPAL);
    } else {
        $PRINCIPAL = str_replace("{ENTORNO_PREVIA}", "", $PRINCIPAL);
    }

    echo CleanHTMLWhiteList($PRINCIPAL);exit;
}
else if ($seccion == "acepPreviewExcelPreg") {
    $idEmpresa = $_SESSION["id_empresa"];
    $idEval = $get["idEval"];
    $programa = $get["programa"];
    CruzarPreguntasAlternativasTmpConOriginal($idEmpresa, $idEval);

    echo "    <script>
location.href='?sw=viewEval&idEval=$idEval&programa=$programa';
</script>";
    exit;
}
else if ($seccion == "cancelSubirPreg") {
    EliminarDatosPreguntasAlternativasTmp();

    $idEval = $get["idEval"];

    echo "    <script>
location.href='?sw=viewEval&idEval=$idEval';
</script>";
    exit;
}
else if($seccion=="finanzas_facturas"){
    $tiposDocumento = ListarTiposDocumentosFinanza(1);
    $servicios = ListarServiciosFinanza();
    $cuentas = ListarCuentasFinanza();
    $proyectos = ListarProyectosFinanza();
    $cui = ListarCUIFinanza();
    $yearActual = date('Y');
    $anual = "";
    $i=0;
    while($i<10){
        $year = $yearActual-$i;
        $anual .="<option value='$year'>$year</option>";
        $i++;
    }
    $responsables = [];
    $consulResponsables = ListResponsables();
    $respsup="";
    $respejec="";
    $respcon="";
    $respjefe="";
    $respjefe1="";
    $respjefe2="";
    $sladefsup="";
    $sladefeje="";
    $sladefcon="";
    $sladefjef="";
    $sladefjef1="";
    $sladefjef2="";
    foreach($consulResponsables as $keyresp => $rowresp){
        if($keyresp==1){
            foreach($rowresp as $key => $row){
                $rut = Encodear3($row['rut']);
                $respsup.="<option value='$rut'>{$row['nombre']}</option>";
                $sladefsup = $row['sla'];
            }
        }
        if($keyresp==2){
            foreach($rowresp as $key => $row){
                $rut = Encodear3($row['rut']);
                $respejec.="<option value='$rut'>{$row['nombre']}</option>";
                $sladefeje = $row['sla'];
            }
        }
        if($keyresp==3){
            foreach($rowresp as $key => $row){
                $rut = Encodear3($row['rut']);
                $respcon.="<option value='$rut'>{$row['nombre']}</option>";
                $sladefcon = $row['sla'];
            }
        }
        if($keyresp==4){
            foreach($rowresp as $key => $row){
                $rut = Encodear3($row['rut']);
                $respjefe.="<option value='$rut'>{$row['nombre']}</option>";
                $sladefjef = $row['sla'];
            }
        }
        if($keyresp==5){
            foreach($rowresp as $key => $row){
                $rut = Encodear3($row['rut']);
                $respjefe1.="<option value='$rut'>{$row['nombre']}</option>";
                $sladefjef1 = $row['sla'];
            }
        }
        if($keyresp==6){
            foreach($rowresp as $key => $row){
                $rut = Encodear3($row['rut']);
                $respjefe2.="<option value='$rut'>{$row['nombre']}</option>";
                $sladefjef2 = $row['sla'];
            }
        }
    }
    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/finanzas/facturas/entorno.html"));

    $id_empresa = $_SESSION["id_empresa"];
    $PRINCIPAL = str_replace("{ENTORNO}", (file_get_contents("views/finanzas/facturas/factura_table.html")), $PRINCIPAL);
    $PRINCIPAL = str_replace("{OPTIONS_SELECT_TIPO_DOCUM}", $tiposDocumento, $PRINCIPAL);
    $PRINCIPAL = str_replace("{OPTIONS_SELECT_SERVIC}", ($servicios), $PRINCIPAL);
    $PRINCIPAL = str_replace("{OPTIONS_CUENTA}", ($cuentas), $PRINCIPAL);
    $PRINCIPAL = str_replace("{OPTIONS_PROYECTO}", ($proyectos), $PRINCIPAL);
    $PRINCIPAL = str_replace("{OPTIONS_CUI}", $cui, $PRINCIPAL);
    $PRINCIPAL = str_replace("{OPTIONS_ANUAL}", $anual, $PRINCIPAL);
    $PRINCIPAL = str_replace("{OPTIONS_SUPERVISOR}", $respsup, $PRINCIPAL);
    $PRINCIPAL = str_replace("{SLA_DEFINIDO_SUP}", $sladefsup, $PRINCIPAL);
    $PRINCIPAL = str_replace("{SLA_DEFINIDO_EJE}", $sladefeje, $PRINCIPAL);
    $PRINCIPAL = str_replace("{SLA_DEFINIDO_CON}", $sladefcon, $PRINCIPAL);
    $PRINCIPAL = str_replace("{SLA_DEFINIDO_JEF}", $sladefjef, $PRINCIPAL);
    $PRINCIPAL = str_replace("{SLA_DEFINIDO_JEF1}", $sladefjef1, $PRINCIPAL);
    $PRINCIPAL = str_replace("{SLA_DEFINIDO_JEF2}", $sladefjef2, $PRINCIPAL);
    $PRINCIPAL = str_replace("{OPTIONS_EJECUTIVO}", $respejec, $PRINCIPAL);
    $PRINCIPAL = str_replace("{OPTIONS_CONSULTOR}", $respcon, $PRINCIPAL);
    $PRINCIPAL = str_replace("{OPTIONS_JEFE}", $respjefe, $PRINCIPAL);
    $PRINCIPAL = str_replace("{OPTIONS_JEFE1}", $respjefe1, $PRINCIPAL);
    $PRINCIPAL = str_replace("{OPTIONS_JEFE2}", $respjefe2, $PRINCIPAL);
    echo CleanHTMLWhiteList($PRINCIPAL);
}else if($seccion=="finanzas_reembolsos"){
    $tiposDocumento = ListarTiposDocumentosFinanza(2);
    $servicios = ListarServiciosFinanza();
    $cuentas = ListarCuentasFinanza();
    $proyectos = ListarProyectosFinanza();
    $cui = ListarCUIFinanza();
    $yearActual = date('Y');
    $anual = "";
    $i=0;
    while($i<10){
        $year = $yearActual-$i;
        $anual .="<option value='$year'>$year</option>";
        $i++;
    }
    $responsables = [];
    $consulResponsables = ListResponsables();
    $respsup="";
    $respejec="";
    $respcon="";
    $respjefe="";
    $respjefe1="";
    $respjefe2="";
    $sladefsup="";
    $sladefeje="";
    $sladefcon="";
    $sladefjef="";
    foreach($consulResponsables as $keyresp => $rowresp){
        if($keyresp==2){
            foreach($rowresp as $key => $row){
                $rut = Encodear3($row['rut']);
                $respejec.="<option value='$rut'>{$row['nombre']}</option>";
                $sladefeje = $row['slareem'];
            }
        }
        if($keyresp==3){
            foreach($rowresp as $key => $row){
                $rut = Encodear3($row['rut']);
                $respcon.="<option value='$rut'>{$row['nombre']}</option>";
                $sladefcon = $row['sla'];
            }
        }
    }
    $PRINCIPAL = FuncionesTransversalesAdmin(file_get_contents("views/finanzas/reembolsos/entorno.html"));
    $id_empresa = $_SESSION["id_empresa"];
    $PRINCIPAL = str_replace("{ENTORNO}", (file_get_contents("views/finanzas/reembolsos/reembolsos_table.html")), $PRINCIPAL);
    $PRINCIPAL = str_replace("{OPTIONS_SELECT_TIPO_DOCUM}", ($tiposDocumento), $PRINCIPAL);
    $PRINCIPAL = str_replace("{OPTIONS_SELECT_SERVIC}", ($servicios), $PRINCIPAL);
    $PRINCIPAL = str_replace("{OPTIONS_CUENTA}", $cuentas, $PRINCIPAL);
    $PRINCIPAL = str_replace("{OPTIONS_PROYECTO}", ($proyectos), $PRINCIPAL);
    $PRINCIPAL = str_replace("{OPTIONS_CUI}", $cui, $PRINCIPAL);
    $PRINCIPAL = str_replace("{OPTIONS_ANUAL}", $anual, $PRINCIPAL);
    $PRINCIPAL = str_replace("{SLA_DEFINIDO_EJE}", $sladefeje, $PRINCIPAL);
    $PRINCIPAL = str_replace("{OPTIONS_EJECUTIVO}", $respejec, $PRINCIPAL);
    $PRINCIPAL = str_replace("{OPTIONS_CONSULTOR}", $respcon, $PRINCIPAL);
    echo CleanHTMLWhiteList($PRINCIPAL);
}
else if($seccion=="getFacturas"){
    $facturas = GetFacturas();
    echo json_encode($facturas);

}else if($seccion=="getReembolsos"){
    $reembolsos = GetReembolsos();
    echo json_encode($reembolsos);

}else if($seccion=="getProveedores"){
    $result = array(
            "code"=>402,
            "proveedor"=>""
    );
    $rut = $post['rut'];
    $proveedores = proveedores_finanzasData(78,'otec', $rut);
    if(count($proveedores)>0){
        $result['code']=200;
        $result['proveedor']= ($proveedores[0]->nombre);
    }
    echo json_encode($result);
}
else if($seccion=="getColaborador"){
    $result = array(
            "code"=>402,
            "proveedor"=>""
    );
    $rut = $post['rut'];
    $colaboradores = colaboradores_finanzasData($rut);
    if(count($colaboradores)>0){
        $result['code']=200;
        $result['colaborador']=$colaboradores[0]->nombre_completo;
    }
    echo json_encode($result);
}else if($seccion=="getOTA"){
    $result = array(
            "code"=>402,
            "ota"=>"",
            "curso"=>"",
            "cuenta"=>"",
            "cuenta_nombre"=>"",
            "proyecto"=>""
    );
    $ota = $post['ota'];
    $curso = lista_ota_vista_data(78, $ota);
    if(count($curso)>0){
        $result['code']=200;
        $result['ota']=($curso[0]->nombre);
        $result['curso']=($curso[0]->curso);
        $result['cuenta']=($curso[0]->cuenta_num);
        $result['cuenta_nombre']=($curso[0]->cuenta_dsc);
        $result['proyecto']=($curso[0]->proyecto);
    }
    echo json_encode($result);
}else if($seccion=="getCuenta"){
    $result = array(
            "code"=>402,
            "proyecto"=>"",
            "cui"=>""
    );
    $ota = $post['ota'];
    $curso = lista_cuenta_vista_data(78, $ota);
    if(count($curso)>0){
        $result['code']=200;
        $result['proyecto']=($curso[0]->codigo_inscripcion);
        $result['cui']=($curso[0]->nombre);
    }
    echo json_encode($result);
}else if($seccion=="finanzas_delete_file"){
        $code = 200;
    $message ="Documento eliminado exitosamente";
    $id = $post['key'];
    deleteDocumentFacturaData($id);
    echo json_encode(array("code"=>$code, "message"=>$message));
}else if($seccion=="finanzas_delete_file_reemb"){
        $code = 200;
    $message ="Documento eliminado exitosamente";
    $id = $post['key'];
    deleteDocumentReembolsoData($id);
    echo json_encode(array("code"=>$code, "message"=>$message));
}else if($seccion=="finanzas_facturas_delete"){
    $code = 200;
    $message ="Factura eliminada exitosamente";
    $id = $post['idFac'];
    deleteDatosGeneralesFacturaData($id);
    echo json_encode(array("code"=>$code, "message"=>$message));
}else if($seccion=="finanzas_reembolso_delete"){
    $code = 200;
    $message ="Reembolso eliminado exitosamente";
    $id = $post['idFac'];
    deleteDatosGeneralesReembolsoData($id);
    echo json_encode(array("code"=>$code, "message"=>$message));
}else if($seccion=="finanzas_facturas_save"){
    $code = 400;
    $message ="Ha ocurrido un error al guardar la factura";
    $responsables = [];
    $factur_numdoc = $post['numdoc'];
    $factur_tipdocid = $post['tipoDoc'];
    $factur_proveerut = $post['rutProvee'];
    $factur_servid = $post['servOtor'];
    $factur_servotro = $post['servOtorOtro'];
    $factur_montoto = $post['montotot'];
    $factur_montonet = $post['montoNet'];
    $factur_impuest = $post['montoImp'];
    $factur_fecemision = $post['fechaEmision'];
    $factur_ota = $post['conOta'];
    $factur_numota = $post['numOta'];
    $factur_otanombre = $post['nombOta'];
    $factur_cuenta = $post['cuenta'];
    $factur_proyecto = $post['proyecto'];
    $factur_curso = $post['proyecto'];
    $factur_cui = $post['cui'];
    $factur_respgast = $post['respGast'];
    $factur_observacion = $post['observaciones'];
    $factur_mes = $post['mesCon'];
    $factur_anual = $post['anualCon'];
    $factura_status = $post['estdoc'];
    $factura_proveenom = $post['nombreProvee'];
        $factur_cuenta_nombre = $post['cuentanom'];
    $rut_created = $_SESSION['user_'];
        $consulNumDocFac = facturaFinanzaByNumdoc($factur_numdoc);
        $countfiles = count($_FILES['multimediaFiles']['tmp_name']);
        for($i=0;$i<$countfiles;$i++){
            $fileDir = $_FILES['multimediaFiles']['tmp_name'][$i];
            if($fileDir!=""){
                $file = fopen($fileDir, "r");
                //Output lines until EOF is reached
                $palabras = array('/JS', '/JavaScript', '/Action');
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
        }
        if($encontrada){
            echo json_encode(array("code"=>400, "message"=>"Archivo proporcionado inválido"));
            exit();
        }
    if(count($consulNumDocFac)==0){
        /**Se verifica si el proveedor existe antes de guardar **/
        $consulProveedor = DatosOtecDadoRut($factur_proveerut);
        if(count($consulProveedor)==0){
            $code=404;
            $message = "Proveedor no válido";
                echo json_encode(array("code"=>$code, "message"=>$message));
                exit();
        }

        $idInsert = saveDatosGeneralesFactura($factur_numdoc, $factur_tipdocid, $factur_proveerut,
                                  $factur_servid, ($factur_servotro), $factur_montoto, $factur_montonet, $factur_impuest,
                                  $factur_fecemision, $factur_ota, $factur_numota, $factur_cuenta,
                                  ($factur_proyecto), ($factur_curso), ($factur_otanombre), $factur_cui,
                                  $factur_respgast, ($factur_observacion), $factur_mes, $factur_anual,$factura_status,$factura_proveenom,$rut_created,$factur_cuenta_nombre);

        if($idInsert!=""){
            $code = 200;
            $message ="Factura guardada exitosamente";
            //Se guardan los responsables de la factura
$selrespsup = VerificaSQLInjectionLight($post['selrespsup']);
$selrespejec = VerificaSQLInjectionLight($post['selrespejec']);
$selrespcon = VerificaSQLInjectionLight($post['selrespcon']);
$selrespjef = VerificaSQLInjectionLight($post['selrespjef']);
$selrespjef1 = VerificaSQLInjectionLight($post['selrespjef1']);
$selrespjef2 = VerificaSQLInjectionLight($post['selrespjef2']);

// Validar cada entrada y construir el array de responsables
$post['aplicaSup'] == "on" ? $responsables['sup'] = array(
    "tipo" => "1",
    "resprut" => Decodear3($selrespsup),
    "recep" => $post['dateRecepsup'],
    "envio" => $post['dateEnviosup']
) : "";

$post['aplicaEje'] == "on" ? $responsables['eje'] = array(
    "tipo" => "2",
    "resprut" => Decodear3($selrespejec),
    "recep" => $post['dateRecepeje'],
    "envio" => $post['dateEnvioEje']
) : "";

$post['aplicaCon'] == "on" ? $responsables['con'] = array(
    "tipo" => "3",
    "resprut" => Decodear3($selrespcon),
    "recep" => $post['dateRecepcon'],
    "envio" => $post['dateEnviocon']
) : "";

$post['aplicaJef'] == "on" ? $responsables['jef'] = array(
    "tipo" => "4",
    "resprut" => Decodear3($selrespjef),
    "recep" => $post['dateRecepjef'],
    "envio" => $post['dateEnviojef']
) : "";

$post['aplicaJef1'] == "on" ? $responsables['jef1'] = array(
    "tipo" => "5",
    "resprut" => Decodear3($selrespjef1),
    "recep" => $post['dateRecepjef1'],
    "envio" => $post['dateEnviojef1']
) : "";

$post['aplicaJef2'] == "on" ? $responsables['jef2'] = array(
    "tipo" => "6",
    "resprut" => Decodear3($selrespjef2),
    "recep" => $post['dateRecepjef2'],
    "envio" => $post['dateEnviojef2']
) : "";
            saveResponsablesFacturaData($responsables, $idInsert);

            $countfiles = count($_FILES['multimediaFiles']['name']);
            for($i=0;$i<$countfiles;$i++){
                $ext = pathinfo($_FILES['multimediaFiles']['name'][$i], PATHINFO_EXTENSION);
                if($ext=="pdf"){
                    $filename = $_FILES['multimediaFiles']['name'][$i];
                    $url = date('Ymdhis').rand(0,100).".$ext";
                    $target_file = 'upload/finanzas/'.$url;
                    move_uploaded_file($_FILES['multimediaFiles']['tmp_name'][$i],$target_file);
                    saveDocumentoFactura($filename, $url, $idInsert);
                }
            }
        }
     }else {
            $code=404;
            $message = "Número de documento ya existe";
     }
    echo json_encode(array("code"=>$code, "message"=>$message));
}else if($seccion=="finanzas_reembolso_save"){
    $code = 400;
    $message ="Ha ocurrido un error al guardar el reembolso";
    $responsables = [];
    $reembo_numdoc = "";
    $reembo_tipdocid = $post['tipoDoc'];
    $reembo_tipdocOtro = $post['tipDocOtro'];
    $reembo_proveerut = $post['rutColabora'];
    $reembo_servid = $post['servOtor'];
    $reembo_servotro = $post['servOtorOtro'];
    $reembo_montoto = $post['montotot'];
    $reembo_fecemision = $post['fechaEmision'];
    $reembo_ota = $post['conOta'];
    $reembo_numota = $post['numOta'];
    $reembo_otanombre = $post['nombOta'];
    $reembo_cuenta = $post['cuenta'];
    $reembo_proyecto = $post['proyecto'];
    $reembo_curso = $post['proyecto'];
    $reembo_cui = $post['cui'];
    $reembo_respgast = $post['respGast'];
    $reembo_observacion = $post['observaciones'];
    $reembo_mes = $post['mesCon'];
    $reembo_anual = $post['anualCon'];
    $reemboa_status = $post['estdoc'];
    $reemboa_proveenom = ($post['nombreColabora']);
    $reembo_cuenta_nombre = $post['cuentanom'];
    $rut_created = $_SESSION['user_'];
    $consulNumDocReemb = reembolsoFinanzaByNumdoc($reembo_numdoc);
    if(count($consulNumDocReemb)==0){
    $idInsert = saveDatosGeneralesReembolso($reembo_numdoc, $reembo_tipdocid,$reembo_tipdocOtro, $reembo_proveerut,
                                  $reembo_servid, ($reembo_servotro), $reembo_montoto,
                                  $reembo_fecemision, $reembo_ota, $reembo_numota, $reembo_cuenta,
                                  ($reembo_proyecto), ($reembo_curso), ($reembo_otanombre), $reembo_cui,
                                  $reembo_respgast, ($reembo_observacion), $reembo_mes, $reembo_anual,$reemboa_status,$reemboa_proveenom,$rut_created,$reembo_cuenta_nombre);

        if($idInsert!=""){
            $idAuto = str_pad($idInsert, 3, "0", STR_PAD_LEFT);
            updateNumDocReembolso($idInsert,str_pad(date('m').$idAuto, 5, "0", STR_PAD_LEFT));
            $code = 200;
            $message ="Reembolso guardado exitosamente";
            //Se guardan los responsables de la factura
$selrespsup = VerificaSQLInjectionLight($post['selrespsup']);
$selrespejec = VerificaSQLInjectionLight($post['selrespejec']);
$selrespcon = VerificaSQLInjectionLight($post['selrespcon']);
$selrespjef = VerificaSQLInjectionLight($post['selrespjef']);
$selrespjef1 = VerificaSQLInjectionLight($post['selrespjef1']);
$selrespjef2 = VerificaSQLInjectionLight($post['selrespjef2']);

// Validar cada entrada y construir el array de responsables
$post['aplicaSup'] == "on" ? $responsables['sup'] = array(
    "tipo" => "1",
    "resprut" => Decodear3($selrespsup),
    "recep" => $post['dateRecepsup'],
    "envio" => $post['dateEnviosup']
) : "";

$post['aplicaEje'] == "on" ? $responsables['eje'] = array(
    "tipo" => "2",
    "resprut" => Decodear3($selrespejec),
    "recep" => $post['dateRecepeje'],
    "envio" => $post['dateEnvioEje']
) : "";

$post['aplicaCon'] == "on" ? $responsables['con'] = array(
    "tipo" => "3",
    "resprut" => Decodear3($selrespcon),
    "recep" => $post['dateRecepcon'],
    "envio" => $post['dateEnviocon']
) : "";

$post['aplicaJef'] == "on" ? $responsables['jef'] = array(
    "tipo" => "4",
    "resprut" => Decodear3($selrespjef),
    "recep" => $post['dateRecepjef'],
    "envio" => $post['dateEnviojef']
) : "";

$post['aplicaJef1'] == "on" ? $responsables['jef1'] = array(
    "tipo" => "5",
    "resprut" => Decodear3($selrespjef1),
    "recep" => $post['dateRecepjef1'],
    "envio" => $post['dateEnviojef1']
) : "";

$post['aplicaJef2'] == "on" ? $responsables['jef2'] = array(
    "tipo" => "6",
    "resprut" => Decodear3($selrespjef2),
    "recep" => $post['dateRecepjef2'],
    "envio" => $post['dateEnviojef2']
) : "";
            saveResponsablesReembolsoData($responsables, $idInsert);

            $countfiles = count($_FILES['multimediaFiles']['name']);
            for($i=0;$i<$countfiles;$i++){
                $ext = pathinfo($_FILES['multimediaFiles']['name'][$i], PATHINFO_EXTENSION);
                if($ext=="pdf"){
                    $filename = $_FILES['multimediaFiles']['name'][$i];
                    $url = date('Ymdhis').rand(0,100).".$ext";
                    $target_file = 'upload/finanzas/'.$url;
                    move_uploaded_file($_FILES['multimediaFiles']['tmp_name'][$i],$target_file);
                    saveDocumentoReembolso($filename, $url, $idInsert);
                }
            }
        }
     }else {
            $code=404;
            $message = "Número de documento ya existe";
        }
    echo json_encode(array("code"=>$code, "message"=>$message));
}else if($seccion=="finanzas_facturas_update"){
    $code = 200;
    $message ="Factura modificada exitosamente";
    $responsables = [];
	$post = VerificaArregloSQLInjectionLight($post);
    $id = $post['idFact'];
    $factur_numdoc = $post['numdoc'];
    $factur_tipdocid = $post['tipoDoc'];
    $factur_proveerut = $post['rutProvee'];
    $factur_servid = $post['servOtor'];
    $factur_servotro = $post['servOtorOtro'];
    $factur_montoto = $post['montotot'];
    $factur_montonet = $post['montoNet'];
    $factur_impuest = $post['montoImp'];
    $factur_fecemision = $post['fechaEmision'];
    $factur_ota = $post['conOta'];
    $factur_numota = $post['numOta'];
    $factur_otanombre = $post['nombOta'];
    $factur_cuenta = $post['cuenta'];
    $factur_proyecto = $post['proyecto'];
    $factur_curso = $post['curso'];
    $factur_cui = $post['cui'];
    $factur_respgast = $post['respGast'];
    $factur_observacion = $post['observaciones'];
    $factur_mes = $post['mesCon'];
    $factur_anual = $post['anualCon'];
    $factura_status = $post['estdoc'];
    $factura_proveenom = $post['nombreProvee'];
    $factur_cuenta_nombre = $post['cuentanom'];
    if(isset($id) || $id!=""){
        updateDatosGeneralesFactura($id,$factur_numdoc, $factur_tipdocid, $factur_proveerut,
                                      $factur_servid, ($factur_servotro), $factur_montoto, $factur_montonet, $factur_impuest,
                                      $factur_fecemision, $factur_ota, $factur_numota, $factur_cuenta,
                                      ($factur_proyecto), ($factur_curso), ($factur_otanombre), $factur_cui,
                                      $factur_respgast, ($factur_observacion), $factur_mes, $factur_anual,$factura_status,$factura_proveenom,($factur_cuenta_nombre));
        //Se guardan los responsables de la factura
            $selrespsup = VerificaSQLInjectionLight($post['selrespsup']);
$selrespejec = VerificaSQLInjectionLight($post['selrespejec']);
$selrespcon = VerificaSQLInjectionLight($post['selrespcon']);
$selrespjef = VerificaSQLInjectionLight($post['selrespjef']);
$selrespjef1 = VerificaSQLInjectionLight($post['selrespjef1']);
$selrespjef2 = VerificaSQLInjectionLight($post['selrespjef2']);

// Validar cada entrada y construir el array de responsables
$post['aplicaSup'] == "on" ? $responsables['sup'] = array(
    "tipo" => "1",
    "resprut" => Decodear3($selrespsup),
    "recep" => $post['dateRecepsup'],
    "envio" => $post['dateEnviosup']
) : "";

$post['aplicaEje'] == "on" ? $responsables['eje'] = array(
    "tipo" => "2",
    "resprut" => Decodear3($selrespejec),
    "recep" => $post['dateRecepeje'],
    "envio" => $post['dateEnvioEje']
) : "";

$post['aplicaCon'] == "on" ? $responsables['con'] = array(
    "tipo" => "3",
    "resprut" => Decodear3($selrespcon),
    "recep" => $post['dateRecepcon'],
    "envio" => $post['dateEnviocon']
) : "";

$post['aplicaJef'] == "on" ? $responsables['jef'] = array(
    "tipo" => "4",
    "resprut" => Decodear3($selrespjef),
    "recep" => $post['dateRecepjef'],
    "envio" => $post['dateEnviojef']
) : "";

$post['aplicaJef1'] == "on" ? $responsables['jef1'] = array(
    "tipo" => "5",
    "resprut" => Decodear3($selrespjef1),
    "recep" => $post['dateRecepjef1'],
    "envio" => $post['dateEnviojef1']
) : "";

$post['aplicaJef2'] == "on" ? $responsables['jef2'] = array(
    "tipo" => "6",
    "resprut" => Decodear3($selrespjef2),
    "recep" => $post['dateRecepjef2'],
    "envio" => $post['dateEnviojef2']
) : "";
            saveResponsablesFacturaData($responsables, $id);

            $countfiles = count($_FILES['multimediaFiles']['name']);
            for($i=0;$i<$countfiles;$i++){
                $ext = pathinfo($_FILES['multimediaFiles']['name'][$i], PATHINFO_EXTENSION);
                if($ext=="pdf"){
                    $filename = $_FILES['multimediaFiles']['name'][$i];
                    $url = date('Ymdhis').rand(0,100).".$ext";
                    $target_file = 'upload/finanzas/'.$url;
                    move_uploaded_file($_FILES['multimediaFiles']['tmp_name'][$i],$target_file);
                    saveDocumentoFactura($filename, $url, $id);
                }
            }
    } else {
        $code = 404;
        $message = "Ha courrido un error(#001) al actualizar la factura";
    }
    echo json_encode(array("code"=>$code, "message"=>$message));
}else if($seccion=="finanzas_reembolso_update"){
    $code = 200;
    $message ="Reembolso modificado exitosamente";
    $responsables = [];
	$post = VerificaArregloSQLInjectionLight($post);
    $id = $post['idReembo'];
    $reembo_numdoc = $post['numdoc'];
    $reembo_tipdocid = $post['tipoDoc'];
    $reembo_tipdocOtro = $post['tipDocOtro'];
    $reembo_proveerut = $post['rutColabora'];
    $reembo_servid = $post['servOtor'];
    $reembo_servotro = $post['servOtorOtro'];
    $reembo_montoto = $post['montotot'];
    $reembo_montonet = $post['montoNet'];
    $reembo_impuest = $post['montoImp'];
    $reembo_fecemision = $post['fechaEmision'];
    $reembo_ota = $post['conOta'];
    $reembo_numota = $post['numOta'];
    $reembo_otanombre = $post['nombOta'];
    $reembo_cuenta = $post['cuenta'];
    $reembo_proyecto = $post['proyecto'];
    $reembo_curso = $post['curso'];
    $reembo_cui = $post['cui'];
    $reembo_respgast = $post['respGast'];
    $reembo_observacion = $post['observaciones'];
    $reembo_mes = $post['mesCon'];
    $reembo_anual = $post['anualCon'];
    $reemboa_status = $post['estdoc'];
    $reemboa_proveenom = ($post['nombreColabora']);
    $reembo_cuenta_nombre = $post['cuentanom'];
    if(isset($id) || $id!=""){
        updateDatosGeneralesReembolso($id,$reembo_numdoc, $reembo_tipdocid,$reembo_tipdocOtro, $reembo_proveerut,
                                      $reembo_servid, ($reembo_servotro), $reembo_montoto, $reembo_montonet, $reembo_impuest,
                                      $reembo_fecemision, $reembo_ota, $reembo_numota, $reembo_cuenta,
                                      ($reembo_proyecto), ($reembo_curso), ($reembo_otanombre), $reembo_cui,
                                      $reembo_respgast, ($reembo_observacion), $reembo_mes, $reembo_anual,$reemboa_status,$reemboa_proveenom,($reembo_cuenta_nombre));
        //Se guardan los responsables de la factura

$selrespsup = VerificaSQLInjectionLight($post['selrespsup']);
$selrespejec = VerificaSQLInjectionLight($post['selrespejec']);
$selrespcon = VerificaSQLInjectionLight($post['selrespcon']);
$selrespjef = VerificaSQLInjectionLight($post['selrespjef']);
$selrespjef1 = VerificaSQLInjectionLight($post['selrespjef1']);
$selrespjef2 = VerificaSQLInjectionLight($post['selrespjef2']);

// Validar cada entrada y construir el array de responsables
$post['aplicaSup'] == "on" ? $responsables['sup'] = array(
    "tipo" => "1",
    "resprut" => Decodear3($selrespsup),
    "recep" => $post['dateRecepsup'],
    "envio" => $post['dateEnviosup']
) : "";

$post['aplicaEje'] == "on" ? $responsables['eje'] = array(
    "tipo" => "2",
    "resprut" => Decodear3($selrespejec),
    "recep" => $post['dateRecepeje'],
    "envio" => $post['dateEnvioEje']
) : "";

$post['aplicaCon'] == "on" ? $responsables['con'] = array(
    "tipo" => "3",
    "resprut" => Decodear3($selrespcon),
    "recep" => $post['dateRecepcon'],
    "envio" => $post['dateEnviocon']
) : "";

$post['aplicaJef'] == "on" ? $responsables['jef'] = array(
    "tipo" => "4",
    "resprut" => Decodear3($selrespjef),
    "recep" => $post['dateRecepjef'],
    "envio" => $post['dateEnviojef']
) : "";

$post['aplicaJef1'] == "on" ? $responsables['jef1'] = array(
    "tipo" => "5",
    "resprut" => Decodear3($selrespjef1),
    "recep" => $post['dateRecepjef1'],
    "envio" => $post['dateEnviojef1']
) : "";

$post['aplicaJef2'] == "on" ? $responsables['jef2'] = array(
    "tipo" => "6",
    "resprut" => Decodear3($selrespjef2),
    "recep" => $post['dateRecepjef2'],
    "envio" => $post['dateEnviojef2']
) : "";
            saveResponsablesReembolsoData($responsables, $id);

            $countfiles = count($_FILES['multimediaFiles']['name']);
            for($i=0;$i<$countfiles;$i++){
                $ext = pathinfo($_FILES['multimediaFiles']['name'][$i], PATHINFO_EXTENSION);
                if($ext=="pdf"){
                    $filename = $_FILES['multimediaFiles']['name'][$i];
                    $url = date('Ymdhis').rand(0,100).".$ext";
                    $target_file = 'upload/finanzas/'.$url;
                    move_uploaded_file($_FILES['multimediaFiles']['tmp_name'][$i],$target_file);
                    saveDocumentoReembolso($filename, $url, $id);
                }
            }
    } else {
        $code = 404;
        $message = "Ha courrido un error(#001) al actualizar la factura";
    }
    echo json_encode(array("code"=>$code, "message"=>$message));
} else if($seccion == "getResponsableByIdFac"){
    $idFac = $post['idFac'];
    $consulResp = getRespById($idFac);
    echo json_encode($consulResp);
} else if($seccion == "getResponsableByIdReembo"){
    $idReemb = $post['idReemb'];
    $consulResp = getRespReemboById($idReemb);
    echo json_encode($consulResp);
} else if($seccion == "getDocumentoByIdFac"){
    $idFac = $post['idFac'];
    $consulDocumento = getDocumentoById($idFac);
    echo json_encode($consulDocumento);
} else if($seccion == "getDocumentoByIdReembo"){
    $idRem = $post['idRem'];
    $consulDocumento = getDocumentoReemboById($idRem);
    echo json_encode($consulDocumento);
} else if($seccion == "reportExcelFacturas"){
            $estados = array(1=>"Aprobado",2=>"Rechazado", 3=>"Anulado", 4=>"En Aprobación");
    $responsables = array(1=>"Asistentes",2=>"Jefaturas", 3=>"Consultoras");
    $row_excel_enc_sat = ("N° Factura;RUT Proveedor;Nombre Proveedor;Monto Total;Tipo Factura;Servicio Otorgado;Otro Servicio;EVENTO (Nombre OTA);Cuenta Contable;Proyecto;Responsable del Gasto;Con /Sin OTA;OTA;CUI;Estado;Periodo contable;Fecha documento;Observaciones\r\n");
    $facturas = Lista_Facturas();
    foreach($facturas as $row){
        $digito = explode("-",$row->factur_proveerut);
        $ota ="SIN OTA";
        $numota=0;
        if($row->factur_ota==1){
            $ota ="CON OTA";
            $numota = $row->factur_numota;
        }
                if($row->factur_mes>9){

        } else {
            $row->factur_mes="0".$row->factur_mes;
        }
        $monto = str_replace(".",",",$row->factur_montoto);
        $row_excel_enc_sat .= $row->factur_numdoc . ";"
        . $row->factur_proveerut . ";"
        . ($row->proveedor_nombre) . ";"
        . $monto . ";"
        . ($row->tipdoc_dsc) . ";"
        . ($row->servic_dsc) . ";"
        . ($row->factur_servotro) . ";"
        . ($row->factur_otanombre) . ";"
        . $row->factur_cuenta . ";"
        . ($row->factur_proyecto) . ";"
        . ($row->factur_respgast) . ";"
        . $ota . ";"
        . $numota . ";"
        . $row->factur_cui . ";"
        . ($estados[$row->factur_status]) . ";"
        . $row->factur_anual.$row->factur_mes . ";"
              . $row->factur_fecemision . ";"
        . ($row->factur_observacion) . ";\r\n";
        header('Content-Description: File Transfer');
        header('Content-Type: application/csv');
        header("Content-Disposition: attachment; filename=Reporte_facturas.csv");
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    }
    echo $row_excel_enc_sat;
    $handle = fopen('php://output', 'w');
}else if($seccion == "reportExcelReembolsos"){
        $estados = array(1=>"Aprobado",2=>"Rechazado", 3=>"Anulado", 4=>"En Aprobación");
    $row_excel_enc_sat = ("N°;RUT;Nombre Colaborador;Tipo Reembolso;Otro;Monto Total;Cuenta Contable;Proyecto;Centro de costo;Con /Sin OTA;OTA;Aplica/No Aplica;Nombre Ejecutivo;Recepción Ejecutivo;Entrega ejecutivo;Vencimiento;Periodo Contabilización;Observaciones;Estado;Fecha documento\r\n");
    $reembolsos = Lista_Reembolsos();

    foreach($reembolsos as $row){
        $consulResponsables=getRespReemboById($row->reembo_id);
        $fecVenc = "";
        $aplica = "No aplica";
        if(isset($consulResponsables[2]['recep'])){
            $aplica = "aplica";
            $fecVenc = date('Y-m-d', strtotime($consulResponsables[2]['recep']. ' + '.$consulResponsables[2]['sla'].' day'));

            while(date('N', strtotime($fecVenc))==7 || date('N', strtotime($fecVenc))==1){
                        $fecVenc = date('Y-m-d', strtotime($consulResponsables[0]->recep. ' + 1 day'));
            }
        }
        $digito = explode("-",$row->reembo_proveerut);
        $monto = str_replace(".",",",$row->reembo_montoto);
        $ota ="SIN OTA";
        $numota=0;
        if($row->reembo_ota==1){
            $ota ="CON OTA";
            $numota = $row->reembo_numota;
        }
        $row_excel_enc_sat .= $row->reembo_numdoc . ";"
        . $row->reembo_proveerut . ";"
        . ($row->proveedor_nombre) . ";"
             . ($row->tipdoc_dsc) . ";"
             . $row->reembo_tipdoc_otro . ";"
        . $monto . ";"
                . $row->reembo_cuenta . ";"
        . ($row->reembo_proyecto) . ";"
                . $row->cui_desc . ";"
        . $ota . ";"
        . $numota . ";"
        . $aplica . ";"
        . ($consulResponsables[2]['respnom']) . ";"
        . ($consulResponsables[2]['recep']) . ";"
        . ($consulResponsables[2]['envio']) . ";"
        . $fecVenc . ";"
        . $row->reembo_anual.$row->reembo_mes . ";"
        . ($row->reembo_observacion) . ";"
        . $estados[$row->reembo_status] . ";"
        . $row->reembo_fecemision . ";"
         . ";\r\n";
        header('Content-Description: File Transfer');
        header('Content-Type: application/csv');
        header("Content-Disposition: attachment; filename=Reporte_reembolsos.csv");
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    }
    echo $row_excel_enc_sat;
    $handle = fopen('php://output', 'w');
};
	//End JHONATAN
?>