<?php

function imprimir_formatos($DATE_FORMATS)
{
    for ($i=0;$i<sizeof($DATE_FORMATS);$i++) {
        print($i." => ".$DATE_FORMATS[$i]."\n");
    }
}

$DATE_FORMATS = ['Y-m-d','d-m-Y','m-d-Y','Y-m-d H:i:s',
                'm-d-Y H:i:s','d-m-Y H:i:s','Y/m/d H:i:s',
                'd/m/Y H:i:s','Y-m-d 00:00:00','m-d-Y 00:00:00',
                'd-m-Y 00:00:00','Y/m/d 00:00:00','d/m/Y 00:00:00'];

    
print("Ingrese el nombre del archivo: ");
$documento=trim(fgets(STDIN,1024));

$invalido=true;
while($invalido){
    print("El archivo posee columnas de fecha? S/N: ");
    $f=strtolower(trim(fgets(STDIN,1024)));
    if($f=='s' || $f=='n'){
        $invalido=false;
    }
}

if($f=='s'){
    print("Ingrese columna(s) que contienen campo de fecha, de ser más de una, ingreselas separadas por una coma,\n las columnas comienzan en índice 0: ");
    $indices_fecha=array_unique(explode(",",trim(fgets(STDIN,1024))));

    imprimir_formatos($DATE_FORMATS);
    print("Ingrese el índice del formato que poseen los campos de fecha: ");
    $indice_entrada=trim(fgets(STDIN,1024));

    print("Ingrese el índice del formato de salida de la fecha: ");
    $indice_salida=trim(fgets(STDIN,1024));

    if ($indice_entrada<0 || $indice_entrada>=sizeof($DATE_FORMATS) || $indice_salida<0 || $indice_salida>=sizeof($DATE_FORMATS) ){ 
        print("índice no válido, fallo");
        exit(); 
    }
}


//archivo de lectura
$archivo = fopen($documento.".csv","r");
//archivo a generar
$archivo_saneado = fopen($documento."_saneado.csv","w");

//recibiendo cabeceras del documento CSV y agregandolas al nuevo CSV
$cabeceras = fgetcsv($archivo,0);
fputs($archivo_saneado,implode($cabeceras,',').PHP_EOL);
$columnas = sizeof($cabeceras);

while(($dato=fgetcsv($archivo,0))==true){
    for($i=0;$i<$columnas;$i++){
        if(isset($indices_fecha)){
            if(in_array($i,$indices_fecha)){
                $fecha_nueva = DateTime::createFromFormat($DATE_FORMATS[$indice_entrada],$dato[$i]);
                $dato[$i]= $fecha_nueva->format($DATE_FORMATS[$indice_salida]);
            }else{
                $dato[$i]=ucwords(mb_strtolower(trim($dato[$i]),"UTF-8"));
            }
        }else{
            $dato[$i]=ucwords(mb_strtolower(trim($dato[$i]),"UTF-8"));
        }

    }
    //inserta registro en nuevo csv
    fputs($archivo_saneado,implode($dato,',').PHP_EOL);
    
}
fclose($archivo);
fclose($archivo_saneado);
?>