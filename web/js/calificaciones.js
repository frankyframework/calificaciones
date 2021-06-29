function ajax_calificaciones_getPendientesRevisar(permiso,tabla,seccion)
{
    var var_query = {
        "function": "ajax_calificaciones_getPendientesRevisar",
        "vars_ajax":[permiso,tabla,seccion]
    };
    pasarelaAjax('GET',var_query,"ajax_calificaciones_getPendientesRevisarHTML",var_query.vars_ajax,null);
}



function ajax_calificaciones_getPendientesRevisarHTML(response,permiso,tabla,seccion)
{
    var respuesta = null;
    if(response != "null")
    {
      respuesta = JSON.parse(response);

      if(respuesta['solicitudes_'+seccion])
      {
          $('.admin-catalog-products-calificaciones-pendientes').append(respuesta['solicitudes_'+seccion]);
      }
    
    }
    return true;
}

