const API_ENDPOINT = "http://34.203.197.208/api.php";

document.getElementById("hotelForm").addEventListener("submit", function(event) {
    event.preventDefault();
    let hotelData = {
        nombre: document.getElementById("nombreHotel").value,
        direccion: document.getElementById("direccionHotel").value,
        ciudad: document.getElementById("ciudadHotel").value,
        NIT: document.getElementById("nitHotel").value,
        numeroHabitaciones: document.getElementById("numHabitaciones").value
    };
    fetch(`${API_ENDPOINT}?type=hotel`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(hotelData)
    }).then(response => response.json())
    .then(data => {
        alert('Hotel agregado correctamente');
        updateHotelList();
    });
});

document.getElementById("habitacionForm").addEventListener("submit", function(event) {
    event.preventDefault();
    const hotelId = document.getElementById("hotelId").value;

    Promise.all([getHotelInfo(hotelId), getHabitacionCount(hotelId)])
    .then(results => {
        const hotel = results[0];
        const habitacionCount = results[1];

        if (habitacionCount >= hotel.numeroHabitaciones) {
            alert('Has alcanzado el límite máximo de tipos de habitación para este hotel.');
            return;
        }

        let habitacionData = {
            tipo: document.getElementById("tipoHabitacion").value,
            acomodacion: document.getElementById("acomodacion").value,
            cantidad: document.getElementById("cantidad").value,
            hotel_id: hotelId
        };

        fetch(`${API_ENDPOINT}?type=habitacion`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(habitacionData)
        }).then(response => response.json())
        .then(data => {
            alert('Tipo de habitación agregado correctamente');
        });

    }).catch(error => {
        console.error("Hubo un error al intentar validar la cantidad de tipos de habitación:", error);
    });
});

function updateHotelList() {
    fetch(`${API_ENDPOINT}?type=hotel`)
    .then(response => response.json())
    .then(data => {
        let select = document.getElementById("hotelId");
        select.innerHTML = "";
        data.forEach(hotel => {
            let option = document.createElement("option");
            option.value = hotel.id;
            option.text = hotel.nombre;
            select.appendChild(option);
        });
    });
}

// Código para listar hoteles y habitaciones cuando se cambia a la pestaña "Listar"
document.getElementById("listar-tab").addEventListener("click", function() {
    fetch(`${API_ENDPOINT}?type=hotel`)
    .then(response => response.json())
    .then(data => {
        let list = document.getElementById("hotelList");
        list.innerHTML = "";
        data.forEach(hotel => {
            let item = document.createElement("li");
            item.textContent = hotel.nombre + " (" + hotel.ciudad + ")";
            list.appendChild(item);
        });
    });

    fetch(`${API_ENDPOINT}?type=habitacion`)
    .then(response => response.json())
    .then(data => {
        let list = document.getElementById("habitacionList");
        list.innerHTML = "";
        data.forEach(habitacion => {
            let item = document.createElement("li");
            item.textContent = habitacion.tipo + " - " + habitacion.acomodacion;
            list.appendChild(item);
        });
    });
});

function getHotelInfo(hotelId) {
    return fetch(`${API_ENDPOINT}?type=hotel&id=${hotelId}`)
    .then(response => response.json());
}

function getHabitacionCount(hotelId) {
    return fetch(`${API_ENDPOINT}?type=habitacion&hotel_id=${hotelId}`)
    .then(response => response.json())
    .then(data => data.length); // Suponiendo que te devuelve un array de habitaciones
}

// Llamar a la función al cargar el documento para tener la lista de hoteles
updateHotelList();
