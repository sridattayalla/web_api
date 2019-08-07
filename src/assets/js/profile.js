
window.onload = function(){
    var editProfileButton = document.getElementById("edit_profile");
    let cover_button = document.getElementById("cover_button");
    let origin_img_upload_button = document.getElementById("file_upload_button");

    //changing visibility of img upload button
    editProfileButton.onclick = function(){
        cover_button.setAttribute('style', 'display:flex; transition: ease-out 1s;');
    };

    //file upload clicking indirectly
    cover_button.onclick = function(){
        origin_img_upload_button.click();
        cover_button.setAttribute('style', 'display:none;');
    };

    //fetching projects
    let xhr = new XMLHttpRequest();
    let url = "http://localhost/employee/src/php_api/get_projects.php";
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.onreadystatechange = function(){
        if(xhr.readyState===4 && xhr.status==200){
            var json = JSON.parse(xhr.responseText);
            console.log(xhr.status);
            let last_key = 0;
            $(document).ready(function(){
                $('.no_project_div').hide();
                let html_to_append = '';
                let temp_row = '';
                $.each(json.projects, function(key, val){
                    last_key = key;
                    if(key%2==0){
                        html_to_append += temp_row;
                        temp_row = `<div style="display:flex; width=100%;">`;
                        console.log(html_to_append);
                    }
                    console.log(key);
                    temp_row += `<div style="padding: 32px;
                    border: gainsboro solid 1px;
                    border-radius: 10px;display: flex;
                        flex-direction: column;
                        align-items: flex-start;
                        flex:1;">
                    
                        <div >
                            <span style="font-size: 30px;
                            font-weight: 700;">`+
                            val.name
                            +`</span>
                        </div>
                    
                        <div >
                            <span style="font-size: 20px;
                            margin-top: 24px;">`+
                            val.description
                            +`</span>
                        </div>
                    
                        <div >
                            <p style="margin-top: 24px;">`+
                                    val.content
                            +`</p>
                        </div>
                    
                    </div>`;
                    
                    if(key%2==1){
                        temp_row += `</div>`;
                    }
                });
                if(last_key%2==0){
                    temp_row += `</div>`;
                }
                html_to_append += temp_row;
                $('.projects_list_div').append(html_to_append);
              });
        }else{
            var json = JSON.parse(xhr.responseText);
            //if msg is access denied then redirect user to login page
            if(json.message == 'Access denied'){
                window.location.href = "http://localhost/employee/src/login.html";
            }
        }
    };

    let data = JSON.stringify({"jwt": localStorage.getItem('token')});
    xhr.send(data);
};

function add_project(){
    window.location.href = "http://localhost/employee/src/new_project.html";
}

