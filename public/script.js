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
    Users:[],
    Site:[],
    Event:[],
    State:[],
}


function AddCustom()
{
    
    let newState = {
        Name:document.getElementById("NameInput").value,
        Latitude:document.getElementById("LatitudeInput").value,
        Longitude:document.getElementById("LongitudeInput").value,
        Adress:document.getElementById("AdressInput").value,
        CityId:document.getElementById("CitySelect").value
    }
    let mandatoryFields = {
        City:["Name"],
        Location:["Name","Latitude","Longitude","Adress","CityId"],
        Site:["Name"],
        State:["Name"]
    };
    for(let i =0; i<Object.entries(newState).length;i++)
    {
        if(mandatoryFields[currentTemplateName].includes(Object.entries(newState)[i][0])){
            if(newState[Object.entries(newState)[i][0]] != ""){
                axios({
                        method: 'post',
                        url: '/api/new'+currentTemplateName,
                        responseType: 'stream',
                        data:JSON.stringify(newState)
                      })
                        .then(function (response) {
                            RefreshView(response);
                            document.getElementById("NameInput").value ="";
                            document.getElementById("LatitudeInput").value ="";
                            document.getElementById("AdressInput").value = "";
                            document.getElementById("LongitudeInput").value ="";
                        });
            }else{
                console.log(Object.entries(newState)[i][0] + " est manquant");
            }
        }
    }
}

function GetCustom(urlForce){
    let getUrl = urlForce == null || urlForce == undefined ?  currentTemplateName : urlForce;
    axios({
        method: 'get',
        url: '/api/get'+getUrl+'/',
        responseType: 'stream'
      })
        .then(function (response) {
            RefreshView(response, urlForce);
        });
}

function DeleteCustom(id){
    let idToDelete = id.getAttribute("js-id");
    axios({
        method: 'get',
        url: '/api/delete'+currentTemplateName+'/'+idToDelete,
        responseType: 'stream'
      })
        .then(function (response) {
            RefreshView(response, "");
        });
}

function RefreshView(response, forcedUrl){
    currentTemplateName = forcedUrl == null || forcedUrl == undefined || forcedUrl == "" ? currentTemplateName : forcedUrl;
    let properties = ["Name","IsActive","Pseudo","Surname","Email"]
    window["websiteData"][currentTemplateName]=response.data;
    let template = document.getElementById("templateItem");
    document.getElementById("list"+currentTemplateName).innerHTML = "";
    for(let i =0; i<window["websiteData"][currentTemplateName].length; i++){
        var clone = template.cloneNode(true);
        let propertiesFilter = Object.entries(response.data[i]);
        var propertiesFilterPair = propertiesFilter.filter(c=> properties.includes(c[0])).filter(c=> c[0]!= "IsActive");
        for(let j = 0; j<propertiesFilterPair.length; j++){
            let td = document.createElement("div");
            td.innerHTML = propertiesFilterPair[j][1];
            clone.prepend(td);
        }
        let button = clone.getElementsByClassName("btn")[0];
        button.setAttribute("js-id",window["websiteData"][currentTemplateName][i].Id );
        button.innerHTML = window["websiteData"][currentTemplateName][i]["IsActive"] ? "desactiver":"reactiver";
        if(window["websiteData"][currentTemplateName][i]["IsActive"] ){
            button.classList.add("btn-danger");
            button.classList.remove("btn-success");
        }else{
            button.classList.remove("btn-danger");
            button.classList.add("btn-success");
        }
        document.getElementById("list"+currentTemplateName).appendChild(clone);
    }
    document.getElementById("CitySelect").innerHTML = websiteData.City.map(c=> "<option value="+c.Id+">"+c.Name+"</option>")
}

function SearchUsers(input){
    let template = document.getElementById("templateUsers");
    let listUser=document.getElementById("listUsers");
    let data = websiteData.User;
    if(input != ""){
        let selection = websiteData.User.filter(c=> c.Pseudo.includes(input) || c.Name.includes(input) || c.Surname.includes(input) );
        document.getElementById("listUsers").innerHTML = ""; 
        data = selection;
        
    }else{
        document.getElementById("listUsers").innerHTML = ""; 
        data = websiteData.User;
    }
    for(let i =0; i<data.length; i++){
        var clone = template.cloneNode(true);
        clone.getElementsByClassName("nameUser")[0].innerHTML = data[i].Name;
        clone.getElementsByClassName("surnameUser")[0].innerHTML = data[i].Surname;
        clone.getElementsByClassName("pseudoUser")[0].innerHTML = data[i].Pseudo;
        let button = clone.getElementsByClassName("btn")[0];
        button.setAttribute("js-id",data[i].Id );
        button.innerHTML = data[i].IsActive ? "desactiver":"reactiver";
        if(data[i].IsActive ){
            button.classList.add("btn-danger");
            button.classList.remove("btn-success");
        }else{
            button.classList.remove("btn-danger");
            button.classList.add("btn-success");
        }
        listUser.appendChild(clone);
    }
    
}

var templates = 
    [
        {Id : 0, Name:"City"},
        {Id : 1, Name:"Location"},
        {Id : 2, Name:"Users"},
        {Id : 3, Name:"Site"},
        {Id : 4, Name:"Event"},
        {Id : 5, Name:"State"},
    ];

SwitchTemplate(2);

function SwitchTemplate(templateId)
{
    if(templateId == 1){
        GetCustom("City");
    }
    currentTemplateName = templates.filter(c=>c.Id == templateId)[0].Name;
    GetCustom(currentTemplateName);
    let containers = document.getElementsByClassName("categoryContainer");
    for(let i =0; i<containers.length; i++){
        containers[i].classList.remove("d-block");
        containers[i].classList.add("d-none");
    }
   
    currentTemplateName = templates.filter(c=>c.Id == templateId)[0].Name;
    document.getElementById(`${currentTemplateName}Container`).classList.add("d-block");

    
    

}
