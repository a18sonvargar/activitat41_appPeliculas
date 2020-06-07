<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{

    /**
     * @Route("/app", name="app")
     */
    public function index()
    {

        $cliente = HttpClient::create();
        $respuesta = $cliente->request('GET', 'http://localhost:8000/api/peliculas');

        $codigo = $respuesta->getStatusCode();

        if( $codigo == 200 ){
            $contenidoJSON = $respuesta->getContent();

            $datos = json_decode($contenidoJSON);

            $peliculas = array();

            for ($i=0; $i<sizeof($datos); $i++){
                $id = $datos[$i]->id;
                $nombre = $datos[$i]->nombre;
                $genero = $datos[$i]->genero;
                $descripcion = $datos[$i]->descripcion;

                $pelicula = array(
                    'id' => $id,
                    'nombre' => $nombre,
                    'genero' => $genero,
                    'descripcion' => $descripcion
                );

                array_push($peliculas, $pelicula);
            }

            return $this->render('base.html.twig', ['peliculas' => $peliculas]);

        }

        return $this->render('base.html.twig');
    }

    /**
     * @Route("/addPelicula", name="addPelicula")
     */
    public function addPelicula(Request $request)
    {
        $client = HttpClient::create();

        $nombre = $request->get('nombrePeliculaNueva');
        $genero = $request->get('generoPeliculaNueva');
        $descripcion = $request->get('descripcionPeliculaNueva');

        $respuesta = $client->request('POST', 'http://localhost:8000/api/pelicula', [
            'body' => '{ "nombre": "'.$nombre.'", "genero": "'.$genero.'", "descripcion": "'.$descripcion.'" }']);

        return $this->redirect($this->generateUrl('app'));
    }




}
