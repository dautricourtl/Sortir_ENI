function SearchVisibility() {
    var BarFilter = document.getElementById("BarFilter");
    var BtnFilter = document.getElementById("BtnFilter");
    
    
    BarFilter.style.display = BarFilter.style.display == "block" ?  "none" : "block";

    if (BtnFilter.classList.contains('fa-sort-asc')){
        console.log("test");
        BtnFilter.classList.replace("fa-sort-asc", "fa-sort-desc");
    }else if(BtnFilter.classList.contains('fa-sort-desc')){
        BtnFilter.classList.replace("fa-sort-desc", "fa-sort-asc");
    }


}


let currentTemplateName = "";


var websiteData = {
    City:[],
    Location:[],
    User:[],
    Site:[],
    Event:[],
    State:[],
}

////// CITY /////////


function newCityFormActive(icon){
    newCityForm.style.display =  newCityForm.style.display== "block" ? "none":"block";
    if(newCityForm.style.display == "block"){
        icon.classList.add("cityIconRotate");
    }
    else{
        icon.classList.remove("cityIconRotate");
    }
}

function GetCity(){
    
    newCityForm.style.display = "none";
    axios({
        method: 'get',
        url: '/api/getCity/',
        responseType: 'stream'
      })
        .then(function (response) {
            websiteData.City=response.data;
            console.log(websiteData.City);
            let template = document.getElementById("templateCity");
            document.getElementById("listCity").innerHTML = "";
            for(let i =0; i<websiteData.City.length; i++){
                var clone = template.cloneNode(true);
                clone.getElementsByClassName("nameCity")[0].innerHTML = websiteData.City[i].Name;
                let button = clone.getElementsByClassName("btn")[0];
                button.setAttribute("js-id",websiteData.City[i].Id );
                button.innerHTML = websiteData.City[i].IsActive ? "desactiver":"reactiver";
                if(websiteData.City[i].IsActive ){
                    button.classList.add("btn-danger");
                    button.classList.remove("btn-success");
                }else{
                    button.classList.remove("btn-danger");
                    button.classList.add("btn-success");
                }
                listCity.appendChild(clone);
            }
        });
}

function AddCity() {
    let newCityForm = document.getElementById('newCityForm');
    
    formData = new FormData(newCityForm);
    let newCity = {
        Name:formData.get("city[name]"), 
        ZipCode:formData.get("city[zipCode]")
    }
    axios({
        method: 'post',
        url: '/api/newCity',
        responseType: 'stream',
        data: newCity
      })
      .then(function (response) {
        websiteData.City=response.data;
        let template = document.getElementById("templateCity");
        document.getElementById("listCity").innerHTML = ""; 
        for(let i =0; i<websiteData.City.length; i++){
            var clone = template.cloneNode(true);
            clone.getElementsByClassName("nameCity")[0].innerHTML = websiteData.City[i].Name;
            let button = clone.getElementsByClassName("btn")[0];
            button.setAttribute("js-id",websiteData.City[i].Id );
            button.innerHTML = websiteData.City[i].IsActive ? "desactiver":"reactiver";
            if(websiteData.City[i].IsActive ){
                button.classList.add("btn-danger");
                button.classList.remove("btn-success");
            }else{
                button.classList.remove("btn-danger");
                button.classList.add("btn-success");
            }
            listCity.appendChild(clone);
        }
    });
}


function DeleteCity(id){
    let idToDelete = id.getAttribute("js-id");
    axios({
        method: 'get',
        url: '/api/deleteCity/'+idToDelete,
        responseType: 'stream'
      })
      .then(function (response) {
        websiteData.City=response.data;
        let template = document.getElementById("templateCity");
        document.getElementById("listCity").innerHTML = ""; 
        for(let i =0; i<websiteData.City.length; i++){
            var clone = template.cloneNode(true);
            clone.getElementsByClassName("nameCity")[0].innerHTML = websiteData.City[i].Name;
            let button = clone.getElementsByClassName("btn")[0];
            button.setAttribute("js-id",websiteData.City[i].Id );
            button.innerHTML = websiteData.City[i].IsActive ? "desactiver":"reactiver";
            if(websiteData.City[i].IsActive ){
                button.classList.add("btn-danger");
                button.classList.remove("btn-success");
            }else{
                button.classList.remove("btn-danger");
                button.classList.add("btn-success");
            }
            listCity.appendChild(clone);
        }
    });
}


/////// CITY ////////

/////// LOCATION ////////


function newLocationFormActive(icon){
    newLocationForm.style.display =  newLocationForm.style.display== "block" ? "none":"block";
    if(newLocationForm.style.display == "block"){
        icon.classList.add("LocationIconRotate");
    }
    else{
        icon.classList.remove("LocationIconRotate");
    }
}

function GetLocation(){
    newLocationForm.style.display = "none";
    axios({
        method: 'get',
        url: '/api/getLocation/',
        responseType: 'stream'
      })
        .then(function (response) {
            websiteData.Location=response.data;
            document.getElementById("listLocation").innerHTML = "";
            let template = document.getElementById("templateLocation");
            for(let i =0; i<websiteData.Location.length; i++){
                var clone = template.cloneNode(true);
                clone.getElementsByClassName("nameLocation")[0].innerHTML = websiteData.Location[i].Name;
                clone.getElementsByClassName("cityLocation")[0].innerHTML = websiteData.Location[i].Ville;
                let button = clone.getElementsByClassName("btn")[0];
                button.setAttribute("js-id",websiteData.Location[i].Id );
                button.innerHTML = websiteData.Location[i].IsActive ? "desactiver":"reactiver";
                if(websiteData.Location[i].IsActive ){
                    button.classList.add("btn-danger");
                    button.classList.remove("btn-success");
                }else{
                    button.classList.remove("btn-danger");
                    button.classList.add("btn-success");
                }

                
                listLocation.appendChild(clone);
            }
        });
}

function AddLocation() {
    let newLocationForm = document.getElementById('newLocationForm');
    
    formData = new FormData(newLocationForm);
    let newLocation = {
        Name:formData.get("location[name]"), 
        Adress:formData.get("location[adress]"),
        Latitude:formData.get("location[latitude]"),
        Longitude:formData.get("location[longitude]"),
        CityId:formData.get("location[city]"),
    }
    axios({
        method: 'post',
        url: '/api/newLocation',
        responseType: 'stream',
        data: newLocation
      })
      .then(function (response) {
        websiteData.Location=response.data;
        let template = document.getElementById("templateLocation");
        document.getElementById("listLocation").innerHTML = ""; 
        for(let i =0; i<websiteData.Location.length; i++){
            var clone = template.cloneNode(true);
                clone.getElementsByClassName("nameLocation")[0].innerHTML = websiteData.Location[i].Name;
                clone.getElementsByClassName("cityLocation")[0].innerHTML = websiteData.Location[i].Ville;
                let button = clone.getElementsByClassName("btn")[0];
                button.setAttribute("js-id",websiteData.Location[i].Id );
                button.innerHTML = websiteData.Location[i].IsActive ? "desactiver":"reactiver";
                if(websiteData.Location[i].IsActive ){
                    button.classList.add("btn-danger");
                    button.classList.remove("btn-success");
                }else{
                    button.classList.remove("btn-danger");
                    button.classList.add("btn-success");
                }

                
                listLocation.appendChild(clone);
        }
    });
}


function DeleteLocation(id){
    let idToDelete = id.getAttribute("js-id");
    axios({
        method: 'get',
        url: '/api/deleteLocation/'+idToDelete,
        responseType: 'stream'
      })
      .then(function (response) {
        websiteData.Location=response.data;
        let template = document.getElementById("templateLocation");
        document.getElementById("listLocation").innerHTML = ""; 
        for(let i =0; i<websiteData.Location.length; i++){
            var clone = template.cloneNode(true);
                clone.getElementsByClassName("nameLocation")[0].innerHTML = websiteData.Location[i].Name;
                clone.getElementsByClassName("cityLocation")[0].innerHTML = websiteData.Location[i].Ville;
                let button = clone.getElementsByClassName("btn")[0];
                button.setAttribute("js-id",websiteData.Location[i].Id );
                button.innerHTML = websiteData.Location[i].IsActive ? "desactiver":"reactiver";
                button.setAttribute
                if(websiteData.Location[i].IsActive ){
                    button.classList.add("btn-danger");
                    button.classList.remove("btn-success");
                }else{
                    button.classList.remove("btn-danger");
                    button.classList.add("btn-success");
                }

                
                listLocation.appendChild(clone);
        }
    });
}


/////// LOCATION ////////

/////// USERS ////////

function GetUsers(){
    axios({
        method: 'get',
        url: '/api/getUsers/',
        responseType: 'stream'
      })
        .then(function (response) {
            console.log()
            websiteData.User=response.data;
            document.getElementById("listUsers").innerHTML = "";
            let template = document.getElementById("templateUsers");
            for(let i =0; i<websiteData.User.length; i++){
                var clone = template.cloneNode(true);
                clone.getElementsByClassName("nameUsers")[0].innerHTML = websiteData.User[i].Name;
                clone.getElementsByClassName("cityUsers")[0].innerHTML = websiteData.User[i].Ville;
                let button = clone.getElementsByClassName("btn")[0];
                button.setAttribute("js-id",websiteData.User[i].Id );
                button.innerHTML = websiteData.User[i].IsActive ? "desactiver":"reactiver";
                if(websiteData.User[i].IsActive ){
                    button.classList.add("btn-danger");
                    button.classList.remove("btn-success");
                }else{
                    button.classList.remove("btn-danger");
                    button.classList.add("btn-success");
                }

                
                listUsers.appendChild(clone);
            }
        });
}

////// USERS ///////

/////// SITE ///////

function GetSite(){
    
    axios({
        method: 'get',
        url: '/api/getSite/',
        responseType: 'stream'
      })
        .then(function (response) {
            websiteData.Site=response.data;
            
            let template = document.getElementById("templateSite");
            document.getElementById("listSite").innerHTML = "";
            for(let i =0; i<websiteData.Site.length; i++){
                var clone = template.cloneNode(true);
                clone.getElementsByClassName("nameSite")[0].innerHTML = websiteData.Site[i].Name;
                let button = clone.getElementsByClassName("btn")[0];
                button.setAttribute("js-id",websiteData.Site[i].Id );
                button.innerHTML = websiteData.Site[i].IsActive ? "desactiver":"reactiver";
                if(websiteData.Site[i].IsActive ){
                    button.classList.add("btn-danger");
                    button.classList.remove("btn-success");
                }else{
                    button.classList.remove("btn-danger");
                    button.classList.add("btn-success");
                }
                listSite.appendChild(clone);
            }
        });
}

function AddSite() {
    let newSiteForm = document.getElementById('newformSite');
    
    formData = new FormData(newSiteForm);
    let newSite = {
        Name:formData.get("site[name]")
    }
    axios({
        method: 'post',
        url: '/api/newSite',
        responseType: 'stream',
        data: newSite
      })
      .then(function (response) {
        websiteData.Site=response.data;
        let template = document.getElementById("templateSite");
        document.getElementById("listSite").innerHTML = ""; 
        for(let i =0; i<websiteData.Site.length; i++){
            var clone = template.cloneNode(true);
            clone.getElementsByClassName("nameSite")[0].innerHTML = websiteData.Site[i].Name;
            let button = clone.getElementsByClassName("btn")[0];
            button.setAttribute("js-id",websiteData.Site[i].Id );
            button.innerHTML = websiteData.Site[i].IsActive ? "desactiver":"reactiver";
            if(websiteData.Site[i].IsActive ){
                button.classList.add("btn-danger");
                button.classList.remove("btn-success");
            }else{
                button.classList.remove("btn-danger");
                button.classList.add("btn-success");
            }
            listSite.appendChild(clone);
        }
    });
}


function DeleteSite(id){
    let idToDelete = id.getAttribute("js-id");
    axios({
        method: 'get',
        url: '/api/deleteSite/'+idToDelete,
        responseType: 'stream'
      })
      .then(function (response) {
        websiteData.Site=response.data;
        let template = document.getElementById("templateSite");
        document.getElementById("listSite").innerHTML = ""; 
        for(let i =0; i<websiteData.Site.length; i++){
            var clone = template.cloneNode(true);
            clone.getElementsByClassName("nameSite")[0].innerHTML = websiteData.Site[i].Name;
            let button = clone.getElementsByClassName("btn")[0];
            button.setAttribute("js-id",websiteData.Site[i].Id );
            button.innerHTML = websiteData.Site[i].IsActive ? "desactiver":"reactiver";
            if(websiteData.Site[i].IsActive ){
                button.classList.add("btn-danger");
                button.classList.remove("btn-success");
            }else{
                button.classList.remove("btn-danger");
                button.classList.add("btn-success");
            }
            listSite.appendChild(clone);
        }
    });
}

//////SITE/////
/////STATE/////

function GetState(){
    
    axios({
        method: 'get',
        url: '/api/getState/',
        responseType: 'stream'
      })
        .then(function (response) {
            console.log(response.data);
            websiteData.State=response.data;
            
            let template = document.getElementById("templateState");
            document.getElementById("listState").innerHTML = "";
            for(let i =0; i<websiteData.State.length; i++){
                var clone = template.cloneNode(true);
                clone.getElementsByClassName("nameState")[0].innerHTML = websiteData.State[i].Name;

                listState.appendChild(clone);
            }
        });
}

function AddState() {
    let newStateForm = document.getElementById('newformState');
    
    formData = new FormData(newStateForm);
    let newState = {
        Name:formData.get("state[name]")
    }
    axios({
        method: 'post',
        url: '/api/newState',
        responseType: 'stream',
        data: newState
      })
      .then(function (response) {
        websiteData.State=response.data;
        let template = document.getElementById("templateState");
        document.getElementById("listState").innerHTML = ""; 
        for(let i =0; i<websiteData.State.length; i++){
            var clone = template.cloneNode(true);
            clone.getElementsByClassName("nameState")[0].innerHTML = websiteData.State[i].Name;
            let button = clone.getElementsByClassName("btn")[0];
            button.setAttribute("js-id",websiteData.State[i].Id );
            button.innerHTML = websiteData.State[i].IsActive ? "desactiver":"reactiver";
            if(websiteData.State[i].IsActive ){
                button.classList.add("btn-danger");
                button.classList.remove("btn-success");
            }else{
                button.classList.remove("btn-danger");
                button.classList.add("btn-success");
            }
            listState.appendChild(clone);
        }
    });
}


function DeleteState(id){
    let idToDelete = id.getAttribute("js-id");
    axios({
        method: 'get',
        url: '/api/deleteState/'+idToDelete,
        responseType: 'stream'
      })
      .then(function (response) {
        websiteData.State=response.data;
        let template = document.getElementById("templateState");
        document.getElementById("listState").innerHTML = ""; 
        for(let i =0; i<websiteData.State.length; i++){
            var clone = template.cloneNode(true);
            clone.getElementsByClassName("nameState")[0].innerHTML = websiteData.State[i].Name;
            let button = clone.getElementsByClassName("btn")[0];
            button.setAttribute("js-id",websiteData.State[i].Id );
            button.innerHTML = websiteData.State[i].IsActive ? "desactiver":"reactiver";
            if(websiteData.State[i].IsActive ){
                button.classList.add("btn-danger");
                button.classList.remove("btn-success");
            }else{
                button.classList.remove("btn-danger");
                button.classList.add("btn-success");
            }
            listState.appendChild(clone);
        }
    });
}




var templates = 
    [
        {Id : 0, Name:"City"},
        {Id : 1, Name:"Location"},
        {Id : 2, Name:"User"},
        {Id : 3, Name:"Site"},
        {Id : 4, Name:"Event"},
        {Id : 5, Name:"State"},
    ];

SwitchTemplate(1);

function SwitchTemplate(templateId)
{
    switch(templateId){
        case 0:
            GetCity();
        break;
        case 1:
            GetLocation();
        break;
        case 2:
            GetUsers();
        break;
        case 3:
            GetSite();
        break;
        case 4:
            
        break;
        case 5:
            GetState();
        break;
    }
    let containers = document.getElementsByClassName("categoryContainer");
    for(let i =0; i<containers.length; i++){
        containers[i].classList.remove("d-block");
        containers[i].classList.add("d-none");
    }
    currentTemplateName = templates.filter(c=>c.Id == templateId)[0];
    document.getElementById(`${currentTemplateName.Name}Container`).classList.add("d-block");
}
