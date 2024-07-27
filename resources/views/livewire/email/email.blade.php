<div>
    <script>

    const xhttp = new XMLHttpRequest();
    document.addEventListener("DOMContentLoaded", function(event) {
        setInterval(timerReader, 1000);
    });

    let ip_address = '';
    const d = new Date();
    let hour = d.getTime()/3600000;
    let prev_hour = Number(hour%1).toFixed(2);
    let valid = false;
    function timerReader(){
        const d = new Date();
        let hour = d.getTime()/3600000;
        let current_hour = Number(hour%1).toFixed(2);
        if(prev_hour != current_hour){
            valid = true;
        }
        if( current_hour == .07 &&  valid ){
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "http://httpbin.org/ip", true);
            xhttp.send();
        }
        console.log(Number(hour%1).toFixed(2));
    }

 
    xhr.onload = function(e) {
        if (xhr.readyState === 4) {
        if (xhr.status === 200) {
            let ip_address = JSON.parse(xhr.responseText).origin;
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
