<?php 

namespace App\front_office\Models;

Class ProjectModel extends \App\DbConnect\Model
{
    /**
     * Get all the projects
     * @param int|null $limit //it determinates if there are a limit when the projects are shown in the page
     * @return array returns in an array all the projects
     */
    public function getProjects(int $limit = null):array
    {
        $sql = 'SELECT * FROM project ORDER BY id DESC';

        if($limit)
        {
            $sql .= ' LIMIT ' . $limit;
        }

        $req = $this->pdo->query($sql);
        return $req->fetchAll();
    }


    /**
     * Get a single project from the database
     * @param $id
     * @return mixed
     */
    public function getProject(int $id)
    {
        $req = $this->pdo->prepare('SELECT id, title, content, file_name, city, DATE_FORMAT(creation_date, \'%d/%m/%Y à %Hh%imin\') AS creation_date_it, DATE_FORMAT(editing_date, \'%d/%m/%Y à %Hh%imin\') AS editing_date_it FROM project WHERE id = :id');

        $req->bindValue(':id', (int) $id);
        $req->execute();

        $project = $req->fetch();

        return $project;
    }

    /**
     * Add a new project
     * @param string $title the title of the project
     * @param string $content the description of project
     * @param string $file_name the name and the extension of the image
     * @param string $city the name of the city of project
     *
     * @return bool Returns TRUE on succes or FALSE on failure
     */
    public function addProject(string $title, string $content, string $file_name, string $city):bool
    {
        $addProject = $this->pdo->prepare('INSERT INTO project(title, content, file_name, city, creation_date) VALUES(:title, :content, :file_name, :city, NOW())');

        $addProject->bindValue(':title', $title);
        $addProject->bindValue(':content', $content);
        $addProject->bindValue(':file_name', $file_name);
        $addProject->bindValue(':city', $city);

        return $addProject->execute();
    }

    /**
     * Edit an existing project
     * @param string $title the title of the image
     * @param string $content the description of the project
     * @param string $city the name of the city where is located the project
     * @param int $id the id of the project
     *
     * @return bool Returns TRUE on succes or FALSE on failure
     */
    public function editProject(string $title, string $content, string $city, int $id):bool
    {
        $editProject = $this->pdo->prepare('UPDATE project SET title = :title, content = :content, city = :city WHERE id = :id');

        return $editProject->execute(array(
                            ':title' => $title,
                            ':content'=> $content,
                            ':city'=> $city,
                            ':id'=> $id
                         ));
    }

    /**
     * It edits the thumbnail of an existing project
     * @param string $file_name
     * @param int $id id of the project
     * @return bool return true if success and false if falure
     */
    public function editProjectThumbnail(string $file_name, int $id):bool
    {
        $editProject = $this->pdo->prepare('UPDATE project SET file_name = :file_name WHERE id = :id');

        return $editProject->execute(array(
            ':file_name' => $file_name,
            ':id'=> $id
        ));
    }

    /**
     * Remove a project from the list
     * @param  int $id the id of the project
     * @return bool Returns True on succes or False on failure.
     */
    public function deleteProject(int $id):bool
    {
        $deleteProject = $this->pdo->prepare('DELETE FROM project WHERE id = :id');

        return $deleteProject->execute(array( ':id' => $id));

    }

}