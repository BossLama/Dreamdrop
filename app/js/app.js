document.addEventListener("DOMContentLoaded", function() {
    console.log("DOM fully loaded and parsed");
    loadDreamdrops();
});


function togglePopup()
{
    console.log("togglePopup() called");
    var popup = document.getElementById("popup-wrapper");
    if (popup.classList.contains("hidden"))
    {
        popup.classList.remove("hidden");
    }
    else
    {
        popup.classList.add("hidden");
    }
}

function createDreamdrop()
{
    let formdata = new FormData(document.getElementById("form_entry"));
    fetch("./app/php/create_dreamdrop.php", {
        method: "POST",
        body: formdata,
    })
    .then((response) => response.text())
    .then((data) => {
        console.log(data);
        data = JSON.parse(data);
        if (data.status == "success")
        {
            alert("Dreamdrop erstellt!");
            window.location.reload();
        }
        else
        {
            alert("Error: " + data.message);
        }
    })
}

function loadDreamdrops()
{
    fetch("./app/php/get_dreamdrops.php")
    .then((response) => response.json())
    .then((data) => {
        if(data.status != "success")
        {
            alert("Error: " + data.message);
            return;
        }

        let dreamdrops = data.data;
        let dreamdropList = document.getElementById("dreamdrop-list");
        dreamdropList.innerHTML = "";

        if (dreamdrops.length == 0)
        {
            let listItem = document.createElement("div");
            listItem.classList.add("entry");
            listItem.innerHTML = `<p class="name">Keine Dreamdrops gefunden</p>`;
            dreamdropList.appendChild(listItem);
            return;
        }

        dreamdrops.forEach((dreamdrop) => {
            let listItem = document.createElement("div");
            listItem.classList.add("entry");

            let author = dreamdrop.author;
            let name = dreamdrop.title;
            let price = dreamdrop.price || "0€";
            let location = dreamdrop.location;
            let url = dreamdrop.url || "#";
            let created_at = dreamdrop.created_at;

            // Format created_at to dd.mm.yyyy
            let date = new Date(created_at);
            let day = String(date.getDate()).padStart(2, '0');
            let month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
            let year = date.getFullYear();
            let formattedDate = `${day}.${month}.${year}`;

            let html = `<img src="./app/icons/icon_accent_stars.svg" alt="" class="icon">`;
            html += `<p class="name">Dreamdrop von ${author} am ${formattedDate}</p>`;
            html += `<p class="drop">${author} hat den Dreamdrop <span>${name}</span> für <span>${price}</span> in <span>${location}</span> erstellt.</p>`;

            if (url != "#")
            {
                html += `<a href="${url}" target="_blank"><button class="button_primary" style="width: 100%; margin-top: 1rem; justify-content: center;"><img src="./app/icons/icon_light_beach.svg" alt="beach"> Link öffnen</button></a>`;
            }
            
            listItem.innerHTML = html;
            dreamdropList.appendChild(listItem);
        });


    })
    .catch((error) => console.error("Error loading dreamdrops:", error));
}