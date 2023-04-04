<?php
@unlink('token.txt');
?>
<html>
<head>
    <script>
        function parseQuery(queryString) {
            var query = {};
            var pairs = (queryString[0] === '?' ? queryString.substr(1) : queryString).split('&');
            for (var i = 0; i < pairs.length; i++) {
                var pair = pairs[i].split('=');
                query[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1] || '');
            }
            return query;
        }
        function setCookie(name,value,days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days*24*60*60*1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "")  + expires + "; path=/";
        }

        let hash = window.location.hash.substr(1);;
        let parts = parseQuery(hash);
        if(parts.access_token) {
            setCookie('yandex_token', parts.access_token, 30);
            window.location.href='http://127.0.0.1/';
        }
    </script>
</head>
<body></body>
</html>
