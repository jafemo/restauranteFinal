<?php

namespace tapasBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use tapasBundle\Entity\tapas;
use tapasBundle\Form\tapasType;
use Symfony\Component\HttpFoundation\Request;

class tapasController extends Controller
{
    /**
     * @Route("/mostrar", name="mostrar_tapa")
     */
    public function mostrarTapaAction()
    {

      $repository = $this->getDoctrine()->getRepository('tapasBundle:tapas');
      $mostrar = $repository->findAll();
      return $this->render('tapasBundle:Carpeta_Tapas:mostrarTapas.html.twig',array('TablaTapas' => $mostrar ));
    }

    /**
     * @Route("/mostrarUna", name="mostrar_una_tapa")
     */
    public function mostrarTapaIdAction($id)
    {

        $repository = $this->getDoctrine()->getRepository('tapasBundle:tapas');

        $tapas = $repository->find($id);
          //Te redirecciona donde estan todos los elementos de la tabla
         // if(!$productos){return $this->redirectToRoute('pruebas_muestraProductos');}
         if (!$tapas) {
               throw $this->createNotFoundException(
                   'No se ha encontrado el id : '.$id
               );
           }
        return $this->render('tapasBundle:Carpeta_Tapas:muestraTodosId.html.twig',array('idTapa' => $tapas ));
    }

    /**
     * @Route("/CUD/crearTapa", name="crearTapa")
     */
    public function crearTapaAction(Request $request)
    {
    //dentro de la función añadimos un objeto de nuestra Entity:
    $entity = new tapas();
    $form= $this->createForm(tapasType::class,$entity);/*Aquí le añadimos la variable del objeto*/
    $form->handleRequest($request);
    //A continuación viene una comprobación si se aprieta el botón de enviar:
    if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $entity = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
             $DB = $this->getDoctrine()->getManager();
             $DB->persist($entity);
             $DB->flush();

              return $this->render('tapasBundle:Carpeta_Tapas:ultimoInsertado.html.twig', array('TablaTapas' => $entity));
      }
        //en el caso de que no haya validacion se mostrara el formulario
        return $this->render('tapasBundle:Carpeta_Tapas:formulario.html.twig',array('form' => $form->createView() ));
    }

    /**
   * @Route("/CUD/eliminarTapa/{id}", name="eliminar_campo")
   */
  public function eliminarTapaAction($id)
  {
          $DB = $this->getDoctrine()->getManager();
          $eliminar = $DB->getRepository('tapasBundle:tapas')->find($id);

          if (!$eliminar) {
              throw $this->createNotFoundException(
                  'No se ha encontrado el id: '.$id
              );
          }

          $DB->remove($eliminar);
          $DB->flush();

      return $this->render("tapasBundle:Carpeta_Tapas:eliminar.html.twig", array('TablaEntity'=>$eliminar));
  }

}
