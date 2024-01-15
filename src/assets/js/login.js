$(document).ready(function () {
  $("#loginButton").click(function () {
    var login = $("#login").val();
    var password = $("#password").val();

    $.ajax({
      url: "https://gajownik.cfolks.pl/backend/login",
      type: "POST",
      data: {
          login: login,
          password: password
      },
      success: function (response) {
        // Sprawdzenie czy autentykacja zakończyła się sukcesem
        if (response.success) {
            // Przypisanie wartości do zmiennych
            var token = response.data.token;
            var role = response.data.role;

            // Przykładowe użycie zmiennych
            console.log("Token:", token);
            console.log("Rola:", role);

            // Tutaj możesz wykonać dodatkowe operacje związane z zalogowaniem
            // np. przekierowanie do innej strony
            window.location.href = "index.html";
        } else {
            // Obsługa sytuacji, gdy autentykacja nie powiodła się
            console.log("Błąd logowania:", response.message);
        }
    },
    error: function (error) {
        // Obsługa błędu autentykacji
        console.log("Błąd logowania:", error);
    }
    });
  });
});
