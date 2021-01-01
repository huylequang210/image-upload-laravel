require('./bootstrap');
import Axios from 'axios';

const panelChangePassword = document.querySelector('.panel-changepassword');
const panelDeletedImage = document.querySelector('.panel-deletedimage');
const showDeletedimages = document.querySelector('.showDeletedimages');
const changePassword = document.querySelector('.changepassword');
const perfEntries = performance.getEntriesByType('navigation');

if(!panelChangePassword.classList.contains('showPanel')) {
  panelChangePassword.style.display = "none";
}

if(!panelDeletedImage.classList.contains('showPanel')) {
  panelDeletedImage.style.display = "none";
} else {
  showDeletedimages.innerHTML = "Hide";
}

// close deletedImage from session cache in back_forwatd
if (perfEntries.length && perfEntries[0].type === 'back_forward') {
  if(panelDeletedImage.classList.contains('showPanel')) {
    panelDeletedImage.style.display = "none";
    panelDeletedImage.classList.remove('showPanel');
  }
}

changePassword.addEventListener('click', function(e) {
  if(panelChangePassword.classList.contains('showPanel')) {
    panelChangePassword.style.display = "none";
    panelChangePassword.classList.remove('showPanel');
  } else {
    panelChangePassword.style.display = "block";
    panelChangePassword.classList.add('showPanel');
  } 
});

showDeletedimages.addEventListener('click', function(e) {
  if(panelDeletedImage.classList.contains('showPanel')) {
    panelDeletedImage.style.display = "none";
    panelDeletedImage.classList.remove('showPanel');
    showDeletedimages.innerHTML = 'Show';
  } else {
    panelDeletedImage.style.display = "flex";
    panelDeletedImage.classList.add('showPanel');
    showDeletedimages.innerHTML = "Hide";
  } 
});

// if(deleteImageButtons) {
//   deleteImageButtons.forEach(deleteButton => {
//     deleteButton.parentElement.addEventListener('submit', e => {
//       deleteImageEvent(e, e.target);
//     }, false);
//   });
// }


// async function deleteImageEvent(e, deleteImageForm) {
//   //e.preventDefault();
//   const deleteButton = deleteImageForm.querySelector('.deleteImageButton');
//   deleteButton.innerHTML = '...';
//   await Axios.delete(`${deleteImageForm.action}`)
//     .then(function(response) {
//       console.log(response);
//     }).then(function(error) {
//       console.log(error);
//     }); 
// }
