<?php
function EstilosResultadoFinal2022($valor){
    if($valor>79.9){
        $estilo="btn-verde-clima-RF";
    }else if($valor>=70 && $valor<=79.9){
        $estilo="btn-amarillo-clima-RF";

    }else if($valor<=69.9){
        $estilo="btn-rojo-clima-RF";

    }

    return($estilo);

}


function EstilosBorde2022($valor){
    if($valor>79.9){
        $estilo="17aa30";
    }else if($valor>=70 && $valor<=79.9){
        $estilo="c1c412";

    }else if($valor<=69.9){
        $estilo="f44336";

    }

    return($estilo);

}

function CategoriaClima2022($valor){

    if($valor<=69.9){
        $categoria="Cr&iacute;tico";
    }else if($valor>=70 && $valor<=79.9){
        $categoria="Oportunidad de Mejora";
    }else if($valor>79.9){
        $categoria="Esperado";
    }

    return $categoria;
}



function ColocaBotonesClimaP($html, $rut){

    if($_GET["sw"]=="ClimaSindatosP"){
        $html 			= str_replace("{BOTON_PROFUNDIZACION_P}", '', $html);


    }
    if($_GET["sw"]=="clima_resultados_dex" or $_GET["sw"]=="clima_resultados_edex" or $_GET["sw"]=="clima_profundizacion_pe" or $_GET["sw"]=="ClimaSindatosP"){

        $pestana_profundizacion=DatosGlobalProfundizacionP($rut);
        $pestana_equipo=DatosGlobalJEfeP($rut);

        if($pestana_profundizacion){

            if($_GET["sw"]=="clima_profundizacion_pe"){
                $estilo_pestana_profundizacion="295eff";

            }else{
                $estilo_pestana_profundizacion="d3d6df";

            }



            $html 			= str_replace("{BOTON_PROFUNDIZACION_P}", '
			
			<div class="col-md-3 col-12 my-2">
						 
						 
						 <a href="?sw=clima_profundizacion_pe&re='.Encodear3($rut).'" class="btn btn-info btn-dashboard pull-center" style="border-radius: 1rem;
    				box-shadow: 0 4px 12px 0 rgb(0 36 100 / 10%);
    				background-color: #fff;
				    border-color: #fff;">
						<span style="    color: #'.$estilo_pestana_profundizacion.';font-weight: 600;font-family: Nunito;">
							<i class="fas fa-chevron-circle-right icon_mascon" style="color: #'.$estilo_pestana_profundizacion.';"></i> Profundizaci&oacute;n de '.$pestana_equipo[0]->nombre.'
						</span>
					</a>
					
					
                        </div>
							
							', $html);

        }else{

            $html 			= str_replace("{BOTON_PROFUNDIZACION_P}", '', $html);

        }





        if($pestana_equipo){

            if($_GET["sw"]=="clima_resultados_dex" or $_GET["sw"]=="ClimaSindatosP"){
                $estilo_pestana_equipo="295eff";

            }else{
                $estilo_pestana_equipo="d3d6df";

            }

            $html 			= str_replace("{BOTON_REPORTES_DIRECTOS_P}", '
			
			<div class="col-md-3 col-12 my-2">
						 
						 
						 <a href="?sw=clima_resultados_dex&re='.Encodear3($rut).'" class="btn btn-info btn-dashboard pull-center" style="border-radius: 1rem;
    				box-shadow: 0 4px 12px 0 rgb(0 36 100 / 10%);
    				background-color: #fff;
				    border-color: #fff;">
						<span style="    color: #'.$estilo_pestana_equipo.';font-weight: 600;font-family: Nunito;">
							<i class="fas fa-chevron-circle-right icon_mascon" style="color: #'.$estilo_pestana_equipo.';"></i> Resultados de '.$pestana_equipo[0]->nombre.'
						</span>
					</a>
					
					
                        </div>
							
							', $html);

        }else{

            $html 			= str_replace("{BOTON_REPORTES_DIRECTOS_P}", '', $html);


        }


        $pestana_estructura=DatosGlobalEstructuraP($rut);
        if($pestana_estructura){

            if($_GET["sw"]=="clima_resultados_edex"){
                $estilo_pestana_estructura="295eff";

            }else{
                $estilo_pestana_estructura="d3d6df";

            }


            $html 			= str_replace("{BOTON_ESTRUCTURA_P}", '
			
			<div class="col-md-3 col-12 my-2">
						 
						 
						 <a href="?sw=clima_resultados_edex&re='.Encodear3($rut).'" class="btn btn-info btn-dashboard pull-center" style="border-radius: 1rem;
    				box-shadow: 0 4px 12px 0 rgb(0 36 100 / 10%);
    				background-color: #fff;
				    border-color: #fff;">
						<span style="    color: #'.$estilo_pestana_estructura.';font-weight: 600;font-family: Nunito;">
							<i class="fas fa-chevron-circle-right icon_mascon" style="color: #'.$estilo_pestana_estructura.';"></i> Estructura de '.$pestana_estructura[0]->nombre.'
						</span>
					</a>
					
					
                        </div>
							
							', $html);

        }else{

            $html 			= str_replace("{BOTON_ESTRUCTURA_P}", '', $html);


        }



    }else{



        $pestana_equipo=DatosGlobalJEfeP($rut);
        if($pestana_equipo){
            if($_GET["sw"]=="clima_resultados_d"){
                $estilo_pestana_equipo="295eff";

            }else{
                $estilo_pestana_equipo="d3d6df";

            }

            $html 			= str_replace("{BOTON_REPORTES_DIRECTOS_P}", '
			
			<div class="col-md-3 col-12 my-2">
						 
						 
						 <a href="?sw=clima_resultados_d&i=me" class="btn btn-info btn-dashboard pull-center" style="border-radius: 1rem;
    				box-shadow: 0 4px 12px 0 rgb(0 36 100 / 10%);
    				background-color: #fff;
				    border-color: #fff;">
						<span style="    color: #'.$estilo_pestana_equipo.';font-weight: 600;font-family: Nunito;">
							<i class="fas fa-chevron-circle-right icon_mascon" style="color: #'.$estilo_pestana_equipo.';"></i> Mis resultados
						</span>
					</a>
					
					
                        </div>
							
							', $html);


        }else{

            $html 			= str_replace("{BOTON_REPORTES_DIRECTOS_P}", '', $html);



        }

        $pestana_estructura=DatosGlobalEstructuraP($rut);


        if($pestana_estructura){

            if($_GET["sw"]=="clima_resultados_ed"){
                $estilo_pestana_estructura="295eff";

            }else{
                $estilo_pestana_estructura="d3d6df";

            }

            $html 			= str_replace("{BOTON_ESTRUCTURA_P}", '
			
			<div class="col-md-3 col-12 my-2">
						 
						 
						 <a href="?sw=clima_resultados_ed" class="btn btn-info btn-dashboard pull-center" style="border-radius: 1rem;
    				box-shadow: 0 4px 12px 0 rgb(0 36 100 / 10%);
    				background-color: #fff;
				    border-color: #fff;">
						<span style="    color: #'.$estilo_pestana_estructura.';font-weight: 600;font-family: Nunito;">
							<i class="fas fa-chevron-circle-right icon_mascon" style="color: #'.$estilo_pestana_estructura.';"></i> Mi estructura
						</span>
					</a>
					
					
                        </div>
							
							', $html);

        }else{
            $html 			= str_replace("{BOTON_ESTRUCTURA_P}", '', $html);
        }



        $pestana_profundizacion=DatosGlobalProfundizacionP($rut);
        if($pestana_profundizacion){


            if($_GET["sw"]=="clima_profundizacion_p"){
                $estilo_pestana_profundizacion="295eff";

            }else{
                $estilo_pestana_profundizacion="d3d6df";

            }



            $html 			= str_replace("{BOTON_PROFUNDIZACION_P}", '
			
			<div class="col-md-3 col-12 my-2">
						 
						 
						 <a href="?sw=clima_profundizacion_p" class="btn btn-info btn-dashboard pull-center" style="border-radius: 1rem;
    				box-shadow: 0 4px 12px 0 rgb(0 36 100 / 10%);
    				background-color: #fff;
				    border-color: #fff;">
						<span style="    color: #'.$estilo_pestana_profundizacion.';font-weight: 600;font-family: Nunito;">
							<i class="fas fa-chevron-circle-right icon_mascon" style="color: #'.$estilo_pestana_profundizacion.';"></i> Profundizaci&oacute;n
						</span>
					</a>
					
					
                        </div>
							
							', $html);

        }else{

            $html 			= str_replace("{BOTON_PROFUNDIZACION_P}", '', $html);

        }





    }


    $html 			= str_replace("{BOTON_BANCO_P}", '', $html);

    return($html);

}



function EstilosBorde($valor){
	if($valor>=66.6){
		$estilo="17aa30";
	}else if($valor>=56.6 && $valor<=66.5){
		$estilo="c1c412";
		
	}else if($valor<=56.5){
		$estilo="f44336";
		
	}
	
	return($estilo);
	
}

function EstilosResultadoFinal($valor){
	if($valor>=66.6){
		$estilo="btn-verde-clima-RF";
	}else if($valor>=56.6 && $valor<=66.5){
		$estilo="btn-amarillo-clima-RF";
		
	}else if($valor<=56.5){
		$estilo="btn-rojo-clima-RF";
		
	}
	
	return($estilo);
	
}

function CambiaPuntoPorComa($valor){
	
	$valor=round($valor, 2);
	$valor 			= str_replace(".", ',', $valor);
	
	return($valor);
}


function FuncionesTransversalesClima($html, $rut){
	
	//Boton Banco
	$acceso_resultado_banco=AccesoAResultadoBanco($rut);
	//if($rut=="9851837"){
		
	if(!$_GET["i"]){
		if(!$_GET["re"]){
			
			$color_texto="#295eff"; 
		}else{
			$color_texto="#d3d6df";
		}
	}
	
	if(Decodear3($_GET["re"]) or Decodear3($_GET["rex"])){
		$html 			= str_replace("{BOTON_BANCO}", '', $html);
		//$html 			= str_replace("{BOTON_ESTRUCTURA}", '', $html);
		
		$rut_ve=Decodear3($_GET["re"]);
		if(Decodear3($_GET["rex"])){
			$rut_ve=Decodear3($_GET["rex"]);
			
		}
		$acceso_estructura=AccesoAEstructura($rut_ve);
		$datos_usuario = UsuarioEnBasePersonas($rut_ve, $rut_ve);
		
		if($_GET["i"]=="mr"){
		$color_texto="#295eff"; 
		
		}else{
			$color_texto="#d3d6df";
			
		}
		
		
		if($acceso_estructura){
			$html 			= str_replace("{BOTON_ESTRUCTURA}", ' 
			
			
			 <div class="col-md-3 col-12 my-2">
							 
							 
							 <a href="?sw=clima_resultados&i=mr&rex='.Encodear3($rut_ve).'" class="btn btn-info btn-dashboard pull-center" style="border-radius: 1rem;
						box-shadow: 0 4px 12px 0 rgb(0 36 100 / 10%);
						background-color: #fff;
						border-color: #fff;">
							<span style="    color: '.$color_texto.';font-weight: 600;font-family: Nunito;">
								<i class="fas fa-chevron-circle-right icon_mascon" style="color: #295eff;"></i> Estructura de '.$datos_usuario[0]->nombre_completo.'
							</span>
						</a>
						
						
							</div>
							
							
							
			
			', $html);
			
		}else{
			
			$html 			= str_replace("{BOTON_ESTRUCTURA}", '', $html); 
			
		}
		
		
		
		
		//$html 			= str_replace("{BOTON_REPORTES_DIRECTOS}", '', $html);
		
		
		$acceso_reportes_directos=AccesoAReportesDirectos($rut_ve);
		$datos_usuario = UsuarioEnBasePersonas($rut_ve, $rut_ve);
		
		if($_GET["i"]=="me" or $_GET["re"]){
		$color_texto="#295eff"; 
		
		}else{
			$color_texto="#d3d6df";
			
		}
		
		/*if($_GET["re"]){
			$color_texto="#295eff"; 
		}else{
			$color_texto="#d3d6df";
		}
		*/
		
		if($acceso_reportes_directos){
			$html 			= str_replace("{BOTON_REPORTES_DIRECTOS}", '
			
			
			<div class="col-md-3 col-12 my-2">
							 
							 
							 <a href="?sw=clima_resultados&i=me&rex='.Encodear3($rut_ve).'" class="btn btn-info btn-dashboard pull-center" style="border-radius: 1rem;
						box-shadow: 0 4px 12px 0 rgb(0 36 100 / 10%);
						background-color: #fff;
						border-color: #fff;">
							<span style="    color: '.$color_texto.';font-weight: 600;font-family: Nunito;">
								<i class="fas fa-chevron-circle-right icon_mascon" style="color: #295eff;"></i> Resultados de '.$datos_usuario[0]->nombre_completo.'
							</span>
						</a>
						
						
							</div>
							
							', $html);
			
		}else{
			$html 			= str_replace("{BOTON_REPORTES_DIRECTOS}", '', $html);
			
		}
		
		
		
		
		$html 			= str_replace("{BOTON_PROFUNDIZACION}", '', $html);
		
		
		
	}
	
	if($acceso_resultado_banco){
		
		$html 			= str_replace("{BOTON_BANCO}", ' <div class="col-md-3 col-12 my-2">
						 
						 
						 <a href="?sw=clima_resultados" class="btn btn-info btn-dashboard pull-center" style="border-radius: 1rem;
    				box-shadow: 0 4px 12px 0 rgb(0 36 100 / 10%);
    				background-color: #fff;
				    border-color: #fff;">
						<span style="    color: '.$color_texto.';font-weight: 600;font-family: Nunito;">
							<i class="fas fa-chevron-circle-right icon_mascon" style="color: #295eff;"></i> Resultado Banco
						</span>
					</a>
					
					
                        </div>', $html);
		
	}else{
		
		
		$html 			= str_replace("{BOTON_BANCO}", '', $html);
		
	}
	
	
	
	//Acceso a Estructura
	if($_GET["i"]=="mr"){
		$color_texto="#295eff"; 
		
	}else{
		$color_texto="#d3d6df";
		
	}
	$acceso_estructura=AccesoAEstructura($rut);
	if($acceso_estructura){
		$html 			= str_replace("{BOTON_ESTRUCTURA}", ' 
		
		
		 <div class="col-md-3 col-12 my-2">
						 
						 
						 <a href="?sw=clima_resultados&i=mr" class="btn btn-info btn-dashboard pull-center" style="border-radius: 1rem;
    				box-shadow: 0 4px 12px 0 rgb(0 36 100 / 10%);
    				background-color: #fff;
				    border-color: #fff;">
						<span style="    color: '.$color_texto.';font-weight: 600;font-family: Nunito;">
							<i class="fas fa-chevron-circle-right icon_mascon" style="color: #295eff;"></i> Mi estructura
						</span>
					</a>
					
					
                        </div>
						
						
						
		
		', $html);
		
	}else{
		
		$html 			= str_replace("{BOTON_ESTRUCTURA}", '', $html);
		
	}
	
	
	//Acceso Reportes Directos
	if($_GET["i"]=="me"){
		$color_texto="#295eff"; 
		
	}else{
		$color_texto="#d3d6df";
		
	}
	$acceso_reportes_directos=AccesoAReportesDirectos($rut);
	if($acceso_reportes_directos){
		$html 			= str_replace("{BOTON_REPORTES_DIRECTOS}", '
		
		
		<div class="col-md-3 col-12 my-2">
						 
						 
						 <a href="?sw=clima_resultados&i=me" class="btn btn-info btn-dashboard pull-center" style="border-radius: 1rem;
    				box-shadow: 0 4px 12px 0 rgb(0 36 100 / 10%);
    				background-color: #fff;
				    border-color: #fff;">
						<span style="    color: '.$color_texto.';font-weight: 600;font-family: Nunito;">
							<i class="fas fa-chevron-circle-right icon_mascon" style="color: #295eff;"></i> Mis resultados
						</span>
					</a>
					
					
                        </div>
						
						', $html);
		
	}else{
		$html 			= str_replace("{BOTON_REPORTES_DIRECTOS}", '', $html);
		
	}
	
	
	
	//Acceso a Boton de Profundizacion
	$acceso_a_profundizacion=AccesoAProfundizacion($rut);
	
	if($_GET["sw"]=="clima_profundizacion"){
		$color_texto="#295eff";
		
	}else{
		$color_texto="#d3d6df";
		
	}
	if($acceso_a_profundizacion){
		
		$html 			= str_replace("{BOTON_PROFUNDIZACION}", '
		
		
			<div class="col-md-3 col-12 my-2">
						 
						 
						 <a href="?sw=clima_profundizacion" class="btn btn-info btn-dashboard pull-center" style="border-radius: 1rem;
    				box-shadow: 0 4px 12px 0 rgb(0 36 100 / 10%);
    				background-color: #fff;
				    border-color: #fff;">
						<span style="    color: '.$color_texto.';font-weight: 600;font-family: Nunito;">
							<i class="fas fa-chevron-circle-right icon_mascon" style="color: #295eff;"></i> Profundizaci&oacute;n
						</span>
					</a>
					
					
                        </div>
		', $html);
	}else{
		$html 			= str_replace("{BOTON_PROFUNDIZACION}", '', $html);
	}
	
	return($html);
}

function ResultadosClimaTotal($cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1){
	
	
	
	
	 $rut 						= $_SESSION["user_"];
	 
	
	//ini_set('display_errors', 1);ini_set('display_startup_errors', 1);error_reporting(E_ALL);
	
	//echo "test";
	
	/*$tiene_acceso_resultados=CLIMA_TieneAccesoResultados($rut);
		if($tiene_acceso_resultados){
			
		}else{
			exit(" acceso denegado");
		}
		
		*/
	//exit(" ok ");
	
	//por Cada Categoria , de 1 a 5
	////echo "<h1>Resultados por Dimensi�n</h1>";
	
	
	/*$cod_division_ajustado=$_GET["cod_division_ajustado"];
	$cod_area_ajustado=$_GET["cod_area_ajustado"];
	$cod_depto_ajustado=$_GET["cod_depto_ajustado"];
	$cod_zona_ajustado=$_GET["cod_zona_ajustado"];
	$cod_secc_ajustado=$_GET["cod_secc_ajustado"];
	$unidad_cuipr_ajustado=$_GET["unidad_cuipr_ajustado"];
	$cod_sucursal_ajustado=$_GET["cod_sucursal_ajustado"];
	
	$rut_jefe=$_GET["rut_jefe"];
	$rut_jefe_nivel1=$_GET["rut_jefe_nivel1"];
	
	
	$sexo=$_GET["sexo"];
	*/
	
	//Datos Banco
	///////////////////////$categorias=ClimaCategoriasPorEvaluacion(1, "", "", "", "", "", "", "", "", "", "");
	$acumulador_respuestas_5_banco=0;
	$acumulador_total_respuestas_banco=0;
	$i=0;
	
	$arrayDetalle["banco_1"] = array(  "dimension1"        => "66.92","total1"      => $cat->resp_total);
	$arrayDetalle["banco_2"] = array(  "dimension2"        => "80.36","total2"      => $cat->resp_total);
	$arrayDetalle["banco_3"] = array(  "dimension3"        => "68.41","total3"      => $cat->resp_total);
	$arrayDetalle["banco_4"] = array(  "dimension4"        => "82.9","total4"      => $cat->resp_total);
	$arrayDetalle["banco_5"] = array(  "dimension5"        => "77.01","total5"      => $cat->resp_total);
	
	
	foreach($categorias as $cat){
		
	////	echo "Id Dimension: ".$cat->id_categoria." ".$cat->descripcion;
		// por cada dimension / cateogoria, 
	////	echo "<br>";
		//cuento cuantas respuestas
		$resultado=round((($cat->resp_nivel_5*100)/$cat->resp_total), 2);
		
		/*$array_dimensiones.="dimension".$cat->id_categoria."=".$resultado.";dimension".$cat->id_categoria."_total_respuestas=".$cat->resp_total;
		if($contador<count($categorias)){
			$array_dimensiones.=";";
		}
		$contador++;*/
		
		
		  $arrayDetalle["banco_".$cat->id_categoria] = array(  #Aqu� est� la respuesta, usa [] luego del nombre del array.
        "dimension".$cat->id_categoria        => $resultado,
        "total".$cat->id_categoria      => $cat->resp_total
       
    );
		
		
	////	echo "Respuestas Totales: ".$cat->resp_total;
		$acumulador_total_respuestas_banco=$acumulador_total_respuestas_banco+$cat->resp_total;
		
	////	echo "<br>";
		
	////	echo "Respuestas Nivel 5: ".$cat->resp_nivel_5;
		$acumulador_respuestas_5_banco=$acumulador_respuestas_5_banco+$cat->resp_nivel_5;
	////	echo "<br>";
		
		
	////	echo "Resultado: ".$resultado."%";
	////	echo "<br>";
	////	echo "<br>";
	////	echo "<br>";
		//echo "Calculo de las notas de cada dimension<br><br>";
		
		
	}
	
	
	
	//fin Datos Banco Dimension
	
	
	
	
	$categorias=ClimaCategoriasPorEvaluacion(1, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
	
	$total_usuarios=TotalUsuariosRespondidos($cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
	$total_usuarios_total=TotalUsuariosFiltrados($cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
	
	
	//print_r($categorias);exit;
	$acumulador_respuestas_5=0;
	$acumulador_total_respuestas=0;
	$i=0;
	foreach($categorias as $cat){
		
	////	echo "Id Dimension: ".$cat->id_categoria." ".$cat->descripcion;
		// por cada dimension / cateogoria, 
	////	echo "<br>";
		//cuento cuantas respuestas
		$resultado=round((($cat->resp_nivel_5*100)/$cat->resp_total), 2);
		
		/*$array_dimensiones.="dimension".$cat->id_categoria."=".$resultado.";dimension".$cat->id_categoria."_total_respuestas=".$cat->resp_total;
		if($contador<count($categorias)){
			$array_dimensiones.=";";
		}
		$contador++;*/
		
		
		  $arrayDetalle[$cat->id_categoria] = array(  #Aqu� est� la respuesta, usa [] luego del nombre del array.
        "dimension".$cat->id_categoria        => $resultado,
        "total".$cat->id_categoria      => $cat->resp_total
       
    );
		
		
	////	echo "Respuestas Totales: ".$cat->resp_total;
		$acumulador_total_respuestas=$acumulador_total_respuestas+$cat->resp_total;
		
	////	echo "<br>";
		
	////	echo "Respuestas Nivel 5: ".$cat->resp_nivel_5;
		$acumulador_respuestas_5=$acumulador_respuestas_5+$cat->resp_nivel_5;
	////	echo "<br>";
		
		
	////	echo "Resultado: ".$resultado."%";
	////	echo "<br>";
	////	echo "<br>";
	////	echo "<br>";
		//echo "Calculo de las notas de cada dimension<br><br>";
		
		
	}


		
		$recomendacion1_banco=ClimaRecomendacion1("", "", "", "", "", "", "", "", "", ""); 
		//print_r($recomendacion1_banco);exit;
		$recomendacion2_banco=ClimaRecomendacion2("", "", "", "", "", "", "", "", "", "");
		$recomendacion3_banco=ClimaRecomendacion3("", "", "", "", "", "", "", "", "", "");
		
		$total_recomendacion_banco=$recomendacion1_banco[0]->total+$recomendacion2_banco[0]->total+$recomendacion3_banco[0]->total;
		$porcentaje1_banco=round((($recomendacion1_banco[0]->total*100)/$total_recomendacion_banco), 2);
		$porcentaje2_banco=round((($recomendacion2_banco[0]->total*100)/$total_recomendacion_banco), 2);
		$nota_recomendacion_banco=$porcentaje2_banco-$porcentaje1_banco;
		
		
		
		
		$recomendacion1=ClimaRecomendacion1($cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1); 
		$recomendacion2=ClimaRecomendacion2($cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$recomendacion3=ClimaRecomendacion3($cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		
		
		
		
		
		
		
		//$total=$recomendacion1[0]->total+$recomendacion2[0]->total;
		$total=$recomendacion1[0]->total+$recomendacion2[0]->total+$recomendacion3[0]->total;
		////echo "<br>Total de Respuestas 1, 2, 3, 4, 5, 6 <br>Total: ".$recomendacion1[0]->total;
		////////$porcentaje1=round((($recomendacion1[0]->total*100)/$total), 2);
		$porcentaje1=(($recomendacion1[0]->total*100)/$total);
		////echo "<br><b>$porcentaje1%</b>";
		////echo "<br>";
		
		////echo "<br>Total de Respuestas 8, 9, 10 <br>Total: ".$recomendacion2[0]->total;
		//$porcentaje2=round((($recomendacion2[0]->total*100)/$total), 2);
		$porcentaje2=(($recomendacion2[0]->total*100)/$total);
		////echo "<br><b>$porcentaje2%</b>";
		////echo "<br><br>";
		$nota_recomendacion=$porcentaje2-$porcentaje1;
		////echo "<b>Nota de Recomendaci�n: ".$nota_recomendacion."%</b>"; 
		////echo "<br><br>";
		////echo "<hr>";
		
		////echo "<h1>Nota Final de Clima</h1>";
		////echo "Total respuestas Nivel 5 ".$acumulador_respuestas_5;
		////echo "<br>";
		////echo "Total respuestas ".$acumulador_total_respuestas;
		////echo "<br>";
		$resultado_final=round((($acumulador_respuestas_5*100)/$acumulador_total_respuestas), 2);
		$resultado_final_banco=round((($acumulador_respuestas_5_banco*100)/$acumulador_total_respuestas_banco), 2);
		////echo "<b>Resultado Final ".$resultado_final."%</b>";
		////echo "<hr>";
		
		
	
	
	
	

$persona = [
    "total_usuarios" => $total_usuarios[0]->total,
    "total_usuarios_total" => $total_usuarios_total[0]->total,
    "resultado_final" => $resultado_final,
    "resultado_final_banco" => "75.13", 
    "respuestas_nivel_5" => $acumulador_respuestas_5,
    "total_respuestas" => $acumulador_total_respuestas,
    "recomendacion1" => $porcentaje1,
    "recomendacion1_banco" => $porcentaje1_banco,
    "recomendacion1_total" => $recomendacion1[0]->total,
	"recomendacion2" => $porcentaje2,
	"recomendacion2_banco" => $porcentaje2_banco,
	"recomendacion1_tota2" => $recomendacion2[0]->total,
	
	"profundizacion1" => $porcentaje_1_1,
	"profundizacion1_tota2" => $pregunta_1_opcion_1[0]->total,
	
	"profundizacion2" => $porcentaje_1_2,
	"profundizacion2_tota2" => $pregunta_1_opcion_2[0]->total,
	
	
	"profundizacion3" => $porcentaje_1_3,
	"profundizacion3_tota2" => $pregunta_1_opcion_3[0]->total,
	
	
	"profundizacion4" => $porcentaje_1_4,
	"profundizacion4_tota2" => $pregunta_1_opcion_4[0]->total,
	
	"profundizacion5" => $porcentaje_1_5,
	"profundizacion5_tota2" => $pregunta_1_opcion_5[0]->total,
	
	"profundizacion6" => $porcentaje_1_6,
	"profundizacion6_tota2" => $pregunta_1_opcion_6[0]->total,
	
	"profundizacion7" => $porcentaje_1_7,
	"profundizacion7_tota2" => $pregunta_1_opcion_7[0]->total, 
	
	
	
	"profundizacion1_2" => $porcentaje_2_1,
	"profundizacion1_tota2_2" => $pregunta_2_opcion_1[0]->total,
	
	"profundizacion2_2" => $porcentaje_2_2,
	"profundizacion2_tota2_2" => $pregunta_2_opcion_2[0]->total,
	
	
	"profundizacion3_2" => $porcentaje_2_3,
	"profundizacion3_tota2_2" => $pregunta_2_opcion_3[0]->total,
	
	
	"profundizacion4_2" => $porcentaje_2_4,
	"profundizacion4_tota2_2" => $pregunta_2_opcion_4[0]->total,
	
	"profundizacion5_2" => $porcentaje_2_5,
	"profundizacion5_tota2_2" => $pregunta_2_opcion_5[0]->total,
	
	"profundizacion6_2" => $porcentaje_2_6,
	"profundizacion6_tota2_2" => $pregunta_2_opcion_6[0]->total,
	
	"profundizacion7_2" => $porcentaje_2_7,
	"profundizacion7_tota2_2" => $pregunta_2_opcion_7[0]->total, 
	
	
	
	
	"profundizacion1_3" => $porcentaje_3_1,
	"profundizacion1_tota2_3" => $pregunta_3_opcion_1[0]->total,
	
	"profundizacion2_3" => $porcentaje_3_2,
	"profundizacion2_tota2_3" => $pregunta_3_opcion_2[0]->total,
	
	
	"profundizacion3_3" => $porcentaje_3_3,
	"profundizacion3_tota2_3" => $pregunta_3_opcion_3[0]->total,
	
	
	"profundizacion4_3" => $porcentaje_3_4,
	"profundizacion4_tota2_3" => $pregunta_3_opcion_4[0]->total,
	
	"profundizacion5_3" => $porcentaje_3_5,
	"profundizacion5_tota2_3" => $pregunta_3_opcion_5[0]->total,
	
	"profundizacion6_3" => $porcentaje_3_6,
	"profundizacion6_tota2_3" => $pregunta_3_opcion_6[0]->total,
	
	"profundizacion7_3" => $porcentaje_3_7,
	"profundizacion7_tota2_3" => $pregunta_3_opcion_7[0]->total, 
	
	"profundizacion8_3" => $porcentaje_3_8,
	"profundizacion8_tota2_3" => $pregunta_3_opcion_8[0]->total, 
	
	
	
	
	"profundizacion1_4" => $porcentaje_4_1,
	"profundizacion1_tota2_4" => $pregunta_4_opcion_1[0]->total,
	
	"profundizacion2_4" => $porcentaje_4_2,
	"profundizacion2_tota2_4" => $pregunta_4_opcion_2[0]->total,
	
	
	"profundizacion3_4" => $porcentaje_4_3,
	"profundizacion3_tota2_4" => $pregunta_4_opcion_3[0]->total,
	
	
	"profundizacion4_4" => $porcentaje_4_4,
	"profundizacion4_tota2_4" => $pregunta_4_opcion_4[0]->total,
	
	"profundizacion5_4" => $porcentaje_4_5,
	"profundizacion5_tota2_4" => $pregunta_4_opcion_5[0]->total,
	
	"profundizacion6_4" => $porcentaje_4_6,
	"profundizacion6_tota2_4" => $pregunta_4_opcion_6[0]->total,
	
	"profundizacion7_4" => $porcentaje_4_7,
	"profundizacion7_tota2_4" => $pregunta_4_opcion_7[0]->total, 
	
	"profundizacion8_4" => $porcentaje_4_8,
	"profundizacion8_tota2_4" => $pregunta_4_opcion_8[0]->total, 
	
	"profundizacion9_4" => $porcentaje_4_9,
	"profundizacion9_tota2_4" => $pregunta_4_opcion_9[0]->total, 
	
	
	
	"array_dimensiones" => $arrayDetalle 
	
	
];
/*
echo "<pre>";
print_r($persona);
*/

// Los codificamos
// recomendado: https://parzibyte.me/blog/2018/12/26/codificar-decodificar-json-php/
$datosCodificados = json_encode($persona);
return($datosCodificados);

//echo $datosCodificados;
//exit;

		




	
	
	
	
	
	
}


function ResultadosClimaTotalProfundizacion($cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1){
	
	
	
	
	 $rut 						= $_SESSION["user_"];
	 
	
	//ini_set('display_errors', 1);ini_set('display_startup_errors', 1);error_reporting(E_ALL);
	
	//echo "test";
	
	/*$tiene_acceso_resultados=CLIMA_TieneAccesoResultados($rut);
		if($tiene_acceso_resultados){
			
		}else{
			exit(" acceso denegado");
		}
	
	*/
	$total_usuarios=TotalUsuariosRespondidos($cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
	$total_usuarios_total=TotalUsuariosFiltrados($cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
	
	
	
	
		//BANCO
		$pregunta_1_total=RespuestasProfundizacion(23, "", $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1); 
		$pregunta_1_opcion_1=RespuestasProfundizacion(23, 1, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_1_1=round((($pregunta_1_opcion_1[0]->total*100)/$pregunta_1_total[0]->total), 2);
		if (is_nan($porcentaje_1_1)) $porcentaje_1_1 = 0;
		
		
		$pregunta_1_opcion_2=RespuestasProfundizacion(23, 2, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_1_2=round((($pregunta_1_opcion_2[0]->total*100)/$pregunta_1_total[0]->total), 2);
		if (is_nan($porcentaje_1_2)) $porcentaje_1_2 = 0;
		
		
		$pregunta_1_opcion_3=RespuestasProfundizacion(23, 3, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_1_3=round((($pregunta_1_opcion_3[0]->total*100)/$pregunta_1_total[0]->total), 2);
		if (is_nan($porcentaje_1_3)) $porcentaje_1_3 = 0;
		
		
		$pregunta_1_opcion_4=RespuestasProfundizacion(23, 4, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_1_4=round((($pregunta_1_opcion_4[0]->total*100)/$pregunta_1_total[0]->total), 2);
		if (is_nan($porcentaje_1_4)) $porcentaje_1_4 = 0;
		
		
		$pregunta_1_opcion_5=RespuestasProfundizacion(23, 5, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_1_5=round((($pregunta_1_opcion_5[0]->total*100)/$pregunta_1_total[0]->total), 2);
		if (is_nan($porcentaje_1_5)) $porcentaje_1_5 = 0;
		
		
		$pregunta_1_opcion_6=RespuestasProfundizacion(23, 6, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_1_6=round((($pregunta_1_opcion_6[0]->total*100)/$pregunta_1_total[0]->total), 2);
		if (is_nan($porcentaje_1_6)) $porcentaje_1_6 = 0;
		
		
		$pregunta_1_opcion_7=RespuestasProfundizacion(23, 7, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_1_7=round((($pregunta_1_opcion_7[0]->total*100)/$pregunta_1_total[0]->total), 2);
		if (is_nan($porcentaje_1_7)) $porcentaje_1_7 = 0;
		
		
		//Jefatura
		$pregunta_2_total=RespuestasProfundizacion(24, "", $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1); 
		$pregunta_2_opcion_1=RespuestasProfundizacion(24, 1, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_2_1=round((($pregunta_2_opcion_1[0]->total*100)/$pregunta_2_total[0]->total), 2);
		if (is_nan($porcentaje_2_1)) $porcentaje_2_1 = 0;
		
		
		$pregunta_2_opcion_2=RespuestasProfundizacion(24, 2, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_2_2=round((($pregunta_2_opcion_2[0]->total*100)/$pregunta_2_total[0]->total), 2);
		if (is_nan($porcentaje_2_2)) $porcentaje_2_2 = 0;
		
		
		$pregunta_2_opcion_3=RespuestasProfundizacion(24, 3, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_2_3=round((($pregunta_2_opcion_3[0]->total*100)/$pregunta_2_total[0]->total), 2);
		if (is_nan($porcentaje_2_3)) $porcentaje_2_3 = 0;
		
		
		$pregunta_2_opcion_4=RespuestasProfundizacion(24, 4, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_2_4=round((($pregunta_2_opcion_4[0]->total*100)/$pregunta_2_total[0]->total), 2);
		if (is_nan($porcentaje_2_4)) $porcentaje_2_4 = 0;
		
		
		$pregunta_2_opcion_5=RespuestasProfundizacion(24, 5, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_2_5=round((($pregunta_2_opcion_5[0]->total*100)/$pregunta_2_total[0]->total), 2);
		if (is_nan($porcentaje_2_5)) $porcentaje_2_5 = 0;
		
		
		$pregunta_2_opcion_6=RespuestasProfundizacion(24, 6, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_2_6=round((($pregunta_2_opcion_6[0]->total*100)/$pregunta_2_total[0]->total), 2);
		if (is_nan($porcentaje_2_6)) $porcentaje_2_6 = 0;
		
		
		$pregunta_2_opcion_7=RespuestasProfundizacion(24, 7, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_2_7=round((($pregunta_2_opcion_7[0]->total*100)/$pregunta_2_total[0]->total), 2);
		if (is_nan($porcentaje_2_7)) $porcentaje_2_7 = 0;
		
		
		
		
		//Compa�er@s
		$pregunta_3_total=RespuestasProfundizacion(25, "", $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1); 
		$pregunta_3_opcion_1=RespuestasProfundizacion(25, 1, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_3_1=round((($pregunta_3_opcion_1[0]->total*100)/$pregunta_3_total[0]->total), 2);
		if (is_nan($porcentaje_3_1)) $porcentaje_3_1 = 0;
		
		
		$pregunta_3_opcion_2=RespuestasProfundizacion(25, 2, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_3_2=round((($pregunta_3_opcion_2[0]->total*100)/$pregunta_3_total[0]->total), 2);
		if (is_nan($porcentaje_3_2)) $porcentaje_3_2 = 0;
		
		
		$pregunta_3_opcion_3=RespuestasProfundizacion(25, 3, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_3_3=round((($pregunta_3_opcion_3[0]->total*100)/$pregunta_3_total[0]->total), 2);
		if (is_nan($porcentaje_3_3)) $porcentaje_3_3 = 0;
		
		
		$pregunta_3_opcion_4=RespuestasProfundizacion(25, 4, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_3_4=round((($pregunta_3_opcion_4[0]->total*100)/$pregunta_3_total[0]->total), 2);
		if (is_nan($porcentaje_3_4)) $porcentaje_3_4 = 0;
		
		
		$pregunta_3_opcion_5=RespuestasProfundizacion(25, 5, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_3_5=round((($pregunta_3_opcion_5[0]->total*100)/$pregunta_3_total[0]->total), 2);
		if (is_nan($porcentaje_3_5)) $porcentaje_3_5 = 0;
		
		
		$pregunta_3_opcion_6=RespuestasProfundizacion(25, 6, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_3_6=round((($pregunta_3_opcion_6[0]->total*100)/$pregunta_3_total[0]->total), 2);
		if (is_nan($porcentaje_3_6)) $porcentaje_3_6 = 0;
		
		
		$pregunta_3_opcion_7=RespuestasProfundizacion(25, 7, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_3_7=round((($pregunta_3_opcion_7[0]->total*100)/$pregunta_3_total[0]->total), 2);
		if (is_nan($porcentaje_3_7)) $porcentaje_3_7 = 0;
		
		$pregunta_3_opcion_8=RespuestasProfundizacion(25, 8, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_3_8=round((($pregunta_3_opcion_8[0]->total*100)/$pregunta_3_total[0]->total), 2);
		if (is_nan($porcentaje_3_8)) $porcentaje_3_8 = 0;
		
		
		//ambito personal
		
		
		$pregunta_4_total=RespuestasProfundizacion(27, "", $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1); 
		$pregunta_4_opcion_1=RespuestasProfundizacion(27, 1, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_4_1=round((($pregunta_4_opcion_1[0]->total*100)/$pregunta_4_total[0]->total), 2);
		if (is_nan($porcentaje_4_1)) $porcentaje_4_1 = 0;
		
		
		$pregunta_4_opcion_2=RespuestasProfundizacion(27, 2, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_4_2=round((($pregunta_4_opcion_2[0]->total*100)/$pregunta_4_total[0]->total), 2);
		if (is_nan($porcentaje_4_2)) $porcentaje_4_2 = 0;
		
		
		$pregunta_4_opcion_3=RespuestasProfundizacion(27, 3, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_4_3=round((($pregunta_4_opcion_3[0]->total*100)/$pregunta_4_total[0]->total), 2);
		if (is_nan($porcentaje_4_3)) $porcentaje_4_3 = 0;
		
		
		$pregunta_4_opcion_4=RespuestasProfundizacion(27, 4, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_4_4=round((($pregunta_4_opcion_4[0]->total*100)/$pregunta_4_total[0]->total), 2);
		if (is_nan($porcentaje_4_4)) $porcentaje_4_4 = 0;
		
		
		$pregunta_4_opcion_5=RespuestasProfundizacion(27, 5, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_4_5=round((($pregunta_4_opcion_5[0]->total*100)/$pregunta_4_total[0]->total), 2);
		if (is_nan($porcentaje_4_5)) $porcentaje_4_5 = 0;
		
		
		$pregunta_4_opcion_6=RespuestasProfundizacion(27, 6, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_4_6=round((($pregunta_4_opcion_6[0]->total*100)/$pregunta_4_total[0]->total), 2);
		if (is_nan($porcentaje_4_6)) $porcentaje_4_6 = 0;
		
		
		$pregunta_4_opcion_7=RespuestasProfundizacion(27, 7, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_4_7=round((($pregunta_4_opcion_7[0]->total*100)/$pregunta_4_total[0]->total), 2);
		if (is_nan($porcentaje_4_7)) $porcentaje_4_7 = 0;
		
		$pregunta_4_opcion_8=RespuestasProfundizacion(27, 8, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_4_8=round((($pregunta_4_opcion_8[0]->total*100)/$pregunta_4_total[0]->total), 2);
		if (is_nan($porcentaje_4_8)) $porcentaje_4_8 = 0;
		
		$pregunta_4_opcion_9=RespuestasProfundizacion(27, 9, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_4_9=round((($pregunta_4_opcion_9[0]->total*100)/$pregunta_4_total[0]->total), 2);
		if (is_nan($porcentaje_4_9)) $porcentaje_4_9 = 0;
		
		
	
	
	
	

$persona = [
    "total_usuarios" => $total_usuarios[0]->total,
    "total_usuarios_total" => $total_usuarios_total[0]->total,
    "resultado_final" => $resultado_final,
    "resultado_final_banco" => $resultado_final_banco,
    "respuestas_nivel_5" => $acumulador_respuestas_5,
    "total_respuestas" => $acumulador_total_respuestas,
    "recomendacion1" => $porcentaje1,
   // "recomendacion1_banco" => $porcentaje1_banco,
    "recomendacion1_total" => $recomendacion1[0]->total,
	"recomendacion2" => $porcentaje2,
	//"recomendacion2_banco" => $porcentaje2_banco,
	"recomendacion1_tota2" => $recomendacion2[0]->total,
	
	"profundizacion1" => $porcentaje_1_1,
	"profundizacion1_tota2" => $pregunta_1_opcion_1[0]->total,
	
	"profundizacion2" => $porcentaje_1_2,
	"profundizacion2_tota2" => $pregunta_1_opcion_2[0]->total,
	
	
	"profundizacion3" => $porcentaje_1_3,
	"profundizacion3_tota2" => $pregunta_1_opcion_3[0]->total,
	
	
	"profundizacion4" => $porcentaje_1_4,
	"profundizacion4_tota2" => $pregunta_1_opcion_4[0]->total,
	
	"profundizacion5" => $porcentaje_1_5,
	"profundizacion5_tota2" => $pregunta_1_opcion_5[0]->total,
	
	"profundizacion6" => $porcentaje_1_6,
	"profundizacion6_tota2" => $pregunta_1_opcion_6[0]->total,
	
	"profundizacion7" => $porcentaje_1_7,
	"profundizacion7_tota2" => $pregunta_1_opcion_7[0]->total, 
	
	
	
	"profundizacion1_2" => $porcentaje_2_1,
	"profundizacion1_tota2_2" => $pregunta_2_opcion_1[0]->total,
	
	"profundizacion2_2" => $porcentaje_2_2,
	"profundizacion2_tota2_2" => $pregunta_2_opcion_2[0]->total,
	
	
	"profundizacion3_2" => $porcentaje_2_3,
	"profundizacion3_tota2_2" => $pregunta_2_opcion_3[0]->total,
	
	
	"profundizacion4_2" => $porcentaje_2_4,
	"profundizacion4_tota2_2" => $pregunta_2_opcion_4[0]->total,
	
	"profundizacion5_2" => $porcentaje_2_5,
	"profundizacion5_tota2_2" => $pregunta_2_opcion_5[0]->total,
	
	"profundizacion6_2" => $porcentaje_2_6,
	"profundizacion6_tota2_2" => $pregunta_2_opcion_6[0]->total,
	
	"profundizacion7_2" => $porcentaje_2_7,
	"profundizacion7_tota2_2" => $pregunta_2_opcion_7[0]->total, 
	
	
	
	
	"profundizacion1_3" => $porcentaje_3_1,
	"profundizacion1_tota2_3" => $pregunta_3_opcion_1[0]->total,
	
	"profundizacion2_3" => $porcentaje_3_2,
	"profundizacion2_tota2_3" => $pregunta_3_opcion_2[0]->total,
	
	
	"profundizacion3_3" => $porcentaje_3_3,
	"profundizacion3_tota2_3" => $pregunta_3_opcion_3[0]->total,
	
	
	"profundizacion4_3" => $porcentaje_3_4,
	"profundizacion4_tota2_3" => $pregunta_3_opcion_4[0]->total,
	
	"profundizacion5_3" => $porcentaje_3_5,
	"profundizacion5_tota2_3" => $pregunta_3_opcion_5[0]->total,
	
	"profundizacion6_3" => $porcentaje_3_6,
	"profundizacion6_tota2_3" => $pregunta_3_opcion_6[0]->total,
	
	"profundizacion7_3" => $porcentaje_3_7,
	"profundizacion7_tota2_3" => $pregunta_3_opcion_7[0]->total, 
	
	"profundizacion8_3" => $porcentaje_3_8,
	"profundizacion8_tota2_3" => $pregunta_3_opcion_8[0]->total, 
	
	
	
	
	"profundizacion1_4" => $porcentaje_4_1,
	"profundizacion1_tota2_4" => $pregunta_4_opcion_1[0]->total,
	
	"profundizacion2_4" => $porcentaje_4_2,
	"profundizacion2_tota2_4" => $pregunta_4_opcion_2[0]->total,
	
	
	"profundizacion3_4" => $porcentaje_4_3,
	"profundizacion3_tota2_4" => $pregunta_4_opcion_3[0]->total,
	
	
	"profundizacion4_4" => $porcentaje_4_4,
	"profundizacion4_tota2_4" => $pregunta_4_opcion_4[0]->total,
	
	"profundizacion5_4" => $porcentaje_4_5,
	"profundizacion5_tota2_4" => $pregunta_4_opcion_5[0]->total,
	
	"profundizacion6_4" => $porcentaje_4_6,
	"profundizacion6_tota2_4" => $pregunta_4_opcion_6[0]->total,
	
	"profundizacion7_4" => $porcentaje_4_7,
	"profundizacion7_tota2_4" => $pregunta_4_opcion_7[0]->total, 
	
	"profundizacion8_4" => $porcentaje_4_8,
	"profundizacion8_tota2_4" => $pregunta_4_opcion_8[0]->total, 
	
	"profundizacion9_4" => $porcentaje_4_9,
	"profundizacion9_tota2_4" => $pregunta_4_opcion_9[0]->total, 
	
	
	
	"array_dimensiones" => $arrayDetalle 
	
	
];

//print_r($persona);exit;

// Los codificamos
// recomendado: https://parzibyte.me/blog/2018/12/26/codificar-decodificar-json-php/
$datosCodificados = json_encode($persona);
return($datosCodificados);

//echo $datosCodificados;
//exit;

		




	
	
	
	
	
}



function ResultadosClimaTotalFULL($cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1){
	
	
	
	
	 $rut 						= $_SESSION["user_"];
	 
	
	//ini_set('display_errors', 1);ini_set('display_startup_errors', 1);error_reporting(E_ALL);
	
	//echo "test";
	
	/* $tiene_acceso_resultados=CLIMA_TieneAccesoResultados($rut);
		if($tiene_acceso_resultados){
			
		}else{
			exit(" acceso denegado");
		}
		
		
		*/
	//exit(" ok ");
	
	//por Cada Categoria , de 1 a 5
	////echo "<h1>Resultados por Dimensi�n</h1>";
	
	
	/*$cod_division_ajustado=$_GET["cod_division_ajustado"];
	$cod_area_ajustado=$_GET["cod_area_ajustado"];
	$cod_depto_ajustado=$_GET["cod_depto_ajustado"];
	$cod_zona_ajustado=$_GET["cod_zona_ajustado"];
	$cod_secc_ajustado=$_GET["cod_secc_ajustado"];
	$unidad_cuipr_ajustado=$_GET["unidad_cuipr_ajustado"];
	$cod_sucursal_ajustado=$_GET["cod_sucursal_ajustado"];
	
	$rut_jefe=$_GET["rut_jefe"];
	$rut_jefe_nivel1=$_GET["rut_jefe_nivel1"];
	
	
	$sexo=$_GET["sexo"];
	*/
	
	//Datos Banco
	$categorias=ClimaCategoriasPorEvaluacion(1, "", "", "", "", "", "", "", "", "", "");
	$acumulador_respuestas_5_banco=0;
	$acumulador_total_respuestas_banco=0;
	$i=0;
	foreach($categorias as $cat){
		
	////	echo "Id Dimension: ".$cat->id_categoria." ".$cat->descripcion;
		// por cada dimension / cateogoria, 
	////	echo "<br>";
		//cuento cuantas respuestas
		$resultado=round((($cat->resp_nivel_5*100)/$cat->resp_total), 2);
		
		/*$array_dimensiones.="dimension".$cat->id_categoria."=".$resultado.";dimension".$cat->id_categoria."_total_respuestas=".$cat->resp_total;
		if($contador<count($categorias)){
			$array_dimensiones.=";";
		}
		$contador++;*/
		
		
		  $arrayDetalle["banco_".$cat->id_categoria] = array(  #Aqu� est� la respuesta, usa [] luego del nombre del array.
        "dimension".$cat->id_categoria        => $resultado,
        "total".$cat->id_categoria      => $cat->resp_total
       
    );
		
		
	////	echo "Respuestas Totales: ".$cat->resp_total;
		$acumulador_total_respuestas_banco=$acumulador_total_respuestas_banco+$cat->resp_total;
		
	////	echo "<br>";
		
	////	echo "Respuestas Nivel 5: ".$cat->resp_nivel_5;
		$acumulador_respuestas_5_banco=$acumulador_respuestas_5_banco+$cat->resp_nivel_5;
	////	echo "<br>";
		
		
	////	echo "Resultado: ".$resultado."%";
	////	echo "<br>";
	////	echo "<br>";
	////	echo "<br>";
		//echo "Calculo de las notas de cada dimension<br><br>";
		
		
	}
	
	
	
	//fin Datos Banco Dimension
	
	
	
	
	$categorias=ClimaCategoriasPorEvaluacion(1, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
	
	$total_usuarios=TotalUsuariosRespondidos($cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
	$total_usuarios_total=TotalUsuariosFiltrados($cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
	
	
	//print_r($categorias);exit;
	$acumulador_respuestas_5=0;
	$acumulador_total_respuestas=0;
	$i=0;
	foreach($categorias as $cat){
		
	////	echo "Id Dimension: ".$cat->id_categoria." ".$cat->descripcion;
		// por cada dimension / cateogoria, 
	////	echo "<br>";
		//cuento cuantas respuestas
		$resultado=round((($cat->resp_nivel_5*100)/$cat->resp_total), 2);
		
		/*$array_dimensiones.="dimension".$cat->id_categoria."=".$resultado.";dimension".$cat->id_categoria."_total_respuestas=".$cat->resp_total;
		if($contador<count($categorias)){
			$array_dimensiones.=";";
		}
		$contador++;*/
		
		
		  $arrayDetalle[$cat->id_categoria] = array(  #Aqu� est� la respuesta, usa [] luego del nombre del array.
        "dimension".$cat->id_categoria        => $resultado,
        "total".$cat->id_categoria      => $cat->resp_total
       
    );
		
		
	////	echo "Respuestas Totales: ".$cat->resp_total;
		$acumulador_total_respuestas=$acumulador_total_respuestas+$cat->resp_total;
		
	////	echo "<br>";
		
	////	echo "Respuestas Nivel 5: ".$cat->resp_nivel_5;
		$acumulador_respuestas_5=$acumulador_respuestas_5+$cat->resp_nivel_5;
	////	echo "<br>";
		
		
	////	echo "Resultado: ".$resultado."%";
	////	echo "<br>";
	////	echo "<br>";
	////	echo "<br>";
		//echo "Calculo de las notas de cada dimension<br><br>";
		
		
	}


		
		$recomendacion1_banco=ClimaRecomendacion1("", "", "", "", "", "", "", "", "", ""); 
		$recomendacion2_banco=ClimaRecomendacion2("", "", "", "", "", "", "", "", "", "");
		$recomendacion3_banco=ClimaRecomendacion3("", "", "", "", "", "", "", "", "", "");
		
		$total_recomendacion_banco=$recomendacion1_banco[0]->total+$recomendacion2_banco[0]->total+$recomendacion3_banco[0]->total;
		$porcentaje1_banco=round((($recomendacion1_banco[0]->total*100)/$total), 2);
		$porcentaje2_banco=round((($recomendacion2_banco[0]->total*100)/$total), 2);
		$nota_recomendacion_banco=$porcentaje2_banco-$porcentaje1_banco;
		
		
		
		
		$recomendacion1=ClimaRecomendacion1($cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1); 
		$recomendacion2=ClimaRecomendacion2($cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$recomendacion3=ClimaRecomendacion3($cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		
		
		
		
		
		
		
		//$total=$recomendacion1[0]->total+$recomendacion2[0]->total;
		$total=$recomendacion1[0]->total+$recomendacion2[0]->total+$recomendacion3[0]->total;
		////echo "<br>Total de Respuestas 1, 2, 3, 4, 5, 6 <br>Total: ".$recomendacion1[0]->total;
		$porcentaje1=round((($recomendacion1[0]->total*100)/$total), 2);
		////echo "<br><b>$porcentaje1%</b>";
		////echo "<br>";
		
		////echo "<br>Total de Respuestas 8, 9, 10 <br>Total: ".$recomendacion2[0]->total;
		$porcentaje2=round((($recomendacion2[0]->total*100)/$total), 2);
		////echo "<br><b>$porcentaje2%</b>";
		////echo "<br><br>";
		$nota_recomendacion=$porcentaje2-$porcentaje1;
		////echo "<b>Nota de Recomendaci�n: ".$nota_recomendacion."%</b>"; 
		////echo "<br><br>";
		////echo "<hr>";
		
		////echo "<h1>Nota Final de Clima</h1>";
		////echo "Total respuestas Nivel 5 ".$acumulador_respuestas_5;
		////echo "<br>";
		////echo "Total respuestas ".$acumulador_total_respuestas;
		////echo "<br>";
		$resultado_final=round((($acumulador_respuestas_5*100)/$acumulador_total_respuestas), 2);
		$resultado_final_banco=round((($acumulador_respuestas_5_banco*100)/$acumulador_total_respuestas_banco), 2);
		////echo "<b>Resultado Final ".$resultado_final."%</b>";
		////echo "<hr>";
		
		//BANCO
		$pregunta_1_total=RespuestasProfundizacion(23, "", $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1); 
		$pregunta_1_opcion_1=RespuestasProfundizacion(23, 1, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_1_1=round((($pregunta_1_opcion_1[0]->total*100)/$pregunta_1_total[0]->total), 2);
		if (is_nan($porcentaje_1_1)) $porcentaje_1_1 = 0;
		
		
		$pregunta_1_opcion_2=RespuestasProfundizacion(23, 2, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_1_2=round((($pregunta_1_opcion_2[0]->total*100)/$pregunta_1_total[0]->total), 2);
		if (is_nan($porcentaje_1_2)) $porcentaje_1_2 = 0;
		
		
		$pregunta_1_opcion_3=RespuestasProfundizacion(23, 3, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_1_3=round((($pregunta_1_opcion_3[0]->total*100)/$pregunta_1_total[0]->total), 2);
		if (is_nan($porcentaje_1_3)) $porcentaje_1_3 = 0;
		
		
		$pregunta_1_opcion_4=RespuestasProfundizacion(23, 4, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_1_4=round((($pregunta_1_opcion_4[0]->total*100)/$pregunta_1_total[0]->total), 2);
		if (is_nan($porcentaje_1_4)) $porcentaje_1_4 = 0;
		
		
		$pregunta_1_opcion_5=RespuestasProfundizacion(23, 5, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_1_5=round((($pregunta_1_opcion_5[0]->total*100)/$pregunta_1_total[0]->total), 2);
		if (is_nan($porcentaje_1_5)) $porcentaje_1_5 = 0;
		
		
		$pregunta_1_opcion_6=RespuestasProfundizacion(23, 6, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_1_6=round((($pregunta_1_opcion_6[0]->total*100)/$pregunta_1_total[0]->total), 2);
		if (is_nan($porcentaje_1_6)) $porcentaje_1_6 = 0;
		
		
		$pregunta_1_opcion_7=RespuestasProfundizacion(23, 7, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_1_7=round((($pregunta_1_opcion_7[0]->total*100)/$pregunta_1_total[0]->total), 2);
		if (is_nan($porcentaje_1_7)) $porcentaje_1_7 = 0;
		
		
		//Jefatura
		$pregunta_2_total=RespuestasProfundizacion(24, "", $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1); 
		$pregunta_2_opcion_1=RespuestasProfundizacion(24, 1, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_2_1=round((($pregunta_2_opcion_1[0]->total*100)/$pregunta_2_total[0]->total), 2);
		if (is_nan($porcentaje_2_1)) $porcentaje_2_1 = 0;
		
		
		$pregunta_2_opcion_2=RespuestasProfundizacion(24, 2, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_2_2=round((($pregunta_2_opcion_2[0]->total*100)/$pregunta_2_total[0]->total), 2);
		if (is_nan($porcentaje_2_2)) $porcentaje_2_2 = 0;
		
		
		$pregunta_2_opcion_3=RespuestasProfundizacion(24, 3, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_2_3=round((($pregunta_2_opcion_3[0]->total*100)/$pregunta_2_total[0]->total), 2);
		if (is_nan($porcentaje_2_3)) $porcentaje_2_3 = 0;
		
		
		$pregunta_2_opcion_4=RespuestasProfundizacion(24, 4, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_2_4=round((($pregunta_2_opcion_4[0]->total*100)/$pregunta_2_total[0]->total), 2);
		if (is_nan($porcentaje_2_4)) $porcentaje_2_4 = 0;
		
		
		$pregunta_2_opcion_5=RespuestasProfundizacion(24, 5, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_2_5=round((($pregunta_2_opcion_5[0]->total*100)/$pregunta_2_total[0]->total), 2);
		if (is_nan($porcentaje_2_5)) $porcentaje_2_5 = 0;
		
		
		$pregunta_2_opcion_6=RespuestasProfundizacion(24, 6, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_2_6=round((($pregunta_2_opcion_6[0]->total*100)/$pregunta_2_total[0]->total), 2);
		if (is_nan($porcentaje_2_6)) $porcentaje_2_6 = 0;
		
		
		$pregunta_2_opcion_7=RespuestasProfundizacion(24, 7, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_2_7=round((($pregunta_2_opcion_7[0]->total*100)/$pregunta_2_total[0]->total), 2);
		if (is_nan($porcentaje_2_7)) $porcentaje_2_7 = 0;
		
		
		
		
		//Compa�er@s
		$pregunta_3_total=RespuestasProfundizacion(25, "", $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1); 
		$pregunta_3_opcion_1=RespuestasProfundizacion(25, 1, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_3_1=round((($pregunta_3_opcion_1[0]->total*100)/$pregunta_3_total[0]->total), 2);
		if (is_nan($porcentaje_3_1)) $porcentaje_3_1 = 0;
		
		
		$pregunta_3_opcion_2=RespuestasProfundizacion(25, 2, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_3_2=round((($pregunta_3_opcion_2[0]->total*100)/$pregunta_3_total[0]->total), 2);
		if (is_nan($porcentaje_3_2)) $porcentaje_3_2 = 0;
		
		
		$pregunta_3_opcion_3=RespuestasProfundizacion(25, 3, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_3_3=round((($pregunta_3_opcion_3[0]->total*100)/$pregunta_3_total[0]->total), 2);
		if (is_nan($porcentaje_3_3)) $porcentaje_3_3 = 0;
		
		
		$pregunta_3_opcion_4=RespuestasProfundizacion(25, 4, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_3_4=round((($pregunta_3_opcion_4[0]->total*100)/$pregunta_3_total[0]->total), 2);
		if (is_nan($porcentaje_3_4)) $porcentaje_3_4 = 0;
		
		
		$pregunta_3_opcion_5=RespuestasProfundizacion(25, 5, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_3_5=round((($pregunta_3_opcion_5[0]->total*100)/$pregunta_3_total[0]->total), 2);
		if (is_nan($porcentaje_3_5)) $porcentaje_3_5 = 0;
		
		
		$pregunta_3_opcion_6=RespuestasProfundizacion(25, 6, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_3_6=round((($pregunta_3_opcion_6[0]->total*100)/$pregunta_3_total[0]->total), 2);
		if (is_nan($porcentaje_3_6)) $porcentaje_3_6 = 0;
		
		
		$pregunta_3_opcion_7=RespuestasProfundizacion(25, 7, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_3_7=round((($pregunta_3_opcion_7[0]->total*100)/$pregunta_3_total[0]->total), 2);
		if (is_nan($porcentaje_3_7)) $porcentaje_3_7 = 0;
		
		$pregunta_3_opcion_8=RespuestasProfundizacion(25, 8, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_3_8=round((($pregunta_3_opcion_8[0]->total*100)/$pregunta_3_total[0]->total), 2);
		if (is_nan($porcentaje_3_8)) $porcentaje_3_8 = 0;
		
		
		//ambito personal
		
		
		$pregunta_4_total=RespuestasProfundizacion(27, "", $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1); 
		$pregunta_4_opcion_1=RespuestasProfundizacion(27, 1, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_4_1=round((($pregunta_4_opcion_1[0]->total*100)/$pregunta_4_total[0]->total), 2);
		if (is_nan($porcentaje_4_1)) $porcentaje_4_1 = 0;
		
		
		$pregunta_4_opcion_2=RespuestasProfundizacion(27, 2, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_4_2=round((($pregunta_4_opcion_2[0]->total*100)/$pregunta_4_total[0]->total), 2);
		if (is_nan($porcentaje_4_2)) $porcentaje_4_2 = 0;
		
		
		$pregunta_4_opcion_3=RespuestasProfundizacion(27, 3, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_4_3=round((($pregunta_4_opcion_3[0]->total*100)/$pregunta_4_total[0]->total), 2);
		if (is_nan($porcentaje_4_3)) $porcentaje_4_3 = 0;
		
		
		$pregunta_4_opcion_4=RespuestasProfundizacion(27, 4, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_4_4=round((($pregunta_4_opcion_4[0]->total*100)/$pregunta_4_total[0]->total), 2);
		if (is_nan($porcentaje_4_4)) $porcentaje_4_4 = 0;
		
		
		$pregunta_4_opcion_5=RespuestasProfundizacion(27, 5, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_4_5=round((($pregunta_4_opcion_5[0]->total*100)/$pregunta_4_total[0]->total), 2);
		if (is_nan($porcentaje_4_5)) $porcentaje_4_5 = 0;
		
		
		$pregunta_4_opcion_6=RespuestasProfundizacion(27, 6, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_4_6=round((($pregunta_4_opcion_6[0]->total*100)/$pregunta_4_total[0]->total), 2);
		if (is_nan($porcentaje_4_6)) $porcentaje_4_6 = 0;
		
		
		$pregunta_4_opcion_7=RespuestasProfundizacion(27, 7, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_4_7=round((($pregunta_4_opcion_7[0]->total*100)/$pregunta_4_total[0]->total), 2);
		if (is_nan($porcentaje_4_7)) $porcentaje_4_7 = 0;
		
		$pregunta_4_opcion_8=RespuestasProfundizacion(27, 8, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_4_8=round((($pregunta_4_opcion_8[0]->total*100)/$pregunta_4_total[0]->total), 2);
		if (is_nan($porcentaje_4_8)) $porcentaje_4_8 = 0;
		
		$pregunta_4_opcion_9=RespuestasProfundizacion(27, 9, $cod_division_ajustado, $sexo, $cod_area_ajustado, $cod_depto_ajustado, $cod_zona_ajustado, $cod_secc_ajustado, $unidad_cuipr_ajustado, $cod_sucursal_ajustado, $rut_jefe, $rut_jefe_nivel1);
		$porcentaje_4_9=round((($pregunta_4_opcion_9[0]->total*100)/$pregunta_4_total[0]->total), 2);
		if (is_nan($porcentaje_4_9)) $porcentaje_4_9 = 0;
		
		
	
	
	
	

$persona = [
    "total_usuarios" => $total_usuarios[0]->total,
    "total_usuarios_total" => $total_usuarios_total[0]->total,
    "resultado_final" => $resultado_final,
    "resultado_final_banco" => $resultado_final_banco,
    "respuestas_nivel_5" => $acumulador_respuestas_5,
    "total_respuestas" => $acumulador_total_respuestas,
    "recomendacion1" => $porcentaje1,
   // "recomendacion1_banco" => $porcentaje1_banco,
    "recomendacion1_total" => $recomendacion1[0]->total,
	"recomendacion2" => $porcentaje2,
	//"recomendacion2_banco" => $porcentaje2_banco,
	"recomendacion1_tota2" => $recomendacion2[0]->total,
	
	"profundizacion1" => $porcentaje_1_1,
	"profundizacion1_tota2" => $pregunta_1_opcion_1[0]->total,
	
	"profundizacion2" => $porcentaje_1_2,
	"profundizacion2_tota2" => $pregunta_1_opcion_2[0]->total,
	
	
	"profundizacion3" => $porcentaje_1_3,
	"profundizacion3_tota2" => $pregunta_1_opcion_3[0]->total,
	
	
	"profundizacion4" => $porcentaje_1_4,
	"profundizacion4_tota2" => $pregunta_1_opcion_4[0]->total,
	
	"profundizacion5" => $porcentaje_1_5,
	"profundizacion5_tota2" => $pregunta_1_opcion_5[0]->total,
	
	"profundizacion6" => $porcentaje_1_6,
	"profundizacion6_tota2" => $pregunta_1_opcion_6[0]->total,
	
	"profundizacion7" => $porcentaje_1_7,
	"profundizacion7_tota2" => $pregunta_1_opcion_7[0]->total, 
	
	
	
	"profundizacion1_2" => $porcentaje_2_1,
	"profundizacion1_tota2_2" => $pregunta_2_opcion_1[0]->total,
	
	"profundizacion2_2" => $porcentaje_2_2,
	"profundizacion2_tota2_2" => $pregunta_2_opcion_2[0]->total,
	
	
	"profundizacion3_2" => $porcentaje_2_3,
	"profundizacion3_tota2_2" => $pregunta_2_opcion_3[0]->total,
	
	
	"profundizacion4_2" => $porcentaje_2_4,
	"profundizacion4_tota2_2" => $pregunta_2_opcion_4[0]->total,
	
	"profundizacion5_2" => $porcentaje_2_5,
	"profundizacion5_tota2_2" => $pregunta_2_opcion_5[0]->total,
	
	"profundizacion6_2" => $porcentaje_2_6,
	"profundizacion6_tota2_2" => $pregunta_2_opcion_6[0]->total,
	
	"profundizacion7_2" => $porcentaje_2_7,
	"profundizacion7_tota2_2" => $pregunta_2_opcion_7[0]->total, 
	
	
	
	
	"profundizacion1_3" => $porcentaje_3_1,
	"profundizacion1_tota2_3" => $pregunta_3_opcion_1[0]->total,
	
	"profundizacion2_3" => $porcentaje_3_2,
	"profundizacion2_tota2_3" => $pregunta_3_opcion_2[0]->total,
	
	
	"profundizacion3_3" => $porcentaje_3_3,
	"profundizacion3_tota2_3" => $pregunta_3_opcion_3[0]->total,
	
	
	"profundizacion4_3" => $porcentaje_3_4,
	"profundizacion4_tota2_3" => $pregunta_3_opcion_4[0]->total,
	
	"profundizacion5_3" => $porcentaje_3_5,
	"profundizacion5_tota2_3" => $pregunta_3_opcion_5[0]->total,
	
	"profundizacion6_3" => $porcentaje_3_6,
	"profundizacion6_tota2_3" => $pregunta_3_opcion_6[0]->total,
	
	"profundizacion7_3" => $porcentaje_3_7,
	"profundizacion7_tota2_3" => $pregunta_3_opcion_7[0]->total, 
	
	"profundizacion8_3" => $porcentaje_3_8,
	"profundizacion8_tota2_3" => $pregunta_3_opcion_8[0]->total, 
	
	
	
	
	"profundizacion1_4" => $porcentaje_4_1,
	"profundizacion1_tota2_4" => $pregunta_4_opcion_1[0]->total,
	
	"profundizacion2_4" => $porcentaje_4_2,
	"profundizacion2_tota2_4" => $pregunta_4_opcion_2[0]->total,
	
	
	"profundizacion3_4" => $porcentaje_4_3,
	"profundizacion3_tota2_4" => $pregunta_4_opcion_3[0]->total,
	
	
	"profundizacion4_4" => $porcentaje_4_4,
	"profundizacion4_tota2_4" => $pregunta_4_opcion_4[0]->total,
	
	"profundizacion5_4" => $porcentaje_4_5,
	"profundizacion5_tota2_4" => $pregunta_4_opcion_5[0]->total,
	
	"profundizacion6_4" => $porcentaje_4_6,
	"profundizacion6_tota2_4" => $pregunta_4_opcion_6[0]->total,
	
	"profundizacion7_4" => $porcentaje_4_7,
	"profundizacion7_tota2_4" => $pregunta_4_opcion_7[0]->total, 
	
	"profundizacion8_4" => $porcentaje_4_8,
	"profundizacion8_tota2_4" => $pregunta_4_opcion_8[0]->total, 
	
	"profundizacion9_4" => $porcentaje_4_9,
	"profundizacion9_tota2_4" => $pregunta_4_opcion_9[0]->total, 
	
	
	
	"array_dimensiones" => $arrayDetalle 
	
	
];

//print_r($persona);exit;

// Los codificamos
// recomendado: https://parzibyte.me/blog/2018/12/26/codificar-decodificar-json-php/
$datosCodificados = json_encode($persona);
return($datosCodificados);

//echo $datosCodificados;
//exit;

		




	
	
	
	
	
	
}


