'use strict'

function selecionado(){
    if(document.getElementById("arq").value!='')
        document.form1.btupload.className = "bt2";
    else
        document.form1.btupload.className = "bt";
}

window.addEventListener('load', function(){
    document.getElementById('arq').addEventListener("change", selecionado);
    //document.getElementById('noup').addEventListener("click", function(){ window.location.href='managefiles.php'; });
});

//console.log();