      
function save(){
    let name_input = document.getElementById('name_input').value;
    let descreption = document.getElementById('descreption_input').value;
    let content = document.getElementById('content_input').value;

    if(name_input.length==0){
        document.getElementsByClassName('err_msg_hide')[0].innerHTML = "Please enter valid project name";
        document.getElementsByClassName('err_msg_hide')[0].setAttribute("style", "display:block;");
    }

    else if(descreption.length===0){
        document.getElementsByClassName('err_msg_hide')[0].innerHTML = "Please enter valid description";
        document.getElementsByClassName('err_msg_hide')[0].setAttribute("style", "display:block;");
    }

    else if(content.length===0){
        document.getElementsByClassName('err_msg_hide')[0].innerHTML = "Please enter valid content";
        document.getElementsByClassName('err_msg_hide')[0].setAttribute("style", "display:block;");
    }

    else{
        document.getElementsByClassName('err_msg_hide')[0].setAttribute("style", "display:none;");

        let xhr = new XMLHttpRequest();
        let url = "http://localhost/employee/src/php_api/add_project.php";
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-Type", "application/json");

        xhr.onreadystatechange = function(){
            if(xhr.readyState===4 && xhr.status==200){
                console.log(xhr.status);
                window.location.href = "http://localhost/employee/src/profile.html";
            }else{
                var json = JSON.parse(xhr.responseText);
                document.getElementsByClassName('err_msg_hide')[0].innerHTML = json.message;
                document.getElementsByClassName('err_msg_hide')[0].setAttribute("style", "display:block;");
                console.log(json.message);
                //if msg is access denied then redirect user to login page
                if(json.message == 'Access denied'){
                    window.location.href = "http://localhost/employee/src/login.html";
                }
            }
        }

        let data = JSON.stringify({"jwt": localStorage.getItem('token'),"name": name_input, "description": descreption, "content": content});
        xhr.send(data);
    }
}

$(document).ready(function(){
    
});