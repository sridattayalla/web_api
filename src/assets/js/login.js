function login(event){
    let email_entered = document.getElementById('email_input').value;
    let password_entered1 = document.getElementById('password_input').value;
    //console.log(email_entered, name_entered, password_entered1, password_entered2);

    if(email_entered.length==0 || !email_entered.match(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/)){
        document.getElementsByClassName('err_msg_hide')[0].innerHTML = "Please enter valid email";
        document.getElementsByClassName('err_msg_hide')[0].setAttribute("style", "display:block;");
    }
    else if(password_entered1.length===0){
        document.getElementsByClassName('err_msg_hide')[0].innerHTML = "Please enter valid password";
        document.getElementsByClassName('err_msg_hide')[0].setAttribute("style", "display:block;");
        document.getElementById('password_input').focus();
    }
    else{
        document.getElementsByClassName('err_msg_hide')[0].setAttribute("style", "display:none;");

        //request and response to php api
        let xhr = new XMLHttpRequest();
        let url = "http://localhost/employee/src/php_api/login.php";
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var json = JSON.parse(xhr.responseText);
                console.log(json.message);

                localStorage.setItem('token', json.jwt);
                document.getElementsByClassName('err_msg_hide')[0].innerHTML = "Successful";
                document.getElementsByClassName('err_msg_hide')[0].setAttribute("style", "background-color: rgba(0, 255, 0, 0.2);color: green;display: block;");
            }else{
                /*console.log(xhr.status);*/
                var json = JSON.parse(xhr.responseText);
                document.getElementsByClassName('err_msg_hide')[0].innerHTML = json.message;
                document.getElementsByClassName('err_msg_hide')[0].setAttribute("style", "display:block;");
            }
        };

        let data = JSON.stringify({"email": email_entered, "password": password_entered1});
        xhr.send(data);
    }
}