 /**
       * Sample JavaScript code for youtube.search.list
       * See instructions for running APIs Explorer code samples locally:
       * https://developers.google.com/explorer-help/code-samples#javascript
       */
 var player;
 var vidid;
function authenticate() {
  return gapi.auth2.getAuthInstance()
      .signIn({scope: "https://www.googleapis.com/auth/youtube.readonly"})
      .then(function() { console.log("Sign-in successful"); },
            function(err) { console.error("Error signing in", err); });
}
function loadClient() {
  gapi.client.setApiKey("");
  return gapi.client.load("https://www.googleapis.com/discovery/v1/apis/youtube/v3/rest")
      .then(function() { console.log("GAPI client loaded for API"); },
            function(err) { console.error("Error loading GAPI client for API", err); });
}
// Make sure the client is loaded and sign-in is complete before calling this method.
function execute() {
  var song=document.getElementById('textInput');
  return gapi.client.youtube.search.list({
    "part": [
      "snippet"
    ],
    "maxResults": 25,
    "q":song.value
  })
      .then(function(response) {
              // Handle the results here (response.result has the parsed body).
              console.log("Response", response);
              if(typeof player !== 'undefined'){
              player.destroy();}
              player = new YT.Player('player', {
  videoId: response.result.items[0].id.videoId,
  playerVars: {
    'autoplay': 1}
});

document.getElementById('player').style.display = 'none';
             // document.getElementById("youtube").src = "https://www.youtube.com/embed/"+response.result.items[0].id.videoId+"?autoplay=1";
            },
            function(err) { console.error("Execute error", err); });
            
            


}
gapi.load("client:auth2", function() {
  gapi.auth2.init({client_id: "523915827804-gd1hl10208ifhmmbi5j3lr8k574rq8ch.apps.googleusercontent.com"});
});




function stopVideo() {
player.pauseVideo();


}
function playVideo() {
player.playVideo();
}