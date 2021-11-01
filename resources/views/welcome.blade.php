<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa&display=swap" rel="stylesheet">
    <style>
        html, body {
            font-family: 'Comfortaa', cursive;
            height: 100%;
            background: #ececec;
        }

        div#wrapper {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        div#panel {
            width: 800px;
            height: 400px;
            background: #fff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            flex-direction: column;
            padding-top: 72px;
            box-sizing: border-box;
        }

        h2 {
            font-size: 32px;
        }

        form#main {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        input.text-input {
            border: 1px solid #e1e1e1;
            padding: 12px 24px;
            width: 400px;
            border-radius: 5px;
        }

        input#cut {
            background: #72c791;
            color: #fff;
            font-size: 21px;
            border: none;
            border-radius: 5px;
            margin-left: 12px;
            padding: 8px 24px;
        }

        input#cut:hover {
            cursor: pointer;
            background: #437b57;
        }

        .link-box {
            text-align: center;
            font-size: 16px;
            padding: 6px;
            color: #000;
            background: #17c557;
            margin-top: 18px;
            width: 80%;
            visibility: hidden;
        }

        .link-box h5 {
            display: none;
        }

        .link-box.success {
            background: #17c557;
            visibility: visible;
        }

        .link-box.success h5 {
            display: block;
        }

        .link-box.fail {
            background: #ff3838;
            visibility: visible;
        }
    </style>
</head>
<body>
<div id="wrapper">
    <div id="panel">
        <h2>LinkCutter</h2>
        <form id="main">
            <input type="text" name="url" class="text-input" placeholder="Paste a long url">
            <input type="submit" id="cut" value="Shorten">
        </form>
        <div class="link-box">
            <h5>Your link:</h5>
            <p></p>
        </div>
    </div>
</div>
<script
    src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
    crossorigin="anonymous"></script>
<script>
$('#main').submit(function () {
    let input = $('input[name=url]');
    $.ajax({
        type: "POST",
        url: "/api/cut",
        data: {
            url: input.val()
        },
        dataType: "json",
        success: function(response) {
            $('.link-box').removeClass('fail').addClass('success').find('p').text(response.data.link);
        },
        error: function(response) {
            $('.link-box').removeClass('success').addClass('fail').find('p').text(response.responseJSON.message);
        },
    });
    input.val('');
    return false;
});
</script>
</body>
</html>
