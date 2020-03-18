<?php

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
    print("Ingrese la(s) columna(s) que contienen campo de fecha, de ser más de una, ingreselas separadas por una coma,\n las columnas comienzan en índice 0: ");
    $indices_fecha=array_unique(explode(",",trim(fgets(STDIN,1024))));
}



$archivo = fopen($documento+".csv","r");

//recibiendo cabeceras del documento CSV
$cabeceras = fgetcsv($archivo,0);
$columnas = sizeof($cabeceras);

while(($dato=fgetcsv($archivo,0))==true){
    for($i=0;$i<$columnas;$i++){
        if(isset($indices_fecha)){
            if(in_array($i,$indices_fecha)){
                #codigo
            }else{
                $dato[$i]=ucwords(mb_strtolower(trim($dato[$i]),"UTF-8"));
            }
        }else{
            $dato[$i]=ucwords(mb_strtolower(trim($dato[$i]),"UTF-8"));
        }

    }

    print($dato);
}

?>