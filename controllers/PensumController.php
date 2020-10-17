<?php
//Ejemplo
namespace app\controllers;

use yii\web\Controller;
use app\models\ExpedienteAlumno;
use app\models\Planes;
use app\models\Asignatura;
use app\models\Notas;
use app\models\Notash;
use app\models\Carrera;
use Yii;

class PensumController extends Controller
{

    public $layout = 'pensum/main';

    public function actionBuscar()
    {
       
        $is_post_request = Yii::$app->request->isPost;
        //Solicitando a yii las variables que vienen en la peticion post   
        $post_vars =  Yii::$app->request->post(); 

        //Obteniendo el carnet del alumno si viene en las variables de la peticion post de lo contrario obtenemos una cadena vacia
        $carnet = array_key_exists('carnet', $post_vars) ? $post_vars['carnet'] : ''; 

        //Intentamos encontrar el alumno en la tabla expediente alumno
        $alumno = ExpedienteAlumno::findOne($carnet);

        //Obteniendo carrera del alumno
        $carrera = !empty($alumno) ? Carrera::findOne($alumno->codcarrera) : NULL;

        //Intentamos encontrar las materias que el alumno esta cursando actualmente
        $materias_alumno_ciclo_actual = Notas::findAll(['carnet' => $carnet]);

        //Obteniendo listado de codigos de asignaturas que el alumno esta cursando actualmente
        $codigos_materias_alumno_ciclo_actual = !empty($materias_alumno_ciclo_actual) ? array_map(function($nota){ return $nota->codigo_asi;  }, $materias_alumno_ciclo_actual) : array();
       
        //Intentamos obtener el historial academico del alumno, es decir las notas de ciclos pasados
        $materias_cursadas = Notash::findAll(['carnet' => $carnet]);

        //Colocamos los codigos de las asignaturas como llaves al array para poder acceder facilmente a ellas
        $materias_cursadas = $this->indexar($materias_cursadas, 'codigo_asi');      

        //Intentamos obtener el plan de estudios que ha sido asignado al alumno
        $plan = !empty($alumno) ? Planes::findAll($alumno->codigo_pla) : array();
   
        //Inicializando listado de numeros de ciclos, esto nos permitira saber cuantas columnas debomos mostrar en la tabla
        $ciclos = array();

        //Inicializando total ciclos
        $total_ciclos = 0;     

        //Inicializando unidades valorativas por ciclo
        $unidades_valorativas = array(); 

        //Inicializando codigos de materias
        $codigos_materias = array();

        //Inicializando lista para plan con codigos de asignaturas como llaves del array para poder acceder facilmente a ellas
        $plan_indexado = array();

        //Indexando plan es decir colocando los codigos de las materias como llaves del array
        foreach($plan as $materia_plan){
              //Colocando el codigo de la materia como llave del array y asignamos la materia al mismo tiempo
              $plan_indexado[$materia_plan->asignatura] = $materia_plan ;
              //Agregando a los numeros de ciclos el ciclo de la materia actual en la iteracion
              $ciclos[] = $materia_plan->ciclo;  
              //Agregamos el codigo de la materia al listado de codigos de materias, esto nos servira para obtener la informacion de las materias en la tabla asignatura
              $codigos_materias[] = $materia_plan->asignatura;
      
        }

        //Intentamos obtener la informacion de las materias del plan 
        $materias = Asignatura::findAll($codigos_materias);

        //Inicializamos la lista que tendra las materias por ciclo
        $materias_ciclos = array();  

        //Calculando las unidades valorativas por ciclo
        foreach($materias as $materia){
               //Obteniendo materia del plan
               $materia_plan = $plan_indexado[$materia->codigo];

               //Sumando las unidades valorativas
               $unidades_valorativas[$materia_plan->ciclo] = 
                              array_key_exists($materia_plan->ciclo,  $unidades_valorativas) ?
                                 $unidades_valorativas[$materia_plan->ciclo] + $materia->unidadvalo :
                                 $materia->unidadvalo;

               //Isertamos la materia de acuerdo al ciclo
               //Nos referimos a la informacion de la materia segun la tabla de la que proviene
               $materia_info = array(
                   'plan' => $materia_plan,
                   'asignatura' => $materia
               );  

               $materias_ciclos[$materia_plan->ciclo][] = $materia_info;

        }
        
        //Calculamos el numero maximo de ciclo y esa cantidad de columnas tendra la tabla que mostrara el pensum
        $total_ciclos = !empty($ciclos) ? max($ciclos) : 0;   

        //Contamos las materias de cada ciclo
        $cuenta_materias_ciclos = array_map('count', $materias_ciclos);

        //Calculando el numero maximo de materias que se dan por ciclo, esa cantidad de filas como maximo tendra la tabla que mostrara el pensum
        $max_filas = !empty($cuenta_materias_ciclos) ? max($cuenta_materias_ciclos) : 0;

        //Asignando los estados de las materias
        $materias_con_estado = $this->generar_plan_con_estados_de_materias($materias_ciclos, $materias_cursadas, $codigos_materias_alumno_ciclo_actual);
        
        //Inicializando vista
        return $this->render(
               'pensum',
               [
                  'is_post_request' =>  $is_post_request, 
                  'alumno' =>$alumno ,
                  'carrera' => $carrera,
                  'carnet' => !empty($post_vars['carnet']) ? $post_vars['carnet'] : '',
                  'unidades_valorativas' => $unidades_valorativas,
                  'total_ciclos' => $total_ciclos,                
                  'max_filas' => $max_filas,                  
                  'materias_con_estado' => $materias_con_estado,
                  'numeros_romanos' => array(
                        1  => 'I',
                        2  => 'II',
                        3  => 'III',
                        4  => 'IV',
                        5  => 'V',
                        6  => 'VI',
                        7  => 'VII',
                        8  => 'VIII',
                        9  => 'VIX',
                        10 => 'X'
                  ) 
               ]
        );

    }

    public function indexar($items, $key){

          $lista_indexada = array(); 

          if(empty($items)){ return array(); }

          foreach($items as $item){

              $lista_indexada[$item->$key] = $item;

          }

          return $lista_indexada;

    }

    public function generar_plan_con_estados_de_materias($materias_ciclos, $materias_cursadas, $materias_cursando){
        
          $nota_minima_para_aprobar = array_key_exists('nota_minima_para_aprobar', Yii::$app->params) ?
                                           floatval(Yii::$app->params['nota_minima_para_aprobar']) :
                                           6;
         
          $materias_con_estado = array();

          foreach($materias_ciclos as $ciclo => $materias){
            
            foreach($materias as $materia){            
    
                $cursando_materia = in_array( $materia['asignatura']->codigo, $materias_cursando ) ;
             
                $materia['estado'] = $cursando_materia ? 'cursando-materia' : '';           

                if($cursando_materia){
                    $materia['texto_nota'] = '';
                    $materias_con_estado[$ciclo][] = $materia; 
                    continue; 
                }
    
                $materia_cursada = in_array($materia['asignatura']->codigo, array_keys($materias_cursadas));                
    
                if(!$materia_cursada){                  
                    $materia['texto_nota'] = '';
                    $materia['estado'] = '';  
                    $materias_con_estado[$ciclo][] = $materia; 
                    continue; 
                }
    
                $materia['estado'] = floatval($materias_cursadas[$materia['asignatura']->codigo]->promedio) >= $nota_minima_para_aprobar ? 'materia-aprobada' : 'materia-reprobada'; 
                $materia['texto_nota'] = ' - ' . $materias_cursadas[$materia['asignatura']->codigo]->promedio;
                $materias_con_estado[$ciclo][] = $materia;           
                 
            }   
            
            usort($materias_con_estado[$ciclo], array($this, 'comparar_correlativo'));

          }

          return $materias_con_estado;

    }

    function comparar_correlativo($a, $b){

        return $a['plan']->correlativ > $b['plan']->correlativ;

    }

}