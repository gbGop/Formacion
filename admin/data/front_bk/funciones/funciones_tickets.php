<?php

function Tickets_SubirArchivosTickets_2020($FILES, $extension_doc, $prefijo, $ruta, $numero, $nombre_file)
{
echo "Tickets_SubirArchivosTickets_2020";
print_r($FILES);

    //ini_set('display_errors', 1);ini_set('display_startup_errors', 1);error_reporting(E_ALL);
    //$ruta_con_archivo=$ruta."/".$prefijo."_BK.".$extension_archivo;
    $ruta_con_archivo = $ruta . "/" . $prefijo . "." . $extension_doc;
    copy($FILES["archivo"]['tmp_name'], $ruta_con_archivo);
    $nombre_imagen_video = $prefijo . "." . $extension_doc;
    //RedimensionarImagenSlider($ruta_con_archivo, $nombre_imagen_slider);
    //Devuelvol un arreglo, con datos del archivo subido
    $arreglo[0]          = $ruta_con_archivo; //Ruta Completa
    $arreglo[1]          = $prefijo . "." . $extension_doc; //Nombre del Archivo
    $arreglo[2]          = $ruta . "/" . $prefijo; //Ruta del Objeto sin el index
    // print_r($arreglo);
    //exit;
    if($extension_doc==""){
        $arreglo[1]="";
    }

    return ($arreglo);
}
