<?php
namespace Calificaciones\Form;

class CalificacionesForm extends \Franky\Form\Form
{
    public function __construct($name)
    {

        parent::__construct();

        $this->setAtributos(array(
            'name' => $name,
            'action' =>  "/calificaciones/modulo/submit.php",
            'method' => 'post'
        ));

        $this->add(array(
                    'name' => 'callback',
                    'type'  => 'hidden',
                )
        );
        $this->add(array(
                    'name' => 'id_item',
                    'type'  => 'hidden',
                )
        );
        $this->add(array(
            'name' => 'calificacion',
            'type'  => 'hidden',
            'atributos' => array(
                'id'       => 'calificacion'
             ),
        )
        );

        $this->add(array(
            'name' => 'seccion',
            'type'  => 'hidden',
        )
        );
        $this->add(array(
            'name' => 'seccion_config',
            'type'  => 'hidden',
        )
        );

        $this->add(array(
                'name' => 'nombre',
                'label' => 'Nombre:',
                'type'  => 'text',
                'required'  => true,
                'atributos' => array(
                    'class'       => 'required',
                    'maxlength' => 255
                 ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                 )
            )
        );
        $this->add(array(
            'name' => 'email',
            'label' => 'E-mail:',
            'type'  => 'text',
            'required'  => true,
            'atributos' => array(
                'class'       => 'required email',
                'maxlength' => 255
             ),
            'label_atributos' => array(
                'class'       => 'desc_form_obligatorio'
             )
            )
        );
        $this->add(array(
            'name' => 'titulo',
            'label' => 'Titulo:',
            'type'  => 'text',
            'required'  => true,
            'atributos' => array(
                'class'       => 'required',
                'maxlength' => 255
                ),
            'label_atributos' => array(
                'class'       => 'desc_form_obligatorio'
                )
            )
        );
        $this->add(array(
                'name' => 'comentario',
                'label' => 'Comentario:',
                'type'  => 'textarea',
                'required'  => false,
                'atributos' => array(
                    'class'       => 'required',
                    'maxlength' => 500
                ),
                'label_atributos' => array(
                    'class'       => 'desc_form_obligatorio'
                )
            )
        );


        $this->add(array(
                'name' => 'guardar',
                'type'  => 'submit',
                'atributos' => array(
                    'class'       => 'btn btn-primary btn-big float_right',
                    'value' => "Guardar"
                 )

            )
        );

    }

}
?>
