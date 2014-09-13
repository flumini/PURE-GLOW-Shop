{extends file="parent:frontend/index/header.tpl"}
{block name="frontend_index_header_css_screen" append}


	<link type="text/css" media="screen, projection" rel="stylesheet" href="{link file='frontend/_resources/styles/emotion_pureglow.css'}" />
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,600,700' rel='stylesheet' type='text/css'>
    {literal}
    <script>(function() {
  var _fbq = window._fbq || (window._fbq = []);
  if (!_fbq.loaded) {
    var fbds = document.createElement('script');
    fbds.async = true;
    fbds.src = '//connect.facebook.net/en_US/fbds.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(fbds, s);
    _fbq.loaded = true;
  }
  _fbq.push(['addPixelId', '729902737047434']);
})();
window._fbq = window._fbq || [];
window._fbq.push(['track', 'PixelInitialized', {}]);
</script>
<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?id=729902737047434&amp;ev=PixelInitialized" /></noscript>


<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-3022126-11', 'auto');
  ga('send', 'pageview');
  ga('require', 'displayfeatures');


</script>
{/literal}

{/block}
 