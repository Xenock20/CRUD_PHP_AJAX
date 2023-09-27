<?php
$servername = "mysql";
$username = "root";
$password = "rootadmin";
$dbname = "IAC";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$sql = "SELECT * FROM t_alumnos_del_curso";
$result = $conn->query($sql);

$datos = array();
while ($row = $result->fetch_assoc()) {
    $datos[] = $row;
}

$datosJSON = json_encode($datos);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>IAC</title>
</head>

<body>
    <div class="container">
        <div class="form-cont">
            <form id="miForm">
                <input type="hidden" id="idInput" name="id">

                <label for="nombreInput">Nombre:</label>
                <input type="text" id="nombreInput" name="nombre" placeholder="Ingresa un nombre" required>

                <label for="emailInput">Correo Electrónico:</label>
                <input type="email" id="emailInput" name="email" placeholder="Ingresa un correo electrónico" required>

                <label for="codigoCursoSelect">Código del Curso:</label>
                <select id="codigoCursoSelect" name="codigo_curso">
                    <option value="1">C++</option>
                    <option value="2">PHP</option>
                    <option value="3">Python</option>
                </select>


                <button type="submit" id="btnSubmit">
                    Agregar Alumno
                </button>
            </form>
        </div>

        <div class="modal-modif">

        </div>

        <div id="tablaDatos"></div>
    </div>
    <script>
        let datosJSON = <?php echo $datosJSON; ?>;

        const btnModif = (id) => {
            const btn = document.createElement('button')
            btn.textContent = "Edit"
            btn.setAttribute("id", id)
            btn.setAttribute("class", "mod")
            return btn;
        }

        const btnDel = (id) => {
            const btn = document.createElement('button')
            btn.textContent = "Eliminar"
            btn.setAttribute("id", id)
            btn.setAttribute("class", "del")
            return btn;
        }

        function generarTabla(datos) {
            let tabla = "<table>";
            tabla += "<tr><th>ID</th><th>Nombre</th><th>Correo Electrónico</th><th>Codigo Curso</th></th><th>Acciones</th></tr>";

            for (let i = 0; i < datos.length; i++) {
                tabla += "<tr>";
                for (let j = 0; j < Object.keys(datos[i]).length; j++) {
                    const td = document.createElement("td");
                    td.innerText = Object.values(datos[i])[j]
                    tabla += td.outerHTML;
                }
                const tdBtn = document.createElement("td");
                tdBtn.appendChild(btnModif(datos[i].id))
                tdBtn.appendChild(btnDel(datos[i].id))
                tabla += tdBtn.outerHTML;
                tabla += "</tr>";
            }

            setTimeout(() => {
                const btnsMod = document.getElementsByClassName("mod")
                const btnsDel = document.getElementsByClassName("del")

                for (let l = 0; l < btnsMod.length; l++) {
                    btnsMod[l].addEventListener("click", (e) => {
                        const td = btnsMod[l].parentNode;
                        const tr = td.parentNode;
                        document.getElementById("idInput").value = tr.childNodes[0].textContent;
                        document.getElementById("nombreInput").value = tr.childNodes[1].textContent;
                        document.getElementById("emailInput").value = tr.childNodes[2].textContent;
                        document.getElementById("codigoCursoSelect").value = tr.childNodes[3].textContent;
                        document.getElementById("btnSubmit").innerText = "Editar Alumno";
                    });
                    btnsDel[l].addEventListener("click", (e) => {
                        const td = btnsDel[l].parentNode;
                        const tr = td.parentNode;
                        deleteData(tr.childNodes[0].textContent);
                    })
                }
            }, 100);


            tabla += "</table>";
            document.getElementById("tablaDatos").innerHTML = tabla;
        }

        const deleteData = (id) => {
            let xhr = new XMLHttpRequest();

            xhr.open('DELETE', 'db_conect.php', true);

            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    let dt = JSON.parse(xhr.responseText);

                    generarTabla(dt);
                }
            };

            // Envía la solicitud con el ID en el cuerpo
            xhr.send('id=' + id);
        }

        generarTabla(datosJSON);
    </script>

    <script src="./main.js"></script>
</body>

</html>