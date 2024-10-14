<?php
function MisIncentivos_date_spanish($date)
{
    setlocale(LC_ALL, 'es_ES');
    $formattedDate = strftime('%A, %d de %B de %Y', strtotime($date));
    return utf8_encode($formattedDate);
}

function MisIncentivos_quedan_faltan($start_date, $end_date)
{
    // Get the current date
    $today = new DateTime();

    // Convert the start and end dates to DateTime objects
    $start = new DateTime($start_date);
    $end = new DateTime($end_date);

    // Check if the start date is ahead of today
    if ($start > $today) {
        // Calculate the difference in days between today and the start date
        $diff = $today->diff($start)->format('%a');
        return "Faltan $diff días para que empiece el incentivo.";
    } // Check if the end date has already passed
    else if ($end < $today) {
        // Calculate the difference in days between today and the end date
        $diff = $end->diff($today)->format('%a');
        return "El incentivo ha terminado hace $diff días.";
    } // Otherwise, today is between the start and end dates
    else {
        // Calculate the difference in days between today and the end date
        $diff = $today->diff($end)->format('%a');
        return "Quedan $diff días para que termine el incentivo.";
    }
}
function MisIncentivos_visualizacion($cuenta,$total){
            //$randomNumber = rand(100000000000, 999999999999);
            //ECHO "<BR>MisIncentivos_visualizacion($cuenta,$total)";
            if($total>0){
                $Progress=round(100*$cuenta/$total);
            } else {
                $Progress=0;
            }
            $row="";
            $row.=file_get_contents("views/mis_incentivos/chart_visualization.html");
            $row = str_replace("{PROGRESS}",$Progress, $row);
            $row = str_replace("{PROGRESS_x}",$cuenta, $row);
    $row = str_replace("{PROGRESS_y}",$total, $row);
    return $row;
}

function MisIncentivos_fn($PRINCIPAL, $rut)
{
    $datos_usuario = MisIncentivos_Datos_rut($_SESSION["user_"]);
    $PRINCIPAL = str_replace("{ID_CARGO}", ($datos_usuario["id_cargo"]), $PRINCIPAL);
    $PRINCIPAL = str_replace("{ID_DIVISION}", ($datos_usuario["id_division"]), $PRINCIPAL);
    $PRINCIPAL = str_replace("{ID_UNIDAD}", ($datos_usuario["id_unidad"]), $PRINCIPAL);
    $PRINCIPAL = str_replace("{RUT}", ($rut), $PRINCIPAL);

    $Mis_Audiencias_Array = MisIncentivos_ListaMisAudencias_rut($rut, $datos_usuario["id_cargo"], $datos_usuario["id_division"], $datos_usuario["id_unidad"]);
    $publicaciones = array();
    //print_r($Mis_Audiencias_Array);
    foreach ($Mis_Audiencias_Array as $a) {
        if($a->id>0){
            $MisPublicaciones = MisIncentivos_Publicaciones_por_idAudiencia($a->id);
            foreach ($MisPublicaciones as $p) {
                if (!in_array($p->id, $publicaciones)) {
                    $publicaciones[] = $p->id;
                }
            }
        }
    }
    global $hoy;
    foreach ($publicaciones as $un) {
        $Publicacion = MisIncentivos_IdPublicacion($un);
        //echo "<br>".$Publicacion[0]->id." ".$Publicacion[0]->fecha_inicio." ".$Publicacion[0]->fecha_termino;
        $row .= file_get_contents("views/mis_incentivos/row_datatables.html");
        $categoria = MisIncentivos_publicaciones_options_traduccion($Publicacion[0]->id_categoria);
        $negocio = MisIncentivos_publicaciones_options_traduccion($Publicacion[0]->id_negocio);
        $periodicidad = MisIncentivos_publicaciones_options_traduccion($Publicacion[0]->id_periodicidad);
        $clasificacion = MisIncentivos_publicaciones_options_traduccion($Publicacion[0]->id_clasificacion);
        if ($Publicacion[0]->fecha_inicio <= $hoy && $Publicacion[0]->fecha_termino >= $hoy) {
            $vigencia = "<span class='badge badge-success'>En curso</span>";
            $vigenciaSearch = "En curso";
        }
        if ($Publicacion[0]->fecha_inicio > $hoy) {
            $vigencia = "<span class='badge badge-warning'>Próximos</span>";
            $vigenciaSearch = "Próximos";
        }
        if ($Publicacion[0]->fecha_termino < $hoy) {
            $vigencia = "<span class='badge badge-info'>Históricos</span>";
            $vigenciaSearch = "Históricos";
        }
        $row = str_replace("{ID}", ($Publicacion[0]->id), $row);
        $row = str_replace("{ID_DOCUMENTO}", ($Publicacion[0]->id_documento), $row);
        $row = str_replace("{NOMBRE}", ($Publicacion[0]->nombre), $row);
        $row = str_replace("{FECHA_INICIO}", ($Publicacion[0]->fecha_inicio), $row);
        $row = str_replace("{FECHA_TERMINO}", ($Publicacion[0]->fecha_termino), $row);
        $row = str_replace("{VIGENCIA}", ($vigencia), $row);
        $row = str_replace("{VIGENCIA_SEARCH}", ($vigenciaSearch), $row);
        $row = str_replace("{CLASIFICACION}", ($clasificacion), $row);
        $row = str_replace("{NEGOCIO}", ($negocio), $row);
        $row = str_replace("{PERIODICIDAD}", ($periodicidad), $row);
        $row = str_replace("{CATEGORIA}", ($categoria), $row);
        //$row = str_replace("{DOCUMENTO}", MisIncentivos_documento($Publicacion[0]->id_documento),$row);
        $row = str_replace("{FECHA_INICIO_CASTELLANO}", MisIncentivos_date_spanish($Publicacion[0]->fecha_inicio), $row);
        $row = str_replace("{FECHA_TERMINO_CASTELLANO}", MisIncentivos_date_spanish($Publicacion[0]->fecha_termino), $row);
        $row = str_replace("{QUEDAN_TANTOS_DIAS_O_FALTAN_DIAS}", MisIncentivos_quedan_faltan($Publicacion[0]->fecha_inicio, $Publicacion[0]->fecha_termino), $row);
        $boton_ver_detalle = "<center><a href='?sw=home_misincentivos&idp=" . Encodear3($unico->id) . "' class='btn btn-info'>Ver Detalle</a></center>";
        $row = str_replace("{BOTON_VER_RESULTADOS}", $boton_ver_resultados, $row);

        $vistaEUP = publicacionVista($rut, $Publicacion[0]->id);

        $color = "bg-gray";
        $test = "NO VISTO";
        $icon = "bi-clipboard";
        if (count($vistaEUP) > 0) {
            $color = "bg-success";
            $test = "VISTO";
            $icon = "bi-check";
        }
        $row = str_replace("{BG-BADGE}",         $color, $row);
        $row = str_replace("{ICON-BADGE}",       $icon, $row);
        $row = str_replace("{TEXT-BADGE}",       $test, $row);

    }
    $PRINCIPAL = str_replace("{LISTA_MIS_INCENTIVOS}", $row, $PRINCIPAL);

    // echo "<br>B Equipo por Publicaciones<br>";
    $Equipo = MisIncentivos_MiEquipo($_SESSION["user_"]);
    //print_r($Equipo);

    $publicacionesE = array();
    $rowEU="";
    foreach ($Equipo as $e) {
        $rowEUP="";
        $cuentaEUP=0;
        $totalEUP=0;
        //echo "<br>- rut col ".$e->rut;
        $rowEU .= file_get_contents("views/mis_incentivos/row_datatables_equipo_usuarios.html");
        $datos_usuarioE = MisIncentivos_Datos_rut($e->rut);
        $Mis_Audiencias_ArrayE = MisIncentivos_ListaMisAudencias_rut($e->rut, $datos_usuarioE["id_cargo"], $datos_usuarioE["id_division"], $datos_usuarioE["id_unidad"]);
        //echo "<br>z-> "; print_r($Mis_Audiencias_ArrayE);

        foreach ($Mis_Audiencias_ArrayE as $a) {
            $MisPublicacionesE = MisIncentivos_Publicaciones_por_idAudiencia($a->id);

            foreach ($MisPublicacionesE as $p) {

                if (!in_array($p->id, $publicacionesE)) {
                    //echo "<br>-> ".$p->id." "
                    $publicacionesE[] = $p->id;
                }
                $Publicacion = MisIncentivos_IdPublicacion($p->id);
                if ($Publicacion[0]->fecha_inicio <= $hoy && $Publicacion[0]->fecha_termino >= $hoy) {
                    $vigencia = "<span class='badge badge-success' style='font-size: 11px;'>En curso</span>";
                    $vigenciaSearch = "En curso";
                    $rowEUP.= file_get_contents("views/mis_incentivos/row_datatables_equipo_usuarios_publicaciones.html");

                    $rowEUP = str_replace("{RUT_COL}",  ($e->rut), $rowEUP);
                    $rowEUP = str_replace("{ID_PUB}",   ($p->id), $rowEUP);
                    $rowEUP = str_replace("{ID}",   ($p->id), $rowEUP);


                    $rowEUP = str_replace("{NOMBRE_PUBLICACION}", ($Publicacion[0]->nombre), $rowEUP);
                    $rowEUP = str_replace("{FECHA_INICIO}",     ($Publicacion[0]->fecha_inicio), $rowEUP);
                    $rowEUP = str_replace("{FECHA_TERMINO}",    ($Publicacion[0]->fecha_termino), $rowEUP);
                    $rowEUP = str_replace("{ACTIVO}",               $vigencia, $rowEUP);
                    $rowEUP = str_replace("{FECHA_INICIO_CASTELLANO}",  utf8_decode(MisIncentivos_date_spanish($Publicacion[0]->fecha_inicio)), $rowEUP);
                    $rowEUP = str_replace("{FECHA_TERMINO_CASTELLANO}", utf8_decode(MisIncentivos_date_spanish($Publicacion[0]->fecha_termino)), $rowEUP);
                    $rowEUP = str_replace("{QUEDAN_TANTOS_DIAS_O_FALTAN_DIAS}", utf8_decode(MisIncentivos_quedan_faltan($Publicacion[0]->fecha_inicio, $Publicacion[0]->fecha_termino)), $rowEUP);

                    $categoria = MisIncentivos_publicaciones_options_traduccion($Publicacion[0]->id_categoria);
                    $negocio = MisIncentivos_publicaciones_options_traduccion($Publicacion[0]->id_negocio);
                    $periodicidad = MisIncentivos_publicaciones_options_traduccion($Publicacion[0]->id_periodicidad);
                    $clasificacion = MisIncentivos_publicaciones_options_traduccion($Publicacion[0]->id_clasificacion);
                    if ($Publicacion[0]->fecha_inicio <= $hoy && $Publicacion[0]->fecha_termino >= $hoy) {
                        $vigencia = "<span class='badge badge-success'>En curso</span>";
                        $vigenciaSearch = "En curso";
                    }
                    if ($Publicacion[0]->fecha_inicio > $hoy) {
                        $vigencia = "<span class='badge badge-warning'>Próximos</span>";
                        $vigenciaSearch = "Próximos";
                    }
                    if ($Publicacion[0]->fecha_termino < $hoy) {
                        $vigencia = "<span class='badge badge-info'>Históricos</span>";
                        $vigenciaSearch = "Históricos";
                    }

                    $rowEUP = str_replace("{VIGENCIA}", ($vigencia), $rowEUP);
                    $rowEUP = str_replace("{VIGENCIA_SEARCH}", ($vigenciaSearch), $rowEUP);
                    $rowEUP = str_replace("{CLASIFICACION}", ($clasificacion), $rowEUP);
                    $rowEUP = str_replace("{NEGOCIO}", ($negocio), $rowEUP);
                    $rowEUP = str_replace("{PERIODICIDAD}", ($periodicidad), $rowEUP);
                    $rowEUP = str_replace("{CATEGORIA}", ($categoria), $rowEUP);



                    $vistaEUP = publicacionVista($e->rut, $p->id);
                    $totalEUP++;
                    $color = "bg-gray";
                    $test = "NO VISTO";
                    $icon = "bi-clipboard";

                    if (count($vistaEUP) > 0) {
                        $color = "bg-success";
                        $test = "VISTO";
                        $icon = "bi-check";
                        $cuentaEUP++;
                    }


                    $rowEUP = str_replace("{BG-BADGE}",         $color, $rowEUP);
                    $rowEUP = str_replace("{ICON-BADGE}",       $icon, $rowEUP);
                    $rowEUP = str_replace("{TEXT-BADGE}",       $test, $rowEUP);
                }
                if ($Publicacion[0]->fecha_inicio > $hoy) {
                    $vigencia = "<span class='badge badge-warning'>Próximos</span>";
                    $vigenciaSearch = "Próximos";

                }
                if ($Publicacion[0]->fecha_termino < $hoy) {
                    $vigencia = "<span class='badge badge-info'>Históricos</span>";
                    $vigenciaSearch = "Históricos";

                }
                $vista = publicacionVista($e->rut, $p->id);
                $usuario[$p->id][] = $e->rut;
                $usuarioView[$p->id][$e->rut]['view'] = false;
                if (count($vista) > 0) {
                    $usuarioView[$p->id][$e->rut]['view'] = true;
                }
            }
        }
        //echo "<BR>".$datos_usuarioE["nombre_completo"]." ".$e->rut." TOTAL ".$totalEUP." CUENTA ".$cuentaEUP;
        $rowEU = str_replace("{ID}",       ($datos_usuarioE["rut"]), $rowEU);
        $rowEU = str_replace("{AVATAR}", MisIncentivos_avatar($datos_usuarioE["rut"]), $rowEU);
        $rowEU = str_replace("{RUT_USUARIO}",       ($datos_usuarioE["rut_completo"]), $rowEU);
        $rowEU = str_replace("{CARGO_USUARIO}",     utf8_encode($datos_usuarioE["cargo"]), $rowEU);
        $rowEU = str_replace("{NOMBRE_USUARIO}",    utf8_encode($datos_usuarioE["nombre_completo"]), $rowEU);
        $progressEU=MisIncentivos_visualizacion($cuentaEUP,$totalEUP);
        $rowEU = str_replace("{VISUALIZACION}",    $progressEU, $rowEU);
        $rowEU = str_replace("{LISTA_PUBLICACIONES_USUARIO}",    $rowEUP, $rowEU);
    }
    //echo "<br>Publicaciones "; print_r($publicacionesE);
    //echo "<br>Usuario<br>"; echo "<pre>"; print_r($usuario); echo "</pre>";
    $cuentaE=0;$totalE=0;
    foreach ($publicacionesE as $un) {
        $Publicacion = MisIncentivos_IdPublicacion($un);
        //echo "<br>Pub ".$Publicacion[0]->id;
        $rowE .= file_get_contents("views/mis_incentivos/row_datatables_equipo.html");
        $categoria = MisIncentivos_publicaciones_options_traduccion($Publicacion[0]->id_categoria);
        $negocio = MisIncentivos_publicaciones_options_traduccion($Publicacion[0]->id_negocio);
        $periodicidad = MisIncentivos_publicaciones_options_traduccion($Publicacion[0]->id_periodicidad);
        $clasificacion = MisIncentivos_publicaciones_options_traduccion($Publicacion[0]->id_clasificacion);
        if ($Publicacion[0]->fecha_inicio <= $hoy && $Publicacion[0]->fecha_termino >= $hoy) {
            $vigencia = "<span class='badge badge-success'>En curso</span>";
            $vigenciaSearch = "En curso";
        }
        if ($Publicacion[0]->fecha_inicio > $hoy) {
            $vigencia = "<span class='badge badge-warning'>Próximos</span>";
            $vigenciaSearch = "Próximos";
        }
        if ($Publicacion[0]->fecha_termino < $hoy) {
            $vigencia = "<span class='badge badge-info'>Históricos</span>";
            $vigenciaSearch = "Históricos";
        }
        $rowE = str_replace("{ID}", ($Publicacion[0]->id), $rowE);
        $rowE = str_replace("{ID_DOCUMENTO}", ($Publicacion[0]->id_documento), $rowE);
        $rowE = str_replace("{NOMBRE}", ($Publicacion[0]->nombre), $rowE);
        $rowE = str_replace("{FECHA_INICIO}", ($Publicacion[0]->fecha_inicio), $rowE);
        $rowE = str_replace("{FECHA_TERMINO}", ($Publicacion[0]->fecha_termino), $rowE);
        $rowE = str_replace("{VIGENCIA_SEARCH}", ($vigenciaSearch), $rowE);
        $rowE = str_replace("{VIGENCIA}", ($vigencia), $rowE);
        $rowE = str_replace("{CLASIFICACION}", ($clasificacion), $rowE);
        $rowE = str_replace("{NEGOCIO}", ($negocio), $rowE);
        $rowE = str_replace("{PERIODICIDAD}", ($periodicidad), $rowE);
        $rowE = str_replace("{CATEGORIA}", ($categoria), $rowE);

        $rowE = str_replace("{FECHA_INICIO_CASTELLANO}", MisIncentivos_date_spanish($Publicacion[0]->fecha_inicio), $rowE);
        $rowE = str_replace("{FECHA_TERMINO_CASTELLANO}", MisIncentivos_date_spanish($Publicacion[0]->fecha_termino), $rowE);
        $rowE = str_replace("{QUEDAN_TANTOS_DIAS_O_FALTAN_DIAS}", MisIncentivos_quedan_faltan($Publicacion[0]->fecha_inicio, $Publicacion[0]->fecha_termino), $rowE);
        $boton_ver_detalle = "<center><a href='?sw=home_misincentivos&idp=" . Encodear3($unico->id) . "' class='btn btn-info'>Ver Detalle</a></center>";
        $rowE = str_replace("{BOTON_VER_RESULTADOS}", $boton_ver_resultados, $rowE);
        $rowC = "";
        $totalE=0;
        $cuentaE=0;

        /*echo "<pre>";
        print_r($usuario[$Publicacion[0]->id]);
        echo "</pre>";*/
        foreach($usuario[$Publicacion[0]->id] as $u1){
            $dato_u = MisIncentivos_DatosRut($u1);
            $usuario[$Publicacion[0]->id]["rut"][]=$u1;
            $usuario[$Publicacion[0]->id]["nombre_completo"][]=$dato_u[0]->nombre_completo;
        }
        /*echo "<pre>";
        print_r($usuario[$Publicacion[0]->id]);
        echo "</pre>";*/
            // Assuming your array is named $usuario
            // Extract the 'nombre_completo' and 'rut' arrays
                    $nombre_completo = $usuario[$Publicacion[0]->id]["nombre_completo"];
                    $rut = $usuario[$Publicacion[0]->id]["rut"];

            // Combine the 'nombre_completo' and 'rut' arrays into a single array for sorting
                    $dataToSort = array_map(null, $nombre_completo, $rut);

            // Sort the data by 'nombre_completo' in ascending order
                    array_multisort($nombre_completo, SORT_ASC, $rut, $dataToSort);

            // Reassign the sorted arrays back to the original array
                    $usuario[$Publicacion[0]->id]["nombre_completo"] = $nombre_completo;
                    $usuario[$Publicacion[0]->id]["rut"] = $rut;

            /*
                echo "<pre>";
                print_r($usuario[$Publicacion[0]->id]);
                echo "</pre>";
                echo "<pre>";
                print_r($usuario[$Publicacion[0]->id]["rut"]);
                echo "</pre>";
                exit();
            */

        foreach ($usuario[$Publicacion[0]->id]["rut"] as $u) {
            $color = "bg-gray";
            $test = "NO VISTO";
            $icon = "bi-clipboard";
            if ($usuarioView[$Publicacion[0]->id][$u]['view']) {
                $color = "bg-success";
                $test = "VISTO";
                $icon = "bi-check";
                $cuentaE++;
            }
            //echo "<br>-> ".$Publicacion[0]->id." ".$u;
            $rowC .= file_get_contents("views/mis_incentivos/row_datatables_equipo_cols.html");
            $dato_u = MisIncentivos_DatosRut($u);
            $rowC = str_replace("{NOMBRE}", $dato_u[0]->nombre_completo, $rowC);
            $rowC = str_replace("{BG-BADGE}", $color, $rowC);
            $rowC = str_replace("{TEXT-BADGE}", $test, $rowC);
            $rowC = str_replace("{ICON-BADGE}", $icon, $rowC);
            $rowC = str_replace("{CARGO}", $dato_u[0]->cargo, $rowC);
            $rowC = str_replace("{AVATAR}", MisIncentivos_avatar($u), $rowC);
            $totalE++;
        }


        $rowE = str_replace("{ROW_COLABORADORES_INCENTIVOS_EQUIPO}", ($rowC), $rowE);
        $progressE=MisIncentivos_visualizacion($cuentaE,$totalE);
        $rowE = str_replace("{VISUALIZACION}",       $progressE, $rowE);

    }



    $PRINCIPAL = str_replace("{LISTA_MIS_INCENTIVOS_equipo}", $rowE, $PRINCIPAL);
    $PRINCIPAL = str_replace("{LISTA_MIS_INCENTIVOS_equipo_USUARIOS}", $rowEU, $PRINCIPAL);

    $Options_categoria = MisIncentivos_groupby_options("categoria");
    foreach ($Options_categoria as $o) {
        $Options_categoria_row .= "<option value='" . $o->nombre_item . "'>" . $o->nombre_item . "</option>";
    }
    $Options_negocio = MisIncentivos_groupby_options("negocio");
    foreach ($Options_negocio as $o) {
        $Options_negocio_row .= "<option value='" . $o->nombre_item . "'>" . $o->nombre_item . "</option>";
    }
    $Options_periodicidad = MisIncentivos_groupby_options("periodicidad");
    foreach ($Options_periodicidad as $o) {
        $Options_periodicidad_row .= "<option value='" . $o->nombre_item . "'>" . $o->nombre_item . "</option>";
    }
    $Options_clasificacion = MisIncentivos_groupby_options("clasificacion");
    foreach ($Options_clasificacion as $o) {
        $Options_clasificacion_row .= "<option value='" . $o->nombre_item . "'>" . $o->nombre_item . "</option>";
    }
    $PRINCIPAL = str_replace("{MIS_INCENTIVOS_OPTIONS_CLASIFICACION}", ($Options_clasificacion_row), $PRINCIPAL);
    $PRINCIPAL = str_replace("{MIS_INCENTIVOS_OPTIONS_PERIODICIDAD}", ($Options_periodicidad_row), $PRINCIPAL);
    $PRINCIPAL = str_replace("{MIS_INCENTIVOS_OPTIONS_CATEGORIA}", ($Options_categoria_row), $PRINCIPAL);

    if(count($Equipo)>0){

    } else {
        $display_team="display:none!important";
    }
    $PRINCIPAL = str_replace("{DISPLAY_TEAM}", ($display_team), $PRINCIPAL);

    return ($PRINCIPAL);

}