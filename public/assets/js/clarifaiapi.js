
function checkimage() {
 

// Mark the form as invalid

  const fileInput = document.getElementById('map_art_imageFile_file');
     console.log(fileInput);
fileInput.addEventListener('change', (event) => {
  
  const file = event.target.files[0];
  const reader = new FileReader();

  reader.readAsDataURL(file);
  

// Once the image file is loaded, convert it to a base64-encoded string
reader.onloadend = () => {
const base64data = reader.result.replace(/^data:image\/(png|jpeg);base64,/, '');

// Construct a data URL that includes the base64-encoded image data
const dataUrl = `data:image/jpeg;base64,${base64data}`;
//console.log(dataUrl);
/*const fileInput = document.getElementById('fileInput');

fileInput.addEventListener('change', (event) => {
const file = event.target.files[0];
const formData = new FormData();
formData.append('image', file);*/
const raw = JSON.stringify({
"user_app_id": {
 "user_id": "clarifai",
 "app_id": "main"
},
"inputs": [
   {
       "data": {
           "image": {
               "base64": base64data
           }
       }
   }
]
});

const requestOptions = {
 method: 'POST',
 headers: {
     'Accept': 'application/json',
     'Authorization': 'Key ' + ''
 },
 body: raw

};

// NOTE: MODEL_VERSION_ID is optional, you can also call prediction with the MODEL_ID only
// https://api.clarifai.com/v2/models/{YOUR_MODEL_ID}/outputs
// this will default to the latest version_id

fetch(`https://api.clarifai.com/v2/models/moderation-recognition/versions/aa8be956dbaa4b7a858826a84253cab9/outputs`, requestOptions)
 .then(response => response.text())
 .then(result => {const resultatsplit = result.split(",");
          if(resultatsplit[33].search("safe") != -1){console.log(resultatsplit[32]+resultatsplit[33]);
           }
 else if(resultatsplit[33].search("drug") != -1){
  document.getElementById('map_art_imageFile_file').value ='';
  
   alert('this image goes against our guideline, it contains drug content');
     
 }
 else if(resultatsplit[33].search("suggestive") != -1){
  document.getElementById('map_art_imageFile_file').value ='';
 
  
  alert('this image goes against our guideline, it contains suggestive content');
    
 }
 else if(resultatsplit[33].search("explicit") != -1){
  document.getElementById('map_art_imageFile_file').value ='';
   
   alert('this image goes against our guideline, it contains explicit content');
    
 }
 else if(resultatsplit[33].search("gore") != -1){
  document.getElementById('map_art_imageFile_file').value ='';
   alert( 'this image goes against our guideline, it contains gore content');
     
 }})
 .catch(error => console.log('error', error));
};
});
}