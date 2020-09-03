<?php 

namespace App\front_office\Models;

Class ServicesModel extends \App\DbConnect\Model
{
    /**
     * Get all the services
     * @param int|null $limit
     * @return array returns in an array all the services
     */
    public function getServices(int $limit = null):array
    {

        $sql = 'SELECT * FROM services ORDER BY id DESC';

        if($limit)
        {
            $sql .= ' LIMIT ' . $limit;
        }

        $req = $this->pdo->query($sql);
        return $req->fetchAll();
    }

    /**
     * It get a single service from the database
     * @param $id Id of the service
     * @return mixed
     */
    public function getServiceById(int $id)
    {

        $req = $this->pdo->prepare('SELECT * FROM services WHERE id = :id');

        $req->bindValue(':id', (int) $id);
        $req->execute();

        $services = $req->fetch();

        return $services;

    }

    /**
     * Add a new service
     * @param string $title the title of the service
     * @param string $content the description of service
     * @param string $file_name the name and the extension of the image
     *
     * @return bool Returns TRUE on succes or FALSE on failure
     */
    public function addService(string $title, string $content, string $file_name):bool
    {
        $addService = $this->pdo->prepare('INSERT INTO services(title, content, file_name) VALUES(:title, :content, :file_name)');

        $addService->bindValue(':title', $title);
        $addService->bindValue(':content', $content);
        $addService->bindValue(':file_name', $file_name);

        $service =$addService->execute();
        return $service;
    }

    /**
     * Edit an existing service
     * @param string $title the title of the image
     * @param string $content the description of the project
     * @param string $file_name the name and the extention of the image
     * @param int $id the id of the project
     *
     * @return bool Returns TRUE on succes or FALSE on failure
     */
    public function editService(string $title, string $content, string $file_name, int $id):bool
    {

        $editService = $this->pdo->prepare('UPDATE services SET title = :title, content = :content, file_name = :file_name WHERE id = :id');

        return $editService->execute(array(
                            ':title' => $title,
                            ':content'=> $content,
                            ':file_name' => $file_name,
                            ':id'=> $id
                         ));
    }

    /**
     * Remove a service from the list
     * @param  int $id the id of the service
     * @return bool Returns True on succes or False on failure.
     */
    public function deleteService(int $id):bool
    {
        $deleteService = $this->pdo->prepare('DELETE FROM services WHERE id = :id');

        return $deleteService->execute(array( ':id' => $id));

    }
}