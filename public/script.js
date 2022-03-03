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