<?php

namespace App\Controllers;

use App\Models\Users;
use CodeIgniter\Model;
use CodeIgniter\RESTful\ResourceController;

class UserController extends ResourceController
{
    private Model $userModel;

    public function __construct()
    {
        $this->userModel = new Users();
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $result =[
          'message' => 'success',
          'data' =>  $this->userModel->findAll(),
        ];

        return $this->respond($result, 200);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        $result = [
          'message' => 'success',
          'data' => $this->userModel->find($id),
        ];

        if ($result['data'] == null) {
            return $this->failNotFound("Not data found");
        }

        return $this->respond($result, 200);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $valid = $this->validate([
            'firstname' => 'required|min_length[3]',
            'lastname' => 'required|min_length[3]',
            'email' => 'required|valid_email'
        ]);

        if (!$valid) {
            $error = $this->validator->getErrors();
            return $this->failValidationErrors($error);
        }

        try {
            $this->userModel->insert([
                'firstname' => esc($this->request->getVar("firstname")),
                'lastname' => esc($this->request->getVar("lastname")),
                'email' => esc($this->request->getVar("email")),
            ]);

            $result = [
                'message' => 'User has been created!!!'
            ];

            return $this->respondCreated($result);
        } catch (\Throwable $e) {
            return ["message" => $e->getMessage()];
        }
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        $valid = $this->validate([
            'firstname' => 'required|min_length[3]',
            'lastname' => 'required|min_length[3]',
            'email' => 'required|valid_email'
        ]);

        if (!$valid) {
            $error = $this->validator->getErrors();
            return $this->failValidationErrors($error);
        }

        try {
            $this->userModel->update($id, [
                'firstname' => esc($this->request->getVar("firstname")),
                'lastname' => esc($this->request->getVar("lastname")),
                'email' => esc($this->request->getVar("email")),
            ]);

            $result = [
                'message' => 'User has been updated!!!'
            ];

            return $this->respondCreated($result);
        } catch (\Throwable $e) {
            return ["message" => $e->getMessage()];
        }
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        try {
            $this->userModel->delete($id);

            $result = [
                'message' => 'User has been deleted!!!'
            ];

            return $this->respondDeleted($result, 200);
        } catch (\Throwable $e) {
            return ["message" => $e->getMessage()];
        }
    }
}
