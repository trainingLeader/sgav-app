import { postData,putData,opc } from '/Apis/country-api.js';
let myform = document.querySelector("#frmDataCountry");
document.addEventListener('DOMContentLoaded',(e)=>{

});
myform.addEventListener("submit", async (e) => {
    e.preventDefault();
    let myHeader = new Headers({"Content-Type": "application/json; charset:utf8"});
    let data = Object.fromEntries(new FormData(e.target));
    let config = {
        method: "POST",
        headers: myHeader,
        body: JSON.stringify(data)
    };
    postData(data).then(r=>{
        document.querySelector("pre").innerHTML = r;
    });
})
const Registrar = (id)=>{

}
export{
    Registrar
}