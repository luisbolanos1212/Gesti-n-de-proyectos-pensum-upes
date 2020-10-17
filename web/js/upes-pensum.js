$(document).ready(function(){

    $('th').hover(function(){
        //console.log($(this).width());
    });

    var upes = {

       corregir_columnas: function (){
           
            var alturas = [];
            var anchos = [];
            $columnas_encabezado = $('th');

            $columnas_encabezado.each(function(){
                console.log($(this).width());
                anchos.push($(this).width());
                alturas.push($(this).height());
                $columnas_encabezado.width(Math.max(anchos));
                $columnas_encabezado.height(Math.max(alturas));
            });           
            console.log(Math.max(anchos), Math.max(alturas));
       

       }
    
    };

    //upes.corregir_columnas();

});