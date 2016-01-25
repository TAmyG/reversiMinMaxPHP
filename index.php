<?php
//http://reversi-201212589-tamy-g.c9users.io/?turno=0&estado=2222222222222222222222222221022222211222222222222222222222222222

$matrizPeso = [ 
                 [ 120, -20, 20,  5,  5, 20, -20, 120 ],
                 [ -20, -40, -5, -5, -5, -5, -40, -20 ],
                 [  20,  -5, 15,  3,  3, 15,  -5,  20 ],
                 [   5,  -5,  3,  3,  3,  3,  -5,   5 ],
                 [  20,  -5, 15,  3,  3, 15,  -5,  20 ],
                 [   5,  -5,  3,  3,  3,  3,  -5,   5 ],
                 [ -20, -40, -5, -5, -5, -5, -40, -20 ],
                 [ 120, -20, 20,  5,  5, 20, -20, 120 ]
                ];

if( $_GET['estado']!= '' && $_GET['turno']!='' ){
    file_put_contents('your_log_file', $_GET['estado']);
    llenarMatriz($_GET['estado'], $_GET['turno']);
}else
    echo 'no parametros';
    
function llenarMatriz ($estado, $turno){
    $yo = 0;
    $oponente = 1;
    $contador = 0;
    
    if($turno == '1'){
        $yo = 1; $oponente = 0;
    }
    
    for ($i = 0; $i < 8; $i++) {
         for ($j = 0; $j < 8; $j++) {
              $matriz[$i][$j] = (int)$estado[$contador];
              $contador++;
         }
    }
    /*for ($i = 0; $i < 8; $i++) {
         for ($j = 0; $j < 8; $j++) {
              echo $matriz[$i][$j];
         }
         echo '<br>';
    }*/
    recorrerMatriz($matriz, $yo, $oponente);
}

function recorrerMatriz($matriz,$yo, $oponente){
    $pila = array();
    for ($i = 0; $i < 8; $i++) {
         for ($j = 0; $j < 8; $j++) {
              if($matriz[$i][$j] == $yo){
                  
                //echo $matriz[$i][$j].'**********';
                  $mN = tieneMovimientoNorte($matriz, $yo, $oponente, $i, $j);
                  $mS = tieneMovimientoSur($matriz, $yo, $oponente, $i, $j);
                  $mD = tieneMovimientoDerecha($matriz, $yo, $oponente, $i, $j);
                  $mI = tieneMovimientoIzquierda($matriz, $yo, $oponente, $i, $j);
                  //esquinas
                  $mND = tieneMovimientoNorDer($matriz, $yo, $oponente, $i, $j);
                  $mNI = tieneMovimientoNorIzq($matriz, $yo, $oponente, $i, $j);
                  $mSD = tieneMovimientoSurDer($matriz, $yo, $oponente, $i, $j);
                  $mSI = tieneMovimientoSurIzq($matriz, $yo, $oponente, $i, $j);
                  //echo $mN.'|'.$mS.'|'.$mD.'|'.$mI.'|'.$mND.'|'.$mNI.'|'.$mSD.'|'.$mSI.'<br>';
                  array_push($pila, getMejor($mN, $mS, $mD, $mI,$mND,$mNI,$mSD,$mSI));
                  
              }
         }
    }
    
    
    $retorno = getMejorDefinitivo($pila);
    //echo '<br><br>'.$retorno;
    //file_put_contents('your_log_file', $retorno);
    echo $retorno;
}

function tieneMovimientoNorte($matriz, $yo, $oponente, $i, $j){
    $iterador = $i;
    $contador = 1;
    if(($i-1) > 0){
        if($matriz[$i-1][$j] == $oponente){
            $contador ++;
            while($iterador > -1){
                $iterador--;
                if($matriz[$iterador][$j] == $oponente){//hay oponente por lo que puede seguir subiendo
                    $contador ++;
                }else if($matriz[$iterador][$j] == 2){//hay vacío por lo que aquí debería colocar mi ficha
                    $contador += getPeso($iterador, $j);
                    return $iterador.''.$j.','.$contador;
                }else{//me econtré a mi mismo por lo que el movimientos es inválido.
                    return '-1';
                }
            }
            return '-1';
        }else 
            return '-1';
    }else
        return '-1';
}

function tieneMovimientoSur($matriz, $yo, $oponente, $i, $j){
    $iterador = $i;
    $contador = 1;
    if(($i + 1) < 7){
        if($matriz[$i+1][$j] == $oponente){
            $contador ++;
            while($iterador < 8){
                $iterador++;
                if($matriz[$iterador][$j] == $oponente){//hay oponente por lo que puede seguir subiendo
                    $contador ++;
                }else if($matriz[$iterador][$j] == 2){//hay vacío por lo que aquí debería colocar mi ficha
                    $contador += getPeso($iterador, $j);
                    return $iterador.''.$j.','.$contador;
                }else{//me econtré a mi mismo por lo que el movimientos es inválido.
                    return '-1';
                }
            }
            return '-1';
        }else 
            return '-1';
    }else
        return '-1';
}

function tieneMovimientoDerecha($matriz, $yo, $oponente, $i, $j){
    $iterador = $j;
    $contador = 1;
    if(( $j + 1 ) < 7){
        if($matriz[$i][$j + 1] == $oponente){
            $contador ++;
            while($iterador < 8){
                $iterador++;
                if($matriz[$i][$iterador] == $oponente){//hay oponente por lo que puede seguir subiendo
                    $contador ++;
                }else if($matriz[$i][$iterador] == 2){//hay vacío por lo que aquí debería colocar mi ficha
                    $contador += getPeso($i, $iterador);
                    return $i.''.$iterador.','.$contador;
                    break 1;
                }else{//me econtré a mi mismo por lo que el movimientos es inválido.
                    return '-1';
                    break 1;
                }
            }
            return '-1';
        }else 
            return '-1';
    }else
        return '-1';
}

function tieneMovimientoIzquierda($matriz, $yo, $oponente, $i, $j){
    $iterador = $j;
    $contador = 1;
    if(( $j - 1 ) > 0){
        if($matriz[$i][$j - 1] == $oponente){
            $contador ++;
            while($iterador > -1){
                $iterador--;
                if($matriz[$i][$iterador] == $oponente){//hay oponente por lo que puede seguir subiendo
                    $contador ++;
                }else if($matriz[$i][$iterador] == 2){//hay vacío por lo que aquí debería colocar mi ficha
                    $contador += getPeso($i, $iterador);
                    return $i.''.$iterador.','.$contador;
                    break 1;
                }else{//me econtré a mi mismo por lo que el movimientos es inválido.
                    return '-1';
                    break 1;
                }
            }
            return '-1';
        }else 
            return '-1';
    }else
        return '-1';
}

//DIAGONALES------------------------------------------------------------------------------------------------------------------
function tieneMovimientoNorIzq($matriz, $yo, $oponente, $i, $j){
    $fila = $i;    $col = $j;
    $contador = 1;
    if(( $i - 1 ) > 0 and ($j - 1) > 0){
        if($matriz[$i - 1][$j - 1] == $oponente){
            $contador ++;
            while($fila > -1 && $col > -1){
                $fila--; $col--;
                if($matriz[$fila][$col] == $oponente){//hay oponente por lo que puede seguir subiendo
                    $contador ++;
                }else if($matriz[$fila][$col] == 2){//hay vacío por lo que aquí debería colocar mi ficha
                    $contador += getPeso($fila, $col);
                    return $fila.''.$col.','.$contador;
                    break 1;
                }else{//me econtré a mi mismo por lo que el movimientos es inválido.
                    return '-1';
                    break 1;
                }
            }
            return '-1';
        }else 
            return '-1';
    }else
        return '-1';
}


function tieneMovimientoNorDer($matriz, $yo, $oponente, $i, $j){
    $fila = $i;    $col = $j;
    $contador = 1;
    if(( $i - 1 ) > 0 and ($j + 1) < 7){
        if($matriz[$i - 1][$j + 1] == $oponente){
            $contador ++;
            while($fila > -1 && $col < 8){
                $fila--; $col++;
                if($matriz[$fila][$col] == $oponente){//hay oponente por lo que puede seguir subiendo
                    $contador ++;
                }else if($matriz[$fila][$col] == 2){//hay vacío por lo que aquí debería colocar mi ficha
                    $contador += getPeso($fila, $col);
                    return $fila.''.$col.','.$contador;
                    break 1;
                }else{//me econtré a mi mismo por lo que el movimientos es inválido.
                    return '-1';
                    break 1;
                }
            }
            return '-1';
        }else 
            return '-1';
    }else
        return '-1';
}

function tieneMovimientoSurIzq($matriz, $yo, $oponente, $i, $j){
    $fila = $i;    $col = $j;
    $contador = 1;
    if(( $i + 1 ) < 7 and ($j - 1) > 0){
        if($matriz[$i + 1][$j - 1] == $oponente){
            $contador ++;
            while($fila < 8 && $col > -1){
                $fila++; $col--;
                if($matriz[$fila][$col] == $oponente){//hay oponente por lo que puede seguir subiendo
                    $contador ++;
                }else if($matriz[$fila][$col] == 2){//hay vacío por lo que aquí debería colocar mi ficha
                    $contador += getPeso($fila, $col);
                    return $fila.''.$col.','.$contador;
                    break 1;
                }else{//me econtré a mi mismo por lo que el movimientos es inválido.
                    return '-1';
                    break 1;
                }
            }
            return '-1';
        }else 
            return '-1';
    }else
        return '-1';
}

function tieneMovimientoSurDer($matriz, $yo, $oponente, $i, $j){
    $fila = $i;    $col = $j;
    $contador = 1;
    if(( $i + 1 ) < 7 and ($j + 1) < 7){
        if($matriz[$i + 1][$j + 1] == $oponente){
            $contador ++;
            while($fila < 8 && $col < 8){
                $fila++; $col++;
                if($matriz[$fila][$col] == $oponente){//hay oponente por lo que puede seguir subiendo
                    $contador ++;
                }else if($matriz[$fila][$col] == 2){//hay vacío por lo que aquí debería colocar mi ficha
                    $contador += getPeso($fila, $col);
                    return $fila.''.$col.','.$contador;
                    break 1;
                }else{//me econtré a mi mismo por lo que el movimientos es inválido.
                    return '-1';
                    break 1;
                }
            }
            return '-1';
        }else 
            return '-1';
    }else
        return '-1';
}

//Mejor movimiento individual, hijos que maximizan
function getMejor($N, $S, $D, $I, $ND, $NI, $SD, $SI){
    $coordenadaActual = '';
    $contadorActual = -1000;
    if($N != '-1'){
        list($xy, $count) = split(',', $N);
        if($count > $contadorActual){
            $contadorActual = $count;
            $coordenadaActual = $xy;
        }
    }
    if($S != '-1'){
        list($xy, $count) = split(',', $S);
        if($count > $contadorActual){
            $contadorActual = $count;
            $coordenadaActual = $xy;
        }
    }
    if($D != '-1'){
        list($xy, $count) = split(',', $D);
        if($count > $contadorActual){
            $contadorActual = $count;
            $coordenadaActual = $xy;
        }
    }
    if($I != '-1'){
        list($xy, $count) = split(',', $I);
        if($count > $contadorActual){
            $contadorActual = $count;
            $coordenadaActual = $xy;
        }
    }
    if($ND != '-1'){
        list($xy, $count) = split(',', $ND);
        if($count > $contadorActual){
            $contadorActual = $count;
            $coordenadaActual = $xy;
        }
    }
    if($NI != '-1'){
        list($xy, $count) = split(',', $NI);
        if($count > $contadorActual){
            $contadorActual = $count;
            $coordenadaActual = $xy;
        }
    }
    if($SD != '-1'){
        list($xy, $count) = split(',', $SD);
        if($count > $contadorActual){
            $contadorActual = $count;
            $coordenadaActual = $xy;
        }
    }
    if($SI != '-1'){
        list($xy, $count) = split(',', $SI);
        if($count > $contadorActual){
            $contadorActual = $count;
            $coordenadaActual = $xy;
        }
    }
    return $coordenadaActual.','.$contadorActual;
}

//Mejor movimiento general maximización general
function getMejorDefinitivo($vector){
    $stringloco = '';
    $coordenadaActual = 'ab';
    $contadorActual = -1000;
    foreach($vector as $val) {
        list($xy, $count) = split(',', $val);
        if($count > $contadorActual){
            $stringloco = $stringloco.$count.'..'.$xy.'-----';
             $contadorActual = $count;
            $coordenadaActual = $xy;
        }
    }
    //file_put_contents('your_log_file', $stringloco);
    return $coordenadaActual;
}

//Retorna el peso según la posición donde se colocará la ficha
function getPeso($i, $j){
    global $matrizPeso;
    return $matrizPeso[$i][$j];
}

?>