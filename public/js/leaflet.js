const latitude = document.querySelector('meta[name="latitude"]').content;
const longitude = document.querySelector('meta[name="longitude"]').content;
const logo = document.querySelector('meta[name="logo"]').content;
var mymap = L.map('mapid').setView([latitude, longitude], 12);

L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
    attribution: 'Map data &copy; <a target="_blank" href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a target="_blank" href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a target="_blank" href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 18,
    id: 'mapbox/streets-v11',
    tileSize: 512,
    zoomOffset: -1,
    accessToken: 'pk.eyJ1IjoibWF1bGFuYWtldmlucCIsImEiOiJjazZ0M3Mydm8wMXduM2txZjB4dXJrNXR6In0.XJeEw8u_Tpyk54hcU5P5xA'
}).addTo(mymap);

var marker = L.marker([latitude, longitude]).addTo(mymap);
marker.bindPopup(`
<img src="`+ logo +`" alt="logo" heigth="100px" width="100px">
<p class="text-center">We are here<p>`).openPopup();
