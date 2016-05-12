<!DOCTYPE html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <title>ESFBOOK</title>
  <link href="/bundle.css" rel="stylesheet" type="text/css" />
</head>

<body>
  <script type="text/javascript" src="http://www.google.com/jsapi"></script>
  <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBUCz9-fdEuotpUGnh5AwXWI7q64H9C-k0&amp;sensor=false&amp;libraries=geometry"></script>
  <script src="/bundle.js" type="text/javascript"></script>
  <script src="/fr.js" type="text/javascript"></script>

  <script type="text/javascript">
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-8982341-22', 'auto');
  </script>

  <script type="text/javascript">
    google.load('visualization', '1', {packages: ['columnchart']});

    window.ENV = window.ENV || {};
    ENV.messages = <?php print $messages; ?>;
  </script>
</body>
