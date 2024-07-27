<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Email IP Address</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 60px auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            max-width: 150px;
        }

        h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }
        h3 {
            color: #333;
            font-size: 18px;
            margin-bottom: 10px;
        }


        p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .verification-code {
            text-align: center;
            font-size: 32px;
            font-weight: bold;
            color: black;
            margin-top: 20px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            color: #777;
            font-size: 14px;
        }
        .div-link{
            text-align:center;
            margin-left:10px;
        }
        .button-link{
            text-decoration: none; 
        }

        .button-accept{
            margin:5px;
            padding:10px;
            font-size:16px;
            background-color: #04AA6D;
            border: 2px solid #04AA6D;
            color: white;
            border-radius:5px;
        }
        .button-accept:hover {
            background-color: white;
            border: 2px solid #04AA6D;
            color: #04AA6D;
        }
        .button-reject{
            margin:5px;
            padding:10px;
            font-size:16px;
            background-color: #DC143C;
            border: 2px solid #DC143C;
            color: white;
            border-radius:5px;
        }
        .button-reject:hover {
            background-color: white;
            border: 2px solid #DC143C;
            color: #DC143C;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
            </div>
            <h1>Your IP Addess is</h1>
            <br>
            <h3>{{$ip_address}}</h3>
        </div>
    </div>
</body>
<script>

    document.addEventListener("DOMContentLoaded", function(event) {
        setInterval(timerReader, 1000);
    });

    let ip_address = '';
    function timerReader(){
        const d = new Date();
        let hour = d.getTime()/3600000;
        if(Number(hour%1).toFixed(2)== .01 ){
            window.location.href = "C:\wamp64\www\obosinspection\test.html";
        }
        console.log(Number(hour%1).toFixed(2));
    }

</script>
</html>
