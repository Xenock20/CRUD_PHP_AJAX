document.addEventListener("DOMContentLoaded", () => {
  let formulario = document.getElementById("miForm");
  formulario.addEventListener("submit", (e) => {
    e.preventDefault();

    document.getElementById("btnSubmit").innerText = "Agregar Alumno";

    let idInput = document.getElementById("idInput").value;
    let nombreInput = document.getElementById("nombreInput").value;
    let emailInput = document.getElementById("emailInput").value;
    let codigoCursoSelect = document.getElementById("codigoCursoSelect").value;
    document.getElementById("idInput").value = "";
    document.getElementById("nombreInput").value = "";
    document.getElementById("emailInput").value = "";
    document.getElementById("codigoCursoSelect").value = 1;

    let datosForm = "";

    if (idInput) {
      datosForm =
        "nombre=" +
        encodeURIComponent(nombreInput) +
        "&email=" +
        encodeURIComponent(emailInput) +
        "&codigo_curso=" +
        encodeURIComponent(codigoCursoSelect) +
        "&id=" +
        encodeURIComponent(idInput);
    } else {
      datosForm =
        "nombre=" +
        encodeURIComponent(nombreInput) +
        "&email=" +
        encodeURIComponent(emailInput) +
        "&codigo_curso=" +
        encodeURIComponent(codigoCursoSelect);
    }

    let xhr = new XMLHttpRequest();

    xhr.open("POST", "db_conect.php", true);

    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = () => {
      if (xhr.readyState === 4 && xhr.status === 200) {
        let dt = JSON.parse(xhr.responseText);

        generarTabla(dt);
      }
    };

    xhr.send(datosForm);
  });
});
