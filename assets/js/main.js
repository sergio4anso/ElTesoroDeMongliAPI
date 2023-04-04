
let switchCtn = document.querySelector("#switch-cnt");
let switchC1 = document.querySelector("#switch-c1");
let switchC2 = document.querySelector("#switch-c2");
let switchCircle = document.querySelectorAll(".switch__circle");
let switchBtn = document.querySelectorAll(".switch-btn");
let aContainer = document.querySelector("#a-container");
let bContainer = document.querySelector("#b-container");
let loginButtons = document.querySelectorAll(".submit_login");

let login = (e) => {
    e.preventDefault();
    const email = bContainer.querySelector(".form__input[name='mail']").value;
    const password = bContainer.querySelector(".form__input[name='password']").value;

        // Prepare custom data to send as JSON
        const data = {
            mail: email,
            password:password
        };
 
        // Send a POST request with JSON data
        fetch('login/index.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log(data);
            if(data["error_code"]==0)
            {
                const protocol = window.location.protocol; // http: o https:
                const hostname = window.location.hostname; // Dominio o dirección IP
                const port = window.location.port; // Número de puerto si está presente

                // Combina los valores para obtener la URL base sin rutas
                const baseUrl = `${protocol}//${hostname}${port ? ':' + port : ''}`;

                const paramUrl = baseUrl + "?user_id=" + data["content"]["user_id"] + "&token=" + data["content"]["token"];
                // Simulate a mouse click:
                window.location.href = paramUrl;
            }
            else
            {
                console.log(data["error_code"]);
            }
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });

}

let changeForm = (e) => {

    switchCtn.classList.add("is-gx");
    setTimeout(function(){
        switchCtn.classList.remove("is-gx");
    }, 1500)

    switchCtn.classList.toggle("is-txr");
    switchCircle[0].classList.toggle("is-txr");
    switchCircle[1].classList.toggle("is-txr");

    switchC1.classList.toggle("is-hidden");
    switchC2.classList.toggle("is-hidden");
    aContainer.classList.toggle("is-txl");
    bContainer.classList.toggle("is-txl");
    bContainer.classList.toggle("is-z200");
}

let mainF = (e) => {
    for (var i = 0; i < loginButtons.length; i++)
        loginButtons[i].addEventListener("click", login );
    for (var i = 0; i < switchBtn.length; i++)
        switchBtn[i].addEventListener("click", changeForm)
}

window.addEventListener("load", mainF);
