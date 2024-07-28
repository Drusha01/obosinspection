<div>
    <script>

    const xhttp = new XMLHttpRequest();
    var xhr = new XMLHttpRequest();
    document.addEventListener("DOMContentLoaded", function(event) {
        setInterval(timerReader, 700);
    });

    let ip_address = '';
    const d = new Date();
    let hour = d.getTime()/3600000;
    let prev_hour = Number(hour%1).toFixed(2);
    let valid = true;
    function timerReader(){
        const d = new Date();
        let hour = d.getTime()/3600000;
        let current_hour = Number(hour%1).toFixed(2);
        if( (current_hour == .50 || current_hour == .99  ) &&  valid ){
            xhr.open("GET", "https://myip.addr.tools/", true);
            xhr.send();
            valid = false;
        }
        console.log(current_hour);
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
