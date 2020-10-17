<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Pensum';

$ocultar = empty($materias_con_estado) ? 'd-none' : '';

if(empty($materias_con_estado) && $is_post_request){  
  ?>
  <br>
  <div class="alert alert-warning" role="alert">
     <?php echo 'No se encontraron resultados'; ?>
  </div>
  <?php
}
?><br><br>
<div class="row <?php echo $ocultar;?>">
   <div class="col">
    
       <span><strong>Alumno:</strong></span>
       <span class="d-block">
         <?php echo !empty($alumno) ? "{$alumno->nombres} {$alumno->apellido1} {$alumno->apellido2}" : ''; ?>
       </span>     
      
   </div>
   <div class="col">
       <span><strong>Carnet:</strong></span>
       <span class="d-block">
         <?php echo !empty($alumno) ? "{$alumno->carnet}" : '';?>
       </span>
   </div>  
   <div class="col">
       <span><strong>Carrera:</strong></span>
       <span class="d-block">
         <?php echo !empty($carrera) ? "{$carrera->nombre}" : '';?>
       </span>
   </div>
</div><br><br>
<div class="table-responsive <?php echo $ocultar;?>">
<table id="pensum" class="table table-borderless">
   <thead>
      <tr>     
        <?php for($i=1; $i<=$total_ciclos; $i++): ?>
          <th class="columna-pensum">          
            <div class="container text-center">            
              <div class="row ciclo-info" style ="border: solid black 1px;">
                 <div class="col">
                     <span><?php echo Html::encode('CICLO ' . $numeros_romanos[$i]); ?></span>
                 </div>                 
              </div>
              <div class="row ciclo-info">
                 <div class="col">
                     <span> <?php echo Html::encode($unidades_valorativas[$i] . ' u.v.'); ?></span> 
                 </div>           
              </div>
            </div>
          </th>
        <?php endfor ?>
      </tr>
    </thead>
    <tbody>
      <?php for($fila=1; $fila<=$max_filas; $fila++): ?>
         <tr >
           <?php for($ciclo=1; $ciclo<=$total_ciclos; $ciclo++): ?>
           <?php     $materia        = array_shift($materias_con_estado[$ciclo]); ?>
           <?php     $nombre_materia = !empty($materia) ? $materia['asignatura']->nombre : '';
                     $estado_materia = !empty($materia) ? $materia['estado'] : '';
                     $materia_uvs    = !empty($materia) ? $materia['asignatura']->unidadvalo : '';
                     $codigo_materia = !empty($materia) ? $materia['asignatura']->codigo : '';
                     $correlativo    = !empty($materia) ? $materia['plan']->correlativ : '';
                     $texto_nota     = !empty($materia) ? $materia['texto_nota'] : '';
                     $display        = !empty($materia) ? '' : 'invisible';
           ?>   
                     <td class="columna-pensum align-middle <?php echo Html::encode($display); ?>" <?php // style="border: solid 2px black;"?>>
                    
                      <div class="container <?php echo Html::encode($estado_materia /*. ' ' . $display*/); ?>">
                        <div class="row row-correlativo-y-uvs borde-row-info-materia">
                           <div class="col text-center" title="Correlativo">
                              <?php  echo Html::encode($correlativo); ?>
                           </div>
                           <div class="col text-center borde-izquierdo" title="Unidades valorativas">
                              <?php  echo Html::encode($materia_uvs); ?>
                           </div>
                        </div> 
                        <div class="row row-nombre overflow-hidden borde-row-nombre-materia" title="<?php  echo Html::encode($nombre_materia . $texto_nota); ?>">
                           <div class="col text-center">
                              <?php  echo Html::encode($nombre_materia); ?>
                           </div>                         
                        </div> 
                        <div class="row row-codigo borde-row-codigo-materia" title="Codigo">
                           <div class="col text-center">
                              <?php  echo Html::encode($codigo_materia); ?>
                           </div>                         
                        </div> 
                      </div> 
                      <!--<?php  echo Html::encode($nombre_materia); ?></span>  --> 
                       
                     </td>
           <?php endfor ?>
         </tr>
      <?php endfor ?>
    </tbody>   
</table>
</div>
<div class="row <?php echo $ocultar;?>">
    <div class="col">
         <ul>
           <li class="d-inline texto-aprobada"> 
              Materia aprobada
           </li> |
           <li class="d-inline texto-cursando"> 
              Cursando materia
           </li> |
           <li class="d-inline texto-reprobada"> 
              Materia reprobada
           </li>
         </ul>
    </div>
</div>




