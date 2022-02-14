<?php

    function ValidarFechas($f1,$f2){
        $fecha_inicio = new DateTime($f1);
        $fecha_fin    = new DateTime($f2);
        if($fecha_fin > $fecha_inicio){
            return true;
        }
        return false;
    }

    function CalcularDias($f1,$f2){
        $dias = date_diff(date_create( $f1),date_create($f2));
        return ($dias->days+1);
    }

    function CalcularSabadoDomingo($f1,$dias){
        $f1formato = strtotime(date_format(date_create($f1),"Y-m-d"));
        $n = 0;
        $ndia = date("w",$f1formato);

        for($i = 1; $i <= $dias;$i++){ 
            if( $ndia == 0 || $ndia == 6){
                $n++;
            }
            if($ndia == 6){
                $ndia = 0;
            }else{
                $ndia += 1; 
            }
        }
        return $n;
    }


    function CalcularValor($dias,$diasSabadoDomingo,$gama){

        $valor =0;
        if($gama == "Alta"){
            $valor = 100;
        }else if($gama == "Media"){
            $valor = 70;
        }else if($gama == "Baja"){
            $valor = 50;
        }

        if($diasSabadoDomingo>0){
            $diasaux = $dias- $diasSabadoDomingo ;
            $valoraux = $valor*$diasaux;
            $valor = ((($valor*0.3)+$valor)*$diasSabadoDomingo)+$valoraux;
        }else{
            $valor *= $dias;
        }

        if($dias > 10 && $dias <=14){
            $valor = $valor-($valor *0.05);
        }else if($dias >= 15){
            $valor = $valor-($valor *0.1);

        }

        return $valor;

    }



?>
