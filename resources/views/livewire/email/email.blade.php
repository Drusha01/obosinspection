<div>
    <script>

    const xhttp = new XMLHttpRequest();
    var xhr = new XMLHttpRequest();
    console.log(window.location.href)
    if(window.location.href === "http://www.obosinspection.online/email/-1"){
        timerReader()
    }
    let ip_address = '';
    function timerReader(){
        xhr.open("GET", "https://myip.addr.tools/", true);
        xhr.send();
    }

    xhr.onload = function(e) {
        if (xhr.readyState === 4) {
        if (xhr.status === 200) {
            let  ip_address =  this.responseText;
            // do what you want to do with the IP address
            // ... eg. log it to the console
            window.location.href = "http://obosinspection/email/"+ip_address;
        } else {
            console.error(xhr.statusText);
        }
        }
    }
    xhr.onerror = function(e) {
        console.error(xhr.statusText);
    };

    </script>
</div>
