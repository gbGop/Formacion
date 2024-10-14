<?php
function ColocaBotoneraPlanificador($PRINCIPAL, $rut){
    $es_controller=VerificoController($rut);
    if($es_controller){

        if($es_controller[0]->codigo_division<1){
            $PRINCIPAL = str_replace("{BOTONERA_CONTROLLER}",file_get_contents("views/planificador_2022/botonera_full.html"), $PRINCIPAL);


        }else{
            $PRINCIPAL = str_replace("{BOTONERA_CONTROLLER}",file_get_contents("views/planificador_2022/botonera_controller.html"), $PRINCIPAL);
        }

    }else{
        exit("#Error 07");
    }
    return($PRINCIPAL);
}
function ColocaPlanificacionProcesosDeSeleccion($html, $cui, $cargo){

    $procesos_de_seleccion=lanificador_ProcesosDeSeleccionCuiCargo($cui, $cargo);
    $i=1;
    foreach($procesos_de_seleccion as $unico){

        $row.=file_get_contents("views/planificador_2022/row_planificacion_proceso_seleccion.html");
        $row = str_replace("{PROCESO}",$unico->proceso, $row);
        $row = str_replace("{NOMBRE_PROCESO}",$unico->nombre_proceso, $row);
        $row = str_replace("{ETAPA}",$unico->etapa, $row);
        $row = str_replace("{NOMBRE_CANDIDATO}",$unico->nombre_candidato, $row);
        $row = str_replace("{RUT_CANDIDATO}",$unico->rut_candidato, $row);
        $row = str_replace("{GESTOR_ACARGO}",$unico->gestor_acargo, $row);
        $row = str_replace("{JEFE_SOLICITANTE}",$unico->jefe_solicitante, $row);
        $row = str_replace("{FECHA_INGRESO}",$unico->fecha_ingreso, $row);
        $row = str_replace("{COD_CARGO}",$unico->cod_cargo, $row);
        $row = str_replace("{GLOSA_CARGO}",$unico->glosa_cargo, $row);
        $row = str_replace("{COD_CUI}",$unico->cod_cui, $row);
        $row = str_replace("{GLOSA_CUI}",$unico->glosa_cui, $row);
        $row = str_replace("{ETAPA_PROCESO_FINAL}",$unico->etapa_proceso_final, $row);
        $row = str_replace("{NUMERO_VACANTES}",$unico->numero_vacantes, $row);
        $row = str_replace("{RUT_ASOCIADO_VACANTE}",$unico->rut_asociado_vacante, $row);
        $row = str_replace("{OBSERVACIONES}",$unico->observaciones, $row);
        $row = str_replace("{I_N}",$i, $row);
        $i++;


    }
    $html = str_replace("{LISTADO_PROCESOS_SELECCION}",utf8_encode($row), $html);

    return($html);

}


function ColocaListadoCuiCargo($html, $arreglo_post){
    $total=TraigoCombinacionesCuiCargo();
    $i=1;

    foreach($total as $tot){
        $row.=file_get_contents("views/planificador_2022/row_planificacion_nuevo_cui_cargo.html");
        $row = str_replace("{N}",$i, $row);
        $row = str_replace("{CUI}",$tot->cui, $row);
        $row = str_replace("{CUI_ENCODEADO}",Encodear3($tot->cui), $row);
        $row = str_replace("{GLOSA_CUI}",$tot->descripcion_cui, $row);
        $row = str_replace("{CARGO}",$tot->cargo, $row);
        $row = str_replace("{CARGO_ENCODEADO}",Encodear3($tot->cargo), $row);
        $detalle_cargo=TRaigoDatosCargoPlan($tot->cargo);
        $row = str_replace("{GLOSA_CARGO}",$detalle_cargo[0]->silla_cargo, $row);
        $row = str_replace("{DIVISION}",$tot->descripcion_division, $row);

    }

    $html = str_replace("{ROW_CUICARGO}",$row, $html);

    return($html);
}
function ColocaListadoControllers($html, $arreglo_post){
    //traigo listado de controllers
    $controllers=ListadoUsuariosControllersTotal();
    $i=1;
    foreach($controllers as $contr){
        $row_controllers.=file_get_contents("views/planificador_2022/row_planificador_controller.html");
        $row_controllers = str_replace("{N}",$i, $row_controllers);
        $row_controllers = str_replace("{RUT}",$contr->rut, $row_controllers);
        $row_controllers = str_replace("{ID_ENCODEADO}",Encodear3($contr->id), $row_controllers);
        $row_controllers = str_replace("{NOMBRE}",$contr->nombre_completo, $row_controllers);
        $row_controllers = str_replace("{CARGO}",$contr->silla_cargo, $row_controllers);
        if($contr->codigo_division==="0"){
            $row_controllers = str_replace("{DIVISION}","<b>Todas las Divisiones</b>", $row_controllers);
        }else{
            $row_controllers = str_replace("{DIVISION}",$contr->silla_division, $row_controllers);
        }


        $row_controllers = str_replace("{DIVISION_ASIGNADA}",$contr->codigo_division, $row_controllers);
        $row_controllers = str_replace("{EDITABLE}",$contr->editable, $row_controllers);
        $row_controllers = str_replace("{SELECTED_".$contr->editable."}","selected", $row_controllers);
        $row_controllers = str_replace("{SELECTED_No}","", $row_controllers);
        $row_controllers = str_replace("{SELECTED_Si}","", $row_controllers);

    }
    $html = str_replace("{ROW_CONTROLLERS}",$row_controllers, $html);


    return($html);
}
function ColocaListadoUsuarios($html, $arreglo_post){
    //triago usuarios
    $usuarios=ListadoUsuariosControllers();
    $row_usuarios="";
    foreach($usuarios as $usu){
        $row_usuarios.="<option value='".$usu->rut."'>".$usu->nombre_completo."</option>";

    }
    $html = str_replace("{OPTIONS_USUARIOS}",$row_usuarios, $html);


    foreach($usuarios as $usu){
        $row_usuarios2.="<option value='".$usu->rut."'>".$usu->nombre_completo." - ".$usu->cargo."</option>";

    }
    $html = str_replace("{OPTIONS_USUARIOS_CON_CARGO}",$row_usuarios2, $html);
    return($html);

}
function ColocaFiltrosPlanificador($html, $arreglo_post){

    //$divisiones=PlanificadorDistinctCampo("silla_id_division", "silla_division");


    $divisiones=PlanificadorDistinctCampo("division", "descripcion_division");
    foreach($divisiones as $division) {
        if($division->valor==$arreglo_post["division"]){
            $row_divisiones.="<option selected value='".$division->valor."'>".$division->valor_a_mostrar."</option>";
        }else{
            $row_divisiones.="<option value='".$division->valor."'>".$division->valor_a_mostrar."</option>";
        }

    }
    $html = str_replace("{OPTIONS_DIVISIONES}",$row_divisiones, $html);


    $areas=PlanificadorDistinctCampo("area", "descripcion_area");
    foreach($areas as $area) {
        if($area->valor==$arreglo_post["area"]){
            $row_area.="<option selected value='".$area->valor."'>".$area->valor_a_mostrar."</option>";
        }else{
            $row_area.="<option value='".$area->valor."'>".$area->valor_a_mostrar."</option>";
        }

    }
    $html = str_replace("{OPTIONS_AREAS}",$row_area, $html);




    $zonas=PlanificadorDistinctCampo("zona", "descripcion_zona");
    foreach($zonas as $zona) {
        if($zona->valor==$arreglo_post["zona"]){
            $row_zona.="<option selected value='".$zona->valor."'>".$zona->valor_a_mostrar."</option>";
        }else{
            $row_zona.="<option  value='".$zona->valor."'>".$zona->valor_a_mostrar."</option>";
        }

    }
    $html = str_replace("{OPTIONS_ZONAS}",$row_zona, $html);


    $secciones=PlanificadorDistinctCampo("seccion", "descripcion_seccion");
    foreach($secciones as $seccione) {
        if($seccione->valor==$arreglo_post["seccion"]){
            $row_seccion.="<option selected value='".$seccione->valor."'>".$seccione->valor_a_mostrar."</option>";
        }else{
            $row_seccion.="<option value='".$seccione->valor."'>".$seccione->valor_a_mostrar."</option>";
        }

    }
    $html = str_replace("{OPTIONS_SECCIONES}",$row_seccion, $html);

    $departamentos=PlanificadorDistinctCampo("departamento", "descripcion_departamento");
    foreach($departamentos as $departamento) {
        if($departamento->valor==$arreglo_post["departamento"]){
            $row_departamentos.="<option selected value='".$departamento->valor."'>".$departamento->valor_a_mostrar."</option>";
        }else{
            $row_departamentos.="<option value='".$departamento->valor."'>".$departamento->valor_a_mostrar."</option>";
        }

    }
    $html = str_replace("{OPTIONS_DEPARTAMENTOS}",$row_departamentos, $html);

    //seccion

    $oficinas=PlanificadorDistinctCampo("oficina", "descripcion_oficina");
    foreach($oficinas as $oficina) {
        if($oficina->valor==$arreglo_post["oficina"]){
            $row_oficina.="<option selected value='".$oficina->valor."'>".$oficina->valor_a_mostrar."</option>";
        }else{
            $row_oficina.="<option value='".$oficina->valor."'>".$oficina->valor_a_mostrar."</option>";
        }

    }
    $html = str_replace("{OPTIONS_OFICINAS}",$row_oficina, $html);

    //unidad
    $unidades=PlanificadorDistinctCampo("cui", "descripcion_cui");
    foreach($unidades as $unidad) {
        if($unidad->valor==$arreglo_post["unidad"]){
            $row_unidad.="<option selected value='".$unidad->valor."'>".$unidad->valor_a_mostrar."</option>";
        }else{
            $row_unidad.="<option value='".$unidad->valor."'>".$unidad->valor_a_mostrar."</option>";
        }

    }
    $html = str_replace("{OPTIONS_UNIDAD}",$row_unidad, $html);



    //unidad22
    $unidades=PlanificadorDistinctCampo("cui", "descripcion_cui");
    foreach($unidades as $unidad) {
        if($unidad->valor==$arreglo_post["unidad"]){
            $row_unidad.="<option selected value='".$unidad->valor."'>".$unidad->valor_a_mostrar."</option>";
        }else{
            $row_unidad.="<option value='".$unidad->valor."'>".$unidad->valor_a_mostrar."</option>";
        }

    }
    $html = str_replace("{OPTIONS_UNIDAD_2}",$row_unidad, $html);





    //cargos
    $cargos=PlanificadorDistinctCampo("cargo", "descripcion_cargo");
    foreach($cargos as $cargo) {
        if($unidad->valor==$arreglo_post["cargo"]){
            $row_cargo.="<option selected value='".$cargo->valor."'>".$cargo->valor_a_mostrar."</option>";
        }else{
            $row_cargo.="<option  value='".$cargo->valor."'>".$cargo->valor_a_mostrar."</option>";
        }



    }


    if($arreglo_post["cui"]){
        $html = str_replace("{CUI_INGRESADO}",$arreglo_post["cui"], $html);
    }else{
        $html = str_replace("{CUI_INGRESADO}",$arreglo_post["cui"]  , $html);
    }
    $html = str_replace("{OPTION_LISTADO_CARGOS}",$row_cargo, $html);



    return($html);

}


function ColocaCombinacionesCuiCargoSumatoria($html, $id, $division, $arreglo_post){

    //traigo todos los datos de la tabla planificacion
    $data=PlanificadorUltimoProcesoData($id, $division, $arreglo_post);
    if($_POST["ex"]=="1"){
        $row_excel=file_get_contents("views/planificador_2022/row_planificador_accion_lectura_excel.html");
    }else{
        $row_excel=file_get_contents("views/planificador_2022/row_planificador_acion_lectura.html");

    }
    foreach($data as $dat){
        /*if($_POST["ex"]=="1"){
            $row.=file_get_contents("views/planificador_2022/row_planificador_accion_lectura_excel.html");
        }else{
            $row.=file_get_contents("views/planificador_2022/row_planificador_acion_lectura.html");

        }*/
        $row.=$row_excel;

        $row = str_replace("{CODIGO_CUI}",$dat->cui, $row);
        $row = str_replace("{CODIGO_CARGO}",$dat->cargo, $row);
        $row = str_replace("{STOCK}",$dat->plan, $row);
        $row = str_replace("{DESCRIPCION_CARGO}",$dat->descripcion_cargo, $row);
        $row = str_replace("{DESCRIPCION_CUI}",$dat->descripcion_cui, $row);
        $row = str_replace("{NIVEL_CARGO}",$dat->nivel, $row);
        $row = str_replace("{REAL}",$dat->real, $row);
        $row = str_replace("{PLAZO_FIJO}",$dat->plazo_fijo, $row);
        $row = str_replace("{PLAZO_INDEFINIDO}",$dat->plazo_indefinido, $row);
        $acumulador=0;
        //$acumulador=$acumulador+$dat->plan;
        //$acumulador=$acumulador+$dat->real;
        $total_acumulador_mas_real=0;
        $total_acumulador_mas_real=$total_acumulador_mas_real+$dat->real;
        for($i=11;$i<=12;$i++){

            $verifica_otros_procesos=PlanificacionVerificaDiarioPorProcesoPorMesAnoCargoCui("2022", $i, $dat->cui, $dat->cargo);

            $verifica=PlanificacionVerificaDiario("2022", $i, $dat->cui, $dat->cargo);
            //$row = str_replace("{2022_".$i."}",$verifica[0]->cantidad, $row);
            $acumulador=$acumulador+$verifica[0]->cantidad;
            //$row = str_replace("{2022_".$i."}",$acumulador, $row);



            ////


            if(!$verifica[0]->estado  and ($verifica[0]->cantidad>0 or $verifica[0]->cantidad<0 or $verifica[0]->cantidad==="0")){
                //$row = str_replace("{PROCESO_VALIDAR_2022_".$i."}","", $row);
                if($_POST["ex"]=="11" ){


                }else{
                    // Comentado por que solo se debiese acumular los Aprobados o Verdes
                    //$total_acumulador_mas_real=$total_acumulador_mas_real+$verifica[0]->cantidad;
                }



            }else if($verifica[0]->estado=="Aprobado"){
                $total_acumulador_mas_real=$total_acumulador_mas_real+$verifica[0]->cantidad;

            } else if($verifica[0]->estado=="Rechazado"){

            }else{
                if($_POST["ex"]=="11" ){


                }else{
                    // Comentado por que solo se debiese acumular los Aprobados o Verdes
                    //$total_acumulador_mas_real=$total_acumulador_mas_real+$verifica[0]->cantidad;
                }


            }


            $row = str_replace("{2022_".$i."}",$total_acumulador_mas_real, $row);

            ///




            if($verifica_otros_procesos[0]->total>0){
                //$total_acumulador_mas_real= $total_acumulador_mas_real + 1;
                $total_acumulador_mas_real= $total_acumulador_mas_real + $verifica_otros_procesos[0]->total;
                /*$row = str_replace("{PROCESOS_PARALELOS_2022_".$i."}","<hr>
        <img src='views/planificador_2022/icono_verde.png' width='20px;'>
        1", $row);*/
                $row = str_replace("{PROCESOS_PARALELOS_2022_".$i."}","", $row);
            }else{
                $row = str_replace("{PROCESOS_PARALELOS_2022_".$i."}","", $row);
            }




        }
        //$row = str_replace("{ACUMULADO_2022}",$acumulador, $row);
        $row = str_replace("{ACUMULADO_2022}",$total_acumulador_mas_real, $row);












        //$acumulador=0;
        for($i=1;$i<=12;$i++){
            $verifica=PlanificacionVerificaDiario("2023", $i, $dat->cui, $dat->cargo);
            $verifica_otros_procesos=PlanificacionVerificaDiarioPorProcesoPorMesAnoCargoCui("2023", $i, $dat->cui, $dat->cargo);
            //$row = str_replace("{2023_".$i."}",$verifica[0]->cantidad, $row);
            //$acumulador=$acumulador+$verifica[0]->cantidad;
            //$total_acumulador_mas_real=$total_acumulador_mas_real+$verifica[0]->cantidad;

            if(!$verifica[0]->estado  and ($verifica[0]->cantidad>0 or $verifica[0]->cantidad<0 or $verifica[0]->cantidad==="0")){
                //$total_acumulador_mas_real=$total_acumulador_mas_real+$verifica[0]->cantidad;
                if($_POST["ex"]=="11" ){


                }else{
                    // Comentado por que solo se debiese acumular los Aprobados o Verdes
                    //$total_acumulador_mas_real=$total_acumulador_mas_real+$verifica[0]->cantidad;
                }

                $pendiente="si";
                $enproceso="si";
                $row = str_replace("{PROCESO_VALIDAR_2023_".$i."}",file_get_contents("views/planificador_2022/boton_para_validar.html"), $row);
                $row = str_replace("{MES}",$i, $row);
                $row = str_replace("{ANO}","2023", $row);

            }

            else if($verifica[0]->estado=="Aprobado"){
                $total_acumulador_mas_real=$total_acumulador_mas_real+$verifica[0]->cantidad;
                $row = str_replace("{PROCESO_VALIDAR_2023_".$i."}","<img src='views/planificador_2022/verde.png' width='20px;'>", $row);
                $aprobado="si";
            }

            else if($verifica[0]->estado=="Rechazado"){
                $row = str_replace("{PROCESO_VALIDAR_2023_".$i."}","<img src='views/planificador_2022/rojo.png' width='20px;'>", $row);
                $rechazado="si";
            }
            else{
                if($_POST["ex"]=="111" ){


                }else{
                    // Comentado por que solo se debiese acumular los Aprobados o Verdes
                    //$total_acumulador_mas_real=$total_acumulador_mas_real+$verifica[0]->cantidad;
                }

                $row = str_replace("{PROCESO_VALIDAR_2023_".$i."}","", $row);
            }

            if($verifica_otros_procesos[0]->total>0){
                //$total_acumulador_mas_real = $total_acumulador_mas_real+1;
                $total_acumulador_mas_real = $total_acumulador_mas_real+$verifica_otros_procesos[0]->total;
                $row = str_replace("{PROCESOS_PARALELOS_2023_".$i."}","", $row);
            }else{
                $row = str_replace("{PROCESOS_PARALELOS_2023_".$i."}","", $row);
            }


            //$row = str_replace("{2023_".$i."}",$acumulador, $row);
            $row = str_replace("{2023_".$i."}",$total_acumulador_mas_real, $row);




            ///





        }
    }
    $html = str_replace("{ROW_PLANIFICACIONES}",$row, $html);

    return($html);

}

function ColocaCombinacionesCuiCargo($html, $id, $division, $arreglo_post, $cui_filtro, $cargo_filtro){



    $hora_actual=date("H:i:s");

    //traigo todos los datos de la tabla planificacion

    $data=PlanificadorUltimoProcesoData($id, $division, $arreglo_post, $cui_filtro, $cargo_filtro);
    //echo $hora_actual;
    //exit();
    if($data){
        //veo si hay solicitudes ingresadas.
        $ingresos_diarios=TraigoDatosDiarios($_SESSION["user_"]);
        foreach($ingresos_diarios as $ingreso){
            if(!$ingreso->total_comentarios){

                $html = str_replace("{DISPLAY_MENSAJJE_SWEET}",'<script>
	swal({
		title : "¡Recordatorio!",
		text : "Te recordamos que debes ingresar comentarios para complementar...."
	});
</script>', $html);

                continue;
            }
        }
        $html = str_replace("{DISPLAY_MENSAJJE_SWEET}",'', $html);
    }else{
        $html = str_replace("{DISPLAY_MENSAJJE_SWEET}",'', $html);
    }
    //VEo si esl usuario logueado es controler
    $es_controller=VerificoController($_SESSION["user_"]);
    if($es_controller[0]->editable=="No"){
        $row_html=file_get_contents("views/planificador_2022/row_planificador_acion_lectura_gestion.html");
    }else if($_POST["ex"]=="1"){
        $row_html=file_get_contents("views/planificador_2022/row_planificador_acion_lectura_gestion_Excel.html");
    }else

    {
        $row_html=file_get_contents("views/planificador_2022/row_planificador_acion.html");
    }


    foreach($data as $dat){
        $enproceso="";
        $rechazado="";
        $aprobado="";
        //$row.=$row_html;
        $row=$row_html;

        $contador_procesos_seleccion_asignados=0;
        $contador_procesos_asignados=0;


        $total_acumulador=0;
        $total_acumulador_mas_real=0;
        $total_acumulador_mas_real=$total_acumulador_mas_real+$dat->real;
        $pendiente="no";
        $comentario_por_cui_cargo="";
        for($i=11;$i<=12;$i++){
            $verifica=PlanificacionVerificaDiario("2022", $i, $dat->cui, $dat->cargo);
            $verifica_otros_procesos=PlanificacionVerificaDiarioPorProcesoPorMesAnoCargoCui("2022", $i, $dat->cui, $dat->cargo);
            if($verifica[0]->comentario) $comentario_por_cui_cargo=$verifica[0]->comentario;
            if($_POST["ex"]=="1" ){
                if($verifica[0]->estado=="Rechazado" ){
                    $row = str_replace("{2022_".$i."}","0", $row);
                }

                if($verifica[0]->id &&  !$verifica[0]->estado){
                    $row = str_replace("{2022_".$i."}","0", $row);
                }

            }
            $row = str_replace("{2022_".$i."}",$verifica[0]->cantidad, $row);

            $total_acumulador=$total_acumulador+$verifica[0]->cantidad;

            if(!$verifica[0]->estado  and ($verifica[0]->cantidad>0 or $verifica[0]->cantidad<0 or $verifica[0]->cantidad==="0")){
                //$row = str_replace("{PROCESO_VALIDAR_2022_".$i."}","", $row);
                if($_POST["ex"]=="1" ){


                }else{
                    $total_acumulador_mas_real=$total_acumulador_mas_real+$verifica[0]->cantidad;
                }

                $pendiente="si";
                $enproceso="si";
                $row = str_replace("{PROCESO_VALIDAR_2022_".$i."}",file_get_contents("views/planificador_2022/boton_para_validar.html"), $row);
                $row = str_replace("{MES}",$i, $row);
                $row = str_replace("{ANO}","2022", $row);

            }else if($verifica[0]->estado=="Aprobado"){
                $total_acumulador_mas_real=$total_acumulador_mas_real+$verifica[0]->cantidad;
                $row = str_replace("{PROCESO_VALIDAR_2022_".$i."}","<img src='views/planificador_2022/verde.png'  width='20px;'>", $row);
                $aprobado="si";
            } else if($verifica[0]->estado=="Rechazado"){
                $row = str_replace("{PROCESO_VALIDAR_2022_".$i."}","<img src='views/planificador_2022/rojo.png' width='20px;'>", $row);
                $row = str_replace("{SALTOS_DE_LINEA}","", $row);

                $rechazado="si";
            }else{
                if($_POST["ex"]=="1" ){


                }else{
                    $total_acumulador_mas_real=$total_acumulador_mas_real+$verifica[0]->cantidad;
                }

                $row = str_replace("{PROCESO_VALIDAR_2022_".$i."}","", $row);
            }

            // Comentado el 16 de Abril $row = str_replace("{SALTOS_DE_LINEA}","<br><br>", $row);
            $row = str_replace("{SALTOS_DE_LINEA}","", $row);


            if($verifica_otros_procesos[0]->total>0){
                $total_acumulador_mas_real = $total_acumulador_mas_real + $verifica_otros_procesos[0]->total;
                $contador_procesos_seleccion_asignados=$contador_procesos_seleccion_asignados+$verifica_otros_procesos[0]->total;
                $row = str_replace("{PROCESOS_PARALELOS_2022_".$i."}","
        <img src='views/planificador_2022/icono_verde_check.jpg' width='20px;'>
        ".$verifica_otros_procesos[0]->total, $row);
            }else{
                $row = str_replace("{PROCESOS_PARALELOS_2022_".$i."}","", $row);
            }

        }

            $row = str_replace("{ACUMULADO_2022}",$total_acumulador_mas_real, $row);








        for($i=1;$i<=12;$i++){
            $verifica=PlanificacionVerificaDiario("2023", $i, $dat->cui, $dat->cargo);
            $verifica_otros_procesos=PlanificacionVerificaDiarioPorProcesoPorMesAnoCargoCui("2023", $i, $dat->cui, $dat->cargo);
            if($_POST["ex"]=="1" ){
                if($verifica[0]->estado=="Rechazado" ){
                    $row = str_replace("{2023_".$i."}","0", $row);
                }

                if($verifica[0]->id &&  !$verifica[0]->estado){
                    $row = str_replace("{2023_".$i."}","0", $row);
                }

            }


            if($verifica[0]->comentario) $comentario_por_cui_cargo=$verifica[0]->comentario;
            $row = str_replace("{2023_".$i."}",$verifica[0]->cantidad, $row);
            $total_acumulador=$total_acumulador+$verifica[0]->cantidad;

            if(!$verifica[0]->estado  and ($verifica[0]->cantidad>0 or $verifica[0]->cantidad<0 or $verifica[0]->cantidad==="0")){
                //$total_acumulador_mas_real=$total_acumulador_mas_real+$verifica[0]->cantidad;
                if($_POST["ex"]=="1" ){


                }else{
                    $total_acumulador_mas_real=$total_acumulador_mas_real+$verifica[0]->cantidad;
                }

                $pendiente="si";
                $enproceso="si";
                $row = str_replace("{PROCESO_VALIDAR_2023_".$i."}",file_get_contents("views/planificador_2022/boton_para_validar.html"), $row);
                $row = str_replace("{MES}",$i, $row);
                $row = str_replace("{ANO}","2023", $row);

            }

            else if($verifica[0]->estado=="Aprobado"){
                $total_acumulador_mas_real=$total_acumulador_mas_real+$verifica[0]->cantidad;
                $row = str_replace("{PROCESO_VALIDAR_2023_".$i."}","<img src='views/planificador_2022/verde.png' width='20px;'>", $row);
                $aprobado="si";
            }

            else if($verifica[0]->estado=="Rechazado"){
                $row = str_replace("{PROCESO_VALIDAR_2023_".$i."}","<img src='views/planificador_2022/rojo.png' width='20px;'>", $row);
                $rechazado="si";
            }
            else{
                if($_POST["ex"]=="1" ){


                }else{
                    $total_acumulador_mas_real=$total_acumulador_mas_real+$verifica[0]->cantidad;
                }

                $row = str_replace("{PROCESO_VALIDAR_2023_".$i."}","", $row);
            }

            // Comentado el 16 de Abril$row = str_replace("{SALTOS_DE_LINEA}","<br><br>", $row);
            $row = str_replace("{SALTOS_DE_LINEA}","", $row);


            if($verifica_otros_procesos[0]->total>0){
                $total_acumulador_mas_real=$total_acumulador_mas_real+$verifica_otros_procesos[0]->total;
                $contador_procesos_seleccion_asignados=$contador_procesos_seleccion_asignados+$verifica_otros_procesos[0]->total;
                $row = str_replace("{PROCESOS_PARALELOS_2023_".$i."}","
        <img src='views/planificador_2022/icono_verde_check.jpg' width='20px;'>
        ".$verifica_otros_procesos[0]->total, $row);
            }else{
                $row = str_replace("{PROCESOS_PARALELOS_2023_".$i."}","", $row);
            }


        }


        //por cada combinacion, chequeo si tiene procesos de seleccion
        @$procesos_de_seleccion=lanificador_ProcesosDeSeleccionCuiCargo($dat->cui, $dat->cargo);
        $html_procesos="";
        if($procesos_de_seleccion){
            foreach($procesos_de_seleccion as $unico){

                $html_procesos.=file_get_contents("views/planificador_2022/row_planificacion_proceso_seleccion.html");
                $html_procesos = str_replace("{PROCESO}",$unico->proceso, $html_procesos);
                $html_procesos = str_replace("{NOMBRE_PROCESO}",$unico->nombre_proceso, $html_procesos);
                $html_procesos = str_replace("{ETAPA}",$unico->etapa, $html_procesos);
                $html_procesos = str_replace("{NOMBRE_CANDIDATO}",$unico->nombre_candidato, $html_procesos);
                $html_procesos = str_replace("{RUT_CANDIDATO}",$unico->rut_candidato, $html_procesos);
                $html_procesos = str_replace("{GESTOR_ACARGO}",$unico->gestor_acargo, $html_procesos);
                $html_procesos = str_replace("{JEFE_SOLICITANTE}",$unico->jefe_solicitante, $html_procesos);
                $html_procesos = str_replace("{FECHA_INGRESO}",$unico->fecha_ingreso, $html_procesos);
                $html_procesos = str_replace("{COD_CARGO}",$unico->cod_cargo, $html_procesos);
                $html_procesos = str_replace("{GLOSA_CARGO}",$unico->glosa_cargo, $html_procesos);
                $html_procesos = str_replace("{COD_CUI}",$unico->cod_cui, $html_procesos);
                $html_procesos = str_replace("{GLOSA_CUI}",$unico->glosa_cui, $html_procesos);
                $html_procesos = str_replace("{ETAPA_PROCESO_FINAL}",$unico->etapa_proceso_final, $html_procesos);
                $html_procesos = str_replace("{NUMERO_VACANTES}",$unico->numero_vacantes, $html_procesos);
                $html_procesos = str_replace("{RUT_ASOCIADO_VACANTE}",$unico->rut_asociado_vacante, $html_procesos);
                $html_procesos = str_replace("{OBSERVACIONES}",$unico->observaciones, $html_procesos);
                $html_procesos = str_replace("{I_N}",$i, $html_procesos);

                $esta_asignado=VerificoProcesoEnTablaDiariaPorIdProceso($unico->proceso);

                //if($unico->mes && $unico->ano){
                if($esta_asignado){
                    $array_get = serialize($arreglo_post);
                    $array_get = base64_encode($array_get);
                    $array_get = urlencode($array_get);



                    $html_procesos = str_replace("{BLOQUE_ALERT}",'<div class="alert alert-success" role="alert">
  Ya se encuentra asignado al mes '.devuelveNombreMes($esta_asignado[0]->mes).', a&ntilde;o '.($esta_asignado[0]->ano+1).' 
</div>

<div class="alert alert-danger">
  <strong>Para eliminarlos, haga clic <b><a href="?sw=dlrProcSel&ip='.Encodear3($unico->proceso).'&var='.$array_get.'"><u>ACA</u></a></b></strong>
</div>



<br>', $html_procesos);
                }else{
                    $html_procesos = str_replace("{BLOQUE_ALERT}","", $html_procesos);
                }
                $i++;


            }
            $row = str_replace("{VER_PROCESO_SELECCION}",'<span data-toggle="modal" style="cursor:pointer;" data-target="#myModal_'.$dat->cui.'_'.$dat->cargo.'">Ver ('.count($procesos_de_seleccion).')</span>', $row);
            //$row = str_replace("{VER_PROCESO_SELECCION_MODAL}",'<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal_'.$dat->cui.'_'.$dat->cargo.'">Open Modal</button>', $row);
            $row = str_replace("{VER_PROCESO_SELECCION_MODAL}","", $row);
            $row = str_replace("{LISTADO_PROCESOS_SELECCION}",utf8_encode($html_procesos), $row);

        }else{
            $row = str_replace("{VER_PROCESO_SELECCION}","", $row);
            $row = str_replace("{VER_PROCESO_SELECCION_MODAL}","", $row);
            $row = str_replace("{LISTADO_PROCESOS_SELECCION}","", $row);
        }





        $row = str_replace("{ACUMULADO_FINAL}",$total_acumulador_mas_real, $row);

        if($total_acumulador_mas_real===0){
            $row = str_replace("{BOTON_VALIDAR}","", $row);
            $row = str_replace("{ICONO_ESTADO}","", $row);
        }else if($total_acumulador_mas_real<=-1){
            $row = str_replace("{BOTON_VALIDAR}","", $row);
            $row = str_replace("{ICONO_ESTADO}",'<span class=" alert-danger">Stock Final  debe ser mayor o igual a 0 </span>', $row);




        }else{
            if($pendiente=="si"){
                $row = str_replace("{BOTON_VALIDAR}",file_get_contents("views/planificador_2022/boton_validar.html"), $row);
                //$row = str_replace("{ICONO_ESTADO}","<img src='views/planificador_2022/amarillo.png' width='20px;'>", $row);
                $row = str_replace("{ICONO_ESTADO}",'<span class="fa fa-circle text-warning fs14 mr10"></span><br><span class="etiquetaPrograma etiquetaPrograma-proceso">En Proceso</span>', $row);
            }else{
                $row = str_replace("{BOTON_VALIDAR}","<b>".$comentario_por_cui_cargo."</b>", $row);
                $row = str_replace("{ICONO_ESTADO}","", $row);
            }

        }




        $row = str_replace("{CODIGO_CUI}",$dat->cui, $row);
        $row = str_replace("{CODIGO_CARGO}",$dat->cargo, $row);
        $row = str_replace("{STOCK}",$dat->plan, $row);
        $row = str_replace("{DESCRIPCION_CARGO}",$dat->descripcion_cargo, $row);
        $row = str_replace("{DESCRIPCION_CUI}",$dat->descripcion_cui, $row);

        $row = str_replace("{CODIGO_DIVISION}",$dat->division, $row);
        $row = str_replace("{DESCRIPCION_DIVISION}",$dat->descripcion_division, $row);

        $row = str_replace("{CODIGO_AREA}",$dat->area, $row);
        $row = str_replace("{DESCRIPCION_AREA}",$dat->descripcion_area, $row);

        $row = str_replace("{CODIGO_DEPARTAMENTO}",$dat->departamento, $row);
        $row = str_replace("{DESCRIPCION_DEPARTAMENTO}",$dat->descripcion_departamento, $row);

        $row = str_replace("{CODIGO_ZONA}",$dat->zona, $row);
        $row = str_replace("{DESCRIPCION_ZONA}",$dat->descripcion_zona, $row);

        $row = str_replace("{CODIGO_SECCION}",$dat->zona, $row);
        $row = str_replace("{DESCRIPCION_SECCION}",$dat->descripcion_seccion, $row);

        $row = str_replace("{CODIGO_OFICINA}",$dat->oficina, $row);
        $row = str_replace("{DESCRIPCION_OFICINA}",$dat->descripcion_oficina, $row);

        if(count($procesos_de_seleccion)>0){
            if(count($procesos_de_seleccion)==$contador_procesos_seleccion_asignados){
                $row = str_replace("{PROCESO_SELECCION_ALERTA}","", $row);
            }else{
                $diferencia=(count($procesos_de_seleccion))-$contador_procesos_seleccion_asignados;
                $row = str_replace("{PROCESO_SELECCION_ALERTA}",'<div class="alert-2023 alert-warning" role="alert">
'.$diferencia.' procesos <br>de seleccion <br>no asignados.
</div>', $row);
            }
        }else{
            $row = str_replace("{PROCESO_SELECCION_ALERTA}","", $row);

        }






        $row = str_replace("{NIVEL_CARGO}",$dat->nivel, $row);
        $row = str_replace("{AUTOR}",$dat->autor, $row);
        $row = str_replace("{PLAN}",$dat->plan, $row);
        $row = str_replace("{PLAZO_FIJO}",$dat->plazo_fijo, $row);
        $row = str_replace("{PLAZO_INDEFINIDO}",$dat->plazo_indefinido, $row);
        $row = str_replace("{REAL}",$dat->real, $row);
        $row = str_replace("{COMENTARIO_INGRESADO_CONTROLLER}",$dat->comentario, $row);
        $row = str_replace("{COMENTARIO_INGRESADO_ADMIN}",$dat->comentario_admin, $row);

        if($arreglo_post["estado"]=="enproceso"){
            if($enproceso=="si"){
                $total_row.=$row;
            }
        }else if($arreglo_post["estado"]=="Aprobado"){
            if($aprobado=="si"){
                $total_row.=$row;
            }
        }else if($arreglo_post["estado"]=="Rechazado"){
            if($rechazado=="si"){
                $total_row.=$row;
            }
        }else{
            $total_row.=$row;
        }
        //$total_row.=$row;

    }

    //$html = str_replace("{ROW_PLANIFICACIONES}",$row, $html);
    $html = str_replace("{ROW_PLANIFICACIONES}",$total_row, $html);
    $html = str_replace("{ARREGLO_POST_GET_ENCODEADO}",$array_get, $html);


    if($cui_filtro && $cargo_filtro){
        $total_row_safe = htmlspecialchars($total_row, ENT_QUOTES, 'UTF-8');
        echo $total_row_safe;
        exit;
    }



    return($html);

}

?>