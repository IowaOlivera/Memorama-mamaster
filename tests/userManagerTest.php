<?php

include ('core\php\userManager.php');
//use PHPUnit\Framework\TestCase;
class userManagerTest extends PHPUnit\Framework\TestCase{

    private function setupMockito(){
        $this->UserManager = userManager::getInstance();
        $this->dbManager = Mockery::mock(DatabaseManager::class);
        $this->dbManager->shouldReceive('close')->andReturn(null);
        $this->dbManager->shouldReceive('insertQuery')->once()->with("")->andReturn(false);
        $this->UserManager->setDBManager($this->dbManager);
    }

    public function testSetUser(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('insertQuery')->with("INSERT INTO usuario (nombre, clave, tipo) VALUES('alumno','alumno','0')")->andReturn('true');
        $this->assertEquals(
            'true', $this->UserManager->setuser('alumno','alumno','0'));

    }

    public function testSetUserWithIncorrectTipo(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('insertQuery')->with("INSERT INTO usuario (nombre, clave, tipo) VALUES('','','')")->andReturn('false');
        $this->assertEquals(
            'false', $this->UserManager->setuser('','',''));
    }

    public function testUpdateUser(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('insertQuery')->with("UPDATE usuario set nombre = 'alumno' , clave = 'alumno' , tipo = '0' WHERE id= '1'")->andReturn('true');
        $this->assertEquals(
            'true', $this->UserManager->updateUser("1","alumno",'alumno','0'));
    }

    public function testUpdateUserWithIncorrectTipo(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('insertQuery')->with("UPDATE usuario set nombre = 'alumno' , clave = 'alumno' , tipo = '2' WHERE id= '1'")->andReturn('false');
        $this->assertEquals(
            'false', $this->UserManager->updateUser("1","alumno",'alumno','2'));
    }

    public function testGetUser(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM usuario WHERE nombre='alumno' AND clave='alumno'")
            ->once()
            ->andReturn(
                array(
                    json_encode('
                    {
                        id: "1",
                        nombre: "alumno",
                        tipo: "0",
                        clave: "alumno"
                    }
                ')
                )
            );
        $expectedResponse = json_encode(
            array(
                json_encode('
                    {
                        id: "1",
                        nombre: "alumno",
                        tipo: "0",
                        clave: "alumno"
                    }
                ')
            )
        );
        $this->assertEquals($expectedResponse, $this->UserManager->getUser('alumno','alumno'));

    }

    public function testGetUserWithNoDataInDB(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM usuario WHERE nombre='alumno' AND clave='alumno'")->andReturn(null);
        $this->assertEquals(
            "Tabla usuario vacia", $this->UserManager->getUser('alumno','alumno'));

    }

    public function testGetUserById(){
        $this->setupMockito();
        $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM usuario WHERE id='1'")->andReturn(array(
            json_encode('
                    {
                        id: "1",
                        nombre: "alumno",
                        tipo: "0",
                        clave: "alumno"
                    }
                ')
        ));
        $expectedResponse = json_encode(
            array(
                json_encode('
                    {
                        id: "1",
                        nombre: "alumno",
                        tipo: "0",
                        clave: "alumno"
                    }
                ')
            )
        );
        $this->assertEquals(
            $expectedResponse, $this->UserManager->getUserById(1));
    }


        public function testGetUserByIdWithIDNotExisting(){
            $this->setupMockito();
            $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM usuario WHERE id='1'")->andReturn(null);
            $this->assertEquals(
                "Tabla usuario vacia", $this->UserManager->getUserById(1));

        }


           public function testDeleteUser(){
               $this->setupMockito();
               $this->dbManager->shouldReceive('insertQuery')->with("DELETE FROM usuario WHERE id = 1")->andReturn('true');
               $this->assertEquals(
                   "true", $this->UserManager->deleteUser(1));
           }


               public function testDeleteUserNotExisting(){
                   $this->setupMockito();
                   $this->dbManager->shouldReceive('insertQuery')->with("DELETE FROM usuario WHERE id = 1")->andReturn('false');
                   $this->assertEquals(
                       "false", $this->UserManager->deleteUser(1));

               }

                   public function testGetAllUsers(){
                       $this->setupMockito();
                       $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM usuario")->andReturn(array(
                    array(
                        "id"=> "1",
                        "nombre"=> "alumno",
                        "tipo"=> "0",
                        "clave"=> "alumno"
                    )
                       ));
                       $expectedResponse = "[[{\"id\":\"1\",\"name\":\"alumno\",\"type\":\"0\",\"password\":\"alumno\"}]]";
                       $this->assertEquals(
                           $expectedResponse, $this->UserManager->getAllUsers());
                   }


                       public function testGetAllUsersWithNoUsers(){
                           $this->setupMockito();
                           $this->dbManager->shouldReceive('realizeQuery')->with("SELECT * FROM usuario")->andReturn(null);
                           $this->assertEquals(
                               "Tabla usuario vacia", $this->UserManager->getAllUsers());

                       }





}
