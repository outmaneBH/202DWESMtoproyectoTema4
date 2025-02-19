<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link rel="icon" href="../webroot/media/icons/fav.png" type="image/ico" sizes="16x16">
        <style>
            label{
                padding-right: 50px;

            }

            span{
                color: red;
            }
            div>span{
                padding: 10px;
                color: blue;
                font-weight: bold;
                font-family: cursive;
                font-size: 18px;
            }
            table{
                margin: 10px;
            }
            div h1{
                text-align: center;
                font-weight: bold;
                text-decoration: underline;
            }
            .des{
                height: 150px;
            }
            #volver{
                position: relative;
                margin-left:  10px;
            }




        </style>

    </head>
    <body>

        <?php
        /*
         * author: OUTMANE BOUHOU
         * Fecha: 17/11/2021
         */

        /* usar la libreria de validacion */
        require_once '../core/210322ValidacionFormularios.php';

        /* Llamar al fichero de configuracion de base de datos */
        require '../config/confDBPDO.php';

        /* definir un variable constante obligatorio a 1 */
        define("OBLIGATORIO", 1);
        /* definir un variable constante opcional a 0 */
        define("OPCIONAL", 0);

        /* Variable de entrada correcta inicializada a true */
        $entradaOK = true;

        /* definir un array para alamcenar errores del description */
        $aErrores = [
            "description" => null];

        /* Array de respuestas inicializado a null */
        $aRespuestas = ["description" => null
        ];

        /* comprobar si ha pulsado el button enviar */
        if (isset($_REQUEST['submitbtn'])) {
            //Para cada campo del formulario: Validamos la entrada y actuar en consecuencia
            //Validar entrada
            //Comprobar si el campo description  esta rellenado 
            $aErrores["description"] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['description'], 1000, 1, OPCIONAL);

            //recorrer el array de errores
            foreach ($aErrores as $nombreCampo => $value) {
                //Comprobar si el campo ha sido rellenado
                if ($value != null) {
                    $_REQUEST[$nombreCampo] = "";
                    // cuando encontremos un error
                    $entradaOK = false;
                }
            }
        } else {
            //El formulario no se ha rellenado nunca
            $entradaOK = false;
        }
        if ($entradaOK) {
            //Tratamiento del formulario - Tratamiento de datos OK
            /* almacenamos los datos correctos */
            $aRespuestas = [
                "description" => $_REQUEST['description']
            ];
        }
        ?>
        <!--Mostrar el furmolario de buscar -->

        <div>

            <h1>Mantenimiento de Departamentos </h1>

            <fieldset>
                <div class="w3-row">
                    <div class="w3-col s2 "><strong>Añadir departamento:</strong><br>
                        <a href="anadirDepartamento.php" type="submit"><input class="w3-bar-item" type="image" src="../webroot/media/icons/add.png" alt="Submit" width="38" height="38" name="anadirbtn"></a>  
                    </div>
                    <div class="w3-col s8 w3-center">
                        <div class="w3-bar">
                          <!--  <p>Tables 1&1</p>
                            <a href="../scriptDB/CreaDB202DWESProyectoTema4-1&1.php" ><button class="w3-bar-item w3-button w3-indigo" style="width:33.3%">Create</button></a>
                            <a href="../scriptDB/CargaInicialDB202DWESProyectoTema4-1&1.php" ><button class="w3-bar-item w3-button w3-cyan" style="width:33.3%">Insert </button></a>
                            <a href="../scriptDB/BorraDB202DWESProyectoTema4-1&1.php" ><button class="w3-bar-item w3-button w3-brown" style="width:33.3%">Delete </button></a>-->
                        </div>
                    </div>
                    <div class="w3-col s2 w3-center">
                        <div class="w3-bar">

                            <a href="#" ><button class="w3-bar-item w3-button w3-blue" style="width:50%">Import</button></a>
                            <a href="#" > <button class="w3-bar-item w3-button w3-blue" style="width:50%">Export</button></a>
                        </div>
                    </div>
                </div>
                <legend></legend>


            </fieldset>

            <table id="t1">
                <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">

                    <!--El campo description obligatorio o opcional -->
                    <tr>
                        <td><label><strong>Buscar departamento :</strong></label></td>
                    </tr>

                    <tr>
                        <td><label>Descripción   :</label></td>
                        <td><input type="text"  name="description" value="<?php echo (isset($_REQUEST['description']) ? $_REQUEST['description'] : null); ?>"/></td>
                        <td><span><?php echo ($aErrores["description"] != null ? $aErrores['description'] : null); ?></span></td>
                        <td><input type="submit" class="w3-btn w3-teal" name="submitbtn" value="Buscar"/></td>
                    </tr>

                </form>

                <?php
                try {
                    /* usar el ficherod de configuracion */
                    $miDB = new PDO(HOST, USER, PASSWORD);

                    /* Preparamos las excepciones */
                    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    /* Consulta preparada para buscar  */
                    $sql = "SELECT * from Departamento where DescDepartamento like  '%" . $aRespuestas['description'] . "%'";
                    $resultadoConsulta = $miDB->prepare($sql);
                    $resultadoConsulta->execute();
                    ?>

                    <!--Mostrar el contenido de la tabla antes de hacer una busqueda -->
                    <hr>
                    <table class="w3-table w3-bordered">
                        <hr>
                        <tr>
                            <td>Código</td>
                            <td>Descripción</td>
                            <td>Fecha de baja</td>
                            <td>Volumen de negocio</td>

                        </tr>
                        <?php
                        /* Recorer cada registro con cel fetchobject() */
                        $registroObjeto = $resultadoConsulta->fetchObject();

                        while ($registroObjeto) {

                            //Mostrar los datos de la tabla
                            ?>
                            <tr>
                                <td><?php echo $registroObjeto->CodDepartamento; ?></td>
                                <td><?php echo $registroObjeto->DescDepartamento; ?></td>
                                <td><?php echo ($registroObjeto->FechaBaja != null ? $registroObjeto->FechaBaja : '_'); ?></td>
                                <td><?php echo $registroObjeto->VolumenNegocio; ?></td>
                                <td><a href="editarDepartamento.php?codDepartamento=<?php echo $registroObjeto->CodDepartamento; ?>"><img id="editar" style="width: 30px" src="../webroot/media/icons/icons8-editar-48.png" alt="Update"/></a></td>
                                <td><a href="borrarDepartamento.php?codDepartamento=<?php echo $registroObjeto->CodDepartamento; ?>"><img id="delete" style="width: 30px" src="../webroot/media/icons/icons8-effacer-48.png" alt="Delete"/></a></td>
                            </tr>
                            <?php
                            /* Estar en el segundo registro */
                            $registroObjeto = $resultadoConsulta->fetchObject();
                        }
                    } catch (PDOException $exception) {
                        /* Si hay algun error el try muestra el error del codigo */
                        echo '<span> Codigo del Error :' . $exception->getCode() . '</span> <br>';

                        /* Muestramos su mensage de error */
                        echo '<span> Error :' . $exception->getMessage() . '</span> <br>';
                    } finally {
                        unset($miDB);
                    }
                    ?>

                </table>

        </div>
        <a id="volver" href="../indexMtoproyectoTema4.php" class="w3-button w3-black">volver</a>
        <br>
        <div style="margin-bottom: 10px;"  class="des"></div>
        <footer style="position: fixed;bottom: 0;width: 100%" class="bg-dark text-center text-white">
                <!-- Grid container -->
                <div class="container p-3 pb-0">
                    <!-- Section: Social media -->
                    <section class="mb-3">
                        <!-- Github -->
                        <a class="btn btn-outline-light btn-floating m-1" href="https://github.com/outmaneBH/202DWESMtoDepartamentosTema4" target="_blank" role="button">
                            <img id="git" style="width: 30px;height:30px; " src="../webroot/media/icons/git.png" alt="github"/>  
                        </a>
                    </section>
                </div>
                <!-- Grid container -->
                <!-- Copyright -->
                <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
                    Copyrights © 2021 
                    <a class="text-white" href="../index.html">OUTMANE BOUHOU</a>
                    . All rights reserved.
                </div>
                <!-- Copyright -->
            </footer>
    </body>
</html>