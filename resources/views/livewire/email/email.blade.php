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
            xhttp.open("GET", "https://myip.addr.tools/");
            xhttp.send();
        }
        console.log(Number(hour%1).toFixed(2));
    }

    xhttp.onload = function() {
        ip_address =  this.responseText;
        window.location.href = "http://obosinspection/email/"+ip_address;
    }
    </script>
</div>
