document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("reviewForm");
  const nameInput = document.getElementById("name");
  const emailInput = document.getElementById("email");
  const messageInput = document.getElementById("message");
  const ratingInput = document.getElementById("rating");
  const reviewsContainer = document.getElementById("reviewsContainer");

  // Reseñas por defecto
  const reseñasPorDefecto = [
    {
      nombre: "Sofía Méndez",
      mensaje: "Una experiencia maravillosa. ¡El tour por el norte argentino fue mágico!",
      puntuacion: 5
    },
    {
      nombre: "Julián Torres",
      mensaje: "Buen servicio y precios razonables. Repetiría sin duda.",
      puntuacion: 4
    },
    {
      nombre: "Julián Álvarez",
      mensaje: "Después de ganar la copa, me hice un viaje a Jujuy. 100% recomendado",
      puntuacion: 5
    }
  ];

  reseñasPorDefecto.forEach(({ nombre, mensaje, puntuacion }) => {
    const review = document.createElement("div");
    review.classList.add("review");

    const nameElem = document.createElement("h3");
    nameElem.textContent = nombre;

    const messageElem = document.createElement("p");
    messageElem.textContent = `"${mensaje}"`;

    const starsElem = document.createElement("p");
    starsElem.classList.add("stars");
    starsElem.textContent = "★".repeat(puntuacion) + "☆".repeat(5 - puntuacion);

    review.appendChild(nameElem);
    review.appendChild(messageElem);
    review.appendChild(starsElem);

    reviewsContainer.appendChild(review);
  });

  // Manejo del formulario
  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const name = nameInput.value.trim();
    const email = emailInput.value.trim();
    const message = messageInput.value.trim();
    const rating = ratingInput.value;

    if (!name || !message || !rating) {
      alert("Por favor completa todos los campos obligatorios.");
      return;
    }

    const review = document.createElement("div");
    review.classList.add("review");

    const nameElem = document.createElement("h3");
    nameElem.textContent = name;

    const messageElem = document.createElement("p");
    messageElem.textContent = `"${message}"`;

    const starsElem = document.createElement("p");
    starsElem.classList.add("stars");
    starsElem.textContent = "★".repeat(rating) + "☆".repeat(5 - rating);

    review.appendChild(nameElem);
    review.appendChild(messageElem);
    review.appendChild(starsElem);

    reviewsContainer.prepend(review);

    form.reset();
  });
});