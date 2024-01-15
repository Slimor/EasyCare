var authToken = localStorage.getItem('authToken');
var userRole = localStorage.getItem('userRole');

console.log("Auth Token:", authToken);
console.log("User Role:", userRole);

  fetch('https://gajownik.cfolks.pl/backend/login', {
    method: 'POST',
    headers: {
          'Authorization': `Bearer ${authToken}`  
      }
  })
  .then(response => response.json())
  .then(data => {
      const userRole = data.role;

      // Wywołaj funkcję choosemenu z odpowiednią rolą
      choosemenu(userRole)
      .then(function(user) {
          // Po otrzymaniu danych użytkownika wykonaj działania zgodnie z jego rolą
          switch (user.role) {
              case 5:
                  fetch('../html/menus/menu_admin.html')
                  .then(response => response.text())
                  .then(data => {
                      document.getElementById('chooser').innerHTML = data;
                  })
                  .catch(error => console.error(error));
                  break;
              case 4:
                  fetch('../html/menus/menu_doc.html')
                  .then(response => response.text())
                  .then(data => {
                      document.getElementById('chooser').innerHTML = data;
                  })
                  .catch(error => console.error(error));
                  break;
              case 3:
                  fetch('../html/menus/menu_nur.html')
                  .then(response => response.text())
                  .then(data => {
                      document.getElementById('chooser').innerHTML = data;
                  })
                  .catch(error => console.error(error));
                  break;
              case 2:
                  fetch('../html/menus/menu_res.html')
                  .then(response => response.text())
                  .then(data => {
                      document.getElementById('chooser').innerHTML = data;
                  })
                  .catch(error => console.error(error));
                  break;
              default:
                  console.log('Nieznana rola');
                  // Obsługa nieznanej roli
          }
      })
      .catch(function(error) {
          console.error('Wystąpił błąd:', error);
      });
  })
  .catch(error => console.error('Błąd podczas pobierania danych użytkownika:', error));
  
  /* Pobieranie danych o roli użytkownika z pliku JSON
    fetch('https://gajownik.cfolks.pl/backend/login')
      .then(response => response.json())
      .then(data => {
        const userRole = data.role_id; // Zakładając, że rola użytkownika jest dostępna jako 'role' w pliku JSON
        
        choosemenu(userRole)
        .then(function(user) {
          // Po otrzymaniu danych użytkownika wykonaj działania zgodnie z jego rolą
          switch (user.role) {
            case 5:
                fetch('../html/menus/menu_admin.html')
                .then(response => response.text())
                .then(data => {
                  document.getElementById('chooser').innerHTML = data;
                })
                .catch(error => console.error(error));
              break;
            case 4:
                fetch('../html/menus/menu_doc.html')
                .then(response => response.text())
                .then(data => {
                  document.getElementById('chooser').innerHTML = data;
                })
                .catch(error => console.error(error));
              break;
            case 3:
                fetch('../html/menus/menu_nur.html')
                .then(response => response.text())
                .then(data => {
                  document.getElementById('chooser').innerHTML = data;
                })
                .catch(error => console.error(error));
              break;
              case 2:
                fetch('../html/menus/menu_res.html')
                .then(response => response.text())
                .then(data => {
                  document.getElementById('chooser').innerHTML = data;
                })
                .catch(error => console.error(error));
              break;

            default:
              console.log('Nieznana rola');
              // Obsługa nieznanej roli
          }
        })
        .catch(function(error) {
          console.error('Wystąpił błąd:', error);
        });
    }); */