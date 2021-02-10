<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class ANIMALController extends ResourceController
{

    protected $modelName = 'App\Models\AnimalModelo';
    protected $format = 'json';

    public function consultarTodos()
    {
        return $this->respond($this->model->findAll());
    }

    public  function agregarAnimal()
    {

        //recibir datos
        $id = $this->request->getPost('id');
        $nombre = $this->request->getPost('nombre');
        $edad = $this->request->getPost('edad');
        $tipo = $this->request->getPost('tipo');
        $descripcion = $this->request->getPost('descripcion');
        $comida = $this->request->getPost('comida');

        //armar arreglo asociativo donde las claves sean los nombres de las columnas
        $datosEnviar = array(
            'id' => $id,
            'nombre' => $nombre,
            'edad' => $edad,
            'tipo' => $tipo,
            'descripcion' => $descripcion,
            'comida' => $comida
        );

        //validaciones
        if ($this->validate('validacionAnimales')) {
            $this->model->insert($datosEnviar);
            $mensaje = array('estado' => true, 'mensaje' => '¡Animal agregado exitosamente!');
            return $this->respond($mensaje);
        } else {
            $validation = \Config\Services::validation();
            return $this->respond($validation->getErrors(), 400);
        }
    }

    public  function editar($id)
    {

        //recibir datos
        $datosEditar = $this->request->getRawInput();

        //armar arreglo asociativo donde las claves sean los nombres de las columnas
        $datosEnviar = array(
            'nombre' => $datosEditar['nombre'],
            'edad' => $datosEditar['edad'],
            'tipo' => $datosEditar['tipo'],
            'descripcion' => $datosEditar['descripcion'],
            'comida' => $datosEditar['comida']
        );

        //validaciones
        if ($this->validate('validacionAnimal')) {
            $this->model->update($id, $datosEnviar);
            $mensaje = array('estado' => true, 'mensaje' => '¡Animal agregado exitosamente!');
            return $this->respond($mensaje);
        } else {
            $validation = \Config\Services::validation();
            return $this->respond($validation->getErrors(), 400);
        }
    }

    public function eliminar($id)
    {

        $consulta = $this->model->where('id', $id)->delete();
        if ($consulta->connID->affected_rows == 1) {
            $mensaje = array('estado' => true, 'mensaje' => '¡Animal eliminado exitosamente!');
            return $this->respond($mensaje);
        } else {
            $mensaje = array('estado' => false, 'mensaje' => 'Este animal no ha sido encontrado');
            return $this->respond($mensaje, 400);
        }
    }
}
